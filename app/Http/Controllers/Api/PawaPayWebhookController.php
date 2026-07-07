<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Commande;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Support\Facades\Mail;

class PawaPayWebhookController extends Controller
{
    /**
     * Réceptionne les callbacks (webhooks) envoyés par PawaPay.
     *
     * PawaPay appelle cette URL avec une requête POST contenant le statut final
     * du dépôt (COMPLETED, FAILED, CANCELLED, TIMED_OUT).
     *
     * La route doit être exclue du middleware CSRF (voir bootstrap/app.php).
     */
    public function handleDepositCallback(Request $request): JsonResponse
    {
        // 1. Logger la requête entrante pour le débogage
        Log::info('PawaPay Webhook received', [
            'headers' => $request->headers->all(),
            'body'    => $request->all(),
        ]);

        $data = $request->json()->all();

        // 2. Valider la présence des champs obligatoires
        if (empty($data['depositId']) || empty($data['status'])) {
            Log::warning('PawaPay Webhook: missing depositId or status', ['data' => $data]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $depositId = $data['depositId'];
        $status    = $data['status']; // COMPLETED | FAILED | CANCELLED | TIMED_OUT

        // 3. Retrouver la commande correspondante
        $commande = Commande::where('pawapay_deposit_id', $depositId)->first();

        if (!$commande) {
            Log::warning('PawaPay Webhook: no commande found for depositId', ['depositId' => $depositId]);
            // On répond 200 quand même pour éviter que PawaPay ne renvoie en boucle
            return response()->json(['message' => 'Deposit not found, ignored'], 200);
        }

        // 4. Traiter selon le statut reçu
        switch ($status) {
            case 'COMPLETED':
                $this->handleCompleted($commande, $data);
                break;

            case 'FAILED':
            case 'CANCELLED':
            case 'TIMED_OUT':
                $this->handleFailed($commande, $status, $data);
                break;

            default:
                // Statuts intermédiaires (PROCESSING, ACCEPTED) — on ignore
                Log::info('PawaPay Webhook: intermediate status, no action', [
                    'depositId' => $depositId,
                    'status'    => $status,
                ]);
        }

        // 5. Toujours retourner 200 pour confirmer la réception à PawaPay
        return response()->json(['message' => 'Callback processed'], 200);
    }

    /**
     * Le paiement est COMPLÉTÉ : la commande passe en "payée" et l'email est envoyé.
     */
    private function handleCompleted(Commande $commande, array $data): void
    {
        // Éviter le double traitement si le webhook est envoyé deux fois
        if ($commande->isPaid()) {
            Log::info('PawaPay Webhook: commande already paid, skipping', ['commande_id' => $commande->id]);
            return;
        }

        // Mettre à jour le statut de paiement
        $commande->update([
            'payment_status'    => Commande::PAY_PAYEE,
            'payment_reference' => $data['depositId'],
            'statut'            => 'confirmée',
        ]);

        Log::info('PawaPay Webhook: commande marked as PAID', [
            'commande_id' => $commande->id,
            'depositId'   => $data['depositId'],
        ]);

        // Envoyer l'email de confirmation de paiement automatiquement
        try {
            $commande->load(['user', 'produits']);
            if ($commande->user && $commande->user->email) {
                Mail::to($commande->user->email)->send(new PaymentConfirmationMail($commande));
                Log::info('PawaPay: payment confirmation email sent', ['email' => $commande->user->email]);
            }
        } catch (\Exception $e) {
            // L'email est non-bloquant : on logue l'erreur mais on ne fait pas échouer le webhook
            Log::error('PawaPay: failed to send confirmation email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Le paiement a ÉCHOUÉ ou a été annulé.
     */
    private function handleFailed(Commande $commande, string $status, array $data): void
    {
        $failureCode = $data['failureReason']['failureCode'] ?? $status;

        $commande->update([
            'payment_status' => Commande::PAY_REFUSEE,
        ]);

        Log::warning('PawaPay Webhook: payment failed', [
            'commande_id' => $commande->id,
            'status'      => $status,
            'failureCode' => $failureCode,
        ]);
    }
}

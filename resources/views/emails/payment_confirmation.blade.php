<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de paiement</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; color: #111; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header { background: #16a34a; color: #fff; text-align: center; padding: 30px 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 20px; }
        .content h2 { margin-top: 0; font-size: 20px; color: #111; }
        .summary { background: #f3f4f6; border-radius: 8px; padding: 15px; margin: 20px 0; }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .row:last-child { border-bottom: none; font-weight: bold; font-size: 18px; padding-top: 15px; }
        .btn { display: inline-block; background: #111; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Paiement reçu avec succès !</h1>
        </div>
        <div class="content">
            <h2>Bonjour {{ $commande->user->name }},</h2>
            <p>Nous vous confirmons la réception de votre paiement Mobile Money pour la commande <strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong>.</p>
            
            <div class="summary">
                <div class="row">
                    <span>Opérateur :</span>
                    <span>{{ $commande->payment_method_label }}</span>
                </div>
                <div class="row">
                    <span>Numéro :</span>
                    <span>+{{ $commande->mobile_money_number }}</span>
                </div>
                <div class="row">
                    <span>Total payé :</span>
                    <span>{{ number_format($commande->total, 0, ',', ' ') }} CDF</span>
                </div>
            </div>

            <p>Votre commande est maintenant en cours de préparation. Vous serez notifié lors de son expédition.</p>

            <div style="text-align: center;">
                <a href="{{ route('compte.show') }}" class="btn">Voir ma commande</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Stepora. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>

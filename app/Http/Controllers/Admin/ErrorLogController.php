<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    /**
     * Liste des erreurs.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'en_attente');

        $errors = ErrorLog::where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.errors.index', compact('errors', 'status'));
    }

    /**
     * Détail d'une erreur spécifique.
     */
    public function show(ErrorLog $error)
    {
        return view('admin.errors.show', compact('error'));
    }

    /**
     * Marquer comme résolue (ou en attente).
     */
    public function update(Request $request, ErrorLog $error)
    {
        $request->validate([
            'status' => 'required|in:en_attente,resolu'
        ]);

        $error->update(['status' => $request->status]);

        return back()->with('success', 'Statut de l\'erreur mis à jour avec succès.');
    }

    /**
     * Supprimer une erreur définitivement.
     */
    public function destroy(ErrorLog $error)
    {
        $error->delete();

        return redirect()->route('admin.errors.index')
            ->with('success', 'Erreur supprimée avec succès.');
    }
}

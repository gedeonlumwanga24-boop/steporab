<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', ErrorLog::STATUS_PENDING);

        if (! in_array($status, [ErrorLog::STATUS_PENDING, ErrorLog::STATUS_RESOLVED], true)) {
            $status = ErrorLog::STATUS_PENDING;
        }

        $errors = ErrorLog::where('status', $status)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.errors.index', compact('errors', 'status'));
    }

    public function show(ErrorLog $error)
    {
        return view('admin.errors.show', compact('error'));
    }

    public function update(Request $request, ErrorLog $error)
    {
        $request->validate([
            'status' => 'required|in:'.ErrorLog::STATUS_PENDING.','.ErrorLog::STATUS_RESOLVED,
        ]);

        $error->update(['status' => $request->status]);

        return back()->with('success', 'Statut de l\'erreur mis à jour avec succès.');
    }

    public function destroy(ErrorLog $error)
    {
        $error->delete();

        return redirect()->route('admin.errors.index')
            ->with('success', 'Erreur supprimée avec succès.');
    }
}

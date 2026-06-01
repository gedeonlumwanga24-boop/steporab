<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->with('client')->latest();

        if ($request->has('trashed')) {
            $query->onlyTrashed();
        }

        $clients = $query->paginate(15);
                       
        return view('admin.clients.index', compact('clients'));
    }

    public function destroy($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->delete();

        return redirect()->back()->with('success', 'Client supprimé avec succès.');
    }

    public function restore($id)
    {
        $client = User::onlyTrashed()->where('role', 'client')->findOrFail($id);
        $client->restore();

        return redirect()->back()->with('success', 'Client restauré avec succès.');
    }
}

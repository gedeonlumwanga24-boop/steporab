<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')
                       ->with('client') // Charge la relation Client (profil)
                       ->latest()
                       ->paginate(15);
                       
        return view('admin.clients.index', compact('clients'));
    }
}

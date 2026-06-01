<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stepora Admin | @yield('title', 'Dashboard')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
</head>
<body class="admin-body">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-header">
            STEPORA ADMIN
        </a>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('admin.produits.index') }}" class="admin-nav-item {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i> Produits
            </a>
            <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-folder"></i> Catégories
            </a>
            <a href="{{ route('admin.commandes.index') }}" class="admin-nav-item {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cart-shopping"></i> Commandes
            </a>
            <a href="{{ route('admin.clients.index') }}" class="admin-nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Clients
            </a>
            <a href="{{ route('admin.messages.index') }}" class="admin-nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope"></i> Messages
            </a>
            <a href="{{ route('admin.config.index') }}" class="admin-nav-item {{ request()->routeIs('admin.config.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Configuration
            </a>
            <a href="{{ route('admin.errors.index') }}" class="admin-nav-item {{ request()->routeIs('admin.errors.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bug"></i> Gestion des erreurs
            </a>
        </nav>
        <div class="admin-sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" class="admin-logout-form">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 8px;"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <h1 class="admin-topbar-title">@yield('title', 'Dashboard')</h1>
            <div class="admin-topbar-actions">
                <a href="{{ url('/') }}" class="btn-visit-site" target="_blank">
                    <i class="fa-solid fa-globe"></i> Voir le site
                </a>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="admin-content">
            @if(session('success'))
                <div class="admin-alert admin-alert-success">
                    <i class="fa-solid fa-check-circle" style="margin-right: 5px;"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="admin-alert admin-alert-error">
                    <i class="fa-solid fa-circle-exclamation" style="margin-right: 5px;"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>

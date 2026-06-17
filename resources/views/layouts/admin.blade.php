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
        <div class="admin-sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">STEPORA ADMIN</a>
            <button id="sidebarToggle" class="btn-sidebar-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.produits.index') }}" class="admin-nav-item {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i> <span>Produits</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-folder"></i> <span>Catégories</span>
            </a>
            @php
                $pendingPaymentsCount = \App\Models\Commande::where('payment_status', \App\Models\Commande::PAY_EN_VERIF)->count();
            @endphp
            <a href="{{ route('admin.commandes.index') }}" class="admin-nav-item {{ request()->routeIs('admin.commandes.index') ? 'active' : '' }}" style="display: flex; align-items: center;">
                <i class="fa-solid fa-cart-shopping" style="margin-right: 10px; width: 20px; text-align: center;"></i> <span>Commandes</span>
                @if($pendingPaymentsCount > 0)
                    <span class="badge-count" style="background: #dc2626; color: white; padding: 0.1rem 0.4rem; border-radius: 999px; font-size: 0.7rem; font-weight: bold; margin-left: auto;">{{ $pendingPaymentsCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.commandes.paiements') }}" class="admin-nav-item sub-item {{ request()->routeIs('admin.commandes.paiements') ? 'active' : '' }}" style="display: flex; align-items: center;">
                <i class="fa-solid fa-clock" style="margin-right: 10px; width: 20px; text-align: center; color: {{ $pendingPaymentsCount > 0 ? '#dc2626' : 'inherit' }};"></i>
                <span class="nav-text" style="color: {{ $pendingPaymentsCount > 0 ? '#dc2626' : 'inherit' }}; font-size: 0.88rem;">Paiements en attente</span>
                @if($pendingPaymentsCount > 0)
                    <span class="badge-count" style="background: #dc2626; color: white; padding: 0.1rem 0.4rem; border-radius: 999px; font-size: 0.7rem; font-weight: bold; margin-left: auto;">{{ $pendingPaymentsCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.clients.index') }}" class="admin-nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> <span>Clients</span>
            </a>
            @php
                $unreadMessagesCount = \App\Models\Message::where('status', 'non lu')->count();
            @endphp
            <a href="{{ route('admin.messages.index') }}" class="admin-nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" style="display: flex; align-items: center;">
                <i class="fa-solid fa-envelope" style="margin-right: 10px; width: 20px; text-align: center;"></i> <span>Messages</span>
                @if($unreadMessagesCount > 0)
                    <span class="badge-count" style="background: #3b82f6; color: white; padding: 0.1rem 0.4rem; border-radius: 999px; font-size: 0.7rem; font-weight: bold; margin-left: auto;">{{ $unreadMessagesCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.newsletter.create') }}" class="admin-nav-item {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}" style="display: flex; align-items: center;">
                <i class="fa-solid fa-paper-plane" style="margin-right: 10px; width: 20px; text-align: center;"></i>
                <span>Notifications</span>
            </a>
            <a href="{{ route('admin.config.index') }}" class="admin-nav-item {{ request()->routeIs('admin.config.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> <span>Configuration</span>
            </a>
            @php
                $unresolvedErrorsCount = \App\Models\ErrorLog::where('status', \App\Models\ErrorLog::STATUS_PENDING)->count();
            @endphp
            <a href="{{ route('admin.errors.index') }}" class="admin-nav-item {{ request()->routeIs('admin.errors.*') ? 'active' : '' }}" style="display: flex; align-items: center;">
                <i class="fa-solid fa-bug" style="margin-right: 10px; width: 20px; text-align: center;"></i> <span>Gestion des erreurs</span>
                @if($unresolvedErrorsCount > 0)
                    <span class="badge-count" style="background: #ef4444; color: white; padding: 0.1rem 0.4rem; border-radius: 999px; font-size: 0.7rem; font-weight: bold; margin-left: auto;">{{ $unresolvedErrorsCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.admins.index') }}" class="admin-nav-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield"></i> <span>Administrateurs</span>
            </a>
        </nav>
        <div class="admin-sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" class="admin-logout-form">
                @csrf
                <button type="submit" title="Déconnexion">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 8px;"></i> <span>Déconnexion</span>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('sidebarToggle');
            const body = document.querySelector('.admin-body');
            
            // Check localStorage for sidebar state
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                body.classList.add('sidebar-collapsed');
            }

            // Toggle Sidebar
            toggleBtn.addEventListener('click', () => {
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', body.classList.contains('sidebar-collapsed'));
            });
        });
    </script>
</body>
</html>

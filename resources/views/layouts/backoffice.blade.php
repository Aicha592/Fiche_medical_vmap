<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice VMAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bo-bg: #f4f1ec;
            --bo-surface: #ffffff;
            --bo-ink: #1f2a26;
            --bo-muted: #6b7b75;
            --bo-accent: #356a45;
            --bo-accent-2: #b1c56a;
            --bo-border: #e4e0d8;
            --bo-shadow: 0 12px 30px rgba(20, 32, 24, 0.08);
            --pagination-bg: #ffffff;
            --pagination-border: #e4e0d8;
            --pagination-text: #2a3a34;
            --pagination-accent: #356a45;
        }

        body {
            font-family: "Manrope", "Segoe UI", sans-serif;
            background: radial-gradient(circle at 10% 0%, #f7f4ef 0%, var(--bo-bg) 45%, #ece6dc 100%);
            color: var(--bo-ink);
        }

        .bo-shell {
            min-height: 100vh;
            display: flex;
        }

        .bo-sidebar {
            width: 260px;
            background: linear-gradient(165deg, #21432b 0%, #2f5a3b 55%, #2a4a36 100%);
            color: #f2f5ef;
            padding: 28px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .bo-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 1rem;
            margin-bottom: 28px;
        }

        .bo-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 14px;
            padding: 10px 14px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }

        .bo-nav .nav-link.active,
        .bo-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .bo-content {
            flex: 1;
            padding: 32px 36px;
        }

        .bo-topbar {
            background: var(--bo-surface);
            border: 1px solid var(--bo-border);
            border-radius: 20px;
            padding: 16px 20px;
            box-shadow: var(--bo-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .bo-card {
            background: var(--bo-surface);
            border: 1px solid var(--bo-border);
            border-radius: 18px;
            padding: 18px 20px;
            box-shadow: var(--bo-shadow);
        }

        .bo-kpi {
            border-left: 4px solid var(--bo-accent);
        }

        .bo-pill {
            background: rgba(177, 197, 106, 0.2);
            color: #2e4d36;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .btn-bo {
            background: var(--bo-accent);
            border: none;
            color: #fff;
        }

        .btn-bo:hover {
            background: #2c5b3b;
            color: #fff;
        }

        .table thead th {
            color: var(--bo-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--bo-border);
        }

        .bo-muted {
            color: var(--bo-muted);
        }

        @media (max-width: 992px) {
            .bo-shell {
                flex-direction: column;
            }

            .bo-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
        }

        .pagination-wrap {
            display: flex;
            justify-content: center;
            margin-top: 16px;
        }

        .pagination {
            gap: 6px;
            flex-wrap: wrap;
            margin: 0;
        }

        .pagination .page-link {
            border-radius: 999px;
            border: 1px solid var(--pagination-border);
            color: var(--pagination-text);
            padding: 6px 12px;
            min-width: 38px;
            text-align: center;
            background: var(--pagination-bg);
            box-shadow: 0 6px 18px rgba(20, 32, 24, 0.08);
        }

        .pagination .page-item.active .page-link {
            background: var(--pagination-accent);
            color: #fff;
            border-color: var(--pagination-accent);
        }

        .pagination .page-item.disabled .page-link {
            color: #9aa69a;
            border-color: #e6ebe3;
            box-shadow: none;
        }

        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(53, 106, 69, 0.2);
        }
    </style>
</head>
<body>
    <div class="bo-shell">
        <aside class="bo-sidebar">
            <div class="bo-brand">VMAP Backoffice</div>
            <nav class="nav flex-column bo-nav">
                <a class="nav-link {{ request()->routeIs('backoffice.dashboard') ? 'active' : '' }}"
                   href="{{ route('backoffice.dashboard') }}">
                    Tableau de bord
                </a>
                @if(auth()->user()->isDoctor() || auth()->user()->isRh())
                    <a class="nav-link {{ request()->routeIs('backoffice.medical-records.*') ? 'active' : '' }}"
                       href="{{ route('backoffice.medical-records.index') }}">
                        Fiches médicales
                    </a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('backoffice.users.*') ? 'active' : '' }}"
                       href="{{ route('backoffice.users.index') }}">
                        Utilisateurs
                    </a>
                    <a class="nav-link {{ request()->routeIs('backoffice.employees.*') ? 'active' : '' }}"
                       href="{{ route('backoffice.employees.index') }}">
                        Employés
                    </a>
                @endif
            </nav>
        </aside>

        <main class="bo-content">
            <div class="bo-topbar">
                <div>
                    <div class="fw-semibold">Bonjour {{ auth()->user()->name ?? auth()->user()->email ?? 'Utilisateur' }}</div>
                    <div class="bo-muted">
                        @if(auth()->user()->isAdmin())
                            Administrateur
                        @elseif(auth()->user()->isDoctor())
                            Médecin
                        @elseif(auth()->user()->isRh())
                            RH / QHSE
                        @else
                            Utilisateur
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-dark btn-sm">Déconnexion</button>
                </form>
            </div>

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

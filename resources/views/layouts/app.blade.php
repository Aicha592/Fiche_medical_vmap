<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMAP 2026</title>

    <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <style>
        :root{
            --primary: #456f48;
            --secondary: #b0cc66;
            --pagination-bg: #ffffff;
            --pagination-border: #dfe5d9;
            --pagination-text: #2d3b33;
            --pagination-accent: #456f48;
        }
        .bg-primary-custom{ background: var(--primary); }
        .text-primary-custom{ color: var(--primary); }
        .btn-primary-custom{ background: var(--primary); border: 0; }
        .btn-secondary-custom{ background: var(--secondary); border: 0; }

        .modal-fullscreen .modal-body {
    max-height: 75vh;
    overflow-y: auto;
}

.navbar-brand img {
      height: 80px;
      margin-right: 10px;
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
        box-shadow: 0 6px 18px rgba(69, 111, 72, 0.08);
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
        box-shadow: 0 0 0 0.2rem rgba(69, 111, 72, 0.2);
    }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('logo.png') }}" alt="Logo SONAGED">
    </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <!-- <li class="nav-item">
                    <a class="nav-link text-white" href="#">Accueil</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link text-white" href="#">Historique</a>
                </li> -->
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">

<!-- @if(Auth::check())
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">
            🩺 VMAP 2026
        </span>

        <div class="ms-auto d-flex align-items-center text-white">
            <span class="me-3">
                Dr {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>
@endif -->

    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

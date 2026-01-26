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

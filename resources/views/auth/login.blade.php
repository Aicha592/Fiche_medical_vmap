<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion – VMAP</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #afcb61;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-container {
        background-color: #fff;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.2);
        width: 100%;
        max-width: 420px;
    }

    .btn-custom {
        background-color: #456f48;
        color: white;
        font-weight: bold;
    }

    .btn-custom:hover {
        background-color: #afcb61;
        color: #456f48;
    }
</style>
</head>
<body>

<div class="form-container">

    <h3 class="text-center mb-4 fw-bold" style="color:#456f48;">
        Connexion 
    </h3>

    {{-- Erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-bold">Mot de passe</label>
            <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                required
            >
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-custom">
                Se connecter
            </button>
        </div>

    </form>

</div>

</body>
</html>

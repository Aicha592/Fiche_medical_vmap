<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vérification OTP – VMAP</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header text-center fw-bold" style="color:#456f48;">
                    Vérifiez votre code OTP
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label">Code OTP</label>
                            <input type="text" name="otp" id="otp" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom">Vérifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

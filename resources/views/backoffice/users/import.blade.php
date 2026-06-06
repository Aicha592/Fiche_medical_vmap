@extends('layouts.backoffice')

@section('content')
    <div class="mb-4">
        <div>
            <h4 class="mb-1">Importer des utilisateurs</h4>
            <div class="bo-muted">Importez plusieurs utilisateurs à partir d'un fichier CSV ou Excel.</div>
        </div>
    </div>

    <div class="bo-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Erreur:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <form method="POST" action="{{ route('backoffice.users.import.process') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="file" class="form-label">Fichier (CSV ou Excel)</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file"
                            name="file" accept=".csv,.xlsx,.xls" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Formats supportés: CSV, XLS, XLSX (max. 5 MB)
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-bo">Importer</button>
                        <a href="{{ route('backoffice.users.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <a href="{{ route('backoffice.users.template') }}" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Télécharger le modèle
                        </a>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <div class="alert alert-info">
                    <h6 class="alert-heading">Format du fichier</h6>
                    <p class="mb-2">Le fichier doit contenir les colonnes suivantes:</p>
                    <ul class="mb-0 small">
                        <li><strong>Nom</strong> (requis)</li>
                        <li><strong>Email</strong> (requis)</li>
                        <li><strong>Téléphone</strong> (optionnel)</li>
                        <li><strong>Rôle</strong> (requis): admin, med-taf, rh, ch, doctor</li>
                        <li><strong>Mot de passe</strong> (optionnel)</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <h6 class="alert-heading">Remarques importantes</h6>
                    <ul class="mb-0 small">
                        <li>Si l'email existe déjà, l'utilisateur sera mis à jour</li>
                        <li>Si le mot de passe n'est pas fourni, un mot de passe par défaut sera utilisé</li>
                        <li>Les lignes invalides seront ignorées</li>
                        <li>Cliquez sur "Télécharger le modèle" pour voir un exemple</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

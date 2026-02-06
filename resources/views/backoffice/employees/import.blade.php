@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Importer des employés</h4>
            <div class="bo-muted">Importer un CSV pour créer ou mettre à jour des employés.</div>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('backoffice.employees.index') }}">Retour</a>
    </div>

    <div class="bo-card mb-4">
        <form method="POST" action="{{ route('backoffice.employees.import.process') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Fichier Excel / CSV</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">
                @error('file')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn btn-bo" type="submit">Importer</button>
        </form>
    </div>

    <div class="bo-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="fw-semibold">Format attendu</div>
            <a class="btn btn-outline-dark btn-sm" href="{{ route('backoffice.employees.import.template') }}">Télécharger le modèle CSV</a>
        </div>
        <p class="bo-muted mb-2">Les colonnes obligatoires sont: <strong>matricule</strong>, <strong>nom</strong>, <strong>prenom</strong>.</p>
        <p class="bo-muted mb-3">Les autres colonnes possibles:</p>
        <code class="d-block mb-2">matricule;sexe;prenom;nom;date_naissance;date_embauche;emploi_occupe;direction;delegation_r;service;unite_communale;telephone;date_passage</code>
        <div class="bo-muted">Formats de date acceptés: <code>YYYY-MM-DD</code> ou <code>DD/MM/YYYY</code>.</div>
    </div>
@endsection

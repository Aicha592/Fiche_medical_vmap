@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Nouvel utilisateur</h4>
            <div class="bo-muted">Création d’un compte backoffice.</div>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('backoffice.users.index') }}">Retour</a>
    </div>

    <div class="bo-card">
        @if($errors->any())
            <div class="alert alert-danger">
                Merci de corriger les champs en erreur.
            </div>
        @endif

        <form method="POST" action="{{ route('backoffice.users.store') }}">
            @csrf
            @include('backoffice.users._form', ['roles' => $roles])
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a class="btn btn-outline-dark" href="{{ route('backoffice.users.index') }}">Annuler</a>
                <button class="btn btn-bo" type="submit">Créer</button>
            </div>
        </form>
    </div>
@endsection

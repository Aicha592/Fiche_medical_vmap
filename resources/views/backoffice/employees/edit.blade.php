@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Modifier l’employé</h4>
            <div class="bo-muted">Mettre à jour les informations de l’employé.</div>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('backoffice.employees.index') }}">Retour</a>
    </div>

    <div class="bo-card">
        <form method="POST" action="{{ route('backoffice.employees.update', $employee) }}">
            @csrf
            @method('PUT')
            @include('backoffice.employees._form', ['employee' => $employee])

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-bo" type="submit">Mettre à jour</button>
                <a class="btn btn-outline-dark" href="{{ route('backoffice.employees.index') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection

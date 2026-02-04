@extends('layouts.app')

@section('content')
<style>
    :root {
        --green-dark: #467049;
        --green-light: #aeca5f;
        --ink: #000000;
        --ink-soft: #626160;
        --paper: #ffffff;
        --shadow-soft: 0 8px 24px rgba(0, 0, 0, 0.12);
        --radius-lg: 18px;
        --radius-md: 12px;
        --radius-sm: 8px;
        --font-title: "LOEW HEAVY", "LOEW Heavy", "Times New Roman", serif;
        --font-strong: "ALLER BOLD", "Aller Bold", "Arial Black", sans-serif;
        --font-body: "ALLER REGULAR", "Aller Regular", "Arial", sans-serif;
    }

    .qhse-hero {
        background: linear-gradient(130deg, rgba(70, 112, 73, 0.12), rgba(174, 202, 95, 0.25));
        border-radius: var(--radius-lg);
        padding: 22px 26px;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(70, 112, 73, 0.2);
    }

    .qhse-hero h3 {
        font-family: var(--font-title);
        letter-spacing: 0.7px;
        color: var(--green-dark);
        margin-bottom: 6px;
    }

    .qhse-hero p {
        font-family: var(--font-body);
        color: var(--ink-soft);
        margin: 0;
    }

    .qhse-card {
        background: var(--paper);
        border-radius: var(--radius-md);
        padding: 20px 24px;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(70, 112, 73, 0.12);
    }

    .btn-qhse {
        background-color: var(--green-dark);
        border-color: var(--green-dark);
        color: #fff;
        font-family: var(--font-strong);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 0.75rem;
    }

    .badge-qhse {
        background: rgba(70, 112, 73, 0.12);
        color: var(--green-dark);
        border: 1px solid rgba(70, 112, 73, 0.3);
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-family: var(--font-strong);
    }
</style>

<div class="qhse-hero mb-4">
    <h3>Questionnaire QHSE / SST</h3>
    <p>Accès RH uniquement — compléter et mettre à jour les sections QHSE des visites médicales.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="qhse-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Agent</th>
                    <th>Matricule</th>
                    <th>QHSE</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $visit)
                    @php
                        $agentName = trim(($visit->user->nom ?? '') . ' ' . ($visit->user->prenom ?? ''));
                        if ($agentName === '') {
                            $agentName = $visit->user->name ?? '—';
                        }
                        $qhseFilled = !empty($visit->appreciation_poste) || !empty($visit->synthese_risque);
                    @endphp
                    <tr>
                        <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                        <td>{{ $agentName }}</td>
                        <td>{{ $visit->user->matricule ?? '—' }}</td>
                        <td>
                            <span class="badge-qhse">
                                {{ $qhseFilled ? 'Complété' : 'À compléter' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-qhse" href="{{ route('medical-visits.qhse.edit', $visit) }}">
                                Ouvrir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune visite disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $visits->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')

<div class="medical-hero mb-4 text-center">
    <h3>FICHE MÉDICALE – VISITE MÉDICALE ANNUELLE DU PERSONNEL (VMAP 2026)</h3>
    <p>Recherchez un agent puis complétez la fiche médicale en quelques étapes.</p>
</div>

<div class="medical-search">
    <label class="form-label fw-bold">Rechercher un agent</label>
    <input type="text" id="search" class="form-control" placeholder="Nom, prénom ou matricule">

    <ul class="list-group mt-3" id="results"></ul>
</div>

<div class="medical-search mt-4">
    <label class="form-label fw-bold">Dernières visites enregistrées</label>

    @if($recentVisits->isEmpty())
        <p class="mb-0 text-muted">Aucune visite enregistrée pour le moment.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Matricule</th>
                        <th>Avis</th>
                        <th class="text-end">PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentVisits as $visit)
                        @php
                            $agentName = trim(($visit->user->nom ?? '') . ' ' . ($visit->user->prenom ?? ''));
                            if ($agentName === '') {
                                $agentName = $visit->user->name ?? '—';
                            }
                        @endphp
                        <tr>
                            <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                            <td>{{ $agentName }}</td>
                            <td>{{ $visit->user->matricule ?? '—' }}</td>
                            <td>{{ $visit->avis ?? '—' }}</td>
                            <td class="text-end">
                                <a class="btn btn-secondary-custom btn-sm" href="{{ route('medical-visits.pdf', $visit) }}" target="_blank">
                                    Télécharger
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@include('medical_visits.form')

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('search').addEventListener('keyup', function () {
        let query = this.value;

        if (query.length < 2) {
            document.getElementById('results').innerHTML = '';
            return;
        }

        fetch(`/agents/search?q=${query}`)
            .then(res => res.json())
            .then(data => {
                let list = '';
                data.forEach(user => {
                    list += `
                        <li class="list-group-item list-group-item-action"
                            onclick="selectAgent(${JSON.stringify(user).replace(/"/g, '&quot;')})">
                            <strong>${user.nom} ${user.prenom}</strong> – ${user.matricule}
                        </li>
                    `;
                });
                document.getElementById('results').innerHTML = list;
            });
    });

});

function selectAgent(user) {
    document.getElementById('agent_nom').value = user.nom + ' ' + user.prenom;
    document.getElementById('agent_matricule').value = user.matricule;
    document.getElementById('agent_sexe').value = user.sexe;
    document.getElementById('agent_age').value = user.age;
    document.getElementById('agent_direction').value = user.direction;
    document.getElementById('agent_poste').value = user.poste;
    document.getElementById('agent_anciennete').value = user.anciennete;
    document.getElementById('agent_site').value = user.site;
    document.getElementById('agent_user_id').value = user.id;

    let modal = new bootstrap.Modal(document.getElementById('visitModal'));
    modal.show();
}

</script>
@endsection

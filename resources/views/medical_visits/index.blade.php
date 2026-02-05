@extends('layouts.app')

@section('content')
    <div class="mb-4 text-center medical-hero">
        <h3>FICHE MÉDICALE – VISITE MÉDICALE ANNUELLE DU PERSONNEL (VMAP 2026)</h3>
        <p>Recherchez un agent puis complétez la fiche médicale en quelques étapes.</p>
    </div>

    <div class="medical-search">
        <label class="form-label fw-bold">Rechercher un agent</label>
        <input type="text" id="search" class="form-control" placeholder="Nom, prénom ou matricule">

        <ul class="mt-3 list-group" id="results"></ul>
    </div>

    {{-- <div class="mt-4 medical-search">
        <label class="form-label fw-bold">Dernières visites enregistrées</label>

        @if ($recentVisits->isEmpty())
            <p class="mb-0 text-muted">Aucune visite enregistrée pour le moment.</p>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
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
                        @foreach ($recentVisits as $visit)
                            @php
                                $agentName = trim(($visit->employee->nom ?? '') . ' ' . ($visit->employee->prenom ?? ''));
                                if ($agentName === '') {
                                    $agentName = '—';
                                }
                            @endphp
                            <tr>
                                <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                                <td>{{ $agentName }}</td>
                                <td>{{ $visit->employee->matricule ?? '—' }}</td>
                                <td>{{ $visit->avis ?? '—' }}</td>
                                <td class="text-end">
                                    <a class="btn btn-secondary-custom btn-sm"
                                        href="{{ route('medical-visits.pdf', $visit) }}" target="_blank">

                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div> --}}

    @include('medical_visits.form')
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('search').addEventListener('keyup', function() {
                let query = this.value;

                if (query.length < 2) {
                    document.getElementById('results').innerHTML = '';
                    return;
                }

                fetch(`/employees/search?q=${query}`)
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
            document.getElementById('agent_date_naissance').value = user.date_naissance ?? '';
            document.getElementById('agent_date_embauche').value = user.date_embauche ?? '';
            document.getElementById('agent_direction').value = user.direction;
            document.getElementById('agent_delegation').value = user.delegation_r ?? '';
            document.getElementById('agent_service').value = user.service ?? '';
            document.getElementById('agent_unite_communale').value = user.unite_communale ?? '';
            document.getElementById('agent_poste').value = user.poste;
            document.getElementById('agent_anciennete').value = user.anciennete;
            document.getElementById('agent_site').value = user.site;
            document.getElementById('agent_date_passage').value = user.date_passage ?? '';
            document.getElementById('agent_telephone').value = user.telephone ?? '';
            document.getElementById('agent_employee_id').value = user.employee_id;

            let modal = new bootstrap.Modal(document.getElementById('visitModal'));
            modal.show();
        }
    </script>
@endsection

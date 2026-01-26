@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-body text-center">
        <h3 class="text-primary-custom fw-bold" >
           🩺 FICHE MÉDICALE – VISITE MÉDICALE ANNUELLE DU PERSONNEL (VMAP 2026)
        </h3>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <label class="form-label fw-bold" style="color:#afcb61;" >Rechercher un agent</label>
        <input type="text" id="search" class="form-control" placeholder="Nom, prénom ou matricule">

        <ul class="list-group mt-2" id="results"></ul>
    </div>
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

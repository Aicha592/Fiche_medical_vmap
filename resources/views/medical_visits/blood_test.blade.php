@extends('layouts.app')

@section('content')
    <div class="mb-4 text-center medical-hero">
        <h3>BIOLOGIE – VISITE MÉDICALE ANNUELLE DU PERSONNEL (VMAP 2026)</h3>
        <p>Recherchez un agent puis enregistrer les résultats du bilan sanguin.</p>
    </div>

    @if (session('success'))
    <div class="mt-3 alert alert-success" id="successAlert">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
    @endif


    <div class="medical-search">
        <label class="form-label fw-bold">Rechercher un agent</label>
        <input type="text" id="search" class="form-control" placeholder="Nom, prénom ou matricule">

        <ul class="mt-3 list-group" id="results"></ul>
    </div>

    <div id="bloodTestContainer" class="d-none">
        @include('medical_visits.form_blood_test')
    </div>
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
    document.getElementById('agent_date_passage').value = user.date_passage ?? '';
    document.getElementById('agent_telephone').value = user.telephone ?? '';
    document.getElementById('agent_employee_id').value = user.employee_id;

    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (el) {
            el.textContent = value && value !== '' ? value : '—';
        }
    };

    const fullName = `${user.nom || ''} ${user.prenom || ''}`.trim();
    setText('agent_nom_display', fullName || '—');
    setText('agent_matricule_display', user.matricule || '—');
    setText('agent_sexe_display', user.sexe || '—');
    setText('agent_age_display', user.age || '—');
    setText('agent_poste_display', user.poste || '—');

    // show the hidden form container and scroll to it
    const container = document.getElementById('bloodTestContainer');
    if (container) {
        container.classList.remove('d-none');
    }

    document.getElementById('visitForm')?.scrollIntoView({ behavior: 'smooth' });
}
    </script>
@endsection

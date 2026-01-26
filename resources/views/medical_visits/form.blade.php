<style>
.bg-recap-custom {
    background-color: #afcb61 ;
}

.btn-recap-custom {
    background-color: #afcb61 ;
    border-color: #afcb61 ;
    color: #fff ;
}
.btn-recap-custom:hover {
    background-color: #9fbb55 ;
}
</style>

@if(session('success'))
<div class="alert alert-success mt-3" id="successAlert">
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


<!-- Modal Formulaire Visite Médicale -->
<div class="modal fade" id="visitModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title">FICHE MÉDICALE – VISITE MÉDICALE ANNUELLE DU PERSONNEL (VMAP) 2026</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="visitForm" method="POST" action="{{ route('medical-visits.store') }}"
      class="needs-validation"
      novalidate>
                @csrf

                <div class="modal-body">
<div id="stepMedical">
                    <!-- I. IDENTIFICATION -->
                    <h5 class="fw-bold">I. IDENTIFICATION DE L’AGENT</h5>

                   <div class="row">
                       <div class="col-md-6 mb-3">
                           <label class="form-label">Nom et Prénom</label>
                           <input type="text" id="agent_nom" class="form-control" readonly>
                           <input type="hidden" name="user_id" id="agent_user_id">
                       </div>

                       <div class="col-md-6 mb-3">
                           <label class="form-label">Matricule</label>
                           <input type="text" id="agent_matricule" class="form-control" readonly>
                       </div>
                   </div>

                   <div class="row">
                       <div class="col-md-3 mb-3">
                           <label class="form-label">Sexe</label>
                           <input type="text" id="agent_sexe" class="form-control" readonly>
                       </div>

                       <div class="col-md-3 mb-3">
                           <label class="form-label">Âge</label>
                           <input type="number" id="agent_age" name="age" class="form-control" readonly>
                       </div>

                       <div class="col-md-6 mb-3">
                           <label class="form-label">Direction / Département / Service</label>
                           <input type="text" id="agent_direction" name="direction" class="form-control" readonly>
                       </div>
                   </div>

                   <div class="row">
                       <div class="col-md-6 mb-3">
                           <label class="form-label">Intitulé du poste occupé</label>
                           <input type="text" id="agent_poste" name="poste" class="form-control" readonly>
                       </div>

                       <div class="col-md-3 mb-3">
                           <label class="form-label">Ancienneté au poste</label>
                           <input type="text" id="agent_anciennete" name="anciennete" class="form-control" readonly>
                       </div>

                       <div class="col-md-3 mb-3">
                           <label class="form-label">Site (R / D / C)</label>
                           <input type="text" id="agent_site" name="site" class="form-control" readonly>
                       </div>
                   </div>

                    <hr>

                    <!-- II. ANTÉCÉDENTS -->
                    <h5 class="fw-bold">II. ANTÉCÉDENTS MÉDICAUX</h5>

                    <div class="mb-3">
                        <input type="checkbox" name="antecedents[]" value="Diabète" > Diabète
                        <input type="checkbox" name="antecedents[]" value="Asthme/ANS" > Asthme/ANS
                        <input type="checkbox" name="antecedents[]" value="Cardiopathie" > Cardiopathie
                        <input type="checkbox" name="antecedents[]" value="Traumato Orthopédie" > Traumato Orthopédie
                        <input type="checkbox" name="antecedents[]" value="Autres"> Autres
                    </div>

                    <div class="mb-3">
                        <input type="checkbox" name="antecedents[]" value="Accident du travail" > Accident du travail
                        <input type="checkbox" name="antecedents[]" value="Maladie professionnelle"> Maladie professionnelle
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Préciser</label>
                        <input type="text" name="antecedents_precisions" class="form-control" >
                    </div>

                    <hr>

                    <!-- III. EXAMEN CLINIQUE -->
                    <h5 class="fw-bold">III. EXAMEN CLINIQUE</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Taille (cm)</label>
                            <input type="number" step="0.01" name="taille" id="taille" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Poids (kg)</label>
                            <input type="number" step="0.1" name="poids" id="poids" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">IMC</label>
                            <input type="text" name="imc" id="imc" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tension artérielle</label>
                        <input type="text" name="tension" class="form-control" placeholder="Ex: 120 / 80" required>
                    </div>

                    <hr>

                    <!-- IV. DEPITAGE RPS -->
                    <h5 class="fw-bold">IV. DÉPISTAGE RPS</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stress lié au travail</label><br>
                        <input class="form-check-input" type="radio" name="stress" value="Non" required> Non
                        <input class="form-check-input" type="radio" name="stress" value="Parfois"> Parfois
                        <input class="form-check-input" type="radio" name="stress" value="Oui"> Oui
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Troubles du sommeil</label><br>
                        <input class="form-check-input" type="radio" name="sommeil" value="Non" required> Non
                        <input class="form-check-input" type="radio" name="sommeil" value="Parfois"> Parfois
                        <input class="form-check-input" type="radio" name="sommeil" value="Oui"> Oui
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Charge de travail supportable</label><br>
                        <input class="form-check-input" type="radio" name="charge_travail" value="Oui" required> Oui
                        <input class="form-check-input" type="radio" name="charge_travail" value="Variable"> Variable
                        <input class="form-check-input" type="radio" name="charge_travail" value="Non"> Non
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Soutien hiérarchique</label><br>
                        <input class="form-check-input" type="radio" name="soutien" value="Oui" required> Oui
                        <input class="form-check-input" type="radio" name="soutien" value="Peu"> Peu
                        <input class="form-check-input" type="radio" name="soutien" value="Pas du tout"> Pas du tout
                    </div>

                    <hr>

                    <!-- V. AVIS MEDICAL -->
                    <h5 class="fw-bold">V. AVIS MÉDICAL</h5>

                    <div class="mb-3">
                        <input type="radio" name="avis" value="Apte sans restriction" required> Apte sans restriction<br>
                        <input type="radio" name="avis" value="Apte avec aménagement"> Apte avec aménagement<br>
                        <input type="radio" name="avis" value="Inapte temporaire"> Inapte temporaire<br>
                        <input type="radio" name="avis" value="Inapte définitif"> Inapte définitif<br>
                    </div>

                    <hr>

                    <!-- VI. OBSERVATIONS -->
                <h5 class="fw-bold">VI. OBSERVATIONS / RECOMMANDATIONS</h5>
    <textarea name="observations" class="form-control" rows="4" required></textarea>

    <div class="d-flex justify-content-end mt-4">
        <button type="button" id="btnNextToQuestionnaire" class="btn btn-success">
            Suivant ➜
        </button>
    </div>
</div>
                    <!-- ===== QUESTIONNAIRE QHSE / SST ===== -->
                    <div id="stepQuestionnaire" style="display:none;">
                        


                    <h5 class="fw-bold">Questionnaire QHSE / SST</h5>
                    <h6>II. CONTRAINTES PHYSIQUES ET ERGONOMIQUES</h6>
                    <p><b>1. Manutention et efforts physiques</b></p>
                    <input type="checkbox" name="qhse_manutention[]" value="Port manuel de charges lourdes" > Port manuel de charges lourdes<br>
                    <input type="checkbox" name="qhse_manutention[]" value="Soulèvement fréquent"> Soulèvement fréquent<br>
                    <input type="checkbox" name="qhse_manutention[]" value="Poussée / traction"> Poussée / traction<br>
                    <input type="checkbox" name="qhse_manutention[]" value="Efforts physiques intenses"> Efforts physiques intenses<br><br>

                    <p><b>2. Postures et gestes</b></p>
                    <input type="checkbox" name="qhse_postures[]" value="Postures pénibles" > Postures pénibles<br>
                    <input type="checkbox" name="qhse_postures[]" value="Gestes répétitifs"> Gestes répétitifs<br>
                    <input type="checkbox" name="qhse_postures[]" value="Travail prolongé debout"> Travail prolongé debout<br>
                    <input type="checkbox" name="qhse_postures[]" value="Travail accroupi"> Travail accroupi<br>
                    <input type="checkbox" name="qhse_postures[]" value="Vibrations"> Vibrations<br>

                    <hr>

                    <h6>III. EXPOSITIONS AUX NUISANCES PROFESSIONNELLES</h6>
                    <p><b>1. Nuisances physiques</b></p>
                    <input type="checkbox" name="qhse_nuisances_physiques[]" value="Bruit élevé" > Bruit élevé<br>
                    <input type="checkbox" name="qhse_nuisances_physiques[]" value="Chaleur / soleil intense"> Chaleur / soleil intense<br>
                    <input type="checkbox" name="qhse_nuisances_physiques[]" value="Pluie / humidité"> Pluie / humidité<br>
                    <input type="checkbox" name="qhse_nuisances_physiques[]" value="Froid / Vent"> Froid / Vent<br>
                    <input type="checkbox" name="qhse_nuisances_physiques[]" value="Éclairage insuffisant"> Éclairage insuffisant<br><br>

                    <p><b>2. Nuisances chimiques et biologiques</b></p>
                    <input type="checkbox" name="qhse_nuisances_chimiques[]" value="Poussières" > Poussières<br>
                    <input type="checkbox" name="qhse_nuisances_chimiques[]" value="Odeurs fortes"> Odeurs fortes<br>
                    <input type="checkbox" name="qhse_nuisances_chimiques[]" value="Lixiviats"> Lixiviats<br>
                    <input type="checkbox" name="qhse_nuisances_chimiques[]" value="Produits chimiques"> Produits chimiques<br>
                    <input type="checkbox" name="qhse_nuisances_chimiques[]" value="Déchets médicaux"> Déchets médicaux<br>
                    <input type="checkbox" name="qhse_nuisances_chimiques[]" value="Agents biologiques"> Agents biologiques<br>

                    <hr>

                    <h6>IV. RISQUES MÉCANIQUES ET ACCIDENTELS</h6>
                    <input type="checkbox" name="qhse_risques[]" value="Circulation routière" > Circulation routière<br>
                    <input type="checkbox" name="qhse_risques[]" value="Risque de chute"> Risque de chute<br>
                    <input type="checkbox" name="qhse_risques[]" value="Coupures / piqûres"> Coupures / piqûres<br>
                    <input type="checkbox" name="qhse_risques[]" value="Coincement / écrasement"> Coincement / écrasement<br>
                    <input type="checkbox" name="qhse_risques[]" value="Incendie / explosion"> Incendie / explosion<br>
                    <input type="checkbox" name="qhse_risques[]" value="Utilisation d’engins ou machines"> Utilisation d’engins ou machines<br>

                    <hr>

                    <h6>V. ORGANISATION DU TRAVAIL</h6>
                    <input type="checkbox" name="qhse_organisation[]" value="Travail de nuit" > Travail de nuit<br>
                    <input type="checkbox" name="qhse_organisation[]" value="Horaires décalés"> Horaires décalés<br>
                    <input type="checkbox" name="qhse_organisation[]" value="Travail en rotation"> Travail en rotation<br>
                    <input type="checkbox" name="qhse_organisation[]" value="Travail isolé"> Travail isolé<br>
                    <input type="checkbox" name="qhse_organisation[]" value="Pression temporelle élevée"> Pression temporelle élevée<br>
                    <input type="checkbox" name="qhse_organisation[]" value="Manque de pauses"> Manque de pauses<br>

                    <hr>

                    <h6>VI. EPI</h6>
                    <p><b>1. Mise à disposition </b></p>
                    <input type="checkbox" name="qhse_epi_dispo[]" value="Casque" > Casque<br>
                    <input type="checkbox" name="qhse_epi_dispo[]" value="Gants"> Gants<br>
                    <input type="checkbox" name="qhse_epi_dispo[]" value="Bottes"> Bottes<br>
                    <input type="checkbox" name="qhse_epi_dispo[]" value="Masque"> Masque<br>
                    <input type="checkbox" name="qhse_epi_dispo[]" value="Gilet haute visibilité"> Gilet haute visibilité<br>
                    <input type="checkbox" name="qhse_epi_dispo[]" value="Autres"> Autres<br><br>

                    <p><b>2. Utilisation </b></p>
                    <input type="radio" name="qhse_epi_utilisation" value="Toujours" > Toujours
                    <input type="radio" name="qhse_epi_utilisation" value="Souvent"> Souvent
                    <input type="radio" name="qhse_epi_utilisation" value="Rarement"> Rarement
                    <input type="radio" name="qhse_epi_utilisation" value="Jamais"> Jamais <br><br>

                    <p><b>3. Difficultés rencontrées </b></p>
                    <input type="checkbox" name="qhse_epi_difficulte[]" value="Inconfort" > Inconfort<br>
                    <input type="checkbox" name="qhse_epi_difficulte[]" value="Inadaptation"> Inadaptation<br>
                    <input type="checkbox" name="qhse_epi_difficulte[]" value="Usure rapide"> Usure rapide<br>
                    <input type="checkbox" name="qhse_epi_difficulte[]" value="Indisponibilité"> Indisponibilité<br>

                    <hr>

                    <h6>VII. FORMATION ET INFORMATION SST</h6>
                    <input type="checkbox" name="qhse_formation[]" value="Formation SST reçue" > Formation SST reçue<br>
                    <input type="checkbox" name="qhse_formation[]" value="Sensibilisation aux risques"> Sensibilisation aux risques<br>
                    <input type="checkbox" name="qhse_formation[]" value="Formation EPI"> Formation EPI<br>
                    <input type="checkbox" name="qhse_formation[]" value="Formation conduite"> Formation conduite<br>
                    <input type="checkbox" name="qhse_formation[]" value="Aucune formation récente"> Aucune formation récente<br>

                    <hr>

                    <h6>VIII. APPRÉCIATION GLOBALE</h6>
                    <input type="radio" name="qhse_appreciation" value="Faible risque" > Faible risque
                    <input type="radio" name="qhse_appreciation" value="Risque modéré"> Risque modéré
                    <input type="radio" name="qhse_appreciation" value="Risque élevé"> Risque élevé
                    <input type="radio" name="qhse_appreciation" value="Risque très élevé"> Risque très élevé

                    <hr>

                    <h6>IX. OBSERVATIONS / SUGGESTIONS</h6>
                    <textarea name="qhse_observations" class="form-control" rows="4" ></textarea>

                    <hr>

                    <h6>X. SYNTHÈSE QHSE (À REMPLIR PAR QHSE / SST)</h6>
                    <p><b>Poste à risque identifié </b> </p>
                    <input type="radio" name="qhse_synthese_risque" value="Oui" > Oui
                    <input type="radio" name="qhse_synthese_risque" value="Non"> Non<br><br>

                    <p><b>Facteurs de risques dominants </b></p>
                    <input type="checkbox" name="qhse_synthese_facteurs[]" value="Ergonomie" > Ergonomie
                    <input type="checkbox" name="qhse_synthese_facteurs[]" value="Physique"> Physique
                    <input type="checkbox" name="qhse_synthese_facteurs[]" value="Chimique"> Chimique
                    <input type="checkbox" name="qhse_synthese_facteurs[]" value="Biologique"> Biologique
                    <input type="checkbox" name="qhse_synthese_facteurs[]" value="Organisationnel"> Organisationnel<br><br>

                    <p><b>Actions recommandées </b></p>
                    <input type="checkbox" name="qhse_synthese_actions[]" value="Étude ergonomique" > Étude ergonomique
                    <input type="checkbox" name="qhse_synthese_actions[]" value="Aménagement de poste"> Aménagement de poste
                    <input type="checkbox" name="qhse_synthese_actions[]" value="Renforcement EPI"> Renforcement EPI
                    <input type="checkbox" name="qhse_synthese_actions[]" value="Formation ciblée"> Formation ciblée
                    <input type="checkbox" name="qhse_synthese_actions[]" value="Suivi SST"> Suivi SST

                    <div class="d-flex justify-content-between mt-4">
    <button type="button" id="btnBackToMedical" class="btn btn-secondary">
        ⬅ Retour
    </button>

    <button type="button" id="btnShowRecap" class="btn btn-primary-custom text-white">
        Enregistrer
    </button>
</div>
                </div>

                



            </form>

        </div>
        
    </div>
   
</div>


<!-- MODAL RECAP -->
<div class="modal fade" id="recapModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-recap-custom text-white">
                <h5 class="modal-title">Récapitulatif - Vérifiez avant d'enregistrer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="recapBody">
                <!-- Le contenu sera rempli par JS -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Retour</button>
                <button type="button" id="confirmSave" class="btn btn-recap-custom">
                    Confirmer et Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>



<script>
    // ===== Recherche agent + ouverture du formulaire =====
    document.getElementById('searchAgent').addEventListener('input', function() {
        const query = this.value;

        if(query.length < 2){
            document.getElementById('agentsResults').innerHTML = '';
            return;
        }

        fetch(`/agents/search?q=${query}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.forEach(agent => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action"
                           data-id="${agent.id}"
                           data-nom="${agent.name}"
                           data-matricule="${agent.matricule}"
                           data-sexe="${agent.sexe}"
                           data-age="${agent.age}"
                           data-direction="${agent.direction}"
                           data-poste="${agent.poste}"
                           data-anciennete="${agent.anciennete}"
                           data-site="${agent.site}">
                            ${agent.name} - ${agent.matricule}
                        </a>
                    `;
                });
                document.getElementById('agentsResults').innerHTML = html;

                // click sur agent
                document.querySelectorAll('#agentsResults a').forEach(a => {
                    a.addEventListener('click', function(e){
                        e.preventDefault();

                        document.getElementById('agent_user_id').value = this.dataset.id;
                        document.getElementById('agent_nom').value = this.dataset.nom;
                        document.getElementById('agent_matricule').value = this.dataset.matricule;
                        document.getElementById('agent_sexe').value = this.dataset.sexe;
                        document.getElementById('agent_age').value = this.dataset.age;
                        document.getElementById('agent_direction').value = this.dataset.direction;
                        document.getElementById('agent_poste').value = this.dataset.poste;
                        document.getElementById('agent_anciennete').value = this.dataset.anciennete;
                        document.getElementById('agent_site').value = this.dataset.site;

                        // ouvrir modal
                        new bootstrap.Modal(document.getElementById('visitModal')).show();
                    });
                });
            });
    });
    </script>


    <!-- // ===== IMC ===== -->
 <script>
document.addEventListener('DOMContentLoaded', function () {

    function calculerIMC() {
        const tailleInput = document.getElementById('taille');
        const poidsInput  = document.getElementById('poids');
        const imcInput    = document.getElementById('imc');

        if (!tailleInput || !poidsInput || !imcInput) return;

        const tailleCm = parseFloat(tailleInput.value);
        const poidsKg  = parseFloat(poidsInput.value);

        if (!tailleCm || !poidsKg) {
            imcInput.value = '';
            return;
        }

        const tailleM = tailleCm / 100;
        const imc = poidsKg / (tailleM * tailleM);

        imcInput.value = imc.toFixed(2);
    }

    document.addEventListener('input', function (e) {
        if (e.target.id === 'taille' || e.target.id === 'poids') {
            calculerIMC();
        }
    });

});
</script>



    <!-- // ===== RECAP AVANT ENREGISTREMENT ===== -->
<script>
document.getElementById('btnShowRecap').addEventListener('click', function () {

    const form = document.getElementById('visitForm');
    let recapHtml = '';

    const sections = [
        { title: 'IDENTIFICATION', fields: ['agent_nom','agent_matricule','agent_sexe','agent_age','agent_direction','agent_poste','agent_anciennete','agent_site'] },
        { title: 'ANTÉCÉDENTS', nameEndsWith: 'antecedents' },
        { title: 'EXAMEN CLINIQUE', fields: ['taille','poids','imc','tension'] },
        { title: 'DÉPISTAGE RPS', radios: ['stress','sommeil','charge_travail','soutien'] },
        { title: 'AVIS MÉDICAL', radios: ['avis'] },
        { title: 'OBSERVATIONS', textareas: ['observations'] },
        { title: 'QUESTIONNAIRE QHSE / SST', prefix: 'qhse_' }
    ];

    sections.forEach(section => {
        recapHtml += `<h6 class="mt-3 fw-bold">${section.title}</h6><ul class="list-group mb-3">`;

        // Champs simples par ID
        section.fields?.forEach(id => {
            const el = document.getElementById(id);
            if (el && el.value) {
                recapHtml += `<li class="list-group-item"><strong>${el.previousElementSibling?.innerText || id} :</strong> ${el.value}</li>`;
            }
        });

        // Radios
        section.radios?.forEach(name => {
            const checked = form.querySelector(`input[name="${name}"]:checked`);
            if (checked) {
                recapHtml += `<li class="list-group-item"><strong>${name} :</strong> ${checked.value}</li>`;
            }
        });

        // Textarea
        section.textareas?.forEach(name => {
            const el = form.querySelector(`textarea[name="${name}"]`);
            if (el && el.value) {
                recapHtml += `<li class="list-group-item"><strong>${name} :</strong> ${el.value}</li>`;
            }
        });

        // Checkboxes par suffixe
        if (section.nameEndsWith) {
            const checked = [...form.querySelectorAll(`input[name="${section.nameEndsWith}[]"]:checked`)]
                .map(c => c.value);
            if (checked.length) {
                recapHtml += `<li class="list-group-item">${checked.join(', ')}</li>`;
            }
        }

        // QHSE auto
        if (section.prefix) {
            const qhseFields = [...form.elements].filter(e => e.name?.startsWith(section.prefix));
            const grouped = {};

            qhseFields.forEach(el => {
                if (el.checked) {
                    grouped[el.name] ??= [];
                    grouped[el.name].push(el.value);
                }
            });

            Object.values(grouped).forEach(values => {
                recapHtml += `<li class="list-group-item">${values.join(', ')}</li>`;
            });
        }

        recapHtml += `</ul>`;
    });

    document.getElementById('recapBody').innerHTML = recapHtml;

    new bootstrap.Modal(document.getElementById('recapModal')).show();
});

// document.getElementById('confirmSave').addEventListener('click', function () {
//     document.getElementById('visitForm').submit();
// });
</script>
<script>
 document.getElementById('confirmSave').addEventListener('click', function () {

    const form = document.getElementById('visitForm');

    // Si le formulaire n'est pas valide -> on arrête
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    // Fermer la modal récap
    bootstrap.Modal.getInstance(document.getElementById('recapModal')).hide();


    // Enregistrer
    form.submit();
});



</script>

<script>
(() => {
  'use strict';

  const forms = document.querySelectorAll('.needs-validation');

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('btnNextToQuestionnaire').addEventListener('click', function () {

        const stepMedical = document.getElementById('stepMedical');
        let isValid = true;

        stepMedical.querySelectorAll('input, textarea, select').forEach(el => {

            // Champs invisibles
            if (el.offsetParent === null) return;

            // Champs readonly
            if (el.readOnly) return;

            // 👉 ANTÉCÉDENTS = PAS OBLIGATOIRE
            if (el.type === 'checkbox' && el.name === 'antecedents[]') {
                el.classList.remove('is-invalid');
                return;
            }

            // Checkbox obligatoires (autres que antécédents)
            if (el.type === 'checkbox' && el.name.endsWith('[]')) {
                const group = stepMedical.querySelectorAll(`input[name="${el.name}"]`);
                const checked = [...group].some(c => c.checked);

                if (!checked) {
                    isValid = false;
                    group.forEach(c => c.classList.add('is-invalid'));
                } else {
                    group.forEach(c => c.classList.remove('is-invalid'));
                }
                return;
            }

            // Validation normale
            if (!el.checkValidity()) {
                el.classList.add('is-invalid');
                isValid = false;
            } else {
                el.classList.remove('is-invalid');
            }
        });

        if (!isValid) return;

        // Passage à l'étape suivante
        stepMedical.style.display = 'none';
        document.getElementById('stepQuestionnaire').style.display = 'block';
        document.querySelector('.modal-body').scrollTop = 0;
    });

    document.getElementById('btnBackToMedical').addEventListener('click', function () {
        document.getElementById('stepQuestionnaire').style.display = 'none';
        document.getElementById('stepMedical').style.display = 'block';
    });

});
</script>

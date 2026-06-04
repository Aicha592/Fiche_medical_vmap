<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUESTIONNAIRE QHSE / SST VMAP 2026 - SONAGED</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full font-sans antialiased text-gray-900">

<script>
function createFormData() {
    return {
        step: 1,
        searchQuery: '',
        searchResults: [],
        employee: null,

        fetchEmployees() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                return;
            }
            fetch(`/medical-qhse/search-employee?query=${encodeURIComponent(this.searchQuery)}`)
                .then(res => res.json())
                .then(data => { this.searchResults = data; });
        },
        selectEmployee(emp) {
            this.employee = emp;
            this.searchResults = [];
            this.searchQuery = `${emp.prenom} ${emp.nom} (${emp.matricule})`;
            this.calculateAnciennete();
        },
        calculateAnciennete() {
            if (!this.employee?.matricule) return;
            const matches = this.employee.matricule.match(/-(\d+)-/);
            if (!matches || !matches[1]) return;
            const yearSuffix = parseInt(matches[1]);
            const currentYear = new Date().getFullYear();
            const yearPrefix = Math.floor(currentYear / 100);
            const hireYear = yearPrefix * 100 + yearSuffix;
            const anciennete = currentYear - hireYear;
            const ancienneteField = document.querySelector('input[name="anciennete"]');
            if (ancienneteField) {
                ancienneteField.value = `${anciennete} an${anciennete > 1 ? 's' : ''}`;
            }
        },
        resetEmployee() {
            this.employee = null;
            this.searchQuery = '';
            this.searchResults = [];
            this.step = 1;
            const ancienneteField = document.querySelector('input[name="anciennete"]');
            if (ancienneteField) {
                ancienneteField.value = '';
            }
        }
    };
}
</script>

    <div class="max-w-4xl px-4 mx-auto my-12 sm:px-6 lg:px-8" x-data="createFormData()">

        <div
            class="relative p-6 overflow-hidden text-center bg-white border-b border-gray-200 shadow-sm rounded-t-xl sm:p-8">
            <div class="absolute top-0 left-0 right-0 h-2 bg-emerald-600"></div>
            <span
                class="px-3 py-1 text-xs font-bold tracking-widest uppercase rounded-full text-emerald-600 bg-emerald-50">SONAGED
                - VMAP 2026</span>
            <h1 class="mt-3 text-2xl font-extrabold text-gray-900 sm:text-3xl">QUESTIONNAIRE QHSE</h1>
        </div>

        @if (session('success'))
            <div
                class="flex items-center p-4 mt-4 space-x-2 border bg-emerald-50 border-emerald-300 rounded-xl text-emerald-800">
                <span class="font-bold text-emerald-600">✓</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="p-6 space-y-4 bg-white border-b border-gray-100 shadow-sm">
            <div class="pb-2 border-b border-gray-100">
                <h2 class="text-sm font-bold tracking-wide text-gray-700 uppercase">Recherche de l'agent de la SONAGED
                </h2>
                <p class="text-xs text-gray-400">Saisissez le matricule, le nom ou le prénom pour charger le dossier.
                </p>
            </div>

            <div class="relative">
                <div class="flex space-x-2">
                    <div class="relative flex-1">
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchEmployees()"
                            placeholder="Ex: 11111-24-SNG, Diop, Mamadou..."
                            class="w-full text-lg border-gray-300 rounded-lg shadow-sm focus:border-emerald-600 focus:ring-emerald-600"
                            :disabled="employee !== null">

                        <button type="button" x-show="employee !== null" @click="resetEmployee()"
                            class="absolute right-3 top-2.5 text-xs text-red-500 hover:underline font-medium">
                            Changer d'agent
                        </button>
                    </div>
                </div>

                <div class="absolute left-0 right-0 z-50 mt-1 overflow-hidden bg-white border border-gray-200 divide-y divide-gray-100 rounded-lg shadow-xl"
                    x-show="searchResults.length > 0">
                    <template x-for="emp in searchResults" :key="emp.id">
                        <button type="button" @click="selectEmployee(emp)"
                            class="flex items-center justify-between w-full px-4 py-3 text-sm text-left transition hover:bg-emerald-50">
                            <div>
                                <span class="font-semibold text-gray-900" x-text="emp.prenom + ' ' + emp.nom"></span>
                                <span class="block text-xs text-gray-500"
                                    x-text="emp.emploi_occupe || 'Fonction non renseignée'"></span>
                            </div>
                            <span class="px-2 py-1 font-mono text-xs text-gray-600 bg-gray-100 rounded"
                                x-text="emp.matricule"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 p-4 border bg-emerald-50/50 border-emerald-200 rounded-xl sm:grid-cols-2"
                x-show="employee !== null" x-transition>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Matricule :</span>
                    <p class="text-sm font-bold text-gray-800" x-text="employee?.matricule"></p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Direction / Département
                        :</span>
                    <p class="text-sm font-semibold text-gray-800" x-text="employee?.direction || 'N/A'"></p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Zone d'affectation (UC)
                        :</span>
                    <p class="text-sm font-semibold text-gray-800" x-text="employee?.unite_communale || 'N/A'"></p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Poste actuel occupé :</span>
                    <p class="text-sm font-semibold text-gray-800" x-text="employee?.emploi_occupe || 'N/A'"></p>
                </div>
            </div>
        </div>

        <div class="p-12 text-center text-gray-400 bg-white border-b border-gray-100 rounded-b-xl"
            x-show="employee === null">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p class="text-sm">Veuillez rechercher et sélectionner un employé ci-dessus pour faire apparaître son
                questionnaire QHSE.</p>
        </div>

        <div x-show="employee !== null" x-transition>

            <div
                class="flex items-center justify-between px-6 py-3 text-xs font-medium text-gray-400 bg-white border-b border-gray-100 shadow-sm">
                <span :class="step === 1 ? 'text-emerald-600 font-bold' : 'text-gray-900'">1. Planning</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 2 ? 'text-emerald-600 font-bold' : (step > 2 ? 'text-gray-900' : '')">2. Charge
                    Physique</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 3 ? 'text-emerald-600 font-bold' : (step > 3 ? 'text-gray-900' : '')">3.
                    Environnement</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 4 ? 'text-emerald-600 font-bold' : (step > 4 ? 'text-gray-900' : '')">4.
                    Logistique EPI</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 5 ? 'text-emerald-600 font-bold' : ''">5. Clôture</span>
            </div>

            <form action="{{ route('qhse.store') }}" method="POST"
                class="p-6 space-y-8 bg-white shadow-sm rounded-b-xl sm:p-8">
                @csrf

                <input type="hidden" name="employee_id" :value="employee?.id">

                <div x-show="step === 1" x-transition.fade class="space-y-6">
                <div class="border-b border-gray-200 pb-3">
                    <h2 class="text-xl font-bold text-gray-900">1. Identification Professionnelle</h2>
                    <p class="text-xs text-gray-500">Renseignez vos informations de déploiement actuel.</p>
                </div>

                <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Poste occupé (Fonction) * (Choix multiple)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(['Responsable de PRN', 'Ripeur (agent de collecte)', 'Chauffeur poids lourd / BOM', 'Conducteur de tricycle', 'Chauffeur véhicule poids léger / longues distances', 'Agent de balayage mécanisé', 'Agent de balayage manuel', 'Agent de désensablement', 'Agent de nettoiement', 'Agent de sensibilisation', 'Agent de décharge (superviseur)', 'Agent de décharge (surveillance)', 'Agent maintenance / mécanicien', 'Chef d’équipe collecte', 'RUC', 'Personnel administratif'] as $poste)
                                <label class="border rounded-lg p-3 flex items-center cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="poste_occupe[]" value="{{ $poste }}" {{ is_array(old('poste_occupe')) && in_array($poste, old('poste_occupe')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                                    <span class="text-sm text-gray-700 capitalize">{{ $poste }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <input type="text" name="poste_occupe_autre" value="{{ old('poste_occupe_autre') }}" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Autre poste ? Précisez ici...">
                        </div>
                    </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Ancienneté</label>
                        <input disabled type="text" name="anciennete" value="{{ old('anciennete') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ex: 3 ans et 6 mois">
                    </div>

                    <div>
                            <label
                                class="block mb-2 text-xs font-semibold tracking-wider text-gray-700 uppercase">Activité
                                dominante *</label>
                            <select name="type_activite_dominante" class="w-full text-sm border-gray-300 rounded-lg">
                                <option value="Terrain">Terrain</option>
                                <option value="Bureau">Bureau</option>
                                <option value="Mixte">Mixte</option>
                            </select>
                    </div>
                </div>

                <hr class="border-gray-200">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Horaire de travail (Choix multiples) *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach(['Jour', 'après midi', 'Nuit'] as $horaire)
                            <label class="border rounded-lg p-3 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="horaire_travail[]" value="{{ $horaire }}" {{ is_array(old('horaire_travail')) && in_array($horaire, old('horaire_travail')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                                <span class="text-sm text-gray-700 font-medium">En équipe de {{ ucfirst($horaire) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('horaire_travail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>


            <div x-show="step === 2" x-transition.fade class="space-y-6">
                <div class="border-b border-gray-200 pb-3">
                    <h2 class="text-xl font-bold text-gray-900">2. Contraintes Physiques et Ergonomiques</h2>
                    <p class="text-xs text-gray-500">Évaluation de la charge physique de travail au quotidien.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Dans votre activité quotidienne, êtes-vous exposé(e) à : (Choix multiples)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach([
                            'Port manuel de charges lourdes (> 15 kg)',
                            'Soulèvement fréquent de bacs / sacs / déchets en vrac',
                            'Poussée ou traction de conteneurs',
                            'Manipulation de déchets volumineux',
                            'Efforts intenses répétés (chargement / déchargement)'
                        ] as $manutention)
                            <label class="border rounded-lg p-3 flex items-start cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="manutention_efforts[]" value="{{ $manutention }}" {{ is_array(old('manutention_efforts')) && in_array($manutention, old('manutention_efforts')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mt-0.5 mr-3">
                                <span class="text-sm text-gray-700">{{ $manutention }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Fréquence globale de ces efforts : *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['Rare', 'Occasionnelle', 'Fréquente', 'Permanente'] as $freq)
                            <label class="bg-white border rounded-lg p-3 flex items-center justify-center cursor-pointer hover:bg-gray-100">
                                <input type="radio" name="frequence_manutention" value="{{ $freq }}" {{ old('frequence_manutention') === $freq ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500 mr-2">
                                <span class="text-sm font-medium text-gray-700">{{ $freq }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('frequence_manutention') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Gestes et Postures contraignants (Choix multiples)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach([
                            'Dos courbé prolongé',
                            'Torsions répétées du tronc',
                            'Travail prolongé debout',
                            'Travail accroupi / à genoux',
                            'Gestes répétitifs',
                            'Vibrations (conduite engins, compacteurs, camions BOM)'
                        ] as $posture)
                            <label class="border rounded-lg p-3 flex items-start cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="gestes_postures[]" value="{{ $posture }}" {{ is_array(old('gestes_postures')) && in_array($posture, old('gestes_postures')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mt-0.5 mr-3">
                                <span class="text-sm text-gray-700">{{ $posture }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1">Niveau de pénibilité du travail ressenti (de 1 à 5) *</label>
                    <p class="text-xs text-gray-500 mb-3">Sélectionnez la carte correspondant à l'intensité de votre charge de travail.</p>
                    <div class="grid grid-cols-5 gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $labels = [1 => 'Très faible', 2 => 'Faible', 3 => 'Moyen', 4 => 'Élevé', 5 => 'Très élevé'];
                            @endphp
                            <label class="border rounded-lg p-3 flex flex-col items-center justify-center cursor-pointer transition hover:border-emerald-500 hover:bg-emerald-50/30 focus-within:ring-2 focus-within:ring-emerald-500">
                                <input type="radio" name="niveau_penibilite" value="{{ $i }}" {{ old('niveau_penibilite') == $i ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500 mb-2">
                                <span class="text-lg font-bold text-gray-900">{{ $i }}</span>
                                <span class="text-[10px] text-gray-500 text-center font-medium">{{ $labels[$i] }}</span>
                            </label>
                        @endfor
                    </div>
                    @error('niveau_penibilite') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Outils de travail utilisés (Choix multiples)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['Pelles', 'Balais cantonniers', 'Râteaux', 'brouette', 'houe', 'machette', 'hilaire'] as $outil)
                            <label class="border rounded-lg p-3 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="outils_travail[]" value="{{ $outil }}" {{ is_array(old('outils_travail')) && in_array($outil, old('outils_travail')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                                <span class="text-sm text-gray-700 capitalize">{{ $outil }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <input type="text" name="outils_travail_autre" value="{{ old('outils_travail_autre') }}" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Autre outil ? Précisez ici...">
                    </div>
                </div>
            </div>


            <div x-show="step === 3" x-transition.fade class="space-y-6">
                <div class="border-b border-gray-200 pb-3">
                    <h2 class="text-xl font-bold text-gray-900">3. Exposition aux Nuisances & Risques Accidentels</h2>
                    <p class="text-xs text-gray-500">Déclaration de l'environnement physique et des dangers environnants.</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-2">Nuisances physiques (Choix multiples)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach([
                            'Bruit élevé (engins, circulation, compacteurs)',
                            'Exposition prolongée au soleil / chaleur intense',
                            'Exposition à la pluie / ou à l’humidité',
                            'Vent / poussières atmosphériques',
                            'Éclairage insuffisant (travail de nuit)'
                        ] as $nuisPhys)
                            <label class="border rounded-lg p-3 flex items-start cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="nuisances_physiques[]" value="{{ $nuisPhys }}" {{ is_array(old('nuisances_physiques')) && in_array($nuisPhys, old('nuisances_physiques')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mt-0.5 mr-3">
                                <span class="text-sm text-gray-700">{{ $nuisPhys }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-2">Nuisances chimiques et biologiques (Spécifique activité déchets)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach([
                            'Contact avec lixiviats',
                            'Poussières de déchets',
                            'Odeurs fortes / gaz de décharge / gaz d’échappement du trafic routier',
                            'Déchets médicaux ou assimilés',
                            'Déchets dangereux (piles, solvants, huiles, peintures etc.)',
                            'Risque de piqûres / coupures (objets tranchants)',
                            'Présence d’animaux nuisibles (rongeurs, chiens errants, reptiles)'
                        ] as $nuisChimBio)
                            <label class="border rounded-lg p-3 flex items-start cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="nuisances_chimiques_biologiques[]" value="{{ $nuisChimBio }}" {{ is_array(old('nuisances_chimiques_biologiques')) && in_array($nuisChimBio, old('nuisances_chimiques_biologiques')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mt-0.5 mr-3">
                                <span class="text-sm text-gray-700">{{ $nuisChimBio }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-2">Risques accidentels (Choix multiples)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach([
                            'Circulation routière dense (collecte urbaine, balayage de voiries)',
                            'Risque de chute de plain-pied (sol boueux, glissant, en pente)',
                            'Chute de hauteur (benne, engins, talus décharge, nettoyage de vitres)',
                            'Coincement / écrasement par engins',
                            'Incendie / explosion (gaz de décharge)',
                            'Accident lié aux manœuvres de camions'
                        ] as $risqueAcc)
                            <label class="border rounded-lg p-3 flex items-start cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="risques_accidentels[]" value="{{ $risqueAcc }}" {{ is_array(old('risques_accidentels')) && in_array($risqueAcc, old('risques_accidentels')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mt-0.5 mr-3">
                                <span class="text-sm text-gray-700">{{ $risqueAcc }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Avez-vous déjà été témoin d’un accident sur votre site ? *</h3>
                        <p class="text-xs text-gray-500">Concerne les incidents majeurs ou légers survenus en service.</p>
                    </div>
                    <div class="flex space-x-4 shrink-0">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="temoin_accident" value="1" {{ old('temoin_accident') === '1' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Oui</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="temoin_accident" value="0" {{ old('temoin_accident') === '0' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Non</span>
                        </label>
                    </div>
                    @error('temoin_accident') <p class="mt-1 text-xs text-red-600 w-full">{{ $message }}</p> @enderror
                </div>
            </div>


            <div x-show="step === 4" x-transition.fade class="space-y-6">
                <div class="border-b border-gray-200 pb-3">
                    <h2 class="text-xl font-bold text-gray-900">4. Gestion des EPI & Formations</h2>
                    <p class="text-xs text-gray-500">Suivi des dotations de sécurité et compétences métiers.</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-2">Organisation du travail (Choix multiples)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach(['Travail de nuit', 'Travail en rotation', 'Travail isolé', 'Pression liée aux délais', 'Effectif insuffisant'] as $org)
                            <label class="border rounded-lg p-3 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="organisation_travail[]" value="{{ $org }}" {{ is_array(old('organisation_travail')) && in_array($org, old('organisation_travail')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                                <span class="text-sm text-gray-700">{{ $org }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                @php
                    $listeEpi = ['Casquette', 'Gants anti-coupure', 'Bottes en caoutchouc', 'Chaussure de sécurité', 'Masque anti-poussière', 'Lunettes de protection', 'Gilet haute visibilité', 'pantalon', 't-shirt', 'blouson', 'casque de sécurité', 'tenue de pluie', 'protection auditive', 'combinaison étanche'];
                @endphp
                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-2">1. EPI fournis par la SONAGED (Choix multiples)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach($listeEpi as $epi)
                            <label class="border rounded-lg p-2.5 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="epi_fournis[]" value="{{ $epi }}" {{ is_array(old('epi_fournis')) && in_array($epi, old('epi_fournis')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-2.5">
                                <span class="text-xs text-gray-700 capitalize">{{ $epi }}</span>
                            </label>
                        @endforeach
                    </div>
                    <input type="text" name="epi_fournis_autres" value="{{ old('epi_fournis_autres') }}" class="mt-2 w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Autres EPI fournis ? Spécifiez...">
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-2">2. Équipements que vous utilisez tous les jours (Choix multiples)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(array_slice($listeEpi, 0, 11) as $epi) <label class="border rounded-lg p-2.5 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="epi_utilises_quotidien[]" value="{{ $epi }}" {{ is_array(old('epi_utilises_quotidien')) && in_array($epi, old('epi_utilises_quotidien')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-2.5">
                                <span class="text-xs text-gray-700 capitalize">{{ $epi }}</span>
                            </label>
                        @endforeach
                    </div>
                    <input type="text" name="epi_utilises_autres" value="{{ old('epi_utilises_autres') }}" class="mt-2 w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Autres EPI utilisés au quotidien ? Spécifiez...">
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-2">3. Difficultés rencontrées avec les EPI (Choix multiples)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach(['Inconfort', 'Taille inadaptée', 'Usure rapide', 'Manque de renouvellement', 'Non adapté aux conditions climatiques'] as $diff)
                            <label class="border rounded-lg p-3 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="epi_difficultes[]" value="{{ $diff }}" {{ is_array(old('epi_difficultes')) && in_array($diff, old('epi_difficultes')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                                <span class="text-sm text-gray-700">{{ $diff }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 mb-2">Formations et sensibilisations reçues</h3>
                        <div class="space-y-2">
                            @foreach([
                                'Formation en SST reçue',
                                'Formation sur les risques liés aux déchets',
                                'Formation en conduite sécurisée',
                                'Formation en conduite d’engins',
                                'Formation en sécurité incendie',
                                'Aucune formation récente (les 6 derniers mois)'
                            ] as $form)
                                <label class="border rounded-lg p-2.5 flex items-start cursor-pointer hover:bg-gray-50 w-full">
                                    <input type="checkbox" name="formations_recues[]" value="{{ $form }}" {{ is_array(old('formations_recues')) && in_array($form, old('formations_recues')) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 mt-0.5 mr-3">
                                    <span class="text-xs text-gray-700">{{ $form }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Date de la dernière formation reçue</label>
                        <input type="date" name="date_derniere_formation" value="{{ old('date_derniere_formation') }}" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>
            </div>


            <div x-show="step === 5" x-transition.fade class="space-y-6">
                <div class="border-b border-gray-200 pb-3">
                    <h2 class="text-xl font-bold text-gray-900">5. Appréciation Globale & Suggestions</h2>
                    <p class="text-xs text-gray-500">Votre évaluation subjective finale et recommandations terrain.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Comment évaluez-vous votre niveau de risque à ce poste ? *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['Faible', 'Modéré', 'Élevé', 'Très élevé'] as $risq)
                            <label class="border rounded-lg p-3 flex items-center justify-center cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="niveau_risque_agent" value="{{ $risq }}" {{ old('niveau_risque_agent') === $risq ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500 mr-2">
                                <span class="text-sm font-medium text-gray-700">{{ $risq }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('niveau_risque_agent') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="bg-gray-50 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Pensez-vous que des améliorations sont nécessaires ? *</h3>
                    </div>
                    <div class="flex space-x-4 shrink-0">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="ameliorations_necessaires" value="1" {{ old('ameliorations_necessaires') === '1' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Oui</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="ameliorations_necessaires" value="0" {{ old('ameliorations_necessaires') === '0' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Non</span>
                        </label>
                    </div>
                    @error('ameliorations_necessaires') <p class="mt-1 text-xs text-red-600 w-full">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1">Suggestions d’amélioration (Laissé à l’appréciation des agents)</label>
                    <textarea name="suggestions_amelioration" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Exprimez ici librement vos besoins ou propositions d'amélioration (outillage, management, locaux, EPI)...">{{ old('suggestions_amelioration') }}</textarea>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-5 flex items-center justify-between">
                <button type="button"
                        x-show="step > 1"
                        @click="step--"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                    Précédent
                </button>
                <div x-show="step === 1"></div> <button type="button"
                        x-show="step < 5"
                        @click="step++"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition inline-flex items-center space-x-1">
                    <span>Suivant</span>
                    <span>→</span>
                </button>

                <button type="submit"
                        x-show="step === 5"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md transition">
                    Soumettre le questionnaire
                </button>
            </div>
            </form>
        </div>
    </div>

</body>

</html>

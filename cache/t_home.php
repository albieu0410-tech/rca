<?php
$tipContract = [
    'pf'      => 'Persoană fizică',
    'pj'      => 'Persoană juridică',
    'leasing' => 'Leasing',
    'comodat' => 'Comodat',
];

$tipPersoana = [
    'pf'              => 'Persoană fizică',
    'bugetar'         => 'Bugetar',
    'familieb'        => 'Familie',
    'pensionar'       => 'Pensionar',
    'veteran'         => 'Veteran',
    'handicap'        => 'Persoană cu dizabilități',
    'pj'              => 'Persoană juridică',
    'fa'              => 'F.A.',
    'bancare'         => 'Instituții bancare',
    'institutii'      => 'Instituții',
    'sanitar'         => 'Sanitar',
    'cultur,invatamant'=> 'Cultură & învățământ',
    'regii autonome'  => 'Regii autonome',
    'fundatii'        => 'Fundații',
    'corp,institutii' => 'Corp. instituții',
];

$destinatie = [
    'alta'             => 'Altă utilizare',
    'taxi'             => 'Taxi',
    'rent'             => 'Rent-a-car',
    'paza'             => 'Pază',
    'interventie'      => 'Intervenție',
    'curierat'         => 'Curierat',
    'scoala'           => 'Școală',
    'distributie'      => 'Distribuție',
    'construct'        => 'Construcții',
    'agric'            => 'Agricol',
    'forest'           => 'Forestier',
    'transport,internat' => 'Transport internațional',
    'transport,marfa'  => 'Transport marfă',
    'transport,persoan'=> 'Transport persoane',
];

$propulsie = [
    'Gasoline'       => 'Benzină',
    'Diesel'         => 'Motorină',
    'GPL'            => 'GPL',
    'ElectricHybrid' => 'Electric / Hibrid',
];

function renderOptions(array $values, string $selected = ''): string
{
    $html = '';
    foreach ($values as $value => $label) {
        $sel  = $value === $selected ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($value, ENT_QUOTES) . '"' . $sel . '>'
            . htmlspecialchars($label, ENT_QUOTES) . '</option>';
    }

    return $html;
}
?>
<!doctype html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asigurări RCA – demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        }
        header {
            background: #022c43;
            color: #fff;
        }
        .form-section {
            border-left: 4px solid #ffc107;
            padding-left: 1rem;
        }
        .tariff-card {
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .tariff-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
        }
        .loader {
            display: none;
        }
        .loader.active {
            display: inline-block;
        }
    </style>
</head>
<body>
<header class="py-4 mb-4 shadow-sm">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-1">Asiguram.ro – RCA demo</h1>
                <p class="mb-0 opacity-75">Generați oferte și tarife folosind webservice-ul oficial.</p>
            </div>
            <div class="text-lg-end">
                <a class="btn btn-outline-light btn-lg" href="https://kit5.asiguram.ro/rca" target="_blank" rel="noopener">
                    Vezi demo-ul oficial
                </a>
            </div>
        </div>
    </div>
</header>

<main class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h4 mb-3"><i class="bi bi-file-earmark-text me-2 text-warning"></i>Date ofertă</h2>
                    <form id="oferta-form">
                        <div class="mb-4 form-section">
                            <h3 class="h6 text-uppercase text-secondary">Contract</h3>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label" for="datavalabilitate">Data valabilitate</label>
                                    <input type="date" class="form-control" id="datavalabilitate" name="datavalabilitate" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="tipcontract">Tip contract</label>
                                    <select class="form-select" id="tipcontract" name="tipcontract" required>
                                        <option value="">Alege...</option>
                                        <?= renderOptions($tipContract); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 form-section">
                            <h3 class="h6 text-uppercase text-secondary">Asigurat</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_nume">Nume</label>
                                    <input type="text" class="form-control" id="asigurat_nume" name="asigurat[nume]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_tippersoana">Tip persoană</label>
                                    <select class="form-select" id="asigurat_tippersoana" name="asigurat[tippersoana]" required>
                                        <option value="">Alege...</option>
                                        <?= renderOptions($tipPersoana); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_telefon">Telefon</label>
                                    <input type="tel" class="form-control" id="asigurat_telefon" name="asigurat[telefon]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_email">Email</label>
                                    <input type="email" class="form-control" id="asigurat_email" name="asigurat[email]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_cnpcui">CNP/CUI</label>
                                    <input type="text" class="form-control" id="asigurat_cnpcui" name="asigurat[cnpcui]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_destinatie">Destinație vehicul</label>
                                    <select class="form-select" id="asigurat_destinatie" name="asigurat[destinatie]" required>
                                        <option value="">Alege...</option>
                                        <?= renderOptions($destinatie); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="asigurat_permisan">An permis</label>
                                    <input type="number" min="1900" max="2100" class="form-control" id="asigurat_permisan" name="asigurat[permisan]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="asigurat_permisluna">Luna permis</label>
                                    <input type="number" min="1" max="12" class="form-control" id="asigurat_permisluna" name="asigurat[permisluna]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="asigurat_copii">Copii</label>
                                    <input type="number" min="0" class="form-control" id="asigurat_copii" name="asigurat[copii]" value="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_localitate">Localitate</label>
                                    <input type="text" class="form-control" id="asigurat_localitate" name="asigurat[localitate]">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="asigurat_judet">Județ</label>
                                    <input type="text" class="form-control" id="asigurat_judet" name="asigurat[judet]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="asigurat_sector">Sector</label>
                                    <input type="text" class="form-control" id="asigurat_sector" name="asigurat[sector]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="asigurat_casco">Casco existent</label>
                                    <select class="form-select" id="asigurat_casco" name="asigurat[casco]">
                                        <option value="">N/A</option>
                                        <option value="da">Da</option>
                                        <option value="nu">Nu</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="asigurat_telefon_utilizator">Telefon utilizator</label>
                                    <input type="tel" class="form-control" id="asigurat_telefon_utilizator" name="utilizator[telefon]">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="asigurat_adresa_str">Adresă (stradă, număr)</label>
                                    <div class="row g-2">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="asigurat_adresa_str" name="asigurat[adresa][str]" placeholder="Strada">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="asigurat[adresa][nr]" placeholder="Nr.">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 form-section">
                            <h3 class="h6 text-uppercase text-secondary">Vehicul</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="vehicul_inmatriculare">Număr înmatriculare</label>
                                    <input type="text" class="form-control" id="vehicul_inmatriculare" name="vehicul[inmatriculare]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="vehicul_codcateg">Cod categorie</label>
                                    <input type="text" class="form-control" id="vehicul_codcateg" name="vehicul[codcateg]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="vehicul_categorie">Categorie</label>
                                    <input type="text" class="form-control" id="vehicul_categorie" name="vehicul[categorie]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="vehicul_marca">Marcă</label>
                                    <input type="text" class="form-control" id="vehicul_marca" name="vehicul[marca]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="vehicul_model">Model</label>
                                    <input type="text" class="form-control" id="vehicul_model" name="vehicul[model]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="vehicul_anfabricatie">An fabricație</label>
                                    <input type="number" min="1900" max="2100" class="form-control" id="vehicul_anfabricatie" name="vehicul[anfabricatie]" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_cilindree">Cilindree (cc)</label>
                                    <input type="number" min="0" class="form-control" id="vehicul_cilindree" name="vehicul[cilindree]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_cp">CP</label>
                                    <input type="number" min="0" class="form-control" id="vehicul_cp" name="vehicul[cp]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_kg">Greutate (kg)</label>
                                    <input type="number" min="0" class="form-control" id="vehicul_kg" name="vehicul[kg]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_locuri">Locuri</label>
                                    <input type="number" min="1" class="form-control" id="vehicul_locuri" name="vehicul[locuri]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_propulsie">Propulsie</label>
                                    <select class="form-select" id="vehicul_propulsie" name="vehicul[propulsie]" required>
                                        <option value="">Alege...</option>
                                        <?= renderOptions($propulsie); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_seriesasiu">Serie șasiu</label>
                                    <input type="text" class="form-control" id="vehicul_seriesasiu" name="vehicul[seriesasiu]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_serieciv">Serie CIV</label>
                                    <input type="text" class="form-control" id="vehicul_serieciv" name="vehicul[serieciv]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_nrinm">Număr de înmatriculare</label>
                                    <input type="text" class="form-control" id="vehicul_nrinm" name="vehicul[nrinm]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vehicul_parcauto">Parc auto</label>
                                    <select class="form-select" id="vehicul_parcauto" name="vehicul[parcauto]">
                                        <option value="">Nu</option>
                                        <option value="da">Da</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-warning btn-lg" type="submit">
                                <span class="spinner-border spinner-border-sm loader" role="status" aria-hidden="true"></span>
                                <span class="btn-text">Solicită tarife</span>
                            </button>
                        </div>
                    </form>
                    <div class="alert mt-4 d-none" role="alert" id="oferta-feedback"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h2 class="h4 mb-3"><i class="bi bi-cash-coin me-2 text-success"></i>Tarife disponibile</h2>
                    <div id="tarife-container" class="d-grid gap-3">
                        <p class="text-muted" id="tarife-placeholder">Completează formularul pentru a încărca tarifele disponibile.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
(function () {
    const form = document.getElementById('oferta-form');
    const feedback = document.getElementById('oferta-feedback');
    const tarifeContainer = document.getElementById('tarife-container');
    const tarifePlaceholder = document.getElementById('tarife-placeholder');
    const loader = document.querySelector('.loader');
    const btnText = document.querySelector('.btn-text');

    function showFeedback(type, message) {
        if (!feedback) return;
        feedback.className = 'alert mt-4 alert-' + type;
        feedback.textContent = message;
        feedback.classList.remove('d-none');
    }

    function clearTarife(message) {
        if (tarifeContainer) {
            tarifeContainer.innerHTML = '';
            if (message) {
                const p = document.createElement('p');
                p.className = 'text-muted';
                p.textContent = message;
                tarifeContainer.appendChild(p);
            }
        }
    }

    async function fetchTarife(idOferta) {
        if (!idOferta) return;
        try {
            const response = await fetch('index.php?t=tarife&id=' + encodeURIComponent(idOferta), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const html = await response.text();
            tarifeContainer.innerHTML = html;
        } catch (error) {
            clearTarife('A apărut o eroare la încărcarea tarifelor.');
        }
    }

    if (form) {
        const today = new Date().toISOString().split('T')[0];
        const dateField = document.getElementById('datavalabilitate');
        if (dateField && !dateField.value) {
            dateField.value = today;
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            feedback.classList.add('d-none');
            if (!form.reportValidity()) {
                return;
            }

            clearTarife('Se generează oferta...');
            loader?.classList.add('active');
            if (btnText) {
                btnText.textContent = 'Se procesează...';
            }

            try {
                const formData = new FormData(form);
                const response = await fetch('index.php?t=submitoferta', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    showFeedback('danger', payload.message || 'Nu am putut genera oferta.');
                    clearTarife('');
                    return;
                }

                showFeedback('success', 'Oferta a fost creată. ID: ' + payload.idoferta);
                fetchTarife(payload.idoferta);
            } catch (error) {
                console.error(error);
                showFeedback('danger', 'A apărut o eroare neprevăzută.');
                clearTarife('');
            } finally {
                loader?.classList.remove('active');
                if (btnText) {
                    btnText.textContent = 'Solicită tarife';
                }
            }
        });
    }
})();
</script>
</body>
</html>

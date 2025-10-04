<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    return;
}

header('Content-Type: application/json');

$datavalabilitate = trim($_POST['datavalabilitate'] ?? '');
$tipcontract      = trim($_POST['tipcontract'] ?? '');
$asigurat         = $_POST['asigurat'] ?? [];
$utilizatorInput  = $_POST['utilizator'] ?? [];
$vehicul          = $_POST['vehicul'] ?? [];

$required = [
    'datavalabilitate' => $datavalabilitate,
    'tipcontract'      => $tipcontract,
    'asigurat_nume'    => $asigurat['nume'] ?? '',
    'asigurat_email'   => $asigurat['email'] ?? '',
    'asigurat_telefon' => $asigurat['telefon'] ?? '',
    'vehicul_inmatriculare' => $vehicul['inmatriculare'] ?? '',
    'vehicul_codcateg'      => $vehicul['codcateg'] ?? '',
    'vehicul_categorie'     => $vehicul['categorie'] ?? '',
    'vehicul_marca'         => $vehicul['marca'] ?? '',
    'vehicul_model'         => $vehicul['model'] ?? '',
    'vehicul_anfabricatie'  => $vehicul['anfabricatie'] ?? '',
    'vehicul_propulsie'     => $vehicul['propulsie'] ?? '',
];

foreach ($required as $key => $value) {
    if (trim((string) $value) === '') {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Missing required field: ' . $key,
        ]);
        return;
    }
}

$asigurat = array_map(static function ($value) {
    return is_array($value) ? array_map('trim', $value) : trim((string) $value);
}, $asigurat);

$utilizatorInput = array_map(static function ($value) {
    return is_array($value) ? array_map('trim', $value) : trim((string) $value);
}, $utilizatorInput);

$vehicul = array_map(static function ($value) {
    return is_array($value) ? array_map('trim', $value) : trim((string) $value);
}, $vehicul);

if (empty($asigurat['destinatie'])) {
    $asigurat['destinatie'] = 'alta';
}

$utilizator = $asigurat;
if (!empty($utilizatorInput)) {
    $utilizator = array_merge($utilizator, array_filter($utilizatorInput, static function ($value) {
        return $value !== '' && $value !== null;
    }));
}

$payload = [
    'datavalabilitate' => $datavalabilitate,
    'tipcontract'      => $tipcontract,
    'asigurat'         => $asigurat,
    'utilizator'       => $utilizator,
    'vehicul'          => $vehicul,
];

try {
    $response = adauga_oferta($payload);
    $id = $response['idoferta'] ?? '';

    if ($id === '') {
        throw new RuntimeException('Server did not return an offer ID.');
    }

    echo json_encode([
        'success'  => true,
        'idoferta' => $id,
    ]);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage(),
    ]);
}

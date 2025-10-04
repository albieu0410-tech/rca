<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed',
        'code'    => 'method_not_allowed',
    ]);
    return;
}

header('Content-Type: application/json');

$datavalabilitate = trim($_POST['datavalabilitate'] ?? '');
$tipcontract      = trim($_POST['tipcontract'] ?? '');
$asigurat         = $_POST['asigurat'] ?? [];
$utilizatorInput  = $_POST['utilizator'] ?? [];
$vehicul          = $_POST['vehicul'] ?? [];

if ($datavalabilitate !== '') {
    $timestamp = strtotime($datavalabilitate);
    if ($timestamp !== false) {
        $datavalabilitate = date('Y-m-d', $timestamp);
    }
}

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
        echo json_encode([
            'success' => false,
            'message' => 'Missing required field: ' . $key,
            'code'    => 'validation_error',
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
    $isSoapFault = class_exists('SoapFault', false) && $exception instanceof SoapFault;

    if ($isSoapFault) {
        $rawMessage = trim((string) $exception->getMessage());
        $message    = $rawMessage !== '' ? $rawMessage : 'Autentificarea la serviciul SOAP a eșuat.';

        if (stripos($rawMessage, 'autent') !== false) {
            $message = 'Autentificarea la webservice a eșuat. Verificați user/parola din config.php.';
        }

        error_log('[submitoferta] SOAP fault: ' . $rawMessage);

        echo json_encode([
            'success'    => false,
            'message'    => $message,
            'code'       => 'soap_fault',
            'soap_fault' => $rawMessage,
        ]);

        return;
    }

    error_log('[submitoferta] Exception: ' . $exception->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'A apărut o eroare internă. Reîncercați sau contactați administratorul.',
        'code'    => 'internal_error',
    ]);
}

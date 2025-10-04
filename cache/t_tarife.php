<?php
$idOferta = trim($_GET['id'] ?? '');

if ($idOferta === '') {
    http_response_code(400);
    echo '<div class="alert alert-danger">Oferta nu a fost specificată.</div>';
    return;
}

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

try {
    $payload = tarife_oferta($idOferta);
} catch (Throwable $exception) {
    http_response_code(500);
    echo '<div class="alert alert-danger">' . htmlspecialchars($exception->getMessage(), ENT_QUOTES) . '</div>';
    return;
}

$finalValue = $payload['ofertafinalizata'] ?? false;
if (is_string($finalValue)) {
    $finalValue = strtolower($finalValue);
    $finalized = in_array($finalValue, ['1', 'true', 'da', 'yes'], true);
} elseif (is_numeric($finalValue)) {
    $finalized = (int) $finalValue === 1;
} else {
    $finalized = (bool) $finalValue;
}

header('X-Offer-Finalized: ' . ($finalized ? 'yes' : 'no'));

$tarife = $payload['tarif'] ?? [];

if (empty($tarife)) {
    if ($finalized) {
        echo '<div class="alert alert-warning">Nu sunt disponibile tarife pentru această ofertă.</div>';
    } else {
        echo '<div class="alert alert-info">Oferta este în curs de calculare. Vom reîncerca automat...</div>';
    }
    return;
}

if (!$finalized) {
    echo '<div class="alert alert-info">Oferta este în curs de finalizare. Tarifele pot fi provizorii.</div>';
}

foreach ($tarife as $entry) {
    $entry = is_array($entry) ? $entry : (array) $entry;
    $societate = htmlspecialchars($entry['societate'] ?? 'Societate necunoscută', ENT_QUOTES);
    $tarif12   = htmlspecialchars($entry['tarif12'] ?? '-', ENT_QUOTES);
    $tarif6    = htmlspecialchars($entry['tarif6'] ?? '-', ENT_QUOTES);
    $tarif1    = htmlspecialchars($entry['tarif1'] ?? '-', ENT_QUOTES);
    $bm12      = htmlspecialchars($entry['bm12'] ?? '-', ENT_QUOTES);
    $bm6       = htmlspecialchars($entry['bm6'] ?? '-', ENT_QUOTES);
    $cod6      = htmlspecialchars($entry['cod6'] ?? '-', ENT_QUOTES);
    $cod12     = htmlspecialchars($entry['cod12'] ?? '-', ENT_QUOTES);

    echo '<div class="card tariff-card border-0 shadow-sm">';
    echo '  <div class="card-body">';
    echo '      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">';
    echo '          <div>';
    echo '              <h3 class="h5 mb-1">' . $societate . '</h3>';
    echo '              <p class="mb-0 text-muted">Bonus-Malus 12: ' . $bm12 . ' &middot; Bonus-Malus 6: ' . $bm6 . '</p>';
    echo '          </div>';
    echo '          <span class="badge bg-success-subtle text-success fw-semibold">Oferta #' . htmlspecialchars($idOferta, ENT_QUOTES) . '</span>';
    echo '      </div>';
    echo '      <hr class="my-3">';
    echo '      <div class="row text-center g-3">';
    echo '          <div class="col-4">';
    echo '              <p class="text-uppercase text-secondary mb-1">12 luni</p>';
    echo '              <span class="h5 mb-0">' . $tarif12 . ' Lei</span>';
    echo '          </div>';
    echo '          <div class="col-4">';
    echo '              <p class="text-uppercase text-secondary mb-1">6 luni</p>';
    echo '              <span class="h5 mb-0">' . $tarif6 . ' Lei</span>';
    echo '          </div>';
    echo '          <div class="col-4">';
    echo '              <p class="text-uppercase text-secondary mb-1">1 lună</p>';
    echo '              <span class="h5 mb-0">' . $tarif1 . ' Lei</span>';
    echo '          </div>';
    echo '      </div>';
    echo '      <div class="mt-3 small text-muted">Cod 12 luni: ' . $cod12 . ' &middot; Cod 6 luni: ' . $cod6 . '</div>';
    echo '  </div>';
    echo '</div>';
}

<?php
// config.php — Laragon (http://ariguram.test/) setup

global $_CONFIG;
$_CONFIG = [];

/* ---------- Webservice broker ---------- */
$_CONFIG['ws_username']  = 'webservice';
$_CONFIG['ws_parola']    = 'webservice';
$_CONFIG['ws_brokerurl'] = 'http://demo.asiguram.ro/webservice.php';

/* ---------- Base URL & site pin ---------- */
/* The kit is served by Laragon at http://ariguram.test/
   IMPORTANT: keep the trailing slash. If you ever move the kit
   into a subfolder, change to e.g. 'http://ariguram.test/kit/' */
$_CONFIG['ws_merch_kiturl'] = 'http://ariguram.test/';
$_CONFIG['kiturl']          = $_CONFIG['ws_merch_kiturl'];

$_CONFIG['sitepin'] = ''; // set your own secret in production

/* ---------- Cache ---------- */
/* Create E:\laragon\www\ariguram\cache (writable) */
$_CONFIG['cachefolder'] = 'cache';

// For testing only, you could enable a dummy card by uncommenting:
// $_CONFIG['dummycard'] = 'yes';

/* ---------- Payment gateways (examples; keep disabled until configured) ---------- */
/*
$_CONFIG['librapay'] = 'no';
$_CONFIG['ws_merch_name']   = '...';
$_CONFIG['ws_merch_url']    = '...';
$_CONFIG['ws_merch_kiturl'] = $_CONFIG['kiturl'];
$_CONFIG['ws_merchant']     = '.....';
$_CONFIG['ws_merch_terminal']= '....';
$_CONFIG['ws_merch_key']    = '......';
$_CONFIG['ws_merch_backref']= '.....';
$_CONFIG['ws_merch_email']  = '....';
// $_CONFIG['ws_merch_action']= 'https://secure.librapay.ro/pay_auth.php'; // live
$_CONFIG['ws_merch_action'] = 'http://tcom.librapay.ro/pay_auth.php';     // test
$_CONFIG['ws_merch_backurl']= '.....';

$_CONFIG['euplatesc']            = 'no';
$_CONFIG['euplatesc_mid']        = '...';
$_CONFIG['euplatesc_key']        = '...';
$_CONFIG['euplatesc_ratebcr']    = 'yes';
$_CONFIG['euplatesc_ratebtrl']   = 'yes';
$_CONFIG['euplatesc_ratealphabank']= 'yes';

$_CONFIG['unicredit']          = 'no';
$_CONFIG['ws_merch_name']      = '.....';
$_CONFIG['ws_merch_url']       = '....';
$_CONFIG['ws_merchant']        = '....';
$_CONFIG['ws_merch_terminal']  = '...X101';
$_CONFIG['ws_merch_key']       = '....';
$_CONFIG['ws_merch_backref']   = '....';
$_CONFIG['ws_merch_email']     = '....';
// $_CONFIG['ws_merch_action']  = 'https://www.secure11gw.ro/portal/cgi-bin/'; // live
$_CONFIG['ws_merch_action']    = 'https://www.activare3dsecure.ro/teste3d/cgi-bin/'; // test
$_CONFIG['ws_merch_backurl']   = '.....';

$_CONFIG['mobilpay']           = 'no';
$_CONFIG['mobilpay_mid']       = '...';
$_CONFIG['mobilpay_confirmUrl']= $_CONFIG['kiturl'].'mobilpayipn.php';
$_CONFIG['mobilpay_returnUrl'] = $_CONFIG['kiturl'].'site.php?t=thankyou&offid='.(isset($_GET['offid']) ? intval($_GET['offid']) : 0);
$_CONFIG['mobilpay_cert']      = '.cer';
$_CONFIG['mobilpay_key']       = '.key';
*/

/* ---------- Messages ---------- */
$_CONFIG['mesaj_multumire'] = 'Multumim. Un operator va prelua comanda.';
$_CONFIG['mesaj_eroare']    = 'Multumim pentru comanda, un operator va prelua comanda imediat ce e posibil.';
$_CONFIG['mesaj_plata']     = 'Multumim pentru comanda, un operator va prelua comanda imediat ce e posibil.';

// $_CONFIG['nosidebar'] = 'yes';

// Example cache helpers (if used by your template system):
// cache_setvalue("load_in_template","../cache/temp2_31.php");
// cache_setvalue("load_in_css","../cache/css_31.php");
// In template: echo cache_getvalue("body"); cache_setvalue("body","");

?>

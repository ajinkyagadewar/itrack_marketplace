<?php	
define('NO_DEBUG_DISPLAY', true);

require_once "../../../../config.php";
require_once $CFG->dirroot . '/local/buykart/lib.php';

// Require this for curl class
require_once $CFG->libdir . '/filelib.php';

/// Keep out casual intruders
if (empty($_POST) or !empty($_GET)) {
	print_error("Sorry, you can not use the script that way.");
}

/// Read all the data from PayPal and get it ready for later;
/// we expect only valid UTF-8 encoding, it is the responsibility
/// of user to set it up properly in PayPal business account,
/// it is documented in docs wiki.

$req = 'cmd=_notify-validate';

$data = new stdClass();

foreach ($_POST as $key => $value) {
	$req .= "&$key=" . urlencode($value);
	$data->$key = $value;
}

// GET THE PAYPAL GATEWAY TO BE USED
// THE CUSTOM FIELD IS THE TRANSACTION ID
$gateway = new BuykartGatewayPaypal((int) $data->custom);
$secret_key = get_config('local_buykart','payment_ebs_SecretKey');
//$secret_key = $GATEWAY['secretkey'];	 // Your Secret Key

if(isset($_GET['DR'])) {
	require('Rc43.php');
	$DR = preg_replace("/\s/","+",$_GET['DR']);

	$rc4 = new Crypt_RC4($secret_key);
	$QueryString = base64_decode($DR);
	$rc4->decrypt($QueryString);
	$QueryString = split('&',$QueryString);

	$response = array();
	foreach($QueryString as $param){
		$param = split('=',$param);
		$response[$param[0]] = urldecode($param[1]);
	}
}
$fee = $response['amount'];
$transid = $response['PaymentID'];
$gateway = new BuykartGatewayPaypal((int) $data->custom);
// CONFIRM NOTIFICATION WITH PAYPAL
$c = new curl();
$options = array(
	'returntransfer' => true,
	'httpheader' => array('application/x-www-form-urlencoded', "Host: www.ebs.in"),
	'timeout' => 30,
	'CURLOPT_HTTP_VERSION' => CURL_HTTP_VERSION_1_1
	);
$location = $gateway->get_url();
$result = $c->post($location, $req, $options);
// Read the response from Paypal
if (0 < strlen($result) && strcmp($result, "VERIFIED") == 0) {
	// If we are here, it means the payment was a valid paypal transaction
	// So now we get the gateway to validate and handle the transaction info
	$gateway->handle($data);
}

exit;

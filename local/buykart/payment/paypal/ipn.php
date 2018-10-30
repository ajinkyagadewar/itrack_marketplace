<?php
/**
 * Buykart Listener for Instant Payment Notification from Paypal
 *
 * @package     local
 * @subpackage  local_moodec
 * @author      Thomas Threadgold - based on code by others (Paypal Enrolment plugin)
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Disable moodle specific debug messages and any errors in output,
// comment out when debugging or better look into error log!
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
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/buykart/payment/paypal/ipn.php');
$req = 'cmd=_notify-validate';
$data = new stdClass();
foreach ($_POST as $key => $value) {
	$req .= "&$key=" . urlencode($value);
	$data->$key = $value;
}
// GET THE PAYPAL GATEWAY TO BE USED
// THE CUSTOM FIELD IS THE TRANSACTION ID
$gateway = new BuykartGatewayPaypal((int) $data->custom);
// CONFIRM NOTIFICATION WITH PAYPAL
$c = new curl();
// Checks if sandbox mode is enabled
if (!!get_config('local_buykart', 'payment_paypal_sandbox')) {
	$gatewayURL = 'www.sandbox.paypal.com'; // the Paypal sandbox URL
} else {
	$gatewayURL = 'www.paypal.com';
}

$options = array(
	'returntransfer' => true,
	'httpheader' => array('application/x-www-form-urlencoded', "Host: $gatewayURL"),
	'timeout' => 30,
	'CURLOPT_HTTP_VERSION' => CURL_HTTP_VERSION_1_1
);
$location = $gateway->get_url();
$result = $c->post($location, $req, $options);

// Read the response from Paypal
if (0 < strlen($result) && strcmp($result, "VERIFIED") == 0) {
	// If we are here, it means the payment was a valid paypal transaction
	// So now we get the gateway to validate and handle the transaction info
	$success = $gateway->handle($data);
		 if($success){
			redirect(new moodle_url($CFG->wwwroot.'/'),'');
		}
	}
exit;

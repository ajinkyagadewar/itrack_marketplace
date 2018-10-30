<?php 
require("../../../../config.php");
require_once $CFG->dirroot . '/local/buykart/lib.php';
require_login(0,false);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/buykart/payment/stripe/payment.php');
//require_once("lib.php");

$data = new stdClass();

$data->cmd = optional_param('cmd', '',PARAM_RAW);
$data->charset = required_param('charset', PARAM_RAW);
$data->item_name = required_param('item_name', PARAM_TEXT);
$data->item_name = required_param('item_number', PARAM_TEXT);
$data->item_name = required_param('quantity', PARAM_INT);
$data->on0 = optional_param('on0', array(), PARAM_TEXT);
$data->os0 = optional_param('os0', array(), PARAM_TEXT);
$data->custom = optional_param('custom', array(), PARAM_RAW);
$data->mc_currency = required_param('currency_code', PARAM_RAW);
$data->amount = required_param('amount', PARAM_RAW);
$data->for_auction = required_param('for_auction', PARAM_BOOL);
$data->no_note = required_param('no_note', PARAM_INT);
$data->no_shipping = required_param('no_shipping', PARAM_INT);
$data->rm = required_param('rm', PARAM_RAW);
$data->cbt = optional_param('cbt', array(), PARAM_TEXT);
$data->first_name = required_param('first_name', PARAM_TEXT);
$data->last_name = required_param('last_name', PARAM_TEXT);
$data->address = optional_param('address', array(), PARAM_TEXT);
$data->city = optional_param('city', array(), PARAM_TEXT);
$data->email = required_param('email', PARAM_EMAIL);
$data->country = optional_param('country', array(), PARAM_TEXT);
$data->stripeToken = required_param('stripeToken', PARAM_RAW);
$data->stripeTokenType = required_param('stripeTokenType', PARAM_RAW);
$data->stripeEmail = required_param('stripeEmail', PARAM_EMAIL);
try {	
	require_once('lib/Stripe.php');
	//require_once('Stripe/lib/Stripe.php');
	$secretkey = get_config('local_buykart','payment_stripe_secret_key');
	Stripe::setApiKey($secretkey); //Replace with your Secret Key
	
	$charge = Stripe_Charge::create(array(
		"amount" => ($data->amount *100),
		"currency" => required_param('currency_code', PARAM_RAW),
		"card" => required_param('stripeToken', PARAM_RAW),
		"description" => "Demo Transaction"
		));
	 $getuser = $DB->get_record('user', array('email' => $USER->email));
	// if ($getuser) {
	// 	$data->custom  = $getuser->id;
	// } else {
	// 	$this->send_error_to_admin("Not a valid user id", $data);
	// 	die();
	// }
	$data->txn_id = $charge->balance_transaction;
	$data->memo = $charge->id;
	$data->payment_status = $charge->status;
	$data->timeupdated      = time();
	$data->pending_reason = $charge->failure_message;
	$data->reason_code = $charge->failure_code;
	$gateway = new BuykartGatewayStripe((int) $data->rm);
	$success = $gateway->handle($data);
	if($success){
		redirect(new moodle_url($CFG->wwwroot.'/'),'');
	}
	exit;		
}
catch(Stripe_CardError $e) {
	
}
//catch the errors in any way you like
catch (Stripe_InvalidRequestError $e) {
  // Invalid parameters were supplied to Stripe's API

} catch (Stripe_AuthenticationError $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (Stripe_ApiConnectionError $e) {
  // Network communication with Stripe failed
} catch (Stripe_Error $e) {
// Display a very generic error to the user, and maybe send
// yourself an email
} catch (Exception $e) {
// Something else happened, completely unrelated to Stripe
}
?>
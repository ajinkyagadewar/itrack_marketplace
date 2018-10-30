<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public Licens
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Listens for Instant Payment Notification from payumoney
 *
 * This script waits for Payment notification from payumoney,
 * then double checks that data by sending it back to payumoney.
 * If payumoney verifies this then it sets up the enrolment for that
 * user.
 *
 * @package    enrol_buykart
 * @copyright 2010 Eugene Venter
 * @author     Eugene Venter - based on code by others
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Disable moodle specific debug messages and any errors in output,
// comment out when debugging or better look into error log!
define('NO_DEBUG_DISPLAY', true);

require("../../../../config.php");
//require_once("lib.php");
require_once($CFG->libdir.'/eventslib.php');
require_once($CFG->libdir.'/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

require_once $CFG->dirroot . '/local/buykart/lib.php';

// payumoney does not like when we return error messages here,
// the custom handler just logs exceptions and stops.
set_exception_handler('enrol_buykart_ipn_exception_handler');

/// Keep out casual intruders
if (empty($_POST) or !empty($_GET)) {
	print_error("Sorry, you can not use the script that way.");
}
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/buykart/payment/payumoney/ipn.php');

/// Read all the data from payumoney and get it ready for later;
/// we expect only valid UTF-8 encoding, it is the responsibility
/// of user to set it up properly in payumoney business account,
/// it is documented in docs wiki.

$req = 'cmd=_notify-validate';
$strstatus = 0;
$statuschck = 0;
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$udf5 = $_POST["udf5"];
$udf4 = $_POST["udf4"];
$udf3 = $_POST["udf3"];
$udf2 = $_POST["udf2"];
$udf1 = $_POST["udf1"];
// Merchant Salt as provided by Payu
//$merchantsalt = "GQs7yium";
$salt = get_config('local_buykart','payment_payumoney_salt');
if (isset($_POST["additionalCharges"])) {
	$additionalCharges=$_POST["additionalCharges"];
	$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
}
else {
	$retHashSeq = $salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
}
$hash = hash("sha512", $retHashSeq);
if ($hash != $posted_hash) {
	$this->send_error_to_admin(get_string('invalidtr','local_buykart'),$data);
	$strstatus = 0;
}else {
	$strstatus = 1;
	$statuschck = 1;
}
$data = new stdClass();
foreach ($_POST as $key => $value) {
	$req .= "&$key=".urlencode($value);
	$data->$key = $value;
}
$getuser = $DB->get_record('user', array('email' => $data->email));
if ($getuser) {
	$data->userid  = $getuser->id;
} else {
	$this->send_error_to_admin(get_string('notvaliduser','local_buykart'), $data);
	die;
}
$data->payment_gross    = $data->amount;
$data->payment_status = $data->status;
$data->timeupdated      = time();
$data->custom = $udf1;
// GET THE PAYUMONEY GATEWAY TO BE USED
// THE CUSTOM FIELD IS THE TRANSACTION ID
$gateway = new BuykartGatewayPayumoney((int) $data->custom);
//print_object($gateway);
$success = $gateway->handle($data);
		 if($success){
			redirect(new moodle_url($CFG->wwwroot.'/'),'');
		}
exit;
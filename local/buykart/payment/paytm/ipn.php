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
define('NO_DEBUG_DISPLAY', false);

require("../../../../config.php");
//require_once("lib.php");
require_once($CFG->libdir.'/eventslib.php');
require_once($CFG->libdir.'/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

require_once $CFG->dirroot . '/local/buykart/lib.php';

// payumoney does not like when we return error messages here,
// the custom handler just logs exceptions and stops.
//set_exception_handler('enrol_buykart_ipn_exception_handler');

/// Keep out casual intruders
if (empty($_POST) or !empty($_GET)) {
    print_error("Sorry, you can not use the script that way.");
}
/// Read all the data from payumoney and get it ready for later;
/// we expect only valid UTF-8 encoding, it is the responsibility
/// of user to set it up properly in payumoney business account,
/// it is documented in docs wiki.
require_login(0,false);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/buykart/payment/paytm/ipn.php');
$req = 'cmd=_notify-validate';
$pfError = false;
$pfErrMsg = '';
$pfDone = false;
$pfData = array();
$pfParamString = '';
global $CFG, $USER;
require_once($CFG->dirroot.'/local/buykart/payment/paytm/lib/encdec_paytm-mcrypt.php'); // this is needed for the paytm checksum array
$checksum = '';
$merchant_id = get_config('local_buykart', 'payment_paytm_merchant_mid');
$merchant_key = get_config('local_buykart', 'payment_paytm_merchant_key');
$mwebsite = get_config('local_buykart', 'payment_paytm_merchant_website');
//// Get data sent by Paytm
$paramList = $_POST;
$paytmChecksum = isset($paramList["CHECKSUMHASH"]) ? $paramList["CHECKSUMHASH"] : "";
$isValidChecksum = verifychecksum_e($paramList, $merchant_key, $paytmChecksum);
if($isValidChecksum == "1" || $isValidChecksum == "TRUE") {
	$data = new stdClass();
	foreach ($_POST as $key => $value) {
		$req .= "&$key=".urlencode($value);
		$data->$key = $value;
	}
	$transaction_amunt = $_POST['TXNAMOUNT'];
	$transaction_id = $_POST['TXNID'];
	$transaction_orderid = $_POST['ORDERID'];
	$getuser = $DB->get_record('user', array('email' => $USER->email));
	if ($getuser) {
		$data->userid  = $getuser->id;
	} else {
		$this->send_error_to_admin("Not a valid user id", $data);
		die();
	}
	$data->payment_status = $data->STATUS;
	$data->mc_currency = $data->CURRENCY;
	$data->amount = $data->TXNAMOUNT;
	$data->timeupdated      = time();
	$data->custom = $data->MERC_UNQ_REF;
	$data->txn_id = $data->TXNID;
	print_object($data);exit;
	//$data->txn_id = $transaction_orderid;
	// need to send moodle order id to paytm transaction id
	// GET THE PAYTM GATEWAY TO BE USED
	// THE CUSTOM FIELD IS THE TRANSACTION ID
	$gateway = new BuykartGatewayPaytm((int) $data->custom);
	$success = $gateway->handle($data);
		 if($success){
			redirect(new moodle_url($CFG->wwwroot.'/'),'');
		}
	exit;
}
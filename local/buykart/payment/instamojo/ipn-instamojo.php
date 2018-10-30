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
require_once($CFG->dirroot.'/local/buykart/payment/instamojo/Instamojo.php');
$apikey = get_config('local_buykart','payment_instamojo_apikey');
$token = get_config('local_buykart','payment_instamojo_auth_token');

$api = new Instamojo\Instamojo($apikey,$token,'https://test.instamojo.com/api/1.1/');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/buykart/payment/instamojo/ipn-instamojo.php');
$payid = $_GET['payment_request_id'];
try {
	$response = $api->paymentRequestStatus($payid);
	$vid = explode('|',$response['purpose']);
	if($response){
    //success message display here
		$data = new stdClass();
		$getuser = $DB->get_record('user', array('email' => $USER->email));
		if ($getuser) {
			$data->userid  = $getuser->id;
		} else {
			$this->send_error_to_admin("Not a valid user id", $data);
		}
		
		$data->payment_status = $response['status'];
		$data->currency = $response['payments'][0]['currency'];
		$data->amount = $response['amount'];
		$data->timeupdated = time();
		//for update transaction table taking id in custom value here 
		$data->custom = $vid[1];
		$data->txn_id = $vid[2];
		$gateway = new BuykartGatewayInstamojo((int) $data->custom);
		//transaction table object is handovering gateway handle for checking status here. 
		 $success = $gateway->handle($data);
		 if($success){
			redirect(new moodle_url($CFG->wwwroot.'/'),'');
		}
	}
}
catch (Exception $e) {
	print('Error: ' . $e->getMessage());
}	
exit();
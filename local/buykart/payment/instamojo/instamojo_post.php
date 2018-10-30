<?php
//print_r($_POST);
require_once dirname(__FILE__) . '/../../../../config.php';

// Load buykart lib
require_once dirname(__FILE__) . '/../../lib.php';

require_once($CFG->dirroot.'/local/buykart/payment/instamojo/Instamojo.php');
//assigning value of varible here 
$apikey = get_config('local_buykart','payment_instamojo_apikey');
$token =$_POST['token'];
$purpose = $_POST['purpose'];
$amount = $_POST['amount'];
$phone = $_POST['phone'];
$smsflag = false;
if (!empty($phone)) {
	$smsflag = true;
}
$txnid = $_POST['txnid'];
$iorderid = $_POST['iorderid'];
$redirect_url =$_POST['redirect_url'];
$courseid =$_POST['courseid'];
$userid =$_POST['userid'];
$orderid = $_POST['orderid'];
//print_object($orderid);
$currency =$_POST['currency'];
$api = new Instamojo\Instamojo($apikey,$token,'https://test.instamojo.com/api/1.1/');
        try {
            $response = $api->paymentRequestCreate(array(
                'orderid' => $orderid,
                'purpose' => $purpose.'|'.$orderid.'|'.$iorderid,
                'amount' => $amount,
                'phone' => $phone,
                'send_email' => true,
                'email' => 'foo@example.com',
                'send_sms' => $smsflag,
                'redirect_url' => $redirect_url,
                'courseid' =>$courseid,
                'userid' =>$userid,
                'currency'=>$currency,
                'allow_repeated_payments' => false
                ));
            $payurl = $response['longurl'];
           //print_object($response);
           header("Location:$payurl");
            exit();
        }
        catch (Exception $e) {
            print('Error: ' . $e->getMessage());
        }

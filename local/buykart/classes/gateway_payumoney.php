<?php            

/**
 * Buykart Gateway Payumoney
 *
 * @package     local
 * @subpackage  local_buykart
 * @author      Thomas Threadgold
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Load Moodle config
require_once dirname(__FILE__) . '/../../../config.php';
// Load Buykart lib
require_once dirname(__FILE__) . '/../lib.php';

class BuykartGatewayPayumoney extends BuykartGateway {

    function __construct($transaction) {
        parent::__construct($transaction);
        $this->_gatewayName = get_string('payment_payumoney_title', 'local_buykart');
    }

    // handle the IPN notification  
    public function handle($data = null) {
        global $DB, $CFG;
        require_once $CFG->libdir . '/eventslib.php';
        $this->_transaction->set_gateway(BUYKART_GATEWAY_PAYUMONEY);
        // CHECK TRANSACTION CURRENT STATUS
        if ($this->_transaction->get_status() === BuykartTransaction::STATUS_COMPLETE) {
            // this transaction has already been marked as complete, so we don't want to go
            // through the process again
            return false;
        }
        if (is_null($data)) {
            $this->_transaction->fail();
            return false;
        }

        // If status is not completed or pending then unenrol the student if already enrolled
        // and notify admin
        if ($data->payment_status != "success" and $data->payment_status != "Pending") {foreach ($this->_transaction->get_items() as $item) {
                $product = local_buykart_get_product($item->get_product_id());
                $instance = $DB->get_record('enrol', array('courseid' => $product->get_course_id(), 'enrol' => 'buykart'));
                $this->_enrolPlugin->unenrol_user($instance, $this->_transaction->get_user_id());
            }
            $this->send_error_to_admin(get_string('status_pending','local_buykart'), $data);
            $this->_transaction->fail();
            return false;
        }
        // Confirm currency is correctly set and matches the plugin config
        /* if ($data->currency != get_config('local_buykart', 'currency')) {
            //echo 'currency is missmatch';
            $this->send_error_to_admin(get_string('currency_not','local_buykart') . $data->currency, $data);
            $this->_transaction->fail();
            return false;
        }*/
        // If status is pending and reason is other than echeck then we are on hold until further notice
        // Email user to let them know. Email admin.
        if ($data->payment_status == "Pending") {
            $eventdata = new stdClass();
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'local_buykart';
            $eventdata->name = 'local_buykart_payment';
            $eventdata->userfrom = get_admin();
            $eventdata->userto = $user;
            $eventdata->subject = get_string('payumoney','local_buykart');
            $eventdata->fullmessage = get_string('payumoneypending','local_buykart');
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml = '';
            $eventdata->smallmessage = '';
            $this->send_error_to_admin("Payment pending", $eventdata);
            $this->_transaction->pending();
            return false;
        }
        // --------------------
        // At this point we only proceed with 
        // - a status of completed, or 
        // - pending with a reason of echeck
        // --------------------
        
        // Check if the payment was less than the transaction cost
        if ($data->amount < $this->_transaction->get_cost()) {
            $this->send_error_to_admin("Amount paid is not enough (" . $data->amount . " < " . $this->_transaction->get_cost() . ")", $data);
            $this->_transaction->fail();
            return false;
        }
        if($data->payment_status =='success') {
            $this->send_error_to_admin("Transaction successfully done",$data);
        }
        // Lastly, verify the general transaction items and user
        if ($this->verify_transaction()) {
            $this->_transaction->set_txn_id($data->txnid);
            $this->complete_enrolment();
            return true;
        }
        return false;
    }
    public function render() {
        global $CFG,$USER,$COURSE,$DB;
        $merchantkey = get_config('local_buykart','payment_payumoney_key');
        $merchantsalt = get_config('local_buykart','payment_payumoney_salt');
        // Merchant key here as provided by Payu
        // Merchant Salt as provided by Pay
        $sandbox = get_config('local_buykart','payment_payumoney_sandbox');
        $liveurl = get_config('local_buykart','payment_payumoney_live_url');
        $testurl = get_config('local_buykart','payment_payumoney_test_url');
        $MERCHANT_KEY = $merchantkey;
        $SALT = $merchantsalt;
        $PAYU_BASE_URL = empty($sandbox) ? $liveurl : $testurl;
        $this->_gatewayURL = '';
        $posted = array();
        if(!empty($_POST)) {
            foreach($_POST as $key => $value) {    
                $posted[$key] = $value;
            }
        }
        if(empty($posted['txnid'])) {
        // Generate random transaction id
            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        } else {
            $txnid = $posted['txnid'];
        }
        $formError = 0;
        // putting all variables for payumoney
        $totalcost = 0.0;
        $productinfoall = array();
        foreach ($this->_transaction->get_items() as $item) {
            $totalcost += $item->get_cost();
            $product = local_buykart_get_product($item->get_product_id());
            $productinfoall[] = $product->get_course_id();
        }
        //payumoney currency code is added by prashant 
        $currency = get_config('local_buykart','payment_payumoney_currency');
        $posted['key'] = $merchantkey;
        $posted['amount'] = number_format((float)$totalcost,2,'.',''); 
        $posted['firstname'] = $USER->firstname;
        $posted['email'] = $USER->email;
        $posted['phone'] = $USER->phone1;
        $posted['phone'] = $USER->phone1;
        $posted['productinfo'] = implode('-', $productinfoall);
        $posted['surl'] = $CFG->wwwroot.'/local/buykart/payment/payumoney/ipn.php';
        $posted['furl'] = $CFG->wwwroot."/local/buykart/payment/payumoney/return.php?id=$COURSE->id";
        $posted['curl'] = $CFG->wwwroot;
        $posted['udf1'] = $this->_transaction->get_id();
        $posted['udf2'] = '';
        $posted['udf3'] = '';
        $posted['udf4'] = '';
        $posted['udf5'] = '';
        $posted['txnid'] = $txnid;
        $posted['service_provider'] = '';
        // Hash Sequence 
        $hash = '';
        // Hash Sequence
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        if(empty($posted['hash']) && sizeof($posted) > 0) {
            if(
                empty($posted['key'])
                || empty($posted['txnid'])
                || empty($posted['amount'])
                || empty($posted['firstname'])
                || empty($posted['email'])
                || empty($posted['phone'])
                || empty($posted['productinfo'])
                || empty($posted['surl'])
                || empty($posted['furl'])
                ) {
                $formError = 1;
        } else {
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';  
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $SALT;
            $hash = strtolower(hash('sha512', $hash_string));
            $this->_gatewayURL = $PAYU_BASE_URL . '/_payment';
        }
    } elseif(!empty($posted['hash'])) {
        $hash = $posted['hash'];
        $this->_gatewayURL = $PAYU_BASE_URL . '/_payment';
    }
    $req = 'cmd=_notify-validate';
    $data = (object) $_POST;
    foreach ($_POST as $key => $value) {
        $req .= "&$key=" . urlencode($value);
    }
    if (!$user = $DB->get_record("user", array("id" => $USER->id))) {
        send_error_to_admin("Not a valid user id", $data);
        die;
    }
    $html = '<script type="text/javascript">
    var hash = "'.$hash.'";
    function submitPayuForm() {
        if(!hash) {
        return;
    }
    var payuForm = document.forms.payuForm;
    payuForm.submit();
    }
    </script>';
    // output form
    $html .= sprintf('<body onsubmit="submitPayuForm()"><form action="%s" method="POST" class="payment-gateway gateway--payumoney" name="payuForm">', $this->_gatewayURL);
    $html .= sprintf(
        '<input type="hidden" name="key" value="%s">
        <input type="hidden" name="hash" value="%s">
        <input type="hidden" name="txnid" value="%s">
        <input type="hidden" name="amount" value="%s" placeholder="amount">
        <input type="hidden" name="firstname" id="firstname" value="%s" placeholder="firstname">
        <input type="hidden" name="email" id="email" value="%s" placeholder="email">
        <input type="hidden" name="phone" value="%s" placeholder="phone">
        <input type="hidden" name="productinfo" value="%s" placeholder="productinfo">
        <input type="hidden" name="surl" value="%s" placeholder="surl">
        <input type="hidden" name="furl" value="%s" placeholder="furl">
        <input type="hidden" name="udf1" value="%s" placeholder="udf1">
        <input type="hidden" name="udf2" value="%s" placeholder="udf2">
        <input type="hidden" name="udf3" value="%s" placeholder="udf3">
        <input type="hidden" name="udf4" value="%s" placeholder="udf4">
        <input type="hidden" name="udf5" value="%s" placeholder="udf5">
        <input type="hidden" name="service_provider" value="%s" size="64" />',
        $MERCHANT_KEY,
        $hash,
        $txnid,
        (empty($posted['amount'])) ? '' : $posted['amount'],
        (empty($posted['firstname'])) ? '' : $posted['firstname'],
        (empty($posted['email'])) ? '' : $posted['email'],
        (empty($posted['phone'])) ? '' : $posted['phone'], 
        (empty($posted['productinfo'])) ? '' : $posted['productinfo'], 
        (empty($posted['surl'])) ? '' : $posted['surl'],
        (empty($posted['furl'])) ? '' : $posted['furl'],
        (empty($posted['udf1'])) ? '' : $posted['udf1'],
        (empty($posted['udf2'])) ? '' : $posted['udf2'],
        (empty($posted['udf3'])) ? '' : $posted['udf3'],
        (empty($posted['udf4'])) ? '' : $posted['udf4'],
        (empty($posted['udf5'])) ? '' : $posted['udf5'],
        (empty($posted['service_provider'])) ? '' : $posted['service_provider']
        );
    $html .= sprintf(
        '<input type="submit" name="submit"  value="%s">', get_string('button_payumoney_label', 'local_buykart')
        );
    $html .= sprintf('</form></body></html>');
    return $html;
    }
}

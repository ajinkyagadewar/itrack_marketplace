<?php
/**
 *  buykart Gateway paytm
 *  @package     local
 *  @subpackage  local_buykart
 *  @author     prashant yallatti
 *  @copyright   lmsofindia.com
 *  @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *  */
// Load Moodle config
require_once dirname(__FILE__) . '/../../../config.php';
// Load buykart lib
require_once dirname(__FILE__) . '/../lib.php';
//instamojofile is added here
require_once($CFG->dirroot.'/local/buykart/payment/instamojo/Instamojo.php');
global $CFG;
// require_once($CFG->dirroot.'/local/buykart/payment/paytm/lib/encdec_paytm.php');
class BuykartGatewayInstamojo extends BuykartGateway {
    function __construct($transaction) {
        parent::__construct($transaction);
        $this->_gatewayName = get_string('payment_instamojo_title', 'local_buykart');
    }
    // handle the IPN notification
    public function handle($data = null) {
        global $DB, $CFG;
        require_once $CFG->libdir . '/eventslib.php';
        // Set the gateway to be Instamojo
        $this->_transaction->set_gateway(BUYKART_GATEWAY_INSTAMOJO);
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
        if ($data->payment_status != "Completed" and $data->payment_status != "Pending") {foreach ($this->_transaction->get_items() as $item) {
                $product = local_buykart_get_product($item->get_product_id());
                $instance = $DB->get_record('enrol', array('courseid' => $product->get_course_id(), 'enrol' => 'buykart'));
                $this->_enrolPlugin->unenrol_user($instance, $this->_transaction->get_user_id());
            }
            $this->send_error_to_admin(get_string('status_pending','local_buykart'), $data);
            $this->_transaction->fail();
            return false;
        }
        // Confirm currency is correctly set and matches the plugin config
        if ($data->currency != get_config('local_buykart', 'currency')) {
            //echo 'currency is missmatch';
            $this->send_error_to_admin(get_string('currency_not','local_buykart') . $data->currency, $data);
            $this->_transaction->fail();
            return false;
        }
        // If status is pending and reason is other than echeck then we are on hold until further notice
        // Email user to let them know. Email admin.
        if ($data->payment_status == "Pending" and $data->pending_reason != "Completed") {
            $eventdata = new stdClass();
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'local_buykart';
            $eventdata->name = 'local_buykart_payment';
            $eventdata->userfrom = get_admin();
            $eventdata->userto = $user;
            $eventdata->subject = get_string('instamojo_sub','local_buykart');
            $eventdata->fullmessage = get_string('instamojo_pen','local_buykart');
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml = '';
            $eventdata->smallmessage = '';
            $this->send_error_to_admin("Payment pending", $eventdata);
            $this->_transaction->pending();
            return false;
        }
        // Check if the payment was less than the transaction cost
        if ($data->amount < $this->_transaction->get_cost()) {
            //echo 'amount is not enough';
            $this->send_error_to_admin("Amount paid is not enough (" . $data->amount . " < " . $this->_transaction->get_cost() . ")", $data);
            $this->_transaction->fail();
            return false;
        }
       
       if($data->payment_status =='Completed') {
            $this->send_error_to_admin("Transaction successfully done",$data);
        }
        // Lastly, verify the general transaction items and user
        if ($this->verify_transaction()) {
            $this->_transaction->set_txn_id($data->txn_id);
            $this->complete_enrolment();
            return true;
        }
        return false;
    }
    public function render() {
        global $CFG, $DB,$USER,$COURSE;
        $apikey = get_config('local_buykart','payment_instamojo_apikey');
        $token = get_config('local_buykart','payment_instamojo_auth_token');
        $salt = get_config('local_buykart','payment_instamojo_salt');
        //all posted value collected here
        $totalcost = 0.0;
        $productinfoall = array();
        foreach ($this->_transaction->get_items() as $item) {
            $totalcost += $item->get_cost();
            $product = local_buykart_get_product($item->get_product_id());
            $productinfoall[] = $product->get_type() === PRODUCT_TYPE_SIMPLE ?
                $product->get_fullname() : $product->get_fullname() . ' - ' . $product->get_variation($item->get_variation_id())->get_name();
            }
        $amount = number_format((float)$totalcost,2,'.','');
        $orderid = $this->_transaction->get_id();
        $txnid= substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $iorderid = rand(10000,99999999);
        //new currency settings code is added by prashant
        $currency = get_config('local_buykart', 'payment_instamojo_currency');
        $purpose = get_config('local_buykart', 'payment_instamojo_purpose');
        $phone = $USER->phone1;
        $redirect_url = $CFG->wwwroot.'/local/buykart/payment/instamojo/ipn-instamojo.php';
        $post_url = $CFG->wwwroot.'/local/buykart/payment/instamojo/instamojo_post.php';
        $callback = $CFG->wwwroot."/local/buykart/payment/paytm/return.php?id=$COURSE->id";
        $courseid = $COURSE->id;
        $userid = $USER->id;
        // output form
        $html = sprintf('<form action="%s" method="POST" class="payment-gateway gateway-paypal >',$post_url);
        $html .= sprintf(
            '<input type="hidden" name="apikey" value="%s">
            <input type="hidden" name="token" value="%s">
            <input type="hidden" name="salt" value="%s">
            <input type="hidden" name="orderid" value="%s">
            <input type="hidden" name="purpose" value="%s">
            <input type="hidden" name="txnid" value="%s">
            <input type="hidden" name="iorderid" value="%s">
            <input type="hidden" name="amount" value="%s" placeholder="amount">
            <input type="hidden" name="buyer_name" id="buyer_name" value="%s" placeholder="firstname">
            <input type="hidden" name="email" id="email" value="%s" placeholder="email">
            <input type="hidden" name="phone" value="%s" placeholder="phone">
            <input type="hidden" name="redirect_url" value="%s" placeholder="surl">
            <input type="hidden" name="userid" value="%s" placeholder="courseid">
            <input type="hidden" name="courseid" value="%s" placeholder="courseid">
            <input type="hidden" name="currency" value="%s" placeholder="currency">',
            $apikey,
            $token,
            $salt,
            $orderid,
            $purpose,
            $txnid,
            $iorderid,
            $amount,
            $USER->firstname,
            $USER->email,
            $phone,
            $redirect_url,
            $USER->id,
            $COURSE->id,
            $currency
        );
        //instamojo payment code here
        $html .= sprintf(
            '<input type="submit" name="submit"  value="%s">', get_string('button_instamojo_label', 'local_buykart')
        );
        $html .= sprintf('</form>');
        return $html;
    }
}
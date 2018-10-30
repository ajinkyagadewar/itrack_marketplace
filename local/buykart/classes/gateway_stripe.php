<?php

/**
* buykart Gateway paytm
*
* @package     local
* @subpackage  local_buykart
* @author      prashant yallatti
* @copyright   lmsofindia.com
* @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
// Load Moodle config
require_once dirname(__FILE__) . '/../../../config.php';
// Load buykart lib
require_once dirname(__FILE__) . '/../lib.php';

global $CFG;
//require_once($CFG->dirroot.'/local/buykart/payment/paytm/lib/encdec_paytm.php');
class BuykartGatewayStripe extends BuykartGateway {
    function __construct($transaction) {
        parent::__construct($transaction);
        $this->_gatewayName = get_string('payment_stripe_title', 'local_buykart');
    }
    // handle the IPN notification  
    public function handle($data = null) {
        global $DB, $CFG;
        require_once $CFG->libdir . '/eventslib.php';
        $this->_transaction->set_gateway(BUYKART_GATEWAY_STRIPE);
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
        if ($data->payment_status != "succeeded") {
            foreach ($this->_transaction->get_items() as $item) {
                $product = local_buykart_get_product($item->get_product_id());
                $instance = $DB->get_record('enrol', array('courseid' => $product->get_course_id(), 'enrol' => 'buykart'));
                $this->_enrolPlugin->unenrol_user($instance, $this->_transaction->get_user_id());
            }
            $this->send_error_to_admin(get_string('status_pending','local_buykart'), $data);
            $this->_transaction->fail   ();
            return false;
        }
    // Confirm currency is correctly set and matches the plugin config
        if ($data->mc_currency != get_config('local_buykart', 'currency')) {
            $this->send_error_to_admin(get_string('currency_not','local_buykart') . $data->mc_currency, $data);
            $this->_transaction->fail();
            return false;
        }
    // If status is pending and reason is other than echeck then we are on hold until further notice
    // Email user to let them know. Email admin.
        if ($data->payment_status == "OPEN" OR $data->payment_status == "TXN_FAILURE") {
            $eventdata = new stdClass();
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'local_buykart';
            $eventdata->name = 'local_buykart_payment';
            $eventdata->userfrom = get_admin();
            $eventdata->userto = $user;
            $eventdata->subject = get_string('stripe','local_buykart');
            $eventdata->fullmessage = get_string('paytmpending','local_buykart');
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml = '';
            $eventdata->smallmessage = '';
            $this->send_error_to_admin("Payment pending", $data);
            $this->_transaction->pending();
            return false;
        }
    // Check if the payment was less than the transaction cost
        if ($data->amount < $this->_transaction->get_cost()) {
            $this->send_error_to_admin("Amount paid is not enough (" . $data->amount . " < " . $this->_transaction->get_cost() . ")", $data);
            $this->_transaction->fail();
            return false;
        }
        if($data->payment_status =='succeeded') {
           $this->send_error_to_admin("Transaction successfully done");
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
        $secretkey = get_config('local_buykart','payment_stripe_secret_key');
        $publishkey = get_config('local_buykart','payment_stripe_publishableKey');
        $enablekey = get_config('local_buykart','payment_stripe_enable');
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
        $currency = get_config('local_buykart', 'payment_stripe_currency');
        $phone = $USER->phone1;
    //$redirect_url = $CFG->wwwroot.'/local/buykart/payment/instamojo/ipn-instamojo.php';
        $post_url = $CFG->wwwroot.'/local/buykart/payment/stripe/payment.php';
        $courseid = $COURSE->id;
        $coursefname = $COURSE->fullname;
        $coursesname = $COURSE->shortname;
        $userid = $USER->id;
        $uerfullname = $USER->firstname.'-'.$USER->lastname;
        $uservalue = 'user';
        $value = '_xclick';
        $continuetocourse = 'continuetocourse'; 
        // output form
        if($enablekey){
            $html = sprintf('<form action="%s" method="POST" class="payment-gateway gateway-paypal >',$post_url);
            $html .=                 
            '<input type="hidden" name="cmd" value='.$value.' />
            <input type="hidden" name="charset" value="utf-8" />
            <input type="hidden" name="item_name" value='.$coursefname.' />
            <input type="hidden" name="item_number" value='.$coursesname.'/>
            <input type="hidden" name="quantity" value="1" />
            <input type="hidden" name="on0" value="'.$uservalue.'" />
            <input type="hidden" name="os0" value="'.$uerfullname.'" />
            <input type="hidden" name="custom" value='.$USER->id.' />
            <input type="hidden" name="currency_code" value='.$currency.' />
            <input type="hidden" name="amount" value='.$amount.' />
            <input type="hidden" name="for_auction" value="false" />
            <input type="hidden" name="no_note" value="1" />
            <input type="hidden" name="no_shipping" value="1" />
            <input type="hidden" name="rm" value="'.$orderid.'" />
            <input type="hidden" name="cbt" value="'.$continuetocourse.'" />
            <input type="hidden" name="first_name" value='.$USER->firstname.' />
            <input type="hidden" name="last_name" value='.$USER->lastname.' />
            <input type="hidden" name="address" value="'. $uservalue.'" />
            <input type="hidden" name="city" value="'. $uservalue.'" />
            <input type="hidden" name="email" value="'.$USER->email.'" />
            <input type="hidden" name="country" value="'.$USER->country.'" />
            <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key='.$publishkey.' id="stripe button"
            data-image=""
            data-name='.$coursesname.'
            data-description = Enrolment Cost'.$currency.'.'.$amount.'"
            data-metadata="6"
            data-label = "Pay with stripe card"
            data-currency='.$currency.'
            data-amount='.($amount*100).'
            ></script>';
            $html .= '</form>';
            return $html;
        }   
    }
}

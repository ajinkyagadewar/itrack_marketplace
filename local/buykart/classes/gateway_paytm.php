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
//encript code changes here by prashant new upgradation 
require_once($CFG->dirroot.'/local/buykart/payment/paytm/lib/encdec_paytm-mcrypt.php');
class BuykartGatewayPaytm extends BuykartGateway {
    function __construct($transaction) {
        parent::__construct($transaction);
        $this->_gatewayName = get_string('payment_paytm_title', 'local_buykart');
        // Checks if sandbox mode is enabled
        if (!!get_config('local_buykart', 'payment_paytm_environment')) {
            $this->_gatewayURL = "pguat.paytm.com";
        } else {
            $this->_gatewayURL = 'secure.paytm.in';
        }
    }
    // handle the IPN notification  
    public function handle($data = null) {
        global $DB, $CFG;
        require_once $CFG->libdir . '/eventslib.php';
        $this->_transaction->set_gateway(BUYKART_GATEWAY_PAYTM);
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
        if ($data->payment_status != "TXN_SUCCESS") {
            foreach ($this->_transaction->get_items() as $item) {
                $product = local_buykart_get_product($item->get_product_id());
                $instance = $DB->get_record('enrol', array('courseid' => $product->get_course_id(), 'enrol' => 'buykart'));
                $this->_enrolPlugin->unenrol_user($instance, $this->_transaction->get_user_id());
            }
            $this->send_error_to_admin(get_string('status_pending','local_buykart'), $data);
            $this->_transaction->fail();
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
            $eventdata->subject = get_string('paytm','local_buykart');
            $eventdata->fullmessage = get_string('paytmpending','local_buykart');
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml = '';
            $eventdata->smallmessage = '';
            $this->send_error_to_admin("Payment pending", $eventdata);
            $this->_transaction->pending();
            return false;
        }
        // Check if the payment was less than the transaction cost
        if ($data->amount < $this->_transaction->get_cost()) {
            $this->send_error_to_admin("Amount paid is not enough (" . $data->amount . " < " . $this->_transaction->get_cost() . ")", $data);
            $this->_transaction->fail();
            return false;
        }
        if($data->payment_status =='TXN_SUCCESS') {
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
            // output form
            $html = sprintf('<form action="%s" method="POST" class="payment-gateway gateway--paytm">', 'https://'.$this->_gatewayURL.'/oltp-web/processTransaction');
            $checkSum = "";
            // Create an array having all required parameters for creating checksum.
            $mid = get_config('local_buykart', 'payment_paytm_merchant_mid');
            $mkey = get_config('local_buykart', 'payment_paytm_merchant_key');
            $mwebsite = get_config('local_buykart', 'payment_paytm_merchant_website');
            $mcurrency = get_config('local_buykart', 'payment_paytm_currency');
            $mnotifyurl = $CFG->wwwroot.'/local/buykart/payment/paytm/ipn.php';
            $mcallback = $CFG->wwwroot."/local/buykart/payment/paytm/return.php?id=$COURSE->id";
            $mindtype = get_config('local_buykart', 'payment_paytm_industry_id');
            $mchannel = get_config('local_buykart', 'payment_paytm_channel_id');
            $mcustid = $USER->id;
            $museremail = $USER->email;
            $musermobile = $USER->phone1;
            $mrequesttype = get_config('local_buykart', 'payment_paytm_request_type');
            //for total cost of the cart
            $totalcost = 0.0;
            $productinfoall = array();
            foreach ($this->_transaction->get_items() as $item) {
                $totalcost += $item->get_cost();
                $product = local_buykart_get_product($item->get_product_id());
                $productinfoall[] = $product->get_course_id();
            }
            $mtotalcost = number_format((float)$totalcost,2,'.','');
            $morderid = rand(10000,99999999);
            $actualtransactionid = $this->_transaction->get_id();
            $formArray = array(
                "MID" => $mid,
                "MERC_UNQ_REF" => $actualtransactionid,
                "ORDER_ID" => $morderid,
                "CUST_ID" => $mcustid,
                "WEBSITE" => $mwebsite,
                "INDUSTRY_TYPE_ID" => $mindtype,
                "EMAIL" => $museremail,
                "CHANNEL_ID" => $mchannel,
                "MOBILE_NO" => $musermobile,
                "REQUEST_TYPE" => $mrequesttype,
                "TXN_AMOUNT" => $mtotalcost,
                "CALLBACK_URL" => $mnotifyurl
                );
            $checksum = getChecksumFromArray($formArray,$mkey);
            $mchecksumhash=$checksum;
            $html .= sprintf(
                '<input type="hidden" name="MID" value="%s">
                <input type="hidden" name="MERC_UNQ_REF" value="%s">
                <input type="hidden" name="ORDER_ID" value="%s">
                <input type="hidden" name="CUST_ID" value="%s">
                <input type="hidden" name="WEBSITE" value="%s">
                <input type="hidden" name="INDUSTRY_TYPE_ID" value="%s">
                <input type="hidden" name="EMAIL" value="%s">
                <input type="hidden" name="CHANNEL_ID" value="%s">
                <input type="hidden" name="MOBILE_NO" value="%s">
                <input type="hidden" name="REQUEST_TYPE" value="%s">
                <input type="hidden" name="TXN_AMOUNT" value="%s">
                <input type="hidden" name="CALLBACK_URL" value="%s">
                <input type="hidden" name="CHECKSUMHASH" value="%s">',
                $mid,
                $actualtransactionid,
                $morderid,
                $mcustid,
                $mwebsite,
                $mindtype,
                $museremail,
                $mchannel,
                $musermobile,
                $mrequesttype,
                $mtotalcost,
                $mnotifyurl,
                $mchecksumhash
            );
             $html .= sprintf(
                '<input type="submit" name="submit"  value="%s">', get_string('button_paytm_label', 'local_buykart')
                );
             $html .= sprintf('</form>');
             return $html;
         }
     }

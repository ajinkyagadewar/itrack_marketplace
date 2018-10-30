<?php
/**
 * uykart Gateway Paypal
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	Thomas Threadgold
 * @copyright   2015 LearniBngWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Load Moodle config
require_once dirname(__FILE__) . '/../../../config.php';
// Load buykart lib
require_once dirname(__FILE__) . '/../lib.php';
class BuykartGatewayPaypal extends BuykartGateway {
    function __construct($transaction) {
        parent::__construct($transaction);
        $this->_gatewayName = get_string('payment_paypal_title', 'local_buykart');
        // Checks if sandbox mode is enabled
        if (!!get_config('local_buykart', 'payment_paypal_sandbox')) {
            $this->_gatewayURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // the Paypal sandbox URL
        } else {
            $this->_gatewayURL = 'https://www.paypal.com/cgi-bin/webscr';
        }
    }
    // handle the IPN notification	
    public function handle($data = null) {
        global $DB, $CFG;
        require_once $CFG->libdir . '/eventslib.php';
        $this->_transaction->set_gateway(BUYKART_GATEWAY_PAYPAL);
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
        if ($data->payment_status != "Completed" and $data->payment_status != "Pending") {
            echo 'payment is not pending and not complete ';
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
        if ($data->payment_status == "Pending" and $data->pending_reason != "echeck") {
            $eventdata = new stdClass();
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'local_buykart';
            $eventdata->name = 'local_buykart_payment';
            $eventdata->userfrom = get_admin();
            $eventdata->userto = $user;
            $eventdata->subject = get_string('paypal','local_buykart');
            $eventdata->fullmessage = get_string('paypalpending','local_buykart');
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
        // The email address paid to does not match the one we expect
        if (core_text::strtolower($data->business) !== core_text::strtolower(get_config('local_buykart', 'payment_paypal_email'))) {
            // Check that the email is the one we want it to be
            $this->send_error_to_admin("Paypal business email is {$data->business} (not " .
                    get_config('local_buykart', 'payment_paypal_email') . ")", $data);
            $this->_transaction->fail();
            return false;
        }
        // Check if the payment was less than the transaction cost
        if ($data->mc_gross < $this->_transaction->get_cost()) {
            $this->send_error_to_admin("Amount paid is not enough (" . $data->mc_gross . " < " . $this->_transaction->get_cost() . ")", $data);
            $this->_transaction->fail();
            return false;
        }
        if($data->payment_status =='completed') {
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
        global $CFG;
		$paypalcurrency = 'USD'; // paypal will always have USD currency Mihir 14dec
        // output form
        $html = sprintf('<form action="%s" method="POST" class="payment-gateway gateway--paypal">', $this->_gatewayURL);
        $html .= sprintf(
                '<input type="hidden" name="cmd" value="_cart">
				<input type="hidden" name="charset" value="utf-8">
				<input type="hidden" name="upload" value="1">
				<input type="hidden" name="for_auction" value="false">
				<input type="hidden" name="no_note" value="1">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="business" value="%s">
				<input type="hidden" name="currency_code" value="%s">
				<input type="hidden" name="custom" value="%d">
				<input type="hidden" name="notify_url" value="%s">
				<input type="hidden" name="return" value="%s">
				<input type="hidden" name="cancel_return" value="%s">',
                get_config('local_buykart', 'payment_paypal_email'),
                $paypalcurrency, $this->_transaction->get_id(), new moodle_url($CFG->wwwroot . '/local/buykart/payment/paypal/ipn.php'), new moodle_url($CFG->wwwroot . '/my'), // PERHAPS MAKE THIS CONFIGURABLE?
                new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php')
        );
        // Count is used to incrementally name the item fields
        $count = 1;
        foreach ($this->_transaction->get_items() as $item) {
            $product = local_buykart_get_product($item->get_product_id());
            // Output name
            $html .= sprintf(
                    '<input type="hidden" name="%s" value="%s">', 'item_name_' . $count, $product->get_type() === PRODUCT_TYPE_SIMPLE ? $product->get_fullname() : $product->get_fullname() . ' - ' . $product->get_variation($item->get_variation_id())->get_name()
            );
            // Output name
            $html .= sprintf(
                    '<input type="hidden" name="%s" value="%s">', 'amount_' . $count, $item->get_cost()
            );
            $count++;
        }
        $html .= sprintf(
                '<input type="submit" name="submit"  class="btn btn-success" value="%s">', get_string('button_paypal_label', 'local_buykart')
        );
        $html .= sprintf('</form>');
        return $html;
    }
}
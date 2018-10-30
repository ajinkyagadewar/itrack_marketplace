<?php
/**
 * Buykart Gateway Ebs
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

class BuykartGatewayEbs extends BuykartGateway {

    function __construct($transaction) {
        parent::__construct($transaction);

        $this->_gatewayName = get_string('payment_ebs_title', 'local_buykart');

        //  Checks if sandbox mode is enabled
        if (!!get_config('local_buykart', 'payment_ebs_sandbox')) {

            $this->_gatewayURL = "https://secure.ebs.in/pg/ma/sale/vpc";

        } else {
           $this->_gatewayURL = "https://secure.ebs.in/pg/ma/sale/vpc";
       }
   }

   function ebs_config() {

    $configarray = array(
       "FriendlyName" => array("Type" => "System", "Value"=>"EBS"),
       "accountid" => array("FriendlyName" => "Account ID", "Type" => "text", "Size" => "20", ),
       "secretkey" => array("FriendlyName" => "SECRET KEY", "Type" => "text", "Size" => "20",),
       "mode" => array("FriendlyName" => "MODE", "Type" => "text", "Description" => "TEST or LIVE", ),

       );
    return $configarray;
}


    // handle the IPN notification  
public function handle($data = null) {
    global $DB, $CFG;
    require_once $CFG->libdir . '/eventslib.php';

        // Set the gateway to be Ebs
    $this->_transaction->set_gateway(BUYKART_GATEWAY_EBS);

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

        foreach ($this->_transaction->get_items() as $item) {

            $product = local_buykart_get_product($item->get_product_id());

            $instance = $DB->get_record('enrol', array('courseid' => $product->get_course_id(), 'enrol' => 'buykart'));

            $this->_enrolPlugin->unenrol_user($instance, $this->_transaction->get_user_id());
        }

        $this->send_error_to_admin("Status not completed or pending. User unenrolled from course", $data);

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
        $eventdata->subject = "Moodle: Ebs payment";
        $eventdata->fullmessage = "Your Ebs payment is pending.";
        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml = '';
        $eventdata->smallmessage = '';

        $this->send_error_to_admin("Payment pending", $eventdata);

        $this->_transaction->pending();

        return false;
    }

        // Check if the payment was less than the transaction cost
    if ($data->mc_gross < $this->_transaction->get_cost()) {

        $this->send_error_to_admin("Amount paid is not enough (" . $data->mc_gross . " < " . $this->_transaction->get_cost() . ")", $data);

        $this->_transaction->fail();

        return false;
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
    global $CFG,$USER,$COURSE,$DB;
    $accoutnid = $USER->id;
    $secretkey = get_config('local_buykart','payment_ebs_SecretKey');
    $sandbox = get_config('local_buykart','payment_ebs_sandbox');;
        # Gateway Specific Variables
    $gatewayaccountid = get_config('local_buykart','payment_ebs_AccountID');
    $gatewaymode = 'TEST';
        // putting all variables for payumoney
    $totalcost = 0.0;
    $productinfoall = array();
    foreach ($this->_transaction->get_items() as $item) {
        $totalcost += $item->get_cost();
        $product = local_buykart_get_product($item->get_product_id());
        $productinfoall[] = $product->get_type() === PRODUCT_TYPE_SIMPLE ? $product->get_fullname() : $product->get_fullname() . ' - ' . $product->get_variation($item->get_variation_id())->get_name();
    }
    $currency = get_config('local_buykart','currency');
    $posted['amount'] = $totalcost;
    $posted['firstname'] = $USER->firstname;
    $posted['lastname'] = $USER->firstname;
    $posted['email'] = $USER->email;
    $posted['phonenumber'] = $USER->phone1;
    $posted['address1'] = $USER->address;
    $posted['address2'] = $USER->city;
    $posted['city'] = $USER->city;
    $posted['postcode'] = '';
    $posted['country'] = $USER->country;
    $posted['description'] = implode(',', $productinfoall);
    $posted['state'] = '';

        # Invoice Variables
    $invoiceid = '';
    $description = $posted["description"];
    $amount = $posted['amount']; 
    $firstname = $posted['firstname'];
    $lastname = $posted['lastname'];
    $email = $posted['email'];
    $address1 = $posted['address1'];
    $address2 = $posted['address2'];
    $city = $posted['city'];
    $state = $posted['state'];
    $postcode = $posted['postcode'];
    $country = $posted['country'];
    $phone = $posted['phonenumber'];
    $posted['systemurl'] = $CFG->wwwroot;
    # System Variables
    $companyname = 'EBS';
    $systemurl = $posted['systemurl'];


    $html = sprintf('<form method="post" action="%s" name="frmTransaction" id="frmTransaction" onSubmit="return validate()">', $this->_gatewayURL);

    $html .= sprintf('<input type="hidden" name="account_id" value="%s" />
        <input type="hidden" name="mode" value="%s"/>
        <input type="hidden" name="description" value="%s" />
        <input type="hidden" name="reference_no" value="%s" />
        <input type="hidden" name="name" value="%s" />
        <input type="hidden" name="address" value="%s" />
        <input type="hidden" name="city" value="%s" />
        <input type="hidden" name="state" value="%s" />
        <input type="hidden" name="country" value="%s" />
        <input type="hidden" name="postal_code" value="%s" />
        <input type="hidden" name="ship_name" value="%s" />

        <input type="hidden" name="ship_address" value="%s" />
        <input type="hidden" name="ship_city" value="%s" />
        <input type="hidden" name="ship_state" value="%s" />
        <input type="hidden" name="ship_country" value="%s" />
        <input type="hidden" name="ship_postal_code" value="%s" />
        <input type="hidden" name="ship_phone" value="%s" />
        <input type="hidden" name="email" value="%s" />
        <input type="hidden" name="phone" value="%s" />
        <input type="hidden" name="amount" value="%s" />
        <input type="hidden" name="return_url" value="%s" />',

        $gatewayaccountid,$gatewaymode,$description,$invoiceid,
        $firstname,$address1,$city, $state,$country,$postcode,
        $firstname,$address1,$city,$state,$country,$postcode,$phone,
        $email,$phone,$amount,"$CFG->wwwroot.'/local/buykart/payment/ebs/response.php?DR={DR}");    
    $html .= sprintf(
        '<input type="submit" name="submit"  value="%s">', get_string('button_ebs_label', 'local_buykart')
        ); 
    $html .= sprintf('</form>');
    return $html;
    }
}

<?php

require_once('../../config.php');
define(AJAX_SCRIPT, true);
header('Content-Type:application/json');
$callback = trim($_REQUEST['action']);

$params = array();
unset($_REQUEST['action']);

foreach ($_REQUEST as $field => $value) {
    $params[$field] = $value;
}

echo json_encode(call_user_func_array($callback, $params));

/**
 * Functions to perform requestss
 */
function ediscout_exists() {
    $response = array('status' => false, 'message' => get_string('ediscoutnotexists', 'block_edupayments'));
    if (true) {
        unset($response);
        $response['status'] = true;
    }
    return $response;
}
/**
 * this function recive ajax calls and perform their operation.
 * @global type $DB
 * @param type $courseid
 * @param type $itemname
 * @param type $couponcode
 * @return \Exception
 * 
 */
function check_promotions($courseid, $itemname, $couponcode) {
    global $DB;
    /**
     * Here we are mapping front itemname to database field name
     */
    $paymenttypes = array(
        'verifiedcerti' => 'vcostforattendance',
        'attendance_of_completion' => 'costforattendance',
        'firstattend' => 'cost',
        'examination' => 'costforformalcredit',
    );
    $fieldname = $paymenttypes[$itemname];

    $amount = $DB->get_field_sql("SELECT $fieldname from {course_extrasettings_general} where courseid = $courseid");

    $response = array('status' => false, 'message' => get_string('ediscoutnotexists', 'block_edupayments'));

    try {
        if ($instance = $DB->get_record('enrol', array('courseid' => $courseid, 'name' => $itemname, 'enrol' => 'edupay'))) {

            if ($discount = $DB->get_record('ediscount', array('enrolid' => $instance->id, 'courseid' => $courseid, 'ppcode' => $couponcode))) {
               if($discount->status){
                    if ($discount->ppenddate > time()) {
                        if( $discount->ppaplied < $discount->ppreason ) {
                            $discountcost = $amount - round(($discount->ppcent / 100) * $amount);
                            if ($discountcost == 0) {
                                $response['message'] = get_string('promotionfull', 'block_edupayments');
                                $response['freemessage'] = get_string('freepromotion', 'block_edupayments');
                            } else {
                                $response['message'] = get_string('promotionsvalid', 'block_edupayments');
                            }
                            $response['discountamount'] = $discountcost;
                            $response['actualamount'] = $amount;
                            $response['status'] = true;
                            $response['ppcent'] = $discount->ppcent; 
                        } else {
                            $response['message'] = get_string('promotionssoldout', 'block_edupayments');
                        }
                    } else {
                         $response['message'] = get_string('promotionsexperid', 'block_edupayments');
                    }
                } else {
                    $response['message'] = get_string('promotioninactive', 'block_edupayments');
                }
            } else {
                $response['message'] = get_string('promotionsinvalid', 'block_edupayments');
            }
        }
    } catch (Exception $e) {
        return $e;
    }
    return $response;
}
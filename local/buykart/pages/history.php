<?php
/**
 *Buykart Cart Report Page
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	Arjun Singh
 * @copyright   2017 Dhruv Infoline Pvt Ltd
 * @license     http://www.lmsofindia.com 2017 or later
 */

require_once dirname(__FILE__) . '/../../../config.php';
require_once $CFG->dirroot . '/local/buykart/lib.php';
require_once("$CFG->libdir/pdflib.php");
require_login();
$transid = optional_param('transid', null, PARAM_RAW);
$action = optional_param('action', null, PARAM_TEXT);
$systemcontext = context_system::instance();

$PAGE->set_context($systemcontext);
$PAGE->set_url($CFG->wwwroot . '/local/buykart/pages/history.php');
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/buykart/bootstrap-select.min.css'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/buykart/js/bootstrap.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/buykart/js/bootstrap-select.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/buykart/js/jquery.min.js'), true);

// For datatable.
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/buykart/js/jquery.dataTables.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/buykart/js/dataTables.bootstrap.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/buykart/js/dataTables.fixedColumns.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/buykart/js/dataTables.tableTools.js'), true);
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/buykart/css/dataTables.bootstrap.min.css'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/buykart/css/fixedColumns.bootstrap.min.css'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/buykart/css/dataTables.tableTools.css'));

// Check if the theme has a buykart pagelayout defined, otherwise use standard
/*if (array_key_exists('buykart_cart', $PAGE->theme->layouts)) {
	$PAGE->set_pagelayout('buykart_cart');
} else if(array_key_exists('buykart', $PAGE->theme->layouts)) {
	$PAGE->set_pagelayout('coursecategory');
} else {
	$PAGE->set_pagelayout('coursecategory');
}*/
$PAGE->set_pagelayout('custom');
$PAGE->set_title(get_string('history_title', 'local_buykart'));
$PAGE->set_heading(get_string('history_title', 'local_buykart'));
$renderer = $PAGE->get_renderer('local_buykart');

$transactions = $DB->get_records('local_buykart_transaction',array('user_id' => $USER->id));
$i = 1;
$flag = 0; 
if(!isset($transid)){
    echo $OUTPUT->header();
	$table = new html_table();
	$table->head = (array) get_strings(array('slno','orderno','orderdate', 'orderamount', 'status','paymentmethod','invoice'),'local_buykart');
	
	//$transactions = $DB->get_records('local_buykart_transaction',array('txn_id' => $transid));
	foreach ($transactions as $tkey => $tvalue) {
		if ($tvalue->txn_id != NULL || $tvalue->txn_id != '') {
			$total_cost = 0;
			$transaction_cost = $DB->get_records('local_buykart_trans_item',array('transaction_id'=>$tvalue->id));
			foreach ($transaction_cost as $cost) {
				$total_cost  = $cost->item_cost + $total_cost;
			}
			$link = new moodle_url('/local/buykart/pages/history.php?transid='.$tvalue->txn_id.'&action=get');
			$button = new single_button($link, '&#9745;');
			$button->add_action(new popup_action('click', $link, 'view' . $tvalue->txn_id, array('height' => 600, 'width' => 800)));
			$inv = html_writer::tag('div', $OUTPUT->render($button), array('class' => 'invoicelink'));

			$currency = get_transaction_currency($tvalue->id);
			
			if ($tvalue->status == 2) {
				$tvalue->status = 'Completed';
			} else if ($tvalue->status == 1) {
				$tvalue->status = 'Pending';
			}else if ($tvalue->status == 0) {
				$tvalue->status = 'Not Submitted';
			}else if ($tvalue->status == 3) {
				$tvalue->status = 'Failed';
			}
			
			if ($tvalue->gateway == 'BUYKART_GATEWAY_PAYPAL') {
				$tvalue->type = 'PAYPAL';
			} else if ($tvalue->gateway == 'BUYKART_GATEWAY_PAYUMONEY') {
				$tvalue->type = 'PAYUMONEY';
			}else if ($tvalue->gateway == 'BUYKART_GATEWAY_INSTAMOJO') {
				$tvalue->type = 'INSTAMOJO';
			}else if ($tvalue->gateway == 'BUYKART_GATEWAY_PAYTM') {
				$tvalue->type = 'PAYTM';
			}//stripe code is added by prashant
			else if ($tvalue->gateway == 'BUYKART_GATEWAY_STRIPE') {
				$tvalue->type = 'STRIPE';
			}
			
			$table->data[] = array(
						$i,
						//'<a class="translink" href="' . $_SERVER['REQUEST_URI'] . '?transid='.$tvalue->txn_id.'">'.$tvalue->txn_id.'</a>',
						'<a class="translink" href="' . $_SERVER['REQUEST_URI'] . '?transid='.$tvalue->txn_id.'">'.$i.'</a>',
						userdate($tvalue->purchase_date, '%d-%m-%Y'),
						$currency.' '.$total_cost,
						$tvalue->status,
						$tvalue->type,
						$inv
			);
			$i++;
		}
	}
	echo html_writer::table($table);
}else {
        $transaction_id = $DB->get_record('local_buykart_transaction',array('txn_id'=>$transid));

        if($transaction_id->status != 2){
            $status = '<li class="list-group-item list-group-item-success">'.get_string('pending','local_buykart').'</li>';
        }else{
            $status = '<li class="list-group-item list-group-item-danger">'.get_string('success','local_buykart').'</li>';
        }
    	if (empty($action)) {
            echo $OUTPUT->header();
            echo html_writer::start_div();
            echo html_writer::tag('a', '<span class="arrow-left">&#8678</span>'.get_string('goback','local_buykart'),
                             array('class'=>'goback','href' => new moodle_url($CFG->wwwroot.'/local/buykart/pages/history.php')));
            echo html_writer::end_div();
            $userpicture =  "<span class='avatar'>
                                <img src='".$CFG->wwwroot."/user/pix.php?file=/".$USER->id."/f1.jpg' class='uimgcircle' class='avatar avatar-90 photo avatar-default' height='40' width='40'/>
                            </span>";
            $username = $USER->firstname.' '.$USER->lastname;               
            $table2 = new html_table();
            $table2->head = (array) array('','');
            $table2->data[] = array(
                '<ul class="list-group" style="max-width: 58%;min-width:auto;">
                      <li class="list-group-item active">'.$userpicture.$username.'</li>
                      <li class="list-group-item">'.get_string('orderno','local_buykart').$transid.'</li>
                 </ul>',
                 '<ul class="list-group"></ul>'                   
                );  
            echo html_writer::table($table2);

            echo get_transaction_items($transaction_id->id,$status); 
            $str = get_string('openeinvoice', 'local_buykart');
            echo html_writer::tag('p', $str, array('style' => 'text-align:center'));
            $linkname = get_string('getinvoice', 'local_buykart');

            $link = new moodle_url('/local/buykart/pages/history.php?transid='.$transid.'&action=get');
            $button = new single_button($link, $linkname);
            $button->add_action(new popup_action('click', $link, 'view' . $transid, array('height' => 600, 'width' => 800)));
            echo html_writer::tag('div', $OUTPUT->render($button), array('style' => 'text-align:center'));
           
            echo $OUTPUT->footer();
            exit;
        }else{
            get_invoice_pdf($transaction_id->id,$transid);
        }
}
echo $OUTPUT->footer();
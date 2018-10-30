<?php
/**
 * Buykart Library file
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	Thomas Threadgold
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Declare product type constants
define('PRODUCT_TYPE_SIMPLE', 'PRODUCT_TYPE_SIMPLE');
define('PRODUCT_TYPE_VARIABLE', 'PRODUCT_TYPE_VARIABLE');

// Declare gateway type constants
define('BUYKART_GATEWAY_PAYPAL', 'BUYKART_GATEWAY_PAYPAL');
define('BUYKART_GATEWAY_PAYUMONEY', 'BUYKART_GATEWAY_PAYUMONEY');
define('BUYKART_GATEWAY_EBS', 'BUYKART_GATEWAY_EBS');
define('BUYKART_GATEWAY_PAYTM', 'BUYKART_GATEWAY_PAYTM');
define('BUYKART_GATEWAY_INSTAMOJO', 'BUYKART_GATEWAY_INSTAMOJO');
define('BUYKART_GATEWAY_STRIPE', 'BUYKART_GATEWAY_STRIPE');



// Load the required buykart classes
require_once $CFG->dirroot . '/local/buykart/classes/product.php';
require_once $CFG->dirroot . '/local/buykart/classes/product_simple.php';
require_once $CFG->dirroot . '/local/buykart/classes/product_variable.php';
require_once $CFG->dirroot . '/local/buykart/classes/product_variation.php';
require_once $CFG->dirroot . '/local/buykart/classes/cart.php';
require_once $CFG->dirroot . '/local/buykart/classes/transaction.php';
require_once $CFG->dirroot . '/local/buykart/classes/transaction_item.php';
require_once $CFG->dirroot . '/local/buykart/classes/gateway.php';
require_once $CFG->dirroot . '/local/buykart/classes/gateway_paypal.php';
require_once $CFG->dirroot . '/local/buykart/classes/gateway_payumoney.php';
require_once $CFG->dirroot . '/local/buykart/classes/gateway_ebs.php';
require_once $CFG->dirroot . '/local/buykart/classes/gateway_paytm.php';
require_once $CFG->dirroot . '/local/buykart/classes/gateway_instamojo.php';
require_once $CFG->dirroot . '/local/buykart/classes/gateway_stripe.php';

/**
 * Extend the default Moodle navigation
 * @param  global_navigation $nav
 * @return void                 
 */
function local_buykart_extend_navigation(global_navigation $nav) {
	global $CFG, $PAGE, $DB;
	/* For moodle 3.2 and lower
	// Add buykart menus to navigation by Arjun Singh
		$node = $PAGE->navigation->add(get_string('pluginname', 'local_buykart'));

		   $storenode = $node->add(
			get_string('catalogue_title', 'local_buykart'),
			new moodle_url($CFG->wwwroot . '/local/buykart/pages/catalogue.php'),
			navigation_node::TYPE_CONTAINER
		);

		if (!!get_config('local_buykart', 'page_product_enable')) {

			// We store the courses by category
			// but only get categories with active products
			$query = sprintf(
				'SELECT DISTINCT 
						cc.id, 
						cc.visible,
						cc.name
				FROM	{course_categories} cc, 
						{course} c, 
						{local_buykart_product} lmp
				WHERE 	cc.id = c.category
				AND 	c.id = lmp.course_id
				AND 	lmp.is_enabled = 1');

			$categories = $DB->get_records_sql($query);

			if (!!$categories) {
				foreach ($categories as $category) {
					if($category->visible) {

						$catnode = $storenode->add(
							$category->name,
							new moodle_url($CFG->wwwroot . '/local/buykart/pages/catalogue.php', array('category' => $category->id)),
							navigation_node::TYPE_CONTAINER
						);

						// Actually get the products
						$products = local_buykart_get_products(-1, $category->id, 'fullname');

						// Add products to the store menu
						foreach ($products as $product) {
							$catnode->add(
								$product->get_fullname(),
								new moodle_url($CFG->wwwroot . '/local/buykart/pages/product.php', array('id' => $product->get_id()))
							);
						}
					}
				}
			}
		}
		$node->add(
			get_string('cart_title', 'local_buykart'),
			new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php')
		);
		$node->add(
			get_string('shopping_report_title', 'local_buykart'),
			new moodle_url($CFG->wwwroot . '/local/buykart/pages/history.php')
		);
	*/
		$coursename = get_string('pluginname','local_buykart');
		$url = '#';
		//$flat = new flat_navigation_node(navigation_node::create($coursename, $url), 0);
        //$flat->key = 'buykart';
		//$nav->add_node($flat);
		//$flat->showinflatnavigation = true;
		
		$abc = $nav->add(get_string('catalogue_title','local_buykart'), $CFG->wwwroot.'/local/buykart/pages/catalogue.php'); 
		$xyz = $nav->add(get_string('cart_title','local_buykart'), $CFG->wwwroot.'/local/buykart/pages/cart.php');
		$pqr = $nav->add(get_string('history_title','local_buykart'), $CFG->wwwroot.'/local/buykart/pages/history.php'); 
		$abc->showinflatnavigation = true;
		$xyz->showinflatnavigation = true;
		$pqr->showinflatnavigation = true;
}

/**
 * Display the buykart settings in the course settings block
 * For 2.3 and onwards
 *
 * @param  settings_navigation $nav     The settings navigation object
 * @param  stdclass            $context Course context
 */
function local_buykart_extend_settings_navigation(settings_navigation $nav, $context) {
	global $CFG;

	if ($context->contextlevel >= CONTEXT_COURSE and ($branch = $nav->get('courseadmin'))
		and has_capability('moodle/course:update', $context)) {
		$url = new moodle_url($CFG->wwwroot . '/local/buykart/settings/product.php', array('id' => $context->instanceid));
		$branch->add(get_string('buykart_product_settings', 'local_buykart'), $url, $nav::TYPE_CONTAINER, null, 'buykart' . $context->instanceid, new pix_icon('i/settings', ''));
	}
}

function local_buykart_get_currencies() {
	
	$codes = array('AUD','CAD','CHF','DKK','EUR','GBP','HKD','JPY','MYR','NZD','SGD','THB','USD','INR');
	$currencies = array();
	foreach ($codes as $c) {
		$currencies[$c] = new lang_string($c, 'local_buykart');
	}

	return $currencies;
}

/**
 * Returns the symbol for the supplied currency
 * @param  string $currency the currency code
 * @return string           the symbol
 */
function local_buykart_get_currency_symbol($currency) {

	$codes = array(
		'AUD' => ' $ ',
		'CAD' => ' $ ',
		'CHF' => ' CHF ',
		'DKK' => ' kr ',
		'EUR' => ' € ',
		'GBP' => ' £ ',
		'HKD' => ' $ ',
		'JPY' => ' ¥ ',
		'MYR' => ' RM ',
		'NZD' => ' $ ',
		'SGD' => ' $ ',
		'THB' => ' ฿ ',
		'USD' => ' $ ',
        'INR' => ' &#x20B9; ',
	);

	if (array_key_exists($currency, $codes)) {
		return $codes[$currency];
	}

	return '$';
}

/**
 * Returns an product object
 * @param  int 				$id 	the course id
 * @return buykartProduct     		Product, exception thrown if no product found
 */
function local_buykart_get_product($id) {
	global $DB;

	// build the query
	$query = sprintf(
		'SELECT type
		FROM {local_buykart_product}
		WHERE id = %d',
		(int) $id
	);

	// run the query
	$product = $DB->get_record_sql($query);

	// Return the product
	if (!!$product) {
		if( $product->type === PRODUCT_TYPE_SIMPLE) {
			return new BuykartProductSimple((int) $id);
		} else if( $product->type === PRODUCT_TYPE_VARIABLE) {
			return new BuykartProductVariable((int) $id);
		}
	}

	// Otherwise
	throw new Exception('Unable to find product using identifier: ' . $id);
}


/**
 * Returns an array of the products
 * @param  int 		$page 		The pagination 'page' to return. -1 will return all products
 * @param  int 		$category  	The category id to filter
 * @param  string 	$sortfield 	The field to sort the data by
 * @param  string 	$sortorder 	Sort by ASC or DESC
 * @return array            	The products
 */
function local_buykart_get_products($page = 1, $category = null, $sortfield = 'sortorder', $sortorder = 'ASC') {
	global $DB;

	// An array to store the products (this will be returned)
	$products = array();

	// Get the number of products to be shown per page from the plugin config
	$productsPerPage = get_config('local_buykart', 'pagination');

	// VALIDATE PARAMETERS
	if (!in_array($sortfield, array('sortorder', 'price', 'fullname', 'duration', 'timecreated'))) {
		$sortfield = 'sortorder';
	}

	// Sorting can only be done by 2 ways
	if (!in_array($sortorder, array('ASC', 'DESC'))) {
		$sortorder = 'ASC';
	}

	// If default, we won't filter by category
	if ($category == 'default') {
		$category = null;
	}

	// Ensure page is an int
	if( !is_int($page) ) {
		$page = (int) $page;
	}

	// Check if we should be returning all products or just a page of products
	$returnAll = false;
	if( $page === -1 ) {
		$returnAll = true;
	}

	// Reduce page by 1 so we can get the first 10 products
	// Because 0-based array stuff
	$page = $page < 1 ? 0 : $page - 1;

	// BUILD THE QUERY
	$query = sprintf(
		'SELECT DISTINCT lmp.id as productid
		FROM 	{local_buykart_product} lmp, 
				{local_buykart_variation} lmv, 
				{course} c
		WHERE 	lmp.id = lmv.product_id
		AND 	lmp.course_id = c.id
		AND		lmp.is_enabled = 1
		%s
	 	ORDER BY %s %s',
	 	$category !== null ? 'AND c.category = ' . $category : '',
	 	$sortfield,
	 	$sortorder
	);
	
	// RUN THE QUERY	
	if( $returnAll ) {
		$records = $DB->get_records_sql($query);
	} else {
		$records = $DB->get_records_sql($query, null, $productsPerPage * $page, $productsPerPage);
	}

	if( !!$records ) {

		foreach ($records as $record) {
	
			// Add the product matching this id to the array
			$products[] = local_buykart_get_product($record->productid);

		}
	}

	return $products;
}

/**
 * Returns an array of the products
 * @param  int 		$limit 		The number of random products to return
 * @param  int 		$category  	The category id to filter by
 * @return array            	The products
 */
function local_buykart_get_random_products($limit = 1, $category = null, $exclude = 0) {
	global $DB;

	// An array to store the products (this will be returned)
	$products = array();

	// VALIDATE PARAMETERS
	// If default, we won't filter by category
	if ($category == 'default') {
		$category = null;
	}

	// Ensure page is an int
	if( !is_int($limit) ) {
		$limit = (int) $limit;
	}

	// BUILD THE QUERY
	$query = sprintf(
		'SELECT DISTINCT lmp.id as productid
		FROM 	{local_buykart_product} lmp, 
				{local_buykart_variation} lmv, 
				{course} c
		WHERE 	lmp.id = lmv.product_id
		AND 	lmp.course_id = c.id
		AND		lmp.is_enabled = 1
		AND 	lmp.id != %d
		%s
	 	ORDER BY rand()',
	 	$exclude,
	 	$category !== null ? 'AND c.category = ' . $category : ''
	);
	
	// RUN THE QUERY	
	$records = $DB->get_records_sql($query, null, 0, $limit);

	if( !!$records ) {
		foreach ($records as $record) {
	
			// Add the product matching this id to the array
			$products[] = local_buykart_get_product($record->productid);
	
		}
	}

	return $products;
}

/**
 * Returns a list of <option> tags of each category
 * @param  int $id the active category
 * @return string     the HTML <option> list
 */
function local_buykart_get_category_list($id) {
	global $DB;

	$list = sprintf(
		'<option value="default" %s>All</option>',
		$id == null ? 'selected="selected"' : ''
	);

	$categories = $DB->get_records('course_categories');

	if (!!$categories) {
		foreach ($categories as $category) {
			if($category->visible) {
				$list .= sprintf(
					'<option value="%d" %s>%s</option>',
					$category->id,
					(int) $category->id === $id ? 'selected="selected"' : '',
					$category->name
				);
			}
		}
	}

	return $list;
}

function local_buykart_get_groups($id) {
	global $CFG;
	require_once $CFG->libdir . '/grouplib.php';
	$arr = array(
		0 => get_string('product_variation_group_none', 'local_buykart')
	);
	$groups = groups_get_all_groups($id);

	foreach ($groups as $g) {
		$arr[$g->id] = $g->name;
	}

	return $arr;
}

function local_buykart_extract_sort_vars($sort) {
	$sortfield = 'sortorder';
	$sortorder = 'ASC';

	if ($sort !== null && 0 < strlen($sort) && strpos('-', $sort) !== -1) {
		$sortArray = explode('-', $sort);

		$sortfield = $sortArray[0];
		$sortorder = strtoupper($sortArray[1]);
	}

	return array($sortfield, $sortorder);
}

function get_transaction_currency($tranid) {
	
	global $DB;
	//make the currency dynamic
	$transaction_gateway = $DB->get_record('local_buykart_transaction',array('id'=>$tranid));
	if ($transaction_gateway) {
		if (($transaction_gateway->gateway == 'BUYKART_GATEWAY_PAYTM') || ($transaction_gateway->gateway == 'BUYKART_GATEWAY_PAYUMONEY')|| ($transaction_gateway->gateway == 'BUYKART_GATEWAY_INSTAMOJO')) {
			$currency = 'INR';
		} else {
			$currency = '$';
		}
		
	}
	
	return $currency;
	
}

function get_transaction_items($tranid,$status) {

	global $CFG,$DB,$USER;
	$trans_items = $DB->get_records('local_buykart_trans_item',array('transaction_id'=>$tranid));
    $items = '';
    $total = 0;
    $username = $USER->firstname.' '.$USER->lastname;
    $user_details = get_string('userdetail','local_buykart');
    $transaction_details = get_string('itemdetail','local_buykart');
	
	$getcurrency = get_transaction_currency($tranid);
	
    foreach ($trans_items as $trans_item) {
        $product = $DB->get_record('local_buykart_product',array('id'=>$trans_item->product_id));
        $course = $DB->get_record('course',array('id'=>$product->course_id));
        $coursename = html_writer::tag('a', $course->fullname,
                         array('href' => new moodle_url($CFG->wwwroot.'/course/view.php?id='.$course->id)));
        $productname = '<span class="itemname">'.$coursename.'</span>';
		
		
		
        $cost = '<span class="cost">'.$getcurrency.' '.$trans_item->item_cost.'</span>';
        $items .= '<li class="list-group-item">'.$cost.'-'.$productname.'</li>';
        $total = $total + $trans_item->item_cost;
    }
    $table1 = new html_table();
    $table1->head = (array) array('','');
    $table1->data[] = array(
        '<ul class="list-group">
              <li class="list-group-item active">'.$user_details.'</li>
              <li class="list-group-item">Name : '.$username.'</li>
              <li class="list-group-item">Email : '.$USER->email.'</li>
              <li class="list-group-item">Mobile : '.$USER->phone2.'</li>
              <li class="list-group-item">Address: '.$USER->city.'</li>
         </ul>',
         '<ul class="list-group">
	         <li class="list-group-item active">'.$transaction_details.'</li>'
	         .$items.
         '</ul>'                   
        );  
    $table1->data[] = array(
    	'<ul class="list-group">
              '.$status.'
         </ul>',
         '<ul class="list-group">
	         <li class="list-group-item list-group-item-info">Total Amount : '.$getcurrency.' '.$total.'</li>
	     </ul>'            
        );
    return html_writer::table($table1);
}
function get_invoice_items($tranid,$status) {
	global $CFG,$DB,$USER;
	$trans_items = $DB->get_records('local_buykart_trans_item',array('transaction_id'=>$tranid));
    $items = '';
    $total = 0;
    $username = $USER->firstname.' '.$USER->lastname;
    $user_details = '<u>'.get_string('userdetail','local_buykart').'</u>';
    $transaction_details = '<u>'.get_string('itemdetail','local_buykart').'<u>';
	
	$getcurrency = get_transaction_currency($tranid);
	
    foreach ($trans_items as $trans_item) {
        $product = $DB->get_record('local_buykart_product',array('id'=>$trans_item->product_id));
        $course = $DB->get_record('course',array('id'=>$product->course_id));
        $coursename = html_writer::tag('a', $course->fullname,
                         array('href' => new moodle_url($CFG->wwwroot.'/course/view.php?id='.$course->id)));
        $productname = '<span class="itemname">'.$coursename.'</span>';
        $cost = '<span class="cost"> '.$getcurrency.' '.$trans_item->item_cost.'</span>';
        $items .= '<li class="list-group-item active">'.$cost.'-'.$productname.'</li>';
        $total = $total + $trans_item->item_cost;
    }
    $table1 = new html_table();
    $table1->head = (array) array($user_details,$transaction_details);
    $table1->data[] = array(
        '<ul class="list-group">
              <li class="list-group-item active">Name : '.$username.'</li>
              <li class="list-group-item">Email : '.$USER->email.'</li>
              <li class="list-group-item">Mobile : '.$USER->phone2.'</li>
              <li class="list-group-item">Address: '.$USER->city.'</li>
         </ul>',
         '<ul class="list-group">'.$items.'</ul>'                   
        );  
    $table1->data[] = array(
        '<p class="orderstatus">Order Status : '.$status.'</p>',
        '<p class="orderstatus">Total Amount : '.$getcurrency.' '.$total.'</p>',           
        );
    echo html_writer::table($table1);
}
function get_invoice_pdf($transactionid,$transid) {
	global $CFG,$DB,$USER;
	global $CFG,$USER;
        $items = '';
        $total = 0;
        $fullname = $USER->firstname.' '.$USER->lastname.' ('.$USER->email.')';
        $user_details = '<u>'.get_string('userdetail','local_buykart').'</u>';
        $transaction_details = '<u>'.get_string('itemdetail','local_buykart').'<u>';
        $userpicture =  "<img src='".$CFG->wwwroot."/user/pix.php?file=/".$USER->id."/f1.jpg'/>";
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);

        $pagetitle = $filename = get_string('invoice','local_buykart').'_'.$transid;
        $pdf->SetTitle($pagetitle);
        $invoicesubhead = get_string('invoiceid','local_buykart').$transid;
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $fullname, $invoicesubhead);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, '20', PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();
		
		$getcurrency = get_transaction_currency($transactionid);
		
        $trans_itemss = $DB->get_records('local_buykart_trans_item',array('transaction_id'=>$transactionid));
        $html = '<div style="background-color:#0078c5;color:#fff;font-size:20px;font-weight: bolder;padding: 6px;border-top: 1px solid #ddd;"><span style="text-align: left;padding-left: 20px;">'.get_string('buykartinvoicetext','local_buykart').'</span></div>';
        $html .= '<div class="col-md-12">
                    <div style="background-color:#f5f5f5;color:#333;font-size:14px;font-weight:bolder;
                    border: 1px solid #ddd;line-height: 20px;">
                        <span style="text-align: left"><bolder>'.get_string('ordersummary','local_buykart').'</bolder></span>
                    </div>
                    <table style="border: 1px solid #ddd;">

                        <thead>
                            <tr style="background-color: #ddd;">
                                <td><strong>Item(s)</strong></td>
                                <td class="text-center"><strong>Price</strong></td>
                                <td class="text-center"><strong>Quantity</strong></td>
                                <td class="text-right"><strong>Totals</strong></td>
                            </tr>
                        </thead>

                        <tbody>';
                        foreach ($trans_itemss as $trans_item1) {
                            $product = $DB->get_record('local_buykart_product',array('id'=>$trans_item1->product_id));
                            $course = $DB->get_record('course',array('id'=>$product->course_id));
                            $coursename = html_writer::tag('span', $course->fullname,
                                             array());
                            $productname = '<span class="itemname">'.$coursename.'</span>';
                            $cost = '<span class="cost">'.$getcurrency.' '.$trans_item1->item_cost.'</span>';
                            $items .= '<li class="list-group-item active">'.$cost.'-'.$productname.'</li>';
                            $total = $total + $trans_item1->item_cost;
							
							$trans_status = $DB->get_record('local_buykart_transaction',array('id'=>$transactionid));
							if ($trans_status->status == 2) {
								$paystatus = 'Success';
							} else {
								$paystatus = 'Not successful';
							}
							$paytype = 'Online Transaction';
							
                            $html .='<tr>
                                    <td>'.$productname.'</td>
                                    <td class="text-center">'.$cost.'</td>
                                    <td class="text-center">1</td>
                                    <td class="text-right">'.$cost.'</td>
                                </tr>';
                        }
                        $subtotal = '00.00'; 
                        $shipping = '00.00'; 
                        $total = $getcurrency.' '.$total;  
                        $html .='<tr>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                <td class="thick-line text-right">'.$subtotal.'</td>
                            </tr>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>Shipping</strong></td>
                                <td class="no-line text-right">'.$shipping.'</td>
                            </tr>
                            <hr>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>Total</strong></td>
                                <td class="no-line text-right">'.$total.'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>';
                $html .= '<div style="background-color:#f5f5f5;color:#333;font-size:14px;font-weight:bolder;
                    border: 1px solid #ddd;line-height: 20px;">
                            <span style="text-align: left"><bolder>Payment Type   : '. $paytype .'</bolder></span><br>
                            <span style="text-align: left"><bolder>Payment Status : '. $paystatus .'</bolder></span>
                        </div><p></p>';
                
                $imagedir = "local/buykart/pix";
                $imageLocation = $CFG->dataroot . '/' . $imagedir.'/*.jpg';
                $image = glob($imageLocation); 
                if($image) {
	                $l1 = explode('.', $image[0]); 
	                $l2 = explode('/', $l1[0]);
	                foreach ($l2 as $l3) {
	                    $l4 = $l3; 
	                }
					$path = "$CFG->dataroot/local/buykart/pix";
					$imageLocation = $path . '/'.$l4.'.'.$l1[1];
	                $logo =  '<img src="'.$imageLocation.'" width="120" height="60"/>';
            	}else{
            		 $logo = '';
            	}
                $invoicetext = $DB->get_record('config_plugins',array('plugin'=>'local_buykart','name'=>'invoicetext'),'value');
                if($invoicetext){
                	$address = $invoicetext->value;
                }else{
                	$address = '';
                }
                $html .= '<table style="border-bottom: 1px solid #ddd;padding-bottom:5px">
                            <thead>
                                <tr>
                                    <td style="width:150px;">'.$logo.'</td>
                                    <td style="width:50px;"></td>
                                    <td>'.$address.'</td>
                                </tr>
                            </thead>
                          </table>';
        $companyname = get_string('companyname','local_buykart');
        $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
        
        $pdf->Write(5, $companyname, '');

        $filename = 'invoice_'.$transactionid.'.pdf';
        $filecontents = $pdf->Output($filename, 'I');
        return send_file($filecontents, $filename, 0, 0, true, false, 'application/pdf');
}
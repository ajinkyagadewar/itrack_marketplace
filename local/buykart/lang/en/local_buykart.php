<?php
/**
 * Buykart Language file
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	prashant yallatti
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Buykart';

// SETTINGS
$string['buykart_pages'] = 'Page settings';
$string['buykart_settings'] = 'General settings';
$string['invoice_settings'] = 'Invoice settings';
$string['buykart_course_settings'] = 'Edit product settings';
$string['buykart_product_settings'] = 'Edit product settings';
$string['page_setting_heading_catalogue'] = 'Catalogue page';
$string['page_setting_heading_product'] = 'Product page';
$string['buykart_invoice_settings'] = 'Invoice settings';
$string['upload'] = 'Upload image';
$string['uploadimage'] = 'Upload image';
$string['uploadimagedesc'] = 'This button will take you to a new screen where you will be able to upload images.';
$string['border'] = 'Border';
$string['watermark'] = 'Watermark';
$string['signature'] = 'Signature';
$string['seal'] = 'Seal';
$string['imagetype'] = 'Image Type';


$string['page_catalogue_show_description'] = 'Show course description';
$string['page_catalogue_show_description_desc'] = 'This will show the course description excerpt on the catalogue list page';
$string['page_catalogue_show_additional_description'] = 'Show additional description';
$string['page_catalogue_show_additional_description_desc'] = 'This will show the additional description excerpt on the catalogue list page';
$string['page_catalogue_show_duration'] = 'Show product enrolment duration';
$string['page_catalogue_show_duration_desc'] = 'This will show the product enrolment duration excerpt on the catalogue list page';
$string['page_catalogue_show_image'] = 'Show product image';
$string['page_catalogue_show_image_desc'] = 'This will show the product image on the catalogue list page';
$string['page_catalogue_show_category'] = 'Show product category';
$string['page_catalogue_show_category_desc'] = 'This will show the product category on the catalogue list page';
$string['page_catalogue_show_price'] = 'Show product price';
$string['page_catalogue_show_price_desc'] = 'This will show the product price on the catalogue list page';
$string['page_catalogue_show_button'] = 'Show add to cart button';
$string['page_catalogue_show_button_desc'] = 'This will show the add to cart button on the catalogue list page';

$string['page_product_enable'] = 'Enable this page';
$string['page_product_enable_desc'] = 'This will allow users to view individual products and add links to the navigation block';
$string['page_product_show_image'] = 'Show product image';
$string['page_product_show_image_desc'] = 'This will show the product image on the product page';
$string['page_product_show_description'] = 'Show course description';
$string['page_product_show_description_desc'] = 'This will show the course description excerpt on the product page';
$string['page_product_show_additional_description'] = 'Show product\'s additional description';
$string['page_product_show_additional_description_desc'] = 'This will show the product\'s additional description excerpt on the product page';
$string['page_product_show_category'] = 'Show course category';
$string['page_product_show_category_desc'] = 'This will show the course category on the product page';
$string['page_product_show_related_products'] = 'Show related products';
$string['page_product_show_related_products_desc'] = 'This will show the related products on the product page';

$string['businessemail'] = 'PayPal business email';
$string['businessemail_desc'] = 'The email address of your business PayPal account';
$string['currency'] = 'Currency';
$string['pagination'] = 'Courses per page';
$string['pagination_desc'] = 'The number of courses to be displayed per page in the catalogue';

$string['invoicetext'] = 'Invoice custom text';
$string['invoicetext_desc'] = 'The text entered here appears on the buykart order invoices';

$string['payment_title'] = 'Payment Gateways';
$string['payment_enable'] = 'Enable';
$string['payment_enable_desc'] = 'Enable this payment gateway';

$string['payment_payumoney_title'] = 'Payumoney';
$string['payment_payumoney_key'] = 'Payumoney Merchant Key';
$string['payment_payumoney_key_desc'] = 'Enter your Payumoney Merchant Key';
$string['payment_payumoney_salt'] = 'Payumoney Merchant Salt';
$string['payment_payumoney_salt_desc'] = 'Enter your Payumoney Merchant Salt';
$string['payment_payumoney_sandbox'] = 'Use sandbox mode';
$string['payment_payumoney_live_url'] = 'Live url';
$string['payment_payumoney_live_url_desc'] = 'Enter Payumoney live url here';
$string['payment_payumoney_test_url'] = 'Test url';
$string['payment_payumoney_test_url_desc'] = 'Enter Payumoney test url here';
$string['payment_payumoney_sandbox_desc'] = 'This will enable Payumoney sandbox mode for the gateway. You will need to enter a development Key and salt to test.';

$string['payment_ebs_title'] = 'EBS';
$string['payment_ebs_accountid'] = 'EBS Merchant AccountId';
$string['payment_ebs_accountid_desc'] = 'Enter your EBS Merchant AccountId';
$string['payment_ebs_SecretKey'] = 'EBS SecretKey';
$string['payment_ebs_SecretKey_desc'] = 'Enter your EBS SecretKey';
$string['payment_ebs_sandbox'] = 'Use sandbox mode';
$string['payment_ebs_sandbox_desc'] = 'This will enable EBS sandbox mode for the gateway. You will need to enter a development AccountId and SecretKey to test.';


$string['payment_paypal_title'] = 'Paypal';
$string['payment_paypal_email'] = 'Email';
$string['payment_paypal_email_desc'] = 'Enter your Paypal business email address';
$string['payment_paypal_sandbox'] = 'Use sandbox mode';
$string['payment_paypal_sandbox_desc'] = 'This will enable Paypal sandbox for the gateway';

// Errors

$string['error_invalid_name'] = 'Required field. Please enter a name.';
$string['error_invalid_price'] = 'Invalid format: please enter a single number only, such as 20 or 19.99 - no currency symbols or letters are allowed.';
$string['error_invalid_duration'] = 'Invalid format: please enter a single number, indicating the days for the duration of the course. The number 0 represents an unlimited duration.';

$string['messageprovider:payment_gateway'] = 'Notifications from the Buykart Payment Gateway';

// Pages

// CATALOGUE
$string['catalogue_title'] = 'Marketplace';
$string['catalogue_empty'] = 'No products available.';
$string['catalogue_enrolment_duration_label'] = 'Course duration:';
$string['filter_category_label'] = 'Category:';
$string['filter_sort_label'] = 'Sort by:';
$string['filter_sort_default_asc'] = 'Default';
$string['filter_sort_fullname_asc'] = 'Course Title: A - Z';
$string['filter_sort_fullname_desc'] = 'Course Title: Z - A';
$string['filter_sort_price_asc'] = 'Price: Low to High';
$string['filter_sort_price_desc'] = 'Price: High to Low';
$string['filter_sort_duration_asc'] = 'Duration: Low to High';
$string['filter_sort_duration_desc'] = 'Duration: High to Low';
$string['course_list_category_label'] = 'Category:';

// PRODUCT
$string['product_title'] = '{$a->coursename}';
$string['enrolment_duration_label'] = 'Course duration:';
$string['price_label'] = 'Price:';
$string['product_related_label'] = 'Related Products';
$string['product_related_button_label'] = 'View details';

// CHECKOUT
$string['checkout_title'] = 'Checkout';
$string['checkout_message'] = 'Please review your cart once more before purchasing.';
$string['checkout_removed_courses_label'] = 'The following courses have been removed from your cart as they are either invalid, or you are already enrolled in them:';
$string['checkout_total'] = 'Total:';
$string['checkout_guest_message'] = 'You cannot be logged in as guest to purchase courses! Please log out and create your own account to continue.';

// CART
$string['cart_title'] = 'Cart';
$string['cart_total'] = 'Total:';
$string['cart_empty_message'] = 'Your cart is empty!';

// Order History Reports
$string['shopping_report_title'] = 'Order history';
$string['history_title'] = 'Order history';


// Buttons
$string['button_add_label'] = 'Add to cart';
$string['button_remove_label'] = 'Remove';
$string['button_removeall_label'] = 'Empty cart';
$string['button_checkout_label'] = 'Proceed to checkout';
$string['button_paypal_label'] = 'Pay with PayPal';
$string['button_dps_label'] = 'Pay via DPS';
$string['button_payumoney_label'] = 'Pay with Payumoney';
$string['button_ebs_label'] = 'Pay with EBS';
$string['button_return_store_label'] = 'Return to store';
$string['button_logout_label'] = 'Logout';
$string['button_enrolled_label'] = 'Already enrolled';
$string['button_in_cart_label'] = 'In cart';

// Lib
$string['enrolment_duration_unlimited'] = 'Unlimited';
$string['enrolment_duration_year'] = 'year';
$string['enrolment_duration_year_plural'] = 'years';
$string['enrolment_duration_month'] = 'month';
$string['enrolment_duration_month_plural'] = 'months';
$string['enrolment_duration_week'] = 'week';
$string['enrolment_duration_week_plural'] = 'weeks';
$string['enrolment_duration_day'] = 'day';
$string['enrolment_duration_day_plural'] = 'days';

// Edit Product form
$string['edit_product_form_title'] = 'Product settings for {$a->name}';
$string['product_enabled'] = 'Enable';
$string['product_enabled_label'] = 'Toggle whether this product is shown in the store and able to be bought';
$string['product_description'] = 'Description';
$string['product_description_help'] = 'The Buykart product description. This can be combined with the default Course summary and used as additional information, or as the only means to display information on the product page.';
$string['product_tags'] = 'Product tags';
$string['product_tags_help'] = 'List the keyword tags, comma separated, which will be used when performing a search.';
$string['product_type'] = 'Product type';
$string['product_type_help'] = 'Select the type of product this is. Simple products have a single price, duration and group that can be assigned to it. Variable products can have up to 10 variations specified - each with its own name, price and duration.';
$string['product_type_simple_label'] = 'Simple';
$string['product_type_variable_label'] = 'Variable';
$string['product_variation_header'] = 'Variation {$a->count}';
$string['product_variation_enabled'] = 'Enable';
$string['product_variation_enabled_label'] = 'Toggle whether this variation is active or not';
$string['product_variation_count'] = 'Number of variations';
$string['product_variation_count_help'] = 'Select the number of variations for variable product types, up to 10. Simple product types only have 1 variation.';
$string['product_variation_name'] = 'Name';
$string['product_variation_name_help'] = 'The name to be displayed in the store for this variation. This is not shown for Simple product types.';
$string['product_variation_price'] = 'Price';
$string['product_variation_price_help'] = 'The price, set to 2 decimal places. Do not include currency symbol.';
$string['product_variation_duration'] = 'Duration';
$string['product_variation_duration_help'] = 'Enter the number of days for the course enrolment duration. A value of 0 will set an unlimited enrolment duration.';
$string['product_variation_group'] = 'Group';
$string['product_variation_group_help'] = 'Select a course group for the user to be assigned when they purchase this variation.';
$string['product_variations_update'] = 'Update variations form';
$string['product_variation_group_none'] = 'No group';
$string['AUD'] = 'Australian Dollar';
$string['CAD'] = 'Canadian Dollar';
$string['CHF'] = 'Swiss Franc';
$string['DKK'] = 'Danish Krone';
$string['EUR'] = 'Euro';
$string['GBP'] = 'British Pound Sterling';
$string['HKD'] = 'Hong Kong Dollar';
$string['JPY'] = 'Japanese Yen';
$string['MYR'] = 'Malaysian Ringgit';
$string['NZD'] = 'New Zealand Dollar';
$string['SGD'] = 'Singapore Dollar';
$string['THB'] = 'Thai Baht';
$string['USD'] = 'US Dollar';
$string['INR'] = 'Indian Rupee';

//For Transaction/Orders Reports
$string['slno'] = 'Sl.No';
$string['orderno'] = 'Order Number';
$string['orderdate'] = 'Order Date';
$string['orderamount'] = 'Order Amount';
$string['status'] = 'Order Status';
$string['invoice'] = 'Invoice';
$string['product'] = 'Items(s)';
$string['productamount'] = 'Item Amount';
$string['goback'] = 'Go back';
$string['userdetail'] = 'User details';
$string['itemdetail'] = '<span class="cost">Amount</span>-<span class="itemname">Item Name</span>';
$string['orderno'] = 'Order Number : ';
$string['openeinvoice'] = 'Click the button below to download invoice.';
$string['getinvoice'] = 'Get invoice';
$string['invoiceid'] = 'Order id : ';
$string['username'] = 'User name : ';
$string['invoicehead'] = 'Invoice';
$string['companyname'] = '@Dhruv Infoline Pvt Ltd';
$string['ordersummary'] = 'Order Summary';
$string['buykartinvoicetext'] = 'Buykart Invoice';
$string['unsupportedfiletype'] = 'Image type not supported (Please Use JPG images)'; 
$string['nofileselected'] = 'No file selected'; 
$string['filesizelimit'] = 'File size should be less than 1MB'; 
$string['success'] = 'Order Status : Successful';
$string['pending'] = 'Order Status : Pending';

//paytm setting lang file added by prashant
$string['payment_paytm_title'] = 'Paytm';
$string['payment_paytm_environment'] = 'Enter Paytm Environment';
$string['payment_paytm_environment_desc'] = 'Enter Paytm Environment Description';

$string['payment_paytm_merchant_key'] = 'Paytm Merchant Key';
$string['payment_paytm_merchant_key_desc'] = 'Enter your Paytm Merchant Key';
$string['payment_paytm_merchant_mid'] = 'Paytm Merchant MID';
$string['payment_paytm_merchant_mid_desc'] = 'Enter your Paytm MID';

$string['payment_paytm_merchant_website'] = 'Paytm  Website name';
$string['payment_paytm_merchant_website_desc'] = 'Enter your Website name';

$string['payment_paytm_sandbox'] = 'Use paytm test mode';
$string['payment_paytm_sandbox_desc'] = 'This will enable Paytm sandbox mode for the gateway. You will need to enter a development AccountId and SecretKey to test.';
$string['button_paytm_label'] = 'Pay with Paytm';

//new lang file is added here 
$string['payment_paytm_industry_id'] = 'Industry type id ';
$string['payment_paytm_industry_id_desc'] = 'Enter industry type id';
$string['payment_paytm_industry_channel_id'] = 'Channel id';
$string['payment_paytm_industry_channel_id_desc'] = 'Enter Channel id';
$string['payment_paytm_request_type'] = 'Request type';
$string['payment_paytm_request_type_desc'] = 'Enter paytm request type';


//instamojo setting lang text added by prashant
$string['payment_instamojo_title'] = 'Instamojo';
$string['payment_instamojo_apikey'] = 'Enter instamojo apikey';
$string['payment_instamojo_apikey_desc'] = 'Enter instamojo apikey description';
$string['payment_instamojo_auth_token'] = 'Instamojo auth token';
$string['payment_instamojo_auth_token_desc'] = 'Enter your auth token';

$string['payment_instamojo_salt'] = 'Instamojo salt';
$string['payment_instamojo_salt_desc'] = 'Enter your salt';

$string['payment_instamojo_sandbox'] = 'Use instamojo test mode';
$string['payment_instamojo_sandbox_desc'] = 'This will enable instamojo sandbox mode for the gateway. You will need to enter a development apikey,auth_token,salt.';
$string['payment_instamojo_environment'] = 'Enter instamojo Environment';
$string['payment_instamojo_environment_desc'] = 'Enter instamojo Environment Description';
$string['button_instamojo_label'] = 'Pay with Instamojo';
$string['payment_instamojo_purpose'] = 'Purpose of payment';
$string['payment_instamojo_purpose_desc'] = 'Describe purpose of payment';
$string['payment_instamojo_testurl'] = 'Test url';
$string['payment_instamojo_testurl_desc'] = 'Enter test url for payment';
$string['payment_instamojo_liveurl'] = 'Live url';
$string['payment_instamojo_liveurl_desc'] = 'Enter test url for payment';

//prashant is updating lang file here 
$string['status_pending'] = 'Status not completed or pending. User unenrolled from course';
$string['currency_not'] = 'Currency does not match course settings, received:';
$string['instamojo_sub'] = 'Moodle: Instamojo payment';
$string['instamojo_pen'] = 'Your Instamojo payment is pending.';
$string['invalidtr'] = 'Invalid Transaction. Please try again';
$string['notvaliduser'] = '"Not a valid user id"';
$string['payumoney'] ='Moodle: Payumoney payment';
$string['payumoneypending'] = 'Your Payumoney payment is pending.';
$string['paytm'] = 'Moodle: Paytm payment';
$string['paytmpending'] = 'Your Paytm payment is pending.';
$string['paymentmethod'] = 'Payment Method';


//prashant is updating lang file for stripe payment  
$string['payment_stripe_title'] = 'Stripe';
$string['payment_stripe_secret_key'] = 'Stripe secret key';
$string['payment_stripe_secret_key_desc'] = 'Enter stripe secret key';
$string['payment_stripe_publishableKey'] = 'Stripe publishablekey';
$string['payment_stripe_publishableKey_desc'] = 'Enter stripe publishable key';
$string['stripe'] = 'Moodle: stripe payment';
$string['stripepending'] = 'Your stripe payment is pending.';
$string['button_stripe_label'] = 'Pay with Stripe';
$string['note'] = 'Note:';
$string['sandbox'] = 'In Stripe Payment Gatway there in no sandbox url provided insted of that we need to use test secret key and test publishableKey';
$string['currency_desc'] ='Slect only us dollar';
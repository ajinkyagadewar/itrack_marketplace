<?php
/**
 * BuyKart Settings file
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	Thomas Threadgold
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once $CFG->dirroot . '/local/buykart/lib.php';
require_once $CFG->dirroot . '/local/buykart/classes/admin_setting_upload.php';

if ($hassiteconfig) {
	$ADMIN->add(
		'root',
		new admin_category(
			'buykart',
			get_string(
				'pluginname',
				'local_buykart'
				)
			)
		);

	$ADMIN->add(
		'buykart',
		new admin_externalpage(
			'buykartsettings',
			get_string(
				'buykart_settings',
				'local_buykart'
				),
			$CFG->wwwroot . '/admin/settings.php?section=local_buykart_settings',
			'moodle/course:update'
			)
		);

	$ADMIN->add(
		'buykart',
		new admin_externalpage(
			'buykartpages',
			get_string(
				'buykart_pages',
				'local_buykart'
				),
			$CFG->wwwroot . '/admin/settings.php?section=local_buykart_pages',
			'moodle/course:update'
			)
		);
	$ADMIN->add(
		'buykart',
		new admin_externalpage(
			'invoicesettings',
			get_string(
				'invoice_settings',
				'local_buykart'
				),
			$CFG->wwwroot . '/admin/settings.php?section=local_buykart_invoice_settings',
			'moodle/course:update'
			)
		);

	//
	// Add category to local plugins category
	//
	$ADMIN->add(
		'localplugins',
		new admin_category(
			'local_buykart',
			get_string(
				'pluginname',
				'local_buykart'
				)
			)
		);

	//
	// Add generic settings page
	//
	$settings = new admin_settingpage(
		'local_buykart_settings',
		get_string(
			'buykart_settings',
			'local_buykart'
			)
		);
	$ADMIN->add('local_buykart', $settings);

	$paypalcurrencies = local_buykart_get_currencies();
	$settings->add(
		new admin_setting_configselect(
			'local_buykart/currency',
			get_string(
				'currency',
				'local_buykart'
				),
			'',
			'USD',
			$paypalcurrencies
			)
		);

	$settings->add(
		new admin_setting_configtext(
			'local_buykart/pagination',
			get_string(
				'pagination',
				'local_buykart'
				),
			get_string(
				'pagination_desc',
				'local_buykart'
				),
			10,
			PARAM_INT
			)
		);

	// Add invoice settings page

	$settings = new admin_settingpage(
		'local_buykart_invoice_settings',
		get_string(
			'buykart_invoice_settings',
			'local_buykart'
			)
		);
	$ADMIN->add('local_buykart', $settings);
	$settings->add(new local_buykart_admin_setting_upload('buykart/uploadimage',
		get_string('uploadimage', 'local_buykart'), get_string('uploadimagedesc', 'local_buykart'), ''));

	$settings->add(new admin_setting_configtextarea('local_buykart/invoicetext',get_string('invoicetext',
		'local_buykart'),get_string('invoicetext_desc','local_buykart'),''));
	

	//
	// Add category to local plugins category
	//
	$ADMIN->add(
		'local_buykart',
		new admin_category(
			'buykart_payment',
			get_string(
				'payment_title',
				'local_buykart'
				)
			)
		);


	
//////////////////////////payumoney setting//////////////////////////


	// ADD PAYUMONEY PAYMENT SETTINGS PAGE
	//
	$settings = new admin_settingpage(
		'local_buykart_settings_payumoney',
		get_string(
			'payment_payumoney_title',
			'local_buykart'
			)
		);
	$ADMIN->add('buykart_payment', $settings);

	// 
	// Add payumoney enable checkbox
	// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_payumoney_enable',
			get_string(
				'payment_enable',
				'local_buykart'
				),
			get_string(
				'payment_enable_desc',
				'local_buykart'
				),
			0
			)
		);

	// 
	// Add payumoney maerchant id setting
	// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_payumoney_key',
			get_string(
				'payment_payumoney_key',
				'local_buykart'
				),
			get_string(
				'payment_payumoney_key_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

	// 
	// Add payumoney merchant salt setting
	// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_payumoney_salt',
			get_string(
				'payment_payumoney_salt',
				'local_buykart'
				),
			get_string(
				'payment_payumoney_salt_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
	//payumoney live website url
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_payumoney_live_url',
			get_string(
				'payment_payumoney_live_url',
				'local_buykart'
				),
			get_string(
				'payment_payumoney_live_url_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
	//payumoney test website url
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_payumoney_test_url',
			get_string(
				'payment_payumoney_test_url',
				'local_buykart'
				),
			get_string(
				'payment_payumoney_test_url_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);


	// 
	// Add payumoney sandbox checkbox
	// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_payumoney_sandbox',
			get_string(
				'payment_payumoney_sandbox',
				'local_buykart'
				),
			get_string(
				'payment_payumoney_sandbox_desc',
				'local_buykart'
				),
			0
			)
		);



//////////////////////////paytm setting Prashant//////////////////////////
		//paytm settings 
	$settings = new admin_settingpage(
		'local_buykart_settings_paytm',
		get_string(
			'payment_paytm_title',
			'local_buykart'
			)
		);
	$ADMIN->add('buykart_payment', $settings);

		// 
		// Add paytm enable checkbox
		// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_paytm_enable',
			get_string(
				'payment_enable',
				'local_buykart'
				),
			get_string(
				'payment_enable_desc',
				'local_buykart'
				),
			0
			)
		);

		// 
		// Add paytm environment  setting
		// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paytm_environment',
			get_string(
				'payment_paytm_environment',
				'local_buykart'
				),
			get_string(
				'payment_paytm_environment_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		// 
		// Add PAYTM_MERCHANT_KEY setting
		// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paytm_merchant_key',
			get_string(
				'payment_paytm_merchant_key',
				'local_buykart'
				),
			get_string(
				'payment_paytm_merchant_key_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		// 
		// Add PAYTM_MERCHANT_MID setting
		// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paytm_merchant_mid',
			get_string(
				'payment_paytm_merchant_mid',
				'local_buykart'
				),
			get_string(
				'payment_paytm_merchant_mid_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		// 
		// Add PAYTM_MERCHANT_WEBSITE setting
		// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paytm_merchant_website',
			get_string(
				'payment_paytm_merchant_website',
				'local_buykart'
				),
			get_string(
				'payment_paytm_merchant_website_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
		//paytm extra setting here industry type id 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paytm_industry_id',
			get_string(
				'payment_paytm_industry_id',
				'local_buykart'
				),
			get_string(
				'payment_paytm_industry_id_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		//channel id 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paytm_channel_id',
			get_string(
				'payment_paytm_industry_channel_id',
				'local_buykart'
				),
			get_string(
				'payment_paytm_industry_channel_id_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		//paytm request type setting
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paytm_request_type',
			get_string(
				'payment_paytm_request_type',
				'local_buykart'
				),
			get_string(
				'payment_paytm_request_type_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
		// 
		// Add ebs sandbox checkbox
		// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_paytm_sandbox',
			get_string(
				'payment_paytm_sandbox',
				'local_buykart'
				),
			get_string(
				'payment_paytm_sandbox_desc',
				'local_buykart'
				),
			0
			)
		);

//////////////////////////ebs setting//////////////////////////


	// ADD EBS PAYMENT SETTINGS PAGE
	//
	$settings = new admin_settingpage(
		'local_buykart_settings_ebs',
		get_string(
			'payment_ebs_title',
			'local_buykart'
			)
		);
	$ADMIN->add('buykart_payment', $settings);

	// 
	// Add ebs enable checkbox
	// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_ebs_enable',
			get_string(
				'payment_enable',
				'local_buykart'
				),
			get_string(
				'payment_enable_desc',
				'local_buykart'
				),
			0
			)
		);

	// 
	// Add ebs userid setting
	// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_ebs_AccountID',
			get_string(
				'payment_ebs_accountid',
				'local_buykart'
				),
			get_string(
				'payment_ebs_accountid_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

	// 
	// Add ebs secret key setting
	// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_ebs_SecretKey',
			get_string(
				'payment_ebs_SecretKey',
				'local_buykart'
				),
			get_string(
				'payment_ebs_SecretKey_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

	// 
	// Add ebs sandbox checkbox
	// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_ebs_sandbox',
			get_string(
				'payment_ebs_sandbox',
				'local_buykart'
				),
			get_string(
				'payment_ebs_sandbox_desc',
				'local_buykart'
				),
			0
			)
		);


	//
	// ADD PAYPAL PAYMENT SETTINGS PAGE
	//
	$settings = new admin_settingpage(
		'local_buykart_settings_paypal',
		get_string(
			'payment_paypal_title',
			'local_buykart'
			)
		);
	$ADMIN->add('buykart_payment', $settings);

	// 
	// Add paypal enable checkbox
	// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_paypal_enable',
			get_string(
				'payment_enable',
				'local_buykart'
				),
			get_string(
				'payment_enable_desc',
				'local_buykart'
				),
			0
			)
		);

	// 
	// Add paypal business email setting
	// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_paypal_email',
			get_string(
				'payment_paypal_email',
				'local_buykart'
				),
			get_string(
				'payment_paypal_email_desc',
				'local_buykart'
				),
			'',
			PARAM_EMAIL
			)
		);

	// 
	// Add paypal sandbox checkbox
	// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_paypal_sandbox',
			get_string(
				'payment_paypal_sandbox',
				'local_buykart'
				),
			get_string(
				'payment_paypal_sandbox_desc',
				'local_buykart'
				),
			0
			)
		);

	//
	// Add page settings
	//
	$pages = new admin_settingpage(
		'local_buykart_pages',
		get_string(
			'buykart_pages',
			'local_buykart'
			)
		);
	$ADMIN->add('local_buykart', $pages);

	// Catalogue Page
	$pages->add(
		new admin_setting_heading(
			'local_buykart/page_setting_heading_catalogue',
			get_string(
				'page_setting_heading_catalogue',
				'local_buykart'
				),
			''
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_catalogue_show_description',
			get_string(
				'page_catalogue_show_description',
				'local_buykart'
				),
			get_string(
				'page_catalogue_show_description_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_catalogue_show_additional_description',
			get_string(
				'page_catalogue_show_additional_description',
				'local_buykart'
				),
			get_string(
				'page_catalogue_show_additional_description_desc',
				'local_buykart'
				),
			0
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_catalogue_show_duration',
			get_string(
				'page_catalogue_show_duration',
				'local_buykart'
				),
			get_string(
				'page_catalogue_show_duration_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_catalogue_show_image',
			get_string(
				'page_catalogue_show_image',
				'local_buykart'
				),
			get_string(
				'page_catalogue_show_image_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_catalogue_show_category',
			get_string(
				'page_catalogue_show_category',
				'local_buykart'
				),
			get_string(
				'page_catalogue_show_category_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_catalogue_show_price',
			get_string(
				'page_catalogue_show_price',
				'local_buykart'
				),
			get_string(
				'page_catalogue_show_price_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_catalogue_show_button',
			get_string(
				'page_catalogue_show_button',
				'local_buykart'
				),
			get_string(
				'page_catalogue_show_button_desc',
				'local_buykart'
				),
			1
			)
		);

	// Product page
	$pages->add(
		new admin_setting_heading(
			'local_buykart/page_setting_heading_product',
			get_string(
				'page_setting_heading_product',
				'local_buykart'
				),
			''
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_product_enable',
			get_string(
				'page_product_enable',
				'local_buykart'
				),
			get_string(
				'page_product_enable_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_product_show_image',
			get_string(
				'page_product_show_image',
				'local_buykart'
				),
			get_string(
				'page_product_show_image_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_product_show_description',
			get_string(
				'page_product_show_description',
				'local_buykart'
				),
			get_string(
				'page_product_show_description_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_product_show_additional_description',
			get_string(
				'page_product_show_additional_description',
				'local_buykart'
				),
			get_string(
				'page_product_show_additional_description_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_product_show_category',
			get_string(
				'page_product_show_category',
				'local_buykart'
				),
			get_string(
				'page_product_show_category_desc',
				'local_buykart'
				),
			1
			)
		);

	$pages->add(
		new admin_setting_configcheckbox(
			'local_buykart/page_product_show_related_products',
			get_string(
				'page_product_show_related_products',
				'local_buykart'
				),
			get_string(
				'page_product_show_related_products_desc',
				'local_buykart'
				),
			1
			)
		);
	
//instamojo payment setting page here 

		//instamojo settings 
			//instamojo settings 
	$settings = new admin_settingpage(
		'local_buykart_settings_instamojo',
		get_string(
			'payment_instamojo_title',
			'local_buykart'
			)
		);
	$ADMIN->add('buykart_payment', $settings);

		// 
		// Add instamojo enable checkbox
		// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_instamojo_enable',
			get_string(
				'payment_enable',
				'local_buykart'
				),
			get_string(
				'payment_enable_desc',
				'local_buykart'
				),
			0
			)
		);

		// 
		// Add instamojo environment  setting
		// 

	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_instamojo_environment',
			get_string(
				'payment_instamojo_environment',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_environment_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_instamojo_apikey',
			get_string(
				'payment_instamojo_apikey',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_apikey_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		// 
		// Add PAYTM_MERCHANT_KEY setting
		// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_instamojo_auth_token',
			get_string(
				'payment_instamojo_auth_token',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_auth_token_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		// 
		// Add PAYTM_MERCHANT_MID setting
		// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_instamojo_salt',
			get_string(
				'payment_instamojo_salt',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_salt_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
		//purpose of payent instamojo 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_instamojo_purpose',
			get_string(
				'payment_instamojo_purpose',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_purpose_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
		//test url purpose 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_instamojo_testurl',
			get_string(
				'payment_instamojo_testurl',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_testurl_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);
		//live url 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_instamojo_liveturl',
			get_string(
				'payment_instamojo_liveurl',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_liveurl_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);


		// 
		// Add instamojo sandbox setting
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_instamojo_sandbox',
			get_string(
				'payment_instamojo_sandbox',
				'local_buykart'
				),
			get_string(
				'payment_instamojo_sandbox_desc',
				'local_buykart'
				),
			0
			)
		);
		//stripe payment getway settings added by prashant 
		//Stripe setting 
	$settings = new admin_settingpage(
		'local_buykart_settings_stripe',
		get_string(
			'payment_stripe_title',
			'local_buykart'
			)
		);
	$ADMIN->add('buykart_payment', $settings);

		// 
		// Add stripe enable checkbox
		// 
	$settings->add(
		new admin_setting_configcheckbox(
			'local_buykart/payment_stripe_enable',
			get_string(
				'payment_enable',
				'local_buykart'
				),
			get_string(
				'payment_enable_desc',
				'local_buykart'
				),
			0
			)
		);
	$paypalcurrencies = local_buykart_get_currencies();
	$settings->add(
		new admin_setting_configselect(
			'local_buykart/payment_stripe_currency',
			get_string(
				'currency',
				'local_buykart'
				),
			get_string(
				'currency_desc',
				'local_buykart'
				),
			'USD',
			$paypalcurrencies
			)
		);

		// Add stripe secret key setting
		// 
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_stripe_secret_key',
			get_string(
				'payment_stripe_secret_key',
				'local_buykart'
				),
			get_string(
				'payment_stripe_secret_key_desc',
				'local_buykart'
				),
			'',
			PARAM_TEXT
			)
		);

		// 
		// stripe secret publishableKey key setting

	$description = get_string('sandbox', 'local_buykart');
	$settings->add(
		new admin_setting_configtext(
			'local_buykart/payment_stripe_publishableKey',
			get_string(
				'payment_stripe_publishableKey',
				'local_buykart'
				),
			get_string(
				'payment_stripe_publishableKey_desc',
				'local_buykart'
				),
			PARAM_TEXT
			)
		);
	$settings->add(
		new admin_setting_heading(
			'local_buykart/payment_stripe_heading',
			get_string(
				'note',
				'local_buykart'
				),
			$description,
			PARAM_TEXT


			)
		);


}

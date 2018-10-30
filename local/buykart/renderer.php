	<?php
	/**
	 * buykart Renderer
	 *
	 * @package    local_buykart
	 * @copyright  2015 Thomas Threadgold <tj.threadgold@gmail.com>
	 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
	 */
	defined('MOODLE_INTERNAL') || die;

	/**
	 * buykart Renderer
	 *
	 * @copyright  2015 Thomas Threadgold <tj.threadgold@gmail.com>
	 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
	 */
	class local_buykart_renderer extends plugin_renderer_base {

		function catalogue($products) {

			$html = '';

			if (is_array($products) && 0 < count($products)) {

				// OPEN PRODUCT LIST
				$html .= sprintf('<div id="productslist" class="product-list">');

				foreach ($products as $product) {
					
					//$html .= $this->catalogue_item2($product);

				}

				// CLOSE PRODUCT LIST
				$html .= sprintf('</div>');

			} else {

				$html .= sprintf(
					'<div class="catalogue-empty">%s</div>',
					get_string(
						'catalogue_empty',
						'local_buykart'
						)
					);
			}

			return $html;
		}
		function catalogue_item2($product){
			global $CFG;
			require_once $CFG->dirroot . '/local/buykart/lib.php';
			$html = '';
			//image code here 
			if (!!get_config('local_buykart', 'page_catalogue_show_image')) {
				$image = $this->product_image($product);
			} 
			//course description here 
			if (!!get_config('local_buykart', 'page_catalogue_show_description')) {
				$summary = sprintf('%s',$product->get_summary());
			}
			//product summary
			if (!!get_config('local_buykart', 'page_catalogue_show_additional_description')) {
				$desc = sprintf('%s',$product->get_description());
			}
			// output product duration
			if(!!get_config('local_buykart', 'page_catalogue_show_duration')) {
				$duration = $this->product_duration($product);
			}	

			// output product category			
			if (!!get_config('local_buykart', 'page_catalogue_show_category')) {
				$category = $this->product_category($product);
			}
			if (!!get_config('local_buykart', 'page_catalogue_show_price')) {

				if($product->get_type() === PRODUCT_TYPE_SIMPLE) { 

					$price = sprintf('%s',local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')) . $product->get_price());

				} else {
					$attr = '';
					foreach ($product->get_variations() as $v) {
						$attr .= sprintf('data-tier-%d="%.2f" ', $v->get_id(), $v->get_price());
					}

					list($firstVariation) = array_values($product->get_variations());

					$productprice = sprintf('<h4 class="product-price" %s>%s<span class="amount">%.2f</span></h4>',
						$attr,local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')),
						$firstVariation->get_price());

				}
			}
			$button = '';
			if (!!get_config('local_buykart', 'page_catalogue_show_button')) {
				$button = $this->product_button($product);
			}
			$html .= '
						<div id="products" class="item startpage col-lg-4 col-md-12">
						    <div class="card">
						        <div class="image"><img class="card-img-top img-responsive center-small-image" src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/big/course.png" alt="Card image cap"></div>
						        <div class="card-body">
						            <h4 class="card-title">Card title 1</h4>
						            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
						           	'.$button.'
						        </div>
						    </div>
	    			</div>';
    return $html;
		}
		function catalogue_item($product) {
			global $CFG;
			// Require buykart lib
			require_once $CFG->dirroot . '/local/buykart/lib.php';
			//new design code here 
			$html = '';
			//new code design here
			$html .= html_writer::start_tag('div',array('class'=>'row'));//row
			$html .= html_writer::start_tag('div',array('class'=>'col-md-12 first'));//col-md-12
			$html .= html_writer::start_tag('div',array('class'=>'row-fluid'));//row fulid
			$html .= html_writer::start_tag('div',array('class'=>'container'));//container
			$html .= html_writer::start_tag('div',array('class'=>'col-md-12 second'));//col-md-12 second
			$html .= html_writer::start_tag('div',array('class'=>'col-md-4'));//col-md-4
			//image code here 
			if (!!get_config('local_buykart', 'page_catalogue_show_image')) {
				$html .= $this->product_image($product);
			} 
			$html .= html_writer::end_tag('div');//end col-md-4
			$html .= html_writer::start_tag('div',array('class'=>'col-md-8 desc'));//col-md-8 desc
			//title 
			$html .= $this->product_title($product);
			$html .= '<hr>';
			//course description here 
			if (!!get_config('local_buykart', 'page_catalogue_show_description')) {
				$html .= sprintf(
					'<div class="product-summary">%s</div>',
					$product->get_summary()
					);
			}
			//product summary
			if (!!get_config('local_buykart', 'page_catalogue_show_additional_description')) {
				$html .= sprintf(
					'<div class="product-summary additional">%s</div>',
					$product->get_description()
					);
			}
						// output product duration
			if(!!get_config('local_buykart', 'page_catalogue_show_duration')) {
				$html .= $this->product_duration($product);
			}	

						// output product category			
			if (!!get_config('local_buykart', 'page_catalogue_show_category')) {
				$html .= $this->product_category($product);
			}
			$html .= '<hr>';
			$html .= html_writer::start_tag('div',array('class'=>'col-md-12 amount'));//col-md-12 amount
			$html .= html_writer::start_tag('div',array('class'=>'col-md-2 currency'));//col-md-2 currency
			if (!!get_config('local_buykart', 'page_catalogue_show_price')) {

				if($product->get_type() === PRODUCT_TYPE_SIMPLE) { 

					$html .= sprintf(
						'<h4 class="product-price">%s</h4>',
						local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')) . $product->get_price()
						);

				} else {
					$attr = '';

					foreach ($product->get_variations() as $v) {
						$attr .= sprintf('data-tier-%d="%.2f" ', $v->get_id(), $v->get_price());
					}

					list($firstVariation) = array_values($product->get_variations());

					$html .= sprintf('<h4 class="product-price" %s>%s<span class="amount">%.2f</span></h4>',
						$attr,
						local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')),
						$firstVariation->get_price()
						);

				}
			}
			
			$html .= html_writer::end_tag('div');//end col-md-2 currency
			$html .= html_writer::start_tag('div',array('class'=>'col-md-4'));//col-md-4 button
			if (!!get_config('local_buykart', 'page_catalogue_show_button')) {
				$html .= $this->product_button($product);
			}			 
			$html .= html_writer::end_tag('div');//end col-md-4 button
			$html .= html_writer::end_tag('div');//end col-md-12 amount
			$html .= html_writer::end_tag('div');//end col-md-8 desc
			$html .= html_writer::end_tag('div');//end col-md-12 second
			$html .= html_writer::end_tag('div');//end container
			$html .= html_writer::end_tag('div');//end row fulid
			$html .= html_writer::end_tag('div');//end col-md-12 first
			$html .= html_writer::end_tag('div');//end row

			$html .='<br>';
			return $html;
		}


		/**
		 * Outputs the information for the single product page
		 * @param  product 	$product  	object containing all the product information
		 * @return string          		the html output
		 */
		function single_product($product) {
			global $CFG, $DB;

			// Require buykart lib
			require_once $CFG->dirroot . '/local/buykart/lib.php';

			$cart = new buykartCart();

			// Product single wrapper
			$html = '<div class="product-single">';

				// Product title
			$html .= sprintf(
				'<h1 class="product__title">%s</h1>',
				get_string('product_title', 'local_buykart', array('coursename' => $product->get_fullname()) )
				);

				// Product/course image
			if (!!get_config('local_buykart', 'page_product_show_image')) {
				$html .= $this->product_image($product);
			}

				// Product details wrapper
			$html .= '<div class="product-details">';

					// Product description
			if (!!get_config('local_buykart', 'page_product_show_description')) {

				$description = $product->get_summary();

				if( 0 < strlen($description) ) {
					$html .= sprintf(
						'<div class="product-description">%s</div>',
						$description
						);
				}
			}

					// Product additional description
			if (!!get_config('local_buykart', 'page_product_show_additional_description')) {

				if( $product->has_description() ) {
					$html .= sprintf(
						'<div class="additional-info">%s</div>',
						$product->get_description()
						);
				}
			}

					// Additional product details wrapper
			$html .= '<div class="product-details__additional">';

						// Product duration
			$html .= '<div class="product-duration__wrapper">';

			$html .= sprintf(
				'<span class="product-duration__label">%s</span>',
				get_string('enrolment_duration_label', 'local_buykart')
				);

			if( $product->get_type() === PRODUCT_TYPE_SIMPLE) {

				$html .= sprintf(
					'<span class="product-duration">%s</span>',
					$product->get_duration()
					);
			} else {
				$attr = '';

				foreach ($product->get_variations() as $v) {
					$attr .= sprintf('data-tier-%d="%s" ',
						$v->get_id(),
						$v->get_duration()
						);
				}

				list($firstVariation) = array_values($product->get_variations());

				$html .= sprintf(
					'<span class="product-duration" %s>%s</span>',
					$attr,
					$firstVariation->get_duration()
					);
			}

			$html .= '</div>';


						// Product category
			if (!!get_config('local_buykart', 'page_product_show_category')) {

							// Get the category the product belongs to
				$category = $DB->get_record(
					'course_categories',
					array(
						'id' => $product->get_category_id()
						)
					);

							// Get the url to link to the category page with the filter active
				$categoryURL = new moodle_url(
					$CFG->wwwroot . '/local/buykart/pages/catalogue.php',
					array(
						'category' => $product->get_category_id()
						)
					);

							// Category wrapper
				$html .= '<div class="product-category__wrapper">';

								// Category label
				$html .= sprintf(
					'<span class="product-category__label">%s</span> ',
					get_string('course_list_category_label', 'local_buykart')
					);

								// Category link
				$html .= sprintf(
					'<a class="product-category__link" href="%s">%s</a>',
					$categoryURL,
					$category->name
					);

				$html .= '</div>';

			}

						// Product Price
			if($product->get_type() === PRODUCT_TYPE_SIMPLE) {

				$html .= '<div class="product-price">';

								// Price label
				$html .= sprintf(
					'<span class="product-price__label">%s</span>',
					get_string('price_label', 'local_buykart')
					);

								// Price
				$html .= sprintf(
					'<span class="product-price__value">%s<span class="amount">%.2f</span></span>',
					local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')),
					$product->get_price()
					);

				$html .= '</div>';

			} else {

				$attr = '';

				foreach ($product->get_variations() as $v) {
					$attr .= sprintf('data-tier-%d="%.2f" ', $v->get_id(), $v->get_price() );
				}

				list($firstVariation) = array_values($product->get_variations());

				$html .= sprintf(
					'<div class="product-price" %s><span class="product-price__label">%s</span> <span class="product-price__value">%s<span class="amount">%.2f</span></span></div>',
					$attr,
					get_string('price_label', 'local_buykart'),
					local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')),
					$firstVariation->get_price()
					);
			}

						// Add to cart button states
			if (isloggedin() && is_enrolled(context_course::instance($product->get_course_id(), MUST_EXIST))) {

							// Display 'enrolled' button
				$html .= sprintf(
					'<div class="product-single__form">
					<button class="product-form__add button--enrolled" disabled="disabled">%s</button>
				</div>',
				get_string('button_enrolled_label', 'local_buykart')
				);

			} else if ( $cart->check($product->get_id()) ) {
							//print_r($cart->check($product->get_id()));
							// Display 'in cart' button
				$html .= sprintf(
					'<div class="product-single__form">
					<a href="%s" class="product-form__add btn button--cart btn btn-primary">%s</a>
				</div>',
				new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php'),
				get_string('button_in_cart_label', 'local_buykart')
				);

			} else {

							// Check whether this is a simple or variable product
				if($product->get_type() === PRODUCT_TYPE_SIMPLE) {

								// Display simple product 'add to cart' form
					$html .= sprintf(
						'<form action="%s" method="POST" class="product-single__form">
						<input type="hidden" name="action" value="addToCart">
						<input type="hidden" name="id" value="%d">
						<input type="submit" class="product-form__add btn btn-primary" value="%s">
					</form>',
					new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php'),
					$product->get_id(),
					get_string('button_add_label', 'local_buykart')
					);

				} else {

								// Variable product selection 'add to cart' form
					$html .= sprintf(
						'<form action="%s" method="POST" class="product-single__form">
						<input type="hidden" name="action" value="addVariationToCart">
						<select class="product-tier" name="variation">',
							new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php')
							);

								// output variations
					foreach($product->get_variations() as $variation) {

						$html .= sprintf(
							'<option value="%d">%s</option>',
							$variation->get_id(),
							$variation->get_name()
							);

					}

								// output rest of the form
					$html .= sprintf(
						'	</select>
						<input type="hidden" name="id" value="%d">
						<input type="submit" class="product-form__add btn btn-primary" value="%s">
					</form>',
					$product->get_id(),
					get_string('button_add_label', 'local_buykart')
					);

				}
			}


					// close additional product details wrapper
			$html .= '</div>';

				// close product details wrapper
			$html .= '</div>';

			// close product single wrapper
			$html .= '</div>';

			return $html;
		}


		/**
		 * Outputs the HTML to display related products given a product
		 * @param  product 	$product  	the product for which to find the related ones
		 * @return string           	the HTML output
		 */
		function related_products($product) {
			global $CFG;

			// Require buykart lib
			require_once $CFG->dirroot . '/local/buykart/lib.php';

			$html = '';
			$iterator = 0;


			// Get products related to the product passed to us
			$products = $product->get_related();

			// We only output anything if there ARE related products
			if (is_array($products) && 0 < count($products)) {

				// Output section wrapper
				$html .= '<div class="related-products">';

					// Show the section title
				$html .= sprintf(
					'<h2 class="related-products__title">%s</h2>',
					get_string('product_related_label', 'local_buykart')
					);

					// Output container to hold product items
				$html .= '<ul class="grid-container">';

				foreach ($products as $p) {

					$html .='<div class="card" style="width: 20rem;">
					'.$this->product_image($p).'
					<div class="card-block">
						<h4 class="card-title">'.$p->get_fullname().'</h4>
						<p class="card-text">'.$p->get_description().'</p>
						<a href="#" class="btn btn-primary">Go somewhere</a>
					</div>
				</div>';

				$html .= '<li class="grid-item">';

							// Product image
				$html .= $this->product_image($p);

							// Product title
				$html .= sprintf(
					'<h5>%s</h5>',
					$p->get_fullname()
					);

							// Product link
				$html .= sprintf(
					'<a href="%s" class="product-view btn">%s</a>',
					new moodle_url($CFG->wwwroot . '/local/buykart/pages/product.php', array('id' => $p->get_id()) ),
					get_string('product_related_button_label', 'local_buykart')
					);

				$html .= '</li>';

						// Iterator limits only 3 products to be shown
				$iterator++;
				if ($iterator > 2) {
					break;
				}
			}

					// Close item container
			$html .= '</ul>';

				// Close section wrapper
			$html .= '</div>';
		}

		return $html;
	}


	/**
		 * Returns the HTML for the product image
		 * @param  product 	$product 	the product for the image to be retrieved
		 * @return string          		the HTML output
		 */
	function product_image($product) {
		global $CFG;

			// Require buykart lib
		require_once $CFG->dirroot . '/local/buykart/lib.php';

		$html = '';
		$imageURL = $product->get_image_url();

		if ( !!$imageURL ) {
			$html = sprintf(
				'<img src="%s" alt="%s" class="product-image">',
				$imageURL,
				$product->get_fullname()
				);
		}

		return $html;
	}

	function product_title($product) {
		global $CFG;

		$html = sprintf('<h3 class="product-title">');

		if (!!get_config('local_buykart', 'page_product_enable')) {
			$html .= sprintf(
				'<a href="%s">%s</a>',
				new moodle_url($CFG->wwwroot . '/local/buykart/pages/product.php', array('id' => $product->get_id())),
				$product->get_fullname()
				);
		} else {
			$html .= $product->get_fullname();
		}

		$html .= sprintf('</h3>');

		return $html;
	}

	function product_duration($product) {

			// Product duration
		$html = '<div class="product-duration__wrapper">';

				// Product duration label
		$html .= sprintf(
			'<span class="product-duration__label">%s</span> ',
			get_string('catalogue_enrolment_duration_label', 'local_buykart')
			);

		if( $product->get_type() === PRODUCT_TYPE_SIMPLE) {
			$html .= sprintf(
				'<span class="product-duration">%s</span>',
				$product->get_duration()
				);
		} else {
			$attr = '';

			foreach ($product->get_variations() as $v) {
				$attr .= sprintf(
					'data-tier-%d="%s" ',
					$v->get_id(),
					$v->get_duration()
					);
			}

			list($firstVariation) = array_values($product->get_variations());

			$html .= sprintf(
				'<span class="product-duration" %s>%s</span>',
				$attr,
				$firstVariation->get_duration()
				);
		}

			// Product duration wrapper close
		$html .= sprintf('</div>');

		return $html;
	}

	function product_category($product) {
		global $CFG, $DB;

			// Get the category the product belongs to
		$category = $DB->get_record(
			'course_categories',
			array(
				'id' => $product->get_category_id()
				)
			);

			// Get the url to link to the category page with the filter active
		$categoryURL = new moodle_url(
			$CFG->wwwroot . '/local/buykart/pages/catalogue.php',
			array(
				'category' => $product->get_category_id()
				)
			);

			// Category wrapper
		$html = '<div class="product-category__wrapper">';

				// Category label
		$html .= sprintf(
			'<span class="product-category__label">%s</span> ',
			get_string('course_list_category_label', 'local_buykart')
			);

				// Category link
		$html .= sprintf(
			'<a class="product-category__link" href="%s">%s</a>',
			$categoryURL,
			$category->name
			);

		$html .= '</div>';

		return $html;
	}

	function product_button($product) {
		global $CFG,$DB,$USER;
		$html = '';
		$cart = new buykartCart();
		if (isloggedin() && is_enrolled(context_course::instance($product->get_course_id(), MUST_EXIST))) {
			$html = sprintf(
				'<div class="product-form"><button class="product-form__add button--enrolled" disabled="disabled">%s</button></div>',
				get_string('button_enrolled_label', 'local_buykart')
				);
		}else if($DB->record_exists('local_buykart_incart', array('productid'=>$product->get_id(),'userid'=>$USER->id))){
				//if($cart->check($product->get_id())){
			$html = sprintf(
				'<div class="product-single__form">
				<a href="%s" class="product-form__add button--cart btn btn-primary">%s</a>
			</div>',
			new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php'),
			get_string('button_in_cart_label', 'local_buykart')
			);
				//}

		}else {

				// Check whether this is a simple or variable product
			if($product->get_type() === PRODUCT_TYPE_SIMPLE) {

					// Display simple product 'add to cart' form
				$html .= sprintf(
					'<form action="%s" method="POST" class="product-form">
					<input type="hidden" name="action" value="addToCart">
					<input type="hidden" name="id" value="%d">
					<input type="submit" class="product-form__add btn btn-primary" value="%s">
				</form>',
				new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php'),
				$product->get_id(),
				get_string('button_add_label', 'local_buykart')
				);

			} else {

					// Variable product selection 'add to cart' form
				$html .= sprintf(
					'<form action="%s" method="POST" class="product-form">
					<input type="hidden" name="action" value="addVariationToCart">
					<select class="product-tier" name="variation">',
						new moodle_url($CFG->wwwroot . '/local/buykart/pages/cart.php')
						);

					// output variations
				foreach($product->get_variations() as $variation) {

					$html .= sprintf(
						'<option value="%d">%s</option>',
						$variation->get_id(),
						$variation->get_name()
						);

				}

					// output rest of the form
				$html .= sprintf(
					'	</select>
					<input type="hidden" name="id" value="%d">
					<input type="submit" class="product-form__add btn btn-primary" value="%s">
				</form>',
				$product->get_id(),
				get_string('button_add_label', 'local_buykart')
				);

			}
		}

		return $html;
	}

/**
		 * Outputs the HTML for the pagination
		 * @param  array  $products    An array of the products to be paginated
		 * @param  integer $currentPage The index of the current page
		 * @param  int  $category    the category ID
		 * @param  string  $sort        the string sorting parameter
		 * @return string               the HTML output
		 */
function pagination($products, $currentPage = 0, $category = null, $sort = null) {
	global $CFG;

	$html = '';

			// Calculate total page count
	$pageCount = ceil(count($products) / get_config('local_buykart', 'pagination'));

			// Only output pagination when there is more than one page
	if (1 < $pageCount && $currentPage <= $pageCount ) {

		$html .= sprintf('<div class="pagination-bar"><ul class="pagination">');

		$params = array();

		if ($sort !== null) {
			$params['sort'] = $sort;
		}

		if ($category !== null && !!$category ) {
			$params['category'] = $category;
		}

		for ($paginator = 1; $paginator <= $pageCount; $paginator++) {
			$params['page'] = $paginator;

			$html .= sprintf('<li class="page-item"><a href="%s" %s>%d</a></li>',
				new moodle_url($CFG->wwwroot . '/local/buykart/pages/catalogue.php', $params),
				$paginator === $currentPage ? 'class="active"' : '',
				$paginator
				);

		}

		$html .= sprintf('</ul></div>');
	}

	return $html;
}
/*
* Returns the HTML for the buykart cart
* @param 	array 		cart
* @param 	bool 		is it the checkout page
* @return 	string 		the HTML output
		*/
function buykart_cart($cart) {
	global $CFG, $USER;

			// Require buykart lib
	require_once $CFG->dirroot . '/local/buykart/lib.php';

			// Initialise vars
	$html = '';
	$html .= '<div class="cart-overview">';

	if ( $cart->is_empty() === false ) {
				//$html .= '<ul class="products">';
				// Go through each product in the cart
		foreach ($cart->get() as $pID => $vID) {
			$product = local_buykart_get_product($pID);
					//print_object($product);;
					//$html .='<div class="col-md-12" style="border-style: solid;">';//normal
			$html .= html_writer::start_tag('div',array('class'=>'card'));//row
			$html .= html_writer::start_tag('div',array('class'=>'card-body'));//col-md-12
					//$html .= html_writer::start_tag('div',array('class'=>'col-md-12'));//col-md-8
			//$html .= html_writer::start_tag('div',array('class'=>'col-md-2'));//col-md-3
					//image code here 
			if (!!get_config('local_buykart', 'page_catalogue_show_image')) {
				$html .= $this->product_image($product);
			} 
					//$html .= '<img  class="img-responsive" src="'.$CFG->wwwroot.'/theme/boost/demo.png" alt="Avatar" style="width:100px;height:100px;">';
			//$html .=html_writer::end_tag('div');//end col-md-2
			$html .= html_writer::start_tag('div',array('class'=>'col-md-7'));//col-md-8
					//title
			$html .= sprintf(
				'<h4 class="card-title"><i class="ti-shopping-cart-full" style="font-size: 25px;padding-right: 10px;"></i><a href="%s">%s</a></h4>',
				new moodle_url($CFG->wwwroot . '/local/buykart/pages/product.php', array( 'id' => $product->get_id() )),
				$product->get_type() === PRODUCT_TYPE_SIMPLE ? $product->get_fullname() : $product->get_fullname() . ' - ' . $product->get_variation($vID)->get_name()
				);
					//dummy text here for test
					// $html .= '<p class="product_summary">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p>';
					//real text here 
			if($product->get_description()){
				$html .= sprintf(
					'<p class="card-text">%s</p>',
					$product->get_description()
					);
			}
			$html .=html_writer::end_tag('div');//end col-md-7
					//$html .=html_writer::end_tag('div');//end col-md-10
					//$html .= html_writer::start_tag('div',array('class'=>'col-md-2 cardb'));//
			$html .='<div class="row" style="padding:15px;">';//normal
			$html .= html_writer::start_tag('div',array('class'=>'col-md-3'));//
			$html .= '';
			$html .= sprintf(
				'<button class="btn btn-info waves-effect waves-light" type="button"><span class="btn-label">'.
				local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')).'</i></span>%s%.02f</button>','',
				$product->get_type() === PRODUCT_TYPE_SIMPLE ? $product->get_price() : $product->get_variation($vID)->get_price()
				);
			//$html .= html_writer::end_tag('div');//col-md-1

			//$html .= html_writer::start_tag('div',array('class'=>'col-md-1 offset-md-2'));//
			$html .= sprintf(
				'<form class="product__form" action="" method="POST">
				<input type="hidden" name="id" value="%d">
				<input type="hidden" name="action" value="removeFromCart">
				<input class="btn btn-danger tocheckout" type="submit" value="%s">
			</form>',
			$product->get_id(),
			get_string('button_remove_label', 'local_buykart')
			);
			$html .= html_writer::end_tag('div');//col-md-1

		$html .='</div>';
					//$html .= html_writer::end_tag('div');//col-md-2
		$html .=html_writer::end_tag('div');//end col-md-12
		$html .=html_writer::end_tag('div');//end row
					//$html .='</div>';//end normal
	}

				// Output cart summary section
	$html .= '<div class="cart-summary">';

					// Cart total price
	$html .= sprintf(
		'<h3 class="cart-total__label">%s</h3><h3 class="cart-total">%s%0.2f</h3>',
		get_string('cart_total', 'local_buykart'),
		local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')),
		$cart->get_total()
		);


	$html .= '</div>';
	$html .='<hr>';

				// Get the cart action HTML
	$html .= $this->cart_actions();

} else {

				// Empty cart message
	$html .= sprintf(
		'<p class="cart-mesage--empty">%s</p>',
		get_string('cart_empty_message', 'local_buykart')
		);

				// Return to store button
	$html .= $this->return_to_store_action();

}

$html .= '</div>';

return $html;
}

/**
* Returns the HTML for the buykart cart review on the checkout page
* @param 	array 		cart
* @param 	bool 		is it the checkout page
* @return 	string 		the HTML output
		*/
function cart_review($cart, $removedProducts = array()) {
	global $CFG, $USER;

			// Require buykart lib
	require_once $CFG->dirroot . '/local/buykart/lib.php';

			// OPEN cart-overview
	$html = '<div class="cart-overview">';

			// Output cart review message
	$html .= sprintf(
		'<p class="cart-review__message">%s</p>',
		get_string('checkout_message', 'local_buykart')
		);

	if (!!$removedProducts && is_array($removedProducts)) {

		$html .= '<div class="cart-review__wrapper">';

		$html .= sprintf(
			'<p class="cart-review__message--removed">%s</p>', 
			get_string('checkout_removed_courses_label', 'local_buykart')
			);

		$html .= '<ul>';

		foreach ($removedProducts as $p) {
			$thisProduct = local_buykart_get_product($p);

			$html .= sprintf(
				'<li class="cart-review__item--removed">%s</li>', 
				$thisProduct->get_fullname()
				);
		}

		$html .= '</ul></div>';

	}


	if ( $cart->is_empty() === false ) {

		$html .= '<ul class="products">';

				// Go through each product in the cart
		foreach ($cart->get() as $pID => $vID) {

			$product = local_buykart_get_product($pID);

			$html .= '<li class="product-item">';

						// Product title and variation
			$html .= sprintf(
				'<h4 class="product-title"><a href="%s">%s</a></h4>',
				new moodle_url($CFG->wwwroot . '/local/buykart/pages/product.php', array( 'id' => $product->get_id() )),
				$product->get_type() === PRODUCT_TYPE_SIMPLE ? $product->get_fullname() : $product->get_fullname() . ' - ' . $product->get_variation($vID)->get_name()
				);

						// Product price
			$html .= sprintf(
				'<div class="product-price">%s%.02f</div>',
				local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')),
				$product->get_type() === PRODUCT_TYPE_SIMPLE ? $product->get_price() : $product->get_variation($vID)->get_price()
				);

			$html .= '</li>';
		}

		$html .= '</ul>';

				// Output cart summary section
		$html .= '<div class="cart-summary">';

					// Cart total price
		$html .= sprintf(
			'<h3 class="cart-total__label">%s</h3><h3 class="cart-total">%s%0.2f</h3>',
			get_string('cart_total', 'local_buykart'),
			local_buykart_get_currency_symbol(get_config('local_buykart', 'currency')),
			$cart->get_total()
			);

		$html .= '</div>';

				// Return to store button
		$html .= $this->return_to_store_action();
				// Render all active Gateway options
				//for button new renderer code is added by prashant 
		$html .= '<br><br>';
		$html .= '<hr>';
		$html .= '<div class="col-md-12">';
		$html .= '<div class="col-md-2">';
		if( !!get_config('local_buykart', 'payment_paypal_enable') ){
			$gatewayPaypal = new BuykartGatewayPaypal($cart->get_transaction_id());
			$html .= $gatewayPaypal->render();
		}
		$html .= '</div>';
		$html .= '<div class="col-md-2">';
		if( !!get_config('local_buykart', 'payment_payumoney_enable') ){
			$gatewayPayumoney = new BuykartGatewayPayumoney($cart->get_transaction_id());
			$html .= $gatewayPayumoney->render();
		}
		$html .= '</div>';

		$html .= '<div class="col-md-2">';
		if( !!get_config('local_buykart', 'payment_ebs_enable') ){
			$gatewayEbs = new BuykartGatewayEbs($cart->get_transaction_id());
			$html .= $gatewayEbs->render();
		}
		$html .= '</div>';

		$html .= '<div class="col-md-2">';
				//paytm getway button is added by Prashant
		if( !!get_config('local_buykart', 'payment_paytm_enable') ){
			$gatewayPaytm = new BuykartGatewayPaytm($cart->get_transaction_id());
			$html .= $gatewayPaytm->render();
		}
		$html .= '</div>';

				//instamojo getway is adding here button is added here
		$html .= '<div class="col-md-2">';
		if( !!get_config('local_buykart', 'payment_instamojo_enable') ){
			$gatewayInstamojo = new BuykartGatewayInstamojo($cart->get_transaction_id());
			$html .= $gatewayInstamojo->render();
		} 
		$html .= '</div>';

				//stripe getway  button is added by Prashant
		$html .= '<div class="col-md-2">';
		if( !!get_config('local_buykart', 'payment_stripe_enable') ){
			$gatewayStripe = new BuykartGatewayStripe($cart->get_transaction_id());
			$html .= $gatewayStripe->render();
		}
		$html .= '</div>';
		$html .= '</div>';

	} else {

				// Empty cart message
		$html .= sprintf(
			'<p class="cart-mesage--empty">%s</p>',
			get_string('cart_empty_message', 'local_buykart')
			);

				// Return to store button
		$html .= $this->return_to_store_action();

	}

			// CLOSE cart-overview
	$html .= '</div>';

	return $html;
}	


/**
		 * Returns the HTML output for the catalogue page filter bar
		 * @param  int 		$c 		The catalogue ID
		 * @param  string 	$s  	The sorting string
		 * @return string   		HTML
		 */
function filter_bar($c = null, $s= null) {

	/*$filters = array(
		'default-asc',
		'fullname-asc',
		'fullname-desc',
		'price-asc',
		'price-desc',
		'duration-asc',
		'duration-desc',
		);

			// open form wrapper
	$html = '<form action="" method="GET" class="filter-bar">';

				// Render category filter
	$html .= sprintf(
		'<div class="filter__category">
		<span>%s</span>
		<select name="category" id="category">
			%s
		</select>
	</div>',
	get_string('filter_category_label', 'local_buykart'),
	local_buykart_get_category_list($c)
	);

				// Render sorting filter
	$html .= sprintf(
		'<div class="filter__sort">
		<span>%s</span>
		<select name="sort" id="sort">',
			get_string('filter_sort_label', 'local_buykart')
			);

					// Output all options for filters
	foreach ($filters as $f) {

						// Category option
		$html .= sprintf(
			'<option value="%s" %s>%s</option>',
			$f,
			$s === $f ? 'selected="selected"' : '',
			get_string('filter_sort_' . str_replace('-', '_', $f), 'local_buykart')
			);

	}

				// close sort filter
	$html .= '</select></div>';

			// close filter-bar form
	$html .= '</form>';*/
	$html = '<div class="fc-right">
	        	<div class="fc-button-group">
	        		 <a href="#" id="list" style="padding:1px" class="nav-link waves-effect">
		            	<i class="mdi mdi-format-list-bulleted"></i>
		            	List
		            </a> 
	        		<a href="#" id="grid" style="padding:1px" class="nav-link waves-effect">
		            	<i class="mdi mdi-view-grid"></i>
		            	Grid
		            </a>
	        	</div>
	        </div>';
	return $html;

}


/**
		 * Returns the HTML output for the standard cart actions
		 * @return string  	HTML
		 */	
function cart_actions() {
	global $CFG;
	$html = '';

	$html .= html_writer::start_tag('div',array('class'=>'row'));
	$html .= html_writer::start_tag('div',array('class'=>'col-md-12'));
		$html .= html_writer::start_tag('div',array('class'=>''));
			//$html .= html_writer::start_tag('div',array('class'=>'col-md-4'));
				//return to store button icon here 
				$html .= $this->return_to_store_action();
			//$html .= html_writer::end_tag('div');//end col-md-4
			//$html .= html_writer::start_tag('div',array('class'=>'col-md-2'));
			//empty button code here
			$html .= sprintf(
				'<form action="" method="POST" class="back-to-shop">
				<input type="hidden" name="id" value="%d">
				<input type="hidden" name="action" value="removeallFromCart">
				<input class="form__submit btn btn-warning" type="submit" value="%s">
			</form>',
			0,
			get_string('button_removeall_label', 'local_buykart')
			); 
			$html .= sprintf(
						'<form action="%s" method="GET" class="go-to-checkout">
						<input type="submit" class="btn btn-primary" value="%s">
					</form>',
					new moodle_url($CFG->wwwroot . '/local/buykart/pages/checkout.php'),
					get_string('button_checkout_label', 'local_buykart')
					);
			//$html .= html_writer::end_tag('div');//end col-md-2
		$html .= html_writer::end_tag('div');//end col-md-6
$html .= html_writer::end_tag('div');//end col-md-12
$html .= html_writer::end_tag('div');//end row

return $html;
}


/**
		 * Returns the HTML output for the return to store button
		 * @return string 	HTML
		 */	
function return_to_store_action() {
	global $CFG;

			// Return to store button
	return sprintf(
		'<form action="%s" method="GET" class="back-to-shop">
		<input type="submit" class="btn btn-info" value="%s">
	</form>',
	new moodle_url($CFG->wwwroot . '/local/buykart/pages/catalogue.php'),
	get_string('button_return_store_label', 'local_buykart')
	);
}
}
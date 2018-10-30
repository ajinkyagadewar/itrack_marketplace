<?php
/**
 *MarketPlace Outside iTrack Core Pages
 *
 * @package     iTrack Marketplace
 * @subpackage  local_buykart
 * @author   	Arjun Singh
 * @copyright   2018 
 * @license     http://www.transneuron.com 2018 or later
 */

require_once dirname(__FILE__) . '/../config.php';
require_once $CFG->dirroot . '/local/buykart/lib.php';

$categoryID = optional_param('category', null, PARAM_INT);
$sort = optional_param('sort', null, PARAM_TEXT);
$page = optional_param('page', 1, PARAM_INT);

require_login();
global $CFG,$DB,$USER,$PAGE;
$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);
$PAGE->set_url($CFG->wwwroot . '/marketplace/index.php');
$renderer = $PAGE->get_renderer('local_buykart');
list($sortfield, $sortorder) = local_buykart_extract_sort_vars($sort);
// Get the products for this page
$products = local_buykart_get_products($page, $categoryID, $sortfield, $sortorder);
//print_object($products);
// Outputs this page of products
//echo $renderer->catalogue($products);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>iTrack | Marketplace</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <?php       
    echo '<link rel="icon" type="image/png" sizes="16x16" href="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/favicon.png">';
    ?>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->

<body>
	<header id="header"><!--header-->
		<div class="header_top"><!--header_top-->

			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="contactinfo">
							<ul class="nav nav-pills">
								<li><a href="#"><i class="fa fa-phone"></i> 080 4920 2211</a></li>
								<li><a href="mailto:info@itrackglobal.com"><i class="fa fa-envelope"></i> info@itrackglobal.com</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="social-icons pull-right">
							<ul class="nav navbar-nav">
								<li><a href="https://www.facebook.com/itrackglobal/?fref=ts"><i class="fa fa-facebook"></i></a></li>
								<li><a href="https://twitter.com/itrackglobal"><i class="fa fa-twitter"></i></a></li>
								<li><a href="https://www.linkedin.com/company/itrackglobal/"><i class="fa fa-linkedin"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header_top-->
		
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-md-4 clearfix">
						<div class="logo pull-left">

							<?php
							echo '<a href="'.$CFG->wwwroot.'/my">
									<img src="https://cloud.githubusercontent.com/assets/4004313/7087582/81986412-df95-11e4-9516-eab032376b86.jpg" alt="fa-shop" style="width:22%;">									<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
									
								</a><span class="marketplace_logo">| MarketPlace</span>';
							?>

						</div>
						<!-- <div class="btn-group pull-right clearfix">
							<div class="btn-group">
								<button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
									USA
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="">Canada</a></li>
									<li><a href="">UK</a></li>
								</ul>
							</div>
							
							<div class="btn-group">
								<button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
									DOLLAR
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="">Canadian Dollar</a></li>
									<li><a href="">Pound</a></li>
								</ul>
							</div>
						</div> -->
					</div>
					<div class="col-md-8 clearfix">
						<div class="shop-menu clearfix pull-right">
							<ul class="nav navbar-nav">
								<?php
									$accountlink = $CFG->wwwroot.'/user/profile.php?id='.$USER->id;
									echo '<li><a href=""><i class="fa fa-star"></i> Wishlist</a></li>
											<li><a href="checkout.html"><i class="fa fa-crosshairs"></i> Checkout</a></li>
											<li><a href="cart.html"><i class="fa fa-shopping-cart"></i> Cart</a></li>
											<li><a href="'.$accountlink.'"><i class="fa fa-user"></i> Account</a></li>';
								?>							
								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	<section>
		<div class="container bg-overlay">
			<div class="row text-center">
				<h1>Personal Education, Extraordinary Success</h1>
		        <p>Sophisticated yet simple, easy-to-use interface & self-explanatory platform </p>
		        <br><br>
		        <button type="button" class="btn btn-primary btn-lg btn-browse">Browsing All Courses</button>
			</div>
			<div class="homehightlights">
		        <div class="container">
		            <ul>
		                <li>
		                    <span><i class="icon-live-instructor"></i>Live Instructor-led Classes</span>
		                </li>
		                <li><span><i class="icon-Expert-educators"></i>Expert Educators</span></li>
		                <li><span><i class="icon-x7-support2"></i>24X7 Support</span></li>
		                <li><span><i class="icon-calender"></i>Flexible Schedule</span></li>
		            </ul>
		        </div>
		    </div>
		</div>
		<div class="container homecoursesmain">
			<div class="row">
				
				<div class="col-sm-12 padding-right">
					
					<h2 class="trending-crs">Trending Courses</h2>
					<div class="category-tab"><!--category-tab-->
						<div class="col-sm-12">
							<ul class="nav nav-tabs taball">
								<li class="active"><a href="#tshirt" data-toggle="tab">All</a></li>

								<!-- <li><a href="#blazers" data-toggle="tab">Blazers</a></li>
								<li><a href="#sunglass" data-toggle="tab">Sunglass</a></li>
								<li><a href="#kids" data-toggle="tab">Kids</a></li>
								<li><a href="#poloshirt" data-toggle="tab">Polo shirt</a></li> -->
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade active in" id="tshirt" >
								<?php
								foreach ($products as $product) {
									$courseid =  $product->get_course_id();
									$cssetting = $DB->get_record('course', array('id' => $courseid));
									$exsetting = $DB->get_records('course_extrasettings_general', array('courseid' => $cssetting->id));
        							foreach ($exsetting as $allsetting) {
										//$courseimg = csetting_images($allsetting);
										$fs = get_file_storage();
									    $files = $fs->get_area_files($allsetting->contextid, 'local_course_extrasettings', 'content', $allsetting->courseimage, 'id', false);

									    foreach ($files as $file) {
									        $filename = $file->get_filename();

									        if (!$filename <> '.') {
									            $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $allsetting->courseimage, $file->get_filepath(), $filename);
									            //print_object($url);
									        }
									    }
									}
									/*//image code here 
									if (!!get_config('local_buykart', 'page_catalogue_show_image')) {
										
										$imageURL = $product->get_image_url();
									}*/
									//print_object($product);
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
									echo '<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
												<div class="productinfo text-center">
													<img src="'.$url.'" alt="">
													<div class="coursedetails">
											            <h3 class="coursetitle" title="'.$product->get_fullname().'">'.$product->get_fullname().'</h3>
											            <ul class="highlights hidden-xs">
											                <li>
											                    <i class="fa fa-sign-in"></i>
											                    <span> 2k Enrolled Learners </span>
											                </li>
											                <li>
											                    <i class="fa fa-calendar"></i>
											                    <span> Weekend/Weekday</span>
											                </li>
											                <li>
											                    <i class="fa fa-video-camera"></i>
											                    <span> Live Class</span>
											                </li>
											            </ul>
											            <span class="reviewstxt hidden-xs">Reviews</span>
											            <div class="reviewicons">
											                <div class="star">
																<div class="rating" style="width:80%">
																	 <span>&#9734;</span>
																	<span>&#9734;</span>
																	<span>&#9734;</span>
																	<span>&#9734;</span>
																	<span>&#9734;</span> 
															 	</div>
															</div>               
											                <span class="rating">5</span>
											                <span class="totalreviews">(250)</span>
											            </div>
											            <div class="courseprsec">
											                <span class="actualpr"> '.$price.'</span>
									                        
											            </div>
											        </div>
												</div>

												<div class="product-overlay">

												        <a href="'.$CFG->wwwroot.'/marketplace/product_details.php?id='.$courseid.'" class="hoverfeatures hidden-xs ga-trending-card ga-trending-hoverfeatures trackButton" data-button-name="AWS Architect Certification Training-trending" data-button-location="2nd fold navigation" data-button-type="Dynamic" data-course="AWS Architect Certification Training" title="AWS Architect Certification Training" tabindex="0">
												            <span class="comprate">
												            <span class="value">85% </span>
												            <span class="text">- Course completion rate</span>
												                <img src="https://d1jnx9ba8s6j9r.cloudfront.net/imgver.1540472634/img/green-tick.svg" data-src="https://d1jnx9ba8s6j9r.cloudfront.net/imgver.1540472634/img/green-tick.svg" alt="green tick online training">        
												            </span>       
												            '.$product->get_description().'
												            <span class="viewdetailbtn">View Details</span>
												        </a>
												</div>
										</div>
										<div class="choose">
											<ul class="nav nav-pills nav-justified">
												<li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
												<li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
											</ul>
										</div>
									</div>
								</div>';

								}
								?>
								
								
								
							</div>
							
							<div class="tab-pane fade" id="blazers" >
								<?php
								echo '<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/user-info.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>';
								?>
							</div>
							
							<div class="tab-pane fade" id="sunglass" >
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery4.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade" id="kids" >
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery4.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade" id="poloshirt" >
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery4.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div><!--/category-tab-->
					<?php

					?>
					<div class="recommended_items"><!--recommended_items-->
						<h2 class="title text-center">recommended courses</h2>
						
						<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">
								<div class="item active">	
									<div class="col-sm-3">
										<div class="product-image-wrapper">
											<div class="single-products">
													<div class="productinfo text-center">
														<?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/user-info.jpg" alt="" />';?>
														<div class="coursedetails">
												            <h3 class="coursetitle" title="">MapReduce Design Patterns</h3>
												            <ul class="highlights hidden-xs">
												                <li>
												                    <i class="fa fa-sign-in"></i>
												                    <span> 2.2k Enrolled Learners </span>
												                </li>
												                <li>
												                    <i class="fa fa-calendar"></i>
												                    <span> Weekend/Weekday</span>
												                </li>
												                <li>
												                    <i class="fa fa-video-camera"></i>
												                    <span> Live Class</span>
												                </li>
												            </ul>
												            <div class="courseprsec">
												                <span class="actualpr"><i class="fa fa-inr"> </i> 850 </span>
										                        
												            </div>
												        </div>
													</div>
											</div>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="product-image-wrapper">
											<div class="single-products">
													<div class="productinfo text-center">
														<?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/user-info.jpg" alt="" />';?>
														<div class="coursedetails">
												            <h3 class="coursetitle" title="">Apache Storm</h3>
												            <ul class="highlights hidden-xs">
												                <li>
												                    <i class="fa fa-sign-in"></i>
												                    <span> 1k Enrolled Learners </span>
												                </li>
												                <li>
												                    <i class="fa fa-calendar"></i>
												                    <span> Weekend/Weekday</span>
												                </li>
												                <li>
												                    <i class="fa fa-video-camera"></i>
												                    <span> Live Class</span>
												                </li>
												            </ul>
												            <div class="courseprsec">
												                <span class="actualpr"><i class="fa fa-inr"> </i> 790 </span>
										                        
												            </div>
												        </div>
													</div>
											</div>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="product-image-wrapper">
											<div class="single-products">
													<div class="productinfo text-center">
														<?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/user-info.jpg" alt="" />';?>
														<div class="coursedetails">
												            <h3 class="coursetitle" title="">FuulStack Web Development</h3>
												            <ul class="highlights hidden-xs">
												                <li>
												                    <i class="fa fa-sign-in"></i>
												                    <span> 2.5k Enrolled Learners </span>
												                </li>
												                <li>
												                    <i class="fa fa-calendar"></i>
												                    <span> Weekend/Weekday</span>
												                </li>
												                <li>
												                    <i class="fa fa-video-camera"></i>
												                    <span> Live Class</span>
												                </li>
												            </ul>
												            <div class="courseprsec">
												                <span class="actualpr"><i class="fa fa-inr"> </i> 550 </span>
										                        
												            </div>
												        </div>
													</div>
											</div>
										</div>
									</div>	
									<div class="col-sm-3">
										<div class="product-image-wrapper">
											<div class="single-products">
													<div class="productinfo text-center">
														<?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/user-info.jpg" alt="" />';?>
														<div class="coursedetails">
												            <h3 class="coursetitle" title="">Cloud Computing</h3>
												            <ul class="highlights hidden-xs">
												                <li>
												                    <i class="fa fa-sign-in"></i>
												                    <span> 2k Enrolled Learners </span>
												                </li>
												                <li>
												                    <i class="fa fa-calendar"></i>
												                    <span> Weekend/Weekday</span>
												                </li>
												                <li>
												                    <i class="fa fa-video-camera"></i>
												                    <span> Live Class</span>
												                </li>
												            </ul>
												            <div class="courseprsec">
												                <span class="actualpr"><i class="fa fa-inr"> </i> 330 </span>
										                        
												            </div>
												        </div>
													</div>
											</div>
										</div>
									</div>								
								</div>
								<div class="item">	
									<div class="col-sm-3">
										<div class="product-image-wrapper">
											<div class="single-products">
													<div class="productinfo text-center">
														<?php echo '<img src="'.$CFG->wwwroot.'/theme/itrackglobal/assets/images/background/user-info.jpg" alt="" />';?>
														<div class="coursedetails">
												            <h3 class="coursetitle" title="">Business Intelligence</h3>
												            <ul class="highlights hidden-xs">
												                <li>
												                    <i class="fa fa-sign-in"></i>
												                    <span> 4k Enrolled Learners </span>
												                </li>
												                <li>
												                    <i class="fa fa-calendar"></i>
												                    <span> Weekend/Weekday</span>
												                </li>
												                <li>
												                    <i class="fa fa-video-camera"></i>
												                    <span> Live Class</span>
												                </li>
												            </ul>
												            <div class="courseprsec">
												                <span class="actualpr"><i class="fa fa-inr"> </i> 33 </span>
										                        
												            </div>
												        </div>
													</div>
											</div>
										</div>
									</div>
								</div>
								

							</div>
							 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
								<i class="fa fa-angle-left"></i>
							  </a>
							  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
								<i class="fa fa-angle-right"></i>
							  </a>			
						</div>
					</div><!--/recommended_items-->
					
				</div>
			</div>
		</div>
	</section>
	
	<footer id="footer"><!--Footer-->
		
		<div class="footer-widget">
			<div class="container">
				<div class="row">
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Service</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Online Help</a></li>
								<li><a href="#">Contact Us</a></li>
								<li><a href="#">Order Status</a></li>
								<li><a href="#">Change Location</a></li>
								<li><a href="#">FAQ’s</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Quick Look</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Courses</a></li>
								<li><a href="#">Coupons</a></li>
								<li><a href="#">Discount</a></li>
								<li><a href="#">Offers</a></li>
								<li><a href="#">Others</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Policies</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Terms of Use</a></li>
								<li><a href="#">Privecy Policy</a></li>
								<li><a href="#">Refund Policy</a></li>
								<li><a href="#">Billing System</a></li>
								<li><a href="#">Ticket System</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>About iTrack</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Company Information</a></li>
								<li><a href="#">Careers</a></li>
								<li><a href="#">Location</a></li>
								<li><a href="#">Affillate Program</a></li>
								<li><a href="#">Copyright</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3 col-sm-offset-1">
						<div class="single-widget">
							<h2>Subscribe</h2>
							<form action="#" class="searchform">
								<input type="text" placeholder="Your email address" />
								<button type="submit" class="btn btn-default"><i class="fa fa-arrow-circle-o-right"></i></button>
								<p>Get the most recent updates from <br />our site and be updated your self...</p>
							</form>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-right">Copyright © 2018 <span><a target="_blank" href="https://transneuron.com/">Transneuron Technologies Pvt Ltd.</a> All rights reserved.</span></p>
				</div>
			</div>
		</div>
		
	</footer><!--/Footer-->
	

  
    <script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/price-range.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
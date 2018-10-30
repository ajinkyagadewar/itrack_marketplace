<?php
/**
 * buykart Catalogue Page
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	Thomas Threadgold
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once dirname(__FILE__) . '/../../../config.php';
require_once $CFG->dirroot . '/local/buykart/lib.php';


$categoryID = optional_param('category', null, PARAM_INT);
$sort = optional_param('sort', null, PARAM_TEXT);
$page = optional_param('page', 1, PARAM_INT);

$systemcontext = context_system::instance();

$PAGE->set_context($systemcontext);
$PAGE->set_url($CFG->wwwroot . '/local/buykart/pages/catalogue.php');
$PAGE->requires->css(new 
	moodle_url($CFG->wwwroot.'/local/buykart/css/custom.css'));

// Check if the theme has a buykart pagelayout defined, otherwise use standard
if (array_key_exists('buykart_catalogue', $PAGE->theme->layouts)) {
	$PAGE->set_pagelayout('buykart_catalogue');
} else if(array_key_exists('buykart', $PAGE->theme->layouts)) {
	$PAGE->set_pagelayout('buykart');
} else {
	$PAGE->set_pagelayout('custom');
}

$PAGE->set_title(get_string('catalogue_title', 'local_buykart'));
$PAGE->set_heading(get_string('catalogue_title', 'local_buykart'));
echo '<script src="'.$CFG->wwwroot.'/local/buykart/js/jquery.min.js"></script>';
echo '<script src="'.$CFG->wwwroot.'/local/buykart/js/catalogue.js"></script>';
echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/buykart/css/custom.css"></script>';
// Get the renderer for this page
$renderer = $PAGE->get_renderer('local_buykart');

list($sortfield, $sortorder) = local_buykart_extract_sort_vars($sort);

echo $OUTPUT->header();

?>

<!-- <h1 class="page__title"><?php echo get_string('catalogue_title', 'local_buykart'); ?></h1> -->
<div class="card">
    <img class="market-head-image" src="<?php echo $CFG->wwwroot.'/theme/itrackglobal/assets/images/background/weatherbg.jpg';?>" alt="Card image cap">
    <!-- <div class="card-img-overlay" style="height:110px;">
        <h2 class="card-title text-white m-b-0 dl">New Delhi</h2>
        <small class="card-text text-white font-light">Sunday 15 march</small>
    </div> -->
    <div class="card-body weather-small">
        <div class="row">
            <div class="col-12 align-self-center">
                <div class="d-flex">
                    <div class="display-6 text-info"><i class="icon-cloud-download"></i></div>
                    <div class="m-l-20">
                        <h1 class="font-light text-info m-b-0">Marketplace</h1>
                        <small>Get Certified. Get Ahead</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

// Render catalogue filter bar
echo $renderer->filter_bar($categoryID, $sort); 

// Get the products for this page
$products = local_buykart_get_products($page, $categoryID, $sortfield, $sortorder);

// Outputs this page of products
echo $renderer->catalogue($products);

// Get all products matching the filter parameters
$allProducts = local_buykart_get_products(-1, $categoryID, $sortfield, $sortorder);
// Pass them to the pagination function
echo $renderer->pagination($allProducts, $page, $categoryID, $sort);

echo $OUTPUT->footer();

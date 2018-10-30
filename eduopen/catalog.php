<?php
/**
 * Moodle's
 * This file is part of eduopen LMS Product
 *
 * @package   theme_eduopen
 * @copyright http://www.moodleofindia.com
 * @license   This file is copyrighted to Dhruv Infoline Pvt Ltd, http://www.moodleofindia.com
 */
require_once(dirname(__FILE__) . '/../config.php');

redirect_if_major_upgrade_required();

$strcourses = get_string('hmc', 'theme_eduopen');
$header = "$SITE->shortname: $strcourses";
$mode = optional_param('mode', array(), PARAM_TEXT);
// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/catalog.php', $params);
$PAGE->set_pagelayout('allcourse');
$PAGE->blocks->add_region('content');
$PAGE->set_title($header);
$PAGE->set_heading($header);
$PAGE->requires->jquery();
echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');
?>
<!--Custom HTML Starts here$("#id").css("display", "none");-->
<div class="row1" id="coursemain_row">
    <?php
    $return = false;
    if (isset($_POST['search_btn'])) {
        $searchdata = $_POST['search-input'];
        $blockcount = 0;
        $coursedetailsquery = "SELECT id, fullname FROM {course} 
        WHERE fullname Like '%$searchdata%' AND visible = 1 AND category != 0";
        $coursedetailsresultset = $DB->get_records_sql($coursedetailsquery);
        if ($coursedetailsresultset) {
            $return = true;
            foreach ($coursedetailsresultset as $coursedetails) {
                $courseid = $coursedetails->id;
                $courseblock = catalog_featuredcourse_block($courseid);
                if ($courseblock) {
                    $blockcount ++;
                    /* if( ( $blockcount % 2 ) == 0 ){//if two blocks are printed at a time then create a new row
                      echo '<div class="row1">';
                      } */
                    echo $courseblock;
                    /* if( ( $blockcount % 2 ) == 0 ){
                      echo '</div>';
                      } */
                }
            }
            if ($blockcount == 0) {
                echo '<div class="row-fluid center">';
                echo '<div class="col-md-4 alert alert-danger">' . get_string('nocourse', 'theme_eduopen') . '</div>';
                echo '</div>';
            }
        }
    }
    ?>
</div>
<div class="col-md-12 pad30T pad30B getheight">
    <div class="row-fluid">
        <!--        <div class="col-md-4 float-right" id="institutionFilter">
        <?php
//Filter by course categories section
        $csinstituton = $DB->get_records('block_eduopen_master_inst'); //only show the visible categories.
        if ($csinstituton) {
            ?>
                                <select class="form-control input-lg m-bot15" name ="institutions" id="instfilter">
                                    <option value="">Select Institution</option>
            <?php
            foreach ($csinstituton as $institutonname) {
                $intitution_id = $institutonname->id;
                $intitution_name = $institutonname->name;
                //echo '<option class="institute" value="' . $intitution_id . '" />' . $intitution_name . '</option>';
            }
            ?>
                                </select>
        <?php }
        ?>
                </div>-->
   <!--  <div class="row-fluid">
        <div class="col-md-12 browseall">
            <div class="col-md-6 pad7R">
                <div class="square-green single-row float-right">
                    <div class="radio" id="browseallcourses">
                   
                        <button type="button" value="coursefilter" class="catcrs">
                            <label><?php echo ucwords(get_string("browseallcourses", 'theme_eduopen')); ?></label>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pad7L">
                <div class="square-green single-row">
                    <div class="radio" id="browseallpathways">
                       
                        <button type="button" value="pathwayfilter" class="catpath">
                            <label><?php echo ucwords(get_string("browseallpathways", 'theme_eduopen')); ?></label>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div> -->
<!--tab view of course/pathway -->
                <header class="panel-heading tab-bg-dark-navy-blue catalogpagetab">
                    <ul class="nav nav-tabs">
                    <?php if ($mode == 'browsearchive'){ ?>
                        <li class="active" id="coursefilter">
                            <a data-toggle="tab" href="#home"><?php print_string('filterbyarccourses','theme_eduopen'); ?></a>
                        </li>
                        <li class="" id="pathwayfilter">
                            <a data-toggle="tab" href="#about"><?php print_string('filterbyarcpathways','theme_eduopen'); ?></a>
                        </li>
                        <?php }else{ 
                                if ($mode == 'browseallcourses'){ ?>
                                    <li class="active" id="coursefilter">
                                        <a data-toggle="tab" href="#home"><?php print_string('filterbycourses','theme_eduopen'); ?></a>
                                    </li>
                                    <li class="" id="pathwayfilter">
                                        <a data-toggle="tab" href="#about"><?php print_string('filterbypathways','theme_eduopen'); ?></a>
                                    </li>
                                <?php }else{ ?>
                                    <li class="" id="coursefilter">
                                        <a data-toggle="tab" href="#home"><?php print_string('filterbycourses','theme_eduopen'); ?></a>
                                    </li>
                                    <li class="active" id="pathwayfilter">
                                        <a data-toggle="tab" href="#about"><?php print_string('filterbypathways','theme_eduopen'); ?></a>
                                    </li>
                        <?php   } 
                        }?>
                    </ul>
                </header>
    
</div>
<div class="row1" id="coursearea_row" style="<?php echo $return ? 'display:none' : 'display:block'; ?>">
    <div id="CourseArea" class="col-md-12">
        <div class="loader"></div>
    </div><!--AJAX load content will be displayed here-->
</div>

<!--Custom HTML ends here-->
<?php
echo $OUTPUT->footer();

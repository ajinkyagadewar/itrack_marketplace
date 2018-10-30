<?php
// This file is part of MoodleofIndia - http://moodleofindia.com/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * 
 * @package    local_myanalytics
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */
require_once('./../../../config.php');
include("../locallib.php");
require_login();

function lang($lang) {
    return get_string($lang, 'local_myanalytics');
}

require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/site.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('analytics_report');
$PAGE->set_title(lang('courselevelreport'));
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/myanalytics/js/course_level.js'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/local/myanalytics/css/course_level.css'));
//$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/myanalytics/css/bootstrap.min.css'));
$PAGE->set_heading(lang('courselevelreport'));
$PAGE->set_url($CFG->wwwroot . '/local/myanalytics/course/course_level_report.php');
//$PAGE->navbar->add('abc', new moodle_url('ads'));
echo $OUTPUT->header();
$visibleUserss = all_visible_roles();
?>
<style type="text/css">
    .notfound{
        border:solid 1px #F00;
    }
    .list-group-item{
        border:none;
    }
    .panel-body ul{
        margin: 0 0 10px 0px;
    }
</style>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-align-justify">&nbsp;</i> <strong><?php echo lang('courselevelreport'); ?></strong>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <?php  if (is_siteadmin() || $visibleUserss) { ?>
                    <li class="list-group-item" id="category">
                        <div class="form-group">
                            <label><?php echo lang('category'); ?></label>
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo lang('select_cat'); ?></span>
                                <input type="text" class="form-control" id="category-id" placeholder="Type/Double Click to Select a Category" style="height: 34px;" list="category-dl" />
                                <datalist id="category-dl">
                                    <?php
                                    if (is_siteadmin()) {
                                        foreach (coursecat::make_categories_list() as $key => $value) {
                                            echo "<option data-id='$key'>$value</option>";
                                        }
                                    } else if ($visibleUserss) {
                                        $alcourses = enrol_get_my_courses(['id', 'fullname']);
                                        $catlist = array();
                                        foreach ($alcourses as $key) {
                                            $category = $DB->get_record('course_categories', array('id' => $key->category));
                                            if (!isset($catlist[$category->id])) {
                                                $catlist[$category->id] = $category->name;
                                                echo "<option data-id='$key->category'>$category->name</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="alert-alt-message catgory-response"></div>
                    </li>
                    <?php }  ?>
                    <li class="list-group-item" id="course">
                        <div class="form-group ">
                            <label><?php echo lang('select_crs'); ?></label>
                            <div class="com alert alert-info">
                                <?php echo lang('crs_generate'); ?>
                            </div>
                            <div class="input-group">
                                <input type="text" id="course-id" class="form-control" placeholder="Type/Double Click to Select a Course" style="height: 34px;" list="course-dl">
                                <datalist id="course-dl">                            
                                </datalist>
                                <span class="input-group-btn">
                                    <button class="btn btn-success" id="course-btn" type="button"><i class="fa fa-bar-chart-o fa-fw"></i> Generate report
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="course-response"></div>
                        <div class="alert-alt-message activity-response"></div>
                        <div class="alert-alt-message users-response"></div>
                    </li>
                    <li class="list-group-item" id="activity">
                        <div class="form-group ">
                            <hr>
                            <label><?php echo lang('select_act'); ?></label>
                            <div class="com alert alert-info">
                                <?php echo lang('activity_generate'); ?>
                            </div>
                            <div class="input-group">
                                <input type="text" id="activity-id" class="form-control" placeholder="Type/Double Click to Select an Activity" style="height: 34px;" list="activity-dl">
                                <datalist id="activity-dl">                           
                                </datalist>
                                <span class="input-group-btn">
                                    <button class="btn btn-success" id="activity-btn" type="button"><i class="fa fa-bar-chart-o fa-fw"></i> Generate report
                                    </button>
                                </span>
                            </div>
                        </div>                    
                    </li>
                    <li class="list-group-item" id="users">
                        <div class="form-group ">
                            <hr>
                            <label><?php echo lang('select_user'); ?></label>
                            <div class="com alert alert-info">
                                <?php echo lang('user_generate'); ?>
                            </div>
                            <div class="input-group">
                                <input type="text" id="user-id" class="form-control" placeholder="Type/Double Click to Select an User" style="height: 34px;" list="users-dl">
                                <datalist id="users-dl">                           
                                </datalist>
                                <span class="input-group-btn">
                                    <button class="btn btn-success" id="user-btn" type="button"><i class="fa fa-bar-chart-o fa-fw"></i> Generate report
                                    </button>
                                </span>
                            </div>
                        </div>                    
                    </li>
                </ul>         
            </div>        
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-star-o"></i>&nbsp;<strong><?php echo lang('mayfav'); ?></strong>
            </div>
            <div class="panel-body">
                <?php
                $fav = \myanalytics\sitelevel\site_report::view_fav();
                $table = new \html_table();
                $table->attributes = ['class' => 'table table-striped  no-footer'];
                $table->head = [lang('sl'), lang('label'), lang('level')];
                $table->size = ['20%', '40%', '40%'];
                $sl = 1;
                foreach ($fav as $value) {
                    $key = 1;
                    $table->data[] = [$sl++, html_writer::link(new moodle_url($CFG->wwwroot . '/local/myanalytics/' . $value->link), $value->label), $value->level];
                }
                echo \html_writer::table($table);
                ?>
            </div>
        </div>
    </div>
</div>    
<script type="text/javascript">
    var basepath = '<?php echo $CFG->wwwroot . '/local/myanalytics/'; ?>';
    var URL = '<?php echo $CFG->wwwroot . '/local/myanalytics/ajax/course_ajax.php?mode='; ?>';
    var CourseLevelURL = '<?php echo $CFG->wwwroot . '/local/myanalytics/course/course_status.php?'; ?>';
    var UserlevelURL = '<?php echo $CFG->wwwroot . '/local/myanalytics/user/user_view.php?'; ?>';
    var ActivitylevelURL = '<?php echo $CFG->wwwroot . '/local/myanalytics/course/activity_view.php?'; ?>';
</script>
<?php
echo $OUTPUT->footer();
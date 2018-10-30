<?php
//
/**
 * Moodle's
 * This file is part of eduopen LMS Product
 *
 * @package   theme_eduopen
 * @copyright http://www.moodleofindia.com
 * @license   This file is copyrighted to Dhruv Infoline Pvt Ltd, http://www.moodleofindia.com
 */
require_once(dirname(__FILE__) . '/../config.php');

$strcourses = get_string('featured_course_management', 'theme_eduopen');
$header = "$strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/featured_course_management.php', $params);
$PAGE->set_pagelayout('eduopen_institution');
$PAGE->blocks->add_region('content');
$PAGE->set_title($header);
$PAGE->set_heading($header);
$PAGE->requires->jquery();

// For datatable.
$PAGE->requires->js('/eduopen/datatable/js/cdn/jquery.dataTables.min.js', true);
$PAGE->requires->js('/eduopen/datatable/js/cdn/dataTables.bootstrap.js', true);
$PAGE->requires->js('/eduopen/datatable/js/cdn/dataTables.fixedColumns.min.js', true);
$PAGE->requires->js('/eduopen/datatable/tabletool/js/dataTables.tableTools.js', true);
$PAGE->requires->css('/eduopen/datatable/css/cdn/dataTables.bootstrap.min.css');
$PAGE->requires->css('/eduopen/datatable/css/cdn/fixedColumns.bootstrap.min.css');
$PAGE->requires->css('/eduopen/datatable/tabletool/css/dataTables.tableTools.css');

echo $OUTPUT->header();

echo $OUTPUT->custom_block_region('content');

function langua($lang) {
    return get_string($lang, 'theme_eduopen');
}

if (is_siteadmin()) {
    ?>
    <div class="col-md-12 padTB">
        <div class="alert alert-info managehead">
            <i class="fa fa-cogs"></i>&nbsp;
            <?php echo langua('featured_course_management') ?>
        </div>

        <table id = "managefcourse" class = "display table1 table-striped" cellspacing = "0" width = "100%">
            <thead class = "managefeatured-thead">
                <?php
                echo "<tr style='background: rgba(217, 88, 67, 0.28);'>";
                echo "<th class='' width='10%'>" . langua('courseid') . "</th>";
                echo "<th class='' width='35%'>" . langua('coursename') . "</th>";
                echo "<th class='' width='20%'>" . langua('ccategory') . "</th>";
                echo "<th class='' width='20%'>" . langua('courseinst') . "</th>";

                echo "<th class='' width='15%'>" . langua('cstatus') . "</th>";
                echo "<th class='text-center' width='10%'>" . langua('featuredc') . "</th>";
                ?>
            </thead>

            <tbody>
                <?php
                $sql = "SELECT c.id, c.fullname, c.category, ce.featurecourse, ce.institution
                FROM {course} c JOIN {course_extrasettings_general} ce ON
                ce.courseid=c.id WHERE c.visible=1 AND ce.coursestatus=1";
                $rs = $DB->get_records_sql($sql);
                foreach ($rs as $cExtra) {
                    $courseid = $cExtra->id;
                    $coursename = $cExtra->fullname;
                    $ccateg = $DB->get_record('course_categories', array('id' => $cExtra->category), 'name');
                    $categoryname = $ccateg->name;
                    $coursestatusF = coursedetails_course_status($courseid);
                    $coursestatus = $coursestatusF['coursestatus'];
                    $featurCourse = $cExtra->featurecourse;
                    $instid = $cExtra->institution;
                    $instName = $DB->get_record('block_eduopen_master_inst', array('id' => $instid), 'name');

                    echo "<tr>";
                    echo "<td class='text-center pad10'>$courseid</td>";
                    echo "<td class='pad10'><a href='$CFG->wwwroot/course/view.php?id=$courseid'>$coursename</a></td>";
                    echo "<td class=' pad10'>$categoryname</td>";
                    echo "<td class=' pad10'>$instName->name</td>";
                    echo "<td class=' pad10'>$coursestatus</td>";
                    if ($featurCourse == 1) {
                        echo '<td class="text-center pad10 ">'
                        . '<input checked="checked" class="managecaction" url="a.php"  type="checkbox" '
                        . 'name="fcrscheck[]" value="' . $featurCourse . '&' . $courseid . '">'
                        . '</td>';
                    } else {
                        echo '<td class="text-center pad10 ">'
                        . '<input class="managecaction" url="a.php"  type="checkbox"'
                        . ' name="fcrscheck[]" value="' . $featurCourse . '&' . $courseid . '">'
                        . '</td>';
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#managefcourse').dataTable({
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "<?php echo $CFG->wwwroot; ?>/eduopen/datatable/tabletool/swf/copy_csv_xls_pdf.swf"
                }
            });
        });
    </script>
    <script>
        $('.managecaction').change(function () {
            var checkvalue = $(this).val();
            var featured = checkvalue[0];
            var courseid = checkvalue.substr(checkvalue.indexOf("&") + 1);
            var checked = $(this).is(':checked');
            $.ajax({
                type: "POST",
                url: "featured_management_ajax.php",
                data: {checked: checked, mode: "course", featuredcrs: featured, cid: courseid},
                success: function (data) {
                    //alert(data);
                }
            });
        });
    </script>
    <?php
} else {
    echo '<div class="alert alert warning center">You do not have access into this page</div>';
}
echo $OUTPUT->footer();

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

$strcourses = get_string('featured_pathway_management', 'theme_eduopen');
$header = "$strcourses";

// Start setting up the page
$params = array();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/eduopen/featured_pathway_management.php', $params);
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
        <?php echo langua('featured_pathway_management') ?>
    </div>
    <!-- Manage featured Pathway -->
    <table id="managefpathway" class="display table1 table-striped" cellspacing="0" width="100%">
        <thead class="managefeatured-thead">
            <?php
            echo "<tr style='background: rgba(217, 88, 67, 0.28);'>";
            echo "<th class='' width='10%'>" . langua('pathid') . "</th>";
            echo "<th class='' width='35%'>" . langua('pathname') . "</th>";
            echo "<th class='' width='17%'>" . langua('pathinst') . "</th>";
            echo "<th class='' width='10%'>" . langua('pathstatus') . "</th>";
            echo "<th class='text-center' width='8%'>" . langua('featuredc') . "</th>";
            ?>
        </thead>

        <tbody>
            <?php
            $Pathsql = "SELECT id, name, featuredpathway FROM {block_eduopen_master_special}
                WHERE status='1' AND pathwaystatus=1";
            $pathrs = $DB->get_records_sql($Pathsql);
            foreach ($pathrs as $pExtra) {
                $pathid = $pExtra->id;
                $pathname = $pExtra->name;
                $pathstatusF = pathway_status($pathid);
                $pathstatus = $pathstatusF['pathboxstatus'];
                $featurePath = $pExtra->featuredpathway;
                // Institution Name.
                $gen = $DB->get_records_sql("SELECT DISTINCT institution FROM 
                    {course_extrasettings_general} WHERE specializations @@ to_tsquery('$pathid')");
                $gencount = count($gen);
                foreach ($gen as $new) {
                    $newinst = $new->institution;
                }
                if ($gencount == 1) {
                    $ins = $DB->get_record_sql("SELECT * FROM {block_eduopen_master_inst}
                        WHERE id=$newinst");
                    if (current_language() == 'it') {
                        $instName = $ins->itname;
                    } else {
                        $instName = $ins->name;
                    }
                } else {
                    if (current_language() == 'it') {
                        $instName = get_string('instlist_it', 'theme_eduopen');
                    } else {
                        $instName = get_string('instlist', 'theme_eduopen');
                    }
                }
                //$pathInst = $DB->get_record('block_eduopen_master_inst');
                echo "<tr>";
                echo "<td class='text-center pad10'>$pathid</td>";
                echo "<td class='pad10'><a href='$CFG->wwwroot/eduopen/pathway_details.php?specialid=$pathid'>$pathname</a></td>";
                echo "<td class=' pad10'>$instName</td>";
                echo "<td class=' pad10'>$pathstatus</td>";
                if ($featurePath == 1) {
                    echo '<td class="text-center pad10">'
                    . '<input class="managepaction" type="checkbox" '
                    . 'checked="checked" name="fcrscheck[]" '
                    . 'value="' . $featurePath . '&' . $pathid . '"></td>';
                } else {
                    echo '<td class="text-center pad10">'
                    . '<input class="managepaction" type="checkbox" name="fpathcheck[]" '
                    . 'value="' . $featurePath . '&' . $pathid . '">'
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
        $('#managefpathway').dataTable({
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "<?php echo $CFG->wwwroot; ?>/eduopen/datatable/tabletool/swf/copy_csv_xls_pdf.swf"
            }
        });
    });
</script>
<script>
    $('.managepaction').change(function () {
        var checkvalue = $(this).val();
        var featured = checkvalue[0];
        var pathwayid = checkvalue.substr(checkvalue.indexOf("&") + 1);
        var checked = $(this).is(':checked');
        $.ajax({
            type: "POST",
            url: "featured_management_ajax.php",
            data: {checked: checked, mode: "pathway", featuredpath: featured, pathid: pathwayid},
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

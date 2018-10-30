<?php
// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * This script implements the manage_license of the dashboard, and allows editing
 * of the fooboo License.
 *
 * Local Educoupons 
 * @author     Arjun Singh <arjunsingh@elearn10.com>
 * @package    local_educoupons
 * @copyright  20/10/2016 lms of india
 * @license    http://lmsofindia.com/
 */
require_once('./../../config.php');
redirect_if_major_upgrade_required();
$url = new moodle_url($CFG->wwwroot . '/local/educoupons/user_view.php');
$url1 = new moodle_url($CFG->wwwroot . '/local/educoupons/coupons.php');
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->requires->jquery();
$PAGE->set_title(get_string('viewcoupons', 'local_educoupons'));
$PAGE->set_heading(get_string('viewcoupons', 'local_educoupons'));

$PAGE->navbar->add(get_string('viewcoupons', 'local_educoupons'), $url1);
$courseselect = '';

echo $OUTPUT->header();
?>
<?php
echo html_writer::start_tag('p', ['class' => 'lead bottomline']);
echo html_writer::tag('i', '', ['class' => 'fa fa-globe']).'&nbsp;&nbsp;';
echo html_writer::tag('span', get_string('user_view', 'local_educoupons') . $courseselect, ['class' => 'lead ']);
echo html_writer::end_tag('p');

global $DB;
$table = new html_table();
// Updated by Shiuli on 4/11/16.
$table->head = (array) get_strings(['crscouponid', 'couponcode', 'whoused', 'cpstatus'], 'local_educoupons');
$table->warp = array(null, 'nowrap');

$educouponid = required_param('id', PARAM_INT);
$educoupons = $DB->get_record('educoupons', ['id' => $educouponid], 'trackid');

$records = $DB->get_records_sql("SELECT DISTINCT educp.couponcode, educp.id as copid, edc.trackid as trackid,
    educp.cpstatus, edc.couponid, edc.status
    FROM {educoupons} edc
    JOIN {edu_couponcode} educp ON educp.tid=edc.trackid
    WHERE edc.trackid='$educoupons->trackid'");

foreach ($records as $row) {
    $sqlapply = "SELECT ea.userid
    FROM {edupayurl_apply} ea
    JOIN {edu_couponcode} ec ON ec.id=ea.coupid
    JOIN {educoupons} ed ON ed.trackid=ec.tid 
    WHERE ed.id=$educouponid AND ec.id=$row->copid";
    $appliedcoupon = $DB->get_records_sql($sqlapply);
    $usercount = count($appliedcoupon);
    $coupstatus = ($row->cpstatus == 1) ? 'Active' : 'Inactive';
    if ($row->cpstatus == 1) {
        $checkedStatus = html_writer::tag('input', '', array('type' => 'checkbox', 'class' => "managestatus", 'checked' => "checked",
                    'name' => "fcrscheck", 'value' => $row->cpstatus . '&' . $row->copid . '&' . $row->trackid));
    } else {
        $checkedStatus = html_writer::tag('input', '', array('type' => 'checkbox', 'class' => "managestatus",
                    'name' => "fcrscheck", 'value' => $row->cpstatus . '&' . $row->copid . '&' . $row->trackid));
    }
    $table->data[] = array(
        $row->couponid,
        $row->couponcode,
        html_writer::link('#normalModal' . $row->copid, ' ' . $usercount, array('data-toggle' => 'modal',
            'title' => get_string('clicktoshowusers', 'local_educoupons'), 'class' => 'usercntbld')),
        $checkedStatus
    );
    $userlists = '';
    foreach ($appliedcoupon as $applieduser) {
        $user = $DB->get_record('user', ['id' => $applieduser->userid]);
        $userimage = $OUTPUT->user_picture($user, array('size' => 40, 'class' => 'img-circle'));
        $userlists .= html_writer::tag('li', $userimage . ' ' . fullname($user), array('class' => 'usrlist'));
    }
    $userlist = $userlists ? $userlists : '<center>' . get_string('nousers_appliedcp', 'local_educoupons') . '</center>';

    $ctitle = html_writer::tag('h4', get_string('ctitle', 'local_educoupons', $row->couponcode), array('class' => 'modal-title'));
    $chead = html_writer::tag('div', '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' . $ctitle, array('class' => 'modal-header'));
    $cbody = html_writer::tag('div', $userlist, array('class' => 'modal-body user_cplist'));
    $ccontent = html_writer::tag('div', $chead . $cbody, array('class' => 'modal-content'));
    $cdialogue = html_writer::tag('div', $ccontent, array('class' => 'modal-dialog'));
    echo html_writer::tag('div', $cdialogue, array('id' => 'normalModal' . $row->copid, 'class' => 'modal fade'));
}
echo html_writer::table($table);

echo $OUTPUT->footer();
?>

<script>
    $('.managestatus').change(function () {
        var checkstatus = $(this).val();
        var spiltArr = checkstatus.split("&");
        var cpstat = spiltArr[0];
        var coupnid = spiltArr[1];
        var ctrckid = spiltArr[2];
        var checked = $(this).is(':checked');
        console.log(checked);
        $.ajax({
            type: "POST",
            url: "manage_cpstatus.php",
            data: {checked: checked, cpstatus: cpstat, coupid: coupnid, ctrackid: ctrckid},
            success: function (data) {
                //alert(data);
            }
        });
    });
</script>
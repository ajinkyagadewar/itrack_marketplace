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
require_once('forms/select_course_coupon_form.php');
require_once(dirname(__FILE__) . '../../../config.php');
require_once('forms/add_form.php');
redirect_if_major_upgrade_required();
$url = new moodle_url($CFG->wwwroot . '/local/educoupons/coupons.php');
$PAGE->set_pagelayout('course');
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->requires->jquery();
$PAGE->set_title(get_string('userviewcoupons', 'local_educoupons'));
$PAGE->set_heading(get_string('userviewcoupons', 'local_educoupons'));
$selectform = new select_course_coupon_form(null, '', 'get');
$records = null;
$courseselect = '';
if ($selectform->is_cancelled()) {
    redirect(new \moodle_url($CFG->wwwroot . '/course/view.php', ['id' => $courseid]));
} else if ($data = $selectform->get_data()) {
    $records = $DB->get_records('educoupons', ['courseid' => $data->courseid]);
    $selectform = null;
    if ($data->courseid) {
        $course = $DB->get_record('course', array('id' => $data->courseid));
        $coursename = html_writer::link(new moodle_url($CFG->wwwroot . '/course/view.php', ['id' => $course->id]), $course->fullname);
    } else {
        $site = get_string('sitelevel', 'local_educoupons');
        $coursename = html_writer::link(new moodle_url($CFG->wwwroot . '/my'), $site);
    }
    $courseselect = '( ' . get_string('courseselect', 'local_educoupons') . '<strong>' . $coursename . '</strong> )';
}
echo $OUTPUT->header();
echo html_writer::tag('p', get_string('viewcoupons', 'local_educoupons') . $courseselect, ['class' => 'lead bottomline']);
if ($records != null) {
    global $DB;
    $table = new html_table();
    // Updated by Shiuli on 4/11/16.
    $table->head = (array) get_strings(['sl', 'crscouponid', 'cpnstartdate', 'cpnenddate', 'nochead', 'discount', 'level', 'active', 'action'], 'local_educoupons');
    $table->warp = array(null, 'nowrap');
    $sl = 1;
    foreach ($records as $row) {
        if (($row->status) == '1') {
            $status = html_writer::tag('span', get_string('active', 'local_educoupons'), array('class' => 'label label-success'));
        } else {
            $status = html_writer::tag('span', get_string('inactive', 'local_educoupons'), array('class' => 'label label-danger'));
        }

        if ($row->courseid == '1') {
            $course = get_string('sitelevel', 'local_educoupons');
        } else {
            $course = get_string('courses', 'local_educoupons');
        }
        if ($row->deleted == '0') {
            $deleted = html_writer::tag('span', '<i class="icon-trash icon-large"></i> ' . get_string('deleted', 'local_educoupons'), array('class' => 'label label-danger'));
            $status = html_writer::tag('span', get_string('inactive', 'local_educoupons'), array('class' => 'label label-danger'));
        } else {
            // Updated by Shiuli on 4/11/16 -- for couponid.
            $deleted = html_writer::link(new moodle_url($CFG->wwwroot . '/local/educoupons/editcoupons.php', ['id' => $row->id, 'courseid' => $row->courseid]), '<i class="icon icon-edit"></i> ') .
                    html_writer::link(new moodle_url($CFG->wwwroot . '/local/educoupons/download.php', ['courseid' => $row->courseid, 'cpid' => $row->id,]), '<i class="icon icon-download"></i> ') .
                    html_writer::link(new moodle_url($CFG->wwwroot . '/local/educoupons/deletedcoupons.php', ['id' => $row->id, 'courseid' => $row->courseid]), ' <i class="icon icon-trash"></i> ') .
                    html_writer::link(new moodle_url($CFG->wwwroot . '/local/educoupons/user_view.php', ['id' => $row->id, 'courseid' => $row->courseid]), ' <i class="fa fa-link" aria-hidden="true"></i>', array('data-toggle' => 'modal', 'target' => "_blank"));
        }
        $table->data[] = array(
            $sl++,
            $row->couponid,
            userdate($row->cpnstartdate, '%d-%m-%Y'),
            userdate($row->cpnenddate, '%d-%m-%Y'),
            $row->noc,
            $row->cpnpercent . get_string('percent', 'local_educoupons'),
            $course,
            $status,
            $deleted
        );

        $couponlists = '';
//        $cccodes = $DB->get_records('edu_couponcode', array('tid' => $row->trackid), 'id, tid, couponcode');

        $cccodes = $DB->get_records_sql("SELECT DISTINCT educp.couponcode, educp.cpstatus, edc.couponid, edc.status
                FROM {educoupons} edc
                JOIN {edu_couponcode} educp ON educp.tid=edc.trackid
                WHERE edc.trackid='$row->trackid'");
//        print_object($cccodes);
        $couponlists .= '<p>'
                . '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '
                . '<b>Coupon Id:</b>' . '&nbsp;&nbsp;&nbsp;'
                . '<b>Coupon Code</b>' . '&nbsp;&nbsp;&nbsp;'
                . '<b>Who used </b>' . '&nbsp;&nbsp;&nbsp;'
                . '<b>Status</b>' . '&nbsp;&nbsp;&nbsp;'
                . '</p>';
        foreach ($cccodes as $cccode) {
            // Added by Shiuli on 4/11/16.
            $couponlists .= '<p>'
                    . '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '
                    . $cccode->couponid . '&nbsp;&nbsp;&nbsp;'
                    . $cccode->couponcode . '&nbsp;&nbsp;&nbsp;'
                    . 'Shiuli Jana' . '&nbsp;&nbsp;&nbsp;'
                    . $cccode->cpstatus . '&nbsp;&nbsp;&nbsp;'
                    . '</p>';
        }
        $ctitle = html_writer::tag('h4', get_string('ctitle', 'local_educoupons', $row->trackid), array('class' => 'modal-title'));
        $chead = html_writer::tag('div', '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' . $ctitle, array('class' => 'modal-header'));
        $cbody = html_writer::tag('div', $couponlists, array('class' => 'modal-body'));
        $ccontent = html_writer::tag('div', $chead . $cbody, array('class' => 'modal-content'));
        $cdialogue = html_writer::tag('div', $ccontent, array('class' => 'modal-dialog'));
        echo html_writer::tag('div', $cdialogue, array('id' => 'normalModal' . $row->trackid, 'class' => 'modal fade'));
    }
    echo html_writer::table($table);
} else if ($selectform == null) {
    echo \html_writer::div(get_string("couponnotfound", "local_educoupons"), 'alert alert-warning');
}
if ($selectform != null) {
    $selectform->display();
}
echo $OUTPUT->footer();

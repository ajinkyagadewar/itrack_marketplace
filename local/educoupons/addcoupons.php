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

require_once(dirname(__FILE__) . '../../../config.php');

require_once('forms/add_form.php');
redirect_if_major_upgrade_required();
require_login(0,false);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$url = new moodle_url($CFG->wwwroot.'/local/educoupons/addcoupons.php');
$PAGE->set_url($url);
$PAGE->requires->jquery();
$PAGE->requires->js('/local/educoupons/scripts.js');
$PAGE->set_title(get_string('createcoupons', 'local_educoupons'));
$PAGE->set_heading(get_string('createcoupons', 'local_educoupons'));
global $USER,$DB;
$message = false;
$addform = new add_form($url);
$addform->is_validated();
if ($addform->is_cancelled()) {
    redirect(new \moodle_url($CFG->wwwroot.'/my'));
}
else if ($data = $addform->get_data()) {
    $i = 0;
    // if ($data->cpnstartdate && $data->cpnenddate) {
    //     if ($data->cpnstartdate > $data->cpnenddate) {
    //         $errors['cpnenddate'] = get_string('datevalidation', 'local_educoupons');
    //         $addform = null;
    //     }
    // }
    $data->userid = $USER->id;
    $data->timecreated = time();
    $data->timemodified = time();
    $data->trackid = $data->courseid.''.$i;
    $recs = $DB->get_records('educoupons',array('courseid' => $data->courseid),'trackid');
    foreach($recs as $rec){
        if($rec->trackid == $data->trackid){
            $i = $i + 1;
            $data->trackid = $data->courseid.''.$i;
        }
    }
    if ($DB->insert_record('educoupons', $data)) {
        $message = \html_writer::div(get_string("couponscreated", "local_educoupons"), 'alert alert-success');
        //generating coupons here
        $length = 6;
        $allcoupons = array();
        $message .= html_writer::start_div('alert alert-success');
        $message .= html_writer::tag('h5', 'Your coupons are generated', array('class' => 'alert-heading'));  
        for ($i=0;$i<$data->noc;$i++){ 
            $random =  substr(str_shuffle(get_string('shufflestring','local_educoupons')), 0, $length);    
            $randomcoupons = $data->courseprefix.''.$data->recprefix.''.$random;
            $allcoupons []= $randomcoupons;
            $message .= html_writer::tag('p', $randomcoupons, array('class' => 'mb-0'));
        }
        $arrCnt = sizeof($allcoupons);
        $message .= html_writer::end_div();
        $message .= '<form action="couponssheet.php" method="post" class="dndbtn">
                        <input type="hidden" name="arrCnt" value="'.$arrCnt.'" />';
                        for( $i=0; $i < $arrCnt; $i++ ) {
                            $message .= '<input type="hidden" name="name'.$i.'" value="'.$allcoupons[$i].'"/>';
                        }
        $message .= '<button name="desc" value="1">Export to excel</button>
                     </form>';
        $message .= '<form action="coupons.php?courseid='.$data->courseid.'" method="post" class="dndbtn">
                        <button name="viewcoupon" value="1">View coupons</button>
                     </form>';  
        $addform = null;
        $i = 1;
        foreach($allcoupons as $cid => $eachcoupon)
        {
            $cdata = new \stdClass();
            $cdata->tid = $data->trackid;
            $cdata->couponcode = $eachcoupon; 
            $DB->insert_record('edu_couponcode',$cdata);
        }
    }
       
}
echo $OUTPUT->header();
if($message){
    echo $message;
}
if ($addform != null) {
    echo \html_writer::tag('p', get_string('createcoupons', 'local_educoupons'), ['class' => 'lead bottomline']);
    echo $addform->display();
}
echo $OUTPUT->footer();
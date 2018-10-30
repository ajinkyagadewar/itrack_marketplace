<?php

/**
 * Lesson Automation block caps.
 *
 * @package    block_edupayments
 * @author     Shambhu kumar <Moodle Of India>
 * @copyright  Moodle Of India <http://www.moodleofindia.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * cost and currency need to be changed to pick from course extra settings
 */
class block_edupayments extends block_base {

    public function init() {
        $this->title = get_string('edupayments', 'block_edupayments');
    }

    public function get_content() {
        global $CFG, $USER, $DB, $COURSE, $PAGE;
        $user = $USER;
        $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/edupayments/scripts.js'));
        $cnameqr = $DB->get_record('course', array('id' => $COURSE->id));

        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass();
        $contents = '';
       
        $paypalurl = empty($CFG->usepaypalsandbox) ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        $b = new stdclass();
        $b->firstname = fullname($user);
        $b->user = fullname($user);
        $b->coursename = $cnameqr->fullname;
        
        $chk_currency = $DB->get_field_sql("SELECT currency from {course_extrasettings_general} where courseid = $COURSE->id");
        $plugin = $DB->get_record('enrol', array('enrol' => "edupay", 'courseid' => "$COURSE->id", 'name' => 'firstattend'));

        $pay = enrol_get_plugin('edupay');
        $chk_firstatt = $DB->get_record('enrol_edupay', array('item_name' => 'firstattend', 'userid' => $user->id, 'courseid' => $COURSE->id));
        $chk_firstcost = $DB->get_field_sql("SELECT cost from {course_extrasettings_general} where courseid = $COURSE->id");
        $b->amount = $chk_firstcost;
        $b->currency = $chk_currency;
        /**
         * @desc First attemd button with functionality
         * 
         */
        if ($chk_firstcost != 0) {
            if (!$chk_firstatt) {
                $contents .=  '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavenotpaidfirst', 'block_edupayments');
                $itemvalue = get_string('signuppaypalfirstattend', 'block_edupayments');
                $btemp = 'Attendance Fee 
                                        Course: ' . $b->coursename . '
                                        User: ' . $b->user . '
                                        Amount:' . $b->currency . ' ' . $b->amount . ' ';
                $btemp = 'firstattend';
                $contents .='<form name="'.$itemvalue.'" class="actionform" id="form'.$itemvalue.'" action="' . $paypalurl . '" method="post">';
                $contents .='<input type="hidden" name="cmd" value="_xclick" />';
                $contents .='<input type="hidden" name="charset" value="utf-8" />';
                $contents .='<input type="hidden" name="business" value="' . $pay->get_config('edupaybusiness') . '" />';
                $contents .='<input type="hidden" name="item_name" value="' . $btemp . '" />';
                $contents .='<input type="hidden" name="item_number" value="' . $itemvalue . '" />';
                $contents .='<input type="hidden" name="quantity" value="1" />';
                $contents .='<input type="hidden" name="on0" value="' . $user->username . '" />';
  $contents .='<input type="hidden" name="os0" value="' . $user->firstname . ' ' . $user->lastname . '" />';
                $contents .='<input type="hidden" id="custom'.$itemvalue.'" name="custom" value="' . $user->id . '|' . $plugin->courseid . '|' . $plugin->id . '" />';
                $contents .='<input type="hidden" name="currency_code" value="' . $chk_currency . '" />';
                $contents .='<input type="hidden" id="'.$itemvalue.'" name="amount" value="' . $chk_firstcost . '" />';
                $contents .='<input type="hidden" name="for_auction" value="false" />';
                $contents .='<input type="hidden" name="no_note" value="1" />';
                $contents .='<input type="hidden" name="no_shipping" value="1" />';
                $contents .='<input type="hidden" name="notify_url" value="' . $CFG->wwwroot . '/enrol/edupay/ipn.php' . '" />';
                 $contents .='<input type="hidden" name="return" value="' . $CFG->wwwroot . '/enrol/edupay/return.php?id='.$COURSE->id.'" />';
                $contents .='<input type="hidden" name="cancel_return" value="' . $CFG->wwwroot . '" />';
                $contents .='<input type="hidden" name="rm" value="2" />';
                $contents .='<input type="hidden" name="cbt" value="' . get_string('ClickheretoenteryourunimoreDashboard', 'block_edupayments') . '" />';
                $contents .='<input type="hidden" name="first_name" value="' . $user->firstname . '" />';
                $contents .='<input type="hidden" name="last_name" value="' . $user->lastname . '" />';
                $contents .='<input type="hidden" name="address" value="" />';
                $contents .='<input type="hidden" name="city" value="' . $user->city . '" />';
                $contents .='<input type="hidden" name="email" value="' . $user->email . '" />';
                $contents .='<input type="hidden" name="country" value="' . $user->country . '" />';
                $contents .='<input type="submit" style="width: 95px" value="' . get_string('Clicktopay', 'block_edupayments').'"/>';
                
                $contents .= '<div class="pull-right coupon-action-btn"><span id="'.$itemvalue.'amount">'.$chk_currency.' '.$chk_firstcost.'</span><br/>';
                $contents .= html_writer::link('#', get_string('havepromocd', 'block_edupayments'), array('onclick' => 'return get_promotions("firstattenddata",'.$COURSE->id.',"'.$itemvalue.'")'));
                $contents .= '</div>';
                $contents .= html_writer::div('', 'promocode-box', array('id'=>'firstattenddata'));
                $contents .='</form>';
            } else {
                $contents .= '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/payment_done.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavesuccessfullydonethepaymentforexamination', 'block_edupayments').'<br><br>';
            }            
        }
        
        /**
         * @desc attendance of completion
         */
        $plugin = $DB->get_record('enrol', array('enrol' => "edupay", 'courseid' => "$COURSE->id", 'name' => 'attendance_of_completion'));
        $chk_attendance = $DB->get_record('enrol_edupay', array('item_name' => 'attendance_of_completion', 'userid' => $user->id, 'courseid' => $COURSE->id));

        $chk_attendcost = $DB->get_field_sql("SELECT costforattendance from {course_extrasettings_general} where courseid = $COURSE->id");
        $b->amount = $chk_attendcost;
        if ($chk_attendcost != 0 and $plugin) {
            $contents .= html_writer::empty_tag('hr', array('class' => 'row-divider'));
            if (!$chk_attendance) {
                $contents .= '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('Youhavenotgettheattendanceofcompletion', 'block_edupayments');
                $itemvalue = get_string('signuppaypalatdc', 'block_edupayments');
                $contents .='<form name="'.$itemvalue.'" class="actionform" id="form'.$itemvalue.'"  action="' . $paypalurl . '" method="post">';
                $contents .='<input type="hidden" name="cmd" value="_xclick" />';
                $contents .='<input type="hidden" name="charset" value="utf-8" />';
                $contents .='<input type="hidden" name="business" value="' . $pay->get_config('edupaybusiness') . '" />';
                $btemp1 = 'Attendance Certificate
                                        Course: ' . $b->coursename . '
                                        User: ' . $b->user . '
                                        Amount:' . $b->currency . ' ' . $b->amount . ' ';
                $btemp1 = 'attendance_of_completion';
                $contents .='<input type="hidden" name="item_name"   value="' . $btemp1 . '" />';
                $contents .='<input type="hidden" name="item_number" value="' . $itemvalue . '" />';
                $contents .='<input type="hidden" name="quantity" value="1" />';
                $contents .='<input type="hidden" name="on0" value="' . $user->username . '" />';
                $contents .='<input type="hidden" name="os0" value="' . $user->firstname . ' ' . $user->lastname . '" />';
                $contents .='<input type="hidden" id="custom'.$itemvalue.'" name="custom" value="' . $user->id . '|' . $plugin->courseid . '|' . $plugin->id . '" />';
                $contents .='<input type="hidden" name="currency_code" value="' . $chk_currency . '" />';
                $contents .='<input type="hidden" id="'.$itemvalue.'" name="amount" value="' . $chk_attendcost . '" />';
                $contents .='<input type="hidden" name="for_auction" value="false" />';
                $contents .='<input type="hidden" name="no_note" value="1" />';
                $contents .='<input type="hidden" name="no_shipping" value="1" />';
                $contents .='<input type="hidden" name="notify_url" value="' . $CFG->wwwroot . '/enrol/edupay/ipn.php' . '" />';
                 $contents .='<input type="hidden" name="return" value="' . $CFG->wwwroot . '/enrol/edupay/return.php?id='.$COURSE->id.'" />';
                $contents .='<input type="hidden" name="cancel_return" value="' . $CFG->wwwroot . '" />';
                $contents .='<input type="hidden" name="rm" value="2" />';
                $contents .='<input type="hidden" name="cbt" value="' . get_string('ClickheretoenteryourunimoreDashboard', 'block_edupayments') . '" />';
                $contents .='<input type="hidden" name="first_name" value="' . $user->firstname . '" />';
                $contents .='<input type="hidden" name="last_name" value="' . $user->lastname . '" />';
                $contents .='<input type="hidden" name="address" value="" />';
                $contents .='<input type="hidden" name="city" value="' . $user->city . '" />';
                $contents .='<input type="hidden" name="email" value="' . $user->email . '" />';
                $contents .='<input type="hidden" name="country" value="' . $user->country . '" />';
                $contents .='<input type="submit" style="width: 95px" value="' . get_string('Clicktopay', 'block_edupayments').'"/>';
                $contents .= '<div class="pull-right coupon-action-btn"><span id="'.$itemvalue.'amount">'.$chk_currency.' '.$chk_attendcost.'</span><br/>';
                $contents .= html_writer::link('#', get_string('havepromocd', 'block_edupayments'), array('onclick' => 'return get_promotions("attendance",'.$COURSE->id.',"'.$itemvalue.'")'));
                $contents .= '</div>';
                $contents .= html_writer::div('', 'promocode-box', array('id'=>'attendance'));
                $contents .='</form>';
            } else {
                $contents .= '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/payment_done.png'.'"
			 height="20px" width="20px" style="margin-right: 3px"/>'.
			 get_string('Youhavesuccessfullydonethepaymentforattendanceofcompletion', 'block_edupayments').'<br><br>';
            }
        }
        
        /**
         * @desc Varified certificate
         */
        $plugin = $DB->get_record('enrol', array('enrol' => "edupay", 'courseid' => "$COURSE->id", 'name' => 'verifiedcerti'));
        $chk_verifiedcerti = $DB->get_record('enrol_edupay', array('item_name' => 'verifiedcerti', 'userid' => $user->id, 'courseid' => $COURSE->id));
        $chk_verifiedcost = $DB->get_field_sql("SELECT vcostforattendance from {course_extrasettings_general} where courseid = $COURSE->id");
        $b->amount = $chk_verifiedcost;
        if ($chk_verifiedcost != 0 and $plugin) {
             $contents .= html_writer::empty_tag('hr', array('class' => 'row-divider'));
            if (!$chk_verifiedcerti) {                
                $contents .= '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('applyverified', 'block_edupayments');
                $itemvalue = get_string('signuppaypalverified', 'block_edupayments');
                $contents .='<form name="'.$itemvalue.'" class="actionform" id="form'.$itemvalue.'"  action="' . $paypalurl . '" method="post">';
                $contents .='<input type="hidden" name="cmd" value="_xclick" />';
                $contents .='<input type="hidden" name="charset" value="utf-8" />';
                $contents .='<input type="hidden" name="business" value="' . $pay->get_config('edupaybusiness') . '" />';
                $btemp2 = 'Verified Certificate
                                        Course: ' . $b->coursename . '
                                        User: ' . $b->user . '
                                        Amount:' . $b->currency . ' ' . $b->amount . ' ';
               $btemp2 = 'verifiedcerti';
                $contents .='<input type="hidden" name="item_name" value="' . $btemp2 . '" />';
                $contents .='<input type="hidden" name="item_number" value="' . $itemvalue . '" />';
                $contents .='<input type="hidden" name="quantity" value="1" />';
                $contents .='<input type="hidden" name="on0" value="' . $user->username . '" />';
                $contents .='<input type="hidden" name="os0" value="' . $user->firstname . ' ' . $user->lastname . '" />';
                $contents .='<input type="hidden" id="custom'.$itemvalue.'" name="custom" value="' . $user->id . '|' . $plugin->courseid . '|' . $plugin->id . '" />';
                $contents .='<input type="hidden" name="currency_code" value="' . $chk_currency . '" />';
                $contents .='<input type="hidden" id="'.$itemvalue.'" name="amount" value="' . $chk_verifiedcost . '" />';
                $contents .='<input type="hidden" name="for_auction" value="false" />';
                $contents .='<input type="hidden" name="no_note" value="1" />';
                $contents .='<input type="hidden" name="no_shipping" value="1" />';
                $contents .='<input type="hidden" name="notify_url" value="' . $CFG->wwwroot . '/enrol/edupay/ipn.php' . '" />';
                 $contents .='<input type="hidden" name="return" value="' . $CFG->wwwroot . '/enrol/edupay/return.php?id='.$COURSE->id.'" />';
                $contents .='<input type="hidden" name="cancel_return" value="' . $CFG->wwwroot . '" />';
                $contents .='<input type="hidden" name="rm" value="2" />';
                $contents .='<input type="hidden" name="cbt" value="' . get_string('ClickheretoenteryourunimoreDashboard', 'block_edupayments') . '" />';
                $contents .='<input type="hidden" name="first_name" value="' . $user->firstname . '" />';
                $contents .='<input type="hidden" name="last_name" value="' . $user->lastname . '" />';
                $contents .='<input type="hidden" name="address" value="" />';
                $contents .='<input type="hidden" name="city" value="' . $user->city . '" />';
                $contents .='<input type="hidden" name="email" value="' . $user->email . '" />';
                $contents .='<input type="hidden" name="country" value="' . $user->country . '" />';
                $contents .='<input type="submit" style="width: 95px" value="' . get_string('Clicktopay', 'block_edupayments').'"/>';
                $contents .= '<div class="pull-right coupon-action-btn"><span id="'.$itemvalue.'amount">'.$chk_currency.' '.$chk_verifiedcost.'</span><br/>';
                
                $contents .= html_writer::link('#', get_string('havepromocd', 'block_edupayments'), array('onclick' => 'return get_promotions("verifiedcertificate",'.$COURSE->id.',"'.$itemvalue.'")'));
                $contents .= '</div>';
                $contents .= html_writer::div('', 'promocode-box', array('id'=>'verifiedcertificate'));
                $contents .='</form>';
            } else {                
                $contents .= '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/payment_done.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('successverifiedcerti', 'block_edupayments').'</br>';
            }
        }
        /**
         * @desc Examination
         */
        $plugin = $DB->get_record('enrol', array('enrol' => "edupay", 'courseid' => "$COURSE->id", 'name' => 'examination'));
        $chk_examnination = $DB->get_record('enrol_edupay', array('item_name' => 'examination', 'userid' => $user->id, 'courseid' => $COURSE->id));
        $chk_examninationcost = $DB->get_field_sql("SELECT costforformalcredit from {course_extrasettings_general} where courseid = $COURSE->id");
        $b->amount = $chk_verifiedcost;
        if ($chk_examninationcost != 0 and $plugin) {
            $contents .= html_writer::empty_tag('hr', array('class' => 'row-divider'));
            if (!$chk_examnination) {                
                $contents .= '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/msg_icon.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('applyexamination', 'block_edupayments');
                $itemvalue = get_string('signuppaypalexam', 'block_edupayments');
                $contents .='<form name="'.$itemvalue.'" class="actionform" id="form'.$itemvalue.'"  action="' . $paypalurl . '" method="post">';
                $contents .='<input type="hidden" name="cmd" value="_xclick" />';
                $contents .='<input type="hidden" name="charset" value="utf-8" />';
                $contents .='<input type="hidden" name="business" value="' . $pay->get_config('edupaybusiness') . '" />';
                $btemp3 = 'Verified Certificate
                                        Course: ' . $b->coursename . '
                                        User: ' . $b->user . '
                                        Amount:' . $b->currency . ' ' . $b->amount . ' ';
                $btemp3 = 'examination';
                $contents .='<input type="hidden" name="item_name" value="' . $btemp3 . '" />';
                $contents .='<input type="hidden" name="item_number" value="' . $itemvalue . '" />';
                $contents .='<input type="hidden" name="quantity" value="1" />';
                $contents .='<input type="hidden" name="on0" value="' . $user->username . '" />';
                $contents .='<input type="hidden" name="os0" value="' . $user->firstname . ' ' . $user->lastname . '" />';
                $contents .='<input type="hidden" id="custom'.$itemvalue.'"  name="custom" value="' . $user->id . '|' . $plugin->courseid . '|' . $plugin->id . '" />';
                $contents .='<input type="hidden" name="currency_code" value="' . $chk_currency . '" />';
                $contents .='<input type="hidden" id="'.$itemvalue.'" name="amount" value="' . $chk_examninationcost . '" />';
                $contents .='<input type="hidden" name="for_auction" value="false" />';
                $contents .='<input type="hidden" name="no_note" value="1" />';
                $contents .='<input type="hidden" name="no_shipping" value="1" />';
                $contents .='<input type="hidden" name="notify_url" value="' . $CFG->wwwroot . '/enrol/edupay/ipn.php' . '" />';
                 $contents .='<input type="hidden" name="return" value="' . $CFG->wwwroot . '/enrol/edupay/return.php?id='.$COURSE->id.'" />';
                $contents .='<input type="hidden" name="cancel_return" value="' . $CFG->wwwroot . '" />';
                $contents .='<input type="hidden" name="rm" value="2" />';
                $contents .='<input type="hidden" name="cbt" value="' . get_string('ClickheretoenteryourunimoreDashboard', 'block_edupayments') . '" />';
                $contents .='<input type="hidden" name="first_name" value="' . $user->firstname . '" />';
                $contents .='<input type="hidden" name="last_name" value="' . $user->lastname . '" />';
                $contents .='<input type="hidden" name="address" value="" />';
                $contents .='<input type="hidden" name="city" value="' . $user->city . '" />';
                $contents .='<input type="hidden" name="email" value="' . $user->email . '" />';
                $contents .='<input type="hidden" name="country" value="' . $user->country . '" />';
                $contents .='<input type="submit" style="width: 95px" value="' . get_string('Clicktopay', 'block_edupayments').'"/>';
                
                $contents .= '<div class="pull-right coupon-action-btn"><span id="'.$itemvalue.'amount">'.$chk_currency.' '.$chk_examninationcost.'</span><br/>';                
                $contents .= html_writer::link('#', get_string('havepromocd', 'block_edupayments'), array('onclick' => 'return get_promotions("signuppaypalexamsec",'.$COURSE->id.',"'.$itemvalue.'")'));
                $contents .= '</div>';
                $contents .= html_writer::div('', 'promocode-box', array('id'=>'signuppaypalexamsec'));
                $contents .='</form>';
            } else {                
                $contents .= '<img src="'.$CFG->wwwroot.'/blocks/edupayments/pix/payment_done.png'.'" height="20px" width="20px" style="margin-right: 3px"/>'.get_string('successverifiedcerti', 'block_edupayments').'</br>';
            }
        }
        $this->content->text = $contents;
        $this->content->footer = '';
        return $this->content;
    }
}
?>
<script type="text/javascript">
    var promoconfig = {
        'url': '<?php echo $CFG->wwwroot . "/blocks/edupayments/ajax_calls.php"; ?>',
        'autoEnrolUrl': '<?php echo $CFG->wwwroot . "/blocks/edupayments/freeenrol.php"; ?>',
    };
</script>
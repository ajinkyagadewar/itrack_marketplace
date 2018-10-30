<?php
// $Id: inscriptions_massives.php 356 2010-02-27 13:15:34Z ppollet $
/**
 * A bulk enrolment plugin that allow teachers to massively enrol existing accounts to their courses,
 * with an option of adding every user to a group
 * Version for Moodle 1.9.x courtesy of Patrick POLLET & Valery FREMAUX  France, February 2010
 * Version for Moodle 2.x by pp@patrickpollet.net March 2012
 */
require(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once ('lib.php');
/** Include eventslib.php */
require_once($CFG->libdir . '/eventslib.php');
/** Include calendar/lib.php */
require_once($CFG->dirroot . '/calendar/lib.php');

global $PAGE;

$id = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', array('id' => $id));
/// Security and access check
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/role:assign', $context);

/// Start making page
$PAGE->set_pagelayout('course');
$PAGE->set_url('/local/course_extrasettings/course_extrasettings.php');

$addnewpromo = 'Add Course Extrasettings';
$PAGE->navbar->add($addnewpromo);
$PAGE->set_title($addnewpromo);
$PAGE->set_heading("$course->fullname" . ' Add course_extrasettings');

$PAGE->requires->jquery();

if ($gen = $DB->record_exists('course_extrasettings_general', array('courseid' => $id))) {
    /* if(isset($gen)) {
      if($gen){ */
    $url = new moodle_url($CFG->wwwroot . '/local/course_extrasettings/edit_general.php?courseid=' . $id);
    //redirect($CFG->wwwroot . "/local/course_extrasettings/view_promo.php?id=$id");
    redirect($url);
    $insertgen = false;
} else {
    require_once('form/course_extrasettings_form.php');
    $insertgen = true;
}

$mform = new course_extrasettings_form($CFG->wwwroot . '/local/course_extrasettings/course_extrasettings.php', array('course' => $course));

if ($generalfirst = $DB->record_exists('course_extrasettings_general', array('courseid' => $course->id))) {
    $generalalredy = new stdClass();
    $generalalredy = $DB->get_record('course_extrasettings_general', array('courseid' => $course->id));
    $mform->set_data($generalalredy);
}
if (!isset($generalalredy)) {
    $generalalredy = new stdClass();
    $generalalredy->courseid = -1;
}
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course/view.php?id=' . $id));
} else if ($data = $mform->get_data(false)) {
    global $USER;
    //modified by nihar
    $coursecontext = context_course::instance($course->id);
    //$personalcontext = context_user::instance($USER->id);
    if ($data->courseid == $generalalredy->courseid) {
        if ($insertgen == true) {
            $generalupd = new stdClass();
            $generalupd->id = $generalalredy->id;
            // General
            $general->engtitle = $data->engtitle;
            $general->syllabus = $data->syllabus;
            $general->audience = $data->audience; //course format
            $general->courseicon = $data->courseicon;
            $general->courseimage = $data->courseimage;
            $arraysp = $data->specializations;
            $specialdata = implode(",", $arraysp);
            $general->specializations = $specialdata;
            $general->institution = $data->institution;
            $general->coursetype = $data->coursetype;
            $general->courselevel = $data->courselevel;
//            $general->selfpaced = $data->selfpaced;
            $general->currency = $data->currency;
            $general->cost = $data->cost;
            $general->language = $data->language;
            $general->capstonecrs = $data->capstonecrs;
            if (is_siteadmin()) {
                $general->featurecourse = $data->featurecourse;
            }
            $general->courseid = $data->courseid;
            //$general->lifetime    = $data->lifetime;

            // Certificate added on 28/10/16.
            $general->instructname = $data->instructname;
            $general->instcsign = $data->instcsign;
            
            // Attendance Certificate
            $general->certificate1 = $data->certificate1;
            $general->certificatedownload1 = $data->certificatedownload1;
            $general->examrule1 = $data->examrule1;
            $general->attendancecompletion = $data->attendancecompletion;
            $general->costforattendance = $data->costforattendance;
            // Verified Certificate
            $general->certificate2 = $data->certificate2;
            $general->certificatedownload2 = $data->certificatedownload2;
            $general->examrule2 = $data->examrule2;
            $general->vattendancecompletion = $data->vattendancecompletion;
            $general->vcostforattendance = $data->vcostforattendance;

            // Exam Certificate
            $general->certificate = $data->certificate;
            $general->certificatedownload = $data->certificatedownload;
            $general->examrule = $data->examrule;
            $general->formalcredit = $data->formalcredit;
            $general->costforformalcredit = $data->costforformalcredit;
            $generalupd->credits = $data->credits;

            // Others
            $generalupd->length = $data->length;
            $generalupd->estimated = $data->estimated;

            // Added by Shiuli on 28/10/16.
            $generalupd->totalduration = $data->totalduration;

            $generalupd->durationweek = $data->durationweek;
            $generalupd->estimatedweek = $data->estimatedweek;
            $generalupd->videourl = $data->videourl;
            $generalupd->textbook = $data->textbook;
            $generalupd->whatsinside = $data->whatsinside;
            $generalupd->recommendedbackground = $data->recommendedbackground;
            $generalupd->crecruitments = $data->crecruitments;  // as learning outcomes
            $generalupd->contextid = $coursecontext->id;

            // Course date and archival.
            $generalupd->coursemode = $data->coursemode;
            $generalupd->enrolstart = $data->enrolstart;
            $generalupd->crsopen = $data->crsopen;

            if ($data->coursemode == 1) { // If course mode is tutored.
                $generalupd->crsmaintenance = $data->crsmaintenance;
                $generalupd->tutoringstart = $data->tutoringstart;
                $generalupd->tutoringstop = $data->tutoringstop;
            } else {
                $generalupd->crsmaintenance = NULL;
                $generalupd->tutoringstart = NULL;
                $generalupd->tutoringstop = NULL;
            }
            //$generalupd->selfpacedstart = $data->selfpacedstart;
            $generalupd->enrolstop = $data->enrolstop;
            $generalupd->crsclosed = $data->crsclosed;
            $generalupd->nexteditiondate = $data->nexteditiondate;
//            if ($data->enrolstop == 0) {
//                $general->enrolstop = $data->enrolstop;
//            } else {
//                $general->enrolstop = $data->enrolstop + 2556100799;
//            }
//            if ($data->crsclosed == 0) {
//                $general->crsclosed = $data->crsclosed;
//            } else {
//                $general->crsclosed = $data->crsclosed + 2556100799;
//            }
//            if ($data->nexteditiondate == 0) {
//                $general->nexteditiondate = $data->nexteditiondate;
//            } else {
//                $general->nexteditiondate = $data->nexteditiondate + 2556100799;
//            }
            $generalupd->syncstatus = $data->syncstatus;
            if (is_siteadmin()) {
                // Course Status.
                $general->coursestatus = $data->coursestatus;
                // Badge Status.
                $general->badgestatus = $data->badgestatus;
            }

            //get course category.
            $coursecat = $DB->get_record('course', array('id' => $general->courseid));
            $categ = $DB->get_record('course_categories', array('id' => $coursecat->category));

            // Badge section in EN.
            $generalupd->badgeimage = $data->badgeimage;
            $generalupd->enbadgecriteria = $data->enbadgecriteria;
            $generalupd->encompetence = $data->encompetence;
            $enarraytags = $data->enbadgetags;
            array_push($enarraytags, $categ->name);
            $entagdata = implode(",", $enarraytags);
            $generalupd->enbadgetags = $entagdata;

            // Badge section in IT.
            $generalupd->itbadgecriteria = $data->itbadgecriteria;
            $generalupd->itcompetence = $data->itcompetence;
            $itarraytags = $data->itbadgetags;
            array_push($itarraytags, $categ->name);
            $ittagdata = implode(",", $itarraytags);
            $generalupd->itbadgetags = $ittagdata;

            $DB->update_record('course_extrasettings_general', $generalupd);
        }
    } else {
        $general = new stdClass();
        // General
        $general->engtitle = $data->engtitle;
        $general->syllabus = $data->syllabus;
        $general->audience = $data->audience; //learning outcomes
        $general->courseicon = $data->courseicon;
        $general->courseimage = $data->courseimage;
        $arraysp = $data->specializations;
        $specialdata = implode(",", $arraysp);
        $general->specializations = $specialdata;
        $general->institution = $data->institution;
        $general->coursetype = $data->coursetype;
        $general->courselevel = $data->courselevel;
//        $general->selfpaced = $data->selfpaced;
        $general->currency = $data->currency;
        $general->cost = $data->cost;
        $general->language = $data->language;
        $general->capstonecrs = $data->capstonecrs;
        $general->featurecourse = $data->featurecourse;
        $general->courseid = $data->courseid;
        //$general->lifetime   = $data->lifetime;

        // Certificate added on 28/10/16.
        $general->instructname = $data->instructname;
        $general->instcsign = $data->instcsign;

        // Attendance Certificate
        $general->certificate1 = $data->certificate1;
        $general->certificatedownload1 = $data->certificatedownload1;
        $general->examrule1 = $data->examrule1;
        $general->attendancecompletion = $data->attendancecompletion;
        $general->costforattendance = $data->costforattendance;
        // Verified Certificate
        $general->certificate2 = $data->certificate2;
        $general->certificatedownload2 = $data->certificatedownload2;
        $general->examrule2 = $data->examrule2;
        $general->vattendancecompletion = $data->vattendancecompletion;
        $general->vcostforattendance = $data->vcostforattendance;
        // Exam Certificate
        $general->certificate = $data->certificate;
        $general->certificatedownload = $data->certificatedownload;
        $general->examrule = $data->examrule;
        $general->formalcredit = $data->formalcredit;
        $general->costforformalcredit = $data->costforformalcredit;
        $general->credits = $data->credits;

        // Others
        if (isset($data->length) && ($data->length != NULL)) {
            $general->length = $data->length;
        } else {
            $general->length = 0;
        }
        if (isset($data->estimated) && ($data->estimated != NULL)) {
            $general->estimated = $data->estimated;
        } else {
            $general->estimated = 0;
        }

        // Added by Shiuli on 28/10/16.
        $generalupd->totalduration = $data->totalduration;

        $general->durationweek = $data->durationweek;
        $general->estimatedweek = $data->estimatedweek;
        $general->videourl = $data->videourl;
        $general->textbook = $data->textbook;
        $general->whatsinside = $data->whatsinside;
        $general->recommendedbackground = $data->recommendedbackground;
        $general->crecruitments = $data->crecruitments; //course format
        $general->contextid = $coursecontext->id;

        // Course date and archival.
        $general->coursemode = $data->coursemode;
        $general->enrolstart = $data->enrolstart;
        $general->crsopen = $data->crsopen;
        if ($data->coursemode == 1) { // If course mode is tutored.
            $general->crsmaintenance = $data->crsmaintenance;
            $general->tutoringstart = $data->tutoringstart;
            $general->tutoringstop = $data->tutoringstop;
        } else {
            $general->crsmaintenance = NULL;
            $general->tutoringstart = NULL;
            $general->tutoringstop = NULL;
        }
        //$generalupd->selfpacedstart = $data->selfpacedstart;
        $general->enrolstop = $data->enrolstop;
        $general->crsclosed = $data->crsclosed;
        $general->nexteditiondate = $data->nexteditiondate;
//        if ($data->enrolstop == 0) {
//            $general->enrolstop = $data->enrolstop;
//        } else {
//            $general->enrolstop = $data->enrolstop + 2556100799;
//        }
//        if ($data->crsclosed == 0) {
//            $general->crsclosed = $data->crsclosed;
//        } else {
//            $general->crsclosed = $data->crsclosed + 2556100799;
//        }
//        if ($data->nexteditiondate == 0) {
//            $general->nexteditiondate = $data->nexteditiondate;
//        } else {
//            $general->nexteditiondate = $data->nexteditiondate + 2556100799;
//        }
        $general->syncstatus = $data->syncstatus;
        if (is_siteadmin()) {
            // Course Status.
            $general->coursestatus = $data->coursestatus;
            // Badge Status.
            $general->badgestatus = $data->badgestatus;
        }

        //get course category.
        $coursecat = $DB->get_record('course', array('id' => $general->courseid));
        $categ = $DB->get_record('course_categories', array('id' => $coursecat->category));

        // Badge section in EN.
        $general->badgeimage = $data->badgeimage;
        $general->enbadgecriteria = $data->enbadgecriteria;
        $general->encompetence = $data->encompetence;
        $enarraytags = $data->enbadgetags;
        //array_push($enarraytags, $categ->name);
        $entagdata = implode(",", $enarraytags);
        $general->enbadgetags = $entagdata;

        // Badge section in IT.
        $general->itbadgecriteria = $data->itbadgecriteria;
        $general->itcompetence = $data->itcompetence;
        $itarraytags = $data->itbadgetags;
        // array_push($itarraytags, $categ->name);
        $ittagdata = implode(",", $itarraytags);
        $general->itbadgetags = $ittagdata;

        $generalinsertid = $DB->insert_record('course_extrasettings_general', $general);

        // Added by Shiuli on 11/11/16.-- Add calendar event for course open and closed.
        if ($generalinsertid) {
            $courseIT = $DB->get_record('course', ['id' => $id], 'fullname, summary, visible');
            //$courseEN = $DB->get_record('course_extrasettings_general', ['courseid' => $id], 'engtitle, syllabus, crsopen, crsclosed');
            if ($data->crsclosed == 0) {
                $coursecls = 2556100799;
            } else {
                $coursecls = $data->crsclosed;
            }
            $openevent = new stdClass();
            $openevent->name = $courseIT->fullname;
            $openevent->description = strip_tags($courseIT->summary);
            $openevent->format = 8; // Custom format to identify course open event. 
            $openevent->courseid = $id;
            $openevent->groupid = 0;
            $openevent->userid = $USER->id;
            $openevent->modulename = 0;
            $openevent->instance = 0;
            $openevent->eventtype = 'course';
            $openevent->timestart = $data->crsopen;
            $openevent->visible = $courseIT->visible;
            $openevent->timeduration = 0;

            calendar_event::create($openevent); // Course open event.

            $closedevent = new stdClass();
            $closedevent->name = $courseIT->fullname;
            $closedevent->description = strip_tags($courseIT->summary);
            $closedevent->format = 9; // Custom format to identify course close event. 
            $closedevent->courseid = $id;
            $closedevent->groupid = 0;
            $closedevent->userid = $USER->id;
            $closedevent->modulename = 0;
            $closedevent->instance = 0;
            $closedevent->eventtype = 'course';
            $closedevent->timestart = $coursecls;
            $closedevent->visible = $courseIT->visible;
            $closedevent->timeduration = 0;

            calendar_event::create($closedevent); // Course closed event.
        }
        // End of calendar Event.
        
        //added by nihar
        $maxbytes = 5000000;
        file_save_draft_area_files($data->courseicon, $context->id, 'local_course_extrasettings', 'content', $data->courseicon, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));

        file_save_draft_area_files($data->courseimage, $context->id, 'local_course_extrasettings', 'content', $data->courseimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        
        // Moodle certificate
        file_save_draft_area_files($data->instcsign, $context->id, 'local_course_extrasettings', 'content', $data->instcsign, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        //end

        // Attendance certificate
        file_save_draft_area_files($data->certificatedownload1, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload1, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        //end
        // Verified Certificate
        file_save_draft_area_files($data->certificatedownload2, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload2, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        //end
        // Exam Certificate
        file_save_draft_area_files($data->certificatedownload, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        // Badge Image.
        file_save_draft_area_files($data->badgeimage, $context->id, 'local_course_extrasettings', 'content', $data->badgeimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
        //end

        if (!empty($data->instcsign) && ($data->instcsign == 'null')) {
        $uploaddir = "mod/certificate/pix/signatures";
        $filename = $mform->get_new_filename('instcsign');

        make_upload_directory($uploaddir);
        $destination = $CFG->dataroot . '/' . $uploaddir . '/' . $filename;
        if (!$mform->save_file('instcsign', $destination, true)) {
            throw new coding_exception('File upload failed');
        }
        }
       
    }
    if (isset($generalinsertid)) {
        if ($generalinsertid) { //after inserting record the page should redirect to course view page

            redirect(new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $general->courseid));
        }
    }
}
echo $OUTPUT->header();


$strinscriptions = get_string('course_extrasettings', 'local_course_extrasettings');
echo $OUTPUT->heading_with_help($strinscriptions, 'course_extrasettings', 'local_course_extrasettings', 'icon', get_string('course_extrasettings', 'local_course_extrasettings'));
echo $OUTPUT->box(get_string('course_extrasettings_info', 'local_course_extrasettings'), 'center');
$mform->display();
echo $OUTPUT->footer();
?>

<!-- All certificates -->
<script>
    $("#fitem_id_certificatedownload").hide();
    $("#fitem_id_costforformalcredit").hide();

    $("#fitem_id_certificatedownload1").hide();
    $("#fitem_id_costforattendance").hide();

    $("#fitem_id_certificatedownload2").hide();
    $("#fitem_id_vcostforattendance").hide();
    // Attendance Certificate.
    $(document).ready(function () {
        if (document.getElementById("id_certificate1").value == 1) {
            $("#fitem_id_certificatedownload1").show();
        } else {
            $("#fitem_id_certificatedownload1").hide();
        }
    });
    $("#id_certificate1").change(function () {
        if (document.getElementById("id_certificate1").value == 1) {
            $("#fitem_id_certificatedownload1").show();
        } else {
            $("#fitem_id_certificatedownload1").hide();
        }
    });
    $(document).ready(function () {
        if (document.getElementById("id_attendancecompletion").value == 1) {
            $("#fitem_id_costforattendance").show();
        } else {
            $("#fitem_id_costforattendance").hide();
        }
    });
    $("#id_attendancecompletion").change(function () {
        if (document.getElementById("id_attendancecompletion").value == 1) {
            $("#fitem_id_costforattendance").show();
        } else {
            $("#fitem_id_costforattendance").hide();
        }
    });

    // Verified Certificate.
    $(document).ready(function () {
        if (document.getElementById("id_certificate2").value == 1) {
            $("#fitem_id_certificatedownload2").show();
        } else {
            $("#fitem_id_certificatedownload2").hide();
        }
    });
    $("#id_certificate2").change(function () {
        if (document.getElementById("id_certificate2").value == 1) {
            $("#fitem_id_certificatedownload2").show();
        } else {
            $("#fitem_id_certificatedownload2").hide();
        }
    });
    $(document).ready(function () {
        if (document.getElementById("id_vattendancecompletion").value == 1) {
            $("#fitem_id_vcostforattendance").show();
        } else {
            $("#fitem_id_vcostforattendance").hide();
        }
    });
    $("#id_vattendancecompletion").change(function () {
        if (document.getElementById("id_vattendancecompletion").value == 1) {
            $("#fitem_id_vcostforattendance").show();
        } else {
            $("#fitem_id_vcostforattendance").hide();
        }
    });

    // Exam Certificate.
    $(document).ready(function () {
        if (document.getElementById("id_certificate").value == 1) {
            $("#fitem_id_certificatedownload").show();
        } else {
            $("#fitem_id_certificatedownload").hide();
        }
    });
    $("#id_certificate").change(function () {
        if (document.getElementById("id_certificate").value == 1) {
            $("#fitem_id_certificatedownload").show();
        } else {
            $("#fitem_id_certificatedownload").hide();
        }
    });
    $(document).ready(function () {
        if (document.getElementById("id_formalcredit").value == 1) {
            $("#fitem_id_costforformalcredit").show();
        } else {
            $("#fitem_id_costforformalcredit").hide();
        }
    });
    $("#id_formalcredit").change(function () {
        if (document.getElementById("id_formalcredit").value == 1) {
            $("#fitem_id_costforformalcredit").show();
        } else {
            $("#fitem_id_costforformalcredit").hide();
        }
    });
</script>

<!-- Course Dates -->
<!-- Set value for null field for T5, T6 and T7. --> 
<script type="text/javascript">
    $(document).ready(function () {
        var enrolstop_day = document.getElementById("id_enrolstop_day");
        enrolstop_day.value = "31";
        var enrolstop_month = document.getElementById("id_enrolstop_month");
        enrolstop_month.value = "12";
        var enrolstop_year = document.getElementById("id_enrolstop_year");
        enrolstop_year.value = "2050";
        var crsclosed_day = document.getElementById("id_crsclosed_day");
        crsclosed_day.value = "31";
        var crsclosed_month = document.getElementById("id_crsclosed_month");
        crsclosed_month.value = "12";
        var crsclosed_year = document.getElementById("id_crsclosed_year");
        crsclosed_year.value = "2050";
        var nexteditiondate_day = document.getElementById("id_nexteditiondate_day");
        nexteditiondate_day.value = "31";
        var nexteditiondate_month = document.getElementById("id_nexteditiondate_month");
        nexteditiondate_month.value = "12";
        var nexteditiondate_year = document.getElementById("id_nexteditiondate_year");
        nexteditiondate_year.value = "2050";
    });
</script>

<script>
    // If Course mode tutored, then show maintenanace mode.
    $(document).ready(function () {
        if (document.getElementById("id_coursemode").value == 1) {
            $("#fitem_id_crsmaintenance").show();
            $("#fitem_id_tutoringstart").show();
            $("#fitem_id_tutoringstop").show();
            $("#fitem_id_nexteditiondate").show();
        } else {
            $("#fitem_id_crsmaintenance").hide();
            $("#fitem_id_tutoringstart").hide();
            $("#fitem_id_tutoringstop").hide();
            $("#fitem_id_nexteditiondate").hide();
        }
    });

    $("#id_coursemode").change(function () {
        if (document.getElementById("id_coursemode").value == 1) {
            $("#fitem_id_crsmaintenance").show();
            $("#fitem_id_tutoringstart").show();
            $("#fitem_id_tutoringstop").show();
            $("#fitem_id_nexteditiondate").show();
        } else {
            $("#fitem_id_crsmaintenance").hide();
            $("#fitem_id_tutoringstart").hide();
            $("#fitem_id_tutoringstop").hide();
            $("#fitem_id_nexteditiondate").hide();
        }
    });

    // For T5.
    $(document).ready(function () {
        var d5 = document.getElementById("id_enrolstop_day").value;
        var m5 = document.getElementById("id_enrolstop_month").value;
        var y5 = document.getElementById("id_enrolstop_year").value;
        if ((d5 == 31) && (m5 == 12) && (y5 == 2050)) {
            $("#id_enrolstop_day").attr('disabled', 'disabled');
            $("#id_enrolstop_month").attr('disabled', 'disabled');
            $("#id_enrolstop_year").attr('disabled', 'disabled');
            $("#id_enrolstop_enabled").prop('checked', false);
        }
    });
    // For T6.
    $(document).ready(function () {
        var d6 = document.getElementById("id_crsclosed_day").value;
        var m6 = document.getElementById("id_crsclosed_month").value;
        var y6 = document.getElementById("id_crsclosed_year").value;
        if ((d6 == 31) && (m6 == 12) && (y6 == 2050)) {
            $("#id_crsclosed_day").attr('disabled', 'disabled');
            $("#id_crsclosed_month").attr('disabled', 'disabled');
            $("#id_crsclosed_year").attr('disabled', 'disabled');
            $("#id_crsclosed_enabled").prop('checked', false);
        }
    });
    // For T7.
    $(document).ready(function () {
        var d7 = document.getElementById("id_nexteditiondate_day").value;
        var m7 = document.getElementById("id_nexteditiondate_month").value;
        var y7 = document.getElementById("id_nexteditiondate_year").value;
        if ((d7 == 31) && (m7 == 12) && (y7 == 2050)) {
            $("#id_nexteditiondate_day").attr('disabled', 'disabled');
            $("#id_nexteditiondate_month").attr('disabled', 'disabled');
            $("#id_nexteditiondate_year").attr('disabled', 'disabled');
            $("#id_nexteditiondate_enabled").prop('checked', false);
        }
    });
</script>


<script>
    var systemlang = '<?php echo current_language(); ?>';
    if (systemlang === 'it') {
        $("<span class ='margbefore'>settimane</span>").insertAfter("#id_durationweek");
        $("<span class ='margbefore'>ore/settimana</span>").insertAfter("#id_estimatedweek");
    } else {
        $("<span class ='margbefore'>weeks</span>").insertAfter("#id_durationweek");
        $("<span class ='margbefore'>hours/week</span>").insertAfter("#id_estimatedweek");
    }
</script>
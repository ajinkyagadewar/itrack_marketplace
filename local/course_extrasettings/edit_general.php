<?php
require(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once ('lib.php');
require_once ('form/course_extrasettings_form.php');
/** Include eventslib.php */
require_once($CFG->libdir . '/eventslib.php');
/** Include calendar/lib.php */
require_once($CFG->dirroot . '/calendar/lib.php');


$courseid = required_param('courseid', PARAM_INT);
if ($course = $DB->get_record('course', array('id' => $courseid))) {
    $general = $DB->get_record('course_extrasettings_general', array('courseid' => $courseid));
    if ($general->courseid) {
        $dltbtn = $general->courseid;
    }
}
/// Security and access check

require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/role:assign', $context);
/// Start making page
$PAGE->set_pagelayout('course');
$editpromo = 'Edit Course Extrasettings';
$PAGE->set_title($editpromo);
$PAGE->set_url('/local/course_extrasettings/edit_general.php');
$PAGE->requires->jquery();

$mform = new course_extrasettings_form($CFG->wwwroot . '/local/course_extrasettings/edit_general.php?courseid=' . $courseid);

//Added by shiuli on 25th sep
$maxbytes = 5000000;
//added by nihar
// Course Icon.
if (isset($general->courseicon) && !empty($general->courseicon)) {
    $draftitemid0 = file_get_submitted_draft_itemid('courseicon');
    file_prepare_draft_area($draftitemid0, $context->id, 'local_course_extrasettings', 'content', $general->courseicon, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    $general->courseicon = $draftitemid0; //Comment during editing, else comment out.
}
// Courfse Image.
$draftitemid1 = file_get_submitted_draft_itemid('courseimage');
file_prepare_draft_area($draftitemid1, $context->id, 'local_course_extrasettings', 'content', $general->courseimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));

$general->courseimage = $draftitemid1; //Comment during editing, else comment out.
//end of Courfse Image.

// Instructor Signature
$draftitemidsign = file_get_submitted_draft_itemid('instcsign');
file_prepare_draft_area($draftitemidsign, $context->id, 'local_course_extrasettings', 'content', $general->instcsign, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
$general->instcsign = $draftitemidsign;
//end of Instructor Signature

// Attendance certificate
$draftitemid2 = file_get_submitted_draft_itemid('certificatedownload1');
file_prepare_draft_area($draftitemid2, $context->id, 'local_course_extrasettings', 'content', $general->certificatedownload1, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
$general->certificatedownload1 = $draftitemid2;
//end of ttendance certificate
// Verified Certificate
$draftitemid3 = file_get_submitted_draft_itemid('certificatedownload2');
file_prepare_draft_area($draftitemid3, $context->id, 'local_course_extrasettings', 'content', $general->certificatedownload2, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
$general->certificatedownload2 = $draftitemid3;
//end of Verified Certificate
// Exam Certificate
$draftitemid4 = file_get_submitted_draft_itemid('certificatedownload');
file_prepare_draft_area($draftitemid4, $context->id, 'local_course_extrasettings', 'content', $general->certificatedownload, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
$general->certificatedownload = $draftitemid4;
//end of Exam Certificate
// Badge Image.
$draftitemid5 = file_get_submitted_draft_itemid('badgeimage');
file_prepare_draft_area($draftitemid5, $context->id, 'local_course_extrasettings', 'content', $general->badgeimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
$general->badgeimage = $draftitemid5; //Comment during editing, else comment out.
// Tags.
$enbadgetagarray = explode(',', $general->enbadgetags);
$general->enbadgetags = $enbadgetagarray;
$itbadgetagarray = explode(',', $general->itbadgetags);
$general->itbadgetags = $itbadgetagarray;

$mform->set_data($general);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course/view.php?id=' . $courseid));
} else if ($data = $mform->get_data(false)) {
    global $USER, $CFG;

    if (!empty($data->instcsign) && ($data->instcsign == 'null')) {
    $uploaddir = "mod/certificate/pix/signatures";
    $filename = $mform->get_new_filename('instcsign');
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $picnm = explode(".",$filename);
    $picnmup = $picnm[0].'_'.$courseid.'.'.$extension;
    make_upload_directory($uploaddir);
    $destination = $CFG->dataroot . '/' . $uploaddir . '/' . $picnmup;
    if (!$mform->save_file('instcsign', $destination, true)) {
        throw new coding_exception('File upload failed');
    }
    }
    //modified by nihar
    $coursecontext = context_course::instance($course->id);

    //$personalcontext = context_user::instance($USER->id);
    $generalupd = new stdClass();
    $generalupd->id = $general->id;

    // General.
    $generalupd->engtitle = $data->engtitle;
    $generalupd->syllabus = $data->syllabus;
    $generalupd->audience = $data->audience; // learning Outcome
    $generalupd->courseicon = $data->courseicon;
    $generalupd->courseimage = $data->courseimage;
    $arraysp = $data->specializations;
    $specialdata = implode(",", $arraysp);
    $generalupd->specializations = $specialdata;
    $generalupd->institution = $data->institution;
    $generalupd->coursetype = $data->coursetype;
    $generalupd->courselevel = $data->courselevel;
    $generalupd->currency = $data->currency;
    $generalupd->cost = $data->cost;
    $generalupd->language = $data->language;
    $generalupd->capstonecrs = $data->capstonecrs;
    if (is_siteadmin()) {
        $generalupd->featurecourse = $data->featurecourse;
    }
    $generalupd->courseid = $data->courseid;

    // Certificate added on 28/10/16.
    $generalupd->instructname = $data->instructname;
    $generalupd->instcsign = $data->instcsign;

    // Attendance Certificate.
    $generalupd->certificate1 = $data->certificate1;
    if ($data->certificate1 == 1) {
        $generalupd->certificatedownload1 = $data->certificatedownload1;
        ?>
        <div id="fitem_id_certificatedownload1" class="fitem fitem_ffilepicker" style="display:block;"></div>
        <?php
    } else {
        $generalupd->certificatedownload1 = 0;
        ?>
        <div id="fitem_id_certificatedownload1" class="fitem fitem_ffilepicker" style="display:none;"></div>
        <?php
    }
    $generalupd->examrule1 = $data->examrule1;
    $generalupd->attendancecompletion = $data->attendancecompletion;
    if ($data->attendancecompletion == 1) {
        $generalupd->costforattendance = $data->costforattendance;
        ?>
        <div id="fitem_id_costforattendance" style="display:block;"></div>
        <?php
    } else {
        $generalupd->costforattendance = 0;
        ?>
        <div id="fitem_id_costforattendance" style="display:none;"></div>
        <?php
    }

    // Verified Certificate.
    $generalupd->certificate2 = $data->certificate2;
    if ($data->certificate2 == 1) {
        $generalupd->certificatedownload2 = $data->certificatedownload2;
        ?>
        <div id="fitem_id_certificatedownload2" style="display:block;"></div>
        <?php
    } else {
        $generalupd->certificatedownload2 = 0;
        ?>
        <div id="fitem_id_certificatedownload2" style="display:none;"></div>
        <?php
    }
    $generalupd->examrule2 = $data->examrule2;
    $generalupd->vattendancecompletion = $data->vattendancecompletion;
    if ($data->vattendancecompletion == 1) {
        $generalupd->vcostforattendance = $data->vcostforattendance;
        ?>
        <div id="fitem_id_vcostforattendance" style="display:block;"></div>
        <?php
    } else {
        $generalupd->vcostforattendance = 0;
        ?>
        <div id="fitem_id_vcostforattendance" style="display:none;"></div>
        <?php
    }

    // Exam Certificate.
    $generalupd->certificate = $data->certificate;
    if ($data->certificate == 1) {
        $generalupd->certificatedownload = $data->certificatedownload;
        ?>
        <div id="fitem_id_certificatedownload" style="display:block;"></div>
        <?php
    } else {
        $generalupd->certificatedownload = 0;
        ?>
        <div id="fitem_id_certificatedownload" style="display:none;"></div>
        <?php
    }
    $generalupd->examrule = $data->examrule;
    $generalupd->formalcredit = $data->formalcredit;
    if ($data->formalcredit == 1) {
        $generalupd->costforformalcredit = $data->costforformalcredit;
        ?>
        <div id="fitem_id_costforformalcredit" style="display:block;"></div>
        <?php
    } else {
        $generalupd->costforformalcredit = 0;
        ?>
        <div id="fitem_id_costforformalcredit" style="display:none;"></div>
        <?php
    }
    $generalupd->credits = $data->credits;

    // Others.
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
    $generalupd->crecruitments = $data->crecruitments; //as learning outcomes
    $generalupd->contextid = $coursecontext->id;

    // Course date and archival.
    $generalupd->coursemode = $data->coursemode;
    if ($data->syncstatus != 2) {
        $generalupd->enrolstart = $data->enrolstart;
        $generalupd->crsopen = $data->crsopen;
    }
    if ($data->coursemode == 1) { // If course mode is tutored.
        $generalupd->crsmaintenance = $data->crsmaintenance;
        $generalupd->tutoringstart = $data->tutoringstart;
        $generalupd->tutoringstop = $data->tutoringstop;
    } else {
        $generalupd->crsmaintenance = NULL;
        $generalupd->tutoringstart = NULL;
        $generalupd->tutoringstop = NULL;
    }
    $generalupd->enrolstop = $data->enrolstop;
    $generalupd->crsclosed = $data->crsclosed;
    $generalupd->nexteditiondate = $data->nexteditiondate;
//    if ($data->enrolstop == 0) {
//        $generalupd->enrolstop = $data->enrolstop;
//    } else {
//        $generalupd->enrolstop = $data->enrolstop + 2556100799;
//    }
//    if ($data->crsclosed == 0) {
//        $generalupd->crsclosed = $data->crsclosed;
//    } else {
//        $generalupd->crsclosed = $data->crsclosed + 2556100799;
//    }
//    if ($data->nexteditiondate == 0) {
//        $generalupd->nexteditiondate = $data->nexteditiondate;
//    } else {
//        $generalupd->nexteditiondate = $data->nexteditiondate + 2556100799;
//    }
    $generalupd->syncstatus = $data->syncstatus;
    if (is_siteadmin()) {
        // Course Status.
        $generalupd->coursestatus = $data->coursestatus;
        // Badge Status.
        $generalupd->badgestatus = $data->badgestatus;
    }

    //get course category.
    $coursecat = $DB->get_record('course', array('id' => $general->courseid));
    $categ = $DB->get_record('course_categories', array('id' => $coursecat->category));

    // Badge section in EN.
    $generalupd->badgeimage = $data->badgeimage;
    $generalupd->enbadgecriteria = $data->enbadgecriteria;
    $generalupd->encompetence = $data->encompetence;
    $enarraytags = $data->enbadgetags;
    if (in_array($categ->name, $general->enbadgetags)) {
        $entagdata = implode(",", $enarraytags);
        $generalupd->enbadgetags = $entagdata;
    } else {
        array_push($enarraytags, $categ->name);
        $entagdata1 = implode(",", $enarraytags);
        $generalupd->enbadgetags = $entagdata1;
    }

    // Badge section in IT.
    $generalupd->itbadgecriteria = $data->itbadgecriteria;
    $generalupd->itcompetence = $data->itcompetence;
    $itarraytags = $data->itbadgetags;
    if (in_array($categ->name, $general->itbadgetags)) {
        $ittagdata = implode(",", $itarraytags);
        $generalupd->itbadgetags = $ittagdata;
    } else {
        array_push($itarraytags, $categ->name);
        $ittagdata1 = implode(",", $itarraytags);
        $generalupd->itbadgetags = $ittagdata1;
    }
    $generalupdate = $DB->update_record('course_extrasettings_general', $generalupd);

    // Added by Shiuli on 11/11/16.-- Add calendar event for course open and closed.
    if ($generalupdate) {
        $courseIT = $DB->get_record('course', ['id' => $courseid], 'fullname, summary, visible');
        $courseEN = $DB->get_record('course_extrasettings_general', ['courseid' => $courseid], 'engtitle, syllabus, crsopen, crsclosed');
        $openevents = $DB->get_record('event', ['format' => 8, 'courseid' => $courseid, 'eventtype' => 'course']);
        if ($openevents) {
            $openevent = new stdClass();
            $openevent->id = $openevents->id;
            $openevent->name = $courseIT->fullname;
            $openevent->description = strip_tags($courseIT->summary);
            $openevent->format = 8; // Custom format to identify course open event.
            $openevent->courseid = $courseid;
            $openevent->groupid = 0;
            $openevent->userid = $USER->id;
            $openevent->modulename = 0;
            $openevent->instance = 0;
            $openevent->eventtype = 'course';
            $openevent->timestart = $courseEN->crsopen;
            $openevent->visible = $courseIT->visible;
            $openevent->timeduration = 0;
            $DB->update_record('event', $openevent);
        }

        $closedevents = $DB->get_record('event', ['format' => 9, 'courseid' => $courseid, 'eventtype' => 'course']);
        if ($closedevents) {
            $closedevent = new stdClass();
            $closedevent->id = $closedevents->id;
            $closedevent->name = $courseIT->fullname;
            $closedevent->description = strip_tags($courseIT->summary);
            $closedevent->format = 9; // Custom format to identify course closed event.
            $closedevent->courseid = $courseid;
            $closedevent->groupid = 0;
            $closedevent->userid = $USER->id;
            $closedevent->modulename = 0;
            $closedevent->instance = 0;
            $closedevent->eventtype = 'course';
            $closedevent->timestart = $courseEN->crsclosed;
            $closedevent->visible = $courseIT->visible;
            $closedevent->timeduration = 0;
            $DB->update_record('event', $closedevent);
        }
        
    }
    // End of calendar Event.
    
    //added by nihar
    //Course Icon.
    file_save_draft_area_files($data->courseicon, $context->id, 'local_course_extrasettings', 'content', $data->courseicon, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    //Course Image.
    file_save_draft_area_files($data->courseimage, $context->id, 'local_course_extrasettings', 'content', $data->courseimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    
    // Instrructor Signature.
    file_save_draft_area_files($data->instcsign, $context->id, 'local_course_extrasettings', 'content', $data->instcsign, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));

    // Attendance certificate
    file_save_draft_area_files($data->certificatedownload1, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload1, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    //end of Attendance certificate
    // Verified Certificate
    file_save_draft_area_files($data->certificatedownload2, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload2, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    //end of Verified Certificate
    // Exam Certificate
    file_save_draft_area_files($data->certificatedownload, $context->id, 'local_course_extrasettings', 'content', $data->certificatedownload, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    //end of Exam Certificate
    // Badge Image.
    file_save_draft_area_files($data->badgeimage, $context->id, 'local_course_extrasettings', 'content', $data->badgeimage, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));
    
}
//$coursesetting1 = $DB->get_records_sql("SELECT * FROM {eduopen_special_course_sequence}");
if (isset($generalupdate)) {
    if ($generalupdate) {

        redirect(new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $course->id));
    }
}
echo $OUTPUT->header();
$strinscriptions = get_string('course_extrasettings', 'local_course_extrasettings');
echo $OUTPUT->heading_with_help($strinscriptions, 'course_extrasettings', 'local_course_extrasettings', 'icon', get_string('course_extrasettings', 'local_course_extrasettings'));
//echo $OUTPUT->box (get_string('course_extrasettings_info', 'local_course_extrasettings'), 'center');
if ($dltbtn) {
    echo '<div class = "delete_course_extras"><button title = "Click this button to delete Course Extra Setting Details"
    id = "dlt_extra" onclick = "delete_extra(' . $dltbtn . ');">Delete Extra Settings Details</button></div>';
}
$mform->display();
echo $OUTPUT->footer();
$enrolSt = ($general->enrolstop != 0) ? 'set' : 'unset';
$crsClosed = ($general->crsclosed != 0) ? 'set' : 'unset';
$nextEditiondate = ($general->nexteditiondate != 0) ? 'set' : 'unset';
?>

<!-- All certificates -->
<script>
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
        var T5 = '<?php echo $enrolSt ?>';
        if (T5 === 'unset') {
            var enrolstop_day = document.getElementById("id_enrolstop_day");
            enrolstop_day.value = "31";
            var enrolstop_month = document.getElementById("id_enrolstop_month");
            enrolstop_month.value = "12";
            var enrolstop_year = document.getElementById("id_enrolstop_year");
            enrolstop_year.value = "2050";
        }

        var T6 = '<?php echo $crsClosed ?>';
        if (T6 === 'unset') {
            var crsclosed_day = document.getElementById("id_crsclosed_day");
            crsclosed_day.value = "31";
            var crsclosed_month = document.getElementById("id_crsclosed_month");
            crsclosed_month.value = "12";
            var crsclosed_year = document.getElementById("id_crsclosed_year");
            crsclosed_year.value = "2050";
        }

        var T7 = '<?php echo $nextEditiondate ?>';
        if (T7 === 'unset') {
            var nexteditiondate_day = document.getElementById("id_nexteditiondate_day");
            nexteditiondate_day.value = "31";
            var nexteditiondate_month = document.getElementById("id_nexteditiondate_month");
            nexteditiondate_month.value = "12";
            var nexteditiondate_year = document.getElementById("id_nexteditiondate_year");
            nexteditiondate_year.value = "2050";
        }
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

<script src="<?php echo $CFG->wwwroot . '/local/course_extrasettings/js/delete.js' ?>"></script>
<script>
    var page = {url: "<?php echo $CFG->wwwroot ?>"};
</script>
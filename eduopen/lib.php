<?php

defined('MOODLE_INTERNAL') || die();

function cinstitution_images($csettings) {
    global $CFG, $USER, $DB;

    /* $imagedt = $DB->get_records('files', array('itemid' => $csettings->logo));
      foreach ($imagedt as $dataimg) {
      $formitemname = 'logo';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/clean/pix/logo.jpg';//edited by nihar
      if (!empty($csettings->logo)) {
      $files = $fs->get_area_files($dataimg->contextid, 'user', $filearea , $csettings->logo, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($dataimg->contextid,$file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile;
      } */
    $instlogo = $DB->get_records('files', array('itemid' => $csettings->logo));
    foreach ($instlogo as $institutelogo) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($institutelogo->contextid, 'block_institution', 'content', $csettings->logo, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $csettings->logo, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

function cinstitution_logo($csettingslogo) {
    global $CFG, $USER, $DB;

    /* $imagedtlogo = $DB->get_records('files', array('itemid' => $csettingslogo->logo1));
      foreach ($imagedtlogo as $dataimg) {
      $formitemname = 'logo1';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/clean/pix/logo1.jpg';//edited by nihar
      if(!empty($csettingslogo->logo1)){
      $files = $fs->get_area_files($dataimg->contextid, 'user', $filearea ,
      $csettingslogo->logo1, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($dataimg->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile;
      } */
    //$contextmodule = context_block::instance(); //TODO-need to make dynamic--block instance id
    $instlogo1 = $DB->get_records('files', array('itemid' => $csettingslogo->logo1));
    foreach ($instlogo1 as $institutelogo1) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($institutelogo1->contextid, 'block_institution', 'content', $csettingslogo->logo1, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $csettingslogo->logo1, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

//function to get the course extra setting badge 
function course_extrasetting_badgeimage($csettingsbadge) {
    global $CFG, $USER, $DB;

    /* $imagedtlogo = $DB->get_records('files', array('itemid' => $csettingslogo->logo1));
      foreach ($imagedtlogo as $dataimg) {
      $formitemname = 'logo1';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/clean/pix/logo1.jpg';//edited by nihar
      if(!empty($csettingslogo->logo1)){
      $files = $fs->get_area_files($dataimg->contextid, 'user', $filearea ,
      $csettingslogo->logo1, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($dataimg->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile;
      } */
    //$contextmodule = context_block::instance(); //TODO-need to make dynamic--block instance id
    $courseextrasettingbadge = $DB->get_records('files', array('itemid' => $csettingsbadge->badgeimage));
    foreach ($courseextrasettingbadge as $badgeimage) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($badgeimage->contextid, 'local_course_extrasettings', 'content', $csettingsbadge->badgeimage, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $csettingsbadge->badgeimage, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

function banner_images($banner) {
    global $CFG, $DB, $USER;

    /* $banerimg = $DB->get_records('files', array('itemid' => $banner->banner));
      foreach ($banerimg as $imgbaner) {
      $formitemname = 'banner';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/clean/pix/banner.jpg';//edited by nihar
      if(!empty($banner->banner)){
      $files = $fs->get_area_files($imgbaner->contextid, 'user', $filearea , $banner->banner, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($imgbaner->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile;
      } */
    // $contextmodule = context_block::instance(); //TODO-need to make dynamic--block instance id
    $instban = $DB->get_records('files', array('itemid' => $banner->banner));
    foreach ($instban as $instbanner) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($instbanner->contextid, 'block_institution', 'content', $banner->banner, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $banner->banner, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

function specialization_images($spcimg) {
    global $CFG, $USER, $DB;
    /* $splizationimg = $DB->get_records('files', array('itemid' => $spcimg->specialization_picture));
      foreach ($splizationimg as $imgsplization) {

      $formitemname = 'specialization_picture';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/clean/pix/logo.jpg';//edited by nihar
      if (!empty($spcimg->specialization_picture)) {
      $files = $fs->get_area_files($imgsplization->contextid, 'user', $filearea ,
      $spcimg->specialization_picture, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($imgsplization->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile;
      } */
    $splizationimg = $DB->get_records('files', array('itemid' => $spcimg->specialization_picture));
    foreach ($splizationimg as $imgsplization) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($imgsplization->contextid, 'block_specialization', 'content', $spcimg->specialization_picture, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $spcimg->specialization_picture, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

function specialization_certificate($certificate) {
    global $CFG, $USER, $DB;
    //$contextid = context_user::instance($USER->id);
    //$certificate->contextid=$contextid->id;
    /* $crficateimg = $DB->get_records('files', array('itemid' => $certificate->certificate));
      foreach ($crficateimg as $imgcrficate) {
      $formitemname = 'certificate';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/clean/pix/logo.jpg';//edited by nihar
      if (!empty($certificate->certificate)) {
      $files = $fs->get_area_files($imgcrficate->contextid, 'user', $filearea ,
      $certificate->certificate, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($imgcrficate->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile;
      } */
    $certificates = $DB->get_records('files', array('itemid' => $certificate->certificate));
    foreach ($certificates as $certificateimg) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($certificateimg->contextid, 'block_specialization', 'content', $certificate->certificate, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $certificate->certificate, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

// Attendance Certificate
function certificate_images1($extrasettingscertificate1) {
    global $CFG, $DB;

    /* $formitemname = 'certificatedownload1';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      //$picsfile = $CFG->wwwroot.'/theme/elegance/pix/book.jpg';//edited by nihar
      if (!empty($extrasettingscertificate1->certificatedownload1)) {
      $files = $fs->get_area_files($extrasettingscertificate1->contextid, 'user', $filearea ,
      $extrasettingscertificate1->certificatedownload1, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($extrasettingscertificate1->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile; */
    $certificate1 = $DB->get_records('files', array('itemid' => $extrasettingscertificate1->certificatedownload1));
    foreach ($certificate1 as $certificateimg1) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($certificateimg1->contextid, 'local_course_extrasettings', 'content', $extrasettingscertificate1->certificatedownload1, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $extrasettingscertificate1->certificatedownload1, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

// Verified Certificate
function certificate_images2($extrasettingscertificate2) {
    global $CFG, $DB;

    /* $formitemname = 'certificatedownload2';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      //$picsfile = $CFG->wwwroot.'/theme/elegance/pix/book.jpg';//edited by nihar
      if (!empty($extrasettingscertificate2->certificatedownload2)) {
      $files = $fs->get_area_files($extrasettingscertificate2->contextid, 'user', $filearea ,
      $extrasettingscertificate2->certificatedownload2, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($extrasettingscertificate2->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile; */
    $certificate2 = $DB->get_records('files', array('itemid' => $extrasettingscertificate2->certificatedownload2));
    foreach ($certificate2 as $certificateimg2) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($certificateimg1->contextid, 'local_course_extrasettings', 'content', $extrasettingscertificate2->certificatedownload2, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $extrasettingscertificate2->certificatedownload2, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

// Exam Certificate
function certificate_images($extrasettingscertificate) {
    global $CFG, $DB;

    /* $formitemname = 'certificatedownload';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/elegance/pix/book.jpg';//edited by nihar
      if (!empty($extrasettingscertificate->certificatedownload)) {
      $files = $fs->get_area_files($extrasettingscertificate->contextid, 'user', $filearea ,
      $extrasettingscertificate->certificatedownload, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($extrasettingscertificate->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile; */
    $certificate = $DB->get_records('files', array('itemid' => $extrasettingscertificate->certificatedownload));
    foreach ($certificate as $certificateimg) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($certificateimg->contextid, 'local_course_extrasettings', 'content', $extrasettingscertificate->certificatedownload, 'id', false);
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if (!$filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $extrasettingscertificate->certificatedownload, $file->get_filepath(), $filename);
                return $url;
            }
        }
    }
}

function csetting_images($crsextrasettings) {
    global $CFG;
    /* $formitemname = 'courseimage';
      $filearea = 'draft';
      $filepath = '/';
      $fs = get_file_storage();
      //$picsfile = 'NA';
      $picsfile = $CFG->wwwroot.'/theme/elegance/pix/book.jpg';//edited by nihar
      if (!empty($crsextrasettings->courseimage)) {
      $files = $fs->get_area_files($crsextrasettings->contextid, 'user', $filearea ,
      $crsextrasettings->courseimage, 'id', false);
      foreach ($files as $file) {
      $picsfile = make_draftfileinstitution_url($crsextrasettings->contextid, $file->get_itemid(),
      $file->get_filepath(), $file->get_filename());
      $itemid = $file->get_itemid();
      $filename = $file->get_filename();
      }
      }
      return $picsfile; */
    /* var_dump($url); */
    $fs = get_file_storage();
    $files = $fs->get_area_files($crsextrasettings->contextid, 'local_course_extrasettings', 'content', $crsextrasettings->courseimage, 'id', false);

    foreach ($files as $file) {
        $filename = $file->get_filename();

        if (!$filename <> '.') {
            $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $crsextrasettings->courseimage, $file->get_filepath(), $filename);
            return $url;
        }
    }
}

/* added by Shiuli on 4th Oct for course page Icon */

function csetting_icons($crsextrasettings) {
    global $CFG;

    $fs = get_file_storage();
    $files = $fs->get_area_files($crsextrasettings->contextid, 'local_course_extrasettings', 'content', $crsextrasettings->courseicon, 'id', false);

    foreach ($files as $file) {
        $filename = $file->get_filename();

        if (!$filename <> '.') {
            $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $crsextrasettings->courseicon, $file->get_filepath(), $filename);
            return $url;
        }
    }
}

function make_draftfileinstitution_url($contextid, $draftid, $pathname, $filename, $forcedownload = false) {
    global $CFG, $USER;

    $urlbase = "$CFG->httpswwwroot/draftfile.php";
    return make_fileinstitution_url($urlbase, "/$contextid/user/draft/$draftid" . $pathname . $filename, $forcedownload);
}

function make_fileinstitution_url($urlbase, $path, $forcedownload = false) {
    $params = array();
    if ($forcedownload) {
        $params['forcedownload'] = 1;
    }

    $url = new moodle_url($urlbase, $params);
    $url->set_slashargument($path);
    return $url;
}

// Added by Shiuli for pathway details page.
//Lists of courses under each pathway as an array.
function course_under_pathway($pathwayid) {
    global $DB, $USER;
    $pathways = $DB->get_records('course_extrasettings_general', null, '', 'id,specializations, courseid');

    $courses = array();
    foreach ($pathways as $pathway) {
        $ids = explode(',', $pathway->specializations);
        if (in_array($pathwayid, $ids)) {
            if (!isset($courses[$pathway->courseid])) {
                $courses[] = $pathway->courseid;
            }
        }
    }
    return $courses;
}

/*
  |-----------------------
  | Enrol user in courses
  | by given pathwayid
  |---------------------
 */

function self_enrol_in_pathways($pathwayid, $userid = null) {
    global $DB, $CFG, $USER;
    if (is_null($userid)) {
        $userid = $USER->id;
    }
    $pathways = $DB->get_records('course_extrasettings_general', array('coursestatus' => 1), '', 'id,specializations, courseid');

    $courses = array();
    foreach ($pathways as $pathway) {
        $ids = explode(',', $pathway->specializations);
        if (in_array($pathwayid, $ids)) {
            if (!isset($courses[$pathway->courseid])) {
                $courses[$pathway->courseid] = $pathway->courseid;
            }
        }
    }


    if (count($courses) > 0) {
        if (enrol_is_enabled('self')) {
            $enrolledcourses = array();
            $self = enrol_get_plugin('self');
            foreach ($courses as $course) {
                $enrolinstance = null;
                if ($instances = enrol_get_instances($course, false)) {
                    foreach ($instances as $instance) {
                        if ($instance->enrol === 'self') {
                            $enrolinstance = $instance;
                            break;
                        }
                    }
                }

                if ($enrolinstance != null) {
                    $self->enrol_user($enrolinstance, $userid, 5, time(), time() + 400000000000, ENROL_USER_ACTIVE);
                    $enrolledcourses[] = $course;
                }
            }
            // entry to custom table.
            $getRec = $DB->record_exists('custom_pathway_enrolment', array('pathwayid' => $pathwayid,
                'userid' => $userid));
            if ($getRec) {
                $result = $DB->get_record('custom_pathway_enrolment', array('pathwayid' => $pathwayid,
                    'userid' => $userid));
            }
            if (!$getRec) {
                $records = new stdClass();
                $records->pathwayid = $pathwayid;
                $records->userid = $userid;
                $records->enrolmethod = 'self';
                $records->timecreated = time();
                $records->timemodified = NULL;
                $DB->insert_record('custom_pathway_enrolment', $records);
            } else {
                $records1 = new stdClass();
                $records1->id = $result->id;
                $records1->pathwayid = $result->pathwayid;
                $records1->userid = $result->userid;
                $records1->enrolmethod = $result->enrolmethod;
                $records1->timecreated = $result->timecreated;
                $records1->timemodified = time();
                $DB->update_record('custom_pathway_enrolment', $records1);
            }
            return array('status' => true, 'courses' => $enrolledcourses);
        } else {
            throw new moodle_exception('self enrolment plugin not enabled.');
        }
    }
    return array('status' => false, 'msg' => 'No Courses present under this pathway');
}

// Added by Shiuli for delete an user from the site.
function custom_user_delete($user) {
    global $USER, $CFG;
    //  if (($USER->auth == 'email' || $USER->auth == 'manual')) {
    delete_user($USER);
    require_logout();

    //  }
    return true;
}

// Lists of courses under each pathway as an array form sequence table.
function course_under_pathway_withsequence($pathwayid) {
    global $DB;
    $pathways = $DB->get_records_sql("SELECT courseid FROM {eduopen_special_course_seq}
        WHERE specializationid=$pathwayid ORDER BY sequence");

    $courses = array();
    foreach ($pathways as $pathway) {
        if (!isset($courses[$pathway->courseid])) {
            $courses[] = $pathway->courseid;
        }
    }
    return $courses;
}

// ========================== Start of Course Life Cycle ======================

/**
 * 1. This function is used in Course Details Page.
 * 2. Condition: The Archived button as to:
 * -link to the course if user is enrolled or admin or teacher and
 * enrolled users, admin and teachers can go in;
 * 3. Archived button(Enrol Me) should accessible to admin, teacher, tutor and enrolled
 *  users(student or teachers or Tutors).
 * 4. It returns the link of Course view page for admin or teacher or student
 */
function useraccess_archived_button($courseid) {
    global $DB, $USER, $CFG;
    $crscontext = context_course::instance($courseid);
    // Role admin, teacher and enrolled users.
    $teacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
    $tutorrole = $DB->get_record('role', array('shortname' => 'teacher'));
    $teacher = user_has_role_assignment($USER->id, $teacherrole->id);
    $tutor = user_has_role_assignment($USER->id, $tutorrole->id);
    // enrolled users.
    $enrolledUser = is_enrolled($crscontext, $USER->id);
    $BtnText = get_string('crsbox_archived', 'local_courselifecycle');
    if ((is_siteadmin()) || $teacher || $tutor || $enrolledUser) {
        $courselink = '<a class="arc_course btn btn-block btn-primary"
        href="' . $CFG->wwwroot . '/course/view.php?id=' . $courseid . '">'
                . $BtnText . '</a>';
    } else {
        $courselink = '<a class="arc_course btn btn-block btn-primary">'
                . $BtnText . '</a>';
    }
    return $courselink;
}

/**
 * It is used in Course Details Page-- Enrol Button.
 * @global type $DB
 * @global type $CFG
 * @param type $courseid
 * @return type in an array It will return the course link and a class to add css.
 */
function course_enrol_button($courseid) {
    global $DB, $CFG;
    $allsetting = $DB->get_record('course_extrasettings_general', array('courseid' => $courseid));

    // starts of CLC(COurse Life Cycle).
    // For All.
    // If crsclosed is not set then set it to 31/12/2050.
    if ($allsetting->crsclosed == 0) {
        $courseClosed = $allsetting->crsclosed + 2556100799;
    } else {
        $courseClosed = $allsetting->crsclosed;
    }
    if ($allsetting->enrolstop == 0) {
        $enrolStop = $allsetting->enrolstop + 2556100799;
    } else {
        $enrolStop = $allsetting->enrolstop;
    }

    // end of CLC(COurse Life Cycle).
    if ($allsetting->coursemode == 0) {
        $selfpaced = '<p class="selfed">' . get_string('selfpaced', 'theme_eduopen') . '</p>';
    } else {
        $selfpaced = '';
    }

    $crsstarttime = $DB->get_field('course', 'startdate', array('id' => $courseid), $strictness = IGNORE_MISSING);
    $timeafteramonth = time() + (30 * 24 * 60 * 60); // date after 1month from current date
    //echo get_student($id, $USER->id);
    $crscontext = context_course::instance($courseid);
    $enrolled = is_enrolled($crscontext);
    $cstrdate = date("F d", $allsetting->crsopen);
    if ($crsstarttime > $timeafteramonth) { // upcoming
        $cls = 'up-org';
        $BtnText = get_string('cs_start_u', 'theme_eduopen') . '!'; //upcoming
        if ($enrolled) {
            $BtnText2 = get_string('Starts', 'theme_eduopen') . '&nbsp;' . $cstrdate;
        } else {
            $BtnText2 = get_string('enrolme_now', 'theme_eduopen');
        }
        $HrefLink = '<a href="' . $CFG->wwwroot . '/course/view.php?id=' . $courseid . '" class="btn btn-block btn-primary">' . $BtnText . '
                    <p class="btn2">' . $BtnText2 . '</p>' . $selfpaced . '</a>';
    } else if ($crsstarttime > time() && $crsstarttime < $timeafteramonth) { //Coming soon
        $cls = 'cs-yel';
        $BtnText = get_string('cs_start_cs', 'theme_eduopen'); //cooming soon
        if ($enrolled) {
            $BtnText2 = get_string('Starts', 'theme_eduopen') . '&nbsp;' . $cstrdate;
        } else {
            $BtnText2 = get_string('enrolme_now', 'theme_eduopen');
        }
        $HrefLink = '<a href="' . $CFG->wwwroot . '/course/view.php?id=' . $courseid . '" class="btn btn-block btn-primary">' . $BtnText . '
                    <p class="btn2">' . $BtnText2 . '</p>' . $selfpaced . '</a>';
    } else if ($crsstarttime < time()) {//current
        $cls = 'cur-grn';
        $BtnText2 = '';
        if ($enrolled) {
            $BtnText = get_string('enrolled_continue', 'theme_eduopen'); //current
        } else {
            $BtnText = get_string('enrolme_course', 'theme_eduopen'); //current
        }
        $HrefLink = '<a href="' . $CFG->wwwroot . '/course/view.php?id=' . $courseid . '"
                    class=" btn btn-block btn-primary">'
                . $BtnText . '<p class="btn2">' . $BtnText2 . '</p>' . $selfpaced . '</a>';
    }
    if ($allsetting->coursestatus == 0) {
        $cls = 'archived_course';
        $HrefLink = useraccess_archived_button($courseid);
    }
    return array('link' => $HrefLink, 'class' => $cls);
}

/**
 * 1a. This function is used in Course Details Page-Course Status in "Course At a Glance".
 * 1b. Also used in Pathway Details page. --Course Status in Course Tab
 * 2. It returns the Course status and related class to add some css. 
 */
function coursedetails_course_status($courseid) {
    global $CFG, $DB;
    $allsetting = $DB->get_record('course_extrasettings_general', array('courseid' => $courseid));
    // starts of CLC(COurse Life Cycle).
    // For All.
    // If crsclosed is not set then set it to 31/12/2050.
    if ($allsetting->crsclosed == 0) {
        $courseClosed = $allsetting->crsclosed + 2556100799;
    } else {
        $courseClosed = $allsetting->crsclosed;
    }
    if ($allsetting->enrolstop == 0) {
        $enrolStop = $allsetting->enrolstop + 2556100799;
    } else {
        $enrolStop = $allsetting->enrolstop;
    }

    $conditionA = (time() < $allsetting->enrolstart);
    $conditionB = ((time() > $allsetting->enrolstart) && (time() < $allsetting->crsopen));
    if ($allsetting->coursemode == 1) {
        $conditionC = ((time() > $allsetting->tutoringstart) && (time() < $allsetting->tutoringstop));
        $conditionD = (($allsetting->crsmaintenance == 1) && (time() > $allsetting->tutoringstop) && (time() < $enrolStop));
        $conditionE = (($allsetting->crsmaintenance == 0) && (time() > $allsetting->tutoringstop) && (time() < $enrolStop));
    }
    $conditionF = (($allsetting->coursemode == 0) && (time() > $allsetting->crsopen) && (time() < $courseClosed));
    $conditionG = ((time() > $enrolStop) && (time() < $courseClosed));
    $conditionH = (time() > $courseClosed);
if ($allsetting->coursestatus == 1) {
    if ($conditionA) {
        $cstatus = get_string('crsstatus_condiA', 'local_courselifecycle');
        $cls = '';
    } else if ($conditionB) {
        $cstatus = get_string('crsstatus_condiB', 'local_courselifecycle');
        $cls = '';
    } else if (isset($conditionC) && !empty($conditionC)) {
        $cstatus = get_string('crsstatus_condiC', 'local_courselifecycle');
        $cls = '';
    } else if ($conditionH) {
        $cstatus = get_string('crsstatus_condiH', 'local_courselifecycle');
        $cls = 'arc_course';
    } else if (isset($conditionD) && !empty($conditionD)) {
        $cstatus = get_string('crsstatus_condiD', 'local_courselifecycle');
        $cls = '';
    } else if (isset($conditionE) && !empty($conditionE)) {
        $cstatus = get_string('crsstatus_condiE', 'local_courselifecycle');
        $cls = '';
    } else if ($conditionF) {
        $cstatus = get_string('crsstatus_condiF', 'local_courselifecycle');
        $cls = '';
    } else if ($conditionG) {
        $cstatus = get_string('crsstatus_condiG', 'local_courselifecycle');
        $cls = '';
    }
    } else {
        $cstatus = get_string('crsstatus_condiH', 'local_courselifecycle');
        $cls = 'arc_course';
    }
    // end of CLC(COurse Life Cycle).
    return array('coursestatus' => $cstatus, 'class' => $cls);
}

// For Course details page enrollment button.
function single_course_enrolment($courseid) {
    global $DB, $CFG;
    $crscontext = context_course::instance($courseid);
    $details = $DB->get_record('course_extrasettings_general', array('courseid' => $courseid));
    // Course life cycle.
    $coursemode = $details->coursemode;
    $crsmaintenance = $details->crsmaintenance;
    $T0 = $details->enrolstart;
    $T1 = $details->crsopen;
    $T2 = $details->tutoringstart;
    $T3 = $details->tutoringstop;
    // If enrolstop, crsclosed is not set then set it to 31/12/2050.
    if ($details->enrolstop == 0) {
        $enrolStop = $details->enrolstop + 2556100799;
    } else {
        $enrolStop = $details->enrolstop;
    }
    if ($details->crsclosed == 0) {
        $courseClosed = $details->crsclosed + 2556100799;
    } else {
        $courseClosed = $details->crsclosed;
    }

    if ($details->coursestatus == 1) {
        // Before T0.
        if (time() < $T0) {
            // Admins & Teachers &Tutors & Course Creators only, not to public or Student.
            if (visible_roles() || is_siteadmin()) {
                $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
                $nolinks = '';
            } else {
                $crslink = '';
                $nolinks = 'no-crslinks';
            }
            $bgcolor = 'cenrol_red ' . $nolinks;
            $pText = get_string('crsenrol_T0_ptext', 'local_courselifecycle');
            // Checked logged in and enrolled.
            if (isloggedin() && is_enrolled($crscontext)) {
                $sText = '';
            } else {
                $sText = get_string('crsenrol_T0_stext', 'local_courselifecycle') . ' ' .
                        user_cumstom_date($T0);
            }
        }
        // From T0 to T1
        if ((time() > $T0) && (time() < $T1)) {
            $bgcolor = 'cenrol_orange';
            $pText = get_string('crsenrol_T0T1_ptext', 'local_courselifecycle');
            // Checked logged in and enrolled.
            if (isloggedin() && is_enrolled($crscontext)) {
                $sText = get_string('crsenrol_T0T1_stext_loggedin_alreadyenrolled', 'local_courselifecycle');
            } else {
                $sText = get_string('crsenrol_stext_enrolnow', 'local_courselifecycle');
            }
            // Student can only enrol, cannot go to course page.
            if (visible_roles() || is_siteadmin()) {
                $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
            } else {
                $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
            }
        }

        // If Mode = Self Paced.
        if ($coursemode == 0) {
            // From T1 to T5.
            if ((time() > $T1) && (time() < $enrolStop)) {
                $bgcolor = 'cenrol_green';
                $pText = get_string('crsenrol_ptext_selfpaced', 'local_courselifecycle');
                // Checked logged in and enrolled.
                if (isloggedin() && is_enrolled($crscontext)) {
                    $sText = get_string('crsenrol_stext_welcomeback', 'local_courselifecycle');
                } else {
                    $sText = get_string('crsenrol_stext_enrolnow', 'local_courselifecycle');
                }
                // Link to course.
                $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
            }
        } else { //If Mode = Tutored.
            // From T1 to T3.
            if ((time() > $T1) && (time() < $T3)) {
                $bgcolor = 'cenrol_green';
                $pText = get_string('crsenrol_ptext_active', 'local_courselifecycle');
                // Checked logged in and enrolled.
                if (isloggedin() && is_enrolled($crscontext)) {
                    $sText = get_string('crsenrol_stext_welcomeback', 'local_courselifecycle');
                } else {
                    $sText = get_string('crsenrol_stext_enrolnow', 'local_courselifecycle');
                }
                // Link to course.
                $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
            }
            // From T3 to T5.
            if ((time() > $T3) && (time() < $enrolStop)) {
                $bgcolor = 'cenrol_Lgreen';
                $pText = get_string('crsenrol_ptext_selfpaced', 'local_courselifecycle');
                // Checked logged in and enrolled.
                if (isloggedin() && is_enrolled($crscontext)) {
                    $sText = get_string('crsenrol_stext_welcomeback', 'local_courselifecycle');
                } else {
                    $sText = get_string('crsenrol_stext_enrolnow', 'local_courselifecycle');
                }
                // Link to course.
                $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
            }
        }

        // From T5 to T6.
        if ((time() > $enrolStop) && (time() < $courseClosed)) {
            $bgcolor = 'cenrol_orange';
            $pText = get_string('crsenrol_T5T6_ptext', 'local_courselifecycle');
            // Checked logged in and enrolled.
            if (isloggedin() && is_enrolled($crscontext)) {
                $sText = get_string('crsenrol_T5T6_stext_loggedin', 'local_courselifecycle');
            } else {
                $sText = get_string('crsenrol_stext_notloggedin_timeup', 'local_courselifecycle');
            }
            // Link to course.
            $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
        }

        // After T6 & !T7.
        if ($details->nexteditiondate == 0) {
            if (time() > $courseClosed) {
                $bgcolor = 'cenrol_red';
                $pText = get_string('crsenrol_Archived', 'local_courselifecycle');
                // Checked logged in and enrolled.
                if (isloggedin() && is_enrolled($crscontext)) {
                    $sText = get_string('crsenrol_stext_loggedin_lectureonly', 'local_courselifecycle');
                } else {
                    $sText = get_string('crsenrol_stext_notloggedin_timeup', 'local_courselifecycle');
                }
                // Only enrolled & Special
                if (visible_roles() || is_siteadmin() && is_enrolled($crscontext)) {
                    $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
                } else {
                    $crslink = $CFG->wwwroot . '/login';
                }
            }
        } else if ((time() > $courseClosed) && (time() < $details->nexteditiondate)) {
            $bgcolor = 'cenrol_red';
            $pText = get_string('crsenrol_Archived', 'local_courselifecycle');
            // Checked logged in and enrolled.
            if (isloggedin() && is_enrolled($crscontext)) {
                $sText = get_string('crsenrol_stext_loggedin_lectureonly', 'local_courselifecycle');
            } else {
                $sText = get_string('crsenrol_stext_notloggedin_timeup', 'local_courselifecycle');
            }
            // Student can only enrol, cannot go to course page.
            if (visible_roles() || is_siteadmin() && is_enrolled($crscontext)) {
                $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
            } else {
                $crslink = $CFG->wwwroot . '/login';
            }
        }
    } else {
        $bgcolor = 'cenrol_red';
        $pText = get_string('crsenrol_Archived', 'local_courselifecycle');
        // Checked logged in and enrolled.
        if (isloggedin() && is_enrolled($crscontext)) {
            $sText = get_string('crsenrol_stext_loggedin_lectureonly', 'local_courselifecycle');
        } else {
            $sText = get_string('crsenrol_stext_notloggedin_timeup', 'local_courselifecycle');
        }
        // Student can only enrol, cannot go to course page.
        if (visible_roles() || is_siteadmin() && is_enrolled($crscontext)) {
            $crslink = $CFG->wwwroot . '/course/view.php?id=' . $courseid;
        } else {
            $crslink = $CFG->wwwroot . '/login';
        }
    }
    return array('bgcolor' => $bgcolor, 'primaryText' => $pText,
        'secondaryText' => $sText, 'courselink' => $crslink);
}

// ========================== End Course Life Cycle. ==========================
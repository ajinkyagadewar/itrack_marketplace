<?php

defined('MOODLE_INTERNAL') || die();

function local_course_extrasettings_extend_settings_navigation($settingsnav, $context) {
    global $CFG, $COURSE, $courseid, $DB;
    $course = $DB->get_record('course', array('id' => $COURSE->id));
    //require_course_login($course);
    $context = context_course::instance($course->id);
    if (has_capability('moodle/role:assign', $context)) {
        $coursenode = $settingsnav->get('courseadmin');
        if ($coursenode) {
            $url = "$CFG->wwwroot/local/course_extrasettings/course_extrasettings.php?courseid=$COURSE->id";
            $coursenode->add(get_string('coursepromo', 'local_course_extrasettings'), $url, navigation_node::TYPE_CONTAINER, null, 'coursepromo', new pix_icon('i/badge', get_string('coursepromo', 'local_course_extrasettings')));
        }
    }
}

// Cron job.
function local_course_extrasettings_cron() {
    // Delete course extra settings on course deletion.
    global $DB;
    $general = $DB->get_records('course_extrasettings_general');
    /* If there is no record in course table then delete course extra settings
     *  records by using courseid.
     */
    foreach ($general as $exgeneral) {
        $extracourseid = $exgeneral->courseid;
        $crsrecExist = $DB->record_exists_sql("SELECT * FROM {course} WHERE id=$extracourseid");
        if (!$crsrecExist) {
            echo 'Extra records with courseid ' . $extracourseid . ' has been deleted';
            $DB->delete_records('course_extrasettings_general', array('courseid' => $extracourseid));
        }
    }

    /* If there is no record in block_eduopen_master_special table then replace column(spaecializations) with 'none' value in
     *  mdl_course_extrasettings_general table .
     */
    foreach ($general as $exgeneral) {
        $extrapathwayid = $exgeneral->specializations;
        $genid = $exgeneral->id;
        if ($extrapathwayid != 'none') {
            $explode = explode(',', $extrapathwayid);
            foreach ($explode as $explodepathid) {
                $pathrecExist = $DB->record_exists_sql("SELECT * FROM {block_eduopen_master_special} WHERE 
                    id=$explodepathid AND status='1'");
                if (!$pathrecExist) {
                    $length = count($explode);
                    $key = array_search($explodepathid, $explode);
                    if ($length > 1) {
                        unset($explode[$key]);
                        $replaceexp = implode(',', $explode);
                        echo 'Extra records with pathwayid ' . $extrapathwayid . ' has been replaced by ' . $replaceexp . ' value';
                        $DB->execute("UPDATE {course_extrasettings_general} SET specializations='$replaceexp' WHERE id=$genid");
                    } else {
                        echo 'Extra records with pathwayid ' . $extrapathwayid . ' has been replaced by none value';
                        $DB->execute("UPDATE {course_extrasettings_general} SET specializations='none' WHERE id=$genid");
                    }
                }
            }
        }
    }

    /* If there is no record in block_eduopen_master_inst table then replace column(institution) with '' value in
     *  mdl_course_extrasettings_general table .
     */
   /* foreach ($general as $exgeneral) {
        $extrainstid = $exgeneral->institution;
        $generalid = $exgeneral->id;
        $instrecExist = $DB->record_exists_sql("SELECT * FROM {block_eduopen_master_inst} WHERE id=$extrainstid");
        if (!$instrecExist) {
            echo 'Extra records with institution id ' . $extrainstid . ' has been replaced by NULL';
            $DB->execute("UPDATE {course_extrasettings_general} SET institution='' WHERE id=$generalid");
        }
    } */
}

/* get all extra settings of a course from course_extrasettings tables  */

function find_settings_course($cid) {
    global $USER, $DB, $CFG;
    $lastrateq = "SELECT * from {course_extrasettings_general} where courseid = $cid ";
    $lastrate = $DB->get_record_sql($lastrateq);
    if ($lastrate) {
        return $lastrate;
    }
}

function get_course_contents($courseid) {
    global $DB;
    $csectionq = "SELECT section, name, summary, sequence from {course_sections} where sequence !='' and course=$courseid";
    $recsec = $DB->get_records_sql($csectionq);
    $moduleinfo = get_fast_modinfo($courseid);
//var_dump($recsec);
//exit;
    foreach ($recsec as $rs) {
        $sectionname = $rs->name;
        $moduleidarray = $rs->sequence;
        $moduleidstring = explode(',', $moduleidarray);
        $output = "<li class='col-md-6'>";
        $output .= "<h4>";
        $output .= $sectionname;
        $output .= "</h4>";
        $output .= "<ul>";
        for ($i = 0; $i < count($moduleidstring); $i++) {
            $moduleid = $moduleidstring[$i];
            $modulers = $moduleinfo->get_cm($moduleid);
            $output .= "<li>";
            $output .= $modulers->name;
            $output .= "</li>";
        }
        $output .= "</ul>";
        $output .= "</li>";
        echo $output;
    }
}

function local_course_extrasettings_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_COURSE) {
        send_file_not_found();
    }

// If block is in course context, then check if user has capability to access course.
    if ($context->get_course_context(false)) {
//require_course_login($course);
    } else if ($CFG->forcelogin) {
//require_login();
    } else {
// Get parent context and see if user have proper permission.
        $parentcontext = $context->get_parent_context();
        if ($parentcontext->contextlevel === CONTEXT_COURSECAT) {
// Check if category is visible and user can view this category.
            $category = $DB->get_record('course_categories', array('id' => $parentcontext->instanceid), '*', MUST_EXIST);
            if (!$category->visible) {
                require_capability('moodle/category:viewhiddencategories', $parentcontext);
            }
        }
// At this point there is no way to check SYSTEM or USER context, so ignoring it.
    }

    if ($filearea !== 'content') {
        send_file_not_found();
    }

    $fs = get_file_storage();

    $filename = array_pop($args);
//$filepath = $args ? '/'.implode('/', $args).'/' : '/';
    $filepath = '/';
    $itemid = $args['0']; //added by nihar -to get the item id

    if (!$file = $fs->get_file($context->id, 'local_course_extrasettings', 'content', $itemid, $filepath, $filename) or $file->is_directory()) {

        send_file_not_found();
    }

    $managerobj = new \core\session\manager();
    $managerobj->write_close();

    send_stored_file($file, 60 * 60, 0, $forcedownload, $options);
}

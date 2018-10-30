<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * specialization library
 *
 * @package    block_specialization
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 *
 */


function add_specialization($data) {
    global $DB;
    $record1 = new stdClass();
    $record1->name   = $data->name;
    $record1->specialization_picture    = $data->specialization_picture;
    $record1->overview  = $data->overview;
    $record1->overview_video  = $data->overview_video;
    $record1->certificate  = $data->certificate;
    $record1->background   = $data->background;
    $record1->status   = $data->status;
    $record1->title1   = $data->title1;
    $record1->text1   = $data->text1;
    $record1->title2   = $data->title2;
    $record1->text2   = $data->text2;
    $record1->degree   = $data->degree;
    $record1->deg_currency   = $data->deg_currency;
    $record1->category   = $data->category;
    $tmpdegree = trim($data->degree);
    if ($tmpdegree) {
        $record1->deg_title   = $data->deg_title;
        $record1->deg_cost   = $data->deg_cost;
    } else {
        $record1->deg_title   = '';
        $record1->deg_cost   = 0;
    }
    $record1->pathlanguage   = $data->pathlanguage;
   // $templang = $data->pathlanguage;
   // if ($templang != 'en') {
        $record1->localname   = $data->localname;
        $record1->localoverview   = $data->localoverview;
   /* } else {
        $record1->localname   = '';
        $record1->localoverview   = '';
    } */
    $record1->featuredpathway = $data->featuredpathway;
    $record1->pathwaystatus = $data->pathwaystatus;

    $instid = $DB->insert_record('block_eduopen_master_special', $record1);
    return $instid;
}

function update_specialization($data) {
    global $DB;
    //var_dump($data);
    //exit();
    $record1 = new stdClass();
    $record1->id = $data->updateid;
    $record1->name   = $data->name;
    $record1->specialization_picture  = $data->specialization_picture;
    $record1->overview   = $data->overview;
    $record1->overview_video  = $data->overview_video;
    $record1->certificate  = $data->certificate;
    $record1->background   = $data->background;
    $record1->status   = $data->status;
    $record1->title1   = $data->title1;
    $record1->text1   = $data->text1;
    $record1->title2   = $data->title2;
    $record1->text2   = $data->text2;
    $record1->degree   = $data->degree;
    $record1->deg_currency   = $data->deg_currency;
    $record1->category   = $data->category;
    $tmpdegree = trim($data->degree);
    if ($tmpdegree) {
        $record1->deg_title   = $data->deg_title;
        $record1->deg_cost   = $data->deg_cost;
    } else {
        $record1->deg_title   = '';
        $record1->deg_cost   = 0;
    }
    $record1->pathlanguage   = $data->pathlanguage;
    //$templang = $data->pathlanguage;
    //if ($templang != 'en') {
        $record1->localname   = $data->localname;
        $record1->localoverview   = $data->localoverview;
    /* } else {
        $record1->localname   = '';
        $record1->localoverview   = '';
    } */
    $record1->featuredpathway = $data->featuredpathway;
    $record1->pathwaystatus = $data->pathwaystatus;
    //var_dump($record1);
    $instid1 = $DB->update_record('block_eduopen_master_special', $record1);
    return $instid1;
}

function block_specialization_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }

    // If block is in course context, then check if user has capability to access course.
    if ($context->get_course_context(false)) {
        require_course_login($course);
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
    $itemid  = $args['0']; //added by nihar -to get the item id
   
    if (!$file = $fs->get_file($context->id, 'block_specialization', 'content', $itemid, $filepath, $filename)
     or $file->is_directory()) {
       
        send_file_not_found();
    }

    if ($parentcontext = context::instance_by_id($birecord_or_cm->parentcontextid, IGNORE_MISSING)) {
        if ($parentcontext->contextlevel == CONTEXT_USER) {
            // force download on all personal pages including /my/
            //because we do not have reliable way to find out from where this is used
            $forcedownload = true;
        }
    } else {
        // weird, there should be parent context, better force dowload then
        $forcedownload = true;
    }

    $managerobj = new \core\session\manager();
    $managerobj->write_close();
       
    send_stored_file($file, 60*60, 0, $forcedownload, $options);
}
   
   
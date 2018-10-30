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
 * institution library
 *
 * @package    block_institution
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 *
 */


function add_institution($data) {
    global $DB;
    $record1 = new stdClass();
    $record1->name   = $data->name;
    $record1->itname   = $data->itname;
    $record1->description  = $data->description;
    $record1->itdescription  = $data->itdescription;
    $record1->banner   = $data->banner;
    $record1->logo  = $data->logo;
    $record1->logo1  = $data->logo1;
    $record1->country  = $data->country;
    $record1->address  = $data->address;
    $record1->refferencename  = $data->refferencename;
    
    $record1->insttype  = $data->insttype;
    
    $record1->web   = $data->web;
    $record1->facebook   = $data->facebook;
    $record1->twitter   = $data->twitter;
    $record1->youtube   = $data->youtube;
    $record1->map1   = $data->map1;

    $instid = $DB->insert_record('block_eduopen_master_inst', $record1);
    return $instid;
}

function update_institution($data) {
    global $DB;
    
    $record = new stdClass();
    $record->id = $data->updateid;
    $record->name   = $data->name;
    $record->itname   = $data->itname;
    $record->description  = $data->description;
    $record->itdescription  = $data->itdescription;
    $record->banner   = $data->banner;
    $record->logo  = $data->logo;
    $record->logo1  = $data->logo1;
    $record->country  = $data->country;
    $record->address  = $data->address;
    $record->refferencename  = $data->refferencename;
    $record->insttype  = $data->insttype;
    $record->web   = $data->web;
    $record->facebook   = $data->facebook;
    $record->twitter   = $data->twitter;
    $record->youtube   = $data->youtube;
    $record->map1   = $data->map1;
    
    $instid1 = $DB->update_record('block_eduopen_master_inst', $record);
    return $instid1;

}

function block_institution_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload,
array $options = array()) {
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
   
    if (!$file = $fs->get_file($context->id, 'block_institution', 'content', $itemid,
    $filepath, $filename) or $file->is_directory()) {
       
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
   

<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Handles uploading files
 *
 * @package    local_buykart
 * @copyright  Arjun Singh <arjunsingh@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/local/buykart/upload_image_form.php');

require_login();

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$struploadimage = get_string('uploadimage', 'local_buykart');

$PAGE->set_url('/admin/settings.php', array('section' => 'local_buykart_invoice_settings'));
$PAGE->set_pagetype('admin-setting-local_buykart_invoice_settings');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->set_title($struploadimage);
$PAGE->set_heading($SITE->fullname);
$PAGE->navbar->add($struploadimage);

$upload_form = new local_buykart_upload_image_form();

if ($upload_form->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php?section=local_buykart_invoice_settings'));
} else if ($data = $upload_form->get_data()) {
    $uploaddir = "local/buykart/pix";
    $filename = $upload_form->get_new_filename('invoiceimage');
    make_upload_directory($uploaddir);
    $destination = $CFG->dataroot . '/' . $uploaddir . '/' . $filename;
    $destination1 = $CFG->dataroot . '/' . $uploaddir . '/*.jpg';
    $images = glob($destination1);  
    if($images){
        foreach($images as $image){
            if(is_file($image))
                unlink($image);
            }
    }

    if (!($upload_form->save_file('invoiceimage', $destination,true))) {
        throw new coding_exception('File upload failed');
    }
    redirect(new moodle_url('/admin/settings.php?section=local_buykart_invoice_settings'), get_string('changessaved'));
}

echo $OUTPUT->header();
echo $upload_form->display();
echo $OUTPUT->footer();

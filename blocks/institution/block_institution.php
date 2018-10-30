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
 * @package block_institution
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * The institution block
 */

//This block should only visible to Admin

//if (is_siteadmin()) { //It will give 'block outdated' error during installation
    class block_institution extends block_base  {
        function init() {
            $this->title = get_string('pluginname', 'block_institution');
        }

        /* function user_can_addto($page) {
            // Don't allow people to add the block if they can't even use it
            //if (!has_capability('moodle/institution:add', $page->context)) {
            //   return false;
            /}
            return parent::user_can_addto($page);
        }

        function user_can_edit() {
            // Don't allow people to edit the block if they can't even use it
            if (!has_capability('moodle/institution:add',
                context::instance_by_id($this->instance->parentcontextid))) {
                return false;
            }
            return parent::user_can_edit();
         } */

        function has_config() {
            return true;
        }

        function get_content() {
            global $USER, $CFG, $DB, $OUTPUT;
            if ($this->content !== NULL) {
                return $this->content;
            }

            $this->content = new stdClass;
            $this->content->text = '';
            $this->content->footer = '';

            if (empty($this->instance)) {
                return $this->content;
            }
            //display edit and view button if user is admin
            $add = get_string('add', 'block_institution');
            $view = get_string('view', 'block_institution');
            $blockcontextid = $this->context->id;

            $this->content->text = "<a href='".$CFG->wwwroot."/blocks/institution/add_institution.php?context=".$blockcontextid."'>".$add."</a><br>";
            $this->content->text .= "<a href='".$CFG->wwwroot."/blocks/institution/view_institution.php?context=".$blockcontextid."'>".$view."</a><br>";
            //$this->content->footer .= "<a href='".$CFG->wwwroot."/blocks/institution/view_institution.php'>".$view."</a><br>";
            //$this->content->text .= "<a href='".$CFG->wwwroot."/blocks/institution/mycredits.php'>".$mycredits."</a><br>";
            return $this->content;
        }
        public function applicable_formats() {
            return array('admin-index' => true);
        }
    }
//}

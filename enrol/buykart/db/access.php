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
 * Capabilities for buykart enrolment plugin.
 *
 * @package    enrol_buykart
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

	/* Add, edit or remove buykart enrol instance. */
	'enrol/buykart:config' => array(
		'captype' => 'write',
		'contextlevel' => CONTEXT_COURSE,
		'archetypes' => array(
			'manager' => CAP_ALLOW,
		),
	),

	/* Enrol anybody. */
	'enrol/buykart:enrol' => array(
		'captype' => 'write',
		'contextlevel' => CONTEXT_COURSE,
		'archetypes' => array(
			'manager' => CAP_ALLOW,
			'editingteacher' => CAP_ALLOW,
		),
	),

	/* Manage enrolments of users. */
	'enrol/buykart:manage' => array(
		'captype' => 'write',
		'contextlevel' => CONTEXT_COURSE,
		'archetypes' => array(
			'manager' => CAP_ALLOW,
			'editingteacher' => CAP_ALLOW,
		),
	),

	/* Unenrol anybody (including self) - watch out for data loss. */
	'enrol/buykart:unenrol' => array(
		'captype' => 'write',
		'contextlevel' => CONTEXT_COURSE,
		'archetypes' => array(
			'manager' => CAP_ALLOW,
			'editingteacher' => CAP_ALLOW,
		),
	),

	/* Unenrol self - watch out for data loss. */
	'enrol/buykart:unenrolself' => array(
		'captype' => 'write',
		'contextlevel' => CONTEXT_COURSE,
		'archetypes' => array(
		),
	),

);

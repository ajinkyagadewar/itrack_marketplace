<?php
// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * Manage License block caps.
 *
 * @package    block_manage_license
 * @copyright  moodleofindia  {@link  http://www.moodleofindia.com}
 * @license     http://www.moodleofindia.comcopyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    'block/paypal_promo:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/paypal_promo:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' =>  CAP_PROHIBIT,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    'block/paypal_promo:viewlist' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'user' => CAP_ALLOW,
            'guest' =>  CAP_PROHIBIT,
            'student' =>  CAP_PROHIBIT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    )
);

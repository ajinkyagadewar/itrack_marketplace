<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function xmldb_local_educoupons_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2012112923) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('educoupons');
        $field = new xmldb_field('couponid', XMLDB_TYPE_TEXT, null, null, null, null, null, 'deleted');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012112923, 'local', 'educoupons');
    }

    if ($oldversion < 2012112925) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('edu_couponcode');
        $field = new xmldb_field('cpstatus', XMLDB_TYPE_INTEGER, '10', null, null, null, 1, '0');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012112925, 'local', 'educoupons');
    }

    if ($oldversion < 2012112926) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('educoupons');
        $field = new xmldb_field('couponid', XMLDB_TYPE_TEXT, null, null, null, null, null, '0');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012112926, 'local', 'educoupons');
    }

    return true;
}

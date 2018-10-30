<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function xmldb_local_course_extrasettings_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    /// Add a new column newcol 
    if ($oldversion < 2012012422) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field1 = new xmldb_field('encompetence', XMLDB_TYPE_TEXT, null, null, null, null, null, 'courseicon');
        $field2 = new xmldb_field('enbadgecriteria', XMLDB_TYPE_TEXT, null, null, null, null, null, 'encompetence');
        $field3 = new xmldb_field('enbadgetags', XMLDB_TYPE_CHAR, '1000', null, null, null, null, 'enbadgecriteria');
        $field4 = new xmldb_field('itcompetence', XMLDB_TYPE_TEXT, null, null, null, null, null, 'enbadgetags');
        $field5 = new xmldb_field('itbadgecriteria', XMLDB_TYPE_TEXT, null, null, null, null, null, 'itcompetence');
        $field6 = new xmldb_field('itbadgetags', XMLDB_TYPE_CHAR, '1000', null, null, null, null, 'itbadgecriteria');

        // Conditionally launch add field encompetence.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }
        if (!$dbman->field_exists($table, $field4)) {
            $dbman->add_field($table, $field4);
        }
        if (!$dbman->field_exists($table, $field5)) {
            $dbman->add_field($table, $field5);
        }
        if (!$dbman->field_exists($table, $field6)) {
            $dbman->add_field($table, $field6);
        }

        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012422, 'local', 'course_extrasettings');
    }
    if ($oldversion < 2012012424) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('badgeimage', XMLDB_TYPE_CHAR, '30', null, null, null, null, 'courseicon');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012424, 'local', 'course_extrasettings');
    }
    if ($oldversion < 2012012425) {
        // Changing not null ability of field institution on table course_extrasettings_general to null.
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('institution', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'specializations');

        // Launch change of nullability for field institution.
        $dbman->change_field_notnull($table, $field);

        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012425, 'local', 'course_extrasettings');
    }

    if ($oldversion < 2012012427) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('badgestatus', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'badgeimage');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012427, 'local', 'course_extrasettings');
    }

    if ($oldversion < 2012012428) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('selfpaced', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'courselevel');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012428, 'local', 'course_extrasettings');
    }
    if ($oldversion < 2012012430) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field1 = new xmldb_field('endtutoredses', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'courselevel');
        $field2 = new xmldb_field('endselfpacedses', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'endtutoredses');
        $field3 = new xmldb_field('coursestatus', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '1', 'endselfpacedses');
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012430, 'local', 'course_extrasettings');
    }

    if ($oldversion < 2012012432) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field1 = new xmldb_field('coursemode', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '1', 'coursestatus');
        $field2 = new xmldb_field('crsmaintenance', XMLDB_TYPE_INTEGER, '20', null, null, null, '1', 'coursemode');
        $field3 = new xmldb_field('enrolstart', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'crsmaintenance');
        $field4 = new xmldb_field('crsopen', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'enrolstart');
        $field5 = new xmldb_field('tutoringstart', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'crsopen');
        $field6 = new xmldb_field('tutoringstop', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'tutoringstart');
        $field7 = new xmldb_field('enrolstop', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'tutoringstop');
        $field8 = new xmldb_field('crsclosed', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'enrolstop');
        $field9 = new xmldb_field('nexteditiondate', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'nextedition');

        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }
        if (!$dbman->field_exists($table, $field4)) {
            $dbman->add_field($table, $field4);
        }
        if (!$dbman->field_exists($table, $field5)) {
            $dbman->add_field($table, $field5);
        }
        if (!$dbman->field_exists($table, $field6)) {
            $dbman->add_field($table, $field6);
        }
        if (!$dbman->field_exists($table, $field7)) {
            $dbman->add_field($table, $field7);
        }
        if (!$dbman->field_exists($table, $field8)) {
            $dbman->add_field($table, $field8);
        }
        if (!$dbman->field_exists($table, $field9)) {
            $dbman->add_field($table, $field9);
        }

        // Define field engtitle to be dropped from course_extrasettings_general.
        $field11 = new xmldb_field('selfpaced');
        $field12 = new xmldb_field('endtutoredses');
        $field13 = new xmldb_field('endselfpacedses');

        // Conditionally launch drop field selfpaced, endtutoredses, endselfpacedses.
        if ($dbman->field_exists($table, $field11)) {
            $dbman->drop_field($table, $field11);
        }
        if ($dbman->field_exists($table, $field12)) {
            $dbman->drop_field($table, $field12);
        }
        if ($dbman->field_exists($table, $field13)) {
            $dbman->drop_field($table, $field13);
        }

        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012432, 'local', 'course_extrasettings');
    }

    if ($oldversion < 2012012433) {
        // Define field encompetence to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('syncstatus', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'coursestatus');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012433, 'local', 'course_extrasettings');
    }
    // Two new field-- durationweek and estimatedweek.
    if ($oldversion < 2012012434) {
        // Define field syncstatus to be added to course_extrasettings_general.
        $table = new xmldb_table('course_extrasettings_general');
        $field1 = new xmldb_field('durationweek', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '0', 'syncstatus');
        $field2 = new xmldb_field('estimatedweek', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '0', 'durationweek');
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012434, 'local', 'course_extrasettings');
        }

        // One new field-- capstonecrs.
        if ($oldversion < 2012012437) {
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('capstonecrs', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '0', 'estimatedweek');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
       // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012437, 'local', 'course_extrasettings');
    }
   // Change field not-null for length and estimated
    if ($oldversion < 2012012438) {
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('length', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'costforattendance');
        $field1 = new xmldb_field('estimated', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'length');

        // Launch change of nullability for field institution.
        $dbman->change_field_notnull($table, $field);
        $dbman->change_field_notnull($table, $field1);

        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012438, 'local', 'course_extrasettings');
    }

    // One new field-- totalduration.
    if ($oldversion < 2012012440) {
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('totalduration', XMLDB_TYPE_INTEGER, '20', null, null, null, 'null', '0');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012440, 'local', 'course_extrasettings');
    }

    // Added two fiels-- instructname and instcsign.
    if ($oldversion < 2012012441) {
        $table = new xmldb_table('course_extrasettings_general');
        $field = new xmldb_field('instructname', XMLDB_TYPE_TEXT, null, null, null, null, null, '0');
        $field1 = new xmldb_field('instcsign', XMLDB_TYPE_INTEGER, '10', null, null, null, null, '0');

        // Launch change of nullability for field institution.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        // Course_extrasettings savepoint reached.
        upgrade_plugin_savepoint(true, 2012012441, 'local', 'course_extrasettings');
    }

    return true;
}
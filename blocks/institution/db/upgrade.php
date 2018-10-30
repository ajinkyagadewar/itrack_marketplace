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
 * This file keeps track of upgrades to the institution block
 *
 * Sometimes, changes between versions involve alterations to database structures
 * and other major things that may break installations.
 *
 * The upgrade function in this file will attempt to perform all the necessary
 * actions to upgrade your older installation to the current version.
 *
 * If there's something it cannot do itself, it will tell you what you need to do.
 *
 * The commands in here will all be database-neutral, using the methods of
 * database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * @since Moodle 2.0
 * @package block_institution
 * @copyright 2010 Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 *
 * @param int $oldversion
 * @param object $block
 */
function xmldb_block_institution_upgrade($oldversion) {
    global $CFG, $DB, $OUTPUT;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2014111004) {
        // Define field displaywordcount to be added to forum.
        $table = new xmldb_table('block_eduopen_master_inst');
        $field = new xmldb_field('refferencename', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, 'NULL', 'address');

        // Conditionally launch add field displaywordcount.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Forum savepoint reached.
        upgrade_block_savepoint(true, 2014111004, 'institution');
    }
    if ($oldversion < 2014111005) {
        // Define field displaywordcount to be added to forum.
        $table = new xmldb_table('block_eduopen_master_inst');
        $field = new xmldb_field('itdescription', XMLDB_TYPE_CHAR, '1000', null, XMLDB_NOTNULL, null, 'NULL', 'description');
        $field1 = new xmldb_field('crslang', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'NULL', 'refferencename');

        // Conditionally launch add field displaywordcount.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        // Forum savepoint reached.
        upgrade_block_savepoint(true, 2014111005, 'institution');
    }
    if ($oldversion < 2014111008) {
        // Define field displaywordcount to be added to forum.
        $table = new xmldb_table('block_eduopen_master_inst');
        $field = new xmldb_field('itname', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'crslang');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Forum savepoint reached.
        upgrade_block_savepoint(true, 2014111008, 'institution');
    }

    if ($oldversion < 2014111009) {
        // Define field crslang to be dropped from block_eduopen_master_inst.
        $table = new xmldb_table('block_eduopen_master_inst');
        $dfield = new xmldb_field('crslang');
        // Conditionally launch drop field crslang.
        if ($dbman->field_exists($table, $dfield)) {
            $dbman->drop_field($table, $dfield);
        }
        // Define field insttype to be added to block_eduopen_master_inst.
        $field = new xmldb_field('insttype', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'itname');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Forum savepoint reached.
        upgrade_block_savepoint(true, 2014111009, 'institution');
    }
    if ($oldversion < 2014111010) {
        // Changing type of field web on table block_eduopen_master_inst to char.
        $table = new xmldb_table('block_eduopen_master_inst');
        $field = new xmldb_field('itdescription', XMLDB_TYPE_TEXT, null, null, null, null, null, 'refferencename');

        // Launch change of type for field web.
        $dbman->change_field_type($table, $field);

        // Institution savepoint reached.
        upgrade_block_savepoint(true, 2014111010, 'institution');
    }
    return true;
}

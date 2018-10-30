<?php

// This file is part of MoodleofIndia - http://moodleofindia.com/
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
 * Note class is build for Manage Notes (Create/Update/Delete)
 * @desc Note class have one parameterized constructor to receive global 
 *       resources.
 * 
 * @package    local_myanalytics
 * @copyright  2015 MoodleOfIndia
 * @author     shambhu kumar
 * @license    MoodleOfIndia {@web http://www.moodleofindia.com}
 */
namespace myanalytics\sitelevel;
include_once('course.php');
include_once('user_report.php');
require_once('./../../../config.php');
require_once("{$CFG->libdir}/datalib.php");

/**
 * Description of site_report
 *
 * @author scott
 */
class site_report {    
    /**
     * 
     */
    public function __construct() {
        $this->course = new \myanalytics\courselevel\course_report();
        $this->user = new \myanalytics\userlevel\user_report();
    }
    /**
     * 
     * @return type
     */
    public function get_course() {
        return $this->course;
    }
    /**
     * 
     * @return type
     */
    public function get_user() {
        return $this->user;
    }    
    /**
     * 
     */
    public function __destruct() {
        $this->course = null;
        $this->user = null;
    }
    /**
     * 
     * @global type $USER
     * @global type $DB
     * @return type
     */
    public static function view_fav() {
        global $USER, $DB;
        $records = $DB->get_records('myanalytics_fav', ['userid'=>$USER->id]);
        return $records;
    }
    /**
     * 
     * @global \myanalytics\sitelevel\type $USER
     * @global \myanalytics\sitelevel\type $DB
     * @param type $level
     * @param type $label
     * @param type $link
     * @return boolean
     */
    public function add_fav($level , $label, $link) {
        global $USER, $DB;
        try{
        $dataobjects = new \stdClass();
        $dataobjects->userid = $USER->id;
        $dataobjects->level = $level;
        $dataobjects->label = $label;
        $dataobjects->link = $link;

        $records = $DB->insert_record('myanalytics_fav', $dataobjects);
            if($records){
                return true;
            } else {
                return false;
            }
        }
        catch (\Exception $e){
            echo $e->getMessage();
        }
    
    }
    /**
     * 
     * @global \myanalytics\sitelevel\type $USER
     * @global \myanalytics\sitelevel\type $DB
     * @param type $link
     * @return type
     */
    public function remove_fav($link) {
        global $USER, $DB;
        $records = !$DB->get_record_sql("delete from {myanalytics_fav} where " . $DB->sql_compare_text('link') . " = ? and userid = ?",[$link, $USER->id]);
        if($records){
            return json_encode(['status'=>true]);
        }
        return json_encode(['status'=>false]);
    }
    /**
     * 
     * @global \myanalytics\sitelevel\type $DB
     * @global \myanalytics\sitelevel\type $USER
     * @param type $link
     * @return boolean
     */
    public static function check_fav($link) {
        global $DB,$USER;
        //echo $link;
       try{
          $flag = $DB->get_record_sql("select * from {myanalytics_fav} where " . $DB->sql_compare_text('link') . " = ? and userid = ?",[$link, $USER->id]);
            if($flag) {
                return true;
            }
       return false;
       } catch (\Exception $ex) {
           echo $ex->getMessage();
       }
       
    }
    /**
     * 
     * @param type $courseid
     * @param type $user
     * @return boolean
     */    
    public static function has_permission_course($courseid, $user = null) {
        
        if(is_siteadmin()){
           return true; 
        }
        $context = \context_course::instance($courseid); 
        if(is_enrolled($context, $user, 'moodle/course:update')){
            return true;
        }
        return false;
    }
    /**
     * 
     * @param type $param
     */
    public function functionName($param) {
       if (user_has_role_assignment($userid, self::teacher)) {
           $courses = enrol_get_all_users_courses($userid,true,['id']);
           foreach ($courses as $id => $useless) {
               get_role_users(self::student, \context_course::instance($id));
           }
        }
    }
}
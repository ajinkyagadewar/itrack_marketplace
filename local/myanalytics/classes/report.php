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

namespace myanalytics;


require_once('./../../../config.php');
require_once("{$CFG->libdir}/grade/grade_grade.php");

/**
 * Base report class
 *
 * @package    local_myanalytics
 * @since      Moodle 2.8
 * @copyright  2015 MoodleofIndia {@web http://moodleofindia.com}
 * @author     shambhu kumar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

abstract class  report implements \Serializable , \Iterator{
    
    private $id;
    protected $page;
    protected $content;
    protected $db;
    protected $cfg;
    protected $context;
    protected $customdata;
    protected $gradeobject;
    public $enrolledcourses;
    public $completedcourses;
    public $lastlogin;
    public $info;
    public $userid;
    public $courseid;
    const  lang = 'local_myanalytics';
    const student = 5;
    const teacher = 3;

    /**
    * This constructor will receive some useful global variable and initial crosspondance data member.
    *
    * @param stdClass $db of Moodle database.
    * @param stdClass $user
    * @param stdClass $cfg Moodle configuration object 
    * @param stdClass $context Accessiblity of related area's
    * @param stdClass $course The course to object for the report
    * @param stdClass customdata holds some temporary data.
    */
    public function __construct($db = null , $user = null, $cfg = null , $context = null, $course = null, $customdata = null) {
        try {
            global $DB;
            $this->db = $DB;
            $this->user = $user;
            $this->cfg = $cfg;
            $this->course = $course;
            $this->context = $context;
            $this->customdata = $customdata;            
        } catch (\Exception $exc) {
            return $exc->getTraceAsString();
        }   
    }
    /**
     * 
     * @return type
     */
    public function init() {
        try {
            $this->gradeobject = new \grade_grade();
        } catch (\Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
      
    /**
     * Returns course object 
     *
     * @param $courseid 
     * @return array of stdClass objects if $course is null else stdClass.
     */
    public function get_courses($courseid = null) {
        try {
            if($courseid == null) {
                return $this->db->get_records('course');
            } else {
                return $this->db->get_records('course',array('id' => $courseid));
            }
        } catch (\Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    /**
     * Returns category object 
     *
     * @param $categoryid 
     * @return array of stdClass objects if $categoryid is null else stdClass.
     */
    public function get_category($categoryid = null) {
       try {
            if($categoryid == null) {
                return $this->db->get_records('category');
            } else {
                return $this->db->get_records('category',array('id' => $categoryid));
            }
        } catch (\Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    /**
     * Returns category object 
     *
     * @param $userid 
     * @return array of stdClass objects if $userid is null else stdClass.
     */
    public function get_students($userid = null) {
        try {
            if($userid == null) {
                return $this->db->get_records('category');
            } else {
                return $this->db->get_records('category',array('id' => $userid));
            }
        } catch (\Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    /**
     * Returns all teacher's who enrolled in any course 
     *
     * @param $userid 
     * @return array of stdClass objects if $userid is null else stdClass.
     */
    public function get_teachers($userid = null, $courseid = null) {
        try {
            //code
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    /**
     * Returns all student's who enrolled in any course 
     *
     * @param $userid 
     * @return array of stdClass objects if $userid is null else stdClass.
     */
    public function get_users($userid = null) {
        try {
            // code
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
   }
    
    /**
     * Returns json object 
     *
     * @param $stdClass this parameter will receive array of objects 
     * @return json object
     */
    public function json( $stdobj = null) {
        try {
            if($stdobj == null){
                throw new \Exception("parameter required stdclass object given null");
            } else {
                return json_encode($stdobj,JSON_PRETTY_PRINT);
            }
        } catch (\Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    /**
     * Returns full name of given userid
     *
     * @param $userid 
     * @return array of stdClass objects if $userid is null else stdClass.
     */
    public function get_fullname($userid = null) {
        try {
            if($userid == null) {
               $obj = $this->db->get_record('user', ['id' => $this->userid], 'firstname,lastname');
               return $obj->firstname.' '.$obj->lastname;
            } else {
                $obj = $this->db->get_record('user', ['id' => $userid], 'firstname,lastname');
                return $obj->firstname.' '.$obj->lastname;
            }
            } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    public function get_courses_name($courseid = null) {
        try {
            if($courseid == null) {
               $obj = $this->db->get_record('course', ['id' => $this->courseid], 'fullname');
               return $obj->fullname;
            } else {
                $obj = $this->db->get_record('course', ['id' => $courseid], 'fullname');
                return $obj->fullname;
            }
            } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    public function lang($name) {
        return get_string($name, self::lang);
    }
    
    /**
    * Returns list of logs
    *
    * Returns list of logs, for whole site, or category ,course ,users
    * Important: Passing parameter in fields Using l.* take the entire column
    * 
    *
    * @param string $select here we will pass condition fields like `course`
    * @param int $id passing select argument with id then return only those rows.
    * @param array $fields  accepts array like ['id','time','course'] and return these column passed 
    * @param string $order accepts string 
    * @param int $offset accept offset like in sql (begining of row)
    * @param int $limit number of rows you want retrive
    * @param boolean $condtion if its true then condition will be checked as {==} if its false the it will be checked as {!=}
    * @return array Array of logs
    */
    
   public function logs($select = '', $id = '', $fields=array(), $order=null, $offset='', $limit='',$condtion = true) {  
      
       if($select && $id && $condtion){
           $select = 'l.'.$select.'='.$id;           
       } else if($select && $id && !$condtion){
          $select = 'l.'.$select.' !='.$id; 
       } else {
           $select ='';
       }
       
       if(!$order){
           $order='l.id ASC';
       }
       
       if(!$fields){
           $fields = [];
       }
       $tmp =0;
       $totalcount = &$tmp;
       $data = null;
       $bulkdata = get_logs($select,$fields,$order,$offset,$limit,$totalcount);
            
       if($bulkdata){
           foreach ($bulkdata as  $key => $rows) {               
               $list = null;
               foreach ($rows as $field => $row) {
                   if(!empty($fields)) {
                      if(in_array($field, $fields)) {
                        $list[$field] = $row;
                      }
                   } else {
                      $list[$field] = $row;
                   }
               }
               $data[$key] = $list;
           }
       }
       return $data;
    }
    /**
     * 
     * @param array $data
     * @param type $sort
     * @param type $reverse
     * @param type $slice
     * @param type $rows
     * @return type
     */
    public function sort(array $data = null,$sort = false,$reverse = true ,$slice = true ,$rows=20) {
        if($sort) {
            usort($data, function($a, $b) {
                return $a['count'] - $b['count'];
            });
        }
        if($reverse) {
            $data = array_reverse($data);
        }
        
        if($slice) {
            $data = array_slice($data,0,10);
        }        
        return $data;
    }
    
    /**
    * This function will generate Google chart JSON data
    * @param array $data recive array data
    * @return array return google format data. 
    */
    public function chart(array $data){
        $cols=array();
        
        $col1=["id"=>"","type"=>"string"];    
        $col2=["id"=>"","type"=>"number"];   
        
        $cols = array($col1,$col2);

        $row_data=array();
        
        foreach ($data as $var) {        
            $cell0["v"]=$var['name'];
            $cell0["f"]=null;    
            $cell1["v"]=$var['count'];
            $cell1["f"]=null;
            $row_data[]["c"]=array($cell0,$cell1);    
         }
        

        return array("cols"=>$cols,"rows"=>$row_data);
    }
   
   
    /* later implementation functions */
    public function current() {}

    public function key() {}

    public function next() {}

    public function rewind() {}

    public function serialize() {}

    public function unserialize($serialized) {}

    public function valid() { }
}

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
namespace myanalytics\userlevel;
require_once('../locallib.php');

require_once('report.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->libdir}/enrollib.php");
require_once("{$CFG->libdir}/gradelib.php");
require_once("{$CFG->libdir}/completionlib.php");
require_once("{$CFG->dirroot}/enrol/locallib.php");
require_once("{$CFG->dirroot}/local/myanalytics/classes/course.php");
require_once("{$CFG->dirroot}/completion/criteria/completion_criteria_activity.php");
require_once("{$CFG->dirroot}/completion/completion_completion.php");
require_once("{$CFG->dirroot}/grade/querylib.php");
require_once($CFG->dirroot . '/blocks/timestat/locallib.php');

class user_report extends \myanalytics\report {
    
    /**
     * 
     * @global type $PAGE
     * @global \myanalytics\userlevel\type $CFG
     * @global \myanalytics\userlevel\type $DB
     * @param type $userid
     * @param type $courseid
     */
    public function __construct($userid = null, $courseid = null) {
        global $PAGE ,$CFG, $DB;
        $this->page= $PAGE;
        $this->cfg = $CFG;
        $this->db = $DB;
        $this->id = $userid;
        $this->userid = $userid;
        $this->courseid = $courseid;
    }
    /**
     * 
     */
    public function init() {
        $this->enrolledcourses =  count(enrol_get_users_courses($this->id,false,['fullname']));
        $this->completedcourses = rand(0, $this->enrolledcourses);
        $this->info = $this->db->get_record('user',['id'=>  $this->id]);
        $this->lastlogin = $this->info->lastlogin !=0 ? userdate($this->info->lastlogin,  '%d %B %Y') : $this->lang('notyetloggedin');
    }
    /**
     * 
     */
    public function init_course() {
        $this->course =  new \myanalytics\courselevel\course_report();
    }
    /**
     * 
     * @param type $userid
     * @param type $fields
     * @param type $order
     * @param type $limit
     */
    public function get_user($userid = null, $fields = null, $order = 'asc', $limit = null) {
    

    }
    /**
     * 
     * @param type $chart
     * @param type $json
     * @param type $number
     * @param type $fields
     * @return type
     */
    public function get_top_users_in_course($chart = false, $json = false ,$number = null,$fields='') {
        $user=null;
        $list = get_courses(null,null,'c.id,c.fullname');
        $data=[];
        foreach ($list as  $value) {
            $course = new \stdClass();
            if($value->id!=1) {
                $course->id = $value->id;
                
                $manager = new \course_enrolment_manager($this->page, $course);
                  $users =  $manager->get_users('id');
                    foreach ($users as $key => $user) {
                        if(!is_siteadmin($user->id)){
                            if(array_key_exists($key,$data)){
                                ++$data[$user->id]['count'];
                            } else {
                                $data[$user->id] = array(
                                              'id'=>$user->id,
                                              'name'=>$user->firstname.' '.$user->lastname,
                                              'count'=>1,
                                              'email'=>$user->email,
                                              'social'=>$this->get_social_links($user->id)
                                              );
                            }
                        }
                    }
                unset($manager);
            }
        }
        usort($data, function($a, $b) {
            return $a['count'] - $b['count'];
        });
        $data = array_reverse($data);
       
        $response = $data;
        
        if($number != null) {
            $response = array_slice($data, 0, $number);
        }
        
        if($chart==true) {
            return  $this->json($this->chart($response));
        }

        if($json == true){
            return $this->json($response);
        }

        return $response;
    }
    /**
     * 
     * @param type $userid
     * @return type
     */
    public function get_social_links($userid) {
        
        $users = $this->db->get_recordset('user',['id'=>$userid],'','id,icq,skype,yahoo,msn');
        $user = null;
        foreach ($users as $user) {
            if ($user->icq) {
                $user->icq = 'https://www.facebook.com/'.$user->icq;
            }
            if ($user->skype) {
                $user->skype = 'https://secure.skype.com/portal/'.$user->skype;
            }
            if ($user->yahoo) {
                $user->yahoo = 'https://in.yahoo.com/'.$user->yahoo;
            }
            if ($user->msn) {
                $user->msn = 'https://www.google.co.in/#q='.$user->msn;
            }
            $user = ['icq'=> $user->icq, 'skype' => $user->skype, 'yahoo'=>$user->yahoo,'msn'=>$user->msn];
        }
        return $user;
    }
    /**
     * 
     * @param type $list
     * @return type
     */
    public function get_top_users_in_log($list = 10) {
       $users = $this->logs('userid',$this->cfg->siteadmins,['userid','firstname','lastname'],'','','',false);
       $data =[];
       foreach ($users as $key => $user) {
            if($user['userid']){
                if(array_key_exists($user['userid'], $data)){
                        ++$data[($user['userid'])]['count'];
                } else {
                    $data[($user['userid'])] = array(
                                  'id'=>$user['userid'],
                                  'name'=>$user['firstname'].$user['lastname'],
                                  'count'=>1
                                  );
                }
           }
       }
        
       return $this->sort($data);
    }
    /**
     * @depericated
     * Don't use beacuse in this function get_users() return  bulk of data which 
     * couse Memeory Issue.
     * 
     */
    /*
    public function get_signup_users_in($days = 10) {
        $users = get_users(true,'',false,null,'','','','','',$fields='id,firstname,lastname,timecreated','',null);
        $now = new \DateTime();
        $curtime = $now->getTimestamp();
        $back = $now->sub(\DateInterval::createFromDateString($days.' days'));
        $backtime = $back->getTimestamp();
        $data = [];
        foreach ($users as  $row) {            
            if($row->id!=1) {               
                if($curtime > $row->timecreated && $backtime < $row->timecreated){
                    $data[$row->id] = [
                                        'id' => $row->id,
                                        'name'=> $row->firstname.$row->lastname,
                                        'created' =>$row->timecreated
                                      ];
                }               
            }
        }
      
       return $this->sort($data,false);
    }
    */
    /**
     * 
     * @return type
     */
    public function number_of_learners() {
        return count($this->get_tutors(self::student,'u.id',true));
    }
    /**
     * 
     * @param type $days
     * @return type
     */
    public function get_signup_users_in($days = 30) {
        try{
            $now = new \DateTime();
            $curtime = $now->getTimestamp();
            $back = $now->sub(\DateInterval::createFromDateString($days.' days'));
            $backtime = $back->getTimestamp();
            $sql ='SELECT count(id) FROM {user} WHERE timecreated < ? AND timecreated > ?';
            $signupusers = $this->db->count_records_sql($sql,array($curtime,$backtime));
            return $signupusers;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }        
    }
    /**
     * 
     * @return int
     */
    public function get_active_user() {
        $tutors = $this->get_tutors(self::student,'u.id,u.suspended,u.confirmed,u.deleted',false);
        unset($tutors[$this->cfg->siteadmins]);
         $days = 0;
        foreach ($tutors as $tutor) {
           if($tutor->confirmed == 1 && $tutor->suspended == 0 && $tutor->deleted == 0) {
                $days += 1;
           }
        }
        $tutors = null;
        return $days;
    }
    /**
     * 
     * @return type
     */
    public function get_not_confirmed() { 
        $tutors = $this->get_tutors(self::student,'u.id,u.suspended,u.confirmed',false);
        unset($tutors[$this->cfg->siteadmins]);
         
        foreach ($tutors as $tutor) {
           if(!$tutor->confirmed == 0) {
                unset($tutors[$tutor->id]);
           }
        }
        $num = count($tutors);
        $tutors = null;
        return $num;
    }
    /**
     * 
     * @param type $roleid
     * @param string $fields
     * @param type $activestate
     * @return type
     */
    public function get_tutors($roleid , $fields = '',$activestate = true){
        $active = '';
        if($activestate) {
           $active ='AND u.deleted = 0 AND u.confirmed = 1';
        }
        if(empty($fields)){
            $fields ="u.id,  CONCAT(u.firstname, ' ', u.lastname) as name, u.email ,u.confirmed ,u.suspended , u.timecreated";
        }        
        return $this->db->get_records_sql("SELECT $fields  FROM {user} u
                        LEFT JOIN {$this->cfg->prefix}role_assignments a ON a.userid = u.id  
                        WHERE a.roleid =$roleid  $active GROUP BY u.id");
    }
    /**
     * 
     * @return type
     */
    public function get_tutors_count(){
        $data = $this->get_tutors(self::teacher,'u.id',false);
        unset($data[$this->cfg->siteadmins]);
        return count($data);
    }
    /**
     * 
     * @return type
     */
    public function get_active_tutor() {
        
        $courses = get_courses("all", "c.sortorder ASC", $fields="c.id,c.fullname");
        
        $tutors = $this->get_tutors(self::teacher);

        $data = [];
        foreach ($courses as $course) {
            $context = \context_course::instance($course->id);
            foreach ($tutors as $user) {
                if(is_enrolled($context, $user->id, '', true)) {
                    if(array_key_exists($user->id, $data)){
                        ++$data[$user->id]['count'];
                        ++$data[$user->id]['coursecount'];
                        $data[$user->id]['course'][]=['id'=>$course->id,'name'=>$course->fullname];
                    } else {
                            $data[$user->id] = array(
                                               'id' => $user->id,
                                               'name' => $user->name,
                                               'count' => 1,
                                               'course'=>[['id'=>$course->id,'name'=>$course->fullname]],
                                               'coursecount' => 1
                                            );
                    }
                }
            }
        }
        $logdata=[];
        foreach ($tutors as $user) {
            $logdata[$user->id]= count($this->logs('userid',$user->id,['id']));
        }

        /**
         * Remove admin record
         * Not use admin data.
         */
        unset($logdata[$this->cfg->siteadmins]);
        unset($data[$this->cfg->siteadmins]);

        $totalvisit=0;
        $tutor='';


        foreach ($data as $key => $user) {

            foreach ($logdata as $userid => $visits) {
                if($key == $userid){
                    $data[$key]['visit'] = $visits;
                    $totalvisit += $visits;
                }
            }
            if($data[$key]['visit'] != 0){
                $tutor[$key] = $visits; 
             }
           
           /* $enrolcount = count($user['course']);
            $percourse = ceil(($enrolcount/count(get_courses()))*100);
            $visits = ($data[$key]['visit'] ==0)?1:$data[$key]['visit'];
            $per = ($visits/$totalvisit)*100;
            
            //echo $totalpercent = ceil(($percourse+$per)),$user['name'],'<br/>';
            $data[$key]['percentage'] = $totalpercent;*/
            
        }
        
        arsort($logdata);
        
        $topuser = array_slice($logdata, 0, 1,true);
        
        if(isset($data[key($topuser)]['name']) && isset($topuser[key($topuser)])){
            return  ['name' => $data[key($topuser)]['name'],'score'=>$topuser[key($topuser)]];
        }
        return ['name' => '-', 'score'=> '-'];
    }
    /**
     * 
     * @return type
     */
    public function get_popular_tutor() {
        $courses = get_courses('all','','c.id,c.fullname');
        
        $tutors = $this->get_tutors(self::teacher); 
        
        $data = [];
        foreach ($courses as $course) {
            $context = \context_course::instance($course->id);
            foreach ($tutors as $user) {
                if(is_enrolled($context, $user->id, '', true)){
                    if(array_key_exists($user->id, $data)){
                        ++$data[$user->id]['count'];
                        ++$data[$user->id]['coursecount'];
                        $data[$user->id]['course'][]=['id'=>$course->id,'name'=>$course->fullname];
                    } else {
                            $data[$user->id] = array(
                                               'id' => $user->id,
                                               'name' => $user->name,
                                               'count' => 1,
                                               'course'=>[['id'=>$course->id,'name'=>$course->fullname]],
                                               'coursecount' => 1
                                            );
                    }
                }
            }
        }
        unset($data[$this->cfg->siteadmins]);
         $topuser = array_slice($data, 0, 1,true); 
         
        if(!empty($data)) {
            return  ['name' => $data[key($topuser)]['name']]; 
        }
         return  ['name' => '-']; 
    }
    /**
     * 
     * @return type
     */
    public  function get_active_tutors() {
         $tutors = $this->get_tutors(self::teacher,'u.id,u.confirmed,u.suspended',true);
         unset($tutors[$this->cfg->siteadmins]);
         foreach ($tutors as $tutor) {
            if(!$tutor->confirmed == 1 && $tutor->suspended == 0) {
                 unset($tutors[$tutor->id]);
            }
         }
         $num = count($tutors);
         $tutors = null;
         return $num;  
    }
    /**
     * 
     * @return type
     */
    public function course_enroll_completion() {
        $tmp = time();
        $uniquelogin = null;
        $days = [10=>'',20=>'',30=>'',4=>'',5=>''];
        
        foreach ($days as $day => $val) {
            
            $time = strtotime('-'.$day.' day'); 
            $days[$day] = $time;
            
            $courseenroll[$day] = $this->db->get_records_sql("SELECT c.id, c.fullname, count( ue.id ) AS nums FROM {$this->cfg->prefix}course c, {$this->cfg->prefix}enrol e, {$this->cfg->prefix}user_enrolments ue WHERE e.courseid = c.id AND ue.enrolid =e.id AND ue.timecreated BETWEEN $time AND $tmp GROUP BY c.id");
            
            $tmp=$time;
        }
       
        //$logins = [];
        $enrols = [];
        $completed = [];
        $data = [];
        foreach ($courseenroll as $key => $rows) {
           $sum = 0;
           foreach ($rows as $row) {
               $sum += $row->nums;
           }
           $enrols[$key] = $sum;
       }
       
       /**
        * Count completed course
        */
        $completioninfolist = [];
        $courses  = get_courses();
        foreach ($courses as $row) {
            $completioninfolist[] = new \completion_info($row);
        }
        $userdata = array();
        foreach ($completioninfolist as $cminfo) {
            
            if($cminfo->is_enabled()){
                $class = new \ReflectionClass("completion_info");
                $property = $class->getProperty("course");
                $property->setAccessible(true);
                $list = $property->getValue($cminfo);
                /**
                 * get number of student who enrolled in this course
                 */
                $users = get_enrolled_users(\context_course::instance($cminfo->course_id));
                
                foreach ($users as $user) {
                     $params = array(
                            'userid'    => $user->id,
                            'course'  => $cminfo->course_id
                        );

                    $ccompletion = new \completion_completion($params);
                    if($ccompletion->is_complete()) {
                        $userdata[$user->id] = ['userid'=>$user->id,'time'=> $ccompletion->timecompleted];
                    }
                }
            }
              
        }    
        $curtime = time();
        foreach ($days as $day => $nouse) {
            $time = strtotime('-'.$day.' day');
            $days[$day] = $time;
            foreach ($userdata as $key => $value) {
                if($value['time'] <= $curtime && $value['time'] >= $time ) {
                    $completed[$day] = count($userdata);
                }
            }
            $curtime=$time;
        }
        
        $days = array_reverse($days,true);
        $completed = array_reverse($completed,true);
       
        $cols=array();

        $col1=["id"=>"","label"=>"Time","type"=>"string"];
        $col2=["id"=>"","label"=> $this->lang('courseenrolled'),"type"=>"number"];
        $col3=["id"=>"","label"=> $this->lang('coursecompleted'),"type"=>"number"];
        
        $cols = array($col1,$col2,$col3);

        $row_data=array();
        
        foreach ($days as $day => $nouse) {
            $cell1["v"] = date('D,d M Y',$days[$day]);
            $cell2["v"] = isset($enrols[$day])?$enrols[$day]:0;
            $cell3["v"] = isset($completed[$day]) ? $completed[$day] : 0;
            $row_data[]["c"]=array($cell1,$cell2,$cell3);
         }
        
        return $this->json(array("cols"=>$cols,"rows"=>$row_data));
    }
    /**
     * 
     * @return type
     */
    public function get_enrol_courses() {
        $courses = enrol_get_users_courses($this->id, $onlyactive = false,['fullname']);        
        $data= [];
        foreach ($courses as  $course) {
            $resultkrb = grade_get_course_grades($course->id, $this->id);
            if($resultkrb->grades[$this->id]->str_grade != '-'){
                $data[] = ['name' => $course->fullname,'count' =>$resultkrb->grades[$this->id]->str_grade];
            }
        }
        return $this->json($this->chart($data));
    }
    /**
     * 
     * @param type $request
     * @return type
     */
    public function get_course_activities($request = null) {
        global $DB;
        
       $this->init_course();
         /**
        * Collect data from database
        */
       $search = $request['search']['value'];

       $sort =   $request['order'][0]['dir'];

       $mode =   $request['order'][0]['column'];

       if(!empty($search) && filter_var($search, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE)) {
            $search = "fullname LIKE '%".$request['search']['value']."%'";
            $mode = 0;
        } else {
            $search = 1;
        }
        $courseactivity = null;
            
        switch ($mode) {
            case 1:
                     switch ($sort) {
                         case 'asc':
                             $this->array_sort($coursedata, 'assigned', SORT_ASC);
                             break;
                         case 'desc':
                             $this->array_sort($coursedata, 'assigned', SORT_DESC);
                             break;
                     }
                     $coursedata = array_slice($coursedata, $request['start'], $request['length']);
                break;
            case 2:
                    switch ($sort) {
                         case 'asc':
                             $this->array_sort($coursedata, 'completed', SORT_ASC);
                             break;
                         case 'desc':
                             $this->array_sort($coursedata, 'completed', SORT_DESC);
                             break;
                     }
                     $coursedata = array_slice($coursedata, $request['start'], $request['length']);
                break;
            case 3:
                    switch ($sort) {
                         case 'asc':
                             $this->array_sort($coursedata, 'completedpercent', SORT_ASC);
                             break;
                         case 'desc':
                             $this->array_sort($coursedata, 'completedpercent', SORT_DESC);
                             break;
                     }                    
                     $coursedata = array_slice($coursedata, $request['start'], $request['length']);
                break;
            case 3:
                    switch ($sort) {
                         case 'asc':
                             $this->array_sort($coursedata, 'active', SORT_ASC);
                             break;
                         case 'desc':
                             $this->array_sort($coursedata, 'active', SORT_DESC);
                             break;
                     }
                     $coursedata = array_slice($coursedata, $request['start'], $request['length']);
                break;
            case 0:
                        $courses = enrol_get_users_courses($this->id);
                        $completioninfolist = [];
                        foreach ($courses as $row) {
                            $completioninfolist[] = new \completion_info($row);
                        }
                        $listarray = [];
                        foreach ($completioninfolist as $key=> $cminfo) {
                            $class = new \ReflectionClass("completion_info");
                            $property = $class->getProperty("course");
                            $property->setAccessible(true);
                            $list = $property->getValue($cminfo);
                            $completion ='<i class="fa fa-warning"></i>';
                            
                            if($cminfo->is_course_complete($this->id)) {
                                $completion = '<i class="fa fa-check"></i>';
                            } else {
                               if($cminfo->is_enabled() ){
                                    $completion = '<i class="fa fa-remove"></i>';
                                } 
                            }
                            
                            $resultkrb = grade_get_course_grades($cminfo->course_id, $this->id);

                            $listarray[$key] = ['id'=>$list->id, 'name' => $list->fullname,'completion'=>$completion, 'coursetotal'=>$resultkrb->grades[$this->id]->str_grade,'activity' => []];
                                  
                            $fastmodinfo = get_fast_modinfo($list->id);
                            foreach ($fastmodinfo->get_cms() as $cm) {
                                $grade = @grade_get_grades($list->id, 'mod', $cm->modname, $cm->instance,  $this->id);
                                //echo '<pre>',print_r($grade);
                                $tmp  = '';
                                if(isset($grade->items[0])) {
                                      $tmp =  $grade->items[0]->grades[$this->id]->str_grade;
                                }
                                //added by Shiuli on 12th feb for timespent.
                                $timelogs = $DB->get_record_sql("SELECT SUM(timespent) as sum FROM {custom_log} WHERE
                                 cmid=$cm->id AND userid=$this->id");
                                 if ($timelogs->sum) {
                                    $spenttime = $timelogs->sum;
                                } else {
                                    $spenttime = 0;
                                }
                                $timee = timestat_seconds_to_stringtime($spenttime);
                                
                                //echo $tmp.'<br/>';
                                if(array_key_exists($key, $listarray)) {
                                    $listarray[$key]['activity'][] = [
                                                    'id' => $cm->id,
                                                    'name' => $cm->name,
                                                    'type' => $cm->modname,
                                                    'completionstate' => $cminfo->get_data($cm, false, $this->id, $cm)->completionstate ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>',
                                                    'score' =>  $tmp,
                                                    'timespent' => $timee
                                                   ];
                                    
                                     
                                } else {
                                    $listarray[$key] = [
                                                    'fullname' => $list->fullname,
                                                    'activity'=> [[
                                                                    'id' => $cm->id,
                                                                    'name' => $cm->name,
                                                                    'type' => $cm->modname,
                                                                    'completionstate' => $cminfo->get_data($cm, false, $this->id, $cm)->completionstate,
                                                                    'score' => $tmp,
                                                                    'timespent' => $timee
                                                                 ]]
                                                   ];
                                }
                            }
                        }
                break;
            case 4:
                $index ='';
                foreach ($coursedata as $key => $list) {
                    
                  if($list['assigned'] == $search || $list['completed'] == $search || $list['completedpercent'] == $search || $list['active'] == $search) {
                      $index = $key;
                      break;
                  }

                }
                $coursedata = $coursedata[$index];
                break;
            default :
                
                break;
        }
        
       $coursecount = count($completioninfolist);
       
       $list = [
                   "sEcho" =>0,
                   "iTotalRecords" => $coursecount,
                   "iTotalDisplayRecords" => $coursecount,
                   "aaData"=> $listarray
               ];
       return $this->json($list);
    }
    
   /* public function completion_icon($param) {
        $completion ='<i class="glyphicon glyphicon-alert"></i>';
                            
                            if($cminfo->is_course_complete($this->id)) {
                                $completion = '<i class="glyphicon glyphicon-ok"></i>';
                            } else {
                               if($cminfo->is_enabled() ){
                                    $completion = '<i class="glyphicon glyphicon-remove"></i>';
                                } 
                            }     
        return $completion;                    
    }*/
    /**
     * 
     * @return type
     */
    public function get_course_activity() {
        global $DB;
        $course = new \stdClass();
        $course->id = $this->courseid;
        
        $modinfo = get_fast_modinfo($this->courseid,  $this->id);
                    
        $listarray = [];
        
        foreach ($modinfo->get_cms() as  $cm) {
            
            $grade =  grade_get_grades($this->courseid, 'mod', $cm->modname, $cm->instance,  $this->id);
            //echo '<pre>', print_r($grade);
            $score  ='-';
            if(isset($grade->items[0])) {
                  $score =  $grade->items[0]->grades[$this->id]->str_grade;
            }
            //$cmdata = $cminfo->get_data($cm, false, $this->id);
            
            // $timespentlog = block_timespent_print_log((object)array('id'=>$this->courseid,'shortname'=>$this->shortname,'groupmode'=>$cm->groupmode), '', 1426502700, time(), $order = "l.time ASC", $page = 0, $perpage = 100, $url = "", "", $cm->id, $modaction = "", $groupid = 0);
            //added by Shiuli on 10th feb.
            $timelogs = $DB->get_record_sql("SELECT SUM(timespent) as sum FROM {custom_log} WHERE
             cmid=$cm->id AND userid=$this->id");
             if ($timelogs->sum) {
                $spenttime = $timelogs->sum;
            } else {
                $spenttime = 0;
            }
            $timee = timestat_seconds_to_stringtime($spenttime);
            $listarray  [] = [
                                'id' => $cm->id,
                                'name' => $cm->name,
                                'type' => $cm->modname,
                                'completionstate' => $this->completed_state($cm),
                                'score' =>$score,
                                'duetime' => $this->complete_time($cm),
                                'timespent' => $timee
                             ];
        }
      return $listarray;
    }
    /**
     * 
     * @param type $cm
     * @return string
     */
    public function complete_time($cm) {

        if($cm->completionexpected != 0) {
            return date('D, d M Y',$cm->completionexpected);
        } else {
            $result = $this->db->get_records('event',['instance'=>$cm->instance]);
            foreach ($result as $row) {
                if($row->eventtype == 'close' || $row->eventtype =='due') {
                    return date('D, d M Y',$row->timestart);
                }
            }
            return '-';
        }       
    }
    /**
     * 
     * @param type $cm
     * @return string
     */
    public function completed_state($cm) {
        $state = '-';
        $course =  new \stdClass();
        $course->id = $this->courseid;
        $completion = new \completion_info($course);

        $grade = grade_get_grades($this->courseid, 'mod', $cm->modname, $cm->instance,  $this->id);

        if($completion->is_enabled($cm)) {
            $state = '<i class="icon icon-ok"></i>';
        } else if(isset($grade->items[0]->grades[$this->id])) {
                 $state = '<i class="icon icon-ok"></i>';
             } else if ($this->db->record_exists('grade_items',['iteminstance' => $cm->instance])) {
                 $state = '<i class="icon icon-remove"></i>';
             }
        return $state;
    }
    /**
     * 
     * @return type
     */
    public function user_quiz_info() {
        $this->init_course();
        return $this->course->quiz_info(null, $this->courseid, $this->id);
    }
    /**
     * 
     * @param type $chart
     * @return int
     */
    public function user_course_completed($chart = false) {
        $course = new \stdClass();
        $course->id = $this->courseid;
        $activity = [];
        $completeactivity = 0;
        $info = new \completion_info($course);
        $completions  = $info->get_completions($this->id);        
        
        foreach ($completions as $completion) {
            $criteria = $completion->get_criteria();
            $complete = $completion->is_complete();
            
            if($criteria->criteriatype == COMPLETION_CRITERIA_TYPE_ACTIVITY) {
                $activity[$criteria->moduleinstance] = $complete;
            }
            
            if($complete) {
                $completeactivity++;
            }
        }
        
        if($chart) {
                $incomplete = count($activity) - $completeactivity;
                $chartdata  = [
                                ['name'=>  $this->lang('complete'),'count'=>$completeactivity],
                                ['name'=>  $this->lang('incomplete'),'count'=>$incomplete]

                              ];
                
                $cols=array();
                $col1=["id"=>"","label" => '',"type"=>"string"];    
                $col2=["id"=>"","type"=>"number"];   

                $cols = array($col1,$col2);

                $row_data = array();

                foreach ($chartdata as $var) {        
                    $cell0["v"]=$var['name'];
                    $cell0["f"]=null;    
                    $cell1["v"]=$var['count'];
                    $cell1["f"]=null;
                    $row_data[]["c"]=array($cell0,$cell1);    
                 }
            return $this->json(array("cols"=>$cols,"rows"=>$row_data));
        }
       
      return $completeactivity;
    }
    /**
     * 
     * @param type $query
     * @param type $option
     * @param type $self
     * @return type
     */
    public function user_search_ajax($query, $option, $self = null) {
//        echo $query;
//        echo $option;
        $json = ['status'=> false,'message'=> $this->lang('nomatchrecordfound')];
        $result = null;
        $option = ($option == 'name') ? 'firstname' : $option;
        // Added by Shiuli to convert search name lowercase to uppercase.
        if ($option == "firstname" || $option == "lastname") {
            $upperquery = strtoupper($query);
        } else {
            $upperquery = $query;
        }
        $search = "$option LIKE '%".$upperquery."%' or lastname LIKE '%".$upperquery."%'";
        
        if(isset($self)){
            $result[] = $this->db->get_record('user', ['id' => $this->userid], 'id,firstname,lastname');
        }
        if(is_siteadmin($this->id)){
            $result = $this->db->get_records_sql('select id,firstname,lastname,email from {user} where '.$search.' and id != 1');
        } else if(user_has_role_assignment($this->id, self::teacher)){
            $result = $this->user_search_in_courses($this->id, $search, self::student);
        }
        if(!empty($result) && $result != null) {
            $json['status'] = true;
            unset($json['message']);
            foreach ($result as  $row) {
                $json['list'][] = $row;
            }
        }
        return $this->json($json);
    }
    /**
     * 
     * @param type $userid
     * @param type $search
     * @param type $roleid
     * @return type
     */
    public function user_search_in_courses($userid, $search, $roleid) {
        $courses = enrol_get_my_courses(['id']);
        $list = array();
        foreach ($courses as $id => $useless) {
            $context = \context_course::instance($id);
            if(has_capability('moodle/course:update',$context, $userid)) {
               $sql ="SELECT u.id as id,u.firstname,u.lastname FROM {course} c
                        JOIN {context} ct ON c.id = ct.instanceid
                        JOIN {role_assignments} ra ON ra.contextid = ct.id
                        JOIN {user} u ON u.id = ra.userid
                        JOIN {role} r ON r.id = ra.roleid
                        WHERE c.id =$id AND r.id=$roleid AND ($search)";
               $results = $this->db->get_records_sql($sql);
               foreach ($results as $userid => $row) {
                   if(!array_key_exists($userid, $list)){
                       $list[$userid] = $row;
                   }
               }
               
            }
        }        
        return $list;
    }
    /**
     * 
     * @global type $USER
     * @global type $CFG
     * @global type $DB
     * @param type $day
     */
    
    function get_unique_sessions($day = 30) {
        global $USER, $CFG, $DB;
        try{
        $params = new \stdClass();
        $params->timefinish = time();
        $params->timestart = strtotime('-'.$day.' day');
        $datediff = $params->timefinish - $params->timestart;
        $days = floor($datediff/(60*60*24)) + 1;
        if ($days <= 3) {
            $ext = 3600; //by hour
        } else if ($days <= 30) {
            $ext = 86400; //by day
        } else if ($days <= 90) {
            $ext = 604800; //by week
        } else if ($days <= 365) {
            $ext = 2592000; //by month
        } else {
            $ext = 31556926; //by year
        }
        $sql ="SELECT floor(lastaccess / $ext) * $ext as time, COUNT(id) as users FROM {user}  WHERE id IN (SELECT DISTINCT(userid) FROM {role_assignments} WHERE roleid = 5) AND lastaccess BETWEEN ? AND ? GROUP BY floor(lastaccess / $ext) * $ext";
        $data = $this->db->get_records_sql($sql,[$params->timestart,$params->timefinish ]); 
        $response = [];
        sort($data);
        foreach($data as $item){
                $response[] = ['name'=>date('r',$item->time),'count'=>$item->users];
        }
        $cols=array();
        $col1=["id"=>"","label" => $this->lang('uniquelogin'),"type"=>"string"];    
        $col2=["id"=>"","type"=>"number"];   

        $cols = array($col1,$col2);

        $row_data = array();

        foreach ($response as $var) {        
            $cell0["v"] = $var['name'];
            $cell0["f"] = null;    
            $cell1["v"] = $var['count'];
            $cell1["f"] = null;
            $row_data[]["c"] = array($cell0,$cell1);    
         }                
        echo $this->json(array("cols" => $cols, "rows" => $row_data));
      }catch(\Exception $e){
          echo $e->getMessage();
          echo $e->getCode();
          echo $e->getFile();
          echo $e->getTraceAsString();         
      }
      
    }
}
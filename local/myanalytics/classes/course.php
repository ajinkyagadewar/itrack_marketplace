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

namespace myanalytics\courselevel;

require_once('./../../../config.php');
require_once("{$CFG->dirroot}/local/myanalytics/classes/report.php");
require_once("{$CFG->dirroot}/local/myanalytics/locallib.php");

require_once("{$CFG->libdir}/datalib.php");
require_once("{$CFG->libdir}/completionlib.php");
require_once("{$CFG->libdir}/coursecatlib.php");
require_once("{$CFG->libdir}/modinfolib.php");
require_once("{$CFG->libdir}/gradelib.php");
require_once("{$CFG->dirroot}/enrol/locallib.php");
require_once("{$CFG->dirroot}/enrol/externallib.php");
require_once("{$CFG->libdir}/coursecatlib.php");
require_once("{$CFG->dirroot}/mod/quiz/attemptlib.php");
require_once("{$CFG->dirroot}/mod/quiz/accessmanager.php");
require_once("{$CFG->dirroot}/mod/forum/lib.php");
require_once($CFG->dirroot . '/calendar/lib.php');
require_once("{$CFG->dirroot}/grade/querylib.php");
require_once("{$CFG->dirroot}/question/engine/questionusage.php");
require_once("{$CFG->dirroot}/question/engine/lib.php");

class course_report extends \myanalytics\report {

    /**
     * 
     * @global type $PAGE
     * @global type $DB
     * @global type $CFG
     * @param type $id
     */
    public function __construct($id = null) {
        parent::__construct();
        global $PAGE, $DB, $CFG;
        $this->page = $PAGE;
        $this->db = $DB;
        $this->cfg = $CFG;
        $this->courseid = $id;
    }

    /**
     * 
     * @param type $select
     * @param type $id
     * @param type $fields
     * @param type $order
     * @param type $offset
     * @param type $limit
     * @return type
     */
    public function get_course_log($select = null, $id = null, $fields = null, $order = '', $offset = null, $limit = null) {
        return $this->json($this->logs($select, $id, $fields, $order, $offset, $limit));
    }

    public function total_courses() {
        $course = get_courses(null, null, 'c.id');
        unset($course[1]);
        return count($course);
    }

    /**
     * 
     * @param type $chart
     * @param type $json
     * @param type $number
     * @return type
     */
    public function get_top_courses($chart = false, $json = false, $number = null) {
        $list = get_courses(null, null, 'c.id,c.fullname,c.startdate');
        $data = [];
        foreach ($list as $value) {
            $course = new \stdClass();
            if ($value->id != 1) {
                $course->id = $value->id;
                $manager = new \course_enrolment_manager($this->page, $course);
                $data[$manager->get_total_users()] = [
                    'id' => $value->id,
                    'name' => $value->fullname,
                    'startdate' => $value->startdate,
                    'count' => $manager->get_total_users()
                ];

                unset($manager);
            }
            krsort($data);
        }
        unset($list);
        $response = $data;
        if ($number != null) {
            $response = array_slice($data, 0, $number);
        }
        if ($chart == true) {
            return $this->json($this->chart($response));
        }
        if ($json == true) {
            return $this->json($response);
        }
        return $response;
    }

    /**
     * 
     * @param type $days
     * @return type
     */
    public function course_created_in() {
        $data = [];
        $days = 30;
        $output = '';
        $now = new \DateTime();
        $curtime = $now->getTimestamp();
        $back = $now->sub(\DateInterval::createFromDateString($days . ' days'));
        $backtime = $back->getTimestamp();
        $records = $this->db->get_records_sql('select id'
                . ' FROM {course} WHERE timecreated < ? AND timecreated > ? '
                . 'AND id != 1', [$curtime, $backtime]);
        return $records;
    }

    /**
     * 
     * @return type
     */
    public function not_pulish_course() {
        $list = get_courses(null, null, 'c.id,c.fullname,c.visible');
        $data = [];
        foreach ($list as $row) {
            if ($row->id != 1) {
                if (!$row->visible) {
                    $data[$row->id] = [
                        'id' => $row->id,
                        'name' => $row->fullname
                    ];
                }
            }
        }
        return $data;
    }

    /**
     * 
     * @param type $top
     * @param type $num
     * @return type
     */
    public function most_popular_course($top, $num) {
        return $this->get_top_courses(false, false, $num)[$top]['name'];
    }

    /**
     * 
     * @param type $courseid
     * @param type $enable
     * @param type $bulk
     * @return boolean
     */
    public function get_activity($courseid = null, $enable = true, $bulk = false) {
        $std = new \stdClass();
        $std->id = $courseid;
        $cminfo = new \completion_info($std);
        if ($cminfo->is_enabled() != $enable) {
            return false;
        }
        /**
         * get_activities function is denfined as private access spcifier thats why we 
         * have used refection to achive the get_acivity features. 
         */
        $class = new \ReflectionClass("\completion_info");
        $property = $class->getProperty("course");
        $property->setAccessible(true);
        $list = $property->getValue($cminfo);
        $listarray = ['id' => $list->id, 'fullname' => $list->fullname, 'activity' => array()];
        foreach ($cminfo->get_activities() as $cm) {
            $listarray['activity'][] = array(
                'id' => $cm->id,
                'name' => $cm->name,
                'type' => $cm->modname,
                'completionstate' => $cminfo->get_data($cm, false, null, $cm)->completionstate
            );
        }
        unset($cminfo);
        if ($bulk) {
            return $listarray;
        }

        return count($listarray['activity']);
    }

    /**
     * 
     * @param type $request
     * @return type
     */
    public function get_course_overview($request) {

        $search = $request['search']['value'];
        $sort = $request['order'][0]['dir'];

        $column = $request['order'][0]['column'];

        if (!empty($search)) {
            $search = "fullname::text LIKE '%" . $request['search']['value'] . "%'";
            $column = 5;
        }
        $coursedata = array();
        switch ($column) {

            case 0:
                $rs = $this->db->get_records('course', null, 'fullname ' . $sort, 'id,fullname,visible', $request['start'], $request['length']);
                unset($rs[1]);
                foreach ($rs as $course) {
                    $complete = 0;
                    $urs = get_enrolled_users(\context_course::instance($course->id), '', 0, 'u.id');
                    foreach ($urs as $user) {
                        $cc = new \completion_completion(['userid' => $user->id, 'course' => $course->id]);
                        if ($cc->is_complete()) {
                            $complete += 1;
                        }
                    }
                    $coursedata[] = [
                        'id' => $course->id,
                        'course' => $course->fullname,
                        'assigned' => count($urs),
                        'completed' => $complete,
                        'completedpercent' => $complete != 0 ? round($complete / count($urs) * 100) : 0,
                        'active' => $course->visible == 1 ? '<span class="label label-success">' . $this->lang('active') . '</span>' : '<span class="label label-warning">' . $this->lang('inactive') . '</span>'
                    ];
                    unset($urs);
                }
                unset($rs);
                break;
            case 1:
                $rs = $this->db->get_records('course', null, '', 'id,fullname,visible', $request['start'], $request['length']);
                unset($rs[1]);
                foreach ($rs as $course) {
                    $complete = 0;
                    $urs = get_enrolled_users(\context_course::instance($course->id), '', 0, 'u.id');
                    foreach ($urs as $user) {
                        $cc = new \completion_completion(['userid' => $user->id, 'course' => $course->id]);
                        if ($cc->is_complete()) {
                            $complete += 1;
                        }
                    }
                    $coursedata[] = [
                        'id' => $course->id,
                        'course' => $course->fullname,
                        'assigned' => count($urs),
                        'completed' => $complete,
                        'completedpercent' => $complete != 0 ? round($complete / count($urs) * 100) : 0,
                        'active' => $course->visible == 1 ? '<span class="label label-success">' . $this->lang('active') . '</span>' : '<span class="label label-warning">' . $this->lang('inactive') . '</span>'
                    ];
                    unset($urs);
                }
                unset($rs);
                switch ($sort) {
                    case 'asc':
                        $this->array_sort($coursedata, 'assigned', SORT_ASC);
                        break;
                    case 'desc':
                        $this->array_sort($coursedata, 'assigned', SORT_DESC);
                        break;
                }
                break;
            case 2:
                $rs = $this->db->get_records('course', null, '', 'id,fullname,visible', $request['start'], $request['length']);
                unset($rs[1]);
                foreach ($rs as $course) {
                    $complete = 0;
                    $urs = get_enrolled_users(\context_course::instance($course->id), '', 0, 'u.id');
                    foreach ($urs as $user) {
                        $cc = new \completion_completion(['userid' => $user->id, 'course' => $course->id]);
                        if ($cc->is_complete()) {
                            $complete += 1;
                        }
                    }
                    $coursedata[] = [
                        'id' => $course->id,
                        'course' => $course->fullname,
                        'assigned' => count($urs),
                        'completed' => $complete,
                        'completedpercent' => $complete != 0 ? round($complete / count($urs) * 100) : 0,
                        'active' => $course->visible == 1 ? '<span class="label label-success">' . $this->lang('active') . '</span>' : '<span class="label label-warning">' . $this->lang('inactive') . '</span>'
                    ];
                    unset($urs);
                }
                switch ($sort) {
                    case 'asc':
                        $this->array_sort($coursedata, 'completed', SORT_ASC);
                        break;
                    case 'desc':
                        $this->array_sort($coursedata, 'completed', SORT_DESC);
                        break;
                }
                break;
            case 3:
                $rs = $this->db->get_records('course', null, '', 'id,fullname,visible', $request['start'], $request['length']);
                unset($rs[1]);
                foreach ($rs as $course) {
                    $complete = 0;
                    $urs = get_enrolled_users(\context_course::instance($course->id), '', 0, 'u.id');
                    foreach ($urs as $user) {
                        $cc = new \completion_completion(['userid' => $user->id, 'course' => $course->id]);
                        if ($cc->is_complete()) {
                            $complete += 1;
                        }
                    }
                    $coursedata[] = [
                        'id' => $course->id,
                        'course' => $course->fullname,
                        'assigned' => count($urs),
                        'completed' => $complete,
                        'completedpercent' => $complete != 0 ? round($complete / count($urs) * 100) : 0,
                        'active' => $course->visible == 1 ? '<span class="label label-success">' . $this->lang('active') . '</span>' : '<span class="label label-warning">' . $this->lang('inactive') . '</span>'
                    ];
                    unset($urs);
                }
                switch ($sort) {
                    case 'asc':
                        $this->array_sort($coursedata, 'completedpercent', SORT_ASC);
                        break;
                    case 'desc':
                        $this->array_sort($coursedata, 'completedpercent', SORT_DESC);
                        break;
                }
                break;
            case 4:
                $rs = $this->db->get_records('course', null, '', 'id,fullname,visible', $request['start'], $request['length']);
                foreach ($rs as $course) {
                    $complete = 0;
                    $urs = get_enrolled_users(\context_course::instance($course->id), '', 0, 'u.id');
                    foreach ($urs as $user) {
                        $cc = new \completion_completion(['userid' => $user->id, 'course' => $course->id]);
                        if ($cc->is_complete()) {
                            $complete += 1;
                        }
                    }
                    $coursedata[] = [
                        'id' => $course->id,
                        'course' => $course->fullname,
                        'assigned' => count($urs),
                        'completed' => $complete,
                        'completedpercent' => $complete != 0 ? round($complete / count($urs) * 100) : 0,
                        'active' => $course->visible == 1 ? '<span class="label label-success">' . $this->lang('active') . '</span>' : '<span class="label label-warning">' . $this->lang('inactive') . '</span>'
                    ];
                    unset($urs);
                }
                switch ($sort) {
                    case 'asc':
                        $this->array_sort($coursedata, 'active', SORT_ASC);
                        break;
                    case 'desc':
                        $this->array_sort($coursedata, 'active', SORT_DESC);
                        break;
                }
                break;
            case 5:
                $sql = 'select id,fullname,visible from {course} where ' . $search . ' ORDER BY fullname ' . $sort . ' OFFSET ' . $request['start'] . '  LIMIT ' . $request['length'];
                $rs = $this->db->get_records_sql($sql);
                $coursedata = array();
                foreach ($rs as $course) {
                    $complete = 0;
                    $users = get_enrolled_users(\context_course::instance($course->id));
                    foreach ($users as $user) {
                        $cc = new \completion_completion(['userid' => $user->id, 'course' => $course->id]);
                        if ($cc->is_complete()) {
                            $complete += 1;
                        }
                    }
                    $coursedata[] = [
                        'id' => $course->id,
                        'course' => $course->fullname,
                        'assigned' => count($users),
                        'completed' => $complete,
                        'completedpercent' => $complete != 0 ? round($complete / count($users) * 100) : 0,
                        'active' => $course->visible == 1 ? '<span class="label label-success">' . $this->lang('active') . '</span>' : '<span class="label label-warning">' . $this->lang('inactive') . '</span>'
                    ];
                    unset($users);
                }
                unset($courses);
                break;
            case 6:
                $index = '';
                foreach ($coursedata as $key => $list) {
                    if ($list['assigned'] == $search || $list['completed'] == $search || $list['completedpercent'] == $search || $list['active'] == $search) {
                        $index = $key;
                        break;
                    }
                }
                $coursedata = $coursedata[$index];
                break;
            default :
                break;
        }
        $coursecount = $this->db->count_records('course');
        $list = [
            "sEcho" => 0,
            "iTotalRecords" => $coursecount,
            "iTotalDisplayRecords" => $coursecount,
            "aaData" => $coursedata
        ];
        return $this->json($list);
    }

    /**
     * 
     * @param type $arr
     * @param type $col
     * @param type $dir
     */
    public function array_sort(&$arr, $col, $dir) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    /**
     * Returns list of completed courses for given userid  
     * 
     * @param int $userid
     * @return array
     */
    public function course_completed() {
        $courses = get_courses("all", "c.sortorder ASC", "c.id,c.fullname");
        $coursedata = array();
        foreach ($courses as $course) {

            $info = new \completion_info($course);
            if ($info->is_enabled()) {
                $complete = 0;
                $users = get_enrolled_users(\context_course::instance($course->id), '', 0, 'u.id');
                foreach ($users as $user) {
                    $cc = new \completion_completion(['userid' => $user->id, 'course' => $course->id]);
                    if ($cc->is_complete()) {
                        $complete += 1;
                    }
                }
                if ($complete != 0) {
                    $completecrs = $course->fullname;
                    $users = null;
                    $coursedata[] = [ 'name' => $completecrs, 'count' => $complete];
                }
            }
        }
        $courses = null;
        return $this->json($this->chart($coursedata));
    }

    /**
     * Returns list of completed courses for given userid  
     * 
     * @param int $userid
     * @return array
     */
    public function user_curse_completed($userid) {
        $data = [];
        /**
         * Returns list of courses for given userid 
         * @param int $userid 
         * @param boolean true only active courses
         */
        $courses = enrol_get_all_users_courses($userid, true);
        foreach ($courses as $course) {
            $cc = new \completion_completion(['userid' => $userid, 'course' => $course->id]);
            if ($cc->is_complete()) {
                $data[] = [
                    'id' => $course->id,
                    'name' => $course->fullname,
                    'completed' => $cc->timecompleted
                ];
            }
        }
        unset($courses);
        return $data;
    }

    public function get_all_category() {
        $data = [];

        $record = $this->db->get_records('course_categories', ['parent' => 0]);
        if ($record) {
            foreach ($record as $key => $value) {
                $data[$key] = $value->name;
            }
        }
        return $this->json($data);
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function get_sub_category($id) {
        $data = ['requestid' => $id, 'status' => false, 'message' => $this->lang('subcatnotfound')];
        $record = $this->db->get_records('course_categories', ['parent' => $id]);
        if ($record) {
            $data['status'] = true;
            foreach ($record as $key => $value) {
                $data['list'] = [
                    $key => $value->name
                ];
            }
        }
        return $this->json($data);
    }

    /**
     * 
     * @param type $categoryid
     * @return type
     */
    public function get_category_courses($categoryid) {
        $data = ['status' => false, 'message' => $this->lang('coursenotavalilable'), 'list' => array()];

        $courses = null;
        if (is_siteadmin()) {
            if (isset($categoryid)) {
                $courses = get_courses($categoryid, "c.sortorder ASC", "c.id,c.fullname");
            } else {
                $courses = enrol_get_my_courses(['id', 'fullname']);
            }
        } else {
            $courses = enrol_get_my_courses(['id', 'fullname']);
        }
        if (isset($courses)) {
            foreach ($courses as $course) {
                $data['list'][$course->id] = $course->fullname;
            }
            if (!empty($data['list'])) {
                $data['status'] = true;
            }
        }
        return $this->json($data);
    }

    /**
     * 
     * @param type $courseid
     * @return type
     */
    public function get_course_users($courseid) {
        global $USER, $DB;
        $allUsers = all_visible_roles();
        $data = ['status' => false, 'message' => $this->lang('nouserincourse'), 'list' => array()];
        if ($courseid != null) {
            if (is_siteadmin()) {
                $users = get_enrolled_users(\context_course::instance($courseid));
            } else if ($allUsers) {
                $users = $DB->get_records_sql("SELECT  u.id as id,u.firstname,u.lastname,
                    ct.instanceid as courseid FROM {course} c
                    JOIN {context} ct ON c.id = ct.instanceid
                    JOIN {role_assignments} ra ON ra.contextid = ct.id
                    JOIN {user} u ON u.id = ra.userid
                    JOIN {role} r ON r.id = ra.roleid
                    WHERE c.id=$courseid AND r.id=5 ORDER BY u.id ASC");
            }
            foreach ($users as $value) {
                $data['list'][$value->id] = $value->firstname . ' ' . $value->lastname;
            }
            unset($users);
            if (!empty($data['list'])) {
                $data['status'] = true;
                unset($data['message']);
            }
        }
        return $this->json($data);
    }

    /**
     * 
     * @param type $courseid
     * @return type
     */
    public function get_course_complete_by_id($courseid) {
        $users = get_enrolled_users(\context_course::instance($courseid), '', 0, 'u.id');
        $count = count_role_users(self::student, \context_course::instance($courseid), false);
        $complete = 0;
        foreach ($users as $user) {
            $cc = new \completion_completion(['userid' => $user->id, 'course' => $courseid]);
            if ($cc->is_complete()) {
                $complete += 1;
            }
        }
        //$uncomplete = $count == 0 ? 10 : ($complete == 0) ? 10 : round(($complete / $count) * 100);
        $uncomplete = $count - $complete;
        $data = [
            ['name' => $this->lang('usercompleted'), 'count' => $complete],
            ['name' => $this->lang('useruncompleted'), 'count' => $uncomplete]
        ];
        unset($users);
        return $this->json($this->chart($data));
    }

    /**
     * 
     * @param type $courseid
     */
    public function get_course_user_list($courseid) {
        $users = get_enrolled_users(\context_course::instance($courseid));
        foreach ($users as $key => $user) {
            
        }
    }

    /**
     * Return's list of activities given course id 
     * 
     * @param type $courseid
     * @param type $json
     * @return boolean
     */
    public function get_activities_types($courseid, $json = false) {
        try {
            $modnames = ['status' => false, 'message' => $this->lang('noactivityincourse')];
            if ($modinfo = get_fast_modinfo($courseid)) {
                foreach ($modinfo->get_cms() as $cmid => $mod) {
                    if (!isset($modnames['list'][$mod->module])) {
                        $modnames['list'][$mod->module] = ucfirst($mod->modname);
                    }
                    unset($mod);
                }
                unset($modinfo);
                if (!empty($modnames['list'])) {
                    $modnames['status'] = true;
                }
                if ($json) {
                    return $this->json($modnames);
                }
                return $modnames;
            }
            return $modnames;
        } catch (\Exception $e) {
            $modnames['message'] = $e->getMessage();
            return $this->json($modnames);
        }
    }

    /**
     * 
     * @param type $modinstance
     * @return type
     */
    public function course_mod_info($modinstance) {
        $data = [];
        $coursemodinfo = \course_modinfo::instance($this->courseid);
        foreach ($coursemodinfo->get_cms() as $key => $modinfo) {
            if ($modinfo->module == $modinstance) {
                $record = $this->db->get_records('quiz_grades', ['quiz' => $modinfo->instance]);

                $data[$modinfo->instance] = [
                    'id' => $modinfo->instance,
                    'name' => $quiz->get_quiz_name(),
                    'list' => []
                ];

                $max = 0;
                $min = 10000;
                foreach ($record as $row) {

                    if (!empty($row->grade)) {

                        if ($row->grade > $max) {
                            $max = $row->grade;
                        }
                        if ($row->grade < $min) {
                            $min = $row->grade;
                        }
                    }
                }
                if ($min == 10000) {
                    $min = 0;
                }
                $data[$modinfo->instance]['list'] = ['max' => round($max, 2), 'min' => round($min, 2)];
            }
        }
        $rowdata = [];
        foreach ($data as $row) {
            if (!empty($row['list'])) {
                $rowdata [] = [
                    'name' => $row['name'],
                    'max' => max($row['list']),
                    'min' => min($row['list'])
                ];
            }
        }
        $cols = array();
        $col1 = ["id" => "", "label" => $this->lang('quiztitle'), "type" => "string"];
        $col2 = ["id" => "", "label" => 'Max score', "type" => "number"];
        $col3 = ["id" => "", "label" => 'Min score', "type" => "number"];
        $cols = array($col1, $col2, $col3);
        $row_data = array();
        $count = 0;
        foreach ($rowdata as $key => $value) {

            $cell1["v"] = $value['name'];
            $cell2["v"] = $value['max'];
            $cell3["v"] = $value['min'];
            $row_data[]["c"] = array($cell1, $cell2, $cell3);
            $count = 0;
        }
        return $this->json(array("cols" => $cols, "rows" => $row_data));
    }

    /**
     * 
     * @param type $courseid
     * @return type
     */
    public function course_quiz_module($courseid) {
        $data = [];
        $coursemodinfo = \course_modinfo::instance($courseid);
        $users = get_enrolled_users(\context_course::instance($courseid), '', 0, 'u.id');

        foreach ($coursemodinfo->get_cms() as $key => $modinfo) {
            if ($modinfo->module == 16 && $modinfo->modname == 'quiz') {

                $quiz = \quiz::create($modinfo->instance);
                $data[$modinfo->instance] = [
                    'id' => $modinfo->instance,
                    'name' => $quiz->get_quiz_name(),
                    'list' => []
                ];
                $max = 0;
                $min = 1000;
                foreach ($users as $id => $userless) {
                    $grade = grade_get_grades($courseid, 'mod', $modinfo->modname, $modinfo->instance, $id);
                    $score = '-';

                    if (isset($grade->items[0])) {
                        $score = (int) $grade->items[0]->grades[$id]->str_grade;
                    }
                    if ($score > $max) {
                        $max = $score;
                    }
                    if ($score < $min) {
                        if ($score != 0) {
                            $min = $score;
                        }
                    }
                }
                if ($min == 1000) {
                    $min = 0;
                }
                if ($max != 0) {
                    $data[$modinfo->instance]['list'] = ['max' => round($max, 2), 'min' => round($min, 2)];
                }
            }
        }
        unset($coursemodinfo);
        unset($users);
        $rowdata = [];
        foreach ($data as $row) {
            if (!empty($row['list'])) {
                $rowdata [] = [
                    'name' => $row['name'],
                    'max' => max($row['list']),
                    'min' => min($row['list'])
                ];
            }
        }
        $cols = array();
        $col1 = ["id" => "", "label" => $this->lang('quiztitle'), "type" => "string"];
        $col2 = ["id" => "", "label" => 'Max score', "type" => "number"];
        $col3 = ["id" => "", "label" => 'Min score', "type" => "number"];
        $cols = array($col1, $col2, $col3);
        $row_data = array();
        $count = 0;
        foreach ($rowdata as $key => $value) {

            $cell1["v"] = $value['name'];
            $cell2["v"] = $value['max'];
            $cell3["v"] = $value['min'];
            $row_data[]["c"] = array($cell1, $cell2, $cell3);
            $count = 0;
        }
        return $this->json(array("cols" => $cols, "rows" => $row_data));
    }

    /**
     * 
     * @param type $courseid
     * @param type $userid
     * @return type
     */
    public function course_quiz_module_user($courseid, $userid) {
        $data = [];
        $coursemodinfo = \course_modinfo::instance($courseid, $userid);
        foreach ($coursemodinfo->get_cms() as $key => $modinfo) {
            if ($modinfo->module == 16 && $modinfo->modname == 'quiz') {
                $record = $this->db->get_records('quiz_grades', ['quiz' => $modinfo->instance]);
                $quiz = \quiz::create($modinfo->instance, $userid);
                $data[$modinfo->instance] = [
                    'id' => $modinfo->instance,
                    'name' => $quiz->get_quiz_name(),
                    'list' => []
                ];

                $max = 0;
                $min = 10000;
                foreach ($record as $row) {

                    if (!empty($row->grade)) {

                        if ($row->grade > $max) {
                            $max = $row->grade;
                        }
                        if ($row->grade < $min) {
                            $min = $row->grade;
                        }
                    }
                }
                if ($min == 10000) {
                    $min = 0;
                }
                $data[$modinfo->instance]['list'] = ['max' => round($max, 2), 'min' => round($min, 2)];
            }
        }
        $rowdata = [];
        foreach ($data as $row) {
            if (!empty($row['list'])) {
                $rowdata [] = [
                    'name' => $row['name'],
                    'max' => $row['list']['max'],
                    'min' => $row['list']['min']
                ];
            }
        }
        $cols = array();
        $col1 = ["id" => "", "label" => $this->lang('quiztitle'), "type" => "string"];
        $col2 = ["id" => "", "label" => 'Max score', "type" => "number"];
        $col3 = ["id" => "", "label" => 'Min score', "type" => "number"];
        $cols = array($col1, $col2, $col3);
        $row_data = array();
        $count = 0;
        foreach ($rowdata as $key => $value) {

            $cell1["v"] = $value['name'];
            $cell2["v"] = $value['max'];
            $cell3["v"] = $value['min'];
            $row_data[]["c"] = array($cell1, $cell2, $cell3);
            $count = 0;
        }
        return $this->json(array("cols" => $cols, "rows" => $row_data));
    }

    /**
     * 
     * @param type $courseid
     * @return type
     */
    public function get_forums($courseid, $json = true) {

        $course = new \stdClass();
        $course->id = $courseid;
        $modinfo = get_fast_modinfo($courseid);
        $data = [];

        foreach ($modinfo->get_instances_of('forum') as $forumid => $cm) {
            $data [] = ['name' => $cm->name, 'count' => $this->db->count_records('forum_discussions', ['forum' => $forumid])];
        }
        if ($json) {
            return $this->json($this->chart($data));
        }
        return $data;
    }

    /**
     * 
     * @param type $courseid
     * @return type
     */
    public function get_user_activity_in_curse($courseid) {

        $users = get_enrolled_users(\context_course::instance($courseid), '', 0, 'u.id');
        $data = [];
        foreach ($users as $id => $user) {
            $data[] = \course_modinfo::instance($courseid, $id);
        }

        return $data;
    }

    /**
     * 
     * @param type $quizid
     * @param type $courseid
     * @param type $userid
     * @param type $json
     * @return type
     */
    public function quiz_info($quizid = 'all', $courseid = null, $userid = null, $json = false) {
        $data = [];
        $coursemodinfo = \course_modinfo::instance($courseid);
        foreach ($coursemodinfo->get_cms() as $key => $modinfo) {

            if ($modinfo->module == 16 && $modinfo->modname == 'quiz') {
                $grade = grade_get_grades($courseid, 'mod', $modinfo->modname, $modinfo->instance, $userid);

                $score = '-';
                if (isset($grade->items[0])) {
                    $score = $grade->items[0]->grades[$userid]->str_grade;
                }
                $quiz = \quiz::create($modinfo->instance, $userid);
                $data[$key] = ['name' => $quiz->get_quiz_name(), 'score' => $score, 'list' => []];
                foreach (quiz_get_user_attempts($modinfo->instance, $userid) as $attempt) {
                    if (array_key_exists($key, $data)) {
                        $qa = $this->attempt_quiz_answer($attempt->id);
                        $data[$key]['list'][] = [
                            'id' => $attempt->id,
                            'attempt' => $attempt->attempt,
                            'timestart' => userdate($attempt->timestart, '%d %b %Y, %H:%M'),
                            'timefinish' => userdate($attempt->timefinish, '%d %b %Y, %H:%M'),
                            'sumgrades' => round($attempt->sumgrades, 1),
                            'correct' => $qa->correct,
                            'incorrect' => $qa->incorrect,
                            'notanswer' => $qa->notanswer
                        ];
                    } else {
                        $data[$key]['list'][] = [
                            'id' => $attempt->id,
                            'attempt' => $attempt->attempt,
                            'timestart' => userdate($attempt->timestart, '%d %b %Y, %H:%M'),
                            'timefinish' => userdate($attempt->timefinish, '%d %b %Y, %H:%M'),
                            'sumgrades' => round($attempt->sumgrades, 1),
                            'correct' => $qa->correct,
                            'incorrect' => $qa->incorrect,
                            'notanswer' => $qa->notanswer
                        ];
                    }
                }
            }
            unset($modinfo);
        }
        unset($coursemodinfo);
        $datalist = [];

        foreach ($data as $key => $row) {
            array_push($datalist, $row);
        }

        $list = [
            "sEcho" => 0,
            "iTotalRecords" => count($datalist),
            "iTotalDisplayRecords" => count($datalist),
            "aaData" => $datalist
        ];
        return $this->json($list);
    }

    /**
     * This function return list of activities for the users inside a course.
     *
     * @param int $courseid
     * @param int $activityid
     * @return array
     */
    public function activity_list_head($courseid) {
        $cols = [];
        $cols[] = $this->lang('name');
        $flag = true;
        foreach (get_fast_modinfo($courseid)->get_cms() as $key => $cminfo) {
            $grade = grade_get_grades($courseid, 'mod', $cminfo->modname, $cminfo->instance);
            if (count($grade->items) > 0) {
                $cols[$key] = $cminfo->name;
            }
        }
        return $cols;
    }

    /**
     * 
     * @param type $request
     * @return type
     */
    public function activity_list($request) {

        $search = $request['search']['value'];
        $sort = $request['order'][0]['dir'];

        $column = $request['order'][0]['column'];

        if (!empty($search)) {
            $search = "(u.firstname LIKE '%" . strtoupper($request['search']['value']) . "%' OR u.lastname LIKE '%" . strtoupper($request['search']['value']) . "%' OR u.email LIKE '%" . $request['search']['value'] . "%')";
            $column = 999;
        }
        $coursedata = array();
        switch ($column) {

            case 0:
                $sort = 'u.firstname ' . $sort;
                $users = get_enrolled_users(\context_course::instance($request['id']), '', 0, 'u.id,u.firstname,u.lastname', $sort, $request['start'], $request['length'], true);
                $data = null;

                $cols = [];
                $cols['name'] = 'Name';
                $flag = true;
                foreach ($users as $user) {
                    $userkey = new \stdClass();
                    $userkey->id = $user->id;
                    $userkey->name = \html_writer::link(new \moodle_url($this->cfg->wwwroot . '/grade/report/user/index.php?id=14&userid=' . $user->id), $user->firstname . ' ' . $user->lastname, ['target' => "_blank"]);

                    $serialobj = serialize($userkey);
                    $cm = get_fast_modinfo($request['id'], $user->id);

                    foreach ($cm->get_cms() as $key => $cminfo) {

                        $grade = grade_get_grades($request['id'], 'mod', $cminfo->modname, $cminfo->instance, $user->id);
                        if (count($grade->items) > 0) {

                            $score = '-';
                            if (isset($grade->items[0])) {
                                $score = $grade->items[0]->grades[$user->id]->str_grade;
                            }

                            $data[$serialobj][] = ['mod' => $cminfo->modname, 'score' => $score];
                            if ($flag) {
                                $cols[$key] = $cminfo->name;
                            }
                            $grade = null;
                        }
                    }
                    $cm = null;
                    $flag = false;
                }

                $coursedata = [];
                if ($data != null) {
                    foreach ($data as $user => $activity) {
                        $row = [];
                        $obj = unserialize($user);
                        $row[] = $obj->name;
                        foreach ($activity as $key => $score) {
                            $row[] = $score['score'];
                        }
                        $coursedata[] = $row;
                    }
                }
                break;
            case 999:
                $sort = 'u.firstname ' . $sort;
                $users = $this->get_enrolled_users_search($request['id'], $search);
                $data = null;

                $cols = [];
                $cols['name'] = 'Name';
                $flag = true;
                foreach ($users as $user) {
                    $userkey = new \stdClass();
                    $userkey->id = $user->id;
                    $userkey->name = \html_writer::link(new \moodle_url($this->cfg->wwwroot . '/grade/report/user/index.php?id=14&userid=' . $user->id), $user->firstname . ' ' . $user->lastname, ['target' => "_blank"]);

                    $serialobj = serialize($userkey);
                    $cm = get_fast_modinfo($request['id'], $user->id);

                    foreach ($cm->get_cms() as $key => $cminfo) {
                        $grade = grade_get_grades($request['id'], 'mod', $cminfo->modname, $cminfo->instance, $user->id);
                        if (count($grade->items) > 0) {

                            $score = '-';
                            if (isset($grade->items[0])) {
                                $score = $grade->items[0]->grades[$user->id]->str_grade;
                            }

                            $data[$serialobj][] = ['mod' => $cminfo->modname, 'score' => $score];
                            if ($flag) {
                                $cols[$key] = $cminfo->name;
                            }
                            $grade = null;
                        }
                    }
                    $cm = null;
                    $flag = false;
                }

                $coursedata = [];
                if ($data != null) {
                    foreach ($data as $user => $activity) {
                        $row = [];
                        $obj = unserialize($user);
                        $row[] = $obj->name;
                        foreach ($activity as $key => $score) {
                            $row[] = $score['score'];
                        }
                        $coursedata[] = $row;
                    }
                    //return ['cols'=>$cols,'rows'=>$rows];
                }
                //return ['cols'=>['Course status'],'rows'=>[['No records available']]];
                break;
            default :
                $sortname = ''; //'u.firstname '.$sort;
                $users = get_enrolled_users(\context_course::instance($request['id']), '', 0, 'u.id,u.firstname,u.lastname', $sortname, $request['start'], $request['length'], true);
                $data = null;

                $cols = [];
                $cols['name'] = 'Name';
                $flag = true;
                foreach ($users as $user) {
                    $userkey = new \stdClass();
                    $userkey->id = $user->id;
                    $userkey->name = \html_writer::link(new \moodle_url($this->cfg->wwwroot . '/grade/report/user/index.php?id=14&userid=' . $user->id), $user->firstname . ' ' . $user->lastname, ['target' => '_blank']);

                    $serialobj = serialize($userkey);
                    $cm = get_fast_modinfo($request['id'], $user->id);

                    foreach ($cm->get_cms() as $key => $cminfo) {

                        $grade = grade_get_grades($request['id'], 'mod', $cminfo->modname, $cminfo->instance, $user->id);
                        if (count($grade->items) > 0) {

                            $score = '-';
                            if (isset($grade->items[0])) {
                                $score = $grade->items[0]->grades[$user->id]->str_grade;
                            }

                            $data[$serialobj][] = ['mod' => $cminfo->modname, 'score' => $score];
                            if ($flag) {
                                $cols[$key] = $cminfo->name;
                            }
                            $grade = null;
                        }
                    }
                    $cm = null;
                    $flag = false;
                }

                $cdata = [];
                if ($data != null) {
                    foreach ($data as $user => $activity) {
                        $row = [];
                        $obj = unserialize($user);
                        $row[] = $obj->name;
                        foreach ($activity as $key => $score) {
                            $row[] = $score['score'];
                        }
                        $cdata[] = $row;
                        //print_r($row);
                    }
                    $coursedata = $this->array_sort_multi($cdata, $column, $sort);
                }
                break;
        }
        $coursecount = count_enrolled_users(\context_course::instance($request['id']), '', 0, false);
        $list = [
            "sEcho" => 0,
            "iTotalRecords" => $coursecount,
            "iTotalDisplayRecords" => $coursecount,
            "aaData" => $coursedata
        ];
        return $this->json($list);
    }

    /**
     * 
     * @param type $courseid
     * @param type $search
     * @return type
     */
    public function get_enrolled_users_search($courseid, $search) {
        return $this->db->get_records_sql("SELECT c.id,u.id,u.firstname,u.lastname FROM {course} c
            JOIN {context} ct ON c.id = ct.instanceid
            JOIN {role_assignments} ra ON ra.contextid = ct.id
            JOIN {user} u ON u.id = ra.userid
            JOIN {role} r ON r.id = ra.roleid
            WHERE c.id = $courseid AND ($search)");
    }

    /**
     * 
     * @param type $array
     * @param type $on
     * @param type $order
     * @return type
     */
    function array_sort_multi($array, $on, $order = SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case 'asc':
                    asort($sortable_array);
                    break;
                case 'desc':
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[] = $array[$k];
            }
        }

        return $new_array;
    }

    /**
     * 
     * @param type $courseid
     * @return type
     */
    public function upcoming_activity($courseid) {
        $course = new \stdClass();
        $course->id = $courseid;
        $course->groupmode = '';
        $course->groupmodeforce = '';
        list($courses, $group, $user) = calendar_set_filters([$courseid => $course]);
        $lookahead = get_user_preferences('calendar_lookahead', CALENDAR_DEFAULT_UPCOMING_LOOKAHEAD);
        $maxevents = get_user_preferences('calendar_maxevents', CALENDAR_DEFAULT_UPCOMING_MAXEVENTS);
        $events = calendar_get_upcoming($courses, $group, $user, $lookahead, $maxevents);
        return $events;
    }

    /**
     * 
     * @param type $param
     */
    public function is_gradeable($param) {
        
    }

    /**
     * 
     * @return type
     */
    public function functionName() {
        return grade_get_gradable_activities(14, 'forum');
        get_coursemodules_in_course('quiz', 7);
    }

    /**
     * 
     * @param type $activity
     * @return type
     */
    public function get_activity_grade($activity) {

        $users = get_enrolled_users(\context_course::instance($this->courseid));
        $data['users'] = count($users);

        if ($activities = grade_get_gradable_activities($this->courseid, $activity)) {

            foreach ($activities as $actid => $activity) {
                $data['activity'][$actid] = ['name' => $activity->name];
                $max = 0;
                $min = 10000;
                foreach ($users as $id => $userless) {
                    $grade = grade_get_grades($this->courseid, 'mod', $activity->modname, $activity->instance, $id);
                    $score = '-';


                    if (isset($grade->items[0])) {
                        $score = (int) $grade->items[0]->grades[$id]->str_grade;
                    }
                    if ($score > $max) {
                        $max = $score;
                    }
                    if ($score < $min) {
                        if ($score != 0) {
                            $min = $score;
                        }
                    }
                }
                if ($min == 10000) {
                    $min = 0;
                }
                $data['activity'][$actid]['score'] = ['max' => $max, 'min' => $min];
            }
        } else {
            $data['activity'] = [];
        }
        $rowdata = [];
        foreach ($data['activity'] as $row) {
            $rowdata [] = [
                'name' => $row['name'],
                'max' => $row['score']['max'],
                'min' => $row['score']['min']
            ];
        }
        $cols = array();
        $col1 = ["id" => "", "label" => '', "type" => "string"];
        $col2 = ["id" => "", "label" => 'Max score', "type" => "number"];
        $col3 = ["id" => "", "label" => 'Min score', "type" => "number"];
        $cols = array($col1, $col2, $col3);
        $row_data = array();
        $count = 0;
        foreach ($rowdata as $key => $value) {

            $cell1["v"] = $value['name'];
            $cell2["v"] = $value['max'];
            $cell3["v"] = $value['min'];
            $row_data[]["c"] = array($cell1, $cell2, $cell3);
            $count = 0;
        }
        return $this->json(array("cols" => $cols, "rows" => $row_data));
    }

    /**
     * 
     * @param type $activity
     * @return type
     */
    public function get_activity_grade_line($activity) {

        $users = get_enrolled_users(\context_course::instance($this->courseid));
        $data['users'] = count($users);

        if ($activities = grade_get_gradable_activities($this->courseid, $activity)) {

            /**
             * Grade ableactivity
             */
            foreach ($activities as $actid => $activity) {
                $data['activity'][$actid] = ['name' => $activity->name];

                $max = 0;
                $min = 10000;
                foreach ($users as $id => $userless) {
                    $grade = grade_get_grades($this->courseid, 'mod', $activity->modname, $activity->instance, $id);
                    $score = '-';

                    if (isset($grade->items[0])) {
                        $score = (int) $grade->items[0]->grades[$id]->str_grade;
                    }
                    if ($score > $max) {
                        $max = $score;
                    }
                    if ($score < $min) {
                        if ($score != 0) {
                            $min = $score;
                        }
                    }
                }

                if ($min == 10000) {
                    $min = 0;
                }
                $data['activity'][$actid]['score'] = ['max' => $max, 'min' => $min];
            }
            $rowdata = [];
            foreach ($data['activity'] as $row) {
                $rowdata [] = [
                    'name' => $row['name'],
                    'max' => $row['score']['max'],
                    'min' => $row['score']['min']
                ];
            }
            $cols = array();
            $col1 = ["id" => "", "label" => $this->lang('quiztitle'), "type" => "string"];
            $col2 = ["id" => "", "label" => 'Max score', "type" => "number"];
            $col3 = ["id" => "", "label" => 'Min score', "type" => "number"];
            $cols = array($col1, $col2, $col3);
            $row_data = array();
            $count = 0;
            foreach ($rowdata as $key => $value) {

                $cell1["v"] = $value['name'];
                $cell2["v"] = $value['max'];
                $cell3["v"] = $value['min'];
                $row_data[]["c"] = array($cell1, $cell2, $cell3);
                $count = 0;
            }
            if (($row_data[0]["c"][1]["v"] != 0) && (!empty($row_data))) {
                $row_data1 = $row_data;
            } else {
                $row_data1 = '';
            }
            return $this->json(array("cols" => $cols, "rows" => $row_data1));
        } else {
            /**
             * Non - Gradeable activity
             */
            $lang = '';
            $modinfo = get_fast_modinfo($this->courseid);
            $data = null;
            foreach ($modinfo->get_instances_of($activity) as $id => $cm) {
                switch ($cm->modname) {
                    case 'url':
                        $lang = 'maxviews';
                        $log = $this->logs('cmid', $cm->id, ['cmid']);
                        $data[] = ['name' => $cm->name, 'count' => count($log)];
                        break;
                    case 'forum':
                        $lang = 'maxdiscussion';
                        $log = $this->db->count_records('forum_discussions', ['forum' => $id]);
                        $data[] = ['name' => $cm->name, 'count' => $log];
                        break;
                    // Added by Shiuli for eduplayer and edustream maxview.
                    case 'eduplayer':
                        $lang = 'maxeduplayer';
                        $log = $this->logs('cmid', $cm->id, ['cmid']);
                        $data[] = ['name' => $cm->name, 'count' => count($log)];
                        break;
                    case 'edustream':
                        $lang = 'maxedustream';
                        $log = $this->logs('cmid', $cm->id, ['cmid']);
                        $data[] = ['name' => $cm->name, 'count' => count($log)];
                        break;
                    default:
                        break;
                }
            }
            $cols = array();
            $col1 = ["id" => "", "label" => "", "type" => "string"];
            $col2 = ["id" => "", "label" => "", "type" => "number"];
            $cols = array($col1, $col2);
            $row_data = array();
            $count = 0;
            if (isset($data)) {
                foreach ($data as $key => $value) {
                    $cell1["v"] = $value['name'];
                    $cell2["v"] = $value['count'];
                    $row_data[]["c"] = array($cell1, $cell2);
                    $count = 0;
                }
            }
            if (($row_data[0]["c"][1]["v"] != 0) && (!empty($row_data))) {
                $row_data1 = $row_data;
            } else {
                $row_data1 = '';
            }
            return $this->json(array("cols" => $cols, "rows" => $row_data1));
        }
    }

    /**
     * 
     * @param type $activity
     * @return type
     */
    public function get_activity_grade_pie($activity) {
        $users = get_enrolled_users(\context_course::instance($this->courseid));
        $data['users'] = count($users);
        $data['useracces'] = [];
        if ($activities = grade_get_gradable_activities($this->courseid, $activity)) {

            foreach ($activities as $actid => $activity) {

                $data['useracces'][$actid] = ['name' => $activity->name, 'count' => 0];

                foreach ($users as $id => $userless) {
                    $grade = grade_get_grades($this->courseid, 'mod', $activity->modname, $activity->instance, $id);
                    $score = '-';

                    if (isset($grade->items[0])) {
                        $score = (int) $grade->items[0]->grades[$id]->str_grade;
                    }
                    if ($score != '-') {
                        if (!isset($data['useracces'][$actid])) {
                            $data['useracces'][$actid]['count'] = 1;
                        } else {
                            $data['useracces'][$actid]['count'] += 1;
                        }
                    }
                }
            }
            $cols = array();
            $col1 = ["id" => "", "label" => $this->lang('quiztitle'), "type" => "string"];
            $col2 = ["id" => "", "label" => 'Max score', "type" => "number"];
            $cols = array($col1, $col2);
            $row_data = array();
            $count = 0;
            foreach ($data['useracces'] as $key => $value) {

                $cell1["v"] = $value['name'];
                $cell2["v"] = $value['count'];
                $row_data[]["c"] = array($cell1, $cell2);
                $count = 0;
            }
            if (($row_data[0]["c"][1]["v"] != 0) && (!empty($row_data))) {
                $row_data1 = $row_data;
            } else {
                $row_data1 = '';
            }
            return $this->json(array("cols" => $cols, "rows" => $row_data1));
        } else {

            $modinfo = get_fast_modinfo($this->courseid);
            $data = null;
            foreach ($modinfo->get_instances_of($activity) as $id => $cm) {

                switch ($cm->modname) {
                    case 'url':
                        $log = $this->logs('cmid', $cm->id, ['cmid']);
                        $data[] = ['name' => $cm->name, 'count' => count($log)];
                        break;
                    case 'forum':
                        if ($count = count(forum_count_discussion_replies($id))) {
                            $data[] = ['name' => $cm->name, 'count' => 0];
                        }
                        break;
                    // Added by Shiuli for eduplayer and edustream maxview.
                    case 'eduplayer':
                        $log = $this->logs('cmid', $cm->id, ['cmid']);
                        $data[] = ['name' => $cm->name, 'count' => count($log)];
                        break;
                    case 'edustream':
                        $log = $this->logs('cmid', $cm->id, ['cmid']);
                        $data[] = ['name' => $cm->name, 'count' => count($log)];
                        break;
                    default:
                        break;
                }
            }

            $cols = array();
            $col1 = ["id" => "", "label" => $this->lang('quiztitle'), "type" => "string"];
            $col2 = ["id" => "", "label" => $this->lang('maxdiscussion'), "type" => "number"];
            $cols = array($col1, $col2);
            $row_data = array();
            $count = 0;
            if ($data) {
                foreach ($data as $key => $value) {
                    $cell1["v"] = $value['name'];
                    $cell2["v"] = $value['count'];
                    $row_data[]["c"] = array($cell1, $cell2);
                    $count = 0;
                }
            }
            if (($row_data[0]["c"][1]["v"] != 0) && (!empty($row_data))) {
                $row_data1 = $row_data;
            } else {
                $row_data1 = '';
            }
            return $this->json(array("cols" => $cols, "rows" => $row_data1));
//            }
//            return $data;
        }
    }

    /**
     * 
     * @param type $activity
     * @return type
     */
    public function get_activity_grade_user($activity) {

        $users = get_enrolled_users(\context_course::instance($this->courseid), '', 0, 'u.id,u.firstname,u.lastname');
        $data = null;

        $cols = [];
        $cols['name'] = 'Name';
        $flag = true;
        if ($activities = grade_get_gradable_activities($this->courseid, $activity)) {

            foreach ($activities as $key => $cminfo) {

                foreach ($users as $user) {
                    $userkey = new \stdClass();
                    $userkey->id = $user->id;
                    $userkey->name = \html_writer::link(new \moodle_url($this->cfg->wwwroot . '/user/profile.php?id=' . $user->id), $user->firstname . ' ' . $user->lastname, ['target' => "_blank"]);

                    $serialobj = serialize($userkey);


                    $grade = grade_get_grades($this->courseid, 'mod', $cminfo->modname, $cminfo->instance, $user->id);
                    $score = '-';
                    if (isset($grade->items[0])) {
                        $score = $grade->items[0]->grades[$user->id]->str_grade;
                    }

                    $data[$serialobj][$key] = ['mod' => $cminfo->modname, 'score' => $score];
                    if ($flag) {
                        $cols[$key] = $cminfo->name;
                    }
                    $grade = null;
                }
            }
            $cm = null;
            $flag = false;
        }
        $rows = [];
        if ($data != null) {
            foreach ($data as $user => $activity) {
                $row = [];
                $obj = unserialize($user);
                $row[] = $obj->name;
                foreach ($activity as $key => $score) {
                    $row[$key] = $score['score'];
                }
                $rows[] = $row;
            }
            return ['cols' => $cols, 'rows' => $rows];
        }
        return $data;
    }

    /**
     * 
     * @param type $attemptid
     * @return \stdClass
     */
    public function attempt_quiz_answer($attemptid) {

        $attemptobj = \quiz_attempt::create($attemptid);
        $qa = new \stdClass();
        $qa->correct = 0;
        $qa->incorrect = 0;
        $qa->notanswer = 0;
        foreach ($attemptobj->get_slots() as $slot) {
            $gqa = $attemptobj->get_question_attempt($slot);
            $options = new \question_display_options();
            $sc = $options->correctness && $gqa->has_marks();

            if ($this->get_state_string($gqa, $sc) == 'Correct') {
                $qa->correct += 1;
            }
            if ($this->get_state_string($gqa, $sc) == 'Incorrect') {
                $qa->incorrect += 1;
            }
            if ($this->get_state_string($gqa, $sc) == 'Not answered') {
                $qa->notanswer += 1;
            }
        }
        return $qa;
    }

    /**
     * 
     * @param \question_attempt $qa
     * @param type $showcorrectness
     * @return type
     */
    protected function get_state_string(\question_attempt $qa, $showcorrectness) {
        if ($qa->get_question()->length > 0) {
            return $qa->get_state_string($showcorrectness);
        }
        if ($qa->get_state() == question_state::$todo) {
            return get_string('notyetviewed', 'quiz');
        } else {
            return get_string('viewed', 'quiz');
        }
    }

    /**
     * 
     * @return type
     */
    public function category_course() {
        $data = null;

        foreach (\coursecat::make_categories_list() as $key => $value) {
            $data[] = ['name' => $value, 'count' => count(get_courses($key, '', "c.id"))];
        }
        return $this->json($this->chart($data));
    }

    /**
     * 
     * @return type
     */
    public function cat_list() {
        $data = [];
        foreach ($this->db->get_records('course_categories', ['parent' => 0]) as $id => $parent) {
            $data[$id] = ['id' => $id, 'name' => $parent->name, 'child' => []];
            if ($this->db->record_exists('course_categories', ['parent' => $id])) {
                foreach ($this->db->get_records('course_categories', ['parent' => $id]) as $subid => $child) {
                    $data[$id]['child'][$subid] = ['name' => $child->name];
                }
            }
        }
        return $data;
    }

    /**
     * Returns list of category with all sub category
     * @param type $parent it is optional no need to pass in function, just use '' in first
     *              parameter.
     * @param type $optional is used when you don't want Miscellaneous category.
     *              just pass false in second parameter
     * @return arrays
     */
    public function category_tree($parent = 0, $optional = true) {
        global $DB;
        $data = array();
        $resultset = $DB->get_records('course_categories', ['parent' => $parent]);
        if ($resultset) {
            foreach ($resultset as $id => $row) {
                if ($optional || $id != 1) {
                    $data[$id] = ['id' => $row->id, 'name' => $row->name, 'flag' => category_tree($row->id)];
                }
            }
        }
        return $data;
    }

}

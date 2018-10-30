<?php

// This file is part of Lmsofindia - http://lmsofindia.com
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
 * @package multitenant\core\classes
 * @author  Shambhu Kumar {@email shambhu384@gmail.com}
 * @copyright 2016 onwards Lmsofindia {@link http://lmsofindia.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*
|----------------------------
| Ajax callback
|
| Recive Ajax Request and
| response JSON format
|---------------------------
*/
define('AJAX_SCRIPT', true);
header('Content-Type: application/json');
require_once(dirname(__FILE__) . '/../config.php');
include('lib.php');
global $CFG;
require_once($CFG->libdir.'/weblib.php');

$callback = clean_param($_REQUEST['callback'], PARAM_RAW_TRIMMED);

unset($_REQUEST['callback']);
$params = array();

foreach($_REQUEST as $field => $value) {
    $params[$field] = clean_param($value, PARAM_RAW_TRIMMED);
}

$response = array();

try {
    if(!function_exists($callback)) {
        throw new Exception($callback);
    }
    //print_object($params);
   $response = call_user_func_array($callback, $params);
} catch (Exception $e) {
   $response['error:'] = array(
                                'message' => $e->getMessage(),
                                'type' => get_class($e)
                              );
}

//print_object($response);
echo json_encode($response, JSON_PRETTY_PRINT);

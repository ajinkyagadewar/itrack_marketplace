<?php
/**
 * BuyKart Version file
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	Thomas Threadgold
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$plugin->component = 'local_buykart';
$plugin->version = 2015100610;
$plugin->release = '2.8 (Build: 2015012900)';
$plugin->requires = 2015012900;
//$plugin->requires = 2014111000;
$plugin->maturity = MATURITY_BETA;
$plugin->dependencies = array(
	'enrol_buykart' => 2014111000,
);
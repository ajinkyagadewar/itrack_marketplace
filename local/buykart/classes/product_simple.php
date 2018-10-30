<?php 
/**
 * Buykart Simple Product
 *
 * @package     local
 * @subpackage  local_buykart
 * @author   	Thomas Threadgold
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Load Moodle config
require_once dirname(__FILE__) . '/../../../config.php';
// Load Buykart lib
require_once dirname(__FILE__) . '/../lib.php';

class BuykartProductSimple extends BuykartProduct {

	/**
	 * A single product variation
	 * @var BuykartProductVariation
	 */
	protected $_simpleVariation;

	/**
	 * Loads the product from the DB, incuding simple product info
	 * @param  int 		$id		BuykartProduct id
	 * @return [type]     		[description]
	 */
	public function load($id) {
		parent::load($id);

		$this->_simpleVariation = new BuykartProductVariation($id);
	}

	/**
	 * Maps the enrolment duration function to the ProductVariation method
	 * @param  boolean $format 		If true, then format the duration as a string
	 * @return string/int       	Duration as a string, or int
	 */
	public function get_duration($format = true){
		return $this->_simpleVariation->get_duration($format);
	}

	/**
	 * Maps the price function to the ProductVariation method
	 * @return float price
	 */
	public function get_price(){
		return $this->_simpleVariation->get_price();
	}

	/**
	 * Maps the group getter function to the ProductVariation method
	 * @return int 		groupid
	 */
	public function get_group(){
		return $this->_simpleVariation->get_group();
	}

}
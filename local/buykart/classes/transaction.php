<?php 
/**
 * Buykart Transaction
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

class BuykartTransaction {

	/**
	 * The transaction STATUS constants
	 * @var int
	 */
	const STATUS_NOT_SUBMITTED = 0;
	const STATUS_PENDING = 1;
	const STATUS_COMPLETE = 2;
	const STATUS_FAILED = 3;

	/**
	 * The BuykartTransaction id
	 * @var int
	 */
	protected $_id;

	/**
	 * The transaction ID returned from 
	 * @var string
	 */
	protected $_txnId;

	/**
	 * The user associated with this transaction
	 * @var int
	 */
	protected $_userId;

	/**
	 * The payment gateway used for this transaction
	 * @var string
	 */
	protected $_gateway;

	/**
	 * The current status of this transaction (use class constants to define, or compare)
	 * @var int
	 */
	protected $_status;

	/**
	 * The date of the transaction
	 * @var datetime	
	 */
	protected $_purchaseDate;

	/**
	 * An array of BuykartTransactionItems belonging to this transaction
	 * @var array
	 */
	protected $_items;

	/**
	 * Constructor. If passed an id, will load the transaction from the DB, otherwise will create a new one
	 * @param int $id transaction_id
	 */
	function __construct($id = null){

		$this->_items = array();
		
		if( !is_null($id) && is_int($id) ) {
			// Try load from DB
			$this->load($id);
		} else {
			// Create a new transaction in the DB
			$this->create();
		}
	}

	/**
	 * Creates a new transaction instance in the DB
	 * @return void 
	 */
	private function create(){
		global $DB, $USER;

		$newRecord 					= new stdClass();
		$newRecord->status 			= self::STATUS_NOT_SUBMITTED;
		$newRecord->txnId 			= '';
		$newRecord->user_id 		= $USER->id;
		//$newRecord->gateway 		= get_config('local_buykart', 'active_gateway'); // get from config which gateway they're using?
		$newRecord->purchase_date 	= time();

		$this->_id = $DB->insert_record('local_buykart_transaction', $newRecord, true);
	}

	/**
	 * Updates the DB table with the current class properties
	 * @return void
	 */
	private function update(){
		global $DB;
		//echo 'updated';
		$updatedRecord 					= new stdClass();
		$updatedRecord->id 				= $this->_id;
		$updatedRecord->user_id 		= $this->_userId;
		$updatedRecord->txn_id 			= $this->_txnId;
		$updatedRecord->status 			= $this->_status;
		$updatedRecord->gateway 		= $this->_gateway;
		$updatedRecord->purchase_date 	= $this->_purchaseDate; 
		$DB->update_record('local_buykart_transaction', $updatedRecord);
	}

	/**
	 * Loads the transaction details from the DB
	 * @param  int 		$id 	transaction_id
	 * @return void    
	 */
	private function load($id){
		global $DB;

		// Get the transaction record
		$record = $DB->get_record('local_buykart_transaction', array('id' => $id));

		// Set the data
		if (!!$record) {
			$this->_id 				= (int) $id;
			$this->_txnId 			= (int) $record->txn_id;
			$this->_userId 			= (int) $record->user_id;
			$this->_gateway 		= 		$record->gateway;
			$this->_status 			= (int) $record->status;
			$this->_purchaseDate 	= 		$record->purchase_date;

			// Load the transaction items
			$this->load_items();
		} else {
        	throw new Exception('Unable to load transaction using identifier: ' . $id);
   		}
	}

	/**
	 * Loads the items associated with this transaction
	 * @return void 
	 */
	private function load_items(){
		global $DB;

		$loadedItems = array();

		$records = $DB->get_records('local_buykart_trans_item', array('transaction_id'=> $this->_id ));

		// Go through the item records and add them to this transaction's items array
		if( !!$records ) {
			foreach($records as $record) {
				$id = (int) $record->id;
				$loadedItems[$id] = new BuykartTransactionItem($id);
			}
		}

		$this->_items = $loadedItems;
	}

	/**
	 * This function clears the items associated with the transaction
	 * @return void
	 */
	public function reset(){
		global $DB;

		// If status is complete, then this transaction has already been processed
		// Therefore, we want to make a new one
		if( $this->_status === self::STATUS_COMPLETE ) {
			// Create a new DB instance
			$this->create();
		} else {
			// If status is not complete, we want to clear the items from the DB and this instance
			$DB->delete_records('local_buykart_trans_item', array('transaction_id' => $this->_id));
		}

		// Reset the items array
		$this->_items = array();
	}

	/**
	 * Sets the transaction status to be marked as fail
	 * Calls update to the DB
	 * @return void
	 */
	public function fail(){
		$this->_status = self::STATUS_FAILED;
		$this->update();
	}

	/**
	 * Sets the transaction status to be marked as pending
	 * @return void
	 */
	public function pending(){
		$this->_status = self::STATUS_PENDING;
		$this->update();
	}

	/**
	 * Sets the transaction to be marked as complete
	 * Calls update to the DB
	 * @return void
	 */
	public function complete(){
		$this->_status = self::STATUS_COMPLETE;
		$this->_purchaseDate = time();
		$this->update();
	}

	/**
	 * Returns an item from the list of items associated with this Transaction
	 * @param  int 							$id 	BuykartTransactionItem id
	 * @return BuykartTransactionItem|bool     		the item, or false if it doesn't exist
	 */
	public function get_item($id) {
		// Ensure id is an int
		$id = (int) $id;

		// Check if the item is part of this transaction,
		// if so, return it
		if( array_key_exists($id, $this->_items) ){
			return $this->_items[$id];
		}

		return false;
	}

	/**
	 * Returns the array of items belonging to this transaction
	 * @return array items
	 */
	public function get_items(){
		return $this->_items;
	}

	public function add($productId, $price, $variationId = 0){

		// Create a new BuykartTransactionItem
		$newItem = new BuykartTransactionItem();
		$newItemID = $newItem->create($this->_id, $productId, $price, $variationId);

		// Add it to the list of transaction items
		$this->_items[$newItemID] = $newItem; 
	}

	/**
	 * Returns the BuykartTransaction id
	 * @return int id
	 */
	public function get_id(){
		return $this->_id;
	}

	/**
	 * Returns the user id who made the transaction
	 * @return int userId
	 */
	public function get_user_id(){
		return $this->_userId;
	}

	/**
	 * Returns the gateway's transaction id
	 * @return string txnId
	 */
	public function get_txn_id(){
		return $this->_txnId;
	}

	/**
	 * Sets the txnId for this transaction, as returned by
	 * @param [type] $txnId [description]
	 */
	public function set_txn_id($txnId){
		$this->_txnId = $txnId;
		//print_object($txnId);
		$this->update();
	}

	/**
	 * Returns the gateway used for this transaction
	 * @return string gateway
	 */
	public function get_gateway(){
		return $this->_gateway;
	}

	/**
	 * Sets the gateway for this transaction (use lib file constants)
	 * @param string 	$gate 		BUYKART_GATEWAY_PAYPAL|BUYKART_GATEWAY_EBS|BUYKART_GATEWAY_PAYUMONEY
	 */	
	public function set_gateway($gate){
		$this->_gateway = $gate;
		$this->update();
	}

	/**
	 * Returns the status of the transaction
	 * @return int status
	 */
	public function get_status(){
		return $this->_status;
	}

	/**
	 * Returns the purchase date
	 * @return datetime purchaseDate;
	 */
	public function get_date(){
		return $_purchaseDate;
	}

	/**
	 * Returns the amount for this transaction
	 * @return float 
	 */
	public function get_cost(){
		$amount = 0.00;

		foreach ($this->_items as $item) {
			$amount += $item->get_cost();
		}

		return $amount;
	}
}

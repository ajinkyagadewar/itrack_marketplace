<?php
// This file is part of MoodleofIndia - http://www.moodleofindia.com/
/**
 * This script implements the manage_license of the dashboard, and allows editing
 * of the fooboo License.
 *
 * Local Educoupons 
 * @author     Arjun Singh <arjunsingh@elearn10.com>
 * @package    local_educoupons
 * @copyright  20/10/2016 lms of india
 * @license    http://lmsofindia.com/
 */

require_once(dirname(__FILE__) . '../../../config.php');
require_once(dirname(__FILE__) . '../../../my/lib.php');
require_once(dirname(__FILE__) . '../../../lib/phpexcel/PHPExcel.php');
// Instantiate a new PHPExcel object 
$objPHPExcel = new PHPExcel();
// Set the active Excel worksheet to sheet 0 
$objPHPExcel->setActiveSheetIndex(0);
// Initialise the Excel row number 
$rowCount = 1;

//start of printing column names as names of MySQL fields  
$column = 'A';
global $CFG, $DB;
$query = "Select id AS 'iD', ppcode AS 'promotion code',FROM_UNIXTIME(ppstartdate) AS 'promotion start date',FROM_UNIXTIME(ppenddate) AS 'promotion end date',ppcent AS discount,ppflag AS active,ppreason AS 'usage upto',ppaplied AS 'already used'  FROM {enrol_paypal_promo} ORDER BY id ";
$rs = $DB->get_records_sql($query);
foreach ($rs as $key1 => $value1) {
    
}
foreach ($value1 as $key => $value) {
    $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $key);
    $column++;
}

//end of adding column names  
//start while loop to get data  
$rowCount = 2;

foreach ($rs as $key1 => $value1) {
    $column = 'A';
    foreach ($value1 as $key => $value) {
        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $value);
        $column++;
    }
    $rowCount++;
}


// Redirect output to a client’s web browser (Excel5) 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="paypalpromo_Results.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>

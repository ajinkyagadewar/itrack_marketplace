<?php
require_once('vendor/autoload.php');
$arrCnt = $_POST["arrCnt"];
$name = array();
$var = array();
for( $i = 0; $i < $arrCnt; $i++ ) {
  $var []= $_POST["name".$i];
//  if( $var != "" ) array_push($name, $var );
}
$objPHPExcel = new PHPExcel();
$coupons_array = unserialize($_POST['input_name']);
$objPHPExcel->getActiveSheet()->fromArray($var, null, 'A1');

$sheet = $objPHPExcel->getActiveSheet();

$sheet->getStyle('B1')->getAlignment()->applyFromArray(
    array('vartical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$filename = 'coupons.xls';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

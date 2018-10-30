<?php

require_once('../../config.php');
require_once('vendor/autoload.php');
global $CFG, $DB;

$url = $_SERVER['QUERY_STRING'];
$impl = explode('&', $url);
$param1 = explode('=', $impl[0]);
$param2 = explode('=', $impl[1]);
$courseid = $param1[1];
$cpid = $param2[1];

$couponrecords = $DB->get_record('educoupons', ['courseid' => $courseid, 'id' => $cpid]);

$arrCnt = $couponrecords->noc;

$ccodes = $DB->get_records('edu_couponcode', array('tid' => $couponrecords->trackid), 'couponcode');
foreach ($ccodes as $ccode) {
    $var[] = $ccode->couponcode;
}

$objPHPExcel = new PHPExcel();
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

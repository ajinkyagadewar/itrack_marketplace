<?php
require(dirname(dirname(dirname(__FILE__))).'/config.php');
global $DB;
if (isset($_REQUEST)) {
    $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : null;
    $data = array();
    if ($mode) {
        switch($mode) {
            case 'specialization':
                $username = isset($_REQUEST['data']) ? $_REQUEST['data'] : null;
                if ($username) {
                    $userdetailsq = "SELECT  name FROM {block_eduopen_master_special} WHERE name LIKE '%$username%' ";
                    $userdetailsrs = $DB->get_records_sql($userdetailsq);
                    if ($userdetailsrs) {
                        foreach ($userdetailsrs as $userdetails) {
                            $firstsname = $userdetails->name;
                            $data[] = $firstsname;
                        }
                        echo json_encode($data);
                    }
                }
            break;
            case 'institution':
                $username = isset($_REQUEST['data']) ? $_REQUEST['data'] : null;
                if ($username) {
                    $userdetailsq = "SELECT name FROM {block_eduopen_master_inst} WHERE name LIKE '%$username%' ";
                    $userdetailsrs = $DB->get_records_sql($userdetailsq);
                    if ($userdetailsrs) {
                        foreach ($userdetailsrs as $userdetails) {
                            $firstsname = $userdetails->name;
                            $data[] = $firstsname;
                        }
                        echo json_encode($data);
                    }
                }
            break;
        }
    }
}

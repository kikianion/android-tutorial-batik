<?php

require_once("Base_model.php");
require_once("Sms_log_model.php");
require_once("Gtalk_log_model.php");
require_once("Stock_detect_set_model.php");

class Balance_detect_model extends \aneon\fw\model\Base_model
{

    public $colMap = array(
        "-",
        "name",
        "phone",
        "globalId",
        "-",
        "-",
    );

    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }

    function load()
    {
        @$days = $_REQUEST['days'];
        if ($days == 0) {
            $days = 2;
        }

        $data1Obj = new Sms_log_model();
        $data1 = $data1Obj->allObj();
        $data2Obj = new \Gtalk_log_model();
        $data2 = $data2Obj->allObj();
        $data3Obj = new \Stock_detect_set_model();
        $data3 = $data3Obj->allObj();

        $res['data1'] = $data1;
        $res['data2'] = $data2;
        $res['data3'] = $data3;
        $res['daysCheck'] = $days;
        $res['result'] = 'ok';
        return $res;
    }
}

?>
<?php

require_once("Base_model.php");
require_once("Sms_log_model.php");
require_once("Gtalk_log_model.php");
require_once("Stock_detect_set_model.php");
require_once("Dayjournal_detect_set_model.php");

class Dayjournal_model extends \aneon\fw\model\Base_model
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

    function all()
    {
        @$daysBefore = $_REQUEST['daysBefore'];
        if ($daysBefore == 0) {
            $daysBefore = 2;
        }


        $date1 = date("Y-m-d H:i:s", strtotime("-" . $daysBefore . " day"));

        $smsObj = new Sms_log_model();
        $dataSms = $smsObj->allObj("where created>='" . $date1 . "'");
        $gtalkObj = new Gtalk_log_model();
        $dataGtalk = $gtalkObj->allObj("where created>='" . $date1 . "'");

        $journalFindObj = new Dayjournal_detect_set_model();
        $journalFind = $journalFindObj->allObj();

        $dataRes = array();

        foreach ($dataSms as $sms) {
            $allStr = $sms->from_ . " " . $sms->cnt;

            foreach ($journalFind as $critStr1Obj) {
                $critStr1 = $critStr1Obj->keyword;
                $orStr1 = explode("|", $critStr1);

                foreach ($orStr1 as $andStr1) {
                    $findAndStr = explode(",", $andStr1);

                    $cek1 = "";
                    foreach ($findAndStr as $find1) {
                        $find1 = str_replace("{coma}", ",", $find1);
                        $s1 = strtolower($find1);
                        $s2 = strtolower($allStr);

                        if ($s1 == "") {
                            continue;
                        }
                        if ($s2 == "") {
                            $s2 = "dsfdsfjhdsfhowey98ey80f9ewf";
                        }
                        $pos1 = strpos($s2, $s1);

                        if ($pos1 === false) {
                            $cek1 = $cek1 . "0";
                        } else {
                            $cek1 = $cek1 . "1";
                        }
                    }

                    if (strpos($cek1, "0") > -1) {
                        $sss = 1;
                    } else {
                        $dataRes[] = array(
                            'from' => $sms->from_,
                            'trxhappen' => $sms->cnt,
                            'created' => $sms->created,
                            'deviceId' => $sms->deviceId,
                            'prodName' => $critStr1Obj->name,
                            'startFindStock' => $critStr1Obj->startFind,
                            'endFindStock' => $critStr1Obj->endFind,
                            'buy' => $critStr1Obj->subtractor,
                        );
                        continue;
                    }
                }
            }
        }

        foreach ($dataGtalk as $p) {
            $allStr = $p->target . ' ' . $p->cnt;

            foreach ($journalFind as $critStr1Obj) {
                $critStr1 = $critStr1Obj->keyword;
                $orStr1 = explode("|", $critStr1);

                foreach ($orStr1 as $andStr1) {
                    $findAndStr = explode(",", $andStr1);

                    $cek1 = "";
                    foreach ($findAndStr as $find1) {
                        $find1 = str_replace("{coma}", ",", strtolower($find1));

                        $s1 = strtolower($find1);
                        $s2 = strtolower($allStr);

                        if ($s1 == "") {
                            continue;
                        }
                        if ($s2 == "") {
                            $s2 = "dsfdsfjhdsfhowey98ey80f9ewf";
                        }

                        $pos1 = strpos($s2, $s1);
                        if ($pos1 === false) {
                            $cek1 = $cek1 . "0";
                        } else {
                            $cek1 = $cek1 . "1";
                        }
                    }
                    if (strpos($cek1, "0") > -1) {
                        $sss = 1;
                    } else {
                        $pos2 = strpos($allStr, "TRx ");
                        $pos3 = strpos($allStr, ".");
                        $ln1 = $pos3 - $pos2;
                        $prodName = substr($allStr, $pos2 + 4, $pos3 - ($pos2 + 4));
                        $dataRes[] = array(
                            'from' => $p->target,
                            'trxhappen' => $p->cnt,
                            'created' => ($p->created),
                            'deviceId' => $p->deviceId,
                            'prodName' => $critStr1Obj->name,
                            'startFindStock' => $critStr1Obj->startFind,
                            'endFindStock' => $critStr1Obj->endFind,
                            'buy' => $critStr1Obj->subtractor,
                        );
                        continue;
                    }

                }
            }
        }
        $res['data'] = $dataRes;
        $res['daysBefore'] = $daysBefore;
        $res['result'] = 'ok';
        return $res;
    }
}

?>
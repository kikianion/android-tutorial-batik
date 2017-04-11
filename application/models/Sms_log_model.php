<?php

require_once("Base_model.php");

class Sms_log_model extends \aneon\fw\model\Base_model
{

    public $colMap = array(
        "-",
        "-",
        "from",
        "cnt",
        "-",
        "-",
    );

    public $import_colChange = array(
        "localCreated" => "clientCreated",
        "localModified" => "clientModified",
    );

    /**
     * @TableColumn(attribute=varchar(1000))
     */
    public $cnt;

    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $from_;

    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $deviceId;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }

//    function importJsonBackup()
//    {
//        $fileContent = file_get_contents($_FILES["dataImport"]["tmp_name"]);
//        $dataJson = json_decode($fileContent);
//        foreach ($dataJson as $obj) {
//            $globalId = $obj->globalId;
//            if ($globalId == null || $globalId == "") {
//                $globalId = "dfdsjhf9dsfy89dsfdjslfn";
//            }
//
//            $className=get_class($this);
//            $objSave=new $className();
//            $sms1 = new Sms_log_model();
//            $sms1 = $sms1->getObjByWhereClause("globalId='" . $globalId . "'");
//            if ($sms1 == null) {//record tidak ketemu
//                $sms1 = new Sms_log_model();
//            }
//
//            $cnt = $obj->cnt;
//            $from_ = $obj->from_;
//            $deviceId = $obj->deviceId;
//
//            $sms1->globalId = $globalId;
//            $sms1->cnt = $cnt;
//            $sms1->from_ = $from_;
//            $sms1->deviceId = $deviceId;
//            $sms1->created = $obj->created;
////            $sms1->clientCreated=$obj->clientCreated;
//            $sms1->clientCreated = $obj->localCreated;
//            $sms1->modified = $obj->modified;
////            $sms1->clientModified=$obj->clientModified;
//            $sms1->clientModified = $obj->localModified;
//
//            $sms1->saveObj();
//        }
//        $res["result"] = "ok";
//        return $res;
//    }

}

?>
<?php
require_once("Base_model.php");

class Devicetrx_log_model extends \aneon\fw\model\Base_model
{

    public $colMap = array(
        "-",
        "-",
        "from",
        "cnt",
        "globalId",
        "-",
    );

    public $import_colChange = array(
        "guid"=>"globalId",
        "localCreated"=>"clientCreated",
        "localModified"=>"clientModified",
    );

    /**
     * @TableColumn(attribute=varchar(1000))
     */
    public $guid;
    /**
     * @TableColumn(attribute=varchar(1000))
     */
    public $prodName;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $nominal;
    /**
     * @TableColumn(attribute=varchar(500))
     */
    public $prefix;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $ussdTrx;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $regexAnswer;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $simSlot;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $deviceId;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $activeUser;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $answer;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $target;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $synced;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $trxState;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $aliasTag1;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $aliasTag2;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }
}

?>
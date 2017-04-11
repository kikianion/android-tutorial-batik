<?php
require_once("Base_model.php");

class Smartreg_log_model extends \aneon\fw\model\Base_model
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
        "guid" => "globalId",
        "localCreated" => "clientCreated",
        "localModified" => "clientModified",
    );

    /**
     * @TableColumn(attribute=varchar(1000))
     */
    public $outlet_id;
    /**
     * @TableColumn(attribute=varchar(1000))
     */
    public $nomor_lain;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $name;
    /**
     * @TableColumn(attribute=varchar(500))
     */
    public $dob;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $email;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $pob;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $alamat;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $noktp;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $mdn;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $code_;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $deviceId;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $state;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }
}

?>
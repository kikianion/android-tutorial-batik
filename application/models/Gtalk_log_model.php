<?php
require_once("Base_model.php");

class Gtalk_log_model extends \aneon\fw\model\Base_model
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
        "localCreated"=>"clientCreated",
        "localModified"=>"clientModified",
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

    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $direction;

    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $target;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }
}

?>
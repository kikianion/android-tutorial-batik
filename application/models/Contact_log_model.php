<?php
require_once("Base_model.php");

class Contact_log_model extends \aneon\fw\model\Base_model
{

    public $colMap = array(
        "-",
        "name",
        "phone",
        "globalId",
        "-",
        "-",
    );

    /**
     * @TableColumn(attribute=varchar(1000))
     */
    public $name;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $phone;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $deviceId;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }
}

?>
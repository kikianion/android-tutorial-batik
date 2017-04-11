<?php
require_once("Base_model.php");

class Stock_detect_set_model extends \aneon\fw\model\Base_model
{

    public $colMap = array(
        "-",
        "name",
        "keyword",
        "startFind",
        "endFind",
        "-",
    );

    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $name;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $keyword;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $startFind;
    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $endFind;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }
}

?>
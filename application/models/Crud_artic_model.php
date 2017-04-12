<?php
require_once("Base_model.php");

class Crud_artic_model extends \aneon\fw\model\Base_model
{

    public $colMap = array(
        "-",
        "title",
        "cnt",
        "order_",
        "cat",
        "-",
    );

    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $title;
    /**
     * @TableColumn(attribute=varchar(5000))
     */
    public $cnt;
    /**
     * @TableColumn(attribute=int)
     */
    public $order_;
    /**
     * @TableColumn(attribute=int)
     */
    public $cat;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }
}

?>
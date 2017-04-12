<?php
require_once("Base_model.php");

class Crud_cat_model extends \aneon\fw\model\Base_model
{

    public $colMap = array(
        "-",
        "name",
        "order_",
        "-",
        "-",
    );

    /**
     * @TableColumn(attribute=varchar(255))
     */
    public $name;
    /**
     * @TableColumn(attribute=int)
     */
    public $order_;


    function buildIndex()
    {
        // TODO: Implement buildIndex() method.
    }
}

?>
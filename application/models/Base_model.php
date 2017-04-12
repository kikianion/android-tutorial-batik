<?php
namespace aneon\fw\model;

use ReflectionClass;

abstract class Base_model extends \CI_Model
{

    public $import_colChange = array();

    /**
     * @TableColumn(attribute=int NOT NULL AUTO_INCREMENT primary key)
     */
    public $id;

    /**
     * @TableColumn(attribute=varchar(500))
     */
    public $globalId;

    /**
     * @TableColumn(attribute=timestamp default '0000-00-00 00:00:00' )
     */
    public $modified;

    /**
     * @TableColumn(attribute=timestamp default current_timestamp)
     */
    public $created;


    /**
     * @TableColumn(attribute=timestamp)
     */
    public $clientCreated;

    /**
     * @TableColumn(attribute=timestamp)
     */
    public $clientModified;

    function Base_model()
    {
        parent::CI_Model();
    }

    abstract function buildIndex();

    function dropTable()
    {
        $this->load->database();
        $this->load->dbforge();
        $this->dbforge->drop_table(get_class($this), TRUE);
    }

    function createTable()
    {
        $class = new \ReflectionClass(get_class($this));
        $properties = $this->_getClassModelProps();

        $this->load->database();
        $this->load->dbforge();

        $this->dropTable();

        $fields = array();
        for ($i = 0; $i < count($properties); $i++) {
            $prop = $properties[$i];
            $this->dbforge->add_field($prop->name . " " . $prop->colAttribute);
        }
        $this->dbforge->create_table(get_class($this));

        return $properties;
    }

    function all()
    {
        $tableName = get_class($this);
        $this->load->database();
        if (!$this->db->table_exists(strtolower(get_class($this)))) {
            $this->createTable();
        }
        $query = $this->db->query("select * from " . get_class($this));

        $res['result'] = 'ok';
        $res['data'] = $query->result();
        return $res;
    }

    function allObj($whereClause = "")
    {
        $tableName = get_class($this);
        $this->load->database();
        if (!$this->db->table_exists(get_class($this))) {
            $this->createTable();
        }
        $query = $this->db->query("select * from " . get_class($this) . " " . $whereClause);
        return $query->result();
    }

    function getObjById($id)
    {
        $res = $this->getObjByWhereClause("id=" . $id);
        return $res;
    }

    function getObjByWhereClause($whereClause)
    {
        $res = null;
        $tableName = get_class($this);
        $this->load->database();
        if (!$this->db->table_exists(get_class($this))) {
            $this->createTable();
        }
        $query = $this->db->query("select * from " . get_class($this) . " where " . $whereClause);
        $rs = $query->result();
        if (count($rs) > 0) {
            $className = get_class($this);
            $res = new $className();

            $properties = $this->_getClassModelProps();
            foreach ($properties as $prop) {
                $fieldName = $prop->name;
                $res->$fieldName = $rs[0]->$fieldName;
            }
        }
        return $res;
    }

    function _getClassModelProps()
    {
        $class = new ReflectionClass(get_class($this));
        $properties = array_filter($class->getProperties(), function ($prop) use ($class) {
            $doc = $prop->getDocComment();
            preg_match_all('#@(.*?)\n#s', $doc, $annotations);
            if (count($annotations[1]) > 0 && strpos($annotations[1][0], "TableColumn(") > -1) {
                $search1 = "(attribute=";
                $idx1 = strpos($annotations[1][0], $search1);
                $idx2 = strrpos($annotations[1][0], ")");
                $attr = substr($annotations[1][0], $idx1 + strlen($search1), $idx2 - ($idx1 + strlen($search1)));
                $prop->colAttribute = $attr;
                return true;
            }
        });

        $res = array();
        foreach ($properties as $prop) {
            $res[] = $prop;
        }
        return $res;
    }

    function delete()
    {
        $res["result"] = "error";

        @$ids = $_REQUEST["ids"];

        try {
//            if(function_exists($callback_delete1)){
//                $callback_delete1();
//                $callback_delete1="";
//            }

            if (count($ids) > 0) {
                $tableName = get_class($this);
                $this->load->database();
                for ($i = 0; $i < count($ids); $i++) {
                    $query = $this->db->query("delete from $tableName WHERE id=? ", array($ids[$i]));

//                    $stmt= $pdoc->prepare("delete from $table WHERE id =:id ");
//                    $stmt->execute(array(
//                        'id'=>$ids[$i]
//
//                    ));

                }
            }
            $res["result"] = "ok";
        } catch (Exception $e) {
            $res["msg"] = $e->getMessage();
        }
        return $res;
    }

    function save()
    {
        $res['result'] = 'error';

        $tableName = get_class($this);
        $this->load->database();

        if (!$this->db->table_exists( strtolower(get_class($this)))) {
            $this->createTable();
        }

        if (isset($_POST['changes'])) {
            $change_rows = json_decode($_POST['changes'], true);
            foreach ($change_rows as $change_row) {

//                if (function_exists($callback_perchange_beforesave1)) {
//                    $res_cbbefore = $callback_perchange_beforesave1();
//                    $callback_perchange_beforesave1 = "";
//                }

                $rowId = $change_row['id'];
                if ($rowId == "") {
                    $rowId = " - 1";
                }

                $colNames = "";
                $newVals = "";

                $newVals_pdo = "";

                $prepareParamarray = array();
                $updateColVal_pdo = '';

                $updateColVal = '';

                for ($j = 0; $j < count($change_row['rowData']); $j++) {
                    $row1 = $change_row['rowData'][$j];
                    $colId = $row1[1];

                    if ($j == count($change_row['rowData']) - 1) {
                        $colNames .= $this->colMap[$colId];
                        $newVals .= "'" . $row1[3] . "'";
                        $updateColVal .= $this->colMap[$colId] . " = " . "'" . $row1[3] . "'";

                        $updateColVal_pdo .= $this->colMap[$colId] . " =?";
                        $prepareParamarray[] = $row1[3];

                        $newVals_pdo .= " ? ";
                    } else {
                        $colNames .= $this->colMap[$colId] . ",";
                        $newVals .= "'" . $row1[3] . "',";
                        $updateColVal .= $this->colMap[$colId] . " = " . "'" . $row1[3] . "',";

                        $updateColVal_pdo .= $this->colMap[$colId] . " =?, ";
                        $prepareParamarray[] = $row1[3];

                        $newVals_pdo .= " ?,";
                    }
                }

                $query = $this->db->query("select id from " . get_class($this) . " where id =? limit 1", array($rowId));
//                $stmt = $pdoc->prepare("SELECT id FROM $table WHERE id =:id LIMIT 1");
//                $stmt->execute(array(
//                    'id' => $rowId
//                ));

                $lastid = null;


                //record sudah ada
//                if ($row = $stmt->fetch()) {
                if (count($query->result()) > 0) {
                    $s = "UPDATE $tableName SET $updateColVal_pdo WHERE id = ? ";

                    $param_arr1 = array_merge($prepareParamarray, array($rowId));
//                    $query = $pdoc->prepare($s);
//                    $query->execute($param_arr1);
                    $query = $this->db->query($s, $param_arr1);

                    $lastid = $rowId;

                    $save_info['type'] = 'update';
                } //record belum ada
                else {
                    $s = "INSERT INTO $tableName ($colNames) VALUES($newVals_pdo)";
//                    $query = $pdoc->prepare($s);
                    $query = $this->db->query($s, $prepareParamarray);
//                    $query->execute($prepareParamarray);
//                    $lastid = $pdoc->lastInsertId();
                    $lastid = $this->db->insert_id();
                    $save_info['type'] = 'insert';
                }


//                if (function_exists($callback_perchange_save1)) {
//                    $res_cb = $callback_perchange_save1($lastid, $save_info);
//                }

            }
            $callback_perchange_save1 = "";

//            if (function_exists($callback_save1)) {
//                $callback_save1();
//                $callback_save1 = "";
//            }

            $res["result"] = "ok";
//            $res["msg"] = "affected: " . $query->rowCount();
        }

        return $res;
    }


    function saveObj()
    {
        $tableName = get_class($this);
        $this->load->database();

        if (!$this->db->table_exists(get_class($this))) {
            $this->createTable();
        }

        $properties = $this->_getClassModelProps();
        $sColsInsert = "";
        $sColsUpdate = "";
        $sValsInsert = "";
        $params = array();
        for ($i = 0; $i < count($properties); $i++) {
            $prop = $properties[$i];
            $fieldName = $prop->name;

            $newVal = $this->$fieldName;
            if ($newVal == null) {
                $params[] = '';
            } else {
                $params[] = $newVal;
            }

            if ($i == count($properties) - 1) {
                $sColsInsert .= $fieldName;
                $sValsInsert .= "?";
                $sColsUpdate .= $fieldName . "=?";
            } else {
                $sColsInsert .= $fieldName . ",";
                $sValsInsert .= "?,";
                $sColsUpdate .= $fieldName . "=?,";
            }
        }

        $sql = "";
        $obj = $this->getObjByWhereClause("globalId='" . $this->globalId . "'");
        if ($obj == null) {
            $sql = "insert into $tableName(" . $sColsInsert . ") values(" . $sValsInsert . ")";

        } else {
            $sql = "update $tableName set $sColsUpdate where globalId=?";
            $params[] = $this->globalId;
        }
        $query = $this->db->query($sql, $params);
//        $res['result'] = "ok";
//        $res['insertId'] = $this->db->insert_id();

        return true;;
    }

    function importJsonBackup()
    {
        $fileContent = file_get_contents($_FILES["dataImport"]["tmp_name"]);
        if (isset($this->import_colChange)) {
            foreach ($this->import_colChange as $colOld => $colNew) {
                $fileContent = str_replace('"' . $colOld . '":', '"' . $colNew . '":', $fileContent);
            }

        }
        $dataJson = json_decode($fileContent);

        foreach ($dataJson as $objFromJson) {
            $globalId = $objFromJson->globalId;
            if ($globalId == null || $globalId == "") {
                $globalId = "dfdsjhf9dsfy89dsfdjslfn";
            }

            $className = get_class($this);
            $objSave = new $className();
            $objSave = $objSave->getObjByWhereClause("globalId='" . $globalId . "'");
            if ($objSave == null) {//record tidak ketemu
                $objSave = new $className();
                $objSave->id = null;
            }

            $properties = $this->_getClassModelProps();
            foreach ($properties as $prop) {
                $fieldName = $prop->name;
                if (isset($objFromJson->$fieldName)) {
                    $objSave->$fieldName = $objFromJson->$fieldName;
                }
            }

            $objSave->saveObj();
        }
        $res["result"] = "ok";
        return $res;
    }
//    function getClassMethodAnnotations($class)
//    {
//        $r = new ReflectionClass($class);
//        $doc = $r->getMethod()->getDocComment();
//        preg_match_all('#@(.*?)\n#s', $doc, $annotations);
//        return $annotations[1];
//    }
}

?>
<?php

abstract class Ladoga2_Set_Abstract {

    protected $_rowSet;
    protected $_rowClass;

    public function __construct($params = array()) {
        $this->_rowClass = preg_replace("/_Set_/", '_Model_', get_class($this));
        if (isset($params['data'])) {
            $this->_rowSet = $this->_parse($params['data']);
        }
    }
    
    public function get(){
        return $this->_rowSet;
    }

    protected function _parse($rows) {
        $obj = new $this->_rowClass();
        $parents = $obj->getParents();
        $row = $rows[0];
        $cols = array();

        foreach ($parents as $parent => $o) {
            foreach ($row as $col => $v) {
                if (preg_match("/^($parent)([_])(.*)$/", $col, $matches)) {
                    $cols[$parent][$col] = $matches[3];
                }
            }
        }

        $result = array();
        foreach ($rows as $key => $row) {
            $row['autoload'] = false;
            $obj = new $this->_rowClass();
            
            foreach ($parents as $parent => $map) {
                $data = array('autoload' => false);
                $isempty = true;
                $parentObj = null;
                
                foreach ($cols[$parent] as $key => $value) {
                    $data[$value] = $row[$key];
                    $isempty = (is_null($row[$key]) && $isempty) ? true : false;
                    unset ($row[$key]);
                }
                if (!$isempty) {
                    $parentObj = new $map['parentClass']($data);
                    $obj->setParent($parent, $parentObj);
                } else {
                    $obj->setParent($parent, null);
                }
            }
            $obj->setOptions($row);
            $result[] = $obj;
        }
        return $result;
    }

}

<?php

class Ladoga2_Mapper_AbstractParent extends Zend_Db_Table_Abstract {

    public function __construct($config = array()) {
        parent::__construct($config);
        $className = preg_replace("/_Mapper_/", '_Model_', get_class($this));
        $this->setRowClass($className);
    }

    public function save($obj) {
        $result = null;
        if (is_string($obj)) {
            $obj = new $obj();
        }
        if (!$obj instanceof Ladoga2_Model_AbstractId) {
            throw new Zend_Exception("Mapper can't save, \$obj must be instance of Ladoga2_Model_Abstract");
        }
        if (!$obj->idIsSet()) {
            //finding autoincrement key and setting it to object after inserting
            $incKey = array_search(null, $obj->getKeys());
            $result = $this->insert($obj->getObjectParams());
            $obj->setIncKey($incKey, $result);
        } else {
            $result = $this->update($obj->getObjectParams(), $this->getWhereArray($obj->getKeys()));
        }
        return $result;
    }

    public function populate(Ladoga2_Model_AbstractRelation $obj, $where = null, $order = null, $parents = null) {

        $keys = $obj->getKeys();
        
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('obj' => $this->_name))
                ->limit(1);
        
        $this->_where($select, $this->getWhereArray($keys));
        $this->_joinParent($select, $obj);

        $rows = $this->_fetch($select);
        if (count($rows) != 1)
            throw new Zend_Exception("Can't populate Obj with this keys. Object of class $obj->objectName with this keys doesn't exists");
        
        $row = $rows[0];
        unset ($rows);
        $row['autoload'] = false;
        
        /*
         * Setting RowColNames with ParentParamNames
         * 
         * result is
         * 
         * $cols = > array(
         *                  'ParentName' => array (
         *                                          'rowColName' => 'parantParamName',
         *                                          ..
         *                                          ),
         *                  ..
         *          )         
         */
        $parents = $obj->getParents();
        $cols = array();

        foreach ($parents as $parent => $map) {
            foreach ($row as $col => $v) {
                /*
                 * 
                 * @todo you can boost here changing preg_match
                */
                if (preg_match("/^($parent)([_])(.*)$/", $col, $matches)) {
                    $cols[$parent][$col] = $matches[3];
                }
            }
        }
        
        /*
         * Creating Parant Objects with ObjectName_ObjectParam's from result Row
         * 
         * and setting then to $obj
         * 
         */
        foreach ($parents as $parent => $map) {
            $data = array();
            $isempty = true;
            $parentObj = null;
            foreach ($cols[$parent] as $rowColName => $objParamName) {
                $data[$objParamName] = $row[$rowColName];
                $isempty = (is_null($row[$rowColName]) && $isempty) ? true : false;
                unset ($row[$rowColName]);
            }
            if (!$isempty) {
                $data['autoload'] = false;
                $parentObj = new $map['parentClass']($data);
                $obj->setParent($parent, $parentObj);
            } else {
                $obj->setParent($parent, null);
            }
        }
        
        /*
         * Unsetting Obj keys from remains of Row
         */
        foreach ($keys as $key => $v) {
            unset ($row[$key]);
        }
        /*
         * Setting Obj params.
         * 
         * Object params, wich correspondes to Parents already setuped (Parent keys in Obj param list) 
         */
        $obj->setOptions($row);
    }

    /**
     * Fetches all elements like $obj
     *
     * @param $obj
     * @param array $where key => value
     * @param bool $deleted fetch deleted values (default false)
     * @return array rowSet
     */
    public function fAll($obj, $where = null, $order = null, $count = null, $offset = null, $parents = null, $deleted = false, $group = null) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('obj' => $this->_name));
        
        $this->_joinParent($select, $obj);

        if(!$deleted){
            $this->_where($select, 'obj.deleted is null');
        }
        
        if(isset ($where)){
            $this->_where($select, $where);
        }
        
        if(isset ($order)){
            $select->order($order);
        }
        
        if(isset ($group)){
            $select->group($group);
        }
        $rows = $this->_fetch($select);

        $rowSetClassName = preg_replace("/_Mapper_/", '_Set_', get_class($this));
        
        $data  = array(
            'data'     => $rows,
        );
                
        $rowSet = new $rowSetClassName($data);
    
        return $rowSet->get();
    }

    public function _joinParent($select, $obj, $objAliasName = null) {

        $objAliasName = (is_null($objAliasName))?'obj':$objAliasName;
        
        $parents = $obj->getParents();

        foreach ($parents as $parent => $map) {

            $model = new $map['parentClass']();
            $mapper = $model->getMapper();

            $ref = $this->getReference(get_class($mapper), $parent);

            $info = $mapper->info();
            $cols = $info['cols'];
            foreach ($cols as $id => $col) {
                $cols[$parent . "_" . $col] = $col;
                unset($cols[$id]);
            }

            $objColumns = (is_array($ref['columns'])) ? $ref['columns'] : array($ref['columns']);
            $refColumns = (is_array($ref['refColumns'])) ? $ref['refColumns'] : array($ref['refColumns']);

            if (count($objColumns) != count($refColumns)) {
                throw new Zend_Exception("Can't get parent $parent obj for class " . $this->getMapperName() . ". Obj parent columns and refColumns count mismatch.");
            }
            $cond = array();
            foreach ($objColumns as $key => $column) {
                $c = $this->getAdapter()->quoteIdentifier($objAliasName . '.' . $column);
                $r = $this->getAdapter()->quoteIdentifier($parent . '.' . $refColumns[$key]);
                $cond[] = "$c = $r";
            }
            $cond = implode(' AND ', $cond);

            $select->joinLeft(array($parent => $mapper->getTableName()), $cond, $cols);
        }
    }

    public function getTableName() {
        return $this->_name;
    }

    public function getMapperName() {
        return preg_replace("/^.*_Mapper_/", '', get_class($this));
    }

}

<?php

abstract class Ladoga2_Model_AbstractMany2Many extends Ladoga2_Model_AbstractId {

    protected $many2many;

    public function __construct(array $params = array()) {
        foreach ($this->many2many as $manyName => $value) {
//            $this->many2many[$manyName]['refClass'] = null;
            $this->many2many[$manyName]['list'] = null;
            $this->many2many[$manyName]['upd'] = null;
            $this->many2many[$manyName]['set'] = null;
            $this->many2many[$manyName]['list_fresh'] = null;
            $this->many2many[$manyName]['set_fresh'] = null;
        }
        parent::__construct($params);
    }

    public function save() {
        $result = parent::save();

        $keys = $this->getKeys();

        foreach ($this->many2many as $rel => $relation) {

            if (!empty($relation['upd'])) {
                if (!$relation['set_fresh']) {
                    $this->populateMany($rel);
                }
                $toSet = array();
                $toUnset = array();
                foreach ($relation['upd'] as $key => $value) {
                    if ($value->isset) {
                        $toSet[$key] = $value;
                    } else {
                        $toUnset[$key] = $value;
                    }
                }
                
                $toSet = array_diff_key($toSet, $this->many2many[$rel]['set']);
                $toUnset = array_intersect_key($toUnset, $this->many2many[$rel]['set']);
                $upd = array_merge($toSet, $toUnset);
                
                foreach ($upd as $new) {
                    foreach ($keys as $key => $value) {
                        if (is_null($new->$key)) {
                            $new->$key = $value;
                        }
                    }
                    $new->save();
                }
                $this->many2many[$rel]['set_fresh'] = false;
            }
        }
        return $result;
    }

    public function setOptions(array $options = array()) {
        parent::setOptions(&$options);
        foreach ($options as $option => $value) {
            if (preg_match('/^(many)([A-Z][a-z]*)(_)(.*)$/', $option, $matches)) {

                $method = 'set' . ucfirst($matches[1] . $matches[2]);

                $className = preg_replace('/' . $this->objectName . '$/', $matches[2], get_class($this));
                $model = new $className();

                $keys = $model->getKeys();
                ksort($keys);
                $keyValues = explode('_', $matches[4]);

                if (count($keyValues) == count($keys)) {
                    $i = 0;
                    foreach ($keys as $k => $v) {
                        $keys[$k] = (int) $keyValues[$i++];
                    }
                } else {
                    throw new Zend_Exception("Can't set Many " . $matches[2] . " for class $this->ObjectName. Number of keys in relation class and number of key values provided mismatch.");
                }
                $this->$method($keys, $value);
            }
        }
    }
    
    public function setMany($manyName, $relations = array(), $interMapperName = null) {
        $mapper = $this->mapper;
        $objName = $this->getObjectName();
        $matchMapperName = preg_replace("/_$objName/", "_$manyName", get_class($mapper));
        $matchMapper = new $matchMapperName();
        $matchDepend = $matchMapper->getDependentTables();

        if(!isset($interMapperName)){
            $thisDepend = $mapper->getDependentTables();
            $depend = array_intersect($matchDepend, $thisDepend);
            $interMapperName = $depend[0];
        }      
        
        $className = preg_replace("/_Mapper_/", '_Model_', $interMapperName);
        
        foreach ($relations as $relation) {
            $params = array_merge($relation['keys'],$this->primary);
            
            $model = new $className($params);
            $model->isset = $relation['isset'];

            /*
             * do you realy think it's needed?
             * think about it
             */
            $keys = $model->getKeys();
            $keys = array_diff_key($keys, $this->getKeys());
            ksort($keys);
            $keys = array_filter($keys);
            $keys = implode('_', $keys);

            $this->many2many[$manyName]['upd'][$keys] = $model;
//            $this->many2many[$manyName]['upd'][] = $model;
            /*
             * 
             */
            $this->many2many[$manyName]['set_fresh'] = false;
        }
    }

    /*
     * list of valid rel's
     */

//    protected function relPopulateList($rel) {
//        $mapper = $this->mapper;
//        $objName = $this->getObjectName();
//
//        $matchMapperName = preg_replace("/_$objName/", "_$rel", get_class($mapper));
//        $matchMapper = new $matchMapperName();
//
//        $result = $matchMapper->fetchAll();
//
//        $list = array();
//        foreach ($result as $k => $r) {
//            $keys = $r->getKeys();
//            ksort($keys);
//            $keys = array_filter($keys);
//            $keys = implode('_', $keys);
//            $list[$keys] = $r;
//        }
//
//        $this->many2many[$rel]['list'] = $list;
//        $this->many2many[$rel]['list_fresh'] = true;
//    }



    protected function populateMany($manyName, $timeLimit = null, $where = null, $order = null, $count = null, $offset = null) {
        if (!$this->idIsSet()) {
            throw new Zend_Exception("You should provide keys for object $this->objectName, before getting relations.");
        }
        $parants = $this->mapper->fetchMany($this, $manyName, $timeLimit, $where, $order, $count, $offset);
        
        $this->many2many[$manyName]['set'] = $parants;
        $this->many2many[$manyName]['set_fresh'] = true;
    }

    public function getRelList($rel) {
        if (isset($this->many2many[$rel])) {
            if (!$this->many2many[$rel]['list_fresh']) {
                $this->populateMany($rel);
            }
            return $this->many2many[$rel]['list'];
        } else {
            throw new Zend_Exception("There is no \"$rel\" relation in $this->objectName");
        }

//        $this->getMany($rel);
    }

    public function getMany($manyName, $timeLimit = null, $where = null, $order = null, $count = null, $offset = null) {
        if (isset($this->many2many[$manyName])) {
            if (!$this->many2many[$manyName]['set_fresh']) {
                $this->populateMany($manyName, $timeLimit, $where, $order, $count, $offset);
            }
            return $this->many2many[$manyName]['set'];
        } else {
            throw new Zend_Exception("There is no \"$manyName\" relation in $this->objectName");
        }
    }

    public function getRels() {
        return $this->many2many;
    }
    
    public function getManyParams($manyName) {
        if (isset($this->many2many[$manyName])) {
            $params = array(
                'intersectoinClass' => $this->many2many[$manyName]['intersectoinClass'],
                'matchClass' => $this->many2many[$manyName]['matchClass'],
                'list_fresh' => $this->many2many[$manyName]['list_fresh'],
                'set_fresh' => $this->many2many[$manyName]['set_fresh']
            );
            return $params;
        } else {
            throw new Zend_Exception("There is no \"$manyName\" childs in $this->objectName");
        }
    }

}

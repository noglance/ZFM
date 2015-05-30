<?php

abstract class Ladoga2_Model_Abstract {

    protected $params;
    
    public function __construct(array $options = array()) {
        foreach ($this->params as $key => $value) {
            $this->params[$value] = null;
            unset($this->params[$key]);
        }
        $this->setOptions($options);
    }

    public function __set($property, $value) {
        $method = 'set' . ucfirst($property);
        $this->$method($value);
    }

    public function __get($property) {
        $method = 'get' . ucfirst($property);
        return $this->$method();
    }

    protected function setOptions(array $options = array()) {
        foreach ($options as $key => $value) {  
            if (array_key_exists($key, $this->params)) {
                $this->$key = $value;
                unset($options[$key]);
            }
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    /*
     * 
     */
    
    public function getObjectParams() {
        foreach ($this->params as $paramName => $paramValue) {
            $this->params[$paramName] = $this->$paramName;
        }
        return $this->params;
    }
    
    public function getObjectParamNames() {
        $result = array();
        foreach ($this->params as $paramName => $paramValue) {
            $result[] = $paramName;
        }
        return $result;
    }

    public function getObjectName() {
        return preg_replace("/^.*_Model_/", '', get_class($this));
    }

    public function getMapper() {
        $mapperClassName = preg_replace("/_Model_/", '_Mapper_', get_class($this));
        $mapper = new $mapperClassName();
        if (!$mapper instanceof Ladoga2_Mapper_Abstract) {
            throw new Zend_Exception("Can't get mapper $className for class " . $this->getObjectName());
        }
        return $mapper;
    }
    
    
    
    
    
    
    
    
    
    /*
     * mappers link
     */

    public function save() {
        return $this->getMapper()->save($this);
    }

    public function populate() {
        $this->getMapper()->populate(&$this);
    }

    public function fetchAll($where = null, $order = null, $count = null, $offset = null, $parents = null, $deleted = null, $group = null) {
        $parents = null;
        $deleted = null;
        return $this->getMapper()->fAll($this, $where, $order, $count, $offset, $parents, $deleted, $group);
    }
    
    public function remove(){
        return $this->getMapper()->remove($this);
    }

}

<?php

abstract class Ladoga2_Model_AbstractId extends Ladoga2_Model_Abstract {

    protected $autoload = true;

    public function __construct(array $params = array()) {

        if (!isset($this->primary)) {
            throw new Zend_Exception("Can't create object of class $this->objectName. You should provide \$this->primary = array('primary keys')");
        }
        if (!is_array($this->primary)) {
            $this->primary = array($this->primary);
        }
        foreach ($this->primary as $k => $key) {
            $this->primary[$key] = null;
            unset($this->primary[$k]);
        }
        parent::__construct($params);
    }

    public function __call($methodName, $args) {
        if (!method_exists($this, $methodName)) {
            $methodType = substr($methodName, 0, 3);
            $property = substr($methodName, 3);
            $property = lcfirst($property);

            switch ($methodType) {
                case 'set':
                    if (array_key_exists($property, $this->params)) {
                        return $this->params[$property] = $args[0];
                        return false;
                    }
                    if (array_key_exists($property, $this->primary)) {
                        return $this->setKey($property, $args[0]);
                    }
                    throw new Zend_Exception("Can't set $property. Property $property doesn't exists in class " . $this->getObjectName());
                    break;
                case 'get':
                    if (array_key_exists($property, $this->params)) {
                        return $this->params[$property];
                    }
                    if (array_key_exists($property, $this->primary)) {
                        return $this->primary[$property];
                    }
                    throw new Zend_Exception("Can't get $property. Property $property doesn't exists in class " . $this->getObjectName());
                case 'default':
                    throw new Zend_Exception('Method ' . $methodName . ' not exists');
            }
        } else {
            return call_user_func_array(array($this, $methodName), $args);
        }
    }

    public function setOptions(array $options = array()) {
        if (isset($options['autoload'])) {
            $this->autoload = (!$options['autoload']) ? false : true;
            unset($options['autoload']);
        }

        if (isset($options['id'])) {
            $this->setId($options['id']);
            unset($options['id']);
        }
        $keys = $this->getKeys();
        foreach ($keys as $key => $value) {
            if (isset($options[$key])) {
                $this->$key = $options[$key];
                unset($options[$key]);
            }
        }
        parent::setOptions(&$options);

        $this->autoload = true;
    }

    protected function setKey($param, $value) {
        if (!is_null($this->primary[$param])) {
            throw new Zend_Exception("Can't set $param. Property $param is immutable in class $this->objectName");
        }
        $this->primary[$param] = $value;
        if ($this->autoload) {
            if ($this->idIsSet()) {
                $this->populate();
            }
        }
    }

    protected function setIncKey($param, $value) {
        if (!is_null($this->primary[$param])) {
            throw new Zend_Exception("Can't set $param. Property $param is immutable in class $this->objectName");
        }
        $this->primary[$param] = $value;
    }

    public function getObjectParams() {
        $params = parent::getObjectParams();
        $result = array();
        $keys = $this->getKeys();
        foreach ($keys as $key => $value) {
            $result[$key] = $this->$key;
        }
        return array_merge($result, $params);
    }
    
    public function getObjectParamNames() {
        $paramNames = parent::getObjectParamNames();
        $result = array();
        $keys = $this->getKeys();
        foreach ($keys as $key => $value) {
            $result[] = $key;
        }
        return array_merge($result, $params);
    }

    public function setId($value) {
        if (count($this->primary) == 1) {
            foreach ($this->primary as $key => $v) {
                $this->setKey($key, $value);
            }
        } else {
            throw new Zend_Exception("Can't set id for object of class $this->objectName. This object have more than one primary key.");
        }
    }

    public function getId() {
        if (count($this->primary) == 1) {
            foreach ($this->primary as $key => $value) {
                return $this->primary[$key];
            }
        } else {
            throw new Zend_Exception("Can't set id for object of class $this->objectName. This object have more than one primary key.");
        }
    }

    public function idIsSet() {
        $result = true;
        $keys = $this->getKeys();
        foreach ($keys as $key) {
            $result = (is_null($key) || !$result) ? false : true;
        }
        return $result;
    }

    public function getKeys() {
        return $this->primary;
    }

}

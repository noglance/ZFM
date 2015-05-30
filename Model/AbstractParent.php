<?php

abstract class Ladoga2_Model_AbstractParent extends Ladoga2_Model_AbstractChild {

    protected $parents;

    public function __construct(array $params = array()) {
        foreach ($this->parents as $parentName => $value) {
            $this->parents[$parentName]['fresh'] = false;
            $this->parents[$parentName]['obj'] = null;
        }
        parent::__construct($params);
    }

    public function __call($methodName, $args) {
        if (preg_match("/^(getMany|getParent)([A-Z].*)$/", $methodName, $matches)) {
            switch ($matches[1]) {
                case 'getMany':
                    $matches[2]; //first param
                    $args = array_merge(array($matches[2]),$args);
                    return call_user_func_array(array($this, getMany), $args);
                case 'getParent':
                    return $this->getParent($matches[2]);
            }
        } else {
            return parent::__call($methodName, $args);
        }
    }

    public function setParent($parentName, $parentObj) {
        if (!isset($this->parents[$parentName])) {
            throw new Zend_Exception("Can't set parent object $parentName. This parent relation not defined in class $this->objectName");
        }
        if (!is_null($parentObj)) {
            if (!$parentObj instanceof $this->parents[$parentName]['parentClass']) {
                throw new Zend_Exception("Parent object $parentName of class $this->objectName should be instance of " . $this->parents[$parentName]['parentClass']);
            }
            if (!$parentObj->idIsSet()) {
                throw new Zend_Exception("Parent object $parentName for class $this->objectName doesn't have setuped keys");
            }
            foreach ($this->parents[$parentName]['prop'] as $objParam => $parantParam) {
                $this->$objParam = $parentObj->$parantParam;
            }
        }
        $this->parents[$parentName]['fresh'] = true;
        $this->parents[$parentName]['obj'] = $parentObj;
    }

    public function getParent($parentName) {
        if (!isset($this->parents[$parentName]))
            throw new Zend_Exception("Can't get parent object $parentName. This parent relation not defined in class $this->objectName");

        if (!$this->parents[$parentName]['fresh'])
            throw new Zend_Exception("Can't get parent object $parentName of class $this->objectName. TODO: Write populate method first");

        return $this->parents[$parentName]['obj'];
    }

    public function getParents() {
        return $this->parents;
    }

}
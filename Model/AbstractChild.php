<?php

abstract class Ladoga2_Model_AbstractChild extends Ladoga2_Model_AbstractMany2Many {

    protected $childs;

    public function __construct(array $params = array()) {
        foreach ($this->childs as $childName => $childParams) {
            $this->childs[$childName]['new'] = null;
            $this->childs[$childName]['set'] = null;
            $this->childs[$childName]['set_fresh'] = null;
        }
        parent::__construct($params);
    }

    public function getChild($childName, $where = null, $order = null, $count = null, $offset = null) {
        if (isset($this->childs[$childName])) {
            if (!$this->childs[$childName]['set_fresh']) {
                $this->populateChild($childName, $where, $order, $count, $offset);
            }
            return $this->childs[$childName]['set'];
        } else {
            throw new Zend_Exception("There is no \"$childName\" childs in $this->objectName");
        }
    }
    
    public function getChildParams($childName) {
        if (isset($this->childs[$childName])) {
            $params = array(
                'prop' => $this->childs[$childName]['prop'],
                'childClass' => $this->childs[$childName]['childClass']
            );
            return $params;
        } else {
            throw new Zend_Exception("There is no \"$childName\" childs in $this->objectName");
        }
    }

    public function setChild($childName, array $childObj) {
        throw new Zend_Exception("Can't set child for object of class $this->objectName. @TO DO write set method first");
    }

    private function populateChild($childName, $where = null, $order = null, $count = null, $offset = null) {
        if (!$this->idIsSet()) {
            throw new Zend_Exception("You should provide keys for object $this->objectName, before getting it's childs.");
        }
        
        $childs = $this->mapper->fetchChilds($this, $childName, $where, $order, $count, $offset);
        
        $this->childs[$childName]['set'] = $childs;
        $this->childs[$childName]['set_fresh'] = true;
    }

}
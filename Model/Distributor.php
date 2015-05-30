<?php

class Ladoga2_Model_Distributor extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'iddist';
    protected $params = array(
        'idmatr',
        'name',
        'iduser',
        'date'
    );
    protected $childs = array(
        'Shop' => array(
            'prop' => array('iddist' => 'iddist'),
            'childClass' => 'Ladoga2_Model_Shop'
        )
    );
    protected $many2many = array(
        'Manager' => array(
            'intersectoinClass' => 'Ladoga2_Model_UserDistributor',
            'matchClass' => 'Ladoga2_Model_User'
        )
    );

    public function setManyManager($value, $isset = true) {
        if (!is_array($value)) {
            $value = array('idmanager' => $value);
        }
        $mprop[] = array('keys' => $value, 'isset' => (bool) $isset);
        return $this->setMany('Manager', $mprop, 'Ladoga2_Mapper_UserDistributor');
    }
    
    public function getIduser() {
        if (!isset($this->params['iduser'])) {
            $this->iduser = Zend_Registry::get('rid');
        }
        return $this->params['iduser'];
    }

    public function getDate() {
        if (!isset($this->params['date'])) {
            $this->date = date('Y-m-d H:i:s');
        }
        return $this->params['date'];
    }

}

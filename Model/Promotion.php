<?php

class Ladoga2_Model_Promotion extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'idpromo';
    protected $params = array(
        'iddist',
        'name',
        'start',
        'final',
        'description',
        'comment',
        'creator',
        'created',
        'remover',
        'deleted'
    );
    protected $parents = array(
        'Distributor' => array(
            'prop' => array('iddist' => 'iddist'),
            'parentClass' => 'Ladoga2_Model_Distributor'
        ),
        'Creator' => array(
            'prop' => array('creator' => 'iduser'),
            'parentClass' => 'Ladoga2_Model_User'
        ),
        'Remover' => array(
            'prop' => array('remover' => 'iduser'),
            'parentClass' => 'Ladoga2_Model_User'
        )
    );
    
    protected $many2many = array(
        'Mpropertie' => array(
            'intersectoinClass' => 'Ladoga2_Model_PromotionMpropertie',
            'matchClass' => 'Ladoga2_Model_Mpropertie'
        ),
        'Production' => array(
            'intersectoinClass' => 'Ladoga2_Model_PromotionProduction',
            'matchClass' => 'Ladoga2_Model_Production'
        ),
        'Shop' => array(
            'intersectoinClass' => 'Ladoga2_Model_PromotionShop',
            'matchClass' => 'Ladoga2_Model_Shop'
        )
    );
    
    
    /*
     * 
     * setting params
     */

    public function getCreator() {
        if (!isset($this->params['creator'])) {
            $this->creator = Zend_Registry::get('rid');
        }
        return $this->params['creator'];
    }

    public function getCreated() {
        if (!isset($this->created)) {
            $this->created = date('Y-m-d H:i:s');
        }
        return $this->params['created'];
    }

    public function setStart($time) {
        $time = strtotime($time);
        if (!$time) {
            throw new Zend_Exception("Can't set start. Property start must be date format");
        }
        $this->params['start'] = date('Y-m-d H:i:s', $time);
    }

    public function setFinal($time) {
        $time = strtotime($time);
        if (!$time) {
            throw new Zend_Exception("Can't set final. Property final must be date format");
        }
        $this->params['final'] = date('Y-m-d 23:59:59', $time);
    }

    
    

    public function setManyMpropertie($value, $isset = true) {
        if (!is_array($value)) {
            $value = array('idmprop' => $value);
        }
        $mprop[] = array('keys' => $value, 'isset' => (bool) $isset);
        return $this->setMany('Mpropertie', $mprop);
    }

    public function setManyShop($value, $isset = true) {
        if (!is_array($value)) {
            $value = array('idshop' => $value);
        }
        $mprop[] = array('keys' => $value, 'isset' => (bool) $isset);
        return $this->setMany('Shop', $mprop);
    }

    public function setManyProduction($value, $isset = true) {
        if (!is_array($value)) {
            $value = array('idprod' => $value);
        }
        $mprop[] = array('keys' => $value, 'isset' => $isset);
        return $this->setMany('Production', $mprop);
    }

    public function isStarted(){
        $curEndDate = Ladoga2_Model_Period::getUPeriod();
        $curStartDate = Ladoga2_Model_Period::getPrevPeriod($curEndDate);
        if(($this->start <= $curEndDate && $curStartDate <= $this->final) ||
                ($this->final < $curStartDate)){
            return true;
        }
        return false;
    }
}

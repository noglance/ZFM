<?php

class Ladoga2_Model_Production extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'idprod';
    protected $params = array(
        'idptype',
        'name',
        'iduser',
        'volumes_id'
    );
    protected $parents = array(
        'Ptype' => array(
            'prop' => array('idptype' => 'idptype'),
            'parentClass' => 'Ladoga2_Model_Ptype'
        ),
        'Volume' => array(
            'prop' => array('volumes_id' => 'id'),
            'parentClass' => 'Ladoga2_Model_Volume'
        ),
        'Creator' => array(
            'prop' => array('iduser' => 'iduser'),
            'parentClass' => 'Ladoga2_Model_User'
        )
    );
    protected $many2many = array(
        'Promotion' => array(
            'intersectoinClass' => 'Ladoga2_Model_PromotionProduction',
            'matchClass' => 'Ladoga2_Model_Production'
        )
    );
    
    public function getIduser() {
        if (!isset($this->params['iduser'])) {
            $this->iduser = Zend_Registry::get('rid');
        }
        return $this->params['iduser'];
    }

}

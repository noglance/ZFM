<?php

class Ladoga2_Model_PromotionMpropertie extends Ladoga2_Model_AbstractRelation {

    protected $primary = array('idpromo', 'idmprop', 'created');
    protected $params = array(
        'isset',
        'creator'
    );

    public function getCreator() {
        if (!isset($this->params['creator'])) {
            $this->creator = Zend_Registry::get('rid');
        }
        return $this->params['creator'];
    }

}

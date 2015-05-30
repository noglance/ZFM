<?php

class Ladoga2_Model_PromotionShop extends Ladoga2_Model_AbstractRelation {

    protected $primary = array('idpromo', 'idshop', 'created');
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

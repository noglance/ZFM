<?php

class Ladoga2_Model_OfficerShop extends Ladoga2_Model_AbstractRelation {

    protected $primary = array('idofficer', 'idshop', 'created');
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

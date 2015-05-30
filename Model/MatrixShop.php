<?php

class Ladoga2_Model_MatrixShop extends Ladoga2_Model_AbstractRelation {

    protected $primary = array('idshop', 'idmatr', 'created');
    protected $params = array(
        'creator'
    );

    public function getCreator() {
        if (!isset($this->params['creator'])) {
            $this->creator = Zend_Registry::get('rid');
        }
        return $this->params['creator'];
    }

}

<?php

class Ladoga2_Model_Matrix extends Ladoga2_Model_AbstractRelation {

    protected $primary = array('idshop');
    protected $params = array(
        'name',
        'lft',
        'rgt',
        'created',
        'date',
        'deleted',
        'remover'
    );

}

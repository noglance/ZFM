<?php

class Ladoga2_Model_Directorate extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'iddirectorate';
    protected $params = array(
        'idmatr',
        'name',
        'iduser',
        'date'
    );

    protected $many2many = array(
        'User' => array(
            'intersectoinClass' => 'Ladoga2_Model_UserDirectorate',
            'matchClass' => 'Ladoga2_Model_User'
        )
    );

}

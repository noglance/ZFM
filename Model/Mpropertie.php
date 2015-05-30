<?php

class Ladoga2_Model_Mpropertie extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'idmprop';
    protected $params = array(
        'name',
        'description',
        'type'
    );

}

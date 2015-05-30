<?php

class Ladoga2_Model_Group extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'idgroup';
    protected $params = array(
        'name',
        'created',
        'remover',
        'deleted'
    );

}

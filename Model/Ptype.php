<?php

class Ladoga2_Model_Ptype extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'idptype';
    protected $params = array(
        'name',
        'iduser',
        'zindex'
    );

}

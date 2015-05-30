<?php

class Ladoga2_Mapper_Ptype extends Ladoga2_Mapper_Abstract {

    protected $_name = 'prod_type';
    protected $_primary = 'idptype';
    protected $_dependentTables = array('Ladoga2_Mapper_Production');
    protected $_referenceMap = array(
        'Creator' => array(
            'columns' => array('creator'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => array('iduser')
        )
    );

}

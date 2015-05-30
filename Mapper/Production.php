<?php

class Ladoga2_Mapper_Production extends Ladoga2_Mapper_Abstract {

    protected $_name = 'production';
    protected $_primary = 'idprod';
    protected $_dependentTables = array('Ladoga2_Mapper_PromotionProduction');
    protected $_referenceMap = array(
        'Ptype' => array(
            'columns' => array('idptype'),
            'refTableClass' => 'Ladoga2_Mapper_Ptype',
            'refColumns' => array('idptype')
        ),
        'Creator' => array(
            'columns' => array('iduser'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => array('iduser')
        ),
        'Volume' => array(
            'columns' => array('volumes_id'),
            'refTableClass' => 'Ladoga2_Mapper_Volume',
            'refColumns' => array('id')
        )
    );

}

<?php

class Ladoga2_Mapper_PromotionMpropertie extends Ladoga2_Mapper_Abstract {

    protected $_name = 'promotion_mproperties';
    protected $_primary = array('idpromo','idmprop','created');
    protected $_referenceMap = array(
        'Promotion' => array(
            'columns' => array('idpromo'),
            'refTableClass' => 'Ladoga2_Mapper_Promotion',
            'refColumns' => 'idpromo'
        ),
        'Mpropertie' => array(
            'columns' => array('idmprop'),
            'refTableClass' => 'Ladoga2_Mapper_Mpropertie',
            'refColumns' => array('idmprop')
        )
    );

}

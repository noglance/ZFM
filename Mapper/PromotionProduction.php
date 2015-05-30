<?php

class Ladoga2_Mapper_PromotionProduction extends Ladoga2_Mapper_Abstract {

    protected $_name = 'promotion_production';
    protected $_primary = array('idpromo','idprod');
    protected $_referenceMap = array(
        'Promotion' => array(
            'columns' => array('idpromo'),
            'refTableClass' => 'Ladoga2_Mapper_Promotion',
            'refColumns' => 'idpromo'
        ),
        'Production' => array(
            'columns' => array('idprod'),
            'refTableClass' => 'Ladoga2_Mapper_Production',
            'refColumns' => array('idprod')
        )
    );

}

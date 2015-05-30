<?php

class Ladoga2_Mapper_PromotionShop extends Ladoga2_Mapper_Abstract {

    protected $_name = 'promotion_shop';
    protected $_primary = array('idpromo','idshop');
    protected $_referenceMap = array(
        'Promotion' => array(
            'columns' => array('idpromo'),
            'refTableClass' => 'Ladoga2_Mapper_Promotion',
            'refColumns' => 'idpromo'
        ),
        'Shop' => array(
            'columns' => array('idshop'),
            'refTableClass' => 'Ladoga2_Mapper_Shop',
            'refColumns' => array('idshop')
        )
    );

}

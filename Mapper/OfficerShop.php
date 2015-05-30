<?php

class Ladoga2_Mapper_OfficerShop extends Ladoga2_Mapper_Abstract {

    protected $_name = 'officer_shop';
    protected $_primary = array('idofficer', 'idshop');
    protected $_referenceMap = array(
        'Officer' => array(
            'columns' => array('idofficer'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => 'iduser'
        ),
        'Shop' => array(
            'columns' => array('idshop'),
            'refTableClass' => 'Ladoga2_Mapper_Shop',
            'refColumns' => array('idshop')
        )
    );

}

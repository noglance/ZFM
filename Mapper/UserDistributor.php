<?php

class Ladoga2_Mapper_UserDistributor extends Ladoga2_Mapper_Abstract {

    protected $_name = 'distributer_manager';
    protected $_primary = array('iddist', 'idmanager');
    protected $_referenceMap = array(
        'User' => array(
            'columns' => array('idmanager'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => 'iduser'
        ),
        'Distributor' => array(
            'columns' => array('iddist'),
            'refTableClass' => 'Ladoga2_Mapper_Distributor',
            'refColumns' => array('iddist')
        )
    );

}

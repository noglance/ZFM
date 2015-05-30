<?php

class Ladoga2_Mapper_Distributor extends Ladoga2_Mapper_Abstract {

    protected $_name = "distributer";
    protected $_primary = "iddist";
    protected $_dependentTables = array(
        'Ladoga2_Mapper_Promotion',
        'Ladoga2_Mapper_Shop',
        'Ladoga2_Mapper_UserDistributor'
    );
    protected $_referenceMap = array(
        'creater' => array(
            'columns' => 'iduser',
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => 'iduser'
        )
    );

}

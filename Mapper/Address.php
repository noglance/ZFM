<?php

class Ladoga2_Mapper_Address extends Ladoga2_Mapper_Abstract {

    protected $_name = "adr_address";
    protected $_primary = "idaddress";
    protected $_dependentTables = array('Ladoga2_Mapper_Shop');
//    protected $_referenceMap = array(
//        'creater' => array(
//            'columns' => 'iduser',
//            'refTableClass' => 'Ladoga2_Mapper_User',
//            'refColumns' => 'iduser'
//        )
//    );

}

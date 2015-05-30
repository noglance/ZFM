<?php

class Ladoga2_Mapper_Directorate extends Ladoga2_Mapper_Abstract {

    protected $_name = "directorate";
    protected $_primary = "iddirectorate";
    protected $_dependentTables = array('Ladoga2_Mapper_UserDirectorate');
//    protected $_dependentTables = array(
//        'Ladoga2_Mapper_Promotion',
//        'Ladoga2_Mapper_Shop',
//        'Ladoga2_Mapper_UserDistributor'
//    );
//    protected $_referenceMap = array(
//        'creater' => array(
//            'columns' => 'iduser',
//            'refTableClass' => 'Ladoga2_Mapper_User',
//            'refColumns' => 'iduser'
//        )
//    );

}

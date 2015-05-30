<?php

class Ladoga2_Mapper_Shop extends Ladoga2_Mapper_Abstract {

    protected $_name = 'shop';
    protected $_primary = "idshop";
    protected $_dependentTables = array(
        'Ladoga2_Mapper_PromotionShop',
        'Ladoga2_Mapper_MatrixShop'
    );
    protected $_referenceMap = array(
        'Area' => array(
            'columns' => 'idarea',
            'refTableClass' => 'Ladoga2_Mapper_Area',
            'refColumns' => 'idarea'
        ),
        'City' => array(
            'columns' => 'idcity',
            'refTableClass' => 'Ladoga2_Mapper_City',
            'refColumns' => 'idcity'
        ),
        'Address' => array(
            'columns' => 'idaddress',
            'refTableClass' => 'Ladoga2_Mapper_Address',
            'refColumns' => 'idaddress'
        ),
        'Premise' => array(
            'columns' => 'idpremise',
            'refTableClass' => 'Ladoga2_Mapper_Premise',
            'refColumns' => 'idpremise'
        ),
        'Kind' => array(
            'columns' => 'idkind',
            'refTableClass' => 'Ladoga2_Mapper_Kind',
            'refColumns' => 'idkind'
        )
    );

}

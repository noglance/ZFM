<?php

class Ladoga2_Mapper_MatrixShop extends Ladoga2_Mapper_Abstract {

    protected $_name = 'matrix_shop';
    protected $_primary = array('idshop','idmatr','created');
    protected $_referenceMap = array(
        'Shop' => array(
            'columns' => array('idshop'),
            'refTableClass' => 'Ladoga2_Mapper_Shop',
            'refColumns' => 'idshop'
        ),
        'Matrix' => array(
            'columns' => array('idmatr'),
            'refTableClass' => 'Ladoga2_Mapper_Matrix',
            'refColumns' => array('idmatr')
        )
    );

}

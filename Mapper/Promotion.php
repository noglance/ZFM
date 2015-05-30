<?php

class Ladoga2_Mapper_Promotion extends Ladoga2_Mapper_Abstract {

    protected $_name = 'promotion';
    protected $_primary = 'idpromo';
    protected $_dependentTables = array('Ladoga2_Mapper_PromotionShop', 'Ladoga2_Mapper_PromotionMpropertie','Ladoga2_Mapper_PromotionProduction');
    protected $_referenceMap = array(
        'Distributor' => array(
            'columns' => 'iddist',
            'refTableClass' => 'Ladoga2_Mapper_Distributor',
            'refColumns' => 'iddist'
        ),
        'Creator' => array(
            'columns' => array('creator'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => array('iduser')
        ),
        'Remover' => array(
            'columns' => array('remover'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => array('iduser')
        )
    );

}

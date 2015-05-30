<?php

class Ladoga2_Mapper_Group extends Ladoga2_Mapper_Abstract {

    protected $_name = 'group';
    protected $_primary = "idgroup";
    protected $_dependentTables = array('Ladoga2_Mapper_UserGroup');
    protected $_referenceMap = array(
        'Creator' => array(
            'columns' => array('iduser'),
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

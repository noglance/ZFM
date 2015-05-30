<?php

class Ladoga2_Mapper_UserGroup extends Ladoga2_Mapper_Abstract {

    protected $_name = 'user_group';
    protected $_primary = array('iduser', 'idgroup');
    protected $_referenceMap = array(
        'User' => array(
            'columns' => array('iduser'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => 'iduser'
        ),
        'Group' => array(
            'columns' => array('idgroup'),
            'refTableClass' => 'Ladoga2_Mapper_Group',
            'refColumns' => array('idgroup')
        )
    );

}

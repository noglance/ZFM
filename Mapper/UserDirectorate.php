<?php

class Ladoga2_Mapper_UserDirectorate extends Ladoga2_Mapper_Abstract {

    protected $_name = 'user_directorate';
    protected $_primary = array('iduser', 'iddirectorate');
    protected $_referenceMap = array(
        'User' => array(
            'columns' => array('iduser'),
            'refTableClass' => 'Ladoga2_Mapper_User',
            'refColumns' => 'iduser'
        ),
        'Directorate' => array(
            'columns' => array('iddirectorate'),
            'refTableClass' => 'Ladoga2_Mapper_Directorate',
            'refColumns' => array('iddirectorate')
        )
    );

}

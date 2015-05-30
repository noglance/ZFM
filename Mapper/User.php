<?php

class Ladoga2_Mapper_User extends Ladoga2_Mapper_Abstract {

    protected $_name = 'user';
    protected $_primary = "iduser";
    protected $_dependentTables = array(
        'Ladoga2_Mapper_Promotion',
        'Ladoga2_Mapper_Distributor',
        'Ladoga2_Mapper_UserDistributor',
        'Ladoga2_Mapper_UserDirectorate',
        'Ladoga2_Mapper_UserGroup'
    );

}

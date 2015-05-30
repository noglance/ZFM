<?php

class Ladoga2_Mapper_Mpropertie extends Ladoga2_Mapper_Abstract {

    protected $_name = 'mproperties';
    protected $_primary = 'idmprop';
    protected $_dependentTables = array('Ladoga2_Mapper_PromotionMpropertie');

}

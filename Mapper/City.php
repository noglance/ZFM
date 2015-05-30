<?php

class Ladoga2_Mapper_City extends Ladoga2_Mapper_Abstract {

    protected $_name = "adr_city";
    protected $_primary = "idcity";
    protected $_dependentTables = array('Ladoga2_Mapper_Shop');

}

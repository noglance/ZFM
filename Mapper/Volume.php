<?php

class Ladoga2_Mapper_Volume extends Ladoga2_Mapper_Abstract {

    protected $_name = 'volumes';
    protected $_primary = 'id';
    protected $_dependentTables = array('Ladoga2_Mapper_Production');


}

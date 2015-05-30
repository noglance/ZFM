<?php

class Ladoga2_Model_Shop extends Ladoga2_Model_AbstractRelation {

    protected $primary = array('idshop');
    protected $params = array(
        'iddist',
        'latitude',
        'longitude',
        'searchline',
        'useraddress',
        'idkind',
        'idarea',
        'idcity',
        'idaddress',
        'idpremise',
        'created',
        'date',
        'iduser',
        'deleted',
        'iduser_delited'
    );
    protected $parents = array(
        'Area' => array(
            'prop' => array('idarea' => 'idarea'),
            'parentClass' => 'Ladoga2_Model_Area'
        ),
        'City' => array(
            'prop' => array('idcity' => 'idcity'),
            'parentClass' => 'Ladoga2_Model_City'
        ),
        'Address' => array(
            'prop' => array('idaddress' => 'idaddress'),
            'parentClass' => 'Ladoga2_Model_Address'
        ),
        'Premise' => array(
            'prop' => array('idpremise' => 'idpremise'),
            'parentClass' => 'Ladoga2_Model_Premise'
        ),
        'Kind' => array(
            'prop' => array('idkind' => 'idkind'),
            'parentClass' => 'Ladoga2_Model_Kind'
        )
//        ,
//        'Matrix' => array(
//            'ext' => array(
//                'keys' => array('idkind' => 'idkind'),
//                'intersectoinClass' => 'Ladoga2_Model_MatrixShop'
//                ),
//            'parentClass' => 'Ladoga2_Model_Matrix'
//        )
    );

    protected $many2many = array(
        'Promotion' => array(
            'intersectoinClass' => 'Ladoga2_Model_PromotionShop',
            'matchClass' => 'Ladoga2_Model_Promotion'
        ),
        'Matrix' => array(
            'intersectoinClass' => 'Ladoga2_Model_MatrixShop',
            'matchClass' => 'Ladoga2_Model_Matrix'            
        )
    );
    
    public function getAddress(){
        if($this->getParentKind()->name != 'house'){
            $address = $this->useraddress;
            $address = str_replace( $this->getParentArea()->name . ', ', '', $address);
            $address = str_replace( $this->getParentCity()->name . ', ', '', $address);
            
        }else{
            $address = $this->getParentAddress()->name . ', ' . $this->getParentPremise()->name; 
        }
        return $address;
    }
}

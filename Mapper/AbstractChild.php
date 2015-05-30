<?php

class Ladoga2_Mapper_AbstractChild extends Ladoga2_Mapper_AbstractMany {

    public function fetchChilds(Ladoga2_Model_AbstractRelation $obj, $childName, $where = null, $order = null, $count = null, $offset = null){
        
        $params = $obj->getChildParams($childName);
        
        $childWhere = array();
        foreach ($params['prop'] as $keyObj => $keyChild) {
            $childWhere[$keyChild] = $obj->$keyObj;
        }
        
        $childWhere = $this->getWhereArray($childWhere);
        
        $childObj = new $params['childClass']();
        $childMapper = $childObj->mapper;
        
        $where = array_merge($childWhere, $where);
        return $childMapper->fAll($childObj, $where, $order, $count, $offset);
    }
}

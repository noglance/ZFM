<?php

class Ladoga2_Mapper_Abstract extends Ladoga2_Mapper_AbstractChild {

    protected function getWhereArray($array) {
        foreach ($array as $key => $value) {
            $array["$key = ?"] = $value;
            unset($array[$key]);
        }
        return $array;
    }

    public function remove($obj) {
        $params = array(
            'remover' => Zend_Registry::get('rid'),
            'deleted' => date('Y-m-d H:i:s')
        );
        $where = $this->getWhereArray($obj->getKeys());
        $where['deleted is null'] = null;
        $result = $this->update($params, $where);
        if($result == 1){
            return true;
        }
        return false;
    }

}

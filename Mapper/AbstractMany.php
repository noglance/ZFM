<?php

class Ladoga2_Mapper_AbstractMany extends Ladoga2_Mapper_AbstractParent {

    public function fetchMany(Ladoga2_Model_AbstractRelation $obj, $manyName, $timeLimit = null, $where = null, $order = null, $count = null, $offset = null) {

        $groupCol = 'created';
        $issetCol = 'isset';

        $params = $obj->getManyParams($manyName);

        $matchObjName = $params['matchClass'];
        $interObjName = $params['intersectoinClass'];

        $matchObj = new $matchObjName();
        $interObj = new $interObjName();

        $mapper = $obj->mapper;
        $matchMapper = $matchObj->mapper;
        $interMapper = $interObj->mapper;

        $matchInfo = $matchMapper->info();
        $interInfo = $interMapper->info();

        $interDb = $interMapper->getAdapter();

        $matchTableName = $matchInfo['name'];
        $interTableName = $interInfo['name'];
        $interPrimaryKeys = $interInfo['primary'];

        $groupKeyId = array_search($groupCol, $interPrimaryKeys);
        if ($groupKeyId) {
            unset($interPrimaryKeys[$groupKeyId]);
        }

        $max = $interMapper->select()
                ->from(array('mx' => $interTableName), new Zend_Db_Expr('max(mx.' . $groupCol . ')'));

        if (isset($timeLimit)) {
            $max->where('mx.' . $groupCol . ' <= ? ', $timeLimit);
        }

        foreach ($interPrimaryKeys as $col) {
            $i = $interDb->quoteIdentifier('i' . '.' . $col);
            $mx = $interDb->quoteIdentifier('mx' . '.' . $col, true);

            $max->where("$i = $mx");
        }

        $select = $matchMapper->select()
                ->where("i.$groupCol = ?", $max)
                ->where("i.$issetCol = ?", true);

        $mapper->setRowClass(preg_replace("/_Model_.*/", '_Row_Abstract', get_class($obj)));


        $row = $mapper->find($obj->getKeys());
        $row = $row->current();

        $result = $row->findManyToManyRowset(get_class($matchMapper), get_class($interMapper), null, null, $select);


        $matchMapper->_joinParent($select, $matchObj, 'm');

        if (isset($where)) {
            $this->_where($select, $where);
        }

        if (isset($order)) {
            $select->order($order);
        }
        if (isset($count)) {
            $select->limit($count);
        }
        $stmt = $select->query();
        $result = $stmt->fetchAll(Zend_Db::FETCH_ASSOC);


        $set = array();

        $objRow = new $matchObjName();
        $parents = $objRow->getParents();

        foreach ($result as $k => $r) {

            $r['autoload'] = false;
            $objRow = new $matchObjName($r);


            $row = array_diff_key($r, $objRow->keys);

            foreach ($parents as $parent => $map) {
                $data = array();
                $isempty = true;
                $parentObj = null;
                foreach ($r as $col => $value) {
                    if (preg_match("/^($parent)([_])(.*)$/", $col, $matches)) {
                        $data[$matches[3]] = $value;
                        $isempty = (is_null($value) && $isempty) ? true : false;
                        unset($r[$col]);
                    }
                }
                if (!$isempty) {
                    $data['autoload'] = false;
                    $parentObj = new $map['parentClass']($data);
                    $objRow->setParent($parent, $parentObj);
                } else {
                    $objRow->setParent($parent, null);
                }
            }

            /*
             * 
             * 
             */

            $keys = $objRow->getKeys();
            ksort($keys);
            $keys = array_filter($keys);
            $keys = implode('_', $keys);

            $set[$keys] = $objRow;
        }

        return $set;
    }

}

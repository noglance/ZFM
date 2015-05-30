<?php

/**
 * Description of Ladoga2_Model_Period
 *
 * @author gurt_noglance
 */
class Ladoga2_Model_IosList {

    /**
     * get($array, $depth, $levels)
     * $array - list for IosList tranformation
     * $levels - array of IosList levels ($keys of $array field)
     *
     * 
     * !!!WARNING!!!
     * 
     * $array should be sorted by $levels from first to last level
     */
    public static function foo() {
        
    }

    public static function get($list, $levels, $row) {
        $result = array(
            'count' => 0,
            'total' => 0,
            'items' => array()
        );
        foreach ($list as $l) {
            $pointer = &$result['items'];
            foreach ($levels as $key => $params) {
                $pointer = &$pointer[$l[$key]];
                if (!isset($pointer)) {
                    $pointer = array(
                        'id' => $l[$key], // id категории
                        'title' => $l[$params['keyTitle']], //имя категории
                        'filterTitle' => $params['filter'], //название группы для категории
                        'count' => 1,
                        'items' => array()
                    );
                } else {
                    $pointer['count']++;
                }
                $pointer = &$pointer['items'];
            }
            $data = array();
            foreach ($row as $key => $r) {
                if(is_int($key)){
                    $data[$r] = $l[$r];
                }else{
                    $data[$key] = call_user_func($r, $l);
                }
            }
            $pointer[] = $data;
            $result['count']++;
            $result['total']++;
        }

        return $result;
    }

}
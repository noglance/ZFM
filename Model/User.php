<?php

class Ladoga2_Model_User extends Ladoga2_Model_AbstractRelation {

    protected $primary = 'iduser';
    protected $params = array(
        'email',
        'password',
        'surname',
        'name',
        'patronymic',
        'phone',
        'inviter_iduser',
        'add_date',
        'del_date',
        'act_key',
        'act_date'
    );
    protected $many2many = array(
        'Group' => array(
            'intersectoinClass' => 'Ladoga2_Model_UserGroup',
            'matchClass' => 'Ladoga2_Model_Group'
        ),
        'Distributor' => array(
            'intersectoinClass' => 'Ladoga2_Model_UserDistributor',
            'matchClass' => 'Ladoga2_Model_Distributor'
        ),
        'Shop' => array(
            'intersectoinClass' => 'Ladoga2_Model_OfficerShop',
            'matchClass' => 'Ladoga2_Model_Shop'
        ),
        'Directorate' => array(
            'intersectoinClass' => 'Ladoga2_Model_UserDirectorate',
            'matchClass' => 'Ladoga2_Model_Directorate'
        )
    );

    public function setManyGroup($value, $isset = true) {
        if (!is_array($value)) {
            $value = array('idgroup' => $value);
        }
        $mprop[] = array('keys' => $value, 'isset' => (bool) $isset);
        return $this->setMany('Group', $mprop);
    }

    public function setManyDirectorate($value, $isset = true) {
        if (!is_array($value)) {
            $value = array('iddirectorate' => $value);
        }
        $mprop[] = array('keys' => $value, 'isset' => (bool) $isset);

        if ($this->idIsSet()) {
            /*
             * Отменяем старые привязки пользователя к дирекции, если такой пользователь уже сохранён
             */
            $manyParams = $this->getManyDirectorate($timeLimit = null, $where = array('isset = ?' => true), $order = 'created DESC', $count = 1, $offset = null);
            foreach ($manyParams as $manyParam) {
                /*
                 * Проверяем, что назначаемое свойство не совпадает с уже назначенным. В таком случае отменять назначенное не нужно.
                 */
                if($value['iddirectorate'] != $manyParam->id){
                    $mprop[] = array('keys' => array('iddirectorate' => $manyParam->id), 'isset' => false);
                }
            }
        }
        
        return $this->setMany('Directorate', $mprop);
    }

    public function getAct_key() {
        if (!isset($this->act_key)) {
            $this->act_key = md5(rand(100000, 999999) . time());
        }
        return $this->params['act_key'];
    }

    public function getAdd_date() {
        if (!isset($this->add_date)) {
            $this->add_date = date('Y-m-d H:i:s');
        }
        return $this->params['add_date'];
    }

    public function getInviter_iduser() {
        if (!isset($this->params['inviter_iduser'])) {
            $this->inviter_iduser = Zend_Registry::get('rid');
        }
        return $this->params['inviter_iduser'];
    }

}

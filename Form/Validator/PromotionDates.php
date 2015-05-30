<?php

class Ladoga2_Form_Validator_PromotionDates extends Zend_Validate_Abstract {
    const NOT_UNIQUE = 'notUnique';

    protected $_messageTemplates = array(
        self::NOT_UNIQUE => "Дата окончания акции должна быть позже даты начала или совпадать с ней"
    );

    public function isValid($value, $context = null) {
        $this->_setValue($value);
        $isValid = true;

        $good = new Ladoga_Model_Good();
        
        $start = strtotime($value);
        $final = strtotime($context['final']);
        if ($start > $final) {
            $this->_error(self::NOT_UNIQUE);
            $isValid = false;
        }
        return $isValid;
    }

}

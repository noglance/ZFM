<?php

class Ladoga2_Form_Validator_StartDate extends Zend_Validate_Abstract {
    const NOT_VALID_PERIOD = 'notValidPeriod';

    protected $_messageTemplates = array(
        self::NOT_VALID_PERIOD => "Акция должна начинаться после даты завершения текущего периода"
    );

    public function isValid($value, $context = null) {
        $this->_setValue($value);
        $isValid = true;

        $period = Ladoga2_Model_Period::getPeriod();
        $start = strtotime($value);
        if ($start <= $period) {
            $this->_error(self::NOT_VALID_PERIOD);
            $isValid = false;
        }
        return $isValid;
    }

}

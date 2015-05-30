<?php

/**
 * Description of Ladoga2_Model_Period
 *
 * @author gurt_noglance
 */
class Ladoga2_Model_Period {

    /**
     * 
     */
    public static function checkdates() {
        if (!Zend_Registry::isRegistered('checkdates')) {
            $config = new Zend_Config_Ini(APPLICATION_CONGIF_PATH, APPLICATION_ENV);
            $checkdates = $config->report->checkdates->toArray();
            sort($checkdates);
            Zend_Registry::set('checkdates', $checkdates);
        }
        return Zend_Registry::get('checkdates');
    }

    /**
     * изменения правила пересчета разрешения/запрета на линковку
     * магазина за торговым представилем
     * начиная с 2011-01-04 23:59:59 изменения вступают только начиная со следующего
     * отчетного периода, то есть отсекаются все, которые внесены до
     * self::getPrevPeriod() - начало периода
     * раньше изменения можно было вносить до
     * $period - конец самого периода
     */
    public static function croced($period) {
        return ($period <= '2011-01-04 23:59:59') ? $period : self::getPrevPeriod($period);
    }

    /**
     * getMonthTimeline($month,$year)
     * возвращает все периоды для месяца $month года $year
     * так же включая предыдущий период (последний период предыдущего месяца)
     * и последующий период (первый период последующего месяца)
     */
    private static function getMonthTimeline($month, $year) {
        $timeline = array();
        array_push($timeline, self::getPreviousMonth($month, $year));
        foreach (self::checkdates() as $c) {
            array_push($timeline, mktime(23, 59, 59, $month, $c, $year));
        }
        array_push($timeline, self::getNextMonth($month, $year));
        sort($timeline);
        return $timeline;
    }

    /**
     * getPrevPeriod($period)
     * возвращает предыдущий периоду $period период
     *
     * $period - Y-m-d H:i:s
     */
    public static function getPrevPeriod($period) {
        $month = date('m', strtotime($period));
        $year = date('Y', strtotime($period));
        $p = strtotime($period);
        $timeline = self::getMonthTimeline($month, $year);
        $previous = -$p;

        foreach ($timeline as $t) {
            if ($previous < $t && $t < $p) {
                $previous = $t;
            }
        }
        return date('Y-m-d H:i:s', $previous);
    }

    /**
     * getPeriod($date)
     * возвращает период, которому пренадлежит дата
     *
     * $date = unix time
     */
    public static function getPeriod($date = null) {
        $date = (isset($date)) ? $date : time();
        $month = date('m', $date);
        $year = date('Y', $date);
        $mtimeline = self::getMonthTimeline($month, $year);
        $min = min($mtimeline);
        foreach ($mtimeline as $mt) {
            if ($min < $date && $date <= $mt) {
                return $mt;
            } else {
                $min = $mt;
            }
        }
        return -1;
    }
    
    /**
     * getPeriod($date)
     * возвращает период, которому пренадлежит дата
     *
     * $date - Y-m-d H:i:s
     */
    public static function getUPeriod($date = null) {
        $date = (isset($date)) ? $date : date('Y-m-d H:i:s');
        $date = strtotime($date);
        $period = self::getPeriod($date);
        return date('Y-m-d H:i:s',$period);
    }

    /**
     * getNextPeriod($period)
     * возвращает следующий периоду $period период
     *
     * $date - Y-m-d H:i:s
     */
    public static function getNextPeriod($date = null) {
        $date = (isset($date)) ? $date : date('Y-m-d H:i:s');
        $date = strtotime($date);
        $period = self::getPeriod($date);
        $period++;
        $period = self::getPeriod($period);
        return date('Y-m-d H:i:s',$period);
    }

    /**
     * getTimeline($date)
     * возвращает временную шкалу периодов начиная с даты $start - unix time
     * по $final - unix time, если $final = null, то по текущую дату
     *
     * $start - unix time
     * $final - unix time
     */
    public static function getTimeline($start, $final = null) {
        $fperiod = self::getPeriod($start);
        if ($fperiod == -1) {
            return -1;
        }

        $timeline = array('u' => array(), 'd' => array());

        $final = (isset ($final)) ? $final : time();
        $pcurrent = self::getPeriod($final);
        
        if ($fperiod <= $pcurrent) {
            array_push($timeline['u'], $fperiod);
            array_push($timeline['d'], date('Y-m-d H:i:s', $fperiod));
            $fperiod = self::getPeriod($fperiod + 1);
            while ($fperiod <= $pcurrent) {
                array_push($timeline['u'], $fperiod);
                array_push($timeline['d'], date('Y-m-d H:i:s', $fperiod));
                $fperiod = self::getPeriod($fperiod + 1);
            }
        }
        return $timeline;
    }

    /**
     * getUTimeline($start, $final = null)
     * возвращает временную шкалу периодов начиная с даты $start
     * по $final , если $final = null, то по текущую дату
     *
     * $start - Y-m-d H:i:s
     * $final - Y-m-d H:i:s
     */
    public static function getUTimeline($start, $final = null) {
        
        $start = strtotime($start);
        $final = (isset ($final))? strtotime($final) : null;
        return self::getTimeline($start, $final);
    }

    public function getBounds($period) {
        $checkdate = $period['checkdate'];
        $month = $period['month'];
        $year = $period['year'];

        $timeline = self::getMonthTimeline($month, $year);
        $date = mktime(23, 59, 59, $month, $checkdate, $year);
        $prev = null;
        foreach ($timeline as $k => $t) {
            if ($prev === null) {
                $prev = $k;
            } else {
                if ($timeline[$prev] < $date && $date <= $timeline[$k]) {
                    return array('prev' => date("Y-m-d H:i:s", $timeline[$prev]), // предыдущий период
                        'next' => date("Y-m-d H:i:s", $timeline[$k]), // сам период
                        'period' => date("Y-m-d H:i:s", mktime(0, 0, -1, $month, $checkdate, $year)), //старый формат
                        'uprev' => $timeline[$prev],
                        'unext' => $timeline[$k],
                        'uperiod' => mktime(0, 0, -1, $month, $checkdate, $year)
                    );
                }
                $prev = $k;
            }
        }
        return '-1';
    }

    private function getPreviousMonth($month, $year) {
        if ($month == 1) {
            $day = max(self::checkdates());
            return mktime(23, 59, 59, 12, $day, $year - 1);
        } else {
            $day = max(self::checkdates());
            return mktime(23, 59, 59, $month - 1, $day, $year);
        }
    }

    private function getNextMonth($month, $year) {
        if ($month == 12) {
            $day = min(self::checkdates());
            return mktime(23, 59, 59, 1, $day, $year + 1);
        } else {
            $day = min(self::checkdates());
            return mktime(23, 59, 59, $month + 1, $day, $year);
        }
    }

}
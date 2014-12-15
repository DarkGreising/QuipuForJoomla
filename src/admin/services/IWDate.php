<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
//-- No direct access
defined('_JEXEC') or die('=;)');

/**
 *
 */
class IWDate extends JDate {

    /**
     *
     * @param type $date
     * @param type $tz 
     */
    public function __construct($date = 'now', $tz = null) {
        /*
        if (is_string($date) && preg_match('/[0-3][0-9]\/[0-1][0-9]\/[0-9]{4}/', $date)) {
            $parts = explode('/', $date);
            $date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }*/

        if ($tz === null) {
            $tz = self::getUserTZ();
        }

        parent::__construct($date, $tz);
    }

    /**
     * @return DateTimeZone
     */
    protected static function getUserTZ() {
        $user = JFactory::getUser();        
        $tz = new DateTimeZone($user->getParam('timezone', IWConfig::getJConfigValue('offset')));
        return $tz;
    }

    /**
     * 
     * @return type
     */
    public static function mySQLDate() {
        $o = new IWDate();
        return $o->toSql();
    }

    /**
     * @todo: aquí habrá que gestionar el formato cuando soportemos varios locales...
     * 
     * @param type $date 
     */
    public static function _($date, $withTime = false) {
        if ($date && strpos($date, '0000') === FALSE) {
            return JHtml::date($date, JText::_('DATE_FORMAT_LC4'));
            /*
              $d = new IWDate($date);
              if ($withTime) {
              return $d->format('d/m/Y H:i', false, false);
              } else {
              return $d->format('d/m/Y', false, false);
              } */
        }
        return '-';
    }

    /**
     *
     * @param type $year
     * @param type $month
     * @return \IWDate 
     */
    public static function getFirstDate($year = false, $month = false) {
        $f = new IWDate();
        $year = $year ? $year : date('Y');
        $month = $month ? $month : date('n');
        $f->setDate($year, $month, 1);
        return $f;
    }

    /**
     *
     * @param type $year
     * @param type $month
     * @return \IWDate 
     */
    public static function getLastDate($year = false, $month = false) {
        $f = new IWDate();
        $year = $year ? $year : date('Y');
        $month = $month ? $month : date('n');
        $month = 1 + $month;
        $f->setDate($year, $month, 1);
        $f->modify('-1 day');
        return $f;
    }

    /*
     * Converts string dates into mysql format
     */

    public static function parseToMySQL($string, $withTime = false) {
        $format = JText::_('DATE_FORMAT_LC4');
        $sqlFormat = $withTime ? JFactory::getDbo()->getDateFormat() : 'Y-m-d';

        $d = self::iw_createFromFormat($format, $string);

        if ($d) {
            return $d->format($sqlFormat);
        } else {
            //do a simple recovery attempt by replacing '/' by '-'
            $string = strpos($string, '/') > 0 ? str_replace('/', '-', $string) : str_replace('-', '/', $string);
            $d = self::iw_createFromFormat($format, $string);
            if ($d) {
                return $d->format($sqlFormat);
            } else {
                JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_QUIPU_ERROR_DATE_FORMAT', $string, $format));
                return '';
            }
        }
    }

    /**
     * created to have this functionality in php 5.2
     * 
     */
    public static function iw_createFromFormat($format, $string) {

        if (method_exists('IWDate', 'createFromFormat')) {
            return self::createFromFormat($format, $string);
        }

        try {  
            JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_PHP52DATES'),'warning');
            if(strtolower(substr($format,0,1)) == 'd'){
                $string = str_replace('/','-', $string);
            }
            return new IWDate($string);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param type $fecha 
     */
    public static function isFuture($date) {
        $d = new IWDate($date);
        $n = new IWDate();

        $d = $d->toUnix();
        $n = $n->toUnix();

        
        return $d > $n;
    }

    /**
     * 
     * @param type $year
     */
    public static function getQuarterRanges($y = false) {
        if (!$y) {
            $y = (int) date('Y');
        }
        $q = 1;
        $ranges = array();
        foreach (array(1, 4, 7, 10) as $m) {
            $d1 = new IWDate();
            $d1->setTime(0, 0);
            $d1->setDate($y, $m, 1);
            $d2 = new IWDate();
            $d2->setTime(23, 59);
            $d2->setDate($y, $m + 3, 0);

            $ranges["Q$q"] = array($d1, $d2);

            $q++;
        }

        return $ranges;
    }

    /**
     * 
     */
    public static function getLastYearRange() {
        $y = (int) date('Y');
        return self::getYearRange($y - 1);
    }

    /**
     * 
     * @param type $y
     * @return an array of two IWDate objects, representing the 01-01 and 12-31 year $y
     */
    public static function getYearRange($y = false) {
        if (!$y) {
            $y = (int) date('Y');
        }
        $t0 = new IWDate();
        $t0->setDate($y, 1, 1);
        $t0->setTime(0, 0);

        $t1 = new IWDate();
        $t1->setDate($y, 12, 31);
        $t1->setTime(23, 59);

        return array($t0, $t1);
    }

}
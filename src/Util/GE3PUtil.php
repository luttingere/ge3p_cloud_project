<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 11:29 AM
 */

namespace App\Util;

use Cake\I18n\Time;
use Cake\Log\Log;
use DateTime;
use DateInterval;
use ZMQContext;
use Cake\Filesystem\File;

class GE3PUtil
{

    public static function getSQLFileContent($path, $fileName)
    {
        $fileName = $path . '/' . $fileName . ".sql";
        $file = new File($fileName, true, 0777);
        $file->open('r', false);
        $queryString = $file->read();
        $file->close();
        return $queryString;
    }

    public static function validateParameters($arrayToTest, $arrayReceived)
    {
        $result = array('code' => '0', 'message' => '');
        if (sizeof($arrayToTest) > 0) {
            foreach ($arrayToTest as $key => $val) {
                if (is_array($val)) {
                    $result = self::validateParameters($val, $arrayReceived[$key]);
                    if ($result['code'] != 0) {
                        break;
                    }
                } else {
                    if (!isset($arrayReceived[$val])) {
                        $result['code'] = '1';
                        $result['message'] = 'Invalid parameters, missing parameter ' . $val;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    public static function getSystemDate()
    {
        $time = Time::now();
        $time->setTimezone(TIME_ZONE);
        $dateAssigned = $time->i18nFormat('YYYY-MM-dd HH:mm:ss');
        return $dateAssigned;
    }

}
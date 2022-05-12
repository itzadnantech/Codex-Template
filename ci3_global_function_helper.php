<?php
if (!function_exists('userInfo')) {
    function userInfo($id)
    {
        $rec = getByWhere('users', '*', array('userId' => $id));
        return $rec[0];
    }
}
if (!function_exists('leadInfo')) {
    function leadInfo($leadId, $memberId)
    {
        $rec = getByWhere('leads' . $memberId, '*', array('leadId' => $leadId));
        return $rec[0];
    }
}

if (!function_exists('rvmInfo')) {
    function rvmInfo($rvmcampId)
    {
        $rec = getByWhere('rvm_campaign', '*', array('rvmcampId' => $rvmcampId));
        return $rec[0];
    }
}
if (!function_exists('campaignInfo')) {
    function campaignInfo($type, $id)
    {
        if ($type == 'rvm') {
            $table = 'rvm_campaign';
            $where = array('rvmcampId' => $id);
        } elseif ($type == 'sms') {
            $table = 'sms_compaigns';
            $where = array('vcId' => $id);
        } elseif ($type == 'voice') {
            $table = 'voice_compaigns';
            $where = array('vcId' => $id);
        }
        return getByWhere($table, '*', $where)[0];
    }
}
if (!function_exists('limitInfo')) {
    function limitInfo($memberId)
    {
        return getByWhere('users_limits', '*', array('memberId' => $memberId))[0];
    }
}

if (!function_exists('unsetEmptyArray')) {
    function unsetEmptyArray($arr)
    {
        return array_filter(
            $arr,
            function ($value) {
                return $value !== '' && $value !== Null && $value !== false;
            }
        );
    }
}
if (!function_exists('userInfoNumber')) {
    function userInfoNumber($number)
    {
        $rec = getByWhere('users', '*', array('twilioNumber' => $number));
        return $rec[0];
    }
}
if (!function_exists('leadInfoNumber')) {
    function leadInfoNumber($phoneNumber, $memberId)
    {
        return getByWhere('leads' . $memberId, '*', array('phoneNumber' => $phoneNumber))[0];
    }
}
if (!function_exists('memberDefaultGroup')) {
    function memberDefaultGroup($memberId)
    {
        $rec = getByWhere('lead_groups', '*', array('memberId' => $memberId, 'status' => 'Default'));
        return $rec[0];
    }
}
if (!function_exists('timeFormatSystem')) {
    function timeFormatSystem($timeStamp, $format = "m-d-Y h:i:s A")
    {
        return date($format, $timeStamp);
    }
}
if (!function_exists('timezoneInfo')) {
    function timezoneInfo($tzId)
    {
        return getByWhere('timezones', '*', array('tzId' => $tzId))[0];
    }
}
if (!function_exists('encrypt')) {
    function encrypt($plaintext)
    {
        $CI = &get_instance();
        return $CI->encryption->encrypt($plaintext);
    }
}
if (!function_exists('decrypt')) {
    function decrypt($ciphertext)
    {
        $CI = &get_instance();
        return $CI->encryption->decrypt($ciphertext);
    }
}
if (!function_exists('makeDirectory')) {
    function makeDirectory($folderName)
    {
        if (!file_exists($folderName)) {
            @mkdir($folderName, 0777);
        }
    }
}
if (!function_exists('phoneFormat')) {
    function phoneFormat($phoneNumber)
    {
        $phoneNumber = trim(str_replace(['+', '(', '/', '-', '_', ')', '*', ' '], "", $phoneNumber));
        if (strlen($phoneNumber) == 10) {
            $phoneNumber = '+1' . $phoneNumber;
        } elseif (strlen($phoneNumber) == 11) {
            $phoneNumber = '+' . $phoneNumber;
        }
        return $phoneNumber;
    }
}
if (!function_exists('readCredentials')) {
    function readCredentials($file)
    {
        $path = APPPATH . 'credentials/' . $file . '.txt';
        return @json_decode(file_get_contents($path));
    }
}
if (!function_exists('directories')) {
    function directories($directory, $type = "")
    {
        $glob = glob($directory . '/' . $type . '*');
        if ($glob === false) {
            return array();
        }
        $filter_dir = array_filter($glob, function ($dir) {
            return is_dir($dir);
        });
        $filter_folder = array();
        foreach ($filter_dir as $rec) {
            $ex_rec = explode('/', $rec);
            $filter_folder[$rec] = end($ex_rec);
        }
        return $filter_folder;
    }
}
if (!function_exists('randomKey')) {
    function randomKey($length = 16)
    {
        $characters = 'ab2cde1fghi4jklmn3opqrs5tuvwxyzAB6CDEFGHI1JK7LMNOP8QRST9UVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
if (!function_exists('runVivaCli')) {
    function runVivaCli($command)
    {
        if ($_SERVER['HTTP_HOST'] == '192.168.5.66') {
        } else {
            $cmd = '/usr/local/bin/php -q /home/hotprosp/publhtml/sms/index.php ' . $command;
            shell_exec($cmd . ' > /dev/null 2>/dev/null &');
        }
    }
}



// New Functions 3/12/2109
if (!function_exists('print_arr')) {
    function print_arr($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}
if (!function_exists('offsetServerTimezone')) {
    function offsetServerTimezone($timezone)
    {
        date_default_timezone_set($timezone);
        $DateTime = date("Y-m-d H:i:s");
        date_default_timezone_set("GMT");
        $serverDateTime = date("Y-m-d H:i:s");
        $Difference = (strtotime($DateTime) - strtotime($serverDateTime));
        $timeStr = $Difference / (60 * 60);
        $hour = explode('.', $timeStr);
        $hours = $hour[0];
        $minutes = $hour[1] * 6;
        if (empty($minutes)) {
            $minutes = '00';
        }
        $time = '';
        if ($hours <= 9) {
            $hours = sprintf('%03d', $hours);
        }
        $time = $hours . ':' . $minutes;
        return trim($time);
    }
}
if (!function_exists('offsetTimezone')) {
    function offsetTimezone($timezone)
    {
        date_default_timezone_set($timezone);
        $DateTime = date("Y-m-d H:i:s");
        date_default_timezone_set("GMT");
        $serverDateTime = date("Y-m-d H:i:s");
        $Difference = (strtotime($DateTime) - strtotime($serverDateTime));
        $timeStr = $Difference / (60 * 60);
        $hour = explode('.', $timeStr);
        $hours = $hour[0];
        $minutes = $hour[1] * 6;
        if (empty($minutes)) {
            $minutes = '00';
        }
        $time = '';
        if ($hours <= 9) {
            if ($hours < 0) {
                $hours = sprintf('%03d', $hours);
            } else {
                $hours = sprintf('%02d', $hours);
            }
        }
        $time = $hours . ':' . $minutes;
        return trim($time);
    }
}

if (!function_exists('server_to_client_date_time')) {
    function server_to_client_date_time($datetime, $time_zone)
    {
        if (!empty($time_zone)) {
            if ($datetime <> '0000-00-00 00:00:00') {
                $format = 'Y-m-d H:i:s';
                $from = new DateTimeZone('America/New_York');
                $to = new DateTimeZone($time_zone);
                $orgTime = new DateTime($datetime, $from);
                $toTime = new DateTime($orgTime->format("c"));
                $toTime->setTimezone($to);
                return $toTime->format($format);
            }
        }
    }
}
if (!function_exists('offsetServerTimezone')) {
    function offsetServerTimezone($timezone)
    {
        date_default_timezone_set($timezone);
        $DateTime = date("Y-m-d H:i:s");
        date_default_timezone_set("America/New_York");
        $serverDateTime = date("Y-m-d H:i:s");
        $Difference = (strtotime($DateTime) - strtotime($serverDateTime)) / 60;
        return $Difference . ' minutes';
    }
}
if (!function_exists('TimezonedateFormat')) {
    function TimezonedateFormat($date, $timezone)
    {
        $offset = offsetServerTimezone($timezone);
        $date = date('m-d-Y h:i:s A', strtotime($offset, strtotime($date)));
        return $date;
    }
}
if (!function_exists('deleteDir')) {
    function deleteDir($dir)
    {
        if (is_dir($dir)) {
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator(
                $it,
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
        }
    }
}
/**
 * New function 06/March/2020
 */
if (!function_exists('changeStatusTenseToPast')) {
    function changeStatusTenseToPast($str)
    {
        $lastChar = substr(strtolower($str), -1);
        if ($lastChar != 'g' && $lastChar != 'd') {
            if ($lastChar == 'e') {
                return $str . 'd';
            } elseif ($lastChar == 'p') {
                return $str . 'ped';
            } else {
                return $str . 'ed';
            }
        } else {
            return $str;
        }
    }
}

<?php
namespace common\components;

use Yii;
use yii\base\Component;

class Myclass extends Component {

    public static function welcome()
    {
     echo "Hello..Welcome to MyComponent";

    }
    
    public static function translate_mail($msg_dub, $translate = array()) {        
        $message = strtr($msg_dub, $translate);
        return $message;
    }
    

    public static function encrypt($value) {
        return hash("sha512", $value);
    }

    public static function refencryption($str) {
        return base64_encode($str);
    }

    public static function refdecryption($str) {
        return base64_decode($str);
    }

    public static function t($str = '', $params = array(), $dic = 'app') {
        return Yii::t($dic, $str, $params);
    }

    public static function getRandomString($length = 9) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //length:36
        $final_rand = '';
        for ($i = 0; $i < $length; $i++) {
            $final_rand .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $final_rand;
    }

    public static function getRandomNUmbers($length = 8) {
        $chars = "1234567890";
        $final_rand = '';
        for ($i = 0; $i < $length; $i++) {
            $final_rand .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $final_rand;
    }

     public static function addAuditTrail($message, $class = 'comment-o') {
        $obj = new AuditTrail();
        $obj->aud_message = $message;
        $obj->aud_class = $class;
        $obj->save();
        return;
    }

    public static function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function is_home_page() {
        $app = Yii::app();
        return $app->controller->route == $app->defaultController;
    }

    public static function rememberMeAdmin($username, $check) {
        if ($check > 0) {
            $time = time();     // Gets the current server time
            $cookie = new CHttpCookie('admin_username', $username);

            $cookie->expire = $time + 60 * 60 * 24 * 30;               // 30 days
            Yii::app()->request->cookies['admin_username'] = $cookie;
        } else {
            unset(Yii::app()->request->cookies['admin_username']);
        }
    }

    public static function getGuid($opt = false) {
        if (function_exists('com_create_guid')) {
            if ($opt) {
                return com_create_guid();
            } else {
                return trim(com_create_guid(), '{}');
            }
        } else {
            mt_srand((double) microtime() * 10000);    // optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);    // "-"
            $left_curly = $opt ? chr(123) : "";     //  "{"
            $right_curly = $opt ? chr(125) : "";    //  "}"
            $uuid = $left_curly
                    . substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12)
                    . $right_curly;
            return $uuid;
        }
    }

    public static function getMonths() {
        $months = array(
            "01" => 'January',
            "02" => 'February',
            "03" => 'March',
            "04" => 'April',
            "05" => 'May',
            "06" => 'June',
            "07" => 'July',
            "08" => 'August',
            "09" => 'September',
            "10" => 'October',
            "11" => 'November',
            "12" => 'December',
        );
        return $months;
    }
    public static function getMonths_M($m) {
            $months = array(
            1 => 'Jan',
            2 => 'Fév',
            3 => 'Mar',
            4 => 'Avr',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juil',
            8 => 'Août',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Déc',
            );

        return $months[$m];
    }
    public static function getMonths_Fr($m) {
        $months = array(
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        );
        return $months[$m];
    }

    public static function getBetweenDates($date_from, $date_to) {
        // Specify the start date. This date can be any English textual format
        $date_from = strtotime($date_from); // Convert date to a UNIX timestamp
        // Specify the end date. This date can be any English textual format
        $date_to = strtotime($date_to); // Convert date to a UNIX timestamp
        // Loop from the start date to end date and output all dates inbetween
        $result = array();
        for ($i = $date_from; $i <= $date_to; $i+=86400) {
            $result[] = date("Y-m-d", $i);
        }
        return $result;
    }


    public static function currencyFormat($number) {
        return Yii::$app->formatter->asCurrency($number,'USD') ;
    }

    public static function numberFormat($number) {
        if (Yii::app()->session['language'] == 'FR') {
            return number_format($number, 2, ",", " ");
        } else {
            return number_format($number, 2);
        }
    }

    public static function format_numbers_words($totalusers) {

        if (!is_numeric($totalusers)) {
            return false;
        }

        // filter and format it
        if ($totalusers > 1000000000000) {
            return round(($totalusers / 1000000000000)) . ' trillion';
        } elseif ($totalusers > 1000000000) {
            return round(($totalusers / 1000000000)) . ' billion';
        } elseif ($totalusers > 1000000) {
            return round(($totalusers / 1000000)) . ' million';
        } elseif ($totalusers > 10000) {
            return Myclass::t('OG210') . round(($totalusers / 1000)) . ' 000';
        } elseif ($totalusers > 1000) {
            return Myclass::t('OG210') . round(($totalusers / 1000)) . '000';
        } elseif ($totalusers > 100) {
            return Myclass::t('OG210') . round(($totalusers / 100)) . '00';
        } else {
            return $totalusers;
        }
    }

    public static function getallcountries($id = null) {
        $criteria = new CDbCriteria;
        $criteria->order = 'country_desc ASC';
        $country = DmvCountry::model()->findAll($criteria);
        $val = CHtml::listData($country, 'id', 'country_desc');
        return $val;
    }

    public static function card_types()
    {
        $card = array();
        $card['CC'] = 'Credit Card';
        $card['CQ']= 'Check';
        $card['CA']= 'Cash';
        $card['MO']= 'Money Order';
        return $card;
    }

    public static function dateformat($cdate)
    {
        return date("Y-m-d",strtotime($cdate));
    }

    public static function date_dispformat($cdate)
    {
        return date("m-d-Y",strtotime($cdate));
    }

    public static function time_dispformat($cdate)
    {
        return date("h:i A",strtotime($cdate));
    }

    public static function getsqlcommand($sql)
    {
        if (isset(Yii::app()->session['currentdb']) && Yii::app()->session['currentdb'] == "olddb") {
            $command = Yii::app()->dbold->createCommand($sql);
        }else{
            $command = Yii::app()->db->createCommand($sql);
        }

        return $command;
    }

}

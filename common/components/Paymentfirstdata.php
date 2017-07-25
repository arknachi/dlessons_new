<?php

namespace common\components;

use Yii;
use yii\base\Component;

class Paymentfirstdata extends Component {

    const LIVE_API_URL = 'https://api.globalgatewaye4.firstdata.com/transaction/';
    const TEST_API_URL = 'http://api.demo.globalgatewaye4.firstdata.com/transaction/';

    // protected $host = "api.demo.globalgatewaye4.firstdata.com";
    protected $host = "api.globalgatewaye4.firstdata.com";
    protected $protocol = "https://";
    protected $uri = "/transaction/v12";

    /* Modify this acording to your firstdata api stuff */
    protected $hmackey = "EDqPxP_jCgt5~H8GYTYbF2~632GfMg1M";
    protected $keyid = "251420";
    protected $gatewayid = "AI9024-05";
    protected $password = "u5apa5c4sr8c2nok406xndr53462k3z3";
    protected $error;
    protected $result_msg;

    public function __construct() {
        $this->error = 'Success';
        $this->result_msg = array();        
    }

    public function config($mode = 'LIVE') {

        if ($mode == 'LIVE') {
            $this->host = "api.globalgatewaye4.firstdata.com";
            $this->protocol = "https://";
            $this->uri = "/transaction/v12";
            $this->hmackey = "2JuTldOI0e0SGArC00y6U74MlYOWW2zS";
            $this->keyid = "69749";
            $this->gatewayid = "A76190-01";
            $this->password = "Tb0WjZ9T0InpSOPtDRZg9MDHCSeE9A2X";
        } elseif ($mode == 'NY') {
            $this->host = "api.globalgatewaye4.firstdata.com";
            $this->protocol = "https://";
            $this->uri = "/transaction/v12";
            $this->hmackey = "aoX4stqhvpIAGQF8a8wqOLaUMhz6mFWH";
            $this->keyid = "495329";
            $this->gatewayid = "H20115-91";
            $this->password = "8wbL8qewennc458wnRPTAMAsaDpipwk1";
        } else {
            $this->host = "api.demo.globalgatewaye4.firstdata.com";
            $this->protocol = "https://";
            $this->uri = "/transaction/v12";
            $this->hmackey = "EDqPxP_jCgt5~H8GYTYbF2~632GfMg1M";
            $this->keyid = "251420";
            $this->gatewayid = "AI9024-05";
            $this->password = "u5apa5c4sr8c2nok406xndr53462k3z3";
        }
    }

    public function purchase($data) {
        if ($data['amount'] == 0)
            return true;
        $location = $this->protocol . $this->host . $this->uri;

        $request = array(
            'transaction_type' => "00",
            'amount' => $data['amount'],
            'cc_expiry' => $data['cc_expiry'],
            'cc_number' => $data['cc_number'],
            'cardholder_name' => $data['name_cardholder'],
            'reference_no' => rand(),
            'cc_verification_str2' => $data['cvv'],
            'cvdcode' => $data['cvv'],
            'cavv' => $data['cvv'],
            'cvd_presence_ind' => 1,
            'client_ip' => $_SERVER['REMOTE_ADDR'],
            'client_email' => (!empty($data['email']) ? $data['email'] : ''),
            'gateway_id' => $this->gatewayid,
            'password' => $this->password,
        );
     
        $content = json_encode($request);    
        //$gge4Date = strftime("%Y-%m-%dT%H:%M:%S", time()) . 'Z';
        $gge4Date = strftime("%Y-%m-%dT%H:%M:%S", time() - (int) substr(date('O'), 0, 3) * 60 * 60) . 'Z';
        $contentType = "application/json";
        $contentDigest = sha1($content);
        $contentSize = sizeof($content);
        $method = "POST";
        $hashstr = "$method\n$contentType\n$contentDigest\n$gge4Date\n$this->uri";
        $authstr = 'GGE4_API ' . $this->keyid . ':' . base64_encode(hash_hmac("sha1", $hashstr, $this->hmackey, true));
        $headers = array(
            "Content-Type: $contentType",
            "X-GGe4-Content-SHA1: $contentDigest",
            "X-GGe4-Date: $gge4Date",
            "Authorization: $authstr",
            "Accept: $contentType"
        );
        
        //CURL stuff
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $location);

        //Warning ->>>>>>>>>>>>>>>>>>>>
        /* Hardcoded for easier implementation, DO NOT USE THIS ON PRODUCTION!! */
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //Warning ->>>>>>>>>>>>>>>>>>>>

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        //This guy does the job
        $output = curl_exec($ch);

        //echo curl_error($ch); 
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = $this->parseHeader(substr($output, 0, $header_size));
        $body = substr($output, $header_size);

        curl_close($ch);
        //Print the response header
        // echo "<pre>";  print_r($header);	exit;	
        /* If we get any of this X-GGe4-Content-SHA1 X-GGe4-Date Authorization
         * then the API call is valid */
        if (isset($header['authorization'])) {
            //Ovbiously before we do anything we should validate the hash
            $msg = json_decode($body);
            // echo $msg->ctr;
//            echo "<pre>";
//            print_r($msg);
//            exit();
            if (!empty($msg->bank_message) && $msg->bank_message == 'Approved') {
                $this->result_msg = $msg;
                return true;
            } elseif (!empty($msg->bank_message) && ($msg->transaction_approved) == 1) {
                $this->result_msg = $msg;
                return true;
            } elseif (!empty($msg->bank_message)) {
                $this->error = $msg->bank_message;
                return false;
            } else {
                $this->error = $header['status'];
                return false;
            }
        }
        //Otherwise just debug the error response, which is just plain text
        else {
            //echo $body;
            $this->error = $body;
            return false;
        }
    }

    private function parseHeader($rawHeader) {
        $header = array();
        //http://blog.motane.lu/2009/02/16/exploding-new-lines-in-php/
        $lines = preg_split('/\r\n|\r|\n/', $rawHeader);
        foreach ($lines as $key => $line) {
            $keyval = explode(': ', $line, 2);
            if (isset($keyval[0]) && isset($keyval[1])) {
                $header[strtolower($keyval[0])] = $keyval[1];
            }
        }
        return $header;
    }

    public function error() {
        return $this->error;
    }

    public function result($node = '') {
        if (!empty($node)) {
            return $this->result_msg->{$node};
        } else {
            return $this->result_msg;
        }
    }

}

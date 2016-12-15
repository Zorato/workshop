<?php
/**
 * SMS Class
 *
 * @author   Sergejs Smirnovs <sergegejs.smirnovs@itgrupa.lv>
 * @version  SVN: $Id: ManaPoliseBankLink.php 249 2008-07-15 14:19:06Z sergejs.nosovs $
 */

DEFINE('SMS_PROVIDER', 2); // 1 - infobip; 2 - text2reach; 0 - salesLV

class ManaPoliseSMS
{
    private $config = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = Controller::getConfig();
        switch(SMS_PROVIDER) {
            case 0: if(isset($config->sms)) $this->config = $config->sms->toArray(); break;
            case 2: if(isset($config->sms_text2reach)) $this->config = $config->sms_text2reach->toArray(); break;
            case 1:
            default: if(isset($config->sms_infobip)) $this->config = $config->sms_infobip->toArray(); break;
        }
    }

    public function setFrom($from)
    {
        $this->config['from'] = $from;
    }

    /**
     * Function for sending sms
     *
     * @param str $phoneNumber
     * @param str $message
     */
    public function sendMessage($phoneNumber = false, $message = false, $orderID = 0)
    {
        switch(SMS_PROVIDER) {
            case 0: $this->sendMessageSalesLV($phoneNumber, $message, $orderID); break;
            case 2: $this->sendMessageText2Reach($phoneNumber, $message, $orderID); break;
            case 1:
            default: $this->sendMessageInfoBip($phoneNumber, $message, $orderID); break;
        }
    }

    /**
     * SMS sending using text2reach service
     *
     * @param str $phoneNumber description
     * @param str $message description
     */
    private function sendMessageText2Reach($phoneNumber = false, $message = false, $orderID = 0) {
        // Validate mandotory fields
        $errors = array();
        if(!$phoneNumber) $errors[] = 'Phone number is not set.';
        if(!$message)     $errors[] = 'Message body is not set.';
        if(!empty($errors)) {
            $this->log($phoneNumber, $message, $orderID, "Canceled. ".implode(' ', $errors) , false);
            return false;
        }

        // Format phone number
        $formatedPhoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber); // remove non digit chars
        if(!preg_match("/^371[0-9]{8,8}$/", $formatedPhoneNumber)) {
            if(strlen($formatedPhoneNumber) == 8) $formatedPhoneNumber = '371'.$formatedPhoneNumber; // add country code
            else {
                $this->log($phoneNumber, $message, $orderID, "Canceled. Incorrect phone number." , false);
                return false;
            }
        }

        /*
         * Response codes
         * > 0 Success. Unique message id number.
         */
        $responseCodes = array(
            '-400' => 'Wrong API KEY for the request',
            '-500' => 'Missing required parameters',
            '-501' => 'Wrong type, must be txt or bin',
            '-503' => 'Destination address is blocked',
            '-504' => 'Not available for this operator',
            '-508' => 'Wrong destination address',
            '-509' => 'Wrong message encoding',
            '-511' => 'Number does not exist or operator/owner has been changed',
            '-513' => 'Wrong message length',
            '-555' => 'General system error'
        );

        // Compose request query
        $query = sprintf("%s?api_key=%s&type=%s&phone=%s&message=%s&from=%s&timestamp=%s&expires=%s&report_url=%s",
                         $this->config['request_url'],
                         $this->config['api_key'],
                         $this->config['type'],
                         $formatedPhoneNumber,
                         urlencode($message),
                         $this->config['from'],
                         $this->config['timestamp'],
                         $this->config['expires'],
                         $this->config['report_url']
        );

        // Send SMS
        $fp = @fopen($query, 'r');
        $rows = array();
        if(!$fp) $fp = @fopen($query, 'r');
        if(!$fp) $fp = @fopen($query, 'r');
        if($fp) {
            while(!feof($fp)) $rows[] = fgets($fp, 4096);
            $result = implode("\n", $rows);

            // Parse response / if responed with error code
            if(array_key_exists($result, $responseCodes)) $result = $responseCodes[$result];

            $this->log($formatedPhoneNumber, $message, $orderID, $result, true);
            return $result;
        }
        else { // No connection was established
            $this->log($formatedPhoneNumber, $message, $orderID, "Could not open stream. Message not sent.", false);
            return false;
        }

    }

    private function sendMessageSalesLV( $phoneNumber = false, $message = false, $orderID=0)
    {
        #validating mandotory fields
        if( !strlen( $phoneNumber ) || !strlen( $message ))
        {
            $this->log($phoneNumber, $message, $orderID, "Canceled. Either phone number or message body not set.", false);
            return FALSE;
        }

        if (strpos($phoneNumber, '371') === 0)
        {
            $phoneNumber = substr($phoneNumber, 3);
        }

        if (strpos($phoneNumber, '+371') === 0)
        {
            $phoneNumber = substr($phoneNumber, 4);
        }

        #validating for correct phone number 2*******
        if(substr( $phoneNumber, 0, 1 ) != 2 || strlen( $phoneNumber ) !=8)
        {
            $this->log($phoneNumber, $message, $orderID, "Canceled. Incorrect phone number.", false);
            return FALSE;
        }
        #validating for correct text length
        #concatenated&&multilange = 210
        #concatenated only = 459
        #multilange only = 67
        #usual message = 160

        $maxLength = $this->config[ 'concatenated' ] ? ($this->config[ 'multilanguage' ] ? 210 : 459) : ($this->config[ 'multilanguage' ] ? 67 : 160);
        if( strlen( $message ) > $maxLength )
        {
            $this->log($phoneNumber, $message, $orderID, "Canceled. Message too long.", false);
            return FALSE;
        }

        //~ print '5';
        $message_encoded = urlencode( $message );
        $phoneNumber = '371' . $phoneNumber;
        #concatenating URL
        $url = $this->config[ 'request_url' ] .
               '?user=' . $this->config[ 'user' ] .
               '&from=' . $this->config[ 'from' ] .
               '&phone=' . $phoneNumber .
               '&msg=' . $message_encoded .
               '&multilanguage=' . $this->config[ 'multilanguage' ] .
               '&concatenated=' . $this->config[ 'concatenated' ];


        $fp = @fopen( $url, 'r' );
        $result = array();
        if (!$fp) $fp = @fopen($url, 'r');
        if (!$fp) $fp = @fopen($url, 'r');
        if ($fp)
        {
            //~ print 'OK';
            while (!feof($fp)) {
                $result[] = fgets($fp, 4096);
            }
            $rez = join( "\n", $result);

            $this->log($phoneNumber, $message, $orderID, $rez, true);
            return $rez;
        }
        else
        {
            //~ print 'BAD!';
            $this->log($phoneNumber, $message, $orderID, "Could not open stream. Message not sent.", false);
            return FALSE;
        }

    }

    public function receive()
    {

        #mobile number from GET
        $mob = Request::getGet('mob', Alpha_Request::TYPE_STRING, '');

        #message GET
        $message = Request::getGet('sms_text', Alpha_Request::TYPE_STRING, '');;

        #date from GET
        $date = Request::getGet('sms_datum', Alpha_Request::TYPE_STRING, '');;


        if( !$message || !$mob )
        {
            return false;
        }
        return array(
            'phoneNumber' => $mob,
            'message' => $message,
            'date' => $date
        );

    }

    private function log($phoneNumber, $message, $orderID, $result, $sendMail)
    {
        $date = date('Y-m-d H:i:s');
        $month = (int)date('m');

        Db::query('INSERT INTO _sms_log(date, message, keyword, mob_number, car_number, doc_number, order_id, month, response, rezult)
                  VALUES(%s, %s, %s, %s, %s, %s, %d, %d, %s, %s)'
            , $date, $message, '', $phoneNumber, '', '', $orderID, $month, $result, '');
    }



    private function sendMessageInfoBip( $phoneNumber = false, $message = false, $orderID=0)
    {
        #validating mandotory fields
        if( !strlen( $phoneNumber ) || !strlen( $message ))
        {
            $this->log($phoneNumber, $message, $orderID, "Canceled. Either phone number or message body not set.", false);
            return FALSE;
        }

        if (strpos($phoneNumber, '+') === 0)
        {
            $phoneNumber = substr($phoneNumber, 1);
        }

        if (strpos($phoneNumber, '00') === 0)
        {
            $phoneNumber = substr($phoneNumber, 2);
        }

        if (strlen($phoneNumber) == 8)
            $phoneNumber = "371" . $phoneNumber;

        //$phoneNumber = "+" . $phoneNumber;

        //print "$phoneNumber\n\n";


        if (strlen($phoneNumber) != 11)
        {
            $this->log($phoneNumber, $message, $orderID, "Canceled. Incorrect phone number.", false);
            return FALSE;
        }

        $error_messages = array (
            '-1' => 'Send error.',
            '-2' => 'Not enough credits.',
            '-3' => 'Network not covered.',
            '-4' => 'Socket exception.',
            '-5' => 'Invalid user or pass.',
            '-6' => 'Missing designation address.',
            '-7' => 'Missing SMS text.',
            '-8' => 'Missing sender name.',
            '-9' => 'Destination address is in invalid format.',
            '-10' => 'Missing username.',
            '-11' => 'Missing password.',
            '-13' => 'Invalid destination address.'
        );



        $username = $this->config['user'];
        $password = $this->config['password'];
        $sender = urlencode($this->config['from']);
        $message_encoded = urlencode($message);
        $flash = "0";
        $gsmnumbers = "<gsm>$phoneNumber</gsm>";
        $type = "longSMS";
        $bookmark = "";

        $message_encoded = urlencode( $message );
        #concatenating URL
        $url = $this->config[ 'request_url' ] .
               '?user=' . $username .
               '&password=' . $password .
               '&sender=' . $sender .
               '&SMSText=' . $message_encoded .
               '&IsFlash=' . $flash .
               '&Type=' . $type .
               '&GSM=' . $phoneNumber;

        //print "$url\n\n";


        $fp = @fopen( $url, 'r' );
        $result = array();
        if (!$fp)$fp = @fopen( $url, 'r' );
        if (!$fp)$fp = @fopen( $url, 'r' );
        if ($fp)
        {
            //~ print 'OK';
            while (!feof($fp)) {
                $result[] = fgets($fp, 4096);
            }
            $rez = join( "\n", $result);

            if (array_key_exists($rez, $error_messages))
                $rez = $error_messages[$rez];

            $this->log($phoneNumber, $message, $orderID, $rez, true);
            //print $rez;
            return $rez;
        }
        else
        {
            //~ print 'BAD!';
            $this->log($phoneNumber, $message, $orderID, "Could not open stream. Message not sent.", false);
            return FALSE;
        }


        //$xmldata = "<SMS><authentification><username>" . $username . "</username><password>" . $password . "</password></authentification><message><sender>" . $sender . "</sender><text>" . $message . "</text><flash>" . $flash . "</flash><type>" . $type . "</type><bookmark>" . $bookmark . "</bookmark></message><recipients><gsm>" . $phoneNumber . "</gsm></recipients></SMS>";
        //print htmlspecialchars($xmldata, ENT_QUOTES);
        //$request_data = 'XML=' . $xmldata;

//

//
        //$host = $this->config['host'];
        //$uri = $this->config['uri'];

//
        //$da = fsockopen($host, 80, $errno, $errstr);
        //if (!$da)
        //{
        //$this->log($phoneNumber, $message, $orderID, "Cannot open host: $errstr ($errno)", false);
        //return FALSE;
        //}
        //else {
        //$salida ="POST $uri  HTTP/1.1\r\n";
        //$salida.="Host: $host\r\n";
        //$salida.="User-Agent: PHP Script\r\n";
        //$salida.="Content-Type: text/xml\r\n";
        //$salida.="Content-Length: ".strlen($request_data)."\r\n";
        //$salida.="Connection: close\r\n\r\n";
        //$salida.=$request_data;
        //fwrite($da, $salida);
        //while (!feof($da))
        //$response.=fgets($da, 128);
        //$response=split("\r\n\r\n",$response);
        //$header=$response[0];
        //$responsecontent=$response[1];
        //if(!(strpos($header,"Transfer-Encoding: chunked")===false)){
        //$aux=split("\r\n",$responsecontent);
        //for($i=0;$i<count($aux);$i++)
        //if($i==0 || ($i%2==0))
        //$aux[$i]="";
        //$responsecontent=implode("",$aux);
        //}//if
        //$responsecontent = chop($responsecontent);
        //}//else

        //print htmlspecialchars($responsecontent, ENT_QUOTES);

        //$this->log($phoneNumber, $message, $orderID, $responsecontent, true);
    }


}

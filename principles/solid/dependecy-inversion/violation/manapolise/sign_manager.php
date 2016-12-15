<?php

class AutomaticSignManager
{

    private $isSigned =false;
    private $insurer ='';
    private $orderId = -1;
    private $startDate = null;
    private $endDate = null;

    private $pdfData = '';
    private $ltabNr;
    private $params;
    private $response;

    // XXX: Test car data
    const TEST_CAR_REG_NUM = "KA110";
    const TEST_CAR_CERT_NUM = "AF1620865";
    const TEST_POLICY_NUM = "TST000000";

    public function __construct($orderId, $insurerCode, $parms)
    {
        $this->insurer = $insurerCode;
        $this->params = $params;
        $this->orderId = $orderId;


        //including insurer library


        include_once(realpath( dirname(__FILE__) . '/' . $insurerCode.".php"));

        $this->InitParams();

        echo 'Order: ' .  $orderId . ', insurer: ' . $insurerCode . '. Init complete!' ."\n";

        if (!defined('SITE_DIR')) {
            define('SITE_DIR', dirname(__FILE__).'/..');
        }
    }

    public function Sign()
    {
        $insurer = "SignCompany" . $this->insurer;

        echo 'Calling this sign method:  ' . $insurer ."\n";

        // XXX: Test car handling / do not create policy
        if(strtoupper($this->params['VehicleRegNr']) === self::TEST_CAR_REG_NUM && strtoupper($this->params['VehicleRegCertNr']) === self::TEST_CAR_CERT_NUM) {
            $this->ltabNr = self::TEST_POLICY_NUM;
            $this->SignOrder();
            //$this->createInvoice();
            $this->SendMailToCustomer();

            return 0;
        }

        try{
            $this->$insurer();
        } catch(Exception $e)
        {
            echo "Error processing this order. Exceptions: ". $e;
            $this->AfterUnsuccessfulSign();
        }
    }



    public function GetPolicyData()
    {
        if(file_exists($tempFilePath))
        {
            $handle = fopen($tempFilePath, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            return $contents;
        }
        return "";
    }

    public function GetLTabnr()
    {
        return $ltabNr;
    }


    /* INTERNAL LOGIC */

    private function InitParams()
    {
        $ta = array();
        $dr = Db::Query("select o.*, r.* from _octa as o inner join _orders as r on o.order_id = r.id where r.id= %d", $this->orderId)->getRow();
        $ta['OwnerCode'] = $dr['pk'];
        $ta['OwnerName'] = $dr['name'] .' '. $dr['surname'];
        $ta['OwnerFirstName'] = $dr['name'];
        $ta['OwnerLastName'] = $dr['surname'];
        $ta['OwnerPhone'] = $dr['mobile'];
        $ta['PolicyPremium'] = $dr['real_price'];
        $ta['VehicleRegNr'] = strtoupper($dr['car_number']);
        $ta['VehicleRegCertNr'] = strtoupper($dr['car_pas_number']);
        $ta['PolicyPeriod'] = $dr['periud'];
        $ta['PolicyStartDate'] = $dr['start_date'];
        $ta['broker_id'] = $dr['broker_id'];
        $ta['CompanyName'] = $dr['company'];
        $ta['CompanyRegNum'] = $dr['registered_number'];

        // XXX: Params for BalticMiles
        if (isset($dr['baltic_miles_nr']) && $dr['baltic_miles_nr'] != '') {
            $ta['bm_price'] = $dr['price'];
            $ta['bm_number'] = $dr['baltic_miles_nr'];
        }

        $this->startDate = date('d.m.Y',strtotime($dr['start_date']));
        $this->endDate = date('d.m.Y',strtotime($dr['end_date']));

        $ta['OwnerEmail'] = $dr['email'];

        $ta['PolicyNr'] = Db::Query("select CONCAT( 'MP-', date_format(create_date, '%y%m'), '-', user_id, '-', number ) as PoliseNr from _orders where id = %s", $this->orderId)->getOne();
        $ta['DraftId'] = $dr['draft_id'];
        $ta['DraftExpirationDate'] = $dr['draft_expiration_date'];

        $this->params = $ta;
    }


    /* Baltikums signup process */
    private function SignCompanyBaltikums()
    {

        echo 'Executing Baltikums sign process: '  ."\n";

        $proxy = new BaltikumsWsProxy();

        $pdf = '';
        $extId = -1;


        echo 'Creating draft' ."\n";


        $res = '';
        try
        {
            $extId = $proxy->CreateOCTASCPolicyDraft($res,
                                                     $this->params['OwnerCode'],
                                                     $this->params['OwnerName'],
                                                     $this->params['OwnerPhone'],
                                                     $this->params['PolicyPremium'],
                                                     $this->params['VehicleRegNr'],
                                                     $this->params['VehicleRegCertNr'],
                                                     $this->params['PolicyPeriod'],
                                                     strtotime($this->params['PolicyStartDate']),
                                                     $this->orderId);
            echo "extId: $extId\n";
            $this->response = $res;
        }
        catch(Exception $e)
        {
            $this->response = $e->getMessage();
        }

        if(strlen($extId) > 0)
        {
            echo 'Draft successfuly created with extId: ' . $extId ."\n";
            $this->ltabNr = $proxy->CreateOCTASCPolicyFromDraft($extId, $pdf, $this->orderId);
        }

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000)
        {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr ."\n";

            $this->AfterSuccessfulSign();
        }
        else
        {
            $this->AfterUnsuccessfulSign();
        }


    }

    /* Balta signup process */
    private function SignCompanyBalta()
    {

        echo 'Executing Balta sign process: '  ."\n";

        $proxy = new BaltaWsProxy();

        $pdf = '';
        $extId = -1;


        echo 'Creating draft' ."\n";


        $res = '';
        try
        {
            if (isset($this->params['DraftId']) && isset($this->params['DraftExpirationDate']) && strtotime($this->params['DraftExpirationDate']) > time())
            {
                $extId = $this->params['DraftId'];
                print "Got already generated draftId(". $extId .") in order " . $this->orderId;
            }
            else {
                $extId = $proxy->CreateOCTASCPolicyDraft($res,
                                                         $this->params['OwnerCode'],
                                                         $this->params['OwnerName'],
                                                         $this->params['OwnerPhone'],
                                                         $this->params['PolicyPremium'],
                                                         $this->params['VehicleRegNr'],
                                                         $this->params['VehicleRegCertNr'],
                                                         $this->params['PolicyPeriod'],
                                                         $this->params['PolicyStartDate'],
                                                         $this->orderId,
                                                         true,
                                                         $this->params['CompanyName'],
                                                         $this->params['CompanyRegNum']);
                echo "extId: $extId\n";
                $this->response = $res;
                print "saving result:\n";
                print_r($res);
            }
        }
        catch(Exception $e)
        {
            print "exception\n";
            $this->response = $e->getMessage();
            print_r($e->getMessage());
            $extId = '';
        }

        if(strlen($extId) > 0)
        {
            echo 'Draft successfuly created with extId: ' . $extId ."\n";
            $this->ltabNr = $proxy->CreateOCTASCPolicyFromDraft($extId, $res, $pdf, $this->orderId, $this->params['PolicyStartDate']);
            $this->response = $res;
        }

        print "ltabNr: ".$this->ltabNr."\n";
        print "ltabNr length: ".strlen($this->ltabNr)."\n";
        print "pdf length: ".strlen($pdf)."\n";

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000)
        {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr ."\n";

            $this->AfterSuccessfulSign();
        }
        else
        {
            $this->AfterUnsuccessfulSign();
        }
    }

    /* Ergo signup process */
    private function SignCompanyErgo()
    {

        echo 'Executing Ergo sign process: '  ."\n";

        $proxy = new ErgoWsProxy();

        $pdf = '';
        $extId = -1;


        echo 'Creating draft' ."\n";

        $PolicyStartDate = strtotime($this->params['PolicyStartDate']);

        $today_arr = getdate(time());
        $today = mktime(0, 0, 0, (int)$today_arr['mon'], (int)$today_arr['mday'], (int)$today_arr['year']);
        $tomorrow = $today + 24 * 60 * 60;

        if ($PolicyStartDate < time())
        {
            //return;
            $PolicyStartDate = time() + 5 * 60; //$tomorrow;
        }


        $res = '';
        try
        {
            $extId = $proxy->CreateOCTASCPolicyDraft($res,
                                                     $this->params['OwnerCode'],
                                                     $this->params['OwnerName'],
                                                     $this->params['OwnerFirstName'],
                                                     $this->params['OwnerLastName'],
                                                     $this->params['OwnerPhone'],
                                                     $this->params['PolicyPremium'],
                                                     $this->params['VehicleRegNr'],
                                                     $this->params['VehicleRegCertNr'],
                                                     $this->params['PolicyPeriod'],
                                                     $PolicyStartDate,
                                                     $this->orderId);
            echo "extId: $extId\n";
            $this->response = $res;
        }
        catch(Exception $e)
        {
            $this->response = $e->getMessage();
        }

        if($extId > 0)
        {
            echo 'Draft successfuly created with extId: ' . $extId ."\n";
            try {
                $this->ltabNr = $proxy->CreateOCTASCPolicyFromDraft($res, $extId, $pdf, $this->orderId);
                $this->response = $res;
            }
            catch(Exception $e) {
                $this->response = $e->getMessage();
            }
            print "ltabNr: $this->ltabNr\n";
            print "pdf length: " . strlen($pdf) . "\n";
        }

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4)
        {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr ."\n";

            $this->AfterSuccessfulSign();
        }
        else
        {
            $this->AfterUnsuccessfulSign();
        }


    }

    /* Balva signup process */
    private function SignCompanyBalva()
    {

        echo 'Executing Balva sign process: '  ."\n";

        $proxy = new BalvaWsProxy();

        $pdf = '';
        $extId = -1;


        echo 'Creating draft' ."\n";


        $res = '';
        try
        {
            $extId = $proxy->CreateOCTASCPolicyDraft($res,
                                                     $this->params['OwnerCode'],
                                                     $this->params['OwnerName'],
                                                     $this->params['OwnerFirstName'],
                                                     $this->params['OwnerLastName'],
                                                     $this->params['OwnerPhone'],
                                                     $this->params['PolicyPremium'],
                                                     $this->params['VehicleRegNr'],
                                                     $this->params['VehicleRegCertNr'],
                                                     $this->params['PolicyPeriod'],
                                                     strtotime($this->params['PolicyStartDate']),
                                                     $this->orderId);
            echo "extId: $extId\n";
            $this->response = $res;
        }
        catch(Exception $e)
        {
            $this->response = $e->getMessage();
        }

        if($extId > 0)
        {
            echo 'Draft successfuly created with extId: ' . $extId ."\n";
            $this->ltabNr = $proxy->CreateOCTASCPolicyFromDraft($res, $extId, $pdf, $this->orderId, strtotime($this->params['PolicyStartDate']));
            $this->response = $res;
            print "ltabNr: $this->ltabNr\n";
            print "pdf length: " . strlen($pdf) . "\n";
        }

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4)
        {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr ."\n";

            $this->AfterSuccessfulSign();
        }
        else
        {
            $this->AfterUnsuccessfulSign();
        }


    }

    /* seesam signup process */
    private function SignCompanySeesam()
    {

        echo 'Executing seesam sign process: '  ."\n";

        $proxy = new SeesamWsProxy();

        $pdf = '';
        $extId = -1;


        echo 'Creating draft' ."\n";

        $PolicyStartDate = strtotime($this->params['PolicyStartDate']);

        $today_arr = getdate(time());
        $today = mktime(0, 0, 0, (int)$today_arr['mon'], (int)$today_arr['mday'], (int)$today_arr['year']);
        $tomorrow = $today + 24 * 60 * 60;

        if ($PolicyStartDate < time())
        {
            //return;
            $PolicyStartDate = time() + 5 * 60; //$tomorrow;
        }

        $res = '';
        try
        {
            $extId = $proxy->CreateOCTASCPolicyDraft($res,
                                                     $this->params['OwnerCode'],
                                                     $this->params['OwnerFirstName'],
                                                     $this->params['OwnerLastName'],
                                                     $this->params['OwnerPhone'],
                                                     $this->params['OwnerEmail'],
                                                     $this->params['PolicyPremium'],
                                                     $this->params['VehicleRegNr'],
                                                     $this->params['VehicleRegCertNr'],
                                                     $this->params['PolicyPeriod'],
                                                     $PolicyStartDate,
                                                     $this->orderId);
            echo "extId: $extId\n";
            $this->response = $res;
        }
        catch(Exception $e)
        {
            $this->response = $e->getMessage();
        }

        if($extId > 0)
        {
            echo 'Draft successfuly created with extId: ' . $extId ."\n";
            $this->ltabNr = $proxy->CreateOCTASCPolicyFromDraft($res, $extId, $pdf, $this->orderId);
            $this->response = $res;
            print "ltabNr: $this->ltabNr\n";
            print "pdf length: " . strlen($pdf) . "\n";
        }

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4)
        {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr ."\n";

            $this->AfterSuccessfulSign();
        }
        else
        {
            $this->AfterUnsuccessfulSign();
        }


    }


    /* seesamgold signup process */
    private function SignCompanySeesamgold()
    {

        echo 'Executing seesamgold ZELTA OCTA sign process: '  ."\n";

        $proxy = new SeesamgoldWsProxy();

        $pdf = '';
        $extId = -1;


        echo 'Creating draft' ."\n";

        $PolicyStartDate = strtotime($this->params['PolicyStartDate']);

        $today_arr = getdate(time());
        $today = mktime(0, 0, 0, (int)$today_arr['mon'], (int)$today_arr['mday'], (int)$today_arr['year']);
        $tomorrow = $today + 24 * 60 * 60;

        if ($PolicyStartDate < time())
        {
            //return;
            $PolicyStartDate = time() + 5 * 60; //$tomorrow;
        }

        $res = '';
        try
        {
            $extId = $proxy->CreateOCTASCPolicyDraft($res,
                                                     $this->params['OwnerCode'],
                                                     $this->params['OwnerFirstName'],
                                                     $this->params['OwnerLastName'],
                                                     $this->params['OwnerPhone'],
                                                     $this->params['OwnerEmail'],
                                                     $this->params['PolicyPremium'],
                                                     $this->params['VehicleRegNr'],
                                                     $this->params['VehicleRegCertNr'],
                                                     $this->params['PolicyPeriod'],
                                                     $PolicyStartDate,
                                                     $this->orderId);
            echo "extId: $extId\n";
            $this->response = $res;
        }
        catch(Exception $e)
        {
            $this->response = $e->getMessage();
        }

        if($extId > 0)
        {
            echo 'Draft successfuly created with extId: ' . $extId ."\n";
            $this->ltabNr = $proxy->CreateOCTASCPolicyFromDraft($res, $extId, $pdf, $this->orderId);
            $this->response = $res;
            print "ltabNr: $this->ltabNr\n";
            print "pdf length: " . strlen($pdf) . "\n";
        }

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4)
        {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr ."\n";

            $this->AfterSuccessfulSign();
        }
        else
        {
            $this->AfterUnsuccessfulSign();
        }


    }

    /* Gjensidige signup process */
    private function SignCompanyGjensidige() {
        $proxy = new GjensidigeWsProxy();

        $pdf = "";
        $res = "";

        if (isset($this->params['DraftId'])) { // && isset($this->params['DraftExpirationDate']) && strtotime($this->params['DraftExpirationDate']) > time()
            // Draft already exist
            $draft = $this->params['DraftId'];
        }
        else {
            $draft = $proxy->CreateOCTAPolicyDraft($res,
                                                   $this->params['OwnerCode'],
                                                   $this->params['OwnerFirstName'] . ' ' . $this->params['OwnerLastName'],
                                                   $this->params['OwnerFirstName'],
                                                   $this->params['OwnerLastName'],
                                                   $this->params['OwnerPhone'],
                                                   $this->params['OwnerEmail'],
                                                   $this->params['PolicyPremium'],
                                                   $this->params['VehicleRegNr'],
                                                   $this->params['VehicleRegCertNr'],
                                                   $this->params['PolicyPeriod'],
                                                   $this->params['PolicyStartDate'],
                                                   $this->orderId);
        }

        if (isset($draft['draft_id']) && isset($draft['draft_number'])) {
            $this->ltabNr = $proxy->CreateOCTAPolicyFromDraft($res, $draft['draft_id'], $draft['draft_number'], $this->orderId);
        }

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4) {
            $pdf = $proxy->GetPolicyPrintout($this->ltabNr, $res, $this->orderId);

            $this->ltabNr = preg_replace('/[^A-Za-z0-9]/', '', $this->ltabNr);
        }

        $this->response = $res;

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000) {
            $this->isSigned = true;
            $this->pdfData = $pdf;

            $this->AfterSuccessfulSign();
        }
        else {
            $this->AfterUnsuccessfulSign();
        }
    }

    private function AfterSuccessfulSign()
    {

        $this->AttachPdfToOrder($this->insurerCode. '_' . $this->params['VehicleRegNr'] . $this->orderId . '_' );
        $this->SignOrder();
        /*
        try{
          $this->createInvoice();
        }
        catch(Exception $e) {}
        */
        $this->SendMailToCustomer();
        $this->SendMailToAdmin();

        // XXX: Accrual BalticMiles award
        if(isset($this->params['bm_number']) && $this->params['bm_number'] != '') {
            $balticmiles = new ManaPoliseBalticMiles();
            $balticmiles->accrualAward($this->params['bm_number'], 'octa', $this->params['bm_price'], $this->orderId);
        }
    }


    private function SignOrder()
    {
        echo 'Signing order' ."\n";
        $sign_date = date("Y-m-d H:i:s");
        if($this->checkPolicyEndDate($sign_date) === false) {
            Db::query("update _orders set AssistentId = 18, status = 4, polise_nr = %s, signed_date = %s where id=%d", $this->ltabNr, $sign_date, $this->orderId);
        }
        else {
            $period = $this->params['PolicyPeriod'];
            $new_end_date = date("Y-m-d H:i:s", strtotime("+$period months -1 day", strtotime($this->startDate))); // -1 day
            $this->endDate = date("d.m.Y", strtotime("+$period months -1 day", strtotime($this->startDate))); // -1 day;
            Db::query("update _orders set AssistentId = 18, status = 4, polise_nr = %s, signed_date = %s, end_date = %s where id=%d", $this->ltabNr, $sign_date, $new_end_date, $this->orderId);
        }
    }

    private function AttachPdfToOrder($fileName)
    {
        echo 'Attaching pdf to order' ."\n";

        if (is_array($this->pdfData))
        {
            foreach($this->pdfData as $k => $v)
            {
                $this->attachFile($fileName."$k", $v, "_$k");
            }
        }
        else
        {
            $this->attachFile($fileName, $this->pdfData);
        }
    }

    private function attachFile($fileName, $pdfData, $num = '')
    {
        $myFile = dirname(__FILE__) .'/..' . '/uploads/files/' . $fileName . '.pdf';
        $fh = fopen($myFile, 'w+') or die("can't open file " . $myFile);
        fwrite($fh, $pdfData);
        print "pdfdata: ";
        print_r($pdfData);
        fclose($fh);
        Db::Query("insert into _files_orders set ParentId = %s, name = %s, file = %s", $this->orderId, $this->ltabNr . $num .'.pdf', $fileName.'.pdf');
    }

    private function SendMailToCustomer()
    {
        echo 'Sending mail to customer' ."\n";
        $language = Alpha_Cms_Settings::getDefaultLanguage();
        $replaces = array(
            'name' => $this->params['OwnerName'],
            'surname' => '',
            'polise' => $this->ltabNr,
            'id' => $this->params['PolicyNr'],
            'valid_from' => $this->startDate,
            'valid' =>$this->endDate
        );

        $template = 'ready_to_sign';


        $mailer = new ManaPoliseMail();

        if ($this->params['broker_id'] == 29)
        {
            $mailer->setFrom('autonams@autonams.lv');
            $mailer->setFromName('autonams');
            $template = $template . '_29';
        }

        $mailer
            ->addAddress($this->params['OwnerEmail'])
            ->setTemplate($template, $replaces, $language);


        $files = Db::query('SELECT * FROM _files_orders WHERE parentID = %d', $this->orderId )->getAll();
        foreach($files as $file) {
            //if ($file['filetype'] != 'invoice')
            if ($file['name'] != 'invoice_' . $orderData['id'] . '.pdf')
                $mailer->addAttachment(dirname(__FILE__) .'/..' . '/uploads/files/' . $file['file'], $file['name']);
        }

        // Send email
        $mailer->send();
        // Clear variables
        $mailer->cleanup();


        $i18n = Controller::getI18n();
        $ready_to_sign = 'sms.ready_to_sign';
        if ($this->params['broker_id'] == '29')
            $ready_to_sign .= '_29';
        $text = $i18n->translate($ready_to_sign);
        $mobile = Db::query("select mobile from _orders where id = %d", $this->orderId)->getOne();

        $ds = date_create_from_format('d.m.Y', $this->startDate);
        $de = date_create_from_format('d.m.Y', $this->endDate);
        $text = str_replace(
            array(
                '{name}',
                '{surname}',
                '{polise}',
                '{valid_from}',
                '{valid_till}',
                '{code}',
                '{car_number}'
            ),
            array(
                $this->params['OwnerName'],
                '',
                $this->ltabNr,
                date_format($ds, 'd.m.y'),
                date_format($de, 'd.m.y'),
                $policyCode,
                $this->params['VehicleRegNr']),
            $text
        );
        print_r('sending sms:'.$mobile . '-' . $text );
        $sms = new ManaPoliseSMS();
        $sms->sendMessage($mobile,  $text, $this->orderId);

    }

    private function SendMailToAdmin()
    {
        echo 'Signing mail to admin' ."\n";
    }

    private function AfterUnsuccessfulSign()
    {
        Db::query("update _orders set AssistentId = 18, status = -10, comment = CONCAT(IFNULL(comment, ''), '\n', IFNULL(%s, '')) where id = %d",  $this->response, $this->orderId);
        echo 'Order status set ERROR' ."\n";
    }

    private function Debug()
    {
        return "";
    }

    private function createInvoice() {
        try {
            $guid = $this->params['guid'];

            $order_data = Db::query("SELECT o.*, ic.octa_auto, oc.car_number as carNr FROM _orders o
			  LEFT JOIN _insurance_company ic ON o.company_id = ic.id
			  LEFT JOIN _octa oc ON o.id = oc.order_id
			  WHERE oc.guid=%s", $guid)->getRow();

            if($order_data['status'] == 2) return false;

            $exist = Db::query("SELECT count(*) FROM _files_orders WHERE filetype=%s AND parentID=%d", 'invoice', $order_data['id'])->getOne();
            if($exist) return false;

            $file_name = md5($order_data['id']+time());

            $rootdir = dirname(__FILE__) . '/..';

            require_once $rootdir . '/alpha/core/lib/tcpdf/config/lang/eng.php';
            require_once $rootdir . '/alpha/core/lib/tcpdf/tcpdf.php';

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setLanguageArray($l);
            $pdf->SetFont('dejavusans', '', 8);
            $pdf->AddPage();

            $fp = @fopen('https://' . $_SERVER['HTTP_HOST'] . '/payment?success=true&language=lv&order_id=' . $guid . '&ispopup=1', 'r');

            $result = array();
            if($fp) {
                while(!feof($fp)) {
                    $result[] = fgets($fp, 4096);
                }

                $filename = $rootdir . '/uploads/files/invoice_' . $file_name . '.pdf';

                $rez = join("\n", $result);
                $pdf->writeHTML($rez, true, 0, true, 0);
                //~ $pdf->Output('C:/example_006.pdf', 'F');
                $pdf->Output($filename, 'F');

                DB::query("INSERT INTO _files_orders SET parentID=%d, name=%s, file=%s, filetype=%s",
                          $order_data['id'],
                          'Rekins_OCTA_' . $order_data['carNr'] . '.pdf',
                          'invoice_' . $file_name . '.pdf',
                          'invoice');
            }
        }
        catch(Exception $e) {}
    }

    /**
     * Check if policy end date fix is needed
     * @param $signDate
     * @return bool
     */
    private function checkPolicyEndDate($signDate) {
        $signYmd = date('Ymd', strtotime($signDate));
        $startYmd = date('Ymd', strtotime($this->startDate));
        if($signYmd != $startYmd || ($signYmd == $startYmd && (int)date('H', strtotime($signDate)) < 12)) {
            return true;
        }
        return false;
    }

    /**
     * Compensa signup process
     */
    private function SignCompanyCompensa() {
        echo 'Executing Compensa sign process: ' . "\n";

        $proxy = new CompensaWsProxy();

        $pdf = "";
        $extId = "";
        $res = "";

        $extId = $proxy->CreateOCTASCPolicyDraft($res,
                                                 $this->params['OwnerCode'],
                                                 $this->params['OwnerName'],
                                                 $this->params['OwnerPhone'],
                                                 $this->params['PolicyPremium'],
                                                 $this->params['VehicleRegNr'],
                                                 $this->params['VehicleRegCertNr'],
                                                 $this->params['PolicyPeriod'],
                                                 $this->params['PolicyStartDate'],
                                                 $this->orderId);

        if(strlen($extId) > 0) {
            echo 'Draft successfuly created with extId: ' . $extId ."\n";
            $this->ltabNr = $proxy->CreateOCTASCPolicyFromDraft($extId, $res, $pdf, $this->orderId);
        }

        $this->response = $res;

        print "ltabNr: " . $this->ltabNr . "\n";
        print "ltabNr length: " . strlen($this->ltabNr) . "\n";
        print "pdf length: " . strlen($pdf) . "\n";

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000) {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr . "\n";

            $this->AfterSuccessfulSign();
        }
        else {
            $this->AfterUnsuccessfulSign();
        }
    }

    /**
     * PZU signup process
     */
    private function SignCompanyPZU() {
        echo 'Executing PZU sign process: ' . "\n";

        $proxy = new PZUWsProxy();

        $pdf = $res = "";

        $this->ltabNr = $proxy->IssuePolicy(
            $res,
            $pdf,
            $this->params['PolicyPremium'],
            $this->params['VehicleRegNr'],
            $this->params['VehicleRegCertNr'],
            $this->params['PolicyPeriod'],
            $this->params['PolicyStartDate'],
            $this->orderId
        );

        $this->response = $res;

        print "ltabNr: " . $this->ltabNr . "\n";
        print "ltabNr length: " . strlen($this->ltabNr) . "\n";
        print "pdf length: " . strlen($pdf) . "\n";

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000) {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr . "\n";

            $this->AfterSuccessfulSign();
        }
        else {
            $this->AfterUnsuccessfulSign();
        }
    }

    /**
     * BAN signup process
     */
    private function SignCompanyBan() {
        echo 'Executing BAN sign process: ' . "\n";

        $proxy = new BANWsProxy();

        $pdf = $res = "";

        $isCompany = (isset($this->params['CompanyName']) && !empty($this->params['CompanyName']));

        $this->ltabNr = $proxy->IssuePolicy(
            $res,
            $pdf,
            $this->params['PolicyPremium'],
            $this->params['VehicleRegNr'],
            $this->params['VehicleRegCertNr'],
            $this->params['PolicyPeriod'],
            $this->params['PolicyStartDate'],
            $this->orderId,
            ($isCompany ? $this->params['CompanyName'] : $this->params['OwnerName']),
            ($isCompany ? $this->params['CompanyRegNum'] : $this->params['OwnerCode']),
            $isCompany
        );

        $this->response = $res;

        print "ltabNr: " . $this->ltabNr . "\n";
        print "ltabNr length: " . strlen($this->ltabNr) . "\n";
        print "pdf length: " . strlen($pdf) . "\n";

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000) {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr . "\n";

            $this->AfterSuccessfulSign();
        }
        else {
            $this->AfterUnsuccessfulSign();
        }
    }

    /**
     * IF signup process
     */
    private function SignCompanyIF() {
        echo 'Executing IF sign process: ' . "\n";

        $proxy = new IFWsProxy();

        $pdf = "";
        $res = "";

        if (isset($this->params['DraftId']) && isset($this->params['DraftExpirationDate']) && strtotime($this->params['DraftExpirationDate']) > time()) {
            $response = '';
            $this->ltabNr = $proxy->IssuePolicyFromDraft($response, $this->params['DraftId'], $this->orderId);
        }
        else {
            $this->ltabNr = $proxy->IssuePolicy($res,
                                                $this->params['OwnerCode'],
                                                $this->params['OwnerFirstName'],
                                                $this->params['OwnerLastName'],
                                                $this->params['OwnerPhone'],
                                                $this->params['PolicyPremium'],
                                                $this->params['VehicleRegNr'],
                                                $this->params['VehicleRegCertNr'],
                                                $this->params['PolicyPeriod'],
                                                $this->params['PolicyStartDate'],
                                                $this->orderId);
        }

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4) {
            echo 'Policy successfuly issued with LTAB nr.: ' . $this->ltabNr ."\n";
            $pdf = $proxy->GetPolicyPrintout($this->ltabNr, $res, $this->orderId);

            $this->ltabNr = substr($this->ltabNr, strpos($this->ltabNr, '/'));
            $this->ltabNr = preg_replace('/[^A-Za-z0-9]/', '', $this->ltabNr);
        }

        $this->response = $res;

        print "ltabNr: " . $this->ltabNr . "\n";
        print "ltabNr length: " . strlen($this->ltabNr) . "\n";
        print "pdf length: " . strlen($pdf) . "\n";

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000) {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr . "\n";

            $this->AfterSuccessfulSign();
        }
        else {
            $this->AfterUnsuccessfulSign();
        }
    }

    /**
     * IFPlus signup process
     */
    private function SignCompanyIFPlus() {
        echo 'Executing IFPlus sign process: ' . "\n";

        $proxy = new IFPlusWsProxy();

        $pdf = "";
        $res = "";

        $this->ltabNr = $proxy->IssuePolicy($res,
                                            $this->params['OwnerCode'],
                                            $this->params['OwnerFirstName'],
                                            $this->params['OwnerLastName'],
                                            $this->params['OwnerPhone'],
                                            $this->params['PolicyPremium'],
                                            $this->params['VehicleRegNr'],
                                            $this->params['VehicleRegCertNr'],
                                            $this->params['PolicyPeriod'],
                                            $this->params['PolicyStartDate'],
                                            $this->orderId);

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4) {
            echo 'Policy successfuly issued with LTAB nr.: ' . $this->ltabNr ."\n";
            $pdf = $proxy->GetPolicyPrintout($this->ltabNr, $res, $this->orderId);

            $this->ltabNr = substr($this->ltabNr, strpos($this->ltabNr, '/'));
            $this->ltabNr = preg_replace('/[^A-Za-z0-9]/', '', $this->ltabNr);
        }

        $this->response = $res;

        print "ltabNr: " . $this->ltabNr . "\n";
        print "ltabNr length: " . strlen($this->ltabNr) . "\n";
        print "pdf length: " . strlen($pdf) . "\n";

        if(isset($this->ltabNr) && strlen($this->ltabNr) > 4 && strlen($pdf) > 1000) {
            $this->isSigned = true;
            $this->pdfData = $pdf;
            echo 'Sign successful! LTABNr: ' . $this->ltabNr . "\n";

            $this->AfterSuccessfulSign();
        }
        else {
            $this->AfterUnsuccessfulSign();
        }
    }
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');

class Instanpayapi {
	protected $_ci;
	private $ipchk;
    private $client;
    private $forward;
    private $remote;
    private $sessionid;
    private $counting = 0;
    private $Url1;
    private $Url2;
    private $token;
    private $cappingamount;
    public function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model('Inst_model');
        $this->_ci->load->model('Main_model');
        $this->_ci->load->helper('custom');
        $this->_ci->load->library('user_agent');
        $this->_ci->load->config('apb_config');
        $this->token = 'e0c9e38d9219020ecc261d23701748b8';
        $this->client = @$_SERVER['HTTP_CLIENT_IP'];
        $this->forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $this->remote = $_SERVER['REMOTE_ADDR'];
        if (filter_var($this->client, FILTER_VALIDATE_IP)) {

            $this->ipchk = $this->client;

        } else if (filter_var($this->forward, FILTER_VALIDATE_IP)) {

            $this->ipchk = $this->forward;

        } else {

            $this->ipchk = $this->remote;
        }
    }


    function start_aeps_action($user_info,$params,$chkservicestat)
    {
        $data=array();
        $params['fastpay_req_type']=@$params['fastpay_req_type'];
        
          if ($chkservicestat['gateway_down'] == 0)
          {
                if($params['fastpay_req_type']=="OPENAEPSWHITELABEL")
                {
                    $data=$this->open_aeps_whitelable($user_info,$params,$chkservicestat);

                }
                else{
                    $data['error']=1;
                    $data['error_desc']='This particular request is not supported, contact helpdesk';
                    $data['msg']=null;
                }
              
          }else{
            $data['error'] = 1;
            $data['error_desc'] = "Service Provider Down, Try again later";
            $data['msg'] = null;
          }
        
        return $data;
    }
    
    
    private function open_aeps_whitelable($user_info,$params,$chkservicestat)
    {
        
              $data=array();
              $this->_ci->config->item('aeps_config')['APP_ID']=@$this->_ci->config->item('aeps_config')['APP_ID'];
        
              $parameterList = array();
              $parameterList["URL"] ="https://aeps.trulyindia.co.in/";
              $parameterList["ALLOWED_SERVICES"] ="WAP,BAP,SAP";
              $parameterList["APP_ID"] =$this->_ci->config->item('aeps_config')['APP_ID'];
              $parameterList["BLOCKED_SERVICES"]  ="";
              $parameterList["PAN"] =$user_info['pan'];

              $checkSumString = "";

              foreach($parameterList as $key => $val){
                  if($checkSumString == ""){

                      $checkSumString .= $key.":".$val;

                  }else{

                      $checkSumString .= "|".$key.":".$val;
                  }
              }

              $key = @$this->_ci->config->item('aeps_config')['APP_TOKEN'];

              $plaintext = $checkSumString;
              $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
              $iv = "1234567812345678";
              $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
              $hmac = hash_hmac("sha256", $ciphertext_raw, $key, $as_binary=true);
              $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

              $parameterList["CHECKSUMHASH"] = $ciphertext;

              if($parameterList){
                        $data['error'] = 0;
                        $data['error_desc'] = null;
                        $data['url']=$parameterList['URL'];
                        unset($parameterList['URL']);
                        $data['msg'] = $parameterList;
  
                }else{

                $data['error'] = 1;
                $data['error_desc'] = 'Unable to process data';
                $data['msg'] = null;
                
                }
        
        return $data;
    }
    
    function process_aeps_callbacks($params,$chkservicestat)
    {
        $data=array();
        $params['fastpay_req_type']=@$params['fastpay_req_type'];
        
        if ($chkservicestat['gateway_down'] == 0)
        {
            
            if($params['fastpay_req_type']=="AEPSWALLETBALANCE")
            {
                $data=$this->return_user_wallet_bal($params,$chkservicestat);
            }
            else if($params['fastpay_req_type']=="AEPSCONFIRMSERVICEACTION" || $params['fastpay_req_type']=="AEPSPROCESSTRANSACTION")
            {
                $data=$this->aeps_service_action_confirmation($params,$chkservicestat);
            }
            else{
                $data['response_code'] = 'ERR';
                $data['response_msg'] = 'This particular request is not supported, contact helpdesk';
                $data['transactions'] = array();
            }
            
        }else{
            $data['response_code'] = 'ERR';
            $data['response_msg'] = 'Service Provider Down, Try again later';
            $data['transactions'] = array();
        }
        return $data;

    }
    
    
    private function return_user_wallet_bal($params,$chkservicestat)
    {
        $data=array();
        if($params['fastpay_req_type']=="AEPSWALLETBALANCE")
        {
            
            unset($params['fastpay_req_type']);
            if (isset($params['outlet_pan'])) 
            {
                
                $find_users_bypan=$this->_ci->Inst_model->find_retailers_bypan($params['outlet_pan']);
                
                if($find_users_bypan)
                {
                    
                    if(count($find_users_bypan)==1)
                    {
                     
                        if($find_users_bypan[0]['is_active']==1)
                        {
                            
                            if($find_users_bypan[0]['is_block']==0)
                            {
                                
                            $data['response_code'] = 'TXN';
                            $data['response_msg'] = 'Transaction Successfull';
                            $data['transactions'][0]['balance'] = $find_users_bypan[0]['rupee_balance'];
                                
                            }else{
                                $data['response_code'] = 'ERR';
                                $data['response_msg'] = 'Account blocked';
                                $data['transactions'] = array();
                            }
                            
                        }else{
                            $data['response_code'] = 'ERR';
                            $data['response_msg'] = 'Account inactive';
                            $data['transactions'] = array();
                        }
                        
                    }else{
                        $data['response_code'] = 'ERR';
                        $data['response_msg'] = 'Error, Multiple users with same pan';
                        $data['transactions'] = array();
                    }
                    
                }else{
                    $data['response_code'] = 'ERR';
                    $data['response_msg'] = 'Unable to find user details';
                    $data['transactions'] = array();
                }
                
            }else{
                $data['response_code'] = 'ERR';
                $data['response_msg'] = 'Unable to find user details';
                $data['transactions'] = array();
            }
            
        }else{
            $data['response_code'] = 'ERR';
            $data['response_msg'] = 'This particular request is not supported, contact helpdesk';
            $data['transactions'] = array();
        }
        return $data;
    }
    
    private function aeps_service_action_confirmation($params,$chkservicestat)
    {
        $data=array();
        if($params['fastpay_req_type']=="AEPSCONFIRMSERVICEACTION" || $params['fastpay_req_type']=="AEPSPROCESSTRANSACTION")
        {
            
            if (isset($params['outlet_pan'])) 
            {
                
                $find_users_bypan=$this->_ci->Inst_model->find_retailers_bypan($params['outlet_pan']);
                
                if($find_users_bypan)
                {
                    
                    if(count($find_users_bypan)==1)
                    {
                     
                        if($find_users_bypan[0]['is_active']==1)
                        {
                            
                            if($find_users_bypan[0]['is_block']==0)
                            {
                                
                                if($params['fastpay_req_type']=="AEPSPROCESSTRANSACTION")
                                {
                                    
                                    $data=$this->aeps_process_service_transaction($find_users_bypan[0],$chkservicestat,$params);
                                    
                                }else{
                                    
                                      $calculate_margins=$this->calculate_aeps_margins($find_users_bypan[0],$chkservicestat,$params);
                                
                                        if($calculate_margins)
                                        {

                                            $data=$calculate_margins;

                                        }else{
                                            $data['response_code'] = 'ERR';
                                            $data['response_msg'] = 'Unable to find margin details';
                                            $data['transactions'] = array();
                                        }

                                }
                                
                            }else{
                                $data['response_code'] = 'ERR';
                                $data['response_msg'] = 'Account blocked';
                                $data['transactions'] = array();
                            }
                            
                        }else{
                            $data['response_code'] = 'ERR';
                            $data['response_msg'] = 'Account inactive';
                            $data['transactions'] = array();
                        }
                        
                    }else{
                        $data['response_code'] = 'ERR';
                        $data['response_msg'] = 'Error, Multiple users with same pan';
                        $data['transactions'] = array();
                    }
                    
                }else{
                    $data['response_code'] = 'ERR';
                    $data['response_msg'] = 'Unable to find user details';
                    $data['transactions'] = array();
                }
                
            }else{
                $data['response_code'] = 'ERR';
                $data['response_msg'] = 'Unable to find user details';
                $data['transactions'] = array();
            }
            
        }else{
            $data['response_code'] = 'ERR';
            $data['response_msg'] = 'This particular request is not supported, contact helpdesk';
            $data['transactions'] = array();
        }
        return $data;
    }
    
    private function calculate_aeps_margins($userdetails,$chkservicestat,$params)
    {
        $data=array();
        
        $amnt=@$params['transactions'][0]['amount'];
        
        if($chkservicestat['code']=='BCS' || $chkservicestat['code']=='CWS' || $chkservicestat['code']=='MST')
        {

            $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($userdetails['role_id'],$userdetails['plan_id'],$chkservicestat['service_id']);

            if($chck_user_pln_dtl)
            {

                if($chkservicestat['code']=='BCS' || $chkservicestat['code']=='MST')
                {
                    if($chkservicestat['billing_model']=="CHARGE")
                    {
                        
                        $retailer_capping_amount=$chck_user_pln_dtl['capping_amount'];
                        $retailer_charge_type=$chck_user_pln_dtl['charge_type'];
                        $reatiler_charge_method=$chck_user_pln_dtl['charge_method'];
                        $retailer_service_rate=$chck_user_pln_dtl['rate'];
                        
                        if($chck_user_pln_dtl['slab_applicable']==1)
                        {
                            $data['response_code'] = 'ERR';
                            $data['response_msg'] = 'Margins Slab is not allowed in this service';
                            $data['transactions'] = array();
                            
                        }else{
                            
                            
                            if($reatiler_charge_method=="DEBIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array=array();
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=0;
                                    $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                    $retailer_trans_array['app_comm']=0;
                                    $retailer_trans_array['tds']=0;
                                    $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['is_comm']=false;
                                    $retailer_trans_array['charged_amt']=$amnt+$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['txn_amt']=$amnt;
                                    
                                    $data['response_code'] = 'TXN';
                                    $data['response_msg'] = 'Transaction Successfull';
                                    $data['display_params'] = array(
                                    "Amount"=>"&#8377; ".$amnt,
                                    "Charge"=>"&#8377; ".$retailer_trans_array['charged_amt'],
                                    "Description"=>"You will be charged &#8377; ".$retailer_trans_array['charged_amt']
                                    );
                                    $data['margin_description']=$retailer_trans_array;
                                    
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                    $data['response_code'] = 'ERR';
                                    $data['response_msg'] = 'Invalid charge type is configured for your account';
                                    $data['transactions'] = array();
                                }
                                else
                                {
                                    
                                    $data['response_code'] = 'ERR';
                                    $data['response_msg'] = 'Invalid charge type is configured for your account';
                                    $data['transactions'] = array();
                                    
                                }
                                
                            }else{
                                
                                $data['response_code'] = 'ERR';
                                $data['response_msg'] = 'Invalid charge method is configured for your account';
                                $data['transactions'] = array();
                                
                            }
                            
                        }
                        
                    }else{
                        $data['response_code'] = 'ERR';
                        $data['response_msg'] = 'Invalid Commission or Surcharge type is set';
                        $data['transactions'] = array();
                    }
                    
                    
                }else{
                    
                    $check_if_settlement_config_exist=$this->_ci->Inst_model->check_users_settlement_config($userdetails['user_id']);
                    
                    if($check_if_settlement_config_exist)
                    {
                    
                    if($chkservicestat['billing_model']=="P2A")
                    {
                        $retailer_trans_array=array();
                        $retailer_capping_amount=$chck_user_pln_dtl['capping_amount'];
                        $retailer_charge_type=$chck_user_pln_dtl['charge_type'];
                        $reatiler_charge_method=$chck_user_pln_dtl['charge_method'];
                        $retailer_service_rate=$chck_user_pln_dtl['rate'];
                        
                        if($chck_user_pln_dtl['slab_applicable']==1)
                        {
                            $chck_agrd_fee_rng = $this->_ci->Inst_model->check__new_pln_amnt_rng($chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);
                            
                            if($chck_agrd_fee_rng)
                            {
                             
                                $retailer_charge_type=$chck_agrd_fee_rng['charge_type'];
                                $reatiler_charge_method=$chck_agrd_fee_rng['charge_method'];
                                $retailer_service_rate=$chck_agrd_fee_rng['rate'];
                                
                            }else{
                                
                                $data['response_code'] = 'ERR';
                                $data['response_msg'] = 'Slab rate not configured for your account';
                                $data['transactions'] = array();
                                return $data;
                                
                            }
                            
                        }
                        
                        if($reatiler_charge_method=="CREDIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                    $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($userdetails['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    $retailer_trans_array['txn_amt']=$amnt;
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                 
                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amnt)/100),2);
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
         $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_capping_amount:$retailer_trans_array['rate_value'];
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($userdetails['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    $retailer_trans_array['txn_amt']=$amnt;
                                    
                                }
                                else
                                {
                                    
                                    $data['response_code'] = 'ERR';
                                    $data['response_msg'] = 'Invalid charge type is configured for your account';
                                    $data['transactions'] = array();
                                    return $data;
                                    
                                }
                                
                            }else{
                                
                                $data['response_code'] = 'ERR';
                                $data['response_msg'] = 'Invalid charge method is configured for your account';
                                $data['transactions'] = array();
                                return $data;
                            
                            }
                        
                        
                            if(count($retailer_trans_array)>0)
                            {
                                
                                if($retailer_trans_array['is_comm']===true)
                                {
                                    
                                    $data['response_code'] = 'TXN';
                                    $data['response_msg'] = 'Transaction Successfull';
                                    $data['display_params'] = array(
                                    "Withdrawal Amount"=>"&#8377; ".$retailer_trans_array['charged_amt'],
                                    "Commission"=>"&#8377; ".$retailer_trans_array['base_comm'],
                                    "TDS"=>"&#8377; ".$retailer_trans_array['tds'],
                                    "Net Amount"=>"&#8377; ".($retailer_trans_array['charged_amt']+$retailer_trans_array['base_comm']-$retailer_trans_array['tds'])
                                    );
                                    $data['margin_description']=$retailer_trans_array;
                                    
                                }else{
                                    $data['response_code'] = 'ERR';
                                    $data['response_msg'] = 'Margin Configuration Issue';
                                    $data['transactions'] = array();
                                }
                                
                                
                            }else{
                                $data['response_code'] = 'ERR';
                                $data['response_msg'] = 'Internal Processing Error, try later';
                                $data['transactions'] = array();
                            }
                        
                        
                    }else{
                        $data['response_code'] = 'ERR';
                        $data['response_msg'] = 'Invalid Commission or Surcharge type is set';
                        $data['transactions'] = array();
                    }
                    
                    }else{
                        $data['response_code'] = 'ERR';
                        $data['response_msg'] = 'Settlement Configuration Issue';
                        $data['transactions'] = array();
                    }
                        
                }


            }else{
                $data['response_code'] = 'ERR';
                $data['response_msg'] = 'Margins not configured for your account';
                $data['transactions'] = array();
            }


        }
        else{
            $data['response_code'] = 'ERR';
            $data['response_msg'] = 'Invalid Service';
            $data['transactions'] = array();
        }                
                                
        return $data;
    }
    
    private function aeps_process_service_transaction($userdetails,$chkservicestat,$params)
    {
        $data=array();
        
        if($params['fastpay_req_type']=="AEPSPROCESSTRANSACTION")
        {
            
            
            if(isset($params['transactions'][0]['customer_params']))
            {
            
            $get_commercial_details=$this->calculate_aeps_margins($userdetails,$chkservicestat,$params);
                
            if($get_commercial_details)
            {
                
                if($get_commercial_details['response_code']=='TXN')
                {
                    
                     $transaction_desc=array(
                            "customerid"=>"",
                            "description"=>"Customer Parameters :"
                            );
                    
                    foreach($params['transactions'][0]['customer_params'] as $ck=>$cv)
                    {
                        if($ck==2)
                        {
                            $transaction_desc['customerid']=$cv;
                        }
                        
                         $transaction_desc['description'].=$cv.", ";
                    }
                    
                    $transaction_desc['description']=(substr($transaction_desc['description'],-2)==", ")?(substr($transaction_desc['description'],0,strlen($transaction_desc['description'])-2)):$transaction_desc['description'];
                    
                    if($chkservicestat['code']=='BCS' || $chkservicestat['code']=='MST')
                    {
                        
                        if($get_commercial_details['margin_description']['is_comm']===false)
                        {
                            
                            
                            $findbalance = $userdetails;
                            
                            $openingbal = $findbalance['rupee_balance'];
                            $closingbal = $openingbal-$get_commercial_details['margin_description']['charged_amt'];

                            if($closingbal>=0 & is_numeric($get_commercial_details['margin_description']['charged_amt']) && $get_commercial_details['margin_description']['charged_amt']>=0)
                            {
                            
                            $transid=generate_txnid();

                            
                            $RetailerTxnEntry = array(
                                            "user_id"=>$findbalance['user_id'],
                                            "req_ip"=>ip_address(),
                                            "request_useragent "=>$this->_ci->agent->agent_string(),
                                            "fstpytxn_id"=>$transid,
                                            "sp_id"=>(isset($params['request_id']))?$params['request_id']:"00",
                                            "opr_ref_no"=>"00",
                                            'customer_no' =>$transaction_desc['customerid'],///dynamic//
                                            'scode' => $chkservicestat['code'],
                                            "servicename"=>$chkservicestat['service_name'],
                                            "servicetype"=>$chkservicestat['type'],
                                            "servedby"=>$chkservicestat['vendor_id'],
                                            "transamt"=>$get_commercial_details['margin_description']['txn_amt'],
                                            "chargeamt"=>$get_commercial_details['margin_description']['charged_amt'],
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$closingbal,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Transaction Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$transaction_desc['description'],
                                        );
                                
                            $retailer_comm_tds_array=array();
                                 
                            $retailer_comm_tds_array['TAX']=array(
                                                'user_id' => $findbalance['user_id'],
                                                'cbrt_id' => $transid,
                                                'billing_model' => $chkservicestat['billing_model'],
                                                'trans_amt' => $get_commercial_details['margin_description']['txn_amt'],
                                                'charged_amt' => $get_commercial_details['margin_description']['charged_amt'],
                                                'comm_amnt' => $get_commercial_details['margin_description']['base_comm'],
                                                'tds_amnt' => $get_commercial_details['margin_description']['tds'],
                                                'gst_amnt' => $get_commercial_details['margin_description']['gst'],
                                                'gst_status' => 'PAID',
                                                'tds_status' => 'PAID',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                            
                            $parent_comm_array=array();
                            $parent_comm_tds_tax_array=array();
                            
                            $initiate_transaction=$this->_ci->Inst_model->initiate_service_transaction($RetailerTxnEntry,$retailer_comm_tds_array,$parent_comm_tds_tax_array);   
                            
                            if($initiate_transaction)
                            {
                                    $data['response_code'] = 'TXN';
                                    $data['response_msg'] = 'Transaction Successfull';
                                    $data['transactions'] = array(
                                                            array("agent_id"=>$transid)
                                                        );
                            }else{
                                
                                    $data['response_code'] = 'ERR';
                                    $data['response_msg'] = 'Internal Processing Error, try again later';
                                    $data['transactions'] = array();
                            }
                                
                        }else{
                                    $data['response_code'] = 'ERR';
                                    $data['response_msg'] = 'Insufficient Account Balance';
                                    $data['transactions'] = array();
                        }
                            
                        }else{
                            $data['response_code'] = 'ERR';
                            $data['response_msg'] = 'Margin Configuration Error, Transaction cannot be processed';
                            $data['transactions'] = array();
                        }
                        
                    }else if($chkservicestat['code']=='CWS')
                    {
                        
                        $check_if_settlement_config_exist=$this->_ci->Inst_model->check_users_settlement_config($userdetails['user_id']);
                    
                    if($check_if_settlement_config_exist)
                    {
                        
                        if($get_commercial_details['margin_description']['is_comm']===true)
                        {
                            
                            $amnt=$get_commercial_details['margin_description']['txn_amt'];
                            $parent_comm_array=array();
                            $parent_comm_tds_tax_array=array();
                              
                            $findbalance = $userdetails;
                            
                            $withdrawal_amt=($get_commercial_details['margin_description']['txn_amt']+$get_commercial_details['margin_description']['base_comm']-$get_commercial_details['margin_description']['tds']);
                            
                            $transid=generate_txnid();
                            $openingbal=$findbalance['rupee_balance'];
                            
                            $RetailerTxnEntry = array(
                                            "user_id"=>$findbalance['user_id'],
                                            "req_ip"=>ip_address(),
                                            "request_useragent "=>$this->_ci->agent->agent_string(),
                                            "fstpytxn_id"=>$transid,
                                            "sp_id"=>(isset($params['request_id']))?$params['request_id']:"00",
                                            "opr_ref_no"=>"00",
                                            'customer_no' =>$transaction_desc['customerid'],///dynamic//
                                            'scode' => $chkservicestat['code'],
                                            "servicename"=>$chkservicestat['service_name'],
                                            "servicetype"=>$chkservicestat['type'],
                                            "servedby"=>$chkservicestat['vendor_id'],
                                            "transamt"=>$get_commercial_details['margin_description']['txn_amt'],
                                            "chargeamt"=>$withdrawal_amt,
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$openingbal,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Transaction Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$transaction_desc['description'],
                                            "op2"=>$get_commercial_details['margin_description']['base_comm'],
                                            "op3"=>$get_commercial_details['margin_description']['tds'],
                                            "op4"=>$get_commercial_details['margin_description']['gst'],
                                            "op10"=>"PENDING"
                                        );
                                
                            $retailer_comm_tds_array=array();
                            
                            if($chkservicestat['billing_model']=="P2P" || $chkservicestat['billing_model']=="P2A")
                                {
                                    
                                    $retailer_comm_tds_array['TAX']=array(
                                                'user_id' => $findbalance['user_id'],
                                                'cbrt_id' => $transid,
                                                'billing_model' => $chkservicestat['billing_model'],
                                                'trans_amt' => $get_commercial_details['margin_description']['txn_amt'],
                                                'charged_amt' => $get_commercial_details['margin_description']['charged_amt'],
                                                'comm_amnt' => $get_commercial_details['margin_description']['base_comm'],
                                                'tds_amnt' => $get_commercial_details['margin_description']['tds'],
                                                'gst_amnt' => $get_commercial_details['margin_description']['gst'],
                                                'gst_status' => 'PENDING',
                                                'tds_status' => 'PENDING',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                                    
                                    
                                }
                                else{
                                    $retailer_comm_tds_array['TAX']=array(
                                                'user_id' => $findbalance['user_id'],
                                                'cbrt_id' => $transid,
                                                'billing_model' => $chkservicestat['billing_model'],
                                                'trans_amt' => $get_commercial_details['margin_description']['txn_amt'],
                                                'charged_amt' => $get_commercial_details['margin_description']['charged_amt'],
                                                'comm_amnt' => $get_commercial_details['margin_description']['base_comm'],
                                                'tds_amnt' => $get_commercial_details['margin_description']['tds'],
                                                'gst_amnt' => $get_commercial_details['margin_description']['gst'],
                                                'gst_status' => 'PAID',
                                                'tds_status' => 'PAID',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                                }
                                 
                            if($userdetails['parent_id']!=0)
                            {
                                
                                $get_parent_info=$this->_ci->Inst_model->UserTreeFetchForComm($userdetails['parent_id']);
                                
                                if($get_parent_info)
                                {
                                    
                                    foreach($get_parent_info as $pk=>$pv)
                                    {
                                        if($pv['role_id']==2 || $pv['role_id']==3 )
                                        {
                                            
                                            $identify_commision_from=$findbalance['first_name'] .' '. $findbalance['last_name'] . ' ( ' . $findbalance['mobile'] . ' )';
                                            
                                            $checkparentsplan = $this->_ci->Inst_model->checkuser_pln_dtl($pv['role_id'],$pv['plan_id'],$chkservicestat['service_id']);
                                            if($checkparentsplan)
                                            {
                                                
                                                $parent_capping_amount=$checkparentsplan['capping_amount'];
                                                $parent_charge_type=$checkparentsplan['charge_type'];
                                                $parent_charge_method=$checkparentsplan['charge_method'];
                                                $parent_service_rate=$checkparentsplan['rate'];
                                                
                                                if($checkparentsplan['slab_applicable']==1)
                                                {
                                                    $chck_parent_agrd_fees_range = $this->_ci->Inst_model->check__new_pln_amnt_rng($checkparentsplan['plan_id'], $checkparentsplan['service_id'], $amnt);
                                                    
                                                    if($chck_parent_agrd_fees_range)
                                                    {
                                                        $parent_charge_type=$chck_parent_agrd_fees_range['charge_type'];
                                                        $parent_charge_method=$chck_parent_agrd_fees_range['charge_method'];
                                                        $parent_service_rate=$chck_parent_agrd_fees_range['rate'];
                                                        
                                                    }else{
                                                        
                                                        $data['response_code'] = 'ERR';
                                                        $data['response_msg'] = 'Slab rate not configured for your parent';
                                                        $data['transactions'] = array();
                                                        
                                                        return $data;
                                                    }
                                                    
                                                    
                                                }
                                                
                                                if($parent_charge_method=="CREDIT")
                                                {
                                                    
                                                    if($parent_charge_type=="FIXED")
                                                    {
                                                        $parent_comm_array[$pv['user_id']]['rate_value']=$parent_service_rate;
                                                        $parent_comm_array[$pv['user_id']]['base_comm']=$parent_service_rate;
                                                        $parent_comm_array[$pv['user_id']]['gst']=round((($parent_comm_array[$pv['user_id']]['base_comm']*18)/100),2);
                                                        $parent_comm_array[$pv['user_id']]['app_comm']=$parent_comm_array[$pv['user_id']]['base_comm']+$parent_comm_array[$pv['user_id']]['gst'];
                                                        $parent_comm_array[$pv['user_id']]['tds']=round(((($parent_comm_array[$pv['user_id']]['base_comm'])*($pv['tds_value']))/100),2);
                                                        $parent_comm_array[$pv['user_id']]['is_comm']=true;
                                                        
                                                        
                                                        
                                                    }
                                                    else if($parent_charge_type=="PERCENTAGE")
                                                    {
                                                        $parent_comm_array[$pv['user_id']]['rate_value']=round((($parent_service_rate*$amnt)/100),2);
                                                        
                                                        if(is_numeric($parent_capping_amount))
                                                        {

                                                        $parent_comm_array[$pv['user_id']]['rate_value']=($parent_comm_array[$pv['user_id']]['rate_value']>$parent_capping_amount)?$parent_capping_amount:$parent_comm_array[$pv['user_id']]['rate_value'];

                                                        }

                                                        $parent_comm_array[$pv['user_id']]['base_comm']=$parent_comm_array[$pv['user_id']]['rate_value'];
                                                        $parent_comm_array[$pv['user_id']]['gst']=round((($parent_comm_array[$pv['user_id']]['base_comm']*18)/100),2);
                                                        $parent_comm_array[$pv['user_id']]['app_comm']=$parent_comm_array[$pv['user_id']]['base_comm']+$parent_comm_array[$pv['user_id']]['gst'];
                                                        $parent_comm_array[$pv['user_id']]['tds']=round(((($parent_comm_array[$pv['user_id']]['base_comm'])*($pv['tds_value']))/100),2);
                                                        $parent_comm_array[$pv['user_id']]['is_comm']=true;
                                                        
                                                        
                                                        
                                                        
                                                    }
                                                    else{
                                                        
                                                        $data['response_code'] = 'ERR';
                                                        $data['response_msg'] = 'Invalid charge type is configured for your parent';
                                                        $data['transactions'] = array();
                                                        
                                                        return $data;
                                                    }
                                                    
                                                    if(isset($parent_comm_array[$pv['user_id']]))
                                                    {
                                                        $parent_comm_opening=$pv['rupee_balance'];
                                                        $parent_comm_closing=$parent_comm_opening+$parent_comm_array[$pv['user_id']]['base_comm'];
                                                        
                                                        $parent_comm_description='Commission From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amnt;
                                                        
                                                        $parent_comm_tds_tax_array[[$pv['user_id']]]['COMM']=array(
                                                                'credit_txnid' => ch_txnid(),
                                                                'user_id' => $pv['user_id'],
                                                                'bank_name' => 'N/A',
                                                                'txn_type' => 'COMMISSION',
                                                                'payment_mode' => 'WALLET',
                                                                'amount' => $parent_comm_array[$pv['user_id']]['base_comm'],
                                                                'opening_balance' => $parent_comm_opening,
                                                                'closing_balance' => $parent_comm_closing,
                                                                'updated_on' => date('Y-m-d H:i:s'),
                                                                'reference_number' => $parent_comm_description,
                                                                'remarks' => $parent_comm_description,
                                                                'txn_code' => $transid,
                                                                'status' => 'CREDIT',
                                                                'created_on'=>date('Y-m-d H:i:s'),
                                                                'created_by'=>$findbalance['user_id'],
                                                                'updated_by' =>$findbalance['user_id']
                                                        );
                                                        
                                                        $parent_tds_opening=$parent_comm_closing;
                                                        $parent_tds_closing=$parent_tds_opening-$parent_comm_array[$pv['user_id']]['tds'];
                                                        
                                                        $prent_tds_description='TDS Deducted On, Commission of Rs. ' . $parent_comm_array[$pv['user_id']]['base_comm'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amnt;
                                                        
                                                        $parent_comm_tds_tax_array[[$pv['user_id']]]['TDS']=array(
                                                                    'credit_txnid' => ch_txnid(),
                                                                    'user_id' => $pv['user_id'],
                                                                    'bank_name' => 'N/A',
                                                                    'txn_type' => 'TDS',
                                                                    'payment_mode' => 'WALLET',
                                                                    'amount' => $parent_comm_array[$pv['user_id']]['tds'],
                                                                    'opening_balance' => $parent_tds_opening,
                                                                    'closing_balance' => $parent_tds_closing,
                                                                    'updated_on' => date('Y-m-d H:i:s'),
                                                                    'reference_number' => $prent_tds_description,
                                                                    'remarks' => $prent_tds_description,
                                                                    'txn_code' => $transid,
                                                                    'status' => 'DEBIT',
                                                                    'created_on'=>date('Y-m-d H:i:s'),
                                                                    'created_by'=>$findbalance['user_id'],
                                                                    'updated_by' =>$findbalance['user_id']
                                                        );
                                                        
                                                        $parent_comm_tds_tax_array[[$pv['user_id']]]['TAX']=array(
                                                                'user_id' => $pv['user_id'],
                                                                'cbrt_id' => $transid,
                                                                'billing_model' => 'P2A',
                                                                'trans_amt' => $amnt,
                                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                                'comm_amnt' => $parent_comm_array[$pv['user_id']]['base_comm'],
                                                                'tds_amnt' => $parent_comm_array[$pv['user_id']]['tds'],
                                                                'gst_amnt' => $parent_comm_array[$pv['user_id']]['gst'],
                                                                'gst_status' => 'PENDING',
                                                                'tds_status' => 'PENDING',
                                                                'tax_type' => 'CREDIT',
                                                                'created_dt' => date('Y-m-d H:i:s'),
                                                                'created_by'=>$findbalance['user_id']
                                                        );
                                                    }
                                                    
                                                    
                                                }else{
                                                    
                                                    $data['response_code'] = 'ERR';
                                                    $data['response_msg'] = 'Invalid charge method is configured for your parent';
                                                    $data['transactions'] = array();
                                                    
                                                    return $data;
                                                }
                                                
                                                

                                            }else{
                                                
                                                $data['response_code'] = 'ERR';
                                                $data['response_msg'] = 'Plan not configured for your parent';
                                                $data['transactions'] = array();
                                                
                                                return $data;
                                            }
                                            
                                        }
                                    }
                                    
                                }
                                
                            }
                            
                            $initiate_transaction=$this->_ci->Inst_model->initiate_aeps_transaction($RetailerTxnEntry,$retailer_comm_tds_array,$parent_comm_tds_tax_array);   
                            
                            if($initiate_transaction)
                            {
                                    $data['response_code'] = 'TXN';
                                    $data['response_msg'] = 'Transaction Successfull';
                                    $data['transactions'] = array(
                                                            array("agent_id"=>$transid)
                                                        );
                            }else{
                                
                                    $data['response_code'] = 'ERR';
                                    $data['response_msg'] = 'Internal Processing Error, try again later';
                                    $data['transactions'] = array();
                            }
                           
                            
                        }else{
                            $data['response_code'] = 'ERR';
                            $data['response_msg'] = 'Margin Configuration Error, Transaction cannot be processed';
                            $data['transactions'] = array();
                        }
                        
                    }else{
                        $data['response_code'] = 'ERR';
                        $data['response_msg'] = 'Settlement Configuration Issue';
                        $data['transactions'] = array();
                    }
                        
                    }else{
                        $data['response_code'] = 'ERR';
                        $data['response_msg'] = 'Invalid Service';
                        $data['transactions'] = array();
                    }
                    
                }else{
                    $data=$get_commercial_details;
                }
                
            }else{
                $data['response_code'] = 'ERR';
                $data['response_msg'] = 'Something went wrong in process';
                $data['transactions'] = array();
            }
                
            }else{
                $data['response_code'] = 'ERR';
                $data['response_msg'] = 'Invalid Customer Parameters';
                $data['transactions'] = array();
            }
            
        }else{
            $data['response_code'] = 'ERR';
            $data['response_msg'] = 'This particular request is not supported, contact helpdesk';
            $data['transactions'] = array();
        }
        
        return $data;
        
    }
    
    
    function prepaid_rech($user_info, $planid, $accnt, $amnt, $chkservicestat) {
        $test['user_info'] = $user_info;
        $test['planid'] = $planid;
        $test['accnt'] = $accnt;
        $test['amnt'] = $amnt;
        $test['chkservicestat'] = $chkservicestat;

        curlRequertLogs($test, 'prepaid_rech', 'instanpayapi');

        $data = array();
        $accnt = trim(strip_tags($accnt));
        $amnt = strip_tags((int) $amnt);
        if (ctype_digit($accnt) && strlen($accnt) >= $chkservicestat['min_len'] && strlen($accnt) <= $chkservicestat['max_len']) {
            if (($amnt) && ($amnt) >= $chkservicestat['min_amt'] && ($amnt) <= $chkservicestat['max_amt']) {
                $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
                if ($operator_dtl) {
                    $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                    curlRequertLogs($chck_user_pln_dtl, 'chck_user_pln_dtl', 'instanpayapi');
                    if ($chck_user_pln_dtl) {
                 
                        if ($chkservicestat['billing_model']) {
                            if ($chkservicestat['billing_model'] == 'P2P') {
                                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                                    if ($chck_user_pln_dtl['slab_applicable'] == 1) {  
                                        $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);
                                        curlRequertLogs($chck_agrd_fee_rng, 'chck_agrd_fee_rng', 'instanpayapi');
                                        
                                        if ($chck_agrd_fee_rng){
                                            if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                                $rate = $chck_agrd_fee_rng['rate']; //rate=7
                                            } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                                $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                                $rate = ($rate_in_prcmt * $amnt) / 100;

                                                $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                                $rate = round($rate, 2);

                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }

                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Rate configuration issue,contact admin';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }

                                    }else {
                                    //    /*************** amount routing not applicable*********** */

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        
                                            $rate = $chck_user_pln_dtl['rate'];

                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);

                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                   
                                    } 

                                }else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate configuration issue, contact admin.1';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }

                            }elseif ($chkservicestat['billing_model'] == 'P2A') {
                                //P2A//
                                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                                    if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                        $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                        if ($chck_agrd_fee_rng) {

                                            if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                                $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                            } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {
                                                $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                                $rate = ($rate_in_prcmt * $amnt) / 100;

                                                $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                                $rate = round($rate, 2);
                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Rate configuration issue,contact admin .111';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                            $rate = $chck_user_pln_dtl['rate'];
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }

                             //P2A//   

                            }elseif ($chkservicestat['billing_model'] == 'CHARGE') {
                                //Charge//
                                if ($chck_user_pln_dtl['charge_method'] == 'DEBIT') {
                                    if ($chck_user_pln_dtl['slab_applicable'] == 1) {

                                        $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                        if ($chck_agrd_fee_rng) {

                                            if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                                $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                            } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                                $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                                $rate = ($rate_in_prcmt * $amnt) / 100;

                                                $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                                $rate = round($rate, 2);
                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Rate configuration issue,contact admin';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                            $rate = $chck_user_pln_dtl['rate']; //rate=11
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {
                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    }
                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }

                                //Charge//

                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }

                            /*******calculation of base,appl comm, gst **********/

                            if($chkservicestat['billing_model'] == 'P2P') {
                                $amount = $amnt;
                                $applicable_commission = $rate; ///(Applicable GST)//2
                                $base_applicable_commisison = $applicable_commission;
                                $gstamount_tobecredited = 0;
                                $tdsamount = (($applicable_commission * 5) / 100);
                                $tdsamount = round($tdsamount, 2);
                                $charged_amount = $amount;
                            } elseif ($chkservicestat['billing_model'] == 'P2A') {
                                $amount = $amnt;
                                $applicable_commission = $rate; ///(Applicable GST)//2
                                $charged_amount = $amount;
                                $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                $base_applicable_commisison = round($base_applicable_commisison, 2);
                                $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                $tdsamount = (($base_applicable_commisison * 5) / 100);
                                $tdsamount = round($tdsamount, 2);
                            
                            } elseif ($chkservicestat['billing_model'] == 'CHARGE') {
                                $amount = $amnt;
                                $applicable_commission = 0; ///(Applicable GST)//2
                                $base_applicable_commisison = 0;
                                $gstamount_tobecredited = ($rate - (($rate / 118) * 100));
                                $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                $tdsamount = 0;

                                $charged_amount = $amount + $rate;

                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }

                            $common_array=array();
                            /*******calculation of base gst ***********/
                            if ($user_info['parent_id'] != '0') {
                                $get_retailer_parent_info = $this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                                    if ($get_retailer_parent_info) {

                                        foreach ($get_retailer_parent_info as $key => $value) {
                               
                                            if($value['role_id']==2 || $value['role_id']==3 ){
                      
                                                $checkusercustomprice_parnt = $this->_ci->Inst_model->checkuser_pln_dtl($value['role_id'],$value['plan_id'],$chkservicestat['service_id']);
                                                curlRequertLogs($checkusercustomprice_parnt, 'checkusercustomprice_parnt', 'instanpayapi');
                                               
                                                if($checkusercustomprice_parnt){
                                                    $prnt_capng_nrmlamnt=$checkusercustomprice_parnt['capping_amount'];
                                                    if($checkusercustomprice_parnt['slab_applicable']==1){
                                                        $chck_agrd_fee_rng_fr_prnt=$this->_ci->Inst_model->check_pln_amnt_rng($checkusercustomprice_parnt['pl_srvc_rl_id'],$checkusercustomprice_parnt['plan_id'],$checkusercustomprice_parnt['service_id'],$amount);
                                    
                                                        if($chck_agrd_fee_rng_fr_prnt){
                                                            $parentplan_rate=$chck_agrd_fee_rng_fr_prnt['rate'];//rate=11
                                                        }else{
                                                            $data['error']=1;
                                                            $data['error_desc']='Rate configuration issue,contact admin';
                                                            $data['msg']=null;
                                                            $data['status'] = 'FAILED';
                                                            return $data;
                                                        }
                                                    }else{
                                                        $parentplan_rate=$checkusercustomprice_parnt['rate'];
                                                    }
                               
                                                    if ($checkusercustomprice_parnt['charge_method'] == 'CREDIT' && ($checkusercustomprice_parnt['charge_type'] == 'FIXED' || $checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE')) {

                                                        if ($checkusercustomprice_parnt['charge_type'] == 'FIXED') {

                                                            /* * 1> txn amount=$amount
                                                            2> ccf=1%of txn amnt
                                                            ccf = (1%of txn amnt) OR Rs 10(gtr ccf consider)
                                                            3> base of ttl ccf(net ccf bank)= (ccf/118)*100
                                                            4> app_comm=net ccf bank -srvc_rate
                                                            5> base_commsn =(app_comm/118)*100
                                                            6> gst of comm= app_comm- base_commsn
                                                            7> 5% TDS on base_commsn=base_commsn*5/100
                                                            * */
                                                            /** $rate_nrmlprnt=Rs 1** */
                                                            $commission_nrmlprnt_amt = $parentplan_rate;

                                                            $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                            $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                            if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                                $base_applicable_commisison_fr_nrmlprnt = ($applicable_commission_nrmlprnt/118)*100;
                                                                $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                                $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                                $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                                $common_array[] = array(
                                                                    'USERID' => $value['user_id'],
                                                                    'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                    'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                    'TDS' => $tdsamount_fr_nrmlprnt,
                                                                    'GST' => 0,
                                                                );
                                                          
                                                            } else {
                                                                $data['error'] = 1;
                                                                $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                                $data['msg'] = null;
                                                                $data['status'] = 'FAILED';
                                                                return $data;
                                                            }
                                                        } else if($checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE') {

                                                            $commission_nrmlprnt_amt = (($parentplan_rate) / 100) * $amnt;

                                                            $commission_nrmlprnt_amt = ($commission_nrmlprnt_amt >= $prnt_capng_nrmlamnt) ? $prnt_capng_nrmlamnt : $commission_nrmlprnt_amt;

                                                            $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                            $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                            if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                                $base_applicable_commisison_fr_nrmlprnt = ($commission_nrmlprnt_amt/118)*100;
                                                                $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                                $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                                $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                                $common_array[] = array(
                                                                    'USERID' => $value['user_id'],
                                                                    'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                    'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                    'TDS' => $tdsamount_fr_nrmlprnt,
                                                                    'GST' => 0,
                                                                );
                                                      
                                                            } else {
                                                                $data['error'] = 1;
                                                                $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                                $data['msg'] = null;
                                                                $data['status'] = 'FAILED';
                                                                return $data;
                                                            }
                                                        } else {
                                                            $data['error'] = 1;
                                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                            $data['msg'] = null;
                                                            $data['status'] = 'FAILED';
                                                            return $data;
                                                        }
                                                    }else{
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'configuration issue,contact admin 1';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }

                                                }else{

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'No Profile for parent';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }

                                            }

                                        }

                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Unable to find parent details';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                }

                            
                                $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                   
                                if ($findbalance) {

                                    $openingbal = $findbalance['rupee_balance'];

                                    $closing_balance = $openingbal - $charged_amount;

                                    $identify_commision_from = $findbalance['first_name'] .' '. $findbalance['first_name'] . ' ( ' . $findbalance['mobile'] . ' )';

                                    if ($openingbal >= $charged_amount && $closing_balance >= 0 && $charged_amount != null) {

                                        $transid = txn_remitt_txnid();
                               
                                        $RetailerTxnEntry = array(
                                            "user_id"=>$findbalance['user_id'],
                                            "req_ip"=>ip_address(),
                                            "request_useragent "=>$this->_ci->agent->agent_string(),
                                            "fstpytxn_id"=>$transid,
                                            "sp_id"=>"00",
                                            "opr_ref_no"=>"00",
                                            'customer_no' =>$accnt,///dynamic//
                                            'scode' => $chkservicestat['code'],
                                            "servicename"=>$chkservicestat['service_name'],
                                            "servicetype"=>$chkservicestat['type'],
                                            "servedby"=>$chkservicestat['served_by'],
                                            "transamt"=>$amount,
                                            "chargeamt"=>$charged_amount,
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$closing_balance,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Recharge Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$accnt,
                                        );

                                        if (($chkservicestat['billing_model'] == 'P2P') || ($chkservicestat['billing_model'] == 'P2A')) {
                                            $CreditHistroyIdCashback = ch_txnid();
                                            $comm_openingbal = $closing_balance;
                                            $basecomm_amount = $base_applicable_commisison;
                                            $comm_closingbal = $comm_openingbal + $basecomm_amount;

                                            $CashbackCrdtEntry_reatiler = array(
                                                'credit_txnid' => $CreditHistroyIdCashback,
                                                'user_id' => $findbalance['user_id'],
                                                'bank_name' => 'N/A',
                                                'txn_type' => 'CASHBACK',
                                                'payment_mode' => 'WALLET',
                                                'amount' => $basecomm_amount,
                                                'opening_balance' => $comm_openingbal,
                                                'closing_balance' => $comm_closingbal,
                                                'updated_on' => date('Y-m-d H:i:s'),
                                                'reference_number' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                'remarks' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                'txn_code' => $transid,
                                                'status' => 'CREDIT',
                                                'updated_by' => $findbalance['user_id'],
                                            );

                                            $CreditHistroyTDSId = ch_txnid();
                                            $Tds_opng_bal = $comm_closingbal;
                                            $RetTds = $tdsamount;
                                            $Tds_clsng_bal = $Tds_opng_bal - $RetTds;

                                            $TDSCreditHistoryEntryRetailer = array(
                                                'credit_txnid' => $CreditHistroyTDSId,
                                                'user_id' => $findbalance['user_id'],
                                                'bank_name' => 'N/A',
                                                'txn_type' => 'TDS',
                                                'payment_mode' => 'WALLET',
                                                'amount' => $RetTds,
                                                'opening_balance' => $Tds_opng_bal,
                                                'closing_balance' => $Tds_clsng_bal,
                                                'updated_on' => date('Y-m-d H:i:s'),
                                                'reference_number' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                'remarks' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                'txn_code' => $transid,
                                                'status' => 'DEBIT',
                                                'updated_by' => $findbalance['user_id'],
                                            );

                                            $TaxRecordRet = array(
                                                'user_id' => $findbalance['user_id'],
                                                'cbrt_id' => $transid,
                                                'billing_model' => $chkservicestat['billing_model'],
                                                'trans_amt' => $amount,
                                                'charged_amt' => $charged_amount,
                                                'comm_amnt' => $base_applicable_commisison,
                                                'tds_amnt' => $tdsamount,
                                                'gst_amnt' => $gstamount_tobecredited,
                                                'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
                                                'tds_status' => 'PENDING',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'updated_by' => $findbalance['user_id'],
                                                'updated_dt' => date('Y-m-d H:i:')
                                            );

                                        } else {

                                            $TaxRecordRet = array(
                                                'user_id' => $findbalance['user_id'],
                                                'cbrt_id' => $transid,
                                                'billing_model' => $chkservicestat['billing_model'],
                                                'trans_amt' => $amount,
                                                'charged_amt' => $charged_amount,
                                                'comm_amnt' => $base_applicable_commisison,
                                                'tds_amnt' => $tdsamount,
                                                'gst_amnt' => $gstamount_tobecredited,
                                                'gst_status' => 'PENDING',
                                                'tds_status' => 'PENDING',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'updated_by' => $findbalance['user_id'],
                                                'updated_dt' => date('Y-m-d H:i:')
                                            );

                                            $CashbackCrdtEntry_reatiler = array();
                                            $TDSCreditHistoryEntryRetailer = array();   
                                        }

                                        $RetailerInsertEntry = $this->_ci->Inst_model->RetailerAllEntryWithoutAdmin($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet);

                                        if ($RetailerInsertEntry) {
                                            $inserted_id = $RetailerInsertEntry;
                                        
                                            $ParentArray = array();
                                       
                                            if ($findbalance['parent_id'] != 0) {
                                                if (count($common_array) > 0) {
                                                    foreach ($common_array as $k => $v) {

                                                        $ParentInfo = $this->_ci->Inst_model->user_info($v['USERID']);

                                                        if ($ParentInfo) {

                                                            $parent_opening = $ParentInfo['rupee_balance'];
                                                            $parent_closeing = $parent_opening + $v['BASCOMM'];
                                                            $ParentArray[$k]['COM'] = array(
                                                                'credit_txnid' => ch_txnid(),
                                                                'user_id' => $ParentInfo['user_id'],
                                                                'bank_name' => 'N/A',
                                                                'txn_type' => 'COMMISSION',
                                                                'payment_mode' => 'WALLET',
                                                                'amount' => $v['BASCOMM'],
                                                                'opening_balance' => $parent_opening,
                                                                'closing_balance' => $parent_closeing,
                                                                'updated_on' => date('Y-m-d H:i:s'),
                                                                'reference_number' => 'Commission From ' . $identify_commision_from . ' For ' . $transid,
                                                                'remarks' => 'Commission From ' . $identify_commision_from . ' For ' . $transid . ',Transaction Amount : Rs. ' . $amount,
                                                                'txn_code' => $transid,
                                                                'status' => 'CREDIT',
                                                                'updated_by' => $ParentInfo['user_id']
                                                            );

                                                            $parent_opening_tds = $parent_closeing;
                                                            $parent_closing_tds = $parent_opening_tds - $v['TDS'];

                                                            $ParentArray[$k]['TDS'] = array(
                                                                'credit_txnid' => ch_txnid(),
                                                                'user_id' => $ParentInfo['user_id'],
                                                                'bank_name' => 'N/A',
                                                                'txn_type' => 'TDS',
                                                                'payment_mode' => 'WALLET',
                                                                'amount' => $v['TDS'],
                                                                'opening_balance' => $parent_opening_tds,
                                                                'closing_balance' => $parent_closing_tds,
                                                                'updated_on' => date('Y-m-d H:i:s'),
                                                                'reference_number' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                                'remarks' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                                'txn_code' => $transid,
                                                                'status' => 'DEBIT',
                                                                'updated_by' => $ParentInfo['user_id']
                                                            );

                                                            $ParentArray[$k]['TAX'] = array(
                                                                'user_id' => $v['USERID'],
                                                                'cbrt_id' => $transid,
                                                                'billing_model' => 'P2A',
                                                                'trans_amt' => $amount,
                                                                'charged_amt' => $charged_amount,
                                                                'comm_amnt' => $v['BASCOMM'],
                                                                'tds_amnt' => $v['TDS'],
                                                                'gst_amnt' => $v['GST'],
                                                                'gst_status' => 'PENDING',
                                                                'tds_status' => 'PENDING',
                                                                'tax_type' => 'CREDIT',
                                                                'created_dt' => date('Y-m-d H:i:s'),
                                                                'updated_by' => $ParentInfo['user_id'],
                                                                'updated_dt' => date('Y-m-s H:i:s')
                                                            );
                                                        }
                                                    }
                                                }
                                          
                                                $parent_comission = $this->_ci->Inst_model->parent_commission_without_Admin($ParentArray);
                                            
                                            }
                                       
                                            $curl = curl_init();
                                            $url = 'https://www.instantpay.in/ws/api/transaction?format=json&token=' . $this->token . '&spkey=' . $chkservicestat['code'] . '&agentid=' . $transid . '&account=' . urlencode($accnt) . '&amount=' . $amount;
                                       
                                            curl_setopt_array($curl, array(

                                                CURLOPT_URL => $url,
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => "",
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 0,
                                                CURLOPT_FOLLOWLOCATION => true,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => "GET",

                                            ));

                                            $response = curl_exec($curl);

                                            curl_close($curl);
                                        
                                            if ($response) {
                                                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Prepaid trans","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                                   
                                                $response = json_decode($response, true);
                                                if(is_array($response)){

                                                    if(isset($response['ipay_errorcode'])) {
                                                        $mapped_error=$response['ipay_errorcode'];
                                                        $mapped_error_desc=$response['ipay_errordesc'];
                                                    }else if(isset($response['res_code'])){
                                                        $mapped_error=$response['res_code'];
                                                        $mapped_error_desc=$response['res_msg'];
                                                    }else{
                                                        $mapped_error='';
                                                        $mapped_error_desc='Unknown Error'; 
                                                    }

                                                    $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,$chkservicestat['vendor_id']); 
                
                                                    if($error_mapping){

                                                        $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                               
                                                        

                                                        if(isset($response['status'])){
                                
                                                            if($response['status']=='SUCCESS'){
                                                                $txnstatus=$response['status'];


                                                                $data['error'] = 0;
                                                                $data['error_desc'] = null;
                                                                $data['msg'] = $error_mapping['error_code_desc'];
                                                                $data['TxnId'] = isset($response['agent_id']) ? $response['agent_id'] : '00'; 
                                                                $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                                $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                                $fstpyresponsecode =$error_mapping['error_code'];
                                                                $fstpayresponse =$error_mapping['error_code_desc'];
                                                                $data['status'] = $txnstatus;
                                      
                                                            } else if($response['status']=='PENDING'){ ///Timeout
                                                                $txnstatus=$response['status'];
                                                                $data['error'] = 3;
                                                                $data['error_desc'] = null;
                                                                $data['msg'] = $error_mapping['error_code_desc'];
                                                                //$data['TxnId'] = $transid;
                                                                $data['TxnId'] = isset($response['agent_id']) ? $response['agent_id'] : '00';
                                                                $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                                $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';

                                                                $data['status'] =$txnstatus;

                                                                $fstpyresponsecode = $error_mapping['error_code'];
                                                                $fstpayresponse = $error_mapping['error_code_desc'];
                                        
                                                            } else{

                                                                $txnstatus='PENDING';
                                                                $data['error'] = 3;
                                                                $data['error_desc'] = $error_mapping['error_code_desc'];
                                                                $data['msg'] = null;
                                                                $data['TxnId'] = $transid;
                                                                $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                                $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                                $data['status'] = $txnstatus;
                                                                $fstpyresponsecode = $error_mapping['error_code'];
                                                                $fstpayresponse = $error_mapping['error_code_desc'];

                                                            }
                                
                                                        }else{
                                                            $txnstatus='FAILED';
                                                            $data['error'] = 1;
                                                            $data['error_desc'] = $error_mapping['error_code_desc'];
                                                            $data['msg'] = null;
                                                            $data['TxnId'] = $transid;
                                                            $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                            $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                            $data['status'] = $txnstatus;
                                                            $fstpyresponsecode = $error_mapping['error_code'];
                                                            $fstpayresponse = $error_mapping['error_code_desc'];
                                                        }

                                                    }else{
                                                        $data['error']=3;
                                                        $data['msg']=$mapped_error_desc; 
                                                        $data['error_desc']=null;
                                                        $txnstatus='PENDING';
                                                        $error_mapping['error_code']='OUE';
                                                        $error_mapping['error_code_desc']=$data['msg'];
                                                        $data['TxnId'] = $transid;
                                                        $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                        $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                        $data['status'] = $txnstatus;
                                                        $fstpyresponsecode = $error_mapping['error_code'];
                                                        $fstpayresponse = $error_mapping['error_code_desc'];
                                                    }
                                                    $update_dt_array = array(
                                                        "sp_id" => isset($response['ipay_id'])?$response['ipay_id']:'00',
                                                        "opr_ref_no" => isset($response['opr_id']) ? $response['opr_id'] : '00',
                                                        "sp_respcode" => $error_mapping['error_code'],
                                                        "sp_respdesc" => $error_mapping['error_code_desc'],
                                                        "sp_response" => json_encode($response),
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                                    );

                                                    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);
                                        
                                                }else{
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process 1';
                                                    $data['page'] = 'hgjgfhfhgf';
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
                                                    $fstpayresponse = 'Transaction Under Process';
                                                    $status = $data['status'];


                                                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Prepaid trans","response"=>"Invalid format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                    $update_dt_array = array(
                                                        "sp_id" => "00",
                                                        "opr_ref_no" => "00",
                                                        "sp_respcode" => "TMDOUT",
                                                        "sp_respdesc" => 'Timed Out',
                                                        "sp_response" => "Request Timedout from Vendor",
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => 'TUP',
                                                        "response" => "Transaction Under Process",
                                                        "status" => "PENDING",
                                                        "upd_id" => $inserted_id
                                                    );

                                                    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);


                                                }
                                            } else { 
                                                    
                                                $data['error'] = 3;
                                                $data['error_desc'] = null;
                                                $data['msg'] = 'Transaction Under Process';
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = '00';
                                                $data['TranId'] = '00';
                                                $data['status'] = 'PENDING';
                                            
                                                $fstpayresponse = 'Transaction Under Process';
                                                $status = $data['status'];

                                                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Prepaid trans","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                $update_dt_array = array(
                                                    "sp_id" => "00",
                                                    "opr_ref_no" => "00",
                                                    "sp_respcode" => "TMDOUT",
                                                    "sp_respdesc" => 'Timed Out',
                                                    "sp_response" => "Request Timedout from Vendor",
                                                    "res_dt" => date('Y-m-d H:i:s'),
                                                    "ind_rcode" => 'TUP',
                                                    "response" => "Transaction Under Process",
                                                    "status" => "PENDING",
                                                    "upd_id" => $inserted_id
                                                );

                                                $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);

                                            }
                                       

                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = "Internal Processing Error, Try Again Later";
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = "Insufficient Balance";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                    }

                                }else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find user details1';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                }

                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = "Service not allowed, contact admin";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }
                    } else {

                        $data['error'] = 1;
                        $data['error_desc'] = "Internal Processing Error, Try Again Later";
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Invalid amount";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Mobile Number';
                $data['msg'] = null;
                $data['status'] = 'FAILED';
            }

        return $data;
    }


    function prepaid_rech_old($user_info, $planid, $accnt, $amnt, $chkservicestat) {
        $test['user_info'] = $user_info;
        $test['planid'] = $planid;
        $test['accnt'] = $accnt;
        $test['amnt'] = $amnt;
        $test['chkservicestat'] = $chkservicestat;

        curlRequertLogs($test, 'prepaid_rech', 'instanpayapi');

    	$data = array();
        $accnt = trim(strip_tags($accnt));
        $amnt = strip_tags((int) $amnt);
        if (ctype_digit($accnt) && strlen($accnt) >= $chkservicestat['min_len'] && strlen($accnt) <= $chkservicestat['max_len']) {
            if (($amnt) && ($amnt) >= $chkservicestat['min_amt'] && ($amnt) <= $chkservicestat['max_amt']) {
            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) {
            $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
            curlRequertLogs($chck_user_pln_dtl, 'chck_user_pln_dtl', 'instanpayapi');
                if ($chck_user_pln_dtl) {
                 
                if ($chkservicestat['billing_model']) {
                if ($chkservicestat['billing_model'] == 'P2P') {
                    if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                     if ($chck_user_pln_dtl['slab_applicable'] == 1) {  
                        $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);
                              curlRequertLogs($chck_agrd_fee_rng, 'chck_agrd_fee_rng', 'instanpayapi');
                                    if ($chck_agrd_fee_rng){

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                            $rate = $chck_agrd_fee_rng['rate']; //rate=7
                                          

                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);

                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }

                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }

                     }else {
                     //    /*************** amount routing not applicable*********** */

                                    if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        
                                        $rate = $chck_user_pln_dtl['rate'];

                                    } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;

                                        $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                        $rate = round($rate, 2);

                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Internal Processing Error, Try again later';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                   
                        } 

                    }else {

                        $data['error'] = 1;
                        $data['error_desc'] = 'Rate configuration issue, contact admin.1';
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                        return $data;
                    }

                }elseif ($chkservicestat['billing_model'] == 'P2A') {
                //P2A//
                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                    $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                    if ($chck_agrd_fee_rng) {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                            $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {
                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin .111';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                   } else {

                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        $rate = $chck_user_pln_dtl['rate'];
                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;

                                        $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                        $rate = round($rate, 2);
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }
                    }
                    } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                    }

                 //P2A//   

                }elseif ($chkservicestat['billing_model'] == 'CHARGE') {
                    //Charge//
                        if ($chck_user_pln_dtl['charge_method'] == 'DEBIT') {
                                if ($chck_user_pln_dtl['slab_applicable'] == 1) {

                                    $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                    if ($chck_agrd_fee_rng) {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                            $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                } else {

                                    if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        $rate = $chck_user_pln_dtl['rate']; //rate=11
                                    } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;

                                        $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                        $rate = round($rate, 2);
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Internal Processing Error, Try again later';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                               }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }

                    //Charge//


                }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                }

                /*******calculation of base,appl comm, gst **********/

                    if($chkservicestat['billing_model'] == 'P2P') {

                            $amount = $amnt;
                            $applicable_commission = $rate; ///(Applicable GST)//2
                            $base_applicable_commisison = $applicable_commission;
                            $gstamount_tobecredited = 0;
                            $tdsamount = (($applicable_commission * 5) / 100);
                            $tdsamount = round($tdsamount, 2);
                            $charged_amount = $amount;
                            

                    } elseif ($chkservicestat['billing_model'] == 'P2A') {

                                    $amount = $amnt;
                                    $applicable_commission = $rate; ///(Applicable GST)//2
                                    $charged_amount = $amount;
                                    $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                    $base_applicable_commisison = round($base_applicable_commisison, 2);
                                    $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                    $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                    $tdsamount = (($base_applicable_commisison * 5) / 100);
                                    $tdsamount = round($tdsamount, 2);
                            
                        } elseif ($chkservicestat['billing_model'] == 'CHARGE') {

                            $amount = $amnt;
                            $applicable_commission = 0; ///(Applicable GST)//2
                            $base_applicable_commisison = 0;
                            $gstamount_tobecredited = ($rate - (($rate / 118) * 100));
                            $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                            $tdsamount = 0;

                            $charged_amount = $amount + $rate;


                        } else {


                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }

                        $common_array=array();
                /*******calculation of base gst ***********/
                        if ($user_info['parent_id'] != '0') {
                        $get_retailer_parent_info = $this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                         if ($get_retailer_parent_info) {

                            foreach ($get_retailer_parent_info as $key => $value) {
                               
                            if($value['role_id']==2 || $value['role_id']==3 ){
                      
                            $checkusercustomprice_parnt = $this->_ci->Inst_model->checkuser_pln_dtl($value['role_id'],$value['plan_id'],$chkservicestat['service_id']);
                                               
                                if($checkusercustomprice_parnt){
                                    $prnt_capng_nrmlamnt=$checkusercustomprice_parnt['capping_amount'];
                                if($checkusercustomprice_parnt['slab_applicable']==1){
                                $chck_agrd_fee_rng_fr_prnt=$this->_ci->Inst_model->check_pln_amnt_rng($checkusercustomprice_parnt['pl_srvc_rl_id'],$checkusercustomprice_parnt['plan_id'],$checkusercustomprice_parnt['service_id'],$amount);
                                	
                                if($chck_agrd_fee_rng_fr_prnt){
                               
                         
                                    $parentplan_rate=$chck_agrd_fee_rng_fr_prnt['rate'];//rate=11

                                }else{

                                             $data['error']=1;
                                             $data['error_desc']='Rate configuration issue,contact admin';
                                             $data['msg']=null;
                                             return $data;
                                }

                                }else{

                                      $parentplan_rate=$checkusercustomprice_parnt['rate'];


                                
                                    
                                }
                               
if ($checkusercustomprice_parnt['charge_method'] == 'CREDIT' && ($checkusercustomprice_parnt['charge_type'] == 'FIXED' || $checkusercustomprice_parnt['charge_type'] == 'PERCENT')) {

                                    if ($checkusercustomprice_parnt['charge_type'] == 'FIXED') {

                                                        /* * 1> txn amount=$amount
                                                      2> ccf=1%of txn amnt
                                                      ccf = (1%of txn amnt) OR Rs 10(gtr ccf consider)
                                                      3> base of ttl ccf(net ccf bank)= (ccf/118)*100
                                                      4> app_comm=net ccf bank -srvc_rate
                                                      5> base_commsn =(app_comm/118)*100
                                                      6> gst of comm= app_comm- base_commsn
                                                      7> 5% TDS on base_commsn=base_commsn*5/100
                                                     * */
                                                    /** $rate_nrmlprnt=Rs 1** */
                                                    $commission_nrmlprnt_amt = $parentplan_rate;

                                                    $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                    $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                    if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                        $base_applicable_commisison_fr_nrmlprnt = ($applicable_commission_nrmlprnt/118)*100;
                                                            $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }
                                                } else if($checkusercustomprice_parnt['charge_type'] == 'PERCENT') {

                                                    $commission_nrmlprnt_amt = (($parentplan_rate) / 100) * $amnt;

                                                    $commission_nrmlprnt_amt = ($commission_nrmlprnt_amt >= $prnt_capng_nrmlamnt) ? $prnt_capng_nrmlamnt : $commission_nrmlprnt_amt;

                                                    $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                    $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                    if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                            $base_applicable_commisison_fr_nrmlprnt = ($commission_nrmlprnt_amt/118)*100;
                                                            $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }
                               }else{

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'configuration issue,contact admin';
                                    $data['msg'] = null;
                                    return $data;
                            }/////charge_method CREDIT charge_type FIXED PERCENT//

                            }else{

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'No Profile for parent';
                                    $data['msg'] = null;
                                    return $data;
                            }

                            }

                            }//end foreach loop


                            } else {


                                $data['error'] = 1;
                                $data['error_desc'] = 'Unable to find parent details';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                        }/***$user_info['parent_id'] != '0'***/

                     /*****No parent *****/
                        $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                   
                        if ($findbalance) {

                            $openingbal = $findbalance['rupee_balance'];

                            $closing_balance = $openingbal - $charged_amount;

                            $identify_commision_from = $findbalance['first_name'] .' '. $findbalance['first_name'] . ' ( ' . $findbalance['mobile'] . ' )';

                        if ($openingbal >= $charged_amount && $closing_balance >= 0 && $charged_amount != null) {

                           $transid = txn_remitt_txnid();
                           
                           $RetailerTxnEntry = array(
                            "user_id"=>$findbalance['user_id'],
                            "req_ip"=>ip_address(),
                            "request_useragent "=>$this->_ci->agent->agent_string(),
                            "fstpytxn_id"=>$transid,
                            "sp_id"=>"00",
                            "opr_ref_no"=>"00",
                            'customer_no' =>$accnt,///dynamic//
                            'scode' => $chkservicestat['code'],
                            "servicename"=>$chkservicestat['service_name'],
                            "servicetype"=>$chkservicestat['type'],
                            "servedby"=>$chkservicestat['served_by'],
                            "transamt"=>$amount,
                            "chargeamt"=>$charged_amount,
                            "openingbal"=>$openingbal,
                            "closingbal"=>$closing_balance,
                            "req_dt"=>date('Y-m-d H:i:s'),
                            "res_dt"=>"0000-00-00 00:00:00",
                            "ind_rcode"=>'TUP',
                            "response"=>"Recharge Under Process",
                            "status"=>"PENDING",
                            "op1"=>$accnt,
                          
                           );

                            if (($chkservicestat['billing_model'] == 'P2P') || ($chkservicestat['billing_model'] == 'P2A')) {
                                    $CreditHistroyIdCashback = ch_txnid();
                                    $comm_openingbal = $closing_balance;
                                    $basecomm_amount = $base_applicable_commisison;
                                    $comm_closingbal = $comm_openingbal + $basecomm_amount;


                                    $CashbackCrdtEntry_reatiler = array(
                                        'credit_txnid' => $CreditHistroyIdCashback,
                                        'user_id' => $findbalance['user_id'],
                                        'bank_name' => 'N/A',
                                        'txn_type' => 'CASHBACK',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $basecomm_amount,
                                        'opening_balance' => $comm_openingbal,
                                        'closing_balance' => $comm_closingbal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'remarks' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'txn_code' => $transid,
                                        'status' => 'CREDIT',
                                        'updated_by' => $findbalance['user_id'],
                                    );


                                    $CreditHistroyTDSId = ch_txnid();
                                    $Tds_opng_bal = $comm_closingbal;
                                    $RetTds = $tdsamount;
                                    $Tds_clsng_bal = $Tds_opng_bal - $RetTds;

                                    $TDSCreditHistoryEntryRetailer = array(
                                        'credit_txnid' => $CreditHistroyTDSId,
                                        'user_id' => $findbalance['user_id'],
                                        'bank_name' => 'N/A',
                                        'txn_type' => 'TDS',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $RetTds,
                                        'opening_balance' => $Tds_opng_bal,
                                        'closing_balance' => $Tds_clsng_bal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'remarks' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'txn_code' => $transid,
                                        'status' => 'DEBIT',
                                        'updated_by' => $findbalance['user_id'],
                                    );



                                    $TaxRecordRet = array(
                                        'user_id' => $findbalance['user_id'],
                                        'cbrt_id' => $transid,
                                        'billing_model' => $chkservicestat['billing_model'],
                                        'trans_amt' => $amount,
                                        'charged_amt' => $charged_amount,
                                        'comm_amnt' => $base_applicable_commisison,
                                        'tds_amnt' => $tdsamount,
                                        'gst_amnt' => $gstamount_tobecredited,
                                        'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
                                        'tds_status' => 'PENDING',
                                        'tax_type' => 'CREDIT',
                                        'created_dt' => date('Y-m-d H:i:s'),
                                        'updated_by' => $findbalance['user_id'],
                                        'updated_dt' => date('Y-m-d H:i:')
                                    );

                                } else {

                                    $TaxRecordRet = array(
                                        'user_id' => $findbalance['user_id'],
                                        'cbrt_id' => $transid,
                                        'billing_model' => $chkservicestat['billing_model'],
                                        'trans_amt' => $amount,
                                        'charged_amt' => $charged_amount,
                                        'comm_amnt' => $base_applicable_commisison,
                                        'tds_amnt' => $tdsamount,
                                        'gst_amnt' => $gstamount_tobecredited,
                                        'gst_status' => 'PENDING',
                                        'tds_status' => 'PENDING',
                                        'tax_type' => 'CREDIT',
                                        'created_dt' => date('Y-m-d H:i:s'),
                                        'updated_by' => $findbalance['user_id'],
                                        'updated_dt' => date('Y-m-d H:i:')
                                    );

                                    $CashbackCrdtEntry_reatiler = array();
                                    $TDSCreditHistoryEntryRetailer = array();   
                                }

                                $RetailerInsertEntry = $this->_ci->Inst_model->RetailerAllEntryWithoutAdmin($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet);

                                if ($RetailerInsertEntry) {
                                    $inserted_id = $RetailerInsertEntry;
                                    
                                    $ParentArray = array();
                                   
                                    if ($findbalance['parent_id'] != 0) {
                                       

                                        if (count($common_array) > 0) {////sup dist/ dist of retailer
                                            foreach ($common_array as $k => $v) {

                                                $ParentInfo = $this->_ci->Inst_model->user_info($v['USERID']);


                                                if ($ParentInfo) {


                                                    $parent_opening = $ParentInfo['rupee_balance'];
                                                    $parent_closeing = $parent_opening + $v['BASCOMM'];
                                                    $ParentArray[$k]['COM'] = array(
                                                        'credit_txnid' => ch_txnid(),
                                                        'user_id' => $ParentInfo['user_id'],
                                                        'bank_name' => 'N/A',
                                                        'txn_type' => 'COMMISSION',
                                                        'payment_mode' => 'WALLET',
                                                        'amount' => $v['BASCOMM'],
                                                        'opening_balance' => $parent_opening,
                                                        'closing_balance' => $parent_closeing,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => 'Commission From ' . $identify_commision_from . ' For ' . $transid,
                                                        'remarks' => 'Commission From ' . $identify_commision_from . ' For ' . $transid . ',Transaction Amount : Rs. ' . $amount,
                                                        'txn_code' => $transid,
                                                        'status' => 'CREDIT',
                                                        'updated_by' => $ParentInfo['user_id']
                                                    );


                                                    $parent_opening_tds = $parent_closeing;
                                                    $parent_closing_tds = $parent_opening_tds - $v['TDS'];

                                                    $ParentArray[$k]['TDS'] = array(
                                                        'credit_txnid' => ch_txnid(),
                                                        'user_id' => $ParentInfo['user_id'],
                                                        'bank_name' => 'N/A',
                                                        'txn_type' => 'TDS',
                                                        'payment_mode' => 'WALLET',
                                                        'amount' => $v['TDS'],
                                                        'opening_balance' => $parent_opening_tds,
                                                        'closing_balance' => $parent_closing_tds,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                        'remarks' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                        'txn_code' => $transid,
                                                        'status' => 'DEBIT',
                                                        'updated_by' => $ParentInfo['user_id']
                                                    );

                                                    $ParentArray[$k]['TAX'] = array(
                                                        'user_id' => $v['USERID'],
                                                        'cbrt_id' => $transid,
                                                        'billing_model' => 'P2A',
                                                        'trans_amt' => $amount,
                                                        'charged_amt' => $charged_amount,
                                                        'comm_amnt' => $v['BASCOMM'],
                                                        'tds_amnt' => $v['TDS'],
                                                        'gst_amnt' => $v['GST'],
                                                        'gst_status' => 'PENDING',
                                                        'tds_status' => 'PENDING',
                                                        'tax_type' => 'CREDIT',
                                                        'created_dt' => date('Y-m-d H:i:s'),
                                                        'updated_by' => $ParentInfo['user_id'],
                                                        'updated_dt' => date('Y-m-s H:i:s')
                                                    );
                                                }
                                            }
                                        }/** *count($common_array) end** */


                                      
                                        $parent_comission = $this->_ci->Inst_model->parent_commission_without_Admin($ParentArray);

                                        
                                    }///$findbalance['parent_id'] != 0
                                   
                                   	$curl = curl_init();
                                     $url = 'https://www.instantpay.in/ws/api/transaction?format=json&token=' . $this->token . '&spkey=' . $chkservicestat['code'] . '&agentid=' . $transid . '&account=' . urlencode($accnt) . '&amount=' . $amount;
                                   
									curl_setopt_array($curl, array(
									 
									  CURLOPT_URL => $url,
									  CURLOPT_RETURNTRANSFER => true,
									  CURLOPT_ENCODING => "",
									  CURLOPT_MAXREDIRS => 10,
									  CURLOPT_TIMEOUT => 0,
									  CURLOPT_FOLLOWLOCATION => true,
									  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									  CURLOPT_CUSTOMREQUEST => "GET",

									));

									$response = curl_exec($curl);

									curl_close($curl);
									

                                      
                                    
                                    if ($response) {///response true 
                                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Prepaid trans","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                  $response = json_decode($response, true);
                                    if(is_array($response)){

                                    if(isset($response['ipay_errorcode']))
                                    {
                     
                                            $mapped_error=$response['ipay_errorcode'];
                                            $mapped_error_desc=$response['ipay_errordesc'];

                                                
                                    }else if(isset($response['res_code'])){

                                        $mapped_error=$response['status'];
                                        $mapped_error_desc=$response['res_msg'];

                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }

                            $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,$chkservicestat['vendor_id']); 
            
                            if($error_mapping){


                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                           
///check status REFUND ONLY WHEN STAUS IS NOT COMING//

                     if(isset($response['status'])){
                            
                                if($response['status']=='SUCCESS')
                                {
                                    $txnstatus=$response['status'];


                                    $data['error'] = 0;
                                    $data['error_desc'] = null;
                                    $data['msg'] = $error_mapping['error_code_desc'];
                                    $data['TxnId'] = isset($response['agent_id']) ? $response['agent_id'] : '00'; 
                                    $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                    $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                    $fstpyresponsecode =$error_mapping['error_code'];
                                    $fstpayresponse =$error_mapping['error_code_desc'];
                                    $data['status'] = $txnstatus;
                                  
                                }

                                else if($response['status']=='PENDING')///Timeout
                                {
                                                        $txnstatus=$response['status'];
                                                        $data['error'] = 3;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = $error_mapping['error_code_desc'];
                                                        //$data['TxnId'] = $transid;
														 $data['TxnId'] = isset($response['agent_id']) ? $response['agent_id'] : '00';
                                                        $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                        $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';

                                                        $data['status'] =$txnstatus;


                                                        $fstpyresponsecode = $error_mapping['error_code'];
                                                        $fstpayresponse = $error_mapping['error_code_desc'];
                                                        
                                    
                                }

                                else{

                                                    $txnstatus='PENDING';
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $fstpyresponsecode = $error_mapping['error_code'];
                                                    $fstpayresponse = $error_mapping['error_code_desc'];
                                                     




                                }

                                
                            
                            }else{/// if status is not coming transaction falied
                              

                                                 $txnstatus='FAILED';


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $fstpyresponsecode = $error_mapping['error_code'];
                                                    $fstpayresponse = $error_mapping['error_code_desc'];

                                                    

                            }

                            }else{


                                                    $data['error']=3;
                                                    $data['msg']=$mapped_error_desc; 
                                                    $data['error_desc']=null;
                                                    $txnstatus='PENDING';
                                                    $error_mapping['error_code']='OUE';
                                                    $error_mapping['error_code_desc']=$data['msg'];
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $fstpyresponsecode = $error_mapping['error_code'];
                                                    $fstpayresponse = $error_mapping['error_code_desc'];

                                                    

                            }



                                 $update_dt_array = array(
                                                        "sp_id" => isset($response['ipay_id'])?$response['ipay_id']:'00',
                                                        "opr_ref_no" => isset($response['opr_id']) ? $response['opr_id'] : '00',
                                                        "sp_respcode" => $error_mapping['error_code'],
                                                        "sp_respdesc" => $error_mapping['error_code_desc'],
                                                        "sp_response" => json_encode($response),
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                                    );


                        $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);
                                    
                    }else{
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process';
                                                    $data['page'] = 'hgjgfhfhgf';
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
                                                    $fstpayresponse = 'Transaction Under Process';
                                                    $status = $data['status'];


    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Prepaid trans","response"=>"Invalid format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                    $update_dt_array = array(
                                                        "sp_id" => "00",
                                                        "opr_ref_no" => "00",
                                                        "sp_respcode" => "TMDOUT",
                                                        "sp_respdesc" => 'Timed Out',
                                                        "sp_response" => "Request Timedout from Vendor",
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => 'TUP',
                                                        "response" => "Transaction Under Process",
                                                        "status" => "PENDING",
                                                        "upd_id" => $inserted_id
                                                    );



    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                        }
                            } else {///response false 
                                                
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process';
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
												
                                                    $fstpayresponse = 'Transaction Under Process';
                                                    $status = $data['status'];




 $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Prepaid trans","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            $update_dt_array = array(
                "sp_id" => "00",
                "opr_ref_no" => "00",
                "sp_respcode" => "TMDOUT",
                "sp_respdesc" => 'Timed Out',
                "sp_response" => "Request Timedout from Vendor",
                "res_dt" => date('Y-m-d H:i:s'),
                "ind_rcode" => 'TUP',
                "response" => "Transaction Under Process",
                "status" => "PENDING",
                "upd_id" => $inserted_id
            );



    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                                    }

                                                             

                                    ///response///    
                                   

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = "Internal Processing Error, Try Again Later";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = "Insufficient Balance";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }

                        }else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Unable to find user details1';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }

                     /****No parent *****/

            } else {

                        $data['error'] = 1;
                        $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                        return $data;
            }
                
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Service not allowed, contact admin";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                }
                 } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = "Internal Processing Error, Try Again Later";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';

                                    }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = "Invalid amount";
                $data['msg'] = null;
                $data['status'] = 'FAILED';
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
            $data['status'] = 'FAILED';
        }

        return $data;
    }



    public function bbps_bill_fetch_request($user_info, $planid,$params_fields, $chkservicestat,$params,$agentcd){

        $test['user_info'] = $user_info;
        $test['planid'] = $planid;
        $test['params_fields'] = $params_fields;
        $test['chkservicestat'] = $chkservicestat;
        $test['params'] = $params;
        $test['agentcd'] = $agentcd;

        curlRequertLogs($test, 'bbps_bill_fetch_request', 'instanpayapi');

      
        // $connection_number = trim(strip_tags($params_fields[0]['value']));
        // $customer_mobile = trim(strip_tags($params_fields[1]['value']));


        $customer_mobile = trim(strip_tags($params_fields[0]['value']));
          
        // $params_fields[2]['value']= isset($params_fields[2]['value'])?$params_fields[2]['value'] :'';
        $data=array(); 

        if (ctype_digit($customer_mobile)) {
            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) {
                if ($chkservicestat['bill_fetch'] == 1) {
                    $transid = txn_remitt_txnid();
                    /***bill fetch code***/
                    foreach ($params_fields as $key => $value) {
                        $request['request']['customer_params'][$key]=$value['value'];
                        if($value['id']=='custm_mob'){
                            $request['request']['customer_mobile']=$value['value'];
                        }
                    }

                    $request['token'] = $this->token;
                    // $request['request']['sp_key'] = 'TZE';
                    $request['request']['sp_key'] = $operator_dtl['vendor_key'];
                    $request['request']['agentid']=$transid;
                    // $request['request']['customer_mobile']=$customer_mobile;
                    // $request['request']['customer_params']=[
                        
                          
                    // ];
                    $request['request']['init_channel']='AGT';
                    $request['request']['endpoint_ip']=ip_address();
                    $request['request']['mac']='AD-fg-12-78-GH';
                    $request['request']['payment_mode']='Cash';
                    $request['request']['payment_info']='bill';
                    $request['request']['amount']='10';
                    $request['request']['reference_id']='';
                    $request['request']['latlong']='24.1568,78.5263';
                    $request['request']['outletid']=$agentcd; 

                    $curl = curl_init();
                    $url = 'https://www.instantpay.in/ws/bbps/bill_fetch';
                    curl_setopt_array($curl, array(
                        CURLOPT_URL =>$url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($request), 
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                            "Accept: application/json"
                        ),
                    ));

                    $response = curl_exec($curl);  
                    curl_close($curl);

                    if ($response) {

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Bill Fetch BBPS","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                        $response = json_decode($response,TRUE);
                        curlRequertLogs($response, 'response-bbps_bill_fetch_request', 'instanpayapi');
                        if(is_array($response)){
                            if(isset($response['statuscode'])) {
                                $mapped_error=$response['statuscode'];
                                $mapped_error_desc=$response['status'];
                            }else{
                                $mapped_error='';
                                $mapped_error_desc='Unknown Error'; 
                            }
                            $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
                            if($error_mapping){
                                $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];
                                if($error_mapping['errorcode_id']=='2'){
                                    $error_mapping['error_code']=$error_mapping['error_code'];
                                    $error_mapping['error_code_desc']=$mapped_error_desc;
                                }


                                if(isset($response['statuscode'])){
                                    if($response['statuscode']=='TXN'){ 
                                        $data['error'] = 0;  
                                        $data['error_desc'] = null;
                                        $data['response'] = $response;
                                        $data['agentid']=$transid;
                                        $data['outletid']=$agentcd;
                                        $data['msg'] =$error_mapping['error_code_desc'];
                                       
                                    } elseif($response['statuscode']=='SNA'){ 
                                        $data['error'] = 1;  
                                        $data['error_desc'] = null;
                                        $data['agentid']=$transid;
                                        $data['outletid']=$agentcd;
                                        $data['msg'] =$error_mapping['error_code_desc'];
                                       
                                    }else{   
                                        $data['error'] = 1;
                                        $data['error_desc'] =$error_mapping['error_code_desc'];
                                        $data['msg'] = null; 
                                    }
                                }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                }

                            }else{
                                $data['error']=3;
                                $data['msg']='Other Unknown Error'; 
                                $data['error_desc']=null;
                            }
                        }else{
                            $data['error'] = 3;
                            $data['error_desc'] = null;
                            $data['msg'] = 'Bill Fetch Under Process';

                            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"Bill Fetch BBPS","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                        }
                    } else { 
                        $data['error'] = 1;
                        $data['error_desc'] = 'Request Timedout';
                        $data['msg'] = null;

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"Bill Fetch BBPS","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                    }
                /*******bill fetch ****/
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Bill Fetch Not Allowed';
                    $data['msg'] = null;
                }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Internal Processing Error';
                $data['msg'] = null;
            }
        }else{
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }

    return $data;

    }

    public function bbps_bill_fetch_request_old($user_info, $planid,$params_fields, $chkservicestat,$params,$agentcd){

        $test['user_info'] = $user_info;
        $test['planid'] = $planid;
        $test['params_fields'] = $params_fields;
        $test['chkservicestat'] = $chkservicestat;
        $test['params'] = $params;
        $test['agentcd'] = $agentcd;

        curlRequertLogs($test, 'bbps_bill_fetch_request', 'instanpayapi');

      
          // $connection_number = trim(strip_tags($params_fields[0]['value']));
          // $customer_mobile = trim(strip_tags($params_fields[1]['value']));


          $customer_mobile = trim(strip_tags($params_fields[0]['value']));

         // $params_fields[2]['value']= isset($params_fields[2]['value'])?$params_fields[2]['value'] :'';
          $data=array(); 

        if (ctype_digit($customer_mobile)) {
            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) {

            if ($chkservicestat['bill_fetch'] == 1) {
                
                  $transid = txn_remitt_txnid();
                /***bill fetch code***/
            
                 foreach ($params_fields as $key => $value) {

                        $request['request']['customer_params'][$key]=$value['value'];
                        if($value['id']=='custm_mob'){
                           
                            $request['request']['customer_mobile']=$value['value'];
                        }
                      
                }

                        $request['token'] = $this->token;
                       // $request['request']['sp_key'] = 'TZE';
                        $request['request']['sp_key'] = $operator_dtl['vendor_key'];
                        $request['request']['agentid']=$transid;
                        // $request['request']['customer_mobile']=$customer_mobile;
                        // $request['request']['customer_params']=[
                            
                              
                        // ];
                        $request['request']['init_channel']='AGT';
                        $request['request']['endpoint_ip']=ip_address();
                        $request['request']['mac']='AD-fg-12-78-GH';
                        $request['request']['payment_mode']='Cash';
                        $request['request']['payment_info']='bill';
                        $request['request']['amount']='10';
                        $request['request']['reference_id']='';
                        $request['request']['latlong']='24.1568,78.5263';
                        $request['request']['outletid']=$agentcd; 

                        $curl = curl_init();
                        $url = 'https://www.instantpay.in/ws/bbps/bill_fetch';
                        curl_setopt_array($curl, array(
                          CURLOPT_URL =>$url,
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                           CURLOPT_POSTFIELDS => json_encode($request), 
                          CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                           
                            "Accept: application/json"
                          ),
                        ));

                        $response = curl_exec($curl);  

                        curl_close($curl);
                      
                  

                /***bill fetch ***/
                if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Bill Fetch BBPS","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                  
                                
								
									
                                    $response = json_decode($response,TRUE);
                                
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }
									
                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){

                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                          
                                if($response['statuscode']=='TXN')
                                { 
                                 
                                  
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                     $data['response'] = $response;
                                     $data['agentid']=$transid;
                                     $data['outletid']=$agentcd;
                                    $data['msg'] =$error_mapping['error_code_desc'];
                                    //$data['data'] = isset($response['data']['mobile']) ? $response['data']['mobile'] : '';
                                    // $data['AdditionalInfo'] = isset($response['additionalInfo']) ? $response['additionalInfo'] : '';
                                   
                                } elseif($response['statuscode']=='SNA')
                                { 
                                 
									/**** NOTE : response set according to doc***/
                                  
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    // $data['response'] = $response;
                                     $data['agentid']=$transid;
                                     $data['outletid']=$agentcd;
                                    $data['msg'] =$error_mapping['error_code_desc'];
                                    //$data['data'] = isset($response['data']['mobile']) ? $response['data']['mobile'] : '';
                                    // $data['AdditionalInfo'] = isset($response['additionalInfo']) ? $response['additionalInfo'] : '';
                                   
                                }else{   
								 // print_r('aaaa');
								//print_r($response);exit;
								
							

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null; 
									
									
                                    
                                }

                                
                            
                            }else{

                                               


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{
							
                                 
								$data['error']=3;
								$data['msg']='Other Unknown Error'; 
                                $data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Bill Fetch Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"Bill Fetch BBPS","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"Bill Fetch BBPS","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   }
                /*******bill fetch ****/

             } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Bill Fetch Not Allowed';
                $data['msg'] = null;
            }

            } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Internal Processing Error';
            $data['msg'] = null;
        }

        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }

        return $data;

    }


    public function request_outlet_otp($uid,$mobile){
    $mobile = trim(strip_tags($mobile));
          $data=array(); 
        if (ctype_digit($mobile) && strlen($mobile) == 10) {

            $request = array();
            $request['token'] = $this->token;
           
            $request['request']['mobile']=$mobile;
            $url = "https://www.instantpay.in/ws/outlet/registrationOTP";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 90,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
          
            if ($response) {
                   
                $insert_array=array("user_id"=>$uid,"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Request for Outlet","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                $xml = simplexml_load_string($response);
                $json = json_encode($xml);
                $response = json_decode($json,true);

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }
               // else if(isset($response['res_code'])){

                                    //     $mapped_error=$response['status'];
                                    //     $mapped_error_desc=$response['res_msg'];

                                    // }
                                    else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }

                            $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8); 
             
                            if($error_mapping){

                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                     if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
                                    
   
                                  // print_r($response);exit;
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                     $data['response'] = $response;

                                  
                                

                                 
                                    $data['msg'] =$error_mapping['error_code_desc'];
                                    //$data['data'] = isset($response['data']['mobile']) ? $response['data']['mobile'] : '';
                                    // $data['AdditionalInfo'] = isset($response['additionalInfo']) ? $response['additionalInfo'] : '';
                                   
                                }else{

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{

                                               


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                                
								$data['error']=3;
                                $data['msg']='Other Unknown Error'; 
                                $data['error_desc']=null;

                            }



                
                    
            } else {

                $data['error'] = 1;
                $data['error_desc'] = 'Request Timedout';
                $data['msg'] = null;

                 $insert_array=array("user_id"=>$uid,"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Request for Outlet","response"=>'Request Timed Out',"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
            }

                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Invalid Mobile Number';
                    $data['msg'] = null;
                }
                return $data;

    }

    public function request_outlet_verification($uid,$param){
        $data = array();
        $mobile = isset($param['nmbr']) ? $param['nmbr'] : '';
        $mobile = trim(strip_tags($mobile));
        $fname = isset($param['fname']) ? $param['fname'] : '';
        $fname = trim(strip_tags($fname));
        $lname = isset($param['lname']) ? $param['lname'] : '';
        $lname = trim(strip_tags($lname));
        $business_name = isset($param['business_name']) ? $param['business_name'] : '';
        $business_name = trim(strip_tags($business_name));
        $shopaddr = isset($param['shopaddr']) ? $param['shopaddr'] : '';
        $shopaddr = trim(strip_tags($shopaddr));
        $pan = isset($param['pan']) ? $param['pan'] : '';
        $pan = trim(strip_tags($pan));
        $email = isset($param['email']) ? $param['email'] : '';
        $email = trim(strip_tags($email));
        $pincode = isset($param['pincode']) ? $param['pincode'] : '';
        $pincode = trim(strip_tags($pincode));
        $otp = isset($param['otp']) ? $param['otp'] : '';
        $otp = trim(strip_tags($otp));

        if (preg_match('/^[A-Za-z ]+$/', $fname)) {

            if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {

                if (preg_match('/^[A-Za-z0-9 -]+$/', $business_name)) {

                    if (preg_match('/^[a-zA-Z0-9 !@#$&()-`.+,\"]*$/', $shopaddr)) {

                        if (preg_match('/^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/', $pan)) {

                            if (preg_match('/^\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*$/', $email)) {

                                if (preg_match('/^\d{6}$/', $pincode)) {

                                    if (preg_match('/^\d{6}$/', $otp)) {

                                            $request = array();
                                            $request['token'] = $this->token;
                                           
                                            $request['request']['mobile']=$mobile;
                                            $request['request']['email']=$email;
                                            $request['request']['company']=$business_name;
                                            $request['request']['name']=$fname;
                                            $request['request']['pan']=$pan;
                                            $request['request']['pincode']=$pincode;
                                            $request['request']['address']=$shopaddr;
                                            $request['request']['otp']=$otp;

                                            $url = "https://www.instantpay.in/ws/outlet/registration";

                                            $curl = curl_init();
                                            curl_setopt_array($curl, array(
                                                CURLOPT_URL => $url,
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => "",
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 90,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => "POST",
                                                CURLOPT_POSTFIELDS => json_encode($request),
                                                CURLOPT_HTTPHEADER => array(
                                                    "content-type: application/json",
                                                ),
                                            ));

                                            $msg = curl_exec($curl);
                                            $err = curl_error($curl);

                                            curl_close($curl);

                                        if ($msg) {
                                            // $log = $this->_ci->Vndr_model->init_log($uid, $this->ipchk, $url . ' post: ' . json_encode($request), $msg, 'Request for Outlet');

                                             $insert_array=array("user_id"=>$uid,"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Request for Outlet","response"=>($msg),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                                              $xml = simplexml_load_string($msg);
                                            $json = json_encode($xml);
                                            $msg = json_decode($json,true);

                                            
                                            if ($msg) {
                                                $response = $msg;

                                                    if(isset($response['statuscode']))
                                                    {
                                     
                                                            $mapped_error=$response['statuscode'];
                                                            $mapped_error_desc=$response['status'];

                                                            
                                                    }
                             
                                                    else{

                                                        $mapped_error='';
                                                        $mapped_error_desc='Unknown Error'; 

                                                    }

                                                   $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8); 
             


                                                //$maperrorcode = $this->_ci->Vndr_model->fetch_error_code($response['statuscode'], 'API');

                                                if ($error_mapping) {
                                                    $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                                                    if($error_mapping['errorcode_id']=='2')
                                                        {
                                                            

                                                            $error_mapping['error_code']=$error_mapping['error_code'];
                                                            $error_mapping['error_code_desc']=$mapped_error_desc;
                                                           

                                                        }


                                                if(isset($response['statuscode'])){
                                                            
                                                                if($response['statuscode']=='TXN')
                                                                {
                                                                    
                                   
                                                                  // print_r($response);exit;
                                                                    $data['error'] = 0;  
                                                                    $data['error_desc'] = null;
                                                                     $data['response'] = $response;
                                                                    $data['msg'] =$error_mapping['error_code_desc'];
                                                                    $data['outlet_id'] = isset($response['data']['outlet_id']) ? $response['data']['outlet_id'] : '';
                                                                    
                                                                   
                                                                }else{

                                                                    $data['error'] = 1;
                                                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                                                    $data['msg'] = null;
                                                                    
                                                                }

                                                                
                                                            
                                                    }else{

                                                                               
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = $error_mapping['error_code_desc'];
                                                        $data['msg'] = null;
                                                                                  

                                                    }
                                                    
                                                } else {

                                                   
													$data['error']=3;
													$data['msg']='Other Unknown Error'; 
													$data['error_desc']=null;
                                                }

                                                // if ($response['statuscode'] == 'RCS') {

                                                //     $a = $this->automatic_outlet_activate($response['OutletID'], $uid);
                                                //     if ($a) {
                                                //         $data['error'] = 0;
                                                //         $data['error_desc'] = null;
                                                //         $data['outlet_id'] = $response['OutletID'];
                                                //         $data['msg'] = 'OTP Sent Successfully';
                                                //     } else {
                                                //         $data['error'] = 1;
                                                //         $data['error_desc'] = 'Something went wrong';
                                                //         $data['msg'] = null;
                                                //     }
                                                // } else {

                                                //     $data['error'] = 1;
                                                //     $data['error_desc'] = $maperrorcode['gcg_error_desc'];
                                                //     $data['msg'] = null;
                                                // }
                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Something went wrong';
                                                $data['msg'] = null;


                                               

                                                 $insert_array=array("user_id"=>$uid,"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Request for Outlet","response"=>'Invalid json decoded',"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Request Timedout';
                                            $data['msg'] = null;

                                             $insert_array=array("user_id"=>$uid,"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Request for Outlet","response"=>'Request Timed Out',"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Please enter valid OTP';
                                        $data['msg'] = null;
                                    }
                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Please enter a valid Pincode';
                                    $data['msg'] = null;
                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Please enter valid Aadhaar number';
                                $data['msg'] = null;
                            }
                        } else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Please enter a valid Pan';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Please enter a valid Shop Address';
                        $data['msg'] = null;
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Please enter a valid Shop Name';
                    $data['msg'] = null;
                }
            } else {

                $data['error'] = 1;
                $data['error_desc'] = 'Please enter a valid mobile';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Please enter a valid name';
            $data['msg'] = null;
        }

        return $data;
    }


   public function request_outlet_kyc($user_info, $check_outlet_id){
     $data = array();
        $outlet_id = isset($check_outlet_id['agent_code']) ? $check_outlet_id['agent_code'] : '';
        $outlet_id = trim(strip_tags($outlet_id));
        $pan_numbr = isset($user_info['pan']) ? $user_info['pan'] : '';
        $pan_numbr = trim(strip_tags($pan_numbr));
        if ($outlet_id) {
        if (preg_match('/^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/', $pan_numbr)) {
        $request['token'] = $this->token;
     
      
        $request['request']['outletid'] = $outlet_id;
        $request['request']['pan_no']=$pan_numbr;
         $url ="https://www.instantpay.in/ws/outlet/requiredDocs";

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
           if ($response) {
           
            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Request for KYC Documents","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

            $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            $msg = json_decode($response, true);

            // if (($msg['statuscode'])) {
            //     if ($msg['statuscode'] == 'TXN') {
                                                if(isset($response['statuscode']))
                                                    {
                                     
                                                            $mapped_error=$response['statuscode'];
                                                            $mapped_error_desc=$response['status'];

                                                            
                                                    }else{

                                                        $mapped_error='';
                                                        $mapped_error_desc='Unknown Error'; 

                                                    }

                    $maperrorcode = $this->_ci->Inst_model->fetch_error_code($mapped_error, 8);
                    if ($maperrorcode) {
                        $maperrorcode['error_code_desc'] = $maperrorcode['errorcode_id'] == 2 ? $mapped_error_desc : $maperrorcode['error_code_desc'];

                     if(isset($response['status'])){
                   
                        if ($msg['status'] == "Transaction Successful") {
                            // print_r($msg['data']);exit;
                            if (isset($msg['data'])) {
                               
                                     $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $msg['data'];
                                    $data['user_dtl'] =$user_info;
                                   
                               
                                // if(isset($msg['data']['REQUIRED'])){
                                //     // print_r($msg['data']['REQUIRED']);exit;
                                //     // $data['error'] = 0;  
                                //     // $data['error_desc'] = null;
                                //     // $data['response'] = $response;
                                //     // $data['msg'] = 'Document Need to upload';
                                //     $data_arry=$msg['data']['REQUIRED'];
                                //     foreach ($data_arry as $sts_data) {  
                                        
                                //     if ($sts_data['2'] == 'MANDATORY') {

                                //     $data['error'] = 0;  
                                //     $data['error_desc'] = null;
                                //     $data['response'] = $response;
                                //     $data['msg'] = 'Document Need to upload'; 

                                //     }

                                //     }


                                // }else if(isset($msg['data']['SCREENING'])){

                                //     $data['error'] = 0;  
                                //     $data['error_desc'] = null;
                                //     $data['response'] = $response;
                                //     $data['msg'] = 'Documents have been uploaded and are pending for approval';

                                // }else{


                                //     $data['error'] = 0;  
                                //     $data['error_desc'] = null;
                                //     $data['response'] = $response;
                                //     $data['msg'] = 'No action required';

                                // }
                                
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = $maperrorcode['error_code_desc'];
                                $data['msg'] = NULL;
                            }

                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = $maperrorcode['error_code_desc'];
                            $data['msg'] = NULL;
                        }

                          } else {
                            $data['error'] = 1;
                            $data['error_desc'] = $maperrorcode['error_code_desc'];
                            $data['msg'] = NULL;
                        }



                    } else {
                        /* $maperrorcode['error_code'] = 'OUE';
                        $maperrorcode['error_code_desc'] = 'Other Unknown Error';
                        $data['error'] = 1;
                        $data['error_desc'] = $maperrorcode['error_code_desc'];
                        $data['msg'] = NULL; */
						
						
						$data['error']=3;
						$data['msg']='Other Unknown Error'; 
						$data['error_desc']=null;
                    }
            //     } else {

            //         $data['error'] = 1;
            //         $data['error_desc'] = 'Something went wrong';
            //         $data['msg'] = null;
            //     }
            // } else {
            //     $data['error'] = 1;
            //     $data['error_desc'] = 'Something went wrong';
            //     $data['msg'] = null;
            // }
        } else {

            $data['error'] = 1;
            $data['error_desc'] = 'Request Timedout';
            $data['msg'] = null;

           
            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Request for KYC Documents","response"=>'Request Timeout',"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
        }

        } else {

                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Pan Number';
                $data['msg'] = null;
        }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Outlet ID';
            $data['msg'] = null;
        }

        return $data;
   }

   public function request_fr_Upload_Doc($user_info, $id,$file_path,$check_outlet_id){
        $data = array();
        $outlet_id = isset($check_outlet_id['agent_code']) ? $check_outlet_id['agent_code'] : '';
        $outlet_id = trim(strip_tags($outlet_id));
        $pan_numbr = isset($user_info['pan']) ? $user_info['pan'] : '';
        $pan_numbr = trim(strip_tags($pan_numbr));
        $file_path=trim(strip_tags($file_path));
        if ($outlet_id) {
        if (preg_match('/^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/', $pan_numbr)) {
        $request['token'] = $this->token;
        $request['request']['outletid'] = $outlet_id;
        $request['request']['pan_no']=$pan_numbr;
        $request['request']['document']['id']=$id;
        $request['request']['document']['link']=$file_path;
        $request['request']['document']['filename']="as.jpeg";
        $url ="https://www.instantpay.in/ws/outlet/uploadDocs";
        $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
          ));

        $response = curl_exec($curl);  

        curl_close($curl);
          
        if ($response) {
           
            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Request for KYC Documents Upload","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

            $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            $msg = json_decode($response, true);
           
            // if (isset($msg['statuscode'])) {
            //     if ($msg['statuscode'] == 'TXN') {
            if(isset($response['statuscode'])){
                                     
                $mapped_error=$response['statuscode'];
                $mapped_error_desc=$response['status'];

                                                            
            }else{

                    $mapped_error='';
                    $mapped_error_desc='Unknown Error'; 

            }
            $maperrorcode = $this->_ci->Inst_model->fetch_error_code($mapped_error, 8);

            if ($maperrorcode) {

            $maperrorcode['error_code_desc'] = $maperrorcode['errorcode_id'] == 2 ? $mapped_error_desc : $maperrorcode['error_code_desc'];
            if (isset($msg['status'])){
            if ($msg['status'] == "Transaction Successful") {
            // print_r($msg['data']);exit;
            if (isset($msg['data'])) {
                               
                    $data['error'] = 0;  
                    $data['error_desc'] = null;
                    $data['msg'] ='Document Uploaded Sucessfully';
                                   
            } else {

                    $data['error'] = 1;
                    $data['error_desc'] = $maperrorcode['error_code_desc'];
                    $data['msg'] = NULL;
            }

            } else {

                    $data['error'] = 1;
                    $data['error_desc'] = $maperrorcode['error_code_desc'];
                    $data['msg'] = NULL;
            }
            } else {

                    $data['error'] = 1;
                    $data['error_desc'] = $maperrorcode['error_code_desc'];
                    $data['msg'] = NULL;
            }

            } else {
					/* 
                    $maperrorcode['error_code'] = 'OUE';
                    $maperrorcode['error_code_desc'] = 'Other Unknown Error';
                    $data['error'] = 1;
                    $data['error_desc'] = $maperrorcode['error_code_desc'];
                    $data['msg'] = NULL; */
					
					$data['error']=3;
					$data['msg']='Other Unknown Error'; 
					$data['error_desc']=null;
            }
            //     } else {

            //         $data['error'] = 1;
            //         $data['error_desc'] = 'Something went wrong';
            //         $data['msg'] = null;
            //     }
            // } else {
            //     $data['error'] = 1;
            //     $data['error_desc'] = 'Something went wrong';
            //     $data['msg'] = null;
            // }
        } else {

            $data['error'] = 1;
            $data['error_desc'] = 'Request Timedout';
            $data['msg'] = null;

           
            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Request for KYC Documents Upload","response"=>'Request Timeout',"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
        }

        } else {

                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Pan Number';
                $data['msg'] = null;
        }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Outlet ID';
            $data['msg'] = null;
        }

    return $data;
   }

public function bbps_bill_payment_request($user_info, $plan_id,$filelds,$chkservicestat,$parameters,$outletid){
    
    $latitude = $parameters['lati'];
    $longitude = $parameters['longi'];
    $outletId = trim(strip_tags($outletid));
    $spkey = trim(strip_tags($parameters['spkey']));
    $agentid =isset($parameters['AgentId'])?$parameters['AgentId']:txn_remitt_txnid();
    $bill_mode=trim(strip_tags($parameters['bill_mode']));
    $amnt = trim(strip_tags($parameters['total_pybl'])); 
    
    if($chkservicestat['bill_fetch']==1){

        $reference_id = trim(strip_tags($parameters['reference_id']));

    }else{

        $reference_id = '';
    }

    $data=array();   
    
    if($agentid){
        if(($amnt)) {
            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) {

                $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$plan_id,$chkservicestat['service_id']);
                    if($chck_user_pln_dtl){
                        if ($chkservicestat['billing_model']) {
                            if ($chkservicestat['billing_model'] == 'P2P'){
                                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                                    if ($chck_user_pln_dtl['slab_applicable'] == 1) {


                              
                                        $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);
                                   // print_r($chck_agrd_fee_rng);
                                        if ($chck_agrd_fee_rng){

                                            if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                                $rate = $chck_agrd_fee_rng['rate']; //rate=7
                                          

                                            } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                                $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                                $rate = ($rate_in_prcmt * $amnt) / 100;

                                                $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                                $rate = round($rate, 2);

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }

                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Rate configuration issue,contact admin';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                   

                                    }else {
                                        /*************** slab not applicable*********** */

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                            $rate = $chck_user_pln_dtl['rate'];

                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);

                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                   
                                    } 

                                }else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate configuration issue, contact admin.1';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }

                            }elseif ($chkservicestat['billing_model'] == 'P2A') {
                //P2A//
                                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                                    if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                        $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);
                                        curlRequertLogs($chck_agrd_fee_rng, 'chck_agrd_fee_rng', 'instanpayapi');
                                        if ($chck_agrd_fee_rng) {

                                            if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                                $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                            } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {
                                                $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                                $rate = ($rate_in_prcmt * $amnt) / 100;

                                                $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                                $rate = round($rate, 2);
                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Rate configuration issue,contact admin .11111';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                            $rate = $chck_user_pln_dtl['rate'];
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    }
                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }

                 //P2A//   

                            }elseif ($chkservicestat['billing_model'] == 'CHARGE') {
                                //Charge//
                                if ($chck_user_pln_dtl['charge_method'] == 'DEBIT') {
                                    if ($chck_user_pln_dtl['slab_applicable'] == 1) {

                                        $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                        if ($chck_agrd_fee_rng) {

                                            if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                                $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                            } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                                $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                                $rate = ($rate_in_prcmt * $amnt) / 100;

                                                $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                                $rate = round($rate, 2);
                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Rate configuration issue,contact admin ..0909';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                            $rate = $chck_user_pln_dtl['rate']; //rate=11
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    }
                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate configuration issue, contact admin.2343';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }

                    //Charge//


                            }else{
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue, contact admin.222';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                            }

                            /*******calculation of base,appl comm, gst **********/
                            if($chkservicestat['billing_model'] == 'P2P') {

                                $amount = $amnt;
                                $applicable_commission = $rate; ///(Applicable GST)//2
                                $base_applicable_commisison = $applicable_commission;
                                $gstamount_tobecredited = 0;
                                $tdsamount = (($applicable_commission * 5) / 100);
                                $tdsamount = round($tdsamount, 2);
                                $charged_amount = $amount;
                            

                            } elseif ($chkservicestat['billing_model'] == 'P2A') {  

                                $amount = $amnt;
                                $applicable_commission = $rate; ///(Applicable GST)//2
                                $charged_amount = $amount;
                                $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                $base_applicable_commisison = round($base_applicable_commisison, 2);
                                $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                $tdsamount = (($base_applicable_commisison * 5) / 100);
                                $tdsamount = round($tdsamount, 2);
                            
                            } elseif ($chkservicestat['billing_model'] == 'CHARGE') {

                                $amount = $amnt;
                                $applicable_commission = 0; ///(Applicable GST)//2
                                $base_applicable_commisison = 0;
                                $gstamount_tobecredited = ($rate - (($rate / 118) * 100));
                                $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                $tdsamount = 0;

                                $charged_amount = $amount + $rate;


                            } else {


                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                        /*****end gst base appl comm****/
                            $common_array=array();
                            /*******calculation of base gst ***********/
                            if ($user_info['parent_id'] != '0') {
                                $get_retailer_parent_info = $this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                                if ($get_retailer_parent_info) {


                                    foreach ($get_retailer_parent_info as $key => $value) {
                               
                                        if($value['role_id']==2 || $value['role_id']==3 ){
                                            // print_r($value['user_id']);echo "<br>";

                                            $checkusercustomprice_parnt = $this->_ci->Inst_model->checkuser_pln_dtl($value['role_id'],$value['plan_id'],$chkservicestat['service_id']);
                                               
                                            if($checkusercustomprice_parnt){
                                                $prnt_capng_nrmlamnt=$checkusercustomprice_parnt['capping_amount'];
                                                if($checkusercustomprice_parnt['slab_applicable']==1){
                                                    $chck_agrd_fee_rng_fr_prnt=$this->_ci->Inst_model->check_pln_amnt_rng($checkusercustomprice_parnt['pl_srvc_rl_id'],$checkusercustomprice_parnt['plan_id'],$checkusercustomprice_parnt['service_id'],$amount);

                                                    if($chck_agrd_fee_rng_fr_prnt){
                               
                         
                                                        $parentplan_rate=$chck_agrd_fee_rng_fr_prnt['rate'];//rate=11

                                                    }else{

                                                                 $data['error']=1;
                                                                 $data['error_desc']='Rate configuration issue,contact admin 00';
                                                                 $data['msg']=null;
                                                                 return $data;
                                                    }

                                                }else{

                                                    $parentplan_rate=$checkusercustomprice_parnt['rate'];


                                                }
                               
                                                if ($checkusercustomprice_parnt['charge_method'] == 'CREDIT' && ($checkusercustomprice_parnt['charge_type'] == 'FIXED' || $checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE')) {

                                                    if ($checkusercustomprice_parnt['charge_type'] == 'FIXED') {

                                                        /* * 1> txn amount=$amount
                                                      2> ccf=1%of txn amnt
                                                      ccf = (1%of txn amnt) OR Rs 10(gtr ccf consider)
                                                      3> base of ttl ccf(net ccf bank)= (ccf/118)*100
                                                      4> app_comm=net ccf bank -srvc_rate
                                                      5> base_commsn =(app_comm/118)*100
                                                      6> gst of comm= app_comm- base_commsn
                                                      7> 5% TDS on base_commsn=base_commsn*5/100
                                                     * */
                                                    /** $rate_nrmlprnt=Rs 1** */
                                                        $commission_nrmlprnt_amt = $parentplan_rate;

                                                        $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                        $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                        
                                                        if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                            $base_applicable_commisison_fr_nrmlprnt = ($applicable_commission_nrmlprnt/118)*100;
                                                            $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                        } else {

                                                            $data['error'] = 1;
                                                            $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                            $data['msg'] = null;
                                                            $data['status'] = 'FAILED';
                                                            return $data;
                                                        }
                                                    } else if($checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE') {

                                                        $commission_nrmlprnt_amt = (($parentplan_rate) / 100) * $amnt;

                                                        $commission_nrmlprnt_amt = ($commission_nrmlprnt_amt >= $prnt_capng_nrmlamnt) ? $prnt_capng_nrmlamnt : $commission_nrmlprnt_amt;

                                                        $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                        $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                        if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                        

                                                            $base_applicable_commisison_fr_nrmlprnt = ($commission_nrmlprnt_amt/118)*100;
                                                            $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                        } else {

                                                            $data['error'] = 1;
                                                            $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                            $data['msg'] = null;
                                                            $data['status'] = 'FAILED';
                                                            return $data;
                                                        }

                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }
                                                }else{

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'configuration issue,contact admin 99';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }/////charge_method CREDIT charge_type FIXED PERCENT//

                                            }else{

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'No Profile for parent';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                            }

                                        }

                                    }//end foreach loop


                                } else {


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find parent details';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }
                            }/***$user_info['parent_id'] != '0'***/
                              /*****No parent *****/

                            $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);

                            if ($findbalance) {

                                $openingbal = $findbalance['rupee_balance'];

                                $closing_balance = $openingbal - $charged_amount;

                                $identify_commision_from = $findbalance['first_name'] .' '. $findbalance['first_name'] . ' ( ' . $findbalance['mobile'] . ' )';


                                if ($openingbal >= $charged_amount && $closing_balance >= 0 && $charged_amount != null) {


                                    $name='';
                                    foreach ($filelds as $key => $value) {

                                        $name.=$value['name'].' - '.$value['value'];

                                        if($key!=count($filelds)-1){

                                            $name.=', ';

                                        }
                                            
                                         
                                    }

                         
                                    $RetailerTxnEntry = array(
                                        "user_id"=>$findbalance['user_id'],
                                        "req_ip"=>ip_address(),
                                        "request_useragent "=>$this->_ci->agent->agent_string(),
                                        "fstpytxn_id"=>$agentid,
                                        "sp_id"=>$reference_id,
                                        "opr_ref_no"=>"00",
                                        'customer_no' => $filelds[0]['value'],///dynamic//
                                        'scode' => $chkservicestat['code'],
                                        "servicename"=>$chkservicestat['service_name'],
                                        "servicetype"=>$chkservicestat['type'],
                                        "servedby"=>$chkservicestat['served_by'],
                                        "transamt"=>$amount,
                                        "chargeamt"=>$charged_amount,
                                        "openingbal"=>$openingbal,
                                        "closingbal"=>$closing_balance,
                                        "req_dt"=>date('Y-m-d H:i:s'),
                                        "res_dt"=>"0000-00-00 00:00:00",
                                        "ind_rcode"=>'TUP',
                                        "response"=>"Recharge Under Process",
                                        "status"=>"PENDING",
                                        // "op1"=>$parameters['Details']['custm_mob'],
                                        "op1"=>$parameters['custm_mob'], ///**** NOTE : by removing customer num by ui****/
                                        'op2'=>$name
                          
                                    );

                                    if (($chkservicestat['billing_model'] == 'P2P') || ($chkservicestat['billing_model'] == 'P2A')) {
                                        $CreditHistroyIdCashback = ch_txnid();
                                        $comm_openingbal = $closing_balance;
                                        $basecomm_amount = $base_applicable_commisison;
                                        $comm_closingbal = $comm_openingbal + $basecomm_amount;


                                        $CashbackCrdtEntry_reatiler = array(
                                            'credit_txnid' => $CreditHistroyIdCashback,
                                            'user_id' => $findbalance['user_id'],
                                            'bank_name' => 'N/A',
                                            'txn_type' => 'CASHBACK',
                                            'payment_mode' => 'WALLET',
                                            'amount' => $basecomm_amount,
                                            'opening_balance' => $comm_openingbal,
                                            'closing_balance' => $comm_closingbal,
                                            'updated_on' => date('Y-m-d H:i:s'),
                                            'reference_number' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                            'remarks' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                            'txn_code' => $agentid,
                                            'status' => 'CREDIT',
                                            'updated_by' => $findbalance['user_id'],
                                        );


                                        $CreditHistroyTDSId = ch_txnid();
                                        $Tds_opng_bal = $comm_closingbal;
                                        $RetTds = $tdsamount;
                                        $Tds_clsng_bal = $Tds_opng_bal - $RetTds;

                                        $TDSCreditHistoryEntryRetailer = array(
                                            'credit_txnid' => $CreditHistroyTDSId,
                                            'user_id' => $findbalance['user_id'],
                                            'bank_name' => 'N/A',
                                            'txn_type' => 'TDS',
                                            'payment_mode' => 'WALLET',
                                            'amount' => $RetTds,
                                            'opening_balance' => $Tds_opng_bal,
                                            'closing_balance' => $Tds_clsng_bal,
                                            'updated_on' => date('Y-m-d H:i:s'),
                                            'reference_number' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                            'remarks' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                            'txn_code' => $agentid,
                                            'status' => 'DEBIT',
                                            'updated_by' => $findbalance['user_id'],
                                        );

                                        $TaxRecordRet = array(
                                            'user_id' => $findbalance['user_id'],
                                            'cbrt_id' => $agentid,
                                            'billing_model' => $chkservicestat['billing_model'],
                                            'trans_amt' => $amount,
                                            'charged_amt' => $charged_amount,
                                            'comm_amnt' => $base_applicable_commisison,
                                            'tds_amnt' => $tdsamount,
                                            'gst_amnt' => $gstamount_tobecredited,
                                            'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
                                            'tds_status' => 'PENDING',
                                            'tax_type' => 'CREDIT',
                                            'created_dt' => date('Y-m-d H:i:s'),
                                            'updated_by' => $findbalance['user_id'],
                                            'updated_dt' => date('Y-m-d H:i:')
                                        );

                                    } else {

                                        $TaxRecordRet = array(
                                            'user_id' => $findbalance['user_id'],
                                            'cbrt_id' => $agentid,
                                            'billing_model' => $chkservicestat['billing_model'],
                                            'trans_amt' => $amount,
                                            'charged_amt' => $charged_amount,
                                            'comm_amnt' => $base_applicable_commisison,
                                            'tds_amnt' => $tdsamount,
                                            'gst_amnt' => $gstamount_tobecredited,
                                            'gst_status' => 'PENDING',
                                            'tds_status' => 'PENDING',
                                            'tax_type' => 'CREDIT',
                                            'created_dt' => date('Y-m-d H:i:s'),
                                            'updated_by' => $findbalance['user_id'],
                                            'updated_dt' => date('Y-m-d H:i:')
                                        );

                                        $CashbackCrdtEntry_reatiler = array();
                                        $TDSCreditHistoryEntryRetailer = array();
                                    }

                                    $RetailerInsertEntry = $this->_ci->Inst_model->RetailerAllEntryWithoutAdmin($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet);

                                    if ($RetailerInsertEntry) {
                                        $inserted_id = $RetailerInsertEntry;
                                    
                                        $ParentArray = array();
                                   
                                        if ($findbalance['parent_id'] != 0) {
                                       
                                            if (count($common_array) > 0) {////sup dist/ dist of retailer
                                                foreach ($common_array as $k => $v) {

                                                    $ParentInfo = $this->_ci->Inst_model->user_info($v['USERID']);

                                                    if ($ParentInfo) {

                                                        $parent_opening = $ParentInfo['rupee_balance'];
                                                        $parent_closeing = $parent_opening + $v['BASCOMM'];
                                                        $ParentArray[$k]['COM'] = array(
                                                            'credit_txnid' => ch_txnid(),
                                                            'user_id' => $ParentInfo['user_id'],
                                                            'bank_name' => 'N/A',
                                                            'txn_type' => 'COMMISSION',
                                                            'payment_mode' => 'WALLET',
                                                            'amount' => $v['BASCOMM'],
                                                            'opening_balance' => $parent_opening,
                                                            'closing_balance' => $parent_closeing,
                                                            'updated_on' => date('Y-m-d H:i:s'),
                                                            'reference_number' => 'Commission From ' . $identify_commision_from . ' For ' . $agentid,
                                                            'remarks' => 'Commission From ' . $identify_commision_from . ' For ' . $agentid . ',Transaction Amount : Rs. ' . $amount,
                                                            'txn_code' => $agentid,
                                                            'status' => 'CREDIT',
                                                            'updated_by' => $ParentInfo['user_id']
                                                        );


                                                        $parent_opening_tds = $parent_closeing;
                                                        $parent_closing_tds = $parent_opening_tds - $v['TDS'];

                                                        $ParentArray[$k]['TDS'] = array(
                                                            'credit_txnid' => ch_txnid(),
                                                            'user_id' => $ParentInfo['user_id'],
                                                            'bank_name' => 'N/A',
                                                            'txn_type' => 'TDS',
                                                            'payment_mode' => 'WALLET',
                                                            'amount' => $v['TDS'],
                                                            'opening_balance' => $parent_opening_tds,
                                                            'closing_balance' => $parent_closing_tds,
                                                            'updated_on' => date('Y-m-d H:i:s'),
                                                            'reference_number' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $agentid . ', Transaction Amount : Rs. ' . $amount,
                                                            'remarks' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $agentid . ', Transaction Amount : Rs. ' . $amount,
                                                            'txn_code' => $agentid,
                                                            'status' => 'DEBIT',
                                                            'updated_by' => $ParentInfo['user_id']
                                                        );

                                                        $ParentArray[$k]['TAX'] = array(
                                                            'user_id' => $v['USERID'],
                                                            'cbrt_id' => $agentid,
                                                            'billing_model' => 'P2A',
                                                            'trans_amt' => $amount,
                                                            'charged_amt' => $charged_amount,
                                                            'comm_amnt' => $v['BASCOMM'],
                                                            'tds_amnt' => $v['TDS'],
                                                            'gst_amnt' => $v['GST'],
                                                            'gst_status' => 'PENDING',
                                                            'tds_status' => 'PENDING',
                                                            'tax_type' => 'CREDIT',
                                                            'created_dt' => date('Y-m-d H:i:s'),
                                                            'updated_by' => $ParentInfo['user_id'],
                                                            'updated_dt' => date('Y-m-s H:i:s')
                                                        );
                                                    }
                                                }
                                            }/** *count($common_array) end** */


                                      
                                            $parent_comission = $this->_ci->Inst_model->parent_commission_without_Admin($ParentArray);

                                        
                                        }///$findbalance['parent_id'] != 0
                                    
                               
                                        $lat = explode('.', $latitude);
                                        $latitude = $lat[0] . '.' . substr($lat[1], 0, 4);
                                        $lon = explode('.', $longitude);
                                        $longitude = $lon[0] . '.' . substr($lon[1], 0, 4);
                                        // $request['token'] =$this->token;
                                        // $request['request']['sp_key'] = $chkservicestat['code'];
                                        // $request['request']['agentid']=$agentid;
                                        // $request['request']['customer_mobile']=$parameters['Details']['custm_mob'];
                                        // $request['request']['customer_params']=[
                                        //      $parameters['Details']['mobileno'],
                                        //       "{{customer_param2}}"
                                        // ];
                                        // $request['request']['init_channel']='AGT';
                                        // $request['request']['endpoint_ip']=ip_address();
                                        // $request['request']['mac']='AD-fg-12-78-GH';
                                        // $request['request']['payment_mode']='Cash';
                                        // $request['request']['payment_info']='bill';

                                        // $request['request']['amount']=$amnt;
                                        // $request['request']['reference_id']=$reference_id;
                                        // $request['request']['latlong']=' . $latitude . ',' . $longitude . ';

                                        // $request['request']['outletid']=$outletId;

                                        // $curl = curl_init();
                                        // curl_setopt_array($curl, array(
                                        //   CURLOPT_URL => "https://www.instantpay.in/ws/bbps/bill_pay",
                                        //   CURLOPT_RETURNTRANSFER => true,
                                        //   CURLOPT_ENCODING => "",
                                        //   CURLOPT_MAXREDIRS => 10,
                                        //   CURLOPT_TIMEOUT => 0,
                                        //   CURLOPT_FOLLOWLOCATION => true,
                                        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        //   CURLOPT_CUSTOMREQUEST => "POST",
                                        //    CURLOPT_POSTFIELDS => json_encode($request), 
                                        //   CURLOPT_HTTPHEADER => array(
                                        //     "Content-Type: application/json",
                                           
                                        //     "Accept: application/json"
                                        //   ),
                                        // ));

                                        // $response = curl_exec($curl);  

                                        // curl_close($curl);

                                        foreach ($filelds as $key => $value) {

                                            $request['request']['customer_params'][$key]=$value['value'];
                                                /**** NOTE: custm_mob*****/
/*                                                 if($value['id']=='custm_mob'){
                                                   
                                                    $request['request']['customer_mobile']=$value['value'];
                                                } */
                                              
                                        }
                                        
                                        
                                        /**** NOTE: custm_mob*****/
                                               // if($value['id']=='custm_mob'){
                                                   
                                                    $request['request']['customer_mobile']=$parameters['custm_mob'];
                                               // }


                                        $request['token'] = '19b6aaa0623d1a724d9ba7691652dbb8';
                                        $request['request']['sp_key'] =$operator_dtl['vendor_key'];
                                        $request['request']['agentid']=$agentid;
                                       // $request['request']['customer_mobile']=$parameters['Details']['custm_mob'];
                                        // $request['request']['customer_params']=[
                                        //       $parameters['Details']['mobileno'],

                                        //       // "{{customer_param2}}"
                                        // ];
                                        $request['request']['init_channel']='AGT';
                                        $request['request']['endpoint_ip']='122.176.102.215';
                                        $request['request']['mac']='AD-fg-12-78-GH';
                                        $request['request']['payment_mode']='Cash';
                                        $request['request']['payment_info']='bill';

                                        $request['request']['amount']='10';
                                        $request['request']['reference_id']=$reference_id;
                                        //$request['request']['latlong']='24.1568,78.5263';
                                        $request['request']['latlong']= $latitude . ',' . $longitude;

                                        $request['request']['outletid']=$outletId;

                                        $curl = curl_init();
                                        $url="https://www.instantpay.in/ws/bbps/bill_pay";
                                        curl_setopt_array($curl, array(
                                            CURLOPT_URL => $url,
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => "",
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 0,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => "POST",
                                            CURLOPT_POSTFIELDS => json_encode($request), 
                                            CURLOPT_HTTPHEADER => array(
                                                "Content-Type: application/json",
                                                "Accept: application/json"
                                            ),
                                        ));

                                        $response = curl_exec($curl);  

                                        curl_close($curl);
                                       
                                        //response//
                                        if ($response) {///response true 
                          

                                            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"BBPS Bill Payment","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                   
                                            $response = json_decode($response,TRUE);
                                            if(is_array($response)){

                                                if(isset($response['statuscode'])) {
                     
                                                    $mapped_error=$response['statuscode'];
                                                    $mapped_error_desc=$response['status'];

                                            
                                                }else{

                                                    $mapped_error='';
                                                    $mapped_error_desc='Unknown Error'; 

                                                }

                                                $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,$chkservicestat['vendor_id']); 
             
                                                if($error_mapping){

                                                    $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];
                                                    if(isset($response['statuscode'])) {
                                                        if(($response['statuscode'])=='TXN') {
                                                            if (isset($response['data'])) {

                                                                $txnstatus=$response['data']['status'];
                                                                $data['error'] = 0;
                                                                $data['error_desc'] = null;
                                                                $data['msg'] = $error_mapping['error_code_desc'];
                                                                $data['TxnId'] = isset($response['data']['agent_id']) ? $response['data']['agent_id'] : '00'; 
                                                                $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                                $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                                $fstpyresponsecode =$error_mapping['error_code'];
                                                                $fstpayresponse =$error_mapping['error_code_desc'];
                                                                $data['status'] = $txnstatus;


                                                            }//isset($response['data'])

                                                        }elseif(($response['statuscode'])=='TUP'){
                                                            //pending TUP
                                                            $txnstatus='PENDING';
                                                            $data['error'] = 3;
                                                            $data['error_desc'] =null;
                                                            $data['msg'] =  $error_mapping['error_code_desc'];
                                                            $data['TxnId'] = $agentid;
                                                            $data['pay_dtl'] = $response;
                                                            $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                            $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                            $data['status'] = $txnstatus;
                                                            $indresponsecode = $error_mapping['error_code'];
                                                            $indresponse = $error_mapping['error_code_desc'];
                                                        }else{
                                                            //failed


                                                            $txnstatus='FAILED';
                                                            $data['error'] = 1;
                                                            $data['error_desc'] = $error_mapping['error_code_desc'];
                                                            $data['msg'] = null;
                                                            $data['TxnId'] = $agentid;
                                                            $data['pay_dtl'] = $response;
                                                            $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                            $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                            $data['status'] = $txnstatus;
                                                            $indresponsecode = $error_mapping['error_code'];
                                                            $indresponse = $error_mapping['error_code_desc'];
                                                        }
                                                    }else{
                                                    //pending

                                                        $txnstatus='PENDING';
                                                        $data['error'] = 3;
                                                        $data['error_desc'] =null;
                                                        $data['msg'] =  $error_mapping['error_code_desc'];
                                                        $data['TxnId'] = $agentid;
                                                        $data['pay_dtl'] = $response;
                                                        $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                        $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                        $data['status'] = $txnstatus;
                                                        $indresponsecode = $error_mapping['error_code'];
                                                        $indresponse = $error_mapping['error_code_desc'];
                                                    }

                          
                                                }else{

                                                    /**** not error mapping txn pndg**/


                                                    $data['error']=3;
                                                    $data['msg']='Other Unknown Error'; 
                                                    $data['error_desc']=null;
                                                    $txnstatus='PENDING';
                                                    $error_mapping['error_code']='OUE';
                                                    $error_mapping['error_code_desc']=$data['msg'];
                                                    $data['TxnId'] = $agentid;
                                                    $data['pay_dtl'] = $response;
                                                    $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $indresponsecode = $error_mapping['error_code'];
                                                    $indresponse = $error_mapping['error_code_desc'];
                                                    $data['dum2'] = 'dum2';

                                                }



                                                $update_dt_array = array(
                                                    "sp_id" => isset($response['ipay_id'])?$response['ipay_id']:'00',
                                                     "opr_ref_no" => isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00',
                                                    //"opr_ref_no" =>isset($response['txnRefId'])?$response['txnRefId']:'00',
                                                    "sp_respcode" => $error_mapping['error_code'],
                                                    "sp_respdesc" => $error_mapping['error_code_desc'],
                                                    "sp_response" => json_encode($response),
                                                    "res_dt" => date('Y-m-d H:i:s'),
                                                    "ind_rcode" => $error_mapping['error_code'],
                                                    "response" => $error_mapping['error_code_desc'],
                                                    "status" => $data['status'],
                                                    "upd_id" => $inserted_id
                                                );


                                                $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);
                                    
                                            }else{

                                                /***** reponse is not array  txn pendg*****/
                                                /***** NOTE****/

                                                $data['error'] = 3;
                                                $data['error_desc'] = null;
                                                $data['msg'] = 'Transaction Under Process';
                                                $data['TxnId'] = $agentid;
                                                $data['OPTId'] = '00';
                                                $data['TranId'] = '00';
                                                $data['status'] = 'PENDING';
                                                //$indresponse = 'Transaction Under Process';
                                                $data['dum3'] = 'dum3';
                                                  


                                                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"BBPS Bill Payment","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                $update_dt_array = array(
                                                      
                                                    "opr_ref_no" => "00",
                                                    "sp_respcode" => "TMDOUT",
                                                    "sp_respdesc" => 'Timed Out',
                                                    "sp_response" => "Request Timedout from Vendor",
                                                    "res_dt" => date('Y-m-d H:i:s'),
                                                    "ind_rcode" => 'TUP',
                                                    "response" => "Transaction Under Process",
                                                    "status" => "PENDING",
                                                    "upd_id" => $inserted_id
                                                );



                                                $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                                            }
                                        } else {

                                            ///response false ///
                                            $data['error'] = 3;
                                            $data['error_desc'] = null;
                                            $data['msg'] = 'Transaction Under Process';
                                            $data['TxnId'] = $agentid;
                                            $data['OPTId'] = '00';
                                            $data['TranId'] = '00';
                                            $data['status'] = 'PENDING';
                                            $indresponse = 'Transaction Under Process';
                                                 

                                            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"BBPS Bill Payment","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                            $update_dt_array = array(
              
                                                "opr_ref_no" => "00",
                                                "sp_respcode" => "TMDOUT",
                                                "sp_respdesc" => 'Timed Out',
                                                "sp_response" => "Request Timedout from Vendor",
                                                "res_dt" => date('Y-m-d H:i:s'),
                                                "ind_rcode" => 'TUP',
                                                "response" => "Transaction Under Process",
                                                "status" => "PENDING",
                                                "upd_id" => $inserted_id
                                            );



                                            $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                                        }

                                 

                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = "Internal Processing Error, Try Again Later 2";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                    }

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = "Insufficient Balance";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';

                                }

                            }else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Unable to find user details1';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }



                        } else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                                  
                        }

                    }else{


                        $data['error']=1;
                        $data['error_desc']="Service not allowed, contact admin";
                        $data['msg']=null;
                        $data['status']='FAILED';


                    }
                } else {

                    $data['error'] = 1;
                    $data['error_desc'] = "Internal Processing Error, Try Again Later 1";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';

                                    }
                }else {

                    $data['error'] = 1;
                    $data['error_desc'] = "Invalid amount".$amnt;
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';

                }
                          
            } else {

                $data['error'] = 1;
                $data['error_desc'] = 'Something Went Wrong';
                $data['msg'] = null;
                $data['status'] = 'FAILED';

            }

        return $data;
   }

   public function bbps_bill_payment_request_old($user_info, $plan_id,$filelds,$chkservicestat,$parameters,$outletid){
	
    $latitude = $parameters['lati'];
    $longitude = $parameters['longi'];
    $outletId = trim(strip_tags($outletid));
    $spkey = trim(strip_tags($parameters['spkey']));
    $agentid =isset($parameters['AgentId'])?$parameters['AgentId']:txn_remitt_txnid();
    $bill_mode=trim(strip_tags($parameters['bill_mode']));
    $amnt = trim(strip_tags($parameters['total_pybl'])); 
    if($chkservicestat['bill_fetch']==1){

        $reference_id = trim(strip_tags($parameters['reference_id']));

    }else{

        $reference_id = '';
    }
     $data=array();   
    if($agentid){
    if(($amnt)) {
    $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
    if ($operator_dtl) {

    $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$plan_id,$chkservicestat['service_id']);
    if($chck_user_pln_dtl){
    if ($chkservicestat['billing_model']) {
      if ($chkservicestat['billing_model'] == 'P2P'){
                    if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                     if ($chck_user_pln_dtl['slab_applicable'] == 1) {


                              
                         $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);
                                   // print_r($chck_agrd_fee_rng);
                                    if ($chck_agrd_fee_rng){

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                            $rate = $chck_agrd_fee_rng['rate']; //rate=7
                                          

                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);

                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }

                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                   

                     }else {
                        /*************** slab not applicable*********** */

                                    if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                        $rate = $chck_user_pln_dtl['rate'];

                                    } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;

                                        $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                        $rate = round($rate, 2);

                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Internal Processing Error, Try again later';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                   
                        } 

                    }else {

                        $data['error'] = 1;
                        $data['error_desc'] = 'Rate configuration issue, contact admin.1';
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                        return $data;
                    }

                }elseif ($chkservicestat['billing_model'] == 'P2A') {
                //P2A//
                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                    $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                    if ($chck_agrd_fee_rng) {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                            $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {
                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin .111';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                    } else {

                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        $rate = $chck_user_pln_dtl['rate'];
                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;

                                        $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                        $rate = round($rate, 2);
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }
                    }
                    } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                    }

                 //P2A//   

                }elseif ($chkservicestat['billing_model'] == 'CHARGE') {
                      //Charge//
                        if ($chck_user_pln_dtl['charge_method'] == 'DEBIT') {
                                if ($chck_user_pln_dtl['slab_applicable'] == 1) {

                                    $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                    if ($chck_agrd_fee_rng) {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                            $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;

                                            $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin ..0909';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                } else {

                                    if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        $rate = $chck_user_pln_dtl['rate']; //rate=11
                                    } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENT') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;

                                        $rate = ($rate >= $capng_amnt) ? $rate : $capng_amnt;
                                        $rate = round($rate, 2);
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Internal Processing Error, Try again later';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.2343';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }

                    //Charge//


                }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.222';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                }

                  /*******calculation of base,appl comm, gst **********/
                    if($chkservicestat['billing_model'] == 'P2P') {

                            $amount = $amnt;
                            $applicable_commission = $rate; ///(Applicable GST)//2
                            $base_applicable_commisison = $applicable_commission;
                            $gstamount_tobecredited = 0;
                            $tdsamount = (($applicable_commission * 5) / 100);
                            $tdsamount = round($tdsamount, 2);
                            $charged_amount = $amount;
                            

                    } elseif ($chkservicestat['billing_model'] == 'P2A') {  

                                    $amount = $amnt;
                                    $applicable_commission = $rate; ///(Applicable GST)//2
                                    $charged_amount = $amount;
                                    $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                    $base_applicable_commisison = round($base_applicable_commisison, 2);
                                    $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                    $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                    $tdsamount = (($base_applicable_commisison * 5) / 100);
                                    $tdsamount = round($tdsamount, 2);
                            
                        } elseif ($chkservicestat['billing_model'] == 'CHARGE') {

                            $amount = $amnt;
                            $applicable_commission = 0; ///(Applicable GST)//2
                            $base_applicable_commisison = 0;
                            $gstamount_tobecredited = ($rate - (($rate / 118) * 100));
                            $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                            $tdsamount = 0;

                            $charged_amount = $amount + $rate;


                        } else {


                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }
                        /*****end gst base appl comm****/
                           $common_array=array();
                            /*******calculation of base gst ***********/
                        if ($user_info['parent_id'] != '0') {
                        $get_retailer_parent_info = $this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                         if ($get_retailer_parent_info) {


                            foreach ($get_retailer_parent_info as $key => $value) {
                               
                            if($value['role_id']==2 || $value['role_id']==3 ){
                        // print_r($value['user_id']);echo "<br>";

                            $checkusercustomprice_parnt = $this->_ci->Inst_model->checkuser_pln_dtl($value['role_id'],$value['plan_id'],$chkservicestat['service_id']);
                                               
                                if($checkusercustomprice_parnt){
                                    $prnt_capng_nrmlamnt=$checkusercustomprice_parnt['capping_amount'];
                                if($checkusercustomprice_parnt['slab_applicable']==1){
                                $chck_agrd_fee_rng_fr_prnt=$this->_ci->Inst_model->check_pln_amnt_rng($checkusercustomprice_parnt['pl_srvc_rl_id'],$checkusercustomprice_parnt['plan_id'],$checkusercustomprice_parnt['service_id'],$amount);

                                if($chck_agrd_fee_rng_fr_prnt){
                               
                         
                                    $parentplan_rate=$chck_agrd_fee_rng_fr_prnt['rate'];//rate=11

                                }else{

                                             $data['error']=1;
                                             $data['error_desc']='Rate configuration issue,contact admin 00';
                                             $data['msg']=null;
                                             return $data;
                                }

                                }else{

                                      $parentplan_rate=$checkusercustomprice_parnt['rate'];


                                }
                               
if ($checkusercustomprice_parnt['charge_method'] == 'CREDIT' && ($checkusercustomprice_parnt['charge_type'] == 'FIXED' || $checkusercustomprice_parnt['charge_type'] == 'PERCENT')) {

                                    if ($checkusercustomprice_parnt['charge_type'] == 'FIXED') {

                                                        /* * 1> txn amount=$amount
                                                      2> ccf=1%of txn amnt
                                                      ccf = (1%of txn amnt) OR Rs 10(gtr ccf consider)
                                                      3> base of ttl ccf(net ccf bank)= (ccf/118)*100
                                                      4> app_comm=net ccf bank -srvc_rate
                                                      5> base_commsn =(app_comm/118)*100
                                                      6> gst of comm= app_comm- base_commsn
                                                      7> 5% TDS on base_commsn=base_commsn*5/100
                                                     * */
                                                    /** $rate_nrmlprnt=Rs 1** */
                                                    $commission_nrmlprnt_amt = $parentplan_rate;

                                                    $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                    $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                    if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                        $base_applicable_commisison_fr_nrmlprnt = ($applicable_commission_nrmlprnt/118)*100;
                                                            $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }
                                                } else if($checkusercustomprice_parnt['charge_type'] == 'PERCENT') {

                                                    $commission_nrmlprnt_amt = (($parentplan_rate) / 100) * $amnt;

                                                    $commission_nrmlprnt_amt = ($commission_nrmlprnt_amt >= $prnt_capng_nrmlamnt) ? $prnt_capng_nrmlamnt : $commission_nrmlprnt_amt;

                                                    $applicable_commission_nrmlprnt = ($commission_nrmlprnt_amt);
                                                    $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);
                                                    if (is_numeric($applicable_commission_nrmlprnt) && $applicable_commission_nrmlprnt >= 0) {

                                                    

                                                            $base_applicable_commisison_fr_nrmlprnt = ($commission_nrmlprnt_amt/118)*100;
                                                            $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }

                                                } else {

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Internal Processing Error, Try again later';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }
                                   }else{

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'configuration issue,contact admin';
                                    $data['msg'] = null;
                                    return $data;
                                   }/////charge_method CREDIT charge_type FIXED PERCENT//

                                    }else{

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'No Profile for parent';
                                            $data['msg'] = null;
											$data['status'] = 'FAILED';
                                            return $data;
                                    }

                            }

                            }//end foreach loop


                            } else {


                                $data['error'] = 1;
                                $data['error_desc'] = 'Unable to find parent details';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                        }/***$user_info['parent_id'] != '0'***/
                              /*****No parent *****/

                        $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);

                        if ($findbalance) {

                            $openingbal = $findbalance['rupee_balance'];

                            $closing_balance = $openingbal - $charged_amount;

                            $identify_commision_from = $findbalance['first_name'] .' '. $findbalance['first_name'] . ' ( ' . $findbalance['mobile'] . ' )';


                        if ($openingbal >= $charged_amount && $closing_balance >= 0 && $charged_amount != null) {


                                            $name='';
                                         foreach ($filelds as $key => $value) {

                                            $name.=$value['name'].' - '.$value['value'];

                                            if($key!=count($filelds)-1){

                                            $name.=', ';

                                            }
                                            
                                         
                                         }

                         
                           $RetailerTxnEntry = array(
                            "user_id"=>$findbalance['user_id'],
                            "req_ip"=>ip_address(),
                            "request_useragent "=>$this->_ci->agent->agent_string(),
                            "fstpytxn_id"=>$agentid,
                            "sp_id"=>$reference_id,
                            "opr_ref_no"=>"00",
                            'customer_no' => $filelds[0]['value'],///dynamic//
                            'scode' => $chkservicestat['code'],
                            "servicename"=>$chkservicestat['service_name'],
                            "servicetype"=>$chkservicestat['type'],
                            "servedby"=>$chkservicestat['served_by'],
                            "transamt"=>$amount,
                            "chargeamt"=>$charged_amount,
                            "openingbal"=>$openingbal,
                            "closingbal"=>$closing_balance,
                            "req_dt"=>date('Y-m-d H:i:s'),
                            "res_dt"=>"0000-00-00 00:00:00",
                            "ind_rcode"=>'TUP',
                            "response"=>"Recharge Under Process",
                            "status"=>"PENDING",
							// "op1"=>$parameters['Details']['custm_mob'],
                            "op1"=>$parameters['custm_mob'], ///**** NOTE : by removing customer num by ui****/
                            'op2'=>$name
                          
                           );

                            if (($chkservicestat['billing_model'] == 'P2P') || ($chkservicestat['billing_model'] == 'P2A')) {
                                    $CreditHistroyIdCashback = ch_txnid();
                                    $comm_openingbal = $closing_balance;
                                    $basecomm_amount = $base_applicable_commisison;
                                    $comm_closingbal = $comm_openingbal + $basecomm_amount;


                                    $CashbackCrdtEntry_reatiler = array(
                                        'credit_txnid' => $CreditHistroyIdCashback,
                                        'user_id' => $findbalance['user_id'],
                                        'bank_name' => 'N/A',
                                        'txn_type' => 'CASHBACK',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $basecomm_amount,
                                        'opening_balance' => $comm_openingbal,
                                        'closing_balance' => $comm_closingbal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                        'remarks' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                        'txn_code' => $agentid,
                                        'status' => 'CREDIT',
                                        'updated_by' => $findbalance['user_id'],
                                    );


                                    $CreditHistroyTDSId = ch_txnid();
                                    $Tds_opng_bal = $comm_closingbal;
                                    $RetTds = $tdsamount;
                                    $Tds_clsng_bal = $Tds_opng_bal - $RetTds;

                                    $TDSCreditHistoryEntryRetailer = array(
                                        'credit_txnid' => $CreditHistroyTDSId,
                                        'user_id' => $findbalance['user_id'],
                                        'bank_name' => 'N/A',
                                        'txn_type' => 'TDS',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $RetTds,
                                        'opening_balance' => $Tds_opng_bal,
                                        'closing_balance' => $Tds_clsng_bal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                        'remarks' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $agentid . ',Transaction Amount : ' . $amount,
                                        'txn_code' => $agentid,
                                        'status' => 'DEBIT',
                                        'updated_by' => $findbalance['user_id'],
                                    );

                                    $TaxRecordRet = array(
                                        'user_id' => $findbalance['user_id'],
                                        'cbrt_id' => $agentid,
                                        'billing_model' => $chkservicestat['billing_model'],
                                        'trans_amt' => $amount,
                                        'charged_amt' => $charged_amount,
                                        'comm_amnt' => $base_applicable_commisison,
                                        'tds_amnt' => $tdsamount,
                                        'gst_amnt' => $gstamount_tobecredited,
                                        'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
                                        'tds_status' => 'PENDING',
                                        'tax_type' => 'CREDIT',
                                        'created_dt' => date('Y-m-d H:i:s'),
                                        'updated_by' => $findbalance['user_id'],
                                        'updated_dt' => date('Y-m-d H:i:')
                                    );

                                } else {

                                    $TaxRecordRet = array(
                                        'user_id' => $findbalance['user_id'],
                                        'cbrt_id' => $agentid,
                                        'billing_model' => $chkservicestat['billing_model'],
                                        'trans_amt' => $amount,
                                        'charged_amt' => $charged_amount,
                                        'comm_amnt' => $base_applicable_commisison,
                                        'tds_amnt' => $tdsamount,
                                        'gst_amnt' => $gstamount_tobecredited,
                                        'gst_status' => 'PENDING',
                                        'tds_status' => 'PENDING',
                                        'tax_type' => 'CREDIT',
                                        'created_dt' => date('Y-m-d H:i:s'),
                                        'updated_by' => $findbalance['user_id'],
                                        'updated_dt' => date('Y-m-d H:i:')
                                    );

                                    $CashbackCrdtEntry_reatiler = array();
                                    $TDSCreditHistoryEntryRetailer = array();
                                }

                                $RetailerInsertEntry = $this->_ci->Inst_model->RetailerAllEntryWithoutAdmin($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet);

                                if ($RetailerInsertEntry) {
                                    $inserted_id = $RetailerInsertEntry;
                                    
                                    $ParentArray = array();
                                   
                                    if ($findbalance['parent_id'] != 0) {
                                       
                                        if (count($common_array) > 0) {////sup dist/ dist of retailer
                                            foreach ($common_array as $k => $v) {

                                                $ParentInfo = $this->_ci->Inst_model->user_info($v['USERID']);

                                                if ($ParentInfo) {

                                                    $parent_opening = $ParentInfo['rupee_balance'];
                                                    $parent_closeing = $parent_opening + $v['BASCOMM'];
                                                    $ParentArray[$k]['COM'] = array(
                                                        'credit_txnid' => ch_txnid(),
                                                        'user_id' => $ParentInfo['user_id'],
                                                        'bank_name' => 'N/A',
                                                        'txn_type' => 'COMMISSION',
                                                        'payment_mode' => 'WALLET',
                                                        'amount' => $v['BASCOMM'],
                                                        'opening_balance' => $parent_opening,
                                                        'closing_balance' => $parent_closeing,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => 'Commission From ' . $identify_commision_from . ' For ' . $agentid,
                                                        'remarks' => 'Commission From ' . $identify_commision_from . ' For ' . $agentid . ',Transaction Amount : Rs. ' . $amount,
                                                        'txn_code' => $agentid,
                                                        'status' => 'CREDIT',
                                                        'updated_by' => $ParentInfo['user_id']
                                                    );


                                                    $parent_opening_tds = $parent_closeing;
                                                    $parent_closing_tds = $parent_opening_tds - $v['TDS'];

                                                    $ParentArray[$k]['TDS'] = array(
                                                        'credit_txnid' => ch_txnid(),
                                                        'user_id' => $ParentInfo['user_id'],
                                                        'bank_name' => 'N/A',
                                                        'txn_type' => 'TDS',
                                                        'payment_mode' => 'WALLET',
                                                        'amount' => $v['TDS'],
                                                        'opening_balance' => $parent_opening_tds,
                                                        'closing_balance' => $parent_closing_tds,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $agentid . ', Transaction Amount : Rs. ' . $amount,
                                                        'remarks' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $agentid . ', Transaction Amount : Rs. ' . $amount,
                                                        'txn_code' => $agentid,
                                                        'status' => 'DEBIT',
                                                        'updated_by' => $ParentInfo['user_id']
                                                    );

                                                    $ParentArray[$k]['TAX'] = array(
                                                        'user_id' => $v['USERID'],
                                                        'cbrt_id' => $agentid,
                                                        'billing_model' => 'P2A',
                                                        'trans_amt' => $amount,
                                                        'charged_amt' => $charged_amount,
                                                        'comm_amnt' => $v['BASCOMM'],
                                                        'tds_amnt' => $v['TDS'],
                                                        'gst_amnt' => $v['GST'],
                                                        'gst_status' => 'PENDING',
                                                        'tds_status' => 'PENDING',
                                                        'tax_type' => 'CREDIT',
                                                        'created_dt' => date('Y-m-d H:i:s'),
                                                        'updated_by' => $ParentInfo['user_id'],
                                                        'updated_dt' => date('Y-m-s H:i:s')
                                                    );
                                                }
                                            }
                                        }/** *count($common_array) end** */


                                      
                                        $parent_comission = $this->_ci->Inst_model->parent_commission_without_Admin($ParentArray);

                                        
                                    }///$findbalance['parent_id'] != 0
                                    
                               
                                        $lat = explode('.', $latitude);
                                        $latitude = $lat[0] . '.' . substr($lat[1], 0, 4);
                                        $lon = explode('.', $longitude);
                                        $longitude = $lon[0] . '.' . substr($lon[1], 0, 4);
                                    // $request['token'] =$this->token;
                                    // $request['request']['sp_key'] = $chkservicestat['code'];
                                    // $request['request']['agentid']=$agentid;
                                    // $request['request']['customer_mobile']=$parameters['Details']['custm_mob'];
                                    // $request['request']['customer_params']=[
                                    //      $parameters['Details']['mobileno'],
                                    //       "{{customer_param2}}"
                                    // ];
                                    // $request['request']['init_channel']='AGT';
                                    // $request['request']['endpoint_ip']=ip_address();
                                    // $request['request']['mac']='AD-fg-12-78-GH';
                                    // $request['request']['payment_mode']='Cash';
                                    // $request['request']['payment_info']='bill';

                                    // $request['request']['amount']=$amnt;
                                    // $request['request']['reference_id']=$reference_id;
                                    // $request['request']['latlong']=' . $latitude . ',' . $longitude . ';

                                    // $request['request']['outletid']=$outletId;

                                    // $curl = curl_init();
                                    // curl_setopt_array($curl, array(
                                    //   CURLOPT_URL => "https://www.instantpay.in/ws/bbps/bill_pay",
                                    //   CURLOPT_RETURNTRANSFER => true,
                                    //   CURLOPT_ENCODING => "",
                                    //   CURLOPT_MAXREDIRS => 10,
                                    //   CURLOPT_TIMEOUT => 0,
                                    //   CURLOPT_FOLLOWLOCATION => true,
                                    //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    //   CURLOPT_CUSTOMREQUEST => "POST",
                                    //    CURLOPT_POSTFIELDS => json_encode($request), 
                                    //   CURLOPT_HTTPHEADER => array(
                                    //     "Content-Type: application/json",
                                       
                                    //     "Accept: application/json"
                                    //   ),
                                    // ));

                                    // $response = curl_exec($curl);  

                                    // curl_close($curl);

                                         foreach ($filelds as $key => $value) {

                                                $request['request']['customer_params'][$key]=$value['value'];
												/**** NOTE: custm_mob*****/
/*                                                 if($value['id']=='custm_mob'){
                                                   
                                                    $request['request']['customer_mobile']=$value['value'];
                                                } */
                                              
                                        }
										
										
										/**** NOTE: custm_mob*****/
                                               // if($value['id']=='custm_mob'){
                                                   
                                                    $request['request']['customer_mobile']=$parameters['custm_mob'];
                                               // }


                                        $request['token'] = '19b6aaa0623d1a724d9ba7691652dbb8';
                                        $request['request']['sp_key'] =$operator_dtl['vendor_key'];
                                        $request['request']['agentid']=$agentid;
                                       // $request['request']['customer_mobile']=$parameters['Details']['custm_mob'];
                                        // $request['request']['customer_params']=[
                                        //       $parameters['Details']['mobileno'],

                                        //       // "{{customer_param2}}"
                                        // ];
                                        $request['request']['init_channel']='AGT';
                                        $request['request']['endpoint_ip']='122.176.102.215';
                                        $request['request']['mac']='AD-fg-12-78-GH';
                                        $request['request']['payment_mode']='Cash';
                                        $request['request']['payment_info']='bill';

                                        $request['request']['amount']='10';
                                        $request['request']['reference_id']=$reference_id;
                                        //$request['request']['latlong']='24.1568,78.5263';
                                        $request['request']['latlong']= $latitude . ',' . $longitude;

                                        $request['request']['outletid']=$outletId;

                                        $curl = curl_init();
                                        $url="https://www.instantpay.in/ws/bbps/bill_pay";
                                        curl_setopt_array($curl, array(
                                          CURLOPT_URL => $url,
                                          CURLOPT_RETURNTRANSFER => true,
                                          CURLOPT_ENCODING => "",
                                          CURLOPT_MAXREDIRS => 10,
                                          CURLOPT_TIMEOUT => 0,
                                          CURLOPT_FOLLOWLOCATION => true,
                                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                          CURLOPT_CUSTOMREQUEST => "POST",
                                           CURLOPT_POSTFIELDS => json_encode($request), 
                                          CURLOPT_HTTPHEADER => array(
                                            "Content-Type: application/json",
                                           
                                            "Accept: application/json"
                                          ),
                                        ));

                                        $response = curl_exec($curl);  

                                        curl_close($curl);
                                       
                                    //response//
                                    if ($response) {///response true 
                          

                                      $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"BBPS Bill Payment","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                   
                                    $response = json_decode($response,TRUE);
                                    if(is_array($response)){

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }

                            $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,$chkservicestat['vendor_id']); 
             
                            if($error_mapping){

                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];
                            if(isset($response['statuscode'])) {
                            if(($response['statuscode'])=='TXN') {
                            if (isset($response['data'])) {

                                    $txnstatus=$response['data']['status'];
                                    $data['error'] = 0;
                                    $data['error_desc'] = null;
                                    $data['msg'] = $error_mapping['error_code_desc'];
                                    $data['TxnId'] = isset($response['data']['agent_id']) ? $response['data']['agent_id'] : '00'; 
                                    $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                    $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                    $fstpyresponsecode =$error_mapping['error_code'];
                                    $fstpayresponse =$error_mapping['error_code_desc'];
                                    $data['status'] = $txnstatus;


                            }//isset($response['data'])

                            }elseif(($response['statuscode'])=='TUP'){
                               //pending TUP
                                                    $txnstatus='PENDING';
                                                    $data['error'] = 3;
                                                    $data['error_desc'] =null;
                                                    $data['msg'] =  $error_mapping['error_code_desc'];
                                                    $data['TxnId'] = $agentid;
                                                    $data['pay_dtl'] = $response;
                                                    $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $indresponsecode = $error_mapping['error_code'];
                                                    $indresponse = $error_mapping['error_code_desc'];
                            }else{
                                //failed


                                                       $txnstatus='FAILED';
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = $error_mapping['error_code_desc'];
                                                        $data['msg'] = null;
                                                        $data['TxnId'] = $agentid;
                                                        $data['pay_dtl'] = $response;
                                                        $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                        $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                        $data['status'] = $txnstatus;
                                                        $indresponsecode = $error_mapping['error_code'];
                                                        $indresponse = $error_mapping['error_code_desc'];
                            }
                            }else{
                               //pending

                                                    $txnstatus='PENDING';
                                                    $data['error'] = 3;
                                                    $data['error_desc'] =null;
                                                    $data['msg'] =  $error_mapping['error_code_desc'];
                                                    $data['TxnId'] = $agentid;
                                                    $data['pay_dtl'] = $response;
                                                    $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $indresponsecode = $error_mapping['error_code'];
                                                    $indresponse = $error_mapping['error_code_desc'];
                            }

                          
                            }else{

                                /**** not error mapping txn pndg**/


                                                    $data['error']=3;
                                                    $data['msg']='Other Unknown Error'; 
                                                    $data['error_desc']=null;
                                                    $txnstatus='PENDING';
                                                    $error_mapping['error_code']='OUE';
                                                    $error_mapping['error_code_desc']=$data['msg'];
                                                    $data['TxnId'] = $agentid;
                                                    $data['pay_dtl'] = $response;
                                                    $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $indresponsecode = $error_mapping['error_code'];
                                                    $indresponse = $error_mapping['error_code_desc'];
                                                      $data['dum2'] = 'dum2';

                                                   


                            }



                                 $update_dt_array = array(
                                                        "sp_id" => isset($response['ipay_id'])?$response['ipay_id']:'00',
                                                         "opr_ref_no" => isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00',
                                                        //"opr_ref_no" =>isset($response['txnRefId'])?$response['txnRefId']:'00',
                                                        "sp_respcode" => $error_mapping['error_code'],
                                                        "sp_respdesc" => $error_mapping['error_code_desc'],
                                                        "sp_response" => json_encode($response),
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                                    );


                        $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);
                                    
                    }else{

                        /***** reponse is not array  txn pendg*****/
						/***** NOTE****/

                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process';
                                                    $data['TxnId'] = $agentid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
                                                    //$indresponse = 'Transaction Under Process';
                                                        $data['dum3'] = 'dum3';
                                                  


     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"BBPS Bill Payment","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                    $update_dt_array = array(
                                                      
                                                        "opr_ref_no" => "00",
                                                        "sp_respcode" => "TMDOUT",
                                                        "sp_respdesc" => 'Timed Out',
                                                        "sp_response" => "Request Timedout from Vendor",
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => 'TUP',
                                                        "response" => "Transaction Under Process",
                                                        "status" => "PENDING",
                                                        "upd_id" => $inserted_id
                                                    );



    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                        }
                        } else {

                            ///response false ///

                            
                                                
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process';
                                                    $data['TxnId'] = $agentid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
                                                    $indresponse = 'Transaction Under Process';
                                                 

      $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"BBPS Bill Payment","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            $update_dt_array = array(
              
                "opr_ref_no" => "00",
                "sp_respcode" => "TMDOUT",
                "sp_respdesc" => 'Timed Out',
                "sp_response" => "Request Timedout from Vendor",
                "res_dt" => date('Y-m-d H:i:s'),
                "ind_rcode" => 'TUP',
                "response" => "Transaction Under Process",
                "status" => "PENDING",
                "upd_id" => $inserted_id
            );



    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                                    }

                                 

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = "Internal Processing Error, Try Again Later 2";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                }

                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = "Insufficient Balance";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';

                            }

                        }else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Unable to find user details1';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }



                    } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                              
                    }

                    }else{


                            $data['error']=1;
                            $data['error_desc']="Service not allowed, contact admin";
                            $data['msg']=null;
                            $data['status']='FAILED';


                    }
                      } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = "Internal Processing Error, Try Again Later 1";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';

                                    }
                    }else {

                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid amount".$amnt;
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';

                    }
                          
                    } else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Something Went Wrong';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';

                    }

                        return $data;
                   }
				   
    public function InstantCustomerLogin ($user_info,$param,$outletid){
        $mobile = strip_tags($param);
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
            
            $request['token'] = $this->token; 
          
            $request['request']['mobile']=$mobile;
         
            $request['request']['outletid']=$outletid;

            $curl = curl_init();
            $url="https://www.instantpay.in/ws/dmi/remitter_details";
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($request), 
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Accept: application/json"
                ),
            ));

            $response = curl_exec($curl);  

            curl_close($curl);
            
            /*  print_r($response);exit;
            echo $response;  */
            if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Check Login","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                           
                $response = json_decode($response,TRUE);
                            
                if(is_array($response)){
                    if(isset($response['statuscode'])){
                        $mapped_error=$response['statuscode'];
                        $mapped_error_desc=$response['status'];
                    }else{

                        $mapped_error='';
                        $mapped_error_desc='Unknown Error'; 

                    }


                    $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
         
                    if($error_mapping){
                    
                        $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                        if($error_mapping['errorcode_id']=='2'){
                            $error_mapping['error_code']=$error_mapping['error_code'];
                            $error_mapping['error_code_desc']=$mapped_error_desc;
                        }


                        if(isset($response['statuscode'])){
                            if($response['statuscode']=='TXN'){



                                if (isset($response['data']) && $response['data']['remitter']['is_verified']==1) {
                              
                                    // if($response['data']['remitter']['is_verified']==1){
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
                                    $data['remitter_mobl']=$response['data']['remitter']['mobile'];
                                    $data['outletid']=$outletid; 
                                    $data['msg'] =$error_mapping['error_code_desc'];
                                }else{
                                    $data['error'] = 4;  
                                    $data['error_desc'] =null;
                                    $data['response'] = $response;
                                    $data['outletid']=$outletid; 
                                    $data['msg'] =$response['status'];
                                    $data['remitterid'] =$response['data']['remitter']['id'];
                                }
                            } else if($response['statuscode']=='RNF'){
                                $data['error'] = 3;  
                                $data['error_desc'] =$response['status'];
                                $data['response'] = $response;
                                $data['outletid']=$outletid; 
                                $data['msg'] =$error_mapping['error_code_desc'];

                                // $data['beneficiary'][0]['id'] = "3384113";
                                // $data['beneficiary'][0]['name'] = "Neha";
                                // $data['beneficiary'][0]['mobile'] = "9773744260";
                                // $data['beneficiary'][0]['account'] = "50100067594485";
                                // $data['beneficiary'][0]['bank'] = "HDFC BANK";
                                // $data['beneficiary'][0]['status'] = "1";
                                // $data['beneficiary'][0]['last_success_date'] = "";
                                // $data['beneficiary'][0]['last_success_name'] = "";
                                // $data['beneficiary'][0]['last_success_imps'] = "";
                                // $data['beneficiary'][0]['ifsc'] = "HDFC0000240";
                                // $data['beneficiary'][0]['imps'] = "0";
                                
                            }else{   

                                $data['error'] = 1;
                                $data['error_desc'] =$error_mapping['error_code_desc'];
                                $data['msg'] = null;
                                
                            }
                        
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = $error_mapping['error_code_desc'];
                            $data['msg'] = null;
                        }

                    }else{
                            
                        $data['error']=3;
                        $data['msg']='Other Unknown Error'; 
                        $data['error_desc']=null;
                    }
                                
                }else{
                    $data['error'] = 3;
                    $data['error_desc'] = null;
                    $data['msg'] = 'Check Remitter Login Under Process';

                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Check Login","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                }
            } else {///response false 
                $data['error'] = 1;
                $data['error_desc'] = 'Request Timedout';
                $data['msg'] = null;

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Check Login","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
            }   
      
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;   
    }
				   
	public function InstantCustomerLogin_old($user_info,$param,$outletid){
	 $mobile = strip_tags($param);
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
			
       $request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
      
        $request['request']['mobile']=$mobile;
     
        $request['request']['outletid']=$outletid;

        $curl = curl_init();
		  $url="https://www.instantpay.in/ws/dmi/remitter_details";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
	/* 	print_r($response);exit;
      echo $response;  */
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Check Login","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               


                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									/* print_r($response['data']);exit; */
                                    
								if (isset($response['data'])) {
                                  
								  if($response['data']['remitter']['is_verified']==1){
									  
									$data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['remitter_mobl']=$response['data']['remitter']['mobile'];
									$data['outletid']=$outletid; 
                                    $data['msg'] =$error_mapping['error_code_desc'];
								  }else{
									$data['error'] = 4;  
                                    $data['error_desc'] =null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 
                                    $data['msg'] =$response['status'];
									$data['remitterid'] =$response['data']['remitter']['id'];
								  }
                                   
                                    
									 }
                                } else if($response['statuscode']=='RNF')
                                {

                               // if (isset($response['data'])) {
								
                                    $data['error'] = 3;  
                                    $data['error_desc'] =$response['status'];
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 
                                    $data['msg'] =$error_mapping['error_code_desc'];
                                    
								//}  
                                }else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                                
								$data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Check Remitter Login Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Check Login","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Check Login","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   }   
      
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;	
	}

    function InstantRemiiterRegstrn($user_info, $param, $outletid){
        $mobile = isset($param['mobile']) ? $param['mobile'] : '';
        $name = isset($param['name']) ? $param['name'] : '';
        // $surnamename = isset($param['surnamename']) ? $param['surnamename'] : '';
        
        $nameArr = array();
        $nameArr = explode(" ", $name);

        if(count($nameArr) == 1){
            $name = $nameArr[0];
            $surnamename = $nameArr[0];
        } else{
            $name = $nameArr[0];
            unset($nameArr[0]);
            $surnamename = implode(" ", $nameArr);
        }

        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
            if (preg_match('/^[A-Za-z .]+$/', $name)) {
                # if (preg_match('/^[A-Za-z .]+$/', $surnamename)) {
        
                    $request['token'] = $this->token; //'e0c9e38d9219020ecc261d23701748b8';
                    $request['request']['mobile']=$mobile;
                    $request['request']['name']=$name;
                    $request['request']['surname']=$surnamename;
                    $request['request']['pincode']=$user_info['business_pincode'];
                    $request['request']['outletid']='1';

                    $curl = curl_init();
                    $url="https://www.instantpay.in/ws/dmi/remitter";
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($request), 
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                            "Accept: application/json"
                        ),
                    ));

                    $response = curl_exec($curl);  

                    curl_close($curl);
      
      
                    if ($response) {///response true 

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Register Customer Request","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                        $response = json_decode($response,TRUE);
                                
                        if(is_array($response)){
                            if(isset($response['statuscode'])){
                                $mapped_error=$response['statuscode'];
                                $mapped_error_desc=$response['status'];
                            }else{
                                $mapped_error='';
                                $mapped_error_desc='Unknown Error'; 
                            }
                            $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
                            if($error_mapping){
                                $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                                if($error_mapping['errorcode_id']=='2'){
                                    $error_mapping['error_code']=$error_mapping['error_code'];
                                    $error_mapping['error_code_desc']=$mapped_error_desc;
                                }


                                if(isset($response['statuscode'])){
                                    if($response['statuscode']=='TXN'){
                                        if (isset($response['data'])) {
                                            $data['error'] = 0;  
                                            $data['error_desc'] = null;
                                            $data['response'] = $response;
                                            $data['outletid']=$outletid; 
                                            $data['msg'] =$response['status'];
                                            $data['remitterid']=@$response['data']['remitter']['id'];
                                        }
                                    }else{   
                                        $data['error'] = 1;
                                        $data['error_desc'] =$error_mapping['error_code_desc'];
                                        $data['msg'] = null;
                                    }
                                }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                }

                            }else{
                                $data['error']=3;
                                $data['msg']='Other Unknown Error'; 
                                $data['error_desc']=null;

                            }
                                    
                        }else{
                            $data['error'] = 3;
                            $data['error_desc'] = null;
                            $data['msg'] = 'Register Customer Request Under Process';

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Register Customer Request","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                        }
                    } else {///response false 
                        $data['error'] = 1;
                        $data['error_desc'] = 'Request Timedout';
                        $data['msg'] = null;

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Register Customer Request","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                    }  

                // } else {
                //     $data['error'] = 1;
                //     $data['error_desc'] = 'Invalid Surname Name';
                //     $data['msg'] = null;
                // }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Name';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;   
    }

	function InstantRemiiterRegstrn_old($user_info, $param,$outletid){
		$mobile = isset($param['mobile']) ? $param['mobile'] : '';
        $name = isset($param['name']) ? $param['name'] : '';
        $surnamename = isset($param['surnamename']) ? $param['surnamename'] : '';
		
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
        if (preg_match('/^[A-Za-z .]+$/', $name)) {
		if (preg_match('/^[A-Za-z .]+$/', $surnamename)) {
		//$request['token'] =$this->token;
         $request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
        $request['request']['mobile']=$mobile;
		$request['request']['name']=$name;
		$request['request']['surname']=$surnamename;
		$request['request']['pincode']=$user_info['business_pincode'];
        $request['request']['outletid']='1';

        $curl = curl_init();
		$url="https://www.instantpay.in/ws/dmi/remitter";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
      
      
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Register Customer Request","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									
								if (isset($response['data'])) {
                                 
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 

                                  
                                     $data['msg'] =$response['status'];
                                    
									 }
                                }
								else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                                
								$data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Register Customer Request Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Register Customer Request","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Register Customer Request","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   }  

				} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Surname Name';
                $data['msg'] = null;
            }
		} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Name';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;	
	}
	
 
    public function InstantOtpValidation($user_info, $param, $outletid){
        $mobile = isset($param['mobile']) ? $param['mobile'] : '';
        $remitterid = isset($param['remitterid']) ? $param['remitterid'] : '';
        $otp = isset($param['otp']) ? $param['otp'] : '';
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
            if (!$remitterid=='') {
                if (preg_match('/^\d{6}$/', $otp)) {
                    $request['token'] = $this->token; //'e0c9e38d9219020ecc261d23701748b8';
                    $request['request']['remitterid']=$remitterid;
                    $request['request']['mobile']=$mobile;
                    $request['request']['otp']=$otp;
                    
                    $request['request']['outletid']='1';

                    $curl = curl_init();
                    $url="https://www.instantpay.in/ws/dmi/remitter_validate";
                    
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($request), 
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                            "Accept: application/json"
                        ),
                    ));

                    $response = curl_exec($curl);  

                    curl_close($curl);
      // echo $response;
      
      
                    if ($response) {///response true 

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Otp Validation","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                        $response = json_decode($response,TRUE);
                                
                        if(is_array($response)){
                            if(isset($response['statuscode'])){
                                $mapped_error=$response['statuscode'];
                                $mapped_error_desc=$response['status'];
                            }else{
                                $mapped_error='';
                                $mapped_error_desc='Unknown Error'; 
                            }


                            $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
                                $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                                if($error_mapping['errorcode_id']=='2'){
                                    $error_mapping['error_code']=$error_mapping['error_code'];
                                    $error_mapping['error_code_desc']=$mapped_error_desc;
                                }


                                if(isset($response['statuscode'])){
                                    if($response['statuscode']=='TXN'){
                                        if (isset($response['data'])) {
                                            $data['error'] = 0;  
                                            $data['error_desc'] = null;
                                            $data['response'] = $response;
                                            $data['outletid']=$outletid; 
                                            $data['msg'] =$response['status'];
                                        }
                                    } else{   
                                        $data['error'] = 1;
                                        $data['error_desc'] =$error_mapping['error_code_desc'];
                                        $data['response'] = $response;
                                        $data['msg'] = null;
                                    }
                            
                                }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                }

                            }else{
                                $data['error']=3;
                                $data['msg']='Other Unknown Error'; 
                                $data['error_desc']=null;
                            }
                        }else{
                            $data['error'] = 3;
                            $data['error_desc'] = null;
                            $data['msg'] = 'Otp Validation Under Process';

                            $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Otp Validation","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                        }
                    } else {///response false 
                        $data['error'] = 1;
                        $data['error_desc'] = 'Request Timedout';
                        $data['msg'] = null;

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                    }  

                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Invalid OTP';
                    $data['msg'] = null;
                }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Remitter Not exsist';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;       
    }

	public function InstantOtpValidation_old($user_info, $param,$outletid){
		$mobile = isset($param['mobile']) ? $param['mobile'] : '';
        $remitterid = isset($param['remitterid']) ? $param['remitterid'] : '';
        $otp = isset($param['otp']) ? $param['otp'] : '';
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
        if (!$remitterid=='') {
		if (preg_match('/^\d{6}$/', $otp)) {
		//$request['token'] =$this->token;
          $request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
	    $request['request']['remitterid']=$remitterid;
        $request['request']['mobile']=$mobile;
		$request['request']['otp']=$otp;
		
        $request['request']['outletid']='1';

        $curl = curl_init();
		$url="https://www.instantpay.in/ws/dmi/remitter_validate";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
      // echo $response;
      
      
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Otp Validation","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									
								if (isset($response['data'])) {
                                 
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 

                                  
                                     $data['msg'] =$response['status'];
                                    
									 }
                                } 
								else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
									$data['response'] = $response;
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                              
								 
								$data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Otp Validation Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Otp Validation","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   }  

				} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid OTP';
                $data['msg'] = null;
            }
		} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Remitter Not exsist';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;		
	}
	
	

        public function InstantCustomerDetailsFetch($user_info, $param, $outletid){
            $mobile = strip_tags($param);
            $data = array();
            if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
            
                $request['token'] = $this->token;
      
                $request['request']['mobile']=$mobile;
     
                $request['request']['outletid']=$outletid;

                $curl = curl_init();
                
                $url="https://www.instantpay.in/ws/dmi/remitter_details";
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($request), 
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Accept: application/json"
                    ),
                ));

                $response = curl_exec($curl);  

                curl_close($curl);
      
                if ($response) {///response true 

                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Customer Details","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                    $response = json_decode($response,TRUE);
                                
                    if(is_array($response)){
                        if(isset($response['statuscode'])){
                            $mapped_error=$response['statuscode'];
                            $mapped_error_desc=$response['status'];
                        }else{
                            $mapped_error='';
                            $mapped_error_desc='Unknown Error'; 
                        }

                        $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                        if($error_mapping){
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];
                            if($error_mapping['errorcode_id']=='2'){
                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                            }

                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN'){
                                    
                                    if (isset($response['data']) && $response['data']['remitter']['is_verified']==1) {
                                  
                                        // if($response['data']['remitter']['is_verified']==1){
                                      
                                        $data['error'] = 0;  
                                        $data['error_desc'] = null;
                                        $data['response'] = $response;
                                        $data['outletid']=$outletid; 
                                        $data['avlbl_lmt'] = $response['data']['remitter_limit'][0]['limit']['remaining'];
                                        $data['ttl_allwd_lmt'] = $response['data']['remitter_limit'][0]['limit']['total'];
                                        $data['rmtr_mob'] = $response['data']['remitter']['mobile'];
                                        $data['sndr_nam'] = $response['data']['remitter']['name'];
                                        $data['remitter_id'] = $response['data']['remitter']['id'];
                                        //$data['bnef_id'] = $response['data']['beneficiary']['id']?$response['data']['beneficiary']['id']:'';
                                        $data['msg'] =$error_mapping['error_code_desc'];
                                        // }
                                   
                                    
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'need to define exect error message';
                                        $data['msg'] = null; 
                                    }
                                }else{   
                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                    
                                }
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = $error_mapping['error_code_desc'];
                                $data['msg'] = null;
                            }
                        }else{
                            $data['error']=3;
                            $data['msg']='Other Unknown Error'; 
                            $data['error_desc']=null;
                        }
                    }else{
                        $data['error'] = 3;
                        $data['error_desc'] = null;
                        $data['msg'] = 'Customer Details Under Process';
                 

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Customer Details","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                    }
                } else {///response false 
                    $data['error'] = 1;
                    $data['error_desc'] = 'Request Timedout';
                    $data['msg'] = null;

                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Customer Details","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                }   
      
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Mobile Number 1111';
                $data['msg'] = null;
            }
        return $data;   
        
    }



	public function InstantCustomerDetailsFetch_old($user_info, $param, $outletid){
	    $mobile = strip_tags($param);
        $data = array();
       if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
			
       $request['token'] = $this->token; // 'e0c9e38d9219020ecc261d23701748b8';
      
        $request['request']['mobile']=$mobile;
     
        $request['request']['outletid']=$outletid;

        $curl = curl_init();
		  $url="https://www.instantpay.in/ws/dmi/remitter_details";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
 	  
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Customer Details","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									/* print_r($response['data']);exit; */
                                    
								if (isset($response['data'])) {
                                  
								  if($response['data']['remitter']['is_verified']==1){
									  
									$data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 
									$data['avlbl_lmt'] = $response['data']['remitter_limit'][0]['limit']['remaining'];
									$data['ttl_allwd_lmt'] = $response['data']['remitter_limit'][0]['limit']['total'];
									$data['rmtr_mob'] = $response['data']['remitter']['mobile'];
									$data['sndr_nam'] = $response['data']['remitter']['name'];
									$data['remitter_id'] = $response['data']['remitter']['id'];
									//$data['bnef_id'] = $response['data']['beneficiary']['id']?$response['data']['beneficiary']['id']:'';
                                    $data['msg'] =$error_mapping['error_code_desc'];
								  }
                                   
                                    
								}
                                }else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                               
								 $data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Customer Details Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Customer Details","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Customer Details","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   }   
      
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number 1111';
            $data['msg'] = null;
        }
        return $data;	
		
	}
	
	
	//[bank] => ANDRA BANK 
          //  [ifsccode] => ANDB0001640
            //[accountno] => 67576567576576
            //[name] => fregrtgtrhty
           // [remitid] => 6613368
	

    public function AddBeneficiary ($user_info, $param, $outletid){
        $mobile = isset($param['mobile']) ? $param['mobile'] : '';
        $name = isset($param['name']) ? $param['name'] : '';
        $bank = isset($param['bank']) ? $param['bank'] : '';
        $ifsccode = isset($param['ifsccode']) ? $param['ifsccode'] : '';
        $accountno=isset($param['accountno']) ? $param['accountno'] : '';
        $remitterid = isset($param['remitid']) ? $param['remitid'] : '';
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
            if (preg_match('/^[A-Za-z .]+$/', $name)) {         
    /*  if (preg_match('/^[A-Za-z .\-\/]+$/', $bank)) { */
                if (preg_match('/^[A-Za-z0-9]+$/', $ifsccode)) {
                    if (preg_match('/^\d+$/', $accountno)) {
                        if($remitterid!=''){
        //$request['token'] =$this->token;
                            $request['token'] = $this->token;
                            $request['request']['remitterid']=$remitterid;
                            $request['request']['name']=$name;
                            $request['request']['mobile']=$mobile;
                            $request['request']['ifsc']=$ifsccode;
                        
                       
                            $request['request']['account']= $accountno; 
                        
                      
                            $curl = curl_init();
                            $url="https://www.instantpay.in/ws/dmi/beneficiary_register";
                            curl_setopt_array($curl, array(

                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => json_encode($request), 
                                CURLOPT_HTTPHEADER => array(
                                    "Content-Type: application/json",
                                    "Accept: application/json"
                                ),
                            ));

                            $response = curl_exec($curl);  

                            curl_close($curl);

                      
                            if ($response) {///response true 

                                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Add Benef","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                                               
                                $response = json_decode($response,TRUE);
                                                
                                if(is_array($response)){

                                    if(isset($response['statuscode'])){
                                        $mapped_error=$response['statuscode'];
                                        $mapped_error_desc=$response['status'];

                                                            
                                    }else{
                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                                    $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
                             
                                    if($error_mapping){
                                        
                                        $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                                        if($error_mapping['errorcode_id']=='2'){
                                            $error_mapping['error_code']=$error_mapping['error_code'];
                                            $error_mapping['error_code_desc']=$mapped_error_desc;
                                        }


                                        if(isset($response['statuscode'])){
                                            
                                            if($response['statuscode']=='TXN'){
                                                    
                                                if (isset($response['data']) && ($response['data']['beneficiary']['status']==1)) {
                                                
                                                    $data['error'] = 0;  
                                                    $data['error_desc'] = null;
                                                    $data['response'] = $response;
                                                    $data['outletid']=$outletid; 
                                                    $data['bnef_id'] = $response['data']['beneficiary']['id'];
                                                    $data['msg'] =$response['status']; 
                                                
                                                 
                                                }else{
                                                  //// OTP validation required  send  to Beneficiary Registration (Validate) api heat////
                                                 
                                                    $data['error'] = 4;  
                                                    $data['error_desc'] =null;
                                                    $data['response'] = $response;
                                                    $data['outletid']=$outletid; 
                                                    $data['msg'] =$response['status'];
                                                    $data['remitterid'] =$response['data']['remitter']['id'];
                                                    $data['bnef_id'] = $response['data']['beneficiary']['id'];
                                                }
                                            } else{   

                                                $data['error'] = 1;
                                                $data['error_desc'] =$error_mapping['error_code_desc'];
                                                $data['msg'] = null;
                                                
                                            }
                                        }else{
                                            $data['error'] = 1;
                                            $data['error_desc'] = $error_mapping['error_code_desc'];
                                            $data['msg'] = null;

                                        }

                                    }else{
                                        $data['error']=3;
                                        $data['msg']='Other Unknown Error'; 
                                        $data['error_desc']=null;
                                    }
                                }else{
                                    $data['error'] = 3;
                                    $data['error_desc'] = null;
                                    $data['msg'] = 'Add Beneficiary Under Process';

                                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Add Benef","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                }
                            } else {///response false 
                                $data['error'] = 1;
                                $data['error_desc'] = 'Request Timedout';
                                $data['msg'] = null;

                                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Add Benef","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                            } 
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Remitter Not Exsist';
                            $data['msg'] = null;
                            
                        }
                    }else{
                        $data['error'] = 1;
                        $data['error_desc'] = 'Invalid Account Number';
                        $data['msg'] = null;
                        
                    }
                }else{
                    $data['error'] = 1;
                    $data['error_desc'] = 'Invalid IFSC Name';
                    $data['msg'] = null;
                    
                }
                /* } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Bank Name';
                $data['msg'] = null;
            } */
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Name';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;   
    }
	
	public function AddBeneficiary_old ($user_info, $param, $outletid){
	    $mobile = isset($param['mobile']) ? $param['mobile'] : '';
        $name = isset($param['name']) ? $param['name'] : '';
        $bank = isset($param['bank']) ? $param['bank'] : '';
		$ifsccode = isset($param['ifsccode']) ? $param['ifsccode'] : '';
		$accountno=isset($param['accountno']) ? $param['accountno'] : '';
		$remitterid = isset($param['remitid']) ? $param['remitid'] : '';
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
        if (preg_match('/^[A-Za-z .]+$/', $name)) {			
	/* 	if (preg_match('/^[A-Za-z .\-\/]+$/', $bank)) { */
		if (preg_match('/^[A-Za-z0-9]+$/', $ifsccode)) {
		if (preg_match('/^\d+$/', $accountno)) {
		if($remitterid!=''){
		//$request['token'] =$this->token;
         $request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
	    $request['request']['remitterid']=$remitterid;
		$request['request']['name']=$name;
		 $request['request']['mobile']=$mobile;
		$request['request']['ifsc']=$ifsccode;
		
       
		$request['request']['account']='11114051627'; 
		
      
        $curl = curl_init();
		$url="https://www.instantpay.in/ws/dmi/beneficiary_register";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
       
      
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Add Benef","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									
								if (isset($response['data'])) {
                                 if (($response['data']['beneficiary']['status']==1)) {
									
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 
									$data['bnef_id'] = $response['data']['beneficiary']['id'];
                                    $data['msg'] =$response['status']; 
									
									/* 	$data['error'] = 4;  
                                    $data['error_desc'] =null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 
                                    $data['msg'] =$response['status'];
									$data['remitterid'] =$response['data']['remitter']['id'];
									$data['bnef_id'] = $response['data']['beneficiary']['id']; */
									 
								 }else{
									 //// OTP validation required  send  to Beneficiary Registration (Validate) api heat////
									 
									$data['error'] = 4;  
                                    $data['error_desc'] =null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 
                                    $data['msg'] =$response['status'];
									$data['remitterid'] =$response['data']['remitter']['id'];
									$data['bnef_id'] = $response['data']['beneficiary']['id'];
									 
									 
									 
								 }
									 }
                                }
								else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                              
								 $data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Add Beneficiary Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Add Benef","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Add Benef","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   } 
		}else{
			$data['error'] = 1;
                $data['error_desc'] = 'Remitter Not Exsist';
                $data['msg'] = null;
			
		}
		}else{
			    $data['error'] = 1;
                $data['error_desc'] = 'Invalid Account Number';
                $data['msg'] = null;
			
		}
		}else{
			
			    $data['error'] = 1;
                $data['error_desc'] = 'Invalid IFSC Name';
                $data['msg'] = null;
			
		}
				/* } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Bank Name';
                $data['msg'] = null;
            } */
		} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Name';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;	
	}
	 	
	 	public function InstantBenefOtpValidation($user_info, $param,$outletid){
		$bnef_id = isset($param['bnef_id']) ? $param['bnef_id'] : '';
        $remitterid = isset($param['remitterid']) ? $param['remitterid'] : '';
        $otp = isset($param['otp']) ? $param['otp'] : '';
        $data = array();
        if ($bnef_id!=='') {
        if (!$remitterid=='') {
		if (preg_match('/^\d{6}$/', $otp)) {
		//$request['token'] =$this->token;
		$request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
	    $request['request']['remitterid']=$remitterid;
		$request['request']['beneficiaryid']=$bnef_id;
		$request['request']['otp']=$otp;
        $curl = curl_init();
		$url= "https://www.instantpay.in/ws/dmi/beneficiary_register_validate";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL =>$url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
        //echo $response;
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Benef Otp Validation","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									
								if (isset($response['data'])) {
                                 
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 

                                  
                                     $data['msg'] =$response['status'];
                                    
									 }
                                } 
								else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
									$data['response'] = $response;
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                              
								 $data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Benef Otp Validation Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Benef Otp Validation","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Benef Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   }  

				} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid OTP';
                $data['msg'] = null;
            }
		} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Remitter Not exsist';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Beneficiary Not exsist';
            $data['msg'] = null;
        }
        return $data;		
	}
	
		public function InstantBenefRESENDOtpValidation($user_info, $param,$outletid){
		$bnef_id = isset($param['bnef_id']) ? $param['bnef_id'] : '';
        $remitterid = isset($param['remitterid']) ? $param['remitterid'] : '';
        $data = array();
        if ($bnef_id!=='') {
        if (!$remitterid=='') {
		//$request['token'] =$this->token;
		$request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
	    $request['request']['remitterid']=$remitterid;
		$request['request']['beneficiaryid']=$bnef_id;
		$request['request']['outletid']='1';
        $curl = curl_init();
		$url= "https://www.instantpay.in/ws/dmi/beneficiary_resend_otp";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL =>$url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
       // echo $response;
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Benef Rsend Otp Validation","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									
								if (isset($response['data'])) {
                                 
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 

                                  
                                     $data['msg'] =$response['status'];
                                    
									 }
                                } 
								else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
									$data['response'] = $response;
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                               
								$data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Benef Rsend Otp Validation Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Benef Rsend Otp Validation","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Benef Rsend Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   }  

				
		} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Remitter Not exsist';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Beneficiary Not exsist';
            $data['msg'] = null;
        }
        return $data;		
	}
	

    public function DeleteBeneficiary ($user_info, $param, $outletid){

        $beneid=isset($param['beneid']) ? $param['beneid'] : '';
        $remitterid = isset($param['remitid']) ? $param['remitid'] : '';
        $data = array();
        if ($beneid!='') {
            if($remitterid!=''){
            $request['token'] = $this->token;
            $request['request']['remitterid']= $remitterid;//'30406';
            $request['request']['beneficiaryid']= $beneid;//'13198349';
            $request['request']['outletid']='1';
        
       
      
            $curl = curl_init();
            $url="https://www.instantpay.in/ws/dmi/beneficiary_remove";
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($request), 
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Accept: application/json"
                ),
            ));

            $response = curl_exec($curl);  

            curl_close($curl);
        //echo $response;
        
            if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY delete Benef","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                $response = json_decode($response,TRUE);
                                
                if(is_array($response)){
                    if(isset($response['statuscode'])){
                        $mapped_error=$response['statuscode'];
                        $mapped_error_desc=$response['status'];
                    }else{
                        $mapped_error='';
                        $mapped_error_desc='Unknown Error'; 
                    }
                    $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                    if($error_mapping){
                        
                        $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                        if($error_mapping['errorcode_id']=='2'){
                            $error_mapping['error_code']=$error_mapping['error_code'];
                            $error_mapping['error_code_desc']=$mapped_error_desc;
                        }


                        if(isset($response['statuscode'])){
                            if($response['statuscode']=='TXN'){
                                if (isset($response['data']) && $response['data']['otp']==1) {
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
                                    $data['outletid']=$outletid; 
                                
                                    $data['msg'] =$response['status'];
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Otp issue';
                                    $data['msg'] = null;
                                }
                            }else{   
                                $data['error'] = 1;
                                $data['error_desc'] =$error_mapping['error_code_desc'];
                                $data['msg'] = null;
                            }
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = $error_mapping['error_code_desc'];
                            $data['msg'] = null;
                        }

                    }else{
                        $data['error']=3;
                        $data['msg']='Other Unknown Error'; 
                        $data['error_desc']=null;
                    }
                                    
                }else{
                    $data['error'] = 3;
                    $data['error_desc'] = null;
                    $data['msg'] = 'Delete Beneficiary Under Process';
                                                   
                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY delete Benef","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                }
            } else {///response false 
                $data['error'] = 1;
                $data['error_desc'] = 'Request Timedout';
                $data['msg'] = null;

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Delete Benef","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
            } 
        }else{
            $data['error'] = 1;
            $data['error_desc'] = 'Remitter Not Exsist';
            $data['msg'] = null;
            
        }
    }else{
        $data['error'] = 1;
        $data['error_desc'] = 'Beneficiary Not Exsist';
        $data['msg'] = null;
        
    }
        
    return $data;   
    }
	
		public function DeleteBeneficiary_old ($user_info, $param,$outletid){

		$beneid=isset($param['beneid']) ? $param['beneid'] : '';
		$remitterid = isset($param['remitid']) ? $param['remitid'] : '';
        $data = array();
		if ($beneid!='') {
		if($remitterid!=''){
		//$request['token'] =$this->token;
         	$request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
	    $request['request']['remitterid']='30406';
		$request['request']['beneficiaryid']='13198349';
		$request['request']['outletid']='1';
		
       
      
        $curl = curl_init();
		$url="https://www.instantpay.in/ws/dmi/beneficiary_remove";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
        //echo $response;
        
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY delete Benef","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									
								if (isset($response['data'])) {
                                 if (($response['data']['otp']==1)) {
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 
									
                                    $data['msg'] =$response['status'];
									 
								 }
									 }
                                }
								else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                              
								 $data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;


                                
                                
                                                

                            }



                                    
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Delete Beneficiary Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY delete Benef","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Delete Benef","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            


                   } 
		}else{
			$data['error'] = 1;
                $data['error_desc'] = 'Remitter Not Exsist';
                $data['msg'] = null;
			
		}
		}else{
			    $data['error'] = 1;
                $data['error_desc'] = 'Beneficiary Not Exsist';
                $data['msg'] = null;
			
		}
		
        return $data;	
	}
	
	public function InstantOtpDelbenfValidation($user_info, $param,$outletid){
		
		$beneid = isset($param['beneid']) ? $param['beneid'] : '';
        $remitterid = isset($param['remitid']) ? $param['remitid'] : '';
        $otp = isset($param['otp']) ? $param['otp'] : '';
		
        $data = array();
        if ($beneid!=='') {
        if ($remitterid!=='') {
		if (preg_match('/^\d{6}$/', $otp)) {
		//$request['token'] =$this->token;
        $request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
		$request['request']['beneficiaryid']=$beneid;
		$request['request']['remitterid']=$remitterid;
		$request['request']['otp']=$otp;
		$request['request']['outletid']='1';
		
        $curl = curl_init();
		$url="https://www.instantpay.in/ws/dmi/beneficiary_remove_validate";
        curl_setopt_array($curl, array(
		
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($request), 
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
           
            "Accept: application/json"
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
      
          if ($response) {///response true 

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Delete Benf Otp Validation","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                    $response = json_decode($response,TRUE);
								
                                    if(is_array($response)){
                                     

                                    if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }


                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,8);  
             
                            if($error_mapping){
						
                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                            if($error_mapping['errorcode_id']=='2')
                            {
                                

                                $error_mapping['error_code']=$error_mapping['error_code'];
                                $error_mapping['error_code_desc']=$mapped_error_desc;
                               

                            }


                            if(isset($response['statuscode'])){
                            
                                if($response['statuscode']=='TXN')
                                {
									
								if (isset($response['data'])) {
                                 
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['response'] = $response;
									$data['outletid']=$outletid; 

                                  
                                     $data['msg'] =$response['status'];
                                    
								}
									 
                                } else{   

                                    $data['error'] = 1;
                                    $data['error_desc'] =$error_mapping['error_code_desc'];
									$data['response'] = $response;
                                    $data['msg'] = null;
                                    
                                }

                                
                            
                            }else{


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                  

                            }

                            }else{

                               
								$data['error']=3;
								$data['msg']='Other Unknown Error'; 
								$data['error_desc']=null;

                            }
         
                    }else{
                                $data['error'] = 3;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Delete Benf Otp Validation Under Process';
                                                   

                 

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Delete Benf Otp Validation","response"=>"Invalid xml format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                  


                    }
                    } else {///response false 


                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Request Timedout';
                                    $data['msg'] = null;

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>($request),"req_for"=>"INSTANTPAY Delte Benf Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                     $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                   }  

				} else {
					
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid OTP';
                $data['msg'] = null;
            }
		} else {
                $data['error'] = 1;
                $data['error_desc'] = 'Remitter Not exsist';
                $data['msg'] = null;
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Beneficiary Not exsist';
            $data['msg'] = null;
        }
        return $data;		
	}
    function ApiBalanceCheck($uid) {
       
        $url = 'https://www.instantpay.in/ws/api/checkWallet?format=json&token=' . $this->token .' ';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $msg = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // echo $msg;
       // $msg = json_decode($msg, true);
          
        //<xml><Wallet>889.36</Wallet></xml>
        if ($msg != false) {
            $msg = json_decode($msg, true);

            $response = $msg['Wallet'];

                $data['error'] = 0;
                $data['error_desc'] = null;
                $data['msg']['Balance'] = $response;

                

            // if ($response['status_code'] == 'RCS') {
            //     $data['error'] = 0;
            //     $data['error_desc'] = null;
            //     $data['msg']['Balance'] = $response['usable_balance'];
            //     $data['msg']['ResponseMessage'] = $response['desc'];
            // } else {
            //     $data['error'] = 1;
            //     $data['error_desc'] = $response['desc'];
            //     $data['msg'] = null;
            // }
          //  $log = $this->_ci->Api_Model->init_log($uid, $this->ipchk, $url, json_encode($msg), 'Balance Check');

             // $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Balance Check","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

             //                        $log = $this->_ci->Inst_model->Instantlogs($insert_array);
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Request Timed Out';
            $data['msg'] = null;
           // $log = $this->_ci->Api_Model->init_log($uid, $this->ipchk, $url, 'Request Timed Out', 'Balance Check');
        }
        return $data;
    }
	

    function InstantpyCCFCommsionCal($user_info,$chkusr_instnpaysession,$param, $amnt, $chkservicestat, $planid){
            
        $data = array();
        $amnt = strip_tags((int) $amnt);
         if (($amnt) && ($amnt) >= $chkservicestat['min_amt'] && ($amnt) <= $chkservicestat['max_amt']) {
            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) {
                /* [sv_vnd_id] => 807
                [service_id] => 137
                [vendor_id] => 8
                [vendor_key] => AGI
                [rate_charge_type] => FIXED
                [rate_charge_method] => CREDIT
                [margin] => 2.00
                [capping_amount] => 1.00
                [updated_on] => 2020-01-09 11:21:24
                [updated_by] => 53561036 */
                $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                if ($chck_user_pln_dtl) {
                    if ($chkservicestat['billing_model']) {
               
                        if ($chkservicestat['billing_model'] == 'P2A') {
                //P2A//
                        if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                            if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                    $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                    if ($chck_agrd_fee_rng) {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                            $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                        } else if ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {
                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;
                                            if(is_numeric($capng_amnt)){
                                                $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            }
                                            
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later 1';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin. 5556';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;    
                                    }
                                } else {

                                    if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        $rate = $chck_user_pln_dtl['rate'];
                                    } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;
                                        if(is_numeric($capng_amnt)){
                                          $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                        }
                                      
                                        $rate = round($rate, 2);
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Internal Processing Error, Try again later 2';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }
                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin. 4445';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }

                 //P2A//   

                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin. 3334';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }

                /*******calculation of base,appl comm, gst **********/

                 
                        if ($chkservicestat['billing_model'] == 'P2A') {

                                   /*  $amount = $amnt;
                                    $applicable_commission = $rate; ///(Applicable GST)//2
                                    $charged_amount = $amount;
                                    $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                    $base_applicable_commisison = round($base_applicable_commisison, 2);
                                    $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                    $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                    $tdsamount = (($base_applicable_commisison * 5) / 100);
                                    $tdsamount = round($tdsamount, 2); */
                                    
                                    
                                    
                                    
                                    
                                $amount = $amnt;
                                $base_applicable_commisison = $rate; 
                                $applicable_commission =$base_applicable_commisison+(($base_applicable_commisison / 100) *18);
                                $applicable_commission = round($applicable_commission, 2);
                                $extra_chrg = (($amnt * 1) / 100);
                                $extra_chrg = $extra_chrg > '10' ? $extra_chrg : '10';
                                $charged_amount = $amnt + $extra_chrg;
                                $charged_amount = round($charged_amount, 2);
                                
                                $data['error'] = 0;
                                $data['error_desc'] = Null;
                                $data['trnsferamount'] = $amount;
                                $data['base_applicable_commisison'] = $base_applicable_commisison;
                                $data['extra_chrg'] = $extra_chrg;
                                $data['charged_amount'] = $charged_amount;
                            
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin. 2223';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }

                     /*****No parent *****/
                        
                          
                     /****No parent *****/

                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                        return $data;
                    }
                
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Service not allowed, contact admin";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                }
            } else {

                $data['error'] = 1;
                $data['error_desc'] = "Internal Processing Error, Try Again Later 3";
                $data['msg'] = null;
                $data['status'] = 'FAILED';

            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = "Invalid amount";
            $data['msg'] = null;
            $data['status'] = 'FAILED';
        }
        return $data;
    }
	
    function InstantpyCCFCommsionCal_old($user_info,$chkusr_instnpaysession,$param, $amnt, $chkservicestat, $planid){
			
    	$data = array();
        $amnt = strip_tags((int) $amnt);
         if (($amnt) && ($amnt) >= $chkservicestat['min_amt'] && ($amnt) <= $chkservicestat['max_amt']) {
            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) {
				/* [sv_vnd_id] => 807
				[service_id] => 137
				[vendor_id] => 8
				[vendor_key] => AGI
				[rate_charge_type] => FIXED
				[rate_charge_method] => CREDIT
				[margin] => 2.00
				[capping_amount] => 1.00
				[updated_on] => 2020-01-09 11:21:24
				[updated_by] => 53561036 */
            $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                if ($chck_user_pln_dtl) {
                if ($chkservicestat['billing_model']) {
               
				if ($chkservicestat['billing_model'] == 'P2A') {
                //P2A//
                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                    $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amnt);

                                    if ($chck_agrd_fee_rng) {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                            $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                        } else if ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {
                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amnt) / 100;
											if(is_numeric($capng_amnt))
											{
												$rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
											}
                                            
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later 1';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin .';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;    
                                    }
                   } else {

                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        $rate = $chck_user_pln_dtl['rate'];
                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amnt) / 100;
										if(is_numeric($capng_amnt)){
										  $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
										}
                                      
                                        $rate = round($rate, 2);
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Internal Processing Error, Try again later 2';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }
                    }
                    } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                    }

                 //P2A//   

                }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                }

                /*******calculation of base,appl comm, gst **********/

                 
						if ($chkservicestat['billing_model'] == 'P2A') {

                                   /*  $amount = $amnt;
                                    $applicable_commission = $rate; ///(Applicable GST)//2
                                    $charged_amount = $amount;
                                    $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                    $base_applicable_commisison = round($base_applicable_commisison, 2);
                                    $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                    $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                    $tdsamount = (($base_applicable_commisison * 5) / 100);
                                    $tdsamount = round($tdsamount, 2); */
									
									
									
									
									
							    $amount = $amnt;
								$base_applicable_commisison = $rate; 
								$applicable_commission =$base_applicable_commisison+(($base_applicable_commisison / 100) *18);
                                $applicable_commission = round($applicable_commission, 2);
                                $extra_chrg = (($amnt * 1) / 100);
                                $extra_chrg = $extra_chrg > '10' ? $extra_chrg : '10';
                                $charged_amount = $amnt + $extra_chrg;
                                $charged_amount = round($charged_amount, 2);
								  $data['error'] = 0;
                            $data['error_desc'] = Null;
                            $data['trnsferamount'] = $amount;
							$data['base_applicable_commisison'] = $base_applicable_commisison;
							$data['extra_chrg'] = $extra_chrg;
							$data['charged_amount'] = $charged_amount;
                            
                        } 
						else {


                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }

                     /*****No parent *****/
						
						  
                     /****No parent *****/

            } else {

                        $data['error'] = 1;
                        $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                        return $data;
            }
                
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Service not allowed, contact admin";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                }
                 } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = "Internal Processing Error, Try Again Later 3";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';

                                    }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = "Invalid amount";
                $data['msg'] = null;
                $data['status'] = 'FAILED';
            }
      

        return $data;
    }
	

    public function InstantpyMoneyTransfer($user_info, $mobile, $param, $amount, $chkservicestat, $planid,$get_agentcode){
        $test['user_info'] = $user_info;
        $test['mobile'] = $mobile;
        $test['param'] = $param;
        $test['amount'] = $amount;
        $test['chkservicestat'] = $chkservicestat;
        $test['planid'] = $planid;
        $test['get_agentcode'] = $get_agentcode;

        curlRequertLogs($test, 'InstantpyMoneyTransfer', 'instanpayapi');



        $mobile = trim(strip_tags($mobile));
        $bfnum = trim(strip_tags($mobile));
        $bnef_id = isset($param['TXN_REQUEST']['bnef_id']) ? $param['TXN_REQUEST']['bnef_id'] : '';
        $amount = trim(strip_tags($amount));
        $mode = isset($param['TXN_REQUEST']['mode']) ? $param['TXN_REQUEST']['mode'] : '';  
        $trnsfermode = ['IMPS' => 'IMPS', 'NEFT' => 'NEFT'];
        $bank = isset($param['TXN_REQUEST']['bank']) ? $param['TXN_REQUEST']['bank'] : '';
        $benef_name = isset($param['TXN_REQUEST']['name']) ? $param['TXN_REQUEST']['name'] : '';
        $accountno = isset($param['TXN_REQUEST']['accountno']) ? $param['TXN_REQUEST']['accountno'] : '';
        $ifsccode = isset($param['TXN_REQUEST']['ifsccode']) ? $param['TXN_REQUEST']['ifsccode'] : '';
        $extra_chrg = isset($param['CHCKMPINREQ']['extra_chrg']) ? $param['CHCKMPINREQ']['extra_chrg'] : '';
        $base_applicable_commisison = isset($param['CHCKMPINREQ']['base_applicable_commisison']) ? $param['CHCKMPINREQ']['base_applicable_commisison'] : '';
        if (in_array($mode, ($trnsfermode))) {
            if (ctype_digit($mobile) && strlen($mobile) == 10) {    
                if (ctype_digit($amount) && $amount >= $chkservicestat['min_amt'] && $amount <= $chkservicestat['max_amt']) {     
                    if($bnef_id!=''){  
                        $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
                        curlRequertLogs($test, 'InstantpyMoneyTransfer-operator_dtl', 'instanpayapi');
                        if ($operator_dtl) {
                            $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                            curlRequertLogs($test, 'InstantpyMoneyTransfer-chck_user_pln_dtl', 'instanpayapi');
                            if ($chck_user_pln_dtl) {
                            
                                if ($chkservicestat['billing_model']) {
                                    if ($chkservicestat['billing_model'] == 'P2A') {
                            //P2A//
                                        if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                                            if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                                $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amount);

                                                if ($chck_agrd_fee_rng) {

                                                    if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                                        $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                                    } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {
                                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                        $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                                        $rate = ($rate_in_prcmt * $amount) / 100;

                                                        $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                                        $rate = round($rate, 2);
                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Internal Processing Error, Try again later 56';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }
                                                } else {

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Rate configuration issue,contact admin .111';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;    
                                                }
                                            } else {

                                                if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                                    $rate = $chck_user_pln_dtl['rate'];
                                                } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                                    $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                                    $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                                    $rate = ($rate_in_prcmt * $amount) / 100;

                                                    $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                                    $rate = round($rate, 2);
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Internal Processing Error, Try again later 7';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }

                                        //P2A//   

                                    }else{
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }

                                    /*******calculation of base,appl comm, gst **********/

                 
                                    if ($chkservicestat['billing_model'] == 'P2A') {

                                   /*  $amount = $amnt;
                                    $applicable_commission = $rate; ///(Applicable GST)//2
                                    $charged_amount = $amount;
                                    $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                    $base_applicable_commisison = round($base_applicable_commisison, 2);
                                    $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                    $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                    $tdsamount = (($base_applicable_commisison * 5) / 100);
                                    $tdsamount = round($tdsamount, 2); */
                                    
                                        
                                        $amount = $amount;
                                        $base_applicable_commisison = $rate; 
                                        $applicable_commission =$base_applicable_commisison+(($base_applicable_commisison / 100) *18);
                                        $applicable_commission = round($applicable_commission, 2);
                                        $extra_chrg = (($amount * 1) / 100);
                                        $extra_chrg = $extra_chrg > '10' ? $extra_chrg : '10';
                                        $charged_amount = $amount + $extra_chrg;
                                        $charged_amount = round($charged_amount, 2);    
                                        $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                        $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                        $tdsamount = (($base_applicable_commisison * 5) / 100);
                                        $tdsamount = round($tdsamount, 2);  
                                        
                                    }else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;
                                    }

                   ///***********Calculation of parents**********************///////
                                    $common_array=array();
                /*******calculation of base gst ***********/
                                    if ($user_info['parent_id'] != '0') {
                                        $get_retailer_parent_info = $this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                                            if ($get_retailer_parent_info) {
                                                foreach ($get_retailer_parent_info as $key => $value) {
                                
                                                    if($value['role_id']==2 || $value['role_id']==3 ){
                      
                                                        $checkusercustomprice_parnt = $this->_ci->Inst_model->checkuser_pln_dtl($value['role_id'],$value['plan_id'],$chkservicestat['service_id']);
                                               
                                                        if($checkusercustomprice_parnt){
                                                            $prnt_capng_nrmlamnt=$checkusercustomprice_parnt['capping_amount'];
                                                            if($checkusercustomprice_parnt['slab_applicable']==1){
                                                                $chck_agrd_fee_rng_fr_prnt=$this->_ci->Inst_model->check_pln_amnt_rng($checkusercustomprice_parnt['pl_srvc_rl_id'],$checkusercustomprice_parnt['plan_id'],$checkusercustomprice_parnt['service_id'],$amount);
                                    
                                                                if($chck_agrd_fee_rng_fr_prnt){
                                                                    $parentplan_rate=$chck_agrd_fee_rng_fr_prnt['rate'];//rate=11

                                                                }else{

                                                                    $data['error']=1;
                                                                    $data['error_desc']='Rate configuration issue,contact admin';
                                                                    $data['msg']=null;
                                                                    $data['status'] = 'FAILED';
                                                                    return $data;
                                                                }

                                                            }else{
                                                                $parentplan_rate=$checkusercustomprice_parnt['rate'];
                                    
                                                            }
                               
                                                            if ($checkusercustomprice_parnt['charge_method'] == 'CREDIT' && ($checkusercustomprice_parnt['charge_type'] == 'FIXED' || $checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE')) {

                                                                if ($checkusercustomprice_parnt['charge_type'] == 'FIXED') {

                                                        /* * 1> txn amount=$amount
                                                      2> ccf=1%of txn amnt
                                                      ccf = (1%of txn amnt) OR Rs 10(gtr ccf consider)
                                                      3> base of ttl ccf(net ccf bank)= (ccf/118)*100
                                                      4> app_comm=net ccf bank -srvc_rate
                                                      5> base_commsn =(app_comm/118)*100
                                                      6> gst of comm= app_comm- base_commsn
                                                      7> 5% TDS on base_commsn=base_commsn*5/100
                                                     * */
                                                    /** $rate_nrmlprnt=Rs 1** */
                                                    
                                                                    $base_applicable_commisison_fr_nrmlprnt = $parentplan_rate;//base_applicable_commisison_fr_nrmlprnt
                                                                    $base_applicable_commisison_fr_nrmlprnt = ($base_applicable_commisison_fr_nrmlprnt);
                                                                    $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);
                                                                    if (is_numeric($base_applicable_commisison_fr_nrmlprnt) && $base_applicable_commisison_fr_nrmlprnt >= 0) {

                                                                        $applicable_commission_nrmlprnt =$base_applicable_commisison_fr_nrmlprnt+($base_applicable_commisison_fr_nrmlprnt/100)*18;
                                                            
                                                                        $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);

                                                                        $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                                        $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                                        $common_array[] = array(
                                                                            'USERID' => $value['user_id'],
                                                                            'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                            'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                            'TDS' => $tdsamount_fr_nrmlprnt,
                                                                            'GST' => 0,
                                                                        );
                                                      
                                                                    } else {

                                                                        $data['error'] = 1;
                                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                                        $data['msg'] = null;
                                                                        $data['status'] = 'FAILED';
                                                                        return $data;
                                                                    }
                                                                } else if($checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE') {

                                                                    $commission_nrmlprnt_amt = (($parentplan_rate) / 100) * $amount;

                                                                    $commission_nrmlprnt_amt = ($commission_nrmlprnt_amt >= $prnt_capng_nrmlamnt) ? $prnt_capng_nrmlamnt : $commission_nrmlprnt_amt;

                                                                    $base_applicable_commisison_fr_nrmlprnt = ($commission_nrmlprnt_amt);
                                                                    $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);
                                                                    if (is_numeric($base_applicable_commisison_fr_nrmlprnt) && $base_applicable_commisison_fr_nrmlprnt >= 0) {

                                                                        $applicable_commission_nrmlprnt = $base_applicable_commisison_fr_nrmlprnt+($commission_nrmlprnt_amt/100)*18;
                                                                        $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);

                                                                        $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                                        $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                                        $common_array[] = array(
                                                                            'USERID' => $value['user_id'],
                                                                            'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                            'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                            'TDS' => $tdsamount_fr_nrmlprnt,
                                                                            'GST' => 0,
                                                                        );
                                                      
                                                                    } else {

                                                                        $data['error'] = 1;
                                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                                        $data['msg'] = null;
                                                                        $data['status'] = 'FAILED';
                                                                        return $data;
                                                                    }
                                                                } else {
                                                                    $data['error'] = 1;
                                                                    $data['error_desc'] = 'Internal Processing Error, Try again later 8';
                                                                    $data['msg'] = null;
                                                                    $data['status'] = 'FAILED';
                                                                    return $data;
                                                                }
                                                            }else{

                                                                $data['error'] = 1;
                                                                $data['error_desc'] = 'configuration issue,contact admin';
                                                                $data['msg'] = null;
                                                                $data['status'] = 'FAILED';
                                                                return $data;
                                                            }/////charge_method CREDIT charge_type FIXED PERCENT//

                                                        }else{

                                                            $data['error'] = 1;
                                                            $data['error_desc'] = 'No Profile for parent';
                                                            $data['msg'] = null;
                                                            $data['status'] = 'FAILED';
                                                            return $data;
                                                        }

                                                    }

                                                }//end foreach loop


                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Unable to find parent details';
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }

                                        }/***$user_info['parent_id'] != '0'***/
                                          /*****No parent *****/
                                        $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                   
                                        if ($findbalance) {

                                            $openingbal = $findbalance['rupee_balance'];

                                            $closing_balance = $openingbal - $charged_amount;

                                            $identify_commision_from = $findbalance['first_name'] .' '. $findbalance['first_name'] . ' ( ' . $findbalance['mobile'] . ' )';

                                            if ($openingbal >= $charged_amount && $closing_balance >= 0 && $charged_amount != null) {

                                            $transid = txn_remitt_txnid();
                           
                                            $RetailerTxnEntry = array(
                                                "user_id"=>$findbalance['user_id'],
                                                "req_ip"=>ip_address(),
                                                "request_useragent "=>$this->_ci->agent->agent_string(),
                                                "fstpytxn_id"=>$transid,
                                                "sp_id"=>"00",
                                                "opr_ref_no"=>"00",
                                                'customer_no' =>$bfnum,///dynamic//
                                                'scode' => $chkservicestat['code'],
                                                "servicename"=>$chkservicestat['service_name'],
                                                "servicetype"=>$chkservicestat['type'],
                                                "servedby"=>$chkservicestat['served_by'],
                                                "transamt"=>$amount,
                                                "chargeamt"=>$charged_amount,
                                                "openingbal"=>$openingbal,
                                                "closingbal"=>$closing_balance,
                                                "req_dt"=>date('Y-m-d H:i:s'),
                                                "res_dt"=>"0000-00-00 00:00:00",
                                                "ind_rcode"=>'TUP',
                                                "response"=>"Recharge Under Process",
                                                "status"=>"PENDING",
                                                "op1"=>$bfnum,
                                                "op2"=>$benef_name,
                                                "op3"=>$accountno,
                                                "op4"=>$ifsccode,
                                                "op5"=>$extra_chrg,
                                                "op6"=>$mode,
                                                "op7"=>$base_applicable_commisison,
                                                "op10"=>$bank

                                            );

                                            if (($chkservicestat['billing_model'] == 'P2P') || ($chkservicestat['billing_model'] == 'P2A')) {
                                                $CreditHistroyIdCashback = ch_txnid();
                                                $comm_openingbal = $closing_balance;
                                                $basecomm_amount = $base_applicable_commisison;
                                                $comm_closingbal = $comm_openingbal + $basecomm_amount;


                                                $CashbackCrdtEntry_reatiler = array(
                                                    'credit_txnid' => $CreditHistroyIdCashback,
                                                    'user_id' => $findbalance['user_id'],
                                                    'bank_name' => 'N/A',
                                                    'txn_type' => 'CASHBACK',
                                                    'payment_mode' => 'WALLET',
                                                    'amount' => $basecomm_amount,
                                                    'opening_balance' => $comm_openingbal,
                                                    'closing_balance' => $comm_closingbal,
                                                    'updated_on' => date('Y-m-d H:i:s'),
                                                    'reference_number' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                    'remarks' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                    'txn_code' => $transid,
                                                    'status' => 'CREDIT',
                                                    'updated_by' => $findbalance['user_id'],
                                                );


                                                $CreditHistroyTDSId = ch_txnid();
                                                $Tds_opng_bal = $comm_closingbal;
                                                $RetTds = $tdsamount;
                                                $Tds_clsng_bal = $Tds_opng_bal - $RetTds;

                                                $TDSCreditHistoryEntryRetailer = array(
                                                    'credit_txnid' => $CreditHistroyTDSId,
                                                    'user_id' => $findbalance['user_id'],
                                                    'bank_name' => 'N/A',
                                                    'txn_type' => 'TDS',
                                                    'payment_mode' => 'WALLET',
                                                    'amount' => $RetTds,
                                                    'opening_balance' => $Tds_opng_bal,
                                                    'closing_balance' => $Tds_clsng_bal,
                                                    'updated_on' => date('Y-m-d H:i:s'),
                                                    'reference_number' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                    'remarks' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                                    'txn_code' => $transid,
                                                    'status' => 'DEBIT',
                                                    'updated_by' => $findbalance['user_id'],
                                                );



                                                $TaxRecordRet = array(
                                                    'user_id' => $findbalance['user_id'],
                                                    'cbrt_id' => $transid,
                                                    'billing_model' => $chkservicestat['billing_model'],
                                                    'trans_amt' => $amount,
                                                    'charged_amt' => $charged_amount,
                                                    'comm_amnt' => $base_applicable_commisison,
                                                    'tds_amnt' => $tdsamount,
                                                    'gst_amnt' => $gstamount_tobecredited,
                                                    'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
                                                    'tds_status' => 'PENDING',
                                                    'tax_type' => 'CREDIT',
                                                    'created_dt' => date('Y-m-d H:i:s'),
                                                    'updated_by' => $findbalance['user_id'],
                                                    'updated_dt' => date('Y-m-d H:i:')
                                                );

                                            } else {

                                                $TaxRecordRet = array(
                                                    'user_id' => $findbalance['user_id'],
                                                    'cbrt_id' => $transid,
                                                    'billing_model' => $chkservicestat['billing_model'],
                                                    'trans_amt' => $amount,
                                                    'charged_amt' => $charged_amount,
                                                    'comm_amnt' => $base_applicable_commisison,
                                                    'tds_amnt' => $tdsamount,
                                                    'gst_amnt' => $gstamount_tobecredited,
                                                    'gst_status' => 'PENDING',
                                                    'tds_status' => 'PENDING',
                                                    'tax_type' => 'CREDIT',
                                                    'created_dt' => date('Y-m-d H:i:s'),
                                                    'updated_by' => $findbalance['user_id'],
                                                    'updated_dt' => date('Y-m-d H:i:')
                                                );

                                                $CashbackCrdtEntry_reatiler = array();
                                                $TDSCreditHistoryEntryRetailer = array();   
                                            }

                                            $RetailerInsertEntry = $this->_ci->Inst_model->RetailerAllEntryWithoutAdmin($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet);

                                            if ($RetailerInsertEntry) {
                                                $inserted_id = $RetailerInsertEntry;
                                                
                                                $ParentArray = array();
                                               
                                                if ($findbalance['parent_id'] != 0) {
                                       

                                                    if (count($common_array) > 0) {////sup dist/ dist of retailer
                                                        foreach ($common_array as $k => $v) {

                                                            $ParentInfo = $this->_ci->Inst_model->user_info($v['USERID']);


                                                            if ($ParentInfo) {


                                                                $parent_opening = $ParentInfo['rupee_balance'];
                                                                $parent_closeing = $parent_opening + $v['BASCOMM'];
                                                                $ParentArray[$k]['COM'] = array(
                                                                    'credit_txnid' => ch_txnid(),
                                                                    'user_id' => $ParentInfo['user_id'],
                                                                    'bank_name' => 'N/A',
                                                                    'txn_type' => 'COMMISSION',
                                                                    'payment_mode' => 'WALLET',
                                                                    'amount' => $v['BASCOMM'],
                                                                    'opening_balance' => $parent_opening,
                                                                    'closing_balance' => $parent_closeing,
                                                                    'updated_on' => date('Y-m-d H:i:s'),
                                                                    'reference_number' => 'Commission From ' . $identify_commision_from . ' For ' . $transid,
                                                                    'remarks' => 'Commission From ' . $identify_commision_from . ' For ' . $transid . ',Transaction Amount : Rs. ' . $amount,
                                                                    'txn_code' => $transid,
                                                                    'status' => 'CREDIT',
                                                                    'updated_by' => $ParentInfo['user_id']
                                                                );


                                                                $parent_opening_tds = $parent_closeing;
                                                                $parent_closing_tds = $parent_opening_tds - $v['TDS'];

                                                                $ParentArray[$k]['TDS'] = array(
                                                                    'credit_txnid' => ch_txnid(),
                                                                    'user_id' => $ParentInfo['user_id'],
                                                                    'bank_name' => 'N/A',
                                                                    'txn_type' => 'TDS',
                                                                    'payment_mode' => 'WALLET',
                                                                    'amount' => $v['TDS'],
                                                                    'opening_balance' => $parent_opening_tds,
                                                                    'closing_balance' => $parent_closing_tds,
                                                                    'updated_on' => date('Y-m-d H:i:s'),
                                                                    'reference_number' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                                    'remarks' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                                    'txn_code' => $transid,
                                                                    'status' => 'DEBIT',
                                                                    'updated_by' => $ParentInfo['user_id']
                                                                );

                                                                $ParentArray[$k]['TAX'] = array(
                                                                    'user_id' => $v['USERID'],
                                                                    'cbrt_id' => $transid,
                                                                    'billing_model' => 'P2A',
                                                                    'trans_amt' => $amount,
                                                                    'charged_amt' => $charged_amount,
                                                                    'comm_amnt' => $v['BASCOMM'],
                                                                    'tds_amnt' => $v['TDS'],
                                                                    'gst_amnt' => $v['GST'],
                                                                    'gst_status' => 'PENDING',
                                                                    'tds_status' => 'PENDING',
                                                                    'tax_type' => 'CREDIT',
                                                                    'created_dt' => date('Y-m-d H:i:s'),
                                                                    'updated_by' => $ParentInfo['user_id'],
                                                                    'updated_dt' => date('Y-m-s H:i:s')
                                                                );
                                                            }
                                                        }
                                                    }/** *count($common_array) end** */


                                      
                                                    $parent_comission = $this->_ci->Inst_model->parent_commission_without_Admin($ParentArray);

                                        
                                                }///$findbalance['parent_id'] != 0
                                   
                             
                                    
                                                $request['token'] = $this->token; // 'e0c9e38d9219020ecc261d23701748b8';
                                                $request['request']['remittermobile']=$bfnum; 
                                                $request['request']['beneficiaryid']=$bnef_id; 
                                                $request['request']['agentid']=$transid; 
                                                $request['request']['amount']=$amount; 
                                                $request['request']['mode']=$mode;
                                        
                                                $curl = curl_init();
                                                $url="https://www.instantpay.in/ws/dmi/transfer";
                                                curl_setopt_array($curl, array(
                                                
                                                    CURLOPT_URL => $url,
                                                    CURLOPT_RETURNTRANSFER => true,
                                                    CURLOPT_ENCODING => "",
                                                    CURLOPT_MAXREDIRS => 10,
                                                    CURLOPT_TIMEOUT => 0,
                                                    CURLOPT_FOLLOWLOCATION => true,
                                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                    CURLOPT_CUSTOMREQUEST => "POST",
                                                    CURLOPT_POSTFIELDS => json_encode($request), 
                                                    CURLOPT_HTTPHEADER => array(
                                                        "Content-Type: application/json",
                                                        "Accept: application/json"
                                                    ),
                                                ));

                                                $response = curl_exec($curl);  

                                                curl_close($curl);
                                        
                                                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Init Money Transfer-1","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                        
                                                if ($response) {///response true 
                                                    // $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"Init Money Transfer-1","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                    // $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                                    $response = json_decode($response, true);
                                                    if(is_array($response)){
                                    /* {
                                      "statuscode": "TXN",
                                      "status": "SUCCESS",
                                      "data": {
                                        "ipay_id": "1171227201033YKERU",
                                        "ref_no": 1514385633,
                                        "opr_id": 1514385633,
                                        "name": "A",
                                        "opening_bal": "771.91",
                                        "amount": "11.00",
                                        "locked_amt": 0
                                      }
                                    } */   
                                                        // $response=array(
                                                        //     "statuscode"=> "TXN",
                                                        //     "status"=> "SUCCESS",
                                                        //     "data"=> array(
                                                        //         "ipay_id"=> "1171227201033YKERU",
                                                        //         "ref_no"=> 1514385633,
                                                        //         "opr_id"=> 1514385633,
                                                        //         "name"=> "A",
                                                        //         "opening_bal"=> "771.91",
                                                        //         "amount"=> "11.00",
                                                        //         "locked_amt"=> 0  
                                                        //     )
                                                        // );

                                                        if(isset($response['statuscode'])){
                     
                                                            $mapped_error=$response['statuscode'];
                                                            $mapped_error_desc=$response['status'];

                                            
                                                        }else{

                                                            $mapped_error='';
                                                            $mapped_error_desc='Unknown Error'; 

                                                        }

                                                        $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,$chkservicestat['vendor_id']); 
            
                                                        if($error_mapping){


                                                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                           
///check status REFUND ONLY WHEN STAUS IS NOT COMING//

                                                            if(isset($response['status'])){
                            
                                                                if($response['status']=='SUCCESS'){
                                                                    // $txnstatus=$response['status'];
                                                                      /*  $txnstatus='FAILED';
                                                                        $data['error'] = 1; */
                                                                    $data['error'] = 0; 
                                                                    $data['error_desc'] = null;
                                                                    $data['msg'] = $error_mapping['error_code_desc'];
                                                                    // $data['TxnId'] = isset($response['data']['ref_no']) ? $response['data']['ref_no'] : '00'; 
                                                                    // $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                                    // $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                                    // $fstpyresponsecode =$error_mapping['error_code'];
                                                                    // $fstpayresponse =$error_mapping['error_code_desc'];
                                                                    // $data['status'] = $response['status'];         
                                  
                                                                }else if($response['status']=='PENDING') { ///Timeout
                                                                                                    
                                                                    // $txnstatus=$response['status'];
                                                                    $data['error'] = 3;
                                                                    $data['error_desc'] = null;
                                                                    $data['msg'] = $error_mapping['error_code_desc'];
                                                                    //$data['TxnId'] = $transid;
                                                                    // $data['TxnId'] = isset($response['ref_no']) ? $response['ref_no'] : '00';
                                                                    // $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                                    // $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';

                                                                    // $data['status'] =$response['status'];


                                                                    // $fstpyresponsecode = $error_mapping['error_code'];
                                                                    // $fstpayresponse = $error_mapping['error_code_desc'];
                                                        
                                    
                                                                } else{

                                                                    // $txnstatus='FAILED';
                                                                    $data['error'] = 3;
                                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                                    $data['msg'] = null;
                                                                    // $data['TxnId'] = '';
                                                                    // $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                                    // $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                                    // $data['status'] = $response['status'];
                                                                    // $fstpyresponsecode = $error_mapping['error_code'];
                                                                    // $fstpayresponse = $error_mapping['error_code_desc'];
                                                     
                                                                }

                                                                $data['TxnId'] = isset($response['data']['ref_no']) ? $response['data']['ref_no'] : '00'; 
                                                                $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                                                $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                                                $data['status'] = $response['status'];

                                
                            
                                                            }else{/// if status is not coming transaction falied
                              

                                                                $data['error'] = 1;
                                                                $data['error_desc'] = 'Transaction has failed due to some technical error';
                                                                $data['msg'] = null;
                                                                $data['TxnId'] = '00';
                                                                $data['OPTId'] = '00';
                                                                $data['TranId'] = '00';
                                                                $data['status'] = 'FAILED';
                                                                // $fstpyresponsecode = $error_mapping['error_code'];
                                                                // $fstpayresponse = $error_mapping['error_code_desc'];

                                                    

                                                            }

                                                        }else{

                                                            $data['error']=3;
                                                            $data['msg']=$mapped_error_desc; 
                                                            $data['error_desc']=null;
                                                            $txnstatus='PENDING';
                                                            $error_mapping['error_code']='OUE';
                                                            $error_mapping['error_code_desc']=$data['msg'];
                                                            $data['TxnId'] = $transid;
                                                            $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                            $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                            $data['status'] = $txnstatus;
                                                            // $fstpyresponsecode = $error_mapping['error_code'];
                                                            // $fstpayresponse = $error_mapping['error_code_desc'];

                                                        }



                                                        $update_dt_array = array(
                                                            "sp_id" => isset($response['ipay_id'])?$response['ipay_id']:'00',
                                                            "opr_ref_no" => isset($response['opr_id']) ? $response['opr_id'] : '00',
                                                            "sp_respcode" => $error_mapping['error_code'],
                                                            "sp_respdesc" => $error_mapping['error_code_desc'],
                                                            "sp_response" => json_encode($response),
                                                            "res_dt" => date('Y-m-d H:i:s'),
                                                            "ind_rcode" => $error_mapping['error_code'],
                                                            "response" => $error_mapping['error_code_desc'],
                                                            "status" => $data['status'],
                                                            "upd_id" => $inserted_id
                                                        );


                                                        $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array); 
                                    
                                                    }else{
                                                        $data['error'] = 3;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = 'Some technical issue';
                                                        $data['page'] = 'hgjgfhfhgf';
                                                        $data['TxnId'] = '00';
                                                        $data['OPTId'] = '00';
                                                        $data['TranId'] = '00';
                                                        $data['status'] = 'FAILED';
                                                        // $fstpayresponse = 'Transaction Under Process';
                                                        $status = $data['status'];


                                                        // $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Money Transfer","response"=>"Invalid format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                        // $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                        $update_dt_array = array(
                                                            "sp_id" => "00",
                                                            "opr_ref_no" => "00",
                                                            "sp_respcode" => "TMDOUT",
                                                            "sp_respdesc" => 'Timed Out',
                                                            "sp_response" => "Request Timedout from Vendor",
                                                            "res_dt" => date('Y-m-d H:i:s'),
                                                            "ind_rcode" => 'TUP',
                                                            "response" => "Some technical issue",
                                                            "status" => "FAILED",
                                                            "upd_id" => $inserted_id
                                                        );



                                                        $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                                                    }
                                                } else {///response false 
                                                
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process';
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
                                                
                                                    // $fstpayresponse = 'Transaction Under Process';
                                                    $status = $data['status'];




                                                    // $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Money Transfer","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                                    // $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                    $update_dt_array = array(
                                                        "sp_id" => "00",
                                                        "opr_ref_no" => "00",
                                                        "sp_respcode" => "TMDOUT",
                                                        "sp_respdesc" => 'Timed Out',
                                                        "sp_response" => "Request Timedout from Vendor",
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => 'TUP',
                                                        "response" => "Transaction Under Process",
                                                        "status" => "PENDING",
                                                        "upd_id" => $inserted_id
                                                    );



                                                    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                                                }

                                                             

                                    ///response///    
                                   

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Internal Processing Error, Try Again Later 9";
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = "Insufficient Balance";
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                        }

                                    }else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Unable to find user details1';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                    }

                     /****No parent *****/
                    ///***********Calculation of parents**********************///////

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    // return $data;
                                }
                
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Service not allowed, contact admin";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }
                        } else {

                            $data['error'] = 1;
                            $data['error_desc'] = "Internal Processing Error, Try Again Later 90";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';

                        }      
            
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = "Beneficiary Not exsist";
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                                
                    }           
                } else {
                    
                    $data['error'] = 1;
                    $data['error_desc'] = "Please transfer the amount between ? " . $chkservicestat['min_amt'] . " and ? " . $chkservicestat['max_amt'] . " w/o decimals";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                          
                }
            } else {
                
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Mobile Number';
                $data['msg'] = null;
                $data['status'] = 'FAILED';
            }  
        } else {
            
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Transaction Mode';
            $data['msg'] = null;
        }
        return $data;
    }

	
	public function InstantpyMoneyTransfer_old($user_info, $mobile, $param, $amount, $chkservicestat, $planid,$get_agentcode){
	    $mobile = trim(strip_tags($mobile));
        $bfnum = trim(strip_tags($mobile));
        $bnef_id = isset($param['TXN_REQUEST']['bnef_id']) ? $param['TXN_REQUEST']['bnef_id'] : '';
        $amount = trim(strip_tags($amount));
        $mode = isset($param['TXN_REQUEST']['mode']) ? $param['TXN_REQUEST']['mode'] : '';	
		$trnsfermode = ['IMPS' => 'IMPS', 'NEFT' => 'NEFT'];
		$bank = isset($param['TXN_REQUEST']['bank']) ? $param['TXN_REQUEST']['bank'] : '';
		$benef_name = isset($param['TXN_REQUEST']['name']) ? $param['TXN_REQUEST']['name'] : '';
        $accountno = isset($param['TXN_REQUEST']['accountno']) ? $param['TXN_REQUEST']['accountno'] : '';
		$ifsccode = isset($param['TXN_REQUEST']['ifsccode']) ? $param['TXN_REQUEST']['ifsccode'] : '';
		$extra_chrg = isset($param['CHCKMPINREQ']['extra_chrg']) ? $param['CHCKMPINREQ']['extra_chrg'] : '';
		$base_applicable_commisison = isset($param['CHCKMPINREQ']['base_applicable_commisison']) ? $param['CHCKMPINREQ']['base_applicable_commisison'] : '';
		if (in_array($mode, ($trnsfermode))) {
        if (ctype_digit($mobile) && strlen($mobile) == 10) {    
        if (ctype_digit($amount) && $amount >= $chkservicestat['min_amt'] && $amount <= $chkservicestat['max_amt']) {     
		if($bnef_id!=''){  
		$operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
        if ($operator_dtl) {
            $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                if ($chck_user_pln_dtl) {
				
                if ($chkservicestat['billing_model']) {
                if ($chkservicestat['billing_model'] == 'P2A') {
                //P2A//
                if ($chck_user_pln_dtl['charge_method'] == 'CREDIT') {
                if ($chck_user_pln_dtl['slab_applicable'] == 1) {
                                    $chck_agrd_fee_rng = $this->_ci->Inst_model->check_pln_amnt_rng($chck_user_pln_dtl['pl_srvc_rl_id'], $chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amount);

                                    if ($chck_agrd_fee_rng) {

                                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {

                                            $rate = $chck_agrd_fee_rng['rate']; //rate=11
                                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {
                                            $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                            $rate_in_prcmt = $chck_agrd_fee_rng['rate']; //rate=11
                                            $rate = ($rate_in_prcmt * $amount) / 100;

                                            $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                            $rate = round($rate, 2);
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try again later 56';
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                            return $data;
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Rate configuration issue,contact admin .111';
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                        return $data;    
                                    }
                   } else {

                        if ($chck_user_pln_dtl['charge_type'] == 'FIXED') {
                                        $rate = $chck_user_pln_dtl['rate'];
                        } elseif ($chck_user_pln_dtl['charge_type'] == 'PERCENTAGE') {

                                        $capng_amnt = $chck_user_pln_dtl['capping_amount'];
                                        $rate_in_prcmt = $chck_user_pln_dtl['rate']; //rate=11
                                        $rate = ($rate_in_prcmt * $amount) / 100;

                                        $rate = ($rate <= $capng_amnt) ? $rate : $capng_amnt;
                                        $rate = round($rate, 2);
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Internal Processing Error, Try again later 7';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }
                    }
                    } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Rate configuration issue, contact admin.';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                    }

                 //P2A//   

                }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                }

                /*******calculation of base,appl comm, gst **********/

                 
						if ($chkservicestat['billing_model'] == 'P2A') {

                                   /*  $amount = $amnt;
                                    $applicable_commission = $rate; ///(Applicable GST)//2
                                    $charged_amount = $amount;
                                    $base_applicable_commisison = (($applicable_commission / 118) * 100);
                                    $base_applicable_commisison = round($base_applicable_commisison, 2);
                                    $gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                    $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                    $tdsamount = (($base_applicable_commisison * 5) / 100);
                                    $tdsamount = round($tdsamount, 2); */
									
										
								$amount = $amount;
								$base_applicable_commisison = $rate; 
								$applicable_commission =$base_applicable_commisison+(($base_applicable_commisison / 100) *18);
                                $applicable_commission = round($applicable_commission, 2);
                                $extra_chrg = (($amount * 1) / 100);
                                $extra_chrg = $extra_chrg > '10' ? $extra_chrg : '10';
                                $charged_amount = $amount + $extra_chrg;
                                $charged_amount = round($charged_amount, 2);	
								$gstamount_tobecredited = ($applicable_commission - $base_applicable_commisison);
                                $gstamount_tobecredited = round($gstamount_tobecredited, 2);
                                $tdsamount = (($base_applicable_commisison * 5) / 100);
                                $tdsamount = round($tdsamount, 2);	
										
									
									
								
								
                            
                        }else {


                            $data['error'] = 1;
                            $data['error_desc'] = 'Rate configuration issue, contact admin.';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }

                   ///***********Calculation of parents**********************///////
				       $common_array=array();
                /*******calculation of base gst ***********/
                        if ($user_info['parent_id'] != '0') {
                        $get_retailer_parent_info = $this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                         if ($get_retailer_parent_info) {
                            foreach ($get_retailer_parent_info as $key => $value) {
								
                            if($value['role_id']==2 || $value['role_id']==3 ){
                      
                            $checkusercustomprice_parnt = $this->_ci->Inst_model->checkuser_pln_dtl($value['role_id'],$value['plan_id'],$chkservicestat['service_id']);
                                               
                                if($checkusercustomprice_parnt){
                                $prnt_capng_nrmlamnt=$checkusercustomprice_parnt['capping_amount'];
                                if($checkusercustomprice_parnt['slab_applicable']==1){
                                $chck_agrd_fee_rng_fr_prnt=$this->_ci->Inst_model->check_pln_amnt_rng($checkusercustomprice_parnt['pl_srvc_rl_id'],$checkusercustomprice_parnt['plan_id'],$checkusercustomprice_parnt['service_id'],$amount);
                                	
                                if($chck_agrd_fee_rng_fr_prnt){
                               
                         
                                    $parentplan_rate=$chck_agrd_fee_rng_fr_prnt['rate'];//rate=11

                                }else{

                                             $data['error']=1;
                                             $data['error_desc']='Rate configuration issue,contact admin';
                                             $data['msg']=null;
											 $data['status'] = 'FAILED';
                                             return $data;
                                }

                                }else{

                                      $parentplan_rate=$checkusercustomprice_parnt['rate'];


                                
                                    
                                }
                               
if ($checkusercustomprice_parnt['charge_method'] == 'CREDIT' && ($checkusercustomprice_parnt['charge_type'] == 'FIXED' || $checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE')) {

                                    if ($checkusercustomprice_parnt['charge_type'] == 'FIXED') {

                                                        /* * 1> txn amount=$amount
                                                      2> ccf=1%of txn amnt
                                                      ccf = (1%of txn amnt) OR Rs 10(gtr ccf consider)
                                                      3> base of ttl ccf(net ccf bank)= (ccf/118)*100
                                                      4> app_comm=net ccf bank -srvc_rate
                                                      5> base_commsn =(app_comm/118)*100
                                                      6> gst of comm= app_comm- base_commsn
                                                      7> 5% TDS on base_commsn=base_commsn*5/100
                                                     * */
                                                    /** $rate_nrmlprnt=Rs 1** */
													
                                                    $base_applicable_commisison_fr_nrmlprnt = $parentplan_rate;//base_applicable_commisison_fr_nrmlprnt
                                                    $base_applicable_commisison_fr_nrmlprnt = ($base_applicable_commisison_fr_nrmlprnt);
                                                    $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);
                                                    if (is_numeric($base_applicable_commisison_fr_nrmlprnt) && $base_applicable_commisison_fr_nrmlprnt >= 0) {

                                                        $applicable_commission_nrmlprnt =$base_applicable_commisison_fr_nrmlprnt+($base_applicable_commisison_fr_nrmlprnt/100)*18;
                                                            $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }
                                                } else if($checkusercustomprice_parnt['charge_type'] == 'PERCENTAGE') {

                                                    $commission_nrmlprnt_amt = (($parentplan_rate) / 100) * $amount;

                                                    $commission_nrmlprnt_amt = ($commission_nrmlprnt_amt >= $prnt_capng_nrmlamnt) ? $prnt_capng_nrmlamnt : $commission_nrmlprnt_amt;

                                                    $base_applicable_commisison_fr_nrmlprnt = ($commission_nrmlprnt_amt);
                                                    $base_applicable_commisison_fr_nrmlprnt = round($base_applicable_commisison_fr_nrmlprnt, 2);
                                                    if (is_numeric($base_applicable_commisison_fr_nrmlprnt) && $base_applicable_commisison_fr_nrmlprnt >= 0) {

                                                            $applicable_commission_nrmlprnt = $base_applicable_commisison_fr_nrmlprnt+($commission_nrmlprnt_amt/100)*18;
                                                            $applicable_commission_nrmlprnt = round($applicable_commission_nrmlprnt, 2);

                                                            $tdsamount_fr_nrmlprnt = (($base_applicable_commisison_fr_nrmlprnt * 5) / 100);
                                                            $tdsamount_fr_nrmlprnt = round($tdsamount_fr_nrmlprnt, 2);

                                                            $common_array[] = array(
                                                                'USERID' => $value['user_id'],
                                                                'APPCOMM' => $applicable_commission_nrmlprnt,
                                                                'BASCOMM' => $base_applicable_commisison_fr_nrmlprnt,
                                                                'TDS' => $tdsamount_fr_nrmlprnt,
                                                                'GST' => 0,
                                                            );
                                                      
                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Margins configuration issue, contact admin';
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
                                                        return $data;
                                                    }
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Internal Processing Error, Try again later 8';
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }
                               }else{

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'configuration issue,contact admin';
                                    $data['msg'] = null;
									$data['status'] = 'FAILED';
                                    return $data;
                            }/////charge_method CREDIT charge_type FIXED PERCENT//

                            }else{

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'No Profile for parent';
                                    $data['msg'] = null;
									$data['status'] = 'FAILED';
                                    return $data;
                            }

                            }

                            }//end foreach loop


                            } else {


                                $data['error'] = 1;
                                $data['error_desc'] = 'Unable to find parent details';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                        }/***$user_info['parent_id'] != '0'***/
						  /*****No parent *****/
                        $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                   
                        if ($findbalance) {

                            $openingbal = $findbalance['rupee_balance'];

                            $closing_balance = $openingbal - $charged_amount;

                            $identify_commision_from = $findbalance['first_name'] .' '. $findbalance['first_name'] . ' ( ' . $findbalance['mobile'] . ' )';

                        if ($openingbal >= $charged_amount && $closing_balance >= 0 && $charged_amount != null) {

                           $transid = txn_remitt_txnid();
                           
                           $RetailerTxnEntry = array(
                            "user_id"=>$findbalance['user_id'],
                            "req_ip"=>ip_address(),
                            "request_useragent "=>$this->_ci->agent->agent_string(),
                            "fstpytxn_id"=>$transid,
                            "sp_id"=>"00",
                            "opr_ref_no"=>"00",
                            'customer_no' =>$bfnum,///dynamic//
                            'scode' => $chkservicestat['code'],
                            "servicename"=>$chkservicestat['service_name'],
                            "servicetype"=>$chkservicestat['type'],
                            "servedby"=>$chkservicestat['served_by'],
                            "transamt"=>$amount,
                            "chargeamt"=>$charged_amount,
                            "openingbal"=>$openingbal,
                            "closingbal"=>$closing_balance,
                            "req_dt"=>date('Y-m-d H:i:s'),
                            "res_dt"=>"0000-00-00 00:00:00",
                            "ind_rcode"=>'TUP',
                            "response"=>"Recharge Under Process",
                            "status"=>"PENDING",
                            "op1"=>$bfnum,
							 "op2"=>$benef_name,
							  "op3"=>$accountno,
							    "op4"=>$ifsccode,
								  "op5"=>$extra_chrg,
							   "op6"=>$mode,
							     "op7"=>$base_applicable_commisison,
							    "op10"=>$bank
                          
                           );

                            if (($chkservicestat['billing_model'] == 'P2P') || ($chkservicestat['billing_model'] == 'P2A')) {
                                    $CreditHistroyIdCashback = ch_txnid();
                                    $comm_openingbal = $closing_balance;
                                    $basecomm_amount = $base_applicable_commisison;
                                    $comm_closingbal = $comm_openingbal + $basecomm_amount;


                                    $CashbackCrdtEntry_reatiler = array(
                                        'credit_txnid' => $CreditHistroyIdCashback,
                                        'user_id' => $findbalance['user_id'],
                                        'bank_name' => 'N/A',
                                        'txn_type' => 'CASHBACK',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $basecomm_amount,
                                        'opening_balance' => $comm_openingbal,
                                        'closing_balance' => $comm_closingbal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'remarks' => "Cashback of Rs. " . $basecomm_amount . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'txn_code' => $transid,
                                        'status' => 'CREDIT',
                                        'updated_by' => $findbalance['user_id'],
                                    );


                                    $CreditHistroyTDSId = ch_txnid();
                                    $Tds_opng_bal = $comm_closingbal;
                                    $RetTds = $tdsamount;
                                    $Tds_clsng_bal = $Tds_opng_bal - $RetTds;

                                    $TDSCreditHistoryEntryRetailer = array(
                                        'credit_txnid' => $CreditHistroyTDSId,
                                        'user_id' => $findbalance['user_id'],
                                        'bank_name' => 'N/A',
                                        'txn_type' => 'TDS',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $RetTds,
                                        'opening_balance' => $Tds_opng_bal,
                                        'closing_balance' => $Tds_clsng_bal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'remarks' => "TDS Of Rs. " . $RetTds . " Deducted On Cashback Amount Of Rs. " . $base_applicable_commisison . " Received For " . $transid . ',Transaction Amount : ' . $amount,
                                        'txn_code' => $transid,
                                        'status' => 'DEBIT',
                                        'updated_by' => $findbalance['user_id'],
                                    );



                                    $TaxRecordRet = array(
                                        'user_id' => $findbalance['user_id'],
                                        'cbrt_id' => $transid,
                                        'billing_model' => $chkservicestat['billing_model'],
                                        'trans_amt' => $amount,
                                        'charged_amt' => $charged_amount,
                                        'comm_amnt' => $base_applicable_commisison,
                                        'tds_amnt' => $tdsamount,
                                        'gst_amnt' => $gstamount_tobecredited,
                                        'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
                                        'tds_status' => 'PENDING',
                                        'tax_type' => 'CREDIT',
                                        'created_dt' => date('Y-m-d H:i:s'),
                                        'updated_by' => $findbalance['user_id'],
                                        'updated_dt' => date('Y-m-d H:i:')
                                    );

                                } else {

                                    $TaxRecordRet = array(
                                        'user_id' => $findbalance['user_id'],
                                        'cbrt_id' => $transid,
                                        'billing_model' => $chkservicestat['billing_model'],
                                        'trans_amt' => $amount,
                                        'charged_amt' => $charged_amount,
                                        'comm_amnt' => $base_applicable_commisison,
                                        'tds_amnt' => $tdsamount,
                                        'gst_amnt' => $gstamount_tobecredited,
                                        'gst_status' => 'PENDING',
                                        'tds_status' => 'PENDING',
                                        'tax_type' => 'CREDIT',
                                        'created_dt' => date('Y-m-d H:i:s'),
                                        'updated_by' => $findbalance['user_id'],
                                        'updated_dt' => date('Y-m-d H:i:')
                                    );

                                    $CashbackCrdtEntry_reatiler = array();
                                    $TDSCreditHistoryEntryRetailer = array();   
                                }

                                $RetailerInsertEntry = $this->_ci->Inst_model->RetailerAllEntryWithoutAdmin($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet);

                                if ($RetailerInsertEntry) {
                                    $inserted_id = $RetailerInsertEntry;
                                    
                                    $ParentArray = array();
                                   
                                    if ($findbalance['parent_id'] != 0) {
                                       

                                        if (count($common_array) > 0) {////sup dist/ dist of retailer
                                            foreach ($common_array as $k => $v) {

                                                $ParentInfo = $this->_ci->Inst_model->user_info($v['USERID']);


                                                if ($ParentInfo) {


                                                    $parent_opening = $ParentInfo['rupee_balance'];
                                                    $parent_closeing = $parent_opening + $v['BASCOMM'];
                                                    $ParentArray[$k]['COM'] = array(
                                                        'credit_txnid' => ch_txnid(),
                                                        'user_id' => $ParentInfo['user_id'],
                                                        'bank_name' => 'N/A',
                                                        'txn_type' => 'COMMISSION',
                                                        'payment_mode' => 'WALLET',
                                                        'amount' => $v['BASCOMM'],
                                                        'opening_balance' => $parent_opening,
                                                        'closing_balance' => $parent_closeing,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => 'Commission From ' . $identify_commision_from . ' For ' . $transid,
                                                        'remarks' => 'Commission From ' . $identify_commision_from . ' For ' . $transid . ',Transaction Amount : Rs. ' . $amount,
                                                        'txn_code' => $transid,
                                                        'status' => 'CREDIT',
                                                        'updated_by' => $ParentInfo['user_id']
                                                    );


                                                    $parent_opening_tds = $parent_closeing;
                                                    $parent_closing_tds = $parent_opening_tds - $v['TDS'];

                                                    $ParentArray[$k]['TDS'] = array(
                                                        'credit_txnid' => ch_txnid(),
                                                        'user_id' => $ParentInfo['user_id'],
                                                        'bank_name' => 'N/A',
                                                        'txn_type' => 'TDS',
                                                        'payment_mode' => 'WALLET',
                                                        'amount' => $v['TDS'],
                                                        'opening_balance' => $parent_opening_tds,
                                                        'closing_balance' => $parent_closing_tds,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                        'remarks' => 'TDS Deducted On, Commission of Rs. ' . $v['BASCOMM'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount,
                                                        'txn_code' => $transid,
                                                        'status' => 'DEBIT',
                                                        'updated_by' => $ParentInfo['user_id']
                                                    );

                                                    $ParentArray[$k]['TAX'] = array(
                                                        'user_id' => $v['USERID'],
                                                        'cbrt_id' => $transid,
                                                        'billing_model' => 'P2A',
                                                        'trans_amt' => $amount,
                                                        'charged_amt' => $charged_amount,
                                                        'comm_amnt' => $v['BASCOMM'],
                                                        'tds_amnt' => $v['TDS'],
                                                        'gst_amnt' => $v['GST'],
                                                        'gst_status' => 'PENDING',
                                                        'tds_status' => 'PENDING',
                                                        'tax_type' => 'CREDIT',
                                                        'created_dt' => date('Y-m-d H:i:s'),
                                                        'updated_by' => $ParentInfo['user_id'],
                                                        'updated_dt' => date('Y-m-s H:i:s')
                                                    );
                                                }
                                            }
                                        }/** *count($common_array) end** */


                                      
                                        $parent_comission = $this->_ci->Inst_model->parent_commission_without_Admin($ParentArray);

                                        
                                    }///$findbalance['parent_id'] != 0
                                   
                             
									
										$request['token'] = 'e0c9e38d9219020ecc261d23701748b8';
										$request['request']['remittermobile']=$bfnum; 
										$request['request']['beneficiaryid']=$bnef_id; 
										$request['request']['agentid']=$transid; 
										$request['request']['amount']=$amount; 
										$request['request']['mode']=$mode;
										
										$curl = curl_init();
										$url="https://www.instantpay.in/ws/dmi/transfer";
										curl_setopt_array($curl, array(
										
										  CURLOPT_URL => $url,
										  CURLOPT_RETURNTRANSFER => true,
										  CURLOPT_ENCODING => "",
										  CURLOPT_MAXREDIRS => 10,
										  CURLOPT_TIMEOUT => 0,
										  CURLOPT_FOLLOWLOCATION => true,
										  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
										  CURLOPT_CUSTOMREQUEST => "POST",
										   CURLOPT_POSTFIELDS => json_encode($request), 
										  CURLOPT_HTTPHEADER => array(
											"Content-Type: application/json",
										   
											"Accept: application/json"
										  ),
										));

										$response = curl_exec($curl);  

										curl_close($curl);
										
                                    if ($response) {///response true 
                                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Money Transfer","response"=>($response),"useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                               
                                  $response = json_decode($response, true);
                                    if(is_array($response)){
									/* {
									  "statuscode": "TXN",
									  "status": "SUCCESS",
									  "data": {
										"ipay_id": "1171227201033YKERU",
										"ref_no": 1514385633,
										"opr_id": 1514385633,
										"name": "A",
										"opening_bal": "771.91",
										"amount": "11.00",
										"locked_amt": 0
									  }
									} */   
									$response=array(
									 "statuscode"=> "TXN",
									  "status"=> "SUCCESS",
									  "data"=> array(
										"ipay_id"=> "1171227201033YKERU",
										"ref_no"=> 1514385633,
										"opr_id"=> 1514385633,
										"name"=> "A",
										"opening_bal"=> "771.91",
										"amount"=> "11.00",
										"locked_amt"=> 0  
									  )
									);
									if(isset($response['statuscode']))
                                    {
                     
                                            $mapped_error=$response['statuscode'];
                                            $mapped_error_desc=$response['status'];

                                            
                                    }else{

                                        $mapped_error='';
                                        $mapped_error_desc='Unknown Error'; 

                                    }

                            $error_mapping=$this->_ci->Inst_model->fetch_error_code($mapped_error,$chkservicestat['vendor_id']); 
            
                            if($error_mapping){


                            $error_mapping['error_code_desc'] = $error_mapping['errorcode_id'] == 2 ? $mapped_error_desc : $error_mapping['error_code_desc'];

                           
///check status REFUND ONLY WHEN STAUS IS NOT COMING//

                     if(isset($response['status'])){
                            
                                if($response['status']=='SUCCESS')
                                {
                                    $txnstatus=$response['status'];
								  /*  $txnstatus='FAILED';
                                    $data['error'] = 1; */
									  $data['error'] = 0; 
                                    $data['error_desc'] = null;
                                    $data['msg'] = $error_mapping['error_code_desc'];
                                    $data['TxnId'] = isset($response['data']['ref_no']) ? $response['data']['ref_no'] : '00'; 
                                    $data['OPTId'] = isset($response['data']['opr_id']) ? $response['data']['opr_id'] : '00';
                                    $data['TranId'] = isset($response['data']['ipay_id']) ? $response['data']['ipay_id'] : '00';
                                    $fstpyresponsecode =$error_mapping['error_code'];
                                    $fstpayresponse =$error_mapping['error_code_desc'];
                                    $data['status'] = $txnstatus;         
                                  
                                }

                                else if($response['status']=='PENDING')///Timeout
                                {
                                                        $txnstatus=$response['status'];
                                                        $data['error'] = 3;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = $error_mapping['error_code_desc'];
                                                        //$data['TxnId'] = $transid;
														 $data['TxnId'] = isset($response['ref_no']) ? $response['ref_no'] : '00';
                                                        $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                        $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';

                                                        $data['status'] =$txnstatus;


                                                        $fstpyresponsecode = $error_mapping['error_code'];
                                                        $fstpayresponse = $error_mapping['error_code_desc'];
                                                        
                                    
                                }

                                else{

                                                    $txnstatus='PENDING';
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $fstpyresponsecode = $error_mapping['error_code'];
                                                    $fstpayresponse = $error_mapping['error_code_desc'];
                                                     




                                }

                                
                            
                            }else{/// if status is not coming transaction falied
                              

                                                 $txnstatus='FAILED';


                                                    $data['error'] = 1;
                                                    $data['error_desc'] = $error_mapping['error_code_desc'];
                                                    $data['msg'] = null;
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $fstpyresponsecode = $error_mapping['error_code'];
                                                    $fstpayresponse = $error_mapping['error_code_desc'];

                                                    

                            }

                            }else{


                                                    $data['error']=3;
                                                    $data['msg']=$mapped_error_desc; 
                                                    $data['error_desc']=null;
                                                    $txnstatus='PENDING';
                                                    $error_mapping['error_code']='OUE';
                                                    $error_mapping['error_code_desc']=$data['msg'];
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = isset($response['opr_id']) ? $response['opr_id'] : '00';
                                                    $data['TranId'] = isset($response['ipay_id']) ? $response['ipay_id'] : '00';
                                                    $data['status'] = $txnstatus;
                                                    $fstpyresponsecode = $error_mapping['error_code'];
                                                    $fstpayresponse = $error_mapping['error_code_desc'];

                                                    

                            }



                                 $update_dt_array = array(
                                                        "sp_id" => isset($response['ipay_id'])?$response['ipay_id']:'00',
                                                        "opr_ref_no" => isset($response['opr_id']) ? $response['opr_id'] : '00',
                                                        "sp_respcode" => $error_mapping['error_code'],
                                                        "sp_respdesc" => $error_mapping['error_code_desc'],
                                                        "sp_response" => json_encode($response),
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                                    );


                        $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array); 
                                    
                    }else{
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process';
                                                    $data['page'] = 'hgjgfhfhgf';
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
                                                    $fstpayresponse = 'Transaction Under Process';
                                                    $status = $data['status'];


    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Money Transfer","response"=>"Invalid format","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

                                                    $update_dt_array = array(
                                                        "sp_id" => "00",
                                                        "opr_ref_no" => "00",
                                                        "sp_respcode" => "TMDOUT",
                                                        "sp_respdesc" => 'Timed Out',
                                                        "sp_response" => "Request Timedout from Vendor",
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => 'TUP',
                                                        "response" => "Transaction Under Process",
                                                        "status" => "PENDING",
                                                        "upd_id" => $inserted_id
                                                    );



    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                        }
                            } else {///response false 
                                                
                                                    $data['error'] = 3;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Transaction Under Process';
                                                    $data['TxnId'] = $transid;
                                                    $data['OPTId'] = '00';
                                                    $data['TranId'] = '00';
                                                    $data['status'] = 'PENDING';
												
                                                    $fstpayresponse = 'Transaction Under Process';
                                                    $status = $data['status'];




 $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"GET","ip"=>ip_address(),"req_params"=>'No Params',"req_for"=>"Init Money Transfer","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

    $log = $this->_ci->Inst_model->Instantlogs($insert_array);

            $update_dt_array = array(
                "sp_id" => "00",
                "opr_ref_no" => "00",
                "sp_respcode" => "TMDOUT",
                "sp_respdesc" => 'Timed Out',
                "sp_response" => "Request Timedout from Vendor",
                "res_dt" => date('Y-m-d H:i:s'),
                "ind_rcode" => 'TUP',
                "response" => "Transaction Under Process",
                "status" => "PENDING",
                "upd_id" => $inserted_id
            );



    $this->_ci->Inst_model->update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);



                                    }

                                                             

                                    ///response///    
                                   

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = "Internal Processing Error, Try Again Later 9";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = "Insufficient Balance";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }

                        }else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Unable to find user details1';
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }

                     /****No parent *****/
				    ///***********Calculation of parents**********************///////

            } else {

                        $data['error'] = 1;
                        $data['error_desc'] = 'Rate configuration issue, contact admin. 1112';
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                        return $data;
            }
                
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Service not allowed, contact admin";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                }
        } else {

                    $data['error'] = 1;
                    $data['error_desc'] = "Internal Processing Error, Try Again Later 90";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';

        }	   
			
		} else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Beneficiary Not exsist";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                    
        }			
		} else {
			
                    $data['error'] = 1;
                    $data['error_desc'] = "Please transfer the amount between ? " . $chkservicestat['min_amt'] . " and ? " . $chkservicestat['max_amt'] . " w/o decimals";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                  
        }
        } else {
			
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Mobile Number';
                $data['msg'] = null;
                $data['status'] = 'FAILED';
		}  
        } else {
			
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Transaction Mode';
            $data['msg'] = null;
        }
        return $data;
	}

}

?>
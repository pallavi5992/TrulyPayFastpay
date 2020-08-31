<?php

defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');

class New_inst_pay 
{
    
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
        
        
    }
    
    public function start_aeps_action($user_info,$params,$chkservicestat)
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
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['COMM']=array(
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
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['TDS']=array(
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
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['TAX']=array(
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
    
    public function InstantCustomerLogin ($user_info,$param,$outletid)
    {
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

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Check Login","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                $log = $this->_ci->Inst_model->Instantlogs($insert_array);
            }   
      
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Mobile Number';
            $data['msg'] = null;
        }
        return $data;   
    }
    
    public function InstantRemiiterRegstrn($user_info, $param, $outletid){
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

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Register Customer Request","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

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
    
    public function InstantOtpValidation($user_info, $param, $outletid)
    {
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

                        $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

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
    
    public function InstantCustomerDetailsFetch($user_info, $param, $outletid)
    {
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

                    $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Customer Details","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

                    $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                }   
      
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Mobile Number 1111';
                $data['msg'] = null;
            }
        return $data;   
        
    }

    public function AddBeneficiary ($user_info, $param, $outletid)
    {
        $mobile = isset($param['mobile']) ? $param['mobile'] : '';
        $name = isset($param['name']) ? $param['name'] : '';
        $bank = isset($param['bank']) ? $param['bank'] : '';
        $ifsccode = isset($param['ifsccode']) ? $param['ifsccode'] : '';
        $accountno=isset($param['accountno']) ? $param['accountno'] : '';
        $remitterid = isset($param['remitid']) ? $param['remitid'] : '';
        $data = array();
        if (preg_match('/^[6789][0-9]{9}$/', $mobile)) {
            if ($name!="") {         
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

                                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Add Benef","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

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
	
    public function InstantBenefOtpValidation($user_info, $param,$outletid)
    {
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

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Benef Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

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
	
    public function InstantBenefRESENDOtpValidation($user_info, $param,$outletid)
    {
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

                                                
                                                    

                 


                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Benef Rsend Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

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
	
    public function DeleteBeneficiary ($user_info, $param, $outletid)
    {

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

                $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Delete Benef","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

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
    
    public function InstantOtpDelbenfValidation($user_info, $param,$outletid)
    {
		
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

                     $insert_array=array("user_id"=>$user_info['user_id'],"url"=>$url,"method"=>"POST","ip"=>ip_address(),"req_params"=>json_encode($request),"req_for"=>"INSTANTPAY Delte Benf Otp Validation","response"=>"Request Timed Out","useragent"=>$_SERVER['HTTP_USER_AGENT'],"datetime"=>date('Y-m-d H:i:s'));

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
    
    public function ApiBalanceCheck($uid) 
    {
       
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
	
    public function InstantpyCCFCommsionCal($user_info,$chkusr_instnpaysession,$param, $amount, $chkservicestat, $planid)
    {
            
        $data = array();
        $amount = strip_tags((int) $amount);
        
        $planid=$user_info['plan_id'];
        
         if (($amount) && ($amount) >= $chkservicestat['min_amt'] && ($amount) <= $chkservicestat['max_amt']) {
            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) 
            {
                 $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                if ($chck_user_pln_dtl) 
                {
                 
                    
                        $retailer_capping_amount=$chck_user_pln_dtl['capping_amount'];
                        $retailer_charge_type=$chck_user_pln_dtl['charge_type'];
                        $reatiler_charge_method=$chck_user_pln_dtl['charge_method'];
                        $retailer_service_rate=$chck_user_pln_dtl['rate'];
                    
                        if($chck_user_pln_dtl['slab_applicable']==1)
                        {
                            $chck_agrd_fee_rng = $this->_ci->Inst_model->check__new_pln_amnt_rng($chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amount);
                            
                            if($chck_agrd_fee_rng)
                            {
                             
                                $retailer_charge_type=$chck_agrd_fee_rng['charge_type'];
                                $reatiler_charge_method=$chck_agrd_fee_rng['charge_method'];
                                $retailer_service_rate=$chck_agrd_fee_rng['rate'];
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Slab rate not configured for your account";
                                $data['msg'] = null;
                                return $data;
                            }
                            
                        }    
                        
                        $ccf_value = round(($amount * 1) / 100,2);
                        $ccf_value = $ccf_value > 10 ? $ccf_value : 10;
                    
                        $retailer_trans_array=array(); 
                    
                         if ($chkservicestat['billing_model'] == 'P2A') 
                            {
                                if ($reatiler_charge_method == 'CREDIT') 
                                {

                                        if ($retailer_charge_type == 'FIXED') 
                                        {
                                            $retailer_trans_array['rate_value']=$retailer_service_rate;
                                            $retailer_trans_array['base_comm']=$retailer_service_rate;
                                            $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                            $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                            $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                            $retailer_trans_array['is_comm']=true;
                                            $retailer_trans_array['ccfval']=$ccf_value;
                                            $retailer_trans_array['charged_amt']=$amount+$ccf_value;

                                        } elseif ($retailer_charge_type == 'PERCENTAGE') 
                                        {


                                            $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amount)/100),2);
                            if(is_numeric($retailer_capping_amount))
                            {

    $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_capping_amount:$retailer_trans_array['rate_value'];

                            }

                            $retailer_trans_array['base_comm']=$retailer_trans_array['rate_value'];
                            $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                            $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                            $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                            $retailer_trans_array['is_comm']=true;
                            $retailer_trans_array['ccfval']=$ccf_value;
                            $retailer_trans_array['charged_amt']=$amount+$ccf_value;


                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                            $data['msg'] = null;
                                            return $data;
                                        }

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                    $data['msg'] = null;

                                    return $data;
                                }


                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Commission or Surcharge type is set.';
                                $data['msg'] = null;

                                return $data;
                            }

                  
                      
                     if(count($retailer_trans_array)>0)
                     {
                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['trnsferamount'] = $amount;
                                $data['base_applicable_commisison'] = $retailer_trans_array['base_comm'];
                                $data['extra_chrg'] = $retailer_trans_array['ccfval'];
                                $data['charged_amount'] = $retailer_trans_array['charged_amt'];
                         
                     }else{
                        $data['error'] = 1;
                        $data['error_desc'] = "Margin Configuration Issue";
                        $data['msg'] = null;
                     }

                
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Service not allowed, contact admin";
                    $data['msg'] = null;
                    
                }
            } else {

                $data['error'] = 1;
                $data['error_desc'] = "Unable to get service config details, try again later";
                $data['msg'] = null;
         
            }
        } else {
             
            $data['error'] = 1;
            $data['error_desc'] = "Invalid Transaction amount";
            $data['msg'] = null;
            
        }
        return $data;
    }
    
    public function InstantpyMoneyTransfer($user_info, $mobile, $param, $amount, $chkservicestat, $planid,$get_agentcode)
    {
        
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
        $remitter_name=isset($param['TXN_REQUEST']['reminame']) ? $param['TXN_REQUEST']['reminame'] : '';
        $planid=$user_info['plan_id'];
        
        if (in_array($mode, ($trnsfermode))) 
        {
            
            if (ctype_digit($mobile) && strlen($mobile) == 10) 
            {  
                
                if (ctype_digit($amount) && $amount >= $chkservicestat['min_amt'] && $amount <= $chkservicestat['max_amt']) 
                {     
                   
                    $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
                       
                    if($operator_dtl) 
                    {
                        $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                            
                        if ($chck_user_pln_dtl) {
                            
                            
                        $retailer_capping_amount=$chck_user_pln_dtl['capping_amount'];
                        $retailer_charge_type=$chck_user_pln_dtl['charge_type'];
                        $reatiler_charge_method=$chck_user_pln_dtl['charge_method'];
                        $retailer_service_rate=$chck_user_pln_dtl['rate'];
                            
                        if($chck_user_pln_dtl['slab_applicable']==1)
                        {
                            $chck_agrd_fee_rng = $this->_ci->Inst_model->check__new_pln_amnt_rng($chck_user_pln_dtl['plan_id'], $chck_user_pln_dtl['service_id'], $amount);
                            
                            if($chck_agrd_fee_rng)
                            {
                             
                                $retailer_charge_type=$chck_agrd_fee_rng['charge_type'];
                                $reatiler_charge_method=$chck_agrd_fee_rng['charge_method'];
                                $retailer_service_rate=$chck_agrd_fee_rng['rate'];
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Slab rate not configured for your account";
                                $data['msg'] = null;
                                return $data;
                            }
                            
                        }    
                          
                        
                        $ccf_value = round(($amount * 1) / 100,2);
                        $ccf_value = $ccf_value > 10 ? $ccf_value : 10;
                            
                        $retailer_trans_array=array(); 
                            
                            
                                    if ($chkservicestat['billing_model'] == 'P2A') 
                                    {
                                        if ($reatiler_charge_method == 'CREDIT') 
                                        {
                                            
                                                if ($retailer_charge_type == 'FIXED') 
                                                {
                                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                                    $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                                    $retailer_trans_array['is_comm']=true;
                                                    $retailer_trans_array['ccfval']=$ccf_value;
                                                    $retailer_trans_array['charged_amt']=$amount+$ccf_value;
                                                    
                                                } elseif ($retailer_charge_type == 'PERCENTAGE') 
                                                {

                                                    
                                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amount)/100),2);
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
         $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_capping_amount:$retailer_trans_array['rate_value'];
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['ccfval']=$ccf_value;
                                    $retailer_trans_array['charged_amt']=$amount+$ccf_value;
                                                    
                                                    
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                                    $data['msg'] = null;
                                                    return $data;
                                                }
                                            
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                            $data['msg'] = null;
                                            
                                            return $data;
                                        }

                                  
                                    }else{
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Invalid Commission or Surcharge type is set.';
                                        $data['msg'] = null;
                                        
                                        return $data;
                                    }

                                    if(count($retailer_trans_array)>0)
                                    {
                 
                                        $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                   
                                    if ($findbalance) 
                                    {

                                            $openingbal = $findbalance['rupee_balance'];
                                            $closingbal = $openingbal-$retailer_trans_array['charged_amt'];
                                        
                                            $transid=generate_txnid();
                                        
                                
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
                                            "servedby"=>$chkservicestat['vendor_id'],
                                            "transamt"=>$amount,
                                            "chargeamt"=>$retailer_trans_array['charged_amt'],
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$closingbal,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Transaction Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$remitter_name,
                                            "op2"=>$benef_name,
                                            "op3"=>$accountno,
                                            "op4"=>$ifsccode,
                                            "op5"=>$retailer_trans_array['ccfval'],
                                            "op6"=>$mode,
                                            "op7"=>$retailer_trans_array['base_comm'],
                                            "op10"=>$bank
                                        );
                                        
                                    $retailer_comm_tds_array=array();
                                        
                            
                                    $retailer_CreditHistroyIdCashback = ch_txnid();
                                    $retailer_comm_openingbal = $closingbal;
                                    $retailer_comm_closingbal = $retailer_comm_openingbal + $retailer_trans_array['base_comm'];
                                    
                                    $retailer_cashback_desc="Cashback of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amount;
                                    
                                    $retailer_comm_tds_array['CASHBACK'] = array(
                                                'credit_txnid' => $retailer_CreditHistroyIdCashback,
                                                'user_id' => $findbalance['user_id'],
                                                'bank_name' => 'N/A',
                                                'txn_type' => 'CASHBACK',
                                                'payment_mode' => 'WALLET',
                                                'amount' => $retailer_trans_array['base_comm'],
                                                'opening_balance' => $retailer_comm_openingbal,
                                                'closing_balance' => $retailer_comm_closingbal,
                                                'updated_on' => date('Y-m-d H:i:s'),
                                                'reference_number' => $retailer_cashback_desc,
                                                'remarks' => $retailer_cashback_desc,
                                                'txn_code' => $transid,
                                                'status' => 'CREDIT',
                                                'created_on'=>date('Y-m-d H:i:s'),
                                                'created_by'=>$findbalance['user_id'],
                                                'updated_by' => $findbalance['user_id'],
                                            );
                                    
                                    $retailer_CreditHistroyTDSId = ch_txnid();
                                    $retailer_Tds_opng_bal = $retailer_comm_closingbal;
                                    $retailer_Tds_clsng_bal = $retailer_Tds_opng_bal - $retailer_trans_array['tds'];
                                    
                                    $retailer_tds_description="TDS Of Rs. " . $retailer_trans_array['tds'] . " Deducted On Cashback Amount Of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amount;
                                    
                                    $retailer_comm_tds_array['TDS']=array(
                                                'credit_txnid' => $retailer_CreditHistroyTDSId,
                                                'user_id' => $findbalance['user_id'],
                                                'bank_name' => 'N/A',
                                                'txn_type' => 'TDS',
                                                'payment_mode' => 'WALLET',
                                                'amount' => $retailer_trans_array['tds'],
                                                'opening_balance' => $retailer_Tds_opng_bal,
                                                'closing_balance' => $retailer_Tds_clsng_bal,
                                                'updated_on' => date('Y-m-d H:i:s'),
                                                'reference_number' => $retailer_tds_description,
                                                'remarks' => $retailer_tds_description,
                                                'txn_code' => $transid,
                                                'status' => 'DEBIT',
                                                'created_on'=>date('Y-m-d H:i:s'),
                                                'created_by'=>$findbalance['user_id'],
                                                'updated_by' => $findbalance['user_id'],
                                            );
                                    
                                    $retailer_comm_tds_array['TAX']=array(
                                                'user_id' => $findbalance['user_id'],
                                                'cbrt_id' => $transid,
                                                'billing_model' => $chkservicestat['billing_model'],
                                                'trans_amt' => $amount,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => 'PENDING',
                                                'tds_status' => 'PENDING',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                                    
                                 $parent_comm_array=array();
                                 $parent_comm_tds_tax_array=array();
                           
                                   if($user_info['parent_id']!=0)
                                    {
                                
                                $get_parent_info=$this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                                
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
                                                    $chck_parent_agrd_fees_range = $this->_ci->Inst_model->check__new_pln_amnt_rng($checkparentsplan['plan_id'], $checkparentsplan['service_id'], $amount);
                                                    
                                                    if($chck_parent_agrd_fees_range)
                                                    {
                                                        $parent_charge_type=$chck_parent_agrd_fees_range['charge_type'];
                                                        $parent_charge_method=$chck_parent_agrd_fees_range['charge_method'];
                                                        $parent_service_rate=$chck_parent_agrd_fees_range['rate'];
                                                        
                                                    }else{
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Slab rate not configured for your parent.";
                                                        $data['msg'] = null;
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
                                                        $parent_comm_array[$pv['user_id']]['rate_value']=round((($parent_service_rate*$amount)/100),2);
                                                        
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
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Invalid charge type is configured for your parent.";
                                                        $data['msg'] = null;
                                                        return $data;
                                                    }
                                                    
                                                    if(isset($parent_comm_array[$pv['user_id']]))
                                                    {
                                                        $parent_comm_opening=$pv['rupee_balance'];
                                                        $parent_comm_closing=$parent_comm_opening+$parent_comm_array[$pv['user_id']]['base_comm'];
                                                        
                                                        $parent_comm_description='Commission From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount;
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['COMM']=array(
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
                                                        
                                                        $prent_tds_description='TDS Deducted On, Commission of Rs. ' . $parent_comm_array[$pv['user_id']]['base_comm'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount;
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['TDS']=array(
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
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['TAX']=array(
                                                                'user_id' => $pv['user_id'],
                                                                'cbrt_id' => $transid,
                                                                'billing_model' => 'P2A',
                                                                'trans_amt' => $amount,
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
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = "Invalid charge method is configured for your parent.";
                                                    $data['msg'] = null;
                                                    return $data;
                                                }
                                                
                                                

                                            }else{
                                                $data['error'] = 1;
                                                $data['error_desc'] = "Plan not configured for your parent.";
                                                $data['msg'] = null;
                                                return $data;
                                            }
                                            
                                        }
                                    }
                                    
                                }
                                
                            }
                          
                                      
                                if ($openingbal >= $retailer_trans_array['charged_amt'] && $closingbal >= 0 && is_numeric($closingbal)) 
                                {

                 $RetailerInsertEntry=$this->_ci->Inst_model->initiate_service_transaction($RetailerTxnEntry,$retailer_comm_tds_array,$parent_comm_tds_tax_array);   
                              
                                            if ($RetailerInsertEntry) 
                                            {
                                                $inserted_id = $RetailerInsertEntry;
                                                
                                      
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

                                                $api_response = curl_exec($curl);  

                                                curl_close($curl);
                                        
                                            $insert_array=array(
                                                "user_id"=>$user_info['user_id'],
                                                "url"=>$url,
                                                "method"=>"POST",
                                                "ip"=>ip_address(),
                                                "req_params"=>json_encode($request),
                                                "req_for"=>"Init Money Transfer",
                                                "response"=>($api_response)?$api_response:"No Response, Curl Timeout",
                                                "useragent"=>$this->_ci->agent->agent_string(),
                                                "datetime"=>date('Y-m-d H:i:s')
                                            );

                                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                                  
                                                if ($api_response) 
                                                {///response true 
                                                 
                                                    try {
                                                        
                                                    $response = json_decode($api_response, true);
                                                    $opr_id='00';
                                                    $sp_id='00'; 
                                                        
                                                    $sp_id=isset($response['data']['ipay_id'])?$response['data']['ipay_id']:"00";
                                                    $opr_id=isset($response['data']['opr_id'])?$response['data']['opr_id']:"00";
                                                        
                                                    if(isset($response['statuscode']))
                                                        {
                     
                                                         
                                                        $error_mapping=$this->_ci->Inst_model->fetch_error_code($response['statuscode'],$chkservicestat['vendor_id']); 
            
                                                        if($error_mapping)
                                                        {

                            $response['status']=@$response['status'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$response['status']:$error_mapping['error_code_desc'];
                            
                                                                if($response['statuscode']=='TXN')
                                                                {
                                                                        $txnstatus='SUCCESS';
                                            
                                                                        $data['error'] = 0;
                                                                        $data['error_desc'] = null;
                                                                        $data['msg']=$error_mapping['error_code_desc'];
                                                                        $data['txndata']=array(
                                                                                    "txnid"=>$transid,
                                                                                    "opid"=>$opr_id,
                                                                                    "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                                    "status"=>$txnstatus,
                                                                                    "ccfval"=>$retailer_trans_array['ccfval']
                                                                            );     
                                  
                                                                }
                                                                else if($response['statuscode']=='TUP') 
                                                                { ///Timeout
                                                                                                    
                                                                    $txnstatus='PENDING';
                                            
                                                                    $data['error'] = 0;
                                                                    $data['error_desc'] = null;
                                                                    $data['msg']=$error_mapping['error_code_desc'];
                                                                    $data['txndata']=array(
                                                                                "txnid"=>$transid,
                                                                                "opid"=>$opr_id,
                                                                                "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                                "status"=>$txnstatus,
                                                                                "ccfval"=>$retailer_trans_array['ccfval']
                                                                        );
                                                        
                                     
                                                                } else{

                                                                    $txnstatus='FAILED';
                                            
                                                                    $data['error'] = 0;
                                                                    $data['error_desc'] = null;
                                                                    $data['msg']=$error_mapping['error_code_desc'];
                                                                    $data['txndata']=array(
                                                                                "txnid"=>$transid,
                                                                                "opid"=>$opr_id,
                                                                                "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                                "status"=>$txnstatus,
                                                                                "ccfval"=>$retailer_trans_array['ccfval']
                                                                        );
                                                     
                                                                }

                                                                

                                                        }else{

                                                        $txnstatus='PENDING';
                                            
                                                        $data['error'] = 0;
                                                        $data['error_desc'] = null;
                                                        $data['msg']='Other Unknown Error';
                                                        $data['txndata']=array(
                                                                    "txnid"=>$transid,
                                                                    "opid"=>$opr_id,
                                                                    "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                    "status"=>$txnstatus,
                                                                    "ccfval"=>$retailer_trans_array['ccfval']
                                                            ); 

                                                        $error_mapping['error_code']='OUE';
                                                        $error_mapping['error_code_desc']=$data['msg'];

                                                        }
                                                            
                                                         
                                                        }else{

                                                            $txnstatus='PENDING';
                                            
                                                            $data['error'] = 0;
                                                            $data['error_desc'] = null;
                                                            $data['msg']='Other Unknown Error';
                                                            $data['txndata']=array(
                                                                        "txnid"=>$transid,
                                                                        "opid"=>$opr_id,
                                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                        "status"=>$txnstatus,
                                                                        "ccfval"=>$retailer_trans_array['ccfval']
                                                                ); 

                                                            $error_mapping['error_code']='OUE';
                                                            $error_mapping['error_code_desc']=$data['msg'];

                                                        }


    
                                                    $update_txn_array=array(
                                                        "sp_id" => $sp_id,
                                                        "opr_ref_no" => $opr_id,
                                                        "sp_respcode" => isset($response['statuscode'])?$response['statuscode']:'00',
                                                        "sp_respdesc" => isset($response['status'])?$response['status']:'00',
                                                        "sp_response" => $api_response,
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['txndata']['status'],
                                                        "upd_id" => $inserted_id
                                                    );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                         
                                                        
                                                    
                                                    }
                                                    catch(Exception $e)
                                                    {
                                                            $txnstatus='PENDING';

                                                            $data['error'] = 0;
                                                            $data['error_desc'] = null;
                                                            $data['msg']='Other Unknown Error';
                                                            $data['txndata']=array(
                                                                        "txnid"=>$transid,
                                                                        "opid"=>'00',
                                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                        "status"=>$txnstatus,
                                                                        "ccfval"=>$retailer_trans_array['ccfval']
                                                                ); 

                                                            $error_mapping['error_code']='OUE';
                                                            $error_mapping['error_code_desc']=$data['msg'];

                                                        $update_txn_array=array(
                                                                        "sp_id" => '00',
                                                                        "opr_ref_no" => '00',
                                                                        "sp_respcode" => '',
                                                                        "sp_respdesc" => 'Internal error, entered catch block '.$e->getMessage(),
                                                                        "sp_response" => $api_response,
                                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                                        "ind_rcode" => $error_mapping['error_code'],
                                                                        "response" => $error_mapping['error_code_desc'],
                                                                        "status" => $data['txndata']['status'],
                                                                        "upd_id" => $inserted_id
                                                        );


                                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);


                                                    }
                                                    
                                                } 
                                                else {///response false 
                                                
                                                    $txnstatus='PENDING';
                                    
                                                    $data['error'] = 0;
                                                    $data['error_desc'] = null;
                                                    $data['msg']='Transaction Under Process';
                                                    $data['txndata']=array(
                                                                "txnid"=>$transid,
                                                                "opid"=>'00',
                                                                "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                "status"=>$txnstatus,
                                                                "ccfval"=>$retailer_trans_array['ccfval']
                                                        ); 

                                                    $error_mapping['error_code']='TUP';
                                                    $error_mapping['error_code_desc']=$data['msg'];

                                                    $update_txn_array=array(
                                                                        "sp_id" => '00',
                                                                        "opr_ref_no" => '00',
                                                                        "sp_respcode" => 'TMDOUT',
                                                                        "sp_respdesc" => 'Curl Timedout',
                                                                        "sp_response" => 'Curl Timedout',
                                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                                        "ind_rcode" => $error_mapping['error_code'],
                                                                        "response" => $error_mapping['error_code_desc'],
                                                                        "status" => $data['txndata']['status'],
                                                                        "upd_id" => $inserted_id
                                                        );


                                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);



                                                }

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Internal Processing Error, try again later";
                                                $data['msg'] = null;
                                               
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = "Insufficient Balance";
                                            $data['msg'] = null;
                                            
                                        }

                                    }else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Unable to find user details1';
                                        $data['msg'] = null;
                                        
                                    }

                                        
                                    }else{
                                        $data['error'] = 1;
                                        $data['error_desc'] = "Margin Configuration Issue";
                                        $data['msg'] = null;
                                    }
                               
                
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Plan not configured for your account";
                                $data['msg'] = null;
                                
                            }
                        } else {

                            $data['error'] = 1;
                            $data['error_desc'] = "Unable to get service config details, try again later";
                            $data['msg'] = null;

                        }      
            
                           
                } else {
                    
                    $data['error'] = 1;
                    $data['error_desc'] = "Please transfer the amount between Rs " . $chkservicestat['min_amt'] . " and Rs " . $chkservicestat['max_amt'];
                    $data['msg'] = null;
                    
                          
                }
                
            } else {
                
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Remitter Number';
                $data['msg'] = null;
                
            }  
        } else {
            
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Transaction Mode';
            $data['msg'] = null;
        }
        return $data;
    }

	public function fetch_bene_validationcommercials($user_info,$chkservicestat)
    {
        
         $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
            if ($operator_dtl) 
            {
                 $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$user_info['plan_id'],$chkservicestat['service_id']);
                if ($chck_user_pln_dtl) 
                {
                 
                        $amount=1;
                    
                        $retailer_capping_amount=$chck_user_pln_dtl['capping_amount'];
                        $retailer_charge_type=$chck_user_pln_dtl['charge_type'];
                        $reatiler_charge_method=$chck_user_pln_dtl['charge_method'];
                        $retailer_service_rate=$chck_user_pln_dtl['rate'];
                    
                        $retailer_trans_array=array(); 
                    
                         if ($chkservicestat['billing_model'] == 'CHARGE') 
                            {
                                if ($reatiler_charge_method == 'DEBIT') 
                                {

                                        if ($retailer_charge_type == 'FIXED') 
                                        {
                                            $retailer_trans_array['rate_value']=$retailer_service_rate;
                                            $retailer_trans_array['base_comm']=0;
                                            $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                            $retailer_trans_array['app_comm']=0;
                                            $retailer_trans_array['tds']=0;
                                            $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                            $retailer_trans_array['is_comm']=false;
                                            $retailer_trans_array['charged_amt']=$amount+$retailer_trans_array['rate_value'];

                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                            $data['msg'] = null;
                                            return $data;
                                        }

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                    $data['msg'] = null;

                                    return $data;
                                }


                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Commission or Surcharge type is set.';
                                $data['msg'] = null;

                                return $data;
                            }

                  
                      
                     if(count($retailer_trans_array)>0)
                     {
                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['trnsferamount'] = $amount;
                                $data['charged_amount'] = $retailer_trans_array['charged_amt'];
                         
                     }else{
                        $data['error'] = 1;
                        $data['error_desc'] = "Margin Configuration Issue";
                        $data['msg'] = null;
                     }

                
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Service not allowed, contact admin";
                    $data['msg'] = null;
                    
                }
            } else {

                $data['error'] = 1;
                $data['error_desc'] = "Unable to get service config details, try again later";
                $data['msg'] = null;
         
            }
        return $data;
    }
   
    public function VerifyBeneficiaryAccount($user_info, $param,$chkservicestat)
    {
        $mobile = isset($param['mobile']) ?(is_string($param['mobile'])?trim($param['mobile']):""):"";
        $name = isset($param['name']) ?(is_string($param['name'])?trim($param['name']):""):"";
        $bank = isset($param['bank']) ?(is_string($param['bank'])?trim($param['bank']):""):"";
        $ifsccode = isset($param['ifsccode']) ?(is_string($param['ifsccode'])?trim($param['ifsccode']):""):"";
        $accountno=isset($param['accountno']) ?(is_string($param['accountno'])?trim($param['accountno']):""):"";
        $remitterid = isset($param['remitid']) ?(is_string($param['remitid'])?trim($param['remitid']):""):"";
        $remitter_name = isset($param['reminame'])?(is_string($param['reminame'])?trim($param['reminame']):""):"";
        
        $data=array();
        
           if (preg_match('/^[6789][0-9]{9}$/', $mobile)) 
           {
               
//            if ($name!="") 
//            {         
                if (preg_match('/^[A-Za-z0-9]+$/', $ifsccode)) 
                {
                    if (preg_match('/^\d+$/', $accountno)) 
                    {
                        if($remitterid!='')
                        {
                           
                            $agentcode='30598';
                            
                            $planid=$user_info['plan_id'];
                            
                            $amount=1;
                            
                            $operator_dtl = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
                       
                            if($operator_dtl) 
                            {

                               $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                            
                                if ($chck_user_pln_dtl) 
                                {
                                    
                                    $retailer_capping_amount=$chck_user_pln_dtl['capping_amount'];
                                    $retailer_charge_type=$chck_user_pln_dtl['charge_type'];
                                    $reatiler_charge_method=$chck_user_pln_dtl['charge_method'];
                                    $retailer_service_rate=$chck_user_pln_dtl['rate'];
                                    
                                    
                                    
                        $retailer_trans_array=array(); 
                    
                         if ($chkservicestat['billing_model'] == 'CHARGE') 
                            {
                                if ($reatiler_charge_method == 'DEBIT') 
                                {

                                        if ($retailer_charge_type == 'FIXED') 
                                        {
                                            $retailer_trans_array['rate_value']=$retailer_service_rate;
                                            $retailer_trans_array['base_comm']=0;
                                            $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                            $retailer_trans_array['app_comm']=0;
                                            $retailer_trans_array['tds']=0;
                                            $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                            $retailer_trans_array['is_comm']=false;
                                            $retailer_trans_array['charged_amt']=$amount+$retailer_trans_array['rate_value'];

                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                            $data['msg'] = null;
                                            return $data;
                                        }

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Invalid charge method is configured for your account.';
                                    $data['msg'] = null;

                                    return $data;
                                }


                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Commission or Surcharge type is set.';
                                $data['msg'] = null;

                                return $data;
                            }
                                 
                                    
                                    
                                if(count($retailer_trans_array)>0)
                                    {
                 
                                        $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                   
                                    if ($findbalance) 
                                    {

                                            $openingbal = $findbalance['rupee_balance'];
                                            $closingbal = $openingbal-$retailer_trans_array['charged_amt'];
                                        
                                            $transid=generate_txnid();
                                            $mode='IMPS';
                                        
                                
                                $RetailerTxnEntry = array(
                                            "user_id"=>$findbalance['user_id'],
                                            "req_ip"=>ip_address(),
                                            "request_useragent "=>$this->_ci->agent->agent_string(),
                                            "fstpytxn_id"=>$transid,
                                            "sp_id"=>"00",
                                            "opr_ref_no"=>"00",
                                            'customer_no' =>$mobile,///dynamic//
                                            'scode' => $chkservicestat['code'],
                                            "servicename"=>$chkservicestat['service_name'],
                                            "servicetype"=>$chkservicestat['type'],
                                            "servedby"=>$chkservicestat['vendor_id'],
                                            "transamt"=>$amount,
                                            "chargeamt"=>$retailer_trans_array['charged_amt'],
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$closingbal,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Transaction Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$remitter_name,
                                            "op2"=>"",
                                            "op3"=>$accountno,
                                            "op4"=>$ifsccode,
                                            "op6"=>$mode,
                                            "op10"=>$bank
                                        );
                                        
                                    $retailer_comm_tds_array=array();
                                        
                            
                                    $retailer_comm_tds_array['TAX']=array(
                                                'user_id' => $findbalance['user_id'],
                                                'cbrt_id' => $transid,
                                                'billing_model' => $chkservicestat['billing_model'],
                                                'trans_amt' => $amount,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => 'PENDING',
                                                'tds_status' => 'PAID',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                                    
                                 $parent_comm_array=array();
                                 $parent_comm_tds_tax_array=array();
                           
                                   if($user_info['parent_id']!=0)
                                    {
                                
                                $get_parent_info=$this->_ci->Inst_model->UserTreeFetchForComm($user_info['parent_id']);
                                
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
                                                    $chck_parent_agrd_fees_range = $this->_ci->Inst_model->check__new_pln_amnt_rng($checkparentsplan['plan_id'], $checkparentsplan['service_id'], $amount);
                                                    
                                                    if($chck_parent_agrd_fees_range)
                                                    {
                                                        $parent_charge_type=$chck_parent_agrd_fees_range['charge_type'];
                                                        $parent_charge_method=$chck_parent_agrd_fees_range['charge_method'];
                                                        $parent_service_rate=$chck_parent_agrd_fees_range['rate'];
                                                        
                                                    }else{
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Slab rate not configured for your parent.";
                                                        $data['msg'] = null;
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
                                                        $parent_comm_array[$pv['user_id']]['rate_value']=round((($parent_service_rate*$amount)/100),2);
                                                        
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
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Invalid charge type is configured for your parent.";
                                                        $data['msg'] = null;
                                                        return $data;
                                                    }
                                                    
                                                    if(isset($parent_comm_array[$pv['user_id']]))
                                                    {
                                                        $parent_comm_opening=$pv['rupee_balance'];
                                                        $parent_comm_closing=$parent_comm_opening+$parent_comm_array[$pv['user_id']]['base_comm'];
                                                        
                                                        $parent_comm_description='Commission From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount;
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['COMM']=array(
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
                                                        
                                                        $prent_tds_description='TDS Deducted On, Commission of Rs. ' . $parent_comm_array[$pv['user_id']]['base_comm'] . ' From ' . $identify_commision_from . ' For ' . $transid . ', Transaction Amount : Rs. ' . $amount;
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['TDS']=array(
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
                                                        
                                                        $parent_comm_tds_tax_array[$pv['user_id']]['TAX']=array(
                                                                'user_id' => $pv['user_id'],
                                                                'cbrt_id' => $transid,
                                                                'billing_model' => 'P2A',
                                                                'trans_amt' => $amount,
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
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = "Invalid charge method is configured for your parent.";
                                                    $data['msg'] = null;
                                                    return $data;
                                                }
                                                
                                                

                                            }else{
                                                $data['error'] = 1;
                                                $data['error_desc'] = "Plan not configured for your parent.";
                                                $data['msg'] = null;
                                                return $data;
                                            }
                                            
                                        }
                                    }
                                    
                                }
                                
                            }
                          
                                      
            if ($openingbal >= $retailer_trans_array['charged_amt'] && $closingbal >= 0 && is_numeric($closingbal) && is_numeric($retailer_trans_array['charged_amt'])) 
                                {

                 $RetailerInsertEntry=$this->_ci->Inst_model->initiate_service_transaction($RetailerTxnEntry,$retailer_comm_tds_array,$parent_comm_tds_tax_array);   
                              
                                            if ($RetailerInsertEntry) 
                                            {
                                                $inserted_id = $RetailerInsertEntry;
                                                
                                                $find_lat_long_bypincode=$this->_ci->Main_model->get_distinct_pincodedt($user_info['business_pincode']);
                                      
                                                $request['token'] = $this->token; // 'e0c9e38d9219020ecc261d23701748b8';
                                                $request['request']['sp_key']=$operator_dtl['vendor_key']; 
                                                $request['request']['external_ref']=$transid; 
                                                $request['request']['credit_account']=$accountno; 
                                                $request['request']['ifs_code']=$ifsccode; 
                                                $request['request']['bene_name']='Validate Bene'; 
                                                $request['request']['credit_amount']=1; 
                                                $request['request']['latitude']=$find_lat_long_bypincode?$find_lat_long_bypincode['latitude']:"28.5621";
                                                $request['request']['longitude']=$find_lat_long_bypincode?$find_lat_long_bypincode['longitude']:"77.2857";
                                                $request['request']['endpoint_ip']=$RetailerTxnEntry['req_ip'];
                                                $request['request']['alert_mobile']=$mobile;
                                                $request['request']['alert_email']="";
                                                $request['request']['remarks']=$accountno;
                                        
                                                $curl = curl_init();
                                                $url="https://www.instantpay.in/ws/payouts/direct";
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

                                                $api_response = curl_exec($curl);  

                                                curl_close($curl);
                                        
                                            $insert_array=array(
                                                "user_id"=>$user_info['user_id'],
                                                "url"=>$url,
                                                "method"=>"POST",
                                                "ip"=>ip_address(),
                                                "req_params"=>json_encode($request),
                                                "req_for"=>"Beneficiary Verification",
                                                "response"=>($api_response)?$api_response:"No Response, Curl Timeout",
                                                "useragent"=>$this->_ci->agent->agent_string(),
                                                "datetime"=>date('Y-m-d H:i:s')
                                            );

                                            $log = $this->_ci->Inst_model->Instantlogs($insert_array);
                                  
                                                if ($api_response) 
                                                {///response true 
                                                 
                                                    try {
                                                        
                                                    $response = json_decode($api_response, true);
                                                    $opr_id='00';
                                                    $sp_id='00'; 
                                                        
                                                    $sp_id=isset($response['data']['ipay_id'])?$response['data']['ipay_id']:"00";
                                                    $opr_id=isset($response['data']['payout']['credit_refid'])?$response['data']['payout']['credit_refid']:"00";
                                                    $benename=isset($response['data']['payout']['name'])?$response['data']['payout']['name']:"Unable to verify";
                                                        
                                                    if(isset($response['statuscode']))
                                                        {
                     
                                                         
                                                        $error_mapping=$this->_ci->Inst_model->fetch_error_code($response['statuscode'],$chkservicestat['vendor_id']); 
            
                                                        if($error_mapping)
                                                        {

                            $response['status']=@$response['status'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$response['status']:$error_mapping['error_code_desc'];
                            
                                                                if($response['statuscode']=='TXN')
                                                                {
                                                                        $txnstatus='SUCCESS';
                                            
                                                                        $data['error'] = 0;
                                                                        $data['error_desc'] = null;
                                                                        $data['msg']='Beneficiary Verified Successfully, Beneficiary Name is '.$benename;
                                                                        $data['txndata']=array(
                                                                                    "txnid"=>$transid,
                                                                                    "opid"=>$opr_id,
                                                                                    "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                                    "status"=>$txnstatus,
                                                                                    "benename"=>$benename
                                                                            );     
                                  
                                                                }
                                                                else if($response['statuscode']=='TUP') 
                                                                { ///Timeout
                                                                                                    
                                                                    $txnstatus='PENDING';
                                            
                                                                    $data['error'] = 0;
                                                                    $data['error_desc'] = null;
                                                                    $data['msg']=$error_mapping['error_code_desc'];
                                                                    $data['txndata']=array(
                                                                                "txnid"=>$transid,
                                                                                "opid"=>$opr_id,
                                                                                "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                                "status"=>$txnstatus,
                                                                                "benename"=>$benename
                                                                        );
                                                        
                                     
                                                                } else{

                                                                    $txnstatus='FAILED';
                                            
                                                                    $data['error'] = 0;
                                                                    $data['error_desc'] = null;
                                                                    $data['msg']=$error_mapping['error_code_desc'];
                                                                    $data['txndata']=array(
                                                                                "txnid"=>$transid,
                                                                                "opid"=>$opr_id,
                                                                                "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                                "status"=>$txnstatus,
                                                                                "benename"=>$benename
                                                                        );
                                                     
                                                                }

                                                                

                                                        }else{

                                                        $txnstatus='PENDING';
                                            
                                                        $data['error'] = 0;
                                                        $data['error_desc'] = null;
                                                        $data['msg']='Other Unknown Error';
                                                        $data['txndata']=array(
                                                                    "txnid"=>$transid,
                                                                    "opid"=>$opr_id,
                                                                    "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                    "status"=>$txnstatus,
                                                                    "benename"=>$benename
                                                            ); 

                                                        $error_mapping['error_code']='OUE';
                                                        $error_mapping['error_code_desc']=$data['msg'];

                                                        }
                                                            
                                                         
                                                        }else{

                                                            $txnstatus='PENDING';
                                            
                                                            $data['error'] = 0;
                                                            $data['error_desc'] = null;
                                                            $data['msg']='Other Unknown Error';
                                                            $data['txndata']=array(
                                                                        "txnid"=>$transid,
                                                                        "opid"=>$opr_id,
                                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                        "status"=>$txnstatus,
                                                                        "benename"=>$benename
                                                                ); 

                                                            $error_mapping['error_code']='OUE';
                                                            $error_mapping['error_code_desc']=$data['msg'];

                                                        }


    
                                                    $update_txn_array=array(
                                                        "op2"=>$benename,
                                                        "sp_id" => $sp_id,
                                                        "opr_ref_no" => $opr_id,
                                                        "sp_respcode" => isset($response['statuscode'])?$response['statuscode']:'00',
                                                        "sp_respdesc" => isset($response['status'])?$response['status']:'00',
                                                        "sp_response" => $api_response,
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['txndata']['status'],
                                                        "upd_id" => $inserted_id
                                                    );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                         
                                                        
                                                    
                                                    }
                                                    catch(Exception $e)
                                                    {
                                                            $txnstatus='PENDING';

                                                            $data['error'] = 0;
                                                            $data['error_desc'] = null;
                                                            $data['msg']='Other Unknown Error';
                                                            $data['txndata']=array(
                                                                        "txnid"=>$transid,
                                                                        "opid"=>'00',
                                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                        "status"=>$txnstatus,
                                                                        "benename"=>""
                                                                ); 

                                                            $error_mapping['error_code']='OUE';
                                                            $error_mapping['error_code_desc']=$data['msg'];

                                                        $update_txn_array=array(
                                                                        "op2"=>$benename,
                                                                        "sp_id" => '00',
                                                                        "opr_ref_no" => '00',
                                                                        "sp_respcode" => '',
                                                                        "sp_respdesc" => 'Internal error, entered catch block '.$e->getMessage(),
                                                                        "sp_response" => $api_response,
                                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                                        "ind_rcode" => $error_mapping['error_code'],
                                                                        "response" => $error_mapping['error_code_desc'],
                                                                        "status" => $data['txndata']['status'],
                                                                        "upd_id" => $inserted_id
                                                        );


                                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);


                                                    }
                                                    
                                                } 
                                                else {///response false 
                                                
                                                    $txnstatus='PENDING';
                                    
                                                    $data['error'] = 0;
                                                    $data['error_desc'] = null;
                                                    $data['msg']='Transaction Under Process';
                                                    $data['txndata']=array(
                                                                "txnid"=>$transid,
                                                                "opid"=>'00',
                                                                "datetime"=>$RetailerTxnEntry['req_dt'],
                                                                "status"=>$txnstatus,
                                                                "benename"=>""
                                                        ); 

                                                    $error_mapping['error_code']='TUP';
                                                    $error_mapping['error_code_desc']=$data['msg'];

                                                    $update_txn_array=array(
                                                                        "op2"=>$benename,
                                                                        "sp_id" => '00',
                                                                        "opr_ref_no" => '00',
                                                                        "sp_respcode" => 'TMDOUT',
                                                                        "sp_respdesc" => 'Curl Timedout',
                                                                        "sp_response" => 'Curl Timedout',
                                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                                        "ind_rcode" => $error_mapping['error_code'],
                                                                        "response" => $error_mapping['error_code_desc'],
                                                                        "status" => $data['txndata']['status'],
                                                                        "upd_id" => $inserted_id
                                                        );


                                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);



                                                }

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Internal Processing Error, try again later";
                                                $data['msg'] = null;
                                               
                                            }
                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = "Insufficient Balance";
                                            $data['msg'] = null;
                                            
                                        }

                                    }else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Unable to find user details1';
                                        $data['msg'] = null;
                                        
                                    }

                                        
                                    }else{
                                        $data['error'] = 1;
                                        $data['error_desc'] = "Margin Configuration Issue";
                                        $data['msg'] = null;
                                    }
                                  
                                    
                                    
                                }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Plan not configured for your account";
                                    $data['msg'] = null;
                                }
                            

                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Unable to get service config details, try again later";
                                $data['msg'] = null;
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
               
//            } else {
//                $data['error'] = 1;
//                $data['error_desc'] = 'Invalid Name';
//                $data['msg'] = null;
//            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid Remitter Number';
            $data['msg'] = null;
        }
        
        return $data;
    }

    
  
    
}

?>
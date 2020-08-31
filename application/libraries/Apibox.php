<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Apibox {

	protected $_ci;
    private $token;
    private $default_outlet;

    function __construct() {

        $this->_ci = & get_instance();
        $this->_ci->load->model('Api_Model');
        $this->_ci->load->model('Inst_model');
        $this->_ci->load->config('apb_config');
        $this->token=$this->_ci->config->item('req_token');
        $this->default_outlet='14308';
    }


     function ApiBalanceCheck($uid) {
         $url = 'https://www.apibox.xyz/api/BalCheck/acbal?token=' . $this->token . '&format=json';

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

         if ($msg != false) {
             
             $log_insert_array=array(
                                        "user_id"=>$uid,
                                        "url"=>$url,
                                        "method"=>"GET",
                                        "ip"=>ip_address(),
                                        "req_params"=>'PARAMS IN URL',
                                        "req_for"=>"Balance Check",
                                        "response"=>($msg),
                                        "useragent"=>$this->_ci->agent->agent_string(),
                                        "datetime"=>date('Y-m-d H:i:s')
                                    );
                                    
             $this->_ci->Inst_model->insert_apbox_logs($log_insert_array); 
             
             $msg = json_decode($msg, true);
            
             $response = $msg['response'];
             if ($response['status_code'] == 'RCS') 
             {
                 $data['error'] = 0;
                 $data['error_desc'] = null;
                 $data['msg']['Balance'] = $response['usable_balance'];
                 $data['msg']['ResponseMessage'] = $response['desc'];
             } else {
                 $data['error'] = 1;
                 $data['error_desc'] = $response['desc'];
                 $data['msg'] = null;
             }
          
         } else {
             $data['error'] = 1;
             $data['error_desc'] = 'Request Timed Out';
             $data['msg'] = null;
          
             $log_insert_array=array(
                                        "user_id"=>$uid,
                                        "url"=>$url,
                                        "method"=>"GET",
                                        "ip"=>ip_address(),
                                        "req_params"=>'PARAMS IN URL',
                                        "req_for"=>"Balance Check",
                                        "response"=>'No Response, Curl Timeout',
                                        "useragent"=>$this->_ci->agent->agent_string(),
                                        "datetime"=>date('Y-m-d H:i:s')
                                    );
                                    
             $this->_ci->Inst_model->insert_apbox_logs($log_insert_array); 
             
         }
         return $data;
     }
    
     public function send_curl(array $requestparams) 
     {
        $data = array();
        
        $method = $requestparams['method'];
        $curlparams = $requestparams['params'];
        $curlparams['token']=$this->token;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestparams['url']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 180);

        if ($method == 'GET') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        } else if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curlparams));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        } else {

            $data['error'] = 1;
            $data['error_desc'] = 'Unable to send your request';
            $data['msg'] = null;
            $data['send'] = 'false';

            return $data;
        }

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        $err = curl_error($curl);
        $errno = curl_errno($curl);
        curl_close($curl);

        if ($response) {
            
            $log_insert_array=array(
                "user_id"=>$requestparams['user_id'],
                "url"=>$requestparams['url'],
                "method"=>$method,
                "ip"=>ip_address(),
                "req_params"=>($method == 'GET')?'GET request, check url':json_encode($curlparams),
                "req_for"=>$requestparams['reqfor'],
                "response"=>($response),
                "useragent"=>$this->_ci->agent->agent_string(),
                "datetime"=>date('Y-m-d H:i:s')
            );

           $this->_ci->Inst_model->insert_apbox_logs($log_insert_array);  
            
            
        } else {

            $log_insert_array=array(
                "user_id"=>$requestparams['user_id'],
                "url"=>$requestparams['url'],
                "method"=>$method,
                "ip"=>ip_address(),
                "req_params"=>($method == 'GET')?'GET request, check url':json_encode($curlparams),
                "req_for"=>$requestparams['reqfor'],
                "response"=>'No Response, Curl Timeout',
                "useragent"=>$this->_ci->agent->agent_string(),
                "datetime"=>date('Y-m-d H:i:s')
            );

           $this->_ci->Inst_model->insert_apbox_logs($log_insert_array); 
            
            
        }

        $data['send'] = 'true';
        $data['curldata'] = array(
            'info' => $info,
            'response' => $response,
            'error' => $err,
            'error_no' => $errno
        );

        return $data;
    }
 
    function request_outlet_action($user_info,$params,$vendordetails)
    {
        $params['req_for']=isset($params['req_for'])?(is_string($params['req_for'])?trim($params['req_for']):""):"";
        $data=array();
        if($params['req_for']=="REQUESTOUTLETREGISTER")
        {
            $data=$this->request_outlet_otp($user_info,$params,$vendordetails);
        }
        else if($params['req_for']=="VALIDATEOUTLETOTP")
        {
            $data=$this->validate_outlet_otp($user_info,$params,$vendordetails);
        }
        else{
            $data['error']=1;
            $data['error_desc']='Invalid or Incomplete Request';
            $data['msg']=null;
        }
        
        return $data;
        
    }
    
    function request_outlet_otp($user_info,$params,$vendordetails)
    {
        $data=array();
        //$params['contct_nmbr']=isset($params['contct_nmbr'])?(is_string($params['contct_nmbr'])?trim($params['contct_nmbr']):""):"";
        $regex_array=All_Regex();
        
        if($regex_array)
        {
            
            if(preg_match("/".$regex_array['Mobile']['Full']."/",$user_info['mobile']))
            {
                
                $requestparams = array(
                                "params" => array(
                                    "p1"=>"request",
                                    "p2"=>$user_info['mobile']
                                ),
                                "url" => "https://www.apibox.xyz/api/ManageUser/Outlet",
                                "method" => "POST",
                                "reqfor" => "Request for Outlet OTP",
                                "user_id" => $user_info['user_id'],
                            );

                 $msg = $this->send_curl($requestparams);
                
                
                 if ($msg['send'] === 'false') {

                    $data['error'] = 1;
                    $data['error_desc'] = $msg['error_desc'];
                    $data['msg'] = null;
                 
                 }else{
                     
                     if ($msg['curldata']['response'] != false) 
                     {
                         
                         try{
                             
                             $response_array=json_decode($msg['curldata']['response'],true);
                             
                             $status_code=isset($response_array['response']['status_code'])?$response_array['response']['status_code']:"";
                             
                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($status_code,$vendordetails['vendor_id']); 
                             
                             if($error_mapping)
                             {
                              
                            $response_array['response']['desc']=@$response_array['response']['desc'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$response_array['response']['desc']:$error_mapping['error_code_desc'];
                            
                                 if(@$response_array['response']['status_code']=='RCS')
                                 {
                                     
                                     if(@$response_array['response']['OTP']=='Y')
                                     {
                                      
                                         $data['error'] = 0;
                                         $data['error_desc'] = null;
                                         $data['msg'] = 'OTP sent successfully';
                                         
                                     }elseif(@$response_array['response']['OTP']=='N')
                                     {
                                         $response_array['response']['OutletID']=@$response_array['response']['OutletID'];
                                         if($response_array['response']['Status']=='ACTIVE')
                                         {
                                             
                                             
                                             $outlet_dt_array=array(
                                                 'user_id'=>$user_info['user_id'],
                                                 'kyc_apibox'=>$response_array['response']['OutletID'],
                                                 'status'=>'PENDING',
                                                 'date'=>date('Y-m-d H:i:s')
                                             );
                                             
                                             $data['error'] = 3;
                                             $data['error_desc'] = null;
                                             $data['msg'] = 'Service Activated Successfully';
                                             
                                         
                                         }else{
                                             $data['error'] = 1;
                                             $data['error_desc'] = 'Service not allowed for your account';
                                             $data['msg'] = null;
                                             
                                             $outlet_dt_array=array(
                                                 'user_id'=>$user_info['user_id'],
                                                 'kyc_apibox'=>$response_array['response']['OutletID'],
                                                 'status'=>'BLOCKED',
                                                 'date'=>date('Y-m-d H:i:s')
                                             );
                                             
                                         }
                                         
                                         $this->_ci->Inst_model->upgrade_user_bc_agent_data($outlet_dt_array);
                                         
                                         
                                     }else{
                                         $data['error'] = 1;
                                         $data['error_desc'] = 'Error Occured, contact helpdesk';
                                         $data['msg'] = null;
                                     }
                                     
                                 }else{
                                     $data['error'] = 1;
                                     $data['error_desc'] = $error_mapping['error_code_desc'];
                                     $data['msg'] = null;
                                 }
                                 
                             }else{
                                 $data['error'] = 1;
                                 $data['error_desc'] = 'Other Unknown Error';
                                 $data['msg'] = null;
                             }
                                 
                         }
                         catch(Exception $e)
                         {
                             $data['error'] = 1;
                             $data['error_desc'] = 'Exception occured';
                             $data['msg'] = null;
                         }
                         
                     }else{
                         $data['error'] = 1;
                         $data['error_desc'] = 'Request Timed Out';
                         $data['msg'] = null;
                     }
                     
                 } 
                
                
            }else{
                $data['error']=1;
                $data['error_desc']='Invalid contact number';
                $data['msg']=null;
            }
            
        }else{
            $data['error']=1;
            $data['error_desc']='Unable to process your request';
            $data['msg']=null;
        }
        return $data;
        
    }
    
    function validate_outlet_otp($user_info,$params,$vendordetails)
    {
        $data=array();
        $params['otp']=isset($params['otp'])?(is_string($params['otp'])?trim($params['otp']):""):"";
        $regex_array=All_Regex();
        
        if($regex_array)
        {
            
            if(preg_match("/".$regex_array['Mobile']['Full']."/",$user_info['mobile']))
            {
                
                $requestparams = array(
                                "params" => array(
                                    "p1"=>"validate",
                                    "p2"=>$user_info['mobile'],
                                    "p3"=>$params['otp'],
                                    "p4"=>$params['contact_person'],
                                    "p5"=>$params['shop_name'],
                                    "p6"=>'STORE',
                                    "p7"=>$params['shopaddr'],
                                    "p8"=>$params['shop_pincd'],
                                    "p9"=>$params['pan'],
                                    "p10"=>$params['aadhaar']
                                ),
                                "url" => "https://www.apibox.xyz/api/ManageUser/Outlet",
                                "method" => "POST",
                                "reqfor" => "Validate Outlet OTP",
                                "user_id" => $user_info['user_id'],
                            );

                 $msg = $this->send_curl($requestparams);
                
                
                 if ($msg['send'] === 'false') {

                    $data['error'] = 1;
                    $data['error_desc'] = $msg['error_desc'];
                    $data['msg'] = null;
                 
                 }else{
                     
                     if ($msg['curldata']['response'] != false) 
                     {
                         
                         try{
                             
                             $response_array=json_decode($msg['curldata']['response'],true);
                             
                             $status_code=isset($response_array['response']['status_code'])?$response_array['response']['status_code']:"";
                             
                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($status_code,$vendordetails['vendor_id']); 
                             
                             if($error_mapping)
                             {
                              
                            $response_array['response']['desc']=@$response_array['response']['desc'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$response_array['response']['desc']:$error_mapping['error_code_desc'];
                            
                                 if(@$response_array['response']['status_code']=='RCS')
                                 {
                                     
                                     
                                         $response_array['response']['OutletID']=@$response_array['response']['OutletID'];
                                             
                                             $outlet_dt_array=array(
                                                 'user_id'=>$user_info['user_id'],
                                                 'kyc_apibox'=>$response_array['response']['OutletID'],
                                                 'status'=>'PENDING',
                                                 'date'=>date('Y-m-d H:i:s')
                                             );
                                             
                                             $data['error'] = 0;
                                             $data['error_desc'] = null;
                                             $data['msg'] = 'Service Activated Successfully';
                                         
                                         $this->_ci->Inst_model->upgrade_user_bc_agent_data($outlet_dt_array);
                                     
                                     
                                 }else{
                                     $data['error'] = 1;
                                     $data['error_desc'] = $error_mapping['error_code_desc'];
                                     $data['msg'] = null;
                                 }
                                 
                             }else{
                                 $data['error'] = 1;
                                 $data['error_desc'] = 'Other Unknown Error';
                                 $data['msg'] = null;
                             }
                                 
                         }
                         catch(Exception $e)
                         {
                             $data['error'] = 1;
                             $data['error_desc'] = 'Exception occured';
                             $data['msg'] = null;
                         }
                         
                     }else{
                         $data['error'] = 1;
                         $data['error_desc'] = 'Request Timed Out';
                         $data['msg'] = null;
                     }
                     
                 } 
                
                
            }else{
                $data['error']=1;
                $data['error_desc']='Invalid contact number';
                $data['msg']=null;
            }
            
        }else{
            $data['error']=1;
            $data['error_desc']='Unable to process your request';
            $data['msg']=null;
        }
        return $data;
        
    }
    
    
    function prepaid_rech($user_info, $planid, $accnt, $amnt, $chkservicestat)
    {
        $accnt=strip_tags(trim($accnt));
        $amnt=strip_tags(trim($amnt));
        
        if(strlen($accnt)>=$chkservicestat['min_len'] && strlen($accnt)<=$chkservicestat['max_len'] && preg_match('/^[6789][0-9]{9}$/',$accnt))
        {
            if(ctype_digit($amnt) && $amnt>=$chkservicestat['min_amt'] && $amnt<=$chkservicestat['max_amt'])
            {

                 $getvendor_opr_details = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
                if ($getvendor_opr_details) 
                {
                    
                    $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                    
                    if($chck_user_pln_dtl)
                    {
                        
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
                                $data['error'] = 1;
                                $data['error_desc'] = "Slab rate not configured for your account";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                            
                        }
                        
                        
                        $retailer_trans_array=array();
                        
                        if($chkservicestat['billing_model']=="P2P")
                        {
                            if($reatiler_charge_method=="CREDIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                    $retailer_trans_array['gst']=0;
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                    
                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amnt)/100),2);
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
        $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_capping_amount:$retailer_trans_array['rate_value'];
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['gst']=0;
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }
                                
                                
                            }else{
                                
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                                
                            }
                        }
                        else if($chkservicestat['billing_model']=="P2A")
                        {
                            if($reatiler_charge_method=="CREDIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                    $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
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
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                        }
                        else if($chkservicestat['billing_model']=="CHARGE")
                        {
                            if($reatiler_charge_method=="DEBIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=0;
                                    $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                    $retailer_trans_array['app_comm']=0;
                                    $retailer_trans_array['tds']=0;
                                    $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['is_comm']=false;
                                    $retailer_trans_array['charged_amt']=$amnt+$retailer_trans_array['rate_value'];
                                    
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amnt)/100),2);
                                    
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
         $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_trans_array['rate_value']:$retailer_capping_amount;
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=0;
                                    $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                    $retailer_trans_array['app_comm']=0;
                                    $retailer_trans_array['tds']=0;
                                    $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['is_comm']=false;
                                    $retailer_trans_array['charged_amt']=$amnt+$retailer_trans_array['rate_value'];
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                            
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid Commission or Surcharge type is set.";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }
                        
                        if(count($retailer_trans_array)>0)
                        {
                            
                            
                            $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                            
                            if($findbalance)
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
                                            'customer_no' =>$accnt,///dynamic//
                                            'scode' => $chkservicestat['code'],
                                            "servicename"=>$chkservicestat['service_name'],
                                            "servicetype"=>$chkservicestat['type'],
                                            "servedby"=>$chkservicestat['vendor_id'],
                                            "transamt"=>$amnt,
                                            "chargeamt"=>$retailer_trans_array['charged_amt'],
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$closingbal,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Transaction Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$accnt,
                                        );
                                
                                $retailer_comm_tds_array=array();
                                
                                if($chkservicestat['billing_model']=="P2P" || $chkservicestat['billing_model']=="P2A")
                                {
                                    $retailer_CreditHistroyIdCashback = ch_txnid();
                                    $retailer_comm_openingbal = $closingbal;
                                    $retailer_comm_closingbal = $retailer_comm_openingbal + $retailer_trans_array['base_comm'];
                                    
                                    $retailer_cashback_desc="Cashback of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amnt.', Mobile Number : '.$accnt;
                                    
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
                                    
                                    $retailer_tds_description="TDS Of Rs. " . $retailer_trans_array['tds'] . " Deducted On Cashback Amount Of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amnt.', Mobile Number : '.$accnt;
                                    
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
                                                'trans_amt' => $amnt,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
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
                                                'trans_amt' => $amnt,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => 'PAID',
                                                'tds_status' => 'PAID',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                                }
                                
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
                                                    $chck_parent_agrd_fees_range = $this->_ci->Inst_model->check__new_pln_amnt_rng($checkparentsplan['plan_id'], $checkparentsplan['service_id'], $amnt);
                                                    
                                                    if($chck_parent_agrd_fees_range)
                                                    {
                                                        $parent_charge_type=$chck_parent_agrd_fees_range['charge_type'];
                                                        $parent_charge_method=$chck_parent_agrd_fees_range['charge_method'];
                                                        $parent_service_rate=$chck_parent_agrd_fees_range['rate'];
                                                        
                                                    }else{
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Slab rate not configured for your parent.";
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
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
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Invalid charge type is configured for your parent.";
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
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
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = "Invalid charge method is configured for your parent.";
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }
                                                
                                                

                                            }else{
                                                $data['error'] = 1;
                                                $data['error_desc'] = "Plan not configured for your parent.";
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }
                                            
                                        }
                                    }
                                    
                                }
                                
                            }
                           
                            if(is_numeric($closingbal) && $closingbal>=0)
                            {
                                
                            
                                
                            $initiate_transaction=$this->_ci->Inst_model->initiate_service_transaction($RetailerTxnEntry,$retailer_comm_tds_array,$parent_comm_tds_tax_array);   
                                
                            if($initiate_transaction)
                            {
                                
                                $inserted_id = $initiate_transaction;
                                
                                $url='https://www.apibox.xyz/api/Action/transact';
                                
                                $request_params=array();
                                $request_params['token']=$this->token;
                                $request_params['skey']=$getvendor_opr_details['vendor_key'];
                                $request_params['reqid']=$transid;
                                $request_params['p1']='new';
                                $request_params['p2']=$accnt;
                                $request_params['p3']=$amnt;
                                
                                
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                  CURLOPT_URL => $url,
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => "",
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 180,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => "POST",
                                  CURLOPT_POSTFIELDS => json_encode($request_params),
                                  CURLOPT_HTTPHEADER => array(
                                    "content-type: application/json",
                                   )
                                ));

                                $response = curl_exec($curl);
                                $err = curl_error($curl);
                                curl_close($curl);
                                
                                if($response!=false)
                                {
                                    
                                    $log_insert_array=array(
                                        "user_id"=>$user_info['user_id'],
                                        "url"=>$url,
                                        "method"=>"POST","ip"=>ip_address(),
                                        "req_params"=>json_encode($request_params),
                                        "req_for"=>"Init Prepaid trans",
                                        "response"=>($response),
                                        "useragent"=>$this->_ci->agent->agent_string(),
                                        "datetime"=>date('Y-m-d H:i:s')
                                    );
                                    
                                   $this->_ci->Inst_model->insert_apbox_logs($log_insert_array);  
                                
                                    try {
                                        
                                        $array_response = json_decode($response, true);
                                        
                                        $opr_id='00';
                                        $sp_id='00';
                                        
                                        if(isset($array_response['response']))
                                        {
                                            
                                    $array_response['response']['status_code']=@$array_response['response']['status_code'];
                                    $error_mapping=$this->_ci->Inst_model->fetch_error_code($array_response['response']['status_code'],$chkservicestat['vendor_id']); 
                                            
                                    $opr_id=isset($array_response['response']['Opr_txn_id'])?$array_response['response']['Opr_txn_id']:"00";
                                    $sp_id=isset($array_response['response']['OrderId'])?$array_response['response']['OrderId']:"00";
                                                
                                    
                                    if($error_mapping)
                                    {
                                        
                            $array_response['response']['desc']=@$array_response['response']['desc'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$array_response['response']['desc']:$error_mapping['error_code_desc'];
                                        
                                        
                                        if(isset($array_response['response']['Status']))
                                        {
                                         
                                            if($array_response['response']['Status']=="COMPLETED")
                                            {
                                                $data['error']=0;
                                                $data['msg']=$error_mapping['error_code_desc']; 
                                                $data['error_desc']=null;
                                                $txnstatus='SUCCESS';
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            else if($array_response['response']['Status']=="PENDING")
                                            {
                                                $data['error']=3;
                                                $data['msg']=$error_mapping['error_code_desc']; 
                                                $data['error_desc']=null;
                                                $txnstatus='PENDING';
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            else if($array_response['response']['Status']=="FAILED")
                                            {
                                                $data['error']=1;
                                                $data['msg']=null; 
                                                $data['error_desc']=$error_mapping['error_code_desc'];  //error in error_description.
                                                $txnstatus='FAILED';
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            else{
                                                $data['error']=3;
                                                $data['msg']='Other Unknown Error'; 
                                                $data['error_desc']=null;
                                                $txnstatus='PENDING';
                                                $error_mapping['error_code']='OUE';
                                                $error_mapping['error_code_desc']=$data['msg'];
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            
                                            
                                        }else{
                                            $data['error']=1;
                                            $data['msg']=null; 
                                            $data['error_desc']=$error_mapping['error_code_desc'];  //error in error_description.
                                            $txnstatus='FAILED';
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = $opr_id;
                                            $data['status'] = $txnstatus;
                                        }
                                        
                                        
                                    }else{
                                            $data['error']=3;
                                            $data['msg']='Other Unknown Error'; 
                                            $data['error_desc']=null;
                                            $txnstatus='PENDING';
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = $opr_id;
                                            $data['status'] = $txnstatus;
                                    }
                
                                            
                                        }else{
                                            
                                            $data['error']=3;
                                            $data['msg']='Other Unknown Error'; 
                                            $data['error_desc']=null;
                                            $txnstatus='PENDING';
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = $opr_id;
                                            $data['status'] = $txnstatus;
                                            
                                            
                                        }
                                        
                                        $update_txn_array=array(
                                                        "sp_id" => $sp_id,
                                                        "opr_ref_no" => $opr_id,
                                                        "sp_respcode" => isset($array_response['response']['status_code'])?$array_response['response']['status_code']:'00',
                                                        "sp_respdesc" => isset($array_response['response']['desc'])?$array_response['response']['desc']:'00',
                                                        "sp_response" => $response,
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                        );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                        
                                        
                                    }
                                    catch(Exception $e)
                                    {
                                            $data['error']=3;
                                            $data['msg']='Other Unknown Error'; 
                                            $data['error_desc']=null;
                                            $txnstatus='PENDING';
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = '00';
                                            $data['status'] = $txnstatus;
                                        
                                        $update_txn_array=array(
                                                        "sp_id" => '00',
                                                        "opr_ref_no" => '00',
                                                        "sp_respcode" => '',
                                                        "sp_respdesc" => 'Internal error, entered catch block '.$e->getMessage(),
                                                        "sp_response" => $response,
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                        );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                        
                                        
                                    }
                                    
                                    
                                    
                                }else{
                                    
                                    $log_insert_array=array(
                                        "user_id"=>$user_info['user_id'],
                                        "url"=>$url,
                                        "method"=>"POST","ip"=>ip_address(),
                                        "req_params"=>json_encode($request_params),
                                        "req_for"=>"Init Prepaid trans",
                                        "response"=>'No Response, Curl Timeout',
                                        "useragent"=>$this->_ci->agent->agent_string(),
                                        "datetime"=>date('Y-m-d H:i:s')
                                    );
                                    
                                    $this->_ci->Inst_model->insert_apbox_logs($log_insert_array);
                                    
                                    $data['error']=3;
                                    $data['msg']='Transaction Under Process'; 
                                    $data['error_desc']=null;
                                    $txnstatus='PENDING';
                                    $error_mapping['error_code']='TUP';
                                    $error_mapping['error_code_desc']=$data['msg'];
                                    $data['TxnId'] = $transid;
                                    $data['OPTId'] = '00';
                                    $data['status'] = $txnstatus;
                                    
                                    $update_txn_array=array(
                                                        "sp_id" => '00',
                                                        "opr_ref_no" => '00',
                                                        "sp_respcode" => 'TMDOUT',
                                                        "sp_respdesc" => 'Curl Timedout',
                                                        "sp_response" => 'Curl Timedout',
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                        );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                    
                                    
                                }
                                
                                
                                
                            }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Internal Processing Error, try again later';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                            }
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Insufficient Account Balance';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }
                                
                            }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find user details';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                            }
                            
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = "Margin Configuration Issue";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }
                        
                        
                    }else{
                        $data['error'] = 1;
                        $data['error_desc'] = "Plan not configured for your account";
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                    }
                    
                    
                }else{
                    $data['error'] = 1;
                    $data['error_desc'] = "Unable to get service config details, try again later";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                }
                

            }else{
                $data['error'] = 1;
                $data['error_desc'] = "Invalid Transaction Amount";
                $data['msg'] = null;
                $data['status'] = 'FAILED';
            }
        }else{
            $data['error'] = 1;
            $data['error_desc'] = "Invalid Mobile Number";
            $data['msg'] = null;
            $data['status'] = 'FAILED';
        }
        return $data;
    }

    function dth_rech($user_info, $planid, $accnt, $amnt, $chkservicestat)
    {
        $accnt=strip_tags(trim($accnt));
        $amnt=strip_tags(trim($amnt));
        
        if(strlen($accnt)>=$chkservicestat['min_len'] && strlen($accnt)<=$chkservicestat['max_len'])
        {
            if(ctype_digit($amnt) && $amnt>=$chkservicestat['min_amt'] && $amnt<=$chkservicestat['max_amt'])
            {

                $getvendor_opr_details = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
                if ($getvendor_opr_details) 
                {
                    
                    $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                    
                    if($chck_user_pln_dtl)
                    {
                        
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
                                $data['error'] = 1;
                                $data['error_desc'] = "Slab rate not configured for your account";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                            
                        }
                        
                        
                        $retailer_trans_array=array();
                        
                        if($chkservicestat['billing_model']=="P2P")
                        {
                            if($reatiler_charge_method=="CREDIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                    $retailer_trans_array['gst']=0;
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                    
                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amnt)/100),2);
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
        $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_capping_amount:$retailer_trans_array['rate_value'];
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['gst']=0;
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }
                                
                                
                            }else{
                                
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                                
                            }
                        }
                        else if($chkservicestat['billing_model']=="P2A")
                        {
                            if($reatiler_charge_method=="CREDIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                    $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
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
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                        }
                        else if($chkservicestat['billing_model']=="CHARGE")
                        {
                            if($reatiler_charge_method=="DEBIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=0;
                                    $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                    $retailer_trans_array['app_comm']=0;
                                    $retailer_trans_array['tds']=0;
                                    $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['is_comm']=false;
                                    $retailer_trans_array['charged_amt']=$amnt+$retailer_trans_array['rate_value'];
                                    
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amnt)/100),2);
                                    
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
         $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_trans_array['rate_value']:$retailer_capping_amount;
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=0;
                                    $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                    $retailer_trans_array['app_comm']=0;
                                    $retailer_trans_array['tds']=0;
                                    $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['is_comm']=false;
                                    $retailer_trans_array['charged_amt']=$amnt+$retailer_trans_array['rate_value'];
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                    return $data;
                                }
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                                return $data;
                            }
                            
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid Commission or Surcharge type is set.";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                            return $data;
                        }
                        
                        if(count($retailer_trans_array)>0)
                        {
                            
                            
                            $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                            
                            if($findbalance)
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
                                            'customer_no' =>$accnt,///dynamic//
                                            'scode' => $chkservicestat['code'],
                                            "servicename"=>$chkservicestat['service_name'],
                                            "servicetype"=>$chkservicestat['type'],
                                            "servedby"=>$chkservicestat['vendor_id'],
                                            "transamt"=>$amnt,
                                            "chargeamt"=>$retailer_trans_array['charged_amt'],
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$closingbal,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Transaction Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$accnt,
                                        );
                                
                                $retailer_comm_tds_array=array();
                                
                                if($chkservicestat['billing_model']=="P2P" || $chkservicestat['billing_model']=="P2A")
                                {
                                    $retailer_CreditHistroyIdCashback = ch_txnid();
                                    $retailer_comm_openingbal = $closingbal;
                                    $retailer_comm_closingbal = $retailer_comm_openingbal + $retailer_trans_array['base_comm'];
                                    
                                    $retailer_cashback_desc="Cashback of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amnt.', Mobile Number : '.$accnt;
                                    
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
                                    
                                    $retailer_tds_description="TDS Of Rs. " . $retailer_trans_array['tds'] . " Deducted On Cashback Amount Of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amnt.', Connection Number : '.$accnt;
                                    
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
                                                'trans_amt' => $amnt,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
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
                                                'trans_amt' => $amnt,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => 'PAID',
                                                'tds_status' => 'PAID',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                                }
                                
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
                                                    $chck_parent_agrd_fees_range = $this->_ci->Inst_model->check__new_pln_amnt_rng($checkparentsplan['plan_id'], $checkparentsplan['service_id'], $amnt);
                                                    
                                                    if($chck_parent_agrd_fees_range)
                                                    {
                                                        $parent_charge_type=$chck_parent_agrd_fees_range['charge_type'];
                                                        $parent_charge_method=$chck_parent_agrd_fees_range['charge_method'];
                                                        $parent_service_rate=$chck_parent_agrd_fees_range['rate'];
                                                        
                                                    }else{
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Slab rate not configured for your parent.";
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
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
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Invalid charge type is configured for your parent.";
                                                        $data['msg'] = null;
                                                        $data['status'] = 'FAILED';
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
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = "Invalid charge method is configured for your parent.";
                                                    $data['msg'] = null;
                                                    $data['status'] = 'FAILED';
                                                    return $data;
                                                }
                                                
                                                

                                            }else{
                                                $data['error'] = 1;
                                                $data['error_desc'] = "Plan not configured for your parent.";
                                                $data['msg'] = null;
                                                $data['status'] = 'FAILED';
                                                return $data;
                                            }
                                            
                                        }
                                    }
                                    
                                }
                                
                            }
                           
                            if(is_numeric($closingbal) && $closingbal>=0)
                            {    
                                
                            $initiate_transaction=$this->_ci->Inst_model->initiate_service_transaction($RetailerTxnEntry,$retailer_comm_tds_array,$parent_comm_tds_tax_array);   
                                
                            if($initiate_transaction)
                            {
                                
                                $inserted_id = $initiate_transaction;
                                
                                $url='https://www.apibox.xyz/api/Action/transact';
                                
                                $request_params=array();
                                $request_params['token']=$this->token;
                                $request_params['skey']=$getvendor_opr_details['vendor_key'];
                                $request_params['reqid']=$transid;
                                $request_params['p1']='new';
                                $request_params['p2']=$accnt;
                                $request_params['p3']=$amnt;
                                
                                
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                  CURLOPT_URL => $url,
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => "",
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 180,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => "POST",
                                  CURLOPT_POSTFIELDS => json_encode($request_params),
                                  CURLOPT_HTTPHEADER => array(
                                    "content-type: application/json",
                                   )
                                ));

                                $response = curl_exec($curl);
                                $err = curl_error($curl);
                                curl_close($curl);
                                
                                if($response!=false)
                                {
                                    
                                    $log_insert_array=array(
                                        "user_id"=>$user_info['user_id'],
                                        "url"=>$url,
                                        "method"=>"POST","ip"=>ip_address(),
                                        "req_params"=>json_encode($request_params),
                                        "req_for"=>"Init Prepaid trans",
                                        "response"=>($response),
                                        "useragent"=>$this->_ci->agent->agent_string(),
                                        "datetime"=>date('Y-m-d H:i:s')
                                    );
                                    
                                   $this->_ci->Inst_model->insert_apbox_logs($log_insert_array);  
                                
                                    try {
                                        
                                        $array_response = json_decode($response, true);
                                        
                                        $opr_id='00';
                                        $sp_id='00';
                                        
                                        if(isset($array_response['response']))
                                        {
                                            
                                    $array_response['response']['status_code']=@$array_response['response']['status_code'];
                                    $error_mapping=$this->_ci->Inst_model->fetch_error_code($array_response['response']['status_code'],$chkservicestat['vendor_id']); 
                                            
                                    $opr_id=isset($array_response['response']['Opr_txn_id'])?$array_response['response']['Opr_txn_id']:"00";
                                    $sp_id=isset($array_response['response']['OrderId'])?$array_response['response']['OrderId']:"00";
                                                
                                    
                                    if($error_mapping)
                                    {
                                        
                            $array_response['response']['desc']=@$array_response['response']['desc'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$array_response['response']['desc']:$error_mapping['error_code_desc'];
                                        
                                        
                                        if(isset($array_response['response']['Status']))
                                        {
                                         
                                            if($array_response['response']['Status']=="COMPLETED")
                                            {
                                                $data['error']=0;
                                                $data['msg']=$error_mapping['error_code_desc']; 
                                                $data['error_desc']=null;
                                                $txnstatus='SUCCESS';
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            else if($array_response['response']['Status']=="PENDING")
                                            {
                                                $data['error']=3;
                                                $data['msg']=$error_mapping['error_code_desc']; 
                                                $data['error_desc']=null;
                                                $txnstatus='PENDING';
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            else if($array_response['response']['Status']=="FAILED")
                                            {
                                                $data['error']=1;
                                                $data['msg']=null; 
                                                $data['error_desc']=$error_mapping['error_code_desc'];  //error in error_description.
                                                $txnstatus='FAILED';
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            else{
                                                $data['error']=3;
                                                $data['msg']='Other Unknown Error'; 
                                                $data['error_desc']=null;
                                                $txnstatus='PENDING';
                                                $error_mapping['error_code']='OUE';
                                                $error_mapping['error_code_desc']=$data['msg'];
                                                $data['TxnId'] = $transid;
                                                $data['OPTId'] = $opr_id;
                                                $data['status'] = $txnstatus;
                                            }
                                            
                                            
                                        }else{
                                            $data['error']=1;
                                            $data['msg']=null; 
                                            $data['error_desc']=$error_mapping['error_code_desc'];  //error in error_description.
                                            $txnstatus='FAILED';
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = $opr_id;
                                            $data['status'] = $txnstatus;
                                        }
                                        
                                        
                                    }else{
                                            $data['error']=3;
                                            $data['msg']='Other Unknown Error'; 
                                            $data['error_desc']=null;
                                            $txnstatus='PENDING';
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = $opr_id;
                                            $data['status'] = $txnstatus;
                                    }
                
                                            
                                        }else{
                                            
                                            $data['error']=3;
                                            $data['msg']='Other Unknown Error'; 
                                            $data['error_desc']=null;
                                            $txnstatus='PENDING';
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = $opr_id;
                                            $data['status'] = $txnstatus;
                                            
                                            
                                        }
                                        
                                        $update_txn_array=array(
                                                        "sp_id" => $sp_id,
                                                        "opr_ref_no" => $opr_id,
                                                        "sp_respcode" => isset($array_response['response']['status_code'])?$array_response['response']['status_code']:'00',
                                                        "sp_respdesc" => isset($array_response['response']['desc'])?$array_response['response']['desc']:'00',
                                                        "sp_response" => $response,
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                        );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                        
                                        
                                    }
                                    catch(Exception $e)
                                    {
                                            $data['error']=3;
                                            $data['msg']='Other Unknown Error'; 
                                            $data['error_desc']=null;
                                            $txnstatus='PENDING';
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                            $data['TxnId'] = $transid;
                                            $data['OPTId'] = '00';
                                            $data['status'] = $txnstatus;
                                        
                                        $update_txn_array=array(
                                                        "sp_id" => '00',
                                                        "opr_ref_no" => '00',
                                                        "sp_respcode" => '',
                                                        "sp_respdesc" => 'Internal error, entered catch block '.$e->getMessage(),
                                                        "sp_response" => $response,
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                        );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                        
                                        
                                    }
                                    
                                    
                                    
                                }else{
                                    
                                    $log_insert_array=array(
                                        "user_id"=>$user_info['user_id'],
                                        "url"=>$url,
                                        "method"=>"POST","ip"=>ip_address(),
                                        "req_params"=>json_encode($request_params),
                                        "req_for"=>"Init Prepaid trans",
                                        "response"=>'No Response, Curl Timeout',
                                        "useragent"=>$this->_ci->agent->agent_string(),
                                        "datetime"=>date('Y-m-d H:i:s')
                                    );
                                    
                                    $this->_ci->Inst_model->insert_apbox_logs($log_insert_array);
                                    
                                    $data['error']=3;
                                    $data['msg']='Transaction Under Process'; 
                                    $data['error_desc']=null;
                                    $txnstatus='PENDING';
                                    $error_mapping['error_code']='TUP';
                                    $error_mapping['error_code_desc']=$data['msg'];
                                    $data['TxnId'] = $transid;
                                    $data['OPTId'] = '00';
                                    $data['status'] = $txnstatus;
                                    
                                    $update_txn_array=array(
                                                        "sp_id" => '00',
                                                        "opr_ref_no" => '00',
                                                        "sp_respcode" => 'TMDOUT',
                                                        "sp_respdesc" => 'Curl Timedout',
                                                        "sp_response" => 'Curl Timedout',
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['status'],
                                                        "upd_id" => $inserted_id
                                        );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                    
                                    
                                }
                                
                                
                                
                            }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Internal Processing Error, try again later';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                            }
                             
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Insufficient Account Balance';
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';

                            }
                                
                                
                            }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find user details';
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                            }
                            
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = "Margin Configuration Issue";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }
                        
                        
                    }else{
                        $data['error'] = 1;
                        $data['error_desc'] = "Plan not configured for your account";
                        $data['msg'] = null;
                        $data['status'] = 'FAILED';
                    }
                    
                    
                }else{
                    $data['error'] = 1;
                    $data['error_desc'] = "Unable to get service config details, try again later";
                    $data['msg'] = null;
                    $data['status'] = 'FAILED';
                }
                

            }else{
                $data['error'] = 1;
                $data['error_desc'] = "Invalid Transaction Amount";
                $data['msg'] = null;
                $data['status'] = 'FAILED';
            }
        }else{
            $data['error'] = 1;
            $data['error_desc'] = "Invalid Connection Number";
            $data['msg'] = null;
            $data['status'] = 'FAILED';
        }
        return $data;
    }
    
    function process_billpayment($user_info,$params,$chkservicestat)
    {
        $data=array();
        
        $params['reqtype']=isset($params['reqtype'])?(is_string($params['reqtype'])?trim($params['reqtype']):""):"";
        
        $data=$this->validate_bill_payment_params($user_info,$params,$chkservicestat);
        
        return $data;
        
    }
    
    private function validate_bill_payment_params($user_info,$params,$chkservicestat)
    {
        $data=array();
        $regex_array=All_Regex();
        $txn_params_summary=array(
            "account"=>"",
            "description"=>""
        );
        
        $getvendor_opr_details = $this->_ci->Inst_model->getopertor($chkservicestat['service_id'], $chkservicestat['vendor_id']);
        if ($getvendor_opr_details) 
        {
            
        $txn_params_data = $this->_ci->Main_model->servc_txn_params($chkservicestat['service_id'],true);
        
        if($txn_params_data && $regex_array)
        {
            
            $param_count=0;
            $api_request_params=array();
            $valid=false;
            foreach($txn_params_data as $tk=>$tv)
            {
                if(isset($params[$tv['param_code']]))
                {
                    if(is_string($params[$tv['param_code']]) && preg_match('/'.$tv['full_regex'].'/',$params[$tv['param_code']]))
                    {
                        $valid=true;
                        if($param_count==0)
                        {
                            $api_request_params['p2']=$params[$tv['param_code']];
                            $param_count=4;
                            $txn_params_summary["account"]=$params[$tv['param_code']];
                            
                        }else{
                            $api_request_params['p'.$param_count]=$params[$tv['param_code']];
                            $param_count+=1;
                        }
                        
                        $txn_params_summary['description'].=$tv['param_name']." : ".$params[$tv['param_code']].", ";
                        
                    }else{
                        $valid=false;
                        $data['error']=1;
                        $data['error_desc']='Invalid '.$tv['param_name'];
                        $data['msg']=null;
                        return $data;
                    }
                    
                }else{
                    $valid=false;
                    $data['error']=1;
                    $data['error_desc']='Invalid '.$tv['param_name'];
                    $data['msg']=null;
                    return $data;
                }
            }
            
            
            if($chkservicestat['is_bbps']==1)
            {
                if(isset($params['bbps_cstmmob']))
                {
                    if(preg_match("/".$regex_array['Mobile']['Full']."/",$params['bbps_cstmmob']))
                    {
                        $valid=($valid===true)?true:$valid;
                        
                        $find_lat_long_bypincode=$this->_ci->Main_model->get_distinct_pincodedt($user_info['business_pincode']);
                        
                        $api_request_params['p18']='AGT';
                        $api_request_params['p19']=$user_info['mobile'];
                        $api_request_params['p20']=$find_lat_long_bypincode?($find_lat_long_bypincode['latitude'].",".$find_lat_long_bypincode['longitude']):"28.5621,77.2857";
                        $api_request_params['p21']=$user_info['business_pincode'];
                        $api_request_params['p25']=$params['bbps_cstmmob'];
                        
                    }else{
                        $valid=false;
                        $data['error']=1;
                        $data['error_desc']='Invalid Customer Mobile Number';
                        $data['msg']=null;
                        return $data;
                    }
                    
                }else{
                    $valid=false;
                    $data['error']=1;
                    $data['error_desc']='Invalid Customer Mobile Number';
                    $data['msg']=null;
                    return $data;
                }
            }
            
            if($chkservicestat['verify_outlet']==1)
            {
                
                $check_if_bc_agent_exist=$this->_ci->Main_model->check_outlet_id($user_info['user_id']);
                if($check_if_bc_agent_exist)
                {
                    if($check_if_bc_agent_exist['status']=='ACTIVE' || $check_if_bc_agent_exist['status']=='PENDING')
                    {
                        
                        $valid=($valid===true)?true:$valid;
                    
                        $api_request_params['outlet_id']=($check_if_bc_agent_exist['status']=='PENDING')?$this->default_outlet:$check_if_bc_agent_exist['kyc_apibox'];
                        
                    }else{
                        $valid=false;
                        $data['error']=1;
                        $data['error_desc']='Service Not Allowed';
                        $data['msg']=null;
                        return $data;
                    }
                    
                    
                }else{
                    $valid=false;
                    $data['error']=1;
                    $data['error_desc']='Service Not Allowed';
                    $data['msg']=null;
                    return $data;
                }
                
            }
            
            if($valid===true)
            {
                
                if($params['reqtype']=='VALIDATE')
                {
                    if($chkservicestat['bill_fetch']==1)
                    {
                        $data=$this->fetch_bill_byoperator($user_info,$params,$chkservicestat,$api_request_params,$getvendor_opr_details);
                    }else{
                        
                        if(isset($params['amount']) && is_numeric($params['amount']) && $params['amount']>=$chkservicestat['min_amt'] && $params['amount']<=$chkservicestat['max_amt'])
                        {
                        
                        $data['error']=0;
                        $data['error_desc']=null;
                        $data['msg']='Transaction is validated, Please proceed';
                        $data['validated']=true;
                            
                        }else{
                            $data['error']=1;
                            $data['error_desc']='Invalid Transaction Amount';
                            $data['msg']=null;
                        }
                        
                    }
                    
                    
                }else if($params['reqtype']=='TRANSACT')
                {
                    
                    $data=$this->initiate_billpay_txn($user_info,$params,$chkservicestat,$api_request_params,$getvendor_opr_details,$txn_params_summary);
                    
                }else{
                    $data['error']=1;
                    $data['error_desc']='This particular request is not supported, contact helpdesk';
                    $data['msg']=null;
                }
                
            }else{
                $data['error']=1;
                $data['error_desc']='Internal Processing Error';
                $data['msg']=null;
            }
            
            
        }else{
            $data['error']=1;
            $data['error_desc']='Service configuration issue, try again later';
            $data['msg']=null;
        }
            
        }else{
                    $data['error'] = 1;
                    $data['error_desc'] = "Unable to get service config details, try again later";
                    $data['msg'] = null;
        }
        return $data;
    }
    
    private function fetch_bill_byoperator($user_info,$params,$chkservicestat,$api_request_params,$getvendor_opr_details)
    {
        $data=array();
        
        if(@$params['reqtype']=='VALIDATE')
        {
            
            if($chkservicestat['bill_fetch']==1)
            {
                $api_request_params['skey']=$getvendor_opr_details['vendor_key'];
                $api_request_params['reqid']=generate_txnid();
                $api_request_params['p1']='dueamt';
                
                
                $requestparams = array(
                                "params" => $api_request_params,
                                "url" => "https://www.apibox.xyz/api/Fetch/Bill",
                                "method" => "POST",
                                "reqfor" => "Bill Fetch Request",
                                "user_id" => $user_info['user_id'],
                            );

                 $msg = $this->send_curl($requestparams);
                
                 if ($msg['send'] === 'false') {

                    $data['error'] = 1;
                    $data['error_desc'] = $msg['error_desc'];
                    $data['msg'] = null;
                 
                 }else{
                     
                     if ($msg['curldata']['response'] != false) 
                     {
                         
                         try{
                             
                             $response_array=json_decode($msg['curldata']['response'],true);
                             
                             $status_code=isset($response_array['response']['status_code'])?$response_array['response']['status_code']:"";
                             
                             $error_mapping=$this->_ci->Inst_model->fetch_error_code($status_code,$getvendor_opr_details['vendor_id']); 
                             
                             if($error_mapping)
                             {
                                 
                            $response_array['response']['desc']=@$response_array['response']['desc'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$response_array['response']['desc']:$error_mapping['error_code_desc'];
                            
                            if(@$response_array['response']['status_code']=='RCS')
                            {
                                
                                $data['error']=0;
                                $data['error_desc']=null;
                                $data['msg']='Transaction is validated, Please proceed';
                                $data['validated']=true;
                                $data['billamount']=$response_array['response']['due_amt'];
                                $data['resultid']=$response_array['response']['ResultID'];
                                $data['billdetails']=$response_array['response']['BillDetail'];
                                
                            }else{
                                $data['error'] =1;
                                $data['error_desc'] =$error_mapping['error_code_desc'];
                                $data['msg'] = null;
                            }
                                 
                             }else{
                                 $data['error'] =1;
                                 $data['error_desc'] = 'Other Unknown Error';
                                 $data['msg'] = null;
                             }
                             
                         }
                         catch(Exception $e)
                         {
                             $data['error'] = 1;
                             $data['error_desc'] = 'Exception occured';
                             $data['msg'] = null;
                         }
                         
                     }else{
                         $data['error'] = 1;
                         $data['error_desc'] = 'Request Timed Out';
                         $data['msg'] = null;
                     }
                     
                 }
                
            }else{
                $data['error']=1;
                $data['error_desc']='Bill fetch is not supported in this service';
                $data['msg']=null;
            }
            
        }else{
            $data['error']=1;
            $data['error_desc']='This particular request is not supported, contact helpdesk';
            $data['msg']=null;
        }
        
        return $data;
        
    }
    
    private function initiate_billpay_txn($user_info,$params,$chkservicestat,$api_request_params,$getvendor_opr_details,$txn_params_summary)
    {
        $data=array();
        
        if(@$params['reqtype']=='TRANSACT')
        {
            
            if(isset($params['amount']) && is_numeric($params['amount']) && $params['amount']>=$chkservicestat['min_amt'] && $params['amount']<=$chkservicestat['max_amt'])
            {
                
                $planid=$user_info['plan_id'];
                $amnt=$params['amount'];
                
                $chck_user_pln_dtl = $this->_ci->Inst_model->checkuser_pln_dtl($user_info['role_id'],$planid,$chkservicestat['service_id']);
                    
                    if($chck_user_pln_dtl)
                    {
                        
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
                                $data['error'] = 1;
                                $data['error_desc'] = "Slab rate not configured for your account";
                                $data['msg'] = null;
                                return $data;
                            }
                            
                        }
                        
                        
                        $retailer_trans_array=array();
                        
                        if($chkservicestat['billing_model']=="P2P")
                        {
                            if($reatiler_charge_method=="CREDIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                    $retailer_trans_array['gst']=0;
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                    
                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amnt)/100),2);
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
        $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_capping_amount:$retailer_trans_array['rate_value'];
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['gst']=0;
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    return $data;
                                }
                                
                                
                            }else{
                                
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                return $data;
                                
                            }
                        }
                        else if($chkservicestat['billing_model']=="P2A")
                        {
                            if($reatiler_charge_method=="CREDIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=$retailer_service_rate;
                                    $retailer_trans_array['gst']=round((($retailer_trans_array['base_comm']*18)/100),2);
                                    $retailer_trans_array['app_comm']=$retailer_trans_array['base_comm']+$retailer_trans_array['gst'];
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
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
                                    $retailer_trans_array['tds']=round(((($retailer_trans_array['base_comm'])*($user_info['tds_value']))/100),2);
                                    $retailer_trans_array['is_comm']=true;
                                    $retailer_trans_array['charged_amt']=$amnt;
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    return $data;
                                }
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                return $data;
                            }
                        }
                        else if($chkservicestat['billing_model']=="CHARGE")
                        {
                            if($reatiler_charge_method=="DEBIT")
                            {
                                
                                if($retailer_charge_type=="FIXED")
                                {
                                    $retailer_trans_array['rate_value']=$retailer_service_rate;
                                    $retailer_trans_array['base_comm']=0;
                                    $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                    $retailer_trans_array['app_comm']=0;
                                    $retailer_trans_array['tds']=0;
                                    $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['is_comm']=false;
                                    $retailer_trans_array['charged_amt']=$amnt+$retailer_trans_array['rate_value'];
                                    
                                }
                                else if($retailer_charge_type=="PERCENTAGE")
                                {
                                    $retailer_trans_array['rate_value']=round((($retailer_service_rate*$amnt)/100),2);
                                    
                                    if(is_numeric($retailer_capping_amount))
                                    {
                                        
         $retailer_trans_array['rate_value']=($retailer_trans_array['rate_value']>$retailer_capping_amount)?$retailer_trans_array['rate_value']:$retailer_capping_amount;
                                        
                                    }
                                    
                                    $retailer_trans_array['base_comm']=0;
                                    $retailer_trans_array['gst']=($retailer_trans_array['rate_value']-(round((($retailer_trans_array['rate_value']/118)*100),2)));
                                    $retailer_trans_array['app_comm']=0;
                                    $retailer_trans_array['tds']=0;
                                    $retailer_trans_array['charge']=$retailer_trans_array['rate_value'];
                                    $retailer_trans_array['is_comm']=false;
                                    $retailer_trans_array['charged_amt']=$amnt+$retailer_trans_array['rate_value'];
                                    
                                }
                                else
                                {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid charge type is configured for your account.";
                                    $data['msg'] = null;
                                    return $data;
                                }
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid charge method is configured for your account.";
                                $data['msg'] = null;
                                return $data;
                            }
                            
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid Commission or Surcharge type is set.";
                            $data['msg'] = null;
                            return $data;
                        }
                        
                        if(count($retailer_trans_array)>0)
                        {
                            
                            
                            $findbalance = $this->_ci->Inst_model->user_info($user_info['user_id']);
                            
                            if($findbalance)
                            {
                                
                                $openingbal = $findbalance['rupee_balance'];
                                $closingbal = $openingbal-$retailer_trans_array['charged_amt'];
                                
                                $transid=generate_txnid();
                                
                                $txn_params_summary["account"]=@$txn_params_summary["account"];
                                $txn_params_summary["description"]=(substr($txn_params_summary["description"],-3)==", ")?substr($txn_params_summary["description"],0,strlen($txn_params_summary["description"])-3):$txn_params_summary["description"];
                                
                                $RetailerTxnEntry = array(
                                            "user_id"=>$findbalance['user_id'],
                                            "req_ip"=>ip_address(),
                                            "request_useragent "=>$this->_ci->agent->agent_string(),
                                            "fstpytxn_id"=>$transid,
                                            "sp_id"=>"00",
                                            "opr_ref_no"=>"00",
                                            'customer_no' =>$txn_params_summary["account"],///dynamic//
                                            'scode' => $chkservicestat['code'],
                                            "servicename"=>$chkservicestat['service_name'],
                                            "servicetype"=>$chkservicestat['type'],
                                            "servedby"=>$chkservicestat['vendor_id'],
                                            "transamt"=>$amnt,
                                            "chargeamt"=>$retailer_trans_array['charged_amt'],
                                            "openingbal"=>$openingbal,
                                            "closingbal"=>$closingbal,
                                            "req_dt"=>date('Y-m-d H:i:s'),
                                            "res_dt"=>"0000-00-00 00:00:00",
                                            "ind_rcode"=>'TUP',
                                            "response"=>"Transaction Under Process",
                                            "status"=>"PENDING",
                                            "op1"=>$txn_params_summary["description"],
                                        );
                                
                                $retailer_comm_tds_array=array();
                                
                                if($chkservicestat['billing_model']=="P2P" || $chkservicestat['billing_model']=="P2A")
                                {
                                    $retailer_CreditHistroyIdCashback = ch_txnid();
                                    $retailer_comm_openingbal = $closingbal;
                                    $retailer_comm_closingbal = $retailer_comm_openingbal + $retailer_trans_array['base_comm'];
                                    
                                    $retailer_cashback_desc="Cashback of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amnt;
                                    
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
                                    
                                    $retailer_tds_description="TDS Of Rs. " . $retailer_trans_array['tds'] . " Deducted On Cashback Amount Of Rs. " . $retailer_trans_array['base_comm'] . " Received For " . $transid . ', Transaction Amount : Rs. ' . $amnt;
                                    
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
                                                'trans_amt' => $amnt,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => ($chkservicestat['billing_model'] == 'P2P') ? 'PAID' : 'PENDING',
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
                                                'trans_amt' => $amnt,
                                                'charged_amt' => $retailer_trans_array['charged_amt'],
                                                'comm_amnt' => $retailer_trans_array['base_comm'],
                                                'tds_amnt' => $retailer_trans_array['tds'],
                                                'gst_amnt' => $retailer_trans_array['gst'],
                                                'gst_status' => 'PAID',
                                                'tds_status' => 'PAID',
                                                'tax_type' => 'CREDIT',
                                                'created_dt' => date('Y-m-d H:i:s'),
                                                'created_by' => $findbalance['user_id'],
                                            );
                                }
                                
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
                                                    $chck_parent_agrd_fees_range = $this->_ci->Inst_model->check__new_pln_amnt_rng($checkparentsplan['plan_id'], $checkparentsplan['service_id'], $amnt);
                                                    
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
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Invalid charge type is configured for your parent.";
                                                        $data['msg'] = null;
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
                           
                                
                             if(is_numeric($closingbal) && $closingbal>=0)
                            { 
                                
                            $initiate_transaction=$this->_ci->Inst_model->initiate_service_transaction($RetailerTxnEntry,$retailer_comm_tds_array,$parent_comm_tds_tax_array);   
                                
                            if($initiate_transaction)
                            {
                                
                                $inserted_id = $initiate_transaction;
                                
                                $api_request_params['skey']=$getvendor_opr_details['vendor_key'];
                                $api_request_params['reqid']=$transid;
                                $api_request_params['p1']='new';
                                $api_request_params['p3']=$amnt;
                                
                                if($chkservicestat['is_bbps']==1)
                                {
                                    if($chkservicestat['bill_fetch']==1)
                                    {
                                    $api_request_params['ResultId']=@$params['resultid'];
                                    }
                                    
                                $api_request_params['p23']='Cash';
                                $api_request_params['p24']='Payment Received from Customer Mobile '.$api_request_params['p25'];
                                }
                                
                                
                                $requestparams = array(
                                                "params" => $api_request_params,
                                                "url" => "https://www.apibox.xyz/api/Action/transact",
                                                "method" => "POST",
                                                "reqfor" => "BillPayment Txn Request",
                                                "user_id" => $user_info['user_id'],
                                            );

                                 $msg = $this->send_curl($requestparams);
                
                                
                                if ($msg['send'] === 'false') {

                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = 'Other Unknown Error';
                                $data['txndata']=array(
                                        "txnid"=>$transid,
                                        "opid"=>"00",
                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                        "status"=>"PENDING",
                                );  
                                    
                                $error_mapping['error_code']='OUE';
                                $error_mapping['error_code_desc']=$data['msg'];
                                        
                                $update_txn_array=array(
                                                "sp_id" => '00',
                                                "opr_ref_no" => '00',
                                                "sp_respcode" => '',
                                                "sp_respdesc" => 'send curl function returned false',
                                                "sp_response" => 'send curl function returned false',
                                                "res_dt" => date('Y-m-d H:i:s'),
                                                "ind_rcode" => $error_mapping['error_code'],
                                                "response" => $error_mapping['error_code_desc'],
                                                "status" => $data['txndata']['status'],
                                                "upd_id" => $inserted_id
                                );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);    
                                    

                                }else{
                                
                                if ($msg['curldata']['response'] != false) 
                                {
                                    
                                    try {
                                        
                                        $array_response = json_decode($msg['curldata']['response'], true);
                                        
                                        $opr_id='00';
                                        $sp_id='00';
                                        
                                        if(isset($array_response['response']))
                                        {
                                            
                                    $array_response['response']['status_code']=@$array_response['response']['status_code'];
                                    $error_mapping=$this->_ci->Inst_model->fetch_error_code($array_response['response']['status_code'],$chkservicestat['vendor_id']); 
                                            
                                    $opr_id=isset($array_response['response']['Opr_txn_id'])?$array_response['response']['Opr_txn_id']:"00";
                                    $sp_id=isset($array_response['response']['OrderId'])?$array_response['response']['OrderId']:"00";
                                                
                                    
                                    if($error_mapping)
                                    {
                                        
                            $array_response['response']['desc']=@$array_response['response']['desc'];
                            $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$array_response['response']['desc']:$error_mapping['error_code_desc'];
                                        
                                        
                                        if(isset($array_response['response']['Status']))
                                        {
                                         
                                            if($array_response['response']['Status']=="COMPLETED")
                                            {
                                                $txnstatus='SUCCESS';
                                                
                                                $data['error'] = 0;
                                                $data['error_desc'] = null;
                                                $data['msg'] = $error_mapping['error_code_desc'];
                                                $data['txndata']=array(
                                                        "txnid"=>$transid,
                                                        "opid"=>$opr_id,
                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                        "status"=>$txnstatus,
                                                );  
                                                
                                            }
                                            else if($array_response['response']['Status']=="PENDING")
                                            {
                                                $txnstatus='PENDING';
                                                
                                                $data['error'] = 0;
                                                $data['error_desc'] = null;
                                                $data['msg'] = $error_mapping['error_code_desc'];
                                                $data['txndata']=array(
                                                        "txnid"=>$transid,
                                                        "opid"=>$opr_id,
                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                        "status"=>$txnstatus,
                                                );  
                                                
                                            }
                                            else if($array_response['response']['Status']=="FAILED")
                                            {
                                                $txnstatus='FAILED';
                                                
                                                $data['error'] = 0;
                                                $data['error_desc'] = null;
                                                $data['msg'] = $error_mapping['error_code_desc'];
                                                $data['txndata']=array(
                                                        "txnid"=>$transid,
                                                        "opid"=>$opr_id,
                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                        "status"=>$txnstatus,
                                                ); 
                                                
                                            }
                                            else{
                                                
                                                $txnstatus='PENDING';
                                                
                                                $data['error'] = 0;
                                                $data['error_desc'] = null;
                                                $data['msg']='Other Unknown Error'; 
                                                $data['txndata']=array(
                                                        "txnid"=>$transid,
                                                        "opid"=>$opr_id,
                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                        "status"=>$txnstatus,
                                                ); 
                                                $error_mapping['error_code']='OUE';
                                                $error_mapping['error_code_desc']=$data['msg'];
                                                
                                                
                                            }
                                            
                                            
                                        }else{
                                            
                                            $txnstatus='FAILED';
                                            
                                            $data['error'] = 0;
                                            $data['error_desc'] = null;
                                            $data['msg']=$error_mapping['error_code_desc'];  
                                            $data['txndata']=array(
                                                        "txnid"=>$transid,
                                                        "opid"=>$opr_id,
                                                        "datetime"=>$RetailerTxnEntry['req_dt'],
                                                        "status"=>$txnstatus,
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
                                                ); 
                                        
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                            
                                            
                                            
                                            
                                        }
                                        
                                        $update_txn_array=array(
                                                        "sp_id" => $sp_id,
                                                        "opr_ref_no" => $opr_id,
                                                        "sp_respcode" => isset($array_response['response']['status_code'])?$array_response['response']['status_code']:'00',
                                                        "sp_respdesc" => isset($array_response['response']['desc'])?$array_response['response']['desc']:'00',
                                                        "sp_response" => $msg['curldata']['response'],
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
                                                ); 
                                        
                                            $error_mapping['error_code']='OUE';
                                            $error_mapping['error_code_desc']=$data['msg'];
                                        
                                        $update_txn_array=array(
                                                        "sp_id" => '00',
                                                        "opr_ref_no" => '00',
                                                        "sp_respcode" => '',
                                                        "sp_respdesc" => 'Internal error, entered catch block '.$e->getMessage(),
                                                        "sp_response" => $msg['curldata']['response'],
                                                        "res_dt" => date('Y-m-d H:i:s'),
                                                        "ind_rcode" => $error_mapping['error_code'],
                                                        "response" => $error_mapping['error_code_desc'],
                                                        "status" => $data['txndata']['status'],
                                                        "upd_id" => $inserted_id
                                        );
                                        
                                        
                                $this->_ci->Inst_model->update_service_transaction($RetailerTxnEntry,$update_txn_array);
                                        
                                        
                                    }
                                    
                                    
                                    
                                }else{
                                    
                                    $txnstatus='PENDING';
                                    
                                    $data['error'] = 0;
                                    $data['error_desc'] = null;
                                    $data['msg']='Transaction Under Process';
                                    $data['txndata']=array(
                                                "txnid"=>$transid,
                                                "opid"=>'00',
                                                "datetime"=>$RetailerTxnEntry['req_dt'],
                                                "status"=>$txnstatus,
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
                                
                                }
                                
                            }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Internal Processing Error, try again later';
                                    $data['msg'] = null;
                            }
                                
                             }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Insufficient Account Balance';
                                    $data['msg'] = null;
                             }
                                
                            }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find user details';
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
                    
                
            }else
            {
                $data['error']=1;
                $data['error_desc']='Invalid Transaction Amount';
                $data['msg']=null;
            }
            
        }else{
            $data['error']=1;
            $data['error_desc']='This particular request is not supported, contact helpdesk';
            $data['msg']=null;
        }
        
        return $data;
    }
    
    
}
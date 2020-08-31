<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AePS extends CI_Controller {



function load(){
//print_r($_SERVER);exit;
}

    public function Transaction()
    {
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
        if($user_info)
        {

            if($user_info['is_block']==0)
            {

                 if(page_access(29))
                 { 
                     
                    $check_ifuser_aeps_config_exist=$this->Main_model->configure_aeps_for_userifnot_exist($user_info['user_id']);

                    if($check_ifuser_aeps_config_exist)
                    {
                        if(is_bool($check_ifuser_aeps_config_exist) && !isset($check_ifuser_aeps_config_exist['amount_tobesettled']))
                        {
                            $array['available_Settlement_amt']="0.00";
                        }else{
                            $array['available_Settlement_amt']=@$check_ifuser_aeps_config_exist['amount_tobesettled'];
                        }
                        
                        $this->load->view('Dashboard/templates/header');
                        $this->load->view('AePS/AePs_sidebar');
                        $this->load->view('AePS/Transaction',$array);
                        
                    }else{
                        redirect ('Dashboard');
                    }
              
                }else{

                   redirect ('Dashboard');
                }

            }else{

                $this->session->sess_destroy();
                redirect ('Login');
            }

        }else{

            $this->session->sess_destroy();
            redirect ('Login');
        }
    }
    
    public function get_settlement_balances()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                 if ($user_info['is_block'] == 0) 
                 {
                     if(page_access(29))
                     { 
                         $this->load->model('Inst_model');
                         
                         $check_if_user_Settlement_exist=$this->Inst_model->check_users_settlement_config($user_info['user_id']);
                         
                         if($check_if_user_Settlement_exist)
                         {
                             
                             $getsettlement_config=$this->Inst_model->get_settlement_balances($user_info['user_id']);
                             
                             if($getsettlement_config)
                             {
                                 
                                 $data['error']=0;
                                 $data['error_desc']=null;
                                 $data['msg']=array(
                                                "availbal"=>$getsettlement_config['availbal'],
                                                "withdrawalbal"=>$getsettlement_config['withdrawalbal']
                                                );
                                 
                             }else{
                                 $data['error']=1;
                                 $data['error_desc']='Something went wrong, try again later';
                                 $data['msg']=null;
                             }
                             
                             
                         }else{
                             $data['error'] = 1;
                             $data['error_desc'] = 'Unable to find settlement config';
                             $data['msg'] = NULL;
                         }
                         
                      }else{
                         $data['error'] = 1;
                         $data['error_desc'] = 'Unauthorised access';
                         $data['msg'] = NULL;
                     }
                     
                 } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Access Denied';
                $data['msg'] = NULL;
                $this->session->sess_destroy();
                }
                
               } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        } 
    }
    
    public function withdraw_settlement_bal()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                 if ($user_info['is_block'] == 0) 
                 {
                     if(page_access(29))
                     { 
                         $this->load->model('Inst_model');
                         
                         $check_if_user_Settlement_exist=$this->Inst_model->check_users_settlement_config($user_info['user_id']);
                         
                         if($check_if_user_Settlement_exist)
                         {
                             
                             $getsettlement_config=$this->Inst_model->get_settlement_balances($user_info['user_id']);
                             
                             if($getsettlement_config)
                             {
                                 
                                 $params=$this->input->post();
                                 
                                 $params['withdrwamt']=isset($params['withdrwamt'])?(is_string($params['withdrwamt'])?trim($params['withdrwamt']):""):"";
                                 
                                 $Regex = All_Regex();
                                 
                                 if(isset($Regex['Amount']))
                                 {
                                     if(preg_match("/".$Regex['Amount']."/",$params['withdrwamt']) && is_numeric($params['withdrwamt']) && $params['withdrwamt']>0)
                                     {
                                         
                                         if($getsettlement_config['withdrawalbal']>=$params['withdrwamt'])
                                         {
                                             
                                             $data['error']=0;
                                             $data['error_desc']=null;
                                             $data['msg']='Request Completed Successfully';
                                             
                                             $openingbal=$user_info['rupee_balance'];
                                             $closingbal=$openingbal+$params['withdrwamt'];
                                             
                                             $remarks="Aeps Settlement of Rs. ".$params['withdrwamt']." on ".date('Y-m-d H:i:s');
                                             
                                             $credit_history_array=array(
                                             "credit_txnid"=> ch_txnid(),
                                             "user_id"=>$user_info['user_id'],
                                             "bank_name"=>"N/A",
                                             "txn_type"=>"SETTLEMENT",
                                             "payment_mode"=>"WALLET",
                                             "amount"=>$params['withdrwamt'],
                                             "opening_balance"=>$openingbal,
                                             "closing_balance"=>$closingbal,
                                             "updated_on"=>date('Y-m-d H:i:s'),
                                             "reference_number"=>$remarks,
                                             "remarks"=>$remarks,
                                             "txn_code"=>admn_trnsfer_cd(),
                                             "status"=>"CREDIT",
                                             "created_on"=>date('Y-m-d H:i:s'),
                                             "created_by"=>$user_info['user_id'],
                                             "updated_by"=>$user_info['user_id'],
                                             );
                                             
                                             $settle_amount=$this->Inst_model->settle_aeps_balance_inwallet($credit_history_array);
                                             
                                             if($settle_amount)
                                             {
                                                 
                                                 $data['error']=0;
                                                 $data['error_desc']=null;
                                                 $data['msg']='Rs '.$params['withdrwamt'].' is successfully settled in your wallet.';
                                                 
                                             }else{
                                                 $data['error']=1;
                                                 $data['error_desc']='Something went wrong, try again later';
                                                 $data['msg']=null;
                                             }
                                             
                                             
                                             
                                         }else{
                                             $data['error']=1;
                                             $data['error_desc']='Insufficient Withdrawable Balance';
                                             $data['msg']=null;
                                         }
                                         
                                         
                                     }else{
                                         $data['error']=1;
                                         $data['error_desc']='Invalid Withdrawal Amount';
                                         $data['msg']=null;
                                     }
                                     
                                     
                                 }else{
                                     $data['error']=1;
                                     $data['error_desc']='Validation Failure';
                                     $data['msg']=null;
                                 }
                                 
                                 
                             }else{
                                 $data['error']=1;
                                 $data['error_desc']='Unable to find settlement balance';
                                 $data['msg']=null;
                             }
                             
                             
                         }else{
                             $data['error'] = 1;
                             $data['error_desc'] = 'Unable to find settlement config';
                             $data['msg'] = NULL;
                         }
                         
                      }else{
                         $data['error'] = 1;
                         $data['error_desc'] = 'Unauthorised access';
                         $data['msg'] = NULL;
                     }
                     
                 } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Access Denied';
                $data['msg'] = NULL;
                $this->session->sess_destroy();
                }
                
               } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        } 
    }
    
    public function aeps_trns_prcss() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                 if ($user_info['is_block'] == 0) 
                 {
                     if(page_access(29))
                     { 
                         
                         $params=array();
                         $params['fastpay_req_type']='OPENAEPSWHITELABEL';
                         
                             $chkservicestat = $this->Main_model->fetch_service_prov('CWS');
                             if($chkservicestat)
                             {
                                 
                                 if ($chkservicestat['is_down'] == 0) 
                                 {
                                     
                                     $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                                
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) 
                                            {

                                                $this->load->library($chkservicestat['vendor_library']);
                                                
                                                if (method_exists($this->{$chkservicestat['vendor_library']}, 'start_aeps_action'))
                                                    {   

                                                        $result = $this->{$chkservicestat['vendor_library']}->start_aeps_action($user_info,$params,$chkservicestat);
                                                        $data = $result;
                                                   
                                                    }else{

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Failed to process request";
                                                        $data['msg'] = null;
                                            
                                                    }

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Failed to process request";
                                                $data['msg'] = null;
                                            }
                                     
                                 }else{
                                     $data['error'] = 1;
                                     $data['error_desc'] = "Service Down, Try again later";
                                     $data['msg'] = null;
                                 }
                                 
                             }else{
                                 $data['error'] = 1;
                                 $data['error_desc'] = "Invalid Service";
                                 $data['msg'] = null;
                             }
                             
                         
                         
                     }else{
                         $data['error'] = 1;
                         $data['error_desc'] = 'Unauthorised access';
                         $data['msg'] = NULL;
                     }
                     
                 } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Access Denied';
                $data['msg'] = NULL;
                $this->session->sess_destroy();
                }
                
               } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        } 
    }
    
    
    function AepsBalProcess()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Headers: *');
        header("Content-Type: application/json; charset=UTF-8");
        
        $requestdata = file_get_contents('php://input');
        $this->output->set_status_header(200);
        
        $requesturl = base_url() . $this->uri->uri_string();
        $requesturl = $_SERVER['QUERY_STRING'] ? $requesturl . "?" . $_SERVER['QUERY_STRING'] : $requesturl;
        
        $this->load->model('Inst_model');
        
        $log_entry=array(
        "url"=>$requesturl,
        "callback"=>$requestdata,
        "req_type"=>"CHECK WALLET BALANCE",
        "ip"=>ip_address(),
        "datetime"=>date('Y-m-d H:i:s')
        );
        
        $logged=$this->Inst_model->insert_aeps_log($log_entry);
        
        if($logged)
        {
            $log_id=$this->db->insert_id();
            
            $verify_request = verify_whitelable_autheticity($requestdata);
            
            if(!$verify_request)
            {
                
                try{
                    
                    $requestdata_array = json_decode($requestdata, true);
                    
                    $chkservicestat = $this->Main_model->fetch_service_prov('CWS');
                             if($chkservicestat)
                             {
                                 
                                 if ($chkservicestat['is_down'] == 0) 
                                 {
                                     
                                     $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                                
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) 
                                            {

                                                $this->load->library($chkservicestat['vendor_library']);
                                                
                                                if (method_exists($this->{$chkservicestat['vendor_library']}, 'process_aeps_callbacks'))
                                                    {   

                                    $requestdata_array['fastpay_req_type']='AEPSWALLETBALANCE';                
                                                    
                                    $request = $this->{$chkservicestat['vendor_library']}->process_aeps_callbacks($requestdata_array,$chkservicestat);
                                    
                                                   
                                                    }else{

                                                        $request['response_code'] = 'ERR';
                                                        $request['response_msg'] = 'Failed to process request';
                                                        $request['transactions'] = array();
                                            
                                                    }

                                            } else {

                                                 $request['response_code'] = 'ERR';
                                                 $request['response_msg'] = 'Failed to process request';
                                                 $request['transactions'] = array();
                                                
                                            }
                                     
                                 }else{
                                     
                                     $request['response_code'] = 'ERR';
                                     $request['response_msg'] = 'Service Down, Try again later';
                                     $request['transactions'] = array();
                                     
                                 }
                                 
                             }else{
                                 
                                    $request['response_code'] = 'ERR';
                                    $request['response_msg'] = 'Invalid Service';
                                    $request['transactions'] = array();
                                 
                             }
                
                    
                }
                catch(Exception $e)
                {
                    $request['response_code'] = 'ERR';
                    $request['response_msg'] = 'Exception Occured';
                    $request['transactions'] = array();
                }
                
                
                
                
            }else{
                $request['response_code'] = 'ERR';
                $request['response_msg'] = 'Authentication Failed';
                $request['transactions'] = array();
            }
            
            $log_update_array=array(
                'reply_sent'=>json_encode($request),
                'reply_datetime'=>date('Y-m-d H:i:s')
            );
            
            $this->Inst_model->update_aeps_log($log_update_array,$log_id);
            
        }else{
            $request['response_code'] = 'ERR';
            $request['response_msg'] = 'Something went wrong, try again later';
            $request['transactions'] = array();
        }
        
        
        echo json_encode($request);
        
        
    }
    
    
    function AepsTrnsProcess()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Headers: *');
        header("Content-Type: application/json; charset=UTF-8");
        
        $requestdata = file_get_contents('php://input');
        $this->output->set_status_header(200);
        
        $this->load->model('Inst_model');
        
        $requesturl = base_url() . $this->uri->uri_string();
        $requesturl = $_SERVER['QUERY_STRING'] ? $requesturl . "?" . $_SERVER['QUERY_STRING'] : $requesturl;
        
        
        $log_entry=array(
        "url"=>$requesturl,
        "callback"=>$requestdata,
        "req_type"=>"AEPS SERVICE TXN ACTION",
        "ip"=>ip_address(),
        "datetime"=>date('Y-m-d H:i:s')
        );
        
        $logged=$this->Inst_model->insert_aeps_log($log_entry);
        
        if($logged)
        {
            $log_id=$this->db->insert_id();
            
            $verify_request = verify_whitelable_autheticity($requestdata);
            
            if(!$verify_request)
            {
                
                try{
                    
                    $requestdata_array = json_decode($requestdata, true);
                    $srvc = array('WAP' => 'CWS', 'BAP' => 'BCS', 'SAP'=>'MST');
                    
                    $requestdata_array['transactions'][0]['sp_key']=@$requestdata_array['transactions'][0]['sp_key'];
                    
                    if(in_array($requestdata_array['transactions'][0]['sp_key'],array_keys($srvc)))
                    {
                        
                    $chkservicestat = $this->Main_model->fetch_service_prov($srvc[$requestdata_array['transactions'][0]['sp_key']]);
                             if($chkservicestat)
                             {
                                 
                                 if ($chkservicestat['is_down'] == 0) 
                                 {
                                     
                                     $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                                
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) 
                                            {

                                                $this->load->library($chkservicestat['vendor_library']);
                                                
                                                if (method_exists($this->{$chkservicestat['vendor_library']}, 'process_aeps_callbacks'))
                                                    {   

                                    $requestdata_array['fastpay_req_type']='AEPSPROCESSTRANSACTION';                
                                                    
                                    $request = $this->{$chkservicestat['vendor_library']}->process_aeps_callbacks($requestdata_array,$chkservicestat);
                                    
                                                   
                                                    }else{

                                                        $request['response_code'] = 'ERR';
                                                        $request['response_msg'] = 'Failed to process request';
                                                        $request['transactions'] = array();
                                            
                                                    }

                                            } else {

                                                 $request['response_code'] = 'ERR';
                                                 $request['response_msg'] = 'Failed to process request';
                                                 $request['transactions'] = array();
                                                
                                            }
                                     
                                 }else{
                                     
                                     $request['response_code'] = 'ERR';
                                     $request['response_msg'] = 'Service Down, Try again later';
                                     $request['transactions'] = array();
                                     
                                 }
                                 
                             }else{
                                 
                                    $request['response_code'] = 'ERR';
                                    $request['response_msg'] = 'Invalid Service';
                                    $request['transactions'] = array();
                                 
                             }
                
                    }else{
                        $request['response_code'] = 'ERR';
                        $request['response_msg'] = 'Invalid Service';
                        $request['transactions'] = array();
                        
                    }
                }
                catch(Exception $e)
                {
                    $request['response_code'] = 'ERR';
                    $request['response_msg'] = 'Exception Occured';
                    $request['transactions'] = array();
                }
                
                
                
                
            }else{
                $request['response_code'] = 'ERR';
                $request['response_msg'] = 'Authentication Failed';
                $request['transactions'] = array();
            }
            
            $log_update_array=array(
                'reply_sent'=>json_encode($request),
                'reply_datetime'=>date('Y-m-d H:i:s')
            );
            
            $this->Inst_model->update_aeps_log($log_update_array,$log_id);
            
        }else{
            $request['response_code'] = 'ERR';
            $request['response_msg'] = 'Something went wrong, try again later';
            $request['transactions'] = array();
        }
        
        
        echo json_encode($request);
        
        
    }
    
    function AepsConfirmProcess()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Headers: *');
        header("Content-Type: application/json; charset=UTF-8");
        
        $requestdata = file_get_contents('php://input');
        $this->output->set_status_header(200);
        
        $this->load->model('Inst_model');
        
        $requesturl = base_url() . $this->uri->uri_string();
        $requesturl = $_SERVER['QUERY_STRING'] ? $requesturl . "?" . $_SERVER['QUERY_STRING'] : $requesturl;
        
        
        $log_entry=array(
        "url"=>$requesturl,
        "callback"=>$requestdata,
        "req_type"=>"CONFIRM AEPS SERVICE ACTION",
        "ip"=>ip_address(),
        "datetime"=>date('Y-m-d H:i:s')
        );
        
        $logged=$this->Inst_model->insert_aeps_log($log_entry);
        
        if($logged)
        {
            $log_id=$this->db->insert_id();
            
            $verify_request = verify_whitelable_autheticity($requestdata);
            
            if(!$verify_request)
            {
                
                try{
                    
                    $requestdata_array = json_decode($requestdata, true);
                    $srvc = array('WAP' => 'CWS', 'BAP' => 'BCS', 'SAP'=>'MST');
                    
                    $requestdata_array['transactions'][0]['sp_key']=@$requestdata_array['transactions'][0]['sp_key'];
                    
                    if(in_array($requestdata_array['transactions'][0]['sp_key'],array_keys($srvc)))
                    {
                        
                    $chkservicestat = $this->Main_model->fetch_service_prov($srvc[$requestdata_array['transactions'][0]['sp_key']]);
                             if($chkservicestat)
                             {
                                 
                                 if ($chkservicestat['is_down'] == 0) 
                                 {
                                     
                                     $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                                
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) 
                                            {

                                                $this->load->library($chkservicestat['vendor_library']);
                                                
                                                if (method_exists($this->{$chkservicestat['vendor_library']}, 'process_aeps_callbacks'))
                                                    {   

                                    $requestdata_array['fastpay_req_type']='AEPSCONFIRMSERVICEACTION';                
                                                    
                                    $request = $this->{$chkservicestat['vendor_library']}->process_aeps_callbacks($requestdata_array,$chkservicestat);
                                    
                                                   
                                                    }else{

                                                        $request['response_code'] = 'ERR';
                                                        $request['response_msg'] = 'Failed to process request';
                                                        $request['transactions'] = array();
                                            
                                                    }

                                            } else {

                                                 $request['response_code'] = 'ERR';
                                                 $request['response_msg'] = 'Failed to process request';
                                                 $request['transactions'] = array();
                                                
                                            }
                                     
                                 }else{
                                     
                                     $request['response_code'] = 'ERR';
                                     $request['response_msg'] = 'Service Down, Try again later';
                                     $request['transactions'] = array();
                                     
                                 }
                                 
                             }else{
                                 
                                    $request['response_code'] = 'ERR';
                                    $request['response_msg'] = 'Invalid Service';
                                    $request['transactions'] = array();
                                 
                             }
                
                    }else{
                        $request['response_code'] = 'ERR';
                        $request['response_msg'] = 'Invalid Service';
                        $request['transactions'] = array();
                        
                    }
                }
                catch(Exception $e)
                {
                    $request['response_code'] = 'ERR';
                    $request['response_msg'] = 'Exception Occured';
                    $request['transactions'] = array();
                }
                
                
                
                
            }else{
                $request['response_code'] = 'ERR';
                $request['response_msg'] = 'Authentication Failed';
                $request['transactions'] = array();
            }
            
            $log_update_array=array(
                'reply_sent'=>json_encode($request),
                'reply_datetime'=>date('Y-m-d H:i:s')
            );
            
            $this->Inst_model->update_aeps_log($log_update_array,$log_id);
            
        }else{
            $request['response_code'] = 'ERR';
            $request['response_msg'] = 'Something went wrong, try again later';
            $request['transactions'] = array();
        }
        
        
        echo json_encode($request);
        
        
    }
    
    
    

}
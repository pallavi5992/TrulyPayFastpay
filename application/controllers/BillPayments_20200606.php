<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BillPayments extends CI_Controller {

    public function index(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){
      
    if($user_info['role_id']==1 || $user_info['role_id']==2 || $user_info['role_id']==3) {

        redirect ('BillPayments/TransactionHistory');

    }else{    

        redirect ('BillPayments/PostPaid');

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

    public function PostPaid(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(1)){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==4) {
    //$a=array('operator_typ'=>'POSTPAID','heading'=>'Mobile Payment-PostPaid'); 
    $outletCheck['a']=array('operator_typ'=>'POSTPAID','heading'=>'Mobile Payment-PostPaid'); 
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/billpayments',$outletCheck);            
    // $this->load->view('Dashboard/templates/footer'); 
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    } 
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    //  }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    
    }


    public function GasPayment(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(1)){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==4) {
    //$a=array('operator_typ'=>'GAS','heading'=>'Gas Payment');  
    $outletCheck['a']=array('operator_typ'=>'GAS','heading'=>'Gas Payment');  
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/billpayments',$outletCheck);            
    // $this->load->view('Dashboard/templates/footer'); 
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    } 
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    //  }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    
    }

    public function LandlinePayment(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(4)){
    if($user_info['is_block']==0){
    if($user_info['role_id']==4) {
    //$a=array('operator_typ'=>'LANDLINE','heading'=>'Landline Payment'); 
    $outletCheck['a']=array('operator_typ'=>'LANDLINE','heading'=>'Landline Payment'); 
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/billpayments',$outletCheck);
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    } 
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    // }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    
    }

    public function ElectricityPayment(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(4)){
    if($user_info['is_block']==0){
    if($user_info['role_id']==4) {
              
                $this->load->view('Dashboard/templates/header');
                $this->load->view('BillPayments/billpayments_sidebar');
                $check_outlet_id = $this->Main_model->check_outlet_id($user_info['user_id']);
                if ($check_outlet_id){
                    $outletCheck['outlet'] = $check_outlet_id;
                    if ($check_outlet_id['kyc_apibox'] == 'ACTIVE') {

                    	$outletCheck['a']=array('operator_typ'=>'ELECTRICITY','heading'=>'Electricity Payment');  
                        $this->load->view('BillPayments/billpayments', $outletCheck);

                    } elseif ($check_outlet_id['kyc_apibox'] == 'PENDING') {
                    	
                    	$outletCheck['a']=array('operator_typ'=>'ELECTRICITY','heading'=>'Electricity Payment');  
                        $this->load->view('BillPayments/kyc_document',$outletCheck);

                    } else {
                        $this->load->view('BillPayments/outlet_form');
                    }

                } else {

                    $this->load->view('BillPayments/outlet_form');
                }
               

                //$this->load->view('BillPayments/billpayments',$a);

    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    } 
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    // }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    
    }

    public function WaterPayment(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(4)){
    if($user_info['is_block']==0){
    if($user_info['role_id']==4) {
    // $a=array('operator_typ'=>'WATER','heading'=>'Water Payment'); 
    $outletCheck['a']=array('operator_typ'=>'WATER','heading'=>'Water Payment');   
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/billpayments',$outletCheck);

     }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    // }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    
    }

    public function Insurance(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(4)){
    if($user_info['is_block']==0){
    if($user_info['role_id']==4) {
   // $a=array('operator_typ'=>'INSURANCE','heading'=>'Insurance Payment');  
    $outletCheck['a']=array('operator_typ'=>'INSURANCE','heading'=>'Insurance Payment');   
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/billpayments',$outletCheck);

     }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    // }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }   
    }
	
	 public function Broadband(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(4)){
    if($user_info['is_block']==0){
    if($user_info['role_id']==4) {

    $outletCheck['a']=array('operator_typ'=>'BROADBAND','heading'=>'Broadband Payment');   
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/billpayments',$outletCheck);

     }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    // }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }   
    }
	 public function Fastag(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(4)){
    if($user_info['is_block']==0){
    if($user_info['role_id']==4) {
  
    $outletCheck['a']=array('operator_typ'=>'FASTAG','heading'=>'Fastag Payment');   
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/billpayments',$outletCheck);

     }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    // }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }   
    }

    public function TransactionHistory(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    //if(page_access(4)){
    if($user_info['is_block']==0){
    $this->load->view('Dashboard/templates/header');
    $this->load->view('BillPayments/billpayments_sidebar');
    $this->load->view('BillPayments/transactionhistory');
   

    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    // }else{
    //      redirect('Dashboard');
    // }
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
    
    }
	
       public function get_rchrg_srvc_prvdr(){
        $type = $this->input->post('type');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                        $type = trim($type);
                        if ($type != '') {
                            $servcs_array = $this->Main_model->get_servs($type);
                            if ($servcs_array) {  
                               
                                  foreach ($servcs_array as $v => $k) {
                                    $txn_params = $this->Main_model->servc_txn_params($k['service_id']);  
                                    
                                    $servcs_array[$v]['Params'] = $txn_params;
									

                                }
                                  
                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = $servcs_array ? $servcs_array : [];

                            } else {
                                
                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = array();
                            }

                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid service type';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $this->session->sess_destroy();
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
                    $data['msg'] = NULL;
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

    public function FetchBbpsBillPayment(){
        $spkey=$this->input->post('oprKey');
        $servc_typ=$this->input->post('servc_typ');
        $params=$this->input->post('parameters');
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                        $this->load->model('Inst_model');        
                        $chkservicestat = $this->Inst_model->fetch_service_prov($spkey);
                        if ($chkservicestat){
                            //print_r($chkservicestat);exit;
                            // if(isset($servc_typ)){
                                 $txn_paramtrs = $this->Main_model->servc_txn_params($chkservicestat['service_id']);

                                 curlRequertLogs($txn_paramtrs, 'txn_paramtrs', 'FetchBbpsBillPayment');
                                 curlRequertLogs($params, 'params', 'FetchBbpsBillPayment');
                                if($txn_paramtrs){

								
                                    $filelds=array();

                                    foreach ($params as $key => $value) {
                                        
                                        if (array_key_exists($key, $txn_paramtrs)) {
									
                                        $filelds[] = array('name' => $txn_paramtrs[$key]['param_name'], 'value' => $value,'order'=>$txn_paramtrs[$key]['order'],'id'=>$key);

                                        } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try Again';
                                            $data['msg'] = null;
                                            return json_encode($data);
                                            exit;

                                        }
                                    }

                                  

                                if ($chkservicestat['is_down'] == 0) {

                                    if ($chkservicestat['gateway_down'] == 0) {

                                        if ($chkservicestat['type'] == $servc_typ) {
                                            $get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                            if($get_agentcode){
                                            if ($chkservicestat['Amnt_by_routing'] == 1) {
                                                $this->load->model('Inst_model');
                                                $chck_amnt_rng = $this->Inst_model->check_servc_amnt_rng($chkservicestat['service_id'],  $param['amount']);
                                                 if($chck_amnt_rng){
                                                 if ($chck_amnt_rng['gateway_down'] == 0) {
                                                $chck_amnt_rng['vendor_library'] = trim($chck_amnt_rng['vendor_library']);
                                                  $chkservicestat['vendor_id']=$chck_amnt_rng['vendor_id'];
                                                 //print_r($chkservicestat['vendor_id']);exit;  
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chck_amnt_rng['vendor_library']) . ".php")) {

                                                $this->load->library($chck_amnt_rng['vendor_library']);
                                               
                                                $chkservicestat['vendor_id']=$chck_amnt_rng['vendor_id'];

                                                $result = $this->{$chck_amnt_rng['vendor_library']}->bbps_bill_fetch_request($user_info, $user_info['plan_id'], $filelds, $chkservicestat,$params,$get_agentcode['agent_code']);

                                              

                                                $data = $result;
												
												
                                                $data['date'] = date('Y-m-d H:i:s');

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Failed to process request";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';
                                            }
                                            } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                        $data['date'] = date('Y-m-d H:i:s');
                                        $data['status'] = 'FAILED';
                                    }
                                        }else{
                                                 $data['error'] = 1;
                                                $data['error_desc'] = "Failed to process request";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';

                                        }

                                             }else{

                                            if ($chkservicestat['gateway_down'] == 0) {

                                                 $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                                                $this->load->library($chkservicestat['vendor_library']);
                                               
                                                $result = $this->{$chkservicestat['vendor_library']}->bbps_bill_fetch_request($user_info, $user_info['plan_id'], $filelds, $chkservicestat,$params,$get_agentcode['agent_code']);

                                              

                                                  $data = $result;
                                                 // $data['date'] = date('Y-m-d H:i:s');
                                                  if($data){
                  
                                                  
                                                            $data['error'] = 0;  
                                                            $data['error_desc'] = null;
                                                            $data['user_id'] =$user_info['user_id'];
                                                            $data['date'] = date('Y-m-d H:i:s');
                                                            $data['response'] = array(
                                                                "statuscode"=> "TXN",
                                                                  "status"=> "Transaction Successful",
                                                                  "data"=> array(
                                                                    "dueamount"=> "6818.92",
                                                                    "duedate"=> "01-01-9999",
                                                                    "customername"=> "SHRI UDAY SINGH",
                                                                    "billnumber"=> "678477913662",
                                                                    "billdate"=> "01-01-0001",
                                                                    "billperiod"=> "NA",
                                                                    "billdetails"=> [],
                                                                    "customerparamsdetails"=> [    
                                                                      
                                                                        "Name"=> "K No",     
                                                                        "Value"=> "6784760000"
                                                                      
                                                                    ],
                                                                    "additionaldetails"=> [],
                                                                    "reference_id"=> 91
                                                                  )
                                                            

                                                               );

                                                              $data['agentid'] = 'Z200107113435XIA';
                                                              $data['outletid'] = '30598';


                                                }

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Failed to process request";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';
                                            }

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Service Provider Down, Try again later";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';
                                            }

                                             }
                                            // $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                            // if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                                            //     $this->load->library($chkservicestat['vendor_library']);

                                            //     $result = $this->{$chkservicestat['vendor_library']}->bbps_bill_fetch_request($user_info, $user_info['plan_id'],$filelds,$chkservicestat,$params);
                                            //     //$result = $this->{$chkservicestat['vendor_library']}->bill_fetch_request();
                                               
                                            //     $data = $result;
                                             
                                            //     $data['date'] = date('Y-m-d H:i:s');
                                               
                                            // } else {

                                            //     $data['error'] = 1;
                                            //     $data['error_desc'] = "Failed to process request";
                                            //     $data['msg'] = null;
                                            //     $data['date'] = date('Y-m-d H:i:s');
                                            //     $data['status'] = 'FAILED';

                                            // }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            $data['date'] = date('Y-m-d H:i:s');
                                            $data['status'] = 'FAILED';
                                        }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Invalid Service Type";
                                            $data['msg'] = null;
                                            $data['date'] = date('Y-m-d H:i:s');
                                            $data['status'] = 'FAILED';
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                        $data['date'] = date('Y-m-d H:i:s');
                                        $data['status'] = 'FAILED';

                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                    $data['date'] = date('Y-m-d H:i:s');
                                }

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid Request parameter";
                                    $data['msg'] = null;
                                    $data['date'] = date('Y-m-d H:i:s');
                                    $data['status'] = 'FAILED';
                                }

                            // } else {
                            //     $data['error'] = 1;
                            //     $data['error_desc'] = "Incomplete request parameter";
                            //     $data['msg'] = null;
                            //     $data['date'] = date('Y-m-d H:i:s');
                            //     $data['status'] = 'FAILED';
                            // }


                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid Service";
                            $data['msg'] = null;
                            $data['date'] = date('Y-m-d H:i:s');
                            $data['status'] = 'FAILED';
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['date'] = date('Y-m-d H:i:s');
                        $this->session->sess_destroy();
                        $data['status'] = 'FAILED';
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
                    $data['msg'] = NULL;
                    $data['date'] = date('Y-m-d H:i:s');
                    $data['status'] = 'FAILED';
                }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['date'] = date('Y-m-d H:i:s');
                $this->session->sess_destroy();
                $data['status'] = 'FAILED';
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        } 
    }
    

    public function init_billpayment(){
        $parameters=$this->input->post('data');

          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model');        
                        $chkservicestat = $this->Inst_model->fetch_service_prov($parameters['spkey']);
                        if ($chkservicestat) {
                            // if(isset($servc_typ)){
                                 $txn_paramtrs = $this->Main_model->servc_txn_params($chkservicestat['service_id']);
                                if($txn_paramtrs){
									//print_r($txn_paramtrs);
									
								/* 	array('param_name' => 'Customer Mobile Number',
									'value' => $user_info['mobile'],
									'order' => 2,
									'id' => 'custm_mob');  */

									
									/* $txn_paramtrs=array(
										'custm_mob' => array
											(
												'param_name' => 'Customer Mobile Number',
												'order' => 2,
												//'value' => $user_info['mobile'],
												
											)

									); */
								
                                    $filelds=array();
                                    foreach ($parameters['Details'] as $key => $value) {
												/* print_r($key);echo"</br>";
												print_r($value); */
										//if($key=='custm_mob'){
                                        if (array_key_exists($key, $txn_paramtrs)) {
	
                                     
                                            $filelds[] = array('name' => $txn_paramtrs[$key]['param_name'], 'value' => $value,'order'=>$txn_paramtrs[$key]['order'],'id'=>$key); 

                                        } else {
											
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Internal Processing Error, Try Again';
                                            $data['msg'] = null;
                                            return json_encode($data);
                                            exit;

                                        }
										
									//}
                                    }

							
                                if ($chkservicestat['is_down'] == 0) {
                                    if ($chkservicestat['gateway_down'] == 0) {
                                        if ($chkservicestat['type'] == $parameters['servc_typ']) {

                                        	 $get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                            if($get_agentcode){
                                            if ($chkservicestat['Amnt_by_routing'] == 1) {
                                                $this->load->model('Inst_model');
                                                $chck_amnt_rng = $this->Inst_model->check_servc_amnt_rng($chkservicestat['service_id'],  $param['amount']);
                                                 if($chck_amnt_rng){
                                                 if ($chck_amnt_rng['gateway_down'] == 0) {
                                                $chck_amnt_rng['vendor_library'] = trim($chck_amnt_rng['vendor_library']);
                                                  $chkservicestat['vendor_id']=$chck_amnt_rng['vendor_id'];
                                                 //print_r($chkservicestat['vendor_id']);exit;  
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chck_amnt_rng['vendor_library']) . ".php")) {

                                                $this->load->library($chck_amnt_rng['vendor_library']);
                                               
                                                $chkservicestat['vendor_id']=$chck_amnt_rng['vendor_id'];

                                                $result = $this->{$chck_amnt_rng['vendor_library']}->bbps_bill_payment_request($user_info, $user_info['plan_id'],$filelds,$chkservicestat,$parameters,$get_agentcode['agent_code']);

                                              

                                                $data = $result;
                                                $data['date'] = date('Y-m-d H:i:s');

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Failed to process request";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';
                                            }
                                            } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                        $data['date'] = date('Y-m-d H:i:s');
                                        $data['status'] = 'FAILED';
                                    }
                                        }else{
                                                 $data['error'] = 1;
                                                $data['error_desc'] = "Failed to process request";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';

                                        }

                                             }else{

                                            if ($chkservicestat['gateway_down'] == 0) {

                                                 $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                                                $this->load->library($chkservicestat['vendor_library']);
                                               
                                              
                                                  $result = $this->{$chkservicestat['vendor_library']}->bbps_bill_payment_request($user_info, $user_info['plan_id'],$filelds,$chkservicestat,$parameters,$get_agentcode['agent_code']);

                                              

                                                  $data = $result;
                                                  $data['date'] = date('Y-m-d H:i:s');
                                                

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Failed to process request";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';
                                            }

                                            } else {

                                                $data['error'] = 1;
                                                $data['error_desc'] = "Service Provider Down, Try again later";
                                                $data['msg'] = null;
                                                $data['date'] = date('Y-m-d H:i:s');
                                                $data['status'] = 'FAILED';
                                            }

                                             }
                                           
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            $data['date'] = date('Y-m-d H:i:s');
                                            $data['status'] = 'FAILED';
                                        }
                                            // $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                            // if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                                            //     $this->load->library($chkservicestat['vendor_library']);
                                            //    // if($chkservicestat['bill_fetch']==1 && $chkservicestat['validate_required']==0){
                                            //     $result = $this->{$chkservicestat['vendor_library']}->bbps_bill_payment_request($user_info, $user_info['plan_id'],$filelds,$chkservicestat,$parameters);
                                              
                                               
                                            //     $data = $result;
                                              
                                            //     $data['date'] = date('Y-m-d H:i:s');
                                            // // }else{

                                            // // $result = $this->{$chkservicestat['vendor_library']}->bbps_bill_quick_payment_request($user_info, $user_info['plan_id'],$filelds,$chkservicestat,$parameters);
                                              
                                               
                                            // //     $data = $result;
                                             
                                            // //     $data['date'] = date('Y-m-d H:i:s');

                                            // // }
                                               
                                            // } else {

                                            //     $data['error'] = 1;
                                            //     $data['error_desc'] = "Failed to process request";
                                            //     $data['msg'] = null;
                                            //     $data['date'] = date('Y-m-d H:i:s');
                                            //     $data['status'] = 'FAILED';

                                            // }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Invalid Service Type";
                                            $data['msg'] = null;
                                            $data['date'] = date('Y-m-d H:i:s');
                                            $data['status'] = 'FAILED';
                                        }
                                    } else {

                                        $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                        $data['date'] = date('Y-m-d H:i:s');
                                        $data['status'] = 'FAILED';

                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                    $data['date'] = date('Y-m-d H:i:s');
                                }

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = "Invalid Request parameter";
                                    $data['msg'] = null;
                                    $data['date'] = date('Y-m-d H:i:s');
                                    $data['status'] = 'FAILED';
                                }

                            // } else {
                            //     $data['error'] = 1;
                            //     $data['error_desc'] = "Incomplete request parameter";
                            //     $data['msg'] = null;
                            //     $data['date'] = date('Y-m-d H:i:s');
                            //     $data['status'] = 'FAILED';
                            // }


                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid Service";
                            $data['msg'] = null;
                            $data['date'] = date('Y-m-d H:i:s');
                            $data['status'] = 'FAILED';
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['date'] = date('Y-m-d H:i:s');
                        $this->session->sess_destroy();
                        $data['status'] = 'FAILED';
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
                    $data['msg'] = NULL;
                    $data['date'] = date('Y-m-d H:i:s');
                    $data['status'] = 'FAILED';
                }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['date'] = date('Y-m-d H:i:s');
                $this->session->sess_destroy();
                $data['status'] = 'FAILED';
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }
	
	 public function BillPayment_Txn_Re() {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
          
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                    $from = $this->input->post('from');
                        $to = $this->input->post('to');
                        $cell = $this->input->post('cell');
                        if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
                            $search = $this->input->post('search');
                            $length = $this->input->post('length');
                            $email = $this->session->userdata('email');
                            $this->load->model('Inst_model');
                            if ($user_info) {

                                $result = array();
                                $user_data = $this->Inst_model->Datatable_fetch_BillPaymeny_myorder($user_info['user_id'], $from, $to, $cell);
                                if ($user_data) {

                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_BillPaymeny_myorder($user_info['user_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_BillPaymeny_myorder($user_info['user_id'], $from, $to, $cell),
                                        "data" => $user_data
                                    );
                                } else {
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_BillPaymeny_myorder($user_info['user_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_BillPaymeny_myorder($user_info['user_id'], $from, $to, $cell),
                                        "data" => array()
                                    );
                                }
                            } else {
                                $result['data'] = array();
                            }
                        } else {
                            $result['error'] = 2;
                            $result['error_desc'] = 'Access denied';
                            $result['msg'] = NULL;
                        }
                         
                    } else {
                        $result['error'] = 2;
                        $result['error_desc'] = 'Access denied';
                        $result['msg'] = NULL;
                        $this->session->sess_destroy();
                    }
               
            } else {
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = NULL;
                $this->session->sess_destroy();
            }
            echo json_encode($result);
        } else {
            redirect('Dashboard');
        }
		
    }

		 public function Billpayment_Txn_Allorder() {  
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
          
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
					 $from = $this->input->post('from');
                        $to = $this->input->post('to');
                        $cell = $this->input->post('cell');
                        if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
                            $search = $this->input->post('search');
                            $length = $this->input->post('length');
                            $email = $this->session->userdata('email');
                            $this->load->model('Inst_model');
                            if ($user_info) {

                                $result = array();

                                $treeFetch = $this->Inst_model->GetSubAdminDistChild($user_info['user_id']);
								//print_r($treeFetch);exit;

                                $user_data = $this->Inst_model->Datatable_fetch_BillPaymeny_allorder($user_info['user_id'], $user_info['user_id'], $from, $to, $cell, $treeFetch);
								//print_r($user_data);exit;
                                if ($user_data) {

                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_BillPaymeny_allorder($user_info['user_id'], $user_info['user_id'], $from, $to, $treeFetch),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_BillPaymeny_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch),
                                        "data" => $user_data
                                    );
                                } else {
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_BillPaymeny_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $treeFetch),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_BillPaymeny_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch),
                                        "data" => array()
                                    );
                                }
                            } else {
                                $result['data'] = array();
                            }
                        } else {
                            $result['error'] = 2;
                            $result['error_desc'] = 'Access denied';
                            $result['msg'] = NULL;
                        }
                    } else {
                        $result['error'] = 2;
                        $result['error_desc'] = 'Access denied';
                        $result['msg'] = NULL;
                        $this->session->sess_destroy();
                    }
               
            } else {
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = NULL;
                $this->session->sess_destroy();
            }
            echo json_encode($result);
        } else {
            redirect('Dashboard');
        }
		
    }
	

}

?>
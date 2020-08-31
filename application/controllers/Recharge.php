<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge extends CI_Controller {

	public function index(){
	$e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
	if($user_info){

	if($user_info['is_block']==0){	

	if(page_access(9))
        {

        redirect ('Recharge/Prepaid');
		

	}else{

		redirect ('Recharge/TransactionHistory');

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

	public function Prepaid(){
        
	$e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
	if($user_info){
 
	if($user_info['is_block']==0)
    {	
        
        if(page_access(9))
        {
        
    $this->load->view('Dashboard/templates/header');
	$this->load->view('Recharge/recharge');
    $this->load->view('Recharge/mobile-recharge');
    // $this->load->view('Dashboard/templates/footer'); 
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

	public function DTHRecharge(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){ 
    if($user_info['is_block']==0){
        
        
        if(page_access(10))
        {
        
    $this->load->view('Dashboard/templates/header');
    $this->load->view('Recharge/recharge');
    $this->load->view('Recharge/dth-recharge');
    $this->load->view('Dashboard/templates/footer');
            
        }else{
            redirect ('Dashbaord');
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

    public function TransactionHistory(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
   
    if($user_info['is_block']==0){
        
        if(page_access(16))
        {
        
    $this->load->view('Dashboard/templates/header');
    $this->load->view('Recharge/recharge');
    $this->load->view('Recharge/transactionhistory');
    $this->load->view('Dashboard/templates/footer');

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
	
	  public function getopr_bymob(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) 
            {
                    if ($user_info['is_block'] == 0) 
                    {
                        
                         $msisdn = $this->input->post('msisdn');
                         $msisdn=substr($msisdn,0,4);
                        
                        if (strlen($msisdn) == 4) 
                        {
                            $user_data = $this->Main_model->fetch_opr_bymob($msisdn);
                            if ($user_data) 
                            {
                                
                            $user_data['opcode'] = trim($user_data['opcode']);
                            $user_data['stcode'] = trim($user_data['stcode']); 
                                
                            $code = '';
                                
                           if ($user_data['opcode'] == 'AT') 
                           { 
                               $code = 'ATP';
                           } 
                           else if ($user_data['opcode'] == 'CG') 
                           { 
                               $code = 'BVP'; 
                           }
                           else if ($user_data['opcode'] == 'ID') 
                           {    
                              $code = 'IDP'; 
                           } 
                           else if ($user_data['opcode'] == 'DP') 
                           {

                                if ($user_data['stcode'] == 'DL') 
                                {
                                        $code = 'MMP';
                                } elseif ($user_data['stcode'] == 'MU') 
                                {
                                        $code = 'MSP';
                                } 
                              
                           } 
                           else if ($user_data['opcode'] == 'VF') 
                           {
                                $code = 'VFP';
                           } 
                           else if ($user_data['opcode'] == 'JO') 
                           {
                                $code = 'RJP';
                           }
                                        //print_r($code);exit;
                                if ($code!= '') 
                                {

                                    $data['error'] = 0;
                                    $data['error_desc'] = null;
                                    $data['msg'] = $code;

                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find operator';
                                    $data['msg'] = null;
                                }


                            } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find operator';
                                    $data['msg'] = null;
                            } 
                            
                        } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Invalid Number';
                        $data['msg'] = null;
                        }

                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
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

    public function get_rchrg_srvc_prvdr(){
               $type = $this->input->post('type');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        $type = trim($type);
                        if ($type != '') {
                            $servcs_array = $this->Main_model->get_servs($type);
                            if ($servcs_array) {
                                //print_r($servcs_array);exit;

                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = $servcs_array;
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

    public function init_rchrge(){
       $param = $this->input->post('data');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               if(page_access(9)) 
               {
                    if ($user_info['is_block'] == 0) 
                    {
                        $chkservicestat = $this->Main_model->fetch_service_prov($param['spkey']);
                        if ($chkservicestat) 
                        {
                            if (isset($param['account']) && isset($param['amount'])) 
                            {
                                if ($chkservicestat['is_down'] == 0) {
                                    
                                        if ($chkservicestat['type'] == 'PREPAID') 
                                        {
                                             if ($chkservicestat['Amnt_by_routing'] == 1) 
                                             {
                                                $this->load->model('Inst_model');
                                                $chck_amnt_rng = $this->Inst_model->check_servc_amnt_rng($chkservicestat['service_id'],  $param['amount']);
                                                 
                                                if($chck_amnt_rng)
                                                {
                                                    $chkservicestat=$chck_amnt_rng;
                                                    
                                                }else{
                                                         $data['error'] = 1;
                                                         $data['error_desc'] = "Failed to process request";
                                                         $data['msg'] = null;
                                                         $data['date'] = date('Y-m-d H:i:s');
                                                         $data['status'] = 'FAILED';
                                                         echo json_encode($data);
                                                         exit;
                                                }

                                            }
                                            
                                          
                                            if ($chkservicestat['gateway_down'] == 0)
                                            {

                                               $chkservicestat['vendor_library'] = trim($chkservicestat['vendor_library']);
                                                
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) 
                                            {

                                                $this->load->library($chkservicestat['vendor_library']);
                                                
                                                if (method_exists($this->{$chkservicestat['vendor_library']}, 'prepaid_rech'))
                                                    {   

                                                        $result = $this->{$chkservicestat['vendor_library']}->prepaid_rech($user_info, $user_info['plan_id'], $param['account'], $param['amount'], $chkservicestat);
                                                        $data = $result;
                                                        $data['date'] = date('Y-m-d H:i:s');

                                                    }else{

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = "Failed to process request";
                                                        $data['msg'] = null;
                                                        $data['date'] = date('Y-m-d H:i:s');
                                                        $data['status'] = 'FAILED';

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

                                             

                                            
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Invalid Service Type";
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
                                $data['error_desc'] = "Incomplete request parameter";
                                $data['msg'] = null;
                                $data['date'] = date('Y-m-d H:i:s');
                                $data['status'] = 'FAILED';
                            }
                            
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

    public function dth_recharge(){
      $param = $this->input->post('data');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if (page_access(10)) {
                    if ($user_info['is_block'] == 0) {
                        $chkservicestat = $this->Main_model->fetch_service_prov($param['spkey']);
                        if ($chkservicestat) {
                            // print_r($chkservicestat);exit;  
                            if (isset($param['account']) && isset($param['amount'])) {
                                if ($chkservicestat['is_down'] == 0) {

                                    if ($chkservicestat['gateway_down'] == 0) {

                                        if ($chkservicestat['type'] == 'DTH') {
                                            if ($chkservicestat['Amnt_by_routing'] == 1) {
                                                 
                                                $this->load->model('Inst_model');
                                            $chck_amnt_rng = $this->Inst_model->check_servc_amnt_rng($chkservicestat['service_id'],  $param['amount']);
                                                if($chck_amnt_rng){
                                                if ($chck_amnt_rng['gateway_down'] == 0) {
                                                $chck_amnt_rng['vendor_library'] = trim($chck_amnt_rng['vendor_library']);
                                                  $chkservicestat['vendor_id']=$chck_amnt_rng['vendor_id'];
                                              
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chck_amnt_rng['vendor_library']) . ".php")) {

                                                $this->load->library($chck_amnt_rng['vendor_library']);
                                               
                                                $chkservicestat['vendor_id']=$chck_amnt_rng['vendor_id'];

                                                $result = $this->{$chck_amnt_rng['vendor_library']}->dth_rech($user_info, $user_info['plan_id'], $param['account'], $param['amount'], $chkservicestat);

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
                                               
                                                $result = $this->{$chkservicestat['vendor_library']}->dth_rech($user_info, $user_info['plan_id'], $param['account'], $param['amount'], $chkservicestat);

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
                                $data['error_desc'] = "Incomplete request parameter";
                                $data['msg'] = null;
                                $data['date'] = date('Y-m-d H:i:s');
                                $data['status'] = 'FAILED';
                            }
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
	
	    public function Recharge_Txn_Re() {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
          
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                    $from = $this->input->post('from');
                        $to = $this->input->post('to');
                        $cell = $this->input->post('cell');
                   
                            $search = $this->input->post('search');
                            $length = $this->input->post('length');
                            $email = $this->session->userdata('email');
                            $this->load->model('Inst_model');
                            if ($user_info) {
                                $result = array();
                                $user_data = $this->Inst_model->Datatable_fetch_recharge_myorder($user_info['user_id'], $from, $to, $cell);
                                if ($user_data) {
							
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_recharge_myorder($user_info['user_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_recharge_myorder($user_info['user_id'], $from, $to, $cell),
                                        "data" => $user_data
                                    );
                                } else {
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_recharge_myorder($user_info['user_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_recharge_myorder($user_info['user_id'], $from, $to, $cell),
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
	
	    public function Recharge_Txn_Allorder() {
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
								
								$user_data = $this->Inst_model->Datatable_fetch_recharge_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch);

                                if ($user_data) {

                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_recharge_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $treeFetch),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_recharge_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch),
                                        "data" => $user_data
                                    );
                                } else {
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_recharge_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $treeFetch),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_recharge_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch),
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

	public function selectMenu() {
    $param = $this->input->post('cell_id');
    $type = $this->input->post('type');
    if (isset($type) && ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($param)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
					  $data = array();
                    $param_array = [10, 13, 12];
                    $type_array = ['RECHARGE', 'REMITTANCE'];
                    if (in_array($param, $param_array) && in_array($type, $type_array)) {
                        if ($param == 10 || $param == 13 || $param == 12) {
                            $this->load->model('Inst_model');
                            $status_name = $this->Inst_model->statusMenu_name();
                            if ($status_name) {
                                $arr = [];
                                foreach ($status_name as $key => $value) {
                                    $arr[$key] = ['name' => $value, 'code' => $value];
                                }
                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = $arr;
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Unable to find status name';
                                $data['msg'] = null;
                            }
                        }
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Index Error.';
                        $data['msg'] = null;
                    }
                         
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
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
	
	 /* function selectMenu() {
        $param = $this->input->post('cell_id');
        $type = $this->input->post('type');
        if (isset($type) && ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($param)) {
            $e = $this->session->userdata('acntid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if (page_access(6)) {
                    $data = array();
                    $param_array = [10, 13, 12];
                    $type_array = ['RECHARGE', 'REMITTANCE'];
                    if (in_array($param, $param_array) && in_array($type, $type_array)) {
                        if ($param == 10 || $param == 13 || $param == 12) {
                            $this->load->model('Rech_Model');
                            $status_name = $this->Rech_Model->statusMenu_name();
                            if ($status_name) {
                                $arr = [];
                                foreach ($status_name as $key => $value) {
                                    $arr[$key] = ['name' => $value, 'code' => $value];
                                }
                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = $arr;
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Unable to find status name';
                                $data['msg'] = null;
                            }
                        }
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Index Error.';
                        $data['msg'] = null;
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Invalid Request';
                    $data['msg'] = null;
                }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = null;
            }
            echo json_encode($data);
        } else {
            redirect('Login');
        }
    }
 */

  
    // public function validation (){
        
    // $curl = curl_init();

    // curl_setopt_array($curl, array(
    //   CURLOPT_URL => "https://www.instantpay.in/ws/api/transaction?format=xml&token=19b6aaa0623d1a724d9ba7691652dbb8&agentid=2&amount=11&spkey=VFP&account=9868638810&mode=VALIDATE",
    //   CURLOPT_RETURNTRANSFER => true,
    //   CURLOPT_ENCODING => "",
    //   CURLOPT_MAXREDIRS => 10,
    //   CURLOPT_TIMEOUT => 0,
    //   CURLOPT_FOLLOWLOCATION => true,
    //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //   CURLOPT_CUSTOMREQUEST => "GET",
    // ));

    // $response = curl_exec($curl);

    // curl_close($curl);
    // echo $response;


    // }

   
    // SUCCESS
    // {"ipay_id":"1191223193139MLUNF","agent_id":"Z19177319252JMTU","opr_id":1577109699,"account_no":"8851245444","sp_key":"VFP","trans_amt":11,"charged_amt":10.87,"opening_bal":"9966.40","datetime":"2019-12-23 19:31:39","status":"SUCCESS","res_code":"TXN","res_msg":"Transaction Successful"}

    // Pending
    // {"ipay_id":"1191223193354EJFCX","agent_id":"Z19109319992JMTU","opr_id":"00","account_no":"8851245444","sp_key":"VFP","trans_amt":12,"charged_amt":11.86,"opening_bal":null,"datetime":"2019-12-23 19:33:54","status":"PENDING","res_code":"TUP","res_msg":"Transaction Under Process"}

    //mapp = isset (res_code) then status ==SUCCESS,PENDING 

    // Failed
    // {"ipay_errorcode":"SPE","ipay_errordesc":"Service Provider Error"}

     // mapp = isset(ipay_errorcode) then status == Failed
////**** update parameters in db by bbps_biller*****   IPI TGI  HDI

// FASTAG   PBT  BFT AFT ICT  BBT     BROADBAND  VFB TTB TIB TBB SBB NBB NPB MNB INB ONB//
  
   
	
	  function PrintTable($rcid) {
        $rowdata = $this->input->post('checkrow');
    
        if ($rcid && $rcid != null && $rcid != '' && isset($rowdata) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [];
            $e = $this->session->userdata('userid');
           $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                    $pdfFilePath = "output_pdf_name.pdf";
                    //load mPDF library
                    $this->load->library('m_pdf');
                    $rowdata = json_decode($rowdata, true);
                     $path = base_url() . '/assets/images/logo.png';
                        $image = file_get_contents($path);
                        $base64 = 'data:image/jpg;base64,' . base64_encode($image);
                    $conten = '
             <table border="0" cellpadding="0" cellspacing="0" width="100%">
                         <tr>
                                 <td>
                                        <table  border="0" cellspacing="0" cellpadding="0" width="100%">
                                                 <tr>
                                                     <td  valign="top" width="500">
                                                         <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapper">
                                                         <tr>
                                                                 <td  valign="top" style="padding: 15px 0px 0px 0px;" class="logo">
                                                                     <h2 style="text-align:center;">Transaction Receipt</h2>
                                                                 </td>
                                                                 
                                                              </tr>
                                                            
                                                                 <tr>
                                                                         <td  valign="top" style="padding: 15px 0px 0px 0px;" class="logo">
                                                                             <h4>Shop Name: ' . $user_info['business_name'] . '</h4>
                                                                         </td>
                                                                 </tr>
                                                                 <tr>
                                                                         <td  valign="top" style="padding: 15px 0px 40px 0px;" class="logo">
                                                                             <h4>Address: ' . $user_info['business_address'] . ', ' . $user_info['business_city'] . ', ' . $user_info['business_state'] . ' ' . $user_info['business_pincode'] . '</h4>
                                                                         </td>
                                                                 </tr>
                                                         </table>
                                                     </td>
                                                 </tr>
                                         </table>

                                         <table  border="0" cellspacing="0" cellpadding="0" width="100%" style="border:1px solid #ddd;">
                                                 <tr style="border:1px solid #ddd;">
                                                         <th align="center" valign="top" style="padding: 5px; border:1px solid #ddd;">
                                                         Transaction ID
                                                         </th>
                                                     <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                      Mobile No/ Connection No
                                                         </th>
                                                   ';
                 
                    $conten = $conten . '<th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                     Amount
                                                         </th>
                                                         <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                         UTR
                                                         </th>
                                                     <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                     Transaction Time
                                                         </th>
                                                         <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                         Status
                                                         </th>
                                                 </tr>';
                    foreach ($rowdata as $c) {
					

                        $conten = $conten . '<tr>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['trans'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['remitter'] . '</td>';
                                                     
                        $conten = $conten . '
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['amt'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['utr'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['tran_time'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['status'] . '</td>';
                    }
                    $conten = $conten . '</tr>;
                             </table>
                             <table  border="0" cellspacing="0" cellpadding="0" width="100%">
                                     <tr>
                                         <td  valign="top" width="500">
                                             <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapper">
                                                     <tr>
                                                             <td valign="top" style="padding: 25px 0px 5px 0px;">
                                                                 <h5>Terms & Conditions / Disclaimer</h5>
                                                             </td>
                                                     </tr>
                                                     <tr>
                                                            <td>
                                                                <p style="font-size:12px">1. all service charges our inclusive of GST.</p>
                                                                <p style="font-size:12px">2. This transaction receipt is only a provisional acknowledgment and is issued to customer mentioned herein for accepting mentioned payment for the
                                                                above order and as per the details provided by the customer.</p>
                                                                <p style="font-size:12px">3. The customer is fully responsible for the accuracy of the details as provided by him before the transaction is initiated.</p>
                                                                <p style="font-size:12px">4. The Merchant shall not charge any fee to the customer directly for services rendered by them. The customer is required to immediately report such
                                                                additional/excess charges to TrulyIndia.</p>
                                                                <p style="font-size:12px">5. This is a system generated receipt hence does not require any signature.</p>
                                                            </td>
                                                    </tr>
                                                    
                                                  
                                             </table>
                                         </td>
                                     </tr>
                             </table>
                     </td>
                </tr>
         </table>';
// <tr>
//                                                             <td  valign="top" style="padding: 5px 0px 5px 0px;">
//                                                                 <h6>Is there anything you want to share with us?</h6>
//                                                             </td>
//                                                     </tr>
           //<tr>
                                                            // <td  valign="top" style="padding: 0px 0px 40px 0px;">
                                                            //     <p style="font-size:12px">Feedback, comments, suggestions or compliments - do write to  help@cybertelindia.in </p>
                                                            // </td>
                                                  //  </tr>
                    $this->m_pdf->pdf->WriteHTML($conten);
                    //download it.
                    if (count($rowdata) == 1) {

                        $name = $rowdata[0]['trans'];

                    } else {

                        $name = "Transaction Receipts";
                        
                    }

                    $this->m_pdf->pdf->Output($name, 'I');
                  
            } else {

                redirect('Login');
            }
        } else {  
            redirect('Dashboard');
        }
    }



 


}

?>
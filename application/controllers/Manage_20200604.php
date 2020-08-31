<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {


    public function Users(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==1 ||$user_info['role_id']==2 || $user_info['role_id']==3 ) {
        //redirect ('Manage/users');
        $this->load->view('Dashboard/templates/header');
        $this->load->view('Manage/manage_sidebar');
        $this->load->view('Manage/users');

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

    public function PendingActivation(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==1) {
        
        $this->load->view('Dashboard/templates/header');
        $this->load->view('Manage/manage_sidebar');
        $this->load->view('Manage/pending_activation');

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

    public function Vendor(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==1) {
        
        $this->load->view('Dashboard/templates/header');
        $this->load->view('Manage/manage_sidebar');
        $this->load->view('Manage/vendor');
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
         public function getpininfo() {

        $pin = $this->input->post('data');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
             
                    if ($user_info['is_block'] == 0) {

                        $FetchAdminUser = $this->Main_model->getpininfo($pin);
                        $data['error_data'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL;
                        $data['data'] = $FetchAdminUser;
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
                
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }

    public function PaymentRequest (){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){  
   
      if($user_info['role_id']==2 || $user_info['role_id']==3 || $user_info['role_id']==4) {   
        $this->load->view('Dashboard/templates/header');
        //$this->load->view('Manage/manage_sidebar');
        $this->load->view('Manage/payment_reqst_send');
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

    public function PendingPayments(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==1 || $user_info['role_id']==2 || $user_info['role_id']==3 ) {
        
        $this->load->view('Dashboard/templates/header');
        $this->load->view('Manage/manage_sidebar');
        $this->load->view('Manage/pending_payments');
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



        public function VendorView() {
        
        $e = $this->session->userdata('userid');
        $user_info = $this->Main_model->user_acntid($e);
        if ($user_info) {
            if ($_GET['p']) {
               
                    $getAllDataVendor = $this->Main_model->getAllDataVendor($_GET['p']);
                  //  $getVendorBank = $this->Main_model->getVendorBank($_GET['p']);

                    if ($getAllDataVendor) {
                        
                        $dat = [];
                        $dat['Vendor'] = $getAllDataVendor ? $getAllDataVendor : '';
                        // $dat['Bank'] = $getVendorBank ? $getVendorBank : [];

                        if ($user_info['is_block'] == 0) {

                            

                            $this->load->view('Dashboard/templates/header');
                            $this->load->view('Manage/manage_sidebar');
                            $this->load->view('Manage/vendor_view', $dat);

                        } else {

                            $this->session->sess_destroy();
                            redirect('Login');
                        }
                    } else {

                        redirect('Dashboard');
                    }
               
            } else {
                redirect('Dashboard');
            }
        } else {
            $this->session->sess_destroy();
            redirect('Login');
        }
    }



    public function Plan(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==1) {
        $this->load->view('Dashboard/templates/header');
        $this->load->view('Manage/manage_sidebar');
        $this->load->view('Manage/plan');
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

    public function Services(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    if($user_info['is_block']==0){  
    if($user_info['role_id']==1) {
        
        $this->load->view('Dashboard/templates/header');
        $this->load->view('Manage/manage_sidebar');
        $this->load->view('Manage/services');
    
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

    public function PlanListTable() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
            if ($user_info['is_block'] == 0) {
            if ($user_info['role_id'] == 1) {

                    $PlanListData = $this->Main_model->PlanListData();
                    $data['error_data'] = 0;
                    $data['error_desc'] = NULL;
                    $data['msg'] = NULL;
                    $data['data'] = $PlanListData ? $PlanListData : [];
            } else {
                    $data['error_data'] = 2;
                    $data['error_desc'] = 'Access denied';
                    $data['msg'] = NULL;
                    $data['data'] = array();
                    $this->session->sess_destroy();
            }
            } else {
                    $data['error_data'] = 2;
                    $data['error_desc'] = 'Access denied';
                    $data['msg'] = NULL;
                    $data['data'] = array();
                    $this->session->sess_destroy();
            }
               
            } else {
                    $data['error_data'] = 2;
                    $data['error_desc'] = 'Invalid Request';
                    $data['msg'] = NULL;
                    $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }



    public function get_roles() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $e = $this->session->userdata('userid'); 
        $user_info=$this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
            if ($user_info['is_block'] == 0) {
            $roles_array = $this->Main_model->get_all_roles();
            if ($roles_array) {
                            
                $loggedin_role = $this->session->userdata('role_id');
                           
                $result = array();
                foreach ($roles_array as $key => $value) {
                    $allowed_byarray = explode(",", $value['allowed_for']);
                    if (in_array($loggedin_role, $allowed_byarray)) {
                        $result[] = $value;
                    }
                }
                            
                        $data['error'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = $result;

                } else {

                        $data['error'] = 1;
                        $data['error_desc'] = 'Unable to find user type';
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

     public function Fetch_pln_frUser(){
        $role = $this->input->post('roleId');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $r = $this->session->userdata('role_id');
           
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();   
            if ($user_info) {
               
                if ($user_info['is_block'] == 0) {

                    $plan = $this->Main_model->FetchPlanForCreateUser($role);
                          
                        $data['error_data'] = 0;   
                        $data['error_desc'] = NULL;    
                        $data['msg'] = NULL;
                        $data['data'] = $plan ? $plan : [];
                } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                }
               
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }
       public function UserPlanUpdated(){
        $UserId = $this->input->post('UserId');
        $PlanId = $this->input->post('PlanId');

        $UserId = trim($UserId);
        $PlanId = trim($PlanId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                
                    if ($user_info['is_block'] == 0) {

                        if ($PlanId) {
                            $checkPlanExist = $this->Main_model->checkPlanExist($PlanId);
                            if ($checkPlanExist) {

                                $planUpdate = $this->Main_model->PlanUpdate($PlanId, $UserId);
                                if ($planUpdate) {
                                    $data['error_data'] = 0;
                                    $data['error_desc'] = NULL;
                                    $data['msg'] = 'Plan Updated';
                                } else {
                                    $data['error_data'] = 1;
                                    $data['error_desc'] = 'Plan Not Updated';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error_data'] = 1;
                                $data['error_desc'] = 'Plan Not Exist';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error_data'] = 1;
                            $data['error_desc'] = 'Plan Not Valid';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
                } else {
                    $data['error_data'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
                    $data['msg'] = NULL;
                    $data['data'] = array();
                }
               

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


   public function InsertNewUserUser () {
        $b = All_Regex();
        $Role = $this->input->post('Role');
        $Aadhar = $this->input->post('Aadhar');
        $FirstName = $this->input->post('FirstName');       
        $LastName = $this->input->post('LastName');
        $Mobile = $this->input->post('Mobile');
        $Email = $this->input->post('Email');
        $DateOfBirth = $this->input->post('DateOfBirth');
        $Plan = $this->input->post('Plan');
        $GSTIN = $this->input->post('GSTIN');
        $Pan = $this->input->post('Pan');
        $BusinessName = $this->input->post('BusinessName');
        $BusinessAddress = $this->input->post('BusinessAddress');
        $BusinessState = $this->input->post('BusinessState');
        $BusinessCity = $this->input->post('BusinessCity');
        $Pincode = $this->input->post('Pincode');
        $RegisteredAddress = $this->input->post('RegisteredAddress');
        $IsActive = $this->input->post('IsActive');
        $TwoFactor = $this->input->post('TwoFactor');

        $Role = trim($Role);
        $Aadhar = trim($Aadhar);
        $FirstName = trim($FirstName);
        $LastName = trim($LastName);
        $Mobile = trim($Mobile);
        $Email = trim($Email);
        $DateOfBirth = trim($DateOfBirth);
        $Plan = trim($Plan);
        $GSTIN = trim($GSTIN);
        $Pan = trim($Pan);
        $BusinessName = trim($BusinessName);
        $BusinessAddress = trim($BusinessAddress);
        $BusinessState = trim($BusinessState);
        $BusinessCity = trim($BusinessCity);
        $Pincode = trim($Pincode);
        $RegisteredAddress = trim($RegisteredAddress);
        $IsActive = trim($IsActive);
        $TwoFactor = trim($TwoFactor);

        $TwoFactor = ($TwoFactor == 'true') ? 1 : 0;
        $IsActive = ($IsActive == 'true') ? 1 : 0;

        //$plan_access = page_access_fetch(23);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {    
                // if (page_access(65)) {
                    if ($user_info['is_block'] == 0) {
                         $loggedin_role = $this->session->userdata('role_id');
                         if ($user_info['role_id'] == 1) {
                           
                            if ($Plan) {
                                $checkPlanExist = $this->Main_model->checkPlanExist($Plan);
                                if (!$checkPlanExist) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Plan Not Exist';
                                    $data['msg'] = NULL;
                                    echo json_encode($data);     
                                    exit;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Plan Invalid';  
                                $data['msg'] = NULL;
                                echo json_encode($data);
                                exit;
                            }


                          


                        } else {

                            $Plan = NULL;

                            // $fetchDefaultPlan = $this->Main_model->FetchDefaultPlan($Role);
                             
                            // if ($fetchDefaultPlan) {

                            //     $Plan = $fetchDefaultPlan['plan_id'];

                            // } else {

                            //     $data['error'] = 1;
                            //     $data['error_desc'] = 'Something Went Wrong, Contact Admin';
                            //     $data['msg'] = NULL;
                            //     echo json_encode($data);
                            //     exit;
                            // }
                        }

                      if ($FirstName) {
                        if (preg_match('/' . $b['Name'] . '/', $FirstName)) {
                                    if ($LastName) {
                                    if (preg_match('/' . $b['Name'] . '/', $LastName)) {
                                        if ($Mobile && strlen($Mobile) == 10) {
                                            if (preg_match('/' . $b['Mobile']['Full'] . '/', $Mobile)) {
                                                $checkMobileAlready = $this->Main_model->get_phone($Mobile);
                                            if (!$checkMobileAlready) {
                                            if ($Email) {
                                            if (preg_match('/' . $b['Email']['Full'] . '/', $Email)) {
                                                            $checkEmailAlready = $this->Main_model->get_email($Email);
                                            if (!$checkEmailAlready) {
                                            if ($DateOfBirth) {
                                            if (preg_match('/' . $b['DateOfBirth'] . '/', $DateOfBirth)) {
                                            if (time() > strtotime('+18 years', strtotime($DateOfBirth))) {

                                            // if ($Plan) {
                                            // $checkPlanExist = $this->Main_model->checkPlanExist($Plan);
                                            // if ($checkPlanExist) {
                                            if ($Role) {
                                            $checkRoleValid = $this->Main_model->checkRoleValid($Role);
                                            if ($checkRoleValid){    
                                             
                                            if ($GSTIN != '') {
                                            if (!preg_match('/' . $b['GSTTIN']['Full'] . '/', $GSTIN)) {
                                                                $data['error'] = 1;
                                                                $data['error_desc'] = 'GSTTIN Invalid';
                                                                $data['msg'] = NULL;
                                                                echo json_encode($data);
                                                                exit;

                                            }
                                            }

                                             if ($Pan) {
                                                            if (preg_match('/' . $b['PanNumber']['Full'] . '/', $Pan)) {
                                                                $checkPanAlready = $this->Main_model->checkPanAlready($Pan);
                                                            if (!$checkPanAlready) {

                                                        if ($Aadhar) {
                                                            if (preg_match('/' . $b['AadharNumber']['Full'] . '/', $Aadhar)) {
                                                                $checkAadharAlready = $this->Main_model->checkAadharAlready($Aadhar);
                                                            if (!$checkPanAlready) {                  


                                                            if ($BusinessName) {
                                                            // if (preg_match('/' . $b['Name'] . '/', $BusinessName)) {

                                                             if (strlen($BusinessAddress)>=5 && strlen($BusinessAddress)<=500) {
                                                            // if (preg_match('/' . $b['Name'] . '/', $BusinessAddress)) {
                                                            if ($BusinessState) {
                                                            if (preg_match('/' . $b['Name'] . '/', $BusinessState)) {
                                                            if ($BusinessCity) {
                                                            if (preg_match('/' . $b['Name'] . '/', $BusinessCity)) {    
                                                            if ($Pincode && strlen($Pincode) == 6) {
                                                            if (preg_match('/' . $b['Number'] . '/', $Pincode)) {                                    
                                                              if (strlen($RegisteredAddress)>=5 && strlen($RegisteredAddress)<=500) {                  
                                                            // if (preg_match('/' . $b['Name'] . '/', $RegisteredAddress)) {
                                                                 $letters = array_merge(range('A', 'Z'), range('a', 'z'));
                                             $pswd = $letters[mt_rand(0, 51)] . mt_rand(100, 999) . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)] . mt_rand(0, 9);   
                                                            // $pswd = '123456';



                                                    $accnt_rand = account_id_creation();            
                                                    $insert = array(                                
                                                    'user_id' => $accnt_rand,
                                                     'parent_id'=>($user_info['role_id'] == 1) ? 0 : $user_info['user_id'],
                                                    'plan_id' => $Plan,                                   
                                                    'role_id' => $Role,                                           
                                                     'first_name' => $FirstName,                           
                                                    'last_name' => $LastName,                             
                                                    'mobile' => $Mobile,                        
                                                    'email' => $Email,                            
                                                    'password' => md5($pswd),                            
                                                    'date_of_birth' => date_format(date_create($DateOfBirth), 'Y-m-d'),                                     
                                                    'gstin' => $GSTIN ? $GSTIN : NULL,                       
                                                    'pan' => $Pan, 
                                                    'aadhar' => $Aadhar,                                   
                                                    'business_name' => $BusinessName,                  
                                                    'business_address' => $BusinessAddress,            
                                                    'business_state' => $BusinessState,
                                                    'business_city' => $BusinessCity,                                                                    
                                                    'business_pincode' => $Pincode,                                                                      
                                                    'registered_address' => $RegisteredAddress,
                                                    'created_ip' => ip_address(),                           
                                                    'created_by' => $user_info['user_id'],                    
                                                    // 'updated_by' => $user_info['user_id'],                 
                                                    'created_on' => date('Y-m-d H:i:s'),                      
                                                    'updated_on' => date('Y-m-d H:i:s'),                  
                                                    'is_kyc' => 0,                                            
                                                    'two_factor' => $TwoFactor,
                                                    'is_block' => ($loggedin_role == 1) ? 0 : 1,                                        
                                                    'is_active' => $IsActive                                
                                                );
                                              $doc_array = array();                                           
                                             $user_doc_path = 'assets/UserDocsKYC/' . $accnt_rand . '/';
                                                                                                       
                                             if (!is_dir($user_doc_path)) {                                  mkdir($user_doc_path);                              
                                             }  


                                            if (isset($_FILES['IDProof'])) {
                                            $config['upload_path'] = $user_doc_path;
                                            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                                            $config['max_size'] = 1000000;
                                            $config['max_width'] = 1024;
                                            $config['max_height'] = 768;
                                            $config['file_name'] = 'ID PROOF';
                                            $this->load->library('upload', $config);

                                            if (!$this->upload->do_upload('IDProof')) {
                                            $data['error'] = 1;
                                            $data['error_desc'] = $this->upload->display_errors();
                                            $data['msg'] = null;
                                            echo json_encode($data);
                                            exit;
                                            } else {
                                                $upd_file = $this->upload->data();
                                                $file_path = $config['upload_path'] . $upd_file['file_name'];
                                                $files = array(
                                                'user_id' => $accnt_rand,
                                                'doc_name' => 'ID PROOF',
                                                'doc_for' => 'KYC',
                                                'doc_path' => $file_path,
                                                'uploaded_on' => date('Y-m-d H:i:s'),
                                                'uploaded_by' => $user_info['user_id'],
                                                'status' => ($user_info['role_id'] == 1) ? 'APPROVED' : 'PENDING',
                                                                                                    );
                                            array_push($doc_array, $files);
                                             }
                                             }

                                           if (isset($_FILES['AddressProof'])) {
                                                $config['upload_path'] = $user_doc_path;
                                                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                                                $config['max_size'] = 1000000;
                                                 $config['max_width'] = 1024;
                                                $config['max_height'] = 768;
                                                $config['file_name'] = 'ADDRESS PROOF';
                                                $this->load->library('upload', $config);

                                                if (!$this->upload->do_upload('AddressProof')) {
                                                $data['error'] = 1;
                                                $data['error_desc'] = $this->upload->display_errors();
                                                $data['msg'] = null;
                                                echo json_encode($data);
                                                 exit;
                                                } else {
                                                $upd_file = $this->upload->data();
                                                $file_path = $config['upload_path'] . $upd_file['file_name'];
                                                 $files = array(
                                                   'user_id' => $accnt_rand,
                                                    'doc_name' => 'ADDRESS PROOF',
                                                    'doc_for' => 'KYC',
                                                    'doc_path' => $file_path,
                                                'uploaded_on' => date('Y-m-d H:i:s'),
                                                'uploaded_by' => $user_info['user_id'],
                                                'status' => ($user_info['role_id'] == 1) ? 'APPROVED' : 'PENDING',
                                                );
                                                array_push($doc_array, $files);
                                                }
                                                }
                                                if (isset($_FILES['PhotoProof'])) {
                                                $config['upload_path'] = $user_doc_path;
                                                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                                                $config['max_size'] = 1000000;
                                                $config['max_width'] = 1024;
                                                $config['max_height'] = 768;
                                                $config['file_name'] = 'PHOTO';
                                                $this->load->library('upload', $config);
                                                if (!$this->upload->do_upload('PhotoProof')) {
                                                $data['error'] = 1;
                                               $data['error_desc'] = $this->upload->display_errors();
                                                $data['msg'] = null;
                                                echo json_encode($data);
                                                exit;
                                                } else {
                                                    $upd_file = $this->upload->data();
                                                     $file_path = $config['upload_path'] . $upd_file['file_name'];
                                                    $files = array(
                                                        'user_id' => $accnt_rand,
                                                        'doc_name' => 'PHOTO',
                                                        'doc_for' => 'KYC',
                                                        'doc_path' => $file_path,
                                                        'uploaded_on' => date('Y-m-d H:i:s'),
                                                        'uploaded_by' => $user_info['user_id'],
                                                        'status' => ($user_info['role_id'] == 1) ? 'APPROVED' : 'PENDING',
                                                        );
                                                        array_push($doc_array, $files);
                                                        }
                                                      }  
                                                      $get_data = $this->Main_model->insert_users_data($insert, $doc_array);

                                                    if ($get_data) {
                                                    //print_r($get_data);exit;
                                                 
                                                    $this->load->library('Service_alerts');
                $tk = $this->service_alerts->UAC('UAC',$Mobile, $Email, $BusinessName,$checkRoleValid['role_name'], $accnt_rand, $pswd, 'User Creation');

                                                    $data['error'] = 0;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'User Create successfully';


                                                    } else {

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Something Went wrong';
                                                    $data['msg'] = null;
                                                    }
                                            // } else {
                                            // $data['error'] = 1;
                                            // $data['error_desc'] = 'Registered Address Invalid';
                                            // $data['msg'] = NULL;
                                            // }                    

                                             } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Registered Address Not Valid';
                                            $data['msg'] = NULL;
                                            }                      
                                             } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Pincode Invalid';
                                            $data['msg'] = NULL;
                                            }                    

                                             } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Pincode Not Valid';
                                            $data['msg'] = NULL;
                                            }                     
                                            } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Business City Invalid';
                                            $data['msg'] = NULL;
                                            }                    

                                             } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Business City Not Valid';
                                            $data['msg'] = NULL;
                                            }                     
                                            } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Business State Invalid';
                                            $data['msg'] = NULL;
                                            }                    

                                             } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Business State Not Valid';
                                            $data['msg'] = NULL;
                                            }                     
                                            //   } else {
                                            // $data['error'] = 1;
                                            // $data['error_desc'] = 'Business Address Invalid';
                                            // $data['msg'] = NULL;
                                            // }                    

                                             } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Business Address Not Valid';
                                            $data['msg'] = NULL;
                                            }                    
                                             } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Business Name Invalid';
                                            $data['msg'] = NULL;
                                            }                    

                                            //  } else {
                                            // $data['error'] = 1;
                                            // $data['error_desc'] = 'Business Name Not Valid';
                                            // $data['msg'] = NULL;
                                            // }


                                            } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Aadhar Already Exist';
                                            $data['msg'] = NULL;
                                            }
                                            } else {
                                              $data['error'] = 1;
                                              $data['error_desc'] = 'Aadhar Invalid';
                                              $data['msg'] = NULL;
                                              }
                                            } else {
                                               $data['error'] = 1;
                                               $data['error_desc'] = 'Aadhar Not Valid';
                                               $data['msg'] = NULL;
                                            } 

                                           } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Pan Already Exist';
                                            $data['msg'] = NULL;
                                            }
                                            } else {
                                              $data['error'] = 1;
                                              $data['error_desc'] = 'Pan Invalid';
                                              $data['msg'] = NULL;
                                              }
                                            } else {
                                               $data['error'] = 1;
                                               $data['error_desc'] = 'Pan Not Valid';
                                               $data['msg'] = NULL;
                                            }                    



                                            } else {
                                                     $data['error'] = 1;
                                                     $data['error_desc'] = 'Role Invalid';
                                                      $data['msg'] = NULL;
                                             }
                                            } else {
                                                     $data['error'] = 1;
                                                     $data['error_desc'] = 'Role Not Exist';
                                                     $data['msg'] = NULL;
                                              }
                                            
                                                                            

                                            } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'User Was Under Age Should Be Greater Than 18 Years';
                                                    $data['msg'] = NULL;
                                            }    
                                            } else {
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Date Of Birth Invalid';
                                                        $data['msg'] = NULL;
                                            }
                                            } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Date Of Birth Not Valid';
                                                    $data['msg'] = NULL;
                                            }
                                            } else {
                                                                $data['error'] = 1;
                                                                $data['error_desc'] = 'Email Already Exist';
                                                                $data['msg'] = NULL;
                                            }
                                            } else {
                                                            $data['error'] = 1;
                                                            $data['error_desc'] = 'Email  Invalid';
                                                            $data['msg'] = NULL;
                                            }
                                            } else {
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Email Not Valid';
                                                        $data['msg'] = NULL;
                                             }        
                                                                
                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Mobile Number Already Exist';
                                                $data['msg'] = NULL;
                                            }                       
                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Mobile Number Invalid';
                                                $data['msg'] = NULL;
                                            }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Mobile Number Not Valid And Should Be 10 Digit';
                                            $data['msg'] = NULL;
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Last Name Invalid';
                                        $data['msg'] = NULL;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Last Name Not Valid';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'First Name Invalid';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'First Name Not Valid';
                            $data['msg'] = NULL;
                        }                            
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                    
                        $this->session->sess_destroy();
                    }
                // } else {
                //     $data['error'] = 1;
                //     $data['error_desc'] = 'Unauthorised access';
                //     $data['msg'] = NULL;
                   
                // }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
             
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }

     public function get_usr_lst(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        $loggedin_role = $this->session->userdata('role_id');
                        $data['error_data'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL;
                        $child_role = $this->Main_model->get_my_childs($e, $loggedin_role);
                    
                        if ($child_role) {
                              
                            $result = array();   
                         
                            $data['data'] = $child_role;
                        } else {
                            $data['data'] = array();
                        }
                        
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
              
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }   
    }


       public function get_all_vendors() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {

                        $vendors = $this->Main_model->all_vendors();
                      
                        $data['error_data'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL;
                        if ($vendors) {
                            $result = array();

                            $data['data'] = $vendors;
                        } else {
                            $data['data'] = array();
                        }
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


    public function getvbal() {
        $param = $this->input->post('data');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {
                            $param = trim($param);
                            if (ctype_digit($param)) {
                                $vendr = $this->Main_model->check_vendor($param);
                                if ($vendr) {
                                    if ($vendr['bal_check_api'] == 1) {
                                        $this->load->library($vendr['vendor_library']);
                                        $data = $this->{$vendr['vendor_library']}->ApiBalanceCheck($user_info['user_id']);
                                        if ($data['error'] == 0) {
                                            $data['error'] = 0;
                                            $data['error_desc'] = null;
                                            $data['bal'] = $data['msg']['Balance'];
											$data['msg'] = "Request Completed Successfully";
                                           
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Balance Api not available';
                                        $data['msg'] = null;
                                    }
                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find vendor';
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Vendor id';
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised access';
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

  

        public function PendingUsers(){
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                      $loggedin_role = $this->session->userdata('role_id');
                        $data['error_data'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL;
                        $pdng_actvtn_user = $this->Main_model->pndg_actvtn_usr();
                        if ($pdng_actvtn_user) {

                            foreach ($pdng_actvtn_user as $k => $v) {
                                $doc_ext = $this->Main_model->check_doctment($v['user_id']);
                                $pdng_actvtn_user[$k]['doc_avlbl'] = $doc_ext ? 1 : 0;
                            }
                            $result = array();
                            $data['data'] = $pdng_actvtn_user;
                        } else {
                            $data['data'] = array();
                        }
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


     public function get_user_docs(){
       $id = $this->input->post('data');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        $loggedin_role = $this->session->userdata('role_id');
                        $data['error_data'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL;
                        $id_proof = $this->Main_model->check_id_proof($id, 'ID PROOF');
                        $add_proof = $this->Main_model->check_id_proof($id, 'ADDRESS PROOF');
                        $photo = $this->Main_model->check_id_proof($id, 'PHOTO');

                        $data['error'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg']['ID PROOF'] = $id_proof ? $id_proof : array();
                        $data['msg']['ADDRESS PROOF'] = $add_proof ? $add_proof : array();
                        $data['msg']['PHOTO'] = $photo ? $photo : array();
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }    
    }


     public function UserKYCUploadFile() {

        $id = $this->input->post('accntid');
        $doctyp = $this->input->post('doctyp');

        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {

                         $vald_doc = array("ID PROOF", "ADDRESS PROOF", "PHOTO");
                            if (in_array($doctyp, $vald_doc)) {
                                $chck_exstng_doc = $this->Main_model->check_exstng_doc($id, $doctyp);

                            if (!$chck_exstng_doc) {
                                $user_doc_path = 'assets/UserDocsKYC/' . $id . '/';
                                if (!is_dir($user_doc_path)) {
                                    mkdir($user_doc_path);
                                }
                                $doc_type = str_replace(' ', '_', $doctyp);
                                $config['upload_path'] = $user_doc_path;
                                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                                $config['file_name'] = $doc_type;

                                $this->load->library('upload', $config);

                                if (!$this->upload->do_upload('file')) {

                                    $data['error'] = 1;
                                    $data['error_desc'] = $this->upload->display_errors();
                                    $data['msg'] = null;
                                } else {

                                    $upd_file = $this->upload->data();

                                    $file_path = $config['upload_path'] . $upd_file['file_name'];
                                    $insert = array(
                                        'user_id' => $id,
                                        'doc_path' => $file_path,
                                        'doc_name' => $doctyp,
                                        'doc_for' => 'KYC',
                                        'uploaded_on' => date('Y-m-d H:i:s'),
                                        'uploaded_by' => $user_info['user_id'],
                                        'status' => 'APPROVED'
                                    );
                                    $get_data = $this->Main_model->insert_docby_doc_typ_id($insert);

                                    if ($get_data) {
                                        $data['error'] = 0;
                                        $data['error_desc'] = null;
                                        $data['msg'] = 'Document Uploaded Sucessfully';
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something Went Wrong';
                                        $data['msg'] = null;
                                    }
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Document Already Exists';
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid Document Type';
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


      public function approve_usr_document(){
        $id = $this->input->post('apprv_id');
        $acntid = $this->input->post('acntid');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                
                    if ($user_info['is_block'] == 0) {
                        $chck_pndg_doc = $this->Main_model->chck_pndg_doc($id, $acntid);
                        if ($chck_pndg_doc) {
                            $update = array(
                                'status' => 'APPROVED',
                                'updated_on' => date('Y-m-d H:i:s'),
                                'updated_by' => $user_info['user_id'],
                            );
                            $get_data = $this->Main_model->updt_apprv_docmnt($id, $update, $acntid);

                            if ($get_data) {
                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = 'User Document Approved Successfully';
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Something Went Wrong';
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['msg'] = null;
                            $data['error_desc'] = 'Unable To Find User Document';
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }  
    }

     public function reject_usr_document() {
        $id = $this->input->post('rjct_doc_id');
        $acntid = $this->input->post('acntid');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        $chck_pndg_doc = $this->Main_model->chck_pndg_doc($id, $acntid);
                        if ($chck_pndg_doc) {
                            $update = array(
                                'status' => 'REJECTED',
                                'updated_on' => date('Y-m-d H:i:s'),
                                'updated_by' => $user_info['user_id'],
                            );
                            $get_data = $this->Main_model->updt_apprv_docmnt($id, $update);
                            if ($get_data) {
                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = 'User Document Rejected Successfully';
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Something Went Wrong';
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['msg'] = null;
                            $data['error_desc'] = 'Unable To Find User Document';
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


     public function Actvt_usr_drctly(){  
            $UserId = $this->input->post('UserId');
            $Plan = $this->input->post('Plan');
            $UserId=trim($UserId);
            $Plan=trim($Plan);
        if (isset($Plan)&& isset($UserId) &&$_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                // if (page_access(67)) {
                    if ($user_info['is_block'] == 0) {    
                        $chck_inctv_usr = $this->Main_model->is_inactive_usr($UserId);
                         
                        if ($chck_inctv_usr) {
                            $checkPlanExist = $this->Main_model->checkPlanExistForRole($Plan,$chck_inctv_usr['role_id']);
                            if ($checkPlanExist) {
                            
                            $update = array(
                                'is_block' => 0,
                                'is_active' => 1,
                                'updated_on' => date('Y-m-d H:i:s'),
                                'updated_by' => $user_info['user_id'],
                                'plan_id'=>$Plan
                            );
                            $get_data = $this->Main_model->updt_user_details($UserId, $update);

                            if ($get_data) {

                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = 'User activated successfully';
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Something Went wrong';
                                $data['msg'] = null;
                            }
                             } else {

                            $data['error'] = 1;
                            $data['msg'] = null;
                            $data['error_desc'] = 'Unable to find user details for activation';
                        }
                        } else {

                            $data['error'] = 1;
                            $data['msg'] = null;
                            $data['error_desc'] = 'Unable to find user details for activation';
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
                // } else {
                //     $data['error'] = 1;
                //     $data['error_desc'] = 'Unauthorised access';
                //     $data['msg'] = NULL;
                //     $data['data'] = array();
                // }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }

     public function AddBalance(){
        $b = All_Regex();
        $id = $this->input->post('UserId');
        $blnc = $this->input->post('amnt');
        $py_md = $this->input->post('py_amnt');
        $bnk_ref = $this->input->post('bnk_ref');
        $bnk_nar = $this->input->post('bnk_nar');
        $isdue = $this->input->post('isdue');
        $id=trim($id);
        $blnc=trim($blnc);
        $py_md=trim($py_md);
        $bnk_ref=trim($bnk_ref);
        $bnk_nar=trim($bnk_nar);
        $isdue=trim($isdue);
        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $e = $this->session->userdata('userid');
        $user_info = $this->Main_model->user_acntid($e);
        $data = array();
        if($user_info){
           
        if ($user_info['is_block'] == 0) {
        if($blnc){
        if (preg_match('/' . $b['Amount'] . '/', $blnc)) {
            $vald_mode = array("IMPS", "Cash", "NEFT", "OTHERS");
        if($py_md){
        if (in_array($py_md, $vald_mode)) {
        $vald_due=array("true","false");
        if(in_array($isdue, $vald_due)){  
        if($bnk_ref){
        if (preg_match('/' . $b['Text'] . '/', $bnk_ref)) {
        if($bnk_nar){
        if (preg_match('/' . $b['Text'] . '/', $bnk_nar)) {
        $usrdtl = $this->Main_model->user_acntid($id);
        if($usrdtl){
        if ($usrdtl['is_active'] == 1) {
        if ($usrdtl['is_block'] == 0) {
        if($user_info['role_id']==1){
            /******admin case****/    
            $opn_blnc = $usrdtl['rupee_balance'];
            $chrge = $blnc;
            $cls_blnc = $opn_blnc + $chrge;
           if ($cls_blnc >= 0) {
                                                    $rtlr_txnid = ch_txnid();
                                                    $insert_cdt_hstry = array(
                                                        'credit_txnid' =>$rtlr_txnid,
                                                        'user_id' => $usrdtl['user_id'],
                                                        'bank_name' => 'NA',
                                                        'txn_type' => 'DEPOSIT',
                                                        'payment_mode' => $py_md,
                                                        'amount' => $chrge,
                                                        'opening_balance' => $opn_blnc,
                                                        'closing_balance' => $cls_blnc,
                                                         'updated_on' => date('Y-m-d H:i:s'),
                                                        'updated_by'=>$user_info['user_id'],
                                                        'reference_number' => $py_md . ' : ' . $bnk_ref,
                                                        'remarks' => $bnk_nar,
                                                        'txn_code' => admn_trnsfer_cd(),
                                                        'status' => 'CREDIT',
                                                        // 'credited_by' => $user_info['user_id'],
                                                        'is_due' => ($isdue == 'true') ? 1 : 0,
                                                        'is_received'=>($isdue == 'true') ? 0 : 1,
                                                        'created_on'=>date('Y-m-d H:i:s'),
                                                        'created_by'=>$user_info['user_id']
                                                        
                                                    );
                                                   
                                                    $get_data = $this->Main_model->update_cdt_blnc_by_admin($id, $insert_cdt_hstry);

                                                    if ($get_data) {
                                                        //////////////// send msg//////////////
                                                        $data['error'] = 0;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = 'Balance Added successfully';
                                                    } else {
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Something Went wrong';
                                                        $data['msg'] = null;
                                                    }
                                                } else {

                                                    $data['error'] = 1;
                                                    $data['msg'] = null;
                                                    $data['error_desc'] = 'Internal processing error';
                                                }  
                /******end admin case****/    
        }else{

        /*********super dist & dist case**********/
        $parent_role = $this->Main_model->get_user_parent_role($usrdtl['parent_id']);

        if ($parent_role){
         //print_r($parent_role);exit;
        /******super distributor and distributor case********/

        $opn_blnc = $usrdtl['rupee_balance'];
        $chrge = $blnc;
        $stsdtsr = 'DEBIT';
        $stsrtlr = 'CREDIT';
        $opn_blnc_ofdbstr = $user_info['rupee_balance'];
        $cls_blnc_dstr = $opn_blnc_ofdbstr - $chrge;
        $cls_blnc_rtlr = $opn_blnc + $chrge;

        if ($cls_blnc_dstr >= 0) {
                                                        $dist_txn_id = ch_txnid();
                                                        $rtlr_txn_id = ch_txnid();
                                                        $insert_dstbtr = array(
                                                       /***dist debit entry***/
                                                        'credit_txnid' =>$dist_txn_id,
                                                        'user_id' =>$user_info['user_id'],
                                                        'bank_name' => 'NA',
                                                        'txn_type' => 'TRANSFER FROM',
                                                        'payment_mode' => $py_md,
                                                        'amount' => $chrge,
                                                        'opening_balance' => $opn_blnc_ofdbstr,
                                                        'closing_balance' => $cls_blnc_dstr,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => $py_md . ' : ' . $bnk_ref,
                                                        'remarks' => $bnk_nar,
                                                        'txn_code' => dstrbtr_trnsfer_cd(),
                                                        'status' =>$stsdtsr,
                                                      'updated_by'=>$user_info['user_id'],
                                                        'is_due' =>0,
                                                        'is_received'=>1,
                                                         'created_on'=>date('Y-m-d H:i:s'),
                                                        'created_by'=>$user_info['user_id']
                                                        
                                                    );
                                                    $insert_retlr = array(
                                                       
                                                        'credit_txnid' =>$rtlr_txn_id,
                                                        'user_id' => $usrdtl['user_id'],//retlr user id
                                                        'bank_name' => 'NA',
                                                        'txn_type' => 'TRANSFER To',
                                                        'payment_mode' => $py_md,
                                                        'amount' => $chrge,
                                                        'opening_balance' => $opn_blnc,
                                                        'closing_balance' => $cls_blnc_rtlr,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => $py_md . ' : ' . $bnk_ref,
                                                        'remarks' => $bnk_nar,
                                                        'txn_code' => dstrbtr_trnsfer_cd(),
                                                        'status' =>$stsrtlr,
                                                        'updated_by'=>$user_info['user_id'],
                                                        'is_due' => ($isdue == 'true') ? 1 : 0,
                                                        'is_received'=>($isdue == 'true') ? 0 : 1,
                                                        'created_on'=>date('Y-m-d H:i:s'),
                                                        'created_by'=>$user_info['user_id']

                                                    );
                                                    
                                                    $get_data = $this->Main_model->update_cdt_blnc_by_dstbtr($user_info['user_id'], $insert_dstbtr, $id, $insert_retlr);

                                                    if ($get_data) {


                                                        //////////////// send msg//////////////

                                                        $data['error'] = 0;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = 'Balance Updated successfully';
                                                    } else {
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Something Went wrong';
                                                        $data['msg'] = null;
                                                    }
                                                } else {

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Insuffient balance';
                                                    $data['msg'] = null;
                                                }


        

        }else{
                $data['error_data'] = 1;
                $data['error_desc'] = 'Unable To Process Request,Please contact admin';
                $data['msg'] = NULL;
        }

        /*********end super dist & dist case**********/
        }

        } else {

                    $data['error'] = 1;
                    $data['error_desc'] = 'Unable to fetch user details';
                    $data['msg'] = null;
        }
        } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'User account inactive';
                    $data['msg'] = null;
        }    

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = null;
        }
        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Bank Narration';
                $data['msg'] = NULL;
        }    

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Bank Narration Invalid';
                $data['msg'] = NULL;
        }
        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Bank Refrence';
                $data['msg'] = NULL;
        }    

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Bank Refrence Invalid';
                $data['msg'] = NULL;
        }

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Payment Due';
                $data['msg'] = NULL;
        }  
        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Payment Mode';
                $data['msg'] = NULL;
        }    

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Payment Mode Invalid';
                $data['msg'] = NULL;
        }
        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Amount';
                $data['msg'] = NULL;
        }    

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Amount Invalid';
                $data['msg'] = NULL;
        }

        }else {
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

        }else{
            redirect('Dashboard');
        }

    }



     public function UpdateNewUserDetails(){
        $b = All_Regex();
        $FirstName = $this->input->post('FirstName');
        $LastName = $this->input->post('LastName');
        $Mobile = $this->input->post('Mobile');
        $Email = $this->input->post('Email');
        $DateOfBirth = $this->input->post('DateOfBirth');
        $GSTIN = $this->input->post('GSTIN');
        $Pan = $this->input->post('Pan');
        $Aadhar = $this->input->post('Aadhar');
        $BusinessName = $this->input->post('BusinessName');
        $BusinessAddress = $this->input->post('BusinessAddress');
        $BusinessState = $this->input->post('BusinessState');
        $BusinessCity = $this->input->post('BusinessCity');
        $Pincode = $this->input->post('BusinessPincode');
        $RegisteredAddress = $this->input->post('RegisteredAddress');
        $UserId = $this->input->post('UserId');

        $FirstName = trim($FirstName);
        $LastName = trim($LastName);
        $Mobile = trim($Mobile);
        $Email = trim($Email);
        $DateOfBirth = trim($DateOfBirth);
        $GSTIN = trim($GSTIN);
        $BusinessName = trim($BusinessName);
        $BusinessAddress = trim($BusinessAddress);
        $BusinessState = trim($BusinessState);
        $BusinessCity = trim($BusinessCity);
        $Pincode = trim($Pincode);
        $RegisteredAddress = trim($RegisteredAddress);
        $UserId = trim($UserId);  
        $Aadhar=trim($Aadhar); 
        $Pan=trim($Pan);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                if ($user_info['is_block'] == 0) {
                if ($FirstName) {
                            if (preg_match('/' . $b['Name'] . '/', $FirstName)) {
                                if ($LastName) {
                                    if (preg_match('/' . $b['Name'] . '/', $LastName)) {
                                        if ($Mobile && strlen($Mobile) == 10) {
                                            if (preg_match('/' . $b['Mobile']['Full'] . '/', $Mobile)) {
                                                $checkMobileAlready = $this->Main_model->check_mobile_alrdy_updt($Mobile, $UserId);
                                                if (!$checkMobileAlready) {
                                                    if ($Email) {
                                                        if (preg_match('/' . $b['Email']['Full'] . '/', $Email)) {
                                                            $checkEmailAlready = $this->Main_model->checkEmailAlreadyUpdate($Email, $UserId);
                                                            if (!$checkEmailAlready) {
                                                                if ($DateOfBirth) {
                                                                    if (preg_match('/^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/', $DateOfBirth)) {
                                                                        if (time() > strtotime('+18 years', strtotime($DateOfBirth))) {

                                                                             if ($GSTIN != '') {
                                                                                if (!preg_match('/' . $b['GSTTIN']['Full'] . '/', $GSTIN)) {
                                                                                    $data['error_data'] = 1;
                                                                                    $data['error_desc'] = 'GSTTIN Invalid';
                                                                                    $data['msg'] = NULL;
                                                                                    echo json_encode($data);
                                                                                    exit;
                                                                                }
                                                                            }



    if ($Pan) {
    if (preg_match('/' . $b['PanNumber']['Full'] . '/', $Pan)) {
    $checkPanAlready = $this->Main_model->checkPanAlreadyUpdate($Pan, $UserId);
    if (!$checkPanAlready) {

     if ($Aadhar) {
    if (preg_match('/' . $b['AadharNumber']['Full'] . '/', $Aadhar)) {
    $checkAadharAlready = $this->Main_model->checkAadharAlreadyUpdate($Aadhar, $UserId);
    if (!$checkPanAlready) {
    

    if ($BusinessName) {
    // if (preg_match('/' . $b['Name'] . '/', $BusinessName)) {
    if (strlen($BusinessAddress)>=5 && strlen($BusinessAddress)<=500) {
    // if (preg_match('/' . $b['Name'] . '/', $BusinessAddress)) {
    if ($BusinessState) {
    if (preg_match('/' . $b['Name'] . '/', $BusinessState)) {
    if($BusinessCity) {   
    if (preg_match('/' . $b['Name'] . '/', $BusinessCity)) {

    if ($Pincode && strlen($Pincode) == 6) {
    if (preg_match('/' . $b['Number'] . '/', $Pincode)) {                                    
    if (strlen($RegisteredAddress)>=5 && strlen($RegisteredAddress)<=500) {              
    // if (preg_match('/' . $b['Name'] . '/', $RegisteredAddress)) {
         $update = array(                                
                                                  
                                                   
                                                                                             
                                                     'first_name' => $FirstName,                           
                                                    'last_name' => $LastName,                             
                                                    'mobile' => $Mobile,                        
                                                    'email' => $Email,                            
                                                                              
                                                    'date_of_birth' => date_format(date_create($DateOfBirth), 'Y-m-d'),                                     
                                                    'gstin' => $GSTIN ? $GSTIN : NULL,                       
                                                    'pan' => $Pan, 
                                                    'aadhar' => $Aadhar,                                   
                                                    'business_name' => $BusinessName,                  
                                                    'business_address' => $BusinessAddress,            
                                                    'business_state' => $BusinessState,
                                                    'business_city' => $BusinessCity,                                                                    
                                                    'business_pincode' => $Pincode,                                                                      
                                                    'registered_address' => $RegisteredAddress,
                                                                      
                                                    'updated_by' => $user_info['user_id'],                 
                                                                        
                                                    'updated_on' => date('Y-m-d H:i:s'),                  
                                                                                       
                                                                                
                                                );
                                                 $get_data = $this->Main_model->update_users_data($UserId, $update);

                                                                                        if ($get_data) {
                                                                                            $data['error'] = 0;
                                                                                            $data['error_desc'] = null;
                                                                                            $data['msg'] = 'User updated successfully';
                                                                                        } else {
                                                                                            $data['error'] = 1;
                                                                                            $data['error_desc'] = 'Something Went wrong';
                                                                                            $data['msg'] = null;
                                                                                        }


                                            //  } else {
                                            //             $data['error_data'] = 1;
                                            //             $data['error_desc'] = 'Registered Address Invalid';
                                            //             $data['msg'] = NULL;
                                            // } 
                                            } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Registered Address Not Valid';
                                                        $data['msg'] = NULL;
                                            } 

                                            } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Pincode Invalid';
                                                        $data['msg'] = NULL;
                                            } 
                                            } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Pincode Not Valid';
                                                        $data['msg'] = NULL;
                                            } 

                                            } else {                                                              
                                                $data['error_data'] = 1;                                                      
                                                $data['error_desc'] = 'Business City Invalid';                                                       
                                                $data['msg'] = NULL;
                                             }
                                            } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Business City Not Valid';
                                                        $data['msg'] = NULL;
                                            }    

                                            } else {                                                              
                                                $data['error_data'] = 1;                                                      
                                                $data['error_desc'] = 'Business State Invalid';                                                       
                                                $data['msg'] = NULL;
                                             }
                                            } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Business State Not Valid';
                                                        $data['msg'] = NULL;
                                            }
                                            // } else {
                                            //         $data['error_data'] = 1;
                                            //         $data['error_desc'] = 'Business Address Invalid';
                                            //         $data['msg'] = NULL;
                                            // }
                                            } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Business Address Not Valid';
                                                        $data['msg'] = NULL;
                                            }
                                            } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Business Name Invalid';
                                                        $data['msg'] = NULL;
                                            }
                                            // } else {
                                            //             $data['error_data'] = 1;
                                            //             $data['error_desc'] = 'Business Name Not Valid';
                                            //             $data['msg'] = NULL;
                                            // }

                                             } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Aadhar Already Exist';
                                                        $data['msg'] = NULL;
                                            }
                                            } else {
                                                                                    $data['error_data'] = 1;
                                                                                    $data['error_desc'] = 'Aadhar Invalid';
                                                                                    $data['msg'] = NULL;
                                                                                }
                                                                            } else {
                                                                                $data['error_data'] = 1;
                                                                                $data['error_desc'] = 'Aadhar Not Valid';
                                                                                $data['msg'] = NULL;
                                                                            }
                                             } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Pan Already Exist';
                                                        $data['msg'] = NULL;
                                            }
                                            } else {
                                                                                    $data['error_data'] = 1;
                                                                                    $data['error_desc'] = 'Pan Invalid';
                                                                                    $data['msg'] = NULL;
                                                                                }
                                                                            } else {
                                                                                $data['error_data'] = 1;
                                                                                $data['error_desc'] = 'Pan Not Valid';
                                                                                $data['msg'] = NULL;
                                                                            }
                                                                        } else {
                                                                            $data['error_data'] = 1;
                                                                            $data['error_desc'] = 'User Was Under Age Should Be Greater Than 18 Years';
                                                                            $data['msg'] = NULL;
                                                                        }
                                                                    } else {
                                                                        $data['error_data'] = 1;
                                                                        $data['error_desc'] = 'Date Of Birth Invalid';
                                                                        $data['msg'] = NULL;
                                                                    }
                                                                } else {
                                                                    $data['error_data'] = 1;
                                                                    $data['error_desc'] = 'Date Of Birth Not Valid';
                                                                    $data['msg'] = NULL;
                                                                }
                                                            } else {
                                                                $data['error_data'] = 1;
                                                                $data['error_desc'] = 'Email Already Exist';
                                                                $data['msg'] = NULL;
                                                            }
                                                        } else {
                                                            $data['error_data'] = 1;
                                                            $data['error_desc'] = 'Email  Invalid';
                                                            $data['msg'] = NULL;
                                                        }
                                                    } else {
                                                        $data['error_data'] = 1;
                                                        $data['error_desc'] = 'Email Not Valid';
                                                        $data['msg'] = NULL;
                                                    }
                                                } else {
                                                    $data['error_data'] = 1;
                                                    $data['error_desc'] = 'Mobile Number Already Exist';
                                                    $data['msg'] = NULL;
                                                }
                                            } else {
                                                $data['error_data'] = 1;
                                                $data['error_desc'] = 'Mobile Number Invalid';
                                                $data['msg'] = NULL;
                                            }
                                        } else {
                                            $data['error_data'] = 1;
                                            $data['error_desc'] = 'Mobile Number Not Valid And Should Be 10 Digit';
                                            $data['msg'] = NULL;
                                        }
                                    } else {
                                        $data['error_data'] = 1;
                                        $data['error_desc'] = 'Last Name Invalid';
                                        $data['msg'] = NULL;
                                    }
                                } else {
                                    $data['error_data'] = 1;
                                    $data['error_desc'] = 'Last Name Not Valid';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error_data'] = 1;
                                $data['error_desc'] = 'First Name Invalid';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error_data'] = 1;
                            $data['error_desc'] = 'First Name Not Valid';
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
             
            }

            echo json_encode($data);

        } else {
            redirect('Dashboard');
        }
}



 public function UserDeactivate(){
     
          $acntid = $this->input->post('usr_anctid');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        $chck_alwd_usr = $this->Main_model->is_allowds_usr($acntid, $user_info['role_id'], $user_info['user_id']);
                        if ($chck_alwd_usr) {
                            if ($chck_alwd_usr['is_active'] == 1) {
                            $update = array(
                                
                                'is_active' => 0,
                                'updated_on' => date('Y-m-d H:i:s'),
                                'updated_by' => $user_info['user_id']
                            );
                            $get_data = $this->Main_model->updt_user_details($acntid, $update);

                            if ($get_data) {

                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = 'User Deactivated Successfully';

                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Something Went wrong';
                                $data['msg'] = null;
                            } 
                              
                         } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'User Account Already Deactivated';
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Unable To Fetch User Details';
                            $data['msg'] = null;
                        }         
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


       public function UserActivate() {
        $id = $this->input->post('usr_anctid');
        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        $chck_alwd_usr = $this->Main_model->is_allowds_usr($id, $user_info['role_id'], $user_info['user_id']);
                        if ($chck_alwd_usr) {
                            if ($chck_alwd_usr['is_active'] == 0) {
                                if ($chck_alwd_usr['is_block'] == 0) {
                                    $update = array(
                                        'is_active' => 1,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'updated_by' => $user_info['user_id']
                                    );
                                    $get_data = $this->Main_model->insert_activated_data($id, $update);

                                    if ($get_data) {
                                        $data['error'] = 0;
                                        $data['error_desc'] = null;
                                        $data['msg'] = 'User Activated Successfully';
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something Went Wrong';
                                        $data['msg'] = null;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'User Cannot Be Activated, User Account blocked';
                                    $data['msg'] = null;
                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'User Account inactive';
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Unable To Fetch User Details';
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


     public function deduct_usr_blnc_amnt() {
        $b = All_Regex();
        $id = $this->input->post('UserId');
        $blnc = $this->input->post('amnt');
        $bnk_ref = $this->input->post('bnk_ref');
        $id=trim($id);
        $blnc=trim($blnc);
        $bnk_ref=trim($bnk_ref);
        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $e = $this->session->userdata('userid');
        $user_info = $this->Main_model->user_acntid($e);
        $data = array();
        if($user_info){
        // if (page_access_fetch(27)) {
        // if (page_access(66)) {  
        if ($user_info['is_block'] == 0) {
        if($blnc){
        if (preg_match('/' . $b['Amount'] . '/', $blnc)) {
        if($bnk_ref){
        if (preg_match('/' . $b['Text'] . '/', $bnk_ref)) {
        $usrdtl = $this->Main_model->user_acntid($id);
        if($usrdtl){
        if ($usrdtl['is_block'] == 0) {
           if($user_info['role_id']==1){
               /******admin case****/    
            $opn_blnc = $usrdtl['rupee_balance'];
            $chrge = $blnc;
            $cls_blnc = $opn_blnc - $chrge;
           if ($cls_blnc >= 0) {
                                                    $rtlr_txnid = ch_txnid();

                                                     $insert = array(

                                                        'credit_txnid'=>ch_txnid(),
                                                        'user_id' =>$id,
                                                        'bank_name' => 'NA',
                                                        'txn_type' => 'WITHDRAW',
                                                        'payment_mode' => 'ADMIN',
                                                        'amount' => $chrge,
                                                        'opening_balance' => $opn_blnc,
                                                        'closing_balance' => $cls_blnc,
                                                         'updated_on' => date('Y-m-d H:i:s'),
                                                        'updated_by'=>$user_info['user_id'],
                                                        'reference_number' => $bnk_ref,
                                                        'remarks' => 'ADMIN : ' . $bnk_ref,
                                                        'txn_code' => admn_trnsfer_cd(),
                                                        'status' => 'DEBIT',
                                                        //'credited_by' => $user_info['user_id'],
                                                        'is_received'=>1,
                                                        'created_on'=>date('Y-m-d H:i:s'),
                                                        'created_by'=>$user_info['user_id']
                                                    );
                                                   
                                                    $get_data = $this->Main_model->update_dbt_blnc_by_admin($id, $insert);

                                                    if ($get_data) {
                                                        //////////////// send msg//////////////
                                                        $data['error'] = 0;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = 'Balance deducted successfully';

                                                    } else {

                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Something Went wrong';
                                                        $data['msg'] = null;

                                                    }
                                                    
                                                } else {

                                                    $data['error'] = 1;
                                                    $data['msg'] = null;
                                                    $data['error_desc'] = 'Internal processing error';
                                                }  
                /******end admin case****/    
        }else{

        /*********for super dist & dist case**********/
             $data['error'] = 1;
            $data['error_desc'] = 'Unauthorised Process';
            $data['msg'] = null;

        
        }

        } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'User account inactive';
                    $data['msg'] = null;
        }      
        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Unable to fetch user details';
                $data['msg'] = null;
        }    
        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Bank Refrence';
                $data['msg'] = NULL;
        }    

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Bank Refrence Invalid';
                $data['msg'] = NULL;
        }   

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Amount';
                $data['msg'] = NULL;
        }    

        }else{
                $data['error'] = 1;
                $data['error_desc'] = 'Amount Invalid';
                $data['msg'] = NULL;
        }
        }else {
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

        }else{
            redirect('Dashboard');
        }    
    }


     public function FetchParentDtlOfUsr(){
       $parent_id = $this->input->post('data');
        $User = $this->input->post('User');
        $ViewRoleId = $this->input->post('ViewRoleId');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($parent_id) && isset($User)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                       
                       
                        $AdminRoles = $this->Main_model->AdminRoles($ViewRoleId);
                     
                        if ($parent_id == 0) {

                            $ParentData = $this->Main_model->ParentAdminUser($User);


                        } else {

                          $ParentData = $this->Main_model->ParentDistUsers($parent_id);

                        }
                      
                     
                        $data['error'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL; 
                        $data['data'] = $ParentData;
                        $data['roles'] = $AdminRoles ? $AdminRoles : [];
                       

                    } else {

                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }

               
            } else {
                
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
                $this->session->sess_destroy();
            }

            echo json_encode($data);

        } else {

            redirect('Dashboard');
        }
    }


       public function FetchParentListDataRoleWise() {
        $params = $this->input->post('data');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($params)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
           
                    if ($user_info['is_block'] == 0) {
                        $RolesUser = $this->Main_model->RolesUser($params);

                        $data['error'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL;
                        $data['data'] = $RolesUser ? $RolesUser : [];
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }

        public function UserRemppingWithAdmin(){
    
         $UserId = $this->input->post('UserId');

        $UserId = trim($UserId);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
            
                    if ($user_info['is_block'] == 0) {
                        $FetchUserData = $this->Main_model->user_acntid_user_table($UserId);
                        if ($FetchUserData['parent_id'] != 0) {
                            $updateDis = $this->Main_model->UpdateDataUserParent($UserId, 0, $user_info['user_id']);
                            if ($updateDis) {
                                $data['error'] = 0;
                                $data['error_desc'] = null;
                                $data['msg'] = $FetchUserData['first_name'] . ' ' . $FetchUserData['last_name'] . ' Successfully Map With Admin';
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = $FetchUserData['first_name'] . ' ' . $FetchUserData['last_name'] . ' Not Successfully Map With Admin';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'This User Already Assign This Parent';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
              
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }

        }

        public function UserRemppingWithParent(){
        $UserId = $this->input->post('UserId');
        $RoleUserList = $this->input->post('RoleUserList');
        $Role = $this->input->post('Role');

        $UserId = trim($UserId);
        $RoleUserList = trim($RoleUserList);
        $Role = trim($Role);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                        $FetchUserData = $this->Main_model->user_acntid_user_table($UserId);
                        if ($FetchUserData['parent_id'] != $RoleUserList) {
                            $CheckUpdateParent = $this->Main_model->CheckUpdateParent($RoleUserList, $Role);
                            if ($CheckUpdateParent) {
                                $AdminRoles = $this->Main_model->AdminRoles($FetchUserData['role_id']);
                                if ($AdminRoles) {
                                    $rolesData = array();

                                    foreach ($AdminRoles as $value) {
                                        $rolesData[$value['role_id']] = $value;
                                    }
                                    if (array_key_exists($Role, $rolesData)) {
                                        $updateDis = $this->Main_model->UpdateDataUserParent($UserId, $RoleUserList, $user_info['user_id']);
                                        if ($updateDis) {
                                            $data['error'] = 0;
                                            $data['error_desc'] = null;
                                            $data['msg'] = $FetchUserData['first_name'] . ' ' . $FetchUserData['last_name'] . ' Successfully Map With ' . $CheckUpdateParent['first_name'] . ' ' . $CheckUpdateParent['last_name'];
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = $FetchUserData['first_name'] . ' ' . $FetchUserData['last_name'] . ' Not Successfully Map With ' . $CheckUpdateParent['first_name'] . ' ' . $CheckUpdateParent['last_name'];
                                            $data['msg'] = NULL;
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Not Allowed To Remap This Role';
                                        $data['msg'] = NULL;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Something Went Wrong';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Role User List';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'This User Already Assign This Parent';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


      public function UserKycChange(){
      
        $id = $this->input->post('accntid');
        $doctyp = $this->input->post('doctyp');
        $doc_rowid = $this->input->post('id');
        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                
                    if ($user_info['is_block'] == 0) {
                         $vald_doc = array("ID PROOF", "ADDRESS PROOF", "PHOTO");
                        if (in_array($doctyp, $vald_doc)) {
                            $chck_chng_doc = $this->Main_model->check_chng_doc($id, $doctyp, $doc_rowid);

                            if ($chck_chng_doc) {
                                $user_doc_path = 'assets/UserDocsKYC/' . $id . '/';

                                $doc_type = str_replace(' ', '_', $doctyp);
                                $config['upload_path'] = $user_doc_path;
                                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                                $config['file_name'] = $doc_type;

                                $this->load->library('upload', $config);
                                if (!$this->upload->do_upload('file')) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = $this->upload->display_errors();
                                    $data['msg'] = null;
                                } else {
                                    $upd_file = $this->upload->data();

                                    $file_path = $config['upload_path'] . $upd_file['file_name'];

                                    $update = array(
                                        'doc_path' => $file_path,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'updated_by' => $user_info['user_id'],
                                        'status' => 'APPROVED'
                                    );

                                    $get_data = $this->Main_model->update_exstng_doc_bydoctyp($id, $update, $doc_rowid);
                                    if ($get_data) {

                                        $data['error'] = 0;
                                        $data['error_desc'] = null;
                                        $data['msg'] = 'Document Updated Sucessfully';
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something Went Wrong';
                                        $data['msg'] = null;
                                    }
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Document not exists';
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid Document Type';
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }     
    }


      public function ServiceFetchAll(){
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {

                            $FetchAllService = $this->Main_model->FetchAllService();


                            $data['error'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = 'Service Successfully Fetch';
                            $data['data'] = $FetchAllService ? $FetchAllService : [];
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
                            $data['msg'] = NULL;
                            $data['data'] = array();
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access Denied';
                        $data['msg'] = NULL;
                          $data['data'] = array();
                        $this->session->sess_destroy();
                    }
                
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                 $data['data'] = array();
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }

      public function RoleWiseRoleForPlan(){
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
           
                    if ($user_info['is_block'] == 0) {
                        // if ($user_info['role_id'] == 1 ) {

                        //     $RoleWiseRoleForPlan = $this->Main_model->get_all_roles($user_info['role_id']);
                        //      // print_r($RoleWiseRoleForPlan);exit;
                        //     $data['error'] = 0;
                        //     $data['error_desc'] = NULL;
                        //     $data['msg'] = NULL;
                        //     $data['data'] = $RoleWiseRoleForPlan ? $RoleWiseRoleForPlan : [];
                        // } else {
                        //     $data['error'] = 2;
                        //     $data['error_desc'] = 'Access denied';
                        //     $data['msg'] = NULL;
                        //     $data['data'] = array();
                        //     $this->session->sess_destroy();
                        // }
                         $roles_array = $this->Main_model->get_all_roles_fr_crt_usr();
                       
                         if ($roles_array) {
                           
                            $loggedin_role = $this->session->userdata('role_id');
                            //print_r($loggedin_role);exit;
                            $result = array();
                            foreach ($roles_array as $key => $value) {
                                $allowed_byarray = explode(",", $value['allowed_for']);
                                if (in_array($loggedin_role, $allowed_byarray)) {
                                    $result[] = $value;
                                }
                            }
                           

                            $data['error'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = NULL;
                            $data['data'] = $result ? $result : [];

                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Unable to find user type';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }



         public function PlanDataEdit() {
       
        $b = All_Regex();
        $params = $this->input->post();

        $PlanName = $params['PlanName'];
        $PlanCode = $params['PlanCode'];
        $Description = $params['Description'];
        $PlanFor = $params['PlanFor'];
        $IsActive = $params['IsActive'];
        $PlanId = $params['PlanId'];
        

        $PlanName = trim($PlanName);
        $PlanCode = trim($PlanCode);
        $Description = trim($Description);
        $PlanFor = trim($PlanFor);
        $IsActive = trim($IsActive);
        $PlanId = trim($PlanId);
      

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {
                            if (preg_match('/' . $b['Text'] . '/', $PlanName)) {
                                $checkPlanNameOnupdate = $this->Main_model->checkPlanNameOnupdate($PlanName, $PlanId);
                                if (!$checkPlanNameOnupdate) {
                                    if (preg_match('/' . $b['Code'] . '/', $PlanCode)) {
                                        $checkPlanCodeOnUpdate = $this->Main_model->checkPlanCodeOnUpdate($PlanCode, $PlanId);
                                        if (!$checkPlanCodeOnUpdate) {

                                            if ($Description != '') {
                                                if (!preg_match('/' . $b['Text'] . '/', $Description)) {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Description Invalid';
                                                    $data['msg'] = NULL;
                                                    echo json_encode($data);
                                                    exit;
                                                }
                                            }

                                            // $checkRolesExist = $this->Main_model->checkRolesExist($PlanFor, $user_info['role_id']);
                                            // if ($checkRolesExist) {

                                                $EditPlanData = $this->Main_model->EditPlanData($PlanName, $PlanCode, $Description, $PlanFor, $IsActive, $user_info['user_id'], $PlanId);
                                                if ($EditPlanData) {
                                                    $data['error'] = 0;
                                                    $data['error_desc'] = NULL;
                                                    $data['msg'] = 'Plan Successfully Updated';
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Plan Not Successfully Updated';
                                                    $data['msg'] = NULL;
                                                }
                                            // } else {
                                            //     $data['error'] = 1;
                                            //     $data['error_desc'] = 'Plan For Invalid And You Have No Permission To Create Plan For This Role';
                                            //     $data['msg'] = NULL;
                                            // }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Plan Code Already Exist';
                                            $data['msg'] = NULL;
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Plan Code Invalid';
                                        $data['msg'] = NULL;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Plan Name Already Exist';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Plan Name Invalid';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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



       public function PlanDataAdd(){
       
         $b = All_Regex();
        $params = $this->input->post();
        $PlanName = $params['PlanName'];
        $PlanCode = $params['PlanCode'];
        $Description = $params['Description'];
        $PlanFor = $params['PlanFor'];
        $IsActive = $params['IsActive'];
        // $IsDefault = $params['IsDefault'];

        $PlanName = trim($PlanName);
        $PlanCode = trim($PlanCode);
        $Description = trim($Description);
        $PlanFor = trim($PlanFor);
        $IsActive = trim($IsActive);
        // $IsDefault = trim($IsDefault);
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1 ) {
                            if (preg_match('/' . $b['Text'] . '/', $PlanName)) {
                                $checkPlannameOnInsert = $this->Main_model->check_extng_planName($PlanName);
                                if (!$checkPlannameOnInsert) {

                                    if (preg_match('/' . $b['Code'] . '/', $PlanCode)) {
                                        $checkPlanCodeOnInsert = $this->Main_model->check_extng_planCode($PlanCode);
                                        if (!$checkPlanCodeOnInsert) {

                                            if ($Description != '') {
                                                if (!preg_match('/' . $b['Text'] . '/', $Description)) {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Description Invalid';
                                                    $data['msg'] = NULL;
                                                    echo json_encode($data);
                                                    exit;
                                                }
                                            }

                                            // $checkRolesExist = $this->Main_model->checkRolesExist($PlanFor, $user_info['role_id']);
                                        
                                            // if ($checkRolesExist) {

                                        $AddPlanData = $this->Main_model->AddPlanData($PlanName, $PlanCode, $Description, $PlanFor, $IsActive,  $user_info['user_id']);
                                                if ($AddPlanData) {
                                                    $data['error'] = 0;
                                                    $data['error_desc'] = NULL;
                                                    $data['msg'] = 'Plan Successfully Added';
                                                    $data['data'] = $AddPlanData;
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Plan Not Successfully Added';
                                                    $data['msg'] = NULL;
                                                }
                                            // } else {
                                            //     $data['error'] = 1;
                                            //     $data['error_desc'] = 'Plan For Invalid And You Have No Permission To Create Plan For This Role';
                                            //     $data['msg'] = NULL;
                                            // }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Plan Code Already Exist';
                                            $data['msg'] = NULL;
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Plan Code Invalid';
                                        $data['msg'] = NULL;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Plan Name Already Exist';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Plan Name Invalid';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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



     public function PlanServiceDataInsert() {
        $params = $this->input->post();
        $b = All_Regex();
        $PlanId = isset($params['PlanId'])?$params['PlanId']:'';
        $Service = isset($params['service'])?$params['service']:'';
        $PlanId = trim($PlanId);
        $chargeType = $b['ChargeType'];
        $chargeMethod = $b['ChargeMethod'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {
                            if(is_array($Service)&& count($Service)>0){
                            foreach ($Service as $key => $value) {

                                if ($value['ServiceName'] == '') {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Service Name Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if (!in_array($value['ChargeType'], array_keys($chargeType))) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Charge Type Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if (!in_array($value['ChargeMethod'], array_keys($chargeMethod))) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Charge Method Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if (!preg_match('/' . $b['Rate'] . '/', $value['Rate'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if ($value['CappingAmount'] != '') {
                                    if (!preg_match('/' . $b['Rate'] . '/', $value['CappingAmount'])) {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Capping Amount Invalid';
                                        $data['msg'] = NULL;
                                        json_encode($data);
                                        break;
                                    }
                                }
                            }

                            $PlanDataServiceAdd = $this->Main_model->PlanDataServiceAdd($Service, $PlanId, $user_info['user_id']);

                            if ($PlanDataServiceAdd) {
                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = 'Plan Service Successfully Added';
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Plan Service Not Successfully Added';
                                $data['msg'] = NULL;
                            }

                        }else{
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Service Not Available';
                                        $data['msg'] = NULL;
                                        echo json_encode($data);
                                        
                                        exit;
                        }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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


     public function FetchplanServiceList() {
        $PlanId = $this->input->post('data');
        $PlanId = trim($PlanId);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($PlanId)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {

                            $PlanServiceListData = $this->Main_model->PlanServiceListData($PlanId);
                            $data['error_data'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = NULL;
                            $data['data'] = $PlanServiceListData ? $PlanServiceListData : [];
                        } else {
                            $data['error_data'] = 2;
                            $data['error_desc'] = 'Access denied';
                            $data['msg'] = NULL;
                            $data['data'] = array();
                            $this->session->sess_destroy();
                        }
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }



      public function PlanServiceDataUpdate() {
        $params = $this->input->post();
        $b = All_Regex();
        $PlanId = isset($params['PlanId'])?$params['PlanId']:'';
        $Service =isset($params['service'])?$params['service']:'' ;
   
        $PlanId = trim($PlanId);
       
        $chargeType = $b['ChargeType'];
        $chargeMethod = $b['ChargeMethod'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
            
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {
                            $Plan=$this->Main_model->plan_name_by_planid($PlanId);
                            if(is_array($Service)&& count($Service)>0){

                            foreach ($Service as $key => $value) {

                                if ($value['ServiceName'] == '') {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Service Name Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                   exit;
                                }

                                if (!in_array($value['ChargeType'], array_keys($chargeType))) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Charge Type Invalid';
                                    $data['msg'] = NULL;
                                   echo json_encode($data);
                                   
                                    exit;
                                }

                                if (!in_array($value['ChargeMethod'],array_keys($chargeMethod))) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Charge Method Invalid';
                                    $data['msg'] = NULL;
                                   echo json_encode($data);
                                   
                                    exit;
                                }

                                if (!preg_match('/' . $b['Rate'] . '/', $value['Rate'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate Invalid';
                                    $data['msg'] = NULL;
                                   echo json_encode($data);
                                   
                                    exit;
                                }

                                if ($value['CappingAmount'] != '') {
                                    if (!preg_match('/' . $b['Rate'] . '/', $value['CappingAmount'])) {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Capping Amount Invalid';
                                        $data['msg'] = NULL;
                                      echo  json_encode($data);
                                       
                                        exit;
                                    }
                                }
                            }

                    $PlanDataServiceUpdate = $this->Main_model->PlanDataServiceUpdate($Service, $PlanId, $user_info['user_id']);

                            if ($PlanDataServiceUpdate) {
                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = 'Margin Successfully Updated for '.$Plan['plan_name'];
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Plan Service Not Successfully Updated';
                                $data['msg'] = NULL;
                            }

                        }else{
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Service Not Available';
                                        $data['msg'] = NULL;
                                        echo json_encode($data);
                                        
                                        exit;
                        }

                            
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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


      public function PlanDataSlabRates() {
        $PlanId = $this->input->post('data');
        $PlanId = trim($PlanId);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($PlanId)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {
                            $PlanSlabApplicableListData = $this->Main_model->PlanSlabApplicableListData($PlanId);
                         
                            $data['error_data'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = NULL;
                            $data['data'] = $PlanSlabApplicableListData ? $PlanSlabApplicableListData : [];
                        } else {
                            $data['error_data'] = 2;
                            $data['error_desc'] = 'Access denied';
                            $data['msg'] = NULL;
                            $data['data'] = array();
                            $this->session->sess_destroy();
                        }
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
                
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


    public function FetchSlabRates() {
        $ServiceConfig = $this->input->post('data');
        $ServiceConfig = trim($ServiceConfig);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($ServiceConfig)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {

                        if ($user_info['role_id'] == 1) {

                            $PlanSlabApplicableList = $this->Main_model->PlanSlabApplicableList($ServiceConfig);

                            $data['error_data'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = NULL;
                            $data['data'] = $PlanSlabApplicableList ? $PlanSlabApplicableList : [];

                        } else {

                            $data['error_data'] = 2;
                            $data['error_desc'] = 'Access denied';
                            $data['msg'] = NULL;
                            $data['data'] = array();
                            $this->session->sess_destroy();
                        }

                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }



       public function PlanSlabRatesInsert() {
        $b = All_Regex();
        $ServiceConfigId = $this->input->post('ServiceConfigId');
        $SlabRates = $this->input->post('SlabRates');

        $ServiceConfigId = trim($ServiceConfigId);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($ServiceConfigId)) {
          
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        $checkServiceConfigExist = $this->Main_model->checkServiceConfigExist($ServiceConfigId);
                        if ($checkServiceConfigExist) {     
                           
                            foreach ($SlabRates as $key => $value) {

                                if (!preg_match('/' . $b['Rate'] . '/', $value['SlabMinimumAmount'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Slab Minimum Amount Invalid';
                                    $data['msg'] = NULL;
                                    echo json_encode($data);
                                    exit;
                                }

                                if (!preg_match('/' . $b['Rate'] . '/', $value['SlabMaximunAmount'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Slab Maximun Amount Invalid';
                                    $data['msg'] = NULL;
                                    echo json_encode($data);
                                    exit;
                                }

                                if (!preg_match('/' . $b['Rate'] . '/', $value['SlabRate'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Slab Rate Invalid';
                                    $data['msg'] = NULL;
                                    echo json_encode($data);
                                    exit;
                                }
                                if ($value['SlabMaximunAmount'] != '') {
                                    if ($value['SlabMaximunAmount'] <= $value['SlabMinimumAmount']) {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Slab Maximun Amount Should Be Grater Than Slab Minimum Amount';
                                        $data['msg'] = NULL;
                                        echo json_encode($data);
                                        exit;
                                    }
                                }

                                if ($key != 0) {
                                    $A = $value['SlabMinimumAmount'];
                                    $B = $SlabRates[$key - 1]['SlabMaximunAmount']+1;

                                    if ($B != '') {

                                        $NewB = $B;

                                        if ($NewB == $A) {
                                            $dataErr = TRUE;
                                        } else {
                                            $dataErr = FALSE;
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Slab Range Must Be In Sequence';
                                            $data['msg'] = NULL;
                                            echo json_encode($data);
                                            exit;
                                        }
                                    } else {
                                        $dataErr = FALSE;
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Invalid Slab Range';
                                        $data['msg'] = NULL;
                                        echo json_encode($data);
                                        exit;
                                    }
                                }
                            }
                            $insertSlabData = array();
                            foreach ($SlabRates as $k => $v) {
                                $insertSlabData[$k] = array('plan_id'=>$checkServiceConfigExist['plan_id'],'service_id'=>$checkServiceConfigExist['service_id'],'min_amnt' => $v['SlabMinimumAmount'], 'max_amnt' => ($v['SlabMaximunAmount'] != '') ? $v['SlabMaximunAmount'] : NULl, 'rate' => $v['SlabRate']);
                            }

                            $SlabRatesDataInsert = $this->Main_model->SlabRatesDataInsert($checkServiceConfigExist['plan_id'],$checkServiceConfigExist['service_id'], $insertSlabData);

                            if ($SlabRatesDataInsert) {

                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = 'Plan Slab Added Successfully';

                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Plan Slab Not Added Successfully';
                                $data['msg'] = NULL;
                            }

                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Service Not Exist';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


        public function VendorDataEdit(){
       
        $VendorName = $this->input->post('VendorName');
        $VendorCode = $this->input->post('VendorCode');
        $VendorLibrary = $this->input->post('VendorLibrary');
        //$VendorType = $this->input->post('VendorType');
        $CompanyInfo = $this->input->post('CompanyInfo');
        $BillingContactsTo = $this->input->post('BillingContactsTo');
        $BillingContactsCC = $this->input->post('BillingContactsCC');
        $SupportContactsTo = $this->input->post('SupportContactsTo');
        $SupportContactsCC = $this->input->post('SupportContactsCC');
        $BalanceCheckApi = $this->input->post('BalanceCheckApi');
        $ApiStatus = $this->input->post('ApiStatus');
        $IsDown = $this->input->post('IsDown');
        $IsActive = $this->input->post('IsActive');
        $Id = $this->input->post('Id');

        $VendorName = trim($VendorName);
        $VendorCode = trim($VendorCode);
        $VendorLibrary = trim($VendorLibrary);
        //$VendorType = trim($VendorType);
        $CompanyInfo = trim($CompanyInfo);
        $BillingContactsTo = trim($BillingContactsTo);
        $BillingContactsCC = trim($BillingContactsCC);
        $SupportContactsTo = trim($SupportContactsTo);
        $SupportContactsCC = trim($SupportContactsCC);
        $BalanceCheckApi = trim($BalanceCheckApi);
        $ApiStatus = trim($ApiStatus);
        $IsDown = trim($IsDown);
        $IsActive = trim($IsActive);
        $Id = trim($Id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {
                            $checkVendorName = $this->Main_model->CheckVendorNameDataOnUpdate($VendorName, $Id);
                            if (!$checkVendorName) {
                                   $checkVendorCode = $this->Main_model->CheckVendorCodeDataOnUpdate($VendorCode, $Id);
                            if (!$checkVendorCode) {

                                $VendorDataEdit = $this->Main_model->VendorDataEdit($VendorName, $VendorCode, $VendorLibrary,$CompanyInfo, $BillingContactsTo, $BillingContactsCC, $SupportContactsTo, $SupportContactsCC, $BalanceCheckApi
                                        , $ApiStatus, $IsDown, $IsActive, $Id);

                                if ($VendorDataEdit) {   

                                    $data['error'] = 0;
                                    $data['error_desc'] = NULL;
                                    $data['msg'] = 'Vendor Successfully Updated';

                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Something Went Wrong';
                                    $data['msg'] = NULL;

                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Vendor Code Already Exist';
                                $data['msg'] = NULL;

                            }
                             } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Vendor Name Already Exist';
                                $data['msg'] = NULL;

                            }
                        } else {

                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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


     public function VendorDataAdd() {

        $VendorName = $this->input->post('VendorName');
        $VendorCode = $this->input->post('VendorCode');
        $VendorLibrary = $this->input->post('VendorLibrary');
        //$VendorType = $this->input->post('VendorType');
        $CompanyInfo = $this->input->post('CompanyInfo');
        $BillingContactsTo = $this->input->post('BillingContactsTo');
        $BillingContactsCC = $this->input->post('BillingContactsCC');
        $SupportContactsTo = $this->input->post('SupportContactsTo');
        $SupportContactsCC = $this->input->post('SupportContactsCC');
        $BalanceCheckApi = $this->input->post('BalanceCheckApi');
        $ApiStatus = $this->input->post('ApiStatus');
        $IsDown = $this->input->post('IsDown');
        $IsActive = $this->input->post('IsActive');

        // if (!empty($this->input->post('Bank'))) {
        //     $Vendorbank = $this->input->post('Bank')['VendorBank'];
        //     $VendorIFSC = $this->input->post('Bank')['VendorIFSC'];
        //     $VendorAccount = $this->input->post('Bank')['VendorAccount'];
        //     $VendorBranch = $this->input->post('Bank')['VendorBranch'];

        //     $Vendorbank = trim($Vendorbank);
        //     $VendorIFSC = trim($VendorIFSC);
        //     $VendorAccount = trim($VendorAccount);
        //     $VendorBranch = trim($VendorBranch);
        // }

        $VendorName = trim($VendorName);
        $VendorCode = trim($VendorCode);
        $VendorLibrary = trim($VendorLibrary);
        //$VendorType = trim($VendorType);
        $CompanyInfo = trim($CompanyInfo);
        $BillingContactsTo = trim($BillingContactsTo);
        $BillingContactsCC = trim($BillingContactsCC);
        $SupportContactsTo = trim($SupportContactsTo);
        $SupportContactsCC = trim($SupportContactsCC);
        $BalanceCheckApi = trim($BalanceCheckApi);
        $ApiStatus = trim($ApiStatus);
        $IsDown = trim($IsDown);
        $IsActive = trim($IsActive);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {

                            $checkVendorCode = $this->Main_model->CheckVendorCodeData($VendorCode);
                            if (!$checkVendorCode) {
                            $checkVendorName = $this->Main_model->CheckVendorNameData($VendorName);
                            //print_r($checkVendorName);exit;
                            if(!$checkVendorName){
                            // $VendorDataAdd = $this->Main_model->VendorDataAdd($VendorName, $VendorCode, $VendorLibrary, $CompanyInfo, $BillingContactsTo, $BillingContactsCC, $SupportContactsTo, $SupportContactsCC, $BalanceCheckApi, $ApiStatus, $IsDown, $IsActive, $Vendorbank, $VendorIFSC, $VendorAccount, $VendorBranch);
                             $VendorDataAdd = $this->Main_model->VendorDataAdd($VendorName, $VendorCode, $VendorLibrary, $CompanyInfo, $BillingContactsTo, $BillingContactsCC, $SupportContactsTo, $SupportContactsCC, $BalanceCheckApi, $ApiStatus, $IsDown, $IsActive);

                                if ($VendorDataAdd) {

                                    $data['error'] = 0;
                                    $data['error_desc'] = NULL;
                                    $data['msg'] = 'Vendor Successfully Added';

                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Vendor Not Successfully Added';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Vendor Name Already Exist';
                                $data['msg'] = NULL;
                            }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Vendor Code Already Exist';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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


      public function VendorServiceConfigFetch(){
       $VendorId = $this->input->post('data');

        $VendorId = trim($VendorId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {

                            $getAllDataVendor = $this->Main_model->getAllDataVendor($VendorId);
                            if ($getAllDataVendor) {
                                $FetchServiceVendorConfigData = $this->Main_model->FetchServiceVendorConfigData($VendorId);

                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = 'Vendor Service Config Successfully Fetch';
                                $data['data'] = $FetchServiceVendorConfigData ? $FetchServiceVendorConfigData : [];
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Vendor Data Not Exist';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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


    public function VendorServiceFetchAll(){
        $vendorid = $this->input->post('data');
        $vendorid = trim($vendorid);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($vendorid)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {

                            $FetchAllVendorService = $this->Main_model->FetchAllVendorService($vendorid);

                            $data['error'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = 'Service Successfully Fetch';
                            $data['data'] = $FetchAllVendorService ? $FetchAllVendorService : [];
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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


    public function VendorServiceConfigAdd(){

       $params = $this->input->post();
    
        $b = All_Regex();
        $VendorId = $params['VendorId'];
        $Service = $params['service'];

        $VendorId = trim($VendorId);


        $chargeType = ['FIXED' => 'FIXED', 'PERCENT' => 'PERCENT'];
        $chargeMethod = ['CREDIT' => 'CREDIT', 'DEBIT' => 'DEBIT'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {

                            foreach ($Service as $key => $value) {

                                if ($value['ServiceName'] == '') {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Service Name Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                $checkServiceKey = $this->Main_model->checkServiceKey($value['ServiceKey']);

                                if ($checkServiceKey) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Service Key Already Exist';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if (!in_array($value['ChargeType'], $chargeType)) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate Charge Type Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if (!in_array($value['ChargeMethod'], $chargeMethod)) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Rate Charge Method Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if (!preg_match('/' . $b['Rate'] . '/', $value['Margin'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Margin Invalid';
                                    $data['msg'] = NULL;
                                    json_encode($data);
                                    break;
                                }

                                if ($value['CappingAmount'] != '') {
                                    if (!preg_match('/' . $b['Rate'] . '/', $value['CappingAmount'])) {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Capping Amount Invalid';
                                        $data['msg'] = NULL;
                                        json_encode($data);
                                        break;
                                    }
                                }
                            }

                            $vendorDataServiceAdd = $this->Main_model->vendorDataServiceAdd($Service, $VendorId, $user_info['user_id']);

                            if ($vendorDataServiceAdd) {
                                $data['error'] = 0;
                                $data['error_desc'] = NULL;
                                $data['msg'] = 'Vendor Service Successfully Added';
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Vendor Service Not Successfully Added';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Unauthorised Access';
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


     public function all_services() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                        if ($user_info['role_id'] == 1) {
                            $all_services = $this->Main_model->all_service_provder();
                           foreach ($all_services as $v => $k) {

                                $service_amnt_rng = $this->Main_model->ServiceAmountRange($k['service_id']);
//                               
                                $all_services[$v]['Vendor'] = $service_amnt_rng ? $service_amnt_rng : array();
                            }
                            if ($all_services) {
                                $result = array();

                                $data['data'] = $all_services;
                            } else {
                                $data['data'] = array();
                            }
                        } else {
                            $data['error_data'] = 2;
                            $data['error_desc'] = 'Unauthorised process';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }

    public function FetchServiceAmntRange(){
        $ServiceConfig = $this->input->post('data');
        $ServiceConfig = trim($ServiceConfig);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($ServiceConfig)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {

                        if ($user_info['role_id'] == 1) {

                            $ServiceAmountRange = $this->Main_model->ServiceAmountRange($ServiceConfig);

                            $data['error_data'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = NULL;
                            $data['data'] = $ServiceAmountRange ? $ServiceAmountRange : [];

                        } else {

                            $data['error_data'] = 2;
                            $data['error_desc'] = 'Access denied';
                            $data['msg'] = NULL;
                            $data['data'] = array();
                            $this->session->sess_destroy();
                        }

                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();
                    }
               
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }
public function ManageServiceData(){
    $b = All_Regex();
    $params=$this->input->post();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($params)) {
    $e = $this->session->userdata('userid');
    $user_info = $this->Main_model->user_acntid($e);
    $data = array();
    if ($user_info) {
    if ($user_info['is_block'] == 0) {
        $upDown = $params['upDown'];
        $activeInactive = $params['activeInactive'];
        $autoSwitching = $params['autoswitch'];
        $serviceId = $params['serviceId'];
        $service_dtl= $this->Main_model->Servicedtl_by_servcid($serviceId);
        if($service_dtl){
        if ($autoSwitching == 'true') {
            $routeDat = $params['supported'];

                 foreach ($routeDat as $key => $value) {
                          if (!preg_match('/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/', $value['minamount'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Service Minimum Amount Invalid';
                                    $data['msg'] = NULL;
                                    echo json_encode($data);
                                    exit;
                                }

                                if (!preg_match('/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/', $value['maxamount'])) {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Service Maximun Amount Invalid';
                                    $data['msg'] = NULL;
                                    echo json_encode($data);
                                    exit;
                                }

                                
                                if ($value['maxamount'] != '') {
                                    if ($value['maxamount'] <= $value['minamount']) {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Service Maximun Amount Should Be Grater Than Service Minimum Amount';
                                        $data['msg'] = NULL;
                                        echo json_encode($data);
                                        exit;
                                    }
                                }

                                if ($key != 0) {
                                    $A = $value['minamount'];
                                    $B = $routeDat[$key - 1]['maxamount']+1;

                                    if ($B != '') {

                                        $NewB = $B;

                                        if ($NewB == $A) {
                                            $dataErr = TRUE;
                                        } else {
                                            $dataErr = FALSE;
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Service Range Must Be In Sequence';
                                            $data['msg'] = NULL;
                                            echo json_encode($data);
                                            exit;
                                        }
                                    } else {
                                        $dataErr = FALSE;
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Invalid Service Range';
                                        $data['msg'] = NULL;
                                        echo json_encode($data);
                                        exit;
                                    }
                                }
                            }
         

                } else {

                        $routeDat = $params['Route'];
                       
                }

      $InsertData = $this->Main_model->ManageServiceData($upDown, $activeInactive, $autoSwitching, $routeDat, $serviceId, $user_info['user_id']);

                         
                            if ($InsertData) {

                                            $data['error'] = 0;
                                            $data['error_desc'] = null;
                                            $data['msg'] = 'Service Details '.$service_dtl['service_name'].' Has Been Updated Successfully';
                            } else {

                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Request Not Successfully Completed';
                                            $data['msg'] = null;   
                            }
                       } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid Service';
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
                    }
                    echo json_encode($data);
                    } else {
                            redirect('Dashboard');
                    }

                }

  public function Bank_fr_dffrnt_role(){

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {

                    $fetchBankName=array();

                    if ($user_info['parent_id'] == 0) {

                    $fetchBankName = $this->Main_model->fetch_bank_list();
                    
                    }else{

                    $parent_role = $this->Main_model->get_user_parent_role($user_info['parent_id']);

                    if($parent_role){
                        
                    if($parent_role['role_id']==1){

                    $fetchBankName = $this->Main_model->fetch_bank_list();


                    } else{  

                    $fetchBankName = ['ParentFirstName' => $parent_role['first_name'], 'ParentLastName' => $parent_role['last_name'], 'ParentMobile' => $parent_role['mobile']];

                   

                    } 

                    }else{

                    

                        $data['error'] = 1;
                        $data['error_desc'] = 'Unable To Process Request,Please contact admin';
                        $data['msg'] = NULL;


                    }

                    }

                        $data['error'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = 'ok';
                        $data['data'] = $fetchBankName ? $fetchBankName :array();

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

    public function PaymentRequestList(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $r = $this->session->userdata('role_id');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {

                        $PaymentRequest = $this->Main_model->fetch_paymnet_rqst_by_usr($user_info['user_id']);
                        $data['error_data'] = 0;
                        $data['error_desc'] = NULL;
                        $data['msg'] = NULL;
                        $data['data'] = $PaymentRequest ? $PaymentRequest : array();

                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['data'] = array();
                        $this->session->sess_destroy();   
                    }
                
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }

            echo json_encode($data);

        } else {

                redirect('Dashboard');
        }
    }  


        public function payment_req(){
        $b = All_Regex();
        $BankName = $this->input->post('BankName');
        $PaymentMode = $this->input->post('PaymentMode');
        $Amount = $this->input->post('Amount');
        $BankReferenceNumber = $this->input->post('BankReferenceNumber');
        $Remark = $this->input->post('Remark');

        $BankName = trim($BankName);
        $PaymentMode = trim($PaymentMode);
        $Amount = trim($Amount);
        $BankReferenceNumber = trim($BankReferenceNumber);
        $Remark = trim($Remark);

        $Mode = ['IMPS' => 'IMPS', 'NEFT' => 'NEFT', 'CASH' => 'CASH', 'OTHERS' => 'OTHERS'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        if ($BankName) {

                            $insertbankName = '';

                            if ($user_info['parent_id'] == 0) {

                                $bank_dtl = $this->Main_model->fetch_bank_dtl_rqst_by_usr($BankName);
                                if ($bank_dtl) {
                                    $insertbankName = $bank_dtl['bank_name'] . ' - ' . $bank_dtl['account_number'];
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Bank Details Not Exist';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                
                                $parent_role = $this->Main_model->get_user_parent_role($user_info['parent_id']);
                                if ($parent_role) {
                                    if ($parent_role['role_id'] == 1) {
                                     

                                    $fetchBankName = $this->Main_model->fetch_bank_dtl_rqst_by_usr($BankName);

                                        if ($fetchBankName) {
                                            $insertbankName = $fetchBankName['bank_name'] . ' - ' . $fetchBankName['account_number'];
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Bank Details Not Exist';
                                            $data['msg'] = NULL;
                                        }

                                    } else {

                                    $insertbankName = $parent_role['first_name'] . ' ' . $parent_role['last_name'] . ' (' . $parent_role['mobile'] . ') \'s Bank';

                                        if ($insertbankName != $BankName) {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Bank Details Not Valid';
                                            $data['msg'] = NULL;
                                        }
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable To Process Request,Please contact admin';
                                    $data['msg'] = NULL;
                                }
                            }

                            //if (preg_match('/' . $b['Name'] . '/', $BankName)) {

                                if (in_array($PaymentMode, $Mode)) {
                                    if ($Amount) {
                                        if (preg_match('/' . $b['Rate'] . '/', $Amount)) {
                                            if ($BankReferenceNumber) {
                                                if (preg_match('/' . $b['Text'] . '/', $BankReferenceNumber)) {
                                                    if ($Remark) {
                                                        if (preg_match('/' . $b['Text'] . '/', $Remark)) {

                                                            $paymentRequest = array(
                                                                'user_id' => $user_info['user_id'],
                                                                'bank_name' => $insertbankName,
                                                                'payment_mode' => $PaymentMode,
                                                                'amount' => $Amount,
                                                                'request_date' => date('Y-m-d H:i:s'),
                                                                'bank_ref_no' => $BankReferenceNumber,
                                                                'remarks' => $Remark,
                                                                'status' => 'PENDING',
                                                                'is_file' => 0,
                                                            );

                                                            if (isset($_FILES['File'])) {
                                                                $configRequestImage['upload_path'] = 'assets/PaymentRequest/';
                                                                $configRequestImage['allowed_types'] = 'gif|jpg|jpeg|png|pdf';

                                                                $this->load->library('upload', $configRequestImage);

                                                                if (!$this->upload->do_upload('File')) {
                                                                    $data['error'] = 1;
                                                                    $data['error_desc'] = $this->upload->display_errors();
                                                                    $data['msg'] = null;
                                                                    echo json_encode($data);
                                                                    exit;
                                                                } else {
                                                                    $upd_fileAboutUsImage = $this->upload->data();
                                                                    $file_path_aboutus = $configRequestImage['upload_path'] . $upd_fileAboutUsImage['file_name'];
                                                                    $paymentRequest['is_file'] = 1;
                                                                    $paymentRequest['file_path'] = $file_path_aboutus;
                                                                }
                                                            }

                        $InsertPaymentRequest = $this->Main_model->InsertPaymentRequest($paymentRequest);
                                                            if ($InsertPaymentRequest) {
                                                                $data['error'] = 0;
                                                                $data['error_desc'] = NULL;
                                                                $data['msg'] = 'Payment Request Successfully Sent';
                                                            } else {
                                                                $data['error'] = 1;
                                                                $data['error_desc'] = 'Something Went Wrong, Try Again';
                                                                $data['msg'] = NULL;
                                                            }
                                                        } else {
                                                            $data['error'] = 1;
                                                            $data['error_desc'] = 'Invalid Remark';
                                                            $data['msg'] = NULL;
                                                        }
                                                    } else {
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Invalid Remark';
                                                        $data['msg'] = NULL;
                                                    }
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Invalid Bank Ref. Number';
                                                    $data['msg'] = NULL;
                                                }
                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Invalid Bank Ref. Number';
                                                $data['msg'] = NULL;
                                            }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Invalid Amount';
                                            $data['msg'] = NULL;
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Invalid Amount';
                                        $data['msg'] = NULL;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Invalid Payment Mode';
                                    $data['msg'] = NULL;
                                }
                            // } else {
                            //     $data['error'] = 1;
                            //     $data['error_desc'] = 'Invalid Bank Name';
                            //     $data['msg'] = NULL;
                            // }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Bank Name Invalid';
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
                
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }

    }

       public function get_payment_reqst(){
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                if ($user_info['is_block'] == 0) {
                  
                $all_child = $this->Main_model->get_all_child_of_usr($user_info['user_id'],$user_info['role_id']);

                 
                     if ($all_child) {
                         $result = array();
                         $result['data']=array();

                            foreach ($all_child as $k => $v) {
                                $all_result = $this->Main_model->all_cld_dtl_payment_req($v['user_id']);

                                if($all_result){
                                    
                                    $result['data'] = array_merge($result['data'],$all_result);
                                }
                                 
                            }
                          
                            $data['error_data'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = NULL;
                            $data['data'] = $result['data'];


                        } else {
                             $result = array();
                        }
                    } else {
                        $data['error_data'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                         $data['data'] = array();
                       
                        
                    }
                
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                 $data['data'] = array();
                $this->session->sess_destroy();

                
            }

            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }  
    }



        public function AcceptPaymentRequest(){
        $PaymentId = $this->input->post('tableauto_id');
        $BankName = $this->input->post('bank_name');
        $PaymentMode = $this->input->post('payment_mode');
        $Amount = $this->input->post('amount');
        $BankRefNo = $this->input->post('bank_ref_no');
        $Remarks = $this->input->post('remarks');

        $PaymentId = trim($PaymentId);
        $BankName = trim($BankName);
        $PaymentMode = trim($PaymentMode);
        $Amount = trim($Amount);
        $BankRefNo = trim($BankRefNo);
        $Remarks = trim($Remarks);  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $e = $this->session->userdata('userid');
        $user_info = $this->Main_model->user_acntid($e);
        $data = array();
        if($user_info){   
        if ($user_info['is_block'] == 0) {
        $payuser = $this->Main_model->FetchPaymentRequestDetails($PaymentId);
        if($payuser){
        if ($payuser['status'] == 'PENDING') {
        $payuser_id = $payuser['user_id'];
        $usrdtl = $this->Main_model->user_acntid($payuser_id);
        if($usrdtl){
         $chck_is_paymnt_approv=$this->Main_model->check_usr_pymnt_rqst_crdtd_chtstry($BankRefNo,$Amount,$payuser_id);
        if(!$chck_is_paymnt_approv){
       
        if ($usrdtl['is_active'] == 1) {
        if ($usrdtl['is_block'] == 0) {
        if($user_info['parent_id']==0){

         /******admin case****/                          
            $opn_blnc = $usrdtl['rupee_balance'];
            $rqst_blnc = $Amount;
            $cls_blnc = $opn_blnc + $rqst_blnc;
           if ($cls_blnc >= 0) {
                                                    $rtlr_txnid = ch_txnid();
                                                    $insert_cdt_hstry = array(
                                                        'credit_txnid' =>$rtlr_txnid,
                                                        'user_id' => $payuser['user_id'],
                                                        'bank_name' => $BankName,
                                                        'txn_type' => 'DEPOSIT',
                                                        'payment_mode' => $PaymentMode,
                                                        'amount' => $Amount,
                                                        'opening_balance' => $opn_blnc,
                                                        'closing_balance' => $cls_blnc,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => $BankRefNo,
                                                        'remarks' => $Remarks,
                                                        'txn_code' => admn_trnsfer_cd(),
                                                        'status' => 'CREDIT',
                                                        'updated_by' => $user_info['user_id'],
                                                        'created_on'=>date('Y-m-d H:i:s'),
                                                        'created_by'=>$user_info['user_id']
                                                    );
                                                    $update_pymnt_rqst = array(
                                                        'status' => 'APPROVED',
                                                        'updated_dt' => date('Y-m-d H:i:s'),
                                                        'updated_by' => $user_info['user_id'],
                                                    );
                                                    $get_data = $this->Main_model->update_aprv_pymnt_rqst_by_admin($PaymentId, $insert_cdt_hstry, $payuser['user_id'], $update_pymnt_rqst);

                                                    if ($get_data) {
                                                        //////////////// send msg//////////////

                                                        
                                                        $data['error'] = 0;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = 'Payment Request Approved Sucessfully';
                                                    } else {
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Something Went wrong';
                                                        $data['msg'] = null;
                                                    }
                                                } else {

                                                    $data['error'] = 1;
                                                    $data['msg'] = null;
                                                    $data['error_desc'] = 'Internal processing error';
                                                }  
        } else{

        $parent_role = $this->Main_model->get_user_parent_role($usrdtl['parent_id']);

        if ($parent_role) {
            // print_r($parent_role);exit;
        /******super distributor and distributor case********/

        $opn_blnc = $usrdtl['rupee_balance'];
        $rqst_blnc = $Amount;
        $stsdtsr = 'DEBIT';
        $stsrtlr = 'CREDIT';
        $opn_blnc_ofdbstr = $user_info['rupee_balance'];
        $cls_blnc_dstr = $opn_blnc_ofdbstr - $rqst_blnc;
        $cls_blnc_rtlr = $opn_blnc + $rqst_blnc;


        if ($opn_blnc_ofdbstr >= $Amount) {
                                                    $dist_txn_id = ch_txnid();
                                                    $rtlr_txn_id = ch_txnid();
                                                    $insert_dstbtr = array(
                                                       /***dist debit entry***/
                                                        'credit_txnid' =>$dist_txn_id,
                                                    
                                                        'user_id' =>$user_info['user_id'],
                                                        'bank_name' => $BankName,
                                                        'txn_type' => 'TRANSFER FROM',
                                                        'payment_mode' => $PaymentMode,
                                                        'amount' => $Amount,
                                                        'opening_balance' => $opn_blnc_ofdbstr,
                                                        'closing_balance' => $cls_blnc_dstr,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => $BankRefNo,
                                                        'remarks' => $Remarks,
                                                        'txn_code' => dstrbtr_trnsfer_cd(),
                                                        'status' =>$stsdtsr,
                                                        'updated_by' => $user_info['user_id'],
                                                        'created_on'=>date('Y-m-d H:i:s'),
                                                        'created_by'=>$user_info['user_id']
                                                    );
                                                    $insert_retlr = array(
                                                       
                                                        'credit_txnid' =>$rtlr_txn_id,
                                                        'user_id' => $payuser['user_id'],//retlr user id
                                                       
                                                        'bank_name' => $BankName,
                                                        'txn_type' => 'TRANSFER To',
                                                        'payment_mode' => $PaymentMode,
                                                        'amount' => $Amount,
                                                        'opening_balance' => $opn_blnc,
                                                        'closing_balance' => $cls_blnc_rtlr,
                                                        'updated_on' => date('Y-m-d H:i:s'),
                                                        'reference_number' => $BankRefNo,
                                                        'remarks' => $Remarks,
                                                        'txn_code' => dstrbtr_trnsfer_cd(),
                                                        'status' =>$stsrtlr,
                                                        'updated_by' => $user_info['user_id'],
                                                        'created_on'=>date('Y-m-d H:i:s'),
                                                        'created_by'=>$user_info['user_id']

                                                    );
                                                    $update_pymnt_rqst = array(
                                                        'status' => 'APPROVED',
                                                        'updated_dt' => date('Y-m-d H:i:s'),
                                                        'updated_by' => $user_info['user_id'],
                                                    );

                                                    $get_data = $this->Main_model->update_aprv_pymnt_rqst_by_distbtr($PaymentId, $insert_dstbtr, $insert_retlr, $update_pymnt_rqst);

                                                    if ($get_data) {


                                                        //////////////// send msg//////////////

                                                      

                                                        $data['error'] = 0;
                                                        $data['error_desc'] = null;
                                                        $data['msg'] = 'Payment Request Approved Sucessfully';
                                                    } else {
                                                        $data['error'] = 1;
                                                        $data['error_desc'] = 'Something Went wrong';
                                                        $data['msg'] = null;
                                                    }
                                                } else {

                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Wallet Balnace Is Less Then Payment Request';
                                                    $data['msg'] = null;
                                                }


        

        }else{
                $data['error_data'] = 1;
                $data['error_desc'] = 'Unable To Process Request,Please contact admin';
                $data['msg'] = NULL;
        }

        } 
       
        } else {

                    $data['error'] = 1;
                    $data['error_desc'] = 'Unable to fetch user details';
                    $data['msg'] = null;
        }
        } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'User account inactive';
                    $data['msg'] = null;
        }

       
        }else{

                $data['error'] = 1;
                $data['error_desc'] = 'Payment Request Already Approved';
                $data['msg'] = null;
        }
        
        }else{

                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = null;

        }
         } else {

                $data['error'] = 1;
                $data['error_desc'] = 'Payment Request Can Not Be Rejected, Current Status ' . $payuser['status'];
                $data['msg'] = null;
                            
        }
        
        }else{

                $data['error'] = 1;
                $data['error_desc'] = 'Payment Request Not Found';
                $data['msg'] = null;
        }

        }else {
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

        }else{
            redirect('Dashboard');
        }
    }

     public function RejectPaymentRequest(){
      
       $PaymentId = $this->input->post('tableauto_id');

        $PaymentId = trim($PaymentId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
              
                    if ($user_info['is_block'] == 0) {
                        $payuser = $this->Main_model->fetch_paymt_dtl($PaymentId);
                        if ($payuser) {
                            if ($payuser['status'] == 'PENDING') {
                                $payuser_id = $payuser['user_id'];
                                $usrdtl_of_pymnt = $this->Main_model->user_acntid($payuser_id);
                                if ($usrdtl_of_pymnt) {
                                   
                                    $PaymentRequestReject = $this->Main_model->RejectPaymentRequest($PaymentId, $user_info['user_id']);

                                    if ($PaymentRequestReject) {
                                        $data['error'] = 0;
                                        $data['error_desc'] = null;
                                        $data['msg'] = 'Payment Request Has Been Rejected';
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something Went Wrong, Try Again Later';
                                        $data['msg'] = null;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Invalid Request';
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Payment Request Can Not Be Rejected, Current Status ' . $payuser['status'];
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Payment Request Not Found';
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


    public function Notifications(){
        $e = $this->session->userdata('userid'); 
        $user_info=$this->Main_model->user_acntid($e);
        if($user_info){
            if($user_info['is_block']==0){  
                if($user_info['role_id']==1) {
                    $roles['allroles'] = $this->Main_model->get_all_roles();
                    $this->load->view('Dashboard/templates/header');
                    $this->load->view('Manage/manage_sidebar');
                    $this->load->view('Manage/notifications', $roles);
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

   
    public function notification_list(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if($user_info['is_block']==0 && $user_info['role_id']==1){  
                    $data['error_data'] = 0;
                    $data['error_desc'] = NULL;
                    $data['msg'] = NULL;
                    $data['data'] = $this->Main_model->getnoteData();
                } else {
                    $data['error_data'] = 1;
                    $data['error_desc'] = 'Access denied';
                    $data['msg'] = NULL;
                    $data['data'] = array();
                    
                }
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data'] = array();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }   
    }


    function Edit_Notif() {
        $cuid = $this->input->post('editrow');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array();
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                if($user_info['is_block']==0 && $user_info['role_id']==1){
                    $fetchdata = $this->Main_model->getnoteEditData($cuid);
                    // $roles = $this->Main_model->all_roles();
                    if ($fetchdata) {
                        $data['error'] = 0;
                        $data['error_desc'] = null;
                        $data['data'] = $fetchdata; 
                        $data['msg'] = null;
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Invalid Request';
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
            redirect('Dashboard');
        }
    }


    function Notification_Add() {
                    // echo '<pre>';print_r($_POST);
            $heading = $this->input->post('eheading');
            $content = $this->input->post('econtent');
            $validup = $this->input->post('evalidupto');
            $notifor = $this->input->post('enotifor');
        
        

        if (isset($heading) && isset($content) && isset($validup) && isset($notifor) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array();
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                if($user_info['is_block']==0 && $user_info['role_id']==1){
                    $get = $this->Main_model->InsertNotification($heading, $content, $validup, $notifor);
                    if ($get) {
                        $data['error'] = 0;
                        $data['error_desc'] = null;
                        $data['msg'] = 'Notification has been added.';
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Something Went Wrong.';
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
            redirect('Dashboard');
        }
    }

    function Notification_Edit() {
        $heading = $this->input->post('eheading');
        $content = $this->input->post('econtent');
        $validup = $this->input->post('evalidupto');
        $notifor = $this->input->post('enotifor');
        $editrow = $this->input->post('editrow');
        if (isset($heading) && isset($content) && isset($validup) && isset($notifor) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array();
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                if($user_info['is_block']==0 && $user_info['role_id']==1){
                    $get = $this->Main_model->UpdateNotification($heading, $content, $validup, $notifor, $editrow);
                    if ($get) {
                        $data['error'] = 0;
                        $data['error_desc'] = null;
                        $data['msg'] = 'Notification has been Updated.';
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Something Went Wrong.';
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
            redirect('Dashboard');
        }
    }




    function deleteNotif() {
        $reqdelid = $this->input->post('reqdelid');
        if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                if($user_info['is_block']==0 && $user_info['role_id']==1){
                    $get = $this->Main_model->delNoteRow($reqdelid);
                    if ($get) {
                        $data['error'] = 0;
                        $data['error_desc'] = null;
                        $data['msg'] = 'Notification has been deleted.';
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'Something Went wrong, Try again Later';
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
            redirect('Dashboard');
        }
    }

    

}

?>
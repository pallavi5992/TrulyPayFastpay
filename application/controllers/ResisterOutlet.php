<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResisterOutlet extends CI_Controller {
    
    
    public function request_for_outlet_registration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            $params=$this->input->post();
            if ($user_info) 
            {
                if ($user_info['is_block'] == 0) 
                {
                    $chk_bc_contract = $this->Main_model->check_outlet_id($user_info['user_id']);
                    if($chk_bc_contract)
                    {
                        
                        if ($chk_bc_contract['kyc_apibox'] == NULL) {

                            $params['req_for']='REQUESTOUTLETREGISTER';
                            
                            $data = $this->request_for_outlet_action($params, $user_info);

                        } else {

                                $data['error'] = 3;
                                $data['msg'] = 'Service already active';
                                $data['error_desc'] = null;
                        }
                        
                    }else{
                        $params['req_for']='REQUESTOUTLETREGISTER';
                        
                        $data = $this->request_for_outlet_action($params, $user_info);
                    }
                    
                }else{
                    $data['error'] = 2;
                    $data['error_desc'] = 'Invalid Request';
                    $data['msg'] = NULL;
                    $this->session->sess_destroy();
                }
                
            }else{
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
    
    public function validate_otp_for_registerservice()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            $params=$this->input->post();
            if ($user_info) 
            {
                if ($user_info['is_block'] == 0) 
                {
                    $chk_bc_contract = $this->Main_model->check_outlet_id($user_info['user_id']);
                    if($chk_bc_contract)
                    {
                        
                        if ($chk_bc_contract['kyc_apibox'] == NULL) {

                            $params['req_for']='VALIDATEOUTLETOTP';
                            
                            $data = $this->request_for_outlet_action($params, $user_info);

                        } else {

                                $data['error'] = 3;
                                $data['msg'] = 'Service already active';
                                $data['error_desc'] = null;
                        }
                        
                    }else{
                        
                        $params['req_for']='VALIDATEOUTLETOTP';
                            
                        $data = $this->request_for_outlet_action($params, $user_info);
                    }
                    
                }else{
                    $data['error'] = 2;
                    $data['error_desc'] = 'Invalid Request';
                    $data['msg'] = NULL;
                    $this->session->sess_destroy();
                }
                
            }else{
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
    
    private function request_for_outlet_action($params,$user_info)
    {
        $data=array();
        if ($user_info) 
        {
            $get_vendor_details=$this->Main_model->get_vendor_details_for_outlet_request();
            
            if($get_vendor_details)
            {
                
                $library=$get_vendor_details['vendor_library'];
                if ($get_vendor_details['is_down'] == 0)
                {

                   

                if (file_exists(APPPATH . "libraries/" . ucfirst($library) . ".php")) 
                {

                    $this->load->library($library);

                    if (method_exists($this->{$library}, 'request_outlet_action'))
                        {   

                            $result = $this->{$library}->request_outlet_action($user_info, $params,$get_vendor_details);
                            $data = $result;
                            if(!$data)
                            {
                                $data['error'] = 1;
                                $data['error_desc'] = "Something went wrong, try again later";
                                $data['msg'] = null;
                            }
                         
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

                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = "Service Down, Try again later";
                    $data['msg'] = null;
                }
                
            }else{
                $data['error']=1;
                $data['error_desc']='Unable to process your request';
                $data['msg']=null;
            }
            
        }else{
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $this->session->sess_destroy();
        }
        return $data;
    }

	 public function send_fr_outlet_reg(){
        $mobile = $this->input->post('mob');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {   
                if($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                        $chk_bc_contract = $this->Main_model->check_outlet_id($user_info['user_id']);
                        if ($chk_bc_contract) {
                            //print_r($chk_bc_contract);exit;
                        if ($chk_bc_contract['kyc_apibox'] == NULL) {

                            $data = $this->send_outlet_otp_request($mobile, $user_info);

                        } else {

                                $data['error'] = 3;
                                $data['msg'] = null;
                                $data['error_desc'] = 'Service already active';
                        }

                        } else {

                            $data = $this->send_outlet_otp_request($mobile, $user_info);

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

    public function get_outlet_kycstats() {  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                //if (page_access(9)) {
                if ($user_info['is_block'] == 0) {
                    $check_outlet_id = $this->Main_model->check_outlet_id($user_info['user_id']);
                if ($check_outlet_id) {
                if ($check_outlet_id['kyc_apibox'] == 'PENDING') {
                $chkservicestat['vendor_library'] = trim('instanpayapi');
                if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                $this->load->library($chkservicestat['vendor_library']);
                $mobile = $user_info['mobile'];
                $data = $this->{$chkservicestat['vendor_library']}->request_outlet_kyc($user_info, $check_outlet_id);
                if($data){
                //      $response=array(
                //     "statuscode"=>"TXN",
                //     "status"=>"Transaction Successful",
                //     "data"=>array(
                //         "APPROVED"=>[["14","Identity & Address Proof","MANDATORY"]],
                //         "SCREENING"=>[["14","Identity & Address Proof","MANDATORY"]],
                //         "REQUIRED"=>[["1","PAN Card","OPTIONAL"]]

                //     )


                // );

                 
                                    $data['error'] = 0;  
                                    $data['error_desc'] = null;
                                    $data['user_id'] =$user_info['user_id'];
                                    $data['response'] = array(
                        "APPROVED"=>[["14","Identity & Address Proof","MANDATORY"]],
                         //"SCREENING"=>[["1","PAN Card","MANDATORY"]],
                        "SCREENING"=>[],
                         "REQUIRED"=>[["1","PAN Card","OPTIONAL"]],

                        // "REQUIRED"=>[["1","PAN Card","OPTIONAL"],["10","ADDHAr Card","MANDATORY"],["20","Voter Card","MANDATORY"]]

                    );
                }

            //     $response={"statuscode":"TXN","status":"Transaction Successful",
            //     "data":{"APPROVED":[["14","Identity & Address Proof","MANDATORY"]],"SCREENING":[["14","Identity & Address Proof","MANDATORY"]],"REQUIRED":[["1","PAN Card","OPTIONAL"]]
            // }
            // };
                //if ($data) {
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


                    // if ($data['error'] == 4) {
                    //     if ($data['status'] == 'ACTIVE') {
                           
                    //             $execute = $this->Main_model->update_apibox_outlet_id($user_info['user_id'], $data['outlet_id'],$data['BBPS']);
                                
                    //             if ($execute) {
                    //                 $data['error'] = 0;
                    //                 $data['error_desc'] = null;
                    //                 $data['msg'] = 'Service activated successfully';
                    //             } else {
                    //                 $data['error'] = 1;
                    //                 $data['error_desc'] = 'Something went wrong while activating Service';
                    //                 $data['msg'] = null;
                    //             }
                            
                    //     } else {
                    //         $data['error'] = 1;
                    //         $data['error_desc'] = 'Service is inactive, Please contact admin';
                    //         $data['msg'] = null;
                    //     }
                    // }
                // } else {

                //     $data['error'] = 1;
                //     $data['error_desc'] = 'Internal Processing Error';
                //     $data['msg'] = null;
                // }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = "Failed to process request";
                $data['msg'] = null;
            }
                        } else {

                            $data['error'] = 2;
                            $data['error_desc'] = 'Invalid Request';
                            $data['msg'] = NULL;
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
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
                $this->session->sess_destroy();
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }


      private function send_outlet_otp_request($mobile, $user_info) {
        $data = array();
        if ($user_info) {

            $chkservicestat['vendor_library'] = trim('instanpayapi');
            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                $this->load->library($chkservicestat['vendor_library']);
                $mobile = $user_info['mobile'];
                $data = $this->{$chkservicestat['vendor_library']}->request_outlet_otp($user_info['user_id'], $mobile);
               
                if ($data) {

                    if ($data['error'] == 4) {
                        if ($data['status'] == 'ACTIVE') {
                           
                                $execute = $this->Main_model->update_apibox_outlet_id($user_info['user_id'], $data['outlet_id'],$data['BBPS']);
                                
                                if ($execute) {
                                    $data['error'] = 0;
                                    $data['error_desc'] = null;
                                    $data['msg'] = 'Service activated successfully';
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Something went wrong while activating Service';
                                    $data['msg'] = null;
                                }
                            
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Service is inactive, Please contact admin';
                            $data['msg'] = null;
                        }
                    }
                } else {

                    $data['error'] = 1;
                    $data['error_desc'] = 'Internal Processing Error';
                    $data['msg'] = null;
                }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = "Failed to process request";
                $data['msg'] = null;
            }

        } else {
            $data['error'] = 1;
            $data['error_desc'] = "Unable to process request";
            $data['msg'] = null;
        }
        return $data;
    }

    public function ActivateOutlet_withotp(){
        $param = $this->input->post('params');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {

                        $chk_bc_contract = $this->Main_model->check_outlet_id($user_info['user_id']);
                        if ($chk_bc_contract) {
                            //print_r($chk_bc_contract);exit;
                            if ($chk_bc_contract['kyc_apibox'] == NULL) {

                                $data = $this->send_outlet_verify_request($param, $user_info);
                            } else {

                                $data['error'] = 3;
                                $data['msg'] = null;
                                $data['error_desc'] = 'Service already active';
                            }
                        } else {

                            $data = $this->send_outlet_verify_request($param, $user_info);
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



       private function send_outlet_verify_request($param, $user_info) {
        $data = array();
        if ($user_info) {

//            $chkservicestat = $this->Main_model->fetch_service_prov('IRT');
//            if ($chkservicestat) {
//                if ($chkservicestat['is_down'] == 0) {
//
//                    if ($chkservicestat['gateway_down'] == 0) {

            $chkservicestat['vendor_library'] = trim('instanpayapi');

            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                $this->load->library($chkservicestat['vendor_library']);

                $param['nmbr'] = $user_info['mobile'];
                $param['fname'] = $user_info['first_name'];
                $param['lname'] = $user_info['last_name'];
                $param['business_name'] = $user_info['business_name'];
                $param['email'] = $user_info['email'];
                $data = $this->{$chkservicestat['vendor_library']}->request_outlet_verification($user_info['user_id'], $param);

                if ($data) {
                    if ($data['error'] == 0) {
                        $execute = $this->Main_model->update_apibox_outlet_id($user_info['user_id'], $data['outlet_id'],'PENDING');

                        if ($execute) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = 'Service activated successfully';
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Something went wrong while activating Service';
                            $data['msg'] = null;
                        }
                    }
                } else {

                    $data['error'] = 1;
                    $data['error_desc'] = 'Internal Processing Error';
                    $data['msg'] = null;
                }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = "Failed to process request";
                $data['msg'] = null;
            }
//                    } else {
//                        $data['error'] = 1;
//                        $data['error_desc'] = "Service Provider Down, Try again later";
//                        $data['msg'] = null;
//                    }
//                } else {
//                    $data['error'] = 1;
//                    $data['error_desc'] = "Service Down, Try again later";
//                    $data['msg'] = null;
//                }
//            } else {
//                $data['error'] = 1;
//                $data['error_desc'] = "Invalid Service";
//                $data['msg'] = null;
//            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = "Unable to process request";
            $data['msg'] = null;
        }
        return $data;
    }

    
         public function DocUploadFile() {
           // print_r($this->input->post());exit;
        $id = $this->input->post('DocId');
        $doctyp = $this->input->post('DocName');
        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                            $this->load->model('Inst_model');
                            // $chck_exstng_doc = $this->Inst_model->check_exstng_docfrservc_activation($user_info['user_id'], $doctyp);

                            // if (!$chck_exstng_doc) {

                                $user_doc_path = 'assets/SeviceActivationDoc/' . $user_info['user_id'] . '/';
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

                                    $file_path = base_url() .$config['upload_path'] . $upd_file['file_name'];
                                    // $insert = array(
                                    //     'user_id' => $user_info['user_id'],
                                    //     'doc_path' => $file_path,
                                    //     'doc_name' => $doctyp,
                                    //     'doc_for' => 'SERVICE ACTIVATION',
                                    //     'uploaded_on' => date('Y-m-d H:i:s'),
                                    //     'uploaded_by' => $user_info['user_id'],
                                    //     'status' => 'PENDING'
                                    // );
                                    // $get_data = $this->Inst_model->insert_docmnt_fr_srvc_activation($insert);

                                    // if ($get_data) {
                                        // $data['error'] = 0;
                                        // $data['error_desc'] = null;
                                        // $data['msg'] = 'Document Uploaded Sucessfully';
                                    $chck_exstng_doc = $this->Inst_model->check_exstng_docfrservc_activation($user_info['user_id'], $doctyp);
                                    $chk_bc_contract = $this->Main_model->check_outlet_id($user_info['user_id']);
                                    $chkservicestat['vendor_library'] = trim('instanpayapi');

                                    if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {

                                    $this->load->library($chkservicestat['vendor_library']);

             
                                    $data = $this->{$chkservicestat['vendor_library']}->request_fr_Upload_Doc($user_info, $id,$file_path,$chk_bc_contract);

                // if ($data) {
                //     if ($data['error'] == 0) {
                //         $execute = $this->Main_model->update_instant_outlet_id($user_info['user_id'], $data['outlet_id'],'PENDING');

                //         if ($execute) {
                //             $data['error'] = 0;
                //             $data['error_desc'] = null;
                //             $data['msg'] = 'Service activated successfully';
                //         } else {
                //             $data['error'] = 1;
                //             $data['error_desc'] = 'Something went wrong while activating Service';
                //             $data['msg'] = null;
                //         }
                //     }
                // } else {

                //     $data['error'] = 1;
                //     $data['error_desc'] = 'Internal Processing Error';
                //     $data['msg'] = null;
                // }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = "Failed to process request";
                $data['msg'] = null;
            }
                                   
                                }
                            // } else {
                            //     $data['error'] = 1;
                            //     $data['error_desc'] = 'Document Already Exists for Service Activatation';
                            //     $data['msg'] = null;
                            // }
                        
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

    public function UserAgentCdActivate(){
      $id = $this->input->post('UserId');
        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                //  if (page_access_fetch(29)) {     
                // if (page_access(66)) {
                    if ($user_info['is_block'] == 0) {
                        $chck_pndg_kyc_usr = $this->Main_model->is_agent_fr_user_activate($id,'PENDING');
                        if ($chck_pndg_kyc_usr) {

                                    $update = array(
                                        'kyc_apibox' => 'ACTIVE',
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        
                                    );
                                    $get_data = $this->Main_model->user_activated_KYC($id, $update);

                                    if ($get_data) {
                                        $data['error'] = 0;
                                        $data['error_desc'] = null;
                                        $data['msg'] = 'User KYC Activated Successfully';
                                        
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something Went Wrong';
                                        $data['msg'] = null;
                                    }
                               
                           
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'User KYC already Activated';
                            $data['msg'] = null;
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
                //  } else {
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



}
    
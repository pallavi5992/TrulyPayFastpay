<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MoneyTransfer extends CI_Controller {

    public function index(){

	$e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
	if($user_info){

	if($user_info['is_block']==0){	

	if($user_info['role_id']==1 || $user_info['role_id']==2 || $user_info['role_id']==3) {

		redirect ('MoneyTransfer/TransactionHistory');

	}else{

		redirect ('MoneyTransfer/Transfer');

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
	
	function Transfer(){
    	$e = $this->session->userdata('userid'); 
    	$user_info=$this->Main_model->user_acntid($e);
    	if($user_info){
        	if($user_info['is_block']==0){
            	$this->load->view('Dashboard/templates/header');
                $this->load->view('MoneyTransfer/moneytransfer');
            	$check_outlet_id = $this->Main_model->check_outlet_id($user_info['user_id']);
                    if ($check_outlet_id){
                        $outletCheck['outlet'] = $check_outlet_id;
                        $this->load->view('MoneyTransfer/moneytransfer_sidebar');
                        if ($check_outlet_id['kyc_apibox'] == 'ACTIVE') {
                       //***** if outleId is active for user****//
							 
                            // $chkusr_instantsession = $this->session->userdata('instnpaysession');

                            // if ($chkusr_instantsession && $chkusr_instantsession != null) {
                            //     $this->load->view('MoneyTransfer/instnpay_afterlogin');
                            // } else {
                            //     $this->load->view('MoneyTransfer/instnpay_login');
                            // }

                            $this->load->view('MoneyTransfer/instnpay_login');

							//***** if outleId is active for user****//

                    } elseif ($check_outlet_id['kyc_apibox'] == 'PENDING') {
                    	
                    
                        $this->load->view('BillPayments/kyc_document',$outletCheck);

                    } else {
                        $this->load->view('BillPayments/outlet_form');
                    }

                } else {

                    $this->load->view('BillPayments/outlet_form');
                }



	
	
  
    // $this->load->view('Dashboard/templates/footer');  
	}else{
		
		$this->session->sess_destroy();
    	redirect ('Login');

	}
	
	}else{
		
		$this->session->sess_destroy();
    	redirect ('Login');

	}
	}
	
	
	function TransactionHistory(){
	  $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
   
    if($user_info['is_block']==0){
    $this->load->view('Dashboard/templates/header');
    $this->load->view('MoneyTransfer/moneytransfer_sidebar');
    // $this->load->view('MoneyTransfer/moneytransfer');
    $this->load->view('MoneyTransfer/transactionhistory');
    $this->load->view('Dashboard/templates/footer');

    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
   
    }else{
        
        $this->session->sess_destroy();
        redirect ('Login');

    }
		
	}
	
	public function InstantPayLogin(){
		$param = $this->input->post('mobile');
        if (isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model');        
						// $chkusr_instantsession = $this->session->userdata('instnpaysession');
      //                   if (!$chkusr_instantsession) {
                         
                        $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                        if ($chkservicestat) {
                            if ($chkservicestat['is_down'] == 0) {
    							if ($chkservicestat['gateway_down'] == 0) {
    								$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                    if($get_agentcode){
    									if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->InstantCustomerLogin($user_info, $param,$get_agentcode['agent_code']);
    								
    								        $data = $result;
    									
                                            // if ($data['error'] == 0) {
                                            // $this->session->set_userdata('instnpaysession', $data['remitter_mobl']);
                                            //     $data['error'] = 0;
                                            //     $data['error_desc'] = null;
                                            //     $data['msg'] = null;
                                            // }
										} else {
											$data['error'] = 1;
											$data['error_desc'] = "Failed to process request";
											$data['msg'] = null;
                                            $data['status'] = 'FAILED';
										}
									}else{
									    $data['error'] = 1;
                                        $data['error_desc'] = "Agent Code Not Available";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
									}
								}else{
						            $data['error'] = 1;
                                    $data['error_desc'] = "Service Provider Down, Try again later";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
								}
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }
                        // } else {
                        //     $data['error'] = 2;
                        //     $data['error_desc'] = 'Invalid Request';
                        //     $data['msg'] = null;
                        // }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        // $data['date'] = date('Y-m-d H:i:s');
                        $this->session->sess_destroy();
                        // $data['status'] = 'FAILED';
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
                    $data['msg'] = NULL;
                    // $data['date'] = date('Y-m-d H:i:s');
                    $data['status'] = 'FAILED';
                }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['date'] = date('Y-m-d H:i:s');
                $this->session->sess_destroy();
                // $data['status'] = 'FAILED';
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
	}
	
	public function instantpay_remt_registration(){
        $param = $this->input->post('data');
        if (isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model');        
                        $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                        // print_r($chkservicestat);die;
                        if ($chkservicestat) {
							
                            if ($chkservicestat['is_down'] == 0) {
								if ($chkservicestat['gateway_down'] == 0) {
									$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                    if($get_agentcode){
										
                                        if(file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->InstantRemiiterRegstrn($user_info, $param,$get_agentcode['agent_code']);
                                            $data = $result;

                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Failed to process request";
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                        }
							        }else{
									    $data['error'] = 1;
                                        $data['error_desc'] = "Agent Code Not Available";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
                                    
                                    }
						        }else{
						            $data['error'] = 1;
                                    $data['error_desc'] = "Service Provider Down, Try again later";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
						        }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Service Down, Try again later";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid Service";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }
                        
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['date'] = date('Y-m-d H:i:s');
                        $this->session->sess_destroy();
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }	
	}
	
	public function instpy_verifyregiscus(){
		
	    $param = $this->input->post('data');
        if (isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model');        
						// $chkusr_instantsession = $this->session->userdata('instnpaysession');
      //                   if (!$chkusr_instantsession) {
                         
                        $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                        if ($chkservicestat) {
							
                            if ($chkservicestat['is_down'] == 0) {
								if ($chkservicestat['gateway_down'] == 0) {
									$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                    if($get_agentcode){
											
										if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->InstantOtpValidation($user_info, $param,$get_agentcode['agent_code']);
										
                                            $data = $result;
                                       
										} else {
											$data['error'] = 1;
											$data['error_desc'] = "Failed to process request";
											$data['msg'] = null;
                                            $data['status'] = 'FAILED';
										}
									}else{
									    $data['error'] = 1;
                                        $data['error_desc'] = "Agent Code Not Available";
                                        $data['msg'] = null;
                                        $data['status'] = 'FAILED';
									}
								}else{
    					            $data['error'] = 1;
                                    $data['error_desc'] = "Service Provider Down, Try again later";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
								}
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Service Down, Try again later";
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = "Invalid Service";
                            $data['msg'] = null;
                            $data['status'] = 'FAILED';
                        }
                        // } else {
                        //     $data['error'] = 2;
                        //     $data['error_desc'] = 'Invalid Request';
                        //     $data['msg'] = null;
                        // }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                        $data['date'] = date('Y-m-d H:i:s');
                        $this->session->sess_destroy();
                        // $data['status'] = 'FAILED';
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
                    $data['msg'] = NULL;
                    $data['date'] = date('Y-m-d H:i:s');
                    // $data['status'] = 'FAILED';
                }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['date'] = date('Y-m-d H:i:s');
                $this->session->sess_destroy();
                // $data['status'] = 'FAILED';
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
	}
	
	
	public function InstantpyFetchCustomerDetails(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model'); 					
						$chkusr_instantsession = $this->session->userdata('instnpaysession');
                        if ($chkusr_instantsession){
                            $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
                                if ($chkservicestat['is_down'] == 0) {
									if ($chkservicestat['gateway_down'] == 0) {
										$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                            if($get_agentcode){
											if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->InstantCustomerDetailsFetch($user_info, $chkusr_instantsession,$get_agentcode['agent_code']);
											
										   $data = $result;
                                           
											} else {
												$data['error'] = 1;
												$data['error_desc'] = "Failed to process request";
												$data['msg'] = null;
											}
                   
                                         
										
									}else{
										    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            
									}
                                   
								 
								}else{
									            $data['error'] = 1;
                                                $data['error_desc'] = "Service Provider Down, Try again later";
                                                $data['msg'] = null;
                                              
                                                $data['status'] = 'FAILED';
								}
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                            }
                        } else {
							
                            $data['error'] = 2;
                            $data['error_desc'] = 'Invalid Request';
                            $data['msg'] = null;
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
	
	public function BenefRegistration(){
	 $param = $this->input->post('data');
	  if (isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model');        
						// $chkusr_instantsession = $this->session->userdata('instnpaysession');
                        // if ($chkusr_instantsession) {
							// $param['mobile']= $paramclogin $chkusr_instantsession;
                            $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
								
                                if ($chkservicestat['is_down'] == 0) {
									if ($chkservicestat['gateway_down'] == 0) {
										$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                            if($get_agentcode){
											
											if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->AddBeneficiary($user_info, $param,$get_agentcode['agent_code']);
											
										   $data = $result;
                                           
											} else {
												$data['error'] = 1;
												$data['error_desc'] = "Failed to process request";
												$data['msg'] = null;
											}
                   
                                         
										
									}else{
										    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            
									}
                                   
								 
								}else{
									            $data['error'] = 1;
                                                $data['error_desc'] = "Service Provider Down, Try again later";
                                                $data['msg'] = null;
                                              
                                                $data['status'] = 'FAILED';
								}
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                            }
                        // } else {
                        //     $data['error'] = 2;
                        //     $data['error_desc'] = 'Invalid Request';
                        //     $data['msg'] = null;
                        // }
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
		public function InstantPyBenefFetchDetails(){
		   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model'); 					
						$chkusr_instantsession = $this->session->userdata('instnpaysession');
                        if ($chkusr_instantsession){
                            $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
                                if ($chkservicestat['is_down'] == 0) {
									if ($chkservicestat['gateway_down'] == 0) {
										$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                            if($get_agentcode){
											if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->InstantCustomerDetailsFetch($user_info, $chkusr_instantsession,$get_agentcode['agent_code']);
											
										   $data = $result;
                                           
											} else {
												$data['error'] = 1;
												$data['error_desc'] = "Failed to process request";
												$data['msg'] = null;
											}
                   
                                         
										
									}else{
										    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            
									}
                                   
								 
								}else{
									            $data['error'] = 1;
                                                $data['error_desc'] = "Service Provider Down, Try again later";
                                                $data['msg'] = null;
                                              
                                                $data['status'] = 'FAILED';
								}
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                            }
                        } else {
							
                            $data['error'] = 2;
                            $data['error_desc'] = 'Invalid Request';
                            $data['msg'] = null;
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
		public function instpy_verifyBenefregiscus(){   
		$param = $this->input->post('data');
        if(isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model');        
						$chkusr_instantsession = $this->session->userdata('instnpaysession');
                        if ($chkusr_instantsession) {
                         
                            $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
								
                                if ($chkservicestat['is_down'] == 0) {
									if ($chkservicestat['gateway_down'] == 0) {
										$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                            if($get_agentcode){
											
											if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->InstantBenefOtpValidation($user_info, $param,$get_agentcode['agent_code']);
											
										   $data = $result;
                                           
											} else {
												$data['error'] = 1;
												$data['error_desc'] = "Failed to process request";
												$data['msg'] = null;
											}
                   
                                         
										
									}else{
										    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            
									}
                                   
								 
								}else{
									            $data['error'] = 1;
                                                $data['error_desc'] = "Service Provider Down, Try again later";
                                                $data['msg'] = null;
                                              
                                                $data['status'] = 'FAILED';
								}
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Invalid Request';
                            $data['msg'] = null;
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
		
		public function instpy_resendBenfRegisterResendOTP(){
			/* print_r($this->input->post());exit;
			[remitterid] => 6613368
            [bnef_id] => 13198349
            [otp] =>  */
			$param = $this->input->post('data');
        if (isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
                    	$this->load->model('Inst_model');        
						$chkusr_instantsession = $this->session->userdata('instnpaysession');
                        if ($chkusr_instantsession) {
                         
                            $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
								
                                if ($chkservicestat['is_down'] == 0) {
									if ($chkservicestat['gateway_down'] == 0) {
										$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                            if($get_agentcode){
											
											if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            $result = $this->{$chkservicestat['vendor_library']}->InstantBenefRESENDOtpValidation($user_info, $param,$get_agentcode['agent_code']);
											
										   $data = $result;
                                           
											} else {
												$data['error'] = 1;
												$data['error_desc'] = "Failed to process request";
												$data['msg'] = null;
											}
                   
                                         
										
									}else{
										    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            
									}
                                   
								 
								}else{
									            $data['error'] = 1;
                                                $data['error_desc'] = "Service Provider Down, Try again later";
                                                $data['msg'] = null;
                                              
                                                $data['status'] = 'FAILED';
								}
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                            }
                        } else {
                            $data['error'] = 2;
                            $data['error_desc'] = 'Invalid Request';
                            $data['msg'] = null;
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


		public function InstantPyDelCustBenef (){
		
            $param = $this->input->post('data');
		
            if (isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $e = $this->session->userdata('userid');
                $user_info = $this->Main_model->user_acntid($e);
                $data = array();
                if ($user_info) {
                    if ($user_info['role_id']==4) {
                        if ($user_info['is_block'] == 0) {
                        	$this->load->model('Inst_model');        
                            $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
								
                                if ($chkservicestat['is_down'] == 0) {
								    if ($chkservicestat['gateway_down'] == 0) {
									   $get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                        if($get_agentcode){
											
                                            if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                                $this->load->library($chkservicestat['vendor_library']);
                                                $result = $this->{$chkservicestat['vendor_library']}->DeleteBeneficiary($user_info, $param,$get_agentcode['agent_code']);
										
                                                $data = $result;
                                       
                                            } else {
    											$data['error'] = 1;
    											$data['error_desc'] = "Failed to process request";
    											$data['msg'] = null;
                                            }
									
								        }else{
    									    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                        
								        }
                               
							 
                                    }else{
							            $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                      
                                        $data['status'] = 'FAILED';
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
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
		
		public function InstDelCustBenefOtp(){
    	    $param = $this->input->post('data');
            if (isset($param) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $e = $this->session->userdata('userid');
                $user_info = $this->Main_model->user_acntid($e);
                $data = array();
                if ($user_info) {
                    if ($user_info['role_id']==4) {
                        if ($user_info['is_block'] == 0) {
                        	$this->load->model('Inst_model');        
    						// $chkusr_instantsession = $this->session->userdata('instnpaysession');
          //                   if ($chkusr_instantsession) {
                             
                            $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
    							
                                if ($chkservicestat['is_down'] == 0) {
    								if ($chkservicestat['gateway_down'] == 0) {
    									$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                        if($get_agentcode){
    										
    										if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                                $this->load->library($chkservicestat['vendor_library']);
                                                $result = $this->{$chkservicestat['vendor_library']}->InstantOtpDelbenfValidation($user_info, $param,$get_agentcode['agent_code']);
    										
                                                $data = $result;
                                           
    										} else {
    											$data['error'] = 1;
    											$data['error_desc'] = "Failed to process request";
    											$data['msg'] = null;
                                                $data['status'] = 'FAILED';
    										}
        								}else{
    									    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            $data['status'] = 'FAILED';
                                                
        								}
        							}else{
    						            $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                      
                                        $data['status'] = 'FAILED';
        							}
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                    $data['status'] = 'FAILED';
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                                $data['status'] = 'FAILED';
                            }
                            // } else {
                            //     $data['error'] = 2;
                            //     $data['error_desc'] = 'Invalid Request';
                            //     $data['msg'] = null;
                            // }
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
		public function InstantPayRemitterLogout () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
                        $this->session->unset_userdata('instnpaysession');
                       // $this->session->unset_userdata('instnpaysession_name');
                        $data['error'] = 0;
                        $data['error_desc'] = null;
                        $data['msg'] = 'Logged Out';
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Invalid Request';
                        $data['msg'] = null;
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
    }
	
 
		function InstBankByIfsc() {
		$ifsc = $this->input->post('ifsc');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    // $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
					$this->load->model('Inst_model'); 		
                    $bank_with_ho = $this->Inst_model->fetch_bank_by_ifsc($ifsc);
                    if ($bank_with_ho) {
                        if ($this->db->affected_rows() > 0) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = $bank_with_ho;
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Something Went wrong';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 1;
                        $data['msg'] = null;
                        $data['error_desc'] = 'Invalid IFSC Code';
                    }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
    }
	   function BankNameSelect() {
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    // $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
					$this->load->model('Inst_model'); 		
                    $get_data = $this->Inst_model->BankName();
                    if ($get_data) {
                        if ($this->db->affected_rows() > 0) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = $get_data;
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Something Went wrong';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 1;
                        $data['msg'] = null;
                        $data['error_desc'] = 'Invalid Request1';
                    }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
    }

		public function SelectState() {
	    $bankname = $this->input->post('bank_name');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    // $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
					$this->load->model('Inst_model'); 		
                    $get_data = $this->Inst_model->SelectState($bankname);
                    if ($get_data) {
                        if ($this->db->affected_rows() > 0) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = $get_data;
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Something Went wrong';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 1;
                        $data['msg'] = null;
                        $data['error_desc'] = 'Invalid Request1';
                    }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
    }
	
	
		public function SelectCity() {
			 $state = $this->input->post('state');
			   $bankname = $this->input->post('bank_name');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    // $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
					$this->load->model('Inst_model'); 		
                    $get_data = $this->Inst_model->SelectCity($bankname, $state);
                    if ($get_data) {
                        if ($this->db->affected_rows() > 0) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = $get_data;
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Something Went wrong';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 1;
                        $data['msg'] = null;
                        $data['error_desc'] = 'Invalid Request1';
                    }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
    }

			public function SelectBranch() {
			 $state = $this->input->post('state');
        $bankname = $this->input->post('bank_name');
        $city = $this->input->post('city');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    // $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
						$this->load->model('Inst_model'); 		
                        $get_data = $this->Inst_model->SelectBranch($bankname, $state, $city);
                    if ($get_data) {
                        if ($this->db->affected_rows() > 0) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = $get_data;
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Something Went wrong';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 1;
                        $data['msg'] = null;
                        $data['error_desc'] = 'Invalid Request1';
                    }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
    }

		public function GetIFSC() {
		$state = $this->input->post('state');
        $bankname = $this->input->post('bank_name');
        $city = $this->input->post('city');
        $branch = $this->input->post('branch');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    // $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
						$this->load->model('Inst_model'); 		
                        $get_data = $this->Inst_model->GetIFSCCode($bankname, $state, $city, $branch);
                    if ($get_data) {
                        if ($this->db->affected_rows() > 0) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = $get_data;
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Something Went wrong';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 1;
                        $data['msg'] = null;
                        $data['error_desc'] = 'Invalid Request1';
                    }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
    }
	
	public function BankIfsc(){
	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    // $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
						$this->load->model('Inst_model'); 		
                        $bank_with_ho = $this->Inst_model->fetch_bank_with_ho();
					
                        if ($bank_with_ho) {
                            $data['error'] = 0;
                            $data['error_desc'] = null;
                            $data['msg'] = $bank_with_ho;
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Bank Data not available';
                            $data['msg'] = null;
                        }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
	}
		
		
	public function ChrgCommOnTransaction(){
		$param=$this->input->post('data');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
                    $chkusr_instnpaysession = ''; //$this->session->userdata('instnpaysession');
                    // if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
						$this->load->model('Inst_model'); 	
                        						
					    $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
                                
                                if ($chkservicestat['is_down'] == 0) {
                                    if ($chkservicestat['gateway_down'] == 0) {
                                        if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
                                            if (ctype_digit($param['transamount'])) {
                                                if ($param['transamount'] <= 5000) {  
                                                    $result = $this->{$chkservicestat['vendor_library']}->InstantpyCCFCommsionCal($user_info, $chkusr_instnpaysession, $param, $param['transamount'], $chkservicestat, $user_info['plan_id']);
                                                    $result['date'] = date('Y-m-d H:i:s');
                                                    $data = $result;
													
                                                } else if ($param['transamount'] > 5000) {
                                                    $success_count = $error_count = 0;
                                                    $count = 0;
                                                    $txnData = [];
                                                    foreach (range($param['transamount'], 0, 5000) as $i) {
                                                        if ($i < 5000) {
                                                            if ($i == 0) {
                                                                $p = 0;
                                                            } else {
                                                                $p = $i;
                                                            }
                                                        } else {
                                                            $p = 5000;
                                                        }
                                                        if ($i != 0) {
                                                            $date = date('Y-m-d H:i:s');
                                                            $result = $this->{$chkservicestat['vendor_library']}->InstantpyCCFCommsionCal($user_info, $chkusr_instnpaysession, $param, $p, $chkservicestat, $user_info['plan_id']);
                                                            $data = $result;
                                                            if ($data['error'] == 0) {
                                                                $data['amount'] = $p;
                                                                $data['date'] = $date;
                                                                $txnData[$count] = $data;
                                                                $success_count = $success_count + 1;
                                                            } else {
                                                                $data['amount'] = $p;
                                                                $data['date'] = $date;
                                                                $txnData[$count] = $data;
                                                                $error_count = $error_count + 1;
                                                            }
                                                        }
                                                        $count++;
                                                    }
                                                    $data['error'] = 8;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Total Executed Transaction ' . $success_count . '<br><br>Total Rejected Transaction ' . $error_count;
                                                    $data['result'] = $txnData;
                                                }
                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = "Invalid Transaction Amount";
                                                $data['msg'] = null;
                                            }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Failed to process request";
                                            $data['msg'] = null;
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                            }
                    // } else {
                    //     $data['error'] = 2;
                    //     $data['error_desc'] = 'Invalid Request';
                    //     $data['msg'] = null;
                    // }
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
	}

	public function CheckTxnMPIN() {
        $type = $this->input->post('data');
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
				if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
				        $type['CHCKMPINREQ']['MPIN'] = trim($type['CHCKMPINREQ']['MPIN']);
                        if ($type != '') {
							$this->load->model('Inst_model'); 	
                            $checktxnpin = $this->Inst_model->checktxnmpin($type['CHCKMPINREQ']['MPIN'], $user_info['user_id']);
								
                            if ($checktxnpin) {
                             
								if ($type['CHCKMPINREQ']['REMARK']!= '') {
                                    $data['error'] = 0;
                                    $data['error_desc'] = NULL;
                                    $data['msg'] = 'Request Succesfully Completed';
								}else{
									 $data['error'] = 1;
                                    $data['error_desc'] = 'Invalid Transaction Mpin';
                                    $data['msg'] = NULL;
								}
                                
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Transaction Mpin';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid Transaction Mpin';
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
      
    }
		public function AmountTransfer(){
	//print_r($this->input->post());exit;
	/* 	 [CHCKMPINREQ] => Array
        (
            [valid] => true
            [MPIN] => 1234
            [REMARK] => fgbgf
        )

    [TXN_REQUEST] => Array
        (
            [bank] => ALLAHABAD BANK
            [ifsccode] => ALLA0210001
            [accountno] => 11114051627
            [name] => regrthtr
            [mode] => IMPS
            [transamount] => 5000
        ) */
		  $param=$this->input->post();
	
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info){
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
                    $chkusr_instnpaysession = $param['mobile']; //$this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != '') {
						$this->load->model('Inst_model'); 	
						unset($param['mobile']);
				        $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                        if ($chkservicestat) {
                            //print_r($chkservicestat);exit;
                            if ($chkservicestat['is_down'] == 0) {
                                if ($chkservicestat['gateway_down'] == 0) {
									
									
									$get_agentcode=$this->Main_model->get_agentcode_active_KYC($user_info['user_id']);
                                    if($get_agentcode){
											
                                        if (file_exists(APPPATH . "libraries/" . ucfirst($chkservicestat['vendor_library']) . ".php")) {
                                            $this->load->library($chkservicestat['vendor_library']);
									
                                            if (ctype_digit($param['TXN_REQUEST']['transamount'])) {
                                                if ($param['TXN_REQUEST']['transamount'] <= 5000) {
                                                    $result = $this->{$chkservicestat['vendor_library']}->InstantpyMoneyTransfer($user_info, $chkusr_instnpaysession, $param, $param['TXN_REQUEST']['transamount'], $chkservicestat, $user_info['plan_id'],$get_agentcode);

                                                    $result['date'] = date('Y-m-d H:i:s');
                                                    $data = $result;
                                                } else if ($param['TXN_REQUEST']['transamount'] > 5000) {
                                                    $success_count = $error_count = 0;
                                                    $count = 0;
                                                    $txnData = [];
                                                    foreach (range($param['TXN_REQUEST']['transamount'], 0, 5000) as $i) {
                                                        if ($i < 5000) {
                                                            if ($i == 0) {
                                                                $p = 0;
                                                            } else {
                                                                $p = $i;
                                                            }
                                                        } else {
                                                            $p = 5000;
                                                        }
                                                        if ($i != 0) {
                                                            $date = date('Y-m-d H:i:s');
                                                            $result = $this->{$chkservicestat['vendor_library']}->InstantpyMoneyTransfer($user_info, $chkusr_instnpaysession, $param, $p, $chkservicestat, $user_info['plan_id'],$get_agentcode);
                                                            $data = $result;
                                                            if ($data['error'] == 0) {
                                                                $data['amount'] = $p;
                                                                $data['date'] = $date;
                                                                $txnData[$count] = $data;
                                                                $success_count = $success_count + 1;
                                                            } else {
                                                                $data['amount'] = $p;
                                                                $data['date'] = $date;
                                                                $txnData[$count] = $data;
                                                                $error_count = $error_count + 1;
                                                            }
                                                        }
                                                        $count++;
                                                    }
                                                    $data['error'] = 8;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Total Executed Transaction ' . $success_count . '<br><br>Total Rejected Transaction ' . $error_count;
                                                    $data['result'] = $txnData;
                                                }
                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = "Invalid Transaction Amount";
                                                $data['msg'] = null;
                                            }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = "Failed to process request";
                                            $data['msg'] = null;
                                        }
										}else{
										    $data['error'] = 1;
                                            $data['error_desc'] = "Agent Code Not Available";
                                            $data['msg'] = null;
                                            
									}
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = "Service Provider Down, Try again later";
                                        $data['msg'] = null;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = "Service Down, Try again later";
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = "Invalid Service";
                                $data['msg'] = null;
                            }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Invalid Request';
                        $data['msg'] = null;
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
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');   
        }
		}
		
		 public function MoneyTransfer_Txn_Re() {
		  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            
            if ($user_info) {
				if ($user_info['role_id']==4) {
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
                                $user_data = $this->Inst_model->Datatable_fetch_Money_myorder($user_info['user_id'], $from, $to, $cell);
                                if ($user_data) {
								
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_Money_myorder($user_info['user_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_Money_myorder($user_info['user_id'], $from, $to, $cell),
                                        "data" => $user_data
                                    );
                                } else {
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_Money_myorder($user_info['user_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_Money_myorder($user_info['user_id'], $from, $to, $cell),
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
                    $result['error'] = 1;
                    $result['error_desc'] = 'Unauthorised access';
                    $result['msg'] = NULL;
                }
            } else {
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = NULL;
            }
            echo json_encode($result);
        } else {
            redirect('Dashboard');   
        }
      
    }
		
	    public function MoneyTransfer_Txn_Allorder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
             	if ($user_info['role_id']==1|| $user_info['role_id']==2 || $user_info['role_id']==3) {
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

                                $treeFetch = $this->Inst_model->GetSubAdminDistChildNewFunction($user_info['user_id']);
								
                                $user_data = $this->Inst_model->Datatable_fetch_money_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch);
									
                                if ($user_data) {

                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_money_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $treeFetch),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_money_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch),
                                        "data" => $user_data
                                    );
                                } else {
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_money_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $treeFetch),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_money_allorder($user_info['user_id'], $user_info['role_id'], $from, $to, $cell, $treeFetch),
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
                    }
                } else {
                    $result['error'] = 1;
                    $result['error_desc'] = 'Unauthorised access';
                    $result['msg'] = NULL;
                }
            } else {
                $result['error'] = 1;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = NULL;
            }
            echo json_encode($result);
        } else {
            redirect('Dashboard');
        }
    }
	/*  function PrintTable($rcid) {
        //print_r($this->input->post());exit;
        $rowdata = $this->input->post('checkrow');
        if ($rcid && $rcid != null && $rcid != '' && isset($rowdata) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $this->load->model('Inst_model');
            $data = array();
            if ($user_info) {
               	if ($user_info['role_id']==4) {
                    if ($user_info['is_block'] == 0) {
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
                                                                     <h2>Transaction Receipt</h2>
                                                                 </td>
                                                                 <td  valign="top" style="padding: 15px 0px 0px 0px;" class="logo">
                                                                <img  src="' . $base64 . '" width="23%" height="10%"/>
                                                            </td>
                                                         </tr>
                                                                 <tr>
                                                                         <td  valign="top" style="padding: 15px 0px 0px 0px;" class="logo">
                                                                             <h4>Shop Name: ' . $user_info['shop_name'] . '</h4>
                                                                         </td>
                                                                 </tr>
                                                                 <tr>
                                                                         <td  valign="top" style="padding: 15px 0px 40px 0px;" class="logo">
                                                                            <h4>Address: ' . $user_info['shop_addr'] . ', ' . $user_info['shop_city'] . ', ' . $user_info['shop_state'] . ' ' . $user_info['shop_pincode'] . '</h4>
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
                                                     Remitter Mobile No
                                                         </th>
                                                         <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                         Beneficiary Name
                                                         </th>
                                                     <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                     Account Number
                                                         </th>';
                        if (isset($rowdata[0]['ifsc'])) {
                            $conten = $conten . '<th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                         IFSC
                                                         </th><th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                         BANK
                                                         </th>';
                        }
                        $conten = $conten . '<th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                         Transaction type
                                                         </th>
                                                     <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
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

                            if (isset($c['ifsc'])) {
                                $getifsc = $this->Rech_Model->fetch_bank_ifsc_all($c['ifsc']);
                                //$bankname=($getifsc)?$getifsc['bankname']:$c['ifsc'];
                                $bankname = ($getifsc) ? $getifsc['BANK'] : $c['ifsc'];
                            } else {
                                $bankname = '';
                            }
                            //$bankname=$c['ifsc'];

                            $conten = $conten . '<tr>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['trans'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['remitter'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['beni_name'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['acno'] . '</td>';

                            if (isset($c['ifsc'])) {
                                $conten = $conten . '<td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['ifsc'] . '</td>
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $bankname . '</td>';
                            }

                            $conten = $conten . '<td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['txntype'] . '</td>
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
                                                                additional/excess charges to Travel Guru.</p>
                                                                <p style="font-size:12px">5. This is a system generated receipt hence does not require any signature.</p>
                                                            </td>
                                                    </tr>
                                                    <tr>
                                                            <td  valign="top" style="padding: 5px 0px 5px 0px;">
                                                                <h6>Is there anything you want to share with us?</h6>
                                                            </td>
                                                    </tr>
                                                    <tr>
                                                            <td  valign="top" style="padding: 0px 0px 40px 0px;">
                                                                <p style="font-size:12px"></p>
                                                            </td>
                                                    </tr>
                                             </table>
                                         </td>
                                     </tr>
                             </table>
                     </td>
                </tr>
         </table>';
                        $this->m_pdf->pdf->WriteHTML($conten);
                        //download it.
                        if (count($rowdata) == 1) {
                            $name = $rowdata[0]['trans'];
                        } else {
                            $name = "Transaction Receipts";
                        }
                        $this->m_pdf->pdf->Output($name, 'I');
                    } else {
                        $result['error'] = 2;
                        $result['error_desc'] = 'Access denied';
                        $result['msg'] = NULL;
                    }
                } else {
                    $result['error'] = 1;
                    $result['error_desc'] = 'Unauthorised access';
                    $result['msg'] = NULL;
                }
            } else {
                $result['error'] = 1;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = NULL;
            }
            echo json_encode($result);
        } else {
            redirect('Dashboard');
        }
    } */
	
	
	
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
                                                         Transaction No
                                                         </th>
                                                     <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                      Remitter Mobile No
                                                         </th>
                                                   ';
                 
                    $conten = $conten . '<th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                     Account Number
                                                         </th>
														 <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                   IFSC Code
                                                         </th>
														 <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
                                                    Transaction type
                                                         </th>
														
														 <th align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">
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
                                                     <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['beni_name'] . '</td>
													   <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['acno'] . '</td> <td align="center" valign="top" style="padding: 5px;border:1px solid #ddd;">' . $c['ifsc'] . '</td>
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
                                                                additional/excess charges to Indeepay.</p>
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

    public function BookComplaint() {
        $tid = $this->input->post('tid');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                	if ($user_info['role_id']==4 || $user_info['role_id']==1) {
                    if ($user_info['is_block'] == 0) {
						  $this->load->model('Inst_model');
                        $tid = trim($tid);
                        $findtxn = $this->Inst_model->findtxn_data($tid);
                        if ($findtxn) {
							
                            if ($user_info['role_id'] == 1 || ($user_info['user_id'] == $findtxn['user_id'])) {
                                if ($findtxn['status'] == 'SUCCESS' || $findtxn['status'] == 'PENDING') {

                                    if (date_format(date_create($findtxn['req_dt']), 'Y-m-d H:i:s') >= date('Y-m-01')) {
                                        if ($findtxn['review_stat'] == 0) {
                                            if (date('Y-m-d H:i:s') >= date('Y-m-d H:i:s', strtotime(date_format(date_create($findtxn['req_dt']), 'Y-m-d H:i:s') . "+5 minutes"))) {
                                                $book_comp = $this->Inst_model->book_complaint($tid, $user_info['user_id'], $user_info['role_id']);
                                                if ($this->db->affected_rows() > 0) {

                                                    $data['error'] = 0;
                                                    $data['error_desc'] = null;
                                                    $data['msg'] = 'Complaint successfully booked for Transaction ID ' . $findtxn['fstpytxn_id'];
                                                    ;
                                                } else {
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Something went wrong';
                                                    $data['msg'] = null;
                                                }
                                            } else {
                                                $data['error'] = 1;
                                                $data['error_desc'] = 'Complaint can be filed after 5min from the transaction time';
                                                $data['msg'] = null;
                                            }
                                        } else {
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Complaint is allowed only once';
                                            $data['msg'] = null;
                                        }
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Complaint can be filed for transactions within the same month';
                                        $data['msg'] = null;
                                    }
                                } else {

                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Complaints can booked only for successfull transactions';
                                    $data['msg'] = null;
                                }
                            } else {

                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Request, Complaint cannot be booked';
                                $data['msg'] = null;
                            }
                        } else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'No such transaction exist';
                            $data['msg'] = null;
                        }
                    } else {
                        $data['error'] = 2;
                        $data['error_desc'] = 'Access denied';
                        $data['msg'] = NULL;
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
                    $data['msg'] = NULL;
                }
            } else {
                $data['error'] = 1;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
            }
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
    }
	
	public function Fetch_services_fr_remtt() {
    $param = $this->input->post('cell_id');
    $type = $this->input->post('type');
    // print_r($_POST);die;
    if (isset($type) && ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($param)) {
            $data = array();
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
					  
                        $param_array = [10, 13, 12,2];
                        $type_array = ['RECHARGE', 'REMITTANCE'];
                        if (in_array($param, $param_array) && in_array($type, $type_array)) {
                            if ($param == 10 || $param == 13 || $param == 12 || $param == 2) {
                                $this->load->model('Inst_model');
                               // $status_name = $this->Inst_model->statusMenu_name();
    							  $status_name = $this->Inst_model->FetchSrvcsForRemitt('REMITTANCE');
                                if ($status_name) {
                                    $arr = [];
                                    foreach ($status_name as $key => $value) {
                                        $arr[$key] = ['name' => $value['service_name'], 'code' => $value['code']];
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
            // echo '<pre>';print_r($data);
            echo json_encode($data);
        } else {
            redirect('Dashboard');
        }
		
    }
	
	 /*   public function Fetch_services_fr_remtt(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();   
            if ($user_info) {
               
                if ($user_info['is_block'] == 0) {
					  $this->load->model('Inst_model');
                    $services = $this->Inst_model->FetchSrvcsForRemitt('REMITTANCE');
                        //print_r($plan);exit;    
                        $data['error_data'] = 0;   
                        $data['error_desc'] = NULL;    
                        $data['msg'] = NULL;
                        $data['data'] = $services ? $services : [];
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
    } */
	
	
	}
	


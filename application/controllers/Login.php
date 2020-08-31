<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Login extends CI_Controller {
	public function index()
	{
		$this->load->view('login/login');    
	}

	public function Validate_login(){
		$mobile = $this->input->post('login_input');
        $pass = $this->input->post('login_pass');
		$e = $this->session->userdata('userid'); //retrieve SESSION data 		
		    if (isset($mobile) && isset($pass) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$e) {
				
                $result = array();
				
                if ($mobile != "" && $pass != "") {
					
                  $valid_usr = $this->Main_model->login($mobile, $pass);
                     
                  if($valid_usr) {

					     if($valid_usr['is_block']==0)
                         {

                         if ($valid_usr['two_factor'] == 1) 
                         {
                             
                             $check_if_ip_exist=$this->Main_model->check_if_loginip_exist($valid_usr['user_id'],ip_address());
                             
                             if($check_if_ip_exist)
                             {
                                 
                                $this->session->set_userdata('userid', $valid_usr['user_id']);
                                $this->session->set_userdata('role_id', $valid_usr['role_id']);
                                $result['error'] = 0;
                                $result['error_desc'] = null;
                                $result['msg'] = 'ok';
 
                                 
                                 
                             }else{
                                 
                                $otp = rand(100800, 999995);
                                $this->load->library('Service_alerts');
                                $tk = $this->service_alerts->user_2fa('OTP', $valid_usr['mobile'], $valid_usr['email'], $valid_usr['user_id'], $otp, 'Login');
                                $result['error'] = 3;
                                $result['error_desc'] = null;
                                $result['loginparam'] = $valid_usr['mobile'];
                                // $result['otp'] = $otp;
                                $result['msg'] = 'OTP sent successfully';
                                 
                             }

                            
							
                        } else {

                            $this->session->set_userdata('userid', $valid_usr['user_id']);
							$this->session->set_userdata('role_id', $valid_usr['role_id']);
                            $result['error'] = 0;
                            $result['error_desc'] = null;
                            $result['msg'] = 'ok';

                        } 
                      }else{
  						
                          $result['error'] = 1;
                          $result['error_desc'] = 'Access denied';
                          $result['msg'] = null;
                      }	

                    }else{
						
                        $result['error'] = 1;
                        $result['error_desc'] = 'Invalid mobile number or password';
                        $result['msg'] = null;
                    }
                } else {  

                    $result['error'] = 1;
                    $result['error_desc'] = 'Fields cannot be empty';
                    $result['msg'] = null;
                }
                
            } else {

                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = null;

            }
            echo json_encode($result);
        } else{
            redirect('Dashboard');
        }
	}

	public function Validate_otp(){
	    $mobile = $this->input->post('op1');
        $otp = $this->input->post('otp');
   
        $e = $this->session->userdata('userid');

        if (isset($mobile) && isset($otp) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = array();
            if (!$e) {

                $verify = $this->Main_model->validate_loginotp($mobile, $otp);
                
				 if($verify['is_block']==0){
                if ($verify) {
				

                    if (date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($verify['created_on']))) >= date('Y-m-d H:i:s')) 
                    {
						
                        $this->Main_model->delete_auth_otp($verify['user_id'], $verify['otp']);
                        $this->Main_model->log_login_ip_foruser($verify['user_id']);
                        
                        $this->session->set_userdata('userid', $verify['user_id']);
						 $this->session->set_userdata('role_id', $verify['role_id']);
                        $result['error'] = 0;
                        $result['error_desc'] = null;
                        $result['msg'] = 'ok';
						
                    } else {
						
                        $this->Main_model->delete_auth_otp($verify['user_id'], $verify['OTP']);
                        $result['error'] = 1;
                        $result['error_desc'] = 'Your OTP has expired, Request again';
                        $result['msg'] = null;

                    }
                }else{
                    $result['error'] = 1;
                    $result['error_desc'] = 'Invalid OTP';
                    $result['msg'] = null;
                }
				}else{
						
                        $result['error'] = 1;
                        $result['error_desc'] = 'Access denied';
                        $result['msg'] = null;
                    }
            }else{
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = null;
            }
            echo json_encode($result);
        } else {
            redirect('Dashboard');
        }
	}

	public function Resend_otp_fr_login(){
		$mobile = $this->input->post('Mobile');
        $e = $this->session->userdata('userid');
        if (isset($mobile) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = array();
            if (!$e) {
            	
                $verify = $this->Main_model->user_info($mobile);
				if($verify['is_block']==0){
                if ($verify) {
					//print_r($verify);exit;
                    $otp = rand(100800, 999995);

                    $this->load->library('Service_alerts');
                    $tk = $this->service_alerts->user_2fa('OTP', $verify['mobile'], $verify['email'], $verify['user_id'], $otp, 'Login');


                    $result['error'] = 0;
                    $result['error_desc'] = null;
                    $result['msg'] = 'OTP Sent Successfully';

                } else {

                    $result['error'] = 1;
                    $result['error_desc'] = 'Invalid Request';
                    $result['msg'] = null;
                }

				}else{
						
                        $result['error'] = 1;
                        $result['error_desc'] = 'Access denied';
                        $result['msg'] = null;
                    }
            } else {
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = null;
            }
            echo json_encode($result);
        } else {
			
            redirect('Dashboard');
        }
	}


	    public function Forget_Password_OTP(){
  	     $mobile = $this->input->post('frgtinpt');
        $e = $this->session->userdata('userid');
        if (isset($mobile) && $_SERVER['REQUEST_METHOD'] === 'POST') {
			
            $result = array();
            if (!$e) {

                $verify = $this->Main_model->user_info_byno($mobile);
				if($verify['is_block']==0){
                if ($verify) {
					
                  // print_r($verify);exit;
                    $otp = rand(100800, 999995);


                      $this->load->library('Service_alerts');
                    $tk = $this->service_alerts->user_2fa('OTP', $verify['mobile'], $verify['email'], $verify['user_id'], $otp, 'Reset Password');
                    $result['error'] = 0;
                    $result['error_desc'] = null;
					$result['reset_rsndattemp'] = $verify['mobile'];
                    $result['msg'] ='OTP sent successfully';
                } else {
                    $result['error'] = 1;
                    $result['error_desc'] = 'Invalid Mobile Number';
                    $result['msg'] = null;
                }
				}else{
						
                        $result['error'] = 1;
                        $result['error_desc'] = 'Access denied';
                        $result['msg'] = null;
                    }
            } else {
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = null;
            }
            echo json_encode($result);
			
        } else {
            redirect('Dashboard');
        }
    }


    public function validate_resetotp(){
    	//print_r($this->input->post());exit;
    	$mobile = $this->input->post('frgtinpt');
        $resetotp = $this->input->post('otp');
        $newpassword = $this->input->post('newpass');
        $confrmpassword = $this->input->post('cnfpass');
        //print_r($email);exit;
        $e = $this->session->userdata('userid');
		
        if (isset($resetotp) && isset($newpassword) && isset($confrmpassword) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = array();
            if (!$e){

                $chkotp = $this->Main_model->check_resetotp($resetotp, $mobile);
	
			  //if($chkotp['is_block']==0){	
                if ($chkotp){
                  //print_r($chkotp);exit;   
                    if (date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($chkotp['created_on']))) >= date('Y-m-d H:i:s')) {
                        if ($newpassword == $confrmpassword) {

                            $change = $this->Main_model->change_pass_byreset($mobile, $newpassword);
                            if ($change) {
                                $result['error'] = 0;
                                $result['error_desc'] = null;
                                $result['msg'] = 'Password changed successfully.';
                                $this->Main_model->delete_reset_otp($chkotp['userid'], $chkotp['otp']);
                            } else {
                                $result['error'] = 1;
                                $result['error_desc'] = 'Something went wrong, Try again';
                                $result['msg'] = null;
                            }
                        }else {
                            $result['error'] = 1;
                            $result['error_desc'] = 'Confirm Password and new password mismatched';
                            $result['msg'] = null;
                        }
                    } else {
                        $this->Main_model->delete_reset_otp($chkotp['user_id'], $chkotp['OTP']);
                        $result['error'] = 1;
                        $result['error_desc'] = 'Your OTP has expired, Request again';
                        $result['msg'] = null;
                    }
                } else {
					
                    $result['error'] = 1;
                    $result['error_desc'] = 'Invalid OTP';
                    $result['msg'] = null;
                }
				// }else {
								// $result['error'] = 1;
								// $result['error_desc'] = 'Access denied';
								// $result['msg'] = null;
							// }
				
            }else{
				
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = null;
            }
			
            echo json_encode($result);
			
        } else {
            redirect('Dashboard');
        }
    }

  

       function Resend_otp_fr_frgtpass() {
        $mobile = $this->input->post('Mobile');
        $e = $this->session->userdata('userid');
        if (isset($mobile) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = array();
            if (!$e) {

                $verify = $this->Main_model->user_info($mobile);
				if($verify['is_block']==0){
                if ($verify) {

                    $otp = rand(100800, 999995);

                    $this->load->library('Service_alerts');

                    $tk = $this->service_alerts->user_2fa('OTP', $verify['mobile'], $verify['email'], $verify['user_id'], $otp, 'Reset Forgot Password');
                    $result['error'] = 0;
                    $result['error_desc'] = null;
                    $result['msg'] = 'OTP Sent Successfully';
                } else {
                    $result['error'] = 1;
                    $result['error_desc'] = 'Invalid Request';
                    $result['msg'] = null;
                }
				}else {
						$result['error'] = 1;
						$result['error_desc'] = 'Access denied';
						$result['msg'] = null;
				}
            } else {
                $result['error'] = 2;
                $result['error_desc'] = 'Invalid Request';
                $result['msg'] = null;
            }
            echo json_encode($result);
        } else {
            redirect('Dashboard');
        }
    }

	public function logout(){
		 
    	$this->session->sess_destroy();
    	redirect('Login');

    }

}
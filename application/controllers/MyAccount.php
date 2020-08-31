<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyAccount extends CI_Controller {
    
	
	public function MyProfile(){
	$e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
	if($user_info){
    //if(page_access(1)){
	if($user_info['is_block']==0){	
    $this->load->view('Dashboard/templates/header');
	$this->load->view('Dashboard/profile');
    //$this->load->view('Dashboard/templates/footer');  
	}else{
		
		$this->session->sess_destroy();
    	redirect ('Login');

	}
	// }else{
	// 		redirect('Dashboard');
	// 	}
	}else{
		
		$this->session->sess_destroy();
    	redirect ('Login');

	}
	
	}
    public function change_user_pswd(){
       $crnt_pwsd = $this->input->post('crnt_pass');
        $newpassword = $this->input->post('new_pass');
        $confrmpassword = $this->input->post('cnfrm_pass');
        if (isset($crnt_pwsd) && isset($confrmpassword) && isset($newpassword) && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = array();
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
           
                    if ($user_info['is_block'] == 0) {
                        $verify_pswd = $this->Main_model->user_info_by_pswd($user_info['user_id'], $crnt_pwsd);

                        if ($verify_pswd) {
                            //print_r($verify_pswd);exit;
                            if (strlen($newpassword) >= 6) {   

                                if ($newpassword == $confrmpassword) {

                                    $change = $this->Main_model->change_pass_by_user($user_info['user_id'], $newpassword);
                                    if ($change) {

                                        $data['error'] = 0;
                                        $data['error_desc'] = null;
                                        $data['msg'] = $change['updated'] == 'Yes' ? 'Password changed successfully.' : 'New Password and old password are same';
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something went wrong, Try again';
                                        $data['msg'] = null;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Confirm Password and new password mismatched';
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'password must be at least 6 characters.';
                                $data['msg'] = null;
                            }
                        } else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid Password';
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



	   public function change_user_mpin(){
        $crnt_mpin = $this->input->post('crnt_mpin');
        $newmpin = $this->input->post('new_mpin');
        $confrmmpin = $this->input->post('cnfrm_mpin');
        if (isset($crnt_mpin) && isset($confrmmpin) && isset($newmpin) && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = array();
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
           
                    if ($user_info['is_block'] == 0) {
                        $verify_mpin = $this->Main_model->user_info_by_mpin($user_info['user_id'], $crnt_mpin);

                        if ($verify_mpin) {
                            //print_r($verify_mpin);exit;
                            if (strlen($newmpin) >= 4) {

                                if ($newmpin == $confrmmpin) {

                                    $change = $this->Main_model->change_mpin_by_user($user_info['user_id'], $newmpin);
                                    if ($change) {

                                        $data['error'] = 0;
                                        $data['error_desc'] = null;
                                        $data['msg'] = $change['updated'] == 'Yes' ? 'MPIN changed successfully.' : 'New MPIN and old MPIN are same';
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something went wrong, Try again';
                                        $data['msg'] = null;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Confirm MPIN and new MPIN mismatched';
                                    $data['msg'] = null;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'MPIN must be at least 4 characters.';
                                $data['msg'] = null;
                            }
                        } else {

                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid MPIN';
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



}

?>
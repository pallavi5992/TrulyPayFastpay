<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
	public function index(){
	$e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
	if($user_info){
    //if(page_access(1)){
	if($user_info['is_block']==0){	
    $this->load->view('Dashboard/templates/header');
	$this->load->view('Dashboard/dashboard');
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

	    function getaccntbal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['is_block'] == 0) {
                    $data['error'] = 0;
                    $data['error_desc'] = null;
                    $data['msg'] = $user_info['rupee_balance'];
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
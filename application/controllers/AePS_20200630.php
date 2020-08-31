<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AePS extends CI_Controller {



function load(){
//print_r($_SERVER);exit;
}

public function Transaction(){
    $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
    if($user_info){
    
    if($user_info['is_block']==0){  
    if($user_info['role_id']==1 ||$user_info['role_id']==2 || $user_info['role_id']==3 || $user_info['role_id']==4 ) {
     
        $this->load->view('Dashboard/templates/header');
        $this->load->view('AePS/AePs_sidebar');
        $this->load->view('AePS/Transaction');

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
    
    function AepsBalProcess()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Headers: *');
        header("Content-Type: application/json; charset=UTF-8");
        
        $requestdata = file_get_contents('php://input');
        $this->output->set_status_header(200);
        
        $request['response_code'] = 'TXN';
        $request['response_msg'] = 'Transaction Successfull';
        $request['transactions'][0]['balance'] ='100';
        
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
        
        $request['response_code'] = 'ERR';
        $request['response_msg'] = 'Failed to process request';
        $request['transactions'] = array();
        
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
        
        $request['response_code'] = 'ERR';
        $request['response_msg'] = 'Failed to process request';
        $request['transactions'] = array();
        
        echo json_encode($request);
        
        
    }
    
    

}
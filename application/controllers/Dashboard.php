<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
	public function index(){
	$e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
	if($user_info){
    //if(page_access(1)){
	if($user_info['is_block']==0){	
        $data['user_detail'] = $this->Main_model->check_load_amt_userid($user_info['user_id']);
        // $data['portal_notifs'] = $this->Main_model->getnoteDataWithRole($user_info['role_id']);
        $this->load->view('Dashboard/templates/header');
    	$this->load->view('Dashboard/dashboard', $data);
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


    public function portal_notifs_list(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if($user_info['is_block']==0){
                    $notifs_list = array();
                    $list = $this->Main_model->getnoteDataWithRole($user_info['role_id']);

                    foreach($list as $key=>$list){
                        if($list['event_type'] == 'GREETING'){
                            $path = FCPATH . $list['content'];
                            if(file_exists($path)){
                                $notifs_list[] = $list;
                            }
                        }else { // for notification only
                            $notifs_list[] = $list;
                        }

                    }
                    $data['error'] = 0;
                    $data['error_desc'] = NULL;
                    $data['msg'] = NULL;
                    $data['data'] =  $notifs_list;
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Access denied';
                    $data['msg'] = NULL;
                    $data['data'] = array();
                    
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



    function Suggestion(){
        $e = $this->session->userdata('userid'); 
        $user_info=$this->Main_model->user_acntid($e);
        if($user_info){
            if($user_info['is_block']==0){
                $this->load->view('Dashboard/templates/header');
                if(in_array($user_info['role_id'], array(4,3,2))){
                    $this->load->view('Dashboard/suggestion');
                } else {
                    $this->load->view('Dashboard/suggestion_list');
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


    public function FeedbackPost(){
        $b = All_Regex();
        $Subject = $this->input->post('Subject');
        $Feedback = $this->input->post('Feedback');
        
        $Subject = trim($Subject);
        $Feedback = trim($Feedback);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                        if(in_array($user_info['role_id'], array(4,3,2))){
                            if ($Subject) {
                                if ($Feedback) {
                                    $feedbackPost = array(
                                        'user_id' => $user_info['user_id'],
                                        'subject' => $Subject,
                                        'feedback' => $Feedback,
                                        'posted_on' => date('Y-m-d H:i:s')
                                    );
                                    $InsertFeedbackPost = $this->Main_model->InsertFeedbackPost($feedbackPost);
                                    if ($InsertFeedbackPost) {
                                        $data['error'] = 0;
                                        $data['error_desc'] = NULL;
                                        $data['msg'] = 'Your feedback has been successfully posted';
                                    } else {
                                        $data['error'] = 1;
                                        $data['error_desc'] = 'Something Went Wrong, Try Again';
                                        $data['msg'] = NULL;
                                    }
                                } else {
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Feedback should not be blank';
                                    $data['msg'] = NULL;
                                }
                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Subject should not be blank';
                                $data['msg'] = NULL;
                            }
                        } else {
                            $data['error'] = 1;
                            $data['error_desc'] = 'Unauthorized access';
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


    public function SuggestionPostList(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $r = $this->session->userdata('role_id');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if ($user_info['is_block'] == 0) {
                    $FeedbackPost = $this->Main_model->getFeedbackPost();
                    $data['error'] = 0;
                    $data['error_desc'] = NULL;
                    $data['msg'] = NULL;
                    $data['data'] = $FeedbackPost ? $FeedbackPost : array();
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
    
    public function get_service_txnreport()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
               
                    if ($user_info['is_block'] == 0) {
                        
                        $my_reports=permsn_access(42,"FETCH"); 
                        $child_reports=permsn_access(43,"FETCH"); 
                        $all_reports=permsn_access(52,"FETCH"); 
                        
                        if($my_reports || $child_reports || $all_reports)
                        {
                            $params=$this->input->post();
                            
                            $params['type']=isset($params['type'])?((is_string($params['type']))?trim($params['type']):""):"";
                            
                            $allowed_reqtype=array("Today","Samemonth","Previousmonth");
                            
                            if(in_array($params['type'],$allowed_reqtype))
                            {
                                
                                $get_distinct_categories=$this->Main_model->get_distinct_service_categories();
                                
                                if($get_distinct_categories)
                                {
                                
                                if($my_reports)
                                {
                                    $get_records=$this->Main_model->get_retailer_wise_servicebreakup($user_info['user_id'],$params['type']);
                                        
                                }else if($child_reports)
                                {
                                    
                                    $get_all_childs=$this->Main_model->get_allchild_userid($user_info['user_id']);
                                    if($get_all_childs)
                                    {
                                        
                                        $child_array=array();
                                        foreach($get_all_childs as $ck=>$cv)
                                        {
                                            $child_array[]=$cv['user_id'];
                                        }
                                        if(count($child_array)>0)
                                        {
                                            $get_records=$this->Main_model->get_childs_wise_servicebreakup($child_array,$params['type']);
                                            
                                        }else{
                                            $data['error'] = 1;
                                            $data['error_desc'] = 'Something went wrong, try again later';
                                            $data['msg'] = NULL;
                                            echo json_encode($data);
                                            exit;
                                        }
                                        
                                        
                                    }else{
                                        $get_records=array();
                                    }
                                    
                                }else if($all_reports)
                                {
                                    $get_records=$this->Main_model->get_allusers_servicebreakup($params['type']);
                                    
                                }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Invalid Request Type';
                                    $data['msg'] = NULL;
                                    echo json_encode($data);
                                    exit;
                                }
                                
                                $category_list=array();   
                                    
                                foreach($get_distinct_categories as $ck=>$cv)
                                {
                                    $category_list[]=$cv['category_type'];
                                }    
                                    
                                $data['error']=0;
                                $data['error_desc']=null;
                                $data['msg']=$get_records?$get_records:array();
                                $data['category_list']=$category_list; 
                                
                                    
                                    
                                }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to get category list';
                                    $data['msg'] = NULL;
                                }
                                
                                
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Request Type';
                                $data['msg'] = NULL;
                            }
                            
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Unauthorize Access';
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
            
            
        }else{
            redirect('Dashboard');
        }
    }



}

?>
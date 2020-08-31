<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Reports extends CI_Controller {

        
    function index()
    {
        $e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
        if($user_info)
        {
            if($user_info['is_block']==0)
            {	

                if (page_access(33) || page_access(46)) 
                {
                    
                $this->load->view('Dashboard/templates/header');
                $this->load->view('Reports/reports');
                   
                if (page_access(46)) 
                {
                    $this->load->view('Reports/users_accountstatement');
                }else{
                    $this->load->view('Reports/accountstatement');
                }
                    
                
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
        
    public function AccountStatement()
    {
        
	$e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
        if($user_info)
        {
            if($user_info['is_block']==0)
            {	

                if (page_access(33)) 
                {
                    
                $this->load->view('Dashboard/templates/header');
                $this->load->view('Reports/reports');
                $this->load->view('Reports/accountstatement');
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
        
    public function UsersAccountstatment()
    {
        $e = $this->session->userdata('userid'); 
	$user_info=$this->Main_model->user_acntid($e);
        if($user_info)
        {
            if($user_info['is_block']==0)
            {	

                if (page_access(46)) 
                {
                    
                $this->load->view('Dashboard/templates/header');
                $this->load->view('Reports/reports');
                $this->load->view('Reports/users_accountstatement');
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
	
	
	public function account_statement() 
    {

        $from = $this->input->post('from');
        $to = $this->input->post('to');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($from)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if (page_access(33)) {
                    if ($user_info['is_block'] == 0) {
                        
						$this->load->model('Test_model');
						$user_data = $this->Test_model->fetch_mergereports($user_info['user_id'], $user_info['role_id'], $from, $to);
						if($user_data){
						$i = 1;
						foreach ($user_data as $u) {       
							
						$getchrgcomm = $this->Test_model->getTxRcdCommData($user_info['user_id'],$u['txnid']);
						
						if($u['sname'] == 'REFUND')
                        {
						
										$getcommtdsonrefund = $this->Test_model->getcommtdsonrefund($user_info['user_id'],$u['txnid']);
										$commssn=$getcommtdsonrefund['trans_amt']-$getcommtdsonrefund['charged_amt']; 
										$commsnamount = isset($getcommtdsonrefund['comm_amnt']) ? $getcommtdsonrefund['comm_amnt'] :(($commssn)>0?$commssn:0);
										$tds = isset($getcommtdsonrefund['tds_amnt']) ? $getcommtdsonrefund['tds_amnt'] : 0;
										$amount = $u['clbal'] - $commsnamount + $tds;
						
						$data['data'][] = array($i,$u['date'],$u['stype']. '  (' .$u['sname'].' - '.$u['txnid']. ')',round($getcommtdsonrefund['trans_amt'], 2),round($getcommtdsonrefund['charged_amt'], 2),  round($commsnamount, 2), round($tds, 2),round($amount, 2));
						 
						}
                        elseif ($u['sname'] == 'DEPOSIT' || $u['sname'] == 'TRANSFER TO')
                        { 
						
						/***in  deposit case trans amnt and chrg amnt are same */
						
						                $commsnamount = isset($getchrgcomm['comm_amnt']) ? $getchrgcomm['comm_amnt'] :0;
                                        $tds = isset($getchrgcomm['tds_amnt']) ? $getchrgcomm['tds_amnt'] : 0;
                                        $amount = $u['clbal'];
						
						$data['data'][] = array($i,$u['date'],$u['stype']. '  (' .$u['sname'].' -  '.$u['txnid']. ')',round($u['amt'], 2),round($u['amt'], 2), round($commsnamount, 2), round($tds, 2),round($amount, 2));
						
						}elseif ($u['sname'] == 'WITHDRAW' ||  $u['sname'] == 'TRANSFER FROM')
                        {
										$commssn=0;
						                $commsnamount = isset($getchrgcomm['comm_amnt']) ? $getchrgcomm['comm_amnt'] :(($commssn)>0?$commssn:0);
                                        $tds = isset($getchrgcomm['tds_amnt']) ? $getchrgcomm['tds_amnt'] : 0;
                                        $amount = $u['clbal'];
						
						$data['data'][] = array($i,$u['date'],$u['stype']. '  (' .$u['sname'].' -  '.$u['txnid']. ')',round($u['amt'], 2),round($u['amt'],2), round($commsnamount, 2), round($tds, 2),round($amount, 2));
						
						
						}elseif ($u['sname'] == 'SETTLEMENT')
                        {
							
						            $getcomm_rldt_Aeps=$this->Test_model->getcomm_rldt_Aeps_settlmnt($u['txnid']);
                                    $gettds_rldt_Aeps=$this->Test_model->gettds_rldt_Aeps_settlmnt($u['txnid']);
						
								    $commsnamount = isset($getcomm_rldt_Aeps['amount']) ? $getcomm_rldt_Aeps['amount'] :0;
                                    $tds = isset($gettds_rldt_Aeps['amount']) ? $gettds_rldt_Aeps['amount'] : 0;
                                    $amount = $u['clbal']+$commsnamount-$tds;
                                       
							$data['data'][] = array($i,$u['date'],$u['stype']. '  (' .$u['sname'].' -  '.$u['txnid']. ')',round($u['amt'], 2),round($u['amt'], 2), round($commsnamount, 2), round($tds, 2),round($amount, 2));
						
							
						}
						else{
							
							if(isset($getchrgcomm['trans_amt'])){

								$cal_comm=$getchrgcomm['trans_amt']-$getchrgcomm['charged_amt'];

							}else{

								$cal_comm=0;     

							}


						                $commsnamount = isset($getchrgcomm['comm_amnt']) ? $getchrgcomm['comm_amnt'] :(($cal_comm)>0?$cal_comm:0);
                                        $tds = isset($getchrgcomm['tds_amnt']) ? $getchrgcomm['tds_amnt'] : 0;
                                        $amount = $u['clbal'] + $commsnamount - $tds;
										
						$data['data'][] = array($i,$u['date'],$u['stype']. '  (' .$u['sname'].' -  '.$u['txnid']. ')',round($getchrgcomm['transamt'], 2),round($getchrgcomm['charged_amt'], 2), round($commsnamount, 2), round($tds, 2),round($amount, 2));
						
							
						}
                                       
                          
						  $i++;
                           
                        }
/* 
                        $data['error_data'] = 0;
                        $data['error_desc'] = NULL;
                        $data['data'] = $data['data'] ? $data['data'] : [];  */
					}else{
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
                    $data['error_data'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
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
	
    public function new_account_statement() 
    {

        $from = $this->input->post('from');
        $to = $this->input->post('to');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($from)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if (page_access(33)) {
                    if ($user_info['is_block'] == 0) {
                        
						$this->load->model('Inst_model');
						$user_data = $this->Inst_model->fetch_mergereports($user_info['user_id'], $user_info['role_id'], $from, $to);
						if($user_data)
                        {
						$i = 1;
						foreach ($user_data as $u) 
                        {       
							
                if ($u['status'] == 'CREDIT') 
                {
                            $amounticon = '<span style="color: green;font-size: larger;">+</span>';
                            $commicon= '<span style="color: red;font-size: larger;">-</span>';
                            $tdsicon='<span style="color: green;font-size: larger;">+</span>';
                    
                            $amount = $u['clbal'] - $u['commamount'] + $u['tdsamount'];
                    
                } else if ($u['status'] == 'DEBIT') 
                {
                            $amounticon = '<span style="color: red;font-size: larger;">-</span>';
                            $commicon='';
                            $tdsicon='';
                    
                            $amount = $u['clbal'];
                            
                } else if ($u['status'] == 'SUCCESS' || $u['status'] == 'PENDING' || $u['status'] == 'REVIEW' || $u['status'] == 'FAILED' || $u['status'] == 'REFUND') 
                {
                            $amounticon = '<span style="color: red;font-size: larger;">-</span>';
                            $commicon= '<span style="color: green;font-size: larger;">+</span>';
                            $tdsicon= '<span style="color: red;font-size: larger;">-</span>';
                    
                            $amount = $u['clbal'] + $u['commamount'] - $u['tdsamount'];
                    
                } else 
                {
                            $amounticon = '';
                            $commicon='';
                            $tdsicon='';
                    
                            $amount = $u['clbal'];
                }
						
                            if($u['servicecode']=='CWS')
                            {
                                
                                $amount = $u['clbal'];
                                $u['chargeamt']=$u['transamt'];
                                $amounticon = '';
                                $commicon='';
                                $tdsicon='';
                                
                            }
                            
                            $amounticon=($u['chargeamt']==0)?"":$amounticon;
                            $commicon=($u['commamount']==0)?"":$commicon;
                            $tdsicon=($u['tdsamount']==0)?"":$tdsicon;
                            
						    
                            $data['data'][] = array($i,$u['date'],$u['stype']. '  (' .$u['sname'].' -  '.$u['txnid']. ')',round($u['transamt'], 2),$amounticon." ".round($u['chargeamt'], 2), $commicon." ".round($u['commamount'], 2), $tdsicon." ".round($u['tdsamount'], 2),round($amount, 2));            
                          
						  $i++;
                           
                        }

					}else{
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
                    $data['error_data'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
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
	    
        
	public function account_statement_fr_parent()
    {
	 $from = $this->input->post('from');
        $to = $this->input->post('to');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($from)) {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if (page_access(33)) {
                    if ($user_info['is_block'] == 0) {
                         $this->load->model('Inst_model');
                        $user_data = $this->Inst_model->fetch_parent_account_statement($user_info['user_id'], $user_info['role_id'], $from, $to);
						
					
					if($user_data){
				     $i = 1;
                                foreach ($user_data as $u) {
                                    if ($u['status'] == 'CREDIT') {
                                        $status = '<span style="color: green;font-size: larger;">+</span>';
                                    } else
                                    if ($u['status'] == 'DEBIT') {
                                        $status = '<span style="color: red;font-size: larger;">-</span>';
                                    } else
                                    if ($u['status'] == 'SUCCESS' || $u['status'] == 'PENDING' || $u['status'] == 'REVIEW' || $u['status'] == 'FAILED' || $u['status'] == 'REFUND') {
                                        $status = '<span style="color: red;font-size: larger;">-</span>';
                                    } else {
                                        $status = '';
                                    }

                                    if($u['sname'] == 'COMMISSION') 
                                    {
                                        if ($u['status'] == 'CREDIT') 
                                        {
                                            $tdsicon = '<span style="color: red;font-size: larger;">-</span>';
                                            $amount = $u['clbal'] - $u['tdsamount'];
                                            $amount=round($amount,2);

                                        } elseif ($u['status'] == 'DEBIT') 
                                        {

                                            $tdsicon = '<span style="color: green;font-size: larger;">+</span>';
                                            $amount = $u['clbal'] + $u['tdsamount'];
                                            $amount=round($amount,2);
                                        }

                                    } else {

                                        $tdsicon = "";
                                        $amount = $u['clbal'];
                                    }

                                    $tdsicon=$u['tdsamount']==0?"":$tdsicon;
									
                                    $data['data'][] = array($i,$u['date'], $u['stype'], $status . ' ' . round($u['amt'], 2),$tdsicon." ".round($u['tdsamount'],2), round($amount, 2));
									
									
									
									   /*  $data['data'][] = array($u['date'],$u['stype'], $getcommtdsonrefund['trnsamnt'], $status . ' ' .$u['amt'], $status . ' ' .$getcommtdsonrefund['comm_amnt'], $status . ' ' .$getcommtdsonrefund['tds_amnt'],$u['clbal']); */
										
                                    $i++;
                                }
                                $result['error_data'] = 0;
                                $result['msg'] = 'Result Successfull';
                                $result['data'] = ($data['data']);
						 
						 
					}else{
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
                    $data['error_data'] = 1;
                    $data['error_desc'] = 'Unauthorised access';
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
        
        
    public function get_accountstatement_byuserid()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                if (page_access(46)) 
                {
                    if ($user_info['is_block'] == 0) 
                    {
                        
                        $params=$this->input->post();
                        
                        $params['from']=isset($params['from'])?(is_string($params['from'])?trim($params['from']):""):"";
                        $params['to']=isset($params['to'])?(is_string($params['to'])?trim($params['to']):""):"";
                        $params['party']=isset($params['party'])?(is_string($params['party'])?trim($params['party']):""):"";
                        
                        $date_regex="/^[0-9]{4}-(0[1-9]|1[0-2]|[1-9])-(0[1-9]|[1-2][0-9]|3[0-1]|[1-9])$/";
                        
                        if(preg_match($date_regex,$params['from']) && preg_match($date_regex,$params['to']))
                        {
                        
                            if($params['party']!="")
                            {
                            
                                $this->load->model('Inst_model');
                                
                                $get_userdetails=$this->Inst_model->user_info($params['party']);
                                
                                if($get_userdetails)
                                {
                                
                                if($get_userdetails['role_id']==4)
                                {
                                    
                    $retailer_summary = $this->Inst_model->fetch_mergereports($get_userdetails['user_id'], $get_userdetails['role_id'], $params['from'], $params['to']);
                        
                            if($retailer_summary)
                            {
                                
                                $i = 1;
                                foreach ($retailer_summary as $u) 
                                {       

                                        if ($u['status'] == 'CREDIT') 
                                        {
                                                    $amounticon = '<span style="color: green;font-size: larger;">+</span>';
                                                    $commicon= '<span style="color: red;font-size: larger;">-</span>';
                                                    $tdsicon='<span style="color: green;font-size: larger;">+</span>';

                                                    $amount = $u['clbal'] - $u['commamount'] + $u['tdsamount'];

                                        } else if ($u['status'] == 'DEBIT') 
                                        {
                                                    $amounticon = '<span style="color: red;font-size: larger;">-</span>';
                                                    $commicon='';
                                                    $tdsicon='';

                                                    $amount = $u['clbal'];

                                        } else if ($u['status'] == 'SUCCESS' || $u['status'] == 'PENDING' || $u['status'] == 'REVIEW' || $u['status'] == 'FAILED' || $u['status'] == 'REFUND') 
                                        {
                                                    $amounticon = '<span style="color: red;font-size: larger;">-</span>';
                                                    $commicon= '<span style="color: green;font-size: larger;">+</span>';
                                                    $tdsicon= '<span style="color: red;font-size: larger;">-</span>';

                                                    $amount = $u['clbal'] + $u['commamount'] - $u['tdsamount'];

                                        } else 
                                        {
                                                    $amounticon = '';
                                                    $commicon='';
                                                    $tdsicon='';

                                                    $amount = $u['clbal'];
                                        }

                                    if($u['servicecode']=='CWS')
                                    {

                                        $amount = $u['clbal'];
                                        $u['chargeamt']=$u['transamt'];
                                        $amounticon = '';
                                        $commicon='';
                                        $tdsicon='';

                                    }

                                    $amounticon=($u['chargeamt']==0)?"":$amounticon;
                                    $commicon=($u['commamount']==0)?"":$commicon;
                                    $tdsicon=($u['tdsamount']==0)?"":$tdsicon;


                                    $data['data'][]=array(
                                    "srno"=>$i,
                                    "datetime"=>$u['date'],
                                    "narration"=>$u['stype']. '  (' .$u['sname'].' -  '.$u['txnid']. ')',
                                    "amount"=>round($u['transamt'], 2),
                                    "chargedamount"=>$amounticon." ".round($u['chargeamt'], 2),
                                    "comm_amount"=>$commicon." ".round($u['commamount'], 2),
                                    "tds_amount"=>$tdsicon." ".round($u['tdsamount'], 2),
                                    "balance"=>round($amount, 2)
                                    );
                                    
                                  $i++;

                                }

                            }else{
                                  $data['data'] = array();
                            }
                                    
                                }
                                else{
                                    
        $distributor_data = $this->Inst_model->fetch_parent_account_statement($get_userdetails['user_id'], $get_userdetails['role_id'], $params['from'], $params['to']);
						
        if($distributor_data)
        {
            
            $i=1;
            foreach ($distributor_data as $u) 
            {
                if ($u['status'] == 'CREDIT') {
                    $status = '<span style="color: green;font-size: larger;">+</span>';
                } else
                if ($u['status'] == 'DEBIT') {
                    $status = '<span style="color: red;font-size: larger;">-</span>';
                } else
                if ($u['status'] == 'SUCCESS' || $u['status'] == 'PENDING' || $u['status'] == 'REVIEW' || $u['status'] == 'FAILED' || $u['status'] == 'REFUND') {
                    $status = '<span style="color: red;font-size: larger;">-</span>';
                } else {
                    $status = '';
                }

                if($u['sname'] == 'COMMISSION') 
                {
                    if ($u['status'] == 'CREDIT') 
                    {
                        $tdsicon = '<span style="color: red;font-size: larger;">-</span>';
                        $amount = $u['clbal'] - $u['tdsamount'];
                        $amount=round($amount,2);

                    } elseif ($u['status'] == 'DEBIT') 
                    {

                        $tdsicon = '<span style="color: green;font-size: larger;">+</span>';
                        $amount = $u['clbal'] + $u['tdsamount'];
                        $amount=round($amount,2);
                    }

                } else {

                    $tdsicon = "";
                    $amount = $u['clbal'];
                }

                $tdsicon=$u['tdsamount']==0?"":$tdsicon;
                

                $data['data'][]=array(
                "srno"=>$i,
                "datetime"=>$u['date'],
                "narration"=>$u['stype'],
                "amount"=>$status . ' ' . round($u['amt'], 2),
                "tds_amount"=>$tdsicon." ".round($u['tdsamount'], 2),
                "balance"=>round($amount, 2)
                );
                    
                $i++;
            }
            
            
        }else{
            $data['data'] = array();
        }
                                    
                                    
                                }
                                    
                                $data['error']=0;
                                $data['msg']='Request Completed Successfully';
                                $data['userdata']=$get_userdetails;
                                $data['daterange']=date_format(date_create($params['from']),"Y-m-d")." to ".date_format(date_create($params['to']),'Y-m-d');
                                
                                }else{
                                    $data['error'] = 1;
                                    $data['error_desc'] = 'Unable to find userdata';
                                    $data['msg'] = NULL;
                                }
                                    
                            }else{
                                $data['error'] = 1;
                                $data['error_desc'] = 'Invalid Party Id';
                                $data['msg'] = NULL;
                            }
                        
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Invalid From or To Date';
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
        
	public function merge_report()
    {
		
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
            if (page_access(33)) {
            if ($user_info['is_block'] == 0) {
                         $data = array();
  /* $this->load->model('Inst_model');
                    $user_data = $this->Inst_model->Datatable_search_mergereport($user_info['user_id'], $user_info['role_id'], $from, $to, $cell);
					print_r($user_data);exit;
                    if ($user_data) {
                        $i = 1;
                        foreach ($user_data as $u) {
                            if ($u['status'] == 'CREDIT') {
                                $status = 'CREDIT';
                            } else
                            if ($u['status'] == 'DEBIT') {
                                $status = 'DEBIT';
                            } else
                            if ($u['status'] == 'SUCCESS' || $u['status'] == 'PENDING' || $u['status'] == 'REVIEW' || $u['status'] == 'FAILED' || $u['status'] == 'REFUND') {
                                $status = 'DEBIT';
                            } else {
                                $status = 'Undefined';
                            }
                            $data['data'][] = array($u['date'], $u['txnid'], $u['sname'], $u['stype'], number_format($u['amt'], 2), $status, number_format($u['clbal'], 2));
                            $i++;
                        }
                    } else {
                        $data['data'] = array();
                    }  */
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

                                $user_data = $this->Inst_model->Datatable_search_mergereport($user_info['user_id'], $user_info['role_id'], $from, $to, $cell);
								
                                if ($user_data) {
									
								 $i = 1;	
								
                        foreach ($user_data as $u) {
							
                        /*   $charge = number_format($u['clbal'] - $u['amt'], 4); */
						$getchrgcomm = $this->Inst_model->getTxRcdCommData($u['txnid']);
						print_r($getchrgcomm);
						  foreach ($getchrgcomm as $c) {
										
                                      /*  $transamount = $c['trans_amt'] ;
                                       $chargedamount =  $c['charged_amt'];
									     $commsnamount = $c['comm_amnt'];
										  $tds = $c['tds_amnt']; */

						 }
                            /* $result['data'][] = array($u['date'],$u['stype'], $u['amt'], $chargedamount, $commsnamount, $tds,$u['clbal']);
                            $i++; */

                           
                           
                        }
			
                                    /* $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_mergereport($user_info['user_id'], $user_info['role_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_mergereport($user_info['user_id'], $user_info['role_id'], $from, $to, $cell),
                                        "data" => $user_data
                                    ); */
                                } else {
                                    $result = array(
                                        "draw" => $_POST['draw'],
                                        "recordsTotal" => $this->Inst_model->count_all_mergereport($user_info['user_id'], $user_info['role_id'], $from, $to),
                                        "recordsFiltered" => $this->Inst_model->Datatable_num_mergereport($user_info['user_id'], $user_info['role_id'], $from, $to, $cell),
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

}

?>
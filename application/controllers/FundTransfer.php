<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FundTransfer extends CI_Controller {

    public function index(){
    	$e = $this->session->userdata('userid'); 
    	$user_info=$this->Main_model->user_acntid($e);
    	if($user_info){
        	if($user_info['is_block']==0){	
            	if($user_info['role_id']==4 || $user_info['role_id']==3) {
                    redirect ('FundTransfer/FundToRetailer');
            	}else{
                    redirect ('FundTransfer/TransactionHistory');
            		
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
	
	function FundToRetailer(){
        $e = $this->session->userdata('userid'); 
        $user_info=$this->Main_model->user_acntid($e);
        if($user_info){
            if($user_info['is_block']==0){
                $this->load->view('Dashboard/templates/header');
                $this->load->view('FundTransfer/fundtransfer_sidebar');
                $this->load->view('FundTransfer/retailerlist');
            }else{
                $this->session->sess_destroy();
                redirect ('Login');
            }

        }else{
            $this->session->sess_destroy();
            redirect ('Login');
        }
	}

    function OnlinePayment(){
        $e = $this->session->userdata('userid'); 
        $user_info=$this->Main_model->user_acntid($e);
        if($user_info){
            if($user_info['is_block']==0){
                $this->load->view('Dashboard/templates/header');
                $this->load->view('FundTransfer/fundtransfer_sidebar');
                $this->load->view('FundTransfer/onlinepayment');
            }else{
                $this->session->sess_destroy();
                redirect ('Login');
            }

        }else{
            $this->session->sess_destroy();
            redirect ('Login');
        }
    }
    
    function LoadRequest(){
        $e = $this->session->userdata('userid'); 
        $user_info=$this->Main_model->user_acntid($e);
        if($user_info){
            if($user_info['is_block']==0){
                $this->load->view('Dashboard/templates/header');
                $this->load->view('FundTransfer/fundtransfer_sidebar');
                $this->load->view('FundTransfer/loadrequest');
            }else{
                $this->session->sess_destroy();
                redirect ('Login');
            }

        }else{
            $this->session->sess_destroy();
            redirect ('Login');
        }
    }
    
    public function Bank_fr_dffrnt_role(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            // print_r($user_info);
            $data = array();
            if ($user_info) {
                if ($user_info['is_block'] == 0) {
                    $fetchBankName = $this->Main_model->fetch_bank_list();
                    
                    $data['error'] = 0;
                    $data['error_desc'] = NULL;
                    $data['msg'] = 'ok';
                    $data['data'] = $fetchBankName ? $fetchBankName :array();



                    /*$fetchBankName=array();
                    if ($user_info['parent_id'] == 0) {
                        $fetchBankName = $this->Main_model->fetch_bank_list();
                    }else{
                        $parent_role = $this->Main_model->get_user_parent_role($user_info['parent_id']);
                        print_r($parent_role);
                        if($parent_role){
                            if($parent_role['role_id']==1){
                                $fetchBankName = $this->Main_model->fetch_bank_list();
                            } else{  
                                $fetchBankName = ['ParentFirstName' => $parent_role['first_name'], 'ParentLastName' => $parent_role['last_name'], 'ParentMobile' => $parent_role['mobile']];
                            }

                            $data['error'] = 0;
                            $data['error_desc'] = NULL;
                            $data['msg'] = 'ok';
                            $data['data'] = $fetchBankName ? $fetchBankName :array(); 
                        }else{
                            $data['error'] = 1;
                            $data['error_desc'] = 'Unable To Process Request,Please contact admin';
                            $data['msg'] = NULL;
                        }

                    }*/

                        // $data['error'] = 0;
                        // $data['error_desc'] = NULL;
                        // $data['msg'] = 'ok';
                        // $data['data'] = $fetchBankName ? $fetchBankName :array();

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
                    $data['error'] = 0;
                    $data['error_desc'] = NULL;
                    $data['msg'] = NULL;
                    $data['data'] = $PaymentRequest ? $PaymentRequest : array();
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

        $Mode = ['IMPS' => 'IMPS', 'NEFT' => 'NEFT', 'CASH' => 'CASH', 'RTGS' => 'RTGS', 'CHEQUE' => 'CHEQUE', 'OTHERS' => 'OTHERS'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
                    if ($user_info['is_block'] == 0) {
                        if ($BankName) {

                            $bank_dtl = $this->Main_model->fetch_bank_dtl_rqst_by_usr($BankName);
                            if ($bank_dtl) {
                                $insertbankName = $bank_dtl['bank_name'] . ' - ' . $bank_dtl['account_number'];
                                
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

                            } else {
                                $data['error'] = 1;
                                $data['error_desc'] = 'Bank Details Not Exist';
                                $data['msg'] = NULL;
                            }
                                
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

    public function AddfundToRetailer(){
        $b = All_Regex();
        $id = $this->input->post('userid');
        $blnc = floatval(trim($this->input->post('amount')));
        $py_md = strtoupper(trim($this->input->post('paymode')));
        $bnk_nar = $this->input->post('note');

        if (isset($id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->userInfoByUserId($e);
            $data = array();
            if($user_info){
               
                if ($user_info['is_block'] == 0) {
                    if($blnc && preg_match('/' . $b['Amount'] . '/', $blnc)){
                        if($py_md && in_array($py_md, array("CREDIT", "CASH", "BABK"))){
                            if($bnk_nar && preg_match('/' . $b['Text'] . '/', $bnk_nar)){
                                $usrdtl = $this->Main_model->userInfoByUserId($id);
                                if($usrdtl){
                                    if ($usrdtl['is_active'] == 1) {
                                        if ($usrdtl['is_block'] == 0) {
                                                switch($user_info['role_id']){
                                                    case '4': // retailer to retailer
                                                        if($usrdtl['role_id'] == 4){
                                                            $isValidUser = 'Y';
                                                        }else{
                                                            $isValidUser = 'N';
                                                        }
                                                        break;
                                                    case '3': // distributor to retailer
                                                        $parent_role = $this->Main_model->get_user_parent_role($usrdtl['parent_id']);
                                                        if($parent_role && $parent_role['user_id'] == $user_info['user_id']){
                                                            $isValidUser = 'Y';
                                                        }else{
                                                            $isValidUser = 'N';
                                                        }
                                                        break;
                                                    default:
                                                        $isValidUser = 'N';
                                                }

                                                

                                                if ($isValidUser == 'Y'){

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
                                                            'credit_txnid' =>$dist_txn_id,
                                                            'user_id' =>$user_info['user_id'],
                                                            'bank_name' => 'NA',
                                                            'txn_type' => 'TRANSFER FROM',
                                                            'payment_mode' => $py_md,
                                                            'amount' => $chrge,
                                                            'opening_balance' => $opn_blnc_ofdbstr,
                                                            'closing_balance' => $cls_blnc_dstr,
                                                            'updated_on' => date('Y-m-d H:i:s'),
                                                            'reference_number' => $py_md,
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
                                                            'user_id' => $usrdtl['user_id'],
                                                            'bank_name' => 'NA',
                                                            'txn_type' => 'TRANSFER To',
                                                            'payment_mode' => $py_md,
                                                            'amount' => $chrge,
                                                            'opening_balance' => $opn_blnc,
                                                            'closing_balance' => $cls_blnc_rtlr,
                                                            'updated_on' => date('Y-m-d H:i:s'),
                                                            'reference_number' => $py_md,
                                                            'remarks' => $bnk_nar,
                                                            'txn_code' => dstrbtr_trnsfer_cd(),
                                                            'status' =>$stsrtlr,
                                                            'updated_by'=>$user_info['user_id'],
                                                            'is_due' => 0,
                                                            'is_received' => 1,
                                                            'created_on'=>date('Y-m-d H:i:s'),
                                                            'created_by'=>$user_info['user_id']

                                                        );
                        
                                                        $get_data = $this->Main_model->update_cdt_blnc_by_dstbtr($user_info['user_id'], $insert_dstbtr, $id, $insert_retlr);

                                                        if ($get_data) {
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
                                                    $data['error'] = 1;
                                                    $data['error_desc'] = 'Unable To Process Request, Please contact to system administrator';
                                                    $data['msg'] = NULL;
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
                            $data['error_desc'] = 'Payment Mode Invalid';
                            $data['msg'] = NULL;
                        }
                    }else{
                        $data['error'] = 1;
                        $data['error_desc'] = 'Invalid Amount';
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
	
	function WB() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array();
            $userid = $this->input->post('userid');
            // print_r($_POST);
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            if ($user_info) {
                if($user_info['is_block']==0 && ($user_info['role_id']==4 || $user_info['role_id']==3)){
                    $fetchdata = $this->Main_model->get_user_parent_role($userid);
                    if ($fetchdata) {
                        $data['error'] = 0;
                        $data['error_desc'] = null;
                        $data['data'] = $fetchdata; 
                        $data['msg'] = null;
                    } else {
                        $data['error'] = 1;
                        $data['error_desc'] = 'No agent found';
                        $data['msg'] = null;
                    }
                } else {
                    $data['error'] = 1;
                    $data['error_desc'] = 'Not allowed to use this feature';
                    $data['msg'] = null;
                }
            } else {
                $data['error'] = 2;
                $data['error_desc'] = 'Unauthorized access';
                $data['msg'] = null;
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
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
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
	   function BankNameSelect() {
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
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

		public function SelectState() {
	    $bankname = $this->input->post('bank_name');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
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
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
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
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
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
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
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
	
	public function BankIfsc(){
	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();

            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
               
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
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
		
		
	public function ChrgCommOnTransaction(){
		$param=$this->input->post('data');
       	  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) {
				if ($user_info['role_id']==4) {
                if ($user_info['is_block'] == 0) {
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
						$this->load->model('Inst_model'); 	
                        						
					  $chkservicestat = $this->Inst_model->fetch_service_prov('DMT');
                            if ($chkservicestat) {
                                //print_r($chkservicestat);exit;
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
                    $chkusr_instnpaysession = $this->session->userdata('instnpaysession');
                    if ($chkusr_instnpaysession && $chkusr_instnpaysession != null) {
						$this->load->model('Inst_model'); 	
                    						
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
		
public function FundTransferRetailerList() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $e = $this->session->userdata('userid');
        $user_info = $this->Main_model->user_acntid($e);
        if ($user_info) {
            if ($user_info['role_id']==4 || $user_info['role_id']==3) {
                if ($user_info['is_block'] == 0) {
                    $agent = $this->input->post('agent');
                    $search = $this->input->post('search');
                    $length = $this->input->post('length');
                    $email = $this->session->userdata('email');
                    $this->load->model('Inst_model');
                    if ($user_info) {
                        $result = array();
                        $user_data = $this->Inst_model->Datatable_fetch_Retailer_myorder($agent, $user_info['user_id'],$user_info['role_id']);
                        if ($user_data) {

                            $result = array(
                                "draw" => $_POST['draw'],
                                "recordsTotal" => $this->Inst_model->count_all_Retailer_myorder($agent, $user_info['user_id'],$user_info['role_id']),
                                "recordsFiltered" => $this->Inst_model->Datatable_num_Retailer_myorder($agent, $user_info['user_id'],$user_info['role_id']),
                                "data" => $user_data
                            );
                        } else {
                            $result = array(
                                "draw" => $_POST['draw'],
                                "recordsTotal" => $this->Inst_model->count_all_Retailer_myorder($agent, $user_info['user_id'],$user_info['role_id']),
                                "recordsFiltered" => $this->Inst_model->Datatable_num_Retailer_myorder($agent, $user_info['user_id'],$user_info['role_id']),
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
	


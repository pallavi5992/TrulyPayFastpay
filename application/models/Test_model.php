<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	/* DEPOSIT, WITHDRAW, TRANSFER TO,  TRANSFER FROM, COMMISSION, REVERSAL, CASHBACK, TDS, REFUND, SETTLEMENT */

 
	   function fetch_mergereports($accnt_id, $role_id, $from, $to) {
	        if($role_id==4){
              // $txn_type=array('REFUND','DEPOSIT','WITHDRAW','SETTLEMENT');  
               $txn_type=array('CASHBACK','TDS','COMMISSION');
                
           }else{
             //$txn_type=array('REFUND','DEPOSIT','WITHDRAW');
                $txn_type=array('CASHBACK','TDS','COMMISSION');
           }
	 
        $from = date_format(date_create($from), 'Y-m-d');
        $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));
		
        $this->db->select('fstpytxn_id AS txnid,chargeamt AS amt,openingbal AS opbal,closingbal AS clbal,req_dt AS date,servicename AS sname,CONCAT(servicetype, " ( ", customer_no," )") AS stype,status AS status, id AS id');
        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $accnt_id));
        $this->db->where('req_dt >=', $from);
        $this->db->where('req_dt <=', $to);
        $sel1 = $this->db->get_compiled_select();
     
		
        $this->db->select('credit_txnid AS txnid,amount AS amt,opening_balance AS opbal,closing_balance AS clbal,created_on AS date,txn_type AS sname,remarks AS stype,status AS status,credithistory_tableid AS id');
        $this->db->from('credit_history');
        $this->db->where(array('user_id' => $accnt_id));
		 $this->db->where_not_in('txn_type',$txn_type);
        $this->db->where('created_on >=', $from);
        $this->db->where('created_on <=', $to);
        $sel2 = $this->db->get_compiled_select();
      
			
        $sel = $this->db->query("({$sel1}) UNION ALL ({$sel2})  order by date DESC, id DESC")->result_array();
		//echo $this->db->last_query();die;
        return $sel;
    }
	
	public function getTxRcdCommData($userid,$txnid){
		
		 $this->db->select('tr.trans_amt,tr.charged_amt,tr.comm_amnt,tr.tds_amnt,atx.chargeamt,atx.transamt');
		 $this->db->from('usertxn_table atx');
        $this->db->join('tax_record tr', 'atx.fstpytxn_id =tr.cbrt_id AND atx.user_id=tr.user_id','left');
        $this->db->where(array('atx.fstpytxn_id' => $txnid, 'atx.user_id' => $userid));
        $sel = $this->db->get();
        $s = $sel->row_array();
        if ($s) {
            return $s;
        }
	   
	} 
	
	
	function getcommtdsonrefund($userid,$refunfref){
       $this->db->select('tr.trans_amt,tr.charged_amt,tr.comm_amnt,tr.tds_amnt');
        $this->db->from('usertxn_table ut');
        $this->db->join('tax_record tr','ut.fstpytxn_id = tr.cbrt_id AND ut.user_id=tr.user_id','left');
        $this->db->where(array('ut.refund_ref'=>$refunfref));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if($q){
            return $q;
        }
    }
	
	
	function getcomm_rldt_Aeps_settlmnt($txnId){
        $this->db->from('credit_history cd');
        $this->db->where(array('cd.txn_code'=>$txnId,'cd.txn_type'=>'CASHBACK'));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if($q){
            return $q;
        }

    }

    function gettds_rldt_Aeps_settlmnt($txnId){
        $this->db->from('credit_history cd');
        $this->db->where(array('cd.txn_code'=>$txnId,'cd.txn_type'=>'TDS'));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if($q){
            return $q;
        }

    }
	
	function getcomm_tds_on_cashback($txnId){
       $this->db->select('cd.*,tr.trans_amt,tr.charged_amt');
        $this->db->from('credit_history cd');
        $this->db->join('tax_record tr','cd.txn_code = tr.cbrt_id','left');
        $this->db->where(array('cd.txn_code'=>$txnId));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if($q){
            return $q;
        }
    }
	
	
	
	
	 
	


}
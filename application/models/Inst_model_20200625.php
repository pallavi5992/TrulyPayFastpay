<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inst_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function init_log($usrid, $ip, $url, $rspns, $type) {
        $this->db->insert('apbox_logs', array('user_id' => $usrid, 'request_ip' => $ip, 'request' => $url, 'request_for' => $type, 'response' => $rspns, 'datetime' => date('Y-m-d H:i:s')));
    }

    public function checkuser_pln_dtl($role_id,$paln_id, $srvcd){  
        $this->db->select('mpr.*,sp.service_name,sp.code,sp.type,sp.served_by,sp.Amnt_by_routing,sp.supported_gateway');
        $this->db->from('plan_service_config mpr');
        $this->db->join('plan_config pln', "pln.plan_id=mpr.plan_id");
        $this->db->join('services sp', "sp.service_id=mpr.service_id");
        $this->db->where(array('mpr.plan_id' => $paln_id, 'mpr.service_id' => $srvcd, 'pln.is_active' => 1,'pln.plan_for_role' => $role_id));
        $sel = $this->db->get();
        curlRequertLogs(array($this->db->last_query()), 'checkuser_pln_dtl-query', 'instModel');
        $s = $sel->row_array();
        if ($s) {
            return $s;
        }
    }


    public function check_pln_amnt_rng($id,$plan_id,$service_id,$amount){
        $this->db->select('pamnt.*');
        $this->db->from('plan_service_config psc');
        $this->db->join('plan_amnt_slab_config pamnt', 'psc.plan_id=pamnt.plan_id AND psc.service_id=pamnt.service_id');
        $this->db->where(array('psc.plan_id' => $plan_id, 'psc.service_id' => $service_id,'pamnt.min_amnt<='=>$amount,'pamnt.max_amnt>='=>$amount));
        $sel = $this->db->get();
        curlRequertLogs(array($this->db->last_query()), 'check_pln_amnt_rng', 'instModel');
        $f = $sel->row_array();
        if ($f) {   
            return $f;
        }
    }

     public function check_pln_amnt_rng1($id,$plan_id,$service_id,$amount){
        $this->db->select('pamnt.*');
        $this->db->from('plan_service_config psc');
        $this->db->join('service_amnt_routing pamnt', 'psc.service_id=pamnt.service_id');
        $this->db->where(array('psc.plan_id' => $plan_id, 'psc.service_id' => $service_id,'pamnt.Min_amnt<='=>$amount,'pamnt.Max_amnt>'=>$amount));
        $sel = $this->db->get();
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }
   
    public function check_servc_amnt_rng($service_id,$amnt){
        $this->db->select('pamnt.*,v.vendor_id,v.vendor_name,v.vendor_library,v.is_down as gateway_down,v.is_active AS gateway_active');
        $this->db->from('services srv');
        $this->db->join('service_amnt_routing pamnt', 'srv.service_id=pamnt.service_id');
        $this->db->join('vendor_list v', 'v.vendor_id=pamnt.served_by');
        $this->db->where(array('srv.service_id' => $service_id,'pamnt.Min_amnt<='=>$amnt,'pamnt.Max_amnt>='=>$amnt,'v.is_active'=>1));
        $sel = $this->db->get();
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }

    }

     function UserTreeFetchForComm($usrid) {
        $sel = $this->db->get_where('users', array('user_id' => $usrid, 'is_active' => 1, 'is_block' => 0));
        $f = $sel->row_array();
        if ($f) {
            $sel1 = $this->db->get_where('users', array('user_id' => $f['parent_id'], 'is_active' => 1, 'is_block' => 0));
            $f1 = $sel1->row_array();

            $tree = $f1 ? [$f, $f1] : [$f];
            if ($tree) {

                return $tree;  
                  
            }
        }
    }

    public function user_info($user_id){

        $this->db->select('*');
        $sel = $this->db->get_where('users', array('user_id' => $user_id, 'is_active' => 1));
        $s = $sel->row_array();
        if ($s) {
            return $s;
        }

    }

      public function RetailerAllEntryWithoutAdmin($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet){
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        $this->db->set('rupee_balance', 'rupee_balance-' . $RetailerTxnEntry['chargeamt'], false);
        $this->db->where('user_id', $RetailerTxnEntry['user_id']);
        $this->db->update('users');

        if ($this->db->affected_rows() > 0) {
            $this->db->insert('usertxn_table', $RetailerTxnEntry);
            $inserted_id = $this->db->insert_id();
            if (count($CashbackCrdtEntry_reatiler) > 0 && count($TDSCreditHistoryEntryRetailer) > 0) {
                $this->db->insert('credit_history', $CashbackCrdtEntry_reatiler);

                $this->db->set('rupee_balance', 'rupee_balance+' . $CashbackCrdtEntry_reatiler['amount'], false);
                $this->db->where('user_id', $CashbackCrdtEntry_reatiler['user_id']);
                $this->db->update('users');

                $this->db->set('rupee_balance', 'rupee_balance-' . $TDSCreditHistoryEntryRetailer['amount'], false);
                $this->db->where('user_id', $TDSCreditHistoryEntryRetailer['user_id']);
                $this->db->update('users');

                $this->db->insert('credit_history', $TDSCreditHistoryEntryRetailer);
            }
            $this->db->insert('tax_record', $TaxRecordRet);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === TRUE) {
            return $inserted_id;
        }

    }

     public function parent_commission_without_Admin($parent_array){
     if (count($parent_array) > 0) {
            $this->db->trans_strict(FALSE);
            $this->db->trans_start();
            foreach ($parent_array as $key => $value) {
                $cdt = $this->db->insert('credit_history', $value['COM']);
                if ($cdt) {
                    $this->db->set('rupee_balance', 'rupee_balance+' . $value['COM']['amount'], false);
                    $this->db->where('user_id', $value['COM']['user_id']);
                    $this->db->update('users');

                    $this->db->set('rupee_balance', 'rupee_balance-' . $value['TDS']['amount'], false);
                    $this->db->where('user_id', $value['TDS']['user_id']);
                    $this->db->update('users');
                    $this->db->insert('credit_history', $value['TDS']);

                    $this->db->insert('tax_record', $value['TAX']);
                }
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === TRUE) {
                return true;
            }
        }
    }
       public function getopertor($ServiceId, $VendorId) {
        $this->db->from('service_vendor_config');
        $this->db->where(array('service_id' => $ServiceId, 'vendor_id' => $VendorId));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

    public function Instantlogs($insert_array) {
         return  $this->db->insert('instpy_logs',$insert_array);
       
    }

      function fetch_error_code($mapped_error, $vendrid) {
        $this->db->select('pe.*');
        $this->db->from('internal_errorcodes pe');
        $this->db->join('vendor_errcd_relation s_e', 's_e.intrnl_errorcodeid=pe.errorcode_id');
        $this->db->where('s_e.vendor_errorcode', $mapped_error);
        $this->db->where('s_e.vendor_id', $vendrid);
        $sel = $this->db->get();
        curlRequertLogs(array($this->db->last_query()), 'fetch_error_code', 'InstModel');
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }


       public function update_rchrg_rsp($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array) {


        if ($update_dt_array['status'] == 'FAILED') {

            $this->refund_Instpy_txn($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array);

        } else {

            $update_id = $update_dt_array['upd_id'];
            unset($update_dt_array['upd_id']);
          
            $this->db->update('usertxn_table', $update_dt_array, array('id' => $update_id));
        }
    }

    public function refund_Instpy_txn($RetailerTxnEntry, $CashbackCrdtEntry_reatiler, $TDSCreditHistoryEntryRetailer, $TaxRecordRet, $ParentArray, $update_dt_array){
         $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        $credittxnid = ch_txnid();
        $update_dt_array['status'] = 'REFUND';
        $update_dt_array['refund_dt'] = date('Y-m-d H:i:s');
        $update_dt_array['refund_ref'] = $credittxnid;
        $update_id = $update_dt_array['upd_id'];
        unset($update_dt_array['upd_id']);

        $this->db->update('usertxn_table', $update_dt_array, array('id' => $update_id));
        if ($this->db->affected_rows() > 0) {
            $finduser = $this->user_info($RetailerTxnEntry['user_id']);
            if ($finduser) {
                $opbal = $finduser['rupee_balance'];
                $refundamt = $RetailerTxnEntry['chargeamt'];
                $clbal = $opbal + $refundamt;

                $credit_array = array(
                    'credit_txnid' => $credittxnid,
                    'user_id' => $finduser['user_id'],
                    'bank_name' => "N/A",
                    'txn_type' => 'REFUND',
                    'payment_mode' => 'WALLET',
                    'amount' => $refundamt,
                    'opening_balance' => $opbal,
                    'closing_balance' => $clbal,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'reference_number' => "Refund of Rs. " . $refundamt . " Received For " . $RetailerTxnEntry['fstpytxn_id'] . ', Transaction Amount : Rs. ' . $RetailerTxnEntry['transamt'],
                    'remarks' => "Refund of Rs. " . $refundamt . " Received For " . $RetailerTxnEntry['fstpytxn_id'] . ', Transaction Amount : Rs. ' . $RetailerTxnEntry['transamt'],
                    'status' => 'CREDIT',
                    'updated_by' => $RetailerTxnEntry['user_id'],
                );
                $k = $this->db->insert('credit_history', $credit_array);
                if ($k) {

                    $this->db->set('rupee_balance', 'rupee_balance+' . $refundamt, false);
                    $this->db->where('user_id', $RetailerTxnEntry['user_id']);
                    $this->db->update('users');

                    if (date_format(date_create($RetailerTxnEntry['req_dt']), 'Y-m-d H:i:s') >= date('Y-m-01')) {
                        // WithIn Same Month Refund Retailer Entry//


                        $this->db->update('tax_record', array('gst_status' => 'CANCELLED', 'tds_status' => 'CANCELLED', 'updated_dt' => date('Y-m-d H:i:s'), 'updated_by' => $RetailerTxnEntry['user_id']), array('cbrt_id' => $RetailerTxnEntry['fstpytxn_id']));
                    } else {

                        $tax_new_entries1 = array(
                            "user_id" => $TaxRecordRet['user_id'],
                            "cbrt_id" => $TaxRecordRet['cbrt_id'],
                            "billing_model" => $TaxRecordRet['billing_model'],
                            "trans_amt" => $TaxRecordRet['trans_amt'],
                            "charged_amt" => $TaxRecordRet['charged_amt'],
                            "comm_amnt" => $TaxRecordRet['comm_amnt'],
                            "tds_amnt" => $TaxRecordRet['tds_amnt'],
                            "gst_amnt" => $TaxRecordRet['gst_amnt'],
                            "gst_status" => "PENDING",
                            "tds_status" => "PENDING",
                            'tax_type' => 'DEBIT',
                            "created_dt" => date('Y-m-d H:i:s'),
                            "updated_by" => $RetailerTxnEntry['user_id'],
                            "updated_dt" => date('Y-m-d H:i:s')
                        );

                        $this->db->insert('tax_record', $tax_new_entries1);
                        if (is_array($ParentArray)) {
                            if (count($ParentArray) > 0) {
                                foreach ($ParentArray as $key => $value) {
                                    $parent_comm_array_new_entry = array(
                                        "user_id" => $ParentArray[$key]['TAX']['user_id'],
                                        "cbrt_id" => $ParentArray[$key]['TAX']['cbrt_id'],
                                        "billing_model" => $ParentArray[$key]['TAX']['billing_model'],
                                        "trans_amt" => $ParentArray[$key]['TAX']['trans_amt'],
                                        "charged_amt" => $ParentArray[$key]['TAX']['charged_amt'],
                                        "comm_amnt" => $ParentArray[$key]['TAX']['comm_amnt'],
                                        "tds_amnt" => $ParentArray[$key]['TAX']['tds_amnt'],
                                        "gst_amnt" => $ParentArray[$key]['TAX']['gst_amnt'],
                                        'tax_type' => 'DEBIT',
                                        "gst_status" => "PENDING",
                                        "tds_status" => "PENDING",
                                        "created_dt" => date('Y-m-d H:i:s'),
                                        "updated_by" => $RetailerTxnEntry['user_id'],
                                        "updated_dt" => date('Y-m-d H:i:s')
                                    );
                                    $this->db->insert('tax_record', $parent_comm_array_new_entry);
                                }
                            }
                        }/*                         * **end ParentArray*** */
                    }///////////not within same month
                    // Reatiler Comm And TDs Refund //
                    if (count($CashbackCrdtEntry_reatiler) > 0) {
                        $finduserdata_for_comm = $this->user_info($CashbackCrdtEntry_reatiler['user_id']);

                        $identify_commision_from = $finduser['first_name'] . $finduser['last_name'] . ' ( ' . $finduser['mobile'] . ' )';

                        $comm_opbal = $finduserdata_for_comm['rupee_balance'];
                        $comm_clbal = $comm_opbal - $CashbackCrdtEntry_reatiler['amount'];
                        $comm_refund_id = ch_txnid();

                        $comm_refund_array = array(
                            'credit_txnid' => $comm_refund_id,
                            'user_id' => $CashbackCrdtEntry_reatiler['user_id'],
                            'bank_name' => 'NA',
                            'txn_type' => 'CASHBACK',
                            'payment_mode' => 'WALLET',
                            'amount' => $CashbackCrdtEntry_reatiler['amount'],
                            'opening_balance' => $comm_opbal,
                            'closing_balance' => $comm_clbal,
                            'updated_on' => date('Y-m-d H:i:s'),
                            'reference_number' => 'Cancel Commission From ' . $identify_commision_from . ' For ' . $RetailerTxnEntry['fstpytxn_id'],
                            'remarks' => 'Cancel Commission From ' . $identify_commision_from . ' For ' . $RetailerTxnEntry['fstpytxn_id'] . ', Transaction Amount : Rs. ' . $RetailerTxnEntry['transamt'],
                            'status' => 'DEBIT',
                            'updated_by' => $RetailerTxnEntry['user_id']
                        );

                        $this->db->set('rupee_balance', 'rupee_balance-' . $comm_refund_array['amount'], false);
                        $this->db->where('user_id', $comm_refund_array['user_id']);
                        $this->db->update('users');

                        $this->db->insert('credit_history', $comm_refund_array);
                    }

                    if (count($TDSCreditHistoryEntryRetailer) > 0) {

                        $finduserdata_for_tds = $this->user_info($TDSCreditHistoryEntryRetailer['user_id']);
                        if ($finduserdata_for_tds) {

                            $tds_opbal = $finduserdata_for_tds['rupee_balance'];
                            $tds_clbal = $tds_opbal + $TDSCreditHistoryEntryRetailer['amount'];
                            $tds_refund_id = ch_txnid();

                            $tds_refund_array = array(
                                'credit_txnid' => $tds_refund_id,
                                'user_id' => $TDSCreditHistoryEntryRetailer['user_id'],
                                'bank_name' => 'NA',
                                'txn_type' => 'TDS',
                                'payment_mode' => 'WALLET',
                                'amount' => $TDSCreditHistoryEntryRetailer['amount'],
                                'opening_balance' => $tds_opbal,
                                'closing_balance' => $tds_clbal,
                                'updated_on' => date('Y-m-d H:i:s.u'),
                                'reference_number' => 'TDS Refunded -> ' . $RetailerTxnEntry['fstpytxn_id'],
                                'remarks' => 'TDS Refunded -> ' . $RetailerTxnEntry['fstpytxn_id'] . ', Transaction Amount : Rs. ' . $RetailerTxnEntry['transamt'],
                                'status' => 'CREDIT',
                                'updated_by' => $RetailerTxnEntry['user_id']
                            );

                            $this->db->set('rupee_balance', 'rupee_balance+' . $tds_refund_array['amount'], false);
                            $this->db->where('user_id', $tds_refund_array['user_id']);
                            $this->db->update('users');

                            $this->db->insert('credit_history', $tds_refund_array);
                        }
                    }/*                     * **end count($TDSCreditHistoryEntryRetailer**** */

                    if (is_array($ParentArray)) {
                        if (count($ParentArray) > 0) {
                            foreach ($ParentArray as $key => $value) {
                                $finduserdata_forcashbackdebit = $this->user_info($value['COM']['user_id']);
                                if ($finduserdata_forcashbackdebit) {

                                    $cashback_rnd_opbal = $finduserdata_forcashbackdebit['rupee_balance'];
                                    $cashback_rfnd_clbal = $cashback_rnd_opbal - $value['COM']['amount'];
                                    $cashback_refund_id = ch_txnid();
                                    $cashback_reversal_array = array(
                                        'credit_txnid' => $cashback_refund_id,
                                        'user_id' => $value['COM']['user_id'],
                                        'bank_name' => 'NA',
                                        'txn_type' => 'COMMISSION',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $value['COM']['amount'],
                                        'opening_balance' => $cashback_rnd_opbal,
                                        'closing_balance' => $cashback_rfnd_clbal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => 'Commission Reversal -> ' . $RetailerTxnEntry['fstpytxn_id'],
                                        'remarks' => 'Commission Reversal -> ' . $RetailerTxnEntry['fstpytxn_id'] . ', Transaction Amount : Rs. ' . $RetailerTxnEntry['transamt'],
                                        'status' => 'DEBIT',
                                        'updated_by' => $RetailerTxnEntry['user_id']
                                    );

                                    $this->db->set('rupee_balance', 'rupee_balance-' . $cashback_reversal_array['amount'], false);
                                    $this->db->where('user_id', $cashback_reversal_array['user_id']);
                                    $this->db->update('users');
                                    $this->db->insert('credit_history', $cashback_reversal_array);
                                }


                                $finduserdata_for_tds = $this->user_info($value['TDS']['user_id']);

                                if ($finduserdata_for_tds) {

                                    $tds_opbal = $finduserdata_for_tds['rupee_balance'];
                                    $tds_clbal = $tds_opbal + $value['TDS']['amount'];
                                    $tds_refund_id = ch_txnid();

                                    $tds_refund_array = array(
                                        'credit_txnid' => $tds_refund_id,
                                        'user_id' => $value['TDS']['user_id'],
                                        'bank_name' => 'NA',
                                        'txn_type' => 'TDS',
                                        'payment_mode' => 'WALLET',
                                        'amount' => $value['TDS']['amount'],
                                        'opening_balance' => $tds_opbal,
                                        'closing_balance' => $tds_clbal,
                                        'updated_on' => date('Y-m-d H:i:s'),
                                        'reference_number' => 'TDS Refunded -> ' . $RetailerTxnEntry['fstpytxn_id'],
                                        'remarks' => 'TDS Refunded -> ' . $RetailerTxnEntry['fstpytxn_id'] . ', Transaction Amount : Rs. ' . $RetailerTxnEntry['transamt'],
                                        'status' => 'CREDIT',
                                        'updated_by' => $RetailerTxnEntry['user_id']
                                    );

                                    $this->db->set('rupee_balance', 'rupee_balance+' . $value['TDS']['amount'], false);
                                    $this->db->where('user_id', $value['TDS']['user_id']);
                                    $this->db->update('users');

                                    $this->db->insert('credit_history', $tds_refund_array);
                                }
                            }
                        }
                    }///////is_array($ParentArray//////
                    // End Reatiler Comm And TDs Refund //
                }/*                 * **end $k*** */

          
            }/*             * ***end finduser*** */
        }/*         * ****affected_rows end**** */


        $this->db->trans_complete();
        if ($this->db->trans_status() === TRUE) {
            return true;
        }

    }


     public function fetch_service_prov($code) {    
        $this->db->select('sp.*,v.vendor_id,v.vendor_name,v.vendor_library,v.is_down as gateway_down,v.is_active AS gateway_active');
        $this->db->from('services sp');  
        $this->db->join('vendor_list v', 'v.vendor_id=sp.served_by AND v.is_active = 1', 'left');
        $this->db->where(array('sp.is_active' => 1, 'sp.code' => $code));
        $sel = $this->db->get();
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }

    public function check_exstng_docfrservc_activation($acntid, $doc) {
        $s = $this->db->get_where('instpy_service_activation_docs', array('user_id' => $acntid, 'doc_for' => "SERVICE ACTIVATION", 'doc_name' => $doc));
        return $num = $s->row_array();
    }

    public function insert_docmnt_fr_srvc_activation($insert){
         $q = $this->db->insert('instpy_service_activation_docs', $insert);
        if ($q) {
            return $q;
        }

    }
	
	  public function fetch_bank_with_ho() {
        $sel = $this->db->get('ifsc_autoselect');
        $f = $sel->result_array();
        if ($f) {
            return $f;
        }
    }

    public function fetch_bank_by_ifsc($ifsc) {
        $sel = $this->db->get_where('ifsc_all', array('ifsc' => $ifsc));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }
	
	 function BankName() {
        $this->db->distinct('bank_name');
        $this->db->select('bank_name');
        $get = $this->db->get('ifsc_all');
        $access = $get->result_array();
        if ($access) {
            return $access;
        }
    }
	
	
	
	   function SelectState($bankname) {
        $this->db->distinct('state');
        $this->db->select('state');
        $get = $this->db->get_where('ifsc_all', array('bank_name' => $bankname));
        $access = $get->result_array();
        if ($access) {
            return $access;
        }
    }

    function SelectCity($bankname, $state) {
        $this->db->distinct('city');
        $this->db->select('city');
        $get = $this->db->get_where('ifsc_all', array('bank_name' => $bankname, 'state' => $state));
        $access = $get->result_array();
        if ($access) {
            return $access;
        }
    }

    function SelectBranch($bankname, $state, $city) {
        $this->db->distinct('branch');
        $this->db->select('branch');
        $get = $this->db->get_where('ifsc_all', array('bank_name' => $bankname, 'state' => $state, 'city' => $city));
        $access = $get->result_array();
        if ($access) {
            return $access;
        }
    }

    function GetIFSCCode($bankname, $state, $city, $branch) {
        $this->db->select('ifsc,bank_name,address,branch');
        $get = $this->db->get_where('ifsc_all', array('bank_name' => $bankname, 'state' => $state, 'city' => $city, 'branch' => $branch));
        $access = $get->row_array();
        if ($access) {
            return $access;
        }
    }
	
	/* function fetch_benefdetails($mobile){
		
	} */
	
	
	   public function checktxnmpin($mpin, $uid) {
      
		$mpin = md5($mpin);
        
        $this->db->where("mpin like binary", $mpin);
        $sel = $this->db->get_where('users', array('user_id' => $uid));
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }
	
	
  //....................................................Users Recharge Txn Table...........................................................

    function Datatable_search_recharge_myorder($userid, $from, $to, $cell) {
        $column = array(null, 'fstpytxn_id', 'opr_ref_no', 'customer_no', 'servicename', 'servicetype', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');

        $column_order = array(null, 'fstpytxn_id', 'opr_ref_no', 'customer_no', 'servicename', 'servicetype', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');

        $order = array('id' => 'desc');
        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $userid));
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'PREPAID'));
        $this->db->or_where(array('servicetype' => 'DTH'));
        $this->db->group_end();
        $i = 0;
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell']) {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        }
                        // if($i == $countindex-1)
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }

        $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function Datatable_fetch_recharge_myorder($userid, $from, $to, $cell) {
        $this->Datatable_search_recharge_myorder($userid, $from, $to, $cell);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }

    function Datatable_num_recharge_myorder($userid, $from, $to, $cell) {
        $this->Datatable_search_recharge_myorder($userid, $from, $to, $cell);

        return $this->db->count_all_results();
    }

    public function count_all_recharge_myorder($userid, $from, $to) {

        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $userid));
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'PREPAID'));
        $this->db->or_where(array('servicetype' => 'DTH'));
        $this->db->group_end();
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }
        return $this->db->count_all_results();
    }

    //....................................................All Recharge Reports....................................................................

    function statusMenu_name() {
        $table = $this->db->dbprefix . "usertxn_table";
        $field = "status";
        $query = "SHOW COLUMNS FROM " . $table . " LIKE '$field'";
        $row = $this->db->query("SHOW COLUMNS FROM " . $table . " LIKE '$field'")->row()->Type;
        $regex = "/'(.*?)'/";
        preg_match_all($regex, $row, $enum_array);
        $enum_fields = $enum_array[1];
        return $enum_fields;
    }

    //....................................................Users Recharge Txn Table...........................................................

		
   
    public function count_all_recharge_allorder($userid, $role_id, $from, $to, $treeFetch) {

        $data = $this->db->from('usertxn_table atx');
        $this->db->join('users u', 'u.user_id = atx.user_id');

        if ($role_id != 1) {
            $this->db->group_start();
            $this->db->where('u.parent_id', $userid);
            $this->db->or_where('u.user_id', $userid);
            $this->db->group_end();
        }

        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('atx.req_dt >=', $from);
            $this->db->where('atx.req_dt <=', $to);
        } else {
            $this->db->like('atx.req_dt', date('Y-m-d'));
        }
        $this->db->group_start();
        $this->db->where(array('atx.servicetype' => 'PREPAID'));
        $this->db->or_where(array('atx.servicetype' => 'DTH'));
        $this->db->group_end();

        return $this->db->count_all_results();
    }
		 public function GetSubAdminDistChild($id) {
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->where(array('parent_id' => $id));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            $tree = $q;
            $new = [];
            foreach ($q as $v) {
                $dat = $this->GetSubAdminDistChild($v['user_id']);
                if ($dat) {
                    $tree = array_merge($tree, $dat);
                }
            }
            if ($tree) {
                return $tree;
            }
        } else {
            $tree = [];
        }
		}
	   public function GetSubAdminDistChildNewFunction($id) {
        $this->db->select('user_id,first_name,last_name,role_id');
        $this->db->from('users');
        $this->db->where(array('parent_id' => $id));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            $tree = $q;
            $new = [];
            foreach ($q as $v) {
                $dat = $this->GetSubAdminDistChildNewFunction($v['user_id']);
                if ($dat) {
                    $tree = array_merge($tree, $dat);
                }
            }
            if ($tree) {
                return $tree;
            }
        } else {
            $tree = [];
        }
    }
	
	 function Datatable_search_recharge_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {
		
        $column = array('u.mobile', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.servicename', 'atx.servicetype', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status');

        $column_order = array('u.mobile', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.servicename', 'atx.servicetype', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status');

        $order = array('atx.id' => 'desc');

        $this->db->from('usertxn_table atx');
        $this->db->join('users u', 'u.user_id = atx.user_id');
        $this->db->join('vendor_list v', 'v.vendor_id = atx.servedby');

        if ($role_id == 3) {

            $this->db->group_start();
            $this->db->where('u.parent_id', $userid);
            $this->db->or_where('u.user_id', $userid);
            $this->db->group_end();
        } else if ($role_id == 2) {
            $t = [];
            foreach ($treeFetch as $v) {
                $t[] = $v['user_id'];
            }

            $this->db->group_start();
            $this->db->where_in('u.parent_id', $t);
            $this->db->or_where_in('u.user_id', $t);
            $this->db->group_end();
        }

        $this->db->group_start();
        $this->db->where(array('atx.servicetype' => 'PREPAID'));
        $this->db->or_where(array('atx.servicetype' => 'DTH'));
        $this->db->group_end();
        $i = 0;
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell']) {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        }
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }

        $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
	
    function Datatable_fetch_recharge_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {
        $this->Datatable_search_recharge_allorder($userid, $role_id, $from, $to, $cell, $treeFetch);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }
	
	function Datatable_num_recharge_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {
        $this->Datatable_search_recharge_allorder($userid, $role_id, $from, $to, $cell, $treeFetch);
        return $this->db->count_all_results();
    }
	
	

    //****************************************MoneyTransfer**********************************************//
    function Datatable_search_Retailer_myorder($agent, $user_id) {
        // $column = array(null, 'fstpytxn_id','scode', 'opr_ref_no', 'customer_no', 'op10', 'op2', 'op3', 'op6', 'servicename', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');
        // $column_order = array(null, 'fstpytxn_id','scode', 'opr_ref_no', 'customer_no', 'op10', 'op2', 'op3', 'op6', 'servicename', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');
        
        $column = array('user_id','first_name','last_name', 'mobile' , 'email','business_name','business_address','business_state','business_city','business_pincode', 'rupee_balance');

        $column_order = $column;
        // $column_order = array('first_name','last_name',  'email','business_name','business_address','business_state','business_city','business_pincode');

        $order = array('id' => 'desc');
        $this->db->from('users');
        $this->db->where(array('role_id'=>4,'is_block'=>0,'is_active'=>1));

        if($agent != 'all'){
            $this->db->where(array('mobile' => $agent));
        }

        // if(($roleid == 4 || $roleid == 3) && $agent != ''){
        //     $this->db->where(array('mobile' => $agent));
        // } else if($roleid == 3){
        //     if($agent != '' && $showall == ''){
        //         $this->db->where(array('mobile' => $agent));
        //     }
        // }

        $this->db->where('user_id !=', $user_id);
        // $this->db->group_start();
        // $this->db->where(array('servicetype' => 'REMITTANCE'));
        // $this->db->group_end();
        //$i = 0;
        /*if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell'] && trim($carray['cell'])!="") {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column[$carray['cellIndex']], trim($carray['cell']));
                        }
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }*/

        $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }




	//****************************************MoneyTransfer**********************************************//
	 function Datatable_search_Money_myorder($userid, $from, $to, $cell) {
        // $column = array(null, 'fstpytxn_id','scode', 'opr_ref_no', 'customer_no', 'op10', 'op2', 'op3', 'op6', 'servicename', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');
        // $column_order = array(null, 'fstpytxn_id','scode', 'opr_ref_no', 'customer_no', 'op10', 'op2', 'op3', 'op6', 'servicename', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');
		
		  $column = array(null, 'fstpytxn_id','scode',  'customer_no', 'op2','op3','op10','op4','transamt','op5','op7','op6','opr_ref_no','status', 'req_dt');
    $column_order = array(null, 'fstpytxn_id','servicename',  'customer_no', 'op2','op3','op10','op4','transamt','op5','op7','op6','opr_ref_no','status', 'req_dt' );

        $order = array('id' => 'desc');
        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $userid));
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'REMITTANCE'));
        $this->db->group_end();
        $i = 0;
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell'] && trim($carray['cell'])!="") {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column[$carray['cellIndex']], trim($carray['cell']));
                        }
                        // if($i == $countindex-1)
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }

        $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function Datatable_fetch_Money_myorder($userid, $from, $to, $cell) {
        $this->Datatable_search_Money_myorder($userid, $from, $to, $cell);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }

    function Datatable_fetch_Retailer_myorder($agent, $user_id) {
        $this->Datatable_search_Retailer_myorder($agent, $user_id);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }
    
    function Datatable_num_Money_myorder($userid, $from, $to, $cell) {
        $this->Datatable_search_Money_myorder($userid, $from, $to, $cell);

        return $this->db->count_all_results();
    }

    function Datatable_num_Retailer_myorder($agent, $user_id) {
        $this->Datatable_search_Retailer_myorder($agent, $user_id);

        return $this->db->count_all_results();
    }

    public function count_all_Money_myorder($userid, $from, $to) {

        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $userid));
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'REMITTANCE'));
        $this->db->group_end();
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }
        return $this->db->count_all_results();
    }
	
    public function count_all_Retailer_myorder($agent, $user_id) {

        $this->db->from('users');
        $this->db->where(array('mobile' => $agent));
        $this->db->where('user_id !=', $user_id);
        // $this->db->group_start();
        // $this->db->where(array('servicetype' => 'REMITTANCE'));
        // $this->db->group_end();
        // if ($from != "" && $to != "" || $from != null) {

        //     $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

        //     $this->db->where('req_dt >=', $from);
        //     $this->db->where('req_dt <=', $to);
        // } else {
        //     $this->db->like('req_dt', date('Y-m-d'));
        // }
        return $this->db->count_all_results();
    }	
	    function findtxn_data($tid) {
        $sel = $this->db->get_where('usertxn_table', array('fstpytxn_id' => $tid));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }
	
	  function book_complaint($tid, $user_id, $role_id) {
        if ($role_id == 1) {

            return $this->db->update('usertxn_table', array('review_stat' => 1, 'review_on' => date('Y-m-d H:i:s'), 'status' => 'REVIEW'), array('fstpytxn_id' => $tid));

        } else {

            return $this->db->update('usertxn_table', array('review_stat' => 1, 'review_on' => date('Y-m-d H:i:s'), 'status' => 'REVIEW'), array('fstpytxn_id' => $tid, 'user_id' => $user_id));
        }
    }
	
	
	
	
	
	function Datatable_search_money_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {

        if ($role_id == 2 || $role_id == 3) {
          /*   $column = array('u.mobile', 'u.full_name','u.last_name', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.scode', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status');

            $column_order = array('u.mobile', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.op6', 'atx.servicename', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status'); */
			
			 $column = array('u.mobile', 'u.full_name','u.last_name', 'atx.fstpytxn_id','atx.scode',  'atx.customer_no', 'atx.op2','atx.op3','atx.op10','atx.op4','atx.transamt','atx.op5','atx.op7','atx.op6','atx.opr_ref_no','atx.status', 'atx.req_dt');
    $column_order = array('u.mobile', 'atx.fstpytxn_id','atx.servicename','atx.customer_no', 'atx.op2','atx.op3','atx.op10','atx.op4','atx.transamt','atx.op5','atx.op7','atx.op6','atx.opr_ref_no','atx.status', 'atx.req_dt' );
		  
		
        } else {
           /*  $column = array('u.mobile', 'u.full_name','u.last_name', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.scode', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status');

            $column_order = array('u.mobile', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.servicename', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status'); */
			
			  $column = array('u.mobile', 'u.full_name','u.last_name', 'atx.fstpytxn_id','atx.scode',  'atx.customer_no', 'atx.op2','atx.op3','atx.op10','atx.op4','atx.transamt','atx.op5','atx.op7','atx.op6','atx.opr_ref_no','atx.status', 'atx.req_dt');
    $column_order = array('u.mobile', 'atx.fstpytxn_id','atx.servicename','atx.customer_no', 'atx.op2','atx.op3','atx.op10','atx.op4','atx.transamt','atx.op5','atx.op7','atx.op6','atx.opr_ref_no','atx.status', 'atx.req_dt' );
        }
        $order = array('atx.id' => 'desc');

        $this->db->from('usertxn_table atx');
        $this->db->join('users u', 'u.user_id = atx.user_id');
        $this->db->join('vendor_list v', 'v.vendor_id = atx.servedby');
		
		
		

         if ($role_id == 3) {

            $this->db->group_start();
            $this->db->where('u.parent_id', $userid);
            $this->db->or_where('u.user_id', $userid);
            $this->db->group_end();
        } else if ($role_id == 2) {
            $t = [];
            foreach ($treeFetch as $v) {
                $t[] = $v['user_id'];
            }

            $this->db->group_start();
            $this->db->where_in('u.parent_id', $t);
            $this->db->or_where_in('u.user_id', $t);
            $this->db->group_end();
        }

        $this->db->group_start();
        $this->db->where(array('atx.servicetype' => 'REMITTANCE'));
        $this->db->group_end();
        $i = 0;
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell']) {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        }
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }

        $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function Datatable_fetch_money_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {
        $this->Datatable_search_money_allorder($userid, $role_id, $from, $to, $cell, $treeFetch);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }

    function Datatable_num_money_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {
        $this->Datatable_search_money_allorder($userid, $role_id, $from, $to, $cell, $treeFetch);

        return $this->db->count_all_results();
    }
	
	
	   public function count_all_money_allorder($userid, $role_id, $from, $to, $treeFetch) {

        $data = $this->db->from('usertxn_table atx');
        $this->db->join('users u', 'u.user_id = atx.user_id');

        if ($role_id != 1) {
            $this->db->group_start();
            $this->db->where('u.parent_id', $userid);
            $this->db->or_where('u.user_id', $userid);
            $this->db->group_end();
        }

        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('atx.req_dt >=', $from);
            $this->db->where('atx.req_dt <=', $to);
        } else {
            $this->db->like('atx.req_dt', date('Y-m-d'));
        }
        $this->db->group_start();
        $this->db->where(array('atx.servicetype' => 'REMITTANCE'));
        $this->db->group_end();

        return $this->db->count_all_results();
    }
	
	
	
	  public function FetchSrvcsForRemitt($srvctype) {
        $this->db->select('service_name,type,code');
        $this->db->from('services');
        $this->db->where(array('type' => $srvctype, 'is_active' => 1));
        $selg = $this->db->get();
        $qg = $selg->result_array();
        if ($qg) {
            return $qg;
        }
    } 
	  
	
	public function serviceFilter($userid,$scode){
		$this->db->select('*');
	    $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $userid));
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'REMITTANCE','scode'=>$scode));
        $this->db->group_end();	
		$selg = $this->db->get();
        $qg = $selg->result_array();
        if ($qg) {
            return $qg;
        }
	}
	
	
	
	
	//....................................................Users Billpayment Txn Table...........................................................

    function Datatable_search_BillPaymeny_myorder($userid, $from, $to, $cell) {
      /*   $column = array(null, 'tvgtxn_id', 'opr_ref_no', 'customer_no', 'servicename', 'servicetype', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');

        $column_order = array(null, 'tvgtxn_id', 'opr_ref_no', 'customer_no', 'servicename', 'servicetype', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status'); */
		
		$column = array(null, 'fstpytxn_id', 'opr_ref_no', 'customer_no', 'servicename', 'servicetype', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');

        $column_order = array(null, 'fstpytxn_id', 'opr_ref_no', 'customer_no', 'servicename', 'servicetype', 'transamt', 'chargeamt', 'closingbal', 'req_dt', 'status');

        $order = array('id' => 'desc');
        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $userid));
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'POSTPAID'));
        $this->db->or_where(array('servicetype' => 'LANDLINE'));
       // $this->db->or_where(array('servicetype' => 'BROADBAND'));
        $this->db->or_where(array('servicetype' => 'GAS'));
        $this->db->or_where(array('servicetype' => 'ELECTRICITY'));
        $this->db->or_where(array('servicetype' => 'WATER'));
        $this->db->or_where(array('servicetype' => 'INSURANCE'));
        $this->db->group_end();
        $i = 0;
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell']) {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        }
                        // if($i == $countindex-1)
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }

        $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function Datatable_fetch_BillPaymeny_myorder($userid, $from, $to, $cell) {
        $this->Datatable_search_BillPaymeny_myorder($userid, $from, $to, $cell);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }

    function Datatable_num_BillPaymeny_myorder($userid, $from, $to, $cell) {
        $this->Datatable_search_BillPaymeny_myorder($userid, $from, $to, $cell);

        return $this->db->count_all_results();
    }

    public function count_all_BillPaymeny_myorder($userid, $from, $to) {

        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $userid));
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'POSTPAID'));
        $this->db->or_where(array('servicetype' => 'LANDLINE'));
       // $this->db->or_where(array('servicetype' => 'BROADBAND'));
        $this->db->or_where(array('servicetype' => 'GAS'));
        $this->db->or_where(array('servicetype' => 'ELECTRICITY'));
        $this->db->or_where(array('servicetype' => 'WATER'));
        $this->db->or_where(array('servicetype' => 'INSURANCE'));
        $this->db->group_end();
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }
        return $this->db->count_all_results();
    }

    //....................................................Users Recharge Txn Table...........................................................

    function Datatable_search_BillPaymeny_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {

         $column = array('u.mobile', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.servicename', 'atx.servicetype', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status');

        $column_order = array('u.mobile', 'atx.fstpytxn_id', 'atx.sp_id', 'v.vendor_name', 'atx.opr_ref_no', 'atx.customer_no', 'atx.servicename', 'atx.servicetype', 'atx.transamt', 'atx.chargeamt', 'atx.closingbal', 'atx.req_dt', 'atx.status');


        $order = array('atx.id' => 'desc');

        $this->db->from('usertxn_table atx');
        $this->db->join('users u', 'u.user_id = atx.user_id');
       
		  $this->db->join('vendor_list v', 'v.vendor_id = atx.servedby');

        if ($role_id == 3) {

            $this->db->group_start();
            $this->db->where('u.parent_id', $userid);
            $this->db->or_where('u.user_id', $userid);
            $this->db->group_end();
        } else if ($role_id == 2) {
            $t = [];
            foreach ($treeFetch as $v) {
                $t[] = $v['user_id'];
            }

            $this->db->group_start();
            $this->db->where_in('u.parent_id', $t);
            $this->db->or_where_in('u.user_id', $t);
            $this->db->group_end();
        }

        $this->db->group_start();
        $this->db->where(array('servicetype' => 'POSTPAID'));
        $this->db->or_where(array('servicetype' => 'LANDLINE'));
       // $this->db->or_where(array('servicetype' => 'BROADBAND'));
        $this->db->or_where(array('servicetype' => 'GAS'));
        $this->db->or_where(array('servicetype' => 'ELECTRICITY'));
        $this->db->or_where(array('servicetype' => 'WATER'));
        $this->db->or_where(array('servicetype' => 'INSURANCE'));
        $this->db->group_end();
        $i = 0;
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell']) {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        }
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }

        $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function Datatable_fetch_BillPaymeny_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {
        $this->Datatable_search_BillPaymeny_allorder($userid, $role_id, $from, $to, $cell, $treeFetch);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }

    function Datatable_num_BillPaymeny_allorder($userid, $role_id, $from, $to, $cell, $treeFetch) {
        $this->Datatable_search_BillPaymeny_allorder($userid, $role_id, $from, $to, $cell, $treeFetch);

        return $this->db->count_all_results();
    }

    public function count_all_BillPaymeny_allorder($userid, $role_id, $from, $to, $treeFetch) {

        $data = $this->db->from('usertxn_table atx');
        $this->db->join('users u', 'u.user_id = atx.user_id');

        if ($role_id != 1) {
            $this->db->group_start();
            $this->db->where('u.parent_id', $userid);
            $this->db->or_where('u.user_id', $userid);
            $this->db->group_end();
        }

        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('atx.req_dt >=', $from);
            $this->db->where('atx.req_dt <=', $to);
        } else {
            $this->db->like('atx.req_dt', date('Y-m-d'));
        }
        $this->db->group_start();
        $this->db->where(array('servicetype' => 'POSTPAID'));
        $this->db->or_where(array('servicetype' => 'LANDLINE'));
      //  $this->db->or_where(array('servicetype' => 'BROADBAND'));
        $this->db->or_where(array('servicetype' => 'GAS'));
        $this->db->or_where(array('servicetype' => 'ELECTRICITY'));
        $this->db->or_where(array('servicetype' => 'WATER'));
        $this->db->or_where(array('servicetype' => 'INSURANCE'));
        $this->db->group_end();

        return $this->db->count_all_results();
    }
	
	
	/*******************************Report************************************/
	   
	 function Datatable_search_mergereport($accnt_id, $role_id, $from, $to, $cell) {
	
       $order = array('id' => 'desc');
		 $this->db->select('fstpytxn_id AS txnid,transamt AS amt,openingbal AS opbal,closingbal AS clbal,req_dt AS date,servicename AS sname,CONCAT(servicetype, " ( ", customer_no," )") AS stype,status AS status');
        $this->db->from('usertxn_table');
        $this->db->where('user_id', $accnt_id);
//        $this->db->like('req_dt', $from);
       /*   if (isset($_POST['from']) && isset($_POST['to'])) {
           // $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($_POST['to']), "Y-m-d")) + 86399));
            $this->db->group_start();
            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $this->db->group_end();
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }  */
        $sel1 = $this->db->get_compiled_select();
        //$f = $sel->result_array();

        $this->db->select('credit_txnid	 AS txnid,amount AS amt,opening_balance AS opbal,closing_balance AS clbal,created_on AS date,txn_type AS sname,remarks AS stype,status AS status');
        $this->db->from('credit_history');
        $this->db->where(array('user_id' => $accnt_id, 'is_received' => 1));
      /*   if (isset($_POST['from']) && isset($_POST['to'])) {
           // $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($_POST['to']), "Y-m-d")) + 86399));
            $this->db->group_start();
            $this->db->where('created_on >=', $from);
            $this->db->where('created_on <=', $to);
            $this->db->group_end();
        } else {
            $this->db->like('created_on', date('Y-m-d'));
        } */
//        $this->db->like('credit_dt', $from);
        $sel2 = $this->db->get_compiled_select();
        //$f2 = $sel2->result_array();
 $sel = $this->db->query("({$sel1}) UNION ALL ({$sel2}) order by date ASC")->result_array();
        return $sel;
        /* if ($f) {
            if ($f2) {
                return array_merge($f, $f2);
            } else {
                return $f;
            }
        } else {
            if ($f2) {
                return $f2;
            }
        } */
        
		$i = 0;
        if ($from != "" && $to != "" || $from != null) {

            $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $i = 0;
            $countindex = count($cell);
            if ($countindex > 0) {
                foreach ($cell as $carray) {
                    if ($carray['cell']) {
                        $this->db->group_start();
                        if ($i == 0) {
                            $this->db->like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        } else {
                            $this->db->or_like($column_order[$carray['cellIndex']], trim($carray['cell']));
                        }
                        $this->db->group_end();
                    }
                    $i++;
                }
            }
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        } 
 
         $i = 0;
        foreach ($column as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($search, $_POST['search']['value']);
                } else {
                    $this->db->or_like($search, $_POST['search']['value']);
                }

                if (count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        } 
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            // $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }  
    }

    function Datatable_fetch_mergereports($userid, $role_id, $from, $to, $cell) {
        $this->Datatable_search_mergereport($userid, $role_id, $from, $to, $cell);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $data = $this->db->get();
        $d = $data->result_array();
        if ($d) {
            return $d;
        }
    }

    function Datatable_num_mergereport($userid, $role_id, $from, $to, $cell) {
        $this->Datatable_search_mergereport($userid, $role_id, $from, $to, $cell);

        return $this->db->count_all_results();
    }

    public function count_all_mergereport($accnt_id, $role_id, $from, $to) {
		 $this->db->from('usertxn_table');
        $this->db->where('user_id', $accnt_id);
//        $this->db->like('req_dt', $from);
          if (isset($_POST['from']) && isset($_POST['to'])) {
           // $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($_POST['to']), "Y-m-d")) + 86399));
            $this->db->group_start();
            $this->db->where('req_dt >=', $from);
            $this->db->where('req_dt <=', $to);
            $this->db->group_end();
        } else {
            $this->db->like('req_dt', date('Y-m-d'));
        }  
     
        $f = $this->db->count_all_results();

        $this->db->from('credit_history');
        $this->db->where(array('user_id' => $accnt_id, 'is_received' => 1));
        if (isset($_POST['from']) && isset($_POST['to'])) {
           // $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($_POST['to']), "Y-m-d")) + 86399));
            $this->db->group_start();
            $this->db->where('created_on >=', $from);
            $this->db->where('created_on <=', $to);
            $this->db->group_end();
        } else {
            $this->db->like('created_on', date('Y-m-d'));
        } 
//        $this->db->like('credit_dt', $from);
      
        $f2 = $this->db->count_all_results();

        if ($f) {
            if ($f2) {
                //return array_count_values($f, $f2);
				return $f / $f2;
            } else {
                return $f;
            }
        } else {
            if ($f2) {
                return $f2;
            }
        }
    }
	
	 public function getTxRcdCommData($userid){
	   $this->db->select('*');
	   $sel = $this->db->get_where('tax_record', array('user_id'=>$userid));
        $s = $sel->row_array();
        if ($s) {
            return $s;
        }
		} 
	
   
   function fetch_mergereports($accnt_id, $role_id, $from, $to) {
	    
	 // $this->db->order_by('id','desc');
	     // $this->db->order_by('id','DESC');
        $from = date_format(date_create($from), 'Y-m-d');
        $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));
		
        $this->db->select('fstpytxn_id AS txnid,chargeamt AS amt,openingbal AS opbal,closingbal AS clbal,req_dt AS date,servicename AS sname,CONCAT(servicetype, " ( ", customer_no," )") AS stype,status AS status');
        $this->db->from('usertxn_table');
        $this->db->where(array('user_id' => $accnt_id));
        $this->db->where('req_dt >=', $from);
        $this->db->where('req_dt <=', $to);
        $sel = $this->db->get();
        $f = $sel->result_array();
		
        $this->db->select('credit_txnid AS txnid,amount AS amt,opening_balance AS opbal,closing_balance AS clbal,updated_on AS date,txn_type AS sname,remarks AS stype,status AS status');
        $this->db->from('credit_history');
        $this->db->where(array('user_id' => $accnt_id));
        $this->db->where('updated_on >=', $from);
        $this->db->where('updated_on <=', $to);
        $sel2 = $this->db->get();
        $f2 = $sel2->result_array();

        if ($f) {
            if ($f2) {
                return array_merge($f, $f2);
            } else {
                return $f;
            }
        } else {
            if ($f2) {
                return $f2;
            }
        }
    }
	
		function fetch_parent_account_statement($accnt_id, $role_id, $from, $to) {
			//$this->db->order_by('credithistory_tableid','DESC');
        $from = date_format(date_create($from), 'Y-m-d');
        $to = date('Y-m-d H:i:s', (strtotime(date_format(date_create($to), "Y-m-d")) + 86399));

        $this->db->select('credit_txnid AS txnid,amount AS amt,opening_balance AS opbal,closing_balance AS clbal,updated_on AS date,txn_type AS sname,remarks AS stype,status AS status,credithistory_tableid as id');
        $this->db->from('credit_history');
        $this->db->where(array('user_id' => $accnt_id, 'txn_type !=' => 'TDS'));
        $this->db->where('updated_on >=', $from);
        $this->db->where('updated_on <=', $to);
        $query2 = $this->db->get();
        $q = $query2->result_array();
        if ($q) {
            return $q;
        }
    }

	 	public function getsumTxRcdCommData($userid){
	   $this->db->select('SUM(cast(trans_amt as decimal(12,4))) as trnsamnt,SUM(cast(comm_amnt as decimal(12,4))) as comm_amnt,SUM(cast(tds_amnt as decimal(12,4))) as tds_amnt,charged_amt');
	   $sel = $this->db->get_where('tax_record',  array('user_id'=>$userid));
        $s = $sel->result_array();
        if ($s) {
            return $s;
        }
		} 
	function getcommtdsonrefund($txnid){
        // $this->db->select('tr.*,ut.chargeamt,ut.transamt');
        $this->db->from('tax_record ut');
        //$this->db->join('tax_record tr','ut.cybert_id = tr.cbrt_id','left');
        $this->db->where(array('cbrt_id'=>$txnid));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if($q){
            return $q;
        }
    }
	
	
	
	


}

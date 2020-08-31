<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	 public function __construct() {
        parent::__construct();
        $this->load->database();
    }


      function login($mobile, $pass) {
        $mobile = trim($mobile);
        $pass = trim($pass);
        $pwd = md5($pass);
        $ip_address = ip_address();
        $this->db->where("password like binary", $pwd);
        $sel = $this->db->get_where('users', array('mobile' => $mobile, 'is_active' => 1));
        $f = $sel->row_array();

        if ($f) {

        $this->db->insert('activity_log', array('user_id' => $f['user_id'], 'activity' => 'Login Attempted', 'activity_dt' => date('Y-m-d H:i:s'), 'ip' => $ip_address));   

            return $f;
        }
    }

     function validate_loginotp($mobile, $otp) {
        $ip_address = ip_address();
        $this->db->select('user_id,role_id,is_block');
        $sel = $this->db->get_where('users', array('mobile' => $mobile));
        $f = $sel->row_array();
        if ($f) {
            $sel2 = $this->db->get_where('user_otps', array('userid' => $f['user_id'], 'event_type' => 'Login', 'otp' => $otp, 'is_active' => 1));
            $f1 = $sel2->row_array();
            if ($f1) {
                $this->db->insert('activity_log', array('user_id' => $f['user_id'], 'activity' => 'Logged In By Otp', 'activity_dt' => date('Y-m-d H:i:s'), 'ip' => $ip_address));

                return array_merge($f1, $f);
            }
        }
    }

     function delete_auth_otp($userid, $otp) {
        return $this->db->update('user_otps', array('is_active' => 0), array('userid' => $userid, 'event_type' => 'Login', 'otp' => $otp, 'is_active' => 1));
    }

      function user_acntid($e) {
        $e = trim($e);
        $this->db->select('us.*,r.role_name,nus.first_name as parentName,nus.last_name as parentLName,nus.mobile as parentMobile');
        $this->db->from('users us');
        $this->db->join('roles r', "r.role_id=us.role_id");
        $this->db->join('users nus', 'us.parent_id = nus.user_id', 'left');
        $this->db->where(array('us.user_id' => $e, 'us.is_active' => 1));
        $sel = $this->db->get();
        $s = $sel->row_array();
        if ($s) {
            
            return $s;
        }
    }

    

     

    //     function user_acntid($e) {
    //     $e = trim($e);
    //     $this->db->select('us.*,r.role_name,nus.first_name as parentName,nus.last_name as parentLName,nus.mobile as parentMobile,pr.role_name as parentRoleName');
    //     $this->db->from('users us');
    //     $this->db->join('roles r', "r.role_id=us.role_id");
    //     $this->db->join('users nus', 'us.parent_id = nus.user_id', 'left');
    //     $this->db->join('roles pr', "pr.role_id=nus.role_id");
    //     $this->db->where(array('us.user_id' => $e, 'us.is_active' => 1));
    //     $sel = $this->db->get();
    //     $s = $sel->row_array();
    //     if ($s) {
            
    //         return $s;
    //     }
    // }

        function get_role_perms($r) {

        if ($r) {
            $this->db->select('rhp.perm_id');
            $this->db->from('role_has_permission rhp');
            $this->db->join('roles r', "r.role_id=rhp.role_id");
            $this->db->join('permission p', 'rhp.perm_id=p.id');
            $this->db->where(array('rhp.role_id' => $r, 'p.perm_type' => 'NAVBAR', 'r.is_active' => 1, 'p.is_active' => 1));
            $sel = $this->db->get();
            return $f = $sel->result_array();
        }
    }

    

      function user_info($e) {
        $e = trim($e);
        $this->db->select('*');
        $sel = $this->db->get_where('users', array('mobile' => $e, 'is_active' => 1));
        $s = $sel->row_array();
        if ($s) {
            return $s;
        }
    }

    function user_info_byno($mob) {
        $mob = trim($mob);
        $this->db->select('*');
        $sel = $this->db->get_where('users', array('mobile' => $mob, 'is_active' => 1));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }


     function check_resetotp($resetotp, $mobile) {

        $this->db->select('user_id');
        $sel = $this->db->get_where('users', array('mobile' => $mobile));
        $f = $sel->row_array();
        if ($f) {
            $sel2 = $this->db->get_where('user_otps', array('userid' => $f['user_id'], 'event_type' => 'Reset Password', 'otp' => $resetotp, 'is_active' => 1));
            $f = $sel2->row_array();
            if ($f) {

                return $f;
            }
        }
    }
     function change_pass_byreset($mobile, $newpass) {

        $mobile = trim($mobile);
        $newpass = trim($newpass);
        $newpass = md5($newpass);
        return $this->db->update('users', array('password' => $newpass), array('mobile' => $mobile, 'is_active' => 1));
    }

     function delete_reset_otp($userid, $otp) {

        return $this->db->update('user_otps', array('is_active' => 0), array('userid' => $userid, 'event_type' => 'Reset Password', 'otp' => $otp, 'is_active' => 1));
    }

    /******** nav bar **********/
    function navbar_data($r) {
        if(is_array($r)){
        $this->db->from('navbar');
        $this->db->order_by('tab_order','ASC');
        $this->db->where('is_active',1);
        $this->db->where_in('perm_id',$r);
        $sel = $this->db->get();
        $f = $sel->result_array();
        if ($f) {
            return $f;
        }
        }
    }



    function get_parent_navbar($sid){

        $sel=$this->db->get_where('navbar',array('id'=>$sid,'is_active'=>1));
        $s=$sel->row_array();
        if($s){
            return $s;
        }
        
    }
    function get_child_navbar($navchld){

        $this->db->select('tab_name');
        $this->db->from('navbar nvbr');
        $this->db->join('permission p', 'nvbr.perm_id=p.id');
        $this->db->where(array('nvbr.parent_id' => $navchld, 'p.perm_type' => 'NAVBAR', 'nvbr.is_active' => 1, 'p.is_active' => 1));
        $sel = $this->db->get();
        $s=$sel->result_array();
        if($s){
            return $s;
        }
    }


       function get_role_perms_by_type($r, $prmtyp) {

        if ($r) {
            $this->db->select('rhp.perm_id');
            $this->db->from('role_has_permission rhp');
            $this->db->join('roles r', "r.role_id=rhp.role_id");
            $this->db->join('permission p', 'rhp.perm_id=p.id');
            $this->db->where(array('rhp.role_id' => $r, 'p.perm_type' => $prmtyp, 'r.is_active' => 1, 'p.is_active' => 1));
            $sel = $this->db->get();
            return $f = $sel->result_array();   
        }
    }



    function user_info_by_pswd($usercnt, $pswd) {
        $pswd = trim($pswd);
        $pwd = md5($pswd);
        $this->db->where("password like binary", $pwd);
        $this->db->select('*');
        $sel = $this->db->get_where('users', array('user_id' => $usercnt, 'is_active' => 1));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }

     function change_pass_by_user($user, $newpass) {
        $time = date('Y-m-d H:i:s');
        $f = array();
        $newpass = trim($newpass);
        $newpass = md5($newpass);
        $this->db->update('users', array('password' => $newpass, 'updated_on' => $time, 'updated_by' => $user), array('user_id' => $user, 'is_active' => 1));
        if ($this->db->affected_rows() > 0) {
            $f['updated'] = 'Yes';
            return $f;
        } elseif ($this->db->affected_rows() == 0) {
            $f['updated'] = 'No';
            return $f;
        }
    }
	 function user_info_by_mpin($usercnt, $crnt_mpin) {
        $crnt_mpin = trim($crnt_mpin);
        $crnt_mpin = md5($crnt_mpin);
        $this->db->where("mpin like binary", $crnt_mpin);
        $this->db->select('*');
        $sel = $this->db->get_where('users', array('user_id' => $usercnt, 'is_active' => 1));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }
	function change_mpin_by_user($user, $newmpin) {
        $time = date('Y-m-d H:i:s');
        $f = array();
        $newmpin = trim($newmpin);
        $newmpin = md5($newmpin);
        $this->db->update('users', array('mpin' => $newmpin, 'updated_on' => $time, 'updated_by' => $user), array('user_id' => $user, 'is_active' => 1));
        if ($this->db->affected_rows() > 0) {
            $f['updated'] = 'Yes';
            return $f;
        } elseif ($this->db->affected_rows() == 0) {
            $f['updated'] = 'No';
            return $f;
        }
    }


    public function get_servs($type) {
        $s = $this->db->get_where('services', array('type' => $type, 'is_active' => 1));
        return $num = $s->result_array();
    }


  

    function get_all_roles() {
        $this->db->select('role_name,role_id,allowed_for');
        $sel = $this->db->get_where('roles', array('is_active' => 1, 'allowed_for !=' => 0));
        $s = $sel->result_array();
        if ($s) {
            return $s;
        }
    }

      public function FetchPlanForCreateUser($role) {
        $this->db->select('plan_id,plan_name,plan_code');
        $this->db->from('plan_config');
        $this->db->where(array('plan_for_role' => $role, 'is_active' => 1));
        $selg = $this->db->get();
        $qg = $selg->result_array();
        if ($qg) {
            return $qg;
        }
    } 

     function get_phone($phone) {

        $query = $this->db->get_where('users', array('mobile' => $phone));
        return $num = $query->num_rows();
    }

       function get_email($email) {

        $query = $this->db->get_where('users', array('email' => $email));
        return $num = $query->num_rows();
    }

     public function checkRoleValid($Role) {
        $this->db->from('roles');
        $this->db->where(array('role_id' => $Role));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

    public function checkPlanExist($plan) {
        $this->db->from('plan_config');
        $this->db->where(array('plan_id' => $plan));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }
      public function PlanUpdate($planId, $userId) {
        return $this->db->update('users', array('plan_id' => $planId), array('user_id' => $userId));
    }

    public function checkPlanExistForRole($plan,$role_id){
         $this->db->from('plan_config');
        $this->db->where(array('plan_id' => $plan,'plan_for_role'=>$role_id,'is_active'=>1));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }

    }

    //    public function FetchDefaultPlan($role) {
    //     $this->db->select('plan_id');
    //     $this->db->from('plan_config');
    //     $this->db->where(array('plan_for_role' => $role, 'is_default' => 1));
    //     $sel = $this->db->get();
    //     $q = $sel->row_array();
    //     if ($q) { 
    //         return $q;
    //     }
    // }

    public function checkPanAlready($pn) {
        $this->db->from('users');
        $this->db->where(array('pan' => $pn));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

      public function checkAadharAlready($aadhar){
        $this->db->from('users');
        $this->db->where(array('aadhar' => $aadhar));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }  
    }


    function insert_users_data($insert, $user_files) {
        $s = $this->db->insert('users', $insert);
        if ($s) {
            if (count($user_files) > 0) {
                $this->db->insert_batch('user_docs', $user_files);
            }
            return $s;
        }
    }

     function check_unq_accid($acc_id) {
        $query = $this->db->get_where('users', array('user_id' => $acc_id));
        $sel = $query->row_array();
        if (!$sel) {
            return TRUE;
        }
    }
    
      public function getpininfo($pin) {
        $this->db->from('pincodes');
        $this->db->where(array('pincode' => $pin));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }
   


     function get_my_childs($acid, $roleid) {
        $this->db->order_by('us.id', 'DESC');
        $this->db->select("us.*,usr.first_name as fsnam,usr.last_name as lastnam,rl.role_name");
        $this->db->from('users us');
        $this->db->join('roles rl', "us.role_id=rl.role_id");
        $this->db->join('users usr', 'us.created_by=usr.user_id', 'left');
        if ($roleid != 1) {
            $this->db->where(array('us.parent_id' => $acid));
        }
        $s = $this->db->get();
        $sel = $s->result_array();
        if ($sel) {
            return $sel;
        }
    }

    function getnoteData() {
        $result = array();
        $this->db->order_by('id', 'DESC');
        $get = $this->db->get('portal_notifs');
        $access = $get->result_array();
        if ($access) {
            $result = $access;
        }
        return $result;
    }

    function getnoteEditData($cuid) {
        $get = $this->db->get_where('portal_notifs', array('id' => $cuid));
        $access = $get->row_array();
        if ($access) {
            return $access;
        }
    }

    function InsertNotification($heading, $content, $validup, $notifor, $color='', $hfontsize='', $cfontsize='') {
        $validup = date('Y-m-d H:i:s', (strtotime(date_format(date_create($validup), "Y-m-d")) + 86399));
        return $this->db->insert('portal_notifs', array('contain_fontsize' => $cfontsize, 'heading_fontsize' => $hfontsize, 'noti_color' => $color, 'heading' => $heading, 'content' => $content, 'notif_for' => $notifor, 'valid_upto' => $validup, 'event_type' => 'NOTIFICATION', 'created_on' => date('Y-m-d H:i:s')));
    }

    function UpdateNotification($heading, $content, $validup, $notifor, $editrow) {
        $validup = date('Y-m-d H:i:s', (strtotime(date_format(date_create($validup), "Y-m-d")) + 86399));
        return $this->db->update('portal_notifs', array('heading' => $heading, 'content' => $content, 'notif_for' => $notifor, 'valid_upto' => $validup), array('id' => $editrow));
    }

    public function all_vendors() {
        $s = $this->db->get_where('vendor_list');
        return $num = $s->result_array();
    }

    public function check_vendor($id) {
        $s = $this->db->get_where('vendor_list', array('vendor_id' => $id));
        return $num = $s->row_array();
    }

     public function pndg_actvtn_usr() {
        $this->db->order_by('us.id', 'DESC');
        $this->db->select("us.first_name,us.last_name,us.mobile,us.email,us.user_id,us.business_name,us.business_address,us.business_state,us.business_city,us.registered_address,us.created_on,usr.first_name as fsnam,usr.last_name as lsnam,rl.role_name,rl.role_id");
        $this->db->from('users us');
        $this->db->join('roles rl', "us.role_id=rl.role_id");
        $this->db->join('users usr', 'us.created_by=usr.user_id', 'left');
        $this->db->where(array('us.is_block' => 1));
        $s = $this->db->get();
        $sel = $s->result_array();
        if ($sel) {
            return $sel;
        }
    }


     public function check_doctment($acntid) {
        $s = $this->db->get_where('user_docs', array('user_id' => $acntid, 'doc_for' => "KYC"));
        return $num = $s->result_array();
    }

    public function check_id_proof($acntid, $doc) {
        $s = $this->db->get_where('user_docs', array('user_id' => $acntid, 'doc_for' => "KYC", 'doc_name' => $doc));
        return $num = $s->row_array();
    }

     public function chck_pndg_doc($docid, $acntid) {

        $query = $this->db->get_where('user_docs', array('id' => $docid, 'status' => 'PENDING', 'user_id' => $acntid));

        $num = $query->row_array();
        return $num;
    }

     public function updt_apprv_docmnt($id, $update) {

        return $this->db->update('user_docs', $update, array('id' => $id));
    }

      public function is_inactive_usr($usr_acntid) {
        $query = $this->db->get_where('users', array('user_id' => $usr_acntid, 'is_block' => 1));

        $num = $query->row_array();
        return $num;
    }

     public function updt_user_details($id, $update) {
        return $this->db->update('users', $update, array('user_id' => $id));
    }

      public function check_cdths_txnid($chId) {
        $this->db->from('credit_history');
        $this->db->where(array('credit_txnid' => $chId));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

      public function check_admin_tnfr_cd($tnsfr_cd) {
        if ($tnsfr_cd) {
            $s = $this->db->get_where('credit_history', array('txn_code' => $tnsfr_cd));
            $f = $s->row_array();
            if ($f) {
                return $f;
            }
        }
    }

       public function check_dstrbtr_tnfr_cd($tnsfr_cd) {
        if ($tnsfr_cd) {
            $s = $this->db->get_where('credit_history', array('txn_code' => $tnsfr_cd));
            $f = $s->row_array();
            if ($f) {
                return $f;
            }
        }
    }
	public function update_cdt_blnc_by_admin($usr_acnt_id,$crdt_hstry){
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        $cdt = $this->db->insert('credit_history', $crdt_hstry);
        if ($cdt) {
            $this->db->set('rupee_balance', 'rupee_balance+' . $crdt_hstry['amount'], false);
			if($crdt_hstry['is_due']==1){
			   $this->db->set('outstanding_balance', 'outstanding_balance+' . $crdt_hstry['amount'], false);
			 
			 }
			
            $this->db->where(array('user_id' => $usr_acnt_id));

            $s = $this->db->update('users');
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        }
    }

        public function update_cdt_blnc_by_dstbtr($lngacnt_id, $crdt_hstry, $ef_usr_acnt_id, $crdt_hstry2){
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        //rupee_balance need to be updated
        $this->db->set('rupee_balance', 'rupee_balance-' . $crdt_hstry['amount'], false);
        $this->db->where(array('user_id' => $lngacnt_id));
        $s = $this->db->update('users');
        //effect row $usr_acnt_id
        if ($this->db->affected_rows() > 0) {
            //distbtr crdt_hstry insert query (if distbtr login=>$lngacnt_id)//
            $usrlgn = $this->db->insert('credit_history', $crdt_hstry);
            //retailer effect row=>$ef_usr_acnt_id//
            $eftusr = $this->db->insert('credit_history', $crdt_hstry2);
            //updt retailer blnc//
            $this->db->set('rupee_balance', 'rupee_balance+' . $crdt_hstry2['amount'], false);
				if($crdt_hstry2['is_due']==1){
					
			 $this->db->set('outstanding_balance', 'outstanding_balance+' . $crdt_hstry2['amount'], false);
		
			 
				}
				
            $this->db->where(array('user_id' => $ef_usr_acnt_id));
            $s = $this->db->update('users');
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        } 
    }


    public function check_mobile_alrdy_updt($mobile,$uid){
        $query=$this->db->get_where('users',array('mobile' =>$mobile, 'user_id !=' => $uid));
        $num = $query->row_array();
        return $num;
    }

    public function checkEmailAlreadyUpdate($email,$uid){
     $query=$this->db->get_where('users',array('email' =>$email, 'user_id !=' => $uid));
        $num = $query->row_array();
        return $num;   
    }

    public function checkPanAlreadyUpdate($pan,$uid){
         $query=$this->db->get_where('users',array('pan' =>$pan, 'user_id !=' => $uid));
        $num = $query->row_array();
        return $num; 
    }

    public function checkAadharAlreadyUpdate($aadhar,$uid){
      $query=$this->db->get_where('users',array('aadhar' =>$aadhar, 'user_id !=' => $uid));
        $num = $query->row_array();
        return $num;    
    }    

    public function update_users_data($acntid, $data) {
        return $this->db->update('users', $data, array('user_id' => $acntid));
    }

     public function is_allowds_usr($usr_acntid, $lgnrl, $lgnacnt_id) {
        if ($lgnrl == 1) {
            $query = $this->db->get_where('users', array('user_id' => $usr_acntid));
        } else {
            $query = $this->db->get_where('users', array('user_id' => $usr_acntid, 'parent_id' => $lgnacnt_id));
        }
        $num = $query->row_array();
        return $num;
    }

       public function insert_activated_data($id, $update) {
        return $this->db->update('users', $update, array('user_id' => $id));
    }


    public function update_dbt_blnc_by_admin($usr_acnt_id, $crdt_hstry) {

        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        $this->db->set('rupee_balance', 'rupee_balance-' . $crdt_hstry['amount'], false);
        $this->db->where(array('user_id' => $usr_acnt_id));
        $s = $this->db->update('users');

        if ($this->db->affected_rows() > 0) {

            $dbt = $this->db->insert('credit_history', $crdt_hstry);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        }
    }

       public function RolesUser($roles) {
        $this->db->from('users');
        $this->db->where(array('role_id' => $roles));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }



    public function AdminRoles($ViewRoleId) {
        $this->db->from('roles');
        $this->db->where(array('is_active' => 1, 'role_id !=' => 1));
        $this->db->where('role_id <', $ViewRoleId);
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }

  
     public function ParentAdminUser($user_id) {
        $this->db->select('us.first_name,us.last_name,us.mobile,rl.role_name');
       // $this->db->select("us.*,rl.role_name");
        $this->db->from('users us');
        $this->db->join('roles rl', "us.role_id=rl.role_id");
        $this->db->join('users usr', 'us.created_by=usr.user_id', 'left');
        $this->db->where(array('us.user_id' => $user_id));
        
        $s = $this->db->get();
        $sel = $s->row_array();
        if ($sel) {
            return $sel;
        }
    }


    //   public function ParentDataUsers($parentid) {
    //     $this->db->select('u.first_name,u.last_name,u.mobile,r.role_name');
    //     $this->db->from('users u');
    //     $this->db->join('roles r', 'u.role_id = r.role_id');
    //     $this->db->where(array('u.user_id' => $parentid));
    //     $sel = $this->db->get();
    //     $q = $sel->row_array();
    //     if ($q) {
    //         return $q;
    //     }
    // }

     public function ParentDistUsers($parentid) {
        //$this->db->select('u.first_name,u.last_name,u.mobile,r.role_name');
        $this->db->select('u.*,r.role_name');
        $this->db->from('users u');
        $this->db->join('roles r', 'u.role_id = r.role_id');
        $this->db->where(array('u.user_id' => $parentid));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }


    function user_acntid_user_table($e) {
        $e = trim($e);
        $this->db->select('us.*,r.role_name,nus.first_name as parentFirstName,nus.last_name as parentLastName,nus.mobile as parentMobile');
        $this->db->from('users us');
        $this->db->join('roles r', "r.role_id=us.role_id");
        $this->db->join('users nus', 'us.parent_id = nus.user_id', 'left');
        $this->db->where(array('us.user_id' => $e));
        $sel = $this->db->get();
        $s = $sel->row_array();
        if ($s) {
            return $s;
        }
    }

       public function UpdateDataUserParent($UserId, $RoleUserList, $uid) {
        return $this->db->update('users', array('parent_id' => $RoleUserList, 'updated_by' => $uid, 'updated_on' => date('Y-m-d H:i:s')), array('user_id' => $UserId));
    }

       public function CheckUpdateParent($RoleUserList, $Role) {
        $this->db->from('users');
        $this->db->where(array('user_id' => $RoleUserList, 'role_id' => $Role));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }



 public function check_exstng_doc($acntid, $doc) {
        $s = $this->db->get_where('user_docs', array('user_id' => $acntid, 'doc_for' => "KYC", 'doc_name' => $doc));
        return $num = $s->row_array();
    }

    
    public function insert_docby_doc_typ_id($insert) {
        $q = $this->db->insert('user_docs', $insert);
        if ($q) {
            return $q;
        }
    }

     public function check_chng_doc($acntid, $doc, $doc_rowid) {
        $s = $this->db->get_where('user_docs', array('user_id' => $acntid, 'doc_for' => "KYC", 'doc_name' => $doc, 'id' => $doc_rowid));
        return $num = $s->row_array();
    }

    public function update_exstng_doc_bydoctyp($id, $update, $rowid) {
        $this->db->update('user_docs', $update, array('user_id' => $id, 'id' => $rowid));
        if ($this->db->affected_rows() > 0) {
            return true;
        }
    }

       public function PlanListData() {
        $this->db->select('pc.*,r.role_name as RoleName');
        $this->db->from('plan_config pc');
        $this->db->join('roles r', 'pc.plan_for_role = r.role_id');
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }

    function get_all_roles_fr_crt_usr() {
        $this->db->select('role_name,role_id,allowed_for');
        $sel = $this->db->get_where('roles', array('is_active' => 1, 'allowed_for !=' => 0));
        $s = $sel->result_array();
        if ($s) {
            return $s;
        }
    }

       public function checkPlanNameOnupdate($name, $planid) {
        $this->db->from('plan_config');
        $this->db->where(array('plan_name' => $name, 'plan_id !=' => $planid));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

    public function checkPlanCodeOnUpdate($code, $planid) {
        $this->db->from('plan_config');
        $this->db->where(array('plan_code' => $code, 'plan_id !=' => $planid));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

        public function EditPlanData($PlanName, $PlanCode, $Description, $PlanFor, $IsActive, $uid, $PlanId) {
       
        $data = array(
            'plan_name' => $PlanName,
            'plan_code' => $PlanCode,
            'plan_description' => $Description ? $Description : '',
            'plan_for_role' => $PlanFor,
            'created_by' => $uid,
            'updated_on' => date('Y-m-d H:i:s'),
            'is_active' => ($IsActive == 'true') ? 1 : 0,
           
        );

        $this->db->update('plan_config', $data, array('plan_id' => $PlanId));
        if ($this->db->affected_rows() > 0 || $this->db->affected_rows() == 0) {
            return true;
        }
    }

      public function check_extng_planName($name) {
        $this->db->from('plan_config');
        $this->db->where(array('plan_name' => $name));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

    
    public function check_extng_planCode($code) {
        $this->db->from('plan_config');
        $this->db->where(array('plan_code' => $code));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

    public function FetchAllService() {
        $this->db->from('services');
        $this->db->where(array('is_active' => 1));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }
    public function AddPlanData($PlanName, $PlanCode, $Description, $PlanFor, $IsActive, $uid) {

       
        $data = array(
            'plan_name' => $PlanName,
            'plan_code' => $PlanCode,
            'plan_description' => $Description ? $Description : '',
            'plan_for_role' => $PlanFor,
            'created_by' => $uid,
            'created_on' => date('Y-m-d H:i:s'),
            'is_active' => ($IsActive == 'true') ? 1 : 0,
            
        );

        $q = $this->db->insert('plan_config', $data);
        $id = $this->db->insert_id();
        if ($id) {
            return $id;
        }
    }


        public function PlanDataServiceAdd($Service, $PlanId, $uid) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        foreach ($Service as $key => $val) {
            $data = array(
                'plan_id' => $PlanId,
                'service_id' => $val['ServiceName'],
                'charge_type' => $val['ChargeType'],
                'charge_method' => $val['ChargeMethod'],
                'rate' => $val['Rate'],
                'capping_amount' => $val['CappingAmount'] ? $val['CappingAmount'] : NULL,
                'slab_applicable' => ($val['SlabApplicable'] == 'true') ? 1 : 0,
            );

            $this->db->insert('plan_service_config', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === TRUE) {
            return true;
        }
    }


      public function PlanServiceListData($plan) {
        $this->db->select('psc.*,s.service_name,s.code');
        $this->db->from('plan_service_config psc');
        $this->db->join('services s', 'psc.service_id = s.service_id');
        $this->db->where(array('psc.plan_id' => $plan));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }



    public function plan_name_by_planid($plnid){
         if ($plnid) {
            $s = $this->db->get_where('plan_config', array('plan_id' => $plnid));
            $f = $s->row_array();
            if ($f) {
                return $f;
            }
        }
    }


        public function PlanDataServiceUpdate($Service, $PlanId, $uid) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        $this->db->delete('plan_service_config', array('plan_id' => $PlanId));
        
        foreach ($Service as $key => $val) {
            $data = array(
                'plan_id' => $PlanId,
                'service_id' => $val['ServiceName'],
                'charge_type' => $val['ChargeType'],
                'charge_method' => $val['ChargeMethod'],
                'rate' => $val['Rate'],
                'capping_amount' => $val['CappingAmount'] ? $val['CappingAmount'] : NULL,
                'slab_applicable' => ($val['SlabApplicable'] == 'true') ? 1 : 0,
            );

            $this->db->insert('plan_service_config', $data);
        }
    

        $this->db->trans_complete();
        if ($this->db->trans_status() === TRUE) {
            return true;
        }
    }


        public function PlanSlabApplicableListData($PlanId) {
       // $this->db->select('psc.pl_srvc_rl_id as PlanServiceConfig,psc.charge_type,psc.charge_method,s.service_name,s.code');
        $this->db->select('psc.pl_srvc_rl_id as PlanServiceConfig,s.service_name,s.code');
        $this->db->from('plan_service_config psc');
        $this->db->join('services s', 'psc.service_id = s.service_id');
        $this->db->where(array('psc.plan_id' => $PlanId, 'slab_applicable' => 1));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }

    public function PlanSlabApplicableList($serviceId) {
        $this->db->select('psrvc.*');
        $this->db->from('plan_amnt_slab_config psrvc');
        $this->db->join('plan_service_config psc', 'psc.plan_id =psrvc.plan_id  AND psc.service_id =psrvc.service_id');
        $this->db->where(array('psc.pl_srvc_rl_id' =>$serviceId));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }

     public function checkServiceConfigExist($id) {
        $this->db->from('plan_service_config');
        $this->db->where(array('pl_srvc_rl_id' => $id));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

       public function SlabRatesDataInsert($planid,$Servc_id, $SlabRates) {
        $this->db->delete('plan_amnt_slab_config', array('plan_id' => $planid,'service_id'=>$Servc_id));

        $q = $this->db->insert_batch('plan_amnt_slab_config', $SlabRates);
        if ($q) {
            return $q;
        }
    }

     public function CheckVendorNameDataOnUpdate($VendorName, $Id){
        $this->db->from('vendor_list');
        $this->db->where(array('vendor_name' => $VendorName, 'vendor_id !=' => $Id));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        } 
    }

     public function CheckVendorCodeDataOnUpdate($vCode, $vid) {
        $this->db->from('vendor_list');
        $this->db->where(array('vendor_code' => $vCode, 'vendor_id !=' => $vid));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }


      public function VendorDataEdit($VendorName, $VendorCode, $VendorLibrary,$CompanyInfo, $BillingContactsTo, $BillingContactsCC, $SupportContactsTo, $SupportContactsCC, $BalanceCheckApi, $ApiStatus, $IsDown, $IsActive, $Id) {
        $data = array(
            'vendor_name' => $VendorName,
            'vendor_code' => $VendorCode,
            'vendor_library' => $VendorLibrary ? $VendorLibrary : null,
            'bal_check_api' => ($BalanceCheckApi == 'true') ? 1 : 0,
            'status_api' => ($ApiStatus == 'true') ? 1 : 0,
         
            'company_info' => $CompanyInfo ? $CompanyInfo : null,
            'billing_contacts_to' => $BillingContactsTo ? $BillingContactsTo : null,
            'billing_contacts_cc' => $BillingContactsCC ? $BillingContactsCC : null,
            'support_contacts_to' => $SupportContactsTo ? $SupportContactsTo : null,
            'support_contacts_cc' => $SupportContactsCC ? $SupportContactsCC : null,
            'is_down' => ($IsDown == 'true') ? 1 : 0,
            'is_active' => ($IsActive == 'true') ? 1 : 0,
        );

        $this->db->update('vendor_list', $data, array('vendor_id' => $Id));
        if ($this->db->affected_rows() > 0) {
            return true;
        }
    }

      public function CheckVendorCodeData($vCode) {
        $this->db->from('vendor_list');
        $this->db->where(array('vendor_code' => $vCode));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }


    public function CheckVendorNameData($vname) {
        $this->db->from('vendor_list');
        $this->db->where(array('vendor_name' => $vname));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }


     public function VendorDataAdd($VendorName, $VendorCode, $VendorLibrary,$CompanyInfo, $BillingContactsTo, $BillingContactsCC, $SupportContactsTo, $SupportContactsCC, $BalanceCheckApi, $ApiStatus, $IsDown, $IsActive) {
        
        $BalanceCheckApi = $BalanceCheckApi == 'true' ? 1 : 0;
        $ApiStatus = $ApiStatus == 'true' ? 1 : 0;
        $IsDown = $IsDown == 'true' ? 1 : 0;
        $IsActive = $IsActive == 'true' ? 1 : 0;

        $data = array(
            'vendor_name' => $VendorName,
            'vendor_code' => $VendorCode,
            'vendor_library' => $VendorLibrary ? $VendorLibrary : null,
            'bal_check_api' => $BalanceCheckApi,
            'status_api' => $ApiStatus,
          
            'company_info' => $CompanyInfo ? $CompanyInfo : null,
            'billing_contacts_to' => $BillingContactsTo ? $BillingContactsTo : null,
            'billing_contacts_cc' => $BillingContactsCC ? $BillingContactsCC : null,
            'support_contacts_to' => $SupportContactsTo ? $SupportContactsTo : null,
            'support_contacts_cc' => $SupportContactsCC ? $SupportContactsCC : null,
            'is_down' => $IsDown,
            'is_active' => $IsActive,
        );

        $q = $this->db->insert('vendor_list', $data);

         if ($q) {
            return $q;
        }
       
    }


     public function getAllDataVendor($vId) {
        $this->db->from('vendor_list');
        $this->db->where(array('vendor_id' => $vId));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }

   
    public function FetchServiceVendorConfigData($vId) {
        $this->db->select('svc.*,s.service_name,s.code,s.type');
        $this->db->from('service_vendor_config svc');
        $this->db->join('services s', 'svc.service_id = s.service_id');
        $this->db->join('vendor_list vl', 'svc.vendor_id = vl.vendor_id');
        $this->db->where(array('svc.vendor_id' => $vId));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }

    public function FetchAllVendorService($VendorId) {
        $this->db->from('service_vendor_config');
        $this->db->where(array('vendor_id' => $VendorId));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }

     public function checkServiceKey($serviceKey) {
        $this->db->from('service_vendor_config');
        $this->db->where(array('vendor_key' => $serviceKey));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }


      public function vendorDataServiceAdd($Service, $VendorId, $uid) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        $q = $this->db->delete('service_vendor_config', array('vendor_id' => $VendorId));

        foreach ($Service as $key => $val) {
            $data = array(
                'service_id' => $val['ServiceName'],
                'vendor_id' => $VendorId,
                'vendor_key' => $val['ServiceKey'],
                'rate_charge_type' => $val['ChargeType'],
                'rate_charge_method' => $val['ChargeMethod'],
                'margin' => $val['Margin'],
                'capping_amount' => $val['CappingAmount'] ? $val['CappingAmount'] : null,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $uid,
            );

            $this->db->insert('service_vendor_config', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === TRUE) {
            return true;
        }
    }


    public function all_service_provder() {
        $this->db->order_by('sp.service_id', 'DESC');
        $this->db->select("sp.*,COALESCE (vndr.vendor_name,'Not Defined') As vendor_name");
        $this->db->from('services sp');
        $this->db->join('vendor_list vndr', "sp.served_by=vndr.vendor_id", 'left');

        $s = $this->db->get();
        $sel = $s->result_array();
        if ($sel) {
            return $sel;
        }
    }



     //   public function all_service_provder() {
     // $this->db->from('services');
     //    $sel = $this->db->get();
     //    $q = $sel->result_array();
     //    if ($q) {
     //        return $q;
     //    }
     //      }


    

     public function ServiceAmountRange($serviceId) {
        $this->db->select('psrvc.*');
        $this->db->from('service_amnt_routing psrvc');
        $this->db->join('services srvc', 'srvc.service_id =psrvc.service_id');
        $this->db->where(array('srvc.service_id' =>$serviceId));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
    }

    public function ManageServiceData($upDown, $activeInactive, $amntSwitching, $routeDat, $serviceId, $user_id){
        $todaydate=date('Y-m-d H:i:s');
        $upDown = $upDown == 'true' ? 1 : 0;
        $activeInactive = $activeInactive == 'true' ? 1 : 0;
        $amntSwitching = $amntSwitching == 'true' ? 1 : 0;
        if ($amntSwitching == 1) {
            $supported=array();
            $vendoramntRng=array();
            if (is_array($routeDat)) {
                foreach ($routeDat as $k) {
                    $supported[] = $k['route'];

                    $vendoramntRng[] = array(
                     
                        'service_id' => $serviceId,
                        'Min_amnt' => $k['minamount'],
                        'Max_amnt' => $k['maxamount'],
                        'served_by'=>$k['route'],
                        'created_on'=>$todaydate,
                        'created_by'=>$user_id
                    );
                }

               

            } else {

                return false;
            }

             $data = array(
                'is_down' => $upDown,
                'is_active' => $activeInactive,
              
                'Amnt_by_routing' => $amntSwitching,
                'updated_on' => $todaydate,
                'updated_by' => $user_id
            );

            $this->db->update('services', $data, array('service_id' => $serviceId));
            if ($this->db->affected_rows() > 0) {

                $this->db->delete('service_amnt_routing', array('service_id' => $serviceId));

                $q = $this->db->insert_batch('service_amnt_routing', $vendoramntRng);
                if ($q) {
                    return TRUE;
                }
            }

        }else{

            $data = array(
                'is_down' => $upDown,
                'is_active' => $activeInactive,
                'served_by' => $routeDat,
                'Amnt_by_routing' => $amntSwitching,
                'updated_on' => $todaydate,
                'updated_by' => $user_id
            );

            $this->db->update('services', $data, array('service_id' => $serviceId));
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }


        }
    }

    public function Servicedtl_by_servcid ($servcid) {   
        $this->db->from('services');
        $this->db->where(array('service_id' => $servcid));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        } 
    }


    public function fetch_bank_list() {
        $sel = $this->db->get('bank_details');
        $f = $sel->result_array();
        if ($f) {
            return $f;
        }
    }
      public function get_user_parent_role($id){
    $sel = $this->db->get_where('users', array('user_id' =>$id));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }

     public function fetch_paymnet_rqst_by_usr($userId){
        $this->db->order_by('tableauto_id', 'DESC');
        $sel = $this->db->get_where('payment_requests', array('user_id' =>$userId));
        $f = $sel->result_array();
        if ($f) {
            return $f;
        }

    }

    public function fetch_bank_dtl_rqst_by_usr($bankId){

        $sel = $this->db->get_where('bank_details', array('tableauto_id' =>$bankId));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }

    }

    public function InsertPaymentRequest($insert){
        return $this->db->insert('payment_requests',$insert);
    }


    public function get_all_child_of_usr($usrid,$roleid){
        if($roleid==1){
          $sel = $this->db->get_where('users', array('parent_id' =>0));

        }else{

            $sel = $this->db->get_where('users', array('parent_id' =>$usrid)); 
        }
       
        $f = $sel->result_array();
        if ($f) {
            return $f;
        }
    }


    public function all_cld_dtl_payment_req($usersId){
            $this->db->order_by('pr.tableauto_id', 'DESC');
            $this->db->select('pr.*,u.first_name,u.last_name,u.mobile');
            $this->db->from('payment_requests pr');
            $this->db->join('users u', 'pr.user_id = u.user_id');
            $this->db->where('pr.user_id', $usersId);
            $sel2 = $this->db->get();
            $q2 = $sel2->result_array();
            if ($q2) {
                return $q2;
            }
    }

    public function FetchPaymentRequestDetails($PayId) {
        $sel = $this->db->get_where('payment_requests', array('tableauto_id' => $PayId));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }

    public function check_usr_pymnt_rqst_crdtd_chtstry($BankRefNo, $Amount, $payuser_id) {

         $sel = $this->db->get_where('credit_history', (array('reference_number' => $BankRefNo, 'user_id' => $payuser_id, 'amount' => $Amount)));
        $f = $sel->result_array();
        if ($f) {
            return $f;
        }

    }

    public function update_aprv_pymnt_rqst_by_admin($id, $insrt_cdt_hstry, $usr_id, $updt_pymnt_rqst) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        $cdt = $this->db->insert('credit_history', $insrt_cdt_hstry);
        if ($cdt) {
            $this->db->set('rupee_balance', 'rupee_balance+' . $insrt_cdt_hstry['amount'], false);
            $this->db->where(array('user_id' => $usr_id));
            $s = $this->db->update('users');
            $this->db->update('payment_requests', $updt_pymnt_rqst, array('tableauto_id' => $id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        }
    }

    public function update_aprv_pymnt_rqst_by_distbtr($pymntrqst_id, $cdt_hstry_frdist, $cdt_hstryfrret, $updt_pymnt_rqst) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        $this->db->set('rupee_balance', 'rupee_balance-' . $cdt_hstry_frdist['amount'], false);
        $this->db->where(array('user_id' => $cdt_hstry_frdist['user_id']));
        $s = $this->db->update('users');
        if ($this->db->affected_rows() > 0) {
            /*             * *if distbtr login** */
            $dist_lgn = $this->db->insert('credit_history', $cdt_hstry_frdist); //DEBIT
            $rtlr_pymnt_rqst = $this->db->insert('credit_history', $cdt_hstryfrret); //CREDIT
            $this->db->set('rupee_balance', 'rupee_balance+' . $cdt_hstryfrret['amount'], false); //RUPEE_BAL ADDED IN ACCNT
            $this->db->where(array('user_id' => $cdt_hstryfrret['user_id']));
            $s = $this->db->update('users');
            //Updt paymt rqst apprvd in tbl         
            $this->db->update('payment_requests', $updt_pymnt_rqst, array('tableauto_id' => $pymntrqst_id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        }
    }

    public function fetch_paymt_dtl($PayId) {
        $sel = $this->db->get_where('payment_requests', array('tableauto_id' => $PayId));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }

    function RejectPaymentRequest($tid, $uid) {
        return $this->db->update('payment_requests', array('status' => 'REJECTED', 'updated_dt' => date('Y-m-d H:i:s'), ' updated_by' => $uid), array('tableauto_id' => $tid));
    }

    public function fetch_service_prov($code) {          
        $this->db->select('sp.*,v.vendor_id,v.vendor_name,v.vendor_library,v.is_down as gateway_down,v.is_active AS gateway_active');
        $this->db->from('services sp');
        $this->db->join('vendor_list v', 'v.vendor_id=sp.served_by');
        $this->db->where(array('sp.is_active' => 1, 'v.is_active' => 1, 'sp.code' => $code));   
        $sel = $this->db->get();
        // echo $this->db->last_query();
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }

    public function check_vld_remtt_txnid($chId) {
        $this->db->from('credit_history');
        $this->db->where(array('credit_txnid' => $chId));
        $sel = $this->db->get();
        $q = $sel->row_array();
        if ($q) {
            return $q;
        }
    }


    public function servc_txn_params($service_id) {
        $this->db->order_by('order', 'ASC');
        $this->db->from('service_txn_params');
        $this->db->where(array('service_id' => $service_id));
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            $b = array();
            if ($q) {
                foreach ($q as $v => $k) {
                    $b[$k['param_code']] = $k;
                }
            } else {
                
                $b = [];
            }
            return $b;
        }
    }


     function check_outlet_id($uid) {
        $sel = $this->db->get_where('bc_agent', array('user_id' => $uid));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }

    function update_apibox_outlet_id($user_id, $outletid, $BBPS) {
        $sel = $this->db->get_where('bc_agent', array('user_id' => $user_id));
        $f = $sel->row_array();
        if ($f) {

            if ($f['agent_code'] == null || $f['kyc_apibox'] == null) {

                return $this->db->update('bc_agent', array('agent_code' => $outletid, 'kyc_apibox' => $BBPS, 'updated_on' => date('Y-m-d H:i:s')), array('user_id' => $user_id));
                
            } else if ($f['kyc_apibox'] == 'PENDING') {

                return $this->db->update('bc_agent', array('agent_code' => $outletid, 'kyc_apibox' => $BBPS, 'updated_on' => date('Y-m-d H:i:s')), array('user_id' => $user_id));
            }

        } else {

            return $this->db->insert('bc_agent', array('user_id' => $user_id, 'agent_code' => $outletid, 'kyc_apibox' => $BBPS, 'created_on' => date('Y-m-d H:i:s')));
        }
    }

    public function is_agent_fr_user_activate($uid,$pndgkyc){
        $sel = $this->db->get_where('bc_agent', array('user_id' => $uid,'kyc_apibox'=>$pndgkyc));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }

    }

    public function user_activated_KYC($id,$update){
        return $this->db->update('bc_agent', $update, array('user_id' => $id));
       
    }

    public function get_agentcode_active_KYC($uid){
        $sel = $this->db->get_where('bc_agent', array('user_id' => $uid,'kyc_apibox'=>'ACTIVE'));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }

    }
	
	 public function fetch_opr_bymob($mob) {

        $mob = trim($mob);
        $this->db->select('Operator,OPSC as opcode,CSC as stcode');
        $sel = $this->db->get_where('mobie_opr', array('Number' => $mob));
        $f = $sel->row_array();
        if ($f) {
            return $f;
        }
    }
	
	 public function getallDueToBePaid($accntid,$role_id){  
     
        $this->db->select("sum(cdht.amount) as 'totaldueamnt'");

        if ($role_id == 2) {
			
            $this->db->from('credit_history cdht');
            $this->db->join('users u', 'u.user_id=cdht.user_id');
            $this->db->where(array('cdht.is_due' => 1,'cdht.is_received'=>0,'u.parent_id'=>'0','u.user_id'=>$accntid));

        } else {

           
                $this->db->from('credit_history cdht');
                $this->db->join('users u', 'u.user_id=cdht.user_id');
                $this->db->where(array('cdht.is_due' => 1,'cdht.is_received'=>0,'u.user_id'=>$accntid));
  
          
        }
        $sel = $this->db->get();

        $q = $sel->row_array();  
        if ($q) {
           return $q;

        }  
    }
	  
	
	public function getTdyTxnDtl_user($user_id,$role_id, $time_peroid='today'){
		$result = array();
		if ($role_id==2 || $role_id==3 || $role_id==4) {  
            $this->db->order_by('atx.id',"DESC");
    		$this->db->select("sum(atx.transamt) as 'tdy_totalamnt'");
           
            $this->db->from('usertxn_table atx');
            $this->db->where(array('atx.user_id' =>$user_id,'atx.status!='=>'REFUND'));
          
            switch($time_peroid){
                case 'today':
                    $this->db->like('req_dt', date('Y-m-d'));
                    break;
                case 'cur_month':
                    $this->db->like('DATE_FORMAT(req_dt, \'%Y-m\')', date('Y-m'));
                    break;
                case 'pre_month':
                    $this->db->like('DATE_FORMAT(req_dt, \'%Y-m\')', date('Y-m', strtotime(date('Y-m')." -1 month")));
                    break;
            }
            $query = $this->db->get();
            curlRequertLogs(array($this->db->last_query()), $time_peroid.'query-getTdyTxnDtl_user', 'mainModel');
            $result = $query->row_array();
		}
		return $result;  	
	}




    function delNoteRow($reqdelid) {
        return $this->db->delete('portal_notifs', array('id' => $reqdelid));
    }











}

?>
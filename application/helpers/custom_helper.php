<?php

if (!defined('BASEPATH'))exit('No direct script access allowed');

    
if (!function_exists('All_Regex')) {

    function All_Regex() {
        $regex = [];
        $regex = [
            'Name' => '^[a-zA-Z ]+$',
            'Code' => '^[a-zA-Z]+$',
            'Text' => '^[a-zA-Z0-9 !@#$&()-`.+,"]*$',
            'Mobile' => ['Full' => '^[6-9]\d{9}$', 'Start' => '[6-9]', 'Allowed' => '[0-9]'],
            'OTP' => ['Full' => '^\d{6}$', 'Start' => '[0-9]', 'Allowed' => '[0-9]'],
            'Email' => ['Full' => "^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$", 'Allowed' => "^[a-zA-Z0-9 \-\()~!@#$%^&*_+=*{}:;'<>?\/\\\\\|\, .']+$"],
             'GSTTIN' => ['Full' => "^[0-9]{2}[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[0-9][Z][0-9A-Za-z]$", 'Allowed' => "[a-zA-Z0-9]"],
            'PanNumber' => ['Full' => "^([A-Z]){5}([0-9]){4}([A-Z]){1}?$", 'Allowed' => "[a-zA-Z0-9]"],
             'AadharNumber' => ['Full' => "^\d{12}$", 'Allowed' => "[0-9]"],
             'DateOfBirth' => "^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$",
            'BankAccount' => ['Full' => "^\d{9,18}$", 'Allowed' => "[0-9]"],
            'BankIFSC' => ['Full' => "^[A-Za-z]{4}[a-zA-Z0-9]{7}$", 'Allowed' => "[a-zA-Z0-9]"],
            'Rate' => "^(\d+)?([.]?\d{0,2})?$",
            'BillingModel' => ['P2A' => 'P2A', 'P2P' => 'P2P'],
            'Number' => "^[0-9]*$",
            'Cappingtype' => ['MIN' => 'MIN', 'MAX' => 'MAX'],
            'ServiceType' => ['PREPAID' => 'PREPAID', 'DTH' => 'DTH', 'POSTPAID' => 'POSTPAID', 'LANDLINE' => 'LANDLINE', 'GAS' => 'GAS', 'ELECTRICITY'=>'ELECTRICITY','REMITTANCE'=>'REMITTANCE'],
            'ChargeType' => ['FIXED' => 'Fixed', 'PERCENTAGE' => 'Percentage'],
            'ChargeMethod' => ['CREDIT' => 'Commission', 'DEBIT' => 'Surcharge'],
             'AllowedKYCDocs' => ['ID PROOF' => 'ID PROOF', 'PHOTO PROOF' => 'PHOTO PROOF', 'ADDRESS PROOF' => 'ADDRESS PROOF', 'AADHAR CARD' => 'AADHAR CARD'],
              'Amount'=> "^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$",
              // 'Amount' => ['Full' => '^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$', 'Start' => '[1-9]', 'Allowed' => '[0-9]'],
            'PAYNENTMODE' => ['IMPS'=> 'IMPS', 'Cash'=>'Cash', 'NEFT'=> 'NEFT','OTHERS'=>'OTHERS', 'Admin'=> 'Admin'],
            // 'NOTIFOR' => ['0'=> 'ALL ROLES', '1'=>'Super Distributor', '2'=> 'Distributor','4'=>'Retailer'],
        ];    
        return $regex;
    }

}   

    if (!function_exists('ip_address')) {

        function ip_address() {
            $ip = '0.0.0.0';
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED'];
            } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_FORWARDED_FOR'];
            } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
                $ip = $_SERVER['HTTP_FORWARDED'];
            } else if (!empty($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }

    }

    if (!function_exists('get_user_details')) {

    function get_user_details() {
        $data = [];
        //get main CodeIgniter object
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
        $accnt_id = $CI->session->userdata('userid');

        $user_info = $CI->main->user_acntid($accnt_id);
        if ($user_info) {
            //return $user_info;exit;
            $data['ac'] = $user_info['user_id'];
            $data['fname'] = $user_info['first_name'];
            $data['lname'] = $user_info['last_name'];
            $data['gender'] = $user_info['gender'];
            $data['business_name'] = $user_info['business_name'];
            $data['business_addr'] = $user_info['business_address'];
            $data['business_state'] = $user_info['business_state'];
            $data['business_city'] = $user_info['business_city'];
            $data['business_pincode'] = $user_info['business_pincode'];
            $data['email_id'] = $user_info['email'];
            $data['permanent_addr'] = $user_info['registered_address'];
            $data['mobile'] = $user_info['mobile'];
            $data['pan_num'] = $user_info['pan'];
            $data['aadhar'] = $user_info['aadhar'];
            $data['rupee_balance'] = $user_info['rupee_balance'];
            $data['outstanding_balance'] = $user_info['outstanding_balance'];
            $data['role_id'] = $user_info['role_id'];
            $data['role_name'] = $user_info['role_name'];
            $data['created_on'] = $user_info['created_on'];
            $data['created_by'] = $user_info['created_by'];
            $data['updated_on'] = $user_info['updated_on'];
            $data['updated_by'] = $user_info['updated_by'];
            $data['is_blocked'] = $user_info['is_block'];
            $data['is_active'] = $user_info['is_active'];
            $data['gstin'] = $user_info['gstin'];
            $data['parentName'] = $user_info['parentName'];
            $data['parentLastName'] = $user_info['parentLName'];
           // $data['parentRoleName'] = $user_info['parentRoleName'];
            $data['parentMobile'] = $user_info['parentMobile'];
            $data['parent_id'] = $user_info['parent_id'];
            $data['profile_pic'] = $user_info['profile_pic'];

            unset($user_info);
        } else {
            $data = [];
        }
        return $data;
    }

}



if (!function_exists('get_user_parent_dtl')) {

    function get_user_parent_dtl() {
        $data = [];
        //get main CodeIgniter object
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
        $accnt_id = $CI->session->userdata('userid');

        $user_info = $CI->main->user_acntid($accnt_id);
        if ($user_info) {

             $user_prnt_info = $CI->main->user_acntid($user_info['parent_id']); 
            //return $user_info;exit;
            $data['ac'] = $user_prnt_info['user_id'];
            $data['fname'] = $user_prnt_info['first_name'];
            $data['lname'] = $user_prnt_info['last_name'];
            $data['gender'] = $user_prnt_info['gender'];
            $data['business_name'] = $user_prnt_info['business_name'];
            $data['business_addr'] = $user_prnt_info['business_address'];
            $data['business_state'] = $user_prnt_info['business_state'];
            $data['business_city'] = $user_prnt_info['business_city'];
            $data['business_pincode'] = $user_prnt_info['business_pincode'];
            $data['email_id'] = $user_prnt_info['email'];
            $data['permanent_addr'] = $user_info['registered_address'];
            $data['mobile'] = $user_prnt_info['mobile'];
            $data['role_name'] = $user_prnt_info['role_name'];
           

            unset($user_info);
        } else {
            $data = [];
        }
        return $data;
    }

}


    if (!function_exists('all_permission')) {

    function all_permission($role) {
        $array = [];
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
        $get_role_perms = $CI->main->get_role_perms($role);
        if ($get_role_perms) {
            foreach ($get_role_perms as $value) {
                $array[] = $value['perm_id'];
            }
            return $array;
        }
    }

    }


    if (!function_exists('page_access')) 
    {
        function page_access($navid,$prmtyp=null) 
        {
        if ($navid) {
            $array = $navbar_permission = $sidebar_permission= array();
            $CI = & get_instance();
            $CI->load->model('Main_model', 'main');
            $role = $CI->session->userdata('role_id');
            if ($role) {

                $pagedata = $CI->main->get_pagedata($navid);

                if ($pagedata) {

                    $navbar_permission = all_permsn_by_type($role, 'NAVBAR');
                    $sidebar_permission = all_permsn_by_type($role,'SIDEBAR');
                    
                    $navbar_permission=$navbar_permission?$navbar_permission:array();
                    $sidebar_permission=$sidebar_permission?$sidebar_permission:array();
                    
                    if (in_array($pagedata['perm_id'], $navbar_permission) || in_array($pagedata['perm_id'], $sidebar_permission)) 
                    {
                        if ($pagedata['parent_id'] == 0) {

                            return true;
                            
                        } else {
                            return page_access($pagedata['parent_id']);
                        }
                    }
                    
                }
            }
        }
    }

    }



    if(! function_exists('get_nav_tittle')){
         
         function get_nav_tittle($parent_id,$prmtyp){
           $array=[];
           $CI =& get_instance();
           $CI->load->model('Main_model', 'main');
            if ($prmtyp != "") {
           $permissions = all_permsn_by_type($CI->session->userdata('role_id'),$prmtyp);

           $navbar = $CI->main->navbar_data($permissions); 
          
           if ($navbar) { 
            $array = $navbar;
            }
          return $array;
      }
        }
        
    }


    if(!function_exists('navbar_parent')){
        
      function navbar_parent($navid){
        $name = [];
        if(ctype_digit($navid)){
          $CI =& get_instance();
          $CI->load->model('Main_model','main');
          $navbar = $CI->main->get_parent_navbar($navid);
          if($navbar){ 
            $name = $navbar;
          }
        }
        return $name;
      }
      
    }

    if(!function_exists ('navbar_child')){   
         function navbar_child($nvcd){
              $array = [];
              $CI =& get_instance();
              $CI->load->model('Main_model','main');
              $navbar_child = $CI->main->get_child_navbar($nvcd);
              if($navbar_child){
                
                $array= $navbar_child;

               }

            return $array;
            }
    }


    if (!function_exists('all_permsn_by_type')) {

    function all_permsn_by_type($role, $prm_typ) {
        $array = [];
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
        $get_role_perms = $CI->main->get_role_perms_by_type($role, $prm_typ);
        if ($get_role_perms) {
            foreach ($get_role_perms as $value) {
                $array[] = $value['perm_id'];
            }
            return $array;
        }
    }

}


    if (!function_exists('permsn_access')) {
    
    function permsn_access($prmid, $prmtyp) {
        if ($prmid) {
            $array = $permission = [];
            $CI = & get_instance();
            $CI->load->model('Main_model', 'main');
            $role = $CI->session->userdata('role_id');
            if ($role) {
                if ($prmtyp != "") {

                    $permission = all_permsn_by_type($role, $prmtyp);
                    if ($permission) {
                        if (in_array($prmid, $permission)) {

                            return true;
                        }
                    }
                }
            }
        }
    }

    }

    if (!function_exists('account_id_creation')) {

    function account_id_creation($roleid) 
    {
        $roleid="$roleid";
        $sequence="";
        
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
        
        $account_seq_array=array("4"=>'TI',"3"=>"TD","2"=>"TS");
        
        if(in_array($roleid,array_keys($account_seq_array)))
        {
            
            $check_if_useridexist=$CI->main->find_last_userfor_role($roleid);
            if($check_if_useridexist)
            {

                $lastuserid=$check_if_useridexist['user_id'];
                
                $sequence_check=substr($lastuserid,0,2);

                if(in_array($sequence_check,array_values($account_seq_array)))
                {
                    $sequence=substr($lastuserid,2,5);
                    
                }else{
                    $sequence="0";
                }

            }else{
                $sequence="0";
            }
            
            if($sequence!="")
            {
                if(is_numeric($sequence))
                {
                    $pl_seq=$sequence+1;

                    $numeric_seq=strlen($pl_seq);

                    if($numeric_seq<5)
                    {
                        $numeric_part='';

                        for($i=0;$i<(5-$numeric_seq);$i++)
                        {
                            $numeric_part.='0';
                        }
                        
                        $numeric_part.=$pl_seq;
                        
                    }else if($numeric_seq==5)
                    {
                        $numeric_part=$pl_seq;
                        
                    }else{
                        return false;
                    }

                    $acct_id=$account_seq_array[$roleid].$numeric_part;
                    
                    $check_unique_accid = $CI->main->check_unq_accid($acct_id);
                    
                    if($check_unique_accid)
                    {
                        return $acct_id;
                    }
                    
                }
            }
            
        
        }
        
    }

}


    if (!function_exists('ch_txnid')) {

    function ch_txnid() {
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');

        $year = substr(date('Y'), -2);
        $letters = array_merge(range('A', 'Z'), range('A', 'Z'));
        // substr(md5($this->_ci->encrypt->encode($msg, $key),0,4))
        $x = 'CH' . $year . date('mdHis') . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)];
        $chck_txnid = $CI->main->check_cdths_txnid($x);
        if ($chck_txnid) {
            return ch_txnid();
        } else {
            return $x;
        }
    }

}

    if (!function_exists('admn_trnsfer_cd')) {

    function admn_trnsfer_cd() {
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');

        $x = 'ADM' . date('ymdHis') . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
        $chck_txnid = $CI->main->check_admin_tnfr_cd($x);
        if ($chck_txnid) {
            return admn_trnsfer_cd();
        } else {
            return $x;
        }
    }   

}


    if (!function_exists('dstrbtr_trnsfer_cd')) {

    function dstrbtr_trnsfer_cd() {
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');

        $x = 'TRF' . date('ymdHis') . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
        $chck_txnid = $CI->main->check_dstrbtr_tnfr_cd($x);
        if ($chck_txnid) {
            return dstrbtr_trnsfer_cd();
        } else {
            return $x;
        }
    }

}

    if (!function_exists('txn_remitt_txnid')) {

    function txn_remitt_txnid() {
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');

        $year = substr(date('Y'), -2);
        $letters = array_merge(range('A', 'Z'), range('A', 'Z'));
        // substr(md5($this->_ci->encrypt->encode($msg, $key),0,4))
        $x = 'Z' . $year . date('mdHis') . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)];
        $chck_txnid = $CI->main->check_vld_remtt_txnid($x);
        if ($chck_txnid) {
            return txn_remitt_txnid();
        } else {
            return $x;
        }
    }

}

	/**************** outstanding balance *************/
	if (!function_exists('get_user_due_pymnts')) {

    function get_user_due_pymnts() {
        $data = [];
        //get main CodeIgniter object
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
         $get_DueToBePaid =$CI->main->getallDueToBePaid($CI->session->userdata('userid'),$CI->session->userdata('role_id'));
        if ($get_DueToBePaid) {
             $data['amnt'] = $get_DueToBePaid['totaldueamnt'];
        }else {
            $data = [];
        }
        return $data;
    }

	}
	/**************** Today Transaction balance **************/
	
	if (!function_exists('get_user_tdy_txn_amnt')) {

    function get_user_tdy_txn_amnt($time_peroid='today') {
        $data = [];
        //get main CodeIgniter object
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
         $get_TdyTxnDtl =$CI->main->getTdyTxnDtl_user($CI->session->userdata('userid'),$CI->session->userdata('role_id'), $time_peroid);
		
        if ($get_TdyTxnDtl) {
             $data['amnt'] = $get_TdyTxnDtl['tdy_totalamnt'];
        }else {
            $data = [];
        }
        return $data;
    }

	}

    if (!function_exists('curlRequertLogs')) {
        function curlRequertLogs($data="No response", $log_name="Unknown", $dir="TestLogsFiles"){
            if(is_array($data)){
                $data = json_encode($data);
            }
            $filename = "log-".$log_name."-".date("YmdHis");
            if($filename){
                $path = "logs/{$dir}/";
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file = fopen($path.$filename.".txt", "w");// or die("Unable to open file!");
                fwrite($file, $data);
                fclose($file);
            }
        }
    }
    

    if (!function_exists('generate_txnid')) {

    function generate_txnid() {
        $CI = & get_instance();
        $CI->load->model('Main_model', 'main');
        $year = substr(date('Y'), -2);
        $letters = array_merge(range('A', 'Z'), range('A', 'Z'));
        $x = 'Z' . $year . date('mdHis') . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)];
        $chck_txnid = $CI->main->check_txntable_txnid($x);
        if ($chck_txnid) {
            return txn_remitt_txnid();
        } else {
            return $x;
        }
    }

}
    
if (!function_exists('verify_whitelable_autheticity')) 
{
    function verify_whitelable_autheticity($requestdata)
    {
        $ci=& get_instance();
        $data = json_decode($requestdata);
        $data_without_hash = json_decode($requestdata);
        if($data && $data_without_hash){
        $ci->load->config('apb_config');
        // Remove Property keys request_id and hash
        unset($data_without_hash->hash);
        unset($data_without_hash->request_id);

        // Calculate SHA256 hash
        $new_hash = hash('sha256', json_encode($data_without_hash),$ci->config->item('aeps_config')['APP_TOKEN']);

    
        // Return true if authentication is successful
        // Otherwise return false on failure
        if($data->hash == $new_hash) {
            return true;
            }
        }
        return false;
        
    }

}
    




?>
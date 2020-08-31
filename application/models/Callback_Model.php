<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Callback_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    function insert_vendor_callback(array $log_array)
    {
        if(count($log_array)>0)
        {
            return $this->db->insert('vendor_callback_log',$log_array);
        }
        
    } 
    
    function update_vendor_callback(array $log_update_array,$updateid)
    {
        return $this->db->update('vendor_callback_log',$log_update_array,array('id'=>$updateid));
    }
    
    function get_txn_data($txnid)
    {
        $query=$this->db->from('usertxn_table')->where(array('fstpytxn_id'=>$txnid))->get_compiled_select();
        $data=$this->db->query("{$query}")->row_array();
        
        if($data)
        {
            return $data;
        }
        
    } 
    
    
    
}

?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

    class Alerts extends CI_Model
    {

    function find_alert_type($code)
    {
        $sel= $this->db->get_where('service_alerts',array('alert_code'=>$code));
        $f=$sel->row_array();
        if($f)
        {
            return $f;
        }

    }

    function user_smsalert($userid,$sms_text,$code,$event)
    {
        
        return $this->db->insert('user_otps',array('userid'=>$userid,'content'=>$sms_text,'otp'=>$code,'event_type'=>$event,'sent_on'=>'SMS','is_active'=>1,'created_on'=>date('Y-m-d H:i:s')));
    }

    function user_mailalert($userid,$email_text,$code,$event)
    {
        
        return $this->db->insert('user_otps',array('userid'=>$userid,'content'=>$email_text,'otp'=>$code,'event_type'=>$event,'sent_on'=>'EMAIL','is_active'=>1,'created_on'=>date('Y-m-d H:i:s')));
    
    }

    function user_mailalertfortrans($userid,$email_text,$code,$event) 
    {

        return $this->db->insert('notification_report',array('userid'=>$userid,'msg'=>$email_text,'transid'=>$code,'Event'=>$event,'otp_via'=>'EMAIL','created_on'=>date('Y-m-d H:i:s')));
    }
    function user_smsalertfortrans($userid,$email_text,$code,$event) 
    {

        return $this->db->insert('notification_report',array('userid'=>$userid,'msg'=>$email_text,'transid'=>$code,'Event'=>$event,'otp_via'=>'SMS','created_on'=>date('Y-m-d H:i:s')));
    }
    function find_prev_msg($userid,$event,$type)
    {
        $sel=$this->db->get_where('user_otps',array('userid'=>$userid,'event_type'=>$event,'sent_on'=>$type,'is_active'=>1));
        $f=$sel->row_array();
        if($f)
        {
            return $f;
        }
    }

    function inactive_old_record($id)
    {
        return $this->db->update('user_otps',array('is_active'=>0),array('id'=>$id));
    }

    
    function testinginsert($msg, $url) {
         $ip_address = ip_address();
        return $this->db->insert('testing', array('request' => $msg, 'ip' => $ip_address, 'url' => $url, 'date' => date('Y-m-d H:i:s')));
    }
  
        
    function apblogs($insert_array)
    {
        return $this->db->insert('sms_logs',$insert_array);
    }

   
        
}

?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Service_alerts {

    private $token="";
    function __construct() {
		
        $this->_ci = & get_instance();

        $this->_ci->load->model('Alerts');
        $this->_ci->load->config('apb_config');
        $this->token=$this->_ci->config->item('req_token');
    }

      public function request_fr_usr_otp($code,$number, $userid,$msg,$event){
        $data = array();
        $find = $this->_ci->Alerts->find_alert_type($code);
        if ($find) {
            
            if ($find['is_dynamic'] == 1) {
                if ($find['sms_active'] == 1) {

                    if ($code == "SLV") {
                        $bfrtimeout = $this->_ci->Airtel_model->find_prev_msg($number,$userid, $event, 'SMS');
                        if ($bfrtimeout) {


                            if (date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($bfrtimeout['created_on']))) > date('Y-m-d H:i:s')) {

                                $find['sms_text'] = $bfrtimeout['content'];
                            } else {
                                $this->_ci->Airtel_model->inactive_old_record($bfrtimeout['id']);
                                $find['sms_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['sms_text']));
                                $this->_ci->Airtel_model->user_smsalert($userid,$number,$find['sms_text'], $msg, $event);
                            }
                            $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                            $data['response_msg'] = $check;
                        } else {


                            $find['sms_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['sms_text']));
                            $this->_ci->Airtel_model->user_smsalert($userid,$number, $find['sms_text'], $msg, $event);
                            $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                            $data['response_msg'] = $check;
                        }
                    } else {
                        $find['sms_text'] = str_replace('$' . $code . '$', $msg, $find['sms_text']);
                        $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                        $data['response_msg'] = $check;
                    }
                }
               
            } else {

                if ($find['sms_active'] == 1) {

                    $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                    $data['response_msg'] = $check;
                }
                
            }
        }else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid alert type';
            $data['msg'] = null;
        }
        return $data;

    }


      function user_2fa($code, $number, $email, $userid, $msg, $event) {
		
        $data = array();
        $find = $this->_ci->Alerts->find_alert_type($code);
        if ($find) {
			
            if ($find['is_dynamic'] == 1) {
                if ($find['sms_active'] == 1) {

                    if ($code == "OTP") {
                        $bfrtimeout = $this->_ci->Alerts->find_prev_msg($userid, $event, 'SMS');
                        if ($bfrtimeout) {

                            if (date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($bfrtimeout['created_on']))) > date('Y-m-d H:i:s')) {

                                $find['sms_text'] = $bfrtimeout['content'];
                            } else {
                                $this->_ci->Alerts->inactive_old_record($bfrtimeout['id']);
                                $find['sms_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['sms_text']));
                                $this->_ci->Alerts->user_smsalert($userid, $find['sms_text'], $msg, $event);
                            }
                            $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                            $data['response_msg'] = $check;
                        } else {


                            $find['sms_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['sms_text']));
                            $this->_ci->Alerts->user_smsalert($userid, $find['sms_text'], $msg, $event);
                            $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                            $data['response_msg'] = $check;
                        }
                    } else {
                        $find['sms_text'] = str_replace('$' . $code . '$', $msg, $find['sms_text']);
                        $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                        $data['response_msg'] = $check;
                    }
                }
                if ($find['email_active'] == 1) {

                    if ($code == "OTP") {
                        $bfrtimeout = $this->_ci->Alerts->find_prev_msg($userid, $event, 'EMAIL');
                        if ($bfrtimeout) {

                            if (date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($bfrtimeout['created_on']))) > date('Y-m-d H:i:s')) {
                                $find['email_text'] = $bfrtimeout['content'];
                            } else {
                                $this->_ci->Alerts->inactive_old_record($bfrtimeout['id']);
                                $find['email_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['email_text']));
                                $this->_ci->Alerts->user_mailalert($userid, $find['email_text'], $msg, $event);
                            }
                            $em = $this->sendmail($find, $email, $userid, $event, $code, '');
                            $data['response_email'] = $em;
                        } else {

                            $find['email_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['email_text']));
                            $em = $this->sendmail($find, $email, $userid, $event, $code, '');
                            $data['response_email'] = $em;
                            $this->_ci->Alerts->user_mailalert($userid, $find['email_text'], $msg, $event);
                        }
                    } else {
                        $find['email_text'] = str_replace('$' . $code . '$', $msg, $find['email_text']);

                        $em = $this->sendmail($find, $email, $userid, $event, $code, '');
                        $data['response_email'] = $em;
                    }
                }
            } else {

                if ($find['sms_active'] == 1) {

                    $check = $this->sendmsg($find, $number, $userid, $event, $code, '');
                    $data['response_msg'] = $check;
                }
                if ($find['email_active'] == 1) {
                    $em = $this->sendmail($find, $email, $userid, $event, $code, '');
                    $data['response_email'] = $em;
                }
            }
        }else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid alert type';
            $data['msg'] = null;
        }
        return $data;
    }

        private function sendmsg($find, $number, $userid, $event, $code, $txnid) {
        $msg = '';
        $text = urlencode($find['sms_text']);
        $sender = $find['sms_sender'];
        $number = urlencode($number);
        $reqid = $this->create_id();
        
        $url = 'https://www.apibox.xyz/api/Action/transact?token='.$this->token.'&skey=SST&reqid=' . $reqid . '&p1=send&p2=' . $number . '&p4=TEXT&p5=' . $text . '&p6=' . $sender;

          $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_POST=>0,
                CURLOPT_RETURNTRANSFER => true,
                
                
            ));
            $msg = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
        
        $rsp=$msg?$msg:'Timedout';  
        
        // $insert_array=array("vendor_id"=>$userid,"request"=>$url,"recipient"=>$number,"response"=>$rsp,"created_on"=>date('Y-m-d H:i:s'));
        // $this->_ci->Alerts->apblogs($insert_array);

        $insert_array=array("vendor_id"=>$userid,"request"=>$url,"response"=>$rsp,"recipient"=>$number,"created_on"=>date('Y-m-d H:i:s'));

        $this->_ci->Alerts->apblogs($insert_array);
        
        
        // $this->_ci->Alerts->testinginsert($msg, $url);
        // if ($code != 'OTP') {
        //     $this->_ci->Alerts->user_smsalertfortrans($userid, $find['sms_text'], $txnid, $event);
        // }
        return json_decode($msg);  
        //return $url;
    }

        private function sendmail($find, $email, $userid, $event, $code, $txnid) {

        $this->_ci->load->library('email');
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->_ci->email->initialize($config);
        $this->_ci->email->from($find['email_from'], $find['email_fromname']);
        $this->_ci->email->to($email);
        $this->_ci->email->subject($find['email_subject']);
        $this->_ci->email->message($find['email_text']);
        $send = $this->_ci->email->send();

        // if ($code != 'OTP') {
        //     $this->_ci->Alerts->user_mailalertfortrans($userid, $find['email_text'], $txnid, $event);
        // }
        return $send;
    }

      function UAC($code, $mobile, $email, $msg, $role, $userid, $pass, $event) {
        $data = array();
        $find = $this->_ci->Alerts->find_alert_type($code);
        if ($find) {
            //print_r($find);exit;
            if ($find['is_dynamic'] == 1) {
                if ($find['email_active'] == 1) {
                    if ($code == "UAC") {
                        $find['email_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['email_text']));
                        $find['email_text'] = str_replace('$MOBILE$', $mobile, $find['email_text']);
                        $find['email_text'] = str_replace('$ROLE$', $role, $find['email_text']);
                        $find['email_text'] = str_replace('$UID$', $userid, $find['email_text']);
                        $find['email_text'] = str_replace('$PASS$', $pass, $find['email_text']);
                        $em = $this->sendmail($find, $email, $userid, $event, $code, $msg);
                        $data['response_email'] = $em;


                    } else {

                        $find['email_text'] = str_replace('$' . $code . '$', $msg, $find['email_text']);
                        $find['email_text'] = str_replace('$MOBILE$', $mobile, $find['email_text']);
                        $find['email_text'] = str_replace('$ROLE$', $role, $find['email_text']);
                        $find['email_text'] = str_replace('$UID$', $userid, $find['email_text']);
                        $find['email_text'] = str_replace('$PASS$', $pass, $find['email_text']);
                        $em = $this->sendmail($find, $email, $userid, $event, $code, $msg);
                        $data['response_email'] = $em;
                    }
                }if ($find['sms_active'] == 1) {

                    if ($code == "UAC") {
                        $find['sms_text'] = str_replace('$EVENT$', $event, str_replace('$' . $code . '$', $msg, $find['sms_text']));
                        $find['sms_text'] = str_replace('$MOBILE$', $mobile, $find['sms_text']);
                        $find['sms_text'] = str_replace('$ROLE$', $role, $find['sms_text']);
                        $find['sms_text'] = str_replace('$UID$', $userid, $find['sms_text']);
                        $find['sms_text'] = str_replace('$PASS$', $pass, $find['sms_text']);
                     
                        $check = $this->sendmsg($find, $mobile, $userid, $event, $code, $msg);
                        $data['response_msg'] = $check;

                    } else {

                        $find['sms_text'] = str_replace('$' . $code . '$', $msg, $find['sms_text']);
                        $find['sms_text'] = str_replace('$MOBILE$', $mobile, $find['sms_text']);
                        $find['sms_text'] = str_replace('$ROLE$', $role, $find['sms_text']);
                        $find['sms_text'] = str_replace('$UID$', $userid, $find['sms_text']);
                        $find['sms_text'] = str_replace('$PASS$', $pass, $find['sms_text']);
                    
                        $check = $this->sendmsg($find, $mobile, $userid, $event, $code, $msg);
                        $data['response_msg'] = $check;
                    }
                }
            }
        } else {
            $data['error'] = 1;
            $data['error_desc'] = 'Invalid alert type';
            $data['msg'] = null;
        }
        return $data;
    }


     private function create_id() {
        $year = substr(date('Y'), -2);
        $letters = array_merge(range('A', 'Z'), range('A', 'Z'));
        // substr(md5($this->_ci->encrypt->encode($msg, $key),0,4))
        $x = 'TFS' . $year . date('mdHis') . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)] . $letters[mt_rand(0, 51)];
        return $x;
    }



}

?>
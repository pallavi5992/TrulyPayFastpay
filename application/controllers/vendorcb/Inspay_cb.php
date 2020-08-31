<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Inspay_cb extends CI_Controller
{
    function index()
    {
        $params=$_GET;
        $ip=ip_address();
        $this->load->model('Callback_Model');
        
        $requesturl = base_url() . $this->uri->uri_string();
        $requesturl = $_SERVER['QUERY_STRING'] ? $requesturl . "?" . $_SERVER['QUERY_STRING'] : $requesturl;
        
        $log_array=array('url'=>$requesturl,'callback_from'=>'INSTANTPAY','callback'=>json_encode($params,JSON_UNESCAPED_SLASHES),'ip'=>$ip,'datetime'=>date('Y-m-d H:i:s'));
        
        $insert_callbacklog= $this->Callback_Model->insert_vendor_callback($log_array);
         
        if($insert_callbacklog)
        {
        
        $log_inserid=$this->db->insert_id();    
            
        if($ip=="180.179.213.127" || $ip=="122.176.102.215")
        {
            
            if(isset($params['agent_id']))
            {
                
                $this->load->model('Inst_model');
                
                $get_txndata=$this->Callback_Model->get_txn_data($params['agent_id']);
                
                if($get_txndata)
                {
                    
                    if($get_txndata['servedby']==8)
                    {
                    
                    $params['res_code']=@$params['res_code'];
                    $params['res_msg']=@$params['res_msg'];
                    $params['ipay_id']=@$params['ipay_id'];
                    $params['opr_id']=@$params['opr_id'];
                    $params['status']=@$params['status'];
                    
                    
                    if($params['status']=='SUCCESS' || $params['status']=='REFUND')
                    {
                    
                    $error_mapping=$this->Inst_model->fetch_error_code($params['res_code'],$get_txndata['servedby']); 
                       
                    if($error_mapping)
                    {
                    
                    $error_mapping['error_code_desc']=($error_mapping['errorcode_id']==2)?$params['res_msg']:$error_mapping['error_code_desc'];
                           
                        
                    if($get_txndata['scode']=="CWS")
                    {
                        
                        if($get_txndata['status']=="PENDING")
                        {
                         
                            if($params['status']=='REFUND')
                                {
                                    $txnstatus='FAILED';
                                }
                            else{
                                    $txnstatus='SUCCESS';
                                }
                            
                            $update_txn_array=array(
                                                "sp_id" => $params['ipay_id'],
                                                "opr_ref_no" => $params['opr_id'],
                                                "sp_response" => $get_txndata['sp_response']."  Callback Received : ".json_encode($params,JSON_UNESCAPED_SLASHES),
                                                "res_dt" => date('Y-m-d H:i:s'),
                                                "ind_rcode" => $error_mapping['error_code'],
                                                "response" => $error_mapping['error_code_desc'],
                                                "status" => $txnstatus,
                                                "upd_id" => $get_txndata['id']
                                                );
                                 
                            if($params['status']=='REFUND')
                                {
                                $update_txn_array['op10']='CANCELLED';
                                }
                            
                            if($params['status']=='SUCCESS')
                                {
                                $update_txn_array['op10']='SUCCESS';
                                }
                                        
                            $updat_start=$this->Inst_model->update_aeps_transaction_bycallback($get_txndata,$update_txn_array);
                            
                            if($updat_start)
                            {
                                $data['error']=0;
                                $data['error_desc']=null;
                                $data['msg']='Update Done';
                            }else{
                                $data['error']=1;
                                $data['error_desc']='Something went wrong, while updating transaction';
                                $data['msg']=null;
                            }
                            
                            
                        }else{
                            $data['error']=1;
                            $data['error_desc']='Transaction cannot be updated, current status '.$get_txndata['status'];
                            $data['msg']=null;
                        }
                        
                        
                    }else{
                        if($get_txndata['status']=="PENDING" || $get_txndata['status']=="SUCCESS")
                        {
                            
                            
                            
                                if($params['status']=='REFUND')
                                {
                                    $txnstatus='FAILED';
                                    
                                }else{
                                    $txnstatus='SUCCESS';
                                }
                                
                                 $update_txn_array=array(
                                                "sp_id" => $params['ipay_id'],
                                                "opr_ref_no" => $params['opr_id'],
                                                "sp_response" => $get_txndata['sp_response']."  Callback Received : ".json_encode($params,JSON_UNESCAPED_SLASHES),
                                                "res_dt" => date('Y-m-d H:i:s'),
                                                "ind_rcode" => $error_mapping['error_code'],
                                                "response" => $error_mapping['error_code_desc'],
                                                "status" => $txnstatus,
                                                "upd_id" => $get_txndata['id']
                                        );
                                        
                                        
                                $updat_start=$this->Inst_model->update_service_transaction_bycallback($get_txndata,$update_txn_array);
                                
                                if($updat_start)
                                {
                                    $data['error']=0;
                                    $data['error_desc']=null;
                                    $data['msg']='Update Done';
                                }else{
                                    $data['error']=1;
                                    $data['error_desc']='Something went wrong, while updating transaction';
                                    $data['msg']=null;
                                }
                            
                        }else{
                            $data['error']=1;
                            $data['error_desc']='Transaction cannot be updated, current status '.$get_txndata['status'];
                            $data['msg']=null;
                        }
                    }
                        
                    }else{
                        $data['error']=1;
                        $data['error_desc']='Unmapped Response, cannot update txn';
                        $data['msg']=null;
                    }
                
                    }else{
                        $data['error']=1;
                        $data['error_desc']='Transaction cannot be updated, status not supported';
                        $data['msg']=null;
                    }    
                        
                    }else{
                        $data['error']=1;
                        $data['error_desc']='Vendor mismatch';
                        $data['msg']=null;
                    }
                    
                }
                else{
                    $data['error']=1;
                    $data['error_desc']='Unable to find transaction data';
                    $data['msg']=null;
                }
                
            }else{
                $data['error']=1;
                $data['error_desc']='Unable to find Fastpay Txnid';
                $data['msg']=null;
            }
            
        }else{
            $data['error']=1;
            $data['error_desc']='Invalid IP, Access Denied';
            $data['msg']=null;
        }
            
            
            $log_update_array=array(
            "reply_sent"=>json_encode($data,JSON_UNESCAPED_SLASHES),
            "reply_datetime"=>date('Y-m-d H:i:s')
            );
            
            $this->Callback_Model->update_vendor_callback($log_update_array,$log_inserid);
            echo json_encode($data);
        }else{
            $data['error']=1;
            $data['error_desc']='Unable to insert logs';
            $data['msg']=null;
            echo json_encode($data);
        }
        
    }
}
?>
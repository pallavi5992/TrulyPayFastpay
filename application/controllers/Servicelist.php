<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicelist extends CI_Controller 
{

    function index()
    {
        $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
        if($user_info)
        {

            if($user_info['is_block']==0)
            {

                 if(page_access(47))
                 { 
                        
                    $this->load->view('Dashboard/templates/header');
                    $this->load->view('Servicelist/servicelist_sidebar');
                    $this->load->view('Servicelist/listservices');
                
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
    
   function Commercials()
    {
        $e = $this->session->userdata('userid'); 
    $user_info=$this->Main_model->user_acntid($e);
        if($user_info)
        {

            if($user_info['is_block']==0)
            {

                 if(page_access(48))
                 { 
                        
                    $this->load->view('Dashboard/templates/header');
                    $this->load->view('Servicelist/servicelist_sidebar');
                    $this->load->view('Servicelist/listservices');
                
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
    
    function get_my_commercials()
    {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') 
         {
            $e = $this->session->userdata('userid');
            $user_info = $this->Main_model->user_acntid($e);
            $data = array();
            if ($user_info) 
            {
                 if ($user_info['is_block'] == 0) 
                 {
                     if(page_access(48))
                     { 
                         
                         $plan_id=$user_info['plan_id'];
                         
                         if($plan_id)
                         {
                             
                             $get_all_services_for_plan=$this->Main_model->get_all_services_inplan($plan_id);
                             
                             $plan_data=array();
                             
                             if($get_all_services_for_plan)
                             {
                                 
                                 foreach($get_all_services_for_plan as $sk=>$sv)
                                 {
                                     $service_chargetype=$sv['charge_type'];
                                     $service_chargemethod=$sv['charge_method'];
                                     $service_capping=$sv['capping_amount'];
                                     $service_rate=$sv['rate'];
                                     
                                     $commercials="";
                                     
                                     $plan_array=array(
                                     "servicename"=>$sv['service_name'],
                                     "servicetype"=>$sv['type'],
                                     "slabapplicable"=>$sv['slab_applicable']
                                     );
                                     
                                     if($sv['slab_applicable']==1)
                                     {
                                         
                                         $find_service_slabrates=$this->Main_model->PlanSlabApplicableList($sv['service_id'],$sv['plan_id']);
                                         if($find_service_slabrates)
                                         {
                                             
                                             foreach($find_service_slabrates as $slk=>$slv)
                                             {
                                                 
                                                 if($slv['charge_type']=="FIXED")
                                                 {
                                                     if($slv['charge_method']=="CREDIT")
                                                     {
                                                         $plan_desc='Commission of &#8377; '.$slv['rate'].' per transaction.';
                                                     }
                                                     else if($slv['charge_method']=="DEBIT")
                                                     {
                                                         $plan_desc='Surcharge of &#8377; '.$slv['rate'].' per transaction.';
                                                     }
                                                     else{
                                                         $plan_desc='Margin Configuration Issue.';
                                                     }
                                                 }
                                                 else if($slv['charge_type']=="PERCENTAGE")
                                                 {
                                                     if($slv['charge_method']=="CREDIT")
                                                     {
                                                         
                                                         $plan_desc='Commission of '.$slv['rate'].'%';
                                                         if(is_numeric($service_capping))
                                                         {
                                                             $plan_desc.=' or &#8377; '.$service_capping.' whichever is lower.';
                                                             
                                                         }else{
                                                             $plan_desc.='.';
                                                         }
                                                         
                                                     }
                                                     else if($slv['charge_method']=="DEBIT")
                                                     {
                                                         $plan_desc='Surcharge of '.$slv['rate'].'%';
                                                         if(is_numeric($service_capping))
                                                         {
                                                             $plan_desc.=' or &#8377; '.$service_capping.' whichever is higher.';
                                                             
                                                         }else{
                                                             $plan_desc.='.';
                                                         }
                                                     }
                                                     else{
                                                         $plan_desc='Margin Configuration Issue.';
                                                     }
                                                 }
                                                 else{
                                                     $plan_desc='Margin Configuration Issue.';
                                                 }
                                                 
                                                 $plan_array['slabdata'][]=array(
                                                     'min_amount'=>$slv['min_amnt'],
                                                     'max_amount'=>$slv['max_amnt'],
                                                     'plan_desc'=>$plan_desc
                                                 );
                                                 
                                             }
                                             
                                         }else{
                                             $plan_array['slabdata']=array();
                                         }
                                         
                                         
                                     }else{
                                         
                                         $plan_array['slabdata']=array();
                                         
                                         if($service_chargetype=="FIXED")
                                         {
                                             if($service_chargemethod=="CREDIT")
                                             {
                                                 $commercials='Commission of &#8377; '.$service_rate.' per transaction.';
                                             }
                                             else if($service_chargemethod=="DEBIT")
                                             {
                                                 $commercials='Surcharge of &#8377; '.$service_rate.' per transaction.';
                                             }
                                             else{
                                                 $commercials='Margin Configuration Issue.';
                                             }
                                         }
                                         else if($service_chargetype=="PERCENTAGE")
                                         {
                                             if($service_chargemethod=="CREDIT")
                                             {
                                                 $commercials='Commission of '.$service_rate.'%';
                                                 
                                                 if(is_numeric($service_capping))
                                                 {
                                                     $commercials.=' or &#8377; '.$service_capping.' whichever is lower.';
                                                     
                                                 }else{
                                                     $commercials.=".";
                                                 }
                                             }
                                             else if($service_chargemethod=="DEBIT")
                                             {
                                                 $commercials='Surcharge of '.$service_rate.'%';
                                                 
                                                 if(is_numeric($service_capping))
                                                 {
                                                     $commercials.=' or &#8377; '.$service_capping.' whichever is higher.';
                                                     
                                                 }else{
                                                     $commercials.=".";
                                                 }
                                             }
                                             else{
                                                 $commercials='Margin Configuration Issue.';
                                             }
                                         }
                                         else{
                                             $commercials='Margin Configuration Issue.';
                                         }
                                         
                                         
                                     }
                                     
                                     $plan_array['commercials']=$commercials;
                                     $plan_data[]=$plan_array;
                                     
                                     
                                 }
                                 
                             }
                             
                             $data['error_data']=0;
                             $data['error_desc']=null;
                             $data['msg']='Request Completed Successfully';
                             $data['data']=$plan_data;
                             
                             
                         }else{
                             $data['error_data'] = 1;
                             $data['error_desc'] = 'No Plan Exist';
                             $data['msg'] = NULL;
                             $data['data']=array();
                         }
                         
                         
                      }else{
                         $data['error_data'] = 1;
                         $data['error_desc'] = 'Unauthorised access';
                         $data['msg'] = NULL;
                         $data['data']=array();
                     }
                     
                 } else {
                    $data['error_data'] = 2;
                    $data['error_desc'] = 'Access Denied';
                    $data['msg'] = NULL;
                    $data['data']=array();
                    $this->session->sess_destroy();
                }
                
            } else {
                $data['error_data'] = 2;
                $data['error_desc'] = 'Invalid Request';
                $data['msg'] = NULL;
                $data['data']=array();
                $this->session->sess_destroy();
            }
            echo json_encode($data);
             
        } else {
            redirect('Dashboard');
        } 
    }
    
    
   
}


?>
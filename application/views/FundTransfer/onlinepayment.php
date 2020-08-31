<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
    $user_id =$this->session->userdata('userid');
	$role_id =$this->session->userdata('role_id');
    $parent=$this->uri->segment(1);
	$child=$this->uri->segment(2);
	$parent_check = str_replace('_', '', $parent);
    $child_check = str_replace('_', '', $child);
	$user_data=get_user_details();
	if(!$user_id){

		redirect('Login');
	 }

?>

<style>
    .row .mt-3{
    	margin-bottom: 1rem;
    }
    transaction_history_custom .dataTables_length {
        float:left;
        width: calc(100% - 66px);
    }

    .transaction_history_custom .dt-buttons {
        float:right;
        margin-top:16px;
    }


    .transaction_history_custom #printtd{
        float: right;
        margin-top: 17px
    }

    .transaction_history_custom .buttons-csv{
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
    }

    button#printtd {
        float: right;
        margin-right: 9px;
    }
</style>
								



    <div class="col-lg-9">
        <div class="tab-content">
            <h4 style="color:red;">Waiting for client response- which PG will be implement here</h4>
            
        </div>
    </div>


	<!--end of section-->
</div>

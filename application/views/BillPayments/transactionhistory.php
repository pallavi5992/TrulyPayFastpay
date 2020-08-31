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
transaction_history_custom .dataTables_length
{
float:left;
width: calc(100% - 66px);
}

.transaction_history_custom .dt-buttons
{
float:right;
margin-top:16px;
}


.transaction_history_custom #printtd
{
float: right;
margin-top: 17px
}

.transaction_history_custom .buttons-csv
{
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
								<div class="transaction_history_custom">
								<?php if ($user_data['role_id'] == 4) { ?>
                                <!-- <table  class="table  table-bordered" id="transactiontable">-->
								<table class="table font14 font-medium light-txt datatables table-responsive" id="transactiontable">  
								<thead  class="thead-blue">
								</thead>
                                </table>
                            <?php } else if ($user_data['role_id'] == 2 || $user_data['role_id'] == 3 || $user_data['role_id'] == 1) { ?>
                               <!-- <table  class="table  table-bordered" id="transactiontable_dist">-->
								<table class="table font14 font-medium light-txt datatables table-responsive" id="transactiontable_dist">
								<thead  class="thead-blue">
								</thead>
                                </table>      
                            <?php } ?>
							</div>
						   </div>
						</div>
						
						
						
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--end of section-->

</div>
<!--end of wrapper-->

<script type="text/javascript">
	 $(document).ready(function(){
	// 	$(".nav-set .nav-item").click(function(){
	// 	$(".nav-set .nav-item").removeClass('active');	
	// 	$(this).addClass('active');
	// 	});

	// 	$(".active-status-btn").click(function(){
	// 		$(this).toggleClass('deactive');
	// 		$(this).toggleClass('active');
	// 		$(this).innerHtml = 'dfdf';
	// 	});

	// 	$(".confirm-btn").click(function(){

 //         $("#payment-status").show();
 //         $('#confirm-screen').hide();
	// 	});

	// 	$(".submit-btn").click(function(){
	// 	 $('#confirm-screen').show();
	// 	 $('#dth-recharge').hide();
	// 	});

	// 	$(".back-btn").click(function(){
	// 	$('#dth-recharge').show();		
	// 	$('#confirm-screen').hide();
	// 	 $("#payment-status").hide();
	// 	});

	// 	$('#from').datepicker();
	// 	$('#to').datepicker();

		// $(".show-transaction-mobilerecharge").click(function(){
		// $('#transaction-history').toggle();
		// });

 //       $("#transaction-table").DataTable({
 //       	 dom: "<'row pt-15 pb-10'<'col-sm-12 col-md-12 text-right'  l> ><'row'<'col-sm-12 table-responsive'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
 //       	"oLanguage": {

	// 		"sSearchPlaceholder": "Enter Account Number:",
	// 		"sSearch": ""
	// 	}
 //       });
	 })
</script>

<?php if ($user_data['role_id'] == 4) { ?> 
<script src="assets/script/billpayment_txn_re.js?v=1.3" type="text/javascript"></script>

<?php } else if ($user_data['role_id'] == 1) { ?>
    <script src="assets/script/billpayment_txn_admin.js?v=1" type="text/javascript"></script>
<?php } else if ($user_data['role_id'] == 2 || $user_data['role_id'] == 3) { ?>
  <script src="assets/script/billpayment_txn_dist.js?v=1.3" type="text/javascript"></script>
<?php } ?>
</body>
</html>
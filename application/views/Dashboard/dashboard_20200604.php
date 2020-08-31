<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
$user_email = $this->session->userdata('userid');
$user_data = get_user_details();
$user_prnt_dtl=get_user_parent_dtl();
$user_due_payment=get_user_due_pymnts();





if (!$user_email) {

    redirect('Login');
}

     
?> 
<style>
	span.help-block {
    color: #D84315!important;
    font-size: 80%;
}


</style>  
	
<!--start of section-->
	<section class="dashboard_overview width-100 make_relative mt-30">
		<div class="container">
			<div class="row">
			<?php if($user_data['role_id']==2 || $user_data['role_id']==3 || $user_data['role_id']==4){?>
				<div class="colset col-md col-sm-12">
					<div class="dashboard-inner-col">
						<div class="dashboard-inner-col-left">
							<div class="lbl">Today Transaction</div>
							<div class="val">
								<?php

									$a=get_user_tdy_txn_amnt('today');
									echo isset($a['amnt']) ? $a['amnt'] : "0";
                                ?>
                            </div>
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/transaction.svg" width="40">
						</div>
					</div>
				</div>
				<?php }?>
					<?php if($user_data['role_id']==2 || $user_data['role_id']==3 || $user_data['role_id']==4){?>
				<div class="col-md colset col-sm-12">
					<div class="dashboard-inner-col">
						<div class="dashboard-inner-col-left">
							<div class="lbl">Month Till Date Transaction</div>
							<div class="val">
                                <?php
									$a=get_user_tdy_txn_amnt('cur_month');
									echo isset($a['amnt']) ? $a['amnt'] : "0";
                                ?>
							</div>
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/transaction.svg" width="40">
						</div>
					</div>
				</div>
				
				<?php }?>


				<div class="col-md colset col-sm-12">
					<div class="dashboard-inner-col">
						<div class="dashboard-inner-col-left">
							<div class="lbl">Last Month Transaction</div>
							<div class="val">
								<?php
									$a=get_user_tdy_txn_amnt('pre_month');
									echo isset($a['amnt']) ? $a['amnt'] : "0";
                                ?>
							</div>
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/transaction.svg" width="40">
						</div>
					</div>
				</div>

				<div class="col-md colset col-sm-12">
					<div class="dashboard-inner-col">
						<div class="dashboard-inner-col-left">
							<div class="lbl">Todays Load</div>
							<div class="val">10,000</div>
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/wallet-icon.svg" width="25">
						</div>
					</div>
				</div>
				<?php if($user_data['role_id']==2 || $user_data['role_id']==3 || $user_data['role_id']==4){?>
				<div class="col-md  colset col-sm-12">
					<div class="dashboard-inner-col">
						<div class="dashboard-inner-col-left">
							<div class="lbl">Outstanding Balance</div>
							<div class="val">
										<?php
                                        $user_due_payment['amnt'] = isset($user_due_payment['amnt']) ? $user_due_payment['amnt'] : "0";
                                        echo $user_due_payment['amnt'];
                                        ?></div>
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/wallet-icon.svg" width="25">
						</div>
					</div>
				</div>
				<?php }?>

			</div>
		</div>
		
	</section>
	<!--end of section-->

	<!--start of banner notification banner-->
	<section class="width-100 notification-banner mt-30">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
						<div id="notifications" class="carousel slide" data-ride="carousel">
						  <div class="carousel-inner">
							    <div class="carousel-item active">
							      <img class="d-block w-100" src="assets/images/banner-1.png" alt="First slide">
							    </div>
							    <div class="carousel-item">
							      <img class="d-block w-100" src="assets/images/banner-2.png" alt="Second slide">
							    </div>
							    
						  </div>
						  <a class="carousel-control-prev" href="#notifications" role="button" data-slide="prev">
						    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
						    <span class="sr-only">Previous</span>
						  </a>
						  <a class="carousel-control-next" href="#notifications" role="button" data-slide="next">
						    <span class="carousel-control-next-icon" aria-hidden="true"></span>
						    <span class="sr-only">Next</span>
						  </a>
						</div>	
				</div>

				<div class="col-md-4">
					<div class="banner-right-section">
						<a href="Manage/PaymentRequest">
						<div class="fund-transfer-link">
							Load request
						</div>
					    </a>

					    <ul class="detail_list">
					    	<li class="mb-10">
					    		<div class="lbl">Company Support Number</div>
					    		<div class="vl">9717822345</div>
					    	</li>

					    	<li>
					    		<div class="lbl">Company Support Email</div>
					    		<div class="vl">fastpaysupport@mail.com</div>
					    	</li>
					    </ul>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!--end of notification banner-->

	<section class="width-100 mt-30">
		<div class="container">
			<div class="row">
				<div class="col-12">
					 <div class="owl-carousel owl-theme">
					 	<div class="item">
			              <h4>Test Notification 1</h4>
			              <p>This is Notification Text</p>
			            </div>
			            <div class="item">
			              <h4>Test Notification 2</h4>
			              <p>This is Notification Text</p>
			            </div>
			            <div class="item">
			              <h4>Test Notification 3</h4>
			              <p>This is Notification Text</p>
			            </div>
			            <div class="item">
			              <h4>Test Notification 4</h4>
			              <p>This is Notification Text</p>
			            </div>
					 </div>
				</div>
			</div>
		</div>
	</section>



</div>
<!--end of wrapper-->



<script type="text/javascript">
	$(document).ready(function(){
		$(".nav-set .nav-item").click(function(){
		$(".nav-set .nav-item").removeClass('active');	
		$(this).addClass('active');
		});

		$(".active-status-btn").click(function(){
			$(this).toggleClass('deactive');
			$(this).toggleClass('active');
			$(this).innerHtml = 'dfdf';
		});

		 var owl = $('.owl-carousel');
              owl.owlCarousel({
                margin: 10,
                nav: false,
                loop: true,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 2
                  },
                  1000: {
                    items: 3
                  }
                }
              })  

	})
</script>
</body>
</html>
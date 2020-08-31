<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
$user_email = $this->session->userdata('userid');
$user_data = get_user_details();
$user_prnt_dtl=get_user_parent_dtl();
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
	<section class="account_overview width-100 make_relative mt-30">
		<div class="container">
			<div class="row">
				<div class="width-100 section-top-heading mb-3"><h4 class="dark-txt fontbold">Account Overview</h4></div>

				<div class="col-lg-9">
					<div class="account_overview_outer">
						<div class="account_overview_col-1 white-bg pl-15 pr-15 width-100">
							<div class="account_overview_col-1_topsection pt-15 pb-15 width-100">
								<div class="float-left mr-3 account_profileimg">
									<img src="assets/images/account-img.jpeg">
								</div>

								<div class="float-left mt-15">
									<div class="black-txt font18 font-medium"><?php echo$user_data['fname'].' '.$user_data['lname'];?></div>
									<div class="light-txt"><?php echo$user_data['role_name'];?></div>
								</div>
							</div>

							<div class="width-100">
								<div class="row">
										<div class="col-lg-6 bord-right-dashed sm-bord-bottom">
											<div class="inner-col width-100 pt-15 pb-15">
											<ul class="unstyled-list list-set">
												<li><span class="float-left mr-3"></span> <span class="float-left font-medium">User ID</span> <span class="float-right light-txt"><?php echo $user_data['ac'];?></span></li>
												<li><span class="float-left mr-3"></span> <span class="float-left font-medium">Company Name</span> <span class="float-right light-txt"><?php echo $user_data['business_name'];?></span></li>
												<li><span class="float-left mr-3"></span> <span class="float-left font-medium">Registered Email</span> <span class="float-right light-txt"><?php echo$user_data['email_id'];?></span></li>
												<li><span class="float-left mr-3"></span> <span class="float-left font-medium">Registered Mobile No.</span> <span class="float-right light-txt"><?php echo$user_data['mobile'];?></span></li>
											</ul>
										    </div>
										</div>
										<div class="col-lg-6">
											<div class="inner-col width-100 pt-15 pb-15">
												<ul class="unstyled-list list-set">
													<li><span class="float-left mr-3"></span> <span class="float-left fontbold">Company support number</span> <span class="float-right light-txt">971682234</span></li>
													<?php if($user_data['parent_id']!='0'){ ?>
													<li><span class="float-left mr-3"></span> <span class="float-left fontbold">Your <?php echo$user_prnt_dtl['role_name'];?></span> <span class="float-right light-txt"><?php echo$user_prnt_dtl['mobile'];?></span></li>
												<?php }?>
												</ul>
										    </div>	
										</div>
							   </div>
							</div>
						 </div>

						 <div class="nav nav-tabs width-100 nav-tabs-set">

						 	<a class="nav-item nav-link active" data-toggle="tab" href="#edit-profile">Profile DETAILS</a>
						 	<a class="nav-item nav-link" data-toggle="tab" href="#settings">Settings</a>
						 	<a class="nav-item nav-link" data-toggle="tab" href="#downloads">Downloads</a>
						 </div>

						 <div class="tab-content width-100 white-bg pl-15 pr-15 pt-15 pb-15">
						 	<div class="tab-pane fade show active" id="edit-profile">
						 		<div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold">PROFILE DETAILS</h6></div>
						 		<div class="form-container">
						 			<form>
						 				<div class="row row-divider">
						 					<div class="col-lg-4 input-col pt-10 pb-10 md-bord-bottom">
						 						<label class="light-txt font-medium">COMPANY NAME</label>
						 						<input type="text" class="input-set" placeholder="<?php $user_data['business_name'] = isset($user_data['business_name']) ? $user_data['business_name'] : "";
                                                                        echo $user_data['business_name']; ?>" disabled/>
						 					</div>

						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">CONTACT PERSON</label>
						 						<input type="text" class="input-set" placeholder="<?php $user_data['mobile'] = isset($user_data['mobile']) ? $user_data['mobile'] : "";
                                                                        echo $user_data['mobile']; ?>" disabled/>
						 					</div>

						 					<div class="col-lg-4 pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">STATE</label>
						 						<input type="text" class="input-set" placeholder="<?php $user_data['business_state'] = isset($user_data['business_state']) ? $user_data['business_state'] : "";
                                                                        echo $user_data['business_state']; ?>" disabled/>
						 					</div>
						 				</div>

						 				<div class="row row-divider">
						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">MOBILE NO</label>
						 						<input type="text" class="input-set" placeholder="<?php $user_data['mobile'] = isset($user_data['mobile']) ? $user_data['mobile'] : "";
                                                                        echo $user_data['mobile']; ?>" disabled/>
						 					</div>

						 					<div class="col-lg-8 pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">ADDRESS</label>
						 						<input type="text" class="input-set" placeholder="<?php $user_data['business_addr'] = isset($user_data['business_addr']) ? $user_data['business_addr'] : "";
                                                                        echo $user_data['business_addr']; ?>" disabled/>
						 					</div>
						 				</div>


						 				<div class="row row-divider">
						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">EMAIL ADDRESS</label>
						 						<input type="Email" class="input-set"  placeholder="<?php $user_data['email_id'] = isset($user_data['email_id']) ? $user_data['email_id'] : "";
                                                                        echo $user_data['email_id']; ?>" disabled/>
						 					</div> 

						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">PAN CARD</label>
						 						<input type="text" class="input-set" placeholder="<?php $user_data['pan_num'] = isset($user_data['pan_num']) ? $user_data['pan_num'] : "";
                                                                        echo $user_data['pan_num']; ?>" disabled/>
						 					</div>

						 					<div class="col-lg-4 pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">AADHAR NUMBER</label>
						 						<input type="text" class="input-set" placeholder="<?php $user_data['aadhar'] = isset($user_data['aadhar']) ? $user_data['aadhar'] : "";
                                                                        echo $user_data['aadhar']; ?>" disabled/>
						 					</div>
						 				</div>


						 				<div class="row row-divider">
						 					<div class="col-lg-4 input-col pt-10 pb-10 md-bord-bottom">
						 						<label class="light-txt font-medium">GSTIN NUMBER</label>
						 						<input type="Email" class="input-set" placeholder="<?php $user_data['gstin'] = isset($user_data['gstin']) ? $user_data['gstin'] : "";
                                                                        echo $user_data['gstin']; ?>" disabled/>
						 					</div> 

						 					<div class="col-lg-4 input-col pt-10 pb-10 md-bord-bottom">
						 						<label class="light-txt font-medium">CITY</label>
						 						<input type="text" class="input-set"  value="<?php $user_data['business_city'] = isset($user_data['business_city']) ? $user_data['business_city'] : "";
                                                                        echo $user_data['business_city']; ?>" disabled/>
						 					</div>

						 					<div class="col-lg-4 pt-10 pb-10 md-bord-bottom">
						 						<label class="light-txt font-medium">PINCODE</label>
						 						<input type="text" class="input-set" placeholder="" value="<?php $user_data['business_pincode'] = isset($user_data['business_pincode']) ? $user_data['business_pincode'] : "";
                                                                        echo $user_data['business_pincode']; ?>" disabled/>
						 					</div>
						 				</div>

						 			
						 			</form>
						 		</div>
						 	</div>
					
						 	<div class="tab-pane fade" id="settings">
						 		<div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold">Settings</h6></div>
						 		<div class="gray-header mb-10">Change Password</div>
						 		   <div class="form-container">
						 			<form id="change-password-form">
						 				<div class="row row-divider">
						 					<div class="col-lg-4 input-col pt-10 pb-10 md-bord-bottom">
						 						<label class="light-txt font-medium">OLD PASSWORD</label>
						 						<input type="password" class="input-set" placeholder="Enter Old Password" id="crnt_pass" name="crnt_pass">
						 					</div>

						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">NEW PASSWORD</label>
						 						<input type="password" class="input-set" placeholder="Enter New Password" id="new_pass" name="new_pass">
						 					</div>

						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">CONFIRM PASSWORD</label>
						 						<input type="password" class="input-set" placeholder="Re-Enter New Password" id="cnfrm_pass" name="cnfrm_pass">
						 					</div>
						 				</div>
						 				<div class="row pl-15 pr-15 pt-15 pb-15">
						 					<button class="btn blue-btn mr-2" id="updt_pswd">SUBMIT</button>
						 					
						 				</div>
						 			</form>
						 		</div>

								<div class="gray-header mb-10">Change MPIN </div>
						 		   <div class="form-container">
						 			<form id="change-mpin-form">
						 				<div class="row row-divider">
						 					<div class="col-lg-4 input-col pt-10 pb-10 md-bord-bottom">
						 						<label class="light-txt font-medium">OLD MPIN</label>
						 						<input type="password" class="input-set" placeholder="Enter Old MPIN" id="crnt_mpin" name="crnt_mpin">
						 					</div>

						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">NEW MPIN</label>
						 						<input type="password" class="input-set" placeholder="Enter New MPIN" id="new_mpin" name="new_mpin">
						 					</div>

						 					<div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
						 						<label class="light-txt font-medium">CONFIRM MPIN</label>
						 						<input type="password" class="input-set" placeholder="Re-Enter New MPIN" id="cnfrm_mpin" name="cnfrm_mpin">
						 					</div>
						 				</div>
						 				<div class="row pl-15 pr-15 pt-15 pb-15">
						 					<button class="btn blue-btn mr-2" id="updt_mpin">SUBMIT</button>
						 					
						 				</div>
						 			</form>
						 		</div>

						 	</div>
						 
						 	<div class="tab-pane fade" id="downloads">Downloads</div>

						 </div>


					</div>	 
				</div>
				<div class="col-lg-3">
					<div class="balance-display-card width-100 mb-10 white-bg">
						<div class="balance-display-card-col-1">
							<img src="assets/images/wallet.svg" style="width: 40px;">
						</div>

						<div class="balance-display-card-content text-center">
							<div class="fontbold black-txt font16">Wallet</div>
							<div class="fontbold light-txt font16"><?php
                                        $user_data['rupee_balance'] = isset($user_data['rupee_balance']) ? $user_data['rupee_balance'] : "";
                                        echo $user_data['rupee_balance'];
                                        ?></div>
					    </div>
					</div>

					<div class="balance-display-card width-100 mb-10 white-bg">
						<div class="balance-display-card-col-1">
							<img src="assets/images/wallet.svg" style="width: 40px;">
						</div>

						<div class="balance-display-card-content text-center">
							<div class="fontbold black-txt font14">Outstanding Balance</div>
							<div class="fontbold light-txt font14">3,400</div>
					    </div>
					</div>
					<?php //if($user_data['role_id']==2 || $user_data['role_id']==3 || $user_data['role_id']==4){?>
					<!-- <div class="balance-display-card width-100 mb-10 white-bg">
						<div class="balance-display-card-col-1">
							<img src="assets/images/wallet.svg" style="width: 40px;">
						</div>

						<div class="balance-display-card-content text-center">
							<a  href="Manage/PaymentRequest">
						 	<span class="fontbold black-txt font14" >Payment Request</span>
                            </a>
							
					    </div>
					</div> -->
<?php //}?>

				</div>
			</div>
		</div>
		
	</section>
	<!--end of section-->

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



    
	$.validator.addMethod("exactlength", function (value, element, param) {

        return this.optional(element) || value.length == param;

    },

    $.validator.format("Enter exactly {0} digits."));
    $('#change-password-form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class

        rules: {
            crnt_pass: {
                required: true,
            },
            new_pass: {
                required: true,
                minlength: 6
            },
            cnfrm_pass: {
                required: true,
                equalTo: "#new_pass",
            }
        },
        messages: {
            crnt_pass: {
                required: 'Current password is required',
            },
            new_pass: {
                required: 'New password is required',
                minlength: "Your password must be at least {0} characters."
            },
            cnfrm_pass: {
                required: 'Confirm password is required',
                equalTo: 'Password mismatched'
            }
        },
        highlight: function (element) { // hightlight error inputs

            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
        },
        success: function (label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },
        submitHandler: function (form) {

            $('#updt_pswd').addClass('ladda-button').attr('data-style', 'zoom-in');
            $('#updt_pswd').attr('data-spinner-color', '#000000');
            var la = $('#updt_pswd').ladda();
            la.ladda('start');    

            //console.log($(form).serialize());

            $.post('MyAccount/change_user_pswd', $(form).serialize(), function (response)
            {

                if (response)
                {
                    if (response.error == 1)
                    {
                        toastr.error(response.error_desc);


                    } else if (response.error == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error == 0) {
                        $("#change-password-form")[0].reset();
                        toastr.info(response.msg);

                    }
                    la.ladda('stop');

                }

            }, 'json').fail(function (error) {
                la.ladda('stop');
                //throw error;
            });
            la.ladda('stop');
            return false;
        }
    });

	  $('#change-mpin-form').validate({    
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class

        rules: {
            crnt_mpin: {
                required: true,
            },
            new_mpin: {
                required: true,
                maxlength: 4
            },
            cnfrm_mpin: {
                required: true,
                equalTo: "#new_mpin",
            }
        },
        messages: {
            crnt_mpin: {
                required: 'Current MPIN is required',
            },
            new_mpin: {
                required: 'New MPIN is required',
                maxlength: "Your MPIN must be at least {0} characters."
            },
            cnfrm_mpin: {
                required: 'Confirm MPIN is required',
                equalTo: 'MPIN mismatched'
            }
        },
        highlight: function (element) { // hightlight error inputs

            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
        },
        success: function (label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },
        submitHandler: function (form) {

            $('#updt_mpin').addClass('ladda-button').attr('data-style', 'zoom-in');
            $('#updt_mpin').attr('data-spinner-color', '#000000');
            var la = $('#updt_mpin').ladda();
            la.ladda('start');    

            //console.log($(form).serialize());

            $.post('MyAccount/change_user_mpin', $(form).serialize(), function (response)
            {

                if (response)
                {
                    if (response.error == 1)
                    {
                        toastr.error(response.error_desc);


                    } else if (response.error == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error == 0) {
                        $("#change-mpin-form")[0].reset();
                        toastr.info(response.msg);

                    }
                    la.ladda('stop');

                }

            }, 'json').fail(function (error) {
                la.ladda('stop');
                //throw error;
            });
            la.ladda('stop');
            return false;
        }
    });

    	})


</script>
</body>
</html>
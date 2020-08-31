<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$user_data = get_user_details();
$outletStatus = isset($outlet['kyc_apibox'])?$outlet['kyc_apibox']:'';

$ver = date('YmdHis');

?> 
 <style>
    .operator-logo {
	    position: absolute;
	    right: 0px;
	    z-index: 10;
	    top: 5px;
	    right: 28px;
	}
	.confirm-btn {
		margin-right: 5px;
	} 
 </style>

 
        <div class="col-lg-9">
			<div class="tab-content">
					

				<div id="mobile-recharge" class="width-100 tab-pane fade active show">
					<div class="width-100 section-top-subheading mb-3">
						<h6 class="dark-txt fontbold float-left">REMITTER LOGIN</h6>
					</div>
						   
						   
	                <form id="Login" class="recharge-form-outer">
	              						
						<div class="form-group form-group-divder-set row ml-0 mr-0">
							<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">SENDER MOBILE NUMBER</label>
							<div class="col-lg-7 pl-0 pr-0">
								<input type="tel" name="" class="form-control no-brd dark-txt"  placeholder="Enter Mobile Number" id="mobile_input">
								<span data-for="mobile_input"></span>
							</div>
					    </div>
						
						<div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">
							<a class="btn btn-primary mr-2 submit-btn white-txt" id="check_remi">Proceed</a>						
						</div>
	                </form>
				               
	                <form id="register" style="display:none"  class="recharge-form-outer">
	                
					<div class="form-group form-group-divder-set row ml-0 mr-0">
						<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">MOBILE NUMBER</label>
						<div class="col-lg-7 pl-0 pr-0">
						<input type="tel" name="" class="form-control no-brd dark-txt"  placeholder="Enter Mobile Number" id="new_mobile">
						<span data-for="new_mobile"></span>
						</div>
				    </div>
					
					<div class="form-group form-group-divder-set row ml-0 mr-0">
						<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">NAME</label>
						<div class="col-lg-7 pl-0 pr-0">
						<input type="text" name="" class="form-control no-brd dark-txt"  placeholder="Enter Your Name" id="new_name">
						<span data-for="new_name"></span>
						</div>
				    </div>
	              
	                <div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">
						<button type="submit" class="btn btn-primary legitRipple" id="regis_user">Proceed</button>
		                <button type="button" id="btl" class="btn btn-circle grey-salsa btn-outline">Back</button>
					</div>
	                </form>
	                <form id="register_otp_panel" style="display:none" class="recharge-form-outer">
						
				    <div class="form-group form-group-divder-set row ml-0 mr-0">
						<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">OTP</label>
						<div class="col-lg-7 pl-0 pr-0">
						<input type="tel" name="" class="form-control no-brd dark-txt"  placeholder="Enter OTP" maxlength="6" id="regis_otp">
						<span data-for="regis_otp"></span>
						</div>
				    </div>
					
					<div class="form-actions noborder pull-left">
						<!-- <button id="resendRegisterOTP" class="btn btn-link btn-default">Resend OTP ?</button> -->
					</div>
					<div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">
					 	<button type="button" class="btn btn-primary legitRipple" id="registerOTP">Verify &amp; Register</button>&nbsp;
	                	<a class="btn btn-dark white-txt back-btn" id="tr-bk"><span class="btn-side-icon white-bg"><img src="assets/images/left-arrow.svg" width="12"></span>Back</a>
					</div>
					</form>
			    </div>
			

			<!-- start instntpy_afterlgn  -->
			<section class="width-100 money-remmitance-section hide"  id="instntpy_afterlgn">
		
				<div class="container">
					<div class="row">     
						<div class="col-12">
							<div class="money-remmitance-section-outer-col width-100">
								<div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold">SUMMARY</h6><a class="btn btn-primary mr-2 submit-btn white-txt" id="user_logout_now">Sender Change</a></div> 
									
								<div class="width-100 pl-15 pr-15">
									<div class="summary-list"> 
									<div class="row">
											<div class="col-lg-3 md-mb-10">
												<div class="float-left blue-icon-box-set mr-3">
													<img src="assets/images/wallet.svg" width="30">
												</div>

												<div class="float-left">
													<div class="fontbold font16 dark-txt">Available Limit</div>
													<div class="fontbold font16 light-txt" id="available_limit">0</div>
												</div>
											</div>
		  
											<div class="col-lg-3 md-mb-10">
											<div class="float-left blue-icon-box-set mr-3">
												<img src="assets/images/wallet.svg" width="30">
											</div>

											<div class="float-left">
												<div class="fontbold font16 dark-txt">Total Allowed Limit</div>
												<div class="fontbold font16 light-txt" id="total_allowed_limit">0</div>
											</div>
										  </div>


										<div class="col-lg-3 md-mb-10">
											<div class="float-left blue-icon-box-set mr-3">
												<img src="assets/images/phone-white-icon.svg" width="30">
											</div>

											<div class="float-left">
												<div class="fontbold font16 dark-txt">Mobile Number</div>
												<div class="fontbold font16 light-txt" id="mobile_no">xxxx</div>
											</div>
										 </div>



											<div class="col-lg-3 md-mb-10">
											<div class="float-left blue-icon-box-set mr-3">
												<img src="assets/images/user.png" width="25">
											</div>

											<div class="float-left">
												<div class="fontbold font16 dark-txt">Sender Name</div>
												<div class="fontbold font16 light-txt" id="sender_name">xxxx</div>
											</div>
										  </div>
									</div>	
								   </div>
								</div>

								<div class="width-100 pl-15 pr-15 mt-20">
									<div class="beneficiary-list">
									    <div class="gray-header">Beneficiary List</div>
										<div class="beneficiary-list-main">
											<div class="width-100 pt-15 pb-15">
												<div class="float-right">
												     <button class="btn btn-dark mr-2 float-left" id="add-new-benef-btn"><span class="mr-2"><img src="assets/images/add-user.svg" width="20"></span>Add New Beneficiary</button>
												
												 </div>
											</div>

											<div class="">
												<table class="table datatables table font14 font-medium light-txt data" id="benif-table">
												<thead class="thead-blue">
													
												</thead>
											</table>
											
											</div>
										</div>
									</div>
								</div>


							</div>
						</div>
					</div>
				</div>
		
		
		
			</section>
			<!-- end instntpy_afterlgn -->

			<!-- start instntpy_txncnfm -->
			<section class="width-100 money-remmitance-section" id="instntpy_txncnfm" ></section>
			<!-- end instntpy_txncnfm -->

			<!-- start instntpy_txn_cnfrmtn_screen -->
			<section class="width-100 money-remmitance-section" id="instntpy_txn_cnfrmtn_screen" ></section>
			<!-- end instntpy_txn_cnfrmtn_screen -->

			<!-- --------------- -->

			<!--start of modal-->
			<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="add-beneficiary">
  				<div class="modal-dialog modal-lg" id="size_modal">
	              <div class="modal-content" id="mainbeneaddidv">
	              	<div class="modal-header">
	                <h5 class="modal-title">Add New Beneficiary</h5>
					 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                 <span aria-hidden="true">&times;</span>
	                 </button>
	                 </div>

	                 <div class="modal-body">
					  
					 	<div id="add_be">
	                 	<form>
	                 		<div class="form-group row">
	                 		 <div class="col-lg-6">
			              	 <label>ACCOUNT NUMBER</label>
			               	 <input type="text" class="form-control" id="bcn" name="bcn" placeholder="Enter Account Number">	
			              	 </div>	

			              	 <div class="col-lg-6" id="bnkchoose">
			              	 <label>BANK NAME</label>
			               	<!-- <input type="text" class="form-control" name="bbnksel" id="bbnksel" placeholder="Enter Bank Name">	-->
							  <select name="bbnksel" id="bbnksel" >
									
                             </select>
			              	 </div>

							<div class="col-lg-6" id="bnkinputcol" style="display:none;">
			              	 <label>BANK NAME</label>
			               	<input type="text" class="form-control" id="bkinput" disabled>
			              	 </div>

			              	 </div>	

			              	 <div class="form-group row">
				              	 <div class="col-lg-6">
				              	 <label style="width: 100%;"><span class="float-left">IFSC</span> <span class="float-right"><a class="fontbold search-ifsc-btn" id="search_ifc" >Search IFSC</a></span></label>
				               	 <input type="text" class="form-control" id="bbnkifsc" name="bbnkifsc" placeholder="Enter IFSC">	
				              	 </div>	

				              	 <div class="col-lg-6">
				              	 <label>BENEFICIARY NAME</label>
				               	 <div class="input-group">
                                    <input type="text" class="form-control" name="benefname" id="benefname"  placeholder="Enter Beneficiary Name">	
                                     <div class="input-group-append" >
                                        <button class="btn blue-btn" type="button" id="benef_validate_accnt">Get Name</button>
                                    </div>
                                 </div>
                                 <span id="benevalidte_desc" class="text-danger">Bene. verification charges &#8377; N/A (optional)</span>
				              	 </div>	

			              	</div>


	                 	</form>
					
	                 	<form id="search-ifsc">
	                 	<div class="gray-header mb-20">Search IFSC</div>
	                 	<div class="form-group row">
	                 		<div class="col-lg-6">
			              	 <label>Select Bank Name</label>
			               	 
			               	 <select id="select-bank-name">
			               	 <option selected="selected">Select Bank Name</option>
			               	 </select>
			              	 </div>	
							
			              	  <div class="col-lg-6"  id="statediv" style="display:none;">
			              	  </div>	
							</div>

			              <div class="form-group row">
							
							 <div class="col-lg-6" id="citidiv" style="display:none;">
			              	 </div>
							
			              	 <div class="col-lg-6" id="branchdiv" style="display:none;">
			              	 </div>	
							
			              	</div>
							
			              	 <div class="form-group" id="get_bnkdeatbysearch">
			              	 	
			              	 </div>

			              	 <div class="width-100" id="ifsc-detail">
			              	 	
			              	 </div>	

	                 	</form>
						<div>
	                 </div>
						 </div>
	                   <div class="modal-footer">
							
		                	<button type="button" class="btn blue-btn" id="add_ab">Add Beneficiary</button>
                            <div id="beneaddverify_btndiv">
							<button type="button"  id="resendRegisterOTP" class="btn blue-btn"style="display:none;">Resend OTP</button>
							<button type="button" class="btn blue-btn" id="verfy_ab" style="display:none;">Verify</button>
                           </div>
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						   </div>
	                   </div>
                      
			         </div>
                     <div class="modal-content" id="beneaddverificationdiv" style="display:none;">
                    </div>
                    
				<!--end of modal-->
				
				<!---- benef OTP ------>

				
				
				<!----- benef Otp end--->

			</div>
			</div>
			<!-- ----------------------- -->

			</div>

		</div>
	<!-- </div> -->
<!-- </div> -->
<!-- </div> -->
	</section>
	<!--end of section-->


<!--end of wrapper-->
<script type="text/javascript">
    var latitude = longitude = '';
     // var geocoder = new google.maps.Geocoder();
     // var address =<?php $pin = isset($user_data['business_pincode']) ? $user_data['business_pincode'] : 110019;
     // echo $pin; ?>;
     // geocoder.geocode({'address': address.toString()}, function (results, status) {
     //    if (status == google.maps.GeocoderStatus.OK) {
     //        latitude = results[0].geometry.location.lat();
     //        longitude = results[0].geometry.location.lng();
     //    } else {
     //       // alert("Request failed.")
     //       latitude = "28.7041";
     //        longitude = "77.1025";
     //    }

     // });
            latitude = "28.7041";
            longitude = "77.1025";
    
    var oulLetDataChek = <?php echo json_encode($outletStatus) ?>;
    if (oulLetDataChek == 'PENDING') {
        $.ajax({
            url: 'ResisterOutlet/get_outlet_kycstats',
            dataType: "json",  
            type: 'post',   
            success: function(data) {
                if (data.error == 0) {
                    console.log(data.msg)
                } else if (data.error == 2) {
                    window.location.reload(true);
                } else {
                    console.log(data.error_desc)
                }
            }
        });
    }

</script>
<script type="text/javascript">
var RemitLogin = function(){
  	var date_val;
  
    var clogin = {};    
    var req = {};
   	
    var login_la;
    
    var find_validation_commercials = function(){
        $.ajax({
            "url":"MoneyTransfer/get_benevalidation_commercials",
            "dataType":"JSON",
            "method":"POST"
        }).done(function(beneverycomm_respo){
            if(beneverycomm_respo)
                {
                    if(beneverycomm_respo.error==0)
                        {
                            $('#benevalidte_desc').html('Bene. verification charges &#8377; '+beneverycomm_respo.charged_amount+' (optional)');
                        }
                    else if(beneverycomm_respo.error==2)
                        {
                            window.locaton.reload(true);
                        }
                    else{
                        console.log(console.log(beneverycomm_respo.error_desc));
                    }
                }
        }).fail(function(err){
            
        });
    }

   	return {
        init: function () {
            
            find_validation_commercials();
	    
		    $('#btl').click(function(e){
		        e.preventDefault(); 
		        clogin={};
		        login_la.ladda('stop');
		        switchBox('Login');
		       window.scrollTo(0,0);
		    });
    
    
		    $('#check_remi').click(function(e){
		        e.preventDefault(); 
		        $(this).addClass('btn-ladda ladda-button').attr('data-style','zoom-in');
		        
		        var la=$(this).ladda();
		        clogin.mobile 	= $("#mobile_input").val();	
		        login(la);
		    });
	    
	  
		    $("#regis_otp").on('keypress blur', function(e){
				var k = e.keyCode||e.which;
				var id = $(this)[0].id;
				var str = $(this)[0].value;
				var length = str.length;
				if(e.type == 'keypress'){
					
					if(k != 8 && k != 9){
						k = String.fromCharCode(k);
						var regex=/[0-9]/;
						if(!regex.test(k)){
							return false;
						}
				
						if(length >= 6){
							return false;
						}
					}
					return true;
				} else if(e.type == 'blur'){
					if(!validateAll(str,'otp')){
						$("#"+id).val("");			
					}			
				}
			});

	    	$("#mobile_input,#new_mobile").on('keypress blur keyup', function(e){
				var k = e.keyCode||e.which;
				var id = $(this)[0].id;
				var str = $(this)[0].value;
				var length = str.length;
			
				if(e.type == 'keypress'){
					if(k == 13 && id == 'mobile_input' && length == 10){
	                	$('#check_remi').addClass('btn-ladda ladda-button').attr('data-style','zoom-in');
	                	var la=$('#check_remi').ladda();
	                	clogin.mobile 	= $("#mobile_input").val();	
						login(la);
					}

					if(k != 8 && k != 9){
						k = String.fromCharCode(k);
						var regex=/[0-9]/;
						if(!regex.test(k)){
							return false;
						}
		
						var regexs=/[6-9]/;
						if(length == 0 && !regexs.test(k)){
							return false;     
						}
			
						if(length >= 10){
							return false;
						}
					}
					return true;
				} else if(e.type == 'blur'){
	        	    var _mobile = /^[6789][0-9]+$/;
	        	    if(!_mobile.test(str)){
	        	        $(this).val('')   
	        	    } else if(length != 10){
	        	        $(this).val('')   
	        	    }
	        	}
			});	
	
	
    
    
    
		    var validateAll= function(v,act){  
				var r = "";
			
				if(v == false){
					return false;
				}
				
				if(v == ""){
					return false;
				}
			
				if(act == 'name' || act == 'city'){
					r = /^[A-Za-z .]+$/;		
				} else if(act == 'mobile'){
					r = /^[6789][0-9]{9}$/; 
				} else if(act == 'address'){
					r = /^[A-Za-z0-9 &\-\/\',]+$/; 			
				} else if(act == 'pincode' || act == 'otp'){
					r = /^\d{6}$/;		
				} else if(act == 'pin'){
					r = /^[0-9]+$/;
				} else if(act == 'email'){
					r = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;				
				} else if(act == 'alnum'){
					r = /^[0-9a-zA-Z]+$/;		
				} else {
				return false;	
				}
				return r.test(v);		  
			} 
	
    
		  //  	var switchBox =   function(aBox){
		  //     	$("input").val('').removeClass("edited");
				// $('textarea').val('').removeClass('edited');
		  //     	$('#fgender').val('Female');
		  //     	$('#mgender').val('Male');
		  //     	date_val="";
				// if(aBox == "Login"){
			 //        $('#register').slideUp('fast');
				// 	$('#Login').slideDown("fast");
				// 	$('#register_otp_panel').slideUp("fast");			
		        
				// }else if(aBox == "Register"){
		  //     		$('#Login').slideUp("fast");
		  //     		$('#register').slideDown('fast');
		     
				// }else if(aBox == "otp"){
				// 	$('#register,#Login').slideUp("fast");
				// 	$('#register_otp_panel').slideDown("fast");	
				// }
		 	// }
   
   
		   	var login = function(la){
		   		login_la = la;
				var status = true;
				if(!validateAll(clogin.mobile,'mobile')){
					$("#mobile_input").val('');
			        $("#mobile_input").focus();
					toastr.error('Invalid mobile number');
		        	return false;
				} else{
		        	la.ladda('start');
					

		        	// InstanstPayAfterLogin.init();
			        // $(".js-select").select2({
			        //     theme: "bootstrap",
			        // });


					// acc_summary();
					// GET_BANK_WITH_CIFSC();

					$.ajax({
			            method: 'POST',
			            url: 'MoneyTransfer/InstantPayLogin?BeneficiaryCheck',
			            dataType: 'JSON',
			            data: clogin
			            // data: {'beneid': showtd1.id, 'remitid':remitid},
			        }).done(function (json) {
			            console.log(json);
			            var response = json.response;
			            if(json.error==0){

			            	GET_BANK_WITH_CIFSC();
			                benef_list(clogin, la);
			                getmodalifsconduty.init_ifsc();

							$(".js-select").select2({
					            theme: "bootstrap",
					        });

			            } else if(json.error==1){
                        	toastr.error(json.error_desc, 'Oops!');
	                    } else if(json.error==2){
	                        window.location.reload('true');
	                    } else if(json.error==3){
	                        switchBox('Register');
	                        $("#new_mobile").val(clogin.mobile).addClass('edited'); 
	                    }else if (json.error == 4) {
	                        toastr.success(json.msg);
	                        switchBox('otp');
	                        window.scrollTo(0, 0);
	                        req = {};
	                        req.remitterid = json.remitterid;
	                        req.mobile = clogin.mobile;
	                        console.log(req)
	                    } else{
	                        toastr.error(json.error_desc);
	                    }

			        }).fail(function (err) {
			            throw err;
			        });

					// benef_list(clogin, la);
					// getmodalifsconduty.init_ifsc();

					// $(".js-select").select2({
			  //           theme: "bootstrap",
			  //       });


					/*$.post('MoneyTransfer/InstantPayLogin',clogin,function(response){ 
						if(response){
			                if(response.error==0){
		                      //  toastr.info('ok');
		                        clogin={};
		                        window.location.reload('true');

		                        // call to beneficiary list and other details

		                    } else if(response.error==2){
		                        window.location.reload('true');
		                    } else if(response.error==3){
			                    switchBox('Register');
		    	                $("#new_mobile").val(clogin.mobile).addClass('edited'); 
		                	}else if (response.error == 4) {
		                        toastr.success(response.msg);
		                        switchBox('otp');
		                        window.scrollTo(0, 0);
		                        req = {};
		                        req.remitterid = response.remitterid;
		                        req.mobile = clogin.mobile;
		                        console.log(req)
			                } else{
							 	toastr.error(response.error_desc);
		                	}

		                	la.ladda('stop');
		            	}
					},'json').fail(function(x){throw x;});*/
		    	}
		   	}
	 
		 	$('#regis_user').click(function(e){
		     	e.preventDefault();
		     	$(this).addClass('btn-ladda ladda-button').attr('data-style','zoom-in');
		     	var la=$(this).ladda();
		    	Register(la); 
		 	})
 
 

		   	var Register = function(la){
		       	var param = {};
				param.mobile = $("#new_mobile").val();
			  	param.name = $("#new_name").val();
				
		       
				if(!validateAll(param.mobile,'mobile')){
					$("#new_mobile").focus();	
					 toastr.error("Invalid mobile number");
			        return false;
				}else if(!validateAll(param.name,'name')){
					$("#new_name").focus();		
					toastr.error("Invalid Name");		
			        return false
				} else{
		            la.ladda('start');
		        	$.post('MoneyTransfer/instantpay_remt_registration',{data:param},function(response){
		            	if(response){  
		                    if(response.error==0){
								toastr.success(response.msg);
		                        switchBox('otp');
		                        window.scrollTo(0,0);
		                       	req={}; 
		                        req=param;
		                        req.txn=response;
                                req.remitterid = response.remitterid;
		                        param={};
		                    }else if(response.error==2){
		                        window.location.reload(true);
		                    }else{
								 toastr.error(response.error_desc);
		                    }
		                    la.ladda('stop');
		                }
		        	},'json').fail(function(error){la.ladda('stop'); throw error});
		       	}
		   	}
		   
		  
		   	$("#tr-bk").click(function(e){
			   	e.preventDefault();
			   	login_la.ladda('stop');
			   	console.log('fd');
				switchBox('Login');
			});
   
		
            
            
		    $("#registerOTP").click(function(e){
				e.preventDefault();
				if(req && req!= "" && req!=null && !$.isEmptyObject(req)){
		           	console.log('111'); 
		           	req.otp = $("#regis_otp").val(); 
		            if(req.otp!=""){
		            	if(!validateAll(req.otp,'otp')){
							$("#regis_otp").focus();	
						 	toastr.error('Invalid OTP');
		        			return false;
						}else{
							$(this).addClass('btn-ladda ladda-button').attr({'data-style':'zoom-in'});
				            var la=$(this).ladda();
				            la.ladda('start');
		            
							$.post('MoneyTransfer/instpy_verifyregiscus',{data:req},function(response){
								if(response){  
		                    		if(response.error==0){
		                             	toastr.success(response.msg);
		                        
		                            	clogin.mobile=req.mobile;
		                            	req={};
		                            	login(la);
		                        	}else if(response.error==2){
		                            	window.location.reload(true);
		                        	}else{
										toastr.error(response.error_desc);
		                    		}
		                    		la.ladda('stop');
		                		}
		        			},'json').fail(function(error){
		        				la.ladda('stop'); 
		        				throw error;
		        			});
		        
			  			}
		         	}else {
		                $("#regis_otp").val('');
		                $("#regis_otp").focus();
		                toastr.error('Invalid OTP')
		                return false;
		            }
		        }
			});	
        
		}

	}

}();

$(document).ready(function(){
 	$('<link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />').insertAfter('#endglobal');
		RemitLogin.init();
});
</script>

<script src="assets/script/instantpay_afterlogin.js?v=2.43" type="text/javascript"></script>
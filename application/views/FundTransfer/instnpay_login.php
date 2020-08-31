<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$user_data = get_user_details();
$outletStatus = isset($outlet['kyc_apibox'])?$outlet['kyc_apibox']:'';


?> 
 <style>
     .operator-logo {
    position: absolute;
    right: 0px;
    z-index: 10;
    top: 5px;
    right: 28px;
}
 </style>
        <div class="col-lg-9">
							<div class="tab-content">
							

						   <div id="mobile-recharge" class="width-100 tab-pane fade active show">
						   <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold float-left">REMITTER LOGIN</h6>
						   </div>
						   
						   
                <form id="Login" class="recharge-form-outer">
              <!--  <div class="form-group">
											<label>Sender Mobile No:</label>
											<input type="tel" class="form-control" placeholder="Enter Mobile no" id="mobile_input">
									</div> -->
									
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
									
               <!--   <div class="text-right">
											<button type="submit" class="btn btn-primary legitRipple" id="check_remi">Proceed</button>
									</div>-->
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
				
				<div class="form-group form-group-divder-set row ml-0 mr-0">
				<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">SURNAME</label>
				<div class="col-lg-7 pl-0 pr-0">
				<input type="text" name="" class="form-control no-brd dark-txt"  placeholder="Enter Your Surname Name" id="new_surname">
				<span data-for="new_surname"></span>
				</div>
			    </div>
				
				<!--<div class="form-group form-group-divder-set row ml-0 mr-0">
				<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">PINCODE</label>
				<div class="col-lg-7 pl-0 pr-0">
				<input type="text" name="" class="form-control no-brd dark-txt"  placeholder="Enter Your Name" id="new_pincode">
				<span data-for="new_pincode"></span>
				</div>
			    </div>-->
				
              
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
						</div>
					</div>
				</div>
			</div>
		</div>
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
   return {

       
        init: function () {
        
  
    $('#btl').click(function(e){
        e.preventDefault(); 
        clogin={};
        switchBox('Login');
       window.scrollTo(0,0);
    });
    
    
     $('#check_remi').click(function(e){
        e.preventDefault(); 
        $(this).addClass('btn-ladda ladda-button').attr('data-style','zoom-in');
        
        var la=$(this).ladda();
        clogin.mobile 	= $("#mobile_input").val();	
        login(la);
    })
    
  
    $("#regis_otp").on('keypress blur', function(e)
	{
		var k = e.keyCode||e.which;
		var id = $(this)[0].id;
		var str = $(this)[0].value;
		var length = str.length;
		if(e.type == 'keypress')
		{
			
			if(k != 8 && k != 9)
			{
				k = String.fromCharCode(k);
				var regex=/[0-9]/;
				if(!regex.test(k))
				{
					return false;
				}
			
				if(length >= 6)
				{
					return false;
				}
			}
			
			
			
			return true;
		}
		else if(e.type == 'blur')
		{
			if(!validateAll(str,'otp'))
			{
				$("#"+id).val("");			
			}			
		}
	});
    $("#mobile_input,#new_mobile").on('keypress blur keyup', function(e)
	{
		var k = e.keyCode||e.which;
		var id = $(this)[0].id;
		var str = $(this)[0].value;
		var length = str.length;
		
		if(e.type == 'keypress')
		{
			if(k == 13 && id == 'mobile_input' && length == 10)
			{
                $('#check_remi').addClass('btn-ladda ladda-button').attr('data-style','zoom-in');
                var la=$('#check_remi').ladda();
                clogin.mobile 	= $("#mobile_input").val();	
				login(la);
			}
			if(k != 8 && k != 9)
			{
				k = String.fromCharCode(k);
				var regex=/[0-9]/;
				if(!regex.test(k))
				{
					return false;
				}
	
				var regexs=/[6-9]/;
				if(length == 0 && !regexs.test(k))
				{
					return false;     
				}
	
				if(length >= 10)
				{
					return false;
				}
			}
	
				
			return true;
		}
		else if(e.type == 'blur')
            	{
            	    var _mobile = /^[6789][0-9]+$/;
            	    if(!_mobile.test(str))
            	    {
            	        $(this).val('')   
            	    }
            	    else if(length != 10)
            	    {
            	        $(this).val('')   
            	    }
            	}
	});	
	
	
    
    
    
    var validateAll= function(v,act)  
   {  
	var r = "";
	
	if(v == false)
	{
		return false;
	}
		
	if(v == "")
	{
		return false;
	}
	
	if(act == 'name' || act == 'city')
	{
		r = /^[A-Za-z .]+$/;		
	}
	else if(act == 'mobile')
	{
		r = /^[6789][0-9]{9}$/; 
	}
	else if(act == 'address')
	{
		r = /^[A-Za-z0-9 &\-\/\',]+$/; 			
	}	
	else if(act == 'pincode' || act == 'otp')
	{
		r = /^\d{6}$/;		
	}
	else if(act == 'pin')
	{
		r = /^[0-9]+$/;
	}
	else if(act == 'email')
	{
		r = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;				
	}
	else if(act == 'alnum')
	{
		r = /^[0-9a-zA-Z]+$/;		
	}
	else
	{
		return false;	
	}
	return r.test(v);		  
} 
	
    
   var switchBox =   function(aBox)
  {
      $("input").val('').removeClass("edited");
	$('textarea').val('').removeClass('edited');
      $('#fgender').val('Female');
      $('#mgender').val('Male');
      date_val="";
	if(aBox == "Login")
	{
		
     
        $('#register').slideUp('fast');
       
		$('#Login').slideDown("fast");
$('#register_otp_panel').slideUp("fast");			
        
	}
	else if(aBox == "Register")
	{
      $('#Login').slideUp("fast");
     
      $('#register').slideDown('fast');
     
	}
      else if(aBox == "otp")
	{
		$('#register,#Login').slideUp("fast");
		$('#register_otp_panel').slideDown("fast");	
	}
	
 }
   
   
   var login = function(la){
       
       
	var status = true;
			
	
	if(!validateAll(clogin.mobile,'mobile'))
	{
		$("#mobile_input").val('');
        $("#mobile_input").focus();
		
		 toastr.error('Invalid mobile number');
    
        return false;
	}
	
	else{
        la.ladda('start');
		$.post('MoneyTransfer/InstantPayLogin',clogin,function(response)
		{ if(response)
            {
                
                if(response.error==0)
                    {
                      //  toastr.info('ok');
                        clogin={};
                        window.location.reload('true');
                        
                    }
                else if(response.error==2)
                    {
                        window.location.reload('true');
                    }
                else if(response.error==3)
                {
                  
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

                }
                else{
					 toastr.error(response.error_desc);
                
                }
                la.ladda('stop');
            }
			
            
		},'json').fail(function(x){throw x;});
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
	param.surnamename = $("#new_surname").val();

       
	if(!validateAll(param.mobile,'mobile'))
	{
		
		$("#new_mobile").focus();	

			 toastr.error("Invalid mobile number");
		
        return false;
	}
	
	else if(!validateAll(param.name,'name'))
	{
		
		$("#new_name").focus();		
			toastr.error("Invalid Name");		
	 
        return false
	}
	
	else if(!validateAll(param.surnamename,'name'))
	{
		
		$("#new_surname").focus();		
		toastr.error("Invalid Surname Name");		
	  
        return false
	}
  
       
       else{

           
            la.ladda('start');
         
        $.post('MoneyTransfer/instantpay_remt_registration',{data:param},function(response){
            if(response)
                {  
                    if(response.error==0)
                        {
							
							 toastr.success(response.msg);
                        
                            switchBox('otp');
                             window.scrollTo(0,0);
                           req={}; 
                            req=param;
                            req.txn=response.req;
                            param={};
                        }
                    else if(response.error==2)
                        {
                            window.location.reload(true);
                        }
                    else{
						
						 toastr.error(response.error_desc);
                     
                    }
                    la.ladda('stop');
                }
        },'json').fail(function(error){la.ladda('stop'); throw error});
        
       }
   }
   
  
   $("#tr-bk").click(function(e){
	   e.preventDefault();
	   console.log('fd');
	  
		 switchBox('Login');
	});
   
  /*     $("#resendRegisterOTP").click(function(e)
	{	
		e.preventDefault();
		if(req && req!= "" && req!=null && !$.isEmptyObject(req))
		{
			
			$(this).addClass('btn-ladda ladda-button').attr({'data-style':'zoom-in','data-spinner-color':"#333"});
            var la=$(this).ladda();
            la.ladda('start');
			$.post('',{"data":req},function(response)
			{
				
				if(response)
                {  
                    if(response.error==0)
                        {
							
						 toastr.success(response.msg);
                            
                    
                        
                        }
                    else if(response.error==2)
                        {
                            window.location.reload(true);
                        }
                    else{
						
						 toastr.error(response.error_desc);
                           
                    }
                    la.ladda('stop');
                }			
			},'json').fail(function(x){la.ladda('stop'); throw x;});
		}		
	}); */
            
            
    $("#registerOTP").click(function(e)
	{
		
		e.preventDefault();
   
		if(req && req!= "" && req!=null && !$.isEmptyObject(req))
		{
           console.log('111'); 
           req.otp = $("#regis_otp").val(); 
            if(req.otp!=""){
            if(!validateAll(req.otp,'otp'))
		{
			$("#regis_otp").focus();	

				 toastr.error('Invalid OTP');
	
        return false;
				
		}else{
            
		$(this).addClass('btn-ladda ladda-button').attr({'data-style':'zoom-in'});
            var la=$(this).ladda();
            la.ladda('start');
            
			$.post('MoneyTransfer/instpy_verifyregiscus',{data:req},function(response)
			{
				if(response)
                {  
                    if(response.error==0)
                        {
                             toastr.success(response.msg);
                        
                            clogin.mobile=req.mobile;
                            req={};
                            login(la);
                           
                        }
                    else if(response.error==2)
                        {
                            window.location.reload(true);
                        }
                    else{
						
						toastr.error(response.error_desc);
                      
                    }
                    la.ladda('stop');
                }
        },'json').fail(function(error){la.ladda('stop'); throw error});
        
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

    };

}();



$(document).ready(function(){
 $('<link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />').insertAfter('#endglobal');
     
RemitLogin.init();
  
   
});
</script>
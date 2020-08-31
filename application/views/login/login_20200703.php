<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
 $user_email =$this->session->userdata('userid');

 if($user_email){
 	redirect('Dashboard');
 }
 
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<base href="<?=base_url()?>">
<title>Fast Way</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/common.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
<link href="assets/toastr/toastr.min.css" rel="stylesheet" type="text/css">
<link href="assets/ladda/css/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
<script src="assets/js/jquery-3.4.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/ladda/js/spin.min.js" type="text/javascript" ></script>
<script src="assets/ladda/js/ladda.min.js" type="text/javascript" ></script>
<script src="assets/ladda/js/ladda.jquery.min.js" type="text/javascript" ></script>
<script type="text/javascript" src="assets/toastr/toastr.min.js"></script>
</head>

<style type="text/css">

.login-wrapper
{
width: 100%;
float: left;
position: fixed;
background-image: url('assets/images/login-bg.jpg');
display: flex;
align-items: center;
justify-content: center;
background-size: cover;
top: 0;
right: 0;
bottom: 0;
left: 0;
overflow-y: auto;
}

.login-inner-container
{
background-color: #fff;
border: 1px solid #d6d4d4;
border-radius: 10px;
}

.register-inner-container
{
background-color: #fff;
border: 1px solid #d6d4d4;
border-radius: 10px;
padding:15px;
}

.login-info
{
height: 100%;
background-color: #efefef;
padding: 70px 20px;
position: relative;
display: flex;
flex-direction: column;
justify-content: center;
border-radius: 10px 0px 0px 10px;
}

.login-form-container
{
padding: 10px 20px;
display: flex;
height: 100%;
justify-content: center;
align-items: center;
flex-direction: column;
}

.width50
{
width: 50%;
}

.Copyright-txt
{
position: absolute;
bottom: 0px;
}

#register_form_Section
{
display: none;
}

#password-recovery-view
{
display: none;
}
#login-otp-view
{
display: none;
}


#set-newpassword-view
{
display: none;
}


@media only screen and (min-width: 1100px)
{

.login-inner-container
{
width: 1000px;
}
  
.register-inner-container
{
width: 1000px;
}

}   

@media only screen and (max-width: 1099px)
{
.login-container , .register-container{max-width: 100%; padding-left: 15px; padding-right: 15px;}
.login-wrapper{position: relative; padding-top: 10px; padding-bottom: 10px;}
}

@media only screen and (min-width: 768px)
{
.login-inner-container{height: 650px;}
}


@media only screen and (max-width: 767px)
{
.login-info , .login-form-container{width: 100%;}

.login-inner-container
{
height: auto;
width: 100%;
float: left;
}

.login-info{border-radius: 10px 10px 0px 0px;}

}

</style>

<body>

<div class="login-wrapper">
	<!--start of login section-->

	<div class="login-container" id="login-section">

	<div class="login-inner-container">
		<div class="login-info width50 float-left">
			<h2 style="color: #187dbd;" class="fontbold">Fast Pay</h2>

			<p class="font14  dark-txt">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.	
			</p>

			<p class="font14 fontbold Copyright-txt">@ Copyright 2018. All Rights Reserved.</p>
		</div>

		<div class="login-form-container width50 float-left">
			<!--start of login view-->
					<div id="login-view">
						  <div class="width-100 mb-4"><img src="assets/images/logo.png" width="120"></div>

							<div class="width-100 mb-4">
								<h4 class="fontbold">Welcome Back !</h4>
								<h5 class="light-txt fontbold">Sign in to Continue</h5>
							</div>

							<form class="width-100 mb-4">
								<div class="form-group">
									<input type="text" class="form-control" id="login_mobile" name="login_mobile"  placeholder="Enter Your Mobile">
									 <span data-for="login_mobile"></span>
								</div>

								<div class="form-group">
									<input type="password" class="form-control" id="login_pass" name="login_pass" placeholder="Enter Your Password">
									 <span data-for="login_pass"></span>
								</div>

								<div class="form-group">
									<a class="btn blue-btn btn-block white-txt" id="login_continue">LOGIN</a>
									<a class="btn btn-secondary btn-block white-txt register-btn">REGISTER</a>
								</div>
							</form>

							<div class="width-100">
								<p class="fontbold"><a href="#" class="forgot-pass" id="forgot-password">Forgot Password ?</a></p>
							</div>
				    </div>
		    <!--end of login view-->
		    <!--login otp -->
		            <div id="login-otp-view">
						  <div class="width-100 mb-4"><img src="assets/images/logo.png" width="120"></div>

							<div class="width-100 mb-4">
								<h4 class="fontbold">Login OTP</h4>
								<h5 class="light-txt fontbold">OTP Will Be Send On Your Register Mobile Number.</h5>
							</div>

							<form class="width-100 mb-4">
								<div class="form-group">
									<input type="tel" class="form-control" id="otp_login" name="otp_login"  placeholder="OTP">
									 <span data-for="otp_login"></span>
								</div>

								
								<div class="form-group">
									<a class="btn blue-btn btn-block white-txt" id="login_otp_bttn">LOGIN</a>
									<a class="btn btn-secondary btn-block white-txt register-btn" id="login_Resend_OTP">Resend OTP</a>
								</div>
							</form>

							<div class="width-100">
								<a class="btn btn-secondary btn-block white-txt show-login" id="otp-password">BACK</a>
							</div>
				    </div>

		    <!--enf of login otp-->
		    <!--start of password recovery view-->
		    <div class="" id="password-recovery-view">
		    	<div class="width-100 mb-4"><img src="assets/images/logo.png" width="120"></div>

							<div class="width-100 mb-4">
								<h4 class="fontbold">Welcome Back !</h4>
								<h5 class="light-txt fontbold">Password Recovery</h5>
							</div>

							<form class="width-100 mb-4">
								<div class="form-group">
									<input type="tel" class="form-control" name="resetmob" id="resetmob" placeholder="Enter Mobile Number">
									<span data-for="resetmob"></span>
								</div>

								<div class="form-group">
									<a class="btn blue-btn btn-block white-txt password-set-show-btn" id="frgt_send-btn">SEND</a>
									<a class="btn btn-secondary btn-block white-txt show-login" id="frgt_bck">BACK</a>
								</div>
							</form>
		    </div>
		    <!--end of password recovery view-->

		    <!--start of set new password view-->
		      <div class="" id="set-newpassword-view">
		    	<div class="width-100 mb-4"><img src="assets/images/logo.png" width="120"></div>

							<div class="width-100 mb-4">
								<h4 class="fontbold">Welcome Back !</h4>
								<h5 class="light-txt fontbold">SET NEW PASSWORD</h5>
							</div>

							<form class="width-100 mb-4">
								<div class="form-group">
									<input type="password" class="form-control" name="reset_otp" id="reset_otp" placeholder="Enter OTP">
									 <span data-for="reset_otp"></span>	
								</div>

								<div class="form-group">
									<input type="password" class="form-control" name="" id="newpassword" placeholder="Enter New Password">
									 <span data-for="newpassword"></span>	
								</div>

								<div class="form-group">
									<input type="password" class="form-control" name="" id="confirm-password" placeholder="Confirm Password">
									 <span data-for="confirm-password"></span>	
								</div>

							<!-- 	<div class="form-group">
									<a class="btn blue-btn btn-block white-txt" id="ChangePasswordbtn">SEND</a>
									<a class="btn btn-secondary btn-block white-txt register-btn" id="login_Resend_OTP">Resend OTP</a>
									<a class="btn btn-secondary btn-block white-txt show-password-recovery-view" id="forgotback-btn">BACK</a>
								</div>
							</form>
 -->

							<div class="form-group">
									<a class="btn blue-btn btn-block white-txt" id="ChangePasswordbtn">SEND</a>
									<a class="btn btn-secondary btn-block white-txt register-btn" id="frgtpass_Resend_OTP">Resend OTP</a>
									
								</div>

								
							</form>

							<div class="width-100">
							
								<a class="btn btn-secondary btn-block white-txt show-password-recovery-view" id="forgotback-btn">BACK</a>
							</div>
		    </div>
		    <!--end of set new password view-->




		</div>

	 </div>
   </div>
   <!--end of login section-->


	<!--start of register section-->
	<div  class="register-container" id="register_form_Section">
	<div class="register-inner-container">

		  <div class="width-100 mb-4"><img src="assets/images/logo.png" width="120"></div>

			<div class="width-100 mb-4">
				<h4 class="fontbold">Welcome Back !</h4>
				<h5 class="light-txt fontbold">Register to Continue</h5>
			</div>
		
		       <form id="register_form">
						<div class="row">
							<div class="form-group col-md-3">
							<input type="text" name="fullname" id="fullname" class="form-control" placeholder="Enter Full Name">
							<span data-for="fullname"></span>	
							</div>

							<div class="form-group col-md-3">
								
								<input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter Full Name">
							  <span data-for="lastname"></span>	
							</div>
							<div class="form-group col-md-6">
								
								<input type="tel" name="mobile_num"  id="mobile_num" class="form-control" placeholder="Enter Mobile Number">
								<span data-for="mobile_num"></span>	
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-4">
								
								<input type="email" name="usr_email" id="usr_email" class="form-control" placeholder="Enter Email Id">
							<span data-for="usr_email"></span>	
							</div>
							<div class="form-group col-md-4">
								
								<input type="text" name="dob" id="dob" class="form-control" placeholder="Enter Date Of Birth" readonly="">
							<span data-for="dob"></span>	
							</div>

							<div class="form-group col-md-4">
								    
								    <select class="form-control" id="gender">
								    <option >Select Gender</option>
								      <option value="Male">Male</option>
								      <option value="Female">Female</option>
								    </select>
								    <span data-for="gender"></span>	
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								
								<input type="text" name="shopname" id="shopname" class="form-control" placeholder="Enter Shop Name">
								 <span data-for="shopname"></span>	
							</div>

							<div class="form-group col-md-6">
								
								<textarea class="form-control" name="shop_addrs" id="shop_addrs" placeholder="Enter shop address"></textarea>
								<span data-for="shop_addrs"></span>	
							</div>
					   </div>

					    <div class="row">
							<div class="form-group col-md-6">
								
								<input type="text" name="pincode" id="pincode" class="form-control" placeholder="Enter Pin Code">
								<span data-for="pincode"></span>	
							</div>
							<div class="form-group col-md-6">
								
								<input type="text" name="shop_state" id="shop_state" class="form-control" placeholder="Enter Shop State" readonly="">
								<span data-for="shop_state"></span>	
							</div>
							
					    </div>
					    <div class="row">
							<div class="form-group col-md-6">
								
								<input type="text" name="shopcity" id="shopcity" class="form-control" placeholder="Enter Shop city">
								<span data-for="shopcity"></span>	
							</div>
							<div class="form-group col-md-6">
								
								<textarea class="form-control" name="prmnt_addrs" id="prmnt_addrs" placeholder="Enter permanent address"></textarea>
								<span data-for="prmnt_addrs"></span>	
							</div>
							
					   </div>
					    <div class="row">
							<div class="form-group col-md-4">
								
								<input type="text" name="pan_num" id="pan_num" class="form-control" placeholder="Enter Pan number">
								<span data-for="pan_num"></span>	
							 </div>
							<div class="form-group col-md-4">
								
								<input type="text" name="aadhar_num" id="aadhar_num" class="form-control" placeholder="Enter Aadhar number">
								<span data-for="aadhar_num"></span>	
							</div>
							<div class="form-group col-md-4">
							
							<input type="text" name="gstn_num" id="gstn_num" class="form-control" placeholder="Enter GSTIN number">
							<span data-for="gstn_num"></span>	
							</div>
					    </div>

					    <div class="row">
							<div class="form-group col-md-6">
							<button class="btn blue-btn" type="submit" style="margin-top: 30px;" id="regstrn"><span class="ladda-label">SUBMIT<span class="ladda-spinner"></button>     
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-12">
								<p class="fontbold">Already have an account? <a class="login-btn" href="#" >Login</a></p>
							</div>
						</div>

				</form>
	</div>
    </div>
	<!--end of register section-->
</div>

<!-- <script>

$(document).ready(function(){

$(".register-btn").click(function(){
$("#register_form_Section").show();
$("#login-section").hide();
$("#login-otp-view").hide();
});

$(".login-btn").click(function(){
$("#register_form_Section").hide();
$("#login-section").show();
$("#login-otp-view").hide();
});

$("#login_continue").click(function(){
$("#login-view").hide();
$("#login-otp-view").show();
});

 


$(".forgot-pass").click(function(){
$("#login-view").hide();
$("#password-recovery-view").show();
$("#login-otp-view").hide();
});

$(".show-login").click(function(){
$("#login-view").show();
$("#password-recovery-view").hide();
$("#login-otp-view ").hide();
});

$(".password-set-show-btn").click(function(){
$("#password-recovery-view").hide();
$("#set-newpassword-view").show();
$("#login-otp-view ").hide();
});

$(".show-password-recovery-view").click(function(){
$("#password-recovery-view").show();
$("#set-newpassword-view").hide();
$("#login-otp-view ").hide();
});

});
</script>
 -->

  <script type="text/javascript">
  	var Login_initialize=function()
	{

		var valR = {
                email:/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
                email_old:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z]{2,6}$/,
                otp:/[0-9]/,
                mobile:/^[0-9]{10}$/,
                usr_email: /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    
        }

        $("#login_pass,#newpassword").on('keypress blur keyup', function(e){
		var k = e.keyCode||e.which;
		var id = $(this)[0].id;
		var str = $(this)[0].value;
		var length = str.length;
        
        if(length>5 || length==0){
             helpBlck({id:id,'action':'remove'});
        }
       
		if(e.type == 'blur')
		{
            if(length>0){
                if(length<=5)
                {
                    
                    err=(str != "")?"Invalid Password.":"Password is required."
                    helpBlck({'id':id, 'msg':err, 'type':'error'});	

                }else{
                    
                    if(length>15)
                        {
                         
                    helpBlck({'id':id, 'msg':'Password cannot be more than 15 characters.', 'type':'error'});	 
                            
                        }else{

                            helpBlck({id:id,'action':'remove'});
                        }
                    
                }	
            }else{
                helpBlck({id:id,'action':'remove'});
            }
		}else if(e.type == 'keypress')
            {
                if(id == 'login_pass')
                    {
                        if(k==13){
                             if(length>0){
                            $('#login_continue').trigger('click');
                             }
                        }
                    }
                 else if(id == 'newpassword' || id == 'confirm-password')
                     {
                          if(k==13){
                             if(length>0){
                            $('#ChangePasswordbtn').trigger('click');
                             }
                        }
                     }
              
                if(k != 8 && k != 9)
            		{
            			if(length == 15)
            			{
            				return !1
            			}
            		}
                
            }
	});


        $("#login_mobile,#resetmob,#mobile_num").on('keypress blur keyup keydown', function(e){
		var k = e.keyCode||e.which;
		var id = $(this)[0].id;
		var str = $(this)[0].value;
		var length = str.length;
        
        if(length==0)
           {
               helpBlck({id:id,'action':'remove'});
           }
        if(k==8)
         {
             if(valR.email.test(str))
            {
                
                helpBlck({id:id,'action':'remove'});
            }
            else if(valR.mobile.test(str))
            {
                helpBlck({id:id,'action':'remove'});
            }else{
                if(length>0){
               helpBlck({'id':id, 'msg':'Please enter a valid Mobile number.', 'type':'error'}); 
                }
            }
         }
		 if(e.type == 'blur' || e.type == 'keyup')
		{
            
            if(valR.email.test(str))
            {
                helpBlck({id:id,'action':'remove'});
            }
            else if(valR.mobile.test(str))
            {
                helpBlck({id:id,'action':'remove'});
            }else{
                if(e.type == 'blur'){
                    if(length>0){
               helpBlck({'id':id, 'msg':'Please enter a valid Mobile number.', 'type':'error'}); 
                    }else{
                         helpBlck({id:id,'action':'remove'});
                    }
                }
                
            }
            
            
		}else if(e.type == 'keypress')
            {
                if(id == 'login_mobile')
                    {
                         if(k==13){
                            if(length>1){
                            $('#login_continue').trigger('click');
                            }
                        }
                    }
                else if(id == 'resetmob')
                {
                         if(k==13){
                            if(length>1){
                            $('#frgt_send-btn').trigger('click');
                            }
                        }
                }
                else if(id == 'mobile_num')
                {
                         if(k==13){
                            if(length>1){
                            $('#regstrn').trigger('click');
                            }
                        }
                }
            }
	});

  $("#otp_login,#reset_otp").on('keypress blur keyup keydown', function(e){
        var k = e.keyCode||e.which;
        var id = $(this)[0].id;
        var str = $(this)[0].value;   
        var length = str.length; 
        
        if(e.type == 'keypress')
                {
                    
                    if(id=='otp_login')
                    {

                        if(k==13){
                            if(length>1){
                            $('#login_otp_bttn').trigger('click');
                            }
                        }
                    }else if(id=='reset_otp')
                    {
                           if(k==13){
                               if(length>1){
                            $('#ChangePasswordbtn').trigger('click');      
                               }
                            }      
                     }
                    
                    if(k != 8 && k != 9)
                    {
                        
                        k = String.fromCharCode(k);
                        var mb_regex = valR.otp;
                        if(!mb_regex.test(k))
                        {
                            return !1
                        }
                        
                        if(length == 6)
                        {
                            return !1
                        }
                    }

                    return !0
        }
         else if(e.type == 'blur')
        {
            
            if(!valR.otp.test(str) || length!=6)
            {
                if(length>0){
                err=(str != "")?"Invalid OTP.":"OTP is required."
                helpBlck({'id':id, 'msg':'Invalid OTP.', 'type':'error'});
                
                }else{
                     helpBlck({id:id,'action':'remove'});
                }
            }else{

                helpBlck({id:id,'action':'remove'});
                
            }           
        }
      
     
     
        if((valR.otp.test(str) && length==6) || length==0)
            {
                helpBlck({id:id,'action':'remove'});
            }
      
     });


            //---------------------------------------Validate-----------------------------//

    var validate = function(p){
   
	if(typeof p === 'undefined') {	return false; }
	if(typeof p.id === 'undefined') { p.id = ''; }
	if(typeof p.data === 'undefined') { p.data = ''; }
	if(typeof p.type === 'undefined') {	p.type = ''; }
	if(typeof p.error === 'undefined') { p.error = false; }
	if(typeof p.msg === 'undefined') { p.msg = false; }

	if(p.type == "PASS")
	{
		if(p.data != "" && (p.data.length>5))
		{
            if(p.data.length>15)
                {
                    helpBlck({'id':p.id, 'msg': 'Password cannot be more than 15 characters.', 'type':'error'});
                }else{
                    helpBlck({id:p.id,'action':'remove'});
			         return true;
                }
			
		}
		else
		{
			if(p.error === true)
			{
                err=(p.data != "")?"Invalid Password.":"Password is required."
				//error({'id':p.id, 'action':'set', 'msg': p.msg || 'Invalid Service Provider'});
                helpBlck({'id':p.id, 'msg': err, 'type':'error'});
			}
		}
	}
	else if(p.type == "EMAIL")
	{
		if(valR.email.test(p.data))
		{
			
				helpBlck({id:p.id,'action':'remove'});
				return true;
			
		}
		else
		{
			if(p.error == true)
			{
				//error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Please enter a valid Email.', 'type':'error'});
			}
			
		}
	}
        
else if(p.type == "EMAIL|MOBILE")
	{
        
        
	
         if(valR.mobile.test(p.data))
        {
                helpBlck({id:p.id,'action':'remove'});
				return true;
        }
		else
		{
            
			if(p.error == true)
			{
                if(p.focus===true)
                    {
                        $('#'+p.id).focus();
                    }
				//error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Please enter a valid Mobile number.', 'type':'error'});
			}
			
		}
	}


	      if (p.type == "MOBILE")
                    {
                        var _mobile = /^[6789][0-9]+$/;
                        if (_mobile.test(p.data))
                        {
                            if (p.error == true && (p.data.length != 10))
                            {

                               // helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                                helpBlck({'id':p.id, 'msg':'Please enter a valid contact number.', 'type':'error'});
                            }
                            else
                            {
                                helpBlck({id: p.id, 'action': 'remove'});
                                return true;
                            }
                        } else
                        {
                            if (p.error == true)
                            {
                                //helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                                helpBlck({'id':p.id, 'msg':'Please enter a valid contact number.', 'type':'error'});
                            }

                        }
                    }



        
	else if(p.type == "OTP")
	{
		if(valR.otp.test(p.data) && p.data.length==6)
		{
			
				helpBlck({id:p.id,'action':'remove'});
                $('#'+p.id).closest('.input-group').find('.input-group-btn button').removeClass('m-n-27');
				return true;
			
		}
		else
		{
			if(p.error == true)
			{
				err=(p.data != "")?"Invalid OTP.":"OTP is required."
                helpBlck({'id':p.id, 'msg':err, 'type':'error'});
                $('#'+p.id).closest('.input-group').find('.input-group-btn button').addClass('m-n-27');
			}
			
		}
	}
	else if(p.type == "CONPASS")
	{
		if(p.data==$('#confirm-password').val())
		{
			
				helpBlck({id:p.id,'action':'remove'});
				return true;
			
		}
		else
		{
			if(p.error == true)
			{
				err=(p.data != "")?"Password Mismatch11.":"Confirm Password is required."
                helpBlck({'id':p.id, 'msg':err, 'type':'error'});
			}
			
		}
	}
    else if(p.type == "BNAME")
    {
       if(p.data.length>2)
		{
			
				helpBlck({id:p.id,'action':'remove'});
				return true;
			
		}
		else
		{
			if(p.error == true)
			{
				//error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Please enter a valid shop name.', 'type':'error'});
			}
			
		}
    }
     else if(p.type == "CITY")
    {
       if(p.data.length>1)
        {
            
                helpBlck({id:p.id,'action':'remove'});
                return true;
            
        }
        else
        {
            if(p.error == true)
            {
                //error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Please enter a valid city name.', 'type':'error'});
            }
            
        }
    }
     else if(p.type == "ADDRESS")
    {
       if(p.data.length>5)
        {
            
                helpBlck({id:p.id,'action':'remove'});
                return true;
            
        }
        else
        {
            if(p.error == true)
            {
                //error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Please enter a valid Address.', 'type':'error'});
            }
            
        }
    }else if(p.type == "PINCODE"){
           var _pincode = /[0-9]/;
            if (_pincode.test(p.data)) {
           
                helpBlck({id: p.id, 'action': 'remove'});
                return true;
               
            } else {
              
                helpBlck({'id': p.id, 'msg': 'Invalid Pincode', 'type': 'error'});
            }
        }
        else if(p.type == "PAN"){
           var _pincode = /^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/;
            if (_pincode.test(p.data)) {
           
                helpBlck({id: p.id, 'action': 'remove'});
                return true;
               
            } else {
              
                helpBlck({'id': p.id, 'msg': 'Invalid PAN Number', 'type': 'error'});
            }
        } else if(p.type == "AADHAR"){
           var _pincode = /^\d{4}\s\d{4}\s\d{4}$/;
            if (_pincode.test(p.data)) {
           
                helpBlck({id: p.id, 'action': 'remove'});
                return true;
               
            } else {
              
                helpBlck({'id': p.id, 'msg': 'Invalid AADHAR Number', 'type': 'error'});
            }
        }
        else if(p.type == "GSTIN"){
           var _pincode = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
            if (_pincode.test(p.data)) {
           
                helpBlck({id: p.id, 'action': 'remove'});
                return true;
               
            } else {
              
                helpBlck({'id': p.id, 'msg': 'Invalid GSTIN Number', 'type': 'error'});
            }
        }
    else if(p.type == "NAME")
    {
        if(p.data.length>3)
		{
			
				helpBlck({id:p.id,'action':'remove'});
				return true;
			
		}
		else
		{
			if(p.error == true)
			{
				//error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Please enter a valid name.', 'type':'error'});
			}
			
		}
    }
	// else if(p.type == "MOBILE")
 //    {
 //        if(valR.otp.test(p.data))
	// 	{
			
	// 			helpBlck({id:p.id,'action':'remove'});
	// 			return true;
			
	// 	}
	// 	else
	// 	{
	// 		if(p.error == true)
	// 		{
	// 			//error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
 //                helpBlck({'id':p.id, 'msg':'Please enter a valid contact number.', 'type':'error'});
	// 		}
			
	// 	}
 //    }
	else if(p.type == "QUERY")
    {
        if(p.data.length>=10)
		{
			
				helpBlck({id:p.id,'action':'remove'});
				return true;
			
		}
		else
		{
			if(p.error == true)
			{
				//error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Query should be minimum 10 characters.', 'type':'error'});
			}
			
		}
    }
    else if(p.type == "TNDC")
        {
            if(p.data===true)
                {
                    helpBlck({id:p.id,'action':'remove'});
				    return true;
                }
                else{
                    
                    if(p.error == true)
                        {
                            //error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                            helpBlck({'id':p.id, 'msg':'Please provide your consent to contact you', 'type':'error'});
                        }
                    
                }   
        }   else if(p.type == "DOB")
        {   
            // var _mobile = /^[0-9 -]+$/;
            //             if (_mobile.test(p.data))
            //             {
            //                 if (p.error == true )
            //                 {

            //                    // helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
            //                     helpBlck({'id':p.id, 'msg':'Please enter a valid DOB.', 'type':'error'});
            //                 }
            //                 else
            //                 {
            //                     helpBlck({id: p.id, 'action': 'remove'});
            //                     return true;
            //                 }
            //             } else
            //             {
            //                 if (p.error == true)
            //                 {
            //                     //helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
            //                     helpBlck({'id':p.id, 'msg':'Please enter a valid DOB 1.', 'type':'error'});
            //                 }

            //             }
             if(p.data.length>0)
        {
            
                helpBlck({id:p.id,'action':'remove'});
                return true;
            
        }
        else
        {
            if(p.error == true)
            {
                //error({'id':p.id, 'action':'set', 'msg':'Invalid Mobile Number'});
                helpBlck({'id':p.id, 'msg':'Please enter a valid DOB.', 'type':'error'});
            }
            
        }
     
        }else if(p.type == "GENDER"){
        if(p.data != "" && (p.data in gndr_typ)){
      
            helpBlck({id:p.id,'action':'remove'});
            return true;
        }else{
       
            if(p.error === true){
          
          helpBlck({'id':p.id, 'msg': p.msg || 'Invalid Type', 'type':'error'});
            }
        }
    }
	
	return false;

	}
		 //-----------------------------------Switch Box-------------------------------------//

// 		 $(".register-btn").click(function(){
// $("#register_form_Section").show();
// $("#login-section").hide();
// $("#login-otp-view").hide();
// });

// $(".login-btn").click(function(){
// $("#register_form_Section").hide();
// $("#login-section").show();
// $("#login-otp-view").hide();
// });

// $("#login_continue").click(function(){
// $("#login-view").hide();
// $("#login-otp-view").show();
// });

 


// $(".forgot-pass").click(function(){
// $("#login-view").hide();
// $("#password-recovery-view").show();
// $("#login-otp-view").hide();
// });

// $(".show-login").click(function(){
// $("#login-view").show();
// $("#password-recovery-view").hide();
// $("#login-otp-view ").hide();
// });

// $(".password-set-show-btn").click(function(){
// $("#password-recovery-view").hide();
// $("#set-newpassword-view").show();
// $("#login-otp-view ").hide();
// });

// $(".show-password-recovery-view").click(function(){
// $("#password-recovery-view").show();
// $("#set-newpassword-view").hide();
// $("#login-otp-view ").hide();
// });

           var login = $('#login-view');
                var displayOTPForm = function () {
                   $("#login-view").hide();
                    $("#login-otp-view").show();
                    $('#password-recovery-view').hide();
                    $('#set-newpassword-view').hide();
                }

                var displaySignInForm = function () {
                    $('#login-view').show();
                   $("#login-otp-view").hide();
                    $('#password-recovery-view').hide();
                    $('#set-newpassword-view').hide();
                }

                var displayForgetPasswordForm = function () {
                    $('#login-screen').hide();

                    $('#OTP-screen').hide();
                    $('#forgot-password-screen').show();
                    $('#set-new-passoword-screen').hide();
                }

                var displayForgetPasswordResetForm = function () {
                    $('#login-screen').hide();
                    $('#OTP-screen').hide();
                    $('#forgot-password-screen').hide();
                    $('#password-recovery-view').hide();
                    $('#set-newpassword-view').show();
                    
                }
   
            var switchBox = function (aBox)
                {

                    if (aBox == "Login")
                    {
                    	 
                        loginparam = resetparam = '';
                        login_rsndattemp = 0;

                     
                        displaySignInForm();
                    } else if (aBox == "OTP")
                    {
                        displayOTPForm();
                    } else if (aBox == "Reset")
                    {
                    		var reset_rsndattemp = 0;
                        displayForgetPasswordResetForm();
                    }
                    else if (aBox == "Rotp")
                    {
                        displayForgetPasswordForm();
                    }
                }
     
         //--------------------------------------- Validate Error Show Helpblock -------------------//

	var helpBlck = function(h){
	
    if(typeof h !== 'undefined')
	{

        if(h.action=='remove')
                    {
                        if(typeof h.id==='undefined'){
                        $('span.help-block').html('').removeClass('text-info');
                        $('span.help-block').each(function(){
                            $(this).closest('.form-group').removeClass('text-danger');
                        })




                        }else{
                           

                            if ($('span[data-for=' + h.id + ']').closest('.form-group').hasClass('text-danger')) {
                                    $('span[data-for=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                                    $('span[data-for=' + h.id + ']').html('').removeClass('text-info');
                                }

                        }
                    }

    if(typeof h.type === 'undefined')
		{
			h.type = '';
		}

		if(typeof h.id !== 'undefined')
		{
			if(typeof h.msg !== 'undefined')
			{
				if(h.type == 'error')
				{
                       
						$('span[data-for=' + h.id + ']').closest('.form-group').addClass('text-danger');
                        $('span[data-for=' + h.id + ']').html(h.msg).removeClass('text-info');

				}
                else if(h.type=='bulk')
                    {
                        $('span[data-bulk='+h.id+']').closest('.form-group').removeClass('text-danger');
                        $('span[data-bulk='+h.id+']').html(h.msg).addClass('text-info');
                    }
				else
				{
                    
					$('span[data-for=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                    $('span[data-for=' + h.id + ']').html(h.msg).addClass('text-info');

				}



			}
		}
    }
} 

	    var Login= function(){

	    	var req={
            action:false,
            type:'',
            login_cntinue_la:$('#login_continue').ladda(),
             lgotp_vrfy:$('#login_otp_bttn').ladda(),
            frgt_continue:$('#frgt_send-btn').ladda(),
            frgt_rst_otp:$('#frgtpass_Resend_OTP').ladda(),
            reset_password:$('#ChangePasswordbtn').ladda(),
            rgst_btn:$('#regstrn').ladda(),
            time:30,
            // resend_text:'Resend OTP',
             cog:'<i class="fa fa-cog fa-spin"></i>'
            };


		     $('#login_continue').click(function(e){
              e.preventDefault();
            if(req.action===false && req.type=='')
                {

                    var params={valid:true};
                    
                    params.login_input=$('#login_mobile').val();
                    params.login_pass=$('#login_pass').val();
                    
                    if(!validate({'id':'login_mobile','type':'MOBILE','data':params.login_input, 'error':true})) { params.valid = false;}
                    if(!validate({'id':'login_pass','type':'PASS','data':params.login_pass, 'error':true})) { params.valid = false;}
                    console.log(params)
                    if(params.valid === true){
                            $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            req.login_cntinue_la.ladda('start');
                            req.action=true;
                            req.type='login_attempt';
                            $.ajax({
                                url:"Login/Validate_login",
                                data:params,
                                dataType:'json',
                                "type":'POST',
                                success:function(response)
                                {
                                   
                                      if (response) {
                                      	console.log(response)
                                       
                                                if (response.error == 1)
                                                {
                                                    //toastr.error(response.error_desc);

                                                  req.action=false;
                                                  req.type=''; 
                                                     req.login_cntinue_la.ladda('stop');

                                                      toastr.error(response.error_desc);
                                              

                                                    
                                                } else if (response.error == 2)
                                                {
                                                    window.location.reload(true);

                                                } else if (response.error == 0) {

                                                    if (response.url)
                                                    {
                                                        window.location.replace(response.url);

                                                    } else {

                                                        window.location.replace("Dashboard");
                                                    }

                                                } else if (response.error == 3){
                                                 
                                                    req.action=true;
                                                    req.type='Two Factor Login';

                                                    toastr.success(response.msg);

                                                    switchBox('OTP');

                                                    loginparam=response.loginparam;
                                                    req.time=30;

                                                   //  timeout(req.time,'#m_login_Resend_OTP');
                                                    resend=1;
                                                    req.login_cntinue_la.ladda('stop');
                                                   
                                                       //login_otp();

                                                }
                                              req.login_cntinue_la.ladda('stop');

                                            }else{

                                                  req.login_cntinue_la.ladda('stop');
                                               
                                                  toastr.error(response.error_desc);

                                                req.action=false;
                                                req.type='';
                                            }
                                },
                                error:function(err)
                                {
                                    req.action=false;
                                    req.type='';
                                    req.login_cntinue_la.ladda('stop');
                                }
                            })
                        }

                    
                }else{
                   
                     toastr.error('Please Wait!!');

                
                }
        });





	/*********************************check login Otp*************************************/    


       $('#login_otp_bttn').click(function(e){
                    e.preventDefault();
          
                     if(req.action===true && req.type=='Two Factor Login'){
                        var params={};
                        params.op1=loginparam;
                        params.otp=$('#otp_login').val();
                        params.valid=true;
                        if(!validate({'id':'otp_login','type':'OTP','data':params.otp, 'error':true})) { params.valid = false;}
                        if(params.valid===true)
                        {
                            req.type='Two Factor Login init';
                            $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            req.lgotp_vrfy.ladda('start');
                            
                            $.ajax({ 
                                "url":"Login/Validate_otp",
                                "dataType":"json",
                                "data":params,
                                "type":'POST',
                                success:function(response)
                                {
                                    if(response)
                                    { 
                                        
                                        if(response.error==0)
                                            {
                                                
                                                loginparam = '';
                                                if (response.url)
                                                {
                                                    window.location.replace(response.url);
                                                } else {
                                                    window.location.replace("Dashboard");
                                                }
             
                                            }
                                        else if(response.error==2)
                                            {
                                                window.location.reload(true);
                                            }
                                        else{

                                            req.lgotp_vrfy.ladda('stop');
                                            toastr.error(response.error_desc);
                                            req.action=true;
                                            req.type='Two Factor Login';
                                        }
                                    }
                                },
                                error:function(err)
                                {
                                    req.type='Two Factor Login';
                                }
                            })
                          
                            
                        }
                         
                     }else{

                     	 toastr.error('Please Wait!!');

                        
                     }
                })

/**************************end check login Otp*******************************/     
/**************************resend otp****************************************/

                $('#login_Resend_OTP').click(function (e) {
                    e.preventDefault();

                     if(req.action===true && req.type=='Two Factor Login'){
                   
                    var params = {'valid': true};
                    params.Mobile = loginparam;

                    if (params.valid == true) {
                        if (resend >= 0 && resend < 3)
                        {
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');

                            $.ajax({
                                method: 'POST',
                                url: 'Login/Resend_otp_fr_login',
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                    if (response.error == 0)
                                    {
                                        resend += 1;
                                       
                                         toastr.success( response.msg);
                                    } else if (response.error == 2)
                                    {
                                        window.location.reload(true);
                                    } else {

                                        req.action=true;  req.type='Two Factor Login';
                                        resend = 0;
                                        toastr.error(response.error_desc);
                                        la.ladda('stop');
                                    }
                                    la.ladda('stop');
                                }
                                la.ladda('stop');
                            }).fail(function (err) {
                                la.ladda('stop');
                                throw err;
                            }); 
                        } else {
                            

                            toastr.error('Maximun resend attempt reached.');
                        }
                    }
                     }else{

                       

                          toastr.error('Please Wait!!');

                     }
                });

                $('#otp-password').click(function () {
                	req.action=false;
                    req.type=''; 
                     switchBox('Login');
                })



/*****************************end resend otp*********************************/
//------------------------------------- Forget Password --------------------------//

            
            
        $("#forgot-password").click(function(e){
              e.preventDefault();
        if(req.action===false && req.type==''){
            req.action=true;
            req.type='Forgot Screen';
           
        $("#password-recovery-view").show();
        $("#login-view").hide();
        $("#login-otp-view").hide();
        }else{

        	toastr.error('Please Wait!!');

                      
            }
        });

            $('#frgt_send-btn').click(function(e){
            e.preventDefault();

            if(req.action===true && req.type==='Forgot Screen')
                {
                    var fgparams={valid:true};
                    fgparams.frgtinpt=$('#resetmob').val();
             if(!validate({'id':'resetmob','type':'MOBILE','data':fgparams.frgtinpt, 'error':true,'focus':true})) { fgparams.valid = false;}
                    
                    if(fgparams.valid===true)
                        {
                            
                            $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            req.frgt_continue.ladda('start');
                            req.type='Forgot Screen OTP sent';
                            $.ajax({
                               "url":"login/Forget_Password_OTP",
                                "dataType":"JSON",
                                "data":fgparams,
                                "type":'POST',
                                "success":function(resp)
                                {
                                    if(resp.error==0)
                                    {
       //                              	resetparam = response.reset_rsndattemp;
							// toastr.success(response.msg);		
                            reset_rsndattemp = 0;
       									console.log(resp);
       								

                                        resetparam=fgparams.frgtinpt;
                                        req.frgt_continue.ladda('stop');
                                     
                                        req.type='Forgot OTP Screen';
                                        switchBox('Reset');
                                        req.time=30;
                                      
                                        resend=1;
                                    }
                                    else if(resp.error==2)
                                    {
                                        window.location.reload(true);
                                    }
                                    else{
                                        req.frgt_continue.ladda('stop');
                                        req.action=true;
                                        req.type='Forgot Screen'
                                        toastr.error(resp.error_desc);
                                        
                                    }
                                },
                                "error":function(err)
                                {
                                    req.frgt_continue.ladda('stop');
                                    req.action=true;
                                    req.type='Forgot Screen'
                                }
                                
                            });
                            
                        }
                    
                    
                }else{

                	 toastr.error('Please Wait!!');

                  
                }
        })
  
      

        $("#frgt_bck").click(function(e){
            e.preventDefault();
        if(req.action===true && req.type=='Forgot Screen'){
            req.action=false;
            req.type='';
            $("#password-recovery-view").hide();
	        $("#login-view").show();
	        $("#login-otp-view").hide();

      
        }else{
        		toastr.error('Please Wait!!');
                        
             }
        });

        $('#frgtpass_Resend_OTP').click(function (e) {
                    e.stopPropagation();
				    e.preventDefault();
				  

                     if(req.action===true && req.type=='Forgot OTP Screen'){
                   
                    var params = {'valid': true};
                    params.Mobile = resetparam;

                    if (params.valid == true) {
                        if (reset_rsndattemp >= 0 && reset_rsndattemp < 3)
                        {
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                             req.frgt_rst_otp.ladda('start');
                            // var la = $(this).ladda();
                            // la.ladda('start');

                            $.ajax({
                                method: 'POST',
                                url: 'Login/Resend_otp_fr_frgtpass',
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                    if (response.error == 0)
                                    {
                                        reset_rsndattemp += 1;
                                       
                                         toastr.success( response.msg);
                                    } else if (response.error == 2)
                                    {
                                        window.location.reload(true);
                                    } else {

                                        req.action=true;  req.type='Forgot OTP Screen';
                                        reset_rsndattemp = 0;
                                        toastr.error(response.error_desc);
                                         req.frgt_rst_otp.ladda('stop');
                                        // la.ladda('stop');
                                    }
                                    //la.ladda('stop');
                                     req.frgt_rst_otp.ladda('stop');
                                }
                               // la.ladda('stop');
                                 req.frgt_rst_otp.ladda('stop');
                            }).fail(function (err) {
                            	 req.frgt_rst_otp.ladda('stop');
                               // la.ladda('stop');
                                throw err;
                            }); 
                        } else {
                            

                            toastr.error('Maximun resend attempt reached.');
                        }
                    }
                     }else{

                       

                          toastr.error('Please Wait!!');

                     }
                });



	// $('#frgtpass_Resend_OTP').click(function (e) {
 //       e.stopPropagation();
 //       e.preventDefault();
 //        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
 //            var la = $(this).ladda();

 //       if (reset_rsndattemp >= 0 && reset_rsndattemp < 2)
 //       {
 //             la.ladda('start');
 //           $.post('Login/Resend_otp_fr_frgtpass', {data_otp: resetparam}, function (response) {
 //               if (response)
 //               {
 //                   if (response.error == 0)
 //                   {
 //                     reset_rsndattemp += 1;
                     
 //                       toastr.success(response.msg);

 //                   } else if (response.error == 2)
 //                   {
 //                       window.location.reload(true);
 //                   } else {
                    
 //                       reset_rsndattemp = 0;
 //                       toastr.error(response.error_desc);
 //                        la.ladda('stop');
 //                   }
 //                    la.ladda('stop');
 //               }

 //           }, 'json').fail(function (error) {
 //               la.ladda('stop');
 //           });
 //       } else {
 //           toastr.error('Maximun otp attempt limit reached');
 //       }


 //   })	

//-------------------------------------End Forget Password --------------------------//
  //--------------------------------------- Chnage Password With Otp -------------------------//

            $('#ChangePasswordbtn').click(function(e){
            e.preventDefault();
            if(req.action===true && req.type=='Forgot OTP Screen')
                {
                    var reset_params={valid:true};
                    reset_params.frgtinpt=resetparam;
                    reset_params.otp=$('#reset_otp').val();
                    reset_params.newpass=$('#newpassword').val();
                    reset_params.cnfpass=$('#confirm-password').val();
                    
                    if(reset_params.frgtinpt!='')
                        {
                            
                            if(!validate({'id':'reset_otp','type':'OTP','data':reset_params.otp, 'error':true})) { reset_params.valid = false;}
                            if(!validate({'id':'newpassword','type':'PASS','data':reset_params.newpass, 'error':true})) { reset_params.valid = false;}
                            if(!validate({'id':'confirm-password','type':'CONPASS','data':reset_params.cnfpass, 'error':true})) {reset_params.valid = false;}
                   
                            if(reset_params.valid===true)
                                {
                                    
                                    $(this).addClass('ladda-button').attr('data-style','zoom-in');
                                    req.reset_password.ladda('start');
                                    req.type='Forgot OTP Validating';
                                    $.ajax({
                                        "url":"Login/validate_resetotp",
                                        "type":'POST',
                                        "data":reset_params,
                                        "dataType":"json",
                                        success:function(response)
                                        {
                                            if(response.error==0)
                                                {

                                                    
                                                   toastr.success(response.msg);
                                              
                                                req.reset_password.ladda('stop');
                                                    req.type='';
                                                    req.action=false;
                                                resetparam = '';
                                                setTimeout(function () {
                                                    window.location.href = 'Login';
                                                }, 2000);

                                                }
                                            else if(response.error==2)
                                                {
                                                    window.location.reload(true)
                                                }
                                            else{
                                                req.reset_password.ladda('stop');
                                                 req.action=true;
                                                req.type='Forgot OTP Screen';

                                                  toastr.error(response.error_desc);
                                               
                                            }
                                        },
                                        error:function(err)
                                        {
                                            req.reset_password.ladda('stop');
                                            req.type='Forgot OTP Screen';
                                        }
                                    })
                                }
                            
                            
                        }else{

                        	 toastr.error('Unable to process your request');

                           
                        }
                    
                    
                }else{

                	toastr.error('Please Wait!!');


                  
                }
            
        })


       
         $('#forgotback-btn').click(function (e) {
              e.preventDefault();
        if(req.action===true && req.type=='Forgot OTP Screen'){
            req.action=true;
            req.type='Forgot Screen';
           
            
        $("#forgot-password-screen").show();
        $("#login-screen").hide();


        $("#password-recovery-view").show();
		$("#set-newpassword-view").hide();
		$("#login-otp-view ").hide();

        }else{
        				toastr.error('Please Wait!!');      

                      
                     }
                    

        })

		}
  

		 return {   
	      init:function(){
	          Login();
	      }
	    };  
	}();
	$(document).ready(function(){
	    Login_initialize.init();
	})
  </script>
     
</body>
</html>
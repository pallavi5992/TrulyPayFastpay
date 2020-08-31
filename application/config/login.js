var Login_initialize=function()
{

	var valR = {
                email:/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
                email_old:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z]{2,6}$/,
                otp:/[0-9]/,
                mobile:/^[0-9]{10}$/,
                usr_email: /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    
            }

    var gndr_typ={'Male': 'Male','Female': 'Female'};

        $('#pincode').on('keyup',function(){
                             var pin = $("#pincode").val();
                             var dataString = {'pincode': pin};
                        if($("#pincode").val().length==6){
                            $.ajax({
                                url: 'Login/pin_submit',
                                type: 'post',
                                dataType: "json",
                                data:dataString,
                                success: function(data) {
                                    console.log(data);
                                    if(data.error == 0) {
                                     
                                       $("#shop_state").val(data.msg.statename);
                                    } else {
                                     
                                     $("#shop_state").val();
                                       // toastr.error(data.error_desc);

                                        new PNotify({
                                                        title: 'Oops!',
                                                        text: data.error_desc,
                                                        icon: 'icon-blocked',
                                                        type: 'error'
                                                    });
                                    }
                                }
                              });
                        }
                        else {
                            
                            $("#shop_state").val('');
                        }
                    });

    $("#login_pass,#newpassword").on('keypress blur keyup', function(e)
    {
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

      $("#confirm-password").on('keypress blur keyup', function(e){
        var k = e.keyCode||e.which;
        var id = $(this)[0].id;
        var str = $(this)[0].value;
        var length = str.length;
        
        if(str==$('#newpassword').val())
            {
                 helpBlck({id:id,'action':'remove'});
            }
       if(length==0)
           {
               helpBlck({id:id,'action':'remove'});
           }
         if(e.type == 'blur')
        {
            if(length>0){
            if(str!=$('#newpassword').val())
            {
                err=(str != "")?"Password Mismatch.":"Confirm Password is required."
                helpBlck({'id':id, 'msg':err, 'type':'error'}); 
            }else{
                helpBlck({id:id,'action':'remove'});
            }
            }else{
                 helpBlck({id:id,'action':'remove'});
            }
        }
      else if(e.type == 'keypress')
        { 
        if(k==13){
                if(length>0){
                    $('#ChangePasswordbtn').trigger('click');
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
                            $('#send-btn').trigger('click');
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

    $("#otp_login,#reset_otp").on('keypress blur keyup', function(e){
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
                            $('#m_login_signin_login_otp').trigger('click');
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

    $("#fullname").on('keypress blur keyup', function(e){
        var k = e.keyCode||e.which;
        var id = $(this)[0].id;
        var str = $(this)[0].value;
        var length = str.length;
        
       if(length!=1)
           {
               helpBlck({id:id,'action':'remove'});
           }
    
         if(e.type == 'blur')
        {
            if(length>0){
                
            if(length<2 || length>50)
            {
                msg=(length>50)?'Name cannot be more than 50':'Name cannot be less than 2';
                helpBlck({'id':id, 'msg':msg, 'type':'error'});
                
            }else{
                helpBlck({id:id,'action':'remove'});
            }
                
            }else{
                 helpBlck({id:id,'action':'remove'});
            }
        }
      else if(e.type == 'keypress')
                {
                    if(id == 'fullname')
                    {
                             if(k==13){
                                 if(length>1){
                                $('#regstrn').trigger('click');
                                 }
                            }
                    }
                    
                    if(k != 8 && k != 9)
                    {
                        if(length == 50)
                        {
                            return !1
                        }
                    }

                    return !0
            }
        
    }); 

     $("#lastname").on('keypress blur keyup', function(e){
        var k = e.keyCode||e.which;
        var id = $(this)[0].id;
        var str = $(this)[0].value;
        var length = str.length;
        
       if(length!=1)
           {
               helpBlck({id:id,'action':'remove'});
           }
    
         if(e.type == 'blur')
        {
            if(length>0){
                
            if(length<2 || length>50)
            {
                msg=(length>50)?'Last Name cannot be more than 50':'Name cannot be less than 2';
                helpBlck({'id':id, 'msg':msg, 'type':'error'});
                
            }else{
                helpBlck({id:id,'action':'remove'});
            }
                
            }else{
                 helpBlck({id:id,'action':'remove'});
            }
        }
      else if(e.type == 'keypress')
                {
                    if(id == 'lastname')
                    {
                             if(k==13){
                                 if(length>1){
                                $('#regstrn').trigger('click');
                                 }
                            }
                    }
                    
                    if(k != 8 && k != 9)
                    {
                        if(length == 50)
                        {
                            return !1
                        }
                    }

                    return !0
            }
        
    }); 


        $('#usr_email').on('keypress keyup blur', function (e) {
        var k = e.keyCode || e.which,
                id = $(this)[0].id,
                str = $(this)[0].value,
                length = str.length;


        if (e.type == 'blur' && str != null)
        {
          
             if(!valR.usr_email.test(str))
            {
                if(length>0){
                

                if(length<10 || length>100)
            {
                msg=(length>100)?'Email cannot be more than 100':'Email cannot be less than 10';
                helpBlck({'id':id, 'msg':msg, 'type':'error'});
                
            }else{
                helpBlck({id:id,'action':'remove'});
            }
                
                }else{
                     helpBlck({id:id,'action':'remove'});
                }
            }else{
                helpBlck({id:id,'action':'remove'});
                
                
            }

        }

        }); 
 

      $('#pan_num').on('keyup keypress blur', function (e) {

        var k = e.keyCode || e.which,
                id = $(this)[0].id,
                str = $(this)[0].value,
                length = str.length;
        if (e.type == 'keypress')
        {
            if (k != 8 && k != 9)
            {
                if (length == 10)
                {
                    return !1
                }
            }

            return !0
        } else if (e.type == 'blur')
        {
            var _mobile = /^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/
            if (!_mobile.test(str))
            {
                //$(this).val('')
                   helpBlck({'id':id, 'msg':"Invalid PAN Number", 'type':'error'});
            } else if (length != 10)
            {
               // $(this).val('')
                  helpBlck({'id':id, 'msg':"Invalid PAN Number", 'type':'error'});
            } else{
                helpBlck({id:id,'action':'remove'});
                
                   
            }
        }



    });

        $('#gstn_num').on('keyup keypress blur', function (e) {

        var k = e.keyCode || e.which,
                id = $(this)[0].id,
                str = $(this)[0].value,
                length = str.length;
        if (e.type == 'keypress')
        {
            if (k != 8 && k != 9)
            {
                if (length == 15)
                {
                    return !1
                }
            }

            return !0
        } else if (e.type == 'blur')
        {
            var _mobile =/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/
            if (!_mobile.test(str))
            {
                //$(this).val('')
                   helpBlck({'id':id, 'msg':"Invalid GSTIN Number", 'type':'error'});
            } else if (length != 15)
            {
               // $(this).val('')
                  helpBlck({'id':id, 'msg':"Invalid GSTIN Number", 'type':'error'});
            } else{
                helpBlck({id:id,'action':'remove'});
                
                   
            }
        }



    });


        $('#aadhar_num').on('keyup keypress blur', function (e) {

        var k = e.keyCode || e.which,
                id = $(this)[0].id,
                str = $(this)[0].value,
                length = str.length;
        if (e.type == 'keypress')
        {
            if (k != 8 && k != 9)
            {
                if (length == 14)
                {
                    return !1
                }
            }

            return !0
        } else if (e.type == 'blur')
        {
            var _mobile =/^\d{4}\s\d{4}\s\d{4}$/
            if (!_mobile.test(str))
            {
                //$(this).val('')
                   helpBlck({'id':id, 'msg':"Invalid Aadhar Number", 'type':'error'});
            } else if (length != 14)
            {
               // $(this).val('')
                  helpBlck({'id':id, 'msg':"Invalid Aadhar Number", 'type':'error'});
            } else{
                helpBlck({id:id,'action':'remove'});
                
                   
            }
        }



    });
            
        //-----------------------------------Switch Box-------------------------------------//
           var login = $('#login-screen');
                var displayOTPForm = function () {
                    $('#login-screen').hide();

                    $('#OTP-screen').show();
                    $('#forgot-password-screen').hide();
                    $('#set-new-passoword-screen').hide();
                }

                var displaySignInForm = function () {
                    $('#login-screen').show();

                    $('#OTP-screen').hide();
                    $('#forgot-password-screen').hide();
                    $('#set-new-passoword-screen').hide();
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
                    $('#set-new-passoword-screen').show();
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
                        displayForgetPasswordResetForm();
                    }
                    else if (aBox == "Rotp")
                    {
                        displayForgetPasswordForm();
                    }
                }
     
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
             lgotp_vrfy:$('#m_login_signin_login_otp').ladda(),
            frgt_continue:$('#send-btn').ladda(),
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
                    
                    if(!validate({'id':'login_mobile','type':'EMAIL|MOBILE','data':params.login_input, 'error':true})) { params.valid = false;}
                    if(!validate({'id':'login_pass','type':'PASS','data':params.login_pass, 'error':true})) { params.valid = false;}
                    console.log(params)
                    if(params.valid === true)
                        {
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
                                                new PNotify({
                                                    title: 'Error Notice',
                                                    text: response.error_desc,
                                                
                                                    icon: 'fas fa-check',
                                                    type: 'error'
                                                });


                                                    
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

                                                      new PNotify({
                                                        title: 'Success Notice',
                                                        text: response.msg,
                                                      
                                                        icon: 'fas fa-check',
                                                        type: 'success'
                                                    });

                                                    switchBox('OTP');

                                                    loginparam=response.loginparam;
                                                    req.time=30;

                                                   //  timeout(req.time,'#m_login_Resend_OTP');
                                                    resend=1;
                                                    req.login_cntinue_la.ladda('stop');
                                                    console.log(loginparam);
                                                       //login_otp();

                                                }
                                              req.login_cntinue_la.ladda('stop');
                                            }else{
                                                 req.login_cntinue_la.ladda('stop');
                                              
                                                new PNotify({
                                                        title: 'Oops!',
                                                        text: response.error_desc,
                                                        icon: 'icon-blocked',
                                                        type: 'error'
                                                    });
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
                    new PNotify({
                                title: 'Oops!',
                                text: 'Please Wait!!',
                                icon: 'icon-blocked',
                                type: 'error'
                            });
                
                }
        });



/****check login Otp*******/


  $('#m_login_signin_login_otp').click(function(e){
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
                                        console.log(response);
                                    console.log(response) 
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

                                            new PNotify({
                                            title: 'Oops!',
                                            text: response.error_desc,
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });

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

                         new PNotify({
                                            title: 'Oops!',
                                            text: 'Please Wait!!',
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });
                     }
                })

/**************************end check login Otp*******************************/     

/**************************resend otp****************************************/

                $('#m_login_Resend_OTP').click(function (e) {
                    e.preventDefault();

                     if(req.action===true && req.type=='Two Factor Login'){
                   
                    var params = {'valid': true};
                    params.Mobile = loginparam;

                    if (params.valid == true) {
                        if (resend >= 0 && resend < 2)
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
                                        new PNotify({
                                            title: 'Error Notice',
                                            text: response.msg,
                                        
                                            icon: 'fas fa-check',
                                            type: 'success'
                                        });
                                    } else if (response.error == 2)
                                    {
                                        window.location.reload(true);
                                    } else {

                                        req.action=true;  req.type='Two Factor Login';
                                        resend = 0;
                                        new PNotify({
                                            title: 'Error Notice',
                                            text: response.error_desc,
                                            
                                            icon: 'fas fa-check',
                                            type: 'error'
                                        });
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
                            new PNotify({
                                title: 'Error Notice',
                                text: 'Maximun resend attempt reached.',
                           
                                icon: 'fas fa-check',
                                type: 'error'
                            });
                        }
                    }
                     }else{

                         new PNotify({
                                            title: 'Oops!',
                                            text: 'Please Wait!!',
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });
                     }
                });

                $('#otp-password').click(function () {
                     switchBox('Login');
                })



/*****************************end resend otp*********************************/
//------------------------------------- Forget Password --------------------------//

            
            
        $("#forgot-password").click(function(e){
              e.preventDefault();
        if(req.action===false && req.type==''){
            req.action=true;
            req.type='Forgot Screen';
        $("#forgot-password-screen").show();
        $("#login-screen").hide();
        }else{

                         new PNotify({
                                            title: 'Oops!',
                                            text: 'Please Wait!!',
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });
                     }
        });

            $('#send-btn').click(function(e){
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

                                        new PNotify({
                                            title: 'Oops!',
                                            text: resp.error_desc,
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });
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

                    new PNotify({
                            title: 'Oops!',
                            text: 'Please Wait!!',
                            icon: 'icon-blocked',
                            type: 'error'
                        });
                }
        })

      

        $("#frgt_bck").click(function(e){
            e.preventDefault();
        if(req.action===true && req.type=='Forgot Screen'){
            req.action=false;
            req.type='';
        $("#forgot-password-screen").hide();
        $("#login-screen").show();
        }else{

                         new PNotify({
                                            title: 'Oops!',
                                            text: 'Please Wait!!',
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });
                     }
        });

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

                                                    
                                                   
                                                new PNotify({
                                                    title: 'Error Notice',
                                                    text: response.msg,
                                                    icon: '',
                                                    type: 'success'
                                                });
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

                                                new PNotify({
                                                        title: 'Oops!',
                                                        text: response.error_desc,
                                                        icon: 'icon-blocked',
                                                        type: 'error'
                                                    });
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

                            new PNotify({
                                        title: 'Oops!',
                                        text: 'Unable to process your request',
                                        icon: 'icon-blocked',
                                        type: 'error'
                                    });
                        }
                    
                    
                }else{

                    new PNotify({
                                        title: 'Oops!',
                                        text: 'Please Wait!!',
                                        icon: 'icon-blocked',
                                        type: 'error'
                                    });
                }
            
        })


       
         $('#forgotback-btn').click(function (e) {
              e.preventDefault();
        if(req.action===true && req.type=='Forgot OTP Screen'){
            req.action=true;
            req.type='Forgot Screen';
           
            $("#set-new-passoword-screen").hide();
        $("#forgot-password-screen").show();
        $("#login-screen").hide();

        }else{

                         new PNotify({
                                            title: 'Oops!',
                                            text: 'Please Wait!!',
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });
                     }
                    

        })

    //******************************************registration****************************************// 
    $(".register-btn").click(function(){
            $("#register").toggle();
    });

      // $("#register-btn").click(function(e){
      //         e.preventDefault();
      //   if(req.action===false && req.type==''){
      //       req.action=true;
      //       req.type='Register Screen';
      //     $("#register").toggle();
      //   }else{

      //                    new PNotify({
      //                                       title: 'Oops!',
      //                                       text: 'Please Wait!!',
      //                                       icon: 'icon-blocked',
      //                                       type: 'error'
      //                                   });
      //                }
      //   });
        
      $('#gender').on('change', function(e){
  
      var id = e.target.id;
      var value = e.target.value;
      console.log(id);
      console.log(value);
    
                    if(value == ''){
                    

                       helpBlck({id:id,'msg':'Please select Gender',type:'error'});

                    }else if(value in gndr_typ){
                        
                         helpBlck({id:id,'action':'remove'});
                        
                    }else{
                      
                       helpBlck({id:id,'msg':'Invalid Type',type:'error'}); 
                    }
        });

                    
               
     $('#regstrn').click(function(e){
            e.preventDefault();


            if(req.action===false && req.type=='')
                {
                    var reg_params = {};
                    reg_params.valid=true;
                    reg_params.reg_mob=$('#mobile_num').val();
                    reg_params.reg_name=$('#fullname').val();
                     reg_params.reg_lname=$('#lastname').val();
                    reg_params.reg_email=$('#usr_email').val();
                    reg_params.dob=$('#dob').val();
                    reg_params.gender=$('#gender').val();
                    reg_params.shopname=$('#shopname').val();
                    reg_params.shop_addrs=$('#shop_addrs').val();
                    reg_params.pincode=$('#pincode').val();
                    reg_params.shop_state=$('#shop_state').val();
                    reg_params.shopcity=$('#shopcity').val();
                    reg_params.prmnt_addrs=$('#prmnt_addrs').val();
                    reg_params.pan_num=$('#pan_num').val();
                    reg_params.aadhar_num=$('#aadhar_num').val();
                    reg_params.gstn_num=$('#gstn_num').val();
                    
    if(!validate({'id':'mobile_num','type':'MOBILE','data':reg_params.reg_mob, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'fullname','type':'NAME','data':reg_params.reg_name, 'error':true})) { reg_params.valid = false;}
      if(!validate({'id':'lastname','type':'NAME','data':reg_params.reg_lname, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'usr_email','type':'EMAIL','data':reg_params.reg_email, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'dob','type':'DOB','data':reg_params.dob, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'gender','type':'GENDER','data':reg_params.gender, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'shopname','type':'BNAME','data':reg_params.shopname, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'shop_addrs','type':'ADDRESS','data':reg_params.shop_addrs, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'pincode','type':'PINCODE','data':reg_params.pincode, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'shopcity','type':'CITY','data':reg_params.shopcity, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'prmnt_addrs','type':'ADDRESS','data':reg_params.prmnt_addrs, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'pan_num','type':'PAN','data':reg_params.pan_num, 'error':true})) { reg_params.valid = false;}
    if(!validate({'id':'aadhar_num','type':'AADHAR','data':reg_params.aadhar_num, 'error':true})) { reg_params.valid = false;}
    if (reg_params.gstn_num != '') {
        if(!validate({'id':'gstn_num','type':'GSTIN','data':reg_params.gstn_num, 'error':true})) { reg_params.valid = false;}
    }

       
              
                    if(reg_params.valid===true)
                        {
                             console.log(reg_params);
                           $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            req.rgst_btn.ladda('start');
                           req.action=true;
                           req.type='Resgister Initiated';
                          
                            
                           $.ajax({
                              "url":"Login/Create_usr",
                              "method":"POST",
                              "dataType":"JSON",
                              "data":reg_params,   
                              "success":function(response)
                               {
                                   if(response.error==0)
                                       {


                                           req.action=false;
                                           req.type='';
                                           req.rgst_btn.ladda('stop');
                                           //toastr.info(response.msg);
                                           new PNotify({
                                            title: '',
                                            text: response.msg,
                                            icon: '',
                                            type: 'success'
                                        }); 
                                              $('#register_form')[0].reset();

                                       }
                                   else if(response.error==2)
                                       {
                                           window.location.reload(true);
                                       }
                                   else{

                                         req.action=false;
                                           req.type='';
                                       req.rgst_btn.ladda('stop');
                                       // req.action=true;
                                       // req.type='Register Screen';
                                       //toastr.error(response.error_desc);
                                       new PNotify({
                                            title: 'Oops!',
                                            text: response.error_desc,
                                            icon: 'icon-blocked',
                                            type: 'error'
                                        });
                                   }
                               },
                               "error":function(err)
                               {
                                   
                               }
                           });
                           }
                           }
                           });   


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


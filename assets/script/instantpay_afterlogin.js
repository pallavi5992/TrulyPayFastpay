 
            var switchBox =   function(aBox){
                $("input").val('').removeClass("edited");
                $('textarea').val('').removeClass('edited');
                $('#fgender').val('Female');
                $('#mgender').val('Male');
                date_val="";
                if(aBox == "Login"){
                    $('#register').slideUp('fast');
                    $('#Login').slideDown("fast");
                    $('#register_otp_panel').slideUp("fast");           
                
                }else if(aBox == "Register"){
                    $('#Login').slideUp("fast");
                    $('#register').slideDown('fast');
             
                }else if(aBox == "otp"){
                    $('#register,#Login').slideUp("fast");
                    $('#register_otp_panel').slideDown("fast"); 
                }
            }
            
            var after_login_obj={
                action:false,
                type:""
            };
            
            let bankData = {};
            var benelist = [];
            let instPyBenef = {};
            var accnt_det = {};
            var banklist_with_ifsc = {};
            var remitid;
            var reminumber ;
            var reminame ;
            var htmlbnk;
            var process = false;
                var wait = false;
            var retry = false;
            var request = {
                'allow': true,
                'call': '',
                'type': '',
                'mode': ''
            }
            var TXN_REQUEST = {};
            


            var remitter_limit;


            var table;
      
            var ValidateAll = function (v, act) 
            {
                var r = "";
                if (v == false) {
                    return false;
                }
                if (v == "") {
                    return false;
                }
                if (act == 'name' || act == 'city') {
                    r = /^[A-Za-z .\-\/]+$/;
                } else if (act == 'mobile') {
                    r = /^[6789][0-9]{9}$/;
                } else if (act == 'address') {
                    r = /^[A-Za-z0-9 &\-\/\',]+$/;
                } else if (act == 'pincode') {
                    r = /^\d{6}$/;
                } else if (act == 'otp') {
                    r = /^\d{6,10}$/;
                } else if (act == 'pin') {
                    r = /^\d{4}$/;
                } else if (act == 'email') {
                    r = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                } else if (act == 'alnum') {
                    r = /^[0-9a-zA-Z]+$/;
                } else if (act == 'mobile') {
                    r = /^[6789][0-9]{9}$/;
                } else if (act == "ifsc") {
                    r = /^[A-Za-z0-9]+$/;
                } else if (act == "amount") {
                    r = /^\d+$/;
                } else {
                    return false;
                }
                return r.test(v);
            }
    
    /*********************************** add benef*************************************************/
    
      var GET_BANK_WITH_CIFSC = function () {
            banklist_with_ifsc = {};
            ifsc_bankname_data = {};
            $.ajax({
                method: 'POST',
                url: 'MoneyTransfer/BankIfsc',
                dataType: 'JSON',
            }).done(function (response) {
        
                if (response) {
                    if (response.error == 0) {

                        $.each(response.msg, function (k, v) {
              
                            banklist_with_ifsc[v.bank_name] = v;
                            ifsc_bankname_data[v.ifsc] = v;
                            

                        });
                        MANAGE_BANK();
                    } else if (response.error == 2) {
                        // window.location.reload('true');
                    } else {
                        toastr.error(response.error_desc)

                    }
                }
            }).fail(function (err) {
                throw err;
            });
        }

      var MANAGE_BANK = function () {

            $("#bbnksel").html('<option selected="selected">Select Bank Name</option>');
      
            $.each(banklist_with_ifsc, function (k, v) {

                $("#bbnksel").append($('<option>', {
                    text: v.bank_name,
                    value: v.bank_name
                })); 
        
           });
      
      
            $("#bbnksel").on('change', function (e) {
                var value = $(this).val();
                find_ifsc(e, value);
            });
        }
    
      $('#bbnkifsc').on('keypress keyup', function (e) {
            this.value = this.value.toUpperCase();
            var k = e.keyCode || e.which;
            var id = $(this)[0].id;
            var str = $(this)[0].value;
            var length = str.length;

            find_bank(e, str, length);
            if (e.type == 'keypress') {
                if (k != 8 && k != 9) {
                    k = String.fromCharCode(k);
                    var regex = /[A-Za-z0-9]/;
                    if (!regex.test(k)) {
                        return false;
                    }
                    if (length > 10) {
                        return false;
                    }
                }
                return true;
            }
        });

      var find_bank = function (e, str, length) {
            bankData = {};
            if (e.type == 'keyup') {
                var b = '';
                $("#bbnksel").val('');
            if(length==11)
             {
                if (/^[A-Za-z0-9]+$/.test(str)) 
                {
                    $.ajax({
                        method: 'POST',
                        url: 'MoneyTransfer/InstBankByIfsc',
                        dataType: 'JSON',
                        data: {ifsc: str}
                    }).done(function (response) {
                        if (response) {
           
                            if (response.error == 0) {
                                if (retry != true) {
                           
                                    bankData.bank = response.msg.bank_name;
                                    $('#bnkchoose').hide('');
                                    $('#bkinput').val(response.msg.bank_name).prop('disabled', true).addClass('edited');
                                    $('#bnkinputcol').show('');
                                }
                            } else if (response.error == 2) {
                                window.location.reload('true');
                            } else {
                                bankData = {};
                               // $("#bbnkifsc").val('').focus();
                                toastr.error(response.error_desc);
                            }
                        }
                    }).fail(function (err) {
                        throw err;
                    });
                } else {
                    $('#bnkinputcol').hide('');
                    $('#bnkchoose').show('');
                    
                }
                
            } else {
                    $('#bnkinputcol').hide('');
                    $('#bnkchoose').show('');
                    
                }
                
            }
        }
    
      var find_ifsc = function (e, value) {
          
            if (banklist_with_ifsc.hasOwnProperty(e.currentTarget.value)) {
                $('#bbnkifsc').val(banklist_with_ifsc[value].bank_ifsc).addClass('edited');
             
            } else {
                
                $("#bbnkifsc").val('').removeClass("edited");
            }
        }
    
      var getmodalifsconduty = {
            init_ifsc: function () 
          {
          
                $(".search-ifsc-btn").click(function(){
                    $("#search-ifsc").toggle();
                    $('#statediv,#citidiv,#branchdiv').hide().html("");
                    $('#ifsc-detail').hide().html("");
                    $('#get_bnkdeatbysearch').hide().html("");
                    if($('#search-ifsc').is(':visible'))
                        {
                            GetBankName();
                        }
                    
                });
                
                
                
                
                var GetBankName = function () {
                    $.ajax({
                        method: 'POST',
                        url: 'MoneyTransfer/BankNameSelect',
                        dataType: 'JSON'
                    }).done(function (response) {
                        if (response) {
                          
                            if (response.error == 2) {
                                window.location.reload(true);
                            } else if (response.error == 0) {
                
              
                                $("#select-bank-name").html('<option selected="selected" value="">Select Bank Name</option>');
                                $.each(response.msg, function (index, value) {
                                    $("#select-bank-name").append('<option value="' + value.bank_name + '">' + value.bank_name + '</option>');
                                });
                              
                                
                            }
                        }
                    }).fail(function (err) {
                        throw err;
                    });
                }
                
                

                var OnChangeBank = function () {
                    $("#select-bank-name").change(function () 
                        {
                     
                        $('#statediv,#citidiv,#branchdiv').hide().html("");
                        $('#ifsc-detail').hide().html("");
                        $('#get_bnkdeatbysearch').hide().html("");
                        
                        var get_bankname = $(this).val();
                        if (get_bankname != undefined && get_bankname != null && get_bankname != '') 
                        {
                            $.ajax({
                                method: 'POST',
                                url: 'MoneyTransfer/SelectState',
                                dataType: 'JSON',
                                data: {bank_name: get_bankname}
                            }).done(function (response) {
                                if (response) {
                                   
                                    if (response.error == 2) {
                                        window.location.reload(true);
                                    } else if (response.error == 0) 
                                    {
                                        var state_data='';
                                        state_data+='<label>Select State</label>';
			               	            state_data+='<select id="select-state">';
                                        
			               	            state_data+='<option selected="selected" value="">Select Bank State</option>';
                                        $.each(response.msg, function (index, value) {
                                            state_data+='<option value="' + value.state + '">' + value.state + '</option>';
                                        });
                                        state_data+='</select>';
                                        
                                        $('#statediv').html(state_data).show();
                                        $('#select-state').select2({theme: 'bootstrap4',width:'100%'});
                                        OnStateChange(get_bankname);
                                    }
                                }
                            }).fail(function (err) {  
                                throw err;
                            });
                            
                        }else{
                            
                            $('#statediv,#citidiv,#branchdiv').hide().html("");
                            $('#ifsc-detail').hide().html("");
                            $('#get_bnkdeatbysearch').hide().html("");
                    
                        }
                    });
                }

                OnChangeBank();
                
                var OnStateChange = function (get_bankname) {
                    $("#select-state").change(function () {
                       
                        $('#citidiv,#branchdiv').hide().html("");
                        $('#ifsc-detail').hide().html("");
                        $('#get_bnkdeatbysearch').hide().html("");
              
                        var get_state = $(this).val();
                        if (get_state != undefined && get_state != null && get_state != '') 
                        {
                            
                            $.ajax({
                                method: 'POST',
                                url: 'MoneyTransfer/SelectCity',
                                dataType: 'JSON',
                                data: {state: get_state, bank_name: get_bankname}
                            }).done(function (response) {
                                if (response) {
                                    
                                    if (response.error == 2) {
                                        window.location.reload(true);
                                    } else if (response.error == 0) 
                                    {
                                        
                                        var city_data='<label>Select City</label>';
			               	                city_data+='<select id="select-city">';
			               	 	            
                                            city_data+='<option selected="selected" value="">Select Bank City</option>';
                                        $.each(response.msg, function (index, value) {
                                            city_data+='<option value="' + value.city + '">' + value.city + '</option>';
                                        });
                                        
                                            city_data+='</select>';
                                        
                                        $('#citidiv').html(city_data).show();
                                        $('#select-city').select2({theme: 'bootstrap4',width:'100%'});
                                        
                                        OnCityChange(get_bankname, get_state);
                                    }
                                }
                            }).fail(function (err) {
                                throw err;
                            });
            
                        }else{
                            
                            $('#citidiv,#branchdiv').hide().html("");
                            $('#ifsc-detail').hide().html("");
                            $('#get_bnkdeatbysearch').hide().html("");
                            
                        }
                    });
                }

                var OnCityChange = function (get_bankname, get_state) {
                    $("#select-city").change(function () {
                        
                        $('#branchdiv').hide().html("");
                        $('#ifsc-detail').hide().html("");
                        $('#get_bnkdeatbysearch').hide().html("");
               
                        var get_city = $(this).val();
                        if (get_city != undefined && get_city != null && get_city != '') 
                        {
                            $.ajax({
                                method: 'POST',
                                url: 'MoneyTransfer/SelectBranch',
                                dataType: 'JSON',
                                data: {state: get_state, bank_name: get_bankname, city: get_city}
                            }).done(function (response) {
                                if (response) {
                                    
                                 
                                    if (response.error == 2) {
                                        window.location.reload(true);
                                    } else if (response.error == 0) {
                                        
                                        var branch_data="";
                                        branch_data+='<label>Select Branch</label>';
			               	            branch_data+='<select id="select-branch-name">';
			               	 	
                                        branch_data+='<option selected="selected" value="">Select Bank Branch</option>';
                                       $.each(response.msg, function (index, value) {
                                            branch_data+='<option value="' + value.branch + '">' + value.branch + '</option>';
                                        });
                                        branch_data+='</select>';
                                        $('#branchdiv').html(branch_data).show();
                                        $('#select-branch-name').select2({theme: 'bootstrap4',width:'100%'});
                                        
                                        OnBranchChange(get_bankname, get_state, get_city);
                                    }
                                }
                            }).fail(function (err) {
                                throw err;
                            });
            
                        }else{
            
                            $('#branchdiv').hide().html("");
                            $('#ifsc-detail').hide().html("");
                            $('#get_bnkdeatbysearch').hide().html("");

                        }
                    });
                }

                var OnBranchChange = function (get_bankname, get_state, get_city) {
                    $("#select-branch-name").change(function () {
                     
                        $('#ifsc-detail').hide().html("");
                        $('#get_bnkdeatbysearch').hide().html("");
                        
                        var get_branch = $(this).val();
                        if (get_branch != undefined && get_branch != null && get_branch != '') 
                        {
                            
                        $.ajax({
                            method: 'POST',
                            url: 'MoneyTransfer/GetIFSC',
                            dataType: 'JSON',
                            data: {state: get_state, bank_name: get_bankname, city: get_city, branch: get_branch}
                        }).done(function (response) {
                            if (response) 
                            {
                                if (response.error == 2) 
                                {
                                    window.location.reload(true);
                                } else if (response.error == 0) 
                                {

                                    bankData.bank = response.msg.bank_name;
                                    bankData.branch = response.msg.branch;
                                    bankData.state = get_state;
                                    bankData.city = get_city;
                                    
                                    //$('#get_bnkdeatbysearch').html('<a class="btn blue-btn red-btn white-txt get-details-btn" id="getbnkdet_btn">Get Details</a>');
                                    
                                    var ifscdet_div='';
                                    ifscdet_div+='<ul class="search-ifsc-list">';
			              	 		ifscdet_div+='<li><span class="font14 font-medium mr-1">Bank Name:</span> <span class="font14 font-bold" id="bn">'+response.msg.bank_name+'</span></li>';
			              	 		ifscdet_div+='<li><span class="font14 font-medium mr-1">Branch Name:</span> <span class="font14 font-bold" id="brn">'+response.msg.branch+'</span></li>';
			              	 		ifscdet_div+='<li><span class="font14 font-medium mr-1">Address:</span> <span class="font14 font-bold" id="ad">'+response.msg.address+'</span></li>';
			              	 		ifscdet_div+='<li><span class="font14 font-medium mr-1">IFSC Code:</span> <span class="font14 font-bold" id="ifsc">'+response.msg.ifsc+'</span></li>';
			              	 		ifscdet_div+='<li><button class="blue-btn btn" id="done_bankdet">Done</button></li>';
			              	 	    ifscdet_div+='</ul>';
                                    
                                    $('#ifsc-detail').html(ifscdet_div).show();
                               
                                    $('#done_bankdet').click(function (e) 
                                    {
                                        
                                    e.preventDefault();

                                    $('#bnkchoose').hide('');
                                    $('#statediv,#citidiv,#branchdiv').hide().html("");
                                    $('#ifsc-detail').hide().html("");
                                    $('#get_bnkdeatbysearch').hide().html("");
                                    $('#search-ifsc').hide();
                                    $('#select-bank-name').prop('selectedIndex',0).change();
                                    $('#bbnkifsc').val(response.msg.ifsc);
                                    $('#bkinput').val(response.msg.bank_name).prop('disabled', true).addClass('edited');
                                    $('#bnkinputcol').show('');
                                        
                                    });
                                  
                                }
                            }
                        }).fail(function (err) {
                            throw err;
                        });
        }
                        else{
                        
                            $('#ifsc-detail').hide().html("");
                            $('#get_bnkdeatbysearch').hide().html("");
                            
                            }
                    });
                }

                GetBankName();
            }
        }
      
      $("#add_ab").click(function (e) {
            e.preventDefault();
            if(after_login_obj.action===true && after_login_obj.type=="Open Bene Add Window")
            {
                var param = {};
                $(this).addClass('ladda-button').attr({
                    'data-style': 'zoom-in',
                    'data-spinner-color': "#333"
                });
                var la = $(this).ladda();
        
                //param.bank = $("#bbnksel").val();
                var bank = $("#bbnksel").val();
                param.bank = banklist_with_ifsc[bank] ? banklist_with_ifsc[bank].Bank_id : bankData.bank_id;
                param.ifsccode = $("#bbnkifsc").val();
                param.accountno = $("#bcn").val();
                param.name = $('#benefname').val();
                param.remitid=remitid;

                param.mobile = reminumber;// clogin.mobile;
              
                var status2 = true;
                if (param.ifsccode.length != 11 || !ValidateAll(param.ifsccode, 'ifsc')) 
                {
                    $("#bbnkifsc").focus();
                    toastr.error('Please Enter IFSC');
                    status2 = false;
                    return false;
                } else if (param.accountno == '') 
                {
                    $("#bcn").focus();
                    toastr.error('Please enter Account No');
                    status2 = false;
                    return false;
                } else if (!param.name || param.name == null || param.name.length < 2 || param.name == "") 
                {

                    toastr.error('Please enter a valid name, minimum length 2');

                    $('#benefname').focus();
                    status2 = false;
                    return false;
                }else {
                    status2 = true;
                }
                if (status2) 
                {
                    la.ladda('start');
                    after_login_obj.action=true;
                    after_login_obj.type=="Bene Add Process Started";
                    
                    $.post('MoneyTransfer/BenefRegistration', {
                        data: param
                    }, function (response) {
                        if (response) {
                
                            if (response.error == 0) 
                            {
                                request.benecode = response.bnef_id;
                                    param.beneid = request.benecode;
                                    toastr.success('Beneficiary Added Successfully');
                                    la.ladda('stop');
                                
                                    after_login_obj.action=true;
                                    after_login_obj.type=="Open Bene Add Window";
                                
                                    $('#add-beneficiary').modal('hide')
                                    table.ajax.reload( null, false );
                                   
                            }else if (response.error == 4) 
                            {
                                toastr.success(response.msg);
                                //$("#size_modal").attr('class', 'modal-dialog modal-md');
                                var beneaddverify='';
                                beneaddverify+='<div class="modal-header">';
                                beneaddverify+='<h5 class="modal-title" >Beneficiary Registration Validation</h5>';
                                beneaddverify+='<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
	                            beneaddverify+='<span aria-hidden="true">&times;</span>';
	                            beneaddverify+='</button>';
                                beneaddverify+='</div>';
                                beneaddverify+='<div class="modal-body">';
                                
                                beneaddverify+='</div>';
                                beneaddverify+='<div id="otp-beneficiary">';
                                beneaddverify+='<form>';
                                beneaddverify+='<div class="form-group row">';
                                beneaddverify+='<label>OTP</label>';
                                beneaddverify+='<input type="tel" class="form-control" id="benefOTP" name="benefOTP" placeholder="Enter OTP">';  
                                beneaddverify+='</div>';
                                beneaddverify+='</form>';
                                beneaddverify+='</div>';
                                
                                beneaddverify+='<div class="modal-footer">';
							    beneaddverify+='<button type="button"  id="resendbeneaddOTP" class="btn blue-btn">Resend OTP</button>';
							    beneaddverify+='<button type="button" class="btn blue-btn" id="verfy_ab" >Verify</button>';
                                beneaddverify+='<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
						        beneaddverify+='</div>';
	                            beneaddverify+='</div>';
                        
                                $('#mainbeneaddidv').hide();
                                $('#beneaddverificationdiv').html(beneaddverify).show();
                                
                                
                                after_login_obj.action=true;
                                after_login_obj.type=="Bene Add Verify By OTP";
                                
                                var benefotpreq = {};
                                benefotpreq.remitterid = response.remitterid;
                                benefotpreq.bnef_id = response.bnef_id;
                                validate_Bnef_otp(benefotpreq)
                                
                  
                            } else if (response.error == 2) 
                            {
                                    window.location.reload(true);
                            } else 
                            {
                                after_login_obj.action=true;
                                after_login_obj.type=="Open Bene Add Window";
                                toastr.error(response.error_desc);
                                la.ladda('stop');
                            }
                            
                        }
                    }, 'json').fail(function (error) {
                        la.ladda('stop');
                        throw error;
                    });
                   
                }
            }else{
                toastr.error('Please Wait!!');
            }
        });

      $('#benef_validate_accnt').click(function(e){
          e.preventDefault();
          console.log(after_login_obj);
           if(after_login_obj.action===true && after_login_obj.type=="Open Bene Add Window")
            {
                
                var bank = $("#bbnksel").val();
                var validparams={valid:true};
                validparams.bank = banklist_with_ifsc[bank] ? banklist_with_ifsc[bank].Bank_id : bankData.bank_id;
                validparams.ifsccode = $("#bbnkifsc").val();
                validparams.accountno = $("#bcn").val();
                validparams.remitid=remitid;
                validparams.reminame=reminame;
                validparams.mobile = reminumber;
                var verifyla = $(this).ladda();
                
                
                if (validparams.ifsccode.length != 11 || !ValidateAll(validparams.ifsccode, 'ifsc')) 
                {
                    $("#bbnkifsc").focus();
                    toastr.error('Please Enter IFSC');
                    validparams.valid = false;
                    return false;
                } else if (validparams.accountno == '') 
                {
                    $("#bcn").focus();
                    toastr.error('Please enter Account No');
                    validparams.valid = false;
                    return false;
                } 
                
                if(validparams.valid===true)
                {
                    $(this).addClass('ladda-button').attr('data-style','zoom-in');
                    verifyla.ladda('start');
                    
                    after_login_obj.action=true;
                    after_login_obj.type=="Bene Verify/Validate Process Started";
                    
                    $.ajax({
                        "url":"MoneyTransfer/VerifyBeneficiaryAccount",
                        "data":validparams,
                        "dataType":"JSON",
                        "method":"POST"
                    }).done(function(beneverify_respo){
                        if(beneverify_respo)
                            {
                                if(beneverify_respo.error==0)
                                    {
                                        verifyla.ladda('stop');
                                        if(beneverify_respo.txndata.status=='SUCCESS')
                                            {
                                                toastr.info(beneverify_respo.msg);
                                            }else{
                                                toastr.error(beneverify_respo.msg);
                                            }
                                        
                                        $('#benefname').val(beneverify_respo.txndata.benename);
                                        after_login_obj.action=true;
                                        after_login_obj.type="Open Bene Add Window";
                                    }
                                else if(beneverify_respo.error==2)
                                    {
                                        window.location.reload(true);
                                    }
                                else{
                                    verifyla.ladda('stop');
                                    toastr.error(beneverify_respo.error_desc);
                                    after_login_obj.action=true;
                                    after_login_obj.type="Open Bene Add Window";
                                }
                            }
                    }).fail(function(err){
                        verifyla.ladda('stop');
                        toastr.error('Something went wrong, please check transaction history');
                        after_login_obj.action=true;
                        after_login_obj.type="Open Bene Add Window";
                    });
                    
                }
                
            }else{
                 toastr.error('Please Wait!!');
            }
      });
    
      var validate_Bnef_otp= function(req)
      {
      
      $("#verfy_ab").click(function(e){
            e.preventDefault();
          
       if(after_login_obj.action===true && after_login_obj.type=="Bene Add Verify By OTP")
           {
                if(req && req!= "" && req!=null && !$.isEmptyObject(req))
                {

                req.otp = $("#benefOTP").val(); 
                    
                if(req.otp="")
                {

                    $("#benefOTP").focus(); 
                    toastr.error('Invalid OTP');
                    return false;

                }else{

                    after_login_obj.action=true;
                    after_login_obj.type='Verify Bene Add OTP Request Sent';
                    
                    $(this).addClass('btn-ladda ladda-button').attr({'data-style':'zoom-in'});
                    var la=$(this).ladda();
                    la.ladda('start');

                      $.post('MoneyTransfer/instpy_verifyBenefregiscus',{data:req},function(response)
                      {
                            if(response)
                            {  
                                if(response.error==0)
                                    {
                                         toastr.success(response.msg);
                                         after_login_obj.action=true;
                                         after_login_obj.type=="Open Bene Add Window";
                                
                                         $('#add-beneficiary').modal('hide')
                                         table.ajax.reload( null, false );
                                        
                                    }
                                else if(response.error==2)
                                    {
                                        window.location.reload(true);
                                    }
                                else{

                                        toastr.error(response.error_desc);
                                        la.ladda('stop');
                                        after_login_obj.action=true;
                                        after_login_obj.type=="Bene Add Verify By OTP";

                                }
                                
                           }
                          
                      },'json').fail(function(error){
                          la.ladda('stop'); 
                          after_login_obj.action=true;
                          after_login_obj.type=="Bene Add Verify By OTP";
                          toastr.error('Something went wrong, try again later');
                      });

                }
                    
                }else{
                    toastr.error('Validation Failure');
                    
                }

           }else{
               toastr.error('Please Wait!!');
           }
      });
  
      $("#resendbeneaddOTP").click(function(e){
        e.preventDefault();
  
        if(after_login_obj.action===true && after_login_obj.type=="Bene Add Verify By OTP")
           {

                if(req && req!= "" && req!=null && !$.isEmptyObject(req))
                {
                    
                req.otp =''; 
                after_login_obj.action=true;
                after_login_obj.type='Resend Bene Add OTP Request Sent';
                        
                $(this).addClass('btn-ladda ladda-button').attr({'data-style':'zoom-in'});
                var la=$(this).ladda();
                la.ladda('start');
                    

                  $.post('MoneyTransfer/instpy_resendBenfRegisterResendOTP',{data:req},function(response)
                  {
            if(response)
                    {  
                        if(response.error==0)
                            {
                                 toastr.success(response.msg);
                                 after_login_obj.action=true;
                                 after_login_obj.type=="Bene Add Verify By OTP";
                                 la.ladda('stop');

                            }
                        else if(response.error==2)
                            {
                                window.location.reload(true);
                            }
                        else{
                                toastr.error(response.error_desc);
                                after_login_obj.action=true;
                                after_login_obj.type=="Bene Add Verify By OTP";
                                la.ladda('stop');
                        }
                        
                    }
            },'json').fail(function(error){
                      la.ladda('stop'); 
                      after_login_obj.action=true;
                      after_login_obj.type=="Bene Add Verify By OTP";
                      toastr.error('Something went wrong, try again later');
                  });


                }
                else{
                    toastr.error('Validation Failure');
                    
                }

            }else{
               toastr.error('Please Wait!!');
            }   
           
    });
      
      }

    /*********************************** end add benef*************************************************/
  
    var benef_list = function (clogin, la)
    {
    
    $("#instntpy_afterlgn").hide();

    table = $('.datatables').DataTable({
            "processing": true,
            "ajax": {
                url: "MoneyTransfer/InstantPayLogin",
                type: 'post',
                data : clogin,
                "dataSrc": function (json) {
                    var response = json.response;
                    console.log(json);
                    if(json.error==0){
                        
                        remitter_limit = response['data']['remitter_limit'][0];

                        $('#total_allowed_limit').html(remitter_limit['limit']['total']);
                        $('#available_limit').html(remitter_limit['limit']['remaining']);
                        $('#mobile_no').html(response['data']['remitter']['mobile']);
                        $('#sender_name').html(response['data']['remitter']['name']);
                       /*  $('#search_benef_import_val').val(response.rmtr_mob); */

                        reminumber = response['data']['remitter']['mobile'];
                        remitid = response['data']['remitter']['id'];
                        reminame = response['data']['remitter']['name'];

                        $("#mobile-recharge").hide();
                        $("#instntpy_afterlgn").show();

                        return json.response.data.beneficiary;
                      
                    } /*else if(json.error==1){
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
                        req.remitterid = response.remitterid;
                        req.mobile = clogin.mobile;
                        console.log(req)
                    } else{
                        toastr.error(json.error_desc);
                    }*/

                    
                }
            },
            responsive: true,
                order: [],
                columns: [
                    {"title" : "Beneficiary Name","data": "name",},
                    {"title" : "Bank","data": "bank",},
                    {"title" : "Account No","data": "account",},
                    {"title" : "IFSC","data": "ifsc",},
                    {"title" : "Amount","render": function ( data, type, full, meta ) {
                        return '<input type="text" name="transamount" id="TrnsAmnt-' + full.id + '" class="form-control" style="width:100px;">';
                        }
                    },
              
                    {"title" : "Mode","data": "id","orderable": false,"render": function ( data, type, full, meta ) {
                        return '<button class="btn btn-default float-left mr-1 fontbold strtremit" id="imps_tnsfr" data-impstransfer="' + full.id + '">IMPS</button><button class="btn btn-default float-left btn-default mr-1 fontbold strtremit"  id="neft_tnsfr" data-nefttransfer="' + full.id + '">NEFT</button><button class="btn float-left red-btn mr-1 "  data-delt_benef="' + full.id + '" id="Delete-Benf" ><img src="assets/images/trash-icon.svg" width="15">';
                        }
                    },
                ],
        
        
            });

    $('#user_logout_now').click(function (e) {
                e.preventDefault();
                window.location.reload('true');
            });

    table.on('click', '#Delete-Benf', function (e) {
                e.preventDefault();
                console.log('gkhfgjk');
                $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                var benfid = $(this).data('delt_benef');
                var row = $(this).closest('tr');
                var showtd1 = table.row(row).data();
                if (showtd1['id'] == benfid) {
                    var param = {'beneid': showtd1.id, 'remitid': remitid,'mobile':reminumber};   
                    la.ladda('start');
                    $.ajax({
                        method: 'POST',
                        url: 'MoneyTransfer/InstantPyDelCustBenef',
                        dataType: 'JSON',
                        data: {data: param}
                        // data: {'beneid': showtd1.id, 'remitid':remitid},
                    }).done(function (response) {
                        if (response) {
                            if (response.error == 0) {
                                console.log('hgjfgfg');
                                param.la = la;
                                BenefDelVadtnByOTp(param);
                            } else if (response.error == 2) {
                                window.location.reload('true');
                            } else {
                                toastr.error(response.error_desc)
                                la.ladda('stop');
                            }
                        }
                    }).fail(function (err) {
                        throw err;
                    });
                }
            });
        
    table.on('click', '.strtremit', function (e) {
                e.preventDefault();
          if(after_login_obj.action===false && after_login_obj.type=="")
          {
              
                var id = $(this)[0].id;
                $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
              
                if(id=='imps_tnsfr')
                {
            
                    var benfid = $(this).data('impstransfer');
                    var row = $(this).closest('tr');
                    var showtd1 = table.row(row).data();  
                    
                    if (showtd1['id'] == benfid)
                    {
                        
                        
                        $('#TrnsAmnt-' + benfid + '').focus();
           
                        $(this).addClass('ladda-button').attr({
                            'data-style': 'zoom-in',
                            'data-spinner-color': "#333"
                        });
                        
                        var la = $(this).ladda();
          
                        TXN_REQUEST.bank = showtd1.bank;
                        TXN_REQUEST.ifsccode =showtd1.ifsc;;
                        TXN_REQUEST.accountno = showtd1.account;
                        TXN_REQUEST.name = showtd1.name;

                        TXN_REQUEST.mode = 'IMPS';

                        TXN_REQUEST.transamount = $('#TrnsAmnt-' + benfid + '').val();
                        TXN_REQUEST.bnef_id=benfid;
                        
                        var status2 = true;  
        
                        if (TXN_REQUEST.mode != "IMPS" ) 
                        {
                            toastr.error('Invalid mode');
                            status2 = false;
                            return false;
                   
                        }
                        else if (!TXN_REQUEST.transamount || TXN_REQUEST.transamount == null || TXN_REQUEST.transamount == "" || isNaN(TXN_REQUEST.transamount) || TXN_REQUEST.transamount < 1 || !ValidateAll(TXN_REQUEST.transamount, 'amount') || TXN_REQUEST.transamount > 25000) 
                        {
                  
                            toastr.error('Please enter valid amount');
                            $('#TrnsAmnt-' + benfid + '').focus();
                            status2 = false;
                            return false;
                  
                        } else 
                        {
                            status2 = true;
                        }
    
                        if (status2) 
                        {
                            after_login_obj.action=true;
                            after_login_obj.type="Calculate Commission";
                            
                            la.ladda('start');
                            $.ajax({
                                url: "MoneyTransfer/ChrgCommOnTransaction",
                                dataType: "JSON",
                                method: "POST",
                                data: {data: TXN_REQUEST},
                                // processData: false,
                                success: function (response) {
                                    if (response) {
                                        if (response.error == 0) 
                                        {
                                            after_login_obj.action=true;
                                            after_login_obj.type="Commission Display Screen";
                                            TXN_CONFRMTN(la,response);
                                            
                                        } else if (response.error == 2) 
                                        {
                                            window.location.reload(true);
                                        } else if (response.error == 8) 
                                        {
                                            after_login_obj.action=true;
                                            after_login_obj.type="Commission Display Screen";
                                            LOOPTXN_CONFRMTN(la,response);
                                            
                                        }else {
                                            
                                            after_login_obj.action=false;
                                            after_login_obj.type="";
                                            toastr.error(response.error_desc)
                                        // la.ladda('stop');  
                                        }
                                    la.ladda('stop');
                                    }
                                }
                            }); 
                        }
                 
                }
                    
                }
                else if(id=='neft_tnsfr')
                {
            
                    var benfid = $(this).data('nefttransfer');

                    var row = $(this).closest('tr');
                    var showtd1 = table.row(row).data();

                    if (showtd1['id'] == benfid)
                    {
                   
                    $('#TrnsAmnt-' + benfid + '').focus();
           
                    $(this).addClass('ladda-button').attr({
                        'data-style': 'zoom-in',
                        'data-spinner-color': "#333"
                    });
                    var la = $(this).ladda();
          
                    TXN_REQUEST.bank = showtd1.bank;
                    TXN_REQUEST.ifsccode =showtd1.ifsc;;
                    TXN_REQUEST.accountno = showtd1.account;
                    TXN_REQUEST.name = showtd1.name;

                    TXN_REQUEST.mode = 'NEFT';

                    TXN_REQUEST.transamount = $('#TrnsAmnt-' + benfid + '').val();
                    TXN_REQUEST.bnef_id=benfid;
                    var status2 = true;  
        
                    if (TXN_REQUEST.mode != "NEFT" ) 
                    {
                    
                        toastr.error('Invalid mode');
                        status2 = false;
                        return false;
                   
                    }
                    else if (!TXN_REQUEST.transamount || TXN_REQUEST.transamount == null || TXN_REQUEST.transamount == "" || isNaN(TXN_REQUEST.transamount) || TXN_REQUEST.transamount < 1 || !ValidateAll(TXN_REQUEST.transamount, 'amount') || TXN_REQUEST.transamount > 25000) 
                    {
                  
                        toastr.error('Please enter valid amount');

                        $('#TrnsAmnt-' + benfid + '').focus();
                        status2 = false;
                        return false;
                  
                    } else {
                        status2 = true;
                    }
    
                    if (status2) 
                    {
                        after_login_obj.action=true;
                        after_login_obj.type="Calculate Commission";
                        
                        la.ladda('start');
                        $.ajax({
                            url: "MoneyTransfer/ChrgCommOnTransaction",
                            dataType: "JSON",
                            method: "POST",
                            data: {data: TXN_REQUEST},
                            success: function (response) 
                            {
                                if (response) {
                                    
                                    if (response.error == 0) 
                                    {
                                        after_login_obj.action=true;
                                        after_login_obj.type="Commission Display Screen";
                                        TXN_CONFRMTN(la,response);
                                        
                                    } else if (response.error == 2) 
                                    {
                                        window.location.reload(true);
                                        
                                    } else if (response.error == 8)
                                    {
                                        after_login_obj.action=true;
                                        after_login_obj.type="Commission Display Screen";
                                        LOOPTXN_CONFRMTN(la,response);
                                        
                                    }else 
                                    {
                                        after_login_obj.action=false;
                                        after_login_obj.type="";
                                        toastr.error(response.error_desc)
                                        // la.ladda('stop');  
                                    }
                                }
                                la.ladda('stop');
                            }
                        }); 
          
            
                    }
                 
                }
                    
                }
                  
          }else{
              toastr.error('Please Wait!!');
          }
          
        });
      
    var LOOPTXN_CONFRMTN  = function (la,response) {
    
        if(after_login_obj.action===true && after_login_obj.type=="Commission Display Screen")
        {    
        
        if (!TXN_REQUEST || TXN_REQUEST == "" || TXN_REQUEST == null) 
        {

            toastr.error("Invalid Request");
            return false;
  
        } else {  
            
            $("#instntpy_afterlgn").hide(); 
            var htl='<div class="width-100 transaction-confirm-section" id="transaction-confirmation-screen">';     
             htl += '<div class="container">';
              htl+='<div class="row">';
                 htl+='<div class="col-12">';
                  htl+='<div class="transaction-confirm-section-col width-100 pl-15 pr-15">';
                    htl+='<div class="gray-header">Transaction Confirmation</div>';

                    htl+='<div class="width-100 transaction-confirm-main mt-20">';
                        htl+='<div class="width-100">';
                          htl+='<div class="row">';
                            htl+='<div class="col-lg-4">';
                              htl+='<div class="blue-panel">';
                                htl+='<div class="white-icon-container"><img src="assets/images/wallet-blue-icon.svg" width="25"></div>';
                                htl+='<div class="float-left">';
                                  htl+='<div class="font16 white-txt">YOUR AVAILABLE BALANCE</div>';
                                  htl+='<div class="font16 fontbold white-txt" id="avlbl_blcc">'+remitter_limit['limit']['remaining']+'</div>';
                                htl+='</div>';
                              htl+='</div>';
                            htl+='</div>';

                            htl+='<div class="col-lg-4">';
                              htl+='<div class="blue-panel">';
                                htl+='<div class="white-icon-container"><img src="assets/images/rupee-blue-icon.svg" width="30"></div>';
                                htl+='<div class="float-left">';
                                  htl+='<div class="font16 white-txt">TRANSFER AMOUNT</div>';
                                  htl+='<div class="font16 fontbold white-txt" id="tnsfr_blcc">'+TXN_REQUEST.transamount+'</div>';
                                htl+='</div>';
                              htl+='</div>';
                            htl+='</div>';

                            htl+='<div class="col-lg-4">';
                              htl+='<div class="blue-panel">';
                                htl+='<div class="white-icon-container"><img src="assets/images/wallet-blue-icon.svg" width="25"></div>';
                                htl+='<div class="float-left">';
                                  htl+='<div class="font16 white-txt">TYPE</div>';
                                  htl+='<div class="font16 fontbold white-txt" id="modetype">'+TXN_REQUEST.mode+'</div>';
                                htl+='</div>';
                              htl+='</div>';
                            htl+='</div>';
                          htl+='</div>';
                        htl+='</div>';

                        htl+='<div class="width-100 mt-20">';
                          htl+='<div class="row">';
                            htl+='<div class="col-lg-6">';
                              htl+='<div class="form-container pt-10 mb-10"> ';
                                htl+='<ul class="transaction-confirm-list">';
                                  htl+='<li>';
                                
                                    htl+='<label class="fontbold font14 black-txt">Remmiter</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt"><span id="RemitterNam">'+reminame+'</span> - <span id="RemitterNum">'+reminumber+'</span></p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">Beneficiary Name</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_name">'+TXN_REQUEST.name+'</p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">Account No:</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_accnt">'+TXN_REQUEST.accountno+'</p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">Bank</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_bank">'+TXN_REQUEST.bank+'</p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">IFSC</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_ifsc">'+TXN_REQUEST.ifsccode+'</p>';
                                  htl+='</li>';

                                htl+='</ul>';
                              htl+='</div>';

                             
                            htl+='</div>';

                            htl+='<div class="col-lg-6">';
                                 htl+='<div class="width-100 no-of-transaction">';

                                htl+='<div class="width-100 blue-header white-txt mb-10">Number of Transaction</div>';
                                htl+='<table class="table table-bordered font14">';
                                  htl+='<thead>';
                                    htl+='<tr>';
                                      htl+='<th>S#</th>';
                                      htl+='<th>Amount</th>';
                                      htl+='<th>CCF</th>';
                                    htl+='</tr>';
                                  htl+='</thead>';

                                  htl+='<tbody>';
                                    var i=1;
                                    $.each(response.result, function (k, v) {
                                        if(v.error == 0){
                                            htl+='<tr>';
                                            htl+='<td>'+i+'</td>';
                                            htl+='<td>'+v.trnsferamount+'</td>';
                                            htl+='<td>'+v.extra_chrg+'</td>';
                                            htl+='</tr>';
                                        }
                                        i++;
                                    });
                                  htl+='</tbody>';
                                htl+='</table>';
                              htl+='</div>';

                                  htl+='<div class="form-group pl-10 pr-10 text-center">';
                                    htl+='<a class="btn green-btn white-txt confirm-btn" id="procss_txn">Confirm</a>';
                                    htl+='<button class="btn red-btn white-txt" id="cancel_txn">Cancel</button>';
                                  htl+='</div>';
                            htl+='</div>';
                          htl+='</div>';
                        htl+='</div>';
                    htl+='</div>';
                  htl+='</div>';
                 htl+='</div>';
              htl+='</div>';
            htl+='</div>';
          htl+='</div>';

            $("#instntpy_txncnfm").html(htl);
            
            $('#cancel_txn').click(function(e)
            {
                e.preventDefault();
                
                if(after_login_obj.action===true && after_login_obj.type=="Commission Display Screen")
                { 
                    
                $("#instntpy_afterlgn").show();  
                $('#TrnsAmnt-' + remitid + '').val('') 
                table.ajax.reload( null, false );      
                $("#instntpy_txncnfm").html('');
                status = true;
                    
                after_login_obj.action=false;
                after_login_obj.type="";
                TXN_REQUEST={};
                    
                }else{
                    toastr.error('Please Wait!!');
                }
            
            });

            $('#procss_txn').click(function(e){
                e.preventDefault();
                if(after_login_obj.action===true && after_login_obj.type=="Commission Display Screen")
                { 
                    
                $(this).addClass('ladda-button').attr({
                    'data-style': 'zoom-in',
                    'data-spinner-color': "#333"
                });
                var la1 = $(this).ladda();
                    
                after_login_obj.action=true;
                after_login_obj.type="Initiate Txn Process";
                    
                TRANSPRCSS(la1);
                    
                }else{
                    toastr.error('Please Wait!!');
                }
            });   
        }     
            
        }else{
            toastr.error('Please Wait!!');
        }
      
    }
      
    var TXN_CONFRMTN  = function (la,response) {
    
        if(after_login_obj.action===true && after_login_obj.type=="Commission Display Screen")
        { 
            
        if (!TXN_REQUEST || TXN_REQUEST == "" || TXN_REQUEST == null) 
        {

            toastr.error("Invalid Request");
            return false;
          
        } else {   
           
            $("#instntpy_afterlgn").hide(); 
        
            var htl='<div class="width-100 transaction-confirm-section" id="transaction-confirmation-screen">';     
             htl += '<div class="container">';
              htl+='<div class="row">';
                 htl+='<div class="col-12">';
                  htl+='<div class="transaction-confirm-section-col width-100 pl-15 pr-15">';
                    htl+='<div class="gray-header">Transaction Confirmation</div>';

                    htl+='<div class="width-100 transaction-confirm-main mt-20">';
                        htl+='<div class="width-100">';
                          htl+='<div class="row">';
                            htl+='<div class="col-lg-4">';
                              htl+='<div class="blue-panel">';
                                htl+='<div class="white-icon-container"><img src="assets/images/wallet-blue-icon.svg" width="25"></div>';
                                htl+='<div class="float-left">';
                                  htl+='<div class="font16 white-txt">YOUR AVAILABLE BALANCE</div>';
                                  htl+='<div class="font16 fontbold white-txt" id="avlbl_blcc">'+remitter_limit['limit']['remaining']+'</div>';
                                htl+='</div>';
                              htl+='</div>';
                            htl+='</div>';
                              
                            htl+='<div class="col-lg-4">';
                              htl+='<div class="blue-panel">';
                                htl+='<div class="white-icon-container"><img src="assets/images/rupee-blue-icon.svg" width="30"></div>';
                                htl+='<div class="float-left">';
                                  htl+='<div class="font16 white-txt">TRANSFER AMOUNT</div>';
                                  htl+='<div class="font16 fontbold white-txt" id="tnsfr_blcc">'+response.trnsferamount+'</div>';
                                htl+='</div>';
                              htl+='</div>';
                            htl+='</div>';

                            htl+='<div class="col-lg-4">';
                              htl+='<div class="blue-panel">';
                                htl+='<div class="white-icon-container"><img src="assets/images/wallet-blue-icon.svg" width="25"></div>';
                                htl+='<div class="float-left">';
                                  htl+='<div class="font16 white-txt">TYPE</div>';
                                  htl+='<div class="font16 fontbold white-txt" id="modetype">'+TXN_REQUEST.mode+'</div>';
                                htl+='</div>';
                              htl+='</div>';
                            htl+='</div>';
                          htl+='</div>';
                        htl+='</div>';

                        htl+='<div class="width-100 mt-20">';
                          htl+='<div class="row">';
                            htl+='<div class="col-lg-6">';
                              htl+='<div class="form-container pt-10 mb-10"> ';
                                htl+='<ul class="transaction-confirm-list">';
                                  htl+='<li>';
                                
                                    htl+='<label class="fontbold font14 black-txt">Remmiter</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt"><span id="RemitterNam">'+reminame+'</span> - <span id="RemitterNum">'+reminumber+'</span></p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">Beneficiary Name</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_name">'+TXN_REQUEST.name+'</p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">Account No:</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_accnt">'+TXN_REQUEST.accountno+'</p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">Bank</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_bank">'+TXN_REQUEST.bank+'</p>';
                                  htl+='</li>';

                                  htl+='<li>';
                                    htl+='<label class="fontbold font14 black-txt">IFSC</label>';
                                    htl+='<p class="mb-0 font14 light-txt font-medium blue-txt" id="trns_benef_ifsc">'+TXN_REQUEST.ifsccode+'</p>';
                                  htl+='</li>';

                                htl+='</ul>';
                              htl+='</div>';
                      

                             
                            htl+='</div>';

                            htl+='<div class="col-lg-6">';
                               
                                htl+='<div class="width-100 no-of-transaction">';

                                htl+='<div class="width-100 blue-header white-txt mb-10">Number of Transaction</div>';
                                htl+='<table class="table table-bordered font14">';
                                  htl+='<thead>';
                                    htl+='<tr>';
                                      htl+='<th>S#</th>';
                                      htl+='<th>Amount</th>';
                                      htl+='<th>CCF</th>';
                                    htl+='</tr>';
                                  htl+='</thead>';

                                  htl+='<tbody>';
                                    htl+='<tr>';
                                      htl+='<td>1</td>';
                                      htl+='<td>'+response.trnsferamount+'</td>';
                                      htl+='<td>'+response.extra_chrg+'</td>';
                                    htl+='</tr>';
                                  htl+='</tbody>';
                                htl+='</table>';
                              htl+='</div>';
            
                                  htl+='<div class="form-group pl-10 pr-10 text-center">';
                                    htl+='<a class="btn green-btn white-txt confirm-btn" id="procss_txn">Confirm</a>';
                                    htl+='<button class="btn red-btn white-txt" id="cancel_txn">Cancel</button>';
                                  htl+='</div>';
                            htl+='</div>';
                          htl+='</div>';
                        htl+='</div>';
                    htl+='</div>';
                  htl+='</div>';
                 htl+='</div>';
              htl+='</div>';
            htl+='</div>';
            htl+='</div>';
                
  
      
            $("#instntpy_txncnfm").html(htl);  
            
            $('#cancel_txn').click(function(e){
                e.preventDefault();
                
                if(after_login_obj.action===true && after_login_obj.type=="Commission Display Screen")
                {
                
                $("#instntpy_afterlgn").show();  
                $('#TrnsAmnt-' + remitid + '').val('') 
                table.ajax.reload( null, false );      
                $("#instntpy_txncnfm").html('');
                status = true;
                    
                after_login_obj.action=false;
                after_login_obj.type="";
                TXN_REQUEST={};
                    
                }else{
                    toastr.error('Please Wait!!');
                }
        
            });

            $('#procss_txn').click(function(e){
                e.preventDefault();
               
                if(after_login_obj.action===true && after_login_obj.type=="Commission Display Screen")
                {
                
                $(this).addClass('ladda-button').attr({
                    'data-style': 'zoom-in',
                    'data-spinner-color': "#333"
                });
                var la1 = $(this).ladda();
                
                after_login_obj.action=true;
                after_login_obj.type="Initiate Txn Process";    
                    
                TRANSPRCSS(la1)
                    
                }else{
                    toastr.error('Please Wait!!');
                }
            });
        }     
        
        }else{
            toastr.error('Please Wait!!');
        }    
      
      }
      
    function convertNumberToWords(amount) {
    var words = new Array();
    words[0] = '';
    words[1] = 'One';
    words[2] = 'Two';
    words[3] = 'Three';
    words[4] = 'Four';
    words[5] = 'Five';
    words[6] = 'Six';
    words[7] = 'Seven';
    words[8] = 'Eight';
    words[9] = 'Nine';
    words[10] = 'Ten';
    words[11] = 'Eleven';
    words[12] = 'Twelve';
    words[13] = 'Thirteen';
    words[14] = 'Fourteen';
    words[15] = 'Fifteen';
    words[16] = 'Sixteen';
    words[17] = 'Seventeen';
    words[18] = 'Eighteen';
    words[19] = 'Nineteen';
    words[20] = 'Twenty';
    words[30] = 'Thirty';
    words[40] = 'Forty';
    words[50] = 'Fifty';
    words[60] = 'Sixty';
    words[70] = 'Seventy';
    words[80] = 'Eighty';
    words[90] = 'Ninety';
    amount = amount.toString();
    var atemp = amount.split(".");
    var number = atemp[0].split(",").join("");
    var n_length = number.length;
    var words_string = "";
    if (n_length <= 9) {
        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
        var received_n_array = new Array();
        for (var i = 0; i < n_length; i++) {
            received_n_array[i] = number.substr(i, 1);
        }
        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
            n_array[i] = received_n_array[j];
        }
        for (var i = 0, j = 1; i < 9; i++, j++) {
            if (i == 0 || i == 2 || i == 4 || i == 7) {
                if (n_array[i] == 1) {
                    n_array[j] = 10 + parseInt(n_array[j]);
                    n_array[i] = 0;
                }
            }
        }
        value = "";
        for (var i = 0; i < 9; i++) {
            if (i == 0 || i == 2 || i == 4 || i == 7) {
                value = n_array[i] * 10;
            } else {
                value = n_array[i];
            }
            if (value != 0) {
                words_string += words[value] + " ";
            }
            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                words_string += "Crores ";
            }
            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                words_string += "Lakhs ";
            }
            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                words_string += "Thousand ";
            }
            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                words_string += "Hundred and ";
            } else if (i == 6 && value != 0) {
                words_string += "Hundred ";
            }
        }
        words_string = words_string.split("  ").join(" ");
    }
    return words_string;
}
      
    var TRANSPRCSS= function (la1) {

           if(after_login_obj.action===true && after_login_obj.type=="Initiate Txn Process")
           {
                la1.ladda('start');
                after_login_obj.action=true;
                after_login_obj.type="Txn Process is in progress";

                           // $("#instntpy_txncnfm").html(''); 
                            TXN_REQUEST.reminame=reminame;
                            $.ajax({
                                method: 'POST',
                                url: 'MoneyTransfer/AmountTransfer',
                                dataType: 'JSON',
                                data: {TXN_REQUEST:TXN_REQUEST,'mobile':reminumber}
                            }).done(function (response) 
                                    {
                                if (response) 
                                {
                                    
                                  
                                    if (response.error == 0 || response.error == 8) 
                                    {
                                    
                                        after_login_obj.action=true;
                                        after_login_obj.type="Open Print Screen";
                                        
                                        print_screen_view(response);
                          
                                    } 
                                    else if (response.error == 2) 
                                    {
                                        window.location.reload('true');
                                    } else 
                                    {
                                        
                                        toastr.error(response.error_desc);
                                        la1.ladda('stop');
                                        after_login_obj.action=true;
                                        after_login_obj.type="Commission Display Screen";
                                        
                                        
                                    }
                          
                                    
                                }
                            }).fail(function (err) {
                                throw err;
                            });

          }
           else{
            toastr.error('Please Wait!!');
           } 
        
    }

    var print_screen_view = function(txnresponse)
    {
        if(after_login_obj.action===true && after_login_obj.type=="Open Print Screen")
        {
            
        
                if(txnresponse.error==0 || txnresponse.error==8)
                  {  
                      var print_obj_array=[];
                      
                      $("#instntpy_txncnfm").html('');
                      
                        var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
                      
                        var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

                        var totalamount=TXN_REQUEST.transamount;

                        var words = convertNumberToWords(totalamount);// inWords(totalamount);
                        const capitalize = (s) => {
                            if (typeof s !== 'string') return ''
                            return s.charAt(0).toUpperCase() + s.slice(1)
                        }

                        var tant = parseFloat(totalamount);
                        words = capitalize(words);
                        var currency = (tant > 1) ? 'Rupees ' : 'Rupee ';
                      
                      
                                        var htm='<section class="width-100 transaction-status-section">';
                                        htm+='<div class="container">';
                                          htm+='<div class="row">';
                                            htm+='<div class="col-lg-12 mx-auto">';
                                              htm+='<div class="width-100 transaction-status-col">';
                                                htm+='<div class="width-100 transaction-status-header text-center"><p class="mb-0 font18">';
                                                htm+='<span class="" style="margin-top: 5px;">Transaction Details</span></p></div>';
                                                htm+='<div class="width-100 transaction-status-inner pl-15 pr-15">';
                                                    htm+='<div class="width-100">';
                                                      htm+='<div class="transaction-list-details">';
                                                        htm+='<div class="transaction-list-details-col bord-right-set">';
                                                          htm+='<ul>';
                                                            htm+='<li><span class="font14 font-medium black-txt float-left">Outlet Name:</span> <span class="font14 font-medium black-txt float-right">'+txnresponse.outletname+'</span></li>';
                                                            htm+='<li><span class="font14 font-medium black-txt float-left">Sender Name:</span> <span class="font14 font-medium black-txt float-right">'+reminame+'</span></li>';
                                                            htm+='<li><span class="font14 font-medium black-txt float-left">Beneficiary:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.name+'</span></li>';
                                                            htm+='<li><span class="font14 font-medium black-txt float-left">Bank Name:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.bank+'</span></li>';
                                                          htm+='</ul>'; 
                                                        htm+='</div>';

                                                        htm+='<div class="transaction-list-details-col">';
                                                          htm+='<ul>';
                                                            
                                                            htm+='<li><span class="font14 font-medium black-txt float-left">Sender Mobile:</span> <span class="font14 font-medium black-txt float-right">'+reminumber+'</span></li>';
                                                            htm+='<li><span class="font14 font-medium black-txt float-left">Account No:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
                                                        
                                                            htm+='<li><span class="font14 font-medium black-txt float-left">Date & Time:</span> <span class="font14 font-medium black-txt float-right">'+txnresponse.date+'</span></li>';
                                                            
                                                          htm+='</ul>'; 
                                                        htm+='</div>';
                                                      htm+='</div>';
                                                    htm+='</div>';

                                                    htm+='<div class="width-100 mt-20">';
                                                      htm+='<div class="gray-header mb-20">Transaction Summary</div>';  

                                                      htm+='<div class="table-responsive" style="overflow-x: auto !important;">';
                                                        htm+='<table class="table font14 dark-txt">';
                                                          htm+='<thead>';
                                                            htm+='<tr>';
                                                              htm+='<th>FastPay ID</th>';
                                                              htm+='<th>Operator Ref</th>';
                                                              htm+='<th>Amount</th>';
                                                              htm+='<th>CCF</th>';
                                                              htm+='<th>Status</th>';
                                                              htm+='<th>Description</th>';
                                                              
                                                            htm+='</tr>';
                                                          htm+='</thead>';

                                                          htm+='<tbody>';
                                                    if(txnresponse.error==0)
                                                        {
                                                            
                                                            var textclass="";
                                                           textclass=(txnresponse.txndata.status=='SUCCESS')?'text-success':'';
                                                           textclass=(txnresponse.txndata.status=='PENDING')?'text-warning':'';
                                                           textclass=(txnresponse.txndata.status=='FAILED')?'text-danger':'';
                                                            
                                                           var txnccfval=(txnresponse.txndata.status=='FAILED')?0:txnresponse.txndata.ccfval;    
                                                            
                                                            htm+='<tr>';
                                                              htm+='<td>'+txnresponse.txndata.txnid+'</td>';
                                                              htm+='<td>'+txnresponse.txndata.opid+'</td>';
                                                              htm+='<td>'+TXN_REQUEST.transamount+'</td>';
                                                              htm+='<td>'+txnccfval+'</td>';
                                                              htm+='<td class="fontbold '+textclass+'">'+txnresponse.txndata.status+'</td>';
                                                              htm+='<td>'+txnresponse.msg+'</td>';
                                                            htm+='</tr>';
                                                            
                                                            print_obj_array=[{trans:txnresponse.txndata.txnid}];
                                                            
                                                        }else{
                                                            $.each(txnresponse.result,function(rk,rv){
                                                               if(rv.error==0)
                                                                   {
                                                                       var textclass="";
                                                                       textclass=(rv.txndata.status=='SUCCESS')?'text-success':'';
                                                                       textclass=(rv.txndata.status=='PENDING')?'text-warning':'';
                                                                       textclass=(rv.txndata.status=='FAILED')?'text-danger':'';
                                                                       
                                                            var txnccfval=(rv.txndata.status=='FAILED')?0:rv.txndata.ccfval;   
                                                                       
                                                                       htm+='<tr>';
                                                                          htm+='<td>'+rv.txndata.txnid+'</td>';
                                                                          htm+='<td>'+rv.txndata.opid+'</td>';
                                                                          htm+='<td>'+rv.amount+'</td>';
                                                                          htm+='<td>'+txnccfval+'</td>';
                                                                          htm+='<td class="fontbold '+textclass+'">'+rv.txndata.status+'</td>';
                                                                          htm+='<td>'+rv.msg+'</td>';
                                                                        htm+='</tr>';
                                                                       
                                                                       print_obj_array.push({"trans":rv.txndata.txnid});
                                                                       
                                                                   }
                                                                   else{
                                                                        htm+='<tr>';
                                                                          htm+='<td>00</td>';
                                                                          htm+='<td>00</td>';
                                                                          htm+='<td>'+rv.amount+'</td>';
                                                                          htm+='<td>0</td>';
                                                                          htm+='<td class="fontbold text-danger">FAILED</td>';
                                                                          htm+='<td>'+rv.msg+'</td>';
                                                                        htm+='</tr>';
                                                                    }
                                                            });
                                                        }
                                                          htm+='</tbody>';
                                                        htm+='</table>';
                                                      htm+='</div>';

                                                      htm+='<div class="width-100">';
                                                      htm+='<div class="float-left dashed-border"><span class="fontbold font14 light-txt">Total Amount: </span><span class="fontbold font14 dark-txt">Rs '+TXN_REQUEST.transamount+'</span></div>';
                                                      htm+='<div class="float-right dashed-border"><span class="fontbold font14 light-txt">Amount (In Words): </span><span class="fontbold font14 dark-txt">'+ currency + words + '</span></div>';
                                                      htm+='</div>';

                                                      htm+='<div class="width-100 text-center fontbold font12 mt-20 mb-20">';
                                                        htm+='<p class="mb-0">@2019 All Rights Reserved</p>';
                                                        htm+='<p class="mb-0">This is a System generated Receipt. Hence no seal or signature required.</p>';
                                                      htm+='</div>';

                                                      htm+='<div class="width-100 text-center">';
                                                        htm+='<a class="btn btn-dark back-btn white-txt" id="antr_py"><img src="assets/images/left-arrow-white.svg" width="20" class="mr-2">Pay Again </a> ';
                                                        if(print_obj_array.length>0)
                                                        {
                                                        htm+=' <a class="blue-btn btn white-txt" id="print_rcpt"><img src="assets/images/white-printer-icon.svg" width="20" class="mr-2">Print</a>';
                                                        }
                                                      htm+='</div>';



                                                    htm+='</div>';
                                                htm+='</div>';
                                              htm+='</div>';
                                            htm+='</div>';
                                          htm+='</div>';
                                        htm+='</div>';
                                      htm+='</section>';
                                      
                                      $("#instntpy_txn_cnfrmtn_screen").html(htm); 
      
                                        $('#antr_py').click(function(e) 
                                        {
                                        e.preventDefault();
                                        if(after_login_obj.action===true && after_login_obj.type=="Open Print Screen")
                                        {   
                                        $("#instntpy_afterlgn").show();  
                                        table.ajax.reload( null, false ); 
                                        $('#TrnsAmnt-' + remitid + '').val('')                        
                                        $("#instntpy_txn_cnfrmtn_screen").html(''); 
                            
                                        status = true;
                                        after_login_obj.action=false;
                                        after_login_obj.type="";
                                        TXN_REQUEST={};  
                                            
                                        }else{
                                            toastr.error('Please Wait!!');
                                        }
                                        });
                                    
                                    $('#print_rcpt').click(function(e){
                                        e.preventDefault();
                                        $.redirect("MoneyTransfer/PrintTable/1", print_obj_array);
                                    });
                      
                        }
        }
        
    }
    
     $.extend({
            redirect: function (targets, values)
            {
                var form = $("<form>", {attr: {method: "POST", action: targets, target: '_blank'}});
                $("<input>", {attr: {type: "hidden", name: 'checkrow', value: JSON.stringify(values)}}).appendTo(form);
                $(form).appendTo($(document.body)).submit();
            }
        });
    
    var BenefDelVadtnByOTp = function (param, la = null) {

        swal({
            title: "Please Enter OTP For  Beneficiary Delete Verification",
            type: 'input',
        // text: '<button type="button" class="btn btn-primary" id="BenfregisterOTP">Resend OTP</button>',
            html: true,
            allowOutsideClick: false,
            showConfirmButton: true,
            showCancelButton: true,
            inputPlaceholder: 'Enter Your OTP',
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
        },
        function (inputValue) {
            if (inputValue === false) {
                console.log(param)
                param.la.ladda('stop');
                return false;
            }
            if (inputValue === "") {
                swal.showInputError("Please Enter OTP");
                return false
            } else {
                param.otp = inputValue;
                console.log(param.otp);
              

                    param.la = "";
                    forOtp(param, la);
                    console.log(param)
               
            }
        })
    }
    
    var forOtp = function (param, la = null) {
        $.ajax({
            method: 'POST',
            url: 'MoneyTransfer/InstDelCustBenefOtp',
            dataType: 'JSON',
            data: {data: param}
        }).done(function (response) {
            if (response) {
                 // acc_summary();
                
                if (response.error == 0) {
                    table.ajax.reload( null, false );
                  /*   $('#search_trans_val').val('');
                    $('#response_transaction').html(''); */
                    toastr.success('Request Completed Successfully.');
                    swal.close();

                } else if (response.error == 2) {
                    window.location.reload('true');
                } else {
                    if (la){
                        la.ladda('stop');
                    } 
                    toastr.error(response.error_desc);
                    swal.close();
                }

                if (param.la) {
                    param.la.ladda('stop');
                }
                table.ajax.reload( null, false );
                // if (la){
                //     la.ladda('stop');
                // }
            }
        }).fail(function (err) {
            throw err;
        });
    }    
      
     }
  
       /******************************************************* acc_summary**********************************************************/
    
    var acc_summary = function () {
        $.ajax({
            method: 'POST',
            url: 'MoneyTransfer/InstantpyFetchCustomerDetails',    
            dataType: 'JSON',
        }).done(function (response) {
            if (response) {
                console.log(response);
                if (response.error == 0) {
                    $('#total_allowed_limit').html(response.ttl_allwd_lmt);
                    $('#available_limit').html(response.avlbl_lmt);
                    $('#mobile_no').html(response.rmtr_mob);
                    $('#sender_name').html(response.sndr_nam);
                   /*  $('#search_benef_import_val').val(response.rmtr_mob); */

                    reminumber = response.rmtr_mob;
                    remitid = response.remitter_id;
                    reminame=response.sndr_nam;
                } else if (response.error == 2) {
                    window.location.reload('true');
                } else {
                    toastr.error(response.error_desc);
                }
            }
        }).fail(function (err) {
            throw err;
        }); 

    }
        
    /*******************************************************end acc_summary**********************************************************/
    
        
      

    $(document).ready(function(){
        $(".nav-set .nav-item").click(function(){
        $(".nav-set .nav-item").removeClass('active');  
        $(this).addClass('active');
    });

     $('#bbnksel').select2({
        theme: 'bootstrap4',
       }); 
     $('#select_bank').select2({
        theme: 'bootstrap4',
        });

     $('#select-city,#select-bank-name').select2({
        theme: 'bootstrap4',
        }); 

     $('#select-state').select2({theme: 'bootstrap4'});

     $('#select-branch-name').select2({
        theme: 'bootstrap4',
        }); 

     

        var r= $('<button class="btn btn-dark mr-2" id="add-new-benef-btn"><span class="mr-2"><img src="assets/images/add-user.svg" width="20"></span>Add New Beneficiary</button>');
        $(".btn-wrp").append(r);

        $("#add-new-benef-btn").click(function(e)
        {
            e.preventDefault();
            if(after_login_obj.action===false && after_login_obj.type=="")
                {
                    after_login_obj.action=true;
                    after_login_obj.type="Open Bene Add Window";
            $("#add-beneficiary").modal({backdrop:'static', keyboard: false,show:true});
                }
        });
        
        $('#add-beneficiary').on('hide.bs.modal', function () 
        {
            
            if((after_login_obj.action===true && after_login_obj.type=="Open Bene Add Window") || (after_login_obj.action===true && after_login_obj.type=="Bene Add Verify By OTP"))
           {
               
               after_login_obj.action=false;
               after_login_obj.type="";
               $('#bnkinputcol').hide();
               $('#bkinput').val("");
               $('#bbnksel').prop('selectedIndex',0).trigger('change');
               $('#bnkchoose').show();
               $('#bcn,#bbnkifsc,#benefname').val("");
               $('#mainbeneaddidv').show();
               $('#beneaddverificationdiv').hide().html("");
            
           }
            else{
               return false;
               }
        });
  

    });


// var InstanstPayAfterLogin = function () {
   
      

 /*     $(".get-details-btn").click(function(){
        $("#ifsc-detail").show();
       });  */
     
          
    
    
      
   
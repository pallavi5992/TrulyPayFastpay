<?php
$user_data = get_user_details();
$Regex = All_Regex();
$outlet_form=true;

if(isset($Outletdtl))
{
 
    if(is_array($Outletdtl))
    {
    
        if(count($Outletdtl)>0)
        { 
            
            if($Outletdtl['kyc_apibox']==null)
            {
                $outlet_form=true;
            }else{
                $outlet_form=false;
            }
        }
    }
}
?>

<?php 
if($outlet_form===true)
{ 
    
?>
<div class="col-lg-9">
<div class="tab-content width-100 white-bg pl-15 pr-15 pt-15 pb-15">
    <div class="tab-pane fade show active" >
         <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold" id="title_head">Service Activation Form</h6></div>
              <div class="form-container">

                <div id="registration_form">

                    <div class="row row-divider">
                                <div class="col-lg-4 input-col pt-10 pb-10 md-bord-bottom js-validate">  
                                <label class="light-txt font-medium">Contact Name</label>
                                <input type="text" placeholder="Contact Name" class="input-set" id="contct_persn"  <?php if ($user_data['fname']) {
                                echo 'value="' . trim($user_data['fname']) . '"';
                                } ?>  >
                                 <span class="help-block c3-text" data-for="contct_persn"></span>
                                </div> 
                                <div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom js-validate">
                                    <label class="light-txt font-medium">Contact Mobile</label>
                                    <input type="text" placeholder="Contact Mobile" disabled class="input-set" id="contct_nmbr" <?php if ($user_data['mobile']) {
                                        echo 'value="' . trim($user_data['mobile']) . '"';
                                    } ?>  >
                                    <span class="help-block c3-text" data-for="contct_nmbr"></span>
                                </div>
                                <div class="col-lg-4 pt-10 pb-10  md-bord-bottom js-validate">
                                    <label class="light-txt font-medium">Shop name</label>
                                    <input type="text" placeholder="Shop Name" class="input-set" id="shop_name" <?php if ($user_data['business_name']) {
                                            echo 'value="' . trim($user_data['business_name']) . '"';
                                        } ?> >
                                    <span class="help-block c3-text" data-for="shop_name"></span>
                                </div>
                            </div>

                    <div class="row row-divider">
                                 <div class="col-lg-4 pt-10 pb-10 md-bord-bottom js-validate">
                                    <label class="light-txt font-medium">Pincode</label>
                                    <input type="text" placeholder="Pincode" class="input-set" id="shop_pincd" placeholder="" <?php if ($user_data['business_pincode']) {
                                        echo 'value="' . trim($user_data['business_pincode']) . '"';
                                    } ?> />
                                     <span class="help-block c3-text" data-for="shop_pincd"></span>
                                </div>
                                 <div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom js-validate">
                                    <label class="light-txt font-medium">PAN</label>
                                    <input type="text" placeholder="PAN" class="input-set" id="pan"  <?php if ($user_data['pan_num']) {
                                        echo 'value="' . trim($user_data['pan_num']) . '"';
                                    } ?> />
                                     <span class="help-block c3-text" data-for="pan"></span>
                                </div>
                                  <div class="col-lg-4 pt-10 pb-10  md-bord-bottom js-validate">
                                    <label class="light-txt font-medium">AADHAR Number</label>
                                    <input type="text" placeholder="Aadhar" class="input-set" id="aadhaar"  <?php if ($user_data['aadhar']) {
                                        echo 'value="' . trim($user_data['aadhar']) . '"';
                                    } ?>  />
                                      <span class="help-block c3-text" data-for="aadhaar"></span>
                                </div> 
                            </div>

                    <div class="row row-divider">
                                <div class="col-lg-12 input-col pt-10 pb-10  md-bord-bottom js-validate">
                                    <label class="light-txt font-medium">Shop Address</label>
                                    <input type="text" placeholder="Shop Address" class="input-set" id="shopaddr" <?php if ($user_data['business_addr']) {
                                        echo 'value="' . trim($user_data['business_addr']) . '"';
                                    } ?>  />
                                    <span class="help-block c3-text" data-for="shopaddr"></span>
                                </div>
                                </div>

                    <div class="form-group form-group-fieldset Init_activation_div" style="margin-top: 8px;">
                            <button type="submit" class="btn blue-btn mr-2" id="service_act_prcd">Proceed<span class="icn-spc"><i class="fa fa-arrow-right" aria-hidden="true"></i></span></button>
                    </div>

                </div>

            <div id="validate_service_activation" style="display:none;">      
                <div class="Init_otp_div" >
                    <div class="form-group form-group-fieldset"  >
                    <label for="anount">OTP</label>
                    <input type="tel" class="form-control" id="regis_otp" maxlength="6" placeholder="OTP">
                    <span class="help-block c3-text" data-for="regis_otp" ></span>
                    </div>
                </div>
                <div class="form-group form-group-fieldset Init_otp_div" style="margin-top: 8px;">
                    <button type="submit" class="btn blue-btn mr-2" id="regis_user">Register<span class="icn-spc"><i class="fa fa-arrow-right" aria-hidden="true"></i></span></button>
                </div>
            </div>
            </div>

</div>
</div>
</div>
<script>
var service_activate = function(){
    
    var start_activation_form = function(){
        
        var Regex = <?php if($Regex){ echo json_encode($Regex); }else{ echo "{}"; } ?>;
        
        var action_obj = {action:false,type:""};
        
        var validation_regex = function(){
            
             $("#shop_pincd").on('keypress blur keyup keydown', function (e) {
                 var error_msg;
                 this.value = this.value.toUpperCase();
                 var k = e.keyCode || e.which;
                 var id = $(this)[0].id;
                 var str = $(this)[0].value;
                 var length = str.length;
                 var msg = $(this).attr('placeholder');
                 var regacc = new RegExp(Regex.Number);
                 var newregex = new RegExp(Regex.Number);
                 if (regacc.test(str)) {
                     helpBlck({
                         id: id, 'action': 'remove'
                    });
                 }
                 if (k == 8) {
                     if (!newregex.test(str)) {
                        error_msg = (str == '') ? msg + ' Is Required' : 'Invalid ' + msg;
                         helpBlck({
                            'id': id, 'msg': error_msg,
                             'type': 'error'
                         });
                     } else {
                         helpBlck({
                             id: id, 'action': 'remove'});
                     }
                 }

                 if (e.type == 'keypress') {
                     if (k != 8 && k != 9) {
                         k = String.fromCharCode(k);
                         var mb_regex = newregex;
                        if (!mb_regex.test(k)) {
                             return !1
                        }

                        if (length == 6) {
                             return !1
                         }

                    }
                    return !0
                 } else
                 if (e.type == 'blur') {
                    if (str != '') {
                        if (!regacc.test(str)) {
                            helpBlck({
                                 'id': id, 'msg': 'Invalid ' + msg,
                                 'type': 'error'
                             });
                         } else {
                             helpBlck({
                                 id: id, 'action': 'remove'
                             });
                         }
                    } else {
                         helpBlck({
                             'id': id, 'msg': msg + ' Is Required',
                             'type': 'error'
                        });
                    }
                 }
            });  
             $("#contct_nmbr").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Mobile.Full);
                var newregex = new RegExp(Regex.Mobile.Allowed);
                var extmsg = (id == 'user_add_continue_Partymobile') ? 'Party ' : '';
                if (regacc.test(str)) {
                    helpBlck({
                        id: id, 'action': 'remove'
                    });
                }
                if (k == 8) {
                    if (!newregex.test(str)) {
                        error_msg = (str == '') ? msg + ' Is Required' : 'Invalid ' + msg;
                        helpBlck({
                            'id': id, 'msg': error_msg,
                            'type': 'error'
                        });
                    } else {
                        helpBlck({
                            id: id, 'action': 'remove'
                        });
                    }
                }
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = newregex;
                        if (!mb_regex.test(k)) {
                            return !1
                        }
                        var sw_regex = new RegExp(Regex.Mobile.Start);
                        if (length == 0 && !sw_regex.test(k)) {
                            return !1
                        }
                        if (length == 10) {
                            return !1
                        }
                    }
                    return !0
                } else
                if (e.type == 'blur') {
                    if (str != '') {
                        if (!regacc.test(str)) {
                            helpBlck({
                                'id': id, 'msg': 'Invalid ' + msg,
                                'type': 'error'
                            });
                        } else {
                            helpBlck({
                                id: id, 'action': 'remove'
                            });
                        }
                    } else {
                        helpBlck({
                            'id': id, 'msg': msg + ' Is Required',
                            'type': 'error'
                        });
                    }
                }
            });
             $("#regis_otp").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.OTP.Full);
                var newregex = new RegExp(Regex.OTP.Allowed);
                if (regacc.test(str)) {
                    helpBlck({
                        id: id, 'action': 'remove'
                    });
                }
                if (k == 8) {
                    if (!newregex.test(str)) {
                        error_msg = (str == '') ? msg + ' Is Required' : 'Invalid ' + msg;
                        helpBlck({
                            'id': id, 'msg': error_msg,
                            'type': 'error'
                        });
                    } else {
                        helpBlck({
                            id: id, 'action': 'remove'
                        });
                    }
                }
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = newregex;
                        if (!mb_regex.test(k)) {
                            return !1
                        }
                        var sw_regex = new RegExp(Regex.OTP.Start);
                        if (length == 0 && !sw_regex.test(k)) {
                            return !1
                        }
                        if (length == 10) {
                            return !1
                        }
                    }
                    return !0
                } else
                if (e.type == 'blur') {
                    if (str != '') {
                        if (!regacc.test(str)) {
                            helpBlck({
                                'id': id, 'msg': 'Invalid ' + msg,
                                'type': 'error'
                            });
                        } else {
                            helpBlck({
                                id: id, 'action': 'remove'
                            });
                        }
                    } else {
                        helpBlck({
                            'id': id, 'msg': msg + ' Is Required',
                            'type': 'error'
                        });
                    }
                }
            });
             $("#pan").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.PanNumber.Full);
                var newregex = new RegExp(Regex.PanNumber.Allowed);
                if (regacc.test(str)) {
                    helpBlck({
                        id: id, 'action': 'remove'
                    });
                }

                if (k == 8) {
                    if (!newregex.test(str)) {
                        error_msg = (str == '') ? helpBlck({
                            id: id, 'action': 'remove'
                        }) : 'Invalid ' + msg;
                        helpBlck({
                            'id': id, 'msg': error_msg,
                            'type': 'error'
                        });
                    } else {
                        helpBlck({
                            id: id, 'action': 'remove'
                        });
                    }
                }
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = newregex;
                        if (!mb_regex.test(k)) {
                            return !1
                        }
                        if (length == 10) {
                            return !1
                        }
                    }
                    return !0
                } else
                if (e.type == 'blur') {
                    if (str != '') {
                        if (!regacc.test(str)) {
                            helpBlck({
                                'id': id, 'msg': 'Invalid ' + msg,
                                'type': 'error'
                            });
                        } else {
                            helpBlck({
                                id: id, 'action': 'remove'
                            });
                        }
                    } else {
                        helpBlck({'id': id, 'msg': msg + ' Is Required', 'type': 'error'});
                    }
                }
            });
             $("#aadhaar").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.AadharNumber.Full);
                var newregex = new RegExp(Regex.AadharNumber.Allowed);
                if (regacc.test(str)) {
                    helpBlck({
                        id: id, 'action': 'remove'
                    });
                }

                if (k == 8) {
                    if (!newregex.test(str)) {
                        error_msg = (str == '') ? helpBlck({
                            id: id, 'action': 'remove'
                        }) : 'Invalid ' + msg;
                        helpBlck({
                            'id': id, 'msg': error_msg,
                            'type': 'error'
                        });
                    } else {
                        helpBlck({
                            id: id, 'action': 'remove'
                        });
                    }
                }
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = newregex;
                        if (!mb_regex.test(k)) {
                            return !1
                        }
                        if (length == 14) {
                            return !1
                        }
                    }
                    return !0
                } else
                if (e.type == 'blur') {
                    if (str != '') {
                        if (!regacc.test(str)) {
                            helpBlck({
                                'id': id, 'msg': 'Invalid ' + msg,
                                'type': 'error'
                            });
                        } else {
                            helpBlck({
                                id: id, 'action': 'remove'
                            });
                        }
                    } else {
                        helpBlck({'id': id, 'msg': msg + ' Is Required', 'type': 'error'});
                    }
                }
            }); 

            
        }
        var params={valid:false};
        var req_act_btn_la=$('#service_act_prcd').ladda();
        var reg_btn_la=$('#regis_user').ladda();
        
        $('#service_act_prcd').click(function(e){
            e.preventDefault();
            if(action_obj.action===false && action_obj.type==""){
            
            params.contact_person=$('#contct_persn').val();
            //params.contact_mobile=$('#contct_nmbr').val();
            params.shop_name=$('#shop_name').val();
            params.shop_pincd=$('#shop_pincd').val();
            params.pan=$('#pan').val();
            params.aadhaar=$('#aadhaar').val();
            params.shopaddr=$('#shopaddr').val();
            params.valid = true;
            
            if (!validate({'id': 'contct_persn', 'type': 'NAME', 'data': params.contact_person, 'error': true, msg: $('#contct_persn').attr('placeholder')})) {
                params.valid = false;
            }
            
//            if (!validate({'id': 'contct_nmbr', 'type': 'MOBILE', 'data': params.contact_mobile, 'error': true, msg: $('#contct_nmbr').attr('placeholder')})) {
//                params.valid = false;
//            }
            
            if (!validate({'id': 'shop_name', 'type': 'ADDRESS', 'data': params.shop_name, 'error': true, msg: $('#shop_name').attr('placeholder')})) {
                params.valid = false;
            }
            
            if (!validate({'id': 'shop_pincd', 'type': 'PINCODE', 'data': params.shop_pincd, 'error': true, msg: $('#shop_pincd').attr('placeholder')})) {
                params.valid = false;
            }
            
            if (!validate({'id': 'pan', 'type': 'PAN', 'data': params.pan, 'error': true, msg: $('#pan').attr('placeholder')})) {
                params.valid = false;
            } 

            if (!validate({'id': 'aadhaar', 'type': 'AADHAR', 'data': params.aadhaar, 'error': true, msg: $('#aadhaar').attr('placeholder')})) {
                params.valid = false;
            } 
            
            if (!validate({'id': 'shopaddr', 'type': 'ADDRESS', 'data': params.shopaddr, 'error': true, msg: $('#shopaddr').attr('placeholder')})) {
                params.valid = false;
            }
            
            if(params.valid===true)
            {
                $(this).addClass('ladda-button').attr('data-style','zoom-in');
                action_obj.action=true;
                action_obj.type='Request For OTP';
                req_act_btn_la.ladda('start');
                
                $.ajax({
                   "url":"ResisterOutlet/request_for_outlet_registration",
                    "data":params,
                    "dataType":"JSON",
                    "method":"POST"
                }).done(function(otp_reqrespo)
                {
                    if(otp_reqrespo)
                        {
                            if(otp_reqrespo.error==0)
                                {
                                     $('#registration_form').hide();
                                     $('#validate_service_activation').show();
                                    
                                     action_obj.action=true;
                                     action_obj.type='Move to OTP section';
                                    
                                }
                            else if(otp_reqrespo.error==3)
                                {
                                    toastr.info(otp_reqrespo.msg);
                                    setTimeout(function(){
                                        window.location.reload(true);
                                    },1500);
                                    
                                }
                            else if(otp_reqrespo.error==2)
                                {
                                    window.location.reload(true);
                                }
                            else{
                                toastr.error(otp_reqrespo.error_desc);
                                req_act_btn_la.ladda('stop');
                                action_obj.action=false;
                                action_obj.type='';
                            }
                        }
                }).fail(function(err){
                    toastr.error('Something went wrong');
                    req_act_btn_la.ladda('stop');
                    action_obj.action=false;
                    action_obj.type='';
                });
                
            }
                
            }else{
                toastr.error('Please Wait!!');
            }
        });
        
        $('#regis_user').click(function(e){
           e.preventDefault();
           if(action_obj.action===true && action_obj.type=="Move to OTP section")
           {
            
               params.otp=$('#regis_otp').val();
               
               if (!validate({'id': 'regis_otp', 'type': 'OTP', 'data': params.otp, 'error': true, msg: $('#regis_otp').attr('placeholder')})) {
                params.valid = false;
              }
               
               if(params.valid===true)
                   {
                       
                       $(this).addClass('ladda-button').attr('data-style','zoom-in');
                       reg_btn_la.ladda('start');
                       
                       action_obj.action=true;
                       action_obj.tyep='Activation process started';
                       
                       $.ajax({
                           "url":"ResisterOutlet/validate_otp_for_registerservice",
                           "data":params,
                           "dataType":"JSON",
                           "method":"POST"
                       }).done(function(regis_respo){
                           if(regis_respo)
                               {
                                   if(regis_respo.error==0 || regis_respo.error==3)
                                       {
                                           toastr.info(regis_respo.msg);
                                           setTimeout(function(){
                                               window.location.reload(true);
                                           },1200);
                                       }
                                   else if(regis_respo.error==2)
                                       {
                                           window.location.reload(true);
                                       }
                                   else{
                                       action_obj.action=true;
                                       action_obj.type="Move to OTP section";
                                       reg_btn_la.ladda('stop');
                                       toastr.error(regis_respo.error_dsec);
                                   }
                               }
                       }).fail(function(err){
                           action_obj.action=true;
                           action_obj.type="Move to OTP section";
                           reg_btn_la.ladda('stop');
                           toastr.error('Something went wrong, try again later');
                       });
                       
                       
                   }else{
                       toastr.error('Invalid Request');
                   }
               
           }else{
               toastr.error('Please Wait!!');
           }
        });
        
        var helpBlck = function (h) 
        {
            if (typeof h !== 'undefined')
            {
                if (h.action == 'remove')
                {
                    if (typeof h.id === 'undefined') {
                        $('span.help-block').html('').removeClass('text-info');
                        $('span.help-block').each(function () {
                            $(this).closest('.form-group').removeClass('text-danger');
                        })
                    } else {
                        if ($('span[data-for=' + h.id + ']').closest('.form-group').hasClass('text-danger')) {
                            $('span[data-for=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                            $('span[data-for=' + h.id + ']').html('').removeClass('text-info');
                        }
                    }
                }

                if (typeof h.type === 'undefined')
                {
                    h.type = '';
                }

                if (typeof h.id !== 'undefined')
                {
                    if (typeof h.msg !== 'undefined')
                    {
                        if (h.type == 'error')
                        {
                            $('span[data-for=' + h.id + ']').closest('.js-validate').addClass('text-danger');
                            $('span[data-for=' + h.id + ']').html(h.msg).removeClass('text-info');
                        }
                        else if (h.type == 'bulk')
                        {
                            $('span[data-bulk=' + h.id + ']').closest('.js-validate').removeClass('text-danger');
                            $('span[data-bulk=' + h.id + ']').html(h.msg).addClass('text-info');
                        }
                        else
                        {
                            $('span[data-for=' + h.id + ']').closest('.js-validate').removeClass('text-danger');
                            $('span[data-for=' + h.id + ']').html(h.msg).addClass('text-info');
                        }



                    }
                }
            }
        }
	  
        var validate = function (p)
        {
            if (typeof p === 'undefined') {
                return false;
            }
            if (typeof p.id === 'undefined') {
                p.id = '';
            }
            if (typeof p.data === 'undefined') {
                p.data = '';
            }
            if (typeof p.type === 'undefined') {
                p.type = '';
            }
            if (typeof p.error === 'undefined') {
                p.error = false;
            }
            if (typeof p.msg === 'undefined') {
                p.msg = false;
            }

            if (p.type == "NAME")
            {
                var _identifier_regex = Regex.Name;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            } 
            else if (p.type == 'MOBILE') {
                var _identifier_regex = Regex.Mobile.Full;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            } 
            else if (p.type == 'EMAIL') {
                var _identifier_regex = Regex.Email.Full;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            } 
            else if (p.type == 'PAN') {
                var _identifier_regex = Regex.PanNumber.Full;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            }
            else if (p.type == 'PINCODE') {
                var _identifier_regex = Regex.Number;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            }
            else if (p.type == 'OTP') {
                var _identifier_regex = Regex.OTP.Full;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            }
            else if (p.type == 'AADHAR') {
                var _identifier_regex = Regex.AadharNumber.Full;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
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
                        }
            return false;
        }

        
        
    }
    
    return {
      init:function(){
          start_activation_form();
      }  
    };
    
}();
$(document).ready(function(){
    service_activate.init();
});
</script>
            
<?php   

}else if($outlet_form===false)
{  
        
    if($Outletdtl['status']=='PENDING' || $Outletdtl['status']=='ACTIVE')
    {  

    ?>

<div class="col-lg-9">
    <div class="tab-content">
        <div id="bill_payment_div" class="width-100 tab-pane fade active show">
            <div class="width-100 section-top-subheading mb-3">
                <h6 class="dark-txt fontbold float-left">
                    <?php if(isset($page_details)){ if(isset($page_details['title'])){ echo $page_details['title']; } } ?>
                </h6>  
                <div class="operator-logo"></div>
            </div>
            <div id="billpayment_form" class="recharge-form-outer">
                
                <div class="form-group form-group-divder-set row ml-0 mr-0" style="width: 100%;">
                <label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">SELECT OPERATOR</label>
                <div class="col-lg-7 pl-0 pr-0 make_relative">
                <select class="form-control no-brd custom-select font14 dark-txt" placeholder="Operator" id="operator">
                    <option>Select Operator</option>
                </select>
                <span data-for="operator"></span>
                </div>
                </div>
                
                <div id="OpParams">
                </div>
                
                <div class="form-group form-group-divder-set row ml-0 mr-0" id="AmountDiv" style="display:none">
                <label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">AMOUNT IN (Rs)</label>
                <div class="col-lg-7 pl-0 pr-0">
                <input type="text" name="" class="form-control no-brd dark-txt" placeholder="Amount" id="amount">
                <span data-for="amount"></span>
                </div>
                </div>
                
                <div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">
                <button class="btn btn-primary mr-2 submit-btn white-txt" id="proceed">PROCEED</button>
                </div>
                
            </div>
            
            <div id="confirm_div">
            </div>
            
        </div>
    </div>
</div>
<script>
var billpayment= function(){
    
    var Regex = <?php if($Regex){ echo json_encode($Regex); }else{ echo "{}"; } ?>;
    var bill_pay_obj={
        action:false,
        type:"",
        opr_obj:{},
        valR:{minamt:10,maxamt:10},
        bill_fetched:false,
        bill_fetchid:"",
        init:function(){
            
           bill_pay_obj.fetch_opr();
           $("#amount").on('keypress blur keyup keydown', function (e) {
            var error_msg;
            var k = e.keyCode || e.which;
            var id = $(this).attr('id');
            var str = $(this).val();
            var length = str.length;
            var msg = $(this).attr('placeholder');
            var regacc = new RegExp(Regex.Amount);
            if (str == '') {
                helpBlck({id: id, 'action': 'remove'});
            }
            if (k == 8)
            {
                if (!regacc.test(str))
                {
                    error_msg = (str == '') ? msg + ' Is Required' : 'Invalid ' + msg;
                    helpBlck({'id': id, 'msg': error_msg, 'type': 'error'});
                } else {
                    helpBlck({id: id, 'action': 'remove'});
                }
            }
            if (e.type == 'keypress') {

                if (k != 8 && k != 9)

                {
                    k = String.fromCharCode(k);
                    var price_regex = regacc;
                    //console.log(price_regex.test(k));
                    var sw_regex = /[1-9]/;
                    if (length == 0 && !sw_regex.test(k))
                    {
                        return !1
                    }

                    if (!price_regex.test(k))
                    {
                        return !1
                    }


                }

                return !0
            } else if (e.type == 'blur')
            {
                if (str != '') {
                    if (!regacc.test(str))
                    {
                        validate({'id': id, 'type': 'AMOUNT', 'data': str, error: true});
                    } else {
                        helpBlck({id: id, 'action': 'remove'});
                    }
                } else {
                    helpBlck({id: id, 'action': 'remove'});
                }
            }
        });
           bill_pay_obj.start_action();
        },
        fetch_opr:function(){
            bill_pay_obj.opr_obj={};
            $.ajax({
               "url":"BillPayments/get_rchrg_srvc_prvdr",
               "data":{type:"<?php if(isset($page_details)){ echo @$page_details['opr_type']; }else{ echo ""; } ?>"},
               "dataType":"JSON",
               "method":"POST",
           }).done(function(operator_respo){
               if(operator_respo)
                   {
                       if(operator_respo.error==0)
                       {
                           if(operator_respo.msg.length>0)
                               {
                                   var str='<option>Select Operator</option>';
                                   $.each(operator_respo.msg,function(oprk,oprv){
                                       
                                       bill_pay_obj.opr_obj[oprv.code]=oprv;
                                       str+='<option value="'+oprv.code+'" >'+oprv.service_name+'</option>';
                                       
                                       bill_pay_obj.opr_obj[oprv.code].param_obj={}
                                       if(oprv.Params.length>0)
                                           {
                                               $.each(oprv.Params,function(pk,pv){
                                                   bill_pay_obj.opr_obj[oprv.code].param_obj[pv.param_code]=pv;
                                               });
                                           }
                                   });
                                   
                                   
                                   $('#operator').html(str);
                               }
                       }
                       else if(operator_respo.error==2)
                       {
                           window.location.reload(true);
                       }
                       else{
                            toastr.error(operator_respo.error_desc);
                       }
                   }
           }).fail(function(err){
               toastr.error('Something went wrong, while getting operator list'); 
            }); 
            
            $('#operator').change(function(){
                if(bill_pay_obj.action===false && bill_pay_obj.type=="")
                    {
                        $('#OpParams').html("");
                        $('#AmountDiv').hide();
                        $('#amount').val("");
                        
                        var selid=$(this).attr('id');
                        var val=$(this).val();
                        
                        bill_pay_obj.valR.minamt=0;
                        bill_pay_obj.valR.maxamt=0;
                        
                        if(val in bill_pay_obj.opr_obj)
                        {
                            helpBlck({id: selid, 'action': 'remove'});
                            
                            bill_pay_obj.valR.minamt = parseInt(bill_pay_obj.opr_obj[val].min_amt, 10);
                            bill_pay_obj.valR.maxamt = parseInt(bill_pay_obj.opr_obj[val].max_amt, 10);
                            var html='';
                            if(bill_pay_obj.opr_obj[val].Params.length>0)
                                {
                                    
                                    $.each(bill_pay_obj.opr_obj[val].Params,function(pk,pv){
                                        html += '<div class="form-group form-group-divder-set row ml-0 mr-0">';
                                        html += '<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">' + pv.param_name + '</label>';
                                        html += '<div class="col-lg-7 pl-0 pr-0">';
                                        html += '<input type="' + pv.param_type + '" name="'+pv.param_code+'" class="form-control no-brd dark-txt"  placeholder="' + pv.param_name + '" id="' + pv.param_code + '">';
                                        html += '<span data-for="' + pv.param_code + '"></span>';
                                        html += '</div>';
                                        html += '</div>';
                                    });
                                    
                                    
                                }
                            
                            if(bill_pay_obj.opr_obj[val].is_bbps==1)
                                    {
                                        html +='<div class="form-group form-group-divder-set row ml-0 mr-0">';
                                        html += '<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">Customer Mobile No.</label>';
                                        html += '<div class="col-lg-7 pl-0 pr-0">';
                                        html += '<input type="tel" name="bbps_cstmmob" class="form-control no-brd dark-txt"  placeholder="Customer Mobile Number" id="bbps_cstmmob">';
                                        html += '<span data-for="bbps_cstmmob"></span>';
                                        html += '</div>';
                                        html += '</div>';
                                    }
                                    
                                    $('#OpParams').html(html);
                                    
                                    if(bill_pay_obj.opr_obj[val].bill_fetch==1)
                                    {
                                        $('#AmountDiv').hide();   
                                    }else{
                                         $('#AmountDiv').show(); 
                                    }
                                    
                                    bill_pay_obj.dyno_keypress(val);
                            
                        }else if(val=="Select Operator")
                        {
                            helpBlck({id: selid, 'action': 'remove'});
                        }else{
                            helpBlck({'id': selid, 'msg': 'Invalid Operator', 'type': 'error'});
                        }
                        
                    }else{
                        e.preventDefault();
                    }
            });
        },
        dyno_keypress:function(selval){
            if(selval in bill_pay_obj.opr_obj)
                {
                    if(bill_pay_obj.opr_obj[selval].Params.length>0)
                    {
                        $.each(bill_pay_obj.opr_obj[selval].Params,function(pk,pv){
                            
                            $('#'+pv.param_code).on('keypress blur keyup keydown', function (e) {
                                    var k = e.keyCode || e.which;
                                    var id = $(this).attr('id');
                                    var str = $(this).val();
                                    var length = str.length;
                                    var msg = pv.param_name;
                                    var regacc = new RegExp(pv.full_regex);
                                    var newregex = new RegExp(pv.general_regex);


                                    if (regacc.test(str)) {
                                        helpBlck({
                                            id: id, 'action': 'remove'
                                        });
                                    }
                                    if (k == 8) {
                                        if (!newregex.test(str)) {

                                            error_msg = (str == '') ? msg + ' Is Required' : 'Invalid ' + msg;
                                            helpBlck({
                                                'id': id, 'msg': error_msg,
                                                'type': 'error'
                                            });
                                        } else {
                                            helpBlck({
                                                id: id, 'action': 'remove'
                                            });
                                        }
                                    }
                                    if (e.type == 'keypress') {
                                        if (k != 8 && k != 9) {
                                            k = String.fromCharCode(k);
                                            var mb_regex = newregex;
                                            if (!mb_regex.test(k)) {
                                                return !1
                                            }
                                            var sw_regex = new RegExp(pv.start_regex);
                                            if (length == 0 && !sw_regex.test(k)) 
                                            {
                                                return !1
                                            }
                                        }
                                        return !0
                                    } else
                                    if (e.type == 'blur') {   
                                        if (str != '') {
                                            if (!regacc.test(str)) {
                                                helpBlck({
                                                    'id': id, 'msg': 'Invalid ' + msg,
                                                    'type': 'error'
                                                });
                                            } else {
                                                helpBlck({
                                                    id: id, 'action': 'remove'
                                                });
                                            }
                                        } else {
                                            helpBlck({
                                                'id': id, 'msg': msg + ' Is Required',
                                                'type': 'error'
                                            });
                                        }
                                    }
                            });
                            
                        });
                    }
                    
                    if(bill_pay_obj.opr_obj[selval].is_bbps==1)
                    {
                        $('#bbps_cstmmob').on('keypress blur keyup keydown', function (e) {
                            var error_msg;
                            var k = e.keyCode || e.which;
                            var id = $(this).attr('id');
                            var str = $(this).val();
                            var length = str.length;
                            var msg = $(this).attr('placeholder');
                            var regacc = new RegExp(Regex.Mobile.Full);
                            var newregex = new RegExp(Regex.Mobile.Allowed);

                            if (regacc.test(str)) {
                                helpBlck({
                                    id: id, 'action': 'remove'
                                });
                            }
                            if (k == 8) {
                                if (!newregex.test(str)) {

                                    error_msg = (str == '') ? msg + ' Is Required' : 'Invalid ' + msg;
                                    helpBlck({
                                        'id': id, 'msg': error_msg,
                                        'type': 'error'
                                    });
                                } else {
                                    helpBlck({
                                        id: id, 'action': 'remove'
                                    });
                                }
                            }
                            if (e.type == 'keypress') {
                                if (k != 8 && k != 9) {
                                    k = String.fromCharCode(k);
                                    var mb_regex = newregex;
                                    if (!mb_regex.test(k)) {
                                        return !1
                                    }
                                    var sw_regex = new RegExp(Regex.Mobile.Start);
                                    if (length == 0 && !sw_regex.test(k)) {
                                        return !1
                                    }
                                    if (length == 10) {
                                        return !1
                                    }
                                }
                                return !0
                            } else
                            if (e.type == 'blur') {   
                                if (str != '') {
                                    if (!regacc.test(str)) {
                                        helpBlck({
                                            'id': id, 'msg': 'Invalid ' + msg,
                                            'type': 'error'
                                        });
                                    } else {
                                        helpBlck({
                                            id: id, 'action': 'remove'
                                        });
                                    }
                                } else {
                                    helpBlck({
                                        'id': id, 'msg': msg + ' Is Required',
                                        'type': 'error'
                                    });
                                }
                            }
                        });
                    }
                    
                }
        },
        start_action:function(){
            var proceedla=$('#proceed').ladda();
            $('#proceed').click(function(e){
               e.preventDefault();
               if(bill_pay_obj.action===false && bill_pay_obj.type=="")
                    {
                     
                        var params={valid:true};
                        params.spkey=$('#operator').val();
                        
                        if (!validate({'id': 'operator', 'type': 'OPERATOR', 'data': params.spkey, 'error': true, msg: $('#operator').attr('placeholder')})) 
                        {
                            params.valid = false;
                        }
                        
                        if(params.valid===true)
                        {
                            if(bill_pay_obj.opr_obj[params.spkey].Params.length>0)
                                {
                                    $.each(bill_pay_obj.opr_obj[params.spkey].Params,function(k,v){

                                        params[v.param_code]=$('#'+v.param_code).val();
                                        if(!validate({id:v.param_code,data:params[v.param_code],type:"DYNO",error:true,searchin:v,placeholder:true}))
                                        {
                                            params.valid=false;
                                        }

                                    });

                                }else{
                                    params.valid = false;
                                }
                            
                            if(bill_pay_obj.opr_obj[params.spkey].is_bbps==1)
                                {
                                    params.bbps_cstmmob=$('#bbps_cstmmob').val();
                                    if(!validate({id:'bbps_cstmmob',data:params.bbps_cstmmob,type:"MOBILE",error:true,msg: $('#bbps_cstmmob').attr('placeholder')}))
                                    {
                                        params.valid=false;
                                    }
                                    
                                }
                            
                            if(bill_pay_obj.opr_obj[params.spkey].bill_fetch==0)
                                {
                                    params.amount=$('#amount').val();

                                    if(!validate({id:'amount',data:params.amount,type:"AMOUNT",error:true,msg: $('#amount').attr('placeholder')}))
                                    {
                                        params.valid=false;
                                    }
                                }
                        }
                        
                        if(params.valid===true)
                        {
                            params.reqtype='VALIDATE';
                            
                            bill_pay_obj.action=true;
                            bill_pay_obj.type='Validate Billpayment Txn';
                            $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            proceedla.ladda('start');
                            
                            $.ajax({
                                "url":"BillPayments/Process_billpaytxn",
                                "data":params,
                                "dataType":"JSON",
                                "method":"POST"
                            }).done(function(billvalidate_respo){
                                if(billvalidate_respo)
                                {
                                    if(billvalidate_respo.error==0)
                                        {
                                            bill_pay_obj.action=true;
                                            bill_pay_obj.type='Move to Confirm Screen';
                                            
                var confirm='';

                confirm+='<div class="confirm-screen-col">';
                confirm += '<div class="confirm-screen-header text-center font18 fontbold">VERIFY YOUR TRANSACTION DETAILS</div>';
                confirm += '<div class="confirm-screen-inner">';
                confirm += '<ul class="recharge-detail-list">';
                confirm += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator</span><span class="fontbold dark-txt font16">' + bill_pay_obj.opr_obj[params.spkey].service_name + ' - ' + bill_pay_obj.opr_obj[params.spkey].type + '</span></li>';
                                            
                
                
                $.each(bill_pay_obj.opr_obj[params.spkey].Params,function(pk,pv){
                   
                confirm += '<li class="recharge-detail-list-txt">';
                confirm += '<span class="font-medium light-txt font16 mr-5">'+pv.param_name+'</span>';
                confirm += '<span class="fontbold dark-txt font16">' + $('#'+pv.param_code).val() + '</span>';
                confirm += '</li>';    
                    
                });
                                            
                if(bill_pay_obj.opr_obj[params.spkey].is_bbps==1)
                {
                    confirm += '<li class="recharge-detail-list-txt">';
                    confirm += '<span class="font-medium light-txt font16 mr-5">Customer Mobile No.</span>';
                    confirm += '<span class="fontbold dark-txt font16">' + params.bbps_cstmmob + '</span>';
                    confirm += '</li>';  
                }
                                            
                if(bill_pay_obj.opr_obj[params.spkey].bill_fetch==1)
                {
                    
                    params.resultid=billvalidate_respo.resultid;
                    params.amount=billvalidate_respo.billamount;
                    
                    $.each(billvalidate_respo.billdetails,function(bk,bv){
                       if(bk!='Amount'){
                    confirm += '<li class="recharge-detail-list-txt">';
                    confirm += '<span class="font-medium light-txt font16 mr-5">' + bk + '</span>';
                    confirm += '<span class="fontbold dark-txt font16">' + bv + '</span>';
                    confirm += '</li>'; 
                       }
                    });
                    
                    confirm += '<li class="recharge-detail-list-txt">';
                    confirm += '<span class="font-medium light-txt font16 mr-5">Amount</span>';
                    confirm += '<span class="fontbold dark-txt font16">' + params.amount + '</span>';
                    confirm += '</li>';
                    
                }else{
                    confirm += '<li class="recharge-detail-list-txt">';
                    confirm += '<span class="font-medium light-txt font16 mr-5">Amount</span>';
                    confirm += '<span class="fontbold dark-txt font16">' + params.amount + '</span>';
                    confirm += '</li>'; 
                }
                                            
                confirm += '<li>';
                confirm += '<a class="btn confirm-btn mr-2 white-txt" id="billpay_confirm"><span class="btn-side-icon confirm-bg"><img src="assets/images/check.svg" width="12"></span>Confirm</a>';
                confirm += '<a class="btn btn-dark white-txt back-btn" id="bill_back"><span class="btn-side-icon white-bg"><img src="assets/images/left-arrow.svg" width="12" ></span>Back</a>';
                confirm += '</li>';
                confirm += '</ul>';
                confirm += '</div>';
                confirm += '</div>';
                             
                $('#billpayment_form').hide();                            
                $('#confirm_div').html(confirm).show();
                                            
                $('#bill_back').click(function(e){
                    e.preventDefault();
                    if(bill_pay_obj.action===true && bill_pay_obj.type=='Move to Confirm Screen')
                    {
                       
                        $('#confirm_div').hide().html('');
                        $('#billpayment_form').show(); 
                        bill_pay_obj.action=false;
                        bill_pay_obj.type='';
                        proceedla.ladda('stop');
                        
                    }else{
                       toastr.error('Please Wait!!');
                    }
                });  
                  
                var billpay_cnfrmla=$('#billpay_confirm').ladda();                           
                                            
                $('#billpay_confirm').click(function(e){
                   e.preventDefault(); 
                   if(bill_pay_obj.action===true && bill_pay_obj.type=='Move to Confirm Screen')
                    {
                        
                        if(!validate({id:'amount',data:params.amount,type:"AMOUNT",error:true,msg: $('#amount').attr('placeholder')}))
                        {
                            toastr.error('Invalid Transaction Amount');
                            console.log(params);
                            params.valid=false;
                        }
                        
                        if(params.valid===true)
                        {
                            $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            billpay_cnfrmla.ladda('start');
                            bill_pay_obj.action=true;
                            bill_pay_obj.type='Transaction Initiated';
                            params.reqtype='TRANSACT';
                            
                            $.ajax({
                                "url":"BillPayments/Process_billpaytxn",
                                "data":params,
                                "dataType":"JSON",
                                "method":"POST"
                            }).done(function(billpay_txnrespo){
                                if(billpay_txnrespo)
                                    {
                                        if(billpay_txnrespo.error==0)
                                            {
                                                bill_pay_obj.action=true;
                                                bill_pay_obj.type='View Print Screen';
                                                $('#cbbalanch').trigger('click');
                                                bill_pay_obj.print_screen(params,billpay_txnrespo,proceedla);
                                                
                                            }else if(billpay_txnrespo.error==2)
                                            {
                                                window.location.reload(true);
                                            }else{
                                                toastr.error(billpay_txnrespo.error_desc);
                                                billpay_cnfrmla.ladda('stop');
                                                bill_pay_obj.action=true;
                                                bill_pay_obj.type='Move to Confirm Screen';
                                            }
                                    }
                            }).fail(function(){
                                toastr.error('Something went wrong, try again later');
                                billpay_cnfrmla.ladda('stop');
                                bill_pay_obj.action=true;
                                bill_pay_obj.type='Move to Confirm Screen';
                            });
                            
                        }
                        
                        
                    }else{
                        toastr.error('Please Wait!!');
                    }
                });                            
                                            
                                        }
                                    else if(billvalidate_respo.error==2)
                                        {
                                            window.location.reload(true);
                                        }
                                    else{
                                        toastr.error(billvalidate_respo.error_desc);
                                        bill_pay_obj.action=false;
                                        bill_pay_obj.type='';
                                        proceedla.ladda('stop');
                                    }
                                }
                            }).fail(function(err){
                               toastr.error('Something went wrong, try again later'); 
                               bill_pay_obj.action=false;
                               bill_pay_obj.type='';
                               proceedla.ladda('stop');
                            });
                            
                        }
                        else{
                            toastr.error('Validation Failure');
                        }
                        
                    }else{
                        toastr.error('Please Wait');
                    }
            });
        },
        print_screen(params,print_response,proceedla)
        {
            if(bill_pay_obj.action===true && bill_pay_obj.type=="View Print Screen")
                {
                    if(print_response.error==0)
                        {
                            var str='<div class="width-100 payment-status-col" >';
                                str+='<div class="width-100 payment-status-header text-center">';
                                
                                var status_img={
                                    "SUCCESS":"assets/images/check.svg",
                                    "PENDING":"assets/images/pending.svg",
                                    "FAILED":"assets/images/failed.svg"
                                };
                                var imgsrc=(print_response.txndata.status in status_img)?status_img[print_response.txndata.status]:"";
                                
                                str+='<div class="status-icon success-bg text-center"><img src="'+imgsrc+'" onerror="this.style.display = \'none\'" width="40"></div>';
                            
                                str+='<div class="font18 fontbold">' + print_response.msg + '</div>';
                                str+='<div class="font-medium font16 dark-txt">Transaction Receipt</div>';
                                str+='</div>';
                                str+='<div class="payment-status-innercontent">';
									
                                str+='<ul class="recharge-detail-list">';
                                str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + print_response.txndata.status + '</span></li>';
                                str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + print_response.txndata.opid + '</span></li>';

                                str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' +  bill_pay_obj.opr_obj[params.spkey].service_name + ' - ' + bill_pay_obj.opr_obj[params.spkey].type + '</span></li>';
                            
                                $.each(bill_pay_obj.opr_obj[params.spkey].Params,function(pk,pv){
                   
                                str += '<li class="recharge-detail-list-txt">';
                                str += '<span class="font-medium light-txt font16 mr-5">'+pv.param_name+'</span>';
                                str += '<span class="fontbold dark-txt font16">' + $('#'+pv.param_code).val() + '</span>';
                                str += '</li>';    

                                });

                                if(bill_pay_obj.opr_obj[params.spkey].is_bbps==1)
                                {
                                    str += '<li class="recharge-detail-list-txt">';
                                    str += '<span class="font-medium light-txt font16 mr-5">Customer Mobile No.</span>';
                                    str += '<span class="fontbold dark-txt font16">' + params.bbps_cstmmob + '</span>';
                                    str += '</li>';  
                                }
                            
                                str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.amount + '</span></li>';
                                str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + print_response.txndata.status + '</span></li>';
                                str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + print_response.txndata.datetime + '</span></li>';
                                str+='<li class="sm-pl-10 sm-pr-10">';
                                str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                str+='</li>';
                                str+='</ul>';
                                str+='</div>';
                                str+='</div>';
                                
                                $('#confirm_div').html(str);
                            
                            
                                $('#antr_py').click(function(e){
                                   e.preventDefault();
                                    $('#confirm_div').hide().html('');
                                    $('#billpayment_form').show(); 
                                    $('#billpayment_form input').val("");
                                    $('#billpayment_form select').prop("selectedIndex",0);
                                    bill_pay_obj.action=false;
                                    bill_pay_obj.type='';
                                    proceedla.ladda('stop');
                                });
                        }
                }
        }
        
    }
    
    var helpBlck = function (h) {
            if (typeof h !== 'undefined')
            {
                if (h.action == 'remove')
                {
                    if (typeof h.id === 'undefined') {
                        $('span.help-block').html('').removeClass('text-info');
                        $('span.help-block').each(function () {
                            $(this).closest('.form-group').removeClass('text-danger');
                        })
                    } else {
                        if ($('span[data-for=' + h.id + ']').closest('.form-group').hasClass('text-danger')) {
                            $('span[data-for=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                            $('span[data-for=' + h.id + ']').html('').removeClass('text-info');
                        }
                    }
                }

                if (typeof h.type === 'undefined')
                {
                    h.type = '';
                }

                if (typeof h.id !== 'undefined')
                {
                    if (typeof h.msg !== 'undefined')
                    {
                        if (h.type == 'error')
                        {
                            $('span[data-for=' + h.id + ']').closest('.form-group').addClass('text-danger');
                            $('span[data-for=' + h.id + ']').html(h.msg).removeClass('text-info');
                        }
                        else if (h.type == 'bulk')
                        {
                            $('span[data-bulk=' + h.id + ']').closest('.form-group').removeClass('text-danger');
                            $('span[data-bulk=' + h.id + ']').html(h.msg).addClass('text-info');
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

    
    var validate = function (p) {
            if (typeof p === 'undefined') {
                return false;
            }
            if (typeof p.id === 'undefined') {
                p.id = '';
            }
            if (typeof p.data === 'undefined') {
                p.data = '';
            }
            if (typeof p.type === 'undefined') {
                p.type = '';
            }
            if (typeof p.error === 'undefined') {
                p.error = false;
            }
            if (typeof p.msg === 'undefined') {
                p.msg = false;
            }
            if (p.type == "OPERATOR")
            {
                if (p.data != "" && (p.data in bill_pay_obj.opr_obj))
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                }
                else
                {
                    if (p.error === true)
                    {
                        helpBlck({'id': p.id, 'msg': p.msg || 'Invalid Operator', 'type': 'error'});
                    }
                }
            }
            else if(p.type=='DYNO')
            {
                if(p.searchin)
                    {
                        if(p.searchin.param_code==p.id)
                        {
                        var regex=RegExp(p.searchin.full_regex);
                        if(regex.test(p.data))
                            {
                                 helpBlck({id:p.id,'action':'remove'});
                                 return true;
                            }else{
                                if(p.error == true)
                                    {
                                        helpBlck({'id':p.id, 'msg':'Please enter a valid '+p.searchin.param_name+'.', 'type':'error'});
                                    }
                            }
                        }
                    }
            }
            else if (p.type == "NONEMPTY")
            {
               var _identifier_regex = Regex.Text;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            }
            else if (p.type == "AMOUNT")
            {
                var _identifier_regex = Regex.Amount;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error === true)
                    {
                        if (bill_pay_obj.valR.minamt === 0 && bill_pay_obj.valR.maxamt === 0)
                        {
                            helpBlck({'id': p.id, 'msg': 'Invalid Amount', 'type': 'error'});
                        }
                        else if (p.data < bill_pay_obj.valR.minamt)
                        {
                            helpBlck({'id': p.id, 'msg': 'Minimum Amount should be ' + bill_pay_obj.valR.minamt, 'type': 'error'});

                        }
                        else if (p.data > bill_pay_obj.valR.maxamt)
                        {
                            helpBlck({'id': p.id, 'msg': 'Maximum Amount should be ' + bill_pay_obj.valR.maxamt, 'type': 'error'});
                        }
                        else
                        {
                            helpBlck({id: p.id, 'action': 'remove'});
                            return true;
                        }
                    }
                    else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            }
            else if (p.type == "MOBILE")
            {
                var _identifier_regex = Regex.Mobile.Full;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                }
                else
                {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            } 
            
            return false;
        }

    
    
    
    return {
        init:function(){
            bill_pay_obj.init();
        }
    }
    
}();
$(document).ready(function(){
    billpayment.init();
});
</script>    
<?php
        
    }else{   

    ?>



<?php  

}  

}
   
?>

</div>
</div>
</div>
</div>
</section>
</div>
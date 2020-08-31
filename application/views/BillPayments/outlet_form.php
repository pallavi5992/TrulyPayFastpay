<!-- begin:: Content -->
<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$user_data = get_user_details();
?>

<div class="col-lg-9">
 <div class="tab-content width-100 white-bg pl-15 pr-15 pt-15 pb-15">
                            <div class="tab-pane fade show active" id="edit-profile">
                                <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold" id="title_head">Activation Form</h6></div>
                                <div class="form-container">
                                       <form class="mainform" id="mobl_mainform">
                                        <div id="registration_form">
                                        <div class="row row-divider">
                                            <div class="col-lg-4 input-col pt-10 pb-10 md-bord-bottom">  
                                            <label class="light-txt font-medium">CONTACT NAME</label>
                                            <input type="text" class="input-set" id="contct_persn"  <?php if ($user_data['fname']) {
                                            echo 'value="' . trim($user_data['fname']) . '"';
                                            } ?>  >
                                             <span class="help-block c3-text" data-for="contct_persn"></span>
                                            </div> 

                                            <div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
                                                <label class="light-txt font-medium">CONTACT PERSON</label>
                                                <input type="text" class="input-set" id="contct_nmbr" <?php if ($user_data['mobile']) {
                                                    echo 'value="' . trim($user_data['mobile']) . '"';
                                                } ?>  >
                                                <span class="help-block c3-text" data-for="contct_nmbr"></span>
                                            </div>

                                            <div class="col-lg-4 pt-10 pb-10  md-bord-bottom">
                                                <label class="light-txt font-medium">SHOP NAME</label>
                                                <input type="text" class="input-set" id="outlet_name" <?php if ($user_data['business_name']) {
                                                        echo 'value="' . trim($user_data['business_name']) . '"';
                                                    } ?> >
                                                <span class="help-block c3-text" data-for="outlet_name"></span>
                                            </div>
                                        </div>

                                        <div class="row row-divider">

                                             <div class="col-lg-4 pt-10 pb-10 md-bord-bottom">
                                                <label class="light-txt font-medium">PINCODE</label>
                                                <input type="text" class="input-set" id="outlet_pincd" placeholder="" <?php if ($user_data['business_pincode']) {
                                                    echo 'value="' . trim($user_data['business_pincode']) . '"';
                                                } ?> disabled/>
                                            </div>
                                            


                                             <div class="col-lg-4 input-col pt-10 pb-10  md-bord-bottom">
                                                <label class="light-txt font-medium">PAN</label>
                                                <input type="text" class="input-set" id="pan"  <?php if ($user_data['pan_num']) {
                                                    echo 'value="' . trim($user_data['pan_num']) . '"';
                                                } ?> disabled/>
                                            </div>

                                          <!--    <div class="col-lg-4 pt-10 pb-10  md-bord-bottom">
                                                <label class="light-txt font-medium">AADHAR NUMBER</label>
                                                <input type="text" class="input-set" id="aadhaar"  <?php if ($user_data['aadhar']) {
                                                    echo 'value="' . trim($user_data['aadhar']) . '"';
                                                } ?>  disabled/>
                                            </div> -->


                                           
                                        </div>


                                       

                                        <div class="row row-divider">
                                           
                                            <div class="col-lg-12 input-col pt-10 pb-10  md-bord-bottom">
                                                <label class="light-txt font-medium">SHOP ADDRESS</label>
                                                <input type="text" class="input-set" id="outlet_addr" <?php if ($user_data['business_addr']) {
                                                    echo 'value="' . trim($user_data['business_addr']) . '"';
                                                } ?>  disabled/>
                                            </div>
                                           
                                        </div>
                                        </div>

                        <div class="Init_otp_div" style="display:none;">
                            <div class="form-group form-group-fieldset"  >
                            <label for="anount">OTP</label>
                                            <input type="tel" class="form-control" id="regis_otp" maxlength="6" >
                                            <span class="help-block c3-text" data-for="regis_otp" ></span>
                            </div>
                        </div>

                        <div class="form-group form-group-fieldset Init_activation_div" style="margin-top: 8px;">
                            <button type="submit" class="btn blue-btn mr-2" id="outlet_act_prcd">Send OTP<span class="icn-spc"><i class="fa fa-arrow-right" aria-hidden="true"></i></span></button>
                           <!--  <button class="btn blue-btn mr-2" id="updt_pswd">SUBMIT</button> -->
                            <!--button class="btn btn-primary">Search</button-->
                        </div>

                        <div class="form-group form-group-fieldset Init_otp_div" style="display:none; margin-top: 8px;">
                            <button type="submit" class="btn blue-btn mr-2" id="regis_user">Register<span class="icn-spc"><i class="fa fa-arrow-right" aria-hidden="true"></i></span></button>
                            <!--button class="btn btn-primary">Search</button-->
                        </div>

                                    
                                    </form>
                                </div>
                            </div>
                    
                         </div>

                         </div>

<!-- end:: Content -->

<script >
    var Activation = function () {
    var Regex = <?php echo json_encode($Regex); ?>;
    $('#contct_persn,#contct_nmbr,#outlet_name').prop('disabled', true);
    var switchBox = function (aBox, la) {

        if (aBox == 'OTP') 
        {
            $('#registration_form').hide(); 
            $('.Init_activation_div').slideUp('fast');
            $('.Init_activation_inputs input').prop('disabled', true);
            $('.Init_otp_div').slideDown('fast');
            $('.Init_otp_div input').val('');
            $('#regis_otp').prop('disabled', false).focus();
            $('#title_head').html('OTP').show();
        }
        else if (aBox == 'FORM')
        {
            $('#title_head').html('Activation Form').show();
            $('.Init_activation_div').slideDown('fast');
            $('.Init_activation_inputs input').prop('disabled', false);
            $('#regis_otp').prop('disabled', true);
            $('.Init_otp_div').slideUp('fast');
            $('.Init_otp_div input').val('');
             $('#registration_form').show(); 
            la.ladda('stop');
        }
        else if (aBox == 'SUCCESS' || aBox == 'PENDING' || aBox == 'FAILED')  
        {
            //   $('#notice_topup_mobile').removeClass('animated fadeInDown').addClass('animated fadeOutRight').html('');
            $('#notice_topup_mobile').slideUp('fast').html('');

            //   $('#topup_mobile_screen').removeClass('animated fadeOutLeft').addClass('animated fadeInLeft');
            $('#topup_mobile_screen').slideDown('fast');
            $('#topup_mobile_screen input').val('');
            $('#topup_mobile_screen select').val('').trigger('change');
            window.scrollTo(0, 0);
            la.ladda('stop');
        }


    }

    var validateAll = function (v, act)
    {
        var r = "";

        if (v == false)
        {
            return false;
        }

        if (v == "")
        {
            return false;
        }

        if (act == 'name' || act == 'city')
        {
            r = /^[A-Za-z ]+$/;
        }
        else if (act == 'mobile')
        {
            r = /^[6789][0-9]{9}$/;
        }
        else if (act == 'address')
        {
            //r = /^[A-Za-z0-9 &\-\/\',]+$/;
             r=/^[a-zA-Z0-9 !@#$&()-`.+,\"]*$/;
        }
        else if (act == 'shopname')
        {
            // r = /^[A-Za-z0-9 -]+$/;
            r=/^[a-zA-Z0-9 !@#$&()-`.+,\"]*$/;
        }
        else if (act == 'pincode' || act == 'otp')
        {
            r = /^\d{6}$/;
        }
        else if (act == 'pin')
        {
            r = /^[0-9]+$/;
        }
        else if (act == 'email')
        {
            r = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        }
        else if (act == 'alnum')
        {
            r = /^[0-9a-zA-Z]+$/;
        }
        else if (act == 'pan')
        {
           // r = /^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/

            r=/^([A-Z]){5}([0-9]){4}([A-Z]){1}?$/;
        }
        else if (act == 'aadhaar')
        {
            //r = /^\d{12}$/;

             r=/^\\d{4}\\s\\d{4}\\s\\d{4}[0-9 ]$/;
            
        }
        else
        {
            return false;
        }
        return r.test(v);
    }


    var Signup_form = function () {

        var param = {};
        $('#outlet_act_prcd').click(function (e) {
            e.preventDefault();
            param.name = $('#contct_persn').val();
            param.nmbr = $('#contct_nmbr').val();
            param.shopname = $('#outlet_name').val();
            param.shopaddr = $('#outlet_addr').val();
            param.pan = $('#pan').val();
           // param.adhar = $('#aadhaar').val();
            param.pincode = $('#outlet_pincd').val();
            console.log(param)
            if (!validateAll(param.name, 'name'))
            {
                error({type: 'error', msg: 'Please enter a valid name', ext_msg: 'Oops!', focus_id: 'contct_persn'});  

            }
            else if (!validateAll(param.nmbr, 'mobile'))
            {
                // error('ERROR','Please enter a valid mobile');
                error({type: 'error', msg: 'Please enter a valid mobile', ext_msg: 'Oops!', focus_id: 'contct_nmbr'});
            }
            else if (!validateAll(param.shopname, 'shopname'))
            {
                //  error('ERROR','Please enter a valid Shop Name');
                error({type: 'error', msg: 'Please enter a valid Shop Name', ext_msg: 'Oops!', focus_id: 'outlet_name'});
            }
            else if (!validateAll(param.shopaddr, 'address'))
            {
                //error('ERROR','Please enter a valid Shop Address');
                error({type: 'error', msg: 'Please enter a valid Shop Address', ext_msg: 'Oops!', focus_id: 'outlet_addr'});
            }
            else if (!validateAll(param.pan, 'pan'))
            {
                //error('ERROR','Please enter a valid Pan');
                error({type: 'error', msg: 'Please enter a valid Pan', ext_msg: 'Oops!', focus_id: 'pan'});
            }
            // else if (!validateAll(param.adhar, 'aadhaar'))
            // {
            //     //error('ERROR','Please enter valid Aadhaar number');
            //     error({type: 'error', msg: 'Please enter valid Aadhaar number', ext_msg: 'Oops!', focus_id: 'aadhaar'});
            // }
            else if (!validateAll(param.pincode, 'pincode'))
            {

                //error('ERROR','Please enter a valid Pan');
                error({type: 'error', msg: 'Please enter a valid Pincode', ext_msg: 'Oops!', focus_id: 'outlet_pincd'});

            } else {

                $('#outlet_act_prcd').addClass('ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                la.ladda('start');
                $.post('ResisterOutlet/send_fr_outlet_reg', {mob: param.nmbr}, function (response) {
                   
                    if (response)
                    {
                        if (response.error == 0)
                        {
                            error({type: 'info', msg: response.msg});
                            switchBox('OTP', '');
                        }
                        else if (response.error == 2)
                        {
                            window.location.reload(true);
                        }
                        else if (response.error == 3)
                        {
                            error({type: 'error', msg: response.error_desc});
                            setTimeout(function () {
                                window.location.reload(true);
                            }, 1000);

                        }
                        else {
                            error({type: 'error', msg: response.error_desc});
                            la.ladda('stop');
                        }
                    }
                }, 'json').fail(function (err) {
                    la.ladda('stop');
                    //   throw err;
                });


            }

        })


        $('#regis_user').click(function (e) {
            e.preventDefault();
            if (!$.isEmptyObject(param)) {
                param.otp = $('#regis_otp').val();
               
                param.valid = true;

                var la = $(this).ladda();
                if (!validateAll(param.name, 'name')) {
                    param.valid = false;
                }
                if (!validateAll(param.nmbr, 'mobile')) {
                    param.valid = false;
                }
                if (!validateAll(param.shopname, 'shopname')) {
                    param.valid = false;
                }
                if (!validateAll(param.shopaddr, 'address')) {
                    param.valid = false;
                }
                if (!validateAll(param.pan, 'pan')) {
                    param.valid = false;
                }
                // if (!validateAll(param.adhar, 'aadhaar')) {
                //     param.valid = false;
                // }
                if (!validateAll(param.pincode, 'pincode')) {
                    param.valid = false;
                }
                 console.log(param)
                if (param.valid)
                {
                    if (!validateAll(param.otp, 'otp'))
                    {
                        error({type: 'error', msg: 'Invalid OTP', ext_msg: 'Oops!', focus_id: 'regis_otp'});

                    } else {
                        $('#regis_user').addClass('ladda-button').attr('data-style', 'zoom-in');
                        var la = $(this).ladda();
                        la.ladda('start');
                        $.post('ResisterOutlet/ActivateOutlet_withotp', {params: param}, function (response) {
                            if (response)
                            {
                                if (response.error == 0)
                                {
                                    error({type: 'success', msg: response.msg});
                                    setTimeout(function () {
                                        window.location.reload(true);
                                    }, 500)

                                }
                                else if (response.error == 2)
                                {
                                    window.location.reload(true);
                                }
                                else {

                                    error({type: 'error', msg: response.error_desc});
                                    la.ladda('stop');

                                }
                            }
                        }, 'json').fail(function (err) {
                            throw err;
                        })


                    }


                } else {

                    error({type: 'error', msg: 'Invalid Request', ext_msg: 'Oops!'});
                }


            }
        })




    }

    var error = function (p) {

        if (typeof p != undefined)
        {
            if (typeof p.type != undefined) {

                var t = '';

                if (p.type == 'error')
                {
                    t = 'error';
                }
                else if (p.type == 'success')
                {
                    t = 'success';
                }
                else if (p.type == 'info')
                {
                    t = 'info';
                }

                if (t != '')
                {
                    if (typeof p.msg != undefined)
                    {
                        toastr.clear()
                        toastr[t](p.msg, p.ext_msg = (typeof p.ext_msg != undefined) ? p.ext_msg : '');
                    }

                }

            }

            if (typeof p.focus_id != undefined)
            {
                $('#' + p.focus_id).focus();
            }

            if (typeof p.focus_class != undefined)
            {
                $('.' + p.focus_id).focus();
            }


        }

//    if(type=='INFO')
//        {
//            toastr.info(msg);
//        }
//    else if(type=='ERROR')
//        {
//            toastr.error(msg,'Oops!')
//            
//        }else if(type=='SUCCESS'){
//            
//            toastr.success(msg);
//        }

    }


    return {
        init: function () {
            Signup_form();
        }

    }

}();


$(document).ready(function () {
    Activation.init();
})
</script>
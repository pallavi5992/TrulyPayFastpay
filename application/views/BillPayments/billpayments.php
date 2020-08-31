<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$user_data = get_user_details();
$outletStatus = isset($outlet['kyc_apibox'])?$outlet['kyc_apibox']:'';

$dtl=$a;
?> 
<style>
    .operator-logo {
    position: absolute;
    right: 0px;
    z-index: 10;

    right: 28px;
   }
 
/*   .recharge-detail-list-txt span.fontbold {
display: block;
text-align: left;
}

.recharge-detail-list-txt span.font-medium {
margin-right: 0px !important;
display: block;
text-align: left;
}*/

.modal-dialog ul.recharge-details-list li {
border-bottom: 1px dashed #d4cfcf;
margin-bottom: 10px;
padding-bottom: 10px;
list-style:none;
font-size:14px;
}

.modal-dialog ul.recharge-details-list li:last-child
{
border-bottom:none;
margin-bottom: 10px;
padding-bottom: 10px;
}

li.recharge-detail-list-txt {
    display: flex !important;
    width: 100% !important;
    flex-direction: row !important;
}

.recharge-detail-list-txt span.mr-5 {
    text-align: right!important;
  
}

.recharge-detail-list-txt span {
    width: 50%!important;
    text-align: left!important;
}
</style>
        <div class="col-lg-9">
        <div class="tab-content">
        <!--start of confirmation screen-->
        <div class="width-100" id="confirm-screen"></div>
        <!--end of confirmation screen-->
        <!--start of payment status screen-->
        <div id="response"></div>   
        <!--end of payment status screen-->
        <div id="mobile-recharge" class="width-100 tab-pane fade active show">
        <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold float-left"><?php echo $dtl['heading']; ?></h6>  <div class="operator-logo"></div>
        </div>
        <form class="recharge-form-outer">
        
        <div class="form-group form-group-divder-set row ml-0 mr-0" style="width: 100%;">
        <label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">SELECT OPERATOR</label>
        <div class="col-lg-7 pl-0 pr-0 make_relative">
        <select class="form-control no-brd custom-select font14 dark-txt" id="operator">
                                            
        </select>
        <span data-for="operator"></span>
      
        </div>
        </div>
        <div id="OpParams"></div>
        <div class="form-group form-group-divder-set row ml-0 mr-0" id="AmountDiv" style="display:none">
        <label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">AMOUNT IN (Rs)</label>
        <div class="col-lg-7 pl-0 pr-0">
        <input type="text" name="" class="form-control no-brd dark-txt" placeholder="Enter Amount" id="amount">
        <span data-for="amount"></span>
        </div>
        </div>
        <div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">
        <a class="btn btn-primary mr-2 submit-btn white-txt" id="proceed">SUBMIT</a>
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

</div>             
<!--end of wrapper-->
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_muUtBOIDdqmHzdTov2-1K3dzCGSpyvI"></script> -->

<script type="text/javascript">
    $(document).on('change', 'input[type=file]', function () {
        if ($(this).hasClass('custom-file-input')) {
            console.log('file');
            var fl = $(this).prop("files");
            console.log(fl);
            if (fl.length > 0)
            {

                var nm = (typeof fl[0].name === 'undefined') ? '' : fl[0].name;
                nm = (nm.length > 5) ? nm.substring(0, 4) + '..' : nm;

                $(this).closest('.custom-file').find('.custom-file-label').html('C:\/fakepath\/' + nm);
                
            } else {
                $(this).closest('.custom-file').find('.custom-file-label').html('Choose file');
            }
        } else {
            console.log('file not exsist');
        }

    })
</script>
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
<script>
    var BillPayment = function () {
        var Regex = <?php echo json_encode($Regex); ?>

        var opr = {};
        var all_fetch ={} ;
        var amnt_optns = {};
        var bill_amnt={};
        var bill_obj={};
        var sel_amnt;
        var ccf_amnts;
        var sel_otn_amnt;
        var total_pybl;
        var pymnt_md;
        var opr_sp = '';
        var sel_amnt='';
        var dyn_amt = '';
        var fetch_bill = false;
        var quick=false;
        var quick_pay=false;
        var PAYMENT={};
        var prcss = false;
        var cnfrm = false;
        var cnfrm_pay = false;
        var vRule = {
            minamt: 0,
            maxamt: 0,
            minlen: 0,
            maxlen: 10
        }

        $('input#amount').attr('autocomplete', 'on');

        $('#mobileno').attr('maxlength', 10);

        $('#mobileno').on('keypress blur keyup keydown', function (e) {
            var error_msg;
            var k = e.keyCode || e.which;
            var id = $(this)[0].id;
            var str = $(this)[0].value;
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

        $("#amount").on('keypress blur keyup keydown', function (e) {
            var error_msg;
            var k = e.keyCode || e.which;
            var id = $(this)[0].id;
            var str = $(this)[0].value;
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
                        // helpBlck({'id': id, 'msg': 'Invalid ' + msg, 'type': 'error'});
                        validate({'id': id, 'type': 'AMOUNT', 'data': str, error: true});
                    } else {
                        helpBlck({id: id, 'action': 'remove'});
                    }
                } else {
                    helpBlck({id: id, 'action': 'remove'});
                }
            }
        });

        var Rechrge = function () {
           
            $.post('BillPayments/get_rchrg_srvc_prvdr', {type: "<?php print $dtl['operator_typ']; ?>"}, function (response) {
                if (response) {
                    
                    if (response.error == 2)
                    {
                        window.location.reload(true);
                    }
                    else if (response.error == 0)
                    {
                        if (response.msg) {
                            $('#operator').html('');
                            $('#operator').html('<option>Select Operator</option>');

                            $.each(response.msg, function (k, v) {
                                opr[v.code] = v;
                                $('#operator').append($('<option>', {text: v.service_name, value: v.code}));

                            });
                           // $('#operator').select2();
                        }

                    } else {

                        toastr.error(response.error_desc);
                    }
                }
            }, 'json').fail(function (err) {
                throw err;
            })

            $('#operator').change(function (e) {
                var id = $(this).attr('id');
                var value = $(this).val();
                opr_sp = '';
                opr_sp = value;
                if (value === '') {
                    helpBlck({'id': id, 'msg': ''});
                    helpBlck({'id': id, 'msg': '', 'type': 'bulk'});
                    vRule.minamt = 0;
                    vRule.maxamt = 0;
                    vRule.minlen = 0;
                    vRule.maxlen = 10;

                } else {

                    if (value in opr) {
                        // console.log(value);
                        console.log(opr);
                        vRule.minamt = parseInt(opr[value].min_amt, 10);
                        vRule.maxamt = parseInt(opr[value].max_amt, 10);
                        vRule.minlen = parseInt(opr[value].min_len, 10);
                        vRule.maxlen = parseInt(opr[value].max_len, 10);
                        helpBlck({id: id, 'action': 'remove'});
                        helpBlck({'id': id, type: 'error'}); 
                        $('.width-100.table-responsive.mt-2').html(opr[value].helptext_sp);
					    if(opr_sp=='CWB' || opr_sp=='CBB'|| opr_sp=='DBB' || opr_sp=='FBB'|| opr_sp=='ONB'|| opr_sp=='INB'|| opr_sp=='SBB' || opr_sp=='TBB' ){
							$('.operator-logo').html('<img width="70" src="assets/operators/' + opr_sp + '.png"  />'); 
						}else{
							$('.operator-logo').html('<img width="40" src="assets/operators/' + opr_sp + '.png"  />');
						}
                   
                        helpBlck({'id': id, 'msg': opr[value].helptext_amt, 'type': 'bulk'});
                        // if ($('#mobileno').val().length == 10) {
                        //     $("#amount").focus();
                        // }
                        if (opr[value].helptext_sp == '') {
                            $('#logo').css({"display": "flex", "justify-content": "center", "align-items": "center", "height": "500px"})
                        }

                        /*******/
                        var html = '';
                       
                        if (opr[value].Params) {
                            $.each(opr[value].Params, function (k, v) {

                                html += '<div class="form-group form-group-divder-set row ml-0 mr-0">';
                                html += '<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">' + v.param_name + '</label>';
                                html += '<div class="col-lg-7 pl-0 pr-0">';
                                html += '<input type="' + v.param_type + '" name="" class="form-control no-brd dark-txt"  placeholder="' + v.param_name + '" id="' + v.param_code + '">';
                                html += '<span data-for="' + v.param_code + '"></span>';
                                html += '</div>';
                                html += '</div>';

                            });
                            $('#OpParams').html(html);  

                            $.each(opr[value].Params, function (k, v) {
                                $('#' + v.param_code + '').on('keypress blur keyup keydown', function (e) {
                                    var error_msg;
                                    var k = e.keyCode || e.which;
                                    var id = $(this)[0].id;
                                    var str = $(this)[0].value;
                                    var length = str.length;
                                    var msg = $(this).attr('placeholder');
                                    var regacc = new RegExp(Regex.Number);
                                    var newregex = new RegExp(Regex.Number);

                                    if (str == '') {
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

                                    if (e.type == 'keypress') {

                                    } else if (e.type == 'blur') {
                                        if (str != '') {
                                            helpBlck({
                                                id: id, 'action': 'remove'
                                            });
                                        } else {
                                            helpBlck({
                                                'id': id, 'msg': msg + ' Is Required',
                                                'type': 'error'
                                            });
                                        }
                                    }
                                });
                            });

                        } else {

                            $('#OpParams').html('');
                        }
                        // console.log('opr[value]:');
                        // console.log(opr[value]);
                        if (opr[value].is_bbps == 1) {
                            if (opr[value].bill_fetch == 1) {
                                $('#proceed').html('Get Bill');
                                fetch_bill = true;
                                quick=false;

                                $('#AmountDiv').hide();
                                $('#amount').val('').prop('disabled', false);
                                console.log('11');
                            } else {
                                console.log('22');
                                // if(opr[value].validate_required==1){
                              
                             
                                // $('#proceed').html('Quick Pay');
                                // fetch_bill = false;
                                // quick=true;
                                // // $('#amount').val('').prop('disabled', true);
                                // // $('#AmountDiv').slideUp('fast');
                                // // $('#amount').val('');
                                // // helpBlck({id: 'amount', 'action': 'remove'});

                                // }else{


                                /// amount input field////
                                /***validation optional quick pay directly***/
                                $('#proceed').html('Proceed');
                                fetch_bill = false;
                                quick='optinal';
                                $('#amount').val('').prop('disabled', false);
                                $('#AmountDiv').slideDown('fast');
                                $('#amount').val('');
                                helpBlck({id: 'amount', 'action': 'remove'});

                       // }

                        
                            }

                        } else {
                        
                            $('#mobileno').val('');
                            $('#emailid').val();
                            $('#CustMobileNo').hide();

                            $('#proceed').html('Proceed');
                            fetch_bill = false;
                            $('#amount').val('').prop('disabled', false);
                            $('#AmountDiv').slideDown('fast');
                            $('#amount').val('');
                            helpBlck({id: 'amount', 'action': 'remove'});

                        }
                  

                    /********/

                    } else {  

                        $('#hlp-sp').html('');
                        $('#op_img').html('');
                        $('#ccf_rates').html('');
                        $('#bbps_img').html('');
                        $('#proceed').html('Proceed');
                        fetch_bill = false;
                        quick = false;
                        // $('#amount').val('').prop('disabled', false);
                        // $('#AmountDiv').slideUp('fast');
                        // $('#amount').val('');
                         $('#OpParams').html('');
                        $('#CustMobileNo').hide();
                        $('#BillDeatils').html('').hide();
                        // helpBlck({id: 'amount', 'action': 'remove'});
                        helpBlck({id: id, 'msg': 'Please Select Operator', type: 'error'});



                    }
                }
            });

        }

        /*****bill fetch ****/
        var bill_fetch = function (la, params) {
        if (opr[params.spkey].bill_fetch == 1) {

            var dat = {oprKey: params.spkey,parameters: params.Details,servc_typ:params.servc_typ};

            la.ladda('start');
           

            $('#amount').prop('disabled', true);   

            $.post('BillPayments/FetchBbpsBillPayment', dat, function (response) {
                console.log(response)
                response = JSON.parse(response);
               // console.log(response)  
                if (response) { 
                    if (response.error == 0) {
                   
                        var str = '<div class="confirm-screen-col">';
                        str += '<div class="confirm-screen-header text-center font18 fontbold">Bill DETAILS</div>';
                        str += '<div class="confirm-screen-inner">';
                        str += '<ul class="recharge-detail-list">';
                        str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Biller Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + ' - ' + opr[params.spkey].type + '</span></li>';

                        $.each(opr[params.spkey].Params, function (k, v) {
                            params.Details[v.param_code] = $('#' + v.param_code + '').val();
                            str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">' + v.param_name + '</span><span class="fontbold dark-txt font16">' + params.Details[v.param_code] + '</span></li>';


                        });

                        str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Due Amount</span><span class="fontbold dark-txt font16">' + response.response.data.dueamount + '</span></li>';
                        str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Due Date</span><span class="fontbold dark-txt font16">' + response.response.data.duedate + '</span></li>';
                        str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Name </span><span class="fontbold dark-txt font16">' + response.response.data.customername + '</span></li>';


                        str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Bill Number  </span><span class="fontbold dark-txt font16">' + response.response.data.billnumber + '</span></li>';


                        str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Bill Date  </span><span class="fontbold dark-txt font16">' + response.response.data.billdate + '</span></li>';

                        str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Bill Period  </span><span class="fontbold dark-txt font16">' + response.response.data.billperiod + '</span></li>';

                           // $.each(response.response.data.customerparamsdetails, function (k, v) {
                                  
                           //  str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">' + k + '</span><span class="fontbold dark-txt font16">"' + v + '"</span></li>';

                               
                           //  });
                          
                          
                        str += '<div style="text-align:center;"><a class="btn confirm-btn mr-2 white-txt" id="tr-confirm"><span class="btn-side-icon confirm-bg"><img src="assets/images/check.svg" width="12"></span>Confirm</a>';
                        str += '<a class="btn btn-dark white-txt back-btn" id="tr-bk"><span class="btn-side-icon white-bg"><img src="assets/images/left-arrow.svg" width="12" ></span>Back</a></div>';
                        str += '</li>';
                        str += '</ul>';
                        str += '</div>';
                        str += '</div>';

                        $('#confirm-screen').html(str).show();
                        $('#mobile-recharge').hide();

                        switchBox('CONFIRM', la);
                        $('#tr-bk').click(function (e) {
                            e.preventDefault();
                            if (cnfrm === false) {
                                switchBox('FORM', la);
                            }
                        })
                         
                        $('#tr-bk').click(function (e) {
                            e.preventDefault();
                            if(fetch_bill === true && cnfrm === false && quick==false ) {
                                switchBox('FORM', la);
                                  $('#opr_img_side').show();
                            }
                        })

                      
                        $('#tr-confirm').click(function (e) {
                            console.log(fetch_bill)
                            console.log(cnfrm)
                            e.preventDefault();
                            var bill_fecth_la = $(this).ladda();
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var bill_params = {'valid': true};
                             
                          
                            if (bill_params.valid) {
                                if (cnfrm === false) {
                                    cnfrm = true;
                                    bill_fecth_la.ladda('start');
                                    var str ='<div id="modal_default2" class="modal fade" ><div class="modal-dialog">';
                                    str += '<div class="modal-content">';
                                    str += '<div class="modal-header">';
                                    // str += '<button type="button" class="close" data-dismiss="modal" >&times;</button>';
                                    str += '<h5 class="modal-title">Confirm the Details</h5>';
                                    str += ' </div>';
                                    str += ' <div class="modal-body">';
                                    str += '<ul class="recharge-details-list">';
                                    // str += '<li>Operator : <b>' + opr[params.spkey].service_name + ' - ' + opr[params.spkey].type + '</b></li>';
                                      str += '<li>Operator :<b>' + opr[params.spkey].service_name + ' - ' + opr[params.spkey].type + '</b></li>';
                                    $.each(params.Details, function (k, v) {
                                        if (k in opr[params.spkey].Params) {

                                            str += '<li>' + opr[params.spkey].Params[k].param_name + ' : <b>' + v + '</b></li>';
                                        }
                                    });

                                    //   $.each(opr[params.spkey].Params, function (k, v) {
                                    //         params.Details[v.param_code] = $('#' + v.param_code + '').val();
                                    // str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">' + v.param_name + '</span><span class="fontbold dark-txt font16">"' + params.Details[v.param_code] + '"</span></li>';

                                       
                                    // });
                                    /***** delare global****/
 
                            
                                    str += '<li>Consumer Name : <b>' + response.response.data.customername + '</b></li>';
                                    str += '<li>Bill Date : <b>' + response.response.data.billdate + '</b></li>';
                                    str += '<li> Bill Period : <b>' + response.response.data.billperiod + '</b></li>';
                                  
                                    str += '<li>Bill Number : <b>' + response.response.data.billnumber + '</b></li>';
                                  
                                    str += '<li>Bill due Date : <b>' + response.response.data.duedate + '</b></li>';
                                    str += '<li> Due Amount : <b>' + response.response.data.dueamount + '</b></li>';
                                  
                                    str += '<li>Total Amount : <b>"' + response.response.data.dueamount + '"</b></li>';  
                                    str += ' </ul>';  
                                    str += '</div>';
                                    str += '<div class="modal-footer">';
                                    str += '<button type="button" class="btn btn-primary" id="tr-bk-fetch" data-dismiss="modal">BACK</button>';
                                    str += '<button type="button" class="btn btn-primary" id="pay-confirm" data-dismiss="modal">PAY</button>';
                                    str += '</div>';
                                    str += ' </div>';
                                    str += '</div> </div>';

                                    $('body').append(str);
                                    $('#modal_default2').modal({
                                        backdrop: 'static',
                                        keyboard: false,
                                        show: true,
                                    })
                                    $('#modal_default2').on('hidden.bs.modal', function () {
                                        $('#modal_default2').remove();
                                    });


    
                                    /**** pay section**********/  

                                    $('#tr-bk-fetch').click(function (e) {
                                        e.preventDefault();
                                        
                                        if (cnfrm === true ) {
                                           
                                              bill_fecth_la.ladda('stop');
                                             cnfrm =false;
                                             fetch_bill = true;
                                              quick==false;
                                        }
                                    })  

                                    $('#pay-confirm').click(function (e) {
                                        e.preventDefault();
                                        if (cnfrm_pay === false) {
                                            cnfrm_pay = true;

                                            $('#pay-confirm').addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                                            $('#pay-confirm').attr('data-spinner-color', '#000000');
                                            var l2 = $('#pay-confirm').ladda();
                                            $('#tr-bk-fetch').prop('disabled', true);
                                            l2.ladda('start');

                                            PAYMENT.is_billfetch = opr[params.spkey].bill_fetch;
                                            PAYMENT.is_valdtn = opr[params.spkey].validate_required;
                                            PAYMENT.service_name=opr[params.spkey].service_name;  
                                            PAYMENT.Details= params.Details;
                                            
                                            PAYMENT.bill_dt=response.response.data.billdate;
                                            PAYMENT.bill_mode='CASH';
                                           
                                            PAYMENT.py_amnt=response.response.data.dueamount;
                                            PAYMENT.total_pybl=response.response.data.dueamount;
                                            PAYMENT.AgentId = response.agentid;
                                            PAYMENT.spkey= opr[params.spkey].code;
                                            PAYMENT.servc_typ= opr[params.spkey].type;
                                            PAYMENT.customer_number=params.Details.mobileno;
                                            PAYMENT.outletid = response.outletid;
                                            PAYMENT.reference_id = response.response.data.reference_id;    

                                            PAYMENT.lati = latitude;
                                            PAYMENT.longi = longitude;
                    						PAYMENT.custm_mob = "<?php print $user_data['mobile'];?>";

                                            PAYMENT_PROCESS(l2);
                                            
                                            

                                        } else {
                                            toastr.error("Please Wait!!")
                                        }

                                    });
                
                                }

                            }
                /**** pay section**********/


                        })
                        

                          
                    } else if (response.error == 2) {
                        window.location.reload(true);
                    }else if (response.error == 1) {
                        if(response.error_desc){
                            var er = response.error_desc;
                        } else {
                            var er = response.msg;
                        }
                        toastr.error(er,'Oops');
                        
                        
                        /*if (response.disabled == 'Yes') {
                            $('#tamount_div').slideDown('fast');
                            $('#proceed').html('Proceed <i class="icon-arrow-right14 position-right"></i>');
                            $('#amount').prop('disabled', false);
                            fetch_bill = false;
                        } else {
                            toastr.error(response.error_desc);
                        }*/

                    }else if (response.error == 3) {
                        if(response.error_desc != ''){
                            var er = response.error_desc;
                        } else {
                            var er = response.msg;
                        }
                        toastr.error(er,'Oops');
                        
                        /*if (response.disabled == 'Yes') {
                            $('#tamount_div').slideDown('fast');
                            $('#proceed').html('Proceed <i class="icon-arrow-right14 position-right"></i>');
                            $('#amount').prop('disabled', false);
                            fetch_bill = false;
                            //$('#postpaidpanel').unblock();
                        } else {

                            toastr.error(response.msg);

                        }*/

                    } else {
                        if(response.error_desc != ''){
                            var er = response.error_desc;
                        } else {
                            var er = response.msg;
                        }
                        toastr.error(er,'Oops');
                        
                        /*if (response.disabled == 'Yes') {
                            $('#tamount_div').slideDown('fast');
                            $('#proceed').html('Proceed <i class="icon-arrow-right14 position-right"></i>');
                            $('#amount').prop('disabled', false);
                            fetch_bill = false;
                            //$('#postpaidpanel').unblock();
                        } else {

                            toastr.error(response.msg);

                        }*/

                    }
                    la.ladda('stop');
                }
            }).fail(function (err) {  
                throw err;
            })
        } else {
            $('#amount').val('').prop('disabled', false);
            $('#tamount_div').slideDown('fast');
            dyn_amt = '';
        }
    }


        /****bill fetch ****/

      
       //////**** Payment Procees ***********/////
       var  PAYMENT_PROCESS = function(la)
        {
           console.log(PAYMENT)
            if(!PAYMENT || PAYMENT=="" || PAYMENT==null){
                    
                    toastr.error('Invalid Request');
                    la.ladda('stop');
                    return false;
                
            }else if(!PAYMENT.AgentId || PAYMENT.AgentId =="" || PAYMENT.AgentId ==null){
                      toastr.error('Something Went Wrong');
                        la.ladda('stop');
                        return false;

            }else{  
                   
                    if(PAYMENT.is_billfetch==1 &&  PAYMENT.is_valdtn==0){
                    if(fetch_bill==true && quick == false && cnfrm_pay == true){
                        
                    $.post("BillPayments/init_billpayment",{data:PAYMENT},function(response){
                        if(response){

                                $('#cbbalanch').trigger('click');
                                if (response.error == 0) {
                                     console.log(response);
                                  //customer mobileno ibbps ==1//
                                    //customer name  comes if bbps ==1 and bill_fetch =1//

                                   response.TxnId = response.TxnId ? response.TxnId : '00';
                                response.OPTId = response.OPTId ? response.OPTId : '00';  
                                             
                                            var str='<div class="width-100 payment-status-col" id="payment-status">';
												str+='<div class="width-100 payment-status-header text-center">';
												str+='<div class="status-icon success-bg text-center"><img src="assets/images/check.svg" width="40"></div>';
												str+='<div class="font18 fontbold">' + response.msg + '</div>';
												str+='<div class="font-medium font16 dark-txt">BBPS Bill Receipt</div>';
												str+='</div>';
												str+='<div class="payment-status-innercontent">';
									
                                                 str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                         
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' +  PAYMENT.service_name + '- (' + PAYMENT.servc_typ + ')</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' +  PAYMENT.Details.mobileno  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + PAYMENT.custm_mob  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + PAYMENT.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                        str+='<li class="sm-pl-10 sm-pr-10">';
                                                            str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                            str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                                        str+='</li>';
                                                    str+='</ul>';
												str+='</div>';
											    str+='</div>';
                                                $('#response').html(str);
                                                switchBox('SUCCESS', la);

                          

                                } else if (response.error == 1)
                                {
                                    

                                	response.TxnId = response.TxnId ? response.TxnId : '00';
                                     response.OPTId = response.OPTId ? response.OPTId : '00';  
                                              
                                              var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                 str+='<div class="width-100 payment-status-header text-center">';
                                                    str+='<div class="status-icon failed-bg text-center"><img src="assets/images/failed.svg" width="40"></div>';
                                                    str+='<div class="font18 fontbold">' + response.error_desc + '</div>';
                                                    str+='<div class="font-medium font16 dark-txt">BBPS Bill Receipt</div>';
                                                str+='</div>';

                                                str+='<div class="payment-status-innercontent">';
                                                  str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                         
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' +  PAYMENT.service_name + '- (' + PAYMENT.servc_typ + ')</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' +  PAYMENT.Details.mobileno  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + PAYMENT.custm_mob  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + PAYMENT.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                        str+='<li class="sm-pl-10 sm-pr-10">';
                                                            str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                            str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                                        str+='</li>';
                                                    str+='</ul>';
                                                str+='</div>';
                                            str+='</div>';

                                                $('#response').html(str);
                                                switchBox('FAILED', la);
                             
                                    


                                } else if (response.error == 3)
                                {

                                	response.TxnId = response.TxnId ? response.TxnId : '00';
                                     response.OPTId = response.OPTId ? response.OPTId : '00';  
                                                var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                 str+='<div class="width-100 payment-status-header text-center">';
                                                    str+='<div class="status-icon pending-bg text-center"><img src="assets/images/pending.svg" width="40"></div>';
                                                    str+='<div class="font18 fontbold">' + response.msg + '</div>';
                                                    str+='<div class="font-medium font16 dark-txt">Payment Receipt</div>';
                                                str+='</div>';

                                                str+='<div class="payment-status-innercontent">';
                                                  str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                         
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' +  PAYMENT.service_name + '- (' + PAYMENT.servc_typ + ')</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' +  PAYMENT.Details.mobileno  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + PAYMENT.custm_mob  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + PAYMENT.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                        str+='<li class="sm-pl-10 sm-pr-10">';
                                                            str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                            str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                                        str+='</li>';
                                                    str+='</ul>';
                                                str+='</div>';
                                            str+='</div>';


                                                $('#response').html(str);
                                                switchBox('PENDING', la);

                                    
                                } else if (response.error == 2) {
                                    window.location.reload();

                                } else {


                                    response.TxnId = response.TxnId ? response.TxnId : '00';
                                      response.OPTId = response.OPTId ? response.OPTId : '00';           
                                                 var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                 str+='<div class="width-100 payment-status-header text-center">';
                                                    str+='<div class="status-icon pending-bg text-center"><img src="assets/images/pending.svg" width="40"></div>';
                                                    str+='<div class="font18 fontbold">' + response.msg + '</div>';
                                                    str+='<div class="font-medium font16 dark-txt">Payment Receipt</div>';
                                                str+='</div>';

                                                str+='<div class="payment-status-innercontent">';
                                                     str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                         
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' +  PAYMENT.service_name + '- (' + PAYMENT.servc_typ + ')</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' +  PAYMENT.Details.mobileno  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + PAYMENT.custm_mob  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + PAYMENT.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                        str+='<li class="sm-pl-10 sm-pr-10">';
                                                            str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                            str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                                        str+='</li>';
                                                    str+='</ul>';
                                                str+='</div>';
                                            str+='</div>';


                                                $('#response').html(str);
                                                switchBox('PENDING', la);
                                   
                                }
                               // console.log(fetch_bill)
                               // console.log(cnfrm)
                               //   console.log(quick)
                               // console.log(cnfrm_pay)
                               //  fetch_bill=false;
                               //   cnfrm = false;
                               //   prcss = false;
                               //   quick = false;
                               //   cnfrm_pay=false;



                        }  

                    },'json').fail(function(error){throw error;});
                    
                    }

                    }

                }

        }
       //////**** Payment Procees ***********/////
        var submitRequest = function (la) {
             var params = {'valid': true};
            // params.AgentId = response.agentid;
            // params.outletid = response.outletid;
            params.is_billfetch = 0;
            params.bill_mode='CASH';
            params.servc_typ="<?php print $dtl['operator_typ'];?>";
            params.spkey = opr_sp; //  code of service prvdr
            params.lati = latitude;
            params.longi = longitude;
			//params.longi = longitude;
			

            params.total_pybl = ($('#amount').val() == '') ? '' : parseInt($('#amount').val(), 10);

             if (params.spkey in opr) {

             
                params.total_pybl = ($('#amount').val() == '') ? '' : parseInt($('#amount').val(), 10);

                
            }

          
            if (!validate({'id': 'operator', 'type': 'OPERATOR', 'data': params.spkey, 'error': true, msg: $('#operator').attr('placeholder')})) {
                params.valid = false;
            }
           

            params.Details = {};

            $.each(opr[params.spkey].Params, function (k, v) {
                params.Details[v.param_code] = $('#' + v.param_code + '').val();
                   
                if (!validate({'id': '' + v.param_code + '', 'type': 'NONEMPTY', 'data': params.Details[v.param_code], 'error': true, 'msg': $('#' + v.param_code + '').attr('placeholder')})) {
                    params.valid = false;
                }

            });
			/***** customer mobl no passes *****/
			params.custm_mob = "<?php print $user_data['mobile'];?>";

            if (fetch_bill==true) {

                if (params.valid) {
                    bill_fetch(la, params);
                }

            }else {

                if (!validate({'id': 'amount', 'type': 'AMOUNT', 'data': params.total_pybl, 'error': true, 'msg': $('#amount').attr('placeholder')})) {
                    params.valid = false;
                }
            if (params.valid) {
                prcss = true;
                console.log(params);
                la.ladda('start');
                var str = '<div id="modal_default2" class="modal fade" > <div class="modal-dialog">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                // str += '<button type="button" class="close" data-dismiss="modal" >&times;</button>';
                str += '<h5 class="modal-title">Confirm the Details</h5>';
                str += ' </div>';
                str += ' <div class="modal-body">';
                str += '<ul class="recharge-details-list">';
                str += '<li>Operator :<b>' + opr[params.spkey].service_name + ' - ' + opr[params.spkey].type + '</b></li>';
                $.each(params.Details, function (k, v) {
                    if (k in opr[params.spkey].Params) {
                            str += '<li>' + opr[params.spkey].Params[k].param_name + ' : <b>' + v + '</b></li>';
                    }
                });
           
                str += ' <li>Amount : <b>' + params.total_pybl + '</b></li>';
                str += ' </ul>';
                str += '</div>';
                str += '<div class="modal-footer">';
                str += '<button type="button" class="btn btn-link" id="tr-bk" data-dismiss="modal">BACK</button>';
                str += '<button type="button" class="btn btn-primary" id="tr-confirm" data-toggle="modal" data-target="#modal_default">CONFIRM</button>';
                str += '</div>';
                str += ' </div>';
                str += '</div> </div>';
                $('body').append(str);
                $('#modal_default2').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                })
                $('#modal_default2').on('hidden.bs.modal', function () {
                    $('#modal_default2').remove();

                });

                switchBox('CONFIRM', la);
                $('#tr-bk').click(function (e) {
                    e.preventDefault();

                    if (cnfrm === false) {
                        switchBox('FORM', la);
                    }
                })
                /* confirm section**/
                $('#tr-confirm').click(function (e) {
                    e.preventDefault();
                    if (cnfrm === false) {
                        cnfrm = true;
						console.log(params)
                        $('#tr-confirm').addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                        $('#tr-confirm').attr('data-spinner-color', '#000000');
                        var l2 = $('#tr-confirm').ladda();
                        $('#tr-bk').prop('disabled', true);
                        l2.ladda('start');
                        $.post('BillPayments/init_billpayment', {'data': params}, function (response) {
                            if(response) {
                             console.log(response);
                                $('#cbbalanch').trigger('click');
                                if (response.error == 0) {
                                       response.TxnId = response.TxnId ? response.TxnId : '00';
                                             
                                                    var str='<div class="width-100 payment-status-col" id="payment-status">';
        											   str+='<div class="width-100 payment-status-header text-center">';
													   str+='<div class="status-icon success-bg text-center"><img src="assets/images/check.svg" width="40"></div>';
    													str+='<div class="font18 fontbold">' + response.msg + '</div>';
    													str+='<div class="font-medium font16 dark-txt">BBPS Bill Receipt</div>';
        												str+='</div>';
        												str+='<div class="payment-status-innercontent">';
													    str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
														str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
														str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
														str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.Details.mobileno  + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + params.custm_mob  + '</span></li>';
														str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
														str+='<li class="sm-pl-10 sm-pr-10">';
															str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
															str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
														str+='</li>';
													str+='</ul>';
												str+='</div>';
											str+='</div>';
                                                $('#response').html(str);
                                                switchBox('SUCCESS', la);
                                    

                                } else if (response.error == 1)
                                {
                                	response.TxnId = response.TxnId ? response.TxnId :'00';
                                    response.OPTId = response.OPTId?response.OPTId:'00';
                                              
                                              var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                 str+='<div class="width-100 payment-status-header text-center">';
                                                    str+='<div class="status-icon failed-bg text-center"><img src="assets/images/failed.svg" width="40"></div>';
                                                    str+='<div class="font18 fontbold">' + response.error_desc + '</div>';
                                                    str+='<div class="font-medium font16 dark-txt">BBPS Bill Receipt</div>';
                                                str+='</div>';

                                                str+='<div class="payment-status-innercontent">';
                                                    str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.Details.mobileno  + '</span></li>';
                                                          str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + params.custm_mob  + '</span></li>';
                                                        
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                        str+='<li class="sm-pl-10 sm-pr-10">';
                                                            str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                            str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                                        str+='</li>';
                                                    str+='</ul>';
                                                str+='</div>';
                                            str+='</div>';

                                                $('#response').html(str);
                                                switchBox('FAILED', la);
                                          

                                } else if (response.error == 3)
                                {
                                    

                                    response.TxnId = response.TxnId ? response.TxnId : '00';
                                              
                                                 var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                 str+='<div class="width-100 payment-status-header text-center">';
                                                    str+='<div class="status-icon pending-bg text-center"><img src="assets/images/pending.svg" width="40"></div>';
                                                    str+='<div class="font18 fontbold">' + response.msg + '</div>';
                                                    str+='<div class="font-medium font16 dark-txt">BBPS Bill Receipt</div>';
                                                str+='</div>';

                                                str+='<div class="payment-status-innercontent">';
                                                     str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.Details.mobileno  + '</span></li>';
                                                          str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + params.custm_mob  + '</span></li>';
                                                        
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                        str+='<li class="sm-pl-10 sm-pr-10">';
                                                            str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                            str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                                        str+='</li>';
                                                    str+='</ul>';
                                                str+='</div>';
                                            str+='</div>';


                                                $('#response').html(str);
                                                switchBox('PENDING', la);
                                } else if (response.error == 2) {
                                    window.location.reload();
                                } else {

                              

                                    response.TxnId = response.TxnId ? response.TxnId : '00';
                                    response.OPTId = response.TxnId ? response.OPTId : '00';
                                              
                                              
                                                 var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                 str+='<div class="width-100 payment-status-header text-center">';
                                                    str+='<div class="status-icon pending-bg text-center"><img src="assets/images/pending.svg" width="40"></div>';
                                                    str+='<div class="font18 fontbold">' + response.msg + '</div>';
                                                    str+='<div class="font-medium font16 dark-txt">BBPS Bill Receipt</div>';
                                                str+='</div>';

                                                str+='<div class="payment-status-innercontent">';
                                                  str+='<ul class="recharge-detail-list">';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.Details.mobileno  + '</span></li>';
                                                          str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Customer Mobile Number</span><span class="fontbold dark-txt font16">' + params.custm_mob  + '</span></li>';
                                                        
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.total_pybl + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                        str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                        str+='<li class="sm-pl-10 sm-pr-10">';
                                                            str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                            str+='<button class="btn blue-btn back-btn" id="antr_py">Pay Another Bill</button>';
                                                        str+='</li>';
                                                    str+='</ul>';
                                                str+='</div>';
                                            str+='</div>';


                                                $('#response').html(str);
                                                switchBox('PENDING', la);
                                }
                                $('#antr_py').click(function (e) {
                                    e.preventDefault();

                                    switchBox('FORM');
                                });
                                cnfrm = false;
                                prcss = false;
                               

                            }
                        }, 'json').fail(function (err)
                        {
                            cnfrm = false;
                            throw err;
                        });

                    } else {
                        toastr.error("Please Wait!!")
                    }

                });
            }// params valid//


        }
        }

        var switchBox = function (aBox, la) {
            if (aBox == 'CONFIRM')
            {
             
            $('#confirm-screen').show();
             $('#mobile-recharge').hide();
            $('#payment-status').hide().html('');

            }
            else if (aBox == 'FORM')
            {
               
                prcss = false;


                if(fetch_bill== false){
                  
                $('#mobileno').val('');
                $('#amount').val('');
                $('#operator').prop('selectedIndex', 0).trigger('change');
                $('#modal_default2').modal('hide');
                  $('#modal_default').modal('hide');
                if (la) {
                    la.ladda('stop');
                }


                    $("#modal_default").modal('hide');
                    $('#modal_default2').modal('hide');
                    $('#confirm-screen').html('').hide();
                      $('#mobile-recharge').show();
                       $('#payment-status').hide().html('');


                }else if(quick== false){
                  
                   
                          $('#confirm-screen').html('').hide();
                          $('#mobile-recharge').show();
                          $('#operator').prop('selectedIndex', 0).trigger('change');


                            

                }else{
                     

                         
                          $('#mobile-recharge').show();
                          $('#operator').prop('selectedIndex', 0).trigger('change');
                                    fetch_bill=false;
                                     cnfrm = false;
                                     prcss = false;
                                     quick = false;
                                     cnfrm_pay=false;

                            

                }

                $('#mobileno').val('');
                $('#amount').val('');
                $('#operator').prop('selectedIndex', 0).trigger('change');
                $('#modal_default2').modal('hide');
                if (la) {
                    la.ladda('stop');
                }
               
            }
            else if (aBox == 'SUCCESS' || aBox == 'PENDING' || aBox == 'FAILED')
            {
               
               // $('#recharge-form').show();
                $('#modal_default2').modal('hide');
                $("#modal_default").modal('show');
                 $('#payment-status').show();
                  $('#confirm-screen').html('').hide();
                $('#antr_py').click(function () {
                    $("#modal_default").modal('hide');
                    $('#modal_default2').modal('hide');
                      $('#confirm-screen').html('').hide();
                      $('#mobile-recharge').show();
                        $('#payment-status').hide().html('');
                   
                    $('#operator').prop('selectedIndex', 0).trigger('change');
                    $(this).closest('form').find("input[type=text], input[type=tel]").val("");
                    $("#operator").prop("disabled", false);

                                    fetch_bill=false;
                                     cnfrm = false;
                                     prcss = false;
                                     quick = false;
                                     cnfrm_pay=false;
                                     quick_pay = false;
                });

                if (la) {
                    la.ladda('stop');
                }

            }

        }

        $('#proceed').click(function (e) {
            e.preventDefault();

            var la = $(this).ladda();
            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
            if (prcss === false) {
             
                submitRequest(la);
            } else {
                toastr.error('Please wait!!');
            }

        });

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
                if (p.data != "" && (p.data in opr))
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
            }else if (p.type == "SELBILL")
            {

                if (p.data != "" && (p.data in amnt_optns))
                {

                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else if (p.data != "" && (p.data == 100000))
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                }
                else
                {
                    if (p.error === true)
                    {
                        helpBlck({'id': p.id, 'msg': p.msg || 'Invalid Selected Amount', 'type': 'error'});
                    }
                }
            }
            else if (p.type == "SELBILLMODE")
            {
                if (p.data != "" && (p.data in bill_obj))
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                }
                else
                {
                    if (p.error === true)
                    {
                        helpBlck({'id': p.id, 'msg': p.msg || 'Invalid Payment Mode', 'type': 'error'});
                    }
                }
            }
            else if (p.type == "MOBILE")
            {
                var _identifier_regex = Regex.Mobile.Full;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data))
                {
                    if (p.error == true && (p.data.length != vRule.maxlen))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
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
            } else if (p.type == "NONEMPTY")
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
                        if (vRule.minamt === 0 && vRule.maxamt === 0)
                        {
                            helpBlck({'id': p.id, 'msg': 'Invalid Amount', 'type': 'error'});
                        }
                        else if (p.data < vRule.minamt)
                        {
                            helpBlck({'id': p.id, 'msg': 'Minimum Amount should be ' + vRule.minamt, 'type': 'error'});

                        }
                        else if (p.data > vRule.maxamt)
                        {
                            helpBlck({'id': p.id, 'msg': 'Maximum Amount should be ' + vRule.maxamt, 'type': 'error'});
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
            return false;
        }

        return{
            init: function () {
                Rechrge();
            }
        };

    }();

    $(document).ready(function () {
        BillPayment.init();
    })
</script>

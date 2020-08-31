 <style>
     .operator-logo {
    position: absolute;
    right: 0px;
    z-index: 10;
 
    right: 28px;
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
								<div class="width-100" id="confirm-screen">
									
								</div>
							<!--end of confirmation screen-->

							<!--start of payment status screen-->
							<div id="response">
							
							</div>	
							<!--end of payment status screen-->

						   <div id="mobile-recharge" class="width-100 tab-pane fade active show">
						   <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold float-left">MOBILE RECHARGE</h6><div class="operator-logo"></div>
						   </div>
						        <form class="recharge-form-outer">
									<div class="form-group form-group-divder-set row ml-0 mr-0">
										<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">MOBILE NUMBER</label>
										<div class="col-lg-7 pl-0 pr-0">
											<input type="tel" name="" class="form-control no-brd dark-txt"  placeholder="Enter Mobile Number" id="mobileno">
											<span data-for="mobileno"></span>
										</div>
									</div>

									<div class="form-group form-group-divder-set row ml-0 mr-0" style="width: 100%;">
										<label class="col-lg-5 font14 dark-txt font-medium recharge-lbl">SELECT OPERATOR</label>
										<div class="col-lg-7 pl-0 pr-0 make_relative">
											<select class="form-control no-brd custom-select font14 dark-txt" id="operator">
											
											</select>
											<span data-for="operator"></span>
											
										</div>
									</div>

									<div class="form-group form-group-divder-set row ml-0 mr-0">
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

</body>
</html>

<script type="text/javascript">
	var Recharge = function () {
    var opr = {};
    var opr_sp = '';
    var prcss = false;
    var cnfrm = false;
	var isMobile ='';
    var vRule = {
        minamt: 0,
        maxamt: 0,
        minlen: 0,
        maxlen: 10,
        type: 'MOBILE',
        allowed: /[0-9]/,
        startWith: /[6-9]/
    }
    $('input#amount').attr('autocomplete', 'on');
    $('#mobileno').attr('maxlength', 10);


    $('input[type="tel"]').on('keypress keyup blur', function (e) {
        var k = e.keyCode || e.which,
                id = $(this)[0].id,
                str = $(this)[0].value,
                length = str.length;
        if (id == 'mobileno')
        {
            if(e.type == 'keyup')
            {
				if(length == 10)
			{
               
				$.post('Recharge/getopr_bymob',{msisdn:str},function(response){
					
					if(response.error==0)
                        {
                            if(response.msg)
                                {
                                    if(opr.hasOwnProperty(response.msg))
                                        {

                                            $('#operator').val(response.msg).trigger('change');
                                          
                                        }else{
                                            $('#operator').prop('selectedIndex',0).trigger('change');
                                        }
                                }


                        }else if(response.error==2)
                            {
                                window.location.reload(true);
                            }
                        else{
                            toastr.error(response.error_desc);
                          
                        }
				},'json').fail(function(err){ throw err;});
                
			}else{
                $('#operator').prop('selectedIndex',0).trigger('change');
            }
                
            }

            if (e.type == 'keypress')
            {
                if (k != 8 && k != 9)
                {
                    k = String.fromCharCode(k);
                    var mb_regex = /[0-9]/;
                    if (!mb_regex.test(k))
                    {
                        return !1
                    }
                    var sw_regex = /[6-9]/;
                    if (length == 0 && !sw_regex.test(k))
                    {
                        return !1
                    }

                    if (length == 10)
                    {
                        return !1
                    }

                    if (length >= vRule.minlen)
                    {
                        helpBlck({'action': 'remove'});
                    }
                }

                return !0
            }
            else if (e.type == 'blur')
            {
                var _mobile = /^[6789][0-9]{9}$/;
                if (!_mobile.test(str))
                {
                    helpBlck({'id': id, 'msg': 'Invalid Mobile Number', 'type': 'error'});
                } else {
                    if (length != 10)
                    {
                        helpBlck({'id': id, 'msg': 'Invalid Mobile Number', 'type': 'error'});
                    } else {
                        helpBlck({id: id, 'action': 'remove'});
                    }
                }

            }

        }


    });
        
    $('#amount').on('keyup keypress blur', function (e) {

        var k = e.keyCode || e.which,
                id = $(this)[0].id,
                str = $(this)[0].value,
                length = str.length;

        if (e.type == 'keypress')
        {
            if (k != 8 && k != 9)
            {
                k = String.fromCharCode(k);
                var price_regex = /^[0-9]*$/;
                //console.log(price_regex.test(k));
                var sw_regex = /[1-9]/;
                if (length == 0 && !sw_regex.test(k))
                {
                    return !1
                }
                if (length == 5)
                {
                    return !1
                }
                if (!price_regex.test(k))
                {
                    return !1
                }


            }
            if (length > 0)
            {
                helpBlck({id: id, 'action': 'remove'});
            }
            return !0
        }
        else if (e.type == 'blur')
        {
            var min_price = /^[1-9][0-9]*$/;

            if (!min_price.test(str))
            {
                //$(this).val('')
                validate({'id': id, 'type': 'AMOUNT', 'data': str, error: true});
            }

        }


    });
        
    var Rechrge = function () {

        $.post('Recharge/get_rchrg_srvc_prvdr', {type: 'PREPAID'}, function (response) {
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
			
			if(!isMobile)
            {
			  if( opr_sp != '' && opr_sp!='Select Operator'){
				 $('.operator-logo').html('<img width="40" src="assets/operators/' + opr_sp + '.png"  />');
			  }else{
				 $('.operator-logo').html('');
			  }
			}else{
			  if(opr_sp != '' && opr_sp!='Select Operator'){
				 $('.operator-logo').html('<img width="40" src="assets/operators/' + opr_sp + '.png"  />');
			  }else{
				  $('.operator-logo').html('');
			  }
			}
		
            if (value === '' || value=='Select Operator') 
            {
                helpBlck({'id': id, 'msg': ''});
                helpBlck({'id': id, 'msg': '', 'type': 'bulk'});

                vRule.minamt = 0;
                vRule.maxamt = 0;
                vRule.minlen = 0;
                vRule.maxlen = 10;

            } else {

                if (value in opr) 
                {

                    vRule.minamt = parseInt(opr[value].min_amt, 10);
                    vRule.maxamt = parseInt(opr[value].max_amt, 10);
                    vRule.minlen = parseInt(opr[value].min_len, 10);
                    vRule.maxlen = parseInt(opr[value].max_len, 10);
                    helpBlck({id: id, 'action': 'remove'});
                    helpBlck({'id': id, type: 'error'});
                    $('.width-100.table-responsive.mt-2').html(opr[value].helptext_sp);
                    $('.operator-logo').html('<img width="40" src="assets/operators/' + opr_sp + '.png"  />');
                    helpBlck({'id': id, 'msg': opr[value].helptext_amt, 'type': 'bulk'});
                    if ($('#mobileno').val().length == 10) 
                    {
                        $("#amount").focus();
                    }
                    if (opr[value].helptext_sp == '') 
                    {
                          $('.width-100.table-responsive.mt-2').html('');
                    }
                    
                } else {

                    $('.width-100.table-responsive.mt-2').html('');
                    $('.operator-logo').html('');
                    helpBlck({id: id, 'msg': 'Please select Operator', type: 'error'});

                }
            }
        });

    }
    
    var submitRequest = function (la)
    {
        if (prcss === false && cnfrm === false) 
        {
            var params = {'valid': true};
            params.account = $('#mobileno').val();
            params.spkey = opr_sp; //  code of service prvdr
            params.amount = ($('#amount').val() == '') ? '' : parseInt($('#amount').val(), 10);

            if (!validate({'id': 'mobileno', 'type': 'MOBILE', 'data': params.account, 'error': true})) {
                params.valid = false;
            }
            if (!validate({'id': 'operator', 'type': 'OPERATOR', 'data': params.spkey, 'error': true})) {
                params.valid = false;
            }
            if (!validate({'id': 'amount', 'type': 'AMOUNT', 'data': params.amount, 'error': true})) {
                params.valid = false;
            }

            if (params.valid) 
            {
                prcss = true;
                
                la.ladda('start');
                
                var str = '<div class="confirm-screen-col">';
                 str += '<div class="confirm-screen-header text-center font18 fontbold">VERIFY YOUR RECHARGE DETAILS</div>';
                                             str += '<div class="confirm-screen-inner">';
                                                 str += '<ul class="recharge-detail-list">';
                str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Mobile Number</span><span class="fontbold dark-txt font16">' + params.account + '</span></li>';
                 str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + ' - ' + opr[params.spkey].type + '</span></li>';
                                                     str += '<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.amount + '</span></li>';
                                                     str += '<li>';
                                                         str += '<a class="btn confirm-btn mr-2 white-txt" id="tr-confirm"><span class="btn-side-icon confirm-bg"><img src="assets/images/check.svg" width="12"></span>Confirm</a>';
                                                         str += '<a class="btn btn-dark white-txt back-btn" id="tr-bk"><span class="btn-side-icon white-bg"><img src="assets/images/left-arrow.svg" width="12" ></span>Back</a>';
                                                     str += '</li>';
                                                 str += '</ul>';
                                             str += '</div>';
                                         str += '</div>';


                $('#confirm-screen').html(str).show();
                
                switchBox('CONFIRM', la);
                
                $('#tr-bk').click(function (e) {
                    e.preventDefault();
                    if (cnfrm === true && prcss===true) 
                    {
                        switchBox('FORM', la);
                    }
                })
                /* confirm section**/
                $('#tr-confirm').click(function (e) {
                    e.preventDefault();
                    if (cnfrm === true && prcss === true) 
                    {
                        prcss = false;
                        
                        $('#tr-confirm').addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                        $('#tr-confirm').attr('data-spinner-color', '#000000');
                        var l2 = $('#tr-confirm').ladda();
                       // $('#tr-bk').prop('disabled', true);
                        l2.ladda('start');

                        $.post('Recharge/init_rchrge', {'data': params}, function (response) {
                                            if (response) {
                                                $('#bal_usr').trigger('click');
                                                if (response.error == 0) 
                                                {
                                                    response.TxnId = response.TxnId ? response.TxnId : '00';

                                                  var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                     str+='<div class="width-100 payment-status-header text-center">';
                                                        str+='<div class="status-icon success-bg text-center"><img src="assets/images/check.svg" width="40"></div>';
                                                        str+='<div class="font18 fontbold">' + response.msg + '</div>';
                                                        str+='<div class="font-medium font16 dark-txt">Payment Receipt</div>';
                                                    str+='</div>';

                                                    str+='<div class="payment-status-innercontent">';
                                                        str+='<ul class="recharge-detail-list">';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.account + '</span></li>';

                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.amount + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                            str+='<li class="sm-pl-10 sm-pr-10">';
                                                                str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                                str+='<button class="btn blue-btn back-btn" id="antr_py">Recharge Again</button>';
                                                            str+='</li>';
                                                        str+='</ul>';
                                                    str+='</div>';
                                                str+='</div>';
                                                    $('#response').html(str);
                                                    switchBox('SUCCESS', la);

                                                    // print = {orderID: response.TxnId, opr_txn_id: response.OPR_TXN, TrDate: response.date, SPName: opr[params.spkey].name, SPType: opr[params.spkey].type, CustID: params.account, RAmount: params.amount, Status: response.status};

                                                    // $('#printtd').on('click', function () {
                                                    //     console.log(print)
                                                    //     $.redirect("Recharge/PrintMobileRecharge/1", print);


                                                    // });

                                                    // $.extend({
                                                    //     redirect: function (targets, values)
                                                    //     {
                                                    //         var form = $("<form>", {attr: {method: "POST", action: targets, target: '_blank'}});
                                                    //         $("<input>", {attr: {type: "hidden", name: 'checkrow', value: JSON.stringify(values)}}).appendTo(form);
                                                    //         $(form).appendTo($(document.body)).submit();
                                                    //     }
                                                    // });

                                                } else if (response.error == 1)
                                                {
                                                    response.TxnId = response.TxnId ? response.TxnId : '00';
                                                     response.OPTId = response.OPTId ? response.OPTId : '00';

                                                  var str='<div class="width-100 payment-status-col" id="payment-status">';
                                                     str+='<div class="width-100 payment-status-header text-center">';
                                                        str+='<div class="status-icon failed-bg text-center"><img src="assets/images/failed.svg" width="40"></div>';
                                                        str+='<div class="font18 fontbold">' + response.error_desc + '</div>';
                                                        str+='<div class="font-medium font16 dark-txt">Payment Receipt</div>';
                                                    str+='</div>';

                                                    str+='<div class="payment-status-innercontent">';
                                                        str+='<ul class="recharge-detail-list">';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.account + '</span></li>';

                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.amount + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                            str+='<li class="sm-pl-10 sm-pr-10">';
                                                                str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                                str+='<button class="btn blue-btn back-btn" id="antr_py">Recharge Again</button>';
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
                                                        str+='<div class="font-medium font16 dark-txt">Payment Receipt</div>';
                                                    str+='</div>';

                                                    str+='<div class="payment-status-innercontent">';
                                                        str+='<ul class="recharge-detail-list">';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">FastPay ID</span><span class="fontbold dark-txt font16">' + response.TxnId + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Operator Ref</span><span class="fontbold dark-txt font16">' + response.OPTId + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.account + '</span></li>';

                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.amount + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                            str+='<li class="sm-pl-10 sm-pr-10">';
                                                                str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                                str+='<button class="btn blue-btn back-btn" id="antr_py">Recharge Again</button>';
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
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Service Name</span><span class="fontbold dark-txt font16">' + opr[params.spkey].service_name + '- (' + opr[params.spkey].type + ')</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Number</span><span class="fontbold dark-txt font16">' + params.account + '</span></li>';

                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Amount</span><span class="fontbold dark-txt font16">' + params.amount + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Status</span><span class="fontbold dark-txt font16">' + response.status + '</span></li>';
                                                            str+='<li class="recharge-detail-list-txt"><span class="font-medium light-txt font16 mr-5">Transaction Date</span><span class="fontbold dark-txt font16">' + response.date + '</span></li>';
                                                            str+='<li class="sm-pl-10 sm-pr-10">';
                                                                str+='<div class="font18 light-txt mb-2">THANK YOU FOR YOUR TRANSACTION WITH US</div>';
                                                                str+='<button class="btn blue-btn back-btn" id="antr_py">Recharge Again</button>';
                                                            str+='</li>';
                                                        str+='</ul>';
                                                    str+='</div>';
                                                str+='</div>';


                                                    $('#response').html(str);
                                                    switchBox('PENDING', la);
                                                }
                                                
                                                prcss=true;
                                                $('#antr_py').click(function (e) {
                                                    e.preventDefault();
                                                    switchBox('FORM');
                                                });
                                                
                                            }
                                        }, 'json').fail(function (err)
                                        {
                                            cnfrm = false;
                                            throw err;
                                        });
                    }else {

                        toastr.error("Please Wait!!")
                    }

                })

                /*** confirm section end ****/

}

        }else{
            toastr.error('Please wait!!');
        }
        
    }
    
    var switchBox = function (aBox, la)
    {
        if (aBox == 'CONFIRM')
        {
            if(prcss===true && cnfrm===false)
            {
                cnfrm=true;
            $('#confirm-screen').show();
            $('#payment-status').hide().html('');
            $('#mobile-recharge').hide();
                
            }

        }
        else if (aBox == 'FORM')
        {
            
            if(prcss===true && cnfrm===true)
            {
            
            prcss = false;
            cnfrm = false;
            $('#mobile-recharge').show();
            $('#mobileno').val('');
            $('#amount').val('');
            $('#operator').prop('selectedIndex', 0).trigger('change');
            $('#confirm-screen').hide().html('');
            $('#payment-status').hide().html('');
            if (la) {
                la.ladda('stop');
            }
            $('#antr_py').click(function () {
                $(this).closest('form').find("input[type=text], input[type=tel]").val("");
                $("#operator").prop("disabled", false);
            });
                
            }
        }
        else if (aBox == 'SUCCESS' || aBox == 'PENDING' || aBox == 'FAILED')
        {
            if(prcss===false && cnfrm===true)
            {
                $('#payment-status').show();
                $('#mobile-recharge').hide();
                $('#confirm-screen').hide();
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
                        console.log(('span[data-for=' + h.id + ']'));
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
                    //error({'id':p.id, 'action':'set', 'msg': p.msg || 'Invalid Service Provider'});
                    helpBlck({'id': p.id, 'msg': p.msg || 'Invalid Operator', 'type': 'error'});
                }
            }
        }
        else if (p.type == "MOBILE")
        {
            console.log(p);
            var _mobile = /^[6789][0-9]{9}$/;
            if (_mobile.test(p.data))
            {
                if (p.error == true && (p.data.length != vRule.maxlen))
                {
                    console.log(p.data.length);
                    console.log(vRule.maxlen);
                    helpBlck({'id': p.id, 'msg': 'Invalid Mobile Number', 'type': 'error'});
                }
                else
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                }
            }
            else
            {
                if (p.error == true)
                {
                    console.log('error');
                    helpBlck({'id': p.id, 'msg': 'Invalid Mobile Number', 'type': 'error'});
                }

            }
        }
        else if (p.type == "AMOUNT")
        {
            if (isNaN(p.data) || p.data.length > 5)
            {
                if (p.id != '') {
                    $("#" + p.id).val('');
                }
            }
            else
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
    Recharge.init();
})
</script>
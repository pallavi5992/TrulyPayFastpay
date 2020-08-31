<?php 

$available_Settlement_amt=@$available_Settlement_amt; 

$Regex = All_Regex();

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
								<div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold float-left">AePS Transaction</h6></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="text-center">
                                    <a class="btn btn-primary mr-2 submit-btn white-txt" id="aeps_prcss">Initiate Transaction</a>
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                <div class="col-md-8">
                                <div class="row">
                                <div class="col-md-12">
                                        <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold float-left">Payment Settlement</h6></div>
                                    </div>
                                <div class="col-md-4">
                                <div class="form-group">
                                   <label for="name" class="">Available Balance</label>
                                    <h5><a href="javacript:void(0);" style="text-decoration:none;" class="js-reload_aepsbal" id="avail_aepsbal">&#8377; <?=$available_Settlement_amt?></a></h5>
                                </div> 
                                </div>
                                <div class="col-md-4">
                                <div class="form-group">
                                   <label for="name" class="">Withdrawable Balance</label>
                                    <h5><a href="javacript:void(0);" style="text-decoration:none;" class="js-reload_aepsbal" id="withdrwal_aepsbal">View</a></h5>
                                </div> 
                                </div>
                                <div class="col-md-4">
                                <div class="form-group js-validate-frm">
                                    <label for="mobile">Amount</label>
                                    <input type="tel" class="form-control" id="settle_amt" placeholder="Amount">
                                    <span class="invalid-feedback" data-for="settle_amt"></span>
                                </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="form-group js-validate-frm">
                                     <button type="button" class="btn btn-primary btn-block" id="settle_sbmt">
                                         Proceed
                                    </button>  
                                    </div>
                                </div>    
                                </div>
                                </div>
									 </div>
								
						    </div>
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
<script>
    var Transaction = function () {

        var gateway_form = function () {

            var param = {};
            var result = {};
            $('#aeps_prcss').click(function (e) {
                e.preventDefault();
                $('#aeps_prcss').addClass('ladda-button btn-ladda').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                la.ladda('start');
                $.ajax({
                    url: 'AePS/aeps_trns_prcss',
                    dataType: "json",
                    type: 'post',
                    success: function (response) {
                        if (response.error == 0) {

                            result = response;
                            form_aeps(result,la);


                        } else if (response.error == 2) {

                            window.location.reload(true);

                        } else {


                            toastr.error(data.error_desc);
                            la.ladda('stop');
                        }

                        la.ladda('stop');
                    }
                });

            })

        }


        var form_aeps = function (result,la) {
            let value = encodeURIComponent(JSON.stringify(result.msg));
            var form = document.createElement("form");
            form.setAttribute('method', "POST");
            form.setAttribute('action', result.url);
            form.setAttribute('target', "_blank");
            //form.setAttribute('input').style.display = 'none';;

            var input = document.createElement("input"); //input element, text
            input.setAttribute('type', "hidden");
            input.setAttribute('name', "params");
            input.setAttribute('value', value);

            var submitButton = document.createElement("input"); //input element, Submit button
            submitButton.setAttribute('type', "submit");
            submitButton.setAttribute('value', "Redirect");

            form.appendChild(input);
            
            //form.appendChild(submitButton);

            document.body.append(form);
            form.submit();
            form.remove();
            la.ladda('stop');
            
        }
        
        var Regex = <?php if($Regex){ echo json_encode($Regex); }else{ echo "{}"; } ?>;
        
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
                            $('#'+h.id).removeClass('is-invalid');
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
                            $('#'+h.id).addClass('is-invalid');
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
             if (p.type == "AMOUNT")
            {
                var _identifier_regex = Regex.Amount;
                var _mobile = new RegExp(_identifier_regex);
                if(_mobile.test(p.data) && parseFloat(p.data)>0)
                {

                        helpBlck({id:p.id,'action':'remove'});
                        return true;

                }
                else
                {
                    if(p.error == true)
                    {
                        helpBlck({'id':p.id, 'msg':'Please enter a valid amount.', 'type':'error'});
                    }

                }
            }
            
            return false;
        }

   
        var settlement_bal_act={
            action:false,
            type:"",
            init:function(){
                 $('.js-reload_aepsbal').click(function(e){
                        e.preventDefault();
                        $('.js-reload_aepsbal').html('<i class="fas fa-cog fa-spin"></i>');
                        settlement_bal_act.refreshbal('js-reload_aepsbal');
                    });
                
                  $("#settle_amt").on('keypress blur keyup keydown', function(e)
                                {
                                        var k = e.keyCode||e.which;
                                        var id = $(this)[0].id;
                                        var str = $(this)[0].value;
                                        var length = str.length;
                                        var regacc = new RegExp(Regex.Amount);
                                        if(length==0)
                                           {
                                               helpBlck({id:id,'action':'remove'});
                                           }

                                         if(e.type == 'blur' || e.type == 'keyup')
                                        {

                                             if(regacc.test(str) && parseFloat(str)>0)
                                            {
                                                helpBlck({id:id,'action':'remove'});
                                            }else{
                                                if(e.type == 'blur'){
                                                    if(length>0){
                                               helpBlck({'id':id, 'msg':'Please enter a valid amount.', 'type':'error'}); 
                                                    }else{
                                                         helpBlck({id:id,'action':'remove'});
                                                    }
                                                }

                                            }


                                        }else if(e.type == 'keypress')
                                            {
                                                if(k != 8 && k != 9)
                                                {
                                                    k = String.fromCharCode(k);
                                                    var sw_regex = /[0-9]/;
                                                    if(length == 0 && !sw_regex.test(k))
                                                    {
                                                        return !1					
                                                    }

                                                    if(length == 15)
                                                    {
                                                        return !1
                                                    }
                                                }

                                              return !0
                                            }
                                    });
        
                    settlement_bal_act.process_settlement();
                
            },
            refreshbal:function(action_class){
                if(settlement_bal_act.action===false && settlement_bal_act.type=="")
                    {
                        settlement_bal_act.action=true;
                        settlement_bal_act.type='Get Settlement Bal';
                        
                        $.ajax({
                               "url":"AePS/get_settlement_balances",
                               "dataType":"JSON",
                               "method":"POST"
                           }).done(function(balrespo){
                               if(balrespo.error==0)
                                   {
                                       $('#avail_aepsbal').html("&#8377;  "+balrespo.msg.availbal);
                                       $('#withdrwal_aepsbal').html("&#8377;  "+balrespo.msg.withdrawalbal);

                                       settlement_bal_act.action=false;
                                       settlement_bal_act.type="";

                                   }
                               else if(balrespo.error==2)
                                   {
                                       window.location.reload(true);
                                   }
                               else{

                                   settlement_bal_act.action=false;
                                   settlement_bal_act.type="";

                                   $('.'+action_class).html('Retry');
                                   toastr.error(balrespo.error_desc);
                               }

                           }).fail(function(err){
                               settlement_bal_act.action=false;
                               settlement_bal_act.type="";
                               $('.'+action_class).html('Retry');
                               toastr.error('Something went wrong, try again later'); 
                               
                           });
                        
                    }else{
                        toastr.error('Please Wait!!');
                    }
            },
            process_settlement:function(){
                $('#settle_sbmt').click(function(e){
                    e.preventDefault();
                    if(settlement_bal_act.action===false && settlement_bal_act.type=="")
                    {
                        var params={valid:true};
                        params.withdrwamt=$('#settle_amt').val();
                        
                        if(!validate({'id':'settle_amt','type':'AMOUNT','data':params.withdrwamt, 'error':true})) { params.valid = false;}
                        
                        if(params.valid===true)
                        {
                            settlement_bal_act.action=true;
                            settlement_bal_act.type='Process to withdraw amount';
                            var withdrwala=$(this).ladda();
                            $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            withdrwala.ladda('start');
                            
                            $.ajax({
                                "url":"AePS/withdraw_settlement_bal",
                                "data":params,
                                "dataType":"JSON",
                                "method":"POST"
                            }).done(function(withdrawrespo){
                                if(withdrawrespo)
                                    {
                                        if(withdrawrespo.error==0)
                                            {
                                                toastr.info(withdrawrespo.msg);
                                                settlement_bal_act.action=false;
                                                settlement_bal_act.type="";
                                                withdrwala.ladda('stop');
                                                $('#settle_amt').val("");
                                                settlement_bal_act.refreshbal('js-reload_aepsbal');
                                            }
                                        else if(withdrawrespo.error==2)
                                            {
                                                window.location.reload(true);
                                            }
                                        else{
                                            toastr.error(withdrawrespo.error_desc);
                                           settlement_bal_act.action=false;
                                           settlement_bal_act.type="";
                                           withdrwala.ladda('stop');
                                        }
                                    }
                            }).fail(function(err){
                               toastr.error('Something went wrong, try again later');
                               settlement_bal_act.action=false;
                               settlement_bal_act.type="";
                               withdrwala.ladda('stop');
                            });
                            
                        }
                                    
                        
                    }else{
                        toastr.error('Please Wait!!');
                    }
                })
            }
        }
        
       

        return {
            init: function () {
                gateway_form();
                settlement_bal_act.init();
            }

        }

    }();


    $(document).ready(function () {
        Transaction.init();
    })
</script>
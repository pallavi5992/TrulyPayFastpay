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
							<!--start of confirmation screen-->
								<div class="width-100" id="confirm-screen">
									
								</div>
							<!--end of confirmation screen-->

							<!--start of payment status screen-->
							<div id="response">
							
							</div>	
							<!--end of payment status screen-->

						   <div id="mobile-recharge" class="width-100 tab-pane fade active show">
						   <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold float-left">AEPS TRANSACTION</h6>
						   </div>
						    
									

									<div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">
										<a class="btn btn-primary mr-2 submit-btn white-txt" id="proceed">AEPS</a>
										
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

<script type="text/javascript">
	 $(document).ready(function(){
	// 	$(".nav-set .nav-item").click(function(){
	// 	$(".nav-set .nav-item").removeClass('active');	
	// 	$(this).addClass('active');
	// 	});

	// 	$(".active-status-btn").click(function(){
	// 		$(this).toggleClass('deactive');
	// 		$(this).toggleClass('active');
	// 		$(this).innerHtml = 'dfdf';
	// 	});

	// 	$(".confirm-btn").click(function(){

 //         $("#payment-status").show();
 //         $('#confirm-screen').hide();
	// 	});

	// 	$(".submit-btn").click(function(){
	// 	 $('#confirm-screen').show();
	// 	 $('#dth-recharge').hide();
	// 	});

	// 	$(".back-btn").click(function(){
	// 	$('#dth-recharge').show();		
	// 	$('#confirm-screen').hide();
	// 	 $("#payment-status").hide();
	// 	});

	// 	$('#from').datepicker();
	// 	$('#to').datepicker();

		// $(".show-transaction-mobilerecharge").click(function(){
		// $('#transaction-history').toggle();
		// });

 //       $("#transaction-table").DataTable({
 //       	 dom: "<'row pt-15 pb-10'<'col-sm-12 col-md-12 text-right'  l> ><'row'<'col-sm-12 table-responsive'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
 //       	"oLanguage": {

	// 		"sSearchPlaceholder": "Enter Account Number:",
	// 		"sSearch": ""
	// 	}
 //       });
	 })
</script>
</body>
</html>
<script type="text/javascript">
    var Transaction = function () {

    var gateway_form = function () {

        var param = {};
        var result ={};
        $('#aeps_prcss').click(function (e) {
            e.preventDefault();
                $('#aeps_prcss').addClass('ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                la.ladda('start');
                 $.ajax({
                url: 'AePS/aeps_trns_prcss',
                dataType: "json",
                type: 'post',
                success: function (response) {
                    console.log(response.msg.url);
                    if (response.error == 0) {
                      //execute gateway//
                       result=response.msg;
                      //openEkoGateway(result);
                    

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


    // var openEkoGateway =function(result){
    // var form = document.createElement("form");
    // form.setAttribute('method', 'post');
    // form.setAttribute('action', result.url);

    // form.setAttribute('target', 'ekogateway');
    // popup = window.open("", "ekogateway");
    
    // var customparam={
    //   developer_key: result.developer_key,
    //   secret_key: result.secret_key,
    //   secret_key_timestamp: result.secret_key_timestamp,
    //   initiator_id: result.initiator_id,
    //   user_code: result.user_code,
    //   initiator_logo_url: result.initiator_logo_url,
    //   partner_name: result.partner_name,
    //   language:result.language,
    //   callback_url: result.callback_url,
    //   callback_url_custom_params: JSON.stringify({param1:result.callback_url_custom_params.param1,param2:result.callback_url_custom_params.param2}),
    //   callback_url_custom_headers : JSON.stringify({header1: 'header1val', header2: 'header2val'})
    // };
    //     console.log('sent params '+JSON.stringify(customparam));
    // for(const prop in customparam) {

    //   if(customparam.hasOwnProperty(prop)) {
    //       if(prop!='url'){
    //   var input = document.createElement('input');
    //   input.type = 'hidden';
    //   input.name = prop;
    //   input.value = customparam[prop];
    //   form.appendChild(input);
    //       }
    //   }

    // }

    // document.body.appendChild(form);
    // form.submit();
    // window.addEventListener('message', function(e) {

    // // if(event.origin === 'https://stagegateway.eko.in'|| event.origin === 'https://gateway.eko.in') {

    // //   //e.data
    // // document.getElementById('prnt').innerHTML=(JSON.stringify(e.data));
    // // }

    // })
   
    // }

    return {
        init: function () {
            gateway_form();
        }

    }

}();


$(document).ready(function () {
    Transaction.init();
})
</script>

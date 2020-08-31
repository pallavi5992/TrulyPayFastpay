<style>
.dark-failed-bg{
    background-color: #d00c30;
}

.dark-pending-bg{
background-color: #e49c18;

}
</style>
<div class="col-lg-9">
<section class="width-100 money-remmitance-section mt-20"  id="instntpy_afterlgn">
		<div>
		<div class="container">
			<div class="row">     
				<div class="col-12">
					<div class="money-remmitance-section-outer-col width-100">
						<div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold">SUMMARY</h6><a class="btn btn-primary mr-2 submit-btn white-txt" id="user_logout_now">Sender Change</a></div> 
							
						<div class="width-100 pl-15 pr-15">
							<div class="summary-list"> 
							<div class="row">
									<div class="col-lg-3 md-mb-10">
										<div class="float-left blue-icon-box-set mr-3">
											<img src="assets/images/wallet.svg" width="30">
										</div>

										<div class="float-left">
											<div class="fontbold font16 dark-txt">Available Limit</div>
											<div class="fontbold font16 light-txt" id="available_limit">25,000</div>
										</div>
									</div>
  
									<div class="col-lg-3 md-mb-10">
									<div class="float-left blue-icon-box-set mr-3">
										<img src="assets/images/wallet.svg" width="30">
									</div>

									<div class="float-left">
										<div class="fontbold font16 dark-txt">Total Allowed Limit</div>
										<div class="fontbold font16 light-txt" id="total_allowed_limit">25,000</div>
									</div>
								  </div>


								<div class="col-lg-3 md-mb-10">
									<div class="float-left blue-icon-box-set mr-3">
										<img src="assets/images/phone-white-icon.svg" width="30">
									</div>

									<div class="float-left">
										<div class="fontbold font16 dark-txt">Mobile Number</div>
										<div class="fontbold font16 light-txt" id="mobile_no">8851245833</div>
									</div>
								 </div>



									<div class="col-lg-3 md-mb-10">
									<div class="float-left blue-icon-box-set mr-3">
										<img src="assets/images/user.png" width="25">
									</div>

									<div class="float-left">
										<div class="fontbold font16 dark-txt">Sender Name</div>
										<div class="fontbold font16 light-txt" id="sender_name">Shadab</div>
									</div>
								  </div>
							</div>	
						   </div>
						</div>

						<div class="width-100 pl-15 pr-15 mt-20">
							<div class="beneficiary-list">
							    <div class="gray-header">Beneficiary List</div>
								<div class="beneficiary-list-main">
									<div class="width-100 pt-15 pb-15">
										<div class="float-right">
										     <button class="btn btn-dark mr-2 float-left" id="add-new-benef-btn"><span class="mr-2"><img src="assets/images/add-user.svg" width="20"></span>Add New Beneficiary</button>
										
										 </div>
									</div>

									<div class="">
										<table class="table datatables table font14 font-medium light-txt data" id="benif-table">
										<thead class="thead-blue">
											
										</thead>
									</table>
									
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
	
	
	<section class="width-100 money-remmitance-section mt-20" id="instntpy_txncnfm" ></section>
		<section class="width-100 money-remmitance-section mt-20" id="instntpy_txn_cnfrmtn_screen" ></section>
	<!--end of section--->

	<!--start of modal-->
		<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="add-beneficiary">
  				<div class="modal-dialog modal-lg" id="size_modal">
	              <div class="modal-content">
	              	<div class="modal-header">
	                <h5 class="modal-title" id="head2">Add New Beneficiary</h5>
					<h5 class="modal-title" id="head22" style="display:none;></h5>
	                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                 <span aria-hidden="true">&times;</span>
	                 </button>
	                 </div>

	                 <div class="modal-body">
					  <div id="otp-beneficiary" style="display:none;"></div>
					 	<div id="add_be">
	                 	<form>
	                 		<div class="form-group row">
	                 		 <div class="col-lg-6">
			              	 <label>ACCOUNT NUMBER</label>
			               	 <input type="text" class="form-control" id="bcn" name="bcn" placeholder="Enter Account Number">	
			              	 </div>	

			              	 <div class="col-lg-6" id="bnkchoose">
			              	 <label>BANK NAME</label>
			               	<!-- <input type="text" class="form-control" name="bbnksel" id="bbnksel" placeholder="Enter Bank Name">	-->
							  <select name="bbnksel" id="bbnksel" >
									
                             </select>
			              	 </div>

							<div class="col-lg-6" id="bnkinputcol" style="display:none;">
			              	 <label>BANK NAME</label>
			               	<input type="text" class="form-control" id="bkinput" disabled>
			              	 </div>

			              	 </div>	

			              	 <div class="form-group row">
				              	 <div class="col-lg-6">
				              	 <label style="width: 100%;"><span class="float-left">IFSC</span> <span class="float-right"><a class="fontbold search-ifsc-btn" id="search_ifc" >Search IFSC</a></span></label>
				               	 <input type="text" class="form-control" id="bbnkifsc" name="bbnkifsc" placeholder="Enter IFSC">	
				              	 </div>	

				              	 <div class="col-lg-6">
				              	 <label>BENEFICIARY NAME</label>
				               	 <input type="text" class="form-control" name="benefname" id="benefname"  placeholder="Enter Beneficiary Name">	
				              	 </div>	

			              	</div>


	                 	</form>
					
	                 	<form id="search-ifsc">
	                 	<div class="gray-header mb-20">Search IFSC</div>
	                 	<div class="form-group row">
	                 		<div class="col-lg-6">
			              	 <label>Select Bank Name</label>
			               	 
			               	 <select id="select-bank-name">
			               	 <option selected="selected">Select Bank Name</option>
			               	 </select>
			              	 </div>	
							
			              	  <div class="col-lg-6"  id="statediv" style="display:none;">
			              	 <label>Select State</label>
			               	 
			               	 <select id="select-state">
			               	 
			               	 </select>
			              	 </div>	
							
							 
								

			              	</div>

			              <div class="form-group row">
							
							 <div class="col-lg-6" id="citidiv" style="display:none;">
			              	 <label>Select City</label>
			               	 <select id="select-city">
			               	 	
			               	 </select>
			              
							  </div>
							
			              	 <div class="col-lg-6" id="branchdiv" style="display:none;">
			              	 <label>Select Branch</label>
			               	 <select id="select-branch-name">
			               	 	<option>Delhi</option>
			               	 	<option>Punjab</option>
			               	 	<option>Himachal Pradesh</option>
			               	 	<option>Uttaranchal</option>
			               	 	<option>Uttar Pradesh</option>
			               	 </select>
			              	 </div>	
							
			              	</div>
							
			              	 <div class="form-group">
			              	 	<a class="btn blue-btn red-btn white-txt get-details-btn">Get Details</a>
			              	 </div>

			              	 <div class="width-100" id="ifsc-detail">
			              	 	<ul class="search-ifsc-list">
			              	 		<li><span class="font14 font-medium mr-1">Bank Name:</span> <span class="font14 font-bold" id="bn"></span></li>
			              	 		<li><span class="font14 font-medium mr-1">Branch Name:</span> <span class="font14 font-bold" id="brn"> </span></li>
			              	 		<li><span class="font14 font-medium mr-1">Address:</span> <span class="font14 font-bold" id="ad"></span></li>
			              	 		<li><span class="font14 font-medium mr-1">IFSC Code:</span> <span class="font14 font-bold" id="ifsc"></span></li>
			              	 		<li><button class="blue-btn btn" id="done">Done</button></li>
			              	 	</ul>

			              	 	
			              	 </div>	

	                 	</form>
						<div>
	                 </div>
						 </div>
	                   <div class="modal-footer">
							
		                	<button type="button" class="btn blue-btn" id="add_ab">Add Beneficiary</button>
							<button type="button"  id="resendRegisterOTP" class="btn blue-btn"style="display:none;">Resend OTP ?</button>
							<button type="button" class="btn blue-btn" id="verfy_ab" style="display:none;">Verify</button>
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							
							
			           </div>
	             
				 
  				</div>
         </div>	
	<!--end of modal-->
	
	<!---- benef OTP ------>

	
	
	<!----- benef Otp end--->

</div>
</div>

</div>
<!--end of wrapper-->


<script type="text/javascript">
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

	    $('#select-state').select2({
	    theme: 'bootstrap4',
	    });

       $('#select-branch-name').select2({
	    theme: 'bootstrap4',
	    }); 

     

      

      /*  $("#benif-table").DataTable({
       	 dom: "<'row pt-15 pb-10'<'col-sm-12 col-md-12' <'btn-wrp float-right ml-2'> f> ><'row'<'col-sm-12 table-responsive'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
       	"oLanguage": {

			"sSearchPlaceholder": "Enter Account Number:",
			"sSearch": ""
		}
       }); */

        var r= $('<button class="btn btn-dark mr-2" id="add-new-benef-btn"><span class="mr-2"><img src="images/add-user.svg" width="20"></span>Add New Beneficiary</button>');
        $(".btn-wrp").append(r);

        $("#add-new-benef-btn").click(function(){
		$("#add-beneficiary").modal('show');
		});
  

/* 		 function format (data) {
      return '<div class="details-container">'+
          '<table cellpadding="5" cellspacing="0" border="0" class="table details-table">'+
              '<tr>'+
                  '<td class="title">Person ID:</td>'+
                  '<td>'+data.id+'</td>'+
              '</tr>'+
              '<tr>'+
                  '<td class="title">Name:</td>'+
                  '<td>'+data.first_name + ' ' + data.last_name +'</td>'+
                  '<td class="title">Email:</td>'+
                  '<td>'+data.email+'</td>'+
              '</tr>'+
              '<tr>'+
                  '<td class="title">Country:</td>'+
                  '<td>'+data.country+'</td>'+
                  '<td class="title">IP Address:</td>'+
                  '<td>'+data.ip_address+'</td>'+
              '</tr>'+
          '</table>'+
        '</div>';
  	  }; */


	     
    
				// Add event listener for opening and closing details
		/* 	 var table =  $('.datatables').DataTable({
			 	"columns": [
			 		 {
	                "class": "details-control",
	                "orderable": false,
	                "data": null,
	                "defaultContent": ""
            		},

			 		{ "data": "first_name"},
			 		{ "data": "last_name"},
			 		{ "data": "email"}
			 	],
			 	//"data": data
			 });  */

			/*  $('.datatables tbody').on('click', 'td.details-control', function () {
		     var tr  = $(this).closest('tr'),
		         row = table.row(tr);
		    
		     if (row.child.isShown()) {
		       tr.next('tr').removeClass('details-row');
		       row.child.hide();
		       tr.removeClass('details');
		     }
		     else {
		       row.child(format(row.data())).show();
		       tr.next('tr').addClass('details-row');
		       tr.addClass('details');
		     }
		  }); */


	});
</script>


<script type="text/javascript">
    var InstanstPayAfterLogin = function () {
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
		  var benefotpreq = {};
		  
		  

 /*     $(".get-details-btn").click(function(){
       	$("#ifsc-detail").show();
       });  */
		  var ValidateAll = function (v, act) {
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
                        window.location.reload('true');
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
				
				//var bnk = (bankData.bank == v.bank_name) ? 'selected' : '';
				/* $("#bbnksel").html('<option value="' + v.bank_name + '" ' + bankData.bank + '>' + v.bank_name + '</option>'); */
			    //str += '<option value="' + v.bank_name + '" ' + bnk + '>' + v.bank_name + '</option>';
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
                if (/^[A-Za-z0-9]+$/.test(str)) {
                    $.ajax({
                        method: 'POST',
                        url: 'MoneyTransfer/InstBankByIfsc',
                        dataType: 'JSON',
                        data: {ifsc: str}
                    }).done(function (response) {
                        if (response) {
						console.log(response)
                            if (response.error == 0) {
                                if (retry != true) {
                           
									bankData.bank = response.msg.bank_name;
                                    $('#bkinput').val(response.msg.bank_name).prop('disabled', true).addClass('edited');
                                    $('#bnkchoose').hide('');
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
                    $('#bnkchoose').show('');
                    $('#bnkinputcol').hide('');
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
            init_ifsc: function () {
          
		    $(".search-ifsc-btn").click(function(){
			$("#search-ifsc").toggle();
			$('#statediv,#citidiv,#branchdiv').hide();
			$("#select-branch").html('');    
			$('#select-city').html('');
			$('#select-state').html('');

                                    $('#ifsc-detail').hide();

									$('#bn').html('');
                                    $('#brn').html('');
                                    $('#ad').html('');
                                    $('#ifsc').html('');
			  GetBankName()
		   })
                var GetBankName = function () {
                    $.ajax({
                        method: 'POST',
                        url: 'MoneyTransfer/BankNameSelect',
                        dataType: 'JSON'
                    }).done(function (response) {
                        if (response) {
                          /*   if (response.error == 1) {
                                toastr.error(response.error_desc);
                            } else  */
								if (response.error == 2) {
                                window.location.reload(true);
                            } else if (response.error == 0) {
								
							
								 $("#select-bank-name").html('<option selected="selected" value="">Select Bank Name</option>');
                                $.each(response.msg, function (index, value) {
                                    $("#select-bank-name").append('<option value="' + value.bank_name + '">' + value.bank_name + '</option>');
                                });
                              
                                OnChangeBank();
                            }
                        }
                    }).fail(function (err) {
                        throw err;
                    });
                }

                var OnChangeBank = function () {
                    $("#select-bank-name").change(function () {
                     
						 $('#statediv,#citidiv,#branchdiv,#ifscdiv').hide();
						   $("#select-branch").html('');    
							$('#select-city').html('');
							$('#select-state').html('');
                        var get_bankname = $(this).val();
                        if (get_bankname != undefined && get_bankname != null && get_bankname != '') {
                            $.ajax({
                                method: 'POST',
                                url: 'MoneyTransfer/SelectState',
                                dataType: 'JSON',
                                data: {bank_name: get_bankname}
                            }).done(function (response) {
                                if (response) {
                                   /*  if (response.error == 1) {
                                        toastr.error(response.error_desc);
                                    } else */
										if (response.error == 2) {
                                        window.location.reload(true);
                                    } else if (response.error == 0) {
                                        $('#statediv').show();
                                    
								 $("#select-state").html('<option selected="selected" value="">Select Bank State</option>');
                                        $.each(response.msg, function (index, value) {
                                            $("#select-state").append('<option value="' + value.state + '">' + value.state + '</option>');
                                        });
                                        OnStateChange(get_bankname);
                                    }
                                }
                            }).fail(function (err) {  
                                throw err;
                            });
                        }else{
							$('#statediv,#citidiv,#branchdiv').hide();
							$("#select-city").html('');
							
							   $('#select-city').html('');
							    $('#select-state').html('');


								
                                    $('#ifsc-detail').hide();

									$('#bn').html('');
                                    $('#brn').html('');
                                    $('#ad').html('');
                                    $('#ifsc').html('');
							
						}
                    });
                }

                var OnStateChange = function (get_bankname) {
                    $("#select-state").change(function () {
                       
                        $('#citidiv,#branchdiv,#ifscdiv').hide();
						   $("#select-branch").html('');    
							$('#select-city').html('');
							
                        var get_state = $(this).val();
						 if (get_state != undefined && get_state != null && get_state != '') {
                        $.ajax({
                            method: 'POST',
                            url: 'MoneyTransfer/SelectCity',
                            dataType: 'JSON',
                            data: {state: get_state, bank_name: get_bankname}
                        }).done(function (response) {
                            if (response) {
                                /* if (response.error == 1) {
                                    toastr.error(response.error_desc);
                                } else  */
									if (response.error == 2) {
                                    window.location.reload(true);
                                } else if (response.error == 0) {
									    $('#citidiv').show();
                                  
								    $("#select-city").html('<option selected="selected" value="">Select Bank City</option>');
                                    $.each(response.msg, function (index, value) {
                                        $("#select-city").append('<option value="' + value.city + '">' + value.city + '</option>');
                                    });
                                    OnCityChange(get_bankname, get_state);
                                }
                            }
                        }).fail(function (err) {
                            throw err;
                        });
						
					}else{
							$('#statediv,#citidiv,#branchdiv').hide();
							$("#select-city").html('');
							$("#select-branch").html('');
							$('#select-state').html('');	

							
                                    $('#ifsc-detail').hide();

									$('#bn').html('');
                                    $('#brn').html('');
                                    $('#ad').html('');
                                    $('#ifsc').html('');
							
							 
					}
                    });
                }

                var OnCityChange = function (get_bankname, get_state) {
                    $("#select-city").change(function () {
                        $('#branchdiv,#ifscdiv').hide();
						   
                        var get_city = $(this).val();
						 if (get_city != undefined && get_city != null && get_city != '') {
                        $.ajax({
                            method: 'POST',
                            url: 'MoneyTransfer/SelectBranch',
                            dataType: 'JSON',
                            data: {state: get_state, bank_name: get_bankname, city: get_city}
                        }).done(function (response) {
                            if (response) {
								 $("#select-branch-name").html('<option selected="selected" value="">Select Bank Branch</option>');
                               /*  if (response.error == 1) {
                                    toastr.error(response.error_desc);
                                } else  */

								if (response.error == 2) {
                                    window.location.reload(true);
                                } else if (response.error == 0) {
									   $('#branchdiv').show();
                                
                                   
								    
                                    $.each(response.msg, function (index, value) {
                                        $("#select-branch-name").append('<option value="' + value.branch + '">' + value.branch + '</option>');
                                    });
                                    OnBranchChange(get_bankname, get_state, get_city);
                                }
                            }
                        }).fail(function (err) {
                            throw err;
                        });
						
					}else{
						
						    $('#branchdiv').hide();
							$("#select-city").html('');
							
                                    $('#ifsc-detail').hide();

									$('#bn').html('');
                                    $('#brn').html('');
                                    $('#ad').html('');
                                    $('#ifsc').html('');
						
					}
                    });
                }

                var OnBranchChange = function (get_bankname, get_state, get_city) {
                    $("#select-branch-name").change(function () {
                     
                        var get_branch = $(this).val();
						if (get_branch != undefined && get_branch != null && get_branch != '') {
                        $.ajax({
                            method: 'POST',
                            url: 'MoneyTransfer/GetIFSC',
                            dataType: 'JSON',
                            data: {state: get_state, bank_name: get_bankname, city: get_city, branch: get_branch}
                        }).done(function (response) {
                            if (response) {
                                /* if (response.error == 1) {
                                    toastr.error(response.error_desc);
                                } else  */

								if (response.error == 2) {
                                    window.location.reload(true);
                                } else if (response.error == 0) {

                                    bankData.bank = response.msg.bank_name;
                                    bankData.branch = response.msg.branch;
                                    bankData.state = get_state;
                                    bankData.city = get_city;
                               
								 $('.get-details-btn').click(function () {
									$('#ifsc-detail').show();
                                    $('#bn').html(response.msg.bank_name);
                                    $('#brn').html(response.msg.branch);
                                    $('#ad').html(response.msg.address);
                                    $('#ifsc').html(response.msg.ifsc);
                                   
                              
									
								});
                                   
                                    
                                    $('#ifscpaste').addClass('edited');
                                    $('#done').click(function (e) {
										e.preventDefault();

								    $('#bbnkifsc').val(response.msg.ifsc);
									

									$('#bkinput').val(response.msg.bank_name).prop('disabled', true).addClass('edited');
                                    $('#bnkchoose').hide('');
                                    $('#bnkinputcol').show('');
									/* $.each(banklist_with_ifsc, function (k4, v4) {
										var sel = (v4.bank_name == bankData.bank) ? 'selected' : '';
										 htmlbnk = '<option value="' + v4.bank_name + '" ' + sel + '>' + v4.bank_name + '</option>';
									}); */

									/* $('#bbnksel').html(htmlbnk); */

                                         $('#search-ifsc').hide();

                                        $('#ifsc-detail').hide();

									$('#bn').html('');
                                    $('#brn').html('');
                                    $('#ad').html('');
                                    $('#ifsc').html('');
									   $('#statediv,#citidiv,#branchdiv,#ifscdiv').hide();
									
									
                                       
                                    });
                                  
                                }
                            }
                        }).fail(function (err) {
                            throw err;
                        });
				}else{
									

                                    $('#ifsc-detail').hide();

									$('#bn').html('');
                                    $('#brn').html('');
                                    $('#ad').html('');
                                    $('#ifsc').html('');
					
				}
                    });
                }

                GetBankName();
            }
        }
		  $("#add_ab").click(function (e) {
            e.preventDefault();
            if (wait === false) {
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
              
                var status2 = true;
                if (param.ifsccode.length != 11 || !ValidateAll(param.ifsccode, 'ifsc')) {
                    $("#bbnkifsc").focus();
                    toastr.error('Please Enter IFSC');
                    status2 = false;
                    return false;
                } else if (param.accountno == '') {
                    $("#bcn").focus();
                    toastr.error('Please enter Account No');
                    status2 = false;
                    return false;
                } else if (!param.name || param.name == null || param.name.length < 2 || param.name == "") {

                    toastr.error('Please enter a valid name, minimum length 2');

                    $('#benefname').focus();
                    status2 = false;
                    return false;
                } 
				else {
                    status2 = true;
                }
                if (status2) {
                    la.ladda('start');
                    wait = true;
                 
                        $.post('MoneyTransfer/BenefRegistration', {
                            data: param
                        }, function (response) {
                            if (response) {
								
                                if (response.error == 0) {
                                  
                                        
                                      
									    request.benecode = response.bnef_id;
                                        param.beneid = request.benecode;

                                

                                        toastr.success('Beneficiary Added Successfully');
                                        la.ladda('stop');

                                      /*   $('#beneficiary_choice').val('benefilist').trigger('onchange'); */ 
										 window.location.reload(true);
                                      

                                   
                                }else if (response.error == 4) {
									 toastr.success(response.msg);
									 $("#size_modal").attr('class', 'modal-dialog modal-md');
									
									$("#head2").hide();
									$('#head22').show().html('Beneficiary Registration Validation');
									$('#add_be').hide();
									$('#add_ab').hide();
									var str = '<form>';
									str+= '<div class="form-group row">';
									str+= '<label>OTP</label>';
									str+= '<input type="tel" class="form-control" id="benefOTP" name="benefOTP" placeholder="Enter OTP">';	
									str+= '</div>';
									str+= '</form>';
	                      
									  $('#otp-beneficiary').html(str).show();
									  $('#verfy_ab').show();
									   $('#resendRegisterOTP').show();
									  
									  
									  benefotpreq={};
									   benefotpreq.remitterid = response.remitterid;
										benefotpreq.bnef_id = response.bnef_id;
									validate_Bnef_otp(benefotpreq)
									
								} else if (response.error == 2) {
                                    window.location.reload(true);
                                } else {

                                    toastr.error(response.error_desc);
                                    la.ladda('stop');
                                }
                                wait = false;
                            }
                        }, 'json').fail(function (error) {
                            la.ladda('stop');
                            throw error;
                        });
                  
                }
            }
        });
		
		var validate_Bnef_otp= function(req){
			
		$("#verfy_ab").click(function(e){
		e.preventDefault();
   
		if(req && req!= "" && req!=null && !$.isEmptyObject(req))
		{
          
           req.otp = $("#benefOTP").val(); 
		   console.log(req.otp); 
            if(req.otp=""){
           
			$("#benefOTP").focus();	

				 toastr.error('Invalid OTP 5465');
	
        return false;
				
		}else{
            
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
                        
                            /* clogin.mobile=req.mobile;
                            req={};
                            login(la);
                            */
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
	});
	
	
	
	$("#resendRegisterOTP").click(function(e){
		
		e.preventDefault();
  
		if(req && req!= "" && req!=null && !$.isEmptyObject(req))
		{
          req.otp =''; 
        
		//}else{
            
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
        
	});
	}

		/*********************************** end add benef*************************************************/
		var benef_list = function (){
			var table = $('.datatables').DataTable({
                "processing": true,
                "ajax": {
                    url: "MoneyTransfer/InstantPyBenefFetchDetails",
                    type: 'post',
                    "dataSrc": function (json) {
                    console.log(json)
                        if (json.error == 2)
                        {
                            window.location.reload(true);
                        } else if (json.error == 1)
                        {
                            toastr.error(json.error_desc, 'Oops!');
                        }
                       // return json[response][data][beneficiary];
						return json.response.data.beneficiary;
                    }
                },
					responsive: true,
                order: [],
                columns: [
        

            		 {
			 		 "title" : "Beneficiary Name",
	                
	                 "data": "name",
            		},

            		 {
			 		 "title" : "Bank",
	                
	                 	 "data": "bank",
                    

            		},

            		 {
			 		 "title" : "Account No",
	               
	                 "data": "account",
            		},
            		 {
			 		 "title" : "IFSC",
	               
	                 "data": "ifsc",
            		},
            		 {
			 		 "title" : "Amount",
	               
	                 "render": function ( data, type, full, meta ) {
					      return '<input type="text" name="transamount" id="TrnsAmnt-' + full.id + '" class="form-control" style="width:100px;">';
					    }
            		},
					
          

            		 {
			 		 "title" : "Mode",data: "id",
	                 "orderable": false,
	                 // "data": "status",
	                  "render": function ( data, type, full, meta ) {
					      return '<button class="btn btn-default float-left mr-1 fontbold strtremit" id="imps_tnsfr" data-impstransfer="' + full.id + '">IMPS</button><button class="btn btn-default float-left btn-default mr-1 fontbold strtremit"  id="neft_tnsfr" data-nefttransfer="' + full.id + '">NEFT</button><button class="btn float-left red-btn mr-1 "  data-delt_benef="' + full.id + '" id="Delete-Benf" ><img src="assets/images/trash-icon.svg" width="15">';
					    }
            		},
 
                   
                ],
				
				
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
                   var param = {'beneid': showtd1.id, 'remitid': remitid};   
                
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
					
				 var id = $(this)[0].id;
                    $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                    var la = $(this).ladda();
					if(id=='imps_tnsfr'){
						
					var benfid = $(this).data('impstransfer');
					var row = $(this).closest('tr');
					var showtd1 = table.row(row).data();	
					if (showtd1['id'] == benfid){
						console.log('ghvhbvh')
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
				
					if (TXN_REQUEST.mode != "IMPS" ) {
                    
							
                        toastr.error('Invalid mode');

                       /*  $('#transmode').focus(); */
                        status2 = false;
                        return false;
                   
				
				 }else if (!TXN_REQUEST.transamount || TXN_REQUEST.transamount == null || TXN_REQUEST.transamount == "" || isNaN(TXN_REQUEST.transamount) || TXN_REQUEST.transamount < 1 || !ValidateAll(TXN_REQUEST.transamount, 'amount') || TXN_REQUEST.transamount >= 25000) {
                  
                        toastr.error('Please enter valid amount');

                        $('#transamount').focus();
                        status2 = false;
                        return false;
                  
                } else {
                    status2 = true;
                }
		
										if (status2) {
											la.ladda('start');
												$.ajax({
                                                    url: "MoneyTransfer/ChrgCommOnTransaction",
                                                    dataType: "JSON",
                                                    method: "POST",
                                                    data: {data: TXN_REQUEST},
                                                    // processData: false,
                                                    success: function (response) {
                                                        if (response) {
															  if (response.error == 0) {
																  TXN_CONFRMTN(la,response)
                                                            } else if (response.error == 2) {
                                                                window.location.reload(true);
                                                            } else if (response.error == 8) {
																  LOOPTXN_CONFRMTN(la,response)
															}else {
																toastr.error(response.error_desc)
																la.ladda('stop');  
															}
															la.ladda('stop');
                                                        }
                                                    }
                                                });	
					
						
				}
                 
                    }
					}else {
						
					var benfid = $(this).data('nefttransfer');
					console.log(benfid)
					var row = $(this).closest('tr');
					var showtd1 = table.row(row).data();
					if (showtd1['id'] == benfid){
						console.log('ghvhbvh')
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
				
					if (TXN_REQUEST.mode != "NEFT" ) {
                    
						
                        toastr.error('Invalid mode');

                       /*  $('#transmode').focus(); */
                        status2 = false;
                        return false;
                   
					 }else if (!TXN_REQUEST.transamount || TXN_REQUEST.transamount == null || TXN_REQUEST.transamount == "" || isNaN(TXN_REQUEST.transamount) || TXN_REQUEST.transamount < 1 || !ValidateAll(TXN_REQUEST.transamount, 'amount') || TXN_REQUEST.transamount >= 25000) {
                  
                        toastr.error('Please enter valid amount');

                        $('#transamount').focus();
                        status2 = false;
                        return false;
                  
                } else {
                    status2 = true;
                }
		
										if (status2) {
											la.ladda('start');
												$.ajax({
                                                    url: "MoneyTransfer/ChrgCommOnTransaction",
                                                    dataType: "JSON",
                                                    method: "POST",
                                                    data: {data: TXN_REQUEST},
                                                  
                                                    success: function (response) {
                                                        if (response) {
															console.log('0809809')
															console.log(response)
															  if (response.error == 0) {
																  TXN_CONFRMTN(la,response)
                                                            } else if (response.error == 2) {
                                                                window.location.reload(true);
                                                            } else if (response.error == 8) {
																  LOOPTXN_CONFRMTN(la,response)
															}else {
																toastr.error(response.error_desc)
																la.ladda('stop');  
															}
                                                        }
														la.ladda('stop');
                                                    }
                                                });	
					
						
				}
                 
                    }
					}
                  
					
					
                });
			
			var LOOPTXN_CONFRMTN  = function (la,response) {
		
			if (!TXN_REQUEST || TXN_REQUEST == "" || TXN_REQUEST == null) {

                toastr.error("Invalid Request");
                return false;
				  
            } else {   
			 $("#instntpy_afterlgn").hide(); 
					var htl='<div class="width-100 transaction-confirm-section mt-20" id="transaction-confirmation-screen">';     
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
																htl+='<div class="white-icon-container"><img src="images/wallet-blue-icon.svg" width="25"></div>';
																htl+='<div class="float-left">';
																	htl+='<div class="font16 white-txt">YOUR AVAILABLE BALANCE</div>';
																	htl+='<div class="font16 fontbold white-txt" id="avlbl_blcc">27547.37</div>';
																htl+='</div>';
															htl+='</div>';
														htl+='</div>';

														htl+='<div class="col-lg-4">';
															htl+='<div class="blue-panel">';
																htl+='<div class="white-icon-container"><img src="images/rupee-blue-icon.svg" width="30"></div>';
																htl+='<div class="float-left">';
																	htl+='<div class="font16 white-txt">TRANSFER AMOUNT</div>';
																	htl+='<div class="font16 fontbold white-txt" id="tnsfr_blcc">'+TXN_REQUEST.transamount+'</div>';
																htl+='</div>';
															htl+='</div>';
														htl+='</div>';

														htl+='<div class="col-lg-4">';
															htl+='<div class="blue-panel">';
																htl+='<div class="white-icon-container"><img src="images/wallet-blue-icon.svg" width="25"></div>';
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

															htl+='<div class="width-100 no-of-transaction">';

																htl+='<div class="width-100 blue-header white-txt mb-10">Number of Transaction</div>';
																htl+='<table class="table table-bordered font14">';
																	htl+='<thead>';
																		htl+='<tr>';
																			htl+='<th>S#</th>';
																			htl+='<th>Amount</th>';
																			htl+='<th>Charge</th>';
																			htl+='<th>Commission</th>';
																		htl+='</tr>';
																	htl+='</thead>';

																	htl+='<tbody>';
																	var i=1;
																	   $.each(response.result, function (k, v) {
																		htl+='<tr>';
																			htl+='<td>'+i+'</td>';
																			htl+='<td>'+v.trnsferamount+'</td>';
																			htl+='<td>'+v.extra_chrg+'</td>';
																			htl+='<td>'+v.base_applicable_commisison+'</td>';
																		htl+='</tr>';
																		i++;
																	   });
																	htl+='</tbody>';
																htl+='</table>';
															htl+='</div>';
														htl+='</div>';

														htl+='<div class="col-lg-6">';
															htl+='<form class="form-container pl-0 pr-0">';
																htl+='<div class="width-100 blue-header white-txt mb-10">ENTER MPIN</div>';
																	htl+='<div class="form-group pl-10 pr-10">';
																		htl+='<input type="text" class="form-control" name="MPIN" id="MPIN" placeholder="MPIN">';
																	htl+='</div>';

																	htl+='<div class="form-group pl-10 pr-10">';
																		htl+='<input type="text" class="form-control" name="REMARK" id="REMARK" placeholder="REMARK">';
																	htl+='</div>';

																	htl+='<div class="form-group pl-10 pr-10">';
																		htl+='<a class="btn green-btn white-txt confirm-btn" id="procss_txn">Confirm</a>';
																		htl+='<button class="btn red-btn white-txt" id="cancel_txn">Cancel</button>';
																	htl+='</div>';
															htl+='</form>';
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
			 $("#instntpy_afterlgn").show();  
			 $('#TrnsAmnt-' + remitid + '').val('')	
		 	table.ajax.reload( null, false ); 		 
			$("#instntpy_txncnfm").html('');
			 status = true;
				
			})	
		  $('#procss_txn').click(function(e){
			  e.preventDefault();
                  $(this).addClass('ladda-button').attr({
                    'data-style': 'zoom-in',
                    'data-spinner-color': "#333"
                });
                var la1 = $(this).ladda();
			   var CHCKMPINREQ = {'valid': true};
			   CHCKMPINREQ.MPIN=$('#MPIN').val();
			   CHCKMPINREQ.REMARK=$('#REMARK').val();
			  TRANSPRCSS(CHCKMPINREQ,la1)
			});   
			}     
			
			}
			
			var TXN_CONFRMTN  = function (la,response) {
		
			if (!TXN_REQUEST || TXN_REQUEST == "" || TXN_REQUEST == null) {

                toastr.error("Invalid Request");
                return false;
				  
            } else {   
			     
				$("#instntpy_afterlgn").hide(); 
				
					var htl='<div class="width-100 transaction-confirm-section mt-20" id="transaction-confirmation-screen">';     
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
																htl+='<div class="white-icon-container"><img src="images/wallet-blue-icon.svg" width="25"></div>';
																htl+='<div class="float-left">';
																	htl+='<div class="font16 white-txt">YOUR AVAILABLE BALANCE</div>';
																	htl+='<div class="font16 fontbold white-txt" id="avlbl_blcc">27547.37</div>';
																htl+='</div>';
															htl+='</div>';
														htl+='</div>';
															
														htl+='<div class="col-lg-4">';
															htl+='<div class="blue-panel">';
																htl+='<div class="white-icon-container"><img src="images/rupee-blue-icon.svg" width="30"></div>';
																htl+='<div class="float-left">';
																	htl+='<div class="font16 white-txt">TRANSFER AMOUNT</div>';
																	htl+='<div class="font16 fontbold white-txt" id="tnsfr_blcc">'+response.trnsferamount+'</div>';
																htl+='</div>';
															htl+='</div>';
														htl+='</div>';

														htl+='<div class="col-lg-4">';
															htl+='<div class="blue-panel">';
																htl+='<div class="white-icon-container"><img src="images/wallet-blue-icon.svg" width="25"></div>';
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
											

															htl+='<div class="width-100 no-of-transaction">';

																htl+='<div class="width-100 blue-header white-txt mb-10">Number of Transaction</div>';
																htl+='<table class="table table-bordered font14">';
																	htl+='<thead>';
																		htl+='<tr>';
																			htl+='<th>S#</th>';
																			htl+='<th>Amount</th>';
																			htl+='<th>Charge</th>';
																			htl+='<th>Commission</th>';
																		htl+='</tr>';
																	htl+='</thead>';

																	htl+='<tbody>';
																		htl+='<tr>';
																			htl+='<td>1</td>';
																			htl+='<td>'+response.trnsferamount+'</td>';
																			htl+='<td>'+response.extra_chrg+'</td>';
																			htl+='<td>'+response.base_applicable_commisison+'</td>';
																		htl+='</tr>';
																	htl+='</tbody>';
																htl+='</table>';
															htl+='</div>';
														htl+='</div>';

														htl+='<div class="col-lg-6">';
															htl+='<form class="form-container pl-0 pr-0">';
																htl+='<div class="width-100 blue-header white-txt mb-10">ENTER MPIN</div>';
																	htl+='<div class="form-group pl-10 pr-10">';
																		htl+='<input type="text" class="form-control" name="MPIN" id="MPIN" placeholder="MPIN">';
																	htl+='</div>';

																	htl+='<div class="form-group pl-10 pr-10">';
																		htl+='<input type="text" class="form-control" name="REMARK" id="REMARK" placeholder="REMARK">';
																	htl+='</div>';

																	htl+='<div class="form-group pl-10 pr-10">';
																		htl+='<a class="btn green-btn white-txt confirm-btn" id="procss_txn">Confirm</a>';
																		htl+='<button class="btn red-btn white-txt" id="cancel_txn">Cancel</button>';
																	htl+='</div>';
															htl+='</form>';
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
			 $("#instntpy_afterlgn").show();  
			 $('#TrnsAmnt-' + remitid + '').val('')	
		 	table.ajax.reload( null, false ); 		 
			$("#instntpy_txncnfm").html('');
			 status = true;
				
			})	
		    $('#procss_txn').click(function(e){
			  e.preventDefault();
			   e.preventDefault();
                  $(this).addClass('ladda-button').attr({
                    'data-style': 'zoom-in',
                    'data-spinner-color': "#333"
                });
                var la1 = $(this).ladda();
			   var CHCKMPINREQ = {'valid': true};
			   CHCKMPINREQ.MPIN=$('#MPIN').val();
			   CHCKMPINREQ.REMARK=$('#REMARK').val();
			  TRANSPRCSS(CHCKMPINREQ,la1,response)
			});
			}     
			
			}
			
			
			var TRANSPRCSS= function (CHCKMPINREQ, la1,response) {
			if (!CHCKMPINREQ || CHCKMPINREQ == "" || CHCKMPINREQ == null) {

                toastr.error("Invalid Request");
                return false;
				  
            } else {  
			var status = true;
			
                //if (CHCKMPINREQ.MPIN.length >= 4 || !ValidateAll(CHCKMPINREQ.MPIN, 'pin')) {
					
				if (CHCKMPINREQ.MPIN == '') {
                    $("#MPIN").focus();
                    toastr.error('Please Enter Valid MPIN');
                    status = false;
                    return false;
                } else if (CHCKMPINREQ.REMARK == '') {
                    $("#REMARK").focus();
                    toastr.error('Please enter Remarks');
                    status = false;
                    return false;
                } 
				else {
                    status = true;
                }
                if (status) {
                    la1.ladda('start');
						CHCKMPINREQ.extra_chrg=response.extra_chrg;
						CHCKMPINREQ.base_applicable_commisison=response.base_applicable_commisison;
                 
                        $.post('MoneyTransfer/CheckTxnMPIN', {
							data: {CHCKMPINREQ}
                        }, function (response) {
                            if (response) {
								console.log(response)
						if (response.error == 0) {
							 $("#instntpy_txncnfm").html(''); 
									$.ajax({
										method: 'POST',
										url: 'MoneyTransfer/AmountTransfer',
										dataType: 'JSON',
										data: {CHCKMPINREQ:CHCKMPINREQ,TXN_REQUEST:TXN_REQUEST}
									}).done(function (response) {
										if (response) {
											console.log(response)
											var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
											var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

											function inWords (num) {
												if ((num = num.toString()).length > 9) return 'overflow';
												n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
												if (!n) return; var str = '';
												str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
												str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
												str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
												str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
												str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
												return str;
											}
											var totalamount=TXN_REQUEST.transamount;
										
											var words = inWords(totalamount);
											const capitalize = (s) => {
												  if (typeof s !== 'string') return ''
												  return s.charAt(0).toUpperCase() + s.slice(1)
												}
										      words = capitalize(words);
											if (response.error == 0) {
												        
												response.TxnId = response.TxnId ? response.TxnId : '00';
												response.OPTId = response.OPTId ? response.OPTId : '00';
												var htm='<section class="width-100 transaction-status-section mt-20">';
												htm+='<div class="container">';
													htm+='<div class="row">';
														htm+='<div class="col-lg-8 mx-auto">';
															htm+='<div class="width-100 transaction-status-col">';
												htm+='<div class="width-100 transaction-status-header dark-success-bg"><p class="mb-0 white-txt font18"><span class="transaction-status-icon float-left mr-2"><img src="images/check.svg" width="20"></span>';
																htm+='<span class="float-left" style="margin-top: 5px;">TRANSACTION SUCCESSFULL</span></p></div>';
																htm+='<div class="width-100 transaction-status-inner pl-15 pr-15">';
																		htm+='<div class="width-100">';
																			htm+='<div class="transaction-list-details">';
																				htm+='<div class="transaction-list-details-col bord-right-set">';
																					htm+='<ul>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Outlet Name:</span> <span class="font14 font-medium black-txt float-right">PK MOBILE</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Name:</span> <span class="font14 font-medium black-txt float-right">'+reminame+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Beneficiary:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.name+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Bank Name:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';

																				htm+='<div class="transaction-list-details-col">';
																					htm+='<ul>';
																						
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Mobile:</span> <span class="font14 font-medium black-txt float-right">'+reminumber+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Account No:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Date & Time:</span> <span class="font14 font-medium black-txt float-right">'+response.date+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';
																			htm+='</div>';
																		htm+='</div>';

																		htm+='<div class="width-100 mt-20">';
																			htm+='<div class="gray-header mb-20">Transaction Summary</div>';	

																			htm+='<div class="table-responsive">';
																				htm+='<table class="table font14 dark-txt">';
																					htm+='<thead>';
																						htm+='<tr>';
																							htm+='<th>FastPay ID</th>';
																							htm+='<th>Operator Ref</th>';
																							htm+='<th>Amount</th>';
																							htm+='<th>Status</th>';
																							
																						htm+='</tr>';
																					htm+='</thead>';

																					htm+='<tbody>';
																						htm+='<tr>';
																							htm+='<td>'+response.TxnId+'</td>';
																							htm+='<td>'+response.OPTId+'</td>';
																							htm+='<td>'+TXN_REQUEST.transamount+'</td>';
																							htm+='<td class="fontbold">'+response.status+'</td>';
																						htm+='</tr>';
																					htm+='</tbody>';
																				htm+='</table>';
																			htm+='</div>';

																			htm+='<div class="width-100">';
																			htm+='<div class="float-left dashed-border"><span class="fontbold font14 light-txt">Total Amount: </span><span class="fontbold font14 dark-txt">Rs '+TXN_REQUEST.transamount+'</span></div>';
																			htm+='<div class="float-right dashed-border"><span class="fontbold font14 light-txt">Amount (In Words): </span><span class="fontbold font14 dark-txt">'+words + '</span></div>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center fontbold font12 mt-20 mb-20">';
																				htm+='<p class="mb-0">@2019 All Rights Reserved</p>';
																				htm+='<p class="mb-0">This is a System generated Receipt. Hence no seal or signature required.</p>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center">';
																				htm+='<a class="btn btn-dark back-btn white-txt" id="antr_py"><img src="images/left-arrow-white.svg" width="20" class="mr-2">Pay Again </a>';
																				htm+='<a class="blue-btn btn white-txt"><img src="images/white-printer-icon.svg" width="20" class="mr-2">Print</a>';
																			htm+='</div>';



																		htm+='</div>';
																htm+='</div>';
															htm+='</div>';
														htm+='</div>';
													htm+='</div>';
												htm+='</div>';
											htm+='</section>';
											
											$("#instntpy_txn_cnfrmtn_screen").html(htm); 
	
											
											} else if (response.error == 1) {
												
													  response.TxnId = response.TxnId ? response.TxnId : '00';
													    response.OPTId = response.OPTId ? response.OPTId : '00';
												var htm='<section class="width-100 transaction-status-section mt-20">';
												htm+='<div class="container">';
													htm+='<div class="row">';
														htm+='<div class="col-lg-8 mx-auto">';
															htm+='<div class="width-100 transaction-status-col">';
												htm+='<div class="width-100 transaction-status-header dark-failed-bg"><p class="mb-0 white-txt font18"><span class="transaction-status-icon float-left mr-2"><img src="images/check.svg" width="20"></span>';
																htm+='<span class="float-left" style="margin-top: 5px;">TRANSACTION FAILED('+response.error_desc+')</span></p></div>';
																htm+='<div class="width-100 transaction-status-inner pl-15 pr-15">';
																		htm+='<div class="width-100">';
																			htm+='<div class="transaction-list-details">';
																				htm+='<div class="transaction-list-details-col bord-right-set">';
																					htm+='<ul>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Outlet Name:</span> <span class="font14 font-medium black-txt float-right">PK MOBILE</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Name:</span> <span class="font14 font-medium black-txt float-right">'+reminame+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Beneficiary:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.name+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Bank Name:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';

																				htm+='<div class="transaction-list-details-col">';
																					htm+='<ul>';
																						
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Mobile:</span> <span class="font14 font-medium black-txt float-right">'+reminumber+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Account No:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Date & Time:</span> <span class="font14 font-medium black-txt float-right">'+response.date+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';
																			htm+='</div>';
																		htm+='</div>';

																		htm+='<div class="width-100 mt-20">';
																			htm+='<div class="gray-header mb-20">Transaction Summary</div>';	

																			htm+='<div class="table-responsive">';
																				htm+='<table class="table font14 dark-txt">';
																					htm+='<thead>';
																						htm+='<tr>';
																							htm+='<th>FastPay ID</th>';
																							htm+='<th>Operator Ref</th>';
																							htm+='<th>Amount</th>';
																							htm+='<th>Status</th>';
																							
																						htm+='</tr>';
																					htm+='</thead>';

																					htm+='<tbody>';
																						htm+='<tr>';
																							htm+='<td>'+response.TxnId+'</td>';
																							htm+='<td>'+response.OPTId+'</td>';
																							htm+='<td>'+TXN_REQUEST.transamount+'</td>';
																							htm+='<td class="fontbold">'+response.status+'</td>';
																						htm+='</tr>';
																					htm+='</tbody>';
																				htm+='</table>';
																			htm+='</div>';

																			htm+='<div class="width-100">';
																			htm+='<div class="float-left dashed-border"><span class="fontbold font14 light-txt">Total Amount: </span><span class="fontbold font14 dark-txt">Rs '+TXN_REQUEST.transamount+'</span></div>';
																			htm+='<div class="float-right dashed-border"><span class="fontbold font14 light-txt">Amount (In Words): </span><span class="fontbold font14 dark-txt">'+words + '</span></div>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center fontbold font12 mt-20 mb-20">';
																				htm+='<p class="mb-0">@2019 All Rights Reserved</p>';
																				htm+='<p class="mb-0">This is a System generated Receipt. Hence no seal or signature required.</p>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center">';
																				htm+='<a class="btn btn-dark back-btn white-txt" id="antr_py"><img src="images/left-arrow-white.svg" width="20" class="mr-2">Pay Again</a>';
																				htm+='<a class="blue-btn btn white-txt"><img src="images/white-printer-icon.svg" width="20" class="mr-2">Print</a>';
																			htm+='</div>';



																		htm+='</div>';
																htm+='</div>';
															htm+='</div>';
														htm+='</div>';
													htm+='</div>';
												htm+='</div>';
											htm+='</section>';
											
											$("#instntpy_txn_cnfrmtn_screen").html(htm); 
	
											
											}else if (response.error == 3) {
												response.TxnId = response.TxnId ? response.TxnId : '00';
											    response.OPTId = response.OPTId ? response.OPTId : '00';
												var htm='<section class="width-100 transaction-status-section mt-20">';
												htm+='<div class="container">';
													htm+='<div class="row">';
														htm+='<div class="col-lg-8 mx-auto">';
															htm+='<div class="width-100 transaction-status-col">';
												htm+='<div class="width-100 transaction-status-header dark-pending-bg"><p class="mb-0 white-txt font18"><span class="transaction-status-icon float-left mr-2"><img src="images/check.svg" width="20"></span>';
																htm+='<span class="float-left" style="margin-top: 5px;">TRANSACTION PENDING('+response.error_desc+')</span></p></div>';
																htm+='<div class="width-100 transaction-status-inner pl-15 pr-15">';
																		htm+='<div class="width-100">';
																			htm+='<div class="transaction-list-details">';
																				htm+='<div class="transaction-list-details-col bord-right-set">';
																					htm+='<ul>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Outlet Name:</span> <span class="font14 font-medium black-txt float-right">PK MOBILE</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Name:</span> <span class="font14 font-medium black-txt float-right">'+reminame+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Beneficiary:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.name+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Bank Name:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';

																				htm+='<div class="transaction-list-details-col">';
																					htm+='<ul>';
																						
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Mobile:</span> <span class="font14 font-medium black-txt float-right">'+reminumber+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Account No:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Date & Time:</span> <span class="font14 font-medium black-txt float-right">'+response.date+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';
																			htm+='</div>';
																		htm+='</div>';

																		htm+='<div class="width-100 mt-20">';
																			htm+='<div class="gray-header mb-20">Transaction Summary</div>';	

																			htm+='<div class="table-responsive">';
																				htm+='<table class="table font14 dark-txt">';
																					htm+='<thead>';
																						htm+='<tr>';
																							htm+='<th>FastPay ID</th>';
																							htm+='<th>Operator Ref</th>';
																							htm+='<th>Amount</th>';
																							htm+='<th>Status</th>';
																							
																						htm+='</tr>';
																					htm+='</thead>';

																					htm+='<tbody>';
																						htm+='<tr>';
																							htm+='<td>'+response.TxnId+'</td>';
																							htm+='<td>'+response.OPTId+'</td>';
																							htm+='<td>'+TXN_REQUEST.transamount+'</td>';
																							htm+='<td class="fontbold">'+response.status+'</td>';
																						htm+='</tr>';
																					htm+='</tbody>';
																				htm+='</table>';
																			htm+='</div>';

																			htm+='<div class="width-100">';
																			htm+='<div class="float-left dashed-border"><span class="fontbold font14 light-txt">Total Amount: </span><span class="fontbold font14 dark-txt">Rs '+TXN_REQUEST.transamount+'</span></div>';
																			htm+='<div class="float-right dashed-border"><span class="fontbold font14 light-txt">Amount (In Words): </span><span class="fontbold font14 dark-txt">'+words + ' Only</span></div>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center fontbold font12 mt-20 mb-20">';
																				htm+='<p class="mb-0">@2019 All Rights Reserved</p>';
																				htm+='<p class="mb-0">This is a System generated Receipt. Hence no seal or signature required.</p>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center">';
																				htm+='<a class="btn btn-dark back-btn white-txt" id="antr_py"><img src="images/left-arrow-white.svg" width="20" class="mr-2">Pay Again</a>';
																				htm+='<a class="blue-btn btn white-txt"><img src="images/white-printer-icon.svg" width="20" class="mr-2">Print</a>';
																			htm+='</div>';



																		htm+='</div>';
																htm+='</div>';
															htm+='</div>';
														htm+='</div>';
													htm+='</div>';
												htm+='</div>';
											htm+='</section>';
											
											$("#instntpy_txn_cnfrmtn_screen").html(htm); 
	
											} else if (response.error == 2) {
												window.location.reload('true');
											} else {
													 response.TxnId = response.TxnId ? response.TxnId : '00';
													    response.OPTId = response.OPTId ? response.OPTId : '00';
												var htm='<section class="width-100 transaction-status-section mt-20">';
												htm+='<div class="container">';
													htm+='<div class="row">';
														htm+='<div class="col-lg-8 mx-auto">';
															htm+='<div class="width-100 transaction-status-col">';
												htm+='<div class="width-100 transaction-status-header dark-pending-bg"><p class="mb-0 white-txt font18"><span class="transaction-status-icon float-left mr-2"><img src="images/check.svg" width="20"></span>';
																htm+='<span class="float-left" style="margin-top: 5px;">TRANSACTION PENDING('+response.error_desc+')</span></p></div>';
																htm+='<div class="width-100 transaction-status-inner pl-15 pr-15">';
																		htm+='<div class="width-100">';
																			htm+='<div class="transaction-list-details">';
																				htm+='<div class="transaction-list-details-col bord-right-set">';
																					htm+='<ul>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Outlet Name:</span> <span class="font14 font-medium black-txt float-right">PK MOBILE</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Name:</span> <span class="font14 font-medium black-txt float-right">'+reminame+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Beneficiary:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.name+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Bank Name:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';

																				htm+='<div class="transaction-list-details-col">';
																					htm+='<ul>';
																						
																						htm+='<li><span class="font14 font-medium black-txt float-left">Sender Mobile:</span> <span class="font14 font-medium black-txt float-right">'+reminumber+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Account No:</span> <span class="font14 font-medium black-txt float-right">'+TXN_REQUEST.accountno+'</span></li>';
																						htm+='<li><span class="font14 font-medium black-txt float-left">Date & Time:</span> <span class="font14 font-medium black-txt float-right">'+response.date+'</span></li>';
																					htm+='</ul>';	
																				htm+='</div>';
																			htm+='</div>';
																		htm+='</div>';

																		htm+='<div class="width-100 mt-20">';
																			htm+='<div class="gray-header mb-20">Transaction Summary</div>';	

																			htm+='<div class="table-responsive">';
																				htm+='<table class="table font14 dark-txt">';
																					htm+='<thead>';
																						htm+='<tr>';
																							htm+='<th>FastPay ID</th>';
																							htm+='<th>Operator Ref</th>';
																							htm+='<th>Amount</th>';
																							htm+='<th>Status</th>';
																							
																						htm+='</tr>';
																					htm+='</thead>';

																					htm+='<tbody>';
																						htm+='<tr>';
																							htm+='<td>'+response.TxnId+'</td>';
																							htm+='<td>'+response.OPTId+'</td>';
																							htm+='<td>'+TXN_REQUEST.transamount+'</td>';
																							htm+='<td class="fontbold">'+response.status+'</td>';
																						htm+='</tr>';
																					htm+='</tbody>';
																				htm+='</table>';
																			htm+='</div>';

																			htm+='<div class="width-100">';
																			htm+='<div class="float-left dashed-border"><span class="fontbold font14 light-txt">Total Amount: </span><span class="fontbold font14 dark-txt">Rs '+TXN_REQUEST.transamount+'</span></div>';
																			htm+='<div class="float-right dashed-border"><span class="fontbold font14 light-txt">Amount (In Words): </span><span class="fontbold font14 dark-txt">'+words + ' Only</span></div>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center fontbold font12 mt-20 mb-20">';
																				htm+='<p class="mb-0">@2019 All Rights Reserved</p>';
																				htm+='<p class="mb-0">This is a System generated Receipt. Hence no seal or signature required.</p>';
																			htm+='</div>';

																			htm+='<div class="width-100 text-center">';
																				htm+='<a class="btn btn-dark back-btn white-txt" id="antr_py"><img src="images/left-arrow-white.svg" width="20" class="mr-2">Pay Again</a>';
																				htm+='<a class="blue-btn btn white-txt"><img src="images/white-printer-icon.svg" width="20" class="mr-2">Print</a>';
																			htm+='</div>';



																		htm+='</div>';
																htm+='</div>';
															htm+='</div>';
														htm+='</div>';
													htm+='</div>';
												htm+='</div>';
											htm+='</section>';
											
											$("#instntpy_txn_cnfrmtn_screen").html(htm); 
	
											}
											
											  $('#antr_py').click(function (e) {
                                                e.preventDefault();
                                                $("#instntpy_afterlgn").show();  
												 table.ajax.reload( null, false ); 
												$('#TrnsAmnt-' + remitid + '').val('')												
												$("#instntpy_txn_cnfrmtn_screen").html(''); 
												
												 status = true;
												
												
                                            });
										}
									}).fail(function (err) {
										throw err;
									});

                                }else if (response.error == 2) {   
                                    window.location.reload(true);
                                } else {

                                    toastr.error(response.error_desc);
                                    la1.ladda('stop');
                                }
                              
                            }
                        }, 'json').fail(function (error) {
                            la1.ladda('stop');
							
                            throw error;
                        });
                  
                }
			
			}
				
			}
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
                     acc_summary();
                    table.ajax.reload( null, false );
                    if (response.error == 0) {

                      /*   $('#search_trans_val').val('');
                        $('#response_transaction').html(''); */
                        toastr.success('Request Completed Successfully.');
                        swal.close();

                    } else if (response.error == 2) {
                        window.location.reload('true');
                    } else {
						

                       if (la)
                        {
                            la.ladda('stop');
                        } 

                        toastr.error(response.error_desc);
                        swal.close();
						
                    }
                    if (param.la) {
                        param.la.ladda('stop');
                    }
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
		
	   $('#user_logout_now').click(function (e) {
            e.preventDefault();
            $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
            var la = $(this).ladda();
            la.ladda('start');
            $.ajax({
                method: 'POST',
                url: 'MoneyTransfer/InstantPayRemitterLogout',
                dataType: 'JSON',
            }).done(function (response) {
                if (response) {
                    if (response.error == 0) {
                        window.location.reload('true');
                    } else if (response.error == 2) {
                        window.location.reload('true');
                    } else {
                        toastr.error(response.error_desc);
                    }
                }
            }).fail(function (err) {
                throw err;
            });
        });
	return {
            init: function () {
                 acc_summary();
				 GET_BANK_WITH_CIFSC();
				 benef_list();
				   getmodalifsconduty.init_ifsc();
                /*fetchTransaction();
                default_tr_value();
              
              
                //find_ifsc();
                benef_list(); */



            }
        };
    }();
    $(document).ready(function () {
        InstanstPayAfterLogin.init();
        $(".js-select").select2({
            theme: "bootstrap",
        }); 
    });

</script>   
</body>
</html>
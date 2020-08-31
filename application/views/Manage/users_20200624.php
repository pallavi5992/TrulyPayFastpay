<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$userDetails = get_user_details();
?>	
<style>
.toggle.btn
{
min-width: 100px !important;
}

.green-btn
{
color:#fff;
}

.red-btn
{
color:#fff;
}

.modal-rw h4 {
    float: left;
    font-size: 1rem;
    margin-top: 10px;
}
.modal-rw {
    width: 100%;
    float: left;
    padding: 10px;
    border: 1px solid #eeeeee;
    margin-bottom: 10px;
}
.modal-rw button {
    float: right;
}
.switch-button {
    display: inline-block;
    line-height: 16px;
    border-radius: 50px;
    background-color: #ccc;
    width: 74px;
    height: 30px;
    padding: 2px;
    position: relative;
    overflow: hidden;
    vertical-align: middle;
}
.switch-button input#Remap_process_With {
    display: none;
}
.switch-button.switch-button-lg.remapp  label:before{
content:'admin';
}   
.switch-button input#Remap_process_With:checked+span {
    background-color: #9674c8;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 2px;
}
.switch-button input#Remap_process_With:checked+span label {
    float: right;
}
.switch-button input#Remap_process_With:checked+span label:before {
    position: absolute;
    z-index: 0;
    content: "Others";
    color: #fff;
    left: 0;
    text-align: left;
    padding-left: 8px;
}
.switch-button input#add_blnc_isdue {
    display: none;
}
.switch-button input#dwn {
    display: none;
}
.switch-button input#add_blnc_isdue:checked+span {
    background-color: #9674c8;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 2px;
}
.switch-button input#dwn:checked+span {
    background-color: #9674c8;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 2px;
}
.switch-button input#add_blnc_isdue:checked+span label {
    float: right;
}
.switch-button input#dwn:checked+span label {
    float: right;
}
.switch-button.switch-button-lg label {
    height: 26px;
    width: 26px;
}
.switch-button input#add_blnc_isdue:checked+span label:before {
    position: absolute;
    z-index: 0;
    content: "Yes";
    color: #fff;
    left: 0;
    text-align: left;
    padding-left: 8px;
}
.switch-button input#dwn:checked+span label:before {
    position: absolute;
    z-index: 0;
    content: "Up";
    color: #fff;
    left: 0;
    text-align: left;
    padding-left: 8px;
}
.switch-button label.dwnd:before {
    position: absolute;
    font-size: 11px;
    z-index: 0;
    content: "Down";
    right: 0;
    display: block;
    width: 100%;
    height: 100%;
    line-height: 27px;
    top: 0;
    text-align: right;
    padding-right: 8px;
    color: #fff;
}
.switch-button label:before {
    position: absolute;
    font-size: 11px;
    z-index: 0;
    content: "No";
    right: 0;
    display: block;
    width: 100%;
    height: 100%;
    line-height: 27px;
    top: 0;
    text-align: right;
    padding-right: 8px;
    color: #fff;
}
.switch-button label {
    border-radius: 50%;
    border: 1px solid transparent;
    background-color: #fff;
    margin: 0;
    height: 22px;
    width: 22px;
    display: inline-block;
    cursor: pointer;
    background-clip: padding-box;
}
</style>			
				<div class="col-lg-10">
				<div class="tab-content">
					<div class="width-100 tab-pane fade show active" id="users">
						<nav>
						  <div class="nav nav-tabs" id="nav-tab" role="tablist">
						    <a class="nav-item nav-link active col text-center" data-toggle="tab" href="#userlist" role="tab"  aria-selected="true">USER LIST</a>
						    <a class="nav-item nav-link col text-center"  data-toggle="tab" href="#createuser" role="tab"  aria-selected="false">CREATE USER</a>
						  </div>
						</nav>
						<div class="tab-content mt-20" id="nav-tabContent">
						  <div class="tab-pane fade show active table-responsive" id="userlist" role="tabpanel" >
						  	<table class="table datatables table-scrollable">
						  		<thead class="thead-blue">
						  			
						  		</thead>
						  	</table>
						  </div>
						  <div class="tab-pane fade" id="createuser" role="tabpanel">
						  	<div class="width-100 section-top-subheading mb-3 bord-bottom"><h6 class="dark-txt fontbold">CREATE USER</h6></div>
						  	<form id="create_usr">
						  		<div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">User Role</label>
									<select class="custom-select" id="AddUserNew_Role">
									
									</select>
									 <span data-for="AddUserNew_Role"></span>
									</div>
									<!-- <div class="form-group col-lg-6">
									<label class="fontbold">User Role</label>
									<select class="custom-select" id="AddUserNew_Role">
									
									</select>
									 <span data-for="AddUserNew_Role"></span>
									</div> -->

									<?php if($userDetails['role_id']==1){ ?>
                                   
                                        <div class="form-group col-lg-6">
                                            <label>Plan<span class="mandotry">*</span></label>
                                            <select name="select" class="form-control input-xs Plan" id="AddUserNew_Plan">
                                                <option value="">Select Plan</option>

                                            </select>
                                            <span data-for="AddUserNew_Plan"></span>
                                        </div>
                                    
                                   
                                   <?php } ?>
									</div>

									<div class="form-row">
									 <div class="form-group col-lg-6">
									 <label class="fontbold">First Name</label>
									 <input type="text" class="form-control" placeholder="First Name" id="AddUserNew_FirstName">
									  <span data-for="AddUserNew_FirstName"></span>
									 </div>
									  <div class="form-group  col-lg-6">
									 <label class="fontbold">Last Name</label>
									 <input type="text" class="form-control" placeholder="Last Name" id="AddUserNew_LastName">
									   <span data-for="AddUserNew_LastName"></span>
									 </div>
								 </div>

								 <div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">Mobile No</label>
									<input type="text" class="form-control" name="" placeholder="Mobile No" id="AddUserNew_Mobile">
									  <span data-for="AddUserNew_Mobile"></span>
									</div>
									 <div class="form-group col-lg-6">
									 <label class="fontbold">Email</label>
									 <input type="text" class="form-control" placeholder="Email" id="AddUserNew_Email">
									   <span data-for="AddUserNew_Email"></span>
									 </div>
								 </div>

								 	<div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">Business Name:</label>
									<input type="text" class="form-control" name="" placeholder="Business Name" id="AddUserNew_BusinessName">
									 <span data-for="AddUserNew_BusinessName"></span>
									</div>
									 <div class="form-group col-lg-6">
									 <label class="fontbold">Business Address</label>
									 <input type="text" class="form-control" placeholder="Business Address" id="AddUserNew_BusinessAddress">
									  <span data-for="AddUserNew_BusinessAddress"></span>
									 </div>
								    </div>

								    <div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">Permanent Address:</label>
									<input type="text" class="form-control" name="" placeholder="Permanent Address" id="AddUserNew_RegisteredAddress">
									  <span data-for="AddUserNew_RegisteredAddress"></span>
									</div>
									 <div class="form-group col-lg-6">
									 <label class="fontbold">Aadhar Number:</label>
									 <input type="text" class="form-control" placeholder="Aadhar Number" id="AddUserNew_Aadhar">
									  <span data-for="AddUserNew_Aadhar"></span>
									 </div>
								    </div>


								    <div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">City</label>
									<input type="text" class="form-control" name="" placeholder="City" id="AddUserNew_BusinessCity">
									 <span data-for="AddUserNew_BusinessCity"></span>
									</div>
									 <div class="form-group col-lg-6">
									 <label class="fontbold">Pincode:</label>
									 <input type="text" class="form-control" placeholder="Pincode" id="AddUserNew_Pincode">
									 <span data-for="AddUserNew_Pincode"></span>
									 </div>
								    </div>

								    <div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">State</label>
									<input type="text" class="form-control" name="" placeholder="State" id="AddUserNew_BusinessState">
									 <span data-for="AddUserNew_BusinessState"></span>
									</div>
									 <div class="form-group col-lg-6">
									 <label class="fontbold">Pan Number</label>
									 <input type="text" class="form-control" placeholder="Pan Number" id="AddUserNew_Pan">
									 <span data-for="AddUserNew_Pan"></span>
									 </div>
								    </div>

								    <div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">GSTIN</label>
									<input type="text" class="form-control" name="" placeholder="GSTIN" id="AddUserNew_GSTIN">
									   <span data-for="AddUserNew_GSTIN"></span>
									</div>

									<div class="form-group col-lg-6">
									<label class="fontbold">Date Of Birth</label>
									<input type="text" class="form-control DateOfBirth" name="" placeholder="Date Of Birth" id="AddUserNew_DateOfBirth">
									   <span data-for="AddUserNew_DateOfBirth"></span>
									</div>
								    </div>

								    <div class="form-row">
									<div class="form-group col-lg-6">
									<label class="fontbold">Is Active</label>
									<!--   <input type="checkbox" id="AddUserNew_IsActive"> -->
									     <input type="checkbox" class=" AddUserNew_IsActive" id="AddUserNew_IsActive" >
									</div>
									 <div class="col-lg-6">
									 <label class="fontbold">Login OTP</label>
									<!--    <input type="checkbox" id="AddUserNew_TwoFactor"> -->
									   <input type="checkbox" class=" AddUserNew_TwoFactor" id="AddUserNew_TwoFactor">
									 </div>
								    </div>
								    <div class="form-row">
										<div class="form-group col-lg-6">
										<label class="fontbold">ID PROOF</label>
										<div class="custom-file">
	  										<input type="file" class="custom-file-input" id="AddUserNew_IDProof" name="AddUserNew_IDProof">
	 										 <label class="custom-file-label" for="customFile">Choose file</label>
	 										   <span data-for="AddUserNew_IDProof"></span>
										</div>
										<div class="valid-feedback-set">
	        								document: Passbook, Photo Id.
	      								</div>
										</div>

										<div class="form-group col-lg-6">
										<label class="fontbold">Address PROOF</label>
										<div class="custom-file">
	  										<input type="file" class="custom-file-input" id="AddUserNew_AddressProof" name="AddUserNew_AddressProof">
	 										 <label class="custom-file-label" for="customFile">Choose file</label>
	 										    <span data-for="AddUserNew_AddressProof"></span>
										</div>
										<div class="valid-feedback-set">
	        								document: Addressproof.
	      								</div>
										</div>

										<div class="form-group col-lg-4">
										<label class="fontbold">Photo</label>
										<div class="custom-file">
	  										<input type="file" class="custom-file-input" id="AddUserNew_PhotoProof" name="AddUserNew_PhotoProof">
	 										 <label class="custom-file-label" for="customFile">Choose file</label>
	 										    <span data-for="AddUserNew_PhotoProof"></span>
										</div>
										<div class="valid-feedback-set">
	        								Photo Id
	      								</div>
										</div>
										
										 <div class="width-100 mt-2 text-center">
								    <button class="btn btn-primary" style="width: 200px;" id="AddUserNew">Proceed</button>
								    </div>
								    </div>

								  

						  	</form>
						  </div>
						</div>
					</div>

					<div class="width-100 tab-pane fade" id="pending-activation">
						pending aactivation					
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
	var USERLIST = function () {
	    var Regex = <?php echo json_encode($Regex); ?>;
	    var prcss = false;
        var cnfrm = false;
        var role={};
        var updt_plan_list={};
        var RemapRole = {};


           var FetchPlanFrUpdate = function (role_id, planId) {
            $.ajax({
                method: 'POST',
                url: 'Manage/Fetch_pln_frUser',
                dataType: 'JSON',
                data: {'roleId': role_id},
            }).done(function (response) {
                if (response)
                {
                      console.log(response)
                    if (response.error_data == 1)
                    {
                        
                        toastr.error(response.error_desc);

                    } else if (response.error_data == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error_data == 0) {
                      
                        var str = '';
                        str += '<option value="">Select Plan</option>';
                        $.each(response.data, function (k, v) {
                            updt_plan_list[v.plan_id] = v;
                            console.log(v.plan_id)
                            var pln = (planId == v.plan_id) ? 'selected' : '';
                            str += '<option value="' + v.plan_id + '" ' + pln + '>' + v.plan_name + ' (' + v.plan_code + ')</option>';
                        });

                        $('#UpdateUserPlan').html(str);
                    }
                }
            }).fail(function (err) {
                throw err;
            });
        }

	  var ConfigUser = function () {
	  	  var table = $('.datatables').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/get_usr_lst",
                    type: 'post',
                    "dataSrc": function (json) {
                    
                        if (json.error_data == 2)
                        {
                            window.location.reload(true);
                        } else if (json.error_data == 1)
                        {
                            toastr.error(json.error_desc, 'Oops!');
                        }
                        return json.data;
                    }
                },
                // responsive: true,
                // order: [],
                columns: [
         //         	{"title" : "","class": "details-control",
	        //          "orderable": false,
	        //            "render": function ( data, type, row, meta ) {
					    //   return null;
					    // }
         //    		},

            		 {
			 		 "title" : "User Id",
	                
	                 "data": "user_id",
            		},

            		 {
			 		 "title" : "User Name",
	                
	                 	render: function (data, type, full, meta) {
                        return full.first_name + ' ' + full.last_name;
                    }

            		},

            		 {
			 		 "title" : "Mobile No.",
	               
	                 "data": "mobile",
            		},
            		 {
			 		 "title" : "Email",
	               
	                 "data": "email",
            		},
            		 {
			 		 "title" : "Balance",
	               
	                 "data": "rupee_balance",
            		},


            	   
            		 {"title" : "Active",data: "is_active", 
            		 render: function (data, type, full, meta) {
                            var status = "";

                            var userPending = '';
                            if (full.is_block == 0) {
                                userPending = '';
                            } else {
                                userPending = '<span style="color:red;font-size: 12px;">User Pending For Activation From Admin</span>';
                            }

                            if (full.is_active == 0) {
                                status = '<span class=" btn red-btn mr-2">Inactive</span><br />' + userPending;
                            } else if (full.is_active == 1) {
                                status = '<span class="btn green-btn mr-2">Active</span><br />' + userPending;
                            } else {
                                status = "Undefined";
                            }
                            return status;
                        }
            		},

            		 {
			 		 "title" : "Action",
	                 "orderable": false,
	                 // "data": "status",
	                  "render": function ( data, type, full, meta ) {
					      return '<a data-edit_usr="' + full.user_id + '" id="User_Edit" class="btn btn-info white-txt">Edit</a>';
					    }
            		},
 
                   
                ],

             
            });

			 $('.datatables tbody').on('click', 'td.details-control', function () {
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
		  });


	
		    table.on('click', '#User_Edit', function () {
            var usrid = $(this).data('edit_usr');
            var row = $(this).closest('tr');
            var showtd1 = table.row(row).data();
            if (showtd1['user_id'] == usrid) {
            	   FetchPlanFrUpdate(showtd1.role_id, showtd1.plan_id);
                var str = '<div class="modal fade" id="mdl_usr_dsc"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                str += '<div class="modal-dialog" role="document" id="lrge_modal">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                str += '<h5 class="modal-title" id="head_ttl">Manage: ' + showtd1.first_name + '  '  + showtd1.last_name + ' (Account:' + showtd1.user_id + ')</h5>';
                str += '<h5 class="modal-title" id="head_ttl2" style="display:none;"></h5>';
                str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                str += '<span aria-hidden="true">&times;</span>';
                str += '</button>';
                str += '</div>';
                str += '<div class="modal-body">';
                str += '<div id="model_content">';
                str += '<div class="row manage-blnc-users" id="first_div" style="">';
                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                str += '<div class="k-portlet">';
                str += '<div class="modal-rw"><h4>Add Balance</h4>';
                str += '<button type="button" class="btn btn-primary" data-add_blnc="' + showtd1.user_id + '" id="add_blnc_usr">Add Balance</button>';
                str += '</div>';
                str += '<div class="modal-rw"><h4>Deduct Balance</h4>';
                str += '<button type="button" class="btn btn-primary" data-dedct_blnc="' + showtd1.user_id + '" id="dedct_blnc_usr">Deduct Balance</button>';
                str += '</div>';
                str += '<div class="modal-rw"><h4>Update User Details</h4>';
                str += '<button class="btn btn-primary" data-actvt_usr="' + showtd1.user_id + '" id="updt_usr_dtl">Update User Details</button>';
                str += '</div>';
                if ((showtd1.is_active == 0)) {
                        if (showtd1.is_blocked != 1) {
                            str += '<div class="modal-rw"><h4>Activate User</h4>';
                            str += '<button class="btn btn-primary" data-actvt_usr="' + showtd1.user_id + '" id="actvate_usr">Activate User</button>';
                            str += '</div>';
                        }


                } else {
                        str += '<div class="modal-rw"><h4>Deactivate User</h4>';
                        str += '<button class="btn btn-primary" data-dcvt_usr="' + showtd1.user_id + '" id="dctvt_usr">Deactivate User</button>';
                        str += '</div>';
                }
                str += '<div class="modal-rw"><h4>KYC document</h4>';
                str += '<button class="btn btn-primary" data-kyc_doc="' + showtd1.user_id + '" id="kyc_data">KYC Document</button>';
                str += '</div>';
                str += '<div class="modal-rw"><h4>Update Plan</h4>';
                str += '<button class="btn btn-primary" data-mng_pln="' + showtd1.user_id + '" id="mng_pln">Update Plan</button>';
                str += '</div>';
                
                // str += '<div class="modal-rw"><h4>Reset Pass./PIN</h4>';
                // str += '<button class="btn btn-primary" data-rst_pin="' + showtd1.user_id + '" id="reset_pin" style="margin-left: 12px;">Reset Pin</button> <button class="btn btn-primary" data-rst_pswd="' + showtd1.accnt_id + '" id="reset_pss" >Reset Password</button>';
                // str += '</div>';
                 if ((showtd1.role_id != 2)) {
                    str += '<div class="modal-rw"><h4>Parent Remapping</h4>';
                    str += '<button class="btn btn-primary" data-prnt_remap="' + showtd1.user_id + '" id="parnt_remap">Parent Remapping</button>';
                    str += '</div>';
                    }
                     str += '</div> ';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '<div id="usr_blnc_form" style="display:none;">';
                str += '</div>';
                str += '<div id="usr_deduct_blnc_form" style="display:none;">';
                str += '</div>';
                str += '<div id="usr_updt_dtl_form" style="display:none;">';
                str += '</div>';
                str += '<div id="kyc_dtl_documents_' + showtd1.user_id + '" style="display:none;">';
                str += '</div>';
                str += '<div id="mng_plan_form_' + showtd1.user_id + '" style="display:none;">';
                str += '</div>';
                 str += '<div id="reset_pswd_form_' + showtd1.user_id + '" style="display:none;">';
                str += '</div>';
                 str += '<div id="reset_pin_form_' + showtd1.user_id + '" style="display:none;">';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                $('body').append(str);
                $('#mdl_usr_dsc').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                })
                $('#mdl_usr_dsc').on('hidden.bs.modal', function () {
                    $('#mdl_usr_dsc').remove();
                    table.ajax.reload(null, false);
                });

                  //--------------  User Plan ------------------------//
                          $('#mng_pln').click(function (e) {
		                    e.preventDefault();
		                    var acid = $(this).data('mng_pln');


		                    if (acid == showtd1.user_id) {
		                        $("#head_ttl").hide();
		                        $('#head_ttl2').show().html('Manage ' + showtd1.first_name + ' ' + showtd1.last_name+' Plan');

		                        $('#mng_plan_form_'+showtd1.user_id+'').show();
		                        $("#first_div").hide();
							        $.ajax({
					                method: 'POST',
					                url: 'Manage/Fetch_pln_frUser',
					                dataType: 'JSON',
					                data: {'roleId': showtd1.role_id},
					            }).done(function (response) {
					                if (response)
					                {
					                      console.log(response)
					                    if (response.error_data == 1)
					                    {
					                        
					                        toastr.error(response.error_desc);

					                    } else if (response.error_data == 2)
					                    {
					                        window.location.reload(true);
					                    } else if (response.error_data == 0) {

					                var str = '<div class="row" id="mngplnrt_form_div">';
                                    str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                    str += '<div class="panel-body">';
                                    str += '<form action="#" id="updt_rate_form">';
                                    str += '<div class="row">';
                                    str += ' <div class="col-md-12">';
                                    str += ' <div class="form-group">';

                                
                                    str += ' <label>Plan</label>';
                                    str += '<div class="input-group">';
                                    str += '<select class="form-control custom-select" name="UpdateUserPlan" id="UpdateUserPlan">';
                                    str += '<option value="default">Select Plan</option>';
                                   
					                $.each(response.data, function (k, v) {
					                            updt_plan_list[v.plan_id] = v;
					                            console.log(v.plan_id)
					                            var pln = (showtd1.plan_id == v.plan_id) ? 'selected' : '';
					                            str += '<option value="' + v.plan_id + '" ' + pln + '>' + v.plan_name + ' (' + v.plan_code + ')</option>';
					                 });
                                    str += '</select>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '<div class="modal-footer">';
                                    str += '<button type="submit" class="btn btn-secondary"  id="bcl_rate">Back</button>';
                                    str += '<button type="submit" class="btn btn-brand legitRipple ladda-button"data-style="zoom-in" id="pln_update">Update</button>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</form>';
                                    str += '</div>';
                                    str += '</div>';

                                    $('#mng_plan_form_' + showtd1.user_id + '').html(str).show();
                                    KeyPress_Validation();

                                    $('#bcl_rate').click(function (e) {
                                        e.preventDefault();
                                        $('#head_ttl2').hide();
                                        $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account:' + showtd1.user_id + ')');

                                        $("#first_div").show();
                                        $('#mng_plan_form_' + showtd1.user_id + '').hide().html('');
                                    })
                                   update_plan_view(showtd1, updt_plan_list);
					                      
					                       
					                        
					                    }
					                }
					            }).fail(function (err) {
					                throw err;
					            });
		                   
		                    }

		                    })

							var update_plan_view = function(showtd1, updt_plan_list){
					    $('#pln_update').click(function (e) {
                        e.preventDefault();
                        var params = {'valid': true};
                        var actid = $(this).attr('id');
                        params.UserId = showtd1.user_id;
                        params.PlanId = $('#UpdateUserPlan option:selected').val();
                        if (params.valid == true) {
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');
                            $.ajax({
                                method: 'POST',
                                url: 'Manage/UserPlanUpdated',
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                    if (response.error_data == 1)
                                    {
                                        toastr.error(response.error_desc);

                                    } else if (response.error_data == 2)
                                    {
                                        window.location.reload(true);

                                    } else if (response.error_data == 0) {
                                        
                                        toastr.info(response.msg);

                                        $('#head_ttl2').hide();
                                        $('#head_ttl').show().html('Action User Details of ' + showtd1.first_name + '  ' + showtd1.last_name + ' (Account:' + showtd1.user_id + ')');
                                        $('#updt_rate_form').hide().html('');
                                        $('#mdl_usr_dsc').modal('hide');

                                    }
                                    la.ladda('stop');
                                }
                                la.ladda('stop');
                            }).fail(function (err) {
                                la.ladda('stop');
                                throw err;
                            });
                        }
                    });
						}
							
							

                        //--------------  User Plan End------------------------//

                /*************************************remap user parent*******************************/
                $('#parnt_remap').click(function (e) {
                	e.preventDefault();
                    var acid = $(this).data('prnt_remap');
          
                     if (acid == showtd1.user_id) {
                     	
                        $.ajax({
                            method: 'POST',
                            url: 'Manage/FetchParentDtlOfUsr',
                            dataType: 'JSON',
                            data: {data: showtd1.parent_id, 'User': showtd1.user_id, ViewRoleId: showtd1.role_id}
                        }).done(function (response) {
                            if (response)
                            {
                                if (response.error == 1)
                                {
                                   
                                    toastr.error(response.error_desc);

                                } else if (response.error == 2)
                                {

                                    window.location.reload(true);

                                } else if (response.error == 0) {

                                	
					                    $('#head_ttl').show().html('Current Parent ' + response.data.first_name + ' ' + response.data.last_name + ' (Role: ' + response.data.role_name + ')');
					                    $('#reset_pin_form_' + showtd1.user_id + '').show();
					                    $("#first_div").hide();
					                    

					                    /////parent id 0 case show only Name /////
					                    var str = '<div class="row" id="sec_form_div">';
					                    str += '<div class="col-sm-12">';
					                    str += '<div class="panel-body">';
					                    str += '<form action="#" id="updt_users_form">';
					                    str += '<div class="row">';
					                    if(response.data.role_id==1){
					                   	str += '<div class="col-md-6">';
					                    str += '<div class="form-group">';
					                    str += '<label>Name</label>';
					                    str += '<div class="input-group">';
					                    str += '<input type="text" placeholder="Enter user role name" class="form-control" value="' + response.data.role_name + '" disabled>';
					                    str += '</div>';
					                    str += '</div>';
					                    str += ' </div>';
					                    }else{
					                    str += '<div class="col-md-6">';
					                    str += '<div class="form-group">';
					                    str += '<label>Name</label>';
					                    str += '<div class="input-group">';
					                    str += '<input type="text" placeholder="Enter user role name" class="form-control" value="' + response.data.first_name + ' ' + response.data.last_name + '" disabled>';
					                    str += '</div>';
					                    str += '</div>';
					                    str += ' </div>';
					                    str += ' <div class="col-md-6">';
					                    str += ' <div class="form-group has-error">';

					                    str += ' <label>Mobile</label>';
					                    str += '<div class="input-group">';
					                    str += '<input type="text" placeholder="Enter user full name" class="form-control" name="full_nam" id="full_nam" value="' +  response.data.mobile + '">';
					                    str += '</div>';
					                    str += '</div>';
					                    str += '</div>';
					                    
					                    str += '</div>';
					                    str += ' <div class="col-md-12">';
					                    str += ' <div class="form-group has-error">';
					                    str += '<label>Role</label>';
					                    str += ' <div class="input-group">';
					                    str += '<input type="text" placeholder="Enter business name" class="form-control" name="bs_nam" id="bs_nam" value="' + response.data.role_name + '">';
					                    str += '  </div>';
					                    str += ' </div>';
					                	}
					                    str += ' <div class="col-md-12"> <div class="form-group"><label>Remap With</label><div class="input-group"><div class="switch-button switch-button-lg remapp"><input type="checkbox" name="Remap_process_With" id="Remap_process_With"><span><label for="Remap_process_With"></label></span>  </div></div></div> </div>';

					                       str += ' <div id="default"  class="col-md-12">';
					                    str += '<div class="modal-footer">';
					                    str += '<button type="submit" class="btn btn-secondary"  id="remap_prnt_back_us">Back</button>';
					                    str += '<button type="submit" class="btn btn-brand Remap_process_admin" id="Remap_process_admin">Proceed</button>';

	                                     str += '</div>';
	                                     str += '</div>';
	                                     
					                    str += ' <div id="WithOthers" style="display:none"  class="col-md-12">';
					                    str += ' <div class="form-group">';
						                str += ' <label>Role</label>';
						                // str += '<div class="input-group">';
						                str += '<select class="form-control custom-select RoleUserList" name="Remap_process_role" id="Remap_process_role">';
					                    str += '<option value="">Select Role</option>';
					                    $.each(response.roles, function (k, v) {
					                            role[v.role_id] = v;
					                            str += '<option value="' + v.role_id + '">' + v.role_name + '</option>';
					                    });
						                str += '</select>';
						                 str += '<span><label for="Remap_process_role"></label></span>';
						                // str += '</div>';
						                str += '</div>';
						                str += '<div class="col-md-12" id="ruser" style="display:none">';
										str += '<div class="form-group">';
					                    str += '<label>Role User List</label>';
					                    str += '<select class="form-control RoleUserList" id="Remap_process_RoleUserList">';
										str += '</select>';
					                    str += '<span data-for="Remap_process_RoleUserList"></span>';
					                    str += '</div>';
					                    str += '</div>';
	                        			str += '<div class="modal-footer">';
					                    str += '<button type="submit" class="btn btn-secondary"  id="remap_prnt_back_us1">Back</button>';
					                    str += '<button type="submit" class="btn btn-brand" id="Remap_process">Proceed</button>';
					                    str += '</div>';
					                    str += ' </div>';
   										str += '<div id="WithAdmin"  style="display:none">';

					                    str += '<div class="modal-footer">';
					                    str += '<button type="submit" class="btn btn-secondary"  id="remap_prnt_back_us1">Back</button>';
					                    str += '<button type="submit" class="btn btn-brand Remap_process_admin" id="Remap_process_admin">Proceed</button>';

	                                     str += '</div>';

					                    str += '</form>';
					                    str += '</div>';
					                    str += '</div>';
					                    str += '</div>';
					                    $('#reset_pin_form_' + showtd1.user_id + '').html(str).show();
					                                    
					                    $('#remap_prnt_back_us,#remap_prnt_back_us1').click(function () {
					                    $('#head_ttl2').hide();
					                    $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account:' + showtd1.user_id + ')');

					                    $("#first_div").show();
					                    $('#reset_pin_form_' + showtd1.user_id + '').hide().html('');
					                });
					                                   


                                      $("#Remap_process_With").click(function () {
                                        if ($(this).is(":checked")) {
                                            $("#WithOthers").show();
                                            $('#WithAdmin').hide();
                                            $('#default').hide();
                                           
                                        } else {
                                            $("#WithOthers").hide();
                                           $('#WithAdmin').show();
                                           
                                        }
                                    });
                                      	KeyPress_Validation();
                                    $('#Remap_process_role').on('change', function (e) {
                                        e.preventDefault();
                                        var Value = $(this).val();

                                        $.ajax({
                                            method: 'POST',
                                            url: 'Manage/FetchParentListDataRoleWise',
                                            dataType: 'JSON',
                                            data: {data: Value}
                                        }).done(function (response) {
                                            if (response)
                                            {
                                                if (response.error == 1)
                                                {
                                                    
                                                    toastr.error(response.error_desc);

                                                } else if (response.error == 2)
                                                {
                                                    window.location.reload(true);
                                                } else if (response.error == 0) {
                                                    RemapRole = {};
                                                    var s = '';
                                                    s += '<option value="">Select Role User List</option>';
                                                    $.each(response.data, function (k, v) {
                                                        RemapRole[v.user_id] = v;
                                                        s += '<option value="' + v.user_id + '">' + v.first_name + ' ' + v.last_name + ' (' + v.mobile + ') </option>';
                                                    });
                                                    $('#ruser').show();
                                                    $('#Remap_process_RoleUserList').html(s);

                                                }
                                            }
                                        }).fail(function (err) {
                                            throw err;
                                        });

                                    });
                                 
                                    $('.Remap_process_admin').click(function (e) {
                                        e.preventDefault();
                                        var params = {'valid': true};
                                        var actid = $(this).attr('id');
                                        params.UserId = showtd1.user_id;

                                        if (params.valid == true) {
                                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                            var la = $(this).ladda();
                                            la.ladda('start');
                                            $.ajax({
                                                method: 'POST',
                                                url: 'Manage/UserRemppingWithAdmin',
                                                data: params,
                                                dataType: 'JSON'
                                            }).done(function (response) {
                                                if (response)
                                                {
                                                    if (response.error == 1)
                                                    {
                                                       
                                                        toastr.error(response.error_desc);

                                                    } else if (response.error == 2)
                                                    {
                                                        window.location.reload(true);
                                                    } else if (response.error == 0) {

                                                            toastr.info(response.msg);
                                               
                                                            $('#first_div').show();
                                                            $('#updt_users_form').hide().html('');
                                                             table.ajax.reload(null, false);
                                                    }
                                                    la.ladda('stop');
                                                }
                                                la.ladda('stop');
                                            }).fail(function (err) {
                                                la.ladda('stop');
                                                throw err;
                                            });
                                        }
                                    });
                                    $('#Remap_process').click(function (e) {
                                        e.preventDefault();
                                    	var params = {'valid': true};
                                        var actid = $(this).attr('id');
                                        params.UserId = showtd1.user_id;
                                        params.Role = $('#' + actid + '_role').val();
                                        params.RoleUserList = $('#' + actid + '_RoleUserList').val();
                                       if (!validate({'id': '' + actid + '_RoleUserList', 'type': 'REMAP', 'data': params.RoleUserList, 'error': true, msg: $('#' + actid + '_RoleUserList').attr('placeholder')})) {
                                            params.valid = false;
                                        }

                                        if (!validate({'id': '' + actid + '_role', 'type': 'ROLE', 'data': params.Role, 'error': true, msg: $('#' + actid + '_role').attr('placeholder')})) {
                                            params.valid = false;
                                        }

                                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                            var la = $(this).ladda();
                                            la.ladda('start');
                                            $.ajax({
                                                method: 'POST',
                                                url: 'Manage/UserRemppingWithParent',
                                                data: params,
                                                dataType: 'JSON'
                                            }).done(function (response) {
                                             

                                                if (response)
                                                {
                                                    if (response.error == 1)
                                                    {
                                                       
                                                        toastr.error(response.error_desc);

                                                    } else if (response.error == 2)
                                                    {
                                                        window.location.reload(true);

                                                    } else if (response.error == 0) {
                                                       
                                                        toastr.info(response.msg);
                                                        

                                                            $('#first_div').show();
                                                            $('#updt_users_form').hide().html('');
                                                             table.ajax.reload(null, false);
                                                    }
                                                    la.ladda('stop');
                                                }
                                                la.ladda('stop');
                                            }).fail(function (err) {
                                                la.ladda('stop');
                                                throw err;
                                            });
                                       
                                    });

                                }
                            }
                        }).fail(function (err) {
                            throw err;
                        });
                    }



                    });






                /*********end remap user parent*******/
                    /****activate user ***/
                $('#actvate_usr').click(function (e) {
                    e.preventDefault();
                    $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account: ' + showtd1.user_id + ')');
                    $("#first_div").show();
                    var acid = $(this).data('actvt_usr');
                    if (acid == showtd1.user_id) {
                        var dataString = {'usr_anctid': acid};
                        $.ajax({
                            url: 'Manage/UserActivate',
                            dataType: "json",
                            data: dataString,
                            type: 'post',
                            success: function (data) {
                               
                                if (data.error == 0) {

                                    toastr.success(data.msg);
                                    $('#mdl_usr_dsc').modal('hide');

                                } else {

                                    toastr.error(data.error_desc);

                                }
                            }///success function close       
                        });
                    }
                });
                $('#dctvt_usr').click(function (e) {
                    e.preventDefault();
                    $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account: ' + showtd1.user_id + ')');
                    $("#first_div").show();
                    var acid = $(this).data('dcvt_usr');
                    if (acid == showtd1.user_id) {
                        var dataString = {'usr_anctid': acid};
                        $.ajax({
                            url: 'Manage/UserDeactivate',
                            dataType: "json",
                            data: dataString,
                            type: 'post',
                            success: function (data) {
                                console.log(data);
                                if (data.error == 0) {

                                    toastr.success(data.msg);
                                    $('#mdl_usr_dsc').modal('hide');

                                } else {

                                    toastr.error(data.error_desc);

                                }
                            }///success function close       
                        });
                    }
                });
                /*** end activate user section ****/

                /*****Add Balance**********/
                var pyamnt_obj = {'IMPS': 'IMPS','NEFT': 'NEFT','Cash': 'Cash','OTHERS': 'OTHERS'};    
	
            $('#add_blnc_usr').click(function (e) {
            	
                $("#head_ttl").hide();
                $('#head_ttl2').show().html('Manage ' + showtd1.first_name + ' '+showtd1.last_name+' Balance');
                e.preventDefault();
                console.log('1111');
                $("#usr_blnc_form").show();
                $("#first_div").hide();
                var str = '<div class="row" id="mngblnc_form_div">';
                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                str += '<div class="panel-body">';
                str += '<form action="#" id="updt_users_blnc_form">';
                str += '<div class="row">';

                str += '<div class="col-md-12">';
              
                str += '<div class="form-group">';
                str += '<label>Amount</label>';
                str += '<input type="text" placeholder="Enter Amount" class="form-control amnt" name="add_blnc_amnt" id="add_blnc_amnt"><span data-for="add_blnc_amnt"></span>';
                str += '</div>';

                str += ' </div>';
                str += ' <div class="col-md-12">';
                 str += '<div class="form-group">';
                        var sel_amnt_typ = '';
                        str += '<label>Payment Mode</label>';
                        str += '<select class="form-control pymnt_mod" id="add_blnc_pymnt_mod">';
                        str += '<option>Select Mode</option>';

                        $.each(pyamnt_obj, function (k, v) {

                            str += '<option value="' + k + '" ' + sel_amnt_typ + '>' + v + '</option>';

                        })
                       
                        str += '</select><span data-for="add_blnc_pymnt_mod"></span>';
                        str += '</div>';
                str += '</div>';
            
                str += ' <div class="col-md-12">';
                 str += '<div class="form-group">';
                        str += '<label>Bank Reference</label>';
                        str += '<input type="text" placeholder="Enter Bank Reference" class="form-control bnk_ref" name="add_blnc_bnk_ref" id="add_blnc_bnk_ref"><span data-for="add_blnc_bnk_ref"></span>';
                        str += '</div>';
                str += '</div>';
                str += ' <div class="col-md-12">';
                  str += '<div class="form-group">';
                        str += '<label>Bank Narration</label>';
                        str += '<input type="text" placeholder="Enter Bank Narration" class="form-control bnk_nar" name="add_blnc_bnk_nar" id="add_blnc_bnk_nar"><span data-for="add_blnc_bnk_nar"></span>';
                        str += '</div>';
                str += ' </div>';

                str += ' <div class="col-md-12">';
                str += ' <div class="form-group"><label>Payment Due</label>';
                str += '<div class="input-group"><div class="switch-button switch-button-lg"><input type="checkbox" name="add_blnc_isdue" id="add_blnc_isdue" >';
                str += '<span><label for="add_blnc_isdue"></label></span>  </div></div></div>';
               
                 str += ' </div>';	

                str += '</div>';
                str += '<div class="modal-footer">';
                str += '<button type="submit" class="btn btn-secondary"  id="bck1_bnk">Back</button>';
                str += '<button type="submit" class="btn btn-brand legitRipple ladda-button"data-style="zoom-in" id="add_blnc">Proceed</button>';
                str += '</div>';
                str += '</div>';
                str += '</form>';
                str += '</div>';
                str += '</div>';
                str += '<div id="prcd_blnc_form" style="display:none;">';
                str += '</div>';
                $('#usr_blnc_form').html(str).show();
                	KeyPress_Validation();
                user_mng_balnc(showtd1);
                $('#bck1_bnk').click(function () {   
                    $('#head_ttl2').hide();
                    $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' '+ showtd1.last_name +' (Account:' + showtd1.user_id + ')');

                    $("#first_div").show();
                    $("#usr_blnc_form").hide().html('');
                });
            });

			    var user_mng_balnc = function (showtd1) {
			    	$('#add_blnc').click(function (e) {
			    		 e.preventDefault();
                       var params = {'valid': true};
                        var actid = $(this).attr('id');
                        params.amnt  = $('#' + actid + '_amnt').val();
                        params.py_amnt = $('#' + actid + '_pymnt_mod option:selected').val();
                        params.bnk_ref  = $('#' + actid + '_bnk_ref').val();
                        params.bnk_nar  = $('#' + actid + '_bnk_nar').val();
                        params.isdue = $('#' + actid + '_isdue').is(":checked"); 
					  
                        params.UserId = showtd1.user_id;
                          if (!validate({'id': '' + actid + '_amnt', 'type': 'AMOUNT', 'data': params.amnt, 'error': true, msg: $('#' + actid + '_amnt').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_pymnt_mod', 'type': 'PAYNENTMODE', 'data': params.py_amnt, 'error': true, msg: $('#' + actid + '_amnt').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_bnk_ref', 'type': 'DESC', 'data': params.bnk_ref, 'error': true, msg: $('#' + actid + '_bnk_ref').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_bnk_nar', 'type': 'DESC', 'data': params.bnk_nar, 'error': true, msg: $('#' + actid + '_bnk_nar').attr('placeholder')})) {
                            params.valid = false;
                        }

                        console.log(params)

			    	 if (params.valid == true) {
			    	 
			    	 	 // /***----payment proceed section-----***/
			    	 	

                        $("#first_div,#mngblnc_form_div").hide();

                      

                        var str = '<div class="row" id="prcd_form_div">';
                        str += '<div class="col-sm-12">';
                        str += '<div class="panel-body">';
                        str += '<form action="#" id="show_blnc_form">';
                        str += '<div class="row">';

                        str += '<div class="col-md-12">';
                        str += '<div class="form-group has-error">';
                        str += '<label>Amount</label>';
                        str += '<div class="input-group">';
                        str += '<input type="tel" class="form-control"value="' + params.amnt + '"  disabled>';
                        str += '</div>';
                        str += '</div>';
                        str += ' </div>';
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group has-error">';
                        str += ' <label>Payment Mode</label>';
                        str += '<div class="input-group">';
                        str += '<input type="tel" class="form-control"value="' + params.py_amnt + '" disabled>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group has-error">';

                        str += ' <label>Bank Reference</label>';
                        str += '<div class="input-group">';
                        str += '  <input type="text"class="form-control"value="' + params.bnk_ref + '" disabled>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        str += ' <div class="col-md-12">';
                        str += ' <div class="form-group has-error">';
                        str += '  <label>Bank Naration</label>';
                        str += ' <div class="input-group">';
                        str += ' <input type="text"class="form-control" value="' + params.bnk_nar + '" disabled>';
                        str += ' </div>';
                        str += ' </div>';
                        str += ' </div>';

                         var isdue=params.isdue===true?'Yes':'No';
						str+=' <div class="col-md-12">';
		                 str+=' <div class="form-group has-error">';
		                   str+=' <label>Payment Due</label>';
		                  str+='<div class="input-group">';
						  str+='<input type="text" class="form-control"value="'+isdue+'" disabled>';
		                  str+='</div>';
		                 str+='</div>';
		                str+='</div>';

                        str += '</div>';
                        str += '<div class="modal-footer">';
                        str += '<button type="submit" class="btn btn-secondary"  id="bck_bnk">Back</button>';
                        str += '<button type="submit" class="btn btn-brand legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt">Confirm</button>';
                        str += '</div>';

                        str += '</form>';
                        str += '</div>';
                        str += '</div>';
                        str += '</div>';
                        $('#prcd_blnc_form').html(str).show();

                        
                        $('#bck_bnk').click(function (e) {
                            e.preventDefault();
                            if (prcss === false) {

                                $("#mngblnc_form_div").show();
                                $("#show_blnc_form").hide().html('');
                            } else {

                                console.log('under process');
                            }
                        });
                           // /***----payment proceed section-----***/
                            /******proceed add balnc section ****/

                        $('#sucess_pymnt').click(function (e) {
                        e.preventDefault();
                         
                            if (prcss === false) {
                            prcss = true;
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');
                            $.ajax({
                                method: 'POST',
                                url: 'Manage/AddBalance',  
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                      prcss = false;
                                    if (response.error == 1)
                                    {
                                        
                                         toastr.error(response.error_desc);

                                    } else if (response.error == 2)
                                    {
                                        window.location.reload(true);

                                    } else if (response.error == 0) {
                                        
                                      
                                                            toastr.success(response.msg);

                                                            $('#head_ttl2').hide();
                                                            
															$('#head_ttl').show().html('Action User Details of ' + showtd1.first_name + ' '+ showtd1.last_name +' (Account:' + showtd1.user_id + ')');
                                                            $('#first_div').show();
                                                            $('#usr_blnc_form').hide().html('');

                                    }   
                                    //la.ladda('stop');
                                }
                               // la.ladda('stop');
                            }).fail(function (err) {
                                la.ladda('stop');
                                throw err;
                                 prcss = false;

                            });

                        }else{
                             console.log('please wait');
                        }


                        });
                        /*******end proceed add balance section***/

                      
			    	 }
			    	})
			    	
			    }


	/*****End Add Balance******/



          
              $('#dedct_blnc_usr').click(function (e) {
              	 e.preventDefault();
                    $("#head_ttl").hide();
                    $('#head_ttl2').show().html('Manage ' + showtd1.first_name + ' ' + showtd1.last_name + 'Balance');
                   
                    $("#usr_blnc_form").hide();
                    $("#usr_deduct_blnc_form").show();
                    $("#first_div").hide();
                    var str = '<div class="row" id="dect_blnc_form_div">';
                    str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    str += '<div class="panel-body">';
                    str += '<form action="#" id="deduct_users_blnc_form">';
                    str += '<div class="row">';

                    str += '<div class="col-md-12">';
                    str += '<div class="form-group">';
                    str += '<label>Amount</label>';
                    str += '<input type="text" placeholder="Enter Amount" class="form-control amnt" name="deduct_blnc_amnt" id="deduct_blnc_amnt"><span data-for="deduct_blnc_amnt"></span>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-12">';
                    str += '<div class="form-group">';
                    str += '<label>Bank Reference</label>';
                    str += '<input type="text" placeholder="Enter Bank Reference" class="form-control bnk_ref" name="deduct_blnc_bnk_ref" id="deduct_blnc_bnk_ref"><span data-for="deduct_blnc_bnk_ref"></span>';
                    str += '</div>';
                    str += ' </div>';
                    str += '</div>';
                    str += '<div class="modal-footer">';
                    str += '<button type="submit" class="btn btn-secondary"  id="bckdebt_bnk">Back</button>';
                    str += '<button type="submit" class="btn btn-brand legitRipple ladda-button"data-style="zoom-in" id="deduct_blnc">Proceed</button>';
                    str += '</div>';
                    str += '</div>';
                    str += '</form>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div id="prcd_deduct_blnc_form" style="display:none;">';
                    str += '</div>';
                    $('#usr_deduct_blnc_form').html(str).show();
                    	KeyPress_Validation();
                    deduct_user_balnc(showtd1);
                    $('#bckdebt_bnk').click(function () {
                        $('#head_ttl2').hide();
                      
                         $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account:' + showtd1.user_id + ')');
                        $("#first_div").show();
                        $("#usr_blnc_form").hide();
                        $("#usr_deduct_blnc_form").hide().html('');
                        ;
                    });
                });

                var deduct_user_balnc = function (showtd1) {
                	
                    $('#deduct_blnc').click(function (e) {
					  			  
                        e.preventDefault();
                        var params = {'valid': true};
                        var actid = $(this).attr('id');
                        params.amnt  = $('#' + actid + '_amnt').val();
                        params.bnk_ref  = $('#' + actid + '_bnk_ref').val();
                        params.UserId = showtd1.user_id;
                        if (!validate({'id': '' + actid + '_amnt', 'type': 'AMOUNT', 'data': params.amnt, 'error': true, msg: $('#' + actid + '_amnt').attr('placeholder')})) {
                            params.valid = false;
                        }
                        if (!validate({'id': '' + actid + '_bnk_ref', 'type': 'DESC', 'data': params.bnk_ref, 'error': true, msg: $('#' + actid + '_bnk_ref').attr('placeholder')})) {
                            params.valid = false;
                        }

                           console.log(params)

                        if (params.valid == true) {
                          	$("#first_div,#mngblnc_form_div,#dect_blnc_form_div").hide();
                        	var str = '<div class="row" id="prcd_dect_blnc_form_div">';
                            str += '<div class="col-sm-12">';
                            str += '<div class="panel-body">';
                            str += '<form action="#" id="show_deduct_blnc_form">';
                            str += '<div class="row">';

                            str += '<div class="col-md-12">';
                            str += '<div class="form-group has-error">';
                            str += '<label>Amount</label>';
                            str += '<div class="input-group">';
                            str += '<input type="tel" class="form-control"value="' + params.amnt + '"  disabled>';
                            str += '</div>';
                            str += '</div>';
                            str += ' </div>';
                         
                       

                            str += ' <div class="col-md-12">';
                            str += ' <div class="form-group has-error">';
                            str += '  <label>Remarks</label>';
                            str += ' <div class="input-group">';
                            str += ' <input type="text"class="form-control" value="' + params.bnk_ref + '" disabled>';
                            str += ' </div>';
                            str += ' </div>';
                                 str += '</div>';

                            str += ' </div>';
                               str += '<div class="modal-footer">';
                            str += '<button type="submit" class="btn btn-secondary"  id="deduct_bck_bnk">Back</button>';
                            str += '<button type="submit" class="btn btn-brand legitRipple ladda-button" data-style="zoom-in" id="sucess_pymnt_deduction">Confirm</button>';
                            str += '</div>';
                            str += '</div>';
                         

                            str += '</form>';
                            str += '</div>';
                            str += '</div>';
                            str += '</div>';
                            $('#prcd_deduct_blnc_form').html(str).show();
                            $('#deduct_bck_bnk').click(function (e) {
                                e.preventDefault();
                                if (cnfrm === false) {

                                    $('#head_ttl2').hide();
                                    $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account:' + showtd1.user_id + ')');
                                    $("#first_div").show();
                                    $("#usr_blnc_form").hide();
                                    $("#deduct_users_blnc_form").hide();
                                    $("#show_deduct_blnc_form").hide().html('');
                                } else {

                                    console.log('under process');
                                }
                            })

                              /******proceed deduct balnc section ****/

                        $('#sucess_pymnt_deduction').click(function (e) {
                        e.preventDefault();
                         
                            if (cnfrm === false) {
                            cnfrm = true;
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');
                            $.ajax({
                                method: 'POST',
                                url: 'Manage/deduct_usr_blnc_amnt',
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                      cnfrm = false;
                                    if (response.error == 1)
                                    {
                                        
                                         toastr.error(response.error_desc);

                                    } else if (response.error == 2)
                                    {
                                        window.location.reload(true);

                                    } else if (response.error == 0) {
                                        
                                           $('#head_ttl2').hide();
                                            $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account:' + showtd1.user_id + ')');

                                            $('#first_div').show();
                                            $('#prcd_deduct_blnc_form').hide();
                                            $('#deduct_users_blnc_form').hide().html('');
                                        table.ajax.reload();
                                    }   
                                    //la.ladda('stop');
                                }
                                //la.ladda('stop');
                            }).fail(function (err) {
                                la.ladda('stop');
                                throw err;
                                 cnfrm = false;

                            });

                        }else{
                             console.log('please wait');
                        }


                        });
                        /*******end proceed add balance section***/


                        }
                    });


                }

              /***************************************  Update User Details*****************************************/
               /*-----update user details *----*/
            var gen_obj = {'MALE': 'MALE', 'FEMALE': 'FEMALE'};
                    $('#updt_usr_dtl').click(function (e) {
                    e.preventDefault();
                    $('#head_ttl').show().html('Update ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account: ' + showtd1.user_id + ')');
                    $("#usr_updt_dtl_form").show();
                    $("#first_div").hide();
                    // console.log(usr_dtl);
                     var gstin = (showtd1.gstin != '' && showtd1.gstin != null) ? showtd1.gstin : '';
                    var str = '<div class="row" id="sec_form_div">';
                    str += '<div class="col-sm-12">';
                    str += '<div class="panel-body">';
                    str += '<form action="#" id="updt_users_form">';
                    str += '<div class="row">';
                    str += '<div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Role</label>';
                    str += '<input type="text" class="form-control" value="' + showtd1.role_name + '" disabled>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>First Name</label>';
                    str += '<input type="text" class="form-control FirstName" value="' + showtd1.first_name + '" placeholder="First Name" id="UpdateNewUser_FirstName">';
                    str += '<span data-for="UpdateNewUser_FirstName"></span>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Last Name</label>';
                    str += '<input type="text" class="form-control LastName" value="' + showtd1.last_name + '" placeholder="Last Name" id="UpdateNewUser_LastName">';
                    str += '<span data-for="UpdateNewUser_LastName"></span>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                  	str += '<div class="form-group">';
                    str += '<label>Mobile</label>';
                    str += '<input type="text" class="form-control Mobile" value="' + showtd1.mobile + '" placeholder="Mobile" id="UpdateNewUser_Mobile">';
                    str += '<span data-for="UpdateNewUser_Mobile"></span>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                  	str += '<div class="form-group">';
                    str += '<label>Email</label>';
                    str += '<input type="text" class="form-control Email" value="' + showtd1.email + '" placeholder="Email" id="UpdateNewUser_Email">';
                    str += '<span data-for="UpdateNewUser_Email"></span>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Date Of Birth</label>';
                    str += '<input type="text" class="form-control DateOfBirth date-picker" value="' + showtd1.date_of_birth + '" placeholder="Date Of Birth" id="UpdateNewUser_DateOfBirth">';
                    str += '<span data-for="UpdateNewUser_DateOfBirth"></span>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Pan</label>';
                    str += '<input type="text" class="form-control Pan" value="' + showtd1.pan + '" placeholder="Pan" id="UpdateNewUser_Pan">';
                    str += '<span data-for="UpdateNewUser_Pan"></span>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Aadhar</label>';
                    str += '<input type="text" class="form-control Aadhar" value="' + showtd1.aadhar + '" placeholder="Aadhar" id="UpdateNewUser_Aadhar">';
                    str += '<span data-for="UpdateNewUser_Aadhar"></span>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>GSTIN</label>';
                    str += '<input type="text" class="form-control GSTIN" value="' + gstin + '" placeholder="GSTIN" id="UpdateNewUser_GSTIN">';
                    str += '<span data-for="UpdateNewUser_GSTIN"></span>';
                    str += '</div>';
                    str += '</div>';
                    str += '<div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Business Name</label>';
                    str += '<input type="text" class="form-control BusinessName" value="' + showtd1.business_name + '" placeholder="Business Name" id="UpdateNewUser_BusinessName">';
                    str += '<span data-for="UpdateNewUser_BusinessName"></span>';
                    str += '</div>';
                    str += '</div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Business City</label>';
                    str += '<input type="text" class="form-control BusinessCity" value="' + showtd1.business_city + '" placeholder="Business City" id="UpdateNewUser_BusinessCity">';
                    str += '<span data-for="UpdateNewUser_BusinessCity"></span>';
                    str += '</div>';
                    str += ' </div>';
                      str += '<div class="col-md-6">';
                        str += '<div class="form-group">';
                        str += '<label>Business Pincode</label>';
                        str += '<input type="text" class="form-control BusinessPincode" value="' + showtd1.business_pincode + '" placeholder="Business Pincode" id="UpdateNewUser_BusinessPincode">';
                        str += '<span data-for="UpdateNewUser_BusinessPincode"></span>';
                        str += '</div>';
                        str += '</div>';
                  
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label> Business State</label>';
                    str += '<input type="text" class="form-control BusinessState" value="' + showtd1.business_state + '" placeholder="Business State" id="UpdateNewUser_BusinessState">';
                    str += '<span data-for="UpdateNewUser_BusinessState"></span>';
                    str += '</div>';
                    str += ' </div>';
                    str += ' <div class="col-md-6">';
                    str += '<div class="form-group">';
                    str += '<label>Registered Address</label>';
                    str += '<input type="text" class="form-control RegisteredAddress" value="' + showtd1.registered_address + '" placeholder="Registered Address" id="UpdateNewUser_RegisteredAddress">';
                    str += '<span data-for="UpdateNewUser_RegisteredAddress"></span>';
                    str += '</div>';
                    str += ' </div>';
                      str += ' <div class="col-md-12">';
                    str += '<div class="form-group">';
                    str += '<label>Business Address</label>';
                    str += '<input type="text" class="form-control BusinessAddress" value="' + showtd1.business_address + '" placeholder="Business Address" id="UpdateNewUser_BusinessAddress">';
                    str += '<span data-for="UpdateNewUser_BusinessAddress"></span>';
                    str += '</div>';
                   
                    str += ' </div>';
                    str += '</div>';
                    str += '<div class="modal-footer">';
                    str += '<button type="submit" class="btn btn-secondary"  id="updt_back_us">Back</button>';
                    str += '<button type="submit" class="btn btn-brand legitRipple ladda-button UpdateNewUser" data-style="zoom-in" id="UpdateNewUser">Update User</button>';
                    str += '</div>';
                    str += '</form>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    $('#usr_updt_dtl_form').html(str).show();
                      KeyPress_Validation();
                    profile_update();

                    $('#updt_back_us').click(function () {
                        $('#head_ttl').show().html('Manage: ' + showtd1.full_name + ' (Account:' + showtd1.accnt_id + ')');
                        $("#first_div").show();
                        $("#usr_updt_dtl_form").hide().html('');
                    });

                     $(".date-picker").datepicker({
                        // format: 'dd-mm-yyyy',
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        orientation:'bottom',
                        defaultViewDate: showtd1.date_of_birth,
                        endDate: '-18y',
                    });


                });

				var profile_update = function () {
					    $('#UpdateNewUser').click(function (e) {
					  
                        e.preventDefault();
                        var params = {'valid': true};
                        var actid = $(this).attr('id');
                        params.FirstName = $('#' + actid + '_FirstName').val();
                        params.LastName = $('#' + actid + '_LastName').val();
                        params.Mobile = $('#' + actid + '_Mobile').val();
                        params.Email = $('#' + actid + '_Email').val();
                        params.DateOfBirth = $('#' + actid + '_DateOfBirth').val();
                        params.Pan = $('#' + actid + '_Pan').val();
                        params.Aadhar = $('#' + actid + '_Aadhar').val();
                        params.GSTIN = $('#' + actid + '_GSTIN').val();
                        params.BusinessName = $('#' + actid + '_BusinessName').val();
                        params.BusinessAddress = $('#' + actid + '_BusinessAddress').val();
                        params.BusinessState = $('#' + actid + '_BusinessState').val();
                        params.BusinessCity = $('#' + actid + '_BusinessCity').val();
                        params.BusinessPincode = $('#' + actid + '_BusinessPincode').val();
                        params.RegisteredAddress = $('#' + actid + '_RegisteredAddress').val();
                        params.UserId = showtd1.user_id;
                        if (!validate({'id': '' + actid + '_FirstName', 'type': 'NAME', 'data': params.FirstName, 'error': true, msg: $('#' + actid + '_FirstName').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_LastName', 'type': 'NAME', 'data': params.LastName, 'error': true, msg: $('#' + actid + '_LastName').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_Mobile', 'type': 'MOBILE', 'data': params.Mobile, 'error': true, msg: $('#' + actid + '_Mobile').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_Email', 'type': 'EMAIL', 'data': params.Email, 'error': true, msg: $('#' + actid + '_Email').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_DateOfBirth', 'type': 'DATEOFBIRTH', 'data': params.DateOfBirth, 'error': true, msg: $('#' + actid + '_DateOfBirth').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (params.GSTIN != '') {
                            if (!validate({'id': '' + actid + '_GSTIN', 'type': 'GSTIN', 'data': params.GSTIN, 'error': true, msg: $('#' + actid + '_GSTIN').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }

                        if (!validate({'id': '' + actid + '_Pan', 'type': 'PAN', 'data': params.Pan, 'error': true, msg: $('#' + actid + '_Pan').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_Aadhar', 'type': 'AADHAR', 'data': params.Aadhar, 'error': true, msg: $('#' + actid + '_Aadhar').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_BusinessName', 'type': 'NAME', 'data': params.BusinessName, 'error': true, msg: $('#' + actid + '_BusinessName').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_BusinessAddress', 'type': 'DESC', 'data': params.BusinessAddress, 'error': true, msg: $('#' + actid + '_BusinessAddress').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_BusinessState', 'type': 'NAME', 'data': params.BusinessState, 'error': true, msg: $('#' + actid + '_BusinessState').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_BusinessCity', 'type': 'NAME', 'data': params.BusinessCity, 'error': true, msg: $('#' + actid + '_BusinessCity').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_BusinessPincode', 'type': 'PINCODE', 'data': params.BusinessPincode, 'error': true, msg: $('#' + actid + '_BusinessPincode').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_RegisteredAddress', 'type': 'DESC', 'data': params.RegisteredAddress, 'error': true, msg: $('#' + actid + '_RegisteredAddress').attr('placeholder')})) {
                            params.valid = false;
                        }

                           console.log(params)

                        if (params.valid == true) {
                            console.log(params)
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');
                            $.ajax({
                                method: 'POST',
                                url: 'Manage/UpdateNewUserDetails',
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                    if (response.error == 1)
                                    {
                                        
                                         toastr.error(response.error_desc);

                                    } else if (response.error == 2)
                                    {
                                        window.location.reload(true);

                                    } else if (response.error == 0) {
                                        
                                        
                                        toastr.success(response.msg);
                                        $('#mdl_usr_dsc').modal('hide');
                                    }
                                    //la.ladda('stop');
                                }
                                //la.ladda('stop');
                            }).fail(function (err) {
                                la.ladda('stop');
                                throw err;
                            });
                        }
                    });

					
					

				}
              




              /***************************************  Update User Details*****************************************/

                /****KYC documents******/
                $('#kyc_data').click(function (e) {
                    $('#lrge_modal').addClass('modal-lg');
                    $("#head_ttl").hide();
                    $('#head_ttl2').show().html('Manage ' + showtd1.first_name + ' ' + showtd1.last_name + ' KYC Documents');
                    e.preventDefault();
                    $('#kyc_dtl_documents_' + showtd1.user_id + '').show();
                    $("#first_div").hide();
                    e.preventDefault();
                    var acid = $(this).data('kyc_doc');
                    if (acid == showtd1.user_id) {
                        var str = '<div id="model_content_' + showtd1.user_id + '">';
                        str += ' <div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                        str += '</div>';
                        $('#kyc_dtl_documents_' + showtd1.user_id + '').html(str).show();
                        user_kyc_docs(showtd1);
                    }

                });
                var user_kyc_docs = function (showtd1) {
                    var document = {};
                    var documnt_array = {}
                    var loader ='<div class="timeline-wrapper m-t-md m-b-xxl " id="timeline-wrapper-pro"><div class="timeline-item m-b-xl" ><div class="animated-background"><div class="background-masker content-top"></div><div class="background-masker content-first-end"></div><div class="background-masker content-second-line"></div><div class="background-masker content-second-end"></div><div class="background-masker content-third-line"></div><div class="background-masker content-third-end"></div><div class="background-masker content-top-1"></div><div class="background-masker content-first-end-1"></div><div class="background-masker content-second-line-1"></div><div class="background-masker content-second-end-1"></div><div class="background-masker content-third-line-1"></div><div class="background-masker content-third-end-1"></div><div class="background-masker content-top-2"></div><div class="background-masker content-first-end-2"></div><div class="background-masker content-second-line-2"></div><div class="background-masker content-second-end-2"></div><div class="background-masker content-third-line-2"></div><div class="background-masker content-third-end-2"></div></div></div></div>';
                    $('#kyc_dtl_documents_' + showtd1.user_id + '').html(loader);
                    $.ajax({
                        url: 'Manage/get_user_docs',
                        dataType: "json",
                        type: 'post',
                        data: {data: showtd1.user_id},
                        success: function (data) {
                        	console.log(data)
                            if (data.error == 0) {
                                document = data.msg;
                             
                                var str = '<div class="row manage-blnc-users" style="">';
                                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                str += '<table  class="table table-striped- table-bordered table-hover table-checkable" id="transactiontable">';
                                str += ' <thead>';
                                str += '<tr>';
                                str += '<th>DocumentType</th>';
                                str += ' <th>Document</th>';
                                str += ' <th>Action</th>';
                                str += '</tr>';
                                str += '</thead>';
                                str += '<tbody>';
                                var i = 0;
                                $.each(document, function (k, v) {
                                    documnt_array[i] = v;
                                    console.log(v);
                                   
                                    str += '<tr>';
                                    str += ' <td>' + k + '</td>';
                                    var docdta;
                                    var dta = 'kyc_' + k + '';
                                    docdta = dta.replace(" ", "");
                                   
                                    if ($.isEmptyObject(v) === true) {
                                    	console.log('00000000')
                                        str += ' <td>Not Available</td>';
                                        str += '<td>';
                                        str += '<form id="' + docdta + '_' + showtd1.user_id + '_form"><div class="form-group">';
                                        str += '<label for="doc">' + k + ' :</label>';
                                        str += '<div class="input-group mb-3 file">';
                                        str += '<div class="input-group-prepend">';
                                        str += '<span class="input-group-text">Upload</span>';
                                        str += '</div>';
                                        str += '<div class="form-control custom-file">';

                                        str += '<input type="file" class="custom-file-input" id="' + docdta + '_' + showtd1.user_id + '" name="' + docdta + '_' + showtd1.user_id + '">';
                                        str += '<label class="custom-file-label"></label>';
                                        str += '</div><button type="submit" class="btn btn-space btn-primary btn-sm legitRipple ladda-button" data-style="zoom-in"  id="' + docdta + '_' + showtd1.user_id + '_sbmit" >Submit</button>';
                                        str += '</div>';
                                        str += '</div></form></td>';
                                    } else {
                                    	
                                        str += ' <td><a href="' + v.doc_path + '" class="success-btn" target="_blank">View</a></td>';
                                        if (v.status == "PENDING") {
                                            str += '<td><button class="success-btn apprv_doc legitRipple ladda-button" data-style="zoom-in" data-apprv="' + v.id + '" >Approve</button><button class="failed-btn reject_doc legitRipple ladda-button" data-style="zoom-in" data-reject="' + v.id + '" >Reject</button></td>';
                                        } else {
                                            str += ' <td><div class="chng_doc_apvrject">' + v.status + '  <button class="success-btn aprvd_rjctd_chng_doc legitRipple ladda-button" data-style="zoom-in" data-chng_doc="' + v.id + '" id="aprvd_rjctd_chng_' + docdta + '_' + showtd1.user_id + '" >Change</button></div></td>';
                                        }
                                    }
                                    str += '</tr> ';
                                    i++;
                                });
                            
                                str += ' </tbody>';
                                str += ' </table>';
                                str += '</div>';
                                str += '</div>';
                                str += '<div class="modal-footer"><button type="submit" class="btn btn-secondary" id="bckkyc_doc">Back</button></div>';

                                $('#kyc_dtl_documents_' + showtd1.user_id + '').html(str);
                                /***validate file****/
                                $('.aprvd_rjctd_chng_doc').click(function (e) {
                                    e.preventDefault();
                                    $(this).closest('.chng_doc_apvrject').hide();
                                    var chng_fl = $(this).attr('id');
                                    console.log(chng_fl);
                                    var doc_id = $(this).data('chng_doc');
                                    var row = $(this).closest('tr').index();
                                    if (documnt_array[row] != undefined) {
                                        if (documnt_array[row].id == doc_id) {
                                            var fl = '<form id="' + chng_fl + '_form"><div class="form-group">';
                                            //fl+='<label for="doc">file :</label>';
                                            fl += '<div class="input-group mb-3 file">';
                                            fl += '<div class="input-group-prepend">';
                                            fl += '<span class="input-group-text">Upload</span>';
                                            fl += '</div>';
                                            fl += '<div class="form-control custom-file">';

                                            fl += '<input type="file" class="custom-file-input" id="' + chng_fl + '_file" name="' + chng_fl + '_file">';
                                            fl += '<label class="custom-file-label"></label>';
                                            fl += '</div><button type="submit" class="btn btn-space btn-primary btn-sm legitRipple ladda-button" data-style="zoom-in" data-upd_chng_dc="' + doc_id + '"  id="' + chng_fl + '_btn" >Submit</button>   <button class="btn btn-secondary chng_aprvdoc" id="">Back</button>';
                                            fl += '</div>';
                                            fl += '</div></form>';
                                            if ($('#' + chng_fl + '_form').length > 0) {
                                                $('#' + chng_fl + '_form').remove();
                                            }
                                            $(this).closest('td').append(fl);
                                            $('.chng_aprvdoc').click(function (e) {
                                                e.preventDefault();
                                                $(this).closest('td').find('.chng_doc_apvrject').show();
                                                $(this).closest('#' + chng_fl + '_form').remove();

                                            });
                                            valid_chmg_doc(chng_fl, documnt_array, showtd1);
                                        } else {
                                            toastr.error("Invalid document");
                                        }
                                    } else {
                                        toastr.error("Unable to find document details");
                                    }
                                });

                                
                                $.each(document, function (k, v) {

                                    if ($.isEmptyObject(v) === true) {
                                        var docdta;
                                        var dta = 'kyc_' + k + '';
                                        docdta = dta.replace(" ", "");
                                      

                                        $('#' + docdta + '_' + showtd1.user_id + '').change(function () {
                                            var file = $('#' + docdta + '_' + showtd1.user_id + '')[0]['files'][0];
                                            console.log(file);
                                            if (file == undefined) {
                                                $(this).closest('.form-control').find('.custom-file-label').html('');
                                            } else {
                                                $(this).closest('.form-control').find('.custom-file-label').html("C:\\fakepath\\");
                                            }
                                        });
                                        var valid_obj = {
                                            errorElement: 'span', //default input error message container
                                            errorClass: 'help-block', // default input error message class
                                        }
                                        valid_obj['rules'] = {};
                                        valid_obj['rules']['' + docdta + '_' + showtd1.user_id + ''] = {
                                            required: true,
                                            accept: "jpg,png,jpeg,pdf",
                                        },
                                                valid_obj['messages'] = {};
                                        valid_obj['messages']['' + docdta + '_' + showtd1.user_id + ''] = {
                                            required: "Document is required",
                                            accept: "Only valid format is accepted",
                                        },
                                                valid_obj['invalidHandler'] = function (event, validator) { //display error alert on form submit
                                            $('.alert-danger', $('#' + docdta + '_' + showtd1.user_id + '_form')).show();
                                        }
                                        valid_obj['highlight'] = function (element) { // hightlight error inputs

                                            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                                        }
                                        valid_obj['success'] = function (label) {
                                            label.closest('.form-group').removeClass('has-error');
                                            console.log(label);
                                            label.remove();
                                        }

                                        valid_obj['errorPlacement'] = function (error, element) {

                                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                                        }
                                      

                                        valid_obj['submitHandler'] = function (form) {
                                            var row = $('#' + docdta + '_' + showtd1.user_id + '_sbmit').closest('tr').index();
                                            console.log(row);
                                            if (documnt_array[row] != undefined) {
                                                if (documnt_array[row].length == 0) {

                                                    var la = $('#' + docdta + '_' + showtd1.user_id + '_sbmit').ladda();
                                                    la.ladda('start');
                                                    console.log(la);
                                                    var file = $('#' + docdta + '_' + showtd1.user_id + '')[0]['files'][0];

                                                    console.log(file);

                                                    var data = new FormData();

                                                    data.append('file', file);
                                                    data.append('accntid', showtd1.user_id);
                                                    data.append('doctyp', k);


                                                    $.ajax({
                                                        url: 'Manage/UserKYCUploadFile',
                                                        type: 'POST',
                                                        data: data,
                                                        cache: false,  
                                                        dataType: 'json',
                                                        processData: false, // Don't process the files
                                                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                                        success: function (response)
                                                        {
                                                            if (response) {

                                                                if (response.error == 0)
                                                                {
                                                                    toastr.info(response.msg);
                                                                    $(form)[0].reset();

                                                                    $('#' + docdta + '_' + showtd1.user_id + '').hide().html('');
                                                                    user_kyc_docs(showtd1);
                                                                } else if (response.error == 2) {


                                                                    window.location.reload(true);


                                                                } else {
                                                                    toastr.error(response.error_desc);
                                                                    la.ladda('stop');
                                                                }

                                                            }

                                                        }, error: function (error) {
                                                            la.ladda('stop');
                                                            throw error;
                                                        }
                                                    });


                                                    return false;
                                                } else {
                                                    toastr.error("Invalid document");
                                                }
                                            } else {
                                                toastr.error("Unable to find document details");
                                            }
                                        }
                                        var val_form = $('#' + docdta + '_' + showtd1.user_id + '_form').validate(valid_obj);
                                    }
                                });
                                /***section validate file ***/
                                $('#bckkyc_doc').click(function () {
                                    $('#head_ttl2').hide();
                                    $('#head_ttl').show().html('Manage: ' + showtd1.first_name + ' ' + showtd1.last_name + ' (Account:' + showtd1.user_id + ')');
                                    $("#first_div").show();
                                    $('#kyc_dtl_documents_' + showtd1.user_id + '').hide();
                                    $('#kyc_dtl_documents_' + showtd1.user_id + '').hide().html('');
                                    $('#lrge_modal').removeClass('modal-lg');
                                    $('#kyc_dtl_documents_' + showtd1.user_id + '').hide();
                                });
                                // $('.apprv_doc').click(function (e) {
                                //     e.preventDefault();
                                //     var doc = $(this).data('apprv');
                                //     var row = $(this).closest('tr').index();
                                //     console.log(row);
                                //     console.log(documnt_array);
                                //     console.log(documnt_array[row].id);
                                //     console.log(doc);
                                //     if (documnt_array[row] != undefined) {
                                //         if (documnt_array[row].id == doc) {

                                //             var dataString = {'apprv_id': doc, 'acntid': showtd1.user_id};
                                //             $.ajax({
                                //                 url: 'Manage/approve_usr_document',
                                //                 dataType: "json",
                                //                 data: dataString,
                                //                 type: 'post',
                                //                 success: function (data) {
                                //                     console.log(data);
                                //                     if (data.error == 0) {

                                //                         toastr.success(data.msg);
                                //                         //pendg_actvtn_tbl.ajax.reload(null,false);
                                //                         user_kyc_docs(showtd1);
                                //                     } else {

                                //                         toastr.error(data.error_desc);

                                //                     }
                                //                 }///success function close       
                                //             });
                                //         } else {
                                //             toastr.error("Invalid document");
                                //         }
                                //     } else {
                                //         toastr.error("Unable to find document details");
                                //     }
                                // });
                                /*****reject document ********/
                                // $('.reject_doc').click(function (e) {
                                //     e.preventDefault();
                                //     var rjct_doc = $(this).data('reject');
                                //     var row = $(this).closest('tr').index();
                                //     console.log(row);
                                //     if (documnt_array[row] != undefined) {
                                //         if (documnt_array[row].id == rjct_doc) {
                                //             var dataString = {'rjct_doc_id': rjct_doc, 'acntid': showtd1.user_id};
                                //             $.ajax({
                                //                 url: 'Manage/reject_usr_document',
                                //                 dataType: "json",
                                //                 data: dataString,
                                //                 type: 'post',
                                //                 success: function (data) {
                                //                     console.log(data);
                                //                     if (data.error == 0) {

                                //                         toastr.success(data.msg);

                                //                         user_kyc_docs(showtd1);
                                //                     } else {

                                //                         toastr.error(data.error_desc);

                                //                     }
                                //                 }///success function close       
                                //             });
                                //         } else {
                                //             toastr.error("Invalid document");
                                //         }
                                //     } else {
                                //         toastr.error("Unable to find document details");
                                //     }
                                // });
                                /*****end reject document section*********/

                            }
                        }
                    });
                    var valid_chmg_doc = function (btnid, doc_array, acntid) {

                        var valid_obj = {
                            errorElement: 'span', //default input error message container
                            errorClass: 'help-block', // default input error message class
                        }
                        valid_obj['rules'] = {};
                        valid_obj['rules']['' + btnid + '_file'] = {
                            required: true,
                            accept: "jpg,png,jpeg,pdf",
                        },
                                valid_obj['messages'] = {};
                        valid_obj['messages']['' + btnid + '_file'] = {
                            required: "Document is required",
                            accept: "Only valid format is accepted",
                        },
                                valid_obj['invalidHandler'] = function (event, validator) { //display error alert on form submit
                            $('.alert-danger', $('#' + btnid + '_form')).show();
                        }
                        valid_obj['highlight'] = function (element) { // hightlight error inputs

                            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                        }
                        valid_obj['success'] = function (label) {
                            label.closest('.form-group').removeClass('has-error');
                            console.log(label);
                            label.remove();
                        }
                        valid_obj['errorPlacement'] = function (error, element) {

                            error.insertAfter(element.closest('.form-group').find('.input-group'));
                        }
                        console.log(valid_obj);
                        valid_obj['submitHandler'] = function (form) {
                            var row = $('#' + btnid + '_file').closest('tr').index();
                            var data_row = $('#' + btnid + '_btn').data('upd_chng_dc');
                         
                            if (doc_array[row].id != undefined) {
                                if (doc_array[row].id = data_row) {
                                    var la = $('#' + btnid + '_btn').ladda();
                                    la.ladda('start');

                                    var file = $('#' + btnid + '_file')[0]['files'][0];
                                    var data = new FormData();

                                    data.append('file', file);
                                    data.append('id', doc_array[row].id);
                                    data.append('accntid', showtd1.user_id);
                                    data.append('doctyp', doc_array[row].doc_name);


                                    $.ajax({
                                        url: 'Manage/UserKycChange',
                                        type: 'POST',
                                        data: data,
                                        cache: false,
                                        dataType: 'json',
                                        processData: false, // Don't process the files
                                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                        success: function (response)
                                        {
                                            if (response) {

                                                if (response.error == 0)
                                                {
                                                    toastr.info(response.msg);
                                                    $(form)[0].reset();

                                                 
                                                    user_kyc_docs(showtd1);
                                                } else if (response.error == 2) {


                                                    window.location.reload(true);


                                                } else {
                                                    toastr.error(response.error_desc);
                                                    la.ladda('stop');
                                                }

                                            }

                                        }, error: function (error) {
                                            la.ladda('stop');
                                            throw error;
                                        }
                                    });


                                    return false;
                                } else {
                                    toastr.error("Invalid document");
                                }
                            } else {
                                toastr.error("Unable to find document details");
                            }
                        }
                        var val_form = $('#' + btnid + '_form').validate(valid_obj);
                    }

                }

                /****KYC documents section end******/
              
            }  

        })////User_Edit click

				
			} /// var ConfigUser  function End///



			  var KeyPress_Validation = function () {
            $(".FirstName,.LastName,.BusinessName,.BusinessAddress,.BusinessState,.BusinessCity,.RegisteredAddress").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Name);
                if (regacc.test(str))
                {
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
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = regacc;
                        if (!mb_regex.test(k))
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
                            helpBlck({'id': id, 'msg': 'Invalid ' + msg, 'type': 'error'});
                        } else {
                            helpBlck({id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({'id': id, 'msg': msg + ' Is Required', 'type': 'error'});
                    }
                }
            });
            $(".bnk_ref,.bnk_nar").on('keypress blur keyup keydown', function (e) {   
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');   
                var regacc = new RegExp(Regex.Text);
                if (regacc.test(str))
                {
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
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = regacc;
                        if (!mb_regex.test(k))
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
                            helpBlck({'id': id, 'msg': 'Invalid ' + msg, 'type': 'error'});
                        } else {
                            helpBlck({id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({'id': id, 'msg': msg + ' Is Required', 'type': 'error'});
                    }
                }
            });
            $(".Mobile,.HelplineNumber").on('keypress blur keyup keydown', function (e) {
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
            $(".Email").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Email.Full);
                var newregex = new RegExp(Regex.Email.Allowed);
                var extmsg = (id == 'user_add_continue_Partyemail') ? 'Party ' : '';
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
                        if (length == 80) {
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
                                id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({
                            'id': id, 'msg': msg + ' Is Required',
                            'type': 'error'
                        });
                    }
                }
            });
            $(".GSTIN").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.GSTTIN.Full);
                var newregex = new RegExp(Regex.GSTTIN.Allowed);
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
                        if (length == 15) {
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
                            id: id, 'action': 'remove'
                        })
                    }
                }
            });
            $(".Pan").on('keypress blur keyup keydown', function (e) {
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
                        helpBlck({
                            id: id, 'action': 'remove'
                        });
                    }
                }
            });

                $(".Aadhar").on('keypress blur keyup keydown', function (e) {
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
            $(".BusinessPincode").on('keypress blur keyup keydown', function (e) {
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
                if (e.type == 'keyup') {
                    var state = '';
                    $('#UpdateNewUser_BusinessState').val('');
                    if (length == 6) {
                        $('#UpdateNewUser_BusinessState').val('');
                        $.post('Manage/getpininfo', {data: str}, function (response) {
                            if (response) {
                                if (response.error_data == 0) {
                                    state = response.data.statename;
                                    var Districtname = response.data.Districtname;
                                    $('#UpdateNewUser_BusinessState').val(Districtname + ', ' + state.replace(/\*/gi, ''));

                                } else if (response.error_data == 2) {
                                    window.location.reload(true);
                                } else {
                                    console.log(response.error_msg);
                                }
                            }
                        }, 'json').fail(function (error) {
                            throw error;
                        });
                        return;
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
            $('.Plan').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();
                if (val == '') {
                    helpBlck({id: id, 'action': 'remove'});
                }

                if (val in plan)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Plan', 'type': 'error'});
                }
            });
            $(".amnt").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Rate);
                var newregex = new RegExp(Regex.Rate);
                var extmsg = (id == 'user_add_continue_Partyemail') ? 'Party ' : '';

                if (str == '') {
                    helpBlck({
                        id: id, 'action': 'remove'
                    });
                }

                if (k == 8) {
                    if (!newregex.test(str)) {
                        error_msg = (str == '') ? msg + ' Is Required' : 'Invalid ' + msg;
                        helpBlck({
                            'id': id, 'msg': error_msg, 'type': 'error'
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
                        if (length == 80) {
                            return !1
                        }
                    }
                    return !0
                } else
                if (e.type == 'blur') {
                    if (str != '') {
                        if (!regacc.test(str)) {
                            helpBlck({
                                'id': id, 'msg': 'Invalid ' + msg, 'type': 'error'
                            });
                        } else {
                            helpBlck({
                                id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({
                            'id': id, 'msg': msg + ' Is Required', 'type': 'error'
                        });
                    }
                }
            });
            $('.PartyList').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                if (val in ChildsList)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Party', 'type': 'error'});
                }
            });
            $('.Bank').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                if (val in AdminBank)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Bank', 'type': 'error'});
                }
            });
            $('.pymnt_mod').change(function () {
                var mode = {'IMPS': 'IMPS', 'NEFT': 'NEFT', 'Cash': 'Cash', 'OTHERS': 'OTHERS'};
                var id = $(this).attr('id');
                var val = $(this).val();
                if (val in mode)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Mode', 'type': 'error'});
                }
            });
            $(".amnt").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Rate);
                var newregex = new RegExp(Regex.Rate);
                var extmsg = (id == 'user_add_continue_Partyemail') ? 'Party ' : '';

                if (str == '') {
                    helpBlck({
                        id: id, 'action': 'remove'
                    });
                }

                if (k == 8) {
                    if (!newregex.test(str)) {
                        error_msg = (str == '') ? helpBlck({id: id, 'action': 'remove'}) : 'Invalid ' + msg;
                        helpBlck({
                            'id': id, 'msg': error_msg, 'type': 'error'
                        });
                    } else {
                        helpBlck({id: id, 'action': 'remove'});
                    }
                }
                if (e.type == 'keypress') {
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = newregex;
                        if (!mb_regex.test(k)) {
                            return !1
                        }
                    }
                    return !0
                } else
                if (e.type == 'blur') {
                    if (str != '') {
                        if (!regacc.test(str)) {
                            helpBlck({
                                'id': id, 'msg': 'Invalid ' + msg, 'type': 'error'
                            });
                        } else {
                            helpBlck({
                                id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({
                            id: id, 'action': 'remove'});
                    }
                }
            });
            $('.RoleUserList').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();
                if (val in RemapRole)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Role User List', 'type': 'error'});
                }
            });
            $('.Role').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();
                if (val in role)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Role', 'type': 'error'});
                }
            });
        }

        //---------------------------------------Validate-----------------------------// 
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
            } else if (p.type == 'MOBILE') {
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
            } else if (p.type == 'EMAIL') {
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
            } else if (p.type == 'DATEOFBIRTH') {
                var _identifier_regex = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
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
            } else if (p.type == 'GSTIN') {
                var _identifier_regex = Regex.GSTTIN.Full;
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
            } else if (p.type == 'PAN') {
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
            }else if (p.type == 'AADHAR') {
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
            } else if (p.type == 'DOMAIN') {
                var _identifier_regex = Regex.Domain.Full;
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
            } else if (p.type == 'LINK') {
                var _identifier_regex = Regex.Url.Full;
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
            } else if (p.type == 'COLOR') {
                var _identifier_regex = Regex.Color;
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
            } else if (p.type == 'DESC') {
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
            } else if (p.type == 'ICON') {
                if (p.data != "" && p.data in serviceIconObj)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Service Icon', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Service Icon', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'PLAN') {
                if (p.data != "" && p.data in PlanService)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Plan', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Plan', 'type': 'error'});
                        }
                    }
                }
            }else if (p.type == 'AMOUNT') {
                var _identifier_regex = Regex.Amount;
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
            }else if (p.type == 'PAYNENTMODE') {
                if (p.data != "" && p.data in Regex.PAYNENTMODE)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Mode Type', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Mode Type', 'type': 'error'});
                        }
                    }
                }
              }else if (p.type == 'DESC') {
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
            }  else if (p.type == 'REMAP') {
                if (p.data != "" && p.data in RemapRole)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Role User List', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Role User List', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'ROLE') {
                if (p.data != "" && p.data in role)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Role', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Role', 'type': 'error'});
                        }
                    }
                }
            }
            return false;
        }

        //--------------------------------------- Validate Error Show Helpblock -------------------// 
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
	   return {
            init: function () {
                ConfigUser();
            }
        };

    }();

    $(document).ready(function () {
        USERLIST.init();
    });
</script>

<script>

	$(function() {
	 	   $("#AddUserNew_DateOfBirth").datepicker({
	        format: 'dd-mm-yyyy',
	        autoclose: true,

	            endDate: '-18y',
	            orientation:'bottom'
	        });
		    $('#AddUserNew_TwoFactor').bootstrapToggle({
		      on: 'ON',
		      off: 'OFF'
		    });

		     $('#AddUserNew_IsActive').bootstrapToggle({
		      on: 'Active',
		      off: 'Inactive'
		    });

		     $('#isdue').bootstrapToggle({
		      on: 'Yes',
		      off: 'No'
		    });
	})

    var CreateUsr_intilize=function(){

       var Regex = <?php echo json_encode($Regex); ?>;  
        var plan = {};
      
        var role = {};


            var FetchRole = function () {
            $.ajax({
                method: 'POST',
                url: 'Manage/get_roles',
                dataType: 'JSON'
            }).done(function (response) {
                if (response)
                {
                  
                    if (response.error == 1)
                    {
                        
                        toastr.error(response.error_desc);  

                    } else if (response.error == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error == 0) {
                        role = {};
                        var str = '';
                        str += '<option value="">Select Role</option>';

                        $.each(response.msg, function (k, v) {
                            role[v.role_id] = v;
                            str += '<option value="' + v.role_id + '">' + v.role_name + '</option>';
                        });

                        $('#AddUserNew_Role').html(str);
                         $('#AddUserNew_Role').on('change', function (e) {
                            e.preventDefault();
                            FetchPlan($(this).val());
                        })

                       
                    }
                }
            }).fail(function (err) {
                throw err;
            });
        }

           var FetchPlan = function (role_id) {
            $.ajax({
                method: 'POST',
                url: 'Manage/Fetch_pln_frUser',
                dataType: 'JSON',
                data: {'roleId': role_id},
            }).done(function (response) {
                if (response)
                {
                     // console.log(response)
                    if (response.error_data == 1)
                    {
                        

                        toastr.error(response.error_desc);

                    } else if (response.error_data == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error_data == 0) {
                        plan = {};
                        var str = '';
                        str += '<option value="">Select Plan</option>';
                        $.each(response.data, function (k, v) {
                            plan[v.plan_id] = v;
                            str += '<option value="' + v.plan_id + '">' + v.plan_name + ' (' + v.plan_code + ')</option>';
                        });

                        $('#AddUserNew_Plan').html(str);
                    }
                }
            }).fail(function (err) {
                throw err;
            });
        }

      var gndr_typ={'Male': 'Male','Female': 'Female'};

   var KeyPress_Validation = function () {
         $("#AddUserNew_Pincode").on('keypress blur keyup keydown', function (e) {
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

                 if (e.type == 'keyup') {
                     var state = '';
                     $('#AddUserNew_BusinessState').val('');
                     if (length == 6) {
                         $('#AddUserNew_BusinessState').val('');
                         $.post('Manage/getpininfo', {data: str}, function (response) {
                            if (response) {
                                 if (response.error_data == 0) {
                                     state = response.data.statename;
                                     $('#AddUserNew_BusinessState').val(state.replace(/\*/gi, ''));

                                 } else if (response.error_data == 2) {
                                     window.location.reload(true);
                                 } else {
                                    console.log(response.error_msg);
                                 }
                             }
                         }, 'json').fail(function (error) {
                             throw error;
                         });
                         return;
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

             $("#AddUserNew_Mobile").on('keypress blur keyup keydown', function (e) {
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


               $("#AddUserNew_FirstName,#AddUserNew_LastName,#AddUserNew_BusinessState,#AddUserNew_BusinessCity").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Name);
                if (regacc.test(str))
                {
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
                    if (k != 8 && k != 9) {
                        k = String.fromCharCode(k);
                        var mb_regex = regacc;
                        if (!mb_regex.test(k))
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
                            helpBlck({'id': id, 'msg': 'Invalid ' + msg, 'type': 'error'});
                        } else {
                            helpBlck({id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({'id': id, 'msg': msg + ' Is Required', 'type': 'error'});
                    }
                }
            });

             $("#AddUserNew_Email").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Email.Full);
                var newregex = new RegExp(Regex.Email.Allowed);
                var extmsg = (id == 'user_add_continue_Partyemail') ? 'Party ' : '';
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
                        if (length == 80) {
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
                                id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({
                            'id': id, 'msg': msg + ' Is Required',
                            'type': 'error'
                        });
                    }
                }
            });
            $("#AddUserNew_GSTIN").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.GSTTIN.Full);
                var newregex = new RegExp(Regex.GSTTIN.Allowed);
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
                        if (length == 15) {
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
                            id: id, 'action': 'remove'
                        })
                    }
                }
            });
            $("#AddUserNew_Pan").on('keypress blur keyup keydown', function (e) {
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


             $("#AddUserNew_Aadhar").on('keypress blur keyup keydown', function (e) {
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

          
       
     
       $('.Plan').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                if (val == '') {
                    helpBlck({id: id, 'action': 'remove'});
                }

                if (val in plan)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Plan', 'type': 'error'});
                }
            });
            $('#AddUserNew_Role').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                if (val == '') {
                    helpBlck({id: id, 'action': 'remove'});
                }

                if (val in role)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Role', 'type': 'error'});
                }
            });  


               }
     


       

        var CreateUsr= function(){
          
           $('#AddUserNew').click(function (e) {
            e.preventDefault();
            var params = {'valid': true};
            var actid = $(this).attr('id');
           
            var file1 = $('#' + actid + '_IDProof')[0]['files'][0];
            var file2 = $('#' + actid + '_AddressProof')[0]['files'][0];
            var file3 = $('#' + actid + '_PhotoProof')[0]['files'][0];
            var data = new FormData();

            params.Role = $('#' + actid + '_Role option:selected').val();
            <?php if($userDetails['role_id']==1){ ?>

         
                params.Plan = $('#' + actid + '_Plan option:selected').val();
          
            <?php } ?>

            params.FirstName = $('#' + actid + '_FirstName').val();
            params.LastName = $('#' + actid + '_LastName').val();
            params.Mobile = $('#' + actid + '_Mobile').val();
            params.Email = $('#' + actid + '_Email').val();
            params.DateOfBirth = $('#' + actid + '_DateOfBirth').val();
        
            params.GSTIN = $('#' + actid + '_GSTIN').val();
            params.Pan = $('#' + actid + '_Pan').val();
            params.Aadhar = $('#' + actid + '_Aadhar').val();
            params.BusinessName = $('#' + actid + '_BusinessName').val();
            params.BusinessAddress = $('#' + actid + '_BusinessAddress').val();
            params.BusinessState = $('#' + actid + '_BusinessState').val();
            params.BusinessCity = $('#' + actid + '_BusinessCity').val();
            params.Pincode = $('#' + actid + '_Pincode').val();
            params.RegisteredAddress = $('#' + actid + '_RegisteredAddress').val();
            params.IsActive = $('#' + actid + '_IsActive').is(":checked");
            params.TwoFactor = $('#' + actid + '_TwoFactor').is(":checked");
            console.log(params)
            if (!validate({'id': '' + actid + '_Role', 'type': 'ROLE', 'data': params.Role, 'error': true, msg: $('#' + actid + '_Role').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_FirstName', 'type': 'NAME', 'data': params.FirstName, 'error': true, msg: $('#' + actid + '_FirstName').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_LastName', 'type': 'NAME', 'data': params.LastName, 'error': true, msg: $('#' + actid + '_LastName').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_Mobile', 'type': 'MOBILE', 'data': params.Mobile, 'error': true, msg: $('#' + actid + '_Mobile').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_Email', 'type': 'EMAIL', 'data': params.Email, 'error': true, msg: $('#' + actid + '_Email').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_DateOfBirth', 'type': 'DATEOFBIRTH', 'data': params.DateOfBirth, 'error': true, msg: $('#' + actid + '_DateOfBirth').attr('placeholder')})) {
                params.valid = false;
            }
            if (params.GSTIN != '') {
                if (!validate({'id': '' + actid + '_GSTIN', 'type': 'GSTIN', 'data': params.GSTIN, 'error': true, msg: $('#' + actid + '_GSTIN').attr('placeholder')})) {
                    params.valid = false;
                }
            } 
              if (!validate({'id': '' + actid + '_Pan', 'type': 'PAN', 'data': params.Pan, 'error': true, msg: $('#' + actid + '_Pan').attr('placeholder')})) {
                params.valid = false;
            } 

            if (!validate({'id': '' + actid + '_Aadhar', 'type': 'AADHAR', 'data': params.Aadhar, 'error': true, msg: $('#' + actid + '_Aadhar').attr('placeholder')})) {
                params.valid = false;
            } 

            <?php if($userDetails['role_id']==1){ ?>
          
                             if (!validate({'id': '' + actid + '_Plan', 'type': 'PLAN', 'data': params.Plan, 'error': true, msg: $('#' + actid + '_Plan').attr('placeholder')})) {
                            params.valid = false;
                       }
            
            <?php } ?>

            if (!validate({'id': '' + actid + '_BusinessName', 'type': 'ADDRESS', 'data': params.BusinessName, 'error': true, msg: $('#' + actid + '_BusinessName').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_BusinessAddress', 'type': 'ADDRESS', 'data': params.BusinessAddress, 'error': true, msg: $('#' + actid + '_BusinessAddress').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_BusinessState', 'type': 'NAME', 'data': params.BusinessState, 'error': true, msg: $('#' + actid + '_BusinessState').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_BusinessCity', 'type': 'NAME', 'data': params.BusinessCity, 'error': true, msg: $('#' + actid + '_BusinessCity').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_Pincode', 'type': 'PINCODE', 'data': params.Pincode, 'error': true, msg: $('#' + actid + '_Pincode').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_RegisteredAddress', 'type': 'ADDRESS', 'data': params.RegisteredAddress, 'error': true, msg: $('#' + actid + '_RegisteredAddress').attr('placeholder')})) {
                params.valid = false;
            }

             console.log(params)
  
            if (params.valid == true) {
                $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                la.ladda('start');   
                console.log(params)
                data.append('IDProof', file1);
                data.append('AddressProof', file2);
                data.append('PhotoProof', file3);
                data.append('Role', params.Role);
                 <?php #} ?>
                data.append('FirstName', params.FirstName);
                data.append('LastName', params.LastName);
                data.append('Mobile', params.Mobile);
                data.append('Email', params.Email);
                data.append('DateOfBirth', params.DateOfBirth);
                data.append('Plan', params.Plan);
                data.append('GSTIN', params.GSTIN);
                data.append('Pan', params.Pan);
                 data.append('Aadhar', params.Aadhar);
                data.append('BusinessName', params.BusinessName);
                data.append('BusinessAddress', params.BusinessAddress);
                data.append('BusinessState', params.BusinessState);
                data.append('BusinessCity', params.BusinessCity);
                data.append('Pincode', params.Pincode);
                data.append('RegisteredAddress', params.RegisteredAddress);
                data.append('IsActive', params.IsActive);
                data.append('TwoFactor', params.TwoFactor);

                $.ajax({
                    method: 'POST',
                    url: 'Manage/InsertNewUserUser',
                    data: data,
                    dataType: 'JSON',
                    cache: false,
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                }).done(function (response) {

                    if (response)
                    {
                        if (response.error == 1)
                        {
                           
                            toastr.error(response.error_desc);
                            la.ladda('stop');
                        } else if (response.error == 2)
                        {
                            window.location.reload(true);
                        } else if (response.error == 0) {
                           
                        
                            toastr.success(response.msg);

                             $('#create_usr')[0].reset();
                             $('input[type="file"]').closest('.uploader').find('span.filename').html('No file selected');
                    		 la.ladda('stop');
                          
                        }
                        
                    }
                    la.ladda('stop');
                });
            }
        });
}

   // KeyPress_Validation();


     
            //---------------------------------------Validate-----------------------------// 
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
            } else if (p.type == 'MOBILE') {
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
            } else if (p.type == 'EMAIL') {
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
            } else if (p.type == 'DATEOFBIRTH') {
                var _identifier_regex = Regex.DateOfBirth;
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
            } else if (p.type == 'GSTIN') {
                var _identifier_regex = Regex.GSTTIN.Full;
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
            } else if (p.type == 'PAN') {
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
            } else if (p.type == 'PINCODE') {
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
            } else if (p.type == 'DESC') {
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
            } else if (p.type == 'PLAN') {
                if (p.data != "" && p.data in plan)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Plan', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Plan', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'ROLE') {
                if (p.data != "" && p.data in role)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Role', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Role', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'AADHAR') {
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
            }else if(p.type == "ADDRESS")
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

        //--------------------------------------- Validate Error Show Helpblock -------------------// 
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
  

   return {
      init:function(){
          CreateUsr();
           FetchRole();
           KeyPress_Validation();

           
      }
    };
}();
$(document).ready(function(){
    CreateUsr_intilize.init();
})

</script>  
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
</body>
</html>
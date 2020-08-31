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
.form-row{
	display: block ruby;
}
</style>			

				<div class="col-lg-10">
				<div class="tab-content">

					<div class="width-100 tab-pane fade show active" id="users">

						<nav>
						  <div class="nav nav-tabs" id="nav-tab" role="tablist">
						    <a class="nav-item nav-link active col text-center" data-toggle="tab" href="#notification" role="tab"  aria-selected="true">NOTIFICATION</a>
						    <a class="nav-item nav-link col text-center"  data-toggle="tab" href="#event" role="tab"  aria-selected="false">EVENT</a>
						  </div>
						</nav>
						<div class="tab-content mt-20" id="nav-tabContent">
						  <div class="tab-pane fade show active table-responsive" id="notification" role="tabpanel" >
						  	<!-- <table class="table datatables">
						  		<thead class="thead-blue">
						  			
						  		</thead>
						  	</table> -->
						  </div>

						  <div class="tab-pane fade active table-responsive" id="event" role="tabpanel" >
						  	<!-- <table class="table datatables">
						  		<thead class="thead-blue">
						  			
						  		</thead>
						  	</table> -->
						  </div>

						  <!-- <div class="tab-pane fade" id="event" role="tabpanel"> -->
						  	<!-- <div class="width-100 section-top-subheading mb-3 bord-bottom"> -->
						  		<!-- <table class="table datatables">
						  		<thead class="thead-blue">
						  			
						  		</thead>
						  	</table> -->

						  <!-- </div> -->
						<!-- </div> -->
						<div class="table-responsive">
							<table class="table datatables">
						  		<thead class="thead-blue">
						  			
						  		</thead>
						  	</table>
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
	var USERLIST = function () {
	    var Regex = <?php echo json_encode($Regex); ?>;
	    var prcss = false;
        var cnfrm = false;
        var role={};
        var updt_plan_list={};
        var RemapRole = {};

        var addMode = 'notification';

    	var allroles = <?php echo json_encode($allroles);?>;

    	// console.log(allroles);

        	var notificationModelHtml = function(fetchdata){
				var heading = '', content = '', valid_upto = '', notif_for = '';
				var btnaddmode = '';
				if(typeof fetchdata != 'undefined'){
					heading = fetchdata['heading'];
					content = fetchdata['content'];
					valid_upto = fetchdata['valid_upto'];
				}else {
					btnaddmode = 'add-';
				}

//<option value="1">ADMIN</option>
//<option value="2">DISTRIBUTOR</option>
//<option value="3">RETAILER</option>

		var str =  `<div id="usr_updt_dtl_form" style="">
					<div class="row" id="sec_form_div">
						<div class="col-sm-12">
							<div class="panel-body">
								<form action="#" class="enotification-form" id="enotification-form">
                                    <fieldset>
                                     
                                    <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Heading</label>
                                                    <input type="text" placeholder="Enter your Heading" name="eheading" id="eheading" class="form-control" value="`+ heading +`" required="required">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Content</label>
                                                    <input type="text" placeholder="Enter Your Content" name="econtent" id="econtent" class="form-control" value="`+ content +`" required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Valid Upto</label>
                                                    <input type="text" placeholder="Valid Upto" name="evalidupto" id="evalidupto" class="form-control" required="required" value="`+valid_upto+`" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Notification For</label>
                                                    <select data-placeholder="Notification for" name="enotifor" id="enotifor" class="select" required="required">
                                                        <option value="0">ALL ROLES</option>`;
                                                        $.each(allroles, function(k, rname){
                                                        	str += `<option value="`+rname['role_id']+`">`+rname['role_name']+`</option>`;
                                                        });
                                                    str += `</select>                                                      		
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset>
                                    <div class="text-right">
                                        <button type="reset" class="btn btn-danger  legitRipple" id="`+btnaddmode+`enotisub_reset">Reset form<i class="icon-reload-alt position-right"></i></button>
                                        <button type="submit" class="btn btn-primary legitRipple" id="`+btnaddmode+`enotisub">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
							</div>
						</div>
					</div>
				</div>`;

				return str;
		}

		var eventModelHtml = function(){
			var str =  `<div id="usr_updt_dtl_form" style="">
					<div class="row" id="sec_form_div">
						<div class="col-sm-12">
							<div class="panel-body">
								<form action="#" class="enotification-form" id="enotification-form">
                                    <fieldset>
                                     
                                    	<div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Valid Upto</label>
                                                    <input type="text" placeholder="Valid Upto" name="evalidupto" id="evalidupto" class="form-control" required="required" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Content</label>
                                                    <input type="file" placeholder="select file" name="econtent" id="econtent" class="form-control"  required="required">
                                                    <span class="help-block">please upload only jpg, jpeg, png, gif.</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <fieldset>
                                    <div class="text-right">
                                        <button type="reset" class="btn btn-danger  legitRipple" id="file-add-enotisub_reset">Reset form<i class="icon-reload-alt position-right"></i></button>
                                        <button type="submit" class="btn btn-primary legitRipple" id="file-add-enotisub">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
							</div>
						</div>
					</div>
				</div>`;

				return str;

		}

        //    var FetchPlanFrUpdate = function (role_id, planId) {
        //     $.ajax({
        //         method: 'POST',
        //         url: 'Manage/Fetch_pln_frUser',
        //         dataType: 'JSON',
        //         data: {'roleId': role_id},
        //     }).done(function (response) {
        //         if (response)
        //         {
                      
        //             if (response.error_data == 1)
        //             {
                        
        //                 toastr.error(response.error_desc);

        //             } else if (response.error_data == 2)
        //             {
        //                 window.location.reload(true);
        //             } else if (response.error_data == 0) {
                      
        //                 var str = '';
        //                 str += '<option value="">Select Plan</option>';
        //                 $.each(response.data, function (k, v) {
        //                     updt_plan_list[v.plan_id] = v;
        //                     console.log(v.plan_id)
        //                     var pln = (planId == v.plan_id) ? 'selected' : '';
        //                     str += '<option value="' + v.plan_id + '" ' + pln + '>' + v.plan_name + ' (' + v.plan_code + ')</option>';
        //                 });

        //                 $('#UpdateUserPlan').html(str);
        //             }
        //         }
        //     }).fail(function (err) {
        //         throw err;
        //     });
        // }

    	

	  var ConfigUser = function () {

	  	  var table = $('.datatables').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/notification_list",
                    type: 'post',
                    "dataSrc": function (json) {
                    
                        if (json.error_data == 2){
                            window.location.reload(true);
                        } else if (json.error_data == 1){
                            toastr.error(json.error_desc, 'Oops!');
                        }
                        return json.data;
                    }
                },
                responsive: true,
                order: [],
                columns: [
        		 	{"title" : "Heading", "data": "heading",},
            		{"title" : "Created on", "data": "created_on",},

            		{"title": "Content", data: "content", class: "none compact",
                        render: function (data, type, full, meta) {
                            return full.content;
                        }
                    },

                    {"title": "Valid upto", data: "valid_upto", class: "none compact",
                        render: function (data, type, full, meta) {
                            return full.valid_upto;
                        }
                    },
                    //class: 'all compact' class: 'none compact',
            		{"title" : "Display on", data: "notif_for", 
            		 	render: function (data, type, full, meta) {
                            var status = "";
                            if (full.notif_for == 1) {
                                status = '<span class="label label-sm label-success" data-color=""> ADMIN </span>';
                            } else if (full.notif_for == 2) {
                                status = '<span class="label label-sm label-danger" data-color="text-danger"> DISTRIBUTOR </span>';
                            } else if (full.notif_for == 3) {
                                status = '<span class="label label-sm label-success" data-color="text-danger"> RETAILER </span>';
                            } else if (full.notif_for == 0) {
                                status = '<span class="label label-sm label-success" data-color="text-danger"> ALL </span>';
                            } else {
                                status = '<span class="label label-sm label-success" data-color="text-danger"> INVALID STATUS </span>';
                            }

                            return status;
                        }
            		},

            		{"title" : "Action",data: "event_type", 
            		 	render: function (data, type, full, meta) {
                            var action = "";
                            $id = full.id;//$this->encryption->encrypt($u['id']);
                            if (full.event_type == 'GREETING') {
                                action = '<button type="button" id="delnoti" class="btn red-btn mr-2" data-tnot_del="' + $id + '" title="DELETE" style="width:75px;">delete</button>';
                            }
                            if (full.event_type == 'NOTIFICATION') {
                                action = '<button type="button" id="delnoti" class="btn red-btn mr-2" data-tnot_del="' + $id + '" title="DELETE" style="width:75px;">delete</button><button type="button" class="btn btn-peimary mr-2" id="User_Edit" data-edit_usr="' + $id + '" title="EDIT" style="width:75px;">edit</button>';
                            }
                            return action;
                        }
            		},

 
                   
                ],

             
            });
			
	  	  	$("a.nav-item").click(function(){
	  	  		var s = $(this).attr('href');
	  	  		s = s.replace("#", "");
	  	  		if(s == 'notification'){
	  	  			$("#add_notification").text('Add Notification');
	  	  		} else {
	  	  			$("#add_notification").text('Add Event');
	  	  		}
	  	  		addMode = s;
	  	  	});

            var head = '<button id="add_notification" style="float:right;" class="btn m-btn--pill btn-info" type="button">Add Notification</button>';
            
            $('#notification').before(head);
			


            $("#add_notification").on('click', function(){ 
            	$("#mdl_usr_dsc").remove();

            	console.log(addMode);

            	



            	var str = '<div class="modal fade" id="mdl_usr_dsc"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                str += '<div class="modal-dialog" role="document" id="lrge_modal">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                str += '<h5 class="modal-title" id="head_ttl">EDIT NOTIFICATION</h5>';
                // str += '<h5 class="modal-title" id="head_ttl2" style="display:none;"></h5>';
                str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                str += '<span aria-hidden="true">&times;</span>';
                str += '</button>';
                str += '</div>';
                str += '<div class="modal-body">';
                str += '<div id="model_content">';
                str += '<div class="row manage-blnc-users" id="first_div" style="">';
                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                str += '<div class="k-portlet">';
                
                if(addMode == 'notification'){
	  	  			str +=  notificationModelHtml();	
	  	  		} else {
  	  				str +=  eventModelHtml();
	  	  		}
                
                
                str += '</div> ';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
               	
               	$('body').append(str);

               	$('.select').select2();

                $("#evalidupto").datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    onSelect: function(date) {
                        selectdate = date
                    }
                });




                $('#add-enotisub').click(function(e) {
                    e.preventDefault();
                    $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                    var la1 = $(this).ladda();
                    la1.ladda('start');
                    var formdata = $('#enotification-form').serializeArray();
                    console.log(formdata);
                    // return;
                    $.ajax({
                        method: 'POST',
                        url: 'Manage/Notification_Add',
                        data: formdata,
                        dataType: 'JSON'
                    }).done(function(response) {
                        if (response) {
                            if (response.error == 1) {
                            	toastr.error(json.error_desc, 'Oops!');
                            } else if (response.error == 2) {
                                window.location.reload(!0)
                            } else if (response.error == 0) {
                            	toastr.success(response.msg, 'Success');
                                $('#mdl_usr_dsc').modal('hide');
                            }
                            la1.ladda('stop');

                        }
                    }).fail(function(err) {
                        la1.ladda('stop');
                        throw err
                    })
                });

               	$('#mdl_usr_dsc').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                })
                $('#mdl_usr_dsc').on('hidden.bs.modal', function () {
                    $('#mdl_usr_dsc').remove();
                    table.ajax.reload(null, false);
                });

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


			table.on('click', '#delnoti', function() {
				console.log('ahgdjhsagdhgs')
	            var delrow = {};
	            var delrow = $(this).data('tnot_del');
	            var row = $(this).closest('tr');
	            var editrow = table.row(row).data();
	            console.log(delrow);
	            console.log(editrow);
	            if (editrow['id'] == delrow) {
            
		            swal({
		                title: "Are you sure?",
		                text: "You will not be able to recover this imaginary file!",
		                type: "warning",
		                showCancelButton: !0,
		                confirmButtonColor: "#DD6B55",
		                confirmButtonText: "Yes, delete it!",
		                cancelButtonText: "No, cancel plx!",
		                closeOnConfirm: !1,
		                closeOnCancel: !1
		            }, function(isConfirm) {
		                if (isConfirm) {
		                    $.ajax({
		                        method: 'POST',
		                        url: 'Manage/deleteNotif',
		                        dataType: 'JSON',
		                        data: {
		                            reqdelid: delrow
		                        }
		                    }).done(function(response) {
		                        if (response) {
		                            if (response.error == 1) {
		                            	toastr.error(response.error_desc, 'Oops!');
		                            } else if (response.error == 2) {
	                                    window.location.reload(!0);
	                                    swal.close();
	                                } else if (response.error == 0) {
	                                	toastr.success(response.msg, 'Success');
                                        swal.close()
                                        table.ajax.reload(null, false);
                                    }
	                                
		                            
		                        }
		                    }).fail(function(err) {
		                        throw err
		                    });
		                    swal("Deleted!", "Your imaginary file has been deleted.", "success")
		                } else {
		                    swal("Cancelled", "Your imaginary file is safe :)", "error")
		                }
		            })
	        	}
	        });
	
		    table.on('click', '#User_Edit', function () {
            var id = $(this).data('edit_usr');
            var row = $(this).closest('tr');
            var editrow = table.row(row).data();
            if (editrow['id'] == id) {
            	$.ajax({
	                method: 'POST',
	                url: 'Manage/Edit_Notif',
	                dataType: 'JSON',
	                data: {'editrow': editrow['id']},
	            }).done(function (response) {
	                if (response) {
	                      console.log(response)
	                    if (response.error == 1){
	                        toastr.error(response.error_desc);
	                    } else if (response.error == 2){
	                        window.location.reload(true);
	                    } else if (response.error == 0) {
	                    	var fetchdata = response.data;
		        

							var str = '<div class="modal fade" id="mdl_usr_dsc"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
							str += '<div class="modal-dialog" role="document" id="lrge_modal">';
							str += '<div class="modal-content">';
							str += '<div class="modal-header">';
							str += '<h5 class="modal-title" id="head_ttl">EDIT NOTIFICATION</h5>';
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

							str +=  notificationModelHtml(fetchdata);

							str += '</div> ';
							str += '</div>';
							str += '</div>';
							str += '</div>';
							str += '</div>';
							str += '</div>';
							str += '</div>';
							str += '</div>';

		                    $("#mdl_usr_dsc").remove();
	                       	$('body').append(str);


	                       	
	                        $('.select').select2();

	                        $("#evalidupto").datepicker({
		                        format: 'yyyy-mm-dd',
		                        autoclose: true,
	                            onSelect: function(date) {
	                                selectdate = date
	                            }
		                    });



	                        $('#enotisub').click(function(e) {
	                            e.preventDefault();
	                            $('#enotisub').addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
	                            var la1 = $('#enotisub').ladda();
	                            la1.ladda('start');
	                            var formdata = $('#enotification-form').serializeArray();
	                            formdata.push({
	                                name: 'editrow',
	                                value: editrow['id']
	                            });
	                            console.log(editrow);
	                            console.log(formdata);
	                            $.ajax({
	                                method: 'POST',
	                                url: 'Manage/Notification_Edit',
	                                data: formdata,
	                                dataType: 'JSON'
	                            }).done(function(response) {
	                                if (response) {
	                                    if (response.error == 1) {
	                                    	toastr.error(json.error_desc, 'Oops!');
	                                    } else if (response.error == 2) {
	                                        window.location.reload(!0)
	                                    } else if (response.error == 0) {
	                                    	toastr.success(response.msg, 'Success');
	                                        $('#mdl_usr_dsc').modal('hide');
	                                    }
	                                    la1.ladda('stop');

	                                }
	                            }).fail(function(err) {
	                                la1.ladda('stop');
	                                throw err
	                            })
	                        });

			               	$('#mdl_usr_dsc').modal({
			                    backdrop: 'static',
			                    keyboard: false,
			                    show: true,
			                })
			                $('#mdl_usr_dsc').on('hidden.bs.modal', function () {
			                    $('#mdl_usr_dsc').remove();
			                    table.ajax.reload(null, false);
			                });
	                        
	                    }
	                }
	            }).fail(function (err) {
	                throw err;
	            });
              
            }  

        })

				
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
           // FetchRole();
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
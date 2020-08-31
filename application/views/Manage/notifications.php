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

    						<div class="tab-content mt-20" id="nav-tabContent">
    						    <div class="tab-pane fade show active table-responsive" id="btn-notification" ></div>


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

        // var addMode = 'notification';

    	var allroles = <?php echo json_encode($allroles);?>;

    	// console.log(allroles);

        	var notificationModelHtml = function(fetchdata){
				var heading = '', content = '', valid_upto = '', notif_for = '';
				var btnaddmode = '';
				if(typeof fetchdata != 'undefined'){
					heading = fetchdata['heading'];
					content = fetchdata['content'];
					valid_upto = (fetchdata['valid_upto']) ? fetchdata['valid_upto'].split(' ')[0] : '';
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
                                                    <input type="text" placeholder="Enter your Heading" name="eheading" id="eheading" class="form-control Eheading" value="`+ heading +`">
                                                    <span data-for="Eheading"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Content</label>
                                                    <input type="text" placeholder="Enter Your Content" name="econtent" id="econtent" class="form-control Econtent" value="`+ content +`">
                                                    <span data-for="Econtent"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Valid Upto</label>
                                                    <input type="text" placeholder="Valid Upto" name="evalidupto" id="evalidupto" class="form-control Evalidupto" value="`+valid_upto+`" readonly>
                                                    <span data-for="Evalidupto"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Notification For</label>
                                                    <select data-placeholder="Notification for" name="enotifor" id="enotifor" class="Enotifor select" >
                                                        <option value="0">ALL ROLES</option>`;
                                                        $.each(allroles, function(k, rname){
                                                        	str += `<option value="`+rname['role_id']+`">`+rname['role_name']+`</option>`;
                                                        });
                                                    str += `</select>
                                                    <span data-for="Enotifor"></span>                                                      		
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset>
                                    <div class="text-right">
                                        <!--<button type="reset" class="btn btn-danger  legitRipple" id="`+btnaddmode+`enotisub_reset">Reset form<i class="icon-reload-alt position-right"></i></button>-->
                                        <button type="button" class="btn btn-primary legitRipple" id="`+btnaddmode+`enotisub">Submit<i class="icon-arrow-right14 position-right"></i></button>
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
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Valid Upto</label>
                                                    <input type="text" placeholder="Valid Upto" name="evalidupto" id="evalidupto" class="form-control Evalidupto" readonly>
                                                    <span data-for="Evalidupto"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group input-group mb-3">
  
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input Event_pic" id="event_pic">
                                                <label class="custom-file-label" for="event_pic">Choose file</label>
                                            </div>
                                            <span data-for="Event_pic"></span>
                                        </div>
                                        <span class="help-block">please upload only jpg, jpeg, png, gif.</span><br>
                                            
                                            
                                        
                                        <fieldset>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary legitRipple" id="file-add-enotisub">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
							</div>
						</div>
					</div>
				</div>`;

				return str;

		}

       
    	

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

                    {"title": "Created on", data: "created_on", class: "none compact",
                        render: function (data, type, full, meta) {
                            if(full.created_on){
                                return full.created_on.split(' ')[0];
                            }else{
                                return '';
                            }
                            
                        }
                    },

            		{"title": "Content", data: "content", class: "none compact",
                        render: function (data, type, full, meta) {
                            return full.content;
                        }
                    },

                    {"title": "Valid upto", data: "valid_upto", class: "none compact",
                        render: function (data, type, full, meta) {
                            if(full.valid_upto){
                                return full.valid_upto.split(' ')[0];
                            }else{
                                return '';
                            }
                            
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
                                status = '<span class="label label-sm label-danger" data-color="text-danger"> SUPER DISTRIBUTOR </span>';
                            } else if (full.notif_for == 4) {
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
			
	  	  	

            var head = '<div class="row" style="margin-bottom:10px;"><div class="col-lg-6"><button style="float:right;" id="add_notification" class="btn btn-primary" type="button">Add Notification</button></div><div class="col-lg-6"><button id="add_event" class="btn btn-primary" type="button">Add Event</button></div></div><hr>';
            
            $('#btn-notification').before(head);
			


            $("#add_notification, #add_event").on('click', function(){ 
            	$("#mdl_usr_dsc").remove();


            	var head_ttl = '';

            	var str = '<div class="modal fade" id="mdl_usr_dsc"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                str += '<div class="modal-dialog" role="document" id="lrge_modal">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                str += '<h5 class="modal-title" id="head_ttl">ADD NOTIFICATION</h5>';
                str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                str += '<span aria-hidden="true">&times;</span>';
                str += '</button>';
                str += '</div>';
                str += '<div class="modal-body">';
                str += '<div id="model_content">';
                str += '<div class="row manage-blnc-users" id="first_div" style="">';
                str += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                str += '<div class="k-portlet">';
                
                if(this.id == 'add_notification'){
	  	  			str +=  notificationModelHtml();
                    head_ttl = 'ADD NOTIFICATION';	
	  	  		} else {
  	  				str +=  eventModelHtml();
                    head_ttl = 'ADD EVENT';
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

                $("#head_ttl").text(head_ttl);

               	$('.select').select2();

                // $("#evalidupto").datepicker({
                //     format: 'yyyy-mm-dd',
                //     autoclose: true,
                //     onSelect: function(date) {
                //         selectdate = date
                //     }
                // });

                



                $('#add-enotisub').click(function(e) {
                    e.preventDefault();

                    var params = {'valid': true};

                    // var data = new FormData();

                    params.eheading = $('#eheading').val();
                    params.econtent = $('#econtent').val();
                    params.evalidupto = $('#evalidupto').val();
                    params.enotifor = $('#enotifor').val();


                    if (!validate({'id': 'Eheading', 'type': 'TEXT', 'data': params.eheading, 'error': true, msg: $('#eheading').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': 'Econtent', 'type': 'TEXT', 'data': params.econtent, 'error': true, msg: $('#econtent').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': 'Evalidupto', 'type': 'TEXT', 'data': params.evalidupto, 'error': true, msg: $('#evalidupto').attr('placeholder')})) {
                        params.valid = false;
                    }

                    // if (!validate({'id': 'Enotifor', 'type': 'NOTIFOR', 'data': params.enotifor, 'error': true, msg: $('#enotifor').attr('placeholder')})) {
                    //     params.valid = false;
                    // }

                    if (params.valid == true) {
                        $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                        var la1 = $(this).ladda();
                        la1.ladda('start');
                        var formdata = $('#enotification-form').serializeArray();
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

                    }
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

                $( "#mdl_usr_dsc" ).on('shown.bs.modal', function(){
                    $("#evalidupto").datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        startDate: '+0d',
                        onSelect: function(date) {
                            selectdate = date
                        }
                    });
                    $("#evalidupto").datepicker("setDate", "+0d" );//.datepicker({ startDate: new Date() });
                });


                $('#file-add-enotisub').click(function (e) {
                    e.preventDefault();

                    var params = {'valid': true};


                    params.evalidupto = $('#evalidupto').val();
                    params.event_pic = $('#event_pic').val();
                    

                    if (!validate({'id': 'Evalidupto', 'type': 'TEXT', 'data': params.evalidupto, 'error': true, msg: $('#evalidupto').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': 'Event_pic', 'type': 'FILE', 'data': params.event_pic, 'error': true, msg: 'File'})) {
                        params.valid = false;
                    }

                    if (params.valid == true) {

                        var data = new FormData();
                        var file = $('#event_pic')[0]['files'][0];

                        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                        var la = $(this).ladda();
                        la.ladda('start');

                        data.append('File', file);

                        // console.log(file);return;
                        data.append('validuptoevent', params.evalidupto);

                        $.ajax({
                            method: 'POST',
                            url: 'Manage/Update_Event_Pic',
                            data: data,
                            dataType: 'JSON',
                            cache: false,
                            processData: false, // Don't process the files
                            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        }).done(function (response) {
                            if (response){
                                if (response.error == 1){
                                    toastr.error(response.error_desc, 'Oops!');
                                } else if (response.error == 2) {
                                    window.location.reload(true);
                                } else if (response.error == 0) {
                                    $('#mdl_usr_dsc').modal('hide');
                                    toastr.success(response.msg, 'Success');
                                    table.ajax.reload(null, false);
                                }
                                la.ladda('stop');
                            }
                        });
                    }
                    
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
				// console.log('ahgdjhsagdhgs')
	            var delrow = {};
	            var delrow = $(this).data('tnot_del');
	            var row = $(this).closest('tr');
	            var editrow = table.row(row).data();
	            // console.log(delrow);
	            // console.log(editrow);
	            if (editrow['id'] == delrow) {
            
		            swal({
		                title: "Are you sure to delete it?",
		                // text: "You will not be able to recover this imaginary file!",
		                type: "warning",
		                showCancelButton: !0,
		                confirmButtonColor: "#DD6B55",
		                confirmButtonText: "Yes",
		                cancelButtonText: "No",
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
		                    swal("Deleted!", "Record has been deleted.", "success")
		                } else {
		                    swal("Cancelled", "", "error")
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
	                      // console.log(response)
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

	                     //    $("#evalidupto").datepicker({
		                    //     format: 'yyyy-mm-dd',
		                    //     autoclose: true,
	                     //        onSelect: function(date) {
	                     //            selectdate = date
	                     //        }
		                    // });



	                        $('#enotisub').click(function(e) {
                                e.preventDefault();
                                var params = {'valid': true};

                                params.eheading = $('#eheading').val();
                                params.econtent = $('#econtent').val();
                                params.evalidupto = $('#evalidupto').val();
                                params.enotifor = $('#enotifor').val();


                                if (!validate({'id': 'Eheading', 'type': 'TEXT', 'data': params.eheading, 'error': true, msg: $('#eheading').attr('placeholder')})) {
                                    params.valid = false;
                                }

                                if (!validate({'id': 'Econtent', 'type': 'TEXT', 'data': params.econtent, 'error': true, msg: $('#econtent').attr('placeholder')})) {
                                    params.valid = false;
                                }

                                if (!validate({'id': 'Evalidupto', 'type': 'TEXT', 'data': params.evalidupto, 'error': true, msg: $('#evalidupto').attr('placeholder')})) {
                                    params.valid = false;
                                }

                                // if (!validate({'id': 'Enotifor', 'type': 'NOTIFOR', 'data': params.enotifor, 'error': true, msg: $('#enotifor').attr('placeholder')})) {
                                //     params.valid = false;
                                // }

                                if (params.valid == true) {
    	                            
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
    	                            });

                                }
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

                            $( "#mdl_usr_dsc" ).on('shown.bs.modal', function(){
                                $("#evalidupto").datepicker({
                                    format: 'yyyy-mm-dd',
                                    autoclose: true,
                                    startDate: '+0d',
                                    onSelect: function(date) {
                                        selectdate = date
                                    }
                                });
                                // $("#evalidupto").datepicker("setDate", "+0d" );//.datepicker({ startDate: new Date() });
                            });
	                        
	                    }
	                }
	            }).fail(function (err) {
	                throw err;
	            });
              
            }  

        })

				
	} /// var ConfigUser  function End///



        var validate = function (p){
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

            /*if (p.type == 'NOTIFOR') {
                if (p.data != "" && p.data in Regex.NOTIFOR) {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Notification For', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Notification For', 'type': 'error'});
                        }
                    }
                }
            } else*/ 
            if (p.type == 'TEXT') {
                var _identifier_regex = Regex.Text;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data)){
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else{
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'FILE') {
                var _identifier_regex = Regex.Text;
                var _mobile = new RegExp(_identifier_regex);
                if (_mobile.test(p.data)){
                    if (p.error == true && (p.data == ''))
                    {
                        helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                    } else
                    {
                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                } else{
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

	// $(function() {
	//  	   $("#AddUserNew_DateOfBirth").datepicker({
	//         format: 'dd-mm-yyyy',
	//         autoclose: true,

	//             endDate: '-18y',
	//             orientation:'bottom'
	//         });
	// 	    $('#AddUserNew_TwoFactor').bootstrapToggle({
	// 	      on: 'ON',
	// 	      off: 'OFF'
	// 	    });

	// 	     $('#AddUserNew_IsActive').bootstrapToggle({
	// 	      on: 'Active',
	// 	      off: 'Inactive'
	// 	    });

	// 	     $('#isdue').bootstrapToggle({
	// 	      on: 'Yes',
	// 	      off: 'No'
	// 	    });
	// })

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
                if (response){
                    if (response.error == 1){
                        toastr.error(response.error_desc);  
                    } else if (response.error == 2){
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
                if (response){
                    if (response.error_data == 1){
                        toastr.error(response.error_desc);
                    } else if (response.error_data == 2){
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

      

  

   return {
      init:function(){
           // KeyPress_Validation();
      }
    };
}();
$(document).ready(function(){
    CreateUsr_intilize.init();
})

</script>  
<!-- <script type="text/javascript">
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
</script> -->
</body>
</html>
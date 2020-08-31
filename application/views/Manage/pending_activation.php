	<div class="col-lg-10">
		<div class="beneficiary-list">
			<div class="gray-header" id="pendg_actv">Pending Activation</div>
				<div class="table-responsive">
					 <div id="Pendg_Usr_Tbl">
					<table class="table datatables">
						<thead class="thead-blue">

						</thead>
					</table>
					</div>
					<div id="Actv_Usr_Tbl"></div>
				</div>

		</div>
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		function format (data) {
		 return '<ul class="row-details-list">' +
		 '<li>' + '<span class="fontbold mr-2">Role Name:</span>' + '<span>' + data.role_name + '</span>' + '</li>'+
		 '<li>' + '<span class="fontbold mr-2">Business Name:</span>' + '<span>' + data.business_name +'</span>' + '</li>'+
		 '<li>' + '<span class="fontbold mr-2">Business Address:</span>' + '<span>'+ data.business_address + ' ' + data.business_state + ' ' + data.business_city + ' ' + data.business_pincode +'</span>' + '</li>'+
		 '<li>' + '<span class="fontbold mr-2">Registered Address:</span>' + '<span>' + data.registered_address + '</span>' + '</li>'+
		 '<li>' + '<span class="fontbold mr-2">Created By:</span>' + '<span>' + data.fsnam  + ' '+ data.lsnam+'</span>' + '</li>'+
		 '<li>' + '<span class="fontbold mr-2">Created On:</span>' + '<span>' + data.created_on + '</span>' + '</li>'+

		 '</ul>'
		};


			  var table = $('.datatables').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/PendingUsers",
                    type: 'post',
                    "dataSrc": function (json) {
                        console.log(json);
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
                 	{"title" : "","class": "details-control",
	                 "orderable": false,
	                   "render": function ( data, type, row, meta ) {
					      return null;
					    }
            		},

            		 {
			 		 "title" : "Full Name",

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
			 		 "title" : "Account Id",

	                 "data": "user_id",
            		},


            		{
			 		 "title" : "Action",
	                 "orderable": false,
	                 // "data": "status",
	                  "render": function ( data, type, full, meta ) {
					      return '<a data-pndg_actvtn="' + full.user_id + '" id="actvt_usr" class="btn btn-info white-txt">View</a>';
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

			   table.on('click', '#actvt_usr', function (e) {
                e.preventDefault();
                var user_id = $(this).attr('data-pndg_actvtn');
                var row = $(this).closest('tr');
                var showtd = table.row(row).data();

                if (user_id == showtd.user_id) {
                	console.log(showtd)
                    var str = '';
                    $.ajax({
                        method: 'POST',
                        url: 'Manage/get_user_docs',
                        data: {'data': showtd.user_id},
                        dataType: 'JSON'
                    }).done(function (response) {
                        if (response)
                        {

                            if (response.error == 1)
                            {

                                  toastr.error(json.error_desc, 'Oops!');

                            } else if (response.error == 2)
                            {
                                window.location.reload(true);
                            } else if (response.error == 0) {

                                var documnt_array = {};
                                var kyc = '';
                                kyc += '<div class="panel-heading panel-heading-set"><h6 class="panel-title">KYC Update For ' + showtd.first_name + ' ' + showtd.last_name + '</h6></div>';
                                kyc += '<div class="tableborderoutline" style="border: 1px #dbd7d7 solid;padding: 20px;margin-bottom: 15px;">';
                                kyc += '<table class="table" style="margin-bottom: 15px;">';
                                kyc += '<thead>';
                                kyc += '<tr>';
                                kyc += '<th>DocumentType</th>';
                                kyc += '<th>Document</th>';
                                kyc += '<th>Action</th>';
                                kyc += '</tr>';
                                kyc += '</thead>';
                                kyc += '<tbody>';
                                var kycCount = 1;

                                $.each(response.msg, function (k, v) {

                                    documnt_array[v.id] = v;
                                    kyc += '<tr>';

                                    kyc += '<td>';
                                    kyc += '<span>' + k + '</span>';
                                    kyc += '</td>';

                                    var docdta;
                                    var dta = 'kyc_' + k + '';
                                    docdta = dta.replace(" ", "");
                                    if ($.isEmptyObject(v) === true) {
                                           if(showtd.plan_id=='null'){
                                    console.log('000000000')

                                }
                                        kyc += '<td>';
                                        kyc += '<span class="btn border-orange text-orange btn-flat btn-min-width">Not Available</span>';
                                        kyc += '</td>';

                                        kyc += '<td id="ChnageTD_' + v.id + '">';
                                        kyc += '<span class="btn border-orange text-orange btn-flat btn-min-width">Not Available</span>';
                                        kyc += '</td>';
                                    } else {

                                           if(showtd.plan_id=='null'){
                                    console.log('78788787')

                                }
                                        kyc += ' <td><button class="btn btn-primary"><a href="' + v.doc_path + '" class="success-btn" target="_blank" style="color: white;">View</a></button></td>';
                                        if (v.status == "PENDING") {
                                            kyc += '<td><button class="btn btn btn-primary apprv_doc" data-apprv="' + v.id + '">Approve</button> <button class="btn btn-primary reject_doc" data-reject="' + v.id + '">Reject</button></td>';
                                        } else {
                                            var col;
                                            if (v.status == 'APPROVED') {
                                                col = 'btn border-success text-success btn-flat btn-min-width';
                                            } else {
                                                col = 'btn border-danger text-danger btn-flat btn-min-width';
                                            }
                                            kyc += ' <td id="ChnageTD_' + v.id + '"><button type="button" class="' + col + '" style="margin-right: 20px;"><i class="fas fa-check position-left"></i>' + v.status + '</button></td>';
                                        }
                                    }
                                    kyc += '</tr>';

                                    kyc += '<tr>';

                                    kycCount++;
                                });
                                kyc += '</tbody>';
                                kyc += '</table>';

                              console.log(showtd.plan_id);
                              if (showtd.plan_id='NULL') {
                                kyc += '<div class="row" >';

                                 kyc += '<div class="form-group col-lg-6">';
                                            kyc += '<label>Plan<span class="mandotry">*</span></label>';
                                            kyc += ' <select name="select" class="form-control input-xs Plan" id="UserActivate_Plan">';
                                               kyc += '  <option value="">Select Plan</option>';

                                            kyc += ' </select>';
                                            kyc += ' <span data-for="UserActivate_Plan"></span>';
                                         kyc += '</div>';

                                // kyc += '<div class="col-md-6">';
                                // kyc += '<button class="btn btn-primary" id="UserActivate">Activate User</button>';
                                // kyc += '</div>';
                                kyc += '</div>';
                                }

                                kyc += '</div>';

                                kyc += '<div class="row" style="border: 1px #e6e1e1 solid;padding-top: 10px;padding-bottom: 10px;">';
                                kyc += '<div class="col-md-6" style="text-align: right;">';
                                kyc += '<button class="btn btn-primary" id="UserActivate">Activate User</button>';
                                kyc += '</div>';
                                kyc += '<div class="col-md-6" style="text-align: left;">';
                                kyc += '<button class="btn btn-default" id="pDBack">Back</button>';
                                kyc += '</div>';
                                kyc += '</div>';


                                $('#Pendg_Usr_Tbl').hide();
                                $('#pendg_actv').hide();
                                $('#Actv_Usr_Tbl').html(kyc).show();
                                // $(".file-styled").uniform({
                                //     fileButtonClass: 'action btn btn-primary'
                                // });



                                    $.ajax({
                                        method: 'POST',
                                        url: 'Manage/Fetch_pln_frUser',
                                        dataType: 'JSON',
                                        data: {'roleId': showtd.role_id},
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
                                                plan = {};
                                                var str = '';
                                                str += '<option value="">Select Plan</option>';
                                                $.each(response.data, function (k, v) {
                                                    plan[v.plan_id] = v;
                                                    str += '<option value="' + v.plan_id + '">' + v.plan_name + ' (' + v.plan_code + ')</option>';
                                                });

                                                $('#UserActivate_Plan').html(str);
                                            }
                                        }
                                    }).fail(function (err) {
                                        throw err;
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

                                $('.apprv_doc').click(function (e) {
                                    e.preventDefault();
                                    var doc = $(this).data('apprv');
                                    var row = $(this).closest('tr').index();

                                    if (doc in documnt_array) {
                                        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                        var la = $(this).ladda();
                                        la.ladda('start');
                                        var dataString = {'apprv_id': doc, 'acntid': showtd.user_id};
                                        $.ajax({
                                            url: 'Manage/approve_usr_document',
                                            dataType: "json",
                                            data: dataString,
                                            type: 'post',
                                            success: function (data) {
                                                if (data.error == 0) {

                                                      toastr.success(data.msg);
                                                    $('#Pendg_Usr_Tbl').show();
                                                    $('#pendg_actv').show();
                                                    $('#Actv_Usr_Tbl').html('').hide();
                                                    table.ajax.reload();
                                                } else {


                                                     toastr.error(data.error_desc);
                                                    la.ladda('stop');
                                                }
                                                la.ladda('stop');
                                            }///success function close
                                        });
                                    } else {



                                        toastr.error("Unable To Find Document Details");


                                    }
                                });

                                $('.reject_doc').click(function (e) {
                                    e.preventDefault();
                                    var rjct_doc = $(this).data('reject');
                                    var row = $(this).closest('tr').index();

                                    if (rjct_doc in documnt_array) {
                                        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                        var la = $(this).ladda();
                                        la.ladda('start');
                                        var dataString = {'rjct_doc_id': rjct_doc, 'acntid': showtd.user_id};
                                        $.ajax({
                                            url: 'Manage/reject_usr_document',
                                            dataType: "json",
                                            data: dataString,
                                            type: 'post',
                                            success: function (data) {

                                                if (data.error == 0) {

                                                     toastr.success(data.msg);
                                                     $('#Pendg_Usr_Tbl').show();
                                                      $('#pendg_actv').show();
                                                     $('#Actv_Usr_Tbl').html('').hide();
                                                    table.ajax.reload();
                                                } else {

                                                     toastr.error(data.error_desc);
                                                    la.ladda('stop');
                                                }
                                                la.ladda('stop');
                                            }///success function close
                                        });
                                    } else {

                                           toastr.error("Unable To Find Document Details");
                                    }
                                });

                                $('#UserActivate').click(function (e) {
                                       e.preventDefault();
                                       var params = {'valid': true};
                                       var actid = $(this).attr('id');
                                       console.log(actid);
                                        params.Plan = $('#' + actid + '_Plan option:selected').val();
                                        params.UserId = showtd.user_id;
                                       if (!validate({'id': '' + actid + '_Plan', 'type': 'PLAN', 'data': params.Plan, 'error': true, msg: $('#' + actid + '_Plan').attr('placeholder')})) {
                                                params.valid = false;
                                            }
                                        if (params.valid == true) {

                                            console.log(params)
                                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                            var la = $(this).ladda();
                                            la.ladda('start');
                                            $.ajax({
                                                method: 'POST',
                                                url: 'Manage/Actvt_usr_drctly',
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
                                                        $('#Pendg_Usr_Tbl').show();
                                                         $('#pendg_actv').show();
                                                        $('#Actv_Usr_Tbl').html('').hide();
                                                        table.ajax.reload();
                                                    }
                                                    la.ladda('stop');
                                                }
                                                la.ladda('stop');
                                            }).fail(function (err) {
                                                la.ladda('stop');
                                                throw err;
                                            });


                                        }

                                    // var dataString = {'acntid': showtd.user_id};
                                    // $.ajax({
                                    //     url: 'Manage/Actvt_usr_drctly',
                                    //     dataType: "json",
                                    //     data: dataString,
                                    //     type: 'post',
                                    //     success: function (data) {

                                    //         if (data.error == 0) {
                                    //                toastr.success(data.msg);
                                    //             $('#Pendg_Usr_Tbl').show();
                                    //              $('#pendg_actv').show();
                                    //             $('#Actv_Usr_Tbl').html('').hide();
                                    //             table.ajax.reload();
                                    //         } else {

                                    //              toastr.error(data.error_desc);
                                    //             la.ladda('stop');
                                    //         }
                                    //         la.ladda('stop');
                                    //     }///success function close
                                    // });
                                });

                                $('#pDBack').click(function (e) {
                                    e.preventDefault();
                                    $('#Pendg_Usr_Tbl').show();
                                     $('#pendg_actv').show();
                                    $('#Actv_Usr_Tbl').html('').hide();
                                });
                            }
                        }
                    }).fail(function (err) {
                        throw err;
                    });


                }
            });


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



	});
</script>

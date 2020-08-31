<?php
$Regex = All_Regex();
?>
	<div class="col-lg-10">
		<div class="beneficiary-list">
			<!-- <div class="gray-header" style="margin-bottom: 40px;" id="title_first">Vendors</div>
                <div class="row" id="AddDivButton">
                <div class="col-lg-12">
                <button class="btn btn-primary btn-lgbtn btn-primary btn-md" style="float: right;" id="VendorAdd">Add Vendor</button>
                </div>
                </div> -->

                <div class="width-100 mb-3 bord-bottom gray-header" id="title_first">
              <div class="float-left" style="margin-top:7px;">Vendors</div>
              <div  id="AddDivButton" class="float-right">
              <button class="btn btn-primary  mr-10"  id="VendorAdd">Add Vendor</button>
              </div>
            </div>

                <div  id="TableView">
				<div class="table-responsive">					
				<table class="table datatables">
				<thead class="thead-blue"></thead>
				</table>
				</div>
                </div>
                <div class="row" id="AddView"></div>
                <div class="row" id="EditView"></div>
                <div class="row" id="ViewView"></div>
								
		</div>
	</div>
<script type="text/javascript">
	$(document).ready(function(){
         var Regex = <?php echo json_encode($Regex) ?>

			  var table = $('.datatables').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/get_all_vendors",
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
              
                columns: [
       
            		 {
			 		 "title" : "Vendor Name",
	                
	                 "data": "vendor_name",
            		},

            		{title: 'Down Status', data: 'is_down', class: 'all',
                render: function (data, type, full, meta) {
                    var status = "";
                    if (data == 0) {
                        status = "Up";
                    } else if (data == 1) {
                        status = "Down";
                    } else {
                        status = "Undefined";
                    }
                    return status;
                }

            },

            {title: 'Active Status', data: 'is_active', class: 'all',
                render: function (data, type, full, meta) {
                    var status = "";
                    if (data == 0) {
                        status = "Inactive";
                    } else if (data == 1) {
                        status = "Active";
                    } else {
                        status = "Undefined";
                    }
                    return status;
                }

            },
            		
            {title: 'Balance', data: 'bal_check_api', class: 'all',
                render: function (data, type, full, meta) {
                    var status = "";
                    if (data == 0) {
                        status = '<span id="bal"><span class="label label-sm label-success"> Balance Api not available </span></span>';
                    } else if (data == 1) {
                        status = '<span id="bal" ><a href="javascript:void(0)" id="vbal" data-tbal="' + full.vendor_id + '">View Balance</a></span>';
                    } else {
                        status = "Undefined";
                    }
                    return status;
             }

            },

            {title: 'Action', class: 'all ',

                render: function (data, type, full, meta) {
                            return '<button data-vendrs="' + full.vendor_id + '" type="button" class="btn btn-space btn-primary btn-sm btn-primary btn-sm" id="Vendor_Edit">Edit</button>\n\
                    <a href="Manage/VendorView?p=' + full.vendor_id + '" target="_blank"><button data-vendrs="' + full.vendor_id + '" class="btn btn-space btn-primary btn-sm btn-primary btn-sm">View</button></a>'

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
		     }else {
		       row.child(format(row.data())).show();
		       tr.next('tr').addClass('details-row');
		       tr.addClass('details');
		     }
		  });


		table.on('click','#vbal',function(){
	    var q = $(this)[0];
        var balnc = $(this).data('tbal');
        var span = $(this).closest('td').find('#bal');
        if (balnc && balnc != null && balnc != '')
        {
            $(span).html('<i class="fas fa-cog fa-spin"></i>');
            $.post('Manage/getvbal', {data: balnc}, function (response) {
                if (response)
                {
                    if (response.error == 0)
                    {
                        $(span).html($(q).html(response.bal));
                         toastr.info(response.msg);

                    } else if (response.error == 2)
                    {
                        window.location.reload(true);
                    }
                    else if (response.error == 1)
                    {
                         $(span).html($(q).html('Retry'));
                         toastr.error(response.error_desc);
                        
                    }
                }
            }, 'json').fail(function (error) {
                $(span).html($(q).html('Retry'));
                toastr.error('Something went wrong');
                throw error;
            });


        } else {
           toastr.error(response.error_desc);
          
        }
	
	});


    /*****************************************************Edit Vendor Details ******************************************************/
	   table.on('click', '#Vendor_Edit', function (e) {
                e.preventDefault();
                var vendrs_id = $(this).data('vendrs');
                var row = $(this).closest('tr');
                var showtd = table.row(row).data();
                if (showtd.vendor_id == vendrs_id) {
                    $('#title_first').hide();
                     var balcheck;
                    if (showtd.bal_check_api == 1) {
                        balcheck = 'checked'
                    } else {
                        balcheck = '';
                    }

                    var apiStatus;
                    if (showtd.status_api == 1) {
                        apiStatus = 'checked';
                    } else {
                        apiStatus = '';
                    }

                    var isDown;
                    if (showtd.is_down == 0) {
                        isDown = '';
                    } else {
                        isDown = 'checked';
                    }

                    var isActive;
                    if (showtd.is_active == 1) {
                        isActive = 'checked';
                    } else {
                        isActive = '';
                    }

                    var Vname = showtd.vendor_name ? showtd.vendor_name : '';
                    var Vcode = showtd.vendor_code ? showtd.vendor_code : '';
                    var Vlibrary = showtd.vendor_library ? showtd.vendor_library : '';
                   
                    var VCinfo = showtd.company_info ? showtd.company_info : '';
                    var Vbilling_contacts_to = showtd.billing_contacts_to ? showtd.billing_contacts_to : '';
                    var Vbilling_contacts_cc = showtd.billing_contacts_cc ? showtd.billing_contacts_cc : '';
                    var Vsupport_contacts_to = showtd.support_contacts_to ? showtd.support_contacts_to : '';
                    var Vsupport_contacts_cc = showtd.support_contacts_cc ? showtd.support_contacts_cc : '';
                     var html = '';
                    html += '<div class="gray-header" style="margin-bottom: 40px;">Vendor Edit</div>'
                    html += '  <div class="col-lg-12">';
                    html += '<div class="form-wrp">';

                    html += '<div class="row">';

                    html += '<div class="col-md-6">';
                    html += '<div class="form-group has-feedback">';
                    html += '  <label>Vendor Name<span class="mandotry">*</span></label>';
                    html += '<input type="text" class="form-control input-lg VendorName" placeholder="Vendor Name" id="VendorEdit_VendorName" value=' + Vname + '>';
                    html += '<span data-for="VendorEdit_VendorName"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="col-md-6">';
                    html += '<div class="form-group has-feedback">';
                    html += '  <label>Vendor Code <span class="mandotry">*</span></label>';
                    html += '<input type="text" class="form-control input-lg VendorCode" placeholder="Vendor Code" id="VendorEdit_VendorCode" value=' + Vcode + '>';
                    html += '<span data-for="VendorEdit_VendorCode"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += ' <div class="col-md-6">';
                    html += ' <div class="form-group has-feedback">';
                    html += '  <label>Vendor Library</label>';
                    html += ' <input type="text" class="form-control input-lg VendorLibrary" placeholder="Vendor Library" id="VendorEdit_VendorLibrary" value=' + Vlibrary + '>';
                    html += '<span data-for="VendorEdit_VendorLibrary"></span>';
                    html += '</div>';
                    html += '</div>';

                    // html += ' <div class="col-md-6">';
                    // html += ' <div class="form-group has-feedback">';
                    // html += '  <label>Vendor Type <span class="mandotry">*</span></label>';
                    // html += ' <input type="text" class="form-control input-lg VendorType" placeholder="Vendor Type" id="VendorEdit_VendorType" value=' + Vtype + '>';
                    // html += '<span data-for="VendorEdit_VendorType"></span>';
                    // html += '</div>';
                    // html += '</div>';

                    html += ' <div class="col-md-6">';
                    html += ' <div class="form-group has-feedback">';
                    html += '  <label>Company Info</label>';
                    html += ' <input type="text" class="form-control input-lg CompanyInfo" placeholder="Company Info" id="VendorEdit_CompanyInfo" value=' + VCinfo + '>';
                    html += '<span data-for="VendorEdit_CompanyInfo"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += ' <div class="col-md-6">';
                    html += ' <div class="form-group has-feedback">';
                    html += '  <label>Billing Contacts To</label>';
                    html += ' <input type="text" class="form-control input-lg BillingContactsTo" placeholder="Billing Contacts To" id="VendorEdit_BillingContactsTo" value=' + Vbilling_contacts_to + '>';
                    html += '<span data-for="VendorEdit_BillingContactsTo"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += ' <div class="col-md-6">';
                    html += ' <div class="form-group has-feedback">';
                    html += '  <label>Billing Contacts CC</label>';
                    html += ' <input type="text" class="form-control input-lg BillingContactsCC" placeholder="Billing Contacts CC" id="VendorEdit_BillingContactsCC" value=' + Vbilling_contacts_cc + '>';
                    html += '<span data-for="VendorEdit_BillingContactsCC"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += ' <div class="col-md-6">';
                    html += ' <div class="form-group has-feedback">';
                    html += '  <label>Support Contacts To</label>';
                    html += ' <input type="text" class="form-control input-lg SupportContactsTo" placeholder="Support Contacts To" id="VendorEdit_SupportContactsTo" value=' + Vsupport_contacts_to + '>';
                    html += '<span data-for="VendorEdit_SupportContactsTo"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += ' <div class="col-md-6">';
                    html += ' <div class="form-group has-feedback">';
                    html += '  <label>Support Contacts CC</label>';
                    html += ' <input type="text" class="form-control input-lg SupportContactsCC" placeholder="Support Contacts CC" id="VendorEdit_SupportContactsCC" value=' + Vsupport_contacts_cc + '>';
                    html += '<span data-for="VendorEdit_SupportContactsCC"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += '</div>';
                    html += '<div class="row">';

                    html += ' <div class="col-md-3" style="margin-top: 20px;">';
                    html += ' <div class="form-group has-feedback">';
                    html += '<div class="checkbox checkbox-switch">';
                    html += '<label>';
                    html += '<input type="checkbox" class="switch VendorEdit_BalanceCheckApi" id="VendorEdit_BalanceCheckApi" ' + balcheck + '>';
                    html += 'Bal Check API';
                    html += '</label>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
  
                    html += ' <div class="col-md-3" style="margin-top: 20px;">';
                    html += ' <div class="form-group has-feedback">';
                    html += '<div class="checkbox checkbox-switch">';
                    html += '<label>';
                    html += '<input type="checkbox" class="switch VendorEdit_ApiStatus" id="VendorEdit_ApiStatus" ' + apiStatus + '>';
                    html += 'API Status';
                    html += '</label>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += ' <div class="col-md-3" style="margin-top: 20px;">';
                    html += ' <div class="form-group has-feedback">';
                    html += '<div class="checkbox checkbox-switch">';
                    html += '<label>';
                    html += '<input type="checkbox" class="switch VendorEdit_IsDown" id="VendorEdit_IsDown" ' + isDown + '>';
                    html += 'Is Down';
                    html += '</label>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += ' <div class="col-md-3" style="margin-top: 20px;">';
                    html += ' <div class="form-group has-feedback">';
                    html += '<div class="checkbox checkbox-switch">';
                    html += '<label>';
                    html += '<input type="checkbox" class="switch VendorEdit_IsActive" id="VendorEdit_IsActive" ' + isActive + '>';
                    html += 'Is Active';
                    html += '</label>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += ' </div>';

                    html += '<div class="row">';

                    html += ' <div class="col-md-12">';
                    html += ' <div class="form-group has-feedback has-feedback-left text-center">';
                    html += '  <button class="btn btn-default btn-lg" style="margin-top: 26px;width: 150px;" id="EditBack">Back</button>';
                    html += '  <button class="btn btn-primary btn-lg VendorEdit" style="margin-top: 26px;width: 150px;" id="VendorEdit">Proceed</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '</div>';
                    html += '</div>';

                    $('#AddDivButton').hide();
                    $('#TableView').hide();
                    $('#EditView').html(html).show();
                      KeyPress_Validation();

                    $("#VendorEdit_BalanceCheckApi").bootstrapToggle({
                        on: 'Yes',
                        off: 'No'
                    });

                    $("#VendorEdit_ApiStatus").bootstrapToggle({
                        on: 'Active',
                        off: 'Inactive'
                    });

                    $("#VendorEdit_IsDown").bootstrapToggle({
                        on: 'Yes',
                        off: 'No'
                    });

                    $("#VendorEdit_IsActive").bootstrapToggle({
                        on: 'Yes',
                        off: 'No'
                    });

                     $('.VendorEdit').click(function (e) {
                        e.preventDefault();

                        var params = {'valid': true};

                        var actid = $(this).attr('id');

                        params.VendorName = $('#' + actid + '_VendorName').val();
                        params.VendorCode = $('#' + actid + '_VendorCode').val();
                        params.VendorLibrary = $('#' + actid + '_VendorLibrary').val();
                        // params.VendorType = $('#' + actid + '_VendorType').val();
                        params.CompanyInfo = $('#' + actid + '_CompanyInfo').val();
                        params.BillingContactsTo = $('#' + actid + '_BillingContactsTo').val();
                        params.BillingContactsCC = $('#' + actid + '_BillingContactsCC').val();
                        params.SupportContactsTo = $('#' + actid + '_SupportContactsTo').val();
                        params.SupportContactsCC = $('#' + actid + '_SupportContactsCC').val();
                        params.BalanceCheckApi = $('#' + actid + '_BalanceCheckApi').is(":checked");
                        params.ApiStatus = $('#' + actid + '_ApiStatus').is(":checked");
                        params.IsDown = $('#' + actid + '_IsDown').is(":checked");
                        params.IsActive = $('#' + actid + '_IsActive').is(":checked");
                        params.Id = showtd.vendor_id;
                        if (!validate({'id': '' + actid + '_VendorName', 'type': 'NAME', 'data': params.VendorName, 'error': true, msg: $('#' + actid + '_VendorName').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_VendorCode', 'type': 'CODE', 'data': params.VendorCode, 'error': true, msg: $('#' + actid + '_VendorCode').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (params.VendorLibrary != '') {
                            if (!validate({'id': '' + actid + '_VendorLibrary', 'type': 'CODE', 'data': params.VendorLibrary, 'error': true, msg: $('#' + actid + '_VendorLibrary').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }

                        // if (!validate({'id': '' + actid + '_VendorType', 'type': 'NAME', 'data': params.VendorType, 'error': true, msg: $('#' + actid + '_VendorType').attr('placeholder')})) {
                        //     params.valid = false;
                        // }

                        if (params.CompanyInfo != '') {
                            if (!validate({'id': '' + actid + '_CompanyInfo', 'type': 'NAME', 'data': params.CompanyInfo, 'error': true, msg: $('#' + actid + '_CompanyInfo').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }

                        if (params.BillingContactsTo != '') {
                            if (!validate({'id': '' + actid + '_BillingContactsTo', 'type': 'EMAIL', 'data': params.BillingContactsTo, 'error': true, msg: $('#' + actid + '_BillingContactsTo').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }

                        if (params.BillingContactsCC != '') {
                            if (!validate({'id': '' + actid + '_BillingContactsCC', 'type': 'EMAIL', 'data': params.BillingContactsCC, 'error': true, msg: $('#' + actid + '_BillingContactsCC').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }

                        if (params.SupportContactsTo != '') {
                            if (!validate({'id': '' + actid + '_SupportContactsTo', 'type': 'EMAIL', 'data': params.SupportContactsTo, 'error': true, msg: $('#' + actid + '_SupportContactsTo').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }

                        if (params.SupportContactsCC != '') {
                            if (!validate({'id': '' + actid + '_SupportContactsCC', 'type': 'EMAIL', 'data': params.SupportContactsCC, 'error': true, msg: $('#' + actid + '_SupportContactsCC').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }


                        if (params.valid == true) {
                            console.log(params)
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');

                            $.ajax({
                                method: 'POST',
                                url: 'Manage/VendorDataEdit',
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
                                         $('#title_first').show();

                                        $('#AddDivButton').show();
                                        $('#TableView').show();
                                        $('#EditView').html('').hide();
                                        $('#VendorTableData').DataTable().ajax.reload();
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
                    
                    $('#EditBack').click(function (e) {
                        e.preventDefault();
                        $('#title_first').show();
                        $('#AddDivButton').show();
                        $('#TableView').show();
                        $('#EditView').html('').hide();
                    });

                }

            })



            $('#VendorAdd').click(function (e) {   
            e.preventDefault();

            var html = '';

            html += '<div class="col-lg-12">';
            html += '<div class="gray-header" style="margin-bottom: 40px;">Vendor Add</div>';

            html += '<div class="form-wrp">';

            html += '<div class="row">';

            html += '<div class="col-md-6">';
            html += '<div class="form-group has-feedback">';
            html += '  <label>Vendor Name <span class="mandotry">*</span></label>';
            html += '<input type="text" class="form-control input-lg VendorName" placeholder="Vendor Name" id="VendorAdd_VendorName">';
            html += '<span data-for="VendorAdd_VendorName"></span>';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-md-6">';
            html += '<div class="form-group has-feedback">';
            html += '  <label>Vendor Code <span class="mandotry">*</span></label>';
            html += '<input type="text" class="form-control input-lg VendorCode" placeholder="Vendor Code" id="VendorAdd_VendorCode">';
            html += '<span data-for="VendorAdd_VendorCode"></span>';
            html += '</div>';
            html += '</div>';

            html += ' </div>';
            html += ' <div class="row">';

            html += ' <div class="col-md-6">';
            html += ' <div class="form-group has-feedback">';
            html += '  <label>Vendor Library</label>';
            html += ' <input type="text" class="form-control input-lg VendorLibrary" placeholder="Vendor Library" id="VendorAdd_VendorLibrary">';
            html += '<span data-for="VendorAdd_VendorLibrary"></span>';
            html += '</div>';
            html += '</div>';

            // html += ' <div class="col-md-6">';
            // html += ' <div class="form-group has-feedback">';
            // html += '  <label>Vendor Type <span class="mandotry">*</span></label>';
            // html += ' <input type="text" class="form-control input-lg VendorType" placeholder="Vendor Type" id="VendorAdd_VendorType">';
            // html += '<span data-for="VendorAdd_VendorType"></span>';
            // html += '</div>';
            // html += '</div>';

            html += ' <div class="col-md-6">';
            html += ' <div class="form-group has-feedback">';
            html += '  <label>Company Info</label>';
            html += ' <input type="text" class="form-control input-lg CompanyInfo" placeholder="Company Info" id="VendorAdd_CompanyInfo">';
            html += '<span data-for="VendorAdd_CompanyInfo"></span>';
            html += '</div>';
            html += '</div>';


            html += ' </div>';
            html += ' <div class="row">';

       
            html += ' <div class="col-md-6">';
            html += ' <div class="form-group has-feedback">';
            html += '  <label>Billing Contacts To</label>';
            html += ' <input type="text" class="form-control input-lg BillingContactsTo" placeholder="Billing Contacts To" id="VendorAdd_BillingContactsTo">';
            html += '<span data-for="VendorAdd_BillingContactsTo"></span>';
            html += '</div>';
            html += '</div>';


            html += ' <div class="col-md-6">';
            html += ' <div class="form-group has-feedback">';
            html += '  <label>Billing Contacts CC</label>';
            html += ' <input type="text" class="form-control input-lg BillingContactsCC" placeholder="Billing Contacts CC" id="VendorAdd_BillingContactsCC">';
            html += '<span data-for="VendorAdd_BillingContactsCC"></span>';
            html += '</div>';
            html += '</div>';

            html += ' </div>';
            html += ' <div class="row">';


            html += ' <div class="col-md-6">';
            html += ' <div class="form-group has-feedback">';
            html += '  <label>Support Contacts To</label>';
            html += ' <input type="text" class="form-control input-lg SupportContactsTo" placeholder="Support Contacts To" id="VendorAdd_SupportContactsTo">';
            html += '<span data-for="VendorAdd_SupportContactsTo"></span>';
            html += '</div>';
            html += '</div>';

            html += ' <div class="col-md-6">';
            html += ' <div class="form-group has-feedback">';
            html += '  <label>Support Contacts CC</label>';
            html += ' <input type="text" class="form-control input-lg SupportContactsCC" placeholder="Support Contacts CC" id="VendorAdd_SupportContactsCC">';
            html += '<span data-for="VendorAdd_SupportContactsCC"></span>';
            html += '</div>';
            html += '</div>';


            html += ' </div>';
           
            html += '<div class="row">';

            html += ' <div class="col-md-3" style="margin-top: 20px;">';
            html += ' <div class="form-group has-feedback">';
            html += '<div class="checkbox checkbox-switch">';
            html += '<label>';
            html += '<input type="checkbox" class="switch VendorAdd_BalanceCheckApi" checked="checked" id="VendorAdd_BalanceCheckApi">';
            html += 'Balance Check API';
            html += '</label>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            html += ' <div class="col-md-3" style="margin-top: 20px;">';
            html += ' <div class="form-group has-feedback">';
            html += '<div class="checkbox checkbox-switch">';
            html += '<label>';
            html += '<input type="checkbox" class="switch VendorAdd_ApiStatus" checked="checked" id="VendorAdd_ApiStatus">';
            html += 'API Status';
            html += '</label>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            html += ' <div class="col-md-3" style="margin-top: 20px;">';
            html += ' <div class="form-group has-feedback">';
            html += '<div class="checkbox checkbox-switch">';
            html += '<label>';
            html += '<input type="checkbox" class="switch VendorAdd_IsDown" checked="checked" id="VendorAdd_IsDown">';
            html += 'Is Down';
            html += '</label>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            html += ' <div class="col-md-3" style="margin-top: 20px;">';
            html += ' <div class="form-group has-feedback">';
            html += '<div class="checkbox checkbox-switch">';
            html += '<label>';
            html += '<input type="checkbox" class="switch VendorAdd_IsActive" checked="checked" id="VendorAdd_IsActive">';
            html += 'Is Active';
            html += '</label>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            html += ' </div>';


            // html += '<h4 style="border-bottom: #e6e6e6 1px solid;">Vendor Bank Deatils</h4>';

            // html += '<div class="row">';

            // html += '<div class="col-md-6">';
            // html += '<div class="form-group has-feedback">';
            // html += '  <label>Vendor Bank</label>';
            // html += '<input type="text" class="form-control input-lg VendorAdd_VendorBank" placeholder="Vendor Bank" id="VendorAdd_VendorBank">';
            // html += '<span data-for="VendorAdd_VendorBank"></span>';
            // html += '</div>';
            // html += '</div>';

            // html += '<div class="col-md-6">';
            // html += '<div class="form-group has-feedback">';
            // html += '  <label>Vendor IFSC</label>';
            // html += '<input type="text" class="form-control input-lg VendorAdd_VendorIFSC" placeholder="Vendor IFSC" id="VendorAdd_VendorIFSC">';
            // html += '<span data-for="VendorAdd_VendorIFSC"></span>';
            // html += '</div>';
            // html += '</div>';

            // html += ' </div>';

            // html += '<div class="row">';

            // html += '<div class="col-md-6">';
            // html += '<div class="form-group has-feedback">';
            // html += '  <label>Vendor Account</label>';
            // html += '<input type="text" class="form-control input-lg VendorAdd_VendorAccount" placeholder="Vendor Account" id="VendorAdd_VendorAccount">';
            // html += '<span data-for="VendorAdd_VendorAccount"></span>';
            // html += '</div>';
            // html += '</div>';

            // html += '<div class="col-md-6">';
            // html += '<div class="form-group has-feedback">';
            // html += '  <label>Vendor Branch</label>';
            // html += '<input type="text" class="form-control input-lg VendorAdd_VendorBranch" placeholder="Vendor Branch" id="VendorAdd_VendorBranch">';
            // html += '<span data-for="VendorAdd_VendorBranch"></span>';
            // html += '</div>';
            // html += '</div>';

            // html += ' </div>';



            html += '<div class="row">';

            html += ' <div class="col-md-12">';
            html += ' <div class="form-group has-feedback text-center">';
            html += '  <button class="btn btn-default btn-lg" style="margin-top: 26px;width: 150px;" id="AddBack">Back</button>';
            html += '  <button type="submit" class="btn btn-primary btn-lg VendorAdd" style="margin-top: 26px;width: 150px;" id="VendorAdd">Proceed</button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            html += '</div>';
            html += '</div>';

            html += '<div id="ConformAddView"></div>';
            $('#title_first').hide();
            $('#AddDivButton').hide();
            $('#TableView').hide();
            $('#AddView').html(html).show();
            KeyPress_Validation();
            $("#VendorAdd_BalanceCheckApi").bootstrapToggle({
                onText: 'Yes',
                offText: 'No'
            });

            $("#VendorAdd_ApiStatus").bootstrapToggle({
                onText: 'Active',
                offText: 'Inactive'
            });

            $("#VendorAdd_IsDown").bootstrapToggle({
                onText: 'Yes',
                offText: 'No'
            });

            $("#VendorAdd_IsActive").bootstrapToggle({
                onText: 'Yes',
                offText: 'No'
            });

            $('.VendorAdd').click(function (e) {
                e.preventDefault();

                var params = {'valid': true};

                var actid = $(this).attr('id');

                params.VendorName = $('#' + actid + '_VendorName').val();
                params.VendorCode = $('#' + actid + '_VendorCode').val();
                params.VendorLibrary = $('#' + actid + '_VendorLibrary').val();
                // params.VendorType = $('#' + actid + '_VendorType').val();
                params.CompanyInfo = $('#' + actid + '_CompanyInfo').val();
                params.BillingContactsTo = $('#' + actid + '_BillingContactsTo').val();
                params.BillingContactsCC = $('#' + actid + '_BillingContactsCC').val();
                params.SupportContactsTo = $('#' + actid + '_SupportContactsTo').val();
                params.SupportContactsCC = $('#' + actid + '_SupportContactsCC').val();
                params.BalanceCheckApi = $('#' + actid + '_BalanceCheckApi').is(":checked");
                params.ApiStatus = $('#' + actid + '_ApiStatus').is(":checked");
                params.IsDown = $('#' + actid + '_IsDown').is(":checked");
                params.IsActive = $('#' + actid + '_IsActive').is(":checked");

               // params.Bank = {};

                // params.Bank.VendorBank = $('#' + actid + '_VendorBank').val();
                // params.Bank.VendorIFSC = $('#' + actid + '_VendorIFSC').val();
                // params.Bank.VendorAccount = $('#' + actid + '_VendorAccount').val();
                // params.Bank.VendorBranch = $('#' + actid + '_VendorBranch').val();
                console.log(params);

                if (!validate({'id': '' + actid + '_VendorName', 'type': 'NAME', 'data': params.VendorName, 'error': true, msg: $('#' + actid + '_VendorName').attr('placeholder')})) {
                    params.valid = false;
                }

                if (!validate({'id': '' + actid + '_VendorCode', 'type': 'CODE', 'data': params.VendorCode, 'error': true, msg: $('#' + actid + '_VendorCode').attr('placeholder')})) {
                    params.valid = false;
                }

                if (params.VendorLibrary != '') {
                    if (!validate({'id': '' + actid + '_VendorLibrary', 'type': 'CODE', 'data': params.VendorLibrary, 'error': true, msg: $('#' + actid + '_VendorLibrary').attr('placeholder')})) {
                        params.valid = false;
                    }
                }

                // if (!validate({'id': '' + actid + '_VendorType', 'type': 'NAME', 'data': params.VendorType, 'error': true, msg: $('#' + actid + '_VendorType').attr('placeholder')})) {
                //     params.valid = false;
                // }

                if (params.CompanyInfo != '') {
                    if (!validate({'id': '' + actid + '_CompanyInfo', 'type': 'NAME', 'data': params.CompanyInfo, 'error': true, msg: $('#' + actid + '_CompanyInfo').attr('placeholder')})) {
                        params.valid = false;
                    }
                }

                if (params.BillingContactsTo != '') {
                    if (!validate({'id': '' + actid + '_BillingContactsTo', 'type': 'EMAIL', 'data': params.BillingContactsTo, 'error': true, msg: $('#' + actid + '_BillingContactsTo').attr('placeholder')})) {
                        params.valid = false;
                    }
                }

                if (params.BillingContactsCC != '') {
                    if (!validate({'id': '' + actid + '_BillingContactsCC', 'type': 'EMAIL', 'data': params.BillingContactsCC, 'error': true, msg: $('#' + actid + '_BillingContactsCC').attr('placeholder')})) {
                        params.valid = false;
                    }
                }

                if (params.SupportContactsTo != '') {
                    if (!validate({'id': '' + actid + '_SupportContactsTo', 'type': 'EMAIL', 'data': params.SupportContactsTo, 'error': true, msg: $('#' + actid + '_SupportContactsTo').attr('placeholder')})) {
                        params.valid = false;
                    }
                }

                if (params.SupportContactsCC != '') {
                    if (!validate({'id': '' + actid + '_SupportContactsCC', 'type': 'EMAIL', 'data': params.SupportContactsCC, 'error': true, msg: $('#' + actid + '_SupportContactsCC').attr('placeholder')})) {
                        params.valid = false;
                    }
                }

                // if (params.Bank.VendorBank != '') {
                //     if (!validate({'id': '' + actid + '_VendorBank', 'type': 'NAME', 'data': params.Bank.VendorBank, 'error': true, msg: $('#' + actid + '_VendorBank').attr('placeholder')})) {
                //         params.valid = false;
                //     }
                // }

                // if (params.Bank.VendorIFSC != '') {
                //     if (!validate({'id': '' + actid + '_VendorIFSC', 'type': 'IFSC', 'data': params.Bank.VendorIFSC, 'error': true, msg: $('#' + actid + '_VendorIFSC').attr('placeholder')})) {
                //         params.valid = false;
                //     }
                // }

                // if (params.Bank.VendorAccount != '') {
                //     if (!validate({'id': '' + actid + '_VendorAccount', 'type': 'ACCOUNT', 'data': params.Bank.VendorAccount, 'error': true, msg: $('#' + actid + '_VendorAccount').attr('placeholder')})) {
                //         params.valid = false;
                //     }
                // }

                // if (params.Bank.VendorBranch != '') {
                //     if (!validate({'id': '' + actid + '_VendorBranch', 'type': 'NAME', 'data': params.Bank.VendorBranch, 'error': true, msg: $('#' + actid + '_VendorBranch').attr('placeholder')})) {
                //         params.valid = false;
                //     }
                // }

                if (params.valid == true) {
                    $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                    var la = $(this).ladda();
                    la.ladda('start');

                    $.ajax({
                        method: 'POST',
                        url: 'Manage/VendorDataAdd',
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
                                $('#title_first').show();
                                $('#AddDivButton').show();
                                $('#TableView').show();
                                $('#AddView').html('').hide();
                                $('#VendorTableData').DataTable().ajax.reload();
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

            $('#AddBack').click(function (e) {  
                e.preventDefault();
                $('#title_first').show();
                $('#AddDivButton').show();
                $('#TableView').show();
                $('#AddView').html('').hide();
            });
        });



    /****************************************************End Edit Vendor Details*****************************************************/
    var KeyPress_Validation = function () {
                $(".VendorName,.VendorCode,.VendorLibrary,.CompanyInfo").on('keypress blur keyup keydown', function (e) {
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
    $(".BillingContactsTo,.BillingContactsCC,.SupportContactsTo,.SupportContactsCC").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Email.Full);
                var newregex = new RegExp(Regex.Email.Allowed);
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
            }
            else if (p.type == "CODE")
            {
                var _identifier_regex = Regex.Code;
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
            } else if (p.type == 'IFSC') {
                var _identifier_regex = Regex.BankIFSC.Full;
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
                } else {
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'ACCOUNT') {
                var _identifier_regex = Regex.BankAccount.Full;
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
        
	});
</script>

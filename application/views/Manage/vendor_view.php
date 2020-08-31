<?php
$Regex = All_Regex();
$bankArr = [];

?>
    <div class="col-lg-10">
        <div class="beneficiary-list">
            <div class="gray-header" style="margin-bottom: 10px;" id="title_first">Vendors</div>
                <div>
                <section class="transaction-history-wrp">
                    <h4 style="border-bottom: #d5d5d5 2px dotted; padding-bottom:10px;" id="PageLabel"><?php echo $Vendor['vendor_name'] ?> View</h4>
                    <div class="panel-body">
                        <div class="row" id="VendorViewDiv">
                            <div class="col-lg-12">

                                <div class="row">
                                    <div class="col-lg-12 view-details-card">

                                        <div class="row">
                                            <div class="col-lg-12 view-detail-top-header">
                                                <h4>Full Details</h4>
                                            </div>
                                        </div>

                                        <div class="row details-divider">
                                            <div class="col-lg-6">
                                                <label>Vendor Name</label>
                                                <p><?php echo $Vendor['vendor_name'] ?></p>
                                            </div>

                                            <div class="col-lg-6">
                                                <label>Vendor Code</label>
                                                <p><?php echo $Vendor['vendor_code'] ?></p>
                                            </div>
                                        </div>

                                        <div class="row details-divider">
                                            <div class="col-lg-6">
                                                <label>Vendor Library</label>
                                                <p><?php echo $Vendor['vendor_library'] ?></p>
                                            </div>

                                            <div class="col-lg-6">
                                                <label>Balance Check Api</label>
                                                <p><?php
                                                    if ($Vendor['bal_check_api'] == 1) {
                                                        echo 'Yes';
                                                    } else {
                                                        echo 'No';
                                                    }
                                                    ?></p>
                                            </div>
                                        </div>

                                        <div class="row details-divider">
                                            <div class="col-lg-6">
                                                <label>API Status</label>
                                                <p><?php
                                                    if ($Vendor['status_api'] == 1) {
                                                        echo 'Active';
                                                    } else {
                                                        echo 'InActive';
                                                    }
                                                    ?></p>
                                            </div>

                                            <!-- <div class="col-lg-6">
                                                <label>Vendor Type</label>
                                                <p><?php echo $Vendor['vendor_type'] ?></p>
                                            </div> -->
                                            <div class="col-lg-6">
                                                <label>Company Info</label>
                                                <p><?php echo $Vendor['company_info'] ?></p>
                                            </div>
                                        </div>

                                        <div class="row details-divider">


                                            <div class="col-lg-6">
                                                <label>Billing Contacts To</label>
                                                <p><?php echo $Vendor['billing_contacts_to'] ?></p>
                                            </div>
                                             <div class="col-lg-6">
                                                <label>Billing Contacts CC</label>
                                                <p><?php echo $Vendor['billing_contacts_cc'] ?></p>
                                            </div>

                                        </div>

                                        <div class="row details-divider">

                                            <div class="col-lg-6">
                                                <label>Support Contacts To</label>
                                                <p><?php echo $Vendor['support_contacts_to'] ?></p>
                                            </div>
                                             <div class="col-lg-6">
                                                <label>Support Contacts CC</label>
                                                <p><?php echo $Vendor['support_contacts_cc'] ?></p>
                                            </div>
                                        </div>

                                        <div class="row details-divider">

                                            <div class="col-lg-6">
                                                <label>Is Down</label>
                                                <p><?php
                                                    if ($Vendor['is_down'] == 1) {
                                                        echo 'No';
                                                    } else {
                                                        echo 'Yes';
                                                    }
                                                    ?></p>
                                            </div>
                                             <div class="col-lg-6">
                                                <label>Is Active</label>
                                                <p><?php
                                                    if ($Vendor['is_active'] == 1) {
                                                        echo 'Yes';
                                                    } else {
                                                        echo 'No';
                                                    }
                                                    ?></p>
                                            </div>
                                        </div>


                                    </div>
                                </div>


                                <div class="gray-header" id="title_first" style="margin-bottom: 10px;">
                                  <div class="float-left" style="margin-top:7px;">Vendor Service Config</div>
                                   <div class="float-right" id="AddDivButton">
                                    <button class="btn btn-primary btn-lgbtn btn-primary btn-md" style="float: right;" id="AddService">Add Service</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                     <div id="Pendg_Usr_Tbl">
                                    <table class="table datatables" id="VendorServiceConfigTable">
                                        <thead class="thead-blue">

                                        </thead>
                                    </table>
                                    </div>

                                </div>
                                   <!--  <div class="col-lg-12 transaction-history-wrp">
                                        <div class="col-lg-12 view-detail-top-header">
                                            <h4 class="float-left">Vendor Service Config</h4>
                                            <button class="float-right btn btn-primary btn-lgbtn btn-primary btn-md" id="AddService">Add Service</button>
                                        </div>
                                        <section>

                                            <table class="table datatable-basic"  id="VendorServiceConfigTable">

                                            </table>

                                        </section>
                                    </div> -->

                            </div>

                          <!--   <div class="col-lg-4">
                                <div class="view-details-card">
                                    <div class="row">
                                        <div class="col-lg-12 view-detail-top-header">
                                            <h4 class="float-left">Vender Bank</h4>
                                            <button class="float-right btn custom-blue-btn btn-sm" id="AddBank">Add Bank</button>
                                        </div>
                                    </div>

                                    <div class="row" id="ViewTable">
                                        <?php
                                        if (!empty($Bank)) {
                                            foreach ($Bank as $value) {
                                                $bankArr[$value['vnd_bank_id']] = $value;
                                                ?>
                                                <div class="col-lg-12 table-wrp mb-20">
                                                    <table class="table-set">
                                                        <tr>
                                                            <td><span style="font-weight: bold;">Bank Name :</span></td>
                                                            <td><?php echo $value['vendor_bank'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span style="font-weight: bold;">IFSC :</span></td>
                                                            <td><?php echo $value['vendor_ifsc'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span style="font-weight: bold;">Bank Branch :</span></td>
                                                            <td><?php echo $value['vendor_branch'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span style="font-weight: bold;">Account Number:</span></td>
                                                            <td><?php echo $value['vendor_account'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center"><button class="btn-primary btn BankEdit" data-Edit="<?php echo $value['vnd_bank_id'] ?>">Edit</button> <button class="custom-red-btn btn BankDelete" data-Delete="<?php echo $value['vnd_bank_id'] ?>">Delete</button></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="col-lg-12 table-wrp mb-20">
                                                <table class="table-set">
                                                    <tr style="text-align: center;">
                                                        <td><button class="btn-primary btn">No Bank Details</button></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>

                                    <div class="BankAdd" id="BankAdd">

                                    </div>

                                    <div class="BankAdd" id="BankEdit">

                                    </div>

                                </div>
                            </div> -->

                        </div>

                        <div id="VendorAddDiv">

                        </div>

                    </div>
                                </section>
                </div>


        </div>
    </div>
	<script type="text/javascript">
	 var VENDORVIEW = function () {
	 	  var serviceArr = {};
        var vendorServiceArrAll = {};
        var chargeType = {'FIXED': 'Fixed', 'PERCENT': 'Percent'};
        var chargeMethod = {'CREDIT': 'Credit', 'DEBIT': 'Debit'};

        var Regex = <?php echo json_encode($Regex) ?>

        var id = <?php echo $Vendor['vendor_id'] ?>

         var serviceArray = function () {
            $.ajax({
                method: 'POST',
                url: 'Manage/ServiceFetchAll',
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

                        $.each(response.data, function (k, v) {

                            serviceArr[v.service_id] = v;


                        });

                    }
                }
            }).fail(function (err) {
                throw err;
            });
        }

        var vendorServiceArr = function () {
            $.ajax({
                method: 'POST',
                url: 'Manage/VendorServiceFetchAll',
                dataType: 'JSON',
                data: {'data': id}
            }).done(function (response) {
                if (response)
                {
                    console.log(response)
                    if (response.error == 1)
                    {

                          toastr.error(response.error_desc);

                    } else if (response.error == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error == 0) {

                        $.each(response.data, function (k, v) {
                            vendorServiceArrAll[v.service_id] = v;
                        });
                    }
                }
            }).fail(function (err) {
                throw err;
            });
        }


           $('#AddBank').click(function (e) {
            e.preventDefault();

            var html = '';
            html += '<div class="row">';

            html += '<div class="col-md-6">';
            html += '<div class="form-group has-feedback">';
            html += '  <label>Vendor Bank</label>';
            html += '<input type="text" class="form-control input-lg VendorBank" placeholder="Vendor Bank" id="VendorAddBank_VendorBank">';
            html += '<span data-for="VendorAddBank_VendorBank"></span>';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-md-6">';
            html += '<div class="form-group has-feedback">';
            html += '  <label>Vendor IFSC</label>';
            html += '<input type="text" class="form-control input-lg VendorIFSC" placeholder="Vendor IFSC" id="VendorAddBank_VendorIFSC">';
            html += '<span data-for="VendorAddBank_VendorIFSC"></span>';
            html += '</div>';
            html += '</div>';

            html += ' </div>';

            html += '<div class="row">';

            html += '<div class="col-md-6">';
            html += '<div class="form-group has-feedback">';
            html += '  <label>Vendor Account</label>';
            html += '<input type="text" class="form-control input-lg VendorAccount" placeholder="Vendor Account" id="VendorAddBank_VendorAccount">';
            html += '<span data-for="VendorAddBank_VendorAccount"></span>';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-md-6">';
            html += '<div class="form-group has-feedback">';
            html += '  <label>Vendor Branch</label>';
            html += '<input type="text" class="form-control input-lg VendorBranch" placeholder="Vendor Branch" id="VendorAddBank_VendorBranch">';
            html += '<span data-for="VendorAddBank_VendorBranch"></span>';
            html += '</div>';
            html += '</div>';

            html += ' </div>';

            html += '<div class="row">';

            html += ' <div class="col-md-12">';
            html += ' <div class="form-group has-feedback text-center">';
            html += '  <button class="btn btn-default btn-lg" style="margin-top: 26px;width: 150px;" id="BankAddBack">Back</button>';
            html += '  <button type="submit" class="btn btn-primary btn-lg VendorAddBank" style="margin-top: 26px;width: 150px;" id="VendorAddBank">Proceed</button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            $('#AddBank').hide();
            $('#ViewTable').hide();
            $('#BankAdd').html(html).show();
            KeyPress_Validation();

            $('.VendorAddBank').click(function (e) {
                e.preventDefault();
                	console.log(params);
                var params = {'valid': true};

                var actid = $(this).attr('id');

                params.VendorBank = $('#' + actid + '_VendorBank').val();
                params.VendorIFSC = $('#' + actid + '_VendorIFSC').val();
                params.VendorAccount = $('#' + actid + '_VendorAccount').val();
                params.VendorBranch = $('#' + actid + '_VendorBranch').val();
                params.VendorId = id;

                if (!validate({'id': '' + actid + '_VendorBank', 'type': 'NAME', 'data': params.VendorBank, 'error': true, msg: $('#' + actid + '_VendorBank').attr('placeholder')})) {
                    params.valid = false;
                }

                if (!validate({'id': '' + actid + '_VendorIFSC', 'type': 'IFSC', 'data': params.VendorIFSC, 'error': true, msg: $('#' + actid + '_VendorIFSC').attr('placeholder')})) {
                    params.valid = false;
                }

                if (!validate({'id': '' + actid + '_VendorAccount', 'type': 'ACCOUNT', 'data': params.VendorAccount, 'error': true, msg: $('#' + actid + '_VendorAccount').attr('placeholder')})) {
                    params.valid = false;
                }

                if (!validate({'id': '' + actid + '_VendorBranch', 'type': 'NAME', 'data': params.VendorBranch, 'error': true, msg: $('#' + actid + '_VendorBranch').attr('placeholder')})) {
                    params.valid = false;
                }

                if (params.valid == true) {
                    $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                    var la = $(this).ladda();
                    la.ladda('start');

                    $.ajax({
                        method: 'POST',
                        url: 'Manage/VendorDataAddBank',
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

                                $('#AddBank').show();
                                $('#ViewTable').show();
                                $('#BankAdd').html('').hide();
                                window.location.reload(true);
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

            $('#BankAddBack').click(function (e) {
                e.preventDefault();
                $('#AddBank').show();
                $('#ViewTable').show();
                $('#BankAdd').html('').hide();
            });
        });


			  $('.BankEdit').click(function (e) {
            e.preventDefault();

            var vendorID = $(this).attr('data-Edit');

            if (vendorID in bankArr) {
                var html = '';
                html += '<div class="row">';

                html += '<div class="col-md-6">';
                html += '<div class="form-group has-feedback">';
                html += '  <label>Vendor Bank</label>';
                html += '<input type="text" class="form-control input-lg VendorBank" placeholder="Vendor Bank" id="VendorEditBank_VendorBank" value=' + bankArr[vendorID].vendor_bank + '>';
                html += '<span data-for="VendorEditBank_VendorBank"></span>';
                html += '</div>';
                html += '</div>';

                html += '<div class="col-md-6">';
                html += '<div class="form-group has-feedback">';
                html += '  <label>Vendor IFSC</label>';
                html += '<input type="text" class="form-control input-lg VendorIFSC" placeholder="Vendor IFSC" id="VendorEditBank_VendorIFSC" value=' + bankArr[vendorID].vendor_ifsc + '>';
                html += '<span data-for="VendorEditBank_VendorIFSC"></span>';
                html += '</div>';
                html += '</div>';

                html += ' </div>';

                html += '<div class="row">';

                html += '<div class="col-md-6">';
                html += '<div class="form-group has-feedback">';
                html += '  <label>Vendor Account</label>';
                html += '<input type="text" class="form-control input-lg VendorAccount" placeholder="Vendor Account" id="VendorEditBank_VendorAccount" value=' + bankArr[vendorID].vendor_account + '>';
                html += '<span data-for="VendorEditBank_VendorAccount"></span>';
                html += '</div>';
                html += '</div>';

                html += '<div class="col-md-6">';
                html += '<div class="form-group has-feedback">';
                html += '  <label>Vendor Branch</label>';
                html += '<input type="text" class="form-control input-lg VendorBranch" placeholder="Vendor Branch" id="VendorEditBank_VendorBranch" value=' + bankArr[vendorID].vendor_branch + '> ';
                html += '<span data-for="VendorEditBank_VendorBranch"></span>';
                html += '</div>';
                html += '</div>';

                html += ' </div>';

                html += '<div class="row">';

                html += ' <div class="col-md-12">';
                html += ' <div class="form-group has-feedback text-center">';
                html += '  <button class="btn btn-default btn-lg" style="margin-top: 26px;width: 150px;" id="BankEditBack">Back</button>';
                html += '  <button type="submit" class="btn btn-primary btn-lg VendorEditBank" style="margin-top: 26px;width: 150px;" id="VendorEditBank">Proceed</button>';
                html += '</div>';
                html += '</div>';
                html += '</div>';

                $('#AddBank').hide();
                $('#ViewTable').hide();
                $('#BankEdit').html(html).show();
                KeyPress_Validation();

                $('.VendorEditBank').click(function (e) {
                    e.preventDefault();

                    var params = {'valid': true};

                    var actid = $(this).attr('id');

                    params.VendorBank = $('#' + actid + '_VendorBank').val();
                    params.VendorIFSC = $('#' + actid + '_VendorIFSC').val();
                    params.VendorAccount = $('#' + actid + '_VendorAccount').val();
                    params.VendorBranch = $('#' + actid + '_VendorBranch').val();
                    params.BankId = bankArr[vendorID].vnd_bank_id;
                    params.VendorId = id;

                    if (!validate({'id': '' + actid + '_VendorBank', 'type': 'NAME', 'data': params.VendorBank, 'error': true, msg: $('#' + actid + '_VendorBank').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': '' + actid + '_VendorIFSC', 'type': 'IFSC', 'data': params.VendorIFSC, 'error': true, msg: $('#' + actid + '_VendorIFSC').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': '' + actid + '_VendorAccount', 'type': 'ACCOUNT', 'data': params.VendorAccount, 'error': true, msg: $('#' + actid + '_VendorAccount').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': '' + actid + '_VendorBranch', 'type': 'NAME', 'data': params.VendorBranch, 'error': true, msg: $('#' + actid + '_VendorBranch').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (params.valid == true) {
                        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                        var la = $(this).ladda();
                        la.ladda('start');

                        $.ajax({
                            method: 'POST',
                            url: 'Manage/VendorDataEditBank',
                            data: params,
                            dataType: 'JSON'
                        }).done(function (response) {
                            if (response)
                            {
                                if (response.error == 1)
                                {

                                } else if (response.error == 2)
                                {
                                    window.location.reload(true);
                                } else if (response.error == 0) {


                                    $('#AddBank').show();
                                    $('#ViewTable').show();
                                    $('#BankAdd').html('').hide();
                                    window.location.reload(true);
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

                $('#BankEditBack').click(function (e) {
                    e.preventDefault();
                    $('#AddBank').show();
                    $('#ViewTable').show();
                    $('#BankEdit').html('').hide();
                });
            }
        });


			$('.BankDelete').click(function (e) {
            e.preventDefault();

            var vendorID = $(this).attr('data-Delete');

            if (vendorID in bankArr) {
                $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                la.ladda('start');

                $.ajax({
                    method: 'POST',
                    url: 'Manage/VendorDataDeleteBank',
                    data: {data: bankArr[vendorID].vnd_bank_id},
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
                            window.location.reload(true);
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

			$('#AddService').click(function (e) {
            e.preventDefault();
            var html = '';
            html += '<div class="table-responsive table-scrollable">';
            html += '<table class="table">';
            html += '<thead>';
            html += '<tr>';
            html += '<th>Service Name</th>';
            html += '<th>Vendor Service Key</th>';
            html += '<th>Rate Charge Type</th>';
            html += '<th>Rate Charge Method</th>';
            html += '<th>Margin</th>';
            html += '<th>Capping Amount</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            $.each(serviceArr, function (k, v) {

                if (v.service_id in vendorServiceArrAll) {

                    var capAmt = vendorServiceArrAll[v.service_id].capping_amount ? vendorServiceArrAll[v.service_id].capping_amount : '';

                    html += '<tr>';

                    html += '<td><span class="ServiceName" id="AddVendorService_ServiceName' + v.service_id + '" data-service="' + v.service_id + '">' + v.service_name + ' (' + v.code + ')</span></td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control ServiceKey" id="AddVendorService_ServiceKey' + v.service_id + '" placeholder="Vendor Service Key"  value="' + vendorServiceArrAll[v.service_id].vendor_key + '">';
                    html += '<span data-for="AddVendorService_ServiceKey' + v.service_id + '"></span>';
                    html += '</div>';
                    html += '</td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<select name="select" class="form-control input-xs ChargeType" id="AddVendorService_ChargeType' + v.service_id + '">';
                    html += '<option value="">Select Charge Type</option>';
                    $.each(chargeType, function (k1, v1) {
                        var sel = (k1 == vendorServiceArrAll[v.service_id].rate_charge_type) ? 'selected' : '';
                        html += '<option value="' + k1 + '" ' + sel + '>' + v1 + '</option>';
                    });
                    html += '</select>';
                    html += '<span data-for="AddVendorService_ChargeType' + v.service_id + '"></span>';
                    html += '</div>';
                    html += '</td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<select name="select" class="form-control input-xs ChargeMethod" id="AddVendorService_ChargeMethod' + v.service_id + '">';
                    html += '<option value="">Select Charge Method</option>';
                    $.each(chargeMethod, function (k2, v2) {
                        var sel2 = (k2 == vendorServiceArrAll[v.service_id].rate_charge_method) ? 'selected' : '';
                        html += '<option value="' + k2 + '" ' + sel2 + '>' + v2 + '</option>';
                    });
                    html += ' </select>';
                    html += '<span data-for="AddVendorService_ChargeMethod' + v.service_id + '"></span>';
                    html += '</div>';
                    html += ' </td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control Margin" placeholder="Margin" id="AddVendorService_Margin' + v.service_id + '" value="' + vendorServiceArrAll[v.service_id].margin + '">';
                    html += '<span data-for="AddVendorService_Margin' + v.service_id + '"></span>';
                    html += '</div>';
                    html += '</td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control CappingAmount" placeholder="Capping Amount" id="AddVendorService_CappingAmount' + v.service_id + '" value="' + capAmt + '">';
                    html += '<span data-for="AddVendorService_CappingAmount' + v.service_id + '"></span>';
                    html += '</div>';
                    html += ' </td>';

                    html += '</tr>';
                } else {
                    html += '<tr>';

                    html += '<td><span class="ServiceName" id="AddVendorService_ServiceName' + v.service_id + '" data-service="' + v.service_id + '">' + v.service_name + ' (' + v.code + ')</span></td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control ServiceKey" id="AddVendorService_ServiceKey' + v.service_id + '" placeholder="Vendor Service Key">';
                    html += '<span data-for="AddVendorService_ServiceKey' + v.service_id + '"></span>';
                    html += '</div>';
                    html += '</td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<select name="select" class="form-control input-xs ChargeType" id="AddVendorService_ChargeType' + v.service_id + '">';
                    html += '<option value="">Select Charge Type</option>';
                    $.each(chargeType, function (k, v) {
                        html += '<option value="' + k + '">' + v + '</option>';
                    });
                    html += '</select>';
                    html += '<span data-for="AddVendorService_ChargeType' + v.service_id + '"></span>';
                    html += '</div>';
                    html += '</td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<select name="select" class="form-control input-xs ChargeMethod" id="AddVendorService_ChargeMethod' + v.service_id + '">';
                    html += '<option value="">Select Charge Method</option>';
                    $.each(chargeMethod, function (k, v) {
                        html += '<option value="' + k + '">' + v + '</option>';
                    });
                    html += ' </select>';
                    html += '<span data-for="AddVendorService_ChargeMethod' + v.service_id + '"></span>';
                    html += '</div>';
                    html += ' </td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control Margin" placeholder="Margin" id="AddVendorService_Margin' + v.service_id + '">';
                    html += '<span data-for="AddVendorService_Margin' + v.service_id + '"></span>';
                    html += '</div>';
                    html += '</td>';

                    html += '<td>';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control CappingAmount" placeholder="Capping Amount" id="AddVendorService_CappingAmount' + v.service_id + '">';
                    html += '<span data-for="AddVendorService_CappingAmount' + v.service_id + '"></span>';
                    html += '</div>';
                    html += ' </td>';

                    html += '</tr>';
                }
            });
            html += '</tbody>';

            html += '<tfoot>';
            html += '<tr>';
            html += '<td colspan="7" style="text-align: center;">';
            html += '<button class="btn btn-default " id="BackAddVendorService" style="margin-right: 10px;"><i class="fas fa-chevron-left position-left"></i>Back</button>';
            html += '<button class="btn btn-primary" id="AddVendorService">Submit</button>';
            html += '</td>';
            html += '</tr>';
            html += '</tfoot>';
            html += '</table>';
            html += '</div>';

            $('#PageLabel').html('Vendor Service Add');
            $('#VendorViewDiv').hide();
            $('#VendorAddDiv').html(html).show();

            KeyPress_Validation();

            $('#AddVendorService').click(function (e) {
                e.preventDefault();

                var params = {'valid': true};
                var actid = $(this).attr('id');
                var VendorId={};
                params.service = {};


                $.each(serviceArr, function (k, v) {

                    var ServiceKey = $('#' + actid + '_ServiceKey' + v.service_id + '').val();
                    var ChargeType = $('#' + actid + '_ChargeType' + v.service_id + ' option:selected').val();
                    var ChargeMethod = $('#' + actid + '_ChargeMethod' + v.service_id + ' option:selected').val();
                    var Margin = $('#' + actid + '_Margin' + v.service_id + '').val();
                    var CappingAmount = $('#' + actid + '_CappingAmount' + v.service_id + '').val();
                    var ServiceName = $('#' + actid + '_ServiceName' + v.service_id + '').data('service');

                    if (ServiceKey != '' || ChargeType != '' || ChargeMethod != '' || Margin != '') {
                        params.service[v.service_id] = {'ServiceName': ServiceName, 'ServiceKey': ServiceKey, 'ChargeType': ChargeType, 'ChargeMethod': ChargeMethod, 'Margin': Margin, 'CappingAmount': CappingAmount, 'ServiceKeyDivId': '' + actid + '_ServiceKey' + v.service_id + '',
                            'ChargeTypeDivId': '' + actid + '_ChargeType' + v.service_id + '', 'ChargeMethodDivId': '' + actid + '_ChargeMethod' + v.service_id + '', 'MarginDivId': '' + actid + '_Margin' + v.service_id + '',
                            'CappingAmountDivId': '' + actid + '_CappingAmount' + v.service_id + '', 'ServiceNameDivId': '' + actid + '_ServiceName' + v.service_id + ''};
                    }
                });

                $.each(params.service, function (key, value) {

                    if (!validate({'id': '' + value.ServiceKeyDivId + '', 'type': 'CODE', 'data': value.ServiceKey, 'error': true, msg: $('#' + value.ServiceKeyDivId + '').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': '' + value.ChargeTypeDivId + '', 'type': 'CHARGETYPE', 'data': value.ChargeType, 'error': true, msg: $('#' + value.ChargeTypeDivId + '').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': '' + value.ChargeMethodDivId + '', 'type': 'CHARGEMETHOD', 'data': value.ChargeMethod, 'error': true, msg: $('#' + value.ChargeMethodDivId + '').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (!validate({'id': '' + value.MarginDivId + '', 'type': 'RATE', 'data': value.Margin, 'error': true, msg: $('#' + value.MarginDivId + '').attr('placeholder')})) {
                        params.valid = false;
                    }

                    if (value.CappingAmount != '') {
                        if (!validate({'id': '' + value.CappingAmountDivId + '', 'type': 'RATE', 'data': value.CappingAmount, 'error': true, msg: $('#' + value.CappingAmountDivId + '').attr('placeholder')})) {
                            params.valid = false;
                        }
                    }
                });

                params.VendorId = id;


                console.log(params.VendorId);

                if (params.valid == true) {
                    $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                    var la = $(this).ladda();
                    la.ladda('start');

                    $.ajax({
                        method: 'POST',
                        url: 'Manage/VendorServiceConfigAdd',
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
                                $('#PageLabel').html('<?php echo $Vendor['vendor_name'] ?> View');
                                $('#VendorViewDiv').show();
                                $('#VendorAddDiv').html('').hide();
                                $('#VendorServiceConfigTable').DataTable().ajax.reload();
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

            $('#BackAddVendorService').click(function (e) {
                e.preventDefault();
                $('#PageLabel').html('<?php echo $Vendor['vendor_name'] ?> View');
                $('#VendorViewDiv').show();
                $('#VendorAddDiv').html('').hide();
            });

        });

        var VendorServiceConfig = function () {
            var Datatable = $('#VendorServiceConfigTable').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/VendorServiceConfigFetch",
                    type: 'post',
                    data: {data: id},
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
                responsive: true,
                order: [],
                columns: [
                    {title: 'Service Name', class: 'all compact',
                        render: function (data, type, full, meta) {
                            return full.service_name + ' (' + full.code + ')';
                        }
                    },
                    {title: 'Vendor Service Key', class: 'all compact',
                        render: function (data, type, full, meta) {
                            return full.vendor_key;
                        }
                    },
                    {title: 'Rate Charge Type', data: 'is_down', class: 'all',
                        render: function (data, type, full, meta) {
                            return full.rate_charge_type;
                        }
                    },
                    {title: 'Rate Charge Method', data: 'is_active', class: 'all',
                        render: function (data, type, full, meta) {
                            return full.rate_charge_method;
                        }
                    },
                    {title: 'Margin', data: 'bal_check_api', class: 'all text-center compact',
                        render: function (data, type, full, meta) {
                            return full.margin;
                        }
                    },
                    {title: 'Capping Amount', class: 'all compact',
                        render: function (data, type, full, meta) {
                            return full.capping_amount;
                        }
                    }
                ],
                // "createdRow": function (row, data, dataIndex) {
                //     if (data.is_active == 0 && data.is_blocked == 1) {
                //         $('td', row).addClass('text-warning');
                //     } else if (data.is_active == 0) {
                //         $('td', row).addClass('text-danger');
                //     }
                // },
                // dom: '<"datatable-header header-my search-data"Bfl>r<"datatable-scroll"t><"datatable-footer"ip>',
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

            // $('.datatable-header').each(function () {
            //     $(this).append('<div class="row btn-row-set"><div class="btn-grp-set"><button class="btn btn-default">CSV</button></div> </div>')
            // });
        }

		  var KeyPress_Validation = function () {

            $(".VendorAccount").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.BankAccount.Full);
                var newregex = new RegExp(Regex.BankAccount.Allowed);
                if (str == '') {
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

              $(".VendorIFSC").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.BankIFSC.Full);
                var newregex = new RegExp(Regex.BankIFSC.Allowed);
                if (str == '') {
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
            $(".VendorBank,.VendorBranch").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Name);
                var newregex = new RegExp(Regex.Name);
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

            $('.ChargeType').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                var chargeType = {'FIXED': 'FIXED', 'PERCENT': 'PERCENT'};

                if (val == '') {
                    helpBlck({id: id, 'action': 'remove'});
                }

                if (val in chargeType)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Rate Charge Type', 'type': 'error'});
                }
            });

            $('.ChargeMethod').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                var chargeMethod = {'CREDIT': 'CREDIT', 'DEBIT': 'DEBIT'};

                if (val == '') {
                    helpBlck({id: id, 'action': 'remove'});
                }

                if (val in chargeMethod)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Rate Charge Method', 'type': 'error'});
                }
            });

            $(".ServiceKey").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Code);
                var newregex = new RegExp(Regex.Code);
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

            $(".Margin,.CappingAmount").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
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
            } else if (p.type == 'CHARGETYPE') {

                var chargeType = {'FIXED': 'FIXED', 'PERCENT': 'PERCENT'};

                if (p.data != "" && p.data in chargeType)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Rate Charge Type', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Rate Charge Type', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'CHARGEMETHOD') {

                var chargeMethod = {'CREDIT': 'CREDIT', 'DEBIT': 'DEBIT'};

                if (p.data != "" && p.data in chargeMethod)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Rate Charge Method', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Rate Charge Method', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'RATE') {
                var _identifier_regex = Regex.Rate;
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
                    if (p.error == true)
                    {
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




	 return {
            init: function () {
                VendorServiceConfig();
                serviceArray();
                vendorServiceArr();
            }
        };
    }();
    $(document).ready(function () {
        VENDORVIEW.init();
    });
</script>

<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$userDetails = get_user_details();

?>   
    <section class="width-100 money-remmitance-section mt-20">
    <div class="container">
    <div class="row">
    <div class="col-12">
    <div class="money-remmitance-section-outer-col width-100">
    <div class="width-100 section-top-subheading mb-3"><h6 class="dark-txt fontbold"><?php echo $link; ?></h6></div> 
    <!-- Content area -->
    <div class="content">
        <!--start of section-->
        <div class="row mt-15">
            <div class="col-lg-12">
                <section class="transaction-history-wrp">
                    <div class="panel-body">
                        <div class="col-lg-12" style="margin-bottom: 15px;">
                           <form id="pyment_req_form">
                            <div class="form-wrp"> 
                                <div class="row">
                                    <div class="col-md-6" id="SelectMenu">
                                        <div class="form-group">
                                            <label>Bank Name<span class="mandotry">*</span></label>
                                            <select name="select" class="form-control input-xs BankName" id="PaymentRequest_BankName">
                                            <option value="">Select Bank Name</option>
                                            </select>
                                            <span data-for="PaymentRequest_BankName"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none" id="NoSelect">
                                        <div class="form-group" id="inputBoxShow">

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Payment Mode<span class="mandotry">*</span></label>
                                        <select name="select" class="form-control input-xs PaymentMode" id="PaymentRequest_PaymentMode">
                                                <option value="">Select Payment Mode</option>
                                                <option value="IMPS">IMPS</option>
                                                <option value="NEFT">NEFT</option>
                                                <option value="CASH">CASH</option>
                                                <option value="RTGS">RTGS</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="OTHERS">OTHERS</option>
                                            </select>
                                            <span data-for="PaymentRequest_PaymentMode"></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">



                                        <div class="form-group">
                                            <label>Amount<span class="mandotry">*</span></label>
                                            <input type="text" class="form-control input-lg Amount" placeholder="Amount" id="PaymentRequest_Amount">
                                            <span data-for="PaymentRequest_Amount"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Reference Number<span class="mandotry">*</span></label>
                                            <input type="text" class="form-control input-lg BankReferenceNumber" placeholder="Bank Reference Number" id="PaymentRequest_BankReferenceNumber">
                                            <span data-for="PaymentRequest_BankReferenceNumber"></span>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Remark<span class="mandotry">*</span></label>
                                            <input type="text" class="form-control input-lg Remark" placeholder="Remark" id="PaymentRequest_Remark">
                                            <span data-for="PaymentRequest_Remark"></span>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label class="fontbold">File</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="PaymentRequest_File" name="PaymentRequest_File">
                                             <label class="custom-file-label" for="customFile">Choose file</label>
                                               <span data-for="PaymentRequest_File"></span>
                                        </div>


                                        
                                       
                                        </div>



                                  
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback text-center">
                                            <button type="submit" class="btn btn-primary btn-lg PaymentRequest" style="margin-top: 26px;width: 150px;" id="PaymentRequest">Proceed</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        </div>

                        <div class="col-lg-12">
                           
                            <div class="width-100 mb-3 bord-bottom gray-header"><h6 class="dark-txt fontbold">Payment Request List</h6></div> 
                            <div class="col-lg-12 transaction-history-wrp">
                                <section>
                                    <table class="table datatable-basic"  id="PaymentRequestList"></table>   

                                </section>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
        <!--end of section-->
    </div>
    <!-- /content area -->


                    </div>
                </div>
            </div>
        </div>
    </section>


<script>
 var PAYMENTREQUEST = function () {
    
    var Regex = <?php echo json_encode($Regex); ?>

    
     var Mode = {'IMPS': 'IMPS', 'NEFT': 'NEFT', 'CASH': 'CASH', 'OTHERS': 'OTHERS'};

        var bankOpr = {};

        var bankNameDefined = function () {
            $.ajax({
                url: 'Manage/Bank_fr_dffrnt_role', 
                type: "POST",
                dataType: 'json',
                success: function (response) { 
                    if (response) {
                     
                        if (response.error == 0) {
                            var str = '';
                               
                                <?php if($userDetails['parent_id']==0){?>
                                str += '<option value="">Select Bank Name</option>';
                                $.each(response.data, function (k, v) {
                                    bankOpr[v.tableauto_id] = v;

                                    str += '<option value="' + v.tableauto_id + '">' + v.bank_name + '</option>';

                                });
                                $('#PaymentRequest_BankName').html(str);
                                $('#NoSelect').hide();
                                $('#inputBoxShow').html('').hide();
                                $('#SelectMenu').show();

                            <?php } else { ?>

                                str += '<input class="form-control input-lg" type="text" id="PaymentRequest_BankName" value="' + response.data.ParentFirstName + ' ' + response.data.ParentLastName + ' (' + response.data.ParentMobile + ') \'s Bank" disabled style="margin-top: 22px;">';
                                $('#NoSelect').show();
                                $('#inputBoxShow').html(str).show();
                                $('#SelectMenu').remove();

                           <?php } ?>

                        } else if (response.error == 2) {

                            window.location.reload(true);

                        } else {
                           
                            toastr.error(response.error_desc)
                        }
                    }
                }, error: function (err) {
                    throw err;
                }
            });
        }

        var PaymentRequestUserList = function () {

        var Datatable = $('#PaymentRequestList').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/PaymentRequestList",
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

                    // {"title" : "","class": "details-control",
                    //  "orderable": false,
                    //    "render": function ( data, type, row, meta ) {
                    //       return null;
                    //     }
                    // },

                     {
                     "title" : "Bank Name",
                    
                     "data": "bank_name",
                    },

                    {
                     "title" : "Payment Mode",
                    
                     "data": "payment_mode",
                    },

                    {
                     "title" : "Amount",
                    
                     "data": "amount",
                    },

                    {
                     "title" : "Bank Ref. No.",
                    
                     "data": "bank_ref_no",
                    },

                     {
                     "title" : "Remark",
                    
                     "data": "remarks",
                    },

                    {
                     "title" : "Request Date",
                    
                     "data": "request_date",
                    },
                    {title: 'Status', class: 'all',
                        render: function (data, type, full, meta) {
                            var st = '';
                            if (full.status == 'PENDING') {
                                st += '<span class="label label-warning label-roundless">' + full.status + '</span>';
                            } else if (full.status == 'APPROVED') {
                                st += '<span class="label label-success label-roundless">' + full.status + '</span>';
                            } else if (full.status == 'REJECTED') {
                                st += '<span class="label label-danger label-roundless">' + full.status + '</span>';
                            } else {
                                st += '<span class="label label-danger label-roundless">Error</span>';
                            }
                            return st;
                        }
                    },
                    {title: 'File', class: 'none',
                        render: function (data, type, full, meta) {
                            var str = '';
                            if (full.is_file == 1) {
                                str += '<a href="' + full.file_path + '" class="btn btn-space btn-primary btn-sm btn-primary btn-sm" target="_blank">View</a>';
                            } else {
                                str += '<span class="label label-danger label-roundless">N/A</span>';
                            }
                            return str;
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

        }

        $('#PaymentRequest').click(function (e) {
            e.preventDefault();

            var params = {'valid': true};
            var actid = $(this).attr('id');

            var data = new FormData();

            params.BankName = $('#' + actid + '_BankName').val();
            params.PaymentMode = $('#' + actid + '_PaymentMode option:selected').val();
            params.Amount = $('#' + actid + '_Amount').val();
            params.BankReferenceNumber = $('#' + actid + '_BankReferenceNumber').val();
            params.Remark = $('#' + actid + '_Remark').val();
            var file3 = $('#' + actid + '_File')[0]['files'][0];
            if (!jQuery.isEmptyObject(bankOpr)) {
                if (!validate({'id': '' + actid + '_BankName', 'type': 'BANK', 'data': params.BankName, 'error': true, msg: $('#' + actid + '_BankName').attr('placeholder')})) {
                    params.valid = false;
                }
            }

            if (!validate({'id': '' + actid + '_PaymentMode', 'type': 'MODE', 'data': params.PaymentMode, 'error': true, msg: $('#' + actid + '_PaymentMode').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_Amount', 'type': 'AMOUNT', 'data': params.Amount, 'error': true, msg: $('#' + actid + '_Amount').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_BankReferenceNumber', 'type': 'TEXT', 'data': params.BankReferenceNumber, 'error': true, msg: $('#' + actid + '_BankReferenceNumber').attr('placeholder')})) {
                params.valid = false;
            }

            if (!validate({'id': '' + actid + '_Remark', 'type': 'TEXT', 'data': params.Remark, 'error': true, msg: $('#' + actid + '_Remark').attr('placeholder')})) {
                params.valid = false;
            }

            if (params.valid == true) {
                $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                var la = $(this).ladda();
                la.ladda('start');

                data.append('BankName', params.BankName);
                data.append('PaymentMode', params.PaymentMode);
                data.append('Amount', params.Amount);
                data.append('BankReferenceNumber', params.BankReferenceNumber);
                data.append('Remark', params.Remark);
                data.append('File', file3);

                $.ajax({
                    method: 'POST',
                    url: 'Manage/payment_req',
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

                              $("#pyment_req_form")[0].reset();
                              
                             $('input[type="file"]').closest('.custom-file').find('.custom-file-label').html('No file selected');
                             la.ladda('stop');

                            
                        }
                        la.ladda('stop');
                    }
                });
            }
        });

    var KeyPress_Validation = function () {
      

            $('.BankName').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

              

                if (val in bankOpr)
                {
                    helpBlck({id: id, 'action': 'remove'});

                } else {

                    helpBlck({'id': id, 'msg': 'Invalid Bank Name', 'type': 'error'});
                }
            });
            $('.PaymentMode').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();
                if (val in Mode)
                {
                    helpBlck({id: id, 'action': 'remove'});

                } else {

                    helpBlck({'id': id, 'msg': 'Invalid Payment Mode', 'type': 'error'});
                }
            });

         
            $(".Amount").on('keypress blur keyup keydown', function (e) {
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
                                id: id, 'action': 'remove'});
                        }
                    } else {
                        helpBlck({
                            'id': id, 'msg': msg + ' Is Required', 'type': 'error'
                        });
                    }
                }
            });
            $(".BankReferenceNumber").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Text);
                var newregex = new RegExp(Regex.Text);
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



            $(".Remark").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Text);
                var newregex = new RegExp(Regex.Text);
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

            if (p.type == 'BANK') {
                if (p.data != "" && p.data in bankOpr)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Bank Name', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Bank Name', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'MODE') {
                if (p.data != "" && p.data in Mode)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Payment Mode', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Payment Mode', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'AMOUNT') {
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
                    if (p.error == true) {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': p.msg + ' Is Required', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid ' + p.msg, 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'TEXT') {
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
                 bankNameDefined();
                 KeyPress_Validation();
                 PaymentRequestUserList();
            }
        };
    }();   
    $(document).ready(function () {
        PAYMENTREQUEST.init();
    });
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
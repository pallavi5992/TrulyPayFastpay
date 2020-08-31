
  var TopUpsOrder = function () {
    $('<link href="assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css">').insertAfter('#endglobal');
    var _tgt;
    var currenttime = function ()
    {
        var today = new Date();
        return today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
    }

    var prevtime = function ()
    {
        var today = new Date();
        return today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + (today.getDate() - 1);
    }

    var oldExportAction = function (self, e, dt, button, config) {
        if (button[0].className.indexOf('buttons-csv') >= 0) {
            if ($.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)) {
                $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config);
            }
            else {
                $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            }
        } else if (button[0].className.indexOf('buttons-print') >= 0) {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        }
    };
    var newExportAction = function (e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function (e, s, data) {
            // Just this once, load all data from the server...
            data.start = 0;
            data.length = -1;
            dt.one('preDraw', function (e, settings) {
                // Call the original action function
                oldExportAction(self, e, dt, button, config);
                dt.one('preXhr', function (e, s, data) {
                    // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                    // Set the property to what it was before exporting.
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });
                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);
                // Prevent rendering of the full data to the DOM
                return false;
            });
        });
        // Requery the server with the new one-time export settings
        dt.ajax.reload();
    };
    var MyOrder = function () {
        var checked_item = [];
        var checkedfieldmy = {};
        var imy;
        var v;
        var indexmy;
        var arrayDate = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        var today = new Date();
        var fromformat = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
        var selectedTomy = currenttime();
        var selectedFrommy = fromformat;
		   var oTable = $('#transactiontable').DataTable({
            "processing": true,
            "serverSide": true,
            order: [],
            "ajax": {
                 url: "BillPayments/BillPayment_Txn_Re",
                type: "POST",
                data: function (data) {
                    if (selectedFrommy && selectedTomy) {
                        data.from = selectedFrommy;
                        data.to = selectedTomy;
                    }
                    if (!$.isEmptyObject(v)) {
                        data.cell = v;
                    }
                },
            },
            language: {
                aria: {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                processing: '<span>&nbsp;&nbsp;<i class="icon-spinner4 spinner position-left"></i> LOADING...</span>'
            },
//            columnDefs: [{
//                    targets: 0,
//                    orderable: false,
//                }],
            select: {
                style: 'multi'
            },
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
                    {extend: 'csv',
                        action: newExportAction
                    },
                ]
            },
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columns: [
                 {title: '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input type="checkbox" class="group-checkable" data-set="#transactiontable .checkboxes" /><span></span></label>', render: function (data, type, full, meta)
                            {
                                if (full.status == 'SUCCESS') {
                                    var taxR = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">';
                                    taxR += ' <input type="checkbox" class="checkboxes styled" />';
                                    taxR += '<span></span>';
                                    taxR += '</label>';
                                } else {
                                    var taxR = '';
                                }

                                return taxR;
                            },
                            "orderable": false,
                            class: 'all'
                        },
                        {title: '<span>TXN Id</span>', class: 'all', render: function (data, type, full, meta)
                            {
                                var taxR;

                                if (full.status == 'SUCCESS') {
                                    taxR = '<span>' + full.fstpytxn_id + '</span><br /><span><a class="bcomplnt" href="javascript:void(0);" id="bcomplnt" data-nid="' + full.id + '">Book Compliant</a></span>';
                                } else {
                                    taxR = '<span>' + full.fstpytxn_id + '</span>'
                                }

                                return taxR;
                            }},
                        {title: '<span>Opr Ref</span>', data: 'opr_ref_no', class: 'all'},
                        {title: '<span>Mobile/Connection</span>', data: 'customer_no', class: 'all'},
                        {title: '<span>Service Name</span>', data: 'servicename', class: 'all'},
                        {title: '<span>Service Type</span>', data: 'servicetype', class: 'all'},
                        {title: '<span>Amount (&#8377;)</span>', data: 'transamt', class: 'all'},
                        {title: '<span>Charged Amount (&#8377;)</span>', data: 'chargeamt', class: 'all'},
                        {title: '<span>Current Amount (&#8377;)</span>', data: 'closingbal', class: 'all'},
                        {title: '<span>Date/Time</span>', data: 'req_dt', class: 'all'},
                        {title: '<span>Status</span>', data: 'status', class: 'all'},
            ],
            columnDefs: [{targets: 10,
                    render: function (t, e, a, n) {

                        var s = {
                            SUCCESS: {
                                title: "SUCCESS",
                                class: "btn btn-success"
                            },
                            PENDING: {
                                title: "PENDING",
                               class: "btn btn-pending"
                            },
                            FAILED: {
                                title: "FAILED",
                                class: "btn btn-danger"
                            },
                            REVIEW: {
                                title: "REVIEW",
                                class: "btn btn-info"
                            },
                            REFUND: {
                                title: "REFUND",
                                class: "btn btn-danger"
                            },
                        };
                        return void 0 === s[t] ? t : '<span class="' + s[t].class + '">' + s[t].title + "</span>"
                    }
                }],
            "lengthMenu": [
                [5, 20, 50, 100],
                [5, 20, 50, 100] // change per page values here
            ],
              buttons: [
            {extend: 'csv', className: 'btn-secondary',title: 'MoneyRemittance Transaction List'},
            {extend: 'pdf', className: 'btn-secondary',title: 'MoneyRemittance Transaction List'},
            {extend: 'excel', className: 'btn-secondary',title: 'MoneyRemittance Transaction List'},
        
            ],
            dom: '<"datatable-header header-my search-data"fBl>r<"table-scrollable"t><"datatable-footer"ip>',
        });

    $('#transactiontable_length').append('<button id="printtd" class="btn btn-brand ml-15" type="button" style="float: right;"><i class="icon-printer2 position-left" title="Print" aria-hidden="true"></i>Print</button>');

        $('#transactiontable_filter label').hide();

			  var head =' <div id="transaction-history" class="width-100   mt-3">';
		  
		head += '<div class="card">';
		   head += '<div class="card-header" id="headingOne">';
			head += '<h5 class="mb-0 float-left">';
head += '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: #333;">SEARCH YOUR DETAILS';
			head += '</button>';
			head += '</h5>';
			head += '</div>';
		head += '<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" >';
		head += '<div class="card-body" id="input_search" style="width: 100%;display: inline-block;">';
			head += ' </div>';
		head += '<div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">';
		head += '<button type="submit" class="btn btn-primary legitRipple srchsubmy" id="srchsubmy">Search</button>';
        head += '<button type="button" id="my_reset" class="btn btn-circle grey-salsa btn-outline">Reset</button>';
		head += '</div>';
		head += '</div>';
		head += ' </div>';
		head += ' </div>';

    
		  $('#transactiontable_filter').append(head); 
		  
		
			  var top ='<div class="row mt-3">';
			  top += '<div class="col-lg-6">';
			  top += '<label>From</label>';
			  top += '<input type="text" class="form-control" id="frommy" readonly>';
			  top += '</div>';
			  top += '<div class="col-lg-6">';
			  top += '<label>To</label>';
			  top += '<input class="form-control"  type="text" id="tomy" readonly>';
			  top += '</div>';
			  
			  top += '</div>';
	
			  $('.header-my').append(top);
    
        $('#show_in_filter').click(function () {
            $('#myorder_testfle').slideToggle();
        });

        var year = (new Date).getFullYear();
        var month = (new Date).getMonth();
        $('#frommy').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '0d',
            startDate: '-3m',
            autoclose: true,
            todayHighlight: true
        }).on('hide', function (e) {

            var startDate_new = $('#frommy').datepicker('getFormattedDate');
            var expldestrdt = startDate_new.split('-');
            var startDate = new Date(expldestrdt[0], expldestrdt[1] - 1, expldestrdt[2]);
            var currentdate = new Date();
            var mo = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
            $("#tomy").datepicker("setStartDate", startDate);
            if (startDate.getMonth() == currentdate.getMonth()) {

                $("#tomy").datepicker("setEndDate", '0d');
                $("#tomy").datepicker("setDate", '0d');
                selectedTomy = currenttime();
            } else {
                month = mo.getFullYear() + '-' + (mo.getMonth() + 1) + '-' + mo.getDate();
                $("#tomy").datepicker("setEndDate", mo);
                $("#tomy").datepicker("setDate", mo);
                selectedTomy = month;
            }
            selectedFrommy = startDate_new;
            if (selectedTomy) {
                $('.srchsubmy').trigger('click');
            }

        });

        $('#tomy').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '0d',
            autoclose: true,
            startDate: '0d'
        }).on('hide', function (e) {
            selectedTomy = $('#tomy').datepicker('getFormattedDate');
            selectedTomy = selectedTomy;
            if (selectedFrommy) {
                $('.srchsubmy').trigger('click');
            }
        });

        $('#frommy,#tomy').datepicker('setDate', '0d');

        var nRow = $('#transactiontable thead tr')[0];
        $.each(nRow.cells, function (i, v) {

            if (v.cellIndex != 0) {
                if (v.cellIndex == 12) {
                    $.post('Recharge/selectMenu', {cell_id: v.cellIndex, type: 'REMITTANCE'}, function (response) {
                        if (response) {
                            if (response.error == 2) {
                                window.location.reload(true);
                            } else if (response.error == 0) {
                                var str = '';
                                $.each(response.msg, function (i, v) {
                                    str += '<option value = "' + v.name + '">' + v.name + '</option>';
                                });
                              $('#input_search').append('<div class="col-md-4 col-sm-6"><div id="inputser"><div class="form-group"><select class="custom-select inputser" data-value = "' + v.cellIndex + '" title="' + v.innerText + '" data-width="100%" ><option value="">ALL</option>' + str + '</select></div></div></div>'); 
							   /* $('#input_search').append('<div id="inputser"><div class="form-row"><div class="form-group col-lg-6"><SELECT class="custom-select inputser" data-value = "' + v.cellIndex + '" title="' + v.innerText + '" data-width="100%" ><OPTION>ALL</OPTION><OPTION>' + str + '</OPTION></SELECT></div></div></div>'); */
                                $('.bootstrap-select').selectpicker();
                            } else {
                                console.log(response.error_desc);
                            }
                        }
                    }, 'json').fail(function (err) {
                        throw err;
                    });

                } else {
                    $('#input_search').append('<div class="col-md-4 col-sm-6"><div  id="inputser"><div class="form-group"><input type="text" class="form-control inputser" data-value = "' + v.cellIndex + '" placeholder="' + v.innerText + '"></div></div></div>'); 
				    /* $('#input_search').append('<div id="inputser"><div class="form-row"><div class="form-group col-lg-6"><input type="text" class="form-control inputser" data-value = "' + v.cellIndex + '" placeholder="' + v.innerText + '"></div></div></div>');  */

                }
            }
        });

        $('.srchsubmy').on('click', function () {
            v = $(".inputser").map(function () {
                if ($(this).val() != '') {
                    return {cell: $(this).val(), cellIndex: $(this).data('value')};
                }
            }).get();

            if (!$.isEmptyObject(v)) {
                if (selectedFrommy && selectedTomy) {
                    oTable.draw();
                } else {
                    
                      toastr.error("choose a valid filter.")
                }
            } else {
                if ((selectedFrommy != "" || selectedFrommy != null) && (selectedTomy != "" || selectedTomy != null)) {
                    oTable.draw();
                } else {
                    
                    toastr.error("choose a valid Date range.");
                }
            }
        });

        $('#my_reset').on('click', function () {
            selectedFrommy = '';
            selectedTomy = '';
            v = {};
            if ($('.inputser').val('')) {
                selectedTomy = currenttime();
                selectedFrommy = fromformat;
                $('#frommy').datepicker("setDate", '0d');
                $("#tomy").datepicker("setEndDate", '0d');
                $("#tomy").datepicker("setStartDate", '0d');
                $('#tomy').datepicker("setDate", '0d');
                $('.inputser').trigger('change');
                oTable.draw();
            }
        });

        oTable.on('change', '.group-checkable', function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("selected");
                    checked_item = $(set + ':checked').map(function () {
                        var printtdata = $(this).closest('tr');
                        var showtd = oTable.row(printtdata).data();
                        var tid = $(printtdata).find('span#txnid').html();
                        return  {trans: showtd.fstpytxn_id, beni_name: showtd.op2, acno: showtd.op3, amt: showtd.transamt, utr: showtd.opr_ref_no, remitter: showtd.customer_no, tran_time: showtd.req_dt, status: showtd.status, txntype: showtd.op6, ifsc: showtd.op4};
                    }).get();
                } else {
                    checked_item = [];
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("selected");
                }
            });
        })

        oTable.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("selected");
            var allChecked;
            allChecked = false;
            $('.checkboxes').each(function (index, element) {
                if (element.checked) {
                    allChecked = true;
                } else {
                    allChecked = false;
                    return false;
                }
            })
            if (allChecked)
            {
                $('.group-checkable').prop('checked', true);
            } else {
                $('.group-checkable').prop('checked', false);
            }
            checked_item = $('#transactiontable .checkboxes:checked').map(function () {
                var printtdata = $(this).closest('tr');
                var showtd = oTable.row(printtdata).data();
                var tid = $(printtdata).find('span#txnid').html();
                
                return  {trans: showtd.fstpytxn_id, beni_name: showtd.op2, acno: showtd.op3, amt: showtd.transamt, utr: showtd.opr_ref_no, remitter: showtd.customer_no, tran_time: showtd.req_dt, status: showtd.status, txntype: showtd.op6, ifsc: showtd.op4};
            
            }).get();
        });

        $('#printtd').on('click', function () {
            if (checked_item.length === 0) {
               
                toastr.error("Please select at least one successful transaction");

            } else {
                $.redirect("Recharge/PrintTable/1", checked_item);
            }

        });

        $.extend({
            redirect: function (targets, values)
            {
                var form = $("<form>", {attr: {method: "POST", action: targets, target: '_blank'}});
                $("<input>", {attr: {type: "hidden", name: 'checkrow', value: JSON.stringify(values)}}).appendTo(form);
                $(form).appendTo($(document.body)).submit();
            }
        });

          oTable.on('click', '.bcomplnt', function (e) {
            e.preventDefault();

            var row = $(this).closest('tr');
            var showtd = oTable.row(row).data();

            swal({
                title: "Are You Sure?",
                text: "Book a Complaint for  Transaction ID <u>" + showtd.fstpytxn_id + "</u>",
                type: 'input',
                html: true,
                allowOutsideClick: false,
                showConfirmButton: true,
                showCancelButton: true,
                inputValue: 'NA',
                inputPlaceholder: 'Complaint Reason',
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                closeOnCancel: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
            },
                    function (inputValue) {
                        if (inputValue === false)
                            return false;
                        if (inputValue === "") {
                            swal.showInputError("Plaese Enter Reason Of Complaint");
                            return false
                        } else {

                            $.post('MoneyTransfer/BookComplaint', {tid: showtd.fstpytxn_id, reason: inputValue}, function (response) {
                                if (response)
                                {
                                    if (response.error == 2)
                                    {
                                        window.location.reload(true);
                                    } else if (response.error == 0)
                                    {
                                        toastr.success(response.msg);

                                        swal.close();
                                    } else {
                                        toastr.error(response.error_desc);

                                        swal.close();
                                    }
                                    oTable.ajax.reload();
                                }
                            }, 'json').fail(function (err) {
                                throw err;
                            })

                        }
                    }
            );
        });

    }

    return{   
        init: function () {
            MyOrder();
        }
    };
}();
$(document).ready(function () {
    TopUpsOrder.init();
});
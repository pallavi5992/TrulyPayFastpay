var MoneyTransfer = function () {
    $('<link href="assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css">').insertAfter('#endglobal');
    var _tgt;
	var servc_array={};
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
	
	    // $.ajax({
     //        method: 'POST',
     //        url: 'MoneyTransfer/Fetch_services_fr_remtt',
     //        dataType: 'JSON',
         
     //    }).done(function (response) {
     //        console.log(response)
     //        if (response){
     //            if (response.error == 1){
     //                toastr.error(response.error_desc);
     //            } else if (response.error == 2){
     //                window.location.reload(true);
     //            } else if (response.error == 0) {
     //                var str = '';
     //                str += '<option value="">ALL</option>';
     //                $.each(response.data, function (k, v) {
     //                    str += '<option value="' + v.service_name + '" >' + v.service_name + '</option>';
     //                });

     //                $('#services').html(str);
     //            }
     //        }else{
     //            console.log('error----123');
     //        }
     //    }).fail(function (err) {
     //        throw err;
     //    });
	
	
    var MyOrder = function () {
        var checked_item = [];
        var checkedfieldmy = {};
        var imy;
        var v;
        var indexmy;
        
        // var agent = '8340000000';
	
        var agent = ''; //'9967677757';
        	
	
        var oTable = $('#transactiontable').DataTable({
            "processing": true,
            "serverSide": true,
			//"searching" : false,
			'serverMethod': 'post',
            order: [],
            "ajax": {
                url: "FundTransfer/FundTransferRetailerList",
                type: "POST",
                data: function (data) {
                    if (agent) {
                        data.agent = agent;
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
                // {title: '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input type="checkbox" class="group-checkable" data-set="#transactiontable .checkboxes" /><span></span></label>', class:"compact all" ,render: function (data, type, full, meta)
                //     {
                //         if (full.status == 'SUCCESS') {
                //             var taxR = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">';
                //             taxR += ' <input type="checkbox" class="checkboxes styled" />';
                //             taxR += '<span></span>';
                //             taxR += '</label>';
                //         } else {
                //             var taxR = '';
                //         }

                //         return taxR;
                //     },
                //     "orderable": false
                // },
              
                {title: '<span>Retailer Name</span>', class: 'compact all', render: function (data, type, full, meta){
                        return '<span>' + full.first_name + ' ' + full.last_name + '</span>';
                    }
                },
				{title: '<span>Email</span>', class: 'compact', render: function (data, type, full, meta) {
                        return full.email;
                    }
                },
			    {title: '<span>Mobile</span>', data: 'mobile', class: 'compact all'},
				{title: '<span>Business Name</span>', data: 'business_name', class: 'compact all'},
				{title: '<span>Business Address</span>', data: 'business_address', class: 'compact all'},
				{title: '<span>Business State</span>', data: 'business_state', class: 'compact all'},
				{title: '<span>Business City</span>', data: 'business_city', class: 'compact all'},
				{title: '<span>Business Pincode</span>', data: 'business_pincode', class: 'compact all'},
				{title: '<span>Balance</span>', data: 'rupee_balance', class: 'compact all'},

                {title: '', class: 'compact', render: function (data, type, full, meta) {
                        return '<button type="button" style="" data-userid='+full.user_id+' id="pushwb" class="btn btn-primary legitRipple">Push WB</button>';
                    }
                },
				//   {title: '<span>Transaction Type</span>', data: 'op6', class: 'compact all'},
                
    //             {title: '<span>UTR</span>', data: 'opr_ref_no', class: 'compact all'},
				//  {title: '<span>Status</span>', data: 'status', class: 'compact all'},
				//   {title: '<span>Date &amp; Time</span>', data: 'req_dt', class: 'compact all'}
			
            ],
            // columnDefs: [{
            //     targets: 12,
            //         render: function (t, e, a, n) {

            //             var s = {
            //                 SUCCESS: {
            //                     title: "SUCCESS",
            //                     class: "btn btn-success"
            //                 },
            //                 PENDING: {
            //                     title: "PENDING",
            //                    class: "btn btn-pending"
            //                 },
            //                 FAILED: {
            //                     title: "FAILED",
            //                     class: "btn btn-danger"
            //                 },
            //                 REVIEW: {
            //                     title: "REVIEW",
            //                     class: "btn btn-info"
            //                 },
            //                 REFUND: {
            //                     title: "REFUND",
            //                     class: "btn btn-danger"
            //                 },
            //             };
            //             return void 0 === s[t] ? t : '<span class="' + s[t].class + '">' + s[t].title + "</span>"
            //         }
            //     }],
            "lengthMenu": [
                [5, 20, 50, 100],
                [5, 20, 50, 100] // change per page values here
            ],
            buttons: [
                {extend: 'csv', className: 'btn-secondary',title: 'MoneyRemittance Transaction List'},
                // {extend: 'pdfHtml5', className: 'btn-secondary',
                // title: 'MoneyRemittance Transaction List', orientation: 'landscape', pageSize: 'A3'},
                // {extend: 'excel', className: 'btn-secondary',title: 'MoneyRemittance Transaction List'},
        
            ],



            dom: '<"datatable-header header-my search-data"fBl>r<"table-scrollable"t><"datatable-footer"ip>',
        });
		

        $('#transactiontable_length').append('<button id="printtd" class="btn btn-brand ml-15" type="button" style="float: right;"><i class="icon-printer2 position-left" title="Print" aria-hidden="true"></i>Print</button>');

        $('#transactiontable_filter label').hide();

		// 	  var head =' <div id="transaction-history" class="width-100   mt-3">';
		  
		// head += '<div class="card">';
		// head += '<div class="card-header" id="headingOne">';
		// 	head += '<h5 class="mb-0 float-left">';
  //       head += '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: #333;">SEARCH YOUR DETAILS';
		// head += '</button>';
	 //    head += '</h5>';
		// head += '</div>';
		// head += '<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" >';
		// head += '<div class="card-body" id="input_search" style="width: 100%;display: inline-block;">';
		// 	head += ' </div>';
		// head += '<div class="form-group  row ml-0 mr-0 pt-10 pb-10 pl-15 pr-15 mb-0">';
		// head += '<button type="submit" class="btn btn-primary legitRipple srchsubmy" id="srchsubmy">Search</button>';
  //       head += '<button type="button" id="my_reset" class="btn btn-circle grey-salsa btn-outline">Reset</button>';
		// head += '</div>';
		// head += '</div>';
		// head += ' </div>';
		// head += ' </div>';

    
		//   $('#transactiontable_filter').append(head); 
		  
		  var top ='<div class="row mt-3">';
		
            if(role_id == 3){	  
              top += '<div class="col-md-5 col-sm-4">';
              top += '<button type="submit" style="margin-top: 30px;" class="btn btn-primary legitRipple show_all_agents" id="show_all_agents">Show All Agents</button>';
              top += '</div>';
            }


              top += '<div class="col-md-3 col-sm-4">';
			  top += '<label>Mobile Number</label>';/// Agent Name / Shop Name
			  top += '<input type="text" class="form-control" id="agent" name="agent">';
			  top += '</div>';

			  top += '<div class="col-md-4 col-sm-4">';
              top += '<button type="submit" style="margin-top: 30px;" class="btn btn-primary legitRipple srchsubmy" id="srchsubmy">Search Agent</button>';
              top += '<button type="button" style="margin-top: 30px;" id="my_reset" class="btn btn-circle grey-salsa btn-outline">Reset</button>';
              top += '</div>';

			  top += '</div>';
			  // top += '</div>';
	
			  $('.header-my').append(top);
			
			
        $('#show_in_filter').click(function () {  
		
           /*  $('#myorder_testfle').slideToggle(); */
		    
        });


            var PushWBModelHtml = function(fetchdata){
                var userid = '', name = '';
                if(typeof fetchdata != 'undefined'){
                    userid = fetchdata['user_id'];
                    name = fetchdata['first_name'] + ' ' + fetchdata['last_name'];
                }


            var str =  `<div id="usr_updt_dtl_form" style="">
                    <div class="row" id="sec_form_div">
                        <div class="col-sm-12">
                            <div class="panel-body">
                                <form action="#" class="add-fund-form" id="add-fund-form">
                                    <fieldset>
                                     
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Agent ID</label>
                                                    <input type="text" class="form-control" value="`+ userid +`" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Agent Name</label>
                                                    <input type="text" class="form-control" value="`+ name +`" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="radio-inline">
                                                        <input type="radio" class="paymentoption" name="pay[]" checked value="CP"> Credit
                                                    </label>
                                                    <label class="radio-inline">
                                                      <input type="radio" class="paymentoption" name="pay[]" value="OP"> Others
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group addotherpayoption">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="text" placeholder="Enter the amount" name="amount" id="amount" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Note</label>
                                                    <textarea placeholder="Enter the note" name="note" id="note" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    <fieldset>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary legitRipple" id="add_balance">Add Balance<i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>`;

                return str;
        }


        /////////////////////////////////////////

        oTable.on('click', '#pushwb', function () {
            var userid = $(this).data('userid');
            var row = $(this).closest('tr');
            var editrow = oTable.row(row).data();
            if (editrow['user_id'] == userid) {
                $.ajax({
                    method: 'POST',
                    url: 'FundTransfer/WB',
                    dataType: 'JSON',
                    data: {'userid': editrow['user_id']},
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
                            str += '<h5 class="modal-title" id="head_ttl">PUSH FUND</h5>';
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

                            str +=  PushWBModelHtml(fetchdata);

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

                            var paymentmode = 'credit';
                            $(".paymentoption").click(function(){
                                console.log($(this).val());

                                var payotherop = `
                                    <select name="otherpaymode" id="otherpaymode" class="select">
                                        <option value="">Select mode</option>
                                        <option value="cash">Cash Payment</option>
                                        <option value="bank">Bank Payment</option>
                                    </select>`;

                                if($(this).val() == 'OP'){
                                    $(".addotherpayoption").html(payotherop);
                                    paymentmode == '';
                                } else{
                                    $(".addotherpayoption").html('');
                                    paymentmode = 'credit';
                                }

                            });

                            $("#otherpaymode").change(function(){
                                paymentmode = $(this).val();
                            });


                            $('#add_balance').click(function(e) {
                                e.preventDefault();
                                $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                                var la1 = $(this).ladda();
                                la1.ladda('start');
                                var formdata = {};
                                formdata.userid = editrow['user_id'];
                                formdata.paymode = paymentmode;
                                formdata.amount = $("#amount").val();
                                formdata.note = $("#note").val();
                                
                                // console.log(editrow);
                                console.log(formdata);
                                $.ajax({
                                    method: 'POST',
                                    url: 'FundTransfer/AddfundToRetailer',
                                    data: formdata,
                                    dataType: 'JSON'
                                }).done(function(response) {
                                    if (response) {
                                        if (response.error == 1) {
                                            toastr.error(response.error_desc, 'Oops!');
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
                            });

                            $('#mdl_usr_dsc').modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true,
                            });
                            $('#mdl_usr_dsc').on('hidden.bs.modal', function () {
                                $('#mdl_usr_dsc').remove();
                                oTable.ajax.reload(null, false);
                            });
                            
                        }
                    }
                }).fail(function (err) {
                    throw err;
                });
              
            }  

        });

        /////////////////////////////////////////
	

        var nRow = $('#transactiontable thead tr')[0];
		  // console.log(nRow.cells);
        $.each(nRow.cells, function (i, v) {

            if (v.cellIndex != 0 && v.cellIndex != 1 && v.cellIndex != 4 && v.cellIndex != 6 && v.cellIndex != 7&& v.cellIndex != 8&& v.cellIndex != 9&& v.cellIndex != 11 && v.cellIndex != 13 ) {
                if (v.cellIndex == 12) {
                    /*$.post('Recharge/selectMenu', {cell_id: v.cellIndex, type: 'REMITTANCE'}, function (response) {
                        if (response) {
                            if (response.error == 2) {
                                window.location.reload(true);
                            } else if (response.error == 0) {
                                var str = '';
                                $.each(response.msg, function (i, v) {
                                    str += '<option value = "' + v.name + '">' + v.name + '</option>';
                                });
                              $('#input_search').append('<div class="col-md-4 col-sm-6"><div id="inputser"><label>' +  v.innerText + '</label><div class="form-group"><select class="custom-select inputser" data-value = "' + v.cellIndex + '" title="' + v.innerText + '" data-width="100%" ><option value="">ALL</option>' + str + '</select></div></div></div>'); 
							 
                                $('.bootstrap-select').selectpicker();
                            } else {
                                console.log(response.error_desc);
                            }
                        }
                    }, 'json').fail(function (err) {
                        throw err;
                    });*/

                } 
				else if (v.cellIndex == 2) {


                    /*$.post('MoneyTransfer/Fetch_services_fr_remtt', {cell_id: v.cellIndex, type: 'REMITTANCE'}, function (response) {
                        if (response) {
                            // console.log(response);
                            if (response.error == 2) {
                                window.location.reload(true);
                            } else if (response.error == 0) {
                                var str = '';
                                $.each(response.msg, function (i, v) {
									// console.log(v)   
									 
                                    str += '<option value = "' + v.code + '">' + v.name + '</option>';
                                });
                              $('#input_search').append('<div class="col-md-4 col-sm-6"><div id="inputser"><label>' +  v.innerText + '</label><div class="form-group"><select class="custom-select inputser" data-value = "' + v.cellIndex + '" title="' +  v.innerText + '" data-width="100%" ><option value="">ALL</option>' + str + '</select></div></div></div>'); 
							  
                                $('.bootstrap-select').selectpicker();
                            } else {
                                console.log(response.error_desc);
                            }
                        }
                    }, 'json').fail(function (err) {
                        throw err;
                    });*/

                }
				else {
                    $('#input_search').append('<div class="col-md-4 col-sm-6"><div  id="inputser"><div class="form-group"><input type="text" class="form-control inputser" data-value = "' + v.cellIndex + '" placeholder="' + v.innerText + '"></div></div></div>'); 
				    /* $('#input_search').append('<div id="inputser"><div class="form-row"><div class="form-group col-lg-6"><input type="text" class="form-control inputser" data-value = "' + v.cellIndex + '" placeholder="' + v.innerText + '"></div></div></div>');  */

                }
            }
        });
		
		

        $('#srchsubmy, #show_all_agents').on('click', function () {
            if(this.id == 'show_all_agents'){
                agent = 'all';
                oTable.draw();
            } else {
                agent = $("#agent").val();
                if (agent == "") {
                    toastr.error("Please enter the mobile number");
                } else {
                    oTable.draw();
                }
            }
            // agent = $("#agent").val();

            // if (agent != "") {
            //     oTable.draw();
            // } else {
            //     toastr.error("Please enter the mobile number");
            // }
        });

        $('#my_reset').on('click', function () {
            agent = '';
            $('#agent').val('');
            oTable.draw();
            
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
                $.redirect("FundTransfer/PrintTable/1", checked_item);
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

                            $.post('FundTransfer/BookComplaint', {tid: showtd.fstpytxn_id, reason: inputValue}, function (response) {
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
			//servcfilter();
        }
    };
}();
$(document).ready(function () {
    MoneyTransfer.init();
});

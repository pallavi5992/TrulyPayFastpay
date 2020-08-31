<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$userDetails = get_user_details();

?>   
<div class="col-lg-10">
        <div class="beneficiary-list">
            <div class="gray-header" style="margin-bottom: 40px;" id="title_first"><?php echo $link ?></div>
                <div class="table-responsive">                  
                <table class="table datatables" id="PaymentRequestTable">
                <thead class="thead-blue"></thead>
                </table>
                </div>                 
        </div>
</div>
<script>
    var PAYMENTREQUEST = function () {

        var PaymentRequest = function () {
            var Datatable = $('#PaymentRequestTable').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/get_payment_reqst",
                    type: 'post',
                    "dataSrc": function (json) {
                        console.log(json)
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

                     {
                     "title" : "User",
                    
                        render: function (data, type, full, meta) {
                         return full.first_name + ' ' + full.last_name + ' (' + full.mobile + ')';
                    }

                    },

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
                     "title" : "Remarks",
                   
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
                                st += '<button data-Payment="' + full.tableauto_id + '" type="button" class="btn btn-space btn-primary btn-sm btn-primary btn-sm" id="Accecpt_Payment" style="margin-right: 10px;margin-bottom: 10px;">Accept</button> <button data-Payment="' + full.tableauto_id + '" type="button" class="btn btn-space btn-danger btn-sm btn-primary btn-sm" id="Reject_Payment" >Reject</button>';
                               
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

           


            Datatable.on('click', '#Accecpt_Payment', function (e) {
                e.preventDefault();
                var PaymentId = $(this).attr('data-Payment');
                var row = $(this).closest('tr');
                var showtd = Datatable.row(row).data();

                if (PaymentId == showtd.tableauto_id) {
                    swal({
                        title: "Are you sure?",
                        text: "You Want To Accept This Payment Request Of Rs. : " + showtd.amount,
                        type: "info",
                        showCancelButton: true,
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Yes, Accept it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function (isConfirm) {
                        // accept ajax request
                        $.ajax({
                            method: 'POST',
                            url: 'Manage/AcceptPaymentRequest',
                            dataType: 'JSON',
                            data: showtd
                        }).done(function (response) {
                            if (response) {
                                if (response.error == 1) {
                                   

                                    toastr.error(response.error_desc);

                                    swal.close();
                                } else {
                                    if (response.error == 2) {
                                        window.location.reload(true);
                                    } else {
                                        if (response.error == 0) {
                                            
                                            toastr.success(response.msg);

                                            Datatable.ajax.reload(); 
                                            swal.close();
                                        }
                                    }
                                }
                            }
                        }).fail(function (err) {
                            throw err;
                        });
                    });
                }

            });

             Datatable.on('click', '#Reject_Payment', function (e) {
                e.preventDefault();
                var PaymentId = $(this).attr('data-Payment');
                var row = $(this).closest('tr');
                var showtd = Datatable.row(row).data();

                if (PaymentId == showtd.tableauto_id) {
                    swal({
                        title: "Are you sure?",
                        text: "You Want To Reject This Payment Request Of Rs. : " + showtd.amount,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, Reject it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        // rejet ajax request
                        $.ajax({
                            method: 'POST',
                            url: 'Manage/RejectPaymentRequest',
                            dataType: 'JSON',
                            data: showtd
                        }).done(function (response) {
                            if (response) {
                                if (response.error == 1) {
                                  

                                     toastr.error(response.error_desc);
                                    swal.close();
                                } else {
                                    if (response.error == 2) {
                                        window.location.reload(true);
                                    } else {
                                        if (response.error == 0) {
                                           

                                             toastr.success(response.msg);
                                            Datatable.ajax.reload(); // user paging is not reset on reload
                                            swal.close();
                                        }
                                    }
                                }
                            }
                        }).fail(function (err) {
                            throw err;
                        });
                    });
                }

            })
          

         
        }

        return {
            init: function () {
                PaymentRequest();
            }
        };
    }();
    $(document).ready(function () {
        PAYMENTREQUEST.init();
    });
</script>
</div>

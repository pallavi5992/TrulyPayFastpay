<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$userDetails = get_user_details();
?>
<style type="text/css">
    .customtable {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

.toggle.btn
{
min-width: 100px !important;
}


</style>
    <div class="col-lg-10">
        <div class="beneficiary-list">
            <div class="gray-header" id="pendg_actv">Services</div>
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
       var ManageService = function () {
        var Regex = <?php echo json_encode($Regex); ?>;
        var opr = {};


         Array.prototype.diff = function (a) {
            return this.filter(function (i) {
                return a.indexOf(i) < 0;
            });
        };
        var VendorList = function () {
        $.post('Manage/get_all_vendors', function (response) {
                if (response)
                {

                    if (response.error_data == 0)
                    {
                        $.each(response.data, function (k, v) {
                            opr[v.vendor_id] = v;
                        });


                    } else if (response.error_data == 2)
                    {
                        window.location.reload(true);
                    } else {
                        toastr.error(response.error_desc, 'Oops!');
                    }
                }
            }, 'json').fail(function (err) {
                throw err;
            });
        }
        var Service = function () {
              var table = $('.datatables').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/all_services",
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

                     {
                     "title" : "Name",


                         "data": "service_name",




                    },


                     {
                     "title" : "Type",

                     "data": "type",
                    },
                     {
                     "title" : "Key",

                     "data": "code",
                    },


                    {title: 'Down Status',data:'is_down',class:'none',
                     render:function(data, type, full, meta){
                         var status="";
                         if(data==0){
                             status="Up";
                         }else if(data==1){
                              status="Down";
                         }else{
                             status="Undefined";
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

                    {
                     "title" : "Action",
                     "orderable": false,

                      "render": function ( data, type, full, meta ) {
                          return '<a data-servc="' + full.service_id + '" id="updt_srvc_dtl" class="btn btn-info white-txt">Edit</a>';
                        }
                    },


                ],


            });



           table.on('click', '#updt_srvc_dtl', function () {
            var srvcid = $(this).data('servc');
            var row = $(this).closest('tr');
            var showtd1 = table.row(row).data();
            console.log(showtd1)
            if (showtd1['service_id'] == srvcid) {
                var isDown;
                if (showtd1.is_down == 0) {

                    isDown = '';

                } else {


                    isDown = 'checked';
                }

                var active;
                if (showtd1.is_active == 1) {
                    active = 'checked';
                } else {
                    active = '';
                }

                var auto;
                if (showtd1.Amnt_by_routing == 0) {
                    auto = '';
                } else {
                    auto = 'checked';
                }
                var slabCount = 0;
                var model = '';
                model = '<div class="modal fade" id="m_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                model += ' <div class="modal-dialog modal-lg modal-dialog-centered" role="document">';
                model += ' <div class="modal-content">';
                model += '  <div class="modal-header">';
                model += '  <h5 class="modal-title" id="exampleModalLabel">';
                model += '   Edit Service Details for ' + showtd1.service_name + ' ( ' + showtd1.type + ' ) ';
                model += '</h5>';
                model += ' <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                model += '  <span aria-hidden="true">';
                model += '   &times;';
                model += ' </span>';
                model += '</button>';
                model += '  </div>';
                model += ' <div class="modal-body  modal-lg" id="FirstDiv">';
                model += '  <div class="m-portlet">';
                model += ' <form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">';
                model += '  <div class="m-portlet__body">';
                model += ' <div class="form-group m-form__group row m-0-set">';
                model += ' <div class="col-lg-6">';
                model += '  <label>';
                model += '   Service Name:';
                model += ' </label>';
                model += ' <input type="text" class="form-control m-input" value="' + showtd1.service_name + '" disabled>';
                model += '</div>';
                model += ' <div class="col-lg-6">';
                model += '  <label class="">';
                model += ' Up/Down:';
                model += ' </label>';
                model += ' <div class="checkbox checkbox-switch">';
                model += ' <input type="checkbox" class="switch form-control m-input updown" id="updown" ' + isDown + '>';
                model += '  </div>';
                model += '  </div>';
                model += ' </div>   ';
                model += '<div class="form-group m-form__group row m-0-set">';
                model += ' <div class="col-lg-6">';
                model += '<label>';
                model += '  Active/Inactive Status:';
                model += ' </label>';
                model += ' <div class="checkbox checkbox-switch">';
                model += ' <input type="checkbox" class="switch form-control m-input activeinactive" id="activeinactive" ' + active + '>';
                model += ' </div>';
                model += ' </div>';
                model += ' <div class="col-lg-6">';
                model += '<label>';
                model += 'Amount Wise Switching :';
                model += ' </label>';
                model += ' <div class="checkbox checkbox-switch">';
                model += '  <input type="checkbox" class="switch form-control m-input autoswitch" id="autoswitch" ' + auto + '>';
                model += ' </div>';
                model += ' </div>';
                model += ' </div>';
                model += ' <div class="form-group m-form__group row m-0-set" id="displayDiv">';
                model += ' </div>';
                model += '</div>';
                model += '</div>';
                model += '<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">';
                model += ' <div class="m-form__actions m-form__actions--solid">';
                model += ' <div class="row">';
                model += ' <div class="col-lg-6">';
                model += ' <button type="submit" class="btn btn-primary" id="FormPost">';
                model += '   Next';
                model += ' </button>';
                model += ' </div>';
                model += ' </div>';
                model += '  </div>';
                model += ' </div>';
                model += ' </form>';
                model += ' </div>';
                model += '<div id="ConfirmSceren"></div>'
                model += ' </div>';
                model += '</div>';
                model += '  </div>';
                model += '</div>';

                $('body').append(model);
                $('#m_modal_2').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                $('#m_modal_2').on('hidden.bs.modal', function () {
                    $('#m_modal_2').remove();

                    table.ajax.reload(null, false);
                });
                $("#updown").bootstrapToggle({
                        on: 'Up',
                        off: 'Down'
                    });
                $("#autoswitch").bootstrapToggle({
                        on: 'On',
                        off: 'Off'
                    });
                $("#activeinactive").bootstrapToggle({
                        on: 'Active',
                        off: 'Inactive'
                    });

                var checked = $("#autoswitch").prop('checked');

                var vendorArray = {};

                 $.each(showtd1.Vendor, function (k, v) {
                    vendorArray[v.served_by] = v;
                });

                var apiArray = showtd1.supported_gateway.split(',');
                var AddMinusArray = showtd1.served_by.split(',');

                if (checked) {

                    var html = '';
                    html = '<div class="col-lg-12" id="AppendInthis"><div class="row"><div class="col-md-6"><span>Add Row</span></div><div class="col-md-6"><button type="button" class="btn btn-success rowAdd" id="rowAdd"><i class="fas fa-plus-circle"></i></button></div></div><hr>';

                        if (showtd1.Vendor.length > 0) {
                        var arrLength = showtd1.Vendor.length;
                        $.each(showtd1.Vendor, function (k, v) {

                            html += '<div class="row" id="SlabRates_' + slabCount + '">';
                            html += '<div class="col-md-3">';
                            html += ' <label class="">';
                            html += '  Min Amount:';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right form-group">';

                            html += ' <input type="tel" class="form-control m-input gatewayMinAmnt" id="gatewayMinAmnt_' + slabCount + '" value="' + v.Min_amnt + '">';
                            html += '<span data-for="gatewayMinAmnt_' + slabCount + '"></span>';
                            html += '</div>';
                            html += '</div>';



                                html += '<div class="col-md-3">';
                            html += ' <label class="">';
                            html += '  Max Amount:';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right form-group">';

                            html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMaxAmnt_' + slabCount + '" value="' + v.Max_amnt + '">';
                            html += '<span data-for="gatewayMaxAmnt_' + slabCount + '"></span>';
                            html += '</div>';
                            html += '</div>';


                            html += '<div class="col-md-4">';

                            html += ' <label class="">';
                            html += '  Supported Vendor:';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right form-group">';
                            html += ' <select class="form-control m-input m-input--square Route" id="Route_' + slabCount + '" name="transmode">';
                            if (apiArray) {
                                $.each(apiArray, function (k1, v1) {

                                        var ch = v.served_by == v1 ? 'selected' : '';

                                        html += ' <option value="' + v1 + '" ' + ch + '>' + opr[v1].vendor_name + '</option>';

                                });
                            }
                            html += ' </select>';
                            html += '<span data-for="Route_' + slabCount + '"></span>';
                            html += '</div>';
                            html += '</div>';



                            html += '<div class="col-md-2">';
                            html += ' <label class="">';
                            html += ' ';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right">';
                            html += ' <button type="button" class="btn btn-danger rowminus" id="rowminus" ><i class="fas fa-minus-circle"></i></button>';
                            html += '</div>';
                            html += '</div>';

                            html += '</div>';





                                                    $('#displayDiv').html(html);


                                                        slabCount++;
                                                        KeyPress_Validation();


                                                    });


                                                } else {


                                        var html = '';
                                        html = '<div class="col-lg-12" id="AppendInthis"><div class="row"><div class="col-md-6"><span>Add Row</span></div><div class="col-md-6"><button type="button" class="btn btn-success rowAdd" id="rowAdd"><i class="fas fa-plus-circle"></i></button></div></div><hr>';

                                                html += '<div class="row" id="SlabRates_' + slabCount + '">';

                                                   html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Min Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMinAmnt" id="gatewayMinAmnt_' + slabCount + '" >';
                                                html += '<span data-for="gatewayMinAmnt_' + slabCount + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                    html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Max Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMaxAmnt_' + slabCount + '" >';
                                                html += '<span data-for="gatewayMaxAmnt_' + slabCount + '"></span>';
                                                html += '</div>';
                                                html += '</div>';


                                                html += '<div class="col-md-4">';

                                                html += ' <label class="">';
                                                html += '  Supported Vendor:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';
                                                html += ' <select class="form-control m-input m-input--square Route" id="Route_' + slabCount + '" name="transmode">';
                                                if (apiArray) {
                                                    $.each(apiArray, function (k1, v1) {


                                                            html += ' <option value="' + v1 + '" >' + opr[v1].vendor_name + '</option>';

                                                    });
                                                }
                                                html += ' </select>';
                                                html += '<span data-for="Route_' + slabCount + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                html += '<div class="col-md-2">';
                                                html += ' <label class="">';
                                                html += ' ';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right">';
                                                html += ' <button type="button" class="btn btn-danger rowminus" id="rowminus" ><i class="fas fa-minus-circle"></i></button>';
                                                html += '</div>';
                                                html += '</div>';

                                                html += '</div>';

                                        html += '</div>';

                                        $('#displayDiv').html(html);

                                                    slabCount += 1;
                                     KeyPress_Validation();

                                                }



                                                  var c = slabCount - 1;



                                                 $('.rowminus').click(function (e) {

                                                    e.preventDefault();
                                                    var r = slabCount - 1;
                                                    var s = r - 1;
                                                    $('#SlabRates_' + r + '').remove();

                                                    slabCount = slabCount - 1;

                                                });




                                        $('.rowAdd').click(function (e) {
                                            e.preventDefault();
                                            console.log(slabCount);
                                            var c = slabCount;

                                              var html = '';


                                                html += '<div class="row" id="SlabRates_' + c + '">';

                                                   html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Min Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMinAmnt" id="gatewayMinAmnt_' + c + '" >';
                                                html += '<span data-for="gatewayMinAmnt_' + c + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                    html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Max Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMaxAmnt_' + c + '" >';
                                                html += '<span data-for="gatewayMaxAmnt_' + c + '"></span>';
                                                html += '</div>';
                                                html += '</div>';


                                                html += '<div class="col-md-4">';

                                                html += ' <label class="">';
                                                html += '  Supported Vendor:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';
                                                html += ' <select class="form-control m-input m-input--square Route" id="Route_' + c + '" name="transmode">';
                                                if (apiArray) {
                                                    $.each(apiArray, function (k1, v1) {
                                                       console.log(v1)

                                                            html += ' <option value="' + v1 + '" >' + opr[v1].vendor_name + '</option>';

                                                    });
                                                }
                                                html += ' </select>';
                                                html += '<span data-for="Route_' + c + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                html += '<div class="col-md-2">';
                                                html += ' <label class="">';
                                                html += ' ';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right">';
                                                html += ' <button type="button" class="btn btn-danger rowminus" id="rowminus' + c + '" ><i class="fas fa-minus-circle"></i></button>';
                                                html += '</div>';
                                                html += '</div>';

                                                html += '</div>';

                                        html += '</div>';

                                        $('#AppendInthis').append(html);

                                                 slabCount++;

                                                 $('#rowminus' + c + '').click(function(e){
                                                    e.preventDefault();

                                                    $('#SlabRates_' + c + '').remove();

                                                    slabCount = slabCount - 1;

                                                 })


                                                  KeyPress_Validation();

                                                    })







                }else {

                    slabCount = 0;

                    var html = '';
                    html = '<div class="col-lg-6 form-group" >';
                    html += ' <label class="">';
                    html += '  Route:';
                    html += ' </label>';
                    html += ' <select class="form-control m-input m-input--square" id="Route">';
                    if (apiArray) {
                        $.each(apiArray, function (k, v) {
                              console.log(apiArray)
                              console.log(opr)
                            var ch = v == showtd1.served_by ? 'selected' : '';
                            html += ' <option value="' + v + '" ' + ch + '>' + opr[v].vendor_name + '</option>';
                        });
                    }
                    html += ' </select>';
                    html += '<span data-for="Route"></span>';
                    html += '</div>';

                    $('#displayDiv').html(html);

                }


                KeyPress_Validation();

                /******************amount wise auto switch ******************/

                  $("#autoswitch").change(function () {

                    if ($(this).prop('checked')) {



                    var html = '';
                    html = '<div class="col-lg-12" id="AppendInthis"><div class="row"><div class="col-md-6"><span>Add Row</span></div><div class="col-md-6"><button type="button" class="btn btn-success rowAdd" id="rowAdd"><i class="fas fa-plus-circle"></i></button></div></div><hr>';


                            if (showtd1.Vendor.length > 0) {
                                                    var arrLength = showtd1.Vendor.length;
                                                    $.each(showtd1.Vendor, function (k, v) {



                            html += '<div class="row" id="SlabRates_' + slabCount + '">';

                               html += '<div class="col-md-3">';
                            html += ' <label class="">';
                            html += '  Min Amount:';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right form-group">';

                            html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMinAmnt_' + slabCount + '" value="' + v.Min_amnt + '">';
                            html += '<span data-for="gatewayMinAmnt_' + slabCount + '"></span>';
                            html += '</div>';
                            html += '</div>';



                                html += '<div class="col-md-3">';
                            html += ' <label class="">';
                            html += '  Max Amount:';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right form-group">';

                            html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMaxAmnt_' + slabCount + '" value="' + v.Max_amnt + '">';
                            html += '<span data-for="gatewayMaxAmnt_' + slabCount + '"></span>';
                            html += '</div>';
                            html += '</div>';


                            html += '<div class="col-md-4">';

                            html += ' <label class="">';
                            html += '  Supported Vendor:';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right form-group">';
                            html += ' <select class="form-control m-input m-input--square Route" id="Route_' + slabCount + '" name="transmode">';





                            if (apiArray) {
                                $.each(apiArray, function (k1, v1) {
                                    // if (v in vendorArray) {
                                        var ch = v.served_by == v1 ? 'selected' : '';

                                        html += ' <option value="' + v1 + '" ' + ch + '>' + opr[v1].vendor_name + '</option>';
                                    // } else {
                                    //     html += ' <option value="' + v1 + '" >' + opr[v1].vendor_name + '</option>';
                                    // }
                                });
                            }
                            html += ' </select>';
                            html += '<span data-for="Route_' + slabCount + '"></span>';
                            html += '</div>';
                            html += '</div>';



                            html += '<div class="col-md-2">';
                            html += ' <label class="">';
                            html += ' ';
                            html += ' </label>';
                            html += ' <div class="m-input-icon m-input-icon--right">';
                            html += ' <button type="button" class="btn btn-danger rowminus" id="rowminus' + slabCount + '" d><i class="fas fa-minus-circle"></i></button>';
                            html += '</div>';
                            html += '</div>';

                            html += '</div>';



                    $('#displayDiv').html(html);
                     KeyPress_Validation();



                                                        slabCount++;


                                                    });

                                                    KeyPress_Validation();


                                                } else {


                                        var html = '';
                                        html = '<div class="col-lg-12" id="AppendInthis"><div class="row"><div class="col-md-6"><span>Add Row</span></div><div class="col-md-6"><button type="button" class="btn btn-success rowAdd" id="rowAdd"><i class="fas fa-plus-circle"></i></button></div></div><hr>';

                                                html += '<div class="row" id="SlabRates_' + slabCount + '">';

                                                   html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Min Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMinAmnt_' + slabCount + '" >';
                                                html += '<span data-for="gatewayMinAmnt_' + slabCount + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                    html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Max Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMaxAmnt_' + slabCount + '" >';
                                                html += '<span data-for="gatewayMaxAmnt_' + slabCount + '"></span>';
                                                html += '</div>';
                                                html += '</div>';


                                                html += '<div class="col-md-4">';

                                                html += ' <label class="">';
                                                html += '  Supported Vendor:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';
                                                html += ' <select class="form-control m-input m-input--square Route" id="Route_' + slabCount + '" name="transmode">';
                                                if (apiArray) {
                                                    $.each(apiArray, function (k1, v1) {


                                                            html += ' <option value="' + v1 + '" >' + opr[v1].vendor_name + '</option>';

                                                    });
                                                }
                                                html += ' </select>';
                                                html += '<span data-for="Route_' + slabCount + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                html += '<div class="col-md-2">';
                                                html += ' <label class="">';
                                                html += ' ';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right">';
                                                html += ' <button type="button" class="btn btn-danger rowminus" id="rowminus' + slabCount + '" ><i class="fas fa-minus-circle"></i></button>';
                                                html += '</div>';
                                                html += '</div>';

                                                html += '</div>';

                                        html += '</div>';

                                        $('#displayDiv').html(html);
                                         KeyPress_Validation();


                                                    slabCount += 1;


                                                }


                                                 $('.rowminus').click(function (e) {

                                                    e.preventDefault();
                                                    var r = slabCount - 1;
                                                    console.log(r)
                                                    var s = r - 1;
                                                    $('#SlabRates_' + r + '').remove();

                                                    slabCount = slabCount - 1;

                                                });

                                        $('.rowAdd').click(function (e) {
                                            var c = slabCount;

                                              var html = '';


                                                html += '<div class="row" id="SlabRates_' + c + '">';

                                                   html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Min Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMinAmnt" id="gatewayMinAmnt_' + c + '" >';
                                                html += '<span data-for="gatewayMinAmnt_' + c + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                    html += '<div class="col-md-3">';
                                                html += ' <label class="">';
                                                html += '  Max Amount:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';

                                                html += ' <input type="tel" class="form-control m-input gatewayMaxAmnt" id="gatewayMaxAmnt_' + c + '" >';
                                                html += '<span data-for="gatewayMaxAmnt_' + c + '"></span>';
                                                html += '</div>';
                                                html += '</div>';


                                                html += '<div class="col-md-4">';

                                                html += ' <label class="">';
                                                html += '  Supported Vendor:';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right form-group">';
                                                html += ' <select class="form-control m-input m-input--square Route" id="Route_' + c + '" name="transmode">';
                                                if (apiArray) {
                                                    $.each(apiArray, function (k1, v1) {


                                                            html += ' <option value="' + v1 + '" >' + opr[v1].vendor_name + '</option>';

                                                    });
                                                }
                                                html += ' </select>';
                                                html += '<span data-for="Route_' + c + '"></span>';
                                                html += '</div>';
                                                html += '</div>';



                                                html += '<div class="col-md-2">';
                                                html += ' <label class="">';
                                                html += ' ';
                                                html += ' </label>';
                                                html += ' <div class="m-input-icon m-input-icon--right">';
                                                html += ' <button type="button" class="btn btn-danger rowminus" id="rowminus' + c + '" ><i class="fas fa-minus-circle"></i></button>';
                                                html += '</div>';
                                                html += '</div>';

                                                html += '</div>';

                                        html += '</div>';

                                        $('#AppendInthis').append(html);

                                                 slabCount++;

                                                 $('#rowminus' + c + '').click(function(e){
                                                    e.preventDefault();

                                                    $('#SlabRates_' + c + '').remove();

                                                    slabCount = slabCount - 1;

                                                 })



                                                    })

                                                KeyPress_Validation();



                 }else {


                    slabCount = 0;
                        var html = '';
                        html = '<div class="col-lg-6 form-group" >';
                        html += ' <label class="">';
                        html += '  Route:';
                        html += ' </label>';
                        html += ' <select class="form-control m-input m-input--square" id="Route">';
                        if (apiArray) {

                            $.each(apiArray, function (k5, v5) {
                               console.log(v5)
                                var ch = v5 == showtd1.served_by ? 'selected' : '';
                                html += ' <option value="' + v5 + '" ' + ch + '>' + opr[v5].vendor_name + '</option>';
                            });
                        }
                        html += ' </select>';
                        html += '<span data-for="Route"></span>';
                        html += '</div>';

                        $('#displayDiv').html(html);
                           KeyPress_Validation();

                    }
                  });








                /***************amount wise auto switch***********************/


                  $('#FormPost').click(function (e) {
                    e.preventDefault();
                    var params = {'valid': true};
                    params.serviceId = showtd1.service_id
                    params.upDown = $('#updown').is(':checked');
                    params.activeInactive = $('#activeinactive').is(':checked');
                    params.autoswitch = $('#autoswitch').is(':checked');
                    if (params.autoswitch == false) {

                        params.Route = $('#Route option:selected').val();
                         params.Vendor_nam = $('#Route option:selected').text();


                    } else {

                                                   var supported = {};

                                                    var DivCount = {};
                                                    for (var i = 0; i < slabCount; i++) {
                                                        DivCount[i] = i;
                                                    }


                                                    $.each(DivCount, function (k, v) {
                                                        console.log(DivCount)
                                                        var route = $('#Route_' + v).val();
                                                          var route_nam = $('#Route_' + v +' option:selected').text();
                                                        var minamount = $('#gatewayMinAmnt_' + v).val();
                                                        var maxamount = $('#gatewayMaxAmnt_' + v).val();

                                                        supported[v] = {'route': route, 'minamount': minamount, 'maxamount': maxamount,'RouteDiv': 'Route_' + v,MinDiv: 'gatewayMinAmnt_' + v,MaxDiv: 'gatewayMaxAmnt_' + v,'vendor_name':route_nam};



                                                    });



                                                    $.each(supported, function (key, value) {

                                                      if (!validate({'id': '' + value.RouteDiv + '', 'type': 'API', 'data': value.route, error: true}))
                                                        {
                                                            params.valid = false;
                                                        }

                                                        if (!validate({'id': '' + value.MinDiv + '', 'type': 'AMNT', 'data': value.minamount, error: true}))
                                                        {
                                                            params.valid = false;
                                                        }
                                                        if (!validate({'id': '' + value.MaxDiv + '', 'type': 'AMNT', 'data': value.maxamount, error: true}))
                                                        {
                                                            params.valid = false;
                                                        }

                                                    });




                    }
                    params.supported = supported;

                    console.log(params)

                    if (params.valid == true) {
                        $('#FirstDiv').hide();
console.log(params.upDown);
                        var updown = params.upDown == true ? 'Yes' : 'No';
                        var active = params.activeInactive == true ? 'Yes' : 'No';
                        var swtich = params.autoswitch == true ? 'Yes' : 'No';

                        var newmodel = '';
                        newmodel += ' <div class="modal-body  modal-lg">';
                        newmodel += '  <div class="m-portlet">';

                        newmodel += ' <form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">';
                        newmodel += '  <div class="m-portlet__body">';
                        newmodel += ' <div class="form-group m-form__group row m-0-set">';
                        newmodel += ' <div class="col-lg-6">';
                        newmodel += '  <label>';
                        newmodel += '   Service Name:';
                        newmodel += ' </label>';
                        newmodel += ' <input type="text" class="form-control m-input" value="' + showtd1.service_name + '" disabled>';
                        newmodel += '</div>';

                        newmodel += ' <div class="col-lg-6">';
                        newmodel += '  <label class="">';
                        newmodel += '    Up/Down:';
                        newmodel += ' </label>';
                        newmodel += ' <div class="m-input-icon m-input-icon--right">';
                        newmodel += ' <input type="text" class="form-control m-input" value="' + updown + '" disabled>';
                        newmodel += '  </div>';
                        newmodel += '  </div>';

                        newmodel += ' </div>    ';
                        newmodel += '<div class="form-group m-form__group row m-0-set">';
                        newmodel += ' <div class="col-lg-6">';
                        newmodel += '<label>';
                        newmodel += '  Active/Inactive Status:';
                        newmodel += ' </label>';
                        newmodel += ' <div class="m-input-icon m-input-icon--right">';
                        newmodel += ' <input type="text" class="form-control m-input" value="' + active + '" disabled>';
                        newmodel += ' </div>';
                        newmodel += ' </div>';

                        newmodel += ' <div class="col-lg-6">';
                        newmodel += '<label>';
                        newmodel += '  Auto Switching :';
                        newmodel += ' </label>';
                        newmodel += ' <div class="m-input-icon m-input-icon--right">';
                        newmodel += ' <input type="text" class="form-control m-input" value="' + swtich + '" disabled>';

                        newmodel += ' </div>';

                        newmodel += ' </div>';

                        newmodel += ' </div>';
                        if (params.autoswitch == true) {
                            newmodel += ' <div class="form-group m-form__group row m-0-set">';
                            newmodel += ' <div class="col-lg-12">';

                            newmodel += ' <table class="customtable">';
                            newmodel += '<tr>';

                            newmodel += ' <th>Min Amount</th>';
                            newmodel += ' <th>Max Amount</th>';
                            newmodel += ' <th>Serverd By Vendor</th>';
                            newmodel += '</tr>';
                            if (params.supported) {
                                $.each(params.supported, function (k, v) {
                                    newmodel += '<tr>';

                                    newmodel += '<td>' + v.minamount + '</td>';
                                    newmodel += '<td>' + v.maxamount + '</td>';
                                    newmodel += '<td>' + v.vendor_name + '</td>';
                                    newmodel += '</tr>';
                                });
                            }
                            newmodel += ' </table>';
                            newmodel += '</div>';
                            newmodel += ' </div>';

                        } else {

                            newmodel += ' <div class="form-group m-form__group row m-0-set">';
                            newmodel += ' <div class="col-lg-6">';
                            newmodel += '  <label>';
                            newmodel += '   Serverd By:';
                            newmodel += ' </label>';
                            newmodel += ' <input type="text" class="form-control m-input" value="' + params.Vendor_nam + '" disabled>';
                            newmodel += '</div>';
                            newmodel += ' </div>';

                        }

                        newmodel += '</div>';

                        newmodel += '</div>';
                        newmodel += '<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">';
                        newmodel += ' <div class="m-form__actions m-form__actions--solid">';
                        newmodel += ' <div class="row">';
                        newmodel += ' <div class="col-lg-6">';
                        newmodel += ' <button type="submit" class="btn btn-primary" id="FormPostSubmit">';
                        newmodel += '   Submit';
                        newmodel += ' </button>';

                        newmodel += ' <button type="submit" class="btn btn-primary" id="Back">';
                        newmodel += '   Back';
                        newmodel += ' </button>';

                        newmodel += ' </div>';
                        newmodel += ' </div>';
                        newmodel += '  </div>';
                        newmodel += ' </div>';
                        newmodel += ' </form>';

                        newmodel += ' </div>';
                        newmodel += ' </div>';
                        newmodel += ' </div>';

                        $('#ConfirmSceren').html(newmodel).show();
                        $('#Back').click(function (e) {
                            $('#ConfirmSceren').hide();
                            $('#FirstDiv').show();
                        });

                        console.log(params)
                        $('#FormPostSubmit').click(function (e) {
                            e.preventDefault();
                            $(this).addClass('btn-ladda ladda-button').attr('data-style', 'zoom-in');
                            var as = $(this).ladda();
                            as.ladda('start');
                            $.ajax({
                                url: 'Manage/ManageServiceData',
                                type: "POST",
                                dataType: 'json',
                                data: params,
                                success: function (response) {

                                    if (response) {
                                        if (response.error == 0) {
                                            params = {};
                                            toastr.success(response.msg)

                                            $('#m_modal_2').modal('hide');


                                            $('body, html').scrollTop(0);
                                        } else if (response.error == 2) {
                                            window.location.reload(true);
                                        } else {
                                            toastr.error(response.error_desc)
                                            as.ladda('stop');
                                        }
                                    }
                                }, error: function (err) {
                                    as.ladda('stop');
                                    throw err;
                                }
                            });
                        });
                    }

                });



            }

        })



      //---------------------------------- Keypress Validation--------------------------//

        var KeyPress_Validation = function () {

            $(".gatewayMinAmnt,.gatewayMaxAmnt").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                this.value = this.value.toUpperCase();
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Amount);
                var newregex = new RegExp(Regex.Amount);
                var extmsg = (id == 'user_add_continue_Partyemail') ? 'Party ' : '';

                if (str == '') {
                    helpBlck({
                        id: id, 'action': 'remove'
                    });
                }

                if (k == 8) {
                    if (!newregex.test(str)) {
                        error_msg = (str == '') ? 'Amount Is Required' : 'Invalid Amount';
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
                                'id': id, 'msg': 'Invalid Amount', 'type': 'error'
                            });
                        } else {
                            helpBlck({
                                id: id, 'action': 'remove'});
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

                if (p.type == "API")
                {

                    if (p.data != "" && (p.data in opr))
                    {

                        helpBlck({id: p.id, 'action': 'remove'});
                        return true;
                    }
                    else
                    {

                        if (p.error === true)
                        {
                            helpBlck({'id': p.id, 'msg': 'Invalid Api Gateway', 'type': 'error'});
                        }
                    }
                }
                else if (p.type == "AMNT")
                {


                     var _identifier_regex = Regex.Amount;
                var _mobile = new RegExp(_identifier_regex);

                    // var _mobile ="/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/";
                    //   var _mobile = new RegExp(_mobile);
                    if (_mobile.test(p.data))
                    {
                        if (p.error == true && (p.data == ''))
                        {
                            helpBlck({'id': p.id, 'msg': 'Invalid Amount', 'type': 'error'});
                        }
                        else
                        {
                            helpBlck({id: p.id, 'action': 'remove'});
                            return true;
                        }
                    }
                    else
                    {
                        if (p.error == true)
                        {
                            helpBlck({'id': p.id, 'msg': 'Invalid Amount', 'type': 'error'});
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

}

     return{
            init: function () {
                Service();
                VendorList();

            }
        };
    }();
    $(document).ready(function () {
        ManageService.init();
    });
</script>

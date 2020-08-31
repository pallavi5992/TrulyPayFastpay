<?php
$link = $this->uri->segments[2];
$Regex = All_Regex();
$userDetails = get_user_details();
?>
<style >
    .details-divider {
    border-bottom: 1px solid #e2e2e2;
    padding-bottom: 10px;
    margin-bottom: 10px;
}
.details-divider label {
    font-weight: bold;
    color: #3479ec;
}
a#Plan_SlabServiceRates {
margin-top: 10px;
}
</style>
    <div class="col-lg-10">
        <div class="beneficiary-list">
            <div class="width-100 mb-3 bord-bottom gray-header plan_listpage" id="first_title">
              <div class="float-left" style="margin-top:7px;">Plans</div>
              <div  id="AddDivButton" class="float-right">
              <button class="btn btn-primary  mr-10"  id="PlanAdd">Add Plan</button>
              </div>
            </div>
                
                        <div  id="PlanViewTable" class="plan_listpage">
                             <div class="table-responsive">

                                <table class="table datatables" id="Plantable">
                                    <thead class="thead-blue">

                                    </thead>
                                </table>
                            </div>
                        </div>
                         <div  id="PlanDataAdd"></div>
                        <div id="PlanServiceData"></div>

                        <div  id="PlanDataEdit"></div>
                        <div  id="PlanServiceEdit"></div>

                        <div  id="PlanView"></div>

                        <div  id="PlanSlabRatesView"></div>


        </div>
    </div>
<script type="text/javascript">

    var Plan_config = function(){
        
        var Regex = <?php echo json_encode($Regex); ?>;
        var chargeType = {'FIXED': 'Fixed', 'PERCENT': 'Percent'};
        var chargeMethod = {'CREDIT': 'Commission', 'DEBIT': 'Surcharge'};
        var Rols = {};
        var serviceArr = {};
        
        var plan_act_obj={action:false,type:""};
        
         var RoleWiseRole = function () {
            $.ajax({
                method: 'POST',
                url: 'Manage/RoleWiseRoleForPlan',
                dataType: 'JSON'
            }).done(function (response) {
                if (response)
                {
                    if (response.error == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error == 0) {

                        $.each(response.data, function (k, v) {
                            Rols[v.role_id] = v;
                        });
                    }else{
                         toastr.error(response.error_desc);
                    }
                }
            }).fail(function (err) {
                throw err;
            });
        }
          
         var serviceArr = function () {
            $.ajax({
                method: 'POST',
                url: 'Manage/ServiceFetchAll',
                dataType: 'JSON'
            }).done(function (response) {
                if (response)
                {
                    if (response.error == 2)
                    {
                        window.location.reload(true);
                    } else if (response.error == 0) {

                        $.each(response.data, function (k, v) {

                            serviceArr[v.service_id] = v;

                        });
                    }else{
                        toastr.error(response.error_desc);
                    }
                }
            }).fail(function (err) {
                throw err;
            });
         }  

         var Plantable = function(){
            
            var table = $('#Plantable').DataTable({
                "processing": true,
                "ajax": {
                    url: "Manage/PlanListTable",
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

                columns: [
                     {
                     "title" : "Plan Name",
                     "data": "plan_name",
                    },
                    {title: 'Plan Code', "data": 'plan_code'},
                    {title: 'For', "data": 'RoleName'},
                    {title: 'Plan Code', "data": 'plan_code'},
                    {title: 'For', "data": 'RoleName'},
                    {title: 'Active Status', data: 'is_active', class: 'all',
                        render: function (data, type, full, meta) {
                            var status = "";
                            if (full.is_active == 0) {
                                status = "Inactive";
                            } else if (full.is_active == 1) {
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
                          var btndt='';
                           btndt+='<a data-plan="' + full.plan_id + '" id="Plan_View" class="btn btn-info white-txt">View</a> ';
                           btndt+='<a data-plan="' + full.plan_id + '" id="Plan_Edit" class="btn btn-info white-txt">Edit</a> ';
                           btndt+='<a data-plan="' + full.plan_id + '" id="Plan_EditPlan" class="btn btn-info white-txt">Edit Service Rates</a> ';
                           btndt+='<a data-plan="' + full.plan_id + '" id="Plan_SlabServiceRates" class="btn btn-info white-txt">Edit Slab Rates</a>';
                          return btndt;
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

            /******************************************* Edit Service Rate ********************************************/
            table.on('click', '#Plan_EditPlan', function (e) {
                e.preventDefault();
                if(plan_act_obj.action===false && plan_act_obj.type=="")
                     {  
                $(this).addClass('ladda-button btn-ladda btn-ladda-spinner').attr('data-style', 'zoom-in');
                var d = $(this).ladda();
                d.ladda('start');
                
                var plan_id = $(this).data('plan');
                var row = $(this).closest('tr');
                var showtd = table.row(row).data();

                if (plan_id == showtd.plan_id) {
                    
                    plan_act_obj.action=true;
                    plan_act_obj.type='Plan Service Rate edit window open';
                    
                    
                    $.ajax({
                        method: 'POST',
                        url: 'Manage/FetchplanServiceList',
                        data: {data: plan_id,plan_name:showtd.plan_name},
                        dataType: 'JSON'
                    }).done(function (response) {
                        if (response)
                        {
                            if (response.error_data == 2)
                            {
                                window.location.reload(true);
                            } else if (response.error_data == 0) {
                                
                                d.ladda('stop');
                                var PlanService = {};
                                $.each(response.data, function (k, v) {

                                    PlanService[v.service_id] = v;
                                })

                                var str = '';
                                str += '<div class="width-100 mb-3 bord-bottom gray-header" id="first_title">' + showtd.plan_name + ' (' + showtd.plan_code + ') Services Edit</div>';

                                str += '<div class="form-wrp">';

                                str+='<div class="table-search-filter"><input type="text" id="search-input" placeholder="Search">';
                   // str+=' <span class="srch-icon-set"><i class="fas fa-search"></i></span>';
                    str+='</div>';

                                str += '<div class="table-responsive table-scrollable">';
                                str += '<table class="table service_tbl">';
                                str += '<thead>';
                                str += '<tr>';
                                str += '<th>Service Name</th>';
                                str += '<th>Charge Type</th>';
                                str += '<th>Charge Method</th>';
                                str += '<th>Rate</th>';
                                str += '<th>Capping Amount</th>';
                                str += '<th>Slab Applicable</th>';
                                str += '</tr>';
                                str += '</thead>';

                                str += '<tbody>';
                                console.log(serviceArr)
                                $.each(serviceArr, function (k, v) {
                                        console.log(v)
                                    if (v.service_id in PlanService) {

                                        var capAmt = PlanService[v.service_id].capping_amount ? PlanService[v.service_id].capping_amount : '';

                                        var slab;
                                        if (PlanService[v.service_id].slab_applicable == 1) {
                                            slab = 'checked';
                                        } else {
                                            slab = '';
                                        }

                                        str += '<tr>';

                                        str += '<td><span class="ServiceName" id="EditPlanService_ServiceName' + v.service_id + '" data-service="' + v.service_id + '">' + v.service_name + ' (' + v.type + ')</span></td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<select name="select" class="form-control input-xs ChargeType" id="EditPlanService_ChargeType' + v.service_id + '">';
                                        str += '<option value="">Select Charge Type</option>';

                                        $.each(Regex.ChargeType, function (k2, v2) {

                                            var sel = (k2 == PlanService[v.service_id].charge_type) ? 'selected' : '';
                                            str += '<option value="' + k2 + '" ' + sel + '>' + v2 + '</option>';
                                        });
                                        str += '</select>';
                                        str += '<span data-for="EditPlanService_ChargeType' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += '</td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<select name="select" class="form-control input-xs ChargeMethod" id="EditPlanService_ChargeMethod' + v.service_id + '">';
                                        str += '<option value="">Select Charge Method</option>';
                                        $.each(Regex.ChargeMethod, function (k1, v1) {
                                            var sel12 = (k1 == PlanService[v.service_id].charge_method) ? 'selected' : '';
                                            str += '<option value="' + k1 + '" ' + sel12 + '>' + v1 + '</option>';
                                        });
                                        str += ' </select>';
                                        str += '<span data-for="EditPlanService_ChargeMethod' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += ' </td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<input type="text" class="form-control Rate" placeholder="Rate" id="EditPlanService_Rate' + v.service_id + '" value="' + PlanService[v.service_id].rate + '">';
                                        str += '<span data-for="EditPlanService_Rate' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += '</td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<input type="text" class="form-control CappingAmount" placeholder="Capping Amount" id="EditPlanService_CappingAmount' + v.service_id + '" value="' + capAmt + '">';
                                        str += '<span data-for="EditPlanService_CappingAmount' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += ' </td>';

                                        str += '<td>';
                                        str += ' <div class="col-md-3" style="margin-top: 20px;">';
                                        str += ' <div class="form-group has-feedback">';
                                        str += '<div class="checkbox checkbox-switch">';
                                        str += '<label>';
                                        str += '<input type="checkbox" class="switch SlabApplicable" id="EditPlanService_SlabApplicable' + v.service_id + '" ' + slab + '>';
                                        str += 'Slab Applicable';
                                        str += '</label>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</td>';

                                        str += '</tr>';
                                    } else {

                                        str += '<tr>';

                                        str += '<td><span class="ServiceName" id="EditPlanService_ServiceName' + v.service_id + '" data-service="' + v.service_id + '">' + v.service_name + ' (' + v.type + ')</span></td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<select name="select" class="form-control input-xs ChargeType" id="EditPlanService_ChargeType' + v.service_id + '">';
                                        str += '<option value="">Select Charge Type</option>';
                                        $.each(Regex.ChargeType, function (k, v) {

                                            str += '<option value="' + k + '">' + v + '</option>';
                                        });
                                        str += '</select>';
                                        str += '<span data-for="EditPlanService_ChargeType' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += '</td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<select name="select" class="form-control input-xs ChargeMethod" id="EditPlanService_ChargeMethod' + v.service_id + '">';
                                        str += '<option value="">Select Charge Method</option>';
                                        $.each(Regex.ChargeMethod, function (k1, v1) {
                                            str += '<option value="' + k1 + '">' + v1 + '</option>';
                                        });
                                        str += ' </select>';
                                        str += '<span data-for="EditPlanService_ChargeMethod' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += ' </td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<input type="text" class="form-control Rate" placeholder="Rate" id="EditPlanService_Rate' + v.service_id + '">';
                                        str += '<span data-for="EditPlanService_Rate' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += '</td>';

                                        str += '<td>';
                                        str += '<div class="form-group">';
                                        str += '<input type="text" class="form-control CappingAmount" placeholder="Capping Amount" id="EditPlanService_CappingAmount' + v.service_id + '">';
                                        str += '<span data-for="EditPlanService_CappingAmount' + v.service_id + '"></span>';
                                        str += '</div>';
                                        str += ' </td>';

                                        str += '<td>';
                                        str += ' <div class="col-md-3" style="margin-top: 20px;">';
                                        str += ' <div class="form-group has-feedback">';
                                        str += '<div class="checkbox checkbox-switch">';
                                        str += '<label>';
                                        str += '<input type="checkbox" class="switch SlabApplicable" id="EditPlanService_SlabApplicable' + v.service_id + '">';
                                        str += 'Slab Applicable';
                                        str += '</label>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</div>';
                                        str += '</td>';

                                        str += '</tr>';
                                    }
                                });
                                str += '</tbody>';

                                str += '<tfoot>';
                                str += '<tr>';
                                str += '<td colspan="7" style="text-align: center;">';
                                str += '<button class="btn custom-blue-btn" id="PlanBackService" style="margin-right: 10px;">Back</button>';
                                str += '<button class="btn btn-primary" id="EditPlanService">Submit</button>';
                                str += '</td>';
                                str += '</tr>';
                                str += '</tfoot>';
                                str += '</table>';
                                str += '</div>';
                                str += '</div>';

                                $('.plan_listpage').hide();
                                $('#PlanServiceEdit').html(str).show();
                                

                                $(".SlabApplicable").bootstrapToggle({
                                    onText: 'Yes',
                                    offText: 'No'
                                });
                                KeyPress_Validation();

                                 $("#search-input").on("keyup", function() {

                        var value = $(this).val().toLowerCase();;
                        console.log(value);
                        $("table.table.service_tbl tbody tr").each(function(index) {

                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

                        });
                    });

                                $('#EditPlanService').click(function (e) {
                                    e.preventDefault();
                                    if(plan_act_obj.action===true && plan_act_obj.type=='Plan Service Rate edit window open')
                                        {
                                    var params = {'valid': true};
                                    var actid = $(this).attr('id');

                                    params.service = {};

                                    $.each(serviceArr, function (k, v) {
                                        var ServiceId = $('#' + actid + '_ServiceName' + v.service_id + '').data('service');
                                        var ChargeType = $('#' + actid + '_ChargeType' + v.service_id + ' option:selected').val();
                                        var ChargeMethod = $('#' + actid + '_ChargeMethod' + v.service_id + ' option:selected').val();
                                        var Rate = $('#' + actid + '_Rate' + v.service_id + '').val();
                                        var CappingAmount = $('#' + actid + '_CappingAmount' + v.service_id + '').val();
                                        var SlabApplicable = $('#' + actid + '_SlabApplicable' + v.service_id + '').is(":checked");

                                        if (ChargeType != '' || ChargeMethod != '' || Rate != '') {
                                            params.service[v.service_id] = {'ServiceName': ServiceId, 'ChargeType': ChargeType, 'ChargeMethod': ChargeMethod, 'Rate': Rate, 'CappingAmount': CappingAmount, 'SlabApplicable': SlabApplicable,
                                                'ChargeTypeDivId': '' + actid + '_ChargeType' + v.service_id + '', 'ChargeMethodDivId': '' + actid + '_ChargeMethod' + v.service_id + '', 'RateDivId': '' + actid + '_Rate' + v.service_id + '',
                                                'CappingAmountDivId': '' + actid + '_CappingAmount' + v.service_id + ''};
                                        }
                                    });

                                    $.each(params.service, function (key, value) {

                                        if (!validate({'id': '' + value.ChargeTypeDivId + '', 'type': 'CHARGETYPE', 'data': value.ChargeType, 'error': true, msg: $('#' + value.ChargeTypeDivId + '').attr('placeholder')})) {
                                            params.valid = false;
                                        }

                                        if (!validate({'id': '' + value.ChargeMethodDivId + '', 'type': 'CHARGEMETHOD', 'data': value.ChargeMethod, 'error': true, msg: $('#' + value.ChargeMethodDivId + '').attr('placeholder')})) {
                                            params.valid = false;
                                        }

                                        if (!validate({'id': '' + value.RateDivId + '', 'type': 'RATE', 'data': value.Rate, 'error': true, msg: $('#' + value.RateDivId + '').attr('placeholder')})) {
                                            params.valid = false;
                                        }

                                        if (value.CappingAmount != '') {
                                            if (!validate({'id': '' + value.CappingAmountDivId + '', 'type': 'RATE', 'data': value.CappingAmount, 'error': true, msg: $('#' + value.CappingAmountDivId + '').attr('placeholder')})) {
                                                params.valid = false;
                                            }
                                        }
                                    });

                                    params.PlanId = plan_id;


                                    if (params.valid == true) {
                                        
                                        plan_act_obj.action=true;
                                        plan_act_obj.type='Plan Service Rate edit action sent';
                                        
                                        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                        var la = $(this).ladda();
                                        la.ladda('start');

                                        $.ajax({
                                            method: 'POST',
                                            url: 'Manage/PlanServiceDataUpdate',
                                            data: params,
                                            dataType: 'JSON'
                                        }).done(function (response) {
                                            if (response)
                                            {
                                               if (response.error == 2)
                                                {
                                                    window.location.reload(true);
                                                } else if (response.error == 0) {
                                                    
                                                    toastr.success(response.msg);
                                                    $('#PlanServiceEdit').hide().html('');
                                                    $('.plan_listpage').show();
                                                    table.ajax.reload();
                                                    
                                                    plan_act_obj.action=false;
                                                    plan_act_obj.type='';
                                                    
                                                }
                                                else{
                                                    plan_act_obj.action=true;
                                                    plan_act_obj.type='Plan Service Rate edit window open';
                                                    la.ladda('stop');
                                                    toastr.error(response.error_desc);
                                                }
                                                
                                            }
                                            
                                        }).fail(function (err) {
                                            plan_act_obj.action=true;
                                            plan_act_obj.type='Plan Service Rate edit window open';
                                            toastr.error('Something went wrong, try again later');
                                            la.ladda('stop');
                                            
                                        });
                                    }
                                    
                                    }else{
                                            toastr.error('Please Wait!!');
                                        }
                                });

                                $('#PlanBackService').click(function (e) {
                                    e.preventDefault();
                                    
                                    if(plan_act_obj.action===true && plan_act_obj.type=='Plan Service Rate edit window open')
                                        {
                                            $('#PlanServiceEdit').hide().html('');
                                            $('.plan_listpage').show();
                                            plan_act_obj.action=false;
                                            plan_act_obj.type='';
                                            
                                        }else{
                                            toastr.error('Please Wait!!');
                                        }
                                    
                                });
                            }
                            else{
                                plan_act_obj.action=false;
                                plan_act_obj.type='';
                                toastr.error(response.error_desc);
                                d.ladda('stop');
                            }
                        }
                    }).fail(function (err) {
                        plan_act_obj.action=false;
                        plan_act_obj.type='';
                        toastr.error('Something went wrong, try again later');
                        d.ladda('stop');
                        
                    });
                    
                    }
                         
                     }else{
                         toastr.error('Please Wait!!');
                     }
                
            });
            /******************************************End Edit Service Rate *******************************************/
             
            /***************************************view plan details****************************************************/
             table.on('click', '#Plan_View', function (e) {
                e.preventDefault();
                var plan_id = $(this).data('plan');
                var row = $(this).closest('tr');
                var showtd = table.row(row).data();
                if (plan_id == showtd.plan_id) 
                {
                    
                    if(plan_act_obj.action===false && plan_act_obj.type=="")
                     {   
                         
                        plan_act_obj.action=true;
                        plan_act_obj.type='View Plan Details';
                    
                    var viewla=$(this).ladda();
                    $(this).addClass('ladda-button').attr('data-style','zoom-in');
                    viewla.ladda('start');
                    
                    $.ajax({
                        method: 'POST',
                        url: 'Manage/FetchplanServiceList',
                        data: {data: plan_id},
                        dataType: 'JSON'
                    }).done(function (response) {
                        if (response)
                        {
                            if (response.error_data == 2)
                            {
                                window.location.reload(true);
                            } else if (response.error_data == 0) {
                                
                                viewla.ladda('stop');
                                
                                var desc = showtd.plan_description ? showtd.plan_description : '';

                                var actie;
                                if (showtd.is_active == 1) {
                                    actie = 'Yes';
                                } else {
                                    actie = 'No';
                                }

                                var str = '';
                                str += '<div class="col-lg-12 mx-auto ">';

                                str += '<div class="row mb-20">';
                                str += '<div class="col-lg-12 view-details-card">';

                                str += '<div class="row">';
                                str += '<div class="col-lg-12 view-detail-top-header">';
                                str += '<h4 style="border-bottom: #d5d5d5 2px dotted;">Full Details</h4>';
                                str += '</div>';
                                str += '</div>';

                                str += '<div class="row details-divider">';

                                str += '<div class="col-lg-6">';
                                str += ' <label>Plan Name</label>';
                                str += '<p>' + showtd.plan_name + '</p>';
                                str += ' </div>';

                                str += '<div class="col-lg-6">';
                                str += ' <label>Plan Code</label>';
                                str += ' <p>' + showtd.plan_code + '</p>';
                                str += ' </div>';

                                str += '</div>';

                                str += '<div class="row details-divider">';

                                str += '<div class="col-lg-6">';
                                str += '<label>Plan For</label>';
                                str += '<p>' + showtd.RoleName + '</p>';
                                str += '</div>';

                                str += '<div class="col-lg-6">';
                                str += ' <label>Plan Description</label>';
                                str += '<p>' + desc + '</p>';
                                str += ' </div>';

                                str += '</div>';

                                str += '<div class="row details-divider">';

                                str += ' <div class="col-lg-6">';
                                str += '<label>Is Active</label>';
                                str += '<p>' + actie + '</p>';
                                str += '</div>';

                                str += ' </div>';

                                str += ' </div>';
                                str += '</div>';
                                str += '</div>';

                                //----------//

                                str += '<div class="col-lg-12 mx-auto ">';

                                str += '<div class="row mb-20">';
                                str += '<div class="col-lg-12 view-details-card">';

                                str += '<div class="row">';
                                str += '<div class="col-lg-12 view-detail-top-header">';
                                str += '<h4>Plan Services</h4>';
                                str += '</div>';
                                str += '</div>';

                                str += '<table class="table">';
                                str += '<thead>';
                                str += '<tr>';
                                str += '<th>Service Name</th>';
                                str += '<th>Charge Type</th>';
                                str += '<th>Charge Method</th>';
                                str += '<th>Rate</th>';
                                str += '<th>Capping Amount</th>';
                                str += '<th>Slab Applicable</th>';
                                str += '</tr>';
                                str += '</thead>';
                                str += '<tbody>';
                                var plan_displservobj={}
                                if (response.data.length > 0) {
                                    
                                    $.each(response.data, function (k, v) {

                                        plan_displservobj[v.service_id]=v;
                                        
                                        var slb = v.slab_applicable == 1 ? 'Yes' : 'No';
                                        var capa = v.capping_amount ? v.capping_amount : '';
                                        str += '<tr>';
                                        str += '<td>' + v.service_name + ' (' + v.servicetype + ')</td>';
                                        str += '<td>' + v.charge_type + '</td>';
                                        str += '<td>' + v.charge_method + '</td>';
                                        
                                        var displrate=(v.slab_applicable == 1)?((v.slabdata.length>0)?'<a data-servid="'+v.service_id+'" href="javascript:void();" class="get_slab_ratedata_'+plan_id+'">View Slab Rates</a>':"No slab entries exist"):v.rate;
                                        
                                        str += '<td>' + displrate + '</td>';
                                        
                                        str += '<td>' + capa + '</td>';
                                        str += '<td>' + slb + '</td>';
                                        str += '</tr>';

                                    });
                                } else {
                                    str += '<tr style="text-align: center;"> ';
                                    str += '<td colspan="6">No Data</td>';
                                    str += '</tr>';
                                }
                                str += '</tbody>';
                                str += '<tfoot>';
                                str += '</tfoot>';
                                str += '</table>';

                                str += ' </div>';
                                str += '</div>';

                                str += '<div class="row">';
                                str += '<div class="col-md-12" style="text-align: center;">';
                                str += '<a id="PlanBackService" class="btn btn-info white-txt">Back</a>';
                                str += '</div>';
                                str += '</div>';
                                str += '</div>';


                                $('#PlanView').html(str).show();
                                 $('.plan_listpage').hide();
                                
                                $('.get_slab_ratedata_'+plan_id).click(function(e){
                                   e.preventDefault();
                                    var servid=$(this).data('servid');
                                   if(!$.isEmptyObject(plan_displservobj))
                                       {
                                           if(servid in plan_displservobj)
                                               {
                                                   
            var str = '<div class="modal fade" id="slab_details_for_'+servid+'_plan_'+plan_id+'"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                str += '<div class="modal-dialog modal-lg" role="document" id="">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                str += '<h5 class="modal-title" id="head_ttl">Slab Details for '+plan_displservobj[servid].service_name+' ('+plan_displservobj[servid].servicetype+') in Plan - '+plan_displservobj[servid].plan_name+'</h5>';
                str += '<h5 class="modal-title" id="head_ttl2" style="display:none;"></h5>';
                str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                str += '<span aria-hidden="true">&times;</span>';
                str += '</button>';
                str += '</div>';
                str += '<div class="modal-body">';
                str += '<div id="model_content">';
                                        
                str+='<div class="row">'; 
                    str+='<div class="col-md-12">';
                                str+='<div class="table-responseive">';
                                
                                
                                    
                                 str+='<table class="table table-stripped">';
                                 str+='<thead>';
                                 str+='<th>Minimum Anount</th>';
                                 str+='<th>Maximum Amount</th>';
                                 str+='<th>Charge Type</th>';
                                 str+='<th>Charge Method</th>';
                                 str+='<th>Rate</th>';
                                 str+='</thead>';
                                 str+='<tbody>';
                                 if(plan_displservobj[servid].slabdata.length>0)
                                     {
                                         $.each(plan_displservobj[servid].slabdata,function(slbk,slbv){
                                             
                                         var dspl_ct=(slbv.charge_type in Regex.ChargeType)?Regex.ChargeType[slbv.charge_type]:slbv.charge_type;
                                         var dspl_cm=(slbv.charge_method in Regex.ChargeMethod)?Regex.ChargeMethod[slbv.charge_method]:slbv.charge_method;
                                             
                                         str+='<tr>';
                                         str+='<td>'+slbv.min_amnt+'</td>';
                                         str+='<td>'+slbv.max_amnt+'</td>';
                                            
                                         str+='<td>'+dspl_ct+'</td>';
                                         str+='<td>'+dspl_cm+'</td>';
                                         str+='<td>'+slbv.rate+'</td>';
                                         str+='</tr>';
                                             
                                         });
                                         
                                     }else{
                                         str+='<tr>';
                                         str+='<td colspan="5">No Slab Data Exist</td>';
                                         str+='</tr>';
                                     }
                                 str+='</tbody>';
                                 str+='</table>';        
                                        
                str+='</div>';
                    str += '</div>'; 
                str += '</div>'; 
                                        
                str += '</div>';                       
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                
                $('body').append(str);                        
                                        
                $('#slab_details_for_'+servid+'_plan_'+plan_id).modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                })
                $('#slab_details_for_'+servid+'_plan_'+plan_id).on('hidden.bs.modal', function () {
                    $('#slab_details_for_'+servid+'_plan_'+plan_id).remove();
                });                                                
                                                   
                                                   
                                               }else{
                                                   toastr.error('Something went wrong');
                                               }
                                       }
                                });
                                
                               
                                $('#PlanBackService').click(function (e) {
                                    e.preventDefault();
                                    
                                    $('#PlanView').html('').hide();
                                    $('.plan_listpage').show();
                                    plan_act_obj.action=false;
                                    plan_act_obj.type='';
                                });
                            }
                            else{
                                toastr.error(response.error_desc);
                                viewla.ladda('stop');
                                plan_act_obj.action=false;
                                plan_act_obj.type='';
                            }
                        }
                    }).fail(function (err) {
                        plan_act_obj.action=false;
                        plan_act_obj.type='';
                        toastr.error('Something went wrong, try again later');
                        viewla.ladda('stop');
                        throw err;
                    });
                    
                    }
                     else{
                         toastr.error('Please Wait!!');
                     }
                }
            });

            /***********************************Edit slab rates**********************************************/
            table.on('click', '#Plan_SlabServiceRates', function (e) {
                e.preventDefault();
                
                if(plan_act_obj.action===false && plan_act_obj.type=="")
                     { 
                
                var plan_id = $(this).data('plan');
                var row = $(this).closest('tr');
                var showtd = table.row(row).data();

                if (plan_id == showtd.plan_id) {
                    
                    plan_act_obj.action=true;
                    plan_act_obj.type='Slab wise rate view';
                    
                    var slabbtnla=$(this).ladda();
                    $(this).addClass('ladda-button').attr('data-style','zoom-in');
                    slabbtnla.ladda('start');
                    
                    $.ajax({
                        method: 'POST',
                        url: 'Manage/PlanDataSlabRates',
                        data: {data: plan_id},
                        dataType: 'JSON'
                    }).done(function (response) {
                        if (response)
                        {
                            if (response.error_data == 2)
                            {
                                window.location.reload(true);
                            } else if (response.error_data == 0) {
                               
                                var slab = '';

                                slab += '<div class="col-lg-12">';
                                 slab += '<div class="row">';
                                  slab += '<div class="col-lg-9">';
                                slab += '<div class="width-100 mb-3 bord-bottom gray-header" id="first_title">' + showtd.plan_name + ' (' + showtd.plan_code + ') Slab Rates Edit</div></div> <div class="col-lg-3"><button class="btn btn-default btn-lg" style="float: right;bottom: 60px;" id="AddBackFront">Back</button>';
                                 slab += '</div>';
                                 slab += '</div>';

                                slab += '<div class="form-wrp">';
                                slab += '<div class="row">';

                                slab += '<div class="col-md-6">';
                                slab += '<select name="select" class="form-control input-xs PlanFor" id="SlabRatesEdit_SlabService">';
                                slab += '<option value="">Select Service</option>';
                                $.each(response.data, function (k4, v4) {
                                    slab += '<option value="' + v4.service_id + '">' + v4.service_name + ' (' + v4.servicetype + ')</option>';
                                });
                                slab += '</select>';
                                slab += '</div>';

                                slab += '<div id="SlabServiceDiv" style="width:100%;float:left;">';
                                slab += '</div>';
                                slab += '</div>';
                                slab += '</div>';
                                slab += '</div>';
                                
                                $('.plan_listpage').hide();
                                
                                $('#PlanSlabRatesView').html(slab).show();

                                slabbtnla.ladda('stop');
                                
                                $('#AddBackFront').click(function (e) {
                                    e.preventDefault();
                                    if((plan_act_obj.action===true && plan_act_obj.type=="Slab wise rate view") || plan_act_obj.action===true && plan_act_obj.type=="Slab wise rate view with data start")
                                    { 
                                        
                                    $('.plan_listpage').show();
                                    $('#PlanSlabRatesView').hide().html('');
                                    plan_act_obj.action=false;
                                    plan_act_obj.type='';
                                        
                                     }else{
                                         toastr.error('Please Wait!!');
                                     }
                                });


                                $('#SlabRatesEdit_SlabService').on('change', function (e) {
                                    
                                    if(plan_act_obj.action===true && plan_act_obj.type=="Slab wise rate view")
                                    { 
                                     
                                       plan_act_obj.action=true;
                                       plan_act_obj.type='Slab wise rate view with data start';
                                        
                                    var plsvId = $(this).val();
                                    $.ajax({
                                        method: 'POST',
                                        url: 'Manage/FetchSlabRates',
                                        data: {service_id: plsvId,plan_id:plan_id},
                                        dataType: 'JSON'
                                    }).done(function (response) {
                                        if (response)
                                        {
                                           if (response.error_data == 2)
                                            {
                                                window.location.reload(true);
                                            } else if (response.error_data == 0) {
                                                console.log(response.data)
                                                var sl = '';
                                                var slabCount = 0;
                                                  var PlanService = {};
                                                $.each(response.data, function (k, v) {

                                                    PlanService[v.service_id] = v;

                                                })

                                                sl += '<h4 style="border-bottom: #e6e6e6 1px solid;margin-top: 30px;">Slab Rates</h4>';
                                                if (response.data.length > 0) {
                                                    var arrLength = response.data.length;
                                                    $.each(response.data, function (k4, v4) {
                                                        sl += '<div class="row SlabRates" style="margin-top: 30px;" id="SlabRates_' + slabCount + '" >';
                                                        sl += '<div class="col-md-2">';
                                                        sl += '<div class="form-group">';
                                                        sl += '<label>Min Amount<span class="mandotry">*</span></label>';
                                                        sl += '<input type="text" class="form-control input-lg Rate" placeholder="Minimum Amount" id="SlabEdit_MinimumAmount_' + slabCount + '" value="' + v4.min_amnt + '">';
                                                        sl += '<span data-for="SlabEdit_MinimumAmount_' + slabCount + '"></span>';
                                                        sl += '</div>';

                                                        sl += '</div>';

                                                        sl += '<div class="col-md-2">';

                                                        sl += '<div class="form-group">';
                                                        sl += '<label>Max Amount<span class="mandotry">*</span></label>';
                                                        sl += '<input type="text" class="form-control input-lg Rate" placeholder="Maximun Amount" id="SlabEdit_MaximunAmount_' + slabCount + '" value="' + v4.max_amnt + '">';
                                                        sl += '<span data-for="SlabEdit_MaximunAmount_' + slabCount + '"></span>';
                                                        sl += '</div>';

                                                        sl += '</div>';

                                                        sl += '<div class="col-md-2">';
                                                        sl += '<div class="form-group">';
                                                        sl += '<label>Charge Type<span class="mandotry">*</span></label>';
                                                        sl += '<select name="select" class="form-control input-xs ChargeType" id="SlabEdit_ChargeType_' + slabCount + '">';
                                                        sl += '<option value="">Select Charge Type</option>';

                                                    $.each(Regex.ChargeType, function (k2, v2) {

                                                         var sel = (k2 == v4.charge_type) ? 'selected' : '';
                                                        sl += '<option value="' + k2 + '"  ' + sel + '>' + v2 + '</option>';
                                                    });
                                                    sl += '</select>';
                                                    sl += '<span data-for="SlabEdit_ChargeType_' + slabCount + '"></span>';
                                                    sl += '</div>';
                                                    sl += '</div>';

                                                    sl += '<div class="col-md-2">';
                                                    sl += '<div class="form-group">';
                                                    sl += '<label>Charge Method<span class="mandotry">*</span></label>';
                                                    sl += '<select name="select" class="form-control input-xs ChargeMethod" id="SlabEdit_ChargeMethod_' + slabCount + '">';
                                                    sl += '<option value="">Select Charge Method</option>';
                                                    $.each(Regex.ChargeMethod, function (k1, v1) {
                                                         var sel12 = (k1 == v4.charge_method) ? 'selected' : '';
                                                        sl += '<option value="' + k1 + '"  '+sel12+'>' + v1 + '</option>';
                                                    });
                                                    sl += ' </select>';
                                                    sl += '<span data-for="SlabEdit_ChargeMethod_' + slabCount + '"></span>';

                                                    sl += '</div>';

                                                    sl += '</div>';


                                                        sl += '<div class="col-md-2">';

                                                        sl += '<div class="form-group">';
                                                        sl += '  <label>Rate<span class="mandotry">*</span></label>';
                                                        sl += '<input type="text" class="form-control input-lg Rate" placeholder="Rate" id="SlabEdit_Rate_' + slabCount + '" value="' + v4.rate + '">';
                                                        sl += '<span data-for="SlabEdit_Rate_' + slabCount + '"></span>';
                                                        sl += '</div>';

                                                        sl += '</div>';


                                                        if (k4 == arrLength - 1) {

                                                            sl += '<div class="col-md-2" id="slabbutton_' + slabCount + '">';
                                                            sl += '<button type="button" class="btn btn-primary AddRowFrSlab" style="margin-top: 27px;margin-left: 50px;" id="AddRowFrSlab_' + slabCount + '"><i class="fa fa-plus" aria-hidden="true"></i></button> ';

                                                             sl += '</div>';
                                                        }


                                                        sl += '<div class="col-md-2" id="slabbutton_' + slabCount + '" style="display:none;">';
                                                        sl += '<button type="button" class="btn btn-primary AddRowFrSlab" style="margin-top: 27px;margin-left: 50px;" id="AddRowFrSlab"><i class="fa fa-plus" aria-hidden="true"></i></button> <button type="button" class="btn btn-danger MinusRowFrSlab" style="margin-top: 27px;margin-left: 50px;display:none" id="MinusRowFrSlab_' + slabCount + '"> <i class="fa fa-minus" aria-hidden="true"> </i></button>';

                                                        sl += '';

                                                        sl += '</div>';
                                                         sl += '</div>';

                                                        slabCount++;

                                                    });

                                                } else {


                                                    sl += '<div class="row SlabRates" style="margin-top: 30px;" id="SlabRates_' + slabCount + '" >';
                                                    sl += '<div class="col-md-2">';
                                                    sl += '<div class="form-group">';
                                                    sl += '<label>Min Amount<span class="mandotry">*</span></label>';
                                                    sl += '<input type="text" class="form-control input-lg Rate" placeholder="Minimum Amount" id="SlabEdit_MinimumAmount_' + slabCount + '">';
                                                    sl += '<span data-for="SlabEdit_MinimumAmount_' + slabCount + '"></span>';
                                                    sl += '</div>';
                                                    sl += '</div>';

                                                    sl += '<div class="col-md-2">';
                                                    sl += '<div class="form-group">';
                                                    sl += '<label>Max Amount<span class="mandotry">*</span></label>';
                                                    sl += '<input type="text" class="form-control input-lg Rate" placeholder="Maximun Amount" id="SlabEdit_MaximunAmount_' + slabCount + '">';
                                                    sl += '<span data-for="SlabEdit_MaximunAmount_' + slabCount + '"></span>';
                                                    sl += '</div>';
                                                    sl += '</div>';

                                                    sl += '<div class="col-md-2">';
                                                    sl += '<div class="form-group">';
                                                    sl += '<label>Charge Type<span class="mandotry">*</span></label>';
                                                    sl += '<select name="select" class="form-control input-xs ChargeType" id="SlabEdit_ChargeType_' + slabCount+ '">';
                                                    sl += '<option value="">Select Charge Type</option>';

                                                    $.each(Regex.ChargeType, function (k2, v2) {

                                                       // var sel = (k2 == PlanService[v2.service_id].charge_type) ? 'selected' : '';
                                                        sl += '<option value="' + k2 + '" >' + v2 + '</option>';
                                                    });
                                                    sl += '</select>';
                                                    sl += '<span data-for="SlabEdit_ChargeType_' + slabCount + '"></span>';
                                                    sl += '</div>';
                                                    sl += '</div>';

                                                    sl += '<div class="col-md-2">';
                                                    sl += '<div class="form-group">';
                                                    sl += '<label>Method<span class="mandotry">*</span></label>';
                                                    sl += '<select name="select" class="form-control input-xs ChargeMethod" id="SlabEdit_ChargeMethod_' + slabCount + '">';
                                                    sl += '<option value="">Select Charge Method</option>';
                                                    $.each(Regex.ChargeMethod, function (k1, v1) {
                                                       // var sel12 = (k1 == PlanService[v.service_id].charge_method) ? 'selected' : '';
                                                        sl += '<option value="' + k1 + '" >' + v1 + '</option>';
                                                    });
                                                    sl += ' </select>';
                                                    sl += '<span data-for="SlabEdit_ChargeMethod_' + slabCount + '"></span>';

                                                    sl += '</div>';

                                                    sl += '</div>';

                                                    sl += '<div class="col-md-2">';

                                                    sl += '<div class="form-group">';
                                                    sl += '  <label>Rate<span class="mandotry">*</span></label>';
                                                    sl += '<input type="text" class="form-control input-lg Rate" placeholder="Rate" id="SlabEdit_Rate_' + slabCount + '">';
                                                    sl += '<span data-for="SlabEdit_Rate_' + slabCount + '"></span>';
                                                    sl += '</div>';

                                                    sl += '</div>';

                                                    sl += '<div class="col-md-2" id="slabbutton_' + slabCount + '">';
                                                    sl += '<button type="button" class="btn btn-primary AddRowFrSlab" style="margin-top: 27px;margin-left: 50px;" id="AddRowFrSlab_' + slabCount + '"><i class="fa fa-plus" aria-hidden="true"></i></button>';
                                                    sl += '</div>';

                                                    sl += '</div>';

                                                    slabCount += 1;
                                                }

                                                sl += '<div class="row">';
                                                sl += ' <div class="col-md-12"> ';
                                                sl += '<div class="form-group has-feedback text-center"> ';
                                                // sl += '<button class="btn btn-default btn-lg" style="margin-top: 26px;width: 150px;" id="AddBack">Back</button> ';
                                                sl += ' <button type="submit" class="btn btn-primary btn-lg SlabEdit" style="margin-top: 26px;width: 150px;" id="SlabEdit">Proceed</button>';
                                                sl += '</div>';
                                                sl += '</div>';
                                                sl += '</div>';

                                                $('#SlabServiceDiv').html(sl).show();
                                                KeyPress_Validation();

                                                 //-------------------//

                                                var c = slabCount - 1;

                                                $('#MinusRowFrSlab_' + c + '').click(function (e) {
                                                    e.preventDefault();
                                                    var b = c - 1;
                                                    $('#SlabRates_' + c + '').remove();
                                                    $('#slabbutton_' + b + '').show();
                                                    slabCount = slabCount - 1;
                                                    if (slabCount > 1) {
                                                        $('.MinusRowFrSlab').show();
                                                    }
                                                });

                                                $('.MinusRowFrSlab').click(function (e) {

                                                    e.preventDefault();
                                                    var r = slabCount - 1;
                                                    var s = r - 1;
                                                    $('#SlabRates_' + r + '').remove();
                                                    $('#slabbutton_' + s + '').show();
                                                    slabCount = slabCount - 1;
                                                    if (slabCount <= 1) {
                                                        $('.MinusRowFrSlab').hide();
                                                    }
                                                });



                                                //-------------------//
                                                var c = slabCount - 1;

                                                $('#AddRowFrSlab_' + c + '').on('click', function (e) {
                                                    e.preventDefault();
                                                    $('#slabbutton_' + c + '').hide();
                                                    InfiniteLopForSlab(slabCount);
                                                });


                                                //-------------------//

                                                $('#AddRowFrSlab').click(function (e) {
                                                    e.preventDefault();
                                                    var ra = '';
                                                    var c = slabCount;
                                                    var h = slabCount - 1;

                                                    $('#slabbutton_' + h + '').hide();

                                                    ra += '<div class="row SlabRates" style="margin-top: 30px;" id="SlabRates_' + c + '">';
                                                    ra += '<div class="col-md-2">';
                                                    ra += '<div class="form-group">';
                                                    ra += '<label>Minimum Amount<span class="mandotry">*</span></label>';
                                                    ra += '<input type="text" class="form-control input-lg Rate" placeholder="Minimum Amount" id="SlabEdit_MinimumAmount_' + c + '">';
                                                    ra += '<span data-for="SlabEdit_MinimumAmount_' + c + '"></span>';
                                                    ra += '</div>';

                                                    ra += '</div>';

                                                    ra += '<div class="col-md-2">';

                                                    ra += '<div class="form-group">';
                                                    ra += '<label>Maximun Amount<span class="mandotry">*</span></label>';
                                                    ra += '<input type="text" class="form-control input-lg Rate" placeholder="Maximun Amount" id="SlabEdit_MaximunAmount_' + c + '">';
                                                    ra += '<span data-for="SlabEdit_MaximunAmount_' + c + '"></span>';
                                                    ra += '</div>';

                                                    ra += '</div>';


                                                      ra += '<div class="col-md-2">';
                                                    ra += '<div class="form-group">';
                                                    ra += '<label>Charge Type<span class="mandotry">*</span></label>';
                                                    ra += '<select name="select" class="form-control input-xs ChargeType" id="SlabEdit_ChargeType_' + c+ '">';
                                                    ra += '<option value="">Select Charge Type</option>';

                                                    $.each(Regex.ChargeType, function (k2, v2) {

                                                       // var sel = (k2 == PlanService[v2.service_id].charge_type) ? 'selected' : '';
                                                        ra += '<option value="' + k2 + '" >' + v2 + '</option>';
                                                    });
                                                    ra += '</select>';
                                                    ra += '<span data-for="SlabEdit_ChargeType_' + c + '"></span>';
                                                    ra += '</div>';
                                                    ra += '</div>';

                                                    ra += '<div class="col-md-2">';
                                                    ra += '<div class="form-group">';
                                                    ra += '<label>Method<span class="mandotry">*</span></label>';
                                                    ra += '<select name="select" class="form-control input-xs ChargeMethod" id="SlabEdit_ChargeMethod_' + c + '">';
                                                    ra += '<option value="">Select Charge Method</option>';
                                                    $.each(Regex.ChargeMethod, function (k1, v1) {
                                                       // var sel12 = (k1 == PlanService[v.service_id].charge_method) ? 'selected' : '';
                                                        ra += '<option value="' + k1 + '" >' + v1 + '</option>';
                                                    });
                                                    ra += ' </select>';
                                                    ra += '<span data-for="SlabEdit_ChargeMethod_' + c + '"></span>';

                                                    ra += '</div>';

                                                    ra += '</div>';

                                                    ra += '<div class="col-md-2">';

                                                    ra += '<div class="form-group">';
                                                    ra += '  <label>Rate<span class="mandotry">*</span></label>';
                                                    ra += '<input type="text" class="form-control input-lg Rate" placeholder="Rate" id="SlabEdit_Rate_' + c + '">';
                                                    ra += '<span data-for="SlabEdit_Rate_' + c + '"></span>';
                                                    ra += '</div>';

                                                    ra += '</div>';

                                                    ra += '<div class="col-md-2" id="slabbutton_' + c + '">';
                                                    ra += '<button type="button" class="btn btn-primary" style="margin-top: 27px;margin-left: 50px;" id="AddRowFrSlab_' + c + '"><i class="fa fa-plus" aria-hidden="true"></i></button> <button type="button" class="btn btn-danger" style="margin-top: 27px;margin-left: 50px;" id="MinusRowFrSlab_' + c + '"><i class="fa fa-minus" aria-hidden="true"></i></button>';
                                                    ra += '</div>';
                                                    ra += '</div>';



                                                    $(ra).insertAfter('#SlabRates_' + h + '');
                                                    slabCount = slabCount + 1;
                                                    KeyPress_Validation();
                                                    $('#AddRowFrSlab_' + c + '').click(function (e) {
                                                        e.preventDefault();
                                                        var b = c + 1;
                                                        $('#slabbutton_' + c + '').hide();
                                                        InfiniteLopForSlab(b);
                                                    });

                                                    $('#MinusRowFrSlab_' + c + '').click(function (e) {
                                                        e.preventDefault();
                                                        console.log('32424');
                                                        var b = c - 1;
                                                        $('#SlabRates_' + c + '').remove();
                                                        $('#slabbutton_' + b + '').show();
                                                        slabCount = slabCount - 1;
                                                    });
                                                });

                                                var InfiniteLopForSlab = function (b) {
                                                    var fa = '';

                                                    fa += '<div class="row SlabRates" style="margin-top: 30px;" id="SlabRates_' + b + '">';
                                                    fa += '<div class="col-md-2">';

                                                    fa += '<div class="form-group">';
                                                    fa += '<label>Min Amount<span class="mandotry">*</span></label>';
                                                    fa += '<input type="text" class="form-control input-lg Rate" placeholder="Minimum Amount" id="SlabEdit_MinimumAmount_' + b + '">';
                                                    fa += '<span data-for="SlabEdit_MinimumAmount_' + b + '"></span>';
                                                    fa += '</div>';

                                                    fa += '</div>';

                                                    fa += '<div class="col-md-2">';

                                                    fa += '<div class="form-group">';
                                                    fa += '<label>Max Amount<span class="mandotry">*</span></label>';
                                                    fa += '<input type="text" class="form-control input-lg Rate" placeholder="Maximun Amount" id="SlabEdit_MaximunAmount_' + b + '">';
                                                    fa += '<span data-for="SlabEdit_MaximunAmount_' + b + '"></span>';
                                                    fa += '</div>';

                                                    fa += '</div>';

                                                      fa += '<div class="col-md-2">';
                                                    fa += '<div class="form-group">';
                                                    fa += '<label>Charge Type<span class="mandotry">*</span></label>';
                                                    fa += '<select name="select" class="form-control input-xs ChargeType" id="SlabEdit_ChargeType_' + b+ '">';
                                                    fa += '<option value="">Select Charge Type</option>';

                                                    $.each(Regex.ChargeType, function (k2, v2) {

                                                       // var sel = (k2 == PlanService[v2.service_id].charge_type) ? 'selected' : '';
                                                        fa += '<option value="' + k2 + '" >' + v2 + '</option>';
                                                    });
                                                    fa += '</select>';
                                                    fa += '<span data-for="SlabEdit_ChargeType_' + b + '"></span>';
                                                    fa += '</div>';
                                                    fa += '</div>';

                                                    fa += '<div class="col-md-2">';
                                                    fa += '<div class="form-group">';
                                                    fa += '<label>Method<span class="mandotry">*</span></label>';
                                                    fa += '<select name="select" class="form-control input-xs ChargeMethod" id="SlabEdit_ChargeMethod_' + b + '">';
                                                    fa += '<option value="">Select Charge Method</option>';
                                                    $.each(Regex.ChargeMethod, function (k1, v1) {
                                                       // var sel12 = (k1 == PlanService[v.service_id].charge_method) ? 'selected' : '';
                                                        fa += '<option value="' + k1 + '" >' + v1 + '</option>';
                                                    });
                                                    fa += ' </select>';
                                                    fa += '<span data-for="SlabEdit_ChargeMethod_' + b + '"></span>';

                                                    fa += '</div>';

                                                    fa += '</div>';


                                                    fa += '<div class="col-md-2">';

                                                    fa += '<div class="form-group">';
                                                    fa += '  <label>Rate<span class="mandotry">*</span></label>';
                                                    fa += '<input type="text" class="form-control input-lg Rate" placeholder="Rate" id="SlabEdit_Rate_' + b + '">';
                                                    fa += '<span data-for="SlabEdit_Rate_' + b + '"></span>';
                                                    fa += '</div>';

                                                    fa += '</div>';

                                                    fa += '<div class="col-md-2" id="slabbutton_' + b + '">';
                                                    fa += '<button type="button" class="btn btn-primary" style="margin-top: 27px;margin-left: 50px;" id="AddRowFrSlab_' + b + '"><i class="fa fa-plus" aria-hidden="true"></i></button> <button type="button" class="btn btn-danger" style="margin-top: 27px;margin-left: 50px;" id="MinusRowFrSlab_' + b + '"><i class="fa fa-minus" aria-hidden="true"></i></button>';
                                                    fa += '</div>';
                                                    fa += '</div>';

                                                    var f = b - 1;
                                                    $(fa).insertAfter('#SlabRates_' + f + '');

                                                    slabCount = slabCount + 1;
                                                    KeyPress_Validation();
                                                    $('#AddRowFrSlab_' + b + '').click(function (e) {
                                                        e.preventDefault();
                                                        var g = b + 1;
                                                        $('#slabbutton_' + b + '').hide();
                                                        InfiniteLopForSlab(g);
                                                    });

                                                    $('#MinusRowFrSlab_' + b + '').click(function (e) {
                                                        e.preventDefault();
                                                        var g = b - 1;
                                                        $('#SlabRates_' + b + '').remove();
                                                        $('#slabbutton_' + g + '').show();
                                                        slabCount = slabCount - 1;
                                                    });
                                                }

                                                $('#SlabEdit').click(function (e) {
                                                    e.preventDefault();

                                                    if(plan_act_obj.action===true && plan_act_obj.type=="Slab wise rate view with data start")
                                                      {  
                                                    
                                                    var params = {'valid': true};

                                                    var actid = $(this).attr('id');

                                                    params.SlabRates = {};

//                                                    params.ServiceConfigId = plsvId;
                                                          
                                                    params.service_id= plsvId;
                                                    params.plan_id=plan_id;



                                                    var allDiv = $(".SlabRates");
                                                    var DivCount = {};
                                                    for (var i = 0; i < slabCount; i++) {
                                                        DivCount[i] = i;
                                                    }

                                                      $.each(DivCount, function (k4, v4) {

                                                        var SlabMinimumAmount = $('#' + actid + '_MinimumAmount_' + k4 + '').val();

                                                        var ChargeType = $('#' + actid + '_ChargeType_' + k4 + ' option:selected').val();
                                                        var ChargeMethod = $('#' + actid + '_ChargeMethod_' + k4 + ' option:selected').val();
                                                        var SlabMaximunAmount = $('#' + actid + '_MaximunAmount_' + k4 + '').val();
                                                        var SlabRate = $('#' + actid + '_Rate_' + k4 + '').val();



                                                        params.SlabRates[k4] = {'SlabMinimumAmount': SlabMinimumAmount, 'SlabMaximunAmount': SlabMaximunAmount,'ChargeType': ChargeType,'ChargeMethod': ChargeMethod, 'SlabRate': SlabRate, 'SlabMinAmountDiv': actid + '_MinimumAmount_' + k4 + '',
                                                            'SlabMaxAmountDiv': actid + '_MaximunAmount_' + k4 + '', 'SlabRateDiv': actid + '_Rate_' + k4 + '', 'ChargeTypeDivId': actid + '_ChargeType_' + k4 + '', 'ChargeMethodDivId': actid + '_ChargeMethod_' + k4 + ''};



                                                    });


                                                    $.each(params.SlabRates, function (key, value) {
                                                        console.log(value)


                                                    if (!validate({'id': '' + value.SlabMinAmountDiv + '', 'type': 'RATE', 'data': value.SlabMinimumAmount, 'error': true, msg: $('#' + value.SlabMinAmountDiv + '').attr('placeholder')})) {
                                                            params.valid = false;

                                                    }
                                                    // if (value.SlabMaximunAmount != '' && typeof (value.SlabMaximunAmount) != 'undefined') {
                                                            if (!validate({'id': '' + value.SlabMaxAmountDiv + '', 'type': 'RATE', 'data': value.SlabMaximunAmount, 'error': true, msg: $('#' + value.SlabMaxAmountDiv + '').attr('placeholder')})) {
                                                                params.valid = false;

                                                            }
                                                   // }

                                                    if (!validate({'id': '' + value.ChargeTypeDivId + '', 'type': 'CHARGETYPE', 'data': value.ChargeType, 'error': true, msg: $('#' + value.ChargeTypeDivId + '').attr('placeholder')})) {
                                                            params.valid = false;

                                                    }
                                                    if (!validate({'id': '' + value.ChargeMethodDivId + '', 'type': 'CHARGEMETHOD', 'data': value.ChargeMethod, 'error': true, msg: $('#' + value.ChargeMethodDivId + '').attr('placeholder')})) {
                                                            params.valid = false;

                                                    }
                                                    if (!validate({'id': '' + value.SlabRateDiv + '', 'type': 'RATE', 'data': value.SlabRate, 'error': true, msg: $('#' + value.SlabRateDiv + '').attr('placeholder')})) {
                                                            params.valid = false;

                                                    }
                                                    });
                                                    
                                                    if (params.valid == true) {
                                                        
                                                        plan_act_obj.action=true;
                                                        plan_act_obj.type='Slab wise rate edit request sent';
                                                        
                                                        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                                        var la = $(this).ladda();
                                                        la.ladda('start');

                                                        $.ajax({
                                                            method: 'POST',
                                                            url: 'Manage/PlanSlabRatesInsert',
                                                            data: params,
                                                            dataType: 'JSON'
                                                        }).done(function (response) {
                                                            if (response)
                                                            {
                                                               if (response.error == 2)
                                                                {
                                                                    window.location.reload(true);
                                                                } else if (response.error == 0) {

                                                                    toastr.success(response.msg);
                                                                    
                                                                    $('#PlanSlabRatesView').hide().html('');
                                                                    $('.plan_listpage').show();
                                                                    table.ajax.reload();
                                                                    plan_act_obj.action=false;
                                                                    plan_act_obj.type='';
                                                                    
                                                                }else{
                                                                    plan_act_obj.action=true;
                                                                    plan_act_obj.type='Slab wise rate view with data start';
                                                                    la.ladda('stop');
                                                                    toastr.error(response.error_desc);
                                                                }
                                                                
                                                            }
                                                            
                                                        }).fail(function (err) {
                                                            toastr.error('Something went wrong, try again later');
                                                            plan_act_obj.action=true;
                                                            plan_act_obj.type='Slab wise rate view with data start';
                                                            la.ladda('stop');
                                                            throw err;
                                                        });
                                                    }

                                                    }else{
                                                            toastr.error('Please Wait!!');   
                                                    }
                                                    
                                                });

                                               
                                            }
                                            else{
                                                toastr.error(response.error_desc);
                                                plan_act_obj.action=true;
                                                plan_act_obj.type="Slab wise rate view";
                                            }
                                        }
                                    });
                                    
                                    }else{
                                         toastr.error('Please Wait!!');
                                     }
                                    
                                });
                            }
                            else{
                                toastr.error(response.error_desc);
                                plan_act_obj.action=false;
                                plan_act_obj.type='';
                                slabbtnla.ladda('stop');
                            }
                        }
                    }).fail(function (err) {
                        toastr.error('Something went wrong, try again later');
                        plan_act_obj.action=false;
                        plan_act_obj.type='';
                        slabbtnla.ladda('stop');
                        throw err;
                    });
                }
                
                     }else{
                         toastr.error('Please Wait!!');
                     }
                
            });
            /****************************************Edit Slab rates*****************************************/

            /****************************************view plan details*****************************************************/

              table.on('click', '#Plan_Edit', function (e) {
                e.preventDefault();
               if(plan_act_obj.action===false && plan_act_obj.type=="")
                { 
                var plan_id = $(this).data('plan');
                var row = $(this).closest('tr');
                var showtd = table.row(row).data();

                if (plan_id == showtd.plan_id) {

                    plan_act_obj.action=true;
                    plan_act_obj.type='Edit Plan detail window';
                    
                    var desc = showtd.plan_description ? showtd.plan_description : '';
                    var active;
                    if (showtd.is_active == 1) {
                        active = 'checked';
                    } else {
                        active = '';
                    }

                    var defaultcheckbox;
                    if (showtd.is_default == 1) {
                        defaultcheckbox = 'checked';
                    } else {
                        defaultcheckbox = '';
                    }


                    var html = '';
                     html += ' <div class="width-100 mb-3 bord-bottom gray-header" id="first_title">' + showtd.plan_name + ' (' + showtd.plan_code + ') Edit</div>';
                    //html += '<h4 style="border-bottom: #e6e6e6 1px solid;">' + showtd.plan_name + ' (' + showtd.plan_code + ') Edit</h4>';
                  //  html += '<div class="form-wrp">';

                    html += '<div class="row">';

                    html += '<div class="col-md-6">';
                    html += '<div class="form-group">';
                    html += '  <label>Plan Name<span class="mandotry">*</span></label>';
                    html += '<input type="text" class="form-control input-lg PlanName" placeholder="Plan Name" id="PlanEdit_PlanName" value="' + showtd.plan_name + '">';
                    html += '<span data-for="PlanEdit_PlanName"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="col-md-6">';
                    html += '<div class="form-group">';
                    html += '  <label>Plan Code<span class="mandotry">*</span></label>';
                    html += '<input type="text" class="form-control input-lg PlanCode" placeholder="Plan Code" id="PlanEdit_PlanCode" value="' + showtd.plan_code + '">';
                    html += '<span data-for="PlanEdit_PlanCode"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += ' </div>';

                    html += '<div class="row">';
                    html += '<div class="col-md-6">';
                    html += '<div class="form-group">';
                    html += '<label>Plan For<span class="mandotry">*</span></label>';
                    html += '<select name="select" class="form-control input-xs PlanFor" id="PlanEdit_PlanFor">';
                    html += '<option value="">Select Plan For</option>';
                    $.each(Rols, function (k4, v4) {
                        var sel = (v4.role_id == showtd.plan_for_role) ? 'selected' : '';
                        html += '<option value="' + v4.role_id + '" ' + sel + '>' + v4.role_name + '</option>';
                    });
                    html += '</select>';
                    html += '<span data-for="PlanEdit_PlanFor"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="col-md-6">';
                    html += '<div class="form-group">';
                    html += '<label>Description</label>';
                    html += '<input type="text" class="form-control input-lg Description" placeholder="Description" id="PlanEdit_Description" value="' + desc + '">';
                    html += '<span data-for="PlanEdit_Description"></span>';
                    html += '</div>';
                    html += '</div>';

                    html += ' </div>';
                     html += '<div class="row">';

                    html += '<div class="col-md-6">';
                    html += '<div class="form-group">';
                    html += '<div class="checkbox">';
                    html += '<label style="margin-left: 1.25rem;">';
                    html += '<input type="checkbox" class="switch PlanEdit_IsActive" id="PlanEdit_IsActive" ' + active + '>';
                    html += 'Is Active';
                    html += '</label>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';



                    html += ' </div>';

                    html += ' </div>';

                    html += '<div class="row">';

                    html += ' <div class="col-md-12">';
                    html += ' <div class="form-group has-feedback text-center">';
                    html += '  <button class="btn btn-default btn-lg" style="margin-top: 26px;width: 150px;" id="PlanBackPlanEdit">Back</button>';
                    html += '  <button type="submit" class="btn btn-primary btn-lg PlanEdit" style="margin-top: 26px;width: 150px;" id="PlanEdit">Proceed</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '</div>';
                    // html += '</div>';

//                    $('#PlanViewTable').hide();
//                    $('#AddDivButton').hide();
                    $('.plan_listpage').hide();
                    $('#PlanDataEdit').html(html).show();
                    KeyPress_Validation();
                    $("#PlanEdit_IsActive").bootstrapToggle({

                        on: 'Yes',
                        off: 'No'
                    });

                    $("#PlanEdit_IsDefault").bootstrapToggle({
                        on: 'Yes',
                        off: 'No'
                    });


                    $('.PlanEdit').click(function (e) {
                        e.preventDefault();
                        if(plan_act_obj.action===true && plan_act_obj.type=='Edit Plan detail window')
                            {
                        var params = {'valid': true};

                        var actid = $(this).attr('id');

                        params.PlanName = $('#' + actid + '_PlanName').val();
                        params.PlanCode = $('#' + actid + '_PlanCode').val();
                        params.Description = $('#' + actid + '_Description').val();
                        params.PlanFor = $('#' + actid + '_PlanFor option:selected').val();
                        params.IsActive = $('#' + actid + '_IsActive').is(":checked");
                        params.PlanId = showtd.plan_id;



                        if (!validate({'id': '' + actid + '_PlanName', 'type': 'NAME', 'data': params.PlanName, 'error': true, msg: $('#' + actid + '_PlanName').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (!validate({'id': '' + actid + '_PlanCode', 'type': 'CODE', 'data': params.PlanCode, 'error': true, msg: $('#' + actid + '_PlanCode').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (params.Description != '') {
                            if (!validate({'id': '' + actid + '_Description', 'type': 'DESC', 'data': params.Description, 'error': true, msg: $('#' + actid + '_Description').attr('placeholder')})) {
                                params.valid = false;
                            }
                        }

                        if (!validate({'id': '' + actid + '_PlanFor', 'type': 'PLANFOR', 'data': params.PlanFor, 'error': true, msg: $('#' + actid + '_PlanFor').attr('placeholder')})) {
                            params.valid = false;
                        }

                        if (params.valid == true) {
                            $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                            var la = $(this).ladda();
                            la.ladda('start');
                            
                            plan_act_obj.action=true;
                            plan_act_obj.type='Initiated Edit Plan action';

                            $.ajax({
                                method: 'POST',
                                url: 'Manage/PlanDataEdit',
                                data: params,
                                dataType: 'JSON'
                            }).done(function (response) {
                                if (response)
                                {
                                    if (response.error == 2)
                                    {
                                        window.location.reload(true);
                                    } else if (response.error == 0) {

                                         toastr.success(response.msg);
//                                        $('#PlanViewTable').show();
//                                        $('#AddDivButton').show();
                                        $('#PlanDataEdit').hide().html('');
                                        $('.plan_listpage').show();
                                        table.ajax.reload();
                                        plan_act_obj.action=false;
                                        plan_act_obj.type="";
                                        
                                    }else{
                                        toastr.error(response.error_desc);
                                        la.ladda('stop');
                                        plan_act_obj.action=true;
                                        plan_act_obj.type='Edit Plan detail window';
                                    }
                                    
                                }
                                
                            }).fail(function (err) {
                                toastr.error('Something went wrong, try again later');
                                la.ladda('stop');
                                plan_act_obj.action=true;
                                plan_act_obj.type='Edit Plan detail window';
                                throw err;
                            });
                        }
                            }else{
                                toastr.error('Please Wait!!');
                            }
                    });


                    $('#PlanBackPlanEdit').click(function (e) {
                        e.preventDefault();
                        if(plan_act_obj.action===true && plan_act_obj.type=='Edit Plan detail window')
                            {
//                                $('#first_title').show();
//                                $('#PlanViewTable').show();
//                                $('#AddDivButton').show();
                                $('.plan_listpage').show();
                                $('#PlanDataEdit').hide().html('');
                                plan_act_obj.action=false;
                                plan_act_obj.type="";
                                
                            }else{
                                toastr.error('Please Wait!!');
                            }
                        
                    });
                }
                }else{
                    toastr.error('Please Wait!!');
                }
            });

              $('#PlanAdd').click(function (e) 
                {
                    e.preventDefault();
                  
               if(plan_act_obj.action===false && plan_act_obj.type=="")
                {    
                  
                    plan_act_obj.action=true;
                    plan_act_obj.type='Add new Plan';
                    
            
                    
            var html = '';
            html += '<div class="col-lg-12">';
            html += '<h4 style="border-bottom: #e6e6e6 1px solid;">Plan Add</h4>';
            html += '<div class="form-wrp">';

            html += '<div class="row">';
            html += '<div class="col-md-6">';
            html += '<div class="form-group">';
            html += '  <label>Plan Name<span class="mandotry">*</span></label>';
            html += '<input type="text" class="form-control input-lg PlanName" placeholder="Plan Name" id="PlanAdd_PlanName">';
            html += '<span data-for="PlanAdd_PlanName"></span>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-6">';
            html += '<div class="form-group">';
            html += '  <label>Plan Code<span class="mandotry">*</span></label>';
            html += '<input type="text" class="form-control input-lg PlanCode" placeholder="Plan Code" id="PlanAdd_PlanCode">';
            html += '<span data-for="PlanAdd_PlanCode"></span>';
            html += '</div>';
            html += '</div>';
            html += ' </div>';
            html += '<div class="row">';
            html += '<div class="col-md-6">';
            html += '<div class="form-group">';
            html += '<label>Plan For<span class="mandotry">*</span></label>';
            html += '<select name="select" class="form-control input-xs PlanFor" id="PlanAdd_PlanFor">';
            html += '<option value="">Select Plan For</option>';
            $.each(Rols, function (k4, v4) {
                html += '<option value="' + v4.role_id + '">' + v4.role_name + '</option>';
            });
            html += '</select>';
            html += '<span data-for="PlanAdd_PlanFor"></span>';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-md-6">';
            html += '<div class="form-group">';
            html += '<label>Description</label>';
            html += '<input type="text" class="form-control input-lg Description" placeholder="Description" id="PlanAdd_Description">';
            html += '<span data-for="PlanAdd_Description"></span>';
            html += '</div>';
            html += '</div>';

            html += ' </div>';

            html += '<div class="row">';
            html += ' <div class="col-md-3" style="margin-top: 20px;">';
            html += ' <div class="form-group has-feedback">';
            html += '<div class="checkbox checkbox-switch">';
            html += '<label style="margin-left: 1.25rem;">';
            html += '<input type="checkbox" class="switch PlanAdd_IsActive" id="PlanAdd_IsActive">';
            html += 'Is Active';
            html += '</label>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            //   html += ' <div class="col-md-3" style="margin-top: 20px;">';
            // html += ' <div class="form-group has-feedback">';
            // html += '<div class="checkbox checkbox-switch">';
            // html += '<label>';
            // html += '<input type="checkbox" class="switch PlanAdd_IsDefault" id="PlanAdd_IsDefault">';
            // html += 'Is Default';
            // html += '</label>';
            // html += '</div>';
            // html += '</div>';
            //html += '</div>';
            html += ' </div>';
            html += ' </div>';




            html += '<div class="row">';

            html += ' <div class="col-md-12">';
            html += ' <div class="form-group has-feedback text-center">';
            html += '  <button class="btn btn-default btn-lg" style="margin-top: 26px;width: 150px;" id="PlanBack">Back</button>';
            html += '  <button type="submit" class="btn btn-primary btn-lg PlanAdd" style="margin-top: 26px;width: 150px;" id="PlanAdd">Proceed</button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            html += '</div>';
            html += '</div>';
            //$('#PlanViewTable').hide();
            //$('#AddDivButton').hide();
            $('.plan_listpage').hide();
            $('#PlanDataAdd').html(html).show();
            KeyPress_Validation();
            $("#PlanAdd_IsActive").bootstrapToggle({onText: 'Yes',
                off: 'No'
            });





            $('.PlanAdd').click(function (e) {
                e.preventDefault();
                
                if(plan_act_obj.action===true && plan_act_obj.type=='Add new Plan')
                {
                
                var params = {'valid': true};

                var actid = $(this).attr('id');

                params.PlanName = $('#' + actid + '_PlanName').val();
                params.PlanCode = $('#' + actid + '_PlanCode').val();
                params.Description = $('#' + actid + '_Description').val();
                params.PlanFor = $('#' + actid + '_PlanFor option:selected').val();
                params.IsActive = $('#' + actid + '_IsActive').is(":checked");
                // params.IsDefault = $('#' + actid + '_IsDefault').is(":checked");

                if (!validate({'id': '' + actid + '_PlanName', 'type': 'NAME', 'data': params.PlanName, 'error': true, msg: $('#' + actid + '_PlanName').attr('placeholder')})) {
                    params.valid = false;
                }

                if (!validate({'id': '' + actid + '_PlanCode', 'type': 'CODE', 'data': params.PlanCode, 'error': true, msg: $('#' + actid + '_PlanCode').attr('placeholder')})) {
                    params.valid = false;
                }

                if (params.Description != '') {
                    if (!validate({'id': '' + actid + '_Description', 'type': 'DESC', 'data': params.Description, 'error': true, msg: $('#' + actid + '_Description').attr('placeholder')})) {
                        params.valid = false;
                    }
                }

                if (!validate({'id': '' + actid + '_PlanFor', 'type': 'PLANFOR', 'data': params.PlanFor, 'error': true, msg: $('#' + actid + '_PlanFor').attr('placeholder')})) {
                    params.valid = false;
                }
                if (params.valid == true) {
                    
                    plan_act_obj.action=true;
                    plan_act_obj.type='Initiate Add Plan Request';
                    
                    $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                    var la = $(this).ladda();
                    la.ladda('start');

                    $.ajax({
                        method: 'POST',
                        url: 'Manage/PlanDataAdd',
                        data: params,
                        dataType: 'JSON'
                    }).done(function (response) {
                        if (response)
                        {
                            if (response.error == 2)
                            {
                                window.location.reload(true);
                                
                            } else if (response.error == 0) {

                                 toastr.success(response.msg);


                                var PlanId = response.data;
                                //----------------Plan Service ---------------//

                                var str = '';
                                str += '<h4 style="border-bottom: #e6e6e6 1px solid;">Plan Services Add</h4>';
                                str += '<div class="form-wrp">';
                                str += '<div class="table-responsive table-scrollable">';
                                str += '<table class="table">';
                                str += '<thead>';
                                str += '<tr>';
                                str += '<th>Service Name</th>';
                                str += '<th>Charge Type</th>';
                                str += '<th>Charge Method</th>';
                                str += '<th>Rate</th>';
                                str += '<th>Capping Amount</th>';
                                str += '<th>Slab Applicable</th>';
                                str += '</tr>';
                                str += '</thead>';

                                str += '<tbody>';

                                $.each(serviceArr, function (k, v) {

                                    str += '<tr>';

                                    str += '<td><span class="ServiceName" id="AddPlanService_ServiceName' + v.service_id + '" data-service="' + v.service_id + '">' + v.service_name + ' (' + v.type + ')</span></td>';

                                    str += '<td>';
                                    str += '<div class="form-group">';
                                    str += '<select name="select" class="form-control input-xs ChargeType" id="AddPlanService_ChargeType' + v.service_id + '">';
                                    str += '<option value="">Select Charge Type</option>';
                                    $.each(Regex.ChargeType, function (k, v) {
                                        str += '<option value="' + k + '">' + v + '</option>';
                                    });
                                    str += '</select>';
                                    str += '<span data-for="AddPlanService_ChargeType' + v.service_id + '"></span>';
                                    str += '</div>';
                                    str += '</td>';

                                    str += '<td>';
                                    str += '<div class="form-group">';
                                    str += '<select name="select" class="form-control input-xs ChargeMethod" id="AddPlanService_ChargeMethod' + v.service_id + '">';
                                    str += '<option value="">Select Charge Method</option>';
                                    $.each(Regex.ChargeMethod, function (k1, v1) {
                                        str += '<option value="' + k1 + '">' + v1 + '</option>';
                                    });
                                    str += ' </select>';
                                    str += '<span data-for="AddPlanService_ChargeMethod' + v.service_id + '"></span>';
                                    str += '</div>';
                                    str += ' </td>';

                                    str += '<td>';
                                    str += '<div class="form-group">';
                                    str += '<input type="text" class="form-control Rate" placeholder="Rate" id="AddPlanService_Rate' + v.service_id + '">';
                                    str += '<span data-for="AddPlanService_Rate' + v.service_id + '"></span>';
                                    str += '</div>';
                                    str += '</td>';

                                    str += '<td>';
                                    str += '<div class="form-group">';
                                    str += '<input type="text" class="form-control CappingAmount" placeholder="Capping Amount" id="AddPlanService_CappingAmount' + v.service_id + '">';
                                    str += '<span data-for="AddPlanService_CappingAmount' + v.service_id + '"></span>';
                                    str += '</div>';
                                    str += ' </td>';

                                    str += '<td>';
                                    str += ' <div class="col-md-3">';
                                    str += ' <div class="form-group has-feedback">';
                                    str += '<div class="checkbox checkbox-switch">';
                                    str += '<label>';
                                    str += '<input type="checkbox" class="switch SlabApplicable" id="AddPlanService_SlabApplicable' + v.service_id + '">';
                                    str += 'Slab Applicable';
                                    str += '</label>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</div>';
                                    str += '</td>';

                                    str += '</tr>';

                                });
                                str += '</tbody>';

                                str += '<tfoot>';
                                str += '<tr>';
                                str += '<td colspan="7" style="text-align: center;">';
                                str += '<button class="btn btn-primary" id="AddPlanService">Submit</button>';
                                str += '</td>';
                                str += '</tr>';
                                str += '</tfoot>';
                                str += '</table>';
                                str += '</div>';
                                str += '</div>';

                                $('#PlanDataAdd').hide().html('');
                                $('#PlanServiceData').html(str).show();

                                $(".SlabApplicable").bootstrapToggle({
                                    on: 'Yes',
                                    off: 'No'
                                });
                                KeyPress_Validation();

                                $('#AddPlanService').click(function (e) {
                                    e.preventDefault();

                                    if(plan_act_obj.action===true && plan_act_obj.type=='Initiate Add Plan Request')
                                    {
                                    
                                       plan_act_obj.action=true;
                                       plan_act_obj.type='Add Services for Plan Initiated';
                                        
                                    var params = {'valid': true};
                                    var actid = $(this).attr('id');

                                    params.service = {};

                                    $.each(serviceArr, function (k, v) {
                                        var ServiceId = $('#' + actid + '_ServiceName' + v.service_id + '').data('service');
                                        var ChargeType = $('#' + actid + '_ChargeType' + v.service_id + ' option:selected').val();
                                        var ChargeMethod = $('#' + actid + '_ChargeMethod' + v.service_id + ' option:selected').val();
                                        var Rate = $('#' + actid + '_Rate' + v.service_id + '').val();
                                        var CappingAmount = $('#' + actid + '_CappingAmount' + v.service_id + '').val();
                                        var SlabApplicable = $('#' + actid + '_SlabApplicable' + v.service_id + '').is(":checked");
                                        if (ChargeType != '' || ChargeMethod != '' || Rate != '') {
                                            params.service[v.service_id] = {'ServiceName': ServiceId, 'ChargeType': ChargeType, 'ChargeMethod': ChargeMethod, 'Rate': Rate, 'CappingAmount': CappingAmount, 'SlabApplicable': SlabApplicable,
                                                'ChargeTypeDivId': '' + actid + '_ChargeType' + v.service_id + '', 'ChargeMethodDivId': '' + actid + '_ChargeMethod' + v.service_id + '', 'RateDivId': '' + actid + '_Rate' + v.service_id + '',
                                                'CappingAmountDivId': '' + actid + '_CappingAmount' + v.service_id + ''};
                                        }
                                    });

                                    $.each(params.service, function (key, value) {

                                        if (!validate({'id': '' + value.ChargeTypeDivId + '', 'type': 'CHARGETYPE', 'data': value.ChargeType, 'error': true, msg: $('#' + value.ChargeTypeDivId + '').attr('placeholder')})) {
                                            params.valid = false;
                                        }

                                        if (!validate({'id': '' + value.ChargeMethodDivId + '', 'type': 'CHARGEMETHOD', 'data': value.ChargeMethod, 'error': true, msg: $('#' + value.ChargeMethodDivId + '').attr('placeholder')})) {
                                            params.valid = false;
                                        }
                                        if (!validate({'id': '' + value.RateDivId + '', 'type': 'RATE', 'data': value.Rate, 'error': true, msg: $('#' + value.RateDivId + '').attr('placeholder')})) {
                                            params.valid = false;
                                        }

                                        if (value.CappingAmount != '') {
                                            if (!validate({'id': '' + value.CappingAmountDivId + '', 'type': 'RATE', 'data': value.CappingAmount, 'error': true, msg: $('#' + value.CappingAmountDivId + '').attr('placeholder')})) {
                                                params.valid = false;
                                            }
                                        }
                                    });

                                    params.PlanId = PlanId;

                                  
                                    if (params.valid == true) {
                                        $(this).addClass('ladda-button').attr('data-style', 'zoom-in');
                                        var addservla = $(this).ladda();
                                        addservla.ladda('start');

                                        $.ajax({
                                            method: 'POST',
                                            url: 'Manage/PlanServiceDataInsert',
                                            data: params,
                                            dataType: 'JSON'
                                        }).done(function (response) {
                                            if (response)
                                            {
                                                if (response.error == 2)
                                                {
                                                    window.location.reload(true);

                                                } else if (response.error == 0) {

                                                    toastr.success(response.msg);

                                                    $('#PlanServiceData').hide().html('');
                                                    $('.plan_listpage').show();
                                                    table.ajax.reload();
                                                    plan_act_obj.action=false;
                                                    plan_act_obj.type="";
                                                    
                                                }else{
                                                    toastr.error(response.error_desc);
                                                    plan_act_obj.action=true;
                                                    plan_act_obj.type='Initiate Add Plan Request';
                                                    addservla.ladda('stop');
                                                }
                                                
                                            }
                                            
                                        }).fail(function (err) {
                                            addservla.ladda('stop');
                                            plan_act_obj.action=true;
                                            plan_act_obj.type='Initiate Add Plan Request';
                                            throw err;
                                        });
                                    }
                                    
                                    }else{
                                        toastr.error('Please Wait!!');
                                    }
                                    
                                });

                                //-------------- Plan Service End -------------//

                            }
                            else{
                                toastr.error(response.error_desc);
                                plan_act_obj.action=true;
                                plan_act_obj.type='Add new Plan';
                                la.ladda('stop');
                            }
                            
                        }
                       
                    }).fail(function (err) {
                        toastr.error('Something went wrong, try again later');
                        plan_act_obj.action=true;
                        plan_act_obj.type='Add new Plan';
                        la.ladda('stop');
                        throw err;
                    });
                }
                
                }else{
                    toastr.error('Please Wait!!');
                }    
            
            });

            $('#PlanBack').click(function (e) {
                e.preventDefault();
                if(plan_act_obj.action===true && plan_act_obj.type=='Add new Plan')
                {
//                $('#first_title').show();
//                $('#PlanViewTable').show();
//                $('#AddDivButton').show();
                $('.plan_listpage').show();
                $('#PlanDataAdd').hide().html('');
                plan_act_obj.action=false;
                plan_act_obj.type="";
                    
                }else{
                    toastr.error('Please Wait!!');
                }
            });
                  
              }else{
                         toastr.error('Please Wait!!');
                }
                  
        });

            
         }
         
        //---------------------------------- Keypress Validation--------------------------//

        var KeyPress_Validation = function () {
            $(".PlanName").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Text);
                if (regacc.test(str)) {
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
            $(".PlanCode").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                this.value = this.value.toUpperCase();
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Text);
                if (regacc.test(str)) {
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
            $(".Description").on('keypress blur keyup keydown', function (e) {
                var error_msg;
                var k = e.keyCode || e.which;
                var id = $(this)[0].id;
                var str = $(this)[0].value;
                var length = str.length;
                var msg = $(this).attr('placeholder');
                var regacc = new RegExp(Regex.Text);
                if (str == '') {
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
                        helpBlck({id: id, 'action': 'remove'});
                    }
                }
            });
            $('.PlanFor').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();
                if (val in Rols)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Plan For', 'type': 'error'});
                }
            });
            $('.ChargeType').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                if (val == '') {
                    helpBlck({id: id, 'action': 'remove'});
                }

                if (val in Regex.ChargeType)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Charge Type', 'type': 'error'});
                }
            });
            $('.ChargeMethod').change(function () {
                var id = $(this).attr('id');
                var val = $(this).val();

                if (val == '') {
                    helpBlck({id: id, 'action': 'remove'});
                }

                if (val in Regex.ChargeMethod)
                {
                    helpBlck({id: id, 'action': 'remove'});
                } else {
                    helpBlck({'id': id, 'msg': 'Invalid Charge Method', 'type': 'error'});
                }
            });
            $(".Rate,.CappingAmount").on('keypress blur keyup keydown', function (e) {
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
            } else if (p.type == 'CODE') {
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
            } else if (p.type == 'PLANFOR') {
                if (p.data != "" && p.data in Rols)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Plan For', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Plan For', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'CHARGETYPE') {
                if (p.data != "" && p.data in Regex.ChargeType)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Charge Type', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Charge Type', 'type': 'error'});
                        }
                    }
                }
            } else if (p.type == 'CHARGEMETHOD') {
                if (p.data != "" && p.data in Regex.ChargeMethod)
                {
                    helpBlck({id: p.id, 'action': 'remove'});
                    return true;
                } else
                {
                    if (p.error == true)
                    {
                        if (p.data == '') {
                            helpBlck({'id': p.id, 'msg': 'Select Charge Method', 'type': 'error'});
                        } else {
                            helpBlck({'id': p.id, 'msg': 'Invalid Charge Method', 'type': 'error'});
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
            init:function(){
                RoleWiseRole();
                serviceArr();
                Plantable();
            }
        }
        
    }();
    
    $(document).ready(function(){
        Plan_config.init();
    });
    
</script>

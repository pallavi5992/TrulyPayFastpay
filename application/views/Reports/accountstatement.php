<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
    $user_id =$this->session->userdata('userid');
	$role_id =$this->session->userdata('role_id');
    $parent=$this->uri->segment(1);
	$child=$this->uri->segment(2);
	$parent_check = str_replace('_', '', $parent);
    $child_check = str_replace('_', '', $child);
	$user_data=get_user_details();
	if(!$user_id){

		redirect('Login');
	 }

?>
   
<style>
.row .mt-3{
	margin-bottom: 1rem;
}
table#AccountStatement {
    width: !important100%;
}
</style>

			<div class="col-lg-9">
								<div class="tab-content">
							
								<?php if ($user_data['role_id'] == 4 ||$user_data['role_id'] == 2 || $user_data['role_id'] == 3) { ?>
                                <!-- <table  class="table  table-bordered" id="transactiontable">-->
								<table class="table font14 font-medium light-txt datatables table-responsive" id="AccountStatement">
								<thead  class="thead-blue">
								  <tr>   
													<th class="all compact">Sr No</th>
													<th class="all compact">Date/Time</th>
													<th class="all compact">Narration</th>
													<th class="all compact">Amount (&#8377;)</th>
                                                    <?php if($user_data['role_id'] == 4){ ?>
													<th class="all compact">Charged Amount (&#8377;)</th>
													<th class="all compact">Commission (&#8377;)</th>
                                                    <?php } ?>
													<th class="all compact">TDS (&#8377;)</th>
													<th class="all compact">Balance (&#8377;)</th>
													
												</tr>
								</thead>
                                </table>
                             
                            <?php } ?>

				
						   </div>
						</div>
						
						
						
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--end of section-->

</div>   
<?php if ($role_id ==4 ) { ?>
<script>
var Mergereport = function () {

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

    var Mreport = function () {

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

        var oTable = $('#AccountStatement').DataTable({ 
            "processing": true,    
            "serverSide": false,
            order: [],
            "ajax": {
//                url: "Reports/account_statement",
                url: "Reports/new_account_statement",
                type: "POST",
                data: function (data) {
                    if (selectedFrommy) {
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
            select: {
                style: 'multi'
            },
	       buttons: [
                {extend: 'csv', action: newExportAction, className: 'btn bg-slate btn-md'}
            ],  
            responsive: {
               details: {
                   type: 'column',
                   target: 'tr'
               }
           },
           "lengthMenu": [
                [10, 20, 50, 100],
                [10, 20, 50, 100] // change per page values here
            ],
            dom: '<"header-my"><"datatable-header search-data"Bfl>r<"table-scrollable"t><"datatable-footer"ip><"sumref">',
        });

        var html = '';
        html = '<div class="row">';
        html += ' <div class="col-md-3 col-sm-4">';
        html += ' <div class="form-group">';
        html += '   <input id="frommy" class="form-control" type="text" placeholder="From Date" readonly>';
        html += ' </div>';
        html += ' </div>';
        html += ' <div class="col-md-3 col-sm-4">';
        html += '  <div class="form-group">';
        html += '   <input id="tomy" class="form-control" type="text" placeholder="To Date" readonly>';
        html += ' </div>';
        html += ' </div>';
        html += ' <div class="col-md-2 col-sm-2">';
        html += '<div class="form-group">';
        html += '  <button id="srchsubmy" class="btn btn-success btn-md" type="button">';
        html += ' <i class="fa fa-search fa-fw"></i>Search</button>';
        html += ' </div>';
        html += '</div>';
        html += '<div class="col-md-2 col-sm-2">';
        html += ' <div class="form-group">';
        html += '    <button id="my_reset" class="btn btn-success btn-md" type="button">';
        html += ' Reset</button>';
        html += '</div>';
        html += '</div>';
        html += ' </div>';

        $('.header-my').append(html);


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

        $('#srchsubmy').on('click', function () {
            oTable.ajax.reload();
        });


        $('#my_reset').on('click', function () {
            checkedfieldmy = {};
            selectedTomy = currenttime();
            selectedFrommy = fromformat;
            $('#frommy').datepicker("setDate", '0d');
            $("#tomy").datepicker("setEndDate", '0d');
            $("#tomy").datepicker("setStartDate", '0d');
            $('#tomy').datepicker("setDate", '0d');
            oTable.ajax.reload();
    
        });
    }
    return{
        init: function () {
            Mreport();

        }
    }

}();

$(document).ready(function () {
    Mergereport.init();
});

</script>
<?php }else if($role_id ==2 || $role_id == 3){?>
<script>
var Mergereport = function () {

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

    var Mreport = function () {

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

        var oTable = $('#AccountStatement').DataTable({ 
            "processing": true,    
            "serverSide": false,
            order: [],
            "ajax": {
                url: "Reports/account_statement_fr_parent",
                type: "POST",
                data: function (data) {
                    if (selectedFrommy) {
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
            select: {
                style: 'multi'
            },
            buttons: [
                {extend: 'csv', action: newExportAction, className: 'btn bg-slate btn-md'}
            ],
            responsive: {
               details: {
                   type: 'column',
                   target: 'tr'
               }
           },
              /*  columns: [
			
				  
						 {title: '<span>Date/Time</span>', data: 'date', class: 'all'},
						  {title: '<span>Narration</span>', data: 'stype', class: 'all'},
						    {title: '<span>Amount (&#8377;)</span>', data: 'transamt', class: 'all'},
							{title: '<span>Charged Amount (&#8377;)</span>', data: 'amt', class: 'all'},
							{title: '<span>Commission (&#8377;)</span>', data: 'comssn', class: 'all'},
							{title: '<span>TDS (&#8377;)</span>', data: 'chargeamt', class: 'all'},
							{title: '<span>Balance (&#8377;)</span>', data: 'clbal', class: 'all'}, 
                       
         
            ], 
         columnDefs: [{targets: 7,
                    render: function (t, e, a, n) {
                        var s = {
                            CREDIT: {
                                title: "Cr",
                                class: "success-bg"
                            },
                            DEBIT: {
                                title: "Dr",
                                class: "failed-bg"
                            },
                            SUCCESS: {
                                title: "Dr",
                                class: "failed-bg"
                            },
                            PENDING: {
                                title: "Dr",
                                class: "failed-bg"
                            },
                            REVIEW: {
                                title: "Dr",
                                class: "failed-bg"
                            },
                            FAILED: {
                                title: "Dr",
                                class: "failed-bg"
                            },
                            REFUND: {
                                title: "Dr",
                                class: "failed-bg"
                            },
                        };
                        return void 0 === s[t] ? t : '<span class="' + s[t].class + '">' + s[t].title + "</span>"
                    }
                }],  */
            "lengthMenu": [
                [10, 20, 50, 100],
                [10, 20, 50, 100] // change per page values here
            ],
            dom: '<"header-my"><"datatable-header search-data"Bfl>r<"table-scrollable"t><"datatable-footer"ip><"sumref">',
        });

        var html = '';
        html = '<div class="row">';
        html += ' <div class="col-md-3 col-sm-4">';
        html += ' <div class="form-group">';
        html += '   <input id="frommy" class="form-control" type="text" placeholder="From Date" readonly>';
        html += ' </div>';
        html += ' </div>';
        html += ' <div class="col-md-3 col-sm-4">';
        html += '  <div class="form-group">';
        html += '   <input id="tomy" class="form-control" type="text" placeholder="To Date" readonly>';
        html += ' </div>';
        html += ' </div>';
        html += ' <div class="col-md-2 col-sm-2">';
        html += '<div class="form-group">';
        html += '  <button id="srchsubmy" class="btn btn-success btn-md" type="button">';
        html += ' <i class="fa fa-search fa-fw"></i>Search</button>';
        html += ' </div>';
        html += '</div>';
        html += '<div class="col-md-2 col-sm-2">';
        html += ' <div class="form-group">';
        html += '    <button id="my_reset" class="btn btn-success btn-md" type="button">';
        html += ' Reset</button>';
        html += '</div>';
        html += '</div>';
        html += ' </div>';

        $('.header-my').append(html);


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

        $('#srchsubmy').on('click', function () {
            oTable.ajax.reload();
        });


        $('#my_reset').on('click', function () {
            checkedfieldmy = {};
            selectedTomy = currenttime();
            selectedFrommy = fromformat;
            $('#frommy').datepicker("setDate", '0d');
            $("#tomy").datepicker("setEndDate", '0d');
            $("#tomy").datepicker("setStartDate", '0d');
            $('#tomy').datepicker("setDate", '0d');
            oTable.ajax.reload();

        });
    }
    return{
        init: function () {
            Mreport();

        }
    }

}();

$(document).ready(function () {
    Mergereport.init();
});
</script>
	
<?php } ?>
<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
?>
<style>
.row .mt-3{
	margin-bottom: 1rem;
}
.dataTable thead {
    background-color: #2c98c5;
    color: #fff;
    font-weight: 600;
}

</style>
<div class="col-lg-9">
    <div class="tab-content">
			
    <div class="row">
        <div class="col-md-3 col-sm-3">
         <div class="form-group">
           <input id="frommy" class="form-control" type="text" placeholder="From Date" readonly>
         </div>
         </div>
         <div class="col-md-3 col-sm-3">
          <div class="form-group">
           <input id="tomy" class="form-control" type="text" placeholder="To Date" readonly>
         </div>
         </div>
         <div class="col-md-2 col-sm-2">
          <div class="form-group">
           <input id="party" class="form-control" type="text" placeholder="UserId">
         </div>
         </div>
         <div class="col-md-2 col-sm-2">
        <div class="form-group">
          <button id="srchsubmy" class="btn btn-success btn-md" type="button">
         <i class="fa fa-search fa-fw"></i>Search
          </button>
         </div>
        </div>
        <div class="col-md-2 col-sm-2">
         <div class="form-group">
        <button id="my_reset" class="btn btn-success btn-md" type="button">
         Reset
        </button>
        </div>
        </div>
    </div>    
        <div class="" id="dynotablediv">
        
        </div>
    </div>
</div>
						
						
						
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--end of section-->

</div>   
<script>

var search_account_summary=function(){
   
    var today = new Date();
    var fromformat = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var selectedTomy = fromformat;
    var selectedFrommy = fromformat;

    
    var start_summary= {
        action:false,
        type:"",
        init:function(){
            
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
                if (startDate.getMonth() == currentdate.getMonth()) 
                {

                    $("#tomy").datepicker("setEndDate", '0d');
                    $("#tomy").datepicker("setDate", '0d');
                    selectedTomy = fromformat;
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

            var searchla=$('#srchsubmy').ladda();
            
            $('#srchsubmy').click(function(e){
               e.preventDefault();
                if(start_summary.action===false && start_summary.type=="")
                {
                    
                   $('#dynotablediv').hide().html("");    
                   var params={valid:true};
                       params.party=$('#party').val();
                       params.from=selectedFrommy;
                       params.to=selectedTomy;

                       if(params.party!="")
                        {
                            $(this).addClass('ladda-button').attr('data-style','zoom-in');
                            searchla.ladda('start');
                            
                            start_summary.action=true;
                            start_summary.type="Fetching Account Summary";
                            
                            $.ajax({
                                "url":"Reports/get_accountstatement_byuserid",
                                "data":params,
                                "dataType":"JSON",
                                "method":"POST"
                            }).done(function(statement_respo){
                                if(statement_respo)
                                    {
                                        if(statement_respo.error==0)
                                            {
                                                searchla.ladda('stop');
                                                start_summary.action=false;
                                                start_summary.type="";
                                                start_summary.dynotable(statement_respo);
                                                
                                            }
                                        else if(statement_respo.error==2)
                                            {
                                                window.location.reload(true);
                                            }
                                        else{
                                            toastr.error(statement_respo.error_desc);
                                            searchla.ladda('stop');
                                            start_summary.action=false;
                                            start_summary.type="";
                                        }
                                    }
                            }).fail(function(err){
                                toastr.error('Something went wrong, try again later');
                                searchla.ladda('stop');
                                start_summary.action=false;
                                start_summary.type="";
                                
                            });

                        }else{
                            toastr.error('Invalid UserId');
                            $('#party').focus();
                        }

                }else{
                    toastr.error('Please Wait!!');
                }
                
            });
            
            $('#my_reset').click(function(e){
                e.preventDefault();
                if(start_summary.action===false && start_summary.type=="")
                {
                    
                    selectedTomy = fromformat;
                    selectedFrommy = fromformat;
                    $('#frommy').datepicker("setDate", '0d');
                    $("#tomy").datepicker("setEndDate", '0d');
                    $("#tomy").datepicker("setStartDate", '0d');
                    $('#tomy').datepicker("setDate", '0d');
                    $('#dynotablediv').html("");
                    $('#party').val("");
                    
                }else{
                    toastr.error('Please Wait!!');
                }
            })
            
        },
        dynotable:function(dataresponse){
            if(dataresponse)
            {
                if(dataresponse.error==0)
                    {
                        
                        var str='<div class="text-center">';
                        str+='<h4>';
                        str+='Account Statement of '+dataresponse.userdata.first_name+" "+dataresponse.userdata.last_name+' ('+dataresponse.userdata.user_id+') - RMN: '+dataresponse.userdata.mobile;
                        str+='</h4>';
                        str+='</div>';
                        str+='<div class="text-center mt-3"><h5 class="text-danger">'+dataresponse.daterange+' | Current Balance : &#8377; '+dataresponse.userdata.rupee_balance+'</h5></div>';
                        str+='<table class="table font14 font-medium light-txt datatables table-responsive" id="dyno_dttable" >';
                        str+='</table>';
                        
                        $('#dynotablediv').html(str).show();
                        
                        var tableconfig={
                            "processing": true,
                            "order":[],
                            "data":dataresponse.data,
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
                            buttons: [
                                {
                                    extend: 'csv', 
                                    className: 'btn btn-info btn-md',
                                    title:"Account Statement of "+dataresponse.userdata.first_name+" "+dataresponse.userdata.last_name+' for '+dataresponse.daterange
                                }
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
                            dom: '<"header-my"><"datatable-header search-data"Bfl>r<"table-scrollable"t><"datatable-footer"ip>',
                        };
                        
                        if(dataresponse.userdata.role_id==2 || dataresponse.userdata.role_id==3)
                            {
                                tableconfig.columns=[
                                    {
                                        title: 'Sr No',
                                        class:"compact all",
                                        data:'srno'
                                    },
                                    {
                                        title: 'Date/Time',
                                        class:"compact all",
                                        data:'datetime'
                                    },
                                    {
                                        title: 'Narration',
                                        class:"compact all",
                                        data:'narration'
                                    },
                                    {
                                        title: 'Amount (&#8377;)',
                                        class:"compact all",
                                        data:'amount'
                                    },
                                    {
                                        title: 'TDS (&#8377;)',
                                        class:"compact all",
                                        data:'tds_amount'
                                    },
                                    {
                                        title: 'Balance (&#8377;)',
                                        class:"compact all",
                                        data:'balance'
                                    },
                                ];
                            }
                        else{
                            tableconfig.columns=[
                                    {
                                        title: 'Sr No',
                                        class:"compact all",
                                        data:'srno'
                                    },
                                    {
                                        title: 'Date/Time',
                                        class:"compact all",
                                        data:'datetime'
                                    },
                                    {
                                        title: 'Narration',
                                        class:"compact all",
                                        data:'narration'
                                    },
                                    {
                                        title: 'Amount (&#8377;)',
                                        class:"compact all",
                                        data:'amount'
                                    },
                                    {
                                        title: 'Charged Amount (&#8377;)',
                                        class:"compact all",
                                        data:'chargedamount'
                                    },
                                    {
                                        title: 'Commission (&#8377;)',
                                        class:"compact all",
                                        data:'comm_amount'
                                    },
                                    {
                                        title: 'TDS (&#8377;)',
                                        class:"compact all",
                                        data:'tds_amount'
                                    },
                                    {
                                        title: 'Balance (&#8377;)',
                                        class:"compact all",
                                        data:'balance'
                                    },
                                ];
                        }
                        
                        
                       var accounttable= $('#dyno_dttable').DataTable(tableconfig);
                       accounttable.columns.adjust();
                        
                    }
            }
        }
    };
    
    return {
        init:function(){
            start_summary.init();
        }
    };
    
}();    
    
$(document).ready(function(e){
  
    search_account_summary.init();
    
});   
    
</script>
   
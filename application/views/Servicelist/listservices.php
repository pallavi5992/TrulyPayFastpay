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
<div class="col-lg-10">
    				<div class="tab-content">

    					<div class="width-100 tab-pane fade show active" id="users">

    						<div class="tab-content mt-20" id="nav-tabContent">
    						    <div class="tab-pane fade show active table-responsive" id="btn-notification" ></div>
        				        <div class="table-responsive">
        							<table class="table datatables" id="servicelist">
        						  	</table>
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

var Commercials = function(){
    
    var Table = function(){
        
        var oTable = $('#servicelist').DataTable({
            "processing": true,    
            "serverSide": false,
            "order": [],
            "ajax": {
                url: "Servicelist/get_my_commercials",
                type: "POST",
                "dataSrc": function(json) {
                  if(json.error_data==2)
                      {
                          window.location.reload(true);
                      }else if(json.error_data==1)
                      {
                          toastr.error(json.error_desc,'Oops!');
                      }
                  return json.data;
              }
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
            columns:[
                {
                    title: 'Service Name',
                    class:"compact all",
                    data:'servicename'
                },
                {
                    title: 'Service Type',
                    class:"compact all",
                    data:'servicetype',
                    width:'30%'
                },
                {
                    title: 'Commission / Surcharge',
                    class:"compact all",
                    data:'commercials',
                    render:function(data, type, full, meta)
                    {
                        if(full.slabapplicable==1)
                            {
                                if(full.slabdata.length>0)
                                    {
                                        var str='';
                                        str+='<div class="table-responsive">';
                                        str+='<table class="table table-hover">';
                                        str+='<tbody>';
                                        str+='<tr>';
                                        str+='<th>Minimum Amount</th>';
                                        str+='<th>Maximum Amount</th>';
                                        str+='<th>Commission / Surcharge</th>';
                                        str+='</tr>';
                                        $.each(full.slabdata,function(slk,slv){
                                            str+='<tr>';
                                            str+='<td>&#8377; '+slv.min_amount+'</td>';
                                            str+='<td>&#8377; '+slv.max_amount+'</td>';
                                            str+='<td>'+slv.plan_desc+'</td>';
                                            str+='</tr>';
                                        });
                                        str+='</tbody>';
                                        str+='</table>';
                                        str+='</div>';
                                        
                                        return str;
                                    }
                                else{
                                    return "Slab Configuration Issue";
                                }
                            }
                        else{
                            return full.commercials;
                        }
                    }
                }
            ],
            buttons: [
                                {
                                    extend: 'csv', 
                                    className: 'btn btn-info btn-md',
                                    title:"Commercials"
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
                        
        });
        
    }
    
    return {
        init:function(){
           Table(); 
        }
    }
    
}();    
    
$(document).ready(function(){
Commercials.init();
});
</script>
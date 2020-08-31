<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
$user_email = $this->session->userdata('userid');
$user_data = get_user_details();
$user_prnt_dtl=get_user_parent_dtl();
$user_due_payment=get_user_due_pymnts();


$my_reports=permsn_access(42,"FETCH"); 

$child_reports=permsn_access(43,"FETCH"); 

$all_reports=permsn_access(52,"FETCH"); 


if (!$user_email) {
	redirect('Login');
}
$todays_load_amt = 0;
if(isset($user_detail['amount']) && $user_detail['amount'] > 0){
	$todays_load_amt = $user_detail['amount'];
}
// print_r($user_detail);die;
?>
<style>
	span.help-block {
    	color: #D84315!important;
	    font-size: 80%;
	}

.owl-carousel.owl-drag .owl-item{
	min-height:130px;
}


</style>

<!--start of section-->
	<section class="dashboard_overview width-100 make_relative mt-30">
		<div class="container">
			<div class="row">
			<?php if($my_reports || $child_reports || $all_reports){ ?>
                
				<div class="colset col-md col-sm-12">
					<div class="dashboard-inner-col today_trans" id="today_service_usage" style="cursor:pointer">
						<div class="dashboard-inner-col-left">
							<div class="lbl" >Today's Transaction</div>
							<!-- <div class="val"> -->
								<?php

									//$a=get_user_tdy_txn_amnt('today');
									//echo isset($a['amnt']) ? $a['amnt'] : "0";
                                ?>
                            <!-- </div> -->
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/transaction.svg" width="40">
						</div>
					</div>
				</div>
				
				<div class="col-md colset col-sm-12">
					<div class="dashboard-inner-col month_tilldate_trans" id="same_month_service_usage" style="cursor:pointer">
						<div class="dashboard-inner-col-left">
							<div class="lbl" >Month Till Date Transaction</div>
							<!-- <div class="val"> -->
                                <?php
									//$a=get_user_tdy_txn_amnt('cur_month');
									//echo isset($a['amnt']) ? $a['amnt'] : "0";
                                ?>
							<!-- </div> -->
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/transaction.svg" width="40">
						</div>
					</div>
				</div>

                <div class="col-md colset col-sm-12">
					<div class="dashboard-inner-col last_month_trans" id="previous_month_service_usage" style="cursor:pointer">
						<div class="dashboard-inner-col-left">
							<div class="lbl" >Last Month's Transaction</div>
							<!-- <div class="val"> -->
								<?php
									//$a=get_user_tdy_txn_amnt('pre_month');
									//echo isset($a['amnt']) ? $a['amnt'] : "0";
                                ?>
							<!-- </div> -->
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/transaction.svg" width="40">
						</div>
					</div>
				</div>
                
				<?php } ?>

				<div class="col-md colset col-sm-12">
					<div class="dashboard-inner-col todays_load">
						<div class="dashboard-inner-col-left">
							<div class="lbl">Todays Load</div>
							<div class="val">
								<?php echo $todays_load_amt; ?>
							</div>
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/wallet-icon.svg" width="25">
						</div>
					</div>
				</div>
				<?php if($user_data['role_id']==2 || $user_data['role_id']==3 || $user_data['role_id']==4){?>
				<div class="col-md  colset col-sm-12">
					<div class="dashboard-inner-col outs_blance">
						<div class="dashboard-inner-col-left">
							<div class="lbl">Outstanding Balance</div>
							<div class="val">
										<?php
              echo ($user_data['outstanding_balance']>0) ? $user_data['outstanding_balance'] : 0;
                                        ?></div>
						</div>

						<div class="dashboard-inner-col-iconbox">
							<img src="assets/images/wallet-icon.svg" width="25">
						</div>
					</div>
				</div>
				<?php }?>

			</div>
		</div>

	</section>
	<!--end of section-->

	<!--start of banner notification banner-->
	<section class="width-100 notification-banner mt-30 hide">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
						<div id="notifications" class="carousel slide " data-ride="carousel">
						  <div class="carousel-inner">

						  </div>
						  <a class="carousel-control-prev" href="#notifications" role="button" data-slide="prev">
						    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
						    <span class="sr-only">Previous</span>
						  </a>
						  <a class="carousel-control-next" href="#notifications" role="button" data-slide="next">
						    <span class="carousel-control-next-icon" aria-hidden="true"></span>
						    <span class="sr-only">Next</span>
						  </a>
						</div>
				</div>

				<div class="col-md-4">
					<!-- <div class="banner-right-section">
						<a href="Manage/PaymentRequest">
						<div class="fund-transfer-link">
							Load request
						</div>
					    </a>

					</div> -->
				</div>

			</div>
		</div>
	</section>

	<!--end of notification banner-->

	<section class="width-100 mt-30 owl-carousel-slider">
		<div class="container">
			<div class="row">
				<div class="col-12">
					 <div class="owl-carousel owl-theme hide">

					 </div>
				</div>
			</div>
		</div>
	</section>



</div>
<!--end of wrapper-->



<script type="text/javascript">
    
    var dashboard_action=function(){
        
        var start_obj={action:false,type:""};
        
        <?php if($my_reports || $child_reports || $all_reports){ ?>
        
        var service_usage_sumary = function(){
            
            $('#today_service_usage,#same_month_service_usage,#previous_month_service_usage').click(function(e){
               
                e.preventDefault();
                var linkid=$(this).attr('id');
                if(start_obj.action===false && start_obj.type=="")
                    {
                        start_obj.action=true;
                        start_obj.type="Getting Reposrt";
                        
                        var reqtype="";
                        var reqdesc="";
                        switch(linkid){
                            case "today_service_usage":
                                reqtype='Today';
                                reqdesc='Today\'s Transaction Report';
                                break;
                            case "same_month_service_usage":
                                reqtype='Samemonth';
                                reqdesc='Month Till Date Transaction Report';
                                break;
                            case "previous_month_service_usage":
                                reqtype='Previousmonth';
                                reqdesc='Last Month\'s Transaction Report';
                                break;
                            default : reqtype="";reqdesc="";
                        }
                        
                        $.ajax({
                           "url":"Dashboard/get_service_txnreport",
                           "data":{type:reqtype},
                           "dataType":"JSON",
                           "method":"POST"
                        }).done(function(getrespo){
                            if(getrespo)
                            {
                                if(getrespo.error==0)
                                    {
                                        console.log(getrespo);
                                        start_obj.action=false;
                                        start_obj.type="";
                                        
                var str = '<div class="modal fade" id="txn_summary_reportmodal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                str += '<div class="modal-dialog modal-lg" role="document" id="">';
                str += '<div class="modal-content">';
                str += '<div class="modal-header">';
                str += '<h5 class="modal-title" id="head_ttl">'+reqdesc+'</h5>';
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
                                
                                 <?php if($my_reports){ ?>
                                    
                                 str+='<table class="table table-stripped">';
                                 str+='<thead>';
                                 str+='<th>Service Category</th>';
                                 str+='<th>Number of txns</th>';
                                 str+='<th>Sum total of transactions</th>';
                                 str+='</thead>';
                                 str+='<tbody>';
                                    
                                 if(getrespo.msg.length>0)
                                     {
                                        
                                         var txnobj={};
                                         $.each(getrespo.msg,function(tk,tv){
                                             txnobj[tv.category_type]=tv;
                                             if(!getrespo.category_list.includes(tv.category_type))
                                                 {
                                                     str+='<tr>';
                                                     str+='<td>'+tv.category_type+'</td>';
                                                     str+='<td>'+tv.total_count_txn+'</td>';
                                                     str+='<td>'+tv.total_sum_txn+'</td>';
                                                     str+='</tr>';
                                                 }
                                         });
                                         
                                        $.each(getrespo.category_list,function(clk,clv){
                                           
                                            if(clv in txnobj)
                                                {
                                                    str+='<tr>';
                                                    str+='<td>'+clv+'</td>';
                                                    str+='<td>'+txnobj[clv].total_count_txn+'</td>';
                                                    str+='<td>'+txnobj[clv].total_sum_txn+'</td>';
                                                    str+='</tr>';
                                                }else{
                                                    str+='<tr>';
                                                    str+='<td>'+clv+'</td>';
                                                    str+='<td>0</td>';
                                                    str+='<td>0</td>';
                                                    str+='</tr>';
                                                }
                                            
                                        });
                                         
                                         
                                         
                                     }else{
                                         
                                       if(getrespo.category_list.length>0)
                                        {
                                            $.each(getrespo.category_list,function(clk,clv){
                                                str+='<tr>';
                                                str+='<td>'+clv+'</td>';
                                                str+='<td>0</td>';
                                                str+='<td>0</td>';
                                                str+='</tr>';
                                            });
                                        }else{
                                            str+='<tr>';
                                            str+='<td class="text-center" colspan="3">No Data</td>';
                                            str+='</tr>';
                                        }
                                        
                                     }
                                        
                                 str+='</tbody>';
                                 str+='</table>';        
                                        
                                 <?php }else{ ?>        
                                        
                                 str+='<table class="table table-stripped">';
                                 str+='<thead>';
                                 str+='<th>Serial Number</th>';
                                 str+='<th>Retailer</th>';
                                 str+='<th>Retailer Name</th>';
                                 $.each(getrespo.category_list,function(clk,clv){
                                    str+='<th>'+clv+'</th>';
                                 });
                                 str+='</thead>';
                                 str+='<tbody>'; 
                                 
                                 if(getrespo.msg.length>0)
                                     {
                                         
                                         $.each(getrespo.msg, function(mk,mv){
                                            
                                             str+='<tr>';
                                             str+='<td>'+(mk+1)+'</td>';
                                             str+='<td>'+mv.user_id+'</td>';
                                             str+='<td>'+mv.name+'</td>';
                                             
                                             $.each(getrespo.category_list,function(clk,clv){
                                                
                                                 if(clv in mv)
                                                     {
                                                         str+='<td>'+mv[clv]+'</td>';
                                                     }else{
                                                         str+='<td>0</td>';
                                                     }
                                                 
                                             });
                                             
                                             str+='</tr>';
                                             
                                         });
                                         
                                     }else{
                                         var colspan=3+(getrespo.category_list.length);
                                        str+='<tr>';
                                        str+='<td class="text-center" colspan="'+colspan+'">No Data</td>';
                                        str+='</tr>';
                                         
                                     }
                                        
                                        
                                 <?php } ?>   
                                        
                                str+='</div>';
                    str += '</div>'; 
                str += '</div>'; 
                                        
                str += '</div>';                       
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                
                $('body').append(str);                        
                                        
                $('#txn_summary_reportmodal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                })
                $('#txn_summary_reportmodal').on('hidden.bs.modal', function () {
                    $('#txn_summary_reportmodal').remove();
                });                        
                    
                                        
                                    }
                                else if(getrespo.error==2)
                                    {
                                        window.location.reload(true);
                                    }
                                else{
                                    toastr.error(getrespo.error_desc);
                                    start_obj.action=false;
                                    start_obj.type="";
                                }
                            }
                        }).fail(function(err){
                            toastr.error('Something went wrong, try again later');
                                    start_obj.action=false;
                                    start_obj.type="";
                        });
                        
                    }else{
                        toastr.error('Please Wait!!');
                    }
                
            });
        }
        
        <?php } ?>
        
         var reports = function(){
             
         } 
        
         return {
             init:function(){
                  <?php if($my_reports || $child_reports || $all_reports){ ?>
                  service_usage_sumary();
                  <?php } ?>
             }
         }
        
    }();
    
	$(document).ready(function(){
        
        dashboard_action.init();
        
		$.ajax({
            method: 'POST',
            url: 'Dashboard/portal_notifs_list',
            data: '',
            dataType: 'JSON'
        }).done(function(response) {
            if (response) {
                if (response.error == 1) {
                	toastr.error(json.error_desc, 'Oops!');
                } else if (response.error == 2) {
                    window.location.reload(!0)
                } else if (response.error == 0) {
                	var event_list = '', noti_list = '';
                	var noti_list_count = 0;
				    $.each(response.data, function(k, obj){
				    	var activeclass = (k == 0) ? 'active' : '';
			  			if(obj['event_type'] == 'GREETING'){
					    	event_list += '<div class="carousel-item '+activeclass+'">';
						    event_list += '<img class="d-block w-100" src="'+obj['content']+'" alt="First slide">';
					    	event_list += '</div>';
				    	}  else {
				    		noti_list_count++;
					    	noti_list += '<div class="item">';
				            noti_list += '<h4>'+obj['heading']+'</h4>';
				            noti_list += '<p>'+obj['content']+'</p>';
				            noti_list += '</div>';
			        	}
				    });
				    if(event_list){
					    $("div.carousel-inner").html(event_list);
					    $('#notifications').carousel();
					    $(".notification-banner").show();
		          	}else{
		          		$('.notification-banner').hide();
		          	}

		          	if(noti_list){
		          		$('.owl-carousel').html(noti_list);
		          		$('.owl-carousel-slider').show();
		          		var owl = $('.owl-carousel');

				          var owl = $('.owl-carousel');
							owl.owlCarousel({
									margin: 10,
									nav: false,
									loop: (noti_list_count>3) ? true : false,
									responsive: {
										0: {
											items: 1
										},
										600: {
											items: 2
										},
										1000: {
											items: 3
										}
									}
								}
							);

		          	}else {
		          		$('.owl-carousel-slider').hide();
		          	}
                }


            }
        }).fail(function(err) {
            throw err
        })

		$(".nav-set .nav-item").click(function(){
		$(".nav-set .nav-item").removeClass('active');
		$(this).addClass('active');
		});

		$(".active-status-btn").click(function(){
			$(this).toggleClass('deactive');
			$(this).toggleClass('active');
			$(this).innerHtml = 'dfdf';
		});

       


	})
</script>
</body>
</html>

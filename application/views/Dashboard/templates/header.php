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
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<base href="<?=base_url()?>">
<title>TrulyPay</title>

<link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
<link rel="manifest" href="assets/favicon/site.webmanifest">
<link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
<link rel="shortcut icon" href="assets/favicon/favicon.ico">
<meta name="msapplication-TileColor" content="#ffc40d">
<meta name="msapplication-config" content="assets/favicon/browserconfig.xml">
<meta name="theme-color" content="#ffffff">    
    
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/common.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css?v=1">
<link rel="stylesheet" type="text/css" href="assets/css/responsive.css?v=1">
<link href="assets/toastr/toastr.min.css" rel="stylesheet" type="text/css">
<link href="assets/ladda/css/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/fontawesome.min.css" rel="stylesheet" type="text/css" />

<link href="assets/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/select2-bootstrap4.min.css" rel="stylesheet" type="text/css" />

<link href="assets/css/bootstrap4-toggle.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css">
<link href="assets/css/sweetalert.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="assets/css/owl.carousel.min.css">
<script src="assets/js/jquery-3.4.1.min.js"></script>
<script src="assets/js/jquery.validate.min.js"></script>
<script src="assets/js/additional-methods.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/select2.min.js"></script>
<script src="assets/js/number-to-words.js"></script>
<script src="assets/ladda/js/spin.min.js" type="text/javascript" ></script>
<script src="assets/ladda/js/ladda.min.js" type="text/javascript" ></script>
<script src="assets/ladda/js/ladda.jquery.min.js" type="text/javascript" ></script>
<script type="text/javascript" src="assets/toastr/toastr.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="assets/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap4-toggle.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/js/daterangepicker.js"></script>
<script type="text/javascript" src="assets/js/datepicker.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/js/sweetalert.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-select.min.js"></script>
<script src="assets/js/owl.carousel.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>

<style>
.failed-bg {
    background-color: #af1b11;
}
.pending-bg {
    background-color: #bf7302;
}
.transaction-status-icon-failed
{
    background-color: #af1b11;
}
.transaction-status-icon-pending
{
    background-color: #bf7302;
}
span.btn.btn-pending {
    color: #fff;
    background-color: #dca935;
    border-color: #dca935;
}
</style>
</head>
<body>
<!--start of wrapper-->
<div class="wrapper">
	<!--start of header-->
	<header class="header-set white-bg width-100 make_relative pb-15 pt-15">
		<!--start of top section-->
		<div class="top-section width-100 make_relative pb-15">
			<div class="container">
        <div class="row">
          <div class="col-12">
				<div class="logo float-left">
					<img src="assets/images/logo.png">
				</div>

				<div class="float-right top-section-right">
					<div class="wallet-balance-box ml-20 float-left">
						<div class="blue-icon-box float-left mr-2"><img src="assets/images/wallet.svg" class="img-icon-size"></div>
						 <div class="wallet-balance-right-txt float-right">
    						 	<div id="cbbalanch" style="cursor:pointer">
    						 	<span class="light-txt font-medium" >Wallet Balance : </span><span class="dark-txt fontbold" id="bal_usr"><?php
                                            $user_data['rupee_balance'] = isset($user_data['rupee_balance']) ? $user_data['rupee_balance'] : "";
                                            echo $user_data['rupee_balance'];
                                            ?></span>
                </div>
						 </div>
					 </div>

           <div class="support-section float-left mr-3" style="margin-left:20px;">
              <div>
              <span class="mr-1 icn-cnt-set"><img src="assets/images/support.png" width="20"></span><span class="fontbold" style="color:#000;">9716111911</span>
              </div>
              <div>
              <span class="icn-cnt-set" style="margin-right:0.80px;"><img src="assets/images/mail.png" width="20"/></span>
              <a  href="mailto:support@trulyindia.co.in" class="fontbold" style="color:#000;">support@trulyindia.co.in</a>
              </div>
              <div>
                <span class="mr-1 icn-cnt-set"><img src="assets/images/feedback.png" width="20"></span><span><a href="Dashboard/Suggestion" class="fontbold" style="color:#000;">Company Feedback Suggestions</a></span>
              </div>
            </div>

					   <div class="dropdown float-left">
						  <button class="transparent-btn make_relative" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="ml-2 mr-2"><img src="assets/images/account-img.jpeg" style="width: 42px;height: 43px;border-radius: 100px;"></span><span><?php
                                        $user_data['fname'] = isset($user_data['fname']) ? $user_data['fname'] : "";
                                        echo $user_data['fname'];
                                        ?></span>
						  </button>
						  <div class="dropdown-menu font14" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="MyAccount/MyProfile">Profile</a>
						    <a class="dropdown-item" href="Login/logout">Logout</a>
						  </div>
					  </div>


				</div>
      </div>
      </div>
		   </div>
		</div>
		<!--end of top section-->
		<!--start of navbar-->
		<div class="nav-set width-100 make_relative">
			<div class="container">
        <div class="row">
          <div class="col-12">
		      <nav class="navbar navbar-expand-md navbar-light bg-light">
			      <a class="navbar-brand" href="#"></a>
			      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			        <span class="navbar-toggler-icon"></span>
			      </button>
			      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
			        <ul class="navbar-nav mr-auto">
			        <?php
					$navbar = get_nav_tittle(0,'NAVBAR');
					//print_r($navbar);
					echo get_navbar_head($navbar,0);
					function get_navbar_head($navbar,$parent){
					$c=& get_instance();
					$t = '';
					$active_link = $c->uri->segment(1);
				    $sub_active_link = $c->uri->segment(2);

						foreach ($navbar as $key => $value) {
							$content = '';
                            $active = '';
							if($value['parent_id'] == $parent){
							$content =  get_navbar_head($navbar,$value['id']);
							$cmpare_name = str_replace(" ", "", $value['tab_name']);
							$cmpare_name = str_replace("&", "", $cmpare_name);
							if($value['parent_id'] == 0) {

								if(strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

									$active = 'active';

									} else {

										 $has_child=navbar_child($value['id']);
										if($has_child)	{
											foreach ($has_child as $k=>$v){
												$check_name=str_replace(" ", "", $v['tab_name']);
												$check_name=str_replace("&", "", $check_name);
												if (strcasecmp($active_link, $check_name) == 0 || strcasecmp($sub_active_link, $check_name) == 0) {

													$active = '';
//													$active = 'show';
													break;
												}else{
													$active = '';
												}
											}
										}else{
												$active = '';
										}

									}


							}else{
									$cmpare_parentname = navbar_parent($value['parent_id']);
									// print_r($cmpare_parentname);
										if($cmpare_parentname){

												if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

//														$active = 'show';
                                                        $active = 'active';

														} else {
																$active = '';
															}


										 }else{

											if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

														$active = 'active';

														} else {
																$active = '';
															}
										 }



							}

							 $has_child=navbar_child($value['id']);
							  $cmpare_parentname = navbar_parent($value['parent_id']);

							 if($has_child){


					    $t .= '<li class="nav-item dropdown ">
			            <a class="nav-link dropdown-toggle last-link" href="'. $value['link_url'] .'" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="nav-icon"><img src="'. $value['icon_class'] .'" style="width: 21px;"></span>' . $value['tab_name'] . '</a>
			            <div class="dropdown-menu '.$active.'" aria-labelledby="dropdown01">'.$content.'


			            </div>
			          </li>';

							 }else{



								 if($cmpare_parentname){





									 $t .='<a href="'. $value['link_url'] .'" class="dropdown-item '.$active.'">' . $value['tab_name'] . '</a>';

								 }else{

									 $t .='<li class="nav-item '.$active.'">
			            <a class="nav-link" href="'. $value['link_url'] .'"><span class="nav-icon"><img src="'. $value['icon_class'] .'"></span><span class="service-name">' . $value['tab_name'] . '</spna></a>
			          </li>';

									 }
							    }



							}

						}

						return $t;

					}

			   ?>


			        <!--   <li class="nav-item active">
			            <a class="nav-link" href="index.html" target="_blank"><span class="nav-icon"><img src="assets/images/dashboard.svg"></span><span class="service-name">DASHBOARD</span></a>
			          </li>
			          <li class="nav-item">
			            <a class="nav-link" href="recharge.html" target="_blank"><span class="nav-icon"><img src="assets/images/service.svg"></span><span class="service-name">RECHARGE</span></a>
			          </li>

			           <li class="nav-item">
			            <a class="nav-link" href="beneficiary-list.html" target="_blank"><span class="nav-icon"><img src="assets/images/money-transfer.svg" style="width: 26px;"></span><span class="service-name">MONEY TRANSFER</span></a>
			          </li>

			           <li class="nav-item">
			            <a class="nav-link" href="#" target="_blank"><span class="nav-icon"><img src="assets/images/flight.svg" style="width: 26px;"></span><span class="service-name">FLIGHT BOOKING</span></a>
			          </li>

			           <li class="nav-item">
			            <a class="nav-link" href="#" target="_blank"><span class="nav-icon"><img src="assets/images/bus.svg" style="width: 31px;"></span><span class="service-name">BUS BOOKING</span></a>
			           </li>

			          <li class="nav-item dropdown">
			            <a class="nav-link dropdown-toggle last-link" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="nav-icon"><img src="assets/images/more.svg" style="width: 21px;"></span>More Services</a>
			            <div class="dropdown-menu" aria-labelledby="dropdown01">
			              <a class="dropdown-item" href="#">Insurance</a>

			            </div>
			          </li> -->
			        </ul>
			      </div>
			    </nav>

        </div>
        </div>
			</div>
		</div>
		<!--end of navbar-->
	</header>
	<!--end of header-->
	<script>
                    $(document).ready(function () {
	  var i = 0;
                        var timer1;
                        var rest_timer;
                        var fetchaccntbal = function () {

                            $('#cbbalanch').click(function (e) {
                                e.preventDefault();

                                i++;
                                if (i <= 100) {
                                    var anc = $(this)[0];
                                    var span = $(this).closest('#bal_usr');
                                    $(this).html('<span class="h6"><i class=" icon-spinner3 spinner"></i> Please Wait ...</span>');
                                    $.post('Dashboard/getaccntbal', function (response) {
                                        if (response) {
                                            if (response.error == 0)
                                            {
                                                console.log(response.msg)
                                                $('#cbbalanch').html('<span class="light-txt font-medium">Wallet Balance : </span><span class="dark-txt fontbold" id="bal_usr"> '+response.msg +'</span>');



                                                window.clearTimeout(timer1);

                                            } else if (response.error == 2)
                                            {
                                                window.location.reload(true);
                                            } else {
                                                $('#cbbalanch').html('Retry');
                                            }                                        }
                                    }, 'json');


                                } else {

                                    toastr.error('Too Many Balance request, try after a minute or refresh the page');
                                    window.clearTimeout(rest_timer);
                                    rest_timer = setTimeout(function () {
                                        i = 0;
                                    }, 60 * 1000);
                                }

                            })

                        }
                        fetchaccntbal();

                    })
                </script>

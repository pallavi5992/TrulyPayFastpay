<?php 
$e = $this->session->userdata('userid'); 
$role_id = $this->session->userdata('role_id');
$parent = $this->uri->segment(1);
$child = $this->uri->segment(2);
$parent_check = str_replace('_', '', $parent);
$child_check = str_replace('_', '', $child);
$user_data = get_user_details();
//print_r($user_data); exit;
if (!$e) {

    redirect('Login');
}

?>
<!DOCTYPE html>  
<html lang="en">
<head>
	<base href="<?= base_url() ?>">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Indipay</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/styles.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/core.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/components.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/colors.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/icommon.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/style.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/toastr/toastr.min.css" rel="stylesheet" type="text/css">
	<link href="assets/plugins/ladda/css/ladda-themeless.min.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/fontawesome.min.css" rel="stylesheet" type="text/css">    
	<link href="assets/dashboard/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"> 
	<link href="assets/dashboard/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css">
	<link href="assets/dashboard/css/sweetalert.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
	<!-- Core JS files -->
	<script type="text/javascript" src="assets/dashboard/js/pace.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/additional-methods.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/blockui.min.js"></script>
	<!-- /core JS files -->  
	<!-- Theme JS files -->
	<script type="text/javascript" src="assets/dashboard/js/d3.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/d3_tooltip.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/switchery.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/uniform.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/bootstrap_multiselect.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/moment.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/daterangepicker.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/datepicker.js"></script>
	 <script type="text/javascript" src="assets/dashboard/js/sweetalert.min.js"></script> 
	<script type="text/javascript" src="assets/dashboard/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/app2.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/app.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/dashboard.js"></script>
	<script type="text/javascript" src="assets/plugins/ladda/js/spin.min.js"></script>
	<script type="text/javascript" src="assets/plugins/ladda/js/ladda.min.js"></script>
	<script type="text/javascript" src="assets/plugins/ladda/js/ladda.jquery.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/toastr/toastr.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/select2.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/datatables.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/switch.min.js"></script>
		<script type="text/javascript" src="assets/dashboard/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="assets/dashboard/js/jquery.dataTables.min.js"></script>
	<!-- /theme JS files -->

</head>
<style>
.datepicker > div {
   display: block; 
    }
   table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before{top:18px;}
</style>
<body>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse navbar-inverse-custom">
		<div class="navbar-header navbar-header-set">
			<a class="navbar-brand" href="index.html"><img src="assets/dashboard/images/logo_light.png" alt=""></a>

			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse navbar-collapse-custom


		" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
				<li class="mt-8 mr-20 sm-p-10"><span class="mr-10"><img src="assets/dashboard/images/user.png"></span>CUSTOMER CARE: 011-340-45000</li>
				<li class="mt-8 sm-m-10 mr-10"><div class="btn-black">Balance: <?php
                                        $user_data['rupee_balance'] = isset($user_data['rupee_balance']) ? $user_data['rupee_balance'] : "";
                                        echo $user_data['rupee_balance'];
                                        ?></div></li>
				<li class="mt-8 sm-m-10"><div class="btn-pink">Due: 50,000</div></li>
			</ul>

			

			<ul class="nav navbar-nav navbar-right">

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img id="prfl_pic" src="<?php
                                     $user_data['profile_pic'] = isset($user_data['profile_pic']) ? $user_data['profile_pic'] : "";
                                     echo $user_data['profile_pic'];
                                     ?>" alt="" id="upd_pr">
						<span><?php
                                        $user_data['fname'] = isset($user_data['fname']) ? $user_data['fname'] : "";
                                        echo $user_data['fname'];
                                        ?>  <?php
                                        $user_data['lname'] = isset($user_data['lname']) ? $user_data['lname'] : "";
                                        echo $user_data['lname'];
                                        ?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="MyAccount/MyProfile"><i class="icon-user-plus"></i> My profile</a></li>
						<li><a href="#"><i class="icon-coins"></i> My balance</a></li>
						<li><a href="#"><span class="badge bg-teal-400 pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
						<li class="divider"></li>
						<li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
						<li><a href="Login/logout"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->
	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<div class="sidebar sidebar-set sidebar-main">
				<div class="sidebar-content">
					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								 <?php
                                    $sidebar = get_sidebar_tittle();
                                    //print_r($sidebar);exit;
                                      echo get_navbar_head($sidebar, 0);

                                      function get_navbar_head($sidebar, $parent) {

                                        $c = & get_instance();
                                        $t = '';
                                        $active_link = $c->uri->segment(1);
                                        $sub_active_link = $c->uri->segment(2);
                                        foreach ($sidebar as $key => $value) {
                                            $content = '';
                                            $active = '';
                                            $addclass = '';
                                            $none = '';
                                            if ($value['parent_id'] == $parent) {
                                                $content = get_navbar_head($sidebar, $value['id']);
                                                $cmpare_name = str_replace(" ", "", $value['act_link_name']);
                                                $cmpare_name = str_replace("&", "", $cmpare_name);
                                                if ($value['parent_id'] == 0) {

                                                    if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                        if ($content !== '') {
                                                            $none = 'style="display: block;"';
                                                            $active = '';
                                                        } else {
                                                            $none = 'style="display: block;"';
                                                            $active = 'active';
                                                        }
                                                    } else {
                                                    	
                                                        $active = '';
                                                        $none = '';
                                                    }
                                                } else {

                                                    $cmpare_parentname = sidebar_parent($value['parent_id']);
                                                    if ($cmpare_parentname) {
                                                    	//print_r($cmpare_parentname);exit;
                                                        $cmpare_name_parent = str_replace(" ", "", $cmpare_parentname['act_link_name']);
                                                        $cmpare_name_parent = str_replace("&", "", $cmpare_name_parent);
                                                        if (strcasecmp($active_link, $cmpare_name_parent) == 0 && strcasecmp($sub_active_link, $cmpare_name) == 0) {
                                                            $none = 'style="display: block;"';

                                                            $active = 'active';
                                                        } else {
                                                            $active = '';
                                                            $none = '';
                                                        }
                                                    } else {
                                                        if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {
                                                            $none = 'style="display: block;"';
                                                            $active = 'active';
                                                        } else {
                                                            $active = '';
                                                            $none = '';
                                                        }
                                                    }
                                                }

                                                $addclass = ($content != '') ? 'has-ul' : '';

                                                if ($content != '') {
                                                    $content = '<ul class="hidden-ul" ' . $none . '>' . $content . '</ul>';
                                                }
                                                $t.='<li class="' . $active . '">';
                                                $t.='<a href="' . $value['link_url'] . '" class="' . $addclass . '">';
                                                $t.='<i class="' . $value['icon_class'] . '" ></i>';
                                                $t.='<span>' . $value['tab_name'] . '</span>';

                                                $t.='</a>';

                                                $t.=$content;
                                                $t.='</li>';
                                            }
                                        }

                                        return $t;
                                    }
                                    ?>
								<!-- <li class="active"><a href="#"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
								<li>
									<a href="#"><i class="icon-stack2"></i> <span>Recharge</span></a>
									<ul>
										<li><a href="#">Mobile</a></li>
										<li><a href="#">DTH</a></li>
										<li><a href="#">Transaction History</a></li>
									</ul>
								</li>

								
								<li>
									<a href="#"><i class="icon-stack2"></i> <span>Bill Payments</span></a>
									<ul>
										<li><a href="#">Mobile Postpaid</a></li>
										<li><a href="#">Landline</a></li>
										<li><a href="#">Broadband</a></li>
										<li><a href="#">Electricity</a></li>
										<li><a href="#">Gas</a></li>
										<li><a href="#">Water</a></li>
										<li><a href="#">Insurance</a></li>
										<li><a href="#">Transaction History</a></li>
									</ul>
								</li>

								<li>
									<a href="#"><i class="icon-stack2"></i> <span>Money Remmitance</span></a>
									<ul>
										<li><a href="#">Money Transfer</a></li>
										<li><a href="#">Transaction History</a></li>
									</ul>
								</li>

								<li><a href="#"><i class="icon-home4"></i> <span>Airline</span></a></li>
								<li>
									<a href="#"><i class="icon-stack2"></i> <span>AEPS</span></a>
									<ul>
										<li><a href="#">Transaction</a></li>
										<li><a href="#">Transaction History</a></li>
									</ul>
								</li>

								<li>
									<a href="#"><i class="icon-stack2"></i> <span>Reports</span></a>
									<ul>
										<li><a href="#">Account Statement</a></li>
										<li><a href="#">Operator Wise</a></li>
										<li><a href="#">Refund</a></li>
										<li><a href="#">Load Transfer</a></li>
										<li><a href="#">Complaint</a></li>
										<li><a href="#">Due Payments</a></li>
									</ul>
								</li>

								<li><a href="#"><i class="icon-home4"></i> <span>Service List</span></a></li>
								<li><a href="#"><i class="icon-home4"></i> <span>Logout</span></a></li>

								<li>
									<a href="#"><i class="icon-stack2"></i> <span>My Account</span></a>
									<ul>
										<li><a href="MyAccount/MyProfile">My Profile</a></li>
										<li><a href="#">Operator Wise</a></li>
									</ul>
								</li>

								<li><a href="#"><i class="icon-home4"></i> <span>Bank Details</span></a></li>
 -->
								
								
								<!-- /main -->
							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>
			<!-- /main sidebar -->
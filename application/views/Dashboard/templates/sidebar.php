<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$user_email = $this->session->userdata('accnt_id');
$role_id = $this->session->userdata('role_id');
$parent = $this->uri->segment(1);
$child = $this->uri->segment(2);
$parent_check = str_replace('_', '', $parent);
$child_check = str_replace('_', '', $child);
$user_data = get_user_details();
//print_r($user_data); exit;
if (!$user_email) {

    redirect('Login');
}
?> 
<!DOCTYPE html>
<html lang="en">
    <!-- begin::Head -->
    <head>
        <base href="<?= base_url() ?>">
        <meta charset="utf-8" />
        <title>Go Cash Go</title>
        <meta name="description" content="Latest updates and statistic charts">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!--begin::Page Vendors Styles -->
        <link href="assets/css/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
        <!--end::Page Vendors Styles -->
        <!--begin::Global Theme Styles -->
        <link href="assets/css/vendors.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <!--end::Global Theme Styles -->     
        <link href="assets/css/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <!--begin::Layout Skins -->
        <link href="assets/css/all.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/navy.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/navy.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="assets/css/style.css?v=1.3">
        <link rel="stylesheet" type="text/css" href="assets/css/custom.css?v=1.8">
        <link href="https://www.apibox.xyz/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" id="bootstrap-css">
        <!--<link href="assets/css/sweetalert/sweetalert_style.css?v=1.4" rel="stylesheet" id="bootstrap-css">-->
        <script src="assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>	
        <script src="assets/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/js/toastr/toastr.min.js"></script>
        <script src="https://www.apibox.xyz/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
        <!--<script src="assets/js/sweetalert/sweetalert_new.min.js?v=1"></script>-->
    
        <link href="assets/js/plugins/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/toastr/toastr.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" type="text/css" href="assets/css/line-awesome.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.css" </link>

        <!--end::Layout Skins -->     
    </head>
    <!-- end::Head -->
    <style type="text/css">

        .sweet-alert .form-group .sa-input-error .show {
            display: block !important;
        }

    /*input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:hover,
    select:-webkit-autofill:focus {
     
      -webkit-text-fill-color: black !important;
      -webkit-box-shadow: 0 0 0px 1000px #000 inset;
      transition: background-color 5000s ease-in-out 0s;
      color: black;
    }*/

    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active  {
        -webkit-box-shadow: 0 0 0 30px white inset !important;
        -webkit-text-fill-color: black !important;
    }

    </style>
    <!-- begin::Body -->

    <body class="k-header--fixed k-header-mobile--fixed k-aside--enabled k-aside--fixed">

        <!-- begin:: Page -->
        <!-- begin:: Header Mobile -->
        <div id="k_header_mobile" class="k-header-mobile  k-header-mobile--fixed ">
            <div class="k-header-mobile__logo">
                <a href="#">
                    <img alt="Logo" src="assets/images/logo.png" style="border-radius: 100%;width: 55px;" />
                </a>
            </div>
            <div class="k-header-mobile__toolbar">
                <!-- <button class="k-header-mobile__toolbar-toggler k-header-mobile__toolbar-toggler--left" id="k_aside_mobile_toggler"><span></span></button>
    
                <button class="k-header-mobile__toolbar-toggler" id="k_header_mobile_toggler"><span></span></button>
                <button class="k-header-mobile__toolbar-topbar-toggler" id="k_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button> -->
                <button class="button-lft"><i class="fas fa-bars"></i></button>
            </div>
        </div>
        <!-- end:: Header Mobile -->
        <div class="k-grid k-grid--hor k-grid--root">

            <div class="slide-left-box">
                <span class="fas fa-times sidebar-closebtn" ></span>
                <div class="logo-circle">
                    <img src="assets/images/logo.png" >
                </div>
                <div class="detail-box">
                    <ul>
                        <?php if ($user_data['parent_id'] != 0) { ?>
                            <li>
                                <div style="margin: 10px;">Retailer</div>
                                <div>
                                    <img src="assets/images/icon-1.png"><BR>
                                    <div style="margin: 10px;"> ID :<?php
                                        $user_data['ac'] = isset($user_data['ac']) ? $user_data['ac'] : "";
                                        echo $user_data['ac'];
                                        ?></div>

                                    <img src="assets/images/icon-1.png"/><BR>
                                    <div>RMN : <?php
                                        $user_data['mobile'] = isset($user_data['mobile']) ? $user_data['mobile'] : "";
                                        echo $user_data['mobile'];
                                        ?></div>
                                </div>
                            </li>

                            <li>
                                <div style="margin: 10px;">Distributor</div>
                                <div>
                                    <img src="assets/images/icon-1.png"><BR>
                                    <div style="margin: 10px;">Name :<?php
                                        $user_data['parentName'] = isset($user_data['parentName']) ? $user_data['parentName'] : "";
                                        echo $user_data['parentName'];
                                        ?></div>

                                    <img src="assets/images/icon-1.png"><BR>
                                    <div>RMN : <?php
                                        $user_data['parentMobile'] = isset($user_data['parentMobile']) ? $user_data['parentMobile'] : "";
                                        echo $user_data['parentMobile'];
                                        ?></div>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li>
                                <img src="assets/images/icon-1.png"><BR>
                                <span>ID :<?php $user_data['ac'] = isset($user_data['ac']) ? $user_data['ac'] : "";
                        echo $user_data['ac'];
                            ?></span>
                            </li>
                            <li>
                                <img src="assets/images/icon-1.png"><BR>
                                <span>RMN : <?php $user_data['mobile'] = isset($user_data['mobile']) ? $user_data['mobile'] : "";
                                echo $user_data['mobile'];
                                ?></span>
                            </li>
<?php } ?>
                    </ul>   
                </div>
            </div> 

            <div class="k-grid__item k-grid__item--fluid k-grid k-grid--ver k-page">
                <!-- begin:: Aside -->
                <button class="k-aside-close " id="k_aside_close_btn"><i class="la la-close"></i></button>

                <div class="k-aside  k-aside--fixed     k-grid__item k-grid k-grid--desktop k-grid--hor-desktop" id="k_aside">
                    <!-- begin:: Aside -->
                    <div class="k-aside__brand  k-grid__item " id="k_aside_brand">
                        <h2 id="sidebartitle">GO Cash GO</h2>
                        <div class="k-aside__brand-tools">
                            <button class="k-aside__brand-aside-toggler k-aside__brand-aside-toggler--left" id="k_aside_toggler"><span></span></button>
                        </div>
                    </div>
                    <!-- end:: Aside -->


                    <!-- begin:: Aside Menu -->
                    <div class="k-aside-menu-wrapper    k-grid__item k-grid__item--fluid" id="k_aside_menu_wrapper">

                        <div id="k_aside_menu" class="k-aside-menu " data-kmenu-vertical="1" data-kmenu-scroll="1" data-kmenu-dropdown-timeout="500">
                            <div class="aside-nav-top-section">
                                <!-- <div class="logo-circle">
                                    <img src="assets/images/logo.png" >
                                </div> -->
                                <!-- <div class="detail-box">
                                <ul>
                                <li>
                                 <img src="assets/images/icon-1.png"><BR>
                                 <span>ID :<?php
                                $user_data['ac'] = isset($user_data['ac']) ? $user_data['ac'] : "";
                                echo$user_data['ac'];
                                ?></span>
                                </li>
                                <li>
                                 <img src="assets/images/icon-1.png"><BR>
                                 <span>RMN : <?php
                                $user_data['mobile'] = isset($user_data['mobile']) ? $user_data['mobile'] : "";
                                echo $user_data['mobile'];
                                ?></span>
                                </li>
                                 </ul>   
                             </div> -->

                                <div class="dark-blue-panel">
                                    <h4><?php
                                        $user_data['role_name'] = isset($user_data['role_name']) ? $user_data['role_name'] : "";
                                        echo strtoupper($user_data['role_name']);
                                        ?></h4>
                                    <p title="<?php
                                       $user_data['fname'] = isset($user_data['fname']) ? $user_data['fname'] : "";
                                       echo strtoupper($user_data['fname']);
                                       ?>">
                                           <?php
                                           $user_data['fname'] = isset($user_data['fname']) ? $user_data['fname'] : "";
                                           $strng = substr($user_data['fname'], 0, 15);
                                           if ((strlen($user_data['fname'])) > 15) {
                                               echo strtoupper($strng . '...');
                                           } else {
                                               echo strtoupper($user_data['fname']);
                                           }
                                           ?></p>
                                    <a  class="slide-left-trigger">Click here for more details</a>
                                </div>
                            </div> 

                            <ul class="k-menu__nav ">
                                <!--<li class="k-menu__item " aria-haspopup="true"><a href="#" class="k-menu__link "><i class="k-menu__link-icon k-menu__link-icon-color fas fa-home"></i><span class="k-menu__link-text">Dashboard</span></a></li>
                                <li class="k-menu__item  k-menu__item--submenu k-menu__item--here" aria-haspopup="true" data-kmenu-submenu-toggle="hover"><a href="javascript:;" class="k-menu__link k-menu__toggle"><i class="k-menu__link-icon k-menu__link-icon-color  fas fa-mobile"></i><span class="k-menu__link-text">Recharge</span><i class="k-menu__ver-arrow k-menu__ver-arrow-set fas fa-angle-right"></i></a>
                                    <div class="k-menu__submenu "><span class="k-menu__arrow"></span>
                                        <ul class="k-menu__subnav">
                                            <li class="k-menu__item  k-menu__item--parent" aria-haspopup="true"><span class="k-menu__link"><span class="k-menu__link-text">Recharge</span></span>
                                            </li>
                                            <li class="k-menu__item" aria-haspopup="true"><a href="mobilerecharge.html" class="k-menu__link "><i class="k-menu__link-bullet k-menu__link-bullet--dot"><span></span></i><span class="k-menu__link-text">Mobile</span></a></li>
                                            <li class="k-menu__item " aria-haspopup="true"><a href="#" class="k-menu__link "><i class="k-menu__link-bullet k-menu__link-bullet--dot"><span></span></i><span class="k-menu__link-text">DTH</span></a></li>
                                            <li class="k-menu__item " aria-haspopup="true"><a href="#" class="k-menu__link "><i class="k-menu__link-bullet k-menu__link-bullet--dot"><span></span></i><span class="k-menu__link-text">Datacards</span></a></li>
                                            <li class="k-menu__item " aria-haspopup="true"><a href="transactionhisory.html" class="k-menu__link "><i class="k-menu__link-bullet k-menu__link-bullet--dot"><span></span></i><span class="k-menu__link-text">Transaction History</span></a></li>
                                        </ul>
                                    </div>
                                </li>-->
                                <?php
                                $sidebar = get_sidebar_tittle();
                                echo get_navbar_head($sidebar, 0);

                                function get_navbar_head($sidebar, $parent) {

                                    $c = & get_instance();
                                    $t = '';
                                    $active_link = $c->uri->segment(1);
                                    $sub_active_link = $c->uri->segment(2);
                                    foreach ($sidebar as $key => $value) {
                                        //print_r($value);
                                        $content = '';
                                        $active = '';
                                        $addclass = '';
                                        if ($value['parent_id'] == $parent) {
                                            $content = get_navbar_head($sidebar, $value['id']);
                                            $cmpare_name = str_replace(" ", "", $value['tab_name']);
                                            $cmpare_name = str_replace("&", "", $cmpare_name);
                                            if ($value['parent_id'] == 0) {

                                                if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                    if ($content !== '') {

                                                        $active = 'k-menu__item--open';
                                                    } else {

                                                        $active = 'k-menu__item--active';
                                                    }
                                                } else {

                                                    // $has_child = sidebar_child($value['id']);
                                                    // if ($has_child) {
                                                    //     foreach ($has_child as $k => $v) {
                                                    //         $check_name = str_replace(" ", "", $v['tab_name']);
                                                    //         $check_name = str_replace("&", "", $check_name);
                                                    //         if (strcasecmp($active_link, $check_name) == 0 || strcasecmp($sub_active_link, $check_name) == 0) {
                                                    //             //$active = 'open show';
                                                    //             break;
                                                    //         } else {
                                                    //             $active = '';
                                                    //         }
                                                    //     }
                                                    // } else {
                                                    //     $active = '';
                                                    // }
                                                    $active = '';
                                                }
                                            } else {

                                                $cmpare_parentname = sidebar_parent($value['parent_id']);
                                                if ($cmpare_parentname) {
                                                    $cmpare_name_parent = str_replace(" ", "", $cmpare_parentname['tab_name']);
                                                    $cmpare_name_parent = str_replace("&", "", $cmpare_name_parent);
                                                    if (strcasecmp($active_link, $cmpare_name_parent) == 0 && strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                        $active = 'k-menu__item--active';
                                                    } else {
                                                        $active = '';
                                                    }
                                                } else {
                                                    if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                        $active = 'k-menu__item--active';
                                                    } else {
                                                        $active = '';
                                                    }
                                                }
                                            }

                                            $addclass = ($content != '') ? 'k-menu__item--submenu k-menu__item--here ' : '';
                                            $toggleclass = ($content != '') ? 'k-menu__toggle' : '';
                                            if ($content != '') {
                                                $content = '<div class="k-menu__submenu " k-hidden-height="160" style=""><span class="k-menu__arrow"></span>
                                    <ul class="k-menu__subnav">' . $content . '</ul></div>';
                                            }
                                            $t.='<li class="k-menu__item ' . $addclass . '' . $active . '" aria-haspopup="true">';
                                            $t.='<a href="' . $value['link_url'] . '" class="k-menu__link ' . $toggleclass . '">';
                                            $t.='<i class="' . $value['icon_class'] . '"></i>';
                                            $t.='<span class="k-menu__link-text">' . $value['tab_name'] . '</span>';
                                            if ($content != '') {
                                                $t.='<i class="k-menu__ver-arrow k-menu__ver-arrow-set fas fa-angle-right"></i>';
                                            }
                                            $t.='</a>';

                                            $t.=$content;
                                            $t.='</li>';
                                        }
                                    }

                                    return $t;
                                }
                                ?>      
                            </ul>
                        </div>
                    </div>
                    <!-- end:: Aside Menu -->

                </div>
                <!-- end:: Aside -->
                <div class="k-grid__item k-grid__item--fluid k-grid k-grid--hor k-wrapper" id="k_wrapper">
                    <!-- begin:: Header -->
                    <div id="k_header" class="k-header k-grid__item  k-header--fixed k-header-set">
                        <ul class="header-left-sidecontent">
                            <li>Customer Care : <span class="bold-txt">011- 343-344304</span></li>
                            <li><span class="bold-txt" id="cbbalanch" style="cursor:pointer">Balance Is: Rs. <?php
                                    $user_data['rupee_balance'] = isset($user_data['rupee_balance']) ? $user_data['rupee_balance'] : "";
                                    echo $user_data['rupee_balance'];
                                    ?></span></li>
                        </ul>

                        <div class="header-right-sidecontent">
                          <!--   <button class="notify-btn"><span><i class="far fa-bell"></i></span> <span class="notify-numb">3</span> Notifications </button> -->
                            <a href="Login/logout" class="logout-btn"><span class="swith-icn"><i class="fas fa-power-off"></i></span>Logout</a>
                            <div class="profile-btn-section">
                                <img id="prfl_pic" src="<?php
                                     $user_data['profile_pic'] = isset($user_data['profile_pic']) ? $user_data['profile_pic'] : "";
                                     echo $user_data['profile_pic'];
                                     ?>" />
                                <div class="dropdown">
                                    <a class="user-dropdown-toggle dropdown-toggle"  id="userprofile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php
                                        $user_data['fname'] = isset($user_data['fname']) ? $user_data['fname'] : "";
                                        $fname = explode(" ", $user_data['fname']);
                                        echo ucwords($fname[0]);
                                        ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="userprofile">
                                        <a href="MyAccount/MyProfile" class="dropdown-item" href="profile.html">My Profile</a>
                                        <!-- <a class="dropdown-item" href="#">Request Payment</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end:: Header -->
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
                                    var span = $(this).closest('#cbbalspan');
                                    $(this).html('<span class="h6"><i class=" icon-spinner3 spinner"></i> Please Wait ...</span>');
                                    $.post('Dashboard/getaccntbal', function (response) {
                                        if (response) {
                                            if (response.error == 0)
                                            {
                                                console.log(response.msg)
                                                $('#cbbalanch').html('Balance is Rs. ' + response.msg);
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

                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#k_aside_toggler").click(function () {
                                $(".aside-nav-top-section").toggleClass("hideme");
                                $("#sidebartitle").toggleClass("hideme");
                            });

                            $(".slide-left-trigger").click(function () {
                                $(".slide-left-box").toggleClass("slide-left-box-left");
                            });



                            $(".button-lft").click(function () {
                                $(".slide-left-box").removeClass("slide-left-box-left");
                                $(".k-aside--fixed").toggleClass("k-aside--on");
                            });

                            $(".sidebar-closebtn").click(function () {
                                $(".slide-left-box").removeClass("slide-left-box-left");
                                // $(".k-aside--fixed").toggleClass("k-aside--on");
                            });






                        });
                    </script>


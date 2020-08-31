
<!--start of section-->
	<section class="recharge_section width-100 make_relative mt-30">
		<div class="container">
			<div class="row">
				<div class="col-12 recharge_section_outer white-bg">
					<div class="row">
						<div class="col-lg-3">
							<div class="all-recharge-wrp list-group">
							  <?php
							    $activeurl = $this->router->fetch_class();
							    if($this->router->fetch_class()){
							  	    $activeurl = $activeurl.'/'.$this->router->fetch_method();
							    }
								$sidebar = get_nav_tittle(37,'SIDEBAR');
								
								echo get_sidebar_head($sidebar, 37, $activeurl);
								function get_sidebar_head($sidebar, $parent, $activeurl='') {
								    $c = & get_instance();
                                    $t = '';
                                    $active_link = $c->uri->segment(1);
                                    $sub_active_link = $c->uri->segment(2);

                                    foreach ($sidebar as $key => $value) {
                                        $content = '';
                                        $active = '';
                                      

                                   	 if ($value['parent_id'] == $parent){
                                            $content = get_sidebar_head($sidebar, $value['id']);
                                            $cmpare_name = str_replace(" ", "", $value['act_link_name']);
                                            $cmpare_name = str_replace("&", "", $cmpare_name);
                                            
                                            if ($value['parent_id'] == 15) {

                                                if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                    if ($content !== '') {

                                                        $active = '';

                                                    } else {

                                                        $active = 'active show';
                                                    }
                                                } else {

                                                   
                                                    $active = '';
                                                }
                                            } else {

                                                $cmpare_parentname = navbar_parent($value['parent_id']);
                                                if ($cmpare_parentname) {
                                                    $cmpare_name_parent = str_replace(" ", "", $cmpare_parentname['act_link_name']);
                                                    $cmpare_name_parent = str_replace("&", "", $cmpare_name_parent);
                                                    if (strcasecmp($active_link, $cmpare_name_parent) == 0 && strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                        $active = 'active show';
                                                    } else {
                                                        $active = '';
                                                    }
                                                } else {
                                                    if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                        $active = 'active show';
                                                    } else {
                                                        $active = '';
                                                    }
                                                }
                                            }

                                          
                                          
                                     

                                            if ($content != '') {
                                              //  $content = '<span >' . $content . '</span>';
                                            }
                                            if($activeurl == $value['link_url']){
                                            	$active = 'active show';
                                            }
                                             $t.='<a class="recharge-col text-center list-group-item list-group-item-action  ' . $active . '" href="' . $value['link_url'] . '">';
											  $t.='<div class="recharge-icon"><img src="' . $value['icon_class'] . '" width="40"></div>';
											  $t.='<div class="recharge-name font16 dark-txt font-medium">' . $value['tab_name'] . '</div>';
											     $t.=$content;
										  $t.='</a>'; 

                                          
                                            
                                        }
                                    }

                                    return $t;
								}
								?>
							

							</div>
						</div>
				



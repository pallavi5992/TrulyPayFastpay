
<!--start of section-->
	<section class="recharge_section width-100 make_relative mt-30">
		<div class="container">
			<div class="row">
				<div class="col-12 recharge_section_outer white-bg">
					<div class="row">
						<div class="col-lg-2">
							<div class="all-recharge-wrp list-group">
							  <?php
								$sidebar = get_nav_tittle(47,'SIDEBAR');
								
								echo get_sidebar_head($sidebar, 47);
								function get_sidebar_head($sidebar, $parent) {
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

                                            if ($value['parent_id'] == 8) {

                                                if (strcasecmp($active_link, $cmpare_name) == 0 || strcasecmp($sub_active_link, $cmpare_name) == 0) {

                                                    if ($content !== '') {
                                                  		//$active = preg_replace('#active show#', '', 'a.recharge-col.text-center');

                                                        $active = '';

                                                    } else {


                                                        $active = 'active show';
                                                        	//$active = preg_replace('#active show#', '', 'a.recharge-col.text-center');
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

                                             $t.='<a class="recharge-col text-center list-group-item list-group-item-action ' . $active . '" href="' . $value['link_url'] . '">';
											  $t.='<div class="recharge-icon"><img src="' . $value['icon_class'] . '" width="40"></div>';
											  $t.='<div class="recharge-name font16 dark-txt font-medium">' . $value['tab_name'] . '</div>';
											  $t.=$content;
										  $t.='</a>'; 

										 // $active = preg_replace('#active show#', '', $t);

                                          
                                            
                                        }
                                    }

                                    return $t;
								}
								?>
							
							</div>
						</div>
				



<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <div class="headerTopicContainer defaultFont boldText">
        <?php if ($tot_page_count != 0):?>    
            <div class="out-of">Page <?php echo $cur_page;?> of <?php echo $tot_page_count;?></div>
        <?php endif;?>
        <span class="headerTopicSelected">Search Results</span>
    </div>
</div>

<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <div id="pfacTableDatContainer" align="center">
            <?php if (count($all_search_results) > 0):?>
            <table cellpadding="0" cellspacing="0" class="tbs-main-users" bordercolor="#e3c9e4" border="1">
                <?php if ($_SESSION['Controller_to_search'] == "case"):?>
                
                <thead>
                    <th align="center"><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th>            
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=ref_no".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Case ID ".((isset($_GET['sort']) && $_GET['sort'] == "ref_no") ? $img : "")."</a>" : "Case No :";?></a></span></th>
                    <th width="250"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=case".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Case Name ".((isset($_GET['sort']) && $_GET['sort'] == "case") ? $img : "")."</a>" : "Case Name";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=case_cat".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Category </a>".((isset($_GET['sort']) && $_GET['sort'] == "case_cat") ? $img : "")."" : "Case Category";?></span></th>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <th width="200"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=own_country".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Country </a>".((isset($_GET['sort']) && $_GET['sort'] == "own_country") ? $img : "")."" : "Country";?></span></th>                                                            
					<?php endif;?>                    
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=status".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Current Status </a>".((isset($_GET['sort']) && $_GET['sort'] == "status") ? $img : "")."" : "Current Status";?></span></th>                                                            
                    <th width="250"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=op_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Opened date ".((isset($_GET['sort']) && $_GET['sort'] == "op_date") ? $img : "")."</a>" : "Opened date";?></a></span></th>
                    <th width="250"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=up_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Review Date ".((isset($_GET['sort']) && $_GET['sort'] == "up_date") ? $img : "")."</a>" : "Review Date";?></span></th>
                    <th width="200"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=res_staff".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Staff Responsible </a>".((isset($_GET['sort']) && $_GET['sort'] == "res_staff") ? $img : "")."" : "Responsible Staff";?></span></th>                                                            
                    <th width="250"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=cre_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Created At </a>".((isset($_GET['sort']) && $_GET['sort'] == "cre_date") ? $img : "")."" : "Created At";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=cre_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Created By </a>".((isset($_GET['sort']) && $_GET['sort'] == "cre_by") ? $img : "")."" : "Created By";?></span></th>                                                            
                </thead>
                <?php for($i=0; $i<count($all_search_results); $i++):?>
                <tr>
                    <td><div align="left" class="actionTableFieldTd">
                    
                    <?php foreach($action_panel_menu as $eachMenu):?>                

							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             

                                <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>case/delete/?ref_no=<?php echo urlencode($all_search_results[$i]['reference_number']);?>')"                                
                                   title="<?php echo $eachMenu['menu_text'];?>" 
                                   href=""><?php echo $eachMenu['menu_img'];?></a>                    
                                   
                            <?php else:?>                                       
    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?><?php echo ((strstr($eachMenu['menu_url'], "case/show")) || (strstr($eachMenu['menu_url'], "case/delete"))) ? 
                                   "?" : "&";?>ref_no=<?php echo urlencode($all_search_results[$i]['reference_number']).
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    
							
                            <?php endif;?>                            
                        
                    <?php endforeach;?>
                    
                    </div></td>
                    
                    <td width="150"><span title="<?php echo $all_search_results[$i]['reference_number'];?>" class="defaultFont"><?php echo $all_search_results[$i]['reference_number'];?></span></td>
                    <td width="250"><span title="<?php echo $all_search_results[$i]['case_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['case_name'];?></span></td>
                    <td width="150"><span title="<?php echo $all_search_results[$i]['case_cat_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['case_cat_name'];?></span></td>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <td width="200"><span title="<?php echo $all_search_results[$i]['country_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['country_name'];?></span></td>                                                            
					<?php endif;?>                    
                    <td width="150"><span title="<?php echo $all_search_results[$i]['status'];?>" class="defaultFont"><?php echo $all_search_results[$i]['status'];?></span></td>                                                            
                    <td width="250"><span title="<?php echo $all_search_results[$i]['opend_date'];?>" class="defaultFont"><?php echo ($all_search_results[$i]['opend_date'] != "0000-00-00") ? AppViewer::format_date_regular($all_search_results[$i]['opend_date']) : "Not Opend";?></span></td>
                    <td width="250"><span title="<?php echo $all_search_results[$i]['upcoming_date'];?>" class="defaultFont"><?php echo ($all_search_results[$i]['upcoming_date'] != "0000-00-00") ? AppViewer::format_date_regular($all_search_results[$i]['upcoming_date']) : "Not Published";?></span></td>
                    <td width="200"><span title="<?php echo $all_search_results[$i]['staff_responsible'];?>" class="defaultFont"><?php echo $caseModel->grab_the_responsible_staff_name($all_search_results[$i]['staff_responsible']);?></span></td>
                    <td width="250"><span title="<?php echo $all_search_results[$i]['created_date'];?>" class="defaultFont"><?php echo AppViewer::format_date_regular($all_search_results[$i]['created_date']);?></span></td>                                                            
                    <td width="150"><span title="<?php echo $all_search_results[$i]['username'];?>" class="defaultFont"><?php echo $all_search_results[$i]['username'];?></span></td>
                    
                </tr>					
                <?php endfor;?>
               
                <?php elseif ($_SESSION['Controller_to_search'] == "case-category"):?>

                <thead>
                    <th align="center"><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th>            
                    <th width="250"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=case".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Case Name ".((isset($_GET['sort']) && $_GET['sort'] == "case") ? $img : "")."</a>" : "Case Name";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=case_cat".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Category </a>".((isset($_GET['sort']) && $_GET['sort'] == "case_cat") ? $img : "")."" : "Case Category";?></span></th>
                </thead>
                <?php for($i=0; $i<count($all_search_results); $i++):?>
                <tr>
                    <td><div align="left" class="actionTableFieldTd">
                    
                    <?php foreach($action_panel_menu as $eachMenu):?>                

							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             

                                <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>case-category/delete/?cat_id=<?php echo $all_search_results[$i]['case_cat_id'];?>')"                                
                                   title="<?php echo $eachMenu['menu_text'];?>" 
                                   href=""><?php echo $eachMenu['menu_img'];?></a>                    
                                   
                            <?php else:?>                                       
    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?><?php echo ((strstr($eachMenu['menu_url'], "case-category/show")) || (strstr($eachMenu['menu_url'], "case-category/delete"))) ? 
                                   "?" : "&";?>cat_id=<?php echo $all_search_results[$i]['case_cat_id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    
							
                            <?php endif;?>                            
                        
                    <?php endforeach;?>
                    
                    </div></td>
                    
                    <td width="250"><span title="<?php echo $all_search_results[$i]['case_cat_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['case_cat_name'];?></span></td>
                    <td width="150"><span title="<?php echo $all_search_results[$i]['case_cat_description'];?>" class="defaultFont"><?php echo $all_search_results[$i]['case_cat_description'];?></span></td>
                </tr>					
                <?php endfor;?>
                
                <?php elseif ($_SESSION['Controller_to_search'] == "client"):?>
                
                <thead>
                    <th><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th>            
                    <th width="250"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By First / Last Name\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Name </a>".
                                                                    (((isset($_GET['sort']) && $_GET['sort'] == "f_name") || (isset($_GET['sort']) && $_GET['sort'] == "l_name")) ? $img : "").
                                                                    "<br /><a title=\"Sort By First Name\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">First Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "f_name") ? $img : "").
                                                                    "|<a title=\"Sort By Last Name\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=l_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">Last Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "l_name") ? $img : "")."" : "Name";?></span></th>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <th width="200"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Owned Country\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=own_country".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Owned Country ".((isset($_GET['sort']) && $_GET['sort'] == "own_country") ? $img : "")."</a>" : "Owned Country";?></a></span></th>
					<?php endif;?>                    
                    <th width="75"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Maritial Status\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=mar_stat".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Maritial Status ".((isset($_GET['sort']) && $_GET['sort'] == "mar_stat") ? $img : "")."</a>" : "Maritial Status";?></a></span></th>
                    <th width="200"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Residence\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=res".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Residence Address ".((isset($_GET['sort']) && $_GET['sort'] == "res") ? $img : "")."</a>" : "Residence Address";?></span></th>
                    <th width="75"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Land Phone\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=l_phone".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Land Phone </a>".((isset($_GET['sort']) && $_GET['sort'] == "l_phone") ? $img : "")."" : "Land Phone";?></span></th>
                    <th width="100"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Country\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=coun".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Country </a>".((isset($_GET['sort']) && $_GET['sort'] == "coun") ? $img : "")."" : "Country";?></span></th>                                                            
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Email\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=email".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Email </a>".((isset($_GET['sort']) && $_GET['sort'] == "email") ? $img : "")."" : "Email";?></span></th>                                                            
                    <th width="75"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Address of Employment\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=emp_add".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Address of Employment </a>".((isset($_GET['sort']) && $_GET['sort'] == "emp_add") ? $img : "")."" : "Address of Employment";?></span></th>                                                            
                </thead>
                <?php for($i=0; $i<count($all_search_results); $i++):?>
                <tr>
                    <td><div class="actionTableFieldTd">
                    
						<?php foreach($action_panel_menu as $eachMenu):?>                
    
							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             
                         
                            <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>client/drop/?client_id=<?php echo $all_search_results[$i]['client_id'];?>')"                                
                               title="<?php echo $eachMenu['menu_text'];?>" 
                               href=""><?php echo $eachMenu['menu_img'];?></a>                    
                               
                            <?php else:?>                                       
    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?><?php echo ((strstr($eachMenu['menu_url'], "client/show")) || (strstr($eachMenu['menu_url'], "client/drop"))) ? 
                                   "?" : "&";?>client_id=<?php echo $all_search_results[$i]['client_id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    
							
                            <?php endif;?>                            
                            
                        <?php endforeach;?>
                         
                         </div></td>         
                                   
                    <td width="250"><span title="<?php echo $all_search_results[$i]['title'] . ". " . $all_search_results[$i]['first_name'] . " " . $all_search_results[$i]['last_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['title'] . ". " . $all_search_results[$i]['first_name'] . " " . $all_search_results[$i]['last_name'];?></span></td>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <td width="200"><span title="<?php echo $all_search_results[$i]['country_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['country_name'];?></span></td>
					<?php endif;?>                    
                    <td width="75"><span title="<?php echo $all_search_results[$i]['martial_status'];?>" class="defaultFont"><?php echo $all_search_results[$i]['martial_status'];?></span></td>
                    <td width="200"><span title="<?php echo $all_search_results[$i]['resident_address'];?>" class="defaultFont"><?php echo $all_search_results[$i]['resident_address'];?></span></td>
                    <td width="75"><span title="<?php echo ($all_search_results[$i]['land_phone'] != "") ? $all_search_results[$i]['land_phone'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_search_results[$i]['land_phone'] != "") ? $all_search_results[$i]['land_phone'] : "--" ;?></span></td>
                    <td width="100"><span title="<?php echo ($all_search_results[$i]['country'] != "") ? $all_search_results[$i]['country'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_clients[$i]['country'] != "") ? $all_search_results[$i]['country'] : "--";?></span></td>                                                            
                    <td width="150"><span title="<?php echo ($all_search_results[$i]['address_of_employment'] != "") ? $all_search_results[$i]['address_of_employment'] : "--";?>" class="defaultFont"><?php echo ($all_search_results[$i]['address_of_employment'] != "") ? $all_search_results[$i]['address_of_employment'] : "--";?></span></td>                                                            
                    <td width="75"><span title="<?php echo $all_search_results[$i]['email'];?>" class="defaultFont"><?php echo ($all_search_results[$i]['email'] != "") ? $all_search_results[$i]['email'] : "--";?></span></td>                                                                                                
                </tr>					
                <?php endfor;?>
                
                <?php elseif ($_SESSION['Controller_to_search'] == "counter-party"):?>
                
                <thead>
                    <th><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th>            
                    <th width="250"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By First / Last Name\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Name </a>".
                                                                    (((isset($_GET['sort']) && $_GET['sort'] == "f_name") || (isset($_GET['sort']) && $_GET['sort'] == "l_name")) ? $img : "").
                                                                    "<br /><a title=\"Sort By First Name\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">First Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "f_name") ? $img : "").
                                                                    "|<a title=\"Sort By Last Name\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=l_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">Last Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "l_name") ? $img : "")."" : "Name";?></span></th>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <th width="200"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Owned Country\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=own_country".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Owned Country ".((isset($_GET['sort']) && $_GET['sort'] == "own_country") ? $img : "")."</a>" : "Owned Country";?></a></span></th>
					<?php endif;?>                    
                    <th width="75"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Company Name\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=comp".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Company Name ".((isset($_GET['sort']) && $_GET['sort'] == "comp") ? $img : "")."</a>" : "Company Name";?></a></span></th>
                    <th width="200"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Residence\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=res".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Residence Address ".((isset($_GET['sort']) && $_GET['sort'] == "res") ? $img : "")."</a>" : "Residence Address";?></span></th>
                    <th width="100"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Postal Address\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=pos_add".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Postal Address </a>".((isset($_GET['sort']) && $_GET['sort'] == "pos_add") ? $img : "")."" : "Postal Address";?></span></th>                                                            
                    <th width="75"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Land Phone\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=l_phone".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Land Phone </a>".((isset($_GET['sort']) && $_GET['sort'] == "l_phone") ? $img : "")."" : "Land Phone";?></span></th>
                    <th width="75"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Mobile Phone\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=mob".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Mobile Phone </a>".((isset($_GET['sort']) && $_GET['sort'] == "mob") ? $img : "")."" : "Mobile Phone";?></span></th>                                                            
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a title=\"Sort By Email\" class=\"soringMenusLink\" href=\"".$searchQ."&sort=email".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Email </a>".((isset($_GET['sort']) && $_GET['sort'] == "email") ? $img : "")."" : "Email";?></span></th>                                                            
                </thead>
                <?php for($i=0; $i<count($all_search_results); $i++):?>
                <tr>
                    <td><div class="actionTableFieldTd">
                    
						<?php foreach($action_panel_menu as $eachMenu):?>                
    
							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             
                         
                            <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>counter-party/drop/?cp_id=<?php echo $all_search_results[$i]['counter_party_id'];?>')"                                
                               title="<?php echo $eachMenu['menu_text'];?>" 
                               href=""><?php echo $eachMenu['menu_img'];?></a>                    
                               
                            <?php else:?>                                       
    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?><?php echo ((strstr($eachMenu['menu_url'], "counter-party/show")) || (strstr($eachMenu['menu_url'], "counter-party/drop"))) ? 
                                   "?" : "&";?>cp_id=<?php echo $all_search_results[$i]['counter_party_id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    
							
                            <?php endif;?>                            
                            
                        <?php endforeach;?>
                         
                         </div></td>         
                                   
                    <td width="250"><span title="<?php echo $all_search_results[$i]['title'] . " " . $all_search_results[$i]['first_name'] . " " . $all_search_results[$i]['last_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['title'] . " " . $all_search_results[$i]['first_name'] . " " . $all_search_results[$i]['last_name'];?></span></td>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <td width="75"><span title="<?php echo $all_search_results[$i]['country_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['country_name'];?></span></td>
					<?php endif;?>                    
                    <td width="75"><span title="<?php echo $all_search_results[$i]['company_name'];?>" class="defaultFont"><?php echo ($all_search_results[$i]['company_name'] != "") ? $all_search_results[$i]['company_name'] : "--";?></span></td>
                    <td width="200"><span title="<?php echo $all_search_results[$i]['resident_address'];?>" class="defaultFont"><?php echo $all_search_results[$i]['resident_address'];?></span></td>
                    <td width="150"><span title="<?php echo ($all_search_results[$i]['postal_address'] != "") ? $all_search_results[$i]['postal_address'] : "--";?>" class="defaultFont"><?php echo ($all_search_results[$i]['postal_address'] != "") ? $all_search_results[$i]['postal_address'] : "--";?></span></td>                                                            
                    <td width="75"><span title="<?php echo ($all_search_results[$i]['land_phone'] != "") ? $all_search_results[$i]['land_phone'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_search_results[$i]['land_phone'] != "") ? $all_search_results[$i]['land_phone'] : "--" ;?></span></td>
                    <td width="100"><span title="<?php echo ($all_search_results[$i]['mobile_phone'] != "") ? $all_search_results[$i]['mobile_phone'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_search_results[$i]['mobile_phone'] != "") ? $all_search_results[$i]['mobile_phone'] : "--";?></span></td>                                                            
                    <td width="75"><span title="<?php echo $all_search_results[$i]['email'];?>" class="defaultFont"><?php echo ($all_search_results[$i]['email'] != "") ? $all_search_results[$i]['email'] : "--";?></span></td>                                                                                                
                </tr>					
                <?php endfor;?>
                
                <?php elseif ($_SESSION['Controller_to_search'] == "pahro"):?>
                
                <thead>
                    <?php if ($have_permissions):?><th align="center"><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th><?php endif;?>            
                    <th width="200"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Name </a>".
                                                                    (((isset($_GET['sort']) && $_GET['sort'] == "f_name") || (isset($_GET['sort']) && $_GET['sort'] == "l_name")) ? $img : "").
                                                                    "<br /><a class=\"soringMenusLink\" href=\"".$searchQ."&sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">First Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "f_name") ? $img : "").
                                                                    "|<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=l_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">Last Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "l_name") ? $img : "")."" : "Name";?></span></th>
                    <th width="100"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=u_type".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">User Type ".((isset($_GET['sort']) && $_GET['sort'] == "u_type") ? $img : "")."</a>" : "User Type";?></a></span></th>                                                                    
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=u_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Username ".((isset($_GET['sort']) && $_GET['sort'] == "u_name") ? $img : "")."</a>" : "Username";?></a></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=email".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Email ".((isset($_GET['sort']) && $_GET['sort'] == "email") ? $img : "")."</a>" : "Email";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=created_at".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Created At </a>".((isset($_GET['sort']) && $_GET['sort'] == "created_at") ? $img : "")."" : "Created At";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=last_login".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Last Login </a>".((isset($_GET['sort']) && $_GET['sort'] == "last_login") ? $img : "")."" : "Last Login";?></span></th>                                                            
                </thead>
                <?php for($i=0; $i<count($all_search_results); $i++):?>
                	<?php $trClass = "";?>
                     <?php if ($_SESSION['logged_user']['id'] == $all_search_results[$i]['id']):?>                
                        	<?php $trClass = "systemUserColored";?>                     	
                     <?php endif;?>
                <tr class="<?php echo $trClass;?>">
                    <?php if ($have_permissions):?><td><div align="left" class="actionTableFieldTd">

						<?php foreach($action_panel_menu as $eachMenu):?>                
    
                            <?php if ($_SESSION['logged_user']['id'] != $all_search_results[$i]['id']):?>
                             
		 						<?php if ($eachMenu['menu_text'] == "Drop"):?>                             
                             
                             	<div class="d-del">                             
								<?php if (in_array(21, $_SESSION['logged_user']['permissions'])):?>
                                    <input type="checkbox" name="pahro_users_deletion" value="<?php echo $all_pahro_users[$i]['id'];?>" />&nbsp;
                                <?php endif;?>
                                <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>pahro/delete/?pahro_id=<?php echo $all_search_results[$i]['id'];?>')"                                
                                   title="<?php echo $eachMenu['menu_text'];?>" 
                                   href=""><?php echo $eachMenu['menu_img'];?></a>                    
                                </div>
                                &nbsp;  
                                <!---->
                                <?php
									$status = $all_search_results[$i]['status'];
									$page_no = $_GET['page'];
									if($status == 1){
										$status_val = 0;
										$status_img = 'deact.jpg';
										$status_title = 'Click to deactivate this user.';
										$status_msg = 'deactivate';
									}
									else{
										$status_val = 1;
										$status_img = 'act.jpg';
										$status_title = 'Click to enable this user.';
										$status_msg = 'enable';
									}
								?>
                                <a href="#" onclick="return set_status('<?php echo $all_search_results[$i]['id'];?>','<?php echo $status_val;?>','<?php echo $page_no;?>')" title="<?php echo $status_title;?>"><img border="0" src="../../public/images/<?php echo $status_img;?>"></a>
                                <!---->
								<?php else:?>                                   

                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?>pahro_id=<?php echo $all_search_results[$i]['id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><?php echo $eachMenu['menu_img'];?></a>                    
                                
                                <?php endif;?>
                            
                            <?php else:?>
                            
                                <?php if ($eachMenu['menu_text'] != "Drop"):?>
                                
                                    <a title="<?php echo $eachMenu['menu_text'];?>" 
                                       href="<?php echo $eachMenu['menu_url'];?>pahro_id=<?php echo $all_search_results[$i]['id'].
                                       (isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><?php echo $eachMenu['menu_img'];?></a>                    
                                       
                                <?php endif;?>

                            <?php endif;?>
                            
                        <?php endforeach;?>

                        </div>
                        
                    </td><?php endif;?>
                    <td width="200"><span title="<?php echo $all_search_results[$i]['first_name'] . " " . $all_search_results[$i]['last_name'];?>" class="defaultFont"><?php echo $all_search_results[$i]['first_name'];?></span>&nbsp;<span class="defaultFont"><?php echo $all_search_results[$i]['last_name'];?></span></td>
                    <td width="100"><span title="<?php echo $all_search_results[$i]['user_type'];?>" class="defaultFont"><?php echo $all_search_results[$i]['user_type'];?></span></td>                           
                    <td width="150"><span title="<?php echo $all_search_results[$i]['username'];?>" class="defaultFont"><?php echo $all_search_results[$i]['username'];?></span></td>
                    <td width="150"><span title="<?php echo $all_search_results[$i]['email'];?>" class="defaultFont"><?php echo ($all_search_results[$i]['email'] != "") ? $all_search_results[$i]['email'] : "--";?></span></td>
                    <td width="150"><span title="<?php echo $all_search_results[$i]['created_at'];?>" class="defaultFont"><?php echo AppViewer::format_date($all_search_results[$i]['created_at']);?></span></td>
                    <td width="150"><span title="<?php echo $all_search_results[$i]['last_login'];?>" class="defaultFont"><?php echo ($all_search_results[$i]['last_login'] != "0000-00-00 00:00:00") ? $all_search_results[$i]['last_login'] : "Not Logged";?></span></td>                                                            
                </tr>					
                <?php endfor;?>

                <?php elseif ($_SESSION['Controller_to_search'] == "system"):?>
                
                <thead>
                    <th><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=past".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"> No ".((isset($_GET['sort']) && $_GET['sort'] == "past") ? $img : "")."</a>" : " Past Activity No";?></a></span></th>                
                    <th width="75"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=u_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Username ".((isset($_GET['sort']) && $_GET['sort'] == "u_name") ? $img : "")."</a>" : " Username";?></a></span></th>
                    <th width="400"><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=act_desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Activity Description ".((isset($_GET['sort']) && $_GET['sort'] == "act_desc") ? $img : "")."</a>" : " Activity Description";?></span></th>
                    <th><span class="defaultFont"><?php echo ($all_search_results_count > 1) ? "<a class=\"soringMenusLink\" href=\"".$searchQ."&sort=time".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Date &amp; Time </a>".((isset($_GET['sort']) && $_GET['sort'] == "time") ? $img : "")."" : " Date &amp; Time";?></span></th>
                </thead>
                <?php for($i=0; $i<count($all_search_results); $i++):?>
                <?php
					$act_desc = $all_search_results[$i]['action_type_desc'];
					$act_desc = str_replace("$$$$$","'",$act_desc);
				?>
                <tr>
                    <td><span title="<?php echo $all_search_results[$I]['id'];?>" class="defaultFont"><?php echo $all_search_results[$i]['id'];?></span></td>                
                    <td><span title="<?php echo $all_search_results[$i]['username'];?>" class="defaultFont"><?php echo $all_search_results[$i]['username'];?></span></td>
                    <td class="a-left"><span title="<?php echo $act_desc;?>" class="defaultFont"><?php echo $act_desc;?></span></td>
                    <td><span title="<?php echo $all_search_results[$i]['date_time'];?>" class="defaultFont"><?php echo AppViewer::format_date_with_time($all_search_results[$i]['date_time']);?></span></td>
                </tr>					
                <?php endfor;?>
                
                <?php endif;?>    
            </table>
            <?php else:?>
                <div align="left"><span class="defaultFont boldText specializedTexts">No results found !</span></div>
            <?php endif;?>
        </div>
    </div>   
</div>
	<?php if ($tot_page_count > 1):?>   
    <div id="pagination_wrap">
        <div id="pagination" align="center">
        <!-- This is the page jumping secte box and at the moment it has been disbaled and will be enables if requested -->    
        <?php /*?>                
        <div id="jump_to_page_wrapper" class="floatLeft">
            <select id="jump_to_page" class="smallDropDownMenu" onchange="got_to_this_page('<?php echo $pathToJump;?>');">
            <?php if ($tot_page_count == 0):?>
                <option value="">Page no</option>            
            <?php elseif (($tot_page_count == 1) && (!isset($_GET['page']))):?>
                <option value="">Page no</option>                    
                <option value="1" selected="selected">1</option>                    
            <?php else:?>    
                <option value="">Page no</option>                    
                <?php for($i=1; $i<=$tot_page_count; $i++):?>
                    <option value="<?php echo $i;?>" <?php echo ($i == $_GET['page']) ? "selected='selected'" : "";?>><?php echo $i;?></option>                
                <?php endfor;?>
            <?php endif;?>
            </select>
    </div><?php */
    ?>           		
            <div class="paginationContainer"><?php echo $pagination;?></div>
        </div>        
    </div>
    <?php endif;?>
<?php if (strstr($_SERVER['REQUEST_URI'], "pahro/search")):?>    
<a href="#" onclick="javascript:delete_selected_users();" class="singleLink defaultFont">Delete Selected records</a>        
<?php endif;?>	
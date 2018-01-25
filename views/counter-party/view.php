<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <!-- Success / Warning message -->    
    <?php echo $headerDivMsg;?>
    <!--  End of the Success / Warning mesage -->    
    <div class="headerTopicContainer defaultFont boldText">
        <?php if ($tot_page_count != 0):?>    
			<div class="out-of">Page <?php echo $cur_page;?> of <?php echo $tot_page_count;?></div>   
        <?php endif;?>  
        <span class="headerTopicSelected">All Counter Parties</span>
    </div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <div id="pfacTableDatContainer" align="center">
           <?php if ($invalidPage != true):?>           
            <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" class="tbs-main-users">
                <thead>
                    <th><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th>            
                    <th width="250"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By First / Last Name\" class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Name </a>".
                                                                    (((isset($_GET['sort']) && $_GET['sort'] == "f_name") || (isset($_GET['sort']) && $_GET['sort'] == "l_name")) ? $img : "").
                                                                    "<br /><a title=\"Sort By First Name\" class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">First Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "f_name") ? $img : "").
                                                                    "|<a title=\"Sort By Last Name\" class=\"soringMenusLink\" href=\"?sort=l_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">Last Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "l_name") ? $img : "")."" : "Name";?></span></th>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                                        
                    <th width="75"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By Country\" class=\"soringMenusLink\" href=\"?sort=own_country".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Owned Country ".((isset($_GET['sort']) && $_GET['sort'] == "own_country") ? $img : "")."</a>" : "Owned Country";?></a></span></th>
					<?php endif;?>                    
                    <th width="150"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By Company Name\" class=\"soringMenusLink\" href=\"?sort=comp".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Company Name ".((isset($_GET['sort']) && $_GET['sort'] == "comp") ? $img : "")."</a>" : "Company Name";?></a></span></th>
                    <th width="200"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By Residence\" class=\"soringMenusLink\" href=\"?sort=res".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Residence Address ".((isset($_GET['sort']) && $_GET['sort'] == "res") ? $img : "")."</a>" : "Residence Address";?></span></th>
                    <th width="100"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By Postal Address\" class=\"soringMenusLink\" href=\"?sort=pos_add".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Postal Address </a>".((isset($_GET['sort']) && $_GET['sort'] == "pos_add") ? $img : "")."" : "Postal Address";?></span></th>                                                            
                    <th width="75"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By Land Phone\" class=\"soringMenusLink\" href=\"?sort=l_phone".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Land Phone </a>".((isset($_GET['sort']) && $_GET['sort'] == "l_phone") ? $img : "")."" : "Land Phone";?></span></th>
                    <th width="75"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By Mobile Phone\" class=\"soringMenusLink\" href=\"?sort=mob".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Mobile Phone </a>".((isset($_GET['sort']) && $_GET['sort'] == "mob") ? $img : "")."" : "Mobile Phone";?></span></th>                                                            
                    <th width="150"><span class="defaultFont"><?php echo ($all_counter_partys_count > 1) ? "<a title=\"Sort By Email\" class=\"soringMenusLink\" href=\"?sort=email".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Email </a>".((isset($_GET['sort']) && $_GET['sort'] == "email") ? $img : "")."" : "Email";?></span></th>                                                            
                </thead>
                <?php for($i=0; $i<count($all_counter_partys); $i++):?>
                <tr>
                    <td><div class="actionTableFieldTd">
                    
						<?php foreach($action_panel_menu as $eachMenu):?>                
    
							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             
                         
                            <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>counter-party/drop/?cp_id=<?php echo $all_counter_partys[$i]['counter_party_id'];?>')"                                
                               title="<?php echo $eachMenu['menu_text'];?>" 
                               href=""><?php echo $eachMenu['menu_img'];?></a>                    
                               
                            <?php else:?>                                       
    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?><?php echo ((strstr($eachMenu['menu_url'], "counter-party/show")) || (strstr($eachMenu['menu_url'], "counter-party/drop"))) ? 
                                   "?" : "&";?>cp_id=<?php echo $all_counter_partys[$i]['counter_party_id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    
							
                            <?php endif;?>                            
                            
                        <?php endforeach;?>
                         
                         </div></td>         
                                   
                    <td width="250"><span title="<?php echo $all_counter_partys[$i]['title'] . " " . $all_counter_partys[$i]['first_name'] . " " . $all_counter_partys[$i]['last_name'];?>" class="defaultFont"><?php echo $all_counter_partys[$i]['title'] . " " . $all_counter_partys[$i]['first_name'] . " " . $all_counter_partys[$i]['last_name'];?></span></td>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                                        
                    <td width="150"><span title="<?php echo $all_counter_partys[$i]['country_name'];?>" class="defaultFont"><?php echo ($all_counter_partys[$i]['country_name'] != "") ? $all_counter_partys[$i]['country_name'] : "--";?></span></td>
					<?php endif;?>                    
                    <td width="75"><span title="<?php echo $all_counter_partys[$i]['company_name'];?>" class="defaultFont"><?php echo ($all_counter_partys[$i]['company_name'] != "") ? $all_counter_partys[$i]['company_name'] : "--";?></span></td>
                    <td width="200"><span title="<?php echo $all_counter_partys[$i]['resident_address'];?>" class="defaultFont"><?php echo $all_counter_partys[$i]['resident_address'];?></span></td>
                    <td width="150"><span title="<?php echo ($all_counter_partys[$i]['postal_address'] != "") ? $all_counter_partys[$i]['postal_address'] : "--";?>" class="defaultFont"><?php echo ($all_counter_partys[$i]['postal_address'] != "") ? $all_counter_partys[$i]['postal_address'] : "--";?></span></td>                                                            
                    <td width="75"><span title="<?php echo ($all_counter_partys[$i]['land_phone'] != "") ? $all_counter_partys[$i]['land_phone'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_counter_partys[$i]['land_phone'] != "") ? $all_counter_partys[$i]['land_phone'] : "--" ;?></span></td>
                    <td width="100"><span title="<?php echo ($all_counter_partys[$i]['mobile_phone'] != "") ? $all_counter_partys[$i]['mobile_phone'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_counter_partys[$i]['mobile_phone'] != "") ? $all_counter_partys[$i]['mobile_phone'] : "--";?></span></td>                                                            
                    <td width="75"><span title="<?php echo $all_counter_partys[$i]['email'];?>" class="defaultFont"><?php echo ($all_counter_partys[$i]['email'] != "") ? $all_counter_partys[$i]['email'] : "--";?></span></td>                                                                                                
                </tr>					
                <?php endfor;?>
            </table>
            <?php else:?>  
	            <div class="someWidth6 floatLeft"><span class="defaultFont boldText specializedTexts">No Records !</span></div>
			<?php endif;?>                  
        </div>
    </div>
</div>
<?php if ($invalidPage != true):?>
	<?php if ($tot_page_count > 1):?>   
    <div id="pagination_wrap">
        <div id="pagination" align="center">
    <!-- This is the page jumping secte box and at the moment it has been disbaled and will be enables if requested -->    
    <?php /*?>        
    <div id="jump_to_page_wrapper" class="floatLeft">
        <select id="jump_to_page" class="smallDropDownMenu" onchange="got_to_this_page('<?php echo $jumpPath;?>');">
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
<?php endif;?>    	 
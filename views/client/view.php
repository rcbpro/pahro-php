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
        <span class="headerTopicSelected">All Clients</span>
    </div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <div id="pfacTableDatContainer" align="center">
           <?php if ($invalidPage != true):?>           
            <table border="1" bordercolor="#E3C9E4" cellpadding="0" cellspacing="0" class="tbs-main-users">
                <thead>
                    <th><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th>            
                    <th width="250"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By First / Last Name\" class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Name </a>".
                                                                    (((isset($_GET['sort']) && $_GET['sort'] == "f_name") || (isset($_GET['sort']) && $_GET['sort'] == "l_name")) ? $img : "").
                                                                    "<br /><a title=\"Sort By First Name\" class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">First Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "f_name") ? $img : "").
                                                                    "|<a title=\"Sort By Last Name\" class=\"soringMenusLink\" href=\"?sort=l_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">Last Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "l_name") ? $img : "")."" : "Name";?></span></th>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <th width="150"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By Owned Country\" class=\"soringMenusLink\" href=\"?sort=own_country".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Owned Country ".((isset($_GET['sort']) && $_GET['sort'] == "own_country") ? $img : "")."</a>" : "Owned Country";?></a></span></th>
					<?php endif;?>                    
                    <th width="75"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By Maritial Status\" class=\"soringMenusLink\" href=\"?sort=mar_stat".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Maritial Status ".((isset($_GET['sort']) && $_GET['sort'] == "mar_stat") ? $img : "")."</a>" : "Maritial Status";?></a></span></th>
                    <th width="200"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By Residence\" class=\"soringMenusLink\" href=\"?sort=res".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Residence Address ".((isset($_GET['sort']) && $_GET['sort'] == "res") ? $img : "")."</a>" : "Residence Address";?></span></th>
                    <th width="75"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By Land Phone\" class=\"soringMenusLink\" href=\"?sort=l_phone".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Land Phone </a>".((isset($_GET['sort']) && $_GET['sort'] == "l_phone") ? $img : "")."" : "Land Phone";?></span></th>
                    <th width="100"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By Country\" class=\"soringMenusLink\" href=\"?sort=coun".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Country </a>".((isset($_GET['sort']) && $_GET['sort'] == "coun") ? $img : "")."" : "Country";?></span></th>                                                            
                    <th width="150"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By Email\" class=\"soringMenusLink\" href=\"?sort=email".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Email </a>".((isset($_GET['sort']) && $_GET['sort'] == "email") ? $img : "")."" : "Email";?></span></th>                                                            
                    <th width="75"><span class="defaultFont"><?php echo ($all_clients_count > 1) ? "<a title=\"Sort By Address of Employment\" class=\"soringMenusLink\" href=\"?sort=emp_add".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Address of Employment </a>".((isset($_GET['sort']) && $_GET['sort'] == "emp_add") ? $img : "")."" : "Address of Employment";?></span></th>                                                            
                </thead>
                <?php for($i=0; $i<count($all_clients); $i++):?>
                <tr>
                    <td><div class="actionTableFieldTd">
                    
						<?php foreach($action_panel_menu as $eachMenu):?>                
    
							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             
                         
                            <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>client/drop/?client_id=<?php echo $all_clients[$i]['client_id'];?>')"                                
                               title="<?php echo $eachMenu['menu_text'];?>" 
                               href=""><?php echo $eachMenu['menu_img'];?></a>                    
                               
                            <?php else:?>                                       
    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?><?php echo ((strstr($eachMenu['menu_url'], "client/show")) || (strstr($eachMenu['menu_url'], "client/drop"))) ? 
                                   "?" : "&";?>client_id=<?php echo $all_clients[$i]['client_id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    
							
                            <?php endif;?>                            
                            
                        <?php endforeach;?>
                         
                         </div></td>         
                                   
                    <td width="250"><span title="<?php echo $all_clients[$i]['title'] . " " . $all_clients[$i]['first_name'] . " " . $all_clients[$i]['last_name'];?>" class="defaultFont"><?php echo $all_clients[$i]['title'] . " " . $all_clients[$i]['first_name'] . " " . $all_clients[$i]['last_name'];?></span></td>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <td width="150"><span title="<?php echo $all_clients[$i]['country_name'];?>" class="defaultFont"><?php echo $all_clients[$i]['country_name'];?></span></td>
					<?php endif;?>                    
                    <td width="75"><span title="<?php echo $all_clients[$i]['martial_status'];?>" class="defaultFont"><?php echo ($all_clients[$i]['martial_status'] != "") ? $all_clients[$i]['martial_status'] : "--";?></span></td>
                    <td width="200"><span title="<?php echo $all_clients[$i]['resident_address'];?>" class="defaultFont"><?php echo $all_clients[$i]['resident_address'];?></span></td>
                    <td width="75"><span title="<?php echo ($all_clients[$i]['land_phone'] != "") ? $all_clients[$i]['land_phone'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_clients[$i]['land_phone'] != "") ? $all_clients[$i]['land_phone'] : "--" ;?></span></td>
                    <td width="100"><span title="<?php echo ($all_clients[$i]['country'] != "") ? $all_clients[$i]['country'] : "- Not Available -";?>" class="defaultFont"><?php echo ($all_clients[$i]['country'] != "") ? $all_clients[$i]['country'] : "--";?></span></td>                                                            
                    <td width="75"><span title="<?php echo $all_clients[$i]['email'];?>" class="defaultFont"><?php echo ($all_clients[$i]['email'] != "") ? $all_clients[$i]['email'] : "--";?></span></td>                                                                                                
                    <td width="150"><span title="<?php echo ($all_clients[$i]['address_of_employment'] != "") ? $all_clients[$i]['address_of_employment'] : "--";?>" class="defaultFont"><?php echo ($all_clients[$i]['address_of_employment'] != "") ? $all_clients[$i]['address_of_employment'] : "--";?></span></td>                                                            
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
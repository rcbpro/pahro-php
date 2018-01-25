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
        <span class="headerTopicSelected">All Cases</span>
    </div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <div id="pfacTableDatContainer" align="center">
           <?php if ($invalidPage != true):?>           
            <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" class="tbs-main-users">
                <thead>
                    <th align="center"><div class="actionTableFieldTd3"><span class="defaultFont">Action</span></div></th>            
                    <th width="150"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=ref_no".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Case ID ".((isset($_GET['sort']) && $_GET['sort'] == "ref_no") ? $img : "")."</a>" : "Case No :";?></a></span></th>
                    <th width="350"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=case".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Case Name ".((isset($_GET['sort']) && $_GET['sort'] == "case") ? $img : "")."</a>" : "Case Name";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=case_cat".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Category </a>".((isset($_GET['sort']) && $_GET['sort'] == "case_cat") ? $img : "")."" : "Case Category";?></span></th>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                    
                    <th width="250"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=own_country".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Country </a>".((isset($_GET['sort']) && $_GET['sort'] == "own_country") ? $img : "")."" : "Country";?></span></th>                                                            
					<?php endif;?>                    
                    <th width="150"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=status".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Current Status </a>".((isset($_GET['sort']) && $_GET['sort'] == "status") ? $img : "")."" : "Current Status";?></span></th>                                                            
                    <th width="200"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=op_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Opened date ".((isset($_GET['sort']) && $_GET['sort'] == "op_date") ? $img : "")."</a>" : "Opened date";?></a></span></th>
                    <th width="200"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=up_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Review Date ".((isset($_GET['sort']) && $_GET['sort'] == "up_date") ? $img : "")."</a>" : "Review Date";?></span></th>
                    <th width="224"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=res_staff".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Staff Responsible </a>".((isset($_GET['sort']) && $_GET['sort'] == "res_staff") ? $img : "")."" : "Responsible Staff";?></span></th>                                                            
                    <th width="250"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=cre_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Created At </a>".((isset($_GET['sort']) && $_GET['sort'] == "cre_date") ? $img : "")."" : "Created At";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_cases_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=cre_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Created By </a>".((isset($_GET['sort']) && $_GET['sort'] == "cre_by") ? $img : "")."" : "Created By";?></span></th>                                                            

                </thead>
                <?php for($i=0; $i<count($all_cases); $i++):?>
                <tr>
                    <td><div align="left" class="actionTableFieldTd">
                    
                    <?php foreach($action_panel_menu as $eachMenu):?>                

							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             
                         
                                <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>case/delete/?ref_no=<?php echo urlencode($all_cases[$i]['reference_number']);?>')"                                
                                   title="<?php echo $eachMenu['menu_text'];?>" 
                                   href=""><?php echo $eachMenu['menu_img'];?></a>                    

                            <?php else:?>                                       
                    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?>ref_no=<?php echo urlencode($all_cases[$i]['reference_number']).
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    
                                
                            <?php endif;?>                            
                        
                    <?php endforeach;?>
                    
                    </div></td>
                    
                    <td><span title="<?php echo $all_cases[$i]['reference_number'];?>" class="defaultFont"><?php echo $all_cases[$i]['reference_number'];?></span></td>
                    <td><span title="<?php echo $all_cases[$i]['case_name'];?>" class="defaultFont"><?php echo $all_cases[$i]['case_name'];?></span></td>
                    <td><span title="<?php echo $all_cases[$i]['case_cat_name'];?>" class="defaultFont"><?php echo $all_cases[$i]['case_cat_name'];?></span></td>
					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>                                        
                    <td><span title="<?php echo $all_cases[$i]['country_name'];?>" class="defaultFont"><?php echo $all_cases[$i]['country_name'];?></span></td>                                                            
					<?php endif;?>                    
                    <td><span title="<?php echo $all_cases[$i]['status'];?>" class="defaultFont"><?php echo $all_cases[$i]['status'];?></span></td>                                                           
                    <td><span title="<?php echo $all_cases[$i]['opend_date'];?>" class="defaultFont"><?php echo ($all_cases[$i]['opend_date'] != "0000-00-00") ? AppViewer::format_date_regular($all_cases[$i]['opend_date']) : "Not Opend";?></span></td>
                    <td><span title="<?php echo $all_cases[$i]['upcoming_date'];?>" class="defaultFont"><?php echo ($all_cases[$i]['upcoming_date'] != "0000-00-00") ? AppViewer::format_date_regular($all_cases[$i]['upcoming_date']) : "Not Published";?></span></td>
                    <td><span title="<?php echo $all_cases[$i]['staff_responsible'];?>" class="defaultFont"><?php echo CaseModel::grab_the_responsible_staff_name($all_cases[$i]['staff_responsible']);?></span></td>
                    <td><span title="<?php echo $all_cases[$i]['created_date'];?>" class="defaultFont"><?php echo AppViewer::format_date_regular($all_cases[$i]['created_date']);?></span></td>                                                            
                    <td><span title="<?php echo $all_cases[$i]['username'];?>" class="defaultFont"><?php echo $all_cases[$i]['username'];?></span></td>
                    
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
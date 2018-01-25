<script type="text/javascript" language="javascript">
function delete_selected_users(){

	var mainCtl;
	var checkBoxes;
	var userIds = "0";
	
	checkBoxes = document.getElementsByTagName('input');
	
	for (var i=0; i<checkBoxes.length; i++){
		if(checkBoxes[i].checked == true){
			userIds += "&" + checkBoxes[i].value;
		}
	}
	
	if (userIds == "0"){
		alert("Please select user(s)");
	}else{
		if(confirm('Are you sure to delete selected user(s)?')){
		   userIds = userIds.replace('0',"");
		   location.href = "<?php echo $site_config['base_url'];?>pahro/delete-multiple/?pahro_ids=" + userIds;
		}
	}
}
</script>
<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <!-- Start of the header div message -->
    <?php echo $headerDivMsg;?>    
    <!-- End of the header div message -->
    <div class="headerTopicContainer defaultFont boldText">
        <?php if ($tot_page_count != 0):?>    
			<div class="out-of">Page <?php echo $cur_page;?> of <?php echo $tot_page_count;?></div>   
        <?php endif;?>  
        <span class="headerTopicSelected">Users</span>
    </div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <div id="pfacTableDatContainer" align="center">
           <?php if ($invalidPage != true):?>          
           <!-- Users filtering part -->
	    	<form id="pahro_country_submit_form" name="pahro_country_submit_form" action="" method="post">            
            <div id="d-filter" class="a-left">
                <div id="d-filter-l" class="defaultFont specializedTexts boldText"><!-- --></div>                       
                <div id="d-filter-r" class="defaultFont">
                    <?php echo CommonFunctions::get_all_countries("filter_by_countries_required", $inline=true, "filtering", $_SESSION['filter_by_countries_required']); ?><input name="countries_filter" type="submit" value="Filter" class="inputs submit btn-filter" />
                    <input name="countries_filter_reset" type="submit" value="Reset" class="inputs submit btn-filter" />                                    
                </div>
            </div>            
            </form>
            <br />
           <!-- Users filtering part -->            
            <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" class="tbs-main-users">
                <thead>
                    <?php if ($have_permissions):?><th align="center"><div class="actionTableFieldTd"><span class="defaultFont">Action</span></div></th><?php endif;?>            
                    <th width="200"><span class="defaultFont"><?php echo ($all_pahro_users_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Name </a>".
                                                                    (((isset($_GET['sort']) && $_GET['sort'] == "f_name") || (isset($_GET['sort']) && $_GET['sort'] == "l_name")) ? $img : "").
                                                                    "<br /><a class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">First Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "f_name") ? $img : "").
                                                                    "|<a class=\"soringMenusLink\" href=\"?sort=l_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">Last Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "l_name") ? $img : "")."" : "Name";?></span></th>
                    <th width="100"><span class="defaultFont"><?php echo ($all_pahro_users_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=u_type".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">User Type ".((isset($_GET['sort']) && $_GET['sort'] == "u_type") ? $img : "")."</a>" : "User Type";?></a></span></th>                                                                    
                    <th width="150"><span class="defaultFont"><?php echo ($all_pahro_users_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=u_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Username ".((isset($_GET['sort']) && $_GET['sort'] == "u_name") ? $img : "")."</a>" : "Username";?></a></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_pahro_users_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=email".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Email ".((isset($_GET['sort']) && $_GET['sort'] == "email") ? $img : "")."</a>" : "Email";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_pahro_users_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=created_at".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Created At </a>".((isset($_GET['sort']) && $_GET['sort'] == "created_at") ? $img : "")."" : "Created At";?></span></th>
                    <th width="150"><span class="defaultFont"><?php echo ($all_pahro_users_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=last_login".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Last Login </a>".((isset($_GET['sort']) && $_GET['sort'] == "last_login") ? $img : "")."" : "Last Login";?></span></th>                                                            
                </thead>
                <?php for($i=0; $i<count($all_pahro_users); $i++):?>
                	<?php $trClass = "";?>
                     <?php if ($_SESSION['logged_user']['id'] == $all_pahro_users[$i]['id']):?>                
                        	<?php $trClass = "systemUserColored";?>                     	
                     <?php endif;?>
                <tr class="<?php echo $trClass;?>">
                    <?php if ($have_permissions):?><td><div align="left" class="actionTableFieldTd">

						<?php foreach($action_panel_menu as $eachMenu):?>                
    
                            <?php if ($_SESSION['logged_user']['id'] != $all_pahro_users[$i]['id']):?>

		 						<?php if ($eachMenu['menu_text'] == "Drop"):?>                             
                             
                             	<div class="d-del">
								<?php if (in_array(21, $_SESSION['logged_user']['permissions'])):?>
                                    <input type="checkbox" name="pahro_users_deletion" value="<?php echo $all_pahro_users[$i]['id'];?>" />&nbsp;
                                <?php endif;?>
                             	
                                <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>pahro/delete/?pahro_id=<?php echo $all_pahro_users[$i]['id'];?>')"                                
                                   title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="#"><?php echo $eachMenu['menu_img'];?></a>                    
                                </div>

                                <!---->
                                <?php
									$status = $all_pahro_users[$i]['status'];
									$page_no = $_GET['page'];
									if($status == 1){
										$status_val = 0;
										$status_img = 'deact.jpg';
										$status_title = 'Click to deactivate this user.';
									}
									else{
										$status_val = 1;
										$status_img = 'act.jpg';
										$status_title = 'Click to enable this user.';
									}
								?>
                                <a href="#" onclick="return set_status('<?php echo $all_pahro_users[$i]['id'];?>','<?php echo $status_val;?>','<?php echo $page_no;?>')" title="<?php echo $status_title;?>"><img border="0" src="../../public/images/<?php echo $status_img;?>"></a>
                                <!---->
								<?php else:?>                                   

                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?>pahro_id=<?php echo $all_pahro_users[$i]['id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><?php echo $eachMenu['menu_img'];?></a>                    
                                
                                <?php endif;?>
                            
                            <?php else:?>
                                
                                <div style="height:10px; width:14px; float:left;">&nbsp;</div>	    
                                
                                <?php if ($eachMenu['menu_text'] != "Drop"):?>
                                
                                    <a title="<?php echo $eachMenu['menu_text'];?>" 
                                       href="<?php echo $eachMenu['menu_url'];?>pahro_id=<?php echo $all_pahro_users[$i]['id'].
                                       (isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><?php echo $eachMenu['menu_img'];?></a>                    
                                       
                                <?php endif;?>

                            <?php endif;?>
                            
                        <?php endforeach;?>

                        </div>
                        
                    </td><?php endif;?>
                    <td width="200"><span title="<?php echo $all_pahro_users[$i]['first_name'] . " " . $all_pahro_users[$i]['last_name'];?>" class="defaultFont"><?php echo $all_pahro_users[$i]['first_name'];?></span>&nbsp;<span class="defaultFont"><?php echo $all_pahro_users[$i]['last_name'];?></span></td>
                    <td width="100"><span title="<?php echo $all_pahro_users[$i]['user_type'];?>" class="defaultFont"><?php echo $all_pahro_users[$i]['user_type'];?></span></td>                           
                    <td width="150"><span title="<?php echo $all_pahro_users[$i]['username'];?>" class="defaultFont"><?php echo $all_pahro_users[$i]['username'];?></span></td>
                    <td width="150"><span title="<?php echo $all_pahro_users[$i]['email'];?>" class="defaultFont"><?php echo ($all_pahro_users[$i]['email'] != "") ? $all_pahro_users[$i]['email'] : "--";?></span></td>
                    <td width="150"><span title="<?php echo $all_pahro_users[$i]['created_at'];?>" class="defaultFont"><?php echo AppViewer::format_date($all_pahro_users[$i]['created_at']);?></span></td>
                    <td width="150"><span title="<?php echo $all_pahro_users[$i]['last_login'];?>" class="defaultFont"><?php echo ($all_pahro_users[$i]['last_login'] != "0000-00-00 00:00:00") ? $all_pahro_users[$i]['last_login'] : "Not Logged";?></span></td>                                                            
                </tr>
                <?php endfor;?>
            </table>
            <?php else:?>  
	            <div class="someWidth6 floatLeft"><span class="defaultFont boldText specializedTexts">No Records !</span></div>
               <!-- Users filtering part -->
                <form id="pahro_country_submit_form" name="pahro_country_submit_form" action="" method="post">            
                <div id="d-filter" class="a-left">
                    <div id="d-filter-l" class="defaultFont specializedTexts boldText"><!-- --></div>                       
                    <div id="d-filter-r" class="defaultFont">
                        <?php echo CommonFunctions::get_all_countries("filter_by_countries_required", $inline=true, "filtering", $_SESSION['filter_by_countries_required']); ?><input name="countries_filter" type="submit" value="Filter" class="inputs submit btn-filter" />
                        <input name="countries_filter_reset" type="submit" value="Reset" class="inputs submit btn-filter" />                                    
                    </div>
                </div>            
                </form>
                <br />
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
            <div class="paginationContainer">
				<?php echo $pagination;?>
            </div>	        					                                                
        </div>        
<a href="#" onclick="javascript:delete_selected_users();" class="singleLink defaultFont">Delete Selected records</a>        
    </div>    
     <?php endif;?>
<?php endif;?>    	 
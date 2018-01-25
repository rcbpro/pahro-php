<script type="text/javascript">
function prepareInputsForHints() {
	var inputs = document.getElementsByClassName("inputs");
	for (var i=0; i<inputs.length; i++){
		// test to see if the hint span exists first
		if (inputs[i].parentNode.getElementsByTagName("span")[0]) {
			// the span exists!  on focus, show the hint
			inputs[i].onmouseover = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
			}
			// when the cursor moves away from the field, hide the hint
			inputs[i].onmouseout = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "none";
			}
		}
	}
}
addLoadEvent(prepareInputsForHints);
</script>
<?php if ($_GET['mode'] != "notes"):?>    
		<span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><span class="defaultFont requiredFieldsIndicator smallFont">Required fields</span><br /><br />    
        <form id="pahro_form" name="pahro_form" method="post" action="">
        <div align="center">
            <table cellpadding="0" cellspacing="0" border="0">
            	<tr valign="top">
                	<td width="336">
                        <div class="add-user-l">
                            <table border="0" cellpadding="0" cellspacing="0" class="pfac_fields_table">
                                <tr>
                                    <td><span class="defaultFont">User Type</span></td>
                                    <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
                                    <td>
                                   	<?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?>
                                    <div id="d-case-status2">                                                                        
	                                    <?php for($i=0; $i<count($user_types[0]); $i++):?>
                                        <label><input class="c-status" type="radio" id="user_type" name="pahro_reqired[user_type]" onclick="change_per(this.value);" 
                                        <?php 
                                            $selected = "";
                                            if ($user_types[$i]['user_type_id'] == $_POST['pahro_reqired']['user_type']){
                                                echo "checked='checked'";
                                            }else{
                                                echo "";																
                                            }
                                        ?>
                                        value="<?php echo $user_types[$i]['user_type_id'];?>" />&nbsp;<span class="defaultFont"><?php echo $user_types[$i]['user_type'];?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    	<?php endfor;?>
                                    </div>
                                    <?php else:?>
                                    	<span class="specializedTexts defaultFont boldText"><?php echo $full_details[0]['user_type'];?></span>
                                    <?php endif;?>
                                    </td>
                                </tr>
                                <!-- Displaying the viewing user's country -->  
                                <?php if (strstr($_SERVER['REQUEST_URI'], "edit")):?>
                                <tr>
                                    <td><span class="defaultFont">Owned countries</span></td>
                                    <td><div class="smallHeight"><!-- --></div></td>                
                                    <td><?php foreach($user_countires_names as $each_coun_name):?>
										<span class="specializedTexts defaultFont boldText">                                    
                                        <?php echo $each_coun_name;?>                                        
                                        </span><br />                                                
                                        <?php endforeach;?>
                                    </td>
                                </tr>
                                <?php endif;?>
			                   	<tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>
                                <!-- Displaying the viewing user's country -->                                
                                <?php if (!$need_to_hide_select_menu):?>                      
                                <tr>
                                    <td><span class="defaultFont">Countries</span></td>
                                    <td><?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></td>                
                                    <td>
                                        <div class="<?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("country", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>">
                                            <select multiple="multiple" id="country" class="longSelectBox_multi_select" name="<?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?>pahro_reqired_country[]<?php else:?>pahro_non_reqired_country[]<?php endif;?>">

                                                <?php foreach($all_countries as $each_country):?>                                                
                                                <?php 
                                                    if ($each_country['country_id'] == $_SESSION['pahro_reqired_country']){
                                                        $selected = "selected = 'selected'";
                        							}elseif (PahroUserModel::is_currently_owned_in_country(trim($_GET['pahro_id']), $each_country['country_id'])){													
                                                        $selected = "selected = 'selected'";								
                                                    }else{
                                                        $selected = "";																
                                                    }
                                                ?>
                                                    <option value="<?php echo $each_country['country_id'];?>"<?php echo $selected?>><?php echo $each_country['country_name'];?></option>
                                                <?php endforeach;?>                                                
                                            </select>
                                        </div>
                                        <?php if (count($all_countries) > 1){
												echo "<span class='defaultFont specializedTexts'>".$for_multiple_users_text."</span>";												
										}?>
                                        <span class='defaultFont specializedTexts'><br />Note : Once you add a country you cannot undone.</span>
                                    </td>
                                </tr>
                      			<tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                        
                                <?php endif;?>                                                                
                                <tr>
                                    <td><span class="defaultFont">First Name</span></td>
                                    <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>
                                    <td>
                                        <input type="text" name="pahro_reqired[first_name]" 
                                        value="<?php 
                                                    if (isset($_SESSION['pahro_reqired'])){
                                                        $first_name = $_SESSION['pahro_reqired']['first_name'];
                                                    }elseif (isset($full_details)){
                                                        $first_name = $full_details[0]['first_name'];										
                                                    }else{
                                                        $first_name = "";																				
                                                    }
                                                    echo ucfirst(trim($first_name));
                                                ?>" 
                                        class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("first_name", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>            
                                <tr>
                                    <td><span class="defaultFont">Last Name</span></td>
                                    <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                                    <td>
                                        <input type="text" name="pahro_reqired[last_name]" 
                                        value="<?php 
                                                    if (isset($_SESSION['pahro_reqired'])){
                                                        $last_name = $_SESSION['pahro_reqired']['last_name'];
                                                    }elseif (isset($full_details)){
                                                        $last_name = $full_details[0]['last_name'];										
                                                    }else{
                                                        $last_name = "";																				
                                                    }
                                                    echo ucfirst(trim($last_name));
                                                ?>" 
                                        class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("last_name", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>                            
                                </tr>
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                        
                                <tr>
                                    <td><span class="defaultFont">Username</span></td>
                                    <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
                                    <td><?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?><input type="text" name="pahro_reqired[username]" 
                                                value="<?php 
                                                            if (isset($_SESSION['pahro_reqired'])){
                                                                $username = $_SESSION['pahro_reqired']['username'];
                                                            }elseif (isset($full_details)){
                                                                $username = $full_details[0]['username'];										
                                                            }else{
                                                                $username = "";																				
                                                            }
                                                            echo trim($username);						
                                                        ?>" 
                                                class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("username", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" />
                                        <?php else:?>
                                            <span class="defaultFont boldText specializedTexts"><?php echo $full_details[0]['username'];?></span>
                                        <?php endif;?></td>                            
                                </tr>
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                                    
                                <tr>
                                    <td><span class="defaultFont"><?php echo (strstr($_SERVER['REQUEST_URI'], "edit/")) ? "Current " : ""; ?>Password</span></td>
                                    <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit/")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
                                    <td><input type="password" name="<?php echo (strstr($_SERVER['REQUEST_URI'], "edit/")) ? "pahro_not_reqired_spec": "pahro_reqired";?>[password]" value="" 
                                                    class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("password", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" />
									<?php if (strstr($_SERVER['REQUEST_URI'], "edit/")):?>                                                    
									<span class="hint" style="right:380px !important;">Provide your current Password.<span class="hint-pointer">&nbsp;</span></span>
                                    <?php else:?>
									<span class="hint" style="right:380px !important;">Password must be at least six characters.<span class="hint-pointer">&nbsp;</span></span>                                    
                                    <?php endif;?></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                                    
                                <?php if (strstr($_SERVER['REQUEST_URI'], "edit/")):?>
                                <tr>
                                    <td><span class="defaultFont">New Password</span></td>
                                    <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit/")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
                                    <td><input type="password" name="<?php echo (strstr($_SERVER['REQUEST_URI'], "edit/")) ? "pahro_not_reqired_spec": "pahro_reqired";?>[new_password]" value="" 
                                                            class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("new_password", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" />
									<span class="hint" style="right:380px !important;">Password must be at least six characters.<span class="hint-pointer">&nbsp;</span></span>
                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>
                                </tr>                                    
                                <?php endif;?>
                                <tr>
                                    <td><span class="defaultFont">Confirm Password</span></td>
                                    <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit/")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
                                    <td><input type="password" name="<?php echo (strstr($_SERVER['REQUEST_URI'], "edit/")) ? "pahro_not_reqired_spec": "pahro_reqired";?>[confirm_password]" value="" 
                                                            class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("confirm_password", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" />
									<span class="hint" style="right:380px !important;">Confirmation Password must be equal to password previously entered.<span class="hint-pointer">&nbsp;</span></span>                                                            
                                                            </td>
                                </tr>                        
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                                                
                                <tr>
                                    <td><span class="defaultFont">Email</span></td>
                                    <td><div class="smallHeight"><!-- --></div></td>                
                                    <td><input type="text" name="pahro_not_reqired[email]" 
                                                value="<?php 
                                                            if (isset($_SESSION['pahro_not_reqired'])){
                                                                $email = $_SESSION['pahro_not_reqired']['email'];
                                                            }elseif (isset($full_details)){
                                                                $email = $full_details[0]['email'];										
                                                            }else{
                                                                $email = "";																				
                                                            }
                                                            echo trim($email);						
                                                        ?>" 
                                                class="inputs" /></td>
                                </tr>
                                <tr><td colspan="3"><div class="smallHeight"><!-- --></div></td></tr>
                                <tr>
                                    <td><?php if (strstr($_SERVER['REQUEST_URI'], "edit")):?><span class="defaultFont"><a href="?pahro_id=<?php echo $_GET['pahro_id'];?>&action=reset<?php echo ((isset($_GET['page'])) ? "&page=".$_GET['page'] : "&page=1");?>">Reset Password</a></span><br /><span class="specializedTexts smallFont">Tempory Password will be<br />created as (<?php echo $_SESSION['rand_pass'];?>)</span><?php endif;?></td>
                                    <td><!-- --></td>
                                    <td><div align="center"><input type="submit" name="pahro_submit" value="Save" class="inputs submit" /></div></td>
                                </tr>
                            </table>

                        </div>
                    </td>
                    <td width="300" valign="top">
					<?php if (strstr($_SERVER['REQUEST_URI'], "edit")):?>
                        <div id="user-group-permissions-display-edit">
                        <?php if ($user_type == 1):?>                        
                            <?php if ("POST" == $_SERVER['REQUEST_METHOD']):?>                                                    
                            <fieldset id="permissions-staff" style="<?php echo (isset($_SESSION['pahro_user_staff_permission_reqired'])) ? "display:block" : "";?>">
                            <legend><span class="defaultFont"><strong>Staff Permissions</strong></span></legend>                            
								
                                <?php echo $staff_permissions_after_html;?>

                             </fieldset>   
                            <?php else:?>
                            <fieldset id="permissions-staff" style="<?php echo ($user_type == 1) ? "display:block" : "";?>">
                            <legend><span class="defaultFont"><strong>Staff Permissions</strong></span></legend>                                                        
                            
                            	<?php echo $staff_permissions_before_html;?>
                            
                             </fieldset>   
                            <?php endif;?>
        <!--                <a href="#" name='checkall' onclick='checkedAll(true,14,"staff");'><span class="defaultFont">Check</span></a> | <a href="#" name='uncheckall' onclick='checkedAll(false,14,"staff");'><span class="defaultFont">Uncheck</span></a>&nbsp;                -->
                            </fieldset>
                        <?php endif;?>                
                        <?php if ($user_type == 2):?>
                            <?php if ("POST" == $_SERVER['REQUEST_METHOD']):?>                            
                            <fieldset id="permissions-vol" style="<?php echo (isset($_SESSION['pahro_user_vols_permission_reqired'])) ? "display:block" : "";?>">                            
                            <legend><span class="defaultFont"><strong>Volunteer Permissions</strong></span></legend>

								<?php echo $vols_permissions_after_html;?>
                                
                             </fieldset>  
                            <?php else:?>
                             <fieldset id="permissions-vol">                            
                             <legend><span class="defaultFont"><strong>Volunteer Permissions</strong></span></legend>
                             
                             	<?php echo $vols_permissions_before_html;?>
                             
                             </fieldset>                                                    
                            <?php endif;?>
                        <?php endif;?>
        <!--                <a href="#" name='checkall' onclick='checkedAll(true,14,"staff");'><span class="defaultFont">Check</span></a> | <a href="#" name='uncheckall' onclick='checkedAll(false,14,"staff");'><span class="defaultFont">Uncheck</span></a>&nbsp;                -->
                        </div>
                        <br />
                        <?php if (count($owned_permissions_ids) > 0):?>
                        <div id="user-group-permissions-display-edit">
                            <fieldset>
                            <legend><span class="defaultFont"><strong>Your Current Permissions</strong></span></legend>
                            <?php for($i=0; $i<count($permission_names); $i++):?>
                                <span class="defaultFont"><?php echo $permission_names[$i];?></span> |
                            <?php endfor;?>
                            </fieldset>
                        </div>
                        <?php endif;?>
					<?php else:?>
                        <div id="user-group-permissions-display-add">
                            <?php if ("POST" == $_SERVER['REQUEST_METHOD']):?> 
                            	<?php	
                            		$staff_display ='';
                                	$vol_display = '';   
									if ($_POST['pahro_reqired']['user_type'] == 1){
										$staff_display ='display:block';
	                                	$vol_display = ''; 
									}
									else{
										$staff_display ='';
	                                	$vol_display = 'display:block'; 
									}
								
								?>
                                
                            	<fieldset id="permissions-staff" style="<?php echo $staff_display;?>">
                                    <legend><span class="defaultFont"><strong>Staff Permissions</strong></span></legend>
                                    <?php echo $staff_permissions_after_html;?>
                            	 </fieldset>
                                 
								<fieldset id="permissions-vol" style="<?php echo $vol_display;?>">                                
                                    <legend><span class="defaultFont"><strong>Volunteer Permissions</strong></span></legend>
										<?php echo $vols_permissions_after_html;?>
                            	</fieldset>                                        

                                                                             	
                                 <?php //endif;?>       
                            <?php else:?>
                            
                            	<fieldset id="permissions-staff">                                                            
                                    <legend><span class="defaultFont"><strong>Staff Permissions</strong></span></legend>                            
                              			<?php echo $staff_permissions_before_html;?>  
								</fieldset>        
                                
                            <?php //endif;?>
            <!--                <a href="#" name='checkall' onclick='checkedAll(true,14,"staff");'><span class="defaultFont">Check</span></a> | <a href="#" name='uncheckall' onclick='checkedAll(false,14,"staff");'><span class="defaultFont">Uncheck</span></a>&nbsp;                -->
                            	<fieldset id="permissions-vol">                                
                                    <legend><span class="defaultFont"><strong>Volunteer Permissions</strong></span></legend>                                
                              			<?php echo $vols_permissions_before_html;?>                                  
                                 </fieldset>    
                                 
                            <?php endif;?>                    
                        </div>
                    <?php endif;?>            
                    </td>                    
                </tr>
            </table>
		</div>
        </form>    
		<?php elseif ($_GET['mode'] != "notes"):?>            
        	<?php include VIEW_PATH.'pahro/notes.php';?>
		<?php elseif ($_GET['mode'] != "permissions"):?>            
        	<?php include VIEW_PATH.'pahro/permissions.php';?>
        <?php endif;?>        
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
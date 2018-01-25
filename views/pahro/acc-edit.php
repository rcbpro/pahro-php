<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <!-- This is the success/error message to be diplayed -->
    <?php global $headerDivMsg; echo $headerDivMsg;?>
    <!-- End of the success or error message -->
    <div class="headerTopicContainer defaultFont boldText"><span class="headerTopicSelected">Account Settings</span></div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <form id="pahro_form" name="pahro_form" method="post" action="">
        <div align="center">
            <table cellpadding="0" cellspacing="0" border="0">
            	<tr valign="top">
                	<td>
                        <div class="add-user-l">
                            <table border="0" cellpadding="0" cellspacing="0" class="pfac_fields_table">
<?php /*?>                      <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                        
                                <tr>
                                    <td><span class="defaultFont">Country</span></td>
                                    <td><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></td>                
                                    <td>
                                        <div class="<?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("country", $_SESSION['pahro_reqired_errors']))) ? "longSelectBoxWrapDiv errorsIndicatedFields" : "longSelectBoxWrapDiv"; ?>">
                                            <select id="country" class="longSelectBox" name="pahro_reqired[country]">
                                                <option value=""> --------------- select --------------- </option>
                                                <?php for($i=0; $i<count($all_countries[0]); $i++):?>
                                                <?php 
                                                    if ($all_countries[$i]['country_id'] == $_SESSION['pahro_reqired']['country']){
                                                        $selected = "selected = 'selected'";
                                                    }elseif ((isset($full_details)) && ($full_details[0]['country_id'] == $all_countries[$i]['country_id'] )){
                                                        $selected = "selected = 'selected'";								
                                                    }else{
                                                        $selected = "";																
                                                    }
                                                ?>
                                                <option value="<?php echo $all_countries[$i]['country_id'];?>"<?php echo $selected?>><?php echo $all_countries[$i]['country_name'];?></option>
                                                <?php endfor;?>                                                
                                            </select>
                                        </div>
                                    </td>
                                </tr>
<?php */?>                      <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                        
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
                                                    class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("password", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                                </tr>                                    
                                <?php if (strstr($_SERVER['REQUEST_URI'], "edit/")):?>
                                <tr>
                                    <td><span class="defaultFont">New Password</span></td>
                                    <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit/")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
                                    <td><input type="password" name="<?php echo (strstr($_SERVER['REQUEST_URI'], "edit/")) ? "pahro_not_reqired_spec": "pahro_reqired";?>[new_password]" value="" 
                                                            class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("new_password", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><div class="smallHeight"><!-- --></div></td>
                                </tr>                                    
                                <?php endif;?>
                                <tr>
                                    <td><span class="defaultFont">Confirm Password</span></td>
                                    <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit/")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
                                    <td><input type="password" name="<?php echo (strstr($_SERVER['REQUEST_URI'], "edit/")) ? "pahro_not_reqired_spec": "pahro_reqired";?>[confirm_password]" value="" 
                                                            class="inputs <?php echo ((isset($_SESSION['pahro_reqired_errors'])) && (array_key_exists("confirm_password", $_SESSION['pahro_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
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
                </tr>
            </table>
		</div>
        </form>    
    </div>
</div>
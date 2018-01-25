<script type="text/javascript" src="<?php echo $site_config['base_url'];?>public/js/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $site_config['base_url'];?>public/js/jquery.uploadify.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var max_file_size = 419430400;
	$("#file-counter-party").uploadify({
		'uploader'       : '<?php echo WEB_URL;?>/lib/uploader/uploadify.swf',
		'script'         : '<?php echo WEB_URL;?>/controllers/uploader.php',
		'scriptData'	 : {'msid':'<?php echo session_id();?>','type':'counter-party'},
		'cancelImg'      : '../../../public/images/cancel.png',
		'folder'         : '/user-uploads/temp',
		'queueID'        : 'queue-counter-party',
//		'fileExt'		 : '*.jpg;*.gif;*.png;',
//		'fileDesc'		 : 'Allowed file types : *.jpg, *.gif, *.png',
		'wmode'      	 : 'transparent',
		'buttonImg'		 : '../../../public/images/attachments.png',
		'width'			 : '68','height' : '12',
		'auto'           : 1,
		'multi'          : 1,
		'sizeLimit'		 : max_file_size,
		'onSelect'		 : function(event,queueID,fileObj) {
		
						   },
		'onError'		 : function(event,queueID,fileObj,errorObj){								
								if (errorObj['info'] == max_photo_size){
									$("#queue-counter-party").html("");
									$("#file-counter-party").uploadifyClearQueue();
									var f_name = fileObj['name'];
									var f_size = ((fileObj['size'])/(1024*1024)).toFixed(2);
									alert("Upload error... Maximum size is " + (max_photo_size/(1024*1024)) + "MB.\n\"" + f_name + "\" ("+f_size+"MB)");
								}	
						   },
		'onComplete' 	 : function(){
								get_counter_party();
						   }  
						   
	});
});


function get_counter_party(){
	$.ajax({
		type: "POST",
		data: "get-counter-party=true",
		url: "../../../lib/uploader/uploader-proccess.php",
		success: function(resdata){
			$("#uploaded-counter-party-wrap").append(resdata);
		},
		complete: function(){
			$("#uploaded-counter-party-wrap div:last-child").fadeIn(1000);
		}
	});
}

function remove_counter_party(val){
	$("#d"+val).fadeOut("fast");
	$.ajax({
		type: "POST",
		data: "remove-counter-party=true&id="+val,
		url: "../../../lib/uploader/uploader-proccess.php",
		success: function(resdata){
			
		}
	});
}

function remove_counter_party_edit(fid,cid){
	if(confirm("This will remove permenently.\nAre you sure?")){	
		$.ajax({
			type: "POST",
			data: "remove-counter-party-edit=true&fid="+fid+"&cid="+cid,
			url: "../../../lib/uploader/uploader-proccess.php",
			success: function(resdata){
				$("#d"+fid).fadeOut("fast");
			}
		});

	}
}
</script>
<div align="center">
<form name="counter_main_form" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td>
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td width="200"><span class="defaultFont">Company Name (If applicable)</span></td>
                        <td><div class="smallHeight"><!-- --></div></td>                
                        <td width="306">
                            <input type="text" name="counter_main_not_reqired[company_name]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_not_reqired']))){
                                                    $company_name = $_SESSION['counter_main_not_reqired']['company_name'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $company_name = (isset($_SESSION['counter_main_not_reqired'])) ? $_SESSION['counter_main_not_reqired']['company_name'] : $full_details[0]['company_name'];										
                                                }else{
                                                    $company_name = "";																				
                                                }
                                                echo trim($company_name);
                                            ?>" 
                                    class="inputs" /></td>
                    </tr>
					<?php //if ((count($_SESSION['logged_user']['countries']) > 1) && (!strstr($_SERVER['REQUEST_URI'], "edit"))):?>
                    <!--<tr>
                        <td colspan="3"><div class="smallHeight"></div></td>                
                    </tr>                        
                    <tr>
                        <td><span class="defaultFont">Counter Party Owned Country</span></td>
                        <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                        <td>
                            <div class="floatLeft <?php //echo ((isset($_SESSION['counter_reqired_errors'])) && (array_key_exists("cp_owned_country_id", $_SESSION['counter_reqired_errors']))) ? "longSelectBoxWrapDiv errorsIndicatedFields" : "longSelectBoxWrapDiv"; ?>">
                            <select class="longSelectBox" name="counter_main_reqired[cp_owned_country_id]">
                                <option value=""> --------------- select --------------- </option>
                                <?php //foreach($all_countries as $each_country):?>
                                <?php 
                                    /*$selected = "";
                                    if ($each_country['country_id'] == $_SESSION['counter_main_reqired']['cp_owned_country_id']){
                                        $selected = "selected = 'selected'";
                                    }elseif ((isset($full_details)) && ($each_country['country_id'] == $full_details[0]['cp_owned_country_id'])){
                                        $selected = "selected = 'selected'";								
                                    }else{
                                        $selected = "";																
                                    }*/
                                ?>
                                <option value="<?php //echo $each_country['country_id'];?>" <?php //echo $selected;?>><?php //echo $each_country['country_name'];?></option>                                                               
                                <?php //endforeach;?>     
                            </select>
                            </div>
                        </td>
                    </tr>-->
                    <?php //endif;?>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>                        
                    <tr>
                        <td><span class="defaultFont">Title</span></td>
                        <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                        <td>
                            <div class="floatLeft <?php echo ((isset($_SESSION['counter_reqired_errors'])) && (@array_key_exists("title", $_SESSION['counter_reqired_errors']))) ? "smallDropDownMenuWrapDiv errorsIndicatedFields" : "smallDropDownMenuWrapDiv"; ?>">
                            <select class="smallDropDownMenu" name="counter_main_reqired[title]">
                                <option value="">- select -&nbsp;</option>
                                <?php foreach($allTitles as $title):?>
                                <?php 
                                    $selected = "";
                                    if ($title == $_SESSION['counter_main_reqired']['title']){
                                        $selected = "selected = 'selected'";
                                    }elseif ((isset($full_details)) && ($title == $full_details[0]['title'])){
                                        $selected = "selected = 'selected'";								
                                    }else{
                                        $selected = "";																
                                    }
                                ?>
                                <option value="<?php echo $title;?>" <?php echo $selected;?>><?php echo $title;?></option>                                                               
                                <?php endforeach;?>     
                            </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td width="200"><span class="defaultFont">First Name</span></td>
                        <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                        <td width="306">
                            <input type="text" name="counter_main_reqired[first_name]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_reqired']))){
                                                    $first_name = $_SESSION['counter_main_reqired']['first_name'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $first_name = (isset($_SESSION['counter_main_reqired'])) ? $_SESSION['counter_main_reqired']['first_name'] : $full_details[0]['first_name'];										
                                                }else{
                                                    $first_name = "";																				
                                                }
                                                echo trim($first_name);
                                            ?>" 
                                    class="inputs <?php echo ((isset($_SESSION['counter_reqired_errors'])) && (@array_key_exists("first_name", $_SESSION['counter_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td><span class="defaultFont">Last Name</span></td>
                        <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                        <td>
                            <input type="text" name="counter_main_reqired[last_name]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_reqired']))){
                                                    $last_name = $_SESSION['counter_main_reqired']['last_name'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $last_name = (isset($_SESSION['counter_main_reqired'])) ? $_SESSION['counter_main_reqired']['last_name'] : $full_details[0]['last_name'];																						
                                                }else{
                                                    $last_name = "";																				
                                                }
                                                echo trim($last_name);
                                            ?>" 
                                    class="inputs <?php echo ((isset($_SESSION['counter_reqired_errors'])) && (@array_key_exists("last_name", $_SESSION['counter_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td><span class="defaultFont">Resident Address</span></td>
                        <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                        <td>
                            <input type="text" name="counter_main_reqired[resident_address]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_reqired']))){
                                                    $resident_address = $_SESSION['counter_main_reqired']['resident_address'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $resident_address = (isset($_SESSION['counter_main_reqired'])) ? $_SESSION['counter_main_reqired']['resident_address'] : $full_details[0]['resident_address'];																																			
                                                }else{
                                                    $resident_address = "";																				
                                                }
                                                echo trim($resident_address);
                                            ?>" 
                                    class="inputs <?php echo ((isset($_SESSION['counter_reqired_errors'])) && (@array_key_exists("resident_address", $_SESSION['counter_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td><span class="defaultFont">Postal Address</span></td>
                        <td><div class="smallHeight"><!-- --></div></td>                
                        <td>
                            <input type="text" name="counter_main_not_reqired[postal_address]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_not_reqired']))){
                                                    $postal_address = $_SESSION['counter_main_not_reqired']['postal_address'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $postal_address = (isset($_SESSION['counter_main_not_reqired'])) ? $_SESSION['counter_main_not_reqired']['postal_address'] : $full_details[0]['postal_address'];																																															
                                                }else{
                                                    $postal_address = "";																				
                                                }
                                                echo trim($postal_address);
                                            ?>" 
                                    class="inputs" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td><span class="defaultFont">Land Phone No :</span></td>
                        <td><div class="smallHeight"><!-- --></div></td>                
                        <td>
                            <input type="text" name="counter_main_not_reqired[land_phone]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_not_reqired']))){
                                                    $land_phone = $_SESSION['counter_main_not_reqired']['land_phone'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $land_phone = (isset($_SESSION['counter_main_not_reqired'])) ? $_SESSION['counter_main_not_reqired']['land_phone'] : $full_details[0]['land_phone'];																																															
                                                }else{
                                                    $land_phone = "";																				
                                                }
                                                echo trim($land_phone);
                                            ?>" 
                                    class="inputs" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td><span class="defaultFont">Mobile Phone No :</span></td>
                        <td><div class="smallHeight"><!-- --></div></td>                
                        <td>
                            <input type="text" name="counter_main_not_reqired[mobile_phone]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_not_reqired']))){
                                                    $mobile_phone = $_SESSION['counter_main_not_reqired']['mobile_phone'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $mobile_phone = (isset($_SESSION['counter_main_not_reqired'])) ? $_SESSION['counter_main_not_reqired']['mobile_phone'] : $full_details[0]['mobile_phone'];																																																												
                                                }else{
                                                    $mobile_phone = "";																				
                                                }
                                                echo trim($mobile_phone);
                                            ?>" 
                                    class="inputs" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>            
                    <tr>
                        <td><span class="defaultFont">Email</span></td>
                        <td><div class="smallHeight"><!-- --></div></td>                
                        <td>
                            <input type="text" name="counter_main_not_reqired[email]" 
                                    value="<?php 
                                                if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_not_reqired']))){
                                                    $email = $_SESSION['counter_main_not_reqired']['email'];
                                                }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
                                                    $email = (isset($_SESSION['counter_main_not_reqired'])) ? $_SESSION['counter_main_not_reqired']['email'] : $full_details[0]['email'];																																																																																																
                                                }else{
                                                    $email = "";																				
                                                }
                                                echo trim($email);
                                            ?>" 
                                    class="inputs" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr> 
                    
                    <tr>
                        <td valign="top"><span class="defaultFont">Please enter the Case IDs : </span></td>
                        <td><div class="smallHeight"><!-- --></div></td>
                        <td><textarea id-"clientIds" rows="6" name="counter_main_not_reqired[case_ids]"><?php 
                                                                    if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_not_reqired']))){
                                                                        $case_ids_printed = $_SESSION['counter_main_not_reqired']['case_ids'];
                                                                    }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (!isset($_SESSION['counter_main_not_reqired']))){
                                                                        foreach($case_ids as $eachId){
                                                                            $case_ids_printed .= $eachId.", ";
                                                                        }
                                                                    }elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($_SESSION['counter_main_not_reqired']['case_ids']))){
                                                                        $case_ids_printed = $_SESSION['counter_main_not_reqired']['case_ids'];
                                                                    }else{
                                                                        $case_ids_printed = "";																				
                                                                    }
																	$case_ids_printed = trim($case_ids_printed);
																	if (substr($case_ids_printed, -1) == ','){
																	  $case_ids_printed = substr($case_ids_printed, 0, -1);					
																	} 																	
                                                                    echo $case_ids_printed;
                                                                ?></textarea>
						</td>
                    </tr>
                    
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr> 
                               
                    <tr>
                        <td valign="top"><span class="defaultFont">Extra Comment</span></td>
                        <td><div class="smallHeight"><!-- --></div></td>                
                        <td><textarea rows="6" name="counter_main_not_reqired[comment]"><?php 
                                                                    if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['counter_main_not_reqired']))){
                                                                        $comment = $_SESSION['counter_main_not_reqired']['comment'];
																	}elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
																		$comment = (isset($_SESSION['counter_main_not_reqired'])) ? $_SESSION['counter_main_not_reqired']['comment'] : $full_details[0]['comment'];																																																																																																
                                                                    }else{
                                                                        $comment = "";																				
                                                                    }
                                                                    echo trim($comment);
                                                                ?></textarea></td>
                    </tr> 
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>

                    <tr>
                        <td colspan="3">
                            <div id="d-file-counter-pary">
                                <fieldset id="upload-row-counter-party">
                                	<legend>&nbsp;Attachments&nbsp;</legend>
                                    <div id="queue-file-counter-party" title="Browse files for upload"><input type="file" name="file-counter-party" id="file-counter-party" class="hide-file" /></div>
                                    <div id="uploaded-area">
                                        <div id="queue-counter-party"></div>
                                    </div>
                                    <div id="uploaded-counter-party-wrap">
                                    <?php
                                        if(count($_SESSION['counter_party_main']['template_name']) > 0){	
                                            foreach($_SESSION['counter_party_main']['template_name'] as $file){
                                                $file_id = $file['id'];
                                                $file_name = $file['name'];									
                                                $remove = '&nbsp;<span id="'.$file_id.'" class="remove-counter-party-current" onclick="remove_counter_party(this.id)"> [remove] </span>';
                                                $ary = explode('_', $file_name, 2);
                                                $file_name_real = $ary[1];
                                                
                                                $case_id = $full_details[0]['counter_party_id'];
                                                $file_url = '<a title="Click to download" href="'.WEB_URL.'/pahro/download/?file='.$file_name.'&type=user-uploads&where=temp/">'.$file_name_real.'</a>';
                                                echo '<div id="d'.$file_id.'" class="uploaded-counter-party-display">'.$file_url.$remove.'</div>';
                                            }
                                        }
                                    ?>
                                    
                                    </div>
                                    
                                    <?php if(count($pre_counter_party_attachments) > 0){?>
                                        <div id="pre-counter-party-wrap">
                                        <fieldset>
                                            <legend>&nbsp;Your previous attachments&nbsp;</legend>
                                            <?php
                                            foreach($pre_counter_party_attachments as $file){
                                                $counter_party_id = $full_details[0]['counter_party_id'];
                                                $file_id = $file['id'];
                                                $file_name = $file['name'];
                                                $remove = '&nbsp;<span id="'.$file_id.'" class="remove-counter-party-current" onclick="remove_counter_party_edit(this.id,\''.$counter_party_id.'\');"> [remove] </span>';
                                                $ary = explode('_', $file_name, 2);
                                                $file_name_real = $ary[1];
                                                
                                                $file_url = '<a title="Click to download" href="'.WEB_URL.'/pahro/download/?file='.$file_name.'&type=user-uploads&where=counter-parties/'.$counter_party_id.'/">'.$file_name_real.'</a>';
                                                echo '<div id="d'.$file_id.'" class="uploaded-counter-party-display">'.$file_url.$remove.'</div>';
                                            }
                                            ?>
                                        </fieldset>
                                        </div>
                                    <?php }?>
                                    </fieldset>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>
                    
                    <tr>               
                        <td colspan="3"><div align="center"><span class="defaultFont"><input type="submit" name="counter_main_submit" value="Save and Proceed" class="inputs submit" /></span></div></td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>
</form>
</div>
<script type="text/javascript" src="<?php echo $site_config['base_url'];?>public/js/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $site_config['base_url'];?>public/js/jquery.uploadify.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var max_file_size = 419430400;
	$("#file-case").uploadify({
		'uploader'       : '<?php echo WEB_URL;?>/lib/uploader/uploadify.swf',
		'script'         : '<?php echo WEB_URL;?>/controllers/uploader.php',
		'scriptData'	 : {'msid':'<?php echo session_id();?>','type':'case'},
		'cancelImg'      : '../../../public/images/cancel.png',
		'folder'         : '/user-uploads/temp',
		'queueID'        : 'queue-case',
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
									$("#queue-case").html("");
									$("#file-case").uploadifyClearQueue();
									var f_name = fileObj['name'];
									var f_size = ((fileObj['size'])/(1024*1024)).toFixed(2);
									alert("Upload error... Maximum file size is " + (max_photo_size/(1024*1024)) + "MB.\n\"" + f_name + "\" ("+f_size+"MB)");
								}	
						   },
		'onComplete' 	 : function(){
								get_case();
						   }  
						   
	});
	
	$('#click-to').click(function() {
		$('#file-caseUploader').click();
	});
	
});
function get_case(){
	$.ajax({
		type: "POST",
		data: "get-case=true",
		url: "../../../lib/uploader/uploader-proccess.php",
		success: function(resdata){
			$("#uploaded-case-wrap").append(resdata);
		},
		complete: function(){
			$("#uploaded-case-wrap div:last-child").fadeIn(1000);
		}
	});
}
function remove_case(val){
	$("#d"+val).fadeOut("fast");
	$.ajax({
		type: "POST",
		data: "remove-case=true&id="+val,
		url: "../../../lib/uploader/uploader-proccess.php",
		success: function(resdata){
			
		}
	});
}
function remove_case_edit(fid,cid){
	if(confirm("This will remove permenently.\nAre you sure?")){	
		$.ajax({
			type: "POST",
			data: "remove-case-edit=true&fid="+fid+"&cid="+cid,
			url: "../../../lib/uploader/uploader-proccess.php",
			success: function(resdata){
				$("#d"+fid).fadeOut("fast");
			}
		});

	}
}
function enableDisableDiv(val){
	  //status = $("input[@name='case_status']:checked").val();
	  //alert(val);
	  if (val == 'Closed'){
			$("#d-case-close").slideDown("");
	  }else{
			$("#d-case-close").slideUp("");
	  }
 }
</script>
<div align="center">
<form name="case_main_form" method="post" action="">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="200"><span class="defaultFont">Reference Number</span></td>
            <td><div class="smallHeight"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><?php endif;?></div></td>                
            <td width="306"><?php if (!strstr($_SERVER['REQUEST_URI'], "edit")):?>
                <input type="text" name="case_main_reqired[reference_number]" 
                        value="<?php 
                                    if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['case_main_reqired']))){
                                        $reference_number = $_SESSION['case_main_reqired']['reference_number'];
                                    }else{
                                        $reference_number = "";																				
                                    }
                                    echo trim($reference_number);
                                ?>" 
                        class="inputs <?php echo ((isset($_SESSION['case_reqired_errors'])) && (array_key_exists("reference_number", $_SESSION['case_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" />
                        <?php else:?><span class="specializedTexts defaultFont boldText"><?php echo $full_details[0]['reference_number'];?></span><?php endif;?></td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr>            
        <tr>
            <td><span class="defaultFont">Case Name</span></td>
            <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
            <td>
                <input type="text" name="case_main_reqired[case_name]" 
                        value="<?php 
                                    if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['case_main_reqired']))){
                                        $case_name = $_SESSION['case_main_reqired']['case_name'];
									}elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
										$case_name = (isset($_SESSION['case_main_reqired'])) ? $_SESSION['case_main_reqired']['case_name'] : $full_details[0]['case_name'];																						
                                    }else{
                                        $case_name = "";																				
                                    }
                                    echo trim($case_name);
                                ?>" 
                        class="inputs <?php echo ((isset($_SESSION['case_reqired_errors'])) && (array_key_exists("case_name", $_SESSION['case_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
        </tr>
        <?php //if ((count($_SESSION['logged_user']['countries']) > 1) && (!strstr($_SERVER['REQUEST_URI'], "edit"))):?>        
        <!--
        <tr>
            <td colspan="3"><div class="smallHeight"></div></td>                
        </tr>                        
        <tr>
            <td><span class="defaultFont">Case Owned Country</span></td>
            <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
            <td>
                <div class="floatLeft <?php //echo ((isset($_SESSION['case_reqired_errors'])) && (array_key_exists("case_owned_country_id", $_SESSION['case_reqired_errors']))) ? "longSelectBoxWrapDiv errorsIndicatedFields" : "longSelectBoxWrapDiv"; ?>">
                <select class="longSelectBox" name="case_main_reqired[case_owned_country_id]">
                    <option value=""> --------------- select --------------- </option>
                    <?php //foreach($all_countries as $each_country):?>
                    <?php 
						/*
                        $selected = "";
                        if ($each_country['country_id'] == $_SESSION['case_main_reqired']['case_owned_country_id']){
                            $selected = "selected = 'selected'";
                        }elseif ((isset($full_details)) && ($each_country['country_id'] == $full_details[0]['case_owned_country_id'])){
                            $selected = "selected = 'selected'";								
                        }else{
                            $selected = "";																
                        }*/
                    ?>
                    <option value="<?php //echo $each_country['country_id'];?>" <?php echo $selected;?>><?php echo $each_country['country_name'];?></option>                                                               
                    <?php //endforeach;?>     
                </select>
                </div>
            </td>
        </tr>-->
		<?php //endif;?>
        <tr>
            <td colspan="3"><div class="smallHeight"></div></td>                
        </tr>                        
        <tr>
            <td><span class="defaultFont">Case Category</span></td>
            <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
            <td>
                <div class="floatLeft <?php echo ((isset($_SESSION['case_reqired_errors'])) && (array_key_exists("case_cat_id", $_SESSION['case_reqired_errors']))) ? "longSelectBoxWrapDiv errorsIndicatedFields" : "longSelectBoxWrapDiv"; ?>">
                <select class="longSelectBox" name="case_main_reqired[case_cat_id]">
                    <option value=""> --------------- select --------------- </option>
                    <?php foreach($case_cats as $category):?>
                    <?php 
                        $selected = "";
                        if ($category['case_cat_id'] == $_SESSION['case_main_reqired']['case_cat_id']){
                            $selected = "selected = 'selected'";
                        }elseif ((isset($full_details)) && ($category['case_cat_id'] == $full_details[0]['case_cat_id'])){
                            $selected = "selected = 'selected'";								
                        }else{
                            $selected = "";																
                        }
                    ?>
                    <option title="<?php echo $category['case_cat_description'];?>" value="<?php echo $category['case_cat_id'];?>" <?php echo $selected;?>><?php echo $category['case_cat_name'];?></option>                                                               
                    <?php endforeach;?>     
                </select>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr>                        
        <tr>
            <td><span class="defaultFont">Staff Responsible</span></td>
            <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
            <td>
                <div class="floatLeft <?php echo ((isset($_SESSION['case_reqired_errors'])) && (array_key_exists("staff_responsible", $_SESSION['case_reqired_errors']))) ? "longSelectBoxWrapDiv errorsIndicatedFields" : "longSelectBoxWrapDiv"; ?>">
                <select class="longSelectBox" name="case_main_reqired[staff_responsible]">
                    <option value=""> --------------- select --------------- </option>
                    <?php foreach($staff_names as $staff):?>
                    <?php 
                        $selected = "";
                        if ($staff['id'] == $_SESSION['case_main_reqired']['staff_responsible']){
                            $selected = "selected = 'selected'";
                        }elseif ((isset($full_details)) && ($staff['id'] == $full_details[0]['staff_responsible'])){
                            $selected = "selected = 'selected'";								
                        }else{
                            $selected = "";																
                        }
                    ?>
                    <option value="<?php echo $staff['id'];?>" <?php echo $selected;?>><?php echo $staff['username'];?></option>                                                               
                    <?php endforeach;?>                                                
                </select>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr>            
        <tr>
            <td><span class="defaultFont">Case Opened date</span></td>
            <td><div class="smallHeight"><!-- --></div></td>                
            <td>
				<?php 				
					if ((!empty($_SESSION['case_main_open_non_reqired']['day'])) || (!empty($_SESSION['case_main_open_non_reqired']['month'])) || (!empty($_SESSION['case_main_open_non_reqired']['year']))){
						$day = $_SESSION['case_main_open_non_reqired']['day'];
						$month = $_SESSION['case_main_open_non_reqired']['month'];
						$year = $_SESSION['case_main_open_non_reqired']['year'];
						$required_status = false;
					}elseif (isset($full_details)){
						$date_opend = explode("-", $full_details[0]['opend_date']);
						$day = $date_opend[2];
						$month = $date_opend[1];						
						$year = $date_opend[0];						
					}else{
						$day = $month = $year = "";
					}
                    echo CommonFunctions::print_date_selecting_drop_down("case_main_open_non_reqired", 
							$day, $month, $year, $submitStatus, @$_SESSION['date_input_error'], $_SERVER['REQUEST_URI'], $required_status);
                ?></td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr>                        
        <tr>
            <td><span class="defaultFont">Case Review Date</span></td>
            <td><div class="smallHeight"><!-- --></div></td>                
            <td>
				<?php 
					if ((!empty($_SESSION['case_main_upcoming_non_reqired']['day'])) || (!empty($_SESSION['case_main_upcoming_non_reqired']['month'])) || (!empty($_SESSION['case_main_upcoming_non_reqired']['year']))){
						$day = $_SESSION['case_main_upcoming_non_reqired']['day'];
						$month = $_SESSION['case_main_upcoming_non_reqired']['month'];
						$year = $_SESSION['case_main_upcoming_non_reqired']['year'];
						$required_status = false;
					}elseif (isset($full_details)){
						$date_upcoming = explode("-", $full_details[0]['upcoming_date']);
						$day = $date_upcoming[2];
						$month = $date_upcoming[1];						
						$year = $date_upcoming[0];						
					}else{
						$day = $month = $year = "";
					}
                    echo CommonFunctions::print_date_selecting_drop_down("case_main_upcoming_non_reqired", $day, $month, $year, 
								$submitStatus, @$_SESSION['date_input_error_2'], $_SERVER['REQUEST_URI'], $required_status);
                ?></td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr>
        <tr>
            <td><span class="defaultFont">Case Current Status</span></td>
            <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
            <td>
            <div id="d-case-status">
            <?php foreach($case_statuses as $status):?>
                <label><input class="c-status" onclick="enableDisableDiv(this.value)" type="radio" name="case_status"
				<?php 
                    $selected = "";
                    if ($status == $_SESSION['case_status']){
                        echo "checked='checked'";
                    }elseif ((isset($full_details)) && ($status == $full_details[0]['status'])){
                        echo "checked='checked'";								
                    }else{
                        echo "";																
                    }
                ?>
                value="<?php echo $status;?>" />&nbsp;<span class="defaultFont"><?php echo $status;?></span></label>
			<?php endforeach;?>
            </div>
            </td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr>        
        <tr>
        	<td colspan="3">
            <div id="d-case-close" style="<?php echo (($_SESSION['case_status'] == 'Closed') || ($full_details[0]['status'] == "Closed")) ? 'display:block' : '';?>">
            	<table width="531" border="0" cellpadding="0" cellspacing="0">

                    <tr>
                        <td width="200"><span class="defaultFont">Case Closed Date</span></td>
                        <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                        <td width="306">
                            <?php 
                                if ((!empty($_SESSION['case_main_close_non_reqired']['day'])) || (!empty($_SESSION['case_main_close_non_reqired']['month'])) || (!empty($_SESSION['case_main_close_non_reqired']['year']))){
                                    $day = $_SESSION['case_main_close_non_reqired']['day'];
                                    $month = $_SESSION['case_main_close_non_reqired']['month'];
                                    $year = $_SESSION['case_main_close_non_reqired']['year'];
                                    $required_status = false;
                                }elseif (isset($full_details)){
                                    $closedDate = explode("-", $full_details[0]['closed_date']);
                                    $day = $closedDate[2];
                                    $month = $closedDate[1];						
                                    $year = $closedDate[0];						
                                }else{
                                    $day = $month = $year = "";
                                }
                                echo CommonFunctions::print_date_selecting_drop_down("case_main_close_non_reqired", $day, $month, $year, 
                                            $submitStatus, @$_SESSION['date_input_error_3'], $_SERVER['REQUEST_URI'], $required_status);
                            ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr> 
                    <tr>
                        <td><span class="defaultFont">Reason for Case Closing</span></td>
                        <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
                        <td><textarea rows="6" name="case_main_non_reqired[reasone_for_close]"><?php 
                                                                    if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['case_main_non_reqired']))){
                                                                        $reasone_for_close = $_SESSION['case_main_non_reqired']['reasone_for_close'];
                                                                    }elseif (isset($full_details)){
                                                                        $reasone_for_close = $full_details[0]['reasone_for_close'];										
                                                                    }else{
                                                                        $reasone_for_close = "";																				
                                                                    }
                                                                    echo trim($reasone_for_close);
                                                                ?></textarea></td>
                    </tr> 
                    <tr>
                        <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
                    </tr>
                </table>
            </div>
            </td>
        </tr> 
        
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr> 
        <tr>
            <td><span class="defaultFont">Description</span></td>
            <td><div class="smallHeight"><!-- --></div></td>                
            <td><textarea rows="6" name="case_main_non_reqired[description]"><?php 
														if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['case_main_non_reqired']))){
															$description = $_SESSION['case_main_non_reqired']['description'];
														}elseif (isset($full_details)){
															$description = $full_details[0]['description'];										
														}else{
															$description = "";																				
														}
														echo trim($description);
													?></textarea></td>
        </tr> 
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr> 
        <tr>
            <td><span class="defaultFont">Extra Comment</span></td>
            <td><div class="smallHeight"><!-- --></div></td>                
            <td><textarea rows="6" name="case_main_non_reqired[comment]"><?php 
														if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['case_main_non_reqired']))){
															$comment = $_SESSION['case_main_non_reqired']['comment'];
														}elseif (isset($full_details)){
															$comment = $full_details[0]['comment'];										
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
				<?php /*?><td><span class="defaultFont">Volunteers currently involved</span></td>
                <td><div class="smallHeight"><!-- --></div></td>                
                <td>
                    <select multiple="multiple" class="longSelectBox_multi_select" name="case_main_non_reqired[vols_working][]">
                        <?php foreach($refined_volunteers as $vols):?>
                        <?php 						
                            $selected = "";
                            if ($vols['id'] == $_SESSION['case_main_non_reqired']['vols_working'][$i]){
                                $selected = "selected = 'selected'";
                            }elseif (CaseModel::is_current_case_handlling_volunteer_for_this_case(trim(urlencode($_GET['ref_no'])), $vols['id'])){
                                $selected = "selected = 'selected'";								
                            }else{
                                $selected = "";																
                            }
                        ?>
                        <option value="<?php echo $vols['id'];?>" <?php echo $selected;?>><?php echo $vols['username'];?></option>                                                                                   
                        <?php endforeach;?>                                                
                    </select>
                </td><?php */?>
                
              <?php include(SERVER_ROOT.'/views/case/mode/filtering.php');?>
		  	</td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr>        
        <tr>
        	<td colspan="3">
            	<div id="d-file-case">
                	<fieldset id="upload-row-case">
                    	<legend>&nbsp;Attachments&nbsp;</legend>
                        <div id="queue-file-case" title="Browse files for upload"><input type="file" name="file-case" id="file-case" class="hide-file" /></div>
                        <div id="uploaded-area">
                            <div id="queue-case"></div>
                        </div>
                        <div id="uploaded-case-wrap">
                        <?php
							if(count($_SESSION['case_main']['template_name']) > 0){	
								foreach($_SESSION['case_main']['template_name'] as $file){
									$file_id = $file['id'];
									$file_name = $file['name'];									
									$remove = '&nbsp;<span id="'.$file_id.'" class="remove-case-current" onclick="remove_case(this.id)"> [remove] </span>';									
									$ary = explode('_', $file_name, 2);
									$file_name_real = $ary[1];
									
									$case_id = $full_details[0]['reference_number'];
									$file_url = '<a title="Click to download" href="'.WEB_URL.'/pahro/download/?file='.$file_name.'&type=user-uploads&where=temp/">'.$file_name_real.'</a>';
									echo '<div id="d'.$file_id.'" class="uploaded-case-display">'.$file_url.$remove.'</div>';
								}
							}
						?>
                        
	                    </div>
						
                        <?php if(count($pre_case_templates) > 0){?>
                            <div id="pre-case-wrap">
                            <fieldset>
                            	<legend>&nbsp;Your previous attachments&nbsp;</legend>
                                <?php
								foreach($pre_case_templates as $file){
									$case_id = $full_details[0]['reference_number'];
									$file_id = $file['id'];
									$file_name = $file['name'];
									$remove = '&nbsp;<span id="'.$file_id.'" class="remove-case-current" onclick="remove_case_edit(this.id,\''.$case_id.'\');"> [remove] </span>';
									$ary = explode('_', $file_name, 2);
									$file_name_real = $ary[1];
									
									$file_url = '<a title="Click to download" href="'.WEB_URL.'/pahro/download/?file='.$file_name.'&type=user-uploads&where=cases/'.$case_id.'/">'.$file_name_real.'</a>';
									echo '<div id="d'.$file_id.'" class="uploaded-case-display">'.$file_url.$remove.'</div>';

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
            <td colspan="3"><div align="center"><span class="defaultFont"><input type="submit" name="case_main_submit" value="Save and proceed" class="inputs submit" /></span></div></td>
        </tr>
    </table>
</form>
</div>
<?php
	//$file_path = $_SERVER['DOCUMENT_ROOT'].'/user-uploads/cases/RER/f6913_Dark Angel.mpg';
	//rmdir($_SERVER['DOCUMENT_ROOT'].'/user-uploads/RER');
	//unlink($file_path);
?>

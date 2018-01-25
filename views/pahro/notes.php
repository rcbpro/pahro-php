<?php if ($invalidPage != true):?>           
	<?php if ((@$_GET['opt'] != "view") && (@$_GET['opt'] != "edit")):?>
        <span class="defaultFont requiredFieldsIndicator">*&nbsp;</span><span class="defaultFont requiredFieldsIndicator smallFont">Required fields</span><br /><br />
    <?php endif;?>
    <form id="pahro_notes_submit_form" name="pahro_notes_submit_form" action="" method="post"> 
        <!-- Start of notes in its PAHRO edit section -->
		<?php if (!isset($_GET['opt'])):?>        
		<div align="center">
			<table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
				<!-- New note adding in the Client edit section -->
				<tr valign="middle">
					<th align="center"><span class="defaultFont">Description</span></th>
				</tr>
				<tr>
					<td align="center" valign="top"><textarea class="noteTextArea" rows="6" name="pahro_note_required[note_text]"><?php if (!empty($_SESSION['pahro_note_required']['note_text'])){ echo trim($_SESSION['pahro_note_required']['note_text']);}?></textarea></td>
				</tr>                                   
			</table>
		</div>
        <br />
        <div align="center"><input type="submit" name="pahro_notes_submit" class="inputs submit" value="Save note" />&nbsp;<input onclick="location.href='<?php echo $site_config['base_url'];?>pahro/view/'" type="button" name="pahro_notes_exit" class="inputs submit" value="Done" /></div>
		<!-- End of the Client Edit new note adding section -->
		<!-- If this client having notes -->
			<?php if (count($all_notes_to_this_client) > 0):?>  
			<br /><br />
            <div class="someWidth5 text-center-align"><span class="defaultFont specializedTexts boldText">Your Previous Notes</span></div>                    
            <br />
            <div align="center">
                <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
                    <tr>
                        <th width="50" align="center"><div class="actionTableFieldTd3"><span class="defaultFont">Action</span></div></th>            
                        <th width="300" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=note".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Description ".((isset($_GET['sort']) && $_GET['sort'] == "note") ? $img : "")."</a>" : "<span class='defaultFont'>Description</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added By ".((isset($_GET['sort']) && $_GET['sort'] == "add_by") ? $img : "")."</a>" : "<span class='defaultFont'>Added By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added Date ".((isset($_GET['sort']) && $_GET['sort'] == "add_date") ? $img : "")."</a>" : "<span class='defaultFont'>Added Date</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified By ".((isset($_GET['sort']) && $_GET['sort'] == "mod_by") ? $img : "")."</a>" : "<span class='defaultFont'>Modified By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified Date ".((isset($_GET['sort']) && $_GET['sort'] == "mod_date") ? $img : "")."</a>" : "<span class='defaultFont'>Modified Date</span>";?></a></th>
                    </tr>
                    <?php for($i=0; $i<count($all_notes_to_this_client); $i++):?>
                    <tr>
                        <td width="50" align="center"><div class="actionTableFieldTd3">
                        
                                       <a title="Details" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=view&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $all_notes_to_this_client[$i]['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_browse.png" border="0" alt="Browse" /></a>                                   
                                       <a title="Edit" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=edit&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $all_notes_to_this_client[$i]['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_edit.png" border="0" alt="Edit" /></a>
                                       <a title="Drop" onclick="ask_for_delete_record('<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=drop&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $all_notes_to_this_client[$i]['note_id'];?>');" href="#"><img src="../../public/images/b_drop.png" border="0" alt="Drop" /></a></div></td>
                                       
                        <td width="300" align="center"><p class="defaultFont note_description"><?php echo $all_notes_to_this_client[$i]['note'];?></p></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo $all_notes_to_this_client[$i]['username'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo $all_notes_to_this_client[$i]['added_date'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($all_notes_to_this_client[$i]['modified_by'] != 0) ? $pahro_users_model->grab_the_username_related_note_as_modifield_person($all_notes_to_this_client[$i]['modified_by']) : "--";?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($all_notes_to_this_client[$i]['date_modified'] != "0000-00-00 00:00:00") ? $all_notes_to_this_client[$i]['date_modified'] : "--";?></span></td>                        
                    </tr>					
                    <?php endfor;?>
                </table>
            </div>                 
			<?php endif;?> 
		<?php else:?>         
			<?php if ((isset($_GET['opt'])) && ($_GET['opt'] == "edit")):?>
				<!-- single note edit section -->
                <div align="center">
                    <table border="0" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
                        <!-- New note adding in the pfac edit section -->
                        <tr valign="middle">
                            <th align="center"><span class="defaultFont">Added Date</span></th>
                            <th align="center"><span class="defaultFont">Description</span></th>
                            <th align="center"><span class="defaultFont">Added By</span></th>
                        </tr>
                        <tr>
                            <td valign="middle"><span class="defaultFont"><?php echo $note_full_details[0]['added_date'];?></span></td>
                            <td valign="top"><textarea class="noteTextArea defaultFont" rows="6" name="pahro_note_required[note_text]"><?php if (!empty($_SESSION['pahro_note_required']['note_text'])){ echo trim($_SESSION['pahro_note_required']['note_text']);} else { echo $note_full_details[0]['note'];}?></textarea></td>
                            <td valign="middle"><span class="defaultFont"><?php echo $note_full_details[0]['username'];?></span></td>
                        </tr>
                    </table>
                </div>
            <br />
            <div align="center"><input type="submit" name="pahro_notes_update_submit" class="inputs submit" value="Update" /></div>
			<?php elseif ((isset($_GET['opt'])) && ($_GET['opt'] == "view")):?>
				<!-- single note show section -->   
                <div align="center">             	
				<table border="0" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Date Created</span></td>
                        <td width="30">:</td>
						<td width="150"><span class="defaultFont boldText"><?php echo $note_full_details[0]['added_date'];?></span></td>                
					</tr>
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Created By</span></td>
                        <td width="30">:</td>
						<td width="150"><span class="defaultFont boldText"><?php echo $note_full_details[0]['username'];?></span></td>                
					</tr>
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Description</span></td>
                        <td>:</td>
						<td width="250"><span class="defaultFont boldText"><?php echo $note_full_details[0]['note'];?></span></td>                
					</tr>
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Modified by</span></td>
                        <td>:</td>
						<td width="150"><span class="defaultFont boldText"><?php echo ($note_full_details[0]['modified_by'] != 0) ? $pahro_users_model->grab_the_username_related_note_as_modifield_person($note_full_details[0]['modified_by']) : "--";?></span></td>                
					</tr> 
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Modified Date</span></td>
                        <td>:</td>
						<td width="150"><span class="defaultFont boldText"><?php echo ($note_full_details[0]['date_modified'] != "0000-00-00 00:00:00") ? $note_full_details[0]['date_modified'] : "--";?></span></td>                
					</tr> 
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
				</table>
                </div>
			<?php elseif ((isset($_GET['opt'])) && (($_GET['opt'] == "sorting") || ($_GET['opt'] == "drop"))):?>
				<?php if (count(all_notes_to_this_client) > 0):?>    
                <div align="center">
                    <table border="0" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
                        <!-- New note adding in the pfac edit section -->
                        <tr valign="middle">
                            <th align="center"><span class="defaultFont">Description</span></th>
                        </tr>
                        <tr>
                            <td align="center" valign="top"><textarea class="noteTextArea" rows="6" name="pahro_note_required[note_text]"><?php if (!empty($_SESSION['pahro_note_required']['note_text'])){ echo trim($_SESSION['pahro_note_required']['note_text']);}?></textarea></td>
                        </tr>                                   
                    </table>
                </div>
                <br />
                <div align="center"><input type="submit" name="pahro_notes_submit" class="inputs submit" value="Save note" />&nbsp;<input onclick="location.href='<?php echo $site_config['base_url'];?>pahro/view/'" type="button" name="pahro_notes_exit" class="inputs submit" value="Done" /></div>
				<div class="smallHeight"><!-- --></div>                            
                <div class="someWidth5 text-center-align"><span class="defaultFont specializedTexts boldText">Your Previous Notes</span></div>                    
                <div class="smallHeight"><!-- --></div>                
                <div align="center">                
                <table border="0" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
                    <tr>
                        <th width="50" align="center"><div class="actionTableFieldTd3"><span class="defaultFont">Action</span></div></th>            
                        <th width="300" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=note".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Description ".((isset($_GET['sort']) && $_GET['sort'] == "note") ? $img : "")."</a>" : "<span class='defaultFont'>Description</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added By ".((isset($_GET['sort']) && $_GET['sort'] == "add_by") ? $img : "")."</a>" : "<span class='defaultFont'>Added By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added Date ".((isset($_GET['sort']) && $_GET['sort'] == "add_date") ? $img : "")."</a>" : "<span class='defaultFont'>Added Date</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified By ".((isset($_GET['sort']) && $_GET['sort'] == "mod_by") ? $img : "")."</a>" : "<span class='defaultFont'>Modified By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified Date ".((isset($_GET['sort']) && $_GET['sort'] == "mod_date") ? $img : "")."</a>" : "<span class='defaultFont'>Modified Date</span>";?></a></th>
                    </tr>
                    <?php for($i=0; $i<count($all_notes_to_this_client); $i++):?>
                    <tr>
                        <td width="50" align="center"><div class="actionTableFieldTd3">
                        
                                       <a title="Details" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=view&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $all_notes_to_this_client[$i]['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_browse.png" border="0" alt="Browse" /></a>                                   
                                       <a title="Edit" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=edit&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $all_notes_to_this_client[$i]['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_edit.png" border="0" alt="Edit" /></a>
                                       <a title="Drop" onclick="ask_for_delete_record('<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=drop&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $all_notes_to_this_client[$i]['note_id'];?>');" href="#"><img src="../../public/images/b_drop.png" border="0" alt="Drop" /></a></div></td>
                                       
                        <td width="300" align="center"><p class="defaultFont note_description"><?php echo $all_notes_to_this_client[$i]['note'];?></p></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo $all_notes_to_this_client[$i]['username'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo $all_notes_to_this_client[$i]['added_date'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($all_notes_to_this_client[$i]['modified_by'] != 0) ? $pahro_users_model->grab_the_username_related_note_as_modifield_person($all_notes_to_this_client[$i]['modified_by']) : "--";?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($all_notes_to_this_client[$i]['date_modified'] != "0000-00-00 00:00:00") ? $all_notes_to_this_client[$i]['date_modified'] : "--";?></span></td>                        
                    </tr>					
                    <?php endfor;?>
                </table>
                </div>
                <?php endif;?>   
			<?php endif;?>
		<?php endif;?>                       
		<!-- End of the notes set display section  -->
	<?php endif;?> 
	<!-- End of the PFAC Edit note section -->          
</form>
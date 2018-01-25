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
                	<th align="center"><span class="defaultFont">Categories</span></th>
					<th align="center"><span class="defaultFont">Description</span></th>                    
				</tr>
				<tr>
                	<td valign="top" class="a-left note-cats"><?php echo CommonFunctions::get_note_categories("pahro_note_categories_required");?></td>
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
            <div align="center">
                <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
                	<tr id="tr-filter">
                        <td id="td-filter" colspan="7">
                            <div id="d-filter" class="a-left">
                                <div id="d-filter-l" class="defaultFont specializedTexts boldText">Your Previous Notes</div>                            
                                <div id="d-filter-r" class="defaultFont">
                                    <?php echo CommonFunctions::get_note_categories("pahro_note_categories_required", $inline=true, "filtering", $_POST['pahro_note_categories_required']); ?><input name="notes_filter" type="submit" value="Filter" class="inputs submit btn-filter" />
									<input name="notes_filter_reset" type="button" onclick="location.href='<?php echo $_SERVER['REQUEST_URI'];?>'" value="Reset" class="inputs submit btn-filter" />                                    
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="50" align="center"><div class="actionTableFieldTd3"><span class="defaultFont">Action</span></div></th>            
                        <th width="300" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=note".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Description ".((isset($_GET['sort']) && $_GET['sort'] == "note") ? $img : "")."</a>" : "<span class='defaultFont'>Description</span>";?></a></th>
                        <th width="100"align="center"><span class="defaultFont">Note Categories</span></th> 
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added By ".((isset($_GET['sort']) && $_GET['sort'] == "add_by") ? $img : "")."</a>" : "<span class='defaultFont'>Added By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added Date ".((isset($_GET['sort']) && $_GET['sort'] == "add_date") ? $img : "")."</a>" : "<span class='defaultFont'>Added Date</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified By ".((isset($_GET['sort']) && $_GET['sort'] == "mod_by") ? $img : "")."</a>" : "<span class='defaultFont'>Modified By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified Date ".((isset($_GET['sort']) && $_GET['sort'] == "mod_date") ? $img : "")."</a>" : "<span class='defaultFont'>Modified Date</span>";?></a></th>
                    </tr>
                    <?php foreach($all_notes_to_this_client as $note):?>
                    <tr>
                        <td width="50" align="center"><div class="actionTableFieldTd3">
                        
                                       <a title="Details" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=view&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $note['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_browse.png" border="0" alt="Browse" /></a>                                   
                                       <a title="Edit" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=edit&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $note['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_edit.png" border="0" alt="Edit" /></a>
                                       <a title="Drop" onclick="ask_for_delete_record('<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=drop&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $note['note_id'];?>');" href="#"><img src="../../public/images/b_drop.png" border="0" alt="Drop" /></a></div></td>
                                       
                        <td width="300" align="center"><p class="defaultFont note_description"><?php echo $note['note'];?></p></td>
                        <td class="a-left">
						<?php for($i=0; $i<count($note['note_cats']); $i++):?>
                    		<span class="specializedTexts defaultFont">
							<?php echo $note['note_cats'][$i]['note_cat_name'];?>
                            </span>
                            <br />
						<?php endfor;?></td> 
                        <td width="100" align="center"><span class="defaultFont"><?php echo $note['username'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo $note['added_date'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($note['modified_by'] != 0) ? $pahro_users_model->grab_the_username_related_note_as_modifield_person($note['modified_by']) : "--";?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($note['date_modified'] != "0000-00-00 00:00:00") ? $note['date_modified'] : "--";?></span></td>                        
                    </tr>					
                    <?php endforeach;?>
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
                            <th align="center"><span class="defaultFont">Categories</span></th>
                            <th align="center"><span class="defaultFont">Description</span></th>
                            <th align="center"><span class="defaultFont">Added By</span></th>
                        </tr>
                        <tr>
                            <td valign="middle"><span class="defaultFont"><?php echo $note_full_details['added_date'];?></span></td>
                            <td valign="top" class="a-left note-cats">
								<?php echo CommonFunctions::get_note_categories("pahro_note_categories_required", "", "", $note_full_details['notes_categories']);?>
                            </td>
                            <td valign="top"><textarea class="noteTextArea defaultFont" rows="6" name="pahro_note_required[note_text]"><?php if (!empty($_SESSION['pahro_note_required']['note_text'])){ echo trim($_SESSION['pahro_note_required']['note_text']);} else { echo $note_full_details['note'];}?></textarea></td>
                            <td valign="middle"><span class="defaultFont"><?php echo $note_full_details['username'];?></span></td>
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
						<td width="150"><span class="defaultFont boldText"><?php echo $note_full_details['added_date'];?></span></td>                
					</tr>
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Created By</span></td>
                        <td width="30">:</td>
						<td width="150"><span class="defaultFont boldText"><?php echo $note_full_details['username'];?></span></td>                
					</tr>
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Description</span></td>
                        <td>:</td>
						<td width="250"><span class="defaultFont boldText"><?php echo $note_full_details['note'];?></span></td>                
					</tr>
                    <tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Categories</span></td>
                        <td>:</td>
						<td width="250">
						<?php foreach($note_full_details['notes_categories'] as $each_note_cat):?>                        
                        <span class="defaultFont boldText"><?php echo $each_note_cat['note_cat_name'];?></span><br />
                        <?php endforeach;?>
                        </td>                
					</tr>
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Modified by</span></td>
                        <td>:</td>
						<td width="150"><span class="defaultFont boldText"><?php echo ($note_full_details['modified_by'] != 0) ? $pahro_users_model->grab_the_username_related_note_as_modifield_person($note_full_details['modified_by']) : "--";?></span></td>                
					</tr> 
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
					<tr valign="top">
						<td width="150"><span class="defaultFont specializedTexts">Modified Date</span></td>
                        <td>:</td>
						<td width="150"><span class="defaultFont boldText"><?php echo ($note_full_details['date_modified'] != "0000-00-00 00:00:00") ? $note_full_details['date_modified'] : "--";?></span></td>                
					</tr> 
					<tr valign="top">
						<td colspan="3">&nbsp;</td>
					</tr>                        
				</table>
                </div>
			<?php elseif ((isset($_GET['opt'])) && (($_GET['opt'] == "sorting") || ($_GET['opt'] == "drop"))):?>
				<?php if (count(all_notes_to_this_client) > 0):?>    
                <div align="center">
                    <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
                        <!-- New note adding in the pfac edit section -->
                        <tr valign="middle">
                        	<th align="center"><span class="defaultFont">Categories</span></th>
                            <th align="center"><span class="defaultFont">Description</span></th>
                        </tr>
                        <tr>
                        	<td valign="top" class="a-left note-cats"><?php echo CommonFunctions::get_note_categories("pahro_note_categories_required"); ?></td>
                            <td align="center" valign="top"><textarea class="noteTextArea" rows="6" name="pahro_note_required[note_text]"><?php if (!empty($_SESSION['pahro_note_required']['note_text'])){ echo trim($_SESSION['pahro_note_required']['note_text']);}?></textarea></td>
                        </tr>                                   
                    </table>
                </div>
                <br />
                <div align="center"><input type="submit" name="pahro_notes_submit" class="inputs submit" value="Save note" />&nbsp;<input onclick="location.href='<?php echo $site_config['base_url'];?>pahro/view/'" type="button" name="pahro_notes_exit" class="inputs submit" value="Done" /></div>
                <br /><br />
                <div align="center">                
                <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" align="center" class="tbs-main-notes">
                	<tr id="tr-filter">
                        <td id="td-filter" colspan="7">
                            <div id="d-filter" class="a-left">
                                <div id="d-filter-l" class="defaultFont specializedTexts boldText">Your Previous Notes</div>                            
                                <div id="d-filter-r" class="defaultFont">
                                    <?php echo CommonFunctions::get_note_categories("pahro_note_categories_required", $inline=true, "filtering", $_POST['pahro_note_categories_required']); ?><input name="notes_filter" type="submit" value="Filter" class="inputs submit btn-filter" />
									<input name="notes_filter_reset" type="button"  onclick="location.href='<?php echo $_SERVER['REQUEST_URI'];?>'" value="Reset" class="inputs submit btn-filter" />                                                                        
                                </div>
                            </div>
                        </td>
                    </tr>                    
                    <tr>
                        <th width="50" align="center"><div class="actionTableFieldTd3"><span class="defaultFont">Action</span></div></th>            
                        <th width="300" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=note".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Description ".((isset($_GET['sort']) && $_GET['sort'] == "note") ? $img : "")."</a>" : "<span class='defaultFont'>Description</span>";?></a></th>
                        <th width="100">Note Categories</th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added By ".((isset($_GET['sort']) && $_GET['sort'] == "add_by") ? $img : "")."</a>" : "<span class='defaultFont'>Added By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note added date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=add_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Added Date ".((isset($_GET['sort']) && $_GET['sort'] == "add_date") ? $img : "")."</a>" : "<span class='defaultFont'>Added Date</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified person\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_by".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified By ".((isset($_GET['sort']) && $_GET['sort'] == "mod_by") ? $img : "")."</a>" : "<span class='defaultFont'>Modified By</span>";?></a></th>
                        <th width="100" align="center"><?php echo ($all_notes_count_to_this_client > 1) ? "<a title=\"Sort By note modified date\" class=\"defaultFont soringMenusLink\" href=\"?mode=notes&pahro_id=".$pahro_id."&opt=sorting&sort=mod_date".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Modified Date ".((isset($_GET['sort']) && $_GET['sort'] == "mod_date") ? $img : "")."</a>" : "<span class='defaultFont'>Modified Date</span>";?></a></th>
                    </tr>
                    <?php foreach($all_notes_to_this_client as $note):?>
                    <tr>
                        <td width="50" align="center"><div class="actionTableFieldTd3">
                                       <a title="Details" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=view&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $note['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_browse.png" border="0" alt="Browse" /></a>                                   
                                       <a title="Edit" href="<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=edit&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $note['note_id'].(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1");?>"><img src="../../public/images/b_edit.png" border="0" alt="Edit" /></a>
                                       <a title="Drop" onclick="ask_for_delete_record('<?php echo $site_config['base_url'];?>pahro/edit/?mode=notes&opt=drop&pahro_id=<?php echo $pahro_id;?><?php echo (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>&note_id=<?php echo $note['note_id'];?>');" href="#"><img src="../../public/images/b_drop.png" border="0" alt="Drop" /></a></div></td>
                        <td width="300" align="center"><p class="defaultFont note_description"><?php echo $all_notes_to_this_client[$i]['note'];?></p></td>
                        <td class="a-left">
						<?php for($i=0; $i<count($note['note_cats']); $i++):?>
                    		<span class="specializedTexts defaultFont">
							<?php echo $note['note_cats'][$i]['note_cat_name'];?>
                            </span>
                            <br />
						<?php endfor;?></td> 
                        <td width="100" align="center"><span class="defaultFont"><?php echo $note['username'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo $note['added_date'];?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($note['modified_by'] != 0) ? $pahro_users_model->grab_the_username_related_note_as_modifield_person($note['modified_by']) : "--";?></span></td>
                        <td width="100" align="center"><span class="defaultFont"><?php echo ($note['date_modified'] != "0000-00-00 00:00:00") ? $note['date_modified'] : "--";?></span></td>                        
                    </tr>					
                    <?php endforeach;?>
                </table>
                </div>
                <?php endif;?>   
			<?php endif;?>
		<?php endif;?>                       
		<!-- End of the notes set display section  -->
	<?php endif;?> 
	<!-- End of the PFAC Edit note section -->          
</form>
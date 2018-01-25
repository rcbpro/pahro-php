<script type="text/javascript">
function prepareInputsForHints() {
	var inputs = document.getElementsByTagName("input");
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
<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <!-- This is the success/error message to be diplayed -->
    <?php global $headerDivMsg; echo $headerDivMsg;?>
    <!-- End of the success or error message -->
    <div class="headerTopicContainer defaultFont boldText"><span class="headerTopicSelected">Import Volunteer Users from Tagos</span></div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
    	<!-- Which subview to load -->
        <div class="im-wrap" align="center">
        <div id="im-loader">Please wait...</div>
            <div id="d-h-sa" title="History for South Africa">
                <div class="row-h">
                    <div class="r1">Date</div>
                    <div class="r2">Imported by</div>
                    <div class="r3">No. of Vols</div>
                </div>
                <?php

					foreach($import_inter_log_for_sa as $vol){
						$date = AppViewer::format_date_full($vol['last_clicked_date']);
						$user = $vol['username'];
						$no_of_vols = $vol['no_of_vols_imported'];
				?>
                <div class="row">
                    <div class="r1"><?php echo $date;?></div>
                    <div class="r2"><?php echo $user;?></div>
                    <div class="r3"><?php echo $no_of_vols;?></div>
                </div>
                <?php }?>
                <div class="dis-rec">Displaying recent 15 records</div>
            </div>
            
            <div id="d-h-ga" title="History for Ghana">
            	<div class="row-h">
                    <div class="r1">Date</div>
                    <div class="r2">Imported by</div>
                    <div class="r3">No. of Vols</div>
                </div>
                <?php
					foreach($import_inter_log_for_gh as $vol){
						$date = AppViewer::format_date_full($vol['last_clicked_date']);
						$user = $vol['username'];
						$no_of_vols = $vol['no_of_vols_imported'];
				?>
                <div class="row">
                    <div class="r1"><?php echo $date;?></div>
                    <div class="r2"><?php echo $user;?></div>
                    <div class="r3"><?php echo $no_of_vols;?></div>
                </div>
                <?php }?>
                <div class="dis-rec">Displaying recent 15 records</div>
            </div>
            <form name="tagos_search" method="POST" action="<?php echo $site_config['base_url'];?>pahro/import/">            
            <table id="t-b-import" border="0" cellpadding="0" cellspacing="0">
				<?php if (count($_SESSION['logged_user']['countries']) > 1):?>
                <tr>
                <td>
                    <input type="submit" name="mytpa_submit_from_sa" value="Import From South Africa" class="inputs submit" />
                </td>
                <td width="10">&nbsp;</td>
                <td>
                    <input type="submit" name="mytpa_submit_from_ghana" value="Import From Ghana" class="inputs submit" />                        
                </td>
                <tr>
                	<td><span id="s-h-sa">View history</span></td>
                    <td>&nbsp;</td>
                    <td><span id="s-h-ga">View history</span></td>
                </tr>
                <?php elseif ($_SESSION['logged_user']['countries'][0] == 1):?>
                <tr>
                    <td>
                        <input type="submit" name="mytpa_submit_from_sa" value="Import From South Africa" class="inputs submit" />
                    </td>
                </tr>
                <tr>
                	<td><span id="s-h-sa">View histroy</span></td>
                </tr>
                <?php elseif ($_SESSION['logged_user']['countries'][0] == 2):?>
                <tr>
                    <td align="center">
                        <input type="submit" name="mytpa_submit_from_ghana" value="Import From Ghana" class="inputs submit" />
                    </td>
                </tr>
                
                <tr>
                	<td><span id="s-h-ga">View histroy</span></td>
                </tr>    
                <?php endif;?>

            </table>                    
            </form>    
        <!-- End of the sub view -->
    	<?php if ($previously_imported_vols):?>
        <br /><hr /><br />                            
        <div id="pfacTableDatContainer" align="center">
            <?php if (count($previously_imported_vols) > 0):?>
            <table cellpadding="0" cellspacing="0" class="tbs-main-users" bordercolor="#e3c9e4" border="1">
                <thead>
                    <th width="200"><span class="defaultFont"><?php echo ($previously_imported_vols_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Name </a>".
                                                                    (((isset($_GET['sort']) && $_GET['sort'] == "f_name") || (isset($_GET['sort']) && $_GET['sort'] == "l_name")) ? $img : "").
                                                                    "<br /><a class=\"soringMenusLink\" href=\"?sort=f_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">First Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "f_name") ? $img : "").
                                                                    "|<a class=\"soringMenusLink\" href=\"?sort=l_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><span class=\"smallFont\">Last Name </span></a>".
                                                                    ((isset($_GET['sort']) && $_GET['sort'] == "l_name") ? $img : "")."" : "Name";?></span></th>
   					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>	                                                                 
                    <th><span class="defaultFont soringMenusLink">Countries</span></th>                                                                    
                    <?php endif;?>
                    <th><span class="defaultFont soringMenusLink"><?php echo ($previously_imported_vols_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=u_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Username ".((isset($_GET['sort']) && $_GET['sort'] == "u_name") ? $img : "")."</a>" : "Username";?></a></span></th>
                    <th><span class="defaultFont soringMenusLink"><?php echo ($previously_imported_vols_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=email".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Email ".((isset($_GET['sort']) && $_GET['sort'] == "email") ? $img : "")."</a>" : "Email";?></a></span></th>
                    <th><span class="defaultFont soringMenusLink"><?php echo ($previously_imported_vols_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=created_at".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Created At ".((isset($_GET['sort']) && $_GET['sort'] == "created_at") ? $img : "")."</a>" : "Created At";?></a></span></th>
                    <th><span class="defaultFont soringMenusLink"><?php echo ($previously_imported_vols_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=last_login".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Last Login ".((isset($_GET['sort']) && $_GET['sort'] == "last_login") ? $img : "")."</a>" : "Last Login";?></a></span></th>                                                            
                </thead>
                <?php foreach($previously_imported_vols as $each_vol):?>
                <tr>
                    <td class="a-left"><a href="<?php echo $site_config['base_url'];?>pahro/show/?pahro_id=<?php echo $each_vol['id'];?>"><span title="<?php echo $each_vol['first_name'];?>&nbsp;<?php echo $each_vol['last_name'];?>" class="defaultFont"><?php echo $each_vol['first_name'];?>&nbsp;<?php echo $each_vol['last_name'];?></span></a></td>
   					<?php if (count($_SESSION['logged_user']['countries']) > 1):?>	                                                                                     
                    <?php
					if (count($_SESSION['logged_user']['countries']) > 1){
						$country_name1 = '';
						$country_name2 = '';
						if (in_array(1, $each_vol['country_list'])){
							$country_name1 = "South Africa";						
						}
						if (in_array(2, $each_vol['country_list'])){
							$country_name2 = "Ghana";						
						}
						$countries = '';
						if($country_name1 && $country_name2){
							$countries = $country_name1.' / '.$country_name2;
						}
						else{
							$countries = $country_name1.$country_name2;
						}
						
					}
					
					?>
                    <td><span title="<?php echo $countries;?>" class="defaultFont"><?php echo $countries;?></span></td>
					<?php endif;?>                    
                    <td><span title="<?php echo $each_vol['username'];?>" class="defaultFont"><?php echo $each_vol['username'];?></span></td>
                    <td><span title="<?php echo $each_vol['email'];?>" class="defaultFont"><?php echo ($each_vol['email'] != "") ? $each_vol['email'] : "--";?></span></td>                                                            
                    <td><span title="<?php echo $each_vol['created_at'];?>" class="defaultFont"><?php echo $each_vol['created_at'];?></span></td>
                    <td><span title="<?php echo $each_vol['last_login'];?>" class="defaultFont"><?php echo ($each_vol['last_login'] != "") ? $each_vol['last_login'] : "Not Logged";?></span></td>
                </tr>					
                <?php endforeach;?>
            </table><br />
            <?php else:?>
                <div align="left"><span class="defaultFont boldText specializedTexts">No volunteers !</span></div>
            <?php endif;?>
        </div>     
        <?php else:?>
        <div align="left"><span class="defaultFont boldText specializedTexts">No volunteers !</span></div>
        <?php endif;?>
        </div>        
    </div>
</div>
<!-- Start of the Pagination for the notes section -->                          
<?php if ((@$_GET['opt'] != "edit") && ($tot_page_count > 1)):?>        
<div id="pagination_wrap">
    <div id="pagination">
        <div class="paginationContainer"><?php echo $pagination;?></div>
    </div>
</div>
<?php endif;?>    
<!-- End of the Pagination for the notes section -->
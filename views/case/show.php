<div id="static_header">
<!-- Breadcrumb div message -->
<?php echo $breadcrumb;?>
<!--  End of the Breadcrumb div mesage -->
<div class="headerTopicContainer view-title">Case Reference <span class="view-ref"><?php echo $fullDetails[0]['reference_number'];?></span></div>

</div>
<div id="dataInputContainer_wrap">
    <div id="dataInputContainer" align="center">
    	<div id="view-main" align="center">            	
            <br />
            <div>
            <fieldset>
                <legend>General Details</legend>
                <div class="view-row">
                    <div class="row-l">Case title</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo $fullDetails[0]['case_name'];?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Case owned country</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo $fullDetails[0]['country_name'];?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Case category</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo $fullDetails[0]['case_cat_name'];?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Staff responsible</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo $fullDetails[0]['username'];?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Description</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo ($fullDetails[0]['description'] != "") ? $fullDetails[0]['description'] : '<span class="na">- N/A -</span>';?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Comment</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo ($fullDetails[0]['comment'] != "") ? $fullDetails[0]['comment'] : '<span class="na">- N/A -</span>';?></div>
                </div>            
            </fieldset>
            </div>
            <br />
            <div>
                <fieldset>
                    <legend>Status</legend>
                    <div class="view-row">
                        <div class="row-l">Current status</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo $fullDetails[0]['status'];?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Created date</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo AppViewer::format_date($fullDetails[0]['created_date']);?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Opend date</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['opend_date'] != "0000-00-00") ? AppViewer::format_date($fullDetails[0]['opend_date']) : "Not opend";?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Review date</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['upcoming_date'] != "0000-00-00") ? AppViewer::format_date($fullDetails[0]['upcoming_date']) : '<span class="na">--</span>';?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Closed date</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['closed_date'] != "0000-00-00") ? AppViewer::format_date($fullDetails[0]['closed_date']) : "Not closed";?></div>
                    </div>
                    <?php if($fullDetails[0]['closed_date'] != "0000-00-00"){?>
                    <div class="view-row">
                        <div class="row-l">Reason for close</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo $fullDetails[0]['reasone_for_close'];?></div>
                    </div>
                    <?php }?>
                </fieldset>
            </div>
            <br />
            <div>
                <fieldset>
                    <legend>Miscellaneous</legend>
                    <div class="view-row">
                        <div class="row-l">Created by</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo $caseModel->grab_the_username_for_cases($fullDetails[0]['created_by'], "created_by");?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Last edited by</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['edited_by'] != 0) ? $caseModel->grab_the_username_for_cases($fullDetails[0]['edited_by'], "edited_by") : '<span class="na">--</span>';?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Last edited date</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['edited_date'] != "0000-00-00 00:00:00") ? AppViewer::format_date($fullDetails[0]['edited_date']) : '<span class="na">--</span>';?></div>
                    </div>       
                </fieldset>
            </div>
            <br />
            <div>
                <fieldset>
                    <legend class="bg-sp">Volunteers Involved</legend>
                    <div class="view-row-name">
                        <?php if (!empty($vols_assigned)):?>
                            <?php foreach($vols_assigned as $vols):?>
                                    <a href="<?php echo $site_config['base_url'];?>pahro/show/?pahro_id=<?php echo $vols['id'];?>&notes_page=1">
                                        <div class="view-name" title="View details"><?php echo $vols['first_name'].' '.$vols['last_name'];?></div>
                                    </a>
                            <?php endforeach;?>
                       <?php else:?>
                            <div class="no">No Volunteers are involved</div>
                       <?php endif;?> 
                    
                    </div>
                </fieldset>
            </div>
            <br />
            <div>
                <fieldset>
                    <legend class="bg-sp">Clients</legend>
                    <div class="view-row-name">
                        <?php if (!empty($clients_assigned)):?>
                            <?php foreach($clients_assigned as $client):?>
                                <a href="<?php echo $site_config['base_url'];?>client/show/?client_id=<?php echo $client['client_id'];?>">
                                    <div class="view-name" title="View details"><?php echo $client['first_name']."&nbsp;".$client['last_name'];?></div>
                                </a>							
                            <?php endforeach;?>
                       <?php else:?>
                            <div class="no">No Clients are assigned</div>
                       <?php endif;?>
                    </div>
                </fieldset>
            </div>
            <br />
            <div>
                <fieldset>
                    <legend class="bg-sp">Counter Parties</legend>
                    <div class="view-row-name">
                        <?php if (!empty($cp_assigned)):?>
                            <?php foreach($cp_assigned as $cp):?>
                                <a href="<?php echo $site_config['base_url'];?>counter-party/show/?cp_id=<?php echo $cp['counter_party_id'];?>">
                                    <div class="view-name" title="View details"><?php echo $cp['first_name']."&nbsp;".$cp['last_name'];?></div>
                                </a>
                            <?php endforeach;?>
                       <?php else:?>
                            <div class="no">No Counter Parties are assigned</div>
                       <?php endif;?>
                    </div>
                </fieldset>
            </div>

            <br />
            <br />
        </div>
    </div>
</div>
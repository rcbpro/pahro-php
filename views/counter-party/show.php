<!-- Breadcrumb div message -->
<?php echo $breadcrumb;?>
<!--  End of the Breadcrumb div mesage -->
<div class="headerTopicContainer defaultFont boldText"><span class="headerTopicSelected"><div class="someWidth2 floatLeft"><!-- --></div><?php echo $fullDetails[0]['title'].". ".$fullDetails[0]['first_name']." ".$fullDetails[0]['last_name'];?></span>
</div>
<div id="dataInputContainer_wrap">
    <div id="dataInputContainer" align="center">
    	<div id="view-main" align="center">
        <br />
            <div>
                <fieldset>
                    <legend>General Details</legend>
                    <div class="view-row">
                        <div class="row-l">Counter Party owned country</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['country_name'] != "") ? $fullDetails[0]['country_name'] : '<span class="na">- N/A -</span>';?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Company Name</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['company_name'] != "") ? $fullDetails[0]['company_name'] : '<span class="na">- N/A -</span>';?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Residental Address</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo $fullDetails[0]['resident_address'];?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Postal Address</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['postal_address'] != "") ? $fullDetails[0]['postal_address'] : '<span class="na">- N/A -</span>';?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Land phone number</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['land_phone'] != "") ? $fullDetails[0]['land_phone'] : '<span class="na">- N/A -</span>';?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">Mobile phone number</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['mobile_phone'] != "") ? $fullDetails[0]['mobile_phone'] : '<span class="na">- N/A -</span>';?></div>
                    </div>
                    <div class="view-row">
                        <div class="row-l">E-mail</div>
                        <div class="row-space">:</div>
                        <div class="row-r"><?php echo ($fullDetails[0]['email'] != "") ? $fullDetails[0]['email'] : '<span class="na">- N/A -</span>';?></div>
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
                    <legend class="bg-sp">Cases owned</legend>
                    <div class="view-row-name">
                        <?php if (!empty($cases_owned)):?>
                            <?php foreach($cases_owned as $case):?>
                            	<div class="case-c-panel">
                                    <a href="<?php echo $site_config['base_url']."case/show/?ref_no=".$case;?>">
                                        <div class="c-v" title="View Case details"><?php echo $case;?></div>
                                    </a>
                                    <a href="<?php echo $site_config['base_url']."case/edit/?mode=main&ref_no=".$case;?>">
                                        <div class="c-e" title="Edit Case"></div>
                                    </a>
                                </div>
                            <?php endforeach;?>
                       <?php else:?>
                            <div class="no">No Cases are owned</div>
                       <?php endif;?>
                    </div>
                </fieldset>
            </div>
            <br />
            <br />
		</div>
    </div>
</div>
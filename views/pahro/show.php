<!-- Breadcrumb div message -->
<?php echo $breadcrumb;?>
<!--  End of the Breadcrumb div mesage -->
<div class="headerTopicContainer view-title">
	<span class="view-ref"><?php echo ucwords($fullDetails[0]['first_name'])." ".ucwords($fullDetails[0]['last_name']);?></span>
</div>
<div id="dataInputContainer_wrap">
    <div id="dataInputContainer" align="center">
    <div id="view-main" align="center">
    	<br />
    	<div>
            <fieldset>
                <legend>General Details</legend>
                <div class="view-row">
                    <div class="row-l">Username</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo $fullDetails[0]['username'];?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Full name</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo $fullDetails[0]['first_name']." ".$fullDetails[0]['last_name'];?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">User type</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo $fullDetails[0]['user_type'];?></div>
                </div>                
                <div class="view-row">
                    <div class="row-l">Email</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo ($fullDetails[0]['email'] != "") ? $fullDetails[0]['email'] : '<span class="na">- N/A -</span>';?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Created date</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo AppViewer::format_date($fullDetails[0]['created_at']);?></div>
                </div>
                <div class="view-row">
                    <div class="row-l">Last login</div>
                    <div class="row-space">:</div>
                    <div class="row-r"><?php echo ($fullDetails[0]['last_login'] != "0000-00-00 00:00:00") ? AppViewer::format_date($fullDetails[0]['last_login']) : "Not logged";?></div>
                </div>				
            </fieldset>
        </div>        
        <?php if (!empty($assigned_cases)):?>
        <br />
        <div>
            <fieldset>
                <legend class="bg-sp">Cases Involved</legend>
                <div class="view-row-name">
                    <?php if (!empty($assigned_cases)):?>
                        <?php foreach($assigned_cases as $case):?>
                                <a href="<?php echo $site_config['base_url']."case/show/?ref_no=".$case['reference_number'];?>">
                                    <div class="view-name">
                                        <?php echo $case['reference_number'];?>
                                    </div>
                                </a>
                        <?php endforeach;?>
					<?php else:?>
                        <div class="no">No cases are involved</div>
					<?php endif;?> 
                </div>
            </fieldset>
        </div>
        <?php endif;?>
        <?php if (!empty($responsible_cases)):?>
        <br />
        <div>
            <fieldset>
                <legend class="bg-sp">Cases Responsible</l
                egend>
                <div class="view-row-name">
                    <?php if (!empty($responsible_cases)):?>
                        <?php foreach($responsible_cases as $case):?>
                                <a href="<?php echo $site_config['base_url']."case/show/?ref_no=".$case;?>">
                                    <div class="view-name">
                                        <?php echo $case;?>
                                    </div>
                                </a>
                        <?php endforeach;?>
					<?php else:?>
                        <div class="no">No cases are involved</div>
					<?php endif;?> 
                </div>
            </fieldset>
        </div>
        <?php endif;?>       
    </div>
    </div>
</div>
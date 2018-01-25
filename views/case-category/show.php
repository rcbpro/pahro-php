<div id="static_header">
<!-- Breadcrumb div message -->
<?php echo $breadcrumb;?>
<!--  End of the Breadcrumb div mesage -->
<div class="headerTopicContainer defaultFont boldText"><span class="headerTopicSelected"><?php echo $fullDetails[0]['case_cat_name'];?></span></div>
</div>
<div id="dataInputContainer_wrap">
    <div id="dataInputContainer" align="center">
         <table border="0" cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr valign="top">
                <td><span class="defaultFont specializedTexts">Case Category Name</span></td>
                <td width="20" align="center" class="smallHeight defaultFont a-center">:</td>
                <td><span class="defaultFont boldText"><?php echo $fullDetails[0]['case_cat_name'];?></span></td>                
            </tr>
            <tr valign="top">
                <td><span class="defaultFont specializedTexts">Case Category Description</span></td>
                <td width="20" align="center" class="smallHeight defaultFont a-center">:</td>                
                <td><span class="defaultFont boldText"><?php echo ($fullDetails[0]['case_cat_description'] != "") ? $fullDetails[0]['case_cat_description'] : "--";?></span></td>                
            </tr>        
            <tr valign="top">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr valign="top">
                <td colspan="2">&nbsp;</td>
            </tr>
            </table>
            <?php if (!empty($cases_owned)):?>   
            	<div align="center">
                	<div><span class="defaultFont specializedTexts"><U>Cases involved with this Case Category</U></span></div>
                    <br />
                </div>
                <table border="1" cellpadding="0" cellspacing="0" class="tbs-main-users" bordercolor="#e3c9e4">
                     <tr>
                        <th width="200"><span class="defaultFont"><span class="defaultFont">Case Name</span></th>
                        <th width="300"><span class="defaultFont"><span class="defaultFont">Case Description</span></th>
                    </tr>     
				<?php foreach($cases_owned as $each_case):?>
                	<tr>
                    	<td><a class="edtingMenusLink_in_view" href="<?php echo $site_config['base_url'];?>case/show/?ref_no=<?php echo $each_case['reference_number'];?>"><?php echo $each_case['reference_number'];?></a></td>
                        <td class="a-left"><?php echo $each_case['case_name'];?></td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                </table>                              
	        <?php endif;?>
    </div>
</div>
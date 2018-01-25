<div align="center">
<form name="case_cat_main_form" method="post" action="">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><span class="defaultFont">Case Category Name</span></td>
            <td><div class="smallHeight"><span class="defaultFont requiredFieldsIndicator">*&nbsp;</span></div></td>                
            <td>
                <input type="text" name="case_category_main_reqired[case_category_name]" 
                        value="<?php 
                                    if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['case_category_main_reqired']))){
                                        $case_category_name = $_SESSION['case_category_main_reqired']['case_category_name'];
									}elseif ((strstr($_SERVER['REQUEST_URI'], "edit/")) && (isset($full_details))){
										$case_category_name = (isset($_SESSION['case_category_main_reqired'])) ? $_SESSION['case_category_main_reqired']['case_category_name'] : $full_details[0]['case_cat_name'];																						
                                    }else{
                                        $case_category_name = "";																				
                                    }
                                    echo trim($case_category_name);
                                ?>" 
                        class="inputs <?php echo ((isset($_SESSION['case_category_reqired_errors'])) && (array_key_exists("case_category_name", $_SESSION['case_category_reqired_errors']))) ? "errorsIndicatedFields" : ""; ?>" /></td>
        </tr>
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr> 
        <tr>
            <td><span class="defaultFont">Category Description</span></td>
            <td><div class="smallHeight"><!-- --></div></td>                
            <td><textarea rows="6" name="case_category_main_non_reqired[category_description]"><?php 
														if ((strstr($_SERVER['REQUEST_URI'], "add/")) && (isset($_SESSION['case_category_main_non_reqired']))){
															$category_description = $_SESSION['case_category_main_non_reqired']['category_description'];
														}elseif (isset($full_details)){
															$category_description = (isset($_SESSION['case_category_main_reqired'])) ? $_SESSION['case_category_main_reqired']['category_description'] : $full_details[0]['case_cat_description'];																						
														}else{
															$category_description = "";																				
														}
														echo trim($category_description);
													?></textarea></td>
        </tr> 
        <tr>
            <td colspan="3"><div class="smallHeight"><!-- --></div></td>                
        </tr> 
        <tr>               
            <td></td>
            <td></td>
            <td><span class="defaultFont"><input type="submit" name="case_cat_main_submit" value="Save and proceed" class="inputs submit" /></span></td>
        </tr>
    </table>
</form>
</div>
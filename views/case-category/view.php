<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <!-- Success / Warning message -->    
    <?php echo $headerDivMsg;?>
    <!--  End of the Success / Warning mesage -->    
    <div class="headerTopicContainer defaultFont boldText">
        <?php if ($tot_page_count != 0):?>    
			<div class="out-of">Page <?php echo $cur_page;?> of <?php echo $tot_page_count;?></div>   
        <?php endif;?>  
        <span class="headerTopicSelected">All Case Categories</span>
    </div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <div id="pfacTableDatContainer" align="center">
           <?php if ($invalidPage != true):?>           
            <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" class="tbs-main-users">
                <thead>
                    <th align="center"><div class="actionTableFieldTd3"><span class="defaultFont">Action</span></div></th>            
                    <th width="150"><span class="defaultFont"><?php echo ($all_case_categories_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=case_cat".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Case Category Name ".((isset($_GET['sort']) && $_GET['sort'] == "case_cat") ? $img : "")."</a>" : "Case Category Name :";?></a></span></th>
                    <th width="350"><span class="defaultFont"><?php echo ($all_case_categories_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=case_cat_desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Case Category Description ".((isset($_GET['sort']) && $_GET['sort'] == "case_cat_desc") ? $img : "")."</a>" : "Case Category Description";?></span></th>
                </thead>
                <?php for($i=0; $i<count($all_case_categories); $i++):?>
                <tr>
                    <td width="15%"><div align="left" class="actionTableFieldTd">
                    
                    <?php foreach($action_panel_menu as $eachMenu):?>                

							<?php if ($eachMenu['menu_text'] == "Delete"):?>                             
                         
                                <a onclick="return ask_for_delete_record('<?php echo $site_config['base_url'];?>case-category/delete/?cat_id=<?php echo $all_case_categories[$i]['case_cat_id'];?>')"                                
                                   title="<?php echo $eachMenu['menu_text'];?>" 
                                   href=""><?php echo $eachMenu['menu_img'];?></a>                    

                            <?php else:?>                                       
                    
                                <a title="<?php echo $eachMenu['menu_text'];?>" 
                                   href="<?php echo $eachMenu['menu_url'];?>cat_id=<?php echo $all_case_categories[$i]['case_cat_id'].
                                   (isset($_GET['page']) ? "&page=".$_GET['page'] : "");?>"><?php echo $eachMenu['menu_img'];?></a>                    

                            <?php endif;?>                            
                        
                    <?php endforeach;?>
                    
                    </div></td>
                    
                    <td><span title="<?php echo $all_case_categories[$i]['case_cat_name'];?>" class="defaultFont"><?php echo $all_case_categories[$i]['case_cat_name'];?></span></td>
                    <td><span title="<?php echo $all_case_categories[$i]['case_cat_description'];?>" class="defaultFont"><?php echo ($all_case_categories[$i]['case_cat_description'] != "") ?  $all_case_categories[$i]['case_cat_description'] : "--";?></span></td>
                </tr>					
                <?php endfor;?>
            </table>
            <?php else:?>  
	            <div class="someWidth6 floatLeft"><span class="defaultFont boldText specializedTexts">No Records !</span></div>
			<?php endif;?>                  
        </div>
    </div>
</div>
<?php if ($invalidPage != true):?>
	<?php if ($tot_page_count > 1):?>   
    <div id="pagination_wrap">
        <div id="pagination" align="center">
    <!-- This is the page jumping secte box and at the moment it has been disbaled and will be enables if requested -->    
    <?php /*?>        
    <div id="jump_to_page_wrapper" class="floatLeft">
        <select id="jump_to_page" class="smallDropDownMenu" onchange="got_to_this_page('<?php echo $jumpPath;?>');">
        <?php if ($tot_page_count == 0):?>
            <option value="">Page no</option>            
        <?php elseif (($tot_page_count == 1) && (!isset($_GET['page']))):?>
            <option value="">Page no</option>                    
            <option value="1" selected="selected">1</option>                    
        <?php else:?>    
            <option value="">Page no</option>                    
            <?php for($i=1; $i<=$tot_page_count; $i++):?>
                <option value="<?php echo $i;?>" <?php echo ($i == $_GET['page']) ? "selected='selected'" : "";?>><?php echo $i;?></option>                
            <?php endfor;?>
        <?php endif;?>
        </select>
    </div><?php */
    ?>           
            <div class="paginationContainer"><?php echo $pagination;?></div>
        </div>
    </div>
     <?php endif;?>
<?php endif;?>    	 
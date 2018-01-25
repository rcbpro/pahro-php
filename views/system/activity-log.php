<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <div class="headerTopicContainer defaultFont boldText">
        <?php if ($tot_page_count != 0):?>    
            <div class="out-of">Page <?php echo $cur_page;?> of <?php echo $tot_page_count;?></div>   
        <?php endif;?>  
        <span class="headerTopicSelected">Activity Log</span>
    </div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
        <div id="pfacTableDatContainer" align="center">
           <?php if ($invalidPage != true):?>           
            <table border="1" bordercolor="#e3c9e4" cellpadding="0" cellspacing="0" class="tbs-main-users">
                <thead>
                    <th><span class="defaultFont"><?php echo ($allLogs_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=past".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"> No ".((isset($_GET['sort']) && $_GET['sort'] == "past") ? $img : "")."</a>" : " Past Activity No";?></a></span></th>                
                    <th width="75"><span class="defaultFont"><?php echo ($allLogs_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=u_name".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Username ".((isset($_GET['sort']) && $_GET['sort'] == "u_name") ? $img : "")."</a>" : " Username";?></a></span></th>
                    <th><span class="defaultFont"><?php echo ($allLogs_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=act_desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Activity Description ".((isset($_GET['sort']) && $_GET['sort'] == "act_desc") ? $img : "")."</a>" : " Activity Description";?></span></th>
                    <th width="264"><span class="defaultFont"><?php echo ($allLogs_count > 1) ? "<a class=\"soringMenusLink\" href=\"?sort=time".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\">Date &amp; Time </a>".((isset($_GET['sort']) && $_GET['sort'] == "time") ? $img : "")."" : " Date &amp; Time";?></span></th>
                </thead>
                <?php for($i=0; $i<count($allLogs); $i++):?>
                <?php
					$act_desc = $allLogs[$i]['action_type_desc'];
					$act_desc = str_replace("$$$$$","'",$act_desc);
				?>
                <tr>
                    <td><span title="<?php echo $allLogs[$I]['id'];?>" class="defaultFont"><?php echo $allLogs[$i]['id'];?></span></td>                
                    <td><span title="<?php echo $allLogs[$i]['username'];?>" class="defaultFont"><?php echo $allLogs[$i]['username'];?></span></td>
                    <td class="a-left"><span title="<?php echo $act_desc;?>" class="defaultFont"><?php echo $act_desc;?></span></td>
                    <td><span title="<?php echo $allLogs[$i]['date_time'];?>" class="defaultFont"><?php echo AppViewer::format_date_with_time($allLogs[$i]['date_time']);?></span></td>
                </tr>					
                <?php endfor;?>
            </table>
            <?php else:?>  
	            <div class="someWidth6 floatLeft"><span class="defaultFont boldText specializedTexts">Invalid page !</span></div><div class="someWidth6 boldText floatLeft specializedTexts2"><span class="defaultFont">Redirecting ... </span></div>
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
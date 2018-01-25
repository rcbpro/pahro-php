<div id="static_header">
    <!-- Breadcrumb div message -->
    <?php echo $breadcrumb;?>
    <!--  End of the Breadcrumb div mesage -->
    <!-- This is the success/error message to be diplayed -->
    <?php global $headerDivMsg; echo $headerDivMsg;?>
    <!-- End of the success or error message -->
    <div class="headerTopicContainer defaultFont boldText"><span class="headerTopicSelected"><?php echo $printHtml;?></span></div>
</div>
<div id="dataInputContainer_wrap">
    <div class="dataInputContainer">
    	<!-- Which subview to load -->
		<?php 
            if (isset($_GET['mode'])){
                $whichFormToInclude = "mode".DS.$_GET['mode'];		
            }else{
                $whichFormToInclude = "mode/main";					
            }	
            include $whichFormToInclude.".php";	
        ?>
        <!-- End of the sub view -->
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
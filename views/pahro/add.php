<script type="text/javascript" language="javascript">
$(document).ready(function(){
////////////////////////////////////////Staff
//user
	//view	
	$("#chk_per_staff_18").click(function (){
		if (!$(this).is(':checked')){
			$("#chk_per_staff_19, #chk_per_staff_20, #chk_per_staff_21, #chk_per_staff_65").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_19, #chk_per_staff_20, #chk_per_staff_21, #chk_per_staff_65").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_18").attr('checked',true);
		}
	});
	
//case
	//view	
	$("#chk_per_staff_1").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_2, #chk_per_staff_3, #chk_per_staff_4").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_2, #chk_per_staff_3, #chk_per_staff_4").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_1").attr('checked',true);
		}
	});

//case notes
	//view	
	$("#chk_per_staff_62").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_63, #chk_per_staff_64, #chk_per_staff_66").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_63, #chk_per_staff_64, #chk_per_staff_66").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_62").attr('checked',true);
		}
	});


//case category
	//view	
	$("#chk_per_staff_5").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_6, #chk_per_staff_7, #chk_per_staff_8").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_6, #chk_per_staff_7, #chk_per_staff_8").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_5").attr('checked',true);
		}
	});

//client
	//view	
	$("#chk_per_staff_9").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_10, #chk_per_staff_11, #chk_per_staff_12").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_10, #chk_per_staff_11, #chk_per_staff_12").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_9").attr('checked',true);
		}
	});

//client notes
	//view	
	$("#chk_per_staff_67").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_68, #chk_per_staff_69, #chk_per_staff_70").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_68, #chk_per_staff_69, #chk_per_staff_70").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_67").attr('checked',true);
		}
	});

	
//counter-party
	//view	
	$("#chk_per_staff_13").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_14, #chk_per_staff_15, #chk_per_staff_16").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_14, #chk_per_staff_15, #chk_per_staff_16").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_13").attr('checked',true);
		}
	});
//counter-party notes
	//view	
	$("#chk_per_staff_71").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_73, #chk_per_staff_74, #chk_per_staff_75").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_73, #chk_per_staff_74, #chk_per_staff_75").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_71").attr('checked',true);
		}
	});


//legislations
	//view	
	$("#chk_per_staff_22").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_23, #chk_per_staff_24, #chk_per_staff_25").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_23, #chk_per_staff_24, #chk_per_staff_25").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_22").attr('checked',true);
		}
	});

//research
	$("#chk_per_staff_26").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_27, #chk_per_staff_28, #chk_per_staff_29").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_27, #chk_per_staff_28, #chk_per_staff_29").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_26").attr('checked',true);
		}
	});

//handbook
	$("#chk_per_staff_30").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_31, #chk_per_staff_33").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_31, #chk_per_staff_33").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_30").attr('checked',true);
		}
	});
	
//Parliamentary Submissions
	$("#chk_per_staff_34").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_35, #chk_per_staff_36, #chk_per_staff_57").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_35, #chk_per_staff_36, #chk_per_staff_57").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_34").attr('checked',true);
		}
	});
	
//organizations
	$("#chk_per_staff_37").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_38, #chk_per_staff_30, #chk_per_staff_40").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_38, #chk_per_staff_39, #chk_per_staff_40").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_37").attr('checked',true);
		}
	});

//minutes
	$("#chk_per_staff_41").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_42, #chk_per_staff_43, #chk_per_staff_44").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_42, #chk_per_staff_43, #chk_per_staff_44").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_41").attr('checked',true);
		}
	});

//templates
	$("#chk_per_staff_45").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_46, #chk_per_staff_47, #chk_per_staff_48").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_46, #chk_per_staff_47, #chk_per_staff_48").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_45").attr('checked',true);
		}
	});

//Agreements & Partnerships
	$("#chk_per_staff_49").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_50, #chk_per_staff_51, #chk_per_staff_52").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_50, #chk_per_staff_51, #chk_per_staff_52").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_49").attr('checked',true);
		}
	});
	
//Miscellaneous
	$("#chk_per_staff_53").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_54, #chk_per_staff_55, #chk_per_staff_56").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_54, #chk_per_staff_55, #chk_per_staff_56").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_53").attr('checked',true);
		}
	});
	
//Social Justice
	$("#chk_per_staff_58").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_59, #chk_per_staff_60, #chk_per_staff_61").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_59, #chk_per_staff_60, #chk_per_staff_61").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_58").attr('checked',true);
		}
	});



////////////////////////////////////////Vol
//case
	//view	
	$("#chk_per_vol_1").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_3").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_3").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_1").attr('checked',true);
		}
	});

//case notes
	//view	
	$("#chk_per_vol_62").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_63").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_63").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_62").attr('checked',true);
		}
	});


//case category
	//view	
	$("#chk_per_vol_5").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_6, #chk_per_vol_7, #chk_per_vol_8").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_6, #chk_per_vol_7, #chk_per_vol_8").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_5").attr('checked',true);
		}
	});

//client
	//view	
	$("#chk_per_vol_9").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_10, #chk_per_vol_11, #chk_per_vol_12").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_10, #chk_per_vol_11, #chk_per_vol_12").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_9").attr('checked',true);
		}
	});

//client notes
	//view	
	$("#chk_per_vol_67").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_68").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_68").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_67").attr('checked',true);
		}
	});

	
//counter-party
	//view	
	$("#chk_per_vol_13").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_14, #chk_per_vol_15, #chk_per_vol_16").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_14, #chk_per_vol_15, #chk_per_vol_16").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_13").attr('checked',true);
		}
	});

//counter-party notes
	//view	
	$("#chk_per_vol_71").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_73").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_73").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_71").attr('checked',true);
		}
	});



//legislations
	//view	
	$("#chk_per_vol_22").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_23, #chk_per_vol_24, #chk_per_vol_25").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_23, #chk_per_vol_24, #chk_per_vol_25").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_22").attr('checked',true);
		}
	});

//research
	$("#chk_per_vol_26").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_27, #chk_per_vol_28, #chk_per_vol_29").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_27, #chk_per_vol_28, #chk_per_vol_29").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_26").attr('checked',true);
		}
	});

//handbook
	$("#chk_per_vol_30").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_31, #chk_per_vol_32, #chk_per_vol_33").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_31, #chk_per_vol_32, #chk_per_vol_33").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_30").attr('checked',true);
		}
	});
	
//Parliamentary Submissions
	$("#chk_per_vol_34").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_35, #chk_per_vol_36, #chk_per_vol_57").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_35, #chk_per_vol_36, #chk_per_vol_57").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_34").attr('checked',true);
		}
	});
	
//organizations
	$("#chk_per_vol_37").click(function () {
		/*
		if (!$(this).is(':checked')){
			$("#chk_per_vol_38, #chk_per_vol_30, #chk_per_vol_40").attr('checked',false);
		}
		*/
	});
	
	//add,edit and delete
	$("#chk_per_vol_38, #chk_per_vol_39, #chk_per_vol_40").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_37").attr('checked',true);
		}
	});

//minutes
	$("#chk_per_vol_41").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_42, #chk_per_vol_43, #chk_per_vol_44").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_42, #chk_per_vol_43, #chk_per_vol_44").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_41").attr('checked',true);
		}
	});

//templates
	$("#chk_per_vol_45").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_46, #chk_per_vol_47, #chk_per_vol_48").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_46, #chk_per_vol_47, #chk_per_vol_48").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_45").attr('checked',true);
		}
	});

//Agreements & Partnerships
	$("#chk_per_vol_49").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_50, #chk_per_vol_51, #chk_per_vol_52").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_50, #chk_per_vol_51, #chk_per_vol_52").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_49").attr('checked',true);
		}
	});
	
//Miscellaneous
	$("#chk_per_vol_53").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_54, #chk_per_vol_55, #chk_per_vol_56").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_54, #chk_per_vol_55, #chk_per_vol_56").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_53").attr('checked',true);
		}
	});
	
//Social Justice
	$("#chk_per_vol_58").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_59, #chk_per_vol_60, #chk_per_vol_61").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_59, #chk_per_vol_60, #chk_per_vol_61").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_58").attr('checked',true);
		}
	});
//////////////////////////////////////////////



});
</script>

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
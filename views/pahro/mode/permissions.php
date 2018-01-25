<script type="text/javascript" language="javascript">
$(document).ready(function(){
////////////////////////////////////////Staff
//user
	//view	
	$("#chk_per_staff_14").click(function (){
		if (!$(this).is(':checked')){
			$("#chk_per_staff_15, #chk_per_staff_16, #chk_per_staff_17").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_15, #chk_per_staff_16, #chk_per_staff_17").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_14").attr('checked',true);
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

//client
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
	
//counter-party
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

//legislations
	//view	
	$("#chk_per_staff_18").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_19, #chk_per_staff_20, #chk_per_staff_21").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_19, #chk_per_staff_20, #chk_per_staff_21").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_18").attr('checked',true);
		}
	});

//research
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

//handbook
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
	
//Parliamentary Submissions
	$("#chk_per_staff_30").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_31, #chk_per_staff_32, #chk_per_staff_53").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_31, #chk_per_staff_32, #chk_per_staff_53").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_30").attr('checked',true);
		}
	});
	
//organizations
	$("#chk_per_staff_33").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_34, #chk_per_staff_35, #chk_per_staff_36").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_34, #chk_per_staff_35, #chk_per_staff_36").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_33").attr('checked',true);
		}
	});

//minutes
	$("#chk_per_staff_37").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_staff_38, #chk_per_staff_39, #chk_per_staff_40").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_staff_38, #chk_per_staff_39, #chk_per_staff_40").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_staff_37").attr('checked',true);
		}
	});

//templates
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

//Agreements & Partnerships
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
	
//Miscellaneous
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



////////////////////////////////////////Vol
//user
	//view	
	$("#chk_per_vol_14").click(function (){
		if (!$(this).is(':checked')){
			$("#chk_per_vol_15, #chk_per_vol_16, #chk_per_vol_17").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_15, #chk_per_vol_16, #chk_per_vol_17").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_14").attr('checked',true);
		}
	});
	
//case
	//view	
	$("#chk_per_vol_1").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_2, #chk_per_vol_3, #chk_per_vol_4").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_2, #chk_per_vol_3, #chk_per_vol_4").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_1").attr('checked',true);
		}
	});

//client
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
	
//counter-party
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

//legislations
	//view	
	$("#chk_per_vol_18").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_19, #chk_per_vol_20, #chk_per_vol_21").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_19, #chk_per_vol_20, #chk_per_vol_21").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_18").attr('checked',true);
		}
	});

//research
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

//handbook
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
	
//Parliamentary Submissions
	$("#chk_per_vol_30").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_31, #chk_per_vol_32, #chk_per_vol_53").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_31, #chk_per_vol_32, #chk_per_vol_53").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_30").attr('checked',true);
		}
	});
	
//organizations
	$("#chk_per_vol_33").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_34, #chk_per_vol_35, #chk_per_vol_36").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_34, #chk_per_vol_35, #chk_per_vol_36").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_33").attr('checked',true);
		}
	});

//minutes
	$("#chk_per_vol_37").click(function () {
		if (!$(this).is(':checked')){
			$("#chk_per_vol_38, #chk_per_vol_39, #chk_per_vol_40").attr('checked',false);
		}
	});
	
	//add,edit and delete
	$("#chk_per_vol_38, #chk_per_vol_39, #chk_per_vol_40").click(function () {
		if ($(this).is(':checked')){
			$("#chk_per_vol_37").attr('checked',true);
		}
	});

//templates
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

//Agreements & Partnerships
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
	
//Miscellaneous
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

//////////////////////////////////////////////



});
</script>
dfsdfsd
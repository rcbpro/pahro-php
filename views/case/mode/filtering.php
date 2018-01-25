<?php
	$textvalue = 'Start typing a name';
	$blankvalue = '<div class="d-blank">You have not selected anyone.</div>';
	
	
	// all volunteers from user table
	$ary_names = array();
	foreach($refined_volunteers as $vol){
		$ary_names[$vol['id']] = $vol['username'];
	}
	//

	//currently assined volunteers from db
	$ary_assined_vols = array();
	foreach($volunteers_currently_working as $vol){
		$ary_assined_vols[] = $vol['vol_id'];
	}
	//
	
	$ary_vols = array();
	foreach($ary_names as $key => $val){
		$ary_vols[] = $key;
	}
	
	
	//detect the page
	$is_edit = false;
	$filter_add = '';
	if(strstr($_SERVER['REQUEST_URI'],'edit')){
		$is_edit = true;
	}
	else{
		$filter_add = 'class="filter-add"';
	}
	
?>
<?php

//page loads
		if(!isset($_POST['case_main_submit'])){
		
			$ary_for_select_box = array_diff($ary_vols,$ary_assined_vols);
			$ary_for_added_box = array();
			$ary_for_assined_box = $ary_assined_vols;
			//echo count($ary_vols);
		}
		else{//after hit save buttom
			$chk_ary_added = @$_POST['chkad'];
			if(!is_array($chk_ary_added)){$chk_ary_added = array();}
			$ary_for_select_box = @array_diff($ary_vols,$ary_assined_vols,$chk_ary_added);
			$ary_for_added_box = $chk_ary_added;
			$ary_for_assined_box = $ary_assined_vols;
			//////////////////////////////////////////
			$ary_db_ad = @$_POST['chkad'];
			$ary_db_as = @$_POST['chkas'];
			if(!is_array($ary_db_ad)){$ary_db_ad = array();}
			if(!is_array($ary_db_as)){$ary_db_as = array();}
			$ary_for_db = array_unique(array_merge($ary_db_ad,$ary_db_as));
		}
		/* End - this is for the volunteers adding for the cases section */

?>


<script type="text/javascript" language="javascript" src="<?php echo WEB_URL;?>/public/js/jquery.uitablefilter.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo WEB_URL;?>/public/js/jquery.highlight.js"></script>

<script type="text/javascript" language="javascript">
function dosearch(){
	var theTable = $("#tbl-filter-se");
	
	//theTable.find("span").find("span:eq(1)").mousedown(function(){});	
	//$("#txt-filter").focus(function() {
		$.uiTableFilter( theTable, this.value );
		//IE
		ie();
		
	//})
}

function setdef(){	
	if($("#d-filter-ad .d-ad-row").length == 0){
		$("#d-filter-ad").html('<?php echo $blankvalue;?>');
	}
	
	$("#tbl-filter-se").removeHighlight().highlight($("#txt-filter").val());
}

function ie(){
	/*
	if(navigator.appName == "Microsoft Internet Explorer"){
		var abc = $("#tbl-filter-se").find("tbody > tr:visible").css("display","block");
	}
	*/
}

$(document).ready(function(){
	setdef();
	
	var theTable = $("#tbl-filter-se");	
	$("#txt-filter").keyup(function(){
		$.uiTableFilter( theTable, this.value );
		
		//IE
		ie();
		
		$("#tbl-filter-se").removeHighlight()
		if($.trim(this.value) != ""){
			$("#tbl-filter-se").highlight(this.value);
		}
		
	})
	
	//from selected bolx
	$("#tbl-filter-se input:checkbox").live("click",function(){		
		var v_id = $(this).val();
		var v_name = $(this).parents("tr").find("span:eq(0)").attr("title");
		var v_html = '<div class="d-ad-row"><div class="d-ad-rm"><div id="'+v_id+'" title="Remove"></div><input type="checkbox" checked="checked" value="'+v_id+'" class="chkad" name="chkad[]" /></div><div class="d-ad-lbl"><span title="'+v_name+'">'+v_name+'</span></div></div>';
		
		if($("#d-filter-ad .d-ad-row").length == 0){$("#d-filter-ad").html("");}
		
		$("#d-filter-ad").html(v_html + $("#d-filter-ad").html());		
		$(this).attr("checked",false);
		if($("#txt-filter").val() != "<?php echo $textvalue;?>"){$("#txt-filter").focus();}
		$(this).parents("tr:first").remove();
		dosearch();
	});
	//
	
	//from added box
	$(".d-ad-rm div").live("click",function(){
		var v_id = $(this).attr("id");
		var v_name = $(this).parents("div").parents("div").find("span").html();
		
		var v_html = '<tr><td><div class="d-se-row"><div class="d-se-chk"><input type="checkbox" value="'+v_id+'"></div><div class="d-se-lbl"><span title="'+v_name+'">'+v_name+'</span></div></div></td></tr>';
		$("#tbl-filter-se tbody").html(v_html + $("#tbl-filter-se tbody").html());
		$(this).parents("div").parents("div .d-ad-row").remove();
		if($("#txt-filter").val() != "<?php echo $textvalue;?>"){$("#txt-filter").focus();}
		dosearch();
		setdef();
		
	});
	//
	
	//text box
	$("#txt-filter").focus(function() {
		if($(this).val() == "<?php echo $textvalue;?>"){
			$(this).val("").removeClass("clr");			
		}
	});
	
	$("#txt-filter").blur(function() {
		if($(this).val() == ""){
			$(this).val("<?php echo $textvalue;?>").addClass("clr");			
		}
	});
	//
	
	
	$("#d-txt #btn-clear").click(function() {
		$("#tbl-filter-se tr").css("display","block");		
		$("#txt-filter").focus().val("").focus();
		$("#tbl-filter-se").removeHighlight();
		
		//IE
		ie();
	});
	
	
	if(navigator.appName == "Microsoft Internet Explorer"){
		$("#btn-clear").css("display","none");
	}
	
	
	

});
</script>
<form action="" method="post">
<fieldset id="filter-main-wrap">
	<legend>&nbsp;Volunteers&nbsp;</legend>
    <div id="filter-main" <?php echo $filter_add;?>>
    	<div id="d-txt">
            <div style="float:left;height:20px;"><input type="text" id="txt-filter" class="clr" value="<?php echo $textvalue;?>" /></div>
        	<div id="btn-clear" title="Clear"></div>
        </div>
        <div id="d-se-bx">
        	<table id="tbl-filter-se" cellpadding="0" cellspacing="0">
            <tbody>
            <?php
				$ary_sort = array();
				foreach($ary_for_select_box as $vol_id){
					$ary_sort[$vol_id] = $ary_names[$vol_id];
				}
				asort($ary_sort);//sort the array				
				foreach($ary_sort as $id => $name){
					echo '<tr><td>';
					echo '<div class="d-se-row">';
					echo '<div class="d-se-chk"><input type="checkbox" value="'.$id.'" /></div>';
					echo '<div class="d-se-lbl"><span title="'.$name.'">'.$name.'</span></div>';
					echo '</div>';
					echo '</td></tr>';
				}
				
			?>
            </tbody>
            </table>
        </div>
        
        <div id="d-ad-bx">
        	<div id="d-filter-ad">
            <?php
				if(count($ary_for_added_box) > 0){				
					foreach($ary_for_added_box as $vol_id){					
						echo '<div class="d-ad-row">';
							echo '<div class="d-ad-rm">';
								echo '<div id="'.$vol_id.'" title="Remove"></div>';
									echo '<input type="checkbox" checked="checked" value="'.$vol_id.'" class="chkad" name="chkad[]" />';
								echo '</div>';
							echo '<div class="d-ad-lbl"><span title="'.$ary_names[$vol_id].'">'.$ary_names[$vol_id].'</span></div>';
						echo '</div>';
					}
				}
			?>
            </div>
        </div>        
    </div>
    
    <?php if($is_edit){?>
    <div id="d-current-vols">
		<div class="header">Volunteers currently working on</div>
        <div class="list">
        
        	<?php
				if(count($ary_assined_vols) > 0){
					foreach($ary_assined_vols as $vol_id){
						$checked = '';
						if(!isset($_POST['case_main_submit'])){
							$checked = 'checked="checked"';
						}
						else{
							$ary_chk_post = @$_POST['chkas'];
							if(@in_array($vol_id,$ary_chk_post)){
								$checked = 'checked="checked"';
							}
						}
						echo '<div class="d-se-row">';
						echo '<div class="d-se-chk"><label><input type="checkbox" name="chkas[]" '.$checked.' value="'.$vol_id.'" />'.$ary_names[$vol_id].'</label></div>';
						echo '</div>';
					}
				}
				else{
					echo '<div class="d-blank">No volunteers are assigned.</div>';
				}
			?>

        </div>
    </div>
    <?php }?>
    
</fieldset>
<!--<input type="submit" value="Save" name="case_main_submit" />-->
</form>
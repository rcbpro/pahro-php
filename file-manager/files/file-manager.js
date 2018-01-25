$(document).ready(function() {
	
	//file manager	
	oTable = $('#tbl-files').dataTable({
		"bJQueryUI": true,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers"
	});
	//end
	
	
	//multiple upload
		// dialog
	$("#d-m-u").dialog("destroy");
	$("#d-m-u").dialog({
		autoOpen: false,
		height: 444,
		hide:'fade',
		width: 440,
		modal: true,
		closeOnEscape: false,
		buttons: {
		},
		close: function(){
		}
		
	});


	$('#upload_box').hide();
	$('#upload_button').click(function(){
		$('#upload_button').fadeOut('fast', function(){
			$('#upload_box').fadeIn('fast');
		});
	});

	$('#close_upload').click(function() {
		$('#upload_box').fadeOut('fast', function() {
			$('#upload_button').fadeIn('fast');
		});
		return false;
	});


	$('#folder_box').show();
	$('#folder_button').click(function() {
		$('#folder_button').fadeOut('fast', function() {
			$('#folder_box').fadeIn('fast');
			$('#txt-create-folder').focus();
		});
	});

	$('#close_folder').click(function() {
		$('#folder_box').fadeOut('fast', function() {
			$('#folder_button').fadeIn('fast');
		});
		return false;
	});
	
	$(".close").click(
		function () {
			$(this).fadeTo(400, 0, function () { // Links with the class "close" will close parent
				$(this).slideUp(400);
			});
		return false;
		}
	);	

	var pre_color = "";
	$("#tbl-files tr").hover(
	function (){
			pre_color = $(this).css("background-color");
			$(this).css("background-color","#00FFCC");
		},
	function(){
		$(this).css("background-color",pre_color);
	});	
	
	
	$("#d-ac-fm div").hover(function (){$(this).fadeTo("medium", .7);},function(){$(this).fadeTo("fast", 1);});
	
	//$("#d-file-wrap").show();
	setTimeout('$("#d-file-wrap").fadeIn("medium");',1000);

	

});
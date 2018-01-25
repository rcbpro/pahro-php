<?php header('Content-type: application/javascript');?>
// JavaScript Document

var newWindow;
var checked = false;
function popup(url){
	newWindow = window.open(url,'name','height=1100,width=695,vAlign=middle,hAlign=center,scrollbars=yes,resizable=no,screenx=335,screeny=250');
	if (window.focus) {newWindow.focus()}
}
function ask_for_delete_record(path){
	if (confirm("Do you really want to delete this record ?")){
		location.href = path;
    }		
    return false;
}
function set_status(pid,pstatus,pg){
	if(pstatus == 1){
    	msg = 'activate';
    }
    else{
    	msg = 'deactivate';
    }
	if (confirm("Are you sure you want to "+msg+" this user ?")){
		location.href = "http://<?php echo $_SERVER['HTTP_HOST'];?>/pahro/status/?pahro_id="+pid+"&status="+pstatus+"&pg="+pg;
    }		
    return false;
}

function open_print(nid){
	var purl = "http://<?php echo $_SERVER['HTTP_HOST'];?>/views/previews/print.php?note_id="+nid;
    p_win = window.open (purl,"pwin","menubar=0,resizable=0,width=800,height=600");
    
	
}

function submit_the_search_query(controller){

	query = "";
    if ((controller != "case") && (controller != "case-category") && (controller != "search") && (controller != "file-manager")){    
        if (($("#first_name").val != "") && ($("#surname").val != "")){
            query += "?fname="+$("#first_name").val() + "&sname="+$("#surname").val();
        }else{
            if ($("#first_name").value != ""){
                query += "?fname="+$("#first_name").val();	
            }else if ($("#surname").value != ""){
                query += "?sname="+$("#surname").val();	
            }else{
                query += "";	
            }
        }
    }else if ((controller == "case") || (controller == "file-manager")){    	
        if ($("#case_id").value != ""){
            query += "?case_id="+$("#case_id").val();
        }else{
            query += "";	
        }
    }else if (controller == "case-category"){
        if ($("#cat_name").value != ""){
            query += "?cat_name="+$("#cat_name").val();
        }else{
            query += "";	
        }
    }
	if (query != ""){

    
    	if ((controller == "search") || (controller == "file-manager")){
        	controller = "case";
        }
		location.href = "http://<?php echo $_SERVER['HTTP_HOST'];?>/" + controller + "/search/" + query;	
	}
}

//layout auto resizing
    function set_dim(){
        //width
        var min_res = 960;
        var left_w = $("#sidebarmenu_wrap").outerWidth(true) + 48 ;
        $("#dataInputContainer_wrap").css("width",min_res -left_w);
        $("#main_container").css("width",min_res);
        
        if($(window).width() > min_res){
            var client_width = $(document).width() - left_w;
            $("#dataInputContainer_wrap").width(client_width);
            $("#main_container").css("width","100%");
            //alert($(window).width());
        }		
        //height
        var footer = 20;
        var static_header = $("#static_header").outerHeight(true);
        var pagi = $("#pagination_wrap").outerHeight();
        var content_height = ($(window).height() - footer - pagi - static_header - 133);
        $("#dataInputContainer_wrap").height(content_height);
        $("#sidebarmenu_wrap").height($(window).height() - footer - 128);
    }
    
    $(window).resize(function() {
        set_dim();
    });
//end


$(document).ready(function(){
	//history  
    
    window.onload = function () {
        var country_code = $("#current_work_env").val();   
        var country_name = $("#current_work_env").text();		
		puttingToSession(country_code, country_name, true);
	};

	$('select[name=current_work_env]').change(function(){
        var country_code = $("#current_work_env option:selected").val();   
        var country_name = $("#current_work_env option:selected").text();
		puttingToSession(country_code, country_name, false);
    });
    
    
    $("#s-h-sa").click(function () {
		$("#d-h-sa").dialog("open");
	});
    $("#s-h-ga").click(function () {
		$("#d-h-ga").dialog("open");
	});
    
    
	$("#t-b-import input").click(function () {
		$("#im-loader").css("display","block");
	})

    //end
	

	//search
    $("#first_name, #surname, #case_id, #cat_name").keypress(function(e)  {
        if (e.which == 13 ){
          submit_the_search_query($("#searchController").val());
        }
    });
    //end

    //misc
    $(".tbs-main-address-book tr").hover(function (){$(this).css("background-color","#dde9e5");},function(){$(this).css("background-color","#dddddd");});
    $(".tbs-main-users tr").hover(function (){$(this).css("background-color","#dde9e5");},function(){$(this).css("background-color","");});
    $(".tbs-main-notes tr").not($("#tr-filter")).hover(function (){$(this).css("background-color","#dde9e5");},function(){$(this).css("background-color","");});
    $(".tbs-main-log tr").hover(function (){$(this).css("background-color","#dde9e5");},function(){$(this).css("background-color","");});
    $(".noteTextArea").focus();
    
    $(".view-row").hover(function (){$(this).css("background-color","#dde9e5");},function(){$(this).css("background-color","");});
    $("#d-h-sa .row, #d-h-ga .row").hover(function (){$(this).css("background-color","#a6b1c4");},function(){$(this).css("background-color","#7889a6");});
    
   	$(".submit, .btn-filter, #s-h-sa, #s-h-ga, #queue-file-case, #queue-file-client, #queue-file-counter-party, #btn-search").hover(function (){$(this).fadeTo("medium", .7);},function(){$(this).fadeTo("fast", 1);});

    //end misc
    
});

function puttingToSession(country_code, country_name, onload) {

    $.ajax({
        type: "POST",
        url: 'http://<?php echo $_SERVER['HTTP_HOST'] . str_replace("common.js.php", "data.php", $_SERVER['REQUEST_URI']);?>',
        data: ({ country_code: country_code, country_name: country_name }),
        dataType: "html",
        success: function(data) {
        	if (!onload){ 
				location.reload();	
            }    
        },
        error: function() {
            alert('Error occured');
        }
    });
}

///// permi
function change_per(val){
    if(val == 1){
        $("#permissions-vol").css("display","none");
        $("#permissions-staff").css("display","block");
    }
    else if(val == 2){
        $("#permissions-staff").css("display","none");
        $("#permissions-vol").css("display","block");
    }

}
/*function EnableDisableDIV(){
      if ($("#searchTick").attr("checked")){
            $("#searchBox").slideDown()
      }else{
            $("#searchBox").slideUp()
      }             
 }          
*/
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

/*function EnableDisableDIV(){
  if ($("#searchTick").attr("checked")){
        $("#searchBox").slideDown()
  }else{
        $("#searchBox").slideUp()
  }             
}          */


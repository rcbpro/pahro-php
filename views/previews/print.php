<?php
	//error_reporting(E_ALL ^ E_NOTICE);
	//ini_set('display_errors', '1');	
	
	include $_SERVER['DOCUMENT_ROOT'].'/models/preview.php';	
	// objects instantiation
	$previewModel = new Preview_model();
	// Retreive the case details related to the requested note id
	$case_details = $previewModel->retrieve_some_details_for_the_given_note_id(trim($_GET['note_id']));
	$case_details = $case_details[0];
	//echo "<pre>";
	//print_r($case_details);	
	//echo "</pre>";	
	
	
	
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"" />
<title>Print</title>

<style type="text/css">
body{
	font-family:Verdana, Geneva, sans-serif;
}
#wrap{
	margin:20px;
	width:740px;
}
#top{
	font-size:11px;
	line-height:16px;
	overflow:hidden;
	position:relative;
	border-bottom:1px solid #000;
	padding-bottom:20px;
	margin-bottom:20px;
}
#left{
	float:left;
}
#right{
	float:right;
	width:180px;
	height:90px;
}
.ofc{
	position:absolute;
	right:48px;
	bottom:42px;
	font-weight:bold;
	color:#327d9a;
}
.ln{
	margin:10px 0;
	overflow:hidden;
	font-size:12px;
}
.ln .l{
	width:80px;
	float:left;
	font-weight:bold;
}
.ln .m{
	width:10px;
	float:left;
}
.ln .r{
	width:150px;
	float:left;
}
#content{
	margin:20px 0;
	font-size:12px;
	border-top:1px solid #000;
	padding-top:20px;
	text-align:justify;
}

</style>

</head>

<body onload="window.print();window.close();">
<div id="wrap">
    <div id="top">
        <div id="left">
            Level 1 Riverside Mall; 34 Main Road<br />
            Rondebosch<br />
            Cape Town, 7700<br />
            South Africa<br />
            Tel: +27 (21) 685 1998<br />
            Mobile: +27 (82) 932 3856<br />
            Email: <a href="mailto:humanrights@projects-abroad.org.za">humanrights@projects-abroad.org.za</a><br />
            Website: <a href="mailto:http://www.pahro.org">http://www.pahro.org</a><br />
        </div>
        
        <div id="right"><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/public/images/print-logo.jpg" /></div>
        <div class="ofc">Human Rights Office</div>
        
    </div>

    <div class="ln">
		<div class="l">Date</div>
        <div class="m">:</div>
        <div class="r"><?php echo date("d, F Y");?></div>
    </div>
    
    <div class="ln">
		<div class="l">File Ref.</div>
        <div class="m">:</div>
        <div class="r"><?php echo $case_details['reference_number'];?></div>
    </div>
    
    <div class="ln">
		<div class="l">File name</div>
        <div class="m">:</div>
        <div class="r"><?php echo $case_details['case_name'];?></div>
    </div>
    
    <div id="content">
		<?php echo $case_details['note'];?>        
    </div>
    
    
</div>

</body>
</html>
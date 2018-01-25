<script type="text/javascript" src="http://devel.admin.pahro.org/public/js/swfobject.js"></script>
<script type="text/javascript" src="http://devel.admin.pahro.org/public/js/jquery.uploadify.js"></script>
<script type="text/javascript" language="javascript" src="http://devel.admin.pahro.org/public/modal/jquery.ui.core.js"></script>
<script type="text/javascript" language="javascript" src="http://devel.admin.pahro.org/public/modal/jquery.ui.widget.js"></script>
<script type="text/javascript" language="javascript" src="http://devel.admin.pahro.org/public/modal/jquery.ui.button.js"></script>
<script type="text/javascript" language="javascript" src="http://devel.admin.pahro.org/public/modal/jquery.ui.position.js"></script>
<script type="text/javascript" language="javascript" src="http://devel.admin.pahro.org/public/modal/jquery.ui.dialog.js"></script>
<script type="text/javascript" language="javascript" src="http://devel.admin.pahro.org/public/modal/jquery.effects.core.js"></script>
<script type="text/javascript" language="javascript" src="http://devel.admin.pahro.org/public/modal/jquery.effects.fade.js"></script>

<?php
//Configuration::admin_gate_keeper($_SERVER['REQUEST_URI'], $_SESSION['logged_user']['permissions']);

include(SERVER_ROOT.DS.'models/system.php');
include(SERVER_ROOT.DS.'file-manager/file-manager-func.php');

//global $getwhere;
$getwhere = ($_GET['where']);
define("ROOT", $_SERVER['DOCUMENT_ROOT'].'/file-manager/'.$getwhere);
define("DOC_ROOT",$_SERVER['DOCUMENT_ROOT'].'/file-manager');
define("WEB_ROOT",'http://'.$_SERVER['HTTP_HOST'].'/file-manager');

$chars = array('#','&','?','<','>','*','\\','/','|',':','"','+','%','!','~','{','}','[',']');

$url = $_SERVER['REQUEST_URI'];

if(substr($getwhere, -1) != '/'){//adds / to end of the url
	$getwhere .= '/';
	//echo $getwhere;exit();
	//forward($url);exit();
}

//url hackers :P
if($_GET['do'] != 'upload_file' && $_GET['do']!='create_folder' && $_GET['do']!='download_file' && $_GET['do']!='delete_file' && $_GET['do']!='edit' && $_GET['do']!= 'save_file'){
	$is_where = $_SERVER['DOCUMENT_ROOT'].'/file-manager/'.$getwhere;
	if(!file_exists($is_where) || $_GET['do']!='view_files' || !isset($getwhere) || trim($getwhere)==''){
		forward('?do=view_files&where=uploads/');exit(); 
	}
}

//first folder
$folders = explode('/',$getwhere);
$folder_first = $folders[1];

//use to track Legislation folder
$ary = explode('/',$getwhere);
$folder_last = @$ary[count($ary)-2];
$folder_last_two = @$ary[count($ary)-3];
//$folder_lasttwo = @$ary[count($ary)-4];

//--------------------------------------------------------------------------------------------------------------------------------------------------------//

//setting up permissions
$folder_names = array(1=>"Agreements and Partnerships",2=>"Handbooks",3=>"Legislation", 4=>"Minutes", 5=>"Miscellaneous", 6=>"Other Organisations", 7=>"Parliamentary Submissions", 8=>"Research Projects", 9=>"Templates", 10=>"Social Justice", 11=>"Researchs", 12=>"Diary"); 
$folder_access = array(1=>
	array(
			"folder_id" => 1,
			"folder_text" => $folder_names[1],
			"folder_permissions" => array('view'=>49, 'add'=>50, 'replace'=>51, 'delete'=>52)
		 ),
	array(
			"folder_id" => 2,
			"folder_text" => $folder_names[2],
			"folder_permissions" => array('view'=>30, 'add'=>31, 'replace'=>32, 'delete'=>33)
		 ),
	array(
			"folder_id" => 3,
			"folder_text" => $folder_names[3],
			"folder_permissions" => array('view'=>22, 'add'=>23, 'replace'=>24, 'delete'=>25)
		 ),
	array(
			"folder_id" => 4,
			"folder_text" => $folder_names[4],
			"folder_permissions" => array('view'=>41, 'add'=>42, 'replace'=>43, 'delete'=>44)
		 ),
	array(
			"folder_id" => 5,
			"folder_text" => $folder_names[5],
			"folder_permissions" => array('view'=>53, 'add'=>54, 'replace'=>55, 'delete'=>56)
		 ),
	array(
			"folder_id" => 6,
			"folder_text" => $folder_names[6],
			"folder_permissions" => array('view'=>37, 'add'=>38, 'replace'=>39, 'delete'=>40)
		 ),
	array(
			"folder_id" => 7,
			"folder_text" => $folder_names[7],
			"folder_permissions" => array('view'=>34, 'add'=>35, 'replace'=>36, 'delete'=>57)
		 ),
	array(
			"folder_id" => 8,
			"folder_text" => $folder_names[8],
			"folder_permissions" => array('view'=>26, 'add'=>27, 'replace'=>28, 'delete'=>29)
		 ),
	array(
			"folder_id" => 9,
			"folder_text" => $folder_names[9],
			"folder_permissions" => array('view'=>45, 'add'=>46, 'replace'=>47, 'delete'=>48)
		 ),
	array(
			"folder_id" => 10,
			"folder_text" => $folder_names[10],
			"folder_permissions" => array('view'=>58, 'add'=>59, 'replace'=>60, 'delete'=>61)
		 ),
	array(
			"folder_id" => 11,
			"folder_text" => $folder_names[11],
			"folder_permissions" => array('view'=>76, 'add'=>77, 'replace'=>78, 'delete'=>79)
		 ),
	array(
			"folder_id" => 12,
			"folder_text" => $folder_names[12],
			"folder_permissions" => array('view'=>80, 'add'=>81, 'replace'=>82, 'delete'=>83)
		 )
		 
	);


#Main function to list files
function list_files($getwhere = '/') {
	global $folder_first;
	global $folder_last;
	global $folder_last_two;
	global $folder_access;
	global $folder_names;
	global $row;
	
	global $ary_all_files;
	$where = stripslashes($getwhere);
	
	?>

<div id="static_header">
    <div class="breadcrumbMessageDiv defaultFont boldText"><a class="headerLink" href="<?php echo WEB_URL;?>">Home</a> › File Management</div>    <!--  End of the Breadcrumb div mesage -->
    <div class="headerTopicContainer defaultFont boldText"><span class="headerTopicSelected">File Manager</span></div>
</div>

<div id="dataInputContainer_wrap">
<div id="dataInputContainer">

<div id="d-file-wrap">
    <div id="breadcrumb-file"><?php echo breadcrumb($getwhere);?></div>
    
    <div id="file-msg-wrap">
    <?php
        if(isset($_SESSION['warning_message'])){
            echo "<div title='Click to close' class='message_warning close'>".$_SESSION['warning_message']."</div>";
			unset($_SESSION['warning_message']);
        }elseif(isset($_SESSION['success_message'])){
            echo "<div title='Click to close' class='message_success close'>".$_SESSION['success_message']."</div>";
			unset($_SESSION['success_message']);
        }
    ?>
    </div>
        
    <div id="d-list-file">
        <table width="100%" border="1" bordercolor="#cccccc" align="center" cellpadding="0" cellspacing="0" id="tbl-files">
          <thead>
            <tr>
              <th width="100%">Name</th>
              
        <?php if($getwhere != 'uploads/'){?>
                  <!--<th width="70" scope="col">Edit</th> temporaly removed-->
                  <th scope="col"><div class="w70">Folders</div></th>
                  <th scope="col"><div class="w70">Files</div></th>
              	  <?php //if(has_permission($folder_first,'delete')){ ?>    
                  <th scope="col"><div class="w70">Delete</div></th>
              	  <?php //}?>    
                  <th scope="col"><div class="w80">Size&nbsp;(MB)</div></th>
                  <th scope="col"><div class="w80">Download</div></th>
              	  <?php }else{?>
				  <th><div class="w70">Folders</div></th>
                  <th><div class="w70">Files</div></th>
                  <th><div class="w80">Size&nbsp;(MB)</div></th>
                  <?php }?>
            </tr>
          </thead>
          <tbody>
				<?php
                    $dir = opendir(ROOT);
					$i=0;
                    while(false !== ($unique_file = readdir($dir))){
					
	    				$i++;
						if (check_subfolder_or_not($unique_file)){
							$unique_file = check_non_root_folder_name_validity($unique_file);							
						}
						$_SESSION['unique_file'] = $unique_file;

						$ary_all_files = get_all_files($_SESSION['curr_country']['country_code']);
						$new_all_files = array();					
						foreach($ary_all_files as $each_sec){ 
							$new_all_files[$each_sec['id']] = $each_sec; 
						}
						if (isset($_SESSION['ary_all_files'])) unset($_SESSION['ary_all_files']);
						$_SESSION['ary_all_files'] = $new_all_files;

                        $real_name = stripslashes($ary_all_files[$unique_file]['real_name']);
						
                        $unique_file = stripslashes($unique_file);

                        if(file_exists(ROOT.$file)){
                            $size = entry_size(ROOT.$unique_file);
                        }else{
                            $size = '-';
                        }
                        //Edit here to hide certain files.						
                        if  ($unique_file != "." && $unique_file != ".." && $unique_file != "Thumbs.db" && $unique_file != ".DS_Store"){                            
								$ext = strtolower(pathinfo($unique_file, PATHINFO_EXTENSION));						
								
								if (empty($ext)){
									$ext = 'folder';
								}
								if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'gif'){
									$ext = 'image';
								}
								if ($ext == 'htaccess'){
									$ext = 'txt';
								}
								if (!file_exists(DOC_ROOT."/files/images/doc_img/icon_$ext.gif")){
									$ext = 'generic';
								}
								$icon = '<img id="'.$unique_file.'" class="icon" src="'.WEB_ROOT.'/files/images/doc_img/icon_'. $ext . '.gif" />';
								//Edit below to state if a file type can be edited or not  
								if ($ext == 'html' || $ext == 'htm' || $ext == 'php' || $ext == 'js' || $ext == 'css'|| $ext == 'xml'|| $ext == 'txt'){
									$allow_edit = '1';
								}else{
									$allow_edit = '0';
								}

                                if($getwhere == 'uploads/'){//if home folder
                                
                                   //if(has_permission($unique_file,'view')){
                            ?>
                                    <tr>
                                        
                                        <!--icon and name-->
                                        <td class="a-left">
                                            <?php echo $icon;?>
                                            <?php if($ext=='folder'){?>
                                                <div style=" display:inline;" title="Browse This Folder."><a href="?do=view_files&where=<?php echo $where.$unique_file.'/'?>">
                                                <?php echo str_replace("$$$$$","'",$unique_file);?>
                                                </a></div>
                                            <?php }else{
                                                echo $unique_file;
                                            }?>
                                        </td>                           
                
                                        <!--count-->
                                        <td><?php echo count_dir_elements(ROOT.$unique_file,'folder'); ?></td>
                                        <td><?php echo count_dir_elements(ROOT.$unique_file); ?></td>
                    
                                        <!--file size-->
                                        <td class="a-right size"><?php echo entry_size_in_mb($size)?></td>            
                                
                                    </tr>
                                <?php //} //close permission block?>
      <?php } //close checking home folder
			else{//if not home folder?>
                <?php if (
							(check_unique_name_belongs_to_its_country(substr($icon, 9, strlen($_SESSION['unique_file'])))) || 
							(empty($ext)) ||
							(check_unique_name_eligible_to_display())
						 ):?>
                <tr>                        
                        <!--icon and name-->
                        <td class="a-left">
                            <?php echo $icon?>
                            <?php
                            if($ext=='folder'){?>
                                <div style="display:inline;" title="Browse This Folder."><a href="?do=view_files&where=<?php echo $where.$real_name.'/'?>">
                                <?php echo str_replace("$$$$$","'",$real_name); ?>
                                </a></div>
                            <?php
                            }else{								
                                echo $real_name;	
                            }?>
                        </td>

                        <!--count-->
                        <td><?php echo count_dir_elements(ROOT.$unique_file,'folder'); ?></td>
                        <td><?php echo count_dir_elements(ROOT.$unique_file) ?></td>                        
                        
                        <!--delete-->
                        <?php //if(has_permission($folder_first,'delete')){ ?> 
                        <td><a href="?file=<?php echo ($ext=='folder') ? $real_name : $unique_file;?>&do=delete_file&where=<?php echo urlencode($where)?>" onclick="return confirm('Are you sure?')"><img src="files/images/delete.png" alt="edit" width="16" height="16" border="0" /></a></td>            
                        <?php //}?>
                        
                        <!--file size-->
                        <td class="a-right size"><?php echo entry_size_in_mb($size)?></td>
                        
                        <!--download-->
                        <td><?php if($ext=='folder'){?>-<?php } else{ ?><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/pahro/download/?file=<?php echo $unique_file;?>&type=file-manager&do=download_file&where=<?php echo $where;?>"><img src="files/images/download.png" alt="edit" border="0" width="16" height="16" /></a><?php } ?></td>            
                </tr>
                <?php endif;?>

			<?php   }//close else part of checking home folder?>    
            <?php }  
				}
				closedir($dir); 
			?>
          </tbody>
        </table>
    </div>
    
    <div id="upload_box">
      <form action="?do=upload_file&where=<?php echo $where?>" method="post" enctype="multipart/form-data" name="form_upload" id="form_upload" class="form_upload">
        Upload a file in current folder:
        <input class="f-input wte" type="file" name="file" id="file" />
        &nbsp;&nbsp;
        <!--<input class="f-input" name="replace_file" type="checkbox" value="1" />
        Overwrite existing file &nbsp;  &nbsp;-->
        <input class="f-input" type="submit" name="upload" id="upload" value="Upload" />
        <input class="f-input" name="upload_file" type="hidden" id="upload_file" value="upload_file" />
        <input class="f-input" type="button" id="close_upload" value="X" title="Close" />
      </form>
    </div>
    
    <div id="folder_box">
      <form action="?do=create_folder&where=<?php echo $where?>" method="post" enctype="multipart/form-data" name="form_upload" id="form_upload" class="form_upload">
        <?php 
            if($folder_last != 'Legislation'){
                echo 'Folder name';
                $btn_value = 'Create Folder';
            }
            else{
                echo 'Segment name';
                $btn_value = 'Create Segment';
            }
        ?>
        <input class="f-input wte" id="txt-create-folder" name="txt-create-folder" type="text" style="width:290px" />
        <input class="f-input" type="submit" name="create_folder" id="create_folder" value="<?php echo $btn_value;?>" />
        <input class="f-input" type="button" id="close_folder" value="X" title="Close" />
      </form>
    </div>

<?php
	
	$is_upload = false;
	$is_folder = false;
	$btn_value = '';
		
	if($folder_last == 'Legislation' && $folder_last_two == 'uploads'){
		if($getwhere != 'uploads/'){//removes both button from home page
			
			if($folder_last == 'Legislation'){//hides file upload
				$btn_value = 'Create a Segment';
			}else{
				$is_upload = true;
				$btn_value = 'Create a folder';
			}
			$is_folder = true;			
		}
		//added later
		if($getwhere == 'uploads/'){
			$is_folder = true;
		}

	}
	else{
		$btn_value = 'Create a folder';
		$is_upload = true;
		$is_folder = true;				
	}

	//if(has_permission($folder_first,'add')){
		if($is_upload){?>
        <!--jsscript-->
        	<script type="text/javascript" language="javascript">    
			$(document).ready(function() {
				//start multiple upload	
				var qcount = 0;
				var inprogress = false;
				var msg1 = "Uploading in progreess... Please be patient";
				var msg2 = "Uploading in progreess, you can not clear.";			
				var msg3 = "Nothing to upload !";
				
				// uploader
				var max_file_size = 419430400;
				$("#file-fm").uploadify({
					'uploader'       : '<?php echo WEB_URL;?>/lib/uploader/uploadify.swf',
					'script'         : '<?php echo WEB_URL;?>/controllers/uploader.php',
					'scriptData'	 : {'msid':'<?php echo session_id();?>','type':'file-fm'},
					'cancelImg'      : '../../../public/images/cancel.png',
					'folder'         : '/<?php echo $where;?>',
					'queueID'        : 'queue-fm',
					'wmode'      	 : 'transparent',
					'buttonImg'		 : '../../../public/images/attachmentdds.png',
					/*'width'			 : '68','height' : '12',*/
					'auto'           : 0,
					'multi'          : 1,
					/*'queueSizeLimit' : 4,*/
					'sizeLimit'		 : max_file_size,
					'onSelectOnce'	 : function(event,queueID,fileObj) {
											qcount = queueID.fileCount;
									   },
					'onOpen'	 	 : function(event,queueID,fileObj) {
											$("#l-"+queueID).addClass("fm-loader");
									   },
					'onError'		 : function(event,queueID,fileObj,errorObj){
											
									   },
					'onComplete' 	 : function(event, queueID, fileObj, response, data) {
											qcount --;
											$("#l-"+queueID).removeClass("fm-loader");
									   },
					'onAllComplete'  : function() {
											inprogress = false;
											$("#d-m-u").dialog("close");
											$("#fm-msg").hide()
											setTimeout("location.reload()",1000);
									   },
					'onCancel'       : function(event, queueID) {
											qcount --;
									   },
					'onClearQueue'   : function(event, queueID) {
											
									   }
									   
				});
				
				function save_file(){
					$.ajax({
						type: "POST",
						data: "save-file=true",
						url: "<?php echo WEB_URL;?>/file-manager/file-manager-func.php",
						success: function(resdata){
						},
						complete: function(){
						}
					});
				}
				
				$('#multi_upload_button').click(function(){
					$("#d-m-u").dialog("open");
					$(".ui-dialog-titlebar-close").remove();
				});
				
				//uploadall
				$("#d-u-su").bind("click", function(){
					if(qcount > 0){
						inprogress = true;
						$("#file-fm").uploadifyUpload();
					}
					else{
						msg(msg3);
					}
				});
			
				//exit
				$("#d-c").bind("click", function(){
					if(qcount > 0){
						if(inprogress == true){
							msg(msg1);
						}
						else{
							$("#file-fm").uploadifyClearQueue();
							$("#fm-msg").hide()
							$("#d-m-u").dialog("close");							
						}
					}
					else{
						$("#file-fm").uploadifyClearQueue();
						$("#fm-msg").hide()
						$("#d-m-u").dialog("close");
					}
				});
				
				//clear queue
				$("#d-cl").bind("click", function(){
					if(qcount > 0){
						if(inprogress == true){
							msg(msg2);
						}
						else{
							$("#file-fm").uploadifyClearQueue();
						}
					}
					else{
						$("#file-fm").uploadifyClearQueue();
					}
					
				});
			
				function msg(val){
					$("#fm-msg").html(val).show();
					setTimeout('$("#fm-msg").fadeOut("medium")',3000);
				}
				//end multiple upload
				
			});
			</script>
        
            <input class="f-input" id="upload_button" type="button" value="Upload a File" title="Click to upload a sigle file to this folder"/>
            <input class="f-input" id="multi_upload_button" name="" type="button" value="Upload Mulitiple Files" title="Click to upload multiple files to this folder" />
            <div id="d-m-u" style="display:none;" title="Multi Files Uploader">
                <div id="queue-fm"></div>
                <div id="fm-msg-wp"><div id="fm-msg"></div></div>
                <div id="d-ac-fm">
                    <div id="d-browse" title="Click to browse files"><input type="file" class="hide-file" id="file-fm" name="file-fm" />Browse</div>
                    <div id="d-u-su" title="Upload selected files">Start Upload</div>
                    <div id="d-cl" title="Clear all files in the queue">Clear</div>
                    <div id="d-c" title="Exit">Exit</div>
                </div>
            </div>

		<?php }
		
		if($is_folder){
			echo '<input class="f-input" id="folder_button" type="button" value="'.$btn_value.'" title="Click to create a folder" />';
		}
	//}	
}

  switch ($_GET['do']) {
	  
	  case 'view_files':
	  list_files($getwhere);
	  break;
	  				  
	  case 'delete_file':
	  delete_file(ROOT.$_GET['file'],$_GET['file'],$getwhere);
	  //list_files($getwhere);
	  forward("?do=view_files&where=".$getwhere);
	  break;
	  
	  case 'download_file':
	  download_file($getwhere, $_GET['file']);
	  forward("?do=view_files&where=".$getwhere);
	  break;
	  
	  case 'upload_file':
	  upload_file($getwhere);
	  forward("?do=view_files&where=".$getwhere);
	  break;
	  
	  case 'create_folder':
	  create_folder($getwhere,$_POST['txt-create-folder']);
	  forward("?do=view_files&where=".$getwhere);
	  break;
	  
	  default;
	  forward("?do=view_files&where=".$getwhere);
	  
} 

#function to create a breadcrumb link back to start folder.
function breadcrumb($getwhere){
	$where = stripcslashes($getwhere);
	if($where != 'uploads/'){
		echo '<a id="breadcrumb" class="f-root" title="Browse home folder" href="?do=view_files&where=uploads/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>';
	}
	$bc=explode("/",$where);
//	$bc = array_filter($bc);
	while(list($key,$val)=each($bc)){
		$dir = '';
		if($key > 1){
			$n = 1;
			while($n < $key){
				$dir .= '/'.$bc[$n];
				$val = $bc[$n];
				$n++;
			}
			
			if($val != ""){
				if($key < count($bc)) echo ' <span class="f-p">&rsaquo;</span> <a id="breadcrumb"  href="?do=view_files&where=uploads'.$dir.'/">'.str_replace("$$$$$","'",$val).'</a>';
			}
		}
	}
}
?>
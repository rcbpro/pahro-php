<div style="display:block;" align="center">
    <?php if (strstr($_SERVER['REQUEST_URI'], "logged")):?>
    <div id="indexDiv" class="defaultFont specializedTexts mediumLargeFont">You have logged into your account. To end your session click 'Logout'.</div>
    <?php else:?>	
	<div class="defaultFont LargeFont specializedTexts" style=" background-color:#CCCCCC; margin-top:75px; width:400px; height:200px;">Welcome to "Projects Abroad Human Rights Database" home page.</div>
    <?php endif;?>
</div>
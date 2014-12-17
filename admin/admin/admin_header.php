<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if(strpos(GetVars('HTTP_USER_AGENT','SERVERS'),'MSIE')){?>
<meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
<?php }?>
<meta name="generator" content="TQBlog <?php echo $option['CFG_BLOG_VERSION']?>" />
<meta name="robots" content="none" />
<title><?php echo $blogname . ' - ' . $blogtitle?> - Powered by TQBlog</title>
<link href="<?php echo $bloghost?>admin/css/admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $bloghost?>admin/css/jquery.bettertip.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $bloghost?>admin/css/jquery-ui.custom.css"/>
<script src="<?php echo $bloghost?>admin/script/common.js" type="text/javascript"></script>
<script src="<?php echo $bloghost?>admin/script/admin_util.php" type="text/javascript"></script>
<script src="<?php echo $bloghost?>admin/script/jquery.bettertip.pack.js" type="text/javascript"></script>
<script src="<?php echo $bloghost?>admin/script/jquery-ui.custom.min.js" type="text/javascript"></script>
<?php
	foreach ($GLOBALS['Filter_Plugin_Admin_Header'] as $fpname => &$fpsignal) {$fpname();}
?>
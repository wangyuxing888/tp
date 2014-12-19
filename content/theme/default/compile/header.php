<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php  echo $language;  ?>" lang="<?php  echo $language;  ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-Language" content="<?php  echo $language;  ?>" />
	<title><?php  echo $name;  ?> - <?php  echo $title;  ?> - Powered by TQBlog</title>
    
    <meta name="keywords" content="<?php  echo $keywords;  ?>" />
	<meta name="description" content="<?php  echo $description;  ?>" />
    <meta name="generator" content="<?php  echo $tqblogphp;  ?>" />
	<meta name="author" content="<?php  echo $tqauthor;  ?>" />
	<meta name="copyright" content="2008-2028 tqtqtq.com" />
	<meta name="MSSmartTagsPreventParsing" content="True" />
	<meta http-equiv="MSThemeCompatible" content="Yes" />

	<link rel="shortcut icon" href="<?php  echo $host;  ?>favicon.ico" />
	<link rel="stylesheet" rev="stylesheet" href="<?php  echo $host;  ?>content/theme/<?php  echo $theme;  ?>/style/<?php  echo $style;  ?>.css" type="text/css" media="all"/>
	<script src="<?php  echo $host;  ?>admin/script/common.js" type="text/javascript"></script>
	<script src="<?php  echo $host;  ?>admin/script/html_util.php" type="text/javascript"></script>
<?php  echo $header;  ?>
<?php if ($type=='index' && $page=='1') { ?>
	<link rel="alternate" type="application/rss+xml" href="<?php  echo $feedurl;  ?>" title="<?php  echo $name;  ?>" />
	<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php  echo $host;  ?>admin/xmlrpc/?rsd" />
	<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php  echo $host;  ?>admin/xmlrpc/wlwmanifest.xml" /> 
<?php }else{  ?>
	<link rel="alternate" type="application/rss+xml" href="<?php  echo $feedurl;  ?>" title="<?php  echo $name;  ?>" />
<?php } ?>
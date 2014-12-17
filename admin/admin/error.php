<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow"/>
	<meta name="generator" content="<?php echo $GLOBALS['option']['CFG_BLOG_PRODUCT_FULL'];?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
	<title><?php echo $GLOBALS['blogname'] . ' - ' . $GLOBALS['lang']['msg']['error']; ?> - Powered by TQBlog</title>
    <link rel="shortcut icon" href="<?php echo $GLOBALS['bloghost'];?>favicon.ico" />
	<link rel="stylesheet" href="<?php echo $GLOBALS['bloghost'];?>admin/css/module.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo $GLOBALS['bloghost'];?>admin/script/common.js"></script>
	<style type="text/css">
	.content div{
	}
	</style>
</head>
<body class="short">

<!-- top bar -->
<div id="top">
	<div class="center">
    <div class="menu-left">
    <ul>
     <li><a href="<?php echo $GLOBALS['bloghost'];?>">返回首页</a></li>    
    </ul>
    </div>
    <div class="menu-right">
    <ul>
     	<li></li> 
    </ul>
    </div>
   </div>	
</div>

<div class="bg">
<div id="wrapper">
  <div class="logo"><a href="<?php echo $GLOBALS['bloghost'];?>" title="<?php echo $GLOBALS['blogname'] ?>"></a></div>
  <div class="login loginw">
	<form id="frmLogin" method="post" action="#">
	  <div class="divHeader"><?php echo $GLOBALS['lang']['msg']['error_tips']; ?></div>
  	  <div class="content">
	 	<div><p><?php echo $GLOBALS['lang']['msg']['error_info']; ?></p><div>
		<?php echo '(' . $this->type . ')     ' . htmlspecialchars($this->message); ?>
		</div></div>
	 	<?php if($GLOBALS['option']['CFG_DEBUG_MODE']){?>
	 	<div><p><?php echo $GLOBALS['lang']['msg']['file_line']; ?></p><div>
	 	<p><i><?php echo $this->file ?></i><br/></p>
	 	<table style='width:100%'>
	 	<tbody>
		 	<?php
		 		$aFile = $this->get_code($this->file,$this->line);
		 		foreach ($aFile as $iInt => $sData) {
		 	?>
		 		<tr<?php echo($iInt+1 == $this->line?' style="background:#75BAFF"':'')?>>
		 			<td style='width:50px'><?php echo $iInt+1 ?></td>
		 			<td><?php echo $sData?></td>
		 		</tr>
		 	<?php
		 		$post_data = $_COOKIE;unset($post_data['username']);unset($post_data['password']);
		 		}
		 	?>
		</tbody>
	 	</table>
	 	</div></div>
	 	<div><p><?php echo $GLOBALS['lang']['msg']['request_data']; ?></p><div>
	 	<pre><?php echo '$_GET = '.print_r($_GET, 1) ?></pre>
	 	<pre><?php echo '$_POST = '.print_r($_POST, 1) ?></pre>
		<pre><?php echo '$_COOKIE = '.print_r($post_data, 1) ?></pre>
	 	</div></div>
	 	<div><p><?php echo $GLOBALS['lang']['msg']['include_file']; ?></p><div>
	 	<table style='width:100%'>
	 	<tbody>
		<?php foreach(get_included_files() as $iInt => $sData) {?>
			<tr><td style='width:30px'><?php echo $iInt?></td><td><?php echo $sData;?></td></tr>
		<?php }	?>
		</tbody>
		</table>
	 	</div></div>
	 	<?php } ?>
	  </div>
	  <p><a href="javascript:history.back(-1)"><?php echo $GLOBALS['lang']['msg']['back'];?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:location.reload()"><?php echo $GLOBALS['lang']['msg']['refresh'];?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $GLOBALS['bloghost'];?>admin/admin.php?act=login"><?php echo $GLOBALS['lang']['msg']['admin'];?></a></p>
    </form>
  </div>
</div>
</div>
<script type="text/javascript">
</script>
</body>
</html>
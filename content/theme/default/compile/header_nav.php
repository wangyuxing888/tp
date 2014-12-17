<?php  include $this->GetTemplate('header');  ?>

</head>
<body class="<?php if ($type=='index') { ?>multi <?php  echo $type;  ?><?php }else{  ?>single <?php  echo $type;  ?><?php } ?>">

<!-- top bar -->
<div id="top">
	<div class="center">
    <div class="menu-left">
    <ul>
     <li><a href="javascript:;" onclick="setHomepage('<?php echo $option['CFG_BLOG_HOST']; ?>');">设为首页</a></li>
     <li><a href="javascript:;" onclick="addFavorite('<?php echo $option['CFG_BLOG_HOST']; ?>','<?php echo $option['CFG_BLOG_NAME']; ?> - <?php echo $option['CFG_BLOG_SUBNAME']; ?>')">收藏本站</a></li>      
    </ul>
    </div>
    <div class="menu-right">
    <ul>
     <?php if ($tqb->CheckRights('admin')) { ?>
     	<li>欢迎 <?php  echo $user->Name;  ?>(<?php  echo $user->LevelName;  ?>)！</li>
        <?php if ($tqb->CheckRights('ArticleEdt')) { ?>
     	<li><a href="<?php  echo $host;  ?>admin/admin.php?act=ArticleEdt">发博文</a></li>
     	<?php } ?> 
        <li><a href="<?php  echo $host;  ?>admin/admin.php?act=login">管理</a></li>
        <li><a href="<?php  echo $host;  ?>admin/admin.php?act=logout">退出</a></li>
     <?php }else{  ?>
     	<li><a href="<?php  echo $host;  ?>admin/admin.php?act=login">登陆</a></li>
     <?php } ?>    
    </ul>
    </div>
   </div>	
</div>
  
<div id="divAll">
	<div id="divPage">
	<div id="divMiddle">
		<div id="divTop">
			<h1 id="BlogTitle"><a href="<?php  echo $host;  ?>"><?php  echo $name;  ?></a></h1>
			<h3 id="BlogSubTitle"><?php  echo $subname;  ?></h3>
		</div>
		<div id="divNavBar">
			<ul>
				<?php if ($type=='index') { ?><?php  if(isset($modules['navbar'])){echo $modules['navbar']->Content;}  ?><?php }else{  ?><?php  echo $modules['navbar']->Content;  ?><?php } ?>
			</ul>
		</div>
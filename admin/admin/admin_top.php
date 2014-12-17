</head>
<body>
<?php if($tqb->option['CFG_ADMIN_HTML5_ENABLE']){?><header class="header"><?php }else{?><div class="header"><?php }?>
    <div class="logo"><a href="<?php echo $bloghost?>admin/admin.php?act=admin" title="<?php echo $option['CFG_BLOG_PRODUCT'];?>"></a></div>
    <div class="user"> <a href="<?php echo $bloghost?>admin/admin.php?act=MemberEdt&amp;id=<?php echo $tqb->user->ID?>" title="<?php echo $lang['msg']['edit']?>"><img src="<?php echo $tqb->user->Avatar?>" width="40" height="40" id="avatar" alt="Avatar" /></a>
      <div class="username"><?php echo $tqb->user->LevelName?>：<?php echo $tqb->user->Name?></div>
      <div class="userbtn"><a class="profile" href="<?php echo $bloghost?>" title="" target="_blank"><?php echo $lang['msg']['return_to_site']?></a>&nbsp;&nbsp;<a class="logout" href="<?php echo $bloghost?>admin/admin.php?act=logout" title=""><?php echo $lang['msg']['logout']?></a></div>
    </div>
    <div class="menu">
      <ul id="topmenu">
<?php
ResponseAdmin_TopMenu()
?>
      </ul>
    </div>
<?php if($tqb->option['CFG_ADMIN_HTML5_ENABLE']){?></header><?php }else{?></div><?php }?>
<?php
require $blogpath . 'admin/admin/admin_left.php';
?>
<?php if($tqb->option['CFG_ADMIN_HTML5_ENABLE']){?><section class="main"><?php }else{?><div class="main"><?php }?>
<?php
/*
if(GetVars('batch','COOKIE')){
?>
<div id="batch">
<iframe style="width:20px;height:20px;" frameborder="0" scrolling="no" src="<?php echo $bloghost?>admin/admin.php?act=batch"></iframe><p><?php echo $lang['msg']['batch_operation']?>...</p>
</div>
<?php
	}else{
?>
<!--<div id="batch"><img src="<?php echo $bloghost?>admin/image/admin/error.png" width="16"/><p><?php echo $lang['msg']['previous_operation_not_finished']?></p></div>-->
<?php
}
*/
$tqb->GetHint();
?>
<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: client.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}
if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

if(!$tqb->Config('AppCentre')->shop_username||!$tqb->Config('AppCentre')->shop_password){
	$blogtitle='应用中心-登录应用商城';
}else{
	$blogtitle='应用中心-我的应用仓库';
}

if(GetVars('act')=='shoplogin'){

	$s=Server_Open('shopvaild');

	if($s){
		$tqb->Config('AppCentre')->shop_username=GetVars("shop_username");
		$tqb->Config('AppCentre')->shop_password=$s;
		$tqb->SaveConfig('AppCentre');
		$tqb->SetHint('good','您已成功登录"应用中心"商城.');
		Redirect('./client.php');
		die;
	}else{
		$tqb->SetHint('bad','购买者账户名或密码错误.');
		Redirect('./client.php');
		die;
	}
	
}

if(GetVars('act')=='shoplogout'){
	$tqb->Config('AppCentre')->shop_username='';
	$tqb->Config('AppCentre')->shop_password='';
	$tqb->SaveConfig('AppCentre');
	$tqb->SetHint('good','您已退出"应用中心"商城.');
	Redirect('./main.php');
	die;
}

require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';
?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
<div class="SubMenu"><?php AppCentre_SubMenus(9);?></div>
  <div id="divMain2">
<?php if(!$tqb->Config('AppCentre')->shop_username||!$tqb->Config('AppCentre')->shop_password){ ?>
            <form action="?act=shoplogin" method="post">
              <table style="line-height:3em;" width="100%" border="0">
                <tr height="32">
                  <th  align="center">请填写您在"<a href="http://app.tqtqtq.com/?shop&type=account" target="_blank">应用中心</a>"的购买者账号(Email)和密码
                    </th>
                </tr>
                <tr height="32">
                  <td align="center">&nbsp;&nbsp;账号:
                    <input type="text" name="shop_username" value="" style="width:35%"/></td>
                </tr>
                <tr height="32">
                  <td align="center">&nbsp;&nbsp;密码:
                    <input type="password" name="shop_password" value="" style="width:35%" /></td>
                </tr>
                <tr height="32" align="center">
                  <td align="center"><input type="submit" value="登陆" class="button" /></td>
                </tr>
              </table>
            </form>
<?php }else{ 

//已登录
Server_Open('shoplist');

      }?>



	<script type="text/javascript">ActiveLeftMenu("aAppCentre");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'content/plugin/AppCentre/logo.png';?>");</script>	
  </div>
</div>

<?php
require $blogpath . 'admin/admin/admin_footer.php';
RunTime();
?>
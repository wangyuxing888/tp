<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: app_developer.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

$blogtitle='应用中心-设置';

if(GetVars('act')=='save'){

	$tqb->Config('AppCentre')->enabledcheck=(int)GetVars("app_enabledcheck");
	$tqb->Config('AppCentre')->checkbeta=(int)GetVars("app_checkbeta");
	$tqb->Config('AppCentre')->enabledevelop=(int)GetVars("app_enabledevelop");
	$tqb->SaveConfig('AppCentre');

	$tqb->SetHint('good');
	Redirect('./setting.php');

}

if(GetVars('act')=='login'){

	$s=Server_Open('vaild');
	if($s){

		$tqb->Config('AppCentre')->username=GetVars("app_username");
		$tqb->Config('AppCentre')->password=$s;
		$tqb->SaveConfig('AppCentre');

		$tqb->SetHint('good','您已成功登录APP应用中心.');
		Redirect('./main.php');
		die;
	}else{
		$tqb->SetHint('bad','用户名或密码错误.');
		Redirect('./setting.php');
		die;
	}
}

if(GetVars('act')=='logout'){
	$tqb->Config('AppCentre')->username='';
	$tqb->Config('AppCentre')->password='';
	$tqb->SaveConfig('AppCentre');
	$tqb->SetHint('good','您已退出APP应用中心.');
	Redirect('./setting.php');
	die;
}

require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';
?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
<div class="SubMenu"><?php AppCentre_SubMenus(4);?></div>
  <div id="divMain2">

            <form action="?act=save" method="post">
              <table width="100%" border="0">
                <tr height="32">
                  <th colspan="2" align="center">设置
                    </td>
                </tr>
                <tr height="32">
                  <td width="30%" align="left"><p><b>· 启用自动检查更新</b><br/>
                      <span class="note">&nbsp;&nbsp;在进入后台时会检查应用更新和系统更新 </span></p></td>
                  <td><input id="app_enabledcheck" name="app_enabledcheck" type="text" value="<?php echo $tqb->Config('AppCentre')->enabledcheck; ?>" class="checkbox"/></td>
                </tr>
                <tr height="32">
                  <td width="30%" align="left"><p><b>· 检查Beta版程序</b><br/>
                      <span class="note">&nbsp;&nbsp;若打开，则系统将检查最新测试版的TQBlog更新</span></p></td>
                  <td><input id="app_checkbeta" name="app_checkbeta" type="text" value="<?php echo $tqb->Config('AppCentre')->checkbeta; ?>" class="checkbox"/></td>
                </tr>
                <tr height="32">
                  <td width="30%" align="left"><p><b>· 启用开发者模式</b><br/>
                      <span class="note">&nbsp;&nbsp;启用开发者模式可以修改应用信息、导出应用和远程提交应用</span></p></td>
                  <td><input id="app_enabledevelop" name="app_enabledevelop" type="text" value="<?php echo $tqb->Config('AppCentre')->enabledevelop; ?>" class="checkbox"/></td>
                </tr>
              </table>
              <hr/>
              <p>
                <input type="submit" value="提交" class="button" />
              </p>
              <hr/>
            </form>

            <div class="divHeader2">开发者登录</div>
<?php if(!$tqb->Config('AppCentre')->username){ ?>
            <form action="?act=login" method="post">
              <table style="line-height:3em;" width="100%" border="0">
                <tr height="32">
                  <th  align="center">开发者请填写您在"APP应用中心"的用户名和密码并点登陆
                    </td>
                </tr>
                <tr height="32">
                  <td  align="center">用户名:
                    <input type="text" name="app_username" value="" style="width:40%"/></td>
                </tr>
                <tr height="32">
                  <td  align="center">密&nbsp;&nbsp;&nbsp;&nbsp;码:
                    <input type="password" name="app_password" value="" style="width:40%" /></td>
                </tr>
                <tr height="32" align="center">
                  <td align="center"><input type="submit" value="登陆" class="button" /></td>
                </tr>
              </table>
            </form>
<?php }else{ ?>
            <form action="?act=logout" method="post">
              <p>开发者 <b><?php echo $tqb->Config('AppCentre')->username; ?></b> 您好,您已经在当前客户端登录TQBlog官方网站-APP应用中心.</p>
              <p>
                <input name="submit" type="submit" value="退出登录" class="button" />
              </p>
            </form>
<?php }?>
	<script type="text/javascript">ActiveLeftMenu("aAppCentre");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'content/plugin/AppCentre/logo.png';?>");</script>	
  </div>
</div>

<?php
require $blogpath . 'admin/admin/admin_footer.php';

RunTime();
?>
<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: main.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

$blogtitle='应用中心';

if(!$tqb->Config('AppCentre')->HasKey('enabledcheck')){
	$tqb->Config('AppCentre')->enabledcheck=1;
	$tqb->Config('AppCentre')->checkbeta=0;
	$tqb->Config('AppCentre')->enabledevelop=0;
	$tqb->SaveConfig('AppCentre');
}

if(count($_POST)>0){

	$tqb->SetHint('good');
	Redirect('./main.php');
}

require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';

?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
<div class="SubMenu"><?php AppCentre_SubMenus(GetVars('method','GET')=='check'?2:1);?></div>
  <div id="divMain2">

<?php
$method=GetVars('method','GET');
if(!$method)$method='view';
Server_Open($method);
?>
	<script type="text/javascript">
		window.plug_list = "<?php echo AddNameInString($option['CFG_USING_PLUGIN_LIST'],$option['CFG_BLOG_THEME'])?>";
		window.appKey = '<?php echo $tqb->GetToken()?>';
	</script>
	<script type="text/javascript">ActiveLeftMenu("aAppCentre");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'content/plugin/AppCentre/logo.png';?>");</script>	
  </div>
</div>
<?php
require $blogpath . 'admin/admin/admin_footer.php';

RunTime();
?>
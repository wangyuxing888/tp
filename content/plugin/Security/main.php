<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: main.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('Security')) {$tqb->ShowError(48);die();}

$blogtitle='Security词语过滤';

if(count($_POST)>0){


	$tqb->Config('Security')->BlackWord_List=Trim(Trim(GetVars('BlackWord_List','POST')),'|');
	$tqb->Config('Security')->Op_BlackWord_Audit=GetVars('Op_BlackWord_Audit','POST');
	$tqb->Config('Security')->Op_BlackWord_Throw=GetVars('Op_BlackWord_Throw','POST');
	$tqb->Config('Security')->Op_Chinese_None=GetVars('Op_Chinese_None','POST');
	$tqb->SaveConfig('Security');

	$tqb->SetHint('good','Security已保存设置');
	Redirect('./main.php');
}


require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';

?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
<div class="SubMenu"></div>
  <div id="divMain2">
	<form id="edit" name="edit" method="post" action="#">
<input id="reset" name="reset" type="hidden" value="" />
<table border="1" class="tableFull tableBorder">
<tr>
	<th class="td25"><p align='left'><b>选项</b><br><span class='note'></span></p></th>
	<th>
	</th>
</tr>
<tr>
	<td><p align='left'><b>没有中文字符直接进入审核</b></p></td>
	<td><input type="text" name="Op_Chinese_None" value="<?php echo $tqb->Config('Security')->Op_Chinese_None;?>" class="checkbox" />
	</td>
</tr>
<tr>
	<td><p align='left'><b>有N个不良词语直接进入审核</b></p></td>
	<td>N=<input type="text" name="Op_BlackWord_Audit" value="<?php echo $tqb->Config('Security')->Op_BlackWord_Audit;?>" /> 个不良词语
	</td>
</tr>
<tr>
	<td><p align='left'><b>有N个不良词语直接丢掉</b></p></td>
	<td>N=<input type="text" name="Op_BlackWord_Throw" value="<?php echo $tqb->Config('Security')->Op_BlackWord_Throw;?>" /> 个不良词语
	</td
></tr>
<tr>
	<td><p align='left'><b>不良词语列表</b><br><span class='note'>不良词语间用“|”分隔.列表为正则表达式,请谨慎修改,如果修改的格式不正确,会造成程序错误.</span></p></td>
	<td><textarea name="BlackWord_List" style="width:95%;height:400px"><?php echo $tqb->Config('Security')->BlackWord_List;?></textarea>
	</td>
</tr>

</table>
	  <hr/>
	  <p>
		<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" />
	  </p>

	</form>
	<script type="text/javascript">
function changeOptions(i){
	$('input[name^=CFG_]').each(function(){
		var s='radio' + $(this).prop('name');
		$(this).val( $("input[type='radio'][name='"+s+"']").eq(i).val() );
	});
	if(i=='0'){
		$("input[name^='radio']").prop('disabled',true);
		$("input[name='CFG_STATIC_MODE']").val('ACTIVE');
	}else{
		$("input[name^='radio']").prop('disabled',false);
		$("input[name='CFG_STATIC_MODE']").val('REWRITE');
	}

}
	</script>
	<script type="text/javascript">ActiveLeftMenu("aCommentAdmin");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'content/plugin/Security/logo.png';?>");</script>	
  </div>
</div>


<?php
require $blogpath . 'admin/admin/admin_footer.php';

RunTime();
?>
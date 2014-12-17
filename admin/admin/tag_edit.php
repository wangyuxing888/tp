<?php
require '../function/function_base.php';
require '../function/function_admin.php';

$tqb->CheckGzip();
$tqb->Load();

$action='TagEdt';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6,__FILE__,__LINE__);die();}

$blogtitle=$lang['msg']['tag_edit'];

require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';

?>
<?php

$tagid=null;
if(isset($_GET['id'])){$tagid = (integer)GetVars('id','GET');}else{$tagid = 0;}

$tag=$tqb->GetTagByID($tagid);

?>

<div id="divMain">
  <div class="divHeader2"><?php echo $lang['msg']['tag_edit']?></div>
  <div class="SubMenu"></div>
  <div id="divMain2" class="edit tag_edit">
	<form id="edit" name="edit" method="post" action="#">
	  <input id="edtID" name="ID" type="hidden" value="<?php echo $tag->ID;?>" />
	  <p>
		<span class="title"><?php echo $lang['msg']['name']?>:</span><span class="star">(*)</span><br />
		<input id="edtName" class="edit" size="40" name="Name" maxlength="50" type="text" value="<?php echo $tag->Name;?>" />
	  </p>
	  <p>
		<span class="title"><?php echo $lang['msg']['alias']?>:</span><br />
		<input id="edtAlias" class="edit" size="40" name="Alias" type="text" value="<?php echo $tag->Alias;?>" />
	  </p>
	  <p>
		<span class="title"><?php echo $lang['msg']['template']?>:</span><br />
		<select class="edit" size="1" name="Template" id="cmbTemplate">
<?php echo CreateOptoinsOfTemplate($tag->Template);?>
		</select>
	  </p>
	  <p>
		<label><span class="title"><?php echo $lang['msg']['add_to_navbar']?>:</span>   <input type="text" name="AddNavbar" id="edtAddNavbar" value="<?php echo (int)$tqb->CheckItemToNavbar('tag',$tag->ID)?>" class="checkbox" /></label>
	  </p>
       <div id='response' class='editmod'>
<?php
foreach ($GLOBALS['Filter_Plugin_Tag_Edit_Response'] as $fpname => &$fpsignal) {$fpname();}
?>
	   </div>
	  <p>
		<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" id="btnPost" onclick="return checkInfo();" />
	  </p>
	</form>
	<script type="text/javascript">
function checkInfo(){
  document.getElementById("edit").action="../admin.php?act=TagPst";

  if(!$("#edtName").val()){
    alert("<?php echo $lang['error']['72']?>");
    return false
  }

}
	</script>
	<script type="text/javascript">ActiveLeftMenu("aTagAdmin");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $tqb->host . 'admin/image/admin/tag_32.png';?>");</script>
  </div>
</div>


<?php
require $blogpath . 'admin/admin/admin_footer.php';

RunTime();
?>
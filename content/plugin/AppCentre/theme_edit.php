<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: theme_edit.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

$blogtitle=$lang['msg']['tools_manage'];

if(GetVars('id')){
  $app = $tqb->LoadApp('theme',GetVars('id'));
}else{
  $app = new App;
  $app->price=0;
  $app->version='1.0';
  $app->pubdate=date('Y-m-d',time());
  $app->modified=date('Y-m-d',time());
  $v=array_keys($tqbvers);
  $app->adapted=(string)end($v);
  $app->type='theme';
}

if(count($_POST)>0){

  $app->id=trim($_POST['app_id']);
  if(!CheckRegExp($app->id,"/^[A-Za-z0-9_]{3,30}/")) {$tqb->ShowError('ID名必须是字母数字和下划线组成,长度3-30字符.');die();}
  if(!GetVars('id')){
    $app2 = $tqb->LoadApp('theme',$app->id);
    if($app2->id) {$tqb->ShowError('已存在同名的APP应用.');die();}
    @mkdir($tqb->contentdir . 'theme/' . $app->id);
    @mkdir($tqb->contentdir . 'theme/' . $app->id . '/style');
    @mkdir($tqb->contentdir . 'theme/' . $app->id . '/compile');
    @mkdir($tqb->contentdir . 'theme/' . $app->id . '/template');
    @copy($tqb->contentdir . 'plugin/AppCentre/images/theme.png',$tqb->contentdir . 'theme/' . $app->id . '/screenshot.png');
    @file_put_contents($tqb->contentdir . 'theme/' . $app->id . '/style/style.css','');

    if(trim($_POST['app_path'])){
      $file = file_get_contents('tpl/main.html');
      $file = str_replace("<%appid%>", $app->id, $file);
      $path=$tqb->contentdir . 'theme/' . $app->id . '/' . trim($_POST['app_path']);
      @file_put_contents($path, $file);
    }
    if(trim($_POST['app_include'])){
      $file = file_get_contents('tpl/include.html');
      $file = str_replace("<%appid%>", $app->id, $file);
      $path=$tqb->contentdir . 'theme/' . $app->id . '/include.php';
      @file_put_contents($path, $file);
    }
  }

$app->name=trim($_POST['app_name']);
$app->url=trim($_POST['app_url']);
$app->note=trim($_POST['app_note']);
$app->adapted=$_POST['app_adapted'];
$app->version=(float)$_POST['app_version'];
if($app->version==1)$app->version='1.0';
$app->pubdate=date('Y-m-d',strtotime($_POST['app_pubdate']));
$app->modified=date('Y-m-d',time());

$app->author_name=trim($_POST['app_author_name']);
$app->author_email=trim($_POST['app_author_email']);
$app->author_url=trim($_POST['app_author_url']);
$app->source_name=trim($_POST['app_source_name']);
$app->source_email=trim($_POST['app_source_email']);
$app->source_url=trim($_POST['app_source_url']);

$app->path=trim($_POST['app_path']);
$app->include=trim($_POST['app_include']);
$app->level=(int)$_POST['app_level'];
$app->price=(float)$_POST['app_price'];

$app->advanced_dependency=trim($_POST['app_advanced_dependency']);
$app->advanced_rewritefunctions=trim($_POST['app_advanced_rewritefunctions']);
$app->advanced_conflict=trim($_POST['app_advanced_conflict']);

$app->description=trim($_POST['app_description']);

if(GetVars('app_sidebars_sidebar1')){
  $app->sidebars_sidebar1=$tqb->option['CFG_SIDEBAR_ORDER'];
}else{
  $app->sidebars_sidebar1='';
}
if(GetVars('app_sidebars_sidebar2')){
  $app->sidebars_sidebar2=$tqb->option['CFG_SIDEBAR2_ORDER'];
}else{
  $app->sidebars_sidebar2='';
}
if(GetVars('app_sidebars_sidebar3')){
  $app->sidebars_sidebar3=$tqb->option['CFG_SIDEBAR3_ORDER'];
}else{
  $app->sidebars_sidebar3='';
}
if(GetVars('app_sidebars_sidebar4')){
  $app->sidebars_sidebar4=$tqb->option['CFG_SIDEBAR4_ORDER'];
}else{
  $app->sidebars_sidebar4='';
}
if(GetVars('app_sidebars_sidebar5')){
  $app->sidebars_sidebar5=$tqb->option['CFG_SIDEBAR5_ORDER'];
}else{
  $app->sidebars_sidebar5='';
}


$app-> SaveInfoByXml();

	$tqb->SetHint('good');
  Redirect($_SERVER["HTTP_REFERER"]);
}

require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';

?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
    <?php 
	echo '<a href="../../../admin/admin/?act=UploadAdmin&id=1"><span class="m-left">附件管理</span></a>';
    echo '<a href="update.php"><span class="m-left">系统更新与校验</span></a>';
	echo '<a href="theme_edit.php"><span class="m-left m-now">新建主题</span></a>';
	echo '<a href="plugin_edit.php"><span class="m-left">新建插件</span></a>';
	echo '<a href="setting.php"><span class="m-left">设置</span></a>';
	?>
  </div>
  <div id="divMain2">

<form method="post" action="">
  <table border="1" width="100%" cellspacing="0" cellpadding="0" class="tableBorder tableBorder-thcenter">
    <tr>
      <th width='28%'>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
    <tr>
      <td><p><b>· 主题ID</b><br/>
          <span class="note">&nbsp;&nbsp;主题ID为主题的目录名,且不能重复.ID名只能用字母数字和下划线的组合.</span></p></td>
      <td><p>&nbsp;
          <input id="app_id" name="app_id" style="width:550px;"  type="text" value="<?php echo $app->id;?>" <?php if($app->id)echo 'readonly="readonly"';?>  />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 主题名称</b></p></td>
      <td><p>&nbsp;
          <input id="app_name" name="app_name" style="width:550px;"  type="text" value="<?php echo $app->name;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 主题发布页面</b></p></td>
      <td><p>&nbsp;
          <input id="app_url" name="app_url" style="width:550px;"  type="text" value="<?php echo $app->url;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 主题简介</b></p></td>
      <td><p>&nbsp;
          <input id="app_note" name="app_note" style="width:550px;"  type="text" value="<?php echo $app->note;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 适用的最低要求 TQBlog 版本</b></p></td>
      <td><p>&nbsp;
          <select name="app_adapted" id="app_adapted" style="width:400px;">
<?php echo CreateOptoinsOfVersion($app->adapted);?>
          </select>
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 主题版本号</b></p></td>
      <td><p>&nbsp;
          <input id="app_version" name="app_version" style="width:550px;" type="number" step="0.1" value="<?php echo $app->version;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 主题首发时间</b><br/>
          <span class="note">&nbsp;&nbsp;日期格式为2012-12-12</span></p></td>
      <td><p>&nbsp;
          <input id="app_pubdate" name="app_pubdate" style="width:550px;"  type="text" value="<?php echo $app->pubdate;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 主题最后修改时间</b><br/>
          <span class="note">&nbsp;&nbsp;系统自动检查目录内文件的最后修改日期</span></p></td>
      <td><p>&nbsp;
          <input id="app_modified" name="app_modified" style="width:550px;"  type="text" value="<?php echo $app->modified;?>" readonly />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 作者名称</b></p></td>
      <td><p>&nbsp;
          <input id="app_author_name" name="app_author_name" style="width:550px;"  type="text" value="<?php echo $app->author_name;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 作者邮箱</b></p></td>
      <td><p>&nbsp;
          <input id="app_author_email" name="app_author_email" style="width:550px;"  type="text" value="<?php echo $app->author_email;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 作者网站</b></p></td>
      <td><p>&nbsp;
          <input id="app_author_url" name="app_author_url" style="width:550px;"  type="text" value="<?php echo $app->author_url;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 源作者名称</b> (可选)</p></td>
      <td><p>&nbsp;
          <input id="app_source_name" name="app_source_name" style="width:550px;"  type="text" value="<?php echo $app->source_name;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 源作者邮箱</b> (可选)</p></td>
      <td><p>&nbsp;
          <input id="app_source_email" name="app_source_email" style="width:550px;"  type="text" value="<?php echo $app->source_email;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 源作者网站</b> (可选)</p></td>
      <td><p>&nbsp;
          <input id="app_source_url" name="app_source_url" style="width:550px;"  type="text" value="<?php echo $app->source_url;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 内置插件管理页</b> (可选)<br/>
          <span class="note">&nbsp;&nbsp;习惯命名为main.php</span></p></td>
      <td><p>&nbsp;
          <input id="app_path" name="app_path" style="width:550px;"  type="text" value="<?php echo $app->path;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 内置插件嵌入页</b> (可选)<br/>
          <span class="note">&nbsp;&nbsp;只能命名为include.php</span></p></td>
      <td><p>&nbsp;
          <input id="app_include" name="app_include" style="width:550px;"  type="text" value="<?php echo $app->include;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 内置插件管理权限</b> (可选)</p></td>
      <td><p>&nbsp;
          <select name="app_level" id="app_level" style="width:200px;">
<?php echo CreateOptoinsOfMemberLevel(1)?>
          </select>
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 主题定价</b></p></td>
      <td><p>&nbsp;
          <input id="app_price" name="app_price" style="width:550px;"  type="text" value="<?php echo $app->price;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 【高级】依赖插件（以|分隔）</b>(可选)</p></td>
      <td><p>&nbsp;
          <input id="app_advanced_dependency" name="app_advanced_dependency" style="width:550px;"  type="text" value="<?php echo $app->advanced_dependency;?>" />
        </p></td>
    </tr>
    <tr style="display:none;">
      <td><p><b>· 【高级】内置插件重写系统函数列表（以|分隔）</b>(可选)</p></td>
      <td><p>&nbsp;
          <input id="app_advanced_rewritefunctions" name="app_advanced_rewritefunctions" style="width:550px;"  type="text" value="<?php echo $app->advanced_rewritefunctions;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 【高级】内置插件冲突插件列表（以|分隔）</b>(可选)</p></td>
      <td><p>&nbsp;
          <input id="app_advanced_conflict" name="app_advanced_conflict" style="width:550px;"  type="text" value="<?php echo $app->advanced_conflict;?>" />
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 详细说明</b> (可选)</p></td>
      <td><p>&nbsp;
          <textarea cols="3" rows="6" id="app_description" name="app_description" style="width:550px;"><?php echo htmlspecialchars($app->description);?></textarea>
        </p></td>
    </tr>
    <tr>
      <td><p><b>· 侧栏配置导出</b> (可选)</p></td>
      <td><p>&nbsp;
          <label>
            <input type="checkbox" name="app_sidebars_sidebar1" value="1" <?php if($app->sidebars_sidebar1)echo 'checked="checked"';?>  />
            侧栏</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>
            <input type="checkbox" name="app_sidebars_sidebar2" value="1" <?php if($app->sidebars_sidebar2)echo 'checked="checked"';?>   />
            侧栏2</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>
            <input type="checkbox" name="app_sidebars_sidebar3" value="1" <?php if($app->sidebars_sidebar3)echo 'checked="checked"';?>   />
            侧栏3</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>
            <input type="checkbox" name="app_sidebars_sidebar4" value="1" <?php if($app->sidebars_sidebar4)echo 'checked="checked"';?>   />
            侧栏4</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label>
            <input type="checkbox" name="app_sidebars_sidebar5" value="1" <?php if($app->sidebars_sidebar5)echo 'checked="checked"';?>   />
            侧栏5</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
    </tr>
  </table>
  <p> 提示:主题的缩略图是名为screenshot.png的<b>300x240px</b>大小的png文件,放在插件的目录下.</p>
  <p><br/>
    <input type="submit" class="button" value="提交" id="btnPost" onclick='' />
  </p>
  <p>&nbsp;</p>
</form>


	<script type="text/javascript">ActiveLeftMenu("aUploadAdmin");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'admin/image/admin/tools_32.png';?>");</script>	
  </div>
</div>
<?php
require $blogpath . 'admin/admin/admin_footer.php';

RunTime();
?>
<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: update.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

$blogtitle=$lang['msg']['tools_manage'];

$checkbegin=false;
$nowxml='';


if(GetVars('update','GET')!=''){
$url=APPCENTRE_SYSTEM_UPDATE . '?mod=' . GetVars('update','GET');
$f=AppCentre_GetHttpContent($url);
  $xml=simplexml_load_string($f);
  if($xml){
	  foreach ($xml->children() as $file){
		$full=$tqb->path . str_replace('\\','/',$file['name']);
		$dir=dirname($full);
		if(!file_exists($dir . '/'))@mkdir($dir,0755,true);
		$f=base64_decode($file);
		@file_put_contents($full,$f);
	  }
	  $tqb->SetHint('good');
  }
  Redirect('./update.php');
}

if(GetVars('restore','GET')!=''){
  $file=base64_decode(GetVars('restore','GET'));
  $url=APPCENTRE_SYSTEM_UPDATE . '?' . substr(CFG_BLOG_VERSION,-8,8) . '\\' . $file;
  $f=AppCentre_GetHttpContent($url);
  $file=$tqb->path . str_replace('\\','/',$file);
  $dir=dirname($file);
  if(!file_exists($dir.'/'))@mkdir($dir,0755,true);
  @file_put_contents($file,$f);
  echo '<img src="'.$tqb->host.'admin/image/admin/ok.png" width="16" alt="" />';
  die();
}


if(GetVars('check','GET')=='now'){
  $r = AppCentre_GetHttpContent(APPCENTRE_SYSTEM_UPDATE . '?mod=' . array_search(CFG_BLOG_VERSION,$tqbvers));
  //file_put_contents($tqb->contentdir . 'cache/now.xml', $r);
  $nowxml=$r;
  $checkbegin=true;
}


require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';


$newversion=AppCentre_GetHttpContent(APPCENTRE_SYSTEM_UPDATE . '?mod=version' . ($tqb->Config('AppCentre')->checkbeta==true?'?beta':''));

?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
    <?php 
	echo '<a href="../../../admin/admin/?act=UploadAdmin&id=1"><span class="m-left">附件管理</span></a>';
    echo '<a href="update.php"><span class="m-left m-now">系统更新与校验</span></a>';
	echo '<a href="theme_edit.php"><span class="m-left">新建主题</span></a>';
	echo '<a href="plugin_edit.php"><span class="m-left">新建插件</span></a>';
	echo '<a href="setting.php"><span class="m-left">设置</span></a>';
	?>
  </div>
  <div id="divMain2">

            <form method="post" action="">
              <table border="1" width="100%" cellspacing="0" cellpadding="0" class="tableBorder tableBorder-thcenter">
                <tr>
                  <th width='50%'>当前版本</th>
                  <th>最新版本</th>
                </tr>
                <tr>
                  <td align='center' id='now'>TQBlog <?php echo CFG_BLOG_VERSION?></td>
                  <td align='center' id='last'>TQBlog <?php echo $newversion;?></td>
                </tr>
              </table>
              <p>
                
<?php

$nowbuild=(int)$blogversion;
$newbuild=(int)substr($newversion,-8,8);

if($newbuild-$nowbuild>0){
	echo '<input id="updatenow" type="button" onclick="location.href=\'?update='.$nowbuild.'-'.$newbuild.'\'" value="升级新版程序" />';
}
?>
              </p>
			  <hr/>

              <div class="divHeader">校验系统核心文件&nbsp;&nbsp;<span id="checknow"><a href="?check=now" title="开始校验"><img src="images/refresh.png" width="16" alt="校验" /></a></span></div>
			  <!--<div>进度<span id="status">0</span>%；已发现<span id="count">0</span>个修改过的系统文件。<div id="bar"></div></div>-->
              <table border="1" width="100%" cellspacing="0" cellpadding="0" class="tableBorder tableBorder-thcenter">
                <tr>
                  <th width='78%'>文件名</th>
                  <th id="_s">状态</th>
                </tr>
<?php
//if (file_exists($tqb->contentdir . 'cache/now.xml')) {
if ($nowxml!=''){

  $i=0;
  libxml_use_internal_errors(true);
  //$xml=simplexml_load_file($tqb->contentdir . 'cache/now.xml');
  $xml=simplexml_load_string($nowxml);
  if($xml){
  foreach ($xml->children() as $file) {
  	if(file_exists($f=$tqb->path . str_replace('\\','/',$file['name']))){
		$f=file_get_contents($f);
	  	$newcrc32=substr(strtoupper(dechex(crc32_signed($f))),-8);
		$f=str_replace("\n","\r\n",$f);
		$newcrc32_2=substr(strtoupper(dechex(crc32_signed($f))),-8);
  	}else{
  		$newcrc32='';
		$newcrc32_2='';
  	}
	//echo PHP_INT_SIZE;
    if( ($newcrc32 == $file['crc32']) || ($newcrc32_2 == $file['crc32']) ){
      echo '<tr style="display:none;"><td><b>' . str_replace('\\','/',$file['name']) . '</b></td>';
    	$s='<img src="'.$tqb->host.'admin/image/admin/ok.png" width="16" alt="" />';
    }else{
      $i+=1;
      echo '<tr><td><b>' . str_replace('\\','/',$file['name']) . '</b></td>';
    	$s='<a href="javascript:void(0)" onclick="restore(\''.base64_encode($file['name']).'\',\'file'.md5($file['name']) .'\')" class="button" title="还原系统文件"><img src="'.$tqb->host.'admin/image/admin/exclamation.png" width="16" alt=""></a>';
    }
    echo '<td class="tdCenter" id="file' . md5($file['name']) . '">' . $s . '</td></tr>';
  }
  }
  echo '<tr><th colspan="2">'.$i.'个文件不同或被修改过.</tr>';
  //@unlink($tqb->contentdir . 'cache/now.xml');
}
?>

              </table>
              <p> </p>
            </form>
<script type="text/javascript">
function restore(f,id){
	$.get(bloghost+"content/plugin/AppCentre/update.php?restore="+f, function(data){
		//alert(data);
		$('#'+id).html(data);
	});
}
</script>            
	<script type="text/javascript">ActiveLeftMenu("aUploadAdmin");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'admin/image/admin/tools_32.png';?>");</script>	
  </div>
</div>


<?php
require $blogpath . 'admin/admin/admin_footer.php';

RunTime();
?>
<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: function_admin.php 33828 2008-02-22 09:25:26Z team $
 */

Add_Filter_Plugin('Filter_Plugin_Admin_PageAdmin_SubMenu','tqb_admin_addpagesubmenu');
Add_Filter_Plugin('Filter_Plugin_Admin_TagAdmin_SubMenu','tqb_admin_addtagsubmenu');
Add_Filter_Plugin('Filter_Plugin_Admin_CategoryAdmin_SubMenu','tqb_admin_addcatesubmenu');
Add_Filter_Plugin('Filter_Plugin_Admin_MemberAdmin_SubMenu','tqb_admin_addmemsubmenu');
Add_Filter_Plugin('Filter_Plugin_Admin_ModuleAdmin_SubMenu','tqb_admin_addmodsubmenu');
Add_Filter_Plugin('Filter_Plugin_Admin_CommentAdmin_SubMenu','tqb_admin_addcmtsubmenu');
Add_Filter_Plugin('Filter_Plugin_Admin_UploadAdmin_SubMenu','tqb_admin_addtoolsubmenu');

$tqb->LoadTemplates();
$manage=true;

//********************************************************
function tqb_admin_addpagesubmenu(){
	echo '<a href="../admin.php?act=PageEdt"><span class="m-left">' . $GLOBALS['lang']['msg']['new_page'] . '</span></a>';
}

function tqb_admin_addtagsubmenu(){
	echo '<a href="../admin.php?act=TagEdt"><span class="m-left">' . $GLOBALS['lang']['msg']['new_tag'] . '</span></a>';
}

function tqb_admin_addcatesubmenu(){
	echo '<a href="../admin.php?act=CategoryEdt"><span class="m-left">' . $GLOBALS['lang']['msg']['new_category'] . '</span></a>';
}

function tqb_admin_addmemsubmenu(){
	global $tqb;
	if($tqb->CheckRights('MemberNew')){
		echo '<a href="../admin.php?act=MemberNew"><span class="m-left">' . $GLOBALS['lang']['msg']['new_member'] . '</span></a>';
	}
}

function tqb_admin_addmodsubmenu(){
	echo '<a href="../admin.php?act=ModuleEdt"><span class="m-left">' . $GLOBALS['lang']['msg']['new_module'] . '</span></a>';
	echo '<a href="../admin.php?act=ModuleEdt&amp;filename=navbar"><span class="m-left">' . $GLOBALS['lang']['msg']['module_navbar'] . '</span></a>';
	echo '<a href="../admin.php?act=ModuleEdt&amp;filename=link"><span class="m-left">' . $GLOBALS['lang']['msg']['module_link'] . '</span></a>';
	echo '<a href="../admin.php?act=ModuleEdt&amp;filename=favorite"><span class="m-left">' . $GLOBALS['lang']['msg']['module_favorite'] . '</span></a>';
	echo '<a href="../admin.php?act=ModuleEdt&amp;filename=misc"><span class="m-left">' . $GLOBALS['lang']['msg']['module_misc'] . '</span></a>';
}
function tqb_admin_addcmtsubmenu(){
	global $tqb;
	if($tqb->CheckRights('CommentAll')){
		$n=GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(comm_ID) AS num FROM ' . $GLOBALS['table']['Comment'] . ' WHERE comm_Ischecking=1'),'num');
		if($n!=0){$n=' ('.$n.')';}else{$n='';}
		echo '<a href="../admin.php?act=CommentAdmin&amp;ischecking=1"><span class="m-left '.(GetVars('ischecking')?'m-now':'').'">' . $GLOBALS['lang']['msg']['check_comment']  . $n . '</span></a>';
	}
}
function tqb_admin_addtoolsubmenu(){
	echo '<a href="../admin/?act=UploadAdmin&id=1"><span class="m-left '.(GetVars('id','GET')==1?'m-now':'').'">' . $GLOBALS['lang']['msg']['tools_upload'] . '</span></a>';
	echo '<a href="../../content/plugin/AppCentre/update.php"><span class="m-left">' . $GLOBALS['lang']['msg']['tools_update'] . '</span></a>';
	echo '<a href="../../content/plugin/AppCentre/theme_edit.php"><span class="m-left">' . '新建主题' . '</span></a>';
	echo '<a href="../../content/plugin/AppCentre/plugin_edit.php"><span class="m-left">' . '新建插件' . '</span></a>';
	echo '<a href="../../content/plugin/AppCentre/setting.php"><span class="m-left">' . '设置' . '</span></a>';
}

//********************************************************
$topmenus=array();
$leftmenus=array();

function ResponseAdmin_LeftMenu(){

	global $tqb;
	global $leftmenus;

	$leftmenus[]=BuildLeftMenu("ArticleEdt",$tqb->lang['msg']['new_article'],$tqb->host . "admin/admin.php?act=ArticleEdt","nav_new","aArticleEdt","");
	$leftmenus[]=BuildLeftMenu("ArticleAdmin",$tqb->lang['msg']['article_manage'],$tqb->host . "admin/admin.php?act=ArticleAdmin","nav_article","aArticleAdmin","");
	$leftmenus[]=BuildLeftMenu("PageAdmin",$tqb->lang['msg']['page_manage'],$tqb->host . "admin/admin.php?act=PageAdmin","nav_page","aPageAdmin","");
	$leftmenus[]=BuildLeftMenu("CommentAdmin",$tqb->lang['msg']['comment_manage'],$tqb->host . "admin/admin.php?act=CommentAdmin","nav_comments","aCommentAdmin","");

	$leftmenus[]="<li class='split'><hr/></li>";


	$leftmenus[]=BuildLeftMenu("CategoryAdmin",$tqb->lang['msg']['category_manage'],$tqb->host . "admin/admin.php?act=CategoryAdmin","nav_category","aCategoryAdmin","");
	$leftmenus[]=BuildLeftMenu("TagAdmin",$tqb->lang['msg']['tag_manage'],$tqb->host . "admin/admin.php?act=TagAdmin","nav_tags","aTagAdmin","");
	$leftmenus[]=BuildLeftMenu("MemberAdmin",$tqb->lang['msg']['member_manage'],$tqb->host . "admin/admin.php?act=MemberAdmin","nav_user","aMemberAdmin","");

	$leftmenus[]="<li class='split'><hr/></li>";

	$leftmenus[]=BuildLeftMenu("ThemeAdmin",$tqb->lang['msg']['theme_manage'],$tqb->host . "admin/admin.php?act=ThemeAdmin","nav_themes","aThemeAdmin","");
	$leftmenus[]=BuildLeftMenu("ModuleAdmin",$tqb->lang['msg']['module_manage'],$tqb->host . "admin/admin.php?act=ModuleAdmin","nav_function","aModuleAdmin","");
	//$leftmenus[]=BuildLeftMenu("PluginAdmin",$tqb->lang['msg']['plugin_manage'],$tqb->host . "admin/admin.php?act=PluginAdmin","nav_plugin","aPluginAdmin","");
	$leftmenus[]=BuildLeftMenu("UploadAdmin",$tqb->lang['msg']['tools_manage'],$tqb->host . "admin/admin.php?act=UploadAdmin&id=1","nav_tools","aUploadAdmin","");

	foreach ($GLOBALS['Filter_Plugin_Admin_LeftMenu'] as $fpname => &$fpsignal) {
		$fpname($leftmenus);
	}

	foreach ($leftmenus as $m) {
		echo $m;
	}

}

function ResponseAdmin_TopMenu(){

	global $tqb;
	global $topmenus;

	$topmenus[]=MakeTopMenu("admin",$tqb->lang['msg']['dashboard'],$tqb->host . "admin/admin.php?act=admin","","");
	$topmenus[]=MakeTopMenu("SettingAdmin",$tqb->lang['msg']['settings'],$tqb->host . "admin/admin.php?act=SettingAdmin","","");

	foreach ($GLOBALS['Filter_Plugin_Admin_TopMenu'] as $fpname => &$fpsignal) {
		$fpname($topmenus);
	}

	$topmenus[]=MakeTopMenu("misc",$tqb->lang['msg']['official_website'],"http://www.tqtqtq.com/","_blank","");

	foreach ($topmenus as $m) {
		echo $m;
	}

}

function MakeTopMenu($requireAction,$strName,$strUrl,$strTarget,$strLiId){
	global $tqb;

	static $AdminTopMenuCount=0;
	if ($tqb->CheckRights($requireAction)==false) {
		return null;
	}

	$tmp=null;
	if($strTarget==""){$strTarget="_self";}
	$AdminTopMenuCount=$AdminTopMenuCount+1;
	if($strLiId==""){$strLiId="topmenu" . $AdminTopMenuCount;}
	$tmp="<li id=\"" . $strLiId . "\"><a href=\"" . $strUrl . "\" target=\"" . $strTarget . "\">" . $strName . "</a></li>";
	return $tmp;
}

function BuildLeftMenu($requireAction,$strName,$strUrl,$strLiId,$strAId,$strImgUrl){
	global $tqb;

	static $AdminLeftMenuCount=0;
	if ($tqb->CheckRights($requireAction)==false) {
		return null;
	}

	$AdminLeftMenuCount=$AdminLeftMenuCount+1;
	$tmp=null;
	if($strImgUrl!=""){
		$tmp="<li id=\"" . $strLiId . "\"><a id=\"" . $strAId . "\" href=\"" . $strUrl . "\"><span style=\"background-image:url('" . $strImgUrl . "')\">" . $strName . "</span></a></li>";
	}else{
		$tmp="<li id=\"" . $strLiId . "\"><a id=\"" . $strAId . "\" href=\"" . $strUrl . "\"><span>" . $strName . "</span></a></li>";
	}
	return $tmp;

}

//********************************************************
function CreateOptoinsOfCategorys($default){
	global $tqb;

	foreach ($GLOBALS['Filter_Plugin_CreateOptoinsOfCategorys'] as $fpname => &$fpsignal) {
		$fpreturn=$fpname($default);
		if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
	}

	$s=null;
	foreach ($tqb->categorysbyorder as $id => $cate) {
	  $s.='<option ' . ($default==$cate->ID?'selected="selected"':'') . ' value="'. $cate->ID .'">' . $cate->SymbolName . '</option>';
	}

	return $s;
}

function CreateOptoinsOfTemplate($default){
	global $tqb;

	$s=null;
	$s .= '<option value="" >' . $tqb->lang['msg']['none'] . '</option>';
	foreach ($tqb->templates as $key => $value) {
		if(substr($key,0,2)=='b_')continue;
		if(substr($key,0,2)=='c_')continue;
		if(substr($key,0,5)=='post-')continue;
		if(substr($key,0,6)=='module')continue;
		if(substr($key,0,6)=='header')continue;
		if(substr($key,0,6)=='footer')continue;
		if(substr($key,0,7)=='comment')continue;
		if(substr($key,0,7)=='sidebar')continue;
		if(substr($key,0,7)=='pagebar')continue;
		if($default==$key){
			$s .= '<option value="' . $key . '" selected="selected">' . $key . ' ('.$tqb->lang['msg']['default_template'].')' . '</option>';
		}else{
			$s .= '<option value="' . $key . '" >' . $key . '</option>';
		}
	}

	return $s;
}

function CreateOptoinsOfMemberLevel($default){
	global $tqb;

	$s=null;
	if(!$tqb->CheckRights('MemberAll')){
		return '<option value="' . $default . '" selected="selected" >' . $tqb->lang['user_level_name'][$default] . '</option>';
	}
	for ($i=1; $i <7 ; $i++) {
		$s .= '<option value="' . $i . '" ' . ($default==$i?'selected="selected"':'') . ' >' . $tqb->lang['user_level_name'][$i] . '</option>';
	}
	return $s;
}

function CreateOptoinsOfMember($default){
	global $tqb;

	$s=null;
	if(!$tqb->CheckRights('ArticleAll')){
		if(!isset($tqb->members[$default]))return '<option value="0" selected="selected" ></option>';
		return '<option value="' . $default . '" selected="selected" >' . $tqb->members[$default]->Name . '</option>';
	}
	foreach ($tqb->members as $key => $value) {
		if($tqb->CheckRightsByLevel($tqb->members[$key]->Level,'ArticleEdt')){
			$s .= '<option value="' . $key . '" ' . ($default==$key?'selected="selected"':'') . ' >' . $tqb->members[$key]->Name . '</option>';
		}
	}
	return $s;
}

function CreateOptoinsOfPostStatus($default){
	global $tqb;

	$s=null;
	if(!$tqb->CheckRights('ArticlePub')&&$default==2){
		return '<option value="2" ' . ($default==2?'selected="selected"':'') . ' >' . $tqb->lang['post_status_name']['2'] . '</option>';
	}
	if(!$tqb->CheckRights('ArticleAll')&&$default==2){
		return '<option value="2" ' . ($default==2?'selected="selected"':'') . ' >' . $tqb->lang['post_status_name']['2'] . '</option>';
	}
	$s .= '<option value="0" ' . ($default==0?'selected="selected"':'') . ' >' . $tqb->lang['post_status_name']['0'] . '</option>';
	$s .= '<option value="1" ' . ($default==1?'selected="selected"':'') . ' >' . $tqb->lang['post_status_name']['1'] . '</option>';
	if($tqb->CheckRights('ArticleAll')){
		$s .= '<option value="2" ' . ($default==2?'selected="selected"':'') . ' >' . $tqb->lang['post_status_name']['2'] . '</option>';
	}
	return $s;
}

function CreateOptoinsOfPostOrigin($default){
	global $tqb;

	$s=null;
	$s .= '<option value="0" ' . ($default==0?'selected="selected"':'') . ' >' . $tqb->lang['post_origin_name']['0'] . '</option>';
	$s .= '<option value="1" ' . ($default==1?'selected="selected"':'') . ' >' . $tqb->lang['post_origin_name']['1'] . '</option>';
	return $s;
}

function CreateModuleDiv($m,$button=true){
	global $tqb;

	echo '<div class="widget widget_source_' . $m->SourceType . ' widget_id_' . $m->FileName . '">';
	echo '<div class="widget-title"><img class="more-action" width="16" src="../image/admin/module_16.png" alt="" />' . ($m->SourceType!='theme'?$m->Name:$m->FileName) . '';

	if($button){
		if($m->SourceType!='theme'){
			echo '<span class="widget-action"><a href="../admin.php?act=ModuleEdt&amp;id=' . $m->ID . '"><img class="edit-action" src="../image/admin/module_edit.png" alt="'.$tqb->lang['msg']['edit'].'" title="'.$tqb->lang['msg']['edit'].'" width="16" /></a>';
		}else{
			echo '<span class="widget-action"><a href="../admin.php?act=ModuleEdt&amp;source=theme&amp;filename=' . $m->FileName . '"><img class="edit-action" src="../image/admin/module_edit.png" alt="'.$tqb->lang['msg']['edit'].'" title="'.$tqb->lang['msg']['edit'].'" width="16" /></a>';
			echo '&nbsp;<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=ModuleDel&amp;source=theme&amp;filename=' . $m->FileName . '&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
		}
		if($m->SourceType!='system'&&$m->SourceType!='theme'){
			echo '&nbsp;<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=ModuleDel&amp;id=' . $m->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
		}
		echo '</span>';
	}

	echo '</div>';
	echo '<div class="funid" style="display:none">' . $m->FileName . '</div>';
	echo '</div>';
}

function CreateOptionsOfTimeZone($default){
	global $tqb;
	$s='';
$tz=array
     (
		'Etc/GMT+12' => '-12:00',
		'Pacific/Midway' => '-11:00',
		'Pacific/Honolulu' => '-10:00',
		'America/Anchorage' => '-09:00',
		'America/Los_Angeles' => '-08:00',
		'America/Denver' => '-07:00',
		'America/Tegucigalpa' => '-06:00',
		'America/New_York' => '-05:00',
		'America/Halifax' => '-04:00',
		'America/Argentina/Buenos_Aires' => '-03:00',
		'Atlantic/South_Georgia' => '-02:00',
		'Atlantic/Azores' => '-01:00',
		'UTC' => '00:00',
		'Europe/Berlin' => '+01:00',
		'Europe/Sofia' => '+02:00',
		'Africa/Nairobi' => '+03:00',
		'Europe/Moscow' => '+04:00',
		'Asia/Karachi' => '+05:00',
		'Asia/Dhaka' => '+06:00',
		'Asia/Bangkok' => '+07:00',
		'Asia/Shanghai' => '+08:00',
		'Asia/Tokyo' => '+09:00',
		'Pacific/Guam' => '+10:00',
		'Australia/Sydney' => '+11:00',
		'Pacific/Fiji' => '+12:00',
		'Pacific/Tongatapu' => '+13:00'
     );

	foreach ($tz as $key => $value) {
		$s .= '<option value="' . $key . '" ' . ($default==$key?'selected="selected"':'') . ' >' . $key . ' ' . $value . '</option>';
	}

	return $s;
}

function CreateOptionsOfLang($default){
	global $tqb;
	$s='';
	$dir=$tqb->contentdir . 'language/';
	$files=GetFilesInDir($dir,'php');
	foreach($files as $f){
		$n=basename($f,'.php');
		$t= require($f);
		$s.= '<option value="' . $n . '" ' . ($default==$n?'selected="selected"':'') . ' >' . $t['lang'] .' ('. $n .')'. '</option>';
	}
	return $s;
}

//********************************************************
function Admin_SiteInfo(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['info_intro'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_SiteInfo_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';
	
	echo '<table class="tableFull tableBorder"><tr><th colspan="4"  scope="col">&nbsp;' . $tqb->lang['msg']['subscribe_title'] . '</th></tr>';	
	echo '<tr><td class="td20">';
	echo '<form class="search" id="subscribe" method="post" action="http://localhost/tqtqtq/subscribe.php?mod=subscribe" target="_blank">';
	echo '<p>' . $tqb->lang['msg']['subscribe_desc'] . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="itemid" value="4" /><input name="email" style="width:300px;" type="text" value="" /> &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="' . $tqb->lang['msg']['subscribe'] . '"/></p>';
	echo '</form>';
	echo '</td></tr>';	
	echo '</table>';

	echo '<table class="tableFull tableBorder" id="tbStatistic"><tr><th colspan="4"  scope="col">&nbsp;' . $tqb->lang['msg']['site_analyze'] . '&nbsp;<a href="javascript:statistic(\'?act=misc&amp;type=statistic\');" id="statistic">[' . $tqb->lang['msg']['refresh_cache'] . ']</a> <img id="statloading" style="display:none" src="../image/admin/loading.gif" alt=""/></th></tr>';

	if((time()-(int)$tqb->cache->reload_statistic_time) > (23*60*60) && $tqb->CheckRights('root')){
		echo '<script type="text/javascript">$(document).ready(function(){ statistic(\'?act=misc&type=statistic\'); });</script>';
	}else{
		$r=$tqb->cache->reload_statistic;
		$r=str_replace('{$tqb->user->Name}', $tqb->user->Name, $r);
		echo $r;
	}

	echo '</table>';

	echo '<table class="tableFull tableBorder" id="tbUpdateInfo"><tr><th>&nbsp;' . $tqb->lang['msg']['latest_news'] . '&nbsp;<a href="javascript:updateinfo(\'?act=misc&amp;type=updateinfo\');">[' . $tqb->lang['msg']['refresh'] . ']</a> <img id="infoloading" style="display:none" src="../image/admin/loading.gif" alt=""/></th></tr>';

	if((time()-(int)$tqb->cache->reload_updateinfo_time) > (23*60*60) && $tqb->CheckRights('root')){
		echo '<script type="text/javascript">$(document).ready(function(){ updateinfo(\'?act=misc&type=updateinfo\'); });</script>';
	}else{
		echo $tqb->cache->reload_updateinfo;
	}

	echo '</table>';

	echo '</div>';
	include $tqb->path . "admin/script/thanks.js";
	echo '<script type="text/javascript">ActiveTopMenu("topmenu1");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/home_32.png' . '");</script>';

}

//********************************************************
function Admin_ArticleAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['article_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_ArticleAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';
	echo '<form class="search" id="search" method="post" action="#">';

	echo '<p>' . $tqb->lang['msg']['search'] . ':&nbsp;&nbsp;' . $tqb->lang['msg']['category'] . ' <select class="edit" size="1" name="category" style="width:150px;" ><option value="">' . $tqb->lang['msg']['any'] . '</option>';
	foreach ($tqb->categorysbyorder as $id => $cate) {
	  echo '<option value="'. $cate->ID .'">' . $cate->SymbolName . '</option>';
	}
	echo'</select>&nbsp;&nbsp;&nbsp;&nbsp;' . $tqb->lang['msg']['type'] . ' <select class="edit" size="1" name="status" style="width:80px;" ><option value="">' . $tqb->lang['msg']['any'] . '</option> <option value="0" >' . $tqb->lang['post_status_name']['0'] . '</option><option value="1" >' . $tqb->lang['post_status_name']['1'] . '</option><option value="2" >' . $tqb->lang['post_status_name']['2'] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
	<label><input type="checkbox" name="istop" value="True"/>&nbsp;' . $tqb->lang['msg']['top'] . '</label>&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="search" style="width:250px;" type="text" value="" /> &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="' . $tqb->lang['msg']['submit'] . '"/></p>';
	echo '</form>';
	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>
	<th>' . $tqb->lang['msg']['id'] . '</th>
	<th>' . $tqb->lang['msg']['category'] . '</th>
	<th>' . $tqb->lang['msg']['author'] . '</th>
	<th>' . $tqb->lang['msg']['title'] . '</th>
	<th>' . $tqb->lang['msg']['date'] . '</th>
	<th>' . $tqb->lang['msg']['comment'] . '</th>
	<th>' . $tqb->lang['msg']['status'] . '</th>
	<th></th>
	</tr>';

$p=new Pagebar('{%host%}admin/admin.php?act=ArticleAdmin{&page=%page%}{&status=%status%}{&istop=%istop%}{&category=%category%}{&search=%search%}',false);
$p->PageCount=$tqb->managecount;
$p->PageNow=(int)GetVars('page','GET')==0?1:(int)GetVars('page','GET');
$p->PageBarCount=$tqb->pagebarcount;

$p->UrlRule->Rules['{%category%}']=GetVars('category');
$p->UrlRule->Rules['{%search%}']=urlencode(GetVars('search'));
$p->UrlRule->Rules['{%status%}']=GetVars('status');
$p->UrlRule->Rules['{%istop%}']=(boolean)GetVars('istop');

$w=array();
if(!$tqb->CheckRights('ArticleAll')){
	$w[]=array('=','log_AuthorID',$tqb->user->ID);
}
if(GetVars('search')){
	$w[]=array('search','log_Content','log_Intro','log_Title',GetVars('search'));
}
if(GetVars('istop')){
	$w[]=array('=','log_Istop','1');
}
if(GetVars('status')){
	$w[]=array('=','log_Status',GetVars('status'));
}
if(GetVars('category')){
	$w[]=array('=','log_CateID',GetVars('category'));
}

$array=$tqb->GetArticleList(
	'',
	$w,
	array('log_PostTime'=>'DESC'),
	array(($p->PageNow-1) * $p->PageCount,$p->PageCount),
	array('pagebar'=>$p),
	false
);

foreach ($array as $article) {
	echo '<tr>';
	echo '<td class="td5">' . $article->ID .  '</td>';
	echo '<td class="td10">' . $article->Category->Name . '</td>';
	echo '<td class="td10">' . $article->Author->Name . '</td>';
	echo '<td><a href="'.$article->Url.'" target="_blank"><img src="../image/admin/link.png" alt="" title="" width="16" /></a> ' . $article->Title . '</td>';
	echo '<td class="td20">' .$article->Time() . '</td>';
	echo '<td class="td5">' . $article->CommNums . '</td>';
	echo '<td class="td5">' . ($article->IsTop?$tqb->lang['msg']['top'].'|':'').$article->StatusName . '</td>';
	echo '<td class="td10 tdCenter">';
	echo '<a href="../admin.php?act=ArticleEdt&amp;id='. $article->ID .'"><img src="../image/admin/page_edit.png" alt="'.$tqb->lang['msg']['edit'] .'" title="'.$tqb->lang['msg']['edit'] .'" width="16" /></a>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=ArticleDel&amp;id='. $article->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
	echo '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '<hr/><p class="pagebar">';

foreach ($p->buttons as $key => $value) {
	echo '<a href="'. $value .'">' . $key . '</a>&nbsp;&nbsp;' ;
}

	echo '</p></div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aArticleAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/article_32.png' . '");</script>';

}

//********************************************************
function Admin_PageAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['page_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_PageAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';
	echo '<!--<form class="search" id="search" method="post" action="#"></form>-->';
	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>
	<th>' . $tqb->lang['msg']['id'] . '</th>
	<th>' . $tqb->lang['msg']['author'] . '</th>
	<th>' . $tqb->lang['msg']['title'] . '</th>
	<th>' . $tqb->lang['msg']['date'] . '</th>
	<th>' . $tqb->lang['msg']['comment'] . '</th>
	<th>' . $tqb->lang['msg']['status'] . '</th>
	<th></th>
	</tr>';

$p=new Pagebar('{%host%}admin/admin.php?act=PageAdmin{&page=%page%}',false);
$p->PageCount=$tqb->managecount;
$p->PageNow=(int)GetVars('page','GET')==0?1:(int)GetVars('page','GET');
$p->PageBarCount=$tqb->pagebarcount;

$w=array();
if(!$tqb->CheckRights('PageAll')){
	$w[]=array('=','log_AuthorID',$tqb->user->ID);
}

$array=$tqb->GetPageList(
	'',
	$w,
	array('log_PostTime'=>'DESC'),
	array(($p->PageNow-1) * $p->PageCount,$p->PageCount),
	array('pagebar'=>$p)
);

foreach ($array as $article) {
	echo '<tr>';
	echo '<td class="td5">' . $article->ID . '</td>';
	echo '<td class="td10">' . $article->Author->Name . '</td>';
	echo '<td><a href="'.$article->Url.'" target="_blank"><img src="../image/admin/link.png" alt="" title="" width="16" /></a> ' . $article->Title . '</td>';
	echo '<td class="td20">' . $article->Time() . '</td>';
	echo '<td class="td5">' . $article->CommNums . '</td>';
	echo '<td class="td5">' . $article->StatusName . '</td>';
	echo '<td class="td10 tdCenter">';
	echo '<a href="../admin.php?act=PageEdt&amp;id='. $article->ID .'"><img src="../image/admin/page_edit.png" alt="'.$tqb->lang['msg']['edit'] .'" title="'.$tqb->lang['msg']['edit'] .'" width="16" /></a>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=PageDel&amp;id='. $article->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
	echo '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '<hr/><p class="pagebar">';
foreach ($p->buttons as $key => $value) {
	echo '<a href="'. $value .'">' . $key . '</a>&nbsp;&nbsp;' ;
}
	echo '</p></div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aPageAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/page_32.png' . '");</script>';

}

//********************************************************
function Admin_CategoryAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['category_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_CategoryAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';
	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>

	<th>' . $tqb->lang['msg']['id'] . '</th>
	<th>' . $tqb->lang['msg']['order'] . '</th>
	<th>' . $tqb->lang['msg']['name'] . '</th>
	<th>' . $tqb->lang['msg']['alias'] . '</th>
	<th>' . $tqb->lang['msg']['post_count'] . '</th>
	<th></th>
	</tr>';


foreach ($tqb->categorysbyorder as $category) {
	echo '<tr>';
	echo '<td class="td5">' . $category->ID . '</td>';
	echo '<td class="td5">' . $category->Order . '</td>';
	echo '<td class="td25"><a href="'.$category->Url .'" target="_blank"><img src="../image/admin/link.png" alt="" title="" width="16" /></a> ' . $category->Symbol . $category->Name . '</td>';
	echo '<td class="td20">' . $category->Alias . '</td>';
	echo '<td class="td10">' . $category->Count . '</td>';
	echo '<td class="td10 tdCenter">';
	echo '<a href="../admin.php?act=CategoryEdt&amp;id='. $category->ID .'"><img src="../image/admin/folder_edit.png" alt="'.$tqb->lang['msg']['edit'] .'" title="'.$tqb->lang['msg']['edit'] .'" width="16" /></a>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=CategoryDel&amp;id='. $category->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
	echo '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '</div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aCategoryAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/category_32.png' . '");</script>';

}

//********************************************************
function Admin_CommentAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['comment_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_CommentAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';

	echo '<form class="search" id="search" method="post" action="#">';
	echo '<p>' . $tqb->lang['msg']['search'] . '&nbsp;&nbsp;&nbsp;&nbsp;<input name="search" style="width:450px;" type="text" value="" /> &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="' . $tqb->lang['msg']['submit'] . '"/></p>';
	echo '</form>';
	echo '<form method="post" action="'.$tqb->host.'admin/admin.php?act=CommentBat">';
	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>
	<th>' . $tqb->lang['msg']['id'] . '</th>
	<th>' . $tqb->lang['msg']['parend_id'] . '</th>
	<th>' . $tqb->lang['msg']['name'] . '</th>
	<th>' . $tqb->lang['msg']['content'] . '</th>
	<th>' . $tqb->lang['msg']['article'] . '</th>
	<th>' . $tqb->lang['msg']['date'] . '</th>
	<th>' . ''. '</th>
	<th><a href="" onclick="BatchSelectAll();return false;">' . $tqb->lang['msg']['select_all'] . '</a></th>
	</tr>';

$p=new Pagebar('{%host%}admin/admin.php?act=CommentAdmin{&page=%page%}{&ischecking=%ischecking%}{&search=%search%}',false);
$p->PageCount=$tqb->managecount;
$p->PageNow=(int)GetVars('page','GET')==0?1:(int)GetVars('page','GET');
$p->PageBarCount=$tqb->pagebarcount;

$p->UrlRule->Rules['{%search%}']=urlencode(GetVars('search'));
$p->UrlRule->Rules['{%ischecking%}']=(boolean)GetVars('ischecking');

$w=array();
if(!$tqb->CheckRights('CommentAll')){
	$w[]=array('=','comm_AuthorID',$tqb->user->ID);
}
if(GetVars('search')){
	$w[]=array('search','comm_Content','comm_Name',GetVars('search'));
}

$w[]=array('=','comm_Ischecking',(int)GetVars('ischecking'));

$array=$tqb->GetCommentList(
	'',
	$w,
	array('comm_ID'=>'DESC'),
	array(($p->PageNow-1) * $p->PageCount,$p->PageCount),
	array('pagebar'=>$p)
);

foreach ($array as $cmt) {
	echo '<tr>';
	echo '<td class="td5">' . $cmt->ID .  '</td>';
	echo '<td class="td5">' . $cmt->ParentID . '</td>';
	echo '<td class="td10">' . $cmt->Author->Name . '</td>';
	echo '<td><div style="overflow:hidden;max-width:500px;">' . $cmt->Content . '<div></td>';
	echo '<td class="td5">' . $cmt->LogID .  '</td>';
	echo '<td class="td15">' .$cmt->Time() . '</td>';
	echo '<td class="td10 tdCenter">';
	echo '<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=CommentDel&amp;id='. $cmt->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
if(!GetVars('ischecking','GET')){
	echo '<a href="../admin.php?act=CommentChk&amp;id='. $cmt->ID .'&amp;ischecking='.(int)!GetVars('ischecking','GET').'&token='. $tqb->GetToken() .'"><img src="../image/admin/comments_check.png" alt="'.$tqb->lang['msg']['audit'] .'" title="'.$tqb->lang['msg']['audit'] .'" width="16" /></a>';
}else{
	echo '<a href="../admin.php?act=CommentChk&amp;id='. $cmt->ID .'&amp;ischecking='.(int)!GetVars('ischecking','GET').'&token='. $tqb->GetToken() .'"><img src="../image/admin/ok.png" alt="'.$tqb->lang['msg']['pass'] .'" title="'.$tqb->lang['msg']['pass'] .'" width="16" /></a>';
}
	echo '</td>';
	echo '<td class="td5 tdCenter">' . '<input type="checkbox" id="id'.$cmt->ID.'" name="id[]" value="'.$cmt->ID.'"/>' . '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '<hr/>';

	echo'<p style="float:right;">';

	if((boolean)GetVars('ischecking')){
		echo '<input type="submit" name="all_del"  value="' . $tqb->lang['msg']['all_del'] . '"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<input type="submit" name="all_pass"  value="' . $tqb->lang['msg']['all_pass'] . '"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}else{
		echo '<input type="submit" name="all_del"  value="' . $tqb->lang['msg']['all_del'] . '"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<input type="submit" name="all_audit"  value="' . $tqb->lang['msg']['all_audit'] . '"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}

	echo '</p>';

	echo'<p class="pagebar">';

foreach ($p->buttons as $key => $value) {
	echo '<a href="'. $value .'">' . $key . '</a>&nbsp;&nbsp;' ;
}

	echo '</p>';

	echo '<hr/></form>';

	echo '</div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aCommentAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/comments_32.png' . '");</script>';
}

//********************************************************
function Admin_MemberAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['member_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_MemberAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';
	echo '<!--<form class="search" id="edit" method="post" action="#"></form>-->';
	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>
	<th>' . $tqb->lang['msg']['id'] . '</th>
	<th>' . $tqb->lang['msg']['member_level'] . '</th>
	<th>' . $tqb->lang['msg']['name'] . '</th>
	<th>' . $tqb->lang['msg']['alias'] . '</th>
	<th>' . $tqb->lang['msg']['all_artiles'] . '</th>
	<th>' . $tqb->lang['msg']['all_pages'] . '</th>
	<th>' . $tqb->lang['msg']['all_comments'] . '</th>
	<th>' . $tqb->lang['msg']['all_uploads'] . '</th>
	<th></th>
	</tr>';

$p=new Pagebar('{%host%}admin/admin.php?act=MemberAdmin{&page=%page%}',false);
$p->PageCount=$tqb->managecount;
$p->PageNow=(int)GetVars('page','GET')==0?1:(int)GetVars('page','GET');
$p->PageBarCount=$tqb->pagebarcount;


$w=array();
if(!$tqb->CheckRights('MemberAll')){
	$w[]=array('=','mem_ID',$tqb->user->ID);
}
$array=$tqb->GetMemberList(
	'',
	$w,
	array('mem_ID'=>'ASC'),
	array(($p->PageNow-1) * $p->PageCount,$p->PageCount),
	array('pagebar'=>$p)
);

foreach ($array as $member) {
	echo '<tr>';
	echo '<td class="td5">' . $member->ID . '</td>';
	echo '<td class="td10">' . $member->LevelName . ($member->Status>0?'('.$tqb->lang['user_status_name'][$member->Status].')':'') .'</td>';
	echo '<td><a href="'.$member->Url.'" target="_blank"><img src="../image/admin/link.png" alt="" title="" width="16" /></a> ' . $member->Name . '</td>';
	echo '<td class="td15">' . $member->Alias . '</td>';
	echo '<td class="td10">' . $member->Articles . '</td>';
	echo '<td class="td10">' . $member->Pages . '</td>';
	echo '<td class="td10">' . $member->Comments . '</td>';
	echo '<td class="td10">' . $member->Uploads . '</td>';
	echo '<td class="td10 tdCenter">';
	echo '<a href="../admin.php?act=MemberEdt&amp;id='. $member->ID .'"><img src="../image/admin/user_edit.png" alt="'.$tqb->lang['msg']['edit'] .'" title="'.$tqb->lang['msg']['edit'] .'" width="16" /></a>';
if($tqb->CheckRights('MemberDel')){
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=MemberDel&amp;id='. $member->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
}
	echo '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '<hr/><p class="pagebar">';
foreach ($p->buttons as $key => $value) {
	echo '<a href="'. $value .'">' . $key . '</a>&nbsp;&nbsp;' ;
}
	echo '</p></div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aMemberAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/user_32.png' . '");</script>';
}

//********************************************************
function Admin_UploadAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['tools_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_UploadAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';


	echo '<form class="search" name="upload" id="upload" method="post" enctype="multipart/form-data" action="../admin.php?act=UploadPst">';
	echo '<p>' . $tqb->lang['msg']['upload_file'] . ': </p>';
	echo '<p><input type="file" name="file" size="60" />&nbsp;&nbsp;';
	echo '<input type="submit" class="button" value="' . $tqb->lang['msg']['submit'] . '" onclick="" />&nbsp;&nbsp;';
	echo '<input class="button" type="reset" value="' . $tqb->lang['msg']['reset'] . '" /></p>';
	echo '</form>';

	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>
	<th>' . $tqb->lang['msg']['id'] . '</th>
	<th>' . $tqb->lang['msg']['author'] . '</th>
	<th>' . $tqb->lang['msg']['name'] . '</th>
	<th>' . $tqb->lang['msg']['date'] . '</th>
	<th>' . $tqb->lang['msg']['size'] . '</th>
	<th>' . $tqb->lang['msg']['type'] . '</th>
	<th></th>
	</tr>';

$w=array();
if(!$tqb->CheckRights('UploadAll')){
	$w[]=array('=','ul_AuthorID',$tqb->user->ID);
}

$p=new Pagebar('{%host%}admin/admin.php?act=UploadAdmin{&page=%page%}',false);
$p->PageCount=$tqb->managecount;
$p->PageNow=(int)GetVars('page','GET')==0?1:(int)GetVars('page','GET');
$p->PageBarCount=$tqb->pagebarcount;

$array=$tqb->GetUploadList(
	'',
	$w,
	array('ul_PostTime'=>'DESC'),
	array(($p->PageNow-1) * $p->PageCount,$p->PageCount),
	array('pagebar'=>$p)
);

foreach ($array as $upload) {
	echo '<tr>';
	echo '<td class="td5">' . $upload->ID . '</td>';
	echo '<td class="td10">' . $upload->Author->Name . '</td>';
	echo '<td><a href="'.$upload->Url.'" target="_blank"><img src="../image/admin/link.png" alt="" title="" width="16" /></a> ' . $upload->Name . '</td>';
	echo '<td class="td15">' . $upload->Time() . '</td>';
	echo '<td class="td10">' . $upload->Size . '</td>';
	echo '<td class="td20">' . $upload->MimeType . '</td>';
	echo '<td class="td10 tdCenter">';
	echo '<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=UploadDel&amp;id='. $upload->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
	echo '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '<hr/><p class="pagebar">';
foreach ($p->buttons as $key => $value) {
	echo '<a href="'. $value .'">' . $key . '</a>&nbsp;&nbsp;' ;
}
	echo '</p></div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aUploadAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/tools_32.png' . '");</script>';
}

//********************************************************
function Admin_TagAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['tag_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_TagAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';


	echo '<div id="divMain2">';
	echo '<!--<form class="search" id="edit" method="post" action="#"></form>-->';
	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>
	<th>' . $tqb->lang['msg']['id'] . '</th>
	<th>' . $tqb->lang['msg']['name'] . '</th>
	<th>' . $tqb->lang['msg']['alias'] . '</th>
	<th>' . $tqb->lang['msg']['post_count'] . '</th>
	<th></th>
	</tr>';

$p=new Pagebar('{%host%}admin/admin.php?act=TagAdmin&page={%page%}',false);
$p->PageCount=$tqb->managecount;
$p->PageNow=(int)GetVars('page','GET')==0?1:(int)GetVars('page','GET');
$p->PageBarCount=$tqb->pagebarcount;

$array=$tqb->GetTagList(
	'',
	'',
	array('tag_ID'=>'ASC'),
	array(($p->PageNow-1) * $p->PageCount,$p->PageCount),
	array('pagebar'=>$p)
);

foreach ($array as $tag) {
	echo '<tr>';
	echo '<td class="td5">' . $tag->ID . '</td>';
	echo '<td class="td25"><a href="'.$tag->Url.'" target="_blank"><img src="../image/admin/link.png" alt="" title="" width="16" /></a> ' . $tag->Name . '</td>';
	echo '<td class="td20">' . $tag->Alias . '</td>';
	echo '<td class="td10">' . $tag->Count . '</td>';
	echo '<td class="td10 tdCenter">';
	echo '<a href="../admin.php?act=TagEdt&amp;id='. $tag->ID .'"><img src="../image/admin/tag_edit.png" alt="'.$tqb->lang['msg']['edit'] .'" title="'.$tqb->lang['msg']['edit'] .'" width="16" /></a>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<a onclick="return window.confirm(\''.$tqb->lang['msg']['confirm_operating'] .'\');" href="../admin.php?act=TagDel&amp;id='. $tag->ID .'&token='. $tqb->GetToken() .'"><img src="../image/admin/delete.png" alt="'.$tqb->lang['msg']['del'] .'" title="'.$tqb->lang['msg']['del'] .'" width="16" /></a>';
	echo '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '<hr/><p class="pagebar">';
foreach ($p->buttons as $key => $value) {
	echo '<a href="'. $value .'">' . $key . '</a>&nbsp;&nbsp;' ;
}
	echo '</p></div>';

	echo '<script type="text/javascript">ActiveLeftMenu("aTagAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/tag_32.png' . '");</script>';
}

//********************************************************
function Admin_ThemeAdmin(){

	global $tqb;

	$tqb->LoadThemes();

	echo '<div class="divHeader">' . $tqb->lang['msg']['theme_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_ThemeAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2"><form id="frmTheme" method="post" action="../admin.php?act=ThemeSet">';
	echo '<input type="hidden" name="theme" id="theme" value="" />';
	echo '<input type="hidden" name="style" id="style" value="" />';

	foreach ($tqb->themes as $theme) {

echo '<div class="theme '.($theme->IsUsed()?'theme-now':'theme-other').'">';
echo '<div class="theme-name">';

if($theme->IsUsed() && $theme->path){
echo '<a href="'.$theme->GetManageUrl().'" title="管理" class="button"><img width="16" title="" alt="" src="../image/admin/setting_16.png"/></a>&nbsp;&nbsp;';
}else{
echo '<img width="16" title="" alt="" src="../image/admin/layout.png"/>&nbsp;&nbsp;';
}
echo '<a target="_blank" href="'.$theme->url.'" title=""><strong style="display:none;">'.$theme->id.'</strong>';
echo '<b>'.$theme->name.'</b></a></div>';
echo '<div><img src="'.$theme->GetScreenshot().'" title="'.$theme->name.'" alt="'.$theme->name.'" width="200" height="150" /></div>';
echo '<div class="theme-author">'.$tqb->lang['msg']['author'].': <a target="_blank" href="'.$theme->author_url.'">'.$theme->author_name.'</a></div>';
echo '<div class="theme-style">'.$tqb->lang['msg']['style'].': ';
echo '<select class="edit" size="1" style="width:110px;">';
foreach ($theme->GetCssFiles() as $key => $value) {
	echo '<option value="'.$key.'" '.($theme->IsUsed()?($key==$tqb->style?'selected="selected"':''):'').'>'.basename($value).'</option>';
}
echo '</select>';
echo '<input type="button" onclick="$(\'#style\').val($(this).prev().val());$(\'#theme\').val(\''.$theme->id.'\');$(\'#frmTheme\').submit();" class="theme-activate button" value="'.$tqb->lang['msg']['enable'].'">';
echo '</div>';
echo '</div>';

	}

	echo '</form></div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aThemeAdmin");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/themes_32.png' . '");</script>';

}

//********************************************************
function Admin_ModuleAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['module_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_ModuleAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';

$sm=array();
$um=array();
$tm=array();
$pm=array();

foreach ($tqb->modules as $m) {
	if($m->Source=='system'){
		$sm[]=$m;
	}elseif($m->Source=='user'){
		$um[]=$m;
	}elseif($m->Source=='theme'){
		$tm[]=$m;
	}else{
		$pm[]=$m;
	}
}
	#widget-list begin
	echo '<div class="widget-left">';
	echo '<div class="widget-list">';

	echo '<script type="text/javascript">';
	echo 'var functions = {';
foreach ($tqb->modules as $key => $value) {
	echo "'" . $value->FileName . "':'" . $value->Source . "' ,";
}
	echo "'':''};";
	echo '</script>';
	echo "\r\n";
	echo '<div class="widget-list-header">'. $tqb->lang['msg']['system_module'] .'</div>';
	echo '<div class="widget-list-note">'. $tqb->lang['msg']['drag_module_to_sidebar'] .'</div>';
	echo "\r\n";
foreach ($sm as $m) {
	CreateModuleDiv($m);
}

	echo '<div class="widget-list-header">'. $tqb->lang['msg']['user_module'] .'</div>';
	echo "\r\n";
foreach ($um as $m) {
	CreateModuleDiv($m);
}

	echo '<div class="widget-list-header">'. $tqb->lang['msg']['theme_module'] .'</div>';
	echo "\r\n";
foreach ($tm as $m) {
	CreateModuleDiv($m);
}

	echo '<div class="widget-list-header">'. $tqb->lang['msg']['plugin_module'] .'</div>';
	echo "\r\n";
foreach ($pm as $m) {
	CreateModuleDiv($m);
}

	echo '<hr/>';
	echo "\r\n";
	echo '<form id="edit" method="post" action="../admin.php?act=SidebarSet">';
	echo '<input type="hidden" id="strsidebar" name="edtSidebar" value="'. $tqb->option['CFG_SIDEBAR_ORDER'] .'"/>';
	echo '<input type="hidden" id="strsidebar2" name="edtSidebar2" value="'. $tqb->option['CFG_SIDEBAR2_ORDER'] .'"/>';
	echo '<input type="hidden" id="strsidebar3" name="edtSidebar3" value="'. $tqb->option['CFG_SIDEBAR3_ORDER'] .'"/>';
	echo '<input type="hidden" id="strsidebar4" name="edtSidebar4" value="'. $tqb->option['CFG_SIDEBAR4_ORDER'] .'"/>';
	echo '<input type="hidden" id="strsidebar5" name="edtSidebar5" value="'. $tqb->option['CFG_SIDEBAR5_ORDER'] .'"/>';
	echo '</form>';
	echo "\r\n";
	echo '<div class="clear"></div></div>';
	echo '</div>';
	#widget-list end
	echo "\r\n";
	#siderbar-list begin
	echo '<div class="siderbar-list">';
	echo '<div class="siderbar-drop" id="siderbar"><div class="siderbar-header">' . $tqb->lang['msg']['sidebar'] . '&nbsp;<img class="roll" src="../image/admin/loading.gif" width="16" alt="" /><span class="ui-icon ui-icon-triangle-1-s"></span></div><div  class="siderbar-sort-list" >';
	echo '<div class="siderbar-note" >' . str_replace('%s', Count($tqb->sidebar),$tqb->lang['msg']['sidebar_module_count']) . '</div>';
foreach ($tqb->sidebar as $m) {
	CreateModuleDiv($m,false);
}
	echo '</div></div>';
	echo "\r\n";

	echo '<div class="siderbar-drop" id="siderbar2"><div class="siderbar-header">' . $tqb->lang['msg']['sidebar2'] . '&nbsp;<img class="roll" src="../image/admin/loading.gif" width="16" alt="" /><span class="ui-icon ui-icon-triangle-1-s"></span></div><div  class="siderbar-sort-list" >';
	echo '<div class="siderbar-note" >' . str_replace('%s', Count($tqb->sidebar2),$tqb->lang['msg']['sidebar_module_count']) . '</div>';
foreach ($tqb->sidebar2 as $m) {
	CreateModuleDiv($m,false);
}
	echo '</div></div>';
	echo "\r\n";

	echo '<div class="siderbar-drop" id="siderbar3"><div class="siderbar-header">' . $tqb->lang['msg']['sidebar3'] . '&nbsp;<img class="roll" src="../image/admin/loading.gif" width="16" alt="" /><span class="ui-icon ui-icon-triangle-1-s"></span></div><div  class="siderbar-sort-list" >';
	echo '<div class="siderbar-note" >' . str_replace('%s', Count($tqb->sidebar3),$tqb->lang['msg']['sidebar_module_count']) . '</div>';
foreach ($tqb->sidebar3 as $m) {
	CreateModuleDiv($m,false);
}
	echo '</div></div>';
	echo "\r\n";

	echo '<div class="siderbar-drop" id="siderbar4"><div class="siderbar-header">' . $tqb->lang['msg']['sidebar4'] . '&nbsp;<img class="roll" src="../image/admin/loading.gif" width="16" alt="" /><span class="ui-icon ui-icon-triangle-1-s"></span></div><div  class="siderbar-sort-list" >';
	echo '<div class="siderbar-note" >' . str_replace('%s', Count($tqb->sidebar4),$tqb->lang['msg']['sidebar_module_count']) . '</div>';
foreach ($tqb->sidebar4 as $m) {
	CreateModuleDiv($m,false);
}
	echo '</div></div>';
	echo "\r\n";

	echo '<div class="siderbar-drop" id="siderbar5"><div class="siderbar-header">' . $tqb->lang['msg']['sidebar5'] . '&nbsp;<img class="roll" src="../image/admin/loading.gif" width="16" alt="" /><span class="ui-icon ui-icon-triangle-1-s"></span></div><div  class="siderbar-sort-list" >';
	echo '<div class="siderbar-note" >' . str_replace('%s', Count($tqb->sidebar5),$tqb->lang['msg']['sidebar_module_count']) . '</div>';
foreach ($tqb->sidebar5 as $m) {
	CreateModuleDiv($m,false);
}
	echo '</div></div>';
	echo "\r\n";

	echo '<div class="clear"></div></div>';
	#siderbar-list end
	echo "\r\n";
	echo '<div class="clear"></div>';

	echo '</div>';
	echo "\r\n";

	echo '<script type="text/javascript">ActiveLeftMenu("aModuleAdmin");</script>';
?>
<script type="text/javascript">
	$(function() {
		function sortFunction(){
			var s1="";
			$("#siderbar").find("div.funid").each(function(i){
			   s1 += $(this).html() +"|";
			 });

			 var s2="";
			$("#siderbar2").find("div.funid").each(function(i){
			   s2 += $(this).html() +"|";
			 });

			 var s3="";
			$("#siderbar3").find("div.funid").each(function(i){
			   s3 += $(this).html() +"|";
			 });

			 var s4="";
			$("#siderbar4").find("div.funid").each(function(i){
			   s4 += $(this).html() +"|";
			 });

			 var s5="";
			$("#siderbar5").find("div.funid").each(function(i){
			   s5 += $(this).html() +"|";
			 });

			$("#strsidebar" ).val(s1);
			$("#strsidebar2").val(s2);
			$("#strsidebar3").val(s3);
			$("#strsidebar4").val(s4);
			$("#strsidebar5").val(s5);


			$.post($("#edit").attr("action"),
				{
				"sidebar": s1,
				"sidebar2": s2,
				"sidebar3": s3,
				"sidebar4": s4,
				"sidebar5": s5
				},
			   function(data){
				 //alert("Data Loaded: " + data);
			   });

		};

		var t;
		function hideWidget(item){
				item.find(".ui-icon").removeClass("ui-icon-triangle-1-s").addClass("ui-icon-triangle-1-w");
				t=item.next();
				t.find(".widget").hide("fast").end().show();
				t.find(".siderbar-note>span").text(t.find(".widget").length);
		}
		function showWidget(item){
				item.find(".ui-icon").removeClass("ui-icon-triangle-1-w").addClass("ui-icon-triangle-1-s");
				t=item.next();
				t.find(".widget").show("fast");
				t.find(".siderbar-note>span").text(t.find(".widget").length);
		}

		$(".siderbar-header").toggle( function () {
				hideWidget($(this));
			  },
			  function () {
				showWidget($(this));
			  });

 		$( ".siderbar-sort-list" ).sortable({
 			items:'.widget',
			start:function(event, ui){
				showWidget(ui.item.parent().prev());
				 var c=ui.item.find(".funid").html();
				 if(ui.item.parent().find(".widget:contains("+c+")").length>1){
					ui.item.remove();
				 };
			} ,
			stop:function(event, ui){$(this).parent().find(".roll").show("slow");sortFunction();$(this).parent().find(".roll").hide("slow");
				showWidget($(this).parent().prev());
			}
 		}).disableSelection();

		$( ".widget-list>.widget" ).draggable({
            connectToSortable: ".siderbar-sort-list",
            revert: "invalid",
            containment: "document",
            helper: "clone",
            cursor: "move"
        }).disableSelection();

		$( ".widget-list" ).droppable({
			accept:".siderbar-sort-list>.widget",
            drop: function( event, ui ) {
            	ui.draggable.remove();
            }
        });

});

</script>
<?php
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/module_32.png' . '");</script>';
}

//********************************************************
function Admin_PluginAdmin(){

	global $tqb;

	$tqb->LoadPlugins();

	echo '<div class="divHeader">' . $tqb->lang['msg']['plugin_manage'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_PluginAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';
	echo '<div id="divMain2">';
	echo '<table border="1" class="tableFull tableBorder tableBorder-thcenter">';
	echo '<tr>

	<th></th>
	<th>' . $tqb->lang['msg']['name'] . '</th>
	<th>' . $tqb->lang['msg']['author'] . '</th>
	<th>' . $tqb->lang['msg']['date'] . '</th>
	<th></th>
	</tr>';
$plugins=array();

$app = new App;
if($app->LoadInfoByXml('theme',$tqb->theme)==true){
	if($app->HasPlugin()){
		array_unshift($plugins,$app);
	}
}

$pl=$tqb->option['CFG_USING_PLUGIN_LIST'];
$apl=explode('|',$pl);
foreach ($apl as $name) {
	foreach ($tqb->plugins as $plugin) {
		if($name==$plugin->id){
			$plugins[]=$plugin;
		}
	}
}
foreach ($tqb->plugins as $plugin) {
	if(!$plugin->IsUsed()){
		$plugins[]=$plugin;
	}
}


foreach ($plugins as $plugin) {
	echo '<tr>';
	echo '<td class="td5 tdCenter'.($plugin->type=='plugin'?' plugin':'').($plugin->IsUsed()?' plugin-on':'').'"><strong style="display:none;">'.$plugin->id.'</strong><img ' . ($plugin->IsUsed()?'':'style="opacity:0.2"') . ' src="' . $plugin->GetLogo() . '" alt="" width="32" height="32" /></td>';
	echo '<td class="td25">' . $plugin->name .' '. $plugin->version . '</td>';
	echo '<td class="td20">' . $plugin->author_name . '</td>';
	echo '<td class="td20">' . $plugin->modified . '</td>';
	echo '<td class="td10 tdCenter">';

	if($plugin->type=='plugin'){
		if($plugin->IsUsed()){
			echo '<a href="../admin.php?act=PluginDis&amp;name=' . htmlspecialchars($plugin->id) . '&token='. $tqb->GetToken() .'" title="' . $tqb->lang['msg']['disable'] . '"><img width="16" alt="' . $tqb->lang['msg']['disable'] . '" src="../image/admin/control-power.png"/></a>';
		}else{
			echo '<a href="../admin.php?act=PluginEnb&amp;name=' . htmlspecialchars($plugin->id) . '&token='. $tqb->GetToken() .'" title="' . $tqb->lang['msg']['enable'] . '"><img width="16" alt="' . $tqb->lang['msg']['enable'] . '" src="../image/admin/control-power-off.png"/></a>';
		}
	}
	if($plugin->IsUsed() && $plugin->CanManage()){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<a href="' . $plugin->GetManageUrl() . '" title="' . $tqb->lang['msg']['manage'] . '"><img width="16" alt="' . $tqb->lang['msg']['manage'] . '" src="../image/admin/setting_16.png"/></a>';
	}

	echo '</td>';

	echo '</tr>';
}
	echo '</table>';
	echo '</div>';
	echo '<script type="text/javascript">ActiveLeftMenu("aAppCentre");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/plugin_32.png' . '");</script>';

}

//********************************************************
function Admin_SettingAdmin(){

	global $tqb;

	echo '<div class="divHeader">' . $tqb->lang['msg']['settings'] . '</div>';
	echo '<div class="SubMenu">';
	foreach ($GLOBALS['Filter_Plugin_Admin_SettingAdmin_SubMenu'] as $fpname => &$fpsignal) {
		$fpname();
	}
	echo '</div>';

?>

          <form method="post" action="../admin.php?act=SettingSav<?php echo '&token='. $tqb->GetToken();?>">
            <div id="divMain2">
              <div class="content-box"><!-- Start Content Box -->

                <div class="content-box-header">
                  <ul class="content-box-tabs">
                    <li><a href="#tab1" class="default-tab"><span><?php echo $tqb->lang['msg']['basic_setting']?></span></a></li>
                    <li><a href="#tab2"><span><?php echo $tqb->lang['msg']['global_setting']?></span></a></li>
                    <li><a href="#tab3"><span><?php echo $tqb->lang['msg']['page_setting']?></span></a></li>
                    <li><a href="#tab4"><span><?php echo $tqb->lang['msg']['comment_setting']?></span></a></li>
                  </ul>
                  <div class="clear"></div>
                </div>
                <!-- End .content-box-header -->

                <div class="content-box-content">
<?php

	echo '<div class="tab-content default-tab" style="border:none;padding:0px;margin:0;" id="tab1">';
	echo '<table style="padding:0px;margin:0px;width:100%;">';
	echo '<tr><td class="td25"><p><b>'.$tqb->lang['msg']['blog_host'].'</b><br/><span class="note">&nbsp;'.$tqb->lang['msg']['blog_host_add'].'</span></p></td><td><p><input id="CFG_BLOG_HOST" name="CFG_BLOG_HOST" style="width:600px;" type="text" value="'.$tqb->option['CFG_BLOG_HOST'].'" '.($tqb->option['CFG_PERMANENT_DOMAIN_ENABLE']?'':'readonly="readonly"').' />';
	echo '<p><label onclick="$(\'#CFG_BLOG_HOST\').prop(\'readonly\', $(\'#CFG_PERMANENT_DOMAIN_ENABLE\').val()==0?true:false);"><input type="text" id="CFG_PERMANENT_DOMAIN_ENABLE" name="CFG_PERMANENT_DOMAIN_ENABLE" class="checkbox" value="'.$tqb->option['CFG_PERMANENT_DOMAIN_ENABLE'].'"/></label>&nbsp;&nbsp;'.$tqb->lang['msg']['permanent_domain'].'</p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['blog_name'].'</b></p></td><td><p><input id="CFG_BLOG_NAME" name="CFG_BLOG_NAME" style="width:600px;" type="text" value="'.$tqb->option['CFG_BLOG_NAME'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['blog_subname'].'</b></p></td><td><p><input id="CFG_BLOG_SUBNAME" name="CFG_BLOG_SUBNAME" style="width:600px;"  type="text" value="'.$tqb->option['CFG_BLOG_SUBNAME'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['keywords'].'</b></p></td><td><p><input id="CFG_BLOG_KEYWORDS" name="CFG_BLOG_KEYWORDS" style="width:600px;" type="text" value="'.$tqb->option['CFG_BLOG_KEYWORDS'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['description'].'</b></p></td><td><p><input id="CFG_BLOG_DESCRIPTION" name="CFG_BLOG_DESCRIPTION" style="width:600px;"  type="text" value="'.$tqb->option['CFG_BLOG_DESCRIPTION'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['email_admin'].'</b></p></td><td><p><input id="CFG_BLOG_EMAIL" name="CFG_BLOG_EMAIL" style="width:600px;" type="text" value="'.$tqb->option['CFG_BLOG_EMAIL'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['qq_admin'].'</b></p></td><td><p><input id="CFG_BLOG_QQ" name="CFG_BLOG_QQ" style="width:600px;"  type="text" value="'.$tqb->option['CFG_BLOG_QQ'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['copyright'].'</b><br/><span class="note">&nbsp;'.$tqb->lang['msg']['copyright_add'].'</span></p></td><td><p><textarea cols="3" rows="6" id="CFG_BLOG_COPYRIGHT" name="CFG_BLOG_COPYRIGHT" style="width:600px;">'.htmlspecialchars($tqb->option['CFG_BLOG_COPYRIGHT']).'</textarea></p></td></tr>';

	echo '</table>';
	echo '</div>';

	echo '<div class="tab-content" style="border:none;padding:0px;margin:0;" id="tab2">';
	echo '<table style="padding:0px;margin:0px;width:100%;">';

	echo '<tr><td class="td25"><p><b>'.$tqb->lang['msg']['blog_timezone'].'</b></p></td><td><p><select id="CFG_TIME_ZONE_NAME" name="CFG_TIME_ZONE_NAME" style="width:600px;" >';
	echo CreateOptionsOfTimeZone($tqb->option['CFG_TIME_ZONE_NAME']);
	echo '</select></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['blog_language'].'</b></p></td><td><p><select id="CFG_BLOG_LANGUAGEPACK" name="CFG_BLOG_LANGUAGEPACK" style="width:600px;" >';
	echo CreateOptionsOfLang($tqb->option['CFG_BLOG_LANGUAGEPACK']);
	echo '</select></p></td></tr>';

	echo '<tr><td><p><b>'.$tqb->lang['msg']['allow_upload_type'].'</b></p></td><td><p><input id="CFG_UPLOAD_FILETYPE" name="CFG_UPLOAD_FILETYPE" style="width:600px;" type="text" value="'.$tqb->option['CFG_UPLOAD_FILETYPE'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['allow_upload_size'].'</b></p></td><td><p><input id="CFG_UPLOAD_FILESIZE" name="CFG_UPLOAD_FILESIZE" style="width:600px;" type="text" value="'.$tqb->option['CFG_UPLOAD_FILESIZE'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['debug_mode'].'</b></p></td><td><p><input id="CFG_DEBUG_MODE" name="CFG_DEBUG_MODE" type="text" value="'.$tqb->option['CFG_DEBUG_MODE'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['gzip_compress'].'</b></p></td><td><p><input id="CFG_GZIP_ENABLE" name="CFG_GZIP_ENABLE" type="text" value="'.$tqb->option['CFG_GZIP_ENABLE'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['syntax_high_lighter'].'</b></p></td><td><p><input id="CFG_SYNTAXHIGHLIGHTER_ENABLE" name="CFG_SYNTAXHIGHLIGHTER_ENABLE" type="text" value="'.$tqb->option['CFG_SYNTAXHIGHLIGHTER_ENABLE'].'" class="checkbox"/></p></td></tr>';

	echo '</table>';
	echo '</div>';
	echo '<div class="tab-content" style="border:none;padding:0px;margin:0;" id="tab3">';
	echo '<table style="padding:0px;margin:0px;width:100%;">';

	echo '<tr><td><p><b>'.$tqb->lang['msg']['display_qrcode_enable'].'</b></p></td><td><p><input id="CFG_DISPLAY_QRCODE" name="CFG_DISPLAY_QRCODE" type="text" value="'.$tqb->option['CFG_DISPLAY_QRCODE'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['display_subcategorys'].'</b></p></td><td><p><input id="CFG_DISPLAY_SUBCATEGORYS" name="CFG_DISPLAY_SUBCATEGORYS" type="text" value="'.$tqb->option['CFG_DISPLAY_SUBCATEGORYS'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['display_count'].'</b></p></td><td><p><input id="CFG_DISPLAY_COUNT" name="CFG_DISPLAY_COUNT" style="width:600px;" type="text" value="'.$tqb->option['CFG_DISPLAY_COUNT'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['pagebar_count'].'</b></p></td><td><p><input id="CFG_PAGEBAR_COUNT" name="CFG_PAGEBAR_COUNT" style="width:600px;" type="text" value="'.$tqb->option['CFG_PAGEBAR_COUNT'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['search_count'].'</b></p></td><td><p><input id="CFG_SEARCH_COUNT" name="CFG_SEARCH_COUNT" style="width:600px;" type="text" value="'.$tqb->option['CFG_SEARCH_COUNT'].'" /></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['manage_count'].'</b></p></td><td><p><input id="CFG_MANAGE_COUNT" name="CFG_MANAGE_COUNT" style="width:600px;" type="text" value="'.$tqb->option['CFG_MANAGE_COUNT'].'" /></p></td></tr>';
	echo '</table>';
	echo '</div>';
	echo '<div class="tab-content" style="border:none;padding:0px;margin:0;" id="tab4">';
	echo '<table style="padding:0px;margin:0px;width:100%;">';


	echo '<tr><td class="td25"><p><b>'.$tqb->lang['msg']['comment_turnoff'].'</b></p></td><td><p><input id="CFG_COMMENT_TURNOFF" name="CFG_COMMENT_TURNOFF" type="text" value="'.$tqb->option['CFG_COMMENT_TURNOFF'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td class="td25"><p><b>'.$tqb->lang['msg']['comment_check'].'</b></p></td><td><p><input id="CFG_COMMENT_CHECK" name="CFG_COMMENT_CHECK" type="text" value="'.$tqb->option['CFG_COMMENT_CHECK'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['comment_reverse_order'].'</b></p></td><td><p><input id="CFG_COMMENT_REVERSE_ORDER" name="CFG_COMMENT_REVERSE_ORDER" type="text" value="'.$tqb->option['CFG_COMMENT_REVERSE_ORDER'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['comment_verify_enable'].'</b></p></td><td><p><input id="CFG_COMMENT_VERIFY_ENABLE" name="CFG_COMMENT_VERIFY_ENABLE" type="text" value="'.$tqb->option['CFG_COMMENT_VERIFY_ENABLE'].'" class="checkbox"/></p></td></tr>';
	echo '<tr><td><p><b>'.$tqb->lang['msg']['comments_display_count'].'</b></p></td><td><p><input id="CFG_COMMENTS_DISPLAY_COUNT" name="CFG_COMMENTS_DISPLAY_COUNT" type="text" value="'.$tqb->option['CFG_COMMENTS_DISPLAY_COUNT'].'"  style="width:600px;" /></p></td></tr>';

	echo '</table>';
	echo '</div>';
?>
                </div>
                <!-- End .content-box-content -->

              </div>
              <hr/>
			  <p><input type="submit" class="button" value="提交" id="btnPost" onclick="" /></p>
            </div>
          </form>
<?php

	echo '<script type="text/javascript">ActiveTopMenu("topmenu2");</script>';
	echo '<script type="text/javascript">AddHeaderIcon("'. $tqb->host . 'admin/image/admin/setting_32.png' . '");</script>';
}

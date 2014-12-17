<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: search.php 33828 2008-02-22 09:25:26Z team $
 */

require './admin/function/function_base.php';

$tqb->CheckGzip();
$tqb->Load();

$action='search';

if(!$tqb->CheckRights($action)){Redirect('./');}

foreach ($GLOBALS['Filter_Plugin_Search_Begin'] as $fpname => &$fpsignal) {$fpname();}

$q=trim(strip_tags(GetVars('q','GET')));

$article = new Post;
$article->ID=0;
$article->IsLock=true;
$article->Type=CFG_POST_TYPE_PAGE;

if(isset($tqb->templates['search'])){
	$article->Template='search';
}

$w=array();
$w[]=array('=','log_Type','0');
if($q){
	$w[]=array('search','log_Content','log_Intro','log_Title',$q);
}else{
	Redirect('./');
}

if(!($tqb->CheckRights('ArticleAll')&&$tqb->CheckRights('PageAll'))){
	$w[]=array('=','log_Status',0);
}

$array=$tqb->GetArticleList(
	'',
	$w,
	array('log_PostTime'=>'DESC'),
	array($tqb->searchcount),
	null
);

$article->Title='结果：找到' . ' &quot;' . $q . '&quot;' . '相关内容 ' . count($array) . ' 个';

if(count($array)>0){
	foreach ($array as $a) {
		$article->Content .= '<p><br/>' . $a->Title . '<br/>';
		$article->Content .= '<a href="' . $a->Url . '">' . $a->Url . '</a></p>';
	}
}else{
	$article->Content .= '<p><br/>对不起，没有找到匹配结果。<br/>';
}

$tqb->header.= '<meta name="robots" content="none" />' . "\r\n";
$tqb->template->SetTags('title',$article->Title);
$tqb->template->SetTags('article',$article);
$tqb->template->SetTags('type',$article->type=0?'article':'page');
$tqb->template->SetTags('page',1);
$tqb->template->SetTags('pagebar',null);
$tqb->template->SetTags('comments',array());
$tqb->template->SetTemplate($article->Template);

foreach ($GLOBALS['Filter_Plugin_ViewPost_Template'] as $fpname => &$fpsignal) {
	$fpreturn=$fpname($tqb->template);
}

$tqb->template->Display();

RunTime();
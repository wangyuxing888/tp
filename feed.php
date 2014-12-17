<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: feed.php 33828 2008-02-22 09:25:26Z team $
 */

require './admin/function/function_base.php';

header("Content-type:text/xml; Charset=utf-8");

$tqb->CheckGzip();
$tqb->Load();

$action='feed';

if(!$tqb->CheckRights($action)){Http404();die;}

foreach ($GLOBALS['Filter_Plugin_Feed_Begin'] as $fpname => &$fpsignal) {$fpname();}

$rss2 = new Rss2($tqb->name,$tqb->host,$tqb->subname);

$articles=$tqb->GetArticleList(
	'*',
	array(array('=','log_Status',0)),
	array('log_PostTime'=>'DESC'),
	$tqb->option['CFG_XMLRSS2_COUNT'],
	null
);

foreach ($articles as $article) {
	$rss2->addItem($article->Title,$article->Url,($tqb->option['CFG_RSS_EXPORT_WHOLE']==true?$article->Content:$article->Intro),$article->PostTime);
}

echo $rss2->saveXML();

RunTime();
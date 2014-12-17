<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: function_event.php 33828 2008-02-22 09:25:26Z team $
 */

//********************************************************
function GetPost($idorname, $option = null) {
	global $tqb;

	if (!is_array($option)) {
		$option = array();
	}

	if (!isset($option['only_article']))
		$option['only_article'] = false;
	if (!isset($option['only_page']))
		$option['only_page'] = false;

	if(is_string($idorname)){
		$w[] = array('array', array(array('log_Alias', $idorname), array('log_Title', $idorname)));
		if($option['only_article']==true){
			$w[]=array('=','log_Type','0');
		}
		elseif($option['only_page']==true){
			$w[]=array('=','log_Type','1');
		}
		$articles = $tqb->GetPostList('*', $w, null, 1, null);
		if (count($articles) == 0) {
			return new Post;
		}
		return $articles[0];
	}
	if(is_integer($idorname)){
		return $tqb->GetPostByID($idorname);
	}
}

function GetList($count = 10, $cate = null, $auth = null, $date = null, $tags = null, $search = null, $option = null) {
	global $tqb;

	if (!is_array($option)) {
		$option = array();
	}

	if (!isset($option['only_ontop']))
		$option['only_ontop'] = false;
	if (!isset($option['only_not_ontop']))
		$option['only_not_ontop'] = false;
	if (!isset($option['has_subcate']))
		$option['has_subcate'] = false;
	if (!isset($option['is_related']))
		$option['is_related'] = false;

	if ($option['is_related']) {
		$at = $tqb->GetPostByID($option['is_related']);
		$tags = $at->Tags;
		if (!$tags)
			return array();
		$count = $count + 1;
	}

	if ($option['only_ontop'] == true) {
		$w[] = array('=', 'log_IsTop', 0);
	} elseif ($option['only_not_ontop'] == true) {
		$w[] = array('=', 'log_IsTop', 1);
	}

	$w = array();
	$w[] = array('=', 'log_Status', 0);

	$articles = array();

	if ($cate) {
		$category = new Category;
		$category = $tqb->GetCategoryByID($cate);

		if ($category->ID > 0) {

			if (!$option['has_subcate']) {
				$w[] = array('=', 'log_CateID', $category->ID);
			} else {
				$arysubcate = array();
				$arysubcate[] = array('log_CateID', $category->ID);
				foreach ($tqb->categorys[$category->ID]->SubCategorys as $subcate) {
					$arysubcate[] = array('log_CateID', $subcate->ID);
				}
				$w[] = array('array', $arysubcate);

			}

		}
	}

	if ($auth) {
		$author = new Member;
		$author = $tqb->GetMemberByID($auth);

		if ($author->ID > 0) {
			$w[] = array('=', 'log_AuthorID', $author->ID);
		}
	}

	if ($date) {
		$datetime = strtotime($date);
		if ($datetime) {
			$datetitle = str_replace(array('%y%', '%m%'), array(date('Y', $datetime), date('n', $datetime)), $tqb->lang['msg']['year_month']);
			$w[] = array('BETWEEN', 'log_PostTime', $datetime, strtotime('+1 month', $datetime));
		}
	}

	if ($tags) {
		$tag = new Tag;
		if (is_array($tags)) {
			$ta = array();
			foreach ($tags as $t) {
				$ta[] = array('log_Tag', '%{' . $t->ID . '}%');
			}
			$w[] = array('array_like', $ta);
			unset($ta);
		} else {
			if (is_int($tags)) {
				$tag = $tqb->GetTagByID($tags);
			} else {
				$tag = $tqb->GetTagByAliasOrName($tags);
			}
			if ($tag->ID > 0) {
				$w[] = array('LIKE', 'log_Tag', '%{' . $tag->ID . '}%');
			}
		}
	}

	if ($search) {
		$w[] = array('search', 'log_Content', 'log_Intro', 'log_Title', $search);
	}

	$articles = $tqb->GetArticleList('*', $w, array('log_PostTime' => 'DESC'), $count, null, false);

	if ($option['is_related']) {
		foreach ($articles as $k => $a) {
			if ($a->ID == $option['is_related'])
				unset($articles[$k]);
		}
		if (count($articles) == $count)
			$articles = array_pop($articles);
	}

	return $articles;

}

//********************************************************
function VerifyLogin() {
	global $tqb;

	if (isset($tqb->membersbyname[GetVars('username', 'POST')])) {
		if ($tqb->Verify_MD5(GetVars('username', 'POST'), GetVars('password', 'POST'))) {
			$un = GetVars('username', 'POST');
			$ps = md5($tqb->user->Password . $tqb->guid);
			if (GetVars('savedate') == 0) {
				setcookie("username", $un, 0, $tqb->cookiespath);
				setcookie("password", $ps, 0, $tqb->cookiespath);
			} else {
				setcookie("username", $un, time() + 3600 * 24 * GetVars('savedate', 'POST'), $tqb->cookiespath);
				setcookie("password", $ps, time() + 3600 * 24 * GetVars('savedate', 'POST'), $tqb->cookiespath);
			}

			return true;
		} else {
			$tqb->ShowError(8, __FILE__, __LINE__);
		}
	} else {
		$tqb->ShowError(8, __FILE__, __LINE__);
	}
}

function Logout() {
	global $tqb;

	setcookie('username', '', time() - 3600, $tqb->cookiespath);
	setcookie('password', '', time() - 3600, $tqb->cookiespath);

}

//********************************************************
function ViewAuto($url) {
	global $tqb;
	
	$url=GetValueInArray(explode('?',$url),'0');

	foreach ($GLOBALS['Filter_Plugin_ViewAuto_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($url);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			return $fpreturn;
		}
	}

	if ($tqb->option['CFG_STATIC_MODE'] == 'ACTIVE') {
		$tqb->ShowError(2, __FILE__, __LINE__);

		return null;
	}

	if (isset($_SERVER['SERVER_SOFTWARE'])) {
		if ((strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) && (isset($_GET['rewrite']) !== true))
			$url = iconv('GBK', 'UTF-8//TRANSLIT//IGNORE', $url);
	}
	$url = substr($url, strlen($tqb->cookiespath));
	$url = urldecode($url);

	$r = UrlRule::Rewrite_url($tqb->option['CFG_INDEX_REGEX'], 'index');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		ViewList($m[1], null, null, null, null, true);

		return null;
	}

	$r = UrlRule::Rewrite_url($tqb->option['CFG_DATE_REGEX'], 'date');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		ViewList($m[2], null, null, $m[1], null, true);

		return null;
	}

	$r = UrlRule::Rewrite_url($tqb->option['CFG_AUTHOR_REGEX'], 'auth');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		ViewList($m[2], null, $m[1], null, null, true);

		return null;
	}

	$r = UrlRule::Rewrite_url($tqb->option['CFG_TAGS_REGEX'], 'tags');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		ViewList($m[2], null, null, null, $m[1], true);

		return null;
	}

	$r = UrlRule::Rewrite_url($tqb->option['CFG_CATEGORY_REGEX'], 'cate');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		$result = ViewList($m[2], $m[1], null, null, null, true);
		if ($result <> CFG_REWRITE_GO_ON)
			return null;
	}

	$r = UrlRule::Rewrite_url($tqb->option['CFG_ARTICLE_REGEX'], 'article');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		if (strpos($tqb->option['CFG_ARTICLE_REGEX'], '{%id%}') !== false) {
			$result = ViewPost($m[1], null, true);
		} else {
			$result = ViewPost(null, $m[1], true);
		}
		if ($result == CFG_REWRITE_GO_ON)
			$tqb->ShowError(2, __FILE__, __LINE__);

		return null;
	}

	$r = UrlRule::Rewrite_url($tqb->option['CFG_PAGE_REGEX'], 'page');
	$m = array();
	if (preg_match($r, $url, $m) == 1) {
		if (strpos($tqb->option['CFG_PAGE_REGEX'], '{%id%}') !== false) {
			$result = ViewPost($m[1], null, true);
		} else {
			$result = ViewPost(null, $m[1], true);
		}
		if ($result == CFG_REWRITE_GO_ON)
			$tqb->ShowError(2, __FILE__, __LINE__);

		return null;
	}

	if($url==''){
		return ViewList(null,null,null,null,null);
	}
	
	//ViewList(null,null,null,null,null);
	foreach ($GLOBALS['Filter_Plugin_ViewAuto_End'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($url);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			return $fpreturn;
		}
	}

	$tqb->ShowError(2, __FILE__, __LINE__);

}

function ViewList($page, $cate, $auth, $date, $tags, $isrewrite = false) {
	global $tqb;
	foreach ($GLOBALS['Filter_Plugin_ViewList_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($page, $cate, $auth, $date, $tags);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			return $fpreturn;
		}
	}

	//if($cate=='index.php'|$auth=='index.php'|$date=='index.php'|$tags=='index.php'){
	//	$cate=$auth=$date=$tags=null;
	//}

	$type = 'index';
	if ($cate !== null)
		$type = 'category';
	if ($auth !== null)
		$type = 'author';
	if ($date !== null)
		$type = 'date';
	if ($tags !== null)
		$type = 'tag';

	$category = null;
	$author = null;
	$datetime = null;
	$tag = null;

	$w = array();
	$w[] = array('=', 'log_IsTop', 0);
	$w[] = array('=', 'log_Status', 0);

	$page = (int)$page == 0 ? 1 : (int)$page;

	$articles = array();
	$articles_top = array();

	if(isset($tqb->option['CFG_LISTONTOP_TURNOFF'])&&$tqb->option['CFG_LISTONTOP_TURNOFF']==false){
		if ($type == 'index' && $page == 1) {
			$articles_top = $tqb->GetArticleList('*', array(array('=', 'log_IsTop', 1), array('=', 'log_Status', 0)), array('log_PostTime' => 'DESC'), null, null);
		}
	}

	switch ($type) {
		//********************************************************
		case 'index':
			$pagebar = new Pagebar($tqb->option['CFG_INDEX_REGEX']);
			$pagebar->Count = $tqb->cache->normal_article_nums;
			$category = new Metas;
			$author = new Metas;
			$datetime = new Metas;
			$tag = new Metas;
			$template = $tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'];
			if ($page == 1) {
				$tqb->title = $tqb->subname;
			} else {
				$tqb->title = str_replace('%num%', $page, $tqb->lang['msg']['number_page']);
			}
			break;
		//********************************************************
		case 'category':
			$pagebar = new Pagebar($tqb->option['CFG_CATEGORY_REGEX']);
			$author = new Metas;
			$datetime = new Metas;
			$tag = new Metas;

			$category = new Category;
			if (strpos($tqb->option['CFG_CATEGORY_REGEX'], '{%id%}') !== false) {
				$category = $tqb->GetCategoryByID($cate);
			}
			if (strpos($tqb->option['CFG_CATEGORY_REGEX'], '{%alias%}') !== false) {
				$category = $tqb->GetCategoryByAliasOrName($cate);
			}
			if ($category->ID == 0) {
				if ($isrewrite == true)
					return CFG_REWRITE_GO_ON;
				$tqb->ShowError(2, __FILE__, __LINE__);
			}
			if ($page == 1) {
				$tqb->title = $category->Name;
			} else {
				$tqb->title = $category->Name . ' ' . str_replace('%num%', $page, $tqb->lang['msg']['number_page']);
			}
			$template = $category->Template;

			if (!$tqb->option['CFG_DISPLAY_SUBCATEGORYS']) {
				$w[] = array('=', 'log_CateID', $category->ID);
				$pagebar->Count = $category->Count;
			} else {
				$arysubcate = array();
				$arysubcate[] = array('log_CateID', $category->ID);
				foreach ($tqb->categorys[$category->ID]->SubCategorys as $subcate) {
					$arysubcate[] = array('log_CateID', $subcate->ID);
				}
				$w[] = array('array', $arysubcate);
			}

			$pagebar->UrlRule->Rules['{%id%}'] = $category->ID;
			$pagebar->UrlRule->Rules['{%alias%}'] = $category->Alias == '' ? urlencode($category->Name) : $category->Alias;
			break;
		//********************************************************
		case 'author':
			$pagebar = new Pagebar($tqb->option['CFG_AUTHOR_REGEX']);
			$category = new Metas;
			$datetime = new Metas;
			$tag = new Metas;

			$author = new Member;
			if (strpos($tqb->option['CFG_AUTHOR_REGEX'], '{%id%}') !== false) {
				$author = $tqb->GetMemberByID($auth);
			}
			if (strpos($tqb->option['CFG_AUTHOR_REGEX'], '{%alias%}') !== false) {
				$author = $tqb->GetMemberByAliasOrName($auth);
			}
			if ($author->ID == 0) {
				if ($isrewrite == true)
					return CFG_REWRITE_GO_ON;
				$tqb->ShowError(2, __FILE__, __LINE__);
			}
			if ($page == 1) {
				$tqb->title = $author->Name;
			} else {
				$tqb->title = $author->Name . ' ' . str_replace('%num%', $page, $tqb->lang['msg']['number_page']);
			}
			$template = $author->Template;
			$w[] = array('=', 'log_AuthorID', $author->ID);
			$pagebar->Count = $author->Articles;
			$pagebar->UrlRule->Rules['{%id%}'] = $author->ID;
			$pagebar->UrlRule->Rules['{%alias%}'] = $author->Alias == '' ? urlencode($author->Name) : $author->Alias;
			break;
		//********************************************************
		case 'date':
			$pagebar = new Pagebar($tqb->option['CFG_DATE_REGEX']);
			$category = new Metas;
			$author = new Metas;
			$tag = new Metas;
			$datetime = strtotime($date);

			$datetitle = str_replace(array('%y%', '%m%'), array(date('Y', $datetime), date('n', $datetime)), $tqb->lang['msg']['year_month']);
			if ($page == 1) {
				$tqb->title = $datetitle;
			} else {
				$tqb->title = $datetitle . ' ' . str_replace('%num%', $page, $tqb->lang['msg']['number_page']);
			}

			$tqb->modulesbyfilename['calendar']->Content = BuildModule_calendar(date('Y', $datetime) . '-' . date('n', $datetime));

			$template = $tqb->option['CFG_INDEX_DEFAULT_TEMPLATE'];
			$w[] = array('BETWEEN', 'log_PostTime', $datetime, strtotime('+1 month', $datetime));
			$pagebar->UrlRule->Rules['{%date%}'] = $date;
			$datetime = Metas::ConvertArray(getdate($datetime));
			break;
		//********************************************************
		case 'tag':
			$pagebar = new Pagebar($tqb->option['CFG_TAGS_REGEX']);
			$category = new Metas;
			$author = new Metas;
			$datetime = new Metas;
			$tag = new Tag;
			if (strpos($tqb->option['CFG_TAGS_REGEX'], '{%id%}') !== false) {
				$tag = $tqb->GetTagByID($tags);
			}
			if (strpos($tqb->option['CFG_TAGS_REGEX'], '{%alias%}') !== false) {
				$tag = $tqb->GetTagByAliasOrName($tags);
			}
			if ($tag->ID == 0) {
				if ($isrewrite == true)
					return CFG_REWRITE_GO_ON;
				$tqb->ShowError(2, __FILE__, __LINE__);
			}

			if ($page == 1) {
				$tqb->title = $tag->Name;
			} else {
				$tqb->title = $tag->Name . ' ' . str_replace('%num%', $page, $tqb->lang['msg']['number_page']);
			}

			$template = $tag->Template;
			$w[] = array('LIKE', 'log_Tag', '%{' . $tag->ID . '}%');
			$pagebar->UrlRule->Rules['{%id%}'] = $tag->ID;
			$pagebar->UrlRule->Rules['{%alias%}'] = $tag->Alias == '' ? urlencode($tag->Name) : $tag->Alias;
			break;
	}

	$pagebar->PageCount = $tqb->displaycount;
	$pagebar->PageNow = $page;
	$pagebar->PageBarCount = $tqb->pagebarcount;
	$pagebar->UrlRule->Rules['{%page%}'] = $page;

	$articles = $tqb->GetArticleList(
		'*', 
		$w,
		array('log_PostTime' => 'DESC'), array(($pagebar->PageNow - 1) * $pagebar->PageCount, $pagebar->PageCount),
		array('pagebar' => $pagebar),
		true
	);

	$tqb->template->SetTags('title', $tqb->title);
	$tqb->template->SetTags('articles', array_merge($articles_top, $articles));
	if ($pagebar->PageAll == 0)
		$pagebar = null;
	$tqb->template->SetTags('pagebar', $pagebar);
	$tqb->template->SetTags('type', $type);
	$tqb->template->SetTags('page', $page);

	$tqb->template->SetTags('date', $datetime);
	$tqb->template->SetTags('tag', $tag);
	$tqb->template->SetTags('author', $author);
	$tqb->template->SetTags('category', $category);

	if (isset($tqb->templates[$template])) {
		$tqb->template->SetTemplate($template);
	} else {
		$tqb->template->SetTemplate('index');
	}

	foreach ($GLOBALS['Filter_Plugin_ViewList_Template'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($tqb->template);
	}

	$tqb->template->Display();

}

function ViewPost($id, $alias, $isrewrite = false) {
	global $tqb;
	foreach ($GLOBALS['Filter_Plugin_ViewPost_Begin'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($id, $alias);
		if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
			return $fpreturn;
		}
	}

	$w = array();

	if ($id !== null) {
		$w[] = array('=', 'log_ID', $id);
	} elseif ($alias !== null) {
		$w[] = array('array', array(array('log_Alias', $alias), array('log_Title', $alias)));
	} else {
		$tqb->ShowError(2, __FILE__, __LINE__);
		die();
	}

	if (!($tqb->CheckRights('ArticleAll') && $tqb->CheckRights('PageAll'))) {
		$w[] = array('=', 'log_Status', 0);
	}

	$articles = $tqb->GetPostList('*', $w, null, 1, null);
	if (count($articles) == 0) {
		if ($isrewrite == true)
			return CFG_REWRITE_GO_ON;
		$tqb->ShowError(2, __FILE__, __LINE__);
	}

	$article = $articles[0];
	if ($tqb->option['CFG_COMMENT_TURNOFF']) {
		$article->IsLock = true;
	}

	if ($article->Type == 0) {
		$tqb->LoadTagsByIDString($article->Tag);
	}

	if (isset($tqb->option['CFG_VIEWNUMS_TURNOFF']) && $tqb->option['CFG_VIEWNUMS_TURNOFF']==false) {
		$article->ViewNums += 1;
		$sql = $tqb->db->sql->Update($tqb->table['Post'], array('log_ViewNums' => $article->ViewNums), array(array('=', 'log_ID', $article->ID)));
		$tqb->db->Update($sql);
	}

	$pagebar = new Pagebar('javascript:GetComments(\'' . $article->ID . '\',\'{%page%}\')', false);
	$pagebar->PageCount = $tqb->commentdisplaycount;
	$pagebar->PageNow = 1;
	$pagebar->PageBarCount = $tqb->pagebarcount;

	$comments = array();

	$comments = $tqb->GetCommentList(
		'*', 
		array(
			array('=', 'comm_RootID', 0),
			array('=', 'comm_IsChecking', 0),
			array('=', 'comm_LogID', $article->ID)
		),
		array('comm_ID' => ($tqb->option['CFG_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
		array(($pagebar->PageNow - 1) * $pagebar->PageCount, $pagebar->PageCount),
		array('pagebar' => $pagebar)
	);
	$rootid = array();
	foreach ($comments as &$comment) {
		$rootid[] = array('comm_RootID', $comment->ID);
	}
	$comments2 = $tqb->GetCommentList(
		'*', 
		array(
			array('array', $rootid),
			array('=', 'comm_IsChecking', 0),
			array('=', 'comm_LogID', $article->ID)
		),
		array('comm_ID' => ($tqb->option['CFG_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
		null,
		null
	);
	$floorid = ($pagebar->PageNow - 1) * $pagebar->PageCount;
	foreach ($comments as &$comment) {
		$floorid += 1;
		$comment->FloorID = $floorid;
		$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
	}
	foreach ($comments2 as &$comment) {
		$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
	}

	$tqb->template->SetTags('title', ($article->Status == 0 ? '' : '[' . $tqb->lang['post_status_name'][$article->Status] . ']') . $article->Title);
	$tqb->template->SetTags('article', $article);
	$tqb->template->SetTags('type', ($article->Type == 0 ? 'article' : 'page'));
	$tqb->template->SetTags('page', 1);
	if ($pagebar->PageAll == 0 || $pagebar->PageAll == 1)
		$pagebar = null;
	$tqb->template->SetTags('pagebar', $pagebar);
	$tqb->template->SetTags('comments', $comments);

	if (isset($tqb->templates[$article->Template])) {
		$tqb->template->SetTemplate($article->Template);
	} else {
		$tqb->template->SetTemplate('single');
	}

	foreach ($GLOBALS['Filter_Plugin_ViewPost_Template'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($tqb->template);
	}

	$tqb->template->Display();
}

function ViewComments($postid, $page) {
	global $tqb;

	$post = New Post;
	$post->LoadInfoByID($postid);
	$page = $page == 0 ? 1 : $page;
	$template = 'comments';

	$pagebar = new Pagebar('javascript:GetComments(\'' . $post->ID . '\',\'{%page%}\')');
	$pagebar->PageCount = $tqb->commentdisplaycount;
	$pagebar->PageNow = $page;
	$pagebar->PageBarCount = $tqb->pagebarcount;

	$comments = array();

	$comments = $tqb->GetCommentList(
		'*',
		array(
			array('=', 'comm_RootID', 0),
			array('=', 'comm_IsChecking', 0),
			array('=', 'comm_LogID', $post->ID)
		),
		array('comm_ID' => ($tqb->option['CFG_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
		array(($pagebar->PageNow - 1) * $pagebar->PageCount, $pagebar->PageCount),
		array('pagebar' => $pagebar)
	);
	$rootid = array();
	foreach ($comments as $comment) {
		$rootid[] = array('comm_RootID', $comment->ID);
	}
	$comments2 = $tqb->GetCommentList(
		'*',
		array(
			array('array', $rootid),
			array('=', 'comm_IsChecking', 0),
			array('=', 'comm_LogID', $post->ID)
		),
		array('comm_ID' => ($tqb->option['CFG_COMMENT_REVERSE_ORDER'] ? 'DESC' : 'ASC')),
		null,
		null
	);

	$floorid = ($pagebar->PageNow - 1) * $pagebar->PageCount;
	foreach ($comments as &$comment) {
		$floorid += 1;
		$comment->FloorID = $floorid;
		$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
	}
	foreach ($comments2 as &$comment) {
		$comment->Content = TransferHTML($comment->Content, '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';
	}

	$tqb->template->SetTags('title', $tqb->title);
	$tqb->template->SetTags('article', $post);
	$tqb->template->SetTags('type', 'comment');
	$tqb->template->SetTags('page', $page);
	if ($pagebar->PageAll == 1)
		$pagebar = null;
	$tqb->template->SetTags('pagebar', $pagebar);
	$tqb->template->SetTags('comments', $comments);

	$tqb->template->SetTemplate($template);

	foreach ($GLOBALS['Filter_Plugin_ViewComments_Template'] as $fpname => &$fpsignal) {
		$fpreturn = $fpname($tqb->template);
	}

	$s = $tqb->template->Output();

	$a = explode('<label id="AjaxCommentBegin"></label>', $s);
	$s = $a[1];
	$a = explode('<label id="AjaxCommentEnd"></label>', $s);
	$s = $a[0];

	echo $s;

}

function ViewComment($id) {
	global $tqb;

	$template = 'comment';
	$comment = $tqb->GetCommentByID($id);
	$post = new Post;
	$post->LoadInfoByID($comment->LogID);

	$comment->Content = TransferHTML(htmlspecialchars($comment->Content), '[enter]') . '<label id="AjaxComment' . $comment->ID . '"></label>';

	$tqb->template->SetTags('title', $tqb->title);
	$tqb->template->SetTags('comment', $comment);
	$tqb->template->SetTags('article', $post);
	$tqb->template->SetTags('type', 'comment');
	$tqb->template->SetTags('page', 1);
	$tqb->template->SetTemplate($template);

	$tqb->template->Display();

}

//********************************************************
function PostArticle() {
	global $tqb;
	if (!isset($_POST['ID'])) return;

	if (isset($_COOKIE['timezone'])) {
		$tz = GetVars('timezone', 'COOKIE');
		if (is_numeric($tz)) {
			date_default_timezone_set('Etc/GMT' . sprintf('%+d', -$tz));
		}
		unset($tz);
	}

	if (isset($_POST['Tag'])) {
		$_POST['Tag'] = TransferHTML($_POST['Tag'], '[noscript]');
		$_POST['Tag'] = PostArticle_CheckTagAndConvertIDtoString($_POST['Tag']);
	}
	if (isset($_POST['Content'])) {
		$_POST['Content'] = str_replace('<hr class="more" />', '<!--more-->', $_POST['Content']);
		$_POST['Content'] = str_replace('<hr class="more"/>', '<!--more-->', $_POST['Content']);
		if (strpos($_POST['Content'], '<!--more-->') !== false) {
			if (isset($_POST['Intro'])) {
				$_POST['Intro'] = GetValueInArray(explode('<!--more-->', $_POST['Content']), 0);
			}
		} else {
			if (isset($_POST['Intro'])) {
				if ($_POST['Intro'] == '') {
					$_POST['Intro'] = SubStrUTF8($_POST['Content'], $tqb->option['CFG_ARTICLE_EXCERPT_MAX']);
					if (strpos($_POST['Intro'], '<') !== false) {
						$_POST['Intro'] = CloseTags($_POST['Intro']);
					}
				}
			}
		}
	}

	if (!isset($_POST['AuthorID'])) {
		$_POST['AuthorID'] = $tqb->user->ID;
	} else {
		if (($_POST['AuthorID'] != $tqb->user->ID) && (!$tqb->CheckRights('ArticleAll'))) {
			$_POST['AuthorID'] = $tqb->user->ID;
		}
		if ($_POST['AuthorID'] == 0)
			$_POST['AuthorID'] = $tqb->user->ID;
	}

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	if (isset($_POST['PostTime'])) {
		$_POST['PostTime'] = strtotime($_POST['PostTime']);
	}

	if (!$tqb->CheckRights('ArticleAll')) {
		unset($_POST['IsTop']);
	}

	$article = new Post();
	$pre_author = null;
	$pre_tag = null;
	$pre_category = null;
	if (GetVars('ID', 'POST') == 0) {
		if (!$tqb->CheckRights('ArticlePub')) {
			$_POST['Status'] = CFG_POST_STATUS_AUDITING;
		}
	} else {
		$article->LoadInfoByID(GetVars('ID', 'POST'));
		if (($article->AuthorID != $tqb->user->ID) && (!$tqb->CheckRights('ArticleAll'))) {
			$tqb->ShowError(6, __FILE__, __LINE__);
		}
		if ((!$tqb->CheckRights('ArticlePub')) && ($article->Status == CFG_POST_STATUS_AUDITING)) {
			$_POST['Status'] = CFG_POST_STATUS_AUDITING;
		}
		$pre_author = $article->AuthorID;
		$pre_tag = $article->Tag;
		$pre_category = $article->CateID;
	}

	foreach ($tqb->datainfo['Post'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$article->$key = GetVars($key, 'POST');
		}
	}

	$article->Type = CFG_POST_TYPE_ARTICLE;

	foreach ($GLOBALS['Filter_Plugin_PostArticle_Core'] as $fpname => &$fpsignal) {
		$fpname($article);
	}

	FilterPost($article);
	FilterMeta($article);

	$article->Save();

	CountTagArrayString($pre_tag . $article->Tag);
	CountMemberArray(array($pre_author, $article->AuthorID));
	CountCategoryArray(array($pre_category, $article->CateID));
	CountPostArray(array($article->ID));
	CountNormalArticleNums();

	$tqb->AddBuildModule('previous');
	$tqb->AddBuildModule('calendar');
	$tqb->AddBuildModule('comments');
	$tqb->AddBuildModule('archives');
	$tqb->AddBuildModule('tags');
	$tqb->AddBuildModule('authors');

	foreach ($GLOBALS['Filter_Plugin_PostArticle_Succeed'] as $fpname => &$fpsignal)
		$fpname($article);

	return true;
}

function DelArticle() {
	global $tqb;

	$id = (int)GetVars('id', 'GET');

	$article = new Post();
	$article->LoadInfoByID($id);
	if ($article->ID > 0) {

		if (!$tqb->CheckRights('ArticleAll') && $article->AuthorID != $tqb->user->ID)
			$tqb->ShowError(6, __FILE__, __LINE__);

		$pre_author = $article->AuthorID;
		$pre_tag = $article->Tag;
		$pre_category = $article->CateID;

		$article->Del();

		DelArticle_Comments($article->ID);

		CountTagArrayString($pre_tag);
		CountMemberArray(array($pre_author));
		CountCategoryArray(array($pre_category));
		CountNormalArticleNums();

		$tqb->AddBuildModule('previous');
		$tqb->AddBuildModule('calendar');
		$tqb->AddBuildModule('comments');
		$tqb->AddBuildModule('archives');
		$tqb->AddBuildModule('tags');
		$tqb->AddBuildModule('authors');

		foreach ($GLOBALS['Filter_Plugin_DelArticle_Succeed'] as $fpname => &$fpsignal)
			$fpname($article);
	} else {

	}

	return true;
}

function PostArticle_CheckTagAndConvertIDtoString($tagnamestring) {
	global $tqb;
	$s = '';
	$tagnamestring = str_replace(';', ',', $tagnamestring);
	$tagnamestring = str_replace('，', ',', $tagnamestring);
	$tagnamestring = str_replace('、', ',', $tagnamestring);
	$tagnamestring = strip_tags($tagnamestring);
	$tagnamestring = trim($tagnamestring);
	if ($tagnamestring == '')
		return '';
	if ($tagnamestring == ',')
		return '';
	$a = explode(',', $tagnamestring);
	$b = array();
	foreach ($a as &$value) {
		$value = trim($value);
		if ($value)	$b[] = $value;
	}
	$b = array_unique($b);
	$b = array_slice($b, 0, 20);
	$c = array();

	$t = $tqb->LoadTagsByNameString($tagnamestring);
	foreach ($t as $key => $value) {
		$c[] = $key;
	}
	$d = array_diff($b, $c);
	if ($tqb->CheckRights('TagNew')) {
		foreach ($d as $key) {
			$tag = new Tag;
			$tag->Name = $key;
			FilterTag($tag);
			$tag->Save();
			$tqb->tags[$tag->ID] = $tag;
			$tqb->tagsbyname[$tag->Name] =& $tqb->tags[$tag->ID];
		}
	}

	foreach ($b as $key) {
		if (!isset($tqb->tagsbyname[$key])) continue;
		$s .= '{' . $tqb->tagsbyname[$key]->ID . '}';
	}

	return $s;
}

function DelArticle_Comments($id) {
	global $tqb;

	$sql = $tqb->db->sql->Delete($tqb->table['Comment'], array(array('=', 'comm_LogID', $id)));
	$tqb->db->Delete($sql);
}

//********************************************************
function PostPage() {
	global $tqb;
	if (!isset($_POST['ID'])) return;

	if (isset($_POST['PostTime'])) {
		$_POST['PostTime'] = strtotime($_POST['PostTime']);
	}

	if (!isset($_POST['AuthorID'])) {
		$_POST['AuthorID'] = $tqb->user->ID;
	} else {
		if (($_POST['AuthorID'] != $tqb->user->ID) && (!$tqb->CheckRights('PageAll'))) {
			$_POST['AuthorID'] = $tqb->user->ID;
		}
	}

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$article = new Post();
	$pre_author = null;
	if (GetVars('ID', 'POST') == 0) {
	} else {
		$article->LoadInfoByID(GetVars('ID', 'POST'));
		if (($article->AuthorID != $tqb->user->ID) && (!$tqb->CheckRights('PageAll'))) {
			$tqb->ShowError(6, __FILE__, __LINE__);
		}
		$pre_author = $article->AuthorID;
	}

	foreach ($tqb->datainfo['Post'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$article->$key = GetVars($key, 'POST');
		}
	}

	$article->Type = CFG_POST_TYPE_PAGE;

	foreach ($GLOBALS['Filter_Plugin_PostPage_Core'] as $fpname => &$fpsignal) {
		$fpname($article);
	}

	FilterPost($article);
	FilterMeta($article);

	$article->Save();

	CountMemberArray(array($pre_author, $article->AuthorID));
	CountPostArray(array($article->ID));

	$tqb->AddBuildModule('comments');

	if (GetVars('AddNavbar', 'POST') == 0)
		$tqb->DelItemToNavbar('page', $article->ID);
	if (GetVars('AddNavbar', 'POST') == 1)
		$tqb->AddItemToNavbar('page', $article->ID, $article->Title, $article->Url);

	foreach ($GLOBALS['Filter_Plugin_PostPage_Succeed'] as $fpname => &$fpsignal)
		$fpname($article);

	return true;
}

function DelPage() {
	global $tqb;

	$id = (int)GetVars('id', 'GET');

	$article = new Post();
	$article->LoadInfoByID($id);
	if ($article->ID > 0) {

		if (!$tqb->CheckRights('PageAll') && $article->AuthorID != $tqb->user->ID)
			$tqb->ShowError(6, __FILE__, __LINE__);

		$pre_author = $article->AuthorID;

		$article->Del();

		DelArticle_Comments($article->ID);

		CountMemberArray(array($pre_author));

		$tqb->AddBuildModule('comments');

		$tqb->DelItemToNavbar('page', $article->ID);

		foreach ($GLOBALS['Filter_Plugin_DelPage_Succeed'] as $fpname => &$fpsignal)
			$fpname($article);
	} else {

	}

	return true;
}

//********************************************************
function PostComment() {
	global $tqb;

	$_POST['LogID'] = $_GET['postid'];

	if ($tqb->VerifyCmtKey($_GET['postid'], $_GET['key']) == false)
		$tqb->ShowError(43, __FILE__, __LINE__);

	if ($tqb->option['CFG_COMMENT_VERIFY_ENABLE']) {
		if ($tqb->user->ID == 0) {
			if ($tqb->CheckCaptcha($_POST['verify'], 'cmt') == false)
				$tqb->ShowError(38, __FILE__, __LINE__);
		}
	}

	$replyid = (integer)GetVars('replyid', 'POST');

	if ($replyid == 0) {
		$_POST['RootID'] = 0;
		$_POST['ParentID'] = 0;
	} else {
		$_POST['ParentID'] = $replyid;
		$c = $tqb->GetCommentByID($replyid);
		if ($c->Level == 3) {
			$tqb->ShowError(52, __FILE__, __LINE__);
		}
		$_POST['RootID'] = Comment::GetRootID($c->ID);
	}

	$_POST['AuthorID'] = $tqb->user->ID;
	$_POST['Name'] = $_POST['name'];
	$_POST['QQ'] = $_POST['qq'];
	$_POST['Email'] = $_POST['email'];
	$_POST['HomePage'] = $_POST['homepage'];
	$_POST['Content'] = $_POST['content'];
	$_POST['PostTime'] = Time();
	$_POST['IP'] = GetGuestIP();
	$_POST['Agent'] = GetGuestAgent();

	$cmt = new Comment();

	foreach ($tqb->datainfo['Comment'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if ($key == 'IsChecking') $cmt->$key = ($tqb->option['CFG_COMMENT_CHECK'])?1:0;
		if (isset($_POST[$key])) {
			$cmt->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostComment_Core'] as $fpname => &$fpsignal) {
		$fpname($cmt);
	}

	FilterComment($cmt);

	if ($cmt->IsThrow == false) {

		$cmt->Save();

		if ($cmt->IsChecking == false) {

			CountPostArray(array($cmt->LogID));

			$tqb->AddBuildModule('comments');

			$tqb->comments[$cmt->ID] = $cmt;

			if (GetVars('isajax', 'POST')) {
				ViewComment($cmt->ID);
			}

			foreach ($GLOBALS['Filter_Plugin_PostComment_Succeed'] as $fpname => &$fpsignal)
				$fpname($cmt);

			return true;

		} else {

			$tqb->ShowError(53, __FILE__, __LINE__);

		}

	} else {

		$tqb->ShowError(14, __FILE__, __LINE__);

	}
}

function DelComment() {
	global $tqb;

	$id = (int)GetVars('id', 'GET');
	$cmt = $tqb->GetCommentByID($id);
	if ($cmt->ID > 0) {

		$comments = $tqb->GetCommentList('*', array(array('=', 'comm_LogID', $cmt->LogID)), null, null, null);

		DelComment_Children($cmt->ID);

		$cmt->Del();

		$tqb->AddBuildModule('comments');

		foreach ($GLOBALS['Filter_Plugin_DelComment_Succeed'] as $fpname => &$fpsignal)
			$fpname($cmt);
	}

	return true;
}

function DelComment_Children($id) {
	global $tqb;

	$cmt = $tqb->GetCommentByID($id);

	foreach ($cmt->Comments as $comment) {
		if (Count($comment->Comments) > 0) {
			DelComment_Children($comment->ID);
		}
		$comment->Del();
	}

}

function DelComment_Children_NoDel($id, &$array) {
	global $tqb;

	$cmt = $tqb->GetCommentByID($id);

	foreach ($cmt->Comments as $comment) {
		$array[] = $comment->ID;
		if (Count($comment->Comments) > 0) {
			DelComment_Children_NoDel($comment->ID, $array);
		}
	}

}

function CheckComment() {
	global $tqb;

	$id = (int)GetVars('id', 'GET');
	$ischecking = (bool)GetVars('ischecking', 'GET');

	$cmt = $tqb->GetCommentByID($id);
	$cmt->IsChecking = $ischecking;

	$cmt->Save();

	CountPostArray(array($cmt->LogID));
	$tqb->AddBuildModule('comments');
}

function BatchComment() {
	global $tqb;
	if (isset($_POST['all_del'])) {
		$type = 'all_del';
	}
	if (isset($_POST['all_pass'])) {
		$type = 'all_pass';
	}
	if (isset($_POST['all_audit'])) {
		$type = 'all_audit';
	}
	$array = array();
	$array = $_POST['id'];
	if ($type == 'all_del') {
		$arrpost = array();
		foreach ($array as $i => $id) {
			$cmt = $tqb->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$arrpost[] = $cmt->LogID;
		}
		$arrpost = array_unique($arrpost);
		foreach ($arrpost as $i => $id)
			$comments = $tqb->GetCommentList('*', array(array('=', 'comm_LogID', $id)), null, null, null);

		$arrdel = array();
		foreach ($array as $i => $id) {
			$cmt = $tqb->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$arrdel[] = $cmt->ID;
			DelComment_Children_NoDel($cmt->ID, $arrdel);
		}
		foreach ($arrdel as $i => $id) {
			$cmt = $tqb->GetCommentByID($id);
			$cmt->Del();
		}
	}
	if ($type == 'all_pass')
		foreach ($array as $i => $id) {
			$cmt = $tqb->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$cmt->IsChecking = false;
			$cmt->Save();
		}
	if ($type == 'all_audit')
		foreach ($array as $i => $id) {
			$cmt = $tqb->GetCommentByID($id);
			if ($cmt->ID == 0)
				continue;
			$cmt->IsChecking = true;
			$cmt->Save();
		}
}

//********************************************************
function PostCategory() {
	global $tqb;
	if (!isset($_POST['ID'])) return;

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$parentid = (int)GetVars('ParentID', 'POST');
	if ($parentid > 0) {
		if ($tqb->categorys[$parentid]->Level > 2) {
			$_POST['ParentID'] = '0';
		}
	}

	$cate = new Category();
	if (GetVars('ID', 'POST') == 0) {
	} else {
		$cate->LoadInfoByID(GetVars('ID', 'POST'));
	}

	foreach ($tqb->datainfo['Category'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$cate->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostCategory_Core'] as $fpname => &$fpsignal) {
		$fpname($cate);
	}

	FilterCategory($cate);
	FilterMeta($cate);

	CountCategory($cate);

	$cate->Save();

	$tqb->LoadCategorys();
	$tqb->AddBuildModule('catalog');

	if (GetVars('AddNavbar', 'POST') == 0)
		$tqb->DelItemToNavbar('category', $cate->ID);
	if (GetVars('AddNavbar', 'POST') == 1)
		$tqb->AddItemToNavbar('category', $cate->ID, $cate->Name, $cate->Url);

	foreach ($GLOBALS['Filter_Plugin_PostCategory_Succeed'] as $fpname => &$fpsignal)
		$fpname($cate);

	return true;
}

function DelCategory() {
	global $tqb;

	$id = (int)GetVars('id', 'GET');
	$cate = $tqb->GetCategoryByID($id);
	if ($cate->ID > 0) {
		DelCategory_Articles($cate->ID);
		$cate->Del();

		$tqb->LoadCategorys();
		$tqb->AddBuildModule('catalog');
		$tqb->DelItemToNavbar('category', $cate->ID);

		foreach ($GLOBALS['Filter_Plugin_DelCategory_Succeed'] as $fpname => &$fpsignal)
			$fpname($cate);
	}

	return true;
}

function DelCategory_Articles($id) {
	global $tqb;

	$sql = $tqb->db->sql->Update($tqb->table['Post'], array('log_CateID' => 0), array(array('=', 'log_CateID', $id)));
	$tqb->db->Update($sql);
}

//********************************************************
function PostTag() {
	global $tqb;
	if (!isset($_POST['ID'])) return;

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$tag = new Tag();
	if (GetVars('ID', 'POST') == 0) {
	} else {
		$tag->LoadInfoByID(GetVars('ID', 'POST'));
	}

	foreach ($tqb->datainfo['Tag'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$tag->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostTag_Core'] as $fpname => &$fpsignal) {
		$fpname($tag);
	}

	FilterTag($tag);
	FilterMeta($tag);

	CountTag($tag);

	$tag->Save();

	if (GetVars('AddNavbar', 'POST') == 0)
		$tqb->DelItemToNavbar('tag', $tag->ID);
	if (GetVars('AddNavbar', 'POST') == 1)
		$tqb->AddItemToNavbar('tag', $tag->ID, $tag->Name, $tag->Url);

	$tqb->AddBuildModule('tags');

	foreach ($GLOBALS['Filter_Plugin_PostTag_Succeed'] as $fpname => &$fpsignal)
		$fpname($tag);

	return true;
}

function DelTag() {
	global $tqb;

	$tagid = (int)GetVars('id', 'GET');
	$tag = $tqb->GetTagByID($tagid);
	if ($tag->ID > 0) {
		$tag->Del();
		$tqb->DelItemToNavbar('tag', $tag->ID);
		$tqb->AddBuildModule('tags');
		foreach ($GLOBALS['Filter_Plugin_DelTag_Succeed'] as $fpname => &$fpsignal)
			$fpname($tag);
	}

	return true;
}

//********************************************************
function PostMember() {
	global $tqb;
	if (!isset($_POST['ID'])) return;

	if (!$tqb->CheckRights('MemberAll')) {
		unset($_POST['Level']);
		unset($_POST['Name']);
		unset($_POST['Status']);
	}
	if (isset($_POST['Password'])) {
		if ($_POST['Password'] == '') {
			unset($_POST['Password']);
		} else {
			if (strlen($_POST['Password']) < $tqb->option['CFG_PASSWORD_MIN'] || strlen($_POST['Password']) > $tqb->option['CFG_PASSWORD_MAX']) {
				$tqb->ShowError(54, __FILE__, __LINE__);
			}
			if (!CheckRegExp($_POST['Password'], '[password]')) {
				$tqb->ShowError(54, __FILE__, __LINE__);
			}
			$_POST['Password'] = Member::GetPassWordByGuid($_POST['Password'], $_POST['Guid']);
		}
	}

	if (isset($_POST['Name'])) {
		if (isset($tqb->membersbyname[$_POST['Name']])) {
			if ($tqb->membersbyname[$_POST['Name']]->ID <> $_POST['ID']) {
				$tqb->ShowError(62, __FILE__, __LINE__);
			}
		}
	}

	if (isset($_POST['Alias'])) {
		$_POST['Alias'] = TransferHTML($_POST['Alias'], '[noscript]');
	}

	$mem = new Member();
	if (GetVars('ID', 'POST') == 0) {
		if (isset($_POST['Password']) == false || $_POST['Password'] == '') {
			$tqb->ShowError(73, __FILE__, __LINE__);
		}
		$_POST['IP'] = GetGuestIP();
	} else {
		$mem->LoadInfoByID(GetVars('ID', 'POST'));
	}

	if ($tqb->CheckRights('MemberAll')) {
		if ($mem->ID == $tqb->user->ID) {
			unset($_POST['Level']);
			unset($_POST['Status']);
		}
	}

	foreach ($tqb->datainfo['Member'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$mem->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostMember_Core'] as $fpname => &$fpsignal) {
		$fpname($mem);
	}

	FilterMember($mem);
	FilterMeta($mem);

	CountMember($mem);

	$mem->Save();

	foreach ($GLOBALS['Filter_Plugin_PostMember_Succeed'] as $fpname => &$fpsignal)
		$fpname($mem);

	if (isset($_POST['Password'])) {
		if ($mem->ID == $tqb->user->ID) {
			Redirect($tqb->host . 'admin/admin.php?act=login');
		}
	}

	return true;
}

function DelMember() {
	global $tqb;

	$id = (int)GetVars('id', 'GET');
	$mem = $tqb->GetMemberByID($id);
	if ($mem->ID > 0 && $mem->ID <> $tqb->user->ID) {
		DelMember_AllData($id);
		$mem->Del();
		foreach ($GLOBALS['Filter_Plugin_DelMember_Succeed'] as $fpname => &$fpsignal)
			$fpname($mem);
	} else {
		return false;
	}

	return true;
}

function DelMember_AllData($id) {
	global $tqb;

	$w = array();
	$w[] = array('=', 'log_AuthorID', $id);

	$articles = $tqb->GetPostList('*', $w);
	foreach ($articles as $a) {
		$a->Del();
	}

	$w = array();
	$w[] = array('=', 'comm_AuthorID', $id);
	$comments = $tqb->GetCommentList('*', $w);
	foreach ($comments as $c) {
		$c->AuthorID = 0;
		$c->Save();
	}

	$w = array();
	$w[] = array('=', 'ul_AuthorID', $id);
	$uploads = $tqb->GetUploadList('*', $w);
	foreach ($uploads as $u) {
		$u->Del();
		$u->DelFile();
	}

}

//********************************************************
function PostModule() {
	global $tqb;

	if (isset($_POST['catalog_style'])) {
		$tqb->option['CFG_MODULE_CATALOG_STYLE'] = $_POST['catalog_style'];
		$tqb->SaveOption();
	}

	if (!isset($_POST['ID'])) return;
	if (!GetVars('FileName', 'POST')) {
		$_POST['FileName'] = 'mod' . rand(1000, 2000);
	} else {
		$_POST['FileName'] = strtolower($_POST['FileName']);
	}
	if (!GetVars('HtmlID', 'POST')) {
		$_POST['HtmlID'] = $_POST['FileName'];
	}
	if (isset($_POST['MaxLi'])) {
		$_POST['MaxLi'] = (integer)$_POST['MaxLi'];
	}
	if (isset($_POST['IsHideTitle'])) {
		$_POST['IsHideTitle'] = (integer)$_POST['IsHideTitle'];
	}
	if (!isset($_POST['Type'])) {
		$_POST['Type'] = 'div';
	}
	if (isset($_POST['Content'])) {
		if ($_POST['Type'] != 'div') {
			$_POST['Content'] = str_replace(array("\r", "\n"), array('', ''), $_POST['Content']);
		}
	}
	if (isset($_POST['Source'])) {
		if ($_POST['Source'] == 'theme') {
			$c = GetVars('Content', 'POST');
			$f = $tqb->contentdir . 'theme/' . $tqb->theme . '/include/' . GetVars('FileName', 'POST') . '.php';
			@file_put_contents($f, $c);

			return true;
		}
	}

	$mod = $tqb->GetModuleByID(GetVars('ID', 'POST'));

	foreach ($tqb->datainfo['Module'] as $key => $value) {
		if ($key == 'ID' || $key == 'Meta')	{continue;}
		if (isset($_POST[$key])) {
			$mod->$key = GetVars($key, 'POST');
		}
	}

	foreach ($GLOBALS['Filter_Plugin_PostModule_Core'] as $fpname => &$fpsignal) {
		$fpname($mod);
	}

	FilterModule($mod);

	$mod->Save();

	$tqb->AddBuildModule($mod->FileName);

	foreach ($GLOBALS['Filter_Plugin_PostModule_Succeed'] as $fpname => &$fpsignal)
		$fpname($mod);

	return true;
}

function DelModule() {
	global $tqb;

	if (GetVars('source', 'GET') == 'theme') {
		if (GetVars('filename', 'GET')) {
			$f = $tqb->contentdir . 'theme/' . $tqb->theme . '/include/' . GetVars('filename', 'GET') . '.php';
			if (file_exists($f))
				@unlink($f);

			return true;
		}

		return false;
	}

	$id = (int)GetVars('id', 'GET');
	$mod = $tqb->GetModuleByID($id);
	if ($mod->Source <> 'system') {
		$mod->Del();
		foreach ($GLOBALS['Filter_Plugin_DelModule_Succeed'] as $fpname => &$fpsignal)
			$fpname($mod);
	} else {
		return false;
	}

	return true;
}

//********************************************************
function PostUpload() {
	global $tqb;

	foreach ($_FILES as $key => $value) {
		if ($_FILES[$key]['error'] == 0) {
			if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$tmp_name = $_FILES[$key]['tmp_name'];
				$name = $_FILES[$key]['name'];

				$upload = new Upload;
				$upload->Name = $_FILES[$key]['name'];
				$upload->SourceName = $_FILES[$key]['name'];
				$upload->MimeType = $_FILES[$key]['type'];
				$upload->Size = $_FILES[$key]['size'];
				$upload->AuthorID = $tqb->user->ID;

				if (!$upload->CheckExtName())
					$tqb->ShowError(26, __FILE__, __LINE__);
				if (!$upload->CheckSize())
					$tqb->ShowError(27, __FILE__, __LINE__);

				$upload->SaveFile($_FILES[$key]['tmp_name']);
				$upload->Save();
			}
		}
	}
	if (isset($upload))
		CountMemberArray(array($upload->AuthorID));

}

function DelUpload() {
	global $tqb;

	$id = (int)GetVars('id', 'GET');
	$u = $tqb->GetUploadByID($id);
	if ($tqb->CheckRights('UploadAll') || (!$tqb->CheckRights('UploadAll') && $u->AuthorID == $tqb->user->ID)) {
		$u->Del();
		CountMemberArray(array($u->AuthorID));
		$u->DelFile();
	} else {
		return false;
	}

	return true;
}

//********************************************************
function EnablePlugin($name) {
	global $tqb;
	$tqb->option['CFG_USING_PLUGIN_LIST'] = AddNameInString($tqb->option['CFG_USING_PLUGIN_LIST'], $name);
	$tqb->SaveOption();

	return $name;
}

function DisablePlugin($name) {
	global $tqb;
	$tqb->option['CFG_USING_PLUGIN_LIST'] = DelNameInString($tqb->option['CFG_USING_PLUGIN_LIST'], $name);
	$tqb->SaveOption();
}

function SetTheme($theme, $style) {
	global $tqb;
	$oldtheme = $tqb->option['CFG_BLOG_THEME'];

	if ($oldtheme != $theme) {
		$app = $tqb->LoadApp('theme', $theme);
		if ($app->sidebars_sidebar1 | $app->sidebars_sidebar2 | $app->sidebars_sidebar3 | $app->sidebars_sidebar4 | $app->sidebars_sidebar5) {
			$s1 = $tqb->option['CFG_SIDEBAR_ORDER'];
			$s2 = $tqb->option['CFG_SIDEBAR2_ORDER'];
			$s3 = $tqb->option['CFG_SIDEBAR3_ORDER'];
			$s4 = $tqb->option['CFG_SIDEBAR4_ORDER'];
			$s5 = $tqb->option['CFG_SIDEBAR5_ORDER'];
			$tqb->option['CFG_SIDEBAR_ORDER'] = $app->sidebars_sidebar1;
			$tqb->option['CFG_SIDEBAR2_ORDER'] = $app->sidebars_sidebar2;
			$tqb->option['CFG_SIDEBAR3_ORDER'] = $app->sidebars_sidebar3;
			$tqb->option['CFG_SIDEBAR4_ORDER'] = $app->sidebars_sidebar4;
			$tqb->option['CFG_SIDEBAR5_ORDER'] = $app->sidebars_sidebar5;
			$tqb->cache->CFG_SIDEBAR_ORDER1 = $s1;
			$tqb->cache->CFG_SIDEBAR_ORDER2 = $s2;
			$tqb->cache->CFG_SIDEBAR_ORDER3 = $s3;
			$tqb->cache->CFG_SIDEBAR_ORDER4 = $s4;
			$tqb->cache->CFG_SIDEBAR_ORDER5 = $s5;
		} else {
			if ($tqb->cache->CFG_SIDEBAR_ORDER1 | $tqb->cache->CFG_SIDEBAR_ORDER2 | $tqb->cache->CFG_SIDEBAR_ORDER3 | $tqb->cache->CFG_SIDEBAR_ORDER4 | $tqb->cache->CFG_SIDEBAR_ORDER5) {
				$tqb->option['CFG_SIDEBAR_ORDER'] = $tqb->cache->CFG_SIDEBAR_ORDER1;
				$tqb->option['CFG_SIDEBAR2_ORDER'] = $tqb->cache->CFG_SIDEBAR_ORDER2;
				$tqb->option['CFG_SIDEBAR3_ORDER'] = $tqb->cache->CFG_SIDEBAR_ORDER3;
				$tqb->option['CFG_SIDEBAR4_ORDER'] = $tqb->cache->CFG_SIDEBAR_ORDER4;
				$tqb->option['CFG_SIDEBAR5_ORDER'] = $tqb->cache->CFG_SIDEBAR_ORDER5;
				$tqb->cache->CFG_SIDEBAR_ORDER1 = '';
				$tqb->cache->CFG_SIDEBAR_ORDER2 = '';
				$tqb->cache->CFG_SIDEBAR_ORDER3 = '';
				$tqb->cache->CFG_SIDEBAR_ORDER4 = '';
				$tqb->cache->CFG_SIDEBAR_ORDER5 = '';
				$tqb->SaveCache();
			}
		}

	}

	$tqb->option['CFG_BLOG_THEME'] = $theme;
	$tqb->option['CFG_BLOG_CSS'] = $style;

	$tqb->BuildTemplate();

	$tqb->SaveOption();

	if ($oldtheme != $theme) {
		UninstallPlugin($oldtheme);

		return $theme;
	}
}

function SetSidebar() {
	global $tqb;

	$tqb->option['CFG_SIDEBAR_ORDER'] = trim(GetVars('sidebar', 'POST'), '|');
	$tqb->option['CFG_SIDEBAR2_ORDER'] = trim(GetVars('sidebar2', 'POST'), '|');
	$tqb->option['CFG_SIDEBAR3_ORDER'] = trim(GetVars('sidebar3', 'POST'), '|');
	$tqb->option['CFG_SIDEBAR4_ORDER'] = trim(GetVars('sidebar4', 'POST'), '|');
	$tqb->option['CFG_SIDEBAR5_ORDER'] = trim(GetVars('sidebar5', 'POST'), '|');
	$tqb->SaveOption();
}

function SaveSetting() {
	global $tqb;

	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 3) !== 'CFG') continue;
		if ($key == 'CFG_PERMANENT_DOMAIN_ENABLE' || 
			$key == 'CFG_DEBUG_MODE' || 
			$key == 'CFG_COMMENT_TURNOFF' || 
			$key == 'CFG_COMMENT_CHECK' ||
			$key == 'CFG_COMMENT_REVERSE_ORDER_EXPORT' || 
			$key == 'CFG_DISPLAY_SUBCATEGORYS' || 
			$key == 'CFG_DISPLAY_QRCODE' ||
			$key == 'CFG_GZIP_ENABLE' ||
			$key == 'CFG_SYNTAXHIGHLIGHTER_ENABLE'		
		) {
			$tqb->option[$key] = (boolean)$value;
			continue;
		}
		if ($key == 'CFG_XMLRSS2_COUNT' || 
			$key == 'CFG_UPLOAD_FILESIZE' || 
			$key == 'CFG_DISPLAY_COUNT' || 
			$key == 'CFG_SEARCH_COUNT' || 
			$key == 'CFG_PAGEBAR_COUNT' || 
			$key == 'CFG_COMMENTS_DISPLAY_COUNT' || 
			$key == 'CFG_MANAGE_COUNT'
		) {
			$tqb->option[$key] = (integer)$value;
			continue;
		}
		if ($key == 'CFG_UPLOAD_FILETYPE'){
			$value = strtolower($value);
			$value = DelNameInString($value, 'php');
			$value = DelNameInString($value, 'asp');
		}
		$tqb->option[$key] = trim(str_replace(array("\r", "\n"), array("", ""), $value));
	}

	$tqb->option['CFG_BLOG_HOST'] = trim($tqb->option['CFG_BLOG_HOST']);
	$tqb->option['CFG_BLOG_HOST'] = trim($tqb->option['CFG_BLOG_HOST'], '/') . '/';
	$lang = require($tqb->contentdir . 'language/' . $tqb->option['CFG_BLOG_LANGUAGEPACK'] . '.php');
	$tqb->option['CFG_BLOG_LANGUAGE'] = $lang['lang'];
	$tqb->SaveOption();
}

//********************************************************
function FilterMeta(&$object) {

	//$type=strtolower(get_class($object));

	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 5) == 'meta_') {
			$name = substr($key, 5 - strlen($key));
			$object->Metas->$name = $value;
		}
	}

	foreach ($object->Metas->Data as $key => $value) {
		if ($value == "")
			unset($object->Metas->Data[$key]);
	}

}

function FilterComment(&$comment) {
	global $tqb;

	if (!CheckRegExp($comment->Name, '[username]')) {
		$tqb->ShowError(15, __FILE__, __LINE__);
	}
	if ($comment->QQ && (!CheckRegExp($comment->QQ, '[qq]'))) {
		$tqb->ShowError(83, __FILE__, __LINE__);
	}
	if ($comment->Email && (!CheckRegExp($comment->Email, '[email]'))) {
		$tqb->ShowError(29, __FILE__, __LINE__);
	}
	if ($comment->HomePage && (!CheckRegExp($comment->HomePage, '[homepage]'))) {
		$tqb->ShowError(30, __FILE__, __LINE__);
	}

	$comment->Name = substr($comment->Name, 0, 20);
	$comment->QQ = substr($comment->QQ, 0, 16);
	$comment->Email = substr($comment->Email, 0, 30);
	$comment->HomePage = substr($comment->HomePage, 0, 100);

	$comment->Content = TransferHTML($comment->Content, '[nohtml]');

	$comment->Content = substr($comment->Content, 0, 1000);
	$comment->Content = trim($comment->Content);
	if (strlen($comment->Content) == 0) {
		$tqb->ShowError(46, __FILE__, __LINE__);
	}
}

function FilterPost(&$article) {
	global $tqb;

	$article->Title = strip_tags($article->Title);
	$article->Alias = TransferHTML($article->Alias, '[normalname]');
	$article->Alias = str_replace(' ', '', $article->Alias);

	if ($article->Type == CFG_POST_TYPE_ARTICLE) {
		if (!$tqb->CheckRights('ArticleAll')) {
			$article->Content = TransferHTML($article->Content, '[noscript]');
			$article->Intro = TransferHTML($article->Intro, '[noscript]');
		}
	} elseif ($article->Type == CFG_POST_TYPE_PAGE) {
		if (!$tqb->CheckRights('PageAll')) {
			$article->Content = TransferHTML($article->Content, '[noscript]');
			$article->Intro = TransferHTML($article->Intro, '[noscript]');
		}
	}
}

function FilterMember(&$member) {
	global $tqb;
	$member->Intro = TransferHTML($member->Intro, '[noscript]');
	$member->Alias = TransferHTML($member->Alias, '[normalname]');
	$member->Alias = str_replace('/', '', $member->Alias);
	$member->Alias = str_replace('.', '', $member->Alias);
	$member->Alias = str_replace(' ', '', $member->Alias);
	if (strlen($member->Name) < $tqb->option['CFG_USERNAME_MIN'] || strlen($member->Name) > $tqb->option['CFG_USERNAME_MAX']) {
		$tqb->ShowError(77, __FILE__, __LINE__);
	}

	if (!CheckRegExp($member->Name, '[username]')) {
		$tqb->ShowError(77, __FILE__, __LINE__);
	}
	
	if (!CheckRegExp($member->QQ, '[qq]')) {
		$member->QQ = '';
	}

	if (!CheckRegExp($member->Email, '[email]')) {
		$member->Email = 'null@null.com';
	}

	if (substr($member->HomePage, 0, 4) != 'http') {
		$member->HomePage = 'http://' . $member->HomePage;
	}

	if (!CheckRegExp($member->HomePage, '[homepage]')) {
		$member->HomePage = '';
	}
	
	if (strlen($member->QQ) > $tqb->option['CFG_QQ_MAX']) {
		$tqb->ShowError(83, __FILE__, __LINE__);
	}

	if (strlen($member->Email) > $tqb->option['CFG_EMAIL_MAX']) {
		$tqb->ShowError(29, __FILE__, __LINE__);
	}

	if (strlen($member->HomePage) > $tqb->option['CFG_HOMEPAGE_MAX']) {
		$tqb->ShowError(30, __FILE__, __LINE__);
	}

}

function FilterModule(&$module) {
	global $tqb;
	$module->FileName = TransferHTML($module->FileName, '[filename]');
	$module->HtmlID = TransferHTML($module->HtmlID, '[normalname]');
}

function FilterCategory(&$category) {
	global $tqb;
	$category->Name = strip_tags($category->Name);
	$category->Alias = TransferHTML($category->Alias, '[normalname]');
	//$category->Alias=str_replace('/','',$category->Alias);
	$category->Alias = str_replace('.', '', $category->Alias);
	$category->Alias = str_replace(' ', '', $category->Alias);
}

function FilterTag(&$tag) {
	global $tqb;
	$tag->Name = strip_tags($tag->Name);
	$tag->Alias = TransferHTML($tag->Alias, '[normalname]');
}

//********************************************************
#统计函数
function CountNormalArticleNums() {
	global $tqb;
	$s = $tqb->db->sql->Count($tqb->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_Type', 0), array('=', 'log_IsTop', 0), array('=', 'log_Status', 0)));
	$num = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$tqb->cache->normal_article_nums = $num;
	$tqb->SaveCache();
}

function CountPost(&$article) {
	global $tqb;

	$id = $article->ID;

	$s = $tqb->db->sql->Count($tqb->table['Comment'], array(array('COUNT', '*', 'num')), array(array('=', 'comm_LogID', $id), array('=', 'comm_IsChecking', 0)));
	$num = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$article->CommNums = $num;
}

function CountPostArray($array) {
	global $tqb;
	$array = array_unique($array);
	foreach ($array as $value) {
		if ($value == 0) continue;
		$article = new Post;
		$article->LoadInfoByID($value);
		CountPost($article);
		$article->Save();
	}
}

function CountCategory(&$category) {
	global $tqb;

	$id = $category->ID;

	$s = $tqb->db->sql->Count($tqb->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_Type', 0), array('=', 'log_IsTop', 0), array('=', 'log_Status', 0), array('=', 'log_CateID', $id)));
	$num = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$category->Count = $num;
}

function CountCategoryArray($array) {
	global $tqb;
	$array = array_unique($array);
	foreach ($array as $value) {
		if ($value == 0) continue;
		CountCategory($tqb->categorys[$value]);
		$tqb->categorys[$value]->Save();
	}
}

function CountTag(&$tag) {
	global $tqb;

	$id = $tag->ID;

	$s = $tqb->db->sql->Count($tqb->table['Post'], array(array('COUNT', '*', 'num')), array(array('LIKE', 'log_Tag', '%{' . $id . '}%')));
	$num = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$tag->Count = $num;
}

function CountTagArrayString($string) {
	global $tqb;
	$array = $tqb->LoadTagsByIDString($string);
	foreach ($array as &$tag) {
		CountTag($tag);
		$tag->Save();
	}
}

function CountMember(&$member) {
	global $tqb;

	$id = $member->ID;

	$s = $tqb->db->sql->Count($tqb->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_AuthorID', $id), array('=', 'log_Type', 0)));
	$member_Articles = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$s = $tqb->db->sql->Count($tqb->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_AuthorID', $id), array('=', 'log_Type', 1)));
	$member_Pages = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$s = $tqb->db->sql->Count($tqb->table['Comment'], array(array('COUNT', '*', 'num')), array(array('=', 'comm_AuthorID', $id)));
	$member_Comments = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$s = $tqb->db->sql->Count($tqb->table['Upload'], array(array('COUNT', '*', 'num')), array(array('=', 'ul_AuthorID', $id)));
	$member_Uploads = GetValueInArrayByCurrent($tqb->db->Query($s), 'num');

	$member->Articles = $member_Articles;
	$member->Pages = $member_Pages;
	$member->Comments = $member_Comments;
	$member->Uploads = $member_Uploads;
}

function CountMemberArray($array) {
	global $tqb;
	$array = array_unique($array);
	foreach ($array as $value) {
		if ($value == 0) continue;
		CountMember($tqb->members[$value]);
		$tqb->members[$value]->Save();
	}
}

//********************************************************
#BuildModule
function BuildModule_catalog() {
	global $tqb;
	$s = '';

	if ($tqb->option['CFG_MODULE_CATALOG_STYLE'] == '2') {

		foreach ($tqb->categorysbyorder as $key => $value) {
			if ($value->Level == 0) {
				$s .= '<li class="li-cate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li>';
			}
		}
		foreach ($tqb->categorysbyorder as $key => $value) {
			if ($value->Level == 1) {
				$s = str_replace('<!--' . $value->ParentID . 'end-->', '<li class="li-subcate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li><!--' . $value->ParentID . 'end-->', $s);
			}
		}
		foreach ($tqb->categorysbyorder as $key => $value) {
			if ($value->Level == 2) {
				$s = str_replace('<!--' . $value->ParentID . 'end-->', '<li class="li-subcate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li><!--' . $value->ParentID . 'end-->', $s);
			}
		}
		foreach ($tqb->categorysbyorder as $key => $value) {
			if ($value->Level == 3) {
				$s = str_replace('<!--' . $value->ParentID . 'end-->', '<li class="li-subcate"><a href="' . $value->Url . '">' . $value->Name . '</a><!--' . $value->ID . 'begin--><!--' . $value->ID . 'end--></li><!--' . $value->ParentID . 'end-->', $s);
			}
		}

		foreach ($tqb->categorysbyorder as $key => $value) {
			$s = str_replace('<!--' . $value->ID . 'begin--><!--' . $value->ID . 'end-->', '', $s);
		}
		foreach ($tqb->categorysbyorder as $key => $value) {
			$s = str_replace('<!--' . $value->ID . 'begin-->', '<ul class="ul-subcates">', $s);
			$s = str_replace('<!--' . $value->ID . 'end-->', '</ul>', $s);
		}

	} elseif ($tqb->option['CFG_MODULE_CATALOG_STYLE'] == '1') {
		foreach ($tqb->categorysbyorder as $key => $value) {
			$s .= '<li>' . $value->Symbol . '<a href="' . $value->Url . '">' . $value->Name . '</a></li>';
		}
	} else {
		foreach ($tqb->categorysbyorder as $key => $value) {
			$s .= '<li><a href="' . $value->Url . '">' . $value->Name . '</a></li>';
		}
	}

	return $s;
}

function BuildModule_calendar($date = '') {
	global $tqb;

	if ($date == '')
		$date = date('Y-m', time());

	$s = '<table id="tbCalendar"><caption>';

	$url = new UrlRule($tqb->option['CFG_DATE_REGEX']);
	$value = strtotime('-1 month', strtotime($date));
	$url->Rules['{%date%}'] = date('Y-n', $value);
	$url->Rules['{%year%}'] = date('Y', $value);
	$url->Rules['{%month%}'] = date('n', $value);

	$url->Rules['{%day%}'] = 1;
	$s .= '<a href="' . $url->Make() . '">«</a>';

	$value = strtotime($date);
	$url->Rules['{%date%}'] = date('Y-n', $value);
	$url->Rules['{%year%}'] = date('Y', $value);
	$url->Rules['{%month%}'] = date('n', $value);
	$s .= '&nbsp;&nbsp;&nbsp;<a href="' . $url->Make() . '">' . str_replace(array('%y%', '%m%'), array(date('Y', $value), date('n', $value)), $tqb->lang['msg']['year_month']) . '</a>&nbsp;&nbsp;&nbsp;';

	$value = strtotime('+1 month', strtotime($date));
	$url->Rules['{%date%}'] = date('Y-n', $value);
	$url->Rules['{%year%}'] = date('Y', $value);
	$url->Rules['{%month%}'] = date('n', $value);
	$s .= '<a href="' . $url->Make() . '">»</a></caption>';

	$s .= '<thead><tr>';
	for ($i = 1; $i < 8; $i++) {
		$s .= '<th title="' . $tqb->lang['week'][$i] . '" scope="col"><small>' . $tqb->lang['week_abbr'][$i] . '</small></th>';
	}

	$s .= '</tr></thead>';
	$s .= '<tbody>';
	$s .= '<tr>';

	$a = 1;
	$b = date('t', strtotime($date));
	$j = date('N', strtotime($date . '-1'));
	$k = 7 - date('N', strtotime($date . '-' . date('t', strtotime($date))));

	if ($j > 1) {
		$s .= '<td class="pad" colspan="' . ($j - 1) . '"> </td>';
	} elseif ($j = 1) {
		$s .= '';
	}

	$l = $j - 1;
	for ($i = $a; $i < $b + 1; $i++) {
		$s .= '<td>' . $i . '</td>';

		$l = $l + 1;
		if ($l % 7 == 0)
			$s .= '</tr><tr>';
	}

	if ($k > 1) {
		$s .= '<td class="pad" colspan="' . ($k) . '"> </td>';
	} elseif ($k = 1) {
		$s .= '';
	}

	$s .= '</tr></tbody>';
	$s .= '</table>';
	$s = str_replace('<tr></tr>', '', $s);

	$fdate = strtotime($date);
	$ldate = (strtotime(date('Y-m-t', strtotime($date))) + 60 * 60 * 24);
	$sql = $tqb->db->sql->Select(
		$tqb->table['Post'],
		array('log_ID', 'log_PostTime'),
		array(
			array('=', 'log_Type', '0'),
			array('=', 'log_Status', '0'),
			array('BETWEEN', 'log_PostTime', $fdate, $ldate)
		),
		array('log_PostTime' => 'ASC'),
		null,
		null
	);
	$array = $tqb->db->Query($sql);
	$arraydate = array();
	$arrayid = array();
	foreach ($array as $key => $value) {
		$arraydate[date('j', $value['log_PostTime'])] = $value['log_ID'];
	}
	if (count($arraydate) > 0) {
		foreach ($arraydate as $key => $value) {
			$arrayid[] = array('log_ID', $value);
		}
		$articles = $tqb->GetArticleList('*', array(array('array', $arrayid)),null,null,null,false);
		foreach ($arraydate as $key => $value) {
			$a = $tqb->GetPostByID($value);
			$s = str_replace('<td>' . $key . '</td>', '<td><a href="' . $a->Url . '" style="color:#fff; background-color:#00f; width:20px; height:20px; display:block;">' . $key . '</a></td>', $s);
		}
	}

	return $s;

}

function BuildModule_comments() {
	global $tqb;

	$i = $tqb->modulesbyfilename['comments']->MaxLi;
	if ($i == 0) $i = 10;
	$comments = $tqb->GetCommentList('*', array(array('=', 'comm_IsChecking', 0)), array('comm_PostTime' => 'DESC'), $i, null);

	$s = '';
	foreach ($comments as $comment) {
		$s .= '<li><a href="' . $comment->Post->Url . '#cmt' . $comment->ID . '" title="' . htmlspecialchars($comment->Author->Name . ' @ ' . $comment->Time()) . '">' . TransferHTML($comment->Content, '[noenter]') . '</a></li>';
	}

	return $s;
}

function BuildModule_previous() {
	global $tqb;

	$i = $tqb->modulesbyfilename['previous']->MaxLi;
	if ($i == 0) $i = 10;
	$articles = $tqb->GetArticleList('*', array(array('=', 'log_Type', 0), array('=', 'log_Status', 0)), array('log_PostTime' => 'DESC'), $i, null,false);
	$s = '';
	foreach ($articles as $article) {
		$s .= '<li><a href="' . $article->Url . '">' . $article->Title . '</a></li>';
	}

	return $s;
}

function BuildModule_archives() {
	global $tqb;

	$i = $tqb->modulesbyfilename['archives']->MaxLi;
	if($i<0)return '';

	$fdate;
	$ldate;

	$sql = $tqb->db->sql->Select($tqb->table['Post'], array('log_PostTime'), null, array('log_PostTime' => 'DESC'), array(1), null);

	$array = $tqb->db->Query($sql);

	if (count($array) == 0)
		return '';

	$ldate = array(date('Y', $array[0]['log_PostTime']), date('m', $array[0]['log_PostTime']));

	$sql = $tqb->db->sql->Select($tqb->table['Post'], array('log_PostTime'), null, array('log_PostTime' => 'ASC'), array(1), null);

	$array = $tqb->db->Query($sql);

	if (count($array) == 0)
		return '';

	$fdate = array(date('Y', $array[0]['log_PostTime']), date('m', $array[0]['log_PostTime']));

	$arraydate = array();

	for ($i = $fdate[0]; $i < $ldate[0] + 1; $i++) {
		for ($j = 1; $j < 13; $j++) {
			$arraydate[] = strtotime($i . '-' . $j);
		}
	}

	foreach ($arraydate as $key => $value) {
		if ($value - strtotime($ldate[0] . '-' . $ldate[1]) > 0)
			unset($arraydate[$key]);
		if ($value - strtotime($fdate[0] . '-' . $fdate[1]) < 0)
			unset($arraydate[$key]);
	}

	$arraydate = array_reverse($arraydate);

	$s = '';

	foreach ($arraydate as $key => $value) {
		$url = new UrlRule($tqb->option['CFG_DATE_REGEX']);
		$url->Rules['{%date%}'] = date('Y-n', $value);
		$url->Rules['{%year%}'] = date('Y', $value);
		$url->Rules['{%month%}'] = date('n', $value);
		$url->Rules['{%day%}'] = 1;

		$fdate = $value;
		$ldate = (strtotime(date('Y-m-t', $value)) + 60 * 60 * 24);
		$sql = $tqb->db->sql->Count($tqb->table['Post'], array(array('COUNT', '*', 'num')), array(array('=', 'log_Type', '0'), array('=', 'log_Status', '0'), array('BETWEEN', 'log_PostTime', $fdate, $ldate)));
		$n = GetValueInArrayByCurrent($tqb->db->Query($sql), 'num');
		if ($n > 0) {
			$s .= '<li><a href="' . $url->Make() . '">' . str_replace(array('%y%', '%m%'), array(date('Y', $fdate), date('n', $fdate)), $tqb->lang['msg']['year_month']) . ' (' . $n . ')</a></li>';
		}
	}

	return $s;

}

function BuildModule_navbar() {
	global $tqb;

	$s = $tqb->modulesbyfilename['navbar']->Content;

	$a = array();
	preg_match_all('/<li id="navbar-(page|category|tag)-(\d+)">/', $s, $a);

	$b = $a[1];
	$c = $a[2];
	foreach ($b as $key => $value) {

		if ($b[$key] == 'page') {

			$type = 'page';
			$id = $c[$key];
			$o = $tqb->GetPostByID($id);
			$url = $o->Url;
			$name = $o->Title;

			$a = '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';
			$s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/a><\/li>/', $a, $s);

		}
		if ($b[$key] == 'category') {

			$type = 'category';
			$id = $c[$key];
			$o = $tqb->GetCategoryByID($id);
			$url = $o->Url;
			$name = $o->Name;

			$a = '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';
			$s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/a><\/li>/', $a, $s);

		}
		if ($b[$key] == 'tag') {

			$type = 'tag';
			$id = $c[$key];
			$o = $tqb->GetTagByID($id);
			$url = $o->Url;
			$name = $o->Name;

			$a = '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';
			$s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/a><\/li>/', $a, $s);

		}
	}

	return $s;
}

function BuildModule_tags() {
	global $tqb;
	$s = '';
	$i = $tqb->modulesbyfilename['tags']->MaxLi;
	if ($i == 0) $i = 25;
	$array = $tqb->GetTagList('*', '', array('tag_Count' => 'DESC'), $i, null);
	$array2 = array();
	foreach ($array as $tag) {
		$array2[$tag->ID] = $tag;
	}
	ksort($array2);

	foreach ($array2 as $tag) {
		$s .= '<li><a href="' . $tag->Url . '">' . $tag->Name . '<span class="tag-count"> (' . $tag->Count . ')</span></a></li>';
	}

	return $s;
}

function BuildModule_authors($level = 4) {
	global $tqb;
	$s = '';

	$w = array();
	$w[] = array('<=', 'mem_Level', $level);

	$array = $tqb->GetMemberList('*', $w, array('mem_ID' => 'ASC'), null, null);

	foreach ($array as $member) {
		$s .= '<li><a href="' . $member->Url . '">' . $member->Name . '<span class="article-nums"> (' . $member->Articles . ')</span></a></li>';
	}

	return $s;
}

function BuildModule_statistics($array = array()) {
	global $tqb;
	$all_artiles = 0;
	$all_pages = 0;
	$all_categorys = 0;
	$all_tags = 0;
	$all_views = 0;
	$all_comments = 0;

	if (count($array) == 0) {
		return $tqb->modulesbyfilename['statistics']->Content;
	}

	if (isset($array[0])) $all_artiles = $array[0];
	if (isset($array[1])) $all_pages = $array[1];
	if (isset($array[2])) $all_categorys = $array[2];
	if (isset($array[3])) $all_tags = $array[3];
	if (isset($array[4])) $all_views = $array[4];
	if (isset($array[5])) $all_comments = $array[5];

	$s = "";
	$s .= "<li>{$tqb->lang['msg']['all_artiles']}:{$all_artiles}</li>";
	$s .= "<li>{$tqb->lang['msg']['all_pages']}:{$all_pages}</li>";
	$s .= "<li>{$tqb->lang['msg']['all_categorys']}:{$all_categorys}</li>";
	$s .= "<li>{$tqb->lang['msg']['all_tags']}:{$all_tags}</li>";
	$s .= "<li>{$tqb->lang['msg']['all_comments']}:{$all_comments}</li>";
	if($tqb->option['CFG_VIEWNUMS_TURNOFF']==false){
		$s .= "<li>{$tqb->lang['msg']['all_views']}:{$all_views}</li>";
	}

	$tqb->modulesbyfilename['statistics']->Type = "ul";

	return $s;

}












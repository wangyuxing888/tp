<?php
/**
 * @author star.wang 
 * 2014-12-15
 * for myblog
 */

/**
 * 完成初始化工作
 * 
 * 
 */
require './admin/function/function_base.php';

$tqb->CheckGzip();
$tqb->Load();
$tqb->RedirectInstall(true);
foreach ($GLOBALS['Filter_Plugin_Index_Begin'] as $fpname => &$fpsignal) {
    $fpname();
}

$url = GetRequestUri();

if ($url == $cookiespath || $url == $cookiespath . 'index.php') {
    ViewList(null, null, null, null, null);
} elseif (isset($_GET['rewrite'])) {
    ViewAuto(GetVars('rewrite', 'GET'));
} elseif (isset($_GET['id']) || isset($_GET['alias'])) {
    ViewPost(GetVars('id', 'GET'), GetVars('alias', 'GET'));
} elseif (isset($_GET['page']) || isset($_GET['cate']) || isset($_GET['auth']) || isset($_GET['date']) || isset($_GET['tags'])) {
    ViewList(GetVars('page', 'GET'), GetVars('cate', 'GET'), GetVars('auth', 'GET'), GetVars('date', 'GET'), GetVars('tags', 'GET'));
} else {
    ViewAuto($url);
}

foreach ($GLOBALS['Filter_Plugin_Index_End'] as $fpname => &$fpsignal) {
    $fpname();
}
RunTime();

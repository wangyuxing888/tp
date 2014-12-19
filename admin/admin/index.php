<?php
require '../function/function_base.php';
require '../function/function_admin.php';

$tqb->CheckGzip();
$tqb->Load();

$action = GetVars('act', 'GET');

if (($action == '') || ($action == null)) {
    $action = 'admin';
}

foreach ($GLOBALS['Filter_Plugin_Admin_Begin'] as $fpname => &$fpsignal) {
    $fpname();
}

if (!$tqb->CheckRights($action)) {
    $tqb->ShowError(6, __FILE__, __LINE__);
    die();
}

$f = null;
switch ($action) {
    case 'ArticleAdmin':
        $f = 'Admin_ArticleAdmin';
        $blogtitle = $lang['msg']['article_manage'];
        break;
    case 'PageAdmin':
        $f = 'Admin_PageAdmin';
        $blogtitle = $lang['msg']['page_manage'];
        break;
    case 'CommentAdmin':
        $f = 'Admin_CommentAdmin';
        $blogtitle = $lang['msg']['comment_manage'];
        break;
    case 'CategoryAdmin':
        $f = 'Admin_CategoryAdmin';
        $blogtitle = $lang['msg']['category_manage'];
        //print_r(get_class($lang));
        break;
    case 'TagAdmin':
        $f = 'Admin_TagAdmin';
        $blogtitle = $lang['msg']['tag_manage'];
        break;
    case 'MemberAdmin':
        $f = 'Admin_MemberAdmin';
        $blogtitle = $lang['msg']['member_manage'];
        break;
    case 'ThemeAdmin':
        $f = 'Admin_ThemeAdmin';
        $blogtitle = $lang['msg']['theme_manage'];
        break;
    case 'ModuleAdmin':
        $f = 'Admin_ModuleAdmin';
        $blogtitle = $lang['msg']['module_manage'];
        break;
    case 'UploadAdmin':
        $f = 'Admin_UploadAdmin';
        $blogtitle = $lang['msg']['tools_manage'];
        break;
    case 'PluginAdmin':
        $f = 'Admin_PluginAdmin';
        $blogtitle = $lang['msg']['app_manage'];
        break;
    case 'SettingAdmin':
        $f = 'Admin_SettingAdmin';
        $blogtitle = $lang['msg']['settings'];
        break;
    case 'admin':
        $f = 'Admin_SiteInfo';
        $blogtitle = $lang['msg']['dashboard'];
        break;
    default:
        $tqb->ShowError(6, __FILE__, __LINE__);
        die();
        break;
}

require $blogpath . 'admin/admin/admin_header.php';
require $blogpath . 'admin/admin/admin_top.php';
?>
<div id="divMain">
<?php
$f();
?>
</div>
<?php
require $blogpath . 'admin/admin/admin_footer.php';
foreach ($GLOBALS['Filter_Plugin_Admin_End'] as $fpname => &$fpsignal) {
    $fpname();
}

RunTime();
?>
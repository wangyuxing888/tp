<?php
/**
 * 禁用错误报告
 */
error_reporting(0);
ob_start();
/*常用函数*/
require 'function_common.php';

require 'function_debug.php';
require 'function_plugin.php';
require 'function_event.php';

$tqbvers = array();
$tqbvers['20080222'] = 'V1.0 Release 20080222';
$tqbvers['20140101'] = 'V2.0 Release 20140101';
$tqbvers['20140511'] = 'V2.1 Release 20140511';

#定义常量
define('CFG_BLOG_VERSION', $tqbvers['20140511']);

define('CFG_POST_TYPE_ARTICLE', 0);
define('CFG_POST_TYPE_PAGE', 1);

define('CFG_POST_STATUS_PUBLIC', 0);
define('CFG_POST_STATUS_DRAFT', 1);
define('CFG_POST_STATUS_AUDITING', 2);

define('CFG_MEMBER_STATUS_NORMAL', 0);
define('CFG_MEMBER_STATUS_AUDITING', 1);
define('CFG_MEMBER_STATUS_LOCKED', 2);

define('CFG_REWRITE_GO_ON', 'go_on');

if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    _stripslashes($_GET);
    _stripslashes($_POST);
    _stripslashes($_COOKIE);
}


$action = null;
$manage = false;


$blogpath = str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../')) . '/';
$contentdir = $blogpath . 'content/';

$option_users = null;
if (file_exists($blogpath . 'config.php')) {
    $option_users = require($blogpath . 'config.php');
}
if (!is_array($option_users))
    $option_users = array();
$option = require($blogpath . 'admin/config/config.php');
foreach ($option_users as $key => $value) {
    $option[$key] = $value;
}
unset($option_users);
date_default_timezone_set($option['CFG_TIME_ZONE_NAME']);
$lang = require($blogpath . 'content/language/' . $option['CFG_BLOG_LANGUAGEPACK'] . '.php');
$blogtitle = $option['CFG_BLOG_SUBNAME'];
$blogname = &$option['CFG_BLOG_NAME'];
$blogsubname = &$option['CFG_BLOG_SUBNAME'];
$blogtheme = &$option['CFG_BLOG_THEME'];
$blogstyle = &$option['CFG_BLOG_CSS'];
$blogversion = substr(CFG_BLOG_VERSION, -8, 8);

$cookiespath = null;
$bloghost = GetCurrentHost($cookiespath);

#加载TQBlog数据库类对象
$lib_array = array('tqblogphp', 'dbsql', 'base');
foreach ($lib_array as $f) {
    require $blogpath . 'admin/class/' . $f . '.php';
}
unset($lib_array);

#定义命令
$actions = array(
    'login' => 6,
    'logout' => 6,
    'verify' => 6,
    'admin' => 5,
    'search' => 6,
    'misc' => 6,
    'feed' => 6,
    'xml' => 6,
    'cmt' => 6,
    'getcmt' => 6,
    'ArticleEdt' => 4,
    'ArticlePst' => 4,
    'ArticleDel' => 4,
    'ArticlePub' => 3,
    'PageEdt' => 2,
    'PagePst' => 2,
    'PageDel' => 2,
    'CategoryEdt' => 2,
    'CategoryPst' => 2,
    'CategoryDel' => 2,
    'CommentEdt' => 5,
    'CommentSav' => 5,
    'CommentDel' => 5,
    'CommentChk' => 5,
    'CommentBat' => 5,
    'MemberEdt' => 5,
    'MemberPst' => 5,
    'MemberDel' => 1,
    'MemberNew' => 1,
    'TagEdt' => 2,
    'TagPst' => 2,
    'TagDel' => 2,
    'TagNew' => 2,
    'PluginEnb' => 1,
    'PluginDis' => 1,
    'UploadPst' => 3,
    'UploadDel' => 3,
    'ModuleEdt' => 3,
    'ModulePst' => 3,
    'ModuleDel' => 3,
    'ThemeSet' => 1,
    'SidebarSet' => 1,
    'SettingSav' => 1,
    'ArticleAdmin' => 4,
    'PageAdmin' => 2,
    'CategoryAdmin' => 2,
    'SettingAdmin' => 1,
    'TagAdmin' => 2,
    'CommentAdmin' => 5,
    'UploadAdmin' => 3,
    'MemberAdmin' => 5,
    'ThemeAdmin' => 1,
    'PluginAdmin' => 1,
    'ModuleAdmin' => 1,
    'ArticleAll' => 2,
    'PageAll' => 2,
    'CategoryAll' => 2,
    'CommentAll' => 2,
    'MemberAll' => 1,
    'TagAll' => 2,
    'UploadAll' => 2,
    'root' => 1,
);

$table = array(
    'Post' => '%pre%post',
    'Category' => '%pre%category',
    'Comment' => '%pre%comment',
    'Tag' => '%pre%tag',
    'Upload' => '%pre%upload',
    'Counter' => '%pre%counter',
    'Module' => '%pre%module',
    'Member' => '%pre%member',
    'Config' => '%pre%config',
);

$datainfo = array(
    'Config' => array(
        'Name' => array('conf_Name', 'string', 250, ''),
        'Value' => array('conf_Value', 'string', '', ''),
    ),
    'Post' => array(
        'ID' => array('log_ID', 'integer', '', 0),
        'CateID' => array('log_CateID', 'integer', '', 0),
        'AuthorID' => array('log_AuthorID', 'integer', '', 0),
        'Tag' => array('log_Tag', 'string', 250, ''),
        'Status' => array('log_Status', 'integer', '', 0),
        'Origin' => array('log_Origin', 'integer', '', 0),
        'Type' => array('log_Type', 'integer', '', 0),
        'Alias' => array('log_Alias', 'string', 250, ''),
        'IsTop' => array('log_IsTop', 'boolean', '', false),
        'IsLock' => array('log_IsLock', 'boolean', '', false),
        'Title' => array('log_Title', 'string', 250, ''),
        'Intro' => array('log_Intro', 'string', '', ''),
        'Content' => array('log_Content', 'string', '', ''),
        'PostTime' => array('log_PostTime', 'integer', '', 0),
        'CommNums' => array('log_CommNums', 'integer', '', 0),
        'ViewNums' => array('log_ViewNums', 'integer', '', 0),
        'Good' => array('log_Good', 'integer', '', 0),
        'Template' => array('log_Template', 'string', 50, ''),
        'Meta' => array('log_Meta', 'string', '', ''),
    ),
    'Category' => array(
        'ID' => array('cate_ID', 'integer', '', 0),
        'Name' => array('cate_Name', 'string', 50, ''),
        'Order' => array('cate_Order', 'integer', '', 0),
        'Count' => array('cate_Count', 'integer', '', 0),
        'Alias' => array('cate_Alias', 'string', 50, ''),
        'Intro' => array('cate_Intro', 'string', '', ''),
        'RootID' => array('cate_RootID', 'integer', '', 0),
        'ParentID' => array('cate_ParentID', 'integer', '', 0),
        'Template' => array('cate_Template', 'string', 50, ''),
        'LogTemplate' => array('cate_LogTemplate', 'string', 50, ''),
        'Meta' => array('cate_Meta', 'string', '', ''),
    ),
    'Comment' => array(
        'ID' => array('comm_ID', 'integer', '', 0),
        'LogID' => array('comm_LogID', 'integer', '', 0),
        'IsChecking' => array('comm_IsChecking', 'boolean', '', false),
        'RootID' => array('comm_RootID', 'integer', '', 0),
        'ParentID' => array('comm_ParentID', 'integer', '', 0),
        'AuthorID' => array('comm_AuthorID', 'integer', '', 0),
        'Name' => array('comm_Name', 'string', 20, ''),
        'Content' => array('comm_Content', 'string', '', ''),
        'QQ' => array('comm_QQ', 'string', 16, ''),
        'Email' => array('comm_Email', 'string', 50, ''),
        'HomePage' => array('comm_HomePage', 'string', 250, ''),
        'PostTime' => array('comm_PostTime', 'integer', '', 0),
        'IP' => array('comm_IP', 'string', 15, ''),
        'Agent' => array('comm_Agent', 'string', '', ''),
        'Meta' => array('comm_Meta', 'string', '', ''),
        'Good' => array('comm_Good', 'integer', '', 0),
    ),
    'Counter' => array(
        'ID' => array('coun_ID', 'integer', '', 0),
        'MemID' => array('coun_MemID', 'integer', '', 0),
        'IP' => array('coun_IP', 'string', 15, ''),
        'Agent' => array('coun_Agent', 'string', '', ''),
        'Refer' => array('coun_Refer', 'string', 250, ''),
        'Title' => array('coun_Title', 'string', 250, ''),
        'PostTime' => array('coun_PostTime', 'integer', '', 0),
        'Description' => array('coun_Description', 'string', '', ''),
        'PostData' => array('coun_PostData', 'string', '', ''),
        'AllRequestHeader' => array('coun_AllRequestHeader', 'string', '', ''),
    ),
    'Module' => array(
        'ID' => array('mod_ID', 'integer', '', 0),
        'Name' => array('mod_Name', 'string', 100, ''),
        'FileName' => array('mod_FileName', 'string', 50, ''),
        'Content' => array('mod_Content', 'string', '', ''),
        'HtmlID' => array('mod_HtmlID', 'string', 50, ''),
        'Type' => array('mod_Type', 'string', 5, 'div'),
        'MaxLi' => array('mod_MaxLi', 'integer', '', 0),
        'Source' => array('mod_Source', 'string', 50, 'user'),
        'IsHideTitle' => array('mod_IsHideTitle', 'boolean', '', false),
        'Meta' => array('mod_Meta', 'string', '', ''),
    ),
    'Member' => array(
        'ID' => array('mem_ID', 'integer', '', 0),
        'Guid' => array('mem_Guid', 'string', 36, ''),
        'Level' => array('mem_Level', 'integer', '', 6),
        'Status' => array('mem_Status', 'integer', '', 0),
        'Name' => array('mem_Name', 'string', 50, ''),
        'Password' => array('mem_Password', 'string', 32, ''),
        'QQ' => array('mem_QQ', 'string', 16, ''),
        'Email' => array('mem_Email', 'string', 50, ''),
        'HomePage' => array('mem_HomePage', 'string', 250, ''),
        'IP' => array('mem_IP', 'string', 15, ''),
        'PostTime' => array('mem_PostTime', 'integer', '', 0),
        'Alias' => array('mem_Alias', 'string', 250, ''),
        'Intro' => array('mem_Intro', 'string', '', ''),
        'Articles' => array('mem_Articles', 'integer', '', 0),
        'Pages' => array('mem_Pages', 'integer', '', 0),
        'Comments' => array('mem_Comments', 'integer', '', 0),
        'Uploads' => array('mem_Uploads', 'integer', '', 0),
        'Template' => array('mem_Template', 'string', 50, ''),
        'Meta' => array('mem_Meta', 'string', '', ''),
    ),
    'Tag' => array(
        'ID' => array('tag_ID', 'integer', '', 0),
        'Name' => array('tag_Name', 'string', 250, ''),
        'Order' => array('tag_Order', 'integer', '', 0),
        'Count' => array('tag_Count', 'integer', '', 0),
        'Alias' => array('tag_Alias', 'string', 250, ''),
        'Intro' => array('tag_Intro', 'string', '', ''),
        'Template' => array('tag_Template', 'string', 50, ''),
        'Meta' => array('tag_Meta', 'string', '', ''),
    ),
    'Upload' => array(
        'ID' => array('ul_ID', 'integer', '', 0),
        'AuthorID' => array('ul_AuthorID', 'integer', '', 0),
        'Size' => array('ul_Size', 'integer', '', 0),
        'Name' => array('ul_Name', 'string', 250, ''),
        'SourceName' => array('ul_SourceName', 'string', 250, ''),
        'MimeType' => array('ul_MimeType', 'string', 50, ''),
        'PostTime' => array('ul_PostTime', 'integer', '', 0),
        'DownNums' => array('ul_DownNums', 'integer', '', 0),
        'LogID' => array('ul_LogID', 'integer', '', 0),
        'Intro' => array('ul_Intro', 'string', '', ''),
        'Meta' => array('ul_Meta', 'string', '', ''),
    ),
);
#实例化tqb
$tqb = TQBlogPHP::GetInstance();
$tqb->Initialize();

/* include plugin */
#加载主题插件
if (file_exists($filename = $contentdir . 'theme/' . $blogtheme . '/include.php')) {
    require $filename;
}

#加载激活插件
$ap = explode("|", $option['CFG_USING_PLUGIN_LIST']);
$ap = array_unique($ap);
foreach ($ap as $plugin) {
    if (file_exists($filename = $contentdir . 'plugin/' . $plugin . '/include.php')) {
        require $filename;
    }
}
unset($ap);
unset($filename);
ActivePlugin();
/* autoload */
function __autoload($classname) {
    foreach ($GLOBALS['Filter_Plugin_Autoload'] as $fpname => &$fpsignal) {
        $fpreturn = $fpname($classname);
        if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
            return $fpreturn;
        }
    }
    if (is_readable($f = $GLOBALS['blogpath'] . 'admin/class/' . strtolower($classname) . '.php')) {
        require $f;
    }
}

function _stripslashes(&$val) {
    if (!is_array($val)){
        return stripslashes($val);
    }
    foreach ($val as $k => &$v){
        $val[$k] = _stripslashes($v);
    }
    return $val;
}

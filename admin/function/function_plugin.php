<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: function_plugin.php 33828 2008-02-22 09:25:26Z team $
 */

//---------- 接口模式 ----------

define('PLUGIN_EXITSIGNAL_NONE', '');
define('PLUGIN_EXITSIGNAL_RETURN', 'return');
define('PLUGIN_EXITSIGNAL_BREAK', 'break');

//定义总插件激活函数列表
$plugins = array();

/*
'------------------------------------------------
' 目的： 注册插件函数，由每个插件主动调用
'------------------------------------------------
*/
function RegisterPlugin($strPluginName, $strPluginActiveFunction) {

	$GLOBALS['plugins'][$strPluginName] = $strPluginActiveFunction;

}

/*
'------------------------------------------------
' 目的： 激活插件函数
'------------------------------------------------
*/
function ActivePlugin() {

	foreach ($GLOBALS['plugins'] as &$sPluginActiveFunctions) {
		if(function_exists($sPluginActiveFunctions))$sPluginActiveFunctions();
	}

}

/*
'------------------------------------------------
' 目的： 安装插件函数，只运行一次
'------------------------------------------------
*/
function InstallPlugin($strPluginName) {

	if (function_exists('InstallPlugin_' . $strPluginName) == true) {
		$f = 'InstallPlugin_' . $strPluginName;
		$f();
	}

}

/*
'------------------------------------------------
' 目的： 删除插件函数，只运行一次
'------------------------------------------------
*/
function UninstallPlugin($strPluginName) {

	if (function_exists('UninstallPlugin_' . $strPluginName) == true) {
		$f = 'UninstallPlugin_' . $strPluginName;
		$f();
	}

}

/*
'------------------------------------------------
' 目的：挂上Action接口
' 参数：'plugname:接口名称
		'actioncode:要执行的语句，要转义为Execute可执行语句
'------------------------------------------------
*/
//function Add_Action_Plugin($plugname,$actioncode){
//	$GLOBALS[$plugname][]=$actioncode;
//}

/*
'------------------------------------------------
' 目的：挂上Filter接口
' 参数：'plugname:接口名称
		'functionname:要挂接的函数名
		'exitsignal:return,break,continue
'------------------------------------------------
*/
function Add_Filter_Plugin($plugname, $functionname, $exitsignal = PLUGIN_EXITSIGNAL_NONE) {
	$GLOBALS[$plugname][$functionname] = $exitsignal;
}

/*
'------------------------------------------------
' 目的：挂上Response接口
' 参数：'plugname:接口名称
		'parameter:要写入的内容
'------------------------------------------------
*/
//function Add_Response_Plugin($plugname,$functionname){
//	$GLOBALS[$plugname][]=$functionname;
//}

//------------------------------------------------
//base里的

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Autoload
'参数:$classname
'说明:定义autoload魔术方法
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Autoload = array();

//------------------------------------------------
//TQB类里的接口

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_Call
'参数:$method, $args
'说明:Tqb类的魔术方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_Call = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_Get
'参数:$name
'说明:Tqb类的魔术方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_Get = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_Set
'参数:$name,$value
'说明:Tqb类的魔术方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_Set = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_CheckRights
'参数:$action
'说明:Tqb类的检查权限接口(检查当前用户)
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_CheckRights = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_CheckRightsByLevel
'参数:$level,$action
'说明:Tqb类的检查权限接口(检查指定level)
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_CheckRightsByLevel = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_ShowError
'参数:$idortext
'说明:Tqb类的显示错误接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_ShowError = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_ShowCaptcha
'参数:$id
'说明:Tqb类的显示验证码接口，具有唯一性；
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_ShowCaptcha = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_CheckCaptcha
'参数:$vaidcode,$id
'说明:Tqb类的比对验证码接口，具有唯一性；
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_CheckCaptcha = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_BuildTemplate
'参数:$template
'说明:Tqb类的重新编译模板接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_BuildTemplate = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_MakeTemplatetags
'参数:$template
'说明:Tqb类的生成模板标签接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_MakeTemplatetags = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_BuildModule
'参数:
'说明:Tqb类的生成模块内容的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_BuildModule = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_Load
'参数:
'说明:Tqb类的初始加载接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_Load = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_LoadManage
'参数:
'说明:Tqb类的后台管理初始加载接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_LoadManage = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tqb_Terminate
'参数:
'说明:Tqb类的终结接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tqb_Terminate = array();

//------------------------------------------------
//前台view,index

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Index_Begin
'参数:
'说明:定义index.php接口 起动
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Index_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Index_End
'参数:
'说明:定义index.php接口 结束
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Index_End = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Html_Util
'参数:
'说明:html_util.php脚本调用,JS页接口需要强制开启
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Html_Util = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Search_Begin
'参数:
'说明:搜索页接口，可以接管搜索页。
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Search_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Feed_Begin
'参数:
'说明:Feed页接口，可以接管Feed页。
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Feed_Begin = array();

//------------------------------------------------
#CMD里的接口

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Cmd_Begin
'参数:
'说明:admin.php的启动接口,可以在这里拦截各种action
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Cmd_Begin = array();

//------------------------------------------------
//后台里的接口

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Login_Header
'参数:
'说明:定义Login.php首页header接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Login_Header = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_Begin
'参数:
'说明:后台管理页的启动接口,可以拦截后台管理请求实现自己的管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_End
'参数:
'说明:后台管理页的终结接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_End = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_Header
'参数:
'说明:定义后台首页header接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_Header = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_Footer
'参数:
'说明:定义后台首页footer接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_Footer = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_LeftMenu
'参数:&$leftmenus
'说明:定义后台左侧栏接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_LeftMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_TopMenu
'参数:&$topmenus
'说明:定义后台顶部导航栏接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_TopMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_SiteInfo_SubMenu
'参数:
'说明:后台首页SubMenu
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_SiteInfo_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_ArticleAdmin_SubMenu
'参数:
'说明:文章管理SubMenu
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_ArticleAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_PageAdmin_SubMenu
'参数:
'说明:页面管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_PageAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_CategoryAdmin_SubMenu
'参数:
'说明:分类管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_CategoryAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_CommentAdmin_SubMenu
'参数:
'说明:评论管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_CommentAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_MemberAdmin_SubMenu
'参数:
'说明:用户管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_MemberAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_UploadAdmin_SubMenu
'参数:
'说明:
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_UploadAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_TagAdmin_SubMenu
'参数:
'说明:标签管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_TagAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_PluginAdmin_SubMenu
'参数:
'说明:插件管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_PluginAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_ThemeAdmin_SubMenu
'参数:
'说明:主题管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_ThemeAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_ModuleAdmin_SubMenu
'参数:
'说明:模块管理
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_ModuleAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_SettingAdmin_SubMenu
'参数:
'说明:站点设置
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_SettingAdmin_SubMenu = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Edit_Begin
'参数:
'说明:文章页面编辑页开始接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Edit_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Edit_End
'参数:
'说明:文章页面编辑页结束接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Edit_End = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Edit_Response
'参数:
'说明:文章页面编辑1号输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Edit_Response = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Edit_Response2
'参数:
'说明:文章页面编辑2号输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Edit_Response2 = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Edit_Response3
'参数:
'说明:文章页面编辑3号输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Edit_Response3 = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Category_Edit_Response
'参数:
'说明:分类编辑页输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Category_Edit_Response = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tag_Edit_Response
'参数:
'说明:标签编辑页输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tag_Edit_Response = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Member_Edit_Response
'参数:
'说明:会员编辑页输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Member_Edit_Response = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Admin_Util
'参数:
'说明:admin_util.php脚本页的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Admin_Util = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Login_Header
'参数:
'说明:定义Login.php首页header接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_CreateOptoinsOfCategorys = array();

//------------------------------------------------
#Event里的接口

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_ViewAuto_Begin
'参数:&$url
'说明:定义列表输出接口Begin
'调用:
'------------------------------------------------
*/
$Filter_Plugin_ViewAuto_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_ViewAuto_End
'参数:&$url
'说明:定义列表输出接口End
'调用:
'------------------------------------------------
*/
$Filter_Plugin_ViewAuto_End = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_ViewList_Begin
'参数:&$page,&$cate,&$auth,&$date,&$tags
'说明:定义列表输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_ViewList_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_ViewPost_Begin
'参数:&$id,&$alias
'说明:定义列表输出接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_ViewPost_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_ViewList_Template
'参数:&$template
'说明:
'调用:
'------------------------------------------------
*/
$Filter_Plugin_ViewList_Template = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_ViewPost_Template
'参数:&$template
'说明:
'调用:
'------------------------------------------------
*/
$Filter_Plugin_ViewPost_Template = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_ViewComments_Template
'参数:&$template
'说明:
'调用:
'------------------------------------------------
*/
$Filter_Plugin_ViewComments_Template = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostModule_Core
'参数:&$mod
'说明:模块编辑的核心接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostModule_Core = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostMember_Core
'参数:&$mem
'说明:会员编辑的核心接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostMember_Core = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostTag_Core
'参数:&$tag
'说明:标签编辑的核心接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostTag_Core = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostCategory_Core
'参数:&$cate
'说明:分类编辑的核心接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostCategory_Core = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostComment_Core
'参数:&$cmt
'说明:评论发表的核心接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostComment_Core = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostArticle_Core
'参数:&$article
'说明:文章编辑的核心接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostArticle_Core = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostPage_Core
'参数:&$article
'说明:页面编辑的核心接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostPage_Core = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostMember_Succeed
'参数:&$mem
'说明:会员编辑成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostMember_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostTag_Succeed
'参数:&$tag
'说明:标签编辑成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostTag_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostCategory_Succeed
'参数:&$cate
'说明:分类编辑成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostCategory_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostComment_Succeed
'参数:&$cmt
'说明:评论发表成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostComment_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostPage_Succeed
'参数:&$article
'说明:页面编辑成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostPage_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostArticle_Succeed
'参数:&$article
'说明:文章编辑成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostArticle_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_PostModule_Succeed
'参数:&$mod
'说明:模块编辑成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_PostModule_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_DelMember_Succeed
'参数:&$mem
'说明:会员删除成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_DelMember_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_DelTag_Succeed
'参数:&$tag
'说明:标签删除成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_DelTag_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_DelCategory_Succeed
'参数:&$cate
'说明:分类删除成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_DelCategory_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_DelComment_Succeed
'参数:&$cmt
'说明:评论删除成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_DelComment_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_DelPage_Succeed
'参数:&$article
'说明:页面删除成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_DelPage_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_DelArticle_Succeed
'参数:&$article
'说明:文章删除成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_DelArticle_Succeed = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_DelModule_Succeed
'参数:&$mod
'说明:模块删除成功的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_DelModule_Succeed = array();

//------------------------------------------------
#类里的接口

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Post_CommentPostUrl
'参数:$post
'说明:Post类的CommentPostUrl接口
'调用:返回CommentPostUrl值.
'------------------------------------------------
*/
$Filter_Plugin_Post_CommentPostUrl = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Post_RelatedList
'参数:$post
'说明:Post类的RelatedList 接口
'调用:返回RelatedList Array.
'------------------------------------------------
*/
$Filter_Plugin_Post_RelatedList = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Post_Call
'参数:&$post,$method,$args
'说明:Post类的魔术方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Post_Call = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Comment_Call
'参数:&$comment,$method,$args
'说明:Comment类的魔术方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Comment_Call = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tag_Call
'参数:&$tag,$method,$args
'说明:Tag类的魔术方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tag_Call = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Category_Call
'参数:&$category,$method,$args
'说明:Category类的魔术方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Category_Call = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Post_Save
'参数:&$post,$method,$args
'说明:Post类的Save方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Post_Save = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Comment_Save
'参数:&$comment,$method,$args
'说明:Comment类的Save方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Comment_Save = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Tag_Save
'参数:&$tag,$method,$args
'说明:Tag类的Save方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Tag_Save = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Category_Save
'参数:&$category,$method,$args
'说明:Category类的Save方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Category_Save = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Member_Save
'参数:&$member,$method,$args
'说明:Member类的Save方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Member_Save = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Mebmer_Avatar
'参数:$member
'说明:Mebmer类的Avatar接口
'调用:返回Avatar值,可以返回null.
'------------------------------------------------
*/
$Filter_Plugin_Mebmer_Avatar = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Upload_SaveFile
'参数:$tmp,$this
'说明:Upload类的SaveFile方法接口
'调用:对$tmp临时文件进行拦截
'------------------------------------------------
*/
$Filter_Plugin_Upload_SaveFile = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Upload_SaveBase64File=
'参数:$str64,$this
'说明:Upload类的SaveBase64File方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Upload_SaveBase64File = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Upload_DelFile
'参数:$this
'说明:Upload类的DelFile方法接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Upload_DelFile = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Upload_Url
'参数:$upload
'说明:Upload类的Url方法接口
'调用:返回Url的值,可以返回null.
'------------------------------------------------
*/
$Filter_Plugin_Upload_Url = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Template_Compiling_Begin
'参数:$this,$content
'说明:Template类编译一个模板前的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Template_Compiling_Begin = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Template_Compiling_End
'参数:$this,$content
'说明:Template类编译一个模板后的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Template_Compiling_End = array();

/*
'------------------------------------------------
'类型:Filter
'名称:Filter_Plugin_Template_GetTemplate
'参数:$this,$name
'说明:Template类读取一个模板前的接口
'调用:
'------------------------------------------------
*/
$Filter_Plugin_Template_GetTemplate = array();

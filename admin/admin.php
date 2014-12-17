<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: admin.php 33828 2008-02-22 09:25:26Z team $
 */
 
require './function/function_base.php';

$tqb->Load();

$action=GetVars('act','GET');

foreach ($GLOBALS['Filter_Plugin_Cmd_Begin'] as $fpname => &$fpsignal) {$fpname();}

if(!$tqb->CheckRights($action)){$tqb->ShowError(6,__FILE__,__LINE__);die();}

switch ($action) {
	case 'login':
		if ($tqb->user->ID>0 && GetVars('redirect','GET')) {
			Redirect(GetVars('redirect','GET'));
		}
		if ($tqb->CheckRights('admin')) {
			Redirect('admin.php?act=admin');
		}
		if ($tqb->user->ID==0 && GetVars('redirect','GET')) {
			setcookie("redirect", GetVars('redirect','GET'),0,$tqb->cookiespath);
		}
		Redirect('login.php');
		break;
	case 'logout':
		Logout();
		Redirect('../');
		break;
	case 'admin':
		Redirect('admin/?act=admin');
		break;
	case 'verify':
		if(VerifyLogin()){
			if ($tqb->user->ID>0 && GetVars('redirect','COOKIE')) {
				Redirect(GetVars('redirect','COOKIE'));
			}
			Redirect('admin/?act=admin');
		}else{
			Redirect('../');
		}
		break;
	case 'search':
		$q=urlencode(trim(strip_tags(GetVars('q','POST'))));
		Redirect('../search.php?q=' . $q);
		break;
	case 'misc':
		require './function/function_misc.php';
		break;
	case 'cmt':
		if(GetVars('isajax','POST')){
			Add_Filter_Plugin('Filter_Plugin_Tqb_ShowError','RespondError',PLUGIN_EXITSIGNAL_RETURN);
		}
		PostComment();
		$tqb->BuildModule();
		if(GetVars('isajax','POST')){
			die();
		}else{
			Redirect(GetVars('HTTP_REFERER','SERVER'));
		}
		break;
	case 'getcmt':
		ViewComments((int)GetVars('postid','GET'),(int)GetVars('page','GET'));
		die();
		break;
	case 'ArticleEdt':
		Redirect('admin/edit.php?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'ArticleDel':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		DelArticle();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=ArticleAdmin');
		break;
	case 'ArticleAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'ArticlePst':
		PostArticle();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=ArticleAdmin');
		break;
	case 'PageEdt':
		Redirect('admin/edit.php?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'PageDel':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		DelPage();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=PageAdmin');
		break;
	case 'PageAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'PagePst':
		PostPage();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=PageAdmin');
		break;
	case 'CategoryAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'CategoryEdt':
		Redirect('admin/category_edit.php?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'CategoryPst':
		PostCategory();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=CategoryAdmin');
		break;
	case 'CategoryDel':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		DelCategory();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=CategoryAdmin');
		break;
	case 'CommentDel':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		DelComment();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentChk':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		CheckComment();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentBat':
		if(isset($_POST['id'])==false)Redirect($_SERVER["HTTP_REFERER"]);
		BatchComment();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect($_SERVER["HTTP_REFERER"]);
		break;
	case 'CommentAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberEdt':
		Redirect('admin/member_edit.php?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberNew':
		Redirect('admin/member_edit.php?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'MemberPst':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		PostMember();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=MemberAdmin');
		break;
	case 'MemberDel':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		if(DelMember()){
			$tqb->BuildModule();
			$tqb->SetHint('good');
		}else{
			$tqb->SetHint('bad');
		}
		Redirect('admin.php?act=MemberAdmin');
		break;
	case 'UploadAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'UploadPst':
		PostUpload();
		$tqb->SetHint('good');
		Redirect('admin.php?act=UploadAdmin');
		break;
	case 'UploadDel':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		DelUpload();
		$tqb->SetHint('good');
		Redirect('admin.php?act=UploadAdmin');
		break;
	case 'TagAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'TagEdt':
		Redirect('admin/tag_edit.php?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'TagPst':
		PostTag();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=TagAdmin');
		break;
	case 'TagDel':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		DelTag();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=TagAdmin');
		break;
	case 'PluginAdmin':
		if(GetVars('install','GET')){
			InstallPlugin(GetVars('install','GET'));
		}
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'PluginDis':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		UninstallPlugin(GetVars('name','GET'));
		DisablePlugin(GetVars('name','GET'));
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=PluginAdmin');
		break;
	case 'PluginEnb':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		$install='&install=';
		$install .= EnablePlugin(GetVars('name','GET'));
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=PluginAdmin' . $install);
		break;
	case 'ThemeAdmin':
		if(GetVars('install','GET')){
			InstallPlugin(GetVars('install','GET'));
		}
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'ThemeSet':
		$install='&install=';
		$install .=SetTheme(GetVars('theme','POST'),GetVars('style','POST'));
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=ThemeAdmin' . $install);
		break;
	case 'SidebarSet':
		SetSidebar();
		$tqb->BuildModule();
		break;
	case 'ModuleEdt':
		Redirect('admin/module_edit.php?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'ModulePst':
		PostModule();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=ModuleAdmin');
		break;
	case 'ModuleDel':

		DelModule();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=ModuleAdmin');
		break;
	case 'ModuleAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'SettingAdmin':
		Redirect('admin/?' . GetVars('QUERY_STRING','SERVER'));
		break;
	case 'SettingSav':
		if(!$tqb->ValidToken(GetVars('token','GET'))){$tqb->ShowError(5,__FILE__,__LINE__);die();}
		SaveSetting();
		$tqb->BuildModule();
		$tqb->SetHint('good');
		Redirect('admin.php?act=SettingAdmin');
		break;
	default:
		# code...
		break;
}

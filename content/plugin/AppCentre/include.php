<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: include.php 33828 2008-02-22 09:25:26Z team $
 */
 
//注册插件
RegisterPlugin("AppCentre","ActivePlugin_AppCentre");

define('APPCENTRE_URL','http://app.tqtqtq.com/app.php');

define('APPCENTRE_SYSTEM_UPDATE','http://update.tqtqtq.com/update.php');

function ActivePlugin_AppCentre() {

	Add_Filter_Plugin('Filter_Plugin_Admin_LeftMenu','AppCentre_AddMenu');
	Add_Filter_Plugin('Filter_Plugin_Admin_ThemeAdmin_SubMenu','AppCentre_AddThemeMenu');
	Add_Filter_Plugin('Filter_Plugin_Admin_PluginAdmin_SubMenu','AppCentre_AddPluginMenu');
	Add_Filter_Plugin('Filter_Plugin_Admin_SiteInfo_SubMenu','AppCentre_AddSiteInfoMenu');
}

function InstallPlugin_AppCentre(){
	global $tqb;
	$tqb->Config('AppCentre')->enabledcheck=1;
	$tqb->Config('AppCentre')->checkbeta=0;
	$tqb->Config('AppCentre')->enabledevelop=0;
	$tqb->SaveConfig('AppCentre');
}


function AppCentre_AddMenu(&$m){
	global $tqb;
	$m['nav_AppCentre']=BuildLeftMenu("root","应用中心",$tqb->host . "content/plugin/AppCentre/main.php","nav_AppCentre","aAppCentre",$tqb->host . "content/plugin/AppCentre/images/Cube1.png");
}

function AppCentre_AddSiteInfoMenu(){
	global $tqb;
	if($tqb->Config('AppCentre')->enabledcheck){
		$last=(int)$tqb->Config('AppCentre')->lastchecktime;
		if( (time()-$last) > 11*60*60 ){
			echo "<script type='text/javascript'>$(document).ready(function(){  $.getScript('{$tqb->host}content/plugin/AppCentre/main.php?method=checksilent&rnd='); });</script>";
			$tqb->Config('AppCentre')->lastchecktime=time();
			$tqb->SaveConfig('AppCentre');
		}
	}
}

function AppCentre_AddThemeMenu(){
	global $tqb;
	echo "<script type='text/javascript'>var app_enabledevelop=".(int)$tqb->Config('AppCentre')->enabledevelop.";</script>";
	echo "<script type='text/javascript'>var app_username='".$tqb->Config('AppCentre')->username."';</script>";
	echo "<script src='{$tqb->host}content/plugin/AppCentre/theme.js' type='text/javascript'></script>";
}

function AppCentre_AddPluginMenu(){
	global $tqb;
	echo "<script type='text/javascript'>var app_enabledevelop=".(int)$tqb->Config('AppCentre')->enabledevelop.";</script>";
	echo "<script type='text/javascript'>var app_username='".$tqb->Config('AppCentre')->username."';</script>";
	echo "<script src='{$tqb->host}content/plugin/AppCentre/plugin.js' type='text/javascript'></script>";
}
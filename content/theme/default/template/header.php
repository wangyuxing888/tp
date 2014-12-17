<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$language}" lang="{$language}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-Language" content="{$language}" />
	<title>{$name} - {$title} - Powered by TQBlog</title>
    
    <meta name="keywords" content="{$keywords}" />
	<meta name="description" content="{$description}" />
    <meta name="generator" content="{$tqblogphp}" />
	<meta name="author" content="{$tqauthor}" />
	<meta name="copyright" content="2008-2028 tqtqtq.com" />
	<meta name="MSSmartTagsPreventParsing" content="True" />
	<meta http-equiv="MSThemeCompatible" content="Yes" />

	<link rel="shortcut icon" href="{$host}favicon.ico" />
	<link rel="stylesheet" rev="stylesheet" href="{$host}content/theme/{$theme}/style/{$style}.css" type="text/css" media="all"/>
	<script src="{$host}admin/script/common.js" type="text/javascript"></script>
	<script src="{$host}admin/script/html_util.php" type="text/javascript"></script>
{$header}
{if $type=='index' && $page=='1'}
	<link rel="alternate" type="application/rss+xml" href="{$feedurl}" title="{$name}" />
	<link rel="EditURI" type="application/rsd+xml" title="RSD" href="{$host}admin/xmlrpc/?rsd" />
	<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="{$host}admin/xmlrpc/wlwmanifest.xml" /> 
{else}
	<link rel="alternate" type="application/rss+xml" href="{$feedurl}" title="{$name}" />
{/if}
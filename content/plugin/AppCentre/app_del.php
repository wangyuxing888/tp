<?php 
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: app_del.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

function rrmdir($dir) { 
	if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
			if ($object != '.' && $object != '..') { 
				if (filetype($dir.'/'.$object) == 'dir') rrmdir($dir.'/'.$object); else unlink($dir.'/'.$object); 
			} 
		} 
		reset($objects); 
	} 
} 

rrmdir($tqb->contentdir . $_GET['type'] . '/' . $_GET['id']);

Redirect($_SERVER["HTTP_REFERER"]);
<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: app_upload.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

foreach ($_FILES as $key => $value) {
	if($_FILES[$key]['error']==0){
		if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
			$tmp_name = $_FILES[$key]['tmp_name'];
			$name = $_FILES[$key]['name'];

			$xml=file_get_contents($tmp_name);
			if(App::UnPack($xml)){
				$tqb->SetHint('good','上传APP并解压成功!');
				Redirect($_SERVER["HTTP_REFERER"]);
			};
		}
	}
}

Redirect($_SERVER["HTTP_REFERER"]);
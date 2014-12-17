<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: app_pack.php 33828 2008-02-22 09:25:26Z team $
 */
 
require '../../../admin/function/function_base.php';
require '../../../admin/function/function_admin.php';
require 'function.php';

$tqb->Load();

$action='root';
if (!$tqb->CheckRights($action)) {$tqb->ShowError(6);die();}

if (!$tqb->CheckPlugin('AppCentre')) {$tqb->ShowError(48);die();}

$type=$_GET['type'];

$id=$_GET['id'];

$app=new App;

if($app->LoadInfoByXml($type,$id)==false)die;

ob_clean();

header('Content-Type: application/octet-stream');

header('Content-Disposition:attachment;filename='. $id .'.tqp');

echo $app->Pack();
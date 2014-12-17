<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: qrcode.php 33828 2014-05-01 10:25:30Z team $
 */

require '../function/function_base.php';
$tqb->Load();
ob_clean();

$url = GetVars('url','GET');             // 博客url
$level = 'QR_ECLEVEL_L';             // 纠错级别，值为：QR_ECLEVEL_L,QR_ECLEVEL_M, QR_ECLEVEL_Q,QR_ECLEVEL_H;
$size = 3;                			 // 大小，值为1-10;
	
QRcode::png($url, false, $level, $size);
exit;
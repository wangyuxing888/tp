<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: include.php 33828 2008-02-22 09:25:26Z team $
 */

//注册插件
RegisterPlugin("Security","ActivePlugin_Security");


function ActivePlugin_Security() {

	Add_Filter_Plugin('Filter_Plugin_Admin_CommentAdmin_SubMenu','Security_AddMenu');
	Add_Filter_Plugin('Filter_Plugin_PostComment_Core','Security_Core');
}

function InstallPlugin_Security(){
	global $tqb;

	if($tqb->Config('Security')->BlackWord_List==null){	
		$tqb->Config('Security')->BlackWord_List=urldecode('%28%E6%8E%A8%E5%B9%BF%7C%E7%BE%A4%E5%8F%91%7C%E5%B9%BF%E5%91%8A%7C%E8%A7%A3%E5%AF%86%7C%E8%B5%8C%E5%8D%9A%7C%E5%8C%85%E9%9D%92%E5%A4%A9%7C%E5%B9%BF%E5%91%8A%7C%E9%98%BF%E5%87%A1%E6%8F%90%7C%E5%8F%91%E8%B4%B4%7C%E9%A1%B6%E8%B4%B4%7C%28%E9%92%88%E5%AD%94%7C%E9%9A%90%E5%BD%A2%7C%E9%9A%90%E8%94%BD%E5%BC%8F%29%E6%91%84%E5%83%8F%7C%E5%B9%B2%E6%89%B0%7C%E9%A1%B6%E5%B8%96%7C%E5%8F%91%E5%B8%96%7C%E6%B6%88%E5%A3%B0%7C%E9%81%A5%E6%8E%A7%7C%E8%A7%A3%E7%A0%81%7C%E7%AA%83%E5%90%AC%7C%E8%BA%AB%E4%BB%BD%E8%AF%81%E7%94%9F%E6%88%90%7C%E6%8B%A6%E6%88%AA%7C%E5%A4%8D%E5%88%B6%7C%E7%9B%91%E5%90%AC%7C%E5%AE%9A%E4%BD%8D%7C%E6%B6%88%E5%A3%B0%7C%E4%BD%9C%E5%BC%8A%7C%E6%89%A9%E6%95%A3%7C%E4%BE%A6%E6%8E%A2%7C%E8%BF%BD%E6%9D%80%29%28%E6%9C%BA%7C%E5%99%A8%7C%E8%BD%AF%E4%BB%B6%7C%E8%AE%BE%E5%A4%87%7C%E7%B3%BB%E7%BB%9F%29%7C%28%E6%B1%82%7C%E6%8D%A2%7C%E6%9C%89%E5%81%BF%7C%E4%B9%B0%7C%E5%8D%96%7C%E5%87%BA%E5%94%AE%29%28%E8%82%BE%7C%E5%99%A8%E5%AE%98%7C%E7%9C%BC%E8%A7%92%E8%86%9C%7C%E8%A1%80%29%7C%E8%82%BE%E6%BA%90%7C%28%E5%81%87%7C%E6%AF%95%E4%B8%9A%29%28%E8%AF%81%7C%E6%96%87%E5%87%AD%7C%E5%8F%91%E7%A5%A8%7C%E5%B8%81%29%7C%28%E6%89%8B%E6%A6%B4%7C%E4%BA%BA%7C%E9%BA%BB%E9%86%89%7C%E9%9C%B0%29%E5%BC%B9%7C%E6%B2%BB%E7%96%97%28%E8%82%BF%E7%98%A4%7C%E4%B9%99%E8%82%9D%7C%E6%80%A7%E7%97%85%7C%E7%BA%A2%E6%96%91%E7%8B%BC%E7%96%AE%29%7C%E9%87%8D%E4%BA%9A%E7%A1%92%E9%85%B8%E9%92%A0%7C%28%E7%B2%98%E6%B0%AF%7C%E5%8E%9F%E7%A0%B7%29%E9%85%B8%7C%E9%BA%BB%E9%86%89%E4%B9%99%E9%86%9A%7C%E5%8E%9F%E8%97%9C%E8%8A%A6%E7%A2%B1A%7C%E6%B0%B8%E4%BC%8F%E8%99%AB%7C%E8%9D%87%E6%AF%92%7C%E7%BD%82%E7%B2%9F%7C%E9%93%B6%E6%B0%B0%E5%8C%96%E9%92%BE%7C%E6%B0%AF%E8%83%BA%E9%85%AE%7C%E5%9B%A0%E6%AF%92%28%E7%A1%AB%E7%A3%B7%7C%E7%A3%B7%29%7C%E5%BC%82%E6%B0%B0%E9%85%B8%28%E7%94%B2%E9%85%AF%7C%E8%8B%AF%E9%85%AF%29%7C%E5%BC%82%E7%A1%AB%E6%B0%B0%E9%85%B8%E7%83%AF%E4%B8%99%E9%85%AF%7C%E4%B9%99%E9%85%B0%28%E4%BA%9A%E7%A0%B7%E9%85%B8%E9%93%9C%7C%E6%9B%BF%E7%A1%AB%E8%84%B2%29%7C%E4%B9%99%E7%83%AF%E7%94%B2%E9%86%87%7C%E4%B9%99%E9%85%B8%28%E4%BA%9A%E9%93%8A%7C%E9%93%8A%7C%E4%B8%89%E4%B9%99%E5%9F%BA%E9%94%A1%7C%E4%B8%89%E7%94%B2%E5%9F%BA%E9%94%A1%7C%E7%94%B2%E6%B0%A7%E5%9F%BA%E4%B9%99%E5%9F%BA%E6%B1%9E%7C%E6%B1%9E%29%7C%E4%B9%99%E7%A1%BC%E7%83%B7%7C%E4%B9%99%E9%86%87%E8%85%88%7C%E4%B9%99%E6%92%91%E4%BA%9A%E8%83%BA%7C%E4%B9%99%E6%92%91%E6%B0%AF%E9%86%87%7C%E4%BC%8A%E7%9A%AE%E6%81%A9%7C%E6%B5%B7%E6%B4%9B%E5%9B%A0%7C%E4%B8%80%E6%B0%A7%28%E5%8C%96%E6%B1%9E%7C%E5%8C%96%E4%BA%8C%E6%B0%9F%29%7C%E4%B8%80%E6%B0%AF%28%E4%B9%99%E9%86%9B%7C%E4%B8%99%E9%85%AE%29%7C%E6%B0%A7%E6%B0%AF%E5%8C%96%E7%A3%B7%7C%E6%B0%A7%E5%8C%96%28%E4%BA%9A%E9%93%8A%7C%E9%93%8A%7C%E6%B1%9E%7C%E4%BA%8C%E4%B8%81%E5%9F%BA%E9%94%A1%29%7C%E7%83%9F%E7%A2%B1%7C%E4%BA%9A%E7%A1%9D%E9%85%B0%E4%B9%99%E6%B0%A7%7C%E4%BA%9A%E7%A1%9D%E9%85%B8%E4%B9%99%E9%85%AF%7C%E4%BA%9A%E7%A1%92%E9%85%B8%E6%B0%A2%E9%92%A0%7C%E4%BA%9A%E7%A1%92%E9%85%B8%E9%92%A0%7C%E4%BA%9A%E7%A1%92%E9%85%B8%E9%95%81%7C%E4%BA%9A%E7%A1%92%E9%85%B8%E4%BA%8C%E9%92%A0%7C%E4%BA%9A%E7%A1%92%E9%85%B8%7C%E4%BA%9A%E7%A0%B7%E9%85%B8%28%E9%92%A0%7C%E9%92%BE%7C%E9%85%90%29%7C%E5%86%B0%E6%AF%92%7C%E9%A2%84%E6%B5%8B%E7%AD%94%E6%A1%88%7C%E8%80%83%E5%89%8D%E9%A2%84%E6%B5%8B%7C%E6%8A%BC%E9%A2%98%7C%E4%BB%A3%E5%86%99%E8%AE%BA%E6%96%87%7C%28%E6%8F%90%E4%BE%9B%7C%E5%8F%B8%E8%80%83%7C%E7%BA%A7%7C%E4%BC%A0%E9%80%81%7C%E8%80%83%E4%B8%AD%7C%E7%9F%AD%E4%BF%A1%29%E7%AD%94%E6%A1%88%7C%28%E5%BE%85%7C%E4%BB%A3%7C%E5%B8%A6%7C%E6%9B%BF%7C%E5%8A%A9%29%E8%80%83%7C%28%E5%8C%85%7C%E9%A1%BA%E5%88%A9%7C%E4%BF%9D%29%E8%BF%87%7C%E8%80%83%E5%90%8E%E4%BB%98%E6%AC%BE%7C%E6%97%A0%E7%BA%BF%E8%80%B3%E6%9C%BA%7C%E8%80%83%E8%AF%95%E4%BD%9C%E5%BC%8A%7C%E8%80%83%E5%89%8D%E5%AF%86%E5%8D%B7%7C%E6%BC%8F%E9%A2%98%7C%E4%B8%AD%E7%89%B9%7C%E4%B8%80%E8%82%96%7C%E6%8A%A5%E7%A0%81%7C%28%E5%90%88%7C%E9%A6%99%E6%B8%AF%29%E5%BD%A9%7C%E5%BD%A9%E5%AE%9D%7C3D%E8%BD%AE%E7%9B%98%7Cliuhecai%7C%E4%B8%80%E7%A0%81%7C%28%E7%9A%87%E5%AE%B6%7C%E4%BF%84%E7%BD%97%E6%96%AF%29%E8%BD%AE%E7%9B%98%7C%E8%B5%8C%E5%85%B7%7C%E7%89%B9%E7%A0%81%7C%E7%9B%97%28%E5%8F%B7%7Cqq%7C%E5%AF%86%E7%A0%81%29%7C%E7%9B%97%E5%8F%96%28%E5%AF%86%E7%A0%81%7Cqq%29%7C%E5%97%91%E8%8D%AF%7C%E5%B8%AE%E6%8B%9B%E4%BA%BA%7C%E7%A4%BE%E4%BC%9A%E6%B7%B7%7C%E6%8B%9C%E5%A4%A7%E5%93%A5%7C%E7%94%B5%E8%AD%A6%E6%A3%92%7C%E5%B8%AE%E4%BA%BA%E6%80%80%E5%AD%95%7C%E5%BE%81%E5%85%B5%E8%AE%A1%E5%88%92%7C%E5%88%87%E8%85%B9%7CVE%E8%A7%86%E8%A7%89%7C%E7%94%B5%E9%B8%A1%7C%E4%BB%BF%E7%9C%9F%E6%89%8B%E6%9E%AA%7C%E5%81%9A%E7%82%B8%E5%BC%B9%7CONS%7C%E8%B5%B0%E7%A7%81%7C%E9%99%AA%E8%81%8A%7Ch%28%E5%9B%BE%7C%E6%BC%AB%7C%E7%BD%91%29%7C%E5%BC%80%E8%8B%9E%7C%E6%89%BE%28%E7%94%B7%7C%E5%A5%B3%29%7C%E5%8F%A3%E6%B7%AB%7C%E5%8D%96%E8%BA%AB%7C%E5%85%83%E4%B8%80%E5%A4%9C%7C%28%E7%94%B7%7C%E5%A5%B3%29%E5%A5%B4%7C%E5%8F%8C%28%E7%AD%92%7C%E6%A1%B6%29%7C%E7%9C%8BJJ%7C%E5%81%9A%E5%8F%B0%7C%E5%8E%95%E5%A5%B4%7C%E9%AA%9A%E5%A5%B3%7C%E5%AB%A9%E9%80%BC%7C%E4%B8%80%E5%A4%9C%E6%BF%80%E6%83%85%7C%E4%B9%B1%E4%BC%A6%7C%E6%B3%A1%E5%8F%8B%7C%E5%AF%8C%28%E5%A7%90%7C%E5%A9%86%29%7C%28%E8%B6%B3%7C%E7%BE%A4%7C%E8%8C%B9%29%E4%BA%A4%7C%E9%98%B4%E6%88%B7%7C%E6%80%A7%28%E6%9C%8D%E5%8A%A1%7C%E4%BC%B4%E4%BE%A3%7C%E4%BC%99%E4%BC%B4%7C%E4%BA%A4%29%7C%E6%9C%89%E5%81%BF%28%E6%8D%90%E7%8C%AE%7C%E6%9C%8D%E5%8A%A1%29%7C%28%E6%9C%89%7C%E6%97%A0%29%E7%A0%81%7C%E5%8C%85%E5%85%BB%7C%28%E7%8A%AC%7C%E5%85%BD%7C%E5%B9%BC%29%E4%BA%A4%7C%E6%A0%B9%E6%B5%B4%7C%E6%8F%B4%E4%BA%A4%7C%E5%B0%8F%E5%8F%A3%E5%BE%84%7C%E6%80%A7%28%E8%99%90%7C%E7%88%B1%7C%E6%81%AF%29%7C%E5%88%BB%E7%AB%A0%7C%E6%91%87%E5%A4%B4%E4%B8%B8%7C%E7%9B%91%E5%90%AC%E7%8E%8B%7C%E6%98%8F%E8%8D%AF%7C%E4%BE%A6%E6%8E%A2%E8%AE%BE%E5%A4%87%7C%E6%80%A7%E5%A5%B4%7C%E9%80%8F%E8%A7%86%E7%9C%BC%28%E7%9D%9B%7C%E9%95%9C%29%7C%E6%8B%8D%E8%82%A9%E7%A5%9E%7C%28%E5%A4%B1%E5%BF%86%7C%E5%82%AC%E6%83%85%7C%E8%BF%B7%28%E5%B9%BB%7C%E6%98%8F%7C%E5%A5%B8%29%3F%7C%E5%AE%89%E5%AE%9A%29%28%E8%8D%AF%7C%E7%89%87%7C%E9%A6%99%29%7C%E6%B8%B8%E6%88%8F%E6%9C%BA%E7%A0%B4%E8%A7%A3%7C%E9%9A%90%E5%BD%A2%E8%80%B3%E6%9C%BA%7C%E9%93%B6%E8%A1%8C%E5%8D%A1%E5%A4%8D%E5%88%B6%E8%AE%BE%E5%A4%87%7C%E4%B8%80%E5%8D%A1%E5%A4%9A%E5%8F%B7%7C%E4%BF%A1%E7%94%A8%E5%8D%A1%E5%A5%97%E7%8E%B0%7C%E6%B6%88%E9%98%B2%5B%E7%81%AD%E7%81%AB%5D%3F%E6%9E%AA%7C%E9%A6%99%E6%B8%AF%E7%94%9F%E5%AD%90%7C%E5%9C%9F%E7%82%AE%7C%E8%83%8E%E7%9B%98%7C%E6%89%8B%E6%9C%BA%E9%AD%94%E5%8D%A1%7C%E5%AE%B9%E5%BC%B9%E9%87%8F%7C%E6%9E%AA%E6%A8%A1%7C%E9%93%85%E5%BC%B9%7C%E6%B1%BD%28%E6%9E%AA%7C%E7%8B%97%7C%E8%B5%B0%E8%A1%A8%E5%99%A8%29%7C%E6%B0%94%E6%9E%AA%7C%E6%B0%94%E7%8B%97%7C%E4%BC%9F%E5%93%A5%7C%E7%BA%BD%E6%89%A3%E6%91%84%E5%83%8F%E6%9C%BA%7C%E5%85%8D%E7%94%B5%E7%81%AF%7C%E5%8D%96QQ%E5%8F%B7%E7%A0%81%7C%E9%BA%BB%E9%86%89%E8%8D%AF%7C%E5%BA%B7%E7%94%9F%E4%B8%B9%7C%E8%AD%A6%E5%BE%BD%7C%E8%AE%B0%E5%8F%B7%E6%89%91%E5%85%8B%7C%E6%BF%80%E5%85%89%28%E6%B1%BD%7C%E6%B0%94%29%7C%E7%BA%A2%E5%BA%8A%7C%E7%8B%97%E5%8F%8B%7C%E5%8F%8D%E9%9B%B7%E8%BE%BE%E6%B5%8B%E9%80%9F%7C%E7%9F%AD%E4%BF%A1%E6%8A%95%E7%A5%A8%E4%B8%9A%E5%8A%A1%7C%E7%94%B5%E5%AD%90%E7%8B%97%E5%AF%BC%E8%88%AA%E6%89%8B%E6%9C%BA%7C%E5%BC%B9%28%E7%A7%8D%7C%E5%A4%B9%29%7C%28%E8%BF%BD%7C%E8%AE%A8%29%E5%80%BA%7C%E8%BD%A6%E7%94%A8%E7%94%B5%E5%AD%90%E7%8B%97%7C%E9%81%BF%E5%AD%95%7C%E5%8A%9E%E7%90%86%28%E8%AF%81%E4%BB%B6%7C%E6%96%87%E5%87%AD%29%7C%E6%96%91%E8%9D%A5%7C%E6%9A%97%E8%AE%BF%E5%8C%85%7CSIM%E5%8D%A1%E5%A4%8D%E5%88%B6%E5%99%A8%7CBB%28%E6%9E%AA%7C%E5%BC%B9%29%7C%E9%9B%B7%E7%AE%A1%7C%E5%BC%93%E5%BC%A9%7C%28%E7%94%B5%7C%E9%95%BF%29%E7%8B%97%7C%E5%AF%BC%E7%88%86%E7%B4%A2%7C%E7%88%86%E7%82%B8%E7%89%A9%7C%E7%88%86%E7%A0%B4%7C%E5%B7%A6%E6%A3%8D%7C%E5%A9%8A%E5%AD%90%7C%E6%8D%A2%E5%A6%BB%7C%E6%88%90%E4%BA%BA%E7%89%87%7C%E6%B7%AB%28%E9%9D%A1%7C%E6%B0%B4%7C%E5%85%BD%29%7C%E9%98%B4%28%E6%AF%9B%7C%E8%92%82%7C%E9%81%93%7C%E5%94%87%29%7C%E5%B0%8F%E7%A9%B4%7C%E7%BC%A9%E9%98%B4%7C%E5%B0%91%E5%A6%87%E8%87%AA%E6%8B%8D%7C%28%E4%B8%89%E7%BA%A7%7C%E8%89%B2%E6%83%85%7C%E6%BF%80%E6%83%85%7C%E9%BB%84%E8%89%B2%7C%E5%B0%8F%29%28%E7%89%87%7C%E7%94%B5%E5%BD%B1%7C%E8%A7%86%E9%A2%91%7C%E4%BA%A4%E5%8F%8B%7C%E7%94%B5%E8%AF%9D%29%7C%E8%82%89%E6%A3%92%7C%28%E6%83%85%7C%E5%A5%B8%29%E6%9D%80%7C%E8%A3%B8%E7%85%A7%7C%E4%B9%B1%E4%BC%A6%7C%E5%8F%A3%E4%BA%A4%7C%E7%A6%81%28%E7%BD%91%7C%E7%89%87%29%7C%E6%98%A5%E5%AE%AB%E5%9B%BE%7CSM%E7%94%A8%E5%93%81%7C%E8%87%AA%E5%8A%A8%E7%BE%A4%E5%8F%91%7C%E7%A7%81%E5%AE%B6%E4%BE%A6%E6%8E%A2%E6%9C%8D%E5%8A%A1%7C%E7%94%9F%E6%84%8F%E5%AE%9D%7C%E5%95%86%E5%8A%A1%28%E5%BF%AB%E8%BD%A6%7C%E7%9F%AD%E4%BF%A1%29%7C%E6%85%A7%E8%81%AA%7C%E4%BE%9B%E5%BA%94%E5%8F%91%E7%A5%A8%7C%E5%8F%91%E7%A5%A8%E4%BB%A3%E5%BC%80%7C%E7%9F%AD%E4%BF%A1%E7%BE%A4%E5%8F%91%7C%E7%9F%AD%E4%BF%A1%E7%8C%AB%7C%E7%82%B9%E9%87%91%E5%95%86%E5%8A%A1%7C%E5%A3%AB%E7%9A%84%E5%AE%81%7C%E5%A3%AB%E7%9A%84%E5%B9%B4%7C%E5%85%AD%E5%90%88%E9%87%87%7C%E4%B9%90%E9%80%8F%E7%A0%81%7C%E5%BD%A9%E7%A5%A8%7C%E7%99%BE%E4%B9%90%E4%BA%8C%E5%91%93%7C%E7%99%BE%E5%AE%B6%E4%B9%90%7C%E9%BB%84%E9%A1%B5%7C%E5%87%BA%E7%A7%9F%7C%E6%B1%82%E8%B4%AD%7C%E7%95%99%E5%AD%A6%E5%92%A8%E8%AF%A2%7C%E5%A4%96%E6%8C%82%7C%E6%B7%98%E5%AE%9D%7C%E7%BE%A4%E5%8F%91%7C%E8%B4%A7%E5%88%B0%E4%BB%98%E6%AC%BE%7C%E6%B1%BD%E8%BD%A6%E9%85%8D%E4%BB%B6%7C%E6%8E%A8%E5%B9%BF%E8%81%94%E7%9B%9F%7C%E5%8A%B3%E5%8A%A1%E6%B4%BE%E9%81%A3%7C%E7%BD%91%E7%BB%9C%28%E5%85%BC%E8%81%8C%7C%E8%B5%9A%E9%92%B1%29%7C%28%E8%AF%81%E4%BB%B6%7C%E5%A9%9A%E5%BA%86%7C%E7%BF%BB%E8%AF%91%7C%E6%90%AC%E5%AE%B6%7C%E8%BF%BD%E5%80%BA%7C%E5%80%BA%E5%8A%A1%29%E5%85%AC%E5%8F%B8%7C%E6%89%8B%E6%9C%BA%28%E6%B8%B8%E6%88%8F%7C%E7%AA%83%E5%90%AC%7C%E7%9B%91%E5%90%AC%7C%E9%93%83%E5%A3%B0%7C%E5%9B%BE%E7%89%87%29%7C%E4%B8%89%E5%94%91%E4%BB%91%7C%E5%A5%87%E8%BF%B9%E4%B8%96%E7%95%8C%7C%E5%B7%A5%E4%BD%9C%E6%9C%8D%7Cwow%7C%E8%AE%BA%E6%96%87%7C%E9%93%83%E5%A3%B0%7C%E5%BD%A9%28%E4%BF%A1%7C%E9%93%83%7C%E7%A5%A8%29%7C%E6%98%BE%E7%A4%BA%E5%B1%8F%7C%E6%8A%95%E5%BD%B1%E4%BB%AA%7C%E8%99%9A%E6%8B%9F%E4%B8%BB%E6%9C%BA%7C%28%E5%9F%9F%E5%90%8D%7C%E4%B8%93%E4%B8%9A%29%E6%B3%A8%E5%86%8C%7C%E8%90%A5%E9%94%80%7C%E6%9C%8D%E5%8A%A1%E5%99%A8%E6%89%98%E7%AE%A1%7C%E7%BD%91%E7%AB%99%E5%BB%BA%E8%AE%BE%7C%28google%7C%E7%99%BE%E5%BA%A6%29%E6%8E%92%E5%90%8D%7C%E6%95%B0%E6%8D%AE%E6%81%A2%E5%A4%8D%7C%E5%8C%BB%E9%99%A2%7C%E6%80%A7%E7%97%85%7C%E4%B8%8D%E5%AD%95%E4%B8%8D%E8%82%B2%7C%E4%B9%B3%E8%85%BA%E7%97%85%7C%E5%B0%96%E9%94%90%E6%B9%BF%E7%96%A3%7C%E7%9A%AE%E8%82%A4%E7%97%85%7C%E5%87%8F%E8%82%A5%7C%E7%98%A6%7C3P%7C%E4%BA%BA%E5%85%BD%7Csex%7C%E4%BB%A3%E5%AD%95%7C%E6%89%93%E7%82%AE%7C%E6%89%BE%E5%B0%8F%E5%A7%90%7C%E5%88%BB%E7%AB%A0%7C%E4%B9%B1%E4%BC%A6%7C%E4%B8%AD%E5%87%BA%7C%E6%A5%BC%E5%87%A4%7C%E5%8D%96%E6%B7%AB%7C%E8%8D%A1%E5%A6%87%7C%E7%BE%A4%E4%BA%A4%7C%E5%B9%BC%E5%A5%B3%7C18%E7%A6%81%7C%E4%BC%A6%E7%90%86%E7%94%B5%E5%BD%B1%7C%28%E5%82%AC%E6%83%85%7C%E8%92%99%E6%B1%97%7C%E8%92%99%E6%B1%89%7C%E6%98%A5%29%E8%8D%AF%7C%E6%83%85%E8%B6%A3%E7%94%A8%E5%93%81%7C%E6%88%90%E4%BA%BA.%2B%3F%28%E7%94%B5%E5%BD%B1%7C%E7%94%A8%E5%93%81%29%7C%E6%BF%80%E6%83%85%28%E8%A7%86%E9%A2%91%7C%E7%94%B5%E5%BD%B1%7C%E5%BD%B1%E9%99%A2%29%7C%E7%88%BD%E7%89%87%7C%E6%80%A7%E6%84%9F%E7%BE%8E%E5%A5%B3%7C%E4%BA%A4%E5%8F%8B%7C%E6%80%80%E5%AD%95%7C%E8%A3%B8%E8%81%8A%7C%E5%88%B6%E6%9C%8D%E8%AF%B1%E6%83%91%7C%E4%B8%9D%E8%A2%9C%7C%E9%95%BF%E8%85%BF%7C%E5%AF%82%E5%AF%9E%E5%A5%B3%E5%AD%90%7C%E5%85%8D%E8%B4%B9%E7%94%B5%E5%BD%B1%7C%E5%8F%8C%E8%89%B2%E7%90%83%7C%E7%A6%8F%E5%BD%A9%7C%E4%BD%93%E5%BD%A9%7C6%E5%90%88%E5%BD%A9%7C%E6%97%B6%E6%97%B6%E5%BD%A9%7C%E5%8F%8C%E8%89%B2%E7%90%83%7C%E5%92%A8%E8%AF%A2%E7%83%AD%E7%BA%BF%7C%E8%82%A1%E7%A5%A8%7C%E8%8D%90%E8%82%A1%7C%E5%BC%80%E8%82%A1%7C%E7%A7%81%E6%9C%8D%7CSF%7C%E6%9E%AA%7C%E8%AD%A6%E6%A3%92%7C%E8%AD%A6%E6%9C%8D%7C%E9%BA%BB%E9%86%89%7C%E8%AF%9A%E6%8B%9B%E5%8A%A0%E7%9B%9F%7C%E8%AF%9A%E4%BF%A1%E7%BB%8F%E8%90%A5%7C%E6%9D%80%E6%89%8B%7C%28%E6%B8%B8%E6%88%8F%7C%E9%87%91%29%E5%B8%81%7C%E7%BE%A4%E5%8F%91%7C%E6%B3%A8%E5%86%8C.%2B%3F%E5%85%AC%E5%8F%B8%7C%E5%85%AC%E5%8F%B8%E6%B3%A8%E5%86%8C%7C%E5%8F%91%E7%A5%A8%7C%E4%BB%A3%E5%BC%80%7C%E6%B7%98%E5%AE%9D%7C%E8%BF%94%E5%88%A9%7C%E5%9B%A2%E8%B4%AD%7C%E5%9F%B9%E8%AE%AD%7C%E6%8A%98%E6%89%A3%7C%28%E6%89%93%E5%8C%85%7C%E8%AF%95%E9%AA%8C%7C%E6%89%93%E6%A0%87%7C%E7%A0%B4%E7%A2%8E%7C%E7%81%8C%E8%A3%85%7C%E5%8D%87%E9%99%8D%29%E6%9C%BA%7C%E6%9D%A1%E7%A0%81%7C%E6%A0%87%E7%AD%BE%E7%BA%B8%7C%E5%8D%87%E9%99%8D%E5%B9%B3%E5%8F%B0%7C%E5%9C%B0%E6%BA%90%E7%83%AD%E6%B3%B5%7C%E9%A3%8E%E6%9C%BA%E7%9B%98%E7%AE%A1%7C%E4%BA%8C%E6%89%8B%28%E8%BD%A6%7C%E7%94%B5%E8%84%91%29');
	}
	$tqb->Config('Security')->Op_BlackWord_Audit=1;
	$tqb->Config('Security')->Op_BlackWord_Throw=2;
	$tqb->Config('Security')->Op_Chinese_None=1;
	$tqb->SaveConfig('Security');
}


function Security_AddMenu(){
	global $tqb;
	echo '<a href="'. $tqb->host .'content/plugin/Security/main.php"><span class="m-right">Security设置</span></a>';

}


function Security_Core_BlackWord(&$cmt){
	global $tqb;
	
	$BlackWord_List=trim($tqb->Config('Security')->BlackWord_List);
	$BlackWord_Audit=(int)$tqb->Config('Security')->Op_BlackWord_Audit;
	$BlackWord_Throw=(int)$tqb->Config('Security')->Op_BlackWord_Throw;

	if(!$BlackWord_List)return null;

	$array=array();
	preg_match_all('/'.$BlackWord_List.'/ui',$cmt->Content,$array);
	
	$array=array_unique($array);
	$i=count($array[0]);

	if($i>=$BlackWord_Audit) $cmt->IsChecking=true;
	if($i>=$BlackWord_Throw) $cmt->IsThrow=true;

}

function Security_Core_NoneChinese(&$cmt){
	global $tqb;

	$Chinese_None=(bool)$tqb->Config('Security')->Op_Chinese_None;

	if($Chinese_None){
		if(preg_match('/[\x{4e00}-\x{9fa5}]+/u',$cmt->Name . $cmt->Content)==0){
			$cmt->IsChecking=true;
		}
	}

}


function Security_Core(&$cmt){
	global $tqb;

	Security_Core_NoneChinese($cmt);
	Security_Core_BlackWord($cmt);	

}


?>
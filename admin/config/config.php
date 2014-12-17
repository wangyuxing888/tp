<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: config.php 33828 2008-02-22 09:25:26Z team $
 */

/**
 * 返回配置
 * @param
 * @return array
 */
return array(
	//--------------- 数据库配置 ---------------
	//mysql||sqlite||sqlite3||pdo_mysql
	'CFG_DATABASE_TYPE'=> '',
	'CFG_SQLITE_NAME' => '',
	'CFG_SQLITE_PRE' => 'tqb_',
	'CFG_MYSQL_SERVER' => 'localhost',
	'CFG_MYSQL_USERNAME' => 'root',
	'CFG_MYSQL_PASSWORD' => '',
	'CFG_MYSQL_NAME' => '',
	'CFG_MYSQL_CHARSET' => 'utf8',
	'CFG_MYSQL_PRE' => 'tqb_',
	'CFG_MYSQL_ENGINE'=>'MyISAM',
    'CFG_MYSQL_PORT' => '3306',
    'CFG_MYSQL_PERSISTENT' => false,
	
	//--------------- 基本设置 ---------------
	'CFG_BLOG_HOST' => 'http://localhost/',
	'CFG_BLOG_NAME' => 'TQBlog',
	'CFG_BLOG_SUBNAME' => 'Welcome to TQBlog!',
	'CFG_BLOG_KEYWORDS' => 'TQBlog,开源博客系统,PHP,MySQL,OpenSource,Blog,CMS系统',
	'CFG_BLOG_DESCRIPTION' => 'TQBlog 开源博客系统，是一个采用 PHP 和 MySQL SQLite 等其他多种数据库构建的性能优异、功能实用、体积小速度快、安全稳定的个人博客系统。',
	'CFG_BLOG_EMAIL' => '',
	'CFG_BLOG_QQ' => '',
	'CFG_BLOG_AUTHOR' => 'TQBlog Team',
	'CFG_BLOG_COPYRIGHT' => 'Copyright © 2008-2028 tqtqtq.com All Rights Reserved',
	'CFG_BLOG_LANGUAGE' => 'zh-CN',
	'CFG_BLOG_LANGUAGEPACK' => 'lang_SimpChinese',
	'CFG_BLOG_THEME' => 'default',
	'CFG_BLOG_CSS' => 'default',
	//关闭站点
	'CFG_SITE_TURNOFF'=>false,

	//--------------- 全局设置 ---------------
	'CFG_YUN_SITE'=>'',
	'CFG_DEBUG_MODE' => true,
	'CFG_BLOG_CLSID' => '',
	//时区设置
	'CFG_TIME_ZONE_NAME' => 'Asia/Shanghai',
	
	'CFG_UPDATE_INFO_URL' => 'http://update.tqtqtq.com/update.php?mod=info',
	//固定域名,默认为false,如启用则'CFG_BLOG_HOST生效而'CFG_MULTI_DOMAIN_SUPPORT无效
	'CFG_PERMANENT_DOMAIN_ENABLE' => false,
	'CFG_MULTI_DOMAIN_SUPPORT' => false,

	//当前TQBlog版本
	'CFG_BLOG_PRODUCT' => 'TQBlog',
	'CFG_BLOG_VERSION' => '',
	'CFG_BLOG_PRODUCT_FULL' => '',
	'CFG_BLOG_PRODUCT_FULLHTML' => '',
	
	//用户名,密码,评论长度等限制
	'CFG_USERNAME_MIN' => 3,
	'CFG_USERNAME_MAX' => 50,
	'CFG_PASSWORD_MIN' => 6,
	'CFG_PASSWORD_MAX' => 32,
	'CFG_QQ_MAX' => 16,
	'CFG_EMAIL_MAX' => 50,
	'CFG_HOMEPAGE_MAX' => 100,
	'CFG_CONTENT_MAX' => 1000,

	//留言评论
	'CFG_COMMENT_TURNOFF' => false,
	'CFG_COMMENT_CHECK' => false,
	'CFG_COMMENT_VERIFY_ENABLE' => false,
	'CFG_COMMENT_REVERSE_ORDER' => false,
	//赞间隔时间，单位秒，值86400为24小时。
	'CFG_COOKIE_GOOD' => 86400,
	
	//侧栏评论最大字数
	'CFG_COMMENT_EXCERPT_MAX' => 20,
	
	//自动摘要字数
	'CFG_ARTICLE_EXCERPT_MAX' => 250,

	//验证码
	'CFG_VERIFYCODE_STRING' => 'ABCDEFGHKMNPRSTUVWXYZ123456789',
	'CFG_VERIFYCODE_WIDTH' => 100,
	'CFG_VERIFYCODE_HEIGHT' => 30,

	//页面各项列数
	'CFG_DISPLAY_COUNT' => 10,
	'CFG_SEARCH_COUNT' => 25,
	'CFG_PAGEBAR_COUNT' => 10,
	'CFG_COMMENTS_DISPLAY_COUNT' => 100,
	'CFG_DISPLAY_SUBCATEGORYS' => false,
	'CFG_DISPLAY_QRCODE' => false,

	//表情相关
	'CFG_EMOTICONS_FILENAME' => 'default',
	'CFG_EMOTICONS_FILETYPE' => 'png|gif|jpg',
	'CFG_EMOTICONS_FILESIZE' => '24',

	//上传相关
	'CFG_UPLOAD_FILETYPE' => 'jpg|gif|png|jpeg|bmp|psd|wmf|ico|rpm|deb|tar|gz|sit|7z|bz2|zip|rar|xml|xsl|svg|svgz|doc|docx|ppt|pptx|xls|xlsx|wps|chm|txt|pdf|mp3|avi|mpg|rm|ra|rmvb|mov|wmv|wma|swf|fla|torrent|apk|tqp',
	'CFG_UPLOAD_FILESIZE' => 2,
	
	//杂项
	'CFG_XMLRSS2_COUNT' => 10,
	'CFG_RSS_EXPORT_WHOLE' => true,
	
	//后台管理
	'CFG_MANAGE_COUNT' => 50,

	//--------------- 静态化配置 ---------------
	//文章,页面类,列表页的静态模式ACTIVE or REWRITE
	'CFG_STATIC_MODE' => 'ACTIVE',
	'CFG_ARTICLE_REGEX' => '{%host%}?id={%id%}',
	'CFG_PAGE_REGEX' => '{%host%}?id={%id%}',
	'CFG_CATEGORY_REGEX' => '{%host%}?cate={%id%}&page={%page%}',
	'CFG_AUTHOR_REGEX' => '{%host%}?auth={%id%}&page={%page%}',
	'CFG_TAGS_REGEX' => '{%host%}?tags={%id%}&page={%page%}',
	'CFG_DATE_REGEX' => '{%host%}?date={%date%}&page={%page%}',
	'CFG_INDEX_REGEX' => '{%host%}?page={%page%}',

	//首页，分类页，文章页，页面页的默认模板
	'CFG_INDEX_DEFAULT_TEMPLATE' => 'index',
	'CFG_POST_DEFAULT_TEMPLATE' => 'single',
	'CFG_SIDEBAR_ORDER' => 'searchpanel|calendar|catalog|comments|archives|favorite|link|misc',
	'CFG_SIDEBAR2_ORDER' => '',
	'CFG_SIDEBAR3_ORDER' => '',
	'CFG_SIDEBAR4_ORDER' => '',
	'CFG_SIDEBAR5_ORDER' => '',
	
	//--------------- 插件 ---------------
	'CFG_USING_PLUGIN_LIST' => '',
	
	//--------------- 其它 ---------------
	'CFG_GZIP_ENABLE'=>false,
	'CFG_ADMIN_HTML5_ENABLE'=>true,
	//代码高亮
	'CFG_SYNTAXHIGHLIGHTER_ENABLE' => true,

	//源码编辑高亮
	'CFG_CODEMIRROR_ENABLE' => true,
	'CFG_HTTP_LASTMODIFIED' => false,
	'CFG_MODULE_CATALOG_STYLE'=>0,
	'CFG_VIEWNUMS_TURNOFF' => false,
	'CFG_LISTONTOP_TURNOFF' => false,
	'CFG_RELATEDLIST_COUNT'=>10,
)
?>
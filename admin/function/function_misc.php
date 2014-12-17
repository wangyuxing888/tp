<?php
/**
 * [TQBlog] (C)2008-2028 tqtqtq.com
 * @author TQBlog Team
 * This is NOT a freeware, use is subject to license terms
 * $Id: function_misc.php 33828 2014-04-21 15:20:16Z team $
 */

ob_clean();

switch (GetVars('type', 'GET')) {
	case 'statistic':
		if (!$tqb->CheckRights('root')) {
			echo $tqb->ShowError(6, __FILE__, __LINE__);
			die();
		}
		misc_statistic();
		break;
	case 'updateinfo':
		if (!$tqb->CheckRights('root')) {
			echo $tqb->ShowError(6, __FILE__, __LINE__);
			die();
		}
		misc_updateinfo();
		break;
	case 'showtags':
		if (!$tqb->CheckRights('ArticleEdt')) {
			Http404();
			die();
		}
		misc_showtags();
		break;
	case 'vrs':
		if (!$tqb->CheckRights('misc')) {
			$tqb->ShowError(6, __FILE__, __LINE__);
		}
		misc_viewrights();
		break;
	default:
		break;
}

function misc_updateinfo() {

	global $tqb;

	$r = GetHttpContent($tqb->option['CFG_UPDATE_INFO_URL']);

	$r = '<tr><td>' . $r . '</td></tr>';

	$tqb->LoadConfigs();
	$tqb->LoadCache();
	$tqb->cache->reload_updateinfo = $r;
	$tqb->cache->reload_updateinfo_time = time();
	$tqb->SaveCache();

	echo $r;
}

function misc_statistic() {

	global $tqb;

	$r = null;

	$tqb->BuildTemplate();

	$xmlrpc_address = $tqb->host . 'admin/xmlrpc/';
	$current_member = $tqb->user->Name;
	$current_version = $tqb->option['CFG_BLOG_VERSION'];
	$all_artiles = GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(*) AS num FROM ' . $GLOBALS['table']['Post'] . ' WHERE log_Type=0'), 'num');
	$all_pages = GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(*) AS num FROM ' . $GLOBALS['table']['Post'] . ' WHERE log_Type=1'), 'num');
	$all_categorys = GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(*) AS num FROM ' . $GLOBALS['table']['Category']), 'num');
	$all_comments = GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(*) AS num FROM ' . $GLOBALS['table']['Comment']), 'num');
	$all_views = $tqb->option['CFG_VIEWNUMS_TURNOFF']==true?0:GetValueInArrayByCurrent($tqb->db->Query('SELECT SUM(log_ViewNums) AS num FROM ' . $GLOBALS['table']['Post']), 'num');
	$all_tags = GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(*) as num FROM ' . $GLOBALS['table']['Tag']), 'num');
	$all_members = GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(*) AS num FROM ' . $GLOBALS['table']['Member']), 'num');
	$current_theme = $tqb->theme;
	$current_style = $tqb->style;
	$current_member = '{$tqb->user->Name}';
	$n=GetValueInArrayByCurrent($tqb->db->Query('SELECT COUNT(comm_ID) AS num FROM ' . $GLOBALS['table']['Comment'] . ' WHERE comm_Ischecking=1'),'num');

	$system_environment = PHP_OS . ';' . GetValueInArray(explode('/', GetVars('SERVER_SOFTWARE', 'SERVER')), 0) . ';' . 'PHP ' . phpversion() . ';' . $tqb->option['CFG_DATABASE_TYPE'];

	$r .= "<tr><td class='td20'>{$tqb->lang['msg']['current_member']}</td><td class='td30'>{$current_member}</td><td class='td20'>{$tqb->lang['msg']['current_version']}</td><td class='td30'>{$current_version}</td></tr>";
	$r .= "<tr><td class='td20'>{$tqb->lang['msg']['all_artiles']}</td><td>{$all_artiles}</td><td>{$tqb->lang['msg']['all_categorys']}</td><td>{$all_categorys}</td></tr>";
	$r .= "<tr><td class='td20'>{$tqb->lang['msg']['all_pages']}</td><td>{$all_pages}</td><td>{$tqb->lang['msg']['all_tags']}</td><td>{$all_tags}</td></tr>";
	$r .= "<tr><td class='td20'>{$tqb->lang['msg']['all_comments']}</td><td>{$all_comments} (<a href='../admin.php?act=CommentAdmin&amp;ischecking=1'>{$tqb->lang['msg']['check_comment']}：{$n}</a>)</td><td>{$tqb->lang['msg']['all_views']}</td><td>{$all_views}</td></tr>";
	$r .= "<tr><td class='td20'>{$tqb->lang['msg']['current_theme']}/{$tqb->lang['msg']['current_style']}</td><td>{$current_theme}/{$current_style}</td><td>{$tqb->lang['msg']['all_members']}</td><td>{$all_members}</td></tr>";
	$r .= "<tr><td class='td20'>{$tqb->lang['msg']['xmlrpc_address']}</td><td>{$xmlrpc_address}</td><td>{$tqb->lang['msg']['system_environment']}</td><td>{$system_environment}</td></tr>";


	$tqb->LoadConfigs();
	$tqb->LoadCache();
	$tqb->cache->reload_statistic = $r;
	$tqb->cache->reload_statistic_time = time();
	//$tqb->SaveCache();
	CountNormalArticleNums();

	$tqb->AddBuildModule('statistics', array($all_artiles, $all_pages, $all_categorys, $all_tags, $all_views, $all_comments));
	$tqb->BuildModule();

	$r = str_replace('{$tqb->user->Name}', $tqb->user->Name, $r);

	echo $r;
}


function misc_showtags() {
	global $tqb;

	header('Content-Type: application/x-javascript; Charset=utf-8');

	echo '$("#ajaxtags").html("';

	$array = $tqb->GetTagList(null, null, array('tag_Count' => 'DESC', 'tag_ID' => 'ASC'), array(100), null);
	if (count($array) > 0) {
		$t = array();
		foreach ($array as $tag) {
			echo '<a href=\"#\">' . $tag->Name . '</a>';
		}
	}

	echo '");$("#ulTag").tagTo("#edtTag");';
}


function misc_viewrights(){
global $tqb;

$blogtitle = $tqb->name . '-' . $tqb->lang['msg']['view_rights'];
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php if (strpos(GetVars('HTTP_USER_AGENT', 'SERVERS'), 'MSIE')) { ?>
		<meta http-equiv="X-UA-Compatible" content="IE=EDGE"/>
	<?php } ?>
	<meta name="robots" content="none"/>
	<meta name="generator" content="<?php echo $GLOBALS['option']['CFG_BLOG_PRODUCT_FULL'] ?>"/>
	<link rel="stylesheet" href="css/module.css" type="text/css" media="screen"/>
	<title><?php echo $blogtitle; ?></title>
</head>
<body class="short">
<div class="bg">
	<div id="wrapper">
		<div class="logo"><img src="image/admin/none.gif" title="TQBlog" alt="TQBlog"/></div>
		<div class="login">
			<form method="post" action="#">
				<dl>
					<dt><?php echo $tqb->lang['msg']['current_member'] . ' : <b>' . $tqb->user->Name; ?></b><br/>
						<?php echo $tqb->lang['msg']['member_level'] . ' : <b>' . $tqb->user->LevelName; ?></b></dt>
					<?php

					foreach ($GLOBALS['actions'] as $key => $value) {
						if ($GLOBALS['tqb']->CheckRights($key)) {
							echo '<dd><b>' . $key . '</b> : ' . ($GLOBALS['tqb']->CheckRights($key) ? '<span style="color:green">true</span>' : '<span style="color:red">false</span>') . '</dd>';
						}
					}

					?>
				</dl>
			</form>
		</div>
	</div>
</div>
</body>
</html>
<?php
}

?>
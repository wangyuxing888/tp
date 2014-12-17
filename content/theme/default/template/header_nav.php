{template:header}

</head>
<body class="{if $type=='index'}multi {$type}{else}single {$type}{/if}">

<!-- top bar -->
<div id="top">
	<div class="center">
    <div class="menu-left">
    <ul>
     <li><a href="javascript:;" onclick="setHomepage('{#CFG_BLOG_HOST#}');">设为首页</a></li>
     <li><a href="javascript:;" onclick="addFavorite('{#CFG_BLOG_HOST#}','{#CFG_BLOG_NAME#} - {#CFG_BLOG_SUBNAME#}')">收藏本站</a></li>      
    </ul>
    </div>
    <div class="menu-right">
    <ul>
     {if $tqb->CheckRights('admin')}
     	<li>欢迎 {$user->Name}({$user->LevelName})！</li>
        {if $tqb->CheckRights('ArticleEdt')}
     	<li><a href="{$host}admin/admin.php?act=ArticleEdt">发博文</a></li>
     	{/if} 
        <li><a href="{$host}admin/admin.php?act=login">管理</a></li>
        <li><a href="{$host}admin/admin.php?act=logout">退出</a></li>
     {else}
     	<li><a href="{$host}admin/admin.php?act=login">登陆</a></li>
     {/if}    
    </ul>
    </div>
   </div>	
</div>
  
<div id="divAll">
	<div id="divPage">
	<div id="divMiddle">
		<div id="divTop">
			<h1 id="BlogTitle"><a href="{$host}">{$name}</a></h1>
			<h3 id="BlogSubTitle">{$subname}</h3>
		</div>
		<div id="divNavBar">
			<ul>
				{if $type=='index'}{module:navbar}{else}{$modules['navbar'].Content}{/if}
			</ul>
		</div>
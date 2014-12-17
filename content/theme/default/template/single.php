{template:header_nav}


		<div id="divMain">
{if $article.Type==CFG_POST_TYPE_ARTICLE}
{template:post-single}
{else}
{template:post-page}
{/if}
		</div>
		<div id="divSidebar">
{template:sidebar}
		</div>
{template:footer}
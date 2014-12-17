{template:header_nav}


		<div id="divMain">
{foreach $articles as $article}

{if $article.IsTop}
{template:post-istop}
{else}
{template:post-multi}
{/if}

{/foreach}
<div class="pagebar">{template:pagebar}</div>
		</div>
		<div id="divSidebar">
{template:sidebar}
		</div>
{template:footer}
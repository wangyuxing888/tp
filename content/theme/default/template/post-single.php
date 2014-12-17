<div class="post single">
	<h4 class="post-date">{$article.Time('Y年m月d日')}</h4>
	<h2 class="post-title">{$article.OriginName}{$article.Title}</h2>
	<div class="post-body">{$article.Content}</div>
	<h5 class="post-tags"></h5>
	<h6 class="post-footer">
		发布者:{$article.Author.Name} | 分类:{$article.Category.Name} | 阅读:{$article.ViewNums} | <span id="article_flash_{$article.ID}">&nbsp;</span><a href="javascript:;" onclick=ChangeGood("{$article.ID}")>赞</a>:<span id="article_good_{$article.ID}">{$article.Good}</span> | 评论:{$article.CommNums}
	</h6>
    {if $option['CFG_DISPLAY_QRCODE']}<div style="text-align:center;"><img src="{$article.QRcode}" alt="{$article.Url}" title="{$article.Title}" /><p>“扫一扫”二维码分享给朋友</p></div>{/if}
    {template:share}
</div>

{if !$article.IsLock}
{template:comments}
{/if}
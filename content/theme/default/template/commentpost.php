<div class="post" id="divCommentPost">
	<p class="posttop"><a name="comment">{if $user.ID>0}{$user.Name}{/if}发表评论:</a><a rel="nofollow" id="cancel-reply" href="#divCommentPost" style="display:none;"><small>取消回复</small></a></p>
	<form id="frmSumbit" target="_self" method="post" action="{$article.CommentPostUrl}" >
	<input type="hidden" name="inpId" id="inpId" value="{$article.ID}" />
	<input type="hidden" name="inpRevID" id="inpRevID" value="0" />
{if $user.ID>0}
	<input type="hidden" name="inpName" id="inpName" value="{$user.Name}" />
    <input type="hidden" name="inpQQ" id="inpQQ" value="{$user.QQ}" />
	<input type="hidden" name="inpEmail" id="inpEmail" value="{$user.Email}" />
	<input type="hidden" name="inpHomePage" id="inpHomePage" value="{$user.HomePage}" />	
{else}
	<p><input type="text" name="inpName" id="inpName" class="text" value="{$user.Name}" size="28" tabindex="1" /> <label for="inpName">名称(*)</label></p>
    <p><input type="text" name="inpQQ" id="inpQQ" class="text" value="{$user.QQ}" size="28" tabindex="2" /> <label for="inpQQ">QQ</label></p>
	<p><input type="text" name="inpEmail" id="inpEmail" class="text" value="{$user.Email}" size="28" tabindex="3" /> <label for="inpEmail">邮箱</label></p>
	<p><input type="text" name="inpHomePage" id="inpHomePage" class="text" value="{$user.HomePage}" size="28" tabindex="4" /> <label for="inpHomePage">网址</label></p>
{if $option['CFG_COMMENT_VERIFY_ENABLE']}
	<p><input type="text" name="inpVerify" id="inpVerify" class="text" value="" size="28" tabindex="5" /> <label for="inpVerify">验证码(*)</label>
	<img style="width:{$option['CFG_VERIFYCODE_WIDTH']}px;height:{$option['CFG_VERIFYCODE_HEIGHT']}px;cursor:pointer;" src="{$article.CaptchaUrl}" alt="" title="" onclick="javascript:this.src='{$article.CaptchaUrl}&amp;tm='+Math.random();"/>
	</p>
{/if}

{/if}
	<p><label for="txaArticle">正文(*)</label></p>
	<p><textarea name="txaArticle" id="txaArticle" class="text" cols="50" rows="4" tabindex="6" ></textarea></p>
	<p><input name="sumbit" type="submit" tabindex="7" value="提交" onclick="return VerifyMessage()" class="button" /></p>
	</form>
	<p class="postbottom">☆欢迎发表您的看法、交流您的观点。</p>
</div>
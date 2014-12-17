<div class="post single">
	<h4 class="post-date"><?php  echo $article->Time('Y年m月d日');  ?></h4>
	<h2 class="post-title"><?php  echo $article->OriginName;  ?><?php  echo $article->Title;  ?></h2>
	<div class="post-body"><?php  echo $article->Content;  ?></div>
	<h5 class="post-tags"></h5>
	<h6 class="post-footer">
		发布者:<?php  echo $article->Author->Name;  ?> | 分类:<?php  echo $article->Category->Name;  ?> | 阅读:<?php  echo $article->ViewNums;  ?> | <span id="article_flash_<?php  echo $article->ID;  ?>">&nbsp;</span><a href="javascript:;" onclick=ChangeGood("<?php  echo $article->ID;  ?>")>赞</a>:<span id="article_good_<?php  echo $article->ID;  ?>"><?php  echo $article->Good;  ?></span> | 评论:<?php  echo $article->CommNums;  ?>
	</h6>
    <?php if ($option['CFG_DISPLAY_QRCODE']) { ?><div style="text-align:center;"><img src="<?php  echo $article->QRcode;  ?>" alt="<?php  echo $article->Url;  ?>" title="<?php  echo $article->Title;  ?>" /><p>“扫一扫”二维码分享给朋友</p></div><?php } ?>
    <?php  include $this->GetTemplate('share');  ?>
</div>

<?php if (!$article->IsLock) { ?>
<?php  include $this->GetTemplate('comments');  ?>
<?php } ?>
<div class="post multi">
	<h4 class="post-date"><?php  echo $article->Time('Y年m月d日');  ?></h4>
	<h2 class="post-title"><?php  echo $article->OriginName;  ?><a href="<?php  echo $article->Url;  ?>"><?php  echo $article->Title;  ?></a></h2>
	<div class="post-body"><?php  echo $article->Intro;  ?></div>
	<h5 class="post-tags"></h5>
	<h6 class="post-footer">
		发布者:<?php  echo $article->Author->Name;  ?> | 分类:<?php  echo $article->Category->Name;  ?> | 阅读:<?php  echo $article->ViewNums;  ?> | 赞:<?php  echo $article->Good;  ?> | 评论:<?php  echo $article->CommNums;  ?>
	</h6>
</div>
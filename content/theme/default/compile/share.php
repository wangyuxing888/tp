<div class="share">
	<div class="bdsharebuttonbox">
	    <a class="bds_mshare" title="分享到一键分享" href="#" data-cmd="mshare"></a>
    	<a class="bds_tsina" title="分享到新浪微博" href="#" data-cmd="tsina"></a>
    	<a class="bds_tqq" title="分享到腾讯微博" href="#" data-cmd="tqq"></a>
    	<a class="bds_sqq" title="分享到QQ好友" href="#" data-cmd="sqq"></a>
    	<a class="bds_qzone" title="分享到QQ空间" href="#" data-cmd="qzone"></a>
    	<a class="bds_tqf" title="分享到腾讯朋友" href="#" data-cmd="tqf"></a>
    	<a class="bds_tsohu" title="分享到搜狐微博" href="#" data-cmd="tsohu"></a>
    	<a class="bds_t163" title="分享到网易微博" href="#" data-cmd="t163"></a>
    	<a class="bds_xinhua" title="分享到新华微博" href="#" data-cmd="xinhua"></a>
    	<a class="bds_renren" title="分享到人人网" href="#" data-cmd="renren"></a>
    	<a class="bds_douban" title="分享到豆瓣网" href="#" data-cmd="douban"></a>
    	<a class="bds_kaixin001" title="分享到开心网" href="#" data-cmd="kaixin001"></a>
    	<a class="bds_fbook" title="分享到Facebook" href="#" data-cmd="fbook"></a>
    	<a class="bds_twi" title="分享到Twitter" href="#" data-cmd="twi"></a>
    	<a class="bds_weixin" title="分享到微信" href="#" data-cmd="weixin"></a>
    	<a class="bds_more" href="#" data-cmd="more"></a>
	</div>
</div>
<?php  $init="<script> var text='#" . $name . "# " . $article->Title . " — " . $description . "';var desc='" . $article->Title . "';var img='" . $article->QRcode . "';var url='" . $article->Url . "';</script>";  ?>
<?php  echo $init;  ?>
<script>
	//bdText : '自定义分享内容',bdDesc : '自定义分享摘要',bdUrl : '自定义分享url地址',bdPic : '自定义分享图片'
	window._bd_share_config={"common":{"bdSnsKey":{"tsina":"新浪key","tqq":"腾讯key","t163":"网易key","tsohu":"搜狐key"},"bdText":text,"bdDesc":desc,"bdUrl":url,"bdMini":"2","bdMiniList":false,"bdPic":img,"bdStyle":"0","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script>
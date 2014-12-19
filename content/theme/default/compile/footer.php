		<div id="divBottom">
        	<h3 id="BlogCopyRight">
                <?php if ($email_admin) { ?><a href="mailto:<?php  echo $email_admin;  ?>" target="_blank" title="管理邮箱">管理邮箱 </a><?php } ?>
                <?php if ($qq) { ?><a href="http://wpa.qq.com/msgrd?V=3&Uin=<?php  echo $qq;  ?>&Site=<?php  echo $name;  ?>&Menu=yes&from=TQBlog" target="_blank" title="QQ"><img src="admin/image/common/site_qq.png" alt="QQ" /> </a><?php } ?>
            	<?php  echo $copyright;  ?>
            </h3>
			<h4 id="BlogPowerBy">Powered by <?php  echo $tqblogphphtml;  ?></h4>
		</div><div class="clear"></div>
	</div><div class="clear"></div>
	</div><div class="clear"></div>
</div>
<?php  echo $footer;  ?>

<div id="scrolltop">
	<?php if ($tqb->CheckRights('ArticleEdt')) { ?>
	<span hidefocus="true"><a href="<?php  echo $host;  ?>admin/admin.php?act=ArticleEdt" class="fastpublish" title="发博文"><b>发博文</b></a></span>
    <?php } ?>
    <span hidefocus="true" id="backtop"><a title="返回顶部" onclick="window.scrollTo('0','0')" class="scrolltopa" ><b>返回顶部</b></a></span>
</div>
<script type="text/javascript">
$(document).ready(function(){ 
	$("#scrolltop").hide();
	$(function () {$(window).scroll(function(){if ($(window).scrollTop()>100){$("#scrolltop").fadeIn(500);}else{$("#scrolltop").fadeOut(500);}});
	});});
</script>

</body>
</html>
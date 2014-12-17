<?php if($tqb->option['CFG_ADMIN_HTML5_ENABLE']){?></section><?php }else{?></div><?php }?>
<?php
foreach ($GLOBALS['Filter_Plugin_Admin_Footer'] as $fpname => &$fpsignal) {$fpname();}
?>
</body>
</html>
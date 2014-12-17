<?php if($tqb->option['CFG_ADMIN_HTML5_ENABLE']){?><aside class="left"><?php }else{?><div class="left"><?php }?>
  <ul id="leftmenu">
<?php
  ResponseAdmin_LeftMenu()
?>
  </ul>
<?php if($tqb->option['CFG_ADMIN_HTML5_ENABLE']){?></aside><?php }else{?></div><?php }?>
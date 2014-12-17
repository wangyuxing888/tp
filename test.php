<?php  
//ob_start();   
echo "this will be printed to browser<BR>";  
header("location:index.php");   
ob_end_flush();
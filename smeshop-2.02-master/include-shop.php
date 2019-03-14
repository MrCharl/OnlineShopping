<?php

session_start();

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");

themehead2($title);

echo "<center><table border=0 width=1000><tr><td>";

show_reccommend_products2();

echo "</td></tr></table></center>";

?>
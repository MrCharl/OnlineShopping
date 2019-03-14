<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

include ("config.php");
include ("category.php");
include ("toplink.php");
include("function.php");

$new_s = $_GET["new_s"];
$qtys = $_GET["qtys"];
$act = $_GET["act"];

if($act=="remove") {

	themehead("Recently Viewed Products");
	echo "<br><div class=\"boxshadow boxred\" align=center><h1>ลบรายการสินค้า เรียบร้อยแล้ว !!</h1></div><br><br>";
	unset($_SESSION["recently"][$new_s]);
	$_SESSION['num_recently'] = $_SESSION['num_recently'] - 1;
	show_bestseller_products();
	themefoot();
	exit;

}	

if($act=="clear") {

	themehead("Recently View Product");
	echo "<br><div class=\"boxshadow boxred\" align=center><h1>ลบประวัติกาชมสินค้าที่ชอบ เรียบร้อยแล้ว !!</h1></div><br><br>";
	$_SESSION["recently"] = Array();	
	$_SESSION['num_recently'] = 0;
	show_discount_products();
	themefoot();
	exit;

}	
	

$nofollow=1;

themehead("Recently View Product");

$_SESSION['num_recently'] = count($_SESSION["recently"]);


if(count($_SESSION["recently"])<1)   
	{
echo "<br><div class=\"boxshadow boxred\" align=center><h1>ยังไม่มีประวัติการชมสินค้า !!</h1></div><br><br>";

show_reccommend_products();

themefoot();
mysql_close($connection); 
exit;
	}

	$info = "";
	$i=0;
	$info .= "<center><table width=100% border=0 ><tr><td>";
	
	$info .= "<center><br>
	<i class='boxshadow boxlightblue fa fa-eye'></i>&nbsp;ดูรายละเอียด&nbsp;&nbsp;
	<i class='boxshadow boxred fa fa-remove'></i>&nbsp;ลบออก&nbsp;&nbsp;
	<i class=\"boxshadow boxlemon fa fa-check\"></i>&nbsp;มีสินค้านี้ในตะกร้าแล้ว&nbsp;&nbsp;
	<i class=\"boxshadow boxheart fa fa-heart\"></i>&nbsp;เพิ่มเป็นสินค้าที่ชอบ&nbsp;&nbsp;
	<i class=\"boxshadow boxred fa fa-shopping-cart\"></i>&nbsp;หยิบใส่ตะกร้า&nbsp;&nbsp;
	<i class=\"boxshadow boxeye fa fa-exchange\"></i>&nbsp;เปรียบเทียบสินค้า&nbsp;&nbsp;
	</center><br>";
	
	$info .= "<table width=100% border=0 cellpadding=4 cellspacing=10><tr>";
	foreach ($_SESSION["recently"] as $isbn => $qty)
	{
		$maxview = $_SESSION["recently"][$isbn];
		$mvtitle = ($maxview > 2)  ? "<font color=red>".$maxview."</font>&nbsp;<img src=images/hot1.jpg>" : $maxview;
	  	if($i%3==0) $info  .="</tr><tr>";
		$product = get_product_details_1($isbn);
		$pid = $product['mainid'];
		if($pid=="") {
			$product = get_product_details_2($isbn);		
			$pid = $product['mainid'];			
		}
		$image = get_catalog_image($pid);
		$imarray = explode("@",$image['picture']);
		$img = $imarray[0];
		$info .= "<td align=center><div class=\"box\"><br>
		<a href=catalog.php?idp=".$product['mainid']."><ul class=\"enlarge\"><li><img src=images/".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span></li></ul>	
		<font color=#5dbae1>".stripslashes($image["title"])."</font></a><br><br>จำนวนครั้งที่เข้าชม <i class='fa fa-eye'></i> ".$mvtitle."<br><br>
		<a href=catalog.php?idp=".$product['mainid']." title=\"ดูรายละเอียด\"><i class='boxshadow boxlightblue fa fa-eye'></i></a>&nbsp;&nbsp;";
		
	/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
	$productdetail = get_product_details_2($isbn);
	$chkid  = $productdetail['id'];
	$itemprice = $productdetail['price'];
	$chkincart = $_SESSION["cart"][$chkid];
	$sumprice = $itemprice * $chkincart;
	
	$info .= "<a href=\"recently-view.php?act=remove&new_s=$isbn\" title=\"ลบออก\"><i class='boxshadow boxred fa fa-remove'></i>&nbsp;";
	
	if($chkincart != "")
	{
		$info .= "&nbsp;<a href=order.php title=\"มีสินค้านี้ในตะกร้าแล้ว\"><i class=\"boxshadow boxlemon fa fa-check\"></i></a>&nbsp;
					<a href=wishlist.php?new_s=".$chkid." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp
					<a href=compare-products.php?new_s=".$chkid." title=\"เปรียบเทียบสินค้า\">&nbsp;<i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";	
	} else {
		$info .= "&nbsp;<a href=order.php?qtys=1&new_s=".$chkid." title=\"หยิบใส่ตะกร้า\">&nbsp;<i class=\"boxshadow boxred fa fa-shopping-cart\"></i></a>&nbsp;
				<a href=wishlist.php?new_s=".$chkid." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
				<a href=compare-products.php?new_s=".$chkid." title=\"เปรียบเทียบสินค้า\">&nbsp;<i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";	
	}
		$info .= "</div></td>";
		$i++;
	}
	$info .= "</tr></table></td></tr></table></center>";

echo "<br><div class=\"boxshadow recently\"><br><br><br><br><br></div>";
echo "<table border=0 cellpadding=0 cellspacing=0 bordercolor=#eeeeee width=100%><tr><td align=center><br>";
echo $info;
echo "<br></td></tr></table>";
echo "<div align=center><a href=\"recently-view.php?act=clear\" class=\"btn btn-danger btn-xs\"><i class='fa fa-remove'></i> ลบประวัติการชมสินค้าทั้งหมด</a></div>";
 
themefoot();
mysql_close($connection); 
exit;

?> 
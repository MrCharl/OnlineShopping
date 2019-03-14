<?php

/*####################################################
โปรแกรม: SMEweb เวอร์ชั่น: 1.4f  
คือโปรแกรมบริหารเว็บไซต์ Content Manager System (CMS)
พัฒนาขึ้นมาจาก ภาษา PHP HTML และ JAVASCRIPT 
เป็นโปรแกรมเปิดเผย Source Code แจกจ่ายให้ใช้งานได้ฟรี โดยไม่มีค่าใช้จ่าย 
ท่านสามารถ เผยแพร่ ทำซ้ำ แก้ไข ดัดแปลง โปรแกรมนี้ได้ ภายใต้ข้อกำหนดและเงื่อนไข GPL 
ทางผู้พัฒนา จะไม่รับผิดชอบความเสียหายที่เกิดขึ้น จากโปรแกรมนี้ในทุกกรณี

GPL คืออะไร?
อ่านเอกสาร GPL ภาษาไทยได้ที่ http://developer.thai.net/gpl/
อ่านเอกสาร GPL ต้นฉบับได้ที่ http://www.gnu.org/copyleft/gpl.html

Copyright (C) 2007  Mr.Monsun Uthayanugul 
E-mail: admin@ebizzi.net  Homepage: http://www.ebizzi.net/
#####################################################*/

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
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

	themehead("Compare Products");
	echo "<br><div class=\"boxshadow boxred\" align=center><h1>ลบรายการสินค้าเปรียบเทียบ เรียบร้อยแล้ว !!</h1></div><br><br>";
	unset($_SESSION["compare"][$new_s]);
	$_SESSION['num_compare'] = $_SESSION['num_compare'] - 1;
	themefoot();
	exit;

}	

if($act=="clear") {

	themehead("Compare Products");
	echo "<br><div class=\"boxshadow boxred\" align=center><h1>ลบรายการสินค้าเปรียบเทียบทั้งหมด เรียบร้อยแล้ว !!</h1></div><br><br>";
	$_SESSION["compare"] = Array();	
	$_SESSION['num_compare'] = 0;
	show_reccommend_products();
	show_discount_products();
	show_bestseller_products();
	themefoot();
	exit;

}	
	

$nofollow=1;

themehead("Compare Products");

if($new_s)
{
	  if(!isset($_SESSION["compare"])) $_SESSION["compare"] = Array();
	  
	if($_SESSION["compare"][$new_s])
	{
		if($qtys)     $_SESSION["compare"][$new_s]+=$qtys;	
		  else          $_SESSION["compare"][$new_s]++;	
	}else{ 
		if($qtys)     $_SESSION["compare"][$new_s]=$qtys;
		 else           $_SESSION["compare"][$new_s]=1;
	}
	
}elseif($act){

	    if(count($_SESSION["compare"])>0)
           {
$superGlobal = '_GET';
global $$superGlobal;
$newSuperGlobal = $$superGlobal;

	foreach($_SESSION["compare"] as $isbn=>$qty)
	{
		if($newSuperGlobal[$isbn]=="0")  unset($_SESSION["compare"][$isbn]);
		else 			$_SESSION["compare"][$isbn] = $newSuperGlobal[$isbn];
	}
           }

}

$_SESSION['num_compare'] = count($_SESSION["compare"]);


if(count($_SESSION["compare"])<1)   
	{
echo "<br><div class=\"boxshadow boxred\" align=center><h1>ยังไม่มีสินค้าเปรียบเทียบ !!</h1></div><br><br>";

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
	</center><br>";
	
	$info .= "<table width=100% border=0 cellpadding=4 cellspacing=10><tr>";
	foreach ($_SESSION["compare"] as $isbn => $qty)
	{
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
		$mw = 100;
		$maxwidth = $mw/count($_SESSION['compare']);
		$info .= "<td align=center valign=top width=$maxwidth><div class=\"box\"><br>
		ชื่อสินค้า: <font color=#5dbae1>".stripslashes($image["title"])."</font><hr>
		<a href=catalog.php?idp=".$product['mainid']."><ul class=\"enlarge\"><li><img src=images/".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span></li></ul></a><hr>
		รหัสสินค้า: <font color=#5dbae1>".$product["pid"]."</font><hr>
		ราคา: <font color=#5dbae1>".$product["sale"]." - ".$product["price"]." บาท</font><hr>";
		
		$instock = ($product['stock'] == 0) ? "<font color=orange><i class='fa fa-close'></i> หมดชั่วคราว</font>" : "<font color=green><i class='fa fa-check-square-o'></i> มีสินค้า</font>";
		$info .= "ในสต๊อก: <font color=#5dbae1>".$instock."</font><hr>";
		
		$query = "SELECT * FROM ".$fix."reviews where product_id='".$product['mainid']."'";
		$result = mysql_query($query); 
		$numrows = mysql_num_rows($result);

		$r = 0;
		while ($array = @mysql_fetch_array($result))
		{
			$r = $r+ $array['rating'];
		}
		if($r>0) $r = $r / $numrows;
		$r = number_format($r,1);
		
		if($numrows >0) {
			$info .= "เรทติ้ง: ".$r." (จาก 5 คะแนน)<br>มี ".$numrows." ความคิดเห็น<hr>";
		} else {
			$info .= "เรทติ้ง: 0 คะแนน<br>ยังไม่มีความเห็น<hr>";
		}
		
		$info .= "ข้อมูลสินค้า (โดยย่อ)<br><div style='height:50px; padding:10 5 5 5px; text-align: left;'><font color=#5dbae1>".len2desc(stripslashes($image["story"]),250)."</font></div><hr>";
		
		$reccommend = ($image['recom'] == 1) ? "<font color=green><i class='fa fa-check-square-o'></i> แนะนำ</font>" : "";
		$bestseller = ($image['bestseller'] == 1) ? "<font color=green><i class='fa fa-check-square-o'></i> ขายดี</font>" : "";
		$promotion = ($product['sale'] < $product['price']) ? "<font color=green><i class='fa fa-check-square-o'></i> ลดราคา</font>" : "";
		$freeshipping = ($product['sale'] >= 500) ? "<font color=green><i class='fa fa-check-square-o'></i> ส่งฟรี</font>" : "";
		
		$info .= "ขนาด : ".$product["size"]."<hr>";
		$info .= "น้ำหนัก : ".$product["weight"]."<hr>";
		
		$info .= "ข้อมูลอื่นๆ เพื่อประกอบการตัดสินใจ<br>$reccommend $bestseller $freeshipping $promotion<hr>		
		<a href=catalog.php?idp=".$product['mainid']." title=\"ดูรายละเอียด\"><i class='boxshadow boxlightblue fa fa-eye'></i></a>&nbsp;&nbsp;";
		
	/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
	$productdetail = get_product_details_1($isbn);
	$chkid  = $productdetail['id'];
	$itemprice = $productdetail['price'];
	$chkincart = $_SESSION["cart"][$chkid];
	$sumprice = $itemprice * $chkincart;
	
	$info .= "<a href=\"compare-products.php?act=remove&new_s=$isbn\" title=\"ลบออก\"><i class='boxshadow boxred fa fa-remove'></i>&nbsp;";
	
	if($chkincart != "")
	{
		$info .= "&nbsp;<a href=order.php title=\"มีสินค้านี้ในตะกร้าแล้ว\"><i class=\"boxshadow boxlemon fa fa-check\"></i></a>&nbsp;";	
	} else {
		$info .= "<a href=order.php?qtys=1&new_s=".$isbn." title=\"หยิบใส่ตะกร้า\">&nbsp;<i class=\"boxshadow boxred fa fa-shopping-cart\"></i></a>&nbsp;";
	}
		$info .= "<a href=wishlist.php?new_s=".$isbn." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a><br><br>";
		$info .= "</div></td>";
		$i++;
	}
	$info .= "</tr></table></td></tr></table></center>";

echo "<br><div class=\"boxshadow compare\"><br><br><br><br><br></div>";
echo "<table border=0 cellpadding=0 cellspacing=0 bordercolor=#eeeeee width=100%><tr><td align=center><br>";
echo $info;
echo "<br></td></tr></table>";
echo "<div align=center><a href=\"compare-products.php?act=clear\" class=\"btn btn-danger btn-xs\"><i class='fa fa-remove'></i> ลบรายการสินค้าเปรียบเทียบทั้งหมด</a></div>";
 
themefoot();
mysql_close($connection); 
exit;

?> 
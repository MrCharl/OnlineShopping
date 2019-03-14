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
include("shipping.php");

require_once ("mpdf/mpdf.php");
$mpdf=new mPDF('UTF-8');
ob_start();

if(!$_SESSION["admin"]) { $errmsg = "กรุณา Login ก่อน"; usrlogin($errmsg); exit; }

$act = $_GET["act"];
$orderno = $_REQUEST["orderno"];
$maxwidth = "100%";

	$sql = mysql_db_query($dbname,"select * from ".$fix."orders where orderno='".$orderno."' ");
	$row=mysql_num_rows($sql);
	
if($row > 0) {
	
	$arr=mysql_fetch_array($sql);
	$ordidarray = explode("@",$arr[11]);
	$ordnumarray = explode("@",$arr[12]);
	
	echo "	
	<link rel=\"stylesheet\" href=\"font-awesome/css/font-awesome.min.css\" />
	<script language=\"javascript\" src=\"js/cp.js\"></script>
	<script src=\"js/sweet/sweetalert-dev.js\"></script>
	<link rel=\"stylesheet\" href=\"js/sweet/sweetalert.css\">
	<link rel=\"stylesheet\" href=\"css/css.css\" type=\"text/css\">
	<link rel=\"stylesheet\" href=\"css\print.css\" type=\"text/css\" media=\"print\" />
	";	
	
	
	echo "<center><br>
	<h1>ข้อมูลใบสั่งซื้อ (Order Details)</h1>
	Order No# $arr[0] @ ".thaidate(substr($arr[1],0,10))."</center><br>";
	
	echo "<center>
	<table border=1 width=$maxwidth cellspacing=0 cellpadding=1 bordercolor=#ccc><tr bgcolor=\"$color2\">
	<td align=center class=top>#</td>
	<td width=50 align=center class=top>ต.ย.</td>
	<td class=top align=center>ชื่อสินค้า</td>
	<td width=100 align=center class=top>ราคาต่อหน่วย</td>
	<td align=center width=60 class=top>จำนวน</td>
	<td align=right width=120 class=top>รวม</td></tr>";
    
	for($i=1; $i<count($ordidarray); $i++)
	{		
		$product = get_product_details_1($ordidarray[$i]);
		$price1 = $product['price'];
		$price2 = $product['sale'];
		if($price2 < $price1) {
			$realprice = $price2;
		} else {
			$realprice = $price1;
		}
		$pid = $product['mainid'];
		$image = get_catalog_image($pid);
		$imarray = explode("@",$image['picture']);
		$img = $imarray[0];
		
		echo "<tr bgcolor=white>";
		echo  "<td align=center>$i</td><td align=center><a href=catalog.php?idp=".$product['mainid']."><img src=images/thumb_".$img." width=50 height=50></td>";
		echo  "<td>".$product["pid"]." ".stripslashes($product["title"])."</td>";
	
		if($price2 < $price1) {
			echo "<td align=center>ปกติ <i class='cross'>" .number_format($price1,2)."</i> บาท<br><font color=red>พิเศษ ".number_format($price2,2)." บาท</font></td>";
		} else {
			echo "<td align=center>" .number_format($realprice,2)." บาท</td>";
		}
		echo  "<td align=center>" .$ordnumarray[$i]."</td>";
	
		echo  "<td align=right>" .number_format(($realprice*$ordnumarray[$i]),2). " บาท</td></tr>\n";
		
		$totalprice = $totalprice + ($realprice*$ordnumarray[$i]);

	}
	echo"<tr bgcolor=\"#ffffff\"><td colspan=5 align=right>ยอดรวมค่าสินค้า</td><td align=right><font color=red>".number_format($totalprice,2)." บาท</font>";
	echo "</td></tr></table><br>";
	echo "<font color=#0000ff>ยอดรวม ค่าธรรมเนียม PayPal (ถ้ามี) + ค่าจัดส่งสินค้า (ถ้ามี) = ".number_format($arr[5],2)." บาท (".bahttext($arr[5]).")</font><br><br>";
	
	include ("shipping.php");
	$pmid = count($shippingmethod);
	if($pmid>0)	{    
		for($i=0; $i<$pmid; $i++) {
			$shipmethod[] = $shippingmethod[$i][0];
		}
	}
	
	switch($arr[4]){
		case "0" : $ordstatus = "<font color=black>ยังไม่ชำระเงิน/ยังไม่จัดส่งสินค้า</font>"; break;
		case "1" : $ordstatus = "<font color=orange>ชำระเงินแล้ว/รอจัดส่งสินค้า</font>"; break;
		case "2" : $ordstatus = "<font color=green>ชำระเงินแล้ว/จัดส่งสินค้าแล้ว</font>"; break;	
	}
	
	echo "<table border=1 cellpading=4 cellspacing=0 bordercolor=#ccc width=$maxwidth>";
	echo "<tr><td width=15%>ชื่อผู้สั่งซื้อสินค้า</td><td>".$arr[6]."</td></tr>";
	echo "<tr><td>ข้อมูลติดต่อ</td><td>โทรศัพท์: ".$arr[7]." อีเมล์: ".$arr[3]."</td></tr>";
	echo "<tr><td>ชื่อผู้รับสินค้า</td><td>".$arr[13]."</td></tr>";
	echo "<tr><td>ที่อยู่จัดส่งสินค้า</td><td>".$arr[14]."</td></tr>";
	echo "<tr><td>หมายเหตุ</td><td>".$arr[15]."</td></tr>";
	echo "<tr><td>สถานะใบสั่งซื้อ</td><td>".$ordstatus."</td></tr>";
	echo "<tr><td>วันที่จัดส่งสินค้า</td><td>".thaidate(substr($arr[8],0,10))."</td></tr>";
	echo "<tr><td>วิธีจัดส่งสินค้า</td><td>".$shipmethod[$arr[9]]."</td></tr>";
	echo "<tr><td>หมายเลขพัสดุ</td><td>".$arr[10]."</td></tr>";
	echo "</table><br>";
	
	echo "<div class='noprint'>
			<table border=0>
			<tr>
				<td><a class='boxshadow boxorose' href=backshopoffice.php?action=vorder><i class='fa fa-arrow-circle-left'> ย้อนกลับ</a></td>
				<td><a class='boxshadow boxlightblue' href=download-order.php?act=download&orderno=".$arr['orderno']."><i class='fa fa-download'> ดาวน์โหลด</a></td>";
				if ($media=="print") {
					echo "<td><a class='boxshadow boxlemon' href=\"javascript:void();\" onclick=\"window.print();\"><i class='fa fa-print'></i> พิมพ์ใบสั่งซื้อ</a></td>";
				} else {
					echo "<td><a class='boxshadow boxlemon' href=view-order.php?act=view&media=print&orderno=".$arr['orderno']."><i class='fa fa-print'></i> ดูมุมมองเพื่อสั่งพิมพ์</a></td>";					
				}
	echo "<td width=45 align=center><a class='boxshadow boxred' href=\"javascript:void();\" onclick=\"mordr5('1','$orderno')\"><i class='fa fa-remove'></i> ลบ </a></td>		
			</tr>
			</table>
			</div>";
			
	$orderno = $arr['orderno'];
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf->SetAutoFont();
	$mpdf->WriteHTML($html);
	//$mpdf->Output();
	$mpdf->Output("ShopPDF/Order-$orderno.pdf");   
	echo "<center>Download Order $orderno.pdf <a href='ShopPDF/Order-$orderno.pdf'>Click Here</a></center>";

} else {
	echo "<div align=center><h1>ไม่พบข้อมูลใบสั่งซื้อเลขที่ $orderno</h1></div>";
}	
	
?>
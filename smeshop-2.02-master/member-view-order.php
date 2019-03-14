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
include("shipping.php");

$act = $_GET["act"];
$orderno = $_REQUEST["orderno"];
$email = $_REQUEST["email"];
$maxwidth = ($_REQUEST["media"] == "print") ? "100%" : "50%";

$sql = mysqli_query($connection,"select * from ".$fix."orders where orderno='".$orderno."' && ordermail ='".$email."' ");
$numrow = mysqli_num_rows($sql);

if($numrow > 0) {
	
$arr=mysqli_fetch_array($sql);
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
	Order No# $arr[0] @ ".substr($arr[1],8,2)."-".substr($arr[1],5,2)."-".substr($arr[1],2,2)." ".substr($arr[1],11,5)."</center><br>";
	
	echo "<center>
	<table border=1 width=$maxwidth cellspacing=0 cellpadding=1 bordercolor=#ccc><tr bgcolor=\"$color2\">
	<td align=center class=top>ลำดับที่</td>
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
	echo "<tr><td>ชื่อผู้สั่งซื้อสินค้า</td><td>".$arr[6]."</td></tr>";
	echo "<tr><td>ข้อมูลติดต่อ</td><td>โทรศัพท์: ".$arr[7]." อีเมล์: ".$arr[3]."</td></tr>";
	echo "<tr><td>ชื่อผู้รับสินค้า</td><td>".$arr[13]."</td></tr>";
	echo "<tr><td>ที่อยู่จัดส่งสินค้า</td><td>".$arr[14]."</td></tr>";
	echo "<tr><td>หมายเหตุเพิ่มเติม</td><td>".$arr[15]."</td></tr>";
	echo "<tr><td>สถานะใบสั่งซื้อ</td><td>".$ordstatus."</td></tr>";
	echo "<tr><td>วันที่จัดส่งสินค้า</td><td>".$arr[8]."</td></tr>";
	echo "<tr><td>วิธีจัดส่งสินค้า</td><td>".$shipmethod[$arr[9]]."</td></tr>";
	echo "<tr><td>หมายเลขพัสดุ</td><td>".$arr[10]."</td></tr>";
	echo "</table><br>";
	
	echo "<div class='noprint'>
			<table border=0>
			<tr>
				<td><a class='boxshadow boxlightblue' href=member-download-order.php?orderno=".$arr['orderno']."&email=".$arr['ordermail']."><i class='fa fa-download'> ดาวน์โหลด.pdf</a></td>";
				if ($media=="print") {
					echo "<td><a class='boxshadow boxlemon' href=\"javascript:void();\" onclick=\"window.print();\"><i class='fa fa-print'></i> พิมพ์ใบสั่งซื้อ</a></td>";
				} else {
					echo "<td><a class='boxshadow boxlemon' href=view-order.php?act=view&media=print&orderno=".$arr['orderno']."><i class='fa fa-print'></i> ดูมุมมองเพื่อสั่งพิมพ์</a></td>";					
				}
	echo "			
			</tr>
			</table>
			</div>";
			
} else {
	echo "<center><h1>Access Denied</h1>เฉพาะลูกค้าเจ้าของ Order นี้เท่านั้นที่จะสามารถดูข้อมูลใบสั่งซื้อได้</center>";
}
	
?>
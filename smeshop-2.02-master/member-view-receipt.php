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

if(!$_SESSION['member']['user']) { echo "<center>Member Area Only. Please login!</center>"; exit; }

$act = $_GET["act"];
$orderno = $_REQUEST["orderno"];
$email = $_REQUEST["email"];
$maxwidth = ($_REQUEST["media"] == "print") ? "100%" : "50%";

	$sql = mysqli_query($connection,"select * from ".$fix."orders where orderno='".$orderno."' and ordermail='".$email."' and orderstatus!='0' ");
	$row=mysqli_num_rows($sql);
	
if($row > 0)	 {
	
	$arr=mysqli_fetch_array($sql);
	
	$ordidarray = explode("@",$arr[11]);
	$ordnumarray = explode("@",$arr[12]);
	
	$receiptno = $arr[16];
	if($receiptno=="") {
		$receiptno = $orderno;
	}
	$receiptdate = $arr[17];
	if($receiptdate=="0000-00-00 00:00:00") {
		$receiptdate = thaidate(substr($arr[19],0,10));
	} else {
		$receiptdate = thaidate(substr($arr[17],0,10));
	}
	$receiptname = $arr[18];
	if($receiptname=="") {
		$receiptname = $shopowner;
	}
	
	$shopurl = "www.".$domainname;
	
echo "	
<link rel=\"stylesheet\" href=\"font-awesome/css/font-awesome.min.css\" />
<script language=\"javascript\" src=\"js/cp.js\"></script>
<script src=\"js/sweet/sweetalert-dev.js\"></script>
<link rel=\"stylesheet\" href=\"js/sweet/sweetalert.css\">
<link rel=\"stylesheet\" href=\"css/css.css\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"css\print.css\" type=\"text/css\" media=\"print\" />
";	
	
	echo "<center>
	<h1>ใบเสร็จรับเงิน (Receipt)</h1>
	<table border=1 width=$maxwidth cellspacing=0 cellpadding=4 bordercolor=#ccc>
	<tr>
	<td width=50% height=80 align=left valign=top>
	ออกให้: คุณ".$arr[6]."<br>".$arr[14]."
	</td>
	<td width=50% height=80 align=left valign=top>
	ออกโดย: ".$shopname."<br>".$shopaddr."<br>
	โทร. ".$shoptelno." ".$shopurl."
	</td>
	</tr>
	<tr>
	<td align=left valign=top>
	เลขที่ใบสั่งซื้อ# $arr[0]<br>
	วันที่สั่งซื้อสินค้า ".thaidate(substr($arr[1],0,10))."
	</td>
	<td align=left valign=top>
	เลขที่ใบเสร็จรับเงิน# $receiptno<br>
	วันที่ออกใบเสร็จรับเงิน  ".$receiptdate."
	</td>
	</tr>
	</table><br>
	
	<table border=1 width=$maxwidth cellspacing=0 cellpadding=1 bordercolor=#ccc><tr bgcolor=\"$color2\"><td align=center class=top>#</td><td width=50 align=center class=top>ต.ย.</td><td class=top align=center>ชื่อสินค้า</td><td width=100 align=center class=top>ราคาต่อหน่วย</td><td align=center width=60 class=top>จำนวน</td><td align=right width=120 class=top>รวม</td></tr>";
    
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
	
	echo "<table border=1 cellpading=4 cellspacing=0 bordercolor=#ccc width=$maxwidth>";
	if($arr[8]!="0000-00-00 00:00:00") {
		echo "<tr><td>วันที่จัดส่งสินค้า: ".thaidate(substr($arr[8],0,10))."</td><td>วิธีจัดส่งสินค้า: ".$shipmethod[$arr[9]]."</td><td>หมายเลขพัสดุ: ".$arr[10]."</td></tr>";
	}
	echo "<tr><td>วันที่ชำระเงิน: ".thaidate(substr($arr[19],0,10))."</td><td>ช่องทางชำระเงิน: ".$arr[20]."</td><td>ชื่อผู้รับเงิน: ".$receiptname."</td></tr>";
	echo "</table><br><br><hr class='style16'><br>";
	
	echo "<center>
	<table border=1 width=$maxwidth cellspacing=0 cellpadding=4 bordercolor=#ccc>
	<tr>
	<td width=50% height=100 align=left valign=top>
	<br>กรุณานำส่ง: คุณ".$arr[6]."<br>".$arr[14]."
	</td>
	<td width=50% height=100 align=left valign=top>
	<br>ผู้ส่ง: ".$shopname."<br>".$shopaddr."<br>
	โทร. ".$shoptelno." ".$shopurl."
	</td>
	</tr>
	</table><br>";
	
	echo "<div class='noprint'>
			<table border=0>
			<tr>";
				if ($media=="print") {
					echo "<td><a class='boxshadow boxlemon' href=\"javascript:void();\" onclick=\"window.print();\"><i class='fa fa-print'></i> พิมพ์ใบสั่งซื้อ</a></td>";
				} else {
					echo "<td><a class='boxshadow boxlemon' href=view-receipt.php?act=view&media=print&orderno=".$arr['orderno']."><i class='fa fa-print'></i> ดูมุมมองเพื่อสั่งพิมพ์</a></td>";					
				}
	echo "			
			</tr>
			</table>
			</div>";

} else {
	echo "<div align=center><h1>ไม่พบข้อมูลใบเสร็จรับเงิน ของ ใบสั่งซื้อเลขที่ $orderno<br>หรืออาจเป็นเพราะใบสั่งซื้อนี้ยังไม่มีการปรับปรุงสถานะ</h1></div>";
}			
	
?>
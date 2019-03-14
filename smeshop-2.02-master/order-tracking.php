<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");
include("shipping.php");

$act = $_GET['act'];

if($act=="") {
	themehead("Order Tracking");	
		ordertrackingform();
	themefoot();
exit;
}

if($act=="tracking"){

$err="";
$orderno = $_POST['orderno'];

If($orderno=="") { $err = "Error: กรุณากรอก เลขที่ใบสั่งซื้อ ของท่านด้วย<br>";}
If($err !="") { 
	themehead("Order Tracking");	
	echo "<br><br><center>";
	echo "<div class=\"boxshadow boxred\">$err</div>";
	echo "<br><a href='order-tracking.php'>Go Back</a><br>";
	echo "<center>";
	themefoot();
	exit;
}

$strSQL = "SELECT * FROM ".$fix."orders WHERE orderno = '".$orderno."' and orderstatus='2'";
$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
$objResult = mysqli_fetch_array($objQuery);

If($objResult[0]!="") {
	themehead("Order Tracking");	
	echo "<center>
	<br><div class=\"boxshadow boxlemon\" align=center><h1>ขอแสดงความยินดี สินค้าของท่าน จัดส่งเรียบร้อยแล้ว</h1></div><br>
	<table cellspacing=0 cellpadding=2 width=100% border=0><tr><td valign=top>
	<table width=100% cellspacing=0 cellpadding=4 border=0><tr><td valign=top><table width=100% cellspacing=0 cellpadding=4 border=1 bordercolor=#eeeeee>
	<tr background=\"images/bgbb2.gif\"><td width=755 colspan=2><font color=#FF816E size=3><b>รายการสินค้าที่จัดส่งแล้ว</b></font> <br></td></tr><tr><td>
	<table cellspacing=4 cellpadding=0 width=100% border=1 bgcolor=#cccccc bordercolor=#eeeeee><tr><td valign=top>
	<center><table width=100% cellspacing=2 cellpadding=3 border=0 bgcolor=#ffffff><tr><td valign=top>
	<table width=100% border=1 cellpadding=0 cellspacing=0  bordercolor=#eeeeee><tr  bgcolor=\"#5DBAE1\"><td align=center><font color=white><b>เลขที่ใบสั่งซื้อ</b></font></td>
	<td align=center><font color=white><b>วันที่จัดส่ง</b></font></td><td align=center><font color=white><b>วิธีจัดส่ง</b></font></td><td align=center><font color=white><b>หมายเลขพัสดุ</b></font></td></tr>
	<tr><td align=center>".$objResult['orderno']."</td><td align=center>".thaidate(substr($objResult['shippingdate'],0,10))." ".substr($objResult['shippingdate'],11,5)."</td><td align=center>".$objResult['shippingmethod']."</td><td align=center>".$objResult['trackingno']."</td></tr>
	</table></table></center>
	</td></tr></table></td></tr></table></td></tr></table>
	<br><center><a href='order-tracking.php'>Go Back</a></center><br>
	</td></tr></table>";		
	themefoot();
} else {
	themehead("Order Tracking");	
	echo "<center><br><br>";
	echo "<div class=\"boxshadow boxred\" align=center><h1>ไม่พบข้อมูลการจัดส่งของ Order No:$orderno</h1></div><br>";
	echo "<br><a href='order-tracking.php'>Go Back</a><br>";
	echo "</center>";
	themefoot();
}	
	
}


function ordertrackingform()
{
echo "<table cellspacing=0 cellpadding=2 width=100% border=0><tr><td valign=top>
<table width=100% cellspacing=0 cellpadding=4 border=0><tr><td valign=top>
<table width=100% cellspacing=0 cellpadding=4 border=1 bordercolor=#eeeeee>
<tr background=\"images/bgbb2.gif\"><td width=755 colspan=2><font color=#FF816E size=3><b>ตรวจสอบการจัดส่งสินค้า</b></font> <br></td></tr><tr><td>";
echo "
<center>
		<br><br>
		<form name=\"tracking\" action=\"order-tracking.php?act=tracking\" method=\"post\">
		<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr bgcolor=#ffffff>
			<td align=center><a href=\"http://track.thailandpost.co.th/tracking/default.aspx\"><img src=\"images/ems.jpg\" border=0><br>ตรวจสอบสถานะ</a></td>
			<td align=center>
				<table border=0>
				<tr><td align=center><img src=\"images/shipping.jpg\" border=0></td></tr>
				<tr><td align=center bgcolor=#F8F8F6>กรอกเลขที่ใบสั่งซื้อที่ต้องการตรวจสอบ</td></tr>
				<tr>
					<td align=center bgcolor=#F8F8F6><input class=\"normal_input\" type=\"text\" name=\"orderno\" size=\"25\" required>
					<input type=hidden name='chkform' value='ok'>
					<input type=\"submit\" name=\"submit\" value=\" Tacking \"></td>
				</tr>
				</table>
			</td>
		<td align=center><a href=\"http://th.ke.rnd.kerrylogistics.com/shipmenttracking/track.aspx?con=&pid=&ref=\"><img src=\"images/kerry-express.jpg\" border=0><br>ตรวจสอบสถานะ</a></td>
		<tr><td colspan=3>&nbsp;</td></tr>
		</table>
</center>";
echo "</td></tr></table></td></tr></table></td></tr></table></center>";

}

?>
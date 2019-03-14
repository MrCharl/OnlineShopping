<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include("config.php");
include ("shipping.php");

echo "<table cellspacing=0 cellpadding=2 width=100% border=0><tr><td valign=top>
<table width=100% cellspacing=0 cellpadding=4 border=0><tr><td valign=top><table width=100% cellspacing=0 cellpadding=4 border=1 bordercolor=#eeeeee>
<tr background=\"images/bgbb2.gif\"><td width=755 colspan=2><font color=#FF816E size=3><b>รายการสินค้าที่จัดส่งแล้ว</b></font> <br></td></tr><tr><td>";

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
		</table><br>แสดงเฉพาะ 25 รายการ เรียงลำดับจากวันที่จัดส่งล่าสุด เท่านั้น ท่านสามารถค้นหาโดยกรอกเลขที่ใบสั่งซื้อของท่าน<br><font color=red>(หมายเหตุ: ใบสั่งซื้อที่ไม่ชำระเงินภายใน 15 วัน จะถูกลบทิ้งโดยอัตโนมัติ)</font>
</center>";

echo "<table cellspacing=4 cellpadding=0 width=100% border=1 bgcolor=#cccccc bordercolor=#eeeeee><tr><td valign=top>";
echo "<center><table width=100% cellspacing=2 cellpadding=3 border=0 bgcolor=#ffffff><tr><td valign=top>
<table width=100% border=1 cellpadding=0 cellspacing=0  bordercolor=#eeeeee><tr  bgcolor=\"#5DBAE1\"><td align=center><font color=white><b>ลำดับที่</b></font></td><td align=center><font color=white><b>เลขที่ใบสั่งซื้อ</b></font></td>
<td align=center><font color=white><b>วันที่-เวลา จัดส่ง</b></font></td><td align=center><font color=white><b>วิธีจัดส่ง</b></font></td><td align=center><font color=white><b>หมายเลขพัสดุ</b></font></td></tr>";

$strSQL = "SELECT * FROM ".$fix."orders WHERE orderstatus = '2' order by shippingdate desc limit 25";
$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
$i = 0;
while($objResult = mysqli_fetch_array($objQuery))
{
		$i++;
		echo "<tr>
		<td align=center>".$i."</td>
		<td align=center>".$objResult['orderno']."</td>
		<td align=center>".thaidate(substr($objResult['shippingdate'],0,10))." ".substr($objResult['shippingdate'],11,5)."</td>
		<td align=left>".$objResult['shippingmethod']."</td>
		<td align=center>".$objResult['trackingno']."</td>
		</tr>";	
}
	
echo "</table></table>";
echo "</td></tr></table></td></tr></table></td></tr></table>";
echo "</td></tr></table>";

?>
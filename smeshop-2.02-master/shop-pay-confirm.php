<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

require("phpmailer/class.phpmailer.php"); // path to the PHPMailer class

if($_POST['submit']) {

	////////////////////////////////////////////////////////////////////////////////////////// Add to Database  //////////////////////////////////////////////////////////////////////////////

	$orderno = $_POST[ 'orderno' ];
	$orderno = stripslashes( $orderno );
	$orderno = mysqli_real_escape_string($connection, $orderno );

	$custname = $_POST[ 'custname' ];
	$custname = stripslashes( $custname );
	$custname = mysqli_real_escape_string($connection, $custname );

	$custemail = $_POST[ 'custemail' ];
	$custemail = stripslashes( $custemail );
	$custemail = mysqli_real_escape_string($connection, $custemail );

	$bankname = $_POST[ 'bankname' ];
	$bankname = stripslashes( $bankname );
	$bankname = mysqli_real_escape_string($connection, $bankname );

	$total = $_POST[ 'total' ];
	$total = stripslashes( $total );
	$total = mysqli_real_escape_string($connection, $total);
	$total = intval(preg_replace('/[^\d.]/', '', $total));

	$paymentdate = $_POST[ 'paymentdate' ];

	$details = $_POST[ 'details' ];
	$details = stripslashes( $details );
	$details = mysqli_real_escape_string($connection, $details );
	
	$custid = $_POST[ 'custid' ];
	if($custid=="") { $custid="00000";}

	$strSQL = "SELECT * FROM ".$fix."orders where orderno='".$orderno."'";
	$objQuery = mysqli_query($connection,$strSQL) or die(mysql_error());
	$count = mysqli_num_rows($objQuery);

	if($count > 0) {
		if(mysqli_query($connection,"insert into ".$fix."payconfirm values ('','$orderno','$custid','$custname','$custemail','$bankname','$total','$paymentdate','$details','1','')"))
		{
			echo "<div class='boxshadow boxlemon' align=center><h1>บันทึกข้อมูลเรียบร้อยแล้ว</h1></div><br>";
		} else {
			echo "<div class='boxshadow boxred' align=center><h1>Error: ไม่สามารถบันทึกข้อมูลได้ในขณะนี้</h1></div><br>";
		}
	} else {
			echo "<div class='boxshadow boxred' align=center><h1>Error: ไม่พบหมายเลขใบสั่งซื้อที่ท่านกรอก</h1></div><br>";
	}

}

echo "<center><h3><i class='fa fa-edit'></i> แบบฟอร์มยืนยันการโอนเงิน</h3>
<table width=\"100%\" class=\"mytables\">
<form name=\"payconfirm\" method=\"post\" enctype=\"multipart/form-data\">

<tr bgcolor=\"$syscolor1\">
<td>เลขที่ใบสั่งซื้อ:</td><td><input class=\"tblogin\" name=\"orderno\" type=\"text\" id=\"orderno\" size=10 value=".$orderno."></td></tr>
<tr bgcolor=\"$syscolor1\"><td>ชื่อ-นามสกุล:</td><td><input class=\"tblogin\" type=text size=40 name=\"custname\" value='".$cname."'> *</td></tr>
<tr bgcolor=\"$syscolor1\"><td>อีเมล์:</td><td><input class=\"tblogin\" type=text size=40 name=\"custemail\" value=".$cemail."> *</td></tr>
<tr bgcolor=\"$syscolor1\"><td>ยอดเงินตามใบสั่งซื้อ:</td><td><input class=\"tblogin\" name=\"showtotal\" type=text value=".$ctotal."></td></tr>
<tr bgcolor=\"$syscolor1\"><td>ยอดเงินที่โอน:</td><td><input class=\"tblogin\" name=\"total\" type=text> * </td></tr>

<tr bgcolor=\"$syscolor1\">
	<td>วันที่ชำระเงิน:</td><td><input class=\"tblogin\"  type=\"text\" name=\"paymentdate\" size=\"25\"> <i class='fa fa-calendar-check-o'></i>
			<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
			<script src=\"js/datepicker/jquery.simple-dtpicker.js\"></script>
			<link rel=\"stylesheet\" href=\"js/datepicker/jquery.simple-dtpicker.css\">
			<script type=\"text/javascript\">
			$(function(){
				$('*[name=paymentdate]').appendDtpicker({
					\"dateFormat\": \"YYYY-MM-DD h:m\"
				});
			});
			</script>
	</td>
</tr>
<tr bgcolor=\"$syscolor1\"><td>ช่องทางการชำระเงิน</td><td>";

include ("payment.php");
echo "<table width=90% border=1 cellpadding=2 cellspacing=4><tr bgcolor=\"#5DBAE1\" height=20><td colspan=2 align=center><font color=#ffffff><b>ธนาคาร</b></font></td>
<td><font color=#ffffff><b>สาขา</b></font></td><td><font color=#ffffff><b>เลขที่บัญชี</b></font></td><td><font color=#ffffff><b>ชื่อบัญชี</b></font></td></tr>";

$pmid = count($paymentmethod);
 if($pmid>0)	{    
    for($i=0; $i<$pmid; $i++) {
			if( ereg("กรุงเทพ",$paymentmethod[$i][0])) { $bankimg="bbl.jpg"; }
			if( ereg("กสิกร",$paymentmethod[$i][0])) { $bankimg="kbank.jpg"; }
			if( ereg("ไทยพาณิชย์",$paymentmethod[$i][0])) { $bankimg="scb.jpg"; }
			if( ereg("กรุงศรี",$paymentmethod[$i][0])) { $bankimg="bay.jpg"; }
			if( ereg("กรุงไทย",$paymentmethod[$i][0])) { $bankimg="ktb.jpg"; }
			if( ereg("ทหารไทย",$paymentmethod[$i][0])) { $bankimg="tmb.jpg"; }
			echo "<tr><td valign=middle align=center><img src=images/".$bankimg." width=25 height=25></td><td align=left><input type=radio id=bankname$i name=bankname value=".$paymentmethod[$i][0]."><label for=bankname$i><span></span>".$paymentmethod[$i][0]."</label></td><td>".$paymentmethod[$i][1]."</td><td>".$paymentmethod[$i][2]."</td><td>".$paymentmethod[$i][3]."</td></tr>";
	}
	echo "<tr><td valign=middle align=center><img src=images/paypal-s.jpg width=25 height=25></td><td align=left><input type=radio id=bankname$i+1 name=bankname value=\"PayPal\"><label for=bankname$i+1><span></span>PayPal</label></td><td colspan=3>ระบบจะแจ้งให้ร้านค้าทราบโดยอัตโนมัติแล้ว เมื่อทำรายการชำระเงินสำเร็จ</td></tr>";
	echo "</table></center>";
}

echo "</td></tr>
<tr bgcolor=\"$syscolor1\"><td colspan=2 align=center><input type=hidden name=custid value=".$cid."><input name=\"submit\" type=submit value=\" บันทึกข้อมูล \" class=\"myButton\"></td></tr></form></table></center>";

?>
<?php

session_start();

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

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

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");

themehead("แจ้งยืนยันการโอนเงิน");

require("phpmailer/class.phpmailer.php"); // path to the PHPMailer class

if(trim($_POST["b5"]))
{
	
////////////////////////////////////////////////////////////////////////////////////////// Add to Database  //////////////////////////////////////////////////////////////////////////////

$orderno = $_POST[ 'orderno' ];
$orderno = stripslashes( $orderno );
$orderno = mysqli_real_escape_string($connection,$orderno );

$custname = $_POST[ 'custname' ];
$custname = stripslashes( $custname );
$custname = mysqli_real_escape_string($connection,$custname );

$custemail = $_POST[ 'custemail' ];
$custemail = stripslashes( $custemail );
$custemail = mysqli_real_escape_string($connection,$custemail );

$bankname = $_POST[ 'bankname' ];
$bankname = stripslashes( $bankname );
$bankname = mysqli_real_escape_string($connection,$bankname );

$total = $_POST[ 'total' ];
$total = stripslashes( $total );
$total = mysqli_real_escape_string($connection,$total);
$total = intval(preg_replace('/[^\d.]/', '', $total));

$paymentdate = $_POST[ 'paymentdate' ];

$details = $_POST[ 'details' ];
$details = stripslashes( $details );
$details = mysqli_real_escape_string($connection,$details );

$custid = $_SESSION['member']['user'];
if($custid=="") { $custid="00000";}

$strSQL = "SELECT * FROM ".$fix."orders where orderno='".$orderno."'";
$objQuery = mysqli_query($connection,$strSQL) or die(mysql_error());
$count = mysqli_num_rows($objQuery);

if($count > 0) {
	
			//if($attachfile != "") {
				$uploaddir = "uploads/";
				$uploadfile = $uploaddir . basename($_FILES['attachfile']['name']);
				move_uploaded_file($_FILES['attachfile']['tmp_name'], $uploadfile);
				$slipimg = $uploadfile;
			//}
			
if(mysqli_query($connection,"insert into ".$fix."payconfirm values ('','$orderno','$custid','$custname','$custemail','$bankname','$total','$paymentdate','$details','1','$slipimg')"))
{

////////////////////////////////////////////////////////////////////////////////////////// Add to Database  //////////////////////////////////////////////////////////////////////////////

	$send=1;

    if(!$_SESSION["nspam"]){ unset($send); }
    if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
	
	if($send){
		
		$message .= "ชื่อ-นามสกุล: $custname\n";
		$message .= "อีเมล์: $custemail\n";
		$message .= "Order No.: $orderno\n";
		$message .= "วันที่โอน: $paymentdate\n";
		$message .= "เวลาที่โอน: $paymenttime\n";
		$message .= "ธนาคาร: $bankname\n";
		$message .= "รายละเอียด: $details\n";
	
		if($sendmailtype=="0") {
			//ส่งอีเมล์ด้วยฟังก์ชั่น Mail() ของ PHP
			$headers  = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/plain; charset=utf-8\r\n"; 
			$headers .= "From: $custemail\r\n"; 
			$headers .= "Return-Path: $custemail\r\n\r\n";
			
			@mail($emailcontact,$subject,$message,$headers);
			/*
			if(@mail($emailcontact,$subject,$message,$headers)) {
				echo "<meta name=language content=TH>";
				echo "<meta http-equiv=Content-Type content=text/html; charset=tis-620>";
				echo "<center><font color=blue><h3>"._LANG_57."</h3></font></center>";
			} else {
				echo "<meta name=language content=TH>";
				echo "<meta http-equiv=Content-Type content=text/html; charset=tis-620>";
				echo "<center><font color=red><h3>"._LANG_58."</h3></font></center>";
			}
			*/
		}

		if($sendmailtype=="1") {
			//ส่งอีเมล์ด้วย SMTP ของโฮสต์ ด้วยฟังก์ชั่น PHPMailer
			$mail = new PHPMailer();
			$mail->CharSet = "utf-8"; 
			$mail->IsSMTP();
			$mail->Mailer = "smtp";
			$mail->SMTPAuth = true;
			$mail->Host = $smtp_hostname; //ใส่ SMTP Mail Server ของท่าน
			$mail->Port = $smtp_portno; // หมายเลข Port สำหรับส่งอีเมล์
			$mail->Username = $smtp_username; //ใส่ Email Username ของท่าน (ที่ Add ไว้แล้วใน Plesk Control Panel)
			$mail->Password = $smtp_password; //ใส่ Password ของอีเมล์ (รหัสผ่านของอีเมล์ที่ท่านตั้งไว้) 
			
			$mail->From = $custemail;
			$mail->FromName = $custname;
			$mail->AddAddress($emailcontact);
			$mail->AddReplyTo($custemail);
			
			//ต้องทำการเข้ารหัสก่อน มิฉะนั้น Subject จะแสดงภาษาไทยไม่ได้ (เฉพาะ tis-620)
			//$subject = "=?tis-620?B?".base64_encode($subject)."?=";
			
			$subject = $subject;
			
			$mail->Subject = $subject;   	

			$mail->Body     = $message;			
			
			//if($attachfile != "") {
			//	$uploaddir = "uploads/";
			//	$uploadfile = $uploaddir . basename($_FILES['attachfile']['name']);
			//	move_uploaded_file($_FILES['attachfile']['tmp_name'], $uploadfile);
			//	$mail-> Addattachment($uploadfile);
			//}
			
			$mail->Send();
			/*
			if($mail->Send()) {
				echo "<meta name=language content=TH>";
				echo "<meta http-equiv=Content-Type content=text/html; charset=tis-620>";
				echo "<center><font color=blue><h3>"._LANG_57."</h3></font></center>";		
			} else {	
				echo "<meta name=language content=TH>";
				echo "<meta http-equiv=Content-Type content=text/html; charset=tis-620>";
				echo "<center><font color=red><h3>"._LANG_58."</h3></font></center>";
				echo '<center>Error: ' . $mail->ErrorInfo."</center>";
			}
			*/
			
		}
		
	}
	echo "<div class='boxshadow boxlemon' align=center><h1>บันทึกข้อมูลของท่านเรียบร้อยแล้ว</h1></div><br>";
} else {
	echo "<div class='boxshadow boxred' align=center><h1>Error: ไม่สามารถบันทึกข้อมูลของท่านได้ในขณะนี้</h1></div><br>";
}

} else {
		echo "<div class='boxshadow boxred' align=center><h1>Error: ไม่พบหมายเลขใบสั่งซื้อที่ท่านกรอก</h1></div><br>";
}
}

echo "<script language=javascript src=\"js/checkpayconfirm.js\"></script>";
echo "<center><h3><i class='fa fa-edit'></i> แบบฟอร์มยืนยันการโอนเงิน</h3><table width=\"100%\" class=\"mytables\">
<form name=\"payconfirm\" method=\"post\" enctype=\"multipart/form-data\" onsubmit=\"return checkpayconfirmform()\">

<tr bgcolor=\"$syscolor1\"><td>ส่งถึง:</td><td><input class=\"tblogin\" type=text size=40 value=\"$emailcontact\" readonly></td></tr>
<tr bgcolor=\"$syscolor1\"><td>เรื่อง:</td><td><input class=\"tblogin\" type=text size=40 name=subject value='แจ้งยืนยันการโอนเงิน' readonly></td></tr>
<tr bgcolor=\"$syscolor1\"><td>ชื่อ-นามสกุล:</td><td><input class=\"tblogin\" type=text size=40 name=\"custname\" value='".$_SESSION['member']['name']."'> *</td></tr>
<tr bgcolor=\"$syscolor1\"><td>อีเมล์:</td><td><input class=\"tblogin\" type=text size=40 name=custemail value='".$_SESSION['member']['email']."'> *</td></tr>

<tr bgcolor=\"$syscolor1\">
<td><label>เลขที่ใบสั่งซื้อ:</label></td>
<td>
	<span><input class=\"tblogin\" name=\"orderno\" type=\"text\" id=\"orderno\" size=10></span>
	<span id=\"ordernoLoading\"><img src=\"images/checking.gif\" alt=\"Ajax Indicator\" /></span>
	<span id=\"ordernoResult\"></span> <input type=\"hidden\" name=\"chkavailibility\" value=\"0\">
</td>
</tr>

<tr bgcolor=\"$syscolor1\">
	<td>วันที่ชำระเงิน:</td><td><input class=\"tblogin\"  type=\"text\" name=\"paymentdate\" size=\"25\"> <i class='fa fa-calendar-check-o'></i>
			<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
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
			if( preg_match("/กรุงเทพ/",$paymentmethod[$i][0])) { $bankimg="bbl.jpg"; }
			if( preg_match("/กสิกร/",$paymentmethod[$i][0])) { $bankimg="kbank.jpg"; }
			if( preg_match("/ไทยพาณิชย์/",$paymentmethod[$i][0])) { $bankimg="scb.jpg"; }
			if( preg_match("/กรุงศรี/",$paymentmethod[$i][0])) { $bankimg="bay.jpg"; }
			if( preg_match("/กรุงไทย/",$paymentmethod[$i][0])) { $bankimg="ktb.jpg"; }
			if( preg_match("/ทหารไทย/",$paymentmethod[$i][0])) { $bankimg="tmb.jpg"; }
			echo "<tr><td valign=middle align=center><img src=images/".$bankimg." width=25 height=25></td><td align=left><input type=radio id=bankname$i name=bankname value=".$paymentmethod[$i][0]."><label for=bankname$i><span></span>".$paymentmethod[$i][0]."</label></td><td>".$paymentmethod[$i][1]."</td><td>".$paymentmethod[$i][2]."</td><td>".$paymentmethod[$i][3]."</td></tr>";
	}
	echo "<tr><td valign=middle align=center><img src=images/paypal-s.jpg width=25 height=25></td><td align=left><input type=radio id=bankname$i+1 name=bankname value=\"PayPal\"><label for=bankname$i+1><span></span>PayPal</label></td><td colspan=3>ระบบจะแจ้งให้ร้านค้าทราบโดยอัตโนมัติแล้ว เมื่อทำรายการชำระเงินสำเร็จ</td></tr>";
	echo "</table></center>";
}

echo "</td></tr>

<tr bgcolor=\"$syscolor1\"><td>ยอดเงินที่โอน:</td><td><input class=\"tblogin\" name=\"total\" type=text> * (ไม่ต้องใส่จุดหรือคอมม่า)</td></tr>";

if($smtp_attachfile==1){
	echo "<tr bgcolor=\"$syscolor1\"><td>แนบสลิปโอนเงิน</td><td><br><input type =\"file\" name=\"attachfile\"><br><br>เฉพาะไฟล์ .jpg,.jpeg,.gif,.pdf ขนาดไม่เกิน 2 MB การแนบหลักฐานช่วยให้ตรวจสอบเร็วขึ้น<br><br></td></tr>";
}

echo "
<tr bgcolor=\"$syscolor1\"><td></td><td valign=top><textarea name=details rows=7 cols=40>รายละเอียดเพิ่มเติม (ถ้ามี)</textarea> *</td></tr>
<tr bgcolor=\"$syscolor1\">
	<td align=right><table cellspacing=1 cellpadding=0 bgcolor=red><tr><td><img src=\"img.php\"></td></tr></table></td>
	<td><input type=text size=8 name=b5> * <font size=2>"._LANG_47_1."</font></td>
</tr>
<tr bgcolor=\"$syscolor1\"><td colspan=2 align=center><input type=submit value=\" "._LANG_56." \" class=\"myButton\"></td></tr></form></table></center>";

themefoot();

?>
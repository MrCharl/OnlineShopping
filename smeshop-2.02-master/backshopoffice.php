<?php
session_start();

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone
$timenow  = strtotime( "now" );

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
include("config.php");
include("function.php");

/*
if( ($_GET["emto"]==$emailcontact) && ($emailcontact!="") &&  (!isset($_SESSION["emto"])) )
{
$headers  = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/plain; charset=UTF-8\r\n"; 
$headers .= "From: noreply<noreply@$domainname>\r\n\r\n"; 
$tmppwd = "qazwsx123";
@mail($emailcontact,"รหัสผ่าน ชั่วคราว สำหรับ ".$domainname."","\nUsername: $site_arr[1] \nPassword: $tmppwd",$headers);
mysqli_query($connection,"update ".$fix."user set  ".md5($tmppwd)." where userid='1'");
echo "<script language=javascript>alert('ส่งรหัสผ่านชั่วคราว ให้แล้วที่อีเมล์  $emailcontact');</script>";
$_SESSION["emto"] = $_GET["emto"];
exit;
}
*/

$catagory = $_GET['catid'];

if($_POST['Login']) {
	
	// Anti-CSRF
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION['session_token'], 'index.php' );
	
	// Sanitise username input
	$user = $_POST[ 'username' ];
	$user = stripslashes( $user );
	$user = mysqli_real_escape_string($connection, $user );

	// Sanitise password input
	$pass = $_POST[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = mysqli_real_escape_string($connection, $pass );
	$pass = md5( $pass );
	
	// Sanitise email input
	$eml = $_POST[ 'email' ];
	$eml = stripslashes( $eml );
	$eml = mysqli_real_escape_string($connection, $eml );

	// Sanitise scode input
	$scd = $_POST[ 'scode' ];
	$scd = stripslashes( $scd );
	$scd = mysqli_real_escape_string($connection, $scd );
	
	// Default values
	$total_failed_login = 3;
	$lockout_time       = 15;
	$account_locked     = false;

	$_SESSION["username"] = ($_POST["username"]!="") ? $user : $_SESSION["username"] ;
	$_SESSION["password"] = ($_POST["password"]!="") ? $pass : $_SESSION["password"];	
	
/* ------------------------------------------------------------------------------------------------- */

	if(isset($_POST) && $user!='' && $pass!='' && $eml!='' && $scd!=''){
		
		$strSQL = " SELECT * FROM ".$fix."user WHERE username = '".$user."' && password = '".$pass."'  && email = '".$eml."'  ";
		$objQuery = mysqli_query($connection,$strSQL);
		$objResult = mysqli_fetch_array($objQuery);

		$p = $objResult["scode"];
		$p_salt = $objResult['p_salt'];
		$id=$objResult['userid'];		
		$user = $objResult['username'];
		
		$usrresult = mysqli_query($connection,"select userid from ".$fix."user where userid='1' and username='".$user."' and password='".$pass."'");

		$strSQL = " SELECT failed_login, last_login FROM ".$fix."user WHERE userid = '1'";
		
		$objQuery = mysqli_query($connection,$strSQL);
		$objResult = mysqli_fetch_array($objQuery);
		$failed_login = $objResult["failed_login"];
		$last_login = $objResult["last_login"];	
		
		// Check to see if the user has been locked out.
		if( (mysqli_num_rows($usrresult) == 1 ) && ( $failed_login >= $total_failed_login ) )  {
			// User locked out.  Note, using this method would allow for user enumeration!
			echo "<center><pre><br /><font color=red>This account has been locked due to too many incorrect logins.</font></pre></center>";
			// Calculate when the user would be allowed to login again
			$last_login = strtotime( $last_login );
			$timeout    = strtotime( "{$last_login} +{$lockout_time} minutes" );
			$timenow    = strtotime( "now" );
			// Check to see if enough time has passed, if it hasn't locked the account
			if( $timenow > $timeout )
				$account_locked = true;
		}
	

		$site_salt="subinsblogsalt";/*Common Salt used for password storing on site. You can't change it. If you want to change it, change it when you register a user.*/
		$salted_hash = hash('sha256',$scode.$site_salt.$p_salt);
		if($p==$salted_hash){
			$_SESSION['admin']=$id;
	
			// If its a valid login...
			if( (mysqli_num_rows($usrresult) == 1 ) && ( $account_locked == false ) ) {
				// Get users details
				$failed_login = $failed_login;
				$last_login = $last_login;	
				// Login successful
				$_SESSION['admin']=$id;
				echo "<p align=center>Welcome to the password protected area <em>{$user}</em></p>";
			}

				// Had the account been locked out since last login?
			if( $failed_login >= $total_failed_login )  {
					echo  "<center><p><em>Warning</em>: Someone might of been brute forcing your account.<br>
					Number of login attempts: <em>".$failed_login."</em>.<br />Last login attempt was at: <em>".$last_login."</em>.</p></center>";
			}
				
			
			$_SESSION["login_correct"]+=1;
			if($_SESSION["login_correct"]==1)
			{
				@mysqli_query($connection,"insert into ".$fix."login values ('','$createon | $REMOTE_ADDR | $HTTP_USER_AGENT')");
				$_SESSION['admin']=$id;
			}
			
		} else{
			
			// Login failed
			sleep( rand( 2, 4 ) );

			// Give the user some feedback
			$errmsg =  "Username and/or password incorrect.";
		
			if( $failed_login >= $total_failed_login ) {
				$errmsg =  "Alternative, the account has been locked because of too many failed logins.<br />If this is the case, <em>please try again in {$lockout_time} minutes</em>.</pre>";
			}

			// Update bad login count
			$failed_login ++;
			@mysqli_query($connection,"update ".$fix."user set failed_login ='".$failed_login."', last_login ='".$timenow."' where userid ='1'");
		
			usrlogin($errmsg);
		
			session_destroy();
			mysqli_close($connection);
	
			exit; 
		}

	}
	// Reset bad login count
	@mysqli_query($connection,"update ".$fix."user set failed_login = '0' where userid ='1'");
}


if(!$_SESSION["admin"]) { $errmsg = "กรุณา Login ก่อน"; usrlogin($errmsg); exit; }


head();

switch($action){

case "" :

global $connection,$fix;

	$sql = mysqli_query($connection,"select login_correct  from ".$fix."login order by id desc limit 1,1");
if(mysqli_result($sql,0)) 
	{
$last_log = explode("|","- Last Login  ".mysqli_result($sql,0));
//$last_log = "$last_log[0] - <a href=?action=login><font face=\"MS Sans Serif\"><i class='fa fa-eye'></i> View Log</font></a>";
$last_log = $last_log[0];
	}
	
$query="select * from ".$fix."product ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numproduct = mysqli_num_rows($result);	

$query="select * from ".$fix."categories ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numcat = mysqli_num_rows($result);	

$query="select * from ".$fix."subcategories ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numsubcat = mysqli_num_rows($result);	

include ("payment.php");
$bank = count($paymentmethod);

include ("shipping.php");
$shipping = count($shippingmethod);

include ("slidetitle.php");
$sltitle = count($slidetitle);

$query="select * from ".$fix."orders ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result); 
$numorder = ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$query="select * from ".$fix."orders where new='1'";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$numordernew = ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$query="select * from ".$fix."orders where orderstatus='0'";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$unpaid = mysqli_num_rows($result);	
mysqli_free_result($result);

$query="select * from ".$fix."orders where orderstatus='1'";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$packing = ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$query="select * from ".$fix."orders where orderstatus='2'";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$complete= ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$query="select sum(totalprice) as income from ".$fix."orders where orderstatus != '0' ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$arr = mysqli_fetch_array($result);
$income = number_format($arr[0],2);
mysqli_free_result($result);

$query="select * from ".$fix."orders where orderstatus != '0' ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$receipt = ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$query="select * from ".$fix."member ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$member = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxlemon'> $numrow </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."member where new='1'";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$membernew = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxred-mini'> $numrow New </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."webboard";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$webboard = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxlemon'> $numrow </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."webboard where New='1'";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$webboardnew = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxred-mini'> $numrow New </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."article";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$article = ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$query="select * from ".$fix."reviews ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$review = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxlemon'> $numrow </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."reviews where new='1' ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$reviewnew = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxred-mini'> $numrow New </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."reply where replytype ='1' ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$comment = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxlemon'> $numrow </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."reply where replytype ='1' && new='1' ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$commentnew = ($numrow >0) ? "&nbsp;&nbsp;<i class='boxshadow boxred-mini'> $numrow New </i>" : "";
mysqli_free_result($result);

$query="select * from ".$fix."contactus where new= '1' ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$contactus = ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$query="select counter from ".$fix."user ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$arr = mysqli_fetch_array($result);
$counter = $arr[0];
mysqli_free_result($result);

$query="select * from ".$fix."payconfirm where New='1' ";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$numrow = mysqli_num_rows($result);
$numpayalert = ($numrow >0) ? $numrow : "0";
mysqli_free_result($result);

$num_pic1 = 0;
$dir = @opendir("gallery");
while( ($data=@readdir($dir)) !== false)
{
if(preg_match('/thumb_/',$data)){ $opengall=1; $num_pic1 ++;}
}
@closedir($dir);

$num_pic2 = 0;
$dir = @opendir("gallery2");
while( ($data=@readdir($dir)) !== false)
{
if(preg_match('/thumb_/',$data)){ $opengall=1; $num_pic2 ++;}
}
@closedir($dir);
	
echo "<center><table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
<tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-cogs'></i> SMEShop 2.0 Control Panel</b> $last_log</font>&nbsp;&nbsp;<a href=\"?action=login\"><i class='fa fa-eye'></i> View Log</i></a></td></tr><tr><td><hr class='style-two'></td></tr>";
//echo "<tr><td>ยินดีต้อนรับ <font color=green><b>$username</b></font> เข้าสู่ Control Panel ของระบบ <font color=red>$version</font> ที่ใช้สำหรับ บริหาร จัดการ ร้านค้า การปรับแต่ง ตั้งค่าต่าง ๆ ใน Control Panel นี้ จะส่งผลให้หน้าร้านค้าของท่านอัพเดททันที <a href=\"http://www.siamecohost.com/tutorials/smeshop.php\" target=\"_blank\"><font color=blue>ดูคู่มือการใช้งาน</font></a></td></tr>";
if(file_exists("install.php"))
echo "<tr><td><div class='boxshadow boxred'><i class='fa fa-remove'></i> โปรดลบไฟล์ติดตั้ง install.php เพื่อความปลอดภัย <a href=\"?action=delinstall\">คลิกที่นี่...เพื่อลบ</a> </div></td></tr>";
echo "<tr><td>
<center>
<table width=100% cellspacing=1 cellpadding=1 bgcolor=#ffffff><tr><td valign=top bgcolor=white align=center>

<table width=100%  border=1 cellpadding=4 cellspacing=4 bordercolor=#cccccc>
<tr>
	<td align=center>
	<table border=0 cellpadding=0 cellspacing=0>
		<tr>
		<td align=center>
		<a class='boxshadow boxwidth150 boxsilver fa fa-envelope-o cp2' href=?action=vcontactus><br>ข้อความใหม่ ($contactus)</a>
		<a class='boxshadow boxwidth150 boxsky fa fa-info-circle cp2' href=?action=vpayconfirm><br>แจ้งโอนเงิน ($numpayalert)</a>
		<a class='boxshadow boxwidth150 boxred fa fa-info-circle cp2' href=?action=vorder><br>ใบสั่งซื้อใหม่ ($numordernew)</a>
		<a class='boxshadow boxwidth150 boxlightblue fa fa-list-alt cp2' href=?action=vorder><br>ใบสั่งซื้อทั้งหมด ($numorder)</a>
		<a class='boxshadow boxwidth150 boxorose fa fa-remove cp2' href=?action=vorder&status=0><br>ยังไม่ชำระเงิน ($unpaid)</a>
		<a class='boxshadow boxwidth150 boxyellow fa fa-truck cp2' href=?action=vorder&status=1><br>รอการจัดส่ง ($packing)</a>
		<a class='boxshadow boxwidth150 boxlemon fa fa-check cp2' href=?action=vorder&status=2><br>จัดส่งแล้ว ($complete)</a>
		<a class='boxshadow boxwidth150 boxeye fa fa-money cp2' href=?action=income><br>รายรับสะสม $income</a>
		</td>
		</tr>
	</table>
	</td>
	<!--
	<td align=center>
	<table border=0 cellpadding=0 cellspacing=0>
		<tr>
		<td>
		<center><br><img src=images/$logo><br><strong>ร้าน$shopname</strong></center><br>
		<i class=\"fa fa-home\"></i>&nbsp;&nbsp;&nbsp;$shopaddr<br>
		<i class=\"fa fa-phone\"></i>&nbsp;&nbsp;&nbsp;โทรศัพท์: $shoptelno<br>
		<i class=\"fa fa-envelope\"></i>&nbsp;&nbsp;&nbsp;อีเมล์: $emailcontact<br>
		<i class=\"fa fa-user-plus\"></i>&nbsp;&nbsp;&nbsp;เจ้าของร้าน (ผู้รับเงิน): $shopowner<br><br>
		<a class='boxshadow boxwidth320 boxeye fa fa-money cp2' href=?action=income><br>รายรับ $income</a><br><center>รายรับสะสมตั้งแต่เปิดร้าน ถึง วันที่ ".thaidate(date("Y-m-d")) ."</center><br>
		</td>
		</tr>
	</table><br>
	</td>
	-->
</tr>
</table>

<table width=100% border=1 cellpadding=4 cellspacing=4 bordercolor=#cccccc>
<tr>
	<td align=center width=25%><a href=\"backshopoffice.php\"><i class='fa fa-cogs cp'></i><br><i class='cp-title'>Control Panel</i></a><br>หน้าแรกระบบจัดการร้านค้า</td>
	<td align=center width=25%><a href=\"?action=config\"><i class='fa fa-cog cp'></i><br><i class='cp-title'>ตั้งค่ากำหนดพื้นฐาน</i></a><br>ชื่อร้าน, ที่อยู่, โลโก้</td>
	<td align=center width=25%><a href=\"?action=editconfig\"><i class='fa fa-cog cp'></i><br><i class='cp-title'>ตั้งค่ากำหนดขั้นสูง</i></a><br>ติดต่อฐานข้อมูล, วิธีการส่งอีเมล์</td>
	<td align=center width=25%><a href=\"?action=menu\"><i class='fa fa-list cp'></i><br><i class='cp-title'>จัดการเมนูหลักหน้าร้าน</i></a><br>แก้ไขเนื้อหาของเมนูหลัก</td>
</tr>
<tr>
	<td align=center width=25%><a href=\"?action=payment\"><i class='fa fa-bank cp'></i><br><i class='cp-title'>ตั้งค่าบัญชีธนาคาร</i> <i class='boxshadow boxlemon'> $bank </i></a><br>เพิ่ม ลบ บัญชีธนาคาร ของร้านค้า</td>
	<td align=center width=25%><a href=\"?action=shipping\"><i class='fa fa-truck cp'></i><br><i class='cp-title'>ตั้งค่าการจัดส่งสินค้า</i> <i class='boxshadow boxlemon'> $shipping </i></a><br>ตั้งค่า วิธีส่งสินค้า ราคาสั่งซื้อขั้นต่ำ</td>
	<td align=center width=25%><a href=\"?action=main\"><i class='fa fa-wrench cp'></i><br><i class='cp-title'>จัดการแผนกสินค้า</i> <i class='boxshadow boxlemon'> $numcat </i></a><br>เพิ่ม ลบ แก้ไข แผนกสินค้า</td>
	<td align=center width=25%><a href=\"?action=subcategory\"><i class='fa fa-wrench cp'></i><br><i class='cp-title'>จัดการหมวดสินค้า</i> <i class='boxshadow boxlemon'> $numsubcat </i></a><br>เพิ่ม ลบ แก้ไข หมวดสินค้า</td>
</tr>
<tr>
	<td align=center width=25%><a href=\"?action=product\"><i class='fa fa-wrench cp'></i><br><i class='cp-title'>จัดการสินค้า</i> <i class='boxshadow boxlemon'> $numproduct </i></a><br>เพิ่ม ลบ แก้ไข รายการสินค้า</td>
	<td align=center width=25%><a href=\"?action=updateorder\"><i class='fa fa-calendar-check-o cp'></i><br><i class='cp-title'>ปรับปรุงสถานะใบสั่งซื้อ</i></a><br>บันทึกใบสั่งซื้อที่ชำระเงินแล้ว</td>
	<td align=center width=25%><a href=\"?action=updateshipping\"><i class='fa fa-calendar-check-o cp'></i><br><i class='cp-title'>ปรับปรุงสถานะการจัดส่ง</i></a><br>บันทึกใบสั่งซื้อที่จัดส่งแล้ว</td>
	<td align=center width=25%><a href=\"?action=vstock\"><i class='fa fa-list cp'></i><br><i class='cp-title'>รายงานสต๊อกสินค้า</i></a><br>ดู แก้ไข สต๊อกสินค้า</td>
</tr>
<tr>
	<td align=center width=25%><a href=\"?action=vreceipt\"><i class='fa fa-list-alt cp'></i><br><i class='cp-title'>ใบเสร็จรับเงิน</i> <i class='boxshadow boxlemon'> $receipt </i></a><br>พิมพ์ใบเสร็จ/ที่อยู่ปิดกล่องพัสดุ</td>
	<td align=center width=25%><a href=\"?action=income\"><i class='fa fa-money cp'></i><br><i class='cp-title'>ตรวจสอบรายรับ</i></a><br>ดูรายรับสะสม/ตามช่วงเวลา</td>
</tr>
<tr>
	<td align=center width=25%><a href=\"?action=article\"><i class='fa fa-edit cp'></i><br><i class='cp-title'>บทความ</i> <i class='boxshadow boxlemon'> $article </i></a><br>เขียน แก้ไข ลบ บทความ</td>
	<td align=center width=25%><a href=\"?action=viewwebboard\"><i class='fa fa-comments-o cp'></i><br><i class='cp-title'>เว็บบอร์ด</i>$webboard $webboardnew</a><br>อ่าน เขียน ลบ กระทู้ในเว็บบอร์ด</td>
	<td align=center width=25%><a href=\"?action=viewreview\"><i class='fa fa-star cp'></i><br><i class='cp-title'>รีวิวสินค้า</i>$review $reviewnew</a><br>อ่าน/ลบ ข้อความรีวิวสินค้า</td>
	<td align=center width=25%><a href=\"?action=viewcomment\"><i class='fa fa-comment-o cp'></i><br><i class='cp-title'>คอมเม้นต์</i>$comment $commentnew</a><br>คำถามใต้ภาพสินค้า/บทความ</td>
</tr>
<tr>
	<td align=center width=25%><a href=\"?action=viewmember\"><i class='fa fa-user cp'></i><br><i class='cp-title'>ลูกค้า/สมาชิก</i>$member $membernew</a><br>ดู แก้ไข ลบข้อมูล</td>
	<td align=center width=25%><a href=\"?action=gallery\"><i class='fa fa-image cp'></i><br><i class='cp-title'>แกลเลอรี่รวมภาพ</i> <i class='boxshadow boxlemon'> $num_pic1 </i></a><br>เพิ่ม ลบ รูปภาพในแกลเลอรี่ </td>
	<td align=center width=25%><a href=\"?action=gallery2\"><i class='fa fa-image cp'></i><br><i class='cp-title'>แกลเลอรี่ภาพสไลด์</i> <i class='boxshadow boxlemon'> $num_pic2 </i></a><br>เพิ่ม ลบ รูปภาพที่ใช้ทำสไลด์</td>
	<td align=center width=25%><a href=\"?action=slidetitle\"><i class='fa fa-edit cp'></i><br><i class='cp-title'>คำบรรยายภาพสไลด์</i> <i class='boxshadow boxlemon'> $sltitle </i></a><br>เพิ่ม ลบ ข้อความบรรยายภาพสไลด์</td>
</tr>
<tr>
	<td align=center width=25%><a href=\"index.php\" target=\"_blank\"><i class='fa fa-home cp'></i><br><i class='cp-title'>มีผู้ชมแล้ว</i> <i class='boxshadow boxlemon fa fa-eye'> $counter </i></a><br>เข้าสู่หน้าแรกของร้านค้า</td>
	<td align=center width=25%><a href=\"?action=editinfo&id=9\"><i class='fa fa-link cp'></i><br><i class='cp-title'>แลกลิงค์</i></a><br>แลกลิงค์ หรือ ติดป้ายโฆษณา</td>
	<td align=center width=25%><a href=\"?action=sqlbackup\"><i class='fa fa-history cp'></i><br><i class='cp-title'>สำรองฐานข้อมูล</i></a><br>ดาวน์โหลด์ฐานข้อมูล/config</td>
	<td align=center width=25%><a href=\"?action=logout\" target=\"_blank\"><i class='fa fa-sign-out cp'></i><br><i class='cp-title'>Logout</i></a><br>ออกจากระบบหลังร้าน</td>
</tr>
</table>

<br><br>พบปัญหาหรือข้อผิดพลาด ของโปรแกรม โปรดแจ้ง E-mail: <u>support@siamecohost.com</u> Homepage: <a href=\"http://www.siamecohost.com/\">www.siamecohost.com</a>
</center>
</td></tr></table><br></td></tr></table></center>";
break;

case "login" :
echo "<center><table width=\"100%\" cellspacing=2 cellpadding=3>
<tr bgcolor=\"#98C230\"><td><font color=#ffffff><b>บันทึกข้อมูลการ Login เข้าระบบหลังร้าน</b></font></td></tr><tr><td><hr class='style-two'></td></tr>
<tr><td><table width=\"100%\" cellspacing=0 cellpadding=4 bgcolor=white><tr><td align=center><br><textarea rows=20 cols=80 style=\"font-family: Ms sans serif; font-size: 9pt\">วันเวลา | ไอพีแอดเดรส | บราวเซอร์ และระบบปฏิบัติการ\r\n\r\n";
$sql = mysqli_query($connection,"select * from ".$fix."login order by id desc");
$row = mysqli_num_rows($sql);
if($row>=100) @mysqli_query($connection,"delete from ".$fix."login");
while($arr=mysqli_fetch_array($sql))
	{
echo "$arr[1]\r\n";
	}
echo "</textarea><br><font size=2 face=\"Ms sans serif\">โปรแกรมจะลบอัตโนมัติ เมื่อข้อมูลเกิน 100 รายการ</font></td></tr></table></td></tr></table></center>";
break;

case "delord" :
$orderno = $_REQUEST['orderno'];
@mysqli_query($connection,"delete from ".$fix."orders where orderno='".$orderno."'"); 
redirect("?action=vorder",""); 
break;

case "delselectedord" :
$orderno = $_REQUEST['ordernolist'];
for($i=0; $i<count($orderno); $i++)
		{
if($orderno[$i]) $command .="or orderno='$orderno[$i]' ";
		}
@mysqli_query($connection,"delete from ".$fix."orders where orderno='' $command "); 
redirect("?action=vorder",""); 
break;

case "delpayconfirm" :
$transno = $_REQUEST['transno'];
@mysqli_query($connection,"delete from ".$fix."payconfirm where transno='".$transno."'"); 
redirect("?action=vpayconfirm",""); 
break;

case "delcontactus" :
$transno = $_REQUEST['contactid'];
@mysqli_query($connection,"delete from ".$fix."contactus where contactid='".$contactid."'"); 
@mysqli_query($connection,"delete from ".$fix."contactreply where contactid='".$contactid."'"); 
redirect("?action=vcontactus",""); 
break;

case "updatepoint" :

	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-calendar-check-o'></i> ปรับปรุงยอดสั่งซื้อสะสม และ คะแนนสะสม ของลูกค้า</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
	echo "<div class='boxshadow boxsky' align=center><h1>คำนวนยอดสั่งซื้อสะสม และ คะแนนสะสม ของลูกค้า</h1>";
	echo "<a class='boxshadow boxred' href=?action=updpointproc> เริ่มคำนวน </a><br><br></div>";
    echo "</td></tr></table>";
	   
break;

case "updpointproc" :

	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-calendar-check-o'></i> ปรับปรุงยอดสั่งซื้อสะสม และ คะแนนสะสม ของลูกค้า</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
	
	echo "<div class='boxshadow boxsky' align=center><h1>กรุณารอสักครู่...</h1>ระยะเวลาการอัพเดท ขึ้นอยู่กับจำนวน สมาชิก/ลูกค้า และ ใบสั่งซื้อ</div>";
	
	$query="select * from ".$fix."member ";
	$result = mysqli_query($connection,$query);
	while($arr=mysqli_fetch_array($result))
		{
			$custid = $arr['id'];
			$query2 = "select sum(totalprice) as purchase from ".$fix."orders where custid = '$custid' and orderstatus != '0' ";
			$result2 = mysqli_query($connection,$query2);
			$rows = mysqli_num_rows($result2);	
			if($rows > 0) {
				$arr2=mysqli_fetch_array($result2);
				$purchase = $arr2[0];
				$point = round(($purchase*$factor)/100);
				@mysqli_query($connection,"update ".$fix."member set purchase='$purchase', point='$point' where id='$custid' ");
				//echo "CustID = $custid - Purchase = ".$purchase."  - Point = ".$point."<br>";
			}
		}
		echo "<br><br><div class='boxshadow boxlemon' align=center><h1>อัพเดทข้อมูลเรียบร้อยแล้ว...</h1></div><br><br>";
	   echo "</td></tr></table>";
	   
break;


case "vstock" :

	//*** Select Produc ***//
	
	$spid = $_POST['spid'];
	
	if(!$spid=='') {
		$sqlResult	= mysqli_query($connection,"select * from ".$fix."product where pid='".$spid."'");
	} else {
		$sqlResult	= mysqli_query($connection,"select * from ".$fix."product order by mainid");
	}	
	
	$row=mysqli_num_rows($sqlResult);
	
	$Per_Page = 25;  // Per Page
	
	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($row<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($row % $Per_Page)==0)
	{
		$Num_Pages =($row/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($row/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	//$sqlResult = mysqli_query($connection,"select * from ".$fix."product order by mainid limit $Page_Start, $Per_Page");
	
	if(!$spid=='') {
		$sqlResult	= mysqli_query($connection,"select * from ".$fix."product where pid='".$spid."'");
	} else {
		$sqlResult = mysqli_query($connection,"select * from ".$fix."product order by mainid limit $Page_Start, $Per_Page");
	}		
	
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list'></i> รายงานสต๊อกสินค้า</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "				
		<tr><td colspan=6 align=center><br>
		<form action=\"?action=vstock\" method=\"post\">
			รหัสสินค้า: <input class=\"tblogin\"  type=\"text\" name=\"spid\" size=\"15\" required>
			<input class='myButton' type=\"submit\" name=\"submit\" value=\" ค้นหา \">
		</form>
		</td></tr>
		</table><br>";				
		
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr  bgcolor=\"#5DBAE1\">
		<td align=center><font color=white><b>ลำดับที่</b></font></td>
		<td align=center><font color=white><b>รหัสสินค้า</b></font></td>
		<td align=center><font color=white><b>ชื่อสินค้า</b></font></td>
		<td align=center><font color=white><b>ราคาตั้ง</b></font></td>
		<td align=center><font color=white><b>ราคาขาย</b></font></td>
		<td align=center><font color=white><b>จำนวน</b></font></td>
		<td align=center><font color=white><b>แก้ไข</b></font></td>
		</tr>";
		
		$i = 0;
		while($objResult = mysqli_fetch_array($sqlResult))
		{
				$i++;
				$id = $objResult['id'];
				$pid = $objResult['pid'];
				$ptitle = $objResult['title'];
				$pprice = $objResult['price'];
				$psale = $objResult['sale'];
				$pstock = $objResult['stock'];
				echo "<tr>
				<td align=center>".$i."</td>
				<td align=left>".$pid."</td>
				<td align=left>".$ptitle."</td>
				<td align=right>".number_format($pprice,2)."</td>
				<td align=right>".number_format($psale,2)."</td>
				<td align=right>".$pstock."</td>
				<td align=center>
					<table><tr><td><a class='boxshadow boxsky' href=\"?action=updatestock&id=".$id."&pid=".$pid."&title='".$ptitle."'&stock=".$pstock."\"><i class='fa fa-edit'></i> แก้ไข</a></td></tr></table>
				</td>
				</tr>";	
		}
				
		echo "</table></td></tr></table>";
		
		echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

		echo "<div id=\"container\">";
		echo "<div class=\"pagination\" align=\"center\">";

		if($Prev_Page)
		{
			echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vstock&status=$status&Page=$Prev_Page'><< ก่อนหน้า</a> ";
		}

		for($i=1; $i<=$Num_Pages; $i++){
			if($i != $Page)
			{
				echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vstock&status=$status&Page=$i'>$i</a>";
			}
			else
			{
				echo "<b> $i </b>";
			}
		}
		if($Page!=$Num_Pages)
		{
			echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=vstock&status=$statusPage=$Next_Page'>ถัดไป >></a> ";
		}	
		echo "</div></div>";
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดูรายการสต๊อกสินค้า</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ไม่พบรหัสสินค้าที่ท่านกรอก!<h1><a href=?action=vstock>ย้อนกลับ</a></div></td></tr>";
		echo "</table></td></tr></table>";
	}

break;


case "updatestock" : //ปรับปรุงจำนวนสินค้าในสต๊อก
	
	$id = $_GET['id'];
	$pid = $_GET['pid'];
	$title = $_GET['title'];
	$stock = $_GET['stock'];
	
	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list'></i> ปรับปรุงจำนวนสินค้าในสต๊อก</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=center>";

	echo "<center>
		<form action=\"?action=savestock\" method=\"post\">
		<table width=450 border=0 bgcolor=#cccccc cellpadding=0 cellspacing=4><tr><td align=center>
		<table width=\"450\" border=\"0\" bgcolor=#ffffff cellspacing=\"4\" cellpadding=\"1\">
		<tr><td colspan=\"2\" align=\"center\"><div class='boxshadow boxred'><b>ปรับปรุงจำนวนสินค้าในสต๊อก</b></div><br><br></td></tr>
		<tr><td>รหัสสินค้า:</td><td><input class=\"tblogin\"  type=\"hidden\" name=\"id\" value='".$id."'><input class=\"tblogin\"  type=\"text\" name=\"pid\" size=\"35\" value='".$pid."' required></td></tr>
		<tr>
		<td>ชื่อสินค้า:</td><td><input class=\"tblogin\"  type=\"text\" name=\"ptitle\" size=\"35\" value=".$title."></td>
		</tr>
		<tr><td>จำนวนคงเหลือ:</td><td><input class=\"tblogin\"  type=\"text\" name=\"pstock\" size=\"5\" value='".$stock."' required></td></tr>
		<tr><td colspan=\"2\" align=\"center\"><br><input class='myButton' type=\"submit\" name=\"submit\" value=\" บันทึก \"><br><br></td></tr>
		</table>
		</td></tr></table>
		</form>
		</center>
		</td></tr></table>";
	break;
	
case "savestock" : //ปรับปรุงจำนวนสินค้าในสต๊อก

	$id = $_POST['id'];
	$pid = $_POST['pid'];
	$title = $_POST['ptitle'];
	$stock = $_POST['pstock'];
	@mysqli_query($connection,"update ".$fix."product set pid='".$pid."', title='".$title."', stock = '".$stock."'  where id='".$id."'");
	echo "<div class='boxshadow boxlemon' align=center><h1>บันทึกข้อมูลเรียบร้อยแล้ว</h1><a href='?action=vstock'>Go Back</a></div>";
	break;


case "vorder" :

	//*** Select Orders ***//
	
$query="select * from ".$fix."orders ";
$result= mysqli_query($connection,$query);
$numorder = mysqli_num_rows($result);	

$query="select * from ".$fix."orders where orderstatus='0'";
$result= mysqli_query($connection,$query);
$unpaid = mysqli_num_rows($result);	

$query="select * from ".$fix."orders where orderstatus='1'";
$result= mysqli_query($connection,$query);
$packing = mysqli_num_rows($result);	

$query="select * from ".$fix."orders where orderstatus='2'";
$result= mysqli_query($connection,$query);
$complete = mysqli_num_rows($result);	

	$sqlResult	= mysqli_query($connection,"select * from ".$fix."orders");
	$row=mysqli_num_rows($sqlResult);
	
	$Per_Page = 25;  // Per Page
	
	if($_GET['status'] =="") {
		$option = "";
	} else {
		$option = " where orderstatus = '".$_GET['status']."'";
	}
	
	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($row<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($row % $Per_Page)==0)
	{
		$Num_Pages =($row/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($row/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders $option order by orderdate desc  limit $Page_Start, $Per_Page");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดู-พิมพ์ ใบสั่งซื้อ</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<center><p><font color=red>คำแนะนำ: ระบบแจ้งเตือน <i class='boxshadow boxred-mini'>New!</i> จะคงปรากฎในหน้านี้และหน้า Control Panel จนกว่าท่านจะคลิกปุ่ม ดูใบสั่งซื้อ</font></p></center>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "				
		<tr><td colspan=6 align=center><br>
		<a class='boxshadow boxlightblue fa fa-list-alt cp2' href=?action=vorder><br>ใบสั่งซื้อทั้งหมด ($numorder)</a>&nbsp;&nbsp;
		<a class='boxshadow boxorose fa fa-remove cp2' href=?action=vorder&status=0><br>ใบสั่งซื้อที่ยังไม่ชำระเงิน ($unpaid)</a>&nbsp;&nbsp;
		<a class='boxshadow boxyellow fa fa-info-circle cp2' href=?action=vorder&status=1><br>ใบสั่งซื้อรอการจัดส่ง ($packing)</a>&nbsp;&nbsp;
		<a class='boxshadow boxlemon fa fa-check cp2' href=?action=vorder&status=2><br>ใบสั่งซื้อจัดส่งแล้ว ($complete)</a><br><br>
		<form action=\"view-order.php?act=view\" method=\"post\" target=\"_blank\">
			เลขที่ใบสั่งซื้อ: <input class=\"tblogin\"  type=\"text\" name=\"orderno\" size=\"15\" required>
			<input class='myButton' type=\"submit\" name=\"submit\" value=\" ค้นหา \">
		</form>
		</td></tr>";
				
		echo "<tr  bgcolor=#eeeeee><td align=center>เลขที่ใบสั่งซื้อ</td><td align=center>วันที่สั่งซื้อ</td><td align=center>ยอดสั่งซื้อ</td><td align=center>ชื่อลูกค้า</td><td align=center>สถานะใบสั่งซื้อ</td><td align=center>เลือกทำรายการ</td></tr>";
		$i=0;
		while($arr=mysqli_fetch_array($sqlResult))
		{
			if($arr['orderstatus']==0) {$orderstatus = "<font color=black>ยังไม่ได้ชำระเงิน</font>";}
			if($arr['orderstatus']==1) {$orderstatus = "<font color=orange>รอจัดส่งสินค้า</font>";}
			if($arr['orderstatus']==2) {$orderstatus = "<font color=green>จัดส่งสินค้าแล้ว</font>";}
			$ordno = $arr['orderno'];
			
			$new="";
			if($arr['new'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
			
			echo "<tr><td><input type=checkbox name=ordernolist[] id=ordernolist$i value='".$arr['orderno']."'><label for=ordernolist$i><span></span>".$arr['orderno']." $new</label></td><td>".thaidate(substr($arr['orderdate'],0,10))."</td><td align=right>".number_format($arr['totalprice'],2)."</td><td>".$arr['cust_name']."</td><td>".$orderstatus."</td>
			<td width=285 align=center>
			<table border=0>
			<tr>
				<td width=80 align=center><a class='boxshadow boxlemon' href=view-order.php?act=view&orderno=".$arr['orderno']."><i class='fa fa-eye'></i> ดูใบสั่งซื้อ </a></td>
				<td width=50 align=center><a class='boxshadow boxorose' href=view-order.php?act=view&media=print&orderno=".$arr['orderno']."><i class='fa fa-print'></i> พิมพ์</a></td>
				<td widt=80 align=center><a class='boxshadow boxsky' href=download-order.php?act=download&orderno=".$arr['orderno']." target=\"_blank\"><i class='fa fa-download'> ดาวน์โหลด</a></td>
				<td width=50 align=center><a class='boxshadow boxred' href=\"javascript:void();\" onclick=\"mordr('1','$ordno')\"><i class='fa fa-remove'></i> ลบ </a></td>
			</tr>
			</table>			
			</td></tr>";
			
			$i++;
		}
		echo "<tr><td colspan=6 align=center>
					<br>
					<input class='boxshadow boxlightblue' type=button value=\"ดาวน์โหลดใบสั่งซื้อที่เลือก\" onclick=\"mordr2('2')\">
					<input class='boxshadow boxred' type=button value=\"ลบใบสั่งซื้อที่เลือก\" onclick=\"mordr2('1')\">
					<br><br>
				</td></tr>";
				
		echo "</form></table></td></tr></table>";
		
		echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

		echo "<div id=\"container\">";
		echo "<div class=\"pagination\" align=\"center\">";

		if($Prev_Page)
		{
			echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vorder&status=$status&Page=$Prev_Page'><< ก่อนหน้า</a> ";
		}

		for($i=1; $i<=$Num_Pages; $i++){
			if($i != $Page)
			{
				echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vorder&status=$status&Page=$i'>$i</a>";
			}
			else
			{
				echo "<b> $i </b>";
			}
		}
		if($Page!=$Num_Pages)
		{
			echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=vorder&status=$statusPage=$Next_Page'>ถัดไป >></a> ";
		}	
		echo "</div></div>";
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดูรายการใบสั่งซื้อ</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><form name=mainorder method=post><br>
				<a class='boxshadow boxlightblue' href=?action=vorder>ดูรายการใบสั่งซื้อทั้งหมด</a>&nbsp;&nbsp;
				<a class='boxshadow boxorose' href=?action=vorder&status=0>ใบสั่งซื้อที่ยังไม่ชำระเงิน</a>&nbsp;&nbsp;
				<a class='boxshadow boxyellow' href=?action=vorder&status=1>ใบสั่งซื้อรอการจัดส่ง</a>&nbsp;&nbsp;
				<a class='boxshadow boxlemon' href=?action=vorder&status=2>ใบสั่งซื้อจัดส่งแล้ว</a><br><br>
				</td></tr>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}

break;

case "vpayconfirm" :

	//*** Select payconfirm ***//
	
	$sqlResult	= mysqli_query($connection,"select * from ".$fix."payconfirm");
	$row=mysqli_num_rows($sqlResult);
	
	$Per_Page = 25;  // Per Page
	
	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($row<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($row % $Per_Page)==0)
	{
		$Num_Pages =($row/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($row/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$sqlResult = mysqli_query($connection,"select * from ".$fix."payconfirm order by paymentdate desc limit $Page_Start, $Per_Page");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-money'></i> แจ้งโอนเงิน</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<center><p><font color=red>คำแนะนำ: ระบบแจ้งเตือน <i class='boxshadow boxred-mini'>New!</i> จะคงปรากฎในหน้านี้ และหน้า Control Panel จนกว่าท่านจะทำการ ปรับปรุงสถานะใบสั่งซื้อ</font></p></center>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr  bgcolor=#eeeeee><td align=center>เลขที่ใบสั่งซื้อ</td><td align=center>วันที่แจ้งโอนเงิน</td><td align=center>จำนวนเงิน</td><td align=left>ชื่อ-นามสกุล ลูกค้า</td><td align=left>โอนเข้าธนาคาร</td><td align=center>ดูสลิปโอนเงิน</td><td align=center>เลือกทำรายการ</td></tr>";
		$i=0;
		while($arr=mysqli_fetch_array($sqlResult))
		{
			$ordno = $arr['orderno'];
			$transno = $arr['transno'];
			
			$new="";
			if($arr['New'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>"; } else { $new="<i class='boxshadow boxlemon fa fa-check'></i>"; }
						
			echo "<tr><td>".$arr['orderno']." $new</td><td>".thaidate(substr($arr['paymentdate'],0,10))."</td><td align=right>".number_format($arr['total'],2)."</td><td>".$arr['custname']."</td><td>".$arr['bankname']."</td><td align='center'>";
			if($arr['slipimg']!="") { 
				echo "<a class='boxshadow boxlemon' href='".$arr['slipimg']."' target='_blank'><i class='fa fa-eye'></i> คลิก</a>"; 
			} else {
				echo "-";
			}
			echo "
			</td>
			<td width=200 align=center>
			<table border=0>
			<tr>
				<td width=150 align=center><a class='boxshadow boxsky' href=?action=updateorder&orderno=".$arr['orderno']."&total=".$arr['total']."&bankname=".$arr['bankname']."&paymentdate=".$arr['paymentdate']."><i class='fa fa-edit'></i> ปรับปรุงสถานะใบสั่งซื้อ</a></td>
				<td width=45 align=center><a class='boxshadow boxred' href=\"javascript:void();\" onclick=\"mordr3('1','$transno')\"><i class='fa fa-remove'></i> ลบ </a></td>
			</tr>
			</table>			
			</td></tr>";
			
			$i++;
		}
				
		echo "</form></table></td></tr></table>";
		
		echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

		echo "<div id=\"container\">";
		echo "<div class=\"pagination\" align=\"center\">";

		if($Prev_Page)
		{
			echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$status&Page=$Prev_Page'><< ก่อนหน้า</a> ";
		}

		for($i=1; $i<=$Num_Pages; $i++){
			if($i != $Page)
			{
				echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$status&Page=$i'>$i</a>";
			}
			else
			{
				echo "<b> $i </b>";
			}
		}
		if($Page!=$Num_Pages)
		{
			echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$statusPage=$Next_Page'>ถัดไป >></a> ";
		}	
		echo "</div></div>";
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-money'></i> แจ้งยืนยันการโอนเงิน</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}
	

break;


case "vshipping" :

	//*** Select Orders ***//
	
	
	$sqlResult	= mysqli_query($connection,"select * from ".$fix."orders where orderstatus='2' order by shippingdate desc");
	$row=mysqli_num_rows($sqlResult);
	
	$Per_Page = 25;  // Per Page
	
	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($row<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($row % $Per_Page)==0)
	{
		$Num_Pages =($row/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($row/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders where orderstatus='2' order by orderdate desc limit $Page_Start, $Per_Page");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-truck'></i> รายการจัดส่งสินค้า</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr  bgcolor=\"#5DBAE1\">
		<td align=center><font color=white><b>ลำดับที่</b></font></td>
		<td align=center><font color=white><b>เลขที่ใบสั่งซื้อ</b></font></td>
		<td align=center><font color=white><b>ชื่อลูกค้า</b></font></td>
		<td align=center><font color=white><b>วันที่-เวลา จัดส่ง</b></font></td>
		<td align=center><font color=white><b>วิธีจัดส่ง</b></font></td>
		<td align=center><font color=white><b>หมายเลขพัสดุ</b></font></td>
		<td align=center><font color=white><b>เลือกทำรายการ</b></font></td>
		</tr>";
		
		$i = 0;
		while($objResult = mysqli_fetch_array($sqlResult))
		{
				$i++;
				$orderno = $objResult['orderno'];
				$custname = $objResult['cust_name'];
				$shippingdate = $objResult['shippingdate'];
				$shipmd = $objResult['shippingmethod'];
				$trackingno = $objResult['trackingno'];
				echo "<tr>
				<td align=center>".$i."</td>
				<td align=left>".$orderno."</td>
				<td align=left>".$custname."</td>
				<td align=left>".thaidate(substr($shippingdate,0,10))." ".substr($shippingdate,11,5)."</td>
				<td align=left>".$shipmd."</td>
				<td align=left>".$trackingno."</td>
				<td align=center>
					<table><tr><td><a class='boxshadow boxsky' href=\"?action=updateshipping&orderno=".$orderno."&shippingdate=".$shippingdate."&shipmd=".$shipmd."&trackingno=".$trackingno."\"><i class='fa fa-edit'></i> แก้ไข</a></td></tr></table>
				</td>
				</tr>";	
		}
				
		echo "</table></td></tr></table>";
		
		echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

		echo "<div id=\"container\">";
		echo "<div class=\"pagination\" align=\"center\">";

		if($Prev_Page)
		{
			echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vshipping&status=$status&Page=$Prev_Page'><< ก่อนหน้า</a> ";
		}

		for($i=1; $i<=$Num_Pages; $i++){
			if($i != $Page)
			{
				echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vshipping&status=$status&Page=$i'>$i</a>";
			}
			else
			{
				echo "<b> $i </b>";
			}
		}
		if($Page!=$Num_Pages)
		{
			echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=vshipping&status=$statusPage=$Next_Page'>ถัดไป >></a> ";
		}	
		echo "</div></div>";
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดูรายการใบสั่งซื้อ</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}

break;

case "vpayment" :

	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-money'></i> แจ้งโอนเงินแทนลูกค้า</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
	echo "<center>ในกรณีที่ลูกค้าไม่ได้แจ้งยืนยันการโอนเงินผ่านแบบฟอร์มหน้าร้าน แต่ทางร้านต้องการบันทึกข้อมูลการโอนเงินเก็บไว้ สามารถใช้แบบฟอร์มนี้ได้<br><br>";
	echo "<form method=post action=?action=vpayment2>Order No: <input type=text name=orderno size=15 required>&nbsp;&nbsp;<input type=\"submit\" value=\" ค้นหา \"></form></center>";
	echo "</td></tr></table></td></tr></table></center>";

break;

case "vpayment2" :

	$orderno = $_REQUEST['orderno'];

	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-money'></i> แจ้งโอนเงินแทนลูกค้า</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
	echo "<center>ในกรณีที่ลูกค้าไม่ได้แจ้งยืนยันการโอนเงินผ่านแบบฟอร์มหน้าร้าน แต่ทางร้านต้องการบันทึกข้อมูลการโอนเงินเก็บไว้ สามารถใช้แบบฟอร์มนี้ได้<br><br>";
	
	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders where orderno='".$orderno."' ");
	$row=mysqli_num_rows($sqlResult);
	if($row > 0)
	{
		$arr=mysqli_fetch_array($sqlResult);

		$cid = $arr['custid'];
		$cname = $arr['cust_name'];
		$cemail = $arr['ordermail'];
		$ctotal = $arr['totalprice'];
		
		include "shop-pay-confirm.php";
	} else {
		echo "<h1><font color=red>ไม่พบใบสั่งซื้อ $orderno</font></h1><a class='boxshadow boxred' href=?action=vpayment><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</a><br><br>";
	}
	echo "</td></tr></table></td></tr></table></center>";

break;

case "vreceipt" :

	//*** Select Orders ***//
	
	$sqlResult	= mysqli_query($connection,"select * from ".$fix."orders where orderstatus!='0'");
	$row=mysqli_num_rows($sqlResult);
	
	$Per_Page = 25;  // Per Page
	
	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($row<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($row % $Per_Page)==0)
	{
		$Num_Pages =($row/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($row/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders where orderstatus!='0' limit $Page_Start, $Per_Page");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดู-พิมพ์ ใบเสร็จรับเงิน</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "	
			<tr><td colspan=6 align=center><br>
			<form action=\"view-receipt.php?act=view\" method=\"post\">
			เลขที่ใบสั่งซื้อ: <input class=\"tblogin\"  type=\"text\" name=\"orderno\" size=\"15\" required>
			<input class='myButton' type=\"submit\" name=\"submit\" value=\" ค้นหา \">
			</form>
			</td></tr>";		
		echo "
		<tr><td colspan=6><br>
		คำแนะนำในการพิมพ์ใบเสร็จรับเงิน
		<ul>
		<li>ใบเสร็จรับเงิน ของ ใบสั่งซื้อที่มีสถานะ จัดส่งแล้ว จะเป็นใบเสร็จที่สมบูรณ์แบบมากที่สุด</li>
		<li>ใบเสร็จรับเงิน ของ ใบสั่งซื้อที่มีสถานะ รอจัดส่ง จะไม่แสดงข้อมูล วันที่จัดส่งสินค้า/วิธีการจัดส่ง/หมายเลขที่พัสดุ</i>
		</ul>
		</td></tr>";
		echo "<tr  bgcolor=#eeeeee><td align=center>เลขที่ใบสั่งซื้อ</td><td align=center>วันที่ออกใบเสร็จ</td><td align=center>จำนวนเงิน</td><td align=center>ชื่อ-นามสกุล ลูกค้า</td><td align=center>สถานะใบสั่งซื้อ</td><td align=center>เลือกทำรายการ</td></tr>";
		$i=0;
		while($arr=mysqli_fetch_array($sqlResult))
		{
			if($arr['orderstatus']==0) {$orderstatus = "<font color=black>ยังไม่ได้ชำระเงิน</font>";}
			if($arr['orderstatus']==1) {$orderstatus = "<font color=orange>รอจัดส่งสินค้า</font>";}
			if($arr['orderstatus']==2) {$orderstatus = "<font color=green>จัดส่งสินค้าแล้ว</font>";}
			$ordno = $arr['orderno'];
			
			echo "<tr><td>".$arr['orderno']."</td><td>".thaidate(substr($arr['paymentdate'],0,10))."</td><td align=right>".number_format($arr['totalprice'],2)."</td><td>".$arr['cust_name']."</td><td>".$orderstatus."</td>
			<td width=300 align=center>
			<table border=0>
			<tr>
				<td width=70 align=center><a class='boxshadow boxlemon' href=view-receipt.php?act=view&orderno=".$arr['orderno']."><i class='fa fa-eye'></i> ดูใบเสร็จ</a></td>
				<td width=50 align=center><a class='boxshadow boxlightblue' href=view-receipt.php?act=view&media=print&orderno=".$arr['orderno']."><i class='fa fa-print'></i> พิมพ์</a></td>
				<td widt=120 align=center><a class='boxshadow boxsky' href=download-order.php?act=download&orderno=".$arr['orderno']." target=\"_blank\"><i class='fa fa-download'> ดาวน์โหลด .pdf</a></td>
				<td width=50 align=center><a class='boxshadow boxred' href=?action=addreceipt&orderno=".$arr['orderno']."&orderdate=".$arr[1]."><i class='fa fa-edit'></i> แก้ไข</a></td>
			</tr>
			</table>			
			</td></tr>";
			
			$i++;
		}
				
		echo "</form></table></td></tr></table>";
		
		echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

		echo "<div id=\"container\">";
		echo "<div class=\"pagination\" align=\"center\">";

		if($Prev_Page)
		{
			echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$status&Page=$Prev_Page'><< ก่อนหน้า</a> ";
		}

		for($i=1; $i<=$Num_Pages; $i++){
			if($i != $Page)
			{
				echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$status&Page=$i'>$i</a>";
			}
			else
			{
				echo "<b> $i </b>";
			}
		}
		if($Page!=$Num_Pages)
		{
			echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$statusPage=$Next_Page'>ถัดไป >></a> ";
		}	
		echo "</div></div>";
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดู-พิมพ์ ใบเสร็จรับเงิน</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}
	
break;

case "vcontactus" :

	//*** Select contactus ***//
	
	$sqlResult	= mysqli_query($connection,"select * from ".$fix."contactus");
	$row=mysqli_num_rows($sqlResult);
	
	$Per_Page = 25;  // Per Page
	
	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($row<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($row % $Per_Page)==0)
	{
		$Num_Pages =($row/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($row/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$sqlResult = mysqli_query($connection,"select * from ".$fix."contactus order by contactid desc limit $Page_Start, $Per_Page");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comment'></i> ติดต่อสอบถาม</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
		echo "<center><p><font color=red>คำแนะนำ: ระบบแจ้งเตือน <i class='boxshadow boxred-mini'>New</i> จะคงปรากฎในหน้านี้และหน้า Control Panel จนกว่าท่านจะคลิกปุ่ม ดูรายละเอียด</font></p></center>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr  bgcolor=#eeeeee><td align=left>Contact ID</td><td align=left>วันที่</td><td align=left>เรื่อง</td><td align=left>ชื่อ-นามสกุล</td><td align=center>ทำรายการ</td></tr>";
		$i=0;
		while($arr=mysqli_fetch_array($sqlResult))
		{
			$contactid = $arr['contactid'];
			
			$new="";
			if($arr['new'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
						
			echo "<tr><td>".$arr['contactid']." $new</td><td>".thaidate(substr($arr['contactdate'],0,10))."</td><td align=left>".$arr['subject']."</td><td>".$arr['custname']."</td>
			<td width=150 align=center>
			<table border=0>
			<tr>
				<td width=100 align=center><a class='boxshadow boxlemon' href=?action=view-contact-us&contactid=".$contactid."><i class='fa fa-eye'></i> ดูรายละเอียด</a></td>
				<td align=center><a class='boxshadow boxred' href=\"javascript:void();\" onclick=\"mordr4('1','$contactid')\"><i class='fa fa-remove'></i> ลบ </a></td>
			</tr>
			</table>			
			</td></tr>";
			
			$i++;
		}
				
		echo "</form></table></td></tr></table>";
		
		echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

		echo "<div id=\"container\">";
		echo "<div class=\"pagination\" align=\"center\">";

		if($Prev_Page)
		{
			echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$status&Page=$Prev_Page'><< ก่อนหน้า</a> ";
		}

		for($i=1; $i<=$Num_Pages; $i++){
			if($i != $Page)
			{
				echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$status&Page=$i'>$i</a>";
			}
			else
			{
				echo "<b> $i </b>";
			}
		}
		if($Page!=$Num_Pages)
		{
			echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=vreceiptr&status=$statusPage=$Next_Page'>ถัดไป >></a> ";
		}	
		echo "</div></div>";
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comment'></i> ติดต่อสอบถาม</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";		
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีรายการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}
	
break;

case "view-contact-us" :
	$contactid = $_GET['contactid'];
	$sqlResult = mysqli_query($connection,"select * from ".$fix."contactus where contactid='".$contactid."' ");
	$row=mysqli_num_rows($sqlResult);
	if($row > 0)
	{
		$arr=mysqli_fetch_array($sqlResult);
		$contactid = $arr[0];
		$custid = $arr[1];
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comment'></i> ติดต่อสอบถาม</b></font></td></tr>
		<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>
		<center><br><a class='boxshadow boxlemon' href=?action=vcontactus><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</a>&nbsp;&nbsp;
		<a class='boxshadow boxred' href=\"javascript:void();\" onclick=\"mordr4('1','$contactid')\"><i class='fa fa-remove'></i> ลบ </a><br><br></center>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=#eeeeee>";
		echo "
		<tr><td align=left width=20%>Contact ID</td><td>".$arr['contactid']."</td></tr>
		<tr><td align=left>วันที่</td><td>".$arr['contactdate']."</td></tr>
		<tr><td align=left>เรื่อง</td><td>".$arr['subject']."</td></tr>
		<tr><td align=left>ชื่อ-นามสกุล</td><td>".$arr['custname']."</td></tr>
		<tr><td align=left>อีเมล์</td><td>".$arr['custemail']."</td></tr>
		<tr><td align=left>รายละเอียด</td><td>".$arr['details']."</td></tr>
		</table><br>
		";	
		
		$contactid = $_GET['contactid'];
		$sqlResult2 = mysqli_query($connection,"select * from ".$fix."contactreply where contactid='".$contactid."' ");
		$row2=mysqli_num_rows($sqlResult2);
		if($row2 > 0)
		{		
			while($arr2=mysqli_fetch_array($sqlResult2)) {
				echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=#eeeeee>";
				echo "
				<tr><td align=left width=20%>วันที่ตอบ</td><td>".$arr2['replydate']."</td></tr>
				<tr><td align=left>ชื่อ-นามสกุล</td><td>".$arr2['custname']."</td></tr>
				<tr><td align=left>รายละเอียด</td><td>".$arr2['details']."</td></tr>
				</table><br>";
			}
		}
		echo "</td></tr></table>";
		
		include "reply-mail.php";
		@mysqli_query($connection,"update ".$fix."contactus set new='0' where contactid='".$contactid."'");
	} else {
		echo "Error: ไม่พบข้อมูลที่ต้องการ";
	}
break;


case "viewmember" : 

$counter = 0;

$query = "SELECT * FROM ".$fix."member ORDER BY id desc";

$query_result = mysqli_query($connection,$query) or die("Unable to process query: " . mysql_error());
$numrows = mysqli_num_rows($query_result);

$lastpage = ceil($numrows/10);
if ($_GET['page'] < 1) 
{
	$_GET['page'] = 1;
} 
elseif($_GET['page'] > $lastpage) 
{
	$_GET['page'] = $lastpage;
}
$limit = 'LIMIT '.(($_GET['page']-1)*10) .',10';

		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-user'></i> ทะเบียนลูกค้า-สมาชิก</b></font></td></tr>
		<tr><td><hr class='style-two'></td></tr><tr><td align=left>
		<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>
			<tr bgcolor=\"#eeeeee\">
			<td height=\"30\"  align=\"center\">ID</td>
			<td height=\"30\"  align=\"center\">ชื่อ-นามสกุล</td>
			<td height=\"30\"  align=\"center\">ยอดสั่งซื้อสะสม</td>
			<td height=\"30\"  align=\"center\">คะแนนสะสม</td> 
			<td height=\"30\"  align=\"center\">ระดับ</td> 
			<td height=\"30\" align=\"center\">Activated</td>
			<td height=\"30\" align=\"center\"></td>
			</tr>";

$query = "SELECT * FROM ".$fix."member ORDER BY id desc ".$limit;

$query_result = mysqli_query($connection,$query) or die("Unable to process query: " . mysql_error());

echo "
<script language=\"JavaScript\">
function confirmdel(cid)
{
		//if( confirm('ท่านแน่ใจที่จะลบข้อมูลสมาชิกรายนี้ ?')==true )  location = '?act=delete&userid='+cid; 
		
		title = 'คำเตือน'; text = 'ท่านแน่ใจที่จะลบข้อมูลสมาชิกรายนี้ ?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn-danger',
			confirmButtonText: 'ใช่, ต้องการลบ',
			cancelButtonText: 'ไม่, ยกเลิกการลบ',
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal('Deleted!', 'ลบภาพที่เลือกเรียบร้อยแล้ว', 'success');
				location = '?action=delmember&userid='+cid; 
			} else {
				swal('Cancelled', 'ยกเลิกการลบภาพแล้ว :)', 'error');
			}
		});	
}
</script>
";

while ($info = @mysqli_fetch_array($query_result))
{
	$counter++;
	
	$new="";
	if($info['new'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
	
	$lv = $info['level'];
	
	if($lv=='0'){ $level = "สมาชิกทั่วไป"; }
	if($lv=='1'){ $level = "ลูกค้า"; }
	if($lv=='2'){ $level = "ลูกค้า VIP"; }	
	
	$actionivate = ($info['active']=='1') ? "<i class='boxshadow boxlemon'>Yes</i>" : "<i class='boxshadow boxred'>No</i>";
	
	echo "
              <tr>
              <td height=\"30\" align=\"center\">".$info['id']."</td>
              <td height=\"30\" align=\"left\"><a href=?action=modifymember&id=".$info['id'].">".$info['name']." $new</a></td>
              <td height=\"30\" align=\"right\">".number_format($info['purchase'],2)."</td>
              <td height=\"30\" align=\"right\">".$info['point']."</td>
              <td height=\"30\" align=\"left\">".$level."</td>
              <td height=\"30\" align=\"center\">".$actionivate."</td>
              <td height=\"30\" align=\"center\">
			  <a class='boxshadow boxlightblue' href=?action=modifymember&id=".$info['id']." title=\"แก้ไข\"><i class='fa fa-edit'></i> แก้ไข</a>
			  <a class='boxshadow boxred' href=\"javascript: confirmdel('$info[id]')\" title=\"ลบ\"><i class='fa fa-remove'></i> ลบ</a>
			  </td>
              </tr>
			  ";

}

	echo "</table></td></tr></table><br>";

if($numrows==0)
{
	$st=0;
	$en=0;
}
elseif($lastpage==$_GET['page'])
{
	$st=$numrows-$counter+1;
	$en=$numrows;
}
else
{
	$st=$counter*$_GET['page']-10+1;
	$en=$counter*$_GET['page'];
}

echo "<div align=\"center\">ลำดับที่ ".$st." - ".$en." จากทั้งหมด ".$numrows." ราย</div></td>
        <div align=\"center\">";

if ($_GET['page'] != 1 AND $numrows!=0) 
{
   echo " <a href='{$_SERVER['PHP_SELF']}?page=1'>&lt;&lt;</a> ";
   $prevpage = $_GET['page']-1;
   echo " <a href='{$_SERVER['PHP_SELF']}?page=$prevpage'>&lt;</a> ";
}

if ($_GET['page'] != $lastpage AND $numrows!=0) 
{
   $nextpage = $_GET['page']+1;
   echo " <a href='{$_SERVER['PHP_SELF']}?page=$nextpage'>&gt;</a> ";   echo " <a href='{$_SERVER['PHP_SELF']}?page=$lastpage'>&gt;&gt;</a><br>";
}

   //echo ' ( Page '.$_GET['page'].' of '.$lastpage.' ) ';

$strSQL = "UPDATE ".$fix."member ";
$strSQL .="SET new = '0' ";
$objQuery = mysqli_query($connection,$strSQL);	
	
break;

case "modifymember" : 

	$userid = $_REQUEST['id'];
	
	//$userid = $_SESSION['user'];
	$strSQL = " SELECT * FROM ".$fix."member WHERE id = '".$userid." '  ";
	$objQuery = mysqli_query($connection,$strSQL);
	$objResult = mysqli_fetch_array($objQuery);
	
	$name = $objResult['name'];
	$email = $objResult['email'];
	$address = $objResult['address'];
	$city = $objResult['city'];
	$zipcode = $objResult['zipcode'];
	$mobile = $objResult['mobile'];
	$sex = $objResult['sex'];
	$bdate = $objResult['bdate'];
	$bmonth = $objResult['bmonth'];
	$byear = $objResult['byear'];
	$actionive = $objResult['active'];
	$level = $objResult['level'];
	$currentpwd = $objResult['password'];
	$point = $objResult['point'];
	
	$imgavatar = $objResult['avatar'];
	if($imgavatar=="") { $imgavatar = "member.jpg"; }
	
	$timenow=time();
	$currentdate = date("Y-m-d H:i:s A",$timenow);
	
	if($actionive=='0'){ $action1 = " selected"; }
	if($actionive=='1'){ $action2 = " selected"; }
			
	if($level=='0'){ $lv1 = " selected"; }
	if($level=='1'){ $lv2 = " selected"; }
	if($level=='2'){ $lv3 = " selected"; }
	
	echo "

	<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-user'></i> ทะเบียนลูกค้า-สมาชิก / แก้ไขข้อมูล</b></font></td></tr>
	<tr><td><hr class='style-two'></td></tr><tr><td align=left>
	<center><a href=\"?action=viewmember\"><i class='boxshadow boxorose fa fa-arrow-circle-left'> กลับไปหน้า ทะเบียนรายชื่อสมาชิก</i></a></center><br><br>
	<form action=\"?action=updatemember\" method=post name=\"memberform\" onsubmit=\"return checkmemberform()\">
	<table class=\"mytables\" width=\"100%\" cellpadding=\"4\" cellspacing=\"4\" background=\"#ffffff\" border=\"0\" bordercolor=#eeeeee>
	<td>
		<table class=\"mytables\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" background=\"#ffffff\" border=\"0\" bordercolor=#eeeeee>
		<tr>
		<td colspan=2 align=center>UserID:
			<input class=\"tblogin\" type=text name=\"id\" value='".$userid."' size=2 disabled>&nbsp;&nbsp;Level:&nbsp;&nbsp;
			<select name=\"level\"> 
				<option value='0' $lv1>สมาชิกทั่วไป</option>
				<option value='1' $lv2>ลูกค้า</option>
				<option value='2' $lv3>ลูกค้า VIP</option>
			</select>&nbsp;&nbsp;Activate:&nbsp;&nbsp;
			<select name=\"active\">
				<option value='0' $action1>No</option>
				<option value='1' $action2>Yes</option>
			</select>&nbsp;&nbsp;Points:&nbsp;&nbsp;
			<input class=\"tblogin\" type=text name=\"point\" value='".$point."' size=5>
		</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr><td height=10></td></tr>
		<tr><td>ชื่อ-นามสกุล :</td><td><input class=\"tblogin\" type=text name=\"name\" value='".$name."' size=30> *</td></tr>
		<tr><td height=10></td></tr>
		<tr><td>เพศ :</td>
		<td>
			<table border=0 width=60%>
			<tr>";
			
			if($sex=='M'){ $s1 = " checked"; }
			if($sex=='W'){ $s2 = " checked"; }
			if($sex=='N'){ $s3 = " checked"; }
			
			echo "
			<td><input type=radio name=\"sex\" id=\"sex1\" value=\"M\" $s1><label for=sex1><span></span>ชาย</label></td>
			<td><input type=radio name=\"sex\" id=\"sex2\" value=\"W\" $s2><label for=sex2><span></span>หญิง</label></td>
			<td><input type=radio name=\"sex\" id=\"sex3\" value=\"N\" $s3><label for=sex3><span></span>ไม่ระบุ</label></td>
			</tr>
			</table>
		</td></tr>
		<tr><td height=10></td></tr>
		<tr><td>วัน/เดือน/ปี เกิด :</td>
			<td>
			<select name=bdate>";
			$i=1;
			while($i <= 31) {
				$o = sprintf("%02d",$i);
				if($o==$bdate) { $chk = 'selected'; }
				echo "<option value=\"$i\" $chk>$o</option><br>";
				$chk="";
				$i++;
			}
			echo "
			</select>&nbsp;&nbsp;
			<select name=bmonth>";
			$i=1;
			while($i <= 12) {
				$o = sprintf("%02d",$i);
				if($o==$bmonth) { $chk = 'selected'; }
				echo "<option value=\"$i\" $chk>$o</option><br>";
				$chk="";
				$i++;
			}
			echo "
			</select>&nbsp;&nbsp;
			<input class=\"tblogin\" type=text name=byear value='".$byear."' size=5>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr><td>Email:</td><td><input class=\"tblogin\" type=text name=\"email\" value=".$email." size=30> *</td></tr>
		<tr><td height=10></td></tr>
		<tr><td>รหัสผ่าน : </td><td><input class=\"tblogin\" type=password name=\"pwd1\" size=30></td></tr>
		<tr><td height=10></td></tr>
		<tr><td>ยืนยัน รหัสผ่าน : </td><td><input class=\"tblogin\" type=password name=\"pwd2\" size=30></td></tr>
		<tr><td height=10></td></tr>
		<tr><td>ที่อยู่ :</td><td><textarea name=\"address\"rows=5 cols=40>".$address."</textarea> *</td></tr>
		<tr><td height=10></td></tr>
		<tr><td>จังหวัด :</td><td><input class=\"tblogin\" type=text name=\"city\" value='".$city."' size=15> รหัสไปรษณีย์ : <input class=\"tblogin\" type=text name=\"zipcode\" value='".$zipcode."' size=5></td></tr>
		<tr><td height=10></td></tr>
		<tr><td>เบอร์โทรศัพท์ :</td><td><input class=\"tblogin\" type=text name=\"mobile\" value='".$mobile."' size=15> *</td></tr>
		<tr><td height=10></td></tr>
		<tr>
		<td colspan=2 align=center>
			<input type=\"hidden\" name=\"id\" value='".$userid."'>
			<input type=\"hidden\" name=\"currentpwd\" value='".$currentpwd."'>
			<input class=\"myButton\" type=submit name=\"update\" value=\" Update Profile \">
		</td>
		</tr>
	</table>		
	</form>
	</td>
		<td valign=top align=center>
			<table class=\"mytables\" width=\"100%\"><tr><td>
			<center><img src=images/users/".$imgavatar." width=150 height=150></center>
			</td></tr></table>
			</center>
		</td>
	</td></tr></table>
	";

break;

case "delmember" :
	$id = $_GET['cid'];
	mysqli_query($connection,"delete from ".$fix."member where id='$userid'");
	echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลสมัครสมาชิก เรียบร้อยแล้ว</h1><a href=\"?action=viewmember\">กลับไปหน้า ทะเบียนรายชื่อสมาชิก</a></div>";
break;


case "updatemember" :
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$zipcode = $_POST['zipcode'];
	$mobile = $_POST['mobile'];
	$sex = $_POST['sex'];
	$bdate = $_POST['bdate'];
	$bmonth = $_POST['bmonth'];
	$byear = $_POST['byear'];
	$level = $_POST['level'];
	$actionive = $_POST['active'];
	$pwd1 = $_POST['pwd1'];
	$pwd2 = $_POST['pwd2'];
	$currentpwd = $_POST['currentpwd'];
	$activate = $_POST['active'];
	$level = $_POST['level'];
	$point = $_POST['point'];
	
	$userid = $_POST['id'];
	
	If($pwd1 != "" && $pwd2 !="") {
		$password = $pwd1;
	} else {
		$password = $currentpwd;
	}
	
	$update = mysqli_query($connection,"update ".$fix."member set email='$email', password='$password', name='$name', address='$address', city='$city', zipcode='$zipcode', mobile='$mobile', active='$activate', sex='$sex', bdate='$bdate', bmonth='$bmonth', byear='$byear',level='$level',point='$point' where id='$userid' ");

	echo "<center><h1>Update Member Profied Completed.</h1><a href=\"?action=viewmember\">กลับไปหน้า ทะเบียนรายชื่อสมาชิก</a></center>";
		
break;

case "viewreview" :

	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-star'></i> จัดการรีวิวสินค้า</b></font></td></tr>
	<tr><td><hr class='style-two'></td></tr><tr><td align=left>";

	$strSQL = "SELECT * FROM ".$fix."reviews";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$Num_Rows = mysqli_num_rows($objQuery);

	$Per_Page = 10;   // Per Page

	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($Num_Rows<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($Num_Rows % $Per_Page)==0)
	{
		$Num_Pages =($Num_Rows/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($Num_Rows/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$strSQL .=" order by review_date DESC LIMIT $Page_Start , $Per_Page";
	$objQuery  = mysqli_query($connection,$strSQL);

	echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"#eeeeee\" border=\"0\">";

	$i = 0; 
	while($objResult = mysqli_fetch_array($objQuery))
	{
		$idp = $objResult['product_id'];
		$strSQL = "SELECT title FROM ".$fix."catalog where idp='$idp' ";
		$strQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
		$product = mysqli_fetch_array($strQuery);
		
		$new="";
		if($objResult['new'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
		
		echo "<tr><td colspan=\"4\"><hr></td></tr>";
		echo "<tr>
		<td valign=\"top\" width=25%><a href=catalog.php?idp=".$objResult['product_id']." target=\"_blank\">".$product['title']."</a></b></td>
		<td valign=\"top\" width=55%>".$objResult['review']." (".$objResult['rating']." คะแนน) $new</td>
		<td valign=\"top\" width=15%>".$objResult['reviewer_name']."</td>
		<td width=5%><a href=?action=delreview&reviewid=".$objResult['review_id']."&productid=".$objResult['product_id']."><img src=images/delete.gif></a></td>
		</tr>";

		$i++;
	}
	echo "<tr><td colspan=\"4\"><hr></td></tr>";
	echo "</table></td></tr></table><br>";
	
	echo "<center>จำนวนทั้งสิ้น ".$Num_Rows." รีวิว รวม ".$Num_Pages." หน้า</center>";
	
	echo "<div id=\"container\">";
	echo "<div class=\"pagination\" align=\"center\">";

	if($Prev_Page)
	{
		echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=viewreview&Page=$Prev_Page'><< Back</a> ";
	}

	for($i=1; $i<=$Num_Pages; $i++){
		if($i != $Page)
		{
			echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=viewreview&Page=$i'>$i</a>";
		}
		else
		{
			echo "<b> $i </b>";
		}
	}
	if($Page!=$Num_Pages)
	{
		echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=viewreview&Page=$Next_Page'>Next>></a> ";
	}
	
	echo "</div></div>";

	$strSQL = "UPDATE ".$fix."reviews ";
	$strSQL .="SET New = '0' ";
	$objQuery = mysqli_query($connection,$strSQL);	
	
break;


case "delreview" :
	
	$confirm = $_GET['confirm'];
	
	$reviewid = $_REQUEST[ 'reviewid' ];
	$reviewid = stripslashes( $reviewid );
	$reviewid = mysqli_real_escape_string($connection, $reviewid );

	$productid = $_REQUEST[ 'productid' ];
	$productid = stripslashes( $productid );
	$productid = mysqli_real_escape_string($connection, $productid );

	
	if($confirm =="") {
		
		echo "<div class=\"boxshadow boxred\" align=center><h2>ท่านแน่ใจที่จะลบ Review นี้หรือไม่ ?</h2>"; 
		echo "<a href=\"?action=delreview&confirm=yes&reviewid=$reviewid&productid=$productid\">แน่ใจ ยืนยันลบ Review นี้</a> | <a href=?action=viewreview>ยกเลิกการลบ</a></div>";
		
	} else {

		mysqli_query($connection,"delete from ".$fix."reviews where review_id='".$reviewid." ' ");
		echo "<div class=\"boxshadow boxlemon\" align=center><h1>ลบข้อมูล Review เรียบร้อยแล้ว</h1><a href=?action=viewreview>ย้อนกลับ</a></div>";
	}
	
break;


case "viewcomment" :

	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comment'></i> จัดการคอมเม้นต์ใต้ภาพสินค้า/บทความ</b></font></td></tr>
	<tr><td><hr class='style-two'></td></tr><tr><td align=left>";

	$strSQL = "SELECT * FROM ".$fix."reply where replytype='1'";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$Num_Rows = mysqli_num_rows($objQuery);

	$Per_Page = 10;   // Per Page

	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($Num_Rows<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($Num_Rows % $Per_Page)==0)
	{
		$Num_Pages =($Num_Rows/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($Num_Rows/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$strSQL .=" order by CreateDate DESC LIMIT $Page_Start , $Per_Page";
	$objQuery  = mysqli_query($connection,$strSQL);

	echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"#eeeeee\" border=\"0\">";
	echo "<tr><td>ประเภท</td><td>เรื่อง/สินค้า</td><td>รายละเอียด</td><td>โดย</td><td colspan=2></td>";

	$i = 0;
	while($objResult = mysqli_fetch_array($objQuery))
	{
		$id = $objResult['QuestionID'];
		$strSQL = "SELECT title FROM ".$fix."catalog where idp='$id' and category NOT LIKE 'L%' ";
		$strQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
		$product = mysqli_fetch_array($strQuery);
		$title = $product['title'];
		$link = "catalog.php?idp=";
		$tag = "สินค้า";
		
		if($title=="") {
			$strSQL = "SELECT Article FROM ".$fix."article where ArticleID ='$id' ";
			$strQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
			$article = mysqli_fetch_array($strQuery);
			$title = $article['Article'];
			$link = "view-article.php?ArticleID=";
			$tag = "บทความ";
		}
		
		$new="";
		if($objResult['New'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
		
		echo "<tr><td colspan=\"6\"><hr></td></tr>";
		echo "<tr>
		<td>".$tag."</td>
		<td><a href=$link".$objResult['QuestionID'].">".$title."</a></b></td>
		<td>".$objResult['Details']."</b> $new</td>
		<td>".$objResult['Name']."</b></td>
		<td><a href=$link".$objResult['QuestionID'].">ตอบ</a></td>
		<td><a href=?action=delcomment&replyid=".$objResult['ReplyID']."&postid=".$objResult['QuestionID']."><img src=images/delete.gif></a></td>
		</tr>";

		$i++;
	}
	echo "<tr><td colspan=\"6\"><hr></td></tr>";
	echo "</table><br>";
	
	echo "<center>จำนวนทั้งสิ้น ".$Num_Rows." คอมเม้นต์ รวม ".$Num_Pages." หน้า</center>";
	
	echo "<div id=\"container\">";
	echo "<div class=\"pagination\" align=\"center\">";

	if($Prev_Page)
	{
		echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=viewcomment&Page=$Prev_Page'><< Back</a> ";
	}

	for($i=1; $i<=$Num_Pages; $i++){
		if($i != $Page)
		{
			echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=viewcomment&Page=$i'>$i</a>";
		}
		else
		{
			echo "<b> $i </b>";
		}
	}
	if($Page!=$Num_Pages)
	{
		echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=viewcomment&Page=$Next_Page'>Next>></a> ";
	}
	
	echo "</div></div>";
	
	$strSQL = "UPDATE ".$fix."reply ";
	$strSQL .="SET New = '0' ";
	$objQuery = mysqli_query($connection,$strSQL);	
	
break;

case "delcomment" :
	
	$postid = $_REQUEST['postid'];
	$replyid = $_REQUEST['replyid'];
	$confirm = $_GET['confirm'];
	
	if($confirm =="") {
		
		echo "<div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบ ?</h2>"; 
		echo "<a href=\"?action=delcomment&confirm=yes&postid=$postid&replyid=$replyid\">แน่ใจ ยืนยันการลบ</a> | <a href=?action=viewcomment>ยกเลิกการลบ</a></div>";
		
	} else {

		mysqli_query($connection,"delete from ".$fix."reply where ReplyID='".$replyid." ' ");
		
		//*** Update Reply ***//
		$strSQL = "UPDATE ".$fix."webboard ";
		$strSQL .="SET Reply = Reply -1 WHERE QuestionID = ' ".$postid." ' ";
		$objQuery = mysqli_query($connection,$strSQL);	

		echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลเรียบร้อยแล้ว</h1><a href=?action=viewcomment>ย้อนกลับไปหน้าที่มา</a></div>";

	}
break;


case "article" :

	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-edit'></i> จัดการบทความ</b></font></td></tr>
	<tr><td><hr class='style-two'></td></tr><tr><td align=left>";
	
	echo "<div class=\"boxshadow article\"><h2>สารบัญบทความ</h2>
	<a class='bb' href=?action=addarticle><i class='boxshadow boxsky fa fa-edit'> เขียนบทความใหม่</i></a>&nbsp;&nbsp;
	<br><br></div><br>";
	
	$strSQL = "SELECT * FROM ".$fix."article";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$Num_Rows = mysqli_num_rows($objQuery);

	$Per_Page = 10;   // Per Page

	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($Num_Rows<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($Num_Rows % $Per_Page)==0)
	{
		$Num_Pages =($Num_Rows/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($Num_Rows/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$strSQL .=" order  by CreateDate DESC LIMIT $Page_Start , $Per_Page";
	$objQuery  = mysqli_query($connection,$strSQL);

	echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"#eeeeee\" border=\"0\">";

	$i = 0;
	while($objResult = mysqli_fetch_array($objQuery))
	{
		$img[$i] = $objResult['Picture'];
		if($img[$i]=="") { $img[$i] = "nothumbnail.gif"; }

		echo "<tr><td colspan=\"3\"><hr class='style-two'></td></tr>";
		echo "<tr>
		<td><a href=?action=viewarticle&ArticleID=".$objResult['ArticleID']."><img src=images/".$img[$i]." width=150 height=150 border=0></a></td>
		<td valign=\"top\"><div class=\"boxshadow boxlightblue\"><a href=view-article.php?ArticleID=".$objResult['ArticleID']."><b>".lendesc($objResult['Article'],50)."</b></a>&nbsp;&nbsp;&nbsp;
		<i class=\"fa fa-edit\"></i>&nbsp;".thai_time($objResult['CreateDate'])."&nbsp;&nbsp;&nbsp;<i class=\"fa fa-eye\"></i>&nbsp;".$objResult['View']."</div><br>"
		.len2desc($objResult['Details'],1000)."&nbsp;<a href=?action=viewarticle&ArticleID=".$objResult['ArticleID'].">อ่านต่อ</a></td></tr>";

		$i++;
	}
	echo "<tr><td colspan=\"3\"><hr class='style-two'></td></tr>";
	echo "</table><br>";
	
	echo "<center>จำนวนทั้งสิ้น ".$Num_Rows." บทความ รวม ".$Num_Pages." หน้า</center>";
	
	echo "<div id=\"container\">";
	echo "<div class=\"pagination\" align=\"center\">";

	if($Prev_Page)
	{
		echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page'><< Back</a> ";
	}

	for($i=1; $i<=$Num_Pages; $i++){
		if($i != $Page)
		{
			echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?Page=$i'>$i</a>";
		}
		else
		{
			echo "<b> $i </b>";
		}
	}
	if($Page!=$Num_Pages)
	{
		echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page'>Next>></a> ";
	}
	
	echo "</div></div>";
	
	echo "</td></tr></table>";
	
break;


case "viewarticle" :

$qid = $_GET["ArticleID"];

echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-edit'></i> จัดการบทความ</b></font></td></tr>
<tr><td><hr class='style-two'></td></tr><tr><td align=left>";

	//*** Select Article ***//
	$strSQL = "SELECT * FROM ".$fix."article  WHERE ArticleID = '".$_GET["ArticleID"]."' ";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$objResult = mysqli_fetch_array($objQuery);
	$img = $objResult['Picture'];
	if($img=="") { $img = "nothumbnail.gif"; }

	//*** Update View ***//
	$strSQL = "UPDATE ".$fix."article ";
	$strSQL .="SET View = View + 1 WHERE ArticleID = '".$_GET["ArticleID"]."' ";
	$objQuery = mysqli_query($connection,$strSQL);	

echo "<div class=\"boxshadow article\"><h2>".$objResult['Article']."</h2>
<a class='bb' href=?action=article><i class='boxshadow boxsky fa fa-arrow-circle-left'> สารบัญบทความ</i></a>&nbsp;&nbsp;
<a class='bb' href=?action=editarticle&postid=$qid><i class='boxshadow boxlemon fa fa-edit'>  แก้ไขบทความ</i></a>&nbsp;&nbsp;
<a class='bb' href=?action=delarticle&postid=$qid><i class='boxshadow boxorose fa fa-remove'> ลบบทความ</i></a>
<br><br></div><br>";

echo "	
<center>
<table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"#cccccc\">
  <tr>
    <td colspan=\"4\"><b>บทความ เรื่อง : ".$objResult['Article']."</b></td>
  </tr>
  <tr>
    <td colspan=\"4\"><center><img src=images/".$img."></center><br><br>".$objResult['Details']."</td>
  </tr>
  <tr>
    <td>โดย คุณ : ".$objResult['Name']."</td><td>วันที่เขียนบทความ : ".$objResult['CreateDate']." (".thai_time($objResult['CreateDate']).")</td>
    <td>อ่านแล้ว : ".$objResult['View']."</td>
	<td align=\"center\" width=\"80\">
		<a href=?action=delarticle&postid=$qid&url=$url><img src=images/delete.gif></a>
		<a href=?action=editarticle&postid=$qid><img src=images/edit.gif></a>
	</td>
  </tr>
</table>
</td></tr></table>";

break;

case "addarticle" :

echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-edit'></i> จัดการบทความ</b></font></td></tr>
<tr><td><hr class='style-two'></td></tr><tr><td align=left>";

echo "<div class=\"boxshadow article\"><h2>เขียนบทความใหม่</h2>
<a class='bb' href=?action=article><i class='boxshadow boxsky fa fa-arrow-circle-left'> สารบัญบทความ</i></a>&nbsp;&nbsp;
<br><br></div><br>";
	
	$Article = $_POST[ 'txtQuestion' ];
	$Article = stripslashes( $Article );
	$Article = mysqli_real_escape_string($connection, $Article );
	
	$Details = $_POST[ 'txtDetails' ];
	$Details = stripslashes( $Details );
	$Details = mysqli_real_escape_string($connection, $Details );
	
	$Name = $_POST[ 'txtName' ];
	$Name = stripslashes( $Name );
	$Name = mysqli_real_escape_string($connection, $Name );

	if($_GET["Action"] == "Save") 
	{
		if((trim($_POST["txtQuestion"]))&&(trim($_POST["txtDetails"]))&&(trim($_POST["txtName"]))&&(trim($_POST["b5"]))) 
		{
			/* Check Spam */
			$send=1;
			if(!preg_match('/$_SERVER["HTTP_HOST"]/',$HTTP_REFERER)){ unset($send); }
			if(!$_SESSION["nspam"]){ unset($send); }
			if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
			if($send){
				echo "<script language=javascript>sweetAlert('Good Job','บันทึกบทความใหม่เรียบร้อยแล้ว','success');</script>"; 
				echo "<center><font color=blue><h3><b>บันทึกบทความใหม่เรียบร้อยแล้ว</b></h3></font></center><br>";	

				$picturewidth = "742"; //ขนาดรูปภาพประกอบไม่ควรเกินไซต์นี้
				
				if($_FILES["pic"]["size"]>0)	  
				{ $pic=check_image($_FILES["pic"]["tmp_name"],$_FILES["pic"]["type"],"1",date("U")); 
						$pic = preg_replace("/none/","",$pic);
				} else {
						$pic = "nothumbnail.gif"; 
				}
				
				//*** Insert Question ***//
				$strSQL = "INSERT INTO ".$fix."article ";
				$strSQL .="(CreateDate,Article,Details,Name,Picture,New)";
				$strSQL .="VALUES ";
				$strSQL .="('".date("Y-m-d H:i:s")."','".$Article."','".$Details."','".$Name."','".$pic."','1')";
				$objQuery = mysqli_query($connection,$strSQL);
			}  else {
				echo "<script language=javascript>sweetAlert('คำเตือน','ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด','error');</script>"; 
				echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font></center><br>";	
			}
		} else {
			echo "<script language=javascript>sweetAlert('คำเตือน','ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด','error');</script>"; 
			echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font></center><br>";	
		}
		
	}
	echo "
	<center>
	<form action=\"new-article.php?Action=Save\" method=\"post\" name=\"frmMain\" id=\"frmMain\" enctype=\"multipart/form-data\">
	<table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"#eeeeee\">
	<tr>
		<td colspan=2>หัวข้อ/เรื่อง: <input name=\"txtQuestion\" type=\"text\" class=\"tblogin\" id=\"txtQuestion\" value=\"\" size=\"50\"></td>
    </tr>
	<tr>
		<td colspan=2>ภาพประกอบ: <input type=file name=\"pic\"></td>
	</tr>
    <tr>
      <td colspan=2 align=center><textarea name=\"txtDetails\" cols=\"95\" rows=\"15\" id=\"txtDetails\"></textarea></td>
    </tr>
    <tr>
      <td width=\"200\">ชื่อ:<br><input name=\"txtName\" type=\"text\" class=\"tblogin\" id=\"txtName\" value=\"เจ้าของร้าน\" size=\"50\"></td>
      <td align=center>รหัสลับ: <font color=red size=2> กรุณากรอกรหัส 4 ตัว</font><br><table cellspacing=1 cellpadding=0><tr><td><img src=\"img.php\"></td><td><input type=text class=\"tblogin\" size=8 name=b5></td></tr></table></td>
    </tr>	
	</table>
	<br>
	<input name=\"btnSave\" type=\"submit\" id=\"btnSave\" class=\"myButton\" value=\"Submit\">
	</form>
	</center>

	<script src=\"ckeditor/ckeditor.js\"></script>
    <script>
            CKEDITOR.replace(\"txtDetails\");
    </script>";

break;


case "editarticle" :

$qid = $_REQUEST['postid'];

echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-edit'></i> จัดการบทความ/แก้ไข</b></font></td></tr>
<tr><td><hr class='style-two'></td></tr><tr><td align=left>";

echo "<div class=\"boxshadow article\"><h2>แก้ไขบทความ</h2>
<a class='bb' href=?action=article><i class='boxshadow boxsky fa fa-arrow-circle-left'> สารบัญบทความ</i></a>&nbsp;&nbsp;
<a class='bb' href=?action=editarticle&postid=$qid><i class='boxshadow boxlemon fa fa-edit'>  แก้ไขบทความ</i></a>&nbsp;&nbsp;
<a class='bb' href=?action=delarticle&postid=$qid><i class='boxshadow boxorose fa fa-remove'> ลบบทความ</i></a>
<br><br></div><br>";

//*** Select Article ***//
$strSQL = "SELECT * FROM ".$fix."article  WHERE ArticleID = '".$qid."' ";
$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
$objResult = mysqli_fetch_array($objQuery);
$img = $objResult['Picture'];

if($_GET["act"] == "save") 
	{
		
		$Article = $_POST[ 'txtArticle' ];
		$Article = stripslashes( $Article );
		$Article = mysqli_real_escape_string($connection, $Article );
	
		$Details = $_POST[ 'txtDetails' ];
		$Details = stripslashes( $Details );
		$Details = mysqli_real_escape_string($connection, $Details );
	
		$Name = $_POST[ 'txtName' ];
		$Name = stripslashes( $Name );
		$Name = mysqli_real_escape_string($connection, $Name );
	
		if(trim($_POST["b5"])) 
		{
			/* Check Spam */
			$send=1;

			if(!$_SESSION["nspam"]){ unset($send); }
			if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
			if($send){
				echo "<center><font color=blue><h3><b>แก้ไขบทความเรียบร้อยแล้ว</b></h3></font></center><br>";	

				$picturewidth = "742"; //ขนาดรูปภาพประกอบไม่ควรเกินไซต์นี้
				
				if($_FILES["pic"]["size"]>0)	  
				{ $pic=check_image($_FILES["pic"]["tmp_name"],$_FILES["pic"]["type"],"1",date("U")); 
						//$pic = eregi_replace("none","",$pic);
						$pic = preg_replace('/none/','',$pic);
				} 
				
				if($_FILES["pic"]["size"]==0)  { $pic = $_POST['oldimg']; }
				
				//*** Update Article***//
				$strSQL = "UPDATE ".$fix."article ";
				$strSQL .="SET CreateDate='".date("Y-m-d H:i:s")."', Article='".$Article."', Details='".$Details."', Picture='".$pic."' WHERE ArticleID = '".$qid."' ";
				$objQuery = mysqli_query($connection,$strSQL);	
			}  else {
				echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font>";
				echo "กรุณา คลิก <a href=?action=editarticle&postid=".$qid."><b><u>คลิก ตรงนี้</u></b></a> เพื่อกลับไปแก้ไข";
				exit;
			}
		} else {
				echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font>";
				echo "กรุณา <a href=?action=editarticle&postid=".$qid."><b><u>คลิก ตรงนี้</u></b></a> เพื่อกลับไปแก้ไข";
				exit;
		}
		
}

echo "
	<center>
	<form action=?action=editarticle&act=save&postid=".$qid." method=\"post\" name=\"frmMain\" id=\"frmMain\" enctype=\"multipart/form-data\">
	<table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"4\" cellspacing=\"10\" bordercolor=\"#eeeeee\">
	<tr>
		<td colspan=2>หัวข้อ/เรื่อง: <input name=\"txtArticle\" type=\"text\" class=\"tblogin\" id=\"txtArticle\" value='".$objResult["Article"]."' size=\"50\"></td>
    </tr>
	<tr>
		<td colspan=2>ภาพประกอบ: <input type=file name=\"pic\"><input type=\"hidden\" name=\"oldimg\" value=".$img."><br><font color=red>หากต้องการเปลี่ยนภาพประกอบให้ Upload ใหม่ หากต้องการใช้ภาพเดิมให้เว้นไว้</font></td>
	</tr>
    <tr>
      <td colspan=2><textarea name=\"txtDetails\" cols=\"95\" rows=\"15\" id=\"txtDetails\">".$objResult["Details"]."</textarea></td>
    </tr>
    <tr>
      <td width=\"200\">ชื่อ:<br><input name=\"txtName\" type=\"text\" class=\"tblogin\" id=\"txtName\" value=\"เจ้าของร้าน\" size=\"50\"></td>
      <td align=center>รหัสลับ: <font color=red size=2> กรุณากรอกรหัส 4 ตัว</font><br><table cellspacing=1 cellpadding=0><tr><td><img src=\"img.php\"></td><td><input type=text class=\"tblogin\" size=8 name=b5></td></tr></table></td>
    </tr>	
	</table>
	<br>
	<input name=\"btnSave\" type=\"submit\" id=\"btnSave\" class=\"myButton\" value=\"Submit\">
	</form>
	</center>
	<script src=\"ckeditor/ckeditor.js\"></script>
    <script>
            CKEDITOR.replace(\"txtDetails\");
    </script>
	</td></tr></table>";
break;


case "delarticle" :
	
	$postid = $_REQUEST['postid'];
	$confirm = $_GET['confirm'];
	
	if($confirm =="") {
		
		echo "<div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบบทความนี้หรือไม่ ?</h2>"; 
		echo "<a href=\"?action=delarticle&confirm=yes&postid=$postid\">แน่ใจ ยืนยันลบบทความนี้</a> | <a href=?action=article>ยกเลิก</a></div>";
		
	} else {
	
		//*** Select Question ***//
		$strSQL = "SELECT Picture FROM ".$fix."article where ArticleID = '".$postid."'";
		$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
		$objResult = mysqli_fetch_array($objQuery);
		$img = $objResult['Picture'];
	
		//Delete Picture
		if($img)
		{
			@unlink("$folder/$img"); 
			@unlink("$folder/thumb_$img"); 
		}

		//Delete Post
		mysqli_query($connection,"delete from ".$fix."article where ArticleID='".$postid." ' ");
	
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบบาทความพร้อมภาพประกอบ เรียบร้อยแล้ว</h1><a href=?action=article>กลับไปหน้าบทความ</a></div>";
	}
break;


case "viewwebboard" :

echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comments-o'></i> จัดการเว็บบอร์ด</b></font></td></tr>
<tr><td><hr class='style-two'></td></tr><tr><td align=left>";
echo "<div class=\"boxshadow webboard\" align=left><h2>ถาม-ตอบ ปัญหา</h2><a class=\"bb\" href=\"new-question.php\"><i class='boxshadow boxsky fa fa-edit'> ตั้งคำถามใหม่</i></a><br><br></div><br>";

	$strSQL = "SELECT * FROM ".$fix."webboard ";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$Num_Rows = mysqli_num_rows($objQuery);

	$Per_Page = 10;   // Per Page

	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($Num_Rows<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($Num_Rows % $Per_Page)==0)
	{
		$Num_Pages =($Num_Rows/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($Num_Rows/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$strSQL .=" order  by QuestionID DESC LIMIT $Page_Start , $Per_Page";
	$objQuery  = mysqli_query($connection,$strSQL);
	
	echo "
	<table class=\"mytables\" width=\"100%\" cellpadding=4 cellspacing=0 bordercolor=#eeeeee border=1>
	<tr>
		<th> <div align=center>เรื่อง</div></th>
		<th> <div align=center>โดย</div></th>
		<th> <div align=center>เมื่อ</div></th>
		<th> <div align=center>อ่าน</div></th>
		<th> <div align=center>ตอบ</div></th>
	</tr>";

	while($objResult = mysqli_fetch_array($objQuery))
	{
		$Name = $objResult["Name"];
		$chkimg = substr($Name,-3);
		switch ($chkimg) {
			case "(M)" :
				 $Name = "<i class='fa fa-user'></i> คุณ".substr($Name,0,-3); 
				 break;
		    case "(S)" :
				$Name = "<i class='fa fa-user-plus'></i> คุณ".substr($Name,0,-3); 
				break;
		    default :
				$Name = "<i class='fa fa-user-secret'> คุณ".$Name; 
				break;								
		}
		
		$new="";
		if($objResult['New'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}

		echo "
		<tr>
		<td><a href=?action=readwebboard&QuestionID=".$objResult["QuestionID"].">".len2desc($objResult["Question"],50)."</a> $new</td>
		<td>".$Name."</td>
		<td><div align=center>".thai_time($objResult['CreateDate'])."</div></td>
		<td align=right>".$objResult["View"]."</td>
		<td align=right>".$objResult["Reply"]."</td>
		</tr>";
		
	}
	
	echo "</table></td></tr></table>";
	
	echo "<br><center>Total ".$Num_Rows." Record : ".$Num_Pages." Page :</center>";

	echo "<div id=\"container\">";
	echo "<div class=\"pagination\" align=\"center\">";

	if($Prev_Page)
	{
		echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=viewwebboard&Page=$Prev_Page'><< Back</a> ";
	}

	for($i=1; $i<=$Num_Pages; $i++){
		if($i != $Page)
		{
			echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?action=viewwebboard&Page=$i'>$i</a>";
		}
		else
		{
		echo "<b> $i </b>";
		}
	}
	if($Page!=$Num_Pages)
	{
		echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?action=viewwebboard&Page=$Next_Page'>Next>></a> ";
	}
	
	echo "</div></div>";
	

	if($_SESSION['admin']!="") {
		$strSQL = "UPDATE ".$fix."webboard ";
		$strSQL .="SET New = '0' ";
		$objQuery = mysqli_query($connection,$strSQL);	
	}
	
break;

case "readwebboard" :

echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comments-o'></i> จัดการเว็บบอร์ด</b></font></td></tr>
<tr><td><hr class='style-two'></td></tr><tr><td align=left>
<div class=\"boxshadow webboard\" align=left><h2>ถาม-ตอบ ปัญหา</h2>
<a href=\"?action=viewwebboard\"><i class='boxshadow boxsky fa fa-arrow-circle-left'> กลับไปหน้าสารบัญเว็บบอร์ด</i></a>&nbsp;&nbsp;
<a href=?action=delpost&replyid=$replyid&postid=".$_GET["QuestionID"]."&url=?action=viewwebboard&QuestionID=".$_GET["QuestionID"]."><i class='boxshadow boxorose fa fa-remove'> ลบคำถามนี้</i></a>
<br><br></div><br>";

	//*** Select Question ***//
	$strSQL = "SELECT * FROM ".$fix."webboard  WHERE QuestionID = '".$_GET["QuestionID"]."' ";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$objResult = mysqli_fetch_array($objQuery);
	
			$Name = $objResult["Name"];
			$chkimg = substr($Name,-3);
			switch ($chkimg) {
					case "(M)" :
						$Name = substr($Name,0,-3); 
						$bbimg = "member.jpg";
						$title = "สมาชิก";
						break;
					case "(S)" :
						$Name = substr($Name,0,-3); 
						$bbimg = "shopper.jpg";
						$title = "เจ้าของร้าน";
						break;
					default:
						$Name = $Name; 
						$bbimg = "user.png";		
						$title = "ลูกค้าทั่วไป";
			}
	
	//*** Update View ***//
	$strSQL = "UPDATE ".$fix."webboard ";
	$strSQL .="SET View = View + 1 WHERE QuestionID = '".$_GET["QuestionID"]."' ";
	$objQuery = mysqli_query($connection,$strSQL);	
	
	echo "
	<table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#cccccc\">
	<tr bgcolor=#eeeeee>
		<td colspan=\"5\"><h2>เรื่อง : ".$objResult["Question"]."</h2></td>
	</tr>
	<tr>
	<td>
		<table width=100% border=1 cellpadding=4 cellspacing=0 bordercolor=#eeeeee>
		<tr><td align=center width=100><img src=images/$bbimg widt=75 height=75></td><td valign=top colspan=4>".nl2br($objResult["Details"])."</td></tr>
		<tr bgcolor=#eeeeee>
			<td align=center><b>".$title."</b></td>
			<td width=200>ถามโดย คุณ".$Name."</td>
			<td>ถามเมื่อ ".thai_time($objResult['CreateDate'])."</td>
			<td>อ่านแล้ว : ".$objResult["View"]."</td>
			<td align=center><a href=?action=delpost&replyid=$replyid&postid=".$_GET["QuestionID"]."&url=?action=viewwebboard&QuestionID=".$_GET["QuestionID"]."><img src=images/delete.gif></a></td>
			</tr>
		</table>
	</td>
	</tr>
	</table><br>
	</td></tr></table>";

break;

case "delpost" :
	
	$confirm = $_GET['confirm'];
	$url = $_REQUEST['url'];
	
	if($confirm =="") {
		
		echo "<div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบ ?</h2>"; 
		echo "<a href=\"?action=delpost&confirm=yes&postid=".$_GET['postid']."&url=$url\">แน่ใจ ยืนยันการลบ</a> | <a href=?action=viewwebboard>ยกเลิกการลบ</a></div>";
		
	} else {

		mysqli_query($connection,"delete from ".$fix."webboard where QuestionID='".$postid." ' ");
		mysqli_query($connection,"delete from ".$fix."reply where QuestionID='".$postid." ' ");
	
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลเรียบร้อยแล้ว</h1><a href=?action=viewwebboard>กลับไปหน้าสารบัญเว็บบอร์ด</a></div>";

	}
break;


case "payment" :  //เพิ่มบัญชีธนาคาร
	include ("payment.php");
echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-bank'></i> ตั้งค่าบัญชีธนาคาร</b></font></td></tr>
<tr><td colspan=4><hr class='style-two'></td></tr>
<tr><td valign=top><table><form method=post action=\"backshopoffice.php?action=createpm\"><tr><td></td><td><b>ชื่อธนาคาร</b></td><td><b>สาขา</b></td><td><b>เลขที่บัญชี</b></td><td><b>ชื่อบัญชี</b></td></tr>";
	for($i=0; $i<$Sshipmethod; $i++)
	{
	echo "<tr>
	<td>".($i+1).".</td>
	<td><input type=text name=\"pmdesc[]\" size=15 value=\"".$paymentmethod[$i][0]."\"></td>
	<td><input type=text name=\"pmdesc1[]\" size=15 value=\"".$paymentmethod[$i][1]."\"></td>
	<td><input type=text name=\"pmdesc2[]\" size=15 value=\"".$paymentmethod[$i][2]."\"></td>
	<td><input type=text name=\"pmdesc3[]\" size=25 value=\"".$paymentmethod[$i][3]."\"></td>
	</tr>";
	}
    echo "<tr><td></td><td colspan=4><br><center><input type=submit value=\"       บันทึก      \"></center></td></tr></form></table><br><font face=\"MS Sans Serif\" size=2 color=gray><ul>คำแนะนำ<li>สามารถใส่  บัญชีธนาคาร ได้สูงสุด $Sshipmethod รายการ หรือจะไม่ใส่เลยก็ได้ <li>ถ้าไม่ต้องการข้อใดให้ลบข้อความ แล้วกด บันทึก<li>กรณีที่ต้องการให้มีระบบตัดบัตรเครดิต โปรดติดต่อ support@siamecohost.com</ul></font></td></tr></form></table>";
break;

case "shipping" : //เพิ่มวิธีการจัดส่งสินค้า
	include ("shipping.php");
echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-truck'></i> ตั้งค่าการจัดส่งสินค้า</b></font></td></tr>
<tr><td colspan=4><hr class='style-two'></td></tr>
<tr><td valign=top><table><form method=post action=\"backshopoffice.php?action=createsp\"><tr><td></td><td><b>วิธีจัดส่งสินค้า</b></td><td><b>รายละเอียด</b></td><td><b>ค่าบริการ</b></td></tr>";
	for($i=0; $i<$Sshipmethod; $i++)
	{
	echo "<tr>
	<td>".($i+1).".</td>
	<td><input type=text name=\"spdesc[]\" size=30 value=\"".$shippingmethod[$i][0]."\"></td>
	<td><input type=text name=\"spdesc1[]\" size=30 value=\"".$shippingmethod[$i][1]."\"></td>
	<td><input type=text name=\"spdesc2[]\" size=15 value=\"".$shippingmethod[$i][2]."\"></td>
	</tr>";
	}
	if(!$mprice) $mprice="0";
    echo "<tr><td></td><td colspan=3><br><center>"._LANG_10_1."<input type=text name=mprice size=4 value=\"$mprice\">&nbsp;&nbsp;<input type=submit value=\"       บันทึก      \"></center></td></tr></form></table><br><br><font face=\"MS Sans Serif\" size=2 color=gray><ul>คำแนะนำ<li>สามารถใส่ "._LANG_26." & "._LANG_26_2."  ได้สูงสุด $Sshipmethod รายการ หรือจะไม่ใส่เลยก็ได้ <li>ถ้าไม่ต้องการข้อใดให้ลบข้อความ แล้วกด บันทึก<li>"._LANG_10_1." จะแสดงเตือนลูกค้าในหน้าตะกร้าสินค้า<li>กรณีที่ต้องการให้มีระบบตัดบัตรเครดิต โปรดติดต่อ support@siamecohost.com</ul></font></td></tr></form></table>";
break;

case "slidetitle" :  //เพิ่มไตเติลสไลด์และลิงค์
	include ("slidetitle.php");
echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-edit'></i> เพิ่มคำบรรยายภาพสไลด์</b></font></td></tr>
<tr><td colspan=4><hr class='style-two'></td></tr>
<tr><td valign=top><table><form method=post action=\"backshopoffice.php?action=createtitle\"><tr><td>ภาพสไลด์ #</td><td>คำบรรยาย</td><td>Link (ต.ย. http://www.google.com)</td></tr>";
	for($j=0; $j<10; $j++)
	{
	echo "<tr>
	<td align=center>".($j+1).".</td>
	<td><input type=text name=\"sldesc[]\" size=50 value=\"".$slidetitle[$j][0]."\"></td>
	<td><input type=text name=\"sldesc1[]\" size=30 value=\"".$slidetitle[$j][1]."\"></td>
	</tr>";
	}
	echo "<tr><td>$Sslidetitle</td></tr>";
    echo "<tr><td></td><td colspan=4><br><center><input type=submit value=\"       บันทึก      \"></center></td></tr></form></table><br><font face=\"MS Sans Serif\" size=2 color=gray><ul>คำแนะนำ<li>สามารถใส่  คำบรรยาย และ ลิงค์ ได้สูงสุด 10 ข้อความ หรือจะไม่ใส่เลยก็ได้ <li>ถ้าไม่ต้องการข้อใดให้ลบข้อความ แล้วกด บันทึก</ul></font></td></tr></form></table>";
break;

case "createpm":
$data .= "<?php\r\n\$paymentmethod=Array(";
	for($i=0; $i<count($pmdesc); $i++)
	{
if($pmdesc[$i]) $data .= "array(\"$pmdesc[$i]\",\"$pmdesc1[$i]\",\"$pmdesc2[$i]\",\"$pmdesc3[$i]\"),";
	}
$data .= ");\r\n?>";

$data = str_replace(",)",")",$data);
$startwrite = fopen("payment.php", "w") or die("<script language=javascript>sweetAlert('Error','ไม่สามารถเขียนไฟล์ payment.php ได้\\nโปรดทำการ Chmod 777 ไฟล์ payment.php','error');</script>");
fputs($startwrite,$data);
fclose($startwrite);
redirect("?action=payment","");
break;

case "createsp":
$data .= "<?php\r\n\$shippingmethod=Array(";
	for($i=0; $i<count($spdesc); $i++)
	{
if($spdesc[$i]) $data .= "array(\"$spdesc[$i]\",\"$spdesc1[$i]\",\"$spdesc2[$i]\"),";
	}
$data .= ");\r\n\$mprice=\"$mprice\";\r\n?>";

$data = str_replace(",)",")",$data);
$startwrite = fopen("shipping.php", "w") or die("<script language=javascript>sweetAlert('error','ไม่สามารถเขียนไฟล์ shipping.php ได้\\nโปรดทำการ Chmod 777 ไฟล์ payment.php','error');</script>");
fputs($startwrite,$data);
fclose($startwrite);
redirect("?action=shipping","");
break;

case "createtitle":
$data .= "<?php\r\n\$slidetitle=Array(";
	for($i=0; $i<count($sldesc); $i++)
	{
if($sldesc[$i]) $data .= "array(\"$sldesc[$i]\",\"$sldesc1[$i]\"),";
	}
$data .= ");\r\n?>";

$data = str_replace(",)",")",$data);
$startwrite = fopen("slidetitle.php", "w") or die("<script language=javascript>sweetAlert('Error','ไม่สามารถเขียนไฟล์ slidetitle.php ได้\\nโปรดทำการ Chmod 777 ไฟล์ slidetitle.php','error');</script>");
fputs($startwrite,$data);
fclose($startwrite);
redirect("?action=slidetitle","");
break;


case "updateorder" : //ปรับปรุงสถานะใบสั่งซื้อ
	
	include("payment.php");
	
	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-calendar-check-o'></i> ปรับปรุงสถานะใบสั่งซื้อสินค้า</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=center>";

    Global $paymentmethod;	
	
	$pmid1 = count($paymentmethod);
	$dat1 = "";
	$dat1 .= "<table>";
	if($pmid1>0){    
			for($i=0; $i<$pmid1; $i++)
			{
				$chkbank = "";
				if(trim($paymentmethod[$i][0])==$_GET['bankname']) { $chkbank = "checked"; }
				$txt1 = $paymentmethod[$i][0]." เลขที่บัญชี ".$paymentmethod[$i][2];
				$dat1 .= "<tr><td><input type=radio id=paymentmethod$i name=paymentmethod value='".$paymentmethod[$i][0]."' ".$chkbank."><label for=paymentmethod$i><span></span>".$txt1."</label></td></tr>";
			}  
		   if($_GET['bankname']=="PayPal") { $chkpaypal = "checked"; }
		   $dat1 .= "<tr><td><input type=radio id=paymentmethod$i+1 name=paymentmethod value='PayPal' ".$chkpaypal."><label for=paymentmethod$i+1><span></span>ชำระผ่าน PayPal</label></td></tr>";
		   $dat1 .= "</table></td>";
	}
	
	$paid = ($total) ? " checked" : "";
	
	echo "<center>
		<form action=\"?action=updatestatus\" method=\"post\">
		<table width=550 border=0 bgcolor=#cccccc cellpadding=0 cellspacing=4><tr><td align=center>
		<table width=\"550\" border=\"0\" bgcolor=#ffffff cellspacing=\"4\" cellpadding=\"1\">
		<tr><td colspan=\"2\" align=\"center\"><div class='boxshadow boxred'><b>ปรับปรุงสถานะใบสั่งซื้อ</b></div><br><br></td></tr>
		<tr><td>เลขที่ใบสั่งซื้อ:</td><td><input class=\"tblogin\"  type=\"text\" name=\"orderno\" size=\"25\" value=\"$orderno\" required></td></tr>
		<tr><td>สถานะใบสั่งซื้อ:</td>
		<td>
				<input type=radio name=orderstatus id=orderstatus0 value=0><label for=orderstatus0><span></span>ยังไม่ชำระเงิน/ยังไม่จัดส่งสินค้า</label>
				<input type=radio name=orderstatus id=orderstatus1 value=1 $paid><label for=orderstatus1><span></span>ชำระเงินแล้ว/รอจัดส่งสินค้า</label>
				<input type=radio name=orderstatus id=orderstatus2 value=2><label for=orderstatus2><span></span>ชำระเงินแล้ว/จัดส่งสินค้าแล้ว</label>
		</td>
		</tr>
		<tr><td>จำนวนเงิน:</td><td><input class=\"tblogin\"  type=\"text\" name=\"price\" size=\"25\" value=\"$total\" required> (ไม่ต้องใส่จุดหรือคอมม่า)</td></tr>
		<tr>
			<td>วันที่ได้รับเงิน:</td><td><input class=\"tblogin\"  type=\"text\" name=\"paymentdate\" size=\"25\" value=$paymentdate> <i class='fa fa-calendar-check-o'></i>
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
		<tr><td>วิธีการชำระเงิน:</td><td>".$dat1."</td></tr>
		<tr><td colspan=\"2\" align=\"center\"><br><input class='myButton' type=\"submit\" name=\"submit\" value=\" บันทึก \"><br></td></tr>
		<tr>
		<td colspan=\"2\">
			คำแนะนำ<br>
			<ul>
				<li>ใบสั่งซื้อใหม่ทุกใบ ระบบจะตั้งค่าเป็น ยังไม่ชำระเงิน/ยังไม่จัดส่งสินค้า ให้แล้วโดยอัตโนมัติ</li>
				<li>ใบสั่งซื้ัอที่ ชำระเงินแล้ว/จัดส่งแล้ว ต้องปรับปรุงสถานะการจัดส่งสินค้า ด้วย</li>
			</ul>
		</td>
		</tr>
		</table>
		</td></tr></table>
		</form>
		</center>
		</td></tr></table>";
	break;
	
case "updateshipping" : //ปรับปรุงสถานะการจัดส่งสินค้า
	
	include("shipping.php");
	
	$orderno = $_GET['orderno'];
	$shippingdate = $_GET['shippingdate'];
	$shipmd = $_GET['shipmd'];
	$trackingno = $_GET['trackingno'];
	
	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-calendar-check-o'></i> ปรับปรุงสถานะการจัดส่งสินค้า</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=center>";

    Global $shippingmethod;	
	
	$pmid2 = count($shippingmethod);
	$dat2 = "";
	$dat2 .= "<table>";
	if($pmid2>0){    
			for($i=0; $i<$pmid2; $i++)
			{
				$chk = "";
				if(trim($shippingmethod[$i][0])==trim($shipmd)) { $chk = "checked"; }
				$txt2 = $shippingmethod[$i][0]." + ".$shippingmethod[$i][2];
				$dat2 .= "<tr><td><input type=radio id=shipping$i name=shipping value='".$shippingmethod[$i][0]."' $chk><label for=shipping$i><span></span>".$txt2." บาท </label></td></tr>";				
			}  
		   $dat2 .= "</table></td>";
	}	

	echo "<center>
		<form action=\"?action=addtracking\" method=\"post\">
		<table width=450 border=0 bgcolor=#cccccc cellpadding=0 cellspacing=4><tr><td align=center>
		<table width=\"450\" border=\"0\" bgcolor=#ffffff cellspacing=\"4\" cellpadding=\"1\">
		<tr><td colspan=\"2\" align=\"center\"><div class='boxshadow boxred'><b>ปรับปรุงสถานะการจัดส่ง</b></div><br><br></td></tr>
		<tr><td>เลขที่ใบสั่งซื้อ:</td><td><input class=\"tblogin\"  type=\"text\" name=\"orderno\" size=\"25\" value='".$orderno."' required></td></tr>
		<tr>
			<td>วันที่จัดส่งสินค้า:</td><td><input class=\"tblogin\"  type=\"text\" name=\"shippingdate\" size=\"25\" value=".$shippingdate."> <i class='fa fa-calendar-check-o'></i>
			<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
			<script src=\"js/datepicker/jquery.simple-dtpicker.js\"></script>
			<link rel=\"stylesheet\" href=\"js/datepicker/jquery.simple-dtpicker.css\">
			<script type=\"text/javascript\">
			$(function(){
				$('*[name=shippingdate]').appendDtpicker({
					\"dateFormat\": \"YYYY-MM-DD h:m\"
				});
			});
			</script>
			</td>
		</tr>
		<tr><td>วิธีการจัดส่งสินค้า:</td><td>".$dat2."</td></tr>
		<tr><td>หมายเลขจัดส่ง:</td><td><input class=\"tblogin\"  type=\"text\" name=\"trackingno\" size=\"25\" value='".$trackingno."' required></td></tr>
		<tr><td colspan=\"2\" align=\"center\"><br><input class='myButton' type=\"submit\" name=\"submit\" value=\" บันทึก \"><br><br></td></tr>
		<tr>
		<td colspan=\"2\">
			คำแนะนำ<br>
			<ul>
				<li>ใบสั่งซื้ัอที่จัดส่งแล้ว ต้องปรับปรุงสถานะใบสั่งซื้อ ด้วย</li>
			</ul>
		</td>
		</tr>
		</table>
		</td></tr></table>
		</form>
		</center>
		</td></tr></table>";
	break;
	
	
case "updatestatus" : //ปรับปรุงสถานะใบสั่งซื้อ

	$orderno = $_POST['orderno'];
	$orderstatus = $_POST['orderstatus'];
	
	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders where orderno='".$orderno."' ");
	$numrow = mysqli_num_rows($sqlResult);
	
	if($numrow > 0) {
	
		$sqlResult2 = mysqli_query($connection,"select New from ".$fix."payconfirm where orderno='".$orderno."' ");
		$newarr=mysqli_fetch_array($sqlResult2);
	
		if($newarr['New'] != '0') {
	
			$price = $_POST['price'];
			$price = intval(preg_replace('/[^\d.]/', '', $price));
			$newpoint = ($price*$points)/100;
	
			//Update Orders
			@mysqli_query($connection,"update ".$fix."orders set orderstatus='".$orderstatus."', paymentdate='".$paymentdate."', paymentmethod='".$paymentmethod."' where orderno='".$orderno."'");
	
			//Update PayConfirm
			@mysqli_query($connection,"update ".$fix."payconfirm set New='0' where orderno='".$orderno."'");
	
			//Update Member (ยอดสั่งซื้อสะสม+ยอดคะแนนสะสม)
			@mysqli_query($connection,"update ".$fix."member set purchase=purchase+$price, point=point+$newpoint where id='".$custid."' ");
			echo "<script language=javascript>sweetAlert('Complete','ปรับปรุงสถานะใบสั่งซื้อเรียบร้อยแล้ว','success');</script>";
			echo "<div class='boxshadow boxlemon' align=center><h1>ปรับปรุงสถานะใบสั่งซื้อ $orderno เรียบร้อยแล้ว</h1><a href='?action=updateorder'>Go Back</a></div>";
	
		} else {
			echo "<script language=javascript>sweetAlert('Warning','ใบสั่งซื้อนี้ ปรับปรุงสถานะไปแล้ว','warning');</script>";
			echo "<div class='boxshadow boxred' align=center><h1>ใบสั่งซื้อ $orderno นี้ ปรับปรุงสถานะไปแล้ว</h1><a href='?action=updateorder'>Go Back</a></div>";
		}
	} else {
			echo "<script language=javascript>sweetAlert('Error','ไม่พบใบสั่งซื้อนี้','error');</script>";
			echo "<div class='boxshadow boxred' align=center><h1>ไม่พบใบสั่งซื้อ $orderno นี้</h1><a href='?action=updateorder'>Go Back</a></div>";
	}
	
	break;

case "addtracking" : //ปรับปรุงสถานะการจัดส่งสินค้า

	$orderno = $_POST['orderno'];
	
	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders where orderno='".$orderno."' ");
	$numrow = mysqli_num_rows($sqlResult);
	
	if($numrow > 0) {
			$shippingdate = $_POST['shippingdate'];
			$shippingmethod = $_POST['shipping'];
			$trackingno = $_POST['trackingno'];
			@mysqli_query($connection,"update ".$fix."orders set orderstatus='2', shippingdate='".$shippingdate."', shippingmethod='".$shippingmethod."', trackingno = '".$trackingno."'  where orderno='".$orderno."'");
			echo "<script language=javascript>sweetAlert('Complete','บันทึกข้อมูลเรียบร้อยแล้ว','success');</script>";
			echo "<div class='boxshadow boxlemon' align=center><h1>บันทึกข้อมูลเรียบร้อยแล้ว</h1><a href='?action=updateshipping'>Go Back</a></div>";
	} else {
			echo "<script language=javascript>sweetAlert('Error','ไม่พบข้อมูลใบสั่งซื้อ','error');</script>";
			echo "<div class='boxshadow boxred' align=center><h1>ไม่พบใบสั่งซื้อ $orderno นี้</h1><a href='?action=updateshipping'>Go Back</a></div>";
	}
	
	break;
	
case "addreceipt" : //บันทึกข้อมูลใบเสร็จรับเงิน
	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-money'></i> แก้ไขข้อมูลใบเสร็จรับเงิน</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=center>";

	echo "<center>
		<table width=550 border=0 bgcolor=#cccccc cellpadding=0 cellspacing=1><tr><td align=center>
			<table width=\"550\" bgcolor=#ffffff cellspacing=\"4\" cellpadding=\"1\" border=0>
			<form action=\"?action=savereceipt\" method=\"post\">
			<tr><td colspan=\"2\" align=\"center\"><div class='boxshadow boxred'><b>แก้ไขข้อมูลใบเสร็จรับเงิน</b></div><br></td></tr>
			<tr><td colspan=2>
			ตามปกติระบบจะสร้างใบเสร็จรับเงินไว้ให้ท่านแล้วโดยอัตโนมัติ โดยใช้ข้อมูลดังต่อไปนี้<br>
			<ul>
				<li>ใช้เลขที่ใบสั่งซื้อ เป็น เลขที่ใบเสร็จรับเงิน
				<li><font color=red>ใช้วันที่ ที่ลูกค้าชำระเงิน/แจ้งโอนเงิน เป็น  วันที่ออกใบเสร็จรับเงิน</font>
				<li>ใช้ชื่อเจ้าของร้าน เป็น ชื่อผู้รับเงิน
			</ul>
			<center>หากท่านต้องการแก้ไขข้อมูล ใบเสร็จรับเงิน กรุณากรอกแบบฟอร์มนี้</center>
			</td></tr>
			<tr><td>เลขที่ใบสั่งซื้อ:</td><td><input class=\"tblogin\"  type=\"text\" name=\"orderno\" size=\"25\" value=$orderno></td></tr>
			<tr><td>เลขที่ใบเสร็จรับเงิน:</td><td><input class=\"tblogin\"  type=\"text\" name=\"receiptno\" size=\"25\" value=$orderno></td></tr>
			<tr>
			<td>วันที่ออกใบเสร็จรับเงิน:</td><td><input class=\"tblogin\"  type=\"text\" name=\"receiptdate\" size=\"25\" value=$orderdate>
			<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
			<script src=\"js/datepicker/jquery.simple-dtpicker.js\"></script>
			<link rel=\"stylesheet\" href=\"js/datepicker/jquery.simple-dtpicker.css\">
			<script type=\"text/javascript\">
			$(function(){
				$('*[name=receiptdate]').appendDtpicker({
					\"dateFormat\": \"YYYY-MM-DD h:m\"
				});
			});
			</script>		
			</td>
			</tr>
			<tr><td>ชื่อผู้รับเงิน:</td><td><input class=\"tblogin\"  type=\"text\" name=\"receiptname\" size=\"25\" value=$shopowner></td></tr>
			<tr><td colspan=\"2\" align=\"center\"><br><input class='myButton' type=\"submit\" name=\"submit\" value=\" บันทึกข้อมูล \"><br><br></td></tr>
			</form>
			</table>
		</td></tr></table>
		</center><br><br>
		</td></tr></table>";
	break;
	
case "savereceipt" : //ปรับปรุงข้อมูลใบเสร็จรับเงิน

	$orderno = $_POST['orderno'];
	$receiptno = $_POST['receiptno'];
	$receiptdate = $_POST['receiptdate'];
	$receiptname = $_POST['receiptname'];
	@mysqli_query($connection,"update ".$fix."orders set receiptno='".$receiptno."', receiptdate='".$receiptdate."', receiptname = '".$receiptname."'  where orderno='".$orderno."'");
	echo "<script language=javascript>sweetAlert('Complete','บันทึกข้อมูลเรียบร้อยแล้ว','success');</script>";
	echo "<div class='boxshadow boxlemon' align=center><h1>บันทึกข้อมูลเรียบร้อยแล้ว</h1><a href='?action=addreceipt'>Go Back</a></div>";
	break;

case "delinstall" :
echo "<center>";
if(@unlink("install.php"))
redirect("?action=",""); 
else
redirect("?action=","alert('ผิดพลาด! ไม่สามารถลบไฟล์ install.php ได้\\nโปรดใช้โปรแกรม FTP  ต่อเข้าไปยังเซิฟเวอร์เพื่อรลบ\\nหากไฟล์ install.php ไม่ถูกลบออกไป\\nจะทำให้ เว็บไซต์ถูกแทรกแซงโดยผู้อื่น');"); 
echo "</center>";
break;

case "config" :
echo "
	<link href=\"css/magic-check.css\" rel=\"stylesheet\">
	<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css\">
	<link rel=\"stylesheet\" href=\"/resources/demos/style.css\">
	<script src=\"https://code.jquery.com/jquery-1.12.4.js\"></script>
	<script src=\"https://code.jquery.com/ui/1.12.0/jquery-ui.js\"></script>
	<script>
	$( function() {
		$( \"#tabs\" ).tabs();
	} );
	</script>";

echo "
<script language=javascript src=\"js/jscolor/jscolor.js\"></script>
<script language=\"javascript\">
function myFunction(a,b) {
    var x = document.getElementById(a).value;
	document.getElementById(b).innerHTML = x;
}
</script>
<form name=\"alltext\" action=\"backshopoffice.php\" method=post enctype=\"multipart/form-data\">
<div id=\"tabs\">
  <ul>
    <li><a href=\"#tabs-1\">ข้อมูลเว็บไซต์</a></li>
    <li><a href=\"#tabs-2\">ข้อมูลร้านค้า</a></li>
    <li><a href=\"#tabs-3\">Social Network</a></li>
	<li><a href=\"#tabs-4\">ตั้งค่าตัวเลือก</a></li>
	<li><a href=\"#tabs-5\">สีของเว็บไซต์</a></li>
	<li><a href=\"#tabs-6\">โปรโมชั่น-ส่วนลด</a></li>
  </ul>
  <div id=\"tabs-1\">
		<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
		<input type=hidden name=\"action\" value=\"saveconfig\">
		<tr><td colspan=2 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-cog'></i> ตั้งค่ากำหนดพื้นฐาน /  ข้อมูลเว็บไซต์</b></font></td></tr>
		<tr><td colspan=2><hr class='style-two'></td></tr>
		<tr bgcolor=#eeeeee><td width=100><i class='cp4 fa fa-user'></i> Username:</td><td><b><font color=green>$site_arr[1]</font></b></td></tr>
		<tr><td><i class='cp4 fa fa-key'></i> Password: </td><td><input type=text name=\"c2\" size=12 maxlength=8> <input type=text name=\"c22\" size=12 maxlength=8></td></tr>
		<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-envelope-o'></i> E-mail: </td><td><input type=text name=c3 value=\"$emailcontact\" size=29></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-globe'></i> Domain:  <font size=1>(ตัวอย่าง: mywebsite.com)</font></td><td><input type=text name=\"c4\" value=\"$domainname\" size=29></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-edit'></i> Title: </td><td><input type=text name=\"c5\" value=\"".stripslashes($sitetitle)."\" size=29></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-edit'></i> Description: </td><td><input type=text name=\"c6\" value=\"".stripslashes($description)."\" size=29></td></tr>
		<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-image'></i> รูป Logo: <font size=1>(กว้างไม่เกิน $logowidth พิกเซล)</font></td><td><input type=file name=\"c7\"><input type=hidden name=\"c7old\" value=\"$logo\"></td></tr>
		<tr><td><i class='cp4 fa fa-image'></i> รูป Banner ด้านบน: <font size=1>(กว้างไม่เกิน $bannerwidth พิกเซล)</font></td><td><input type=file name=\"c8\"></td></tr>
		<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-link'></i> ลิ้งค์ของ Banner</td><td><input type=text name=bannerlink value=\"$bannerlinks\"></td></tr>
		</table>
  </div>
  <div id=\"tabs-2\">
		<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
		<tr><td colspan=2 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-cog'></i> ตั้งค่ากำหนดพื้นฐาน /  ข้อมูลร้านค้า</b></font></td></tr>
		<tr><td colspan=2><hr class='style-two'></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-bookmark'></i> ชื่อร้าน (แสดงในหน้าติดต่อสอบถาม/ใบเสร็จรับเงิน): </td><td><input type=text name=\"sname\" value=\"$shopname\" size=29></font></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-user'></i> ชื่อเจ้าของร้าน (ชื่อผู้รับเงินในใบเสร็จรับเงิน): </td><td><input type=text name=\"sowner\" value=\"$shopowner\" size=29></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i cp4 class='cp4 fa fa-home'></i> ที่อยู่ร้าน (แสดงหน้าติดต่อสอบถาม/ใบเสร็จรับเงิน): </td><td><textarea name=\"saddr\" cols=30 rows=3>$shopaddr</textarea></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-phone'></i> เบอร์โทรศัพท์ (แสดงหน้าติดต่อสอบถาม/ใบเสร็จรับเงิน): </td><td><input type=text name=\"stelno\" value=\"$shoptelno\" size=29></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-check-square'></i> เลขทะเบียนพาณิชย์อิเล็กทรอนิกส์ (DBD Registered): </td><td><input type=text name=\"sdbd\" value=\"$dbd\" size=29></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-map'></i> แผนที่ร้านค้า (Google Map) <a href=\"http://muit.mahidol.ac.th/km_multi/km_multi_003.htm\" target=\"_blank\"><u>ดูวิธีทำ</u></a><br><br>
		<font color=#ff0000>ให้ Copy ลิงค์ จากช่อง Paste link in email or IM มากรอก ในช่องด้านขวามือ --&gt;</font><br><br>ตัวอย่าง: https://www.google.com/maps/d/u/0/embed?mid=1HxZBDkJ3pSyg6-IDFC7B5Gv1emo&ll=13.903111251865301%2C100.49140623339599&z=12</td><td><textarea name=\"smap\" cols=30 rows=7>$googlemap</textarea></td></tr>		
		</table>
</div>
 <div id=\"tabs-3\">
 		<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
		<tr><td colspan=2 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-cog'></i> ตั้งค่ากำหนดพื้นฐาน /  Social Network</b></font></td></tr>
		<tr><td colspan=2><hr class='style-two'></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-facebook'></i> Facebook: https://www.facebook.com/<span id=\"demo1\"></span></td><td><input type=text id=\"fbuser\" name=\"fbuser\" value=\"$facebook\" size=29 oninput=\"myFunction('fbuser','demo1')\"></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-twitter'></i> Twitter: https://www.twitter.com/<span id=\"demo2\"></span></td><td><input type=text id=\"twtuser\" name=\"twtuser\" value=\"$twitter\" size=29  oninput=\"myFunction('twtuser','demo2')\"></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-google-plus'></i> Google+: https://plus.google.com/<span id=\"demo3\"></span></td><td><input type=text id=\"gplususer\" name=\"gplususer\" value=\"$googleplus\" size=29  oninput=\"myFunction('gplususer','demo3')\"></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-instagram'></i> Instagram: https://www.instagram.com/<span id=\"demo4\"></span></td><td><input type=text id=\"instguser\" name=\"instguser\" value=\"$instagram\" size=29  oninput=\"myFunction('instguser','demo4')\"></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-linkedin'></i> LinkedIn: https://www.linkedin.com/in/<span id=\"demo5\"></span></td><td><input type=text id=\"linkedinuser\" name=\"linkedinuser\" value=\"$linkedin\" size=29  oninput=\"myFunction('linkedinuser','demo5')\"></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-youtube'></i> Youtube: https://www.youtube.com/channel/<span id=\"demo6\"></span></td><td><input type=text id=\"youtubeuser\" name=\"youtubeuser\" value=\"$youtube\" size=29  oninput=\"myFunction('youtubeuser','demo6')\"></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-comment'></i> LINE Account (ถ้าเป็น LINE@ ให้พิมพ์ @ ด้วย) </td><td><input type=text name=\"lineuser\" value=\"$line\" size=29></td></tr>
		</table>
 </div>
<div id=\"tabs-4\">
		<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
		<tr><td colspan=2 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-cog'></i> ตั้งค่ากำหนดพื้นฐาน /  ตั้งค่าตัวเลือก</b></font></td></tr>
		<tr><td colspan=2><hr class='style-two'></td></tr>";
		
		if($shoppingsys==0) $opcs1=" checked"; else $opcs2=" checked";
		
		echo "
		<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-shopping-cart'></i> ระบบตะกร้าสินค้า: </td>
		<td>
			<table>
			<tr>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opcs\" id=\"opcs1\" value=\"0\"$opcs1><label for=\"opcs1\">ปิด</label></td>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opcs\" id=\"opcs2\" value=\"1\"$opcs2><label for=\"opcs2\">เปิด</label></td>
			</tr>
			</table>
		</td>
		</tr>";
		
		if($gateway==0) $opgw1=" checked"; else $opgw2=" checked";
		
		echo "
		<tr><td><i class='cp4 fa fa-credit-card'></i> ระบบชำระเงินออนไลน์ (PayPal): </td>
		<td>
			<table>
			<tr>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opgw\" id=\"opgw1\" value=\"0\"$opgw1><label for=\"opgw1\">ปิด</label></td>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opgw\" id=\"opgw2\" value=\"1\"$opgw2><label for=\"opgw2\">เปิด</label></td>
			</tr>
			</table>
		</td>
		</tr>
		<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-envelope-o'></i> PayPal Account (Email): ถ้าเปิดระบบชำระเงินออนไลน์จำเป็นต้องกรอก</td><td><input type=text name=c14 value=\"$paypal\" size=29></td></tr>";
		
		if($gateway2==0) $opgw21=" checked"; else $opgw22=" checked";
		
		echo "
		<tr><td><i class='cp4 fa fa-credit-card'></i> ระบบชำระเงินออนไลน์ (PaySbuy): </td>
		<td>
			<table>
			<tr>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opgw2\" id=\"opgw21\" value=\"0\"$opgw21><label for=\"opgw21\">ปิด</label></td>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opgw2\" id=\"opgw22\" value=\"1\"$opgw22><label for=\"opgw22\">เปิด</label></td>
			</tr>
			</table>
		</td>
		</tr>
		<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-envelope-o'></i> PaySbuy Account (Email): ถ้าเปิดระบบ PaySbuy จำเป็นต้องกรอก</td><td><input type=text name=c15 value=\"$paysbuy\" size=29></td></tr>";
		

	if($fbcomment==0) $opfb1=" checked"; else $opfb2=" checked"; 
		echo "
		<tr><td><i class='cp4 fa fa-comment-o'></i> Facebook คอมเม้นต์: </td>
		<td>
			<table><tr>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opfb\" id=\"opfb1\" value=\"0\"$opfb1><label for=\"opfb1\">ปิด</label></td>
			<td><input class=\"magic-radio\" type=\"radio\" name=\"opfb\" id=\"opfb2\" value=\"1\"$opfb2><label for=\"opfb2\">เปิด</label></td>
			</tr></table>
	</td>
	</tr>";
	
	
	if($bbsys==0) $opbs1=" checked"; else $opbs2=" checked"; 
	$allowposter = ($bbsys==2) ? " checked" : "";
	
	echo "
	<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-comments-o'></i> ระบบ เว็บบอร์ด (ถาม-ตอบปัญหา ทั่วไป): </td>
	<td>
		<table>
		<tr>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opbs\" id=\"opbs1\" value=\"0\"$opbs1><label for=\"opbs1\">ปิด</label></td>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opbs\" id=\"opbs2\" value=\"1\"$opbs2><label for=\"opbs2\">เปิด</label></td>
		<td><input class=\"magic-checkbox\" type=\"checkbox\" name=\"allowposter\" id=\"allowposter\" value=1".$allowposter."><label for=\"allowposter\">อนุญาตให้ผู้อ่านตอบคำถามได้</label></td>
		</tr>
		</table>
	</td>
	</tr>";
	
	if($reviewsys==0) $opreview1=" checked"; else $opreview2=" checked"; 
	$allowmbrw = ($reviewsys==2) ? " checked" : "";
	
	echo "
	<tr><td><i class='cp4 fa fa-star'></i> ระบบ รีวิว Rating สินค้า: </td>
	<td>
		<table><tr>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opreview\" id=\"opreview1\" value=\"0\"$opreview1><label for=\"opreview1\">ปิด</label></td>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opreview\" id=\"opreview2\" value=\"1\"$opreview2><label for=\"opreview2\">เปิด</label></td>
		<td><input class=\"magic-checkbox\" type=\"checkbox\" name=\"allowmbrw\" id=\"allowmbrw\" value=1".$allowmbrw."><label for=\"allowmbrw\">อนุญาตเฉพาะสมาชิกเท่านั้น</label></td>
		</tr></table>
	</td>
	</tr>";
	
	if($commentsys==0) $opcomment1=" checked"; else $opcomment2=" checked"; 
	$allowmbcm = ($commentsys==2) ? " checked" : "";
	
	echo "
	<tr bgcolor=#eeeeee><td><i class='cp4 fa fa-comments'></i> ระบบ คอมเม้นต์ (ถาม-ตอบปัญหา) ใต้ภาพสินค้า/บทความ: </td>
	<td>
		<table><tr>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opcomment\" id=\"opcomment1\" value=\"0\"$opcomment1><label for=\"opcomment1\">ปิด</label></td>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opcomment\" id=\"opcomment2\" value=\"1\"$opcomment2><label for=\"opcomment2\">เปิด</label></td>
		<td><input class=\"magic-checkbox\" type=\"checkbox\" name=\"allowmbcm\" id=\"allowmbcm\" value=1".$allowmbcm."><label for=\"allowmbcm\">อนุญาตเฉพาะสมาชิกเท่านั้น</label></td>
		</tr></table>
	</td>
	</tr>";
	
	if($slideshow==0) $opsdw1=" checked"; else $opsdw2=" checked";
		
	echo "
	<tr><td><i class='cp4 fa fa-image'></i> ระบบ สไลด์โชว์หน้าแรก-ร้านค้า:<br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=red>ก่อนเปิดใช้งาน ต้อง Upload รูปภาพไว้ใน แกลเลอรี่ภาพสไลด์ ก่อน</font></td>
	<td>
		<table>
		<tr>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opslide\" id=\"opslide1\" value=\"0\"$opsdw1><label for=\"opslide1\">ปิด</label></td>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"opslide\" id=\"opslide2\" value=\"1\"$opsdw2><label for=\"opslide2\">เปิด</label></td>
		</tr>
		</table>
	</td>
	</tr>";
	
	if($treeview==0) $optmenutree1 =" checked"; else $optmenutree2=" checked";
		
	echo "
	<tr><td><i class='cp4 fa fa-list'></i> แสดงหมวดสินค้าแบบ Treeview: (<font color=red>ตัวเลือกนี้ ยกเลิกการใช้งานแล้ว</font>)<br></td>
	<td>
		<table>
		<tr>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"optmenutreeview\" id=\"optview1\" value=\"0\"$optmenutree1><label for=\"optview1\">ปิด</label></td>
		<td><input class=\"magic-radio\" type=\"radio\" name=\"optmenutreeview\" id=\"optview2\" value=\"1\"$optmenutree2 disabled><label for=\"optview2\">เปิด</label></td>
		</tr>
		</table>
	</td>
	</tr>";
	
	echo "
	</table>
  </div>
  <div id=\"tabs-5\">
  	<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
	<tr><td colspan=2 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-cog'></i> ตั้งค่ากำหนดพื้นฐาน /  สีของเว็บไซต์  (** เมนูนี้ยังไม่สมบูรณ์ ***)</b></font></td></tr>
	<tr><td colspan=2><hr class='style-two'></td></tr>
	<tr><td></td><td><b>สีเว็บไซต์</b><br>เปลี่ยนสีเว็บไซต์ ใส่ Code สีลงไปในช่อง หรือ คลิก รูปกระป๋องสี<br><a href=\"$folder/color.gif\" target=\"_blank\">ค่าสี Default ของโปรแกรม</a></td></tr>";
	for($i=1; $i<7; $i++)
	{
		$c = ($i-1);
		$bgweb = ($i==6)? " <font size=1>(สีพื้นหลังเว็บ)</font>" : "";
		echo "<tr><td>Color $i:$bgweb</td>";
		//echo "<td><input type=text name=\"co$i\" value=\"#$sitecolors[$c]\" style=\"background-color: $sitecolors[$c]\"> <a href=\"#\" onclick=\"popupcolor('$folder','co$i')\" title=\"เปลี่ยนสี Color$i\"><img src=\"$folder/java/images/ed_color_bg.gif\" border=0></a></td>";
		echo "<td><input type=text name=\"co$i\" value=\"#$sitecolors[$c]\" class=\"jscolor\"></td></tr>\n";
	}
	echo "
 	</table>
  </div>
  <div id=\"tabs-6\">
 		<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
		<tr><td colspan=2 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-cog'></i> ตั้งค่ากำหนดพื้นฐาน / โปรโมชั่น-ส่วนลด (** เมนูนี้ยังไม่สมบูรณ์ ***)</b></font></td></tr>
		<tr><td colspan=2><hr class='style-two'></td></tr>
		<tr><td colspan=2>รหัสคูปอง-ส่วนลด นี้ จะแสดงในพื้นที่สำหรับสมาชิก เมื่อสมาชิก Login จะเห็นเฉพาะ รหัสคูปอง-ส่วนลด ตามระดับ (Level) ของตนเอง<br>หากท่านไม่มี คูปองส่วนลด/การให้คะแนนสะสม ให้เว้นว่างไว้
		ท่านสามารถปรับระดับสมาชิก, คะแนนสะสม ลูกค้าได้ ที่เมนู ลูกค้า/สมาชิก<br><br></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-user'></i> ส่วนลดสำหรับ ลูกค้า/สมาชิก: </td><td><input type=text name=\"mdisc\" value=\"$mdiscount\" size=15> %</td></tr>
		<tr><td valign=top><i class='cp4 fa fa-barcode'></i> รหัสคูปองส่วนลด:</td><td><input type=text name=\"mcode\" value=\"$mcoupon\" size=15></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-user-plus'></i> ส่วนลดสำหรับ ลูกค้า/สมาชิก คนพิเศษ (VIP):</td><td><input type=text name=\"vipdisc\" value=\"$vipdiscount\" size=15> %</td></tr>
		<tr><td valign=top><i class='cp4 fa fa-barcode'></i> รหัสคูปองส่วนลด: </td><td><input type=text name=\"vipcode\" value=\"$vipcoupon\" size=15></td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-comment-o'></i> ข้อความทักทาย ลูกค้า/สมาชิก:<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[shopname] = ชื่อร้าน<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[discount] = ส่วนลด<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[coupon] = รหัสคูปอง<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระบบจะแทนที่ค่าตัวแปรให้เองโดยอัตโนมัติ</td>
		<td><textarea name=\"greeting\" cols=50 rows=5>$greetingmsg</textarea></td></tr>
		<tr><td valign=top><i class='cp4 fa fa-star'></i> คะแนนสะสม (ทุก 100 บาท ให้กี่คะแนน) : </td><td><input type=text name=\"tpoints\" value=\"$points\" size=15> คะแนน</td></tr>
		<tr bgcolor=#eeeeee><td valign=top><i class='cp4 fa fa-comment'></i> ข้อความแนะนำโปรโมชั่นใหม่:</td><td><textarea name=\"promotion\" cols=50 rows=5>$promotionmsg</textarea></td></tr>
		</table>
 </div>
  
 </div>
  
 <center><br><input type=button value=\"      บันทึก     \" onclick=\"saveconf()\"></center>
 </form>
 </center>";
 
break;

case "saveconfig" :

global $fix, $connection;

if(trim($c2)!="") 
	{   if($c2==$c22) 
	   { 
	   $c222="password='".md5(trim($c2))."',";  
       $textalert="sweetAlert('เปลี่ยนรหัสผ่านเรียบร้อยแล้ว\\nหลังจากนี้ท่านจะต้องล็อคอินใหม่.');"; 
	   }else{	 
			//redirect("?action=config","sweetAlert('รหัสผ่านไม่เหมือนกันทั้ง 2 ช่อง');"); exit; 
			echo "<script>sweetAlert('รหัสผ่านไม่เหมือนกันทั้ง 2 ช่อง');</script>";
			echo "<div class='boxshadow boxred' align=center><h1>รหัสผ่านไม่เหมือนกันทั้ง 2 ช่อง</h1><a href=?action=config>กลับไปแก้ไขใหม่</a></div><br>";
			exit;
		}
	}
	
$c4 = preg_replace('/http:\/\//','',$c4);
$c4 = preg_replace('/www./','',$c4);

if($_FILES["c7"]["size"]>0)	  
	{ $c7=check_image($_FILES["c7"]["tmp_name"],$_FILES["c7"]["type"],"1",date("U")); 
       $c7 = preg_replace('/none/','',$c7);
if(isset($c7)){ 
	@unlink("$folder/$logo"); 
	@unlink("$folder/thumb_$logo");  
	$slogo = "logo='$c7',"; 
	                }
	}


if($_FILES["c8"]["size"]>0)   
	{ $c8=check_image($_FILES["c8"]["tmp_name"],$_FILES["c8"]["type"],"2",date("U")+1); 
       $c8 = preg_replace('/none/','',$c8);
if(isset($c8)){ 
	@unlink("$folder/$banner"); 
	@unlink("$folder/thumb_$banner");  
	$sbanner = ",banner='$c8@".$_POST["bannerlink"]."'"; 
	}else
	$sbanner = ",banner='$banner@".$_POST["bannerlink"]."'"; 		      
	}else
	$sbanner = ",banner='$banner@".$_POST["bannerlink"]."'"; 		      


$sitecolor = preg_replace('/#/','',$co1."@".$co2."@".$co3."@".$co4."@".$co5."@".$co6);

$allowposter = ($opbs<1) ? "0" : $allowposter;
$opbs = ($opbs+$allowposter);

$allowmbrw = ($opreview<1) ? "0" : $allowmbrw;
$opreview = ($opreview+$allowmbrw);

$allowmbcm = ($opcomment<1) ? "0" : $allowmbcm;
$opcomment = ($opcomment+$allowmbcm);

if(mysqli_query($connection,"update ".$fix."user set 
".$c222."
email='$c3',
".$slogo."
sitecolor='$sitecolor',
domain='$c4',
description='".addslashes($c6)."',
title='".addslashes($c5)."'
".$sbanner."
,shop='$opcs'
,board='$opbs'
,gateway='$opgw'
,paypal='$c14' 
,fbcomment='$opfb'
,review='$opreview'
,comment='$opcomment'
,shopname='$sname'
,shopowner='$sowner'
,shopaddr='$saddr'
,shoptelno='$stelno'
,facebook='$fbuser'
,twitter='$twtuser'
,googleplus='$gplususer'
,line='$lineuser'
,dbd='$sdbd'
,googlemap='".addslashes($smap)."'
,mdiscount='$mdisc'
,mcoupon='$mcode'
,vipdiscount='$vipdisc'
,vipcoupon='$vipcode'
,points='$tpoints'
,greetingmsg='$greeting'
,promotionmsg='$promotion'
,slideshow='$opslide'
,instagram='$instguser'
,linkedin='$linkedinuser'
,youtube='$youtubeuser'
,gateway2='$opgw2'
,paysbuy='$c15' 
,treeview='$optmenutreeview'
where userid='1'")) {
	redirect("?action=config","$textalert opensite('index.php');");
} else {
	echo "Error cloud not update data";
}
break;

case "editconfig" :

//Read Text File

$newdata = $_POST['newdata'];

if($newdata != "") {
	
$editconfigtxt = fopen("config.txt","w") or die("<center><font color=red>ผิดพลาด!ไม่สามารถเขียนไฟล์ config.php ได้<br>โปรด Chmod 777 ไฟล์ config.txt เพื่อแก้ไขข้อผิดพลาดนี้</font></center><br>");
                               fputs($editconfigtxt,$newdata);
							   fclose($editconfigtxt);
							   echo "<script language=javascript>sweetAlert('Complete','บันทึกไฟล์เรียบร้อยแล้ว','success');</script>";
							   echo "<center><font color=red>Config file Saved. บันทึกไฟล์เรียบร้อยแล้ว</font></center><br>";
							   
$editconfigphp = fopen("config.php","w") or die("<center><font color=red>ผิดพลาด!ไม่สามารถเขียนไฟล์ config.php ได้<br>โปรด Chmod 777 ไฟล์ config.php เพื่อแก้ไขข้อผิดพลาดนี้</font></center><br>");
                               fputs($editconfigphp,$newdata);
							   fclose($editconfigphp);
}

$myfile = fopen("config.txt", "r") or die("Unable to open file!");
$data = fread($myfile,filesize("config.txt"));
fclose($myfile);

echo "<center><table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
<tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-cog'></i> ตั้งค่ากำหนดขั้นสูง (ของระบบ)</b></font></td></tr>
<tr><td><hr class='style-two'></td></tr>
<tr><td><font color=red><b>คำเตือน:</b></font><br>
1. เมนูนี้สำหรับ Advance User เท่านั้น หากท่านไม่แน่ใจ กรุณาอย่าแก้ไขเด็ดขาด !!<br>
2. ก่อนทำการแก้ไขขอแนะนำให้ท่านดาวน์โหลดไฟล์ config.php เก็บไว้ก่อน โดยเข้าไปที่เมนู สำรองฐานข้อมูล<br>
3. วิธีแก้ไข เปลี่ยนค่าตัวแปรที่ต้องการ เช่น //จำนวนสินค้าใหม่ที่จะแสดงหน้าแรก \$Snew = \"6\" ให้เปลี่ยนตัวเลขในเครื่องหมายคำพูด<br>
</td></tr>
<tr><td><table width=100% cellspacing=1 cellpadding=4 bgcolor=gray>
<tr bgcolor=#ffffff><td align=center><br><form name=\"editconf\" method=\"post\">";

echo "<textarea name=\"newdata\" rows=25 cols=90>".$data."</textarea><br>";
echo "<font color=red>กรุณาตรวจสอบให้แน่ใจ ก่อนทำการบันทึกข้อมูล</font><br><br>";
echo "<input type=submit name=submit value=\" บันทึกข้อมูล \" onClick=areyousure()></form></center>";
echo "</td></tr></table></td></tr></table></center>";

break;

case "add" :
mysqli_query($connection,"insert into ".$fix."categories (category) values ('$category')");
createcategoryfile();
redirect("?action=main","");
break;

case "addsub" :
mysqli_query($connection,"insert into ".$fix."subcategories (category,subcategory) values ('0','$subcategory')");
createsubcategoryfile();
redirect("?action=subcategory","");
break;


case "update" :
mysqli_query($connection,"update ".$fix."categories set category='$category' where id='$id'");
createcategoryfile();
redirect("?action=main","");
break;

case "updatesubcat" :
mysqli_query($connection,"update ".$fix."subcategories set subcategory='$subcategory' where id='$id'");
createsubcategoryfile();
redirect("?action=subcategory","");
break;


case "savesubcat" :
mysqli_query($connection,"update ".$fix."subcategories set category='$changecat' where id='$id'");
createsubcategoryfile();
redirect("?action=subcategory","");
break;


case "savecatopt" :
	@mysqli_query($connection,"update ".$fix."categories set display='$value' where id='$id' ");
createcategoryfile();
redirect("?action=main","");
break;


case "main" :
echo "<center>";
echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr><td colspan=5 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-wrench'></i> จัดการแผนกสินค้า</b></font></td></tr><tr><td>";
$result = mysqli_query($connection,"select * from ".$fix."categories order by id asc");
echo "<hr class='style-two'><a href=\"javascript: addcat('1','add')\" title=\"เพิ่มแผนกใหม่\"><i class='boxshadow boxlemon fa fa-plus'> เพิ่มแผนกใหม่</i></a><br><br><table width=\"100%\" cellspacing=2 cellpadding=3><tr bgcolor=#C3E3FD><td>ชื่อแผนก</td><td>รูปแบบการแสดงสินค้าในแผนก</td><td align=center>แก้ไข</td><td align=center>ลบ</td></tr>";
$i=1;
 while($arr = mysqli_fetch_array($result))
  {

	if($arr[2]==0){ $value0 = " checked"; $value1 = "";  $value2 = "";}
	if($arr[2]==1){ $value1 = " checked"; $value2 = "";  $value0 = "";}
	if($arr[2]==2){ $value2 = " checked"; $value0 = "";  $value1 = "";}

	echo "<tr>
	<td bgcolor=#EEEEEE><font color=green>$i.<b>".stripslashes($arr[1])."</b></font></td>
	<td bgcolor=#EDD9BF>
	<table border=0 cellspacing=5 cellpadding=5><tr>
	<td><input type=radio name=$arr[0] id=a0$i onclick=\"javascript: location='backshopoffice.php?action=savecatopt&id=$arr[0]&value=0';\"$value0><label for=a0$i><span></span>ข้อความลิ้งค์</label></td>
	<td><input type=radio name=$arr[0] id=a1$i onclick=\"javascript: location='backshopoffice.php?action=savecatopt&id=$arr[0]&value=1';\"$value1><label for=a1$i><span></span>รูปภาพ</label></td>
	<td><input type=radio name=$arr[0] id=a2$i onclick=\"javascript: location='backshopoffice.php?action=savecatopt&id=$arr[0]&value=2';\"$value2><label for=a2$i><span></span>รูปภาพ+ข้อความลิ้งค์</label></td>
	</tr></table>
	</td>
	<td align=center width=\"5%\" bgcolor=\"orange\"><a href=\"javascript: editcat('1','$arr[0]')\" title=\"แก้ไขชื่อแผนก\"><i class='fa fa-edit' style='color:#ffffff; font-size:15px;'></a></td>
	<td align=center width=\"5%\" bgcolor=\"red\"><a href=\"javascript: category_act('2','$arr[0]')\" title=\"ลบแผนก\"><i class='fa fa-remove' style='color:#ffffff; font-size:15px;'></a></td>
	</tr>"; 
	
	$i++;
	
  }

echo "</table></td></tr></table>";
echo "</center>";
break;

case "subcategory" :
echo "<center>";
echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr><td colspan=5 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-wrench'></i> จัดการหมวดสินค้า</b></font></td></tr><tr><td>";
$result = mysqli_query($connection,"select * from ".$fix."subcategories order by id asc");
$result2 = mysqli_query($connection,"select * from ".$fix."categories");
echo "<hr class='style-two'><a href=\"javascript: addsubcat('1','addsub')\" title=\"เพิ่มหมวดสินค้าใหม่\"><i class='boxshadow boxlemon fa fa-plus'> เพิ่มหมวดสินค้าใหม่</i></a> * เมื่อเพิ่มหมวดสินค้าแล้ว ให้คลิกเลือก แผนกสินค้า ด้วย
<br><br><table width=\"100%\" cellspacing=2 cellpadding=3><tr bgcolor=#C3E3FD><td>หมวดสินค้า (Sub-Category)</td><td>แผนกสินค้า (Category)</td><td align=center>แก้ไข</td><td align=center>ลบ</td></tr>";
$i=1;
 while($arr = mysqli_fetch_array($result))
  {

	$catid = $arr[1];
	echo "<form action=\"?action=savesubcat\" method=post>
	<tr>
	<td bgcolor=#EDD9BF><font color=green><input class='tblogin' type=text name=subcat value='".stripslashes($arr[2])."' readonly></b></font></td>
	<td bgcolor=#EDD9BF>
	<input type=\"hidden\" name=\"id\" value=".$arr[0].">&nbsp;&nbsp;";
	changecat2($catid); 
	echo "</td>
	<td align=center width=\"5%\" bgcolor=\"orange\"><a href=\"javascript: editsubcat('1','$arr[0]')\" title=\"แก้ไขชื่อหมวด\"><i class='fa fa-edit' style='color:#ffffff; font-size:15px;'></a></td>
	<td align=center width=\"5%\" bgcolor=\"red\"><a href=\"javascript: subcategory_act('2','$arr[0]')\" title=\"ลบหมวด\"><i class='fa fa-remove' style='color:#ffffff; font-size:15px;'></a></td>
	</form>
	</tr>"; 
	
	$i++;
	
  }

echo "</table></td></tr></table>";
echo "</center>";
break;


case "menu" :
echo "<center>";
echo "<script language=\"javascript\">
function edit_id(act,ids)
{	pid = product.elements['p_'+ids].value;
if(act==1)
	{  if(pid>0){	location='?action=editinfo&id='+pid;	}
         else	   {	sweetAlert('โปรดเลือกเมนูก่อน');	 product.elements['p_'+ids].focus();	    }
	}
	else if(act==2){

	if(pid>0){
		
		title = 'คำเตือน'; text = 'ท่านแน่ใจที่จะลบเมนูที่เลือก?'; 
		swal(title,text);						

		swal({
			title: title,
			text: text,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn-danger',
			confirmButtonText: 'ใช่, ต้องการลบ',
			cancelButtonText: 'ไม่, ยกเลิกการลบ',
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal('Deleted!', 'เมนูถูกลบออกเรียบร้อยแล้ว', 'success');
				location='?action=delstory&id='+pid;
			} else {
			swal('Cancelled', 'ยกเลิกการลบเมนูแล้ว :)', 'error');
			}
		});
		
	    }else{ sweetAlert('โปรดเลือกเมนูก่อน');  product.elements['p_'+ids].focus();	  }
	}
}
</script>
<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><form name=\"product\"><tr><td colspan=5 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-list'></i> จัดการเมนูหน้าร้าน</b></font></td></tr><tr><td colspan=4><hr class='style-two'></td></tr>";

echo "<tr><td bgcolor=\"$color2\" align=center>";
showsub("L","เมนูหลัก");
echo "</td>
<!--td bgcolor=\"#66CC66\" align=center width=\"5%\"><a href=\"?action=editinfo&catid=L\" title=\"เพิ่มเนื้อหาใหม่\"><i class='fa fa-plus' style='color:#ffffff; font-size:15px;'></a></td-->
<td align=center width=\"5%\" bgcolor=\"orange\"><a href=\"javascript: edit_id('1','L')\" title=\"แก้ไขเนื้อหาที่เลือก\"><i class='fa fa-edit' style='color:#ffffff; font-size:15px;'></a></td>
<!--td align=center width=\"5%\" bgcolor=\"red\"><a href=\"javascript: edit_id('2','L')\" title=\"ลบเนื้อหาที่เลือก\"><i class='fa fa-remove' style='color:#ffffff; font-size:15px;'></a></td></tr-->";

echo"</form></table>";
echo "</center>";
break;

case "product" :
echo "<center>";
echo "<script language=\"javascript\">
function edit_id(act,ids)
{	pid = product.elements['p_'+ids].value;
if(act==1)
	{  if(pid>0){	location='?action=editinfo&id='+pid;	}
         else	   {	sweetAlert('โปรดเลือกรายการสินค้าก่อน');	 product.elements['p_'+ids].focus();	    }
	}
	else if(act==2){

	if(pid>0){
		
		//if(confirm('ลบรายการสินค้าที่เลือก?')==true) { location='?action=delstory&id='+pid; 	}

		title = 'คำเตือน'; text = 'ท่านแน่ใจที่จะลบรายการสินค้าที่เลือก?'; 
		swal(title,text);						

		swal({
			title: title,
			text: text,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn-danger',
			confirmButtonText: 'ใช่, ต้องการลบ',
			cancelButtonText: 'ไม่, ยกเลิกการลบ',
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal('Deleted!', 'สินค้าถูกลบออกเรียบร้อยแล้ว', 'success');
				location='?action=delstory&id='+pid;
			} else {
			swal('Cancelled', 'ยกเลิกการลบสินค้าแล้ว :)', 'error');
			}
		});
		
	    }else{ sweetAlert('โปรดเลือกสินค้าก่อน');  product.elements['p_'+ids].focus();	  }
	}
}
</script>
<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><form name=\"product\"><tr><td colspan=5 bgcolor=\"#98C230\"><font color=#ffffff><b><i class='fa fa-wrench'></i> จัดการสินค้า</b></font></td></tr><tr><td colspan=4><hr class='style-two'></td></tr>";

$result = mysqli_query($connection,"select * from ".$fix."categories order by id asc");
if(mysqli_num_rows($result))
{ while($allcat = mysqli_fetch_array($result))
  {
    echo "<tr><td bgcolor=\"#EEEEEE\">";
	showsub($allcat[0],stripslashes($allcat[1]));
    echo "</td><td bgcolor=\"#66CC66\" align=center width=\"5%\"><a href=\"?action=editinfo&catid=$allcat[0]\" title=\"เพิ่มเนื้อหาใหม่\"><i class='fa fa-plus' style='color:#ffffff; font-size:15px;'></a></td>
    <td align=center width=\"5%\" bgcolor=\"orange\"><a href=\"javascript: edit_id('1','$allcat[0]')\" title=\"แก้ไขเนื้อหาที่เลือก\"><i class='fa fa-edit' style='color:#ffffff; font-size:15px;'></a></td>
   <td align=center width=\"5%\" bgcolor=\"red\"><a href=\"javascript: edit_id('2','$allcat[0]')\" title=\"ลบเนื้อหาที่เลือก\"><i class='fa fa-remove' style='color:#ffffff; font-size:15px;'></a></td></tr>";
  }
mysqli_free_result($result);
}

echo"</form></table>";
echo "</center>";
break;

case "editinfo" :

if($id)
{
      $result = mysqli_query($connection,"select * from ".$fix."catalog where idp='$id'");
      $array = @mysqli_fetch_array($result);
	  $subcat = $array[12];
	  $result2 = mysqli_query($connection,"select * from ".$fix."subcategories where id='$subcat'");
	  $array2 = @mysqli_fetch_array($result2);
	  if($id > '9') {
		$atitle = "<i class='fa fa-wrench'></i> จัดการสินค้า";
		$topt   =   "ปรับปรุงข้อมูลสินค้า";
		$subtitle = "ข้อมูลสินค้า โดยย่อ:";
		$ptitle = "ชื่อสินค้า";
	  } else {
		 $atitle = "<i class='fa fa-list'></i> จัดการเมนูหน้าร้าน";
		 $topt   =   "ปรับปรุงเนื้อหา";
		 $subtitle = "รายละเอียด/คำอธิบาย:";
		 $ptitle = "ชื่อเมนู";
	  }
	  $hiddenform = "<input type=hidden name=\"id\" value=\"$array[0]\"><input type=hidden name=\"save\" value=\"2\">";
}
elseif($catid)
{
	if($catid=="L")
	{
		$result = mysqli_query($connection,"select category from ".$fix."catalog where category like 'L%' ");
		$Lrow = mysqli_num_rows($result);
		$cid = ($Lrow) ? "L".($Lrow+1)."" : "L1";
		$topt = "เพิ่มเมนูหลัก";
	}
	else
	{
		$result = mysqli_query($connection,"select * from ".$fix."categories where id='$catid'");
	    $result2 = mysqli_query($connection,"select * from ".$fix."subcategories");
		$array2 = @mysqli_fetch_array($result2);
		$atitle = "<i class='fa fa-wrench'></i> จัดการสินค้า";
		$topt   =  "เพิ่ม สินค้า ใหม่ แผนก: <font color=blue>".stripslashes(mysqli_result($result,0,"category"))."</font>";
		$ptitle = "ชื่อสินค้า";
		$cid       = mysqli_result($result,0,"id");
	 }
	$hiddenform =  "<input type=hidden name=\"category\" value=\"$cid\"><input type=hidden name=\"subcategory\" value=\"$subcat\"><input type=hidden name=\"save\" value=\"1\">";
}

echo "<center>";
echo"
<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><form name=\"alltext\" action=\"backshopoffice.php\" method=\"post\" enctype=\"multipart/form-data\">$hiddenform
<tr bgcolor=\"#98C230\"><td colspan=2><font color=#ffffff><b>$atitle</b> / <b>$topt </b></font></td></tr>
<tr><td colspan=4><hr class='style-two'></td></tr>";

if($array[1])
{ 
	if(preg_match("/L/",$array[1])) echo "<input type=hidden name=\"changecat\" value=\"$array[1]\">";
	else{
		echo "<tr><td>แผนก (Category):</td><td>"; 
		changecat($array[1]); 
		echo "</td></tr>"; 
	}
}

if(!preg_match("/L/",$array[1])) {
	echo "<input type=hidden name=\"changesubcat\" value=\"$subcat\">";
	echo "<tr><td>หมวด (Sub-Category):</td><td>"; 
	changesubcat($subcat); 
	echo "</td></tr>"; 
}


if(!preg_match("/LA/",$array[1]))
	{
       echo "<tr><td><b>$ptitle:</b></td><td><input type=text name=title size=40  value=\"".stripslashes($array[2])."\" maxlength=100></td></tr>";
	   $category = $array[1];
       $allpic = @explode("@",$array[4]);
       if($array[4]) echo "<input type=hidden name=allpicture value=\"$array[4]\">";
          for($i=0; $i<$Limages; $i++)
	      {
                if($allpic[$i])
	                 echo "<tr><td>ภาพประกอบ ".($i+1)."</td><td><input type=file name=\"picture[]\" style=\"font-size: 8pt;\" disabled> <a href=\"$folder/$allpic[$i]\" target=\"_blank\" title=\"คลิก..ขยายรูปภาพที่ ".($i+1)."\"><img src=\"$folder/thumb_$allpic[$i]\" border=0 width=25 height=25></a> <a href=\"#\" onclick=\"jumpto('?action=delpic&id=$array[0]&pn=$allpic[$i]')\" title=\"ลบรูปภาพที่ ".($i+1)."\"><img src=\"$folder/remove_gif.gif\" border=0></a></td></tr>";
               else
                     echo "<tr><td>ภาพประกอบ ".($i+1)."</td><td><input type=file name=\"picture[]\" style=\"font-size: 8pt;\"></td></tr>";
	      }
		  echo "<tr><td colspan=3><font size=2 face=\"MS Sans serif\" color=red>[ไฟล์รูปภาพควรเป็น .jpg .gif แนะนำ .jpg เพราะโปรแกรมจะย่ออัตโนมัติได้ - ไม่ควรใช้ไฟล์ภาพที่มีขนาดใหญ่เกิน]</font><br><br></td></tr>";
	}else

		echo "<tr><td colspan=3><font color=green><b>แลกลิ้งค์:</b> ตำแหน่งใต้ สถิติผู้เข้าชมเว็บไซต์ ที่เมนูด้านซ้าย<br>วิธีทำ: ใส่โค๊ด Html ในช่อง รายละเอียด แล้วบันทึก</font><input type=hidden name=title value=\"".stripslashes($array[2])."\"></td></tr>";

$linktoeditor = ($editor=="1") ? str_replace("&editor=1","","<a class='boxshadow boxred' href=\"$REQUEST_URI&editor=1\">ปิดการใช้งาน Html Editor</a>") : "<a class='boxshadow boxlemon' href=\"$REQUEST_URI&editor=1\" title=\"ถ้าบราวเซอร์ของคุณ ไม่สนับสนุน Html Editor นี้\nการทำงานและการแสดงผลต่างๆอาจผิดพลาดได้\">เปิดใช้งาน Html Editor</a>";
echo "<tr><td valign=top colspan=3><b>$subtitle</b> $linktoeditor<br><br><textarea name=\"story\" id=\"story\" rows=5 cols=90>".stripslashes($array[3])."</textarea>";

if($editor=="1")
	echo "
		<script src=\"ckeditor/ckeditor.js\"></script>
         <script>
                CKEDITOR.replace(\"story\");
         </script>";
	echo "</td></tr><tr><td></td><td></td></tr>";
	
/////////////////////////////////////////////////////////////////////////////

	if( ($catid!="L") && (!preg_match("/L/",$array[1])) )
	{
		if($shoppingsys)
		{
			
			$linktoeditor2 = ($editor2=="1") ? str_replace("&editor2=1","","<a class='boxshadow boxred' href=\"$REQUEST_URI&editor2=1\">ปิดการใช้งาน Html Editor</a>") : "<a class='boxshadow boxlemon' href=\"$REQUEST_URI&editor2=1\" title=\"ถ้าบราวเซอร์ของคุณ ไม่สนับสนุน Html Editor นี้\nการทำงานและการแสดงผลต่างๆอาจผิดพลาดได้\">เปิดใช้งาน Html Editor</a>";
			echo "<tr><td valign=top colspan=3><b>ข้อมูลสินค้า โดยละเอียด (หากไม่กรอก ระบบจะนำข้อมูลสินค้าโดยย่อไปแสดงแทน):</b> $linktoeditor2<br><br><textarea name=\"details\" id=\"details\" rows=15 cols=90>".stripslashes($array[10])."</textarea>";

			if($editor2=="1")
			echo "
				<script src=\"ckeditor/ckeditor.js\"></script>
				<script>
						CKEDITOR.replace(\"details\");
				</script>";
			echo "</td></tr><tr><td></td><td></td></tr>";
	
            echo "<tr>
			<td colspan=3>
			คำแนะนำ
			<ul>
			<li>หากสินค้าชนิดนี้มี หลายสี/หลายไซส์/หลายราคา กรุณาระบุรายละเอียด</li>
			<li>ต้องกรอกทั้ง 2 ราคา ระบบจะคำนวนค่าสินค้าจากราคาขายจริง</li>
			<li>ถ้าราคาตั้ง=ราคาขายจริง ถือเป็นสินค้าราคาปกติ</li>
			<li>ถ้าราคาขายจริง < ราคาตั้ง ถือเป็นสินค้าโปรโมชั่นลดราคา</li>
			<li>ในสต๊อก 1 = มีในสต๊อก / 0=สินค้าหมดชั่วคราว (ถ้าสินค้าหมดถาวรหรือเลิกจำหน่ายแนะนำให้ลบออก)</li>
			</ul>
			<br><br>
			<table width=100% bgcolor=\"#eeeeee\" cellspacing=1 cellpadding=3>
			<tr bgcolor=white><td>รหัสสินค้า</td><td>สินค้า (ชื่อสินค้า-สี-ไซส์)</td><td>ราคาตั้ง</td><td>ราคาขายจริง</td><td>ในสต๊อก</td><td>ขนาด</td><td>น้ำหนัก</td></tr>";
	     if($id>0)
		   {
              $optsql = mysqli_query($connection,"select * from ".$fix."product where mainid='$id' ");
                $optrow = mysqli_num_rows($optsql);
                  while($Opt = mysqli_fetch_array($optsql))
			      {
                     echo "<tr>
					 <td><input type=text name=\"Opt_pid[]\" size=10 style=\"font: 9pt\" value=\"$Opt[6]\"></td>
					 <td> <input type=hidden name=\"Opt_id[]\" value=\"$Opt[0]\"><input type=text name=\"Opt_title[]\" size=30 style=\"font: 9pt\" value=\"".stripslashes($Opt[2])."\"></td>
					 <td><input type=text name=\"Opt_price[]\" size=5 style=\"font: 9pt\" value=\"$Opt[3]\"></td>
					 <td><input type=text name=\"Opt_sale[]\" size=5 style=\"font: 9pt\" value=\"$Opt[4]\"></td>
					 <td><input type=text name=\"Opt_stock[]\" size=5 style=\"font: 9pt\" value=\"$Opt[5]\"></td>
					 <td><input type=text name=\"Opt_size[]\" size=5 style=\"font: 9pt\" value=\"$Opt[9]\"></td>
					 <td><input type=text name=\"Opt_weight[]\" size=5 style=\"font: 9pt\" value=\"$Opt[10]\"></td>
					 </tr>";
			      }
			mysqli_free_result($optsql);
		   }
                $countopt  =  ($optrow) ? ($Sproducts-$optrow) : $Sproducts;
                    for($i=0; $i<$countopt; $i++)
			         {
                       echo "<tr>
					   	<td><input type=text name=\"Opt_pid[]\" size=10 style=\"font: 9pt\"></td>
					   <td><input type=text name=\"Opt_title[]\" size=30 style=\"font: 9pt\"></td>
					   <td><input type=text name=\"Opt_price[]\" size=5 style=\"font: 9pt\"></td>
					   <td><input type=text name=\"Opt_sale[]\" size=5 style=\"font: 9pt\"></td>
					   <td><input type=text name=\"Opt_stock[]\" size=5 style=\"font: 9pt\"></td>
					   <td><input type=text name=\"Opt_size[]\" size=5 style=\"font: 9pt\"></td>
					   <td><input type=text name=\"Opt_weight[]\" size=5 style=\"font: 9pt\"></td>
					   </tr>";
			         }
echo "</table></td></tr>";	
		}


echo "<tr><td colspan=3 align=center>ราคาต่ำสุด <input type=\"text\" name=\"price\" size=\"10\" value=\"$array[9]\" required> *ใช้อ้างอิงตอนเรียงลำดับราคา (Sort by Price)</td></tr>";			
echo "<tr><td colspan=3 align=center>";

$checked1 = ($array[6]) ? " checked" : "";
$checked2 = ($array[7]) ? " checked" : "";
$checked3 = ($array[11]) ? " checked" : "";

echo "
<table>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
<td>เพิ่มสินค้าชิ้นนี้ไว้ในรายการ</td>
<td><input type=checkbox name=recom id=recom value=\"1\"$checked1><label for=recom><span></span>สินค้าแนะนำ</label></td>
<td><input type=checkbox name=bestseller id=bestseller value=\"1\"$checked2><label for=bestseller><span></span>สินค้าขายดี</label></td>
<td><input type=checkbox name=newarr id=newarr value=\"1\"$checked3><label for=newarr><span></span>สินค้ามาใหม่</label></td>
</tr></table>";

	}
	
echo "</td></tr>";

if( ($catid=="L") || (preg_match("/L/",$array[1])) )
	{
	if( (!preg_match("/L1/",$array[1])) && (!preg_match("/LA/",$array[1])) )
$tip = "";
$tip .= "Tip: พิมพ์ <font color=red>[emailform]</font> ลงในช่อง รายละเอียด ถ้าต้องการแสดงแบบฟอร์มติดต่อสอบถาม <br>";
$tip .= "Tip: พิมพ์ <font color=red>[payment]</font> ลงในช่อง รายละเอียด ถ้าต้องการแสดงบัญชีธนาคาร<br>";
$tip .= "Tip: พิมพ์ <font color=red>[shipping]</font> ลงในช่อง รายละเอียด ถ้าต้องการแสดง วิธีการจัดส่งสินค้า<br>";
$tip .= "<br><br>";
	}

echo "
<tr><td colspan=2>$tip<center><br><br>
<input type=hidden name=\"action\" value=\"save\">
<input type=\"button\" value=\"บันทึก\" onclick=\"checkstory()\" style=\"width: 200px;\"><br><br></center></td></tr></form></table>";	
break;


case "delcat" :
$sql=mysqli_query($connection,"select  idp,picture from ".$fix."catalog where category='$id'");
if(mysqli_num_rows($sql))	
{ while($arr=mysqli_fetch_array($sql))
	{		
	$picarr = explode("@",$arr[1]);
	for($i=0; $i<count($picarr); $i++)
		{
	      if($picarr[$i]){  @unlink("$folder/$picarr[$i]"); @unlink("$folder/thumb_$picarr[$i]"); }
		}
           mysqli_query($connection,"delete from ".$fix."product where mainid='$arr[0]'");
     }     
}
mysqli_free_result($sql);
     mysqli_query($connection,"delete from ".$fix."catalog where category='$id'");
     mysqli_query($connection,"delete from ".$fix."categories where id='$id'");
	 createcategoryfile();
     redirect("?action=main","");
break;


case "delsubcat" :
     mysqli_query($connection,"delete from ".$fix."subcategories where id='$id'");
	 createsubcategoryfile();
     redirect("?action=subcategory","");
break;


case "delstory" :
$result = mysqli_query($connection,"select category,picture from ".$fix."catalog where idp='$id'");
if(mysqli_num_rows($result))  $arr = mysqli_fetch_array($result);

if($arr[0]=="L1") 
	{
        redirect("?action=product","alert('หน้าแรก Home จะไม่สามารถลบออกได้ ควรใช้วิธี แก้ไขเนื้อหา.');"); exit;
	}

$picarr = explode("@",$arr[1]);
for($i=0; $i<count($picarr); $i++)
{
	if($picarr[$i]){ @unlink("$folder/$picarr[$i]");  @unlink("$folder/thumb_$picarr[$i]"); }
}

mysqli_query($connection,"delete from ".$fix."catalog where idp='$id'");
mysqli_query($connection,"delete from ".$fix."product where mainid='$id'");
if(preg_match("L",$arr[0])) createtoplink();
//redirect("?action=product","");
die();
break;



case "delpic" :
$sql = mysqli_query($connection,"select picture from ".$fix."catalog where idp='$id'");
$result = mysqli_result($sql,0);
if($result)
	{
mysqli_query($connection,"UPDATE ".$fix."catalog set picture='".preg_replace($pn."/@/","",$result)."' where idp='$id'");
@unlink("$folder/$pn"); 
@unlink("$folder/thumb_$pn"); 
	}
redirect("?action=editinfo&id=$id","");
break;

case "save" :
$pictures = $_POST["allpicture"];

for($i=0; $i<count($_FILES["picture"]["name"]); $i++)
	{
if($_FILES["picture"]["size"][$i]>0) 
$picture_a[$i] = check_image($_FILES["picture"]["tmp_name"][$i],$_FILES["picture"]["type"][$i],"3",date("U")+$i);
$pictures .= (isset($picture_a[$i])) ? $picture_a[$i]."@" : "";
	}
$pictures = preg_replace("/none@/","",$pictures);



////////////////////////////////////////////////////////// เขียนข้อมูลสินค้าลง Table smeweb_catalog


if($save==1) {
		$title=trim(addslashes($title));
		$story=trim(addslashes($story));
		$insert = mysqli_query($connection,"insert into ".$fix."catalog values ('','$category','$title','$story','$pictures','$createon','$recom','$bestseller','','$price','$details','$newarr','$subcategory')");
		if ($insert) {
			$idp=mysqli_insert_id($connection);
		} else {
			echo "<div class='boxshadow boxred' align='center'><h3><i class='fa fa-info-circle'></i> พบข้อผิดพลาด!! ไม่สามารถบันทึกข้อมูลสินค้า  - กรุณาลองใหม่อีกครั้ง</h3></div>";
			break;
		}
} elseif($save==2)  {
		$title=trim(addslashes($title));
		$story=trim(addslashes($story));
		$update = mysqli_query($connection,"update ".$fix."catalog set category='$changecat',title='$title',story='$story',picture='$pictures',createon='$createon',recom='$recom',bestseller='$bestseller',price='$price',details='$details',newarrival='$newarr',subcategory='$changesubcat' where idp='$id'");
		$idp=$id;
}

		
//////////////////////////////////////////////////////////  เขียนข้อมูลสินค้าลง Table smeweb_product
$loopproduct  = count($Opt_title);
	if($loopproduct)
	{		
         for($i=0; $i<$loopproduct; $i++)
	     {
        if($Opt_id[$i])  		        
			$update = mysqli_query($connection,"update ".$fix."product set mainid='$id', title='".trim(addslashes($Opt_title[$i]))."',price='$Opt_price[$i]',sale='$Opt_sale[$i]',stock='$Opt_stock[$i]', pid='$Opt_pid[$i]',createon='$createon',category='$changecat',size='$Opt_size[$i]',weight='$Opt_weight[$i]',subcategory='$changesubcat' where id='$Opt_id[$i]' ");	
		else 		
			$insert = mysqli_query($connection,"insert into ".$fix."product values ('','$idp','".trim(addslashes($Opt_title[$i]))."','$Opt_price[$i]','$Opt_sale[$i]','$Opt_stock[$i]','$Opt_pid[$i]','$createon','$category','$Opt_size[$i]','$Opt_weight[$i]','$subcategory')");	
	      }		  
		$delete = mysqli_query($connection,"delete from ".$fix."product where !title and !price");	
	}
////////////////////////////////////////////////////////

if( ($changecat=="L1") || ($category=="L1")  )
    {
createtoplink();
redirect("?action=product","opensite('index.php');");
exit;
	 }
if( (preg_match("/L/",$changecat)) || (preg_match("/L/",$category)) )
	{
createtoplink();
redirect("?action=menu","opensite('catalog.php?idp=$idp');");
exit;
	}
redirect("?action=product","opensite('catalog.php?idp=$idp');");
break;

case "logout" :
session_destroy();  echo "<script language=\"javascript\">location='backshopoffice.php';</script>";
break;

case "savegallery" :
for($i=0; $i<count($_FILES["picture"]["name"]); $i++)
	{
if($_FILES["picture"]["size"][$i]>0)	  
{			
check_image_ga($_FILES["picture"]["tmp_name"][$i],$_FILES["picture"]["type"][$i],"gallery",date("U")+$i); 
}
	}
redirect("?action=gallery","opensite('catalog.php?gallery=1');");
break;

case "savegallery2" :
for($i=0; $i<count($_FILES["picture"]["name"]); $i++)
	{
if($_FILES["picture"]["size"][$i]>0)	  
{			
check_image_ga($_FILES["picture"]["tmp_name"][$i],$_FILES["picture"]["type"][$i],"gallery2",date("U")+$i); 
}
	}
redirect("?action=gallery2","opensite('catalog.php?gallery2=1');");
break;


case "delgallery" :
@unlink("gallery/$img");
@unlink("gallery/thumb_$img");
redirect("?action=gallery","");
break;

case "delgallery2" :
@unlink("gallery2/$img");
@unlink("gallery2/thumb_$img");
redirect("?action=gallery2","");
break;

case "gallery" :

echo "
<script type=\"text/javascript\" src=\"js/highslide/highslide-full.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"js/highslide/highslide.css\" />
<script type=\"text/javascript\">
	hs.align = 'center';
	hs.graphicsDir = 'js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>
";

echo "<table width=\"100%\" cellspacing=2 cellpadding=3><form action=\"backshopoffice.php?action=savegallery\" method=post enctype=\"multipart/form-data\">
<tr bgcolor=\"#98C230\"><td colspan=2><font color=#ffffff><b><i class='fa fa-picture-o'></i> Gallery แกลเลอรี่ รวมรูปภาพ</b></font></td></tr>
<tr bgcolor=white><td colspan=4><hr class='style-two'></td></tr>";
echo "<tr><td colspan=2 bgcolor=white>";
gallery("gallery","1");
echo "</td></tr><tr bgcolor=\"#98C230\"><td colspan=2><center>การอัพโหลดรูปภาพจะทำให้ ("._LANG_59.") เปิดการใช้งานอัตโนมัติ<br>สามารถอัพโหลดรูปได้พร้อมกันครั้งละ 10 รูป ( เฉพาะไฟล์ .jpg .gif )<br></td></tr>
<tr bgcolor=white><td>";
echo "<table>";
for($i=0; $i<5; $i++)
	{
echo "<tr><td align=right>".($i+1).".</td><td><input type=file name=\"picture[]\" style=\"font: 9pt;\"></td></tr>";
	}
echo "</table></td><td><table>";
for($i=5; $i<10; $i++)
	{
echo "<tr><td align=center>".($i+1).".</td><td><input type=file name=\"picture[]\" style=\"font: 9pt;\"></td></tr>";
	}
echo "</table></td></tr>
<tr bgcolor=white><td colspan=2 align=center><input type=submit value=\" อัพโหลดรูปภาพ \"></td></td></td></tr></form></table>";
break;

case "gallery2" :

echo "
<script type=\"text/javascript\" src=\"js/highslide/highslide-full.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"js/highslide/highslide.css\" />
<script type=\"text/javascript\">
	hs.align = 'center';
	hs.graphicsDir = 'js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>
";

echo "<table width=\"100%\" cellspacing=2 cellpadding=3><form action=\"backshopoffice.php?action=savegallery2\" method=post enctype=\"multipart/form-data\">
<tr bgcolor=\"#98C230\"><td colspan=2><font color=#ffffff><b><i class='fa fa-picture-o'></i> Gallery ภาพสไลด์โชว์หน้าแรก</b></font></td></tr>
<tr bgcolor=white><td colspan=4><hr class='style-two'></td></tr>";
echo "<tr><td colspan=2 bgcolor=white>";
gallery2("gallery2","1");
echo "</td></tr><tr bgcolor=\"#98C230\"><td colspan=2><center><font color=white><b>คำแนะนำ: รูปภาพที่ใช้ทำสไลด์ไม่ควรเกิน 5 ภาพ ( สูงสุดใส่ได้ 10 ภาพ เฉพาะไฟล์ .jpg ) และ ต้องเป็นภาพที่มีขนาด 756x178 pixel เท่านั้น!!!</b></font></td></tr>
<tr bgcolor=white><td align=center>";
echo "<table>";
for($i=0; $i<10; $i++)
	{
echo "<tr><td align=center>".($i+1).".</td><td><input type=file name=\"picture[]\" style=\"font: 9pt;\"></td></tr>";
	}
echo "</table></td></tr>
<tr><td bgcolor=#ffffff><ul><li>หากต้องการเปลี่ยนภาพสไลด์ชุดใหม่ ให้ลบภาพเก่าออกก่อนแล้วจึงอัพโหลดภาพชุดใหม่เข้าไปแทนที่<li>หากไม่ลบภาพเก่า ระบบจะดึงภาพทั้งหมดมาทำสไลด์!!!<li>หากต้องการเพิ่มคำบรรยายภาพสไลด์ หรือใส่ Link สามารถเพิ่มได้ที่เมนู เพิ่มคำบรรยายภาพสไลด์</ul></td></tr>
<tr bgcolor=white><td colspan=2 align=center><input type=submit value=\" อัพโหลดรูปภาพ \"><br><br></td></td></td></tr></form></table>";
break;

case "sqlbackup" :
echo "<center><table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff>
<tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-history'></i> สำรองฐานข้อมูล</b></font></td></tr>
<tr><td colspan=4><hr class='style-two'></td></tr>
<tr><td>เพื่อป้องกันปัญหาการสูญหายของข้อมูล ท่านสามารถ Download ฐานข้อมูล SQL เก็บสำรองไว้ได้ตลอดเวลา</td></tr>";
echo "<tr><td>
<table width=100% cellspacing=1 cellpadding=4 bgcolor=#ffffff>
<tr><td valign=top><a href=\"backup_sql.php\"><font color=blue>Download SQL Backup (file dbname.sql)</font></a></td><td>สำรองข้อมูลทั้งหมดของเว็บไซต์ (ไม่รวมรูปภาพ และ config.php)</td></tr>
<tr><td valign=top><a href=\"sitemap.php\"><font color=blue>Download SiteMap (file sitemap.xml)</font></a></td><td>สำรองข้อมูลแผนที่เว็บไซต์ สำหรับ Google Webmaster Tools</td></tr>
<tr><td valign=top><a href=\"backup_conf.php\"><font color=blue>Download Configuration (file config.php)</font></a></td><td>สำรองข้อมูลการตั้งค่ากำหนดขั้นสูง (Configuration) ของเว็บไซต์</td></tr>
<!--tr><td valign=top><a href=\"backshopoffice.php?action=editconfig\"><font color=blue>Edit Configuration (file config.php)</font></a></td><td>แก้ไขข้อมูลการตั้งค่า Configuration  (Advance User)</td></tr-->
</table></td></tr></table></center>";
break;

case "income" :

	//*** Select Question ***//
	
	$query="select sum(totalprice) as income from ".$fix."orders where orderstatus != '0' ";
	$result = mysqli_query($connection,$query);
	$arr = mysqli_fetch_array($result);
	$income = number_format($arr[0],2);

	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	if($startdate=="") { $startdate = date("Y-m-d");}
	if($enddate=="") { $enddate = date("Y-m-d") ;}
	$strSQL = "SELECT * FROM ".$fix."orders WHERE (orderstatus = '1' or orderstatus = '2') and (orderdate >= '$startdate' and orderdate <= '$enddate') order by orderno";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$row = mysqli_num_rows($objQuery);
	
	echo "<form action=?action=income method=post>";
	echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-money'></i> สรุปรายรับ (จากยอดขายที่ลูกค้าชำระเงินแล้ว)</b></font></td></tr>
	<tr><td colspan=4><hr class='style-two'></td></tr><tr><td align=left>";
	echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\">";
	
	echo "<tr><td colspan=4 align=center><br>
	ยอดรายรับ(สะสม) ณ วันที่ ".date("d/m/Y") ." <i class='boxshadow boxred'> ".$income." </i>&nbsp;&nbsp;บาท<br><br>
	จากวันที่ <input type=text name=startdate value='".$startdate."'> <i class='fa fa-calendar-check-o'></i>&nbsp;&nbsp;&nbsp;
	ถึงวันที่ <input type=text name=enddate value='".$enddate."'> <i class='fa fa-calendar-check-o'></i>&nbsp;&nbsp;&nbsp;

			<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
			<script src=\"js/datepicker/jquery.simple-dtpicker.js\"></script>
			<link rel=\"stylesheet\" href=\"js/datepicker/jquery.simple-dtpicker.css\">
			<script type=\"text/javascript\">
			$(function(){
				$('*[name=startdate]').appendDtpicker({
					\"startdate\": \"YYYY/MM/DD/\"
				});
				$('*[name=enddate]').appendDtpicker({
					\"startdate\": \"YYYY/MM/DD/\"
				});
			});
			</script>	
			
	<input type=submit class='myButton' value= ' แสดงรายการ '><br><br></td></tr>";
	
	
	echo "<tr  bgcolor=#eeeeee><td>Order No</td><td>Order Date</td><td>Order Status</td><td align=right>Total Income</td></tr>";
	if($row >0) { 
		while($objResult = mysqli_fetch_array($objQuery))
		{
			if($objResult['orderstatus']==1) {$odstatus = "<font color=orange>1. ชำระเงินแล้ว/รอจัดส่งสินค้า</font>";}
			if($objResult['orderstatus']==2) {$odstatus = "<font color=green>2. ชำระเงินแล้ว/จัดส่งสินค้าแล้ว</font>";}		
			echo "<tr><td>".$objResult['orderno']."</td><td>".thaidate(substr($objResult['orderdate'],0,10))." ".substr($objResult['orderdate'],11,5)."</td><td>".$odstatus."</td><td align=right>".number_format($objResult['totalprice'],2)."</td></tr>";
			$grandtotal = $grandtotal + $objResult['totalprice'];
		}
		echo "<tr><td colspan=3 align=center><b>( ".bahttext($grandtotal)." )</b></td><td align=right bgcolor=#eeeeee><b>".number_format($grandtotal,2)."</b></td></tr>";
		echo "</table></td></tr></table>";
	}
	echo "</form>";
break;

}

echo "</td></tr></table></body></html>";
mysqli_close($connection);
exit;

function changecat($dat)
{ global $connection,$dbname,$fix;
$sql = mysqli_query($connection,"select * from ".$fix."categories order by id asc");
echo "<select name=\"changecat\">";
while($arr=mysqli_fetch_array($sql))
	{
if($dat==$arr[0]) echo"<option value=\"$arr[0]\" selected>".stripslashes($arr[1])."</option>";
else                       echo"<option value=\"$arr[0]\">".stripslashes($arr[1])."</option>"; 
	}
echo "</select>";
}


function changecat2($dat)
{ global $connection,$dbname,$fix;
$sql = mysqli_query($connection,"select * from ".$fix."categories order by id asc");
//echo "<select name=\"changecat\">";
echo "<select name=\"changecat\" onchange=\"this.form.submit()\">";
while($arr=mysqli_fetch_array($sql))
	{
if($dat==$arr[0]) echo"<option value=\"$arr[0]\" selected>".stripslashes($arr[1])."</option>";
else                       echo"<option value=\"$arr[0]\">".stripslashes($arr[1])."</option>"; 
	}
echo "</select>";
}

function changesubcat($dat)
{ global $connection,$dbname,$fix;
$sql = mysqli_query($connection,"select * from ".$fix."subcategories order by id asc");
echo "<select name=\"changesubcat\">
<option value=\"0\">-</option>";
while($arr=mysqli_fetch_array($sql))
	{
if($dat==$arr[0]) echo"<option value=\"$arr[0]\" selected>".stripslashes($arr[2])."</option>";
else                       echo"<option value=\"$arr[0]\">".stripslashes($arr[2])."</option>"; 
	}
echo "</select>";
}


function check_image_ga($picture,$picture_type,$folder,$pn)
{
global $thumbwidth;

if(	($picture_type=="image/jpg") || 	
($picture_type=="image/jpeg") || 	
($picture_type=="image/pjpeg") ) 
{ 	
$width = getimagesize($picture);	
$picturename = $pn.".jpg";

                                  if($width[0]>=$width[1])
	                             {
                                  $dstH = (int) (($thumbwidth*$width[1])/$width[0]);
                                  $dstW = $thumbwidth;	
                                  }
                                  else
								 {
                                  $dstW = (int) (($thumbwidth*$width[0])/$width[1]);
                                  $dstH = $thumbwidth;	
								  }

if(function_exists('ImageCreateFromJPEG'))  makeThumb( $picture,"$folder/thumb_$picturename",$dstW,$dstH );
                                                                          else     copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
}
elseif( $picture_type=="image/gif" )
	{
$picturename = $pn.".gif";
copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
	}	
}



function check_image($picture,$picture_type,$act,$name)
{
global $logowidth,$bannerwidth,$thumbwidth,$folder;
$width = getimagesize($picture);	
if( ($width[0]>$logowidth) && ($act=="1") )
echo "<script language=javascript>sweetAlert('Error','ขนาดของ Logo ไม่ควรกว้างเกิน $logowidth พิกเซล','error');</script>";
elseif( ($width[0]>$bannerwidth) && ($act=="2") )
echo "<script language=javascript>sweetAlert('Error','ขนาดของ Banner ไม่ควรกว้างเกิน $bannerwidth พิกเซล','error');</script>";
else{

if(	($picture_type=="image/jpg") || 
	($picture_type=="image/jpeg") || 
	($picture_type=="image/pjpeg")  ) { 
$picturename = $name.".jpg";

                                  if($width[0]>=$width[1])
	                             {
                                  $dstH = (int) (($thumbwidth*$width[1])/$width[0]);
                                  $dstW = $thumbwidth;	
                                  }
                                  else
								 {
                                  $dstW = (int) (($thumbwidth*$width[0])/$width[1]);
                                  $dstH = $thumbwidth;	
								  }
if(function_exists('ImageCreateFromJPEG'))  makeThumb( $picture,"$folder/thumb_$picturename",$dstW,$dstH );
                                                                          else     copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
}
elseif( $picture_type=="image/gif" )
	{
$picturename = $name.".gif";
copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
	}
}
return $picturename;
}



function makeThumb( $scrFile, $dstFile, $dstW, $dstH )
{ 
		$im = ImageCreateFromJPEG( $scrFile );
		$srcW = ImageSX( $im );
		$srcH = ImageSY( $im );
		if(function_exists('ImageCreatetruecolor'))		$ni = ImageCreatetruecolor( $dstW, $dstH );
		                                                                        else       $ni = ImageCreate( $dstW, $dstH );
        ImageCopyResized( $ni, $im, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH );
		ImageJPEG( $ni, $dstFile, 99 );	    
}




function showsub($idcat,$namecat)
{ global $connection,$dbname,$fix,$domainname;
if($idcat=="L") {
	$dept="";
	$query="select idp,category,title from ".$fix."catalog where category RLIKE 'L[0-9]' order by category asc";
} else {
	$dept="แผนก";
	$query="select idp,category,title from ".$fix."catalog where category='$idcat' order by idp asc";
}
	$result= mysqli_query($connection,$query);
	$row = mysqli_num_rows($result);
	echo "<center><select name=\"p_$idcat\" style=\"width: 550px;\"><option value=0>$dept $namecat ($row)</option>";

while($array = mysqli_fetch_array($result))
   {
	echo "<option value=\"$array[0]\"> - ".stripslashes($array[2])." </option>";
   }
mysqli_free_result($result);

echo "</select></center>";
}


function redirect($goto,$ect)
{
echo "<script language=\"javascript\">
$ect;
location='$goto';
</script>";
exit;
}

function head()
{
global $connection,$fix,$dbname,$editor,$folder,$domainname,$shoppingsys;
$color2 = "darkblue";
$sql = mysqli_query($connection,"select idp from ".$fix."catalog where category = 'LA' ");
$LAid = mysqli_result($sql,0);
echo "<HTML><HEAD><TITLE>$domainname Control Panel</TITLE>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UTF-8\">
<META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
<META HTTP-EQUIV=\"Cache-Control\" CONTENT=\"NO-CACHE\">
<style type=\"text/css\">
body,td,div,p     { font-family: Tahoma; font-size: 10pt}
b                            { font-family: Tahoma; font-size: 9pt; font-weight: bold }
.menu a:link       { font-family: Tahoma; font-size: 9pt; font-weight: bold; text-decoration: none; color: white} 
.menu a:visited { font-family: Tahoma; font-size: 9pt; font-weight: bold; text-decoration: none; color: white}
</style>
<script language=javascript>
function sqlbackup()
{
sql = confirm(\"Download ไฟล์ฐานข้อมูล .sql\");
if(sql==true) location = \"download-order.php?cp=sql\";
}
</script>
";

if($editor=="1")
echo "<script src=\"ckeditor/ckeditor.js\"></script>";
echo "
<link rel=\"stylesheet\" href=\"font-awesome/css/font-awesome.min.css\" />
<script language=\"javascript\" src=\"js/cp.js\"></script>
<script src=\"js/sweet/sweetalert-dev.js\"></script>
<link rel=\"stylesheet\" href=\"js/sweet/sweetalert.css\">
<LINK rel=\"stylesheet\" href=\"css/css.css\" type=\"Text/Css\">

</HEAD>
<BODY BGCOLOR=\"#eeeeee\">
					 <center>
					 <table width=\"1000\" cellspacing=0 cellpadding=0 bgcolor=\"#eeeeee\">
                     <tr><td width=150 valign=top>
                     <table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=\"#eeeeee\">
					 <tr class=\"menu\"><td bgcolor=\"orange\"><a href=\"backshopoffice.php\"><i class='fa fa-cogs'></i> Control Panel</a></td></tr>
		             <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=config\"><i class='fa fa-cog'></i> ตั้งค่ากำหนดพื้นฐาน</a></td></tr>
		             <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=editconfig\"><i class='fa fa-cog'></i> ตั้งค่ากำหนดขั้นสูง</a></td></tr>
	                 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=menu\"><i class='fa fa-list'></i> จัดการเมนูหน้าร้าน</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=payment\"><i class='fa fa-bank'></i> ตั้งค่าบัญชีธนาคาร</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=shipping\"><i class='fa fa-truck'></i> ตั้งค่าการจัดส่งสินค้า</a></td></tr>
	                 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=main\"><i class='fa fa-wrench'></i> จัดการแผนกสินค้า</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=subcategory\"><i class='fa fa-wrench'></i> จัดการหมวดสินค้า</a></td></tr>
	                 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=product\"><i class='fa fa-wrench'></i> จัดการสินค้า</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=vorder\"><i class='fa fa-list-alt'></i> รายการใบสั่งซื้อ</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=vpayconfirm\"><i class='fa fa-money'></i> รายการแจ้งโอนเงิน</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=vpayment\"><i class='fa fa-money'></i> แจ้งโอนเงินแทนลูกค้า</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=updateorder\"><i class='fa fa-calendar-check-o'></i> ปรับปรุงสถานะใบสั่งซื้อ</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=updateshipping\"><i class='fa fa-calendar-check-o'></i> ปรับปรุงสถานะการจัดส่ง</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=vstock\"><i class='fa fa-list'></i> รายงานสต๊อกสินค้า</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=vshipping\"><i class='fa fa-truck'></i> รายการจัดส่งสินค้า</a></td></tr>
					<!--tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=updatepoint\"><i class='fa fa-money'></i> ปรับปรุงยอดสั่งซื้อสะสม</a></td></tr-->
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=vreceipt\"><i class='fa fa-money'></i> ใบเสร็จรับเงิน</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=income\"><i class='fa fa-money'></i> ตรวจสอบรายรับ</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=viewmember\"><i class='fa fa-user'></i> ลูกค้า/สมาชิก</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=vcontactus\"><i class='fa fa-envelope-o'></i> ติดต่อสอบถาม</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=article\"><i class='fa fa-edit'></i> บทความ</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=viewwebboard\"><i class='fa fa-comments-o'></i> เว็บบอร์ด</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=viewreview\"><i class='fa fa-star'></i> รีวิวสินค้า</a></td></tr>
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=viewcomment\"><i class='fa fa-comment-o'></i> คอมเม้นต์ฯ</a></td></tr>		
					<tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=sqlbackup\"><i class='fa fa-history'></i> สำรองฐานข้อมูล</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=gallery\"><i class='fa fa-picture-o'></i> แกลเลอรี่รวมภาพ</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=gallery2\"><i class='fa fa-picture-o'></i> แกลเลอรี่ภาพสไลด์</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=slidetitle\"><i class='fa fa-edit'></i> เพิ่มคำบรรยายภาพสไลด์</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#4D94FF\"><a href=\"?action=editinfo&id=$LAid\"><i class='fa fa-link'></i> แลกลิ้งค์</a></td></tr>
					 <tr class=\"menu\"><td bgcolor=\"#98C230\"><a href=\"index.php\" target=\"_blank\"><i class='fa fa-home'></i> หน้าร้าน</a></td></tr>
	                 <tr class=\"menu\"><td bgcolor=\"#FF816E\"><a href=\"?action=logout\"><i class='fa fa-sign-out'></i> ออกจากระบบ</a></td></tr>
                     </table></td><td valign=top width=\"700\">";
}


function createcategoryfile()
	{ global $connection,$dbname,$fix;
$sql = mysqli_query($connection,"select * from ".$fix."categories order by id asc");
$row = mysqli_num_rows($sql);
$data = "<?php\r\n\$categories = Array(";
$i=1;
while($arr=mysqli_fetch_array($sql))
		{
$data .= "array(\"$arr[0]\",\"$arr[1]\",\"$arr[2]\")";
$data .= ($i!=$row) ? "," : "";
$i++;
		}
mysqli_free_result($sql);
$data .=");\r\n?>";
$startwrite = fopen("category.php", "w") or die("<script language=javascript>sweetAlert('Error','ไม่สามารถเขียนไฟล์ category.php ได้\\nโปรดทำการ Chmod 777 ไฟล์ category.php','error');</script>");
fputs($startwrite,$data);
fclose($startwrite);
	}
	
	
function createsubcategoryfile()
	{ global $connection,$dbname,$fix;
$sql = mysqli_query($connection,"select * from ".$fix."subcategories order by id asc");
$row = mysqli_num_rows($sql);
$data = "<?php\r\n\$subcategories = Array(";
$i=1;
while($arr=mysqli_fetch_array($sql))
		{
$data .= "array(\"$arr[0]\",\"$arr[1]\",\"$arr[2]\")";
$data .= ($i!=$row) ? "," : "";
$i++;
		}
mysqli_free_result($sql);
$data .=");\r\n?>";
$startwrite = fopen("subcategory.php", "w") or die("<script language=javascript>sweetAlert('Error','ไม่สามารถเขียนไฟล์ subcategory.php ได้\\nโปรดทำการ Chmod 777 ไฟล์ subcategory.php','error');</script>");
fputs($startwrite,$data);
fclose($startwrite);
	}
	
function createtoplink()
	{ global $connection,$dbname,$fix;
$sql = mysqli_query($connection,"select idp,title from ".$fix."catalog where category RLIKE 'L[0-9]' order by category asc");
$row = mysqli_num_rows($sql);
$data .= "<?php\r\n\$toplinking = Array(";
$i=1;
while($arr=mysqli_fetch_array($sql))
		{
$data .= "array(\"$arr[0]\",\"$arr[1]\")";
$data .= ($i!=$row) ? "," : "";
$i++;
		}
mysqli_free_result($sql);
$data .=");\r\n?>";
$startwrite = fopen("toplink.php", "w") or die("<script language=javascript>sweetAlert('Error','ไม่สามารถเขียนไฟล์ toplink.php ได้\\nโปรดทำการ Chmod 777 ไฟล์ toplink.php','error');</script>");
fputs($startwrite,$data);
fclose($startwrite);
	}
	
?>
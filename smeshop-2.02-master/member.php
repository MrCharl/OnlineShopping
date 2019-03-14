<?php
session_start();

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

$timenow  = strtotime( "now" );

$act = $_GET["act"];

$userid = $_SESSION['member']['user'];

if($act=='') {
	//session_start();
	if($_SESSION['member']['user']!=''){header("Location:member.php?act=home");}
	themehead("สมัครสมาชิก");
		registerform();
	themefoot(); 
	exit;
}

if($act=='demo') {
		themehead("สมัครสมาชิก");
echo "<br><br>
<center>
<div class='boxshadow boxlightblue' align='center'>1. เมื่อ Login แล้วจะพบกับหน้า ยินดีต้อนรับ</div><br>
<img src=images/member/member1.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>2. แบบฟอร์มแก้ไขข้อมูลสมาชิก</div><br>
<img src=images/member/member2.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>3. หน้าแสดงประวัติสั่งซื้อ</div><br>
<img src=images/member/member3.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>4. หน้าแสดงรายละเอียดใบสั่งซื้อสินค้า</div><br>
<img src=images/member/member4.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>5. หน้าแสดงใบเสร็จรับเงิน</div><br>
<img src=images/member/member5.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>6. หน้าแสดงรายละเอียดใบสั่งเสร็จรับเงิน</div><br>
<img src=images/member/member6.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>7. หน้าแสดงรายการติดต่อสอบถาม (ข้อความ)</div><br>
<img src=images/member/member7-1.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>8. หน้าแสดงรายละเอียดติดต่อสอบถาม (ข้อความ)</div><br>
<img src=images/member/member7-2a.jpg><br><br>
<img src=images/member/member7-2b.jpg><br><br>
<div class='boxshadow boxlightblue' align='center'>&nbsp;</div>
</center>";
	themefoot(); 
	exit;
}

if ($act=="logout") {
	session_start();
	//session_destroy();
	
	$_SESSION["member"] = Array();	
	
	themehead("Logout Completed");
		echo "<center><h1>ท่าน Logout ออกจากระบบสมาชิก เรียบร้อยแล้ว</h1></center>";
	memberloginform();
	themefoot(); 
	exit;
}

/////////////////////////////////////////////////////////////////// กู้คืนรหัสผ่านแบบง่าย

if ($act=="forgotpwd") { //แบบฟอร์มให้สมาชิกกรอกอีเมล์
	themehead("Forgot Password - Stpe 1");
		forgot_password_form();
	themefoot(); 
	exit;
}

if ($act=="retrievepwd") { //ตรวจสอบอีเมล์ว่ามีอยู่ในระบบหรือไม่ ถ้ามีส่งรหัสยืนยัน
	$email = $_POST['email'];
	themehead("Forgot Password - Step 2");
    retrieve_password($email) ;
	themefoot(); 
	exit;
}

/////////////////////////////////////////////////////////////////////


if ($act=="activate") {
	
	$email = $_GET['email'];
	
	if($email !="") {

	@mysqli_query($connection,"update ".$fix."member set active='1'  where email='$email' ");

	themehead("Activate Completed.");	
	echo "<script language=javascript>sweetAlert('Activate Account Complete','ยืนยันการสมัครสมาชิกเรียบร้อยแล้ว','success');</script>";
	echo "<center><h1>Activate Completed.</h1>กรุณา <a href=\"member.php?act=2\">Login</a> เพื่อปรับปรุงข้อมูลส่วนตัวของท่าน</center>";
	themefoot();
	mysqli_close($connection);
	exit;
	
	} else {
	themehead("Activate Account Completed.");	
		memberloginform();
	themefoot();
	exit;
	}
		
}


if ($act=="update") {
	
	// Sanitise username input

	$name = $_POST[ 'name' ];
	$name = stripslashes( $name );
	$name =mysqli_real_escape_string($connection, $name );
	
	$username = $_POST[ 'username' ];
	$username = stripslashes( $username );
	$username =mysqli_real_escape_string($connection, $username );
	
	$email = $_POST[ 'email' ];
	$email = stripslashes( $email );
	$email =mysqli_real_escape_string($connection, $email );
	
	$address = $_POST[ 'address' ];
	$address = stripslashes( $address );
	$address =mysqli_real_escape_string($connection, $address );
	
	$city = $_POST[ 'city' ];
	$city = stripslashes( $city );
	$city =mysqli_real_escape_string($connection, $city );
	
	$zipcode = $_POST[ 'zipcode' ];
	$zipcode = stripslashes( $zipcode );
	$zipcode =mysqli_real_escape_string($connection, $zipcode );
	
	$mobile = $_POST[ 'mobile' ];
	$mobile = stripslashes( $mobile );
	$mobile =mysqli_real_escape_string($connection, $mobile );
	
	$sex = $_POST[ 'sex' ];
	$sex = stripslashes( $sex );
	$sex =mysqli_real_escape_string($connection, $sex );
	
	$bdate = $_POST[ 'bdate' ];
	$bdate = stripslashes( $bdate );
	$bdate =mysqli_real_escape_string($connection, $bdate );
	
	$bmonth = $_POST[ 'bmonth' ];
	$bmonth = stripslashes( $bmonth );
	$bmonth =mysqli_real_escape_string($connection, $bmonth );	
	
	$byear = $_POST[ 'byear' ];
	$byear = stripslashes( $byear );
	$byear =mysqli_real_escape_string($connection, $byear );	
	
	$password = $_POST[ 'password' ];
	$password = stripslashes( $password );
	$password =mysqli_real_escape_string($connection, $password );
	
	$pwd1 = $_POST[ 'pwd1' ];
	$pwd1 = stripslashes( $pwd1 );
	$pwd1 =mysqli_real_escape_string($connection, $pwd1 );
	
	$pwd2 = $_POST[ 'pwd2' ];
	$pwd2 = stripslashes( $pwd2 );
	$pwd2 =mysqli_real_escape_string($connection, $pwd2 );
	
	If($pwd1 != "" && $pwd2 !="") {
		$password = $pwd1;
	} else {
		$password = $_POST['currentpwd'];
	}
		
	@mysqli_query($connection,"update ".$fix."member set email='$email', password='$password', name='$name', address='$address', city='$city', zipcode='$zipcode', mobile='$mobile', active='1', sex='$sex', bdate='$bdate', bmonth='$bmonth', byear='$byear'  where id='$userid' ");

	themehead("Update Complete.");	
	echo "<script language=javascript>sweetAlert('Update Complete','ปรับปรุงข้อมูลสมัครสมาชิก เรียบร้อยแล้ว','success');</script>";
	echo "<center><h1>Update Completed.</h1><a href=\"member.php?act=3\">กลับไปหน้าข้อมูลสมาชิก</a></center>";
	themefoot();
	mysqli_close($connection);
	exit;
		
}


if ($act=="login") {
	
	//session_start();
	if($_SESSION['member']['user']!=''){header("Location:member.php?act=home");}
	
	if(isset($_POST) && $username!='' && $password!=''){
		
	    global $fix;
		$strSQL = "SELECT * FROM ".$fix."member WHERE active='1' && username='".$_POST['username']."' and password='".$_POST['password']."'";
		$objQuery = mysqli_query($connection,$strSQL) or die(mysql_error());
		$objResult = mysqli_fetch_array($objQuery);
		
		$count = mysqli_num_rows($objQuery);		

		if ($count == 1){
			$_SESSION['member'] = Array();	
			$_SESSION["member"]['user'] = $objResult['id'];
			$_SESSION["member"]['name'] = $objResult['name'];
			$_SESSION["member"]['username'] = $objResult['username'];
			$_SESSION["member"]['email'] =$objResult['email'];
			$_SESSION["member"]['address'] = $objResult['address'];
			$_SESSION["member"]['city'] = $objResult['city'];
			$_SESSION["member"]['zipcode'] = $objResult['zipcode'];
			$_SESSION["member"]['mobile'] = $objResult['mobile'];
			$_SESSION["member"]['sex'] = $objResult['sex'];
			$_SESSION["member"]['bdate'] = $objResult['bdate'];
			$_SESSION["member"]['bmonth'] = $objResult['bmonth'];
			$_SESSION["member"]['byear'] = $objResult['byear'];
			$_SESSION["member"]['level'] = $objResult['level'];
			
			$purchase = $objResult['purchase'];
			$point = $objResult['point'];
			$custname = $objResult['name'];
			
			$imgavatar = $objResult['avatar'];
			if($imgavatar=="") { $imgavatar = "member.jpg"; }
	
			$_SESSION["member"]["avatar"] = $imgavatar;
			
			header("Location:member.php?act=home");
	
	echo "
		<center>
		<a class='boxshadow boxwidth150 boxlemon fa fa-money cp3' href=member.php?act=vieworder&userid=".$userid."><br>ยอดสั่งซื้อสะสม<br>".number_format($purchase,2)." บาท</a>
		<a class='boxshadow boxwidth150 boxsky fa fa-star cp3' href=member.php?act=vieworder&userid=".$userid."><br>คะแนนสะสม<br>".number_format($point,2)." คะแนน</a>
		<a class='boxshadow boxwidth150 boxred fa fa-user cp3' href=member.php?act=3><br>Level<br>".$level."</a>
		<a class='boxshadow boxwidth150 boxsilver fa fa-envelope-o cp3' href=member.php?act=vcontactus><br>ข้อความใหม่<br>(".$newmesg.")</a>
		</center>
	";		
			
			
			themefoot();
			exit;
		}else{
			themehead("Member Area.");	
			echo "<center><font color=red><h2>Username/Password is Incorrect.</h2></font></center>";
			memberloginform();
			themefoot();
			mysqli_close($connection);
			exit;
		}
}
}

if ($act=="home") {
	
	session_start();
	
	themehead("Member Area.");	
	
	//*** Select Orders ***//
	
	$userid = $_SESSION['member']['user'];
	$lv = $_SESSION['member']['level'];
	
	$query="select * from ".$fix."member where id = '$userid' ";
	$result = mysqli_query($connection,$query);
	$arr = mysqli_fetch_array($result);
	
	$purchase = $arr['purchase'];
	$point = $arr['point'];
	
	if($lv=='0'){ $level = "สมาชิกทั่วไป"; }
	if($lv=='1'){ $level = "ลูกค้า"; }
	if($lv=='2'){ $level = "ลูกค้า VIP"; }	
	
	$query="select * from ".$fix."contactus where custid=$userid and new2= '1' ";
	$result= mysqli_query($connection,$query);
	$numrow = mysqli_num_rows($result);
	$newmesg = ($numrow >0) ? $numrow : "0";
			
	echo "<div class=\"boxshadow member\" align=right><h2>ยินดีต้อนรับ คุณ".$arr[1]."</h2>
	<a class=\"bb\" href=\"member.php?act=home\">หน้าแรก</a> |
	<a class=\"bb\" href=\"member.php?act=3\">แก้ไขข้อมูล</a> |
	<a class=\"bb\" href=member.php?act=vieworder&userid=".$userid.">ประวัติสั่งซื้อ</a> |
	<a class=\"bb\" href=member.php?act=viewreceipt&userid=".$userid.">ใบเสร็จรับเงิน</a> |
	<a class=\"bb\" href=member.php?act=vcontactus>ข้อความ (".$newmesg.")</a>
	<br><br></div><br><br>";
	

	if($lv == "2") {
			$discount = $vipdiscount;
			$coupon = $vipcoupon;
	} else {
			$discount = $mdiscount;
			$coupon = $mcoupon;
	}
	

	
	if($greetingmsg) {
		
		if(preg_match("/\[shopname\]/",$greetingmsg))  {$greetingmsg = stripslashes(str_replace("[shopname]",$shopname,$greetingmsg)); }
		if(preg_match("/\[discount\]/",$greetingmsg))  {$greetingmsg = stripslashes(str_replace("[discount]",$discount,$greetingmsg)); }
		if(preg_match("/\[coupon\]/",$greetingmsg))  {$greetingmsg = stripslashes(str_replace("[coupon]",$coupon,$greetingmsg)); }
		
		echo "<div class='boxshadow boxlightblue'><i class='fa fa-comment'> ข่าวสารจากร้านค้า: </i> $greetingmsg</div><br>";
	}
	
	echo "
		<center>
		<a class='boxshadow boxwidth150 boxlemon fa fa-money cp3' href=member.php?act=vieworder&userid=".$userid."><br>ยอดสั่งซื้อสะสม<br>".number_format($purchase,2)." บาท</a>
		<a class='boxshadow boxwidth150 boxsky fa fa-star cp3' href=member.php?act=vieworder&userid=".$userid."><br>คะแนนสะสม<br>".number_format($point,2)." คะแนน</a>
		<a class='boxshadow boxwidth150 boxred fa fa-user cp3' href=member.php?act=3><br>Level<br>".$level."</a>
		<a class='boxshadow boxwidth150 boxsilver fa fa-envelope-o cp3' href=member.php?act=vcontactus><br>ข้อความใหม่<br>(".$newmesg.")</a>
		</center>
	";			
	
	themefoot();
	exit;

}



if($act==1) { //บันทึกข้อมูลสมาชิกสมาชิกใหม่

	// Sanitise username input

	$name = $_POST[ 'name' ];
	$name = stripslashes( $name );
	$name =mysqli_real_escape_string($connection, $name );
	
	$username = $_POST[ 'username' ];
	$username = stripslashes( $username );
	$username =mysqli_real_escape_string($connection, $username );
	
	$email = $_POST[ 'email' ];
	$email = stripslashes( $email );
	$email =mysqli_real_escape_string($connection, $email );
	
	$address = $_POST[ 'address' ];
	$address = stripslashes( $address );
	$address =mysqli_real_escape_string($connection, $address );
	
	$city = $_POST[ 'city' ];
	$city = stripslashes( $city );
	$city =mysqli_real_escape_string($connection, $city );
	
	$zipcode = $_POST[ 'zipcode' ];
	$zipcode = stripslashes( $zipcode );
	$zipcode =mysqli_real_escape_string($connection, $zipcode );
	
	$mobile = $_POST[ 'mobile' ];
	$mobile = stripslashes( $mobile );
	$mobile =mysqli_real_escape_string($connection, $mobile );
	
	$sex = $_POST[ 'sex' ];
	$sex = stripslashes( $sex );
	$sex =mysqli_real_escape_string($connection, $sex );
	
	$bdate = $_POST[ 'bdate' ];
	$bdate = stripslashes( $bdate );
	$bdate =mysqli_real_escape_string($connection, $bdate );
	
	$bmonth = $_POST[ 'bmonth' ];
	$bmonth = stripslashes( $bmonth );
	$bmonth =mysqli_real_escape_string($connection, $bmonth );	
	
	$byear = $_POST[ 'byear' ];
	$byear = stripslashes( $byear );
	$byear =mysqli_real_escape_string($connection, $byear );	
	
	$password = $_POST[ 'password' ];
	$password = stripslashes( $password );
	$password =mysqli_real_escape_string($connection, $password );
	
	$pwd1 = $_POST[ 'pwd1' ];
	$pwd1 = stripslashes( $pwd1 );
	$pwd1 =mysqli_real_escape_string($connection, $pwd1 );
	
	$pwd2 = $_POST[ 'pwd2' ];
	$pwd2 = stripslashes( $pwd2 );
	$pwd2 =mysqli_real_escape_string($connection, $pwd2 );

    if(isset($_POST['username']) && isset($_POST['password'])  && isset($_POST['email']) )
	{
		
		$custresult = mysqli_query($connection,"select email from ".$fix."member where email = '".$email."' ");
		if(mysqli_num_rows($custresult) >0 ) {
			//die("User Exists");
			themehead("Member Area.");	
			echo "<center><font color=red><h3>Error: Email Already Exists - มีผู้ใช้ อีเมล์ นี้แล้ว</h3></font></center>";
			registerform();
			themefoot();
			exit;
		}

		@mysqli_query($connection,"insert into ".$fix."member (id,name,username,email, password,address,city,zipcode,mobile,active,sex,bdate,bmonth,byear,New,level,purchase,point,avatar) values ('', '$name', '$username', '$email', '$password', '','','','','0','','','','','1','0','0','0','')");

		themehead("Register Complete.");	
		echo "<script language=javascript>sweetAlert('Register Complete','ท่านสมัครสมาชิก เรียบร้อยแล้ว','success');</script>";
		echo "<center><h1>Register Completed.</h1>ท่านจะได้รับอีเมล์ตอบรับจากทางเรา กรุณา คลิกลิงค์ เพื่อยืนยันการสมัครสมชิก ก่อนจึงจะสามารถ Login ได้</center>";
		
		$subject =  "Please confirm your register - กรุณายืนยันการสมัครสมาชิก";
		
		$message = "
		Dear Khun $name\n
		เรียน คุณ $name\n\n
		
		Please confirm your register by click this link:\n
		กรุณายืนยันการสมัครสมาชิกโดยคลิกที่ลิ้งนี้: \n\n
		
		---------------------------------------------\n" .
		$dir . "member.php?act=activate&email=$email\n" . 
		"---------------------------------------------\n\n

		Sincerely,\n
		ขอแสดงความนับถือ\n\n
		
		$domainname";

		global $emailcontact;
		send_email_to_memember($subject,$emailcontact,$email,$message,$domainname);		
		
		themefoot();
		mysqli_close($connection);
		exit;
		
	}

}

if($act==2) { //แสดงแบบฟอร์มล๊อกอิน
	if($_SESSION['member']['user']!=''){header("Location:member.php?act=3");}
	themehead("Member Area.");	
	memberloginform();
	themefoot();
	exit;
}

if($act==3) { //พื้นที่เฉพาะลูกค้า

if($_SESSION['member']['user']==''){
	header("Location:member.php?act=2");
}else{
	include("config.php");
	
	$userid = $_SESSION['member']['user'];
	$strSQL = " SELECT * FROM ".$fix."member WHERE id = '".$userid." '  ";
	$objQuery = mysqli_query($connection,$strSQL);
	$objResult = mysqli_fetch_array($objQuery);
	
	$_SESSION["member"]['user'] = $objResult['id'];;
	$_SESSION["member"]['name'] = $objResult['name'];
	$_SESSION["member"]['username'] = $objResult['username'];
	$_SESSION["member"]['email'] =$objResult['email'];
	$_SESSION["member"]['address'] = $objResult['address'];
	$_SESSION["member"]['city'] = $objResult['city'];
	$_SESSION["member"]['zipcode'] = $objResult['zipcode'];
	$_SESSION["member"]['mobile'] = $objResult['mobile'];
	$_SESSION["member"]['sex'] = $objResult['sex'];
	$_SESSION["member"]['bdate'] = $objResult['bdate'];
	$_SESSION["member"]['bmonth'] = $objResult['bmonth'];
	$_SESSION["member"]['byear'] = $objResult['byear'];
	
	$currentpwd = $objResult['password'];
	
	$imgavatar = $objResult['avatar'];
	if($imgavatar=="") { $imgavatar = "member.jpg"; }
	
	$_SESSION["member"]["avatar"] = $imgavatar;
	
	$pwd = $objResult['password'];		
	
	themehead("Member Area.");	

	/*
	$timenow=time();
	$currentdate = date("Y-m-d H:i:s A",$timenow);
	*/
	
	$lv = $objResult['level'];
	
	if($lv=='0'){ $level = "สมาชิกทั่วไป"; }
	if($lv=='1'){ $level = "ลูกค้า"; }
	if($lv=='2'){ $level = "ลูกค้า VIP"; }	
	
	$query="select * from ".$fix."contactus where custid=$userid and new2= '1' ";
	$result= mysqli_query($connection,$query);
	$numrow = mysqli_num_rows($result);
	$newmesg = ($numrow >0) ? $numrow : "0";
			
	echo "<div class=\"boxshadow member\" align=right><h2>แก้ไขข้อมูลสมาชิก</h2>
	<a class=\"bb\" href=\"member.php?act=home\">หน้าแรก</a> |
	<a class=\"bb\" href=\"member.php?act=3\">แก้ไขข้อมูล</a> |
	<a class=\"bb\" href=member.php?act=vieworder&userid=".$userid.">ประวัติสั่งซื้อ</a> |
	<a class=\"bb\" href=member.php?act=viewreceipt&userid=".$userid.">ใบเสร็จรับเงิน</a> |
	<a class=\"bb\" href=member.php?act=vcontactus>ข้อความ (".$newmesg.")</a>
	<br><br></div><br><br>	
	
	 <center>
	<table class=\"mytables\" width=\"100%\">
		<tr>
		<td>
			<form action=\"member.php?act=update\" method=post name=\"memberform\" onsubmit=\"return checkmemberform()\">
			<table class=\"mytables\" width=\"100%\">
			<tr><td height=10></td></tr>
			<tr><td>UserID:</td><td><input class=\"tblogin\" type=text name=\"id\" value='".$userid."' size=5 disabled>&nbsp;&nbsp;Level:&nbsp;&nbsp;<i class='boxshadow boxsky'> $level </i></td></tr>
			<tr><td height=10></td></tr>
			<tr><td>ชื่อ-นามสกุล :</td><td><input class=\"tblogin\" type=text name=\"name\" value='".$_SESSION['member']['name']."' size=30> *</td></tr>
			<tr><td height=10></td></tr>
			<tr><td>เพศ :</td>
			<td>
				<table border=0 width=60%>
				<tr>";
			
				if($_SESSION['member']['sex']=='M'){ $s1 = " checked"; }
				if($_SESSION['member']['sex']=='W'){ $s2 = " checked"; }
				if($_SESSION['member']['sex']=='N'){ $s3 = " checked"; }
			
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
				if($o==$_SESSION['member']['bdate']) { $chk = 'selected'; }
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
				if($o==$_SESSION['member']['bmonth']) { $chk = 'selected'; }
				echo "<option value=\"$i\" $chk>$o</option><br>";
				$chk="";
				$i++;
			}
			echo "
			</select>&nbsp;&nbsp;
			<input class=\"tblogin\" type=text name=byear value='".$_SESSION['member']['byear']."' size=5>
			</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr><td>Username:</td><td><input class=\"tblogin\" type=text name=\"username\" value='".$_SESSION['member']['username']."' size=30 disabled></td></tr>
			<tr><td height=10></td></tr>
			<tr><td>E-mail:</td><td><input class=\"tblogin\" type=email name=\"email\" value=".$_SESSION['member']['email']." size=30></td></tr>
			<tr><td height=10></td></tr>
			<tr>
				<td>รหัสผ่าน : </td>
				<td><input class=\"tblogin\" type=password name=\"pwd1\" size=15>&nbsp;&nbsp;<input class=\"tblogin\" type=password name=\"pwd2\" size=15></td>
			</tr>
			<tr><td height=10></td></tr>
			<tr><td>ที่อยู่ :</td><td><textarea name=\"address\"rows=5 cols=40>".$_SESSION['member']['address']."</textarea></td></tr>
			<tr><td height=10></td></tr>
			<tr><td>จังหวัด :</td><td><input class=\"tblogin\" type=text name=\"city\" value='".$_SESSION['member']['city']."' size=15> รหัสไปรษณีย์ : <input class=\"tblogin\" type=text name=\"zipcode\" value='".$_SESSION['member']['zipcode']."' size=5></td></tr>
			<tr><td height=10></td></tr>
			<tr><td>เบอร์โทรศัพท์ :</td><td><input class=\"tblogin\" type=text name=\"mobile\" value='".$_SESSION['member']['mobile']."' size=15></td></tr>
			<tr><td height=10></td></tr>
			<tr>
			<td colspan=2 align=center>
				<input type=\"hidden\" name=\"currentpwd\" value='".$currentpwd."'>
				<input class=\"myButton\" type=submit name=\"update\" value=\" Update Profile \"><br><br>
			</td>
			</tr>
			</table>		
			</form>
		</td>
		<td valign=top>
			<table class=\"mytables\" width=\"100%\"><tr><td>
			<center><img class='avatar' src=images/users/".$imgavatar." width=150 height=150><br>
			<form id=\"avatar_form\" enctype=\"multipart/form-data\" method=\"post\" action=\"?act=updateavatar\">
			<h4>Change your avatar</h4>
			<input type=\"file\" name=\"avatar\" required>
			<p><input class=\"myButton\"  type=\"submit\" value=\"Upload\"></p>
			ไฟล์ .gif/.jpg/.png ขนาด 150x150 px
			</td></tr></table>
			</form>
			</center>
	</td>
	</tr>
	</table>
	</center>
	";
	themefoot();
	mysqli_close($connection);
	exit;
	}
}


if($act=="vieworder") {
	
	$userid = $_SESSION['member']['user'];
	
	themehead("ดูประวัติสั่งซื้อ - Member Area.");	
	
	echo "<script language=\"javascript\" src=\"js/member.js\"></script>";
	echo "<center>";
	
	$query="select * from ".$fix."contactus where custid=$userid and new2= '1' ";
	$result= mysqli_query($connection,$query);
	$numrow = mysqli_num_rows($result);
	$newmesg = ($numrow >0) ? $numrow : "0";
			
	echo "<div class=\"boxshadow member\" align=right><h2>ประวัติสั่งซื้อ</h2>
	<a class=\"bb\" href=\"member.php?act=home\">หน้าแรก</a> |
	<a class=\"bb\" href=\"member.php?act=3\">แก้ไขข้อมูล</a> |
	<a class=\"bb\" href=member.php?act=vieworder&userid=".$userid.">ประวัติสั่งซื้อ</a> |
	<a class=\"bb\" href=member.php?act=viewreceipt&userid=".$userid.">ใบเสร็จรับเงิน</a> |
	<a class=\"bb\" href=member.php?act=vcontactus>ข้อความ (".$newmesg.")</a>
	<br><br></div><br><br>";

	//*** Select Orders ***//
	
	$query="select sum(totalprice) as purchase from ".$fix."orders where custid = '$userid' and orderstatus != '0'";
	$result = mysqli_query($connection,$query);
	$arr = mysqli_fetch_array($result);
	$purchase = $arr[0];
	$point = round(($purchase*$points)/100);
	
	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders where custid ='$userid' order by orderdate desc");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ประวัติสั่งซื้อ</b></font></td></tr><tr><td>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><br>ยอดสั่งซื้อสะสม <i class='boxshadow boxsky'>".number_format($purchase,2)."</i> บาท คะแนนสะสม <i class='boxshadow boxred'>$point</i> คะแนน<br><br></td></tr>";
		echo "<tr  bgcolor=#eeeeee><td align=center>เลขที่ใบสั่งซื้อ</td><td align=center>วันที่สั่งซื้อ</td><td align=center>ยอดสั่งซื้อ</td><td align=center>ชื่อ-นามสกุล ลูกค้า</td><td align=center>สถานะใบสั่งซื้อ</td><td align=center>เลือกทำรายการ</td></tr>";
		$i=0;
		while($arr=mysqli_fetch_array($sqlResult))
		{
			if($arr['orderstatus']==0) {$orderstatus = "<font color=black>ยังไม่ได้ชำระเงิน</font>";}
			if($arr['orderstatus']==1) {$orderstatus = "<font color=orange>รอจัดส่งสินค้า</font>";}
			if($arr['orderstatus']==2) {$orderstatus = "<font color=green>จัดส่งสินค้าแล้ว</font>";}
			$ordno = $arr['orderno'];
			
			echo "<tr><td>".$arr['orderno']."</td><td>".substr($arr['orderdate'],0,10)."</td><td align=right>".number_format($arr['totalprice'],2)."</td><td>".$arr['cust_name']."</td><td>".$orderstatus."</td>
			<td align=center>
			<table border=0>
			<tr>
				<td><a class='boxshadow boxlemon' href=member-view-order.php?act=view&orderno=".$arr['orderno']."&email=".$arr['ordermail']." target=_blank><i class='fa fa-eye'></i> ดูใบสั่งซื้อ</a></td>
				<td><a class='boxshadow boxorose' href=member-view-order.php?act=view&media=print&orderno=".$arr['orderno']."&email=".$arr['ordermail']." target=_blank><i class='fa fa-print'></i> พิมพ์</a></td>
				<td><a class='boxshadow boxlightblue' href=member-download-order.php?orderno=".$arr['orderno']."&email=".$arr['ordermail']." target=\"_blank\"><i class='fa fa-download'> ดาวน์โหลด .pdf</a></td>
			</tr>
			</table>			
			</td></tr>";
			
			$i++;
		}
				
		echo "</form></table></td></tr></table>";
		
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดูรายการใบสั่งซื้อ</b></font></td></tr><tr><td>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}
	
	themefoot();
	exit;
}

if($act=="viewreceipt") {
	

	$userid = $_SESSION['member']['user'];
	
	themehead("ดูใบเสร็จรับเงิน - Member Area.");	
	
	echo "<script language=\"javascript\" src=\"js/member.js\"></script>";
	echo "<center>";
	
	$query="select * from ".$fix."contactus where custid=$userid and new2= '1' ";
	$result= mysqli_query($connection,$query);
	$numrow = mysqli_num_rows($result);
	$newmesg = ($numrow >0) ? $numrow : "0";
			
	echo "<div class=\"boxshadow member\" align=right><h2>ใบเสร็จรับเงิน</h2>
	<a class=\"bb\" href=\"member.php?act=home\">หน้าแรก</a> |
	<a class=\"bb\" href=\"member.php?act=3\">แก้ไขข้อมูล</a> |
	<a class=\"bb\" href=member.php?act=vieworder&userid=".$userid.">ประวัติสั่งซื้อ</a> |
	<a class=\"bb\" href=member.php?act=viewreceipt&userid=".$userid.">ใบเสร็จรับเงิน</a> |
	<a class=\"bb\" href=member.php?act=vcontactus>ข้อความ (".$newmesg.")</a>
	<br><br></div><br><br>";

	//*** Select Orders ***//
	
	$sqlResult = mysqli_query($connection,"select * from ".$fix."orders where orderstatus='2' and custid ='$userid' ");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดู-พิมพ์ ใบเสร็จรับเงิน</b></font></td></tr><tr><td>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr  bgcolor=#eeeeee><td align=center>เลขที่ใบสั่งซื้อ</td><td align=center>วันที่ออกใบเสร็จ</td><td align=center>จำนวนเงิน</td><td align=center>ชื่อ-นามสกุล ลูกค้า</td><td align=center>เลือกทำรายการ</td></tr>";
		$i=0;
		while($arr=mysqli_fetch_array($sqlResult))
		{

			$ordno = $arr['orderno'];
			
			echo "<tr><td>".$arr['orderno']."</td><td>".thaidate(substr($arr['paymentdate'],0,10))."</td><td align=right>".number_format($arr['totalprice'],2)."</td><td>".$arr['cust_name']."</td>
			<td width=300 align=center>
			<table border=0>
			<tr>
				<td width=80 align=center><a class='boxshadow boxlemon' href=member-view-receipt.php?act=view&orderno=".$arr['orderno']."&email=".$arr['ordermail']." target=_blank><i class='fa fa-eye'></i> ดูใบเสร็จ</a></td>
				<td width=100 align=center><a class='boxshadow boxlightblue' href=member-view-receipt.php?act=view&media=print&orderno=".$arr['orderno']."&email=".$arr['ordermail']." target=_blank><i class='fa fa-print'></i> พิมพ์ใบเสร็จ</a></td>
				<td width=100 align=center><a class='boxshadow boxorose' href=member-download-receipt.php?orderno=".$arr['orderno']."&email=".$arr['ordermail']." target=\"_blank\"><i class='fa fa-download'> ดาวน์โหลด .pdf</a></td>
			</tr>
			</table>			
			</td></tr>";
			
			$i++;
		}
				
		echo "</form></table></td></tr></table>";
		
	} else {
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-list-alt'></i> ดู-พิมพ์ ใบเสร็จรับเงิน</b></font></td></tr><tr><td>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}
	
	themefoot();
	exit;
}

function registerform()
{ 

echo "

<center>
<h1><font color=#5DBAE1><i class='fa fa-user'></i> ฝ่ายบริการสมาชิก</h1></font>
รับทราบข่าวสาร โปรโมชั่นใหม่  คูปองส่วนลด และ สิทธิพิเศษต่าง ๆ ไม่ต้องเสียเวลากรอกข้อมูลใบสั่งซื้อทุกครั้ง<br><br>
<table class=\"mytables\" width=\"100%\"><tr><td>
<div class=\"register-form\">
<h1>Register</h1>
	<form action=\"member.php?act=1\" name=\"registerform\" method=\"post\" onsubmit=\"return checkregisterform()\">
	<p><label>Your Name : </label>
	<input id=\"name\" type=\"text\" name=\"name\" placeholder=\"name\" /></p>	

	<p><label>User Name : </label>
	<input id=\"username\" type=\"text\" name=\"username\" placeholder=\"username\" /></p>

	<!--
	<p><label>User Name : </label>
	<span><input name=\"username\" type=\"text\" id=\"username\" placeholder=\"username\" /></span>
	<span id=\"usernameLoading\"><img src=\"images/checking.gif\" alt=\"Ajax Indicator\" /></span>
	<span id=\"usernameResult\"></span> <input type=\"hidden\" name=\"chkavailibility\" value=\"0\">
	-->
	
	<p><label>E-Mail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </label>
	<span><input name=\"email\" type=\"text\" id=\"email\" placeholder=\"email\" /></span>
	<span id=\"emailLoading\"><img src=\"images/checking.gif\" alt=\"Ajax Indicator\" /></span>
	<span id=\"emailResult\"></span> <input type=\"hidden\" name=\"chkavailibility\" value=\"0\">
	
	<p><label>Password 1&nbsp;: </label>
	<input id=\"password\" type=\"password\" name=\"password\" placeholder=\"password\" /></p>
	<p><label>Password 2&nbsp;: </label>
	<input id=\"pwd2\" type=\"password\" name=\"pwd2\" placeholder=\"password\" /></p>
    <input class=\"myButton\" type=submit value=\"Register\" />
    </form>
</div>
</td>
<td valign=top>
<div class=\"register-form\">
<h1>Login</h1>
	<form name=\"memberloginform\" action=\"member.php?act=login\" method=post  onsubmit=\"return checkloginform()\">
    <p><label>User Name : </label>
    <input type=\"text\" name=\"username\" required></p>
     <p><label>Password&nbsp;&nbsp; : </label>
     <input type=\"password\" name=\"password\" required></p>
    <input class=\"myButton\" type=submit value=\" Login \">
    </form><a href=\"member.php?act=forgotpwd\">ลืมรหัสผ่าน?</a>
</div>
<center><br><br><a href=\"?act=demo\">คลิกตรงนี้</a> เพื่อดูหน้าตัวอย่างพื้นที่เฉพาะสมาชิก<br>หลังจากทำการ Login เรียบร้อยแล้ว</center><br>
</td></tr></table>";

}

if($act=="updateavatar") {
	
	if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
		$fileName = $_FILES["avatar"]["name"];
		$fileTmpLoc = $_FILES["avatar"]["tmp_name"];
		$fileType = $_FILES["avatar"]["type"];
		$fileSize = $_FILES["avatar"]["size"];
		$fileErrorMsg = $_FILES["avatar"]["error"];
		$kaboom = explode(".", $fileName);
		$fileExt = end($kaboom);
		list($width, $height) = getimagesize($fileTmpLoc);
		if($width < 10 || $height < 10){
			echo "ERROR: That image has no dimensions";
			exit();	
		}
		$db_file_name = "avt".rand(100000000000,999999999999).".".$fileExt;
		if($fileSize > 1048576) {
			echo "ERROR: Your image file was larger than 1mb";
			exit();	
		} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
			echo "ERROR: Your image file was not jpg, gif or png type";
			exit();
		} else if ($fileErrorMsg == 1) {
			echo "ERROR: An unknown error occurred";
			exit();
		}
	
		$sql = "SELECT avatar FROM ".$fix."member WHERE id='$userid' LIMIT 1";
		$query = mysqli_query($connection, $sql);
		$row = mysqli_fetch_row($query);
		$avatar = $row[0];
	
		if($avatar != ""){
			$picurl = "images/users/$avatar"; 
			if (file_exists($picurl)) { unlink($picurl); }
		}
		$moveResult = move_uploaded_file($fileTmpLoc, "images/users/$db_file_name");
		if ($moveResult != true) {
			echo "ERROR: File upload failed";
			exit();
		}
		$target_file = "images/users/$db_file_name";
		$resized_file = "images/users/$db_file_name";
		$wmax = 150;
		$hmax = 150;
		img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);

		$sql = "UPDATE ".$fix."member SET avatar='$db_file_name' WHERE id='$userid' LIMIT 1";
		$query = mysqli_query($connection, $sql);
		mysqli_close($connection);
		header("Location:member.php?act=3");
		exit();

	}
	
}

function img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
      $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
      $img = imagecreatefrompng($target);
    } else { 
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 84);
}


function memberloginform()
{ 

echo "
<div class=\"register-form\">
<h1>Login</h1>
	<form action=\"member.php?act=login\" method=post name=\"memberloginform\" onsubmit=\"return checkloginform()\">
    <p><label>User Name : </label>
    <input type=\"text\" name=\"username\" required></p>
     <p><label>Password&nbsp;&nbsp; : </label>
     <input type=\"password\" name=\"password\" required></p>
    <input class=\"myButton\" name=\"submit\" type=submit value=\" Login \">
    </form><a href=\"member.php?act=forgotpwd\">ลืมรหัสผ่าน?</a>
</div>";

}

function forgot_password_form()
{
echo "
<div class=\"register-form\">
<h1>Forgot Password</h1>
<form action=\"member.php?act=retrievepwd\" method=\"post\">
     <p><label>Email&nbsp;&nbsp; : </label>
     <input id=\"email\" type=\"email\" name=\"email\" placeholder=\"email\" /></p>
    <input class=\"btn register\" type=\"submit\" name=\"submit\" value=\" Submit \" />
    </form>
</div>";
}

function retrieve_password($email) 
{ global $connection,$fix,$domainname,$emailcontact;

	$query="select * from ".$fix."member where email='".$email."'";
	$result   = mysqli_query($connection,$query);
	$count=mysqli_num_rows($result);
	// If the count is equal to one, we will send message other wise display an error message.
	if($count==1)
	{
		$rows=mysqli_fetch_array($result);
		$user  =  $rows['username'];//FETCHING PASS
		$pass  =  $rows['password'];//FETCHING PASS
		//echo "your pass is ::".($pass)."";
		$to = $rows['email'];
		//echo "your email is ::".$email;
		//Details for sending E-mail

		$subject =  "Password recovery";
		
		$message = "
		Password recovery\n;
		---------------------------------------------\n;
 		Url : $url\n
		Your Email : $to\n
		Your Username : $user\n
		Your password  : $pass\n\n
		Sincerely,\n
		$domainname";		

		send_email_to_memember($subject,$emailcontact,$to,$message);
	}
	else {
	   echo "<br><br><div class='boxshadow boxred' align=center>Not found your email in our database</div><br>";
	}	

}

if($act=="vcontactus") {
	
	themehead("ข้อความ ถาม-ตอบ Member Area.");	
	
	echo "<center>";
	
	$query="select * from ".$fix."contactus where custid=$userid and new2= '1' ";
	$result= mysqli_query($connection,$query);
	$numrow = mysqli_num_rows($result);
	$newmesg = ($numrow >0) ? $numrow : "0";
			
	echo "<div class=\"boxshadow member\" align=right><h2>ติดต่อ-สอบถาม</h2>
	<a class=\"bb\" href=\"member.php?act=home\">หน้าแรก</a> |
	<a class=\"bb\" href=\"member.php?act=3\">แก้ไขข้อมูล</a> |
	<a class=\"bb\" href=member.php?act=vieworder&userid=".$userid.">ประวัติสั่งซื้อ</a> |
	<a class=\"bb\" href=member.php?act=viewreceipt&userid=".$userid.">ใบเสร็จรับเงิน</a> |
	<a class=\"bb\" href=member.php?act=vcontactus>ข้อความ (".$newmesg.")</a>
	<br><br></div><br><br>";

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

	$sqlResult = mysqli_query($connection,"select * from ".$fix."contactus where custid=$userid order by contactid desc limit $Page_Start, $Per_Page");
	$row=mysqli_num_rows($sqlResult);

	if($row > 0)
	{
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comment'></i> ติดต่อสอบถาม</b></font></td></tr><tr><td>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr  bgcolor=#eeeeee><td align=left>Contact ID</td><td align=left>วันที่</td><td align=left>เรื่อง</td><td align=left>ชื่อ-นามสกุล</td><td align=center>ทำรายการ</td></tr>";
		$i=0;
		while($arr=mysqli_fetch_array($sqlResult))
		{
			$contactid = $arr['contactid'];
			
			$new="";
			if($arr['new2'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
						
			echo "<tr><td>".$arr['contactid']." $new</td><td>".thaidate(substr($arr['contactdate'],0,10))."</td><td align=left>".$arr['subject']."</td><td>".$arr['custname']."</td>
			<td width=150 align=center>
			<table border=0>
			<tr>
				<td width=100 align=center><a class='boxshadow boxlemon' href=?act=view-contact-us&contactid=".$contactid."><i class='fa fa-eye'></i> ดูรายละเอียด</a></td>
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
			echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=vreceiptr&status=$status&Page=$Prev_Page'><< ก่อนหน้า</a> ";
		}

		for($i=1; $i<=$Num_Pages; $i++){
			if($i != $Page)
			{
				echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=vreceiptr&status=$status&Page=$i'>$i</a>";
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
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comment'></i> ติดต่อสอบถาม</b></font></td></tr><tr><td>";
		echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" background=\"#ffffff\" border=\"1\" bordercolor=#eeeeee>";
		echo "<tr><td colspan=6 align=center><div class='boxshadow boxred' align=center><h1>ยังไม่มีรายการ!</div></td></tr>";
		echo "</table></td></tr></table>";
	}
	
	themefoot();
	exit;
	
}

if($act=="view-contact-us") {
	
	themehead("ข้อความ ถาม-ตอบ Member Area.");	
	
	echo "<center>";
	
	$query="select * from ".$fix."contactus where custid=$userid and new2= '1' ";
	$result= mysqli_query($connection,$query);
	$numrow = mysqli_num_rows($result);
	$newmesg = ($numrow >0) ? $numrow : "0";
			
	echo "<div class=\"boxshadow member\" align=right><h2>ติดต่อ-สอบถาม</h2>
	<a class=\"bb\" href=\"member.php?act=home\">หน้าแรก</a> |
	<a class=\"bb\" href=\"member.php?act=3\">แก้ไขข้อมูล</a> |
	<a class=\"bb\" href=member.php?act=vieworder&userid=".$userid.">ประวัติสั่งซื้อ</a> |
	<a class=\"bb\" href=member.php?act=viewreceipt&userid=".$userid.">ใบเสร็จรับเงิน</a> |
	<a class=\"bb\" href=member.php?act=vcontactus>ข้อความ (".$newmesg.")</a>
	<br><br></div><br><br>";
	
	$contactid = $_GET['contactid'];
	$sqlResult = mysqli_query($connection,"select * from ".$fix."contactus where contactid='".$contactid."' ");
	$row=mysqli_num_rows($sqlResult);
	if($row > 0)
	{
		$arr=mysqli_fetch_array($sqlResult);
		$contactid = $arr[0];
		$custid = $arr[1];
		echo "<table width=\"100%\" cellspacing=2 cellpadding=3 bgcolor=#ffffff><tr bgcolor=\"#98C230\"><td><font color=#ffffff><b><i class='fa fa-comment'></i> ติดต่อสอบถาม</b></font></td></tr><tr><td>
		<center><br><a class='boxshadow boxlemon' href=?act=vcontactus><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</a><br><br></center>";
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
				echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#eeeeee\">";
				echo "
				<tr><td align=left width=20%>วันที่ตอบ</td><td>".$arr2['replydate']."</td></tr>
				<tr><td align=left>ชื่อ-นามสกุล</td><td>".$arr2['custname']."</td></tr>
				<tr><td align=left>รายละเอียด</td><td>".$arr2['details']."</td></tr>
				</table><br>";
			}
		}
		echo "</td></tr></table>";
		
		include "member-reply-mail.php";
		@mysqli_query($connection,"update ".$fix."contactus set new2='0' where contactid='".$contactid."'");
	} else {
		echo "Error: ไม่พบข้อมูลที่ต้องการ";
	}
	
	themefoot();
	exit;
}

function send_email_to_memember($subject,$fromemail,$toemail,$mesg,$domainname) 
{ global $smtp_hostname,$smtp_portno,$smtp_username,$smtp_password;
		require("phpmailer/class.phpmailer.php"); // path to the PHPMailer class
	
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
		
		$mail->FromName = $domainname;
		$mail->From = $fromemail;
		$mail->AddAddress($toemail);
		$mail->AddReplyTo($fromemail) ;
			
		//ต้องทำการเข้ารหัสก่อน มิฉะนั้น Subject จะแสดงภาษาไทยไม่ได้ (เฉพาะ tis-620)
		//$subject = "=?tis-620?B?".base64_encode($sem2)."?=";
			
		$mail->Subject = $subject;   			

		$mail->Body     = $mesg;		
		
		if($mail->Send()) 		
		{
			echo "<br><br><div class='boxshadow boxlemon' align=center> Your Password Has Been Sent To Your Email Address.</div><br>";
		} else
		{
			echo "<br><br><div class='boxshadow boxred' align=center>Cannot send password to your e-mail address. Problem with sending mail...</div><br>";
		}
}

?>
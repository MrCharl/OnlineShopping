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

if(!$_SESSION["admin"]) { session_destroy(); echo "<center><h2>Permission Denied. Please login as admin first.<br><a href=index.php>Go back</a></h2></center>"; exit; }

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone
$timenow  = strtotime( "now" );

$act = $_GET["act"];


if($act=="delete") {
	$id = $_GET['cid'];
	mysql_db_query($dbname,"delete from ".$fix."member where id='$userid'");
	themehead("ทะเบียนรายชื่อสมาชิก");	
	echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลสมัครสมาชิก เรียบร้อยแล้ว</h1><a href=\"view-member.php\">กลับไปหน้า ทะเบียนรายชื่อสมาชิก</a></div>";
	themefoot();
	exit;
}


if ($act=="update") {
	
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
	$active = $_POST['active'];
	$pwd1 = $_POST['pwd1'];
	$pwd2 = $_POST['pwd2'];
	$currentpwd = $_POST['currentpwd'];
	$active = $_POST['active'];
	$level = $_POST['level'];
	$point = $_POST['point'];
	
	$userid = $_POST['id'];
	
	If($pwd1 != "" && $pwd2 !="") {
		$password = $pwd1;
	} else {
		$password = $currentpwd;
	}
	
	@mysql_db_query($dbname,"update ".$fix."member set email='$email', password='$password', name='$name', address='$address', city='$city', zipcode='$zipcode', mobile='$mobile', active='$active', sex='$sex', bdate='$bdate', bmonth='$bmonth', byear='$byear',level='$level',point='$point' where id='$userid' ");

	themehead("Update Completed.");	
	echo "<script language=javascript>sweetAlert('ปรับปรุงข้อมูลสมาชิก เรียบร้อยแล้ว');</script>";
	echo "<center><h1>Update Member Profied Completed.</h1><a href=\"view-member.php\">กลับไปหน้า ทะเบียนรายชื่อสมาชิก</a></center>";
	themefoot();
	exit;
		
}

if($act=="modify") { //แก้ไขข้อมูลสมาชิก

	include("config.php");
	
	$userid = $_REQUEST['id'];
	
	//$userid = $_SESSION['user'];
	$strSQL = " SELECT * FROM ".$fix."member WHERE id = '".$userid." '  ";
	$objQuery = mysql_query($strSQL);
	$objResult = mysql_fetch_array($objQuery);
	
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
	$active = $objResult['active'];
	$level = $objResult['level'];
	$currentpwd = $objResult['password'];
	$point = $objResult['point'];
	
	themehead("Member Area.");	
	
	$timenow=time();
	$currentdate = date("Y-m-d H:i:s A",$timenow);
	
	if($active=='0'){ $act1 = " selected"; }
	if($active=='1'){ $act2 = " selected"; }
			
	if($level=='0'){ $lv1 = " selected"; }
	if($level=='1'){ $lv2 = " selected"; }
	if($level=='2'){ $lv3 = " selected"; }
	
	echo "

	<center><h2>ข้อมูลลสมาชิก: คุณ$name</h2>
	<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถ แก้ไข / ลบ ข้อมูลลสมาชิก</b> | <a href=backshopoffice.php><i class='fa fa-arrow-circle-left'></i> กลับไปที่หลังร้าน</a></div><br>
	<p align=\"center\"><a class='boxshadow boxsky' href=\"?act=\"><i class='fa fa-arrow-circle-left'></i> กลับไปหน้า ทะเบียนรายชื่อสมาชิก</a></p>
	<form action=\"view-member.php?act=update\" method=post name=\"memberform\" onsubmit=\"return checkmemberform()\">
	<table class=\"mytables\" width=\"80%\">
		<tr>
		<td colspan=2 align=center>UserID:
			<input class=\"tblogin\" type=text name=\"id\" value='".$userid."' size=2 disabled>&nbsp;&nbsp;Level:&nbsp;&nbsp;
			<select name=\"level\"> 
				<option value='0' $lv1>สมาชิกทั่วไป</option>
				<option value='1' $lv2>ลูกค้า</option>
				<option value='2' $lv3>ลูกค้า VIP</option>
			</select>&nbsp;&nbsp;Activate:&nbsp;&nbsp;
			<select name=\"active\">
				<option value='0' $act1>No</option>
				<option value='1' $act2>Yes</option>
			</select>&nbsp;&nbsp;Points:&nbsp;&nbsp;
			<input class=\"tblogin\" type=text name=\"point\" value='".$point."' size=5>
		</td>
		</tr>
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
	";
	themefoot();
	exit;
}

if($act=="") {

themehead("ทะเบียนรายชื่อสมาชิก");

$counter = 0;

$query = "SELECT * FROM ".$fix."member ORDER BY id desc";

$query_result = mysql_query($query) or die("Unable to process query: " . mysql_error());
$numrows = mysql_num_rows($query_result);

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

echo "
			<div class=\"boxshadow boxlemon\" align=center><h2>จัดการ ลูกค้า/สมาชิก</h2>Activated = ลูกค้าคลิกยืนยันการสมัครในอีเมล์ตอบรับแล้ว / ยอดสั่งซื้อสะสม = ยอดที่จ่ายเงินแล้ว</div><br>
			<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถ แก้ไข / ลบ ข้อมูลลสมาชิก</b> | <a href=backshopoffice.php><i class='fa fa-arrow-circle-left'></i> กลับไปที่หลังร้าน</a></div><br>
			<table width=\"90%\" border=\"1\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\">
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

$query_result = mysql_query($query) or die("Unable to process query: " . mysql_error());

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
				location = '?act=delete&userid='+cid; 
			} else {
				swal('Cancelled', 'ยกเลิกการลบภาพแล้ว :)', 'error');
			}
		});	
}
</script>
";

while ($info = @mysql_fetch_array($query_result))
{
	$counter++;
	
	$new="";
	if($info['new'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
	
	$lv = $info['level'];
	
	if($lv=='0'){ $level = "สมาชิกทั่วไป"; }
	if($lv=='1'){ $level = "ลูกค้า"; }
	if($lv=='2'){ $level = "ลูกค้า VIP"; }	
	
	$activate = ($info['active']=='1') ? "<i class='boxshadow boxlemon'>Yes</i>" : "<i class='boxshadow boxred'>No</i>";
	
	/*
	$query="select sum(totalprice) as purchase from ".$fix."orders where custid = '".$info['id']."' and orderstatus!='0' ";
	$result = mysql_db_query($dbname,$query);
	$arr = mysql_fetch_array($result);
	$purchase  = number_format($arr[0],2);
	$point = 0;
	$point = round(($arr[0]*$factor)/100);
	*/

	echo "
              <tr>
              <td height=\"30\" align=\"center\">".$info['id']."</td>
              <td height=\"30\" align=\"left\"><a href=view-member.php?act=modify&id=".$info['id'].">".$info['name']." $new</a></td>
              <td height=\"30\" align=\"right\">".number_format($info['purchase'],2)."</td>
              <td height=\"30\" align=\"right\">".$info['point']."</td>
              <td height=\"30\" align=\"left\">".$level."</td>
              <td height=\"30\" align=\"center\">".$activate."</td>
              <td height=\"30\" align=\"center\">
			  <a class='boxshadow boxlightblue' href=view-member.php?act=modify&id=".$info['id']." title=\"แก้ไข\"><i class='fa fa-edit'></i> แก้ไข</a>
			  <a class='boxshadow boxred' href=\"javascript: confirmdel('$info[id]')\" title=\"ลบ\"><i class='fa fa-remove'></i> ลบ</a>
			  </td>
              </tr>
			  ";

}

	echo "</table><br>";

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

}

	$strSQL = "UPDATE ".$fix."member ";
	$strSQL .="SET new = '0' ";
	$objQuery = mysql_query($strSQL);	
	
themefoot();
?>

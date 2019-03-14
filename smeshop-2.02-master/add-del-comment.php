<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

$Details = $_POST[ 'txtDetails' ];
$Details = stripslashes( $Details );
$Details = mysql_real_escape_string( $Details );
	
$Name = $_POST[ 'txtName' ];
$Name = stripslashes( $Name );
$Name = mysql_real_escape_string( $Name );

$url = $_REQUEST['url'];

Global $qid;

if($_GET["Action"] == "Save") 
	{
				
		themehead("Add Comment");	
		
		if((trim($_POST["txtDetails"]))&&(trim($_POST["txtName"]))&&(trim($_POST["b5"]))) 
		{
			/* Check Spam */
			$send=1;
			if(!eregi($_SERVER["HTTP_HOST"],$HTTP_REFERER)){ unset($send); }
			if(!$_SESSION["nspam"]){ unset($send); }
			if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
			if($send){
				echo "<div class='boxshadow boxlemon' align=center><h1>บันทึกข้อมูลเรียบร้อยแล้ว</h1><a href=$url>กลับไปหน้าเก่า</a></div>";			

				if($_SESSION['member']['name']) {
					$Name = $Name."(M)";
				}	
					if($_SESSION['admin']) {
					$Name = $Name."(S)";
				}				
				
				//*** Insert Reply ***//
				$strSQL = "INSERT INTO ".$fix."reply ";
				$strSQL .="(QuestionID,CreateDate,Details,Name,ReplyType,New) ";
				$strSQL .="VALUES ";
				$strSQL .="('".$qid."','".date("Y-m-d H:i:s")."','".$Details."','".$Name."','1','1')";	
		
				$objQuery = mysql_query($strSQL);

				
			}  else {
				echo "<div class='boxshadow boxred' align=center><h1>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1><a href=$url>กลับไปแก้ไขใหม่อีกครั้ง</a></div>";	
			}
		} else {
				echo "<div class='boxshadow boxred' align=center><h1>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1><a href=$url>กลับไปแก้ไขใหม่อีกครั้ง</a></div>";	
		}
		
		themefoot();
}
		

if($act=="delreply") {
	
	if(!$_SESSION["admin"]) { session_destroy(); echo "<center><h2>Permission Denied. Please login as admin first.<br><a href=index.php>Go back</a></h2></center>"; exit; }
	
	$postid = $_REQUEST['postid'];
	$replyid = $_REQUEST['replyid'];
	$confirm = $_GET['confirm'];
	
	if($confirm =="") {
		
		themehead("ลบ Reply");	
		echo "<br><br><div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบ ?</h2>"; 
		echo "<a href=\"?act=delreply&confirm=yes&postid=$postid&replyid=$replyid&url=$url\">แน่ใจ ยืนยันการลบ</a> | <a href=\"$url\">ยกเลิกการลบ</a></div>";
		themefoot();
		exit;
		
	} else {

		mysql_db_query($dbname,"delete from ".$fix."reply where ReplyID='".$replyid." ' ");
		
		//*** Update Reply ***//
		$strSQL = "UPDATE ".$fix."webboard ";
		$strSQL .="SET Reply = Reply -1 WHERE QuestionID = ' ".$postid." ' ";
		$objQuery = mysql_query($strSQL);	
	
		themehead("ลบข้อมูลโพสต์");	
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลเรียบร้อยแล้ว</h1><a href=$url>ย้อนกลับไปหน้าที่มา</a></div>";
		themefoot();
		mysql_close($connection);
		exit;
	}
}

if($act=="delpost") {
	
	$postid = $_REQUEST['postid'];
	$confirm = $_GET['confirm'];
	
	if($confirm =="") {
		
		themehead("Delete Post & Reply");	
		echo "<br><br><div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบ ?</h2>"; 
		echo "<a href=\"?act=delpost&confirm=yes&postid=$postid&url=$url\">แน่ใจ ยืนยันการลบ</a> | <a href=$url>ยกเลิกการลบ</a></div>";
		themefoot();
		exit;
		
	} else {

		mysql_db_query($dbname,"delete from ".$fix."webboard where QuestionID='".$postid." ' ");
		mysql_db_query($dbname,"delete from ".$fix."reply where QuestionID='".$postid." ' ");
	
		themehead("ลบข้อมูลโพสต์");	
		echo "<script language=javascript>sweetAlert('ลบข้อมูลเรียบร้อยแล้ว');</script>"; 
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลเรียบร้อยแล้ว</h1></div>";
		themefoot();
		mysql_close($connection);
		exit;
	}
}

if($act=="delarticle") {
	
	$postid = $_REQUEST['postid'];
	$confirm = $_GET['confirm'];
	
	if($confirm =="") {
		
		themehead("Delete Article");	
		echo "<br><br><div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบบทความนี้หรือไม่ ?</h2>"; 
		echo "<a href=\"?act=delarticle&confirm=yes&postid=$postid&url=$url\">แน่ใจ ยืนยันลบบทความนี้</a> | <a href=$url>ยกเลิก</a></div>";
		themefoot();
		exit;
		
	} else {
	
		//*** Select Question ***//
		$strSQL = "SELECT Picture FROM ".$fix."webboard where QuestionID = '".$postid."'";
		$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		$objResult = mysql_fetch_array($objQuery);
		$img = $objResult['Picture'];
	
		//Delete Picture
		if($img)
		{
			@unlink("$folder/$img"); 
			@unlink("$folder/thumb_$img"); 
		}

		//Delete Post
		mysql_db_query($dbname,"delete from ".$fix."webboard where QuestionID='".$postid." ' ");
	
		themehead("ลบบทความ");	
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบบาทความพร้อมภาพประกอบ เรียบร้อยแล้ว</h1><a href=$url>กลับไปหน้าบทความ</a></div>";
		themefoot();
		mysql_close($connection);
		exit;
	}
}
		
?>
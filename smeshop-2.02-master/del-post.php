<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

if(!$_SESSION["admin"]) { session_destroy(); echo "<center><h2>Permission Denied. Please login as admin first.<br><a href=index.php>Go back</a></h2></center>"; exit; }

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

$Details = $_POST[ 'txtDetails' ];
$Details = stripslashes( $Details );
$Details = mysqli_real_escape_string($connection, $Details );
	
$Name = $_POST[ 'txtName' ];
$Name = stripslashes( $Name );
$Name = mysqli_real_escape_string($connection, $Name );

switch($act) {

case "delreply" :
	
	$postid = $_REQUEST['postid'];
	$replyid = $_REQUEST['replyid'];
	$confirm = $_GET['confirm'];
	$url = $_REQUEST['url'];
	
	if($confirm =="") {
		
		themehead("ลบ Reply");	
		echo "<br><br><div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบ ?</h2>"; 
		echo "<a href=\"?act=delreply&confirm=yes&postid=$postid&replyid=$replyid&url=$url\">แน่ใจ ยืนยันการลบ</a> | <a href=\"$url\">ยกเลิกการลบ</a></div>";
		themefoot();
		exit;
		
	} else {

		mysqli_query($connection,"delete from ".$fix."reply where ReplyID='".$replyid." ' ");
		
		//*** Update Reply ***//
		$strSQL = "UPDATE ".$fix."webboard ";
		$strSQL .="SET Reply = Reply -1 WHERE QuestionID = ' ".$postid." ' ";
		$objQuery = mysqli_query($connection,$strSQL);	
	
		themehead("ลบข้อมูลโพสต์");	
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลเรียบร้อยแล้ว</h1><a href=$url>ย้อนกลับไปหน้าที่มา</a></div>";
		themefoot();
		mysqli_close($connection);
		exit;
	}
break;

case "delpost" :
	
	$postid = $_REQUEST['postid'];
	$confirm = $_GET['confirm'];
	$url = $_REQUEST['url'];
	
	if($confirm =="") {
		
		themehead("Delete Post & Reply");	
		echo "<br><br><div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบ ?</h2>"; 
		echo "<a href=\"?act=delpost&confirm=yes&postid=$postid&url=$url\">แน่ใจ ยืนยันการลบ</a> | <a href=$url>ยกเลิกการลบ</a></div>";
		themefoot();
		exit;
		
	} else {

		mysqli_query($connection,"delete from ".$fix."webboard where QuestionID='".$postid." ' ");
		mysqli_query($connection,"delete from ".$fix."reply where QuestionID='".$postid." ' ");
	
		themehead("ลบข้อมูลโพสต์");	
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบข้อมูลเรียบร้อยแล้ว</h1><a href=webboard.php>กลับไปหน้าเว็บบอร์ด</a></div>";
		themefoot();
		mysqli_close($connection);
		exit;
	}
break;

case "delarticle" :
	
	$postid = $_REQUEST['postid'];
	$confirm = $_GET['confirm'];
	$url = $_REQUEST['url'];
	
	if($confirm =="") {
		
		themehead("Delete Article");	
		echo "<br><br><div class='boxshadow boxred' align=center><h2>ท่านแน่ใจที่จะลบบทความนี้หรือไม่ ?</h2>"; 
		echo "<a href=\"?act=delarticle&confirm=yes&postid=$postid&url=$url\">แน่ใจ ยืนยันลบบทความนี้</a> | <a href=$url>ยกเลิก</a></div>";
		themefoot();
		exit;
		
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
	
		themehead("ลบบทความ");	
		echo "<div class='boxshadow boxlemon' align=center><h1>ลบบาทความพร้อมภาพประกอบ เรียบร้อยแล้ว</h1><a href=$url>กลับไปหน้าบทความ</a></div>";
		themefoot();
		mysqli_close($connection);
		exit;
	}
break;

case "delreview" :
	
	$confirm = $_GET['confirm'];
	$url = $_REQUEST['url'];
	
	$reviewid = $_REQUEST[ 'reviewid' ];
	$reviewid = stripslashes( $reviewid );
	$reviewid = mysqli_real_escape_string($connection, $reviewid );

	$productid = $_REQUEST[ 'productid' ];
	$productid = stripslashes( $productid );
	$productid = mysqli_real_escape_string($connection, $productid );
	
	if($confirm =="") {
		themehead("Delete Article");	
		echo "<div class=\"boxshadow boxred\" align=center><h2>ท่านแน่ใจที่จะลบ Review นี้หรือไม่ ?</h2>"; 
		echo "<a href=\"?act=delreview&confirm=yes&reviewid=$reviewid&productid=$productid&url=$url\">แน่ใจ ยืนยันลบ Review นี้</a> | <a href=$url>ยกเลิกการลบ</a></div>";
		themefoot();
	} else {
		themehead("Delete Article");	
		mysqli_query($connection,"delete from ".$fix."reviews where review_id='".$reviewid." ' ");
		echo "<div class=\"boxshadow boxlemon\" align=center><h1>ลบข้อมูล Review เรียบร้อยแล้ว</h1><a href=$url>กลับไปหน้าสินค้า</a></div>";
		themefoot();
		mysqli_close($connection);
	}

break;

}
		
?>
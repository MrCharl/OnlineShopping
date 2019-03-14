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

themehead("เขียนรีวิวสินค้าใหม่");

$url = $_REQUEST['url'];

if($act == "addreview") {
		/* Check Spam */
		$send=1;
		if(!eregi($_SERVER["HTTP_HOST"],$HTTP_REFERER)){ unset($send); }
		if(!$_SESSION["nspam"]){ unset($send); }
		if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
		if($send){
				$reviewdate = date("Y-m-d H:i:s");
				@mysql_db_query($dbname,"insert into ".$fix."reviews (review_id,product_id,rating,review, reviewer_name,reviewer_email,review_date,new) values('','$product_id','$rating','$review','$reviewer_name','$reviewer_email','$reviewdate','1')");
				echo "<div class=\"boxshadow boxlemon\" align=center><h2>บันทึก Review เรียบร้อยแล้ว ขอบคุณมากค่ะ</h2><a href=$url>กลับไปที่หน้าสินค้า</a></div>";
		}  else {
				echo "<div class=\"boxshadow boxlemon\" align=center><h1><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1><a href=$url>กลับไปหน้าสินค้า</a></div>";	
		}
}


if($act=="delreview") {
	
	if(!$_SESSION["admin"]) { session_destroy(); echo "<center><h2>Permission Denied. Please login as admin first.<br><a href=index.php>Go back</a></h2></center>"; exit; }
	
	$confirm = $_GET['confirm'];
	
	$reviewid = $_REQUEST[ 'reviewid' ];
	$reviewid = stripslashes( $reviewid );
	$reviewid = mysql_real_escape_string( $reviewid );

	$productid = $_REQUEST[ 'productid' ];
	$productid = stripslashes( $productid );
	$productid = mysql_real_escape_string( $productid );

	
	if($confirm =="") {
		
		echo "<div class=\"boxshadow boxred\" align=center><h2>ท่านแน่ใจที่จะลบ Review นี้หรือไม่ ?</h2>"; 
		echo "<a href=\"?act=delreview&confirm=yes&reviewid=$reviewid&productid=$productid&url=$url\">แน่ใจ ยืนยันลบ Review นี้</a> | <a href=$url>ยกเลิกการลบ</a></div>";
		
	} else {

		mysql_db_query($dbname,"delete from ".$fix."reviews where review_id='".$reviewid." ' ");
	
		echo "<div class=\"boxshadow boxlemon\" align=center><h1>ลบข้อมูล Review เรียบร้อยแล้ว</h1><a href=$url>กลับไปหน้าสินค้า</a></div>";
	}
}

themefoot();
mysql_close($connection);
		
?>
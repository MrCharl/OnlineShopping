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

$act = $_REQUEST['act'];

switch($act){

case "addreview" :

		$review = $_POST[ 'review' ];
		$review = stripslashes( $review );
		$review = mysqli_real_escape_string($connection, $review );
	
		$reviewer_name = $_POST[ 'reviewer_name' ];
		$reviewer_name = stripslashes( $reviewer_name );
		$reviewer_name = mysqli_real_escape_string($connection, $reviewer_name );
		
		$reviewer_email = $_POST[ 'reviewer_email' ];
		$reviewer_email = stripslashes( $reviewer_email );
		$reviewer_email = mysqli_real_escape_string($connection, $reviewer_email );

		if($_SESSION['member']['avatar']) {
			$AvatarPic = "users/".$_SESSION['member']['avatar'];
		}
		
		switch ($AvatarPic) {
			case "users/" :
					$AvatarPic = "member.jpg";
			break;
			case "" :
					$AvatarPic = "unknown_user.jpg";
			break;
		}

		if($_SESSION['admin']) {
			$AvatarPic = "shopper.jpg";
		}		

		$url = $_REQUEST['url'];
		themehead("เขียนรีวิวสินค้าใหม่");
		/* Check Spam */
		$send=1;

		if(!$_SESSION["nspam"]){ unset($send); }
		if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
		if($send){

				if($_SESSION['member']['name']) {
					$reviewer_name = $reviewer_name."(M)";
				}	
				if($_SESSION['admin']) {
					$reviewer_name = $reviewer_name."(S)";
				}				
			
				$reviewdate = date("Y-m-d H:i:s");
				@mysqli_query($connection,"insert into ".$fix."reviews (review_id,product_id,rating,review, reviewer_name,reviewer_email,review_date,New,avatar) values('','$product_id','$rating','$review','$reviewer_name','$reviewer_email','$reviewdate','1','$AvatarPic')");
				echo "<div class=\"boxshadow boxlemon\" align=center><h2>บันทึก Review เรียบร้อยแล้ว ขอบคุณมากค่ะ</h2><a href=$url>กลับไปที่หน้าสินค้า</a></div>";
		}  else {
				echo "<div class=\"boxshadow boxlemon\" align=center><h1><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1><a href=$url>กลับไปหน้าสินค้า</a></div>";	
		}
		themefoot();
		mysql_close($connection);
break;

case "addcomment" :

		$Details = $_POST[ 'txtDetails' ];
		$Details = stripslashes( $Details );
		$Details = mysqli_real_escape_string($connection, $Details );
	
		$Name = $_POST[ 'txtName' ];
		$Name = stripslashes( $Name );
		$Name = mysqli_real_escape_string($connection, $Name );
		
		if($_SESSION['member']['avatar']) {
			$AvatarPic = "users/".$_SESSION['member']['avatar'];
		}
		
		switch ($AvatarPic) {
			case "users/" :
					$AvatarPic = "member.jpg";
			break;
			case "" :
					$AvatarPic = "unknown_user.jpg";
			break;
		}

		if($_SESSION['admin']) {
			$AvatarPic = "shopper.jpg";
		}		

		$url = $_REQUEST['url'];

		Global $qid;

		themehead("Add Comment");	
		
		if((trim($_POST["txtDetails"]))&&(trim($_POST["txtName"]))&&(trim($_POST["b5"]))) 
		{
			/* Check Spam */
			$send=1;

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
				$strSQL .="(QuestionID,CreateDate,Details,Name,ReplyType,New,Avatar) ";
				$strSQL .="VALUES ";
				$strSQL .="('".$qid."','".date("Y-m-d H:i:s")."','".$Details."','".$Name."','1','1','".$AvatarPic."')";	
		
				$objQuery = mysqli_query($connection,$strSQL);

				
			}  else {
				echo "<div class='boxshadow boxred' align=center><h1>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1><a href=$url>กลับไปแก้ไขใหม่อีกครั้ง</a></div>";	
			}
		} else {
				echo "<div class='boxshadow boxred' align=center><h1>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1><a href=$url>กลับไปแก้ไขใหม่อีกครั้ง</a></div>";	
		}
		
		themefoot();
		mysql_close($connection);
break;


}


		
?>
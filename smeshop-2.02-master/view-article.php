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

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

$qid = $_GET["ArticleID"];
$url = "view-article.php?ArticleID=$qid";

themehead("บทความที่น่าสนใจ");	

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

echo "<div class=\"boxshadow article\"><h2>".$objResult['Article']."</h2><a  class=\"bb\" href=\"article.php\"><b><i class='fa fa-eye'></i> บทความทั้งหมด</b></a><br><br></div><br>";

if($_SESSION['admin']) { echo "<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถเขียน/แก้ไข/ลบ บทความ</b> | <a href=backshopoffice.php>กลับไปที่หลังร้าน</a></div><br>"; }

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
		<a href=del-post.php?act=delarticle&postid=$qid&url=$url><img src=images/delete.gif></a>
		<a href=edit-article.php?postid=$qid><img src=images/edit.gif></a>
	</td>
  </tr>
</table>
<br>
<br>";

/******************** แสดงช่องโพสต์ถาม-ตอบ ************************/

if($commentsys) {
	echo"<br><br><div class=\"boxshadow boxlemon\" align=center><a name='question'>ร่วมแสดงความคิดเห็น</a></div><hr class='style-two'>";
	$url = "view-article.php?ArticleID=$qid";
	include("view-comment.php");
}

/*************************************************************/

themefoot();
mysqli_close($connection);
exit;
		
?>
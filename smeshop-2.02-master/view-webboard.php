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


themehead("อ่าน-ตอบคำถาม");	

	echo "<div class=\"boxshadow webboard\"><h2>กระดานสนทนา</h2>
	<center><a class=\"bb\" href=\"new-question.php\"><b><i class='fa fa-edit'></i> ตั้งคำถามใหม่</b></a>&nbsp;&nbsp;&nbsp;
	<a class=\"bb\" href=\"webboard.php\"><b><i class='fa fa-eye'></i> ดูคำถามทั้งหมด</b></a><br><br></div></center><br>";
	
	if($_SESSION['admin']) { echo "<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถตั้งกระทู้ / ตอบ / ลบ Post /ลบ Reply</b> | <a href=backshopoffice.php><i class='fa fa-arrow-left-circle'></i> กลับไปที่หลังร้าน</a></div><br>"; }

	$qid = $_GET["QuestionID"];

	$Details = $_POST[ 'txtDetails' ];
	$Details = stripslashes( $Details );
	$Details = mysqli_real_escape_string($connection, $Details );
	
	$Name = $_POST[ 'txtName' ];
	$Name = stripslashes( $Name );
	$Name = mysqli_real_escape_string($connection, $Name );

	if($_GET["Action"] == "Save") 
	{
		if((trim($_POST["txtDetails"]))&&(trim($_POST["txtName"]))&&(trim($_POST["b5"]))) 
		{
			/* Check Spam */
			$send=1;

			if(!$_SESSION["nspam"]){ unset($send); }
			if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
			if($send){
				echo "<center><font color=blue><h3><b>บันทึกคำตอบเรียบร้อยแล้ว</b></h3></font></center><br>";				
				
				if($_SESSION['member']['name']) {
					$Name = $Name."(M)";
				}	
					if($_SESSION['admin']) {
					$Name = $Name."(S)";
				}			
				
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
				
				//*** Insert Reply ***//
				$strSQL = "INSERT INTO ".$fix."reply ";
				$strSQL .="(QuestionID,CreateDate,Details,Name,ReplyType,New,Avatar) ";
				$strSQL .="VALUES ";
				$strSQL .="('".$_GET["QuestionID"]."','".date("Y-m-d H:i:s")."','".$Details."','".$Name."','0','1','".$AvatarPic."')";	
		
				$objQuery = mysqli_query($connection,$strSQL);
				
				//*** Update Webboard ***//
				$strSQL = "UPDATE ".$fix."webboard ";
				$strSQL .="SET Reply = Reply + 1 WHERE QuestionID = '".$_GET["QuestionID"]."' ";
				$objQuery = mysqli_query($connection,$strSQL);	
				
			}  else {
				echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font></center><br>";	
			}
		} else {
			echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font></center><br>";	
		}
	}
	
	//*** Select Question ***//
	$strSQL = "SELECT * FROM ".$fix."webboard  WHERE QuestionID = '".$_GET["QuestionID"]."' ";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$objResult = mysqli_fetch_array($objQuery);
	
			$Name = $objResult["Name"];
			$chkimg = substr($Name,-3);
			switch ($chkimg) {
					case "(M)" :
						$Name = substr($Name,0,-3); 
						$title = "ลูกค้า/สมาชิก";
						break;
					case "(S)" :
						$Name = substr($Name,0,-3); 
						$title = "เจ้าของร้าน";
						break;
					default:
						$Name = $Name; 
						$title = "ลูกค้าทั่วไป";
			}
			
			$bbimg = $objResult['Avatar'];
			if($bbimg == "") { $bbimg == "unknown_user.jpg"; }
	
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
			<td align=center><a href=del-post.php?act=delpost&replyid=$replyid&postid=$qid&url=view-webboard.php?QuestionID=$qid><img src=images/delete.gif></a></td>
			</tr>
		</table>
	</td>
	</tr>
	</table><br>";

$intRows = 0;
$strSQL2 = "SELECT * FROM ".$fix."reply  WHERE QuestionID = '".$_GET["QuestionID"]."' ORDER by CreateDate ASC ";
$objQuery2 = mysqli_query($connection,$strSQL2) or die ("Error Query [".$strSQL."]");
while($objResult2 = mysqli_fetch_array($objQuery2))
{
	$intRows++;
	$replyid = $objResult2['ReplyID'];
	
			$Name = $objResult2["Name"];
			$chkimg = substr($Name,-3);
			switch ($chkimg) {
					case "(M)" :
						$Name = substr($Name,0,-3); 
						$title = "สมาชิก";
						break;
					case "(S)" :
						$Name = substr($Name,0,-3); 
						$title = "เจ้าของร้าน";
						break;
					default:
						$Name = $Name; 
						$title = "ลูกค้าทั่วไป";
			}
			
			$bbimg = $objResult2['Avatar'];
			if($bbimg == "") { $bbimg == "unknown_user.jpg"; }
		
	echo "
	<table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#cccccc\">
	<tr>
	<td>
		<table width=100% border=1 cellpadding=4 cellspacing=0 bordercolor=#eeeeee>
		<tr><td align=center width=100><img src=images/$bbimg widt=75 height=75></td><td valign=top colspan=3>".nl2br($objResult2["Details"])."</td></tr>
		<tr bgcolor=#eeeeee>
			<td align=center><b>".$title."</b></td>
			<td width=200>ตอบโดย คุณ".$Name."</td>
			<td>ตอบเมื่อ ".thai_time($objResult2['CreateDate'])."</td>
			<td align=center><a href=del-post.php?act=delreply&replyid=$replyid&postid=$qid&url=view-webboard.php?QuestionID=$qid><img src=images/delete.gif></a></td>
		</tr>
		</table>
	</td>
	</tr>
	</table><br>";
}

if( $bbsys==2)  {

echo "
<h2>ตอบคำถาม</h2>
<div class=\"boxshadow boxlemon\"><a href=\"webboard.php\"><b><i class='fa fa-eye'></i> ดูคำถามทั้งหมด</b></a>&nbsp;&nbsp;&nbsp;<a href=\"new-question.php\"><b><i class='fa fa-edit'></i> ตั้งคำถามใหม่</b></a> </div><br><br>
<form action=\"view-webboard.php?QuestionID=".$_GET['QuestionID']."&Action=Save\" method=\"post\" name=\"frmMain\" id=\"frmMain\">
  <table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" bordercolor=\"#cccccc\">
  <tr><td align=center>
  <table border=0 width=100%>
    <tr>
      <td colspan=2><textarea name=\"txtDetails\" cols=\"95\" rows=\"15\" id=\"txtDetails\"></textarea></td>
    </tr>
    <tr>
      <td width=\"200\">ชื่อ:<br><input name=\"txtName\" type=\"text\" class=\"tblogin\" id=\"txtName\" value='".$_SESSION['member']['name']."' size=\"50\"></td>
      <td align=center>กรุณากรอกรหัส 4 ตัว<br><table cellspacing=1 cellpadding=0><tr><td bgcolor=red><img src=\"img.php\"></td><td><input type=text class=\"tblogin\" size=8 name=b5></td></tr></table></td>
    </tr>	
  </table>
  </td></tr>
  </table>
  <br>
  <center>
  <input name=\"btnSave\" type=\"submit\" id=\"btnSave\" class=\"myButton\" value=\"Submit\">
  </center>
</form>
</center>

	<!--
	<script src=\"ckeditor/ckeditor.js\"></script>
    <script>
            CKEDITOR.replace(\"txtDetails\");
    </script>
	-->
	
	<script src=\"js/nicEdit.js\" type=\"text/javascript\"></script>
	<script type=\"text/javascript\">
	bkLib.onDomLoaded(function() {
		new nicEditor({fullPanel : true}).panelInstance('txtDetails');
	});
	</script>";

} else {
		echo "<font color=red><b>กล่องตอบคำถามถูกปิดซ่อนไว้ เนื่องจากเจ้าของร้านกำหนด ให้ตอบคำถามได้เฉพาะเจ้าของร้านค้าเท่านั้น</b></font><br>";
}

themefoot();
mysqli_close($connection);
exit;
		
?>
<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

	Global $url;
	
	$Details = $_POST[ 'txtDetails' ];
	$Details = stripslashes( $Details );
	$Details = mysqli_real_escape_string($connection, $Details );
	
	$Name = $_POST[ 'txtName' ];
	$Name = stripslashes( $Name );
	$Name = mysqli_real_escape_string($connection, $Name );

	$intRows = 0;
	$strSQL = "SELECT * FROM ".$fix."reply  WHERE QuestionID = '".$qid."' ORDER by CreateDate ASC ";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$numrow = mysqli_num_rows($objQuery);
	
	if($numrow>0) {
	while($objResult = mysqli_fetch_array($objQuery))
	{
			$intRows++;
			$replyid = $objResult['ReplyID'];
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
			echo "
			<table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#cccccc\">
			<tr>
			<td>
			<table width=100% border=1 cellpadding=4 cellspacing=0 bordercolor=#eeeeee>
			<tr><td align=center width=100><img src=images/$bbimg widt=75 height=75></td><td valign=top colspan=3>".nl2br($objResult["Details"])."</td></tr>
			<tr bgcolor=#eeeeee>
			<td align=center><b>".$title."</td>
			<td>โดยคุณ ".$Name."</td>
			<td>เมื่อ ".thai_time($objResult['CreateDate'])."</td>
			<td align=center><a href=del-post.php?act=delreply&replyid=$replyid&postid=$qid&url=$url><img src=images/delete.gif></a></td>
			</tr>
			</table>
			</td>
			</tr>
			</table><br>";
	}
	

}  else {
			echo "<div align=center><b>=== ยังไม่มีคำถาม/ความคิดเห็น ===</b></div><hr class='style-two'>";
}
	

if( $bbsys==2)  {

echo "<center>
<form action=\"add-post.php?qid=".$qid."&act=addcomment\" method=\"post\" name=\"frmMain\" id=\"frmMain\">
  <table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" bordercolor=\"#cccccc\">
  <tr><td align=center>
  <table border=0 width=100%>
    <tr>
      <td colspan=2><textarea name=\"txtDetails\" cols=\"95\" rows=\"5\" id=\"txtDetails\"></textarea></td>
    </tr>
    <tr>
      <td width=\"200\">ชื่อ:<br><input name=\"txtName\" type=\"text\" class=\"tblogin\" id=\"txtName\" value='".$_SESSION['member']['name']."' size=\"50\"></td>
      <td align=center>กรุณากรอกรหัส 4 ตัว<br><table cellspacing=1 cellpadding=0><tr><td bgcolor=red><img src=\"img.php\"></td><td><input type=text class=\"tblogin\" size=8 name=b5></td></tr></table></td>
    </tr>	
	<tr>
	<td colspan=2>
  <center>
  <input type=\"hidden\" name=\"url\" value=".$url.">";

  if($commentsys==2) {
		if($_SESSION['member']['name']) {
			echo "<input name=\"btnSave\" type=\"submit\" id=\"btnSave\" class=\"myButton\" value=\"Submit\">";
		} else {
			echo "<br><font color=red><b>* ปุ่ม Submit ถูกซ่อนไว้ เฉพาะสมาชิกที่ Login เท่านั้น จึงจะสามารถตั้งคำถามหรือร่วมแสดงความเห็นได้</b></font>";
		}
  } else {
	  	echo "<input name=\"btnSave\" type=\"submit\" id=\"btnSave\" class=\"myButton\" value=\"Submit\">";
  }
  
  echo "
 </center>	
	</td>
	</tr>
  </table>
  </td></tr>
  </table>
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
		
?>
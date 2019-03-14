<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

	Global $url;

	$query = "SELECT * FROM ".$fix."reviews where product_id='".$idp."' ORDER BY review_date desc";
	$result = mysqli_query($connection,$query); 
	$numrows = mysqli_num_rows($result);
	$i=0;
	if($numrows > 0) {
		echo "<table width=100% border=0><tr>";
		while ($reviewarr = @mysqli_fetch_array($result))
		{
			
			$Name = $reviewarr['reviewer_name'];
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
			
			$bbimg = $reviewarr['avatar'];
			if($bbimg == NULL) { $bbimg == "unknown_user.jpg"; }
			
			echo "
			<table class=\"mytables\" width=\"738\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#cccccc\">
			<tr>
			<td>
			<table width=100% border=1 cellpadding=4 cellspacing=0 bordercolor=#eeeeee>
			<tr>
				<td align=center width=100><img src=images/$bbimg widt=75 height=75></td>
				<td valign=top colspan=2>".$reviewarr['review']."</td>
				<td width=100 align=center>คะแนนความพอใจ<br><div class=\"rateit\" data-rateit-value=".$reviewarr['rating']." data-rateit-ispreset=\"true\" data-rateit-readonly=\"true\"></div></td>
			</tr>
			<tr bgcolor=#eeeeee>
				<td align=center><b>".$title."</b></td>
				<td width=200>โดยคุณ ".$reviewarr['reviewer_name']."</td>
				<td>เมื่อ ".thai_time($reviewarr['review_date'])."</td>
				<td align=center><a href=del-post.php?act=delreview&reviewid=".$reviewarr['review_id']."&productid=".$reviewarr['product_id']."&url=catalog.php?idp=".$idp."><img src=images/delete.gif></a></td>
			</tr>
			</table>
			</td>
			</tr>
			</table><br>";
			$i++;
		}
	} else {
			echo "<div align=center><b>=== ยังไม่มีความเห็น ===</b></div><hr class='style-two'>";
	}

	echo "
	<form action=\"add-post.php?act=addreview\" method=\"post\">
	<table  width=100% border=0  cellpadding=0 cellspacing=1><tr><td align=center>
	<table class=\"mytables\" width=80% border=0 cellpadding=0 cellspacing=10>
	<tr><td colspan=2 align=center>ขอเชิญร่วมแสดงความเห็นต่อสินค้าชิ้นนี้</td><tr>
	<tr>
		<td>ความพึงพอใจ (Rating)</td>
		<td>
		<input type=\"range\" value=\"1\" step=\"1\" name=\"rating\" id=\"rating\"> 
		<div class=\"rateit\" data-rateit-backingfld=\"#rating\" data-rateit-resetable=\"false\" data-rateit-ispreset=\"true\"
		data-rateit-min=\"0\" data-rateit-max=\"5\">
		</div>
		<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\" type=\"text/javascript\"></script>
		<script type=\"text/javascript\">
			var jQuery_1_10_2 = $.noConflict(true);
		</script>
		<link rel=\"stylesheet\" href=\"js/rateit/rateit.css\">
		<script src=\"js/rateit/jquery.rateit.js\"></script>
		</td>
	</tr>
	<tr><td colspan=2></td></tr>
	<tr>
		<td><label for=\"review\">ความเห็น (Review)</label></td><td><textarea name=\"review\" rows=\"4\" cols=\"50\" required></textarea> *</td>
	</tr>
	<tr>
		<td><label for=\"review\">ชื่อ</label></td><td><input class=\"tblogin\"  type=\"text\"  size=30 name=\"reviewer_name\" value='".$_SESSION['member']['name']."' required> *</td>
	</tr>
	<tr>
		<td><label for=\"review\">อีเมล์</label></td><td><input class=\"tblogin\"  type=\"text\" size=30 name=\"reviewer_email\" value='".$_SESSION['member']['email']."' required></td>
	</tr>
    <tr>
		<td><lable for=\"b5\">กรุณากรอกรหัส 4 ตัว</lable></td><td><table cellspacing=1 cellpadding=0><tr><td bgcolor=red><img src=\"img.php\"></td><td><input type=text class=\"tblogin\" size=8 name=b5></td></tr></table></td>
	</tr>
   	<tr>
		<td colspan=3 align=center>
		<input type=\"hidden\" name=\"product_id\" value='".$idp."'>
		 <input type=\"hidden\" name=\"url\" value=$url>";
		if($reviewsys==2) {
			if($_SESSION['member']['name']) {
				echo "<input type=\"submit\" class=\"myButton\" value=\"Submit Review\">";
			} else {
				echo "<br><font color=red><b>* ปุ่ม Submit ถูกซ่อนไว้ เฉพาะสมาชิกที่ Login เท่านั้น จึงจะสามารถร่วมแสดงความเห็นได้</b></font>";
			}
		} else {
			echo "<input type=\"submit\" class=\"myButton\" value=\"Submit Review\">";
		}
	echo "
	</td>
	</tr>
	</table>
	</td></tr></table>
	</form>";
	
?>
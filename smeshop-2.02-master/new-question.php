<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

include ("config.php");
include ("category.php");
include ("toplink.php");
include("function.php");

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone


themehead("ตั้งคำถามใหม่");

	echo "<div class=\"boxshadow webboard\"><h2>ตั้งคำถามใหม่</h2><a class=\"bb\" href=\"webboard.php\"><b><i class='fa fa-eye'></i> ดูคำถามทั้งหมด</b></a><br><br></div><br>";
	
	if($_SESSION['admin']) { echo "<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถ ตั้งกระทู้ / ตอบ / ลบ Post /ลบ Reply</b> | <a href=backshopoffice.php>กลับไปที่หลังร้าน</a></div><br>"; }
	
	$Question = $_POST[ 'txtQuestion' ];
	$Question = stripslashes( $Question );
	$Question = mysqli_real_escape_string($connection, $Question );
	
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
	
	if($_GET["Action"] == "Save") 
	{
		if((trim($_POST["txtQuestion"]))&&(trim($_POST["txtDetails"]))&&(trim($_POST["txtName"]))&&(trim($_POST["b5"]))) 
		{
			/* Check Spam */
			$send=1;

			if(!$_SESSION["nspam"]){ unset($send); }
			if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
			if($send){
				echo "<script language=javascript>sweetAlert('Good Job','บันทึกคำถามใหม่เรียบร้อยแล้ว','success');</script>"; 
				echo "<div class='boxshadow boxlemon' align=center><h1>บันทึกคำถามเรียบร้อยแล้ว</h1></div><br>";	
				
				if($_SESSION['member']['name']) {
					$Name = $Name."(M)";
				}	
				if($_SESSION['admin']) {
					$Name = $Name."(S)";
				}
								
				//*** Insert Question ***//
				$strSQL = "INSERT INTO ".$fix."webboard ";
				$strSQL .="(CreateDate,Question,Details,Name,New,Avatar) ";
				$strSQL .="VALUES ";
				$strSQL .="('".date("Y-m-d H:i:s")."','".$Question."','".$Details."','".$Name."','1','".$AvatarPic."')";
				$objQuery = mysqli_query($connection,$strSQL);
			}  else {
				echo "<script language=javascript>sweetAlert('คำเตือน','ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด','error');</script>"; 
				echo "<div class='boxshadow boxred' align=center><h1>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1></div><br>";	
			}
		} else {
			echo "<script language=javascript>sweetAlert('คำเตือน','ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด','error');</script>"; 
			echo "<div class='boxshadow boxred' align=center><h1>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</h1></div><br>";	
		}
		
	}
	?>
	<center>
	<form action="new-question.php?Action=Save" method="post" name="frmMain" id="frmMain">
	<table class="mytables" width="738" border="0" cellpadding="4" cellspacing="0" bordercolor="#eeeeee">
	<tr>
		<td colspan=2>หัวข้อ/เรื่อง: <input name="txtQuestion" type="text" class="tblogin" id="txtQuestion" value="" size="50"></td>
    </tr>
    <tr>
      <td colspan=2><textarea name="txtDetails" cols="95" rows="15" id="txtDetails"></textarea></td>
    </tr>
    <tr>
      <td>ชื่อ:<br><input name="txtName" type="text" class="tblogin" id="txtName" value="<?=$_SESSION['member']['name']?>"  size="50"></td>
      <td align=center>กรุณากรอกรหัส 4 ตัว <table cellspacing=1 cellpadding=0><tr><td bgcolor=red><img src="img.php"></td><td><input type=text class="tblogin" size=8 name=b5></td></tr></table></td>
    </tr>	
	</table>
	<br>
	<center><input name="btnSave" type="submit" id="btnSave" class="myButton" value="Submit"></center>
	</form>
	</center>
	
	<!--
	<script src="ckeditor/ckeditor.js"></script>
    <script>
            CKEDITOR.replace("txtDetails");
    </script>
	-->

	<script src="js/nicEdit.js" type="text/javascript"></script>
	<script type="text/javascript">
	bkLib.onDomLoaded(function() {
		new nicEditor({fullPanel : true}).panelInstance('txtDetails');
	});
	</script>

<?php

themefoot();
mysqli_close($connection);
exit;
		
?>
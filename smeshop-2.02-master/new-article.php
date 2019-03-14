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

if(!$_SESSION["admin"]) { session_destroy(); echo "<center><h2>Permission Denied. Please login as admin first.<br><a href=index.php>Go back</a></h2></center>"; exit; }


themehead("เขียนบทความใหม่");

	echo "<div class=\"boxshadow article\"><h2>เขียนบทความใหม่</h2><a class=\"bb\" href=\"article.php\"><b><i class='fa fa-eye'></i> บทความทั้งหมด</b></a><br><br></div><br>";
	
	if($_SESSION['admin']) { echo "<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถเขียน/แก้ไข/ลบ บทความ</b> | <a href=backshopoffice.php>กลับไปที่หลังร้าน</a></div><br>"; }
	
	$Article = $_POST[ 'txtQuestion' ];
	$Article = stripslashes( $Article );
	$Article = mysqli_real_escape_string($connection, $Article );
	
	$Details = $_POST[ 'txtDetails' ];
	$Details = stripslashes( $Details );
	$Details = mysqli_real_escape_string($connection, $Details );
	
	$Name = $_POST[ 'txtName' ];
	$Name = stripslashes( $Name );
	$Name = mysqli_real_escape_string($connection, $Name );

	if($_GET["Action"] == "Save") 
	{
		if((trim($_POST["txtQuestion"]))&&(trim($_POST["txtDetails"]))&&(trim($_POST["txtName"]))&&(trim($_POST["b5"]))) 
		{
			/* Check Spam */
			$send=1;
			//if(!preg_match('/$_SERVER["HTTP_HOST"]/',$HTTP_REFERER)){ unset($send); }
			if (!preg_match ('/' . $_SERVER['HTTP_HOST'] . '/i', parse_url ($_SERVER['HTTP_REFERER'], PHP_URL_HOST))) { unset($send); }
			if(!$_SESSION["nspam"]){ unset($send); }
			if($_POST["b5"]!=$_SESSION["nspam"]){ unset($send);  }
			if($send){
				echo "<script language=javascript>sweetAlert('Good Job','บันทึกบทความใหม่เรียบร้อยแล้ว','success');</script>"; 
				echo "<center><font color=blue><h3><b>บันทึกบทความใหม่เรียบร้อยแล้ว</b></h3></font></center><br>";	

				$picturewidth = "742"; //ขนาดรูปภาพประกอบไม่ควรเกินไซต์นี้
				
				if($_FILES["pic"]["size"]>0)	  
				{ $pic=check_image($_FILES["pic"]["tmp_name"],$_FILES["pic"]["type"],"1",date("U")); 
						$pic = preg_replace("/none/","",$pic);
				} else {
						$pic = "no-thumbnail.jpg"; 
				}
				
				//*** Insert Question ***//
				$strSQL = "INSERT INTO ".$fix."article ";
				$strSQL .="(CreateDate,Article,Details,Name,Picture,New)";
				$strSQL .="VALUES ";
				$strSQL .="('".date("Y-m-d H:i:s")."','".$Article."','".$Details."','".$Name."','".$pic."','1')";
				$objQuery = mysqli_query($connection,$strSQL);
			}  else {
				echo "<script language=javascript>sweetAlert('คำเตือน','ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด','error');</script>"; 
				echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font></center><br>";	
			}
		} else {
			echo "<script language=javascript>sweetAlert('คำเตือน','ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด','error');</script>"; 
			echo "<center><font color=red><h3><b>ท่านกรอกข้อมูลไม่ครบถ้วน หรือ กรอกรหัสลับ ผิด</b></h3></font></center><br>";	
		}
		
	}
	?>
	<center>
	<form action="new-article.php?Action=Save" method="post" name="frmMain" id="frmMain" enctype="multipart/form-data">
	<table class="mytables" width="738" border="0" cellpadding="4" cellspacing="0" bordercolor="#eeeeee">
	<tr>
		<td colspan=2>หัวข้อ/เรื่อง: <input name="txtQuestion" type="text" class="tblogin" id="txtQuestion" value="" size="50"></td>
    </tr>
	<tr>
		<td colspan=2>ภาพประกอบ: <input type=file name="pic"></td>
	</tr>
    <tr>
      <td colspan=2 align=center><textarea name="txtDetails" cols="95" rows="15" id="txtDetails"></textarea></td>
    </tr>
    <tr>
      <td width="200">ชื่อ:<br><input name="txtName" type="text" class="tblogin" id="txtName" value="เจ้าของร้าน" size="50"></td>
      <td align=center>รหัสลับ: <font color=red size=2> กรุณากรอกรหัส 4 ตัว</font><br><table cellspacing=1 cellpadding=0><tr><td><img src="img.php"></td><td><input type=text class="tblogin" size=8 name=b5></td></tr></table></td>
    </tr>	
	</table>
	<br>
	<input name="btnSave" type="submit" id="btnSave" class="myButton" value="Submit">
	</form>
	</center>

	<script src="ckeditor/ckeditor.js"></script>
    <script>
            CKEDITOR.replace("txtDetails");
    </script>

	<!--
	<script src="js/nicEdit.js" type="text/javascript"></script>
	<script type="text/javascript">
	bkLib.onDomLoaded(function() {
		new nicEditor({fullPanel : true}).panelInstance('txtDetails');
	});
	</script>
	-->

<?php

themefoot();
mysqli_close($connection);
exit;

function check_image_ga($picture,$picture_type,$folder,$pn)
{
global $thumbwidth;

if(	($picture_type=="image/jpg") || 	
($picture_type=="image/jpeg") || 	
($picture_type=="image/pjpeg") ) 
{ 	
$width = getimagesize($picture);	
$picturename = $pn.".jpg";

                                  if($width[0]>=$width[1])
	                             {
                                  $dstH = (int) (($thumbwidth*$width[1])/$width[0]);
                                  $dstW = $thumbwidth;	
                                  }
                                  else
								 {
                                  $dstW = (int) (($thumbwidth*$width[0])/$width[1]);
                                  $dstH = $thumbwidth;	
								  }

if(function_exists('ImageCreateFromJPEG'))  makeThumb( $picture,"$folder/thumb_$picturename",$dstW,$dstH );
                                                                          else     copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
}
elseif( $picture_type=="image/gif" )
	{
$picturename = $pn.".gif";
copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
	}	
}

function check_image($picture,$picture_type,$act,$name)
{
global $picturewidth,$bannerwidth,$thumbwidth,$folder;
$width = getimagesize($picture);	
if( ($width[0]>$picturewidth) && ($act=="1") )
echo "<script language=javascript>sweetAlert('คำเตือน','ขนาดของ ภาพประกอบ ไม่ควรกว้างเกิน $picturewidth พิกเซล','error');</script>";
elseif( ($width[0]>$bannerwidth) && ($act=="2") )
echo "<script language=javascript>sweetAlert('คำเตือน','ขนาดของ ภาพประกอบ ไม่ควรกว้างเกิน $bannerwidth พิกเซล','error');</script>";
else{

if(	($picture_type=="image/jpg") || 
	($picture_type=="image/jpeg") || 
	($picture_type=="image/pjpeg")  ) { 
$picturename = $name.".jpg";

                                  if($width[0]>=$width[1])
	                             {
                                  $dstH = (int) (($thumbwidth*$width[1])/$width[0]);
                                  $dstW = $thumbwidth;	
                                  }
                                  else
								 {
                                  $dstW = (int) (($thumbwidth*$width[0])/$width[1]);
                                  $dstH = $thumbwidth;	
								  }
if(function_exists('ImageCreateFromJPEG'))  makeThumb( $picture,"$folder/thumb_$picturename",$dstW,$dstH );
                                                                          else     copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
}
elseif( $picture_type=="image/gif" )
	{
$picturename = $name.".gif";
copy($picture,"$folder/thumb_$picturename");
copy($picture,"$folder/$picturename");
	}
}
return $picturename;
}

function makeThumb( $scrFile, $dstFile, $dstW, $dstH )
{ 
		$im = ImageCreateFromJPEG( $scrFile );
		$srcW = ImageSX( $im );
		$srcH = ImageSY( $im );
		if(function_exists('ImageCreatetruecolor'))		$ni = ImageCreatetruecolor( $dstW, $dstH );
		                                                                        else       $ni = ImageCreate( $dstW, $dstH );
        ImageCopyResized( $ni, $im, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH );
		ImageJPEG( $ni, $dstFile, 99 );	    
}
		
?>
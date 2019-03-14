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

$timenow  = strtotime( "now" );


themehead("กระดานถามตอบปัญหา");

	echo "<div class=\"boxshadow webboard\" align=left><h2>ถาม-ตอบ ปัญหา</h2><a class=\"bb\" href=\"new-question.php\"><b><i class='fa fa-edit'></i> ตั้งคำถามใหม่</b></a><br><br></div><br>";

	if($_SESSION['admin']) { echo "<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถ ตั้งกระทู้ / ตอบ / ลบ Post /ลบ Reply ได้</b> | <a href=backshopoffice.php><i class='fa fa-arrow-circle-left'></i> กลับไปที่หลังร้าน</a></div><br>"; }

	$strSQL = "SELECT * FROM ".$fix."webboard ";
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$Num_Rows = mysqli_num_rows($objQuery);

	$Per_Page = 10;   // Per Page

	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($Num_Rows<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($Num_Rows % $Per_Page)==0)
	{
		$Num_Pages =($Num_Rows/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($Num_Rows/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$strSQL .=" order  by QuestionID DESC LIMIT $Page_Start , $Per_Page";
	$objQuery  = mysqli_query($connection,$strSQL);
	
	?>
	<table class="mytables" width="100%" cellpadding=4 cellspacing=0 bordercolor=#eeeeee border="1">
	<tr>
		<!--th> <div align="center">QID</div></th-->
		<th> <div align="center">เรื่อง</div></th>
		<th> <div align="center">โดย</div></th>
		<th> <div align="center">เมื่อ</div></th>
		<th> <div align="center">อ่าน</div></th>
		<th> <div align="center">ตอบ</div></th>
	</tr>
	<?
	while($objResult = mysqli_fetch_array($objQuery))
	{
			$Name = $objResult["Name"];
			$chkimg = substr($Name,-3);
			switch ($chkimg) {
					case "(M)" :
						 $Name = "<i class='fa fa-user'></i> คุณ".substr($Name,0,-3); 
						 break;
				    case "(S)" :
						$Name = "<i class='fa fa-user-plus'></i> คุณ".substr($Name,0,-3); 
						break;
				    default :
						$Name = "<i class='fa fa-user-secret'> คุณ".$Name; 
						break;								
			}
	?>
	<tr>
		<!--td><div align="center"><?=$objResult["QuestionID"];?></div></td-->
		<td><a href="view-webboard.php?QuestionID=<?=$objResult["QuestionID"];?>"><?=len2desc($objResult["Question"],50);?></a></td>
		<td><?=$Name;?></td>
		<td><div align="center"><?=thai_time($objResult['CreateDate']);?></div></td>
		<td align="right"><?=$objResult["View"];?></td>
		<td align="right"><?=$objResult["Reply"];?></td>
	</tr>
	<?
	}
	?>
	</table>
	<br>
	Total <?= $Num_Rows;?> Record : <?=$Num_Pages;?> Page :

	<?
	
	echo "<div id=\"container\">";
	echo "<div class=\"pagination\" align=\"center\">";

	if($Prev_Page)
	{
		echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page'><< Back</a> ";
	}

	for($i=1; $i<=$Num_Pages; $i++){
		if($i != $Page)
		{
			echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?Page=$i'>$i</a>";
		}
		else
		{
			echo "<b> $i </b>";
		}
	}
	if($Page!=$Num_Pages)
	{
		echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page'>Next>></a> ";
	}
	
	echo "</div></div>";
	
	if($_SESSION['admin']!="") {
	$strSQL = "UPDATE ".$fix."webboard ";
	$strSQL .="SET New = '0' ";
	$objQuery = mysqli_query($connection,$strSQL);	
	}
	
themefoot();
mysqli_close($connection);
exit;

		
?>

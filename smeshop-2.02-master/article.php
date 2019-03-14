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

$timenow  = strtotime( "now" );

themehead("บทความที่น่าสนใจ");	

echo "<div class=\"boxshadow article\"><h2>บทความที่น่าสนใจ</h2><a class=\"bb\" href=\"new-article.php\"><b><i class='fa fa-edit'></i> เขียนบทความใหม่ (เฉพาะเจ้าของร้าน)</b></a><br><br></div><br>";

	if($_SESSION['admin']) { echo "<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถเขียน/แก้ไข/ลบ บทความ ได้</b> | <a href=backshopoffice.php><i class='fa fa-arrow-circle-left'></i> กลับไปที่หลังร้าน</a></div><br>"; }

	$strSQL = "SELECT * FROM ".$fix."article";
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

	$strSQL .=" order  by CreateDate DESC LIMIT $Page_Start , $Per_Page";
	$objQuery  = mysqli_query($connection,$strSQL);

	echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"#eeeeee\" border=\"0\">";

	$i = 0;
	while($objResult = mysqli_fetch_array($objQuery))
	{
		$img[$i] = $objResult['Picture'];
		if($img[$i]=="") { $img[$i] = "nothumbnail.gif"; }

		echo "<tr><td colspan=\"3\"><hr class='style-two'></td></tr>";
		echo "<tr>
		<td><a href=view-article.php?ArticleID=".$objResult['ArticleID']."><img src=images/".$img[$i]." width=150 height=150 border=0></a></td>
		<td valign=\"top\"><div class=\"boxshadow boxlightblue\"><a href=view-article.php?ArticleID=".$objResult['ArticleID']."><b>".lendesc($objResult['Article'],50)."</b></a>&nbsp;&nbsp;&nbsp;
		<i class=\"fa fa-edit\"></i>&nbsp;".thai_time($objResult['CreateDate'])."&nbsp;&nbsp;&nbsp;<i class=\"fa fa-eye\"></i>&nbsp;".$objResult['View']."</div><br>"
		.len2desc($objResult['Details'],1000)."&nbsp;<a href=view-article.php?ArticleID=".$objResult['ArticleID'].">อ่านต่อ</a></td></tr>";

		$i++;
	}
	echo "<tr><td colspan=\"3\"><hr class='style-two'></td></tr>";
	echo "</table><br>";
	
	echo "<center>จำนวนทั้งสิ้น ".$Num_Rows." บทความ รวม ".$Num_Pages." หน้า</center>";
	
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
	
themefoot();
mysqli_close($connection);
exit;
		
?>

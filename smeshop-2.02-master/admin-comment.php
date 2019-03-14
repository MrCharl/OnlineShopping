<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
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

$timenow  = strtotime( "now" );

themehead("จัดการรีวิวสินค้า");	

echo "<div class=\"boxshadow boxlemon\" align=center><h2>จัดการคอมเม้นต์-คำถาม ใต้ภาพสินค้า/บทความ</h2></div><br>";

	if($_SESSION['admin']) { echo "<div class='boxshadow boxred' align=center><b>ขณะนี้ท่าน Login ในสถานะเจ้าของร้าน สามารถ อ่าน/ลบ คอมเม้นต์-คำถาม ได้</b> | <a href=backshopoffice.php><i class='fa fa-arrow-circle-left'></i> กลับไปที่หลังร้าน</a></div><br>"; }

	$strSQL = "SELECT * FROM ".$fix."reply where replytype='1'";
	$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
	$Num_Rows = mysql_num_rows($objQuery);

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

	$strSQL .=" order by CreateDate DESC LIMIT $Page_Start , $Per_Page";
	$objQuery  = mysql_query($strSQL);

	echo "<table class=\"mytables\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"#eeeeee\" border=\"0\">";

	$i = 0;
	while($objResult = mysql_fetch_array($objQuery))
	{
		$id = $objResult['QuestionID'];
		$strSQL = "SELECT title FROM ".$fix."catalog where idp='$id' and category NOT LIKE 'L%' ";
		$strQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		$product = mysql_fetch_array($strQuery);
		$title = $product['title'];
		$link = "catalog.php?idp=";
		
		if($title=="") {
			$strSQL = "SELECT Article FROM ".$fix."article where ArticleID ='$id' ";
			$strQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
			$article = mysql_fetch_array($strQuery);
			$title = $article['Article'];
			$link = "view-article.php?ArticleID=";
		}
		
		$new="";
		if($objResult['New'] =='1') { $new="<i class='boxshadow boxred-mini'> New! </i>" ;}
		
		echo "<tr><td colspan=\"5\"><hr class='style-two'></td></tr>";
		echo "<tr>
		<td valign=\"top\" width=20%><a href=$link".$objResult['QuestionID'].">".$title."</a></b></td>
		<td valign=\"top\" width=50%>".$objResult['Details']."</b> $new</td>
		<td valign=\"top\" width=10%><a href=$link".$objResult['QuestionID'].">ตอบคำถาม</a></td>
		<td valign=\"top\" width=15%>".$objResult['Name']."</b></td>
		<td width=5%><a href=add-del-comment.php?act=delreply&replyid=".$objResult['ReplyID']."&postid=".$objResult['QuestionID']."&url=admin-comment.php><img src=images/delete.gif></a></td>
		</tr>";

		$i++;
	}
	echo "<tr><td colspan=\"5\"><hr class='style-two'></td></tr>";
	echo "</table><br>";
	
	echo "<center>จำนวนทั้งสิ้น ".$Num_Rows." คอมเม้นต์ รวม ".$Num_Pages." หน้า</center>";
	
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
	
	$strSQL = "UPDATE ".$fix."reply ";
	$strSQL .="SET New = '0' ";
	$objQuery = mysql_query($strSQL);	
	
themefoot();
mysql_close($connection);
exit;
		
?>

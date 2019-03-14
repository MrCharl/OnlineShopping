<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include "config.php";
   $orderno = $_POST['orderno']; // get the orderno
if ($orderno=="")
 {
 // if the post value is empty it will not do anything
 }
 else
 { // if it's not empty, it will  query the database and outputs the message
    $query = "SELECT * FROM ".$fix."orders WHERE orderno ='$orderno' ";
	$result = mysqli_query($connection,$query);
	$num_rows = mysqli_num_rows($result);
	if($num_rows >0 )
	{
		echo "<font color=green>&nbsp;<i class='fa fa-check'></i> พบใบสั่งซื้อมีอยู่ในระบบ</font>";
	}
	else
	{
		echo "<font color=red>&nbsp;<i class='fa fa-remove'></i> ไม่มีใบสั่งซื้อเลขที่นี้อยู่ในระบบ</font>";
	}
}
?>
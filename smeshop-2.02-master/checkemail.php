<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include "config.php";
   $email = $_POST['email']; // get the email
if ($email=="")
 {
 // if the post value is empty it will not do anything
	echo "TEST";
 }
 else
 { // if it's not empty, it will  query the database and outputs the message
    $query = "SELECT * FROM ".$fix."member WHERE email ='$email'";
	$result = mysqli_query($connection,$query);
	$num_rows = mysqli_num_rows($result);
	if($num_rows >0 )
	{
		echo "<br><font color=red><i class='fa fa-remove'></i> มีผู้ใช้งานอีเมล์นี้แล้ว</font>";
	}
	else
	{
		echo "<br><font color=green><i class='fa fa-check'></i> สามารถใช้อีเมล์นี้ได้</font>";
	}
}
?>
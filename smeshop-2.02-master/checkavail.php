<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include "config.php";
   $username = $_POST['username']; // get the username
if ($username=="")
 {
 // if the post value is empty it will not do anything
 }
 else
 { // if it's not empty, it will  query the database and outputs the message
    $query = "SELECT * FROM ".$fix."member WHERE username ='$username'";
	$result = mysqli_query($connection,$query);
	$num_rows = mysqli_num_rows($result);
	if($num_rows >0 )
	{
		echo '<br><font color=red>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username Unavailable</font>';
	}
	else
	{
		echo '<br><font color=green>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username Available</font>';
	}
}
?>
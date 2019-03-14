<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include ("config.php");
include ("category.php");
include ("sbucategory.php");
include ("toplink.php");
include("function.php");

themehead("ขอขอบคุณ");
	
	echo "<table bgcolor='#ffffff' width='95%' border='0' cellspacing='2' cellpadding='2' align='center'>";
	echo "<tbody><tr><TD colspan='2' bgcolor='#8BBDDA' align='center'>";
	echo "<font color='#ffffff' class='shadowtext'><b>ได้รับข้อมูลการชำระค่าบริการจากบัญชี PaySbuy ของท่านเรียบร้อยแล้วค่ะ<b></font>";
	echo "</td></tr><tr><td>";
	echo "ขอขอบคุณ ที่ท่านกรุณาทำรายการชำระค่าบริการให้กับเรา ขณะนี้ทางทีมงานได้รับข้อมูลการชำระค่าบริการจากบัญชี PaySbuy ของท่านแล้ว ";
	echo "เมื่อตรวจสอบยอดเงินถูกต้อง เราจะรีบดำเนินการ จัดส่งสินค้าให้ท่านโดยเร็วที่สุด ท่านสามารถตรวจสอบการจัดส่งสินค้า ได้ที่เมนู ตรวจสอบการจัดส่ง ";
	echo "</p><br></td></tr></tbody></table><br>";

	
themefoot();
	
?>
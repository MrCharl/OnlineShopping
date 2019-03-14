<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include("config.php");
include ("shipping.php");

echo "<table cellspacing=0 cellpadding=2 width=100% border=0><tr><td valign=top>
<table width=100% cellspacing=0 cellpadding=4 border=0><tr><td valign=top><table width=100% cellspacing=0 cellpadding=4 border=1 bordercolor=#eeeeee>
<tr background=\"images/bgbb2.gif\"><td width=755 colspan=2><font color=#FF816E size=3><b>วิธีจัดส่งสินค้า</b></font> <br></td></tr>
<tr><td align=center bgcolor=#F8F8F6><img src=\"images/shipping.jpg\" border=0><br>
<table cellspacing=4 cellpadding=0 width=100% border=1 bgcolor=#cccccc bordercolor=#eeeeee><tr><td valign=top>
<center><table width=100% cellspacing=2 cellpadding=3 border=0 bgcolor=#ffffff><tr><td valign=top>
<table width=100% border=1 cellpadding=0 cellspacing=0  bordercolor=#eeeeee><tr  bgcolor=\"#5DBAE1\"><td><font color=white><b>วิธีจัดส่งสินค้า</b></font></td>
<td><font color=white><b>รายละเอียด</b></font></td><td><font color=white><b>ค่าบริการ</b></font></td></tr>";
$pmid = count($shippingmethod);
 if($pmid>0)	{    
    for($i=0; $i<$pmid; $i++) {
			echo "<tr><td>".($i+1).". ".$shippingmethod[$i][0]."</td><td>".$shippingmethod[$i][1]."</td><td align=right>".$shippingmethod[$i][2]." บาท </td></tr>";
	}
	echo "</table></table></center>";
	echo "</td></tr></table></td></tr></table></td></tr></table>";
}
	echo "</td></tr></table>";
?>
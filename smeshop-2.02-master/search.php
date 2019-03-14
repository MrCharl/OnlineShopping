<?php

/*####################################################
โปรแกรม: SMEweb เวอร์ชั่น: 1.4f
คือโปรแกรมบริหารเว็บไซต์ Content Manager System (CMS)
พัฒนาขึ้นมาจาก ภาษา PHP HTML และ JAVASCRIPT 
เป็นโปรแกรมเปิดเผย Source Code แจกจ่ายให้ใช้งานได้ฟรี โดยไม่มีค่าใช้จ่าย 
ท่านสามารถ เผยแพร่ ทำซ้ำ แก้ไข ดัดแปลง โปรแกรมนี้ได้ ภายใต้ข้อกำหนดและเงื่อนไข GPL 
ทางผู้พัฒนา จะไม่รับผิดชอบความเสียหายที่เกิดขึ้น จากโปรแกรมนี้ในทุกกรณี

GPL คืออะไร?
อ่านเอกสาร GPL ภาษาไทยได้ที่ http://developer.thai.net/gpl/
อ่านเอกสาร GPL ต้นฉบับได้ที่ http://www.gnu.org/copyleft/gpl.html

Copyright (C) 2007  Mr.Monsun Uthayanugul 
E-mail: admin@ebizzi.net  Homepage: http://www.ebizzi.net/
#####################################################*/

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

@mysqli_query($connectionion,"update ".$fix."user set counter=(counter+1) where userid='1' "); 
$keyword = trim($keyword);
$nofollow = "1";
themehead(""._LANG_37_2." $keyword");
if($keyword)
{
            //$keywordS=split(" ",$keyword);
			$keywordS=explode(" ",$keyword);
			$query1 = "select distinct(idp) from ".$fix."catalog where category NOT LIKE 'L%'  and "; 
            $query2 = "select distinct(mainid) from ".$fix."product where "; 

        for($i=0; $i<count($keywordS); $i++)
        {
          if($keywordS[$i]) 
			{
		        $query1 .= "  concat(title,story) like '%$keywordS[$i]%' or";
		        $query2 .= "  concat(title,pid) like '%$keywordS[$i]%' or";
			}
         }
 
                
		$query1 = substr($query1,0,strlen($query1)-3);
        $sql1 = mysqli_query($connection,$query1);
		while($arr = mysqli_fetch_array($sql1))
			
	         {
                $idfound[] = $arr[0];
	         } mysqli_free_result($sql1);
		

		if($idfound[0]=='') {
			$query2 = substr($query2,0,strlen($query2)-3);
			$sql2 = mysqli_query($connection,$query2);
		    while($arr1 = mysqli_fetch_array($sql2))
	         {
                $idfound[] = $arr1[0];
	         } mysqli_free_result($sql2);
		}



$list_page = $Sbb;
if (!$page) $page=1;
$NRow = count($idfound);
$rt = $NRow%$list_page;
	if($rt!=0) 		$totalpage = floor($NRow/$list_page)+1; 
	else  		    $totalpage = floor($NRow/$list_page); 	
	                    
$goto = ($page-1)*$list_page;


if($NRow > 0) {
	echo "<br><div class=\"boxshadow boxlemon\" align=center><h1>ผลการค้นหา: พบสินค้า $NRow รายการ ที่เกี่ยวข้อง</h1></div>";
} else {
	echo "<br><div class=\"boxshadow boxred\" align=center><h1>ผลการค้นหา: ไม่พบรายการสินค้า ที่เกี่ยวข้อง</h1></div><br><br>";
	show_reccommend_products();
}

echo "<table width=100% border=0><tr>";
		for($i=$goto; $i<($goto+$list_page); $i++)
        { 
		  	if($i%3==0) echo "</tr><tr>";
			if($idfound[$i]) 	searchresult($idfound[$i],$bb);
         }

		 echo "</tr></table>";
		 
echo "<hr size=1 color=\"#cccccc\"><div align=right class=catbox>Page: ";


if(($page-1)!=0) 
		{
                 echo "<a href=\"search.php?page=".($page-1)."&bb=$bb&keyword=$keyword\">&laquo;</a> ";
		}

for($i=1; $i<$page; $i++){	if( ($page-$i)<3) echo "<a href=\"search.php?page=$i&bb=$bb&keyword=$keyword\">$i</a> ";	}

     echo "<font color=red>$page</font> ";
for($i=($page+1); $i<=$totalpage; $i++){	if( ($i-$page)<3) echo "<a href=\"search.php?page=$i&bb=$bb&keyword=$keyword\">$i</a> ";	}
if( ($page+1)<=$totalpage ) echo "<a href=\"search.php?page=".($page+1)."&bb=$bb&keyword=$keyword\">&raquo;</a> ";
echo "</div>";



} //end if($keyword)
	else {
		echo "<div class=\"boxshadow boxred\" align=center><h1>กรุณากรอกคีย์เวิร์ด ที่ต้องการค้นหา</h1></div>";
	}
themefoot(); 
mysqli_close($connectionion);

function searchresult($id,$bb)
{ global $fix,$connection,$syscolor;

$sql = mysqli_query($connection,"SELECT idp,title,story,createon,category,picture FROM ".$fix."catalog where category NOT LIKE 'L%' and idp='$id'");
$arr = mysqli_fetch_array($sql,MYSQLI_ASSOC);

		$product = get_product_details_2($arr['idp']);
		$dept = get_catagory_details($arr['category']);
		$imarray = explode("@",$arr['picture']);
		$img = $imarray[0];
		
			
		echo "<td align=center>";
		echo "<table width=100% border=0 cellpadding=3 cellspacing=3 bgcolor=#ffffff><tr><td align=center>";	
		echo "<div class=\"box\">";
		echo "$j<br><a class=\"thumbnail\" href=catalog.php?idp=".$id.">
		<ul class=\"enlarge\"><li><img src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span></li></ul>
		<font color=#5dbae1><h3><i>".stripslashes($arr['title'])."</i></h3></font>
		รหัสสินค้า: ".$arr['pid']."<br>
		แผนก: <a href=view-products.php?category=".$arr['category'].">".$dept['category']."</a><br>";
		if($product['sale'] < $product['price']) {
			echo "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		echo "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		echo "<br><br>";
		checkincart_2($product['id']);		
		echo "<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";	
		echo "</div></td></tr></table>";
		echo "</td>";
	
}


?>
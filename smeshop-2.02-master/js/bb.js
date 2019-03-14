function checkbb(act,rep,warn)
{
if ( (!rep) && (act) &&  (document.bbpost.b1.value=="") )
{  alert(warn);  return false;  }
if (document.bbpost.b2.value=="")
{  	alert(warn);  return false; }
if (document.bbpost.b3.value=="")
{  	alert(warn);  return false; }
}

function searchbb(warn)
{
keyword = prompt(warn,'');
if(keyword) location='search.php?bb=1&keyword='+keyword;
}
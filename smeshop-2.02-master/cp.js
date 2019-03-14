function popupcolor(folder,dat)
{
newcolor = showModalDialog(folder+'/java/popups/select_color.html', '', 'resizable: no; help: no; status: no; scroll: no; ');
if(newcolor)
	{
document.alltext[dat].value = "#"+newcolor;
document.alltext[dat].style.background = "#"+newcolor;
	}
}
function checkstory()
{valid = true;
if (document.alltext.title.value.length == 0)
{ valid=false; alert('โปรดใส่ชื่อเรื่อง!');  return false;  }
if (document.alltext.story.value.length == 0)
{  valid=false; alert('โปรดใส่รายละเอียด!');  return false; }
if(valid==true) document.alltext.submit();
}
function jumpto(url)
{
	if(confirm('ลบรูปภาพที่เลือก?')==true)    location = url;
}
function opensite(URL)
{
window.open(URL,'mysite','');
}
function saveconf(){	valid = true;
if (document.alltext.c3.value == '' )          { valid=false;  alert('โปรดใส่ E-mail');             return false; }
if (document.alltext.c4.value == '' )          { valid=false;  alert('โปรดใส่ Domain');          return false; }
if (document.alltext.c5.value == '' )          { valid=false;  alert('โปรดใส่ Title');                 return false; }
if (document.alltext.c6.value == '' )          { valid=false;  alert('โปรดใส่ Description');   return false; }
if (document.alltext.co1.value == '' )        { valid=false;  alert('โปรดใส่ ค่าสี Color1');     return false; }
if (document.alltext.co2.value == '' )        { valid=false;  alert('โปรดใส่ ค่าสี Color2');     return false; }
if (document.alltext.co3.value == '' )        { valid=false;  alert('โปรดใส่ ค่าสี Color3');     return false; }
if (document.alltext.co4.value == '' )        { valid=false;  alert('โปรดใส่ ค่าสี Color4');     return false; }
if (document.alltext.co5.value == '' )        { valid=false;  alert('โปรดใส่ ค่าสี Color5');     return false; }
if (document.alltext.co6.value == '' )        { valid=false;  alert('โปรดใส่ ค่าสี Color6');     return false; }
if (valid==true)  document.alltext.submit(); }

function delimg(act,oldimg)
	{
if(confirm('ท่านต้องการลบ?')==true) location='?action=delimg&m='+act+'&oldimg='+oldimg;
	}

function delgallery(img)
	{
if(confirm('ลบรูปที่เลือก?')==true) location='?action=delgallery&img='+img;
	}

function addcat(act,url)
{  	    if(act==1)  text = 'เพิ่มหมวด: ใส่ชื่อหมวดลงในช่องด้านล่าง'; 
                else   text = 'แก้ไขหมวด: ใส่ชื่อใหม่ลงในช่องด้านล่าง'; 
catname = prompt(text,'');
if(catname) location = '?action='+url+'&category='+catname;
}


function category_act(act,cid)
{	
if(act==1)   addcat('2','update&id='+cid); 
if( (act==2) && (confirm('ลบหมวด และ เนื้อหาในหมวด?')==true) )  location = '?action=delcat&id='+cid; 
}
function mordr(act)
{
if(act==1) 
	{
       if(confirm('โปรดยืนยันการลบ')==true)  
		{
		   document.mord.action="?action=delord";
		   document.mord.submit();
		}
	}
else
	{
document.mord.action="download.php?cp=order";
document.mord.submit();
	}
}


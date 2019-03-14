/* --------------------------------------------- Check Member Username ---------------------------------*/

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
{ valid=false; sweetAlert('คำเตือน','โปรดใส่ชื่อสินค้า!','error');  return false;  }
if (document.alltext.story.value.length == 0)
{  valid=false; sweetAlert('คำเตือน','โปรดใส่รายละเอียดของสินค้า!','error');  return false; }
if(valid==true) document.alltext.submit();
}

function jumpto(url)
{
	//if(confirm('ลบรูปภาพที่เลือก?')==true)    location = url;
	
		title = 'คำเตือน'; text = 'ท่านต้องการลบภาพที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ลบภาพที่เลือกเรียบร้อยแล้ว", "success");
				location = url;
			} else {
				swal("Cancelled", "ยกเลิกการลบภาพแล้ว :)", "error");
			}
		});	
}


function opensite(URL)
{
window.open(URL,'mysite','');
}


function saveconf(){	valid = true;
if (document.alltext.c3.value == '' )          { valid=false;  sweetAlert('โปรดใส่ E-mail');             return false; }
if (document.alltext.c4.value == '' )          { valid=false;  sweetAlert('โปรดใส่ Domain');          return false; }
if (document.alltext.c5.value == '' )          { valid=false;  sweetAlert('โปรดใส่ Title');                 return false; }
if (document.alltext.c6.value == '' )          { valid=false;  sweetAlert('โปรดใส่ Description');   return false; }
if (document.alltext.co1.value == '' )        { valid=false;  sweetAlert('โปรดใส่ ค่าสี Color1');     return false; }
if (document.alltext.co2.value == '' )        { valid=false;  sweetAlert('โปรดใส่ ค่าสี Color2');     return false; }
if (document.alltext.co3.value == '' )        { valid=false;  sweetAlert('โปรดใส่ ค่าสี Color3');     return false; }
if (document.alltext.co4.value == '' )        { valid=false;  sweetAlert('โปรดใส่ ค่าสี Color4');     return false; }
if (document.alltext.co5.value == '' )        { valid=false;  sweetAlert('โปรดใส่ ค่าสี Color5');     return false; }
if (document.alltext.co6.value == '' )        { valid=false;  sweetAlert('โปรดใส่ ค่าสี Color6');     return false; }
if (valid==true)  document.alltext.submit(); }


function delimg(act,oldimg)
	{
		//if(confirm('ท่านต้องการลบ?')==true) location='?action=delimg&m='+act+'&oldimg='+oldimg;
		
		title = 'คำเตือน'; text = 'ท่านต้องการลบภาพนี้?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ลบภาพออกเรียบร้อยแล้ว", "success");
				location='?action=delimg&m='+act+'&oldimg='+oldimg;
			} else {
				swal("Cancelled", "ยกเลิกการลบภาพแล้ว :)", "error");
			}
		});				
		
	}


function delgallery(img)
	{
		
		//if(confirm('ลบรูปที่เลือก?')==true) location='?action=delgallery&img='+img;

		title = 'คำเตือน'; text = 'ท่านต้องการลบภาพที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ลบภาพที่เลือกเรียบร้อยแล้ว", "success");
				location='?action=delgallery&img='+img;
			} else {
				swal("Cancelled", "ยกเลิกการลบภาพแล้ว :)", "error");
			}
		});	
		
	}
	
function delgallery2(img)
	{
		
		//if(confirm('ลบรูปที่เลือก?')==true) location='?action=delgallery&img='+img;

		title = 'คำเตือน'; text = 'ท่านต้องการลบภาพที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ลบภาพที่เลือกเรียบร้อยแล้ว", "success");
				location='?action=delgallery2&img='+img;
			} else {
				swal("Cancelled", "ยกเลิกการลบภาพแล้ว :)", "error");
			}
		});	
		
	}
	
	

function addcat(act,url)
{ 

	title = 'เพิ่มแผนกสินค้าใหม่'; text = 'ใส่ชื่อแผนกลงในช่องด้านล่าง'; 

	catname = swal(title,text);

	swal({
		title: title,
		text: text,
		type: "input",
		showCancelButton: true, 
		closeOnConfirm: false, 
		inputPlaceholder: "กรุณากรอกชื่อแผนกสินค้าใหม่"
	},function (inputValue) {
	if (inputValue === false) return false;
	if (inputValue === "") {
		swal.showInputError("กรุณากรอกชื่อแผนกสินค้าใหม่!");
		return false
	}
	swal("เยี่ยม!", "เพิ่มแผนกสินค้า: " + inputValue + " เรียบร้อยแล้ว", "success");
    location = '?action='+url+'&category='+inputValue;
	});

}

function addsubcat(act,url)
{ 

	title = 'เพิ่มหมวดสินค้าใหม่'; text = 'ใส่ชื่อหมวดลงในช่องด้านล่าง'; 

	subcatname = swal(title,text);

	swal({
		title: title,
		text: text,
		type: "input",
		showCancelButton: true, 
		closeOnConfirm: false, 
		inputPlaceholder: "กรุณากรอกชื่อหมวดสินค้าใหม่"
	},function (inputValue) {
	if (inputValue === false) return false;
	if (inputValue === "") {
		swal.showInputError("กรุณากรอกชื่อหมวดสินค้าใหม่!");
		return false
	}
	swal("เยี่ยม!", "เพิ่มหมวดสินค้า: " + inputValue + " เรียบร้อยแล้ว", "success");
    location = '?action='+url+'&subcategory='+inputValue;
	});

}

function editcat(act,cid)
{  	   

title = 'แก้ไขชื่อแผนกสินค้า'; text = 'ใส่ชื่อใหม่ลงในช่องด้านล่าง'; 
//catname = swal(title,text);

	swal({
		title: title,
		text: text,
		type: "input",
		showCancelButton: true, 
		closeOnConfirm: false, 
		inputPlaceholder: "กรุณากรอกชื่อแผนกสินค้าใหม่"
	},function (inputValue) {
	if (inputValue === false) return false;
	if (inputValue === "") {
		swal.showInputError("กรุณากรอกชื่อแผนกสินค้าใหม่!");
		return false
	}
	swal("เยี่ยม!", "แก้ไขชื่อแผนกสินค้า: " + inputValue + " เรียบร้อยแล้ว", "success");
	location = '?action=update&id='+cid+'&category='+inputValue;
	});

}

function editsubcat(act,cid)
{  	   

title = 'แก้ไขชื่อหมวดสินค้า'; text = 'ใส่ชื่อใหม่ลงในช่องด้านล่าง'; 
//catname = swal(title,text);

	swal({
		title: title,
		text: text,
		type: "input",
		showCancelButton: true, 
		closeOnConfirm: false, 
		inputPlaceholder: "กรุณากรอกชื่อแผนกสินค้าใหม่"
	},function (inputValue) {
	if (inputValue === false) return false;
	if (inputValue === "") {
		swal.showInputError("กรุณากรอกชื่อแผนกสินค้าใหม่!");
		return false
	}
	swal("เยี่ยม!", "แก้ไขชื่อหมวดสินค้า: " + inputValue + " เรียบร้อยแล้ว", "success");
	location = '?action=updatesubcat&id='+cid+'&subcategory='+inputValue;
	});

}


function category_act(act,cid)
{	

	if(act==2) { 
		title = 'คำเตือน'; text = 'การลบแผนกจะลบสินค้าทุกชิ้นในแผนกด้วย'; 
		swal(title,text);
	}

	swal({
		title: title,
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "ใช่, ลบทั้งหมด",
		cancelButtonText: "ไม่, ยกเลิกการลบ",
		closeOnConfirm: false,
		closeOnCancel: false
		},
		function(isConfirm) {
		if (isConfirm) {
			swal("Deleted!", "ชื่อแผนกและสินค้าถูกลบออกเรียบร้อยแล้ว", "success");
			location = '?action=delcat&id='+cid; 
		} else {
			swal("Cancelled", "ยกเลิกการลบชื่อแผนกและสินค้าในแผนกแล้ว :)", "error");
		}
	});

}

function subcategory_act(act,cid)
{	

	if(act==2) { 
		title = 'คำเตือน'; text = 'ท่านแน่ใจที่จะลบชื่อหมวดสินค้า?'; 
		swal(title,text);
	}

	swal({
		title: title,
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "ใช่, ต้องการลบ",
		cancelButtonText: "ไม่, ยกเลิกการลบ",
		closeOnConfirm: false,
		closeOnCancel: false
		},
		function(isConfirm) {
		if (isConfirm) {
			swal("Deleted!", "ชื่อหมวดสินค้าถูกลบเรียบร้อยแล้ว", "success");
			location = '?action=delsubcat&id='+cid; 
		} else {
			swal("Cancelled", "ยกเลิกการลบชื่อหมวดสินค้า :)", "error");
		}
	});

}


function mordr(act,ordno)
{
if(act==1) 
	{
		
		title = 'คำเตือน'; text = 'ท่านต้องการลบใบสั่งซื้อที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ใบสั่งซื้อที่เลือกถูกลบออกเรียบร้อยแล้ว", "success");
				//document.mord.action="?action=delord";
				//document.mord.submit();
				location = '?action=delord&orderno='+ordno; 
			} else {
				swal("Cancelled", "ยกเลิกการลบใบสั่งซื้อแล้ว :)", "error");
			}
		});	
			
	}
}


function mordr2(act)
{
if(act==1) 
	{
	{
		
		title = 'คำเตือน'; text = 'ท่านต้องการลบใบสั่งซื้อที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ใบสั่งซื้อที่เลือกถูกลบออกเรียบร้อยแล้ว", "success");
				document.mainorder.action="?action=delselectedord";
				document.mainorder.submit();
			} else {
				swal("Cancelled", "ยกเลิกการลบใบสั่งซื้อแล้ว :)", "error");
			}
		});	
			
	}
	}
else
	{
document.mainorder.action="download-order.php?act=downloadselected";
document.mainorder.submit();
	}
}

function mordr3(act,transno)
{
if(act==1) 
	{
		
		title = 'คำเตือน'; text = 'ท่านต้องการลบข้อมูลแจ้งโอนเงินที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ข้อมูลแจ้งโอนเงินที่เลือกถูกลบออกเรียบร้อยแล้ว", "success");
				//document.mord.action="?action=delord";
				//document.mord.submit();
				location = '?action=delpayconfirm&transno='+transno; 
			} else {
				swal("Cancelled", "ยกเลิกการลบข้อมูลแจ้งโอนเงินแล้ว :)", "error");
			}
		});	
			
	}
}

function mordr4(act,contactid)
{
if(act==1) 
	{
		
		title = 'คำเตือน'; text = 'ท่านต้องการลบข้อมูลติดต่อสอบถามที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ข้อมูลติดต่อสอบถามที่เลือกถูกลบออกเรียบร้อยแล้ว", "success");
				//document.mord.action="?action=delord";
				//document.mord.submit();
				location = '?action=delcontactus&contactid='+contactid; 
			} else {
				swal("Cancelled", "ยกเลิกการลบข้อมูลติดต่อสอบถามแล้ว :)", "error");
			}
		});	
			
	}
}

function mordr5(act,orderno)
{
if(act==1) 
	{
	{
		
		title = 'คำเตือน'; text = 'ท่านต้องการลบใบสั่งซื้อที่เลือก?'; 
		swal(title,text);

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "ใช่, ต้องการลบ",
			cancelButtonText: "ไม่, ยกเลิกการลบ",
			closeOnConfirm: false,
			closeOnCancel: false
			},
			function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "ใบสั่งซื้อที่เลือกถูกลบออกเรียบร้อยแล้ว", "success");
				location ="backshopoffice.php?action=delord&orderno="+orderno;				
			} else {
				swal("Cancelled", "ยกเลิกการลบใบสั่งซื้อแล้ว :)", "error");
			}
		});	
			
	}
	}
else
	{
document.mainorder.action="download-order.php?act=downloadselected";
document.mainorder.submit();
	}
}


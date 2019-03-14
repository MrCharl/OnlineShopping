//********************************************** ส่วนตรวจสอบสถานะโดเมนเนม *******************************************

var httpRequest=false;

function checkorderno()
{

	var now=new Date();

	var ord = document.payconfirm.orderno.value;
	
	 if (ord == "") {
	   sweetAlert("พบข้อผิดพลาด !!","กรุณากรอก เลขที่ใบสั่งซื้อ ด้วยค่ะ","error");
	   document.payconfirm.orderno.focus();
	    return (false);
	 }

    var element = document.getElementById('wait');
    if (element) {
            element.style.display = "inline";
    }

	if(window.XMLHttpRequest)
		httpRequest=new XMLHttpRequest();
	else if(window.ActiveXObject)
		httpRequest=new ActiveXObject("Microsoft.XMLHTTP");
	this.httpRequest.onreadystatechange=function ()
	{
		if(httpRequest.readyState==4)
			if(httpRequest.status==200)
			{
				if(httpRequest.responseText==1)
				{
						sweetAlert("ขอแสดงความยินดี","ชื่อโดเมนเนมที่ท่านต้องการ ยังว่างอยู่ ท่านสามารถจดได้ค่ะ","success");
						var element = document.getElementById('wait');
						if (element) {
							element.style.display = "none";
						}
						return (true);
				}
				else
				{
						sweetAlert("ขอแสดงความเสียใจ","ชื่อโดเมนเนมที่ท่านต้องการ ถูกจดไปแล้ว กรุณาหาชื่อใหม่","warning");
						var element = document.getElementById('wait');
						if (element) {
							element.style.display = "none";
						}
						document.payconfirm.orderno.focus();
						return (false);
				}
			}
	}
	
	
	httpRequest.open('GET',document.payconfirm.goto_index.value+"checkorderno-2.php?time="+now.getTime()+"&orderno="+ord,true);
	httpRequest.send(null);
	
}
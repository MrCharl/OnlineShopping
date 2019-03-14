function popupimg(path,width,height,domain)
{
year = new Date();
imwin = window.open('','','toolbar=0,location=0,status=0,scrollbars=0,resizable=0,width='+width+',height='+height);
imwin.document.write("<title>Copyright&copy;"+year.getFullYear()+" "+domain+"</title><body topmargin=0 leftmargin=0 marginwidth=0 marginheight=0><img src="+path+" width="+width+" height="+height+"></BODY>");
}
function sharelink(url,title,domain) {
		document.write("<a href='https://facebook.com/sharer.php?u="+url+"&title="+title+"' onclick='window.open(this.href,\"\",\"width=500,height=500\");return false;'><img src=images/facebook.gif widt=24 height=24 valign=middle></a>&nbsp;");
		document.write("<a href='https://twitter.com/intent/tweet?url=" +url+ "&text="+title+"' onclick='window.open(this.href,\"\",\"width=500,height=300\");return false;'><img src=images/twitter.gif width=24 height=24 valign=middle></a>&nbsp;");
		document.write("<a href='https://plus.google.com/share?url="+url+"' onclick='window.open(this.href,\"\",\"width=500,height=300\");return false;'><img src=images/google-plus.gif width=24 height=24 valign=middle></a>&nbsp;");
		//document.write("<a href='http://line.me/R/msg/"+title +"/?"+url+"' onclick='window.open(this.href,\"\",\"width=500,height=300\");return false;'><img src=images/line.gif width=24 height=24 valign=middle></a>&nbsp;");
		document.write("<i class='fa fa-link'></i> <input type=text value='"+url+"'>");
}




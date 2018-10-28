<?php
$ip=getInvokeIP();
function getInvokeIP()
{
    /*
    *获取客户端IP地址
    */
    if (getenv("HTTP_CLIENT_IP") && getenv("HTTP_CLIENT_IP")!="" && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
    {
        $ip = getenv("HTTP_CLIENT_IP");
    }
    else if (getenv("HTTP_X_FORWARDED_FOR") && getenv("HTTP_X_FORWARDED_FOR")!="" && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
    {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    }
    else if (getenv("REMOTE_ADDR") && getenv("REMOTE_ADDR")!="" && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
    {
        $ip = getenv("REMOTE_ADDR");
    }
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] !="" && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    else
    {
        $ip = "unknown";
    }
    return $ip;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>龙卷风会员登陆</title>
<link rel="stylesheet" type="text/css" id="css" href="style/index.css" />
<script language="JavaScript" type="text/javascript" src="lib/jquery/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/clientindex.js"></script>
<SCRIPT src="js/clock.js" type=text/javascript></SCRIPT>
</head>
<body style="margin-left: 0px">
<table border="0" cellpadding="0" cellspacing="0" style="width: 420px; height:100px">
<tr>
    <td style="width: 220px">
    <div class="leftbox" style="margin-left:0px; width:220px; height:100px">
        <form action="#" method="post" onSubmit="return false;">
        <div class="userlogin">
             <dl><dt>用户名：</dt>
	             <dd><input id="Login_UserName" name="username" class="username" type="text" maxlength="20" /></dd>
	         </dl>
	         <dl><dt>密&nbsp;&nbsp;&nbsp;&nbsp;码：</dt>
	              <dd><input id="Login_UserPassword" name="password" class="password" type="password" maxlength="20" /></dd>
	         </dl>
	         <dl>
	 	        <dt><input id="Check_KeepLogin" type="checkbox"/></dt>
	 	        <dd>
	 		        记住登录状态&nbsp;&nbsp;&nbsp;&nbsp;
			        <a href="javascript:UserLogin();"><img border=0 src="images/submit.gif"/></a>
		        </dd>
	         </dl>
	         <dl style="margin-top:4px">
	 	        <dt></dt>
	             <dd><img src="images/nav_icon.gif" width="4" height="7" alt="" />&nbsp;
		 	        <A href="index.html"  target="_blank">龙卷风在线服务中心</a>
		         </dd>
	         </dl>
        </div>
        </form>
    </div>
    </td>
    <td style="width: 200px; vertical-align: top;">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 200px; height: 79px">
        <tr>
            <td style="width: 200px; height: 4px;">
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 25px">
                &nbsp;我 的&nbsp;&nbsp;IP：<span id="myip"><?=$ip?></span>
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 25px">
                &nbsp;我的积分：<span id="mycore">0</span>分
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 25px">
                &nbsp;我的余额：<span id="mymodey">0</span>元
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 25px">
                &nbsp;当前时间：<span id=clock></span>
            </td>
        </tr>
    </table>
    </td>
</tr>
</table>
<script type=text/javascript>
    var clock = new Clock();
    clock.display(document.getElementById("clock"));
</script> 
</body>
</html>
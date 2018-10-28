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
$date=date('Y年m月d日 H:i:s',time());
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>金宝客户端会员中心</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" href="style/style.css" />
<link rel="stylesheet" href="lib/jquery-ui/themes/base/ui.core.css" type="text/css" />
<link rel="stylesheet" href="lib/jquery-ui/themes/base/ui.theme.css" type="text/css" />
<link rel="stylesheet" href="lib/jquery-ui/themes/base/ui.slider.css" type="text/css" />
<script language="JavaScript" type="text/javascript" src="lib/jquery/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/jquery-ui/ui/ui.core.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/jquery-ui/ui/ui.slider.js"></script>
<script language="JavaScript" type="text/javascript" src="js/clientjs_loader.js?v=123"></script>
<style type="text/css">
    td{
	    font-family: "宋体";
	    font-size: 12px;
    }
    a{text-decoration: none;} /* 链接无下划线,有为underline */
    a:link {color: #333333;} /* 未访问的链接 */
    a:visited {color: #333333;} /* 已访问的链接 */
    a:hover{text-decoration: underline;color: #3372A2;} /* 鼠标在链接上 */
    a:active {color: #CC00CC;} /* 点击激活链接 */
</style>
</head>
<body style="margin-left: 0px">
<table border="0" cellpadding="0" cellspacing="0" style="width: 420px; height:100px; margin-top:8px">
<tr>
    <td style="width: 220px">
    <table border="0" cellpadding="0" cellspacing="0" style="margin-left:15px;width: 200px; height: 79px">
        <tr>
            <td style="width: 200px; height: 4px;">
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 24px">
                &nbsp;用户名：<span id="Span_User_Name"></span></td>
        </tr>
        <tr>
            <td style="width: 200px; height: 25px">
                &nbsp;我的积分：<span id="Span_User_Score"></span>分
        </tr>
        <tr>
            <td style="width: 200px; height: 25px">
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 24px">
            &nbsp;<img src="images/nav_icon.gif" width="4" height="7" alt="" /><A href="index.html" target="_blank">金宝在线服务中心</a></td>
        </tr>
    </table>
    </td>
    <td style="width: 200px; vertical-align: top;">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 200px; height: 79px">
        <tr>
            <td style="width: 200px; height: 4px;">
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 24px">
                &nbsp;我的&nbsp;&nbsp;IP：<span id="myip"><?=$ip?></span>
		&nbsp;&nbsp;<a id="A_User_Logout" style="color:blue;" target="_self" href="javascript:void(0)">退出</a>
            </td>
        </tr>
        <tr>
            
            <td style="width: 200px; height: 25px">
                &nbsp;我的余额：<span id="Span_User_Money"></span>
            </td>
        </tr>
        <tr>
            <td style="width: 200px; height: 24px">
		<!--&nbsp;登陆时间：<?=$date?>-->
                &nbsp;上次登陆：<span id="Span_User_Lasttime"></span>
            </td>
        </tr>
    </table>
    </td>
</tr>
</table>
    <div style="display:none">
	<iframe id="Iframe_Content" scrolling="no" frameborder=0 width="100%" height="auto" src=""></iframe>
    </div>
</body>
</html>
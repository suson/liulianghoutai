﻿$(function() {
    $.ajaxSetup({
        async: false,
        cache: false
    });
    $("#Div_Add_url").load("html/addurl.html");
    $("#Div_UrlControl").load("html/setflow.html");
    $("#Div_Manage_url").load("html/setoptimize.html");
    $("#Div_Start_Trustee").load("html/starttrustee.html");
    $("#Div_View_Trustee").load("html/viewtrustee.html");
    $("#Div_MoreTrustee").load("html/moretrustee.html");
    $("#Div_Start_Optimize").load("html/startoptimize.html");
    $("#Div_View_Optimize").load("html/viewoptimize.html");
    $("#Div_MoreOptimize").load("html/moreoptimize.html");
    $("#Div_User_Pay").load("html/paymoney.html");
    $("#Div_MoreSet").load("html/moreset.html");
    $.getScript("lib/sp-lib/md5.js");
    $.getScript("lib/sp-lib/extent.js");
    $.getScript("js/webapp.js");
    $.ajaxSetup({
        async: true
    });
    SPWebApp.WebMain();
});
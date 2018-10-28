var SPWebApp = {
    WebMain: function() {
        SPWebApp.InitEvent();
        SPWebApp.InitPage();
        SPWebApp.PreloadImg();
    },
    InitEvent: function() {
        SPWebApp.m_objIframe = $("#Iframe_Content");
        SPWebApp.m_objWndChild = window.frames["Iframe_Content"];
        SPWebApp.m_objSpanSet = $("#TR_UrlControl_Set").find("span");
        SPWebApp.m_objSpanValue = $("#TR_UrlControl_Value > td").not(":first-child").not(":last-child");
        $("#TD_Menu_URL").hover(SPWebApp.MenuOver, SPWebApp.MenuOut).click(SPWebApp.MenuClick);
        $("#TD_Menu_PAY").hover(SPWebApp.MenuOver, SPWebApp.MenuOut).click(SPWebApp.MenuClick);
        $("#TD_Menu_PSW").hover(SPWebApp.MenuOver, SPWebApp.MenuOut).click(SPWebApp.MenuClick);
        $("#A_PayMoney_Now").click(function() {
            $("#TD_Menu_PAY").trigger("click");
        });
        $("#A_User_Logout").click(SPWebApp.Logout);
        $("#Btn_Close_AddUrl").click(SPWebApp.CloseAddUrl);
        $("#Btn_Close_SetFlow").click(SPWebApp.CloseFlowLine);
        $("#Btn_Close_Optimize").click(SPWebApp.CloseOptimize);
        $("#Btn_Close_Trustee").click(SPWebApp.CloseTrustee);
        $("#Btn_Close_ViewTrustee").click(SPWebApp.CloseViewTrustee);
        $("#Btn_Close_OptimizeSvr").click(SPWebApp.CloseOptimizeSvr);
        $("#Btn_Close_ViewOptimize").click(SPWebApp.CloseViewOptimize);
        $("#Btn_Close_PayMoney").click(SPWebApp.ClosePayMoney);
        $("#Btn_Close_MoreTrustee").click(SPWebApp.CloseMoreTrustee);
        $("#Btn_Close_MoreOptimizeSvr").click(SPWebApp.CloseMoreOptimize);
        $("#Btn_Close_MoreSet").click(SPWebApp.CloseMoreSet);
        $("#Btn_Reset_PayMoney").click(SPWebApp.ClosePayMoney);
        $("#Btn_SetControl").click(SPWebApp.SetControlUrl);
        $("#Btn_ReSetControl").click(SPWebApp.ReSetControlUrl);
        $("#Btn_Reload_PayMoney").click(SPWebApp.PaymoneyFinished);
        $("#Manage_My_Submit").click(SPWebApp.SubmitOptimize);
        $("#MoreTrustee_Submit_Btn").click(SPWebApp.MoreSubmitTrustee);
        $("#MoreOptimize_Submit_Btn").click(SPWebApp.MoreSubmitOptimize);
        $("#Text_Add_Url").blur(SPWebApp.CheckAddUrl);
        $("#Text_Add_My_Site").blur(SPWebApp.CheckAddSite);
        $("#Btn_Submit_AddUrl").click(SPWebApp.SubmitAddUrl);
        $("#Btn_Submit_MoreSet").click(SPWebApp.SubmitMoreSet);
        $("#A_Pay_Money").click(SPWebApp.StartPayAction);
        $("#A_PopWnd_Help").click(SPWebApp.ShowOptimize);
        $("#A_Target_Help").click(SPWebApp.ShowOptimize);
        $("#A_FUrl_Help").click(SPWebApp.ShowOptimize);
        $("#A_Add_Trustee").click(SPWebApp.OpenAddTrustee);
        $(window).unload(SPWebApp.Unload);
    },
    InitPage: function() {
        $.extend($.ui.slider.defaults, {
            range: "min",
            animate: true,
            orientation: "vertical"
        });
        SPWebApp.m_objIframe.attr("src", "urlmgt.html");
    },
    Unload: function() {},
    PreloadImg: function() {},
    JustHeight: function() {
        var nHeight = document.body.scrollHeight - document.body.clientHeight;
        $("#Div_Back").height((nHeight >= 0) ? document.body.scrollHeight: document.body.clientHeight);
    },
    MenuClick: function() {
        var objSelf = $(this);
        if ("menuSelect" == objSelf.attr("class")) return;
        objSelf.attr("class", "menuSelect");
        switch ($(this).attr("vlaue")) {
        case "0":
            objSelf.next().attr("class", "menuUnSelect");
            objSelf.next().next().attr("class", "menuUnSelect");
            SPWebApp.m_objIframe.attr("src", "urlmgt.html");;
            break;
        case "1":
            objSelf.next().attr("class", "menuUnSelect");
            objSelf.prev().attr("class", "menuUnSelect");
            SPWebApp.m_objIframe.attr("src", "paymgt.html");
            break;
        case "2":
            objSelf.prev().prev().attr("class", "menuUnSelect");
            objSelf.prev().attr("class", "menuUnSelect");
            SPWebApp.m_objIframe.attr("src", "pswmgt.html");
            break;
        default:
            break;
        }
    },
    MenuOver: function() {
        if ("menuUnSelect" == $(this).attr("class")) {
            $(this).css("font-weight", "bolder");
        }
    },
    MenuOut: function() {
        if ("menuUnSelect" == $(this).attr("class")) {
            $(this).css("font-weight", "normal");
        }
    },
    LoadUserInfo: function() {
        $("#Span_User_Level").html(g_userLevel[SPWebApp.m_objUser.level]);
        $("#Span_User_Score").html(SPWebApp.m_objUser.score);
        $("#Span_User_Money").html(formatCurrency((SPWebApp.m_objUser.money / 100), 2) + " 元");
        $("#Span_User_Lasttime").html(SPWebApp.m_objUser.ltime);
    },
    OpenBack: function() {
        var nHeight = document.body.scrollHeight - document.body.clientHeight;
        $("#Div_Back").height((nHeight >= 0) ? document.body.scrollHeight: document.body.clientHeight).show();
    },
    CloseBack: function() {
        $("#Div_Back").hide();
    },
    OpenAddUrl: function() {
        var objDiv = $("#Div_Add_url");
        $("#TR_AddUrl_Start", objDiv).show().next().hide();
        $("#Text_Add_Url", objDiv).val("http://").select().focus().parent().next().css("color", "#818384").html("网址必须以http(s)://开头");
        $("#Text_Add_My_Site", objDiv).val("网址" + (SPWebApp.m_objWndChild.SPUrlMgt.m_objOnline.totalurl + 1)).parent().next().css("color", "#818384").html("3-20个字符,不含特殊字符");
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        var objTemplate = $("#Select_Set_Template", objDiv);
        objTemplate.children().not(":first-child").remove();
        $.each(SPWebApp.m_objWndChild.SPUrlMgt.m_arrCBox,
        function(i, obj) {
            var objItem = $("<option></option)");
            var objData = SPWebApp.m_objWndChild.SPUrlMgt.m_arrCBox[i].data("objItem");
            objItem.attr("value", objData.urlid).html("编号:" + objData.urlid + " - 名称:" + objData.name);
            objTemplate.append(objItem);
        }); (null != Cookies.get("template")) ? objTemplate.val(Cookies.get("template")) : objTemplate.val("0");
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    CloseAddUrl: function() {
        $("#Div_Add_url").hide();
        SPWebApp.CloseBack();
    },
    CheckAddUrl: function() {
        var jObj = $("#Text_Add_Url");
        var strUrl = jObj.val();
        if (((0 != strUrl.indexOf("http://")) && (0 != strUrl.indexOf("https://"))) || (strUrl.length < 11)) {
            jObj.parent().next().css("color", "red").html("错误,网址必须以http(s)://开头");
            return false;
        } else {
            jObj.parent().next().css("color", "green").html("网址有效");
            return true;
        }
    },
    CheckAddSite: function() {
        var jObj = $("#Text_Add_My_Site");
        var nLength = jObj.val().length;
        if (nLength < 3 || nLength > 20 || contain(jObj.val(), "`~!@#$%^&*()+={}[]|;:',.<>/?\"\\")) {
            jObj.parent().next().css("color", "red").html("错误,3-20个字符,不含特殊字符");
            return false;
        } else {
            jObj.parent().next().css("color", "green").html("网站名称有效");
            return true;
        }
    },
    SubmitAddUrl: function() {
        if (!SPWebApp.CheckAddUrl()) {
            alert("'我的网址'无效");
            $("#Text_Add_Url").select().focus();
            return;
        }
        if (!SPWebApp.CheckAddSite()) {
            alert("'网站名称'无效");
            $("#Text_Add_My_Site").select().focus();
            return;
        }
        var objAdd = {};
        objAdd.url = $("#Text_Add_Url").val();
        objAdd.name = $("#Text_Add_My_Site").val();
        objAdd.tid = $("#Select_Set_Template").val();
        SPWebApp.m_objWndChild.SPUrlMgt.SubmitAddUrl(objAdd);
    },
    AddurlSucced: function(urlid) {
        var objDiv = $("#Div_Add_url");
        $("#Span_Add_Urlid", objDiv).html(urlid);
        $("#Span_Add_Urlid2", objDiv).html(urlid);
        $("#TR_AddUrl_Start", objDiv).hide().next().show();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    OpenFlowLine: function(urlid) {
        var objDiv = $("#Div_UrlControl");
        $("#Btn_SetControl", objDiv).data("urlid", urlid);
        $("#TR_SetFlowStart", objDiv).show().next().show();
        $("#TR_SetFlowEnd", objDiv).hide();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
        SPWebApp.GetControlUrl(urlid);
    },
    CloseFlowLine: function() {
        $("#Div_UrlControl").hide();
        SPWebApp.CloseBack();
        SPWebApp.DestroyControl();
    },
    GetControlUrl: function(id) {
        var objUrl = {};
        objUrl.urlid = id;
        $.post(g_webaction, {
            f: 26,
            i: String($.toJSON(objUrl))
        },
        function(json) {
            var jsonObj = $.evalJSON(json);
            var sliderValues = [];
            if ("0" == jsonObj.error) {
                sliderValues = jsonObj.hrs;
            } else {
                sliderValues = [100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100];
            }
            SPWebApp.m_objSpanSet.each(function(i) {
                $(this).slider({
                    value: sliderValues[i],
                    slide: function(event, ui) {
                        SPWebApp.m_objSpanValue.eq($(this).attr("index")).text(ui.value);
                    }
                }).attr("index", i);
                SPWebApp.m_objSpanValue.eq(i).text(sliderValues[i]);
            });
        });
    },
    SetControlUrl: function() {
        var objUrl = {};
        objUrl.urlid = $("#Btn_SetControl").data("urlid");;
        objUrl.cid = 0;
        objUrl.hrs = [];
        for (var i = 0; i < SPWebApp.m_objSpanValue.length; i++) {
            objUrl.hrs.push(SPWebApp.m_objSpanValue.eq(i).text());
        }
        SPWebApp.m_objWndChild.SPUrlMgt.SubmitFlow($.toJSON(objUrl));
    },
    ReSetControlUrl: function() {
        SPWebApp.DestroyControl();
        SPWebApp.GetControlUrl($("#Btn_SetControl").data("urlid"));
    },
    SetFlowSucced: function() {
        var objDiv = $("#Div_UrlControl");
        $("#TR_SetFlowStart", objDiv).hide().next().hide();
        $("#TR_SetFlowEnd", objDiv).show();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    DestroyControl: function() {
        SPWebApp.m_objSpanSet.each(function(i) {
            $(this).slider('destroy');
        });
        SPWebApp.m_objSpanValue.each(function() {
            $(this).empty();
        });
    },
    OpenOptimize: function(objItem) {
        var objDiv = $("#Div_Manage_url");
        var objOptimize = SPWebApp.m_objWndChild.SPUrlMgt.GetOptimize(objItem);
        if ((undefined != objOptimize.odrid) && (0 != objOptimize.status)) {
            $("#A_PopWnd_Help", objDiv).hide();
            $("#A_Target_Help", objDiv).hide();
            $("#A_FUrl_Help", objDiv).hide();
        } else {
            $("#A_PopWnd_Help", objDiv).show();
            $("#A_Target_Help", objDiv).show();
            $("#A_FUrl_Help", objDiv).show();
        } {
            $("#Manage_My_Url", objDiv).parent().next().css("color", "#818384").html("网址必须以http(s)://开头");
            $("#Manage_My_Site", objDiv).parent().next().css("color", "#818384").html("3-20个字符,不含特殊字符");
            $("#Manage_My_PopPos", objDiv).next().css("color", "#818384").html("(范围：1%—100%)");
            $("#Manage_My_TUrlPos", objDiv).next().css("color", "#818384").html("(范围：1%—100%)");
            $("#Manage_My_AddFurl", objDiv).val("");
            if ((undefined != objOptimize.odrid) && (0 != objOptimize.status)) {
                $("#Manage_My_TargetUrl", objDiv).next().css("color", "#818384").html("(不限制目标网址格式)");
            } else {
                $("#Manage_My_TargetUrl", objDiv).next().css("color", "#818384").html("(网址必须以http(s)://开头)");
            }
        }
        $("#Manage_My_Url", objDiv).val(objItem.url).blur(SPWebApp.CheckModUrl);
        $("#Manage_My_Site", objDiv).val(objItem.name).blur(SPWebApp.CheckModUrlName);
        if ("0" == objItem.usepop) {
            $("#Manage_My_PopWnd_Fire", objDiv).removeAttr("checked");
        } else {
            $("#Manage_My_PopWnd_Fire", objDiv).attr("checked", "true");
        }
        if ((undefined != objOptimize.odrid) && (0 != objOptimize.status)) {
            $("#Manage_My_PopPos", objDiv).css("background-color", "#ffffff").removeAttr("disabled").val((parseInt(objItem.usepop) > 0) ? objItem.usepop: "5").blur(SPWebApp.CheckPopPos);
        } else {
            $("#Manage_My_PopPos", objDiv).css("background-color", "#d5d5d5").attr("disabled", "true").val("5").blur(SPWebApp.CheckPopPos);
        }
        if (parseInt(objItem.useturl) > 0) {
            $("#Manage_My_Target_Fire", objDiv).attr("checked", "true");
        } else {
            $("#Manage_My_Target_Fire", objDiv).removeAttr("checked");
        }
        $("#Manage_My_TargetUrl", objDiv).val(objItem.turl).blur(SPWebApp.CheckModTarget);
        if ((undefined != objOptimize.odrid) && (0 != objOptimize.status)) {
            var npos = (0x00ff & parseInt(objItem.useturl));
            $("#Manage_My_TUrlPos", objDiv).css("background-color", "#ffffff").removeAttr("disabled").val((npos > 0) ? npos: 1).blur(SPWebApp.CheckTUrlPos);
        } else {
            $("#Manage_My_TUrlPos", objDiv).css("background-color", "#d5d5d5").attr("disabled", "true").val("1").blur(SPWebApp.CheckTUrlPos);
        }
        if (0x0100 == (parseInt(objItem.useturl) & 0x0f00)) {
            $("#Manage_My_SearchUrl", objDiv).attr("checked", "true");
        } else {
            $("#Manage_My_SearchUrl", objDiv).removeAttr("checked");
        }
        if ("0" == objItem.usefurl) {
            $("#Manage_My_FUrl_Fire", objDiv).removeAttr("checked");
        } else {
            $("#Manage_My_FUrl_Fire", objDiv).attr("checked", "true");
        }
        if ((undefined != objOptimize.odrid) && (0 != objOptimize.status)) {
            $("#Span_SetFUrl_Number", objDiv).html(20);
        } else {
            $("#Span_SetFUrl_Number", objDiv).html(2);
        }
        var strFurls = objItem.furls;
        var arrFurl = strFurls.split("|");
        $("#Manage_My_FUrls", objDiv).children().remove();
        if (strFurls.length > 0) {
            for (var i = 0; i < arrFurl.length; i++) {
                var item = $("<option value='" + arrFurl[i] + "'>" + (i + 1) + "、" + arrFurl[i] + "</option>");
                $("#Manage_My_FUrls", objDiv).append(item);
            }
        }
        $("#Manage_Furl_Up", objDiv).unbind("click").click(SPWebApp.MoveToUp);
        $("#Manage_Furl_Down", objDiv).unbind("click").click(SPWebApp.MoveToDown);
        $("#Manage_Furl_Del", objDiv).unbind("click").click(SPWebApp.DeleteFurl);
        $("#Manage_AddFurl_Btn", objDiv).unbind("click").click(SPWebApp.AddFurlToList);
        $("#Manage_My_FUrls", objDiv).unbind("click").click(SPWebApp.SelectFurlList);
        $("#Manage_My_Submit").data("urlid", objItem.urlid).data("boptimize", ((undefined != objOptimize.odrid) && (0 != objOptimize.status)));
        $("#Manage_My_ClickSelf", objDiv).css("color", "red").html(objItem.clickself);
        $("#Manage_My_ClickOther", objDiv).css("color", "red").html(objItem.clickother);
        $("#Manage_My_LastTime", objDiv).css("color", "red").html(objItem.ltime);
        $("#Manage_My_AddTime", objDiv).css("color", "red").html(objItem.rtime);
        $("#TR_SetOptimize_Start", objDiv).show().next().hide();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
        $("#Manage_My_Url", objDiv).focus().select();
    },
    CloseOptimize: function() {
        $("#Div_Manage_url").hide();
        SPWebApp.CloseBack();
    },
    CheckModUrl: function() {
        var jObj = $("#Manage_My_Url");
        var strUrl = jObj.val();
        if (((0 != strUrl.indexOf("http://")) && (0 != strUrl.indexOf("https://"))) || (strUrl.length < 11)) {
            jObj.parent().next().css("color", "red").html("错误,网址必须以http(s)://开头");
            return false;
        } else {
            jObj.parent().next().css("color", "green").html("网址有效");
            return true;
        }
    },
    CheckModUrlName: function() {
        var jObj = $("#Manage_My_Site");
        var nLength = jObj.val().length;
        if (nLength < 3 || nLength > 20 || contain(jObj.val(), "`~!@#$%^&*()+={}[]|;:',.<>/?\"\\")) {
            jObj.parent().next().css("color", "red").html("错误,3-20个字符,不含特殊字符");
            return false;
        } else {
            jObj.parent().next().css("color", "green").html("网站名称有效");
            return true;
        }
    },
    CheckPopPos: function() {
        var jObj = $("#Manage_My_PopPos");
        var nPos = jObj.val();
        if (!isNaN(nPos) && ((nPos <= 0) || (nPos > 100))) {
            jObj.next().css("color", "red").html("(错误,比例范围：1%—100%)");
            return false;
        } else {
            jObj.next().css("color", "green").html("(弹窗比例有效)");
            return true;
        }
    },
    CheckModTarget: function() {
        var bOptimize = $("#Manage_My_Submit").data("boptimize");
        var jObj = $("#Manage_My_TargetUrl");
        var strUrl = jObj.val();
        if ((!bOptimize) && ((0 != strUrl.indexOf("http://")) && (0 != strUrl.indexOf("https://"))) && (strUrl.length > 0)) {
            jObj.next().css("color", "red").html("(错误,网址必须以http(s)://开头)");
            return false;
        } else {
            jObj.next().css("color", "green").html("(目标网址有效)");
            return true;
        }
    },
    CheckTUrlPos: function() {
        var jObj = $("#Manage_My_TUrlPos");
        var nPos = jObj.val();
        if (!isNaN(nPos) && ((nPos <= 0) || (nPos > 100))) {
            jObj.next().css("color", "red").html("(错误,比例范围：1%—100%)");
            return false;
        } else {
            jObj.next().css("color", "green").html("(访问比例有效)");
            return true;
        }
    },
    MoveToUp: function() {
        var objCurrent = $("#Manage_My_FUrls option:selected");
        var objPrev = objCurrent.prev();
        if (undefined == objPrev.val()) {
            return;
        }
        var objTemp = {};
        objTemp.html = objPrev.html();
        objTemp.val = objPrev.val();
        objPrev.html(objPrev.html().substring(0, objPrev.html().indexOf("、") + 1) + objCurrent.val());
        objPrev.val(objCurrent.val());
        objCurrent.html(objCurrent.html().substring(0, objCurrent.html().indexOf("、") + 1) + objTemp.val);
        objCurrent.val(objTemp.val);
        objPrev.attr("selected", "true");
    },
    MoveToDown: function() {
        var objCurrent = $("#Manage_My_FUrls option:selected");
        var objNext = objCurrent.next();
        if (undefined == objNext.val()) {
            return;
        }
        var objTemp = {};
        objTemp.html = objNext.html();
        objTemp.val = objNext.val();
        objNext.html(objNext.html().substring(0, objNext.html().indexOf("、") + 1) + objCurrent.val());
        objNext.val(objCurrent.val());
        objCurrent.html(objCurrent.html().substring(0, objCurrent.html().indexOf("、") + 1) + objTemp.val);
        objCurrent.val(objTemp.val);
        objNext.attr("selected", "true");
    },
    DeleteFurl: function() {
        var objCurrent = $("#Manage_My_FUrls option:selected");
        if (undefined == objCurrent.val()) {
            return;
        }
        if (!window.confirm("您确认要删除该来源网址吗？")) {
            return;
        }
        var allNext = objCurrent.nextAll();
        objCurrent.next().attr("selected", "true");
        objCurrent.remove();
        allNext.each(function() {
            $(this).html((parseInt($(this).html().substring(0, $(this).html().indexOf("、")) - 1) + "、" + $(this).val()));
        });
        $("#Manage_My_FUrlA").val("");
    },
    AddFurlToList: function() {
        var objSelect = $("#Manage_My_FUrls");
        var jObj = $("#Manage_My_AddFurl");
        var strUrl = jObj.val();
        if (0 == strUrl.length) {
            alert("请输入来源风址");
            jObj.focus();
            return;
        }
        if (((0 != strUrl.indexOf("http://")) && (0 != strUrl.indexOf("https://"))) || (strUrl.length < 11)) {
            alert("网址必须以http(s)://开头");
            jObj.focus().select();
            return;
        }
        if (strUrl.indexOf("|") >= 0) {
            alert("网址不能包含'|'符号");
            jObj.focus().select();
            return;
        }
        if ($("#Manage_My_Submit").data("boptimize")) {
            if (20 == objSelect.children().length) {
                alert("您的来源网址数量已经用完！");
                return;
            }
        } else {
            if (2 == objSelect.children().length) {
                alert("您未开通“流量优化服务”，目前最多只能设置2个来源网址！");
                return;
            }
        }
        var furl = jObj.val();
        var nLength = objSelect.children().length;
        var item = $("<option value='" + furl + "'>" + (nLength + 1) + "、" + furl + "</option>");
        objSelect.append(item);
        jObj.val("");
    },
    SelectFurlList: function() {
        var selectFUrl = $(this).val();
        if (null == selectFUrl) {
            return;
        }
        $("#Manage_My_AddFurl").val(selectFUrl);
    },
    SubmitOptimize: function() {
        var objDiv = $("#Div_Manage_url");
        var objMod = {};
        objMod.urlid = $(this).data("urlid");
        if (!SPWebApp.CheckModUrl()) {
            alert('"我的网址"无效');
            $("#Manage_My_Url", objDiv).focus().select();
            return false;
        }
        objMod.url = $("#Manage_My_Url", objDiv).val();
        if (!SPWebApp.CheckModUrlName()) {
            alert("'网站名称'无效");
            $("#Manage_My_Site", objDiv).focus().select();
            return false;
        }
        objMod.name = $("#Manage_My_Site").val();
        if (!SPWebApp.CheckPopPos()) {
            alert('"弹窗比例"无效');
            return;
        }
        if ($("#Manage_My_PopWnd_Fire", objDiv).attr("checked")) {
            if ($(this).data("boptimize")) {
                objMod.usepop = $("#Manage_My_PopPos", objDiv).val();
            } else {
                objMod.usepop = 1;
            }
        } else {
            objMod.usepop = 0;
        }
        if (!SPWebApp.CheckTUrlPos()) {
            alert('"访问比例"无效');
            return;
        }
        if (!SPWebApp.CheckModTarget()) {
            alert('"目标网址"无效');
            $("#Manage_My_TargetUrl", objDiv).focus().select();
            return;
        }
        objMod.turl = $("#Manage_My_TargetUrl", objDiv).val();
        if ($("#Manage_My_Target_Fire", objDiv).attr("checked") && (objMod.turl.length > 0)) {
            if ($(this).data("boptimize")) {
                objMod.useturl = parseInt($("#Manage_My_TUrlPos", objDiv).val());
            } else {
                objMod.useturl = 1;
            }
            if ($("#Manage_My_SearchUrl", objDiv).attr("checked")) {
                objMod.useturl = parseInt(objMod.useturl) | 0x0100;
            } else {
                objMod.useturl = parseInt(objMod.useturl) | 0x0000;
            }
        } else {
            objMod.useturl = 0;
            $("#Manage_My_Target_Fire", objDiv).removeAttr("checked");
        }
        objMod.furls = "";
        var objFurls = $("#Manage_My_FUrls", objDiv).children();
        for (var i = 0; i < objFurls.length; i++) {
            objMod.furls += objFurls.eq(i).attr("value");
            if (i < (objFurls.length - 1)) {
                objMod.furls += "|";
            }
        }
        if ($("#Manage_My_FUrl_Fire").attr("checked") && (objMod.furls.length > 0)) {
            objMod.usefurl = 1;
        } else {
            objMod.usefurl = 0;
            $("#Manage_My_FUrl_Fire", objDiv).removeAttr("checked");
        }
        SPWebApp.m_objWndChild.SPUrlMgt.SubmitOptimize(objMod);
    },
    SetOptimizeSucced: function() {
        var objDiv = $("#Div_Manage_url");
        $("#TR_SetOptimize_Start", objDiv).hide().next().show();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    OpenTrustee: function(objItem, bContinue) {
        var objDiv = $("#Div_Start_Trustee");
        var objTrustee = SPWebApp.m_objWndChild.SPUrlMgt.GetTrustee(objItem);
        $("#TD_Trustee_Urlid", objDiv).html(objItem.urlid);
        $("#TD_Trustee_Name", objDiv).html(objItem.name);
        $("#TD_Trustee_Url", objDiv).html(objItem.url);
        $("#TD_Service_EndTime", objDiv).html("");
        $("#TD_Trust_PayMoney", objDiv).html("当前支付：<span style='color:red'>0.00</span> 元，共需支付：<span style='color:red'>0.00</span> 元，赠送天数：<span style='color:red'>0</span> 天");
        $("#Trustee_Server_IP", objDiv).unbind("change").change(function() {
            SPWebApp.CalculationPay(8);
        });
        $("#Trustee_Server_Date", objDiv).unbind("keyup").keyup(function() {
            SPWebApp.CalculationPay(8);
        });
        $("#Radio_Pay_Hand", objDiv).attr("checked", "true").unbind("click").click(function() {
            SPWebApp.CalculationPay(8);
        });
        $("#Radio_Pay_Hand_Day", objDiv).removeAttr("checked").unbind("click").click(function() {
            SPWebApp.CalculationPay(8);
        });
        $("#TR_Trustee_Start").show().next().hide();
        if (bContinue) {
            $("#Trustee_Server_IP", objDiv).attr("disabled", "true").css("background-color", "#d4d0c8").val(objTrustee.svcid);
            if ("1" == objTrustee.status) {
                $("#Radio_Pay_Hand_Day", objDiv).attr("checked", "true").unbind("click");
                $("#Radio_Pay_Hand", objDiv).attr("disabled", "true").removeAttr("checked").unbind("click");
            } else if ("2" == objTrustee.status) {
                $("#Radio_Pay_Hand", objDiv).attr("checked", "true").unbind("click");
                $("#Radio_Pay_Hand_Day", objDiv).attr("disabled", "true").removeAttr("checked").unbind("click");
            }
        } else {
            $("#Trustee_Server_IP", objDiv).css("background-color", "#ffffff").removeAttr("disabled").val(103);
            $("#Radio_Pay_Hand", objDiv).removeAttr("disabled");
            $("#Radio_Pay_Hand_Day", objDiv).removeAttr("disabled");
        }
        $("#Trustee_Submit_Btn", objDiv).data("urlid", objItem.urlid).data("odrid", (undefined != objTrustee.odrid) ? objTrustee.odrid: 0).data("etime", (undefined != objTrustee.etime) ? objTrustee.etime: 0);
        $("#Trustee_Submit_Btn", objDiv).click(SPWebApp.SubmitTrustee);
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
        $("#Trustee_Server_Date", objDiv).val("30").focus().select().keyup();
    },
    CloseTrustee: function() {
        $("#Div_Start_Trustee").hide();
        SPWebApp.CloseBack();
    },
    CalculationPay: function(svcid) {
        var objDiv = {};
        var nDate = 0;
        if (8 == svcid) {
            objDiv = $("#Div_Start_Trustee");
            nDate = $("#Trustee_Server_Date", objDiv).val();
        } else if (10 == svcid) {
            objDiv = $("#Div_Start_Optimize");
            nDate = $("#Optimize_Server_Date", objDiv).val();
        }
        if (isNaN(nDate) || (nDate < 1) || (nDate > 365) || (nDate > Math.floor(nDate))) {
            if (nDate > Math.floor(nDate)) {
                alert("服务时间不能包含小数");
            } else {
                alert("请输入有效的服务时间(1-365)");
            }
            if (8 == svcid) {
                $("#Trustee_Server_Date", objDiv).val("30").focus().select().keyup();
                $("#TD_Trust_PayMoney", objDiv).html("当前支付：<span style='color:red'>0.00</span> 元，共需支付：<span style='color:red'>0.00</span> 元，赠送天数：<span style='color:red'>0</span> 天");
            } else if (10 == svcid) {
                $("#Optimize_Server_Date", objDiv).val("30").focus().select().keyup();
                $("#TD_Optimize_PayMoney", objDiv).html("当前支付：<span style='color:red'>0.00</span> 元，共需支付：<span style='color:red'>0.00</span> 元，赠送天数：<span style='color:red'>0</span> 天");
            }
            return false;
        }
        var objCalculation = {};
        objCalculation.day = nDate;
        if (8 == svcid) {
            objCalculation.nowpay = $("#Radio_Pay_Hand", objDiv).attr("checked") ? "1": "0";
            objCalculation.odrid = $("#Trustee_Submit_Btn", objDiv).data("odrid");
            objCalculation.etime = $("#Trustee_Submit_Btn", objDiv).data("etime");
            objCalculation.svcid = $("#Trustee_Server_IP", objDiv).val();
        } else if (10 == svcid) {
            objCalculation.nowpay = $("#Radio_Optimize_Hand", objDiv).attr("checked") ? "1": "0";
            objCalculation.odrid = $("#Optimize_Submit_Btn", objDiv).data("odrid");
            objCalculation.etime = $("#Optimize_Submit_Btn", objDiv).data("etime");
            objCalculation.svcid = svcid;
        }
        g_PayMethod = objCalculation.nowpay;
        $.post(g_webaction, {
            f: 31,
            i: String($.toJSON(objCalculation))
        },
        function(json) {
            var jsonObj = $.evalJSON(json);
            if ("0" == jsonObj.error) {
                if (8 == svcid) {
                    $("#TD_Service_EndTime", objDiv).html(jsonObj.etime);
                    $("#TD_Trust_PayMoney", objDiv).html("当前支付：<span style='color:red'>" + formatCurrency(jsonObj.paymoney / 100) + "</span> 元，共需支付：<span style='color:red'>" + formatCurrency(jsonObj.allmoney / 100) + "</span> 元，赠送天数：<span style='color:red'>" + jsonObj.pday + "</span> 天");
                    $("#Trustee_Submit_Btn", objDiv).data("money", (jsonObj.paymoney));
                } else if (10 == svcid) {
                    $("#TD_Optimize_EndTime", objDiv).html(jsonObj.etime);
                    $("#TD_Optimize_PayMoney", objDiv).html("当前支付：<span style='color:red'>" + formatCurrency(jsonObj.paymoney / 100) + "</span> 元，共需支付：<span style='color:red'>" + formatCurrency(jsonObj.allmoney / 100) + "</span> 元，赠送天数：<span style='color:red'>" + jsonObj.pday + "</span> 天");
                    $("#Optimize_Submit_Btn", objDiv).data("money", (jsonObj.paymoney));
                }
            }
        });
        return true;
    },
    SubmitTrustee: function() {
        var objDiv = $("#Div_Start_Trustee");
        if (!SPWebApp.CalculationPay(8)) {
            return;
        }
        var paymoney = $("#Trustee_Submit_Btn", objDiv).data("money");
        if ((parseInt(paymoney) > parseInt(SPWebApp.m_objUser.money))) {
            if (window.confirm("您的余额不足，是否立即充值 ？")) {
                SPWebApp.CloseTrustee();
                window.scrollTo(0, 0);
                $("#TD_Menu_PAY").click();
                return;
            } else {
                return;
            }
        }
        $("#Trustee_Submit_Btn", objDiv).unbind("click");
        var objTrustee = {};
        objTrustee.urlid = $("#Trustee_Submit_Btn", objDiv).data("urlid");
        objTrustee.odrid = $("#Trustee_Submit_Btn", objDiv).data("odrid");
        objTrustee.etime = $("#Trustee_Submit_Btn", objDiv).data("etime");
        objTrustee.day = $("#Trustee_Server_Date", objDiv).val();
        objTrustee.nowpay = $("#Radio_Pay_Hand", objDiv).attr("checked") ? "1": "0";
        objTrustee.svcid = $("#Trustee_Server_IP", objDiv).val();
        SPWebApp.m_objWndChild.SPUrlMgt.SubmitTrustee(objTrustee);
    },
    TrusteeSucced: function(jsonObj) {
        var objDiv = $("#Div_Start_Trustee");
        var trRecord = $("#Table_Trustee_Succeed", objDiv).children("tr");
        trRecord.eq(0).children("td").eq(1).html(jsonObj.odrid);
        if ("1" == jsonObj.status) {
            trRecord.eq(1).children("td").eq(1).html("按天付费");
        } else if ("2" == jsonObj.status) {
            trRecord.eq(1).children("td").eq(1).html("一次性付费");
        } else {
            trRecord.eq(1).children("td").eq(1).html("无效的付费");
        }
        trRecord.eq(2).children("td").eq(1).html(formatCurrency(jsonObj.dayprice / 100) + " 元/天");
        trRecord.eq(3).children("td").eq(1).html(formatCurrency(jsonObj.paymoney / 100) + " 元");
        switch (parseInt(jsonObj.svcid)) {
        case 101:
            trRecord.eq(4).children("td").eq(1).html("1000IP");
            break;
        case 102:
            trRecord.eq(4).children("td").eq(1).html("2000IP");
            break;
        case 103:
            trRecord.eq(4).children("td").eq(1).html("3000IP");
            break;
        case 106:
            trRecord.eq(4).children("td").eq(1).html("6000IP");
            break;
        case 110:
            trRecord.eq(4).children("td").eq(1).html("10000IP");
            break;
        case 115:
            trRecord.eq(4).children("td").eq(1).html("15000IP");
            break;
        case 120:
            trRecord.eq(4).children("td").eq(1).html("20000IP");
            break;
        default:
            trRecord.eq(4).children("td").eq(1).html("3000IP");
            break;
        }
        trRecord.eq(5).children("td").eq(1).html(jsonObj.pday + " 天");
        trRecord.eq(6).children("td").eq(1).html(jsonObj.btime);
        trRecord.eq(7).children("td").eq(1).html(jsonObj.etime);
        $("#TR_Trustee_Start", objDiv).hide().next().show();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    OpenViewTrustee: function(objItem) {
        var objDiv = $("#Div_View_Trustee");
        var trRecord = $("#Table_View_Trustee", objDiv).children("tr");
        var objTrustee = SPWebApp.m_objWndChild.SPUrlMgt.GetTrustee(objItem);
        trRecord.eq(0).children("td").eq(1).html(objTrustee.odrid);
        if ("1" == objTrustee.status) {
            trRecord.eq(1).children("td").eq(1).html("按天付费");
            $("#TR_Trustee_Reback", objDiv).show();
            $("#Btn_Trustee_ReBack", objDiv).unbind("click").click(function() {
                SPWebApp.m_objWndChild.SPUrlMgt.TrustReback(true);
            });
        } else if ("2" == objTrustee.status) {
            trRecord.eq(1).children("td").eq(1).html("一次性付费");
            $("#TR_Trustee_Reback", objDiv).hide();
            $("#Btn_Trustee_ReBack", objDiv).removeData("objItem").unbind("click");
        } else {
            trRecord.eq(1).children("td").eq(1).html("无效的付费");
            $("#TR_Trustee_Reback", objDiv).hide();
            $("#Btn_Trustee_ReBack", objDiv).removeData("objItem").unbind("click");
        }
        trRecord.eq(2).children("td").eq(1).html(formatCurrency(objTrustee.price / 100) + " 元/天");
        switch (parseInt(objTrustee.svcid)) {
        case 101:
            trRecord.eq(3).children("td").eq(1).html("1000IP");
            break;
        case 102:
            trRecord.eq(3).children("td").eq(1).html("2000IP");
            break;
        case 103:
            trRecord.eq(3).children("td").eq(1).html("3000IP");
            break;
        case 106:
            trRecord.eq(3).children("td").eq(1).html("6000IP");
            break;
        case 110:
            trRecord.eq(3).children("td").eq(1).html("10000IP");
            break;
        case 115:
            trRecord.eq(3).children("td").eq(1).html("15000IP");
            break;
        case 120:
            trRecord.eq(3).children("td").eq(1).html("20000IP");
            break;
        default:
            trRecord.eq(3).children("td").eq(1).html("3000IP");
            break;
        }
        trRecord.eq(4).children("td").eq(1).html(objTrustee.btime);
        trRecord.eq(5).children("td").eq(1).html(objTrustee.etime);
        $("#TR_Trustee_EndView", objDiv).hide().prev().show();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    RebackTrusteeSucced: function(bDelete) {
        var objDiv = $("#Div_View_Trustee");
        $("#TR_Trustee_EndView", objDiv).show().prev().hide();
        if ("1" == bDelete) {
            $("#TD_Trust_Suc", objDiv).html("您的代挂服务已经正式结束，将不再扣费！");
        } else {
            $("#TD_Trust_Suc", objDiv).html("您的代挂服务将在凌晨正式结束，明日起将不再扣费！");
        }
    },
    CloseViewTrustee: function() {
        $("#Div_View_Trustee").hide();
        SPWebApp.CloseBack();
    },
    OpenOptimizeSvr: function(objItem, bContinue) {
        var objDiv = $("#Div_Start_Optimize");
        var objOptimize = SPWebApp.m_objWndChild.SPUrlMgt.GetOptimize(objItem);
        $("#TD_Optimize_Urlid", objDiv).html(objItem.urlid);
        $("#TD_Optimize_Name", objDiv).html(objItem.name);
        $("#TD_Optimize_Url", objDiv).html(objItem.url);
        $("#TD_Optimize_EndTime", objDiv).html("");
        $("#TD_Optimize_PayMoney", objDiv).html("当前支付：<span style='color:red'>0.00</span> 元，共需支付：<span style='color:red'>0.00</span> 元，赠送天数：<span style='color:red'>0</span> 天");
        $("#Optimize_Server_Date", objDiv).unbind("keyup").keyup(function() {
            SPWebApp.CalculationPay(10);
        });
        $("#Radio_Optimize_Hand", objDiv).attr("checked", "true").unbind("click").click(function() {
            SPWebApp.CalculationPay(10);
        });
        $("#Radio_Optimize_Hand_Day", objDiv).removeAttr("checked").unbind("click").click(function() {
            SPWebApp.CalculationPay(10);
        });
        $("#TR_Optimize_Start").show().next().hide();
        if (bContinue) {
            if ("1" == objOptimize.status) {
                $("#Radio_Optimize_Hand_Day", objDiv).attr("checked", "true").unbind("click");
                $("#Radio_Optimize_Hand", objDiv).attr("disabled", "true").removeAttr("checked").unbind("click");
            } else if ("2" == objOptimize.status) {
                $("#Radio_Optimize_Hand", objDiv).attr("checked", "true").unbind("click");
                $("#Radio_Optimize_Hand_Day", objDiv).attr("disabled", "true").removeAttr("checked").unbind("click");
            }
        } else {
            $("#Radio_Optimize_Hand", objDiv).removeAttr("disabled");
            $("#Radio_Optimize_Hand_Day", objDiv).removeAttr("disabled");
        }
        $("#Optimize_Submit_Btn", objDiv).data("urlid", objItem.urlid).data("odrid", (undefined != objOptimize.odrid) ? objOptimize.odrid: 0).data("etime", (undefined != objOptimize.etime) ? objOptimize.etime: 0);
        $("#Optimize_Submit_Btn", objDiv).click(SPWebApp.SubmitOptimizeSvr);
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
        $("#Optimize_Server_Date", objDiv).val("30").focus().select().keyup();
    },
    CloseOptimizeSvr: function() {
        $("#Div_Start_Optimize").hide();
        SPWebApp.CloseBack();
    },
    SubmitOptimizeSvr: function() {
        var objDiv = $("#Div_Start_Optimize");
        if (!SPWebApp.CalculationPay(10)) {
            return;
        }
        var paymoney = $("#Optimize_Submit_Btn", objDiv).data("money");
        if ((parseInt(paymoney) > parseInt(SPWebApp.m_objUser.money))) {
            if (window.confirm("您的余额不足，是否立即充值 ？")) {
                SPWebApp.CloseOptimizeSvr();
                window.scrollTo(0, 0);
                $("#TD_Menu_PAY").click();
                return;
            } else {
                return;
            }
        }
        $("#Optimize_Submit_Btn", objDiv).unbind("click");
        var objOptimize = {};
        objOptimize.urlid = $("#Optimize_Submit_Btn", objDiv).data("urlid");
        objOptimize.odrid = $("#Optimize_Submit_Btn", objDiv).data("odrid");
        objOptimize.etime = $("#Optimize_Submit_Btn", objDiv).data("etime");
        objOptimize.day = $("#Optimize_Server_Date", objDiv).val();
        objOptimize.nowpay = $("#Radio_Optimize_Hand", objDiv).attr("checked") ? "1": "0";
        objOptimize.svcid = 10;
        SPWebApp.m_objWndChild.SPUrlMgt.SubmitOptimizeSvr(objOptimize);
    },
    OptimizeSvrSucced: function(jsonObj) {
        var objDiv = $("#Div_Start_Optimize");
        var trRecord = $("#Table_Optimize_Succeed", objDiv).children("tr");
        trRecord.eq(0).children("td").eq(1).html(jsonObj.odrid);
        if ("1" == jsonObj.status) {
            trRecord.eq(1).children("td").eq(1).html("按天付费");
        } else if ("2" == jsonObj.status) {
            trRecord.eq(1).children("td").eq(1).html("一次性付费");
        } else {
            trRecord.eq(1).children("td").eq(1).html("无效的付费");
        }
        trRecord.eq(2).children("td").eq(1).html(formatCurrency(jsonObj.dayprice / 100) + " 元/天");
        trRecord.eq(3).children("td").eq(1).html(formatCurrency(jsonObj.paymoney / 100) + " 元");
        trRecord.eq(4).children("td").eq(1).html(jsonObj.pday + " 天");
        trRecord.eq(5).children("td").eq(1).html(jsonObj.btime);
        trRecord.eq(6).children("td").eq(1).html(jsonObj.etime);
        $("#TR_Optimize_Start", objDiv).hide().next().show();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    OpenViewOptimize: function(objItem) {
        var objDiv = $("#Div_View_Optimize");
        var trRecord = $("#Table_View_Optimize", objDiv).children("tr");
        var objOptimize = SPWebApp.m_objWndChild.SPUrlMgt.GetOptimize(objItem);
        trRecord.eq(0).children("td").eq(1).html(objOptimize.odrid);
        if ("1" == objOptimize.status) {
            trRecord.eq(1).children("td").eq(1).html("按天付费");
            $("#TR_Optimize_Reback", objDiv).show();
            $("#Btn_Optimize_ReBack", objDiv).unbind("click").click(function() {
                SPWebApp.m_objWndChild.SPUrlMgt.OptimizeReback(true);
            });
        } else if ("2" == objOptimize.status) {
            trRecord.eq(1).children("td").eq(1).html("一次性付费");
            $("#TR_Optimize_Reback", objDiv).hide();
            $("#Btn_Optimize_ReBack", objDiv).removeData("objItem").unbind("click");
        } else {
            trRecord.eq(1).children("td").eq(1).html("无效的付费");
            $("#TR_Optimize_Reback", objDiv).hide();
            $("#Btn_Optimize_ReBack", objDiv).removeData("objItem").unbind("click");
        }
        trRecord.eq(2).children("td").eq(1).html(formatCurrency(objOptimize.price / 100) + " 元/天");
        trRecord.eq(3).children("td").eq(1).html(objOptimize.btime);
        trRecord.eq(4).children("td").eq(1).html(objOptimize.etime);
        $("#TR_Optinize_EndView", objDiv).hide().prev().show();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    RebackOptimizeSucced: function(bDelete) {
        var objDiv = $("#Div_View_Optimize");
        $("#TR_Optinize_EndView", objDiv).show().prev().hide();
        if ("1" == bDelete) {
            $("#TD_Optimize_Suc", objDiv).html("您的优化服务已经正式结束，将不再扣费！");
        } else {
            $("#TD_Optimize_Suc", objDiv).html("您的优化服务将在凌晨正式结束，明日起将不再扣费！");
        }
    },
    CloseViewOptimize: function() {
        $("#Div_View_Optimize").hide();
        SPWebApp.CloseBack();
    },
    OpenPayMoney: function(jsonObj, nMoney) {
        var objDiv = $("#Div_User_Pay");
        $("#Span_Pay_Money", objDiv).html(formatCurrency(nMoney, 2));
        $("#Span_Feed_Money", objDiv).html(formatCurrency(parseFloat(jsonObj.pm / 100.00)));
        $("#Span_Reward_Money", objDiv).html(formatCurrency(parseFloat(jsonObj.lm) / 100.00));
        $("#Span_All_Money", objDiv).html(formatCurrency(parseFloat(nMoney) + (jsonObj.pm / 100.00) + (jsonObj.lm / 100.00)));
        $("#Span_To_Score", objDiv).html(parseInt(jsonObj.to_score));
        $("#Span_To_Level", objDiv).html(g_userLevel[jsonObj.to_level]);
        $("#A_Pay_Money", objDiv).attr("href", jsonObj.aplink);
        $("#TR_Start_PayMoney", objDiv).show().next().hide();
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    ClosePayMoney: function() {
        $("#Div_User_Pay").hide();
        SPWebApp.CloseBack();
    },
    StartPayAction: function() {
        setTimeout(function() {
            $("#TR_Start_PayMoney").hide().next().show();
        },
        2000);
    },
    Logout: function() {
        if (window.confirm("您确认要注销登录吗？")) {
            $.post(g_webaction, {
                f: "8"
            },
            function(json) {
                Cookies.clear("keep");
                Cookies.clear("user");
                Cookies.clear("password");
                window.document.location = "index.html";
            });
        }
    },
    ShowOptimize: function() {
        $("#Div_Manage_url").hide();
        SPWebApp.m_objWndChild.SPUrlMgt.ShowOptimize();
    },
    PaymoneyFinished: function() {
        SPWebApp.ClosePayMoney();
        SPWebApp.m_objWndChild.location.reload();
    },
    OpenMoreTrustee: function() {
        var objDiv = $("#Div_MoreTrustee");
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        $("#Trustee_MoreServer_IP", objDiv).val("103");
        $("#Trustee_MoreServer_Date", objDiv).val(30);
        $("#Trustee_MoreServer_Date", objDiv).unbind("keyup").keyup(function() {
            SPWebApp.CheckMoreServiceDate(8);
        });
        $("#Radio_MorePay_Hand", objDiv).attr("checked", true);
        $("#Radio_MorePay_Hand_Day", objDiv).removeAttr("checked");
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    CloseMoreTrustee: function() {
        $("#Div_MoreTrustee").hide();
        SPWebApp.CloseBack();
    },
    MoreSubmitTrustee: function() {
        var objDiv = {};
        var objData = {};
        objDiv = $("#Div_MoreTrustee");
        objData.day = $("#Trustee_MoreServer_Date", objDiv).val();
        objData.nowpay = $("#Radio_MorePay_Hand", objDiv).attr("checked") ? "1": "0";
        objData.svcid = $("#Trustee_MoreServer_IP", objDiv).val();
        SPWebApp.CloseMoreTrustee();
        SPWebApp.m_objWndChild.SPUrlMgt.MoreSubmitTrustee(objData);
    },
    CheckMoreServiceDate: function(svcid) {
        var objDiv = {};
        var nDate = 0;
        if (8 == svcid) {
            objDiv = $("#Div_MoreTrustee");
            nDate = $("#Trustee_MoreServer_Date", objDiv).val();
        } else if (10 == svcid) {
            objDiv = $("#Div_MoreOptimize");
            nDate = $("#Optimize_MoreServer_Date", objDiv).val();
        }
        if (isNaN(nDate) || (nDate < 1) || (nDate > 365) || (nDate > Math.floor(nDate))) {
            if (nDate > Math.floor(nDate)) {
                alert("服务时间不能包含小数");
            } else {
                alert("请输入有效的服务时间(1-365)");
            }
            if (8 == svcid) {
                $("#Trustee_MoreServer_Date", objDiv).val(30);
            } else if (10 == svcid) {
                $("#Optimize_MoreServer_Date", objDiv).val(30);
            }
        }
    },
    OpenMoreOptimize: function() {
        var objDiv = $("#Div_MoreOptimize");
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        $("#Optimize_MoreServer_Date", objDiv).val(30);
        $("#Optimize_MoreServer_Date", objDiv).unbind("keyup").keyup(function() {
            SPWebApp.CheckMoreServiceDate(10);
        });
        $("#Radio_MoreOptimize_Hand", objDiv).attr("checked", true);
        $("#Radio_MoreOptimize_Hand_Day", objDiv).removeAttr("checked");
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    CloseMoreOptimize: function() {
        $("#Div_MoreOptimize").hide();
        SPWebApp.CloseBack();
    },
    MoreSubmitOptimize: function() {
        var objDiv = {};
        var objData = {};
        objDiv = $("#Div_MoreOptimize");
        objData.day = $("#Optimize_MoreServer_Date", objDiv).val();
        objData.nowpay = $("#Radio_MoreOptimize_Hand", objDiv).attr("checked") ? "1": "0";
        SPWebApp.CloseMoreOptimize();
        SPWebApp.m_objWndChild.SPUrlMgt.MoreSubmitOptimize(objData);
    },
    OpenAddTrustee: function() {
        $("#Div_Add_url").hide();
        var nIndex = SPWebApp.m_objWndChild.SPUrlMgt.m_arrCBox.length;
        SPWebApp.m_objWndChild.SPUrlMgt.m_objCBox = SPWebApp.m_objWndChild.SPUrlMgt.m_arrCBox[nIndex - 1];
        SPWebApp.OpenTrustee(SPWebApp.m_objWndChild.SPUrlMgt.m_objCBox.data("objItem"), false);
    },
    OpenMoreSet: function() {
        var objDiv = $("#Div_MoreSet");
        var nHeight = objDiv.height();
        var nWidth = objDiv.width();
        nHeight = (document.body.clientHeight - nHeight) / 2;
        nWidth = (document.body.clientWidth - nWidth) / 2;
        var nUrlCount = 0;
        var objTemplate = $("#Select_MoreSet_Template", objDiv);
        objTemplate.children().remove();
        $.each(SPWebApp.m_objWndChild.SPUrlMgt.m_arrCBox,
        function(i, obj) {
            var objItem = $("<option></option)");
            var objData = SPWebApp.m_objWndChild.SPUrlMgt.m_arrCBox[i].data("objItem");
            objItem.attr("value", objData.urlid).html("编号:" + objData.urlid + " - 名称:" + objData.name);
            objTemplate.append(objItem);
            if (obj.attr("checked")) {
                nUrlCount++;
            }
        }); (null != Cookies.get("template")) ? objTemplate.val(Cookies.get("template")) : Cookies.clear("template");
        $("#Span_MoreSet_Count", objDiv).html(nUrlCount);
        $("#Span_MoreSet_Counts", objDiv).html(nUrlCount);
        $("#TR_MoreSet_Start", objDiv).show().next().hide();
        objDiv.css({
            top: (document.body.scrollTop + nHeight),
            left: nWidth
        }).show();
    },
    CloseMoreSet: function() {
        $("#Div_MoreSet").hide();
        SPWebApp.CloseBack();
    },
    SubmitMoreSet: function() {
        var objDiv = $("#Div_MoreSet");
        var objMoreSet = {};
        objMoreSet.srcurlid = $("#Select_MoreSet_Template", objDiv).val();
        objMoreSet.descurlids = [];
        $.each(SPWebApp.m_objWndChild.SPUrlMgt.m_arrCBox,
        function(i, obj) {
            if (obj.attr("checked")) {
                objMoreSet.descurlids.push(obj.data("objItem").urlid);
            }
        });
        if ((null == objMoreSet.srcurlid) || (0 == objMoreSet.descurlids.length)) {
            SPWebApp.CloseMoreSet();
            return;
        }
        SPWebApp.m_objWndChild.SPUrlMgt.CloneSetInfo(objMoreSet);
    },
    MoreSetFinished: function(jsonObj) {
        if ("0" == jsonObj.error) {
            var objDiv = $("#Div_MoreSet");
            $("#TR_MoreSet_Start", objDiv).hide().next().show();
        } else {
            alert("批量设置失败,请重试！");
        }
    },
    m_objSpanSet: {},
    m_objSpanValue: {},
    m_objUser: {},
    m_objWndChild: {},
    m_objIframe: {}
};
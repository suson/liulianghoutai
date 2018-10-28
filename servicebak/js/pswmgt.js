$(function() {
    $.ajaxSetup({
        async: false
    });
    $.getScript("lib/sp-lib/md5.js");
    $.getScript("lib/sp-lib/extent.js");
    $.ajaxSetup({
        async: true
    });
    SPPswMgt.WebMain();
});
var SPPswMgt = {
    WebMain: function() {
        SPPswMgt.InitEvent();
        SPPswMgt.InitPage();
        SPPswMgt.PreloadImg();
    },
    InitEvent: function() {
        $("#Text_VerifyCode").focus(SPPswMgt.ModLoadImgCheck).blur(SPPswMgt.ModChecVerifyCode);
        $("#Img_VerifyCode").click(SPPswMgt.ModLoadImgCheck);
        $("#Btn_Submit_Pas").click(SPPswMgt.SubmitPassword);
        $("#Text_Pas_Curent").blur(SPPswMgt.ModCheckCurtPassword);
        $("#Text_Pas_New").blur(SPPswMgt.ModCheckPassword);
        $("#Text_Pas_News").blur(SPPswMgt.ModCheckNewPassword);
        SPPswMgt.JustHeight();
    },
    InitPage: function() {},
    Unload: function() {},
    PreloadImg: function() {},
    JustHeight: function() {
        parent.SPWebApp.m_objIframe.css("height", document.body.scrollHeight);
        parent.SPWebApp.JustHeight();
    },
    ModLoadImgCheck: function() {
        var objImg = {};
        objImg.clen = 4;
        $.post(g_webaction, {
            f: 1,
            i: String($.toJSON(objImg))
        },
        function(json) {
            var jsonObj = $.evalJSON(json);
            if (0 == jsonObj.error) {
                var imgObj = $("#Img_VerifyCode");
                imgObj.attr("src", g_webimage + "?c=" + jsonObj.ccmd5);
                imgObj.css("visibility", "visible");
                imgObj.attr("md5", String(jsonObj.ccmd5));
            }
        });
    },
    ModCheckCurtPassword: function() {
        var jObj = $("#Text_Pas_Curent");
        var nLength = jObj.val().length;
        if (nLength < 3 || nLength > 16) {
            jObj.parent().next().css("color", "red").html("密码无效");
            return false;
        } else {
            jObj.parent().next().css("color", "green").html("密码有效");
            return true;
        }
    },
    ModCheckPassword: function() {
        var jObj = $("#Text_Pas_New");
        var nLength = jObj.val().length;
        if (nLength < 3 || nLength > 16) {
            jObj.parent().next().css("color", "red").html("密码无效");
            return false;
        } else {
            jObj.parent().next().css("color", "green").html("密码有效");
            return true;
        }
    },
    ModCheckNewPassword: function() {
        var jObj1 = $("#Text_Pas_New");
        var jObj2 = $("#Text_Pas_News");
        if (jObj1.val() != jObj2.val()) {
            jObj2.parent().next().css("color", "red").html("新密码不一致");
            return false;
        } else {
            jObj2.parent().next().html("");
            return true;
        }
    },
    ModChecVerifyCode: function() {
        var jObj = $("#Text_VerifyCode");
        var nLength = jObj.val().length;
        if ((4 != nLength) || ($("#Img_VerifyCode").attr("md5")) != hex_md5(String(jObj.val()).toUpperCase())) {
            jObj.parent().next().css("color", "red").html("验证码输入错误");
            return false;
        } else {
            jObj.parent().next().css("color", "green").html("验证码有效");
            return true;
        }
    },
    SubmitPassword: function() {
        if (!SPPswMgt.ModCheckCurtPassword()) {
            alert("请输入有效的当前密码");
            $("#Text_Pas_Curent").focus().select();
            return;
        }
        if (!SPPswMgt.ModCheckNewPassword()) {
            alert("请输入有效的新密码");
            $("#Text_Pas_New").focus().select();
            return;
        }
        if (!SPPswMgt.ModCheckNewPassword()) {
            alert("请输入有新的确认密码");
            $("#Text_Pas_News").focus().select();
            return;
        }
        if (!SPPswMgt.ModChecVerifyCode()) {
            alert("'验证码'无效");
            $("#Text_VerifyCode").focus().select();
            return;
        }
        var objPas = {};
        objPas.oldpwd = $("#Text_Pas_Curent").val();
        objPas.newpwd = $("#Text_Pas_New").val();
        objPas.cc = $("#Text_VerifyCode").val();
        $.post(g_webaction, {
            f: 9,
            i: String($.toJSON(objPas))
        },
        function(json) {
            var jsonObj = $.evalJSON(json);
            if (0 == jsonObj.error) {
                $("#Text_Pas_Curent").val("");
                $("#Text_Pas_New").val("");
                $("#Text_Pas_News").val("");
                $("#Text_VerifyCode").val("");
                Cookies.clear("keep");
                Cookies.clear("user");
                Cookies.clear("password");
                alert("恭喜您，修改密码成功");
            } else {
                alert("修改密码失败，请检查填写信息是否正确");
                $("#Text_VerifyCode").val("");
            }
        });
    }
};
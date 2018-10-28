function CheckUser() {
    var jObj = $("#Reg_UserName");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 16 || contain(jObj.val(), "`~!@#$%^&*()+={}[]|;:',.<>/?\"\\")) {
        jObj.next().html("无效的用户名").css("color", "red").show();
        return false;
    }
    return CheckUserExist(jObj.val());
}
function CheckUserExist(strName) {
    var jsonObj = {};
    jsonObj.name = strName;
    $.post(g_webaction, {
        f: 3,
        i: String($.toJSON(jsonObj))
    },
    function(json) {
        var jsonObj = $.evalJSON(json);
        if (0 == jsonObj.error) {
            if (1 == jsonObj.good) {
                var jObj = $("#Reg_UserName");
                jObj.next().html("恭喜您，该用户名可用").css("color", "#0080c0").show();
            } else if (0 == jsonObj.good) {
                var jObj = $("#Reg_UserName");
                jObj.next().html("该用户名已经注册").css("color", "red").show();
            } else {
                var jObj = $("#Reg_UserName");
                jObj.next().html("该用户名不合法").css("color", "red").show();
            }
        } else {
            var jObj = $("#Reg_UserName");
            jObj.next().html("检查用户名是否注册失败").css("color", "red").show();
        }
    });
    return true;
}
function CheckLoginPassword() {
    var jObj = $("#Reg_UserPassword");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 16) {
        jObj.next().show();
        return false;
    } else {
        jObj.next().hide();
    }
    return true;
}
function CheckConfirmPassword() {
    var jObj1 = $("#Reg_UserPassword");
    var jObj2 = $("#Reg_UserPassword_Confirm");
    if (jObj1.val() != jObj2.val()) {
        jObj2.next().show();
        return false;
    } else {
        jObj2.next().hide();
    }
    return true;
}
function isEmail(email) {
    var srt = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    return srt.test(email);
}
function CheckEMail() {
    var jObj = $("#Reg_UserEMail");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 50 || !isEmail(jObj.val())) {
        jObj.next().show();
        return false;
    } else {
        jObj.next().hide();
    }
    return true;
}
function CheckResetPasswordQuestion() {
    var jObj = $("#Reg_UserQuestion");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 20) {
        jObj.next().show();
        return false;
    } else {
        jObj.next().hide();
    }
    return true;
}
function CheckResetPasswordAnswer() {
    var jObj = $("#Reg_UserAnswer");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 20) {
        jObj.next().show();
        return false;
    } else {
        jObj.next().hide();
    }
    return true;
}
function ChecVerifyCode() {
    var jObj = $("#Reg_VerifyCode");
    var nLength = jObj.val().length;
	//hex_md5(String(jObj.val()).toUpperCase())
    if ((6 != nLength) || ($("#Reg_ImgCode").attr("md5")) != hex_md5(String(jObj.val()).toUpperCase())) {
        jObj.next().next().show();
        return false;
    } else {
        jObj.next().next().hide();
    }
    return true;
}
function LoadImgCheck() {
    $.post(g_webaction, {
        f: 1
    },
    function(json) {
        var jsonObj = $.evalJSON(json);
        if (0 == jsonObj.error) {
            var imgObj = $("#Reg_ImgCode");
            imgObj.attr("src", g_webimage + "?c=" + jsonObj.ccmd5);
            imgObj.css("visibility", "visible");
            imgObj.attr("md5", String(jsonObj.ccmd5));
        }
    });
}
function PasCheckUser() {
    var jObj = $("#Pas_UserName");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 16 || contain(jObj.val(), "`~!@#$%^&*()+={}[]|;:',.<>/?\"\\")) {
        jObj.next().html("无效的用户名").css("color", "red").show();
        return false;
    }
    return PasCheckUserExist(jObj.val());
}
function PasCheckUserExist(strName) {
    var jsonObj = {};
    jsonObj.name = strName;
    $.post(g_webaction, {
        f: 3,
        i: String($.toJSON(jsonObj))
    },
    function(json) {
        var jsonObj = $.evalJSON(json);
        if (0 == jsonObj.error) {
            if (1 == jsonObj.good) {
                var jObj = $("#Pas_UserName");
                jObj.next().html("该用户名未注册").css("color", "red").show();
            } else if (0 == jsonObj.good) {
                var jObj = $("#Pas_UserName");
                jObj.next().html("用户名有效").css("color", "#0080c0").show();
            } else {
                var jObj = $("#Pas_UserName");
                jObj.next().html("该用户名不合法").css("color", "red").show();
            }
        }
    });
    return true;
}
function PasCheckEMail() {
    var jObj = $("#Pas_UserEMail");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 50 || !isEmail(jObj.val())) {
        jObj.next().css("color", "red").html("无效的邮箱地址").show();
        return false;
    }
    return PasGetQuestion();
}
function PasGetQuestion() {
    var objUser = {};
    objUser.name = $("#Pas_UserName").val();
    objUser.email = $("#Pas_UserEMail").val();
    $.post(g_webaction, {
        f: 5,
        i: String($.toJSON(objUser))
    },
    function(json) {
        var jsonObj = $.evalJSON(json);
        if (0 == jsonObj.error) {
            $("#Pas_UserEMail").next().css("color", "#0080c0").html("邮箱地址有效").show();
            $("#Span_Pas_Question").html(jsonObj.question);
        } else {
            $("#Pas_UserEMail").next().css("color", "red").html("用户名邮箱不匹配").show();
        }
    });
    return true;
}
function PasCheckPasswordAnswer() {
    var jObj = $("#Pas_UserAnswer");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 20) {
        jObj.next().show();
        return false;
    } else {
        jObj.next().hide();
    }
    return true;
}
function PasCheckLoginPassword() {
    var jObj = $("#Pas_UserPassword");
    var nLength = jObj.val().length;
    if (nLength < 3 || nLength > 16) {
        jObj.next().show();
        return false;
    } else {
        jObj.next().hide();
    }
    return true;
}
function PasCheckConfirmPassword() {
    var jObj1 = $("#Pas_UserPassword");
    var jObj2 = $("#Pas_UserPassword_Confirm");
    if (jObj1.val() != jObj2.val()) {
        jObj2.next().show();
        return false;
    } else {
        jObj2.next().hide();
    }
    return true;
}
function PasLoadImgCheck() {
    $.post(g_webaction, {
        f: 1
    },
    function(json) {
        var jsonObj = $.evalJSON(json);
        if (0 == jsonObj.error) {
            var imgObj = $("#Pas_ImgCode");
            imgObj.attr("src", g_webimage + "?c=" + jsonObj.ccmd5);
            imgObj.css("visibility", "visible");
            imgObj.attr("md5", String(jsonObj.ccmd5));
        }
    });
}
function PasChecVerifyCode() {
    var jObj = $("#Pas_VerifyCode");
    var nLength = jObj.val().length;
    if ((6 != nLength) || ($("#Pas_ImgCode").attr("md5")) != hex_md5(String(jObj.val()).toUpperCase())) {
        jObj.next().next().show();
        return false;
    } else {
        jObj.next().next().hide();
    }
    return true;
}
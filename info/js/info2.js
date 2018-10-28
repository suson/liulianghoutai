var WebApp = 
{
	WebMain : function ()
	{
		WebApp.InitPage();
		WebApp.InitEvent();
	},
	InitPage : function()
	{
		$("#Text_Advise").focus();
		$("#Href_Ask").click(function(){$("#Div_Main").show();$("#Div_Result").hide();WebApp.ReSet();});
		
		$("#Div_AjaxBack").ajaxStart(function(){$(this).show();});
		$("#Div_AjaxBack").ajaxStop(function(){$(this).hide();});
	},
	InitEvent : function()
	{
		$("#Btn_Submit").click(WebApp.SubMit);
		$("#Btn_Reset").click(WebApp.ReSet);
	},
	SubMit : function ()
	{
		var strAdvise = $("#Text_Advise").val();
		
		if ("" == strAdvise)
		{
			alert("请填写建议内容！")
			return;
		}
		if(strAdvise.length > 200)
		{
			alert("建议内容不能超过200字！")
			return;
		}
		
		var strQQ = $("#Text_QQ").val();
		var strEMail = $("#Text_EMail").val();
		var strApp = $("#Get_App").val();
		
		$.post("./url_msg.php",
		{msg : strAdvise, qq : strQQ, email : strEMail, app : strApp, flag : Math.random()},
		WebApp.Callback);
		
	},
	ReSet : function()
	{
		$("#Text_Advise").val("");
		$("#Text_QQ").val("");
		$("#Text_EMail").val("");
		$("#Text_Advise").focus();
	},
	Callback : function (result)
	{
		$("#Div_Main").hide();
		$("#Div_Result").show();
	}
}

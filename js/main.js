/**
 * 
 */
var g_webaction = 'http://new.b861.com/Include/index.php';;
(function($) {
	$.extend($.expr[':'], {
		nochild : function(a, i, m) {
			return $(a).has(m[3]).size() == 0;
		}
	});
})(jQuery);;
(function($) {
	$(document).ready(
			function() {
				$('#confirm').click(
						function() {
							$.post(g_webaction, {
								f : 3,
								i : String(JSON.stringify({
									name : $('#username').val()
								}))
							}, function(json) {
								var oJson = JSON.parse(json);
								if (oJson.error == -1) {
									$('#showMSG').css('blackgroundColor',
											'#00f').css('color', '#f00').text(
											'用户名已存在！');
								} else {
									$('#showMSG').css('blackgroundColor',
											'#00f').css('color', '#0f0').text(
											'用户名不存在！');
								}
							});
						});
				$('#submit').click(
						function() {
							var data = {
								'name' : $('#name').val(),
								'pwd' : $('#pwd').val(),
								'email' : $('#email').val(),
								'question' : $('#question').val(),
								'answer' : $('#answer').val(),
								'cc' : $('#cc').val()
							};
							$.post(g_webaction, {
								f : 2,
								i : String(JSON.stringify(data))
							}, function(json) {
								var oJson = JSON.parse(json);
								if (oJson.error != 0) {
									$('#showMsg').css('blackgroundColor',
											'#00f').css('color', '#f00').text(
											'注册失败');
								} else {
									$('#showMsg').css('blackgroundColor',
											'#00f').css('color', '#f00').text(
											'注册成功！');
								}
							});
						});
			});
})(jQuery);
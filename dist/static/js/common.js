var handleUrl = "/Task/handle";
var l = $('.js-batch-set').ladda();
l.click(function () {
	var tips = $(this).data('tips');

	        var ids = checkedValue(); //选择的任务
	        if (!ids) {
	        	toastr.error('请选择任务');
	        	return false;
	        }
	        var action = $(this).data('action');
	        $(this).ladda('start');
	        doAction(ids, action, tips);
	    });


$('.do-action-copy-as').click(function () {
	var tips = $(this).data('tips');

	        var ids = checkedValue(); //选择的任务
	        if (!ids) {
	        	return false;
	        }
	        var action = $(this).data('action');

	        var type = $(this).data('type');
	        doAction(ids, action, tips, {type: type});
	    });

$('.btn-doAction').on('click', function () {
	var tips = $(this).data('tips');
	var ids = $(this).data('id');
	var action = $(this).data('action');
	var status = $(this).data('status');
	doAction(ids, action, tips);
})

function doAction(ids, action, tips, extend) {
	if (!ids) {
		return false;
	}
	if (ids.length < 1) {
		return false;
	}
	if (ids instanceof Array) {
		var a = ids.join(',');
	} else {
		var a = ids;
	}
	var text = "编号:" + a;
	if (action == 'del') {
		text = '删除的任务将无法找回,并返还未使用积分!';
	}
	swal({
		title: tips ? "确定" + tips + '?' : '确定操作',
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "确定",
		cancelButtonText: "取消",
		closeOnConfirm: true,
		closeOnCancel: true,
		showLoaderOnConfirm: true,
	},
	function (isConfirm) {
		if (!isConfirm) {
			l.ladda('stop');
			return;
		}
		var postdata = {action: action, id: ids}
		if (extend) {
			$.extend(postdata, extend);
		}
		$.post(handleUrl, postdata, function (json) {
			setTimeout(function () {
				if (json.code == 1) {
					swal({
						title: "操作成功",
						text: json.msg,
						type: "success",
						timer: 3000
					}, function () {
						location.reload();
					});
				} else {
					swal({
						title: "操作失败",
						text: json.msg,
						type: "error",
					}, function () {
						location.reload();
					});
				}
			}, 100)
		});

	});
}

function openWin(url) {
	window.open(url, 'newwindow', 'height=600, width=900, top=100,left=100,toolbar=no, menubar=no, scrollbars=no,resizable=no,location=no, status=no');
}var handleUrl = "/Task/handle";
var l = $('.js-batch-set').ladda();
l.click(function () {
	var tips = $(this).data('tips');

	        var ids = checkedValue(); //选择的任务
	        if (!ids) {
	        	toastr.error('请选择任务');
	        	return false;
	        }
	        var action = $(this).data('action');
	        $(this).ladda('start');
	        doAction(ids, action, tips);
	    });


$('.do-action-copy-as').click(function () {
	var tips = $(this).data('tips');

	        var ids = checkedValue(); //选择的任务
	        if (!ids) {
	        	return false;
	        }
	        var action = $(this).data('action');

	        var type = $(this).data('type');
	        doAction(ids, action, tips, {type: type});
	    });

$('.btn-doAction').on('click', function () {
	var tips = $(this).data('tips');
	var ids = $(this).data('id');
	var action = $(this).data('action');
	var status = $(this).data('status');
	doAction(ids, action, tips);
})

function doAction(ids, action, tips, extend) {
	if (!ids) {
		return false;
	}
	if (ids.length < 1) {
		return false;
	}
	if (ids instanceof Array) {
		var a = ids.join(',');
	} else {
		var a = ids;
	}
	var text = "编号:" + a;
	if (action == 'del') {
		text = '删除的任务将无法找回,并返还未使用积分!';
	}
	swal({
		title: tips ? "确定" + tips + '?' : '确定操作',
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "确定",
		cancelButtonText: "取消",
		closeOnConfirm: true,
		closeOnCancel: true,
		showLoaderOnConfirm: true,
	},
	function (isConfirm) {
		if (!isConfirm) {
			l.ladda('stop');
			return;
		}
		var postdata = {action: action, id: ids}
		if (extend) {
			$.extend(postdata, extend);
		}
		$.post(handleUrl, postdata, function (json) {
			setTimeout(function () {
				if (json.code == 1) {
					swal({
						title: "操作成功",
						text: json.msg,
						type: "success",
						timer: 3000
					}, function () {
						location.reload();
					});
				} else {
					swal({
						title: "操作失败",
						text: json.msg,
						type: "error",
					}, function () {
						location.reload();
					});
				}
			}, 100)
		});

	});
}

function openWin(url) {
	window.open(url, 'newwindow', 'height=600, width=900, top=100,left=100,toolbar=no, menubar=no, scrollbars=no,resizable=no,location=no, status=no');
}





var tags='全部分组'
var url="/1?o=6&os=1&page=1";
var setTagsObj=new Vue({
	el: '#set-tags',
	data: function() {
		return {
			visible: false ,
			ids:[],
			tags:JSON.parse('["\u5168\u90e8\u5206\u7ec4","\u6ca1\u6709\u5206\u7ec4"]'),
			tags1: tags,
			tags2: []
		}
	},
	metdods: {
		changeTag:function () {
			tags=this.tags1
			window.location.href=url+"&tags="+tags;
		},
		apply:function () {
			var self=this;
			$.ajax({
				url:'/task/handle',
				data:{'action':'set-tags','id':self.ids,'tags':self.tags2},
				type:'post',
				success:function (json) {
					if(json.code==1){
						window.location.reload();
					}else{
						swal({
							type:'error',
							text:json.msg,
							title:'操作失败'
						})
					}
				}
			})
		}
	}

})
$(function () {
	$('.btn-set-tag').on('click', function () {
		var ids=checkedValue();
		if(!ids){
			return;
		}
		setTagsObj.ids = ids;
		setTagsObj.visible = true;
	});
})





var ueditor_url = "http://www.seoyanfa.com/home/ueditor/index.html";
    //表格中的全选操作
    $('.js-check-all').click(function (event) {
    	var checkboxs = $(this).parents('table').find('input.js-check');
    	var allChecked = $(this).prop('checked');
    	if (allChecked) {
    		checkboxs.prop('checked', 'checked');
    	} else {
    		checkboxs.prop('checked', false);
    	}
    });

    function mailactive() {
    	$.get("http://www.seoyanfa.com/Home/Login/handle.html?action=mailactive", function (data) {
    		toastr.success(data.msg);
    	})
    }

    function openWin(url) {
    	window.open(url, 'newwindow', 'height=600, width=900, top=100,left=100,toolbar=no, menubar=no, scrollbars=no,resizable=no,location=no, status=no');
    }

    function errorCallback() {
    	return false;
    }

    //获取表格中checked的值的集合
    function checkedValue() {
    	var jsCheck = $('.js-check:checked');
    	var value = [];
    	if (jsCheck.length < 1) {
            //alert('请选择列表!');
            return false;
        }

        $.each(jsCheck, function (i, v) {
        	value.push($(v).val());
        });
        return value;
    }

    //消息通知
    toastr.options = {
        "closeButton": false, //关闭按钮
        "debug": false, //调试开关
        "progressBar": true, //进度条
        "preventDuplicates": true, //防止重复
        "positionClass": "toast-top-right", //顶部中间
        "onclick": null, //点击回调
        "showDuration": "400", //显示时间
        "hideDuration": "1000", //隐藏时间
        "timeOut": "2000", //缓冲时间
        "extendedTimeOut": "1000",
        "showEasing": "swing", //显示动画
        "hideEasing": "linear", //隐藏动画
        "showMethod": "fadeIn", //显示方式
        "hideMethod": "fadeOut" //隐藏方式
    };

    //js 表单提交
    $('form.js-ajax').submit(function (e) {
    	e.preventDefault();
    	if (typeof rules == 'undefined') {
    		$(this).validate();
    	} else {
    		$(this).validate(rules);
    	}

    	var input = $(this).serialize();
    	var url = $(this).attr('action');
    	var method = $(this).attr('method');
    	$.ajax({
    		url: url,
    		type: method,
    		dataType: 'json',
    		data: input,
    	})
    	.done(function (res) {
    		if (res.code) {
    			toastr.success(res.msg);
    			if (res.url) {
    				if (res.url == 'reload') {
    					setTimeout(function () {
    						location.reload();
    					}, 2000);
    				} else {
    					setTimeout(function () {
    						location.href = res.url;
    					}, 2000);
    				}
    			}
    		} else {
    			toastr.error(res.msg);
    			if (typeof errorCallback != 'undefined') {
    				errorCallback();
    			}
    		}
    	})
    	.fail(function () {

    	})
    	.always(function () {
    	});

    });

    //js按钮动作
    $('.js-btn').click(function (e) {
    	e.preventDefault();
    	var _this = $(this);
    	var url = _this.data('action');
    	_this.prop('disabled', 'disabled');
    	$.get(url, function (data) {
    		if (data.code) {
    			toastr.success(data.msg);
    			setTimeout(function () {
    				window.location.reload();
    			}, 2000);
    		} else {
    			toastr.error(data.msg);
    			_this.prop('disabled', false);
    		}
    	});
    });

    //a链接异步提交
    $('.js-a').click(function (e) {
    	e.preventDefault();
    	var _this = $(this);
    	var url = _this.attr('href');
    	_this.prop('disabled', 'disabled');
    	$.get(url, function (data) {
    		if (data.code) {
    			toastr.success(data.msg);
    			setTimeout(function () {
    				window.location.reload();
    			}, 2000);
    		} else {
    			toastr.error(data.msg);
    			_this.prop('disabled', false);
    		}
    	});
    });


    var handleUrl = "/Task/handle";
    var l = $('.js-batch-set').ladda();
    l.click(function () {
    	var tips = $(this).data('tips');

        var ids = checkedValue(); //选择的任务
        if (!ids) {
        	toastr.error('请选择任务');
        	return false;
        }
        var action = $(this).data('action');
        $(this).ladda('start');
        doAction(ids, action, tips);
    });


    $('.do-action-copy-as').click(function () {
    	var tips = $(this).data('tips');

        var ids = checkedValue(); //选择的任务
        if (!ids) {
        	return false;
        }
        var action = $(this).data('action');

        var type = $(this).data('type');
        doAction(ids, action, tips, {type: type});
    });

    $('.btn-doAction').on('click', function () {
    	var tips = $(this).data('tips');
    	var ids = $(this).data('id');
    	var action = $(this).data('action');
    	var status = $(this).data('status');
    	doAction(ids, action, tips);
    })

    function doAction(ids, action, tips, extend) {
    	if (!ids) {
    		return false;
    	}
    	if (ids.length < 1) {
    		return false;
    	}
    	if (ids instanceof Array) {
    		var a = ids.join(',');
    	} else {
    		var a = ids;
    	}
    	var text = "编号:" + a;
    	if (action == 'del') {
    		text = '删除的任务将无法找回,并返还未使用积分!';
    	}
    	swal({
    		title: tips ? "确定" + tips + '?' : '确定操作',
    		text: text,
    		type: "warning",
    		showCancelButton: true,
    		confirmButtonColor: "#DD6B55",
    		confirmButtonText: "确定",
    		cancelButtonText: "取消",
    		closeOnConfirm: true,
    		closeOnCancel: true,
    		showLoaderOnConfirm: true,
    	},
    	function (isConfirm) {
    		if (!isConfirm) {
    			l.ladda('stop');
    			return;
    		}
    		var postdata = {action: action, id: ids}
    		if (extend) {
    			$.extend(postdata, extend);
    		}
    		$.post(handleUrl, postdata, function (json) {
    			setTimeout(function () {
    				if (json.code == 1) {
    					swal({
    						title: "操作成功",
    						text: json.msg,
    						type: "success",
    						timer: 3000
    					}, function () {
    						location.reload();
    					});
    				} else {
    					swal({
    						title: "操作失败",
    						text: json.msg,
    						type: "error",
    					}, function () {
    						location.reload();
    					});
    				}
    			}, 100)
    		});

    	});
    }

    function openWin(url) {
    	window.open(url, 'newwindow', 'height=600, width=900, top=100,left=100,toolbar=no, menubar=no, scrollbars=no,resizable=no,location=no, status=no');
    }
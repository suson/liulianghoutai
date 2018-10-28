$(document).keypress(function(e){
        if(e.which == 13){
            $('#submit').click();
        }
    });
    $('#submit').click(function () {
        var Check = $('#UserProfile').data('amui.validator').isFormValid();
        if (Check == false) {
            //$.ThinkBox.error('请正确填写相应字段');
            $('#submit').button('reset');
            return false;
        }

        var autoLogin = 0;
        var checked = $('input[name="auto_login"]').prop('checked');
        if (checked) {
            autoLogin = 1;
        } else {
            autoLogin = 0;
        }
        var data = {
            email: $('#inputEmail').val(),
            password: $('#inputPassword').val(),
            verify: $('#inputVerify').val(),
            auto_login: autoLogin,
        };
        $.ajax({
            url: "/Home/Login/handle?action=signup",
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (data) {
                if (data.code == 1) {
                    location.href = "http://www.seoyanfa.com/tool.html";
                } else {
                    if(data.msg){
                        $.ThinkBox.error(data.msg);
                    }else{
                     //$.ThinkBox.error('服务器出错了');
                    }
                    refreshCaptcha();
                    $('#submit').button('reset');
                }
            },
            error: function () {
               // $.ThinkBox.error('服务器出错了');
                //refreshCaptcha();
            }
        });
    });
    $('#verify').click(function () {
        refreshCaptcha();
    });

    function refreshCaptcha() {
        $('#verify').children('img').attr('src', "/captcha?" + Math.random());
    }


    $(function () {
        var $form = $('#form-with-tooltip');
        var $tooltip = $('<div id="vld-tooltip">提示信息！</div>');
        $tooltip.appendTo(document.body);

        $form.validator();

        var validator = $form.data('amui.validator');

        $form.on('focusin focusout', '.am-form-error input', function (e) {
            if (e.type === 'focusin') {
                var $this = $(this);
                var offset = $this.offset();
                var msg = $this.data('foolishMsg') || validator.getValidationMessage($this.data('validity'));

                $tooltip.text(msg).show().css({
                    left: offset.left + 10,
                    top: offset.top + $(this).outerHeight() + 10
                });
            } else {
                $tooltip.hide();
            }
        });

        var screenHeight = document.body.scrollHeight;
        var bodyHeight = document.body.clientHeight;

        if (bodyHeight < screenHeight) {
            $('body').css('height', bodyHeight);
            $('footer').addClass('am-topbar-fixed-bottom');
        }
    });
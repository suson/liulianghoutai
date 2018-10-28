/*
 * 文件功能：验证账号密码是否正确
 * 正确就加载文件
 * 否则就不加载
 */

var gWidth, gHeight;
if (Ext.isIE) {
    gWidth = screen.width * 0.9;
    gHeight = screen.height * 0.6;
} else {
    gWidth = 1000;
    gHeight = 600;
}
var cfgservice = function() {
    var form = new Ext.form.FormPanel({
        id: "cfgserviceform",
        border: false,
        labelWidth: 80,
        bodyStyle: "padding:5px;",
        defaultType: "textfield",
        defaults: {
            anchor: "95%"
        },
        labelSeparator: ': <br /><font style="color:blue;font-size:11px;" >单位(元/天)</font>',
        items: [{
            fieldLabel: "优化服务",
            name: "optimize",
            style: {
                marginBottom: "5px",
                marginTop: "5px"
            }
        },
        {
            fieldLabel: "默认价格",
            name: "ip8",
            style: {
                marginBottom: "5px",
                marginTop: "5px"
            }
        },
        {
            fieldLabel: "服务1",
            name: "ip101",
            style: {
                marginTop: "5px",
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务2",
            name: "ip103",
            style: {
                marginTop: "5px",
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务3",
            name: "ip106",
            style: {
                marginTop: "5px",
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务4",
            name: "ip108",
            style: {
                marginTop: "5px",
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务5",
            name: "ip110",
            style: {
                marginTop: "5px",
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务6",
            name: "ip115",
            style: {
                marginTop: "5px",
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务7",
            name: "ip120",
            style: {
                marginTop: "5px",
                marginBottom: "5px"
            }
        }]
    });
    var win = new Ext.Window({
        title: "配置服务单价",
        width: 300,
        height: 430,
        shadow: false,
        modal: true,
        draggable: false,
        layout: "fit",
        buttonAlign: 'center',
        items: [form],
        buttons: [{
            xtype: "button",
            text: "确 定",
            handler: function() {
                Ext.getCmp("cfgserviceform").getForm().submit({
                    url: "./cfgservice.php",
                    method: "POST",
                    success: function(form, action) {
                        Ext.Msg.alert("提示", "配置服务价格成功！",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert("提示", "请检查网络！<br >&nbsp;&nbsp;网络连接失败！");
                    }
                });
            }
        }]
    });

    win.show();
    setDefaultValue(form, "cfgservice");
}
var modCenter = function() {

    var TCData = ["充值面板", "修改密码面板", "登入首页", "用户中心", "网址管理面板", "添加网址面板", "优化服务续费面板", "网址设置面板", "代挂服务续费面板", "显示消费金额面板", "流量控制面板", "优化设置面板", "开通优化服务面板", "开通代挂服务面板", "查看优化设置面板", "查看代挂服务面板"];
    var modpanel = new Ext.form.FormPanel({
        id: "modCenter",
        border: false,
        height: 600,
        region: "center",
        labelWidth: 0,

        bodyStyle: "padding:10px;",
        items: [{
            xtype: 'combo',
            displayField: 'page',
            valueField: "page",
            mode: 'local',
            fieldLabel: "用户中心页面",
            name: "page",
            anchor: '40%',
            emptyText: "请选择页面",
            forceSelection: true,
            triggerAction: 'all',
            selectOnFocus: true,
            style: {
                marginBottom: "5px"
            },
            store: new Ext.data.ArrayStore({
                fields: ["page"],
                data: [["paymgt.html"], ['pswmgt.html'], ['index.html'], ['center.html'], ['urlmgt.html'], ['html/addurl.html'], ['html/moreoptimize.html'], ['html/moreset.html'], ['html/moretrustee.html'], ['html/paymoney.html'], ['html/setflow.html'], ['html/setoptimize.html'], ['html/startoptimize.html'], ['html/starttrustee.html'], ['html/viewoptimize.html'], ['html/viewtrustee.html']]
            }),
            listeners: {
                change: function(c, nv, ov) {
                    Ext.Ajax.request({
                        url: './loadCFile.php',
                        params: {
                            file: nv
                        },
                        method: "POST",
                        success: function(rs) {
                            Ext.getCmp("centertextarea").setValue(rs.responseText);
                        }
                    });
                },
                select: function(c, r, i) {
                    Ext.getCmp("centerlabel").setText(TCData[i]);
                }
            }
        },
        {
            xtype: "label",
            id: "centerlabel",
            text: ""
        },
        {
            xtype: "textarea",
            name: 'cont',
            anchor: "99%",
            hideLabel: true,
            height: 500,
            style: {
                marginBottom: "5px",
                marginTop: '5px'
            },
            id: "centertextarea"
        },
        {
            xtype: "button",
            text: "确  定",
            width: 50,
            style: {
                marginLeft: "350px"
            },
            handler: function() {
                Ext.getCmp("modCenter").getForm().submit({
                    url: "./modCenter.php",
                    method: "POST",
                    success: function(form, action) {
                        Ext.Msg.alert("提示", action.result.msg);
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert("提示", action.result.msg);
                    }
                });
            }
        }]
    });
    var p = Ext.getCmp("zhumianban");
    if ( !! p.get(0)) {
        p.get(0).destroy();
        p.add(modpanel);
        p.doLayout();
    } else {
        p.add(modpanel);
        p.doLayout();
    }
}
var modOffice = function() {
    var TOData = ["常见问题", "升级日志", "信息反馈", "首页", "流量优化", "产品介绍", "使用技巧"];
    var modpanel = new Ext.form.FormPanel({
        id: "modOffice",
        border: false,
        height: 600,
        region: "center",
        labelWidth: 0,
        bodyStyle: "padding:10px;",
        items: [{
            xtype: 'combo',
            fieldLabel: "网站页面",
            displayField: 'page',
            valueField: "page",
            mode: 'local',
            name: "page",
            anchor: '40%',
            emptyText: "请选择页面",
            forceSelection: true,
            triggerAction: 'all',
            selectOnFocus: true,
            style: {
                marginBottom: "5px"
            },
            store: new Ext.data.ArrayStore({
                fields: ['page'],
                data: [["aq.html"], ['blog.html'], ['feedback.html'], ['index.html'], ['optimize.html'], ['product.html'], ['useit.html']]
            }),
            listeners: {
                change: function(cobo, nv, ov) {
                    cobo.label = nv;
                    Ext.Ajax.request({
                        url: './loadOFile.php',
                        params: {
                            file: nv
                        },
                        method: "POST",
                        success: function(rs) {
                            Ext.getCmp("officetextarea").setValue(rs.responseText);
                        }
                    });
                },
                select: function(c, r, i) {
                    Ext.getCmp("officelabel").setText(TOData[i]);
                }
            }
        },
        {
            xtype: "label",
            id: "officelabel",
            text: ""
        },
        {
            xtype: "textarea",
            name: 'cont',
            anchor: "99%",
            hideLabel: true,
            width: 200,
            height: 500,
            style: {
                marginBottom: "5px",
                marginTop: "5px"
            },
            id: "officetextarea"
        },
        {
            xtype: "button",
            text: "确  定",
            width: 50,
            style: {
                marginLeft: "350px"
            },
            handler: function() {
                Ext.getCmp("modOffice").getForm().submit({
                    url: "./modOffice.php",
                    method: "POST",
                    success: function(form, action) {
                        Ext.Msg.alert("提示", action.result.msg);
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert("提示", action.result.msg);
                    }
                });
            }
        }]
    });

    var p = Ext.getCmp("zhumianban");
    if ( !! p.get(0)) {
        p.get(0).destroy();
        p.add(modpanel);
        p.doLayout();
    } else {
        p.add(modpanel);
        p.doLayout();
    }
}
var login_form = new Ext.FormPanel({
    labelAlign: 'top',
    id: 'loginForm',
    bodyStyle: 'padding:5px 0px 5px 20px',
    items: [{
        xtype: 'textfield',
        fieldLabel: '用户名',
        anchor: "80%",
        name: 'user',
        allowBlank: false,
        msgTarget: "side",
        emptyText: '用户名...'

    },
    {
        xtype: 'textfield',
        fieldLabel: '密  码',
        msgTarget: "side",
        name: 'pwd',
        anchor: "80%",
        inputType: 'password',
        style: {
            marginBottom: "10px"
        }
    }]
});
var uploadFile = function() {
    var form = new Ext.form.FormPanel({
        fileUpload: true,
        width: 500,
        frame: true,
        autoHeight: true,
        bodyStyle: 'padding: 10px 10px 0 10px;',
        labelWidth: 50,
        defaults: {
            anchor: '95%',
            msgTarget: 'side'
        },
        items: [{
            xtype: 'textfield',
            fieldLabel: '文件名',
            emptyText: "例如: skynet",
            name: "name"
        },
        {
            xtype: 'fileuploadfield',
            id: 'form-file',
            emptyText: '选择文件...',
            fieldLabel: '文件',
            name: 'file',
            buttonText: '上&nbsp;&nbsp;传'
        }],
        buttons: [{
            text: '保&nbsp;&nbsp;存',
            handler: function() {
                if (form.getForm().isValid()) {
                    form.getForm().submit({
                        url: './fileUpload.php',
                        //waitMsg: '正在上传文件，请稍等...',
                        success: function(fp, o) {
                            Ext.Msg.alert("提示", o.result.msg,
                            function() {
                                win.destroy();
                            });
                        },
                        failure: function() {
                            Ext.Msg.alert("提示", "文件上传失败",
                            function() {
                                form.getForm().reset();
                            });
                        }
                    });
                }
            }
        },
        {
            text: '重 置',
            handler: function() {
                form.getForm().reset();
            }
        }]
    });
    var win = new Ext.Window({
        title: "上传文件",
        items: [form]
    });
    win.show();
}
var setGradeScore = function() {
    var form = new Ext.form.FormPanel({
        id: "setGradeScoreForm",
        border: false,
        labelWidth: 100,
        bodyStyle: "padding:5px;",
        defaultType: "textfield",
        defaults: {
            anchor: "95%"
        },
        items: [{
            fieldLabel: "二级所需积分",
            name: "lv2",
            emptyText: "2000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "三级所需积分",
            name: "lv3",
            emptyText: "8000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "四级所需积分",
            name: "lv4",
            emptyText: "30000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "五级所需积分",
            name: "lv5",
            emptyText: "100000",
            style: {
                marginBottom: "5px"
            }
        }]
    });
    var win = new Ext.Window({
        title: "设置用户升级积分",
        id: "setGradeScoreWin",
        width: 300,
        height: 200,
        shadow: false,
        modal: true,
        draggable: false,
        layout: "fit",
        buttonAlign: "center",
        items: [form],
        buttons: [{
            text: "确&nbsp;&nbsp;定",
            xtype: "button",
            handler: function() {
                Ext.getCmp("setGradeScoreForm").getForm().submit({
                    url: "./setGradeScore.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "设置成功！",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function() {
                        Ext.Msg.alert("提示", "设置失败！",
                        function() {});
                    }
                });
            }
        }]
    });
    win.show();
    setDefaultValue(form, "GradeScore");
}
var setTdLoginScore = function() {
    var form = new Ext.form.FormPanel({
        id: "setTdLoginScoreForm",
        bodyStyle: 'padding:20px 0 0 20px',
        defaultType: "textfield",
        labelWidth: 50,
        defaults: {
            anchor: "85%"
        },
        items: [{
            fieldLabel: '积分',
            style: {
                marginBottom: "5px"
            },
            emptyText: "50",
            name: "score"
        }]
    });
    var win = new Ext.Window({
        title: "设置每天登陆奖励积分",
        id: "setTdLoginScoreWin",
        width: 400,
        height: 130,
        shadow: false,
        modal: true,
        resizable: false,
        items: form,
        draggable: false,
        buttonAlign: 'center',
        layout: 'fit',
        buttons: [{
            xtype: "button",
            text: "确&nbsp;&nbsp;定",
            id: "comfire",
            handler: function() {
                Ext.getCmp("setTdLoginScoreForm").getForm().submit({
                    url: "./setTdLoginScore.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "设置成功",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function() {
                        Ext.Msg.alert("提示", "设置失败",
                        function() {});
                    }
                });
            }
        }]
    });
    win.show();
    setDefaultValue(form, "tdloginscore");
}
var setRechangeTax = function() {
    var form = new Ext.form.FormPanel({
        id: "setRechangeTaxForm",
        bodyStyle: 'padding:10px',
        defaultType: "textfield",
        labelWidth: 70,
        defaults: {
            anchor: "85%"
        },
        items: [{
            xtype: "box",
            html: "<div><font color=red>提示:&nbsp;&nbsp;</font>如果充值金额与奖励金额比为 1%，请输入 1</div>",
            style: {
                marginBottom: "10px"
            },
        },
        {
            fieldLabel: '充值奖励',
            style: {
                marginBottom: "5px"
            },
            name: "lmoney",
            emptyText: "1"
        },
        {
            fieldLabel: '充值赠送',
            style: {
                marginBottom: "5px"
            },
            name: "pmoney",
            emptyText: "1"
        },
        {
            fieldLabel: '充值积分',
            style: {
                marginBottom: "5px"
            },
            name: "score",
            emptyText: "1000"
        },
        {
            fieldLabel: '赠送天数',
            style: {
                marginBottom: "5px"
            },
            name: "pday",
            emptyText: "1000"
        }]
    });

    var win = new Ext.Window({
        title: "设置充值奖励",
        id: "setRechangeTaxWin",
        width: 400,
        height: 230,
        shadow: false,
        modal: true,
        resizable: false,
        items: form,
        draggable: false,
        buttonAlign: 'center',
        layout: 'fit',
        buttons: [{
            xtype: "button",
            text: "确&nbsp;&nbsp;定",
            id: "comfire",
            handler: function() {
                Ext.getCmp("setRechangeTaxForm").getForm().submit({
                    url: "./setRechangeTax.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "设置成功",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function() {
                        Ext.Msg.alert("提示", "设置失败",
                        function() {});
                    }
                });
            }
        }]
    });

    win.show();
    setDefaultValue(form, "rechangetax");
}
var setsvcIPs = function() {
    var form = new Ext.form.FormPanel({
        id: "setsvcIPsForm",
        border: false,
        labelWidth: 100,
        bodyStyle: "padding:5px;",
        defaultType: "textfield",
        defaults: {
            anchor: "95%"
        },
        items: [{
            fieldLabel: "优化",
            name: "optimize",
            emptyText: "1000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "默认",
            name: "ip8",
            emptyText: "800",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务0",
            name: "ip101",
            emptyText: "1000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务1",
            name: "ip103",
            emptyText: "3000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务2",
            name: "ip106",
            emptyText: "6000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务3",
            name: "ip108",
            emptyText: "8000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务4",
            name: "ip110",
            emptyText: "10000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务5",
            name: "ip115",
            emptyText: "15000",
            style: {
                marginBottom: "5px"
            }
        },
        {
            fieldLabel: "服务6",
            name: "ip120",
            emptyText: "20000",
            style: {
                marginBottom: "5px"
            }
        }]
    });
    var win = new Ext.Window({
        title: "设置服务IP数量",
        id: "setsvcIPsWin",
        width: 300,
        height: 350,
        shadow: false,
        modal: true,
        resizable: false,
        items: form,
        draggable: false,
        buttonAlign: 'center',
        layout: 'fit',
        buttons: [{
            xtype: "button",
            text: "确&nbsp;&nbsp;定",
            id: "comfire",
            handler: function() {
                Ext.getCmp("setsvcIPsForm").getForm().submit({
                    url: "./setsvcIPs.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "设置成功",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function() {
                        Ext.Msg.alert("提示", "设置失败",
                        function() {});
                    }
                });
            }
        }]
    });
    win.show();
    setDefaultValue(form, "svcIPs");
}
var setUrlToUser = function() {
    var form = new Ext.form.FormPanel({
        id: "setUrlToUserForm",
        border: false,
        labelWidth: 50,
        bodyStyle: "padding:10px;",
        defaultType: "textfield",
        defaults: {
            anchor: "95%"
        },
        items: [{
            fieldLabel: "用户名",
            name: "name",
            emptyText: "userName...",
            style: {
                marginBottom: "5px"
            }
        },{
            fieldLabel: "网址",
            name: "url",
            emptyText: "http://",
            style: {
                marginBottom: "5px"
            }
        }]
    });

    var win = new Ext.Window({
        title: "修改网址所属用户",
        id: "setsvcIPsWin",
        width: 300,
        height: 150,
        shadow: false,
        modal: true,
        resizable: false,
        items: form,
        draggable: false,
        buttonAlign: 'center',
        layout: 'fit',
        buttons: [{
            xtype: "button",
            text: "确&nbsp;&nbsp;定",
            id: "comfire",
            handler: function() {
                form.getForm().submit({
                    url: "./setUrlToUser.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "修改成功",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function(form, action) {
                        if (action.result != undefined) {
                            Ext.Msg.alert("提示", action.result.msg,
                            function() {});
                        } else {
                            Ext.Msg.alert("提示", "网络连接失败...",
                            function() {});
                        }
                    }
                });
            }
        }]
    });
    win.show();
}
var init = function() {
	var adminInfo= new Ext.Panel({
		id:"adminInfo",
		bodyStyle:"padding:20px 0 0 20px;",
		autoLoad:{
			url:"./loadAdminInfo.php?_r="+Math.random(),
			method:"POST"
		}
	});
    new Ext.Panel({
        id: 'mainpanel',
        renderTo: 'x-main',
        title: "数据库查看",
        width: 1000,
        height: 630,
        border: false,
        layout: "border",
        items: [{
            xtype: "panel",
            width: 200,
            height: 600,
            region: "west",
            id: "navpanel",
            layout: "accordion",
            items: [{
                xtype: "panel",
                title: '网站修改',
                border: false,
                id: "WSmod",
                layout: "form",
                bodyStyle: "padding:5px 0 5px 15px;",
                items: [{
                    xtype: "button",
                    text: "删除用户",
                    anchor: '95%',
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        dltMWin();
                    }
                },{
                    xtype: "button",
                    text: "删除网址",
                    anchor: '95%',
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        dltUrlWin();
                    }
                },{
                    xtype: 'button',
                    text: "修改用户信息",
                    style: {
                        marginBottom: "5px"
                    },
                    anchor: '95%',
                    handler: function() {
                        modUserWin();
                    }
                },{
                    xtype: "button",
                    text: "修改用户账户信息",
                    style: {
                        marginBottom: "5px"
                    },
                    anchor: '95%',
                    handler: function() {
                        modAcntWin();
                    }
                },{
                    xtype: "button",
                    text: "修改网站页面",
                    anchor: '95%',
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        modOffice();
                    }
                },{
                    xtype: "button",
                    text: "修改用户中心页面",
                    anchor: '95%',
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        modCenter();
                    }
                },{
                    xtype: "button",
                    text: "修改网址所属用户",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        setUrlToUser();
                    }
                },{
                    xtype: "button",
                    text: "上传文件",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        uploadFile();
                    }
                },{
					xtype:"button",
					text:"刷新数据库",
					anchor: "95%",
                    style: {marginBottom: "5px"},
					handler:function(){
						Ext.Msg.show({
							title:"危险",
							icon:Ext.MessageBox.WARNING,
							modal:true,
							buttons:{ok:"继续",cancel:"取消"},
							msg:"<font color=red>危险:</font>该操作会执行以下行为：<br />&nbsp;&nbsp;&nbsp;&nbsp;1、将所有网址设置为离线。<br />&nbsp;&nbsp;&nbsp;&nbsp;2、将开通代挂服务的网址设置为在线。<br />&nbsp;&nbsp;&nbsp;&nbsp;3、将所有网址的今日流量设为0。<br />4、将所有网址的今日分享设为0。<br /> <font color=blue>点继续完成该操作</font>",
							fn:function(btnid){
								var me=this;
								if(btnid=="ok"){
									Ext.Ajax.request({
										url:"./refresh.php",
										success:function(res){
											var json=Ext.decode(res.responseText);
											if(json.success){
												Ext.Msg.alert("提示","刷新完成！");
											}else{
												Ext.Msg.alert("提示",json.msg);
											}
										}
									});
								}
							}
						});
					}
				}]
            },{
				xtype: "panel",
                title: '服务配置',
                border: false,
                id: "WSset",
                layout: "form",
                bodyStyle: "padding:5px 0 5px 15px;",
                items: [{
                    xtype: "button",
                    text: "配置服务单价",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        cfgservice();
                    }
                },{
                    xtype: "button",
                    text: "设置用户添加网址上限",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        setAddUrlLimit();
                    }
                },{
                    xtype: "button",
                    text: "设置用户升级积分",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        setGradeScore();
                    }
                },{
                    xtype: "button",
                    text: "设置用户每天登入奖励积分",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        setTdLoginScore();
                    }
                },{
                    xtype: "button",
                    text: "设置充值奖励",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        setRechangeTax();
                    }
                },{
                    xtype: "button",
                    text: "设置服务IP数量",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        setsvcIPs();
                    }
                },{
					xtype: "button",
                    text: "刷新服务IP数量",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
						Ext.Msg.show({
							title:"警告",
							icon:""
						});
					
					
                        Ext.Ajax.request({
							url:"./refreshsvcIPs.php",
							method:"POST",
							success:function(res){
								var oJson=Ext.decode(res.responseText);
								if(oJson.success){
									Ext.Msg.alert("提示","刷新完成");
								}else{
									Ext.Msg.alert("提示",oJson.msg);
								}
							}
						});
                    }
				},{
                    xtype: "button",
                    text: "配置支付宝",
                    anchor: "95%",
                    style: {
                        marginBottom: "5px"
                    },
                    handler: function() {
                        setMyAlipay();
                    }
                }]
			}]
        },{
            xtype: "panel",
            region: "center",
            id: "zhumianban",
            layout: "fit",
			items:[adminInfo],
            tbar: [{
                press: true,
                text: '用户信息',
                handler: function() {
                    this.setDisabled(true);
                    var p = Ext.getCmp("zhumianban");
                    var t = p.getTopToolbar();
                    t.items.each(function(item) {
                        item.setDisabled(false);
                    });
                    this.setDisabled(true);
                    if ( !! p.get(0)) {
                        p.get(0).destroy();
                        p.add(MbrMgt());
                        p.doLayout();
                    } else {
                        p.add(MbrMgt());
                        p.doLayout();
                    }
                }
            },{
                press: true,
                text: "交易信息",
                handler: function() {

                    var p = Ext.getCmp("zhumianban");
                    var t = p.getTopToolbar();
                    t.items.each(function(item) {
                        item.setDisabled(false);
                    });
                    this.setDisabled(true);
                    if ( !! p.get(0)) {
                        p.get(0).destroy();
                        p.add(OdrMgt());
                        p.doLayout();
                    } else {
                        p.add(OdrMgt());
                        p.doLayout();
                    }
                }
            },{
                press: true,
                text: "网址信息",
                handler: function() {
                    this.setDisabled(true);
                    var p = Ext.getCmp("zhumianban");
                    var t = p.getTopToolbar();
                    t.items.each(function(item) {
                        item.setDisabled(false);
                    });
                    this.setDisabled(true);
                    if ( !! p.get(0)) {
                        p.get(0).destroy();
                        p.add(UrlMgt());
                        p.doLayout();
                    } else {
                        p.add(UrlMgt());
                        p.doLayout();
                    }
                }
            },'->',{
				press:true,
				text:'<font style="color:red;font-weight:bold;">退&nbsp;&nbsp;出</font>',
				handler:function(){
					Ext.Msg.show({
						title:"提示",
						msg:"你确定退出吗？",
						buttons:{ok:"确定",cancel:"取消"},
						icon:Ext.Msg.WARNING,
						modal:true,
						fn:function(btn){
							if(btn=="ok"){
								Ext.Ajax.request({
									url:"./login.php?login=false"
								});
								Ext.getCmp('mainpanel').destroy();
							}else{
							}
						}
					});
				}
			}]
        }]
    });
}
var setAddUrlLimit = function() {
    var form = new Ext.form.FormPanel({
        id: "setAddUrlLimitForm",
        labelAlign: 'left',
        bodyStyle: 'padding:10px',
        defaultType: "textfield",
        labelWidth: 70,
        defaults: {
            anchor: "85%"
        },
        items: [{
            fieldLabel: "一级用户",
            style: {
                marginBottom: "5px"
            },
            name: "lv1",
        },
        {
            fieldLabel: "二级用户",
            style: {
                marginBottom: "5px"
            },
            name: "lv2",
        },
        {
            fieldLabel: "三级用户",
            style: {
                marginBottom: "5px"
            },
            name: "lv3",
        },
        {
            fieldLabel: "四级用户",
            style: {
                marginBottom: "5px"
            },
            name: "lv4",
        },
        {
            fieldLabel: "五级用户",
            style: {
                marginBottom: "5px"
            },
            name: "lv5",
        }]
    });
    var win = new Ext.Window({
        title: "设置用户添加网址数量上限",
        id: "setAddUrlLimitWin",
        width: 400,
        height: 240,
        shadow: false,
        modal: true,
        resizable: false,
        items: [form],
        draggable: false,
        buttonAlign: 'center',
        layout: 'fit',
        buttons: [{
            text: "确定",
            id: "comfire",
            handler: function() {
                Ext.getCmp("setAddUrlLimitForm").getForm().submit({
                    url: "setAddUrlLimit.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "设置成功！",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function() {
                        Ext.Msg.alert("提示", "设置成功！",
                        function() {
                            Ext.getCmp("setAddUrlLimitForm").getForm().reset();
                        });
                    }
                });
            }
        }]
    });
    win.show();
    setDefaultValue(form, "addurllimit");
}
var resetPwd = function() {
    var win = new Ext.Window({
        title: "修改密码",
        id: "resetpwdWin",
        width: 300,
        height: 200,
        shadow: false,
        modal: true,
        resizeable: false,
        draggable: false,
        buttonAlign: 'center',
        layout: 'fit',
        items: [{
            xtype: "form",
            border: false,
            labelAlign: 'left',
            labelWidth: 60,
            id: 'resetpwdForm',
            bodyStyle: 'padding:5px 0px 5px 20px',
            items: [{
                xtype: "label",
                id: "alert",
                anchor: "95%",
                stype: {
                    marginBottom: "5px"
                }
            },
            {
                xtype: "textfield",
                fieldLabel: "旧密码",
                id: "oldpwd",
                name: "oldpwd",
                anchor: "95%"
            },
            {
                xtype: "textfield",
                fieldLabel: "新密码",
                id: "newpwd1",
                name: "newpwd1",
                anchor: "95%"
            },
            {
                xtype: "textfield",
                fieldLabel: "确认密码",
                id: "newpwd2",
                name: "newpwd2",
                anchor: "95%"
            }]
        }],
        buttons: [{
            text: "确 认",
            handler: function() {
                var v1 = Ext.getCmp("newpwd1").getValue();
                var v2 = Ext.getCmp("newpwd2").getValue();
                if (v1 != v2) {
                    Ext.getCmp("alert").setText("密码输入不一致");
                } else {
                    Ext.getCmp("resetpwdForm").getForm().submit({
                        url: "./resetpwd.php",
                        method: "POST",
                        success: function() {
                            Ext.Msg.alert("提示", "密码修改成功！",
                            function() {
                                Ext.getCmp("resetpwdWin").destroy();
                            });
                        },
                        failure: function() {
                            Ext.Msg.alert("提示", "密码错误");
                        }
                    });
                }

            }
        }]
    });
    win.show();
}
var setMyAlipay = function() {
    var form = new Ext.form.FormPanel({
        id: "setMyAlipayForm",
        labelAlign: 'left',
        bodyStyle: 'padding:10px',
        defaultType: "textfield",
        labelWidth: 70,
        defaults: {
            anchor: "85%",
            allowBlank: false,
            msgTarget: "side",
            blankText: "必须的配置项"
        },
        items: [{
            fieldLabel: "合作者身份ID",
            style: {
                marginBottom: "5px"
            },
            name: "partner",
        },
        {
            fieldLabel: "安全检验码",
            style: {
                marginBottom: "5px"
            },
            name: "security_code",
        },
        {
            fieldLabel: "卖家支付宝帐号",
            style: {
                marginBottom: "5px"
            },
            name: "seller_email",
        },
        {
            fieldLabel: "收款方名称",
            style: {
                marginBottom: "5px"
            },
            name: "mainname",
        }]
    });
    var win = new Ext.Window({
        title: "配置支付宝",
        id: "setMyAlipayWin",
        width: 400,
        height: 220,
        shadow: false,
        modal: true,
        resizable: false,
        items: [form],
        draggable: false,
        buttonAlign: 'center',
        layout: 'fit',
        buttons: [{
            text: "确定",
            id: "comfire",
            handler: function() {
                form.getForm().submit({
                    url: "./setMyAlipay.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "设置成功！",
                        function() {
                            win.destroy();
                        });
                    },
                    failure: function() {
                        Ext.Msg.alert("提示", "设置成功！",
                        function() {
                            form.getForm().reset();
                        });
                    }
                });
            }
        }]
    });
    win.show();
}
var loginWin = new Ext.Window({
    id: 'loginWin',
    renderTo: document.body,
    width: 300,
    height: 170,
    shadow: false,
    modal: true,
    resizable: false,
    items: login_form,
    draggable: false,
    buttonAlign: 'center',
    closable: false,
    layout: 'fit',
    buttons: [{
        text: '确定',
        id: 'comfire',
        handler: function() {
            var form = Ext.getCmp('loginForm');
            form.getForm().submit({
                url: 'login.php',
                method: 'POST',
                success: function(f, a) {
                    loginWin.destroy();
                    init();
                },
                failure: function(f, a) {

                    Ext.Msg.show({
                        title: '错误',
                        icon: Ext.MessageBox.ERROR,
                        buttons: {
                            ok: '确定'
                        },
                        closable: false,
                        fn: function(btnID) {
                            f.reset();
                        },
                        msg: "您输入的账号或密码错误，请重新输入..."
                    });
                }
            });
        }
    },
    {
        text: '修改密码',
        id: 'resetpwd',
        handler: function() {
            resetPwd();
        }
    }]
});

Ext.onReady(function() {
    Ext.QuickTips.init();
    Ext.Ajax.request({
        url: './login.php',
        method: "POST",
        success: function(res) {
            var json = Ext.decode(res.responseText);
            if (json.success) {
                init();
            } else {
                loginWin.show();
            }
        }
    });
});

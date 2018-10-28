var UrlMgt = function() {

    var urlStore = new Ext.data.JsonStore({
        root: 'records',
        totalProperty: 'totalCount',
        autoDestroy: true,
        autoLoad: true,
        fields: [{
            name: "userid",
            mapping: "userid"
        },{
            name: "username",
            mapping: "username"
        },{
            name: "urlid",
            mapping: "urlid"
        },{
            name: "urlname",
            mapping: "urlname"
        },{
            name: "url",
            mapping: "url"
        },{
			name:"clickother",
			mapping:"clickother"
		},{
			name:"tdclick",
			mapping:"tdclick"
		},{
			name:"clickself",
			mapping:"clickself"
		},{
			name:"online",
			mapping:"online"
		}],

        url: './urlmgt.php'
    });

    var UrlGrid = new Ext.grid.GridPanel({
        id: 'urlgridPanel',
        loadmask:true,
        store: urlStore,
        columns: [new Ext.grid.RowNumberer({
            header: ''
        }), {
            header: "会员ID",
            dataIndex: 'userid',
            sortable: true,
            menuDisabled: true
        },{
            header: '会员名',
            dataIndex: 'username',
            menuDisabled: true
        },{
            header: '网址ID',
            dataIndex: 'urlid',
            sortable: true,
			width:60,
            menuDisabled: true
        },{
            header: '网址名称',
            dataIndex: 'urlname',
			width:85,
            menuDisabled: true
        },{
            header: '网址',
            dataIndex: 'url',
            width: 190,
            menuDisabled: true
        },{
			header: '今日分享',
            dataIndex: 'clickother',
            width: 60,
            menuDisabled: true
		},{
			header:"今日流量",
			dataIndex:"tdclick",
			width: 60,
            menuDisabled: true
		},{
			header:"累计流量",
			dataIndex:"clickself",
			width: 60,
            menuDisabled: true
		},{
			header:"&nbsp;&nbsp;&nbsp;&nbsp;",
			dataIndex:"online",
			width: 40,
            menuDisabled: true,
			renderer:function(v){return v==1 ? "<font color=#25A725>在线</font>" : "<font color=#A1A1A1>离线</font>";}
		}],
        bbar: new Ext.PagingToolbar({
            pageSize: 100,
            store: urlStore,
            displayInfo: true,
            displayMsg: "当前记录{0}-{1} 总共：{2}",
            emptyMsg: "没有交易记录"
        })
    });
    var addFilterUrl = function() {
        var addUrl = new Ext.Window({
            title: '添加过滤网址',

            width: 300,
            heigth: 400,
            modal: true,
            items: [{
                xtype: 'form',
                id: 'addFilterUrl',
                labelAlign: 'top',
                defaultType: 'textfield',
                bodyStyle: 'padding: 5px 0px 5px 15px',
                items: [{
                    fieldLabel: "网址",
                    name: 'url',
                    allowBlank: false,
                    anchor: '80%',
                    emptyText: '请不要输入http://'
                },{
                    fieldLabel: '过滤原因',
                    name: 'reason',
                    allowBlank: false
                },{
                    xtype: 'button',
                    text: '提&nbsp;&nbsp;交',
                    handler: function() {

                        Ext.getCmp("addFilterUrl").getForm().submit({
                            url: "addFilter.php",
                            method: 'POST',
                            success: function(form, action) {
                                Ext.Msg.show({
                                    title: '成功',
                                    icon: Ext.MessageBox.INFO,
                                    buttons: {
                                        ok: "确定"
                                    },
                                    closable: false,
                                    msg: action.result.msg,
                                    fn: function() {}
                                });

                            },
                            failure: function(form, action) {
                                Ext.Msg.show({
                                    title: '失败',
                                    icon: Ext.MessageBox.ERROR,
                                    buttons: {
                                        ok: "确定"
                                    },
                                    closable: false,
                                    msg: action.result.msg,
                                    fn: function() {}
                                });
                            }
                        });
                    }
                }]
            }]
        });
        addUrl.show();
    }
    var removeFilterUrl = function() {
        var removeUrl = new Ext.Window({
            title: '删除过滤网址',
            width: 300,
            height: 115,
            layout: 'fit',
            modal: true,
            items: [{
                xtype: 'form',
                id: 'removeUrlform',
                labelAlign: 'top',
                bodyStyle: 'padding: 5px 0px 5px 15px',
                defaultType: 'numberfield',
                border: false,
                items: [{
                    fieldLabel: '网址ID',
                    id: 'url',
                    name: 'url',
                    anchor: '70%'
                },
                {
                    xtype: 'button',
                    text: '确&nbsp;&nbsp;定',
                    handler: function() {
                        Ext.getCmp("removeUrlform").getForm().submit({
                            url: 'removeFilter.php',
                            method: "POST",
                            success: function(form, action) {
                                Ext.Msg.show({
                                    title: '成功',
                                    icon: Ext.MessageBox.INFO,
                                    buttons: {
                                        ok: '确定 '
                                    },
                                    closable: false,
                                    msg: action.result.msg,
                                    fn: function() {}
                                });
                            },
                            failure: function(form, action) {
                                Ext.Msg.show({
                                    title: '失败',
                                    icon: Ext.MessageBox.ERROR,
                                    buttons: {
                                        ok: '确定'
                                    },
                                    closable: false,
                                    msg: action.result.msg,
                                    fn: function() {}
                                });
                            }
                        });
                    }
                }]
            }]
        });
        removeUrl.show();
    }

    var showFilterUrls = function() {

        var filterUrlsGrid = new Ext.grid.GridPanel({
            id: 'filterUrlsGrid',
            minColumnWidth: 50,
            store: new Ext.data.JsonStore({
                root: 'records',
                totalProperty: 'totalCount',
                url: './loadFilterUrl.php',

                autoLoad: true,
                fields: [{
                    name: 'urlid',
                    mapping: "id"
                },{
                    name: 'url',
                    mapping: "url"
                },{
                    name: 'reason',
                    mapping: 'reason'
                }]
            }),
            columns: [new Ext.grid.RowNumberer({
                header: ''
            }), {
                header: '网址ID',
                dataIndex: 'urlid'
            },{
                header: '网址',
                width: 200,
                dataIndex: 'url'
            },{
                header: '过滤原因',
                width: 150,
                dataIndex: 'reason'
            }]
        });

        var filterUrls = new Ext.Window({
            title: '过滤网址',
            layout: 'fit',
            width: 500,
            height: 500,
            modal: true,
            items: [filterUrlsGrid]
        });

        filterUrls.show();
    }

    var UrlMgt = new Ext.Panel({
        tbar: [{
            press: true,
            text: '添加过滤网址',
            handler: addFilterUrl
        },'-', {
            press: true,
            text: '删除过滤网址',
            handler: removeFilterUrl
        },'-', {
            press: 'true',
            text: '查看过滤网址',
            handler: showFilterUrls
        }],
        id: 'urlmgtPanel',
        height: 600,
        layout: "fit",

        items: [UrlGrid]
    });

    return UrlMgt;
}

var dltUrlWin = function() {
    var form = new Ext.form.FormPanel({
        border: false,
        id: "dltUrlForm",
        bodyStyle: 'padding:5px 5px 5px 5px',
        labelWidth: 60,
        defaultType: "textfield",
        items: [{
            xtype: "box",
            html: "<div><font color=blue>提示：</font>以下信息仅需输入一项</div>",
            style: {
                marginBottom: "10px",
                marginLeft: "10px"
            }
        },{
            fieldLabel: "网址",
            name: "url",
            anchor: '95%'
        },{
            fieldLabel: "网址编号",
            name: "urlid",
            anchor: '95%'
        },{
            fieldLabel: "网站名称",
            name: "name",
            anchor: '95%'
        },{
            xtype: "button",
            text: "删 除",
            style: {
                marginLeft: '230px'
            },
            handler: function() {
                var f = Ext.getCmp("dltUrlForm").getForm().submit({
                    url: "./dltUrl.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "删除成功");
                    },
                    failue: function(f, a) {
                        Ext.Msg.alert("提示", a.result.msg);
                    }
                });
            }
        }]
    });
    var win = new Ext.Window({
        title: "删除网址",
        width: 300,
        autoHeight: true,

        items: [form]
    });
    win.show();
}

var modUrlWin = function() {
    var form = new Ext.form.FormPanel({
        border: false,
        id: "dltUrlForm",
        bodyStyle: 'padding:5px 5px 5px 5px',
        labelWidth: 60,
        defaultType: "textfield",
        items: [{
            fieldLabel: "网址",
            name: "url",
            anchor: '95%'
        },{
            fieldLabel: "网址编号",
            name: "urlid",
            anchor: '95%'
        },{
            fieldLabel: "网站名称",
            name: "name",
            anchor: '95%'
        },{
            fieldLabel: "弹窗比例",
            name: "poptax",
            anchor: '95%'
        },{
            fieldLabel: "目标网址",
            name: "turl",
            anchor: '95%'
        },{
            fieldLabel: "来源网址",
            name: "furls",
            anchor: '95%'
        },{
            xtype: "checkbox",
            boxLabel: "启用弹窗",
            name: "usepop",
            inputValue: 1
        },{
            xtype: "checkbox",
            boxLabel: "启用目标网址",
            name: "useturl",
            inputValue: 1
        },{
            xtype: "checkbox",
            boxLabel: "启用来源网址",
            name: "usefurl",
            inputValue: 1
        },{
            xtype: "button",
            text: "修  改",
            style: {
                marginLeft: '230px'
            },
            handler: function() {
                var f = Ext.getCmp("dltUrlForm").getForm().submit({
                    url: "./modUrl.php",
                    method: "POST",
                    success: function() {
                        Ext.Msg.alert("提示", "修改成功");
                    },
                    failue: function(f, a) {
                        Ext.Msg.alert("提示", a.result.msg);
                    }
                });
            }
        }]
    });
    var win = new Ext.Window({
        title: "修改网址信息",
        width: 300,
        autoHeight: true,

        items: [form]
    });
    win.show();
}
var getUrlInfo=function(){
	
}
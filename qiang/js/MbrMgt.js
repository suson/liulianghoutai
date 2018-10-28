
var MbrMgt=function(){
var mStore=new Ext.data.JsonStore({
root:"records",
totalProperty:"totalCount",
autoLoad:true,
autoDestroy:true,
fields:["userid","name","money","question","email","rtime","otime","ltime"],
url:"./getMemberInfo.php"
});
var mGrid=new Ext.grid.GridPanel({
title:"会员信息",
id:"memberInfo",
loadMask:true,
store:mStore,
columns:[new Ext.grid.RowNumberer({
header:''
}),{
header:"用户ID",
dataIndex:"userid",
sortable:true
},{
header:"用户名",
dataIndex:"name",
menuDisabled:true
},{
header:"余额",
sortable:true,
dataIndex:"money",
width:70,
renderer:cnMoney,
menuDisabled:true
},{
header:'密保问题',
dataIndex:"question",
menuDisabled:true,
width:100
},{
header:"email",
width:100,
dataIndex:"email",
renderer:render,
menuDisabled:true
},{
header:"注册时间",
width:100,
dataIndex:"rtime",
renderer:formatDate,
menuDisabled:true
},{
header:"最近交易时间",
width:100,
dataIndex:"otime",
renderer:formatDate,
menuDisabled:true
},{
header:"最近登入时间",
width:100,
dataIndex:"ltime",
renderer:formatDate,
menuDisabled:true
}],
bbar:new Ext.PagingToolbar({
pageSize:100,
store:mStore,
displayInfo:true,
displayMsg:"当前记录{0}-{1} 总共：{2}",
emptyMsg:"没有用户记录"
})
});
var aStore=new Ext.data.JsonStore({
root:"records",
totalProperty:"totalCount",
autoLoad:true,

autoDestroy:true,
fields:["userid","name","money","pmoney","lmoney","score"],
url:"./getAccountInfo.php"
});

var aGrid=new Ext.grid.GridPanel({
title:"用户账户信息",
id:"mgrid",
loadmask:true,
store:aStore,
columns:[new Ext.grid.RowNumberer({
header:""
}),{
header:"用户ID",
dataIndex:"userid",
sortable:true
},{
header:"用户名",
dataIndex:"name"
},{
header:"余额",
menuDisabled:true,
dataIndex:"money",
renderer:cnMoney 
},{
header:"赠送金额",
menuDisabled:true,
renderer:cnMoney,
dataIndex:"pmoney"
},{
header:"奖励金额",
menuDisabled:true,
renderer:cnMoney,
dataIndex:"lmoney"
},{
header:"积分",
dataIndex:"score",
menuDisabled:true,
sortable:true
}],
bbar:new Ext.PagingToolbar({
pageSize:100,
store:aStore,
displayInfo:true,
displayMsg:"当前记录{0}-{1},总共：{2}",
emptyMsg:"没用账户记录"
})

});

var mTpl=new Ext.XTemplate(
'<div ><table class="mInfo"><tbody><tr><th>用户ID：</th><td>{userid}</td><th>用户名：</th><td>{name}</td><th>最近登入时间：</th><td>{ltime}</td></tr></tbody></table>',
'<table class="aInfo"><tbody><tr><th>账户余额：</th><td>{money}</td><th>赠送金额：</th><td>{pmoney}</td><th>奖励金额：</th><td>{lmoney}</td></tr></tbody></table>',
'<table class="urlInfo"><tbody>',
'<tr><th>网址编号</th><th>网址</th><th>网址名称</th><th>代挂服务</th><th>优化服务</th></tr>',
'<tpl for="urls"><tr><td>{urlid}</td><td>{url}</td><td>{name}</td><td><tpl if="t==true">已开通</tpl><tpl if="t==false">未开通</tpl></td><td><tpl if="o==true">已开通</tpl><tpl if="o==false">未开通</tpl></td></tr></tpl>',
'</tbody></table>',
'</div>'
);
var  mPanel=new Ext.Panel({
title:"用户详细信息",
id:'mPanel',
layout:"fit",
tpl:mTpl,
bodyStyle:"padding:15px",
tbar:["用户名: ",
{
xtype:"textfield",
enableKeyEvents:true,
listeners:{
keyup:function(me,e){
var value=me.getValue();
if(13==e.getKey()){
Ext.Ajax.request({
url:'./queryMember.php',
method:"POST",
params:{q:value},
success:function(response,opt){
var json=Ext.decode(response.responseText,true);
if(json.success==true){
json.money=cnMoney(json.money);
json.pmoney=cnMoney(json.pmoney);
json.lmoney=cnMoney(json.lmoney);
Ext.getCmp("mPanel").update(json);
}else
alert("Failue");
},
failure:function(){
alert("Failure");
}
});
}
}
}
}]
});

var MbrMgt=new Ext.TabPanel({
id:'MbrMgtpanel',
height:600,
activeTab:0,
items:[mGrid,aGrid,mPanel]
});

return MbrMgt;
}
var dltMWin=function(){
var form=new Ext.form.FormPanel({
border:false,
id:"dltmForm",
bodyStyle:'padding:5px 5px 5px 5px',
labelWidth:60,
items:[{
xtype:"textfield",
fieldLabel:'用户名',
name:'user',
anchor:"95%"
},{
xtype:"button",
text:"删 除",
style:{marginLeft:"230px"},
handler:function(){
var f=Ext.getCmp("dltmForm").getForm().submit({
url:"./deleteMember.php",
method:"POST",
success:function(){
Ext.Msg.alert("提示","删除成功");
},
failue:function(f,a){
Ext.Msg.alert("提示",a.result.msg);
}
});
}
}]
});

var win=new Ext.Window({
title:"删除用户",
width:300,
autoHeight:true,
items:[form]
});
win.show();
}

var modAcntWin=function(){
var form=new Ext.form.FormPanel({
border:false,
id:"modAcntForm",
bodyStyle:'padding:5px 5px 5px 5px',
labelWidth:60,
items:[{
xtype:"textfield",
fieldLabel:"用户名",
name:"user",
anchor:'95%'
},{
xtype:"textfield",
fieldLabel:"资金余额",
name:"money",
anchor:'95%'
},{
xtype:"textfield",
fieldLabel:"赠送金额",
name:"pmoney",
anchor:'95%'
},{
xtype:"textfield",
fieldLabel:"奖励金额",
name:"lmoney",
anchor:'95%'
},{
xtype:"button",
text:"添  加",
style:{marginLeft:'230px'},
handler:function(){
var f=Ext.getCmp("modAcntForm").getForm().submit({
url:"./modAccount.php",
method:"POST",
success:function(){
Ext.Msg.alert("提示","添加成功");
},
failure:function(f,a){
Ext.Msg.alert("提示",a.result.msg);
}
});
}
}]
});

var win=new Ext.Window({
title:"修改用户账户信息",
width:300,
autoHeight:true,
items:[form]
});
win.show();
}

var modUserWin=function(){
var form=new Ext.form.FormPanel({
border:false,
id:"modUserForm",
bodyStyle:'padding:5px 5px 5px 5px',
labelWidth:60,
defaultType:"textfield",
items:[{
fieldLabel:"用户名",
name:"user",
anchor:'95%'
},{
fieldLabel:"用户密码",
name:"pwd",
anchor:'95%'
},{
fieldLabel:"E-mail",
name:"email",
anchor:'95%'
},{
fieldLabel:"密码保护问题",
name:"question",
anchor:'95%'
},{
fieldLabel:"秘密保护答案",
name:"answer",
anchor:'95%'
},{
xtype:"button",
text:"修 改",
style:{marginLeft:'230px'},
handler:function(){
var f=Ext.getCmp("modUserForm").getForm().submit({
url:"./modUser.php",
method:"POST",
success:function(){
Ext.Msg.alert("提示","修改成功");
},
failue:function(f,a){
Ext.Msg.alert("提示",a.result.msg);
}
});
}
}]
});
var win=new Ext.Window({
title:"修改用户信息",
width:300,
autoHeight:true,
items:[form]
});
win.show();
}
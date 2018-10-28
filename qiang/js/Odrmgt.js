
var OdrMgt=function(){


var rechangeStore=new Ext.data.JsonStore({
root:'records',
totalProperty:'totalCount',
 

fields:[{name:"orderid",mapping:"orderid"},{name:'userid',mapping:'userid'},'name','otime','val','pm','lm',"bae"],
url:'./getRechange.php',
listeners:{
 
}
});

var expStore=new Ext.data.JsonStore({
root:'records',
totalProperty:'totalCount',

/*{name:"userid",mapping:"userid"},
 *{name:"name",mapping:"name"},
 *{name:"otime",mappaing:"otime"},
 *{name:"val",mapping:"val"},
 *{name:"bae",mapping:"bae"}
 */
fields:[{name:"orderid",mapping:'orderid'},
{name:"userid",mapping:"userid"},
{name:"name",mapping:"name"},
{name:"otime",mappaing:"otime"},
{name:"val",mapping:"val"},
{name:"bae",mapping:"bae"}],
url:'./getExp.php'
});
var expGrid=new Ext.grid.GridPanel({
title:'会员消费记录',
store:expStore,
loadMask:true,
listeners:{
activate:function(){expStore.load({params:{start:0, limit:25}});}
},
columns:[new Ext.grid.RowNumberer({header:''}),
{
header:'交易单号',
dataIndex:"orderid"
},{
header:"会员ID",
dataIndex:'userid'
},{
header:"会员名",
dataIndex:'name'
},{
header:'交易时间',
dataIndex:"otime",
renderer:formatDate,
width:150
},{
header:'交易金额',
dataIndex:"val",
renderer:cnMoney
},{
header:'余额',
dataIndex:'bae',
renderer:cnMoney
}],
bbar:new Ext.PagingToolbar({
pageSize:100,
store:expStore,
displayInfo:true,
displayMsg:"当前记录{0}-{1} 总共：{2}",
emptyMsg:"没有交易记录"
})
});

var rechangeGrid=new Ext.grid.GridPanel({
title:'会员充值记录',
store:rechangeStore,
loadMask:true,
listeners:{
render:function(){rechangeStore.load({params:{start:0, limit:25}});}
},
columns:[new Ext.grid.RowNumberer({header:''}),
{
header:'交易单号',
dataIndex:"orderid"
},{
 
header:'会员ID',
dataIndex:'userid'
},{
 
header:'会员名',
dataIndex:'name'
},{
 
header:'交易时间',
dataIndex:'otime',
width:130,
renderer:formatDate
},{
 
header:'交易金额',
dataIndex:"val",
renderer:cnMoney,
width:80
},{
 
header:'赠送金额',
dataIndex:'pm',
renderer:cnMoney,
width:80
},{
 
header:'奖励金额',
dataIndex:'lm',
width:80,
renderer:cnMoney
},{
header:"余额",
dataIndex:"bae",
width:80,
renderer:cnMoney
}],
bbar:new Ext.PagingToolbar({
pageSize:100,
store:rechangeStore,
displayInfo:true,
displayMsg:"当前记录{0}-{1} 总共：{2}",
emptyMsg:"没有交易记录"
})
});

var queryStore={
rechangeStore: new Ext.data.JsonStore({
root:'records',

totalProperty:"totalCount",
fields:[{name:'orderid',mapping:'orderid'},
        {name:'userid',mapping:"userid"},
        {name:'name',mapping:"name"},
        {name:'otime',mapping:"otime"},
        {name:'val',mapping:"val"},
        {name:'pm',mapping:"pm"},
        {name:'lm',mapping:"lm"},
        {name:'bae',mapping:"bae"}],
data:{totalCount:0,records:[]}
}),
expStore: new Ext.data.JsonStore({
root:'records',
totalProperty:'totalCount',

fields:[{name:'orderid',mapping:"orderid"},
        {name:'userid',mapping:"userid"},
        {name:'name',mapping:"name"},
        {name:'otime',mapping:"otime"},
        {name:'val',mapping:"val"},
        {name:'bae',mapping:"bae"}],
data:{totalCount:0,records:[]}
})
};
var queryRSGrid=new Ext.grid.GridPanel({
title:'充值记录',
id:'queryRS',
height:500,
store:queryStore.rechangeStore,
columns:[new Ext.grid.RowNumberer({header:''}),
{
menuDisabled:true,
header:"交易单号",
dataIndex:"orderid"
},{
menuDisabled:true,
header:"会员ID",
dataIndex:"userid"
},{
header:"会员名",
menuDisabled:true,
dataIndex:"name"
},{
header:"交易时间",
menuDisabled:true,
width:130,
dataIndex:"otime"
},{
header:"交易金额",
menuDisabled:true,
dataIndex:"val"
},{
header:"赠送金额",
dataIndex:"pm",
menuDisabled:true,
width:80
},{
header:'奖励金额',
dataIndex:'lm',
menuDisabled:true,
width:80
},{
header:'余额',
menuDisabled:true,
dataIndex:'bae'
}]
});
var queryExpGrid=new Ext.grid.GridPanel({
title:'消费记录',
id:'queryExp',
store:queryStore.expStore,
height:600,
columns:[new Ext.grid.RowNumberer({header:''}),
{
header:"交易单号",
menuDisabled:true,
dataIndex:'orderid'
},{
header:'会员ID',
menuDisabled:true,
dataIndex:"userid"
},{
header:'会员名',
menuDisabled:true,
dataIndex:"name"
},{
header:'交易时间',
menuDisabled:true,
width:150,
dataIndex:"otime"
},{
header:'交易金额',
menuDisabled:true,
dataIndex:"val"
},{
header:"余额",
menuDisabled:true,
dataIndex:"bae"
}]

});

var queryPanel=new Ext.Panel({
title:'查询交易记录',
layout:'form',
items:[{
xtype:'form',
id:'queryForm',
items:[{
layout:'column',
anchor:'100%',
bodyStyle:'padding:3px 0px 2px 5px',
items:[{
xtype:'label',
text:'查询方式',
columnWidth:.15
},{
xtype:'radio',
columnWidth: .15,
checked:true,
boxLabel:"会员ID",
name:"width",
inputValue:'id'
},{
columnWidth: .15,
xtype:'radio',
fieldLabel:'',
labelSeparator:'',
boxLabel:'会员名',
name:'width',
inputValue:'name'
},{
columnWidth:.3,
xtype:'textfield',
fieldLabel:"",
labelSeparator:'',
name:'query'
},{
colunmWidth:.08,
xtype:'label',
html:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
},{
columnWidth:.1,
xtype:'button',
text:'查询',
handler:function(){
Ext.getCmp("queryForm").getForm().submit({
url:'./query.php',
method:"POST",
success:function(form,action){
Ext.getCmp("queryRS").getStore().loadData(action.result.RS);
Ext.getCmp("queryExp").getStore().loadData(action.result.Exp);

},
failure:function(form,action){
Ext.Msg.show({
title:'失败',
icon:Ext.MessageBox.ERROR,
buttons:{ok:'确定'},
closable:false,
fn:function(){
form.reset();
},
msg:'查询出错，请重新查询....'
});
}
});
}
}]
}]

},{
xtype:"tabpanel",
activeItem:0,
height:'500',
border:false,
items:[queryRSGrid,queryExpGrid]
}]
});
var rechange=new Ext.TabPanel({
id:'rechange',
shadow:false,
activeItem:0,
border:false,
items:[rechangeGrid,expGrid,queryPanel]
});

/*
new Ext.Window({
title:'交易记录查询',
layout:'fit',
width:gWidth,
height:600,
resizable:false,
draggable:false,
shadow:false,
modal:true,
x:50,
y:10,
items:[rechange]
}).show();
*/

return rechange;
}

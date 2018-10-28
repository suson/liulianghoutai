var formatDate=function(s){
if(s!==null){
var dt=new Date();
dt=Date.parseDate(s,"Y-m-d G:i:s");
return String.format('<div ext:qtip="{0}">{1}</div>',s,dt.format("Y-m-d"));
}
return '<div ext:qtip=" 无记录 ">无记录</div>';
}
var render=function(v){
return String.format('<div  ext:qtip=" {0} ">{1}</div>',v,v);
}
var cnMoney=function(v){
v=v/1;
if(Ext.isNumber(v)){
var r=Ext.util.Format.number(v/100,"0,000.00");
return r+" 元";
}else{
return v/100+" 元";
}
}
var setDefaultValue=function(form,cfg){
Ext.Ajax.request({
url:"./getCfg.php?cfg="+cfg,
method:"GET",
success:function(res){
var cfg=Ext.decode(res.responseText);
if(cfg !=null){
form.items.each(function(item,index,len){
try{
item.setValue(cfg[index]);
}catch(e){}
return true;
});
}
}
});

}
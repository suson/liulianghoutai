(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-3293"],{"8REc":function(t,e,s){},"9LED":function(t,e,s){"use strict";s.r(e);var i={components:{urlList:s("zKop").a}},r=s("KHd+"),a=Object(r.a)(i,function(){var t=this.$createElement;return(this._self._c||t)("url-list",{attrs:{menuType:"baidu_task",subMenuType:"baidu_speed"}})},[],!1,null,null,null);a.options.__file="speed.vue";e.default=a.exports},"gDS+":function(t,e,s){t.exports={default:s("oh+g"),__esModule:!0}},"hh5+":function(t,e,s){"use strict";var i=s("8REc");s.n(i).a},"oh+g":function(t,e,s){var i=s("WEpk"),r=i.JSON||(i.JSON={stringify:JSON.stringify});t.exports=function(t){return r.stringify.apply(r,arguments)}},sZnh:function(t,e,s){"use strict";s.d(e,"d",function(){return a}),s.d(e,"b",function(){return n}),s.d(e,"g",function(){return o}),s.d(e,"c",function(){return l}),s.d(e,"h",function(){return u}),s.d(e,"e",function(){return c}),s.d(e,"f",function(){return d}),s.d(e,"a",function(){return f});var i=s("t3Un"),r={url:"/service/urlcore/webreg.php",method:"post",data:{}};function a(t){return r.data.f=36,r.data.i=t,Object(i.a)(r)}function n(t){return r.data.f=38,r.data.i=t,Object(i.a)(r)}function o(t){return r.data.f=37,r.data.i=t,Object(i.a)(r)}function l(t){return r.data.f=39,r.data.i=t,Object(i.a)(r)}function u(t){return r.data.f=35,r.data.i=t,Object(i.a)(r)}function c(t){return r.data.f=31,r.data.i=t,Object(i.a)(r)}function d(t){return r.data.f=32,r.data.i=t,Object(i.a)(r)}function f(t){return r.data.f=33,r.data.i=t,Object(i.a)(r)}},zKop:function(t,e,s){"use strict";var i=s("gDS+"),r=s.n(i),a=s("sZnh"),n={props:{menuType:{type:String,default:"baidu_task"},subMenuType:{type:String,default:"baidu_speed"}},data:function(){return{trusteeServerIP:{101:"1000IP,1.60元/天",103:"3000IP,2.60元/天",106:"6000IP,5.20元/天",110:"10000IP,9.80元/天"},listLoading:!0,labelPosition:"right",centerDialogVisible:!1,TrusteeDialogVisible:!1,listData:[],listTotal:0,listPage:1,listPageSize:10,multipleSelection:[],substituteForm:{urlid:0,day:"30",svcid:"103",nowpay:"1",odrid:0,etime:""},disabled:!1,substituteRet:{etime:"",allmoney:0,paymoney:0,pday:0},odrInfo:{},formLabelWidth:"150px"}},created:function(){this.fetchData()},updated:function(){},computed:{reversedMessage:function(){return this.formLabelWidth.split("").reverse().join("")}},watch:{"substituteForm.day":function(t,e){if(t>365||t<1)return this.$message({message:"请输入有效的服务时间（1-365）",type:"error",duration:3e3}),this.substituteForm.day=30,!1;this.getService()},"substituteForm.svcid":function(t,e){this.getService()},"substituteForm.nowpay":function(t,e){this.getService()}},filters:{urlStatusName:function(t){var e="";switch(t.toString()){case"0":e="离线";break;case"1":e="在线"}return e},shareStatusName:function(t){var e="";switch(t.toString()){case"0":e="停止优化";break;case"1":e="优化中"}return e},shareBtnName:function(t){var e="";switch(t.toString()){case"0":e="开始优化";break;case"1":e="停止优化"}return e},filterUrl:function(t,e){switch(t=t.split("|"),e){case"url":return t[0]||"";case"keyword":return t[1]||""}},getTrusteeServiceInfo:function(t){return this.getTrusteeServiceInfo(t)},showTrusteeService:function(t){return this.getTrusteeServiceInfo(t).showTitle||"无"},showOdrStatus:function(t){var e="";switch(parseInt(t)){case 1:e="按天付费";break;case 2:e="一次性付费"}return e},showSvcid:function(t){var e="";switch(parseInt(t)){case 101:e="1000IP";break;case 102:e="2000IP";break;case 103:e="3000IP";break;case 106:e="6000IP";break;case 110:e="10000IP";break;case 115:e="15000IP";break;case 120:e="20000IP";break;default:e="3000IP"}return e}},methods:{getTrusteeServiceInfo:function(t){var e={};for(var s in t)10!=t[s].svcid&&(e=t[s]);if(e.odrid){var i=parseInt(e.sday),r="剩",a=Math.floor(i/1440),n=Math.floor((i-1440*a)/60);return r+=a+"天"+n+"时"+(i-(1440*a+60*n))+"分",e.showTitle=r,e}return!1},toggleSelection:function(t){var e=this;t?t.forEach(function(t){e.$refs.multipleTable.toggleRowSelection(t)}):this.$refs.multipleTable.clearSelection()},handleSelectionChange:function(t){this.multipleSelection=t},fetchData:function(){var t=this;this.listLoading=!1;var e={type:this.subMenuType,page:this.listPage,page_size:this.listPageSize};e=r()(e),Object(a.d)(e).then(function(e){t.listData=e.urls,t.listLoading=!1,t.listTotal=e.total})},delTip:function(){var t=this,e=[];for(var s in this.multipleSelection)e.push(this.multipleSelection[s].urlid);if(e.length<1)return this.$message({message:"请选择任务",type:"error",duration:3e3}),!1;this.$confirm("删除则不可恢复，确定删除？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){t.del(e)}).catch(function(){})},del:function(t){var e=this,s={ids:t};s=r()(s),Object(a.b)(s).then(function(s){if(s.error>-1){for(var i in t)for(var r in e.listData)if(t[i]==e.listData[r].urlid){e.listData.splice(r,1);break}e.$message({type:"success",message:"删除成功!"})}else e.$message({message:s.msg,type:"error",duration:3e3})})},addTask:function(){this.$router.push("/"+this.menuType+"/add")},operateOneItem:function(t,e){var s=0;s=0==e?1:0,this.$refs.multipleTable.clearSelection(),this.$refs.multipleTable.toggleRowSelection(t,!0),this.operate(s)},operate:function(t){var e=this,s=[];for(var i in this.multipleSelection)s.push(this.multipleSelection[i].urlid);if(s.length<1)return this.$message({message:"请选择任务",type:"error",duration:3e3}),!1;i={free:t,ids:s};i=r()(i),Object(a.g)(i).then(function(i){if(i.error>-1){for(var r in s)for(var a in e.listData)if(s[r]==e.listData[a].urlid){e.listData[a].free=t;break}e.$message({type:"success",message:"操作成功!"})}else e.$message({message:i.msg,type:"error",duration:3e3})})},edit:function(t){this.$router.push({path:"/"+this.menuType+"/edit/"+t.urlid})},showTrustee:function(t){var e=this.getTrusteeServiceInfo(t.odrs);this.odrInfo=e,this.TrusteeDialogVisible=!0},openTrustee:function(t,e){if(this.$refs.multipleTable.clearSelection(),this.$refs.multipleTable.toggleRowSelection(t,!0),this.substituteForm.urlid=t.urlid,1==e){var s=this.getTrusteeServiceInfo(t.odrs);this.disabled=!0,this.substituteForm.odrid=s.odrid,this.substituteForm.etime=s.etime}else this.disabled=!1;this.getService(),this.centerDialogVisible=!0},closeTrusteeTip:function(){var t=this;this.$confirm("您确认要退订网址代挂服务吗？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){t.closeTrustee()}).catch(function(){})},closeTrustee:function(){var t=this,e=this.odrInfo;e=r()(e),Object(a.a)(e).then(function(e){e.error>-1?(t.$message({message:"退订成功",type:"success",duration:3e3}),t.TrusteeDialogVisible=!1,t.fetchData()):t.$message({message:e.msg,type:"error",duration:3e3})})},getService:function(){var t=this,e=this.substituteForm;e=r()(e),Object(a.e)(e).then(function(e){e.error>-1?(t.substituteRet.etime=e.etime,t.substituteRet.allmoney=e.allmoney/100,t.substituteRet.paymoney=e.paymoney/100,t.substituteRet.pday=e.pday):t.$message({message:e.msg,type:"error",duration:3e3})})},submitTrustee:function(){var t=this,e=this.substituteForm;e=r()(e),Object(a.f)(e).then(function(e){e.error>-1?(t.$message({message:"开通成功",type:"success",duration:3e3}),t.fetchData()):t.$message({message:e.msg||"开通失败",type:"error",duration:3e3}),t.centerDialogVisible=!1})},handleCurrentChange:function(t){this.listPage=t,this.fetchData()}}},o=(s("hh5+"),s("KHd+")),l=Object(o.a)(n,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"app-container"},[s("el-row",[s("el-col",{attrs:{span:24}},[s("div",{staticClass:"grid-content bg-purple"},[s("el-button",{attrs:{size:"small",type:"primary",plain:""},on:{click:function(e){t.addTask()}}},[t._v("创建新任务")])],1)])],1),t._v(" "),s("el-row",[s("el-col",{attrs:{span:24}},[s("div",{staticClass:"grid-content bg-purple"},[s("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],ref:"multipleTable",staticStyle:{width:"100%"},attrs:{"empty-text":"暂无数据",border:"",data:t.listData,"tooltip-effect":"dark"},on:{"selection-change":t.handleSelectionChange}},[s("el-table-column",{attrs:{type:"selection",width:"50"}}),t._v(" "),s("el-table-column",{attrs:{label:"id",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(e.row.urlid))]}}])}),t._v(" "),s("el-table-column",{attrs:{label:"标题",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(e.row.name))]}}])}),t._v(" "),s("el-table-column",{attrs:{label:"网址","show-overflow-tooltip":""},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(t._f("filterUrl")(e.row.turl,"url")))]}}])}),t._v(" "),s("el-table-column",{attrs:{label:"关键字",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(t._f("filterUrl")(e.row.turl,"keyword")))]}}])}),t._v(" "),s("el-table-column",{attrs:{label:"今日分享",width:"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(e.row.tdclick))]}}])}),t._v(" "),s("el-table-column",{attrs:{label:"状态",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(t._f("urlStatusName")(e.row.online))+" "),s("el-button",{attrs:{size:"mini",type:"text"},on:{click:function(s){t.operateOneItem(e.row,e.row.free)}}},[t._v(t._s(t._f("shareBtnName")(e.row.free)))])]}}])}),t._v(" "),s("el-table-column",{attrs:{label:"网址代挂服务",width:"220"},scopedSlots:t._u([{key:"default",fn:function(e){return[t.getTrusteeServiceInfo(e.row.odrs)?t._e():s("el-button",{attrs:{size:"mini"},on:{click:function(s){t.openTrustee(e.row)}}},[t._v("立即开通")]),t._v(" "),t.getTrusteeServiceInfo(e.row.odrs)?s("el-button",{attrs:{size:"mini",type:"text"},on:{click:function(s){t.showTrustee(e.row,1)}}},[t._v(t._s(t.getTrusteeServiceInfo(e.row.odrs).showTitle))]):t._e(),t._v(" "),t.getTrusteeServiceInfo(e.row.odrs)?s("el-button",{attrs:{size:"mini"},on:{click:function(s){t.openTrustee(e.row,1)}}},[t._v("续费")]):t._e()]}}])}),t._v(" "),s("el-table-column",{attrs:{label:"操作",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[s("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(s){t.edit(e.row)}}},[t._v("编辑")])]}}])})],1),t._v(" "),s("div",{staticStyle:{"margin-top":"20px"}},[s("el-col",{attrs:{span:6}},[s("el-button-group",[s("el-button",{attrs:{size:"small",type:"primary",icon:"el-icon-remove-outline"},on:{click:function(e){t.operate(0)}}},[t._v("停止")]),t._v(" "),s("el-button",{attrs:{size:"small",type:"primary",icon:"el-icon-caret-right"},on:{click:function(e){t.operate(1)}}},[t._v("开始")]),t._v(" "),s("el-button",{attrs:{size:"small",type:"danger",icon:"el-icon-delete"},on:{click:function(e){t.delTip()}}},[t._v("删除")])],1)],1),t._v(" "),s("el-col",{attrs:{span:18}},[s("div",{staticStyle:{float:"right"}},[s("el-pagination",{attrs:{background:"",layout:"total,prev, pager, next","page-size":t.listPageSize,total:t.listTotal},on:{"current-change":t.handleCurrentChange}})],1)])],1)],1)])],1),t._v(" "),s("el-dialog",{attrs:{title:"龙卷风软件-网址代挂服务",visible:t.centerDialogVisible,width:"60%"},on:{"update:visible":function(e){t.centerDialogVisible=e}}},[s("el-col",{attrs:{span:24}},[s("p",[t._v("开通步骤：")])]),t._v(" "),s("el-form",{attrs:{model:t.substituteForm,"label-position":t.labelPosition}},[s("el-form-item",{attrs:{label:"1、请选择代挂流量：","label-width":t.formLabelWidth}},[s("el-select",{attrs:{placeholder:"请选择代挂流量",disabled:t.disabled},model:{value:t.substituteForm.svcid,callback:function(e){t.$set(t.substituteForm,"svcid",e)},expression:"substituteForm.svcid"}},t._l(t.trusteeServerIP,function(t,e){return s("el-option",{key:e,attrs:{label:t,value:e}})}))],1),t._v(" "),s("el-form-item",{attrs:{label:"2、请输入服务时间：","label-width":t.formLabelWidth}},[s("el-col",{attrs:{span:5}},[s("el-input",{model:{value:t.substituteForm.day,callback:function(e){t.$set(t.substituteForm,"day",t._n(e))},expression:"substituteForm.day"}})],1),t._v(" "),s("el-col",{attrs:{span:19}},[s("span",[t._v("天(范围:1-365天)")])])],1),t._v(" "),s("el-form-item",{attrs:{label:"到期时间：","label-width":t.formLabelWidth}},[s("el-col",{attrs:{span:24}},[s("span",{staticClass:"red"},[t._v(t._s(t.substituteRet.etime))])])],1),t._v(" "),s("el-form-item",{attrs:{label:"3、请选择付费方式：",prop:"resource","label-width":t.formLabelWidth}},[s("el-radio-group",{attrs:{disabled:t.disabled},model:{value:t.substituteForm.nowpay,callback:function(e){t.$set(t.substituteForm,"nowpay",e)},expression:"substituteForm.nowpay"}},[s("el-radio",{attrs:{label:"1"}},[t._v("一次性付费")]),t._v(" "),s("el-radio",{attrs:{label:"0"}},[t._v("按天付费")])],1)],1)],1),t._v(" "),s("el-col",{attrs:{span:24}},[s("p",{},[t._v("支付预算("),s("span",{staticClass:"red"},[t._v("当日不足1天按剩余时间计费")]),t._v(")：")]),t._v(" "),s("p",[t._v("当前支付："),s("span",{staticClass:"red"},[t._v(t._s(t._f("formatCurrency")(t.substituteRet.allmoney)))]),t._v(" 元，共需支付："),s("span",{staticClass:"red"},[t._v(t._s(t._f("formatCurrency")(t.substituteRet.paymoney)))]),t._v(" 元，赠送天数："),s("span",{staticClass:"red"},[t._v(t._s(t.substituteRet.pday))]),t._v(" 天")]),t._v(" "),s("p",[s("span",{staticClass:"red"},[t._v("注意：")])]),t._v(" "),s("p",{staticClass:"red"},[t._v("1、只有“按天付费”才可以在中途取消网址代挂服务； 2、取消网址代挂服务在当天凌晨才正式生效； ")])]),t._v(" "),s("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[s("el-button",{on:{click:function(e){t.centerDialogVisible=!1}}},[t._v("取 消")]),t._v(" "),s("el-button",{attrs:{type:"primary"},on:{click:t.submitTrustee}},[t._v("确 定")])],1)],1),t._v(" "),s("el-dialog",{attrs:{title:"网址代挂服务",visible:t.TrusteeDialogVisible,width:"30%"},on:{"update:visible":function(e){t.TrusteeDialogVisible=e}}},[s("p",[t._v("交易订单号："),s("span",[t._v(t._s(t.odrInfo.odrid))])]),t._v(" "),s("p",[t._v("费用支付方式："),s("span",[t._v(t._s(t._f("showOdrStatus")(t.odrInfo.status)))])]),t._v(" "),s("p",[t._v("网址代挂价格："),s("span",[t._v(t._s(t._f("formatCurrency")(t.odrInfo.dayprice/100))+" 元/天")])]),t._v(" "),s("p",[t._v("网址代挂流量："),s("span",[t._v(t._s(t._f("showSvcid")(t.odrInfo.svcid)))])]),t._v(" "),s("p",[t._v("代挂开始时间："),s("span",[t._v(t._s(t.odrInfo.btime))])]),t._v(" "),s("p",[t._v("代挂结束时间："),s("span",[t._v(t._s(t.odrInfo.etime))])]),t._v(" "),1==t.odrInfo.status?s("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[s("el-button",{attrs:{type:"primary"},on:{click:t.closeTrusteeTip}},[t._v("退订代挂服务")])],1):t._e()])],1)},[],!1,null,"4030b635",null);l.options.__file="urlList.vue";e.a=l.exports}}]);
(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-c953"],{PKT8:function(t,e,r){},YNWN:function(t,e,r){"use strict";var n=r("PKT8");r.n(n).a},"gDS+":function(t,e,r){t.exports={default:r("oh+g"),__esModule:!0}},"oh+g":function(t,e,r){var n=r("WEpk"),a=n.JSON||(n.JSON={stringify:JSON.stringify});t.exports=function(t){return a.stringify.apply(a,arguments)}},sZnh:function(t,e,r){"use strict";r.d(e,"d",function(){return u}),r.d(e,"b",function(){return i}),r.d(e,"g",function(){return s}),r.d(e,"c",function(){return o}),r.d(e,"h",function(){return l}),r.d(e,"e",function(){return c}),r.d(e,"f",function(){return p}),r.d(e,"a",function(){return d});var n=r("t3Un"),a={url:"/service/urlcore/webreg.php",method:"post",data:{}};function u(t){return a.data.f=36,a.data.i=t,Object(n.a)(a)}function i(t){return a.data.f=38,a.data.i=t,Object(n.a)(a)}function s(t){return a.data.f=37,a.data.i=t,Object(n.a)(a)}function o(t){return a.data.f=39,a.data.i=t,Object(n.a)(a)}function l(t){return a.data.f=35,a.data.i=t,Object(n.a)(a)}function c(t){return a.data.f=31,a.data.i=t,Object(n.a)(a)}function p(t){return a.data.f=32,a.data.i=t,Object(n.a)(a)}function d(t){return a.data.f=33,a.data.i=t,Object(n.a)(a)}},siFS:function(t,e,r){"use strict";var n=r("gDS+"),a=r.n(n),u=r("sZnh"),i={props:{menuType:{type:String,default:"baidu_task"},subMenuType:{type:String,default:"baidu_speed"}},created:function(){this.$route.meta.isEdit&&(this.ruleForm.urlid=this.$route.params.id,this.getInfo(this.ruleForm.urlid),this.save_tip="保存")},data:function(){return{save_tip:"立即创建",type:this.subMenuType,ruleForm:{id:"",name:"",keyword:"",url:"",desc:""},rules:{name:[{required:!0,message:"请输入任务备注",trigger:"blur"},{min:3,max:50,message:"长度在 3 到 50 个字符",trigger:"blur"}],url:[{required:!0,message:"请输入网址",trigger:"blur"},{validator:function(t,e,r){/^(?=^.{3,255}$)(http(s)?:\/\/)?(www\.)?[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+(:\d+)*(\/\w+\.\w+)*([\?&amp;]\w+=\w*)*$/.test(e)?r():r(new Error("请输入正确的网址"))},trigger:"blur"}]}}},methods:{submitForm:function(t){var e=this;this.$refs[t].validate(function(t){if(!t)return console.log("error submit!!"),!1;e.save()})},resetForm:function(t){this.$refs[t].resetFields()},goBack:function(){window.history.length>1?this.$router.go(-1):this.$router.push("/"+this.menuType+"/speed")},getInfo:function(t){var e=this,r={urlid:t};r=a()(r),Object(u.c)(r).then(function(t){e.ruleForm=t.data})},save:function(){var t=this,e=this.ruleForm;e.type=this.type,e=a()(e),Object(u.h)(e).then(function(e){e.error>-1&&(t.$message({type:"success",message:"操作成功!"}),t.$router.push("/"+t.menuType+"/speed"))})}}},s=(r("YNWN"),r("KHd+")),o=Object(s.a)(i,function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("el-row",[r("el-col",{attrs:{span:24}},["baidu_speed"==t.subMenuType?r("div",{staticClass:"grid-content bg-purple"},[r("label",[t._v("简介")]),t._v(" "),r("p",[t._v("1：百度排名任务是快速提升网站在百度上关键字排名，请添加百度前50名的关键字！")]),t._v(" "),r("p",[t._v("2：任务总数代表这个任务一共能做多少次，每天次数代表每天可以做的数量。")]),t._v(" "),r("p",[t._v("3：每天次数建议是关键字指数的十分之一左右，具体多少根据排名高低可以适当加减。"),r("br")]),t._v(" "),r("p",[t._v("4：百度排名功能需要长期使用才会见效，如果只是偶尔使用效果很小。")]),t._v(" "),r("p",[r("br")]),t._v(" "),r("label",[t._v("基础设置")])]):t._e(),t._v(" "),"sogou_ranking"==t.subMenuType?r("div",{staticClass:"grid-content bg-purple"},[r("label",[t._v("简介")]),t._v(" "),r("p",[t._v("快速提升网站在搜狗搜索上关键字排名，请添加搜狗搜索前500名的关键字！")]),t._v(" "),r("p",[r("br")]),t._v(" "),r("label",[t._v("基础设置")])]):t._e(),t._v(" "),"360_ranking"==t.subMenuType?r("div",{staticClass:"grid-content bg-purple"},[r("label",[t._v("简介")]),t._v(" "),r("p",[t._v("快速提升网站在360搜索上关键字排名，请添加360搜索前100名的关键字！")]),t._v(" "),r("p",[r("br")]),t._v(" "),r("label",[t._v("基础设置")])]):t._e()])],1),t._v(" "),r("el-row",{attrs:{gutter:20}},[r("el-col",{attrs:{span:2}},[r("div",{staticClass:"grid-content bg-purple"})]),t._v(" "),r("el-col",{attrs:{span:12}},[r("div",{staticClass:"grid-content bg-purple"},[r("el-form",{ref:"ruleForm",staticClass:"demo-ruleForm",attrs:{model:t.ruleForm,rules:t.rules,"label-width":"100px"}},[r("el-form-item",{attrs:{label:"任务备注",prop:"name"}},[r("el-input",{attrs:{placeholder:"备注名称"},model:{value:t.ruleForm.name,callback:function(e){t.$set(t.ruleForm,"name",e)},expression:"ruleForm.name"}})],1),t._v(" "),r("el-form-item",{attrs:{label:"关键字",prop:"keyword"}},[r("el-input",{attrs:{placeholder:"添加需要刷的关键字"},model:{value:t.ruleForm.keyword,callback:function(e){t.$set(t.ruleForm,"keyword",e)},expression:"ruleForm.keyword"}})],1),t._v(" "),r("el-form-item",{attrs:{label:"域名",prop:"url"}},[r("el-input",{attrs:{placeholder:"例：http://www.baidu.com"},model:{value:t.ruleForm.url,callback:function(e){t.$set(t.ruleForm,"url",e)},expression:"ruleForm.url"}})],1),t._v(" "),r("el-form-item",[r("el-button",{attrs:{type:"primary"},on:{click:function(e){t.submitForm("ruleForm")}}},[t._v(t._s(t.save_tip))]),t._v(" "),r("el-button",{on:{click:function(e){t.resetForm("ruleForm")}}},[t._v("重置")]),t._v(" "),r("el-button",{on:{click:function(e){t.goBack()}}},[t._v("返回列表")])],1)],1)],1)]),t._v(" "),r("el-col",{attrs:{span:2}},[r("div",{staticClass:"grid-content bg-purple"})]),t._v(" "),r("el-col",{attrs:{span:4}},[r("div",{staticClass:"grid-content bg-purple"})]),t._v(" "),r("el-col",{attrs:{span:4}},[r("div",{staticClass:"grid-content bg-purple"})])],1)],1)},[],!1,null,null,null);o.options.__file="urlSave.vue";e.a=o.exports},"y2d+":function(t,e,r){"use strict";r.r(e);var n={components:{urlSave:r("siFS").a}},a=r("KHd+"),u=Object(a.a)(n,function(){var t=this.$createElement;return(this._self._c||t)("url-save",{attrs:{menuType:"360_task",subMenuType:"360_ranking"}})},[],!1,null,null,null);u.options.__file="saveSpeed.vue";e.default=u.exports}}]);
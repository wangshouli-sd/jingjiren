(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-6c81"],{"30/W":function(e,t,s){"use strict";s.r(t);var a=s("GQL0"),i=s("Pf+D"),l={data:function(){return{activeName:"first",rules:{keyid:[{required:!0,message:"请输入访问阿里云API的密钥",trigger:"blur"}],secret:[{required:!0,message:"请输入AccessKeySecret",trigger:"blur"}],singname:[{required:!0,message:"请输入短信签名",trigger:"blur"}],templateid:[{required:!0,message:"请输入短信模板编号",trigger:"blur"}],code:[{required:!0,message:"请输入模板变量名",trigger:"blur"}]},alisms:{},msgtemp:{templet_shenhe:"",templet_renzheng:"",templet_TXfail:"",templet_TXsuccess:""}}},created:function(){this.getparmas(),this.getmsgparmas()},methods:{getparmas:function(){var e=this;Object(a.b)(this).then(function(t){e.alisms=t})},getmsgparmas:function(){var e=this;Object(i.j)(this).then(function(t){e.msgtemp.templet_shenhe=t.templet_shenhe,e.msgtemp.templet_renzheng=t.templet_renzheng,e.msgtemp.templet_TXfail=t.templet_TXfail,e.msgtemp.templet_TXsuccess=t.templet_TXsuccess})},handlesubmitForm:function(){var e=this;this.$confirm("确认提交更改短信验证参数, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){Object(a.d)(e.alisms).then(function(t){"success"===t.status.state&&(e.getparmas(),e.$message({type:"success",message:"设置成功!"}))})}).catch(function(){})},handlesubmitmsg:function(){var e=this;this.$confirm("确认提交模板消息设置, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){Object(i.o)(e.msgtemp).then(function(t){"success"===t.status.state&&(e.getmsgparmas(),e.$message({type:"success",message:"设置成功!"}))})}).catch(function(){})}}},n=(s("TmmT"),s("KHd+")),m=Object(n.a)(l,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"filter-container"},[a("el-tabs",{staticClass:"tab-scroll",model:{value:e.activeName,callback:function(t){e.activeName=t},expression:"activeName"}},[a("el-tab-pane",{staticClass:"el-tab-pane",staticStyle:{"margin-top":"2px",padding:"20px"},attrs:{label:"短信参数",name:"first"}},[a("keep-alive",["first"==e.activeName?a("div",[a("h6",{staticClass:"item-title"},[e._v("短信参数设置")]),e._v(" "),a("el-form",{staticClass:"ruleForm",attrs:{"label-width":"180px"}},[a("el-form-item",{attrs:{label:"AccessKey",prop:"keyid"}},[a("el-input",{model:{value:e.alisms.keyid,callback:function(t){e.$set(e.alisms,"keyid",t)},expression:"alisms.keyid"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("AccessKey是访问阿里云API的密钥")])],1),e._v(" "),a("el-form-item",{attrs:{label:"AccessKeySecret",prop:"secret"}},[a("el-input",{model:{value:e.alisms.secret,callback:function(t){e.$set(e.alisms,"secret",t)},expression:"alisms.secret"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("AccessKeySecret")])],1),e._v(" "),a("el-form-item",{attrs:{label:"短信签名",prop:"singname"}},[a("el-input",{model:{value:e.alisms.singname,callback:function(t){e.$set(e.alisms,"singname",t)},expression:"alisms.singname "}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("短信签名")])],1),e._v(" "),a("el-form-item",{attrs:{label:"短信模板编号",prop:"templateid"}},[a("el-input",{model:{value:e.alisms.templateid,callback:function(t){e.$set(e.alisms,"templateid",t)},expression:"alisms.templateid"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("短信模板编号")])],1),e._v(" "),a("el-form-item",{attrs:{label:"模板变量名",prop:"code"}},[a("el-input",{model:{value:e.alisms.code,callback:function(t){e.$set(e.alisms,"code",t)},expression:"alisms.code"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("模板变量模板变量名，选择的模板只能有一个变量，且为验证码变量")])],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:e.handlesubmitForm}},[e._v("保存")])],1)],1)],1):e._e()])],1),e._v(" "),a("el-tab-pane",{staticClass:"el-tab-pane",attrs:{label:"消息模板",name:"second"}},[a("keep-alive",["second"==e.activeName?a("div",[a("h6",{staticClass:"item-title"},[e._v("经纪人认证模板")]),e._v(" "),a("el-form",{staticClass:"ruleForm",attrs:{"label-width":"180px"}},[a("el-form-item",{attrs:{label:"消息模板ID"}},[a("el-input",{model:{value:e.msgtemp.templet_renzheng,callback:function(t){e.$set(e.msgtemp,"templet_renzheng",t)},expression:"msgtemp.templet_renzheng"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("微信公众平台-小程序-功能-模板消息，模板库中选择ID为AT0918的消息模板，设置好配置关键词提交，到个人模板库中复制模板ID提交到这个表单")])],1),e._v(" "),a("img",{attrs:{src:s("xfva"),height:"537",width:"1283"}})],1)],1):e._e()]),e._v(" "),a("keep-alive",["second"==e.activeName?a("div",[a("h6",{staticClass:"item-title"},[e._v("业务审核通知模板")]),e._v(" "),a("el-form",{staticClass:"ruleForm",attrs:{"label-width":"180px"}},[a("el-form-item",{attrs:{label:"消息模板ID"}},[a("el-input",{model:{value:e.msgtemp.templet_shenhe,callback:function(t){e.$set(e.msgtemp,"templet_shenhe",t)},expression:"msgtemp.templet_shenhe"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("微信公众平台-小程序-功能-模板消息，模板库中选择ID为AT0146的消息模板，设置好配置关键词提交，到个人模板库中复制模板ID提交到这个表单")])],1),e._v(" "),a("img",{attrs:{src:s("c6zX"),height:"541",width:"1284"}})],1)],1):e._e()]),e._v(" "),a("keep-alive",["second"==e.activeName?a("div",[a("h6",{staticClass:"item-title"},[e._v("提现成功通知模板")]),e._v(" "),a("el-form",{staticClass:"ruleForm",attrs:{"label-width":"180px"}},[a("el-form-item",{attrs:{label:"消息模板ID"}},[a("el-input",{attrs:{v:""},model:{value:e.msgtemp.templet_TXsuccess,callback:function(t){e.$set(e.msgtemp,"templet_TXsuccess",t)},expression:"msgtemp.templet_TXsuccess"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("微信公众平台-小程序-功能-模板消息，模板库中选择ID为AT1581的消息模板，设置好配置关键词提交，到个人模板库中复制模板ID提交到这个表单，注意顺序模块必须一致")])],1),e._v(" "),a("img",{attrs:{src:s("F7AI"),height:"868",width:"1287"}})],1)],1):e._e()]),e._v(" "),a("keep-alive",["second"==e.activeName?a("div",[a("h6",{staticClass:"item-title"},[e._v("提现失败模板")]),e._v(" "),a("el-form",{staticClass:"ruleForm",attrs:{"label-width":"180px"}},[a("el-form-item",{attrs:{label:"消息模板ID"}},[a("el-input",{model:{value:e.msgtemp.templet_TXfail,callback:function(t){e.$set(e.msgtemp,"templet_TXfail",t)},expression:"msgtemp.templet_TXfail"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("微信公众平台-小程序-功能-模板消息，模板库中选择ID为AT1242的消息模板，设置好配置关键词提交，到个人模板库中复制模板ID提交到这个表单")])],1),e._v(" "),a("img",{attrs:{src:s("wg62"),height:"868",width:"1287"}}),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:e.handlesubmitmsg}},[e._v("提交")])],1)],1)],1):e._e()])],1)],1)],1)])},[],!1,null,"1694d860",null);m.options.__file="setmsgparams.vue";t.default=m.exports},F7AI:function(e,t,s){e.exports=s.p+"static/img/tixiandao.e45508a.png"},GQL0:function(e,t,s){"use strict";s.d(t,"a",function(){return m}),s.d(t,"c",function(){return r}),s.d(t,"b",function(){return c}),s.d(t,"d",function(){return p});var a=s("Kw5r"),i=s("vDqi"),l=s.n(i),n=s("Q2AE");a.default.use(l.a);var m=function(e){return l.a.get(n.a.state.baseurl+"r=bk-set/get-alipay",{params:{}}).then(function(e){return e.data})},r=function(e){return l.a.post(n.a.state.baseurl+"r=bk-set/set-alipay",{appid:e.appid,private:e.private,public:e.public,singtype:e.singtype,showname:e.showname,remark:e.remark}).then(function(e){return e.data})},c=function(e){return l.a.get(n.a.state.baseurl+"r=bk-set/get-alisms",{params:{}}).then(function(e){return e.data})},p=function(e){return l.a.post(n.a.state.baseurl+"r=bk-set/set-alisms",{keyid:e.keyid,secret:e.secret,singname:e.singname,templateid:e.templateid,code:e.code}).then(function(e){return e.data})}},TmmT:function(e,t,s){"use strict";var a=s("U85q");s.n(a).a},U85q:function(e,t,s){},c6zX:function(e,t,s){e.exports=s.p+"static/img/shen.c57eed6.png"},wg62:function(e,t,s){e.exports=s.p+"static/img/tixianshi.9669a29.png"},xfva:function(e,t,s){e.exports=s.p+"static/img/renzheng.f9def2f.png"}}]);
(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-2026"],{CdDa:function(e,t,n){"use strict";var a=n("pxDL");n.n(a).a},pxDL:function(e,t,n){},walj:function(e,t,n){"use strict";n.r(t);var a=n("QbLZ"),i=n.n(a),o=n("L2JU"),s=n("Kw5r"),u=n("vDqi"),f=n.n(u),r=n("Q2AE");s.default.use(f.a);var c={data:function(){return{kefuinfo:{}}},computed:i()({},Object(o.c)({})),created:function(){this.getparmas()},methods:{getparmas:function(){var e=this;f.a.get(r.a.state.baseurl+"r=bk-set/showservice",{params:{}}).then(function(e){return e.data}).then(function(t){e.kefuinfo=t})},handlesubmitForm:function(){var e=this;this.$confirm("确认提交修改内容, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){(function(e){return f.a.post(r.a.state.baseurl+"r=bk-set/setservice",{name:e.name,qq:e.qq,phone:e.phone}).then(function(e){return e.data})})(e.kefuinfo).then(function(t){"success"===t.status.state&&(e.getparmas(),e.$message({type:"success",message:"设置成功!"}))})}).catch(function(){})}}},l=(n("CdDa"),n("KHd+")),p=Object(l.a)(c,function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"app-container"},[n("h6",{staticClass:"item-title"},[e._v("客服信息设置")]),e._v(" "),n("el-form",{staticClass:"weixin-ruleForm",attrs:{model:e.kefuinfo,"label-width":"100px"}},[n("el-form-item",{attrs:{label:"昵称"}},[n("el-input",{model:{value:e.kefuinfo.name,callback:function(t){e.$set(e.kefuinfo,"name",t)},expression:"kefuinfo.name"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"手机号",prop:"telphone"}},[n("el-input",{model:{value:e.kefuinfo.phone,callback:function(t){e.$set(e.kefuinfo,"phone",t)},expression:"kefuinfo.phone"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"QQ",prop:"qq"}},[n("el-input",{model:{value:e.kefuinfo.qq,callback:function(t){e.$set(e.kefuinfo,"qq",t)},expression:"kefuinfo.qq"}})],1),e._v(" "),n("el-form-item",[n("el-button",{attrs:{type:"primary"},on:{click:e.handlesubmitForm}},[e._v("提交")])],1)],1)],1)},[],!1,null,"f37814fe",null);p.options.__file="setkefu.vue";t.default=p.exports}}]);
(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-7060"],{"2UyW":function(e,t,a){},"4Z8k":function(e,t,a){"use strict";var i=a("YSbb");a.n(i).a},"56/k":function(e,t,a){"use strict";var i=a("P38I");a.n(i).a},GQL0:function(e,t,a){"use strict";a.d(t,"a",function(){return l}),a.d(t,"c",function(){return o}),a.d(t,"b",function(){return c}),a.d(t,"d",function(){return u});var i=a("Kw5r"),n=a("vDqi"),r=a.n(n),s=a("Q2AE");i.default.use(r.a);var l=function(e){return r.a.get(s.a.state.baseurl+"r=bk-set/get-alipay",{params:{}}).then(function(e){return e.data})},o=function(e){return r.a.post(s.a.state.baseurl+"r=bk-set/set-alipay",{appid:e.appid,private:e.private,public:e.public,singtype:e.singtype,showname:e.showname,remark:e.remark}).then(function(e){return e.data})},c=function(e){return r.a.get(s.a.state.baseurl+"r=bk-set/get-alisms",{params:{}}).then(function(e){return e.data})},u=function(e){return r.a.post(s.a.state.baseurl+"r=bk-set/set-alisms",{keyid:e.keyid,secret:e.secret,singname:e.singname,templateid:e.templateid,code:e.code}).then(function(e){return e.data})}},P38I:function(e,t,a){},P42R:function(e,t,a){"use strict";a.r(t);var i=a("QbLZ"),n=a.n(i),r=a("L2JU"),s={name:"SetProcess",props:{proptitle:{type:String,default:""},size:{type:String,default:""},filelist:{type:Array,default:[]}},data:function(){return{accepttype:".jpg,.png,.jpeg"}},computed:n()({},Object(r.c)({baseurl:function(e){return e.baseurl+"r=base/addimg"}})),created:function(){},methods:{handleRemove:function(e,t){console.log(e);for(var a=[],i=0;i<t.length;i++)t[i].response?a.push(t[i].response.id):a.push(t[i].id);this.$emit("childupload",{file:{},imgid:""})},handlePreview:function(e){console.log(e)},handleoverlimit:function(){this.$message({message:"文件数量超出限制，最多上传1张图片",type:"warning"})},handleUploadSucess:function(e,t,a){this.$message({message:"上传成功",type:"success"}),this.$emit("childupload",{file:t,imgid:e.id})},handledefeat:function(e,t,a){this.$message({message:"上传失败",type:"warning"})},handleChange:function(e,t){}}},l=(a("VtFC"),a("KHd+")),o=Object(l.a)(s,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-upload",{staticClass:"upload-demo",attrs:{limit:2,"on-preview":e.handlePreview,"on-remove":e.handleRemove,"on-success":e.handleUploadSucess,accept:e.accepttype,"on-exceed":e.handleoverlimit,"on-error":e.handledefeat,"on-change":e.handleChange,"file-list":e.filelist,action:e.baseurl,"list-type":"picture"}},[a("el-button",{attrs:{size:"small",type:"primary"}},[e._v("点击上传")]),e._v(" "),a("div",{staticClass:"el-upload__tip",attrs:{slot:"tip"},slot:"tip"},[e._v("(注：只能上传jpg/png文件，推荐大小 "+e._s(e.size)+" )")])],1)],1)},[],!1,null,"38c8a089",null);o.options.__file="UploadListimg.vue";var c=o.exports,u={data:function(){return{ruleForm:{appid:"",region:"",date1:"",date2:"",delivery:!1,type:[],typesigning:"",typevalidation:"",secretkey:"",publickey:""},rules:{appid:[{required:!0,message:"请输入开放平台应用的APPID",trigger:"blur"}],region:[{required:!0,message:"请选择活动区域",trigger:"change"}],date1:[{type:"date",required:!0,message:"请选择日期",trigger:"change"}],date2:[{type:"date",required:!0,message:"请选择时间",trigger:"change"}],type:[{type:"array",required:!0,message:"请至少选择一个活动性质",trigger:"change"}],typesigning:[{required:!0,message:"请选择签约方式",trigger:"change"}],typevalidation:[{required:!0,message:"请选择验签方式",trigger:"change"}],secretkey:[{required:!0,message:"请输入应用私钥",trigger:"blur"}],publickey:[{required:!0,message:"请输入支付宝公钥",trigger:"blur"}]}}},methods:{submitForm:function(e){this.$refs[e].validate(function(e){if(!e)return console.log("error submit!!"),!1;alert("submit!")})},resetForm:function(e){this.$refs[e].resetFields()}}},p=(a("4Z8k"),Object(l.a)(u,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("el-form",{ref:"ruleForm",staticClass:"weixin-ruleForm",attrs:{model:e.ruleForm,rules:e.rules,"label-width":"100px"}},[a("el-form-item",{attrs:{label:"签约方式",prop:"typesigning"}},[a("el-radio-group",{model:{value:e.ruleForm.typesigning,callback:function(t){e.$set(e.ruleForm,"typesigning",t)},expression:"ruleForm.typesigning"}},[a("el-radio",{attrs:{label:"支付宝打款"}}),e._v(" "),a("el-radio",{attrs:{label:"支付宝打款新接口"}})],1),e._v(" "),a("p",{staticClass:"el-p"},[e._v('新接口需要签约"转账到支付宝账户"，之前已经签约使用旧接口可以正常打款的无需修改')])],1),e._v(" "),a("el-form-item",{attrs:{label:"APPID",prop:"appid"}},[a("el-input",{model:{value:e.ruleForm.appid,callback:function(t){e.$set(e.ruleForm,"appid",t)},expression:"ruleForm.appid"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("开放平台应用的id")])],1),e._v(" "),a("el-form-item",{attrs:{label:"验签方式",prop:"typevalidation"}},[a("el-radio-group",{model:{value:e.ruleForm.typevalidation,callback:function(t){e.$set(e.ruleForm,"typevalidation",t)},expression:"ruleForm.typevalidation"}},[a("el-radio",{attrs:{label:"RSA"}}),e._v(" "),a("el-radio",{attrs:{label:"RSA2"}})],1),e._v(" "),a("p",{staticClass:"el-p"},[e._v("请选择正确的验证签名方式，否则支付宝支付不起作用(建议使用RSA2)")])],1),e._v(" "),a("el-form-item",{attrs:{label:"支付宝公钥",prop:"publickey"}},[a("el-input",{attrs:{type:"textarea",autosize:""},model:{value:e.ruleForm.publickey,callback:function(t){e.$set(e.ruleForm,"publickey",t)},expression:"ruleForm.publickey"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("一行且不能有空格")])],1),e._v(" "),a("el-form-item",{attrs:{label:"应用私钥",prop:"secretkey"}},[a("el-input",{attrs:{type:"textarea",autosize:""},model:{value:e.ruleForm.secretkey,callback:function(t){e.$set(e.ruleForm,"secretkey",t)},expression:"ruleForm.secretkey"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("一行且不能有空格")])],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:function(t){e.submitForm("ruleForm")}}},[e._v("提交")]),e._v(" "),a("el-button",{on:{click:function(t){e.resetForm("ruleForm")}}},[e._v("重置")])],1)],1)},[],!1,null,"20a48be4",null));p.options.__file="weixin.vue";var m=p.exports,d=a("GQL0"),f={data:function(){return{rules:{appid:[{required:!0,message:"请输入开放平台应用的APPID",trigger:"blur"}],singtype:[{required:!0,message:"请选择签约方式",trigger:"change"}],private:[{required:!0,message:"请输入应用私钥",trigger:"blur"}],public:[{required:!0,message:"请输入支付宝公钥",trigger:"blur"}]},alipay:{}}},created:function(){this.getparmas()},methods:{getparmas:function(){var e=this;Object(d.a)().then(function(t){e.alipay=t})},handlesubmitForm:function(){var e=this;this.$confirm("确认提交更改支付宝打款参数, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){Object(d.c)(e.alipay).then(function(t){"success"===t.status.state&&(e.getparmas(),e.$message({type:"success",message:"设置成功!"}))})}).catch(function(){})}}},v=(a("56/k"),Object(l.a)(f,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("el-form",{staticClass:"weixin-ruleForm",attrs:{model:e.alipay,rules:e.rules,"label-width":"100px"}},[a("el-form-item",{attrs:{label:"APPID",prop:"appid"}},[a("el-input",{model:{value:e.alipay.appid,callback:function(t){e.$set(e.alipay,"appid",t)},expression:"alipay.appid"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("开放平台应用的id")])],1),e._v(" "),a("el-form-item",{attrs:{label:"验签方式",prop:"singtype"}},[a("el-radio-group",{model:{value:e.alipay.singtype,callback:function(t){e.$set(e.alipay,"singtype",t)},expression:"alipay.singtype"}},[a("el-radio",{attrs:{label:"RSA"}}),e._v(" "),a("el-radio",{attrs:{label:"RSA2"}})],1),e._v(" "),a("p",{staticClass:"el-p"},[e._v("请选择正确的验证签名方式，否则支付宝支付不起作用(建议使用RSA2)")])],1),e._v(" "),a("el-form-item",{attrs:{label:"支付宝公钥",prop:"public"}},[a("el-input",{attrs:{type:"textarea",autosize:""},model:{value:e.alipay.public,callback:function(t){e.$set(e.alipay,"public",t)},expression:"alipay.public"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("一行且不能有空格")])],1),e._v(" "),a("el-form-item",{attrs:{label:"应用私钥",prop:"private"}},[a("el-input",{attrs:{type:"textarea",autosize:""},model:{value:e.alipay.private,callback:function(t){e.$set(e.alipay,"private",t)},expression:"alipay.private"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("一行且不能有空格")])],1),e._v(" "),a("el-form-item",{attrs:{label:"付款方名字"}},[a("el-input",{attrs:{type:"textarea",autosize:""},model:{value:e.alipay.showname,callback:function(t){e.$set(e.alipay,"showname",t)},expression:"alipay.showname"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("输入付款方名字，可用公司名，不填写默认转账支付宝账号")])],1),e._v(" "),a("el-form-item",{attrs:{label:"转账备注"}},[a("el-input",{attrs:{type:"textarea",autosize:""},model:{value:e.alipay.remark,callback:function(t){e.$set(e.alipay,"remark",t)},expression:"alipay.remark"}}),e._v(" "),a("p",{staticClass:"el-p"},[e._v("付款备注(理由)")])],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:e.handlesubmitForm}},[e._v("提交")])],1)],1)},[],!1,null,"1f91f874",null));v.options.__file="alipay.vue";var b=v.exports,g=a("Pf+D"),y={data:function(){return{value1:!0,temp:{bank_name:"",is_open:"0",order:"0"}}},computed:n()({},Object(r.c)({cardlist:function(e){return e.setindex.list}})),created:function(){this.getList()},methods:{getList:function(){var e=this;Object(g.d)().then(function(t){e.$store.dispatch("setindex/banklistall",t)})},addDomain:function(){var e=this;if(""===this.temp.bank_name)return this.$message({message:"请输入银行名称",type:"warning"}),!1;this.$confirm("确认添加"+this.temp.bank_name+"银行, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){Object(g.a)({name:e.temp.bank_name}).then(function(t){e.$store.dispatch("setindex/addbank",t),e.getList()}),e.temp.bank_name=""}).catch(function(){})},handleSubmit:function(){var e=this;this.$confirm("确认提交设置, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){for(var t=e.cardlist,a=0;a<t.length;a++)Object(g.l)(t[a]).then(function(i){e.$store.dispatch("setindex/setbank",i),a==t.length-1&&e.getList()})}).catch(function(){})}}},h=(a("c7we"),Object(l.a)(y,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"bankcard-ruleForm"},[a("el-row",{attrs:{gutter:20}},e._l(e.cardlist,function(t){return a("el-col",{attrs:{span:8}},[a("div",{staticClass:"card-content"},[e._v("\n        "+e._s(t.bank_name)+"\n        "),e._v(" "),a("el-switch",{staticClass:"el-switch",attrs:{"active-color":"#13ce66","active-value":"1","inactive-value":"0"},model:{value:t.is_open,callback:function(a){e.$set(t,"is_open",a)},expression:"item.is_open"}})],1)])})),e._v(" "),a("el-row",{staticStyle:{"margin-top":"50px"}},[a("el-form",{attrs:{inline:""}},[a("el-form-item",[a("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"新增银行"},model:{value:e.temp.bank_name,callback:function(t){e.$set(e.temp,"bank_name",t)},expression:"temp.bank_name"}})],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:function(t){e.addDomain()}}},[e._v("添加")])],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:e.handleSubmit}},[e._v("保存")])],1)],1)],1)],1)},[],!1,null,"8a5f9c56",null));h.options.__file="bankcard.vue";var _=h.exports,k=a("ZySA"),w=a("Rne/"),x={name:"ComplexTable",directives:{waves:k.a},components:{UploadListimg:c,weixin:m,alipay:b,bankcard:_},filters:{statusFilter:function(e){return{true:"success",false:"danger"}[e]},txTypeFilter:function(e){return{alipay:"支付宝",bank:"银行卡",wechat:"微信零钱"}[e.slice(9)]},isopenFilter:function(e){return"1"===e}},data:function(){return{formLabelAlign:{name:"",region:"",typeArr:[{id:"0",name:"name",type:"1",isopen:!1}]},activeName:"first",inputVisible:!1,inputValue:"",weixin:null,alipay:null,bankcard:null}},computed:n()({},Object(r.c)({withdrawalType:function(e){return e.financial.withdrawaltype}})),created:function(){this.getList()},methods:{getList:function(){Object(w.f)(this)},handleSubmit:function(){var e=this;this.$confirm("确认提交设置, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){for(var t=e.withdrawalType,a=0;a<t.length;a++)Object(g.n)(t[a]).then(function(t){e.$store.dispatch("setindex/setdakuan",t)})}).catch(function(){})}}},C=(a("nAww"),Object(l.a)(x,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"filter-container"},[a("el-tabs",{staticClass:"tab-scroll",model:{value:e.activeName,callback:function(t){e.activeName=t},expression:"activeName"}},[a("el-tab-pane",{staticClass:"el-tab-pane",staticStyle:{"margin-top":"2px",padding:"20px"},attrs:{label:"打款方式",name:"first"}},[a("keep-alive",[a("div",[a("h6",{staticClass:"item-title"},[e._v("打款方式设置")]),e._v(" "),e._l(e.withdrawalType,function(t){return[a("el-row",{staticClass:"first-item-row"},[a("el-col",{attrs:{span:18}},[a("div",{staticClass:"first-item-l"},[a("div",{staticClass:"first-item-l-title"},[e._v(e._s(e._f("txTypeFilter")(t.name)))]),e._v(" "),a("div",{staticClass:"first-item-l-desc"},[e._v("开启后可以通过"+e._s(e._f("txTypeFilter")(t.name))+"打款")])])]),e._v(" "),a("el-col",{attrs:{span:6}},[a("div",{staticClass:"first-item-r"},[a("el-switch",{attrs:{"active-text":"启用","inactive-text":"禁用","active-value":"1","inactive-value":"0"},model:{value:t.type,callback:function(a){e.$set(t,"type",a)},expression:"item.type"}})],1)])],1)]}),e._v(" "),a("el-row",{staticStyle:{"text-align":"right","margin-top":"50px"}},[a("el-button",{attrs:{type:"primary"},on:{click:e.handleSubmit}},[e._v("提交")])],1)],2)])],1),e._v(" "),a("el-tab-pane",{staticClass:"el-tab-pane",attrs:{label:"支付宝打款",name:"third"}},[a("keep-alive",[a("div",[a("h6",{staticClass:"item-title"},[e._v("支付宝打款--基本参数设置")]),e._v(" "),a("alipay")],1)])],1),e._v(" "),a("el-tab-pane",{staticClass:"el-tab-pane",attrs:{label:"银行卡转账",name:"fourth"}},[a("keep-alive",[a("div",[a("h6",{staticClass:"item-title"},[e._v("设置支持银行")]),e._v(" "),a("bankcard")],1)])],1)],1)],1)])},[],!1,null,"17e40eca",null));C.options.__file="withdrawal.vue";t.default=C.exports},"Rne/":function(e,t,a){"use strict";a.d(t,"d",function(){return l}),a.d(t,"f",function(){return o}),a.d(t,"c",function(){return c}),a.d(t,"b",function(){return u}),a.d(t,"e",function(){return p}),a.d(t,"a",function(){return m});var i=a("Kw5r"),n=a("vDqi"),r=a.n(n),s=a("Q2AE");i.default.use(r.a);var l=function(e){return r.a.get(s.a.state.baseurl+"r=bk-getmoney/list",{params:{page:e.page,size:e.limit,state:e.state,type:e.type,name:e.name,number:e.number}}).then(function(e){return e.data})};function o(e){r.a.get(s.a.state.baseurl+"r=bk-getmoney/typelist",{}).then(function(e){s.a.dispatch("financial/typemoney",e.data)}).catch(function(e){console.log(e)})}var c=function(e){return r.a.get(s.a.state.baseurl+"r=bk-getmoney/deltixian",{params:{id:e}}).then(function(e){return e.data})},u=function(e){return r.a.post(s.a.state.baseurl+"r=bk-getmoney/shen",{id:e.id,state:e.state,remarks:e.remarks}).then(function(e){return e.data})},p=function(e){return r.a.get(s.a.state.baseurl+"r=bk-getmoney/shenpush",{params:{id:e,state:"1"}}).then(function(e){return e.data})},m=function(e){return r.a.get(s.a.state.baseurl+"r=bk-set/pay",{params:{id:e}}).then(function(e){return e.data})}},VtFC:function(e,t,a){"use strict";var i=a("dAoL");a.n(i).a},YSbb:function(e,t,a){},ZySA:function(e,t,a){"use strict";var i=a("P2sY"),n=a.n(i),r=(a("jUE0"),{bind:function(e,t){e.addEventListener("click",function(a){var i=n()({},t.value),r=n()({ele:e,type:"hit",color:"rgba(0, 0, 0, 0.15)"},i),s=r.ele;if(s){s.style.position="relative",s.style.overflow="hidden";var l=s.getBoundingClientRect(),o=s.querySelector(".waves-ripple");switch(o?o.className="waves-ripple":((o=document.createElement("span")).className="waves-ripple",o.style.height=o.style.width=Math.max(l.width,l.height)+"px",s.appendChild(o)),r.type){case"center":o.style.top=l.height/2-o.offsetHeight/2+"px",o.style.left=l.width/2-o.offsetWidth/2+"px";break;default:o.style.top=(a.pageY-l.top-o.offsetHeight/2-document.documentElement.scrollTop||document.body.scrollTop)+"px",o.style.left=(a.pageX-l.left-o.offsetWidth/2-document.documentElement.scrollLeft||document.body.scrollLeft)+"px"}return o.style.backgroundColor=r.color,o.className="waves-ripple z-active",!1}},!1)}}),s=function(e){e.directive("waves",r)};window.Vue&&(window.waves=r,Vue.use(s)),r.install=s;t.a=r},c7we:function(e,t,a){"use strict";var i=a("zNOo");a.n(i).a},dAoL:function(e,t,a){},jUE0:function(e,t,a){},nAww:function(e,t,a){"use strict";var i=a("2UyW");a.n(i).a},zNOo:function(e,t,a){}}]);
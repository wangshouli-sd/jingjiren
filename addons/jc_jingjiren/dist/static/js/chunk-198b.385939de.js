(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-198b"],{OQTy:function(t,e,i){"use strict";var n=i("SDdx");i.n(n).a},SDdx:function(t,e,i){},ZySA:function(t,e,i){"use strict";var n=i("P2sY"),s=i.n(n),a=(i("jUE0"),{bind:function(t,e){t.addEventListener("click",function(i){var n=s()({},e.value),a=s()({ele:t,type:"hit",color:"rgba(0, 0, 0, 0.15)"},n),l=a.ele;if(l){l.style.position="relative",l.style.overflow="hidden";var r=l.getBoundingClientRect(),o=l.querySelector(".waves-ripple");switch(o?o.className="waves-ripple":((o=document.createElement("span")).className="waves-ripple",o.style.height=o.style.width=Math.max(r.width,r.height)+"px",l.appendChild(o)),a.type){case"center":o.style.top=r.height/2-o.offsetHeight/2+"px",o.style.left=r.width/2-o.offsetWidth/2+"px";break;default:o.style.top=(i.pageY-r.top-o.offsetHeight/2-document.documentElement.scrollTop||document.body.scrollTop)+"px",o.style.left=(i.pageX-r.left-o.offsetWidth/2-document.documentElement.scrollLeft||document.body.scrollLeft)+"px"}return o.style.backgroundColor=a.color,o.className="waves-ripple z-active",!1}},!1)}}),l=function(t){t.directive("waves",a)};window.Vue&&(window.waves=a,Vue.use(l)),a.install=l;e.a=a},bN1i:function(t,e,i){"use strict";i.d(e,"d",function(){return r}),i.d(e,"e",function(){return o}),i.d(e,"a",function(){return c}),i.d(e,"c",function(){return u}),i.d(e,"b",function(){return p}),i.d(e,"f",function(){return d});var n=i("Kw5r"),s=i("vDqi"),a=i.n(s),l=i("Q2AE");function r(t,e){a.a.get(t.$store.state.baseurl+"r=bk-category",{params:{page:e.page,size:e.limit}}).then(function(e){t.$store.dispatch("projectindex/getcategory",e.data)}).catch(function(t){console.log(t)})}n.default.use(a.a);var o=function(t){return a.a.get(l.a.state.baseurl+"r=bk-category",{params:{page:t.page,size:t.limit}}).then(function(t){return t.data})},c=function(t){return a.a.post(l.a.state.baseurl+"r=bk-category/createup",{title:t.title,is_open:t.is_open,is_index:t.is_index,description:t.description}).then(function(t){return t.data})},u=function(t){return a.a.post(l.a.state.baseurl+"r=bk-category/createup",{id:t.id,title:t.title,is_open:t.is_open,is_index:t.is_index,description:t.description}).then(function(t){return t.data})},p=function(t){return a.a.get(l.a.state.baseurl+"r=bk-category/del",{params:{id:t}}).then(function(t){return t.data})},d=function(t){return a.a.get(l.a.state.baseurl+"r=bk-category/one",{params:{id:t}}).then(function(t){return t.data})}},io4S:function(t,e,i){"use strict";i.r(e);var n=i("QbLZ"),s=i.n(n),a=i("L2JU"),l=i("ZySA"),r=i("Kw5r"),o=i("vDqi"),c=i.n(o),u=i("Q2AE");r.default.use(c.a);var p=function(t,e,i,n){return c.a.post(u.a.state.baseurl+"r=bk-task/shentask",{id:e,state:t,why:i,process:n}).then(function(t){return t.data})},d=i("bN1i"),f={name:"ComplexTable",directives:{waves:l.a},filters:{statusFilter:function(t){return{true:"success",false:"danger"}[t]},timeFilter:function(t){return t.slice(0,10)+""+t.slice(10,16)},pageFilters:function(t){return parseInt(t)}},data:function(){return{listLoading:!0,listQuery:{page:1,limit:10,title:null,type:null},dialogFormVisible:!1,formLabelWidth:"120px",steps:[{process_name:"审核通过"},{process_name:"电话邀约"},{process_name:"约见详谈"},{process_name:"洽谈成功"}],selectitem:null,list:[],page:{}}},computed:s()({},Object(a.c)({processTypeOptions:function(t){return t.projectindex.list}})),created:function(){this.getList(),this.getCategory()},methods:{getList:function(){var t=this;(function(t){return c.a.get(u.a.state.baseurl+"r=bk-task",{params:{page:t.page,size:t.limit,name:t.title,check:1,stop:0,category_id:t.type}}).then(function(t){return t.data})})(this.listQuery).then(function(e){t.list=e.list,t.page=e.page})},getCategory:function(){Object(d.d)(this,this.listQuery)},handleFilter:function(){this.listQuery.page=1,this.getList()},handleSizeChange:function(t){this.listQuery.limit=t,this.getList()},handleCurrentChange:function(t){this.listQuery.page=t,this.getList()},handleUpdate:function(t){this.selectitem=t,this.dialogFormVisible=!0},handleBackto:function(t){var e=this;this.$prompt("审核未通过，请输入退回原因","提示",{confirmButtonText:"确定",cancelButtonText:"取消"}).then(function(i){var n=i.value;if(""===n||null==n)return e.$message({type:"warning",message:"请输入退回原因"}),!1;p("0",t.id,n,null).then(function(t){"success"===t.status.state&&(e.getList(),e.$message({type:"success",message:"提交成功"}))})}).catch(function(){})},handleDelete:function(t){var e=this;this.$confirm("此操作将删除该业务信息, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){(function(t){return c.a.get(u.a.state.baseurl+"r=bk-check/del",{params:{id:t}}).then(function(t){return t.data})})(t.id).then(function(t){"success"===t.status.state&&(e.getList(),e.$message({type:"success",message:"删除成功!"}))})}).catch(function(){})},removeDomain:function(t){var e=this.steps.indexOf(t);-1!==e&&this.steps.splice(e,1)},addDomain:function(){var t=this.steps.length;this.steps.splice(t-1,0,{process_name:"",key:Date.now()})},handleSkipset:function(){var t=this,e=this.selectitem;this.$confirm("跳过洽谈设置，使用默认流程, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){t.dialogFormVisible=!1,p("1",e.id,null,t.steps).then(function(e){"success"===e.status.state&&(t.getList(),t.$message({type:"success",message:"提交成功"}))})}).catch(function(){})},handleSubmit:function(){var t=this,e=this.selectitem;this.$confirm("确认该业务信息通过审核并提交洽谈流程, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){t.dialogFormVisible=!1,p("1",e.id,null,t.steps).then(function(e){"success"===e.status.state&&(t.getList(),t.$message({type:"success",message:"提交成功"}))})}).catch(function(){})}}},m=(i("OQTy"),i("KHd+")),h=Object(m.a)(f,function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("div",{staticClass:"filter-container",staticStyle:{background:"#ffffff",padding:"16px"}},[i("el-input",{staticClass:"filter-item",staticStyle:{width:"200px"},attrs:{placeholder:"联系人"},nativeOn:{keyup:function(e){return"button"in e||!t._k(e.keyCode,"enter",13,e.key,"Enter")?t.handleFilter(e):null}},model:{value:t.listQuery.title,callback:function(e){t.$set(t.listQuery,"title",e)},expression:"listQuery.title"}}),t._v(" "),i("el-select",{staticClass:"filter-item",staticStyle:{width:"130px"},attrs:{clearable:"",placeholder:"项目分类"},model:{value:t.listQuery.type,callback:function(e){t.$set(t.listQuery,"type",e)},expression:"listQuery.type"}},t._l(t.processTypeOptions,function(t){return i("el-option",{key:t.title,attrs:{label:t.title,value:t.id}})})),t._v(" "),i("el-button",{directives:[{name:"waves",rawName:"v-waves"}],staticClass:"filter-item",attrs:{type:"primary",icon:"el-icon-search"},on:{click:t.handleFilter}},[t._v("搜索")])],1),t._v(" "),i("el-table",{ref:"multipleTable",staticStyle:{width:"100%"},attrs:{data:t.list,"tooltip-effect":"dark",border:""}},[i("el-table-column",{attrs:{type:"expand"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("div",{staticClass:"opinion-content"},[i("el-form",{attrs:{"label-width":"80px"}},[i("el-form-item",{attrs:{label:"备注"}},[i("el-input",{attrs:{type:"textarea",readonly:"true"},model:{value:e.row.describe,callback:function(i){t.$set(e.row,"describe",i)},expression:"scope.row.describe"}})],1)],1)],1)]}}])}),t._v(" "),i("el-table-column",{attrs:{label:"序号",type:"index",width:"60",align:"center"}}),t._v(" "),i("el-table-column",{attrs:{prop:"task_number",label:"业务编号",width:"150",align:"center"}}),t._v(" "),i("el-table-column",{attrs:{label:"业务名称",align:"center","min-width":"200px"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-row",[i("el-col",{staticClass:"ywitem-l",attrs:{span:6}},[i("div",{},[i("img",{staticClass:"ywitem-l-img",attrs:{src:e.row.img_path,alt:""}})])]),t._v(" "),i("el-col",{staticClass:"ywitem-r",attrs:{span:18}},[i("div",{},[i("div",{staticClass:"ywitem-r-title"},[t._v(t._s(e.row.taskname))]),t._v(" "),i("div",t._l(e.row.tags,function(e){return i("el-tag",{key:e.key,staticStyle:{"margin-right":"5px"},attrs:{type:"success",size:"mini"}},[t._v(t._s(e))])}))])])],1)]}}])}),t._v(" "),i("el-table-column",{attrs:{prop:"username",label:"业务联系人",width:"120",align:"center"}}),t._v(" "),i("el-table-column",{attrs:{prop:"userphone",label:"联系方式",width:"120",align:"center"}}),t._v(" "),i("el-table-column",{attrs:{prop:"time",label:"提交时间",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n        "+t._s(t._f("timeFilter")(e.row.time))+"\n      ")]}}])}),t._v(" "),i("el-table-column",{attrs:{prop:"expect_commission",label:"预计佣金",width:"120",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",{staticStyle:{color:"red"}},[t._v("￥"+t._s(e.row.min_price)+"--"+t._s(e.row.max_price))])]}}])}),t._v(" "),i("el-table-column",{attrs:{prop:"name",label:"经纪人",width:"120",align:"center"}}),t._v(" "),i("el-table-column",{attrs:{label:"状态",width:"120",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-tag",{attrs:{type:"danger"}},[t._v("待审核")])]}}])}),t._v(" "),i("el-table-column",{attrs:{align:"center",label:"操作",width:"200","class-name":"small-padding fixed-width"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("i",{staticClass:"el-icon-check el-icon",on:{click:function(i){t.handleUpdate(e.row)}}}),t._v(" "),i("i",{staticClass:"el-icon-close el-icon",on:{click:function(i){t.handleBackto(e.row)}}}),t._v(" "),i("i",{staticClass:"el-icon-delete el-icon",on:{click:function(i){t.handleDelete(e.row)}}})]}}])})],1),t._v(" "),i("div",{staticClass:"pagination-container",staticStyle:{background:"white","margin-top":"0","text-align":"right",padding:"16px"}},[i("el-pagination",{attrs:{"current-page":t.listQuery.page,"page-sizes":[10,20,30,50],"page-size":t.listQuery.limit,total:t._f("pageFilters")(t.page.data_total),background:"",layout:"total, sizes, prev, pager, next, jumper"},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1),t._v(" "),i("el-dialog",{attrs:{visible:t.dialogFormVisible,title:"通过审核，设置洽谈流程"},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[i("div",{staticClass:"tab-title"},[i("div",{staticClass:"tab-title-item tab-title-item-left"},[t._v("序号")]),t._v(" "),i("div",{staticClass:"tab-title-item tab-title-item-right"},[t._v("名称")])]),t._v(" "),i("el-form",{ref:"steps",staticClass:"demo-dynamic",attrs:{"label-width":"80px"}},[t._l(t.steps,function(e,n){return i("el-form-item",{key:e.key},[i("span",{staticClass:"serialnumber"},[t._v(t._s(++n))]),t._v(" "),i("el-input",{staticClass:"process-input",attrs:{disabled:1==n||n==t.steps.length},model:{value:e.process_name,callback:function(i){t.$set(e,"process_name",i)},expression:"domain.process_name"}}),t._v(" "),1!=n&&n!=t.steps.length?i("i",{staticClass:"el-icon-delete el-icon",on:{click:function(i){i.preventDefault(),t.removeDomain(e)}}}):t._e()],1)}),t._v(" "),i("el-form-item",[i("el-button",{on:{click:t.addDomain}},[t._v("新增步骤")])],1)],2),t._v(" "),i("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.dialogFormVisible=!1}}},[t._v("取 消")]),t._v(" "),i("el-popover",{attrs:{placement:"top-start",width:"200",trigger:"hover",content:"洽谈流程为可选项，点击跳过使用默认设置"}},[i("el-button",{attrs:{slot:"reference"},on:{click:t.handleSkipset},slot:"reference"},[t._v("跳 过")])],1),t._v(" "),i("el-button",{attrs:{type:"primary"},on:{click:t.handleSubmit}},[t._v("提 交")])],1)],1)],1)},[],!1,null,"3edd2bb2",null);h.options.__file="state0.vue";e.default=h.exports},jUE0:function(t,e,i){}}]);
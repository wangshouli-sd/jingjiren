(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-8a53"],{"4hQ3":function(t,e,n){},ZySA:function(t,e,n){"use strict";var i=n("P2sY"),a=n.n(i),s=(n("jUE0"),{bind:function(t,e){t.addEventListener("click",function(n){var i=a()({},e.value),s=a()({ele:t,type:"hit",color:"rgba(0, 0, 0, 0.15)"},i),l=s.ele;if(l){l.style.position="relative",l.style.overflow="hidden";var r=l.getBoundingClientRect(),c=l.querySelector(".waves-ripple");switch(c?c.className="waves-ripple":((c=document.createElement("span")).className="waves-ripple",c.style.height=c.style.width=Math.max(r.width,r.height)+"px",l.appendChild(c)),s.type){case"center":c.style.top=r.height/2-c.offsetHeight/2+"px",c.style.left=r.width/2-c.offsetWidth/2+"px";break;default:c.style.top=(n.pageY-r.top-c.offsetHeight/2-document.documentElement.scrollTop||document.body.scrollTop)+"px",c.style.left=(n.pageX-r.left-c.offsetWidth/2-document.documentElement.scrollLeft||document.body.scrollLeft)+"px"}return c.style.backgroundColor=s.color,c.className="waves-ripple z-active",!1}},!1)}}),l=function(t){t.directive("waves",s)};window.Vue&&(window.waves=s,Vue.use(l)),s.install=l;e.a=s},bN1i:function(t,e,n){"use strict";n.d(e,"d",function(){return r}),n.d(e,"e",function(){return c}),n.d(e,"a",function(){return o}),n.d(e,"c",function(){return u}),n.d(e,"b",function(){return p}),n.d(e,"f",function(){return d});var i=n("Kw5r"),a=n("vDqi"),s=n.n(a),l=n("Q2AE");function r(t,e){s.a.get(t.$store.state.baseurl+"r=bk-category",{params:{page:e.page,size:e.limit}}).then(function(e){t.$store.dispatch("projectindex/getcategory",e.data)}).catch(function(t){console.log(t)})}i.default.use(s.a);var c=function(t){return s.a.get(l.a.state.baseurl+"r=bk-category",{params:{page:t.page,size:t.limit}}).then(function(t){return t.data})},o=function(t){return s.a.post(l.a.state.baseurl+"r=bk-category/createup",{title:t.title,is_open:t.is_open,is_index:t.is_index,description:t.description}).then(function(t){return t.data})},u=function(t){return s.a.post(l.a.state.baseurl+"r=bk-category/createup",{id:t.id,title:t.title,is_open:t.is_open,is_index:t.is_index,description:t.description}).then(function(t){return t.data})},p=function(t){return s.a.get(l.a.state.baseurl+"r=bk-category/del",{params:{id:t}}).then(function(t){return t.data})},d=function(t){return s.a.get(l.a.state.baseurl+"r=bk-category/one",{params:{id:t}}).then(function(t){return t.data})}},j4w2:function(t,e,n){"use strict";n.r(e);var i=n("QbLZ"),a=n.n(i),s=n("L2JU"),l=n("ZySA"),r=n("Kw5r"),c=n("vDqi"),o=n.n(c),u=n("Q2AE");r.default.use(o.a);var p=function(t){return o.a.get(u.a.state.baseurl+"r=bk-check/del-all",{params:{id:t}}).then(function(t){return t.data})},d=n("bN1i"),f={name:"ComplexTable",directives:{waves:l.a},filters:{statusFilter:function(t){return{true:"success",false:"danger"}[t]},timeFilter:function(t){return t.slice(0,10)+""+t.slice(10,16)},pageFilters:function(t){return parseInt(t)}},data:function(){return{listLoading:!0,listQuery:{page:1,limit:10,title:void 0,type:void 0},multipleSelection:[],list:[],page:{}}},computed:a()({},Object(s.c)({processTypeOptions:function(t){return t.projectindex.list}})),created:function(){this.getList(),this.getCategory()},methods:{getList:function(){var t=this;(function(t){return o.a.get(u.a.state.baseurl+"r=bk-task",{params:{page:t.page,size:t.limit,name:t.title,category_id:t.type,check:"0",state:"0",stop:"0"}}).then(function(t){return t.data})})(this,this.listQuery).then(function(e){t.list=e.list,t.page=e.page})},getCategory:function(){Object(d.d)(this,this.listQuery)},handleFilter:function(){this.listQuery.page=1,this.getList()},handleSizeChange:function(t){this.listQuery.limit=t,this.getList()},handleCurrentChange:function(t){this.listQuery.page=t,this.getList()},handleDelete:function(t){var e=this;this.$confirm("此操作将删除该项目相关信息, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){p(t.id).then(function(t){"success"===t.status.state&&(e.getList(),e.$message({type:"success",message:"删除成功!"}))})}).catch(function(){})},handleBackto:function(t){var e=this;this.$prompt("重新填写退回原因","提示",{confirmButtonText:"确定",cancelButtonText:"取消",inputValue:t.state_why}).then(function(n){var i=n.value;if(""===i||null==i)return e.$message({type:"warning",message:"请输入退回原因"}),!1;(function(t,e){return o.a.get(u.a.state.baseurl+"r=bk-check/editnotpass",{params:{id:t,why:e}}).then(function(t){return t.data})})(t.id,i).then(function(t){"success"===t.status.state&&(e.getList(),e.$message({type:"success",message:"提交成功"}))})}).catch(function(){})},handleSelectionChange:function(t){this.multipleSelection=t},handleBatchDelete:function(){for(var t=this,e=this.multipleSelection,n=[],i=0;i<e.length;i++)n.push(e[i].id);this.$confirm("此操作将删除所选项目相关信息, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){p(n.join(",")).then(function(e){"success"===e.status.state&&(t.getList(),t.$message({type:"success",message:"删除成功!"}))})}).catch(function(){})}}},h=(n("pcay"),n("KHd+")),g=Object(h.a)(f,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("div",{staticClass:"filter-container",staticStyle:{background:"#ffffff",padding:"16px"}},[n("el-input",{staticClass:"filter-item",staticStyle:{width:"200px"},attrs:{placeholder:"联系人"},nativeOn:{keyup:function(e){return"button"in e||!t._k(e.keyCode,"enter",13,e.key,"Enter")?t.handleFilter(e):null}},model:{value:t.listQuery.title,callback:function(e){t.$set(t.listQuery,"title",e)},expression:"listQuery.title"}}),t._v(" "),n("el-select",{staticClass:"filter-item",staticStyle:{width:"130px"},attrs:{clearable:"",placeholder:"项目分类"},model:{value:t.listQuery.type,callback:function(e){t.$set(t.listQuery,"type",e)},expression:"listQuery.type"}},t._l(t.processTypeOptions,function(t){return n("el-option",{key:t.title,attrs:{label:t.title,value:t.id}})})),t._v(" "),n("el-button",{directives:[{name:"waves",rawName:"v-waves"}],staticClass:"filter-item",attrs:{type:"primary",icon:"el-icon-search"},on:{click:t.handleFilter}},[t._v("搜索")]),t._v(" "),n("el-button",{directives:[{name:"waves",rawName:"v-waves"}],staticClass:"filter-item",attrs:{type:"danger",icon:"el-icon-delete"},on:{click:t.handleBatchDelete}},[t._v("批量删除")])],1),t._v(" "),n("el-table",{ref:"multipleTable",staticStyle:{width:"100%"},attrs:{data:t.list,"tooltip-effect":"dark",border:""},on:{"selection-change":t.handleSelectionChange}},[n("el-table-column",{attrs:{type:"selection",align:"center",width:"50"}}),t._v(" "),n("el-table-column",{attrs:{prop:"task_number",label:"业务编号",width:"120",align:"center"}}),t._v(" "),n("el-table-column",{attrs:{label:"业务名称",align:"center","min-width":"200px"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-row",[n("el-col",{staticClass:"ywitem-l",attrs:{span:6}},[n("div",{},[n("img",{staticClass:"ywitem-l-img",attrs:{src:e.row.img_path,alt:""}})])]),t._v(" "),n("el-col",{staticClass:"ywitem-r",attrs:{span:18}},[n("div",{},[n("div",{staticClass:"ywitem-r-title"},[t._v(t._s(e.row.taskname))]),t._v(" "),n("div",t._l(e.row.tags,function(e){return n("el-tag",{key:e.key,staticStyle:{"margin-right":"3px"},attrs:{size:"mini",type:"success"}},[t._v(t._s(e))])}))])])],1)]}}])}),t._v(" "),n("el-table-column",{attrs:{prop:"username",label:"业务联系人",width:"120",align:"center"}}),t._v(" "),n("el-table-column",{attrs:{prop:"userphone",label:"联系方式",width:"120",align:"center"}}),t._v(" "),n("el-table-column",{attrs:{prop:"time",label:"提交时间",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n        "+t._s(e.row.time)+"\n      ")]}}])}),t._v(" "),n("el-table-column",{attrs:{prop:"name",label:"经纪人",width:"120",align:"center"}}),t._v(" "),n("el-table-column",{attrs:{prop:"state",label:"状态",width:"120",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-tag",{attrs:{type:"danger"}},[t._v("审核未通过")])]}}])}),t._v(" "),n("el-table-column",{attrs:{prop:"process",label:"原因",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("div",{staticStyle:{display:"-webkit-box","-webkit-line-clamp":"2","-webkit-box-orient":"vertical",overflow:"hidden"}},[t._v("\n          "+t._s(e.row.state_why)+"\n        ")])]}}])}),t._v(" "),n("el-table-column",{attrs:{align:"center",label:"操作",width:"200","class-name":"small-padding fixed-width"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("i",{staticClass:"el-icon-edit el-icon",on:{click:function(n){t.handleBackto(e.row)}}}),t._v(" "),n("i",{staticClass:"el-icon-delete el-icon",on:{click:function(n){t.handleDelete(e.row)}}})]}}])})],1),t._v(" "),n("div",{staticClass:"pagination-container",staticStyle:{background:"white","margin-top":"0","text-align":"right",padding:"16px"}},[n("el-pagination",{attrs:{"current-page":t.listQuery.page,"page-sizes":[10,20,30,50],"page-size":t.listQuery.limit,total:t._f("pageFilters")(t.page.data_total),background:"",layout:"total, sizes, prev, pager, next, jumper"},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)},[],!1,null,"7ca66937",null);g.options.__file="state4.vue";e.default=g.exports},jUE0:function(t,e,n){},pcay:function(t,e,n){"use strict";var i=n("4hQ3");n.n(i).a}}]);
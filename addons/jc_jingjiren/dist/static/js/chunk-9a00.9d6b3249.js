(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-9a00"],{N80c:function(t,e,i){},ZySA:function(t,e,i){"use strict";var n=i("P2sY"),s=i.n(n),a=(i("jUE0"),{bind:function(t,e){t.addEventListener("click",function(i){var n=s()({},e.value),a=s()({ele:t,type:"hit",color:"rgba(0, 0, 0, 0.15)"},n),l=a.ele;if(l){l.style.position="relative",l.style.overflow="hidden";var o=l.getBoundingClientRect(),c=l.querySelector(".waves-ripple");switch(c?c.className="waves-ripple":((c=document.createElement("span")).className="waves-ripple",c.style.height=c.style.width=Math.max(o.width,o.height)+"px",l.appendChild(c)),a.type){case"center":c.style.top=o.height/2-c.offsetHeight/2+"px",c.style.left=o.width/2-c.offsetWidth/2+"px";break;default:c.style.top=(i.pageY-o.top-c.offsetHeight/2-document.documentElement.scrollTop||document.body.scrollTop)+"px",c.style.left=(i.pageX-o.left-c.offsetWidth/2-document.documentElement.scrollLeft||document.body.scrollLeft)+"px"}return c.style.backgroundColor=a.color,c.className="waves-ripple z-active",!1}},!1)}}),l=function(t){t.directive("waves",a)};window.Vue&&(window.waves=a,Vue.use(l)),a.install=l;e.a=a},fbuD:function(t,e,i){},jUE0:function(t,e,i){},mLXK:function(t,e,i){"use strict";var n=i("N80c");i.n(n).a},q4h8:function(t,e,i){"use strict";var n=i("fbuD");i.n(n).a},rr1a:function(t,e,i){"use strict";var n=i("QbLZ"),s=i.n(n),a=i("L2JU"),l={name:"SetProcess",props:{proptitle:{type:String,default:""},size:{type:String,default:""},filelist:{type:Array,default:[]}},data:function(){return{accepttype:".jpg,.png,.jpeg"}},computed:s()({},Object(a.c)({baseurl:function(t){return t.baseurl+"r=base/addimg"}})),created:function(){},methods:{handleRemove:function(t,e){console.log(t);for(var i=[],n=0;n<e.length;n++)e[n].response?i.push(e[n].response.id):i.push(e[n].id);this.$emit("childupload",{file:{},imgid:""})},handlePreview:function(t){console.log(t)},handleoverlimit:function(){this.$message({message:"文件数量超出限制，最多上传1张图片",type:"warning"})},handleUploadSucess:function(t,e,i){this.$message({message:"上传成功",type:"success"}),this.$emit("childupload",{file:e,imgid:t.id})},handledefeat:function(t,e,i){this.$message({message:"上传失败",type:"warning"})},handleChange:function(t,e){}}},o=(i("q4h8"),i("KHd+")),c=Object(o.a)(l,function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("el-upload",{staticClass:"upload-demo",attrs:{limit:2,"on-preview":t.handlePreview,"on-remove":t.handleRemove,"on-success":t.handleUploadSucess,accept:t.accepttype,"on-exceed":t.handleoverlimit,"on-error":t.handledefeat,"on-change":t.handleChange,"file-list":t.filelist,action:t.baseurl,"list-type":"picture"}},[i("el-button",{attrs:{size:"small",type:"primary"}},[t._v("点击上传")]),t._v(" "),i("div",{staticClass:"el-upload__tip",attrs:{slot:"tip"},slot:"tip"},[t._v("(注：只能上传jpg/png文件，推荐大小 "+t._s(t.size)+" )")])],1)],1)},[],!1,null,"3a16ad55",null);c.options.__file="UploadListimg.vue";e.a=c.exports},uwNo:function(t,e,i){"use strict";i.r(e);var n=i("QbLZ"),s=i.n(n),a=i("rr1a"),l=i("ZySA"),o=i("L2JU"),c=i("Pf+D"),r={name:"ComplexTable",directives:{waves:l.a},components:{UploadListimg:a.a},data:function(){return{form:{},customform:{},bannerlist:[],dialogFormVisible:!1,dialogStatus:"",textMap:{update:"修改",create:"添加"},selectid:"",projectlist:[]}},computed:s()({filelist:function(){var t=this.$store.state.setindex.img,e=this.$store.state.setindex.imgPath,i=[];return t?(i.push({id:t,name:e[0].name,url:e[0].path}),i):[]},filelistbanner:function(){var t=this.$store.state.setindex.bannertemp,e=[];return t.img_id?(e.push({id:t.img_id,name:t.img_name,url:t.img_patch}),e):[]}},Object(o.c)({dic:function(t){return t.setindex},temp:function(t){return t.setindex.bannertemp},img:function(t){return t.setindex.img},imgPath:function(t){return t.setindex.imgPath}})),created:function(){this.handleinit(),this.handlegetproject(),this.handleinitdic()},methods:{handleinit:function(){var t=this;Object(c.i)(this).then(function(e){t.bannerlist=e})},handleinitdic:function(){var t=this;Object(c.r)(this).then(function(e){t.$store.dispatch("setindex/setindeximg",e)})},handlegetproject:function(){var t=this;Object(c.g)(this).then(function(e){t.projectlist=e.list})},handleUpdate:function(t){this.$store.dispatch("setindex/setbanneritem",t),this.selectitem=t.banner_id,this.dialogStatus="update",this.dialogFormVisible=!0},handleDelete:function(t){var e=this;this.$confirm("此操作将删除该条数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){Object(c.e)(t.banner_id).then(function(t){"success"===t.status.state&&(e.handleinit(),e.$message({type:"success",message:"删除成功!"}))})}).catch(function(){})},handleCreate:function(){this.$store.dispatch("setindex/resetbannertemp"),this.dialogStatus="create",this.dialogFormVisible=!0},handlecreateData:function(){var t=this;Object(c.b)(this.temp).then(function(e){"success"===e.status.state&&(t.handleinit(),t.$message({type:"success",message:"添加成功!"}))}),this.dialogFormVisible=!1},handleupdateData:function(t){var e=this;Object(c.m)(this.temp,this.selectitem).then(function(t){"success"===t.status.state&&(e.handleinit(),e.$message({type:"success",message:"修改成功!"}))}),this.dialogFormVisible=!1},handleUpload:function(){var t=this;this.$confirm("确认提交修改内容, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){Object(c.p)(t.img,t.dic.dic).then(function(e){"success"===e.status.state&&(t.handleinitdic(),t.$message({type:"success",message:"设置成功!"}))})}).catch(function(){})},childupload:function(t){this.$store.dispatch("setindex/setworkbenchimg",t)},childuploadbanner:function(t){this.$store.dispatch("setindex/setbannerimg",t)}}},d=(i("mLXK"),i("KHd+")),u=Object(d.a)(r,function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("el-row",{staticClass:"el-row"},[i("el-col",{attrs:{span:4}},[i("div",{staticClass:"grid-content grid-content-l"},[t._v("\n          首页轮播图\n        ")])]),t._v(" "),i("el-col",{attrs:{span:20}},[i("div",{staticClass:"grid-content grid-content-r"},[i("div",{staticClass:"filter-container"},[i("el-button",{staticClass:"filter-item",staticStyle:{"margin-left":"10px"},attrs:{type:"primary",icon:"el-icon-plus"},on:{click:t.handleCreate}},[t._v("添加轮播图\n            ")])],1),t._v(" "),i("el-table",{staticStyle:{width:"100%"},attrs:{data:t.bannerlist,border:"",fit:""}},[i("el-table-column",{attrs:{align:"center",type:"index",label:"序号",width:"65"}}),t._v(" "),i("el-table-column",{attrs:{"class-name":"status-col",label:"图片"},scopedSlots:t._u([{key:"default",fn:function(t){return[i("div",{staticStyle:{width:"80px",height:"50px","-webkit-border-radius":"10px","-moz-border-radius":"10px","border-radius":"10px",overflow:"hidden",margin:"auto"}},[i("img",{staticStyle:{width:"100%",height:"100%"},attrs:{src:t.row.path,alt:""}})])]}}])}),t._v(" "),i("el-table-column",{attrs:{"class-name":"status-col",label:"关联任务"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n                "+t._s(e.row.title)+"\n              ")]}}])}),t._v(" "),i("el-table-column",{attrs:{align:"center",label:"操作",width:"200","class-name":"small-padding fixed-width"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("i",{staticClass:"el-icon-edit el-icon",on:{click:function(i){t.handleUpdate(e.row)}}}),t._v(" "),i("i",{staticClass:"el-icon-delete el-icon",on:{click:function(i){t.handleDelete(e.row)}}})]}}])})],1)],1)])],1),t._v(" "),i("el-row",{staticClass:"el-row"},[i("el-col",{attrs:{span:4}},[i("div",{staticClass:"grid-content grid-content-l"},[t._v("\n          工作台首页图\n        ")])]),t._v(" "),i("el-col",{attrs:{span:20}},[i("div",{staticClass:"grid-content grid-content-r"},[i("upload-listimg",{attrs:{filelist:t.filelist,proptitle:"测试标题",size:"355*100"},on:{childupload:t.childupload}})],1)])],1),t._v(" "),i("el-row",{staticClass:"el-row"},[i("el-col",{attrs:{span:4}},[i("div",{staticClass:"grid-content grid-content-l"},[t._v("\n          自定义字段\n        ")])]),t._v(" "),i("el-col",{attrs:{span:20}},[i("div",{staticClass:"grid-content grid-content-r"},[i("el-form",{ref:"form",attrs:{model:t.customform}},[i("el-form-item",[i("el-input",{attrs:{placeholder:"佣金"},model:{value:t.dic.dic,callback:function(e){t.$set(t.dic,"dic",e)},expression:"dic.dic"}},[i("template",{slot:"prepend"},[t._v("佣金")])],2)],1)],1)],1)])],1),t._v(" "),i("el-row",[i("div",{staticStyle:{background:"#ffffff","text-align":"right","margin-top":"3px",padding:"20px"}},[i("el-button",{attrs:{type:"primary"},on:{click:t.handleUpload}},[t._v("提交")])],1)]),t._v(" "),i("el-dialog",{attrs:{title:t.textMap[t.dialogStatus],visible:t.dialogFormVisible},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[i("el-form",{staticStyle:{"min-width":"400px","max-width":"700px","margin-left":"50px"},attrs:{model:t.temp,"label-position":"left","label-width":"70px"}},[i("el-form-item",{attrs:{label:"关联任务"}},[i("el-select",{staticStyle:{width:"100%"},attrs:{filterable:"",clearable:"",placeholder:"请选择一个项目作为跳转链接"},model:{value:t.temp.params,callback:function(e){t.$set(t.temp,"params",e)},expression:"temp.params"}},t._l(t.projectlist,function(t){return i("el-option",{key:t.id,attrs:{label:t.name,value:t.id}})}))],1),t._v(" "),i("el-form-item",{attrs:{label:"图片选择",prop:"description"}},[i("upload-listimg",{attrs:{filelist:t.filelistbanner,proptitle:"测试标题",size:"355*100"},on:{childupload:t.childuploadbanner}})],1)],1),t._v(" "),i("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.dialogFormVisible=!1}}},[t._v("取消")]),t._v(" "),"create"==t.dialogStatus?i("el-button",{attrs:{type:"primary"},on:{click:t.handlecreateData}},[t._v("添加")]):i("el-button",{attrs:{type:"primary"},on:{click:t.handleupdateData}},[t._v("修改")])],1)],1)],1)},[],!1,null,"79358c2c",null);u.options.__file="setcustom.vue";e.default=u.exports}}]);
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0a98e576"],{"572a":function(e,t,a){},6736:function(e,t,a){"use strict";var n=a("572a"),o=a.n(n);o.a},"9a38":function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"warehouse mainPage"},[a("div",{staticClass:"filterBox"},[a("el-button",{directives:[{name:"btnRole",rawName:"v-btnRole",value:22,expression:"22"}],attrs:{type:"primary",size:"small"},on:{click:e.handleCreate}},[e._v("新增仓库")]),e._v(" "),a("el-input",{staticClass:"keywords fr",attrs:{size:"small",placeholder:"请输入仓库名称"},model:{value:e.keywords,callback:function(t){e.keywords=t},expression:"keywords"}},[a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:e.handleSearch},slot:"append"})],1)],1),e._v(" "),a("div",{staticClass:"tableContent"},[a("el-table",{staticStyle:{width:"100%"},attrs:{data:e.tableData,border:""}},[a("el-table-column",{attrs:{type:"index",label:"编号",width:"50"}}),e._v(" "),a("el-table-column",{attrs:{prop:"name",label:"仓库名称",width:"180"}}),e._v(" "),a("el-table-column",{attrs:{prop:"address",label:"仓库地址"}}),e._v(" "),a("el-table-column",{attrs:{prop:"desc",label:"描述"}}),e._v(" "),a("el-table-column",{attrs:{prop:"store_count",width:"120",label:"在库设备数"}}),e._v(" "),a("el-table-column",{attrs:{align:"left",prop:"status",width:"120",label:"仓库状态"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[a("el-switch",{attrs:{"active-value":1,"inactive-value":2,"active-color":"#29cc85","inactive-color":"#888888"},on:{change:function(a){return e.handleStatusChange(t.row)}},model:{value:t.row.status,callback:function(a){e.$set(t.row,"status",a)},expression:"scope.row.status"}})],1)]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"操作",width:"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{directives:[{name:"btnRole",rawName:"v-btnRole",value:23,expression:"23"}],attrs:{size:"mini",type:"text"},on:{click:function(a){return e.handleEdit(t.row)}}},[e._v("编辑")]),e._v(" "),a("el-button",{attrs:{size:"mini",type:"text"},on:{click:function(a){return e.handleDelete(t.row)}}},[e._v("删除")])]}}])})],1),e._v(" "),a("myPagination",{attrs:{pageInfo:e.pageInfo},on:{"current-change":e.pageChange}})],1),e._v(" "),a("el-dialog",{attrs:{title:(e.isEdit?"编辑":"新增")+"仓库信息",visible:e.dialogShow,"before-close":e.handleClose,"close-on-click-modal":!1,width:"500px"},on:{"update:visible":function(t){e.dialogShow=t}}},[a("el-form",{ref:"warehouseForm",staticClass:"warehouseForm",attrs:{model:e.formData,rules:e.rules,"label-position":"top","label-width":"100px"}},[a("el-form-item",{attrs:{label:"仓库名称",prop:"name"}},[a("el-input",{attrs:{placeholder:"请填写仓库名称"},model:{value:e.formData.name,callback:function(t){e.$set(e.formData,"name",t)},expression:"formData.name"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"仓库地址",prop:"address"}},[a("el-input",{attrs:{placeholder:"请填写仓库地址"},model:{value:e.formData.address,callback:function(t){e.$set(e.formData,"address",t)},expression:"formData.address"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"描述",prop:"desc"}},[a("el-input",{attrs:{type:"textarea","show-word-limit":!0,maxlength:"100",rows:"4",resize:"none",placeholder:"不超过100个字"},model:{value:e.formData.desc,callback:function(t){e.$set(e.formData,"desc",t)},expression:"formData.desc"}})],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{size:"small"},on:{click:e.handleCancel}},[e._v("取消")]),e._v(" "),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.handleSubmit}},[e._v("提交")])],1)],1)],1)],1)},o=[],r=(a("5ab2"),a("6d57"),a("e10e"),a("200d")),s=a("de9f"),l=a("b775");function i(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function c(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?i(Object(a),!0).forEach((function(t){Object(r["a"])(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):i(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var u={name:"warehouse",data:function(){return{keywords:"",tableData:[],isEdit:!1,dialogShow:!1,formData:{name:"",address:"",desc:""},rules:{name:[{required:!0,message:"请填写仓库名称",trigger:"blur"}]},pageInfo:{total:0,pageSize:20,currentPage:1}}},created:function(){this.getStoreHouses()},methods:{handleSearch:function(){this.pageInfo.currentPage=1,this.getStoreHouses()},getStoreHouses:function(){var e=this;Object(l["a"])(this.ajaxUrl.storeHouses,{page:this.pageInfo.currentPage,pageSize:this.pageInfo.pageSize,name:this.keywords},"get").then((function(t){e.tableData=t.data,e.pageInfo.total=t.total})).catch((function(e){console.log(e)}))},pageChange:function(e){this.pageInfo.currentPage=e,this.getStoreHouses()},handleCreate:function(){this.isEdit=!1,this.dialogShow=!0},handleStatusChange:function(e){Object(l["a"])(this.ajaxUrl.updateStoreStatus+e.id+"/status",{status:e.status},"put").then((function(e){})).catch((function(e){console.log(e)}))},handleEdit:function(e){var t=this;this.isEdit=!0,this.dialogShow=!0,this.$nextTick((function(){t.formData=c({},e)}))},handleClose:function(e){this.$refs["warehouseForm"].resetFields(),e()},handleCancel:function(){this.$refs["warehouseForm"].resetFields(),this.dialogShow=!1},handleSubmit:function(){var e=this;this.$refs["warehouseForm"].validate((function(t){if(!t)return console.log("error submit!!"),!1;e.isEdit?Object(l["a"])(e.ajaxUrl.editStoreHouse+e.formData.id,c({},e.formData),"put").then((function(t){e.$message.success("仓库编辑成功"),e.dialogShow=!1,e.getStoreHouses()})).catch((function(e){console.log(e)})):Object(l["a"])(e.ajaxUrl.addStoreHouse,c({},e.formData)).then((function(t){e.$message.success("仓库新增成功"),e.$refs["warehouseForm"].resetFields(),e.dialogShow=!1,e.getStoreHouses()})).catch((function(e){}))}))},handleDelete:function(e){var t=this;this.$confirm("确定删除该仓库","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(l["a"])(t.ajaxUrl.delStoreHouse+e.id,{},"delete").then((function(e){t.$message.success("仓库删除成功"),t.getStoreHouses()})).catch((function(e){console.log(e)}))})).catch((function(){}))},handleCurrentChange:function(e){this.pageInfo.currentPage=e}},components:{myPagination:s["a"]}},d=u,f=(a("bd55"),a("9ca4")),h=Object(f["a"])(d,n,o,!1,null,null,null);t["default"]=h.exports},bd55:function(e,t,a){"use strict";var n=a("c410"),o=a.n(n);o.a},c410:function(e,t,a){},de9f:function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"myPagination"},[a("div",{staticClass:"total"},[e._v("\n        共"+e._s(e.pageInfo.total)+"条数据，"+e._s(Math.ceil(e.pageInfo.total/e.pageInfo.pageSize))+"页\n    ")]),e._v(" "),a("el-pagination",{attrs:{background:"",layout:"prev, pager, next","page-size":e.pageInfo.pageSize,"current-page":e.pageInfo.currentPage,total:e.pageInfo.total},on:{"current-change":e.handleCurrentChange}})],1)},o=[],r={name:"myPagination",props:["pageInfo"],data:function(){return{}},created:function(){},methods:{handleCurrentChange:function(e){this.$emit("current-change",e)}}},s=r,l=(a("6736"),a("9ca4")),i=Object(l["a"])(s,n,o,!1,null,"dabd56de",null);t["a"]=i.exports}}]);
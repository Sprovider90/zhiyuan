(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-c49bd8fe"],{1162:function(t,e,a){},"572a":function(t,e,a){},6736:function(t,e,a){"use strict";var n=a("572a"),i=a.n(n);i.a},de9f:function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"myPagination"},[a("div",{staticClass:"total"},[t._v("\n        共"+t._s(t.pageInfo.total)+"条数据，"+t._s(Math.ceil(t.pageInfo.total/t.pageInfo.pageSize))+"页\n    ")]),t._v(" "),a("el-pagination",{attrs:{background:"",layout:"prev, pager, next","page-size":t.pageInfo.pageSize,"current-page":t.pageInfo.currentPage,total:t.pageInfo.total},on:{"current-change":t.handleCurrentChange}})],1)},i=[],o={name:"myPagination",props:["pageInfo"],data:function(){return{}},created:function(){},methods:{handleCurrentChange:function(t){this.$emit("current-change",t)}}},c=o,r=(a("6736"),a("9ca4")),s=Object(r["a"])(c,n,i,!1,null,"dabd56de",null);e["a"]=s.exports},df63:function(t,e,a){"use strict";var n=a("1162"),i=a.n(n);i.a},df9c:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"Troubleshooting mainPage"},[a("div",{staticClass:"filters"},[a("div",{staticClass:"filterBox"},[a("div",{staticClass:"filterItem"},[a("span",[t._v("设备ID")]),t._v(" "),a("el-input",{attrs:{type:"text",size:"small",clearable:"",placeholder:"请输入设备ID"},model:{value:t.searchInfo.device_id,callback:function(e){t.$set(t.searchInfo,"device_id",e)},expression:"searchInfo.device_id"}}),t._v(" "),a("br")],1),t._v(" "),a("div",{staticClass:"filterItem"},[a("span",[t._v("项目名称")]),t._v(" "),a("el-select",{attrs:{filterable:"",clearable:"",size:"small",placeholder:"请选择项目名称"},model:{value:t.searchInfo.project_id,callback:function(e){t.$set(t.searchInfo,"project_id",e)},expression:"searchInfo.project_id"}},t._l(t.projectList,(function(t){return a("el-option",{key:t.id,attrs:{label:t.name,value:t.id}})})),1),t._v(" "),a("br")],1),t._v(" "),a("br"),t._v(" "),a("div",{staticClass:"filterItem"},[a("span"),t._v(" "),a("el-button",{attrs:{type:"primary",size:"small"},on:{click:t.handleSearch}},[t._v("查询")]),t._v(" "),a("el-button",{attrs:{type:"text",size:"small"},on:{click:t.handleResize}},[t._v("重置查询条件")])],1)])]),t._v(" "),a("div",{staticClass:"content"},[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.tableData,border:""}},[a("el-table-column",{attrs:{prop:"id",width:"120",label:"故障ID"}}),t._v(" "),a("el-table-column",{attrs:{prop:"device_id",label:"设备ID"}}),t._v(" "),a("el-table-column",{attrs:{label:"所属项目"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\r\n\t\t\t\t\t"+t._s(e.row.project&&e.row.project.name)+"\r\n\t\t\t\t")]}}])}),t._v(" "),a("el-table-column",{attrs:{prop:"type",label:"故障类型"}}),t._v(" "),a("el-table-column",{attrs:{prop:"happen_time",label:"故障时间"}}),t._v(" "),a("el-table-column",{attrs:{label:"设备当前状态"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\r\n\t\t\t\t\t"+t._s(e.row.devices&&t.publicObj.runStatus[e.row.devices.run_status])+"\r\n\t\t\t\t")]}}])})],1),t._v(" "),a("myPagination",{attrs:{pageInfo:t.pageInfo},on:{"current-change":t.pageChange}})],1)])},i=[],o=a("de9f"),c=a("b775"),r={components:{myPagination:o["a"]},data:function(){return{searchInfo:{device_id:"",project_id:""},projectList:[],tableData:[],pageInfo:{pageSize:20,currentPage:1,total:0}}},created:function(){this.getBreakDownList(),this.getProjectList()},methods:{getProjectList:function(){var t=this;Object(c["a"])(this.ajaxUrl.projectsList,{pageSize:99999,page:1},"get").then((function(e){t.projectList=e.data})).catch((function(t){console.log(t)}))},handleSearch:function(){this.pageInfo.currentPage=1,this.getBreakDownList()},handleResize:function(){this.searchInfo={device_id:"",project_id:""}},getBreakDownList:function(){var t=this;Object(c["a"])(this.ajaxUrl.breakDownList,{include:"project,devices",project_id:this.searchInfo.project_id,device_id:this.searchInfo.device_id,page:this.pageInfo.currentPage,pageSize:this.pageInfo.pageSize},"get").then((function(e){t.tableData=e.data,t.pageInfo.total=e.meta.total})).catch((function(t){console.log(t)}))},pageChange:function(t){this.pageInfo.currentPage=t,this.getBreakDownList()}}},s=r,l=(a("df63"),a("9ca4")),p=Object(l["a"])(s,n,i,!1,null,"f786a688",null);e["default"]=p.exports}}]);
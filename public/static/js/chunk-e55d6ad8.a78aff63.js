(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e55d6ad8"],{"13e2":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"projectCheck mainPage"},[a("div",{staticClass:"project-item"},[a("div",{staticClass:"item-title"},[t._v("项目信息")]),t._v(" "),a("ul",{staticClass:"info-list"},[a("li",[a("span",[t._v("项目编号")]),t._v(" "),a("span",[t._v(t._s(t.projectInfo.number))])]),t._v(" "),a("li",[a("span",[t._v("项目名称")]),t._v(" "),a("span",[t._v(t._s(t.projectInfo.name))])]),t._v(" "),a("li",[a("span",[t._v("所属客户")]),t._v(" "),a("span",[t._v(t._s(t.projectInfo.customs[0]&&t.projectInfo.customs[0].company_name))])]),t._v(" "),a("li",[a("span",[t._v("项目地址")]),t._v(" "),a("span",[t._v(t._s(t.projectInfo.address))])]),t._v(" "),a("li",[a("span",[t._v("创建时间")]),t._v(" "),a("span",[t._v(t._s(t.projectInfo.created_at))])]),t._v(" "),a("li",[a("span",[t._v("开始时间")]),t._v(" "),a("span",[t._v(t._s(t.projectInfo.start_time))])]),t._v(" "),a("li",[a("span",[t._v("结束时间")]),t._v(" "),a("span",[t._v(t._s(t.projectInfo.end_time))])])])]),t._v(" "),a("div",{staticClass:"project-item"},[a("div",{staticClass:"item-title"},[t._v("项目状态")]),t._v(" "),a("el-steps",{attrs:{active:t.nowStage,"align-center":""}},t._l(t.projectInfo.stages,(function(t,e){return a("el-step",{key:e,attrs:{title:t.stage_name,description:t.start_date}})})),1)],1),t._v(" "),a("div",{staticClass:"project-item"},[a("div",{staticClass:"item-title"},[t._v("监测点详情")]),t._v(" "),a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.tableContent,border:""}},[a("el-table-column",{attrs:{align:"left",prop:"name",label:"监测点名称"}}),t._v(" "),a("el-table-column",{attrs:{prop:"address",align:"left",label:"所处区域"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n                    "+t._s(e.row.area&&e.row.area.area_name)+"\n                ")]}}])}),t._v(" "),a("el-table-column",{attrs:{align:"left",prop:"member",label:"设备运行状态",width:"180"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("div",{staticClass:"table-status"},[a("span",{class:"color"+e.row.status},[t._v(t._s(1==e.row.status?"运行中":"已停止"))])])]}}])}),t._v(" "),a("el-table-column",{attrs:{align:"left",label:"操作",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{type:"text"},on:{click:function(a){return t.$router.push({name:"inquireDetail",params:{id:e.row.id}})}}},[t._v("查看数据")])]}}])})],1),t._v(" "),a("myPagination",{attrs:{pageInfo:t.pageInfo},on:{"current-change":t.pageChange}})],1)])},s=[],o=a("de9f"),i=a("b775"),r={name:"projectCheck",data:function(){return{id:this.$route.params.id,projectInfo:{stages:[],customs:[]},tableContent:[],pageInfo:{total:0,pageSize:20,currentPage:1},nowStage:1}},created:function(){this.getProjectInfo(),this.getPositionList()},methods:{getProjectInfo:function(){var t=this;Object(i["a"])(this.ajaxUrl.projectsInfo+this.id,{},"get").then((function(e){t.projectInfo=e,t.projectInfo.stages.push({stage_name:"项目结束",start_date:e.stages[e.stages.length-1].end_date}),2===e.status?t.nowStage=e.stages.length:e.stages.map((function(a,n){a.id===e.stage_id&&(t.nowStage=n+1)}))})).catch((function(t){console.log(t)}))},getPositionList:function(){var t=this;Object(i["a"])(this.ajaxUrl.areaList+this.id+"/area",{page:this.pageInfo.currentPage,pageSize:this.pageInfo.pageSize},"get").then((function(e){t.pageInfo.total=e.total,t.tableContent=e.data})).catch((function(t){console.log(t)}))},pageChange:function(t){this.pageInfo.currentPage=t,this.getPositionList()}},components:{myPagination:o["a"]}},c=r,l=(a("a763"),a("9ca4")),p=Object(l["a"])(c,n,s,!1,null,null,null);e["default"]=p.exports},"4c85":function(t,e,a){},"572a":function(t,e,a){},6736:function(t,e,a){"use strict";var n=a("572a"),s=a.n(n);s.a},a763:function(t,e,a){"use strict";var n=a("4c85"),s=a.n(n);s.a},de9f:function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"myPagination"},[a("div",{staticClass:"total"},[t._v("\n        共"+t._s(t.pageInfo.total)+"条数据，"+t._s(Math.ceil(t.pageInfo.total/t.pageInfo.pageSize))+"页\n    ")]),t._v(" "),a("el-pagination",{attrs:{background:"",layout:"prev, pager, next","page-size":t.pageInfo.pageSize,"current-page":t.pageInfo.currentPage,total:t.pageInfo.total},on:{"current-change":t.handleCurrentChange}})],1)},s=[],o={name:"myPagination",props:["pageInfo"],data:function(){return{}},created:function(){},methods:{handleCurrentChange:function(t){this.$emit("current-change",t)}}},i=o,r=(a("6736"),a("9ca4")),c=Object(r["a"])(i,n,s,!1,null,"dabd56de",null);e["a"]=c.exports}}]);
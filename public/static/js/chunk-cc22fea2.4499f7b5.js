(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-cc22fea2"],{"484f":function(e,t,a){"use strict";var r,o=a("200d");a("9dd9"),a("9a33");t["a"]=(r={checkUsername:function(e,t,a){var r=/^[a-zA-Z][a-zA-Z0-9]+$/;r.test(t)?t.length<6||t.length>20?a(new Error("最小6位，最大20位")):a():a(new Error("只支持字母、字母和数字组合，首位必须为字母"))},checkPassword:function(e,t,a){var r=/^[a-zA-Z0-9]{6,20}$/;r.test(t)?a():a(new Error("密码只能是数字、字母,长度在6-20之间"))},checkNumber:function(e,t,a){""!==t&&(isNaN(t)?a(new Error("只能为数字")):a())},checkMoney:function(e,t,a){var r=String(t).split(".")[1]||"";isNaN(t)?a(new Error("请输入正确的价格")):r.length>2?a(new Error("小数点后最多两位")):a()},checkCode:function(e,t,a){if(""==t)a(new Error("请输入分类code"));else{var r=new RegExp("^[a-zA-Z]+$");r.test(t)?a():a(new Error("分类code只能为字母"))}},checkPhone:function(e,t,a){if(""!==t){var r=new RegExp("^[1][0-9]{10}$");r.test(t)?a():a(new Error("请输入合规的手机号码"))}},checkChinese:function(e,t,a){/^[\u4e00-\u9fa5]+$/.test(t)?a():a(new Error("只能是中文"))},notSymbol:function(e,t,a){var r=/^[A-Za-z0-9\u4e00-\u9fa5]+$/;r.test(t)?a():a(new Error("只能输入汉字、数字、大小写字母"))},checkText:function(e,t,a){/[\u4e00-\u9fa5]/.text(t)?a():a(new Error("只能包含汉字、中文标点符号"))},checkJQ:function(e,t,a){var r=String(t).split(".")[1]||"";t>.3||t<0?a(new Error("最大0.3，最小0")):r.length>3?a(new Error("小数点后最多3位")):a()},checkTOVC:function(e,t,a){var r=String(t).split(".")[1]||"";t>1||t<0?a(new Error("最大 1，最小 0")):r.length>3?a(new Error("小数点后最多3位")):a()},checkPM:function(e,t,a){var r=String(t).split(".")[1]||"";t>100||t<0?a(new Error("最大 100，最小 0")):r.length>3?a(new Error("小数点后最多3位")):a()},checkCO:function(e,t,a){var r=String(t).split(".")[1]||"";t>1500||t<0?a(new Error("最大 100，最小 0")):r.length>2?a(new Error("小数点后最多2位")):a()},checkTP:function(e,t,a){var r=String(t).split(".")[1]||"";t>50||t<-50?a(new Error("最大 50，最小 -50")):r.length>1?a(new Error("小数点后最多1位")):a()}},Object(o["a"])(r,"checkTP",(function(e,t,a){var r=String(t).split(".")[1]||"";t>100||t<0?a(new Error("最大 100，最小 0")):r.length>0?a(new Error("只能为整数")):a()})),Object(o["a"])(r,"checkDecimal",(function(e){var t=String(e).split(".")[1]||"";return!isNaN(e)&&!(t.length>2)})),r)},"499b":function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"userManagement mainPage"},[a("div",{staticClass:"filterBox"},[a("div",{staticClass:"filterItem"},[a("el-button",{directives:[{name:"btnRole",rawName:"v-btnRole",value:34,expression:"34"}],attrs:{type:"primary",size:"small"},on:{click:e.handleAddUser}},[e._v("新增用户")])],1),e._v(" "),a("div",{staticClass:"filterItem fr"},[a("el-input",{staticClass:"keywords fr",attrs:{size:"small",placeholder:"输入关键字查询"},model:{value:e.searchInfo.keyword,callback:function(t){e.$set(e.searchInfo,"keyword",t)},expression:"searchInfo.keyword"}},[a("el-button",{attrs:{slot:"append",icon:"el-icon-search"},on:{click:e.handleSearch},slot:"append"})],1)],1)]),e._v(" "),a("el-table",{staticStyle:{width:"100%","margin-top":"20px"},attrs:{data:e.userList,border:"",fit:""}},[a("el-table-column",{attrs:{prop:"id",label:"ID",align:"left"}}),e._v(" "),a("el-table-column",{attrs:{prop:"name",label:"用户名",align:"left"}}),e._v(" "),a("el-table-column",{attrs:{prop:"truename",label:"姓名",align:"left"}}),e._v(" "),a("el-table-column",{attrs:{prop:"phone",label:"电话",align:"left"}}),e._v(" "),a("el-table-column",{attrs:{label:"账号类型",align:"left",width:"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[1==t.row.type?a("p",[e._v("数据中心")]):e._e(),e._v(" "),2==t.row.type?a("p",[e._v("客户平台")]):e._e()]}}])}),e._v(" "),a("el-table-column",{attrs:{prop:"customs",label:"所属公司",align:"left"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("\n                "+e._s(t.row.customer&&t.row.customer.company_name)+"\n            ")]}}])}),e._v(" "),a("el-table-column",{attrs:{prop:"role",label:"角色",align:"left",width:"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("\n                "+e._s(t.row.roles&&t.row.roles.map((function(e){return e.name})).join(","))+"\n            ")]}}])}),e._v(" "),a("el-table-column",{attrs:{prop:"status",label:"启用状态",align:"left",width:"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",{directives:[{name:"btnRole",rawName:"v-btnRole",value:36,expression:"36"}]},[a("el-switch",{attrs:{"active-value":1,"inactive-value":0,"active-color":"#29cc85","inactive-color":"#888888"},on:{change:function(a){return e.handleStatusChange(t.row)}},model:{value:t.row.status,callback:function(a){e.$set(t.row,"status",a)},expression:"scope.row.status"}})],1)]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"操作",align:"left"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{directives:[{name:"btnRole",rawName:"v-btnRole",value:35,expression:"35"}],attrs:{type:"text"},on:{click:function(a){return e.handleEdit(t.row,1)}}},[e._v("编辑")])]}}])})],1),e._v(" "),a("div",{staticClass:"pagination"},[a("myPagination",{attrs:{pageInfo:e.pageInfo},on:{"current-change":e.pageChange}})],1),e._v(" "),a("el-dialog",{attrs:{title:"新增用户",visible:e.addDialogShow,"before-close":e.handleClose,"close-on-click-modal":!1,width:"500px"},on:{"update:visible":function(t){e.addDialogShow=t}}},[a("el-form",{ref:"addUserForm",staticClass:"warehouseForm",attrs:{model:e.addFormData,rules:e.addRules,"label-width":"100px"}},[a("el-form-item",{attrs:{label:"用户名",prop:"username"}},[a("el-input",{attrs:{size:"small",clearable:"",maxlength:"20",placeholder:"请输入用户名"},model:{value:e.addFormData.username,callback:function(t){e.$set(e.addFormData,"username",t)},expression:"addFormData.username"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"手机号",prop:"phone"}},[a("el-input",{attrs:{size:"small",clearable:"",maxlength:"11",placeholder:"请输入手机号码"},model:{value:e.addFormData.phone,callback:function(t){e.$set(e.addFormData,"phone",t)},expression:"addFormData.phone"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"姓名",prop:"truename"}},[a("el-input",{attrs:{size:"small",maxlength:"6",placeholder:"请输入姓名"},model:{value:e.addFormData.truename,callback:function(t){e.$set(e.addFormData,"truename",t)},expression:"addFormData.truename"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"密码",prop:"password"}},[a("el-input",{attrs:{"show-password":"",clearable:"",size:"small",maxlength:"20",placeholder:"请输入密码"},model:{value:e.addFormData.password,callback:function(t){e.$set(e.addFormData,"password",t)},expression:"addFormData.password"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"确认密码",prop:"checkPass"}},[a("el-input",{attrs:{"show-password":"",clearable:"",size:"small",maxlength:"20",placeholder:"确认密码"},model:{value:e.addFormData.checkPass,callback:function(t){e.$set(e.addFormData,"checkPass",t)},expression:"addFormData.checkPass"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"账户类型",prop:"userType"}},[a("el-select",{attrs:{size:"small",placeholder:"请选择账户类型"},model:{value:e.addFormData.userType,callback:function(t){e.$set(e.addFormData,"userType",t)},expression:"addFormData.userType"}},[a("el-option",{attrs:{value:"1",label:"数据中心"}}),e._v(" "),a("el-option",{attrs:{value:"2",label:"客户平台"}})],1)],1),e._v(" "),e.showCustoms?a("el-form-item",{attrs:{label:"所属客户",prop:"customs"}},[a("el-select",{attrs:{size:"small",placeholder:"请选择所属客户"},model:{value:e.addFormData.customs,callback:function(t){e.$set(e.addFormData,"customs",t)},expression:"addFormData.customs"}},e._l(e.customsList,(function(e){return a("el-option",{key:e.id,attrs:{label:e.company_name,value:e.id}})})),1)],1):e._e(),e._v(" "),a("el-form-item",{attrs:{label:"角色",prop:"role"}},[a("el-select",{attrs:{size:"small",multiple:"",placeholder:"请选择角色"},model:{value:e.addFormData.role,callback:function(t){e.$set(e.addFormData,"role",t)},expression:"addFormData.role"}},e._l(e.roleList,(function(e){return a("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})),1)],1),e._v(" "),a("div",{staticStyle:{"text-align":"center"}},[a("el-button",{attrs:{size:"small"},on:{click:function(t){return e.handleCancel(1)}}},[e._v("取消")]),e._v(" "),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.handleSubmit(1)}}},[e._v("确定")])],1)],1)],1),e._v(" "),a("el-dialog",{attrs:{title:"编辑用户",visible:e.editDialogShow,"before-close":e.handleClose,"close-on-click-modal":!1,width:"500px"},on:{"update:visible":function(t){e.editDialogShow=t}}},[a("el-form",{ref:"editUserForm",staticClass:"warehouseForm",attrs:{model:e.editFormData,rules:e.editRules,"label-width":"100px"}},[a("el-form-item",{attrs:{label:"用户名",prop:"username"}},[a("el-input",{attrs:{size:"small",disabled:"",placeholder:"请输入用户名"},model:{value:e.editFormData.username,callback:function(t){e.$set(e.editFormData,"username",t)},expression:"editFormData.username"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"手机号",prop:"phone"}},[a("el-input",{attrs:{size:"small",maxlength:"11",placeholder:"请输入手机号码"},model:{value:e.editFormData.phone,callback:function(t){e.$set(e.editFormData,"phone",t)},expression:"editFormData.phone"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"姓名",prop:"truename"}},[a("el-input",{attrs:{size:"small",maxlength:"6",placeholder:"请输入姓名"},model:{value:e.editFormData.truename,callback:function(t){e.$set(e.editFormData,"truename",t)},expression:"editFormData.truename"}})],1),e._v(" "),1!=e.editFormData.type?a("el-form-item",{attrs:{label:"所属客户",prop:"customs"}},[a("el-select",{attrs:{size:"small",disabled:"",placeholder:"请选择所属客户"},model:{value:e.editFormData.customs,callback:function(t){e.$set(e.editFormData,"customs",t)},expression:"editFormData.customs"}},e._l(e.customsList,(function(e){return a("el-option",{key:e.id,attrs:{label:e.company_name,value:e.id}})})),1)],1):e._e(),e._v(" "),a("el-form-item",{attrs:{label:"角色",prop:"role"}},[a("el-select",{attrs:{size:"small",multiple:"",placeholder:"请选择角色"},model:{value:e.editFormData.role,callback:function(t){e.$set(e.editFormData,"role",t)},expression:"editFormData.role"}},e._l(e.roleList,(function(e){return a("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})),1)],1),e._v(" "),a("el-form-item",{attrs:{label:"密码",prop:"password"}},[a("el-input",{attrs:{"show-password":"",size:"small",maxlength:"20",placeholder:"请输入6-20位登录密码"},model:{value:e.editFormData.password,callback:function(t){e.$set(e.editFormData,"password",t)},expression:"editFormData.password"}}),e._v(" "),a("p",{staticClass:"form-item-hint"},[e._v("如需重置密码请直接填写新密码，保留原密码请留空")])],1),e._v(" "),a("div",{staticStyle:{"text-align":"center"}},[a("el-button",{attrs:{size:"small"},on:{click:function(t){return e.handleCancel(2)}}},[e._v("取消")]),e._v(" "),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(t){return e.handleSubmit(2)}}},[e._v("确定")])],1)],1)],1)],1)},o=[],s=(a("5ab2"),a("6d57"),a("e10e"),a("200d")),n=(a("cc57"),a("de9f")),l=a("b775"),i=a("484f");function c(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,r)}return a}function d(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?c(Object(a),!0).forEach((function(t){Object(s["a"])(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):c(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var u={name:"memberList",components:{myPagination:n["a"]},data:function(){var e=this,t=function(t,a,r){Object(l["a"])(e.ajaxUrl.checkUsername,{name:a},"get").then((function(e){r()})).catch((function(e){r(new Error(e.data.errors.name.join(",")))}))},a=function(t,a,r){""===a?r(new Error("请输入密码")):(""!==e.addFormData.checkPass&&e.$refs.addUserForm.validateField("checkPass"),r())},r=function(t,a,r){""===a?r(new Error("请再次输入密码")):a!==e.addFormData.password?r(new Error("两次输入密码不一致!")):r()};return{searchInfo:{keyword:""},showCustoms:!0,addDialogShow:!1,editDialogShow:!1,addFormData:{username:"",phone:"",truename:"",password:"",checkPass:"",userType:"",customs:"",role:""},editFormData:{username:"",phone:"",truename:"",customs:"",role:"",password:"",type:"",id:""},addRules:{username:[{required:!0,message:"请输入用户名",trigger:"blur"},{validator:i["a"].checkUsername,trigger:"blur"},{validator:t,trigger:"blur"}],phone:[{required:!0,message:"请输入手机号码",trigger:"blur"},{validator:i["a"].checkPhone,trigger:"blur"}],truename:[{required:!0,message:"请输入姓名",trigger:"blur"}],password:[{required:!0,message:"请输入密码",trigger:"blur"},{validator:i["a"].checkPassword,trigger:"blur"},{validator:a,trigger:"blur"}],checkPass:[{required:!0,message:"请输入密码",trigger:"blur"},{validator:i["a"].checkPassword,trigger:"blur"},{validator:r,trigger:"blur"}],userType:[{required:!0,message:"请选择账户类型",trigger:"blur"}],customs:[{required:!0,message:"请选择所属客户",trigger:"blur"}],role:[{required:!0,message:"请选择账户角色",trigger:"blur"}]},editRules:{username:[{required:!0,message:"请输入用户名",trigger:"blur"}],phone:[{required:!0,message:"请输入手机号码",trigger:"blur"},{validator:i["a"].checkPhone,trigger:"blur"}],truename:[{required:!0,message:"请输入姓名",trigger:"blur"}],customs:[{required:!0,message:"请选择所属客户",trigger:"blur"}],role:[{required:!0,message:"请选择账户角色",trigger:"blur"}]},userList:[],roleList:[],customsList:[],pageInfo:{total:0,pageSize:20,currentPage:1}}},created:function(){this.getRoleList(),this.getCustomsList(),this.getUsersList()},methods:{getUsersList:function(){var e=this;Object(l["a"])(this.ajaxUrl.usersList,{page:this.pageInfo.currentPage,pageSize:this.pageInfo.pageSize,reuse_param:this.searchInfo.keyword,include:"customer,roles"},"get").then((function(t){e.userList=t.data,e.pageInfo.total=t.meta.total})).catch((function(e){console.log(e)}))},getCustomsList:function(){var e=this;Object(l["a"])(this.ajaxUrl.customsList,{pageSize:99999,page:1},"get").then((function(t){e.customsList=t.data})).catch((function(e){console.log(e)}))},getRoleList:function(){var e=this;Object(l["a"])(this.ajaxUrl.roleList,{},"get").then((function(t){e.roleList=t.data})).catch((function(e){console.log(e)}))},handleAddUser:function(){this.addDialogShow=!0},handleSearch:function(){this.pageInfo.currentPage=1,this.getUsersList()},handleClose:function(e){this.$refs["addUserForm"]&&this.$refs["addUserForm"].resetFields(),this.$refs["editUserForm"]&&this.$refs["editUserForm"].resetFields(),e()},handleSubmit:function(e){var t=this;1==e?this.$refs["addUserForm"].validate((function(e){if(!e)return console.log("error submit!"),!1;Object(l["a"])(t.ajaxUrl.addUsers,{name:t.addFormData.username,phone:t.addFormData.phone,password:t.addFormData.password,roles:t.addFormData.role.join(","),truename:t.addFormData.truename,type:t.addFormData.userType,customer_id:t.addFormData.customs}).then((function(e){t.$message.success("用户新增成功"),t.$refs["addUserForm"].resetFields(),t.addDialogShow=!1,t.getUsersList()})).catch((function(e){console.log(e)}))})):this.$refs["editUserForm"].validate((function(e){if(!e)return console.log("error submit"),!1;var a={};a=t.editFormData.password?{truename:t.editFormData.truename,roles:t.editFormData.role.join(","),password:t.editFormData.password}:{truename:t.editFormData.truename,roles:t.editFormData.role.join(",")},Object(l["a"])(t.ajaxUrl.editUsers+t.editFormData.id,d({},a),"patch").then((function(e){t.$message.success("用户编辑成功"),t.$refs["editUserForm"].resetFields(),t.editDialogShow=!1,t.getUsersList()}))}))},handleCancel:function(e){1==e?(this.$refs["addUserForm"].resetFields(),this.addDialogShow=!1):(this.$refs["editUserForm"].resetFields(),this.editDialogShow=!1)},pageChange:function(e){this.pageInfo.currentPage=e,this.getUsersList(),console.log(e)},handleEdit:function(e){this.editFormData.username=e.name,this.editFormData.phone=e.phone,this.editFormData.truename=e.truename,this.editFormData.customs=e.customer_id,this.editFormData.role=e.roles.map((function(e){return e.id})),this.editFormData.type=e.type,this.editFormData.id=e.id,this.editDialogShow=!0},handleStatusChange:function(e){var t=this;Object(l["a"])(this.ajaxUrl.editUsers+e.id,{status:e.status},"patch").then((function(e){t.getUsersList()})).catch((function(e){console.log(e)}))}},watch:{"addFormData.userType":function(e,t){1==e?(this.showCustoms=!1,this.addFormData.customs="",console.log("数据中心",this.addFormData)):this.showCustoms=!0,console.log(e)}}},m=u,p=(a("e9ae"),a("9ca4")),h=Object(p["a"])(m,r,o,!1,null,"3e1767da",null);t["default"]=h.exports},"572a":function(e,t,a){},6736:function(e,t,a){"use strict";var r=a("572a"),o=a.n(r);o.a},bffb:function(e,t,a){},de9f:function(e,t,a){"use strict";var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"myPagination"},[a("div",{staticClass:"total"},[e._v("\n        共"+e._s(e.pageInfo.total)+"条数据，"+e._s(Math.ceil(e.pageInfo.total/e.pageInfo.pageSize))+"页\n    ")]),e._v(" "),a("el-pagination",{attrs:{background:"",layout:"prev, pager, next","page-size":e.pageInfo.pageSize,"current-page":e.pageInfo.currentPage,total:e.pageInfo.total},on:{"current-change":e.handleCurrentChange}})],1)},o=[],s={name:"myPagination",props:["pageInfo"],data:function(){return{}},created:function(){},methods:{handleCurrentChange:function(e){this.$emit("current-change",e)}}},n=s,l=(a("6736"),a("9ca4")),i=Object(l["a"])(n,r,o,!1,null,"dabd56de",null);t["a"]=i.exports},e9ae:function(e,t,a){"use strict";var r=a("bffb"),o=a.n(r);o.a}}]);
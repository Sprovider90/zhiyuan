(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-da160334"],{"211b":function(t,e,a){},"484f":function(t,e,a){"use strict";var s,i=a("200d");a("9dd9"),a("9a33");e["a"]=(s={checkUsername:function(t,e,a){var s=/^[a-zA-Z][a-zA-Z0-9]+$/;s.test(e)?e.length<6||e.length>20?a(new Error("最小6位，最大20位")):a():a(new Error("只支持字母、字母和数字组合，首位必须为字母"))},checkPassword:function(t,e,a){var s=/^[a-zA-Z0-9]{6,20}$/;s.test(e)?a():a(new Error("密码只能是数字、字母,长度在6-20之间"))},checkNumber:function(t,e,a){""!==e&&(isNaN(e)?a(new Error("只能为数字")):a())},checkMoney:function(t,e,a){var s=String(e).split(".")[1]||"";isNaN(e)?a(new Error("请输入正确的价格")):s.length>2?a(new Error("小数点后最多两位")):a()},checkCode:function(t,e,a){if(""==e)a(new Error("请输入分类code"));else{var s=new RegExp("^[a-zA-Z]+$");s.test(e)?a():a(new Error("分类code只能为字母"))}},checkPhone:function(t,e,a){if(""!==e){var s=new RegExp("^[1][0-9]{10}$");s.test(e)?a():a(new Error("请输入合规的手机号码"))}},checkChinese:function(t,e,a){/^[\u4e00-\u9fa5]+$/.test(e)?a():a(new Error("只能是中文"))},notSymbol:function(t,e,a){var s=/^[A-Za-z0-9\u4e00-\u9fa5]+$/;s.test(e)?a():a(new Error("只能输入汉字、数字、大小写字母"))},checkText:function(t,e,a){/[\u4e00-\u9fa5]/.text(e)?a():a(new Error("只能包含汉字、中文标点符号"))},checkJQ:function(t,e,a){var s=String(e).split(".")[1]||"";e>.3||e<0?a(new Error("最大0.3，最小0")):s.length>3?a(new Error("小数点后最多3位")):a()},checkTOVC:function(t,e,a){var s=String(e).split(".")[1]||"";e>1||e<0?a(new Error("最大 1，最小 0")):s.length>3?a(new Error("小数点后最多3位")):a()},checkPM:function(t,e,a){var s=String(e).split(".")[1]||"";e>100||e<0?a(new Error("最大 100，最小 0")):s.length>3?a(new Error("小数点后最多3位")):a()},checkCO:function(t,e,a){var s=String(e).split(".")[1]||"";e>1500||e<0?a(new Error("最大 100，最小 0")):s.length>2?a(new Error("小数点后最多2位")):a()},checkTP:function(t,e,a){var s=String(e).split(".")[1]||"";e>50||e<-50?a(new Error("最大 50，最小 -50")):s.length>1?a(new Error("小数点后最多1位")):a()}},Object(i["a"])(s,"checkTP",(function(t,e,a){var s=String(e).split(".")[1]||"";e>100||e<0?a(new Error("最大 100，最小 0")):s.length>0?a(new Error("只能为整数")):a()})),Object(i["a"])(s,"checkDecimal",(function(t){var e=String(t).split(".")[1]||"";return!isNaN(t)&&!(e.length>2)})),s)},9897:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"projectForm mainPage"},[a("div",{staticClass:"project-item"},[a("div",{staticClass:"form-title"},[t._v("基本信息")]),t._v(" "),a("el-form",{ref:"informationForm",attrs:{model:t.informationForm,rules:t.informationRules,size:"small","label-width":"100px"}},[a("el-form-item",{staticStyle:{width:"420px"},attrs:{label:"项目名称",prop:"name"}},[a("el-input",{attrs:{placeholder:"请输入项目名称",maxlength:"20"},model:{value:t.informationForm.name,callback:function(e){t.$set(t.informationForm,"name",e)},expression:"informationForm.name"}})],1),t._v(" "),a("el-form-item",{staticStyle:{width:"420px"},attrs:{label:"项目地址",prop:"address"}},[a("el-input",{attrs:{placeholder:"请输入项目地址",maxlength:"100"},model:{value:t.informationForm.address,callback:function(e){t.$set(t.informationForm,"address",e)},expression:"informationForm.address"}})],1),t._v(" "),a("el-form-item",{staticStyle:{width:"300px"},attrs:{label:"所属客户",prop:"customer_id"}},[a("el-select",{attrs:{filterable:"",placeholder:"请输入客户名称"},model:{value:t.informationForm.customer_id,callback:function(e){t.$set(t.informationForm,"customer_id",e)},expression:"informationForm.customer_id"}},t._l(t.customsList,(function(t){return a("el-option",{key:t.id,attrs:{label:t.company_name,value:t.id}})})),1)],1)],1)],1),t._v(" "),a("div",{staticClass:"project-item"},[a("div",{staticClass:"form-title"},[t._v("项目指标参考标准")]),t._v(" "),a("el-form",{ref:"standardForm",attrs:{model:t.standardForm,rules:t.standardRules,size:"small","label-width":"100px"}},[a("el-form-item",{staticStyle:{width:"420px"},attrs:{label:"甲醛",prop:"hcho"}},[a("el-input",{attrs:{placeholder:"请填入甲醛参考标准值"},model:{value:t.standardForm.hcho,callback:function(e){t.$set(t.standardForm,"hcho",e)},expression:"standardForm.hcho"}})],1),t._v(" "),a("el-form-item",{staticStyle:{width:"420px"},attrs:{label:"TOVC",prop:"tvoc"}},[a("el-input",{attrs:{placeholder:"请填入TOVC参考标准值"},model:{value:t.standardForm.tvoc,callback:function(e){t.$set(t.standardForm,"tvoc",e)},expression:"standardForm.tvoc"}})],1)],1)],1),t._v(" "),a("div",{staticClass:"project-item"},[a("div",{staticClass:"form-title"},[t._v("项目阶段")]),t._v(" "),a("div",{staticClass:"stage-box"},[a("div",{staticClass:"project-stage"},[a("div",{staticClass:"stage-title"},[t._v("施工阶段")]),t._v(" "),a("ul",{staticClass:"stage-list"},t._l(t.stageInfo.construction,(function(e,s){return a("li",{key:s,staticClass:"stage-item"},[a("span",[t._v("阶段"+t._s(s+1))]),t._v(" "),a("el-input",{staticStyle:{width:"320px"},attrs:{size:"small",placeholder:"请输入阶段名称"},model:{value:e.stage_name,callback:function(a){t.$set(e,"stage_name","string"===typeof a?a.trim():a)},expression:"item.stage_name"}}),t._v(" "),a("el-date-picker",{staticClass:"dayPicker",attrs:{"value-format":"yyyy-MM-dd",size:"small",type:"daterange","start-placeholder":"开始日期","end-placeholder":"结束日期"},on:{change:function(e){return t.handleChangeTime("construction",s)}},model:{value:e.time,callback:function(a){t.$set(e,"time",a)},expression:"item.time"}}),t._v(" "),a("el-select",{attrs:{size:"small",placeholder:"请选择监测标准"},model:{value:e.threshold_id,callback:function(a){t.$set(e,"threshold_id",a)},expression:"item.threshold_id"}},t._l(t.thresholdsList,(function(t){return a("el-option",{directives:[{name:"show",rawName:"v-show",value:1===t.status,expression:"item.status===1"}],key:t.id,attrs:{label:t.name,value:t.id}})})),1),t._v(" "),0!=s?a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(e){return t.handleDelStage("construction",s)}}},[t._v("删除")]):t._e()],1)})),0),t._v(" "),a("div",{staticClass:"stage-btn"},[a("el-button",{attrs:{size:"small"},on:{click:function(e){return t.handleAddStage("construction")}}},[t._v("+添加新阶段")])],1)]),t._v(" "),a("div",{staticClass:"project-stage"},[a("div",{staticClass:"stage-title"},[t._v("交付阶段")]),t._v(" "),a("ul",{staticClass:"stage-list"},t._l(t.stageInfo.deliver,(function(e,s){return a("li",{key:s,staticClass:"stage-item"},[a("span",[t._v("阶段"+t._s(s+1))]),t._v(" "),a("el-input",{staticStyle:{width:"320px"},attrs:{size:"small",placeholder:"请输入阶段名称"},model:{value:e.stage_name,callback:function(a){t.$set(e,"stage_name",a)},expression:"item.stage_name"}}),t._v(" "),a("el-date-picker",{staticClass:"dayPicker",attrs:{"value-format":"yyyy-MM-dd",size:"small",type:"daterange","start-placeholder":"开始日期","end-placeholder":"结束日期"},on:{change:function(e){return t.handleChangeTime("deliver",s)}},model:{value:e.time,callback:function(a){t.$set(e,"time",a)},expression:"item.time"}}),t._v(" "),a("el-select",{attrs:{size:"small",placeholder:"请选择监测标准"},model:{value:e.threshold_id,callback:function(a){t.$set(e,"threshold_id",a)},expression:"item.threshold_id"}},t._l(t.thresholdsList,(function(t){return a("el-option",{directives:[{name:"show",rawName:"v-show",value:1===t.status,expression:"item.status===1"}],key:t.id,attrs:{label:t.name,value:t.id}})})),1),t._v(" "),0!=s?a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(e){return t.handleDelStage("deliver",s)}}},[t._v("删除")]):t._e()],1)})),0),t._v(" "),a("div",{staticClass:"stage-btn"},[a("el-button",{attrs:{size:"small"},on:{click:function(e){return t.handleAddStage("deliver")}}},[t._v("+添加新阶段")])],1)]),t._v(" "),a("div",{staticClass:"project-stage"},[a("div",{staticClass:"stage-title"},[t._v("运维阶段")]),t._v(" "),a("ul",{staticClass:"stage-list"},t._l(t.stageInfo.maintain,(function(e,s){return a("li",{key:s,staticClass:"stage-item"},[a("span",[t._v("阶段"+t._s(s+1))]),t._v(" "),a("el-input",{staticStyle:{width:"320px"},attrs:{size:"small",placeholder:"请输入阶段名称"},model:{value:e.stage_name,callback:function(a){t.$set(e,"stage_name",a)},expression:"item.stage_name"}}),t._v(" "),a("el-date-picker",{staticClass:"dayPicker",attrs:{"value-format":"yyyy-MM-dd",size:"small",type:"daterange","start-placeholder":"开始日期","end-placeholder":"结束日期"},on:{change:function(e){return t.handleChangeTime("maintain",s)}},model:{value:e.time,callback:function(a){t.$set(e,"time",a)},expression:"item.time"}}),t._v(" "),a("el-select",{attrs:{size:"small",placeholder:"请选择监测标准"},model:{value:e.threshold_id,callback:function(a){t.$set(e,"threshold_id",a)},expression:"item.threshold_id"}},t._l(t.thresholdsList,(function(t){return a("el-option",{directives:[{name:"show",rawName:"v-show",value:1===t.status,expression:"item.status===1"}],key:t.id,attrs:{label:t.name,value:t.id}})})),1),t._v(" "),0!=s?a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(e){return t.handleDelStage("maintain",s)}}},[t._v("删除")]):t._e()],1)})),0),t._v(" "),a("div",{staticClass:"stage-btn"},[a("el-button",{attrs:{size:"small"},on:{click:function(e){return t.handleAddStage("maintain")}}},[t._v("+添加新阶段")])],1)])])]),t._v(" "),a("div",{staticClass:"project-item"},[a("div",{staticClass:"form-title"},[t._v("区域信息")]),t._v(" "),a("div",{staticClass:"region-box"},[a("div",{staticClass:"project-region"},[a("ul",{staticClass:"region-list"},t._l(t.regionList,(function(e,s){return a("li",{key:e.id||s,staticClass:"region-item"},[a("div",{staticClass:"region-name"},[a("span",{staticClass:"item-tit"},[t._v("区域"+t._s(s+1))]),t._v(" "),a("el-input",{staticStyle:{width:"320px"},attrs:{placeholder:"请输入区域名称",maxlength:"10",size:"small"},model:{value:e.area_name,callback:function(a){t.$set(e,"area_name","string"===typeof a?a.trim():a)},expression:"regionItem.area_name"}}),t._v(" "),a("el-button",{attrs:{type:"text"},on:{click:function(a){return t.handleDelRegion(s,e)}}},[t._v("删除")])],1),t._v(" "),a("div",{staticClass:"region-img"},[a("span",{staticClass:"item-tit"},[t._v("图纸")]),t._v(" "),a("div",{staticClass:"region-upload-img"},[a("uploadImg",{attrs:{idx:s,removeBtn:e.edit_flg,imgUrl:e.img},on:{afterUpload:t.afterUpload,afterRemove:t.afterRemove}})],1),t._v(" "),t._m(0,!0)])])})),0),t._v(" "),a("div",{staticClass:"region-btn"},[a("el-button",{attrs:{size:"small"},on:{click:t.handleAddRegion}},[t._v("+添加新区域")])],1)])])]),t._v(" "),a("div",{staticClass:"project-btn"},[a("el-button",{attrs:{type:"primary",size:"small"},on:{click:t.handleSave}},[t._v("保存")]),t._v(" "),a("el-button",{attrs:{size:"small"},on:{click:t.handleCancle}},[t._v("取消")])],1)])},i=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"region-hint"},[a("p",[t._v("建议尺寸像素")]),t._v(" "),a("p",[t._v("图片大小最大1MB")]),t._v(" "),a("p",[t._v("图片支持png,jpeg,jpg")])])}],n=(a("5ab2"),a("c904"),a("f548"),a("ad0a")),r=(a("6d57"),a("e10e"),a("200d")),o=(a("cc57"),a("82e5")),l=a("484f"),c=a("b775");function d(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function m(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?d(Object(a),!0).forEach((function(e){Object(r["a"])(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):d(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}var u={name:"projectForm",data:function(){return{thresholdsList:[],customsList:[{id:"00001",name:"客户一号"},{id:"00002",name:"客户二号"},{id:"00003",name:"客户三号"},{id:"00004",name:"客户四号"},{id:"00005",name:"客户五号"}],informationForm:{name:"",address:"",customer_id:""},standardForm:{hcho:"",tvoc:""},informationRules:{name:[{required:!0,message:"请输入项目名称",trigger:"blur"}],customer_id:[{required:!0,message:"请选择所属客户",trigger:"blur"}]},standardRules:{hcho:[{required:!0,message:"请输入参考标准",trigger:"blur"},{validator:l["a"].checkNumber,trigger:"blur"},{validator:l["a"].checkJQ,trigger:"blur"}],tvoc:[{required:!0,message:"请输入参考标准",trigger:"blur"},{validator:l["a"].checkNumber,trigger:"blur"},{validator:l["a"].checkTOVC,trigger:"blur"}]},stageInfo:{construction:[{stage_name:"",time:[],threshold_id:"",default:1,stage:1}],deliver:[{stage_name:"",time:[],threshold_id:"",default:1,stage:2}],maintain:[{stage_name:"",time:[],threshold_id:"",default:1,stage:3}]},regionList:[{id:"",area_name:"",img:"",edit_flg:!0}],delStageIds:[],delAreaIds:[],dialogVisible:!1,bigImgUrl:"",projectId:""}},created:function(){this.getCustomsList(),this.getThresholdsList(),this.$route.params.id&&(this.projectId=this.$route.params.id,this.getProjectInfo())},methods:{getThresholdsList:function(){var t=this;Object(c["a"])(this.ajaxUrl.thresholdsList,{page:1,pageSize:999999},"get").then((function(e){t.thresholdsList=e.data})).catch((function(t){console.log(t)}))},getCustomsList:function(){var t=this;Object(c["a"])(this.ajaxUrl.customsList,{page:1,pageSize:999999},"get").then((function(e){t.customsList=e.data})).catch((function(t){console.log(t)}))},getProjectInfo:function(){var t=this;Object(c["a"])(this.ajaxUrl.projectsInfo+this.projectId,{},"get").then((function(e){t.resizeDataLeading(e)})).catch((function(t){console.log(t)}))},resizeDataLeading:function(t){var e=this;this.informationForm.name=t.name,this.informationForm.address=t.address,this.informationForm.customer_id=t.customs[0]&&t.customs[0].id,this.standardForm.hcho=t.hcho,this.standardForm.tvoc=t.tvoc,t.stages.map((function(t){var a={};switch(a.time=[t.start_date,t.end_date],a.stage_name=t.stage_name,a.threshold_id=t.threshold_id,a.default=t.default,a.stage=t.stage,a.id=t.id,t.stage){case 1:e.stageInfo.construction.push(a);break;case 2:e.stageInfo.deliver.push(a);break;case 3:e.stageInfo.maintain.push(a);break}})),this.stageInfo.construction.shift(),this.stageInfo.deliver.shift(),this.stageInfo.maintain.shift(),t.areas&&(this.regionList=t.areas.map((function(t){return{id:t.id,area_name:t.area_name,img:t.file&&t.file.path,img_id:t.file&&t.file.id,edit_flg:t.edit_flg}})))},resizeDataServer:function(){var t=this,e={};return e=m(m({},this.informationForm),this.standardForm),e.stages=[],Object.keys(this.stageInfo).map((function(a){e.stages=[].concat(Object(n["a"])(e.stages),Object(n["a"])(t.stageInfo[a]))})),e.stages=e.stages.map((function(t){return t.start_date=t.time[0],t.end_date=t.time[1],delete t.time,t})),e.stages=JSON.stringify(e.stages),e.areas=[],e.areas=this.regionList.map((function(t){return{area_name:t.area_name,file:t.img_id,id:t.id}})),e.areas=JSON.stringify(e.areas),e},afterUpload:function(t,e){this.regionList[e].img=t.path,this.regionList[e].img_id=t.id},afterRemove:function(t){this.regionList[t].img=""},handleCancle:function(){this.$router.push({name:"projectList"})},handleSave:function(){var t=this;if(this.checkForm())if(console.log(this.delAreaIds,this.delStageIds,111),this.projectId){var e=this.resizeDataServer();Object(c["a"])(this.ajaxUrl.updateProjects+this.projectId,m(m({},e),{},{stages_del:this.delStageIds.join(","),areas_del:this.delAreaIds.join(",")}),"PUT").then((function(e){t.$message.success("编辑成功"),t.delAreaIds=[],t.delStageIds=[],t.$router.replace({name:"projectList"})})).catch((function(t){console.log(t)}))}else{var a=this.resizeDataServer();Object(c["a"])(this.ajaxUrl.addPorjects,m({},a),"post").then((function(e){t.$message.success("新增项目成功"),t.$router.replace({name:"projectList"})})).catch((function(t){console.log(t)}))}},checkForm:function(){var t=!1,e=!1;if(this.$refs["informationForm"].validate((function(e){if(!e)return t=!1,!1;t=!0})),this.$refs["standardForm"].validate((function(t){if(!t)return e=!1,!1;e=!0})),!t)return this.$message({message:"项目基本信息错误",type:"warning"}),!1;if(!e)return this.$message({message:"项目指标参考标准错误",type:"warning"}),!1;if(-1!==JSON.stringify(this.stageInfo).indexOf('""'))return this.$message({message:"项目阶段信息错误",type:"warning"}),!1;var a=this.regionList.every((function(t){return t.area_name}));return!!a||(this.$message({message:"区域信息错误",type:"warning"}),!1)},handleDelRegion:function(t,e){var a=this;e.edit_flg?this.$confirm("确定要删除该区域？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){e.isAdd||a.delAreaIds.push(e.id),a.regionList.splice(t,1)})).catch((function(){})):this.$message.warning("不能删除存在监测点的区域")},handleAddRegion:function(){this.regionList.push({name:"",img:"",id:"",edit_flg:!0,isAdd:!0})},handleChangeTime:function(t,e){var a=[],s=[];for(var i in this.stageInfo)this.stageInfo[i].map((function(t){0!=t.time.length&&(a.push(t.time[0]),s.push(t.time[1]))}));this.checkTime(a,s)||(this.stageInfo[t][e].time=[])},checkTime:function(t,e){for(var a=Object(n["a"])(t).sort(),s=Object(n["a"])(e).sort(),i=1;i<a.length;i++)if(a[i]<=s[i-1])return this.$message({message:"时间段与其他阶段重复，请选择正确的时间段",type:"warning"}),!1;return JSON.stringify(t)===JSON.stringify(a)&&JSON.stringify(e)===JSON.stringify(s)||(this.$message({message:"后一个阶段的时间必须在前一个阶段之后",type:"warning"}),!1)},handleAddStage:function(t){var e=!1,a=1;switch(t){case"construction":a=1;break;case"deliver":a=2;break;case"maintain":a=3;break}e=this.stageInfo[t].every((function(t){return t.time.length>0})),e?this.stageInfo[t].push({stage_name:"",time:[],threshold_id:"",default:0,stage:a}):this.$message({message:"项目阶段时间未填写完整",type:"warning"})},handleDelStage:function(t,e){var a=this;this.$confirm("确定要删除该阶段？","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(s){a.stageInfo[t][e].id&&a.delStageIds.push(a.stageInfo[t][e].id),a.stageInfo[t].splice(e,1)})).catch((function(){}))}},components:{uploadImg:o["a"]}},h=u,g=(a("c45c"),a("9ca4")),f=Object(g["a"])(h,s,i,!1,null,"5f71120a",null);e["default"]=f.exports},a2cd:function(t,e,a){"use strict";var s=a("238a");t.exports=function(t,e){return!!t&&s((function(){e?t.call(null,(function(){}),1):t.call(null)}))}},c45c:function(t,e,a){"use strict";var s=a("211b"),i=a.n(s);i.a},c904:function(t,e,a){"use strict";var s=a("e46b"),i=a("5daa"),n=a("008a"),r=a("238a"),o=[].sort,l=[1,2,3];s(s.P+s.F*(r((function(){l.sort(void 0)}))||!r((function(){l.sort(null)}))||!a("a2cd")(o)),"Array",{sort:function(t){return void 0===t?o.call(n(this)):o.call(n(this),i(t))}})}}]);
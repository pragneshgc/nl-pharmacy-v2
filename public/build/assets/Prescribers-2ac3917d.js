import{n as s,_ as l}from"./app-620eae05.js";const a={components:{TableComponentSearch:()=>l(()=>import("./TableComponentSearch-55a361fb.js"),["assets/TableComponentSearch-55a361fb.js","assets/app-620eae05.js","assets/app-7eda0943.css","assets/vuejs-datepicker.esm-72f1c627.js"])},data:function(){return{filters:[{title:"Doctor Name",value:"name",type:"text"},{title:"Doctor Surname",value:"surname",type:"text"},{title:"Status",value:"status",type:"select",selected:1,options:[{title:"Select Prescriber Status",value:""},{title:"Active",value:1},{title:"Inactive",value:2}]},{title:"Type",value:"type",type:"select",options:[{title:"Select Prescriber Type",value:""},{title:"GMC",value:1},{title:"EU",value:2},{title:"GPhC",value:3},{title:"Test",value:4}]}]}},mounted(){}};var i=function(){var e=this,t=e._self._c;return t("div",{staticClass:"content"},[t("section",{staticClass:"card"},[e._m(0),t("div",{staticClass:"card-body"},[t("router-link",{staticClass:"btn btnSize01 secondaryBtn mb-10",attrs:{tag:"button",to:"/prescribers/new",exact:""}},[e._v(" Add new prescriber ")]),t("TableComponentSearch",{attrs:{"data-url":"/doctors/index","column-class":"col-lg-12","table-title":"Doctors","redirect-name":"prescriber","redirect-id":"ID","hidden-columns":[],filters:e.filters,deleteUrl:"/doctors",deleteId:"ID"}})],1)])])},n=[function(){var r=this,e=r._self._c;return e("div",{staticClass:"card-header"},[e("h3",[r._v("Prescribers")])])}],c=s(a,i,n,!1,null,null,null,null);const u=c.exports;export{u as default};
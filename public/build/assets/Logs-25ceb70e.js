import{n as a}from"./app-620eae05.js";const o={data:function(){return{selectedFolder:"/",currentFolder:"/",selectedFile:"",files:[],folders:[],logContent:!1,logType:!1,q:""}},computed:{listUrl:function(){return"/logs?folder="+this.selectedFolder},fileUrl:function(){return"/logs/view?file="+this.selectedFile}},mounted(){this.getFolder()},watch:{q:_.debounce(function(){this.openFile(this.selectedFile)},500)},methods:{getFolder:function(){axios.get(this.listUrl).then(s=>{this.folders=s.data.folders,this.files=s.data.files,this.currentFolder=this.selectedFolder}).catch(s=>{console.log(s.response.data)})},changeFolder(s){this.selectedFolder=s!="/"?"/"+s:"/",this.getFolder()},openFile(s){this.selectedFile!=s&&(this.selectedFile="/"+s);let t="";this.q!=""&&(t=`&q=${this.q}`),axios.get(this.fileUrl+t).then(e=>{this.logContent=e.data.data.array,this.logType=e.data.data.type}).catch(e=>{console.log(e.response.data)})}}};var r=function(){var t=this,e=t._self._c;return e("div",[e("div",{staticClass:"card"},[t._m(0),e("div",{staticClass:"card-body"},[e("div",{staticClass:"row"},[e("div",{staticClass:"col-lg-12",staticStyle:{"max-height":"200px",overflow:"auto"}},[t.currentFolder!="/"?e("div",{staticClass:"fileExplorerIcon",on:{click:function(l){return t.changeFolder("/")}}},[e("i",{staticClass:"fa fa-folder-open-o",attrs:{"aria-hidden":"true"}}),e("span",[t._v("...")])]):t._e(),t._l(t.folders,function(l){return e("div",{staticClass:"fileExplorerIcon",on:{click:function(i){return t.changeFolder(l)}}},[e("i",{staticClass:"fa fa-folder-o",attrs:{"aria-hidden":"true"}}),e("span",[t._v(t._s(l))])])}),t._l(t.files,function(l){return e("div",{staticClass:"fileExplorerIcon clickable",on:{click:function(i){return t.openFile(l)}}},[e("i",{staticClass:"fa fa-file-text-o",attrs:{"aria-hidden":"true"}}),e("span",[t._v(t._s(l))])])})],2)])])]),e("div",{staticClass:"card"},[e("div",{staticClass:"card-header"},[e("h4",[t._v(t._s(t.selectedFile))]),e("input",{directives:[{name:"model",rawName:"v-model",value:t.q,expression:"q"}],staticClass:"tBox tBoxSize01 mt-10",attrs:{autocomplete:"off",type:"text",placeholder:"Search Logs"},domProps:{value:t.q},on:{input:function(l){l.target.composing||(t.q=l.target.value)}}})]),e("div",{staticClass:"card-body"},[e("div",{staticClass:"row"},[e("div",{staticClass:"col-lg-12"},[e("ul",{staticClass:"list-group log-list-group"},t._l(t.logContent,function(l,i){return e("li",{key:i,staticClass:"list-group-item p-0"},[e("div",[t._v(" "+t._s(l)+" ")])])}),0)])])])])])},n=[function(){var s=this,t=s._self._c;return t("div",{staticClass:"card-header"},[t("h4",[s._v("Log Explorer")])])}],c=a(o,r,n,!1,null,null,null,null);const u=c.exports;export{u as default};
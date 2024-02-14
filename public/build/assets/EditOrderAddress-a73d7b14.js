import{E as c,e as u,h as m,n as p}from"./app-620eae05.js";const f="/build/assets/iconPaper-74eba9a2.png",h={mixins:[c,u],props:["orderID","orderStatus","products"],components:{DiffTableAddress:m},data:function(){return{countries:[],companies:[],details:{order:{},oldOrder:{},ups:{},oldUps:{},details:{}},columnHome:["Address1","Address2","Address3","Address4","Postcode","CountryCode","APNotificationLanguage","APNotificationValue"],columnPatient:["Name","Surname","Telephone","Email"],disabledFields:["TokenID","Repeats"],loading:!0,dragEventTriggered:!1,backdrop:!0,watch:!1,saveConfirmation:!1,confirmationChanges:{},confirmationChangesUPS:{},confirmationOld:{},confirmationOldUPS:{},iconPaper:f}},watch:{"details.order.DCountryCode":function(){this.watch&&this.getDeliveryCompany()},"details.order.DeliveryID":function(){this.watch&&this.getPostcodeFormatting()},loading(){!this.loading&&!this.dragEventTriggered&&document.getElementById("draggable-div-address")?setTimeout(()=>{this.dragEventTriggered=!0,r(document.getElementById("draggable-div-address"));function r(e){var t=0,o=0,s=0,a=0;document.getElementById("draggable-div-header-address")?document.getElementById("draggable-div-header-address").onmousedown=n:e.onmousedown=n;function n(l){l=l||window.event,l.preventDefault(),s=l.clientX,a=l.clientY,document.onmouseup=d,document.onmousemove=i}function i(l){l=l||window.event,l.preventDefault(),t=s-l.clientX,o=a-l.clientY,s=l.clientX,a=l.clientY,e.style.top=e.offsetTop-o+"px",e.style.left=e.offsetLeft-t+"px"}function d(){document.onmouseup=null,document.onmousemove=null}}},100):(this.dragEventTriggered=!1,this.backdrop=!0)}},computed:{columnDelivery(){let r=["DAddress1","DAddress2","DAddress3","DAddress4","DPostcode","DCountryCode","DeliveryID","UPSAccessPointAddress","TrackingCode","Repeats","TokenID"];return this.details.details.ClientID==51&&this.details.details.JVM!=2&&r.push("JVM"),r},canSetSaturdayDelivery(){let r=new Date,e=r.getDay(),t=r.getHours();return(e==4||e==5)&&(e==4&&t>=17||e==5&&t<=17)}},mounted(){this.getCountries(),this.getCompanies(),this.getOrderDetails(),this.$root.$on("modal.close.all",this.close)},destroyed(){this.$root.$off("modal.close.all")},methods:{getOrderDetails(){this.watch=!1,axios.get("/order-edit/"+this.orderID).then(r=>{this.details=r.data.data,this.loading=!1}).catch(r=>{this.postError(r.response.data.message),this.loading=!1}).finally(()=>{this.watch=!0})},getCountries(){axios.get("/countries").then(r=>{this.countries=r.data.data}).catch(r=>{this.postError(r.response.data.message)})},getCompanies(){axios.get("/delivery-companies").then(r=>{this.companies=r.data.data}).catch(r=>{this.postError(r.response.data.message)})},getDeliveryCompany(){axios.post(`/order-edit/${this.orderID}/delivery-company`,this.details.order).then(r=>{let e=r.data.data;e.DeliveryID&&(this.details.order.DeliveryID=e.DeliveryID),e.CountryCode&&(this.details.order.CountryCode=e.CountryCode),this.getPostcodeFormatting(),this.postSuccess("Delivery company updated")}).catch(r=>{this.postError(r.response.data.message)})},getPostcodeFormatting(){this.details.order.DeliveryID==10&&axios.post(`/order-edit/${this.orderID}/postcode-formatting`,this.details.order).then(r=>{let e=r.data.data;e.Postcode&&(this.details.order.Postcode=e.Postcode),e.DPostcode&&(this.details.order.DPostcode=e.DPostcode)}).catch(r=>{this.postError(r.response.data.message)})},getCountryTitle(r,e=!1){let t="Unknown";return e||(e=this.countries),e.forEach(o=>{o.CountryID==r&&(t=o.Name)}),t},getCompanyTitle(r,e=!1){let t="Unknown";return e||(e=this.companies),e.forEach(o=>{o.SettingID==r&&(t=o.Name)}),t},getCounterColor(r,e){if(e[r]!=null&&this.alias[r].value){if(e[r].length>0&&this.alias[r].combined&&e[this.alias[r].combined]!=null)return e[r].length+e[this.alias[r].combined].length<=this.alias[r].value?"input-count-success":"input-count-danger";if(e[r].length>0&&e[r].length<=this.alias[r].value)return"input-count-success";if(e[r].length>this.alias[r].value)return"input-count-danger"}return""},close(){this.saveConfirmation=!1,this.confirmationChanges={},this.confirmationOld={},this.confirmationOld={},this.confirmationOldUPS={},this.details={order:{},oldOrder:{},ups:{},oldUps:{}},this.$emit("closeorder")},toggleBackdrop(){this.backdrop=!this.backdrop},save(r=!1){if(this.saveConfirmation)this.submit(r);else{let e=JSON.parse(JSON.stringify(this.details.order));delete e.ClientID,axios.post(`/order-edit/check/${this.orderID}`,{order:e,ups:this.details.ups}).then(t=>{Object.keys(t.data.data.changes).length>0||Object.keys(t.data.data.changesUPS).length?(this.confirmationChanges=t.data.data.changes,this.confirmationChangesUPS=t.data.data.changesUPS,this.confirmationOld=t.data.data.old,this.confirmationOldUPS=t.data.data.oldUPS,this.saveConfirmation=!0):(this.saveConfirmation=!1,this.$emit("closeorder"))}).catch(t=>{this.saveConfirmation=!1,this.postError(t)})}},saturdayDeliveryCheck(){this.details.order.SaturdayDelivery==0?this.$swal({title:"DPD Saturday Delivery should not be selected without authorisation",html:"If authorisation has been granted, click OK, otherwise click Cancel",type:"warning",showCancelButton:!0,confirmButtonColor:"#ff5151",cancelButtonColor:"#d33",confirmButtonText:"OK"}).then(r=>{r.value&&(this.details.order.SaturdayDelivery=1)}):this.details.order.SaturdayDelivery=0},submit(r=!1){let e=JSON.parse(JSON.stringify(this.details.order));delete e.ClientID,axios.post("/order-edit/"+this.orderID,{order:e,ups:this.details.ups}).then(t=>{this.postSuccess("Saved"),r&&this.$root.$emit("prescription.validate")}).catch(t=>{this.postError(t)}).finally(()=>{this.close(),this.saveConfirmation=!1,this.$root.$emit("orderupdate")})},back(){this.saveConfirmation=!1,this.confirmationChanges={},this.confirmationChangesUPS={}},isEqual:_.isEqual}};var g=function(){var e=this,t=e._self._c;return t("div",[e.backdrop?t("div",{staticClass:"backdrop",on:{click:function(o){return e.close()}}}):e._e(),t("div",{ref:"parentEl",staticClass:"modal",attrs:{id:"draggable-div-address"}},[e.loading?e._e():t("div",{staticClass:"modal-header draggable-div-header",attrs:{id:"draggable-div-header-address",target:"parentEl"}},[t("transition",{attrs:{name:"fade"}},[t("section",{staticClass:"flexContent"},[t("div",{staticClass:"orderDetails"},[t("img",{attrs:{src:e.iconPaper}}),t("ul",[t("li",[t("span",[e._v("Order #: ")]),e._v(e._s(e.orderID))]),t("li",[t("span",[e._v("Name: ")]),e._v(e._s(e.details.order.Name))]),t("li",[t("span",[e._v("Surname: ")]),e._v(e._s(e.details.order.Surname))]),t("li",[t("span",[e._v("Status: ")]),e._v(e._s(e.orderStatus))]),e.details.order.TrackingCode!=""&&e.details.order.TrackingCode!=null?t("li",[t("span",[e._v("Tracking Code: ")]),e._v(e._s(e.details.order.TrackingCode)+" ")]):e._e(),e.details.order.Repeats!="0"&&e.details.order.Repeats!=""&&[143,162,205,243].includes(e.details.order.DCountryCode)?t("li",[t("span",[e._v("Commercial value: ")]),e._v(e._s(e.details.order.Repeats)+" ")]):e._e()])])])]),t("transition",{attrs:{name:"fade"}},[e.products.length!=0?t("section",{staticClass:"flexContent"},e._l(e.products,function(o){return t("div",{staticClass:"productListItem mb-10"},[t("div",{staticClass:"title"},[t("h3",[e._v(e._s(o.Name)+", "+e._s(o.Dosage)+" x "+e._s(o.Quantity)+" "+e._s(o.Unit))])])])}),0):e._e()])],1),t("transition",{attrs:{name:"fade"}},[e.countries.length!=0&&e.companies.length!=0&&!e.loading&&Object.keys(e.confirmationChanges).length==0&&Object.keys(e.confirmationChangesUPS).length==0?t("form",{staticClass:"pxp-form address-form mb-20",on:{submit:function(o){return o.preventDefault(),e.save.apply(null,arguments)}}},[t("div",{staticClass:"form-column"},[t("h3",[e._v("Patient Details")]),e._l(e.details.order,function(o,s){return e.columnPatient.includes(s)?t("div",{key:s,staticClass:"form-group form-group-2 pb-10"},[t("label",{attrs:{for:o}},[e._v(e._s(e.alias[s].title))]),e.alias[s].value?t("label",{staticClass:"input-count",class:e.getCounterColor(s,e.details.order),attrs:{for:o}},[e._v(" "+e._s(e.details.order[s]?e.details.order[s].length+e.details.order[e.alias[s].combined].length:0)+"/"+e._s(e.alias[s].value)+" ")]):e._e(),t("input",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],attrs:{disabled:e.disabledFields.includes(s),name:o,placeholder:""},domProps:{value:e.details.order[s]},on:{input:function(a){a.target.composing||e.$set(e.details.order,s,a.target.value)}}})]):e._e()}),t("h3",[e._v("Delivery Details")]),e._l(e.details.order,function(o,s){return e.columnDelivery.includes(s)?t("div",{key:s,staticClass:"form-group form-group-2"},[t("label",{attrs:{for:o}},[e._v(e._s(e.alias[s].title))]),e.alias[s].value?t("label",{staticClass:"input-count",class:e.getCounterColor(s,e.details.order),attrs:{for:o}},[e._v(e._s(e.details.order[s]?e.details.order[s].length:0)+"/"+e._s(e.alias[s].value))]):e._e(),["JVM","UPSAccessPointAddress","CountryCode","DCountryCode","DeliveryID","Notes"].includes(s)?["DCountryCode","CountryCode"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.order,s,a.target.multiple?n:n[0])}}},e._l(e.countries,function(a){return t("option",{domProps:{value:a.CountryID}},[e._v(e._s(a.Name))])}),0):["DeliveryID"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.order,s,a.target.multiple?n:n[0])}}},e._l(e.companies,function(a){return t("option",{domProps:{value:a.SettingID}},[e._v(e._s(a.Name))])}),0):["UPSAccessPointAddress"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.order,s,a.target.multiple?n:n[0])}}},[t("option",{attrs:{value:"0"}},[e._v("No")]),t("option",{attrs:{value:"1"}},[e._v("Yes")])]):["JVM"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.order,s,a.target.multiple?n:n[0])}}},[t("option",{attrs:{value:"0"}},[e._v("No")]),t("option",{attrs:{value:"1"}},[e._v("Yes")])]):e._e():t("input",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],attrs:{disabled:e.disabledFields.includes(s),name:o,placeholder:""},domProps:{value:e.details.order[s]},on:{input:function(a){a.target.composing||e.$set(e.details.order,s,a.target.value)}}})]):e._e()}),t("div",{staticClass:"form-group form-group-2"},[t("label",{attrs:{for:"saturday-delivery"}},[e._v("Saturday Delivery")]),t("label",{staticClass:"switch",class:[e.canSetSaturdayDelivery?"":"disabled-slider"],staticStyle:{width:"60px"},attrs:{title:e.canSetSaturdayDelivery?"Switch to Saturday Delivery":"Can not switch Saturday Delivery on when the day is not Thursday after 17:00 or Friday before 17:00"}},[t("input",{class:[e.details.order.SaturdayDelivery==1?"slider-checked":""],attrs:{id:"saturday-delivery",type:"checkbox",disabled:!e.canSetSaturdayDelivery},domProps:{value:e.details.order.SaturdayDelivery==1},on:{click:function(o){return e.saturdayDeliveryCheck()}}}),t("span",{staticClass:"slider"})])]),e.details.order.Notes!=null&&e.details.order.Notes!=""?t("div",{staticClass:"form-group form-group-2"},[t("label",{attrs:{for:"Notes"}},[e._v(e._s(e.alias.Notes.title))]),t("textarea",{directives:[{name:"model",rawName:"v-model",value:e.details.order.Notes,expression:"details.order.Notes"}],staticClass:"form-control tBoxSize02",staticStyle:{"min-width":"300px","min-height":"60px","line-height":"1"},attrs:{placeholder:"Add notes here if you want them to show for dispensers and customer support"},domProps:{value:e.details.order.Notes},on:{input:function(o){o.target.composing||e.$set(e.details.order,"Notes",o.target.value)}}})]):e._e()],2),t("div",{staticClass:"form-column"},[t("h3",[e._v("Home Details")]),e._l(e.details.order,function(o,s){return e.columnHome.includes(s)?t("div",{key:s,staticClass:"form-group form-group-2"},[t("label",{attrs:{for:o}},[e._v(e._s(e.alias[s].title))]),["CountryCode","DCountryCode","DeliveryID","Notes"].includes(s)?["DCountryCode","CountryCode"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.order,s,a.target.multiple?n:n[0])}}},e._l(e.countries,function(a){return t("option",{domProps:{value:a.CountryID}},[e._v(e._s(a.Name))])}),0):["DeliveryID"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.order,s,a.target.multiple?n:n[0])}}},e._l(e.companies,function(a){return t("option",{domProps:{value:a.SettingID}},[e._v(e._s(a.Name))])}),0):e._e():t("input",{directives:[{name:"model",rawName:"v-model",value:e.details.order[s],expression:"details.order[value]"}],attrs:{disabled:e.disabledFields.includes(s),name:o,placeholder:""},domProps:{value:e.details.order[s]},on:{input:function(a){a.target.composing||e.$set(e.details.order,s,a.target.value)}}})]):e._e()})],2),t("div",{staticClass:"form-column"},[e.details.ups!=null?t("h3",[e._v("UPS Access Point")]):e._e(),e._l(e.details.ups,function(o,s){return t("div",{key:s,staticClass:"form-group"},[t("label",{attrs:{for:o}},[e._v(e._s(e.alias[s].title))]),["CountryCode","DCountryCode","APNotificationLanguage"].includes(s)?e._e():t("input",{directives:[{name:"model",rawName:"v-model",value:e.details.ups[s],expression:"details.ups[value]"}],attrs:{disabled:e.disabledFields.includes(s),name:o,placeholder:""},domProps:{value:e.details.ups[s]},on:{input:function(a){a.target.composing||e.$set(e.details.ups,s,a.target.value)}}}),e.alias[s].value?t("label",{staticClass:"input-count",class:e.getCounterColor(s,e.details.ups),attrs:{for:o}},[e._v(" "+e._s(e.details.ups[s]?e.details.ups[s].length:0)+"/"+e._s(e.alias[s].value)+" ")]):["DCountryCode","CountryCode"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.ups[s],expression:"details.ups[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.ups,s,a.target.multiple?n:n[0])}}},e._l(e.countries,function(a){return t("option",{domProps:{value:a.CountryID}},[e._v(e._s(a.Name))])}),0):["APNotificationLanguage"].includes(s)?t("select",{directives:[{name:"model",rawName:"v-model",value:e.details.ups[s],expression:"details.ups[value]"}],class:[e.details.order[s]&&e.details.order[s]!=""?"select-dropdown-active":""],on:{change:function(a){var n=Array.prototype.filter.call(a.target.options,function(i){return i.selected}).map(function(i){var d="_value"in i?i._value:i.value;return d});e.$set(e.details.ups,s,a.target.multiple?n:n[0])}}},e._l(e.countries,function(a){return t("option",{domProps:{value:a.CountryID}},[e._v(e._s(a.Name))])}),0):e._e()])})],2)]):e._e()]),t("transition",{attrs:{name:"fade"}},[(Object.keys(e.confirmationChanges).length>0||Object.keys(e.confirmationChangesUPS).length>0)&&!e.loading?t("div",{staticClass:"pxp-form mb-20"},[t("div",{staticClass:"infoBox warning"},[t("p",[e._v("Please review and confirm all the changes in the order before saving!")])]),t("DiffTableAddress",{attrs:{"old-object":e.confirmationOld,"new-object":e.confirmationChanges,"old-object-u-p-s":e.confirmationOldUPS,"new-object-u-p-s":e.confirmationChangesUPS,"countries-prop":e.countries,"companies-prop":e.companies}})],1):e._e()]),t("transition",{attrs:{name:"fade"}},[e.loading?e._e():t("div",{staticClass:"modal-footer"},[!e.isEqual(e.details.order,e.details.oldOrder)||!e.isEqual(e.details.ups,e.details.oldUPS)?t("button",{staticClass:"btn btnSize01 tertiaryBtn bigButton",on:{click:function(o){return e.save()}}},[e.saveConfirmation?t("span",[e._v(" Save ")]):t("span",[e._v(" Review ")])]):e._e(),(!e.isEqual(e.details.order,e.details.oldOrder)||!e.isEqual(e.details.ups,e.details.oldUPS))&&e.saveConfirmation&&e.orderStatus=="SAFETYCHECK"?t("button",{staticClass:"btn btnSize01 tertiaryBtn bigButton",on:{click:function(o){return e.save(!0)}}},[e._v(" Save & Validate ")]):e._e(),(!e.isEqual(e.details.order,e.details.oldOrder)||!e.isEqual(e.details.ups,e.details.oldUPS))&&e.saveConfirmation?t("button",{staticClass:"btn btnSize01 tertiaryBtn bigButton",on:{click:function(o){return e.back()}}},[t("span",[e._v(" Back ")])]):e._e(),t("button",{staticClass:"btn btnSize01 secondaryBtn bigButton",on:{click:function(o){return e.close()}}},[e._v("Cancel")])])]),e.loading?t("div",{staticClass:"loader"},[e._v("Loading...")]):e._e(),t("span",{staticClass:"backdrop-toggle",attrs:{title:"Unfocus the modal"},on:{click:function(o){return e.toggleBackdrop()}}},[t("i",{staticClass:"fa fa-clone"})]),t("span",{staticClass:"close",attrs:{title:"Close the modal"},on:{click:function(o){return e.close()}}},[t("i",{staticClass:"fa fa-close"})])],1)])},v=[],C=p(h,g,v,!1,null,null,null,null);const D=C.exports;export{D as default};
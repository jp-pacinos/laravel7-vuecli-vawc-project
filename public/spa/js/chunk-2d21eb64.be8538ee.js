(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d21eb64"],{d772:function(e,a,t){"use strict";t.r(a);var r=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("v-app",{attrs:{id:"inspire"}},[t("v-content",[t("v-container",{staticClass:"fill-height",attrs:{fluid:""}},[t("v-row",{attrs:{align:"center",justify:"center"}},[t("v-col",{attrs:{cols:"12",sm:"8",md:"4"}},[t("v-card",{staticClass:"elevation-12"},[t("v-toolbar",{attrs:{color:"primary",dark:"",flat:""}},[t("v-toolbar-title",[e._v(" Authentication ")]),t("v-progress-linear",{attrs:{active:e.loading,indeterminate:e.loading,absolute:"",bottom:"",color:"yellow accent-4"}})],1),t("v-card-text",[t("v-form",{ref:"form",on:{submit:function(a){return a.preventDefault(),e.send(a)}},model:{value:e.form,callback:function(a){e.form=a},expression:"form"}},[t("v-text-field",{attrs:{label:"Email",name:"Email","prepend-icon":"mdi-account",type:"email",rules:[e.$store.state.rules.required,e.$store.state.rules.email],required:""},model:{value:e.username,callback:function(a){e.username=a},expression:"username"}}),t("v-text-field",{attrs:{id:"password",label:"Password",name:"password","prepend-icon":"mdi-lock",type:"password",rules:[e.$store.state.rules.required],required:""},model:{value:e.password,callback:function(a){e.password=a},expression:"password"}}),t("div",{staticClass:"d-flex flex-row-reverse"},[t("v-btn",{attrs:{type:"submit",color:"primary",disabled:!e.form}},[e._v("Login")])],1)],1)],1)],1)],1)],1)],1)],1),t("Snackbar")],1)},n=[],o=(t("d3b7"),t("5530")),s=t("2f62"),i=t("dde5"),l=t("b012"),c=t("22b2"),d={components:{Snackbar:c["a"]},data:function(){return{username:null,password:null,loading:!1,form:!0}},methods:Object(o["a"])({},Object(s["b"])("app",["snackbar"]),{send:function(){var e=this;this.loading=!0,i["a"].get("/sanctum/csrf-cookie",{baseURL:""}).then((function(){return e.login()})).catch((function(a){return e.snackbar({text:"There was an error: "+a.message,y:"bottom"})})).finally((function(){return e.loading=!1}))},login:function(){var e=this;l["a"].login({email:this.username,password:this.password}).then((function(a){e.$store.dispatch("auth/saveToken",a.data.token),e.$router.push({name:"admin.home"})})).catch((function(a){var t="";t=a.response?a.response.data.error:a.message?a.message:"Something went wrong",e.snackbar({color:"red",text:t,y:"bottom"})}))}})},u=d,m=t("2877"),p=t("6544"),f=t.n(p),b=t("7496"),v=t("8336"),w=t("b0af"),h=t("99d9"),g=t("62ad"),k=t("a523"),x=t("a75b"),V=t("4bd4"),y=t("8e36"),C=t("0fd9"),T=t("8654"),$=t("71d9"),j=t("2a7f"),q=Object(m["a"])(u,r,n,!1,null,null,null);a["default"]=q.exports;f()(q,{VApp:b["a"],VBtn:v["a"],VCard:w["a"],VCardText:h["c"],VCol:g["a"],VContainer:k["a"],VContent:x["a"],VForm:V["a"],VProgressLinear:y["a"],VRow:C["a"],VTextField:T["a"],VToolbar:$["a"],VToolbarTitle:j["a"]})}}]);
//# sourceMappingURL=chunk-2d21eb64.be8538ee.js.map
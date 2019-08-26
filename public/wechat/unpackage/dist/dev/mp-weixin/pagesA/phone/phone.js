(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["pagesA/phone/phone"],{

/***/ "../../../../project/zb-api/public/wechat/main.js?{\"page\":\"pagesA%2Fphone%2Fphone\"}":
/*!*********************************************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/main.js?{"page":"pagesA%2Fphone%2Fphone"} ***!
  \*********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
__webpack_require__(/*! uni-pages */ "../../../../project/zb-api/public/wechat/pages.json");
var _mpvuePageFactory = _interopRequireDefault(__webpack_require__(/*! mpvue-page-factory */ "./node_modules/@dcloudio/vue-cli-plugin-uni/packages/mpvue-page-factory/index.js"));
var _phone = _interopRequireDefault(__webpack_require__(/*! ./pagesA/phone/phone.vue */ "../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue"));function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}
Page((0, _mpvuePageFactory.default)(_phone.default));

/***/ }),

/***/ "../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue":
/*!**************************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/pagesA/phone/phone.vue ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _phone_vue_vue_type_template_id_034fc6a4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./phone.vue?vue&type=template&id=034fc6a4& */ "../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=template&id=034fc6a4&");
/* harmony import */ var _phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./phone.vue?vue&type=script&lang=js& */ "../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _phone_vue_vue_type_template_id_034fc6a4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _phone_vue_vue_type_template_id_034fc6a4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "project/zb-api/public/wechat/pagesA/phone/phone.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/babel-loader/lib!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib??vue-loader-options!./phone.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=script&lang=js&");
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=template&id=034fc6a4&":
/*!*********************************************************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=template&id=034fc6a4& ***!
  \*********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_template_id_034fc6a4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!../../../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib??vue-loader-options!./phone.vue?vue&type=template&id=034fc6a4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib/index.js?!../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=template&id=034fc6a4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_template_id_034fc6a4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_phone_vue_vue_type_template_id_034fc6a4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!C:/Users/lifei/project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0;









//微信提供的解密函数，请修改为你自己的路径  
var WXBizDataCrypt = __webpack_require__(/*! ../../css/WXBizDataCrypt.js */ "../../../../project/zb-api/public/wechat/css/WXBizDataCrypt.js");var _default =

{
  data: function data() {
    return {
      login_code: '' };

  },
  onLoad: function onLoad() {

    var that = this;
    uni.login({
      success: function success(res) {

        // 获取code    
        console.log(JSON.stringify(res));
        //{"errMsg":"login:ok","code":"071JIp1t1pv马赛克t1Ran1t1JIp1l"}  

        that.login_code = res.code;

      } });


  },

  methods: {

    getPhoneNumber: function getPhoneNumber(e) {
      console.log(e);
      if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {
        console.log('用户拒绝提供手机号');
      } else {
        console.log('用户同意提供手机号');

        console.log(JSON.stringify(e.detail.encryptedData));
        console.log(JSON.stringify(e.detail.iv));

        var encryptedData = e.detail.encryptedData;
        var iv = e.detail.iv;

        ////////////////////////////////////////////////////////////////////////////////  
        //定义在根目录下的main.js里  
        //Vue.prototype.APPID                           = 'wxb1a马赛克2bfc90a';  
        //Vue.prototype.SECRET                          = 'b3ae36758马赛克dbe146d9acd81d';  
        //Vue.prototype.WX_AUTH_URL                     = 'https://api.weixin.qq.com/sns/jscode2session';  

        var JSCODE = this.login_code;
        var APPID = this.APPID;
        var SECRET = this.SECRET;
        var wx_author_url = 'https://api.weixin.qq.com/sns/jscode2session' + '?appid=wx02c9a76ab01f424c' + '&secret=22f25c64080e8a640d93d104cbc2a3ea' + '&js_code=' + JSCODE + '&grant_type=authorization_code';

        console.log(wx_author_url);
        return;
        uni.request({
          url: wx_author_url,
          success: function success(re) {
            console.log('session_key:' + re.data.session_key);

            var appId = 'wx02c9a76ab01f424c';
            var sessionKey = re.data.session_key;

            var pc = new WXBizDataCrypt(appId, sessionKey);
            var data = pc.decryptData(encryptedData, iv);

            console.log('------------------->');
            console.log('解密后 data: ', data);
            // console.log('解密后 data: ', JSON.stringify(data));  
            /*  
                {  
                    "phoneNumber": "139马赛克9490",  
                    "purePhoneNumber": "139马赛克9490",  
                    "countryCode": "86",  
                    "watermark": {  
                        "timestamp": 1560577589,  
                        "appid": "wxb1a马赛克12bfc90a"  
                    }  
                }  
            */
            console.log('------------------->');

          } });

        ////////////////////////////////////////////////////////////////////////////////  

      }

    } } };exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ "./node_modules/@dcloudio/uni-mp-weixin/dist/index.js")["default"]))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib/index.js?!../../../../project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=template&id=034fc6a4&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib??vue-loader-options!C:/Users/lifei/project/zb-api/public/wechat/pagesA/phone/phone.vue?vue&type=template&id=034fc6a4& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "view",
    [
      _c(
        "button",
        {
          attrs: { "open-type": "getPhoneNumber", eventid: "724ed0b9-0" },
          on: { getphonenumber: _vm.getPhoneNumber }
        },
        [_vm._v("获取电话号码")]
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

},[["../../../../project/zb-api/public/wechat/main.js?{\"page\":\"pagesA%2Fphone%2Fphone\"}","common/runtime","common/vendor"]]]);
//# sourceMappingURL=../../../.sourcemap/mp-weixin/pagesA/phone/phone.js.map
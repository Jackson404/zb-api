(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["common/main"],[
/* 0 */
/*!***********************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/main.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
__webpack_require__(/*! uni-pages */ 1);
var _vue = _interopRequireDefault(__webpack_require__(/*! vue */ 2));
var _App = _interopRequireDefault(__webpack_require__(/*! ./App */ 4));















var _config = _interopRequireDefault(__webpack_require__(/*! ./common/config.js */ 10));



var _request = _interopRequireDefault(__webpack_require__(/*! ./common/request.js */ 11));function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}function _objectSpread(target) {for (var i = 1; i < arguments.length; i++) {var source = arguments[i] != null ? arguments[i] : {};var ownKeys = Object.keys(source);if (typeof Object.getOwnPropertySymbols === 'function') {ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {return Object.getOwnPropertyDescriptor(source, sym).enumerable;}));}ownKeys.forEach(function (key) {_defineProperty(target, key, source[key]);});}return target;}function _defineProperty(obj, key, value) {if (key in obj) {Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true });} else {obj[key] = value;}return obj;}_vue.default.config.productionTip = false;_vue.default.prototype.websiteUrl = 'http://koukou.soft-shop.cn/api/'; // Vue.prototype.apiServer = 'https://521.zhengbu121.com/api/'
_vue.default.prototype.apiServer = 'https://mini.zhengbu121.com'; // Vue.prototype.apiServer = 'http://47.103.102.222'
_vue.default.prototype.apiServer1 = 'https://lion.zhengbu121.com/';_vue.default.prototype.websiteUrlx = 'http://xxx.soft-shop.cn/';_vue.default.prototype.websiteUrll = 'http://haidao.soft-shop.cn/'; // 挂载全局方法
_vue.default.prototype.config = _config.default;_vue.default.prototype.$http = _request.default;
_App.default.mpType = 'app';

var app = new _vue.default(_objectSpread({},
_App.default));

app.$mount();

/***/ }),
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */
/*!***********************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/App.vue ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./App.vue?vue&type=script&lang=js& */ 5);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _App_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./App.vue?vue&type=style&index=0&lang=css& */ 7);
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib/runtime/componentNormalizer.js */ 9);
var render, staticRenderFns





/* normalize component */

var component = Object(_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "project/zb-api/public/wechat/App.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),
/* 5 */
/*!************************************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/App.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/babel-loader/lib!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib??vue-loader-options!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./App.vue?vue&type=script&lang=js& */ 6);
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 6 */
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!C:/Users/lifei/project/zb-api/public/wechat/App.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0;var _default =
{
  onLaunch: function onLaunch() {
    console.log('App Launch');
  },
  onShow: function onShow() {
    console.log('App Show');
  },
  onHide: function onHide() {
    console.log('App Hide');
  } };exports.default = _default;

/***/ }),
/* 7 */
/*!********************************************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/App.vue?vue&type=style&index=0&lang=css& ***!
  \********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/mini-css-extract-plugin/dist/loader.js??ref--6-oneOf-1-0!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--6-oneOf-1-1!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/css-loader??ref--6-oneOf-1-2!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/postcss-loader/src??ref--6-oneOf-1-3!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/vue-loader/lib??vue-loader-options!../../../../Downloads/HBuilderX.0.1.47.20180823-alpha.full/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./App.vue?vue&type=style&index=0&lang=css& */ 8);
/* harmony import */ var _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_Downloads_HBuilderX_0_1_47_20180823_alpha_full_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_App_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 8 */
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--6-oneOf-1-0!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--6-oneOf-1-1!./node_modules/css-loader??ref--6-oneOf-1-2!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-oneOf-1-3!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!C:/Users/lifei/project/zb-api/public/wechat/App.vue?vue&type=style&index=0&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 9 */,
/* 10 */
/*!********************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/common/config.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0; // 配置信息
var _default = {
  // api请求前缀
  webUrl: 'https://lion.zhengbu121.com/' };exports.default = _default;

/***/ }),
/* 11 */
/*!*********************************************************************!*\
  !*** C:/Users/lifei/project/zb-api/public/wechat/common/request.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0;
var _config = _interopRequireDefault(__webpack_require__(/*! ./config.js */ 10));function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}function _defineProperty(obj, key, value) {if (key in obj) {Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true });} else {obj[key] = value;}return obj;}var _default =
{
  config: {
    baseUrl: _config.default.webUrl,
    header: _defineProperty({
      'Content-Type': 'application/json;charset=UTF-8' }, "Content-Type",
    'application/x-www-form-urlencoded'),

    data: {},
    method: "GET",
    dataType: "json" },

  request: function request() {var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    options.header = options.header || this.config.header;
    options.method = options.method || this.config.method;
    options.dataType = options.dataType || this.config.dataType;
    options.url = this.config.baseUrl + options.url;
    // TODO：token增加等操作
    if (options.token) {
      // 验证用户是否登录
      if (!this.checkToken(options.checkToken)) return;

    }
    return uni.request(options);
  },
  get: function get(url, data) {var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options.url = url;
    options.data = data;
    options.method = 'GET';
    return this.request(options);
  },
  post: function post(url, data) {var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    options.url = url;
    options.data = data;
    options.method = 'POST';
    return this.request(options);
  },
  // 上传图片
  upload: function upload(url) {var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    options.url = this.config.baseUrl + url;
    options.header = options.header || this.config.header;
    options.fileType = options.fileType || "image";
    options.formData = options.formData || {};
    options.filePath = options.filePath;
    options.name = options.name;
    // TODO：token增加等操作
    if (options.token) {
      // 验证是否登录
      if (!this.checkToken(options.checkToken)) return;

    }

    return uni.uploadFile(options);
  },
  // 错误处理
  errorCheck: function errorCheck(err, res) {var errfun = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;var resfun = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
    if (err) {
      typeof errfun === 'function' && errfun();
      uni.showToast({ title: '加载失败', icon: "none" });
      return false;
    }
    if (res.data.errorCode) {
      typeof errfun === 'function' && resfun();
      uni.showToast({ title: res.data.msg, icon: "none" });
      return false;
    }
    return true;
  },
  // 验证用户是否登录
  checkToken: function checkToken(_checkToken) {
    if (_checkToken && !User.token) {
      uni.showToast({ title: '请先登录', icon: "none" });
      uni.navigateTo({
        url: '/pages/login/login' });

      return false;
    }
    return true;
  },
  // 验证用户权限
  checkAuth: function checkAuth(_checkAuth) {
    if (_checkAuth && !User.userinfo.phone) {
      uni.showToast({ title: '请先绑定手机号码', icon: "none" });
      uni.navigateTo({
        url: '/pages/user-bind-phone/user-bind-phone' });

      return false;
    }
    return true;
  } };exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ 12)["default"]))

/***/ })
],[[0,"common/runtime","common/vendor"]]]);
//# sourceMappingURL=../../.sourcemap/mp-weixin/common/main.js.map
import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false
Vue.prototype.websiteUrl = 'http://koukou.soft-shop.cn/api/'
// Vue.prototype.apiServer = 'https://521.zhengbu121.com/api/'
Vue.prototype.apiServer = 'https://mini.zhengbu121.com'
// Vue.prototype.apiServer = 'http://47.103.102.222'



Vue.prototype.apiServer1 = 'https://lion.zhengbu121.com/'

Vue.prototype.websiteUrlx = 'http://xxx.soft-shop.cn/'
Vue.prototype.websiteUrll = 'http://haidao.soft-shop.cn/'

// 挂载全局方法
import config from "./common/config.js"
Vue.prototype.config=config


import request from "./common/request.js";
Vue.prototype.$http = request;


App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()

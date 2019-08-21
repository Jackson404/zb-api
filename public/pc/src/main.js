import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import App from './App.vue';
import {
	Loading
} from 'element-ui';
Vue.use(ElementUI);
//引入路由
import router from './router'


//引入css
import '../static/global/index.css'

//因为接口
import * as api from './restful/api'

Vue.prototype.$http = api

//导入store 实例
import store from './store'

// 使用vue-cookies
// es2015 module
import VueCookies from 'vue-cookies'
Vue.use(VueCookies)

//全局导航守卫
router.beforeEach((to, from, next) => {
	if (VueCookies.isKey('access_token')) {

		let userInfo = {
			'access_token': -1
		}
		//重新分发消息
		store.dispatch('getUser', userInfo);
	}

	next();
})




Vue.config.productionTip = false

var vue = new Vue({
	router: router,
	store: store,
	render: h => h(App),
}).$mount('#app')

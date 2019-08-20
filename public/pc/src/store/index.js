import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
// 1.导入对象 2.vue.use 3.创建store对象 4.挂载

const store =new Vuex.Store({
	state:{
		// 存储用户信息
		userinfo:{}
	},
	mutations:{
		get_user(state,data){
			state.userinfo=data;
			console.log(data);
		}
	},
	actions:{
		getUser(context,data){
			context.commit('get_user',data)
		}
	}
})

export default store;
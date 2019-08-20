<template>
  <div id="app" >
			<!-- <Header /> -->
		<router-view class="cc" style="margin-top: 90px;"></router-view>
  </div>
	
</template>

<script>
import Header from './components/common/Header.vue'
import Index from './components/Index/Index.vue'
import Order from './components/Order/Order.vue'

import Staff from './components/Staff/Staff.vue'
import Resume from './components/Resume/Resume.vue'
import Service from './components/Service/Service.vue'
import News from './components/News/News.vue'
import Newsinfo from './components/Newsinfo/Newsinfo.vue'
import Workinfo from './components/Workinfo/Workinfo.vue'
import Attestation from './components/Attestation/Attestation'
import Orderinfo from './components/Orderinfo/Orderinfo'

export default {
  name: 'app',
  components: {
		Header,
		Index,
		Order,
		Staff,
		Resume,
		Service,
		News,
		Newsinfo,
		Workinfo,
		Attestation,
		Orderinfo
  },
	data(){
		return{
			
		}
	},
	created(){
		
		if (this.$cookies.isKey('access_token')) {
			//已登录
			console.log('已登陆');
		} else {
			//未登录
			this.$router.push({ name: 'Login', params: { userId: '123' } });
			console.log('未登陆');
		}
		
	}
	
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
	width: 100%;
}
</style>

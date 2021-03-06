import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)
import Index from '@/components/Index/Index'
import Order from '@/components/Order/Order'
import Staff from '@/components/Staff/Staff'
import Search from '@/components/Search/Search'
import Resume from '@/components/Resume/Resume'
import Service from '@/components/Service/Service'
import News from '@/components/News/News'
import Newsinfo from '@/components/Newsinfo/Newsinfo'
import Workinfo from '@/components/Workinfo/Workinfo'
import Attestation from '@/components/Attestation/Attestation'
import Orderinfo from '@/components/Orderinfo/Orderinfo'
import Login from '@/components/Login/Login'
import Restiger from '@/components/Restiger/Restiger'
import Searcresult from '@/components/Searcresult/Searcresult'
import Setmeal from '@/components/Setmeal/Setmeal'

export default new Router({
	linkActiveClass:'check',
	// mode:'history',
	routes:[
		{
			path:'/',
			redirect:{name:"Index"}
		},
		{
			path:'/index',
			name:'Index',
			component:Index
		},
		{
			path:'/order',
			name:'Order',
			component:Order
		},
		{
			path:'/search',
			name:'Search',
			component:Search
		},
		{
			path:'/staff',
			name:'Staff',
			component:Staff
		},
		{
			path:'/resume',
			name:'Resume',
			component:Resume
		},
		{
			path:'/service',
			name:'Service',
			component:Service
		},
		{
			path:'/news',
			name:'News',
			component:News
		},
		{
			path:'/news_info/:id',
			name:'Newsinfo',
			component:Newsinfo
		},
		{
			path:'/work_info/:id',
			name:'Workinfo',
			component:Workinfo
		},
		{
			path:'/attestation',
			name:'Attestation',
			component:Attestation
		},
		{
			path:'/orderinfo',
			name:'Orderinfo',
			component:Orderinfo
		},
		{
			path:'/login',
			name:'Login',
			component:Login
		},
		{
			path:'/restiger',
			name:'Restiger',
			component:Restiger
		},
		{
			path:'/search_result/:id',
			name:'Searcresult',
			component:Searcresult
		},
		{
			path:'/setmeal',
			name:'Setmeal',
			component:Setmeal
		},
		
		
		
	],
	 
	
	
})
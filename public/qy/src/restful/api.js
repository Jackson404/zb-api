import Axios from 'axios'

import VueCookies from 'vue-cookies'
//设置公共得https
Axios.defaults.baseURL = 'http://47.103.102.222'
// Axios.defaults.baseURL='https://yanyuan.soft-shop.cn/api/'
// Axios.defaults.baseURL='https://www.luffycity.com/api/v1/'


// 拦截器 请求之前
Axios.interceptors.request.use(function(config) {
	// 在发送请求之前做些什么

	//当用户登录之前
	if (VueCookies.isKey('access_token')) {
		//用户身份信息的字段
		Axios.defaults.headers.common['Authorization'] = VueCookies.get('access_token');
	}


	return config;
}, function(error) {
	// 对请求错误做些什么
	return Promise.reject(error);
});


//发送验证码
export function sendSmss(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpUser/sendSms';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//登录注册接口
export function login(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpUser/login';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//个人认证

export function fication(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpUser/pCertification';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}


//个人认证

export function Company(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpUser/epCertification';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//获取首页信息

export function filter(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpOrder/filter';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//获取职位详情

export function getDeta(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpOrder/getDetailWithLogin';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//用户接单接口

export function receive(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpUser/receiveOrder';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//用户分享接口
export function share(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpOrder/shareOrder';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}


//获取已完成订单

export function getOrder(data) {
	//data 为参数数组

	var url = '/api/v1.ep.EpOrder/getOrderList';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}




//获取简历接口
export function resume(data) {
	//data 为参数数组

	var url = '/api/v1.ep.ResumeData/filterResumeData';

	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}


//带参数拼接	
export function categoryList() {
	var url = '/degree_course/';
	return Axios.get(url).then((res) => {
		return res.data;
	})

}



//获取企业的员工列表	
export function review(data) {
	var url = '/api/v1.ep.EpUser/getEmListByGroupId';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//获取企业用户下创建的组列表

export function getEmGroup(data) {
	var url = '/api/v1.ep.EpUser/getEmGroupListByEpUserId';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//企业用户添加员工分组
export function addEmGroup(data) {
	var url = '/api/v1.ep.EpUser/addEmGroup';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//企业用户删除组别
export function delEmGroup(data) {
	var url = '/api/v1.ep.EpUser/delEmGroup';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//企业用户编辑组别信息

export function editEmGroup(data) {
	var url = '/api/v1.ep.EpUser/editEmGroup';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//企业用户审核员工
export function review1(data) {
	var url = '/api/v1.ep.EpUser/review';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//删除员工
export function delEmUser(data) {
	var url = '/api/v1.ep.EpUser/delEmUser';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}
//企业用户修改员工的分组
export function changeEmGroupByEpUser(data) {
	var url = '/api/v1.ep.EpUser/changeEmGroupByEpUser';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//获取员工的详情

export function getEmUserDetail(data) {
	var url = '/api/v1.ep.EpUser/getEmUserDetail';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}


//获取订单详情

export function getOrderDetail(data) {
	var url = '/api/v1.ep.EpOrder/getOrderDetail';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

// 获取消息列表
export function getList(data) {
	var url = '/api/v1.ep.EpMsg/getList';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//获取消息详情
export function getListOne(data) {
	var url = '/api/v1.ep.EpMsg/getDetail';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}


//获取企业用户的简历列表
export function getEpResumeList(data) {
	var url = '/api/v1.ep.ResumeData/getEpResumeList';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}


//创建简历分类
export function addResumeCate(data) {
	var url = '/api/v1.ep.ResumeData/addResumeCate';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//获取简历分类列表
export function getResumeCateList(data) {
	var url = '/api/v1.ep.ResumeData/getResumeCateList';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//编辑简历分类
export function editResumeCate(data) {
	var url = '/api/v1.ep.ResumeData/editResumeCate';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//删除简历分类
export function delResumeCate(data) {
	var url = '/api/v1.ep.ResumeData/delResumeCate';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//简历修改分类
export function moveResumeToCate(data) {
	var url = '/api/v1.ep.ResumeData/moveResumeToCate';
	return Axios.post(url, data).then((res) => {
		return res.data;
	})

}

//post 携带canshu

// 
// export function login(data){
// 	//data 为参数数组
// 	var url='/login/';
// 	return Axios.post(url,data).then((res)=>{
// 		return res.data;
// 	})	
// 	
// 	
// }

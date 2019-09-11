<template>
	<view class="body" style="min-height: 100vh;">
		
		<view v-if="stax">
			
			<view class="title">
				<view class="name" style="display: flex;flex-wrap: nowrap;align-items: center;">
					<text class="text" style="font-size: 36upx;max-width: 70%;display: block;margin-right: 20upx;color: #333333;">{{item.name}}</text> <image v-if="item.t_jr==2"  src="https://www.zhengbu121.com/statics/img/1.png" mode="widthFix" style="width: 30upx;height: 30upx;display: inline-block;" lazy-load="true"></image>
					
				</view>
				<view style="display: flex;height: 30px;line-height: 30px;">
				<!-- 	<view class="tag" v-if="item.t_jr==2">退役军人优先</view>
					<view class="money" v-if="item.t_jr==2" style="margin-left: 20px;color: #0084FF;font-size: 28upx;">{{item.t_xz}}</view> -->
					<view class="money" style="color: #0084FF;font-size: 30upx;">{{item.salary}}元</view>
				</view>
			</view>
			
			
			<view class="title1" style="padding: 10upx 0 20upx 0;color: #333333;font-size: 28upx;">
				{{item.company_name}}		
			</view>
			
			<view class="title1 u-f-ac" style="padding: 0 0 24upx 0;color: #363636;">
				<view class="u-list u-f-ac">
					<image src="../../static/xiao/experience.png" mode="" style="height: 28upx;width: 26upx;display: block;"></image>
					<!-- <view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;" v-if="item.workExp==0"> 不限</view> -->
					<view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;" > {{item.work_experience}}</view>
				</view>
				<view class="u-list u-f-ac">
					<image src="../../static/xiao/education.png" mode="" style="height: 28upx;width: 32upx;display: block;"></image>
					<view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;">  {{item.education}}</view>
				</view>
				<view class="u-list u-f-ac">
					<image src="../../static/xiao/experience.png" mode="" style="height: 28upx;width: 26upx;display: block;"></image>
					<view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;"> {{item.education}}</view>
				</view>
				
			</view>
			
			<view class="tagx u-f-ac" >
				<view class="tagone" v-for="(items, index) in item.welfare_benefits" :key="index">{{items}}</view>
				
			</view>
			<view style="height: 20px;"></view>
			<view class="address" style="display: flex;padding: 20px 0;">
				<!-- <text style="background: url(../../static/xiao/location.png);background-size: 100% 100%;width: 14px;height: 18px;display: inline-block;position: relative;margin-right: 10px;"></text> -->
				<image src="../../static/xiao/location.png" mode="" style="width: 14px;height: 18px;display: block;margin-right: 10px;"></image>
				<view style="line-height: 40upx;word-wrap: break-word;padding: 0px 0px 0 5px;flex: 1;font-size: 26upx;color: #666666;">
					{{item.company_address}}	
				</view>
			</view>
			<view style="height: 15px;"></view>
			<view class="des u-f-ac">
				<image src="../../static/xiao/prove.png" mode="" style="width: 40upx;height: 37upx;"></image>
				<view style="padding-left: 40upx;font-size: 36upx;color: #333;">职位详情</view>
			</view>
			<view class="content" style="padding: 0px 0;">
				<!-- <view style="line-height: 30px;color: #9B9B9B;font-size: 30upx;padding: 5px;word-break: break-word;">{{item.t_yq}}</view> -->
				<rich-text :nodes="item.responsibility" style="line-height: 30px;color: #666666;font-size: 14px !important;word-break: break-word;"></rich-text>
			</view>
			
			<view style="height: 50px;"></view>
			<view style="height: 50px;line-height: 50px;position: fixed;bottom: 0;left: 0;right: 0;box-shadow: 0 -3upx 8upx rgba(0, 0, 0, 0.08);;width:100%;display: flex;flex-wrap: nowrap;background: #fff;">
				<view style="height: 50px;line-height: 50px;width: 50%;">
					<button type="button" open-type="share" style="width: 100%;border: 0;outline: none;color: #0084FF;text-align: center;height: 50px;line-height: 50px;font-size: 16px;">分享</button>
				</view>
				
				<view style="height: 50px;line-height: 50px;width: 50%;text-align: center;color: #fff;background: #0084FF;font-size: 16px;"  @tap="sendinfo">报名</view>
			</view>
			
			
		</view>
		
		<view v-else style="height: 100%;">
			<view class="bg-white flex-sub radius shadow-lg" style="height: 100vh;box-sizing: border-box;padding-top: 300upx;">
				<image src="https://image.weilanwl.com/gif/loading-white.gif" mode="aspectFit" class="gif-white response" style="height:240upx"></image>
			</view>
		</view>
		
			<!-- <button @tap="add" data-target="Modal" class="cu-btn bg-red margin-tb-sm lg" style="width: 90%;background: #0084FF;position: fixed;bottom: 8px;left: 5%;">立刻投递</button> -->
		
	
	
	<view class="cu-modal" :class="modalName1=='Modal'?'show':''" style="z-index: 9999999999;">
		<view class="cu-dialog" style="width:70%;">
			<view class="cu-bar bg-white justify-end" style="text-align: left;background: #fff;color: #333;border-bottom: 1px solid #eee;">
				<view class="content" >登录/注册</view>
				
			</view>
			<view style="text-align: left;background: #fff;padding: 10upx 40upx;">
				
				<button   open-type="getPhoneNumber" @getphonenumber="getPhoneNumber" data-target="Modal1" class="cu-btn bg-red margin-tb-sm lg" style="width: 80%;background: #0084FF;margin: 15px auto;display: block;line-height: 44px;height: 44px;">微信账户快速登录</button>
				<!-- <button  @tap="wechat1" data-target="Modal1" class="cu-btn bg-red margin-tb-sm lg" style="width: 80%;background: #F4F4F4;margin: 15px auto;display: block;line-height: 44px;color: #333;line-height: 44px;height: 44px;">手机号注册/登录</button> -->
			</view>
		</view>
	</view>
	
	<view class="cu-modal" :class="modalName=='Modal'?'show':''" style="z-index: 9999999999;">
		<view class="cu-dialog" style="width:70%;">
			<view class="cu-bar bg-white justify-end" style="text-align: left;background: #0084FF;color: #fff;">
				<view class="content" >绑定手机号</view>
				
			</view>
			<view style="text-align: left;background: #fff;padding: 40upx;">
				<view class="cu-form-group" style="border-bottom: 1px solid #EEEEEE;padding: 18upx 10upx;">
					<input placeholder="输入手机号" name="input" v-model="phone" style="100%;font-size: 30upx;"></input>
				</view>
				<view class="cu-form-group u-f-ac" style="border-bottom: 1px solid #EEEEEE;padding:18upx 10upx;">
					<input placeholder="输入验证码" name="input" v-model="code" style="width: 50%;font-size: 30upx;"></input>
					<view style="font-size: 30upx;color:#0486FF;" v-if="sendcode" @tap="send">获取验证码</view>
					<view style="font-size: 30upx;color:#0486FF;" v-else >{{num}}s</view>
				</view>
				
				
				<button  @tap="reg" data-target="Modal1" class="cu-btn bg-red margin-tb-sm lg" style="width: 70%;background: #0084FF;margin: 30px auto;display: block;line-height: 44px;">绑定</button>
				
			</view>
		</view>
	</view>
	
		</view>
</template>

<script>
	var graceMd5 = require("../../css/md5.js");

	export default {
		data() {
			return {
				phone:'',
				id:'',
				item:{
					name:'',
					t_jr:1,
					pay:'',
					workExp:'',
					education:'',
					age:'',
					labelIds:[],
					address:'',
					positionRequirement:''
				},
				modalName: null,
				modalName1:null,
				login_code : '',  
				sta:'',
				tabIndex:0,
				sendcode:true,
				show:false,
				con:'',
				code:'',
				num:60,
				newCode:'',
				openid:'',
				name:'',
				positionId:'',  //职位id
				orderId:'',
				userId:'',
				stax:false,
			};
		},
		onLoad:function(options){
			
				// #ifdef  MP-WEIXIN
					
					var scene = decodeURIComponent(options.scene)
					console.log(scene);
					if(decodeURIComponent(options.scene)){
						var scene = decodeURIComponent(options.scene)
						
						//切割
						var x=scene.split('#');
						console.log('二维码')
						console.log(x)
						// this.userId=x[0];
						// this.positionId=x[1];
						// this.id=x[1];
						
						this.userId=options.operator_id;
						this.positionId=options.job_description_id;
						this.id=options.job_description_id;
					}
					
					
				// #endif
			
			
			if(options.userId){
				console.log('分享')
				this.userId=options.userId;
				this.positionId=options.positionId;
				this.id=options.positionId;
			}
			
			
			console.log(options);
			console.log(scene);
			var _this =this;

			console.log('执行了');
						  
			   _this.login();
			   _this.getInfo();
			  uni.login({    
			      success: function(res) {    
			          _this.login_code = res.code;  
			      }  
			  }); 
			   uni.showShareMenu();
		},
		onShareAppMessage(res) {
					if (res.from === 'button') {// 来自页面内分享按钮
					  console.log(res.target)
					}
					return {
					  title: '您的朋友邀请您参加'+this.item.name+'职位的面试',
					  path: '/pages/jobinfo/jobinfo?userId='+this.userId+'&positionId='+this.positionId,
					  imageUrl:'https://521.zhengbu121.com/statics/images/321123.png'
					}
		 },
		methods: {
			showModal() {
				
				this.modalName = 'Modal'
			},
			hideModal(e) {
				this.modalName = null
			},
			showModal1() {
				
				this.modalName1 = 'Modal'
			},
			hideModal1(e) {
				this.modalName1 = null
			},
			//授权登录
			login1:function(){
				var _this=this;
					uni.request({
					url: _this.apiServer + '/api/Auth/getToken',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						grantType:graceMd5.md5('zhengbu_client_credential'),
				 		webId:graceMd5.md5('zhengbuwangluokejiwebid'),
				 		webSecret:graceMd5.md5('zhengbuwangluokejisecret'),
					},
					success: res => {
						console.log(res);
						var status=res.data.status;
						var data=res.data.data;
						if(res.data.errorCode==0){
							uni.setStorageSync('utoken',res.data.data.access_token);
							_this.login(res.data.data.access_token);
							_this.getInfo();
						}
						
						
					}
				});
			},
			//授权登录
			//授权登录
			login:function(e){
				var _this=this;
				uni.login({
					success:function(res){
						
						
						uni.request({
							url: _this.apiServer1 + '/mini/users/xcx_login',
							method: 'POST',
							header: {'content-type' : "application/x-www-form-urlencoded"},
							data:{
								xcx_code   : res.code,
								
							},
							success: res => {
								console.log(1111111);
								console.log(res);
								var status=res.data.error_code;
								var data=res.data;
								if(status=='0'){
									//未注册手机号
									if(data.mobile==0){
										_this.modalName1 = 'Modal'
										_this.openid=data.xcx_openid;
										
									}else{
										//已注册手机号
									
										// uni.setStorageSync('uid',res.data.data.uid);
										uni.setStorageSync('openid',res.data.xcx_openid);
										uni.setStorageSync('phone',res.data.mobile);
									}
									
								}
							}
						});
					}
				})
			},
			wechat1:function(){
				this.modalName1 = null
				this.showModal();
			},
			getPhoneNumber: function(e) {    
			    console.log(e);    
				
				
				
				
				
				
				this.modalName1 = null;
				var _this=this;
			    if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {    
			        console.log('用户拒绝提供手机号');  
			    } else {    
			        console.log('用户同意提供手机号');  
			
			        console.log(JSON.stringify(e.detail.encryptedData));    
			        console.log(JSON.stringify(e.detail.iv));   
					
					console.log(e.detail.encryptedData);    
					console.log(e.detail.iv);  
					
			        var encryptedData = e.detail.encryptedData;  
			        var iv = e.detail.iv;  
			
			        ////////////////////////////////////////////////////////////////////////////////  
			        //定义在根目录下的main.js里  
			        //Vue.prototype.APPID                           = 'wxb1a马赛克2bfc90a';  
			        //Vue.prototype.SECRET                          = 'b3ae36758马赛克dbe146d9acd81d';  
			        //Vue.prototype.WX_AUTH_URL                     = 'https://api.weixin.qq.com/sns/jscode2session';  
			
			        var JSCODE = this.login_code;  
					console.log(JSCODE)
			
			  //       var APPID = this.APPID;  
			  //       var SECRET = this.SECRET;  
			  //       var wx_author_url = 'https://api.weixin.qq.com/sns/jscode2session'+'?appid=wx02c9a76ab01f424c'+'&secret=22f25c64080e8a640d93d104cbc2a3ea'+'&js_code='+ JSCODE + '&grant_type=authorization_code';  
					// 
					
					uni.request({
							url: _this.apiServer1 + '/mini/users/get_user_info',
							// url:'http://47.103.59.100:9091/web/#/page/173',
							method: 'POST',
							header: {'content-type' : "application/x-www-form-urlencoded"},
							data:{
								'js_code':JSCODE,
								'encryptedData':encryptedData,
								'iv':iv,
							},
							success: res => {
								console.log(res);
								if(res.data.error_code==0){
									_this.phone=res.data.phoneNumber;
									 _this.reg1();
								}
							}
						});
					
					
			//         uni.request({  
			//             url : wx_author_url,  
			//             success(re){  
			// 				console.log(re);
			//                 console.log( 'session_key:' + re.data.session_key );  
			// 
			//                 var appId = 'wx02c9a76ab01f424c';  
			//                 var sessionKey = re.data.session_key;  
			// 
			//                 var pc = new WXBizDataCrypt(appId, sessionKey);  
			//                 var data = pc.decryptData(encryptedData, iv);  
			// 					
			//                _this.phone=data.phoneNumber;
			// 			   _this.reg1();
			//                 console.log('解密后 data: ', data);  
			//                 
			// 
			//             }  
			//         });  
			       
			
			    }    
			
			},  
			
			//获取验证码
			send:function(){
				var _this=this;
					if(_this.phone.length!=11){uni.showToast({title:'请输入正确手机号', icon:"none"}); return ;}
					this.sendcode=false;
				
				var time=setInterval(function(){
					if(_this.num==0){
						_this.sendcode=true;
						clearInterval(time);
						_this.num=60;
					}else{
						_this.num--;
					}
				},1000)
				
					uni.request({
						url: _this.apiServer1 + '/mini/users/send_auth',
						method: 'POST',
						header: {'content-type' : "application/x-www-form-urlencoded"},
						data:{
							mobile   : _this.phone,
							// accessToken: uni.getStorageSync('utoken')
							
						},
						success:function(res){
							console.log(res);
							if(res.data.error_code == '0'){
								uni.showToast({title:"发送成功", icon:"none"});
								// _this.newCode=res.data.data;
								
							}else{
								uni.showToast({title:res.data.data, icon:"none"});
							}
						}
					});
			},
			reg1:function(){
				var _this=this;
				
				uni.showLoading({title:"正在提交"});
				uni.request({
					url: _this.apiServer1 + '/mini/users/xcx_binding',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						mobile   : _this.phone,
						xcx_openid:_this.openid,
					},
					success:function(res){
						console.log(res);
						 
						 if(res.data.error_code == 0){
				
								uni.setStorageSync('uid',res.data.sid);
								uni.setStorageSync('phone',res.data.mobile);
								uni.showToast({title:'绑定成功',icon:'none'});
								// uni.setStorageSync('user_id' , res.data.data);	
								_this.modalName = null
							}else{
								
								uni.showToast({title:res.data.msg,icon:'none'});
							}
							
						
					}
				});
				
			},
			reg:function(){
				var _this=this;
				if(_this.phone.length!=11){uni.showToast({title:'请输入正确手机号', icon:"none"}); return ;}
				if(_this.code<1){uni.showToast({title:'请输入验证码', icon:"none"}); return ;}
				uni.showLoading({title:"正在提交"});
				uni.request({
					url: _this.apiServer1 + '/mini/users/login',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						mobile   : _this.phone,
						auth_code: _this.code,
						xcx_openid:_this.openid
					},
					success:function(res){
						console.log(res);
						 
						 if(res.data.error_code == 0){
				
								uni.setStorageSync('uid',res.data.sid);
								uni.setStorageSync('phone',res.data.mobile);
								
								uni.showToast({title:'绑定成功',icon:'none'});
								// uni.setStorageSync('user_id' , res.data.data);	
								_this.modalName = null
							}else{
								
								uni.showToast({title:res.data.msg,icon:'none'});
							}
							
						
					}
				});
				
			},
			getInfo:function(){
				var _this=this;
			
				uni.showLoading({'title':"加载中..."});
				uni.request({
					url:_this.apiServer1 +"/mini/job_descriptions/detail",
					// url:_this.apiServer +"/api/v1.ep.EpOrder/getDetailNoLogin",
					
					method:'POST',
					data:{
						
						'job_description_id':_this.id,
						
					},
					header:{'content-type':'application/x-www-form-urlencoded'},
					success:function(res) {
						console.log(123456);
						 console.log(res);
						 if(res.data.error_code==0){
							 _this.item=res.data.detail;
							 _this.stax=true;
						 }else{
							uni.showToast({
									title:res.data.error_reason,
									icon:"none"
								}); 
						 }
						
						
							
							uni.hideLoading();
							
							
							
					
						
					}
				})
			},
			
			sendinfo:function(){
				
				//先获取有没有简历
				var _this=this;
				
				uni.showLoading({'title':"加载中..."});
				uni.request({
					url:_this.apiServer1 +"/mini/resumes/apply",
					method:'POST',
					data:{
						'sid': uni.getStorageSync('uid'),
						'operator_id':_this.userId,
						'job_description_id':_this.id
					},
					header:{'content-type':'application/x-www-form-urlencoded'},
					success:function(res) {
						uni.hideLoading()
						 console.log(111111111122);
						 console.log(res);
						// var data = res.data.data.list;
						var status = res.data.error_code;
						 console.log(status);
						if(status=='0'){
							
							uni.showToast({
									title:res.data.error_reason,
									icon:"none"
								});
							
						}else if(status=='-1'){
							console.log('创建简历了');
							uni.showModal({
							title: '提示',
							content: '请您先添加简历，然后在进行职位投递',
							success: function (res) {
								if (res.confirm) {
									//填写简历
									uni.navigateTo({
										url: '../resume/resume'
									});
								} else if (res.cancel) {
									console.log('用户点击取消');
								}
							}
						});
							
							
						}
						
					}
				})
				
				
			},
			sendfor:function(e){
				var _this=this;
				// var openid =uni.getStorageSync('openid');
				console.log(_this.id);
				uni.showLoading({'title':"提交中..."});
				uni.request({
					url:_this.apiServer+"/api/v1.ep.EpOrderApply/applyWithResume",
					method:'POST',
					data:{
						'id_token':uni.getStorageSync('token'),
						'positionId':parseInt(this.positionId),
						'shareUserId':parseInt(this.userId),
						'accessToken': uni.getStorageSync('utoken')
						
					},
					header:{'content-type':'application/x-www-form-urlencoded'},
					success:function(res) {
						 console.log(res);
						var data = res.data.data;
						var status = res.data.errorCode;
						uni.hideLoading();
						if(status=='0'){
							//投递简历
							
							uni.showToast({
									title:'投递成功',
									icon:"none"
								});
							
						}else if(status=='-20000018'){
							uni.showToast({
									title:'已经申请过该职位',
									icon:"none"
								});
						}else if(status=='-10000005'){
							uni.showToast({
									title:'用户简历不存在',
									icon:"none"
								});
						}
			
						
						
					}
				})
			},
			
		
		}
	}
</script>


<style>
.body{background: #fff;width: 100%;box-sizing: border-box;padding: 20px 10px;}
.text{
	overflow: hidden;
	text-overflow:ellipsis;
	white-space: nowrap;
}
.name{color: #333;font-size: 35upx;}
.tag{height: 20px;padding: 0 10px;line-height: 20px;font-size: 24upx;color: #FF6969;border: 1px solid #FF6969;border-radius: 15px;}
.money{color: #363292;font-size: 32upx;}
.title1{display: flex;}
.gg{font-size: 30upx;color: #666;}
.tt{width: 20px;text-align: center;color: #333;font-size: 30upx;}
.tagx{
		width: 100%;height: auto;flex-wrap: wrap;
	}
.tagone{
		font-size: 24upx;padding: 10upx 18upx;background:#EEEEEE ;color: #333333;margin-right: 40upx;display: inline-block;margin-bottom: 12upx;
	}
.address{width: 100%;height: auto;border-top: 2px solid #FAFAFA;border-bottom: 7px solid #FAFAFA;color: #666;font-size: 30upx;}
.des{height: 50px;line-height: 50px;font-size: 35upx !important;color: #333;letter-spacing: 2px;}	


.list{padding-top: 50px;}
.title{font-size: 35upx;color: #333;}
.con{width: 100%;box-sizing: border-box;padding: 80px 20%;}
.bb{height: 35px;line-height: 35px;border: 1px solid #363292;color: #363292;text-align: center;}
.axx{padding: 0 10px;font-size: 40upx;line-height: 35px;}




.cu-form-group picker::after {
	
    font-family: iconfont;
  display: none;
  content: "\e6a3";
  position: absolute;
  font-size: 34rpx;
  color: #aaa;
  line-height: 100rpx;
  width: 60rpx;
  text-align: center;
  top: 0;
  bottom: 0;
  right: -20rpx;
  margin: auto;
}

.cu-form-group picker .picker {
	border: 1px solid #EDEDED;
  line-height: 60rpx;
  font-size: 28rpx;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  width: 100%;
  text-align: center;
}
.uni-mask{
	z-index: 9999999999999!important;
}
.uni-picker{z-index:99999999999999999999999 !important}.uni-mask{z-index:999999999999999999999} 
	.cu-form-group .title {
	  text-align: justify;
	  font-size: 30rpx;
	  position: relative;
	  height: 60rpx;
	  line-height: 60rpx;
		width: 38%;
	}
	rich-text p{
		font-size: 24upx !important;
	}
</style>

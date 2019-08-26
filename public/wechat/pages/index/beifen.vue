<template>
	<view>
		<image @tap="go1" src="https://521.zhengbu121.com/statics/images/333333.jpg" mode="widthFix" style="width: 100%;display: block;height: 400upx;"></image>
	<!-- 搜索想 -->
		  <view class="top" style="position: absolute;top: 215upx;left: 0;right: 0;background: rgba(255,255,255,0.1);">
			   <view class="seach shadow" style="display: flex;flex-wrap: nowrap;justify-content: space-between;width: 100%;flex-direction: row;">
					<view class="s-left" style="width: 100upx;position: relative;">
						<image src="../../static/xiao/search.png" mode="" style="width: 40upx;height: 40upx;position: absolute;left: 20upx;top:15upx ;"></image>
					</view>
					<view class="s-mod" style="width: 75%;height: 70upx;">
						<input type="text" value="" style="height: 70upx;line-height: 70upx;color: #666;font-size: 25upx;" placeholder="搜索您想投递的职位" v-model="con" />
					</view>
					<view @tap="go" class="s-right" style="width: 150upx;text-align: center;color: #888888;height: 70upx;line-height: 70upx;font-size: 25upx;border-left: 1px solid #EEEEEE;">
						搜索
					</view>
			   </view>
		  </view>
		  <view style="height: 30upx;background:#fff;"></view>
		  
		  
		  
		  
		  
		  
		  
		<!-- 列表 -->
		<view class="topic-detail-list">
			<block v-for="(item,index) in tablist" :key="index">
				<template v-if="tabIndex==index">
					<!-- 列表 -->
					<block v-if="tabIndex==0"  v-for="(list,listindex) in item.list" :key="listindex">
					<!-- <block v-if="tabIndex==0"  v-for="(list,listindex) in 10" :key="listindex"> -->
						<common-list :item="list" :index="listindex"></common-list>
					</block>
					<!-- 上拉加载 -->
					<load-more :loadtext="item.loadtext" v-if="show"></load-more>
				</template>
			</block>
		</view>
		
		
			
		<view class="cu-modal" :class="modalName=='Modal'?'show':''">
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
	
	import commonList from "../../components/common/common-list.vue";
	import loadMore from "../../components/common/load-more.vue";
	
	var page;
	export default {
		components:{
			
			commonList,
			loadMore
		},
		data() {
			return {
				topicInfo:{
					titlepic:"../../static/demo/topicpic/13.jpeg",
					title:"忆往事，敬余生",
					desc:"我是描述",
					totalnum:1000,
					todaynum:1000,
				},
				sta:'',
				modalName: null,
				tabIndex:0,
				sendcode:true,
				tabBars:[
					{ name:"分享",id:"moren" },
					{ name:"消息",id:"zuixin" },
				],
				swiperheight:'fixed',
				tablist:[
					{
						loadtext:"上拉加载更多",
						list:[
							
						]
					}
				],
				show:false,
				con:'',
				phone:'',
				code:'',
				num:60,
				newCode:'',
				openid:''
			};
		},
		onLoad:function(){
			//判断是否登录
			// var loginRes = this.checkLogin('../dongtai/dongtai', '2');
			// if(!loginRes){return false;}
			var _this=this;
			 this.getinfo();
			 setTimeout(function(){
				 _this.login();
				 // _this.showModal();
			 },500)
			  uni.showShareMenu();
			 
		},
		
		 onShareAppMessage(res) {
			if (res.from === 'button') {// 来自页面内分享按钮
			  console.log(res.target)
			}
			return {
			  title: '找工作上稍息立正',
			  path: '/pages/index/index'
			}
		  },
		// 上拉触底事件
		onReachBottom() {
			// 上拉加载
			this.loadmore();
		},
		// 监听下拉刷新 
		onPullDownRefresh(){
			this.getdata();
		},
		methods:{
			showModal() {
				
				this.modalName = 'Modal'
			},
			hideModal(e) {
				this.modalName = null
			},
			go1:function(){
				uni.navigateTo({
					url: '../se/se',
					
				});
			},
			//授权登录
			login:function(){
				var _this=this;
				uni.login({
					success:function(res){
						console.log(res);
						return;
						uni.request({
							url: _this.apiServer + 'app/codeToSession?code='+res.code,
							method: 'GET',
							success: res => {
								console.log(res);
								var status=res.data.status;
								var data=res.data.data;
								if(status=='ok'){
									//未注册手机号
									if(data.phone==0){
										_this.showModal();
										_this.openid=data.openid;
									}else{
										//已注册手机号
										uni.setStorageSync('user_id',data.u_id);	
									}
									
								}
							}
						});
					}
				})
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
						url: _this.apiServer + 'app/sendcode',
						method: 'POST',
						header: {'content-type' : "application/x-www-form-urlencoded"},
						data:{
							phone   : _this.phone,
							
							
						},
						success:function(res){
							console.log(res);
							if(res.data.status == 'ok'){
								uni.showToast({title:"发送成功", icon:"none"});
								_this.newCode=res.data.data;
								
							}else{
								uni.showToast({title:res.data.data, icon:"none"});
							}
						}
					});
			},
			reg:function(){
				var _this=this;
				if(_this.phone.length!=11){uni.showToast({title:'请输入正确手机号', icon:"none"}); return ;}
				if(_this.code!=_this.newCode){uni.showToast({title:'验证码错误', icon:"none"}); return ;}
				uni.showLoading({title:"正在提交"});
				uni.request({
					url: _this.apiServer + 'app/res',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						phone   : _this.phone,
						openid:_this.openid
					},
					success:function(res){
						console.log(res);
						 
							if(res.data.status=='error'){
								uni.showToast({title:res.data.data,icon:'none'});
							}else{
								uni.showToast({title:'绑定成功',icon:'none'});
								// uni.setStorageSync('user_id' , res.data.data);	
								_this.modalName = null
								
							}
						
					}
				});
				
			},
			
			//获取数据
			
			getinfo:function(){
				var _this=this;
				uni.showLoading({title:"加载中..."});
				page=1;
				
				uni.request({
					url: _this.apiServer + 'app/dtlist',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						'page':page
						
					},
					success:function(res){
						page++;
						if(res.data.status == 'ok'){
							uni.hideLoading();
							var data=res.data.data;
							console.log(data);
							for (var i=0;i<data.length;i++) {
								_this.sta=data[0].id;
								
								_this.tablist[_this.tabIndex].list.push(data[i]);	
							}
							
							if(data.length<10){
								_this.tablist[_this.tabIndex].loadtext="已加载全部"; 
							}else{
								_this.tablist[_this.tabIndex].loadtext="上拉加载更多"; 
							}
							
							_this.show=true;
							
						}else{
							uni.showToast({title:res.data.data, icon:"none"});
						}
					}
				});
				
				
				
			},
			go:function(){
				if(this.con < 1){uni.showToast({title:'搜索内容不能为空', icon:"none"}); return ;}
				
				uni.navigateTo({
					url: 'seach?con='+this.con
				});
			},
			// 上拉刷新
			getdata(){
				
					
				
				var _this=this;
				console.log(_this.sta);
				uni.showLoading({title:"加载中..."});
				
				
				uni.request({
					url: _this.apiServer + 'app/dtlist',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						'sta':_this.sta,
						'page':1
					},
					success:function(res){
						// 关闭下拉刷新
						uni.stopPullDownRefresh();
						if(res.data.status == 'ok'){
							
							
							var data=res.data.data;
							uni.showToast({title:"有"+data.length+"条新职位", icon:"none"});
							console.log(data);
							if(data.length>0){
								for (var i=0;i<data.length;i++) {
									_this.sta=data[0].id;
									
									_this.tablist[_this.tabIndex].list.unshift(data[i]);	
								}
							}else{
								uni.showToast({title:"暂无新职位", icon:"none"});
							}
							
							
							
							
							_this.tablist[_this.tabIndex].loadtext="上拉加载更多";
							console.log(_this.tablist)
							
						}else{
							uni.showToast({title:res.data.data, icon:"none"});
						}
						uni.hideLoading();
					}
				});
				
				
				
			},
			// 上拉加载
			loadmore(){
				console.log('chudil');
				if(this.tablist[this.tabIndex].loadtext!="上拉加载更多"){ return; }
				// 修改状态
				this.tablist[this.tabIndex].loadtext="加载中...";

				var _self=this;
				console.log(page);
				 uni.showNavigationBarLoading();
								
								uni.request({
									url: _self.apiServer + 'app/dtlist',
									data: {'page':page},
									method:"POST",
									header : {'content-type':'application/x-www-form-urlencoded'},
									success: function (res) {
									// _self.product =res.data;
								var res=res.data;
									_self.tablist[_self.tabIndex].loadtext = '';
									console.log(res.data);
									 if(res.data.length == 0||res.data.length=='undefined'){
										 uni.showToast({title:"已经到底了", icon:"none"});
										uni.hideNavigationBarLoading();
										_self.tablist[_self.tabIndex].loadtext= '已加载全部';
										return false;
									 }
									 page++;
									
									 //concat 将两个数组拼接起来
									 // 获取数据
									 setTimeout(()=> {
										 // _this.sta=res.data[0].id;
										 _self.tablist[_self.tabIndex].list= _self.tablist[_self.tabIndex].list.concat(res.data);
										_self.tablist[_self.tabIndex].loadtext = '上拉加载更多';
										uni.hideNavigationBarLoading();
										
				
									 }, 1000);
									 
									
									}
									});
			},
			addx:function(){
				uni.navigateTo({
					url: 'dongtaiadd'
				});
			},
			// tabbar点击事件
			tabtap(index){
				this.tabIndex=index;
			},
		}
	}
</script>

<style>
	
	page {
  background-color: #FFFFFF;
  height: 100%;
  font-size: 11px;
  line-height: 1.8;
 }

 .top{
  width: 100%;
  box-sizing: border-box;
  padding: 50upx;
  background: #fff;
 }
 .seach{
  width: 100%;
  height: 70upx;
  line-height: 70upx;
  padding: 0 15upx;
  border-radius: 5px;
  background: #fff;
  /* display: flex;
  align-items: center; */
 }
input::-webkit-input-placeholder{
        color: #C1C1C1;
		letter-spacing: 2upx;
}
.add{
		background: #88BAFB;
		color: #fff;
		font-weight: bold;
		font-size: 50upx;
		width: 100upx;
		height: 100upx;
		line-height: 100upx;
		text-align: center;
		border-radius: 50%;
		position: fixed;
		bottom: 100upx;
		right: 40upx ;
		
	}
</style>

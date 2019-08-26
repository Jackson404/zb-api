<template>
	<view>
		
	
		<!-- 列表 -->
		<view class="topic-detail-list" v-if="show">
			
			
			
			<view class="common-list animated fadeInLeft fast" @tap="reg(item.id)"   v-for="(item,index) in list" :key="index">
				
				<view class="com-top u-f-ajc">
					<view class="com-left text" style="display: flex;flex-wrap: nowrap;align-items: center;">
						<text class="text" style="font-size: 34upx;max-width: 70%;display: block;margin-right: 10upx;">{{item.name}}</text> <image v-if="item.t_jr==2"  src="https://www.zhengbu121.com/statics/img/1.png" mode="widthFix" style="width: 30upx;height: 30upx;display: inline-block;" lazy-load="true"></image>
					</view>
					<view class="com-right text" style="font-size: 32upx;color: #0084FF;">
						{{item.pay}}元
					</view>
				</view>
				
				<view class="com-mod u-f-ajc">
					<view class="com-m-left text u-f-ac">
						<view class="bar" style="padding-left: 0;" v-if="item.workExp==0">不限</view>
						<view class="bar" style="padding-left: 0;" v-else>{{item.workExp}}</view>
						<view class="bar">{{item.education}}</view>
						<view class="bar">{{item.age}}</view>
					</view>
					<view class="com-m-right">
						
					</view>
				</view>
				
				<view class="com-mod u-f-ajc">
					<view class="com-m-left text" style="width: 70%;">
						{{item.companyName}}
					</view>
					<view class="com-m-right" style="width: 30%;">
						{{item.createDate}}
					</view>
				</view>
			</view>
			
			
		</view>
		
		
			<view style="width: 100%;box-sizing: border-box;padding: 0upx;height: 100vh;" v-if="!show">
				
					<image src="../../static/sxx.png" style="width: 34vh;height:34vh;margin: 33vh auto;display: block;" mode="widthFix"></image>
				
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
			loadMore,
			show:false
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
				tabIndex:0,
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
				list:[],
				show:false,
				con:''
			};
		},
		onLoad:function(e){
			console.log(e);
			//判断是否登录
			// var loginRes = this.checkLogin('../dongtai/dongtai', '2');
			// if(!loginRes){return false;}
			// uni.setNavigationBarTitle({
			// 	title: e.con
			// });
			  this.getinfo(e.con);
		},
		
		methods:{
			
			//获取数据
			reg:function(e){
				
					uni.navigateTo({
						url: '../orderInfo/orderInfo?id='+e
					});
				
				
				
			},
			
			getinfo:function(e){
				var _this=this;
				uni.showLoading({title:"加载中..."});
				
				
				uni.request({
					url: _this.apiServer + '/api/v1.PositionManagement/search',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						'searchValue':e,
						accessToken:uni.getStorageSync('utoken')
						
					},
					success:function(res){
						uni.hideLoading();
						
						if(res.data.errorCode == '0'){
							console.log(res.data);
							var data=res.data.data.list;
							
							if(data.length>0){
								_this.show=true;
							}else{
								_this.show=false;
							}
							for (var i=0;i<data.length;i++) {
								
								
								_this.list.push(data[i]);	
							}
							
							// if(data.length<10){
							// 	_this.tablist[_this.tabIndex].loadtext="已加载全部"; 
							// }else{
							// 	_this.tablist[_this.tabIndex].loadtext="上拉加载更多"; 
							// }
							
							
							
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
								uni.showToast({title:"暂无新动态", icon:"none"});
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
  background: #0084FF;
 }
 .seach{
  width: 100%;
  height: 70upx;
  line-height: 70upx;
  padding: 0 15upx;
  border-radius: 25px;
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
		right: 40upx;
		
	}
	
	.common-list{
		padding: 20upx;
		width: 100%;
		box-sizing: border-box;
		/* overflow: hidden; */
		border-bottom: 10upx solid #FAFAFA;
	}
	.text{
		overflow: hidden;
		text-overflow:ellipsis;
		white-space: nowrap;
	}
	
	.com-top{
		padding: 10upx 0;
		width: 100%;
	}
	.com-left{
		width: 70%;
		font-size: 40upx;
		color: #333;
	}
	.com-right{
		width: 30%;
		font-size: 40upx;
		color: #57A6FE;
		text-align: right;
	}
	.com-mod{
		width: 100%;
		padding: 5upx 0;
		
	}
	.com-m-left{
		width: 80%;
		font-size: 24upx;
		color: #333333;
	}
	.com-m-right{
		width: 20%;
		font-size: 24upx;
		color: #333333;
		text-align: right;
	}
	.bar{
		font-size: 24upx;
		color: #333333;
		padding: 0 15upx;
	}
</style>

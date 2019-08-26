<template>
	<view>
		<view style="position: fixed;top: 0;left: 0;right: 0;z-index: 9999999;">
		
			<image src="https://521.zhengbu121.com/statics/images/321123.jpg"  style="width: 100%;display: block;height: 340upx;"></image>
			<!-- 搜索想 -->
				  <view class="top" style="position: absolute;top: 180upx;left: 0;right: 0;background: rgba(255,255,255,0.1);">
					   <view class="seach shadow" style="display: flex;flex-wrap: nowrap;justify-content: space-between;width: 100%;flex-direction: row;">
							<view class="s-left" style="width: 100upx;position: relative;">
								<image src="../../static/xiao/search.png" mode="" style="width: 40upx;height: 40upx;position: absolute;left: 20upx;top:15upx ;"></image>
							</view>
							<view class="s-mod" style="width: 75%;height: 70upx;">
								<input type="text" @focus="InputFocus" @blur="InputBlur" value="" style="height: 70upx;line-height: 70upx;color: #666;font-size: 25upx;" placeholder="搜索您想投递的职位" v-model="con" />
							</view>
							<view @tap="go" class="s-right" style="width: 150upx;text-align: center;color: #888888;height: 70upx;line-height: 70upx;font-size: 25upx;border-left: 1px solid #EEEEEE;">
								搜索
							</view>
					   </view>
				  </view>
				  <!-- <view style="height: 40upx;background:#fff;"></view> -->
				  
				  
				  <view class="grace-filter" id="grace-filter-header" style="display: flex;width: 100%;">
				  	<!-- <view class="items" @tap='showOptions1'>行业<text class="grace-iconfont icon-arrow-down"></text></view> -->
				  	<view class="items" @tap='showOptions2' style="felx:1">薪资<text class="grace-iconfont icon-arrow-down" ></text></view>
					<view class="items" @tap='showOptions3' style="felx:1">要求<text class="grace-iconfont icon-arrow-down"></text></view>

				  <!-- 	<view class="items" @tap='changePriceOrder'>
				  		价格
				  		<image src='../../static/imgs/sort1.png' mode='widthFix' v-if="priceOrder == 1"></image>
				  		<image src='../../static/imgs/sort2.png' mode='widthFix' v-if="priceOrder == 2"></image>
				  	</view> -->
				  	<view class="items" @tap='showOptions99' style="felx:1">其他<text class="grace-iconfont icon-arrow-down"></text></view>
				  	<!-- 第一个选项 -->
				  	<view class='grace-filter-options'  v-if="showingIndex == 1" :style="{'width':'100%', 'height' : filterHeight, 'padding':'0','background':'rgba(0,0,0,0.5)'}">
				  		<view style="background: #fff;display: flex;;flex-wrap: nowrap;overflow: scroll;padding-bottom: 55px;box-sizing: border-box;" :style="{'height':filterHeight1}">
							<view style="width: 25%;box-sizing: border-box;background: #F6F6F6;height: 100%;overflow: scroll;">
								<view @tap.stop="chnn(index)" style="min-height: 100upx;line-height: 60upx;text-align: center;color: #666;font-size: 28upx;box-sizing: border-box;padding: 20upx 0;;" :class="item.checked ?'xx':''" v-for="(item,index) in hangye" :key="index">
									{{item.name}}
								</view>
							</view>
							<view style="width: 75%;box-sizing: border-box;padding: 20upx;height: 100%;overflow: scroll;position: relative;">
								<view style='padding:20upx' class="grace-select-tips">
									<radio-group name="where2" @change.stop="changeFunc25">
										<label v-for="(item, index) in hangye[hangindex].list" :key="index" :class="[item.checked ? 'grace-checked' : '']">
											<radio :value="item.id + ''" :checked="item.checked"></radio> {{item.name}}
										</label>
									</radio-group>
								</view>
								<!-- <view style="height: 50px;"></view> -->
							</view>
							
							<view class='grace-filter-buttons1' style="border-top: 1px solid #EEEEEE;position: fixed;left: 0;right: 0;height: auto;" :style="{'bottom':filterHeight2}">
								<view style="background: #FFFFFF;width: 100%;color: #333;">
									重置
									<button form-type='reset' @tap.stop="cz">重置</button>
								</view>
								<!-- <view >
									确定
									<button form-type='submit' @tap.stop="que">确定</button>
								</view> -->
							</view>
						</view>
						<view @tap="hidel" :style="{'height':filterHeight3,'background':'rgba(0,0,0,0.5)'}">
							
						</view>
				  	</view>
				  	<!-- 第二个选项 -->
				  	<view class='grace-filter-options'  v-if="showingIndex1 == 2" :style="{'width':'100%', 'height' : filterHeight, 'padding':'0','background':'rgba(0,0,0,0.5)'}">
				  		<view style="background: #fff;" >
						<scroll-view scroll-y="true">
							<view style="width:100%; padding:15upx 0;display: block;position: relative;overflow: scroll;" :style="{'height':filterHeight1}">
								
								<view style='padding:20upx 0;' class="grace-select-tips">
									<radio-group name="where2" @change.stop="changeFunc21">
										<label v-for="(item, index) in where2Tips1" :key="index" :class="[item.checked ? 'grace-checked' : '']">
											<radio :value="item.value + ''" :checked="item.checked"></radio> {{item.name}}
										</label>
									</radio-group>
								</view>
								<view style="height:50px;"></view>
								<!-- 占位视图组件 -->
								<view class='grace-filter-buttons1' style="border-top: 1px solid #EEEEEE;position: fixed;left: 0;right: 0;height: auto;" :style="{'bottom':filterHeight2}">
									<view>
										重置
										<button form-type='reset' @tap.stop="cz">重置</button>
									</view>
									<view style="background: #0084FF;">
										确定
										<button form-type='submit' @tap.stop="que">确定</button>
									</view>
								</view>
							</view>
							<view @tap="hidel" :style="{'height':filterHeight3,'background':'rgba(0,0,0,0.5)'}">
								
							</view>
							
						</scroll-view>
						<!-- 按钮  -->
					
						</view>
				  	</view>
					<!-- 第二个选项 -->
					<view class='grace-filter-options'  v-if="showingIndex2 == 3" :style="{'width':'100%', 'height' : filterHeight, 'padding':'0','background':'rgba(0,0,0,0.5)'}">
						<view style="background: #fff;" >
						<!-- <view :class="[index ==  cateIndex ? 'option current' : 'option']" v-for="(item, index) in cateList" :key="index" @tap='changeCate' :data-itemid="index">
							{{item}}<text class="grace-iconfont icon-right" v-if="index ==  cateIndex"></text>
						</view> -->
						
						<!-- <form @submit='formsubmit' @reset='formReset'> -->
							<scroll-view scroll-y="true">
								<view style="width:100%; padding:15upx 0;display: block;position: relative;overflow: scroll;" :style="{'height':filterHeight1}">
									
									<view class="grace-h5 grace-blod" style="box-sizing: border-box;padding: 15upx;">学历要求</view>
									<view  class="grace-select-tips" style="box-sizing: border-box;padding: 15upx;">
										<radio-group name="where2" @change.stop="changeFunc22">
											<label v-for="(item, index) in where2Tips2" :key="index" :class="[item.checked ? 'grace-checked' : '']">
												<radio :value="item.value + ''" :checked="item.checked"></radio> {{item.name}}
											</label>
										</radio-group>
									</view>
									
									<view class="grace-h5 grace-blod" style="box-sizing: border-box;padding: 15upx;">工作经验</view>
									<view  class="grace-select-tips" style="box-sizing: border-box;padding: 15upx;">
										<radio-group name="where2" @change.stop="changeFunc23">
											<label v-for="(item, index) in where2Tips3" :key="index" :class="[item.checked ? 'grace-checked' : '']">
												<radio :value="item.value + ''" :checked="item.checked"></radio> {{item.name}}
											</label>
										</radio-group>
									</view>
									<!-- 占位视图组件 -->
									<view style="height:50px;"></view>
									<!-- 按钮  -->
									<view class='grace-filter-buttons1' style="border-top: 1px solid #EEEEEE;position: fixed;left: 0;right: 0;height: auto;" :style="{'bottom':filterHeight2}">
										<view>
											重置
											<button form-type='reset' @tap.stop="cz">重置</button>
										</view>
										<view style="background: #0084FF;">
											确定
											<button form-type='submit' @tap.stop="que">确定</button>
										</view>
									</view>
									
								</view>
								<view @tap="hidel" :style="{'height':filterHeight3,'background':'rgba(0,0,0,0.5)'}">
									
								</view>
							</scroll-view>
							
					<!-- 	</form> -->
						
						
						</view>
					</view>
				  	<!-- 筛选 start -->
				  	<view class='grace-filter-options'   :style="{'width':'100%', 'height' : filterHeight, 'padding':'0','background':'rgba(0,0,0,0.5)'}" v-if="showingIndex3 == 99">
				  		<!-- <form @submit='formsubmit' @reset='formReset'> -->
				  			<scroll-view scroll-y="true" style="background: #fff;">
				  				<view style="width:100%; padding:15upx 0;display: block;position: relative;overflow: scroll;" :style="{'height':filterHeight1}">
				  					<view class="grace-h5 grace-blod" style="box-sizing: border-box;padding: 15upx;">福利待遇</view>
				  					<view style="box-sizing: border-box;padding: 15upx;" class="grace-select-tips">
				  						<checkbox-group name="where1" @change.stop="changeFunc">
				  							<label v-for="(item, index) in where1Tips" :key="index" :class="[item.checked ? 'grace-checked' : '']">
				  								<checkbox :value="item.value + ''" :checked="item.checked"></checkbox> {{item.name}}
				  							</label>
				  						</checkbox-group>
				  					</view>
				  					<view class="grace-h5 grace-blod" style="box-sizing: border-box;padding: 15upx;">退役军人优先</view>
				  					<view style="box-sizing: border-box;padding: 15upx;" class="grace-select-tips">
				  						<radio-group name="where2" @change.stop="changeFunc2">
				  							<label v-for="(item, index) in where2Tips" :key="index" :class="[item.checked ? 'grace-checked' : '']">
				  								<radio :value="item.value + ''" :checked="item.checked"></radio> {{item.name}}
				  							</label>
				  						</radio-group>
				  					</view>
				  					<!-- 占位视图组件 -->
				  					<view style="height:150upx;"></view>
									<view class='grace-filter-buttons1' style="border-top: 1px solid #EEEEEE;position: fixed;left: 0;right: 0;height: auto;" :style="{'bottom':filterHeight2}">
										<view>
											重置
											<button form-type='reset' @tap.stop="cz">重置</button>
										</view>
										<view style="background: #0084FF;"> 
											确定
											<button form-type='submit' @tap.stop="que">确定</button>
										</view>
									</view>
				  				</view>
								<view @tap="hidel" :style="{'height':filterHeight3,'background':'rgba(0,0,0,0.5)'}">
									
								</view>
				  			</scroll-view>
				  			<!-- 按钮  -->
				  			
				  <!-- 		</form> -->
				  	</view>
				  	<!-- 筛选 end -->
				  </view>
				  
		</view>
		  
		  
		<!-- 列表 -->
		<view class="topic-detail-list" style="margin-top: 430upx;">
			
			<block v-for="(item,index) in tablist" :key="index">
				<template v-if="tabIndex==index">
					<!-- 列表 -->
					<block v-if="tabIndex==0"  v-for="(list,listindex) in item.list" :key="listindex">
					<!-- <block v-if="tabIndex==0"  v-for="(list,listindex) in 10" :key="listindex"> -->
						<common-list :item="list" :index="listindex"></common-list>
					</block>
					<!-- 上拉加载 -->
					<load-more  :loadtext="item.loadtext" v-if="show"></load-more>
				</template>
			</block>
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
		
		<view class="cu-modal" :class="modalName1=='Modal'?'show':''" style="z-index: 9999999999;">
			<view class="cu-dialog" style="width:70%;">
				<view class="cu-bar bg-white justify-end" style="text-align: left;background: #fff;color: #333;border-bottom: 1px solid #eee;">
					<view class="content" >登录/注册</view>
					
				</view>
				<view style="text-align: left;background: #fff;padding: 10upx 40upx;">
					
					<button   open-type="getPhoneNumber" @getphonenumber="getPhoneNumber" data-target="Modal1" class="cu-btn bg-red margin-tb-sm lg" style="width: 80%;background: #0084FF;margin: 15px auto;display: block;line-height: 44px;">微信账户快速登录</button>
					<button  @tap="wechat1" data-target="Modal1" class="cu-btn bg-red margin-tb-sm lg" style="width: 80%;background: #F4F4F4;margin: 15px auto;display: block;line-height: 44px;color: #333;">手机号注册/登录</button>
				</view>
			</view>
		</view>
			
		</view>
		
	
	
</template>

<script>
	var graceMd5 = require("../../css/md5.js");
	 var WXBizDataCrypt = require('../../css/WXBizDataCrypt.js'); 
	import commonList from "../../components/common/common-list.vue";
	import loadMore from "../../components/common/load-more.vue";
	var _self;
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
				login_code : '',  
				sta:'',
				modalName: null,
				modalName1:null,
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
				openid:'',
				
				hangye:[
					
					
				],
				hangindex:0,
				//for
				forData : [1,2,3,4,5,6,7,8,9,10],
				//被展示的菜单
				showingIndex : 0,
				showingIndex1 : 0,
				showingIndex2 : 0,
				showingIndex3 : 0,
				//第一个选项相关
				orderIndex : 0,
				orderList : ['综合','新品','评价'],
				//第二个选项相关
				cateIndex: 0,
				cateList: ['汽车', '新闻', '热点', '电影'],
				//价格排序
				priceOrder : 1,
				//筛选条件
				where1Tips:[
					
					
				],
				where2Tips: [
					{ name: "不限", value: 0, checked: true },
					{ name: "退役军人优先", value: 1 , checked:false},
				],
				where2Tips1: [
					{ name: "全部", value: 1 , checked: true },
					{ name: "3000元以下", value: 2 , checked: false },
					{ name: "3000~5000元", value: 3 , checked: false },
					{ name: "5000~8000元", value: 4 , checked: false },
					{ name: "8000~10000元", value: 5 , checked: false},
					{ name: "10000元以上", value: 6 , checked: false}
				],
				
				where2Tips2: [
					{ name: "不限", value: 1 , checked: true },
					{ name: "初中及以下", value: 2 , checked: false },
					{ name: "高中", value: 3 , checked: false },
					{ name: "专科", value: 4 , checked: false },
					{ name: "本科", value: 5 , checked: false},
					{ name: "硕士", value: 6 , checked: false},
					{ name: "博士", value: 7 , checked: false}
				],
				
				where2Tips3: [
					{ name: "不限", value: 1 , checked: true },
					{ name: "无经验", value: 2 , checked: false },
					{ name: "1~3年", value: 3 , checked: false },
					{ name: "3~5年", value: 4 , checked: false },
					{ name: "5~10年", value: 5 , checked: false},
					{ name: "10年以上", value: 6 , checked: false}
				],
				
				
				where2Tips1ax: [
					{ name: "全部", value: 1 , checked: true },
					{ name: "3000元以下", value: 2 , checked: false },
					{ name: "3000~5000元", value: 3 , checked: false },
					{ name: "5000~8000元", value: 4 , checked: false },
					{ name: "8000~10000元", value: 5 , checked: false},
					{ name: "10000元以上", value: 6 , checked: false}
				],
				
				where2Tips2ax: [
					{ name: "不限", value: 1 , checked: true },
					{ name: "初中及以下", value: 2 , checked: false },
					{ name: "高中", value: 3 , checked: false },
					{ name: "专科", value: 4 , checked: false },
					{ name: "本科", value: 5 , checked: false},
					{ name: "硕士", value: 6 , checked: false},
					{ name: "博士", value: 7 , checked: false}
				],
				
				where2Tips3ax: [
					{ name: "不限", value: 1 , checked: true },
					{ name: "无经验", value: 2 , checked: false },
					{ name: "1~3年", value: 3 , checked: false },
					{ name: "3~5年", value: 4 , checked: false },
					{ name: "5~10年", value: 5 , checked: false},
					{ name: "10年以上", value: 6 , checked: false}
				],
				//
				filterHeight : '100%',
				filterHeight1 : '100%',
				filterHeight2 : '100%',
				filterHeight3 : '100%',
				positionCateId:0,
				salary:'不限',
				labelIds:'',
				education:'不限',
				workYear:'不限',
				isSoldierPriority:0,
				tags:[],
				InputBottom: 0
			};
		},
		onReady:function(){
			_self = this;
			uni.getSystemInfo({
				success: function (res) {
					var windowHeight = res.windowHeight;
					//获取头部标题高度
					uni.createSelectorQuery().select('#grace-filter-header').fields(
						{
							size: true,
						}, function (res) {
							if(!res){return ;}
							var sHeight = (windowHeight -uni.upx2px(340)-res.height);
							var sHeight1 = (windowHeight -uni.upx2px(500)-res.height);
							var sHeight2 = sHeight-sHeight1;
							var sHeight3=sHeight-sHeight1;
							
							console.log(windowHeight)
							console.log(res.height)
							_self.filterHeight = sHeight + 'px';
							_self.filterHeight1 = sHeight1 + 'px';
							_self.filterHeight2 = sHeight2 + 'px';
							_self.filterHeight3= sHeight3 + 'px';
						}
					).exec();
				}
			});
		},
		onLoad:function(){
			//判断是否登录
			// var loginRes = this.checkLogin('../dongtai/dongtai', '2');
			// if(!loginRes){return false;}
			var _this=this;
			 
			  _this.login1();
			 setTimeout(function(){
				
				 // _this.showModal();
			 },500)
			    
			 uni.login({    
			     success: function(res) {    
			 
			         // 获取code    
			         console.log(JSON.stringify(res));  
			         //{"errMsg":"login:ok","code":"071JIp1t1pv马赛克t1Ran1t1JIp1l"}  
			 
			         _this.login_code = res.code;  
			 		
			     }  
			 }); 
			  uni.showShareMenu();
			 
		},
		// 监听下拉刷新
		// onPullDownRefresh() {
		// 	this.getdata();
		// },
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
			// this.getdata();
			this.getinfo();
		},
		methods:{
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
			InputFocus(e) {
				
				this.InputBottom = e.detail.height
				this.showingIndex=0;
				this.showingIndex1=0;
				this.showingIndex2=0;
				this.showingIndex3=0;
			},
			hidel:function(){
				this.showingIndex=0;
				this.showingIndex1=0;
				this.showingIndex2=0;
				this.showingIndex3=0;
			},
			InputBlur(e) {
					console.log(321)
				this.InputBottom = 0
			},
			go1:function(){
				uni.navigateTo({
					url: '../../pagesA/phone/phone',
					
				});
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
							_this.getinfo(res.data.data.access_token);
							// _this.hangye1();
							_this.tag();
						}
						// if(status=='ok'){
						// 	//未注册手机号
						// 	if(data.phone==0){
						// 		_this.showModal();
						// 		_this.openid=data.openid;
						// 	}else{
						// 		//已注册手机号
						// 		uni.setStorageSync('user_id',data.u_id);	
						// 	}
						// 	
						// }
						
					}
				});
			},
			//获取行业筛选类
			tag:function(e){
				var _this=this;
				// uni.showLoading({title:"加载中..."});
				
				
				uni.request({
					url: _this.apiServer + '/api/v1.LabelManagement/getAllLabels',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						
						accessToken:uni.getStorageSync('utoken')
					},
					success:function(res){
						console.log(res);
						
						// uni.hideLoading();
						if(res.data.errorCode == '0'){
							
							var data=res.data.data.list;
							console.log(data);
							for (var i=0;i<data.length;i++) {
								
									var arr={
										name : data[i].name , 
										value : i+1 , 
										checked:false,
										id : data[i].id , 
									}
								
								
								
								_this.where1Tips.push(arr);
								_this.tags.push(arr);
							}
							
							
							
						}else{
							uni.showToast({title:res.data.data, icon:"none"});
						}
					}
				});
				
				
				
			},
			//获取行业筛选类
			hangye1:function(e){
				var _this=this;
				// uni.showLoading({title:"加载中..."});
				
				
				uni.request({
					url: _this.apiServer + '/api/v1.positionCate/getCateByGroup',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						
						accessToken:uni.getStorageSync('utoken')
					},
					success:function(res){
						console.log(res);
						
						// uni.hideLoading();
						if(res.data.errorCode == '0'){
							
							var data=res.data.data;
								console.log(11112222);
							console.log(data);
							for (var i=0;i<data.length;i++) {
								if(i==0){
									var arr={
										name : data[i].name , 
										value : i , 
										checked:true,
										list:[],
									}
								}else{
									var arr={
										name : data[i].name , 
										value : i , 
										checked:false,
										list:[],
									}
								}
								var son=data[i].son;
								for (var j = 0; j <son.length; j++) {
									
										var arr1={
											name : son[j].name , 
											value : j , 
											checked:false,
											id:son[j].id,
										}
									
									arr.list.push(arr1)
								}
								
								_this.hangye.push(arr);
							}
							
							
							
						}else{
							uni.showToast({title:res.data.data, icon:"none"});
						}
					}
				});
				
				
				
			},
			//授权登录
			login:function(e){
				var _this=this;
				uni.login({
					success:function(res){
						console.log(res);
						
						uni.request({
							url: _this.apiServer + '/api/v1.User/codeToSession',
							method: 'POST',
							header: {'content-type' : "application/x-www-form-urlencoded"},
							data:{
								code   : res.code,
								accessToken:e
								
							},
							success: res => {
								console.log(res);
								var status=res.data.errorCode;
								var data=res.data.data;
								if(status=='0'){
									//未注册手机号
									if(data.phone==0){
										_this.modalName1 = 'Modal'
										_this.openid=data.openid;
										
									}else{
										//已注册手机号
									
										uni.setStorageSync('uid',res.data.data.uid);
										uni.setStorageSync('token',res.data.data.id_token);
										uni.setStorageSync('phone',res.data.data.phone);
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
							url: _this.apiServer + '/api/v1.User/number',
							// url:'http://47.103.59.100:9091/web/#/page/173',
							method: 'POST',
							header: {'content-type' : "application/x-www-form-urlencoded"},
							data:{
								'appid':'wx02c9a76ab01f424c',
								'secret':'22f25c64080e8a640d93d104cbc2a3ea',
								'jsCode':JSCODE,
								'encryptedData':encryptedData,
								'iv':iv,
								accessToken:uni.getStorageSync('utoken')	
							},
							success: res => {
								console.log(res);
								if(res.data.errorCode==0){
									_this.phone=res.data.data.phoneNumber;
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
						url: _this.apiServer + '/api/v1.User/sendSms',
						method: 'POST',
						header: {'content-type' : "application/x-www-form-urlencoded"},
						data:{
							phone   : _this.phone,
							accessToken: uni.getStorageSync('utoken')
							
						},
						success:function(res){
							console.log(res);
							if(res.data.errorCode == '0'){
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
					url: _this.apiServer + '/api/v1.User/bindMiniOpenIdWithPhone',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						phone   : _this.phone,
						miniOpenId:_this.openid,
						accessToken: uni.getStorageSync('utoken')
					},
					success:function(res){
						console.log(res);
						 
						 if(res.data.errorCode == 0){
				
								uni.setStorageSync('uid',res.data.data.uid);
								uni.setStorageSync('token',res.data.data.id_token);
								uni.setStorageSync('phone',res.data.data.phone);
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
					url: _this.apiServer + '/api/v1.User/login',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						phone   : _this.phone,
						vCode: _this.code,
						miniOpenId:_this.openid,
						accessToken: uni.getStorageSync('utoken')
					},
					success:function(res){
						console.log(res);
						 
						 if(res.data.errorCode == 0){
				
								uni.setStorageSync('uid',res.data.data.uid);
								uni.setStorageSync('token',res.data.data.id_token);
								uni.setStorageSync('phone',res.data.data.phone);
								uni.showToast({title:'绑定成功',icon:'none'});
								// uni.setStorageSync('user_id' , res.data.data);	
								_this.modalName = null
							}else{
								
								uni.showToast({title:res.data.msg,icon:'none'});
							}
							
						
					}
				});
				
			},
			
			//获取数据
			
			getinfo:function(e){
				var _this=this;
				uni.showLoading({title:"加载中..."});
				page=1;
				
				uni.request({
					url: _this.apiServer + '/api/v1.PositionManagement/filter',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						positionCateId:_this.positionCateId,
						salary:_this.salary,
						labelIds:_this.labelIds,
						education:_this.education,
						workYear:_this.workYear,
						isSoldierPriority:_this.isSoldierPriority,
						// 'page':page
						 pageIndex:page,
						pageSize:10,
						accessToken:uni.getStorageSync('utoken')
					},
					success:function(res){
						console.log(res);
							uni.stopPullDownRefresh();
						page++;
						uni.hideLoading();
						if(res.data.errorCode == '0'){
							
							var data=res.data.data.page;
							console.log(data);
							_this.tablist[_this.tabIndex].list=[];
							console.log(data.length);
							for (var i=0;i<data.length;i++) {
								// _this.sta=data[0].id;
								
								_this.tablist[_this.tabIndex].list.push(data[i]);	
							}
							
							if(data.length<10){
								_this.tablist[_this.tabIndex].loadtext="已加载全部"; 
							}else{
								_this.tablist[_this.tabIndex].loadtext="上拉加载更多"; 
							}
							
							if(data.length>5){
								_this.show=true;
							}else{
								_this.show=false;
							}
							
							
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
						uni.stopPullDownRefresh();
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
									
									url: _self.apiServer + '/api/v1.PositionManagement/filter',
									method: 'POST',
									header: {'content-type' : "application/x-www-form-urlencoded"},
									data:{
										positionCateId:_self.positionCateId,
										salary:_self.salary,
										labelIds:_self.labelIds,
										education:_self.education,
										workYear:_self.workYear,
										isSoldierPriority:_self.isSoldierPriority,
										pageIndex:page,
										pageSize:10,
										accessToken:uni.getStorageSync('utoken')
									},
									
									success: function (res) {
									// _self.product =res.data;
								// var res=res.data;
									_self.tablist[_self.tabIndex].loadtext = '';
									console.log(res.data.data.page);
									 if(res.data.data.page.length == 0||res.data.data.page.length=='undefined'){
										 uni.showToast({title:"已经到底了", icon:"none"});
										uni.hideNavigationBarLoading();
										_self.tablist[_self.tabIndex].loadtext= '已加载全部';
										return false;
									 }
									 
									 page++;
									
									 //concat 将两个数组拼接起来
									 // 获取数据
									 setTimeout(()=> {
										 // if(res.data.errorCode == '0'){
										 // 	
										 // 	var data=res.data.data.page.data;
										 // 	console.log(data);
										 // 	for (var i=0;i<data.length;i++) {
										 // 		// _this.sta=data[0].id;
										 // 		
										 // 		_this.tablist[_this.tabIndex].list.push(data[i]);	
										 // 	}
											
											
											
											
										 // _this.sta=res.data[0].id;
										 _self.tablist[_self.tabIndex].list= _self.tablist[_self.tabIndex].list.concat(res.data.data.page);
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
			
			
			
			
			//筛选
			changeOrder:function(e){
				var tapIndex = e.target.dataset.itemid;
				this.orderIndex = tapIndex;
				this.showingIndex = 0;
				this.getList();
			},
			showOptions1:function(){
				if(this.showingIndex != 0){
					this.showingIndex = 0;
					
				}else{
					this.showingIndex = 1;
					this.showingIndex1 = 0;
					this.showingIndex2 = 0;
					this.showingIndex3 = 0;
				} 
				},
			showOptions2: function(){if(this.showingIndex1 != 0){this.showingIndex1 = 0; return ;} this.showingIndex1 = 2;this.showingIndex = 0;this.showingIndex2 = 0;this.showingIndex3 = 0;},
			showOptions3: function(){if(this.showingIndex2 != 0){this.showingIndex2 = 0; return ;} this.showingIndex2 = 3;this.showingIndex1 = 0;this.showingIndex = 0;this.showingIndex3 = 0;},
			showOptions99 : function(){if(this.showingIndex3 != 0){this.showingIndex3 = 0; return ;} this.showingIndex3 = 99;this.showingIndex1 = 0;this.showingIndex2 = 0;this.showingIndex = 0;},
			changeCate: function (e) {
				var tapIndex = e.target.dataset.itemid;
				this.cateIndex = tapIndex;
				this.showingIndex = 0;
				this.showingIndex1 = 0;
				this.showingIndex2 = 0;
				this.showingIndex3 = 0;
				this.getList();
			},
			//价格排序
			changePriceOrder : function(){
				if(this.priceOrder == 1){
					this.priceOrder = 2;
				}else{
					this.priceOrder = 1;
				}
				uni.showModal({
					title: '价格排序已经切换',
					content: '对应的值保存在 priceOrder 变量中 ^_^'
				});
				this.getList();
			},
			//提交条件
			formsubmit : function(e){
				console.log(JSON.stringify(e.detail.value));
				uni.showModal({
					title: '请观察控制台',
					content: '条件以表单形式提交 ^_^'
				});
				_self.showingIndex = 0;
				this.getList();
			},
			//重置表单
			// formReset : function(){
			// 	for (var i = 0; i < _self.where1Tips.length; i++){
			// 		_self.where1Tips[i].checked= false;
			// 	}
			// 	_self.where1Tips = _self.where1Tips;
			// 	for (var i = 0; i < _self.where2Tips.length; i++) {
			// 		_self.where2Tips[i].checked = false;
			// 	}
			// 	_self.where2Tips = _self.where2Tips;
			// 	_self.showingIndex = 0;
			// 	this.getList();
			// },
			//筛选页面js
			changeFunc: function (e) {
				var checkVal = e.detail.value;
				console.log(checkVal);
				var currentVal = this.where1Tips;
				
				// for (var j = 0; j < checkVal.length; j++) {
				// 	if(checkVal[j]==0){
				// 		console.log(111111);
				// 		for (var i = 0; i < currentVal.length; i++) {
				// 			if (i==0) {
				// 				currentVal[i].checked = true;
				// 			} else {
				// 				currentVal[i].checked = false;
				// 			}
				// 		}
				// 	}else{
				// 		console.log(222);
				// 		
				// 	}
				// }
				var a='';
				for (var i = 0; i < currentVal.length; i++) {
					if (checkVal.indexOf(currentVal[i].value + '') != -1) {
						currentVal[i].checked = true;
						a=a+currentVal[i].name+',';
					} else {
						currentVal[i].checked = false;
					}
				}
				this.labelIds = a;
				this.where1Tips = currentVal;
			},
			changeFunc2: function (e) {
				var checkVal = e.detail.value;
				for (var i = 0; i < this.where2Tips.length; i++) {
					if (checkVal.indexOf(this.where2Tips[i].value + '') != -1) {
						this.where2Tips[i].checked = true;
						if(this.where2Tips[i].value==1){
							this.isSoldierPriority=1;
						}
					} else {
						this.where2Tips[i].checked = false;
					}
				}
				this.where2Tips = this.where2Tips;
			},
			changeFunc21: function (e) {
				var checkVal = e.detail.value;
				for (var i = 0; i < this.where2Tips1.length; i++) {
					if (checkVal.indexOf(this.where2Tips1[i].value + '') != -1) {
						this.where2Tips1[i].checked = true;
						if(this.where2Tips1[i].value!=1){
							this.salary=this.where2Tips1[i].name;
						}else{
							this.salary='不限';
						}
					} else {
						this.where2Tips1[i].checked = false;
					}
				}
				this.where2Tips1 = this.where2Tips1;
			},
			
			changeFunc22: function (e) {
				var checkVal = e.detail.value;
				for (var i = 0; i < this.where2Tips2.length; i++) {
					if (checkVal.indexOf(this.where2Tips2[i].value + '') != -1) {
						this.where2Tips2[i].checked = true;
						if(this.where2Tips2[i].value!=1){
							this.education=this.where2Tips2[i].name;
						}else{
							this.education='不限';
						}
					} else {
						this.where2Tips2[i].checked = false;
					}
				}
				this.where2Tips2 = this.where2Tips2;
			},
			
			changeFunc23: function (e) {
				var checkVal = e.detail.value;
				for (var i = 0; i < this.where2Tips3.length; i++) {
					if (checkVal.indexOf(this.where2Tips3[i].value + '') != -1) {
						this.where2Tips3[i].checked = true;
						if(this.where2Tips3[i].value!=1){
							this.workYear=this.where2Tips3[i].name;
						}else{
							this.workYear='不限';
						}
					} else {
						this.where2Tips3[i].checked = false;
					}
				}
				this.where2Tips3 = this.where2Tips3;
			},
			
			changeFunc25: function (e) {
				var checkVal = e.detail.value;
				console.log(checkVal)
				this.positionCateId=e.detail.value;
				var hangindex=this.hangindex;
				for (var j = 0; j < this.hangye.length; j++) {
					if(hangindex==j){
						for (var i = 0; i < this.hangye[j].list.length; i++) {
							if (checkVal.indexOf(this.hangye[j].list[i].id + '') != -1) {
								this.hangye[j].list[i].checked = true;
							} else {
								this.hangye[j].list[i].checked = false;
							}
						}
					}else{
						for (var i = 0; i < this.hangye[j].list.length; i++) {
							
								this.hangye[j].list[i].checked = false;
							
						}
					}
					
				}
				
				
				this.search();
				this.hangye = this.hangye;
			},
			chnn:function(e){
				console.log(e);
				
				for (var i = 0; i < this.hangye.length; i++) {
					if (i==e) {
						this.hangye[i].checked = true;
					} else {
						this.hangye[i].checked = false;
					}
				}
				this.hangye = this.hangye;
				this.hangindex = e;
			},
			//条件更新后执行统一函数（如重新读取数据等）
			getList : function(){
				console.log('条件更新后执行统一函数（如重新读取数据等）');
			},
			//重置
			cz:function(){
				console.log('执行');
				this.where2Tips1=[
					{ name: "全部", value: 1 , checked: true },
					{ name: "3000元以下", value: 2 , checked: false },
					{ name: "3000~5000元", value: 3 , checked: false },
					{ name: "5000~8000元", value: 4 , checked: false },
					{ name: "8000~10000元", value: 5 , checked: false},
					{ name: "10000元以上", value: 6 , checked: false}
				];
				
				
				this.where2Tips2=[
					{ name: "不限", value: 1 , checked: true },
					{ name: "初中及以下", value: 2 , checked: false },
					{ name: "高中", value: 3 , checked: false },
					{ name: "专科", value: 4 , checked: false },
					{ name: "本科", value: 5 , checked: false},
					{ name: "硕士", value: 6 , checked: false},
					{ name: "博士", value: 7 , checked: false}
				];
				
				this.where2Tips3= [
					{ name: "不限", value: 1 , checked: true },
					{ name: "无经验", value: 2 , checked: false },
					{ name: "1~3年", value: 3 , checked: false },
					{ name: "3~5年", value: 4 , checked: false },
					{ name: "5~10年", value: 5 , checked: false},
					{ name: "10年以上", value: 6 , checked: false}
				];
				this.where2Tips= [
					{ name: "不限", value: 0, checked: true },
					{ name: "退役军人优先", value: 1 , checked:false},
				];
					
					this.where1Tips= this.tags;
					this.labelIds='';
					this.isSoldierPriority=0;
					this.education='';
					this.salary='';
					this.workYear='';
					this.positionCateId=0;
					
					
				this.showingIndex=0;
				this.showingIndex1=0;
				this.showingIndex2=0;
				this.showingIndex3=0;
				this.getinfo();
				
			},
			
			
			//确定
			que:function(){
				this.search();
			},
			search:function(){
				this.showingIndex=0;
				this.showingIndex1=0;
				this.showingIndex2=0;
				this.showingIndex3=0;
				// var _this=this;
				this.getinfo();
				// uni.showLoading({title:"加载中..."});
				// 
				// 
				// uni.request({
				// 	url: _this.apiServer + '/api/v1.PositionManagement/filter',
				// 	method: 'POST',
				// 	header: {'content-type' : "application/x-www-form-urlencoded"},
				// 	data:{
				// 		positionCateId:_this.positionCateId,
				// 		salary:_this.salary,
				// 		labelIds:_this.labelIds,
				// 		education:_this.education,
				// 		workYear:_this.workYear,
				// 		isSoldierPriority:_this.isSoldierPriority,
				// 		accessToken:uni.getStorageSync('utoken')
				// 	},
				// 	success:function(res){
				// 		console.log(res);
				// 		
				// 		uni.hideLoading();
				// 		if(res.data.errorCode == '0'){
				// 			
				// 			var data=res.data.data.page;
				// 			console.log(res.data.data);
				// 			_this.tablist[_this.tabIndex].list=[];
				// 			
				// 			if(data.length>5){
				// 				_this.show=true;
				// 				console.log('长度大于5');
				// 			}else{
				// 				_this.show=false;
				// 				console.log('长度小于5');
				// 			}
				// 			
				// 			for (var i=0;i<data.length;i++) {
				// 				// _this.sta=data[0].id;
				// 				
				// 				_this.tablist[_this.tabIndex].list.push(data[i]);	
				// 			}
				// 			
				// 			
				// 				_this.tablist[_this.tabIndex].loadtext="已加载全部"; 
				// 			
				// 			
				// 			
				// 		}else{
				// 			uni.showToast({title:res.data.data, icon:"none"});
				// 		}
				// 	}
				// });
				// 
				
				
				
				
			}
			
			
			
			
		}
	}
</script>

<style>
	
	page {
  background-color: #FFFFFF;
  height: 100%;
  font-size: 11px;
  line-height: 1.8;
  overflow: scroll;
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
	.grace-blod{
		color: #333333;font-size: 30upx;font-weight: 400;
	}
	.grace-filter-buttons{
		border-top: 1px solid #eee;
	}
	.grace-filter-buttons1{display:flex; width:100%; flex-wrap:nowrap;height:90upx; background:#FFF;box-sizing: border-box;}
	.grace-filter-buttons1 view{width:50%; height:100upx; line-height:100upx; text-align:center; position:relative;}
	.grace-filter-buttons1 view:last-child{background:#FF0000; color:#FFF;}
	.grace-filter-buttons1 view button{opacity:0; width:100%; position:absolute; z-index:9; left:0; top:0; height:90upx;}
	.xx{background: #fff;}
	.items{flex: 1 !important;}
</style>

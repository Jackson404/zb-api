<template>
	<view style="background: #fff;" v-if="show">
		
		
		
		
			<view style="padding: 20upx 0;">
				<view class="uni-list-cell" @tap="showModal" data-target="Modal">
					
					<view class="uni-list-cell-navigate uni-navigate-right" style="display: flex;flex-wrap: nowrap;justify-content: flex-start;width: 100%;height: 30px;line-height: 30px;" >
						<!-- <text style="color: #333;font-size: 40upx;">姓名</text> -->
						<view style="color: #333;font-size: 36upx;">{{item.name}}</view>
						<!-- <image v-if="age=='男'" src="../../static/img/boy-01@2x.png"  style="height: 10px;width: 10px;margin-left: 10px;"></image>
						<image v-else="" src="../../static/img/girl-01@2x.png"  style="height: 13px;width: 8px;margin-left: 10px;"></image> -->
						<image v-if="item.isSoldierPriority==1"  src="../../static/img/6.png"   style="margin-left: 20px;width: 160upx;height: 35upx;"></image>
						
					</view>
					
				</view>
					
						
				<view style="width: 100%;display: flex;flex-wrap: nowrap;align-content: center;height: 60upx;line-height: 60upx;padding: 10upx 30upx;box-sizing: border-box;">
					<!-- <view class="title">工作经验. 年龄</view> -->
					<view class="input1" v-if="item.gender==1">男</view>
					<view class="input1" v-if="item.gender==2">女</view>
					<view class="input1" >{{item.age}}岁</view>
					<view class="input1" >{{item.workYear}}年</view>
					<view class="input1" >{{item.education}}</view>
					
				</view>
				<view class="cu-form-group">
					<view class="title" style="width: 50%;display: flex;align-items: center;"><image src="../../static/img/resume-phone@2x.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 1px;"></image>{{item.phone}}</view>
					
				</view>
			</view>
			
			<view style="height: 10px;background:#FAFAFA;"></view>
			
			<view style="padding: 20upx 0;">
				<view class="uni-list-cell"  @tap="showModal2" data-target="Modal1">
					<view class="uni-list-cell-navigate uni-navigate-right" style="display: flex;flex-wrap: nowrap;justify-content: flex-start;" >
						<text style="color: #333;font-size: 35upx;">求职信息</text>
						
					</view>
				</view>
				<view class="cu-form-group">
					<view class="title"><image src="../../static/img/9.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>期望职位</view>
					<view class="input" style="text-align: left;">{{item.exPosition}}</view>
				</view>
				<view class="cu-form-group">
					<view class="title"><image src="../../static/img/8.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>工作性质</view>
					<view class="input" style="text-align: left;">{{item.nature}}</view>
				</view>
				<view class="cu-form-group">
					<view class="title"><image src="../../static/img/4.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>期望城市</view>
					<view class="input" style="text-align: left;">{{exCity}}</view>
				</view>
				<view class="cu-form-group">
					<view class="title"><image src="../../static/img/10.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>期望薪资</view>
					<view class="input" style="text-align: left;">{{item.salary}}</view>
				</view>
				<view class="cu-form-group">
					<view class="title"><image src="../../static/img/5.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>目前状况</view>
					<view class="input" style="text-align: left;">{{item.curStatus}}</view>
				</view>
				<view class="cu-form-group">
					<view class="title"><image src="../../static/img/123.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>到岗时间</view>
					<view class="input" style="text-align: left;">{{item.arrivalTime}}</view>
				</view>
			</view>
			<view style="height: 10px;background:#FAFAFA;"></view>
			
			<view style="padding: 20upx 0;">
				<view class="uni-list-cell"  @tap="showModal1" data-target="Modal1">
					<view class="uni-list-cell-navigate uni-navigate-right" style="display: flex;flex-wrap: nowrap;justify-content: flex-start;" >
						<text style="color: #333;font-size: 35upx;">简历信息</text>
						
					</view>
				</view>
				
				<view class="cu-form-group">
					<view class="title"  style="width: 50%;"><image src="../../static/img/resume-experience@2x.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>优势/技能</view>
					
				</view>
				<view class="cu-form-group">
						<view class="input" style="text-align: left;font-size: 30upx;">{{item.skills}}</view>
					
				</view>
				
				<view class="cu-form-group">
					<view class="title" style="width: 50%;"><image src="../../static/img/resume-evaluate@2x.png"  style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>自我评价</view>
					
				</view>
				<view class="cu-form-group">
					<view class="input" style="text-align: left;font-size: 30upx;">{{item.selfEvaluation}}</view>
					
				</view>
				
				
			</view>
		
			
			
			<!-- <view style="height: 10px;background:#FAFAFA;"></view>
			
			<view class="uni-list-cell" @tap="showModal2" data-target="Modal2">
				<view class="uni-list-cell-navigate uni-navigate-right" style="display: flex;flex-wrap: nowrap;justify-content: flex-start;" >
					<text style="color: #333;font-size: 35upx;">选填信息</text>
					
				</view>
			</view>
			<view class="cu-form-group">
				<view class="title"><image src="../../static/img/resume-time@2x.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>入伍时间: </view>
				<view class="input" style="text-align: left;">{{rw}}</view>
			</view>
			<view class="cu-form-group">
				<view class="title"><image src="../../static/img/resume-undergo@2x.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>服役时长: </view>
				<view class="input" style="text-align: left;">{{rw_ns}}</view>
			</view>
			<view class="cu-form-group">
				<view class="title"><image src="../../static/img/resume-classify@2x.png" style=" height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>兵种: </view>
				<view class="input" style="text-align: left;">{{bz}}</view>
			</view> -->
			
						<view style="height: 50px;"></view>
		
			<view class="cu-dialog">
				<view class="cu-bar bg-white justify-end" style="text-align: left;background: #211989;color: #fff;">
					<view class="content" >退伍信息(选填)</view>
					<view class="action" @tap="hideModal2">
						<text class="icon-close text-red" style="color: #fff;"></text>
					</view>
				</view>
				<view class="padding-xl" style="text-align: left;background: #fff;">
					
					
					
					<view class="cu-form-group margin-top">
						<view class="title">入伍年份</view>
						<picker @change="PickerChange4" :value="index4" :range="picker4" style="z-index: 9999999;padding-right: 0px;">
							<view class="picker" style="padding-right: 0px;text-align: center;">
								{{index4>-1?picker4[index4]:'请选择入伍年份'}}
							</view>
						</picker>
					</view>
					<!-- <view class="cu-form-group margin-top">
						<view class="title">服役时长</view>
						<picker @change="PickerChange5" :value="index5" :range="picker5">
							<view class="picker">
								{{index5>-1?picker5[index5]:'请选择服役时长'}}
							</view>
						</picker>
					</view> -->
					
					<view class="cu-form-group margin-top" style="display: flex;">
						<view class="title">服役时长</view>
						<input class="xxx" placeholder="请输入服役时长" style="border: 1px solid #EDEDED;line-height: 23px;padding: 0 5px;box-sizing: border-box;text-align: center;height: 35;"  v-model="rw_ns" type="number"></input>
					</view>
					
					<radio-group class="cu-form-group margin-top block" @change="RadioChange1" style="flex-wrap: wrap;">
						<view class="title" style="width: 38%;">兵种</view>
						<view style="flex: 1;display: flex;flex-wrap: wrap;">
							<view style="width: 50%;margin-bottom: 10px;">
								<radio :class="radio1=='海军'?'checked':''" :checked="radio1=='海军'?true:false" value="海军"></radio>
								<text>海军</text>
							</view>
							<view style="width: 50%;margin-bottom: 10px;">
							<radio :class="radio1=='陆军'?'checked':''" :checked="radio1=='陆军'?true:false" value="陆军"></radio>
							<text>陆军</text>
							</view>
							<view style="width: 50%;margin-bottom: 10px;">
							<radio :class="radio1=='空军'?'checked':''" :checked="radio1=='空军'?true:false" value="空军"></radio>
							<text>空军</text>
							</view>
							<view style="width: 50%;margin-bottom: 10px;">
							<radio :class="radio1=='其他'?'checked':''" :checked="radio1=='其他'?true:false" value="其他"></radio>
							<text>其他</text>
							</view>
						</view>
					</radio-group>
					
					
					
					
					<button @tap="add3" data-target="Modal3" class="cu-btn bg-red margin-tb-sm lg" style="width: 70%;background: #211989;margin: 30px auto;display: block;line-height: 44px;">提交</button>
					
				</view>
			</view>
		</view>
		
	</view>
	
	<view v-else style="background: #fff;padding: 50px 0;box-sizing: border-box;height: 100vh;">
		
		<view style="width: 100%;box-sizing: border-box;padding: 200upx 20% 70upx 20%;">
			<image src="../../static/xiao/empty.png" mode="" style="width: 350upx;height: 350upx;display: block;margin: 0 auto;"></image>
		</view>
		
		<button @tap="add4" data-target="Modal2"  class="cu-btn bg-red margin-tb-sm lg" style="width: 40%;background: #0084FF;margin: 30px auto;display: block;font-size: 35upx;height: 40px;line-height: 40px;">+ 添加简历</button>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				'item':[],
				'status':'',
				'age':'',
				'name':'',
				'year':'',
				'year1':'',
				'work':'',
				'work1':'',
				'education':'',
				'money':'',
				'evaluate':'',
				'skill':'',
				'evaluate1':'',
				'skill1':'',
				'rw':'',
				'rw_ns':'',
				'bz':'',
				exCity:'',
				CustomBar: this.CustomBar,
				modalName: null,
				modalName1: null,
				modalName2: null,
				radio: '男',
				radio1: '海军',
				picker: ['50岁','49岁', '48岁', '47岁','46岁', '45岁', '44岁','43岁', '42岁', '41岁','40岁', '39岁', '38岁','37岁', '36岁', '35岁','34岁', '33岁', '32岁', '31岁','30岁', '29岁', '28岁','27岁', '26岁', '25岁','24岁', '23岁', '22岁', '21岁','20岁', '19岁', '18岁','17岁', '16岁'],
				index: -1,
				index1: -1,
				picker1: ['1年','2年', '3年', '4年','5年', '6年', '7年','8年', '9年', '10年','11年', '12年', '13年','14年', '15年', '16年','17年', '18年', '19年', '20年'],
				username:'',
				picker2: ['硕士','博士', '本科', '专科','高中', '初中'],
				index2: -1,
				index3: -1,
				picker3: ['3千元以下','3千~5千', '5千~8千', '8千~1万','1万元以上'],
				
				picker4: ['2019年','2018年', '2017年', '2016年','2015年', '2014年', '2013年','2012年', '2011年', '2010年','2009年', '2008年', '2007年','2006年', '2005年', '2004年','2003年', '2002年', '2001年', '2000年','1999年', '1998年', '1997年','1996年', '1995年', '1994年','1993年', '1992年', '1991年', '1990年'],
				index4: -1,
				index5: -1,
				picker5: ['1年','2年', '3年', '4年','5年', '6年', '7年','8年', '9年', '10年','11年', '12年', '13年','14年', '15年', '16年','17年', '18年', '19年', '20年'],
				
				jineng:'',
				pingjia:'',
				phone:'',
				phone1:'',
				uid:'',
				show:''
				
			};
		},
		onLoad:function(){
			// this.getinfo();
		},
		onShow:function(){
			 this.getinfo();
		},
		methods: {
			getinfo:function(){
				console.log(11111);
				uni.showLoading({'title':"加载中..."});
				var _this =this;
				uni.request({
					url:_this.apiServer + '/api/v1.Resume/getResumeByUserId',
					method:'POST',
					data:{
						"id_token":uni.getStorageSync('token'),
						'accessToken': uni.getStorageSync('utoken')
					},
					header:{'content-type':'application/x-www-form-urlencoded'},
					success:function(res) {
						
					 console.log(res);
						var data = res.data.data.list;
							
						var status = res.data.errorCode;
						console.log(status);
							uni.hideLoading();
						if(status=='0'){
							console.log(data);
								
								if(data==null){
									
								}else{
									_this.show=true;
									_this.item=data;
									
									var d=data.exCity.split(",");
									 _this.exCity=d[0]+'-'+d[1]+'-'+d[2];
									
									
									_this.status=data.status;
									
									_this.uid=data.id;
									_this.index2=_this.arr(_this.picker2,data.education);
									_this.index3=_this.arr(_this.picker3,data.money);
									_this.pingjia=_this.evaluate;
									_this.jineng=_this.skill;
									
									_this.index4=_this.arr(_this.picker4,data.rw);
									// _this.rw_ns=_this.arr(_this.picker5,data.rw_ns);
									_this.radio1=_this.bz;
								}
								
								
							
						}else{
							
							_this.show=false;	
							
						}
						
					}
				})
				
				
				
			},
			add4:function(){
				uni.navigateTo({
						
						url: '../../pagesA/jianli/jianliadd'
					});
			},
			
			arr:function(arr,d){
					var array=arr;
					var v=d;
					var j=0;
					for(var i=0;i<array.length;i++){
					if(array[i]==v){
					   j=i;
					   return j;
					}
				
}
			},
			showModal(e) {
					var _this=this;
					
						var arr=JSON.stringify(_this.item);
					
							uni.navigateTo({
								url: 'updatajian?con='+arr,
								
							});
				},
		showModal2(){
				var _this=this;
							var arr=JSON.stringify(_this.item);
							
							uni.navigateTo({
								url: 'updatajian2?con='+arr
								
							});	
						
				
						
				},
						hideModal(e) {
							this.modalName = null
						},
						showModal1(e) {
									var _this=this;
									var arr=JSON.stringify(_this.item);
									uni.navigateTo({
										url: 'updatajian1?con='+arr
										
									});	
						},
						add1(e){
							var _this=this;
							if(this.username==''){
		
								alert('姓名不能为空');
								return;
							}
							if(this.phone==''){
							
												alert('联系方式不能为空');
												return;
											}
							this.modalName = null;
							
							uni.showLoading({'title':"加载中..."});
							
							uni.request({
								url:this.websiteUrl+"index/index1",
								method:'POST',
								data:{
									'token':'api2018',
									'name':_this.username,
									'age':_this.radio,
									'year':_this.year1,
									'work':_this.work1,
									'phone':_this.phone,
									'id':_this.uid
									
								},
								header:{'content-type':'application/x-www-form-urlencoded'},
								success:function(res) {
									
									 
									var data = res.data.data;
									var status = res.data.status;
									console.log(2222);
									console.log(res);
										uni.hideLoading();
									if(status=='ok'){
									
// 										uni.showToast({
// 												title: '更新成功',
// 												duration: 1000
// 											});
											uni.showModal({
												title: "提示",
												content: "更新成功",
												showCancel: false,
												confirmText: "确定"
											})
										_this.name=_this.username;
										_this.age=_this.radio;
										_this.year=_this.year1;
										_this.work=_this.work1;
										_this.phone1=_this.phone;
										
									}
									
								}
							})
							
							
							
						},
						add2(e){
			
			
							
							var _this=this;
							
							this.modalName1 = null;
							
							uni.showLoading({'title':"加载中..."});
							
							uni.request({
								url:this.websiteUrl+"index/index2",
								method:'POST',
								data:{
									'token':'api2018',
									'education':_this.picker2[_this.index2],
									'money':_this.picker3[_this.index3],
									'skill':_this.jineng,
									'evaluate':_this.pingjia,
									'id':_this.uid
									
								},
								header:{'content-type':'application/x-www-form-urlencoded'},
								success:function(res) {
									
									 
									var data = res.data.data;
									var status = res.data.status;
									console.log(2222);
										uni.hideLoading();
									console.log(res);
									if(status=='ok'){
									
// 										uni.showToast({
// 												title: '更新成功',
// 												duration: 1000
// 											});
											uni.showModal({
												title: "提示",
												content: "更新成功",
												showCancel: false,
												confirmText: "确定"
											})
										_this.education=_this.picker2[_this.index2];
										_this.money=_this.picker3[_this.index3];
										_this.skill=_this.jineng;
										_this.evaluate=_this.pingjia;
																		
									}
									
								}
							})
						},
						add3(e){
							console.log(e);
							this.modalName2 = null;
							var _this=this;
							uni.showLoading({'title':"加载中..."});
							
							 
							 if(_this.index4!='-1'){
									var rw=_this.picker4[_this.index4];
									var rw_ns=_this.rw_ns;
									var bz=_this.radio1;
									var status=2;	
							 }else{
								 var rw=-1;
								 var rw_ns=-1;
								 var bz=-1;
								 var status=1;
							 }
							 
							 
							 console.log(bz);
							 
							uni.request({
								url:this.websiteUrl+"index/index3",
								method:'POST',
								data:{
									'token':'api2018',
									'id':_this.uid,
									'rw':rw,
									'rw_ns':rw_ns,
									'bz':bz
								},
								header:{'content-type':'application/x-www-form-urlencoded'},
								success:function(res) {
									
									 
									var data = res.data.data;
									var status = res.data.status;
									uni.hideLoading();
									if(status=='ok'){
										
// 										uni.showToast({
// 												title: '更新成功',
// 												duration: 1000
// 											});
										
									uni.showModal({
										title: "提示",
										content: "更新成功",
										showCancel: false,
										confirmText: "确定"
									})
										_this.rw=_this.picker4[_this.index4];
										_this.rw_ns=_this.rw_ns;
										_this.bz=_this.radio1;
										_this.status=2;
									}
									
								}
							})
							
							
							
							
							
							
						},
						hideModal1(e) {
							this.modalName1 = null
						},
						
						hideModal2(e) {
							this.modalName2 = null
						},
						RadioChange(e) {
							console.log(e);
							this.radio = e.detail.value
						},
						RadioChange1(e) {
							console.log(e);
							this.radio1 = e.detail.value
						},
						ChooseCheckbox(e) {
							var items = this.checkbox,
								values = e.currentTarget.dataset.value;
							for (var i = 0, lenI = items.length; i < lenI; ++i) {
								if (items[i].value == values) {
									items[i].checked = !items[i].checked;
									break
								}
							}
						},
						PickerChange(e) {
							console.log(e);
							this.index = e.detail.value
						},
						PickerChange1(e) {
							console.log(e);
							this.index1 = e.detail.value
						},
						PickerChange2(e) {
							console.log(e);
							this.index2 = e.detail.value
						},
						PickerChange3(e) {
							console.log(e);
							this.index3 = e.detail.value
						},
						PickerChange4(e) {
							console.log(e);
							this.index4 = e.detail.value
						},
						PickerChange5(e) {
							console.log(e);
							this.index5 = e.detail.value
						}
		}
	}
</script>

<style>
	page{
		height: 100%;
		width: 100%;
		background: #fff;
	}
	.cu-form-group .title {
		min-width: calc(4em + 15px);
	}

.cu-form-group{
	border: none !important;
}
	.title{color: #666;}

	
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
	.cu-form-group .title {
	  text-align: justify;
	  font-size: 30rpx;
	  position: relative;
	  height: 60rpx;
	  line-height: 60rpx;
		width: 30%;
	}
	.cu-dialog{
		display: none;
	}
	.input1{
		font-size: 28upx;
		color: #555;
		padding-right: 25upx;
	}
</style>

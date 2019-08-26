<template>
	<view class="body" style="min-height: 100vh;">
		
		<view class="title">
			<view class="name" style="display: flex;flex-wrap: nowrap;align-items: center;">
				<text class="text" style="font-size: 36upx;max-width: 70%;display: block;margin-right: 20upx;color: #333333;">{{item.name}}</text> <image v-if="item.t_jr==2"  src="https://www.zhengbu121.com/statics/img/1.png" mode="widthFix" style="width: 30upx;height: 30upx;display: inline-block;" lazy-load="true"></image>
				
			</view>
			<view style="display: flex;height: 30px;line-height: 30px;">
			<!-- 	<view class="tag" v-if="item.t_jr==2">退役军人优先</view>
				<view class="money" v-if="item.t_jr==2" style="margin-left: 20px;color: #0084FF;font-size: 28upx;">{{item.t_xz}}</view> -->
				<view class="money" style="color: #0084FF;font-size: 30upx;">{{item.pay}}元</view>
			</view>
		</view>
		
		
		<view class="title1" style="padding: 10upx 0 20upx 0;color: #333333;font-size: 28upx;">
			{{item.companyName}}		
		</view>

		<view class="title1 u-f-ac" style="padding: 0 0 24upx 0;color: #363636;">
			<view class="u-list u-f-ac">
				<image src="../../static/xiao/experience.png" mode="" style="height: 28upx;width: 26upx;display: block;"></image>
				<view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;" v-if="item.workExp==0"> 不限</view>
				<view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;" v-else> {{item.workExp}}</view>
			</view>
			<view class="u-list u-f-ac">
				<image src="../../static/xiao/education.png" mode="" style="height: 28upx;width: 32upx;display: block;"></image>
				<view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;">  {{item.education}}</view>
			</view>
			<view class="u-list u-f-ac">
				<image src="../../static/xiao/experience.png" mode="" style="height: 28upx;width: 26upx;display: block;"></image>
				<view style="font-size: 26upx;color: #333333;padding: 0 40upx 0 20upx;"> {{item.age}}</view>
			</view>
			
		</view>
		
		<view class="tagx u-f-ac" >
			<view class="tagone" v-for="(items, index) in item.labelIds" :key="index">{{items}}</view>
			
		</view>
		<view style="height: 20px;"></view>
		<view class="address" style="display: flex;padding: 20px 0;">
			<!-- <text style="background: url(../../static/xiao/location.png);background-size: 100% 100%;width: 14px;height: 18px;display: inline-block;position: relative;margin-right: 10px;"></text> -->
			<image src="../../static/xiao/location.png" mode="" style="width: 14px;height: 18px;display: block;margin-right: 10px;"></image>
			<view style="line-height: 40upx;word-wrap: break-word;padding: 0px 0px 0 5px;flex: 1;font-size: 26upx;color: #666666;">
				{{item.address}}	
			</view>
		</view>
		<view style="height: 15px;"></view>
		<view class="des u-f-ac">
			<image src="../../static/xiao/prove.png" mode="" style="width: 40upx;height: 37upx;"></image>
			<view style="padding-left: 40upx;font-size: 36upx;color: #333;">职位详情</view>
		</view>
		<view class="content" style="padding: 0px 0;">
			<!-- <view style="line-height: 30px;color: #9B9B9B;font-size: 30upx;padding: 5px;word-break: break-word;">{{item.t_yq}}</view> -->
			<rich-text :nodes="item.positionRequirement" style="line-height: 30px;color: #666666;font-size: 14px !important;word-break: break-word;"></rich-text>
		</view>
		
		
		<view style="height: 50px;"></view>
		
			<button @tap="add" data-target="Modal" class="cu-btn bg-red margin-tb-sm lg" style="width: 90%;background: #0084FF;position: fixed;bottom: 8px;left: 5%;">立刻投递</button>
		
	
	
	
	
	
		</view>
</template>

<script>
	export default {
		data() {
			return {
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
				id:'',
				title:'',
				soldier:'',
				
				money:'',
				wk_year:'',
				education:'',
				experience:'',
				address:'',
				des:'',
				tag:[],
				tag1:'',
				tag2:'',
				tag3:'',
				work:'',
				rw_ns:'',
				year:'',
				item:[]
				
				
			};
		},
		onLoad:function(e){
			var _this =this;
			_this.id=e.id;
			console.log(e);
			 _this.getInfo();
		},
		methods: {
			getInfo:function(){
				var _this=this;
			
				uni.showLoading({'title':"加载中..."});
				uni.request({
					url:_this.apiServer +"/api/v1.PositionManagement/getDetail",
					method:'POST',
					data:{
						
						'positionId':_this.id,
						accessToken: uni.getStorageSync('utoken')
						
					},
					header:{'content-type':'application/x-www-form-urlencoded'},
					success:function(res) {
						
						 
						var data = res.data.data;
						var status = res.data.status;
						console.log(data);
						
							_this.item=data.detail;
							
							
							
							uni.hideLoading();
							
							
							
					
						
					}
				})
			},
			add:function(){
				//先获取有没有简历
				var _this=this;
				
				uni.showLoading({'title':"加载中..."});
				uni.request({
					url:_this.apiServer +"/api/v1.Resume/getResumeByUserId",
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
							uni.hideLoading()
						if(status=='0'){
							
							console.log(data.id);
							//投递简历
							_this.send(data.id);
							
						}else{
							
							uni.showModal({
							title: '提示',
							content: '请您先添加简历，然后在进行职位投递',
							success: function (res) {
								if (res.confirm) {
									//填写简历
									uni.navigateTo({
										url: '../../pagesA/jianli/jianliadd'
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
			
			showModal(e) {
				console.log(e);
				this.modalName = 'Modal';
			},
			send:function(e){
				var _this=this;
				// var openid =uni.getStorageSync('openid');
				console.log(_this.id);
				uni.showLoading({'title':"提交中..."});
				uni.request({
					url:_this.apiServer+"/api/v1.Resume/applyPosition",
					method:'POST',
					data:{
						'id_token':uni.getStorageSync('token'),
						'positionId':_this.id,
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
			hideModal(e) {
				this.modalName = null
			},
			showModal1(e) {
				console.log(e);
				this.modalName1 = e.currentTarget.dataset.target
			},
			showModal11(e){
				
				if(this.username==''){
// 					uni.showToast({
// 						title: '姓名不能为空',
// 						icon: "none"
// 					})
					alert('姓名不能为空');
					return;
				}
				if(this.phone==''){
				// 					uni.showToast({
				// 						title: '姓名不能为空',
				// 						icon: "none"
				// 					})
									alert('联系方式不能为空');
									return;
								}	


				this.modalName = null;
				this.modalName1 = e.currentTarget.dataset.target;
			},
			showModal12(e){
				

				this.modalName1 = null;
				this.modalName2 = e.currentTarget.dataset.target;
			},
			showModal13(e){
				console.log(e);
				this.modalName2 = null;
				var _this=this;
				uni.showLoading({'title':"加载中..."});
				
				 
				 if(_this.index4!='-1'){
						var rw=_this.picker4[_this.index4];
						var rw_ns=_this.rw_ns;
						var bz=_this.radio1;	
				 }else{
					 var rw=-1;
					 var rw_ns=-1;
					 var bz=-1;
				 }
				 
				 
				 
				uni.request({
					url:this.websiteUrl+"index/index",
					method:'POST',
					data:{
						'token':'api2018',
						'name':_this.username,
						'age':_this.radio,
						'year':_this.year,
						'work':_this.work,
						'education':_this.picker2[_this.index2],
						'money':_this.picker3[_this.index3],
						'skill':_this.jineng,
						'evaluate':_this.pingjia,
						'phone':_this.phone,
						'rw':rw,
						'rw_ns':rw_ns,
						'bz':bz
					},
					header:{'content-type':'application/x-www-form-urlencoded'},
					success:function(res) {
						
						 
						var data = res.data.data;
						var status = res.data.status;
						
						if(status=='ok'){
							uni.hideLoading();
							
							uni.showModal({
								title: "提示",
								content: "简历添加成交，请投递",
								showCancel: false,
								confirmText: "确定"
							})
						}
						
					}
				})
				
				
				
				
				
				
			},
			hideModal1(e) {
				this.modalName1 = null
			},
			showModal2(e) {
				console.log(e);
				this.modalName2 = e.currentTarget.dataset.target
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

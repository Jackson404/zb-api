<template>
	<view>
		
		<view>
			
			<view class="cu-form-group">
				<view class="title">期望职位</view>
				<view class="wrap" @tap="showModal" data-target="DialogModal1">
					<view class="con">
						{{exPosition}}
					</view>
				</view>
			</view>
			<view class="cu-form-group">
				<view class="title">工作性质</view>
				<picker @change="PickerChange1" :value="index1" :range="picker1">
					<view class="picker">
						{{index1>-1?picker1[index1]:'请选择工作性质'}}
					</view>
				</picker>
			</view>
			<view class="cu-form-group">
				<view class="title">期望城市</view>
				<picker mode="region" @change="RegionChange" :value="region">
					<view class="picker">
						{{region[0]}}，{{region[1]}}，{{region[2]}}
					</view>
				</picker>
			</view>
			<view class="cu-form-group">
				<view class="title">期望薪资</view>
				<input placeholder="请填写期望薪资" name="input" v-model="salary" type="number" style="text-align: right;"></input>
			</view>
			<view class="cu-form-group">
				<view class="title">目前状况</view>
				<picker @change="PickerChange3" :value="index3" :range="picker3">
					<view class="picker">
						{{index3>-1?picker3[index3]:'请选择目前状况'}}
					</view>
				</picker>
			</view>
			<view class="cu-form-group">
				<view class="title">到岗时间</view>
				<picker @change="PickerChange4" :value="index4" :range="picker4">
					<view class="picker">
						{{index4>-1?picker4[index4]:'请选择到岗时间'}}
					</view>
				</picker>
			</view>
			
			
			
			
			<view style="width: 100%;padding-top: 150upx;">
			<button @tap="add4" class="cu-btn bg-red margin-tb-sm lg" style="width: 55%;background: #0084FF;margin: 30px auto;display: block;font-size: 35upx;height: 40px;line-height: 40px;">下一步</button>	
			</view>
			
			
			<view class="cu-modal" :class="modalName=='DialogModal1'?'show':''">
				<view class="cu-dialog">
					<view class="cu-bar bg-white justify-end" style="border-bottom: 1px solid #EEEEEE;">
						<view class="content">期望职位</view>
						
					</view>
					<view class="padding-xl" style="height: 60vh;padding: 0;">
						<view style="width: 100%;display: flex;flex-wrap: nowrap;background: #fff;height: 60vh;">
							<!-- <view style="width: 25%;box-sizing: border-box;background: #F6F6F6;height: 100%;overflow: scroll;">
								<view @tap="chnn(index)" style="height: 100upx;line-height: 100upx;text-align: center;color: #666;font-size: 28upx;" :class="item.checked ?'xx':''" v-for="(item,index) in hangye" :key="index">
									{{item.name}}
								</view>
							</view> -->
							<view style="width: 25%;box-sizing: border-box;background: #F6F6F6;height: 100%;overflow: scroll;">
								<view @tap.stop="chnn(index)" style="min-height: 100upx;line-height: 60upx;text-align: center;color: #666;font-size: 28upx;box-sizing: border-box;padding: 20upx 0;;" :class="item.checked ?'xx':''" v-for="(item,index) in hangye" :key="index">
									{{item.name}}
								</view>
							</view>
							<view style="width:75%;box-sizing: border-box;padding: 20upx;height: 100%;overflow: scroll;">
								<view style='padding:20upx 10upx' class="grace-select-tips">
									<radio-group name="where2" @change="changeFunc25">
										<label v-for="(item, index1) in hangye[hangindex].list" :key="index1" :class="[item.checked ? 'grace-checked' : '']">
											<radio :value="item.id + ''" :checked="item.checked"></radio> {{item.name}}
										</label>
									</radio-group>
								</view>
							</view>
						</view>
					</view>
					<view class="cu-bar bg-white justify-end">
						<view class="action" style="width: 100%;display: flex;flex-wrap: nowrap;">
							<button class="cu-btn line-green text-green" style="flex: 1;" @tap="hideModal">取消</button>
							<button class="cu-btn bg-green margin-left" style="flex: 1;" @tap="hideModal">确定</button>
			
						</view>
					</view>
				</view>
			</view>
			
			
			
			
			
			
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				index: -1,
				picker: ['2019年','2018年', '2017年', '2016年','2015年', '2014年', '2013年','2012年', '2011年', '2010年','2009年', '2008年', '2007年','2006年', '2005年', '2004年','2003年', '2002年', '2001年', '2000年','1999年', '1998年', '1997年','1996年', '1995年', '1994年','1993年', '1992年', '1991年', '1990年'],
				index1: -1,
				picker1: ['全职','兼职'],
				exPosition:'请选择期望职位',
				index2: -1,
				picker2: ['全职','兼职'],
				index3: -1,
				picker3: ['离职','在职'],
				index4: -1,
				picker4: ['立即','一周内','面谈'],
				multiIndex: [0, 0, 0],
				time: '12:01',
				date: '2018-12-25',
				con:[],
				salary:'',
				id:'',
				modalName: null,
				region: ['广东省','广州市','海珠区'],
				hangye:[
					{
						name:'',
						value:0,
						checked:true,
						list:{
							
						}
					}
				],
				hangindex:0,
				item:[],
				region1:'广东省,广州市,海珠区',
				con1:[],
			};
		},
		onLoad:function(e){
			
			this.con1=JSON.parse(e.con);
			console.log(this.con1);
			this.hangye1();
		},
		methods: {
			RegionChange(e) {
				console.log(e);
				var dd=e.detail.value;
				this.region = e.detail.value;
				this.region1=dd[0]+','+dd[1]+','+dd[2];
			},
			//获取行业筛选类
			hangye1:function(e){
				var _this=this;
				// uni.showLoading({title:"加载中..."});
				
				
				uni.request({
					url: _this.apiServer1 + '/mini/job_description_categories/list',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						
						// accessToken:uni.getStorageSync('utoken')
					},
					success:function(res){
						console.log(res);
						
						// uni.hideLoading();
						if(res.data.error_code == '0'){
							_this.hangye=[];
							var data=res.data.job_description_categories;
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
								this.exPosition=this.hangye[j].list[i].name;
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
				
				
				
				this.hangye = this.hangye;
			},
			
			
			showModal(e) {
				this.modalName = e.currentTarget.dataset.target
			},
			hideModal(e) {
				this.modalName = null
			},
			
			PickerChange(e) {
				this.index = e.detail.value
			},
			PickerChange1(e) {
				this.index1 = e.detail.value
			},
			PickerChange2(e) {
				this.index2 = e.detail.value
			},
			PickerChange3(e) {
				this.index3 = e.detail.value
			},
			PickerChange4(e) {
				this.index4 = e.detail.value
			},
			DateChange(e) {
				this.date = e.detail.value
			},
			add4:function(){
				
				
				if(this.exPosition == '请选择期望职位'){uni.showToast({title:'请选择期望职位', icon:"none"}); return ;}
				if(this.index1 == -1){uni.showToast({title:'请选择期望工作性质', icon:"none"}); return ;}
				
				if(this.region1 < 1){uni.showToast({title:'请选择期望城市', icon:"none"}); return ;}
				
				if(this.salary< 1){uni.showToast({title:'请输入期望薪资', icon:"none"}); return ;}	
				
				if(this.index3 == -1){uni.showToast({title:'请选择目前状态', icon:"none"}); return ;}
				if(this.index4 == -1){uni.showToast({title:'请选择到岗时间', icon:"none"}); return ;}
				
				
				var arr={
					"name":this.con1.name,
					"phone":this.con1.phone,
					"gender":this.con1.gender,
					"age":this.con1.age,
					"workYear":this.con1.workYear,
					"education":this.con1.education,
					"isSoldierPriority":this.con1.isSoldierPriority,
					"exPosition":this.exPosition,
					"salary":this.salary,
					"nature":this.picker1[this.index1],
					"exCity":this.region1,
					"curStatus":this.picker3[this.index3],
					"arrivalTime":this.picker4[this.index4],
				}
				
				
				
				uni.navigateTo({
						
						url: 'resume2?con='+JSON.stringify(arr)
					});
			}
		}
	}
</script>

<style>
	
	
	page{
		width: 100%;
		height: 100%;
		background: #fff;
	}
	.cu-form-group .title {
		min-width: calc(4em + 15px);
	}
</style>

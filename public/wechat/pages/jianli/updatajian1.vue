<template>
	<view>
		
		<view>
			
			
			<!-- <view class="cu-form-group">
				<view class="title">学历</view>
				<picker @change="PickerChange" :value="index" :range="picker">
					<view class="picker">
						{{index>-1?picker[index]:'请选择最高学历'}}
					</view>
				</picker>
			</view>
			
			<view class="cu-form-group">
				<view class="title">期望薪资</view>
				<picker @change="PickerChange1" :value="index1" :range="picker1">
					<view class="picker">
						{{index1>-1?picker1[index1]:'请选择期望薪资'}}
					</view>
				</picker>
			</view> -->
		
			<view class="cu-form-group" style="display: block;">
				<view class="title" style="padding-top: 20upx;">优势/技能</view>
				<textarea v-model="skill" style="background: #F6F6F6;box-sizing: border-box;padding: 15upx;height: 220upx;" maxlength="-1" :disabled="modalName!=null" @input="textareaAInput" placeholder="请填写您拥有的技能"></textarea>
			</view>
			
			<view class="cu-form-group" style="display: block;">
				<view class="title" style="padding-top: 20upx;">自我评价</view>
				<textarea v-model="evaluate" style="background: #F6F6F6;box-sizing: border-box;padding: 15upx;height: 220upx;"  maxlength="-1" :disabled="modalName!=null" @input="textareaAInput" placeholder="请填写自我评价"></textarea>
			</view>
			
			<view style="width: 100%;padding-top: 50upx;">
			<button @tap="add4" class="cu-btn bg-red margin-tb-sm lg" style="width: 55%;background: #0084FF;margin: 30px auto;display: block;font-size: 35upx;height: 40px;line-height: 40px;">提交</button>	
			</view>
			
			<!-- !!!!! placeholder 在ios表现有偏移 建议使用 第一种样式 -->
			
			
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				index: -1,
				index1: -1,
				picker: ['硕士','博士', '本科', '专科','高中', '初中'],
				picker1: ['3千元以下','3千~5千', '5千~8千', '8千~1万','1万元以上'],
				evaluate:'',
				skill:'',
				
				time: '12:01',
				date: '2018-12-25',
				region: ['广东省', '广州市', '海珠区'],
				switchA: false,
				switchB: true,
				switchC: false,
				switchD: false,
				radio: 'A',
				checkbox: [{
					value: 'A',
					checked: true
				}, {
					value: 'B',
					checked: true
				}, {
					value: 'C',
					checked: false
				}],
				imgList: [],
				modalName: null,
				textareaAValue: '',
				textareaBValue: '',
				con:[],
				item:[]
			};
		},
		onLoad:function(e){
			var item=JSON.parse(e.con);
			this.item=item;
			console.log(item);
			this.evaluate =item.selfEvaluation;
			this.skill =item.skills;
			
			
			this.id =item.id;
			
		},
		methods: {
			PickerChange(e) {
				this.index = e.detail.value
			},
			PickerChange1(e) {
				this.index1 = e.detail.value
			},
			
			textareaAInput(e) {
				this.textareaAValue = e.detail.value
			},
			textareaBInput(e) {
				this.textareaBValue = e.detail.value
			},
			add4:function(){
				
				if(this.skill < 1){uni.showToast({title:'请填写您拥有的技能', icon:"none"}); return ;}
				if(this.evaluate < 1){uni.showToast({title:'请填写自我评价', icon:"none"}); return ;}
				
				var _self=this;
				uni.showLoading({title:"正在提交"});
				console.log(_self.imglist);
				uni.request({
					url: _self.apiServer + '/api/v1.Resume/edit',
					method: 'POST',
					header: {'content-type' : "application/x-www-form-urlencoded"},
					data:{
						"selfEvaluation":_self.evaluate,
						"skills":_self.skill,
						"resumeId":parseInt(_self.id),
						
					
						"name":_self.item.name,
						
						"phone":parseInt(_self.item.phone),
						"gender":parseInt(_self.item.gender),
						"age":parseInt(_self.item.age),
						"workYear":parseInt(_self.item.workYear),
						"education":_self.item.education,
						"militaryTime":_self.item.militaryTime,
						"attendedTime":_self.item.attendedTime,
						"corps":_self.item.corps,
						"exPosition":_self.item.exPosition,
						"nature":_self.item.nature,
						"exCity":_self.item.exCity,
						"salary":_self.item.salary,
						"curStatus":_self.item.curStatus,
						"arrivalTime":_self.item.arrivalTime,
						"isSoldierPriority":_self.item.isSoldierPriority,
						
						accessToken:uni.getStorageSync('utoken'),
						"id_token":uni.getStorageSync('token'),
						
						
					},
					success:function(res){
						console.log(res);
						if(res.data.errorCode == '0'){
							uni.showToast({title:"更新成功", icon:"none"});
							_self.imglist = [];
							
							setTimeout(function(){
								uni.switchTab({
									url:"jianli"
								})
							}, 1000);
						}else{
							uni.showToast({title:res.data.msg, icon:"none"});
						}
					}
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

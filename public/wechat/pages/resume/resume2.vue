<template>
	<view>
		
		<view>
			
			<view class="cu-form-group" style="display: block;">
				<view class="title" style="padding-top: 20upx;">优势/技能</view>
				<textarea v-model="skill" style="background: #F6F6F6;box-sizing: border-box;padding: 15upx;height: 220upx;" maxlength="-1"  @input="textareaAInput" placeholder="请填写您拥有的技能"></textarea>
			</view>
			
			<view class="cu-form-group" style="display: block;">
				<view class="title" style="padding-top: 20upx;">自我评价</view>
				<textarea v-model="evaluate" style="background: #F6F6F6;box-sizing: border-box;padding: 15upx;height: 220upx;"  maxlength="-1"  @input="textareaAInput1" placeholder="请填写自我评价"></textarea>
			</view>
			
			
			
			
			
			<view style="width: 100%;padding-top: 150upx;">
			<button @tap="add4" class="cu-btn bg-red margin-tb-sm lg" style="width: 55%;background: #0084FF;margin: 30px auto;display: block;font-size: 35upx;height: 40px;line-height: 40px;">提交</button>	
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
				picker1: ['陆军','海军','空军','其他'],
				multiIndex: [0, 0, 0],
				time: '12:01',
				date: '2018-12-25',
				con:[],
				rw_ns:'',
				evaluate:'',
				skill:'',
			};
		},
		onLoad:function(e){
			this.con=JSON.parse(e.con);
			console.log(e)
		},
		methods: {
			textareaAInput(e) {
				this.skill = e.detail.value
			},
			textareaAInput1(e) {
				this.evaluate = e.detail.value
			},
			PickerChange(e) {
				this.index = e.detail.value
			},
			PickerChange1(e) {
				this.index1 = e.detail.value
			},
			DateChange(e) {
				this.date = e.detail.value
			},
			add4:function(){
				var _self=this;
				
				
				
				
				uni.showLoading({title:"正在提交"});
					
					uni.request({
						url: _self.apiServer1 + '/mini/resumes/create',
						method: 'POST',
						header: {'content-type' : "application/x-www-form-urlencoded"},
						data:{
							"self_assessment":_self.evaluate,
							"skills":_self.skill,
												
												
							"name":_self.con.name,
							
							"mobile":_self.con.phone,
							"gender":_self.con.gender,
							"age":_self.con.age,
							"working_years":_self.con.workYear,
							"education":_self.con.education,
							
							"militaryTime":_self.con.militaryTime,
							"attendedTime":_self.con.attendedTime,
							"corps":_self.con.corps,
							"job_objective":_self.con.exPosition,
							"work_nature":_self.con.nature,
							"expected_city":_self.con.exCity,
							"expected_salary":_self.con.salary,
							"current_working_status":_self.con.curStatus,
							"available_date":_self.con.arrivalTime,
							"is_veteran":_self.con.isSoldierPriority,
							
							"sid":uni.getStorageSync('uid'),
							
						},
						success:function(res){
							console.log(res);
								if(res.data.error_code == '0'){
								uni.showToast({title:"创建简历成功", icon:"none"});
								
								
								setTimeout(function(){
									uni.navigateBack({
										delta: 3
									});
								}, 1000);
							}else{
								uni.showToast({title:res.data.error_reason, icon:"none"});
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
	input{
		text-align: right;
	}
</style>

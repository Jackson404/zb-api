<template>
	<view>
		<view>
			<view class="cu-form-group" style="border-bottom: 1px solid #eee !important;">
				<view class="title">姓名</view>
				<input placeholder="请输入真实姓名" v-model="name" name="input"></input>
			</view>
			<view class="cu-form-group" >
				<view class="title">性别</view>
				<picker @change="PickerChange" :value="index" :range="picker">
					<view class="picker">
						{{index>-1?picker[index]:'请选择性别'}}
					</view>
				</picker>
			</view>
			<view class="cu-form-group">
				<view class="title">年龄</view>
				<picker @change="PickerChange1" :value="index1" :range="picker1">
					<view class="picker">
						{{index1>-1?picker1[index1]:'请选择年龄'}}
					</view>
				</picker>
			</view>
			<view class="cu-form-group">
				<view class="title">工作年限</view>
				<input placeholder="请填写工作年限" v-model="work" name="input" type="number"></input>
			</view>
			<view class="cu-form-group">
				<view class="title">学历</view>
				<picker @change="PickerChange3" :value="index3" :range="picker3">
					<view class="picker">
						{{index3>-1?picker3[index3]:'请选择最高学历'}}
					</view>
				</picker>
			</view>
			<view class="cu-form-group">
				<view class="title">联系电话</view>
				<input placeholder="请输入联系电话" v-model="phone" name="input" type="number" maxlength="11"></input>
			</view>
			<view class="cu-form-group">
				<view class="title">是否退役军人</view>
				<picker @change="PickerChange2" :value="index2" :range="picker2">
					<view class="picker">
						{{index2>-1?picker2[index2]:'请选择是否退役军人'}}
					</view>
				</picker>
			</view>
			<view style="width: 100%;padding-top: 150upx;">
			<button @tap="add4" class="cu-btn bg-red margin-tb-sm lg" style="width: 55%;background: #0084FF;margin: 30px auto;display: block;font-size: 35upx;height: 40px;line-height: 40px;">下一步</button>	
			</view>	
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				index: -1,
				picker: ['男', '女'],
				
				index1: -1,
				picker1: ['50','49', '48', '47','46', '45', '44','43', '42', '41','40', '39', '38','37', '36', '35','34', '33', '32', '31','30', '29', '28','27', '26', '25','24', '23', '22', '21','20', '19', '18','17', '16'],
				
				picker2: ['否','是'],
				index2: -1,
				picker3: ['硕士','博士', '本科', '专科','高中', '初中'],
				index3: -1,
				multiIndex: [0, 0, 0],
				time: '12:01',
				date: '2018-12-25',
				name:'',
				work:'',
				phone:'',
				id:'',
				item:[]
				
			};
		},
		methods: {
			PickerChange(e) {
				this.index = e.detail.value
				console.log(this.index);
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
			DateChange(e) {
				this.date = e.detail.value
			},
			add4:function(){
				
					if(this.name < 1){uni.showToast({title:'请输入姓名', icon:"none"}); return ;}
					if(this.work < 1){uni.showToast({title:'请输入工作年限', icon:"none"}); return ;}
					if(this.phone.length!=11){uni.showToast({title:'请输入联系电话', icon:"none"}); return ;}	
					if(this.index == -1){uni.showToast({title:'请选择性别', icon:"none"}); return ;}
					if(this.index1 == -1){uni.showToast({title:'请选择年龄', icon:"none"}); return ;}
					if(this.index3 == -1){uni.showToast({title:'请选择学历', icon:"none"}); return ;}
					if(this.index2 == -1){uni.showToast({title:'请选择是否军人', icon:"none"}); return ;}
					var arr={
						"name":this.name,
						"phone":this.phone,
						"gender":parseInt(this.index)+1,
						"age":parseInt(this.picker1[this.index1]),
						"workYear":parseInt(this.work),
						"education":this.picker3[this.index3],
						"isSoldierPriority":this.index2,
					}
					
					
					
				
				
				
				uni.navigateTo({
						
						url: 'resume1?con='+JSON.stringify(arr)
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
	.cu-form-group+.cu-form-group{
		border-top: none;
		border-bottom: 1px solid #eee !important;
	}
</style>

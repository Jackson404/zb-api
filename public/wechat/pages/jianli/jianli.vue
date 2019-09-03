<template>
	<view style="background: #fff;" v-if="show">




		<view style="padding: 20upx 0;">
			<view class="uni-list-cell" @tap="showModal" data-target="Modal">

				<view class="uni-list-cell-navigate uni-navigate-right" style="display: flex;flex-wrap: nowrap;justify-content: flex-start;width: 100%;height: 30px;line-height: 30px;">
					<!-- <text style="color: #333;font-size: 40upx;">姓名</text> -->
					<view style="color: #333;font-size: 36upx;">{{item.name}}</view>
					<!-- <image v-if="age=='男'" src="../../static/img/boy-01@2x.png"  style="height: 10px;width: 10px;margin-left: 10px;"></image>
						<image v-else="" src="../../static/img/girl-01@2x.png"  style="height: 13px;width: 8px;margin-left: 10px;"></image> -->
					<image v-if="item.isSoldierPriority==1" src="../../static/img/6.png" style="margin-left: 20px;width: 160upx;height: 35upx;"></image>

				</view>

			</view>


			<view style="width: 100%;display: flex;flex-wrap: nowrap;align-content: center;height: 60upx;line-height: 60upx;padding: 10upx 30upx;box-sizing: border-box;">
				<!-- <view class="title">工作经验. 年龄</view> -->
				<view class="input1" v-if="item.gender==1">男</view>
				<view class="input1" v-if="item.gender==2">女</view>
				<view class="input1">{{item.age}}岁</view>
				<view class="input1">{{item.workYear}}年</view>
				<view class="input1">{{item.education}}</view>

			</view>
			<view class="cu-form-group">
				<view class="title" style="width: 50%;display: flex;align-items: center;">
					<image src="../../static/img/resume-phone@2x.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 1px;"></image>{{item.phone}}
				</view>

			</view>
		</view>

		<view style="height: 10px;background:#FAFAFA;"></view>

		<view style="padding: 20upx 0;">
			<view class="uni-list-cell" @tap="showModal2" data-target="Modal1">
				<view class="uni-list-cell-navigate uni-navigate-right" style="display: flex;flex-wrap: nowrap;justify-content: flex-start;">
					<text style="color: #333;font-size: 35upx;">求职信息</text>

				</view>
			</view>
			<view class="cu-form-group">
				<view class="title">
					<image src="../../static/img/9.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>期望职位
				</view>
				<view class="input" style="text-align: left;">{{item.exPosition}}</view>
			</view>
			<view class="cu-form-group">
				<view class="title">
					<image src="../../static/img/8.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>工作性质
				</view>
				<view class="input" style="text-align: left;">{{item.nature}}</view>
			</view>
			<view class="cu-form-group">
				<view class="title">
					<image src="../../static/img/4.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>期望城市
				</view>
				<view class="input" style="text-align: left;">{{exCity}}</view>
			</view>
			<view class="cu-form-group">
				<view class="title">
					<image src="../../static/img/10.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>期望薪资
				</view>
				<view class="input" style="text-align: left;">{{item.salary}}</view>
			</view>
			<view class="cu-form-group">
				<view class="title">
					<image src="../../static/img/5.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>目前状况
				</view>
				<view class="input" style="text-align: left;">{{item.curStatus}}</view>
			</view>
			<view class="cu-form-group">
				<view class="title">
					<image src="../../static/img/123.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>到岗时间
				</view>
				<view class="input" style="text-align: left;">{{item.arrivalTime}}</view>
			</view>
		</view>
		<view style="height: 10px;background:#FAFAFA;"></view>

		<view style="padding: 20upx 0;">
			<view class="uni-list-cell" @tap="showModal1" data-target="Modal1">
				<view class="uni-list-cell-navigate uni-navigate-right" style="display: flex;flex-wrap: nowrap;justify-content: flex-start;">
					<text style="color: #333;font-size: 35upx;">简历信息</text>

				</view>
			</view>

			<view class="cu-form-group">
				<view class="title" style="width: 50%;">
					<image src="../../static/img/resume-experience@2x.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>优势/技能
				</view>

			</view>
			<view class="cu-form-group">
				<view class="input" style="text-align: left;font-size: 30upx;">{{item.skills}}</view>

			</view>

			<view class="cu-form-group">
				<view class="title" style="width: 50%;">
					<image src="../../static/img/resume-evaluate@2x.png" style="height: 16px;width: 16px;margin-right: 10px;position: relative;top: 3px;"></image>自我评价
				</view>

			</view>
			<view class="cu-form-group">
				<view class="input" style="text-align: left;font-size: 30upx;">{{item.selfEvaluation}}</view>

			</view>


		</view>





		<view style="height: 50px;"></view>


	</view>

	</view>

	<view v-else style="background: #fff;padding: 50px 0;box-sizing: border-box;height: 100vh;">

		<view style="width: 100%;box-sizing: border-box;padding: 200upx 20% 70upx 20%;">
			<image src="../../static/xiao/empty.png" mode="" style="width: 350upx;height: 350upx;display: block;margin: 0 auto;"></image>
		</view>

		<button @tap="add4" data-target="Modal2" class="cu-btn bg-red margin-tb-sm lg" style="width: 40%;background: #0084FF;margin: 30px auto;display: block;font-size: 35upx;height: 40px;line-height: 40px;">+
			添加简历</button>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				'item': [],
				'status': '',
				exCity: '',
				uid: '',
				show: ''

			};
		},
		onLoad: function() {
			// this.getinfo();
		},
		onShow: function() {
			this.getinfo();
		},
		methods: {
			getinfo: function() {
				console.log(11111);
				uni.showLoading({
					'title': "加载中..."
				});
				var _this = this;
				uni.request({
					url: _this.apiServer + '/api/v1.Resume/getResumeByUserId',
					method: 'POST',
					data: {
						"id_token": uni.getStorageSync('token'),
						'accessToken': uni.getStorageSync('utoken')
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {

						console.log(res);
						var data = res.data.data.list;

						var status = res.data.errorCode;
						console.log(status);
						uni.hideLoading();
						if (status == '0') {
							console.log(data);

							if (data == null) {

							} else {
								_this.show = true;
								_this.item = data;

								var d = data.exCity.split(",");
								_this.exCity = d[0] + '-' + d[1] + '-' + d[2];
								_this.uid = data.id;

							}



						} else {

							_this.show = false;

						}

					}
				})



			},
			add4: function() {
				uni.navigateTo({

					url: 'jianliadd'
				});
			},

			
			showModal(e) {
				var _this = this;

				var arr = JSON.stringify(_this.item);

				uni.navigateTo({
					url: 'updatajian?con=' + arr,

				});
			},
			showModal2() {
				var _this = this;
				var arr = JSON.stringify(_this.item);

				uni.navigateTo({
					url: 'updatajian2?con=' + arr

				});



			},
			
			showModal1(e) {
				var _this = this;
				var arr = JSON.stringify(_this.item);
				uni.navigateTo({
					url: 'updatajian1?con=' + arr

				});
			},
			
		}
	}
</script>

<style>
	page {
		height: 100%;
		width: 100%;
		background: #fff;
	}

	.cu-form-group .title {
		min-width: calc(4em + 15px);
	}

	.cu-form-group {
		border: none !important;
	}

	.title {
		color: #666;
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

	.cu-form-group .title {
		text-align: justify;
		font-size: 30rpx;
		position: relative;
		height: 60rpx;
		line-height: 60rpx;
		width: 30%;
	}

	.cu-dialog {
		display: none;
	}

	.input1 {
		font-size: 28upx;
		color: #555;
		padding-right: 25upx;
	}
</style>

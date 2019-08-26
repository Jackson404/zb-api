<template>
	<view class="common-list animated fadeInLeft fast" @tap="reg(item.id)"  >
		
		<view class="com-top u-f-ajc">
			<view class="com-left text" style="display: flex;flex-wrap: nowrap;align-items: center;">
				<!-- v-if="item.isSoldierPriority==1" -->
				<text class="text" style="font-size: 34upx;max-width: 70%;display: block;margin-right: 10upx;">{{item.name}}</text> <image v-if="item.isSoldierPriority==1" class="image"   src="../../static/junren.png"  style="height: 30upx;display: block;width: 30%;" lazy-load="true"></image>
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
</template>

<script>
	export default {
		props:{
			item:Object,
			index:Number
		},
		data() {
			return {
				isguanzhu: this.item.isguanzhu
			}
		},
		methods:{
			guanzhu(){
				this.isguanzhu=true;
				uni.showToast({
					title: '关注成功',
				});
			},
			reg:function(e){
				
					uni.navigateTo({
						url: '../../pages/orderInfo/orderInfo?id='+e
					});
				
				
				
			}
		}
	}
</script>

<style scoped>
.common-list{
	padding: 20upx;
	width: 100%;
	box-sizing: border-box;
	overflow: hidden;
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

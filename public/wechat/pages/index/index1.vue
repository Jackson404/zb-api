<template>
	
	<view class="uni-tab-bar" style="overflow: scroll;background: url(../static/img/main.png);background-size: 100% 100%;">
		
			<image src="../../static/img/banner@2x.png" style="width: 100%;height: 42vh;display: block;overflow: unset;"></image>
			<!-- <view style="background: url(../../static/img/banner@2x.png);background-size: 100% 100%;height: 46vh;opacity: 1;"></view> -->
			
			<view style="position: relative;padding-top: 10px;">
				
				<image src="../../static/title@2x.png" mode="widthFix" style="display: block;margin: 0 auto;"></image>
				<view style="font-size: 16px;color: #fff;text-align: center;position: absolute;top: 0;width: 100%;line-height: 65px;">热门职位</view>
			</view>
			
			<view class="con" style="width: 100%;padding: 10px;box-sizing: border-box;z-index: 999;">
				<navigator :url="'../orderInfo/orderInfo?id='+item.id" class="list" v-for="(item, index) in newsList" :key="index" style="position: relative;">
				
					<image v-if="item.soldier==1" src="../../static/shop@2x.png" mode="widthFix" style="min-height: 260px;width: 100%;"></image>
					<image v-else src="../../static/123.png" mode="widthFix" style="min-height: 260px;width: 100%;"></image>
					<view class="conn">
						<view style="height: 60px;line-height: 60px;font-size: 35upx;color: #fff;width: 90%;box-sizing: border-box;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">{{item.conpany}}</view>
						<view style="width: 100%;height: 35px;line-height: 35px;display: flex;border-bottom: 1px solid #fff;">
						
							<text style="background: url(../../static/position@2x.png);background-size: 100% 100%;height: 15px;width: 12px;display: block;position: relative;top: 10px;"></text>
							<text style="padding: 0 5px;color: #fff;font-size: 28upx;max-width: 60%;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">{{item.address}}</text>
							<text style="color: #fff;padding: 0 5px;">|</text>
							<text style="padding: 0 5px;color: #fff;font-size: 28upx;">{{item.wk_year}}</text>
						</view>
						<view class="tag" >
							<view class="tagone" >{{item.tag1}}</view>
							<view class="tagone" >{{item.tag2}}</view>
							<view class="tagone" >{{item.tag3}}</view>
						</view>
						<view style="height: 25px;"></view>
						<view class="desc" >
							<view class="desc_name">
								{{item.title}}
							</view>
							<view class="desc_mon">
								{{item.money}}
							</view>
						</view>
					</view>
					</navigator>
				</view>	
				
			<!-- 合作伙伴 -->
			
			<view style="height: 30px;"></view>
			<view style="position: relative;z-index: 999;">
				
				<image src="../../static/title@2x.png" mode="widthFix" style="display: block;margin: 0 auto;"></image>
				<view style="font-size: 16px;color: #fff;text-align: center;position: absolute;top: 0;width: 100%;line-height: 50px;">合作伙伴</view>
			</view>
			
			<view style="display: flex;flex-wrap: wrap;width: 100%;padding: 10px;box-sizing: border-box; z-index: 999;padding-bottom: 30px;">
				<image src="../../static/img/partner-01.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-02.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-03.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-04.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-05.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-06.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-07.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-08.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-09.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-10.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-11.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				
				<image src="../../static/img/partner-12.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-13.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
				<image src="../../static/img/partner-14.png" mode="widthFix" style="width: 20%;height: 40px;margin: 8px;"></image>
			</view>
			
			<view style="height: 500px;width: 100%;"></view>
			
		
		
	</view>
	
	
</template>
<script>
	import uniMediaList from '@/components/uni-media-list/uni-media-list.vue';
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';

	import {
		friendlyDate
	} from '@/common/util.js';

	export default {
		components: {
			uniMediaList,
			uniLoadMore
		},
		data() {
			return {
				loadingText: {
					contentdown: '上拉加载更多',
					contentrefresh: '正在加载...',
					contentnomore: '没有更多数据了'
				},
				scrollLeft: 0,
				refreshing: false,
				refreshText: '下拉可以刷新',
				newsList: [],
				tabIndex: 0,
				
			}
		},
		onLoad: function() {
			 this.getInfo();
		},
		methods: {
			
				getInfo:function(){
						var _this =this;
						uni.showLoading({'title':"加载中..."});
						uni.request({
							url:this.websiteUrl+"index/work",
							method:'POST',
							data:{
								'token':'api2018'
								
							},
							header:{'content-type':'application/x-www-form-urlencoded'},
							success:function(res) {
								
								 
								var data = res.data.data;
								var status = res.data.status;
								
								if(status=='ok'){
									_this.newsList=data;
									uni.hideLoading();
									
								}
								
							}
						})
				
				
					
				},
				
			loadMore() {
				this.getList(2);
			},
			async changeTab(event) {
				let index = event.detail.current;
				if (this.isClickChange) {
					this.tabIndex = index;
					this.isClickChange = false;
					return;
				}
				let tabBar = await this.getElSize('tab-bar');
				let tabBarScrollLeft = tabBar.scrollLeft;
				let width = 0;

				for (let i = 0; i < index; i++) {
					let result = await this.getElSize(this.tabBars[i].ref);
					width += result.width;
				}
				let winWidth = uni.getSystemInfoSync().windowWidth,
					nowElement = await this.getElSize(this.tabBars[index].ref),
					nowWidth = nowElement.width;
				if (width + nowWidth - tabBarScrollLeft > winWidth) {
					this.scrollLeft = width + nowWidth - winWidth;
				}
				if (width < tabBarScrollLeft) {
					this.scrollLeft = width;
				}
				this.isClickChange = false;
				this.tabIndex = index;

				// 首次切换后加载数据
				const activeTab = this.newsList[this.tabIndex];
				if (activeTab.data.length === 0) {
					this.getList();
				}
			},
			getNodeSize(node) {
				return new Promise((resolve, reject) => {
					dom.getComponentRect(node, (result) => {
						resolve(result.size);
					});
				});
			},
			onRefresh(event) {
				this.refreshText = '正在刷新...';
				this.refreshing = true;
				this.getList();
			},
			getElSize(id) { //得到元素的size
				return new Promise((res, rej) => {
					uni.createSelectorQuery().select('#' + id).fields({
						size: true,
						scrollOffset: true
					}, (data) => {
						res(data);
					}).exec();
				});
			},
			async tapTab(index) { //点击tab-bar
				if (this.tabIndex === index) {
					return false;
				} else {
					let tabBar = await this.getElSize('tab-bar'),
						tabBarScrollLeft = tabBar.scrollLeft; //点击的时候记录并设置scrollLeft
					this.scrollLeft = tabBarScrollLeft;
					this.isClickChange = true;
					this.tabIndex = index;
					// 首次切换后加载数据
					const activeTab = this.newsList[this.tabIndex];
					if (activeTab.data.length === 0) {
						this.getList();
					}
				}
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

	.uni-tab-bar {
		display: flex;
		flex: 1;
		flex-direction: column;
		overflow: hidden;
		height: 100%;
	}


	.uni-swiper-tab {
		width: 100%;
		white-space: nowrap;
		line-height: 100upx;
		height: 100upx;
		border-bottom: 1px solid #c8c7cc;
	}

	.swiper-tab-list {
		font-size: 30upx;
		width: 150upx;
		display: inline-block;
		text-align: center;
		color: #555;
	}

	.uni-tab-bar .active {
		color: #007AFF;
	}

	.uni-tab-bar .swiper-box {
		flex: 1;
		width: 100%;
		height: calc(100% - 100upx);
	}

	.uni-tab-bar-loading {
		padding: 20upx 0;
	}
	.list{
		width: 100%;
		box-sizing: border-box;
		padding: 3px;
		position: relative;

		
	}
	.conn{
		width: 100%;
		position: absolute;
		left: 0;
		top: 0;
		box-sizing: border-box;
		padding: 20px 30px;
		height: 270px;
	}
	.tag{
		width: 100%;height: 30px;padding: 15px 0;display: flex;flex-wrap: nowrap;line-height: 20px;
	}
	.tagone{
		height: 20px;padding: 0 15px;display: inline-block;font-size: 25upx;color: #fff;border-radius: 25px;border: 1px solid #fff;margin-right: 10px;
	}	
	.desc{
		width: 100%;height: 50px;background: url(../../static/shop-01@2x.png);background-size: 100% 100%;line-height: 50px;display: flex;flex-wrap: nowrap;position: relative;}
	.desc_name{width: 60%;color: #333;box-sizing: border-box;padding-left: 10px;font-size: 32upx;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
	.desc_mon{height: 25px;line-height: 25px;padding: 0 15px;font-size: 23upx;background: #560BC8;color: #fff;border-radius: 5px;position: absolute;top: 12.5px;right: 5px;}
</style>

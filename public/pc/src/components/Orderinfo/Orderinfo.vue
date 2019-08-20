<template>
	<div>
		<Header></Header>
		<div class="wrap">
			<div class="top">订单详情</div>
			<div class="content">
				<div class="c-top">
					<div class="c-l-c-top">
						<div class="c-l-c-t-left">岗位名称</div>
						<div class="c-l-c-t-right">￥:{{detail.unitPrice}}/人</div>
					</div>
					<div class="c-l-c-top" style="padding-bottom: 15px;"><div class="c-l-c-t-left">{{detail.positionName}}</div></div>
					<div class="c-c-wrap">
						<div class="c-c-w-list"><div class="c-c-w-l-top">受邀：{{detail.applyNum}}人</div></div>
						<div class="c-c-w-list"><div class="c-c-w-l-top">面试：{{detail.interviewNum}}人</div></div>
						<div class="c-c-w-list"><div class="c-c-w-l-top">入职：{{detail.entryNum}}人</div></div>
						<div class="c-c-w-list"><div class="c-c-w-l-top">收入：{{detail.income}}元</div></div>
					</div>
				</div>
				<div class="u-l-top">
					<div class="u-l-t-list">面试者名称</div>
					<div class="u-l-t-list">手机号码</div>
					<div class="u-l-t-list">邀请人</div>
					<div class="u-l-t-list">简历信息</div>
					<div class="u-l-t-list">面试情况</div>
					<div class="u-l-t-list">入职情况</div>
				</div>
				<div class="list" v-for="(item, index) in applyList" :key="index">
					<div class="u-l-t-list">{{item.applyUserName}}</div>
					<div class="u-l-t-list">{{item.applyUserPhone}}</div>
					<div class="u-l-t-list">{{item.shareUserName}}</div>
					<div class="u-l-t-list">{{item.resumeId}}</div>
					<div class="u-l-t-list" v-if="item.interviewStatus==0">未面试</div>
					<div class="u-l-t-list" v-else>面试</div>
					<div class="u-l-t-list" v-if="item.entryStatus==0">未入职</div>
					<div class="u-l-t-list" v-else>入职</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'Orderinfo',
	components: {
		Header
	},
	data(){
		return{
			orderId:null,
			detail:[],
			applyList:[],
		}
	},
	created() {
		this.orderId=this.$route.params.id;
		
		this.getinfo(this.$route.params.id);
	},
	methods:{
		getinfo(e){
			
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			
			var data={
				accessToken:"1565742674|145B1691263AEC04CC1722BA2EF68A86",
				orderId:parseInt(this.orderId),
				id_token:this.$cookies.get('access_token')
			}
			
			var _this=this;
			this.$http
				.getOrderDetail(data)
				.then(res => {
					_this.$_loading.close();
					console.log(res);
					if(res.errorCode==0){
						_this.applyList=res.data.applyList;
						_this.detail=res.data.detail;
					}
				})
				.catch(err => {
					console.log(err);
				});
			
			
			
			
		},
		
	}
};
</script>

<style scoped>
div {
	text-align: left;
}
.wrap {
	width: 1200px;
	background: #fff;
	padding: 20px;
	box-sizing: border-box;
	margin: 0 auto;
	margin-top: 110px !important;
	min-height: 800px;
}
.top {
	width: 100%;
	height: 80px;
	line-height: 80px;
	font-size: 25px;
	font-family: Adobe Heiti Std R;
	font-weight: normal;
	color: rgba(78, 86, 94, 1);
}
.content {
	width: 100%;
	padding: 20px 0 0 0;
	border: 1px solid rgba(141, 146, 151, 1);
}
.c-top {
	width: 100%;
	box-sizing: border-box;
	padding: 0 10px;
}
.c-l-c-top {
	width: 100%;
	height: 35px;
	line-height: 35px;
	display: flex;
	flex-wrap: nowrap;
}
.c-l-c-t-left {
	flex: 1;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.c-l-c-t-right {
	width: 150px;
	text-align: right;
	font-size: 18px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 123, 77, 1);
}

.c-c-wrap {
	width: 100%;
	padding: 15px 0;
	border-top: 1px solid rgba(141, 146, 151, 1);
	display: flex;
	padding-right: 100px;
	box-sizing: border-box;
	flex-wrap: nowrap;
	justify-content: space-between;
}
.c-c-w-list {
	width: 25%;
}
.c-c-w-l-top {
	font-size: 18px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 123, 77, 1);
	height: 30px;
	line-height: 30px;
	text-align: center;
}
.c-c-w-l-bom {
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	height: 30px;
	line-height: 30px;
	text-align: center;
}
.u-l-top {
	height: 50px;
	line-height: 50px;
	box-sizing: border-box;
	padding: 0 20px;
	display: flex;
	flex-wrap: nowrap;
	border-top: 1px solid rgba(141, 146, 151, 1);
	background: #f0f2f5;
}
.u-l-t-list {
	flex: 1;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	text-align: center;
}
.list {
	height: 50px;
	line-height: 50px;
	box-sizing: border-box;
	padding: 0 20px;
	display: flex;
	flex-wrap: nowrap;
	border-top: 1px solid rgba(141, 146, 151, 0.6);
}
</style>

<template>
	<div>
		<Header></Header>
		<div class="wrap">
			<!-- top -->
			<div class="top">
				<div class="top-left">
					<div class="t-name">总收益</div>
					<div class="t-money">{{incomeTotal}}</div>
				</div>
				<div class="top-right">
					<div class="t-name">总订单量</div>
					<div class="t-money">{{orderNum}}</div>
				</div>
			</div>

			<!-- 切换 -->
			<div class="nav">
				<div class="n-bar " :class="[status ? 'check' : '']" @click="one()">已完成的订单</div>
				<div class="n-bar" :class="[!status ? 'check' : '']" @click="one1()">进行中的订单</div>
			</div>

				<div class="ul">
					<div v-if="list.length>0">
						
					
					<div class="u-list" v-for="(item, index) in list" :key="index">
						<router-link :to="{ name: 'Orderinfo', params: { id: item.orderId} }">
							<div class="u-l-top">
								<div class="u-l-t-left" >订单编号：{{item.orderId}}</div>
								<div class="u-l-t-right">{{item.createTime}}</div>
							</div>
							<!-- 中间部分 -->
							<div class="u-l-cen">
								<div class="c-l-c-top">
									<div class="c-l-c-t-left">{{item.positionName}}</div>
									<div class="c-l-c-t-right">￥:{{item.unitPrice}}元/人</div>
								</div>
								<div class="c-l-c-top" style="padding-bottom: 15px;"><div class="c-l-c-t-left">{{item.companyName}}</div></div>
			
								<div class="c-c-wrap" style="position: relative;">
									<div class="c-c-w-list">
										<div class="c-c-w-l-top">{{item.applyNum}}</div>
										<div class="c-c-w-l-bom">已投递</div>
									</div>
									<div class="c-c-w-list">
										<div class="c-c-w-l-top">{{item.interviewNum}}</div>
										<div class="c-c-w-l-bom">已面试</div>
									</div>
									<div class="c-c-w-list">
										<div class="c-c-w-l-top">{{item.entryNum}}</div>
										<div class="c-c-w-l-bom">已入职</div>
									</div>
									<div class="c-c-w-list">
										<div class="c-c-w-l-top">￥:{{item.income}}</div>
										<div class="c-c-w-l-bom">收益</div>
									</div>
									
									<img v-if="isFinish==1" style="height: 40px;position: absolute;right: 20px;top: 20px;" src="../../assets/icon-04.png" alt="" />
								</div>
							</div>
						</router-link>
					</div>
					</div>
					<div v-else style="width: 100%;padding: 100px 0;">
						<div style="width: 150px;margin: 0 auto;">
							<img src="../../assets/more123.png" alt="" style="display: block;margin: 0 auto;">
							<div style="text-align: center;font-size: 14px;color: #888;padding: 10px 0;">暂时没有订单</div>
							
							<div style="display: flex;justify-content: center;">
								<el-button size="small" round @click="go">去接单</el-button>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'Order',
	components: {
		Header
	},
	data() {
		return {
			status: true,
			yuangong:[],
			incomeMonth:null,
			entryNumMonth:null,
			orderNumMonth:null,
			list:[],
			data:'',
			incomeTotal:'',
			orderNum:'',
			value2:'2019-08'
		};
	},
	created() {
		if (!this.$cookies.isKey('access_token')) {
			this.$router.push({ name: 'Login', params: { userId: '123' } });
		}
		//获取已完成订单
		this.handleClickk();
	},
	methods: {
		//跳转首页
		go(){
			
			this.$router.push({ name: 'Index', params: { id: 1 } });
		},
		//已完成的订单
		one() {
			this.status = true;
			this.isFinish=1;
			this.handleClickk();
		},
		//进行中的订单
		one1() {
			this.status = false;
			this.isFinish=0;
			this.handleClickk();
		},
		//员工详情
		handleClickk(){
			
			
			//请求接口
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				orderDate:this.value2,
				isFinish:this.isFinish,
				
			};
			
			var _this = this;
			this.$http
				.getOrder(data)
				.then(res => {
					
					_this.$_loading.close();
					
					if (res.errorCode == 0) {
						_this.orderNumMonth=res.data.orderNumMonth;
						_this.incomeMonth=res.data.incomeMonth;
						_this.entryNumMonth=res.data.entryNumMonth;
						_this.list=res.data.list;
						_this.data=res.data.recOrderYear+'-'+res.data.recOrderMonth;
						
						_this.incomeTotal=res.data.incomeTotal;
						_this.orderNum=res.data.orderNum;
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
a {
	text-decoration: none;
}

.wrap {
	width: 1200px;
	background: #fff;
	padding: 20px 0;
	margin: 0 auto;
	margin-top: 110px !important;
}
.top {
	width: 100%;
	box-sizing: border-box;
	padding: 0 80px 30px;

	display: flex;
	flex-wrap: nowrap;
	justify-content: space-between;
}
.top-left {
	width: 49%;
	height: 104px;
	background: url(../../assets/a11.png);
	background-size: 100%;
}
.top-right {
	width: 49%;
	height: 104px;
	background: url(../../assets/a12.png);
	background-size: 100%;
}
.t-name {
	width: 100%;
	height: 50px;
	line-height: 60px;
	font-size: 18px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	text-align: center;
}
.t-money {
	width: 100%;
	height: 50px;
	line-height: 30px;
	font-size: 30px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	text-align: center;
}
.nav {
	background: #0084ff;
	height: 40px;
	line-height: 40px;
	box-sizing: border-box;
	padding: 0 80px;
	display: flex;
	flex-wrap: nowrap;
}
.n-bar {
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	display: inline-block;
	padding: 0 20px;
	letter-spacing: 2px;
	cursor: pointer;
	border: none;
}
.check {
	background: #fff;
	color: #0084ff;
}
.content {
	width: 100%;
	box-sizing: border-box;
	padding: 20px 80px 40px 80px;
}
.c-top {
	width: 100%;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
	height: 40px;
}
.c-t-left {
	flex: 1;
	font-size: 20px;
	font-family: Adobe Heiti Std R;
	font-weight: normal;
	color: rgba(78, 86, 94, 1);
}
.c-t-right {
	width: 100px;
	text-align: right;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.c-t-c {
	height: 30px;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
}
.c-t-list {
	font-size: 16px;
	font-family: Adobe Heiti Std R;
	font-weight: normal;
	color: rgba(255, 123, 77, 1);
	padding-right: 70px;
}
.ul {
	width: 100%;
	padding: 15px 0;
}
.u-list {
	width: 100%;
}
.u-l-top {
	height: 50px;
	line-height: 50px;
	box-sizing: border-box;
	padding: 0 20px;
	display: flex;
	flex-wrap: nowrap;
	border: 1px solid rgba(141, 146, 151, 1);
	background: #f0f2f5;
}
.u-l-t-left {
	flex: 1;
	font-size: 20px;
	font-family: Adobe Heiti Std R;
	font-weight: normal;
	color: rgba(78, 86, 94, 1);
}
.u-l-t-right {
	width: 200px;
	font-size: 16px;
	font-family: Adobe Heiti Std R;
	font-weight: normal;
	color: rgba(78, 86, 94, 1);
	text-align: right;
}
.u-l-cen {
	width: 100%;
	box-sizing: border-box;
	padding: 20px;
	border-left: 1px solid rgba(141, 146, 151, 1);
	border-right: 1px solid rgba(141, 146, 151, 1);
	border-bottom: 1px solid rgba(141, 146, 151, 1);
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
	font-size: 24px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(0, 132, 255, 1);
	height: 35px;
	line-height: 35px;
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
</style>

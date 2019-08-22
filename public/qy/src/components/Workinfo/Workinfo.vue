<template>
	<div>
		<Header></Header>
		<div style="width: 1200px;margin: 0 auto;padding: 50px 0;display: flex;flex-wrap: nowrap;justify-content: space-between;height: auto;margin-top: 110px;">
			<!--左边-->
			<div class="left">
				<div class="l-top">
					<div class="i-w-top">
						<!--岗位名称-->
						<div class="i-w-title">{{list.name}}</div>
						<!--薪资-->
						<div class="i-w-money">￥ {{list.unitPrice}}/人</div>
					</div>

					<div class="l-t-bom">
						<div class="l-t-address">{{list.address}}</div>
						<div class="l-t-rg">
							<div class="l-t-btn" @click="share(list.id)">分享</div>
							<div class="l-t-btn" @click="acc(list.id)">接单</div>
						</div>
					</div>
					<div class="l-t-tag"><div class="tag" v-for="(tag,index) in list.labelIds" :key="index">{{tag}}</div></div>
				</div>
				<div class="l-mod">
					<div class="l-r-list">
						<div class="l-r-title">单位名称：</div>
						<div class="l-r-con">{{list.companyName}}</div>
					</div>
					<div class="l-r-list" style="opacity: 0">
						<div class="l-r-title">单位名称：</div>
						<div class="l-r-con">啦啦啦啦</div>
					</div>
					<div class="l-r-list">
						<div class="l-r-title">学历:</div>
						<div class="l-r-con">{{list.education}}</div>
					</div>
					<div class="l-r-list">
						<div class="l-r-title">招聘人数:</div>
						<div class="l-r-con">{{list.num}}人</div>
					</div>
					<div class="l-r-list">
						<div class="l-r-title">年龄:</div>
						<div class="l-r-con" v-if="list.age==''">不限</div>
						<div class="l-r-con" v-else>{{list.age}}岁</div>
					</div>
					<div class="l-r-list">
						<div class="l-r-title">工作经验:</div>
						
						<div class="l-r-con" v-if="list.workExp==0">不限</div>
						<div class="l-r-con" v-else>{{list.workExp}}年</div>
					</div>
					<div class="l-r-list">
						<div class="l-r-title">面试地址:</div>
						<div class="l-r-con">{{list.interviewAddress}}</div>
					</div>
					<div class="l-r-list" style="opacity: 0">
						<div class="l-r-title">单位名称：</div>
						<div class="l-r-con">啦啦啦啦</div>
					</div>
					<div class="l-r-list">
						<div class="l-r-title" style="color: #FF7B4D">面试时间:</div>
						<div class="l-r-con" style="color: #FF7B4D">{{list.time}}</div>
					</div>
					<div class="l-r-list" style="opacity: 0">
						<div class="l-r-title">单位名称：</div>
						<div class="l-r-con">啦啦啦啦</div>
					</div>
					<div style="height: 15px;width: 100%;border-bottom:1px solid rgba(244,244,244,1);"></div>

					<div class="m-title">岗位职责</div>
					
					<div class="m-wrap">
						
						<div class="m-w-con" v-html="list.positionRequirement"></div>
					</div>

					<!-- <div class="m-wrap">
						
						
						<div class="m-w-list">
							<div class="m-w-title">薪资待遇:</div>
							<div class="m-w-con">{{list.pay}}</div>
						</div>
						<div class="m-w-list">
							<div class="m-w-title">工作时间:</div>
							<div class="m-w-con">{{list.workExp}}</div>
						</div>
						<div class="m-w-list">
							<div class="m-w-title">工作地点:</div>
							<div class="m-w-con">{{list.province}}{{list.city}}{{list.area}}{{list.address}}</div>
						</div>
						<div class="m-w-list">
							<div class="m-w-title">面试时间:</div>
							<div class="m-w-con">{{list.time}}</div>
						</div>
						<div class="m-w-list" style="height: auto;">
							<div class="m-w-title">岗位职责:</div>
							<div class="m-w-con" v-html="list.positionRequirement"></div>
						</div>
					</div> -->
					
				</div>

				<div class="i-wrap">
					<div class="m-title" style="height: 50px;line-height: 50px;">相似岗位</div>
					<div class="list" v-for="(item, index) in items" :key="index" @click="go(item.id)">
						
						<div class="i-list">
							<div class="i-w-top">
								<!--岗位名称-->
								<div class="i-w-title">{{item.name}}</div>
								<!--薪资-->
								<div class="i-w-money">￥ {{item.unitPrice}}/人</div>
							</div>
							<div style="width: 100%;display: flex;flex-wrap: nowrap;align-items: center">
								<div style="flex: 1;">
									<!--公司名称-->
									<div class="i-w-compay">{{item.companyName}}</div>
									<!--学历，工作经营，薪资-->
									<div class="i-w-cty">
										<div class="i-w-x">{{item.education}}</div>
										<div class="i-w-x pad">|</div>
										<div class="i-w-x" v-if="item.workExp==0">不限</div>
										<div class="i-w-x" v-else>{{item.workExp}}年</div>
										<div class="i-w-x pad">|</div>
										<div class="i-w-x">￥:{{item.pay}}元</div>
									</div>
									<!--		地址，人数-->
									<div class="i-w-add">
										<div class="i-w-num" style="padding-right: 30px;">人数需求: {{item.num}}人</div>
										<div class="i-w-num">面试时间:{{item.time}}</div>
									</div>
								</div>
								<div
									style="width: 120px;height: 34px;border:1px solid rgba(0,132,255,1);border-radius:18px;text-align: center;line-height: 34px;font-size:18px;font-family:AlibabaPuHuiTiR;font-weight:400;color:rgba(0,132,255,1);"
								>
									接单
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--右边-->
			<div class="right">
				<div class="m-title" style="text-align: center">{{list.companyName}}</div>
				<div style="width: 100%;box-sizing: border-box;padding: 15px 0;display: flex;flex-wrap: nowrap;justify-content: space-between;align-items: center">
					<div style="height: 1px;flex: 1;background: #F0F2F5"></div>
					<div style="width:90px;font-size:16px;font-family:AlibabaPuHuiTiR;font-weight:400;color:rgba(141,146,151,1);text-align: center">公司简介</div>
					<div style="height: 1px;flex: 1;background: #F0F2F5"></div>
				</div>
				<div class="r-con" v-html="list.companyProfile"></div>
			</div>
		</div>
		
		<el-dialog
		  title="分享"
		  :visible.sync="dialogVisible"
		  width="30%"
		  :before-close="handleClose">
		  <span>
			  <img :src="qrCode" style="width: 300px;height: 300px;display: block;margin: 0 auto;" alt="">
		  </span>
		  <span slot="footer" class="dialog-footer">
			<el-button @click="dialogVisible = false">取 消</el-button>
			<el-button type="primary" @click="dialogVisible = false"><a :href="qrCode" target="_blank" download="123.jpg" style="color: #fff;text-decoration: none;">一键下载</a></el-button>
		  </span>
		</el-dialog>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'Workinfo',
	components: {
		Header
	},
	data() {
		return {
			id:'',
			list:[],
			items:[],
			status:0,
			qrCode:0,
			dialogVisible: false
		};
	},
	created() {
		this.id=this.$route.params.id;
		console.log(this.$route.params.id)
		this.getinfo(this.$route.params.id);
	
		document.documentElement.scrollTop=0;
	},
	watch:{
		'$route'(to,from) {
		  // 对路由变化作出响应...
		  console.log(to)
		  this.id=to.params.id;
		  this.getinfo(to.params.id);
		  console.log(from)
		
		  document.documentElement.scrollTop=0;
		}
	  },
	methods:{
		
		//获取职位详情
		getinfo(e){
			
			var data={
				accessToken:"1565742674|145B1691263AEC04CC1722BA2EF68A86",
				positionId:parseInt(e),
				id_token:this.$cookies.get('access_token')
			}
			
			var _this=this;
			this.$http
				.getDeta(data)
				.then(res => {
					
					if(res.errorCode==0){
						
						var xx=res.data.detail;
						
					
							xx.time=_this.transformTime(xx.interviewTime)
							
						
						
						
						this.list=xx;
						
					
						var xx=res.data.randomList;
						
						for (var i=0;i<xx.length;i++) {
							xx[i].time=_this.transformTime(xx[i].interviewTime)
						}
						
						
						this.items=xx;
						this.status=res.data.hasRecOrder;
					}
				})
				.catch(err => {
					console.log(err);
				});
			
			
			
			
		},
		addZero(m) {
			return m < 10 ? '0' + m : m;
		},
		transformTime(timestamp = +new Date()) {
			if (timestamp) {
				var time = new Date(timestamp*1000);
				var y = time.getFullYear();
				var M = time.getMonth() + 1;
				var d = time.getDate();
				var h = time.getHours();
				var m = time.getMinutes();
				var s = time.getSeconds();
				return y + '-' + this.addZero(M) + '-' + this.addZero(d) + ' ' + this.addZero(h) + ':' + this.addZero(m) + ':' + this.addZero(s);
			  } else {
				  return '';
			  }
		},
		go(e){
			console.log(e);
				this.$router.push({ name: 'Workinfo', params: { id: e} });
		},
		acc(e){
			var _this =this;
			
			 this.$_loading =  this.$loading({
			  lock: true,
			  text: 'Loading',
			  spinner: 'el-icon-loading',
			  background: 'rgba(0, 0, 0, 0.7)'
			});
			
			var data={
				accessToken:"1565742674|145B1691263AEC04CC1722BA2EF68A86",
				positionId:parseInt(e),
				id_token:this.$cookies.get('access_token')
			}
			
			var _this=this;
			this.$http
				.receive(data)
				.then(res => {
					console.log(res);
					_this.$_loading.close();
					if(res.errorCode==0){
						this.$message({
								message: '接单成功',
								type: 'success',
								offset:'100'
							});
						_this.qrCode=res.data.qrCode;
	
					}else{
						_this.$message({
							message: res.msg,
							type: 'warning',
							offset:'100'
						});
					}
				})
				.catch(err => {
					console.log(err);
				});
			
			
			
		},
		share(e){
			var _this =this;
			
			 this.$_loading =  this.$loading({
			  lock: true,
			  text: 'Loading',
			  spinner: 'el-icon-loading',
			  background: 'rgba(0, 0, 0, 0.7)'
			});
			
			var data={
				accessToken:"1565742674|145B1691263AEC04CC1722BA2EF68A86",
				positionId:parseInt(e),
				id_token:this.$cookies.get('access_token')
			}
			
			var _this=this;
			this.$http
				.share(data)
				.then(res => {
					console.log(res);
					_this.$_loading.close();
					
					
					
					
					if(res.errorCode==0){
						if(res.data.hasRecOrder==0){
							_this.$message({
								message: '该单您还未接，请先接单吧',
								type: 'warning',
								offset:'100'
							});
						}else{
							
							_this.dialogVisible=true;
							
							_this.qrCode=res.data.qrCode;
						}
						
						
					
					}
				})
				.catch(err => {
					console.log(err);
				});
			
			
			
		},
		handleClose(done) {
        done();
      }
		
	}
};
</script>

<style scoped>
div {
	text-align: left;
}
.left {
	width: 900px;
}

.l-top,
.l-mod {
	width: 100%;
	box-sizing: border-box;
	padding: 20px;
	background: #fff;
}

.i-w-top {
	height: 60px;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
}
.i-w-title {
	flex: 1;
	font-size: 20px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(0, 132, 255, 1);
}
.i-w-money {
	width: 150px;
	text-align: right;
	font-size: 18px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 123, 77, 1);
}
.l-t-bom {
	height: 40px;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
	padding-bottom: 10px;
	border-bottom: 1px solid rgba(244, 244, 244, 1);
}
.l-t-address {
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	flex: 1;
}
.l-t-rg {
	width: 200px;
	display: flex;
	flex-wrap: nowrap;
	justify-content: space-between;
	align-items: center;
}
.l-t-btn {
	width: 90px;
	height: 30px;
	line-height: 30px;
	text-align: center;
	border: 1px solid rgba(0, 132, 255, 1);
	border-radius: 25px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(0, 132, 255, 1);
	font-size: 16px;
	cursor: pointer;
}
.l-t-tag {
	width: 100%;
	box-sizing: border-box;
	padding: 20px 0 10px 0;
	display: flex;
	flex-wrap: wrap;
	align-items: center;
}
.tag {
	background: #0084ff;
	padding: 6px 10px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	margin-right: 15px;
	margin-bottom: 10px;
	border-radius: 4px;
}
.l-mod {
	margin-top: 10px;
	display: flex;
	flex-wrap: wrap;
}
.l-r-list {
	width: 50%;
	height: 40px;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
}
.l-r-title {
	width: 80px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.l-r-con {
	flex: 1;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.m-title {
	height: 60px;
	line-height: 60px;
	font-size: 20px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.m-wrap {
	width: 100%;
}

.m-w-list {
	width: 100%;
	height: 40px;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
}
.m-w-title {
	width: 80px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.m-w-con {
	flex: 1;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}

.i-wrap {
	margin-top: 10px;
	width: 100%;
	background: #fff;
	box-sizing: border-box;
	padding: 20px;
}
.list {
	/*		padding: 0 30px;*/
	box-sizing: border-box;
	transition-duration: 1s;
	cursor: pointer;
}
/*
	.list :hover {
		background: #eee;
	
	}
*/
.i-list {
	width: 100%;
	box-sizing: border-box;
	padding: 10px 0;
	border-bottom: 1px solid #f4f4f4;

	margin: 0 auto;
}

.i-w-top {
	height: 60px;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
}
.i-w-title {
	flex: 1;
	font-size: 20px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(0, 132, 255, 1);
}
.i-w-money {
	width: 150px;
	text-align: right;
	font-size: 18px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 123, 77, 1);
}
.i-w-compay {
	height: 35px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 35px;
}
.i-w-cty {
	width: 100%;
	display: flex;
	align-items: center;
	height: 35px;
}
.i-w-x,
.i-w-num {
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(141, 146, 151, 1);
	display: inline-block;
}
.pad {
	padding: 0 20px;
}
.i-w-add {
	height: 35px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 35px;
}
.right {
	width: 280px;
	box-sizing: border-box;
	padding: 20px;
	background: #fff;
	height: 400px;
	position: relative;
}
.r-con {
	font-size: 14px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 24px;
	word-break: break-all;
	text-indent: 2rem;
}
</style>

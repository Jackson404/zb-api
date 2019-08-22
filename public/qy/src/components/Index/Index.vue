<template>
	<div>
		<Header></Header>
		<div class="hello" style="margin-top: 110px;">
			<div style="width: 1200px;margin: 0 auto;padding: 20px 0;">
				<div class="i-top">
					<el-dropdown>
						<span class="el-dropdown-link">
							上海
							<img src="../../assets/1.png" alt="" style="margin-left: 10px" />
						</span>
						
					</el-dropdown>

					<div style="width: 50px;"></div>
					
					
					<el-dropdown @command="handleCommand">
					  <span class="el-dropdown-link" style="color: red;font-size: 16px;color: rgba(78, 86, 94, 1);">
						{{zu}}<img src="../../assets/1.png" alt="" style="margin-left: 10px" />
					  </span>
					  <el-dropdown-menu slot="dropdown">
						<el-dropdown-item style="display: block;" :command="'不限'">不限</el-dropdown-item>
						<el-dropdown-item style="display: block;" :command="'从低到高'">从低到高</el-dropdown-item>
						<el-dropdown-item style="display: block;" :command="'从高到底'"> 从高到底</el-dropdown-item>
						
					  </el-dropdown-menu>
					</el-dropdown>

					<div class="i-seach">
						<div class="i-con">
							<input type="text" class="form-control" v-model="keywords" placeholder="搜索岗位关键词" value="" style="height: 38px;line-height: 38px;" />
							<div class="i-btn" style="cursor: pointer;" @click="tap">搜索</div>
						</div>
					</div>
				</div>

				<div class="i-wrap">
					<div v-if="list.length>0">
						<div class="list" v-for="(item, index) in list" :key="index">
							<router-link :to="{ name: 'Workinfo', params: { id: item.id } }">
								<div class="i-list">
									<div class="i-w-top">
										<!--岗位名称-->
										<div class="i-w-title">{{ item.name }}</div>
										<!--薪资-->
										<div class="i-w-money">￥ {{ item.unitPrice }}/人</div>
									</div>
									<div style="width: 100%;display: flex;flex-wrap: nowrap;align-items: center">
										<div style="flex: 1;">
											<!--公司名称-->
											<div class="i-w-compay">{{ item.companyName }}</div>
											<!--学历，工作经营，薪资-->
											<div class="i-w-cty">
												<div class="i-w-x">{{ item.education }}</div>
												<div class="i-w-x pad">|</div>
												<div class="i-w-x" v-if="item.workExp == 0">不限</div>
												<div class="i-w-x" v-else>{{ item.workExp }}年</div>
												<div class="i-w-x pad">|</div>
												<div class="i-w-x">￥:{{ item.pay }}元</div>
											</div>
											<!--		地址，人数-->
											<div class="i-w-add">
												<div class="i-w-num" style="padding-right: 30px;">人数需求: {{ item.num }}人</div>
												<div class="i-w-num">面试时间:{{ item.time }}</div>
											</div>
										</div>
										<!-- <div v-if="item.hasRecOrder==0"
											style="width: 120px;height: 34px;border:1px solid rgba(0,132,255,1);border-radius:18px;text-align: center;line-height: 34px;font-size:18px;font-family:AlibabaPuHuiTiR;font-weight:400;color:rgba(0,132,255,1);"
										>
											接单
										</div> -->
										
										<el-button v-if="item.hasRecOrder==0" type="primary" plain style="font-size: 18px;width: 120px;border-radius: 25px;">接单</el-button>
										<el-button v-else type="primary" plain style="font-size: 18px;width: 120px;border-radius: 25px;" disabled>已接单</el-button>
										
									</div>
								</div>
							</router-link>
						</div>
						</div>
						<div style="width: 100%;padding: 200px 0;" v-else>
							<img src="../../assets/more333.png" alt="" style="display: block;margin: 0 auto;">
							<div style="font-size: 16px;color: #555;text-align: center;letter-spacing: 4;padding: 10px 0;">暂时没有您搜索的岗位</div>
						</div>
										
										
					</div>	
					
				
				</div>
				<div style="padding: 50px 0;width: auto;margin: 0 auto;display: flex;justify-content: center;">
					<!-- <el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :total="total" :current-page.sync="currentPage"></el-pagination> -->
					<el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :total="total" ></el-pagination>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'Index',
	components: {
		Header
	},
	props: {
		msg: String
	},
	data() {
		return {
			list: [],
			total: 0,
			page: 1,
			zu:'按价格顺序',
			zuId:-1,
			keywords:'',
		};
	},
	created() {
		console.log('一登陆');
		if (this.$cookies.isKey('access_token')) {
			//已登录
			console.log('已登陆');
		} else {
			//未登录
			this.$router.push({ name: 'Login', params: { userId: '123' } });
			console.log('未登陆');
		}
		this.categoryList();
	},
	methods: {
		handleCommand(command) {
			this.zu=command;
			if(command=='不限'){
				this.zuId=-1;
			}
			if(command=='从低到高'){
				this.zuId=0;
			}
			if(command=='从高到底'){
				this.zuId=1;
			}
			this.categoryList();
			
		 },
		tap:function(){
			if(this.keywords.length==0){
				this.$message({
				  message: '搜索内容不能为空',
				  type: 'warning',
				  offset:'100'
				});
				return;		
			}
			this.categoryList();	
		},
		tui:function(){
			
			
			
			
			
		},
		//互殴
		categoryList() {
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				keywords: this.keywords,
				priceOrder: parseInt(this.zuId),
				areaInfo: '',
				pageIndex: parseInt(this.page),
				pageSize: parseInt(10),
				id_token:this.$cookies.get('access_token')
			};

			var _this = this;
			this.$http
				.filter(data)
				.then(res => {
					console.log(res);
					_this.$_loading.close();
					if (res.errorCode == 0) {
						var xx = res.data.page;

						for (var i = 0; i < xx.length; i++) {
							xx[i].time = _this.transformTime(xx[i].interviewTime);
						}

						this.list = xx;
						this.total = res.data.total;
					}else if(res.errorCode == -20000001){
						this.$cookies.remove("access_token");
						this.$cookies.remove("name");
						this.$cookies.remove("type");
						this.$router.push({ name: 'Login', params: { id: 1} });
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
				var time = new Date(timestamp * 1000);
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
		handleCurrentChange(e) {
			console.log(e);
			this.page = e;
			this.categoryList();
		}
	}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
	margin: 40px 0 0;
}
ul {
	list-style-type: none;
	padding: 0;
}
li {
	display: inline-block;
	margin: 0 10px;
}
a {
	color: #42b983;
	text-decoration: none;
}
div {
	text-align: left;
	font-family: 'Helvetica Neue', Helvetica, 'PingFang SC';
}
.i-top {
	width: 100%;
	height: 60px;
	line-height: 60px;
	background: #fff;
	padding: 0 30px;
	box-sizing: border-box;
	display: flex;
	flex-wrap: nowrap;
}
.i-city {
	width: 80px;
	padding-right: 50px;
}
.i-city span {
	width: 32px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.i-seach {
	flex: 1;
	display: flex;
	justify-content: flex-end;
}
.i-con {
	width: 500px;
	height: 40px;
	line-height: 40px;
	box-sizing: border-box;
	border: 1px solid #0084ff;
	display: flex;
	flex-wrap: nowrap;
	margin-top: 10px;
}
.form-control {
	flex: 1;
	border: 0;
	outline: none;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(141, 146, 151, 1);
	box-sizing: border-box;
	padding: 0 15px;
}
.i-btn {
	width: 80px;
	background: #0084ff;
	text-align: center;
	color: #fff;
}
.i-wrap {
	margin-top: 10px;
	width: 100%;
	background: #fff;
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
	width: 1140px;
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
	text-align: left;
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

.el-dropdown-link {
	cursor: pointer;
	width: 32px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.el-icon-arrow-down {
	font-size: 12px;
}
el-dropdown-item {
	display: block;
}
</style>

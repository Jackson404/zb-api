<template>
	<div>
		<Header></Header>
		<div class="wrap">
			<div class="l-left">
				<el-col style="width: 100%;">
					<el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose">
						<el-submenu index="1">
							<template slot="title">
								<i class="el-icon-menu"></i>
								<span>员工管理</span>
							</template>
							<el-menu-item-group>
								<el-menu-item index="1-1" @click="go(-1)">全部员工<span style="float: right;font-size: 16px;">{{emNum}}</span></el-menu-item>
								<el-menu-item index="1-2" @click="go(-2)">待审核员工<span style="float: right;font-size: 16px;">{{reviewNum}}</span></el-menu-item>
							</el-menu-item-group>
						</el-submenu>
						<el-submenu index="2">
							<template slot="title">
								<i class="el-icon-setting"></i>
								<span>员工分组</span>
							</template>
							<el-menu-item-group>
								<el-menu-item index="'2-'+(index+1)" v-for="(item, index) in gridData" :key="index" @click="go(item.groupId)">{{ item.name }}</el-menu-item>
							</el-menu-item-group>
						</el-submenu>
					</el-menu>
				</el-col>

				<!-- <div  style="width: 220px;background: #fff;">
					<div class="l-t">管理分组</div>
					<div class="l-list">
						<div class="l-l-left  l-check">全部员工</div>
						<div class="l-l-right l-check ">4</div>
					</div>
					<div class="l-list">
						<div class="l-l-left">待审核</div>
						<div class="l-l-right">4</div>
					</div>
					<div class="l-list">
						<div class="l-l-left">分组</div>
						<div class="l-l-right">4</div>
					</div>
					<div class="l-list">
						<div class="l-l-left">默认分组</div>
						<div class="l-l-right">4</div>
					</div>	
				</div> -->

				<el-popover placement="right" width="400" trigger="click">
					<el-table :data="gridData">
						<el-table-column width="250" property="name" label="分组名称"></el-table-column>
						<el-table-column label="操作" width="150">
							<template slot-scope="scope">
								<el-button @click="handleClick(scope.row)" type="text" size="small">编辑</el-button>
								<el-button type="text" size="small" @click="handleClick1(scope.row)">删除</el-button>
							</template>
						</el-table-column>
					</el-table>
					<el-button size="medium" style="margin-top: 20px;width: 100%;font-size: 16px;border-radius: 0;" slot="reference">分组管理</el-button>

					<el-button type="primary" size="small" style="margin: 15px 0;" @click="dialogFormVisible = true">新增分组</el-button>
				</el-popover>
			</div>

			<div class="right">
				<!--	第一部分-->
				<el-table :data="tableData" style="width: 100%">
					<el-table-column prop="groupName" label="员工分组" width="150"></el-table-column>
					<el-table-column prop="realname" label="员工名称" width="150"></el-table-column>
					<el-table-column prop="orderNumMonth" label="本月接单量" width="150"></el-table-column>
					<el-table-column prop="entryNumMonth" label="本月入职人数" width="150"></el-table-column>
					<el-table-column prop="incomeMonth" label="本月收益" width="150"></el-table-column>
					<el-table-column label="操作" width="150">
						<template slot-scope="scope">
							<el-button @click="handleClickk(scope.row)" type="text" size="small" v-if="scope.row.pass==1">详情</el-button>
							<el-button type="text" size="small" @click="handleClickk1(scope.row)" v-if="scope.row.pass==1">编辑</el-button>
							<el-button @click="handleClickkk(scope.row)" type="text" size="small" v-if="scope.row.pass==0">通过</el-button>
							<el-button type="text" size="small" @click="handleClickkk1(scope.row)" v-if="scope.row.pass==0">拒绝</el-button>
						</template>
					</el-table-column>
				</el-table>
				<div style="padding: 50px 0;width: auto;margin: 0 auto;display: flex;justify-content: center;">
					<el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :total="total" ></el-pagination>
				</div>
				<!--		编辑-->

			
					
				</div>
			</div>

			<el-dialog title="新增分组" :visible.sync="dialogFormVisible">
				<el-form>
					<el-form-item label="分组名称"><el-input v-model="name" autocomplete="off" placeholder="请输入分组名称"></el-input></el-form-item>
				</el-form>
				<div slot="footer" class="dialog-footer">
					<el-button @click="dialogFormVisible = false">取 消</el-button>
					<el-button type="primary" @click="addZ">确 定</el-button>
				</div>
			</el-dialog>

			<el-dialog title="修改分组" :visible.sync="dialogFormVisible1">
				<el-form>
					<el-form-item label="分组名称"><el-input v-model="name1" autocomplete="off" placeholder="请输入分组名称"></el-input></el-form-item>
				</el-form>
				<div slot="footer" class="dialog-footer">
					<el-button @click="dialogFormVisible1 = false">取 消</el-button>
					<el-button type="primary" @click="addZ1">确 定</el-button>
				</div>
			</el-dialog>
			
			<el-dialog title="员工信息" :visible.sync="dialogFormVisible2">
				 <div style="width: 70%;display: flex;flex-wrap: wrap;justify-content: space-between;">
					 <div style="width: 100%;display: flex;padding: 10px 0;align-items: center;">
						 <div style="font-size: 16px;color:#4E565E;width: 80px;">员工分组:</div>
						 <div style="flex: 1;padding-left: 5px;">
							 <el-dropdown @command="handleCommand">
							  <span class="el-dropdown-link" style="color: red;font-size: 16px;">
								{{zu}}<i class="el-icon-arrow-down el-icon--right"></i>
							  </span>
							  <el-dropdown-menu slot="dropdown">
								<el-dropdown-item v-for="(item,index) in gridData" :key="index" :command="item.name">{{item.name}}</el-dropdown-item>
								
								
							  </el-dropdown-menu>
							</el-dropdown>
						 </div>
					 </div>
					 <div style="width: 50%;display: flex;padding: 10px 0;align-items: center;">
						<div style="font-size: 16px;color:#4E565E;width: 80px;">姓名:</div>
						<div style="flex: 1;padding-left: 5px;font-size: 16px;color:#4E565E;">{{yg.realname}}</div>
					 </div>
					 <div style="width: 50%;display: flex;padding: 10px 0;align-items: center;">
						<div style="font-size: 16px;color:#4E565E;width: 80px;">手机号码:</div>
						<div style="flex: 1;padding-left: 5px;font-size: 16px;color:#4E565E;">{{yg.realphone}}</div>
					 </div>
					 <div style="width: 100%;display: flex;padding: 10px 0;align-items: center;">
						<div style="font-size: 16px;color:#4E565E;width: 80px;">入驻时间:</div>
						<div style="flex: 1;padding-left: 5px;font-size: 16px;color:#4E565E;">{{yg.createTime}}</div>
					 </div>
				 </div>
				 
				  <div slot="footer" class="dialog-footer">
					<el-button @click="delex(yg.userId)">删除员工</el-button>
					<el-button type="primary" @click="xiu(yg.id)">确 定</el-button>
				  </div>
				</el-dialog>
				
		<!-- 详情 -->		
			
			 <el-dialog title="详情" :visible.sync="outerVisible4">
					<!-- top -->
					<div class="nav">
						<div class="n-bar " :class="[status ? 'check' : '']" @click="one()">已完成的订单</div>
						<div class="n-bar" :class="[!status ? 'check' : '']" @click="one1()">进行中的订单</div>
					</div>
					
					<div class="content">
						<div class="c-top">
							<div class="c-t-left">{{data}} 订单</div>
							<div class="c-t-right" style="cursor: pointer;">
								<el-dropdown>
									<span class="el-dropdown-link">
										2019年8月
										<i class="el-icon-arrow-down el-icon--right"></i>
									</span>
									<el-dropdown-menu slot="dropdown">
										<el-dropdown-item>2019年8月</el-dropdown-item>
										<el-dropdown-item>2019年7月</el-dropdown-item>
										<el-dropdown-item>2019年6月</el-dropdown-item>
										<el-dropdown-item>2019年5月</el-dropdown-item>
										<el-dropdown-item>2019年4月</el-dropdown-item>
									</el-dropdown-menu>
								</el-dropdown>
							</div>
						</div>
						<div class="c-t-c">
							<div class="c-t-list">收益: {{incomeMonth}}</div>
							<div class="c-t-list">已完成订单: {{entryNumMonth}}</div>
							<div class="c-t-list">总入职人数: {{orderNumMonth}}</div>
						</div>
					
						<div class="ul">
							<div class="u-list" v-for="(item, index) in list" :key="index">
								<router-link :to="{ name: 'Orderinfo', params: { id: item.orderId} }">
									<div class="u-l-top">
										<div class="u-l-t-left">员工名称：{{item.realname}}</div>
										
										<div class="u-l-t-right">{{item.createTime}}</div>
									</div>
									<!-- 中间部分 -->
									<div class="u-l-cen">
										<div class="c-l-c-top">
											<div class="c-l-c-t-left">岗位名称</div>
											<div class="c-l-c-t-right">￥:{{item.unitPrice}}元/人</div>
										</div>
										<div class="c-l-c-top" style="padding-bottom: 15px;"><div class="c-l-c-t-left">{{item.positionName}}</div></div>
					
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
					</div>
					
			  </el-dialog>	
				
		</div>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'Staff',
	components: {
		Header
	},
	data() {
		return {
			gridData: [],
			dialogFormVisible: false,
			name: '',
			dialogFormVisible1: false,
			dialogFormVisible2:false,
			name1: '',
			pageIndex: 1,
			groupId: -1,
			id: '',
			total:1,
			tableData: [],
			yg:[],
			zu:'',
			zuId:0,
			emNum:0,
			reviewNum:0,
			outerVisible4:false,
			status: true,
			isFinish:1,
			yuangong:[],
			incomeMonth:null,
			entryNumMonth:null,
			orderNumMonth:null,
			list:[],
			data:'',
		};
	},
	created() {
		this.getEmGroup();
		// this.getinfo()
	},
	methods: {
		one() {
			this.status = true;
			this.isFinish=1;
			this.handleClickk(this.yuangong);
		},
		
		one1() {
			this.status = false;
			this.isFinish=0;
			this.handleClickk(this.yuangong);
		},
		handleOpen(key, keyPath) {
			console.log(key, keyPath);
		},
		handleClose(key, keyPath) {
			console.log(key, keyPath);
		},
		getEmGroup: function() {
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token')
			};

			var _this = this;
			this.$http
				.getEmGroup(data)
				.then(res => {
					
					_this.$_loading.close();

					if (res.errorCode == 0) {
						_this.gridData = res.data.list;
						_this.emNum=res.data.emNum;
						_this.reviewNum=res.data.reviewNum;
					}
					_this.getinfo();
				})
				.catch(err => {
					console.log(err);
				});
		},
		go:function(e){
			this.groupId=e;
			this.getinfo();
		},
		handleCurrentChange(e) {
			console.log(e);
			this.pageIndex = e;
			this.getinfo();
		},
		getinfo: function() {
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				groupId: parseInt(this.groupId),
				pageIndex: parseInt(this.pageIndex),
				pageSize: 10
			};

			var _this = this;
			this.$http
				.review(data)
				.then(res => {
					_this.$_loading.close();
					console.log('列表');
					console.log(res);
					if (res.errorCode == 0) {
						_this.tableData = res.data.data;
						
						_this.total=res.data.total;
						
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		// 新增分组接口
		addZ() {
			this.dialogFormVisible = false;
			if (this.name < 1) {
				this.$message({
					message: '组名不能为空',
					type: 'warning',
					offset: '100'
				});
				return;
			}

			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				groupName: this.name
			};

			var _this = this;
			this.$http
				.addEmGroup(data)
				.then(res => {
				
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						setTimeout(function() {
							_this.$message({
								message: '新增成功',
								type: 'success',
								offset: '100',
								duration: 3000
							});
						}, 1000);
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		
		handleCommand(command) {
			this.zu=command;
			for(var i=0;i<this.gridData.length;i++){
				
				if(this.gridData[i].name==command){
					this.zuId=this.gridData[i].groupId;
					break;
				}
				
			}
			
		 },
		// 编辑用户组
		handleClick(e) {
			console.log(e);
			this.dialogFormVisible1 = true;
			this.name1 = e.name;
			this.id = e.groupId;
		},
		
		
		
		//员工详情
		handleClickk(e){
			
			console.log(e);
			this.yuangong=e;
			this.outerVisible4 = true;	
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
				orderDate:'2019-08',
				isFinish:this.isFinish,
				userId: parseInt(e.userId),
				
			};
			
			var _this = this;
			this.$http
				.getEmUserDetail(data)
				.then(res => {
					console.log(12344);
					console.log(res);
					_this.$_loading.close();
					
					if (res.errorCode == 0) {
						_this.orderNumMonth=res.data.orderNumMonth;
						_this.incomeMonth=res.data.incomeMonth;
						_this.entryNumMonth=res.data.entryNumMonth;
						_this.list=res.data.list;
						_this.data=res.data.recOrderYear+'-'+res.data.recOrderMonth;
						console.log(33333);
						console.log(_this.list)
					}
				})
				.catch(err => {
					console.log(err);
				});
			
			
			
		},
		//编辑员工信息
		handleClickk1(e){
			console.log(e);
			this.dialogFormVisible2 = true;	
			this.yg=e;
			this.zu=e.groupName;
		},
		//修改用户所在组
		xiu(e){
			this.dialogFormVisible2=false;
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				certId: parseInt(e),
				groupId: parseInt(this.zuId)
			};
			
			var _this = this;
			this.$http
				.changeEmGroupByEpUser(data)
				.then(res => {
					
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						_this.$message({
							message: '修改成功',
							type: 'success',
							offset: '100',
							duration: 3000
						});
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		
		// 修改分组接口
		addZ1() {
			this.dialogFormVisible1 = false;
			if (this.name1 < 1) {
				this.$message({
					message: '组名不能为空',
					type: 'warning',
					offset: '100'
				});
				return;
			}

			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				groupName: this.name1,
				groupId: parseInt(this.id)
			};

			var _this = this;
			this.$http
				.editEmGroup(data)
				.then(res => {
					
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						_this.$message({
							message: '修改成功',
							type: 'success',
							offset: '100',
							duration: 3000
						});
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		// 删除用户组
		handleClick1(e) {
			this.$confirm('此操作将删除该用户分组, 是否继续?', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			})
				.then(() => {
					this.delEmGroup(e);
				})
				.catch(() => {
					
				});
		},
		//删除员工
		delex(e){
			this.dialogFormVisible2=false;
			this.$confirm('此操作将删除该员工信息, 是否继续?', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			})
				.then(() => {
					this.delEmUser(e);
				})
				.catch(() => {
					
				});
		},
		delEmUser(e){
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				emUserId:parseInt(e),
			};
			
			var _this = this;
			this.$http
				.delEmUser(data)
				.then(res => {
					console.log(654321);
					console.log(res);
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						_this.$message({
							message: '删除成功',
							type: 'success',
							offset: '100',
							duration: 3000
						});
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		//通过用户申请
		handleClickkk(e){
				this.$confirm('此操作将通过用户审核, 是否继续?', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			})
				.then(() => {
					this.review(e,1);
				})
				.catch(() => {
					
				});
		},
		//拒绝用户申请
		handleClickkk1(e){
				this.$confirm('此操作将拒绝用户审核, 是否继续?', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			})
				.then(() => {
					this.review(e,-1);
				})
				.catch(() => {
					
				});
		},
		//审核员工
		review:function(e,i){
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				certId: parseInt(e.id),
				pass:parseInt(i),
			};
			
			var _this = this;
			this.$http
				.review1(data)
				.then(res => {
					
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						if(i==1){
							_this.$message({
								message: '审核成功',
								type: 'success',
								offset: '100',
								duration: 3000
							});
						}else{
							_this.$message({
								message: '拒绝成功',
								type: 'success',
								offset: '100',
								duration: 3000
							});	
						}
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		
		// 删除用户分组
		delEmGroup(e) {
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				groupId: parseInt(e.groupId)
			};

			var _this = this;
			this.$http
				.delEmGroup(data)
				.then(res => {
					
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						setTimeout(function() {
							_this.$message({
								message: '删除成功',
								type: 'success',
								offset: '100',
								duration: 3000
							});
						}, 1000);
					}
				})
				.catch(err => {
					console.log(err);
				});
		}
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
	margin: 0 auto;
	display: flex;
	flex-wrap: nowrap;
	justify-content: space-between;
	margin-top: 110px !important;
	background: #f0f2f5;
}
.l-left {
	width: 220px !important;
	height: 300px;
}

.right {
	width: 950px;
	background: #fff;
	box-sizing: border-box;
	padding: 0 15px;
}
.l-t {
	width: 220px;
	height: 50px;
	line-height: 50px;
	background: rgba(0, 132, 255, 1);
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	text-align: center;
}
.l-list {
	width: 100%;
	height: 45px;
	line-height: 45px;
	display: flex;
	align-content: center;
	flex-wrap: nowrap;
	justify-content: space-between;

	color: #4e565e;
}
.l-check {
	background: #7fc1ff;
	color: #fff !important;
}
.l-l-left {
	width: 50%;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	box-sizing: border-box;
	padding-left: 20px;
	/*		color:rgba(255,255,255,1);*/
	color: #4e565e;
}
.l-l-right {
	width: 50%;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: #4e565e;
	box-sizing: border-box;
	padding-right: 20px;
	text-align: right;
}
.r-list {
	width: 100%;
	height: 45px;
	line-height: 45px;
	display: flex;
	flex-wrap: nowrap;
	background: #0084ff;
}
.r-l-list {
	flex: 1;
	text-align: center;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	text-align: center;
}
.r-c-con {
	width: 100%;
	height: 45px;
	line-height: 45px;
	display: flex;
	flex-wrap: nowrap;
	box-sizing: border-box;
	border-bottom: 1px solid rgba(221, 222, 224, 1);
}

.r-c-con .r-l-list {
	color: #4e565e;
}
.s-list {
	width: 50%;
	height: 50px;
	line-height: 50px;
	display: flex;
}
.s-name {
	font-size: 16px;
	font-family: Adobe Heiti Std R;
	font-weight: normal;
	color: rgba(78, 86, 94, 1);
}
.s-r-name {
	flex: 1;
	padding-left: 10px;
	font-size: 16px;
	font-family: Adobe Heiti Std R;
	font-weight: normal;
	color: rgba(78, 86, 94, 1);
}
.cell {
	font-size: 18px !important;
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
	padding: 20px;
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
.el-dialog__body{
	padding: 30px 0 !important;
}
</style>

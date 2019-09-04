<template>
	<div>
		<Header></Header>
		<div class="wrap">
			<div class="l-left">
				<!-- 简历分组 -->
				<el-col style="width: 100%;">
					<el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose">
						<el-submenu index="1">
							<template slot="title">
								<i class="el-icon-menu"></i>
								<span>简历分组</span>
							</template>
							<el-menu-item-group>
								<el-menu-item index="'1-'+(index+1)" v-for="(item, index) in gridData" :key="index" @click="go(item.id)">{{ item.name }}<span style="float: right;font-size: 16px;">{{item.count}}</span></el-menu-item>
							</el-menu-item-group>
						</el-submenu>
						
					</el-menu>
				</el-col>

				
				<!-- 操作分组 增删改 -->
				<el-popover placement="right" width="400" trigger="click">
					<el-table :data="gridData1">
						<el-table-column v-if="name != '未分组'" width="250" property="name" label="分组名称"></el-table-column>
						<el-table-column label="操作" width="150">

							<template slot-scope="scope" v-if="scope.row.isEdit">
								<el-button :disabled="scope.row.name == '未分组'" @click="handleClick(scope.row)" type="text" size="small">编辑</el-button>
								<el-button :disabled="scope.row.name == '未分组'" type="text" size="small" @click="handleClick1(scope.row)">删除</el-button>
							</template>
						</el-table-column>
					</el-table>
					<el-button size="medium" style="margin-top: 20px;width: 100%;font-size: 16px;border-radius: 0;" slot="reference">分组管理</el-button>

					<el-button type="primary" size="small" style="margin: 15px 0;" @click="dialogFormVisible = true">新增分组</el-button>
				</el-popover>
			</div>


			<div class="right" style="padding-bottom: 20px;">
				<!--	列表-->
				<el-table
					ref="multipleTable"
					:data="tableData"
					tooltip-effect="dark"
					style="width: 100%"
					@selection-change="handleSelectionChange"
					@row-click="onRowClick"
				>
					<el-table-column type="selection" width="55"></el-table-column>
				
					
					<el-table-column prop="name" label="姓名" width="100"></el-table-column>
										
					<el-table-column prop="age" label="年龄" width="50"></el-table-column>
					
					<el-table-column prop="phone" label="联系方式" width="150"></el-table-column>
					<el-table-column prop="educationName" label="学历" width="80"></el-table-column>
					<el-table-column prop="workYear" label="工作经验" width="120"></el-table-column>
					<el-table-column prop="exPosition" label="期望职位" width="200"></el-table-column>
					<el-table-column prop="school" label="居住地" width="150"></el-table-column>
					
				
					<!-- <el-table-column label="操作" width="100">
						<template slot-scope="scope">
							<el-button @click="handleClick2(scope.row)" type="text" size="small">查看</el-button>
							
						</template>
					</el-table-column> -->
				</el-table>
				<div style="margin-top: 20px;display: flex;flex-wrap: nowrap;">
					<!-- 移动 -->
					<el-button  @click="toggleSelection(tableData)" style="padding: 12px 40px;">全选</el-button>
					<el-dropdown style="margin-left: 10px;" @command="check">
					  <el-button style="padding: 12px 30px;">
						移动至<i class="el-icon-arrow-down el-icon--right" style="padding-left: 10px;"></i>
					  </el-button>
					  <el-dropdown-menu slot="dropdown">
						<el-dropdown-item v-for="(item, index) in gridData" :key="index" :command="index">{{ item.name }}</el-dropdown-item>
					  </el-dropdown-menu>
					</el-dropdown>
					<!-- 分页 -->
					<!-- <el-button @click="toggleSelection()">取消选择</el-button> -->
					<div style="width: auto;margin: 0 auto;display: flex;justify-content: flex-end;">
						<el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :total="total" ></el-pagination>
					</div>
				</div>
				
				<!--		编辑-->

			
					
				</div>
			</div>
			
			<!-- 新增分组 -->
			<el-dialog title="新增分组" :visible.sync="dialogFormVisible">
				<el-form>
					<el-form-item label="分组名称"><el-input v-model="name" autocomplete="off" placeholder="请输入分组名称"></el-input></el-form-item>
				</el-form>
				<div slot="footer" class="dialog-footer">
					<el-button @click="dialogFormVisible = false">取 消</el-button>
					<el-button type="primary" @click="addZ">确 定</el-button>
				</div>
			</el-dialog>
			<!-- 修改分组 -->
			<el-dialog title="修改分组" :visible.sync="dialogFormVisible1">
				<el-form>
					<el-form-item label="分组名称"><el-input v-model="name1" autocomplete="off" placeholder="请输入分组名称"></el-input></el-form-item>
				</el-form>
				<div slot="footer" class="dialog-footer">
					<el-button @click="dialogFormVisible1 = false">取 消</el-button>
					<el-button type="primary" @click="addZ1">确 定</el-button>
				</div>
			</el-dialog>
			
			<el-dialog title="" :visible.sync="dialogTableVisible">
				<div style="width: 100%;box-sizing: border-box;padding: 0px 20px;">
					<div style="height: 40px;line-height: 40px;font-size: 18px;color: #333;">{{listOne.name}}</div>
					<div style="display: flex;flex-wrap: nowrap;align-items: center;padding: 5px 0;">
						<div style="font-size: 16px;color: #4E565E;" v-if="listOne.gender==1">男</div>
						<div style="font-size: 16px;color: #4E565E;" v-else>女</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">{{listOne.birthYear}}</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">{{listOne.workYear}}</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">{{listOne.education}}</div>
					</div>
					<div style="width: 100%;padding: 10px 0 20px 0;display: flex;border-bottom: 1px solid #FAFAFA;">
						<img src="../../assets/resume-phone.png" alt="" style="width: 15px;display: block;height: 15px;" />
						<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">{{listOne.phone}}</div>
					</div>
					<div style="width: 100%;height: 40px;line-height: 40px;font-size: 18px;color: #333;padding-top: 15px;">求职意向</div>
					<div style="width: 100%;display: flex;flex-wrap: wrap;justify-content: space-between;padding: 10px 0; 25px 0;border-bottom: 1px solid #FAFAFA;">
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px; ">
							<img src="../../assets/a13.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;flex: 1;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">期望职位：{{listOne.exPosition}}</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a10.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">工作性质：</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a1.png" alt="" style="width: 13px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">期望城市：{{listOne.exCity}}</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a17.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">期望薪资：{{listOne.exSalary}}</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a2.png" alt="" style="width: 13px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">目前状况：</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a5.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">到岗时间：</div>
						</div>
					</div>
					<div style="width: 100%;height: 40px;line-height: 40px;font-size: 18px;color: #333;padding-top: 15px;">简历信息</div>
					<div style="width: 100%;display: flex;padding-bottom: 10px;">
						<img src="../../assets/a7.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
						<div style="flex: 1;box-sizing: border-box;padding-left: 20px;">
							<div style="height:35px;line-height: 35px;font-size: 16px;color: #4E565E;">自我评价</div>
							<div style="padding: 4px 0;font-size: 16px;color: #4E565E;line-height: 25px;">
									无评价
							</div>
						</div>
					</div>
					<div style="width: 100%;display: flex;padding-bottom: 10px;">
						<img src="../../assets/resume-experience@2x.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
						<div style="flex: 1;box-sizing: border-box;padding-left: 20px;">
							<div style="height:35px;line-height: 35px;font-size: 16px;color: #4E565E;">技能描述</div>
							<div style="padding: 4px 0;font-size: 16px;color: #4E565E;line-height: 25px;">
									无描述
							</div>
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
			gridData1:[],
			dialogFormVisible: false,
			name: '',
			dialogFormVisible1: false,
			dialogTableVisible: false,
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
			value2:'2019-08',
			listOne:[],
			multipleSelection:[],
			resumeCateId:-1
			};
	},
	created() {
		if (!this.$cookies.isKey('access_token')) {
			this.$router.push({ name: 'Login', params: { userId: '123' } });
		}
		this.getEmGroup();
		// this.getinfo()
	},
	methods: {
		handleSelectionChange(val) {
			console.log(val);
			this.multipleSelection = val;
		},
		onRowClick: function(row, rowIndex) {
			console.log(row);
			console.log(rowIndex);
		},
		handleClick2(row) {
			console.log(row);
			this.dialogTableVisible = true;
			this.listOne = row;
		},
		toggleSelection(rows) {
			if (rows) {
				rows.forEach(row => {
					this.$refs.multipleTable.toggleRowSelection(row);
				});
			} else {
				this.$refs.multipleTable.clearSelection();
			}
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
			console.log('执行')
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				// resumeCateId:parseInt(this.resumeCateId),
			};

			var _this = this;
			this.$http
				.getEpResumeListByCate1(data)
				.then(res => {
					
					_this.$_loading.close();
					console.log('简历分类')
					console.log(res)	
					if (res.errorCode == 0) {
						_this.gridData1=[];
						_this.gridData = res.data.list;
						
						for(var i=0;i<res.data.list.length;i++){
							if(res.data.list[i].name=='未分组'){
								
							}else if(res.data.list[i].name=='投递'){
								
							}else{
								_this.gridData1.push(res.data.list[i])
							}
						}
						
						// _this.emNum=res.data.emNum;
						// _this.reviewNum=res.data.reviewNum;
					}
					_this.getinfo();
				})
				.catch(err => {
					console.log(err);
				});
		},
		go:function(e){
			console.log(e)
			this.resumeCateId=e;
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
				resumeCateId: parseInt(this.resumeCateId),
				pageIndex: parseInt(this.pageIndex),
				pageSize: 10
			};

			var _this = this;
			this.$http
				.getEpResumeListByCate(data)
				.then(res => {
					_this.$_loading.close();
					console.log('列表');
					console.log(res);
					if (res.errorCode == 0) {
						_this.tableData = res.data.page.data;
						console.log(_this.tableData)
						_this.total=res.data.page.total;
						
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
				name: this.name
			};

			var _this = this;
			this.$http
				.addResumeCate(data)
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
			this.id = e.id;
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
				name: this.name1,
				resumeCateId: parseInt(this.id)
			};

			var _this = this;
			this.$http
				.editResumeCate(data)
				.then(res => {
					console.log('修改');
					console.log(res);
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
				resumeCateId: parseInt(e.id)
			};

			var _this = this;
			this.$http
				.delResumeCate(data)
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
		},
		//修改简历分类
		check(command){
			
			console.log(command)
			var list=this.gridData[command];
			
			//判断是否选择简历
			if (this.multipleSelection.length == 0) {
				this.$message({
					message: '请选择下载简历',
					type: 'warning',
					offset: '100'
				});
				return;
			}
			
			var num=this.multipleSelection;
			var arr='';
			for(var i=0;i<num.length;i++){
				if(i==num.length-1){
					var a=num[i].epResumeRecordId;
					arr=arr+a
				}else{
					var a=num[i].epResumeRecordId+',';
					arr=arr+a
				}	
			}
			//开始更换分组
			
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				resumeCateId: parseInt(list.id),
				epResumeRecordIds:arr,
				epResumeRecordId:parseInt(this.multipleSelection[0].id)
			};
			
			var _this = this;
			this.$http
				.moveMultiResumesToCate(data)
				.then(res => {
					console.log(res);
					console.log(123456);
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						setTimeout(function() {
							_this.$message({
								message: '移动成功',
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

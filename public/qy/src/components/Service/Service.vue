<template>
	<div>
		<Header></Header>
		<div class="wrap">
			

				<el-container>
					<el-aside width="250px">
						<el-menu :default-openeds="['1', '3']">
							<el-submenu index="1">
								<template slot="title" style="background:#0084FF !important;color: #fff !important;">
									简历分类
								</template>
								<el-menu-item-group>
									<el-menu-item index="1-1">
										全部简历
										<span style="float: right;">4</span>
									</el-menu-item>
									<el-menu-item index="1-2">
										辅警
										<span style="float: right;">0</span>
									</el-menu-item>
									<el-menu-item index="1-3">
										驾驶员
										<span style="float: right;">4</span>
									</el-menu-item>
								</el-menu-item-group>
							</el-submenu>
						</el-menu>
					</el-aside>

					<el-container>
						<el-main>
							<el-table
								ref="multipleTable"
								:data="tableData"
								tooltip-effect="dark"
								style="width: 100%"
								@selection-change="handleSelectionChange"
								@row-click="onRowClick"
							>
								<el-table-column type="selection" width="55"></el-table-column>

								<el-table-column prop="name" label="姓名" width="120"></el-table-column>
								<el-table-column prop="sex==0?'女':'男'" label="性别" width="120"></el-table-column>
								<el-table-column prop="age" label="年龄" width="120"></el-table-column>
								<el-table-column prop="educationName" label="学历" width="120"></el-table-column>
								<el-table-column prop="school" label="居住地" show-overflow-tooltip></el-table-column>
								<el-table-column prop="workYear" label="工作经验" width="120"></el-table-column>
								<el-table-column prop="exPosition" label="目前职位" show-overflow-tooltip></el-table-column>
								<el-table-column prop="curStatus" label="状态" show-overflow-tooltip></el-table-column>

								<el-table-column label="操作" width="100">
									<template slot-scope="scope">
										<el-button @click="handleClick(scope.row)" type="text" size="small">查看</el-button>
										<el-button type="text" size="small">编辑</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div style="margin-top: 20px">
								<!-- <el-button @click="toggleSelection([tableData[1], tableData[2]])">切换第二、第三行的选中状态</el-button> -->
							
								<el-button @click="toggleSelection(tableData)" style="padding: 12px 40px;">全选</el-button>
								<el-button type="primary" plain style="padding: 12px 40px;">下载</el-button>
								<!-- <el-button @click="toggleSelection()">取消选择</el-button> -->
							</div>
						</el-main>
					</el-container>
				</el-container>
		

		</div>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'Resume',
	components: {
		Header
	},
	data() {
		return {
			status: true,
			tableData: [
				
			],
			
			multipleSelection: [],
			
			value: '',
			
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
		
		categoryList() {
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				posKey:this.posKey,
				wxWorkLocation: '',
				workExp: this.radio,
				educationName: this.educationName,
				minAge: this.minAge,
				maxAge: this.maxAge,
				sex: this.radio1,
				pageIndex: parseInt(this.page),
				pageSize: parseInt(10)
			};

			var _this = this;
			this.$http
				.resume(data)
				.then(res => {
					console.log(res);
					_this.$_loading.close();
					if (res.errorCode == 0) {
						var xx = res.data.page;
						for (var i = 0; i < xx.length; i++) {
							if (xx[i].sex == 0) {
								xx[i].sex = '女';
							} else {
								xx[i].sex = '男';
							}
							xx[i].iid = i;
						}

						this.tableData = xx;

						this.total = res.data.total;
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		onRowClick: function(row, rowIndex) {
			console.log(row);
			console.log(rowIndex);
		},
		handleCurrentChange(e) {
			console.log(e);
			this.page = e;
			this.categoryList();
		},
		
		handleClick(row) {
			console.log(row);
			this.dialogTableVisible = true;
			this.listOne = row;
		},
		handleSelectionChange(val) {
			console.log(val);
			this.multipleSelection = val;
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
	box-sizing: border-box;
	margin: 0 auto;
	margin-top: 110px !important;
	min-height: 800px;
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
	border: 0;
}
.check {
	background: #fff;
	color: #0084ff;
}
.list {
	width: 100%;
	box-sizing: border-box;
	padding: 20px 0;
}
.content {
	width: 100%;
	box-sizing: border-box;
	padding: 10px 50px 30px 50px;
}
.content1 {
	width: 100%;
	box-sizing: border-box;
	padding-top: 40px;
}
.show-list {
	width: 100%;
	height: 60px;
	line-height: 60px;
	display: flex;
	flex-wrap: nowrap;
	border-bottom: 1px solid #dddddd;
	align-items: center;
}
.show-title {
	width: 150px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.show-name {
	flex: 1;
	display: flex;
	flex-wrap: nowrap;
	height: 60px;
	line-height: 60px;
	align-items: center;
}

.el-menu-item-group__title {
	display: none !important;
}
</style>

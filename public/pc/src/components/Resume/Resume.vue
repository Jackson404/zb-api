<template>
	<div>
		<Header></Header>
		<div class="wrap">
			<div class="nav">
				<div class="n-bar " :class="[status ? 'check' : '']" @click="one()">简历搜索</div>
				<div class="n-bar" :class="[!status ? 'check' : '']" @click="one1()">简历分类</div>
			</div>

			<div class="content" v-if="status">
				<div class="show-list">
					<div class="show-title">关键词搜索：</div>
					<div class="show-name">
						<el-input style="width: 300px;;" v-model="input" placeholder="请输入您要输入的内容"></el-input>
						<div style="width: 20px;"></div>
						<el-button type="primary">搜索</el-button>
					</div>
				</div>
				<div class="show-list">
					<div class="show-title">期望地点：</div>
					<div class="show-name">
						<el-select v-model="value" placeholder="不限">
							<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value"></el-option>
						</el-select>
					</div>
				</div>

				<div class="show-list">
					<div class="show-title">工作经验：</div>
					<div class="show-name">
						<el-radio-group v-model="radio">
							<el-radio :label="3">不限</el-radio>
							<el-radio :label="6">1-3年</el-radio>
							<el-radio :label="9">3-5年</el-radio>
						</el-radio-group>
					</div>
				</div>

				<div class="show-list">
					<div class="show-title">学历水平：</div>
					<div class="show-name">
						<el-radio-group v-model="radio1">
							<el-radio :label="3">不限</el-radio>
							<el-radio :label="6">高中及以下</el-radio>
							<el-radio :label="9">大专</el-radio>
							<el-radio :label="12">本科</el-radio>
							<el-radio :label="15">研究生</el-radio>
						</el-radio-group>
					</div>
				</div>

				<div class="show-list">
					<div class="show-title">年龄范围：</div>
					<div class="show-name">
						<el-radio-group v-model="radio2">
							<el-radio :label="3">不限</el-radio>
							<el-radio :label="6">16-20岁</el-radio>
							<el-radio :label="9">20-30岁</el-radio>
							<el-radio :label="12">30-40岁</el-radio>
							<el-radio :label="15">40-50岁</el-radio>
						</el-radio-group>
					</div>
				</div>

				<div class="list">
					<el-table ref="multipleTable" :data="tableData" tooltip-effect="dark" style="width: 100%" @selection-change="handleSelectionChange">
						<el-table-column type="selection" width="55"></el-table-column>

						<el-table-column prop="name" label="姓名" width="120"></el-table-column>
						<el-table-column prop="sex" label="性别" width="120"></el-table-column>
						<el-table-column prop="age" label="年龄" width="120"></el-table-column>
						<el-table-column prop="educationName" label="学历" width="120"></el-table-column>
						<el-table-column prop="school" label="居住地" show-overflow-tooltip></el-table-column>
						<el-table-column prop="workYear" label="工作经验" width="120"></el-table-column>
						<el-table-column prop="exPosition" label="目前职位" show-overflow-tooltip></el-table-column>
						<el-table-column prop="curStatus" label="状态" show-overflow-tooltip></el-table-column>

						<el-table-column label="操作" width="100">
							<template slot-scope="scope">
								<el-button type="text" size="small" @click="handleClick(scope.row)" :data-iid="iid">查看</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div style="margin-top: 20px;display: flex;flex-wrap: nowrap;align-items: center;">
						<!-- <el-button @click="toggleSelection([tableData[1], tableData[2]])">切换第二、第三行的选中状态</el-button> -->
						<el-button @click="toggleSelection(tableData)" style="padding: 12px 40px;">全选</el-button>
						<el-button type="primary" plain style="padding: 12px 40px;">下载</el-button>
						<!-- <el-button @click="toggleSelection()">取消选择</el-button> -->
						<div style="flex: 1;display: flex;justify-content: flex-end;">
							<el-pagination
								background
								@current-change="handleCurrentChange"
								layout="prev, pager, next"
								:total="total"
								:current-page.sync="currentPage"
							></el-pagination>
						</div>
					</div>
				</div>
			</div>

			<div class="content1" v-else>
				<!-- <el-container>
					<el-aside width="270px">Aside</el-aside>
					<el-container>
					  <el-main>Main</el-main>
					</el-container>
				  </el-container> -->

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

			<el-dialog title="" :visible.sync="dialogTableVisible">
				<div style="width: 100%;box-sizing: border-box;padding: 0px 20px;">
					<div style="height: 40px;line-height: 40px;font-size: 18px;color: #333;">张校长</div>
					<div style="display: flex;flex-wrap: nowrap;align-items: center;padding: 5px 0;">
						<div style="font-size: 16px;color: #4E565E;">男</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">27岁</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">4年</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">本科</div>
					</div>
					<div style="width: 100%;padding: 10px 0 20px 0;display: flex;border-bottom: 1px solid #FAFAFA;">
						<img src="../../assets/resume-phone.png" alt="" style="width: 15px;display: block;height: 15px;" />
						<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">18392078783</div>
					</div>
					<div style="width: 100%;height: 40px;line-height: 40px;font-size: 18px;color: #333;padding-top: 15px;">求职意向</div>
					<div style="width: 100%;display: flex;flex-wrap: wrap;justify-content: space-between;padding: 10px 0; 25px 0;border-bottom: 1px solid #FAFAFA;">
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a13.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">期望职位：辅警</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a10.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">工作性质：全职</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a1.png" alt="" style="width: 13px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">期望城市：上海</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a17.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">期望薪资：6000-10000元</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a2.png" alt="" style="width: 13px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">目前状况：已离职</div>
						</div>
						<div style="width: 50%;height: 35px;display: flex;align-content: center;line-height: 35px;">
							<img src="../../assets/a5.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
							<div style="padding-left: 20px;font-size: 16px;color: #4E565E;">到岗时间：随时到岗</div>
						</div>
					</div>
					<div style="width: 100%;height: 40px;line-height: 40px;font-size: 18px;color: #333;padding-top: 15px;">简历信息</div>
					<div style="width: 100%;display: flex;padding-bottom: 10px;">
						<img src="../../assets/a7.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
						<div style="flex: 1;box-sizing: border-box;padding-left: 20px;">
							<div style="height:35px;line-height: 35px;font-size: 16px;color: #4E565E;">自我评价</div>
							<div style="padding: 4px 0;font-size: 16px;color: #4E565E;line-height: 25px;">
								自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最...
							</div>
						</div>
					</div>
					<div style="width: 100%;display: flex;padding-bottom: 10px;">
						<img src="../../assets/resume-experience@2x.png" alt="" style="width: 15px;display: block;height: 15px;margin-top: 10px;" />
						<div style="flex: 1;box-sizing: border-box;padding-left: 20px;">
							<div style="height:35px;line-height: 35px;font-size: 16px;color: #4E565E;">技能描述</div>
							<div style="padding: 4px 0;font-size: 16px;color: #4E565E;line-height: 25px;">
								自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最多显示三行，自我评价最...
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
	name: 'Resume',
	components: {
		Header
	},
	data() {
		return {
			status: true,
			tableData: [
				// {
				// 	date: '2016-05-03',
				// 	name: '王小虎',
				// 	address: '上海市普陀区金沙江路 1518 弄',
				// 	sex: '男',
				// 	age: 30,
				// 	xueli: '本科',
				// 	work: '3年',
				// 	zhi: '测试',
				// 	tai: '在职'
				// },
			],
			multipleSelection: [],
			options: [
				{
					value: '选项1',
					label: '上海'
				},
				{
					value: '选项2',
					label: '北京'
				},
				{
					value: '选项3',
					label: '深圳'
				},
				{
					value: '选项4',
					label: '重庆'
				},
				{
					value: '选项5',
					label: '西安'
				}
			],
			value: '',
			radio: 3,
			radio1: 3,
			radio2: 3,
			input: '',
			total: '',
			dialogTableVisible: false,
			checkindex: '',
			listOne: []
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
				posKey: '',
				wxWorkLocation: '',
				workExp: '',
				educationName: '',
				minAge: '',
				maxAge: '',
				sex: '',
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
		toggleSelection(rows) {
			if (rows) {
				rows.forEach(row => {
					this.$refs.multipleTable.toggleRowSelection(row);
				});
			} else {
				this.$refs.multipleTable.clearSelection();
			}
		},
		goAch: function(e) {
			this.dialogTableVisible = true;
			this.checkindex = e;
			console.log(e);
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
		one() {
			this.status = true;
		},
		one1() {
			this.status = false;
		}
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

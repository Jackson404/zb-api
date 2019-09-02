<template>
	<div>
		<Header></Header>
		<div class="wrap">
			

			<div class="content" >
				<!-- 关键词搜索 -->
				<div class="show-list">
					<div class="show-title">关键词搜索：</div>
					<div class="show-name">
						<el-input style="width: 300px;;" v-model="posKey" placeholder="请输入您要输入的内容"></el-input>
						<div style="width: 20px;"></div>
						<el-button type="primary" @click="search">搜索</el-button>
						<el-button size="medium" @click="des">清空搜索条件</el-button>
					</div>
				</div>
				
				<!-- 工作经验 -->
				<div class="show-list">
					<div class="show-title">工作经验：</div>
					<div class="show-name">
						<el-radio-group v-model="radio" @change="work">
							<el-radio label="不限">不限</el-radio>
							<el-radio label="1-3年">1-3年</el-radio>
							<el-radio label="3-5年">3-5年</el-radio>
							<el-radio label="5-10年">5-10年</el-radio>
						</el-radio-group>
					</div>
				</div>
				<!-- 学历水平 -->
				<div class="show-list">
					<div class="show-title">学历水平：</div>
					<div class="show-name">
						 <!-- <el-checkbox-group v-model="checkList" @change="xueli"> -->
						 <el-checkbox-group v-model="checkList" >	 
							<el-checkbox label="不限" @change="xx"></el-checkbox>
							<el-checkbox label="高中及以下" @change="xx1"></el-checkbox>
							<el-checkbox label="大专" @change="xx1"></el-checkbox>
							<el-checkbox label="本科及以上" @change="xx1"></el-checkbox>
							
						 </el-checkbox-group>
					</div>
				</div>
				<!-- 性别 -->
				<div class="show-list">
					<div class="show-title">性别：</div>
					<div class="show-name">
						<el-radio-group v-model="radio1" @change="sexone">
							<el-radio label="不限">不限</el-radio>
							<el-radio label="男">男</el-radio>
							<el-radio label="女">女</el-radio>
							
						</el-radio-group>
					</div>
				</div>
				
				
				
				<!-- 年龄范围 -->
				<div class="show-list">
					<div class="show-title">年龄范围：</div>
					<div class="show-name" style="display: flex;align-items: center;">
						<el-input v-model="minAge" placeholder="请输入年龄" style="width: 200px;" @blur="gose"></el-input>
						<div style="width: 40px;height: 1px;background: #8492A6;margin: 0 20px;"></div>
						<el-input v-model="maxAge" placeholder="请输入年龄" style="width: 200px;" @blur="gose"></el-input>
					</div>
				</div>

				<div class="list">
					<!-- 列表 -->
					<el-table ref="multipleTable" :data="tableData" tooltip-effect="dark" style="width: 100%" @selection-change="handleSelectionChange">
						<el-table-column type="selection" width="55"></el-table-column>

						<el-table-column prop="name" label="姓名" width="100"></el-table-column>
					
						<el-table-column prop="birth" label="年龄" width="60"></el-table-column>
						<el-table-column prop="educationName" label="学历" width="100"></el-table-column>
						<el-table-column prop="workYear" label="工作经验" width="120"></el-table-column>
						<el-table-column prop="exPosition" label="期望职位" width="200"></el-table-column>
						<el-table-column prop="school" label="居住地" width="200"></el-table-column>
						
						<el-table-column prop="exCity" label="地区" width="250"></el-table-column>

						<!-- <el-table-column label="操作" width="100">
							<template slot-scope="scope">
								<el-button type="text" size="small" @click="handleClick(scope.row)">查看</el-button>
							</template>
						</el-table-column> -->
					</el-table>
					<!-- 操作 移动 全选 -->
					<div style="margin-top: 20px;display: flex;flex-wrap: nowrap;align-items: center;">
						<!-- <el-button @click="toggleSelection([tableData[1], tableData[2]])">切换第二、第三行的选中状态</el-button> -->
						<el-button @click="toggleSelection(tableData)" style="padding: 12px 40px;">全选</el-button>
						
						<el-dropdown style="margin-left: 10px;" @command="check">
						  <el-button type="primary">
							下载至 <i class="el-icon-arrow-down el-icon--right"></i>
						  </el-button>
						  <el-dropdown-menu slot="dropdown">
							<el-dropdown-item v-for="(item, index) in gridData" :key="index" :command="index">{{ item.name }}</el-dropdown-item>
						  </el-dropdown-menu>
						</el-dropdown>
						<!-- <el-button @click="toggleSelection()">取消选择</el-button> -->
						<div style="flex: 1;display: flex;justify-content: flex-end;">
							<!-- 分页 -->
							<el-pagination
								background
								@current-change="handleCurrentChange"
								layout="prev, pager, next"
								:total="total"
								
							></el-pagination>
						</div>
					</div>
				</div>
			</div>

		

			<el-dialog title="" :visible.sync="dialogTableVisible">
				<div style="width: 100%;box-sizing: border-box;padding: 0px 20px;">
					<div style="height: 40px;line-height: 40px;font-size: 18px;color: #333;">{{listOne.name}}</div>
					<div style="display: flex;flex-wrap: nowrap;align-items: center;padding: 5px 0;">
						<div style="font-size: 16px;color: #4E565E;">{{listOne.sex}}</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">{{listOne.birth}}</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">{{listOne.workYear}}</div>
						<div style="display: inline-block;padding: 0 15px;margin-top: 2px;"><span style="height: 12px;width: 2px;background: #666;display: block;"></span></div>
						<div style="font-size: 16px;color: #4E565E;">{{listOne.educationName}}</div>
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
		<div style="height: 50px;"></div>
		
		
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
			tableData: [],
			 checkList: ['不限'],
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
			radio: '不限',
			radio1: '不限',
			radio2: 3,
			posKey: '',
			minAge:'',
			maxAge:'',
			total: 0,
			dialogTableVisible: false,
			checkindex: '',
			listOne: [],
			educationName:'',
			page:1,
			gridData:[],
		};
	},
	created() {
		
		if (!this.$cookies.isKey('access_token')) {
			this.$router.push({ name: 'Login', params: { userId: '123' } });
		}
		//获取分组
		this.getEmGroup();
		//获取简历信息
		this.categoryList();
	},
	methods: {
		// 一键重置
		des:function(){
			this.posKey=''
			this.radio='不限'
			this.radio1='不限'
			this.checkList=['不限']
			this.minAge=''
			this.maxAge=''
			this.categoryList();
		},
		//获取分组
		getEmGroup: function() {
			
			
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
			};
		
			var _this = this;
			this.$http
				.getEpResumeListByCate1(data)
				.then(res => {
					if (res.errorCode == 0) {
						_this.gridData = res.data.list;
						
					}
					
				})
				.catch(err => {
					console.log(err);
				});
		},
		//年龄筛选
		gose(){
			if(this.minAge<1){return}
			if(this.maxAge<1){return}
			
			this.categoryList()
		},
		//搜索
		search(){
			if(this.posKey<1){
				this.$message({
					message: '请输入您要搜索的内容',
					type: 'warning',
					offset:'100'
				});
				return;
			}
			this.categoryList()
			
		},
		//工作经验
		work(){
			console.log(this.radio);
			this.categoryList()
		},
		//性别筛选
		sexone(){
			this.categoryList()
		},
		//学历筛选
		xueli(){
			for(var i=0;i<this.checkList.length;i++){
				if(this.checkList[i]=='不限'){
					this.checkList=['不限']
				}
			}
			
			
		},
		xx(){
			this.checkList=['不限'];
			this.educationName='不限';
			this.categoryList()
		},
		xx1(){
			var index = this.checkList.indexOf('不限'); 
			if (index > -1) { 
			this.checkList.splice(index, 1); 
			}
			var a='';
			for(var i=0;i<this.checkList.length;i++){
				if(i!=this.checkList.length-1){
					var b=this.checkList[i]+',';
					a=a+ b
				}else{
					var b=this.checkList[i];
					a=a+ b
				}
			}
			this.educationName=a;
			this.categoryList()
			
		},
		//获取内容
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
							// xx[i].iid = i;
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
		},
		//下载简历
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
			//开始更换分组
			
			var num=this.multipleSelection;
			var arr=[];
			for(var i=0;i<num.length;i++){
				var data={
					"idCard":num[i].idCard,
					"phone":num[i].phone,
				}
				
				arr.push(data)
				
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
				resumeCateId: parseInt(list.id),
				resumeJson:JSON.stringify(arr)
			};
			
			var _this = this;
			this.$http
				.downLoadMultiResume(data)
				.then(res => {
					console.log(res);
					console.log(123456);
					_this.$_loading.close();
					_this.getEmGroup();
					if (res.errorCode == 0) {
						setTimeout(function() {
							_this.$message({
								message: '下载成功',
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

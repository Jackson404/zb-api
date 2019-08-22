<template>
	<div>
		<div class="hello" style="position: fixed;top: 0;z-index: 99999999;">
				<div style="width: 100%;height: 30px;background:#000000;">
					<div style="width: 1200px;margin: 0 auto;display: flex;justify-content: flex-end;">
						<div style="width: 500px;display: flex;flex-wrap: nowrap;cursor: pointer;">
							<a href="#" class="bara a" v-if="userInfo.access_token">{{name}}</a>
							<span class="bara" @click="news">消息中心</span>
							<a href="#" @click="tui" class="bara">安全退出</a>
						</div>
					</div>
				</div>
				<div class="wrap">
					<div class="left">{{company}}</div>
					<!-- 测试 -->
					<div class="right">
						<el-menu class="el-menu-demo" mode="horizontal" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b">
							<el-menu-item v-for="(item, index) in item" :key="index" style="padding: 0;border: 0;">
								<span v-if="item.name=='员工管理'">
									<router-link v-if="type==1" :to="{ name: item.url }">{{ item.name }}</router-link>
								</span>
								<span v-else>
									<router-link :to="{ name: item.url }">{{ item.name }}</router-link>
								</span>
								
							</el-menu-item>
						</el-menu>
					</div>
				</div>
			</div>
			<el-dialog
		  title="提示"
		  :visible.sync="dialogVisible"
		  width="30%"
		  style="margin-top: 20vh;">
		  <span>确定要退出吗?</span>
		  <span slot="footer" class="dialog-footer">
		    <el-button @click="dialogVisible = false">取 消</el-button>
		    <el-button type="primary" @click="go">确 定</el-button>
		  </span>
		</el-dialog>
	</div>
</template>

<script>
export default {
	name: 'Header',
	props: {
		msg: String
	},
	data() {
		return {
			activeIndex: '1',
			activeIndex2: '1',
			dialogVisible:false,
			company:'正步网络科技（上海）有限公司',
			item: [
				{
					name: '首页',
					url: 'Index',
					check: true
				},
				{
					name: '订单管理',
					url: 'Order',
					check: false
				},
				{
					name: '员工管理',
					url: 'Staff',
					check: false
				},
				{
					name: '简历管理',
					url: 'Resume',
					check: false
				}
				// {
				// 	name:'服务中心',
				// 	url:'Service',
				// 	check:false
				// },
			],
			info: '',
			name:''
		};
	},
	created() {
		this.name=this.$cookies.get('name');
		this.type=this.$cookies.get('type');
	},
	methods: {
		tui:function(){
				this.dialogVisible=true;
				// this.$emit('tui')
	
		},
		go(){
			this.dialogVisible=false;
			this.$cookies.remove("access_token");
				this.$cookies.remove("name");
				this.$cookies.remove("type");
			this.$message({
				type: 'success',
				message: '退出成功!'
			});
			
			this.$router.push({ name: 'Login', params: { id: 1} });
		},
		news(){
			this.$router.push({ name: 'News', params: { id: 1} });
			
		}
	},
	//监听
	computed: {
		userInfo() {
			
			return this.$store.state.userinfo;
			
		}
	}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.hello {
	width: 100%;
	background-color: rgb(84, 92, 100);
}
.wrap {
	width: 1200px;
	background-color: rgb(84, 92, 100);
	margin: 0 auto;
	display: flex;
	flex-wrap: nowrap;
}
.left {
	width: 600px;
	font-size: 18px;
	color: #fff;
	height: 60px;
	line-height: 60px;
	font-weight: 500;
	letter-spacing: 2px;
	text-align: left;
}
h3 {
	margin: 40px 0 0;
}
ul {
	list-style-type: none;
	padding: 0;
	border: 0;
}
li {
	display: inline-block;
	margin: 0 10px;
}
a {
	color: #42b983;
	text-decoration: none;
	display: block;
	padding: 0 20px;
	font-size: 16px;
	letter-spacing: 3px;
	border: 0;
}
.check {
	background: #fff !important;
	color: #333 !important;

	height: 60px;
	line-height: 60px;
}
.bara {
	height: 30px;
	line-height: 30px;
	display: inline-block;
	padding: 0 15px;
	color: #0084ff;
	font-size: 14px;
	font-family: AlibabaPuHuiTiR;
}
.a {
	color: #fff;
	font-weight: 600;
}
</style>

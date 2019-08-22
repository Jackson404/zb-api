<template>
	<div style="width: 100%;height: 100vh;background: #fff;">
		<div style="width: 100%;height: 300px;line-height: 300px;font-size: 40px;color: #666;text-align: center;letter-spacing: 4px;">企业登录</div>

		<div style="width: 500px;margin:0 auto;">
			<el-form
				:model="ruleForm"
				status-icon
				:rules="rules"
				ref="ruleForm"
				label-width="100px"
				class="demo-ruleForm"
				style="border: 1px solid #ebebeb;box-sizing: border-box;padding: 80px 70px 50px 30px;"
			>
				<el-form-item label="账号" prop="pass" style="text-align: center;">
					<el-input type="text" v-model="ruleForm.pass" autocomplete="off" placeholder="请输入账号"></el-input>
				</el-form-item>
				<el-form-item label="验证码" prop="checkPass" style="text-align: center;position: relative;">
					<el-input type="password" v-model="ruleForm.checkPass" autocomplete="off" placeholder="请输入验证码"></el-input>
					<el-button @click="sendCode" v-if="send" type="primary" style="position: absolute;right: 0;top: 0;padding: 12px 30px;">获取验证码</el-button>
					<el-button v-else type="primary" style="position: absolute;right: 0;top: 0;padding: 12px 30px;">{{ num }} s</el-button>
				</el-form-item>

				<el-form-item>
					<el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
					<el-button @click="resetForm('ruleForm')">重置</el-button>
				</el-form-item>
			</el-form>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Staff',

	data() {
		var validatePass = (rule, value, callback) => {
			if (value === '') {
				callback(new Error('请输入账号'));
			} else {
				if (this.ruleForm.checkPass !== '') {
					this.$refs.ruleForm.validateField('checkPass');
				}
				callback();
			}
		};
		var validatePass2 = (rule, value, callback) => {
			if (value === '') {
				callback(new Error('请输入验证码'));
			} else {
				callback();
			}
		};
		return {
			ruleForm: {
				pass: '',
				checkPass: '',
				age: ''
			},
			rules: {
				pass: [{ validator: validatePass, trigger: 'blur' }],
				checkPass: [{ validator: validatePass2, trigger: 'blur' }]
			},
			send: true,
			num: 60
		};
	},
	created() {},
	methods: {
		submitForm(formName) {
			this.$refs[formName].validate(valid => {
				if (valid) {
					console.log(valid);
					console.log(this);

					this.$_loading = this.$loading({
						lock: true,
						text: 'Loading',
						spinner: 'el-icon-loading',
						background: 'rgba(0, 0, 0, 0.7)'
					});
					var data = {
						accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
						phone: this.ruleForm.pass,
						vCode: this.ruleForm.checkPass
					};

					var _this = this;
					this.$http
						.login(data)
						.then(res => {
							console.log(res);
							_this.$_loading.close();
							//切换发送显示
							_this.send = false;
							if (res.errorCode == 0) {
								//存到cookies
								this.$cookies.set('access_token', res.data.id_token);
								
								this.$cookies.set('name', res.data.realname);
								this.$cookies.set('type', res.data.type);
								var data = {
									access_token: res.data.id_token,
									name:res.data.realname,
									type:res.data.type
								};
								//分发actions 中声明的方法
								this.$store.dispatch('getUser', data);

								if (res.data.isReview == 2) {
									this.$message({
										message: '登录成功',
										type: 'success'
									});
								
									//回到前面的数据
									// this.$router.go(-1);
									this.$router.push({ name: 'Index', params: { id: 1 } });
								}
								//第一次登录 去审核
								if (res.data.isReview == 0) {
									//跳转认证
									// this.$router.push({ name: 'Attestation', params: { access_token: res.data.id_token } });
								}
								//第一次登录 去审核
								if (res.data.isReview == 1) {
									//跳转认证
									this.$message({
										showClose: true,
										message: '您的账户审核中，稍后再试',
										type: 'error'
									});

									return;
								}
							}
						})
						.catch(err => {
							console.log(err);
						});
				} else {
					console.log('error submit!!');
					return false;
				}
			});
		},
		resetForm(formName) {
			this.$refs[formName].resetFields();
		},
		sendCode() {
			if (this.ruleForm.pass.length == 0) {
				this.$message({
					message: '请输入手机号哦',
					type: 'warning'
				});
				return;
			}
			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				phone: this.ruleForm.pass
			};
			// this.$http.sendSmss(data)
			//发送验证码
			var _this = this;
			this.$http
				.sendSmss(data)
				.then(res => {
					console.log(res);
					//切换发送显示
					_this.send = false;
					if (res.errorCode == 0) {
						_this.$message({
							message: '验证码发送成功',
							type: 'success'
						});

						//倒计时
						_this.countdown();
					} else if (res.errorCode == -20000013) {
						_this.$message({
							message: '请输入正确手机号',
							type: 'warning'
						});
					} else {
						_this.$message({
							message: res.msg,
							type: 'warning'
						});
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		countdown() {
			var num = 59;
			var _this = this;
			var timer = setInterval(function() {
				if (num == 0) {
					_this.send = true;
					_this.num = 60;
					clearInterval(timer);
				} else {
					_this.num = num--;
				}
			}, 1000);
		}
	}
};
</script>

<style scoped>
.hello {
	display: none;
}
.cc {
	margin-top: 0 !important;
}
.el-button {
	padding: 10px 45px;
}
.demonstration {
	display: block;
	margin: 0 auto;
	cursor: pointer;
	color: #8492a6;
	font-size: 14px;
}
</style>

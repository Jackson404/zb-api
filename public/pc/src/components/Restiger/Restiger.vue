<template>
	<div style="width: 100%;height: 100vh;background: #fff;">
		
		<div style="width: 100%;height: 300px;line-height: 300px;font-size: 40px;color: #666;text-align: center;letter-spacing: 4px;">企业注册</div>
		
		<div style="width: 500px;margin:0 auto;">
			<el-form :model="ruleForm" status-icon :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm" style="border: 1px solid #ebebeb;box-sizing: border-box;padding: 80px 70px 50px 30px;">
			  <el-form-item label="账号" prop="pass" style="text-align: center;">
				<el-input type="password" v-model="ruleForm.pass" autocomplete="off" placeholder="请输入账号"></el-input>
			  </el-form-item>
			  <el-form-item label="密码" prop="checkPass" style="text-align: center;">
				<el-input type="password" v-model="ruleForm.checkPass" autocomplete="off"  placeholder="请输入密码"></el-input>
			  </el-form-item>
			  
			  <el-form-item>
				<el-button type="primary" @click="submitForm('ruleForm')">注册</el-button>
				<el-button @click="resetForm('ruleForm')">重置</el-button>
			  </el-form-item>
				
				<el-form-item>
					<span class="demonstration" @click="to">登录</span>
				</el-form-item>
				
			</el-form>
		</div>
	</div>
</template>

<script>
	
	export default {
		name: 'Restiger',
		
		props: {
			msg: String
		},
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
			    callback(new Error('请输入密码'));
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
			    pass: [
			      { validator: validatePass, trigger: 'blur' }
			    ],
			    checkPass: [
			      { validator: validatePass2, trigger: 'blur' }
			    ],
			    
			  }
			};
		},
		created() {
			
		},
		methods: {
			 submitForm(formName) {
			  this.$refs[formName].validate((valid) => {
			    if (valid) {
			//存到cookies
			this.$cookies.set('access_token','-1')
			
			var data={
				'access_token':-1
			}
			//分发actions 中声明的方法
			this.$store.dispatch('getUser',data);
			//回到前面的数据
			 this.$router.go(-1);
			    } else {
			      console.log('error submit!!');
			      return false;
			    }
			  });
			},
			resetForm(formName) {
			  this.$refs[formName].resetFields();
			},
			to:function(){
				this.$router.push({
						name:"Login",
						params: { userId: '123' }
				})
				
			}
		}
	};
	</script>


<style scoped>
	.hello{
		display: none;
	}
	.cc{
		margin-top: 0 !important;
	}
	.el-button{
		padding: 10px 45px;
	}
	.demonstration{
		display: block;
		margin: 0 auto;
		cursor: pointer;
		color: #8492a6;
    font-size: 14px;
	}
</style>

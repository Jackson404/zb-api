<template>
	<div>
		<div style="width: 1200px;margin: 0 auto;">
			<!-- 选择 -->
			<div class="wrap">
				<div style="height: 50px;line-height: 50px;background:#0084FF;box-sizing: border-box;padding: 0 100px;"><div class="show">认证信息</div></div>

				<div v-if="status">
					<div style="width: 100%;box-sizing: border-box;padding: 100px 10%;">
						<div class="title">实名信息认证</div>

						<div style="width: 380px;padding: 40px 0;margin: 0 auto;">
							<div v-for="(item, index) in list" :key="index" :class="[item.id == cateIndex ? 'check' : '', 'btn']" @click="checkOne(index)">{{ item.name }}</div>
						</div>

						<div style="width: 100%;box-sizing: border-box;padding: 60px 0;"><div class="acc" @click="acc">下一步</div></div>
					</div>
				</div>

				<!-- 个人123456 -->

				<div v-if="geren">
					<div style="width: 100%;box-sizing: border-box;padding:30px 50px;">
						<div class="l-title">
							<div class="l-t-left"></div>
							<div class="l-t-right">个人认证信息</div>
						</div>

						<div class="c-con">
							<div class="c-c-list">
								<div class="c-name">所属公司：</div>
								<el-input placeholder="请输入所属公司" v-model="companyName" clearable></el-input>
								<!-- <input type="text" class="form-control" name="email" value="" /> -->
							</div>
							<div class="c-c-list">
								<div class="c-name">真实姓名：</div>
								<el-input placeholder="请输入真实姓名" v-model="realname" clearable></el-input>
								<!-- <input type="text" class="form-control" name="email" value="" /> -->
							</div>
							<div class="c-c-list">
								<div class="c-name">电话号码：</div>
								<el-input type="number" placeholder="请输入电话号码" v-model="realphone" clearable></el-input>
								<!-- <input type="text" class="form-control" name="email" value="" /> -->
							</div>
							<div class="c-c-list">
								<div class="c-name">身份证信息：</div>
								<el-input type="number" placeholder="请输入身份证信息" v-model="idCard" clearable></el-input>
								<!-- <input type="text" class="form-control" name="email" value="" /> -->
							</div>
						</div>

						<div class="up-con">
							<div class="up-left">
								<div class="up-title">手持身份证照片</div>
								<div class="up-des">身份证正面照</div>
								<div class="up-img">
									<img v-if="card1 == 1" src="../../assets/card.png" alt="" />
									<img v-else :src="card1" alt="" />
								</div>

								<div style="width: 100%;padding: 30px 0;">
									<el-upload
										class="upload-demo"
										action="http://47.103.102.222/api/v1.File/oss"
										:data="item"
										name="img"
										:on-success="hhhx"
										list-type="picture-card"
									>
										<div style="width: 100%;">
											<el-button slot="trigger" size="small" type="primary" style="margin: 0 auto;display: block;width: 100px;">点击上传</el-button>
										</div>
									</el-upload>
								</div>
							</div>
							<div class="up-right">
								<div class="up-title">手持身份证照片</div>
								<div class="up-des">身份证正面照</div>
								<div class="up-img">
									<img v-if="card2 == 1" src="../../assets/card1.png" alt="" />
									<img v-else :src="card2" alt="" />
								</div>
								<div style="width: 100%;padding: 30px 0;">
									<el-upload
										class="upload-demo"
										action="http://47.103.102.222/api/v1.File/oss"
										:data="item"
										name="img"
										:on-success="hhhx1"
										list-type="picture-card"
									>
										<div style="width: 100%;">
											<el-button slot="trigger" size="small" type="primary" style="margin: 0 auto;display: block;width: 100px;">点击上传</el-button>
										</div>
									</el-upload>
								</div>
							</div>
						</div>
					</div>

					<div style="width: 100%;box-sizing: border-box;padding: 60px 0;">
						<el-button @click="send" type="primary" style="width: 230px;text-align: center;">提交审核</el-button>
					</div>
				</div>
				<!-- 企业 -->

				<div v-if="qiye">
					<div style="width: 100%;box-sizing: border-box;padding:30px 50px;">
						<div class="l-title">
							<div class="l-t-left"></div>
							<div class="l-t-right">个人认证信息</div>
						</div>

						<div class="c-con">
							<div class="c-c-list">
								<div class="c-name">真实姓名：</div>
								<el-input placeholder="请输入真实姓名" v-model="realname" clearable></el-input>
							</div>
							<div class="c-c-list">
								<div class="c-name">电话号码：</div>
								<el-input type="number" placeholder="请输入电话号码" v-model="realphone" clearable></el-input>
							</div>
							<div class="c-c-list">
								<div class="c-name">身份证信息：</div>
								<el-input type="number" placeholder="请输入身份证信息" v-model="idCard" clearable></el-input>
							</div>
						</div>

						<div class="up-con">
							<div class="up-left">
								<div class="up-title">手持身份证照片</div>
								<div class="up-des">身份证正面照</div>
								<div class="up-img">
									<img v-if="card1 == 1" src="../../assets/card.png" alt="" />
									<img v-else :src="card1" alt="" />
								</div>

								<div style="width: 100%;padding: 30px 0;">
									<el-upload
										class="upload-demo"
										action="http://47.103.102.222/api/v1.File/oss"
										:data="item"
										name="img"
										:on-success="hhhx"
										list-type="picture-card"
									>
										<div style="width: 100%;">
											<el-button slot="trigger" size="small" type="primary" style="margin: 0 auto;display: block;width: 100px;">点击上传</el-button>
										</div>
									</el-upload>
								</div>
							</div>
							<div class="up-right">
								<div class="up-title">手持身份证照片</div>
								<div class="up-des">身份证正面照</div>
								<div class="up-img">
									<img v-if="card2 == 1" src="../../assets/card1.png" alt="" />
									<img v-else :src="card2" alt="" />
								</div>
								<div style="width: 100%;padding: 30px 0;">
									<el-upload
										class="upload-demo"
										action="http://47.103.102.222/api/v1.File/oss"
										:data="item"
										name="img"
										:on-success="hhhx1"
										list-type="picture-card"
									>
										<div style="width: 100%;">
											<el-button slot="trigger" size="small" type="primary" style="margin: 0 auto;display: block;width: 100px;">点击上传</el-button>
										</div>
									</el-upload>
								</div>
							</div>
						</div>
					</div>

					<div style="width: 100%;box-sizing: border-box;padding:30px 50px;">
						<div class="l-title">
							<div class="l-t-left"></div>
							<div class="l-t-right">公司认证信息</div>
						</div>

						<div class="c-con">
							<div class="c-c-list">
								<div class="c-name">公司名称：</div>
								<el-input type="text" placeholder="请输入公司名称" v-model="companyName" clearable></el-input>
							</div>
							<div class="c-c-list">
								<div class="c-name">公司地址：</div>
								<el-input type="text" placeholder="请输入公司地址" v-model="companyAddr" clearable></el-input>
							</div>
						</div>

						<div class="up-con">
							<div class="up-left">
								<div class="up-title">营业执照复印件</div>
								<div class="up-des"></div>
								<div class="up-img">
									<img v-if="card3 == 1" src="../../assets/a9.png" alt="" />
									<img v-else :src="card3" alt="" />
								</div>
								<div style="width: 100%;padding: 30px 0;">
									<el-upload
										class="upload-demo"
										action="http://47.103.102.222/api/v1.File/oss"
										:data="item"
										name="img"
										:on-success="hhhx2"
										list-type="picture-card"
									>
										<div style="width: 100%;">
											<el-button slot="trigger" size="small" type="primary" style="margin: 0 auto;display: block;width: 100px;">点击上传</el-button>
										</div>
									</el-upload>
								</div>
							</div>
							<div class="up-right">
								<div class="up-title">人力资源许可证</div>
								<div class="up-des">组织机构代码证或人力资源服务许可证</div>
								<div class="up-img">
									<img v-if="card4 == 1" src="../../assets/a8.png" alt="" />
									<img v-else :src="card4" alt="" />
								</div>
								<div style="width: 100%;padding: 30px 0;">
									<el-upload
										class="upload-demo"
										action="http://47.103.102.222/api/v1.File/oss"
										:data="item"
										name="img"
										:on-success="hhhx3"
										list-type="picture-card"
									>
										<div style="width: 100%;">
											<el-button slot="trigger" size="small" type="primary" style="margin: 0 auto;display: block;width: 100px;">点击上传</el-button>
										</div>
									</el-upload>
								</div>
							</div>
						</div>
					</div>

					<div style="width: 100%;box-sizing: border-box;padding: 60px 0;">
						<el-button @click="send1" type="primary" style="width: 230px;text-align: center;">提交审核</el-button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Attestation',
	components: {},
	data() {
		return {
			status: true,
			cateIndex: '-1',
			geren: false,
			qiye: false,
			list: [
				{
					id: 0,
					name: '我是员工，我要认领公司',
					check: false
				},
				{
					id: 1,
					name: '我是企业，我要企业认证',
					check: false
				}
			],
			dialogImageUrl: '',
			dialogVisible: false,
			fileList: [],
			card1: '1',
			card2: '1',
			card3: '1',
			card4: '1',
			item: {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86'
			},
			companyName: '',
			realname: '',
			realphone: '',
			idCard: '',
			companyAddr: '',
			companyName: ''
		};
	},
	created() {
		// console.log(this.$router)
		// console.log(this.$router.route)
	},
	methods: {
		checkOne(e) {
			this.cateIndex = e;
		},
		acc() {
			if (this.cateIndex == '-1') {
				this.$message({
					showClose: true,
					message: '请选择实名认证类型',
					type: 'warning',
					offset: '100'
				});
			} else if (this.cateIndex == 0) {
				this.status = false;
				this.geren = true;
				this.qiye = false;
			} else {
				this.status = false;
				this.geren = false;
				this.qiye = true;
			}
		},

		submitUpload() {
			var a = this.$refs.upload.submit();
		},

		hhhx(file, fileList) {
			if (file.errorCode == 0) {
				this.$message({
					message: '上传成功',
					type: 'success'
				});
				this.card1 = file.data.imgUrl;
			}
		},
		hhhx1(file, fileList) {
			if (file.errorCode == 0) {
				this.$message({
					message: '上传成功',
					type: 'success'
				});
				this.card2 = file.data.imgUrl;
			}
		},
		hhhx2(file, fileList) {
			if (file.errorCode == 0) {
				this.$message({ message: '上传成功', type: 'success' });
				this.card3 = file.data.imgUrl;
			}
		},
		hhhx3(file, fileList) {
			if (file.errorCode == 0) {
				this.$message({ message: '上传成功', type: 'success' });
				this.card4 = file.data.imgUrl;
			}
		},
		send() {
			if (this.companyName.length == 0) {
				this.$message({ showClose: true, message: '公司名字不能为空' });
				return;
			}

			if (this.realname.length == 0) {
				this.$message({ showClose: true, message: '用户名称不能为空' });
				return;
			}

			if (this.realphone.length == 0) {
				this.$message({ showClose: true, message: '手机号不能为空' });
				return;
			}

			if (this.idCard.length == 0) {
				this.$message({ showClose: true, message: '身份证信息不能为空' });
				return;
			}

			if (this.card1 == 1) {
				this.$message({ showClose: true, message: '身份证前面信息不能为空' });
				return;
			}

			if (this.card2 == 1) {
				this.$message({ showClose: true, message: '身份证背面信息不能为空' });
				return;
			}

			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				realname: this.realname,
				realphone: this.realphone,
				companyName: this.companyName,
				idCard: this.idCard,
				idCardFrontPic: this.card1,
				idCardBackPic: this.card2
			};
			this.sendinfo(data);
		},

		send1() {
			if (this.realname.length == 0) {
				this.$message({ showClose: true, message: '用户名称不能为空' });
				return;
			}

			if (this.realphone.length == 0) {
				this.$message({ showClose: true, message: '手机号不能为空' });
				return;
			}

			if (this.idCard.length == 0) {
				this.$message({ showClose: true, message: '身份证信息不能为空' });
				return;
			}

			if (this.card1 == 1) {
				this.$message({ showClose: true, message: '身份证前面信息不能为空' });
				return;
			}

			if (this.card2 == 1) {
				this.$message({ showClose: true, message: '身份证背面信息不能为空' });
				return;
			}

			if (this.companyName.length == 0) {
				this.$message({ showClose: true, message: '公司名字不能为空' });
				return;
			}

			if (this.companyAddr.length == 0) {
				this.$message({ showClose: true, message: '公司地址不能为空' });
				return;
			}

			if (this.card3 == 1) {
				this.$message({ showClose: true, message: '营业执照信息不能为空' });
				return;
			}

			if (this.card4 == 1) {
				this.$message({ showClose: true, message: '人力资源许可证信息不能为空' });
				return;
			}

			var data = {
				accessToken: '1565742674|145B1691263AEC04CC1722BA2EF68A86',
				id_token: this.$cookies.get('access_token'),
				realname: this.realname,
				realphone: this.realphone,
				companyName: this.companyName,
				idCard: this.idCard,
				idCardFrontPic: this.card1,
				idCardBackPic: this.card2,
				companyAddr: this.companyAddr,
				businessLic: this.card3,
				otherQuaLic: this.card4
			};
			this.sendinfo1(data);
		},

		sendinfo(data) {
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var _this = this;
			this.$http
				.fication(data)
				.then(res => {
					console.log(res);
					_this.$_loading.close();
					//切换发送显示
					if (res.errorCode == 0) {
						_this.$router.go(-1);
					} else {
						_this.$message({
							showClose: true,
							message: res.msg
						});
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		sendinfo1(data) {
			this.$_loading = this.$loading({
				lock: true,
				text: 'Loading',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			var _this = this;
			this.$http
				.Company(data)
				.then(res => {
					console.log(res);
					_this.$_loading.close();
					//切换发送显示
					if (res.errorCode == 0) {
						_this.$router.go(-1);
					} else {
						_this.$message({
							showClose: true,
							message: res.msg
						});
					}
				})
				.catch(err => {
					console.log(err);
				});
		}
	}
};
</script>

<style>
/* div {
	text-align: left;
} */

.wrap {
	width: 100%;
	margin-bottom: 80px;
	background: #fff;
	box-sizing: border-box;
}
.show {
	width: 120px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(0, 132, 255, 1);
	text-align: center;
	background: #fff;
}
.title {
	text-align: center;
	height: 40px;
	font-size: 24px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 40px;
}
.btn {
	width: 100%;
	height: 40px;
	line-height: 38px;
	border: 1px solid rgba(141, 146, 151, 1);
	border-radius: 5px;
	text-align: center;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	box-sizing: border-box;
	cursor: pointer;
	transition-duration: 0.5s;
	margin-bottom: 20px;
}
.check {
	background: #0084ff;
	color: #fff;
	border: 1px solid #0084ff;
}
.btn:hover {
	background: #0084ff;
	color: #fff;
	border: 1px solid #0084ff;
}
.acc {
	width: 280px;
	height: 36px;
	background: rgba(0, 132, 255, 0.8);
	border-radius: 2px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	text-align: center;
	line-height: 36px;
	margin: 0 auto;
	cursor: pointer;
	transition-duration: 0.5s;
}
.acc:hover {
	background: rgba(0, 132, 255, 1);
}

.show {
	width: 120px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(0, 132, 255, 1);
	text-align: center;
	background: #fff;
}
.l-title {
	padding: 10px 0;
	display: flex;
	align-items: center;
}
.l-t-left {
	width: 3px;
	height: 22px;

	background: rgba(37, 117, 252, 1);
}
.l-t-right {
	width: 130px;
	font-size: 20px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	padding-left: 20px;
}
.c-con {
	width: 500px;
	box-sizing: border-box;
	padding: 20px 0 20px 0;
	margin: 0 auto;
}
.c-c-list {
	width: 100%;
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
	padding: 10px 0;
}
.c-name {
	width: 150px;

	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	text-align: right;
	box-sizing: border-box;
	padding-right: 8px;
}
.form-control {
	flex: 1;
	height: 30px;
	line-height: 30px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	box-sizing: border-box;
	padding: 0 10px;
}
.up-con {
	width: 100%;
	box-sizing: border-box;
	padding: 30px 100px;
	display: flex;
	flex-wrap: nowrap;
	justify-content: space-between;
}
.up-left,
.up-right {
	width: 50%;
	box-sizing: border-box;
	padding: 5px 50px;
}
.up-left {
	border-right: 1px solid #d9e3ed;
}
.up-title {
	text-align: center;
	height: 35px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 35px;
}
.up-des {
	text-align: center;
	height: 35px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(141, 146, 151, 1);
	line-height: 35px;
	padding-bottom: 15px;
}
.up-img {
	width: 100%;
	background: #f0f2f5;
	box-sizing: border-box;
	padding: 15px 25px;
}
.up-img img {
	width: 100%;
	height: 160px;
	display: block;
}
.btn1 {
	width: 100px;
	height: 35px;
	line-height: 35px;
	text-align: center;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	border: 1px solid #0084ff;
	border-radius: 3px;
	color: #0084ff;
	margin: 0 auto;
	transition-duration: 0.3s;
	cursor: pointer;
}
.btn1:hover {
	background: #0084ff;
	color: #fff;
}

.acc1 {
	width: 280px;
	height: 36px;
	background: rgba(0, 132, 255, 0.8);
	border-radius: 2px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(255, 255, 255, 1);
	text-align: center;
	line-height: 36px;
	margin: 0 auto;
	cursor: pointer;
	transition-duration: 0.5s;
}
.acc1:hover {
	background: rgba(0, 132, 255, 1);
}
.cc {
	margin-top: 40px !important;
}
.upload-demo {
	width: 100%;
}
.el-upload--picture-card {
	height: auto !important;
	width: auto !important;
	border: 0 !important;
	line-height: 40px;
	margin: 0 auto !important;
}
.el-upload-list {
	display: none;
}
</style>

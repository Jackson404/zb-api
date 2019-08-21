<template>
	<div>
		<Header></Header>

		<div style="width: 1200px;margin: 0px auto;">
			<div style="height: 20px;"></div>
			<div class="wrap">
				<div class="list">
					<div class="top">
						<div class="name">{{detail.title}}</div>
						<div class="time">{{detail.createTime}}</div>
					</div>
					<div class="con">
							{{detail.content}}
					</div>
				</div>

				<div class="content">
					<div class="c-top">收件人: {{detail.realname}}</div>
					<div class="c-con">
						{{detail.content}}
					</div>
					<div style="height: 50px;"></div>
					<div class="c-bom">发件人: {{detail.sendUsername}}</div>
					<div class="c-bom">{{detail.updateTime}}</div>
				</div>
			</div>

			<div style="height: 40px;"></div>
		</div>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'Newsinfo',
	components: {
		Header
	},
	data(){
		return{
			detail:[]
		}
	},
	created() {
		this.id=this.$route.params.id;
		console.log(this.$route.params)
		this.getinfo(this.$route.params.id);
		
		
	},
	methods: {
		getinfo(e){
			
			var data={
				accessToken:"1565742674|145B1691263AEC04CC1722BA2EF68A86",
				id_token:this.$cookies.get('access_token'),
				msgId:e
			}
			
			var _this=this;
			this.$http
				.getListOne(data)
				.then(res => {
					
					console.log(res);
					if(res.errorCode==0){
						_this.detail=res.data.detail;
						
					}
				})
				.catch(err => {
					console.log(err);
				});
			
			
			
			
		},
	}
};
</script>

<style scoped>
div {
	text-align: left;
}
.wrap {
	width: 100%;
	padding: 50px;
	margin-top: 40px;
	background: #fff;
	box-sizing: border-box;
}

.list {
	width: 100%;
	padding: 10px 0;
	border-bottom: 1px solid rgba(244, 244, 244, 1);
}
.top {
	width: 100%;
	display: flex;
	flex-wrap: nowrap;
	align-content: center;
	height: 40px;
	padding-top: 10px;
}
.name {
	flex: 1;
	font-size: 20px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
}
.time {
	width: 120px;
	text-align: center;
	font-size: 8px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(141, 146, 151, 1);
}
.con {
	width: 100%;
	box-sizing: border-box;
	padding: 10px 15px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 26px;
	word-break: break-all;
}
.content {
	width: 100%;
	box-sizing: border-box;
	padding: 30px 100px;
}
.c-top {
	height: 40px;
	font-size: 20px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 40px;
}
.c-con {
	width: 100%;
	box-sizing: border-box;
	padding: 20px 25px;
	font-size: 16px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 26px;
	word-break: break-all;
}
.c-bom {
	height: 40px;
	font-size: 20px;
	font-family: AlibabaPuHuiTiR;
	font-weight: 400;
	color: rgba(78, 86, 94, 1);
	line-height: 40px;
	text-align: right;
}
</style>

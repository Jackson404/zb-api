<template>
	<div>
		<Header></Header>

		<div style="width: 1200px;margin: 0px auto;">
			<div style="height: 40px;"></div>
			<div class="wrap">
				<div class="list" v-for="(item,index) in list" :key="index">
					<router-link :to="{ name: 'Newsinfo',params: { id: item.id } }">
						<div class="top">
							<div class="name">{{item.title}}</div>
							<div class="time">{{item.createTime}}</div>
						</div>
						<div class="con">
							{{item.content}}
						</div>
					</router-link>
				</div>
			</div>

			<div style="height: 40px;"></div>
		</div>
	</div>
</template>

<script>
import Header from '@/components/common/Header.vue';
export default {
	name: 'News',
	components: {
		Header
	},
	data(){
		return{
			list:[]
		}
	},
	created() {
		
		this.getinfo()
		
	},
	methods: {
		getinfo(e){
			
			var data={
				accessToken:"1565742674|145B1691263AEC04CC1722BA2EF68A86",
				id_token:this.$cookies.get('access_token')
			}
			
			var _this=this;
			this.$http
				.getList(data)
				.then(res => {
					
					console.log(res);
					if(res.errorCode==0){
						_this.list=res.data.list;
						
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
a {
	text-decoration: none;
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
</style>

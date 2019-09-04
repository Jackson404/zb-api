<template>
	<div>
		<Header></Header>
		<div class="wrap">
			
			  
			  <table class="table table-hover table-bordered">
			  	<thead>
			  		<tr style="height: 50px;line-height: 50px;">
			  			<th>功能明细</th>
			  			<th class="center">招聘基础版</th>
			  			<th class="center">招聘高级版</th>
						<th class="center">招聘VIP版</th>
			  		</tr>
			  	</thead>
			  	<tbody>
			  		<tr v-for="(item,index) in tableData" :key="index">
			  			<td>{{item.title}}</td>
			  			<td><span v-if="item.con1" class="icons"><i class="el-icon-check"></i></span></td>
			  			<td><span v-if="item.con2" class="icons"><i class="el-icon-check"></i></span></td>
						<td><span v-if="item.con3" class="icons"><i class="el-icon-check"></i></span></td>
			  		</tr>
			  		<tr>
						<td>价格</td>
						<td class="center red">2999元/年</td>
						<td class="center red">3999元/年</td>
						<td class="center red">4999元/年</td>
					</tr>
					<tr style="height: 60px;">
						<td></td>
						<td><el-button plain class="margin">立即购买</el-button></td>
						<td><el-button class="margin" type="primary" plain>立即购买</el-button></td>
						<td><el-button class="margin" plain>立即购买</el-button></td>
					</tr>
			  	</tbody>
			  </table>
			  
			  <div class="container">
				  <div class="title">热门职位简历库服务</div>
				  <div class="list" v-for="(item,index) in service" :key="index">
					  <div class="l-left">
						  <div class="name">{{item.title}}</div>
						  <div class="name">{{item.title1}}</div>
					  </div>
					  <div class="l-right">
						  <div class="l-right-name">
							  {{item.title2}}
						  </div>
						  <div class="l-right-btn">
							  <div class="money">{{item.moeny}}元/年</div>
							  <div class="buy"><el-button  class="margin padding" plain>立即购买</el-button></div>
						  </div>
					  </div>
				  </div>
				  <div class="bottom">
					  注: 购买热门职位简历，需要购买招聘基础版才能使用
				  </div>
			  </div>
			  <div class="container">
			  				  <div class="title">简历叠加包</div>
			  				  <div class="list" v-for="(item,index) in resume" :key="index">
			  					  <div class="l-left" style="line-height: 50px;">
									  {{item.title}}
			  						  <!-- <div class="name"></div> -->
			  					  </div>
			  					  <div class="l-right">
			  						  <div class="l-right-name">
			  							  {{item.title2}}
			  						  </div>
			  						  <div class="l-right-btn">
			  							  <div class="money">{{item.moeny}}元</div>
			  							  <div class="buy"><el-button  class="margin padding" plain>立即购买</el-button></div>
			  						  </div>
			  					  </div>
			  				  </div>
			  				  
			  </div>
			 <div class="b_bottom">
				 如需定制请联系 021-xxxxxxx
			 </div> 
		</div>
		
	</div>
</template>

<script>
	import Header from '@/components/common/Header.vue';
	export default {
		name: 'Setmeal',
		components: {
			Header
		},
		data() {
			
			return {
			 tableData: [
					  {
			           title: '开通正步账号',
			           con1: true,
			           con2: true,
			           con3: true
			         }, {
			           title: '普通简历库下载',
			           con1: true,
			           con2: true,
			           con3: true
			         },{
			           title: '热门职位简历库下载',
			           con1: false,
			           con2: true,
			           con3: true
			         },{
			           title: '优选候选人优先推荐',
			           con1: false,
			           con2: true,
			           con3: true
			         },{
			           title: '专属优质企业标签',
			           con1: false,
			           con2: true,
			           con3: true
			         },{
			           title: '赠送1000份下载简历数',
			           con1: false,
			           con2: true,
			           con3: true
			         },{
			           title: '赠送2000份下载简历数',
			           con1: false,
			           con2: false,
			           con3: true
			         }],
				service:[
					{
						title:'司机类职位',
						title1:'简历库',
						title2:'包含网约车，货车，出租车司机的精英简历库',
						moeny:999,
						id:1
					},
					{
						title:'运输类职位',
						title1:'简历库',
						title2:'可以包含快递员，配送员，送餐员的精英简历库',
						moeny:999,
						id:2
					},
					{
						title:'安全类职位',
						title1:'简历库',
						title2:'可以包含保安，消防员，安检员的精英简历库',
						moeny:999,
						id:3
					}
				],
				resume:[
					{
						title:'普通职位简历叠加包',
						title2:'1000份普通职位简历下载数',
						moeny:2999,
						id:1
					},
					{
						title:'热门职位简历叠加包',
						title2:'1000份热门职位简历下载数',
						moeny:4599,
						id:2
					}
				],
			};
		},
		created() {
			
		},
		methods: {
			
		}
	};
	</script>



<style scoped>
	
	@import "../../../static/global/bootstrap.min.css";
	div {
		text-align: left;
	}
	.wrap {
		width: 1200px;
		background: #fff;
		box-sizing: border-box;
		margin: 0 auto;
		margin-top: 110px !important;
		padding: 20px;
		margin-bottom: 30px;
	}
	.icons{
		display: block;
		text-align: center;
	}
	.icons i{
		font-size: 20px;
		color: #000;
		font-weight: bolder;
	}
	.el-table_1_column_2 .cell{
		text-align: center;
	}
	
	.center{
		text-align: center;
	}
	.table{
		cursor: pointer;
		font-size: 16px;
		box-sizing: content-box;
	}
	.table tr{
		box-sizing: content-box;
	}
	.red{
		color: red;
		font-weight: bolder;
		letter-spacing: 2px;
	}
	.margin{
		margin: 0 auto;
		display: block;
	}
	tr{
		height: 40px;
		line-height: 40px;
	}
	.container{
		width: 100%;
		margin-top: 30px;
		border: 1px solid #ddd;
		padding: 0;
	}
	.title{
		font-size: 18px;
		padding: 0 10px;
		font-weight: bolder;
		letter-spacing: 2px;
		box-sizing: border-box;
		height: 50px;
		line-break: 50px;
	}
	.list{
		border-top: 1px solid #ddd;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
		cursor: pointer;
		transition-duration: 0.5s;
	}
	.list:hover{
		background: #F8F8F8;
	}
	.l-left{
		padding: 50px 0;
		width: 30%;
		text-align: center;
		border-right: 1px solid #ddd;
		height: 50px;
		
	}
	.l-right{
		padding: 30px 0;
		width: 70%;
		height: 90px;
		display: flex;
		flex-wrap: nowrap;
		align-items: center;
	}
	.name{
		text-align: center;
	}
	.l-right-name{
		width: 75%;
		padding-left: 25px;
	}
	.l-right-btn{
		width: 25%;
		display: flex;
		flex-direction: column;
	}
	.money{
		font-size: 18px;
		color: #000;
		font-weight: bolder;
		letter-spacing: 2px;
		margin-bottom: 10px;
		text-align: center;
	}
	.padding{
		padding: 6px 30px;
	}
	.bottom{
		height: 30px;
		line-height: 30px;
		box-sizing: border-box;
		padding: 0 10px;
		border-top:  1px solid #ddd;
		letter-spacing: 2px;
	}
	.b_bottom{
		height: 100px;
		line-height: 100px;
		text-align: right;
		box-sizing: border-box;
		padding-right: 10px;
		font-size: 18px;
		letter-spacing: 2px;
	}
</style>

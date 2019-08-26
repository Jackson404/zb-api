<script>
	export default {
		onLaunch: function () {
			console.log('App Launch')
		},
		onShow: function () {
			console.log('App Show')
		},
		onHide: function () {
			console.log('App Hide')
		}
	}
</script>

<style>
	@import "css/uni.css";
	@import "css/colorui.css";
	@import "css/icon.css";
	@import "css/new_file.css";
	@import "css/graceUI.css";
	
	@import "colorui/main.css";
	@import "colorui/icon.css";
	/*每个页面公共css */
	.u-f,.u-f-ac,.u-f-ajc{
			display: flex;
		}
		.u-f-ac,.u-f-ajc{
			align-items: center;
		}
		.u-f-ajc{
			justify-content: center;
		}
		.u-f-jsb{
			justify-content: space-between;
		}
</style>

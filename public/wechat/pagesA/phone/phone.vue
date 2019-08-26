<template>    
    <view>    
      
        <button open-type="getPhoneNumber" @getphonenumber="getPhoneNumber">获取电话号码</button>    

    </view>    
</template>    

<script>    

    //微信提供的解密函数，请修改为你自己的路径  
    var WXBizDataCrypt = require('../../css/WXBizDataCrypt.js');  

    export default {    
        data() {    
            return {    
                login_code : '',  
            };    
        },    
        onLoad: function() {  

            var that = this;  
            uni.login({    
                success: function(res) {    

                    // 获取code    
                    console.log(JSON.stringify(res));  
                    //{"errMsg":"login:ok","code":"071JIp1t1pv马赛克t1Ran1t1JIp1l"}  

                    that.login_code = res.code;  
					
                }  
            });  

        },  

        methods: {  

            getPhoneNumber: function(e) {    
                console.log(e);    
                if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {    
                    console.log('用户拒绝提供手机号');  
                } else {    
                    console.log('用户同意提供手机号');  

                    console.log(JSON.stringify(e.detail.encryptedData));    
                    console.log(JSON.stringify(e.detail.iv));   

                    var encryptedData = e.detail.encryptedData;  
                    var iv = e.detail.iv;  

                    ////////////////////////////////////////////////////////////////////////////////  
                    //定义在根目录下的main.js里  
                    //Vue.prototype.APPID                           = 'wxb1a马赛克2bfc90a';  
                    //Vue.prototype.SECRET                          = 'b3ae36758马赛克dbe146d9acd81d';  
                    //Vue.prototype.WX_AUTH_URL                     = 'https://api.weixin.qq.com/sns/jscode2session';  

                    var JSCODE = this.login_code;  
                    var APPID = this.APPID;  
                    var SECRET = this.SECRET;  
                    var wx_author_url = 'https://api.weixin.qq.com/sns/jscode2session'+'?appid=wx02c9a76ab01f424c'+'&secret=22f25c64080e8a640d93d104cbc2a3ea'+'&js_code='+ JSCODE + '&grant_type=authorization_code';  
					
					console.log(wx_author_url);
					return;
                    uni.request({  
                        url : wx_author_url,  
                        success(re){  
                            console.log( 'session_key:' + re.data.session_key );  

                            var appId = 'wx02c9a76ab01f424c';  
                            var sessionKey = re.data.session_key;  

                            var pc = new WXBizDataCrypt(appId, sessionKey);  
                            var data = pc.decryptData(encryptedData, iv);  

                            console.log('------------------->');  
                            console.log('解密后 data: ', data);  
                            // console.log('解密后 data: ', JSON.stringify(data));  
                            /*  
                                {  
                                    "phoneNumber": "139马赛克9490",  
                                    "purePhoneNumber": "139马赛克9490",  
                                    "countryCode": "86",  
                                    "watermark": {  
                                        "timestamp": 1560577589,  
                                        "appid": "wxb1a马赛克12bfc90a"  
                                    }  
                                }  
                            */  
                            console.log('------------------->');  

                        }  
                    });  
                    ////////////////////////////////////////////////////////////////////////////////  

                }    

            },  

         
        }    
    }    
</script>    

<style>    

</style>    
var Promise = require('../plugins/es6-promise.js');
var configs = require('./config.js');
var md5 = require('./md5.js');
var time = 0;
function wxPromisify(fn) {
  return function(obj = {}) {
    return new Promise((resolve, reject) => {
      obj.success = function(res) {
        let current_time = Date.parse(new Date()) / 1000;
        //成功
        if (res.data.error_code == -100) {
          if (time < current_time){
            time = current_time + 3;
            wx.removeStorageSync('sid' + configs.debug)
            wx.reLaunch({
              url: '/pages/login/login'
            })
          }
        } else if (res.data.error_code == -200) {
          if (time < current_time){
            time = current_time + 3;
            wx.reLaunch({
              url: '/pages/authorize/authorize'
            })
          }
		  
        }
        resolve(res)
      }
      obj.fail = function(res) {
        //失败
        reject(res)
      }
      fn(obj)
    })
  }
}
//无论promise对象最后状态如何都会执行
Promise.prototype.finally = function(callback) {
  let P = this.constructor;
  return this.then(
    value => P.resolve(callback()).then(() => value),
    reason => P.resolve(callback()).then(() => {
      throw reason
    })
  );
};
/**
 * 微信请求get方法
 * url
 * data 以对象的格式传入
 */
function getRequest(url, data = {}) {
  var getRequest = wxPromisify(uni.request);
  data.version_code = configs.version_code;
  data.version_name = configs.version_name;
  data.sid = data.sid ? data.sid : wx.getStorageSync('sid' + configs.debug);
  data.code = configs.code;
  data.ckey = configs.sign_key;
  data.h = this.getValidSign(data);
  console.log(configs.root.path + url)
  return getRequest({
    url: configs.root.path + url,
    method: 'GET',
    data: data,
    header: {
      'Content-Type': 'application/json',
      'Cache-Control': 'max-age = 3600',
      'Pragma': 'max-age = 3600',
    }
  })
}

/**
 * 微信图片上传
 * URL
 */
function uploadFile(url, filePath, formData, name) {
  var uploadFile = wxPromisify(uni.uploadFile);
  formData.version_code = configs.version_code;
  formData.version_name = configs.version_name;
  formData.code = configs.code;
  formData.sid = wx.getStorageSync('sid' + configs.debug);
  formData.type = 'file';
  if (wx.getStorageSync("device_no")) {
    formData.device_no = wx.getStorageSync("device_no");
  }
  formData.ckey = configs.sign_key;
  formData.h = this.getValidSign(formData);
  return uploadFile({
    url: configs.root.path + url,
    filePath: filePath,
    name: name,
    formData: formData,
    header: {
      "Content-Type": "multipart/form-data"
    },
  })
}
/**
 * 微信请求post方法封装
 * url
 * data 以对象的格式传入
 */
function postRequest(url, data = {}) {
  var postRequest = wxPromisify(uni.request)
  data.version_code = configs.version_code;
  data.version_name = configs.version_name;
  data.sid = data.sid ? data.sid : wx.getStorageSync('sid' + configs.debug)
  data.code = configs.code;
  if (wx.getStorageSync("parent")) {
    var parent = wx.getStorageSync("parent");
    data.parent_id = parent.user_id ? parent.user_id : 0
  }
  if (wx.getStorageSync("device_no")) {
    data.device_no = wx.getStorageSync("device_no");
  }
  data.ckey = configs.sign_key;
  data.h = this.getValidSign(data);
  return postRequest({
    url: configs.root.path + url,
    method: 'POST',
    data: data,
    header: {
      "content-type": "application/x-www-form-urlencoded",
      'Cache-Control': 'max-age = 3600',
      'Pragma': 'max-age = 3600',
    },
  })
}

/**
 * getValidSign 
 * 生成签名
 */
function getValidSign(data) {
  var sign = [];
  for (var k in data) {
    if (k == 'h' || k == '_url' || k == 'file') {
      continue;
    }
    sign.push(k + '=' + data[k]);
  }
  sign = sign.sort();
  sign = sign.join("&");
  sign = md5(md5(sign) + configs.sign_key);
  return sign;
}


module.exports = {
  postRequest: postRequest,
  getRequest: getRequest,
  uploadFile: uploadFile,
  getValidSign: getValidSign,
}
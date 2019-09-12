var debug = 2; //0线上、1线下、2本地
var root = {}
switch (debug) {
  case 0:
    root.domain = 'https://oa.zhengbu121.com/';
    root.path = 'https://oa.zhengbu121/xcx/';
    root.image = 'https://oa.zhengbu121/xcx/images/';
    break;
  case 1:
    root.domain = 'http://line.zhengbu121.com/';
    root.path = 'http://line.zhengbu121.com/xcx/';
    root.image = 'http://line.zhengbu121.com/xcx/images/';
    break;
  case 2:
    root.domain = 'http://wms.local.com/';
    root.path = 'http://wms.local.com/xcx/';
    root.image = 'http://wms.local.com/xcx/images/';
    break;
}
module.exports = {
  root: root,
  debug: debug,
  code: "oa",
  version_name: "0.0.0",
  version_code: 0,
  qq_map_key: 'UAEBZ-LVFCV-J6DPX-UUDM6-7ZIHO-QPFV6',
  sign_key: 'wx02c9a76ab01f424c'
}
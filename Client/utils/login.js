const util = require('./util.js')
var noop = function noop() { };
var defaultOptions = {
  method: 'GET',
  success: noop,
  fail: noop,
  loginUrl: 'http://192.168.199.100/b.php',
  endata:'',
  iv:'',
  bLogin:false
};
var SESSION_KEY = 'session_ck';
var CookieKey = {
  get :function() {
    return wx.getStorageSync(SESSION_KEY || null);
  },
  set : function(data) {
    console.log(data)
    try {wx.setStorageSync(SESSION_KEY, data)}
    catch(e) {
      console.log(e.error)
    }
  },
  clear : function () {
    wx.removeStorageSync(SESSION_KEY);
  }
}

var LoginError = (function () {
  function LoginError(type, message) {
    Error.call(this, message);
    this.type = type;
    this.message = message;
  }

  LoginError.prototype = new Error();
  LoginError.prototype.constructor = LoginError;

  return LoginError;
})();

var getWxLoginResult = function getLoginCode(callback) {
  var code = CookieKey.get();
  if (!code) {
    setLoginCk();
    code = CookieKey.get();
  }
  var header = {};
  var dat = (defaultOptions.endata);
  var iv = (defaultOptions.iv);
//  console.log(dat);
//  console.log(iv);
  header['code'] = code;
  header['x-encoder-data'] = dat;
  header['x-iv'] = iv;
  wx.request({
    url: defaultOptions.loginUrl,
    method: 'GET',
    header: header,
    success:function(res){
      if (res.data) {
        console.log("#####login######")
        console.log(res.data)
        console.log("#####login######")
      }
    },
    fail:function(error) {
      console.log(error);
    }

  })
  
};

var login = function login(enData, iv) { 
  if (!defaultOptions.loginUrl) {
    console.log('找不到服务器配置')
    return;
  }
  defaultOptions.endata = enData;
  defaultOptions.iv = iv;
  var doLogin = getWxLoginResult(function (wxLoginError, wxLoginResult) {
    if (wxLoginError) {
      //options.fail(wxLoginError);
      return;
    }
    var userInfo = wxLoginResult.userInfo;
  });
  console.log('after login');

};
var setLoginUrl = function (loginUrl) {
  defaultOptions.loginUrl = loginUrl;
};

var setLoginCk = function() {
  var code = CookieKey.get();
  if (!code) { // 如果本地没有数据，重新请求code
    wx.login({
      success:function(res) {
        if (res.code) {
          CookieKey.set(res.code);
        }
      },
      fail:function() {
        console.log('网络出错');
      }
    })
  }
}

module.exports = {
  LoginError: LoginError,
  login: login,
  setLoginUrl: setLoginUrl,
  setLoginCk:setLoginCk
}
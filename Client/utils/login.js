var noop = function noop() { };
var defaultOptions = {
  method: 'GET',
  success: noop,
  fail: noop,
  loginUrl: 'http://192.168.199.100/a.php',
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
  var code = CookieKey.get()
  if (!code) {
    setLoginCk()
    code = CookieKey.get()
  }
  var header = {};
  header['code'] = code;
  wx.request({
    url: app.globalData.host +'b.php',
    method: 'GET',
    header: header,
    success:function(res){
      if (res.data) {
        console.log("###########")
        console.log(res.data)
      }
    },
    fail:function(error) {
      console.log(error);
    }

  })
  
};

var login = function login(options) { 
  if (!defaultOptions.loginUrl) {
    console.log('找不到服务器配置')
    return;
  }
  var doLogin = getWxLoginResult(function (wxLoginError, wxLoginResult) {
    if (wxLoginError) {
      options.fail(wxLoginError);
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
  if (!CookieKey.get()) { // 如果本地没有数据，重新请求code
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
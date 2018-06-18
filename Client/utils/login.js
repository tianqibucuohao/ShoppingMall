const util = require('./util.js')
var noop = function noop() { };
var defaultOptions = {
  method: 'GET',
  success: noop,
  fail: noop,
  loginUrl: 'http://192.168.199.100/b.php',
  endata: '',
  iv: '',
  bLogin: false
};
var SESSION_KEY = 'session_ck';
var USEY = 'ukey';
var CookieKey = {
  get: function () {
    return wx.getStorageSync(SESSION_KEY || null);
  },
  set: function (data) {
    //    console.log(data)
    try { wx.setStorageSync(SESSION_KEY, data) }
    catch (e) {
      console.log(e.error)
    }
  },
  clear: function () {
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

var getLoginResult = function getLoginCode(callback) {
  wx.login({
    success: function (loginResult) {
      wx.getUserInfo({
        success: function (userResult) {
          callback(null, {
            code: loginResult.code,
            encryptedData: userResult.encryptedData,
            iv: userResult.iv,
            userInfo: userResult.userInfo,
          });
        },

        fail: function (userError) {
          var error = new LoginError(constants.ERR_WX_GET_USER_INFO, '获取微信用户信息失败，请检查网络状态');
          callback(error, null);
        },
      });
    },

    fail: function (loginError) {
      var error = new LoginError(constants.ERR_WX_LOGIN_FAILED, '微信登录失败，请检查网络状态');
      error.detail = loginError;
      callback(error, null);
    },
  });
};


var login = function login(callbak) {
  var userInfo = [];
  try {
    if (!defaultOptions.loginUrl) {
      console.log('找不到服务器配置')
      return;
    }

    var doLogin = getLoginResult(function (wxLoginError, wxLoginResult) {
      //getWxLoginResult(function (wxLoginError, wxLoginResult) {
      if (wxLoginError) {
        //options.fail(wxLoginError);
        return userInfo;
      }
      userInfo = wxLoginResult.userInfo;

      var header = {};
      var dat = wxLoginResult.encryptedData;
      var iv = wxLoginResult.iv;

      header['code'] = wxLoginResult.code;
      header['x-encoder-data'] = dat;
      header['x-iv'] = iv;
      header['nickname'] = wxLoginResult.userInfo.nickName;
      wx.request({
        url: defaultOptions.loginUrl,
        method: 'GET',
        header: header,
        success: function (res) {
          if (res.data) {
            CookieKey.set(res.data.skey);
            callbak(userInfo, null)
          }
        },
        fail: function (error) {
          console.log(error);
        }

      })
    });
  } catch (e) {
    console.log(e);
  }
};
var setLoginUrl = function (loginUrl) {
  defaultOptions.loginUrl = loginUrl;
};

module.exports = {
  LoginError: LoginError,
  login: login,
  setLoginUrl: setLoginUrl
}
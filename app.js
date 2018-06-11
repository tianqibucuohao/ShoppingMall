//app.js
var ck = require("./utils/login.js")
App({
  onShow: function (path, scene) {
    console.log('sb gun')
  },
  onHide: function () {
    console.log("let`s go ahead")
  },
  onError: function (msg) {
    console.log(msg)
  },
  onLaunch: function (path, scene) {
    // 打开小程序的路径
    console.log(this.path)
    console.log(this.scene)
    ck.setLoginCk()
  },
 
  globalData: {
    userInfo: null,
    logged: false,
    takeSession: false,
    requestResult: '',
    shop:{
      goods:[],
      date:'',
      sum:0
    },
    host:'http://192.168.199.100/'
  }
  
})
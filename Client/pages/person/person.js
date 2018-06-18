// pages/sort/sort.js
const app = getApp()
const util = require("../../utils/login.js")
const common = require("../../utils/util.js")
Page({
  /**
   * 页面的初始数据
   */
  data: {
    motto: 'sort',
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo')
  },

  /**
   * 生命周期函数--监听页面加载
   * 一个页面只会调用一次，可以在 onLoad 中获取打开当前页面所调用的 query 参数。
   */
  onLoad: function (options) {
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
      console.log("app.globalData.userinfo true.")
    } else if (this.data.canIUse) {
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
      console.log("this.data.canIUse true.")
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          app.globalData.hasUserInfo = true;
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
      console.log("this.data.geterror")
    }
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   * 一个页面只会调用一次，代表页面已经准备妥当，可以和视图层进行交互。
      对界面的设置如wx.setNavigationBarTitle请在onReady之后设置。
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   * 每次打开页面都会调用一次。
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   * 当navigateTo或底部tab切换时调用。
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   * 当redirectTo或navigateBack的时候调用。
   */
  onUnload: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    common.ShareToFriends();
  },

  onReachBottomDistance: function() {

  },
  getUserInfo : function(ev) {
    var sret = ev.detail.errMsg.search('ok');
    if (sret == -1) {
      
      return;
    }
    var that = this;
    console.log(ev.detail.errMsg)
    console.log(ev.detail.userInfo)
    util.login(function (suc, fail){
      if (suc) {
        console.log('usc +'+suc);
        that.setData({
          userInfo: suc,
          hasUserInfo: true,
        })
        app.globalData.userInfo = suc;
        app.globalData.hasUserInfo = true;
      } else {

      }
     });
  },
  GotoTrolley:function() {
    wx.switchTab({
      url: '/pages/trolley/trolley',
    })
  },
  showhistoryorder:function() {
    var code = common.GetStorage();
    var Url = '/pages/trolley/trolley?histroy=' + 1;
    console.log(Url);
    wx.navigateTo({
      url: Url
    })
  }

  
})
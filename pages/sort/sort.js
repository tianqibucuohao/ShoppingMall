// pages/sort/sort.js
const app = getApp()
const common = require("../../utils/util.js")

Page({
  /**
   * 页面的初始数据
   */
  data: {
    
  },

  /**
   * 生命周期函数--监听页面加载
   * 一个页面只会调用一次，可以在 onLoad 中获取打开当前页面所调用的 query 参数。
   */
  
  onLoad: function (options) {
    
    // 测试用户信息使用-非正式代码内容
    // if (app.globalData.userInfo) {
    //   this.setData({
    //     userInfo: app.globalData.userInfo,
    //     hasUserInfo: true
    //   })
    //   console.log("app.globalData.userinfo true.")
    // } else if (this.data.canIUse) {
    //   // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
    //   // 所以此处加入 callback 以防止这种情况
    //   app.userInfoReadyCallback = res => {
    //     this.setData({
    //       userInfo: res.userInfo,
    //       hasUserInfo: true
    //     })
    //   }
    //   console.log("this.data.canIUse true.")
    // } else {
    //   // 在没有 open-type=getUserInfo 版本的兼容处理
    //   wx.getUserInfo({
    //     success: res => {
    //       app.globalData.userInfo = res.userInfo
    //       this.setData({
    //         userInfo: res.userInfo,
    //         hasUserInfo: true
    //       })
    //     }
    //   })
    //   console.log("this.data.geterror")
    // }
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
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    console.log("on pull down refresh")
  },

  /**
   * 页面滚动触发事件的处理函数
   */
  onPageScroll: function (scrollTop) {
    console.log("on page scroll")
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    console.log("on reach bottome")
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    common.ShareToFriends();
  },

  onReachBottomDistance: function() {

  },
  /**
   * 当前是 tab 页时，点击 tab 时触发
   */
  onTabItemTap: function (item) {
    // console.log(item.index)
    // console.log(item.pagePath)
    // console.log(item.text)
  },
  // Event handler.
  viewTap: function () {
    // this.setData({
    //   text: 'Set some data for updating view.',
    //   data:'hell,sb'
    // }, function () {
    //   // this is setData callback
    //   console.log("click me viewtap")

    // })
  },
  switch: function (e) {
    // const length = this.data.objectArray.length
    // for (let i = 0; i < length; ++i) {
    //   const x = Math.floor(Math.random() * length)
    //   const y = Math.floor(Math.random() * length)
    //   const temp = this.data.objectArray[x]
    //   this.data.objectArray[x] = this.data.objectArray[y]
    //   this.data.objectArray[y] = temp
    // }
    // this.setData({
    //   objectArray: this.data.objectArray
    // })
  },
  ddToFront: function (e) {
    // const length = this.data.objectArray.length
    // this.data.objectArray = [{ id: length, unique: 'unique_' + length }].concat(this.data.objectArray)
    // this.setData({
    //   objectArray: this.data.objectArray
    // })
  },
  addNumberToFront: function (e) {
    // this.data.numberArray = [this.data.numberArray.length + 1].concat       (this.data.numberArray)
    // this.setData({
    //   numberArray: this.data.numberArray
    // })
  }
})
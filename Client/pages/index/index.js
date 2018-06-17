//index.js
//获取应用实例
const app = getApp()
const common = require("../../utils/util.js")
const selflogin = require("../../utils/login.js")
Page({
  data: {
    imgUrls:[
      { url: app.globalData.host+"upload/stdpic/1.jpg"},
      { url: app.globalData.host +"upload/stdpic/2.jpg" },
      { url: app.globalData.host +"upload/stdpic/3.jpg" },
      { url: app.globalData.host +"upload/stdpic/4.jpg" }
    ],
    indicatorDots: false,
    autoplay: true,
    interval: 5000,
    duration: 1000,  
    circular:true,
    imagewidth:0,
    imageheight:0,
    //
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    sb:'u r sb',
    goodsInfo:[],
    sorts: [],
    adgoods_w:0,
    adgoods_h:0
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    this.getDataFromIf()
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
      console.log("app.globalData.userinfo true.")
    } else if (this.data.canIUse){
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
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        },
        fail: res => {
          
        }
      })
      console.log("this.data.geterror")
    }
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  clickMe:function(){
    console.log("cliek me")
  },
  getDataFromIf:function()
  {
    common.showloading();
    var that = this;
    wx.request({
      url: app.globalData.host +'a.php',
      method: 'GET',
      header: {
        'Content-Type': 'application/json'
      },
      dataType:"json",
      success: function (res) {
        console.log(res.data)
        if (res.statusCode == 200) {
          var db = res.data
          that.suc(db)
          that.setData({
            goodsInfo:res.data.data,
            sorts:res.data.sort,
          })
          
        }
      },
      fail: function (res) {
        console.log(res)
        common.GetNetType()
      },
      complete:function() {
       
      }
    })
  },
  suc:function(e) {
    common.hideloading();
  },
  onShow:function(){
  
  },
  imageLoad: function (e) {
    var imageSize = common.imageUtil(e)
    this.setData({
      imagewidth: imageSize.imageWidth,
      imageheight: imageSize.imageHeight
    })
  },
  onGotUserInfo:function(e) {
    console.log(e.detail.userInfo);
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  switchPageFromSort:function(ev) {
   // console.log(ev.currentTarget.id);
    var x = ev.currentTarget.id;
    var id = 0;
    var name = '';
    for (var i=0; i<this.data.sorts.length; i++) {
      if (this.data.sorts[i].sid == x) {
        id = this.data.sorts[i].sid;
        name = this.data.sorts[i].sname;
      }
    }
    var Url = '/pages/list/list?sortid=' + id + '&title=' + name;
    console.log(Url);
    wx.navigateTo({
      url: Url
    })
  },
  
  adgoods:function(e) {
    var imgsize = common.ResizeImg(e);
    this.setData({
      adgoods_w:imgsize.width,
      adgoods_h: imgsize.height
    })
  },
  clickAdGoods: function (ev) {
    console.log('当前goods.id=' + ev.currentTarget.id);
    var x = ev.currentTarget.id;
    var id = 0;
    var name = '';
    var price = 0;
    var discount = 0;
    var desc = '';
    var pricetype= 0;
    var i = 0;
    for (i = 0; i < this.data.goodsInfo.length; i++) {
      if (this.data.goodsInfo[i].id == x) {
        id = this.data.goodsInfo[i].id;
        name = this.data.goodsInfo[i].name;
        price = this.data.goodsInfo[i].price;
        discount = this.data.goodsInfo[i].discount;
        desc = this.data.goodsInfo[i].desc;
        pricetype = this.data.goodsInfo[i].pricetype;
        break;
      }
    }
    var Url = '/pages/single/single?goodsid=' + id + '&title=' + name + '&desc='+desc+'&price='+price+'&discount='+discount+'&pricetype='+pricetype;
    console.log(Url);
    wx.navigateTo({
      url: Url
    })
  },
  ShowImage:function(e){
    common.ShowImage(e)
  },
  searchtxt:function(e){
    console.log('search==='+e);
  },
  onShareAppMessage: function () {
    common.ShareToFriends();
  },
})

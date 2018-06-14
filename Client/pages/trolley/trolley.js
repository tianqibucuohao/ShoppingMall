const app = getApp()
const common = require("../../utils/util.js")
Page({
  data: {
    bIsHistroy:false,
    showView:true,
    bills:[],
    date:'',
    sum:0
  },
  onLoad: function (options) {
    this.LoadData();
//    console.log(this.data.bills);
  },
  onReady: function () {

  },
  onShow: function () {
    //console.log('购物篮正在显示...');
    this.LoadData();
//    console.log(this.data.bills);
  },
  onHide: function () {

  },
  onUnload: function () {

  },
  onPullDownRefresh: function () {

  },
  onReachBottom: function () {

  },
  onShareAppMessage: function () {

  },
  onSwithMainPage:function(){
    wx.switchTab({
      url: '/pages/index/index',
    })
  },
  LoadData:function() {
//    console.log('购物篮正在加载....')
    var glist =[];
    try {
      glist = wx.getStorageSync('goods');
    } catch (ev) {
      console.log('get bills=' + ev);
    }
    if (glist) {
      this.setData({
        showView:false,
        bills:glist.goods,
        date: glist.date,
        sum: glist.sum
      })
    }
  },
  SwitchPage(ev) {
    console.log(ev);
    var x = ev.currentTarget.id;
    var id = 0;
    var name = '';
    var price = 0;
    var discount = 0;
    var desc = '';
    var pricetype = 0;
    var i = 0;
    for (i = 0; i < app.globalData.shop.goods.length; i++) {
      if (app.globalData.shop.goods[i].id == x) {
        id = app.globalData.shop.goods[i].id;
        name = app.globalData.shop.goods[i].name;
        price = app.globalData.shop.goods[i].price;
        discount = app.globalData.shop.goods[i].discount;
        desc = app.globalData.shop.goods[i].desc;
        pricetype = app.globalData.shop.goods[i].pricetype;
        break;
      }
    }
    var Url = '/pages/single/single?goodsid=' + id + '&title=' + name + '&desc=' + desc + '&price=' + price + '&discount=' + discount + '&pricetype=' + pricetype + '&ext=1';
    console.log(Url);
    wx.navigateTo({
      url: Url
    })
  },
  buy :function() {
    console.log('buy them all');
    var ukey = wx.getStorageSync('session_ck');
    var Url = app.globalData.host+'gen.php';
    wx.request({
      url: Url,
      method: 'POST',
      header: {
        'Content-Type': 'application/json'
      },
      dataType: "json",
      data:{
        key:ukey,
        bill:app.globalData.shop.goods,
        sum:app.globalData.shop.sum,
        date:app.globalData.shop.date
      },
      success: function (res) {
        console.log(res.data)
      },
      fail: function(res) {
        console.log(res);
      },
      complete:function() {
        console.log('post complete');
      }
    })
  }

})
const app = getApp()
const common = require("../../utils/util.js")
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: '',
    sortid: 0,
    imggoods_w: 0,
    imggoods_h: 0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      title: options.title,
      sortid: options.sortid
    })
    wx.setNavigationBarTitle({
      title: options.title
    }),

      this.GetLittleSort(this.data.sortid)
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  },

  GetLittleSort: function (e) {
    common.showloading();
    var that = this;
    //    console.log("sortid="+e);
    var Url = app.globalData.host + 'sort.php?sortid=' + e;
    console.log(Url);
    wx.request({
      url: Url,
      method: 'GET',
      header: {
        'Content-Type': 'application/json'
      },
      dataType: "json",
      success: function (res) {
        console.log(res.data)
        if (res.statusCode == 200) {
          var db = res.data
          that.suc(db)
          that.setData({
            goodsInfo: res.data.data
          })

        }
      },
      fail: function (res) {
        console.log(res)
        common.GetNetType()
      },
      complete: function () {
        console.log("complete sort");
      }
    })
  },
  suc: function (e) {
    common.hideloading();
  },
  clickGoods: function (ev) {
    console.log('当前goods.id=' + ev.currentTarget.id);
    var x = ev.currentTarget.id;
    var id = 0;
    var name = '';
    var price = 0;
    var discount = 0;
    var desc = '';
    var pricetype = 0;
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
    var Url = '/pages/single/single?goodsid=' + id + '&title=' + name +'&desc='+ desc + '&price=' + price + '&discount=' + discount + '&pricetype=' + pricetype;
    console.log(Url);
    wx.navigateTo({
      url: Url
    })
  },
  Resize: function (e) {
    var imgsize = common.ResizeImg(e);
    this.setData({
      imggoods_w: imgsize.width,
      imggoods_h: imgsize.height
    })
  },
  ShowImage:function(e) {
   common.ShowImage(e)
  }
 
}
)
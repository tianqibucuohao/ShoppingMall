const app = getApp()
const common = require("../../utils/util.js")

Page({
  /**
   * 页面的初始数据
   */
  data: {
    gid:0,
    name:"",
    desc:'',
    pricetype:0,
    price:0,
    count:0,
    sum:0,
    bShowDelete:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var nshow = parseInt(options.ext);
    var bshow = false;
    if (nshow == 1)
        bshow = true;
    this.showloading();
    var goods = [];
    try {
      goods = wx.getStorageSync('goods');
    } catch (ev) {
      console.log('getdata=' + ev);
    }
    var title = '', goodsid=0,name='',price=0,count =0, sum=0, desc1='',pricetype='';
    goodsid = options.goodsid;
    var bFind = false;
    if (goods){
      var len = goods.length;
      for (var i=0; i<len; i++ ){
        if (goodsid == goods[i].id) {
          goodsid = goods[i].id;
          title = goods[i].name;
          name = goods[i].name;
          price = goods[i].price;
          count = goods[i].count;
          sum = count *price;
          desc1 = goods[i].desc;
          pricetype = goods[i].pricetype;
          bFind = true;
          break;
        }
      }
    }
    if (!bFind) {
      title = options.title;
      name = options.title;
      desc1 = options.desc;
      price = parseFloat(options.price);
      pricetype = options.pricetype
    }
    this.setData({
      title: title,
      gid: goodsid,
      name:title,     
      desc:desc1,
      price:price,
      pricetype: pricetype,
      count:count,
      sum:price*count,
      bShowDelete: bshow
    })
    wx.setNavigationBarTitle({
      title: options.title
    }),

    this.SetCurrentData(this.data.gid);
    this.GetAllImage(this.data.gid);
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
  //  this.SetCurrentData(this.data.gid);
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
    common.ShareToFriends();
  },
  SetCurrentData:function(gid) {
    var x = gid;
    var bFind = false;
    if (app.globalData.shop.goods) {
      var len = app.globalData.shop.goods.length;
      for (var i = 0; i < len; i++) {
        if (x == app.globalData.shop.goods[i].id) {
          this.setData({
            name:app.globalData.shop.goods[i].name,
            desc: app.globalData.shop.goods[i].desc,
            count: app.globalData.shop.goods[i].count,
            price: app.globalData.shop.goods[i].price,
            pricetype: app.globalData.shop.goods[i].pricetype,
            sum: app.globalData.shop.goods[i].count * app.globalData.shop.goods[i].price
          })
          bFind = true;
        }
      }
    }
    // 出错-重新保存到app.shop.goods中
    // if (!bFind) {
    //     var gd = {};
    //     gd.id = x;
    //     gd.name = this.data.name;
    //     gd.price = this.data.price;
    //     gd.discountid = this.data.discountid;
    //     gd.count = this.data.count;
    //     gd.sum = gd.count * gd.price;
    //     gd.desc = this.data.desc;
    //     gd.pricetype = this.data.pricetype;
    //     app.globalData.shop.goods.push(gd);
    //     app.globalData.shop.sum = gd.sum + app.globalData.shop.sum;
    //     app.globalData.shop.date = common.formatTime(new Date());
    //     common.SaveStorage('goods', app.globalData.shop);
    // }
  },
  Add: function (e) {
    console.log('###Add###');
    var bFind = false;
    var x = e.currentTarget.id;
    if (app.globalData.shop.goods) {
      var len = app.globalData.shop.goods.length;
      for (var i = 0; i < len; i++) {
        if (x == app.globalData.shop.goods[i].id) {
          var price = 0;
          price = app.globalData.shop.goods[i].price;
          app.globalData.shop.goods[i].count += 1;
          app.globalData.shop.goods[i].sum = app.globalData.shop.goods[i].count * price;
          app.globalData.shop.sum +=  price;
          app.globalData.shop.date = common.formatTime(new Date());
          this.setData({
            count:app.globalData.shop.goods[i].count,
            sum: app.globalData.shop.goods[i].sum
          })
          common.SaveStorage('goods', app.globalData.shop);
          bFind = true;
        }
      }
    }
    if (!bFind) {
        if (x == this.data.gid) {
          var gd = {};
          gd.id = x;
          gd.name = this.data.name;
          gd.price = this.data.price;
          gd.discountid = this.data.discountid;
          gd.desc = this.data.desc;
          gd.pricetype = this.data.pricetype;
          gd.count = 1;
          gd.sum = gd.count * gd.price;
          app.globalData.shop.date = common.formatTime(new Date());
          app.globalData.shop.sum += gd.sum;
          app.globalData.shop.goods.push(gd);
          common.SaveStorage('goods', app.globalData.shop);
          this.setData({
            count: gd.count ,
            sum: gd.sum
          })
        }
    }
  },
  reduce: function (e) {
    console.log('###miuns###');
    var bFind = false;
    var x = e.currentTarget.id;
    if (app.globalData.shop.goods) {
      var len = app.globalData.shop.goods.length;
      for (var i = 0; i < len; i++) {
        if (x == app.globalData.shop.goods[i].id) {
          var res = 0;
          res = app.globalData.shop.goods[i].count;
          if (res >= 1) {
            var price = 0;
            price = app.globalData.shop.goods[i].price;
            app.globalData.shop.goods[i].count -= 1;
            res -= 1;
            app.globalData.shop.goods[i].sum = app.globalData.shop.goods[i].count * price;
            app.globalData.shop.sum -= price;
            app.globalData.shop.date = common.formatTime(new Date());
            this.setData({
              count: app.globalData.shop.goods[i].count,
              sum: app.globalData.shop.goods[i].sum
            })
          }
          console.log('删除的i='+(i));
         if ( res == 0) {
           //if ((i+1) <= len)
             app.globalData.shop.goods.splice((i), 1); //  当前没有数量，删除该商品
          }
          common.SaveStorage('goods', app.globalData.shop);
          bFind = true;
          break;
        }
      }
    }
    // if (!bFind) {
    //   for (var i = 0; i < this.data.goodsInfo.length; i++) {
    //     if (x == this.data.goodsInfo[i].id) {
    //       var gd = {};
    //       gd.id = x;
    //       gd.name = this.data.name;
    //       gd.price = this.data.price;
    //       gd.discountid = this.data.discountid;
    //       gd.count = this.count;
    //       gd.sum = gd.count * gd.price;
    //       app.globalData.shop.goods.push(gd);
    //       break;
    //     }
    //   }
    // }
  },
  GetAllImage:function(id) {
    var that = this;
    var Url = app.globalData.host + 'img.php?gid=' + id;
//    console.log(Url);
    wx.request({
      url: Url,
      method: 'GET',
      header: {
        'Content-Type': 'application/json'
      },
      dataType: "json",
      success: function (res) {
        
        if (res.statusCode == 200) {
          that.setData({
            imgUrls: res.data.url
          })
          console.log(that.data.imgUrls);
        }
      },
      fail: function (res) {
        console.log(res)
        common.GetNetType()
      },
      complete: function () {
//        console.log("complete imgurl");
        that.hideing();
      }
    })
  },
  showloading:function() {
    common.showloading()
  },
  hideing:function() {
    common.hideloading()
  },
  ShowImg:function(urls) {
    console.log(urls);
    common.ShowImage(urls);
  },
  deletethis:function(id){
    var x = this.data.gid;
    if (app.globalData.shop.goods) {
      var len = app.globalData.shop.goods.length;
      for (var i = 0; i < len; i++) {
        if (x == app.globalData.shop.goods[i].id) {
          var res = 0;
          res = app.globalData.shop.goods[i].count;
          if (res >= 1) {
            var price = 0;
            price = app.globalData.shop.goods[i].price;
            app.globalData.shop.goods[i].count -= 1;
            res -= 1;
            app.globalData.shop.goods[i].sum = app.globalData.shop.goods[i].count * price;
            app.globalData.shop.sum -= price;
            app.globalData.shop.date = common.formatTime(new Date());
            this.setData({
              count: app.globalData.shop.goods[i].count,
              sum: app.globalData.shop.goods[i].sum
            })
          }
          console.log('删除的i=' + (i));
          if (res == 0) {
            //if ((i+1) <= len)
            app.globalData.shop.goods.splice((i), 1); //  当前没有数量，删除该商品
          }
          common.SaveStorage('goods', app.globalData.shop);
          break;
        }
      }
    }
  }

})
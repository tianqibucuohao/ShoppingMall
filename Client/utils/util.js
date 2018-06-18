const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}
// 图片大小处理
function imageUtil(e) {
  var imageSize = {};
  var originalWidth = e.detail.width;//图片原始宽  
  var originalHeight = e.detail.height;//图片原始高  
  var originalScale = originalHeight / originalWidth;//图片高宽比  
  // console.log('originalWidth: ' + originalWidth)
  // console.log('originalHeight: ' + originalHeight)
  //获取屏幕宽高  
  wx.getSystemInfo({
    success: function (res) {
      var windowWidth = res.windowWidth;
      var windowHeight = res.windowHeight;
      var windowscale = windowHeight / windowWidth;//屏幕高宽比  
      // console.log('windowWidth: ' + windowWidth)
      // console.log('windowHeight: ' + windowHeight)
      if (originalScale < windowscale) {//图片高宽比小于屏幕高宽比  
        //图片缩放后的宽为屏幕宽  
        imageSize.imageWidth = windowWidth;
        imageSize.imageHeight = (windowWidth * originalHeight) / originalWidth;
      } else {//图片高宽比大于屏幕高宽比  
        //图片缩放后的高为屏幕高  
        imageSize.imageHeight = windowHeight;
        imageSize.imageWidth = (windowHeight * originalWidth) / originalHeight;
      }

    }
  })
  // console.log('缩放后的宽: ' + imageSize.imageWidth)
  // console.log('缩放后的高: ' + imageSize.imageHeight)
  return imageSize;
}  
//
function ResizeImg(e) {
  var imgsize= {};
 // console.log(e);
  var originalWidth = e.detail.width;//图片原始宽  
  var originalHeight = e.detail.height;//图片原始高 
  wx.getSystemInfo({
    success: function (res) {
      var windowWidth = res.windowWidth;
      var windowHeight = res.windowHeight;
      imgsize.width = windowWidth/4;
      var ss = originalWidth/originalHeight;
      imgsize.height = imgsize.width*ss;
    }
  })  
  return imgsize;
}
// 判断当前网络状态
function GetNetType() {
  var bRet = false;
  wx.getNetworkType({
    success: function (res) {
      // 返回网络类型, 有效值：
      // wifi/2g/3g/4g/unknown(Android下不常见的网络类型)/none(无网络)
      var networkType = res.networkType
      if (networkType == none || networkTyp == unKnown) {
        showBusy('当前没有网络连接，请重试')
      }
      else
        bRet = true;
    },
    fail: function () {
      showBusy('当前没有网络连接，请重试')
    }
  })
  return bRet;
}

/*
* 提示类
*/
// 显示繁忙提示
var showBusy = text => wx.showToast({
  title: text,
  icon: 'loading',
  duration: 10000
})

// 显示成功提示
var showSuccess = text => wx.showToast({
  title: text,
  icon: 'success'
})

// 显示失败提示
var showModel = (title, content) => {
  wx.hideToast();
  wx.showModal({
    title,
    content: JSON.stringify(content),
    showCancel: false
  })
};
function showloading() {
  loading('加载中' );
};
function loginloading() {
  loading('正在登录...')
}
function loading(tips) {
  wx.showLoading({
    title: tips,
  })
}
function hideloading() {
  wx.hideLoading();
}
//
var extend = function extend(target) {
  var sources = Array.prototype.slice.call(arguments, 1);

  for (var i = 0; i < sources.length; i += 1) {
    var source = sources[i];
    for (var key in source) {
      if (source.hasOwnProperty(key)) {
        target[key] = source[key];
      }
    }
  }

  return target;
};

function ShowImage(e) {
  var url = e.currentTarget.id;
  console.log('show url:'+url);
  
  wx.previewImage({
    urls: [url],
    fail:function(ev) {
      console.log(ev);
    }
  })
};

/*
* 存储相关
*/
function SaveStorage(e,b){
  try {
  wx.setStorage({
    key:e,
    data:b
  })
  } catch(ev) {
    console.log(ev)
  }
};

var SESSION_KEY = 'session_ck';
function GetStorage() {
  return wx.getStorageSync(SESSION_KEY || null);
};
/*
* 商品数量增加或减少
*/
// function Add(id) {
//   var bFind = false;
//   return bFind;
// }
// function Reduce(id) {
//   var bFind = false;
//   return bFind;
// }
/*
mobile info
*/
function PostMobileInfo() {
  wx.getSystemInfo({
    success: function (res) {
      
    }
  })
}
/*
* 分享给朋友
*/
function ShareToFriends(){
  console.log("on share app message")
  return {
    title: '在线购物',
    path: '/page/index/index'
  }
}

/*
登录相关
*/

module.exports = {
  formatTime: formatTime,
  imageUtil: imageUtil ,
  showBusy: showBusy,
  showSuccess: showSuccess,
  showModel: showModel,
  ResizeImg: ResizeImg,
  ShowImage:ShowImage,
  SaveStorage: SaveStorage,
  GetStorage: GetStorage,
  ShareToFriends: ShareToFriends,
  showloading:showloading,
  hideloading: hideloading,
  loginloading: loginloading
}

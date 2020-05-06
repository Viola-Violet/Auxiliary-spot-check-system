// pages/chakan/chakan.js
Page({
  /**
   * 页面的初始数据
   */
  data:{
    showData:{},
    dateValue:" - - ",
    department:'',
    grade:''

  },


  datePickerBindchange:function(e){
    this.setData({
     dateValue:e.detail.value
    })
   },

   //搜索记录
   onSearch:function(e){
     var that = this
    wx.request({
      url: getApp().globalData.server + '/cqcq/public/index.php/index/Checkresults/specifiedDate',
      data: {
        department:that.data.department,
        grade:that.data.grade,
        date:that.data.dateValue
      },
      method: "POST",
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      success: function (res) {
        if (res.data.error_code == 1) {
          wx.showModal({
            title: '提示！',
            showCancel: false,
            content: res.data.msg,
            success: function (res) { }
          })
        }
        else if (res.data.error_code == 2) {
          wx.showModal({
            title: '提示！',
            showCancel: false,
            content: res.data.msg,
            success: function (res) { }
          })
        }
        else if (res.data.error_code != 0) {
          wx.showModal({
            title: '哎呀～',
            content: '出错了呢！' + res.data.msg,
            success: function (res) {
              if (res.confirm) {
                console.log('用户点击确定')
              } else if (res.cancel) {
                console.log('用户点击取消')
              }
            }
          })
        }
        else if (res.data.error_code == 0) {
          that.setData({
            showData:res.data.data
          })
        }
      },
      fail: function (res) {
        wx.showModal({
          title: '哎呀～',
          content: '网络不在状态呢！',
          success: function (res) {
            if (res.confirm) {
              console.log('用户点击确定')
            } else if (res.cancel) {
              console.log('用户点击取消')
            }
          }
        })
      },
      complete:function(res){
        wx.hideLoading()
      }
    })
   },

   //全部记录
   onAll:function(e){
     var that = this
     that.onLoad()
   },

   //获取全部记录
  onLoad: function(options) {
    this.setData({
      grade: getApp().globalData.user.grade,
      department: getApp().globalData.user.department
    })
    var that=this
    wx.showLoading({
      title: '加载中',
    })
    wx.request({
      url: getApp().globalData.server + '/cqcq/public/index.php/index/Checkresults/checkRecords',
      data: {
        department:that.data.department,
        grade:that.data.grade
      },
      method: "POST",
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      success: function (res) {
        if (res.data.error_code == 1) {
          wx.showModal({
            title: '提示！',
            showCancel: false,
            content: res.data.msg,
            success: function (res) { }
          })
        }
        else if (res.data.error_code == 2) {
          wx.showModal({
            title: '提示！',
            showCancel: false,
            content: res.data.msg,
            success: function (res) { }
          })
        }
        else if (res.data.error_code != 0) {
          wx.showModal({
            title: '哎呀～',
            content: '出错了呢！' + res.data.msg,
            success: function (res) {
              if (res.confirm) {
                console.log('用户点击确定')
              } else if (res.cancel) {
                console.log('用户点击取消')
              }
            }
          })
        }
        else if (res.data.error_code == 0) {
          that.setData({
            showData: res.data.data,
          })
          
        }
      },
      fail: function (res) {
        wx.showModal({
          title: '哎呀～',
          content: '网络不在状态呢！',
          success: function (res) {
            if (res.confirm) {
              console.log('用户点击确定')
            } else if (res.cancel) {
              console.log('用户点击取消')
            }
          }
        })
      },
      complete:function(res){
        wx.hideLoading()
      }
    })
    setTimeout(function() {
      wx.hideLoading()
    },2000)
  },
  
  //删除记录
  onLike: function (e) {
    var that=this
    wx.showModal({
      title: '提示',
      content: '您确认删除记录吗？操作不可恢复!',
      success: function (res) {
      if (res.confirm) {
        console.log('用户点击确定')
        wx.request({
          url: getApp().globalData.server + '/cqcq/public/index.php/index/Checkresults/deleteRecord',
          data: {
            department:that.data.department,
        grade:that.data.grade,
            start_time:e.target.dataset.time
          },
          method: "POST",
          header: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          success: function (res) {
            if (res.data.error_code == 1) {
              wx.showModal({
                title: '提示！',
                showCancel: false,
                content: res.data.msg,
                success: function (res) { }
              })
            }
            else if (res.data.error_code == 2) {
              wx.showModal({
                title: '提示！',
                showCancel: false,
                content: res.data.msg,
                success: function (res) { }
              })
            }
            else if (res.data.error_code != 0) {
              wx.showModal({
                title: '哎呀～',
                content: '出错了呢！' + res.data.msg,
                success: function (res) {
                  if (res.confirm) {
                    console.log('用户点击确定')
                  } else if (res.cancel) {
                    console.log('用户点击取消')
                  }
                }
              })
            }
            else if (res.data.error_code == 0) {
          wx.showModal({
            success: function (res) {
              if (res.confirm) {
                console.log('用户点击确定')
                that.onLoad()
              } else if (res.cancel) {
                console.log('用户点击取消')
              }
            },
            title: '恭喜！',
            showCancel: false,
            content: '删除成功',
          })
        }
          },
          fail: function (res) {
            wx.showModal({
              title: '哎呀～',
              content: '网络不在状态呢！',
              success: function (res) {
                if (res.confirm) {
                  console.log('用户点击确定')
                } else if (res.cancel) {
                  console.log('用户点击取消')
                }
              }
            })
          },
          complete:function(res){
          }
        })
      }
      else if (res.cancel) {
        console.log('用户点击取消')
      }
    }
    })
  },

  //查看跳转
  onClick: function (e) {
    wx.navigateTo({
      url: "../teacher_details/teacher_details?time=" + e.target.dataset.times
    })
  },
})
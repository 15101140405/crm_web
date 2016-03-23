// 初始化应用程序和存储，为进一步获得其方法MyApp变量
//var myApp = new Framework7();


//可以自定义配置参数
var myApp = new Framework7({
    //materialPageLoadDelay:0,   //延迟（在毫秒）之前的动画加载页。可以增加一点来提高性能
    //materialRipple:'true'       //启用/禁用特有的触摸纹波效应
});

// 我们需要使用自定义DOM类库，让我们将它保存到$$变量：
var $$ = Framework7.$;

// 添加视图
var mainView = myApp.addView('.view-main', {
    // 因为我们要用动态的导航栏，我们需要使它的这一观点：
    dynamicNavbar: true
});

// 现在我们需要运行仅用于页面的代码。
// 在这种情况下，我们需要添加事件侦听器的“pageinit”事件

// 选择1。用一pageinit”事件处理程序的所有页面（推荐的方法）：
$$(document).on('pageInit', function (e) {
    // 从事件数据获取页数据
    var page = e.detail.page;

    //if (page.name === 'form') {
    //    // 下面的代码将被执行的页面与数据页的属性等于“about”
    //    myApp.alert('跳转成功');
    //}
})

//选择2。用活的pageinit”事件处理程序的每一页
$$(document).on('pageInit', '.page[data-page="form"]', function (e) {
    // 下面的代码将被执行的页面与数据页的属性等于“about”
    myApp.alert('Here comes About page');
})


$$('.action1').on('click', function () {
    myApp.alert('Action 1');
});
$$('.action2').on('click', function () {
    myApp.alert('Action 2');
});


//下拉刷新
// 随意编造的内容
var songs = ['Yellow Submarine', 'Don\'t Stop Me Now', 'Billie Jean', 'Californication'];
var authors = ['Beatles', 'Queen', 'Michael Jackson', 'Red Hot Chili Peppers'];

// 下拉刷新页面
var ptrContent = $$('.pull-to-refresh-content');

// 添加'refresh'监听器
ptrContent.on('refresh', function (e) {
    // 模拟2s的加载过程
    setTimeout(function () {
        // 随机图片
        //var picURL = 'img/i-f7-ios.png' + Math.round(Math.random() * 100);
        var picURL = 'img/i-f7-material.png';
        // 随机音乐
        var song = songs[Math.floor(Math.random() * songs.length)];
        // 随机作者
        var author = authors[Math.floor(Math.random() * authors.length)];
        // 列表元素的HTML字符串
        var itemHTML = '<li class="item-content">' +
            '<div class="item-media"><img src="' + picURL + '" width="44"/></div>' +
            '<div class="item-inner">' +
            '<div class="item-title-row">' +
            '<div class="item-title">' + song + '</div>' +
            '</div>' +
            '<div class="item-subtitle">' + author + '</div>' +
            '</div>' +
            '</li>';
        // 前插新列表元素
        ptrContent.find('ul').prepend(itemHTML);
        // 加载完毕需要重置
        myApp.pullToRefreshDone();
    }, 2000);
});



//获取表单的数据
$$('.form-from-json').on('click', function(){
    var formData = myApp.formToJSON('#my-form');
    alert(JSON.stringify(formData));
});


//填充表单数据
$$('.form-from-json1').on('click', function(){

    var formData = {
        'name': 'John',
        'email': 'john@doe.com',
        'gender': 'female',
        'switch': ['yes'],
        'slider': 10
    }
    myApp.formFromJSON('#my-form1', formData);
});




//图片浏览器
var myPhotoBrowserStandalone = myApp.photoBrowser({
    swipeToClose:true,
    theme:'dark',
    backLinkText:'关闭',
    lazyLoading:true,
    lazyLoadingOnTransitionStart:true,

    photos : [

        {
            url: 'http://lorempixel.com/1024/1024/sports/1/',
            caption: '标题1'
        },
        {
            url: 'http://lorempixel.com/1024/1024/sports/2/',
            caption: '标题2'
        },
        {
            html: '<div style="width: 500px;height: 200px;background: #ccc;">1111111111111111</div>',
            caption: '标题3'
        }
    ]

});
//点击时打开图片浏览器
$$('.pb-standalone').on('click', function () {
    myPhotoBrowserStandalone.open();

});


//返回顶部
myApp.upscroller('Go up');




//时间



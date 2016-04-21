$(function () {
    var pro_num=66;//用于接收当前进度
    $('#progress').progress({
        initValue: pro_num
        , radius: 120
        , barWidth: 5
        , curbarWidth: 11
        , roundCorner: true
        , barBgColor: '#353743'
        , barColor: '#66d6e4'
        , basecirc: Math.PI * 0.25
        , basequare: Math.PI * 0.75
        , circ: Math.PI * (2 - 0.5)
        , quart: Math.PI * 1.25
        , percentage: false
    , });
})
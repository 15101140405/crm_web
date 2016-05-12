!(function ($) {
    var DropDownList = function (ele, options) {
        this.$ele = $(ele);
        this.$ele.on('change', $.proxy(this.select, this));
    }
    DropDownList.prototype.select = function () {
        var opt_txt = this.$ele.val();
        this.$ele.prev().find(".select_con").html(opt_txt).css('color', '#333');
        if (opt_txt == '请选择') {
            this.$ele.prev().css('color', '#999');
        }
    }

    $.fn.DropDownList = function (options) {
        this.each(function () {
            var Dropdown = new DropDownList(this, options);
        });
    }
})(jQuery);
$(function () {
    $(".select_list").DropDownList();
})
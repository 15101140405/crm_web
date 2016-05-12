!(function ($) {
    var Input = function (ele, options) {
        this.$ele = $(ele);
        this.default_val=this.$ele.val();
        this.init();
    }
    Input.prototype = {
        init: function () {
            this.$ele.on('focus', $.proxy(this.focus, this));
            this.$ele.on('input', $.proxy(this.input, this));
            this.$ele.on('blur', $.proxy(this.blur, this));
        },
        input: function () {
            var opt_txt1 = this.$ele.val();
            this.$ele.css('color', '#333');
            if (opt_txt1 == this.default_val) {
                this.$ele.css('color', '#999');
            }
        },
        focus: function () {
            if (this.$ele.val() == this.default_val) {
                this.$ele.val('');
            }

        },
        blur: function () {
            if (this.$ele.val() == '') {
                this.$ele.val(this.default_val);
                this.$ele.css('color', '#999');
            }
        }
    }
    $.fn.input = function (options) {
        this.each(function () {
            var input = new Input(this, options);
        });
    }
})(jQuery);
$(function () {
    $(".input_in").input();
})
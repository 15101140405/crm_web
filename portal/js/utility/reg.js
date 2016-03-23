// 输入验证插件
;(function ($) {

    $.regex = $.regex || {};

    /* Check string
     * =============================== */
    $.extend($.regex, {
        isEmail: function (email) {
            return (/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email));
        },
        isPhone: function (phone) {
            return /^((\d{11})|(\d{3}-\d{8})|(\d{4}-\d{7})|(\d{3}-\d{4}-\d{4}))$/.test(phone);
        },
        isMobile: function (mobile) {
            return /^0?(13\d|15[012356789]|18[0236789]|14[57])-?\d{3}-?\d{1}-?\d{4}$/.test(mobile);
        },
        isIDCard: function (idcard) {
            return /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|(x|X))$/.test(idcard);
        },
        isPostcode: function (postcode) {
            return /[1-9]\d{5}/.test(postcode);
        },
        isUrl: function (url) {
            if (!url) return false;
            var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
                + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@
                + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
                + "|" // 允许IP和DOMAIN（域名）
                + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.
                + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名
                + "[a-z]{2,6})" // first level domain- .com or .museum
                + "(:[0-9]{1,4})?" // 端口- :80
                + "((/?)|" // a slash isn't required if there is no file name
                + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
            var re = new RegExp(strRegex);
            return re.test(url);
        },
        isChinese: function (account) {
            return /^[A-Za-z0-9_-]+$/.test(account);
        }
    });

})(Zepto)
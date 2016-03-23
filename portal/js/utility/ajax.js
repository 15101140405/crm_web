/* ================================
 * Extend jQuery to support ajax shorthand methods
 * ================================ */
(function ($) {

    /* Extend jQuery to support ajax method $.put(...) and $.delete(...)
     * =============================================== */
    $.each(['get', 'post', 'put', 'delete'], function (i, method) {
        $[method] = function (url, data, callback, type, options) {
            // shift arguments if data argument was omitted
            if ($.isFunction(data)) {
                options = type || options;
                type = callback;
                callback = data;
                data = undefined;
            }
            // hack callback for requiring login
            var success = function (retval) {
                if (retval && !retval.success && retval.value && retval.value.error_code == 'error_7') {
                    console.error('require signin');
                    //location.href = '/signout?ref=' + encodeURIComponent('/signin?ref=' + location.href);
                } else {
                    callback(retval);
                }
            };
            return $.ajax($.extend({
                type: method,
                url: url,
                data: data,
                success: success,
                dataType: type
            }, options));
        };
    });

    /* Extend jQuery to support ajax method ['get', 'post', 'put', 'delete']JSON
     * =============================================== */
    $.each(['get', 'post', 'put', 'delete'], function (i, method) {
        $[method + 'JSON'] = function (url, data, callback) {
            // shift arguments if data argument was omitted
            if ($.isFunction(data)) {
                callback = data;
                data = undefined;
            }
            if (data == undefined) {
                data = {};
            }
            if (method == 'get') {
                if (!$.isEmptyObject(data))
                    url = url + (url.indexOf('?') < 0 ? '?' : '&') + $.param(data);
                data = '';
            }
            var params = [url];
            params.push((method == 'post' || method == 'put' || method == 'delete') ? JSON.stringify(data) : data);
            params.push(callback);
            params.push('json');
            params.push({contentType: 'application/json', processData: false});
            return $[method].apply(this, params);
        };
    });

})(Zepto)
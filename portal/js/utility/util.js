/* ================================
 * Provide some utility methods
 * ================================ */
(function($){

    $.util = $.util || {};

    /* Generate a guid
     * http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
     * ============================== */
    $.util.guid = function() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            return (Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8)).toString(16);
        });
    };

    /* Generate a unique long id
     * http://www.sitepoint.com/forums/showthread.php?318819-Unique-ID-in-Javascript
     * NOTE: the id is pseudo-unique
     * ============================== */
    $.util.uid = function() {
        return (Math.floor((Math.random() + Math.random() + Math.random() + Math.random()) * Math.pow(10, 16))
            + (new Date()).getTime());
    };

    /* Print a value, make 0-9 starts with a '0'
     * =============================== */
    $.util.printNumber = function(int_value) {
        return ((int_value >= 0 && int_value < 10) ? '0' : '') + int_value;
    };

    /* Max and min time for time widget
     * =============================== */
    $.util.MIN_TIME = (new Date(1900, 0, 1, 0, 0, 0, 0)).getTime();
    $.util.MAX_TIME = (new Date(2049, 11, 30, 23, 59, 59, 999)).getTime();

    /* Print a friendly time
     * =============================== */
    $.util.printTimeFriendly = function(time_in_seconds) {
        var text = time_in_seconds < 0 ? '负 ' : ' ';
        var seconds = Math.abs(time_in_seconds);
        var minutes = Math.floor(seconds / 60 + 0.2);
        if(minutes > 0) {
            var hours = Math.floor(minutes / 60); 
            if(hours > 0)
                text += hours + ' 小时';
            text += (minutes % 60) + ' 分钟';
        } else {
            text += seconds + ' 秒钟';
        }
        return text;
    };
    //打印标准格式年月日
    $.util.printDate = function(date) {
        return (date == null) ? null : date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
    };
    //打印星期几
    $.util.printDay = function(date){
        if(date == null )
            return null;
        else if(date.getDay() == 0){
            return 7;
        }else{
            return date.getDay();
        }
    }
    //日期加减
    $.util.addDate = function(date,type,number){//传入日期，加什么？加多少？
        timestamp = Date.parse(date);
        var after_add_date = timestamp;
        var final_date;

        console.log("qian:"+after_add_date);
        switch (type) {
            case 's' :after_add_date += (1000 * number);break;//秒
            case 'n' :after_add_date += (60000 * number);break;//分
            case 'h' :after_add_date += (3600000 * number);break;//时
            case 'd' :
                after_add_date += (86400000 * number);//天
                break;
            case 'w' :
                after_add_date += ((86400000 * 7) * number);//周
                break;
            //case 'q' :return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number*3, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
            case 'm' ://传回此月最后一天00:00:00
                date.setDate(1);
                var temp_date = new Date(date.getFullYear(), (date.getMonth()) + number, date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds());
                temp_date.setDate(1);
                temp_date.setMonth(temp_date.getMonth()+1);
                after_add_date = new Date(Date.parse(temp_date))-86400000;
                break;
            //case 'y' :return new Date((dtTmp.getFullYear() + Number), dtTmp.getMonth(), dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
        }

        var temp_for_date = new Date(parseInt(after_add_date));
        final_date = new Date($.util.printDate(temp_for_date));
        return final_date;
    }

    /* Get and set param and anchor
     * ================================ */
    $.util.param = function(vKey, vValue) {
        if(vValue === undefined) {
            // get
            if(vKey == null) return null;
            var searchstr = location.search;
            if(searchstr==null || $.trim(searchstr)=="") return null;
            var params = searchstr.substr(1, searchstr.length-1).split("&");
            for(var i=0; i<params.length; i++) {
                var keyandvalue = params[i].split("=");
                if(keyandvalue[0]==vKey) 
                    return (keyandvalue[1]);
            }
            return null;
        } else {
            // set
            if(arguments == null) return;
            var ht = {};
            // old params
            var searchstr = location.search;
            if(searchstr != null) {
                var params = searchstr.substr(1, searchstr.length-1).split("&");
                for(var i=0; i<params.length; i++) {
                    if(params[i] == null || $.trim(params[i]) == "")
                        continue;
                    var keyandvalue = params[i].split("=");
                    if($.trim(keyandvalue[0]) != "")
                        ht[keyandvalue[0]] = (keyandvalue[1]);
                }
            }
            // new params
            for (var i=0; i+1<arguments.length; i+=2) {
                var key = $.trim(arguments[i]), value = arguments[i+1];
                if(key==null || key=="")
                    continue;
                ht[key] = value;
            }

            location.search = (function() {
                var retval = "";
                $.each(ht, function(vName, vValue) {
                    if(vValue == null) return;
                    retval += "&" + vName + "=" + (vValue);
                });
                if(retval!="")
                    retval = retval.substr(1, retval.length-1);
                return retval;
            })();

        }
    };

    $.util.anchor = function(vKey, vValue) {
        if(vValue === undefined) {
            // get
            if(vKey == null) return null;
            var anchorstr = jQuery.trim(location.hash);
            if(anchorstr==null || anchorstr=="") return null;
            var params = anchorstr.substr(1, anchorstr.length-1).split("&");
            for(var i=0; i<params.length; i++) {
                var keyandvalue = params[i].split("="),
                    key = jQuery.trim(keyandvalue[0]),
                    value = unescape(keyandvalue[1]);
                if(key==vKey) return value;
            }
            return null;
        } else {
            if(arguments == null) return;
            var ht = {};
            // old params
            var searchstr = location.hash;
            if(searchstr != null && searchstr.length > 0) {
                var params = searchstr.substr(1, searchstr.length-1).split("&");
                for(var i=0; i<params.length; i++) {
                    var p = jQuery.trim(params[i]);
                    if(p == null || p == "") continue;
                    var keyandvalue = p.split("="),
                        key = jQuery.trim(keyandvalue[0]),
                        value = unescape(keyandvalue[1]);
                    if(key != "") ht[key] = value;
                }
            }
            // new params
            for (var i=0; i+1<arguments.length; i+=2) {
                var key = jQuery.trim(arguments[i]), value = arguments[i+1];
                if(key==null || key=="") continue;
                ht[key] = value;
            }
            location.hash = (function() {
                var retval = "";
                $.each(ht, function(vName, vValue) {
                    if(vValue == null) return;
                    retval += "&" + vName + "=" + escape(vValue);
                });
                if(retval.length > 0) retval = retval.substr(1, retval.length-1);
                return retval;
            })();
        }
    };

    /* Disable and enable doms
     * =============================== */
    $.extend($.util, {
        disable: function(doms) {
            $.each(arguments, function(i, v) {
                v.attr('disabled', 'disabled');
            });
        },
        enable: function(doms) {
            $.each(arguments, function(i, v) {
                v.attr('disabled', null);
            });
        }
    });

})(Zepto)
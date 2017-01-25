if (typeof jQuery === "undefined") {
    throw new Error("Application requires jQuery");
}
/**
 * Form helper
 *
 * @author bison 'goldeagle' fan
 * @type {{add: Form.add, del: Form.del, mod: Form.mod, enable: Form.enable, disable: Form.disable, read: Form.read, jump: Form.jump, submit: Form.submit}}
 */
var Form = {
    /**
     * jump to add action view
     * @param url
     */
    add: function (url) {
        Form.jump(url);
    },
    /**
     * jump to del(delete) action view
     * @param url
     * @returns {boolean}
     */
    del: function (url) {
        var res = confirm('是否删除这条数据？');
        if (res) {
            Form.ajaxJump(url);
        } else {
            return false;
        }
    },
    /**
     * jump to mod(modify) action view
     * @param url
     */
    mod: function (url) {
        Form.jump(url);
    },
    /**
     * jump to enable action
     * @param url
     */
    enable: function (url) {
        Form.jump(url);
    },
    /**
     * jump to disable action
     * @param url
     */
    disable: function (url) {
        Form.jump(url);
    },
    /**
     * jump to read action view
     * @param url
     */
    read: function (url) {
        Form.jump(url);
    },
    /**
     * default jump action
     * @access protected
     * @param url
     */
    jump: function (url) {
        window.location.href = url;
    },
    /**
     * ajax 成功后的处理方式，调用方式为 function(data) { Form.ajaxSuccess(data) }
     * @param data
     * @returns {boolean}
     */
    ajaxSuccess: function (data) {
        if (data.code == 0) {
            if (typeof Form.onError != 'undefined' && Form.onError != null) {
                Form.onError(data);
            } else {
                alert(data.msg);
            }
        } else {
            if (data.url != '' && typeof data.url != 'undefined') {
                Form.jump(data.url);
            }
        }
        return false;
    },
    /**
     * ajax 错误处理的方式
     * @returns {boolean}
     */
    ajaxError: function (data) {
        if (typeof Form.onError != 'undefined' && Form.onError != null) {
            if (typeof data == 'undefined') {
                data = {
                    msg: 'Shit always happens!'
                };
            }
            Form.onError(data);
        } else {
            alert('Shit always happens!');
        }
        return false;
    },
    /**
     * Ajax 跳转
     * @param url
     */
    ajaxJump: function (url) {
        $.ajax({
            type: "GET",
            url: url,
            data: [],
            dataType: "json",
            async: false,
            success: function (data) {
                Form.ajaxSuccess(data)
            },
            error: Form.ajaxError()
        });
    },
    /**
     * form submit & result handler with ajax
     * @param form
     * @returns {boolean}
     */
    submit: function (form) {
        var data = new FormData($(form)[0]);

        $.ajax({
            type: "POST",
            url: form.action,
            data: data,
            dataType: "json",
            async: false,
            success: function (data) {
                Form.ajaxSuccess(data)
            },
            error: function (data) {
                alert(data);
                Form.ajaxError(data)
            }
        });
        return false;
    },
    onError: null
};
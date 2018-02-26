/**
 * Created by 188830923 on 2018/2/7.
 */


/**
 *
 * @param url
 * @param checkbox_group
 * @param msg
 * @param return_msg
 * @private
 */
function _del_recycle_all(url, checkbox_group, msg, return_msg) {
    id = [];
    $(":checked[name='" + checkbox_group + "']").each(function () {
        id.push($(this).val())
    });
    if(id.length<1){
        layer.msg('请勾选数据',{icon: 2});
        return false;
    }
    layer.confirm(msg, {
        btn: ['确定', '取消'],
        title: '提示',
        icon: 3
    }, function () {
        $.post(url, {id: id.join(',')}, function (data) {
            if (data.code == 1) {
                parent.layer.msg(return_msg, {icon: 1, time: 3000});
                window.location.reload();
            } else {
                layer.alert(data.msg, {icon: 2});
            }
        }, 'json')
    }, function (index) {
        layer.close(index);
    });
}

/**
 *
 * @param url
 * @param checkbox_group
 * @param msg
 * @private
 */
function _del_all(url, checkbox_group, msg) {
    _del_recycle_all(url, checkbox_group, msg, "已删除！")
}

/**
 * 批量假性删除操作项
 * @param url 批量删除地址，一般为 {:url('delete')}
 * @param checkbox_group checkbox组的名称，默认 id[]
 */
function del_all(url, checkbox_group) {
    _del_all(url, checkbox_group || 'id[]', '删除须谨慎，确认要删除？');
}

/**
 * 假性删除操作项
 * @param obj this
 * @param id 对象id
 * @param url 删除地址，一般为 {:url('delete')}
 * @param fn 回调函数
 */
function del(obj, id, url, fn) {
    _del(obj, id, url, '确定删除此条数据？', fn);
}

function _del(obj, id, url, msg, fn) {
    _del_recycle(obj, id, url, msg, "删除成功！", fn)
}

function _del_recycle(obj, id, url, msg, returnMsg, fn) {
    layer.confirm(msg, {
        btn: ['确定', '取消'],
        title: '提示',
        icon: 3
    }, function () {
        $.post(url, {id: id}, function (data) {
            if (data.code == 1) {
                layer.msg(returnMsg, {icon: 1, time: 1000});
                $(obj).parents("tr").fadeOut();
            } else {
                layer.alert(data.msg);
            }
            fn && fn(data);
        }, 'json')
    }, function (index) {
        layer.close(index);
    });
}
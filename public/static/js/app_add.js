/**
 * Created by 188830923 on 2018/2/9.
 */

function add(url) {
    $("#form-add").validate({

        onkeyup:false,
        focusCleanup:true,
        success:"valid",
        submitHandler:function(form){
            $(form).ajaxSubmit({
                type: 'post', // 提交方式 get/post
                url: url, // 需要提交的 url
                success: function(data) { // data 保存提交后返回的数据，一般为 json 数据
                    //alert(4343);
                    if(data.code!=1){
                        layer.msg(data.msg,{icon:2,time:1000});
                        return false;
                    }
                    layer.msg(data.msg,{icon:1,time:1000});
                    //alert(data.url);
                    setTimeout(function(){
                        parent.window.location.href=data.url;
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    }, 1000);
                }
            });
        }
    });
}
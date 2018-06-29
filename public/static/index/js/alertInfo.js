/**
 * Created by Administrator on 2018/1/4.
 */

/*
* @showAlertAutoClose() 显示提示信息并自动关闭
* @data 为字符串
* */
function showAlertAutoClose(data) {
        $(".bomb p").html(data);
        $(".bomb").show(300).delay(2000).hide(100);

}

/*
 * @jsonAlertAutoClose() 显示传过来的json提示信息并自动关闭
 * @data 为json数据
 * */
function jsonAlertAutoClose(data) {
    if(data.msg != ""){
        $(".bomb p").html(data.msg);
        $(".bomb").show(300).delay(2000).hide(100);
        if(!jQuery.isEmptyObject(data.data)){
            //延迟3秒跳转
            setTimeout(function () { location.href=data.data; }, 3000);

        }
    }

}


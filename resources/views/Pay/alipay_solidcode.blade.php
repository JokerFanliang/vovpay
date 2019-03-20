<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>在线支付</title>
    <link href="{{ asset('css/Pay/pay.css') }}" rel="stylesheet" media="screen">
    <script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
</head>

<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico_log ico-a"></span>
    </h1>
    <div class="mod-ct">
        <h1 style="padding-top:15px;color: #FF0000">1.需要多次付款，请重新发起订单</h1>
        <h1 style="padding-top:15px;color: #FF0000">2.请保存二维码支付(当前不支持H5)</h1>
        <h1 style="padding-top:15px;color: #FF0000">3.请按照以下数值准确输入支付金额!否则无法到账</h1>
        <div class="amount" id="money">￥{{ $data['money'] }}</div>
        <!--支付宝app支付-->
        {{--<div class="paybtn" style="display: none;padding: 10px;">--}}
            {{--<a href="{{ $data['h5url'] }}" id="alipaybtn" class="btn btn-primary" target="_blank">打开支付宝</a>--}}
        {{--</div>--}}
        <div class="qrcode-img-wrapper" data-role="qrPayImgWrapper">
            <div data-role="qrPayImg" class="qrcode-img-area">
                <div class="ui-loading qrcode-loading" data-role="qrPayImgLoading" style="display: none;"></div>
                <div style="position: relative;display: inline-block;">
                    <img id="show_qrcode" src="" width="200">
                </div>
            </div>
        </div>
        <div class="time-item">
            <dvi class="time-item" id="msg"><h1>付款即时到账 未到账可联系我们</h1></dvi>
            <div class="time-item"><h1>订单:{{$data['orderNo']}}</h1> </div>
            <strong id="hour_show"><s id="h"></s>0时</strong>
            <strong id="minute_show"><s></s>0分</strong>
            <strong id="second_show"><s></s>0秒</strong>
        </div>
    </div>
</div>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.qrcode.min.js') }}"></script>
<script type="text/javascript">
var myTimer;
function timer(intDiff) {
    var i = 0;
    myTimer = window.setInterval(function () {
        i++;
        var day = 0,
            hour = 0,
            minute = 0,
            second = 0;//时间默认值
        if (intDiff > 0) {
            day = Math.floor(intDiff / (60 * 60 * 24));
            hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
            minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        $('#hour_show').html('<s id="h"></s>' + hour + '时');
        $('#minute_show').html('<s></s>' + minute + '分');
        $('#second_show').html('<s></s>' + second + '秒');
        if (hour <= 0 && minute <= 0 && second <= 0) {
            qrcode_timeout()
            clearInterval(myTimer);
        }
        intDiff--;
        checkdata();
    }, 1000);
}

function isMobile() {
    var ua = navigator.userAgent.toLowerCase();
    _long_matches = 'googlebot-mobile|android|avantgo|blackberry|blazer|elaine|hiptop|ip(hone|od)|kindle|midp|mmp|mobile|o2|opera mini|palm( os)?|pda|plucker|pocket|psp|smartphone|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce; (iemobile|ppc)|xiino|maemo|fennec';
    _long_matches = new RegExp(_long_matches);
    _short_matches = '1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-';
    _short_matches = new RegExp(_short_matches);
    if (_long_matches.test(ua)) {
        return 1;
    }
    user_agent = ua.substring(0, 4);
    if (_short_matches.test(user_agent)) {
        return 1;
    }
    return 0;
}


// 过期
function qrcode_timeout(){
    $('#show_qrcode').attr("src","{{ asset('images/Pay/qrcode_timeout.png') }}");
    $('.paybtn').hide();
    $('#msg').html("<h1>订单已过期,请重新支付</h1>");
}

function checkdata(){
    $.ajax({
        url: '{{ route('pay.success','exempt') }}',
        data: {"trade_no": "{{$data['orderNo']}}"},
        type:'get',
        dataType:'json',
        success: function (data) {
            console.log(data.status );
            if (data.status == 'success'){
                window.clearInterval(timer);
                $("#show_qrcode").attr("src","{{ asset('images/Pay/pay_ok.png') }}");
                $("#money").text("支付成功");
                $("#msg").html("<h1>订单已支付成功</h1>");
                $(".paybtn").hide();
                clearInterval(myTimer);
            }
        }
    })
}

$().ready(function(){
    timer(180);
    if(isMobile() == 1 && "{{ $data['type'] == 'alipay' }}")
    {
        $('.paybtn').show();
    }

    var strcode = toUtf8('{!! $data['payurl'] !!}');
    var qrcode = $('#show_qrcode').qrcode({ text: strcode });
    var canvas = qrcode.find('canvas').get(0);
    $('#show_qrcode').attr('src', canvas.toDataURL('image/jpg'));
    canvas.remove();
})
function toUtf8(str) {
    var out, i, len, c;
    out = "";
    len = str.length;
    for(i = 0; i < len; i++) {
        c = str.charCodeAt(i);
        if ((c >= 0x0001) && (c <= 0x007F)){
            out += str.charAt(i);
        } else if (c > 0x07FF) {
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
            out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        } else{
            out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        }
    }
    return out;
}
</script>
</body>
</html>
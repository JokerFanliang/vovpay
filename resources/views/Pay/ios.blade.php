<html>
<head>
    <meta charset="utf-8" />
    <title>正在跳转支付页面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Pay/alipay.css') }}" />
    <link rel="stylesheet" href="https://gw.alipayobjects.com/os/s/prod/i/index-bd57f.css">
    <style>
        .divid{text-align: center;margin-top: 70px}
        .divid a{padding:15px 30px; background: #00a8f2;border-radius: 3px;color:#fff;font-size: 16px}
    </style>
</head>
<body>
<div class="am-loading am-loading-refresh">
    <div class="am-loading-indicator" aria-hidden="true">
        <div class="am-loading-item"></div>
        <div class="am-loading-item"></div>
        <div class="am-loading-item"></div>
    </div>
    <div class="am-loading-text">支付打开中，请耐心等候</div>
</div>
<div class="am-hairline am-hairline-all" style="text-align: center;">
    <div class="wap_code wrap" style="display: block;">
        <div class="load">
            <img src="data:image/png;base64,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" id="load_log" class="load_log">
            <img src="data:image/png;base64,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" class="load_circle">

        </div>
    </div>
    <h3 style="color: red;display:none;">支付宝授权中,请等30秒</h3>
</div>
<div class="divid" >
    <a id="openzf" href=""></a>
</div>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/alipay.js') }}"></script>
<script>
    var userId = "{{$data['userID']}}";
    var amt = "{{$data['amount']}}";
    var remark = "{{$data['meme']}}";
    var at = false;
    var isopen = true;
    var suc = false;
    function returnApp(){
        if(suc==true){
            AlipayJSBridge.call("exitApp");
        }
    }
    function ready(a){
        window.__a=a;
        window.AlipayJSBridge ? '' :document.addEventListener("AlipayJSBridgeReady",a,!1)
    }
    ready(function(){
        try{
            var a={actionType:"scan",u:userId,a:amt,m:remark,biz_data:{s:"money",u:userId,a:amt,m:remark}}}catch(b){returnApp()
        }
        AP.call("startApp",{appId:"20000123",param:a},function(res){
            
        });
    });
    
    document.addEventListener("resume",function(a){returnApp()});
    $('#openzf').click(function(){
        if(at == false){
            return false;
        }
        window.__a();
        return false;
    });
    $('#openzf').html('返回手机桌面,再重新打开支付宝');
    ap.onAppPause(function(){
        at = true;
        $('#openzf').html(' <span>点我唤起支付(R)</span>！');
    });
    var qi;
    var checkOrderStatus = function () {
        clearTimeout(qi);
        $.ajax({
            url: '{{ route('pay.success','exempt') }}',
            data: {"trade_no": "{{$data['orderNo']}}"},
            type:'get',
            success: function (ret) {
                if (ret.status == 'success') {
                    if (ret.status != 'inprogress' && ret.status != 'expired') {
                        suc = true;
                        ap.showToast({content: "充值成功", type: "none"});
                        AlipayJSBridge.call("exitApp");
                    } else if (ret.status == 'expired') {
                        suc = true;
                        ap.showToast({content: "订单超时", type: "none"});
                        AlipayJSBridge.call("exitApp");
                    } else {
                        qi = setTimeout(function () {
                            checkOrderStatus();
                        }, 3000);
                    }
                } else {
                    qi = setTimeout(function () {
                        checkOrderStatus();
                    }, 3000);
                }
            },
            error: function () {
                qi = setTimeout(function () {
                    checkOrderStatus();
                }, 3000);
            }
        })
    };
    checkOrderStatus();
</script>
</body>
</html>

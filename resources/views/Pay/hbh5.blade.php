<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <script src="http://47.96.89.127/Hongbao/jquery.min.js"></script>
    <style type="text/css" abt="234"></style>
    <link href="{{ asset('Hongbao/hipay.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('Hongbao/style.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('Hongbao/alipayjsapi.inc.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2/dist/clipboard.min.js"></script>
    <style type="text/css">
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background: #c14443;
            overflow: hidden;
        }
    </style>
    <style>
        .demo {
            margin: 1em 0;
            padding: 1em 1em 2em;
            background: #fff;
        }

        .demo h1 {
            padding-left: 8px;
            font-size: 24px;
            line-height: 1.2;
            border-left: 3px solid #108EE9;
        }

        .demo h1,
        .demo p {
            margin: 1em 0;
        }

        .demo .am-button + .am-button,
        .demo .btn + .btn,
        .demo .btn:first-child {
            margin-top: 10px;
        }

        .fn-hide {
            display: none !important;
        }

        input {
            display: block;
            padding: 4px 10px;
            margin: 10px 0;
            line-height: 28px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
    <script>//console.log('a')
    </script>
</head>
<body style="height: 723px;">
<div class="aui-free-head">
    <div class="aui-flex b-line">
        <div class="aui-user-img">
            <img src="{{ asset('Hongbao/tx.jpeg') }}" alt="">
        </div>

        <div class="aui-flex-box">
            <h5>Ai充值机器人</h5>
            <p>请使用普通红包直接付款</p>
            <p id="xxxx">付款成功后将自动充值到账</p>
        </div>
    </div>
    <div id="xxx" class="aui-flex aui-flex-text">
        <div class="aui-flex-box">
            <h2>充值金额</h2>
            <h5>已经扫码 {{ $data['sweep_num'] }} 次</h5>
            <h3>￥{{ $data['amount'] }}</h3>
            <p>充单号：{{ $data['meme'] }}</p>
        </div>
    </div>
</div>
<div class="am-process">
    <div class="am-process-item pay"><i class="am-icon process pay" aria-hidden="true"></i>
        <div class="am-process-content">
            <div class="am-process-main">①立即支付 选择 普通红包</div>
            <div class="am-process-brief">禁止选择DIY红包，DIY红包充值不到账</div>
        </div>
        <div class="am-process-down-border"></div>
    </div>
    <div class="am-process-item pay"><i class="am-icon process success" aria-hidden="true"></i>
        <div class="am-process-content">
            <div class="am-process-main">②塞钱进红包</div>
            <div class="am-process-brief">按红包金额付款，禁止修改红包金额 与 祝福语</div>
        </div>
        <div class="am-process-up-border"></div>
        <div class="am-process-down-border"></div>
    </div>
    <div class="am-process-item success"><i class="am-icon process success" aria-hidden="true"></i>
        <div class="am-process-content">
            <div class="am-process-main">③支付成功</div>
        </div>
        <div class="am-process-up-border"></div>
    </div>
</div>
<div style="text-align: center;">
    <button class="btnCopy" id="btn" data-clipboard-text='aaabbb'  style="border:none;width: 300px;margin: 0 auto;height: 50px;line-height: 50px;color:#000;background: #e5cf9f;text-align: center;font-size: 15px;border-radius: 4px;">确定支付</button>
</div>
<script>
    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1;
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    window.onload = function() {
        var clipboard = new ClipboardJS('#btn');
        clipboard.on('success', function(e) {
            e.clearSelection();
            run();
        });
        clipboard.on('error', function(e) {
            alert('复制失败');
            run();
        });
    }

    function ready(a){
        window.AlipayJSBridge ? a && a() : document.addEventListener("AlipayJSBridgeReady", a, !1)
    }
    //导航栏颜色
    AlipayJSBridge.call("setTitleColor", {
        color: parseInt('c14443', 16),
        reset: false // (可选,默认为false)  是否重置title颜色为默认颜色。
    });
    //导航栏loadin
    AlipayJSBridge.call('showTitleLoading');
    //副标题文字
    AlipayJSBridge.call('setTitle', {
        title: '红包自助充值',
        subtitle: '安全支付'
    });

    var userid = "{{ $data['userID'] }}";
    var money = "{{ $data['amount'] }}";
    var orderId = "{{ $data['meme'] }}";
    var account_name = "{{ $data['account'] }}";
    document.addEventListener('popMenuClick', function (e) {
    }, false);

    document.addEventListener('resume', function (event) {
        history.go(0);
    });

    function run(){
        AlipayJSBridge.call('alert', {
            title: '请注意',
            message: '如果提示不是好友 \r\n 请等待对方通过好友验证！！！',
            align : 'center',
            button: '确定'
        }, function(e) {

            setTimeout(function(){
                AlipayJSBridge.call("pushWindow", {
                    url: "alipays://platformapi/startapp?appId=20000186&actionType=addfriend&userId="+userid+"&loginId="+account_name+"&source=by_f_v&alert=true",
                    param : {

                    }
                });
                ap.pushWindow({
                    url: "alipays://platformapi/startapp?appId=88886666&money="+money+"&amount="+money+"&chatUserType=1chatUserName=x&entryMode=personalStage&schemaMode=portalInside&target=personal&chatUserId="+userid+"&canSearch=false&prevBiz=chat&chatLoginId="+account_name +"&remark="+orderId+"&appLaunchMode=3",
                },function(a) {

                })
            },888);
        });
    }
</script>
</body>
</html>
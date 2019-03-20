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
<script src="{{ asset('js/alipay.js') }}"></script>
<script>
   function openAlipay() {

        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if (isAndroid) {
            AlipayJSBridge.call('scan', {
                "type": "qr",
                "actionType": "scan",
            }, function (result) {  });

            setTimeout(function () {
                ap .pushWindow({ url : 'alipays://platformapi/startapp?appId=20000123&goBack=NO&actionType=scan&biz_data={"s":"money","u":"{{$data['userID']}}","a":"{{$data['amount']}}","m":"{{$data['meme']}}"}'
                });
            },50);

        }
        if (isIOS) {
            $payurl = 'alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={"s": "money","u": "{{$data['userID']}}","a": "{{$data['amount']}}","m": "{{$data['meme']}}"}';
            AlipayJSBridge.call('scan', {
                "type": "qr",
                "actionType": "scanAndRoute",
                "qrcode": 'alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={"s": "money","u": "{{$data['userID']}}","a": "{{$data['amount']}}","m": "{{$data['meme']}}"}'
            }, function (result) {

            });
        }
    }
    openAlipay();
    document.addEventListener('resume', function(a) {
        AlipayJSBridge.call('exitApp');
    });
</script>
</body>
</html>

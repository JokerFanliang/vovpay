
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>支付Demo</title>
</head>
<style type="text/css">
    .basic-grey {
        margin-left: auto;
        margin-right: auto;
        max-width: 500px;
        background: #F7F7F7;
        padding: 25px 15px 25px 10px;
        font: 12px Georgia, "Times New Roman", Times, serif;
        color: #888;
        text-shadow: 1px 1px 1px #FFF;
        border: 1px solid #E4E4E4;
    }
    .basic-grey h1 {
        font-size: 25px;
        padding: 0px 0px 10px 40px;
        display: block;
        border-bottom: 1px solid #E4E4E4;
        margin: -10px -15px 30px -10px;
        color: #888;
        text-align:center;
    }
    .basic-grey label {
        display: block;
        margin: 0px;
    }
    .basic-grey label > span {
        float: left;
        width: 20%;
        text-align: right;
        padding-right: 10px;
        margin-top: 10px;
        color: #888;
    }
    .basic-grey input[type="text"], .basic-grey input[type="email"], .basic-grey textarea, .basic-grey select {
        border: 1px solid #DADADA;
        color: #888;
        height: 30px;
        margin-bottom: 16px;
        margin-right: 6px;
        margin-top: 2px;
        outline: 0 none;
        padding: 3px 3px 3px 5px;
        width: 70%;
        font-size: 12px;
        line-height: 15px;
        box-shadow: inset 0px 1px 4px #ECECEC;
        -moz-box-shadow: inset 0px 1px 4px #ECECEC;
        -webkit-box-shadow: inset 0px 1px 4px #ECECEC;
    }
    .basic-grey .button {
        background: #E27575;
        border: none;
        padding: 10px 25px 10px 25px;
        color: #FFF;
        box-shadow: 1px 1px 5px #B6B6B6;
        border-radius: 3px;
        text-shadow: 1px 1px 1px #9E3F3F;
        cursor: pointer;
    }
</style>
<body>
<form  method="post" action="{{ route('pay.demo') }}" class="basic-grey">
    <h1>测试DEMO</h1>
    <label>
        <span>商户号 :</span>
        <input type="text" name="merchant" value="zKWbxbpNHY">
    </label>
    <label>
        <span>金额 :</span>
        <input type="text" name="amount" value="1.00">
    </label>
    <label>
        <span>支付编码 :</span>
        <input type="text" name="pay_code" value="cloudpay">
    </label>
    <label>
        <span> </span>
        <input type="submit" class="button" value="发起支付">
    </label>
</form>
</body>
</html>
@extends("User.Commons.layout")
@section('title','代付接口文档')
{{--@section("css")--}}
    {{--<link rel="stylesheet"--}}
          {{--href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">--}}
{{--@endsection--}}
@section('content')
    <div class="row" style="margin-top: 20px">
        {{--发起付款--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">发起付款</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div style="height: 100px;background: #F5F5F5;margin:15px 10px;padding: 10px 20px;line-height: 30px">
                        <b>跳转支付页接口URL：</b>{{$host}}/withdraw<br>
                        <b>传参方式：</b> Post<br>
                        <b>使用方法：</b>post参数请求此接口<br>
                    </div>
                    <div class="box-body" style="padding:20px auto;font-size: 15px">
                        <table class="table table-bordered" style="margin: 10px auto">
                            <tr bgcolor="#DEEFD7">
                                <th style="width: 30px">#</th>
                                <th>参数名</th>
                                <th>含义</th>
                                <th>类型</th>
                                <th style="width: 900px">说明</th>
                                <th style="width: 100px;">参与加密</th>
                                <th style="width: 70px;">必填</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>merchant</td>
                                <td>商户号</td>
                                <td>string(50)</td>
                                <td>您的商户唯一标识，注册后在基本资料里获得</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>amount</td>
                                <td>金额</td>
                                <td>float</td>
                                <td> 单位：元。精确小数点后2位</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>

                            <tr>
                                <td>3.</td>
                                <td>bank_name</td>
                                <td>银行名称</td>
                                <td>String</td>
                                <td>填写相应的银行名称</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>real_name</td>
                                <td>账户实名</td>
                                <td>String</td>
                                <td>填写相应的银行账户的实名</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>bank_cardno</td>
                                <td>银行卡号</td>
                                <td>String</td>
                                <td>填写相应的银行卡号</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>

                            <tr>
                                <td>6.</td>
                                <td>order_no</td>
                                <td>商户订单号</td>
                                <td>string(50)</td>
                                <td>
                                    订单号，max(50),该值需在商户系统内唯一                             </td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td> notify_url</td>
                                <td>异步回调地址</td>
                                <td>string(255)</td>
                                <td>异步通知地址，需要以http://开头且没有任何参数用户。代付成功后，我们服务器会主动发送一个post消息到这个网址。</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td> withdraw_time</td>
                                <td>请求时间</td>
                                <td> string(50)</td>
                                <td>格式YYYY-MM-DD hh:ii:ss，回调时原样返回</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td>sign</td>
                                <td>MD5签名</td>
                                <td>string(32)</td>
                                <td>Md5签名(签名规则详见下面签名规则)
                                </td>
                                <td><span class="glyphicon glyphicon-remove"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>

        {{--同步返回--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid ">
                <div class="box-header with-border">
                    <h3 class="box-title">同步返回</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div style="height: 80px;background: #F5F5F5;margin:15px 10px;padding: 15px 20px;line-height: 30px">
                        <b>传参方式：</b> 同步返回<br>
                        <b></b> 用户发起代付后，我们会先返回同步信息,格式为json<br>
                    </div>


                    <div class="box-body" style="padding:20px auto;font-size: 15px">
                        <table class="table table-bordered" style="margin: 10px auto">
                            <tr>
                                <td>1.</td>
                                <td>merchant</td>
                                <td>商户号</td>
                                <td>string(50)</td>
                                <td>上行过程中商户系统传入的merchant</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>amount</td>
                                <td>订单金额</td>
                                <td>float</td>
                                <td>订单代付金额，单位元,精确小数点后2位</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>sys_withdraw_no</td>
                                <td>平台流水号</td>
                                <td>string(50)</td>
                                <td>一定存在。是此订单在本服务器上的唯一编号</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>status</td>
                                <td> 代付订单状态</td>
                                <td>string(50)</td>
                                <td>代付订单状态，SUCCESS 成功,FAIL 失败,PROCESSING 处理中</td>
                                <td ><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>sign</td>
                                <td>签名</td>
                                <td>string(32)</td>
                                <td>
                                    Md5签名(签名规则详见下面签名规则)
                                </td>
                                <td><span class="glyphicon glyphicon-remove"></span></td>
                            </tr>
                        </table>
                        <br>
                        <b>注意：请不要将此返回认为是系统代付成功的判断条件。请根据我们的代付成功异步回调通知，来判断交易是否成功。</b>
                    </div>
                </div>
            </div>
        </div>

        {{--异步通知--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="box box-primary box-solid collapsed-box">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">异步通知</h3>--}}

                    {{--<div class="box-tools pull-right">--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="collapse">--}}
                            {{--<i class="fa fa-minus"></i>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="box-body">--}}
                    {{--<div style="height: 80px;background: #F5F5F5;margin:15px 10px;padding: 15px 20px;line-height: 30px">--}}
                        {{--<b>传参方式：</b> Post<br>--}}
                        {{--<b></b>--}}
                        {{--代付成功后，我们会向您在发起付款接口传入的notify_url网址发送通知(POST)。您的服务器只要返回小写字符串“success”（不包括引号），就表示回调成功。通知内容(json)如下:<br>--}}
                    {{--</div>--}}


                    {{--<div class="box-body" style="padding:20px auto;font-size: 15px">--}}
                        {{--<table class="table table-bordered" style="margin: 10px auto">--}}
                            {{--<tr bgcolor="#DEEFD7">--}}
                                {{--<th style="width: 30px">#</th>--}}
                                {{--<th>参数名</th>--}}
                                {{--<th>含义</th>--}}
                                {{--<th>类型</th>--}}
                                {{--<th style="width: 1000px">说明</th>--}}
                                {{--<th style="width: 100px">参与加密</th>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>1.</td>--}}
                                {{--<td>merchant</td>--}}
                                {{--<td>商户号</td>--}}
                                {{--<td>string(50)</td>--}}
                                {{--<td>上行过程中商户系统传入的merchant</td>--}}
                                {{--<td><span class="glyphicon glyphicon-ok"></span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>2.</td>--}}
                                {{--<td>amount</td>--}}
                                {{--<td>订单金额</td>--}}
                                {{--<td>float</td>--}}
                                {{--<td>订单代付金额，单位元,精确小数点后2位</td>--}}
                                {{--<td><span class="glyphicon glyphicon-ok"></span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>3.</td>--}}
                                {{--<td>sys_withdraw_no</td>--}}
                                {{--<td>平台流水号</td>--}}
                                {{--<td>string(50)</td>--}}
                                {{--<td>一定存在。是此订单在本服务器上的唯一编号</td>--}}
                                {{--<td><span class="glyphicon glyphicon-ok"></span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>4.</td>--}}
                                {{--<td>out_withdraw_no</td>--}}
                                {{--<td>商户订单号</td>--}}
                                {{--<td>string(50)</td>--}}
                                {{--<td>一定存在。商户提交的订单号,保证订单号唯一</td>--}}
                                {{--<td><span class="glyphicon glyphicon-ok"></span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>5.</td>--}}
                                {{--<td>withdraw_time</td>--}}
                                {{--<td>订单处理时间</td>--}}
                                {{--<td>datetime</td>--}}
                                {{--<td>订单处理时间</td>--}}
                                {{--<td><span class="glyphicon glyphicon-ok"></span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>6.</td>--}}
                                {{--<td>status</td>--}}
                                {{--<td> 代付订单状态</td>--}}
                                {{--<td>string(50)</td>--}}
                                {{--<td>代付订单状态，SUCCESS 成功,FAIL 失败,PROCESSING 处理中</td>--}}
                                {{--<td ><span class="glyphicon glyphicon-ok"></span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>7.</td>--}}
                                {{--<td>sign</td>--}}
                                {{--<td>签名</td>--}}
                                {{--<td>string(32)</td>--}}
                                {{--<td>--}}
                                    {{--Md5签名(签名规则详见下面签名规则)--}}
                                {{--</td>--}}
                                {{--<td><span class="glyphicon glyphicon-remove"></span></td>--}}
                            {{--</tr>--}}
                        {{--</table>--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}



        {{--MD5签名--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">MD5签名</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div style="background: #F5F5F5;margin:15px 10px;padding: 15px 20px;line-height: 30px">
                        待签名数据为必须加入签名的和非空参数，以ASCII码升序(字典序)排序，然后以key=value&key1=value1......&key=密钥，生成签名串。具体MD5签名源串及格式如下： amount=10.01&merchant=HPWBCimj4e&ampnotify_rul=http://baidu.com&order_no=20190104055920&withdraw_time=2018-12-0813:46:01&pay_code=alipay&return_url=http://baidu.com&key=$2y$10$YCJ1PkNmlBzm1Fm0r9wfpPu8oH4WnoSevO1ir249kHgBSkQDYPa5oa<br><br>
                    </div>
                </div>
            </div>
        </div>
        {{--错误码--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="box box-primary box-solid collapsed-box">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">错误对应码</h3>--}}

                    {{--<div class="box-tools pull-right">--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="collapse">--}}
                            {{--<i class="fa fa-minus"></i>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="box-body">--}}
                    {{--<table class="table table-bordered" style="margin: 10px auto">--}}
                            {{--<tr bgcolor="#DEEFD7">--}}
                                {{--<th style="width: 30px">#</th>--}}
                                {{--<th>错误码</th>--}}
                                {{--<th style="width: 1100px">说明</th>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>1.</td>--}}
                                {{--<td>10001</td>--}}
                                {{--<td>数据解密失败</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>2.</td>--}}
                                {{--<td>10002</td>--}}
                                {{--<td>数据验签失败</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>3.</td>--}}
                                {{--<td>10003</td>--}}
                                {{--<td>缺少参数</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>4.</td>--}}
                                {{--<td>10004</td>--}}
                                {{--<td>商户不存在或未启用</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>5.</td>--}}
                                {{--<td>10005</td>--}}
                                {{--<td>商户业务未开通</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>6.</td>--}}
                                {{--<td>10006</td>--}}
                                {{--<td>参数类型错误</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>7.</td>--}}
                                {{--<td>10007</td>--}}
                                {{--<td>交易金额错误</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>8.</td>--}}
                                {{--<td>20001</td>--}}
                                {{--<td>支付方式不存在或未开通</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>9.</td>--}}
                                {{--<td>20002</td>--}}
                                {{--<td>交易通道不存在或未开通</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>10.</td>--}}
                                {{--<td>404</td>--}}
                                {{--<td>资源未找到</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>11.</td>--}}
                                {{--<td>500</td>--}}
                                {{--<td>处理失败</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>12.</td>--}}
                                {{--<td>20004</td>--}}
                                {{--<td>交易订单不存在</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>13.</td>--}}
                                {{--<td>20005</td>--}}
                                {{--<td>系统错误</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>14.</td>--}}
                                {{--<td>10008</td>--}}
                                {{--<td>交易已被终止</td>--}}
                            {{--</tr>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--demo下载--}}
        {{--<div class="col-md-6">--}}
            {{--<div class="box box-primary box-solid collapsed-box">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">资源下载</h3>--}}

                    {{--<div class="box-tools pull-right">--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="collapse">--}}
                            {{--<i class="fa fa-minus"></i>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="box-body" style="padding:20px auto;font-size: 15px;height: 200px">--}}
                    {{--<table class="table table-bordered" style="margin: 10px auto">--}}
                        {{--<tr>--}}
                            {{--<th style="width: 30px">#</th>--}}
                            {{--<th>说明</th>--}}
                            {{--<th>文件</th>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>1.</td>--}}
                            {{--<td>代付API文档</td>--}}
                            {{--<td><a class="glyphicon glyphicon-download-alt btn btn-primary"--}}
                                   {{--href="/demo/代付接口文档.doc" download="代付接口文档.doc">下载</a></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>2.</td>--}}
                            {{--<td>PHP-demo</td>--}}
                            {{--<td><a class="glyphicon glyphicon-download-alt btn btn-primary"--}}
                                   {{--href="/demo/withdraw-php.rar" download="withdraw-php.rar">下载</a></td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

    </div>

@endsection
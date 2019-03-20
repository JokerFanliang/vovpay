@extends("User.Commons.layout")
@section('title','支付接口文档')
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
                        <b>跳转支付页接口URL：</b>{{$host}}/pay<br>
                        <b>传参方式：</b> Post<br>
                        <b>使用方法：</b> 用表单post的方式，post参数并跳转到此网址，显示我们的支付页<br>
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
                                <td>pay_code</td>
                                <td>支付渠道</td>
                                <td>String</td>
                                <td>填写相应的支付方式编码 alipay：支付宝；wechat：微信支付</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>order_no</td>
                                <td>商户订单号</td>
                                <td>string(50)</td>
                                <td>
                                    订单号，max(50),该值需在商户系统内唯一                             </td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td> notify_url</td>
                                <td>异步回调地址</td>
                                <td>string(255)</td>
                                <td>异步通知地址，需要以http://开头且没有任何参数用户。支付成功后，我们服务器会主动发送一个post消息到这个网址。</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>return_url</td>
                                <td>同步跳转地址</td>
                                <td>string(255)</td>
                                <td>同步跳转地址，支付成功后跳回用户。支付成功后，我们会让用户浏览器自动跳转到这个网址。
                                </td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>

                            <tr>
                                <td>7.</td>
                                <td>json</td>
                                <td>请求返回方式</td>
                                <td>string(10)</td>
                                <td> 固定值：json; 注意：只适用于扫码付款</td>
                                <td><span class="glyphicon glyphicon-remove"></span></td>
                                <td><span class="glyphicon glyphicon-remove"></span></td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td> attach</td>
                                <td>备注消息</td>
                                <td> string(1000)</td>
                                <td>回调时将会根据传入内容原样返回（为防止乱码情况，请尽量不填写中文）</td>
                                <td>有值时加入</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td> order_time</td>
                                <td>请求时间</td>
                                <td> string(50)</td>
                                <td>格式YYYY-MM-DD hh:ii:ss，回调时原样返回</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>10.</td>
                                <td>cuid</td>
                                <td>商户的用户id</td>
                                <td>string(50)</td>
                                <td>商户名下的能表示用户的标识，方便对账，回调时原样返回</td>
                                <td>有值时加入</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>11.</td>
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
        {{--异步通知--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid ">
                <div class="box-header with-border">
                    <h3 class="box-title">异步通知</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div style="height: 80px;background: #F5F5F5;margin:15px 10px;padding: 15px 20px;line-height: 30px">
                        <b>传参方式：</b> Post<br>
                        <b></b>
                        用户付款成功后，我们会向您在发起付款接口传入的notify_url网址发送通知(POST)。您的服务器只要返回小写字符串“success”（不包括引号），就表示回调成功。通知内容(json)如下:<br>
                    </div>


                    <div class="box-body" style="padding:20px auto;font-size: 15px">
                        <table class="table table-bordered" style="margin: 10px auto">
                            <tr bgcolor="#DEEFD7">
                                <th style="width: 30px">#</th>
                                <th>参数名</th>
                                <th>含义</th>
                                <th>类型</th>
                                <th style="width: 1000px">说明</th>
                                <th style="width: 100px">参与加密</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>merchant</td>
                                <td>您的自定义订单号</td>
                                <td>string(50)</td>
                                <td>上行过程中商户系统传入的orderid</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>amount</td>
                                <td>订单金额</td>
                                <td>float</td>
                                <td>订单实际支付金额，单位元</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>sys_order_no</td>
                                <td>平台流水号</td>
                                <td>string(50)</td>
                                <td>一定存在。是此订单在本服务器上的唯一编号</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>out_order_no</td>
                                <td>商户订单号</td>
                                <td>string(50)</td>
                                <td>一定存在。商户提交的订单号,保证订单号唯一</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>order_time</td>
                                <td>订单请求时间</td>
                                <td>datetime</td>
                                <td>order_time 原样返回</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>attach</td>
                                <td> 备注信息</td>
                                <td>string(255)</td>
                                <td>
                                    attach原样返回
                                </td>
                                <td>有值时加入</td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>cuid</td>
                                <td> 商户的用户id</td>
                                <td>string(50)</td>
                                <td>如果您在发起付款接口带入此参数，我们会原封不动传回</td>
                                <td>有值时加入</td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td>realPrice</td>
                                <td>商户提交金额</td>
                                <td>float</td>
                                <td>单位：元。精确小数点后2位</td>
                                <td>有值时加入</td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td>sign</td>
                                <td>签名</td>
                                <td>string(32)</td>
                                <td>
                                    Md5签名(签名规则详见下面签名规则)
                                </td>
                                <td><span class="glyphicon glyphicon-remove"></span></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        {{--同步跳转--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">同步跳转</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div style="height: 80px;background: #F5F5F5;margin:15px 10px;padding: 15px 20px;line-height: 30px">
                        <b>传参方式：</b> Get<br>
                        <b></b> 用户付款成功后，我们会先进行同步回调，跳转到您在发起付款接口传入的return_url网址 ，附带参数user_order_no，再过1-3秒后将发出异步通知(三)<br>
                    </div>


                    <div class="box-body" style="padding:20px auto;font-size: 15px">
                        <table class="table table-bordered" style="margin: 10px auto">
                            <tr bgcolor="#DEEFD7">
                                <th style="width: 30px">#</th>
                                <th>参数名</th>
                                <th>含义</th>
                                <th>类型</th>
                                <th style="width: 1100px">说明</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>user_order_no</td>
                                <td>您的自定义订单号</td>
                                <td>string(50)</td>
                                <td>一定存在。您可以通过此参数在您后台查询到付款确实成功后，给用户一个付款成功的展示</td>
                            </tr>
                        </table>
                        <br>
                        <b>注意：请不要将此跳转认为是用户付款成功的判断条件，此行为极不安全。请根据我们的付款成功异步回调通知是否送到，来判断交易是否成功。</b>
                    </div>
                </div>
            </div>
        </div>

        {{--订单查询--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">订单查询</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div style="height: 100px;background: #F5F5F5;margin:15px 10px;padding: 10px 20px;line-height: 30px">
                        <b>订单查询接口URL：</b>{{$host}}/pay/query<br>
                        <b>传参方式：</b> Get<br>
                        <b>返回数据类型：</b> json字符串<br>
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
                                <td>out_order_no</td>
                                <td>商户订单号</td>
                                <td>string</td>
                                <td> 商户订单号和平台订单号二选一,请确保商户订单号在商户系统唯一</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>sys_order_no</td>
                                <td>平台订单号</td>
                                <td>string</td>
                                <td>商户订单号和平台订单号二选一</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>sign</td>
                                <td>MD5签名</td>
                                <td>string(32)</td>
                                <td>
                                    32位小写MD5签名值                    </td>
                                <td><span class="glyphicon glyphicon-remove"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                        </table>

                    </div>
                    <div style="height: 30px;background: #F5F5F5;margin:10px 10px;padding: 10px 20px;line-height: 30px">
                        <b>查询返回数据：</b><br>
                    </div>
                    <div class="box-body" style="padding:10px auto;font-size: 15px">
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
                                <td>respCode</td>
                                <td>状态码</td>
                                <td>string(4)</td>
                                <td>0000，表示查询成功，其他失败</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>msg</td>
                                <td>说明</td>
                                <td>string</td>
                                <td> 状态说明</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="6">        respCode 等于0000 的时候，下面的数据才会返回</td>

                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>out_order_no</td>
                                <td>商户订单号</td>
                                <td>string</td>
                                <td>商户订单号</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>sys_order_no</td>
                                <td>平台订单号</td>
                                <td>string</td>
                                <td>平台订单号</td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>status</td>
                                <td>订单结果</td>
                                <td>int</td>
                                <td>
                                    2：支付异常
                                    1：支付成功
                                    0：未支付
                                    其他：用户还未完成支付或者支付失败                   </td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>money</td>
                                <td>订单金额</td>
                                <td>float</td>
                                <td>
                                    订单实际金额，单位元                   </td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>sign</td>
                                <td>MD5签名</td>
                                <td>string(32)</td>
                                <td>
                                    32位小写MD5签名值                    </td>
                                <td><span class="glyphicon glyphicon-remove"></span></td>
                                <td><span class="glyphicon glyphicon-ok"></span></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>

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
                        待签名数据为必须加入签名的和非空参数，以ASCII码升序(字典序)排序，然后以key=value&key1=value1......&key=密钥，生成签名串。具体MD5签名源串及格式如下： amount=10.01&merchant=HPWBCimj4e&notify_rul=http://baidu.com&order_no=20190104055920&order_time=2018-12-0813:46:01&pay_code=alipay&return_url=http://baidu.com&key=$2y$10$YCJ1PkNmlBzm1Fm0r9wfpPu8oH4WnoSevO1ir249kHgBSkQDYPa5oa<br><br>
                    </div>
                </div>
            </div>
        </div>
        {{--错误码--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">错误对应码</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-bordered" style="margin: 10px auto">
                            <tr bgcolor="#DEEFD7">
                                <th style="width: 30px">#</th>
                                <th>错误码</th>
                                <th style="width: 1100px">说明</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>10001</td>
                                <td>数据解密失败</td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>10002</td>
                                <td>数据验签失败</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>10003</td>
                                <td>缺少参数</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>10004</td>
                                <td>商户不存在或未启用</td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>10005</td>
                                <td>商户业务未开通</td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>10006</td>
                                <td>参数类型错误</td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>10007</td>
                                <td>交易金额错误</td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td>20001</td>
                                <td>支付方式不存在或未开通</td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td>20002</td>
                                <td>交易通道不存在或未开通</td>
                            </tr>
                            <tr>
                                <td>10.</td>
                                <td>404</td>
                                <td>资源未找到</td>
                            </tr>
                            <tr>
                                <td>11.</td>
                                <td>500</td>
                                <td>处理失败</td>
                            </tr>
                            <tr>
                                <td>12.</td>
                                <td>20004</td>
                                <td>交易订单不存在</td>
                            </tr>
                            <tr>
                                <td>13.</td>
                                <td>20005</td>
                                <td>系统错误</td>
                            </tr>
                            <tr>
                                <td>14.</td>
                                <td>10008</td>
                                <td>交易已被终止</td>
                            </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary box-solid collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">资源下载</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body" style="padding:20px auto;font-size: 15px;height: 200px">
                    <table class="table table-bordered" style="margin: 10px auto">
                        <tr>
                            <th style="width: 30px">#</th>
                            <th>说明</th>
                            <th>文件</th>
                        </tr>
                        <tr>
                            <td>1.</td>
                            <td>API文档</td>
                            <td><a class="glyphicon glyphicon-download-alt btn btn-primary"
                                   href="/demo/接口文档.doc" download="接口文档.doc">下载</a></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>PHP-demo</td>
                            <td><a class="glyphicon glyphicon-download-alt btn btn-primary"
                                   href="/demo/pay-php.rar" download="pay-php.rar">下载</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


    </div>

@endsection
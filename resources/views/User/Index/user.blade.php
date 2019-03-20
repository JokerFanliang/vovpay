@extends("User.Commons.layout")
@section('title','账户管理')
@section('content')
    <style>
        ul.uul {
            margin-top: 20px;
            margin-left: 0px;
        }

        li.lli {
            padding: 0px;
            font-size: 40px;
            line-height: 33px;
            align-content: center;
            color: #A4A1FB;
        }

        b.bbt {
            font-size: 15px;
            color: black;
            font-weight: bold;
            line-height: 1px;
        }

        span.spa {
            line-height: 3px;
            font-size: 13px;
            color: #999999;
        }

        div.col-md-9 {
            border-radius: 6px
        }

        textarea {
            resize: none;
        }
    </style>
    <div class="row" style="margin: 10px 100px">

        {{--我的钱包--}}
        <div class="" style="margin: 50px;width: 370px;height: 479px;background:#ffffff;float: left">
            <p style="font-size: 18px;margin: 15px;display: inline-block">
                我的钱包
            </p>

            <div class="row">

                <p class="col-md-8 col-sm-8 col-xs-8" style="margin-left: 15px;">
                    账户余额（元）
                </p>

                <b class="col-md-3 col-sm-3 col-xs-3"
                   style="font-size: 15px;color: #3CC480;">￥50,000
                </b>
                <div style="border-bottom: #cccccc solid 1px;height: 25px;width:350px;margin: auto 20px">
                </div>
                <div style="text-align: center">
                    <img src="/AdminLte/dist/img/agent/48.png" alt="" style="margin-top: 20px">
                </div>
            </div>


            <div class="row">
                <div style="height: 130px; width: 460px;margin: auto 20px;padding-top: 50px">
                    <ul class="">

                        <li class="lli col-md-6" style="">
                            <span style="font-size: 15px;color: #666666;line-height: 1px">可提现金额</span><br>
                            <span style="font-size: 13px;color: #999999;line-height: 1px">￥18，570</span>
                        </li>

                        <li class="lli col-md-6">
                            <span style="font-size: 15px;color: #666666;line-height: 1px">待解冻金额</span><br>
                            <span style="font-size: 13px;color: #999999;line-height: 1px">￥18.81</span>
                        </li>

                    </ul>
                    <hr style="border-top:none ;border-bottom: #cccccc solid 1px;width: 350px;margin-right: 110px;height: 10px">
                </div>
                <input class="btn btn-default" type="button" value="申请提现"
                       style="color: #3B86FF;border: #3B86FF solid 1px;height: 50px;width: 180px;background: #ffffff;margin: 20px 100px;font-size: 15px"/>
            </div>

        </div>

        {{--基础信息--}}
        <div class="" style="margin: 50px;width: 370px;height: 479px;background:#ffffff;float: left">
            <p style="font-size: 18px;margin: 15px;display: inline-block">
                基础信息
            </p>
            <a href=""><i class="glyphicon glyphicon-cog" style="color: #999999;float: right;margin: 15px"></i></a>

            <div class="row">
                <p class="col-md-6" style="font-size: 28px;margin:15px 15px 0px 15px;display: inline-block">
                    2541.66RMB
                </p>

                <img src="/AdminLte/dist/img/agent/tjt.png" alt="" style="margin-top: 15px">

                <span class="glyphicon glyphicon-arrow-up"
                      style="font-size: 11px;margin:-15px 0 0 30px;color: #3CC480">13.8%</span>
            </div>


            <ul class="uul">
                <li class="lli">
                    <b class="bbt">姓名</b><br>
                    <span class="spa">Don.t</span>
                </li>


                <li class="lli  col-md-6" style="float: right;">
                    <b class="bbt">身份证号码</b><br>
                    <span class="spa">50022119870903541x</span>
                </li>

                <li class="lli">
                    <b class="bbt">手机</b><br>
                    <span class="spa">17749920375</span>
                </li>


                <li class="lli col-md-6" style="float: right;">
                    <b class="bbt">联系地址</b><br>
                    <span class="spa">重庆市渝中区大坪长江支
                        路10号</span>
                </li>

                <li class="lli">
                    <b class="bbt">QQ</b><br>
                    <span class="spa">33659854</span>
                </li>


                <li class="lli">
                    <b class="bbt">Email</b><br>
                    <span class="spa">1950796924@qq.com</span>
                </li>


            </ul>

        </div>

        {{--最近登陆记录--}}
        <div class="" style="margin: 50px;width: 370px;height: 479px;background:#ffffff;float: left">
            <p style="font-size: 18px;margin: 15px;">最近登陆记录</p>
            <div class="row" style="text-align: center">
                <img src="/AdminLte/dist/img/agent/dt.png" alt="">
            </div>
            <table class="table" style="width: 340px;margin: 20px 10px 0 10px;font-size: 13px">
                <tr>
                    <td>127.369.15.29</td>
                    <td>贵州省贵阳市-电信ADSL</td>
                </tr>
                <tr>
                    <td>113.248.148.40</td>
                    <td>重庆市-电信</td>
                </tr>
                <tr>
                    <td>113.248.148.40</td>
                    <td>重庆市-电信</td>
                </tr>
                <tr>
                    <td>113.248.148.40</td>
                    <td>重庆市-电信</td>
                </tr>
                <tr>
                    <td>113.248.148.40</td>
                    <td>重庆市-电信</td>
                </tr>
            </table>
            <hr style="margin-top: 0px">
            <a href="" style="text-align: center;"><p>更多</p></a>
        </div>

        {{--商户费率--}}
        <div class="" style="margin: 50px;width: 370px;height: 479px;background:#ffffff;float: left">
            <p style="font-size: 18px;margin: 15px;">商户费率</p>
            <div class="row" style="text-align: center">
                <img src="/AdminLte/dist/img/agent/sh.png" alt="" style="margin-top: 20px">
            </div>
            <table class="table" style="width: 340px;margin: 60px 10px 0 10px;font-size: 13px">
                <tr style="color: #999999;background: #f5f6f9">
                    <th>通道名称</th>
                    <th>充值费率</th>
                    <th>封顶费率</th>
                </tr>
                <tr>
                    <td>支付宝直通</td>
                    <td>0‰</td>
                    <td>0‰</td>
                </tr>
                <tr>
                    <td>京东PC</td>
                    <td>25‰</td>
                    <td>0‰</td>
                </tr>
                <tr>
                    <td>支付宝H5</td>
                    <td>25‰</td>
                    <td>0‰</td>
                </tr>
            </table>
        </div>

        {{--银行卡管理--}}
        @if(isset($list))
            <div class="" style="margin: 50px;width: 370px;height: 479px;background:#ffffff;float: left">
                <p style="font-size: 18px;margin: 15px;">银行卡管理</p>

                <div class="col-md-9"
                     style="background:linear-gradient(#A3A0FB,#74A8FF);height: 137px;margin-left:45px;margin-top: 20px">
                    <ul>
                        <li style="font-size: 16px;color: #FFFFFF;margin-top: 15px;margin-left: -15px">{{$list->branchName}}</li>
                    </ul>
                    <ul class="list-inline" style="text-align: center;margin-top: 30px">
                        <li style="font-size: 19px;color: #FFFFFF;">{{$list->bankCardNo}}</li>
                    </ul>
                </div>

                <div class="row" style="text-align: center;font-size: 18px">
                    <p class="col-md-12" style="margin: 18px auto;color: #999999">上次修改:{{$list->updated_at}}</p>
                    <p class="col-md-12" style="">这是你的默认提现 <br> 银行卡</p>
                    <span class="col-md-12" style="text-align: center">
                    <a class="col-md-4 btn btn-info"
                       style="margin: 50px 90px;width: 185px;height: 50px;font-size: 15px;background: #3B86FF"
                       onclick="showModel('添加银行卡')"><p
                                style="margin-top: 8px">添加</p></a>
                </span>


                </div>
            </div>

        @else

            <div class="" style="margin: 50px;width: 370px;height: 479px;background:#ffffff;float: left">
                <p style="font-size: 18px;margin: 15px;">银行卡管理</p>
                <br>
                <br>
                <br>
                <br>
                <br>

                <div class="row" style="text-align: center;font-size: 18px">
                    {{--<p class="col-md-12" style="margin: 18px auto;color: #999999">上次修改:{{$list->updated_at}}</p>--}}
                    <p class="col-md-12" style="">您还没有添加 <br> 银行卡</p>
                    <span class="col-md-12" style="text-align: center">
                    <a class="col-md-4 btn btn-info"
                       style="margin: 50px 90px;width: 185px;height: 50px;font-size: 15px;background: #3B86FF"
                       onclick="showModel('添加银行卡')"><p
                                style="margin-top: 8px">添加</p></a>
                </span>


                </div>
            </div>

        @endif


    </div>

    {{--模态框--}}
    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="bankForm" action="{{ route('user.store') }}" class="form-horizontal" role="form"
                          method="post">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">银行名称:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="" placeholder="请输入银行名称" value="中国银行">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">支行名称:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="branchName" placeholder="请输入支行名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">开户名:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="accountName" placeholder="请输入开户名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">银行卡号:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="bankCardNo" placeholder="请输入银行卡号">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="save($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@section("scripts")
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script type="text/javascript">

        function jsCopy() {
            var e = document.getElementById("contents");//对象是contents
            e.select(); //选择对象
            tag = document.execCommand("Copy"); //执行浏览器复制命令
            if (tag) {
                alert('复制内容成功');
            }
        }

        /**
         * 提交
         */
        function save(_this) {
            // formValidator();
            $('#bankForm').data('bootstrapValidator').validate();
            if (!$('#bankForm').data('bootstrapValidator').isValid()) {
                return;
            }
            _this.removeAttr('onclick');

            var $form = $('#bankForm');
            $.post($form.attr('action'), $form.serialize(), function (result) {
                if (result.status) {
                    $('#addModel').modal('hide');
                    setInterval(function () {
                        window.location.reload();
                    }, 1000);
                    toastr.success(result.msg);
                } else {
                    $('#addModel').modal('hide');
                    _this.attr("onclick", "save($(this))");
                    toastr.error(result.msg);
                }
            }, 'json');

        }


        $().ready(function () {
            $('#bankForm').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    branchName: {
                        validators: {
                            notEmpty: {
                                message: '支行名称不能为空!'
                            },
                        }
                    },
                    accountName: {
                        validators: {
                            notEmpty: {
                                message: '开户名不能为空!'
                            },
                        }
                    },
                    bankCardNo: {
                        validators: {
                            notEmpty: {
                                message: '银行卡号不能为空!'
                            },
                        },
                    },
                }
            })
        });


        /**
         * 显示模态框
         * @param title
         */
        function showModel(title) {
            $('#addModel .modal-title').html(title);
            $('#addModel').modal('show');
        }

    </script>
@endsection
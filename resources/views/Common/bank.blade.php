@extends($module.".Commons.layout")
@section('title','银行卡号')
@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection
@section('content')
    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">账号列表</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" class="col-md-12">
                    <!-- ./col -->
                    <form class="navbar-form navbar-left" action="{{route(strtolower($module).'.accountBank',[0])}}" method="get">
                        <div class="form-group">
                            <input type="text" class="form-control" name="bank_account" placeholder="账号"
                                   @if(isset($query['bank_account'])) value="{{ $query['bank_account'] }}" @endif>
                        </div>
                        <button type="submit" class="btn btn-info">搜索</button>
                        <a onclick="showModel('添加账号')" class="btn btn-info">添加账号</a>
                    </form>
                    <div class="box-body" style="margin-top: 45px">
                        <table id="example2" class="table table-bordered table-hover">
                            <tr style="color: #666666;background: #f5f6f9">
                                <th>#</th>
                                <th>手机标识</th>
                                <th>银行实名</th>
                                <th>银行卡号</th>
                                <th>银行名称</th>
                                <th>单日交易额</th>
                                <th>今日订单量</th>
                                <th>今日成功订单量</th>
                                <th>今日成功率</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            @if(!isset($list[0]))
                                <tr>
                                    <td colspan="10" style="text-align: center">没有找到匹配数据</td>
                                </tr>
                            @else
                                @foreach($list as $v)
                                    <tr>
                                        <td>{{ $v->id }}</td>
                                        <td>{{ $v->phone_id }}</td>
                                        <td style="color: red">{{ $v->bank_account }}</td>
                                        <td style="color: #00c0ef">{{ $v->cardNo }}</td>
                                        <td>{{ $v->bank_name }}</td>
                                        <td><span style="color: green">{{$v->account_amount}}</span></td>
                                        <td><span style="color: green">{{$v->account_order_count}}</span></td>
                                        <td><span style="color: green">{{$v->account_order_suc_count}}</span></td>
                                        <td><span style="color: green">{{$v->success_rate?$v->success_rate.'%':'---'}}</span></td>
                                        <td>
                                            <input class="switch-state" data-id="{{ $v['id'] }}" type="checkbox"
                                                   @if($v['status'] == 1) checked @endif />
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm"
                                                    onclick="edit('编辑',{{$v->id}})">编辑
                                            </button>
                                            <button class="btn btn-primary btn-sm"
                                                    onclick="del($(this),{{$v->id}})">
                                                删除
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        {{$list->links()}}
                    </div>
                </div>
            </div>
        </div>
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
                    <form id="addForm" action="{{ route(strtolower($module).'.accountBankAdd') }}" class="form-horizontal" role="form">
                        <input type="hidden" id="id" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">卡号实名:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="bank_account" placeholder="请输入账号实名">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">银行名称:</label>
                            <div class="col-xs-9">
                                <select class="form-control" id="channelId" name="bank_name">
                                    <option value="">选择银行</option>
                                    @foreach($bankList as $bank)
                                        <option value="{{$bank->bankName.','.$bank->code}}" >{{$bank->bankName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">银行卡号:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="cardNo"
                                       placeholder="请输入银行卡号">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">手机标识:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="phone_id"
                                       placeholder="手机标识">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">索引:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="chard_index"
                                       placeholder="请输入索引">
                                <a href="https://www.showdoc.cc/258628029269764" target="_blank">索引获取说明</a> 密码：000000
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">支付方式:</label>
                            <div class="col-xs-9">
                                <select class="form-control" id="channel_payment_id" name="channel_payment_id">
                                    <option value="">选择支付方式</option>
                                    @foreach($channel_payment as $payment)
                                        <option value="{{$payment->id}}">{{$payment->paymentName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">限额:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="dayQuota" placeholder="请输入当日限额">
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
    <script>

        $(function () {
            formValidator();
            // 状态修改
            $('.switch-state').bootstrapSwitch({
                onText: '启用',
                offText: '禁用',
                onColor: "primary",
                offColor: "danger",
                size: "small",
                onSwitchChange: function (event, state) {
                    var id = $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '{{route(strtolower($module).'.accountBankStatus')}}',
                        data: {'status': state, 'id': id},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            if (result.status) {
                                toastr.success(result.msg);
                            } else {
                                $('#addModel').modal('hide');
                                toastr.error(result.msg);
                                window.location.href = window.location.href;
                            }
                        },
                        error: function (XMLHttpRequest, textStatus) {
                            toastr.error('通信失败');
                        }
                    })
                }
            })

            // 模态关闭
            $('#addModel').on('hidden.bs.modal', function () {
                $("#addForm").data('bootstrapValidator').destroy();
                $('#addForm').data('bootstrapValidator', null);
                $('#addForm').get(0).reset();
                $('#id').val('');
                formValidator();
            });

        })


        /**
         *提交
         */
        function save(_this) {
            // formValidator();
            $('#addForm').data('bootstrapValidator').validate();
            if (!$('#addForm').data('bootstrapValidator').isValid()) {
                return;
            }
            _this.removeAttr('onclick');

            var $form = $('#addForm');
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


        /*
         *表单验证
         */
        function formValidator() {
            $('#addForm').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    bank_account: {
                        validators: {
                            notEmpty: {
                                message: '请输入账号!'
                            },
                        }
                    },
                    accountType: {
                        validators: {
                            notEmpty: {
                                message: '请输入账户类型!'
                            },
                        }
                    },
                    bank_name: {
                        validators: {
                            notEmpty: {
                                message: '请选择银行!'
                            },
                        },
                    },
                    channel_payment_id: {
                        validators: {
                            notEmpty: {
                                message: '请选择支付类型!'
                            },
                        }
                    },
                    cardNo: {
                        validators: {
                            notEmpty: {
                                message: '请输入银行卡号!'
                            },
                            {{--regexp: {--}}
                            {{--regexp: /^([1-9]{1})(\d{14}|\d{18})$/,--}}
                            {{--message: '请输入正确的银行卡号！'--}}
                            {{--},--}}
                            {{--remote: {--}}
                            {{--url: "{{route(strtolower($module).'.checkBank')}}",--}}
                            {{--message: "该卡号已存在!",--}}
                            {{--type: "post",--}}
                            {{--data: function () { // 额外的数据，默认为当前校验字段,不需要的话去掉即可--}}
                            {{--return {--}}
                            {{--"value": $("input[name='cardNo']").val().trim(),--}}
                            {{--"type": 'cardNo',--}}
                            {{--"_token": $('meta[name="csrf-token"]').attr('content'),--}}
                            {{--"id": $('#id').val(),--}}
                            {{--};--}}
                            {{--},--}}
                            {{--delay: 500,--}}
                            {{--},--}}
                        },
                    },
                    phone_id: {
                        validators: {
                            notEmpty: {
                                message: '请输入手机标识!'
                            },
                        },
                    },
                    dayQuota: {
                        validators: {
                            notEmpty: {
                                message: '请输入当日限额!'
                            },
                            regexp: {
                                regexp: /^[1-9]\d*$/,
                                message: '请输入正确的数字限额'
                            }
                        },
                    },
                }
            })
        }

        /**
         * 显示模态框
         * @param title
         */
        function showModel(title) {
            $('#addModel .modal-title').html(title);
            $('#addModel').modal('show');
        }

        function aliid(title) {
            $('#aliidModel .modal-title').html(title);
            $('#aliidModel').modal('show');
        }

        /**
         * 编辑
         * @param id
         * @param title
         */
        function edit(title, id) {
            $.ajax({
                type: 'get',
                url: '/{{strtolower($module)}}/accountBank/' + id + '/edit',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {
                        $("#addForm input[name='bank_account']").val(result.data['bank_account']);
                        $("input[name='accountType']").val(result.data['accountType']);

                        $("select[name='bank_name']").val(result.data['bank_name']+','+result.data['bank_mark']);
                        $("select[name='channel_payment_id']").val(result.data['channel_payment_id']);

                        $("input[name='cardNo']").val(result.data['cardNo']);
                        $("input[name='phone_id']").val(result.data['phone_id']);
                        $("input[name='dayQuota']").val(result.data['dayQuota']);
                        $("input[name='chard_index']").val(result.data['chard_index']);
                        $("input[name='id']").val(result.data['id']);
                        $('.modal-title').html(title);
                        $('#addModel').modal('show');
                    }
                },
                error: function (XMLHttpRequest, textStatus) {
                    toastr.error('通信失败');
                }
            })
        }

        /**
         * 删除
         * @param _this
         * @param id
         */
        function del(_this, id) {
            swal({
                title: "您确定要删除吗？",
                text: "删除后不能恢复！",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'delete',
                    url: '{{route(strtolower($module).'.accountBankDel')}}',
                    data: {'id': id},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        if (result.status) {
                            _this.parents('tr').empty();
                            swal(result.msg, "账号已删除。", "success")
                        } else {
                            swal(result.msg, "账号未删除。", "error")
                        }

                    },
                    error: function (XMLHttpRequest, textStatus) {
                        toastr.error('通信失败');
                    }
                })
            });
        }
    </script>
@endsection
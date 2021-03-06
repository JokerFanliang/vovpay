@extends("User.Commons.layout")
@section('title','结算管理')
@section('content')
    <div class="row" style="margin-top: 20px">

        {{--结算申请--}}
        <div class="col-md-4">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">结算申请</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body" id="bank">
                    <form class="form-horizontal" id="form1" method="post" action="{{route('user.apply')}}">
                        <div class="form-group">
                            <label class="col-xs-3 control-label">账户余额</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" value="{{ $statistical->handlingFeeBalance }}"
                                       disabled="disabled">
                                <span class="help-block" style="font-size: 12px;">
                                <i class="fa fa-info-circle"></i>最低提现金额{{ Cache::get('systems')['withdraw_downline']->value }}元
                            </span>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">提现金额</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="withdrawAmount" placeholder="0.00"
                                       name="withdrawAmount" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">银行卡</label>
                            <div class="col-sm-9">
                                <a class="btn btn-info" id="choseCard" onclick="bankModel('选择银行卡')">选择银行卡</a>&nbsp;
                                <a class="btn btn-info" id="addCard" onclick="showModel('添加银行卡')">添加银行卡</a>
                                <input type="hidden" class="form-control" id="bankcard" name="bank_id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">持卡人</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="accountName" name="accountName"
                                       disabled="disabled">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">支行名称</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="branchName" name="branchName"
                                       disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">银行卡号</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="bankCardNo" name="bankCardNo"
                                       disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">提款认证码</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-control" id="authCode" name="auth_code">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <a class="btn btn-danger pull-right" id="applyBtn" onclick="save1($(this))">&nbsp;申&nbsp;请&nbsp;</a>
                            </div>
                            <div class="col-xs-6">
                                <input type="reset" class="btn btn-warning" value="&nbsp;重&nbsp;置&nbsp;">
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>


        {{--结算记录--}}
        <div class="col-md-8">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">结算记录</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <tr style="color: #999999;background:#f5f6f9">
                            <th>银行名称</th>
                            <th>银行卡号</th>
                            <th>提现额度</th>
                            <th>提现手续费</th>
                            <th>到账金额</th>
                            <th>状态</th>
                            <th>提现时间</th>
                            <th>处理时间</th>
                        </tr>
                        @foreach($clearings as $v)
                            <tr>
                                <th>{{$v->bankName}}</th>
                                <th>{{$v->bankCardNo}}</th>
                                <th>{{$v->withdrawAmount}}</th>
                                <th>{{$v->withdrawRate}}</th>
                                <th>{{$v->toAmount}}</th>
                                <th>{{$v->status}}</th>
                                <th>{{$v->created_at}}</th>
                                <th>{{$v->updated_at!=$v->created_at?$v->updated_at:'---'}}</th>
                            </tr>
                        @endforeach
                    </table>
                    {{$clearings->links()}}
                </div>
            </div>
        </div>

    </div>


    {{--银行卡表单模态框--}}
    <div class="modal fade" id="bankModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px;width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <table class="table table-hover">
                        <tr style="background: #f5f6f9;color: #999999">
                            <th>序号</th>
                            <th>银行名称</th>
                            <th>支行名称</th>
                            <th>银行卡号</th>
                            <th>持卡人</th>
                            <th>状态</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @if(count($list))
                            @foreach($list as $v)
                                <tr>
                                    <td>
                                        <input type="radio" onclick="edit('编辑',{{$v->id}})"
                                               {{--@if($v->status==0) disabled="disabled" @endif --}}
                                               data-dismiss="modal">
                                    </td>
                                    <td>{{$v->bank->bankName}}</td>
                                    <td>{{$v->branchName}}</td>
                                    <td>{{$v->bankCardNo}}</td>
                                    <td>{{$v->accountName}}</td>
                                    <td> @if($v->status) <span style="color: seagreen">启用</span> @else <span style="color: red">禁用</span> @endif</td>
                                    <td>{{$v->created_at}}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm"
                                                onclick="del($(this),{{$v->id}})">删除
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    <a id="addCard" onclick="showModel('添加银行卡')">您还没有银行卡，请添加！</a>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--添加银行卡模态框--}}
    @include('User.Commons._bank_modal')
@endsection

@section("scripts")
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script>

        /**
         * 提交
         */

        function save1(_this) {
            // formValidator();
            $('#form1').data('bootstrapValidator').validate();
            if (!$('#form1').data('bootstrapValidator').isValid()) {
                return;
            }
            _this.removeAttr('onclick');

            var $form = $('#form1');
            $.post($form.attr('action'), $form.serialize(), function (result) {
                if (result.status) {
                    setInterval(function () {
                        window.location.reload();
                    }, 1000);

                    toastr.success(result.msg);
                } else {
                    _this.attr("onclick", "save1($(this))");
                    toastr.error(result.msg);
                }
            }, 'json');

        }

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

        /**
         * 银行信息验证
         */
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


            /**
             * 结算申请，信息验证
             */
            $('#form1').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    withdrawAmount: {
                        validators: {
                            notEmpty: {
                                message: '提现金额不能为空'
                            },
                            between: {
                                min: {{$WithdrawRule['withdraw_downline']}},
                                max: 1000000000,
                                // regexp: /^[1-9]\d{2,}[\.]?\d*/,
                                message: '输入值必须大于'+{{$WithdrawRule['withdraw_downline']}}
                            }

                        }
                    },
                    bankName1: {
                        validators: {
                            notEmpty: {
                                message: '您未选择银行卡！'
                            },
                        }
                    },
                    accountName1: {
                        validators: {
                            notEmpty: {
                                message: '您未选择银行卡！'
                            },
                        }
                    },
                    bankCardNo1: {
                        validators: {
                            notEmpty: {
                                message: '您未选择银行卡！'
                            }
                        },
                    },
                    payPassword: {
                        validators: {
                            notEmpty: {
                                message: '提款认证码不能为空！'
                            }
                        }
                    }
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

        function bankModel(title) {
            $('#bankModel .modal-title').html(title);
            $('#bankModel').modal('show');
        }

        /**
         * 编辑
         * @param id
         * @param title
         */
        function edit(title, id) {
            $.ajax({
                type: 'get',
                url: '/user/bankCard/' + id + '/edit',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {

                        $("input[id='bankcard']").val(result.data['id']);
                        $("input[id='branchName']").val(result.data['branchName']);
                        $("input[id='bankCardNo']").val(result.data['bankCardNo']);
                        $("input[id='accountName']").val(result.data['accountName']);
                        $("input[id='user_id']").val(result.data['user_id']);
                        // $("input[name='id']").val(result.data['id']);
                        // $('.modal-title').html(title);
                        // $('#bank').modal('show');
                    }
                },
                error: function (XMLHttpRequest, textStatus) {
                    toastr.error('通信失败');
                }
            })
        }


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
                    url: '/user/bankCard',
                    data: {'id': id},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        if (result.status) {
                            _this.parents('tr').empty();
                            swal(result.msg, "银行卡已删除。", "success")
                        } else {
                            swal(result.msg, "银行卡未删除。", "error")
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
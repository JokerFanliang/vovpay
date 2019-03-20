@extends('Agent.Commons.layout')
@section('title','交易记录')
@section("css")
    <link rel="stylesheet"
          href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="row" style="margin-top: 20px">
        <!-- ./col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-rmb"></i></span>
                <div class="info-box-content">
                    <span class="progress-description" style="padding-top: 10px;">订单金额 </span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="info-box-number">{{$orderInfoSum[0]['amountSum'] ?: 0}} 元</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-pie-chart"></i></span>
                <div class="info-box-content">
                    <span class="progress-description" style="padding-top: 10px;">我的收入 </span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="info-box-number">{{$orderInfoSum[0]['agentSum'] ?: 0 }} 元</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-rmb"></i></span>

                <div class="info-box-content">
                    <span class="progress-description" style="padding-top: 10px;">交易笔数 </span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="info-box-number">{{$orderInfoSum[0]['orderCount'] ?: 0 }}笔</span>
                </div>
            </div>
        </div>

        <!-- ./col -->

        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">订单记录</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <a href="{{ route('agent.order') }}" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
                    <form action="{{ route('agent.order') }}" method="get">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="系统订单" name="orderNo"
                                       @if(isset($query['orderNo'])) value="{{ $query['orderNo'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="商户订单" name="underOrderNo"
                                       @if(isset($query['underOrderNo'])) value="{{ $query['underOrderNo'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="商户号" name="merchant"
                                       @if(isset($query['merchant'])) value="{{ $query['merchant'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" style="min-width:300px;" id="daterange-btn"
                                       placeholder="订单时间" name="orderTime"
                                       @if(isset($query['orderTime'])) value="{{ $query['orderTime'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="channelId" name="channel_id">
                                    <option value="-1">选择通道</option>
                                    @foreach($chanel_list as $v )
                                        <option value="{{ $v['id'] }}"
                                                @if(isset($query['channel_id']) && $query['channel_id'] == $v['id']) selected @endif>{{ $v['channelName'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="paymentId" name="channel_payment_id">
                                    <option value="-1">选择支付方式</option>
                                    @foreach($payments_list as $v )
                                        <option value="{{ $v['id'] }}"
                                                @if(isset($query['channel_payment_id']) && $query['channel_payment_id'] == $v['id']) selected @endif>{{ $v['paymentName'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="status" name="status">
                                    <option value="-1"
                                            @if(!isset($query['status']) || $query['status'] =='-1') selected @endif >
                                        订单状态
                                    </option>
                                    <option value="0"
                                            @if(isset($query['status']) && $query['status'] =='0') selected @endif>未支付
                                    </option>
                                    <option value="1"
                                            @if(isset($query['status']) && $query['status'] =='1') selected @endif >支付成功
                                    </option>
                                    <option value="2"
                                            @if(isset($query['status']) && $query['status'] =='2') selected @endif>支付异常
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btnSearch">查询</button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-body">
                    <table id="example2"
                           class="table table-striped table-condensed table-bordered table-hover dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>商户号</th>
                            <th>系统订单</th>
                            <th>商户订单</th>
                            <th>订单金额</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>代理收入</th>
                            <th>订单状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        @if(isset($list[0]))
                            @foreach($list as $v)
                                <tr>
                                    <td>{{ $v['id'] }}</td>
                                    <td>{{ $v['merchant'] }}</td>
                                    <td>{{ $v['orderNo'] }}</td>
                                    <td>{{ $v['underOrderNo'] }}</td>
                                    <td><span style="color: #e56c69">{{ $v['amount'] }}</span></td>
                                    <td>{{ $v['created_at'] }}</td>
                                    <td>{{ $v['updated_at'] }}</td>
                                    <td><span style="color: green">{{ $v['agentAmount'] }}</span></td>
                                    <td>
                                        @if($v['status'] == 2)
                                            <span style="color:#dd4b39;font-weight: bold">异常</span>
                                        @elseif($v['status'] == 0)
                                            <span style="color:orange;font-weight: bold">未支付</span>
                                        @elseif($v['status'] == 1)
                                            <span style="color:#008d4c;font-weight: bold">已支付</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm"
                                                onclick="info('订单详情',{{ $v['id'] }})">详情
                                        </button>
                                        @if(env('ADD_ACCOUNT_TYPE') ==3)
                                            @if($v['status'] == 1)
                                                <button type="button" class="btn btn-sm"
                                                        onclick="send({{ $v['id'] }},{{$v['agent_id']}})">
                                                    补发通知
                                                </button>
                                            @elseif($v['status'] == 0)
                                                <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="orderSave({{ $v['id'] }},{{$v['agent_id']}})">改为成功
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" style="text-align: center">没有找到匹配数据</td>
                            </tr>
                        @endif
                    </table>
                    @include('Agent.Commons._page')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
         data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="usersForm" action="" class="form-horizontal" role="form">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">商户订单号</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="underOrderNo" name="underOrderNo"
                                       readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">平台流水号</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="orderNo" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">交易金额</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="amount" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">提交时间</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="created_at" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">成功时间</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="updated_at" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">通道名称</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="channelName" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">支付方式</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="paymentName" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">页面返回地址</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="notifyUrl" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">服务器通知地址</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="successUrl" readonly="readonly">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            {{--<button type="button" class="btn btn-primary" onclick="save($(this))">提交</button>--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection('content')
@section("scripts")
    <script src="{{ asset('AdminLTE/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            $('#daterange-btn').val();

            $('#daterange-btn').daterangepicker(
                {
                    dateLimit: {days: 30},
                    timePicker: false,
                    timePicker24Hour: false,
                    linkedCalendars: false,
                    autoUpdateInput: false,
                    ranges: {
                        '今日': [moment().startOf('day'), moment()],
                        '昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '最近7天': [moment().subtract(6, 'days'), moment()],
                        '最近30天': [moment().subtract(29, 'days'), moment()],
                        '本月': [moment().startOf('month'), moment().endOf('month')],
                        '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    opens: 'right', //日期选择框的弹出位置
                    format: 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
                    locale: {
                        applyLabel: '确定',
                        cancelLabel: '取消',
                        fromLabel: '起始时间',
                        toLabel: '结束时间',
                        customRangeLabel: '自定义',
                        daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                        firstDay: 1,
                        endDate: moment(),
                        format: 'YYYY-MM-DD HH:mm:ss',
                    },
                    startDate: moment().startOf('day'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#daterange-btn').val(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'))
                });
        })


        /**
         * 详情
         * @param id
         * @param title
         */
        function info(title, id) {
            $.ajax({
                type: 'get',
                url: '/agent/order/' + id + '/show',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {
                        $("#underOrderNo").val(result.data['underOrderNo']);
                        $("input[name='orderNo']").val(result.data['orderNo']);
                        $("input[name='amount']").val(result.data['amount']);
                        $("input[name='orderRate']").val(result.data['orderRate']);
                        $("input[name='userAmount']").val(result.data['userAmount']);
                        $("input[name='created_at']").val(result.data['created_at']);
                        $("input[name='updated_at']").val(result.data['updated_at']);
                        $("input[name='channelName']").val(result.data['channelName']);
                        $("input[name='paymentName']").val(result.data['paymentName']);
                        $("input[name='notifyUrl']").val(result.data['notifyUrl']);
                        $("input[name='successUrl']").val(result.data['successUrl']);
                        $('.modal-title').html(title);
                        $('#addModel').modal('show');
                    }
                },
                error: function (XMLHttpRequest, textStatus) {
                    toastr.error('通信失败');
                }
            })
        }


        function orderSave(id, agent_id) {
            swal({
                title: "您确定要改为成功吗？",
                text: "修改后不能恢复！",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{ route('order.saveStatus') }}',
                    dataType: 'json',
                    data: {'id': id, 'agent_id': agent_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        if (result.status == 1) {
                            toastr.success(result.msg);
                            window.location.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus) {
                        toastr.error('通信失败');
                    }
                })
            });
        }

        function send(id, agent_id) {
            $.ajax({
                type: 'post',
                url: '{{ route('order.reissue') }}',
                dataType: 'json',
                data: {'id': id, 'agent_id': agent_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function (XMLHttpRequest, textStatus) {
                    toastr.error('通信失败');
                }
            })
        }
    </script>
@endsection




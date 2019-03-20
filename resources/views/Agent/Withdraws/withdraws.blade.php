@extends("Agent.Commons.layout")
@section('title','结算管理')
@section("css")
    <link rel="stylesheet"
          href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="row" style="margin-top: 20px">
        <div class="col-xs-12">

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
                    <form class="navbar-form navbar-left" action="{{route('user.withdraws')}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" class="form-control" style="min-width:300px;" id="daterange-btn"
                                   placeholder="提现时间" name="orderTime"
                                   @if(isset($query['orderTime'])) value="{{ $query['orderTime'] }}" @endif />
                        </div>

                        <div class="form-group">
                            <select class="form-control" id="paymentId" name="paymentId">
                                <option value="-1">支付方式</option>
                                {{--@foreach($payments_list as $v )--}}
                                {{--<option value="{{ $v['id'] }}">{{ $v['paymentName'] }}</option>--}}
                                {{--@endforeach--}}
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="status" name="status">
                                <option value="0"
                                        @if(isset($query['status']) && $query['status'] =='0') selected @endif>
                                    未处理
                                </option>
                                <option value="1"
                                        @if(isset($query['status']) && $query['status'] =='1') selected @endif >
                                    处理中
                                </option>
                                <option value="2"
                                        @if(isset($query['status']) && $query['status'] =='2') selected @endif>
                                    已结算
                                </option>
                                <option value="3"
                                        @if(isset($query['status']) && $query['status'] =='3') selected @endif>
                                    已取消
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info">搜索</button>&nbsp;&nbsp;
                    </form>

                    <div class="box-body">
                        <table id="example2" class="table table-condensed table-bordered table-hover">
                            <tr style="color: #999999">
                                <th>#</th>
                                <th>商户id</th>
                                <th>银行名称</th>
                                <th>提现金额</th>
                                <th>提现手续费</th>
                                <th>到账金额</th>
                                <th>状态</th>
                                <th>申请时间</th>
                                <th>处理时间</th>
                            </tr>
                            @if(isset($list[0]))
                                @foreach($list as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->user_id+10000}}</td>
                                        <td>{{$v->bankName}}</td>
                                        <td>{{$v->withdrawAmount}}</td>
                                        <td>{{$v->withdrawRate}}</td>
                                        <td>{{$v->toAmount}}</td>
                                        <td>{{$v->status?'已结算':'未处理'}}</td>
                                        <td>{{$v->created_at}}</td>
                                        <td>__ __ __</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" style="color: #999999">没有找到匹配数据</td>
                                </tr>
                            @endif
                        </table>
                        {{$list->appends($data)->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script src="{{ asset('AdminLTE/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            $('#daterange-btn').val(moment().startOf('day').format('YYYY-MM-DD HH:mm:ss') + ' - ' + moment().format('YYYY-MM-DD HH:mm:ss'));

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

    </script>
@endsection
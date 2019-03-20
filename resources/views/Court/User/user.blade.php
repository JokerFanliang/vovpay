@extends('Court.Commons.layout')
@section('title','我的账户')
@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="content-wrapper" style="padding: 0;margin: 0">
                    <section class="content">
                        <div class="row" style="margin-top: 20px">
                            <div class="col-md-4">
                                <div class="box box-primary box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">个人信息</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <form class="form-horizontal">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">用户名</label>
                                                    <div class="col-sm-8">
                                                        <label class="control-label"
                                                               style="word-wrap:break-word; word-break:break-all; text-align:left;font-weight: 400;">
                                                            {{ $court['username'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">商户号</label>
                                                    <div class="col-sm-8">
                                                        <label class="control-label"
                                                               style="word-wrap:break-word; word-break:break-all; text-align:left;font-weight: 400;">
                                                            {{ $court['merchant'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">剩余分数</label>
                                                    <div class="col-sm-8">
                                                        <label class="control-label"
                                                               style="color:red;word-wrap:break-word; word-break:break-all; text-align:left;font-weight: 400;">
                                                            {{ $court['quota'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Email</label>
                                                    <div class="col-sm-8">
                                                        <label class="control-label"
                                                               style="word-wrap:break-word; word-break:break-all; text-align:left;font-weight: 400;">
                                                            {{ $court['email'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">电话</label>
                                                    <div class="col-sm-8">
                                                        <label class="control-label"
                                                               style="word-wrap:break-word; word-break:break-all; text-align:left;font-weight: 400;">
                                                            {{ $court['phone'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{--<div class="box box-primary">--}}
                                    <div class="info-box bg-aqua">
                                        <span class="info-box-icon"><i class="fa fa-rmb"></i></span>
                                        <div class="info-box-content">
                                            <span class="progress-description" style="padding-top: 10px;">订单金额 （今日统计）</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 100%"></div>
                                            </div>
                                            <span class="info-box-number">{{$orderInfoSum[0]['amountSum'] ?: 0}} 元</span>
                                        </div>
                                    </div>
                                {{--</div>--}}
                            </div>

                            <div class="col-md-8">
                                <div class="box box-primary box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">上分记录</h3>

                                        <div class="box-tools pull-right">
                                            <a href="{{ route('court.user') }}" class="btn"><i class="fa fa-undo"></i>刷新</a>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <form action="{{ route('court.user') }}" method="get">
                                            <div class="form-inline">
                                                <div class="form-group">
                                                    <input type="text" autocomplete="off" class="form-control" style="min-width:300px;" id="daterange-btn"
                                                           placeholder="订单时间" name="searchTime"
                                                           @if(isset($query['searchTime'])) value="{{ $query['searchTime'] }}" @endif />
                                                </div>
                                                <button type="submit" class="btn btn-primary" id="btnSearch">查询</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="box-body">
                                        增加分数：<span style="color: #FF0000;">{{ $data['add_num'] }}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        减少分数：<span style="color: #FF0000;">{{ $data['reduce_num'] }}</span>
                                    </div>
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover">
                                            <tr style="color: #999999;background:#f5f6f9">
                                                <th>#</th>
                                                <th>操作分数</th>
                                                <th>上分类型</th>
                                                <th>操作类型</th>
                                                <th>操作时间</th>
                                            </tr>
                                            @foreach($list as $v)
                                            <tr>
                                                <td>#</td>
                                                <td>{{ $v['quota'] }}</td>
                                                <td>
                                                    @if($v['quota_type'] == 0)
                                                    <span class="btn btn-success btn-sm">增加</span>
                                                    @else
                                                    <span class="btn btn-danger btn-sm">减少</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($v['action_type'] == 0)
                                                    <span class="btn btn-success btn-sm">手动</span>
                                                    @else
                                                    <span class="btn btn-primary btn-sm">订单</span>
                                                    @endif
                                                </td>
                                                <td>{{ $v['created_at'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        {{$list->links()}}
                                    </div>
                                </div>
                            </div>


                            <!-- /.box -->
                        </div>
                        <!-- /.col -->

                    </section>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>


@endsection('content')
@section("scripts")
    <script src="{{ asset('AdminLTE/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function(){
            // $('#daterange-btn').val(moment().startOf('day').format('YYYY-MM-DD HH:mm:ss') + ' - ' + moment().format('YYYY-MM-DD HH:mm:ss'));

            $('#daterange-btn').daterangepicker(
                {
                    dateLimit:{days:30},
                    timePicker : false,
                    timePicker24Hour : false,
                    linkedCalendars : false,
                    autoUpdateInput : false,
                    ranges : {
                        '今日'    : [moment().startOf('day'), moment()],
                        '昨日'    : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '最近7天' : [moment().subtract(6, 'days'), moment()],
                        '最近30天': [moment().subtract(29, 'days'), moment()],
                        '本月'    : [moment().startOf('month'), moment().endOf('month')],
                        '上月'    : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    opens : 'right', //日期选择框的弹出位置
                    format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
                    locale : {
                        applyLabel : '确定',
                        cancelLabel : '取消',
                        fromLabel : '起始时间',
                        toLabel : '结束时间',
                        customRangeLabel : '自定义',
                        daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                        monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                        firstDay : 1,
                        endDate : moment(),
                        format : 'YYYY-MM-DD HH:mm:ss',
                    },
                    startDate: moment().startOf('day'),
                    endDate  : moment()
                },
                function(start, end) {
                    $('#daterange-btn').val(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'))
                });
        })
    </script>
@endsection
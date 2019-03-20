@extends("Admin.Commons.layout")    @section('title',$title)
@section("css")
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <button onclick="javascript:history.back(-1);" class="btn btn-primary"><i class="fa fa-undo"></i>返回上一页</button>
                    <a href="{{ route('users.quotaLog',array('id'=>$uid)) }}" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
                </div>
                <!-- /.box-header -->
                <div class="box box-primary">
                    <div class="box-body">
                        <form action="{{ route('users.quotaLog',array('id'=>$uid)) }}" method="get">
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
                    <!-- /.box-body -->
                </div>
                <div class="box-body">
                    <table id="example2" class="table table-condensed table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>商户名</th>
                            <th>操作分数</th>
                            <th>上分类型</th>
                            <th>操作类型</th>
                            <th>操作时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $v)
                            <tr>
                                <td>{{ $v->id }}</td>
                                <td>{{ $v->user->username }}</td>
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
                        </tbody>
                    </table>
                    @include('Admin.Commons._page')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection('content')
@section("scripts")
<script src="{{ asset('AdminLTE/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script>
    $(function(){
        $('#daterange-btn').val(moment().startOf('day').format('YYYY-MM-DD HH:mm:ss') + ' - ' + moment().format('YYYY-MM-DD HH:mm:ss'));

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




@extends("User.Commons.layout")
@section('title','用户充值')
@section('css')
    <link rel="stylesheet"
          href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="row" style="margin-top: 20px">

        <div class="col-md-6">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">数据统计</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-rmb"></i></span>

                            <div class="info-box-content">

						<span class="progress-description" style="padding-top: 10px;">
							充值金额 </span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="info-box-number">0.00 元</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>

                            <div class="info-box-content">

						<span class="progress-description" style="padding-top: 10px;">
							充值记录 </span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="info-box-number">0 笔</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">充值</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>


                <div class="box-body" style="font-size: 15px">

                    <form class="form-inline" style="margin: auto 60px">
                        <div class="form-group">
                            <label for="exampleInputName2">充值金额:</label>&emsp;
                            <select name="" class="form-control">
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                                <option value="2000">2000</option>
                                <option value="5000">5000</option>
                                <option value="10000">10000</option>
                            </select>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="exampleInputName2">支付方式:</label>&emsp;
                            <input class="flat-red" id="alipayradio" type="radio" name="paytype" checked>
                            <img src="/AdminLTE/dist/img/ico_zfb.png" alt="">&emsp;
                            {{--<input class="flat-red" id="alipayradio" type="radio" name="paytype">--}}
                            {{--<img src="/AdminLTE/dist/img/ico_wx.png" alt="">--}}
                        </div>
                        <br>
                        <br>

                        <div class="form-group" style="margin-left: 50px">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-reddit">立即支付</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">充值记录</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <form class="navbar-form navbar-left" action="">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" class="form-control" style="min-width:300px;" id="daterange-btn"
                                   placeholder="订单时间" name="orderTime"
                                   @if(isset($query['orderTime'])) value="{{ $query['orderTime'] }}" @endif />
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="paymentId" name="paymentId">
                                <option value="-1">支付方式</option>
                                <option value="">支付宝</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="underOrderNo" placeholder="订单号">
                        </div>
                        <button type="submit" class="btn btn-info">搜索</button>&nbsp;&nbsp;
                    </form>
                    <br>
                    <br>
                    <br>

                    <div class="container-fluid">
                        <table id="example2" class="table table-condensed table-bordered table-hover">
                            <tr style="color: #999999">
                                <th>支付通道</th>
                                <th>订单号</th>
                                <th>商户号</th>
                                <th>实付金额</th>
                                <th>支付时间</th>
                                <th>订单状态</th>
                                <th>操作</th>
                            </tr>

                            <tr>
                                <td colspan="7" style="text-align: center">没有找到匹配数据</td>
                            </tr>
                        </table>
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
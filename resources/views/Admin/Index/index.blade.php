@extends("Admin.Commons.layout")    @section('title',$title)
@section('content')
<div class="row">
    <!-- ./col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-rmb"></i></span>
            <div class="info-box-content">
                <span class="progress-description" style="padding-top: 10px;">今日订单金额</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="info-box-number">@if(isset($sys_day_count->sys_amount)){{ $sys_day_count->sys_amount }} @else 0 @endif  元</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-rmb"></i></span>
            <div class="info-box-content">
                <span class="progress-description" style="padding-top: 10px;">今日平台收入</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="info-box-number">@if(isset($sys_day_count->sys_income)){{ $sys_day_count->sys_income }} @else 0 @endif 元</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-pie-chart"></i></span>
            <div class="info-box-content">
                <span class="progress-description" style="padding-top: 10px;">今日成功笔数</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="info-box-number">@if(isset($sys_day_count->sys_order_suc_count)){{ $sys_day_count->sys_order_suc_count }} @else 0 @endif 笔</span>
            </div>
        </div>
    </div>
    <!-- ./col -->
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">平台订单过去7天统计</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_line"   style="height:300px" ></canvas>
                </div>
                <div id="chart_line_legend" class="line-legend"></div>
            </div>
        </div>
    </div>
</div>
@endsection('content')
@section("scripts")
<script src="{{ asset('js/Chart.min.js') }}"></script>
<!-- ChartJS -->
<!-- FastClick -->
<script src="{{ asset('AdminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script>
var data = JSON.parse('{!! $order_day_count !!}');
var chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
};
var chartBar ;
var barChartData;
$(document).ready(function(){

    var weeklabelsData=[ getDate(-6), getDate(-5),  getDate(-4),  getDate(-3),  getDate(-2), getDate(-1), getDate(0)];

    var color = Chart.helpers.color;

    barChartData = {
        labels : weeklabelsData,
        datasets : [
            {
                label: '订单金额',
                backgroundColor: color( chartColors.purple).alpha(0.5).rgbString(),
                borderColor:  chartColors.red,
                data : [getDate(-6) in data ? data[getDate(-6)]['sys_amount'] : 0, getDate(-5) in data ? data[getDate(-5)]['sys_amount'] : 0, getDate(-4) in data ? data[getDate(-4)]['sys_amount'] : 0, getDate(-3) in data ? data[getDate(-3)]['sys_amount'] : 0, getDate(-2) in data ? data[getDate(-2)]['sys_amount'] : 0,  getDate(-1) in data ? data[getDate(-1)]['sys_amount'] : 0, getDate(0) in data ? data[getDate(0)]['sys_amount'] : 0],
                yAxisID: 'y-axis-1'
            } ,
            {
                label: '平台收益',
                backgroundColor: color( chartColors.blue).alpha(0.5).rgbString(),
                borderColor:  chartColors.blue,
                yAxisID: 'y-axis-2',
                data :[getDate(-6) in data ? data[getDate(-6)]['sys_income'] : 0, getDate(-5) in data ? data[getDate(-5)]['sys_income'] : 0, getDate(-4) in data ? data[getDate(-4)]['sys_income'] : 0, getDate(-3) in data ? data[getDate(-3)]['sys_income'] : 0, getDate(-2) in data ? data[getDate(-2)]['sys_income'] : 0,  getDate(-1) in data ? data[getDate(-1)]['sys_income'] : 0, getDate(0) in data ? data[getDate(0)]['sys_income'] : 0],
            }
        ]

    };
    var ctx   = document.getElementById("chart_line") .getContext('2d');
    chartBar  =new Chart(ctx , {
        type: 'bar',
        data: barChartData,
        options: {
            responsive: true,
            title: {
                display: false
            },
            tooltips: {
                mode: 'index',
                intersect: true
            },
            scales: {
                yAxes: [{
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        beginAtZero:true
                    },
                    id: 'y-axis-1',
                }, {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    ticks: {
                        beginAtZero:true
                    },
                    id: 'y-axis-2',
                    gridLines: {
                        drawOnChartArea: false
                    }
                }],
            }
        }
    });
});
function getDate(index){
    var date = new Date(); //当前日期
    var newDate = new Date();
    newDate.setDate(date.getDate() + index);//官方文档上虽然说setDate参数是1-31,其实是可以设置负数的
    var time = newDate.getFullYear()+"-"+Appendzero(newDate.getMonth()+1)+"-"+Appendzero(newDate.getDate());
    return time;
}

function Appendzero(obj)
{
    if(obj<10) return "0" +""+ obj;
    else return obj;
}


</script>
@endsection

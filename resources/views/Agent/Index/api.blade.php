@extends("Agent.Commons.layout")
@section('title',$title)
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection
@section('content')
    <div class="row">
     
        {{--同步跳转--}}
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">API列表</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box-body" style="padding:20px auto;font-size: 15px">
                        <table class="table table-bordered" style="margin: 10px auto">
                        <tr style="color: #999999;background: #f5f6f9">
                                <th>编码</th>
                                <th>通道名称</th>
                                <th>通道费率</th>
                                <th>通道状态</th>
                        </tr>
                        @foreach ($list as $value)
                            <tr>
                                    <td>{{ $value['paymentCode'] }}</td>
                                    <td>{{ $value['paymentName'] }}</td>
                                    <td>{{ $value['rate'] * 100 }}%</td>
                                    <td> @if ($value['status'] == 1)开通 @else 关闭 @endif </td>
                            </tr>
                        @endforeach
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
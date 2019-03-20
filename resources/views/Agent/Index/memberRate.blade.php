@extends("Agent.Commons.layout")
@section('title','商户费率')
@section("css")
    <link rel="stylesheet"
          href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="content-wrapper" style="padding: 0;margin: 0">
        <section class="content">


            <div class="row" style="margin-top: 20px">

                <div class="col-md-8">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">商户费率</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover table-bordered">
                                <tr style="color: #666666;background: #f5f6f9">
                                    <th>接口名称</th>
                                    <th>充值费率</th>
                                </tr>
                                <tr>
                                    <td>支付宝H5</td>
                                    <td>0.000</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
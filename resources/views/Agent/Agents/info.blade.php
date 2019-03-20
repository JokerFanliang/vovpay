@extends("Agent.Commons.layout")
@section('title','个人信息')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection
@section('content')
    <div class="content-wrapper" style="padding: 0;margin: 0">
        <section class="content">


            <div class="row" style="margin-top: 20px">

                <div class="col-md-12">
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
                                {{--<div class="container">--}}
                                <div class="row form-group">
                                    <label class="control-label col-lg-1" for="name">姓名</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input class="form-control" name="name" id="name" type="text" value="刘德华">
                                    </div>
                                    <label class="control-label col-lg-1" for="name">身份证号码</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input class="form-control" name="name" id="name" type="text"
                                               value="18936578954123688966">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="control-label col-lg-1" for="name">手机:</label>
                                    <div class="col-lg-2 col-md-2">
                                        <input class="form-control" name="name" id="name" type="text"
                                               value="18288999988">
                                    </div>
                                    <label class="control-label col-lg-3" for="name">生日:</label>
                                    <div class="col-lg-2 col-md-2">
                                        <input class="form-control" name="name" id="name" type="text"
                                               value="1998-15-37">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="control-label col-lg-1" for="name">性别:</label>
                                    <div class="col-lg-2 col-md-2">
                                        <input type="radio" name="sex" id="optionsRadios1" value="" checked>男
                                        &emsp;
                                        <input type="radio" name="sex" id="optionsRadios1" value="">女
                                    </div>
                                    <label class="control-label col-lg-3" for="name">联系地址</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input class="form-control" name="name" id="name" type="text"
                                               value="撒哈拉联合酋长部落">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-lg-1" for="name">QQ:</label>
                                    <div class="col-lg-2 col-md-2">
                                        <input class="form-control" name="name" id="name" type="text"
                                               value="1804069318">
                                    </div>
                                    <label class="control-label col-lg-3" for="name">Email:</label>
                                    <div class="col-lg-2 col-md-2">
                                        <input class="form-control" name="name" id="name" type="text"
                                               value="1804069318@qq.com">
                                    </div>
                                </div>

                                <div style="margin-left: 550px;margin-top: 35px">
                                <button class="btn btn-primary" >立即保存</button>
                                </div>
                                {{--</div>--}}
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>


@endsection
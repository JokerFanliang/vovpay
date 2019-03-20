@extends('Agent.Commons.layout')

@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <button onclick="javascript:history.back(-1);" class="btn btn-primary"><i class="fa fa-undo"></i>返回上一页</button>
            </div>
            <div class="box-body">
                <table id="example2" class="table table-condensed table-bordered table-hover">
                    <input type="hidden" id="uid" value="{{$uid}}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>支付方式</th>
                        <th>状态</th>
                        <th>费率</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $v)
                        <tr>
                            <td>{{ $v['id']  }}</td>
                            <td>{{ $v['paymentName'] }}</td>
                            <td>
                                <input class="switch-state" data-channel="{{ $v['channel_id'] }}"  data-id="{{ $v['id'] }}" type="checkbox" @if($v['status'] == 1) checked @endif >
                            </td>
                            <td>{{ $v['rate'] }}</td>
                            <td> <button type="button" class="btn btn-primary btn-sm" data-channel="{{ $v['channel_id'] }}"  data-id="{{ $v['id'] }}" onclick="edit($(this), '用户通道编辑', '{{ $v['paymentName'] }}')">编辑</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->

    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="usersForm" action="{{ route('agent.userRateStore', array('id'=>$uid)) }}" class="form-horizontal" role="form">
                        <input type="hidden" name="payId">
                        <input type="hidden" name="channelId">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">支付方式</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" disabled="1" name="paymentName" placeholder="用户名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">费率</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="rate" placeholder="费率">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">状态</label>
                            <div class="col-xs-9">

                                <select class="form-control" name="status">
                                    <option value="1">启用</option>
                                    <option value="0">禁用</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="save($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.row -->
@endsection('content')
@section("scripts")
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script>
        $(function () {
            // 状态修改
            $('.switch-state').bootstrapSwitch({
                onText:'启用',
                offText:'禁用' ,
                onColor:"primary",
                offColor:"danger",
                size:"small",
                onSwitchChange:function(event,state) {
                    var id = $(event.currentTarget).data('id'),
                        uid= $('#uid').val(),
                        channelId = $(event.currentTarget).data('channel');
                    $.ajax({
                        type: 'POST',
                        url: '/agent/user/'+uid+'/saveUserRate',
                        data:{'status':state,'payId':id,'channelId':channelId},
                        dataType:'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(result){
                            if(result.status)
                            {
                                toastr.success(result.msg);
                            }else{
                                $('#addModel').modal('hide');
                                toastr.error(result.msg);
                            }
                        },
                        error:function(XMLHttpRequest,textStatus){
                            toastr.error('通信失败');
                        }
                    })
                }
            })
        })

        /**
         * 编辑
         * @param id
         * @param title
         */
        function edit(_this, title, paymentname)
        {
            var uid = $('#uid').val(),
                 id = _this.data('id'),
                channelId = _this.data('channel');
            $.ajax({
                type: 'get',
                url: '/agent/user/'+uid+'/rate',
                dataType:'json',
                data:{'payId':id,'channelId':channelId},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    if(result.status == 1)
                    {
                        $("#username").val(result.data['username']);
                        $("input[name='paymentName']").val(paymentname);
                        $("input[name='rate']").val(result.data['rate']);
                        $("select[name='status']").val(result.data['status']);
                        $("input[name='payId']").val(result.data['channel_payment_id']);
                        $("input[name='channelId']").val(result.data['channel_id']);
                        $('.modal-title').html(title);
                        $('#addModel').modal('show');
                    }
                },
                error:function(XMLHttpRequest,textStatus){
                    toastr.error('通信失败');
                }
            })
        }

        /**
         * 提交
         */
        function save(_this){

            _this.removeAttr('onclick');
            var $form = $('#usersForm'),
                regex = /^[0-9]+([.]{1}[0-9]+){0,1}$/,
                rate = $("input[name='rate']").val();

            if(!regex.test(rate))
            {
                toastr.error('格式错误');
                _this.attr("onclick","save($(this))");
                return ;
            }
            $.post($form.attr('action'), $form.serialize(), function(result) {
                if(result.status)
                {
                    $('#addModel').modal('hide');
                    setInterval(function(){
                        window.location.reload();
                    },2000);

                    toastr.success(result.msg);
                }else{
                    $('#addModel').modal('hide');
                    _this.attr("onclick","save($(this))");
                    toastr.error(result.msg);
                }
            }, 'json');

        }
    </script>
@endsection




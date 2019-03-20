@extends("Admin.Commons.layout")    @section('title',$title)

@section("css")

@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <button type="button" class="btn btn-primary" onclick="showModel('添加配置')">添加配置</button>
                        <a href="{{ route('system.index') }}" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
                    </div>
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>配置键名</th>
                                <th>配置键值</th>
                                <th>配置说明</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $v)
                            <tr>
                                <td>{{ $v['name'] }}</td>
                                <td>{{ $v['value'] }}</td>
                                <td>{{ $v['remark'] }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm"
                                            onclick="edit('编辑',{{ $v['id'] }})">编辑
                                    </button>
                                </td>
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
        </div>
        <!-- /.row -->
    </section>
    <!-- 模态框 -->
    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
         data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="systemForm" action="{{ route('system.store') }}" class="form-horizontal" role="form">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">配置键名</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="name" placeholder="配置键名">
                                <span class="help-block" style="font-size: 12px;"><i class="fa fa-info-circle"></i>只能是字母,且唯一</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">配置键值</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="value" placeholder="配置键值">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">配置说明</label>
                            <div class="col-xs-9">
                                <textarea name="remark" class="form-control" id="" cols="30" rows="10" placeholder="配置说明"></textarea>
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
@endsection('content')
@section("scripts")
<script>

    /**
     * 显示模态框
     */
    function showModel(title) {
        $('#addModel .modal-title').html(title);
        $('#addModel').modal('show');
    }

    /**
     * 提交
     */
    function save(){

        var $form = $('#systemForm');
        $.post($form.attr('action'), $form.serialize(), function(result) {
            if(result.status)
            {
                $('#addModel').modal('hide');
                setInterval(function(){
                    window.location.reload();
                },1000);

                toastr.success(result.msg);
            }else{
                $('#addModel').modal('hide');
                toastr.error(result.msg);
            }
        }, 'json');

    }

    /**
     * 编辑
     * @param id
     * @param title
     */
    function edit(title, id) {

        $.ajax({
            type: 'get',
            url: '/admin/system/' + id + '/edit',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.status == 1) {
                    $("input[name='name']").attr('Disabled',true);
                    $("input[name='name']").val(result.data['name']);
                    $("input[name='value']").val(result.data['value']);
                    $("textarea[name='remark']").val(result.data['remark']);
                    $("input[name='id']").val(result.data['id']);
                    $('.modal-title').html(title);
                    $('#addModel').modal('show');
                }
            },
            error: function (XMLHttpRequest, textStatus) {
                toastr.error('通信失败');
            }
        })
    }

    // 模态关闭
    $('#addModel').on('hidden.bs.modal', function () {
        $("input[name='name']").attr('Disabled',false);
        $('#systemForm').get(0).reset();
        $("input[name='id']").val('');
    });
</script>
@endsection




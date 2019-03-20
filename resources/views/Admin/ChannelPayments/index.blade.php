@extends("Admin.Commons.layout")    @section('title',$title)

@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-fileinput-4.5.1/fileinput.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <button type="button" class="btn btn-primary" onclick="showModel('添加支付方式')">添加支付方式</button>
                <a href="{{ route('channelPayments.index') }}" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example2" class="table table-condensed table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>支付名称</th>
                        <th>支付编码</th>
                        <th>所属通道</th>
                        <th>运营费率</th>
                        <th>单笔限额</th>
                        <th>支付Logo</th>
                        <th>支付状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $v)
                        <tr>
                            <td>{{ $v['paymentName'] }}</td>
                            <td>{{ $v['paymentCode'] }}</td>
                            <td>{{ @$v->Channel()->pluck('channelName')[0] }}</td>
                            <td><span style="color: #0f74a8">{{ $v->runRate }}</span></td>
                            <td><span style="color: #999999;font-weight: bold">{{ $v->minAmount }}-{{ $v->maxAmount }} 元</span></td>
                            <td><img width="148" height="38" src="{{ asset($v['ico']) }}" alt=""></td>
                            <td><input class="switch-state" data-id="{{ $v['id'] }}" type="checkbox" @if($v['status'] == 1) checked @endif ></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="edit('支付方式编辑',{{ $v['id'] }})">编辑</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="del($(this),{{ $v['id'] }})">删除</button>
                            </td>
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

<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="margin-top: 123px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <form id="adminsForm" action="{{ route('channelPayments.store') }}" class="form-horizontal" role="form">
                    <input type="hidden" name="id">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">支付名称</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="paymentName" placeholder="支付名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">支付编码</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="paymentCode" placeholder="支付编码">
                            <span class="help-block" style="font-size: 12px;"><i class="fa fa-info-circle"></i>只能是字母,且唯一</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">支付Logo</label>
                        <div class="col-xs-9">
                            <input type="file" name="ico" id="ico" class="file-loading" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">所属通道</label>
                        <div class="col-xs-9">
                            <select class="form-control selectpicker" name="channel_id">
                                @foreach($channel_list as $v)
                                    <option value="{{ $v['id'] }}">{{ $v['channelName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">运营费率</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="runRate" value="0" placeholder="运营费率">
                            <span class="help-block" style="font-size: 12px;">
                                <i class="fa fa-info-circle"></i>费率转为小数即可
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">成本费率</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="costRate" value="0"  placeholder="成本费率">
                            <span class="help-block" style="font-size: 12px;">
                                <i class="fa fa-info-circle"></i>费率转为小数即可
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">单笔最小金额</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="minAmount" value="0" placeholder="单笔最小金额">
                            <i class="fa fa-info-circle"></i>0不限制
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">单笔最大金额</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="maxAmount" value="0"  placeholder="单笔最大金额">
                            <i class="fa fa-info-circle"></i>0不限制
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
                    <input type="hidden" name="fileIco" id="fileIco">
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
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-fileinput-4.5.1/fileinput.js') }}"></script>
    <script>
        $(function () {

            //0.初始化fileinput
            var oFileInput = new FileInput();
            oFileInput.Init("ico", "/admin/channelPayments/upload");

            formValidator();
            // 状态修改
            $('.switch-state').bootstrapSwitch({
                onText:'启用',
                offText:'禁用' ,
                onColor:"primary",
                offColor:"danger",
                size:"small",
                onSwitchChange:function(event,state) {
                    var id =  $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '/admin/channelPayments/saveStatus',
                        data:{'status':state,'id':id},
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

            // 模态关闭
            $('#addModel').on('hidden.bs.modal', function() {
                $("#adminsForm").data('bootstrapValidator').destroy();
                $('#adminsForm').data('bootstrapValidator', null);
                $('#adminsForm').get(0).reset();
                formValidator();
            });

        })

        /**
         * 提交
         */
        function save(_this){
            $('#adminsForm').data('bootstrapValidator').validate();
            if(!$('#adminsForm').data('bootstrapValidator').isValid()){
                return ;
            }
            _this.removeAttr('onclick');

            var $form = $('#adminsForm');
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
                    _this.attr("onclick","save($(this))");
                    toastr.error(result.msg);
                }
            }, 'json');

        }

        /**
         * 表单验证
         */
        function formValidator()
        {
            $('#adminsForm').bootstrapValidator({
                message: '输入值不合法',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    paymentName: {
                        validators: {
                            notEmpty: {
                                message: '支付名称不能为空!'
                            },
                            stringLength: {
                                max: 30,
                                message: '支付名称最大长度%s个字符!'
                            }
                        }
                    },
                    paymentCode: {
                        validators: {
                            notEmpty: {
                                message: '支付编码不能为空!'
                            },
                            stringLength: {
                                max: 20,
                                message: '支付编码最大长度%s个字符!'
                            },
                            regexp: { //正则校验
                                regexp: /^[A-Za-z0-9_]+$/,
                                message:'支付编码格式不正确!'
                            },
                        }
                    },
                    runRate: {
                        validators:{
                            notEmpty: {
                                message: '运营费率不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+([.]{1}[0-9]+){0,1}$/,
                                message:'费率只能是整数或小数!'
                            },
                        }
                    },
                    costRate: {
                        validators:{
                            notEmpty: {
                                message: '成本费率不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+([.]{1}[0-9]+){0,1}$/,
                                message:'费率只能是整数或小数!'
                            },
                        }
                    },
                    minAmount: {
                        validators:{
                            notEmpty: {
                                message: '单笔最小金额不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+$/,
                                message:'金额只能是整数'
                            },
                        }
                    },
                    maxAmount: {
                        validators:{
                            notEmpty: {
                                message: '单笔最大金额不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+$/,
                                message:'金额只能是整数'
                            },
                        }
                    }
                }
            });
        }

        /**
         * 显示模态框
         */
        function showModel(title)
        {
            $('.modal-title').html(title);
            $('#addModel').modal('show');
        }

        /**
         * 编辑
         * @param id
         * @param title
         */
        function edit(title, id)
        {
            $.ajax({
                type: 'get',
                url: '/admin/channelPayments/'+id+'/edit',
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    if(result.status == 1)
                    {
                        $("input[name='paymentName']").val(result.data['paymentName']);
                        $("input[name='paymentCode']").val(result.data['paymentCode']);
                        $("select[name='status']").val(result.data['status']);
                        $("select[name='channel_id']").val(result.data['channel_id']);
                        $("input[name='id']").val(result.data['id']);
                        $("input[name='fileIco']").val(result.data['ico']);
                        $("input[name='minAmount']").val(result.data['minAmount']);
                        $("input[name='maxAmount']").val(result.data['maxAmount']);
                        $("input[name='runRate']").val(result.data['runRate']);
                        $("input[name='costRate']").val(result.data['costRate']);
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
         * 删除
         * @param _this
         */
        function del(_this,id){
            swal({
                title: "您确定要删除吗？",
                text: "删除后不能恢复！",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function(){
                $.ajax({
                    type: 'delete',
                    url: '/admin/channelPayments',
                    data:{'id':id},
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(result){
                        if(result.status)
                        {
                            _this.parents('tr').empty();
                            swal(result.msg, "支付方式已被删除。","success")
                        }else{
                            swal(result.msg, "支付方式没有被删除。","error")
                        }

                    },
                    error:function(XMLHttpRequest,textStatus){
                        toastr.error('通信失败');
                    }
                })
            });
        }

        //初始化fileinput
        var FileInput = function () {
            var oFile = new Object();

            //初始化fileinput控件（第一次初始化）
            oFile.Init = function(ctrlName, uploadUrl) {
                var control = $('#' + ctrlName);

                //初始化上传控件的样式
                control.fileinput({
                    language: 'zh', //设置语言
                    uploadUrl: uploadUrl, //上传的地址
                    allowedFileExtensions: ['jpg', 'png', 'jpeg'],//接收的文件后缀
                    showUpload: true, //是否显示上传按钮
                    showCaption: false,//是否显示标题
                    showPreview: true,//是否开启预览,默认为true
                    browseClass: "btn btn-primary", //按钮样式
                    dropZoneEnabled: false,//是否显示拖拽区域
                    //minImageWidth: 50, //图片的最小宽度
                    //minImageHeight: 50,//图片的最小高度
                    //maxImageWidth: 1000,//图片的最大宽度
                    //maxImageHeight: 1000,//图片的最大高度
                    maxFileSize: 200,//单位为kb，如果为0表示不限制文件大小
                    //minFileCount: 0,
                    maxFileCount: 1, //表示允许同时上传的最大文件个数
                    enctype: 'multipart/form-data',
                    validateInitialCount:true,
                    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
                    msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
                    uploadExtraData: {
                        "_token": $('meta[name="csrf-token"]').attr('content') //参数
                    }
                });

                //导入文件上传完成之后的事件
                $("#ico").on("fileuploaded", function (event, data) {
                    var data = data.response;
                    if (data.status == 1) {
                        $('#fileIco').val(data.data.path);
                        return;
                    }else{
                        toastr.error(data.msg);
                        return false;
                    }
                });
            }
            return oFile;
        };
    </script>
@endsection




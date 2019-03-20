@extends("Admin.Commons.layout")    @section('title',$title)
@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/zTree/css/zTreeStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/setRules.css') }}">
@endsection

@section("content")
<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <!-- form start -->
            <form class="form-horizontal" action="{{ Route('setRules',array('id'=>$role_id)) }}" id="MyForm">
                <ul id="treeDemo" class="ztree"></ul>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-info" onclick="save()">提交</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection
@section("scripts")
    <script src="{{ asset('plugins/zTree/js/jquery.ztree.core.min.js') }}"></script>
    <script src="{{ asset('plugins/zTree/js/jquery.ztree.excheck.min.js') }}"></script>
    <script type="text/javascript">
        var setting = {
            check:{enable: true},
            view: {showLine: false, showIcon: false, dblClickExpand: false},
            data: {
                simpleData: {enable: true, pIdKey:'pid', idKey:'id'},
                key:{name:'title'}
            }
        };
        var zNodes = {!!$rules!!};
        function setCheck() {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.setting.check.chkboxType = { "Y":"ps", "N":"ps"};

        }
        $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        setCheck();

        function save()
        {
            var $form = $('#MyForm'),
                treeObj = $.fn.zTree.getZTreeObj("treeDemo"),
                nodes=treeObj.getCheckedNodes(true),
                v=[];
            for(var i=0;i<nodes.length;i++){
                v[i]=nodes[i].id;
            }
            $.ajax({
                type: 'put',
                url: $form.attr('action'),
                data: { rules: v},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    if(result.status)
                    {
                        toastr.success(result.msg);
                    }else{
                        toastr.error(result.msg);
                    }
                },
                error:function(XMLHttpRequest,textStatus){
                    toastr.error('通信失败');
                }
            })
        }
    </script>
@endsection
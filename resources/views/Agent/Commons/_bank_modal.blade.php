<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="margin-top: 123px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <form id="bankForm" action="{{ route('agent.store') }}" class="form-horizontal" role="form"
                      method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">银行名称:</label>
                        <div class="col-xs-9">
                            <select class="form-control" id="bankid" name="bank_id">
                                <option value="0">
                                    选择银行
                                </option>
                                @if(isset($banks[0]))
                                    @foreach($banks as $v)
                                        <option value="{{$v->id}}">
                                            {{--@if($v['status'] =='-1') selected @endif >>--}}
                                            {{$v->bankName}}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="0}">
                                        {{--@if(!isset($query['status']) || $query['status'] =='-1') selected @endif >--}}
                                        没有系统预设银行
                                    </option>
                                @endif
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">持卡人:</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="accountName" placeholder="请输入开户名">
                            <input type="hidden" class="form-control" name="id" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">支行名称:</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="branchName" placeholder="请输入支行名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">银行卡号:</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="bankCardNo" placeholder="请输入银行卡号">
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
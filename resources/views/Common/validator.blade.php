@extends($module.".Commons.layout")
@section('title','安全设置')
@section("css")
    <link rel="stylesheet"
          href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="content-wrapper" style="padding: 0;margin: 0">
        <section class="content">
            <div class="row" style="margin-top: 20px">
                <div class="col-md-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">google身份验证器</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            @if(!auth()->user()->google_key)
                            <table class="table table-bordered">
                                <tr>
                                    <td style="width: 80px">第一步</td>
                                    <td>下载谷歌验证器（Google Authenticator）</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="https://itunes.apple.com/cn/app/google-authenticator/id388497605?mt=8"><img src="/AdminLTE/dist/img/apple_btn.png"></a>
                                        <a href="https://mobile.baidu.com/item?docid=6132999&source=mobres&from=1010680m"><img src="/AdminLTE/dist/img/android_btn.png"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>第二步</td>
                                    <td>
                                        <p style="margin-bottom: 15px"><b>使用谷歌验证器扫描二维码或输入以下秘钥</b></p>
                                        <img src="/AdminLTE/dist/img/yzm.png" id="qrcode" alt="" width="180px"><br>
                                        <p style="margin-top: 15px"><b>秘钥:{{ $secret }}</b></p>
                                         <p>(手机不支持扫二维码，请选择输入密钥方式)</p>
                                         <p style="color: green">注意：此二维码以及密钥是获取谷歌验证码唯一标识，勿泄漏,如丢失请联系管理员</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>第三步</td>
                                    <td>填写下方信息开放谷歌验证</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="col-sm-12">
                                            <label class="col-sm-1">App Code:</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" style="width: 300px;" id="appCode" name="appCode" placeholder="请输入谷歌验证器上6位验证码"><br>
                                            </div>
                                            <div class="col-sm-8"></div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="col-sm-1">用户密码:</label>
                                            <div class="col-sm-3">
                                                <input type="password" class="form-control" style="width: 300px;" id="userPwd" name="userPwd" placeholder="请输入用户密码"><br>
                                            </div>
                                            <div class="col-sm-8"></div>
                                        </div>
                                        <div class="col-sm-12">
                                            <input type="hidden" name="secret" value="{{ $secret }}">
                                            <button type="button" onclick="saveGoogle($(this))" id="bangBtn" class="btn btn-primary">验证安全码</button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            @else
                                谷歌验证已设置
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
@section("scripts")
<script src="{{ asset('js/jquery.qrcode.min.js') }}"></script>
<script>
$(function () {
    var strcode = toUtf8('{!! $qrCodeUrl !!}');
    var qrcode = $('#qrcode').qrcode({ text: strcode });
    var canvas = qrcode.find('canvas').get(0);
    $('#qrcode').attr('src', canvas.toDataURL('image/jpg'));
    canvas.remove();
})
    
    function saveGoogle(_this) {
        _this.removeAttr('onclick');
        var appCode = $("input[name='appCode']").val();
        var userPwd = $("input[name='userPwd']").val();
        var secret  = $("input[name='secret']").val();
        $.ajax({
            type: 'post',
            url: "{{ route(strtolower($module).'.validator') }}",
            data: {'appCode': appCode, 'userPwd': userPwd,'secret':secret},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if(result.status)
                {
                    toastr.success(result.msg);
                    setInterval(function(){
                        window.location.reload();
                    },1000);
                }else{
                    toastr.error(result.msg);
                    _this.attr("onclick", "saveGoogle($(this))");
                }
            },
            error: function (XMLHttpRequest, textStatus) {
                _this.attr("onclick", "saveGoogle($(this))");
                toastr.error('通信失败');
            }
        })
    }

function toUtf8(str) {
    var out, i, len, c;
    out = "";
    len = str.length;
    for(i = 0; i < len; i++) {
        c = str.charCodeAt(i);
        if ((c >= 0x0001) && (c <= 0x007F)){
            out += str.charAt(i);
        } else if (c > 0x07FF) {
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
            out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        } else{
            out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        }
    }
    return out;
}
</script>
@endsection
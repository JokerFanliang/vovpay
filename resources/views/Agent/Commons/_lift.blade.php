<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- 侧边栏用户 -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->username }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> 欢迎登录</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">

            <li><a href="{{route('agent')}}"><i class="fa fa-circle-o text-aqua"></i><span>主页</span></a></li>
            <li><a href="{{route('agent.show')}}"><i class="fa fa-circle-o text-aqua"></i><span>账户信息</span></a></li>
            <li><a href="{{route('agent.user')}}"><i class="fa fa-circle-o text-aqua"></i><span>商户管理</span></a></li>
            <li><a href="{{route('agent.order')}}"><i class="fa fa-circle-o text-aqua"></i><span>交易管理</span></a></li>
            <li><a href="{{route('agent.clearing')}}"><i class="fa fa-circle-o  text-aqua"></i><span>结算申请</span></a></li>
            <li><a href="{{route('agent.bankCard')}}"><i class="fa fa-circle-o  text-aqua"></i><span>银行卡信息</span></a></li>
            <li><a href="{{ route('agent.api') }}"><i class="fa fa-circle-o text-aqua"></i><span>API管理</span></a></li>
            @if(env('ADD_ACCOUNT_TYPE') ==3)
                <li>
                    <a href="{{ route('agent.manageWithdraws') }}"><i class="fa fa-circle-o text-aqua"></i><span>结算管理</span></a>
                </li>
                <li class="treeview @if(stripos( url()->full(),'account' )) active  menu-open @endif ">
                    <a href="#">
                        <i class="fa fa-circle-o  text-aqua"></i><span>账号管理</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('agent.account',[0])}}"><i class="fa fa-circle-o"></i><span>微信账号</span></a></li>
                        <li><a href="{{route('agent.account',[1])}}"><i class="fa fa-circle-o"></i><span>支付宝账号</span></a></li>
                        <li><a href="{{ route('agent.account',[2]) }}"><i class="fa fa-circle-o"></i><span>云闪付</span></a></li>
                        <li><a href="{{ route('agent.accountBank',[0]) }}"><i class="fa fa-circle-o"></i><span>银行卡号</span></a></li>
                        <li><a href="{{ route('agent.accountBank',[1]) }}"><i class="fa fa-circle-o"></i><span>银行固码</span></a></li>
                    </ul>
                </li>
            @endif
              <li><a href="{{route('agent.validator')}}"><i class="fa fa-circle-o text-yellow"></i><span>安全设置</span></a></li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
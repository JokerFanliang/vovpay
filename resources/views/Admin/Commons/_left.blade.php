<!-- 左侧导航开始 -->
<aside class="main-sidebar">
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
        <!-- /.侧边栏用户 -->

        <!-- 左侧导航条 -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">主导航</li>
            <li>
                <a href="{{ route('admin') }}">
                    <i class="fa fa-tachometer"></i>
                    <span>后台首页</span>
                </a>
            </li>
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-paper-plane"></i>--}}
                    {{--<span>权限控制</span>--}}
                    {{--<span class="pull-right-container">--}}
                      {{--<i class="fa fa-angle-left pull-right"></i>--}}
                    {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="{{ route('rules.index') }}"><i class="fa fa-circle-o"></i>菜单管理</a></li>--}}
                    {{--<li><a href="{{ route('roles.index') }}"><i class="fa fa-circle-o"></i>角色管理</a></li>--}}
                    {{--<li><a href="{{ route('admins.index') }}"><i class="fa fa-circle-o"></i>管理员管理</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li>
                <a href="{{ route('users.index',['agent']) }}">
                    <i class="fa fa-user"></i>
                    <span>代理管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index',['user']) }}">
                    <i class="fa fa-user"></i>
                    <span>商户管理</span>
                </a>
            </li>
            @if(env('ADD_ACCOUNT_TYPE') == 4)
            <li>
                <a href="{{ route('users.index',['court']) }}">
                    <i class="fa fa-user"></i>
                    <span>场外商户</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('channels.index') }}">
                    <i class="fa fa-exchange"></i>
                    <span>通道管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('channelPayments.index') }}">
                    <i class="fa fa-credit-card"></i>
                    <span>支付方式</span>
                </a>
            </li>
            <li>
                <a href="{{ route('orders.index') }}">
                    <i class="fa fa-reorder"></i>
                    <span>订单管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('withdraws.index') }}">
                    <i class="fa fa-reorder"></i>
                    <span>结算管理</span>
                </a>
            </li>
            @if(env('ADD_ACCOUNT_TYPE') == 2)
            <li class="treeview @if(stripos( url()->full(),'account' )) active  menu-open @endif ">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>账号管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.account',[0]) }}"><i class="fa fa-circle-o"></i>微信账号</a></li>
                    <li><a href="{{ route('admin.account',[1]) }}"><i class="fa fa-circle-o"></i>支付宝账号</a></li>
                    <li><a href="{{ route('admin.account',[2]) }}"><i class="fa fa-circle-o"></i>云闪付</a></li>
                    <li><a href="{{ route('admin.accountBank',[0]) }}"><i class="fa fa-circle-o"></i><span>银行卡号</span></a></li>
                    <li><a href="{{ route('admin.accountBank',[1]) }}"><i class="fa fa-circle-o"></i><span>银行固码</span></a></li>
                </ul>
            </li>
            @else
                <li>
                    <a href="{{ route('account.all') }}">
                        <i class="fa fa-reorder"></i>
                        <span>所有账号</span>
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ route('system.index') }}">
                    <i class="fa fa-cog"></i>
                    <span>系统设置</span>
                </a>
            </li>
            <li><a href="{{route('admin.validator')}}"><i class="fa fa-circle-o text-yellow"></i><span>安全设置</span></a></li>
        </ul>
    </section>
</aside>
<!-- 左侧导航结束 -->
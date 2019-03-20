<?php

namespace App\Http\Admin\Controllers;

use App\Services\OrderDayCountService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\AdminsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    protected $orderDayCountService;
    protected $adminsService;
    public function __construct(OrderDayCountService $orderDayCountService, AdminsService $adminsService)
    {
        $this->orderDayCountService = $orderDayCountService;
        $this->adminsService  = $adminsService;
        if(!Cache::has('systems')){
            Cache::rememberForever('systems', function () {
                $system = DB::table('systems')->select('name','value')->get();
                $system_array = [];
                foreach ($system as $k=>$v){
                    $system_array[$v->name] = $v;
                }
                return $system_array;
            });
        }
    }

    public function index()
    {
        $title = '主页';
        $description = '今日统计数据有10分钟延迟,想看实时的请使用订单查询';
        $order_day_count = json_encode(convert_arr_key($this->orderDayCountService->getSevenDaysCount(),'tm'));
        $sys_day_count   = $this->orderDayCountService->findSysDayCount();
        return view('Admin.Index.index', compact('title', 'description','order_day_count','sys_day_count'));
    }

        
    //修改密码
    public function editpwd(Request $request)
    {
        $data = $request->input();
        $id = Auth::user()->id;
        $result = $this->adminsService->updatePassword($id, $data);

        if ($result) {
            return ajaxSuccess('密码修改成功！');
        } else {
            return ajaxError('原密码错误，修改失败！');
        }
    }
}

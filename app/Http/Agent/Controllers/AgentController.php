<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/27
 * Time: 14:53
 */

namespace App\Http\Agent\Controllers;


use App\Http\Agent\Controllers\Controller;

class AgentController extends Controller
{

    public function index()
    {
        return view('Agent.Agents.info');
    }


}
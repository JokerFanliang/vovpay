<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomServiceException;

class ManageWithdrawRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->group_type == 2) {

            if (env('ADD_ACCOUNT_TYPE') != 3) {
                throw new CustomServiceException('非法操作!');
            }

            $agent_id = Auth::user()->id;
            $withdrawid = $this->id;
            $withdrawInfo = DB::table('Withdraws')->whereId($withdrawid)->whereAgentId($agent_id)->first();

            if (!$withdrawInfo) {
                throw new CustomServiceException('非法操作!');
            }
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'id' => 'required|alpha_num',
            'type' => 'required|in:1,2',
            'status' => 'required|in:1,2,3,4',
            'comment' => 'required_if:type,1|max:191',
            'channelCode' => 'required_if:type,2|alpha_num',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '非法操作',
            'id.alpha_num' => '非法操作',
            'type.required' => '非法操作',
            'type.in' => '非法操作',
            'status.required' => '非法操作',
            'status.in' => '非法操作',
            'comment.required_if' => '备注不能为空',
            'comment.max' => '备注过长',
            'channelCode.required_if' => '必须选择代付通道',
            'channelCode.alpha_num' => '非法操作',
        ];
    }
}

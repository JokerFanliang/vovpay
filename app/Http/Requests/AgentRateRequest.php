<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomServiceException;

class AgentRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->group_type == 2) {

            $agent_id = Auth::user()->id;
            $userId = $this->id;

            $userInfo = DB::table('users')->whereId($userId)->where('parentId',$agent_id)->first();

            if (!$userInfo) {
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
            'id' => 'nullable|numeric',
            'payId' => 'nullable|numeric',
            'channelId' => 'nullable|numeric',
            'rate' =>'nullable|numeric',
            'status' => 'nullable|in:1,2,true,false',
        ];
    }

    public function messages()
    {
        return [
            'id.numeric' => '非法操作!',
            'payId.numeric' => '非法操作!',
            'channelId.numeric' => '非法操作!',
            'rate.numeric' => '非法操作!',
            'status.in' => '非法操作!',

        ];
    }
}

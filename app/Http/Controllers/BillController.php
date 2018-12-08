<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workorder;
use App\User;
use App\Bill;
use Illuminate\Support\Facades\Auth;
use Validator;

/**
 * @className BillController
 * @namespace App\Http\Controllers
 * @description 缴费管理模块控制器
 */
class BillController extends Controller
{
    /**
     * 查看所有的反馈与投诉列表
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Validator::make($request->all(), [
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1'
        ])->validate();

        $user = Auth::user();
        if ($user->role_id == 3) {
            return $user->bill()->paginate($request->per_page);
        }
        return Bill::with('user')->paginate($request->per_page);        
    }

    /**
     * 缴费
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'integer|min:0|max:2',
            'pay_type' => 'integer|min:0|max:1',
            'cost' => 'numeric'
        ])->validate();

        $user = Auth::user();
        if ($request->has('user_id')) {
            $this->authorize('isAdmin', Auth::user());
            $user = User::find($request->user_id);
        }

        DB::beginTransaction();
        try {
            $bill = new Bill([
                'type' => $request->type,
                'pay_type' => $request->pay_type,
                'cost' => $request->cost;
            ]);
            $user->bill()->save($bill);
            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => '缴费成功'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 200,
                'message' => '后台繁忙，金额已退回，请重试'
            ]);
        }
    }

    /**
     * 查看缴费清单
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $bill = Bill::with('user')->find($id);
        if ($bill->user_id != Auth::id()) {
            $this->authorize('isAdmin', Auth::user());
        }
        return $bill;
    }
}

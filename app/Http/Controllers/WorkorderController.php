<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workorder;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

/**
 * @className WorkorderController
 * @namespace App\Http\Controllers
 * @description 反馈与投诉模块控制器
 */
class WorkorderController extends Controller
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
            return $user->workorder()->withTrashed()->paginate($request->per_page);
        }

        return Workorder::withTrashed()->with('user')->paginate($request->per_page);
    }

    /**
     * 添加反馈或投诉
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'integer|min:0|max:1'
        ])->validate();

        $order = new Workorder([
            'type' => $request->type,
            'content' => $request->content
        ]);
        $user = User::find(Auth::id());
        $user->workorder()->save($order);
        return response()->json([
            'code' => 200,
            'message' => '反馈成功'
        ]);
    }

    /**
     * 查看某个反馈
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $order = Workorder::withTrashed()->with('user')->find($id);
        if ($order->user_id != Auth::id()) {
            $this->authorize('isAdmin', Auth::user());
        }
        return $order;
    }

    /**
     * 删除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apartment = Workorder::find($id);
        $apartment->delete();
        return response()->json([
            'code' => 200,
            'message' => '删除成功'
        ]);
    }
}

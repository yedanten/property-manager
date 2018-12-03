<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * @className UserController
 * @namespace App\Http\Controllers
 * @description 用户模块控制器
 */
class UserController extends Controller
{
    /**
     * @description 返回当前登录的用户信息
     * @param  Request $request [description]
     * @return App\User
     */
    public function current(Request $request)
    {
        return Auth::user();
    }

    /**
     * @description 添加用户
     * @param  Request $request [description]
     * @return json
     */
    public function add(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'bail|required|unique:users|email',
            'name' => 'bail|required|max:255',
            'password' => 'bail|required|min:6',
            'role' => [
                'bail',
                'required',
                Rule::in(['管理员', '操作员', '业主'])
            ]
        ])->validate();

        $role = Role::where('name', $request->role)->first();

        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;

        $role->user()->save($user);
        return response()->json([
            'code' => 200,
            'message' => '添加成功'
        ]);
    }

    /**
     * @description 用户登录
     * @param  Request $request [description]
     * @return json accessToken
     */
    public function login(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'bail|required|exists:users|email',
            'password' => 'bail|required'
        ])->validate();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken($user->email);
            return response()->json([
                'code' => 200,
                'message' => '登录成功',
                'accessToken' => $token->accessToken
            ]); 
        } else {
            return response()->json([
                'code' => 422,
                'message' => '账号或密码错误'
            ]);
        }
    }

    /**
     * @description 重置密码
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function resetPassword(Request $request)
    {
        Validator::make($request->all(), [
            'password' => 'bail|required|min:6'
        ])->validate();

        $user = Auth::user();
        $user->password = $request->password;
        $user->save();
        return response()->json([
            'code' => 200,
            'message' => '修改成功'
        ]);
    }
}

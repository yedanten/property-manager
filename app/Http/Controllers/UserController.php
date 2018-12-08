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
        $user = Auth::user();
        $user->load('role', 'apartment', 'bill', 'workorder');
        return $user;
    }

    /**
     * @description 添加用户
     * @param  Request $request [description]
     * @return json
     */
    public function add(Request $request)
    {
        $this->authorize('isAdmin', Auth::user());

        Validator::make($request->all(), [
            'email' => 'bail|required|unique:users|email',
            'name' => 'bail|required|max:255',
            'password' => 'bail|required|min:6',
            'role' => 'bail|required|exists:roles,name'
        ])->validate();

        $role = Role::where('name', $request->role)->first();

        if ($role->id !== 3) {
            $this->authorize('isSuperAdmin', Auth::user());
        }

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

        if ($request->has('id')) {
            $this->authorize('isAdmin', Auth::user());
            $user = User::find($request->id);
            if ($user->role_id != 3) {
                $this->authorize('isSuperAdmin', Auth::user());
            }
        } else {
            $user = Auth::user();
        }

        $user->password = $request->password;
        $user->save();
        return response()->json([
            'code' => 200,
            'message' => '修改成功'
        ]);
    }

    /**
     * @description 查找用户
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function find(Request $request)
    {
        $this->authorize('isAdmin', Auth::user());

        Validator::make($request->all(), [
            'email' => 'email',
            'name' => 'string',
            'role' => [
                Rule::in(['管理员', '操作员', '业主'])
            ]
        ])->validate();

        if ($request->has('email')) {
            $user = User::with('role')->where('email', $request->email)->get();
            return $user;
        }


        $query = [];
        if ($request->has('role')) {
            $role_id = Role::where('name', $request->role)->pluck('id');
            array_push($query, ['role_id', $role_id]);
        }

        if ($request->has('name')) {
            array_push($query, ['name', $request->name]);
        } 

        $user = User::with('role')->where($query)->get();
        return $user;
        
    }
}

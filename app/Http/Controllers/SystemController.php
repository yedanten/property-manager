<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @className BillController
 * @namespace App\Http\Controllers
 * @description 系统管理模块控制器
 */
class SystemController extends Controller
{
    /**
     * Display a listing of the config resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('isSuperAdmin', Auth::user());

        $config = [
            'db_host' => env('DB_HOST'),
            'db_port' => env('DB_PORT'),
            'db_database' => env('DB_DATABASE'),
            'db_username' => env('DB_USERNAME'),
            'db_password' => env('DB_PASSWORD'),
            'community_name' => env('COMMUNITY_NAME'),
            'system_state' => env('SYSTEM_STATE')
        ];
        return $config;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('isSuperAdmin', Auth::user());

        Validator::make($request->all(), [
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' =>'required',
            'db_password' => 'required',
            'community_name' => 'required',
            'system_state' => [
                'required',
                Rule::in(['on', 'off'])
            ]
        ])->validate();

        $config = [
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password,
            'COMMUNITY_NAME' => $request->community_name,
            'SYSTEM_STATE' => $request->system_state
        ];

        modifyEnv($config);

        return response()->json([
            'code' => 200,
            'message' => '修改成功'
        ]);
    }

    /**
     * backup ddatabse data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function backupdb(Request $request)
    {
        $this->authorize('isSuperAdmin', Auth::user());
        return response()->streamDownload(function () {
            echo shell_exec('mysqldump -uroot -proot --databases property');
        }, 'backup.sql');
    }
}

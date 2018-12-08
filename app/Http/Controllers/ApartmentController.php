<?php

namespace App\Http\Controllers;

use Validator;
use App\Apartment;
use Illuminate\Http\Request;

/**
 * @className ApartmentController
 * @namespace App\Http\Controllers
 * @description 楼宇模块控制器
 */
class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Validator::make($request->all(), [
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
            'hasuser' => 'boolean'
        ])->validate();

        $query = [];
        if ($request->has('name')) {
            array_push($query, ['name', $request->name]);
        }
        if ($request->has('unit')) {
            array_push($query, $request->unit);
        }
        if ($request->has('doorplate')) {
            array_push($query, $request->doorplate);
        }

        $list = Apartment::with('user')->where($query);

        if ($request->has('hasuser')) {
            if ($request->hasuser) {
                $list->whereNotNull('user_id');
            }
            $list->whereNull('user_id');
        }
        return $list->paginate($request->per_page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isSuperAdmin', Auth::user());

        Validator::make($request->all(), [
            'name' => 'bail|required',
            'unit' => 'bail|integer|min:1',
            'doorplate' => 'bail|integer'
        ])->validate();

        $apartment = new Apartment;
        $apartment->name = $request->name;
        $apartment->unit = $request->unit;
        $apartment->doorplate = $request->doorplate;

        $apartment->save();
        return response()->json([
            'code' => 200,
            'message' => '添加成功'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return Apartment::with('user')->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('isAdmin', Auth::user());

        Validator::make($request->all(), [
            'user_id' => 'exists:user,id',
            'name' => 'required',
            'unit' => 'required',
            'doorplate' => 'required'
        ])->validate();

        $apartment = Apartment::find($id);
        if ($request->has('name') || $request->has('unit') || $request->has('doorplate')) {
            $this->authorize('isSuperAdmin', Auth::user());
        }
        $apartment->name = $request->name;
        $apartment->unit = $request->unit;
        $apartment->doorplate = $apartment->doorplate;
        if ($request->has('user_id')) {
            $apartment->user_id = $request->user_id;
        }
        $apartment->save();
        return response()->json([
            'code' => 200,
            'message' => '修改成功'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isSuperAdmin', Auth::user());
        $apartment = Apartment::find($id);
        $apartment->delete();
        return response()->json([
            'code' => 200,
            'message' => '删除成功'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Transformer\AdminUserTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdminUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function getIndex(Request $request){
        $users = AdminUser::all();
        return DataTables::of($users)
            ->setTransformer(new AdminUserTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.adminuser.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.adminuser.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|max:100',
            'last_name'         => 'required|max:100',
            'email'             => 'required|regex:/^\S*$/u|unique:users|max:50',
            'role_id'           => 'required',
            'password'          => 'required'
        ],[
            'email.unique'      => 'ID Login Akses telah terdaftar!',
            'email.regex'       => 'ID Login Akses harus tanpa spasi!'
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Create Admin
        $user = Auth::guard('admin')->user();
        if($request->input('is_super_admin') == 'on'){
            $superAdmin = 1;
        }
        else{
            $superAdmin = 0;
        }
        $adminUser = AdminUser::create([
            'first_name'    => $request->input('first_name'),
            'last_name'     => $request->input('last_name'),
            'email'         => $request->input('email'),
            'role_id'       => $request->input('role'),
            'password'      => $request->input('password'),
            'status_id'     => $request->input('status'),
            'is_super_admin'=> $superAdmin,
            'created_by'    => $user->id,
            'created_at'    => Carbon::now('Asia/Jakarta')
        ]);

        Session::flash('success', 'Success Creating new Admin User!');
        return redirect()->route('admin.admin-users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $adminUser = AdminUser::find($id);
        return view('admin.adminuser.edit', compact('adminUser'));
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
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|max:100',
            'last_name'         => 'required|max:100',
            'email'             => 'required|regex:/^\S*$/u|unique:admin_users|max:50',
            'role'              => 'required',
            'password'          => 'required'
        ],[
            'email.unique'      => 'ID Login Akses telah terdaftar!',
            'email.regex'       => 'ID Login Akses harus tanpa spasi!'
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Create Admin
        $user = Auth::guard('admin')->user();
        if($request->input('is_super_admin') == 'on'){
            $superAdmin = 1;
        }
        else{
            $superAdmin = 0;
        }

        $adminUser = AdminUser::find($id);
        $adminUser->email = $request->input('email');
        $adminUser->first_name = $request->input('first_name');
        $adminUser->last_name = $request->input('last_name');
        $adminUser->is_super_admin = $superAdmin;
        $adminUser->updated_at = Carbon::now('Asia/Jakarta');
        $adminUser->status_id = $request->input('status');
        $adminUser->save();

        Session::flash('success', 'Success Updating Admin User!');
        return redirect()->route('admin.admin-users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        //
        try {
            //Belum melakukan pengecekan hubungan antar Table
            $adminUserId = $request->input('id');
            $adminUser = AdminUser::find($adminUserId);
            $adminUser->delete();

            Session::flash('success', 'Success Deleting Admin User ' . $adminUser->email . ' - ' . $adminUser->first_name . ' ' . $adminUser->last_name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorEnabledResponse;
use Laravel\Sanctum\PersonalAccessToken;


class User extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* if (! Gate::allows('isAdmin') ) {
            abort(403);
        } */

        $users = ModelsUser::all();

        $users = DB::table('users')
            ->join('roles', 'users.role', '=', 'roles.slug')
            //->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'roles.name as role')
            ->get();

        if ($users) {
            //return json_encode($users);
            return json_encode($users);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = ModelsUser::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        
        
        //$user->save();
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = ModelsUser::where('users.id', "$id")
            ->join('roles', 'users.role', '=', 'roles.slug')
            ->select('users.*', 'roles.name as role_name')
            ->get();

        if ($user) {
            return json_encode($user);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showmyinfo(Request $request)
    {
        
        $user =  auth('sanctum')->user();
        if ($user) {
            return json_encode($user);
        }
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
        $user = ModelsUser::find($id);

        $user->name = $request->name;
        $user->username = $request->username;
        //$user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;

        $user->save();
        
        return $user;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMyInfo(Request $request)
    {
        $user =  auth('sanctum')->user();
        $user = ModelsUser::find($user->id);

        $user->name = $request->name;
        $user->username = $request->username;
        //$user->email = $request->email;
        /* $user->password = Hash::make($request->password); */
        $user->role = $request->role;

        $user->save();
        
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = ModelsUser::find($id);

        $user->delete();

        return $user;
    }

    public function login(Request $request)
    {

        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = ModelsUser::where('email', "=", $request->email)->first();
            $user->accessToken = $user->createToken($request->email)->plainTextToken;
            return response()->json($user, 200);
        }
        /* if( $user ){

            if( Hash::check($request->password, $user->password) ){
                $user->accessToken = $user->createToken("custom name")->plainTextToken;
                return response()->json($user, 200); 
            }
            return response()->json("password is incorrect", 401);
        } */
        
        return response()->json("Email or password is not correct", 401);
    }
    /* public function two_factor_auth (Request $request){
        //redirect('/user/two-factor-authentication');
        
    } */

    /* public function two_factor_store(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user());

        return app(TwoFactorEnabledResponse::class);
    } */
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',

        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {

        return response()->json($request->user());
    }
    public function users(Request $request)
    {
        if ($request->has('name') && $request->has('email')) {
            $test = DB::table('users')
            ->where([
                ['name','LIKE','%'. $request->name.'%'],
                ['email','LIKE','%'. $request->email.'%']
            ])
            ->get();
            if(!$test->isEmpty()){
                return response()->json( $test );
            }
            return response()->json([
                'message' => 'There is no user with this Credentials'
            ]);

        }
        if ($request->has('name')) {
            $test = DB::table('users')
            ->where([
                ['name','LIKE','%'. $request->name.'%'],
            ])
            ->get();
            if(!$test->isEmpty()){
                return response()->json( $test );
            }
            return response()->json([
                'message' => 'There is no user with this Credentials'
            ]);

        }
          if ($request->has('email')) {
            $test = DB::table('users')
            ->where([
                ['email','LIKE','%'. $request->email.'%'],
            ])
            ->get();
            if(!$test->isEmpty()){
                return response()->json( $test );
            }
            return response()->json([
                'message' => 'There is no user with this Credentials'
            ]);

        }


        return response()->json( DB::table('users')->paginate(2));
    }
    public function Resource(Request $request)
    {
     return new UserResource(User::findOrFail($request->user()->id));
    }
}

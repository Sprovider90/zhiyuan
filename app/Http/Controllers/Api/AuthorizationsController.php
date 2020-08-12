<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Jobs\LoginLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthorizationsController extends Controller
{
   public function store(AuthorizationRequest $request)
    {
        
        $users = DB::table('users')->where('name', $request->username)
                    ->orWhere('phone', $request->username)->first();
        
        if(isset($users)&&$users->id){
            if(!Hash::check($request->input('password'), $users->password)){ 

                $queuedata=[];
                $queuedata["name"]=$request->username;
                $queuedata["ip"]=$request->getClientIp();
                $queuedata["type"]=1;
                dispatch(new LoginLog($queuedata));

                throw new AuthenticationException('用户名或密码错误');
            }else{
                $queuedata=[];
                $queuedata["name"]=$request->username;
                $queuedata["ip"]=$request->getClientIp();
                $queuedata["type"]=2;

                dispatch(new LoginLog($queuedata));
            }
        }else{
            throw new AuthenticationException('用户不存在');
        }

        $credentials['name'] = $users->name;
        $credentials['password'] = $request->password;
        
        $token = \Auth::guard('api')->attempt($credentials);

        return $this->respondWithToken($token)->setStatusCode(201);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
    public function update()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();
        return response(null, 204);
    }
}

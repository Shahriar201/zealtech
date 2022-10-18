<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    public function signup(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password),
        ]);

        return $this->set_response($user, 200, 'success', ['User Created Successfully']);
    }

    public function login(Request $request) {

        // \Log::info("message".json_encode(($request->all())));

        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        if($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $credentials =  $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->set_response(null, 400, 'failed', ['Credentials does not match']);
        }

        if(Auth::attempt($credentials)) {
            $user = $request->user();
            $tokenRes = $user->createToken('Create Token');
            $token = $tokenRes->token;

            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }

            $tokenData = [
                'user' => [
                    'access_token'  => $tokenRes->accessToken,
                    'token_type'    => 'Bearer',
                    'expires_at'    => Carbon::parse($token->expires_at)->toDateTimeString(),
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'userId'        => $user->id,
                ]
            ];

            return $this->set_response($tokenData, 200, 'success', ['Logged in!']);
        }
    }

    public function me(Request $request) {
        $tokenData = [
            'user' => [
                'access_token'              => $request->bearerToken(),
                'token_type'                => 'Bearer',
                'name'                      => Auth::user()->name,
                'email'                     => Auth::user()->email,
                'userId'                    => Auth::user()->id,
            ]
        ];

        return $this->set_response($tokenData, 200, 'success', ['Loggedin User']);
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return $this->set_response(null, 200, 'success', ['Successfully logout']);
    }
}

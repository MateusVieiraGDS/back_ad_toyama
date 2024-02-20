<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\ValidCodeRequest;
use App\Mail\ForgoutPasswordEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $tKey = $request->throttleKey();
        if (RateLimiter::tooManyAttempts($tKey, $perMinute = 5)) {
            $seconds = RateLimiter::availableIn($tKey);
         
            return response()->json(
                [
                    'errors'=> ['awaitAttempt' => $seconds]
                ]
                , 401
            );
        }

        if (! $token = auth()->attempt($credentials)) {
            RateLimiter::hit($tKey);
            return response()->json(
                [
                    'errors'=> ['password' => 'Senha incorreta!']
                ]
                , 401
            );
        }
        RateLimiter::clear($tKey);
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function SendEmailForgoutPassword(Request $request){        
        $email = $request->validate([
            'email' => 'required|email'
        ])['email'];
        
        
        $tKey = md5("{$email}SendEmailForgoutPassword");        
        
        if (RateLimiter::tooManyAttempts($tKey, $perMinute = 5)) {
            $seconds = RateLimiter::availableIn($tKey);         
            return response()->json(
                [
                    'errors'=> ['awaitAttempt' => $seconds]
                ]
                , 401
            );
        }
        
        $user = User::where('email', $email)->first();
        
        if(!$user){            
            return response()->json(
                [
                    'errors'=> ['email' => "Non-existent email."]
                ]
                , 401
            );
        }

        RateLimiter::hit($tKey);
        //RateLimiter::clear($tKey);
        $code = $user->GenerateForgoutPasswordCode();
        $username = $user->name;
        
        Mail::to('mateusvieirap010@gmail.com')->send(new ForgoutPasswordEmail($username, $code));
        return response()->json([
            'message' => 'Successfully sended forgout password email.', 
            "remaing"=> RateLimiter::remaining('send-message:'.$user->id, 1),
            'tkey' => $tKey
        ], 201);
    }

    public function ValidForgoutCode(ValidCodeRequest $request){
        $data = $request->validated();

        $tKey = md5("{$data['email']}ValidForgoutCode");
        if (RateLimiter::tooManyAttempts($tKey, $perMinute = 3)) {
            $seconds = RateLimiter::availableIn($tKey);         
            return response()->json(
                [
                    'errors'=> ['awaitAttempt' => $seconds]
                ]
                , 401
            );
        }

        $user = User::where('email', $data['email'])
            ->where('forgout_password_code', $data['code'])
            ->where('forgout_password_expires', '>', now())
            ->first();

        if($user){
            RateLimiter::clear($tKey);
            $password_token = Crypt::encrypt("{$user->GenerateForgoutPasswordCode()}|{$user->id}");
            return response()->json(['password_token' => $password_token]);      
        }

        RateLimiter::hit($tKey);
        return response()->json(
            [
                'errors'=> ['code' => 'invalid code.']
            ]
            , 401
        );
    }
    
    public function ResetPassword(UpdatePasswordRequest $request){
        $data = $request->validated();

        $user = User::find($data['user_id']);

        $user->update([
            'password' => Hash::make($data['new_password']),
            'forgout_password_code' => null,
            'forgout_password_expires' => null,
        ]);

        return response()->json(['message' => 'Successfully updated password.']);
    }
}

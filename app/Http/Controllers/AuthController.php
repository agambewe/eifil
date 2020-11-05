<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller {

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'notLogin']]);
        // $this->middleware('auth:api');
    }

    public function notLogin(Request $request){
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = User::where('email',$request->input('email'))->first();
        if (is_null($data)){
            return response()->json(['errors' => "Wrong Email or Password!"], 422);
        }else{
            if(!Hash::check($request->input('password'), $data->password)){
                return response()->json(['errors' => "Wrong Email or Password!"], 422);
            }
        }
        
        if(!$token = auth()->attempt($validator->validated())) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        $res['message'] = "Data has been successfully registered!";
        $res['user'] = $user;
        return response($res);
        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $user
        // ], 201);
    }

    public function update(Request $request) {
        $email = $request->input('email');
        if(is_null($email)){
            return response()->json(['errors' => "Wrong email!"], 422);
        }
        $data = User::where('email',$email)->first();

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => ['required','string','email','max:100', Rule::unique('users')->ignore($data)],
            'password' => 'required|string',
            'password_old' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $name = $request->input('name');
        $password = $request->input('password');
        $password_old = $request->input('password_old');
        $data->name = $name;
        $data->email = $email;

        if($password_old!=NULL){
            if(Hash::check($request->input('password_old'), $data->password)){
                $data->password = bcrypt($password);
            }else{
                return response()->json(['errors' => "Old password doesn't match!"], 422);
            }
        }

        if($data->save()){
            $res['message'] = "Data has been successfully updated!";
            $res['user'] = $data;
            return response($res);
        }else{
            $res = "Failed!";
            return response($res);
        }
        
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}
<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function Login(Request $request) {
        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    public function Register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ]);
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function Logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('Logged out successfully', 200);
    }

    public function ChangePassword(Request $request){

        try{
            $request->validate([
                'new_password' => 'required|string|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
            ]);
            $user = Auth::user();

            if(Hash::check($request->get('new_password'), bcrypt($request->get('old_password')) )){
                return response()->json('New password must be different.',409);
            } else {

                if (Hash::check($request->get('old_password'), Auth::user()->password)) {
                    $user->password = bcrypt($request->get('new_password'));
                    $user->updated_at = $user->freshTimestamp();
                    $user->save();
                    return response()->json('Password updated successfully', '200');
                } else {
                    return response()->json('Old password does not match.', '409');
                }
            }
        }  catch (Exception $ex) {
            if ($ex->getCode() === 400) {
                return response()->json('Invalid Request. Please provide all the fields.', $ex->getCode());
            } else if ($ex->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $ex->getCode());
            } else if ($ex->getCode() === 409) {
                return response()->json('Provided password is incorrect. Please try again', $ex->getCode());
            }
            return response()->json('Something went wrong on the server.', $ex->getCode());
        }
    }

    public function getUsersList(){
        $userData = User::where('id', '!=', auth()->id())->get();
        return $userData;
    }

    public function DeleteUser(Request $request){
        $id = $request->user_id;
        if(isset($id) && $id != ""){
            $user = User::where('id', '=', $id)->first();
            if($user != null) {
                User::where('id', '=', $id)->delete();
                return response()->json("User with ID: ".$id." deleted successfully", '200');
            } else {
                return response()->json("User with ID: ".$id." not found", '404');
            }
        } else {
            return response()->json("User Id needed", 401);
        }
        
        // $user = User::where('id', '=', $request->user_id)->delete();
        
    }
}

<?php

namespace app\Http\Controllers\API;



use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    public function secure()
    {
        $email = request()->get('email');
        $pass = request()->get('password');
        $app_token = request()->header('AppToken');
        if ($app_token != app_token()) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'Token Denied !',
            ]);
        } else {
            $validator = Validator::make(request()->all(), [
                'email' => 'required|max:255',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $response['success'] = false;
                $response['status'] = 401;
                $response['message'] = $validator->errors()->first();
            } else {
                if (!Auth::attempt(['email' => $email, 'password' => $pass])) {
                    $response['success'] = false;
                    $response['status'] = 401;
                    $response['message'] = 'Email atau password salah.';
                } else {
                    $row = User::find(Auth::user()->id);
                    if ($row->api_token == '') {
                        $token = Str::random(60);
                        User::where('email', $email)->update(['api_token' => $token]);
                    } else {
                        $token = $row->api_token;
                    }
                    $response['success'] = true;
                    $response['message'] = 'Berhasil Login';
                    $response['status'] = 200;
                    $response['first_name'] = $row->first_name;
                    $response['last_name'] = $row->last_name;
                    $response['api_token'] = $token;
                }
            }
        }
        return response()->json($response);
    }
}

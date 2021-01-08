<?php

namespace app\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiBaseController extends Controller
{
    protected $account_id;
    protected $user;

    public function __construct()
    {
        $api_token = request()->get('api_token');
        if (!empty($api_token)) {
            if (User::where('api_token', $api_token)->exists()) {
                $this->user = Auth::guard('api')->user();
                $this->account_id = $this->user->account_id;
            }
        }
    }
}

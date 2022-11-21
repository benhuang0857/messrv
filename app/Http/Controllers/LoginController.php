<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\LineService;
use Auth;

class LoginController extends Controller
{
    protected $lineService;

    public function __construct(LineService $lineService)
    {
        $this->lineService = $lineService;
    }

    public function pageLine()
    {
        $url = $this->lineService->getLoginBaseUrl();
        return view('line')->with('url', $url);
    }

    public function lineLoginCallBack(Request $request)
    {
        try {
            $error = $request->input('error', false);
            if ($error) {
                throw new Exception($request->all());
            }
            $code = $request->input('code', '');
            $response = $this->lineService->getLineToken($code);
            $user_profile = $this->lineService->getUserProfile($response['access_token']);

            $user = User::where('line_id', $user_profile['userId'])->first();
            if ($user == NULL) {
                User::create([
                    'name' => $user_profile['displayName'],
                    'line_id' => $user_profile['userId'],
                ]);
                $theUser = User::where('line_id', $user_profile['userId'])->first();
                Auth::login($theUser);
                return redirect('/');
            }
            else {
                Auth::login($user);
                return redirect('/home');
            }            

        } catch (Exception $ex) {
            Log::error($ex);
        }
    }

    public function loginApi(Request $request)
    {
        return json_encode(User::where('email', $request->email)->first());
    }
}
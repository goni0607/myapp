<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\SessionsController as ParentController;

class SessionsController extends ParentController
{
    protected function respondCreated($token)
    {
    		return response()->json([
    			'token' => $token,
    		], 201, [], JSON_PRETTY_PRINT);
    }


    protected function respondSocialUser()
    {
        return response()->json([
            'error' => 'social_user',
        ], 401, [], JSON_PRETTY_PRINT);
    }


    protected function respondLoginFailed()
    {
        return response()->json([
            'error' => 'invalid_credentials',
        ], 401, [], JSON_PRETTY_PRINT);
    }


    protected function respondNotConfirmed()
    {
        return response()->json([
            'error' => 'not_confirmed',
        ], 401, [], JSON_PRETTY_PRINT);
    }
}

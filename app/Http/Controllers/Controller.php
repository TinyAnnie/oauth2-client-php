<?php

namespace App\Http\Controllers;

use App\Http\Clients\Oauth2Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getAuthentication(): JsonResponse {
        $oauth2Client = new Oauth2Client();
        return response()->json($oauth2Client->getConfig()['headers']);
    }
}

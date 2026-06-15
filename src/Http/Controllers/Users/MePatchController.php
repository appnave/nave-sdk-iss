<?php

namespace BildVitta\Hub\Http\Controllers\Users;

use BildVitta\Hub\Http\Requests\MePatchRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class MePatchController extends UsersController
{
    public function __invoke(MePatchRequest $request)
    {
        $data = [];
        if ($request->filled('companies')) {
            $data['companies'] = $request->input('companies');
        }
        if ($request->filled('current_main_company')) {
            $data['current_main_company'] = $request->input('current_main_company');
        }

        $token_uri = Config::get('hub.base_uri').Config::get('hub.prefix').Config::get('hub.oauth.userinfo_uri');
        $response = Http::acceptJson()
            ->withToken($request->bearerToken())
            ->patch(
                $token_uri,
                $data
            );

        return new Response($response->body(), $response->status());
    }
}

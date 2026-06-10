<?php

namespace BildVitta\Hub\Http\Controllers\Users\Notifications;

use BildVitta\Hub\Http\Requests\NotificationRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class PatchController extends NotificationsController
{
    public function __invoke(NotificationRequest $request)
    {
        $params = [
            'project' => Config::get('app.slug', ''),
        ];
        $token_uri = Config::get('hub.base_uri').Config::get('hub.prefix').Config::get('hub.oauth.notifications_uri').'?'.http_build_query($params);
        $response = Http::acceptJson()
            ->withToken($bearerToken)
            ->patch(
                $token_uri,
                [
                    'mark_all_as_read' => $request->get('mark_all_as_read'),
                ]
            );

        return new Response($response, $response->status());
    }
}

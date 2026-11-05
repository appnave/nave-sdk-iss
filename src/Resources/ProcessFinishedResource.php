<?php

namespace BildVitta\Hub\Resources;

use BildVitta\Hub\Hub;
use Illuminate\Http\Client\Response;

class ProcessFinishedResource extends Resource
{
    private const PREFIX = 'programmatic/process-finished';

    private Hub $hub;

    public function __construct(Hub $hub)
    {
        $this->hub = $hub;
    }

    public function send(
        string $userUuid,
        string $identifier,
        bool $isSuccessful = true,
    ): Response {
        $args = [
            'user_uuid' => $userUuid,
            'identifier' => $identifier,
            'is_successful' => $isSuccessful,
        ];
        $endpoint = self::PREFIX;

        return $this->hub->request->post($endpoint, $args)
            ->throw();
    }
}

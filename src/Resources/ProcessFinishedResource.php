<?php

namespace BildVitta\Hub\Resources;

use BildVitta\Hub\Hub;
use Illuminate\Http\Client\Response;

class ProcessFinishedResource extends Resource
{
    private const ENDPOINT = 'programmatic/process-finished';

    private Hub $hub;

    public function __construct(Hub $hub)
    {
        $this->hub = $hub;
    }

    public function send(
        array $userUuids,
        string $identifier,
        bool $isSuccessful = true,
    ): Response {
        $args = [
            'user_uuids' => $userUuids,
            'identifier' => $identifier,
            'is_successful' => $isSuccessful,
        ];

        return $this->hub->request->post(self::ENDPOINT, $args);
    }
}

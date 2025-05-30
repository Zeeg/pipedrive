<?php

namespace Devio\Pipedrive;

interface PipedriveTokenStorage
{
    public function setToken(PipedriveToken $token): void;

    public function getToken(): ?PipedriveToken;
}

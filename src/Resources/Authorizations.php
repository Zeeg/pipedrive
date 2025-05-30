<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;

class Authorizations extends Resource
{
    /**
     * Enabled methods
     */
    protected array $enabled = ['authorize'];

    /**
     * Get authorizations for user without API key.
     *
     * @param  string $email    Email for the user
     * @param  string $password Password for the user
     * @return Response
     */
    public function authorize(string $email, string $password): Response
    {
        return $this->request->post('/', ['email' => $email, 'password' => $password]);
    }
}

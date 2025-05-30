<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;

trait ListsFollowers
{
    /**
     * List the followers of a resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function followers(int $id): Response
    {
        return $this->request->get(':id/followers', ['id' => $id]);
    }
}

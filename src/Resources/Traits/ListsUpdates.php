<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;
use Illuminate\Support\Arr;

trait ListsUpdates
{
    /**
     * Get the resource updates.
     *
     * @param int   $id
     * @param array $options
     *
     * @return Response
     */
    public function updates(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/flow', $options);
    }
}

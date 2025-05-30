<?php

namespace Devio\Pipedrive\Resources\Traits;

use Illuminate\Support\Arr;
use Devio\Pipedrive\Http\Response;

trait ListsActivities
{
    /**
     * List the resource activities.
     *
     * @param int   $id
     * @param array $options
     *
     * @return Response
     */
    public function activities(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/activities', $options);
    }
}

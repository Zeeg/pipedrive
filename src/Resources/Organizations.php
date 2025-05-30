<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Entity;
use Devio\Pipedrive\Resources\Traits\ListsAttachedFiles;
use Devio\Pipedrive\Resources\Traits\ListsDeals;
use Devio\Pipedrive\Resources\Traits\Searches;
use Illuminate\Support\Arr;

class Organizations extends Entity
{
    use ListsAttachedFiles;
    use ListsDeals;
    use Searches;

    /**
     * List the persons of a resource.
     *
     * @param int   $id      The resource id
     * @param array $options Extra parameters
     *
     * @return Response
     */
    public function persons(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/persons', $options);
    }
}

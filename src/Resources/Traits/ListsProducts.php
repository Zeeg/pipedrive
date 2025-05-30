<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;
use Illuminate\Support\Arr;

trait ListsProducts
{
    /**
     * Get the products attached to a resource.
     *
     * @param int   $id      The resource id
     * @param array $options Extra parameters
     *
     * @return Response
     */
    public function products(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/products', $options);
    }
}

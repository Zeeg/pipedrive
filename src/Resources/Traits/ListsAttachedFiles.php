<?php

namespace Devio\Pipedrive\Resources\Traits;

use Illuminate\Support\Arr;
use Devio\Pipedrive\Http\Response;

trait ListsAttachedFiles
{
    /**
     * Get the resource attached files.
     *
     * @param int   $id      The resource id
     * @param array $options Extra parameters
     * @return Response
     */
    public function attachedFiles(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/files', $options);
    }
}

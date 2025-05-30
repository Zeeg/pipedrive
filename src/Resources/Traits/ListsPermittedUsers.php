<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;

trait ListsPermittedUsers
{
    /**
     * Get the resource permitted users.
     *
     * @param int   $id           The resource id
     * @param mixed $access_level Access level value
     *
     * @return Response
     */
    public function permittedUsers(int $id, mixed $access_level = null): Response
    {
        return $this->request->get(':id/permittedUsers', compact('id', 'access_level'));
    }
}

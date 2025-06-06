<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;
use Devio\Pipedrive\Resources\Traits\FindsByName;
use Devio\Pipedrive\Resources\Traits\ListsActivities;
use Devio\Pipedrive\Resources\Traits\ListsFollowers;
use Devio\Pipedrive\Resources\Traits\ListsPermittedUsers;
use Devio\Pipedrive\Resources\Traits\ListsUpdates;

class Users extends Resource
{
    use FindsByName;
    use ListsActivities;
    use ListsFollowers;
    use ListsPermittedUsers;
    use ListsUpdates;

    /**
     * Disabled abstract methods.
     */
    protected array $disabled = ['delete', 'deleteBulk'];

    /**
     * Get the user permissions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function permissions(int $id): Response
    {
        return $this->request->get(':id/permissions', ['id' => $id]);
    }
}

<?php

namespace Devio\Pipedrive\Resources\Basics;

use Illuminate\Support\Arr;
use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Traits\FindsByName;
use Devio\Pipedrive\Resources\Traits\ListsActivities;
use Devio\Pipedrive\Resources\Traits\ListsUpdates;
use Devio\Pipedrive\Resources\Traits\ListsFollowers;
use Devio\Pipedrive\Resources\Traits\ListsAttachedFiles;
use Devio\Pipedrive\Resources\Traits\ListsPermittedUsers;

abstract class Entity extends Resource
{
    use FindsByName;
    use ListsActivities;
    use ListsAttachedFiles;
    use ListsFollowers;
    use ListsPermittedUsers;
    use ListsUpdates;

    /**
     * List the resource associated emails.
     *
     * @param int   $id
     * @param array $options
     *
     * @return Response
     */
    public function emails(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/mailMessages', $options);
    }

    /**
     * Add a follower to a resource.
     *
     * @param int $id
     * @param int $user_id
     *
     * @return Response
     */
    public function addFollower(int $id, int $user_id): Response
    {
        return $this->request->post(':id/followers', compact('id', 'user_id'));
    }

    /**
     * Delete a follower from a resource.
     *
     * @param int $id
     * @param int $follower_id
     *
     * @return Response
     */
    public function deleteFollower(int $id, int $follower_id): Response
    {
        return $this->request->delete(':id/followers/:follower_id', compact('id', 'follower_id'));
    }

    /**
     * Merge a resource with another.
     *
     * @param int $id
     * @param int $merge_with_id
     *
     * @return Response
     */
    public function merge(int $id, int $merge_with_id): Response
    {
        return $this->request->put(':id/merge', compact('id', 'merge_with_id'));
    }
}

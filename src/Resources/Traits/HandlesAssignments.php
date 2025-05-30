<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;
use Illuminate\Support\Arr;

trait HandlesAssignments
{
    /**
     * Get the resource assignments.
     *
     * @param int   $id
     * @param array $options
     *
     * @return Response
     */
    public function assignments(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/assignments', $options);
    }

    /**
     * Add a new assignment to the resource.
     *
     * @param int $id
     * @param int $user_id
     *
     * @return Response
     */
    public function addAssignment(int $id, int $user_id): Response
    {
        return $this->request->post(':id/assignments', ['id' => $id, 'user_id' => $user_id]);
    }

    /**
     * Delete an assignemt from the resource.
     *
     * @param int $id
     * @param int $user_id
     *
     * @return Response
     */
    public function deleteAssignment(int $id, int $user_id): Response
    {
        return $this->request->delete(':id/assignments', ['id' => $id, 'user_id' => $user_id]);
    }
}

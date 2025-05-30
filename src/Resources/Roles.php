<?php

namespace Devio\Pipedrive\Resources;

use Illuminate\Support\Arr;
use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;
use Devio\Pipedrive\Resources\Traits\HandlesAssignments;

class Roles extends Resource
{
    use HandlesAssignments;

    /**
     * Disabled abstract methods.
     *
     * @var array
     */
    protected array $disabled = ['deleteBulk'];

    /**
     * List the role subroles.
     *
     * @param int   $id
     * @param array $options
     *
     * @return Response
     */
    public function subRoles(int $id, array $options = []): Response
    {
        Arr::set($options, 'id', $id);

        return $this->request->get(':id/roles', $options);
    }

    /**
     * List the role settings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function settings(int $id): Response
    {
        return $this->request->get(':id/settings', compact('id'));
    }

    /**
     * Add or update a setting value.
     *
     * @param int    $id
     * @param string $setting_key
     * @param int    $value
     *
     * @return Response
     */
    public function setSetting(int $id, string $setting_key, int $value): Response
    {
        return $this->request->post(
            ':id/settings',
            compact('id', 'setting_key', 'value')
        );
    }
}

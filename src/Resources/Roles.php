<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;
use Devio\Pipedrive\Resources\Traits\HandlesAssignments;
use Illuminate\Support\Arr;

class Roles extends Resource
{
    use HandlesAssignments;

    /**
     * Disabled abstract methods.
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
        return $this->request->get(':id/settings', ['id' => $id]);
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
            ['id' => $id, 'setting_key' => $setting_key, 'value' => $value]
        );
    }
}

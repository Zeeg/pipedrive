<?php

namespace Devio\Pipedrive\Resources;

use Illuminate\Support\Arr;
use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;
use Devio\Pipedrive\Resources\Traits\Searches;

class Leads extends Resource
{
    use Searches;

    /**
     * Disabled abstract methods.
     *
     * @var array
     */
    protected array $disabled = ['deleteBulk'];

    protected bool $addPostedAsJson = true;

    /**
     * Get all labels.
     *
     * @return Response
     */
    public function labels(): Response
    {
        $this->request->setResource('leadLabels');

        return $this->request->get('');
    }

    /**
     * Add a label.
     *
     * @param array $values
     *
     * @return Response
     */
    public function addLabel(array $values = []): Response
    {
        $this->request->setResource('leadLabels');

        $values['json'] = true;

        return $this->request->post('', $values);
    }


    /**
     * Delete a label.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function deleteLabel(int $id): Response
    {
        $this->request->setResource('leadLabels');

        return $this->request->delete('' . $id);
    }

    /**
     * @param int   $id
     * @param array $values
     *
     * @return Response
     */
    public function updateLabel(int $id, array $values = []): Response
    {
        $this->request->setResource('leadLabels');

        return $this->request->put('/' . $id, $values);
    }

    /**
     * @param int   $id
     * @param array $values
     *
     * @return Response
     */
    public function update(int $id, array $values): Response
    {
        $values['json'] = true;

        Arr::set($values, 'id', $id);

        return $this->request->patch(':id', $values);
    }

    /**
     * Get all sources.
     *
     * @return Response
     */
    public function sources(): Response
    {
        $this->request->setResource('leadSources');

        return $this->request->get('');
    }
}

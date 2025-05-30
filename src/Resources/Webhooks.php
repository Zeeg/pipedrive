<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Exceptions\PipedriveException;
use Devio\Pipedrive\Http\Request;
use Devio\Pipedrive\Resources\Basics\Resource;

class Webhooks extends Resource
{
    /**
     * The API caller object.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * List of abstract methods available.
     *
     * @var array
     */
    protected array $enabled = ['add', 'delete', 'find'];

    /**
     * List of abstract methods disabled.
     *
     * @var array
     */
    protected array $disabled = [];

    /**
     * Get the entity details by ID.
     *
     * @param int $id Entity ID to find.
     *
     * @return Response
     * @throws PipedriveException
     */
    public function find(int $id): Response
    {
        throw new PipedriveException("The method find() is not available for the resource {$this->getName()}");
    }

    /**
     * Update an entity by ID.
     *
     * @param int   $id
     * @param array $values
     *
     * @return Response
     * @throws PipedriveException
     */
    public function update(int $id, array $values): Response
    {
        throw new PipedriveException("The method update() is not available for the resource {$this->getName()}");
    }
}

<?php

namespace Devio\Pipedrive\Resources\Basics;

use Devio\Pipedrive\Exceptions\PipedriveException;
use Devio\Pipedrive\Http\Request;
use Devio\Pipedrive\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Resource
{
    /**
     * The API caller object.
     */
    protected Request $request;

    /**
     * List of abstract methods available.
     */
    protected array $enabled = ['*'];

    /**
     * List of abstract methods disabled.
     */
    protected array $disabled = [];

    /**
     * Should requests to add POST as JSON?
     */
    protected bool $addPostedAsJson = false;

    /**
     * Endpoint constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $request->setResource($this->getName());

        $this->request = $request;
    }

    /**
     * Get all the entities.
     *
     * @param array $options Endpoint accepted options
     *
     * @return Response
     */
    public function all(array $options = []): Response
    {
        return $this->request->get('', $options);
    }

    /**
     * Get the entity details by ID.
     *
     * @param int $id Entity ID to find.
     *
     * @return Response
     */
    public function find(int $id): Response
    {
        return $this->request->get(':id', ['id' => $id]);
    }

    /**
     * Add a new entity.
     *
     * @param array $values
     * @return Response
     */
    public function add(array $values): Response
    {
        if ($this->addPostedAsJson) {
            $values['json'] = true;
        }

        return $this->request->post('', $values);
    }

    /**
     * Update an entity by ID.
     *
     * @param int   $id
     * @param array $values
     *
     * @return Response
     */
    public function update(int $id, array $values): Response
    {
        Arr::set($values, 'id', $id);

        return $this->request->put(':id', $values);
    }

    /**
     * Patch an entity by ID.
     *
     * @param int   $id
     * @param array $values
     *
     * @return Response
     */
    public function patch(int $id, array $values): Response
    {
        Arr::set($values, 'id', $id);

        return $this->request->patch(':id', $values);
    }

    /**
     * Delete an entity by ID.
     *
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        return $this->request->delete(':id', ['id' => $id]);
    }

    /**
     * Bulk deleting entities.
     *
     * @param array $ids
     * @return Response
     */
    public function deleteBulk(array $ids): Response
    {
        return $this->request->delete('', ['ids' => $ids]);
    }

    /**
     * Get the endpoint name based on the name class.
     *
     * @return string
     */
    public function getName(): string
    {
        $reflection = new ReflectionClass($this);

        return Str::camel($reflection->getShortName());
    }

    /**
     * Check if the method is enabled for use.
     *
     * @param string $method
     *
     * @return bool
     */
    public function isEnabled(string $method): bool
    {
        if ($this->isDisabled($method)) {
            return false;
        }

        // First we will make sure the method only belongs to this abstract class
        // as this does not have to interfere with methods described in child
        // classes. We can now check if it is found in the enabled property.
        if (! in_array($method, get_class_methods(self::class))) {
            return true;
        }

        return in_array($method, $this->enabled) || $this->enabled == ['*'];
    }

    /**
     * Check if the method is disabled for use.
     *
     * @param string $method
     *
     * @return bool
     */
    public function isDisabled(string $method): bool
    {
        return in_array($method, $this->disabled);
    }

    /**
     * Get enabled methods.
     *
     * @return array
     */
    public function getEnabled(): array
    {
        return $this->enabled;
    }

    /**
     * Set enabled methods.
     *
     * @param mixed $enabled
     */
    public function setEnabled(mixed $enabled): void
    {
        if (!is_array($enabled)) {
            $enabled = func_get_args();
        }

        $this->enabled = $enabled;
    }

    /**
     * Set disabled methods.
     *
     * @param mixed $disabled
     */
    public function setDisabled(mixed $disabled): void
    {
        if (!is_array($disabled)) {
            $disabled = func_get_args();
        }

        $this->disabled = $disabled;
    }

    /**
     * Magic method call.
     *
     * @param string $method
     * @param array  $args
     *
     * @return void
     * @throws PipedriveException
     */
    public function __call(string $method, array $args = [])
    {
        // As there are only a few resources that do not have the most common
        // methods described in this function, we can disable some methods
        // in the `disabled` property of the class throwing an exception.
        if (! $this->isEnabled($method)) {
            throw new PipedriveException(sprintf(
                'The method %s() is not available for the resource %s',
                $method,
                $this->getName()
            ));
        }
    }
}

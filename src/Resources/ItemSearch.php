<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;
use Illuminate\Support\Arr;

class ItemSearch extends Resource
{
    /**
     * Enabled abstract methods.
     */
    protected array $enabled = [];

    /**
     * Search.
     *
     * @param string $term
     * @param array  $options
     *
     * @return Response
     */
    public function search(string $term, array $options = []): Response
    {
        Arr::set($options, 'search', true);

        return $this->request->get('', $options);
    }

    /**
     * Search from a specific field.
     *
     * @param string $term
     * @param string $field_type
     * @param string $field_key
     * @param array  $options
     *
     * @return Response
     */
    public function searchFromField(
        string $term,
        string $field_type,
        string $field_key,
        array $options = []
    ): Response {
        $options = array_merge(['term' => $term, 'field_type' => $field_type, 'field_key' => $field_key], $options);

        return $this->request->get('field', $options);
    }
}

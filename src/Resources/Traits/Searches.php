<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;

trait Searches
{
    /**
     * @param string $term
     * @param array  $fields
     * @param array  $options
     *
     * @return Response|null
     */
    public function search(string $term, array $fields = [], array $options = []): Response|null
    {
        $options['term'] = $term;
        $options['fields'] = $fields;

        return $this->request->get('search', $options);
    }
}

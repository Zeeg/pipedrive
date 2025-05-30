<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;

trait FindsByName
{
    /**
     * Find an element by name.
     *
     * @param string $term
     * @param array  $options
     *
     * @return Response
     * @deprecated   Use the search method instead
	 */
    public function findByName(string $term, array $options = []): Response
    {
        $options['term'] = $term;

        return $this->request->get('find', $options);
    }
}

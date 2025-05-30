<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Resource;

class EmailMessages extends Resource
{
    /**
     * Enabled abstract methods.
     *
     * @var array
     */
    protected array $enabled = ['find', 'update', 'delete', 'deleteBulk'];
}

<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Resource;

class EmailMessages extends Resource
{
    /**
     * Enabled abstract methods.
     */
    protected array $enabled = ['find', 'update', 'delete', 'deleteBulk'];
}

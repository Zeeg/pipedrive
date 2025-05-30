<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Resource;

class GlobalMessages extends Resource
{
    /**
     * Enabled abstract methods.
     */
    protected array $enabled = ['all', 'delete'];
}

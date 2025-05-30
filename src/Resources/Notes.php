<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Resource;

class Notes extends Resource
{
    /**
     * Disabled abstract methods.
     */
    protected array $disabled = ['deleteBulk'];
}

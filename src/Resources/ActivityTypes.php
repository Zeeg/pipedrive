<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Resource;

class ActivityTypes extends Resource
{
    /**
     * Enabled abstract methods.
     */
    protected array $enabled = ['all', 'deleteBulk'];
}

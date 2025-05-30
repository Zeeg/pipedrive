<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Resource;

class Goals extends Resource
{
    /**
     * Disabled abstract methods.
     *
     * @var array
     */
    protected array $disabled = ['deleteBulk'];
}

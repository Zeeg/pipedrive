<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Resource;
use Devio\Pipedrive\Resources\Traits\FindsByName;
use Devio\Pipedrive\Resources\Traits\ListsAttachedFiles;
use Devio\Pipedrive\Resources\Traits\ListsDeals;
use Devio\Pipedrive\Resources\Traits\ListsPermittedUsers;
use Devio\Pipedrive\Resources\Traits\Searches;

class Products extends Resource
{
    use FindsByName;
    use ListsAttachedFiles;
    use ListsDeals;
    use ListsPermittedUsers;
    use Searches;

    /**
     * Disabled abstract methods.
     */
    protected array $disabled = ['deleteBulk'];
}

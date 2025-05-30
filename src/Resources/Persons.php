<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Resources\Basics\Entity;
use Devio\Pipedrive\Resources\Traits\ListsAttachedFiles;
use Devio\Pipedrive\Resources\Traits\ListsDeals;
use Devio\Pipedrive\Resources\Traits\ListsProducts;
use Devio\Pipedrive\Resources\Traits\Searches;

class Persons extends Entity
{
    use ListsAttachedFiles;
    use ListsDeals;
    use ListsProducts;
    use Searches;
}

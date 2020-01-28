<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Product\Elasticsearch;

use Shopsys\FrameworkBundle\Model\Elasticsearch\AbstractIndex;

class ProductIndex extends AbstractIndex
{
    public function __construct(ProductDataProvider $productDataProvider)
    {
        parent::__construct($productDataProvider);
    }

    public function getName(): string
    {
        return 'product';
    }
}

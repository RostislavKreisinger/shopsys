<?php

namespace Tests\FrameworkBundle\Unit\Model\Elasticsearch\__fixtures;

use Shopsys\FrameworkBundle\Model\Elasticsearch\AbstractIndex;

class CategoryIndex extends AbstractIndex
{
    public function __construct(CategoryDataProvider $dataProvider)
    {
        parent::__construct($dataProvider);
    }

    public function getName(): string
    {
        return 'category';
    }

}

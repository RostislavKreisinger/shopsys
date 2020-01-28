<?php

namespace Tests\FrameworkBundle\Unit\Model\Elasticsearch\__fixtures;

use Shopsys\FrameworkBundle\Model\Elasticsearch\DataProviderInterface;
use Symplify\BetterPhpDocParser\Exception\NotImplementedYetException;

class CategoryDataProvider implements DataProviderInterface
{
    public function getTotalCount(int $domainId): int
    {
        throw new NotImplementedYetException();
    }

    public function getDataForBatch(int $domainId, int $lastProcessedId, array $restrictToIds = []): array
    {
        throw new NotImplementedYetException();
    }

}

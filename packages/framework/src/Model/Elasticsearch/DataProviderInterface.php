<?php

namespace Shopsys\FrameworkBundle\Model\Elasticsearch;

interface DataProviderInterface
{
    public const BATCH_SIZE = 100;

    public function getTotalCount(int $domainId): int;

    public function getDataForBatch(int $domainId, int $lastProcessedId, array $restrictToIds = []): array;
}

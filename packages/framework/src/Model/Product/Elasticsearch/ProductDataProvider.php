<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Product\Elasticsearch;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Elasticsearch\DataProviderInterface;
use Shopsys\FrameworkBundle\Model\Product\Search\Export\ProductSearchExportWithFilterRepository;

class ProductDataProvider implements DataProviderInterface
{
    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Search\Export\ProductSearchExportWithFilterRepository
     */
    protected $productSearchExportWithFilterRepository;

    public function __construct(
        Domain $domain,
        ProductSearchExportWithFilterRepository $productSearchExportWithFilterRepository
    ) {
        $this->domain = $domain;
        $this->productSearchExportWithFilterRepository = $productSearchExportWithFilterRepository;
    }

    public function getTotalCount(int $domainId): int
    {
        return $this->productSearchExportWithFilterRepository->getProductTotalCountForDomain($domainId);
    }

    public function getDataForBatch(int $domainId, int $lastProcessedId, array $restrictToIds = []): array
    {
        return $this->productSearchExportWithFilterRepository->getProductsData(
            $domainId,
            $this->domain->getDomainConfigById($domainId)->getLocale(),
            $lastProcessedId,
            DataProviderInterface::BATCH_SIZE
        );
    }
}

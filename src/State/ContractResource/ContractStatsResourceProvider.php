<?php

namespace App\State\ContractResource;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ContractResource;
use App\Dto\ContractStatsDto;
use App\Repository\ContractRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ContractStatsResourceProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider,
        #[Autowire(service: ItemProvider::class)] private ProviderInterface $itemProvider,
        private ContractRepository $contractRepository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        $queryBuilder = $this->contractRepository->createQueryBuilder('c');
        $queryBuilder->join('c.user_contract', 'u');
        $queryNameGenerator = new QueryNameGenerator();
        ContractResource::handleLinks($queryBuilder, $uriVariables, $queryNameGenerator, $context);
        $queryBuilder->select('COUNT(c.id) as totalContracts')
            ->addSelect('SUM(CASE WHEN u.sex = false THEN 1 ELSE 0 END) as totalMen')
            ->addSelect('SUM(CASE WHEN u.sex = true THEN 1 ELSE 0 END) as totalWomen')
        ;
        $query = $queryBuilder->getQuery();
        $result = $query->setMaxResults(1)->getOneOrNullResult();

        $stats = new ContractStatsDto();
        $stats->totalContracts = $result['totalContracts'];
        $stats->totalMen = $result['totalMen'];
        $stats->totalWomen = $result['totalWomen'];

        return $stats;
    }
}

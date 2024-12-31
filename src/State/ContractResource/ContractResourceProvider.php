<?php

namespace App\State\ContractResource;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ContractResource;
use App\Entity\Contract;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function PHPUnit\Framework\throwException;

class ContractResourceProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider,
        #[Autowire(service: ItemProvider::class)] private ProviderInterface $itemProvider,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $contracts = $this->collectionProvider->provide($operation, $uriVariables, $context);

            $resources = [];

            foreach ($contracts as $contract) {
                $resources[] = $this->mapToResource($contract);
            }

            return $resources;
        }
        if($operation instanceof Post) {
            return null;
        }

        $contract = $this->itemProvider->provide($operation, $uriVariables, $context);

        if (!isset($contract)) {
            throw new NotFoundHttpException("Contract not found");
        }

        $contractResource = $this->mapToResource($contract);
        return $contractResource;
    }

    public function mapToResource(Contract $contract): ContractResource {
        $contractResource = new ContractResource();
        $contractResource->id = $contract->getId();
        $contractResource->user = $contract->getUserContract();
        $contractResource->start_date = $contract->getStartDate();
        $contractResource->end_date = $contract->getEndDate();
        $contractResource->job = $contract->getJob();
        $contractResource->wage = $contract->getWage();
        $contractResource->employee_group = $contract->getEmployeeGroup();
        $contractResource->contract = $contract;

        return $contractResource;
    }
}

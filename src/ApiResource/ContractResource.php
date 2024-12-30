<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Link;
use App\Entity\Contract;
use App\Entity\EmployeeGroup;
use App\Entity\User;
use App\State\ContractResource\ContractResourceProcessor;
use App\State\ContractResource\ContractResourceProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    shortName: 'Contracts',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            uriTemplate: '/contracts/{group_employee_id}',
            uriVariables: [
                'group_employee_id' => new Link(
                    fromProperty: 'contracts',
                    fromClass: EmployeeGroup::class,
                )
            ]
        ),
        new Delete(),
    ],
    normalizationContext: ['groups' => 'read'],
    provider: ContractResourceProvider::class,
    processor: ContractResourceProcessor::class,
    stateOptions: new Options(entityClass: Contract::class),
)]
class ContractResource
{
    #[Groups('read')]
    public int $id;
    #[Groups('read')]
    public ?\DateTimeImmutable $start_date;
    #[Groups('read')]
    public ?\DateTimeImmutable $end_date;

    #[Groups('read')]
    public ?User $user;
    #[Groups('read')]
    public ?string $job;
    #[Groups('read')]
    public ?int $wage;
    #[Groups('read')]
    public ?EmployeeGroup $employee_group;
    public Contract $contract;
}

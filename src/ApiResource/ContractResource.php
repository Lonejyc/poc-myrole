<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Link;
use App\Dto\ContractStatsDto;
use App\Entity\Contract;
use App\Entity\EmployeeGroup;
use App\Entity\Film;
use App\Entity\User;
use App\State\ContractResource\ContractResourceProcessor;
use App\State\ContractResource\ContractResourceProvider;
use App\State\ContractResource\ContractStatsResourceProvider;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    shortName: 'Contracts',
    operations: [
        new GetCollection(),
        new GetCollection(
            uriTemplate: '/movie/{movie_id}/contracts',
            uriVariables: [
                'movie_id' => new Link(
                    toProperty: 'film',
                    fromClass: Film::class
                )
            ]
        ),
        new Get(),
        new Get(
            uriTemplate: '/movie/{movie_id}/stats',
            uriVariables: [
                'movie_id' => new Link(
                    toProperty: 'film',
                    fromClass: Film::class
                )
            ],
            output: ContractStatsDto::class,
            provider: ContractStatsResourceProvider::class
        ),
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
    normalizationContext: ['groups' => ['read', 'stats']],
    provider: ContractResourceProvider::class,
    processor: ContractResourceProcessor::class,
    stateOptions: new Options(entityClass: Contract::class, handleLinks: [ContractResource::class, 'handleLinks']),
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

    #[Groups('read')]
    public ?Film $film;
    public Contract $contract;

    public static function handleLinks(QueryBuilder $queryBuilder, array $uriVariables, QueryNameGeneratorInterface $queryNameGenerator, array $context): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];

        if (empty($uriVariables['movie_id'])) {
            return;
        }

        // Join with employee_group and then with film
        $queryBuilder
            ->join(sprintf('%s.employee_group', $rootAlias), 'eg')
            ->join('eg.film', 'f')
            ->andWhere('f.id = :movie_id')
            ->setParameter('movie_id', $uriVariables['movie_id']);
    }
}

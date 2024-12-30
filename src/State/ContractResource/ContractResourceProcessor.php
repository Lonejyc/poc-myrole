<?php

namespace App\State\ContractResource;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\ContractResource;
use App\Entity\Contract;
use App\Repository\EmployeeGroupRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ContractResourceProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)] private ProcessorInterface $persistProcessor,
        #[Autowire(service: RemoveProcessor::class)]private ProcessorInterface $removeProcessor,
        private EmployeeGroupRepository $employeeGroupRepository
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // TODO: Implement process() method.


        if ($operation instanceof Post) {
            assert($data instanceof ContractResource);

            if ($data->user->getContracts() !== null) {
                $existingContracts = $data->user->getContracts();
                foreach ($existingContracts as $existingContract) {
                    if (
                        ($data->start_date >= $existingContract->getStartDate() && $data->start_date <= $existingContract->getEndDate()) ||
                        ($data->end_date >= $existingContract->getStartDate() && $data->end_date <= $existingContract->getEndDate()) ||
                        ($data->start_date <= $existingContract->getStartDate() && $data->end_date >= $existingContract->getEndDate())
                    ) {
                        throw new \Exception('The new contract period overlaps with an existing contract.');
                    }
                }
            }

            $contract = new Contract();
            $contract->setStartDate($data->start_date);
            $contract->setEndDate($data->end_date);
            $contract->setUserContract($data->user);
            $contract->setWage($data->wage);
            $contract->setJob($data->job);

            $employeeGroup = $this->employeeGroupRepository->find($uriVariables['group_employee_id']);
            $contract->setEmployeeGroup($employeeGroup);

            $this->persistProcessor->process($contract, $operation, $uriVariables, $context);
        }



        if ($operation instanceof Delete) {
            $this->removeProcessor->process($data->contract, $operation, $uriVariables, $context);
        }
    }
}

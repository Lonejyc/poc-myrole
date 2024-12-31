<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;

class ContractStatsDto
{
    #[Groups('stats')]
    public int $totalContracts;
    #[Groups('stats')]
    public int $totalMen;
    #[Groups('stats')]
    public int $totalWomen;
}

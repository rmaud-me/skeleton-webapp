<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;

interface LivePaginationInterface
{
    #[LiveAction]
    public function changePage(#[LiveArg] int $page): void;
}

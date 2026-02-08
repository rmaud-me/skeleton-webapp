<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * If you want to use the pagination in a live component, you need to implement LivePaginationInterface in the component that load the business data.
 */
#[AsTwigComponent(template: 'components/pagination.html.twig')]
class Pagination
{
    public SlidingPagination $pagination;

    public bool $isLivePagination = false;

    public function getFirstItem(): int
    {
        if (0 === $this->pagination->getTotalItemCount()) {
            return 0;
        }

        $currentPage  = $this->pagination->getCurrentPageNumber();
        $itemsPerPage = $this->pagination->getItemNumberPerPage();

        return ($currentPage - 1) * $itemsPerPage + 1;
    }

    public function getLastItem(): int
    {
        $currentPage  = $this->pagination->getCurrentPageNumber();
        $itemsPerPage = $this->pagination->getItemNumberPerPage();
        $totalItems   = $this->pagination->getTotalItemCount();

        $lastItem = $currentPage * $itemsPerPage;

        return min($lastItem, $totalItems);
    }

    public function getTotalItems(): int
    {
        return $this->pagination->getTotalItemCount();
    }

    public function getTotalPageCount(): int
    {
        return $this->pagination->getPageCount();
    }
}

<?php

namespace App\Twig\Components;

use App\Dto\SearchFilters;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class SearchModule
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public string $query = '';

    /** @var string[] */
    #[LiveProp(writable: true, url: true)]
    public array $tags = [];

    #[LiveProp(writable: ['category', 'minPrice'], url: true)]
    public SearchFilters $filters;

    #[PostMount]
    public function postMount(): void
    {
        $this->filters ??= new SearchFilters();
    }

    /** @return string[] */
    public function getAvailableTags(): array
    {
        return ['fiction', 'science', 'history', 'biography'];
    }

    /** @return array<string, string> */
    public function getAvailableCategories(): array
    {
        return [
            'all' => 'All categories',
            'books' => 'Books',
            'electronics' => 'Electronics',
        ];
    }
}

<?php

namespace App\Dto;

class SearchFilters
{
    public function __construct(
        public string $category = 'all',
        public int $minPrice = 0
    ) {
    }
}
